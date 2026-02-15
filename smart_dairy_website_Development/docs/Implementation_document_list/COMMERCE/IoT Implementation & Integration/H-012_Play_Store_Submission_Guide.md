# SMART DAIRY LTD.
## PLAY STORE SUBMISSION GUIDE (Android)
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | H-012 |
| **Version** | 1.0 |
| **Date** | August 29, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Product Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Prerequisites](#2-prerequisites)
3. [Google Play Console Setup](#3-google-play-console-setup)
4. [App Preparation](#4-app-preparation)
5. [Store Listing Configuration](#5-store-listing-configuration)
6. [Build & Upload](#6-build--upload)
7. [Content Rating](#7-content-rating)
8. [Pricing & Distribution](#8-pricing--distribution)
9. [Submission Checklist](#9-submission-checklist)
10. [Post-Release Management](#10-post-release-management)
11. [Troubleshooting](#11-troubleshooting)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides a comprehensive guide for submitting Smart Dairy mobile applications to the Google Play Store. It covers all necessary steps from Google Play Console setup to app publication, ensuring compliance with Google's policies and best practices for Android distribution.

### 1.2 Apps Covered

| App Name | Package Name | Target Users | Min SDK | Target SDK |
|----------|--------------|--------------|---------|------------|
| **Smart Dairy Farm** | com.smartdairy.farm | Farm Workers, Supervisors | API 24 | API 34 |
| **Smart Dairy Customer** | com.smartdairy.customer | B2C Consumers | API 24 | API 34 |
| **Smart Dairy B2B** | com.smartdairy.b2b | Wholesale Partners | API 24 | API 34 |

### 1.3 Submission Timeline

| Phase | Duration | Activities |
|-------|----------|------------|
| **Preparation** | 2-3 days | Testing, asset preparation |
| **Submission** | 1 day | Upload, configure listing |
| **Review** | 1-3 days | Google Play review |
| **Release** | Immediate | App goes live |

---

## 2. PREREQUISITES

### 2.1 Required Accounts

| Account | Purpose | Cost | Setup Time |
|---------|---------|------|------------|
| **Google Play Developer** | App distribution | $25 one-time | Immediate |
| **Google Cloud Platform** | Firebase, APIs | Free tier | 1 hour |
| **Firebase Console** | Push notifications, analytics | Free tier | 1 hour |

### 2.2 Development Environment

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      REQUIRED DEVELOPMENT SETUP                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Hardware:                                                                   │
│  ├── Windows, macOS, or Linux development machine                            │
│  ├── 8GB+ RAM (16GB recommended)                                             │
│  └── 50GB+ free storage                                                      │
│                                                                              │
│  Software:                                                                   │
│  ├── Android Studio Hedgehog (2023.1.1) or later                             │
│  ├── Android SDK API 24-34                                                   │
│  ├── Flutter 3.19+                                                           │
│  ├── Java JDK 17 or OpenJDK 17                                               │
│  └── Google Play Console access                                              │
│                                                                              │
│  Testing Devices:                                                            │
│  ├── Physical Android device (API 24+)                                       │
│  └── Android Emulator for various screen sizes                               │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.3 Asset Requirements

| Asset | Specifications | Quantity |
|-------|---------------|----------|
| **App Icon** | 512×512px PNG or JPEG | 1 |
| **Feature Graphic** | 1024×500px PNG or JPEG | 1 |
| **Phone Screenshots** | 16:9 or 9:16, min 320px, max 3840px | 2-8 |
| **Tablet Screenshots** | 16:9 or 9:16, min 320px, max 3840px | 0-8 (optional) |
| **Promo Video** | YouTube URL, 30 sec - 2 min | 1 (optional) |
| **Privacy Policy URL** | Publicly accessible | 1 |

---

## 3. GOOGLE PLAY CONSOLE SETUP

### 3.1 Developer Account Registration

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    GOOGLE PLAY DEVELOPER REGISTRATION                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Step 1: Visit play.google.com/console                                       │
│          └── Click "Create account"                                          │
│                                                                              │
│  Step 2: Sign in with Google Account                                         │
│          └── Use company Google account: smartdairy.dev@gmail.com            │
│                                                                              │
│  Step 3: Accept Developer Agreement                                          │
│          └── Read and accept Google Play Developer Distribution Agreement    │
│                                                                              │
│  Step 4: Pay Registration Fee                                                │
│          └── $25 USD one-time fee via Google Pay                             │
│                                                                              │
│  Step 5: Complete Account Details                                            │
│          ├── Developer name: Smart Dairy Ltd.                                │
│          ├── Email: mobile@smartdairybd.com                                  │
│          ├── Website: https://smartdairybd.com                               │
│          └── Phone: +880 [company phone]                                     │
│                                                                              │
│  Step 6: Verification                                                        │
│          └── Google may verify identity (1-2 days)                           │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Organization Setup

#### Add Team Members

1. **Sign in to Play Console**: https://play.google.com/console
2. **Navigate to**: Users and permissions
3. **Invite users** with appropriate roles:

| Role | Permissions | Email |
|------|-------------|-------|
| **Account Owner** | Full access | md@smartdairybd.com |
| **Admin** | Full access except critical operations | it-director@smartdairybd.com |
| **Release Manager** | Manage releases, store listing | mobile-lead@smartdairybd.com |
| **Developer** | Upload builds, view data | mobile-dev@smartdairybd.com |
| **Marketer** | Store listing, experiments | marketing@smartdairybd.com |
| **Customer Support** | Reply to reviews | support@smartdairybd.com |

### 3.3 App Creation

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CREATE NEW APP IN PLAY CONSOLE                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Step 1: Click "Create app"                                                  │
│                                                                              │
│  Step 2: Select default language                                             │
│          └── English (en-US)                                                 │
│                                                                              │
│  Step 3: Enter app name                                                      │
│          └── "Smart Dairy Farm"                                              │
│                                                                              │
│  Step 4: Select app type                                                     │
│          └── App (not Game)                                                  │
│                                                                              │
│  Step 5: Specify if it's a paid app                                          │
│          └── Free                                                            │
│                                                                              │
│  Step 6: Accept declarations                                                 │
│          └── Content guidelines                                              │
│          └── US export laws                                                  │
│                                                                              │
│  Step 7: Click "Create app"                                                  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. APP PREPARATION

### 4.1 Flutter Build Configuration

```yaml
# pubspec.yaml
name: smart_dairy_farm
description: Smart Dairy Farm Management Application
version: 1.0.0+100  # Format: version_name+version_code

environment:
  sdk: '>=3.3.0 <4.0.0'

dependencies:
  flutter:
    sdk: flutter
  # Production dependencies
  firebase_core: ^2.24.0
  firebase_messaging: ^14.7.0
  flutter_local_notifications: ^16.3.0
  connectivity_plus: ^5.0.0
  # ... other dependencies

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.0

flutter:
  uses-material-design: true
  assets:
    - assets/images/
    - assets/icons/
```

### 4.2 Android Project Configuration

#### build.gradle (Project Level)

```gradle
// android/build.gradle
allprojects {
    repositories {
        google()
        mavenCentral()
    }
}

buildscript {
    ext.kotlin_version = '1.9.22'
    repositories {
        google()
        mavenCentral()
    }
    dependencies {
        classpath 'com.android.tools.build:gradle:8.2.0'
        classpath "org.jetbrains.kotlin:kotlin-gradle-plugin:$kotlin_version"
        classpath 'com.google.gms:google-services:4.4.0'
        classpath 'com.google.firebase:firebase-crashlytics-gradle:2.9.9'
    }
}
```

#### build.gradle (App Level)

```gradle
// android/app/build.gradle
plugins {
    id "com.android.application"
    id "kotlin-android"
    id "dev.flutter.flutter-gradle-plugin"
    id "com.google.gms.google-services"
    id "com.google.firebase.crashlytics"
}

def localProperties = new Properties()
def localPropertiesFile = rootProject.file('local.properties')
if (localPropertiesFile.exists()) {
    localPropertiesFile.withReader('UTF-8') { reader ->
        localProperties.load(reader)
    }
}

def flutterVersionCode = localProperties.getProperty('flutter.versionCode')
if (flutterVersionCode == null) {
    flutterVersionCode = '100'
}

def flutterVersionName = localProperties.getProperty('flutter.versionName')
if (flutterVersionName == null) {
    flutterVersionName = '1.0.0'
}

android {
    namespace "com.smartdairy.farm"
    compileSdkVersion 34
    ndkVersion flutter.ndkVersion

    compileOptions {
        sourceCompatibility JavaVersion.VERSION_17
        targetCompatibility JavaVersion.VERSION_17
    }

    kotlinOptions {
        jvmTarget = '17'
    }

    sourceSets {
        main.java.srcDirs += 'src/main/kotlin'
    }

    defaultConfig {
        applicationId "com.smartdairy.farm"
        minSdkVersion 24
        targetSdkVersion 34
        versionCode flutterVersionCode.toInteger()
        versionName flutterVersionName
        multiDexEnabled true
    }

    signingConfigs {
        release {
            keyAlias keystoreProperties['keyAlias']
            keyPassword keystoreProperties['keyPassword']
            storeFile keystoreProperties['storeFile'] ? file(keystoreProperties['storeFile']) : null
            storePassword keystoreProperties['storePassword']
        }
    }

    buildTypes {
        release {
            signingConfig signingConfigs.release
            minifyEnabled true
            shrinkResources true
            proguardFiles getDefaultProguardFile('proguard-android-optimize.txt'), 'proguard-rules.pro'
            
            // Firebase Crashlytics
            firebaseCrashlytics {
                mappingFileUploadEnabled true
            }
        }
        debug {
            signingConfig signingConfigs.debug
        }
    }
}

flutter {
    source '../..'
}

dependencies {
    implementation "org.jetbrains.kotlin:kotlin-stdlib-jdk8:$kotlin_version"
    implementation 'androidx.multidex:multidex:2.0.1'
    implementation platform('com.google.firebase:firebase-bom:32.7.0')
    implementation 'com.google.firebase:firebase-analytics'
    implementation 'com.google.firebase:firebase-messaging'
    implementation 'com.google.firebase:firebase-crashlytics'
}
```

#### Signing Configuration

```gradle
// android/key.properties (DO NOT COMMIT - add to .gitignore)
storePassword=[YOUR_STORE_PASSWORD]
keyPassword=[YOUR_KEY_PASSWORD]
keyAlias=smartdairy
storeFile=smartdairy-keystore.jks
```

### 4.3 Android Manifest

```xml
<!-- android/app/src/main/AndroidManifest.xml -->
<manifest xmlns:android="http://schemas.android.com/apk/res/android"
    package="com.smartdairy.farm">

    <!-- Internet & Network -->
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
    
    <!-- Location -->
    <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
    <uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION" />
    <uses-permission android:name="android.permission.ACCESS_BACKGROUND_LOCATION" />
    
    <!-- Camera & Storage -->
    <uses-permission android:name="android.permission.CAMERA" />
    <uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" 
        android:maxSdkVersion="32" />
    <uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE"
        android:maxSdkVersion="32" />
    <uses-permission android:name="android.permission.READ_MEDIA_IMAGES" />
    
    <!-- Bluetooth (for RFID/Wearables) -->
    <uses-permission android:name="android.permission.BLUETOOTH" />
    <uses-permission android:name="android.permission.BLUETOOTH_ADMIN" />
    <uses-permission android:name="android.permission.BLUETOOTH_SCAN" />
    <uses-permission android:name="android.permission.BLUETOOTH_CONNECT" />
    
    <!-- Notifications -->
    <uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED" />
    <uses-permission android:name="android.permission.VIBRATE" />
    <uses-permission android:name="android.permission.POST_NOTIFICATIONS" />
    <uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
    
    <!-- Hardware Requirements -->
    <uses-feature android:name="android.hardware.camera" android:required="false" />
    <uses-feature android:name="android.hardware.location.gps" android:required="false" />
    <uses-feature android:name="android.hardware.bluetooth_le" android:required="false" />

    <application
        android:label="Smart Dairy Farm"
        android:name="${applicationName}"
        android:icon="@mipmap/ic_launcher"
        android:roundIcon="@mipmap/ic_launcher_round"
        android:allowBackup="false"
        android:fullBackupContent="false"
        android:usesCleartextTraffic="false">
        
        <activity
            android:name=".MainActivity"
            android:exported="true"
            android:launchMode="singleTop"
            android:theme="@style/LaunchTheme"
            android:configChanges="orientation|keyboardHidden|keyboard|screenSize|smallestScreenSize|locale|layoutDirection|fontScale|screenLayout|density|uiMode"
            android:hardwareAccelerated="true"
            android:windowSoftInputMode="adjustResize">
            
            <meta-data
                android:name="io.flutter.embedding.android.NormalTheme"
                android:resource="@style/NormalTheme" />
            
            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>
            
            <!-- Deep linking -->
            <intent-filter>
                <action android:name="android.intent.action.VIEW" />
                <category android:name="android.intent.category.DEFAULT" />
                <category android:name="android.intent.category.BROWSABLE" />
                <data android:scheme="smartdairy" android:host="farm" />
            </intent-filter>
        </activity>
        
        <!-- Firebase Messaging Service -->
        <service
            android:name="com.google.firebase.messaging.FirebaseMessagingService"
            android:exported="false">
            <intent-filter>
                <action android:name="com.google.firebase.MESSAGING_EVENT" />
            </intent-filter>
        </service>
        
        <!-- Notification Channel -->
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_channel_id"
            android:value="smart_dairy_alerts" />
        
        <!-- Flutter Embedding -->
        <meta-data
            android:name="flutterEmbedding"
            android:value="2" />
    </application>
</manifest>
```

### 4.4 App Icons

```bash
#!/bin/bash
# generate_android_icons.sh
# Generate Android app icons from source image

SOURCE_ICON="assets/app_icon_512.png"
ANDROID_DIR="android/app/src/main/res"

echo "=== Generating Android App Icons ==="

# Create directories
mkdir -p "$ANDROID_DIR"/mipmap-{mdpi,hdpi,xhdpi,xxhdpi,xxxhdpi}

# Define sizes for each density
# mdpi: 48x48, hdpi: 72x72, xhdpi: 96x96, xxhdpi: 144x144, xxxhdpi: 192x192
declare -a DENSITIES=(
    "mdpi 48"
    "hdpi 72"
    "xhdpi 96"
    "xxhdpi 144"
    "xxxhdpi 192"
)

# Generate icons
for density in "${DENSITIES[@]}"; do
    name=$(echo $density | cut -d' ' -f1)
    size=$(echo $density | cut -d' ' -f2)
    
    # Launcher icon
    convert "$SOURCE_ICON" -resize ${size}x${size} \
        "$ANDROID_DIR/mipmap-$name/ic_launcher.png"
    
    # Round icon (for API 25+)
    convert "$SOURCE_ICON" -resize ${size}x${size} \
        -alpha set -background none -vignette 0x0 \
        "$ANDROID_DIR/mipmap-$name/ic_launcher_round.png"
    
    echo "Generated $name icons (${size}x${size})"
done

# Generate adaptive icon foreground/background (API 26+)
# Foreground
convert "$SOURCE_ICON" -resize 432x432 \
    android/app/src/main/res/drawable/ic_launcher_foreground.xml

# Background color
# Create values/colors.xml with: <color name="ic_launcher_background">#4CAF50</color>

echo "Android icons generated successfully!"
echo ""
echo "Don't forget to:"
echo "1. Add background color in res/values/colors.xml"
echo "2. Create mipmap-anydpi-v26/ic_launcher.xml for adaptive icons"
```

#### Adaptive Icon Configuration

```xml
<!-- android/app/src/main/res/mipmap-anydpi-v26/ic_launcher.xml -->
<?xml version="1.0" encoding="utf-8"?>
<adaptive-icon xmlns:android="http://schemas.android.com/apk/res/android">
    <background android:drawable="@color/ic_launcher_background" />
    <foreground android:drawable="@drawable/ic_launcher_foreground" />
</adaptive-icon>
```

```xml
<!-- android/app/src/main/res/values/colors.xml -->
<?xml version="1.0" encoding="utf-8"?>
<resources>
    <color name="ic_launcher_background">#4CAF50</color>
    <color name="primary_color">#4CAF50</color>
    <color name="accent_color">#FFC107</color>
</resources>
```

---

## 5. STORE LISTING CONFIGURATION

### 5.1 Main Store Listing

| Field | Value for Smart Dairy Farm |
|-------|---------------------------|
| **App Name** | Smart Dairy Farm |
| **Short Description** | Complete farm management & IoT solution |
| **Full Description** | See template below |
| **Category** | Business |
| **Tags** | Agriculture, Farm Management, Dairy |

#### Full Description Template

```
Smart Dairy Farm - Complete Farm Management Solution

Manage your dairy farm operations with ease using Smart Dairy Farm. Our comprehensive mobile app connects you to your farm's IoT sensors, providing real-time insights into milk production, animal health, and environmental conditions.

<b>KEY FEATURES:</b>
• Real-time milk production tracking per cow
• Individual animal health monitoring
• Environmental sensor dashboards (temperature, humidity, ammonia)
• Task management for farm workers
• Offline capability for remote areas
• Push notifications for critical alerts
• Integration with Smart Dairy ERP
• RFID scanning for animal identification

<b>BENEFITS:</b>
✓ Increase operational efficiency
✓ Improve animal health outcomes
✓ Reduce manual data entry by 70%
✓ Make data-driven decisions
✓ Ensure compliance with dairy regulations
✓ Track milk quality parameters

<b>WHO IS IT FOR:</b>
• Farm Managers
• Dairy Supervisors
• Veterinary Staff
• Farm Workers
• Livestock Owners

<b>OFFLINE-FIRST DESIGN:</b>
Smart Dairy Farm works even without internet connectivity. Record data offline and sync automatically when connection is restored. Perfect for farms in rural areas.

<b>SMART INTEGRATIONS:</b>
• Milk meter integration (DeLaval, Afimilk)
• RFID animal identification
• Wearable activity monitors
• Environmental sensors
• Cold storage monitoring

<b>SECURITY:</b>
Your data is secure with enterprise-grade encryption. All farm data is protected and backed up automatically.

<b>SUPPORT:</b>
Need help? Contact our support team:
Email: support@smartdairybd.com
Phone: +880 [support number]
Website: https://smartdairybd.com/support

<b>NOTE:</b>
This app requires a Smart Dairy Farm account. Contact us at info@smartdairybd.com to learn more about our farm management solutions.

<b>PRIVACY:</b>
We respect your privacy. Read our Privacy Policy: https://smartdairybd.com/privacy-policy
```

### 5.2 Graphic Assets

#### Feature Graphic (1024×500px)

Requirements:
- Format: PNG or JPEG
- Size: 1024×500px
- No transparency
- Safe zone: Keep important content in center 600×300px
- No pricing information
- No Google Play/Apple logos

#### Screenshot Guidelines

| Device Type | Size | Quantity | Priority |
|-------------|------|----------|----------|
| **Phone (16:9)** | 1920×1080 or 1080×1920 | 4-8 | Required |
| **Phone (19.5:9)** | 2340×1080 or 1080×2340 | 2-4 | Recommended |
| **Tablet (16:10)** | 2560×1600 or 1600×2560 | 0-8 | Optional |

#### Screenshot Content

| Screenshot | Caption |
|------------|---------|
| 1 | Dashboard with real-time farm metrics |
| 2 | Herd management - individual animal profiles |
| 3 | Milk production tracking and analytics |
| 4 | Environmental monitoring dashboard |
| 5 | Task management for farm workers |
| 6 | Offline mode and data sync |

---

## 6. BUILD & UPLOAD

### 6.1 Pre-Build Checklist

```bash
#!/bin/bash
# pre_build_checklist.sh

echo "=== Android Pre-Build Checklist ==="

# 1. Clean build
echo "✓ Cleaning previous builds..."
flutter clean

# 2. Get dependencies
echo "✓ Getting dependencies..."
flutter pub get

# 3. Run tests
echo "✓ Running tests..."
flutter test

# 4. Analyze code
echo "✓ Analyzing code..."
flutter analyze

# 5. Check code formatting
echo "✓ Checking code formatting..."
flutter format --set-exit-if-changed .

# 6. Build app bundle (recommended for Play Store)
echo "✓ Building Android App Bundle..."
flutter build appbundle --release

# Alternative: Build APK for testing
# flutter build apk --release

echo "=== Build complete ==="
echo "Output: build/app/outputs/bundle/release/app-release.aab"
```

### 6.2 Keystore Setup

```bash
#!/bin/bash
# setup_keystore.sh
# Run this ONCE to create the upload keystore

KEYSTORE_FILE="smartdairy-keystore.jks"
ALIAS="smartdairy"
VALIDITY=10000  # 27+ years

echo "=== Creating Android Upload Keystore ==="
echo "WARNING: Keep this keystore secure! You cannot update the app without it."
echo ""

# Generate keystore
keytool -genkey -v \
  -keystore $KEYSTORE_FILE \
  -alias $ALIAS \
  -keyalg RSA \
  -keysize 2048 \
  -validity $VALIDITY

echo ""
echo "Keystore created: $KEYSTORE_FILE"
echo ""
echo "Add the following to android/key.properties:"
echo "storePassword=[YOUR_PASSWORD]"
echo "keyPassword=[YOUR_PASSWORD]"
echo "keyAlias=$ALIAS"
echo "storeFile=../$KEYSTORE_FILE"
echo ""
echo "IMPORTANT: Add '$KEYSTORE_FILE' to .gitignore!"
```

### 6.3 Build App Bundle

```bash
#!/bin/bash
# build_release.sh

APP_NAME="Smart Dairy Farm"

echo "=== Building $APP_NAME Release ==="

# Ensure keystore exists
if [ ! -f "android/smartdairy-keystore.jks" ]; then
    echo "Error: Keystore not found. Run setup_keystore.sh first."
    exit 1
fi

# Build app bundle
flutter build appbundle --release

# Verify output
if [ -f "build/app/outputs/bundle/release/app-release.aab" ]; then
    echo "✓ Build successful!"
    echo "Output: build/app/outputs/bundle/release/app-release.aab"
    
    # Get file size
    ls -lh build/app/outputs/bundle/release/app-release.aab
else
    echo "✗ Build failed!"
    exit 1
fi
```

### 6.4 Upload to Play Console

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    UPLOAD APP BUNDLE TO PLAY CONSOLE                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Method 1: Direct Upload (Browser)                                           │
│  ─────────────────────────────────                                           │
│  1. Go to Play Console > Your App > Production                               │
│  2. Click "Create new release"                                               │
│  3. Drag and drop app-release.aab file                                       │
│  4. Wait for processing (may take a few minutes)                             │
│                                                                              │
│  Method 2: Using Google Play Developer API                                   │
│  ────────────────────────────────────────────                                │
│  # Requires service account setup                                            │
│  fastlane supply --aab build/app/outputs/bundle/release/app-release.aab      │
│                                                                              │
│  Method 3: Using Transporter (macOS)                                         │
│  ────────────────────────────────────                                        │
│  # Download Transporter from Mac App Store                                   │
│  # Drag .aab file and click Deliver                                          │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 7. CONTENT RATING

### 7.1 Content Rating Questionnaire

| Question | Answer |
|----------|--------|
| **Reference to alcohol** | No |
| **Reference to tobacco** | No |
| **Reference to drugs** | No |
| **Gambling** | No |
| **Profanity/crude humor** | No |
| **Violence** | No |
| **Fear content** | No |
| **Sexual content** | No |
| **User interactions** | Yes - User accounts, chat with support |
| **Location sharing** | Yes - Farm location tracking |
| **In-app purchases** | No (or Yes if subscription) |

### 7.2 Expected Rating

| Region | Expected Rating |
|--------|-----------------|
| **PEGI (Europe)** | PEGI 3 |
| **ESRB (North America)** | Everyone |
| **IARC (Global)** | 3+ |

---

## 8. PRICING & DISTRIBUTION

### 8.1 Pricing

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      PRICING CONFIGURATION                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  App Pricing:                                                                │
│  └── Free                                                                    │
│                                                                              │
│  In-app Products (if applicable):                                            │
│  └── Premium subscription: ৳99/month                                         │
│  └── Enterprise features: Contact sales                                      │
│                                                                              │
│  Currency:                                                                   │
│  └── Local pricing in BDT (Bangladesh Taka)                                  │
│  └── USD for international markets                                           │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 8.2 Distribution

| Setting | Value |
|---------|-------|
| **Countries** | Bangladesh (primary), Select international markets |
| **Managed Publishing** | Enabled (manual release control) |
| **App Signing** | Google Play App Signing (required) |
| **Wear OS** | No |
| **Android TV** | No |
| **Auto-updates** | Enabled |

---

## 9. SUBMISSION CHECKLIST

### 9.1 Pre-Launch Checklist

| Category | Item | Status |
|----------|------|--------|
| **Policy** | App complies with Developer Program Policies | ⬜ |
| **Policy** | Privacy policy URL is valid | ⬜ |
| **Policy** | No deceptive behavior or malicious code | ⬜ |
| **Content** | All text is proper English/Bengali | ⬜ |
| **Content** | No placeholder text or images | ⬜ |
| **Functionality** | App installs and launches | ⬜ |
| **Functionality** | All features work as described | ⬜ |
| **Performance** | No ANR (Application Not Responding) errors | ⬜ |
| **Performance** | No excessive battery drain | ⬜ |
| **Security** | Uses HTTPS for all network calls | ⬜ |
| **Security** | No hardcoded credentials | ⬜ |
| **SDK** | Target SDK 34 or higher | ⬜ |
| **Testing** | Tested on multiple devices | ⬜ |
| **Testing** | Crash-free user experience | ⬜ |

### 9.2 Store Listing Checklist

| Item | Requirement | Status |
|------|-------------|--------|
| **App Name** | 50 characters max | ⬜ |
| **Short Description** | 80 characters max | ⬜ |
| **Full Description** | 4000 characters max | ⬜ |
| **App Icon** | 512×512 PNG/JPEG | ⬜ |
| **Feature Graphic** | 1024×500 PNG/JPEG | ⬜ |
| **Screenshots** | 2-8 phone screenshots | ⬜ |
| **Contact Email** | Valid email address | ⬜ |
| **Privacy Policy** | Valid URL | ⬜ |

---

## 10. POST-RELEASE MANAGEMENT

### 10.1 Release Tracks

| Track | Purpose | Audience |
|-------|---------|----------|
| **Internal Testing** | Developer testing | Up to 100 testers |
| **Closed Testing** | QA and stakeholder testing | Up to 2,000 testers |
| **Open Testing** | Beta release | Public (anyone can join) |
| **Production** | Live release | All users |

### 10.2 Release Strategy

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    RECOMMENDED RELEASE STRATEGY                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Week 1: Internal Testing                                                    │
│  └── Release to internal team (10-20 users)                                  │
│  └── Fix critical bugs                                                       │
│                                                                              │
│  Week 2: Closed Beta                                                         │
│  └── Release to closed beta group (50-100 farm workers)                      │
│  └── Collect feedback                                                        │
│  └── Fix issues                                                              │
│                                                                              │
│  Week 3: Open Beta                                                           │
│  └── Release to open beta                                                    │
│  └── Monitor crash reports                                                   │
│  └── Performance tuning                                                      │
│                                                                              │
│  Week 4: Production Release                                                  │
│  └── Gradual rollout (20% → 50% → 100%)                                      │
│  └── Monitor metrics closely                                                 │
│  └── Respond to reviews                                                      │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 10.3 Monitoring & Analytics

| Metric | Target | Action if Below |
|--------|--------|-----------------|
| **Crash Rate** | < 1% | Investigate and fix |
| **ANR Rate** | < 0.5% | Optimize performance |
| **Rating** | > 4.0 | Address user feedback |
| **Daily Active Users** | Growth trend | Marketing push |
| **Retention (Day 7)** | > 30% | Improve onboarding |

---

## 11. TROUBLESHOOTING

### 11.1 Common Issues

| Issue | Solution |
|-------|----------|
| **AAB upload fails** | Check file size (< 150MB) and signing |
| **Signing error** | Verify keystore and key.properties |
| **Version code exists** | Increment version code in pubspec.yaml |
| **Content rating incomplete** | Complete all questionnaire questions |
| **Policy violation** | Review email and fix reported issues |
| **Bundle not serving** | Check Play Console for errors |

### 11.2 Build Error Solutions

```bash
# Clean and rebuild
flutter clean
flutter pub get
cd android && ./gradlew clean && cd ..
flutter build appbundle

# Fix Gradle issues
cd android
./gradlew wrapper --gradle-version 8.2
./gradlew build

# Fix dependency conflicts
flutter pub upgrade
flutter pub outdated
```

---

## 12. APPENDICES

### Appendix A: Fastlane Configuration

```ruby
# fastlane/Fastfile
default_platform(:android)

platform :android do
  desc "Run tests"
  lane :test do
    gradle(task: "test")
  end

  desc "Build release AAB"
  lane :build do
    gradle(
      task: "bundle",
      build_type: "Release",
      properties: {
        "android.injected.signing.store.file" => ENV["KEYSTORE_PATH"],
        "android.injected.signing.store.password" => ENV["KEYSTORE_PASSWORD"],
        "android.injected.signing.key.alias" => ENV["KEY_ALIAS"],
        "android.injected.signing.key.password" => ENV["KEY_PASSWORD"]
      }
    )
  end

  desc "Deploy to Play Store (Internal)"
  lane :deploy_internal do
    build
    upload_to_play_store(
      track: "internal",
      aab: "../build/app/outputs/bundle/release/app-release.aab"
    )
  end

  desc "Deploy to Play Store (Production)"
  lane :deploy_production do
    build
    upload_to_play_store(
      track: "production",
      aab: "../build/app/outputs/bundle/release/app-release.aab",
      rollout: "0.2"  # 20% rollout
    )
  end
end
```

### Appendix B: Version Code Strategy

```
Version Code Format: BBVVVV (6 digits)

BB = Build type (01 = internal, 02 = beta, 03 = production)
VVVV = Sequential version number

Examples:
010001 = Internal build 1
010002 = Internal build 2
020001 = Beta build 1
030001 = Production release 1.0.0
030002 = Production release 1.0.1

pubspec.yaml:
version: 1.0.0+030001
```

### Appendix C: Release Checklist

```
□ All tests passing
□ Code reviewed and approved
□ Version updated in pubspec.yaml
□ Changelog updated
□ Release notes prepared
□ AAB built successfully
□ AAB tested on physical device
□ Store listing reviewed
□ Screenshots up to date
□ Privacy policy valid
□ Content rating completed
□ Pricing set correctly
□ Distribution countries selected
□ Gradual rollout percentage set
□ Monitoring dashboards ready
□ Support team informed
```

---

**END OF PLAY STORE SUBMISSION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | August 29, 2026 | Mobile Lead | Initial version |
