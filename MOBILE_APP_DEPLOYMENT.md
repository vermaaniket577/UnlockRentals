# UnlockRentals Mobile App — Deployment Guide

Complete guide for building and deploying the UnlockRentals mobile apps to Google Play Store, Apple App Store, and direct APK distribution.

---

## Table of Contents

1. [Android — Build & Deploy](#android--build--deploy)
2. [iOS — Build & Deploy](#ios--build--deploy)
3. [Direct APK Distribution](#direct-apk-distribution)
4. [Website Configuration](#website-configuration)
5. [App Store Listing Requirements](#app-store-listing-requirements)

---

## Android — Build & Deploy

### Prerequisites

- **Android Studio** (latest version) or Android command-line tools
- **JDK 17** (included with Android Studio)
- **Android SDK** with API level 36

### 1. Open the Project

```bash
# Navigate to the Android app directory
cd android-app

# Open in Android Studio
# Or build from command line
```

### 2. Configure Production URL

Edit `app/src/main/res/values/strings.xml`:
```xml
<string name="production_url">https://your-actual-domain.com</string>
```

### 3. Generate Release Keystore

```bash
keytool -genkey -v -keystore keystore/unlockrentals-release.jks \
  -alias unlockrentals \
  -keyalg RSA -keysize 2048 -validity 10000 \
  -storepass YOUR_KEYSTORE_PASSWORD \
  -keypass YOUR_KEY_PASSWORD \
  -dname "CN=UnlockRentals, OU=Mobile, O=UnlockRentals, L=Mumbai, ST=Maharashtra, C=IN"
```

> ⚠️ **IMPORTANT**: Keep your keystore file and passwords safe. You'll need the same keystore for all future updates. If you lose it, you cannot update the app on Google Play.

### 4. Configure Signing

Edit `app/build.gradle.kts` — uncomment the signing config:

```kotlin
signingConfigs {
    create("release") {
        storeFile = file("keystore/unlockrentals-release.jks")
        storePassword = "YOUR_KEYSTORE_PASSWORD"
        keyAlias = "unlockrentals"
        keyPassword = "YOUR_KEY_PASSWORD"
    }
}

buildTypes {
    release {
        signingConfig = signingConfigs.getByName("release")
        // ...
    }
}
```

### 5. Build Release APK

```bash
# From the android-app/ directory:

# Build debug APK (for testing)
./gradlew assembleDebug

# Build release APK (for distribution)
./gradlew assembleRelease

# Build App Bundle (for Google Play - recommended)
./gradlew bundleRelease
```

Output files:
- **Debug APK**: `app/build/outputs/apk/debug/app-debug.apk`
- **Release APK**: `app/build/outputs/apk/release/app-release.apk`
- **App Bundle**: `app/build/outputs/bundle/release/app-release.aab`

### 6. Deploy to Google Play

1. Go to [Google Play Console](https://play.google.com/console)
2. Create a new application
3. Fill in the app listing details (see [App Store Listing Requirements](#app-store-listing-requirements))
4. Upload the `.aab` (App Bundle) file
5. Set up pricing & distribution
6. Submit for review

### 7. Update Deep Links (App Links)

For verified deep links, you need to host a Digital Asset Links file:

Create `/.well-known/assetlinks.json` on your web server:
```json
[{
  "relation": ["delegate_permission/common.handle_all_urls"],
  "target": {
    "namespace": "android_app",
    "package_name": "com.example.unlockrentals",
    "sha256_cert_fingerprints": ["YOUR_SHA256_FINGERPRINT"]
  }
}]
```

Get your SHA-256 fingerprint:
```bash
keytool -list -v -keystore keystore/unlockrentals-release.jks -alias unlockrentals
```

---

## iOS — Build & Deploy

### Prerequisites

- **Mac** with latest macOS
- **Xcode** (latest version)
- **Apple Developer Account** ($99/year) — [developer.apple.com](https://developer.apple.com)

### 1. Open the Project

```bash
# Navigate to the iOS app directory
cd ios-app

# Open in Xcode
open UnlockRentals.xcodeproj
# Or create the project in Xcode using the source files
```

### 2. Create Xcode Project

Since the iOS project is provided as source files (not an .xcodeproj), you need to create the Xcode project:

1. Open Xcode → File → New → Project
2. Select "App" template
3. Product Name: `UnlockRentals`
4. Team: Your Apple Developer Team
5. Organization Identifier: `com.unlockrentals`
6. Interface: `Storyboard`
7. Language: `Swift`
8. Save to the `ios-app/` directory
9. Replace the generated source files with ours:
   - Replace `ViewController.swift`
   - Replace `AppDelegate.swift`
   - Replace `Info.plist`
   - Replace `LaunchScreen.storyboard`
   - Add `UnlockRentals.entitlements`
   - Add the Assets catalog

### 3. Configure Signing

1. Select the project in Xcode navigator
2. Go to "Signing & Capabilities" tab
3. Select your Team
4. Enable "Automatically manage signing"
5. Add "Associated Domains" capability
6. Add domains: `applinks:unlockrentals.in`, `applinks:www.unlockrentals.in`

### 4. Build & Archive

1. Select "Any iOS Device" as the build target
2. Product → Archive
3. In the Archives organizer, click "Distribute App"
4. Select "App Store Connect"
5. Follow the upload wizard

### 5. Deploy to App Store

1. Go to [App Store Connect](https://appstoreconnect.apple.com)
2. Create a new app
3. Fill in the app listing details
4. Add the uploaded build
5. Submit for review

### 6. Universal Links

Host the `apple-app-site-association` file at:
```
https://unlockrentals.in/.well-known/apple-app-site-association
```

Copy `ios-app/apple-app-site-association` to your web server's `.well-known/` directory. Make sure it's served with `Content-Type: application/json`.

---

## Direct APK Distribution

For users who can't access Google Play (or for beta testing):

### 1. Build the APK

```bash
cd android-app
./gradlew assembleRelease
```

### 2. Upload to Your Server

Upload `app-release.apk` to your web server:
```bash
# Example: upload to public/downloads/
cp app/build/outputs/apk/release/app-release.apk /path/to/public/downloads/UnlockRentals.apk
```

### 3. Configure in Admin Panel

1. Go to Admin → Settings
2. Under "Mobile App Distribution", paste the APK URL in the "APK Direct Download URL" field:
   ```
   https://unlockrentals.in/downloads/UnlockRentals.apk
   ```
3. Save settings

The "Download APK Directly" button will automatically appear on the app download section and the `/app` download page.

---

## Website Configuration

### Admin Settings

Go to **Admin → Settings → Mobile App Distribution** and fill in:

| Field | Example |
|-------|---------|
| Google Play URL | `https://play.google.com/store/apps/details?id=com.example.unlockrentals` |
| Apple App Store URL | `https://apps.apple.com/app/unlockrentals/id1234567890` |
| APK Direct Download URL | `https://unlockrentals.in/downloads/UnlockRentals.apk` |

### Pages & Routes

| Route | Description |
|-------|-------------|
| `/app` | Premium app download landing page with platform detection |
| `/download/apk` | Direct APK file download endpoint |

### Store Buttons Appear In

1. **App Download Section** — Main homepage section with mockup
2. **Footer** — Brand column with compact store badges
3. **App Download Page** (`/app`) — Dedicated full-page experience

---

## App Store Listing Requirements

### Required Assets

| Asset | Android (Google Play) | iOS (App Store) |
|-------|----------------------|-----------------|
| App Icon | 512×512 PNG | 1024×1024 PNG |
| Feature Graphic | 1024×500 PNG | N/A |
| Screenshots (Phone) | Min 2, 16:9 or 9:16 | Min 3, for 6.5" & 5.5" |
| Screenshots (Tablet) | Optional but recommended | Required for iPad |
| Short Description | Max 80 chars | N/A |
| Full Description | Max 4000 chars | Max 4000 chars |
| Privacy Policy URL | Required | Required |

### Suggested App Description

```
UnlockRentals — Find Your Perfect Home Across India

Browse 10,000+ verified rental properties, PG stays, shops, and commercial spaces across India's top cities.

✅ 100% Verified Listings — Every property checked by our team
✅ Zero Brokerage — Connect directly with property owners
✅ Smart Search — Filter by location, budget, rooms, and type
✅ Pan-India Coverage — Delhi, Mumbai, Bangalore, Pune, and more
✅ 24/7 Concierge Support — Expert help when you need it
✅ Instant Contact — Call or message owners directly

Whether you're looking for a luxury apartment, a budget PG stay, or a commercial shop — UnlockRentals has you covered.

Download now and find your dream home today!
```

### Category & Tags

- **Category**: House & Home / Lifestyle
- **Tags**: rental, property, house, flat, PG, real estate, India, apartment

---

## Troubleshooting

### Android Build Issues

```bash
# Clean build
./gradlew clean

# Clear Gradle cache
./gradlew --stop
rm -rf ~/.gradle/caches/

# Rebuild
./gradlew assembleDebug
```

### WebView Not Loading

1. Check that the production URL in `strings.xml` is correct
2. Ensure the website has a valid SSL certificate
3. Check `network_security_config.xml` for cleartext traffic settings
4. Verify internet permission in `AndroidManifest.xml`

### File Upload Not Working

- Ensure camera and storage permissions are in the manifest
- On Android 10+, scoped storage is handled automatically
- On iOS, camera and photo library permissions must be in Info.plist
