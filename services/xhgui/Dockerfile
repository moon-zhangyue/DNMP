FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libmongoc-1.0-0 \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /www/xhgui/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && git clone https://github.com/perftools/xhgui.git . \
    && composer install --no-dev \
    && chown -R www-data:www-data /www/xhgui/html

# 确保 MongoDB PHP 扩展已启用
RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini