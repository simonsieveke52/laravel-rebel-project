<?php

namespace App\Http\Controllers;

use Throwable;
use App\Subscriber;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CartRepository;
use App\Http\Requests\SubscribeRequest;
use App\Repositories\MailchimpRepository;

class NewsletterController extends Controller
{

    /**
     * Create new order for a lead starting the checkout process
     *
     * @param  SubscribeRequest $request
     */
    public function store(SubscribeRequest $request)
    {
        $data = $request->validated();

        $subscriber = Subscriber::create([
			'name'       => $data['first_name'] . ' ' . $data['last_name'],
			'first_name' => $data['first_name'],
			'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'origin_id'  => $data['origin_id'],
            'ip_address' => $request->ip(),
            'status'     => 'pending'
        ]);

        $mailchimp = new MailchimpRepository();
        $member = $mailchimp->createOrUpdateMember($subscriber);

        if (is_null($member)) {
            return redirect()->back()->with('error', config('mailchimp.newsletter.failure_message'));
        }

        session()->put('subscriber', $subscriber);
        return redirect()->back()->with('message', config('mailchimp.newsletter.signup_message'));

    }
}
