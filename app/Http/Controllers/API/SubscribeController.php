<?php 

namespace App\Http\Controllers\API;

use App\Subscriber;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Events\SubscriberCreateEvent;
use App\Http\Requests\SubscribeRequest;

class SubscribeController extends Controller
{
	/**
	 * Store new email
	 * 
	 * @param  SubscribeRequest $request [description]
	 * @return [type]                    [description]
	 */
	public function store(SubscribeRequest $request)
	{
		// create new subscription
		$subscriber = tap(new Subscriber(), function($subscriber) use ($request){
			$subscriber->name = $request->name;
			$subscriber->email = $request->email;
			$subscriber->save();
		});

		// need to send email to admin
		// event( new SubscriberCreateEvent($subscriber) );

		// return json response
		return response()->json(['ok' => true]);
	}
}