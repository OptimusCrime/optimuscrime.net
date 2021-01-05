FROM php:8.0.0-cli-buster

ENV TZ=Europe/Oslo

RUN cd /usr/src \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && apt-get update \
    && apt-get install -y zlib1g-dev \
                          libzip-dev \
                          zip \
                          libjpeg-dev \
                          libpng-dev \
                          libzip-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install zip gd \
    && echo $TZ > /etc/timezone \
    && mkdir /site

EXPOSE 8080

WORKDIR /site

COPY . /site

CMD ["php", "-S", "0.0.0.0:8080", "-t", "entrypoint", "entrypoint/index.php"]
