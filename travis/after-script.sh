#/bin/bash

cat /tmp/coverage.xml
vendor/satooshi/php-coveralls/bin/coveralls -x /tmp/coverage.xml