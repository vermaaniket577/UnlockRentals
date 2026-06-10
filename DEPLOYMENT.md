# 🚀 UnlockRentals - Production Deployment Guide

This guide outlines the steps to deploy your **UnlockRentals** application to a production environment. Follow these instructions to ensure optimal performance, security, and stability.

---

## 📋 1. Prerequisites

Before deploying, ensure your production server has the following components installed and configured:

*   **PHP 8.2+** (with required extensions: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_sqlite` or `pdo_mysql`, `tokenizer`, `xml`)
*   **Composer** (global installation)
*   **Node.js & NPM** (for compiling Vite assets)
*   **Web Server** (Nginx or Apache)
*   **Database Engine** (MySQL, PostgreSQL, or SQLite)

---

## 🛠️ 2. Server Setup

Clone the repository to your production server:

```bash
git clone https://github.com/vermaaniket577/UnlockRentals.git
cd UnlockRentals
```

---

## 📦 3. Install Dependencies

Install the PHP dependencies utilizing Composer. Ensure dev packages are excluded and the autoloader is fully optimized:

```bash
composer install --optimize-autoloader --no-dev
```

---

## ⚙️ 4. Environment Configuration

1. Copy the `.env.example` file to create your production `.env` configuration:
   ```bash
   cp .env.example .env
   ```

2. Generate the application encryption key:
   ```bash
   php artisan key:generate
   ```

3. Open `.env` in your editor and configure the following parameters:
   > [!IMPORTANT]
   > Ensure these parameters are strictly set for security and speed:
   > * `APP_ENV=production`
   > * `APP_DEBUG=false`
   > * `APP_URL=https://yourdomain.com` (Ensure HTTPS is enabled)
   > * Configure your production Database, Mail, Razorpay, and Google Client credentials.

---

## 🎨 5. Compile Frontend Assets

Vite resources must be compiled for production. Run the installation and build process:

```bash
npm install
npm run build
```

*This generates optimized and minified chunks under `public/build/`.*

---

## 🗄️ 6. Database Migrations

Run your database migrations to set up your production database schema. The `--force` flag ensures migrations run in production without interactive prompts:

```bash
php artisan migrate --force
```

---

## ⚡ 7. Optimize Laravel

Cache configurations, routes, and views for an instant response-time performance boost:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> [!TIP]
> Always run these optimization commands after modifying configuration, routes, or Blade templates in production.

---

## 🔗 8. Storage Link

Generate the symbolic link linking `public/storage` to `storage/app/public` so files uploaded via the admin panel are accessible:

```bash
php artisan storage:link
```

---

## 🔒 9. File & Cache Permissions

Set correct permissions so the web server can write to the storage and cache folders:

```bash
# Set ownership to web server user (typically www-data, apache, or nginx)
sudo chown -R www-data:www-data storage bootstrap/cache

# Allow read, write, and execute permissions for the group
sudo chmod -R 775 storage bootstrap/cache
```

---

## 🤖 10. Automated Deployment (Recommended)

To make future updates effortless, we have created an automated deployment script in the project root: [deploy.sh](file:///c:/xampp/htdocs/UnlockRentals/deploy.sh).

This script performs code pulling, dependency installation, migration, asset compilation, caching, and cleanups in a single command.

### How to use:
1. Make the script executable:
   ```bash
   chmod +x deploy.sh
   ```
2. Run the deployment:
   ```bash
   ./deploy.sh
   ```

---
*UnlockRentals is ready for takeoff. Safe travels!* 🚀
