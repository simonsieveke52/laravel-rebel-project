<?php

namespace Tests\Feature;

use App\Subscriber;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeControllerTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @test
     * @return void
     */
    public function it_can_subscribe()
    {
        // create new subscriber
        $response = $this->json('POST', route('subscribe.store'), [  
            '_token' => csrf_token(),
            'email' => $this->faker()->email,
            'name' => $this->faker()->name,
        ]);

        $response->assertStatus(200);
    }
}
