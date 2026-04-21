# Deployment Guide for UnlockRentals

This guide outlines the steps to deploy your application to a production environment.

## 1. Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- A web server (Nginx or Apache)
- A database (MySQL, PostgreSQL, or SQLite)

## 2. Server Setup
Clone the repository to your production server:
```bash
git clone https://github.com/vermaaniket577/UnlockRentals.git
cd UnlockRentals
```

## 3. Install Dependencies
Install PHP dependencies:
```bash
composer install --optimize-autoloader --no-dev
```

## 4. Environment Configuration
Copy the `.env.example` to `.env` and update the values:
```bash
cp .env.example .env
php artisan key:generate
```
**Important**: Update `APP_ENV=production`, `APP_DEBUG=false`, and your database/mail credentials in `.env`.

## 5. Build Assets
Since we have already built the assets, you can either push the `public/build` directory (not recommended if it's in .gitignore) or build it on the server:
```bash
npm install
npm run build
```

## 6. Database Migrations
Run the migrations to set up your database schema:
```bash
php artisan migrate --force
```

## 7. Optimizing Laravel
Run these commands to cache your configurations and routes for better performance:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 8. Storage Link
Create a symbolic link from `public/storage` to `storage/app/public`:
```bash
php artisan storage:link
```

## 9. File Permissions
Ensure the `storage` and `bootstrap/cache` directories are writable by the web server:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---
*Note: For a seamless experience, consider using a deployment service like Laravel Forge or Envoyer.*
