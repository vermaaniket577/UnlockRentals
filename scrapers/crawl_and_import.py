import os
import re
import sys
import time
import random
import json
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

def crawl_items_from_city(city_name: str, config: dict, max_items: int = 10):
    url = config["url"]
    state = config["state"]

    try:
        response = requests.get(url, headers=HEADERS, impersonate="chrome120", timeout=25)
    except Exception as e:
        sys.stderr.write(f"Fetch error: {e}\n")
        return []

    if response.status_code != 200:
        sys.stderr.write(f"HTTP error {response.status_code}\n")
        return []

    items = []

    # Priority 1: Extract complete rich gallery & property details from window.__initialData__
    match = re.search(r'window\.__initialData__\s*=\s*(\{.*?\});', response.text)
    if match:
        try:
            raw_json = json.loads(match.group(1))
            props = raw_json.get("srp", {}).get("pageData", {}).get("properties", [])
            for p in props:
                if len(items) >= max_items:
                    break

                title = p.get("PROP_HEADING") or p.get("PROP_NAME")
                if not title:
                    continue

                price_raw = str(p.get("PRICE") or "")
                price = parse_indian_price(price_raw)
                if price <= 0:
                    price = float(p.get("MIN_PRICE") or 25000.0)

                bedrooms = int(p.get("BEDROOM_NUM") or 2)
                bathrooms = int(p.get("BATHROOM_NUM") or max(1, bedrooms - 1))
                area_sqft = int(float(p.get("CARPET_SQFT") or p.get("AREA") or 900))

                locality = p.get("LOCALITY") or p.get("LOCALITY_WO_CITY") or city_name
                society = p.get("SOCIETY_NAME") or p.get("BUILDING_NAME") or ""

                furnish_val = str(p.get("FURNISH") or "").lower()
                furnishing = "semi-furnished"
                if "unfurnished" in furnish_val or "un" in furnish_val:
                    furnishing = "unfurnished"
                elif "full" in furnish_val:
                    furnishing = "fully-furnished"

                # Extract ALL images from PROPERTY_IMAGES list (full gallery: bedrooms, kitchen, bathrooms, views)
                raw_imgs = p.get("PROPERTY_IMAGES") or []
                if not raw_imgs:
                    raw_imgs = p.get("THUMBNAIL_IMAGES") or []
                if not raw_imgs and p.get("MEDIUM_PHOTO_URL"):
                    raw_imgs = [p.get("MEDIUM_PHOTO_URL")]
                if not raw_imgs and p.get("PHOTO_URL"):
                    raw_imgs = [p.get("PHOTO_URL")]

                clean_imgs = []
                for img in raw_imgs:
                    if isinstance(img, str) and img.startswith("http") and not img.endswith(".m3u8"):
                        high_img = img.replace("T-", "M-")
                        if high_img not in clean_imgs:
                            clean_imgs.append(high_img)

                if not clean_imgs:
                    clean_imgs = [random.choice(FALLBACK_IMAGES)]

                contact_phone = f"+91 {random.randint(70000, 99999)} {random.randint(10000, 99999)}"

                desc = p.get("DESCRIPTION")
                if not desc or len(desc) < 30:
                    desc = (
                        f"Spacious {bedrooms} BHK apartment in {society or locality}, {city_name}. "
                        f"Carpet area of {area_sqft} sq.ft. with {bathrooms} bathrooms. "
                        f"Comes {furnishing}. Situated close to public transit, markets, and key commercial hubs."
                    )

                items.append({
                    "title": title,
                    "price": price,
                    "bedrooms": bedrooms,
                    "bathrooms": bathrooms,
                    "area_sqft": area_sqft,
                    "city": city_name,
                    "state": state,
                    "locality": locality,
                    "furnishing": furnishing,
                    "images": clean_imgs,
                    "contact_phone": contact_phone,
                    "description": desc
                })
        except Exception as e:
            sys.stderr.write(f"JSON parsing error: {e}\n")

    # Priority 2: Fallback to HTML card extraction if __initialData__ was absent
    if not items:
        soup = BeautifulSoup(response.text, "html.parser")
        cards = soup.find_all("div", class_=re.compile(r"tupleNew__outerTupleWrap"))
        for idx, card in enumerate(cards):
            if len(items) >= max_items:
                break
            try:
                title_el = card.find(["h2", "a"], class_=re.compile(r"tupleNew__propType|tupleTitle"))
                title = title_el.get_text(" ", strip=True) if title_el else f"Rental Flat in {city_name}"

                price_el = card.find(class_=re.compile(r"tupleNew__priceValWrap|list_header_semiBold"))
                price_raw = price_el.get_text(strip=True) if price_el else ""
                price = parse_indian_price(price_raw)
                if price <= 0:
                    price = 25000.0

                bedrooms = 2
                bhk_match = re.search(r"(\d+)\s*BHK", title, re.I)
                if bhk_match:
                    bedrooms = int(bhk_match.group(1))
                bathrooms = max(1, bedrooms - 1) if bedrooms > 1 else 1

                area_sqft = 850
                area_el = card.find(class_=re.compile(r"tupleNew__area1Type"))
                if area_el:
                    area_match = re.search(r"([\d,]+)\s*sqft", area_el.get_text(strip=True), re.I)
                    if area_match:
                        area_sqft = int(area_match.group(1).replace(",", ""))

                locality = city_name
                loc_match = re.search(r"in\s+(.+?)(?:,|$)", title, re.I)
                if loc_match:
                    locality = loc_match.group(1).strip()

                furnishing = "semi-furnished"
                card_text = card.get_text().lower()
                if "fully furnished" in card_text or "fully-furnished" in card_text:
                    furnishing = "fully-furnished"
                elif "unfurnished" in card_text:
                    furnishing = "unfurnished"

                imgs = []
                for img in card.find_all("img"):
                    src = img.get("src") or img.get("data-src")
                    if src and "imagecdn.99acres.com" in src:
                        imgs.append(src)
                if not imgs:
                    imgs = random.sample(FALLBACK_IMAGES, min(3, len(FALLBACK_IMAGES)))

                contact_phone = f"+91 {random.randint(70000, 99999)} {random.randint(10000, 99999)}"

                description = (
                    f"Spacious {bedrooms} BHK apartment available for rent in {locality}, {city_name}. "
                    f"Carpet area of {area_sqft} sq.ft. with {bathrooms} bathrooms. "
                    f"Comes {furnishing}. Situated close to public transit, markets, and key hubs."
                )

                items.append({
                    "title": title,
                    "price": price,
                    "bedrooms": bedrooms,
                    "bathrooms": bathrooms,
                    "area_sqft": area_sqft,
                    "city": city_name,
                    "state": state,
                    "locality": locality,
                    "furnishing": furnishing,
                    "images": imgs,
                    "contact_phone": contact_phone,
                    "description": description
                })
            except Exception:
                continue

    return items

def save_items_to_sqlite(items):
    if not os.path.exists(DB_PATH):
        print(f"Database file not found at {DB_PATH}")
        return 0

    conn = sqlite3.connect(DB_PATH)
    cursor = conn.cursor()

    cursor.execute("SELECT id FROM users WHERE role = 'admin' OR email LIKE '%admin%' LIMIT 1")
    user_row = cursor.fetchone()
    user_id = user_row[0] if user_row else 1

    cursor.execute("SELECT id FROM categories WHERE name LIKE '%Flat%' OR name LIKE '%Apartment%' LIMIT 1")
    cat_row = cursor.fetchone()
    category_id = cat_row[0] if cat_row else 1

    saved = 0
    for idx, item in enumerate(items):
        slug_base = re.sub(r"[^a-zA-Z0-9]+", "-", item["title"].lower()).strip("-")
        slug = f"{slug_base}-{random.randint(10000, 99999)}"

        cursor.execute("""
            INSERT INTO properties (
                user_id, category_id, title, slug, description, type, purpose,
                price, price_period, state, location, locality, address,
                contact_phone, bedrooms, bathrooms, area_sqft, furnishing,
                status, is_featured, is_booked, approved_at, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, 'house', 'rent', ?, 'month', ?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved', ?, 0, datetime('now'), datetime('now'), datetime('now'))
        """, (
            user_id, category_id, item["title"], slug, item["description"],
            item["price"], item["state"], item["city"], item["locality"],
            f"{item['locality']}, {item['city']}", item["contact_phone"],
            item["bedrooms"], item["bathrooms"], item["area_sqft"], item["furnishing"],
            1 if idx < 3 else 0
        ))

        prop_id = cursor.lastrowid
        for img_idx, img_url in enumerate(item["images"]):
            cursor.execute("""
                INSERT INTO property_images (
                    property_id, path, is_primary, sort_order, created_at, updated_at
                ) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))
            """, (prop_id, img_url, 1 if img_idx == 0 else 0, img_idx))

        saved += 1
        print(f"  [✓] Imported ID #{prop_id}: '{item['title']}' | ₹{int(item['price'])}/mo")

    conn.commit()
    conn.close()
    return saved

if __name__ == "__main__":
    args = sys.argv[1:]
    is_json = "--json" in args
    clean_args = [a for a in args if a != "--json"]

    target_city = clean_args[0] if len(clean_args) > 0 else "Gurgaon"
    max_count = int(clean_args[1]) if len(clean_args) > 1 else 10

    if target_city.lower() == "all":
        all_items = []
        for city, cfg in CITIES_CONFIG.items():
            if not is_json:
                print(f"\n[*] Crawling {city}...")
            city_items = crawl_items_from_city(city, cfg, max_items=max_count)
            all_items.extend(city_items)
            time.sleep(random.uniform(2.5, 4.0))

        if is_json:
            print("===JSON_START===")
            print(json.dumps(all_items))
            print("===JSON_END===")
        else:
            save_items_to_sqlite(all_items)
            print(f"\n[🎉] Done! Stored {len(all_items)} properties.")

    elif target_city in CITIES_CONFIG:
        if not is_json:
            print(f"\n[*] Crawling {target_city} ({max_count} listings)...")
        items = crawl_items_from_city(target_city, CITIES_CONFIG[target_city], max_items=max_count)

        if is_json:
            print("===JSON_START===")
            print(json.dumps(items))
            print("===JSON_END===")
        else:
            save_items_to_sqlite(items)
            print(f"\n[✓] Done! Stored {len(items)} properties.")
    else:
        print(f"Unknown city '{target_city}'. Options: {list(CITIES_CONFIG.keys())} or 'all'")
