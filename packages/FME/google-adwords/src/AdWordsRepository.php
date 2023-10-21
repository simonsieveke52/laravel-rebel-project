<?php

namespace FME\GoogleAdwords;

use App\Order;
use Google\Auth\OAuth2;
use Google\AdsApi\Common\AdsBuilder;
use Google\AdsApi\Common\Configuration;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\v201809\rm\Member;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\v201809\cm\Operator;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201809\rm\AddressInfo;
use Google\AdsApi\AdWords\v201809\rm\CrmBasedUserList;
use Google\AdsApi\AdWords\v201809\rm\UserListOperation;
use Google\AdsApi\AdWords\v201809\rm\MutateMembersOperand;
use Google\AdsApi\AdWords\v201809\rm\AdwordsUserListService;
use Google\AdsApi\AdWords\v201809\rm\MutateMembersOperation;
use Google\AdsApi\AdWords\v201809\rm\CustomerMatchUploadKeyType;

class AdWordsRepository
{
	/**
	 * @var AdWordsSessionBuilder
	 */
	protected $config;
   
    /**
     * @var AdWordsSession
     */
    protected $session;

	/**
	 * @var AdWordsServices
	 */
	protected $adWordsServices;

    /**
     * @param AdWordsSessionBuilder $builder
     */
	public function __construct()
	{
        $oAuth2Credential = (new OAuth2TokenBuilder())
                ->withClientId(config('google-adwords.clientId'))
                ->withClientSecret(config('google-adwords.clientSecret'))
                ->withRefreshToken(config('google-adwords.refreshToken'))
                ->build();

        $this->session = (new AdWordsSessionBuilder())
            ->withDeveloperToken(config('google-adwords.developerToken'))
            ->withClientCustomerId(config('google-adwords.clientCustomerId'))
            ->withOAuth2Credential($oAuth2Credential)
            ->build();

	    $this->adWordsServices = new AdWordsServices();
	}

    /**
     * @param  mixed $uniqueName
     * @return mixed            
     */
    public function createUserList($uniqueName)
    {
        $userListService = $this->adWordsServices->get($this->session, AdwordsUserListService::class);

        // Create a CRM based user list.
        $userList = new CrmBasedUserList();
        $userList->setName($uniqueName);

        $userList->setDescription("A list of customers that originated from email addresses");

        // CRM-based user lists can use a membershipLifeSpan of 10000 to
        // indicate unlimited; otherwise normal values apply.
        // Sets the membership life span to 30 days.
        $userList->setMembershipLifeSpan(30);
        $userList->setUploadKeyType(CustomerMatchUploadKeyType::CONTACT_INFO);

        // Create a user list operation and add it to the list.
        $operation = new UserListOperation();
        $operation->setOperand($userList);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;

        // Create the user list on the server and print out some information.
        $userList = $userListService->mutate($operations)->getValue()[0];

        return $userList->getId();
    }

	/**
	 * @param  Order  $order
	 * @return MutateMembersReturnValue
	 */
	public function uploadUserList(Order $order, int $listId)
	{
        $userListService = $this->adWordsServices->get($this->session, AdwordsUserListService::class);

        $mutateMembersOperations = [];
        $mutateMembersOperation = new MutateMembersOperation();
        $operand = new MutateMembersOperand();
        $operand->setUserListId($listId);

        $member = new Member();
        $member->setHashedEmail($this->normalizeAndHash($order->email));
        $member->setHashedPhoneNumber($this->normalizeAndHash($order->phone));

        $addressInfo = new AddressInfo();
        $addressInfo->setHashedFirstName($this->normalizeAndHash($order->first_name));
        $addressInfo->setHashedLastName($this->normalizeAndHash($order->last_name));
        $addressInfo->setCountryCode('US');
        $addressInfo->setZipCode($order->shippingAddress->zipcode);

        $member->setAddressInfo($addressInfo);

        $members[] = $member;

        // Add members to the operand and add the operation to the list.
        $operand->setMembersList($members);
        $mutateMembersOperation->setOperand($operand);
        $mutateMembersOperation->setOperator(Operator::ADD);
        $mutateMembersOperations[] = $mutateMembersOperation;

        // Add members to the user list based on email addresses.
        return $userListService->mutateMembers($mutateMembersOperations);
	}	

    /**
     * @param  mixed $value
     * @return mixed       
     */
    public function normalizeAndHash($value)
    {
        return hash('sha256', strtolower(trim($value)));
    }
}