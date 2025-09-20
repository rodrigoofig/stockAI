FROM php:8.3-fpm

# Instalar dependências
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev \
    nginx

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Nginx
WORKDIR /app
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# Configurar diretório de trabalho

# Copiar script de inicialização
COPY check-and-create-symfony.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/check-and-create-symfony.sh

# Script de inicialização
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

WORKDIR /var/www/html

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]