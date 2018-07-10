<?php

declare(strict_types=1);

namespace WebsiteCrawler;

use Psr\Http\Message\UriInterface;

class Result
{
    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @var array
     */
    protected $data;

    /**
     * @return UriInterface
     */
    public function getUri(): ?UriInterface
    {
        return $this->uri;
    }

    /**
     * @param UriInterface $uri
     */
    public function setUri(UriInterface $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    public function get(string $type)
    {
        if (isset($this->data[$type])) {
            return $this->data[$type];
        }
    }

    /**
     * @param string $type
     * @param mixed $value
     */
    public function set(string $type, $value): void
    {
        $this->data[$type] = $value;
    }
}
