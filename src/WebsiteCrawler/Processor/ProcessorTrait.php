<?php

declare(strict_types=1);

namespace WebsiteCrawler\Processor;

trait ProcessorTrait
{
    /**
     * {@inheritdoc}
     */
    public static function getType(): string
    {
        return self::TYPE;
    }
}
