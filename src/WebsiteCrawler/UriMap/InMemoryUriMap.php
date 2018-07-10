<?php

declare(strict_types=1);

namespace WebsiteCrawler\UriMap;

/**
 * Sequantial InMememoryUriMap, don't use in multithread context.
 */
class InMemoryUriMap implements UriMapInterface
{
    /**
     * @var array
     */
    private $uris = [];

    /**
     * {@inheritdoc}
     */
    public function set(string $uri, array $value): void
    {
        $this->uris[$uri] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $uri): ?array
    {
        if (!isset($this->uris[$uri])) {
            return null;
        }

        return $this->uris[$uri];
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $uri): bool
    {
        if (isset($this->uris[$uri])) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function push(string $uri, array $value): void
    {
        if (isset($this->uris[$uri])) {
            return;
        }

        $this->uris[$uri] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function distinct(array $uris, array $value): array
    {
        $filteredUris = [];
        foreach ($uris as $uri) {
            if (!isset($this->uris[$uri])) {
                $this->set($uri, $value);
                $filteredUris[] = $uri;
            }
        }

        return $filteredUris;
    }
}
