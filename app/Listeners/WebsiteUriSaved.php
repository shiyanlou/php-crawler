<?php

namespace App\Listeners;

use App\Website;
use App\WebsiteUri;
use App\Events\WebsiteUriSaved as WebsiteUriSavedEvent;

class WebsiteUriSaved
{
    /**
     * Handle the event.
     *
     * @param  WebsiteUriSavedEvent $event
     */
    public function handle(WebsiteUriSavedEvent $event)
    {
        $count = WebsiteUri::where('website_id', $event->websiteUri->website_id)
            ->whereIn('status', [WebsiteUri::STATUS_NEW, WebsiteUri::STATUS_RUNNING])
            ->count();

        if (0 === $count) { // Once all URIs are processed, mark website as completed.
            if (null === $website = Website::find($event->websiteUri->website_id)) { // In case website was remove from UI, but messages with this ID still running.
                return;
            }

            $website->status = Website::STATUS_SUCCESS;
            $website->save();
        }
    }
}
