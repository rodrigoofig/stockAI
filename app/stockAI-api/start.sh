#!/bin/sh
set -e

# Executar o script de criação do projeto
/usr/local/bin/check-and-create-symfony.sh

# Iniciar PHP-FPM em background
php-fpm &

# Iniciar Nginx em primeiro plano
nginx -g "daemon off;"