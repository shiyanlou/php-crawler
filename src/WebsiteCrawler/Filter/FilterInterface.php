<?php

declare(strict_types=1);

namespace WebsiteCrawler\Filter;

use WebsiteCrawler\Context;

interface FilterInterface
{
    /**
     * @param Context $context
     *
     * @return array
     */
    public function filter(Context $context): array;

    /**
     * @return string
     */
    public static function getType(): string;
}
