FROM php:8.1-cli

WORKDIR /app

COPY . .

EXPOSE 8080

# Removed "-t public" because the root directory contains index.php
CMD ["php", "-S", "0.0.0.0:8080"]
