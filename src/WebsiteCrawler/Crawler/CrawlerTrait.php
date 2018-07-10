<?php

declare(strict_types=1);

namespace WebsiteCrawler\Crawler;

trait CrawlerTrait
{
    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return self::TYPE;
    }
}
