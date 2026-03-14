FROM php:8.2-apache

# Instala extensões necessárias para o MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilita o mod_rewrite do Apache (comum para APIs PHP)
RUN a2enmod rewrite

# Copia os arquivos do seu projeto para dentro do servidor
COPY . /var/www/html/

# Ajusta as permissões para a pasta public
RUN chown -R www-data:www-data /var/www/html

# Define a pasta raiz do Apache como a sua pasta public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80