<?php

declare(strict_types=1);

namespace WebsiteCrawler\UriMap;

interface UriMapInterface
{
    /**
     * @param string $uri
     *
     * @return array
     */
    public function get(string $uri): ?array;

    /**
     * @param string $uri
     * @param array $value
     */
    public function set(string $uri, array $value): void;

    /**
     * @param string $uri
     *
     * @return bool
     */
    public function has(string $uri): bool;

    /**
     * @param string $uri
     * @param array $value
     */
    public function push(string $uri, array $value): void;

    /**
     * Returns unmapped uris from comparison of provided uri list with mapped uris.
     *
     * @param array $uris
     * @param array $value
     *
     * @return array
     */
    public function distinct(array $uris, array $value): array;
}
