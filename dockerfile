# Use uma imagem base do PHP com Apache
FROM php:8.2-apache

# Copie todos os arquivos do projeto para o diretório padrão do Apache
COPY . /var/www/html

# Instale extensões necessárias para seu projeto
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Exponha a porta 80 para o servidor
EXPOSE 80
