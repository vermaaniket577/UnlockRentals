package com.unlockrentals.app;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.webkit.GeolocationPermissions;
import android.webkit.ValueCallback;
import android.webkit.WebChromeClient;
import android.webkit.WebResourceError;
import android.webkit.WebResourceRequest;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ProgressBar;
import android.widget.Toast;
import androidx.activity.OnBackPressedCallback;
import androidx.appcompat.app.AppCompatActivity;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

public class MainActivity extends AppCompatActivity {

    private static final String APP_URL = "https://www.unlockrentals.com";
    private WebView webView;
    private ProgressBar progressBar;
    private SwipeRefreshLayout swipeRefreshLayout;
    private ValueCallback<Uri[]> uploadMessage;
    private static final int FILE_CHOOSER_RESULT_CODE = 1;
    private static final int LOCATION_PERMISSION_REQUEST_CODE = 1002;
    private String pendingGeoOrigin;
    private GeolocationPermissions.Callback pendingGeoCallback;

    @SuppressLint("SetJavaScriptEnabled")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        webView = findViewById(R.id.webView);
        progressBar = findViewById(R.id.progressBar);
        swipeRefreshLayout = findViewById(R.id.swipeRefresh);

        // Window Hardware Acceleration & 60/120Hz Ultra-Smooth Pipeline
        getWindow().setFlags(
            android.view.WindowManager.LayoutParams.FLAG_HARDWARE_ACCELERATED,
            android.view.WindowManager.LayoutParams.FLAG_HARDWARE_ACCELERATED
        );

        // Hardware Acceleration & High-Performance Touch Settings
        webView.setLayerType(View.LAYER_TYPE_HARDWARE, null);
        webView.setOverScrollMode(View.OVER_SCROLL_IF_CONTENT_SCROLLS);
        webView.setVerticalScrollBarEnabled(false);
        webView.setHorizontalScrollBarEnabled(false);
        webView.setScrollBarStyle(View.SCROLLBARS_INSIDE_OVERLAY);
        webView.setClickable(true);
        webView.setFocusable(true);
        webView.setFocusableInTouchMode(true);
        webView.setHapticFeedbackEnabled(true);

        // Configure CookieManager
        android.webkit.CookieManager cookieManager = android.webkit.CookieManager.getInstance();
        cookieManager.setAcceptCookie(true);
        cookieManager.setAcceptThirdPartyCookies(webView, true);

        // Configure WebSettings for instant tap response and fastest page rendering
        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webSettings.setDomStorageEnabled(true);
        webSettings.setDatabaseEnabled(true);
        webSettings.setAllowFileAccess(true);
        webSettings.setAllowContentAccess(true);
        webSettings.setLoadsImagesAutomatically(true);
        webSettings.setMixedContentMode(WebSettings.MIXED_CONTENT_ALWAYS_ALLOW);
        webSettings.setCacheMode(WebSettings.LOAD_DEFAULT);
        webSettings.setGeolocationEnabled(true);
        webSettings.setRenderPriority(WebSettings.RenderPriority.HIGH);
        webSettings.setEnableSmoothTransition(true);

        // Offscreen Pre-Raster tiles: eliminates white flashes, checkerboarding, and opens pages smoothly
        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.M) {
            webSettings.setOffscreenPreRaster(true);
        }
        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.O) {
            webSettings.setSafeBrowsingEnabled(false);
        }

        // Enable Chromium ServiceWorker Caching
        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.N) {
            try {
                android.webkit.ServiceWorkerController.getInstance().setServiceWorkerClient(new android.webkit.ServiceWorkerClient() {
                    @Override
                    public android.webkit.WebResourceResponse shouldInterceptRequest(WebResourceRequest request) {
                        return null;
                    }
                });
            } catch (Exception ignored) {}
        }

        webSettings.setUserAgentString(webSettings.getUserAgentString() + " UnlockRentalsMobileApp/1.0");

        // Swipe-to-refresh: prevent touch intercept conflicts during scrolling and tapping
        swipeRefreshLayout.setColorSchemeColors(getResources().getColor(R.color.primary, getTheme()));
        swipeRefreshLayout.setOnChildScrollUpCallback((parent, child) -> {
            if (webView != null) {
                return webView.getScrollY() > 0;
            }
            return false;
        });
        swipeRefreshLayout.setOnRefreshListener(() -> webView.reload());

        // WebView Client
        webView.setWebViewClient(new WebViewClient() {
            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {
                progressBar.setVisibility(View.VISIBLE);
            }

            @Override
            public void onPageFinished(WebView view, String url) {
                progressBar.setVisibility(View.GONE);
                swipeRefreshLayout.setRefreshing(false);
            }

            @Override
            public void onReceivedError(WebView view, WebResourceRequest request, WebResourceError error) {
                swipeRefreshLayout.setRefreshing(false);
                progressBar.setVisibility(View.GONE);
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, WebResourceRequest request) {
                String url = request.getUrl().toString();

                // 1. Handle Custom Scheme: unlockrentals://auth/callback?token=...
                if (url.startsWith("unlockrentals://")) {
                    handleIncomingUri(request.getUrl());
                    return true;
                }
                
                // 2. Handle WhatsApp, Phone calls, Email, and Maps intents
                if (url.startsWith("tel:") || url.startsWith("whatsapp:") || url.startsWith("mailto:") || url.startsWith("geo:")) {
                    try {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                        startActivity(intent);
                        return true;
                    } catch (Exception e) {
                        Toast.makeText(MainActivity.this, "Application not found for this action", Toast.LENGTH_SHORT).show();
                        return true;
                    }
                }

                // 3. Handle UPI payment apps (GPay, PhonePe, Paytm, BHIM, Cred)
                if (url.startsWith("upi:") || url.startsWith("tez:") || url.startsWith("phonepe:") ||
                    url.startsWith("paytmmp:") || url.startsWith("bhim:") || url.startsWith("credpay:")) {
                    try {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                        startActivity(intent);
                        return true;
                    } catch (Exception e) {
                        Toast.makeText(MainActivity.this, "No compatible UPI payment app found on device", Toast.LENGTH_SHORT).show();
                        return true;
                    }
                }

                // 4. Handle Android Intent URIs from payment gateways
                if (url.startsWith("intent:") || url.startsWith("intent://")) {
                    try {
                        Intent intent = Intent.parseUri(url, Intent.URI_INTENT_SCHEME);
                        if (intent != null) {
                            if (getPackageManager().resolveActivity(intent, 0) != null) {
                                startActivity(intent);
                            } else {
                                String fallbackUrl = intent.getStringExtra("browser_fallback_url");
                                if (fallbackUrl != null && !fallbackUrl.isEmpty()) {
                                    webView.loadUrl(fallbackUrl);
                                } else {
                                    Toast.makeText(MainActivity.this, "Requested payment app is not installed", Toast.LENGTH_SHORT).show();
                                }
                            }
                            return true;
                        }
                    } catch (Exception e) {
                        // Fallback
                    }
                }

                // 5. Open OAuth providers (Google / Facebook) in external browser to comply with Google OAuth policy and prevent 403 disallowed_useragent
                if (url.contains("/auth/google") || url.contains("/auth/facebook") ||
                    url.contains("accounts.google.com") || url.contains("facebook.com/v") || url.contains("m.facebook.com/dialog/oauth")) {
                    try {
                        Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                        startActivity(browserIntent);
                        return true;
                    } catch (Exception e) {
                        return false;
                    }
                }

                // 6. Keep all UnlockRentals pages strictly inside the WebView
                if (url.contains("unlockrentals.com") || url.contains("10.0.2.2") || url.contains("localhost")) {
                    return false;
                }
                
                return false;
            }
        });

        // WebChrome Client (Progress and File Uploads for property images)
        webView.setWebChromeClient(new WebChromeClient() {
            @Override
            public void onProgressChanged(WebView view, int newProgress) {
                if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.N) {
                    progressBar.setProgress(newProgress, true);
                } else {
                    progressBar.setProgress(newProgress);
                }
                if (newProgress >= 100) {
                    progressBar.animate().alpha(0f).setDuration(220).withEndAction(() -> {
                        progressBar.setVisibility(View.GONE);
                        progressBar.setAlpha(1f);
                    }).start();
                } else {
                    progressBar.setAlpha(1f);
                    progressBar.setVisibility(View.VISIBLE);
                }
            }

            @Override
            public boolean onShowFileChooser(WebView webView, ValueCallback<Uri[]> filePathCallback, FileChooserParams fileChooserParams) {
                if (uploadMessage != null) {
                    uploadMessage.onReceiveValue(null);
                }
                uploadMessage = filePathCallback;

                Intent intent = fileChooserParams.createIntent();
                try {
                    startActivityForResult(intent, FILE_CHOOSER_RESULT_CODE);
                } catch (Exception e) {
                    uploadMessage = null;
                    return false;
                }
                return true;
            }

            @Override
            public void onGeolocationPermissionsShowPrompt(String origin, GeolocationPermissions.Callback callback) {
                if (checkSelfPermission(android.Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED ||
                    checkSelfPermission(android.Manifest.permission.ACCESS_COARSE_LOCATION) == PackageManager.PERMISSION_GRANTED) {
                    callback.invoke(origin, true, false);
                } else {
                    pendingGeoOrigin = origin;
                    pendingGeoCallback = callback;
                    requestPermissions(new String[]{
                        android.Manifest.permission.ACCESS_FINE_LOCATION,
                        android.Manifest.permission.ACCESS_COARSE_LOCATION
                    }, LOCATION_PERMISSION_REQUEST_CODE);
                }
            }
        });

        // Modern OnBackPressed handling
        getOnBackPressedDispatcher().addCallback(this, new OnBackPressedCallback(true) {
            @Override
            public void handleOnBackPressed() {
                if (webView.canGoBack()) {
                    webView.goBack();
                } else {
                    finish();
                }
            }
        });

        // Load the initial URL or handle incoming auth intent
        if (!handleIncomingUri(getIntent() != null ? getIntent().getData() : null)) {
            webView.loadUrl(APP_URL);
        }
    }

    private boolean handleIncomingUri(Uri uri) {
        if (uri == null) return false;
        String scheme = uri.getScheme() != null ? uri.getScheme().toLowerCase() : "";
        String host = uri.getHost() != null ? uri.getHost().toLowerCase() : "";

        // 1. Handle custom scheme: unlockrentals://auth/callback?token=XYZ
        if ("unlockrentals".equals(scheme) && ("auth".equals(host) || (uri.getPath() != null && uri.getPath().contains("callback")))) {
            String token = uri.getQueryParameter("token");
            if (token != null && !token.isEmpty()) {
                String loginUrl = APP_URL + "/auth/token-login?token=" + token;
                webView.loadUrl(loginUrl);
                return true;
            }
        }

        // 2. Handle url parameter passed via custom scheme (e.g. unlockrentals://open?url=https://...)
        if ("unlockrentals".equals(scheme)) {
            String targetUrl = uri.getQueryParameter("url");
            if (targetUrl != null && !targetUrl.isEmpty() && (targetUrl.startsWith("http://") || targetUrl.startsWith("https://"))) {
                webView.loadUrl(targetUrl);
                return true;
            }

            // Path based scheme: unlockrentals://property/123 or unlockrentals://launch
            String path = uri.getPath() != null ? uri.getPath() : "";
            if (!host.isEmpty() && !"launch".equals(host) && !"open".equals(host)) {
                String query = uri.getQuery() != null ? "?" + uri.getQuery() : "";
                webView.loadUrl(APP_URL + "/" + host + path + query);
                return true;
            } else if (!path.isEmpty() && !"/".equals(path)) {
                String query = uri.getQuery() != null ? "?" + uri.getQuery() : "";
                webView.loadUrl(APP_URL + path + query);
                return true;
            }
            webView.loadUrl(APP_URL);
            return true;
        }

        // 3. Handle direct deep link HTTP / HTTPS URLs (e.g. from Google Search or browser links)
        if ("http".equals(scheme) || "https".equals(scheme)) {
            webView.loadUrl(uri.toString());
            return true;
        }

        return false;
    }

    @Override
    protected void onNewIntent(Intent intent) {
        super.onNewIntent(intent);
        setIntent(intent);
        if (intent != null && intent.getData() != null) {
            handleIncomingUri(intent.getData());
        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if (requestCode == FILE_CHOOSER_RESULT_CODE) {
            if (uploadMessage != null) {
                Uri[] results = null;
                if (resultCode == RESULT_OK && data != null) {
                    String dataString = data.getDataString();
                    if (dataString != null) {
                        results = new Uri[]{Uri.parse(dataString)};
                    }
                }
                uploadMessage.onReceiveValue(results);
                uploadMessage = null;
            }
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, String[] permissions, int[] grantResults) {
        if (requestCode == LOCATION_PERMISSION_REQUEST_CODE) {
            boolean granted = false;
            if (grantResults != null && grantResults.length > 0) {
                for (int res : grantResults) {
                    if (res == PackageManager.PERMISSION_GRANTED) {
                        granted = true;
                        break;
                    }
                }
            }
            if (pendingGeoCallback != null) {
                pendingGeoCallback.invoke(pendingGeoOrigin, granted, false);
                pendingGeoOrigin = null;
                pendingGeoCallback = null;
            }
        }
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
    }

    @Override
    protected void onResume() {
        super.onResume();
        if (webView != null) {
            webView.onResume();
            webView.resumeTimers();
            try {
                webView.evaluateJavascript(
                    "(function(){ window.dispatchEvent(new Event('app_resumed')); if(typeof window.checkPendingPaymentOnResume==='function'){ window.checkPendingPaymentOnResume(); } })();",
                    null
                );
            } catch (Exception ignored) {}
        }
    }

    @Override
    protected void onPause() {
        super.onPause();
        if (webView != null) {
            webView.onPause();
            webView.pauseTimers();
        }
    }

    @Override
    protected void onDestroy() {
        if (webView != null) {
            webView.destroy();
        }
        super.onDestroy();
    }
}
