FROM httpd:latest

RUN apt-get -y update \
    && apt-get -y upgrade

COPY ./conf/default.conf /usr/local/apache2/conf/default.conf

RUN echo "Include /usr/local/apache2/conf/default.conf" >> /usr/local/apache2/conf/httpd.conf
