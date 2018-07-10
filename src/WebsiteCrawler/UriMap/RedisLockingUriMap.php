<?php

declare(strict_types=1);

namespace WebsiteCrawler\UriMap;

use Redis;
use Symfony\Component\Lock\Factory;
use Symfony\Component\Lock\Store\RedisStore;
use Symfony\Component\Lock\Store\RetryTillSaveStore;

class RedisLockingUriMap implements UriMapInterface
{
    /**
     * @var Redis
     */
    private $redis;

    /**
     * @var Factory
     */
    private $lockFactory;

    /**
     * @var string
     */
    protected $statusKey = 'status';

    /**
     * @param Redis $redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;

        $store = new RedisStore($this->redis);
        $store = new RetryTillSaveStore($store);
        $this->lockFactory = new Factory($store);
    }

    /**
     * @param string $key
     */
    public function setStatusKey(string $key): void
    {
        $this->statusKey = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $uri, array $value): void
    {
        $lock = $this->lockFactory->createLock('urimap-set');
        $lock->acquire(true);
        try {
            $this->redis->hMset($uri, $value);
        } finally {
            $lock->release();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $uri): ?array
    {
        $lock = $this->lockFactory->createLock('urimap-get');
        $lock->acquire(true);

        if (!$this->redis->hExists($uri, $this->statusKey)) {
            $lock->release();

            return null;
        }

        $value = $this->redis->hGetAll($uri);
        $lock->release();

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $uri): bool
    {
        $lock = $this->lockFactory->createLock('urimap-has');
        $lock->acquire(true);
        if ($this->redis->hExists($uri, $this->statusKey)) {
            $lock->release();

            return true;
        }

        $lock->release();

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function push(string $uri, array $value): void
    {
        $lock = $this->lockFactory->createLock('urimap-push');
        $lock->acquire(true);

        if ($this->redis->hExists($uri, $this->statusKey)) {
            $lock->release();

            return;
        }

        $this->redis->hMset($uri, $value);
        $lock->release();
    }

    /**
     * {@inheritdoc}
     */
    public function distinct(array $uris, array $value): array
    {
        $lock = $this->lockFactory->createLock('urimap-distinct');
        $lock->acquire(true);

        $filteredUris = [];
        foreach ($uris as $uri) {
            if (!$this->redis->hExists($uri, $this->statusKey)) {
                $this->redis->hMset($uri, $value);
                $filteredUris[] = $uri;
            }
        }

        $lock->release();

        return $filteredUris;
    }

    /**
     * @return int
     */
    public function getCurrentDBIndex(): int
    {
        return  $this->redis->client('list')[0]['db'];
    }

    /**
     * Switch to DB with $index.
     *
     * @param int $index
     */
    public function selectCurrentDB(int $index): void
    {
        $this->redis->select($index);
    }

    /**
     * Cleanup current Redis DB.
     */
    public function flushCurrentDB(): void
    {
        $this->redis->flushDb();
    }
}
