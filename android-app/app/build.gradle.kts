plugins {
    alias(libs.plugins.android.application)
}

android {
    namespace = "com.example.unlockrentals"
    compileSdk = 36

    defaultConfig {
        applicationId = "com.example.unlockrentals"
        minSdk = 24
        targetSdk = 36
        versionCode = 1
        versionName = "1.0.0"
    }

    // Release signing config — fill in your keystore details
    signingConfigs {
        create("release") {
            // Uncomment and fill these in before building a release APK:
            // storeFile = file("keystore/unlockrentals-release.jks")
            // storePassword = "YOUR_KEYSTORE_PASSWORD"
            // keyAlias = "unlockrentals"
            // keyPassword = "YOUR_KEY_PASSWORD"
        }
    }

    buildTypes {
        debug {
            isDebuggable = true
        }
        release {
            isMinifyEnabled = false
            proguardFiles(getDefaultProguardFile("proguard-android-optimize.txt"), "proguard-rules.pro")
            // Uncomment after setting up signing config:
            // signingConfig = signingConfigs.getByName("release")
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
}
