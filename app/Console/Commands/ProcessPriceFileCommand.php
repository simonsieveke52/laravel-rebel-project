<?php

namespace App\Console\Commands;

use App\Product;
use App\UserFile;
use Illuminate\Console\Command;
use App\Jobs\DownloadFtpFileJob;
use App\Notifications\TextNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class ProcessPriceFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:ftp-process-prices {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and process DOT price file.';

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
     * @return string
     */
    public function getDotFile()
    {
        $this->info('Requesting remote storage.');

        return collect(
                Storage::disk('ftp')->allFiles('/FromDot')
            )
            ->reject(function($file) {
                return strpos($file, 'FromDot/P') === false;
            })
            ->take(-10)
            ->reject(function($file) {
                try {
                    [$folder, $fileName] = explode('/', $file);
                    UserFile::where('name', $fileName)->firstOrFail();
                    $this->info('Reject ' . $fileName);
                    return true;
                } catch (\Exception $e) {
                    $this->info('Found ' . $file);
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
            ->last()['file'] ?? '';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $file = is_null($this->argument('file')) || trim($this->argument('file')) === ''
            ? $this->getDotFile()
            : $this->argument('file');

        if (! $this->argument('file')) {

            $filePath = $file;

            $this->info('Processing ' . $filePath);

            $file = explode('/', $file);
            $folder = isset($file[0]) ? $file[0] : '';
            $fileName = isset($file[1]) ? $file[1] : '';

            if ($fileName == '') {
                $this->info('No File found.');
                return false;
            }

            try {
                $file = UserFile::where('name', $fileName)->firstOrFail();
                $this->info('Found file on local database - ' . $fileName);
            } catch (\Exception $e) {
                $this->info('Downloading file ... - ' . $fileName);
                $file = DownloadFtpFileJob::dispatchNow($fileName, $filePath, 'price');
            }
        }

        if (is_string($this->argument('file'))) {
            $file = UserFile::where('name', $file)->firstOrFail();
        }

        if (! ($file instanceof UserFile)) {
            return false;
        }

        $ProductsUpdated = 0;

        $this->info($file->name);
        
        $this->info('total rows ' . count($file->content));

        $file->update([
            'processed' => 2
        ]);

        $progressBar = $this->output->createProgressBar(count($file->content));
        $progressBar->start();

        foreach ($file->content as $index => $row) {

            $newCost = '';
            $file->increment('current_row');

            if ($index === 0) {
                continue;
            }

            try {

                if ((int) $row[17] !== 20000 || (int) $row[2] !== 1) {
                    continue;
                }

                $progressBar->advance();

                switch (strtoupper(trim($row[22]))) {

                    case 'CA':
                    case 'BG':
                    case 'DR':
                    case 'EA':
                    case 'PA':
                        Product::withoutGlobalScopes()
                            ->where('vendor_code', $row[10])
                            ->update([
                                'cost' => round($row[20], 2)
                            ]);
                        break;

                    case 'LB':
                        Product::withoutGlobalScopes()->where('vendor_code', $row[10])
                            ->get()
                            ->each(function($product) use ($row) {
                                $product->cost = round($row[20] * $product->vendor_weight, 2);
                                $product->save();
                            });
                        sleep(mt_rand(0, 1));
                        break;
                }

            } catch (\Exception $e) {
                $this->info($e->getMessage());
            }
        }

        $progressBar->finish();

        $file->update([
            'processed' => 1
        ]);

        try {
            Artisan::call('fme:clear-cache');
        } catch (\Exception $e) {
            logger($e->getMessage());
        }

        $this->info(PHP_EOL);
        $this->info('Generate new products feed.');

        try {
            Notification::route('slack', config('services.slack.order_notification_webhook'))
                ->notify(new TextNotification("Price file '{$file->name}' processed."));
        } catch (\Exception $e) {
            logger($e->getMessage());
        }
    }
}
