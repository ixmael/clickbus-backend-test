FROM clickbus_backend_base:latest

LABEL maintainer="iramosmerlos@outlook.com"
LABEL description="Image to build the ClickBus Backend Test"

COPY ./docker/prod.build/setup.sh /usr/local/bin/setup.sh
RUN chmod +x /usr/local/bin/setup.sh
RUN setup.sh
RUN rm /usr/local/bin/setup.sh

COPY ./docker/prod.build/restapi.prod.env /home/clickbus-backend/.env

COPY ./docker/prod.build/build.sh /usr/local/bin/build.sh
RUN chmod +x /usr/local/bin/build.sh
