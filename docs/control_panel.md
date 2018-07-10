## Control Panel

**Control Panel** is a basic web UI interface to schedule, add, delete and show statistics for website crawling jobs. It extends [**WebsiteCrawler**](website_crawler.md) with multiprocess capabilities via [Enqueue project](https://enqueue.forma-pro.com/).

Application is based on Laravel 5.6 starter project with default directory structure and features set.
Application workflow as follows:
> Visit `http://localhost:8000` --> Add new **URI** --> Start crawling job for selected **URI** --> Press on **URI** name to visit statistics page.

**Enqueue project** is used in both Laravel Queue as interop driver and in `EnqueueUriExecutor` as `\Enqueue\SimpleClient\SimpleClient`.

Once new crawling job is triggered via web UI, `\App\Jobs\ProcessCrawler` is called, which starts the actual crawler. **WebsiteCrawler** configuration happens at `\App\Providers\WebsiteCrawlerServiceProvider`.

**app/WebsiteCrawler** directory contains multiprocess extension for the crawler library.

```
app/WebsiteCrawler
├── Context.php
├── Enqueue
│   └── Processor
│       └── UriExecutorProcessor.php
├── Executor
│   └── EnqueueUriExecutor.php
└── Processor
    ├── HtmlProcessor.php
    └── ImageProcessor.php
```

where
* `\App\WebsiteCrawler\Context` extends `\WebsiteCrawler\Context` to introduce **websiteId** property, which is used to synchronize multiple crawler process per website.
* `\App\Enqueue\Processor\UriExecutorProcessor` is a part of Enqueue terminology and a equivalent of Laravel Job executor, configured to listen for uri processing event and calls a new `\WebsiteCrawler\Client` instance per each event.
* `\App\Executor\EnqueueUriExecutor` implements `\WebsiteCrawler\Executor\ExecutorInterface`. Main executors feature is that it sends each new found **uri** for processing via enqueue event.
* `\App\Processor\HtmlProcessor` saves crawled htmls to FS.
* `\App\Processor\ImageProcessor` saves crawled images to FS.
