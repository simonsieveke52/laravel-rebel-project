<?php

namespace App\Console\Commands;

use App\Order;
use App\TrackingNumber;
use Illuminate\Console\Command;
use FME\Amazon\AmazonRepository;
use App\Notifications\TextNotification;
use App\Events\TrackingNumberCreatedEvent;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TrackingNumberNotification;

class CheckAmazonOrderTrackingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:sync-amazon-fba {order_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check amazon for tracking info about the given order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! is_null($this->argument('order_id'))) {
            $order = Order::findOrFail($this->argument('order_id'));
            $this->processOrder($order);
            return true;
        }

        $self = $this;

        $this->info('Total to process: ' . Order::pending()->afn()->count());

        Order::pending()->afn()->chunk(20, function ($orders) use ($self) {
            foreach ($orders as $order) {
                try {
                    $self->processOrder($order);
                } catch (\Exception $e) {
                    logger($e->getMessage());
                }
            }
        });

        $this->info('Done.');
    }

    /**
     * @return void
     */
    private function processOrder(Order $order)
    {
        $amazon = new AmazonRepository();

        $response = $amazon->GetFulfillmentOrder([
            'SellerFulfillmentOrderId' => $order->id
        ]);

        if (count($response['FulfillmentShipment']) === 0) {
            throw new \Exception("No tracking data found for order Id {$order->id}");
        }

        $fullfillments = isset($response['FulfillmentShipment']['member']['FulfillmentShipmentPackage']) 
            ? [$response['FulfillmentShipment']['member']['FulfillmentShipmentPackage']]
            : $response['FulfillmentShipment']['member'];

        foreach ($fullfillments as $fullfillment) {

            $packages = [$fullfillment];

            if (isset($fullfillment['member'])) {
                $packages = isset($fullfillment['member'][0])
                    ? $fullfillment['member']
                    : [$fullfillment['member']];
            }

            if (count($packages) === 0) {
                throw new \Exception("No packages for this shipment - order Id {$order->id}");
            }

            foreach ($packages as $item) {

                $package = isset($item['TrackingNumber']) 
                    ? $item 
                    : (
                        isset($item['FulfillmentShipmentPackage']['member']) 
                            ? $item['FulfillmentShipmentPackage']['member']
                            : $item['FulfillmentShipmentPackage']
                    );

                if (trim($package['TrackingNumber'] ?? '') === '') {
                    continue;
                }

                $quantityContainer = isset($fullfillment['FulfillmentShipmentItem']) && isset($fullfillment['FulfillmentShipmentItem']['member'])
                    ? $fullfillment['FulfillmentShipmentItem']['member']
                    : $fullfillment['FulfillmentShipmentItem'] ?? null;

                if (! isset($quantityContainer)) {
                    $quantityContainer = isset($response['FulfillmentOrderItem']) && isset($response['FulfillmentOrderItem']['member']) 
                        ? $response['FulfillmentOrderItem']['member']
                        : $response['FulfillmentOrderItem'] ?? null;
                }

                $quantity = 1;

                if (isset($quantityContainer)) {
                    $quantity = isset($quantityContainer['Quantity']) 
                        ? $quantityContainer['Quantity']
                        : collect($quantityContainer)
                            ->where('PackageNumber', $package['PackageNumber'])
                            ->sum('Quantity');
                }

                $trackingNumber = TrackingNumber::create([
                    'number' => $package['TrackingNumber'],
                    'order_id' => $order->id,
                    'name' => $package['CarrierCode'],
                    'lot_number' => $package['PackageNumber'],
                    'quantity' => $quantity,
                ]);

                try {
                    $trackingNumber->notify(new TrackingNumberNotification($order));
                } catch (\Exception $exception) {
                    Notification::route('slack', config('services.slack.order_notification_webhook'))
                        ->notify(new TextNotification($exception->getMessage()));
                }

                event(new TrackingNumberCreatedEvent($order, $trackingNumber));
            }
        }

        $this->info("Order ID {$order->id} completed");
    }
}
