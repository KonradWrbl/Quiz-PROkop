FROM php:7.4-fpm
RUN apt-get update && apt-get install -y --no-install-recommends \
        git zlib1g-dev libzip-dev libxml2-dev wget poppler-utils libonig-dev

RUN docker-php-ext-install mbstring pdo_mysql zip

# PHPUnit
RUN wget https://phar.phpunit.de/phpunit-7.4.phar
RUN chmod +x phpunit-7.4.phar
RUN mv phpunit-7.4.phar /usr/local/bin/phpunit

# Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# COPY FILES
COPY . /var/www/quiz
WORKDIR /var/www/quiz/

# install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini