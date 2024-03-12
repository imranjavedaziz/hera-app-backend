#!/usr/bin/env bash

set -e

BASEDIR=$(dirname "$0")
cd "${BASEDIR}"/../

# switch the relevant branch and pull the latest changes
git fetch origin
git checkout stage
git pull origin

sudo docker-compose -f staging.yml down
sudo docker system prune -af
sudo docker-compose -f staging.yml build
sudo docker-compose -f staging.yml up -d
sudo docker exec -t mbc-staging-app composer install
sudo docker exec -t mbc-staging-app composer update
sudo docker exec -t mbc-staging-app php artisan cache:clear
sudo docker exec -t mbc-staging-app php artisan config:clear
sudo docker exec -t mbc-staging-app php artisan migrate
sudo docker exec -t mbc-staging-app service cron stop
sudo docker exec -t mbc-staging-app service cron start
sudo docker exec -t mbc-staging-app php artisan l5-swagger:generate
sudo chmod -R 777 storage/
sudo chmod -R 777 bootstrap/
sudo chown -R www-data:www-data storage/framework/cache/data
sudo chmod -R 777 storage/logs
sudo chown -R ubuntu:ubuntu storage/logs