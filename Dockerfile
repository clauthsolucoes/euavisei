# Imagem base com PHP, Composer e extensões comuns
FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Gerar cache de config, rotas e views
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expor a porta usada pelo Laravel
EXPOSE 8000

RUN ln -sf /dev/stderr /var/www/html/storage/logs/laravel.log

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Comando para iniciar o servidor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
