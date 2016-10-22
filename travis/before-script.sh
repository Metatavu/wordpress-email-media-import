#/bin/bash

php --info | grep xdebug

BASE=`pwd`

# Install dependencies
composer install
composer require satooshi/php-coveralls

# Setup MySQL
mysql -e 'create database wp;'

# Copy config
cp $BASE/travis/grunt-config.json $BASE/tests/grunt-config.json

# Install wp-cli
mkdir -p /tmp/bin
curl -sS -o /tmp/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod a+x /tmp/bin/wp
export PATH="$PATH:/tmp/bin"

cd tests
# Setup grunt
npm install

# install wordpress 
grunt install-wordpress

# XDebug

export XDEBUG_CONFIG="remote_enable=on default_enable=on remote_autostart=on remote_host=localhost profiler_enable=1"

# Start server
grunt start-server
grunt mock-data