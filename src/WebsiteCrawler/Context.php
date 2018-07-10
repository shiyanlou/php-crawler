<?php

declare(strict_types=1);

namespace WebsiteCrawler;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;

class Context
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var mixed
     */
    protected $walker;

    /**
     * @return HttpClient
     */
    public function getHttpClient(): ?HttpClient
    {
        return $this->httpClient;
    }

    /**
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return MessageFactory
     */
    public function getMessageFactory(): MessageFactory
    {
        return $this->messageFactory;
    }

    /**
     * @param MessageFactory $messageFactory
     */
    public function setMessageFactory(MessageFactory $messageFactory): void
    {
        $this->messageFactory = $messageFactory;
    }

    /**
     * @return mixed
     */
    public function getWalker()
    {
        return $this->walker;
    }

    /**
     * @param mixed $walker
     */
    public function setWalker($walker): void
    {
        $this->walker = $walker;
    }
}
