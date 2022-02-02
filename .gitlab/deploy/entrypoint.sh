#! /bin/sh

supervisord -c /etc/supervisord.conf & composer du & exec php-fpm -F -R & exec /usr/sbin/crond -f -l 8