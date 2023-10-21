<?php

namespace App\Jobs;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120000;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $startDate;

    /**
     * @var string
     */
    protected $endDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $path, string $startDate = '', string $endDate = '')
    {
        $this->path = $path;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $fh = fopen($this->path, 'w');

        logger('file created on ' . $this->path);

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

        Order::with(['products', 'shippings', 'addresses', 'orderStatus'])
            ->where('order_status_id', 2)
            ->confirmed()
            ->whereDate('confirmed_at', '>=', $this->startDate)
            ->whereDate('confirmed_at', '<=', $this->endDate)
            ->chunk(10, function ($orders) use (&$fh) {

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

                logger('running..');
            });

        fclose($fh);

        logger('export completed.');
    }
}
