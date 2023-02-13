FROM laradock/php-fpm:latest-7.4

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

# Configure non-root user.
RUN groupmod -o -g 1000 www-data && \
    usermod -o -u 1000 -g www-data www-data


CMD ["php-fpm"]
EXPOSE 9000