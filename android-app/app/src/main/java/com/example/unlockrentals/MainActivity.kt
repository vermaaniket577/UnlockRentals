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
import android.view.View
import android.view.animation.AlphaAnimation
import android.webkit.*
import android.widget.FrameLayout
import android.widget.ProgressBar
import android.widget.Toast
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
    private lateinit var progressBar: ProgressBar
    private var fileUploadCallback: ValueCallback<Array<Uri>>? = null

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

        // Build layout programmatically
        val rootLayout = FrameLayout(this).apply {
            fitsSystemWindows = true
        }

        // Apply system bar insets dynamically
        ViewCompat.setOnApplyWindowInsetsListener(rootLayout) { view, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            view.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // SwipeRefreshLayout for pull-to-refresh
        swipeRefresh = SwipeRefreshLayout(this).apply {
            setColorSchemeColors(
                getColor(R.color.primary),
                getColor(R.color.primary_light)
            )
            setProgressBackgroundColorSchemeColor(getColor(R.color.white))
        }

        // WebView
        webView = WebView(this).apply {
            layoutParams = FrameLayout.LayoutParams(
                FrameLayout.LayoutParams.MATCH_PARENT,
                FrameLayout.LayoutParams.MATCH_PARENT
            )
            visibility = View.INVISIBLE // Hidden until page loads
        }

        // Top progress bar for page loading
        progressBar = ProgressBar(this, null, android.R.attr.progressBarStyleHorizontal).apply {
            layoutParams = FrameLayout.LayoutParams(
                FrameLayout.LayoutParams.MATCH_PARENT,
                6 // 6px height
            ).apply {
                gravity = android.view.Gravity.TOP
            }
            isIndeterminate = false
            max = 100
            progressDrawable = resources.getDrawable(android.R.drawable.progress_horizontal, theme).mutate().apply {
                setTint(getColor(R.color.primary))
            }
            visibility = View.GONE
            elevation = 10f
        }

        // Error View (hidden by default)
        errorView = layoutInflater.inflate(R.layout.error_page, null).apply {
            visibility = View.GONE
            findViewById<View>(R.id.btn_retry)?.setOnClickListener {
                visibility = View.GONE
                webView.visibility = View.VISIBLE
                progressBar.visibility = View.VISIBLE
                
                // If it failed initially before loading any URL, loadUrl again, otherwise reload
                val currentUrl = webView.url
                if (currentUrl.isNullOrEmpty() || currentUrl == "about:blank") {
                    webView.loadUrl(getString(R.string.production_url))
                } else {
                    webView.reload()
                }
            }
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

        setContentView(rootLayout)

        // Configure WebView
        configureWebView()

        // Pull-to-refresh handler
        swipeRefresh.setOnRefreshListener {
            webView.reload()
        }

        // Load the production URL
        val productionUrl = getString(R.string.production_url)
        webView.loadUrl(productionUrl)
    }

    @SuppressLint("SetJavaScriptEnabled")
    private fun configureWebView() {
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
            mixedContentMode = WebSettings.MIXED_CONTENT_NEVER_ALLOW
            cacheMode = WebSettings.LOAD_DEFAULT
            mediaPlaybackRequiresUserGesture = false

            // Enable geolocation for Google Maps
            setGeolocationEnabled(true)

            // Custom User-Agent to identify the app
            val defaultUA = userAgentString
            userAgentString = "$defaultUA UnlockRentalsApp/1.1"
        }

        // WebViewClient — handles navigation and page lifecycle
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
                    UrlNavigationChecker.NavigationTarget.MAPS -> {
                        false
                    }
                    UrlNavigationChecker.NavigationTarget.UPI -> {
                        try { startActivity(Intent(Intent.ACTION_VIEW, Uri.parse(url))) }
                        catch (e: Exception) { Toast.makeText(this@MainActivity, "No UPI app found", Toast.LENGTH_SHORT).show() }
                        true
                    }
                    UrlNavigationChecker.NavigationTarget.INTERNAL -> {
                        false
                    }
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
                progressBar.progress = 0
            }

            override fun onPageFinished(view: WebView?, url: String?) {
                super.onPageFinished(view, url)
                swipeRefresh.isRefreshing = false
                progressBar.visibility = View.GONE

                // Smoothly reveal WebView if it was hidden
                if (webView.visibility != View.VISIBLE) {
                    val fadeIn = AlphaAnimation(0f, 1f).apply { duration = 300 }
                    webView.startAnimation(fadeIn)
                    webView.visibility = View.VISIBLE
                }

                // Inject CSS to hide web-only elements in the app context
                val hideScript = """
                    (function() {
                        var style = document.createElement('style');
                        style.innerHTML = '.pwa-install-prompt, .feedback-modal-trigger, .app-download-section, .app-dl-section { display: none !important; }';
                        document.head.appendChild(style);
                    })();
                """.trimIndent()
                webView.evaluateJavascript(hideScript, null)
            }

            override fun onReceivedError(view: WebView?, request: WebResourceRequest?, error: WebResourceError?) {
                super.onReceivedError(view, request, error)
                if (request?.isForMainFrame == true) {
                    swipeRefresh.isRefreshing = false
                    progressBar.visibility = View.GONE
                    showErrorPage()
                    
                    val errorMessage = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
                        error?.description?.toString() ?: "Unknown Error"
                    } else {
                        "WebResourceError"
                    }
                    Toast.makeText(this@MainActivity, "Error: $errorMessage", Toast.LENGTH_LONG).show()
                }
            }

            override fun onReceivedHttpError(view: WebView?, request: WebResourceRequest?, errorResponse: WebResourceResponse?) {
                super.onReceivedHttpError(view, request, errorResponse)
                // Handle HTTP errors for main frame (e.g. 500, 503)
                if (request?.isForMainFrame == true && (errorResponse?.statusCode ?: 200) >= 400) {
                    swipeRefresh.isRefreshing = false
                    progressBar.visibility = View.GONE
                    showErrorPage()
                    Toast.makeText(this@MainActivity, "HTTP Error: ${errorResponse?.statusCode}", Toast.LENGTH_LONG).show()
                }
            }
            
            override fun onReceivedSslError(view: WebView?, handler: android.webkit.SslErrorHandler?, error: android.net.http.SslError?) {
                // If there's an SSL certificate error on the live server, the WebView blocks it.
                // For debugging, let's proceed anyway, but show a toast.
                Toast.makeText(this@MainActivity, "SSL Error ignored. Fix cert on server.", Toast.LENGTH_LONG).show()
                handler?.proceed() // Temporarily bypass SSL errors to see if the site loads
            }
        }

        // WebChromeClient — handles file uploads and progress
        webView.webChromeClient = object : WebChromeClient() {
            override fun onProgressChanged(view: WebView?, newProgress: Int) {
                super.onProgressChanged(view, newProgress)
                progressBar.progress = newProgress
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

            // Enable geolocation
            override fun onGeolocationPermissionsShowPrompt(origin: String?, callback: GeolocationPermissions.Callback?) {
                callback?.invoke(origin, true, false)
            }
        }

        // Download listener — handles file downloads (APKs, PDFs, etc.)
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

    @Suppress("DEPRECATION")
    @Deprecated("Deprecated in Java")
    override fun onBackPressed() {
        if (webView.canGoBack()) {
            webView.goBack()
        } else {
            super.onBackPressed()
        }
    }

    override fun onResume() {
        super.onResume()
        webView.onResume()
    }

    override fun onPause() {
        super.onPause()
        webView.onPause()
    }
}
