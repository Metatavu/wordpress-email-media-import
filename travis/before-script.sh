#/bin/bash

BASE=`pwd`

# Setup MySQL
mysql -e 'create database wp;'

# Copy config
cp $BASE/travis/grunt-config.json $BASE/grunt-config.json

# Install wp-cli
mkdir -p /tmp/bin
curl -sS -o /tmp/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod a+x /tmp/bin/wp
export PATH="$PATH:/tmp/bin"

wp

# Setup grunt
npm install

# install wordpress 
grunt install-wordpress --verbose

# Start server
grunt start-server
grunt mock-data