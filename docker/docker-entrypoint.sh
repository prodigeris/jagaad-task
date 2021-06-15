#!/usr/bin/env bash

composer install --no-scripts --no-autoloader
composer dump-autoload -o

./bin/app
