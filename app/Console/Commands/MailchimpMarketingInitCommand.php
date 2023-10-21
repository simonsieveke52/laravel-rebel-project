<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Repositories\MailchimpRepository;

class MailchimpMarketingInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailchimp:marketing-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Mailchimp Marketing Store & Audience';

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
     * @return int
     */
    public function handle()
    {
        if (!config('mailchimp.enabled')) {
            $this->error('Try enabling mailchimp..');
            return 0;
        }

        if (empty(config('mailchimp.api_key'))) {
            $this->error('Mailchimp API key missing..');
            return 0;
        }

        try {
            $mailchimp = new MailchimpRepository();
            if (!$mailchimp->isSetup) {
                $mailchimp->marketingSetup();
                $this->info('Marketing Setup completed');
            } else {
                $this->line('Marketing Setup already completed');
            }
        } catch(Exception $e) {
            $this->error("Failed to setup mailchimp marketing integration\n" . $e->getMessage());
        }
    }
}
