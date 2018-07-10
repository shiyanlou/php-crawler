<?php

declare(strict_types=1);

namespace WebsiteCrawler\Crawler;

use Psr\Http\Message\UriInterface;
use WebsiteCrawler\Client;
use WebsiteCrawler\Context;
use WebsiteCrawler\Result;

interface CrawlerInterface
{
    /**
     * @param UriInterface $uri
     * @param Context $context
     *
     * @return Result
     */
    public function crawl(UriInterface $uri, Context $context): ?Result;

    /**
     * @return string
     */
    public static function getType(): string;
}
