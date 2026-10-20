import os
import re
import sys
import time
import random
import sqlite3
from curl_cffi import requests
from bs4 import BeautifulSoup

# ==============================================================================
# Configuration
# ==============================================================================
CITIES_CONFIG = {
    "Gurgaon": {
        "state": "Haryana",
        "url": "https://www.99acres.com/flats-for-rent-in-gurgaon-ffid"
    },
    "Noida": {
        "state": "Uttar Pradesh",
        "url": "https://www.99acres.com/flats-for-rent-in-noida-ffid"
    },
    "Delhi": {
        "state": "Delhi",
        "url": "https://www.99acres.com/flats-for-rent-in-delhi-ffid"
    },
    "Bangalore": {
        "state": "Karnataka",
        "url": "https://www.99acres.com/flats-for-rent-in-bangalore-ffid"
    },
    "Mumbai": {
        "state": "Maharashtra",
        "url": "https://www.99acres.com/flats-for-rent-in-mumbai-ffid"
    },
    "Pune": {
        "state": "Maharashtra",
        "url": "https://www.99acres.com/flats-for-rent-in-pune-ffid"
    },
    "Hyderabad": {
        "state": "Telangana",
        "url": "https://www.99acres.com/flats-for-rent-in-hyderabad-ffid"
    }
}

# Resolve DB Path
ROOT_DIR = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
DB_PATH = os.path.join(ROOT_DIR, "database", "database.sqlite")

HEADERS = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8",
    "Accept-Language": "en-US,en;q=0.9",
    "Referer": "https://www.99acres.com/",
}

FALLBACK_IMAGES = [
    "https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1000&q=80",
    "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80",
    "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1000&q=80",
    "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80"
]

def parse_indian_price(price_str: str) -> float:
    if not price_str:
        return 0.0
    s = price_str.replace(",", "").strip()
    cr = re.search(r"([\d\.]+)\s*(?:cr|crore)", s, re.I)
    if cr:
        return float(cr.group(1)) * 10000000.0
    lac = re.search(r"([\d\.]+)\s*(?:lac|lakh|l)", s, re.I)
    if lac:
        return float(lac.group(1)) * 100000.0
    digits = re.search(r"([\d\.]+)", s)
    if digits:
        return float(digits.group(1))
    return 0.0

def get_or_create_user(cursor):
    cursor.execute("SELECT id FROM users WHERE role = 'admin' OR email LIKE '%admin%' LIMIT 1")
    row = cursor.fetchone()
    if row:
        return row[0]
    cursor.execute("SELECT id FROM users LIMIT 1")
    row = cursor.fetchone()
    return row[0] if row else 1

def get_category_id(cursor):
    # Try Flat / Apartment (ID 10) or Residential House
    cursor.execute("SELECT id FROM categories WHERE name LIKE '%Flat%' OR name LIKE '%Apartment%' LIMIT 1")
    row = cursor.fetchone()
    if row:
        return row[0]
    cursor.execute("SELECT id FROM categories LIMIT 1")
    row = cursor.fetchone()
    return row[0] if row else 1

def generate_unique_slug(cursor, title):
    base_slug = re.sub(r"[^a-zA-Z0-9]+", "-", title.lower()).strip("-")
    if not base_slug:
        base_slug = "rental-property"
    candidate = f"{base_slug}-{random.randint(10000, 99999)}"
    cursor.execute("SELECT 1 FROM properties WHERE slug = ?", (candidate,))
    while cursor.fetchone():
        candidate = f"{base_slug}-{random.randint(10000, 99999)}"
        cursor.execute("SELECT 1 FROM properties WHERE slug = ?", (candidate,))
    return candidate

def crawl_city(city_name: str, config: dict, max_items: int = 15):
    url = config["url"]
    state = config["state"]

    print(f"\n========================================================")
    print(f"[*] Crawling: {city_name}, {state}")
    print(f"[*] URL: {url}")
    print(f"[*] Target Max: {max_items} properties")
    print(f"========================================================")

    try:
        response = requests.get(url, headers=HEADERS, impersonate="chrome120", timeout=25)
    except Exception as e:
        print(f"[!] Error fetching {url}: {e}")
        return 0

    if response.status_code != 200:
        print(f"[!] HTTP error {response.status_code} while fetching {url}")
        return 0

    soup = BeautifulSoup(response.text, "html.parser")
    cards = soup.find_all("div", class_=re.compile(r"tupleNew__outerTupleWrap"))
    print(f"[+] Found {len(cards)} listings on page.")

    if not cards:
        print("[!] No cards detected. 99acres structure may have changed.")
        return 0

    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()

    user_id = get_or_create_user(cursor)
    category_id = get_category_id(cursor)

    imported = 0

    for idx, card in enumerate(cards):
        if imported >= max_items:
            break

        try:
            # 1. Title
            title_el = card.find(["h2", "a"], class_=re.compile(r"tupleNew__propType|tupleTitle"))
            title = title_el.get_text(" ", strip=True) if title_el else f"Modern Rental Flat in {city_name}"

            # 2. Price
            price_el = card.find(class_=re.compile(r"tupleNew__priceValWrap|list_header_semiBold"))
            price_raw = price_el.get_text(strip=True) if price_el else ""
            price = parse_indian_price(price_raw)
            if price <= 0:
                price = 25000.0  # reasonable fallback

            # 3. BHK & Bathrooms
            bedrooms = 2
            bhk_match = re.search(r"(\d+)\s*BHK", title, re.I)
            if bhk_match:
                bedrooms = int(bhk_match.group(1))

            bathrooms = max(1, bedrooms - 1) if bedrooms > 1 else 1

            # 4. Area
            area_sqft = 850
            area_el = card.find(class_=re.compile(r"tupleNew__area1Type"))
            if area_el:
                area_match = re.search(r"([\d,]+)\s*sqft", area_el.get_text(strip=True), re.I)
                if area_match:
                    area_sqft = int(area_match.group(1).replace(",", ""))

            # 5. Locality
            locality = city_name
            loc_match = re.search(r"in\s+(.+?)(?:,|$)", title, re.I)
            if loc_match:
                locality = loc_match.group(1).strip()

            address = f"{locality}, {city_name}, {state}"

            # 6. Furnishing
            furnishing = "semi-furnished"
            card_text = card.get_text().lower()
            if "fully furnished" in card_text or "fully-furnished" in card_text:
                furnishing = "fully-furnished"
            elif "unfurnished" in card_text:
                furnishing = "unfurnished"

            # 7. Images
            imgs = []
            for img in card.find_all("img"):
                src = img.get("src") or img.get("data-src")
                if src and "imagecdn.99acres.com" in src:
                    imgs.append(src)

            # If no photos found on card, pick a high quality fallback
            if not imgs:
                imgs.append(random.choice(FALLBACK_IMAGES))

            # 8. Contact phone
            contact_phone = f"+91 {random.randint(70000, 99999)} {random.randint(10000, 99999)}"

            # 9. Description
            description = (
                f"Beautiful {bedrooms} BHK apartment available for rent in {locality}, {city_name}. "
                f"Spacious carpet area of {area_sqft} sq.ft. with {bathrooms} bathrooms. "
                f"Comes {furnishing}. Situated close to public transit, markets, and key hubs."
            )

            # 10. Slug
            slug = generate_unique_slug(cursor, title)

            # Insert Property
            cursor.execute("""
                INSERT INTO properties (
                    user_id, category_id, title, slug, description, type, purpose,
                    price, price_period, state, location, locality, address,
                    contact_phone, bedrooms, bathrooms, area_sqft, furnishing,
                    status, is_featured, is_booked, approved_at, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, 'house', 'rent', ?, 'month', ?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved', ?, 0, datetime('now'), datetime('now'), datetime('now'))
            """, (
                user_id,
                category_id,
                title,
                slug,
                description,
                price,
                state,
                city_name,
                locality,
                address,
                contact_phone,
                bedrooms,
                bathrooms,
                area_sqft,
                furnishing,
                1 if idx < 3 else 0  # feature top 3
            ))

            property_id = cursor.lastrowid

            # Insert Images into property_images table
            for img_idx, img_url in enumerate(imgs):
                cursor.execute("""
                    INSERT INTO property_images (
                        property_id, path, is_primary, sort_order, created_at, updated_at
                    ) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))
                """, (
                    property_id,
                    img_url,
                    1 if img_idx == 0 else 0,
                    img_idx
                ))

            imported += 1
            print(f"  [{imported}/{max_items}] Saved ID #{property_id}: '{title}' | ₹{int(price)}/mo | {locality}")

        except Exception as e:
            print(f"  [!] Failed card {idx}: {e}")
            continue

    conn.commit()
    conn.close()
    print(f"[✓] Completed {city_name}: Successfully added {imported} properties.")
    return imported

if __name__ == "__main__":
    target_city = sys.argv[1] if len(sys.argv) > 1 else "Gurgaon"
    max_items = int(sys.argv[2]) if len(sys.argv) > 2 else 10

    if target_city.lower() == "all":
        total = 0
        for city, cfg in CITIES_CONFIG.items():
            cnt = crawl_city(city, cfg, max_items=max_items)
            total += cnt
            time.sleep(random.uniform(3.0, 5.0))
        print(f"\n[🎉] Done! Total properties imported across all cities: {total}")
    elif target_city in CITIES_CONFIG:
        crawl_city(target_city, CITIES_CONFIG[target_city], max_items=max_items)
    else:
        print(f"Unknown city '{target_city}'. Available: {list(CITIES_CONFIG.keys())} or 'all'")
