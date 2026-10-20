# UnlockRentals - Automated Real Estate Crawler & Importer

This crawler allows you to crawl real estate rental listings city-by-city and import them directly into your UnlockRentals database (`properties` and `property_images` tables).

## Features
- **Anti-Bot Bypass**: Uses `curl_cffi` to mimic Chrome TLS/JA3 fingerprints to bypass 99acres bot detection.
- **Ultra-low RAM**: Runs smoothly within 50–70 MB RAM (ideal for your 1 GB RAM hosting).
- **Zero Disk Space Storage**: Saves 99acres CDN WebP image URLs directly into the database.
- **Direct Frontend Display**: Saves listings with `status = 'approved'` so they immediately appear in homepage search and property cards.

## How to Run

### 1. Crawl a Specific City
```bash
python scrapers/crawl_and_import.py Gurgaon 10
python scrapers/crawl_and_import.py Noida 10
python scrapers/crawl_and_import.py Delhi 10
python scrapers/crawl_and_import.py Bangalore 10
python scrapers/crawl_and_import.py Mumbai 10
python scrapers/crawl_and_import.py Pune 10
python scrapers/crawl_and_import.py Hyderabad 10
```

### 2. Crawl All Cities Automatically
```bash
python scrapers/crawl_and_import.py all 10
```

### 3. Verify Frontend Data
```bash
php scrapers/verify_frontend.php
```

### 4. Clear Cache (if needed)
```bash
php artisan cache:clear
```
