FROM php:7.2.34-fpm-buster

LABEL maintainer="asabhi6776"

RUN apt update && apt install git -y

RUN git clone https://github.com/kerrytru/Screen-Squid.git

WORKDIR ./Screen-Squid

EXPOSE 8080

ENTRYPOINT [ "/usr/local/bin/php", "-S", "0.0.0.0:8080" ]