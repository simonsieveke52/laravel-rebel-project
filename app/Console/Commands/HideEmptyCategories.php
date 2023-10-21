<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class HideEmptyCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:hide-empty-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check each category then hide all empty ones';

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
        \App\Category::chunk(50, function($categories) {
            $categories->each(function($category) {
                if ($category->children->isEmpty() && $category->products->isEmpty()) {
                    $category->status = false;
                    $category->save();
                }
            });
        });

        Artisan::call('fme:clear-cache');
    }
}
