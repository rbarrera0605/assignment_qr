# Use official PHP + Apache image
FROM php:8.2-apache

# Install mysqli (for MySQL database support)
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite (optional but useful later)
RUN a2enmod rewrite

# Copy project files into Apache directory
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose Apache port
EXPOSE 80
