#/bin/bash

BASE=`pwd`

# Install wp-cli
mkdir -p /tmp/bin
curl -sS -o /tmp/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod a+x /tmp/bin/wp
export PATH="$PATH:/tmp/bin"