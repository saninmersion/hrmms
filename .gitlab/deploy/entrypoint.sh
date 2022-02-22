#! /bin/sh

supervisord -c /etc/supervisord.conf & composer du & php artisan cache:clear & php artisan config:clear & php artisan view:clear & php artisan route:clear & exec php-fpm -F -R & exec /usr/sbin/crond -f -l 8
