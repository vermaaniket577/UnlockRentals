# 📱 UnlockRentals — Mobile App Suite (Android & iOS)

This folder contains the complete cross-platform mobile app source code for **UnlockRentals**:

1. **Android Native Project** (`mobile_app/android/`) &rarr; Compiles into `.apk` and `.aab` for all Android phones/tablets.
2. **iOS Native Project** (`mobile_app/ios/`) &rarr; Compiles into `.ipa` for Apple iPhones & iPads.
3. **Universal Single-Link Instant Install (PWA)** &rarr; Works on **BOTH** Android and iOS with zero store approval.

---

## 🤖 1. Building the Android APK (`.apk`)

### Prerequisites:
- Download & install [Android Studio](https://developer.android.com/studio).

### Steps to generate your `.apk`:
1. Open **Android Studio**.
2. Click **File &rarr; Open** and select the folder:
   ```
   mobile_app/android
   ```
3. Android Studio will automatically sync Gradle and download dependencies.
4. From the top menu, click:
   **Build &rarr; Build Bundle(s) / APK(s) &rarr; Build APK(s)**.
5. In 1–2 minutes, a popup will appear with a **"locate"** link.
   Your installable APK will be at:
   ```
   mobile_app/android/app/build/outputs/apk/debug/app-debug.apk
   ```
6. Copy `app-debug.apk` to any Android phone and tap **Install**!

### Features included in the Android App:
- ✅ Automatic pull-to-refresh
- ✅ Native top progress bar
- ✅ Full photo picker and file upload for property images
- ✅ Direct integration with WhatsApp (`whatsapp://`), Phone dialer (`tel:`), and Google Maps
- ✅ Hardware back button handling

---

## 🍏 2. Running on iOS (iPhone / iPad)

> *Note: Apple iOS does not allow direct installation of `.apk` files. iOS requires an Xcode project compiled into an `.ipa` or installed via TestFlight/App Store.*

### Steps:
1. Open **Xcode** on a Mac.
2. Create a new iOS App project named **UnlockRentals**.
3. Replace `ViewController.swift`, `AppDelegate.swift`, and `Info.plist` with the files in `mobile_app/ios/`.
4. Connect your iPhone and click **Run (▶)** to install directly onto your device.

---

## ⚡ 3. Universal Instant Install (Works on BOTH Android & iOS Instantly!)

If you want a **single universal link** that any user on **Android OR iPhone** can install without downloading files from computer:

### On Android (Chrome / Samsung Internet / Edge):
1. Open `https://www.unlockrentals.com` on your phone browser.
2. An **"Install UnlockRentals App"** banner will appear automatically at the bottom.
3. Tap **Install** &rarr; the app icon will appear on your phone home screen, opening in full-screen native mode!

### On iOS (Apple iPhone Safari):
1. Open `https://www.unlockrentals.com` in Safari.
2. Tap the **Share** button (the square with an arrow pointing up).
3. Tap **"Add to Home Screen"**.
4. Tap **Add** &rarr; the native UnlockRentals app icon appears on your iPhone home screen!
