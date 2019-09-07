#!/usr/bin/env bash

docker-compose exec metadata_service_phpfpm php /www/artisan migrate $@
