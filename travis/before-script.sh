#/bin/bash

BASE=`pwd`

# Copy config
cp $BASE/travis/grunt-config.js $BASE/grunt-config.js

# Install wp-cli
mkdir -p /tmp/bin
curl -sS -o /tmp/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod a+x /tmp/bin/wp
export PATH="$PATH:/tmp/bin"

# Setup grunt
npm install

# install wordpress 
grunt install-wordpress

# Start server
grunt start-server
grunt mock-data
