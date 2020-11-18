FROM php:7.4-cli-alpine
RUN apk update && apk upgrade

# Clone project repository.
RUN apk add --no-cache git
WORKDIR /opt/mongodb-php-gui
RUN git clone https://github.com/SamuelTS/MongoDB-PHP-GUI.git .

# Enable MongoDB PHP ext.
RUN apk add --no-cache autoconf build-base curl-dev openssl-dev
RUN pecl install mongodb-1.8.2 && docker-php-ext-enable mongodb

# Install PHP dependencies.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Start PHP built-in server.
EXPOSE 5000
CMD ["php", "-S", "0.0.0.0:5000"]
