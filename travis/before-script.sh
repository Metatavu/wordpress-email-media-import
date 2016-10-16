#/bin/bash

# Install wp-cli
curl -sS -o /tmp/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod a+x /tmp/wp

# Setup nginx
nginx -c travis/nginx.conf

# Setup wp
mkdir /tmp/www
cd /tmp/www
/tmp/wp core download
/tmp/wp core config --dbname=www --dbuser=root
/tmp/wp core install --url=http://localhost --title=Test --admin_user=admin --admin_password=password --admin_email=admin@example.com

# Just test
curl http://localhost:8080