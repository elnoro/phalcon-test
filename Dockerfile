FROM mileschou/phalcon:7.4-fpm-alpine

RUN docker-phalcon-install-devtools
RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT -1

