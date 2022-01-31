FROM php:7.2.34-fpm-buster

LABEL maintainer="asabhi6776"

RUN apt update && apt install git -y

COPY script.sh .

EXPOSE 8080

ENTRYPOINT [ "/bin/sh", "script.sh" ]