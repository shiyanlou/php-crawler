<?php

declare(strict_types=1);

namespace WebsiteCrawler;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use League\Uri;
use WebsiteCrawler\Crawler\CrawlerInterface;
use WebsiteCrawler\Executor\ExecutorInterface;
use WebsiteCrawler\Filter\FilterInterface;
use WebsiteCrawler\Processor\ProcessorInterface;

class Client
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var CrawlerInterface
     */
    private $crawler;

    /**
     * @var ExecutorInterface
     */
    private $executor;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var array
     */
    private $processors;

    /**
     * @param HttpClient $httpClient
     * @param MessageFactory $messageFactory
     * @param Context $context
     */
    public function __construct(HttpClient $httpClient = null, MessageFactory $messageFactory = null, ?Context $context = null)
    {
        if (null === $context) {
            $context = new Context();
        }
        $this->context = $context;
        $this->context->setHttpClient($httpClient ?: HttpClientDiscovery::find());
        $this->context->setMessageFactory($messageFactory ?: MessageFactoryDiscovery::find());
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }
    /**
     * @return CrawlerInterface
     */
    public function getCrawler(): ?CrawlerInterface
    {
        return $this->crawler;
    }

    /**
     * @param CrawlerInterface $crawler
     */
    public function setCrawler(CrawlerInterface $crawler): void
    {
        $this->crawler = $crawler;
    }
    /**
     * @return ExecutorInterface
     */
    public function getExecutor(): ?ExecutorInterface
    {
        return $this->executor;
    }

    /**
     * @param ExecutorInterface $executor
     */
    public function setExecutor(ExecutorInterface $executor): void
    {
        $this->executor = $executor;
    }

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter): void
    {
        $this->filters[] = $filter;
    }

    /**
     * @param FilterInterface $filter
     */
    public function removeFilter(FilterInterface $filter): void
    {
        foreach ($this->filters as $idx => $currentFilter) {
            if ($currentFilter === $filter) {
                unset($this->filters[$idx]);
            }
        }
    }

    /**
     * @param ProcessorInterface $processor
     */
    public function addProcessor(ProcessorInterface $processor): void
    {
        $this->processors[] = $processor;
    }

    /**
     * @param ProcessorInterface $processor
     */
    public function removeProcessor(ProcessorInterface $processor): void
    {
        foreach ($this->processors as $idx => $currentProcessor) {
            if ($currentProcessor === $processor) {
                unset($this->processors[$idx]);
            }
        }
    }

    /**
     * @param string $uri
     */
    public function crawl(string $uri): void
    {
        $uri = Uri\create($uri);

        foreach ($this->executor->execute($uri, $this->crawler, $this->context) as $result) {
            foreach ($this->filters as $filter) {
                $result->set($filter::TYPE, $filter->filter($this->context));
            }

            foreach ($this->processors as $processor) {
                $processor->process($result, $this->context);
            }
        }
    }
}
