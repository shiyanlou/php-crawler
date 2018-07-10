<?php

declare(strict_types=1);

namespace WebsiteCrawler\Executor;

use Psr\Http\Message\UriInterface;
use WebsiteCrawler\Context;
use WebsiteCrawler\Crawler\CrawlerInterface;

class SingleUriExecutor implements ExecutorInterface
{
    public function execute(UriInterface $uri, CrawlerInterface $crawler, Context $context): \Generator
    {
        yield $crawler->crawl($uri, $context);
    }
}
