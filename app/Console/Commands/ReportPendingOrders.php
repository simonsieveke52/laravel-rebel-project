<?php

namespace App\Console\Commands;

use App\Order;
use Illuminate\Console\Command;
use App\Notifications\TextNotification;
use Illuminate\Support\Facades\Notification;
use App\Repositories\Locate\LocateRepository;
use App\Notifications\OrderReportedNotification;

class ReportPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:report-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report orders to locate API';

    /**
     * @var LocateRepository
     */
    protected $locateRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LocateRepository $locateRepository)
    {
        parent::__construct();
        set_time_limit(0);
        
        ini_set('memory_limit', -1);
        $this->locateRepository = $locateRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders = Order::confirmed()->where('is_reported', false)->get();

        $this->info('Total orders to report: ' . $orders->count());

        foreach ($orders as $order) {
            
            try {
                $this->locateRepository->reportSaleOrder($order)
                    ->notify(new OrderReportedNotification($order));
            } catch (\Exception $exception) {
                Notification::route('slack', config('services.slack.order_notification_webhook'))
                    ->notify(new TextNotification($exception->getMessage()));
            }

            sleep(mt_rand(1, 5));
        }
    }
}
