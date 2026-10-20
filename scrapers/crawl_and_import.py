import os
import re
import sys
import time
import random
import json
import sqlite3
from curl_cffi import requests
from bs4 import BeautifulSoup

try:
    from playwright_scraper import scrape_with_playwright, PLAYWRIGHT_INSTALLED
except Exception:
    PLAYWRIGHT_INSTALLED = False
    scrape_with_playwright = None

# ==============================================================================
# Comprehensive Indian Cities & States Knowledge Map
# ==============================================================================
CITY_STATE_MAP = {
    "indore": ("Indore", "Madhya Pradesh"),
    "bhopal": ("Bhopal", "Madhya Pradesh"),
    "gwalior": ("Gwalior", "Madhya Pradesh"),
    "jabalpur": ("Jabalpur", "Madhya Pradesh"),
    "gurgaon": ("Gurgaon", "Haryana"),
    "gurugram": ("Gurugram", "Haryana"),
    "faridabad": ("Faridabad", "Haryana"),
    "panipat": ("Panipat", "Haryana"),
    "noida": ("Noida", "Uttar Pradesh"),
    "greater noida": ("Greater Noida", "Uttar Pradesh"),
    "ghaziabad": ("Ghaziabad", "Uttar Pradesh"),
    "lucknow": ("Lucknow", "Uttar Pradesh"),
    "kanpur": ("Kanpur", "Uttar Pradesh"),
    "varanasi": ("Varanasi", "Uttar Pradesh"),
    "agra": ("Agra", "Uttar Pradesh"),
    "delhi": ("Delhi", "Delhi"),
    "new delhi": ("New Delhi", "Delhi"),
    "mumbai": ("Mumbai", "Maharashtra"),
    "pune": ("Pune", "Maharashtra"),
    "navi mumbai": ("Navi Mumbai", "Maharashtra"),
    "thane": ("Thane", "Maharashtra"),
    "nagpur": ("Nagpur", "Maharashtra"),
    "nashik": ("Nashik", "Maharashtra"),
    "bangalore": ("Bangalore", "Karnataka"),
    "bengaluru": ("Bengaluru", "Karnataka"),
    "mysore": ("Mysore", "Karnataka"),
    "hyderabad": ("Hyderabad", "Telangana"),
    "secunderabad": ("Secunderabad", "Telangana"),
    "chennai": ("Chennai", "Tamil Nadu"),
    "coimbatore": ("Coimbatore", "Tamil Nadu"),
    "kolkata": ("Kolkata", "West Bengal"),
    "jaipur": ("Jaipur", "Rajasthan"),
    "udaipur": ("Udaipur", "Rajasthan"),
    "jodhpur": ("Jodhpur", "Rajasthan"),
    "ahmedabad": ("Ahmedabad", "Gujarat"),
    "surat": ("Surat", "Gujarat"),
    "vadodara": ("Vadodara", "Gujarat"),
    "chandigarh": ("Chandigarh", "Punjab"),
    "mohali": ("Mohali", "Punjab"),
    "ludhiana": ("Ludhiana", "Punjab"),
    "bhubaneswar": ("Bhubaneswar", "Odisha"),
    "cuttack": ("Cuttack", "Odisha"),
    "patna": ("Patna", "Bihar"),
    "kochi": ("Kochi", "Kerala"),
    "trivandrum": ("Trivandrum", "Kerala"),
    "goa": ("Goa", "Goa"),
    "dehradun": ("Dehradun", "Uttarakhand"),
}

CITY_ID_MAP = {
    "142": ("Indore", "Madhya Pradesh"),
    "20": ("Bangalore", "Karnataka"),
    "1": ("Delhi", "Delhi"),
    "2": ("Mumbai", "Maharashtra"),
    "18": ("Gurgaon", "Haryana"),
    "19": ("Noida", "Uttar Pradesh"),
    "21": ("Pune", "Maharashtra"),
    "38": ("Hyderabad", "Telangana"),
    "32": ("Chennai", "Tamil Nadu"),
    "25": ("Kolkata", "West Bengal"),
    "176": ("Jaipur", "Rajasthan"),
    "45": ("Ahmedabad", "Gujarat"),
}

CITIES_CONFIG = {
    "Gurgaon": {"state": "Haryana", "url": "https://www.99acres.com/flats-for-rent-in-gurgaon-ffid"},
    "Noida": {"state": "Uttar Pradesh", "url": "https://www.99acres.com/flats-for-rent-in-noida-ffid"},
    "Delhi": {"state": "Delhi", "url": "https://www.99acres.com/flats-for-rent-in-delhi-ffid"},
    "Bangalore": {"state": "Karnataka", "url": "https://www.99acres.com/search/property/rent/bangalore?city=20&preference=R&budget_min=0&res_com=R&isPreLeased=N"},
    "Indore": {"state": "Madhya Pradesh", "url": "https://www.99acres.com/search/property/buy/indore?city=142&preference=S&res_com=R"},
    "Mumbai": {"state": "Maharashtra", "url": "https://www.99acres.com/flats-for-rent-in-mumbai-ffid"},
    "Pune": {"state": "Maharashtra", "url": "https://www.99acres.com/flats-for-rent-in-pune-ffid"},
    "Hyderabad": {"state": "Telangana", "url": "https://www.99acres.com/flats-for-rent-in-hyderabad-ffid"}
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
    "https://imagecdn.99acres.com/media1/42017/5/840345375M-1789551687244.jpg",
    "https://imagecdn.99acres.com/media1/42871/0/857420409M-1791478098385.jpg",
    "https://imagecdn.99acres.com/media1/43068/0/861360145M-1791968331875.jpg",
    "https://imagecdn.99acres.com/media1/42159/1/843181781M-1789893933830.jpg",
    "https://imagecdn.99acres.com/media1/42949/8/858988919M-1791654342416.jpg",
    "https://imagecdn.99acres.com/media1/39272/9/785449752M-1782739242717.jpg",
    "https://imagecdn.99acres.com/media1/43177/5/863545599M-1792209764839.jpg",
    "https://imagecdn.99acres.com/media1/42481/11/849631073M-1790688294984.jpg",
]

def resolve_location(url: str, p: dict = None):
    p = p or {}
    # Check JSON item directly first
    loc_obj = p.get("location") or {}
    json_city = loc_obj.get("cityName") or p.get("CITY") or p.get("CONTACT_CITY_NAME")
    json_state = loc_obj.get("stateName") or p.get("STATE")
    
    if json_city:
        json_city = str(json_city).strip()
        matched = CITY_STATE_MAP.get(json_city.lower())
        if matched:
            return matched[0], json_state or matched[1]
        if json_state:
            return json_city.title(), str(json_state).strip()

    # Check city_id in URL
    cid_match = re.search(r"city=(\d+)", url, re.I)
    if cid_match and cid_match.group(1) in CITY_ID_MAP:
        return CITY_ID_MAP[cid_match.group(1)]

    # Check city name in URL path
    for k, v in CITY_STATE_MAP.items():
        if k in url.lower():
            return v

    return "Bangalore", "Karnataka"

def detect_type_and_category(title: str, prop_type: str = "", res_com: str = ""):
    combined = f"{title} {prop_type} {res_com}".lower()
    
    if "plot" in combined or "land" in combined:
        return "plot", "Plots & Land"
    if "villa" in combined:
        return "house", "Luxury Villa"
    if "house" in combined or "bungalow" in combined or "independent house" in combined:
        return "house", "Residential House"
    if "shop" in combined or "retail" in combined or "showroom" in combined:
        return "shop", "Retail Shop"
    if "office" in combined or "commercial" in combined or str(res_com).upper() == "C":
        return "shop", "Office Space"
    if "pg" in combined or "hostel" in combined or "coliving" in combined or "co-living" in combined:
        return "pg-hostel", "PG & Hostel"
    if "studio" in combined or "1 rk" in combined:
        return "house", "Studio Apartment"
    
    return "house", "Flat / Apartment"

def detect_purpose(url: str, preference: str = ""):
    pref = str(preference or "").upper()
    if pref == "S" or "preference=s" in url.lower() or "/buy/" in url.lower() or "/sale/" in url.lower():
        return "buy"
    return "rent"

def parse_indian_price(price_val) -> float:
    if not price_val:
        return 0.0
    if isinstance(price_val, dict):
        if price_val.get("min"):
            try:
                return float(price_val["min"])
            except:
                pass
        price_val = price_val.get("label") or ""
    if isinstance(price_val, (int, float)):
        return float(price_val)
    
    s = str(price_val).replace(",", "").strip()
    cr = re.search(r"([\d\.]+)\s*(?:cr|crore)", s, re.I)
    if cr:
        return float(cr.group(1)) * 10000000.0
    lac = re.search(r"([\d\.]+)\s*(?:lac|lakh|l\b)", s, re.I)
    if lac:
        return float(lac.group(1)) * 100000.0
    digits = re.search(r"([\d\.]+)", s)
    if digits:
        return float(digits.group(1))
    return 0.0

def crawl_items_from_city(city_name: str, config: dict, max_items: int = 10):
    url = config["url"]
    state = config.get("state", "India")

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

                entity = p.get("entityType")
                is_project = (entity == "PROJECT" or (not p.get("PROP_HEADING") and p.get("heading")))

                if is_project:
                    heading = p.get("heading") or ""
                    sub_heading = p.get("subHeading") or ""
                    title = f"{heading} - {sub_heading}" if sub_heading else heading
                    if not title or len(title) < 3:
                        continue

                    price = parse_indian_price(p.get("price"))
                    if price <= 0:
                        price = parse_indian_price(p.get("price", {}).get("label") or p.get("pricePerSqFt"))
                    if price <= 0:
                        price = 4500000.0

                    item_city, item_state = resolve_location(url, p)
                    locality = p.get("location", {}).get("localityName") or item_city

                    bedrooms = 2
                    bhk_matches = re.findall(r"(\d+)\s*BHK", sub_heading, re.I)
                    if bhk_matches:
                        bedrooms = int(bhk_matches[-1])
                    bathrooms = max(1, bedrooms - 1)

                    area_sqft = 1200
                    config_summary = p.get("configSummary", {})
                    cards = config_summary.get("cards", [])
                    if cards and isinstance(cards, list):
                        area_min = cards[0].get("area", {}).get("min")
                        if area_min:
                            try:
                                area_sqft = int(float(str(area_min).replace(",", "")))
                            except:
                                pass

                    clean_imgs = []
                    if p.get("coverImage", {}).get("url"):
                        clean_imgs.append(p.get("coverImage", {}).get("url"))
                    for c in p.get("projectCarousalImages") or []:
                        for sub_img in c.get("images", []):
                            u = sub_img.get("url")
                            if u and isinstance(u, str) and u.startswith("http") and not u.endswith(".m3u8"):
                                high_u = u.replace("T-", "M-")
                                if high_u not in clean_imgs:
                                    clean_imgs.append(high_u)
                    for u in p.get("immersiveMediaUrl") or []:
                        if u and isinstance(u, str) and u.startswith("http") and not u.endswith(".m3u8"):
                            if u not in clean_imgs:
                                clean_imgs.append(u)

                    if not clean_imgs:
                        clean_imgs = [random.choice(FALLBACK_IMAGES)]

                    builder = p.get("builderName") or "Project Developer"
                    seller_name = builder
                    seller_company = builder
                    seller_type = "builder"
                    seller_photo = ""

                    prop_type, cat_name = detect_type_and_category(title, sub_heading, p.get("rescom") or "R")
                    purpose = detect_purpose(url, p.get("preference") or "S")

                    desc = p.get("description", {}).get("text") or ""
                    if len(desc) < 30:
                        desc = f"Premium {title} situated in {locality}, {item_city}. Masterfully planned by {builder} with lifestyle amenities, grand architecture, and high connectivity."

                    items.append({
                        "title": title,
                        "type": prop_type,
                        "category_name": cat_name,
                        "purpose": purpose,
                        "price": price,
                        "bedrooms": bedrooms if prop_type != "plot" else 0,
                        "bathrooms": bathrooms if prop_type != "plot" else 0,
                        "area_sqft": area_sqft,
                        "city": item_city,
                        "state": item_state,
                        "locality": locality,
                        "furnishing": "semi-furnished" if prop_type != "plot" else "unfurnished",
                        "images": clean_imgs,
                        "contact_phone": f"+91 {random.randint(70000, 99999)} {random.randint(10000, 99999)}",
                        "description": desc,
                        "seller_name": seller_name,
                        "seller_company": seller_company,
                        "seller_photo": seller_photo,
                        "seller_type": seller_type,
                    })
                else:
                    # Standard individual property tuple
                    title = p.get("PROP_HEADING") or p.get("PROP_NAME")
                    if not title:
                        continue

                    price_raw = str(p.get("PRICE") or p.get("FORMATTED_PRICE") or "")
                    price = parse_indian_price(price_raw)
                    if price <= 0:
                        price = float(p.get("MIN_PRICE") or 25000.0)

                    bedrooms = int(p.get("BEDROOM_NUM") or 2)
                    bathrooms = int(p.get("BATHROOM_NUM") or max(1, bedrooms - 1))
                    area_raw = str(p.get("SUPER_SQFT") or p.get("CARPET_SQFT") or p.get("AREA") or "900")
                    area_match = re.search(r"([\d\.]+)", area_raw.replace(",", ""))
                    area_sqft = int(float(area_match.group(1))) if area_match else 900

                    item_city, item_state = resolve_location(url, p)
                    locality = p.get("LOCALITY") or p.get("LOCALITY_WO_CITY") or item_city
                    society = p.get("SOCIETY_NAME") or p.get("BUILDING_NAME") or ""

                    prop_type, cat_name = detect_type_and_category(title, p.get("PROPERTY_TYPE") or "", p.get("RES_COM") or "R")
                    purpose = detect_purpose(url, p.get("PREFERENCE") or "S")

                    furnish_val = str(p.get("FURNISH") or "").lower()
                    furnishing = "semi-furnished"
                    if "unfurnished" in furnish_val or "un" in furnish_val:
                        furnishing = "unfurnished"
                    elif "full" in furnish_val:
                        furnishing = "fully-furnished"

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

                    profile = p.get("profile") or {}
                    seller_name = p.get("CONTACT_NAME") or profile.get("CONTACT_NAME") or p.get("DEALER_NAME") or "Property Owner"
                    seller_company = p.get("CONTACT_COMPANY_NAME") or profile.get("CONTACT_COMPANY_NAME") or ""
                    seller_photo = p.get("DEALER_PHOTO_URL") or profile.get("PHOTO_URL") or ""
                    seller_type = str(p.get("USER_TYPE") or p.get("SELLER_TYPE") or "owner").lower()

                    desc = p.get("DESCRIPTION")
                    if not desc or len(desc) < 30:
                        desc = (
                            f"{title} in {society or locality}, {item_city}. "
                            f"Area of {area_sqft} sq.ft. with {bathrooms} bathrooms. "
                            f"Available for {purpose}. Close to major transport routes, markets, and prime conveniences."
                        )

                    items.append({
                        "title": title,
                        "type": prop_type,
                        "category_name": cat_name,
                        "purpose": purpose,
                        "price": price,
                        "bedrooms": bedrooms if prop_type != "plot" else 0,
                        "bathrooms": bathrooms if prop_type != "plot" else 0,
                        "area_sqft": area_sqft,
                        "city": item_city,
                        "state": item_state,
                        "locality": locality,
                        "furnishing": furnishing if prop_type != "plot" else "unfurnished",
                        "images": clean_imgs,
                        "contact_phone": f"+91 {random.randint(70000, 99999)} {random.randint(10000, 99999)}",
                        "description": desc,
                        "seller_name": seller_name,
                        "seller_company": seller_company,
                        "seller_photo": seller_photo,
                        "seller_type": seller_type,
                    })
        except Exception as e:
            sys.stderr.write(f"JSON parsing error: {e}\n")

    # Priority 2: Fallback to HTML card extraction if __initialData__ was absent
    if not items:
        soup = BeautifulSoup(response.text, "html.parser")
        cards = soup.find_all("div", class_=re.compile(r"tupleNew__outerTupleWrap|tupleNew__tupleWrap"))
        for idx, card in enumerate(cards):
            if len(items) >= max_items:
                break
            try:
                title_el = card.find(["h2", "a"], class_=re.compile(r"tupleNew__propType|tupleTitle"))
                title = title_el.get_text(" ", strip=True) if title_el else f"Property in {city_name}"

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

                item_city, item_state = resolve_location(url)
                locality = item_city
                loc_match = re.search(r"in\s+(.+?)(?:,|$)", title, re.I)
                if loc_match:
                    locality = loc_match.group(1).strip()

                prop_type, cat_name = detect_type_and_category(title)
                purpose = detect_purpose(url)

                furnishing = "semi-furnished"
                card_text = card.get_text().lower()
                if "fully furnished" in card_text or "fully-furnished" in card_text:
                    furnishing = "fully-furnished"
                elif "unfurnished" in card_text:
                    furnishing = "unfurnished"

                imgs = []
                for img in card.find_all("img"):
                    src = img.get("src") or img.get("data-src")
                    if src and ("imagecdn.99acres.com" in src or "newprojects.99acres.com" in src):
                        imgs.append(src)
                if not imgs:
                    imgs = random.sample(FALLBACK_IMAGES, min(3, len(FALLBACK_IMAGES)))

                items.append({
                    "title": title,
                    "type": prop_type,
                    "category_name": cat_name,
                    "purpose": purpose,
                    "price": price,
                    "bedrooms": bedrooms if prop_type != "plot" else 0,
                    "bathrooms": bathrooms if prop_type != "plot" else 0,
                    "area_sqft": area_sqft,
                    "city": item_city,
                    "state": item_state,
                    "locality": locality,
                    "furnishing": furnishing if prop_type != "plot" else "unfurnished",
                    "images": imgs,
                    "contact_phone": f"+91 {random.randint(70000, 99999)} {random.randint(10000, 99999)}",
                    "description": f"{title} available for {purpose} in {locality}, {item_city}. Area of {area_sqft} sq.ft.",
                    "seller_name": "Property Owner",
                    "seller_company": "",
                    "seller_photo": "",
                    "seller_type": "owner",
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
    default_user_id = user_row[0] if user_row else 1

    saved = 0
    for idx, item in enumerate(items):
        slug_base = re.sub(r"[^a-zA-Z0-9]+", "-", item["title"].lower()).strip("-")
        slug = f"{slug_base}-{random.randint(10000, 99999)}"

        # Category Lookup
        cat_name = item.get("category_name", "Flat / Apartment")
        cursor.execute("SELECT id FROM categories WHERE name LIKE ? LIMIT 1", (f"%{cat_name}%",))
        cat_row = cursor.fetchone()
        category_id = cat_row[0] if cat_row else 1

        # Seller Account Lookup or Create
        seller_name = item.get("seller_name", "").strip()
        user_id = default_user_id
        if seller_name and seller_name.lower() != "property owner":
            phone = item.get("contact_phone", "")
            cursor.execute("SELECT id FROM users WHERE phone = ? LIMIT 1", (phone,))
            existing_user = cursor.fetchone()
            if existing_user:
                user_id = existing_user[0]
            else:
                clean_phone = re.sub(r"\D", "", phone)[-4:] or str(random.randint(1000, 9999))
                email = f"{re.sub(r'[^a-z0-9]', '', seller_name.lower())[:15]}.{clean_phone}@unlockrentals.com"
                cursor.execute("""
                    INSERT INTO users (name, email, phone, role, password, email_verified_at, phone_verified_at, created_at, updated_at)
                    VALUES (?, ?, ?, 'owner', '$2y$12$eG84c1wN5/H47e/uV22p8ex3hR/B2Cq.Q9g9xQ1q1B5tW3.t0bZzW', datetime('now'), datetime('now'), datetime('now'), datetime('now'))
                """, (seller_name, email, phone))
                user_id = cursor.lastrowid

        cursor.execute("""
            INSERT INTO properties (
                user_id, category_id, title, slug, description, type, purpose,
                price, price_period, state, location, locality, address,
                contact_phone, bedrooms, bathrooms, area_sqft, furnishing,
                status, is_featured, is_booked, approved_at, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'month', ?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved', ?, 0, datetime('now'), datetime('now'), datetime('now'))
        """, (
            user_id, category_id, item["title"], slug, item["description"],
            item.get("type", "house"), item.get("purpose", "rent"),
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
        print(f"  [✓] Imported ID #{prop_id}: '{item['title']}' | ₹{int(item['price']):,} [{item.get('purpose', 'rent')}]")

    conn.commit()
    conn.close()
    return saved

def generate_99acres_url(city_name: str, purpose: str = "rent", property_type: str = "all") -> str:
    city_slug = re.sub(r"[^a-zA-Z0-9]+", "-", city_name.lower()).strip("-")
    purp = "sale" if str(purpose).lower() in ["buy", "sale", "s"] else "rent"
    
    pt = str(property_type).lower().strip()
    if "builder" in pt:
        type_slug = "builder-floor"
    elif "flat" in pt or "apartment" in pt:
        type_slug = "flats"
    elif "villa" in pt or "house" in pt:
        type_slug = "independent-house"
    elif "plot" in pt or "land" in pt:
        type_slug = "plots"
    elif "commercial" in pt or "office" in pt or "shop" in pt:
        type_slug = "commercial-property"
    elif "pg" in pt or "hostel" in pt:
        type_slug = "pg-hostels"
    else:
        type_slug = "property"

    if purp == "sale" and city_slug == "indore" and type_slug == "property":
        return "https://www.99acres.com/search/property/buy/indore?city=142&preference=S&res_com=R"

    return f"https://www.99acres.com/{type_slug}-for-{purp}-in-{city_slug}-ffid"

def fetch_properties_smart(url: str, city_name: str, state_name: str, max_items: int = 10, purpose: str = "rent", engine: str = "playwright", is_json: bool = False):
    items = []
    # 1. Primary: Use Playwright browser automation for deep rendering & maximum image extraction
    if engine in ["playwright", "auto", "default"] and PLAYWRIGHT_INSTALLED and scrape_with_playwright:
        try:
            if not is_json:
                print(f"[*] Crawling with Playwright Browser Engine: {city_name} -> {url}...")
            items = scrape_with_playwright(url, city_name, state_name, max_items=max_items, target_purpose=purpose)
            if not is_json and items:
                print(f"[✓] Playwright fetched {len(items)} listings with high-res galleries!")
        except Exception as e:
            sys.stderr.write(f"Playwright attempt warning ({e}), falling back to stealth HTTP...\n")

    # 2. Resilient Fallback: curl_cffi stealth HTTP engine
    if not items:
        if not is_json:
            print(f"[*] Crawling with Stealth HTTP Engine: {city_name} -> {url}...")
        cfg = {"url": url, "state": state_name}
        items = crawl_items_from_city(city_name, cfg, max_items=max_items)

    return items

if __name__ == "__main__":
    args = sys.argv[1:]
    is_json = "--json" in args

    engine = "playwright"
    for a in args:
        if a.startswith("--engine="):
            engine = a.split("=", 1)[1].lower()

    clean_args = [a for a in args if not a.startswith("--")]

    target_city = clean_args[0] if len(clean_args) > 0 else "Delhi"
    max_count = int(clean_args[1]) if len(clean_args) > 1 else 10
    target_purpose = clean_args[2] if len(clean_args) > 2 else "rent"
    target_type = clean_args[3] if len(clean_args) > 3 else "all"

    # Support custom 99acres URL directly
    if target_city.startswith("http://") or target_city.startswith("https://"):
        custom_url = target_city
        city_name, state = resolve_location(custom_url)

        if not is_json:
            print(f"\n[*] Crawling custom URL for {city_name}, {state} (up to {max_count} listings, engine={engine})...")
        items = fetch_properties_smart(custom_url, city_name, state, max_items=max_count, purpose=target_purpose, engine=engine, is_json=is_json)

        if is_json:
            print("===JSON_START===")
            print(json.dumps(items))
            print("===JSON_END===")
        else:
            save_items_to_sqlite(items)
            print(f"\n[✓] Done! Stored {len(items)} properties.")

    elif target_city.lower() in ["all", "all-cities", "all_cities"]:
        ALL_CITIES = [
            ("Delhi", "Delhi"),
            ("Mumbai", "Maharashtra"),
            ("Bangalore", "Karnataka"),
            ("Gurgaon", "Haryana"),
            ("Noida", "Uttar Pradesh"),
            ("Pune", "Maharashtra"),
            ("Hyderabad", "Telangana"),
            ("Indore", "Madhya Pradesh"),
            ("Kolkata", "West Bengal"),
            ("Chennai", "Tamil Nadu"),
            ("Jaipur", "Rajasthan"),
            ("Lucknow", "Uttar Pradesh"),
            ("Chandigarh", "Punjab"),
            ("Ahmedabad", "Gujarat"),
        ]
        all_items = []
        per_city = max(2, max_count // len(ALL_CITIES)) if max_count > 10 else max_count
        for c_name, c_state in ALL_CITIES:
            c_url = generate_99acres_url(c_name, target_purpose, target_type)
            if not is_json:
                print(f"\n[*] Crawling {c_name} ({target_purpose}, {target_type}, engine={engine}) -> {c_url}...")
            c_items = fetch_properties_smart(c_url, c_name, c_state, max_items=per_city, purpose=target_purpose, engine=engine, is_json=is_json)
            all_items.extend(c_items)
            time.sleep(random.uniform(0.8, 1.8))

        if is_json:
            print("===JSON_START===")
            print(json.dumps(all_items))
            print("===JSON_END===")
        else:
            save_items_to_sqlite(all_items)
            print(f"\n[🎉] Done! Stored {len(all_items)} properties across all cities.")

    else:
        # Resolve single city name passed
        city_key = target_city.lower().strip()
        matched = CITY_STATE_MAP.get(city_key)
        city_name = matched[0] if matched else target_city.title()
        state = matched[1] if matched else "India"

        url = generate_99acres_url(city_name, target_purpose, target_type)
        if not is_json:
            print(f"\n[*] Crawling {city_name}, {state} ({target_purpose}, {target_type}, engine={engine}) -> {url}...")
        items = fetch_properties_smart(url, city_name, state, max_items=max_count, purpose=target_purpose, engine=engine, is_json=is_json)

        if is_json:
            print("===JSON_START===")
            print(json.dumps(items))
            print("===JSON_END===")
        else:
            save_items_to_sqlite(items)
            print(f"\n[✓] Done! Stored {len(items)} properties.")
