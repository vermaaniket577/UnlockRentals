# UnlockRentals - Automated Real Estate Crawler & Importer

This crawler allows you to crawl real estate rental listings city-by-city and import them directly into your UnlockRentals database (`properties` and `property_images` tables).

## Prerequisites (Python libraries)
In your terminal, make sure `curl_cffi` and `beautifulsoup4` are installed:
```bash
pip install curl_cffi beautifulsoup4
# or
pip3 install curl_cffi beautifulsoup4
```

## How to Run on Live Server

### Option A: Via Laravel Artisan (Recommended - Works with MySQL & SQLite)
From inside your `laravel_project` directory:

```bash
# Scrape a specific city (default: 10 properties)
php artisan scrape:city Gurgaon --count=10
php artisan scrape:city Noida --count=10
php artisan scrape:city Delhi --count=10
php artisan scrape:city Bangalore --count=10
php artisan scrape:city Mumbai --count=10
php artisan scrape:city Pune --count=10
php artisan scrape:city Hyderabad --count=10

# Scrape all supported cities
php artisan scrape:city all --count=5
```

### Option B: Direct Python Execution
```bash
python3 scrapers/crawl_and_import.py Gurgaon 10
python3 scrapers/crawl_and_import.py all 5
```

### Verify Frontend Data
```bash
php scrapers/verify_frontend.php
```

### Clear Cache (if needed)
```bash
php artisan cache:clear
```
