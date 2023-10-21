<?php

namespace App\Console\Commands;

use App\Order;
use App\UserFile;
use App\OrderProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutputFbaOrdersExport;
use App\Exports\OutputBulkOrdersExport;
use App\Notifications\TextNotification;
use App\Exports\OutputFrozenOrdersExport;
use App\Exports\OutputRegularOrdersExport;
use Illuminate\Support\Facades\Notification;

class OutputOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:export-orders {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export orders type in (regular, frozen or bulk)';

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
     * @param  string $type
     * @return int
     */
    protected function checkOrdersCount(string $type)
    {
        try {
            return OrderProduct::whereHas('order', function($query) {
                    return $query->whereNotIn('orders.order_status_id', [0, 4])
                        ->where('orders.refunded', false)
                        ->where('orders.confirmed', true);
                })
                ->whereHas('product', function($query) use ($type) {
                    $query->where('frozen', $type == 'frozen');
                })
                ->where('was_outputted', false)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $type = $this->argument('type');

        if (! in_array($type, ['frozen', 'regular', 'bulk', 'fba'])) {
            throw new \Exception("Invalid argument.");
        }

        $fileName = $type.'-orders-'.strtotime('now').'.csv';

        $userFile = UserFile::create([
            'name' => $fileName,
            'file_type' => 'orders-export'
        ]);

        $url = URL::temporarySignedRoute(
            'export.output-orders', now()->addDays(5), ["export-type={$type}", "fileName={$fileName}"]
        );

        $content = "A new order file is ready - REGULAR: {$url}";
        $exportable = new OutputRegularOrdersExport();

        if ($type === 'frozen') {
            $exportable = new OutputFrozenOrdersExport();
            $content = "A new order file is ready - TEMP CONTROLLED: {$url}";
        }

        if ($type === 'fba') {
            $exportable = new OutputFbaOrdersExport();
            $content = "A new order file is ready - FBA: {$url}";
        }

        if ($type === 'bulk') {
            $exportable = new OutputBulkOrdersExport();
            $content = "A new order file is ready - Bulk: {$url}";
        }

        $this->info($content);

        try {
            if (! $exportable->store($fileName, 'public-csv')) {
                Notification::route('slack', config('services.slack.order_notification_webhook'))
                    ->notify(new TextNotification("couldn't save {$fileName}."));
            }
        } catch (\Exception $e) {
            Notification::route('slack', config('services.slack.order_notification_webhook'))
                    ->notify(new TextNotification("couldn't save {$fileName}. - " . $e->getMessage()));
        }

        if (count($userFile->content) <= 1) {
            Notification::route('slack', config('services.slack.order_notification_webhook'))
                    ->notify(new TextNotification("No file generated for {$type}."));
            return false;
        }

        Mail::send([], [], function ($message) use ($content, $type) {
            $message->to(config('mail.bcc'))
                ->subject("A new order file is ready -  {$type}")
                ->setBody($content);
        });

        Notification::route('slack', config('services.slack.order_notification_webhook'))
                    ->notify(new TextNotification($content));
    }
}
