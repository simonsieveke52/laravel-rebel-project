<?php

namespace App\Console\Commands;

use App\Order;
use App\UserFile;
use App\TrackingNumber;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Jobs\SendTrackingToAmazonJob;
use App\Jobs\SendTrackingToGoogleJob;
use App\Notifications\TextNotification;
use Illuminate\Support\Facades\Storage;
use App\Events\TrackingNumberCreatedEvent;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TrackingNumberNotification;

class ProcessDotTrackingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:process-tracking-file {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process dot tracking file';

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
        $file = $this->argument('file');

        $this->info($file);

        $content = null;

        if (Storage::disk('public')->exists("/csv/{$file}")) {
            $content = Storage::disk('public')->get("/csv/{$file}");
        }

        if (! Storage::disk('public')->exists("/csv/{$file}")) {
            $content = Storage::disk('ftp')->get("/FromDot/{$file}");
            Storage::disk('public')->put("/csv/{$file}", $content);
        }

        if (trim($content) === '') {
            throw new \Exception("Invalid file");
        }

        $csv = \League\Csv\Reader::createFromString($content);

        $userFile = UserFile::create([
            'name' => $file,
            'file_type' => 'tracking',
            'total_rows' => count($csv),
            'errors' => '[]',
        ]);

        try {

            $totalOrders = 0;
            $googleOrders = collect();
            $amazonOrders = Order::manualTracking()->get();

            $this->info("total rows on {$file} " . count($csv));

            foreach ($csv as $index => $row) {

                if ($index === 0) {
                    continue;
                }

                $userFile->increment('current_row');

                if (trim($row[21]) === '') {
                    $this->info("ignore row {$index}");
                    continue;
                }

                $this->info("Row {$index} / Order ID: " . $row[2]);

                try {
                    try {
                        
                        TrackingNumber::where([
                            'number' => trim($row[21]),
                            'order_id' => $row[2],
                            'quantity' => $row[17],
                        ])->firstOrFail();

                        $this->info("Row {$index} / Tracking Found - ");

                    } catch (\Exception $e) {

                        $order = Order::findOrFail($row[2]);

                        $this->info('Order ID: ' . $order->id);

                        $trackingNumber = TrackingNumber::create([
                            'number' => $row[21],
                            'order_id' => $order->id,
                            'quantity' => $row[17],
                            'user_file_id' => $userFile->id,
                            'name' => $row[15],
                            'lot_number' => $row[22],
                        ]);

                        $this->info('Tracking Created - ' . $trackingNumber->id);

                        $totalOrders++;

                        if ($order->type === 'amazon') {
                            $order->loadMissing('trackingNumbers');
                            $amazonOrders->push($order);
                            continue;
                        }

                        if ($order->type === 'google') {
                            $order->loadMissing('trackingNumbers.order');
                            $googleOrders->push($order);
                            continue;
                        }

                        event(new TrackingNumberCreatedEvent($order, $trackingNumber));

                        try {
                            $trackingNumber->notify(new TrackingNumberNotification($order));
                        } catch (\Exception $exception) {
                            Notification::route('slack', config('services.slack.order_notification_webhook'))
                                ->notify(new TextNotification($exception->getMessage()));
                        }
                    }
                } catch (\Exception $e) {
                    $this->info($e->getMessage());
                    logger($e->getMessage());
                }
            }

            $this->info("Google orders " . $googleOrders->count());

            if (! $googleOrders->isEmpty()) {
                SendTrackingToGoogleJob::dispatch($googleOrders)
                    ->onQueue('default')
                    ->delay(
                        now()->addMinutes(5)
                    );
            }

            $this->info("Amazon orders " . $amazonOrders->count());

            if (! $amazonOrders->isEmpty()) {
                try {
                    SendTrackingToAmazonJob::dispatch($amazonOrders)
                        ->onQueue('default')
                        ->delay(
                            now()->addMinutes(mt_rand(10, 11))
                        );
                } catch (\Exception $e) {
                    logger($e->getMessage());    
                }
            }

            try {
                $userFile->processed = true;
                $userFile->file_errors = json_encode($userFile->getErrors());
                $userFile->save();
            } catch (\Exception $e) {
                $this->info($e->getMessage());
                logger($e->getMessage());
            }

            $this->info('total orders processed ' . $totalOrders);

            if ($totalOrders > 0) {
                Notification::route('slack', config('services.slack.order_notification_webhook'))
                    ->notify(new TextNotification("NOTICE: Processed and emailed tracking for {$totalOrders} orders."));
            }

        } catch (\Exception $e) {
            $this->info($e->getMessage());
            logger($e->getMessage());
        }
    }
}
