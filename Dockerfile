FROM php:8.2-apache
LABEL maintainer="Jeremy Echols <jechols@uoregon.edu>"

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY src /var/www/html
COPY src/include/configuration_sample.php /var/www/html/include/configuration.php
