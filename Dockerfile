FROM php:8.3-apache

RUN docker-php-ext-install mysqli && a2enmod rewrite

COPY . /var/www/html/
COPY docker/start.sh /usr/local/bin/start-railway

RUN chmod +x /usr/local/bin/start-railway \
    && mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && printf '%s\n' \
      '<Directory /var/www/html/uploads>' \
      'Options -Indexes' \
      'php_admin_flag engine off' \
      '</Directory>' > /etc/apache2/conf-available/uploads-security.conf \
    && a2enconf uploads-security

EXPOSE 8080
CMD ["/usr/local/bin/start-railway"]
