## PHP-test-v2-Cavaon
[![Build Status](https://travis-ci.org/dustinhsiao21/PHP-test-v2-Cavaon.svg?branch=master)](https://travis-ci.org/dustinhsiao21/PHP-test-v2-Cavaon)
![StyleCI](https://github.styleci.io/repos/200037175/shield?branch=master)

![demo](./public/images/demo.png)

### install

##### version
PHP: 7.2
Laravel: 5.8

##### script

```
cp .env.example .env //remember to setup database config
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run dev
php artisan serve
```

### test
```
./vendor/bin/phpunit
```
