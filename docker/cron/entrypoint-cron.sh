#!/bin/bash -e


#Set directory permission
chmod -R 775 storage
chmod -R 775 bootstrap/cache

cp ./docker/crontab /etc/cron.d/app
chmod 0644 /etc/cron.d/app
crontab /etc/cron.d/app

#exec docker command
exec "$@"
