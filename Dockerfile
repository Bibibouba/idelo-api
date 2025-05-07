FROM php:8.2-cli

WORKDIR /app
COPY . .

# Installer extensions PDO + MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Port par défaut pour Railway
EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080"]
