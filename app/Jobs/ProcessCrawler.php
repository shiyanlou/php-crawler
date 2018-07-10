<?php

namespace App\Jobs;

use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessCrawler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Website
     */
    protected $website;

    /**
     * @param Website $website
     *
     * @return void
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = resolve('WebsiteCrawler\Client');

        $client->getExecutor()->getUriMap()->selectCurrentDB($this->website->id);
        $client->getExecutor()->getUriMap()->flushCurrentDB();
        $client->getContext()->setWebsiteId($this->website->id);

        $client->crawl($this->website->uri);
    }
}
