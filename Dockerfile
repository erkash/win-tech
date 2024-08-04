# Stage 1: Build stage
FROM php:8.3-cli-alpine as win_test

# Install dependencies
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    bash \
    wget \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Copy composer from the official composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Setup php app user
ARG USER_ID=1000
RUN adduser -u ${USER_ID} -D -H app

# Set user to app
USER app

# Copy application files
COPY --chown=app . /app
WORKDIR /app

# Expose the port
EXPOSE 8337

# Command to run the application
CMD ["php", "-S", "0.0.0.0:8337", "-t", "public"]