#!/bin/bash

echo "CREATE DATABASE IF NOT EXISTS ${MYSQL_DATABASE};" | mysql -u root -p"${MYSQL_ROOT_PASSWORD}"
echo "CREATE DATABASE IF NOT EXISTS ${MYSQL_TEST_DATABASE};" | mysql -u root -p"${MYSQL_ROOT_PASSWORD}"

echo "GRANT ALL ON *.* TO '${MYSQL_USER}'@'%';" | mysql -u root -p"${MYSQL_ROOT_PASSWORD}"
