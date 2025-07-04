FROM php:8.4-cli-alpine

WORKDIR /app
COPY . /app

RUN echo "; PHP settings added by MongoDB PHP GUI (MPG)" > /usr/local/etc/php/conf.d/mpg-docker-php.ini \
  && echo "upload_max_filesize = 25M" >> /usr/local/etc/php/conf.d/mpg-docker-php.ini \
  && echo "post_max_size = 25M" >> /usr/local/etc/php/conf.d/mpg-docker-php.ini \
  && apk update && apk add --no-cache --virtual .build-deps autoconf build-base openssl-dev curl \
  && pecl install mongodb-2.1.0 && docker-php-ext-enable mongodb \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && apk del .build-deps \
  && composer install \
  && apk add --no-cache openjdk8-jre

EXPOSE 5000
CMD ["php", "-S", "0.0.0.0:5000"]
