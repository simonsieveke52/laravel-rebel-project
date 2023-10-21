<?php 

namespace App\Repositories\Payment;

use App\Order;
use App\Quote;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use net\authorize\api\contract\v1\OrderType;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\contract\v1\PaymentType;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\controller as AnetController;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\contract\v1\GetTransactionListRequest;
use net\authorize\api\contract\v1\GetSettledBatchListRequest;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use App\Repository\Payment\Contracts\PaymentRepositoryContract;
use net\authorize\api\controller\GetSettledBatchListController;

/**
 * Auth.net payments repository
 * 
 */
class AuthorizeNetRepository
{
	/**
	 * @var AnetAPI\MerchantAuthenticationType
	 */
	protected $merchantAuthentication;

	/**
	 * @var TransactionResponse
	 */
	protected $transactionResponse;

	/**
	 * Common setup for API credentials
	 */
	public function __construct()
	{
		$login = config('app.env') === 'local' 
			? config('authorize.local.login') 
			: config('authorize.production.login');

		$key = config('app.env') === 'local' 
			? config('authorize.local.key') 
			: config('authorize.production.key');

		if (! defined('AUTHORIZENET_LOG_FILE')) {
			define('AUTHORIZENET_LOG_FILE', storage_path('logs/authorize.log'));
		}

		$this->merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
		$this->merchantAuthentication->setName($login);
		$this->merchantAuthentication->setTransactionKey($key);
		$this->transactionResponse = null;
	}

	/**
	 * @param Order         $order      
	 * @param String|string $addressType
	 */
	protected function setAddress(Order $order, String $addressType = 'billing')
	{
		$address = $order->addresses->firstWhere('type', $addressType) ?? $order->addresses->firstWhere('type', 'billing');

        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($order->first_name);
        $customerAddress->setLastName($order->last_name);

		if (! is_null($address)) {
	        $customerAddress->setAddress($address->address_1 . ' ' . $address->address_2);
	        $customerAddress->setCity($address->city);
	        $customerAddress->setState($address->state->abv);
	        $customerAddress->setZip($address->zipcode);
	        $customerAddress->setCountry($address->country->iso3);
		}
    
		return $customerAddress;
	}

    /**
     * Process order payment
     * 
     * @param  Order  $order
     * @return mixed
     * @throws Exception
     */
    public function process($order)
    {
		// Create the payment data for a credit card
		$creditCard = new AnetAPI\CreditCardType();

		try {
			$ccNumber = decrypt($order->cc_number);
		} catch (\Exception $e) {
			$ccNumber = $order->cc_number;
		}

		$order->cc_number = encrypt(substr($ccNumber, -4));
		$order->save();

		$creditCard->setCardNumber($ccNumber);
		$creditCard->setExpirationDate('20'. $order->cc_expiration_year . '-' . $order->cc_expiration_month);

		$payment = new AnetAPI\PaymentType();
		$payment->setCreditCard($creditCard);

		$shipping = new AnetAPI\ExtendedAmountType();

		$shippingName = $order->shippings->count() >= 2 
			? "Regular and Frozen shipping"
			: ($order->shippings->first()->name ?? 'Regular');

		$shipping->setName($shippingName);
		$shipping->setAmount(number_format(floatval($order->shipping_cost), 2, '.', ''));

		$tax = new AnetAPI\ExtendedAmountType();
        $tax->setAmount(number_format(floatval($order->tax), 2, '.', ''));
		
        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setEmail($order->email);
		
        // Create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
		
        $transactionRequestType->setBillTo($this->setAddress($order));
        $transactionRequestType->setShipTo($this->setAddress($order, 'shipping'));
		
		$transactionRequestType->setCustomer($customerData);

		$orderRequestType = new AnetAPI\OrderType();     
		$orderRequestType->setInvoiceNumber($order->id);
		$transactionRequestType->setOrder($orderRequestType);
		
		// Create a transaction
		$transactionRequestType->setAmount(number_format(floatval($order->total), 2, '.', ''));
		$transactionRequestType->setPayment($payment);
        $transactionRequestType->setShipping($shipping);
        $transactionRequestType->setTax($tax);

		$authRequest = new AnetAPI\CreateTransactionRequest();
		$authRequest->setMerchantAuthentication($this->merchantAuthentication);
		$authRequest->setRefId($order->id);
		$authRequest->setTransactionRequest($transactionRequestType);

		$controller = new AnetController\CreateTransactionController($authRequest);

		// Set ANetEnvironment to even sandbox or production mode 
		$response = $controller->executeWithApiResponse(
			config('app.env') !== 'local' ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
		);

		if( $response === null ) {
			throw new \Exception("Something went wrong. ", 1);
		}

		$transactionResponse = $response->getTransactionResponse();

		if ($transactionResponse !== null) {
			$order->transaction_response = json_encode($transactionResponse);
			$order->save();
		}

		if( 
			($transactionResponse != null && $transactionResponse != 'null') && (in_array($transactionResponse->getResponseCode(), ['1', '4']))
		){
			
			return $this->transactionResponse = tap($transactionResponse, function($transactionResponse) use ($order) {
				try {

					$order->transaction_id = $transactionResponse->getTransId();
					$order->transaction_response_code = $transactionResponse->getResponseCode();
					$order->save();

					if ($transactionResponse->getResponseCode() == '4') {
						$order->markAsNeedsReview();
					}

					if (isset($order->quote) && $order->quote instanceof Quote) {
						$order->quote->status = 'Completed';
						$order->quote->save();
					}

				} catch (\Exception $e) {
					logger($e->getMessage());
				}
			});
		}

		try {
			$error = ! empty($response->getMessages()->getMessage())
				? $response->getMessages()->getMessage()[0]->getCode()
				: 'RE000';
			$order->save();
		} catch (\Exception $e) {
			logger($e->getMessage());
		}

		throw new \Exception("Error. " . ($error ?? ''));	
	}

	/**
	 * @param  Order  $order
	 * @return void
	 */
	public function refund(Order $order)
	{  
		$status = $this->getTransactionDetails($order)
			->getTransactionStatus();

		switch ($status) {

			case 'capturedPendingSettlement':
			    $response = $this->checkResponse(
			    	$this->makeTransaction($order, 'voidTransaction'), $order
			    );
			    break;

			case 'settledSuccessfully':
				$response = $this->checkResponse(
			    	$this->makeTransaction($order, 'refundTransaction'), $order
			    );
			    break;

			default: 
				throw new \Exception("Can't process refund for this type of transaction: ({$status})");
		}

		$order->markAsRefunded();

	    return isset($response) ? $response : null;
	}

	/**
	 * @param  Order  $order
	 * @return void
	 */
	public function approveOrDecline(Order $order, $action = 'approve')
	{  
		if (! in_array($action, ['approve', 'decline'])) {
			throw new \Exception("Error Processing Request");
		}

		// create a transaction
		$transactionRequestType = new AnetAPI\HeldTransactionRequestType();
		$transactionRequestType->setAction($action);
		$transactionRequestType->setRefTransId($order->transaction_id);

		$request = new AnetAPI\UpdateHeldTransactionRequest();
		$request->setMerchantAuthentication($this->merchantAuthentication);
		$request->setHeldTransactionRequest( $transactionRequestType);
		$controller = new AnetController\UpdateHeldTransactionController($request);

      	$response = $controller->executeWithApiResponse(
			config('app.env') !== 'local' ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
		);


		try {
			$response = $this->checkResponse($response ?? null, $order);
		} catch (\Exception $exception) {
			
	      	$transactionResponse = $response->getTransactionResponse();

	      	if (count($transactionResponse->getErrors()) === 0) {
	      		throw $exception;
	      	}

	      	$found = false;

	      	foreach ($transactionResponse->getErrors() as $error) {

	      		if ((int) $error->getErrorCode() != 16) {
	      			continue;
	      		}

      			$found = true;
      			break;
	      	}

	      	if (! $found) {
	      		throw $exception;
	      	}
		}

		$action === 'approve' 
			? $order->markAsReviewed() 
			: $order->markAsCanceled();

	    return isset($response) ? $response : null;
	}

	/**
	 * @return Response
	 */
	public function makeTransaction(Order $order, $transactionType = "voidTransaction")
	{
		// Create the payment data for a credit card
		$creditCard = new AnetAPI\CreditCardType();

		try {
			$ccNumber = decrypt($order->cc_number);
		} catch (\Exception $e) {
			$ccNumber = $order->cc_number;
		}

		$creditCard->setCardNumber($ccNumber);
		$creditCard->setExpirationDate('20'. $order->cc_expiration_year . '-' . $order->cc_expiration_month);
	    $paymentOne = new AnetAPI\PaymentType();
	    $paymentOne->setCreditCard($creditCard);

		//create a transaction
	    $transactionRequest = new AnetAPI\TransactionRequestType();
	    $transactionRequest->setBillTo($this->setAddress($order));
        $transactionRequest->setShipTo($this->setAddress($order, 'shipping'));
	    $transactionRequest->setTransactionType($transactionType);
	    $transactionRequest->setAmount(number_format($order->total, 2, '.', ''));
	    $transactionRequest->setPayment($paymentOne);
	    $transactionRequest->setRefTransId($order->transaction_id);

	    $request = new AnetAPI\CreateTransactionRequest();
	    $request->setMerchantAuthentication($this->merchantAuthentication);
	    $request->setRefId($order->id);
	    $request->setTransactionRequest($transactionRequest);

	    $controller = new AnetController\CreateTransactionController($request);
	    
	    return $controller->executeWithApiResponse(
			config('app.env') !== 'local' ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
		);	
	}

	/**
	 * @param  $response
	 * @return void
	 */
	public function checkResponse($response = null, Order $order)
	{
		if( $response === null ) {
			throw new \Exception("Something went wrong. ", 1);
		}

		$transactionResponse = $response->getTransactionResponse();

		if( 
			($transactionResponse != null && $transactionResponse != 'null' ) && ($transactionResponse->getResponseCode() == "1")
		){
			return $this->transactionResponse = $transactionResponse;
		}

		if ($transactionResponse !== null) {
			$order->transaction_response = json_encode($transactionResponse);
			$order->save();
		}

		try {
			$error = ! empty($response->getMessages()->getMessage())
				? $response->getMessages()->getMessage()[0]->getCode()
				: 'RE000';
			$order->save();
		} catch (\Exception $e) {
			
		}

		throw new \Exception("Error. " . ($error ?? ''));	
	}

	/**
	 * 
	 * @param Order $order
	 * @return void
	 */
	public function getTransactionDetails(Order $order)
	{
	    $request = new AnetAPI\GetTransactionDetailsRequest();
	    $request->setMerchantAuthentication($this->merchantAuthentication);
	    $request->setTransId($order->transaction_id);

	    $controller = new AnetController\GetTransactionDetailsController($request);

	    $response = $controller->executeWithApiResponse(
			config('app.env') !== 'local' ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
		);

	    if (! is_null($response) && (strtolower($response->getMessages()->getResultCode()) === 'ok')) {
	    	return $response->getTransaction();
	    }

	    throw new \Exception($response->getMessages()->getMessage());
  	}

  	/**
  	 * @param  DateTime $firstSettlementDate
  	 * @param  DateTime $lastSettlementDate 
  	 * @return array               
  	 */
  	public function getSettledBatchList($firstSettlementDate, $lastSettlementDate)
  	{
  		$request = new AnetAPI\GetSettledBatchListRequest();
	    $request->setMerchantAuthentication($this->merchantAuthentication);
	    $request->setIncludeStatistics(false);

        $request->setFirstSettlementDate($firstSettlementDate);
	    $request->setLastSettlementDate($lastSettlementDate);

        $controller = new AnetController\GetSettledBatchListController($request);

        try {

	        $response = $controller->executeWithApiResponse(
				config('app.env') !== 'local' ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
			);

		    if (($response === null) || ($response->getMessages()->getResultCode() != "Ok")) {
		    	return [];
		    }
        	
        	return Arr::wrap($response->getBatchList());

        } catch (\Exception $e) {
        	return [];
        }
  	}

  	/**
  	 * @param  string $batchId
  	 * @return array         
  	 */
  	public function getTransactionList($batchId)
  	{
	    $request = new AnetAPI\GetTransactionListRequest();
	    $request->setMerchantAuthentication($this->merchantAuthentication);
	    $request->setBatchId($batchId);
        $controller = new AnetController\GetTransactionListController($request);

        try {

	        $response = $controller->executeWithApiResponse(
				config('app.env') !== 'local' ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX
			);

		    if (($response === null) || ($response->getMessages()->getResultCode() != "Ok")) {
		    	return [];
		    }
        	
        	return Arr::wrap($response->getTransactions());

        } catch (\Exception $e) {
        	return [];
        }
  	}

	/**
	 * Confirm transaction and mark order as confirmed
	 * 
	 * @param  Order  $order
	 * @return mixed
	 */
	public function confirm(Order $order)
	{
        $order->confirmed = true;
        $order->confirmed_at = date('Y-m-d H:i:s');
        $order->save();

        return true;
	}
}