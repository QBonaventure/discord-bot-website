#!/bin/sh

#USER_ID=${LOCAL_USER_ID:-9001}
cd /app && composer install

cp /app/config/autoload/bot.local.php.dist /app/config/autoload/bot.local.php
cp /app/config/autoload/db.local.php.dist /app/config/autoload/db.local.php
cp /app/config/autoload/session.local.php.dist /app/config/autoload/session.local.php

sed -i "s/'owner_id' => ''/'owner_id' => '$FTCBOT_OWNER_ID'/g" /app/config/autoload/bot.local.php
sed -i "s/'guild_id' => ''/'guild_id' => '$FTCBOT_GUILD_ID'/g" /app/config/autoload/bot.local.php

sed -i "s/'host' => ''/'host' => '$FTCBOT_DB_HOST'/g" /app/config/autoload/db.local.php
sed -i "s/'port' => ''/'port' => '$FTCBOT_DB_PORT'/g" /app/config/autoload/db.local.php
sed -i "s/'user' => ''/'user' => '$FTCBOT_DB_USER'/g" /app/config/autoload/db.local.php
sed -i "s/'password' => ''/'password' => '$FTCBOT_DB_PASSWORD'/g" /app/config/autoload/db.local.php
sed -i "s/'database' => ''/'database' => '$FTCBOT_DB_DBNAME'/g" /app/config/autoload/db.local.php

sed -i "s/'server' => ''/'server' => '$FTCBOT_DB_CACHE_SERVER'/g" /app/config/autoload/db.local.php
sed -i "s/'version' => ''/'version' => '$FTCBOT_DB_CACHE_VERSION'/g" /app/config/autoload/db.local.php

sed -i "s/'clientId' => ''/'clientId' => '$FTCBOT_DISCORD_CLIENT_ID'/g" /app/config/autoload/session.local.php
sed -i "s/'clientSecret' => ''/'clientSecret' => '$FTCBOT_DISCORD_TOKEN'/g" /app/config/autoload/session.local.php
sed -i "s~'redirectUri' => ''~'redirectUri' => '$FTC_WEBSITE_SESSION_REDIRECT_URI'~g" /app/config/autoload/session.local.php
sed -i "s/'key' => ''/'key' => '$FTC_WEBSITE_SESSION_KEY'/g" /app/config/autoload/session.local.php


exec php-fpm
