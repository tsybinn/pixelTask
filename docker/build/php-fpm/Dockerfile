FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        msmtp \
        nano\
       sendmail\
        gettext \
        wget \
        mc  \
        screen && \
        rm -r /var/lib/apt/lists/*
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install sockets
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli

RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /root/.bashrc && \
        composer

#  Install APCu and APC backward compatibility
 RUN pecl install channel://pecl.php.net/APCu-5.1.21 \
     && pecl install apcu_bc-1.0.3 \
     && docker-php-ext-enable apcu --ini-name 10-docker-php-ext-apcu.ini \
     && docker-php-ext-enable apc --ini-name 20-docker-php-ext-apc.ini

RUN usermod -u 1000 www-data
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
ADD php.ini /usr/local/etc/php/conf.d/php.ini

ADD fake_sendmail.sh /usr/bin/fake_sendmail.sh

RUN chmod 777 /usr/bin/fake_sendmail.sh
RUN chmod  777  /var/mail
