<?php

namespace App\Providers;

use App\WebsiteCrawler\Context;
use App\WebsiteCrawler\Executor\EnqueueUriExecutor;
use App\WebsiteCrawler\Enqueue\Processor\UriExecutorProcessor;
use App\WebsiteCrawler\Processor\HtmlProcessor;
use App\WebsiteCrawler\Processor\ImageProcessor;
use Enqueue\SimpleClient\SimpleClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use \Redis;
use WebsiteCrawler\Client;
use WebsiteCrawler\Crawler\HtmlCrawler;
use WebsiteCrawler\Filter\UriFilter;
use WebsiteCrawler\Filter\ImageFilter;
use WebsiteCrawler\UriMap\RedisLockingUriMap;

class WebsiteCrawlerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving(SimpleClient::class, function (SimpleClient $client, $app) {
            $client->bind('app_website_crawler_uri_executor', 'uriExecutorProcessor', new UriExecutorProcessor());

            return $client;
        });

        $this->app->bind('WebsiteCrawler\HtmlProcessor', function ($app) {
            return new HtmlProcessor(Storage::disk('crawler'));
        });
        $this->app->bind('WebsiteCrawler\ImageProcessor', function ($app) {
            return new ImageProcessor(Storage::disk('crawler'));
        });
        $this->app->bind('WebsiteCrawler\Client', function ($app) {
            $client = new Client(null, null, new Context());
            $client->setCrawler(new HtmlCrawler());

            $redis = new Redis(); // We're using Redis directly as Laravel Redis Facade doesn't allow to get low-level connection.
            $redis->connect(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', 6379)); // @TODO: Replace with config()?
            $redis->auth(env('REDIS_PASSWORD', null));

            $client->setExecutor(new EnqueueUriExecutor(new RedisLockingUriMap($redis), resolve(SimpleClient::class)));

            $client->addFilter(new UriFilter());
            $client->addFilter(new ImageFilter());
            $client->addProcessor($app->make('WebsiteCrawler\HtmlProcessor'));
            $client->addProcessor(resolve('WebsiteCrawler\ImageProcessor'));

            return $client;
        });
    }
}
