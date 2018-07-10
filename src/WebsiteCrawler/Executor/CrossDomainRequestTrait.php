<?php

declare(strict_types=1);

namespace WebsiteCrawler\Executor;

use Psr\Http\Message\UriInterface;

trait CrossDomainRequestTrait
{
    /**
     * @var bool
     */
    protected $allowedCrossDomainRequests = false;

    /**
     * @return bool
     */
    public function isAllowedCrossDomainRequests(): bool
    {
        return $this->allowedCrossDomainRequests;
    }

    /**
     * @param bool $allow
     */
    public function setAllowedCrossDomainRequests(bool $allow): void
    {
        $this->allowedCrossDomainRequests = $allow;
    }

    /**
     * @param UriInterface $previousUri
     * @param UriInterface $newUri
     *
     * @return bool
     */
    protected function isCrossDomainRequest(UriInterface $previousUri, UriInterface $newUri): bool
    {
        if ($previousUri->getHost() !== $newUri->getHost()) {
            return true;
        }

        return false;
    }
}
