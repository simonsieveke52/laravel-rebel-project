<?php

namespace App\Http\Controllers\Voyager;

use App\Order;
use App\Discount;
use App\OrderStatus;
use App\MarketingEmail;
use App\TrackingNumber;
use Illuminate\Support\Str;
use App\Mail\RefundMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Events\OrderCreateEvent;
use TCG\Voyager\Facades\Voyager;
use App\Exports\OutputOrdersExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\TextNotification;
use App\Notifications\OrderNotification;
use App\Repositories\Locate\LocateApiClient;
use Illuminate\Support\Facades\Notification;
use App\Repositories\Locate\LocateRepository;
use TCG\Voyager\Database\Schema\SchemaManager;
use App\Repositories\Payment\AuthorizeNetRepository;

class OrdersController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    /**
     * @param  Request $request
     * @return View
     */
    public function index(Request $request)
    {
        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', 'orders')->first();

        // Check permission
        $this->authorize('browse', app(Order::class));

        $search = (object) [
            'value' => $request->get('s'),
            'key' => $request->get('key'),
            'filter' => $request->get('filter')
        ];

        // get table columns
        $searchable = array_keys(SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray());

        $orderBy = $request->get('order_by');
        $sortOrder = $request->get('sort_order');

        $query = Order::with(['addresses', 'orderStatus', 'TrackingNumbers'])->site();

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'browse');

        if ($search->value && $search->key && $search->filter) {
            $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
            $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';
            $query->where($search->key, $search_filter, $search_value);
        }

        if ($request->has('created_at') && $request->get('created_at') !== null) {
            $date = Carbon::createFromFormat('m/d/Y', $request->get('created_at'));
            $query->whereDate('created_at', '>=', $date);
        }

        $defaulOrderStatus = $request->get('order_status', session('order_status', null));
        session(['order_status' => $defaulOrderStatus]);

        if ($defaulOrderStatus !== null && $defaulOrderStatus !== 'all') {
            $query = $query->where('order_status_id', $defaulOrderStatus);
        }

        switch ($request->get('order_type', 'completed')) {

            case 'abandonments':
                $query = $query->notConfirmed();
                break;

            case 'completed':
                $query = $query->confirmed();
                break;

            case 'pending':
                $query = $query->pending();
                break;

            case 'fba':
                $query = $query->whereHas('products', function ($query) {
                    $query->where('is_fba', true);
                });
                break;
        }

        if ($orderBy && in_array($orderBy, $dataType->fields())) {
            $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'DESC';
            $dataTypeContent = call_user_func([
                $query->orderBy($orderBy, $querySortOrder),
                'paginate',
            ]);
        } else {
            $dataTypeContent = call_user_func([$query->orderBy('id', 'desc'), 'paginate']);
        }

        $orderStatues = OrderStatus::orderBy('display_order', 'asc')
            ->remember(config('global-variables.cache_life_time'))
            ->get();

        $actionButtonsShowed = false;

        $paginationFilter = $this->getPaginationFilter($search, $orderBy, $sortOrder);

        return Voyager::view('voyager::orders.browse', compact(
            'search',
            'paginationFilter',
            'orderBy',
            'actionButtonsShowed',
            'dataType',
            'sortOrder',
            'searchable',
            'orderStatues',
            'dataTypeContent',
        ));
    }

    /**
     * @param  Request $request
     * @return View
     */
    public function amazonIndex(Request $request)
    {
        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', 'orders')->first();

        // Check permission
        $this->authorize('browse', app(Order::class));

        $search = (object) [
            'value' => $request->get('s'),
            'key' => $request->get('key'),
            'filter' => $request->get('filter')
        ];

        // get table columns
        $searchable = array_keys(SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray());

        $orderBy = $request->get('order_by');
        $sortOrder = $request->get('sort_order');

        $query = Order::with(['addresses', 'orderStatus', 'TrackingNumbers'])
            ->amazon();

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'browse');

        if ($search->value && $search->key && $search->filter) {
            $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
            $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';
            $query->where($search->key, $search_filter, $search_value);
        }

        if ($request->has('created_at') && $request->get('created_at') !== null) {
            $date = Carbon::createFromFormat('m/d/Y', $request->get('created_at'));
            $query->whereDate('created_at', '>=', $date);
        }

        $defaulOrderStatus = $request->get('order_status', session('amazon_order_status', null));
        session(['amazon_order_status' => $defaulOrderStatus]);

        if ($defaulOrderStatus !== null && $defaulOrderStatus !== 'all') {
            $query = $query->where('order_status_id', $defaulOrderStatus);
        }

        switch ($request->get('order_type', 'completed')) {

            case 'abandonments':
                $query = $query->notConfirmed();
                break;

            case 'completed':
                $query = $query->confirmed();
                break;

            case 'pending':
                $query = $query->pending();
                break;
        }

        if ($orderBy && in_array($orderBy, $dataType->fields())) {
            $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'DESC';
            $dataTypeContent = call_user_func([
                $query->orderBy($orderBy, $querySortOrder),
                'paginate',
            ]);
        } else {
            $dataTypeContent = call_user_func([$query->orderBy('id', 'desc'), 'paginate']);
        }

        $orderStatues = OrderStatus::orderBy('display_order', 'asc')
            ->remember(config('global-variables.cache_life_time'))
            ->get();

        $actionButtonsShowed = false;

        $paginationFilter = $this->getPaginationFilter($search, $orderBy, $sortOrder);

        return Voyager::view('voyager::orders.amazon-browse', compact(
            'search',
            'paginationFilter',
            'orderBy',
            'actionButtonsShowed',
            'dataType',
            'sortOrder',
            'searchable',
            'orderStatues',
            'dataTypeContent',
        ));
    }

    /**
     * @param  Request $request
     * @return View
     */
    public function googleIndex(Request $request)
    {
        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', 'orders')->first();

        // Check permission
        $this->authorize('browse', app(Order::class));

        $search = (object) [
            'value' => $request->get('s'),
            'key' => $request->get('key'),
            'filter' => $request->get('filter')
        ];

        // get table columns
        $searchable = array_keys(SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray());

        $orderBy = $request->get('order_by');
        $sortOrder = $request->get('sort_order');

        $query = Order::with(['addresses', 'orderStatus', 'TrackingNumbers'])->google();

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'browse');

        if ($search->value && $search->key && $search->filter) {
            $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
            $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';
            $query->where($search->key, $search_filter, $search_value);
        }

        if ($request->has('created_at') && $request->get('created_at') !== null) {
            $date = Carbon::createFromFormat('m/d/Y', $request->get('created_at'));
            $query->whereDate('created_at', '>=', $date);
        }

        $defaulOrderStatus = $request->get('order_status', session('google_order_status', null));
        session(['google_order_status' => $defaulOrderStatus]);

        if ($defaulOrderStatus !== null && $defaulOrderStatus !== 'all') {
            $query = $query->where('order_status_id', $defaulOrderStatus);
        }

        switch ($request->get('order_type', 'completed')) {

            case 'abandonments':
                $query = $query->notConfirmed();
                break;

            case 'completed':
                $query = $query->confirmed();
                break;

            case 'pending':
                $query = $query->pending();
                break;
        }

        if ($orderBy && in_array($orderBy, $dataType->fields())) {
            $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'DESC';
            $dataTypeContent = call_user_func([
                $query->orderBy($orderBy, $querySortOrder),
                'paginate',
            ]);
        } else {
            $dataTypeContent = call_user_func([$query->orderBy('id', 'desc'), 'paginate']);
        }

        $orderStatues = OrderStatus::orderBy('display_order', 'asc')
            ->remember(config('global-variables.cache_life_time'))
            ->get();

        $actionButtonsShowed = false;

        $paginationFilter = $this->getPaginationFilter($search, $orderBy, $sortOrder);

        return Voyager::view('voyager::orders.google-browse', compact(
            'search',
            'paginationFilter',
            'orderBy',
            'actionButtonsShowed',
            'dataType',
            'sortOrder',
            'searchable',
            'orderStatues',
            'dataTypeContent',
        ));
    }

    /**
     * Update order status
     *
     * @param  Order   $order
     * @param  Request $request
     * @return
     */
    public function updateOrderStatus(UpdateOrderStatusRequest $request)
    {
        $order = Order::findOrFail($request->id);

        $orderStatus = OrderStatus::findOrFail($request->orderStatus);

        $order->orderStatus()->attach($orderStatus, ['note' => $request->note]);
        $order->save();

        return redirect()
            ->route("voyager.orders.index")
            ->with([
                'message'    => "Order status updated successfully",
                'alert-type' => 'success',
            ]);
    }

    /**
     * @return void
     */
    public function refund(Request $request, Order $order)
    {
        try {

            (new AuthorizeNetRepository)->refund($order);

            Mail::send(new RefundMailable($order));

            return redirect()
                ->route("voyager.orders.index")
                ->with([
                    'message'    => "Order refunded successfully",
                    'alert-type' => 'success',
                ]);
        } catch (\Exception $e) {

            $order->transaction_response = $e->getMessage();
            $order->save();

            return redirect()
                ->route("voyager.orders.index")
                ->with([
                    'message'    => $e->getMessage(),
                    'alert-type' => 'error',
                ]);
        }
    }

    /**
     * @return void
     */
    public function approveOrDecline(Request $request, Order $order, string $action)
    {
        try {

            (new AuthorizeNetRepository)->approveOrDecline($order, $action);

            if ($action === 'approve') {
                try {
                    (new LocateRepository(new LocateApiClient()))
                        ->reportSaleOrder($order);
                } catch (\Exception $exception) {
                    logger($exception->getMessage());
                }
            }

            return redirect()
                ->route("voyager.orders.index")
                ->with([
                    'message'    => "Order updated successfully",
                    'alert-type' => 'success',
                ]);

        } catch (\Exception $e) {

            $order->transaction_response = $e->getMessage();
            $order->save();

            return redirect()
                ->route("voyager.orders.index")
                ->with([
                    'message'    => $e->getMessage(),
                    'alert-type' => 'error',
                ]);
        }
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function notify(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id'
        ]);

        try {

            $order = Order::findOrFail($request->input('id'));

            try {
                $order->notify(new OrderNotification($order));
                return response()->json([true]);
            } catch (\Exception $exception) {
                return response()->json($exception->getMessage());
            }

        } catch (\Exception $e) {
            return response()->json(false);
        }
    }

    /**
     * Mark order as "Reviewed"
     *
     * @throws  \Exception
     * @return response
     */
    public function review(Order $order)
    {
        $order->markAsReviewed();

        return response()->json([
            'message' => 'Order marked as reviewed'
        ]);
    }

    /**
     * @return string
     */
	public function export($orders = null)
	{
        set_time_limit(0);
        ini_set('memory_limit', -1);

		$fh = fopen('php://output', 'w');

        ob_start();

        fputcsv($fh, [
            'Order Id',
            'Amazon Order Id',
            'Product',
            'Quantity',
            'Name',
            'Email',
            'Customer_id',
            'Created_at',
            'Source',
            'Gclid',
            'Total product cost',
            'Shipping cost',
            'Total paid',
            'Order status',
            'Transaction_id',
            'Cc_number',
            'Card_expiry_year',
            'Card_expiry_month',
            'Cvc',
            'Shipping option',

            'Billing_address',
            'Billing_address2',
            'Billing_address_state',
            'Billing_address_city',
            'Billing_address_zip',

            'Shipping_address1',
            'Shipping_address2',
            'Shipping_address_state',
            'Shipping_address_city',
            'Shipping_address_zip',

            'Processed_at',
            'Custom Notes',
            'Availability'
        ]);

        if (is_null($orders)) {
            $orders = Order::with([
                'products',
                'shippings',
                'addresses',
                'orderStatus'
            ])->whereDate('created_at', '>', now()->subDays(31));
        }

        switch (request()->input('type')) {
            case 'amazon':
                $orders = $orders->amazon();
                break;

            case 'google':
                $orders = $orders->google();
                break;

            default:
                $orders = $orders->confirmed()->site();
                break;
        }

        $orders->chunk(30, function($orders) use ($fh) {

            foreach ($orders as $order) {

                if ($order->products->isEmpty()) {
                    continue;
                }

            	foreach ($order->products as $index => $product) {
                    try {
                		fputcsv($fh, [
        	            	$order->id,
                            $order->amazon_order_id,
        	            	$product->id,
        	            	$product->pivot->quantity,
        	            	$order->name,
        	            	$order->email,
        	            	$order->customer_id,
        	            	$order->created_at,
        	            	$order->order_source,
        	            	$order->gclid,
        	            	$order->total,
        	            	$order->shipping_cost,
        	            	$order->total_paid,
        	            	$order->orderStatus->name,
        	            	$order->transaction_id,
        	            	$order->lastCCDigits,
        	            	$order->card_expiry_year,
        	            	$order->card_expiry_month,
        	            	$order->cvc,
        	            	$order->shippings->pluck('name')->implode(' & '),

        	            	is_object($order->billingAddress) && isset($order->billingAddress->address1) ? $order->billingAddress->address1 : '',
                            is_object($order->billingAddress) && isset($order->billingAddress->address2) ? $order->billingAddress->address2 : '',
                            is_object($order->billingAddress) && is_object($order->billingAddress->state) && isset($order->billingAddress->state) ? $order->billingAddress->state->name : '',
                            is_object($order->billingAddress) && isset($order->billingAddress->city) ? $order->billingAddress->city : '',
                            is_object($order->billingAddress) && isset($order->billingAddress->zipcode) ? $order->billingAddress->zipcode : '',

                            is_object($order->shippingAddress) && isset($order->shippingAddress->address1) ? $order->shippingAddress->address1 : '',
                            is_object($order->shippingAddress) && isset($order->shippingAddress->address2) ? $order->shippingAddress->address2 : '',
                            is_object($order->shippingAddress) && is_object($order->shippingAddress->state) && isset($order->shippingAddress->state) ? $order->shippingAddress->state->name : '',
                            is_object($order->shippingAddress) && isset($order->shippingAddress->city) ? $order->shippingAddress->city : '',
                            is_object($order->shippingAddress) && isset($order->shippingAddress->zipcode) ? $order->shippingAddress->zipcode : '',

                            trim($product->pivot->user_outputted_at) === '' ? now()->format('Y-m-d') : $product->pivot->user_outputted_at->format('Y-m-d'),
                            $order->custom_notes,
                            $product->quantity > 0 ? 'in stock' : 'out of stock',

        	            ]);
                    } catch (\Exception $e) {
                    }
            	}
            }
        });

        $string = ob_get_clean();

        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Content-type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename=orders-'. strtotime('now') .'.csv',
                'Expires'             => '0',
                'Pragma'              => 'public',
        ];

        return response()->stream(function() use ($string) {
            echo $string;
        }, 200, $headers);
    }

    public function exportByRange(Request $request)
    {
            $orders = Order::with([
                'products',
                'shippings',
                'addresses',
                'orderStatus'
            ])->whereBetween('created_at', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)
            ]);

            return $this->export($orders);
    }

    /**
     * @param  Request $request
     * @return response
     */
    public function updateNotes(Request $request)
    {
        return view('vendor.voyager.orders.update-notes');
    }

    /**
     * @param  Request $request
     * @return response
     */
    public function storeUpdateNotes(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $content = file_get_contents($request->files->get('file'));

        $csv = \League\Csv\Reader::createFromString($content);

        foreach ($csv as $row) {

            if ((int) $row[0] === 0) {
                continue;
            }

            try {
                $order = Order::findOrFail($row[0]);
                $order->custom_notes = trim($row[1]);
                $order->save();
            } catch (\Exception $e) {

            }
        }

        return back()->with([
                    'message'    => "Orders updated successfully",
                    'alert-type' => 'success',
                ]);
    }

    /**
     * Append those params to pagination
     *
     * @param  mixed $search
     * @param  mixed $orderBy
     * @param  mixed $sortOrder
     * @return array
     */
    protected function getPaginationFilter($search = null, $orderBy = null, $sortOrder = null)
    {
        $paginationFilter = [];

        if ($search->value) {
            $paginationFilter['s'] = $search->value;
        }

        if ($search->filter) {
            $paginationFilter['filter'] = $search->filter;
        }

        if ($search->key) {
            $paginationFilter['key'] = $search->key;
        }

        if ($orderBy) {
            $paginationFilter['order_by'] = $orderBy;
        }

        if ($sortOrder) {
            $paginationFilter['sort_order'] = $sortOrder;
        }

        if (request()->get('created_at', '') !== '') {
            $paginationFilter['created_at'] = request()->created_at;
        }

        if (request()->get('order_status', '') !== '') {
            $paginationFilter['order_status'] = request()->order_status ?? '';
        }

        if (request()->get('order_type', '') !== '') {
            $paginationFilter['order_type'] = request()->order_type ?? '';
        };

        return $paginationFilter;
    }
}
