FROM php:8.3-apache

RUN a2enmod rewrite

RUN { \
        echo 'display_errors = On'; \
        echo 'error_reporting = E_ALL'; \
        echo 'log_errors = On'; \
    } > /usr/local/etc/php/conf.d/dev.ini

EXPOSE 80
