FROM php:8.3-fpm-alpine3.19 AS php-dev

RUN docker-php-ext-install mysqli
ENTRYPOINT [ "php", "/src/test.php" ]
