<?php

namespace App\Console\Commands;

use App\Product;
use App\Category;
use Illuminate\Console\Command;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear site cache';

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
            $this->call('responsecache:clear');
            Product::flushCache();
            Category::flushCache();
            $this->info('All cache cleared !');
        } catch (\Exception $e) {
            $this->info($e->getMessage());
        }
    }
}