## Introduction

PHP Crawler is an example Laravel5.6 based web page crawler project. It consist of two logical modules, WebsiteCrawler (`src/WebsiteCrawler`) library and Laravel App (`app`) with basic Control Panel and extension of WebsiteCrawler (`app/WebsiteCrawler`) for multiprocess action via Enqueue project.

* [Control Panel](control_panel.md)
* [WebsiteCrawler](website_crawler.md)

### Concerns
* Laravel Queue works via Enqueue, but runs via FS driver. (Doesn't matter as Crawler uses separate RabbitMQ queue).
* Executing multiple website crawlers at the same time is not supported yet. (RedisLockingUriMap needs improving to support different DB access parallely).
* ImageProcessor doesn't distinct images uris and redownloads them all.
