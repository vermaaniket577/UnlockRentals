package com.example.unlockrentals

import android.os.Bundle
import android.webkit.WebView
import android.webkit.WebViewClient
import android.webkit.WebChromeClient
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.compose.BackHandler
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.systemBarsPadding
import androidx.compose.runtime.remember
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.getValue
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.viewinterop.AndroidView
import com.example.unlockrentals.theme.UnlockRentalsTheme

class MainActivity : ComponentActivity() {
  override fun onCreate(savedInstanceState: Bundle?) {
    super.onCreate(savedInstanceState)

    enableEdgeToEdge()
    setContent {
      UnlockRentalsTheme {
        var webView: WebView? by remember { mutableStateOf(null) }

        // Intercept back button to navigate back in webview history if possible
        BackHandler(enabled = webView?.canGoBack() == true) {
          webView?.goBack()
        }

        AndroidView(
          modifier = Modifier.fillMaxSize().systemBarsPadding(),
          factory = { context ->
            WebView(context).apply {
              webViewClient = object : WebViewClient() {
                override fun shouldOverrideUrlLoading(view: WebView?, url: String?): Boolean {
                  // Keep navigation inside the WebView itself for same host or similar
                  return false
                }
              }
              webChromeClient = WebChromeClient()
              settings.apply {
                javaScriptEnabled = true
                domStorageEnabled = true
                databaseEnabled = true
                loadsImagesAutomatically = true
                allowContentAccess = true
                allowFileAccess = true
              }
              // Point to local server mapping for android emulator.
              // Note: http://10.0.2.2:8085 is the standard way to reference localhost from the android emulator
              loadUrl("http://10.0.2.2:8085")
              webView = this
            }
          },
          update = {
            webView = it
          }
        )
      }
    }
  }
}
