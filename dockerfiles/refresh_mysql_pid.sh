#!/usr/bin/env bash

if [ ! -d $MYSQL_PID_DIR ]; then
    /usr/bin/supervisorctl stop mysql;

    mkdir -p $MYSQL_PID_DIR;
    chmod 777 $MYSQL_PID_DIR;
    chown -R mysql:mysql $MYSQL_PID_DIR;

    /usr/bin/supervisorctl start mysql;
fi