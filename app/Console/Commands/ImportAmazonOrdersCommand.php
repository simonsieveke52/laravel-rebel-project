<?php

namespace App\Console\Commands;

use App\Order;
use App\AmazonCronHistory;
use Illuminate\Console\Command;
use FME\Amazon\AmazonRepository;

class ImportAmazonOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:import-amazon-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import amazon orders';

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
        try {
            $order = Order::amazon()->orderBy('created_at', 'desc')->firstOrFail();
            $startFrom = $order->created_at->subMinutes(10)->setTimezone('UTC');
        } catch (\Exception $e) {
            $startFrom = now()->hour(8)->minutes(29)->setTimezone('UTC');
        }

        $this->info('Start from: '.$startFrom->format('Y-m-d H:i:s'));

        $amazonCronHistory = AmazonCronHistory::create();

        $created = 0;

        $amazon = new AmazonRepository();
        $listOrders = $amazon->listOrders($startFrom);

        foreach ($listOrders as $data) {
            try {
                Order::where('amazon_order_id', $data['AmazonOrderId'])->firstOrFail();
            } catch (\Exception $e) {
                try {
                    sleep(mt_rand(62, 65));
                    $order = $amazon->createOrder($data);
                    $created++;
                } catch (\Exception $e) {
                    logger($e->getMessage());
                }
            }
        }

        $this->info('Total Processed: '. $created);

        $amazonCronHistory->markAsProcessed($created);
    }
}
