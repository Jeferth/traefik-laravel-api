FROM php:8.2-cli

# Instalar dependências do sistema e extensões do PHP
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos da aplicação
COPY . .

# Instalar dependências Laravel
RUN composer install

# Dar permissões adequadas para as pastas do Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Expor porta 80 (artisan serve)
EXPOSE 80

# Comando padrão
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
