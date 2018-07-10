<?php

declare(strict_types=1);

namespace App\WebsiteCrawler\Enqueue\Processor;

use Enqueue\Client\TopicSubscriberInterface;
use Enqueue\Util\JSON;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrProcessor;

/**
 * Job processor.
 */
class UriExecutorProcessor implements PsrProcessor, TopicSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(PsrMessage $message, PsrContext $session): string
    {
        $message = JSON::decode($message->getBody());

        $client = resolve('WebsiteCrawler\Client');
        $client->getContext()->setWebsiteId($message['redisDBIndex']);
        $client->getExecutor()->getUriMap()->selectCurrentDB($message['redisDBIndex']);

        $client->crawl($message['uri']);

        return self::ACK;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedTopics()
    {
        return ['app_website_crawler_uri_executor'];
    }
}
