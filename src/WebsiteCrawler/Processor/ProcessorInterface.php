<?php

declare(strict_types=1);

namespace WebsiteCrawler\Processor;

use WebsiteCrawler\Context;
use WebsiteCrawler\Result;

interface ProcessorInterface
{
    /**
     * @param Result $result
     * @param Context $context
     */
    public function process(Result $result, Context $context): void;

    /**
     * @return string
     */
    public static function getType(): string;
}
