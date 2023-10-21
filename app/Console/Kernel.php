<?php

namespace App\Console;

use App\Order;
use App\Notifications\TextNotification;
use App\Console\Commands\ClearCacheCommand;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ProcessFileCommand;
use Illuminate\Support\Facades\Notification;
use App\Console\Commands\GenerateFeedCommand;
use App\Console\Commands\HideEmptyCategories;
use App\Console\Commands\OutputOrdersCommand;
use App\Console\Commands\ReportPendingOrders;
use App\Console\Commands\UpdateProductsCommand;
use App\Console\Commands\ProcessPriceFileCommand;
use App\Console\Commands\ImportAmazonOrdersCommand;
use App\Console\Commands\ImportGoogleOrdersCommand;
use App\Console\Commands\ProcessDotTrackingCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SyncAmazonPriceAndQuantityCommand;
use App\Console\Commands\MailchimpAbandonedCartSyncCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ClearCacheCommand::class,
        ProcessFileCommand::class,
        OutputOrdersCommand::class,
        HideEmptyCategories::class,
        GenerateFeedCommand::class,
        UpdateProductsCommand::class,
        ReportPendingOrders::class,
        ProcessPriceFileCommand::class,
        ImportAmazonOrdersCommand::class,
        ImportGoogleOrdersCommand::class,
        ProcessDotTrackingCommand::class,
        SyncAmazonPriceAndQuantityCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('backup:clean')->dailyAt('00:00');
        
        $schedule->command('backup:run --only-db')->dailyAt('00:10');

        $schedule->command('queue:retry all')->withoutOverlapping()->dailyAt('00:30');
        
        $schedule->command('fme:sync-amazon-fba')->withoutOverlapping()->hourlyAt(30);

        $schedule->command('fme:sync-amazon-price-and-quantity')->withoutOverlapping()->hourly();

        $schedule->command('fme:import-amazon-orders')->withoutOverlapping()->cron('*/20 * * * *');

        $schedule->command('fme:import-google-orders false')->withoutOverlapping()->hourlyAt(15);

        //
        $schedule->command('fme:feed products')->withoutOverlapping()->cron('0 */13 * * *');

        // ftp pricing should call this script
        $schedule->command('fme:feed all-products')->withoutOverlapping()->cron('0 */6 * * *');

        $schedule->command('fme:ftp-get-files')->withoutOverlapping()->hourlyAt(20);

        $schedule->command('fme:ftp-process-prices')->withoutOverlapping()->hourlyAt(45);

        $schedule->command('fme:ftp-get-tracking')->withoutOverlapping()->hourlyAt(30);

        $schedule->command('fme:export-orders fba')->withoutOverlapping()->dailyAt('13:30');

        $schedule->command('fme:export-orders regular')->withoutOverlapping()->dailyAt('13:30');
        
        $schedule->command('fme:export-orders bulk')->withoutOverlapping()->dailyAt('13:30');

        $schedule->command('fme:export-orders frozen')->withoutOverlapping()->days([0, 1, 2, 6])->at('13:30');

        $schedule->command('fme:sync-amazon-price-and-quantity')->withoutOverlapping()->hourly();

        if (config('mailchimp.enabled')) {
            // $schedule->command('mailchimp:order-sync')->dailyAt('3:00');
            // $schedule->command('mailchimp:abandoned-cart-sync')->hourlyAt(0);
            $schedule->command('mailchimp:product-sync')->withoutOverlapping()->dailyAt('3:00');
        }

        $schedule->call(function () {
            try {

                $total = Order::pending()->count();

                if ($total === 0) {
                    throw new \Exception("No orders found");
                }

                $link = route('voyager.orders.index', 'order_type=pending');

                $content = "NOTICE: {$total} orders older than 48 hours with no tracking have been found. {$link}";

                Notification::route('slack', config('services.slack.order_notification_webhook'))
                    ->notify(new TextNotification($content));
            } catch (\Exception $e) {

            }
        })->dailyAt('09:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
