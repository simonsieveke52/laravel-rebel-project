<?php

namespace App\Http\Controllers\Voyager;

use App\Order;
use App\TrackingNumber;
use App\AmazonFeedRequest;
use Illuminate\Http\Request;
use App\Jobs\CheckFeedRequestJob;
use App\Http\Controllers\Controller;
use App\Jobs\SendTrackingToGoogleJob;
use App\Notifications\TextNotification;
use App\Events\TrackingNumberCreatedEvent;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\TrackingNumbersResource;
use App\Notifications\TrackingNumberNotification;
use App\Http\Requests\CreateTrackingNumberRequest;

class TrackingNumbersController extends Controller
{
    /**
     * Create new tracking number
     *
     * @param  Request $request
     * @return view
     */
    public function create(CreateTrackingNumberRequest $request)
    {
        $order = Order::findOrFail($request->id);

        $trackingNumber = TrackingNumber::create([
            'order_id' => $order->id,
            'number' => $request->tracking_number,
            'shipping_carrier_id' => $request->shipping_carrier_id ?? '',
        ]);

        $order->loadMissing('trackingNumbers');

        switch ($order->type) {

            case 'amazon':
                $order->markAsNeedsPushTracking();
                break;

            case 'google': 
                SendTrackingToGoogleJob::dispatch(collect($order))
                    ->onQueue('default')
                    ->delay(now()->addMinutes(mt_rand(1, 2)));
                break;
            
            default:
                event(new TrackingNumberCreatedEvent($order, $trackingNumber));
                break;
        }

        return new TrackingNumbersResource($trackingNumber);
    }

    /**
     * Send email notification to customer
     *
     * @param  Request  $request
     * @return view
     */
    public function notify(Request $request)
    {
        $order = Order::findOrFail($request->id);

        if ($order->trackingNumbers->isEmpty()) {
            return response('', 500);
        }

        try {
            $order->trackingNumbers->first()->notify(
                new TrackingNumberNotification($order)
            );
        } catch (\Exception $exception) {
            Notification::route('slack', config('services.slack.order_notification_webhook'))
                ->notify(new TextNotification($exception->getMessage()));
        }

        return response()->json(['ok' => true]);
    }
}
