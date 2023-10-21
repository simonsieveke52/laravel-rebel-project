<?php

namespace App\Console\Commands;

use App\Order;
use App\UserFile;
use App\TrackingNumber;
use App\AmazonFeedRequest;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Jobs\DownloadFtpFileJob;
use App\Jobs\CheckFeedRequestJob;
use App\Jobs\AssignTrackingNumbers;
use App\Jobs\SendTrackingToAmazonJob;
use App\Jobs\AssignTrackingNumbersJob;
use App\Notifications\TextNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Events\TrackingNumberCreatedEvent;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TrackingNumberNotification;

class ProcessTrackingFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:ftp-get-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download tracking file from ftp and process it';

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
        // return strpos($file, 'DropShipTrackingReport') === false;
        $files = collect(
            Storage::disk('ftp')->allFiles('/FromDot')
        )
        ->reject(function($file) {
            return strpos($file, 'DSTRACKING') === false;
        })
        ->map(function($file) {
            try {
                [$folder, $fileName] = explode('/', $file);
                return $fileName;
            } catch (\Exception $e) {
                return null;                
            }
        })
        ->reject(function($file) {
            return is_null($file) || trim($file) === '';
        });

        $processedFiles = UserFile::whereIn('name', $files->values()->toArray())->get('name');

        $result = $files->diff($processedFiles->pluck('name')->values());

        $result->sortKeysDesc()->each(function($file) {
            Artisan::call('fme:process-tracking-file', [
                'file' => $file
            ]);
        });
    }
}
