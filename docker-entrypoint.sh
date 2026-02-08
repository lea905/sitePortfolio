#!/bin/bash
set -e

# Fix Apache MPM: remove conflicting modules
rm -f /etc/apache2/mods-enabled/mpm_event.*
rm -f /etc/apache2/mods-enabled/mpm_worker.*

# Ensure prefork is enabled (required for PHP)
# We use -f to avoid error if already enabled
a2enmod -q mpm_prefork || a2enmod mpm_prefork

# Configure Apache to listen on the PORT environment variable if set (Railway)
if [ -n "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/" /etc/apache2/sites-available/*.conf
fi

# Execute the original entrypoint
exec docker-php-entrypoint "$@"
