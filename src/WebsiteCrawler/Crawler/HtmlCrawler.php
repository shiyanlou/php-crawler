<?php

declare(strict_types=1);

namespace WebsiteCrawler\Crawler;

use Http\Client\HttpClient;
use Psr\Http\Message\UriInterface;
use Symfony\Component\DomCrawler\Crawler;
use WebsiteCrawler\Client;
use WebsiteCrawler\Context;
use WebsiteCrawler\Result;

class HtmlCrawler implements CrawlerInterface
{
    use CrawlerTrait;

    const TYPE = 'html';

    /**
     * {@inheritdoc}
     */
    public function crawl(UriInterface $uri, Context $context): ?Result
    {
        $response = $context->getHttpClient()->sendRequest(
            $context->getMessageFactory()->createRequest('GET', (string) $uri)
        );

        if (200 !== $response->getStatusCode()) {
            return null;
        }

        $result = new Result();
        $result->setUri($uri);
        $result->set(self::TYPE, (string) $response->getBody());

        $context->setWalker(new Crawler($result->get(self::TYPE)));

        return $result;
    }
}
