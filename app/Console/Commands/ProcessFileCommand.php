<?php

namespace App\Console\Commands;

use App\UserFile;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Jobs\DownloadFtpFileJob;
use App\Notifications\TextNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class ProcessFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:ftp-get-files {--force=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download product files from ftp and process it';

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
            $file = collect(Storage::disk('ftp')->allFiles('/FromDot'))
                ->reject(function($file) {
                    // return strpos($file, 'FromDot/I') === false;
                    return strpos($file, 'FromDot/RLGL') === false;
                })
                ->take(-10)
                ->reject(function($file) {
                    try {
                        UserFile::where('name', explode('/', $file)[1])->firstOrFail();
                        return true;
                    } catch (\Exception $e) {
                        return false;
                    }
                })
                ->map(function($file) {
                    return [
                        'file' => $file,
                        'size' => Storage::disk('ftp')->size($file),
                        'lastModified' => Storage::disk('ftp')->lastModified($file)
                    ];
                })
                ->sortBy(function($item) {
                    return $item['size'];
                })
                ->take(-2)
                ->sortBy(function($item) {
                    return $item['lastModified'];
                })
                ->last();

            if (! is_array($file) || ! isset($file['file'])) {
                throw new \Exception("No file to process found!");
            }

            $file = $file['file'];

            $this->info($file);

            $fileName = 'boh_ftp_'.date('m_d_Y_H_i_s').'.csv';

            $this->info('Renamed to: ' . $fileName);

            try {
                [$folder, $fileName] = explode('/', $file);
                UserFile::where('name', $fileName)->firstOrFail();
            } catch (\Exception $e) {
            }

            $content = Storage::disk('ftp')->get($file);
            $count = count(explode(PHP_EOL, $content));

            if ($count < 20000 && (int) $this->option('force') !== 1) {
                return Notification::route('slack', config('services.slack.order_notification_webhook'))
                    ->notify(
                        new TextNotification('<@UT1P35K7A> inventory file was rejected. Only '. ($count ?? 0) .' items found')
                    );
            }

            DownloadFtpFileJob::dispatchNow($fileName, $file, 'boh');

        } catch (\Exception $e) {
            Notification::route('slack', config('services.slack.order_notification_webhook'))
                ->notify(
                    new TextNotification($e->getMessage())
                );
        }
    }
}
