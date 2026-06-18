package com.example.unlockrentals

import org.junit.Assert.assertTrue
import org.junit.Test

class AppUnitTest {
    @Test
    fun testProductionUrl() {
        val productionUrl = "https://unlockrentals.in"
        assertTrue(productionUrl.startsWith("https://"))
        assertTrue(productionUrl.contains("unlockrentals.in"))
    }
}
