<?php

declare(strict_types=1);

namespace WebsiteCrawler\Filter;

trait FilterTrait
{
    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return self::TYPE;
    }
}
