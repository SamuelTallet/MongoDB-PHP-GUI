#!/bin/bash
container_id=$(docker ps -qf "name=mongodb-php-gui")
docker cp ${container_id}:/app/vendor ./vendor
