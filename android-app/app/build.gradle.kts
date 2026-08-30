plugins {
    alias(libs.plugins.android.application)
}

android {
    namespace = "com.example.unlockrentals"
    compileSdk = 36

    defaultConfig {
        applicationId = "com.unlockrentals.app"
        minSdk = 24
        targetSdk = 35
        versionCode = 6
        versionName = "1.4.1"
    }

    signingConfigs {
        create("release") {
            storeFile = file("${rootDir}/unlockrentals-release.jks")
            storePassword = "UnlockRentals@2026"
            keyAlias = "unlockrentals"
            keyPassword = "UnlockRentals@2026"
        }
    }

    buildTypes {
        debug {
            isDebuggable = true
            signingConfig = signingConfigs.getByName("debug")
        }
        release {
            isMinifyEnabled = false
            signingConfig = signingConfigs.getByName("release")
            proguardFiles(getDefaultProguardFile("proguard-android-optimize.txt"), "proguard-rules.pro")
        }
    }

    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_17
        targetCompatibility = JavaVersion.VERSION_17
    }

    buildFeatures {
        viewBinding = true
    }

    packaging {
        resources {
            excludes += "/META-INF/{AL2.0,LGPL2.1}"
        }
    }
}

kotlin {
    jvmToolchain(17)
}

dependencies {
    // Core Android
    implementation(libs.androidx.core.ktx)
    implementation(libs.androidx.lifecycle.runtime.ktx)

    // AppCompat for AppCompatActivity
    implementation("androidx.appcompat:appcompat:1.7.0")

    // Splash Screen API (backward compatible)
    implementation("androidx.core:core-splashscreen:1.0.1")

    // SwipeRefreshLayout for pull-to-refresh
    implementation("androidx.swiperefreshlayout:swiperefreshlayout:1.1.0")

    // WebKit (enhanced WebView APIs)
    implementation("androidx.webkit:webkit:1.12.1")

    // Testing
    testImplementation(libs.junit)
}
