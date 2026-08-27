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
            url.startsWith("upi://") -> NavigationTarget.UPI
            prodHost.isNotEmpty() && (host == prodHost || host.contains(prodHost)) -> NavigationTarget.INTERNAL
            host.contains("unlockrentals") -> NavigationTarget.INTERNAL
            host.contains("10.0.2.2") || host.contains("localhost") || host.contains("127.0.0.1") -> NavigationTarget.INTERNAL
            else -> NavigationTarget.EXTERNAL
        }
    }
}
