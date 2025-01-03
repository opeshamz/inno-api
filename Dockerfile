FROM ubuntu:20.04 as base

ENV PHP_VERSION 8.2

# Install system dependencies
RUN apt-get update && apt-get install -y \
    sudo \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libxslt-dev \
    zip \
    unzip \
    wget \
    cron \
    default-mysql-client \
    software-properties-common \
    curl && \
    add-apt-repository -y ppa:ondrej/php && \
    apt-get update


# Install PHP and required extensions
RUN apt-get install -y \
    php${PHP_VERSION} \
    php${PHP_VERSION}-dev \
    php${PHP_VERSION}-cgi \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-common \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-soap \
    php${PHP_VERSION}-ldap \
    php${PHP_VERSION}-mcrypt \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-readline \
    php${PHP_VERSION}-xsl \
    php${PHP_VERSION}-opcache \
    php${PHP_VERSION}-xmlrpc \
    php${PHP_VERSION}-odbc \
    php${PHP_VERSION}-pdo

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Get latest Composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:2.5.5 /usr/bin/composer /usr/bin/composer

RUN sed -i 's/memory_limit = .*/memory_limit = 2048M/' /etc/php/8.2/fpm/php.ini

# Set working directory
RUN mkdir -p /var/www
WORKDIR /var/www

# Copy application files and .env
COPY .env /var/www/.env
COPY . /var/www

RUN composer install

# Expose the port for access


# Grant execute access to entrypoint.sh
COPY ./docker/entrypoint.sh /docker/entrypoint.sh
RUN chmod +x /docker/entrypoint.sh
RUN chown root:root /docker/entrypoint.sh

# Copy crontab and entrypoint-cron.sh
COPY ./docker/cron/crontab /docker/crontab
COPY ./docker/cron/entrypoint-cron.sh /docker/entrypoint-cron.sh
RUN chmod +x /docker/entrypoint-cron.sh


# Set the cron job
RUN echo "* * * * * cd /var/www && php artisan schedule:run >> /var/www/cron_test.log 2>&1" > /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron
RUN crontab /etc/cron.d/laravel-cron

# Set the entrypoint
ENTRYPOINT ["/docker/entrypoint.sh"]
 # Run comand

EXPOSE 8000

CMD sh -c "\
until mysqladmin ping -h db --silent; do \
    echo 'Waiting for MySQL...'; \
    sleep 2; \
done; \
echo 'MySQL is ready!'; \
php artisan migrate && \
php artisan serve --host=0.0.0.0 --port=8000 && \
php-fpm -F"


