# 基礎映像檔 (使用 PHP 8.2 + Apache)
FROM php:8.2-apache

# 安裝系統依賴與 PHP 必要擴充 (CI4 需要 intl, zip, gd 等)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl zip gd mysqli pdo_mysql

# 啟動 Apache 的 Rewrite 模組 (CI4 路由必備)
RUN a2enmod rewrite

# 設定 Apache 的 DocumentRoot 指向 CI4 的 public 資料夾
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 設定工作目錄
WORKDIR /var/www/html

# 複製專案程式碼到容器內
COPY . .

# 設定權限 (確保 Apache 可以寫入 writable 資料夾)
RUN chown -R www-data:www-data /var/www/html/writable

# 暴露 8080 埠
EXPOSE 8080