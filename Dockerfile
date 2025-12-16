FROM php:8.1-cli

WORKDIR /app

COPY . .

EXPOSE 8080

# Removed "-t public" because the root directory contains index.php
# Start PHP server binding to the Railway-provided PORT (default 8080)
CMD sh -c "php -S 0.0.0.0:${PORT:-8080}"
