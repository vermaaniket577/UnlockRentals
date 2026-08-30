package com.example.unlockrentals

import android.Manifest
import android.annotation.SuppressLint
import android.app.DownloadManager
import android.content.Intent
import android.content.pm.PackageManager
import android.graphics.Bitmap
import android.net.ConnectivityManager
import android.net.NetworkCapabilities
import android.net.Uri
import android.os.Build
import android.os.Bundle
import android.os.Environment
import android.os.Handler
import android.os.Looper
import android.view.View
import android.view.animation.AccelerateDecelerateInterpolator
import android.webkit.*
import android.widget.FrameLayout
import android.widget.ProgressBar
import android.widget.Toast
import androidx.activity.OnBackPressedCallback
import androidx.activity.result.ActivityResultLauncher
import androidx.activity.result.contract.ActivityResultContracts
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout

class MainActivity : AppCompatActivity() {

    private lateinit var webView: WebView
    private lateinit var swipeRefresh: SwipeRefreshLayout
    private lateinit var errorView: View
    private lateinit var splashOverlay: View
    private lateinit var progressBar: ProgressBar
    private var fileUploadCallback: ValueCallback<Array<Uri>>? = null
    private var isSplashDismissed = false

    private val fileChooserLauncher: ActivityResultLauncher<Intent> =
        registerForActivityResult(ActivityResultContracts.StartActivityForResult()) { result ->
            val data = result.data
            val results: Array<Uri>? = if (result.resultCode == RESULT_OK && data != null) {
                if (data.dataString != null) {
                    arrayOf(Uri.parse(data.dataString))
                } else if (data.clipData != null) {
                    val clipData = data.clipData!!
                    Array(clipData.itemCount) { i -> clipData.getItemAt(i).uri }
                } else null
            } else null
            fileUploadCallback?.onReceiveValue(results ?: arrayOf())
            fileUploadCallback = null
        }

    private val requestPermissionLauncher =
        registerForActivityResult(ActivityResultContracts.RequestPermission()) { isGranted ->
            if (!isGranted) {
                Toast.makeText(this, "Storage permission needed for downloads", Toast.LENGTH_SHORT).show()
            }
        }

    @SuppressLint("SetJavaScriptEnabled")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // Build layout programmatically with system windows support
        val rootLayout = FrameLayout(this).apply {
            fitsSystemWindows = true
            setBackgroundColor(getColor(R.color.primary_dark))
        }

        // Apply system bar insets dynamically
        ViewCompat.setOnApplyWindowInsetsListener(rootLayout) { view, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            view.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // SwipeRefreshLayout for smooth pull-to-refresh
        swipeRefresh = SwipeRefreshLayout(this).apply {
            setColorSchemeColors(
                getColor(R.color.primary),
                getColor(R.color.primary_light)
            )
            setProgressBackgroundColorSchemeColor(getColor(R.color.white))
            
            // Only trigger refresh if webView is at the very top
            setOnChildScrollUpCallback { _, _ ->
                webView.scrollY > 0
            }
        }

        // High-Performance Hardware-Accelerated WebView
        webView = WebView(this).apply {
            layoutParams = FrameLayout.LayoutParams(
                FrameLayout.LayoutParams.MATCH_PARENT,
                FrameLayout.LayoutParams.MATCH_PARENT
            )
            setLayerType(View.LAYER_TYPE_HARDWARE, null)
            isNestedScrollingEnabled = true
            overScrollMode = View.OVER_SCROLL_NEVER
            isVerticalScrollBarEnabled = false
            isHorizontalScrollBarEnabled = false
            setBackgroundColor(getColor(R.color.primary_dark))
        }

        // Top progress bar for smooth network indication
        progressBar = ProgressBar(this, null, android.R.attr.progressBarStyleHorizontal).apply {
            layoutParams = FrameLayout.LayoutParams(
                FrameLayout.LayoutParams.MATCH_PARENT,
                8
            ).apply {
                gravity = android.view.Gravity.TOP
            }
            isIndeterminate = false
            max = 100
            progressDrawable = resources.getDrawable(android.R.drawable.progress_horizontal, theme).mutate().apply {
                setTint(getColor(R.color.primary))
            }
            visibility = View.GONE
            elevation = 15f
        }

        // Error View (hidden by default)
        errorView = layoutInflater.inflate(R.layout.error_page, null).apply {
            visibility = View.GONE
            findViewById<View>(R.id.btn_retry)?.setOnClickListener {
                visibility = View.GONE
                webView.visibility = View.VISIBLE
                progressBar.visibility = View.VISIBLE
                
                val currentUrl = webView.url
                if (currentUrl.isNullOrEmpty() || currentUrl == "about:blank") {
                    webView.loadUrl(getString(R.string.production_url))
                } else {
                    webView.reload()
                }
            }
        }

        // Instant Native Starting Splash Theme Overlay (Zero Lag & Smooth Transition)
        splashOverlay = layoutInflater.inflate(R.layout.activity_splash, null).apply {
            elevation = 30f
        }

        swipeRefresh.addView(webView)
        rootLayout.addView(swipeRefresh, FrameLayout.LayoutParams(
            FrameLayout.LayoutParams.MATCH_PARENT,
            FrameLayout.LayoutParams.MATCH_PARENT
        ))
        rootLayout.addView(progressBar)
        rootLayout.addView(errorView, FrameLayout.LayoutParams(
            FrameLayout.LayoutParams.MATCH_PARENT,
            FrameLayout.LayoutParams.MATCH_PARENT
        ))
        rootLayout.addView(splashOverlay, FrameLayout.LayoutParams(
            FrameLayout.LayoutParams.MATCH_PARENT,
            FrameLayout.LayoutParams.MATCH_PARENT
        ))

        setContentView(rootLayout)

        // Setup Modern Back Press Dispatcher
        onBackPressedDispatcher.addCallback(this, object : OnBackPressedCallback(true) {
            override fun handleOnBackPressed() {
                if (webView.canGoBack()) {
                    webView.goBack()
                } else {
                    isEnabled = false
                    onBackPressedDispatcher.onBackPressed()
                }
            }
        })

        // Configure WebView settings and clients
        configureWebView()

        // Pull-to-refresh handler
        swipeRefresh.setOnRefreshListener {
            webView.reload()
        }

        // Safety fallback to dismiss splash if network is sluggish
        Handler(Looper.getMainLooper()).postDelayed({
            dismissSplash()
        }, 2200)

        // Load the production URL immediately
        val productionUrl = getString(R.string.production_url)
        webView.loadUrl(productionUrl)
    }

    private fun dismissSplash() {
        if (isSplashDismissed) return
        isSplashDismissed = true

        runOnUiThread {
            splashOverlay.animate()
                .alpha(0f)
                .setDuration(280)
                .setInterpolator(AccelerateDecelerateInterpolator())
                .withEndAction {
                    splashOverlay.visibility = View.GONE
                }
                .start()
        }
    }

    @SuppressLint("SetJavaScriptEnabled")
    private fun configureWebView() {
        val cookieManager = CookieManager.getInstance()
        cookieManager.setAcceptCookie(true)
        cookieManager.setAcceptThirdPartyCookies(webView, true)

        webView.settings.apply {
            javaScriptEnabled = true
            domStorageEnabled = true
            databaseEnabled = true
            loadsImagesAutomatically = true
            allowContentAccess = true
            allowFileAccess = true
            setSupportZoom(false)
            builtInZoomControls = false
            displayZoomControls = false
            useWideViewPort = true
            loadWithOverviewMode = true
            mixedContentMode = WebSettings.MIXED_CONTENT_ALWAYS_ALLOW
            mediaPlaybackRequiresUserGesture = false

            // Blazing Fast Cache-First Mode
            cacheMode = if (isNetworkAvailable()) WebSettings.LOAD_DEFAULT else WebSettings.LOAD_CACHE_ELSE_NETWORK

            // Pre-rasterize offscreen content to eliminate scroll stutter and blank tiles
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                offscreenPreRaster = true
            }

            // Enable geolocation
            setGeolocationEnabled(true)

            // Custom User-Agent - Cleaned of WebView markers for Google OAuth compatibility
            val baseUA = userAgentString.replace("; wv", "").replace("Version/4.0 ", "")
            userAgentString = "$baseUA UnlockRentalsApp/1.3 (Android)"
        }

        // WebViewClient — handles navigation and lifecycle
        webView.webViewClient = object : WebViewClient() {
            override fun shouldOverrideUrlLoading(view: WebView, request: WebResourceRequest): Boolean {
                val url = request.url.toString()
                val checker = UrlNavigationChecker(getString(R.string.production_url))
                return when (checker.determineTarget(url)) {
                    UrlNavigationChecker.NavigationTarget.DIAL -> {
                        startActivity(Intent(Intent.ACTION_DIAL, Uri.parse(url)))
                        true
                    }
                    UrlNavigationChecker.NavigationTarget.SENDTO -> {
                        startActivity(Intent(Intent.ACTION_SENDTO, Uri.parse(url)))
                        true
                    }
                    UrlNavigationChecker.NavigationTarget.WHATSAPP -> {
                        try { startActivity(Intent(Intent.ACTION_VIEW, Uri.parse(url))) }
                        catch (e: Exception) { Toast.makeText(this@MainActivity, "WhatsApp not installed", Toast.LENGTH_SHORT).show() }
                        true
                    }
                    UrlNavigationChecker.NavigationTarget.APP_STORE -> {
                        try { startActivity(Intent(Intent.ACTION_VIEW, Uri.parse(url))) }
                        catch (e: Exception) { /* Ignore */ }
                        true
                    }
                    UrlNavigationChecker.NavigationTarget.UPI -> {
                        try {
                            val intent = Intent(Intent.ACTION_VIEW, Uri.parse(url))
                            startActivity(intent)
                        } catch (e: Exception) {
                            Toast.makeText(this@MainActivity, "No compatible UPI app found on device", Toast.LENGTH_SHORT).show()
                        }
                        true
                    }
                    UrlNavigationChecker.NavigationTarget.INTENT -> {
                        try {
                            val intent = Intent.parseUri(url, Intent.URI_INTENT_SCHEME)
                            if (intent != null) {
                                val resolveInfo = packageManager.resolveActivity(intent, PackageManager.MATCH_DEFAULT_ONLY)
                                if (resolveInfo != null) {
                                    startActivity(intent)
                                } else {
                                    val fallbackUrl = intent.getStringExtra("browser_fallback_url")
                                    if (!fallbackUrl.isNullOrEmpty()) {
                                        webView.loadUrl(fallbackUrl)
                                    } else {
                                        Toast.makeText(this@MainActivity, "Requested payment app is not installed", Toast.LENGTH_SHORT).show()
                                    }
                                }
                            }
                        } catch (e: Exception) {
                            try { startActivity(Intent(Intent.ACTION_VIEW, Uri.parse(url))) } catch (_: Exception) {}
                        }
                        true
                    }
                    UrlNavigationChecker.NavigationTarget.MAPS -> false
                    UrlNavigationChecker.NavigationTarget.INTERNAL -> false
                    UrlNavigationChecker.NavigationTarget.EXTERNAL -> {
                        try { startActivity(Intent(Intent.ACTION_VIEW, Uri.parse(url))) }
                        catch (e: Exception) { /* Ignore */ }
                        true
                    }
                }
            }

            override fun onPageStarted(view: WebView?, url: String?, favicon: Bitmap?) {
                super.onPageStarted(view, url, favicon)
                progressBar.visibility = View.VISIBLE
                progressBar.progress = 15
            }

            override fun onPageCommitVisible(view: WebView?, url: String?) {
                super.onPageCommitVisible(view, url)
                // First meaningful paint ready -> reveal immediately
                dismissSplash()
            }

            override fun onPageFinished(view: WebView?, url: String?) {
                super.onPageFinished(view, url)
                dismissSplash()
                swipeRefresh.isRefreshing = false
                progressBar.visibility = View.GONE

                // Inject CSS to hide web-only prompts in the native app wrapper
                val hideScript = """
                    (function() {
                        var style = document.getElementById('ur-native-app-styles');
                        if (!style) {
                            style = document.createElement('style');
                            style.id = 'ur-native-app-styles';
                            style.innerHTML = '#pwa-install-drawer, .pwa-install-prompt, .app-download-section, .app-dl-section { display: none !important; }';
                            document.head.appendChild(style);
                        }
                    })();
                """.trimIndent()
                webView.evaluateJavascript(hideScript, null)
            }

            override fun onReceivedError(view: WebView?, request: WebResourceRequest?, error: WebResourceError?) {
                super.onReceivedError(view, request, error)
                if (request?.isForMainFrame == true) {
                    dismissSplash()
                    swipeRefresh.isRefreshing = false
                    progressBar.visibility = View.GONE
                    showErrorPage()
                }
            }

            override fun onReceivedHttpError(view: WebView?, request: WebResourceRequest?, errorResponse: WebResourceResponse?) {
                super.onReceivedHttpError(view, request, errorResponse)
                if (request?.isForMainFrame == true && (errorResponse?.statusCode ?: 200) >= 500) {
                    dismissSplash()
                    swipeRefresh.isRefreshing = false
                    progressBar.visibility = View.GONE
                    showErrorPage()
                }
            }

            override fun onReceivedSslError(view: WebView?, handler: SslErrorHandler?, error: android.net.http.SslError?) {
                handler?.proceed()
            }
        }

        // WebChromeClient — handles progressive load reveal & file uploads
        webView.webChromeClient = object : WebChromeClient() {
            override fun onProgressChanged(view: WebView?, newProgress: Int) {
                super.onProgressChanged(view, newProgress)
                progressBar.progress = newProgress
                if (newProgress >= 40) {
                    dismissSplash()
                }
                if (newProgress >= 100) {
                    progressBar.visibility = View.GONE
                }
            }

            override fun onShowFileChooser(
                webView: WebView?,
                filePathCallback: ValueCallback<Array<Uri>>?,
                fileChooserParams: FileChooserParams?
            ): Boolean {
                fileUploadCallback?.onReceiveValue(null)
                fileUploadCallback = filePathCallback

                val intent = fileChooserParams?.createIntent() ?: Intent(Intent.ACTION_GET_CONTENT).apply {
                    addCategory(Intent.CATEGORY_OPENABLE)
                    type = "image/*"
                    putExtra(Intent.EXTRA_ALLOW_MULTIPLE, true)
                }

                try {
                    fileChooserLauncher.launch(intent)
                } catch (e: Exception) {
                    fileUploadCallback?.onReceiveValue(null)
                    fileUploadCallback = null
                    Toast.makeText(this@MainActivity, "Cannot open file chooser", Toast.LENGTH_SHORT).show()
                    return false
                }
                return true
            }

            override fun onGeolocationPermissionsShowPrompt(origin: String?, callback: GeolocationPermissions.Callback?) {
                callback?.invoke(origin, true, false)
            }
        }

        // Download listener
        webView.setDownloadListener { url, userAgent, contentDisposition, mimeType, _ ->
            try {
                if (Build.VERSION.SDK_INT < Build.VERSION_CODES.Q &&
                    checkSelfPermission(Manifest.permission.WRITE_EXTERNAL_STORAGE)
                    != PackageManager.PERMISSION_GRANTED
                ) {
                    requestPermissionLauncher.launch(Manifest.permission.WRITE_EXTERNAL_STORAGE)
                    return@setDownloadListener
                }

                val fileName = URLUtil.guessFileName(url, contentDisposition, mimeType)
                val request = DownloadManager.Request(Uri.parse(url)).apply {
                    setMimeType(mimeType)
                    addRequestHeader("User-Agent", userAgent)
                    setTitle(fileName)
                    setDescription("Downloading $fileName")
                    setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED)
                    setDestinationInExternalPublicDir(Environment.DIRECTORY_DOWNLOADS, fileName)
                }

                val downloadManager = getSystemService(DOWNLOAD_SERVICE) as DownloadManager
                downloadManager.enqueue(request)
                Toast.makeText(this, "Downloading $fileName...", Toast.LENGTH_SHORT).show()
            } catch (e: Exception) {
                Toast.makeText(this, "Download failed: ${e.message}", Toast.LENGTH_SHORT).show()
            }
        }
    }

    private fun showErrorPage() {
        webView.visibility = View.GONE
        errorView.visibility = View.VISIBLE
        swipeRefresh.isRefreshing = false
        progressBar.visibility = View.GONE
    }

    private fun isNetworkAvailable(): Boolean {
        val connectivityManager = getSystemService(CONNECTIVITY_SERVICE) as ConnectivityManager
        val network = connectivityManager.activeNetwork ?: return false
        val capabilities = connectivityManager.getNetworkCapabilities(network) ?: return false
        return capabilities.hasCapability(NetworkCapabilities.NET_CAPABILITY_INTERNET)
    }

    override fun onNewIntent(intent: Intent) {
        super.onNewIntent(intent)
        setIntent(intent)
        // When OAuth callback returns via deep link, load the URL in the WebView
        val data = intent.data
        if (data != null) {
            val url = data.toString()
            val host = data.host ?: ""
            if (host.contains("unlockrentals")) {
                webView.loadUrl(url)
            }
        }
    }

    override fun onResume() {
        super.onResume()
        webView.onResume()
        webView.resumeTimers()
    }

    override fun onPause() {
        super.onPause()
        webView.onPause()
        webView.pauseTimers()
    }

    override fun onDestroy() {
        webView.apply {
            stopLoading()
            loadUrl("about:blank")
            clearHistory()
            removeAllViews()
            destroy()
        }
        super.onDestroy()
    }
}
