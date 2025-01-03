#!/bin/bash -e
# Set the directory permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Start PHP-FPM as a foreground process
/etc/init.d/php8.2-fpm start -F
service cron start

# Execute the given command (this is crucial for keeping the container running)
exec "$@"
