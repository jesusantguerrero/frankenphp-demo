<?php

namespace App\Jobs;

use App\Services\SiteService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessVersions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SiteService $siteService): void
    {
            $sites = $siteService->list();
            foreach ($sites as $site) {
                $siteService->updateCall($site);
            }
            event('check-completed');
    }
}
