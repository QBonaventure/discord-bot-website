FROM php:7.2-fpm-alpine

RUN apk --update --no-cache add \
    postgresql-dev; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    docker-php-ext-install bcmath pgsql pdo pdo_pgsql && \
     sed -i '/phpize/i \
    [[ ! -f "config.m4" && -f "config0.m4" ]] && mv config0.m4 config.m4' \
    /usr/local/bin/docker-php-ext-configure; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    mkdir /app && \
    rm -rf /var/cache/apk/*

WORKDIR /app

COPY ./src/ /app/src/
COPY ./public/ /app/public/
COPY ./config/ /app/config/
COPY ./composer.* /app/
COPY ./data/ /app/data/
COPY entrypoint.sh /

RUN  cd /app && composer install --no-dev && \
  rm /app/composer.json && rm /app/composer.lock

RUN cp /app/config/autoload/bot.local.php.dist /app/config/autoload/bot.local.php && \
    cp /app/config/autoload/db.local.php.dist /app/config/autoload/db.local.php && \
    cp /app/config/autoload/session.local.php.dist /app/config/autoload/session.local.php && \
    cp /app/vendor/qbonaventure/discord-website-trello/config/trello.local.php.dist /app/config/autoload/trello.local.php && \
    cp /app/vendor/qbonaventure/discord-website-pushover/config/pushover.local.php.dist /app/config/autoload/pushover.local.php

RUN chmod +x /entrypoint.sh && \
  chmod a+w /app/data/cache/
  

  
ENTRYPOINT ["/entrypoint.sh"]
