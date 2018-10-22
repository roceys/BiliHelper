FROM php:alpine

MAINTAINER roceys <szlszl35622@gmail.com>

ENV USER_NAME='' \
    USER_PASSWORD='' \
    CONIFG_PATH='/app/conf/user.conf'

WORKDIR /app

RUN docker-php-ext-install sockets

#RUN curl -o composer.phar https://getcomposer.org/download/1.7.2/composer.phar \
#    && chmod +x composer.phar

RUN apk add --no-cache git && \
    git clone https://github.com/lkeme/BiliHelper.git /app && \
    #php -r "copy('https://install.phpcomposer.com/installer', 'composer-setup.php');" && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php composer.phar install && \
    cp /app/conf/user.conf.example /app/conf/user.conf && \
    rm -r /var/cache/apk && \
    rm -r /usr/share/man

ENTRYPOINT git pull && \
           php composer.phar install && \
           sed -i ''"$(cat /app/conf/user.conf -n | grep "APP_USER=" | awk '{print $1}')"'c '"$(echo "APP_USER=${USER_NAME}")"'' ${CONIFG_PATH} && \
           sed -i ''"$(cat /app/conf/user.conf -n | grep "APP_PASS=" | awk '{print $1}')"'c '"$(echo "APP_PASS=${USER_PASSWORD}")"'' ${CONIFG_PATH} && \
           php index.php 
