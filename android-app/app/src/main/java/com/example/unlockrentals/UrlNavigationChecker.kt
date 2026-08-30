package com.example.unlockrentals

import java.net.URI

class UrlNavigationChecker(private val productionUrl: String) {

    enum class NavigationTarget {
        DIAL,
        SENDTO,
        WHATSAPP,
        APP_STORE,
        MAPS,
        UPI,
        INTENT,
        INTERNAL,
        EXTERNAL
    }

    fun determineTarget(url: String): NavigationTarget {
        val uri = try {
            URI(url)
        } catch (e: Exception) {
            null
        }
        val host = uri?.host ?: ""
        val prodHost = try {
            URI(productionUrl).host ?: ""
        } catch (e: Exception) {
            ""
        }

        return when {
            url.startsWith("tel:") -> NavigationTarget.DIAL
            url.startsWith("mailto:") -> NavigationTarget.SENDTO
            url.startsWith("whatsapp://") || host.contains("wa.me") || host.contains("api.whatsapp.com") -> NavigationTarget.WHATSAPP
            url.startsWith("market://") || host.contains("play.google.com") || host.contains("apps.apple.com") -> NavigationTarget.APP_STORE
            (host.contains("google.com") && url.contains("maps")) || host.contains("maps.google") -> NavigationTarget.MAPS
            url.startsWith("upi://") || url.startsWith("tez://") || url.startsWith("phonepe://") ||
            url.startsWith("paytmmp://") || url.startsWith("bhim://") || url.startsWith("credpay://") -> NavigationTarget.UPI
            url.startsWith("intent:") || url.startsWith("intent://") -> NavigationTarget.INTENT
            prodHost.isNotEmpty() && (host == prodHost || host.contains(prodHost)) -> NavigationTarget.INTERNAL
            host.contains("unlockrentals") -> NavigationTarget.INTERNAL
            host.contains("10.0.2.2") || host.contains("localhost") || host.contains("127.0.0.1") -> NavigationTarget.INTERNAL
            // Social Auth Providers - Keep directly inside app WebView
            host.contains("accounts.google.com") || host.contains("facebook.com") || host.contains("m.facebook.com") || host.contains("appleid.apple.com") -> NavigationTarget.INTERNAL
            else -> NavigationTarget.EXTERNAL
        }
    }
}

