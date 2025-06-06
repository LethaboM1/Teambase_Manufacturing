#!/bin/bash
# alias php='/usr/local/bin/ea-php83'
git stash
git pull origin master
git pop
php artisan migrate
php artisan optimize:clear
php artisan optimize
