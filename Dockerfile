FROM php:8.2-apache

# Instala drivers MySQL e habilita mod_rewrite (necessário para o .htaccess do front controller)
RUN docker-php-ext-install mysqli pdo_mysql \
    && a2enmod rewrite

# Aponta o DocumentRoot para public/ — apenas essa pasta fica exposta via HTTP
# Arquivos fora dela (config/, app/) são inacessíveis por URL
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' \
        /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/>|<Directory /var/www/html/public>|g' \
        /etc/apache2/apache2.conf \
    && sed -i 's|AllowOverride None|AllowOverride All|g' \
        /etc/apache2/apache2.conf

# Copia todo o projeto para dentro do container
COPY . /var/www/html/

# Permissões corretas para o servidor web
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
