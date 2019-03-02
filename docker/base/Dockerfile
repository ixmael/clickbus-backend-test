FROM alpine:latest

LABEL version="2019-02-28"

LABEL maintainer="iramosmerlos@outlook.com"
LABEL description="Base image for the ClickBus Backend Test"

RUN mkdir -p /home/clickbus-backend/service-restapi
WORKDIR /home/clickbus-backend/service-restapi

COPY ./docker/base/setup.sh /usr/local/bin/setup.sh
RUN chmod +x /usr/local/bin/setup.sh
RUN setup.sh
RUN rm /usr/local/bin/setup.sh
