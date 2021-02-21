FROM php:7.4-fpm

SHELL ["/bin/bash", "-c"]

# install composer
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer
RUN composer self-update

RUN curl -sL https://deb.nodesource.com/setup_15.x | bash -

RUN apt-get update \
&& apt-get install -y \
git \
zip \
unzip \
vim \
libpq-dev \
nodejs

RUN apt-get update \
    && pecl install xdebug \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable xdebug

# install laravel
RUN composer global require laravel/installer
RUN echo "export PATH=~/.composer/vendor/bin:$PATH" >> ~/.bash_profile

# WORKDIR /var/www/html
# RUN ~/.composer/vendor/bin/laravel --jet new wk
# WORKDIR /var/www/html/wk
# RUN composer require laravel/ui