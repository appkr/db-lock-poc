#!/usr/bin/env bash

if [ ! -d $MYSQL_DATA_DIR/mysql ]; then
    if ( ps aux | grep "[/]usr/bin/supervisorctl" ); then
        /usr/bin/supervisorctl stop all;
    fi;

    rm -rf $MYSQL_DATA_DIR/* \
        && mkdir -p $MYSQL_PID_DIR \
        && chmod 777 $MYSQL_PID_DIR \
        && usermod -d $MYSQL_DATA_DIR mysql \
        && chown -R mysql:mysql $MYSQL_DATA_DIR $MYSQL_PID_DIR \
        && /usr/sbin/mysqld --user=mysql --initialize-insecure;

    /usr/bin/supervisorctl restart all;

    /usr/bin/mysql -v -e "CREATE USER 'root'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}'; GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'; FLUSH PRIVILEGES;";

    /usr/bin/mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql mysql;
fi
