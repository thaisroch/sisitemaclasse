FROM  php:7.2-apache

WORKDIR  /var/www/html/public/

COPY . /var/www/html/

LABEL version="1.0" description="Criando a imagem do projeto" maintainer="Thais Rocha<rochathais.ml@gmail.com>"

RUN cd / && mkdir Arquivos && chmod 777 -R Arquivos/

VOLUME /Arquivos/

EXPOSE 80

RUN apt-get update

RUN docker-php-ext-install mysqli

RUN docker-php-ext-install pdo_mysql

