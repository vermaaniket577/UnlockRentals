package com.example.unlockrentals

import org.junit.Assert.assertEquals
import org.junit.Assert.assertTrue
import org.junit.Test

class AppUnitTest {

    private val productionUrl = "https://unlockrentals.in"
    private val checker = UrlNavigationChecker(productionUrl)

    @Test
    fun testProductionUrl() {
        assertTrue(productionUrl.startsWith("https://"))
        assertTrue(productionUrl.contains("unlockrentals.in"))
    }

    @Test
    fun testDialUrl() {
        assertEquals(
            UrlNavigationChecker.NavigationTarget.DIAL,
            checker.determineTarget("tel:1234567890")
        )
    }

    @Test
    fun testMailtoUrl() {
        assertEquals(
            UrlNavigationChecker.NavigationTarget.SENDTO,
            checker.determineTarget("mailto:support@unlockrentals.in")
        )
    }

    @Test
    fun testWhatsappUrl() {
        assertEquals(
            UrlNavigationChecker.NavigationTarget.WHATSAPP,
            checker.determineTarget("whatsapp://send?phone=919999999999")
        )
        assertEquals(
            UrlNavigationChecker.NavigationTarget.WHATSAPP,
            checker.determineTarget("https://wa.me/919999999999")
        )
        assertEquals(
            UrlNavigationChecker.NavigationTarget.WHATSAPP,
            checker.determineTarget("https://api.whatsapp.com/send?phone=919999999999")
        )
    }

    @Test
    fun testAppStoreUrl() {
        assertEquals(
            UrlNavigationChecker.NavigationTarget.APP_STORE,
            checker.determineTarget("market://details?id=com.unlockrentals.app")
        )
        assertEquals(
            UrlNavigationChecker.NavigationTarget.APP_STORE,
            checker.determineTarget("https://play.google.com/store/apps/details?id=com.unlockrentals.app")
        )
        assertEquals(
            UrlNavigationChecker.NavigationTarget.APP_STORE,
            checker.determineTarget("https://apps.apple.com/us/app/unlockrentals/id123456789")
        )
    }

    @Test
    fun testMapsUrl() {
        assertEquals(
            UrlNavigationChecker.NavigationTarget.MAPS,
            checker.determineTarget("https://www.google.com/maps/place/Mumbai")
        )
        assertEquals(
            UrlNavigationChecker.NavigationTarget.MAPS,
            checker.determineTarget("https://maps.google.com/?q=Mumbai")
        )
    }

    @Test
    fun testUpiUrl() {
        assertEquals(
            UrlNavigationChecker.NavigationTarget.UPI,
            checker.determineTarget("upi://pay?pa=merchant@upi&pn=UnlockRentals&am=100")
        )
    }

    @Test
    fun testInternalUrl() {
        assertEquals(
            UrlNavigationChecker.NavigationTarget.INTERNAL,
            checker.determineTarget("https://unlockrentals.in/properties")
        )
        assertEquals(
            UrlNavigationChecker.NavigationTarget.INTERNAL,
            checker.determineTarget("https://www.unlockrentals.in/about")
        )
        assertEquals(
            UrlNavigationChecker.NavigationTarget.INTERNAL,
            checker.determineTarget("http://localhost:8000/api")
        )
        assertEquals(
            UrlNavigationChecker.NavigationTarget.INTERNAL,
            checker.determineTarget("http://127.0.0.1/test")
        )
        assertEquals(
            UrlNavigationChecker.NavigationTarget.INTERNAL,
            checker.determineTarget("http://10.0.2.2:8000/auth")
        )
    }

    @Test
    fun testExternalUrl() {
        assertEquals(
            UrlNavigationChecker.NavigationTarget.EXTERNAL,
            checker.determineTarget("https://github.com/google/antigravity")
        )
        assertEquals(
            UrlNavigationChecker.NavigationTarget.EXTERNAL,
            checker.determineTarget("https://example.com/some/external/page")
        )
    }
}
