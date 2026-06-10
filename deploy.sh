#!/bin/bash
set -e

# =====================================================================
#  UnlockRentals Production Deployment Script
# =====================================================================
# Description: Automates deployment steps for UnlockRentals on production.
# Author: Antigravity AI
# =====================================================================

# Color definitions
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0;m' # No Color

echo -e "${BLUE}=====================================================================${NC}"
echo -e "${BLUE}           🚀 Starting UnlockRentals Production Deployment           ${NC}"
echo -e "${BLUE}=====================================================================${NC}"

# Check for .env file
if [ ! -f .env ]; then
    echo -e "${RED}❌ Error: .env file is missing! Please configure it before deploying.${NC}"
    exit 1
fi

# Ensure correct app environment is defined
APP_ENV=$(grep '^APP_ENV=' .env | cut -d '=' -f2)
echo -e "${YELLOW}ℹ️  Current environment detected: ${GREEN}${APP_ENV}${NC}"

if [ "$APP_ENV" != "production" ]; then
    echo -e "${YELLOW}⚠️  Warning: APP_ENV is not set to 'production'. We recommend production mode for performance/security.${NC}"
fi

# 1. Fetch latest changes
echo -e "\n${BLUE}🔄 1. Pulling latest code from repository...${NC}"
git pull origin main

# 2. Install/Update PHP Dependencies
echo -e "\n${BLUE}📦 2. Installing Composer dependencies (no-dev)...${NC}"
composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# 3. Migrate Database
echo -e "\n${BLUE}🗄️  3. Running database migrations...${NC}"
php artisan migrate --force

# 4. Install & Build Frontend Assets
if [ -f package.json ]; then
    echo -e "\n${BLUE}🎨 4. Installing and building Vite frontend assets...${NC}"
    npm install
    npm run build
else
    echo -e "\n${YELLOW}ℹ️  Skipping frontend asset build: package.json not found.${NC}"
fi

# 5. Optimize Laravel Config & Route Caching
echo -e "\n${BLUE}⚡ 5. Caching configuration, routes, and views...${NC}"
# Clear caches first to avoid any state issues
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Warm up caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Set up Storage Link
echo -e "\n${BLUE}🔗 6. Generating storage symbolic link...${NC}"
if [ -d public/storage ]; then
    echo -e "${YELLOW}ℹ️  Storage link already exists.${NC}"
else
    php artisan storage:link
    echo -e "${GREEN}✓ Storage link generated successfully.${NC}"
fi

# 7. Finalize and report permissions reminder
echo -e "\n${BLUE}⚙️  7. Cleaning up system queues...${NC}"
php artisan queue:restart || true

echo -e "${BLUE}=====================================================================${NC}"
echo -e "${GREEN}       🎉 UnlockRentals Deployment Completed Successfully!           ${NC}"
echo -e "${BLUE}=====================================================================${NC}"
echo -e "${YELLOW}👉 Remember to set correct folder permissions on your server:${NC}"
echo -e "   sudo chown -R www-data:www-data storage bootstrap/cache"
echo -e "   sudo chmod -R 775 storage bootstrap/cache"
echo -e "${BLUE}=====================================================================${NC}"
