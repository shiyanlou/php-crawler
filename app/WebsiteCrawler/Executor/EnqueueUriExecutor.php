<?php

declare(strict_types=1);

namespace App\WebsiteCrawler\Executor;

use App\WebsiteUri;
use Enqueue\SimpleClient\SimpleClient;
use Enqueue\Client\Message;
use League\Uri;
use Psr\Http\Message\UriInterface;
use WebsiteCrawler\Context;
use WebsiteCrawler\Crawler\CrawlerInterface;
use WebsiteCrawler\Executor\CrossDomainRequestTrait;
use WebsiteCrawler\Executor\ExecutorInterface;
use WebsiteCrawler\UriMap\UriMapInterface;

class EnqueueUriExecutor implements ExecutorInterface
{
    use CrossDomainRequestTrait;

    /**
     * @var UriMapInterface
     */
    private $uriMap;

    /**
     * @var SimpleClient
     */
    private $client;

    /**
     * @param UriMapInterface $uriMap
     * @param SimpleClient $client
     */
    public function __construct(UriMapInterface $uriMap, SimpleClient $client)
    {
        $this->uriMap = $uriMap;
        $this->client = $client;
    }

    /**
     * @return UriMapInterface
     */
    public function getUriMap(): UriMapInterface
    {
        return $this->uriMap;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(UriInterface $uri, CrawlerInterface $crawler, Context $context): ?\Generator
    {
        $this->setWebsiteUri((string) $uri, WebsiteUri::STATUS_NEW, $context->getWebsiteId()); // Set status for current uri.

        if (null === $result = $crawler->crawl($uri, $context)) {
            $this->setWebsiteUri((string) $uri, WebsiteUri::STATUS_CANCELLED, $context->getWebsiteId()); // Update status for current uri.

            return null;
        }
        $this->setWebsiteUri((string) $uri, WebsiteUri::STATUS_RUNNING, $context->getWebsiteId());

        yield $result; // Yield successful result to Client for further processing.

        $this->uriMap->set((string) $result->getUri(), ['status'=> 'success']);

        $uris = $this->normalizeUris($result->getUri(), $result->get('uri')); // Converts relative uris to absolute, if necessary.
        foreach ($this->uriMap->distinct($uris, ['status' => 'new']) as $newUri) { // Primary uriMap usage, gets unique list of uris for processing.
            $newUri = Uri\create($newUri);

            if (!$newUri instanceof UriInterface) {
                continue; // Workaround for case, when Filter/UriFilter is not perfect and non-http uris are getting over here.
            }

            if (!$this->isAllowedCrossDomainRequests() && $this->isCrossDomainRequest($result->getUri(), $newUri)) {
                continue;
            }

            $this->setWebsiteUri((string) $uri, WebsiteUri::STATUS_NEW, $context->getWebsiteId());
            $this->produceEvent((string) $newUri, $this->uriMap->getCurrentDBIndex()); // Send event with discovered uri for next process in queue to handle.
        }

        $this->setWebsiteUri((string) $uri, WebsiteUri::STATUS_SUCCESS, $context->getWebsiteId()); // Mark current uri as successful.
    }

    /**
     * @param string $uri
     * @param string $status
     * @param int $websiteId
     */
    protected function setWebsiteUri(string $uri, string $status, int $websiteId): void
    {
        if (null === $websiteUri = WebsiteUri::where(['website_id' => $websiteId, 'uri' => (string) $uri])->first()) {
            $websiteUri = new WebsiteUri();
        }
        $websiteUri->uri = (string) $uri;
        $websiteUri->status = $status;
        $websiteUri->website_id = $websiteId;
        $websiteUri->save();
    }

    /**
     * @param string $uri
     * @param int $dbIndex
     */
    protected function produceEvent(string $uri, int $dbIndex): void
    {
        $message = new Message();
        $message->setBody([
            'uri' => $uri,
            'redisDBIndex' => $dbIndex,
        ]);

        $this->client->sendEvent('app_website_crawler_uri_executor', $message);
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
