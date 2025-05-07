#!/bin/sh

# Install dependencies
apt-get update && apt-get install -y git unzip

# Download and install tideways extension
mkdir -p /tmp/tideways
cd /tmp/tideways
wget https://github.com/tideways/php-xhprof-extension/archive/v5.0.4.tar.gz
tar -zxvf v5.0.4.tar.gz
cd php-xhprof-extension-5.0.4
phpize
./configure
make
make install

# Add extension configuration
echo "extension=tideways_xhprof.so" > /usr/local/etc/php/conf.d/tideways_xhprof.ini
echo "tideways_xhprof.clock_use_rdtsc=0" >> /usr/local/etc/php/conf.d/tideways_xhprof.ini