FROM phpdockerio/php80-fpm:latest
WORKDIR "/application"
COPY php-ini-overrides.ini /etc/php/8.0/fpm/conf.d/

# Install selected extensions and other stuff
RUN apt-get clean \
  && apt-get update \
  && apt-get -y --no-install-recommends install php8.0-mysql php8.0-gd libmcrypt-dev \
  && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
