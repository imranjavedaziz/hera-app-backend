#FROM kiwidevops/php:php-8.0
FROM php:php-8.0

COPY . /var/www/html
WORKDIR /var/www/html
RUN sed -i "s/upload_max_filesize = .*/upload_max_filesize = 150M/" /etc/php/8.0/fpm/php.ini
RUN sed -i "s/max_execution_time = .*/max_execution_time = 2800/" /etc/php/8.0/fpm/php.ini
RUN sed -i "s/post_max_size = .*/post_max_size = 250M/" /etc/php/8.0/fpm/php.ini
RUN sed -i "s/memory_limit = .*/memory_limit = 350M/" /etc/php/8.0/fpm/php.ini

RUN composer update && \ 
 apt-get update && \
 apt-get install -y software-properties-common

# Install "ffmpeg"
#RUN add-apt-repository ppa:jonathonf/ffmpeg-4 -y
RUN apt-get update && apt-get install ffmpeg -y


RUN php artisan route:clear && \
php artisan migrate && \
php artisan config:cache && \
php artisan l5-swagger:generate

RUN chmod -R 777 /var/www/html/storage/

##Install Cron
RUN apt-get -y install cron
ADD crontab /etc/cron.d/appcrons
RUN chmod 0644 /etc/cron.d/appcrons
RUN crontab /etc/cron.d/appcrons

##Install queue:work
ADD script.sh /var/ww/html/script.sh
RUN chmod +x /var/ww/html/script.sh

EXPOSE 9000
CMD ["php-fpm8.0"]
ENTRYPOINT cron start && php-fpm8.0
