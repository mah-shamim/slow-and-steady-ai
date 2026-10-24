FROM php:5.6-apache
RUN a2enmod headers && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
