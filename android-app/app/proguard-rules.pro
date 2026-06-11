# Add project specific ProGuard rules here.

# Keep WebView JavaScript interface
-keepclassmembers class * {
    @android.webkit.JavascriptInterface <methods>;
}

# Keep application class
-keep class com.example.unlockrentals.** { *; }

# WebView
-keepclassmembers class android.webkit.WebView {
   public *;
}

# Prevent stripping of download manager
-keep class android.app.DownloadManager { *; }
