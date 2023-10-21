<?php

namespace Tests\Feature;

use App\Order;
use Tests\TestCase;
use FME\GoogleAdwords\AdWordsRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Google\AdsApi\AdWords\v201809\rm\MutateMembersReturnValue;

class AdWordsRepositoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_a_user_list()
    {
        $repository = app('AdWordsRepository');

        $userListId = $repository->createUserList(config('app.name') . ' - Orders Test');

        $this->assertTrue((int) $userListId > 0);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_populate_a_user_list()
    {
        $order = Order::confirmed()->orderBy('id', 'desc')->first();

        $repository = app('AdWordsRepository');

        $result = $repository->uploadUserList($order, 6512996316);

        $this->assertTrue($result instanceof MutateMembersReturnValue);
    }
}