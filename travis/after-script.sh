#/bin/bash

echo Error log
cat /var/log/php_errors.log
vendor/satooshi/php-coveralls/bin/coveralls