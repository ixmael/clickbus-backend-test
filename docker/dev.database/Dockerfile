FROM mysql:5.7

ENV MYSQL_ROOT_PASSWORD adminadmin
ENV MYSQL_DATABASE cash_machine
ENV MYSQL_USER clickbus
ENV DB_PASS 123456

COPY ./docker/dev.database/cashmachine_dump.sql /docker-entrypoint-initdb.d/

EXPOSE 3306
CMD ["mysqld"]
