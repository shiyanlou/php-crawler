<?php

declare(strict_types=1);

namespace App\WebsiteCrawler;

use WebsiteCrawler\Context as BaseContext;

class Context extends BaseContext
{
    /**
     * @var int
     */
    protected $websiteId;

    /**
     * @return int
     */
    public function getWebsiteId(): ?int
    {
        return $this->websiteId;
    }

    /**
     * @param int $websiteId
     */
    public function setWebsiteId(int $websiteId)
    {
        $this->websiteId = $websiteId;
    }
}
