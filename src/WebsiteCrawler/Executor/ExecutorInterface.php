<?php

declare(strict_types=1);

namespace WebsiteCrawler\Executor;

use Psr\Http\Message\UriInterface;
use WebsiteCrawler\Context;
use WebsiteCrawler\Crawler\CrawlerInterface;
use WebsiteCrawler\Result;

interface ExecutorInterface
{
    /**
     * @param UriInterface $crawler
     * @param CrawlerInterface $crawler
     * @param Context $context
     *
     * @return \Generator|Result[]
     */
    public function execute(UriInterface $uri, CrawlerInterface $crawler, Context $context): ?\Generator;
}
