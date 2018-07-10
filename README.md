# PHP Crawler

实验楼 - 楼+之PHP7实战爬虫代码

学习课程请进入：[https://www.shiyanlou.com/louplus/php](https://www.shiyanlou.com/louplus/php)

## Installation

Minimal docker version is 17+.

```bash
> docker-compose up -d
> docker-compose exec web composer install
> docker-compose exec web php artisan migrate
```

Start up
```bash
> docker-compose exec web php artisan queue:work interop -vvv
> docker-compose exec web php artisan enqueue:consume -vvv --setup-broker # Start as many instances of this service, as you would like processes for crawl.
```

Visit `http://localhost:8000/`

Check "storage/app/crawler" for result.

## Resources

* [Documentation](docs/index.md)
