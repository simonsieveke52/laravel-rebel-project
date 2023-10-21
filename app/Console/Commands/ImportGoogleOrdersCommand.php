<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ReportOrderToAmazonJob;
use App\Notifications\TextNotification;
use Illuminate\Support\Facades\Notification;

class ImportGoogleOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:import-google-orders {acknowledged}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Google orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        set_time_limit(0);
        ini_set('memory_limit', -1);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $imported = 0;

        $parameters['acknowledged'] = $this->argument('acknowledged') === 'false' 
            ? false 
            : true;

        $orders = collect();
        $orderMerchantService = app()->make('OrderMerchantService');
        $googleOrders = $orderMerchantService->listOrders($parameters);

        foreach ($googleOrders as $googleOrder) {

            if ($googleOrder->getStatus() !== 'pendingShipment') {
                continue;
            }

            $this->info($googleOrder->getId());

            try {
                $order = $orderMerchantService->createOrder($googleOrder);
                $orderMerchantService->updateMerchantOrderId($googleOrder->getId(), $order->id);
                $orderMerchantService->setAcknowledged($googleOrder->getId());
                $imported++;

                $orders->push($order);
            } catch (\Exception $e) {
                $this->info($e->getMessage());
            }
        }

        foreach ($orders as $index => $order) {

            if ($order->products->where('is_fba', true)->isEmpty()) {
                continue;
            }

            try {
                ReportOrderToAmazonJob::dispatchNow($order);
                sleep(1);
            } catch (\Exception $e) {
                logger($e->getMessage());
            }
        }

        if ($imported > 0) {
            Notification::route('slack', config('services.slack.order_notification_webhook'))
                ->notify(
                    new TextNotification("NOTICE: Merchant Orders Imported: {$imported} orders")
                );
        }

        $this->info("Total imported: {$imported}");
    }
}
