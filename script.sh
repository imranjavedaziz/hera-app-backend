#!/bin/sh

ps -aux | grep work | grep -v grep
if [ $? -ne 1 ]
then echo " process already running"	
else
echo " executing worker command"
php8.0 /var/www/html/artisan queue:work &
fi
