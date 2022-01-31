#!/bin/bash

cd /var/www/html
git clone https://github.com/kerrytru/Screen-Squid.git
cd Screen-Squid
/usr/local/bin/php -S 0.0.0.0:8080