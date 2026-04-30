<?php

namespace App\Console\Commands;

use App\Models\Subscription; 
use Illuminate\Console\Command;

class UpdateSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-subscription-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       Subscription::where('status', 'active')->where('expires_at', '<', now())->update(['status' => 'expired']);
    }
}
