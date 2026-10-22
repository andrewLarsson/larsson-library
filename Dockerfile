# syntax=docker/dockerfile:1
ARG PHP_IMAGE=php:8.5-apache
FROM ${PHP_IMAGE}
RUN docker-php-ext-install -j1 pdo_mysql
WORKDIR /opt/larsson
# ui is a separate, revision-pinned Compose build context.
COPY --from=ui /app/ ./www/
COPY conf/core/ ./conf/core/
COPY conf/database/statements/ ./conf/database/statements/
COPY library/ ./library/
COPY www/api/ ./www/api/
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/application.ini
COPY docker/entrypoint.sh /usr/local/bin/library-entrypoint
RUN chmod 0755 /usr/local/bin/library-entrypoint \
 && mkdir -p /opt/larsson/conf/database/conf.d \
 && printf 'ServerName localhost\n' >/etc/apache2/conf-available/servername.conf \
 && a2enconf servername \
 && printf 'StartServers 1\nMinSpareServers 1\nMaxSpareServers 2\nServerLimit 3\nMaxRequestWorkers 3\nMaxConnectionsPerChild 500\n' >/etc/apache2/mods-available/mpm_prefork.conf
EXPOSE 80
ENTRYPOINT ["library-entrypoint"]
CMD ["apache2-foreground"]
