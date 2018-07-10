<?php

declare(strict_types=1);

namespace WebsiteCrawler\Filter;

use Symfony\Component\DomCrawler\Crawler;
use WebsiteCrawler\Context;
use WebsiteCrawler\Exception\InvalidWalkerException;

class UriFilter implements FilterInterface
{
    use FilterTrait;

    const TYPE = 'uri';

    /**
     * {@inheritdoc}
     */
    public function filter(Context $context): array
    {
        $walker = $context->getWalker();
        if (!$walker instanceof Crawler) {
            throw new InvalidWalkerException();
        }

        $elements = $walker->filter('a');

        $uris = [];
        foreach ($elements as $element) {
            $uri = $element->getAttribute('href');
            if ('mailto' === mb_substr($uri, 0, 6, 'utf-8')) { // Workaround to avoid processing <a href="mailto:"></a>
                continue;
            }

            $uris[] = $uri;
        }

        return $uris;
    }
}
