<?php

declare(strict_types=1);

namespace WebsiteCrawler\Filter;

use Symfony\Component\DomCrawler\Crawler;
use WebsiteCrawler\Context;
use WebsiteCrawler\Exception\InvalidWalkerException;

class ImageFilter implements FilterInterface
{
    use FilterTrait;

    const TYPE = 'image';

    /**
     * {@inheritdoc}
     */
    public function filter(Context $context): array
    {
        $walker = $context->getWalker();
        if (!$walker instanceof Crawler) {
            throw new InvalidWalkerException();
        }

        $elements = $walker->filter('img');

        $result = [];
        foreach ($elements as $element) {
            $result[] = $element->getAttribute('src');
        }

        return $result;
    }
}
