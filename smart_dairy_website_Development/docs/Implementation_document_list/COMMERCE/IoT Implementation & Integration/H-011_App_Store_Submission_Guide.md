# SMART DAIRY LTD.
## APP STORE SUBMISSION GUIDE (iOS)
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | H-011 |
| **Version** | 1.0 |
| **Date** | August 29, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Product Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Prerequisites](#2-prerequisites)
3. [Apple Developer Account Setup](#3-apple-developer-account-setup)
4. [App Preparation](#4-app-preparation)
5. [App Store Connect Configuration](#5-app-store-connect-configuration)
6. [Build & Archive](#6-build--archive)
7. [Submission Checklist](#7-submission-checklist)
8. [Review Process](#8-review-process)
9. [Post-Release](#9-post-release)
10. [Troubleshooting](#10-troubleshooting)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides a comprehensive guide for submitting the Smart Dairy mobile applications to the Apple App Store. It covers all necessary steps from account setup to final release, ensuring compliance with Apple's guidelines and best practices.

### 1.2 Apps Covered

| App Name | Bundle ID | Target Users | Version |
|----------|-----------|--------------|---------|
| **Smart Dairy Farm** | com.smartdairy.farm | Farm Workers, Supervisors | 1.0.0 |
| **Smart Dairy Customer** | com.smartdairy.customer | B2C Consumers | 1.0.0 |
| **Smart Dairy B2B** | com.smartdairy.b2b | Wholesale Partners | 1.0.0 |

### 1.3 Submission Timeline

| Phase | Duration | Activities |
|-------|----------|------------|
| **Preparation** | 3-5 days | Asset preparation, testing, documentation |
| **Submission** | 1 day | Upload build, configure metadata |
| **Review** | 1-3 days | Apple's review process |
| **Release** | 1 day | App goes live on App Store |

---

## 2. PREREQUISITES

### 2.1 Required Accounts

| Account | Purpose | Cost | Setup Time |
|---------|---------|------|------------|
| **Apple Developer Program** | App distribution | $99/year | 1-2 days |
| **App Store Connect** | App management | Included | Same day |
| **Firebase Console** | Push notifications, analytics | Free tier | 1 hour |

### 2.2 Development Environment

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      REQUIRED DEVELOPMENT SETUP                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Hardware:                                                                   │
│  ├── Mac with Apple Silicon (M1/M2/M3) or Intel (2018+)                      │
│  ├── 16GB+ RAM recommended                                                   │
│  └── 100GB+ free storage                                                     │
│                                                                              │
│  Software:                                                                   │
│  ├── macOS Sonoma (14.0) or later                                            │
│  ├── Xcode 15.0 or later                                                     │
│  ├── Flutter 3.19+                                                           │
│  ├── CocoaPods 1.12+                                                         │
│  └── Transporter app (for upload)                                            │
│                                                                              │
│  Certificates:                                                               │
│  ├── Apple Development Certificate                                           │
│  ├── Apple Distribution Certificate                                          │
│  └── Provisioning Profiles (Development & App Store)                         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.3 Asset Requirements

| Asset | Specifications | Quantity |
|-------|---------------|----------|
| **App Icon** | 1024×1024px PNG, no transparency | 1 |
| **Screenshots** | 6.5" (1284×2778), 6.7" (1290×2796), 5.5" (1242×2208) | 3-5 per size |
| **App Preview** | 1080×1920, 15-30 seconds | Optional |
| **Privacy Policy URL** | Publicly accessible | 1 |
| **Support URL** | Publicly accessible | 1 |

---

## 3. APPLE DEVELOPER ACCOUNT SETUP

### 3.1 Enrollment Process

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    APPLE DEVELOPER ENROLLMENT FLOW                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Step 1: Visit developer.apple.com/programs                                  │
│          └── Click "Enroll"                                                  │
│                                                                              │
│  Step 2: Sign in with Apple ID (use company Apple ID)                        │
│          └── Enable two-factor authentication                                │
│                                                                              │
│  Step 3: Choose Entity Type                                                  │
│          └── Select "Company/Organization" (recommended for Smart Dairy)     │
│                                                                              │
│  Step 4: Provide Company Information                                         │
│          ├── Legal entity name: "Smart Dairy Ltd."                           │
│          ├── D-U-N-S Number: [Get from Dun & Bradstreet]                     │
│          ├── Website: https://smartdairybd.com                               │
│          └── Tax ID/Registration number                                      │
│                                                                              │
│  Step 5: Purchase Membership                                                 │
│          └── $99 USD annual fee                                              │
│                                                                              │
│  Step 6: Verification                                                        │
│          └── Apple verifies company details (1-2 business days)              │
│                                                                              │
│  Step 7: Account Activation                                                  │
│          └── Receive confirmation email                                      │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Team Setup

#### Add Team Members

1. **Sign in to App Store Connect**: https://appstoreconnect.apple.com
2. **Navigate to**: Users and Access
3. **Add team members** with appropriate roles:

| Role | Permissions | Assigned To |
|------|-------------|-------------|
| **Account Holder** | Full access | Managing Director |
| **Admin** | App management, users | IT Director |
| **App Manager** | App submissions | Mobile Lead |
| **Developer** | Build upload, testing | Mobile Developers |
| **Marketing** | Metadata, screenshots | Marketing Team |
| **Finance** | Reports, payments | Finance Manager |

### 3.3 Certificates & Provisioning

```bash
#!/bin/bash
# setup_certificates.sh
# Automated certificate setup for Smart Dairy iOS apps

echo "=== Smart Dairy iOS Certificate Setup ==="

# 1. Create Certificate Signing Request (CSR)
echo "Creating CSR..."
openssl req -new -newkey rsa:2048 -nodes \
  -keyout smartdairy_ios.key \
  -out smartdairy_ios.csr \
  -subj "/emailAddress=mobile@smartdairybd.com, CN=Smart Dairy Ltd, C=BD"

# 2. Instructions for Apple Developer Portal
cat << 'EOF'

Next steps in Apple Developer Portal:

1. Go to https://developer.apple.com/account/resources/certificates/list

2. Create certificates:
   a) Apple Development Certificate
      - Upload the CSR file generated above
      - Download the certificate
      - Install by double-clicking

   b) Apple Distribution Certificate (App Store)
      - Upload the same CSR
      - Download and install

3. Create Identifiers:
   a) com.smartdairy.farm (Smart Dairy Farm)
   b) com.smartdairy.customer (Smart Dairy Customer)
   c) com.smartdairy.b2b (Smart Dairy B2B)
   
   Enable capabilities:
   - Push Notifications
   - Background Modes (fetch, remote notifications)
   - App Groups (if sharing data between apps)

4. Create Provisioning Profiles:
   a) Development profiles for each app
   b) App Store distribution profiles for each app

5. Download and install all provisioning profiles

EOF

echo "CSR files generated. Follow the instructions above."
```

#### Xcode Certificate Setup

```bash
# Open Xcode and configure signing
open ios/Runner.xcworkspace

# Or use command line:
xcodebuild -project ios/Runner.xcodeproj \
  -scheme Runner \
  -configuration Release \
  -destination 'generic/platform=iOS' \
  clean build
```

---

## 4. APP PREPARATION

### 4.1 Flutter Build Configuration

```yaml
# pubspec.yaml
name: smart_dairy_farm
description: Smart Dairy Farm Management Application
version: 1.0.0+100  # Format: version_name+build_number

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
  
dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.0

flutter:
  uses-material-design: true
  assets:
    - assets/images/
    - assets/icons/
    - assets/l10n/
```

### 4.2 iOS Project Configuration

```ruby
# ios/Podfile
platform :ios, '14.0'

# Disable CocoaPods analytics
ENV['COCOAPODS_DISABLE_STATS'] = 'true'

project 'Runner', {
  'Debug' => :debug,
  'Profile' => :release,
  'Release' => :release,
}

def flutter_root
  generated_xcode_build_settings_path = File.expand_path(File.join('..', '..', 'Flutter', 'Generated.xcconfig'), __FILE__)
  unless File.exist?(generated_xcode_build_settings_path)
    raise "#{generated_xcode_build_settings_path} must exist"
  end

  File.foreach(generated_xcode_build_settings_path) do |line|
    matches = line.match(/FLUTTER_ROOT\=(.*)/)
    return matches[1].strip if matches
  end
end

require File.expand_path(File.join('packages', 'flutter_tools', 'bin', 'podhelper'), flutter_root)

flutter_ios_podfile_setup

target 'Runner' do
  use_frameworks!
  use_modular_headers!

  flutter_install_all_ios_pods File.dirname(File.realpath(__FILE__))
  
  # Firebase pods
  pod 'Firebase/Core'
  pod 'Firebase/Messaging'
  pod 'Firebase/Crashlytics'
end

post_install do |installer|
  installer.pods_project.targets.each do |target|
    flutter_additional_ios_build_settings(target)
    
    target.build_configurations.each do |config|
      # Minimum iOS version
      config.build_settings['IPHONEOS_DEPLOYMENT_TARGET'] = '14.0'
      
      # Disable bitcode (required for Flutter)
      config.build_settings['ENABLE_BITCODE'] = 'NO'
      
      # Swift version
      config.build_settings['SWIFT_VERSION'] = '5.0'
    end
  end
end
```

### 4.3 Info.plist Configuration

```xml
<!-- ios/Runner/Info.plist -->
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <!-- Basic App Configuration -->
    <key>CFBundleDisplayName</key>
    <string>Smart Dairy Farm</string>
    <key>CFBundleName</key>
    <string>Smart Dairy Farm</string>
    <key>CFBundleIdentifier</key>
    <string>com.smartdairy.farm</string>
    
    <!-- Version Information -->
    <key>CFBundleShortVersionString</key>
    <string>$(FLUTTER_BUILD_NAME)</string>
    <key>CFBundleVersion</key>
    <string>$(FLUTTER_BUILD_NUMBER)</string>
    
    <!-- Required Device Capabilities -->
    <key>UIRequiredDeviceCapabilities</key>
    <array>
        <string>armv7</string>
        <string>arm64</string>
    </array>
    
    <!-- Supported Interface Orientations -->
    <key>UISupportedInterfaceOrientations</key>
    <array>
        <string>UIInterfaceOrientationPortrait</string>
    </array>
    
    <!-- Background Modes -->
    <key>UIBackgroundModes</key>
    <array>
        <string>fetch</string>
        <string>remote-notification</string>
        <string>processing</string>
    </array>
    
    <!-- Privacy Descriptions (Required by Apple) -->
    <key>NSCameraUsageDescription</key>
    <string>Smart Dairy needs camera access to scan QR codes and RFID tags for animal identification.</string>
    
    <key>NSPhotoLibraryUsageDescription</key>
    <string>Smart Dairy needs photo library access to upload animal photos and documentation.</string>
    
    <key>NSLocationWhenInUseUsageDescription</key>
    <string>Smart Dairy uses your location to track farm visits and optimize delivery routes.</string>
    
    <key>NSLocationAlwaysUsageDescription</key>
    <string>Smart Dairy uses background location to track cattle location and geofence alerts.</string>
    
    <key>NSLocationAlwaysAndWhenInUseUsageDescription</key>
    <string>Smart Dairy uses your location for farm operations and delivery tracking.</string>
    
    <key>NSBluetoothAlwaysUsageDescription</key>
    <string>Smart Dairy uses Bluetooth to connect to RFID readers and wearable devices.</string>
    
    <key>NSMicrophoneUsageDescription</key>
    <string>Smart Dairy does not use the microphone. This description is required by some frameworks.</string>
    
    <!-- Firebase Configuration -->
    <key>FirebaseAppDelegateProxyEnabled</key>
    <false/>
    
    <!-- App Transport Security -->
    <key>NSAppTransportSecurity</key>
    <dict>
        <key>NSAllowsArbitraryLoads</key>
        <false/>
        <key>NSExceptionDomains</key>
        <dict>
            <key>smartdairybd.com</key>
            <dict>
                <key>NSExceptionMinimumTLSVersion</key>
                <string>TLSv1.2</string>
            </dict>
        </dict>
    </dict>
    
    <!-- UI Configuration -->
    <key>UIViewControllerBasedStatusBarAppearance</key>
    <false/>
    <key>UILaunchStoryboardName</key>
    <string>LaunchScreen</string>
    <key>UIMainStoryboardFile</key>
    <string>Main</string>
</dict>
</plist>
```

### 4.4 App Icons

```bash
#!/bin/bash
# generate_ios_icons.sh
# Generate iOS app icons from source image

SOURCE_ICON="assets/app_icon_1024.png"
OUTPUT_DIR="ios/Runner/Assets.xcassets/AppIcon.appiconset"

# Create output directory
mkdir -p "$OUTPUT_DIR"

# Define icon sizes for iOS
# Format: "name size"
declare -a ICONS=(
    "Icon-Notification@2x.png 40"
    "Icon-Notification@3x.png 60"
    "Icon-Settings@2x.png 58"
    "Icon-Settings@3x.png 87"
    "Icon-Spotlight@2x.png 80"
    "Icon-Spotlight@3x.png 120"
    "Icon-60@2x.png 120"
    "Icon-60@3x.png 180"
    "Icon-76.png 76"
    "Icon-76@2x.png 152"
    "Icon-83.5@2x.png 167"
    "Icon-1024.png 1024"
)

# Generate icons
for icon in "${ICONS[@]}"; do
    name=$(echo $icon | cut -d' ' -f1)
    size=$(echo $icon | cut -d' ' -f2)
    
    convert "$SOURCE_ICON" -resize ${size}x${size} "$OUTPUT_DIR/$name"
    echo "Generated: $name (${size}x${size})"
done

# Generate Contents.json
cat > "$OUTPUT_DIR/Contents.json" << 'EOF'
{
  "images" : [
    {
      "filename" : "Icon-Notification@2x.png",
      "idiom" : "iphone",
      "scale" : "2x",
      "size" : "20x20"
    },
    {
      "filename" : "Icon-Notification@3x.png",
      "idiom" : "iphone",
      "scale" : "3x",
      "size" : "20x20"
    },
    {
      "filename" : "Icon-Settings@2x.png",
      "idiom" : "iphone",
      "scale" : "2x",
      "size" : "29x29"
    },
    {
      "filename" : "Icon-Settings@3x.png",
      "idiom" : "iphone",
      "scale" : "3x",
      "size" : "29x29"
    },
    {
      "filename" : "Icon-Spotlight@2x.png",
      "idiom" : "iphone",
      "scale" : "2x",
      "size" : "40x40"
    },
    {
      "filename" : "Icon-Spotlight@3x.png",
      "idiom" : "iphone",
      "scale" : "3x",
      "size" : "40x40"
    },
    {
      "filename" : "Icon-60@2x.png",
      "idiom" : "iphone",
      "scale" : "2x",
      "size" : "60x60"
    },
    {
      "filename" : "Icon-60@3x.png",
      "idiom" : "iphone",
      "scale" : "3x",
      "size" : "60x60"
    },
    {
      "filename" : "Icon-76.png",
      "idiom" : "ipad",
      "scale" : "1x",
      "size" : "76x76"
    },
    {
      "filename" : "Icon-76@2x.png",
      "idiom" : "ipad",
      "scale" : "2x",
      "size" : "76x76"
    },
    {
      "filename" : "Icon-83.5@2x.png",
      "idiom" : "ipad",
      "scale" : "2x",
      "size" : "83.5x83.5"
    },
    {
      "filename" : "Icon-1024.png",
      "idiom" : "ios-marketing",
      "scale" : "1x",
      "size" : "1024x1024"
    }
  ],
  "info" : {
    "author" : "xcode",
    "version" : 1
  }
}
EOF

echo "iOS icons generated successfully!"
```

---

## 5. APP STORE CONNECT CONFIGURATION

### 5.1 App Record Creation

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    APP STORE CONNECT - APP SETUP                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  1. Log in to https://appstoreconnect.apple.com                             │
│                                                                              │
│  2. Click "Apps" then "+" to create new app                                 │
│                                                                              │
│  3. Fill in App Information:                                                 │
│     ├── Platforms: iOS                                                       │
│     ├── Name: Smart Dairy Farm                                               │
│     ├── Primary Language: English                                            │
│     ├── Bundle ID: com.smartdairy.farm (select from dropdown)                │
│     ├── SKU: SDS-FARM-001                                                    │
│     └── User Access: Full Access                                             │
│                                                                              │
│  4. Click "Create"                                                           │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 5.2 App Information

| Field | Value for Smart Dairy Farm |
|-------|---------------------------|
| **Name** | Smart Dairy Farm |
| **Subtitle** | Farm Management & IoT |
| **Category** | Business (Primary), Productivity (Secondary) |
| **Content Rights** | Does not contain third-party content |
| **Age Rating** | 4+ (No objectionable content) |
| **License Agreement** | Standard Apple EULA |

### 5.3 Pricing and Availability

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      PRICING & AVAILABILITY                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Price:                                                                      │
│  └── Free (All Smart Dairy apps are free to download)                        │
│                                                                              │
│  Availability:                                                               │
│  ├── All countries and territories (or specify Bangladesh only)              │
│  └── iPhone and iPad                                                         │
│                                                                              │
│  App Distribution:                                                           │
│  └── Download only (no pre-orders)                                           │
│                                                                              │
│  Business Models (if applicable):                                            │
│  └── Free with in-app purchases (for subscription features)                  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 5.4 App Privacy

#### Privacy Policy URL
- **URL**: `https://smartdairybd.com/privacy-policy`
- **Must include**: Data collection practices, user rights, contact information

#### Privacy Nutrition Labels

| Data Type | Used for | Linked to Identity |
|-----------|----------|-------------------|
| **Location** | App functionality, analytics | Yes |
| **Contact Info** | App functionality, account management | Yes |
| **User Content** | Photos for animal documentation | No |
| **Identifiers** | Device ID for push notifications | Yes |
| **Usage Data** | Product personalization, analytics | No |
| **Diagnostics** | Crash analytics, performance | No |

---

## 6. BUILD & ARCHIVE

### 6.1 Pre-Build Checklist

```bash
#!/bin/bash
# pre_build_checklist.sh

echo "=== iOS Pre-Build Checklist ==="

# 1. Clean build
echo "✓ Cleaning previous builds..."
flutter clean

# 2. Get dependencies
echo "✓ Getting dependencies..."
flutter pub get

# 3. Update pods
echo "✓ Updating CocoaPods..."
cd ios && pod install --repo-update && cd ..

# 4. Run tests
echo "✓ Running tests..."
flutter test

# 5. Analyze code
echo "✓ Analyzing code..."
flutter analyze

# 6. Build iOS app
echo "✓ Building iOS app..."
flutter build ios --release

echo "=== Pre-build checklist complete ==="
```

### 6.2 Build and Archive

```bash
#!/bin/bash
# build_and_archive.sh

APP_NAME="Smart Dairy Farm"
BUNDLE_ID="com.smartdairy.farm"
SCHEME="Runner"

# Build for iOS
flutter build ios --release

# Open Xcode project
open ios/Runner.xcworkspace

# Archive instructions:
echo ""
echo "=== Manual Steps in Xcode ==="
echo "1. Select Product > Scheme > Runner"
echo "2. Select Any iOS Device (arm64)"
echo "3. Select Product > Archive"
echo "4. Wait for archive to complete"
echo "5. In Organizer, select the archive"
echo "6. Click Distribute App"
echo "7. Select App Store Connect"
echo "8. Select Upload"
echo ""

# Alternative: Command line build
xcodebuild -workspace ios/Runner.xcworkspace \
  -scheme "$SCHEME" \
  -sdk iphoneos \
  -configuration Release \
  -destination 'generic/platform=iOS' \
  clean archive \
  -archivePath "build/ios/archives/$APP_NAME.xcarchive"

# Export IPA
xcodebuild -exportArchive \
  -archivePath "build/ios/archives/$APP_NAME.xcarchive" \
  -exportPath "build/ios/ipa" \
  -exportOptionsPlist ios/ExportOptions.plist
```

### 6.3 Export Options Plist

```xml
<!-- ios/ExportOptions.plist -->
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>method</key>
    <string>app-store</string>
    <key>provisioningProfiles</key>
    <dict>
        <key>com.smartdairy.farm</key>
        <string>Smart Dairy Farm App Store</string>
    </dict>
    <key>signingCertificate</key>
    <string>Apple Distribution</string>
    <key>signingStyle</key>
    <string>manual</string>
    <key>stripSwiftSymbols</key>
    <true/>
    <key>teamID</key>
    <string>YOUR_TEAM_ID</string>
    <key>uploadBitcode</key>
    <false/>
    <key>uploadSymbols</key>
    <true/>
</dict>
</plist>
```

---

## 7. SUBMISSION CHECKLIST

### 7.1 Pre-Submission Checklist

| Category | Item | Status |
|----------|------|--------|
| **Functionality** | App launches without crashing | ⬜ |
| **Functionality** | All features work as designed | ⬜ |
| **Functionality** | No placeholder content | ⬜ |
| **Performance** | App is responsive | ⬜ |
| **Performance** | Memory usage is acceptable | ⬜ |
| **UI/UX** | Follows iOS Human Interface Guidelines | ⬜ |
| **UI/UX** | Supports both light and dark mode | ⬜ |
| **Security** | No hardcoded credentials | ⬜ |
| **Security** | Uses HTTPS for network calls | ⬜ |
| **Privacy** | Privacy descriptions in Info.plist | ⬜ |
| **Privacy** | Privacy policy URL provided | ⬜ |
| **Legal** | No copyrighted material without permission | ⬜ |
| **Legal** | Terms of service provided | ⬜ |

### 7.2 App Store Information Checklist

| Field | Requirement | Status |
|-------|-------------|--------|
| **App Name** | 30 characters max, no trademarks | ⬜ |
| **Subtitle** | 30 characters max | ⬜ |
| **Promotional Text** | 170 characters max | ⬜ |
| **Description** | Minimum 1 line, recommended 2000+ chars | ⬜ |
| **Keywords** | 100 characters max, comma-separated | ⬜ |
| **Support URL** | Valid, publicly accessible | ⬜ |
| **Marketing URL** | Optional, valid URL | ⬜ |
| **Screenshots** | Required for 6.5" and 5.5" displays | ⬜ |
| **App Preview** | Optional, 15-30 seconds | ⬜ |
| **App Icon** | 1024×1024 PNG | ⬜ |

### 7.3 Screenshot Specifications

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    REQUIRED SCREENSHOT SIZES                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  iPhone 6.5" Display (Required)                                             │
│  ├── Size: 1284 x 2778 pixels (portrait)                                    │
│  ├── Size: 2778 x 1284 pixels (landscape)                                   │
│  └── Quantity: 3-5 screenshots                                              │
│                                                                              │
│  iPhone 6.7" Display (Optional but recommended)                             │
│  ├── Size: 1290 x 2796 pixels (portrait)                                    │
│  └── Quantity: 3-5 screenshots                                              │
│                                                                              │
│  iPhone 5.5" Display (Required if no 6.5")                                  │
│  ├── Size: 1242 x 2208 pixels (portrait)                                    │
│  └── Quantity: 3-5 screenshots                                              │
│                                                                              │
│  iPad Pro (3rd gen) 12.9" (Optional)                                        │
│  ├── Size: 2048 x 2732 pixels (portrait)                                    │
│  └── Quantity: 3-5 screenshots                                              │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### Screenshot Content Guidelines

| Screenshot | Content Description |
|------------|---------------------|
| **1** | Dashboard/Home screen showing key metrics |
| **2** | Main feature (e.g., Herd management, Order screen) |
| **3** | Secondary feature (e.g., Reports, Analytics) |
| **4** | Settings/Profile screen |
| **5** | Notification/Alert example |

---

## 8. REVIEW PROCESS

### 8.1 Common Rejection Reasons

| Reason | Prevention |
|--------|------------|
| **Crashes on launch** | Thorough testing on physical devices |
| **Placeholder content** | Remove all "Lorem ipsum" and placeholder images |
| **Incomplete functionality** | Ensure all buttons work |
| **Inaccurate description** | Description matches actual functionality |
| **Missing privacy policy** | Include valid privacy policy URL |
| **Incorrect age rating** | Rate appropriately based on content |
| **Web content in app** | Provide native iOS experience |

### 8.2 Review Timeline

| Stage | Duration | Notes |
|-------|----------|-------|
| **Waiting for Review** | 1-7 days | Queue depends on volume |
| **In Review** | 1-24 hours | Actual review process |
| **Pending Release** | Immediate | Developer release control |
| **Ready for Sale** | Immediate | Available on App Store |

### 8.3 Expedited Review

For critical bug fixes or time-sensitive releases:

1. Visit: https://developer.apple.com/contact/request/expedited-review/
2. Provide reason: "Critical bug fix affecting farm operations"
3. Include app name, Apple ID, and explanation

---

## 9. POST-RELEASE

### 9.1 Release Options

| Option | Description | Use Case |
|--------|-------------|----------|
| **Manual Release** | Developer releases after approval | Recommended for initial launch |
| **Automatic Release** | Releases immediately after approval | Use for routine updates |
| **Phased Release** | Gradual rollout over 7 days | Risk mitigation for updates |
| **Pre-Order** | Available for pre-order before release | New app launches |

### 9.2 Version Updates

```yaml
# Version numbering convention
# Format: MAJOR.MINOR.PATCH+BUILD

version: 1.0.0+100  # Initial release
version: 1.0.1+101  # Bug fix
version: 1.1.0+110  # New feature
version: 2.0.0+200  # Major update
```

### 9.3 Analytics & Monitoring

```swift
// AppDelegate.swift - Analytics integration
import FirebaseAnalytics

func application(_ application: UIApplication, 
                 didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?) -> Bool {
    // Initialize Firebase
    FirebaseApp.configure()
    
    // Log app open event
    Analytics.logEvent(AnalyticsEventAppOpen, parameters: nil)
    
    return true
}
```

---

## 10. TROUBLESHOOTING

### 10.1 Common Issues

| Issue | Solution |
|-------|----------|
| **Code signing error** | Verify certificates and provisioning profiles |
| **Bundle ID mismatch** | Ensure bundle ID matches App Store Connect |
| **Archive fails** | Check minimum iOS version in Podfile |
| **Upload fails** | Use Application Loader or Transporter app |
| **Metadata rejected** | Update screenshots, description, or keywords |
| **Binary rejected** | Fix reported issues and resubmit |

### 10.2 Build Error Solutions

```bash
# Clean build issues
rm -rf ios/Pods ios/Podfile.lock
flutter clean
flutter pub get
cd ios && pod install && cd ..

# Code signing issues
# In Xcode: Product > Clean Build Folder
# Then: Product > Archive

# Provisioning profile issues
# In Xcode: Preferences > Accounts > Download Manual Profiles
```

---

## 11. APPENDICES

### Appendix A: Metadata Templates

#### App Description Template

```
Smart Dairy Farm - Complete Farm Management Solution

Manage your dairy farm operations with ease using Smart Dairy Farm. 
Our comprehensive mobile app connects you to your farm's IoT sensors, 
providing real-time insights into milk production, animal health, 
and environmental conditions.

KEY FEATURES:
• Real-time milk production tracking
• Individual animal health monitoring
• Environmental sensor dashboards
• Task management for farm workers
• Offline capability for remote areas
• Push notifications for critical alerts
• Integration with Smart Dairy ERP

BENEFITS:
✓ Increase operational efficiency
✓ Improve animal health outcomes
✓ Reduce manual data entry
✓ Make data-driven decisions
✓ Ensure compliance with regulations

WHO IS IT FOR:
• Farm Managers
• Dairy Supervisors
• Veterinary Staff
• Farm Workers

This app requires a Smart Dairy Farm account. Contact us at 
info@smartdairybd.com to learn more.

Privacy Policy: https://smartdairybd.com/privacy-policy
Terms of Service: https://smartdairybd.com/terms-of-service
Support: https://smartdairybd.com/support
```

#### Keywords Template

```
dairy,farm,management,milk,production,cattle,herd,iot,sensors,agriculture,livestock,veterinary,bangladesh,organic
```

### Appendix B: Release Checklist

```
□ App builds successfully in Release mode
□ All tests pass
□ Code review completed
□ App icon is correct (1024×1024)
□ Screenshots are uploaded (all required sizes)
□ App Preview video (optional)
□ Description is complete and accurate
□ Keywords are optimized
□ Support URL is valid
□ Privacy policy URL is valid
□ Pricing is set correctly
□ Availability is configured
□ Content rights are documented
□ Age rating is accurate
□ Build is uploaded and processed
□ Build is selected for review
□ Contact information is current
□ Demo account credentials provided (if needed)
□ Review notes explain complex features
□ App passes internal testing
□ Release strategy is configured
```

---

**END OF APP STORE SUBMISSION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | August 29, 2026 | Mobile Lead | Initial version |
