FROM clickbus_backend_base:latest

LABEL maintainer="iramosmerlos@outlook.com"
LABEL description="Image to develop the ClickBus Backend Test"

COPY ./docker/dev.server/setup.sh /usr/local/bin/setup.sh
RUN chmod +x /usr/local/bin/setup.sh && setup.sh
RUN chmod 777 /usr/local/bin/setup.sh && rm /usr/local/bin/setup.sh

# Add nginx configuration
RUN mkdir -p /var/run/nginx
COPY ./docker/dev.server/config/default.conf /etc/nginx/conf.d/

# Add php-fpm configuration
RUN mkdir -p /var/run/php7-fpm
COPY ./docker/dev.server/config/clickbus.conf /etc/php7/php-fpm.d/

# forward request and error logs to docker log collector
#RUN ln -sf /dev/stdout /var/log/nginx/access.log \
#	&& ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80 443
#STOPSIGNAL SIGTERM
CMD ["nginx", "-g", "daemon off;"]
