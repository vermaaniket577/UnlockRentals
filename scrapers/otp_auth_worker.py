"""
Persistent Playwright OTP Authentication Worker for 99acres.
Keeps the exact browser session open on the OTP screen after clicking 'Continue'
so that when the user submits the OTP via the API/UI, it fills #otp1..#otp4
on the same page without re-triggering a new OTP.
"""

import os
import re
import sys
import json
import time
from playwright.sync_api import sync_playwright

SCRAPER_DIR = os.path.dirname(os.path.abspath(__file__))
AUTH_SESSION_FILE = os.path.join(SCRAPER_DIR, "auth_session.json")
AUTH_META_FILE = os.path.join(SCRAPER_DIR, "auth_session_meta.json")
OTP_STATUS_FILE = os.path.join(SCRAPER_DIR, "otp_worker_status.json")
OTP_SUBMIT_FILE = os.path.join(SCRAPER_DIR, "otp_submit.json")


def find_chrome_executable():
    candidates = [
        r"C:\Program Files (x86)\Google\Chrome\Application\chrome.exe",
        r"C:\Program Files\Google\Chrome\Application\chrome.exe",
        os.path.expanduser(r"~\AppData\Local\Google\Chrome\Application\chrome.exe"),
        "/usr/bin/google-chrome",
        "/usr/bin/google-chrome-stable",
        "/usr/bin/chromium-browser",
        "/usr/bin/chromium",
    ]
    for p in candidates:
        if os.path.isfile(p):
            return p
    return None


def write_status(data: dict):
    with open(OTP_STATUS_FILE, "w", encoding="utf-8") as f:
        json.dump(data, f, indent=2)


def check_logged_in(page) -> dict:
    try:
        return page.evaluate(r"""() => {
            let loggedIn = false;
            let userName = '';
            if (window.__initialData__) {
                const perf = window.__initialData__.performanceInfo;
                if (perf && (perf.loggedIn === 'yes' || perf.loggedIn === true)) loggedIn = true;
                const auth = window.__initialData__.auth || window.__initialData__.user;
                if (auth && (auth.isLoggedIn || auth.userId || auth.profileId)) {
                    loggedIn = true;
                    userName = auth.userName || auth.name || auth.firstName || '';
                }
            }
            const profileEl = document.querySelector('[data-label="USER_PROFILE"], .header__userProfile, [class*="userProfile" i], a[href*="/my-activity"]');
            if (profileEl) {
                loggedIn = true;
                userName = userName || profileEl.innerText.trim();
            }
            const c = document.cookie || '';
            if (c.includes('AUTH_TOKEN') || c.includes('profileId=') || c.includes('isLoggedIn=true') || c.includes('99auth=')) {
                loggedIn = true;
            }
            return { is_logged_in: loggedIn, user_name: userName };
        }""")
    except Exception:
        return {"is_logged_in": False, "user_name": ""}


def run_worker(mobile: str, timeout_seconds: int = 180):
    clean_mobile = re.sub(r"\D", "", str(mobile))[-10:]
    if len(clean_mobile) != 10:
        write_status({"status": "error", "success": False, "message": "Invalid 10-digit mobile number."})
        return

    # Clean stale submit file
    if os.path.exists(OTP_SUBMIT_FILE):
        try:
            os.remove(OTP_SUBMIT_FILE)
        except Exception:
            pass

    chrome_exe = find_chrome_executable()
    launch_kwargs = {
        "headless": True,
        "args": [
            "--disable-blink-features=AutomationControlled",
            "--no-sandbox",
            "--disable-dev-shm-usage",
            "--disable-gpu",
            "--single-process",
            "--no-zygote",
            "--window-size=1920,1080",
        ]
    }
    if chrome_exe:
        launch_kwargs["executable_path"] = chrome_exe

    with sync_playwright() as p:
        browser = None
        try:
            browser = p.chromium.launch(**launch_kwargs)
            context = browser.new_context(
                user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36",
                viewport={"width": 1920, "height": 1080},
                locale="en-US",
                timezone_id="Asia/Kolkata",
            )
            page = context.new_page()
            page.add_init_script("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})")

            page.goto("https://www.99acres.com", timeout=30000, wait_until="domcontentloaded")
            time.sleep(2)

            page_title = ""
            try:
                page_title = page.title() or ""
            except Exception:
                pass
            if "access denied" in page_title.lower():
                write_status({
                    "status": "error",
                    "success": False,
                    "message": "99acres blocked this server IP (Akamai Access Denied). Try using a residential proxy or authenticating from local."
                })
                return

            # 1. Wait for and open Login / Register modal
            login_selectors = [
                '[data-label="LR.INITIATE"]',
                'a:has-text("Login")',
                'button:has-text("Login")',
                '[class*="userProfile" i]',
                '[class*="login" i]',
            ]
            clicked_login = False
            for sel in login_selectors:
                try:
                    loc = page.locator(sel)
                    loc.first.wait_for(state="attached", timeout=5000 if sel == login_selectors[0] else 1500)
                    loc.evaluate_all("els => els.forEach(el => el.click())")
                    clicked_login = True
                    break
                except Exception:
                    continue

            # 2. Wait for visible Phone Number input
            phone_selector = 'input.commonInput__pl80:visible, input[placeholder*="Phone" i]:visible, input[placeholder*="mobile" i]:visible, input[type="tel"]:visible'
            try:
                page.wait_for_selector(phone_selector, timeout=7000)
            except Exception:
                pass

            phone_input = page.locator(phone_selector).first
            if phone_input.count() == 0:
                diag = page.evaluate("""() => ({
                    title: document.title || '',
                    url: location.href,
                    lrCount: document.querySelectorAll('[data-label="LR.INITIATE"]').length,
                    bodySnippet: (document.body ? document.body.innerText : '').slice(0, 120).replace(/\\s+/g, ' ')
                })""")
                write_status({
                    "status": "error",
                    "success": False,
                    "message": f"Phone Number input not found on 99acres login modal (title='{diag.get('title')}', lr={diag.get('lrCount')}, clicked={clicked_login}, text='{diag.get('bodySnippet')}')."
                })
                return

            phone_input.click()
            phone_input.press_sequentially(clean_mobile, delay=35)
            time.sleep(0.8)

            # 3. Click Continue button
            cont_btn = page.locator('button:has-text("Continue"):visible').first
            if cont_btn.count() == 0 or cont_btn.is_disabled():
                write_status({"status": "error", "success": False, "message": "Continue button is disabled or not found."})
                return

            cont_btn.click()

            # 4. Wait for OTP boxes (#otp1) to appear
            try:
                page.wait_for_selector('#otp1:visible', timeout=10000)
            except Exception:
                # Check if an error message appeared
                err_msg = page.evaluate(r"""() => {
                    const err = document.querySelector('[class*="error" i]:not(script), [class*="invalid" i]');
                    return err ? err.innerText.trim() : '';
                }""")
                write_status({
                    "status": "error",
                    "success": False,
                    "message": err_msg or "OTP box did not appear after clicking Continue."
                })
                return

            write_status({
                "status": "otp_ready",
                "success": True,
                "requires_otp": True,
                "mobile": clean_mobile,
                "message": f"OTP sent to +91 {clean_mobile} and OTP box is displayed. Enter 4-digit OTP to verify."
            })

            # 5. Wait for OTP submission via OTP_SUBMIT_FILE
            start = time.time()
            submitted_otp = None
            while time.time() - start < timeout_seconds:
                if os.path.exists(OTP_SUBMIT_FILE):
                    try:
                        with open(OTP_SUBMIT_FILE, "r", encoding="utf-8") as f:
                            sub = json.load(f)
                        submitted_otp = re.sub(r"\D", "", str(sub.get("otp", "")))
                        os.remove(OTP_SUBMIT_FILE)
                        if submitted_otp:
                            break
                    except Exception:
                        pass
                time.sleep(0.3)

            if not submitted_otp:
                write_status({"status": "timeout", "success": False, "message": "OTP entry timed out."})
                return

            # 6. Fill OTP boxes (#otp1..#otp4 or #otp6)
            for idx, digit in enumerate(submitted_otp[:6]):
                box = page.locator(f"#otp{idx + 1}:visible").first
                if box.count() > 0:
                    box.click()
                    box.fill(digit)

            time.sleep(0.5)

            # 7. Click Verify & Continue
            verify_btn = page.locator('button:has-text("Verify & Continue"):visible, button:has-text("Verify"):visible').first
            if verify_btn.count() > 0:
                verify_btn.click()

            time.sleep(4)

            # 8. Check login result
            status_res = check_logged_in(page)
            if status_res.get("is_logged_in"):
                context.storage_state(path=AUTH_SESSION_FILE)
                meta = {
                    "mobile": clean_mobile,
                    "is_logged_in": True,
                    "authenticated_at": time.strftime("%Y-%m-%d %H:%M:%S"),
                    "status": "authenticated",
                    "user_name": status_res.get("user_name") or f"User (+91 {clean_mobile})"
                }
                with open(AUTH_META_FILE, "w", encoding="utf-8") as f:
                    json.dump(meta, f, indent=2)

                write_status({
                    "status": "verified",
                    "success": True,
                    "is_logged_in": True,
                    "mobile": clean_mobile,
                    "user_name": meta["user_name"],
                    "message": f"Successfully logged in with Mobile OTP (+91 {clean_mobile})! Ready to scrape genuine data."
                })
            else:
                err_text = page.evaluate(r"""() => {
                    const el = document.querySelector('[class*="error" i], [class*="errMsg" i]');
                    return el ? el.innerText.trim() : '';
                }""")
                write_status({
                    "status": "otp_error",
                    "success": False,
                    "is_logged_in": False,
                    "mobile": clean_mobile,
                    "message": err_text or "OTP verification failed or invalid OTP. Please try again."
                })

        except Exception as e:
            write_status({"status": "error", "success": False, "message": f"OTP worker error: {str(e)}"})
        finally:
            if browser:
                try:
                    browser.close()
                except Exception:
                    pass


if __name__ == "__main__":
    mob = sys.argv[1] if len(sys.argv) > 1 else ""
    run_worker(mob)
