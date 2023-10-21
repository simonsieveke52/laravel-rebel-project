<?php

namespace App\Console\Commands;

use App\Order;
use App\UserFile;
use Illuminate\Console\Command;

class CheckOrdersInFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:check-orders-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check orders in files';

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
     * @return mixed
     */
    public function handle()
    {
        $files = UserFile::where('file_type', 'orders-export')
            ->whereDate('created_at', '>=', now()->subDays(15))
            ->get();

        $ordersInFilesIds = [];

        foreach ($files as $file) {

            foreach ($file->content as $index => $row) {

                if ($index === 0) {
                    continue;
                }

                $ordersInFilesIds[] = $row[0];
            }
        }

        Order::whereDate('created_at', '>=', now()->subDays(15))
            ->where('confirmed', true)
            ->where('refunded', false)
            ->where('order_status_id', 3)
            ->whereNotIn('id', $ordersInFilesIds)
            ->update([
                'order_status_id' => 1
            ]);
    }
}
