## WebsiteCrawler Library

**WebsiteCrawler** is standalone crawling library. It features pluggable design, allowing to modify any aspect of the crawling logic.
This library makes use of [php-http](http://php-http.org/) project, which enables end user to employ any **php-http** complaint client.

Base units of functionality are as follows:
* `Client`
* `Result`
* `Context`
* `Crawler`
* `Executor`
* `Filter`
* `Processor`
* `UriMap`

Library Workflow looks like this:
> `Client` --> (`Executor`(`UriMap`) <--> `Crawler`) --> `Client` --> [`Filter`, `Processor`]


`Client` is the library instace, it holds configuration and a starting point of any crawl.

```php
use WebsiteCrawler\Client;

$client = new Client()
```

`Executor` will carry out instruction to execute `Crawler` and act on `Result` data, e.g. to start a next **uri** for execution.

```php
use WebsiteCrawler\Executor\RecursiveUriExecutor();
use WebsiteCrawler\UriMap\InMemoryUriMap();

// ...
$client->setExecutor(new RecursiveUriExecutor(new InMemoryUriMap()));
```

`Crawler` is an actual crawl/request logic holder, will it be existent `symfony/dom-crawler` based `HtmlCrawler` or any possible implementation like `JsonCrawler`, `RestApiCrawler`,`ExampleComCrawler` or any other `ResourceCrawler`.

```php
use WebsiteCrawler\Crawler\HtmlCrawler;

// ...
$client->setCrawler(new HtmlCrawler());
```

`Filter` is a data extractor class, that is meant to be working with **walker** (i.e. `symfony/dom-crawler`) to fill `Result` object, e.g. UriFilter will find all links on the page and will push them to the `Result`.

```php
use WebsiteCrawler\Filter\UriFilter;

// ...
$client->addFilter(new UriFilter());
// $client->removeFilter(new UriFilter());
```

`Processor` operates on bits of data at `Result` object, for example `ImageProcessor` will be downloading images.

```php
use WebsiteCrawler\Processor\EchoProcessor;

// ...
$client->addProcessor(new EchoProcessor());
// $client->removeProcessor(new EchoProcessor());
```

`Context` is shareable services holder, it's passed between all other classes calls. Default implementation contans HttpClient, MessageFactory and **walker**, which is resolves to `symfony/dom-crawler` in `HtmlCrawler`

```php
use WebsiteCrawler\Client;
use App\ImaginaryCustomContext;

$context = new ImaginaryCustomContext();
$client = new Client(null, null, ImaginaryCustomContext) // Optional parameter, it's a way to provide custom context.
```

`Result` is a processed data holder. Object is passed between `Executor --> Client --> [Filter, Processor]` calls.

`UriMap` is a extracted uris map, helps to determine which uri to process next and to distinct already processed ones.


### Usage reference

```php
use WebsiteCrawler\Client;
use WebsiteCrawler\Executor\RecursiveUriExecutor();
use WebsiteCrawler\Filter\UriFilter();
use WebsiteCrawler\Processor\EchoProcessor();
use WebsiteCrawler\UriMap\InMemoryUriMap();

$client = new Client()
$client->setExecutor(new RecursiveUriExecutor(new InMemoryUriMap()));
$client->setCrawler(new HtmlCrawler());
$client->addFilter(new UriFilter());
$client->addProcessor(new EchoProcessor());

$client->crawl('http://www.simplecloud.cn/');
```

### Classes reference

```
src
└── WebsiteCrawler
    ├── Client.php
    ├── Context.php
    ├── Crawler
    │   ├── CrawlerInterface.php
    │   ├── CrawlerTrait.php
    │   └── HtmlCrawler.php
    ├── Exception
    │   ├── Exception.php
    │   ├── InvalidWalkerException.php
    │   └── LogicException.php
    ├── Executor
    │   ├── CrossDomainRequestTrait.php
    │   ├── ExecutorInterface.php
    │   ├── RecursiveUriExecutor.php
    │   └── SingleUriExecutor.php
    ├── Filter
    │   ├── FilterInterface.php
    │   ├── FilterTrait.php
    │   ├── ImageFilter.php
    │   └── UriFilter.php
    ├── Processor
    │   ├── EchoProcessor.php
    │   ├── ProcessorInterface.php
    │   └── ProcessorTrait.php
    ├── Result.php
    └── UriMap
        ├── InMemoryUriMap.php
        ├── RedisLockingUriMap.php
        └── UriMapInterface.php
```
