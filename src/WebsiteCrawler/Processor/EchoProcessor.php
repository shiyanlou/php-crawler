<?php

declare(strict_types=1);

namespace WebsiteCrawler\Processor;

use WebsiteCrawler\Context;
use WebsiteCrawler\Result;
use WebsiteCrawler\Processor\ProcessorInterface;
use WebsiteCrawler\Processor\ProcessorTrait;

/**
 * EchoProcessor class for debug.
 */
class EchoProcessor implements ProcessorInterface
{
    use ProcessorTrait;

    const TYPE = 'html';

    /**
     * {@inheritdoc}
     */
    public function process(Result $result, Context $context): void
    {
        echo (string) $result->getUri();
        echo '<br>';
    }
}
