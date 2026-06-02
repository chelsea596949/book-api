# 基礎映像檔 (使用 PHP 8.2 + Apache)
FROM php:8.2-apache

# 安裝系統依賴與 PHP 必要擴充 (CI4 需要 intl, zip, gd 等)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    curl \
    git \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl zip gd mysqli pdo_mysql pcntl opcache sockets \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 安裝 Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 啟動 Apache 的 Rewrite 模組 (CI4 路由必備)
RUN a2enmod rewrite

# 設定 Apache 的 DocumentRoot 指向 CI4 的 public 資料夾
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 設定 Apache 連線設定
RUN echo "Listen 8080" >> /etc/apache2/ports.conf

# 設定 PHP 設定值 (用於生產環境)
RUN { \
    echo 'memory_limit = 256M'; \
    echo 'max_execution_time = 300'; \
    echo 'upload_max_filesize = 50M'; \
    echo 'post_max_size = 50M'; \
    echo 'date.timezone = "Asia/Taipei"'; \
    echo ''; \
    echo '# 安全性設定 - 隱藏錯誤顯示，防止洩漏敏感資訊'; \
    echo 'display_errors = Off'; \
    echo 'log_errors = On'; \
    echo 'error_log = /var/www/html/writable/logs/php_error.log'; \
    echo 'error_reporting = E_ALL'; \
    } > /usr/local/etc/php/conf.d/app.ini

# 設定工作目錄
WORKDIR /var/www/html

# 複製 composer 相關文件並安裝依賴
COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 複製專案程式碼到容器內
COPY . .

# 設定權限 (確保 Apache 可以寫入 writable 資料夾)
RUN mkdir -p /var/www/html/writable/logs && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/writable

# 暴露 8080 埠
EXPOSE 8080

# 安全性：切換執行用戶為非 root 用戶 (www-data)，遵循最小權限原則
USER www-data