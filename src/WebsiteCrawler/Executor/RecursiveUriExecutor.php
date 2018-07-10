<?php

declare(strict_types=1);

namespace WebsiteCrawler\Executor;

use League\Uri;
use Psr\Http\Message\UriInterface;
use WebsiteCrawler\Context;
use WebsiteCrawler\Crawler\CrawlerInterface;
use WebsiteCrawler\UriMap\UriMapInterface;

class RecursiveUriExecutor implements ExecutorInterface
{
    use CrossDomainRequestTrait;

    /**
     * @var UriMapInterface
     */
    private $uriMap;

    /**
     * @param UriMapInterface $uriMap
     */
    public function __construct(UriMapInterface $uriMap)
    {
        $this->uriMap = $uriMap;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(UriInterface $uri, CrawlerInterface $crawler, Context $context): ?\Generator
    {
        if (null === $result = $crawler->crawl($uri, $context)) {
            return null;
        }

        yield $result;

        $this->uriMap->set((string) $result->getUri(), ['status'=> 'success']);

        $uris = $this->normalizeUris($result->getUri(), $result->get('uri'));
        foreach ($this->uriMap->distinct($uris, ['status' => 'new']) as $newUri) {
            $newUri = Uri\create($newUri);

            if (!$newUri instanceof UriInterface) {
                continue; // Workaround for case, when Filter/UriFilter is not perfect and non-http uris are getting over here.
            }

            if (!$this->isAllowedCrossDomainRequests() && $this->isCrossDomainRequest($result->getUri(), $newUri)) {
                continue;
            }

            yield from $this->execute($newUri, $crawler, $context);
        }
    }

    /**
     * @param UriInterface $baseUri
     * @param array $uris
     *
     * @return array
     */
    private function normalizeUris(UriInterface $baseUri, array $uris): array
    {
        foreach ($uris as $idx => $uri) {
            // Converts relative uris to absolute with $baseUri schema and host.
            $uris[$idx] = (string) Uri\create($uri, $baseUri);
        }

        return $uris;
    }
}
