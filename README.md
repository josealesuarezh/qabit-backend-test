# Technical test for back-end profile(Laravel)

## To run in local


* In the project root directory run
```
$ composer install
$ php artisan migrate --seed
$ php artisan serve
```

* To manage the jobs on local open a new terminal and run
```
$ php rtisn queue:work
```

## To run the tests
```
$ php vendor/bin/phpunit
```

## Available endpoints
+-----------+-----------------------------+-----------------+
| GET|HEAD  | api/products                | index           |
| POST      | api/products                | store           |
| GET|HEAD  | api/products/create         | create          |
| POST      | api/products/subscribe      | toggleSubscribe |
| GET|HEAD  | api/products/{product}      | show            |
| PUT|PATCH | api/products/{product}      | update          |
| DELETE    | api/products/{product}      | destroy         |
| GET|HEAD  | api/products/{product}/edit | edit            |
+-----------+-----------------------------+-----------------+
