#!/bin/sh

cd /app && composer install --no-dev

cp /app/config/autoload/bot.local.php.dist /app/config/autoload/bot.local.php
cp /app/config/autoload/db.local.php.dist /app/config/autoload/db.local.php
cp /app/config/autoload/session.local.php.dist /app/config/autoload/session.local.php
cp /app/vendor/qbonaventure/discord-website-trello/config/trello.local.php.dist /app/config/autoload/trello.local.php
cp /app/vendor/qbonaventure/discord-website-pushover/config/pushover.local.php.dist /app/config/autoload/pushover.local.php

exec php-fpm
