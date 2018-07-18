#!/bin/sh

cd /app && composer install

cp /app/config/autoload/bot.local.php.dist /app/config/autoload/bot.local.php
cp /app/config/autoload/db.local.php.dist /app/config/autoload/db.local.php
cp /app/config/autoload/session.local.php.dist /app/config/autoload/session.local.php

exec php-fpm
