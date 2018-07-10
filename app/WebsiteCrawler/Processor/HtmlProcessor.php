<?php

declare(strict_types=1);

namespace App\WebsiteCrawler\Processor;

use Illuminate\Contracts\Filesystem\Filesystem;
use WebsiteCrawler\Context;
use WebsiteCrawler\Result;
use WebsiteCrawler\Processor\ProcessorInterface;
use WebsiteCrawler\Processor\ProcessorTrait;

class HtmlProcessor implements ProcessorInterface
{
    use ProcessorTrait;

    const TYPE = 'html';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Result $result, Context $context): void
    {
        $this->filesystem->put(
            sprintf('%s/%s', $result->getUri()->getHost(), $this->normalizeFileName($result->getUri()->getPath())),
            $result->get($this->getType())
        );
    }

    /**
     * Solves linux FS special character issue.
     *
     * @param string $filename
     *
     * @return string
     */
    private function normalizeFileName(string $filename): string
    {
        return mb_ereg_replace('/', '__', $filename);
    }
}
