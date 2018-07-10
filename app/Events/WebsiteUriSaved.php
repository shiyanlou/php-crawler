<?php

namespace App\Events;

use App\WebsiteUri;
use Illuminate\Queue\SerializesModels;

class WebsiteUriSaved
{
    use SerializesModels;

    /**
     * @var WebsiteUri
     */
    public $websiteUri;

    /**
     * Create a new event instance.
     */
    public function __construct(WebsiteUri $websiteUri)
    {
        $this->websiteUri = $websiteUri;
    }
}
