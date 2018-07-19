#!/bin/sh
su - www-data

exec php-fpm
