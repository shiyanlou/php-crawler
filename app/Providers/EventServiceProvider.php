<?php

namespace App\Providers;

use App\Events\WebsiteUriSaved as WebsiteUriSavedEvent;
use App\Listeners\WebsiteUriSaved;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        WebsiteUriSavedEvent::class => [
            WebsiteUriSaved::class,
        ],
    ];
}
