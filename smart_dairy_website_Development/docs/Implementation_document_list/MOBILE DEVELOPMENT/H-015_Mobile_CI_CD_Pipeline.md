# H-015: Mobile CI/CD Pipeline

## Smart Dairy Ltd - Technical Implementation Document

---

**Document Information**

| Field | Value |
|-------|-------|
| **Document ID** | H-015 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DevOps Engineer |
| **Owner** | Mobile Lead |
| **Reviewer** | CTO |
| **Status** | Draft |

---

**Document History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | DevOps Engineer | Initial document creation |

---

**Approval**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Mobile Lead | | | |
| CTO | | | |
| QA Lead | | | |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Fastlane Setup](#2-fastlane-setup)
3. [GitHub Actions Workflow](#3-github-actions-workflow)
4. [Code Signing](#4-code-signing)
5. [Environment Management](#5-environment-management)
6. [Build Automation](#6-build-automation)
7. [Test Automation](#7-test-automation)
8. [Code Quality](#8-code-quality)
9. [Distribution](#9-distribution)
10. [Release Management](#10-release-management)
11. [Notifications](#11-notifications)
12. [Rollback Strategy](#12-rollback-strategy)
13. [Monitoring](#13-monitoring)
14. [Security](#14-security)
15. [Troubleshooting](#15-troubleshooting)
16. [Appendices](#16-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the Continuous Integration and Continuous Deployment (CI/CD) pipeline for the Smart Dairy mobile application, enabling automated building, testing, and deployment of the mobile app to various distribution channels.

### 1.2 Scope

This document covers:
- CI/CD pipeline architecture and workflow
- Fastlane configuration for build automation
- GitHub Actions workflow definitions
- Code signing and provisioning
- Environment and secret management
- Automated testing and quality gates
- Distribution to TestFlight, Firebase, and Play Console
- Release management and rollback procedures

### 1.3 CI/CD Strategy

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        SMART DAIRY CI/CD PIPELINE                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐              │
│  │   CODE   │───▶│   PR     │───▶│  STATIC  │───▶│  UNIT    │              │
│  │   PUSH   │    │ CREATED  │    │ ANALYSIS │    │  TESTS   │              │
│  └──────────┘    └──────────┘    └──────────┘    └──────────┘              │
│                                                        │                    │
│                                                        ▼                    │
│  ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐              │
│  │  DEPLOY  │◀───│  BUILD   │◀───│ INTEGR.  │◀───│   BUILD  │              │
│  │  TO      │    │   PROD   │    │  TESTS   │    │   DEV    │              │
│  │  STORES  │    │          │    │          │    │  (APK)   │              │
│  └──────────┘    └──────────┘    └──────────┘    └──────────┘              │
│       ▲                                            │                        │
│       │                                            ▼                        │
│  ┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐              │
│  │ FIREBASE │◀───│  BUILD   │◀───│  DEPLOY  │◀───│  BUILD   │              │
│  │   APP    │    │ STAGING  │    │ FIREBASE │    │  STAGING │              │
│  │  DISTRO  │    │          │    │          │    │          │              │
│  └──────────┘    └──────────┘    └──────────┘    └──────────┘              │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 1.4 Tools and Technologies

| Category | Tool | Purpose |
|----------|------|---------|
| **Version Control** | GitHub | Source code management |
| **CI/CD Platform** | GitHub Actions | Workflow automation |
| **Build Automation** | Fastlane | Mobile build and deployment |
| **Code Quality** | Flutter Analyze, Dart Linter | Static analysis |
| **Testing** | Flutter Test | Unit, widget, integration tests |
| **Distribution** | Firebase App Distribution | Beta testing (Android) |
| **Distribution** | TestFlight | Beta testing (iOS) |
| **Distribution** | Google Play Console | Production distribution (Android) |
| **Distribution** | App Store Connect | Production distribution (iOS) |
| **Secrets Management** | GitHub Secrets | Environment variables and keys |
| **Notifications** | Slack | Build status notifications |
| **Code Coverage** | Codecov/LCOV | Test coverage reporting |

### 1.5 Pipeline Stages Overview

| Stage | Trigger | Duration | Purpose |
|-------|---------|----------|---------|
| **1. Code Push** | PR Created | - | Initiate pipeline |
| **2. Static Analysis + Unit Tests** | PR Update | 5-10 min | Code quality gates |
| **3. Build APK/IPA (Dev)** | PR Pass | 10-15 min | Create dev build |
| **4. Integration Tests** | Dev Build Success | 15-20 min | E2E testing |
| **5. Build Release (Staging)** | Merge to develop | 15-20 min | Staging build |
| **6. Deploy to Firebase** | Staging Build | 5 min | Distribute to QA |
| **7. Build Production** | Tag release | 15-20 min | Production build |
| **8. Deploy to Stores** | Prod Build Success | 10 min | App Store/Play Store |

---

## 2. Fastlane Setup

### 2.1 Fastlane Installation

#### 2.1.1 System Requirements

- Ruby 2.5 or higher
- Bundler gem
- macOS for iOS builds
- Linux/macOS/Windows for Android builds

#### 2.1.2 Installation Steps

```bash
# Install Bundler if not present
gem install bundler

# Navigate to project root
cd /path/to/smart-dairy-mobile

# Create Gemfile
cat > Gemfile << 'EOF'
source "https://rubygems.org"

gem "fastlane", "~> 2.219"
gem "cocoapods", "~> 1.14"  # For iOS
EOF

# Install dependencies
bundle install

# Initialize Fastlane (Android)
cd android
bundle exec fastlane init

# Initialize Fastlane (iOS)
cd ../ios
bundle exec fastlane init
```

### 2.2 Fastlane Directory Structure

```
smart-dairy-mobile/
├── android/
│   └── fastlane/
│       ├── Appfile
│       ├── Fastfile
│       ├── Gymfile
│       ├── Matchfile
│       └── metadata/
│           └── android/
├── ios/
│   └── fastlane/
│       ├── Appfile
│       ├── Fastfile
│       ├── Gymfile
│       ├── Matchfile
│       └── metadata/
│           └── ios/
├── Gemfile
├── Gemfile.lock
└── fastlane/
    └── (shared configurations)
```

### 2.3 Fastlane Appfile (Android)

```ruby
# android/fastlane/Appfile

json_key_file("./play-store-key.json")
package_name("com.smartdairy.mobile")

# Path to Play Store service account JSON
# Generate at: Google Cloud Console > APIs & Services > Credentials
```

### 2.4 Fastlane Appfile (iOS)

```ruby
# ios/fastlane/Appfile

app_identifier("com.smartdairy.mobile")
apple_id("dev@smartdairy.com")
iteam_id("XXXXXXXXXX")
itc_team_id("12345678")

# Your Apple Developer credentials
```

### 2.5 Fastlane Match Setup

Match is used for secure code signing certificate management:

```bash
# Initialize Match repository
cd ios
bundle exec fastlane match init

# Choose storage: git (recommended), Google Cloud, or Amazon S3
# Storage mode: git
# URL: git@github.com:smartdairy/certificates.git

# Generate certificates and profiles
bundle exec fastlane match development
bundle exec fastlane match appstore
bundle exec fastlane match adhoc
```

### 2.6 Fastlane Matchfile

```ruby
# ios/fastlane/Matchfile

git_url("git@github.com:smartdairy/certificates.git")
storage_mode("git")

type("development") # or "appstore", "adhoc"

app_identifier(["com.smartdairy.mobile", "com.smartdairy.mobile.dev", "com.smartdairy.mobile.staging"])

username("dev@smartdairy.com")

team_id("XXXXXXXXXX")

# For CI/CD
force_for_new_devices(true)

# Optional: Google Cloud Storage configuration
# google_cloud_bucket_name("smartdairy-certificates")
# google_cloud_keys_file("./gcloud-key.json")
```

---

## 3. GitHub Actions Workflow

### 3.1 Workflow Architecture

```
┌──────────────────────────────────────────────────────────────────────────┐
│                     GITHUB ACTIONS WORKFLOWS                              │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐        │
│  │   pr-checks.yml  │  │  build-dev.yml   │  │ build-staging.yml│        │
│  │   (PR Events)    │  │  (develop push)  │  │  (develop merge) │        │
│  └──────────────────┘  └──────────────────┘  └──────────────────┘        │
│         │                     │                     │                     │
│         ▼                     ▼                     ▼                     │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐        │
│  │  build-prod.yml  │  │  deploy-fb.yml   │  │ deploy-stores.yml│        │
│  │  (release tag)   │  │  (staging build) │  │   (prod build)   │        │
│  └──────────────────┘  └──────────────────┘  └──────────────────┘        │
│                                                                           │
└──────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Pull Request Checks Workflow

```yaml
# .github/workflows/pr-checks.yml

name: PR Checks

on:
  pull_request:
    branches:
      - develop
      - main
    paths:
      - 'lib/**'
      - 'test/**'
      - 'pubspec.yaml'
      - 'pubspec.lock'
      - '.github/workflows/**'

concurrency:
  group: ${{ github.workflow }}-${{ github.event.pull_request.number }}
  cancel-in-progress: true

jobs:
  analyze:
    name: Static Analysis
    runs-on: ubuntu-latest
    timeout-minutes: 10

    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Get Dependencies
        run: flutter pub get

      - name: Run Dart Analyze
        run: flutter analyze --fatal-infos --fatal-warnings

      - name: Check Code Formatting
        run: dart format --set-exit-if-changed lib test

      - name: Run Import Sorter Check
        run: flutter pub run import_sorter:main --exit-if-changed

  unit-tests:
    name: Unit Tests
    runs-on: ubuntu-latest
    timeout-minutes: 15
    needs: analyze

    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Get Dependencies
        run: flutter pub get

      - name: Run Unit Tests
        run: flutter test --coverage --test-randomize-ordering-seed=random

      - name: Upload Coverage Report
        uses: codecov/codecov-action@v3
        with:
          files: coverage/lcov.info
          fail_ci_if_error: true
          verbose: true

      - name: Upload Test Results
        uses: actions/upload-artifact@v4
        if: always()
        with:
          name: test-results
          path: |
            coverage/
            test-results/

  widget-tests:
    name: Widget Tests
    runs-on: ubuntu-latest
    timeout-minutes: 15
    needs: analyze

    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Get Dependencies
        run: flutter pub get

      - name: Run Widget Tests
        run: flutter test test/widget --coverage

  dependency-check:
    name: Dependency Check
    runs-on: ubuntu-latest
    timeout-minutes: 5

    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Get Dependencies
        run: flutter pub get

      - name: Check for Outdated Dependencies
        run: flutter pub outdated

      - name: Run Dependency Validator
        run: flutter pub run dependency_validator
```

### 3.3 Build Workflow (Development)

```yaml
# .github/workflows/build-dev.yml

name: Build Development

on:
  push:
    branches:
      - develop
    paths:
      - 'lib/**'
      - 'android/**'
      - 'ios/**'
      - 'pubspec.yaml'
      - 'pubspec.lock'

concurrency:
  group: ${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: true

jobs:
  build-android-dev:
    name: Build Android (Dev)
    runs-on: ubuntu-latest
    timeout-minutes: 30

    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4

      - name: Setup Java
        uses: actions/setup-java@v4
        with:
          distribution: 'temurin'
          java-version: '17'
          cache: 'gradle'

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Get Dependencies
        run: flutter pub get

      - name: Build Android APK (Dev)
        run: |
          flutter build apk \
            --flavor dev \
            --target lib/main_dev.dart \
            --debug

      - name: Upload APK Artifact
        uses: actions/upload-artifact@v4
        with:
          name: android-dev-apk
          path: build/app/outputs/flutter-apk/app-dev-debug.apk
          retention-days: 7

  build-ios-dev:
    name: Build iOS (Dev)
    runs-on: macos-latest
    timeout-minutes: 45

    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Setup Ruby
        uses: ruby/setup-ruby@v1
        with:
          ruby-version: '3.2'
          bundler-cache: true

      - name: Get Dependencies
        run: flutter pub get

      - name: Install CocoaPods
        run: |
          cd ios
          bundle exec pod install

      - name: Build iOS (Dev)
        run: |
          flutter build ios \
            --flavor dev \
            --target lib/main_dev.dart \
            --debug \
            --no-codesign

      - name: Upload iOS Artifact
        uses: actions/upload-artifact@v4
        with:
          name: ios-dev-build
          path: build/ios/iphonesimulator/
          retention-days: 7
```

### 3.4 Build Workflow (Staging)

```yaml
# .github/workflows/build-staging.yml

name: Build Staging

on:
  workflow_dispatch:
  push:
    branches:
      - develop
    tags:
      - 'staging-v*'

concurrency:
  group: ${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: true

jobs:
  build-android-staging:
    name: Build Android (Staging)
    runs-on: ubuntu-latest
    timeout-minutes: 30
    environment: staging

    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4

      - name: Setup Java
        uses: actions/setup-java@v4
        with:
          distribution: 'temurin'
          java-version: '17'
          cache: 'gradle'

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Setup Ruby
        uses: ruby/setup-ruby@v1
        with:
          ruby-version: '3.2'
          bundler-cache: true

      - name: Decode Keystore
        run: |
          echo "${{ secrets.ANDROID_KEYSTORE_BASE64 }}" | base64 -d > android/app/keystore.jks

      - name: Create key.properties
        run: |
          cat > android/key.properties << EOF
          storePassword=${{ secrets.ANDROID_KEYSTORE_PASSWORD }}
          keyPassword=${{ secrets.ANDROID_KEY_PASSWORD }}
          keyAlias=${{ secrets.ANDROID_KEY_ALIAS }}
          storeFile=keystore.jks
          EOF

      - name: Get Dependencies
        run: flutter pub get

      - name: Build Android AAB (Staging)
        run: |
          flutter build appbundle \
            --flavor staging \
            --target lib/main_staging.dart \
            --release

      - name: Build Android APK (Staging)
        run: |
          flutter build apk \
            --flavor staging \
            --target lib/main_staging.dart \
            --release

      - name: Upload AAB Artifact
        uses: actions/upload-artifact@v4
        with:
          name: android-staging-aab
          path: build/app/outputs/bundle/stagingRelease/app-staging-release.aab
          retention-days: 14

      - name: Upload APK Artifact
        uses: actions/upload-artifact@v4
        with:
          name: android-staging-apk
          path: build/app/outputs/flutter-apk/app-staging-release.apk
          retention-days: 14

      - name: Deploy to Firebase App Distribution
        uses: wzieba/Firebase-Distribution-Github-Action@v1
        with:
          appId: ${{ secrets.FIREBASE_ANDROID_APP_ID_STAGING }}
          serviceCredentialsFileContent: ${{ secrets.FIREBASE_SERVICE_ACCOUNT_KEY }}
          groups: qa-team, developers
          file: build/app/outputs/flutter-apk/app-staging-release.apk
          releaseNotes: |
            Staging Build: ${{ github.sha }}
            Branch: ${{ github.ref_name }}
            Commit: ${{ github.event.head_commit.message }}

  build-ios-staging:
    name: Build iOS (Staging)
    runs-on: macos-latest
    timeout-minutes: 45
    environment: staging

    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4
        with:
          fetch-depth: 0

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Setup Ruby
        uses: ruby/setup-ruby@v1
        with:
          ruby-version: '3.2'
          bundler-cache: true

      - name: Setup SSH Key for Match
        uses: webfactory/ssh-agent@v0.8.0
        with:
          ssh-private-key: ${{ secrets.MATCH_SSH_KEY }}

      - name: Get Dependencies
        run: flutter pub get

      - name: Install CocoaPods
        run: |
          cd ios
          bundle exec pod install

      - name: Run Fastlane (Staging)
        env:
          MATCH_PASSWORD: ${{ secrets.MATCH_PASSWORD }}
          FASTLANE_PASSWORD: ${{ secrets.FASTLANE_PASSWORD }}
          FASTLANE_APPLE_APPLICATION_SPECIFIC_PASSWORD: ${{ secrets.APPLE_APP_SPECIFIC_PASSWORD }}
        run: |
          cd ios
          bundle exec fastlane build_staging

      - name: Upload IPA Artifact
        uses: actions/upload-artifact@v4
        with:
          name: ios-staging-ipa
          path: build/ios/ipa/*.ipa
          retention-days: 14

      - name: Upload to TestFlight (Staging)
        env:
          FASTLANE_PASSWORD: ${{ secrets.FASTLANE_PASSWORD }}
          FASTLANE_APPLE_APPLICATION_SPECIFIC_PASSWORD: ${{ secrets.APPLE_APP_SPECIFIC_PASSWORD }}
        run: |
          cd ios
          bundle exec fastlane upload_testflight_staging
```

### 3.5 Build Workflow (Production)

```yaml
# .github/workflows/build-prod.yml

name: Build Production

on:
  workflow_dispatch:
    inputs:
      release_type:
        description: 'Release Type'
        required: true
        default: 'patch'
        type: choice
        options:
          - patch
          - minor
          - major
  push:
    tags:
      - 'v*'

concurrency:
  group: ${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: true

jobs:
  version-bump:
    name: Bump Version
    runs-on: ubuntu-latest
    if: github.event_name == 'workflow_dispatch'
    outputs:
      new_version: ${{ steps.version.outputs.new_version }}
    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4
        with:
          token: ${{ secrets.GITHUB_TOKEN }}

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'

      - name: Bump Version
        id: version
        run: |
          # Extract current version from pubspec.yaml
          CURRENT_VERSION=$(grep "version:" pubspec.yaml | sed 's/version: //')
          MAJOR=$(echo $CURRENT_VERSION | cut -d. -f1)
          MINOR=$(echo $CURRENT_VERSION | cut -d. -f2)
          PATCH=$(echo $CURRENT_VERSION | cut -d. -f3 | cut -d+ -f1)
          BUILD=$(echo $CURRENT_VERSION | cut -d+ -f2)

          # Bump version based on input
          case "${{ github.event.inputs.release_type }}" in
            major)
              MAJOR=$((MAJOR + 1))
              MINOR=0
              PATCH=0
              ;;
            minor)
              MINOR=$((MINOR + 1))
              PATCH=0
              ;;
            patch)
              PATCH=$((PATCH + 1))
              ;;
          esn

          NEW_BUILD=$((BUILD + 1))
          NEW_VERSION="$MAJOR.$MINOR.$PATCH+$NEW_BUILD"

          # Update pubspec.yaml
          sed -i "s/version: .*/version: $NEW_VERSION/" pubspec.yaml

          echo "new_version=$NEW_VERSION" >> $GITHUB_OUTPUT

      - name: Commit Version Bump
        run: |
          git config user.name "GitHub Actions"
          git config user.email "actions@github.com"
          git add pubspec.yaml
          git commit -m "chore(release): bump version to ${{ steps.version.outputs.new_version }}"
          git push

  build-android-prod:
    name: Build Android (Production)
    runs-on: ubuntu-latest
    timeout-minutes: 30
    environment: production
    needs: version-bump
    if: always() && (needs.version-bump.result == 'success' || needs.version-bump.result == 'skipped')

    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4

      - name: Setup Java
        uses: actions/setup-java@v4
        with:
          distribution: 'temurin'
          java-version: '17'
          cache: 'gradle'

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Setup Ruby
        uses: ruby/setup-ruby@v1
        with:
          ruby-version: '3.2'
          bundler-cache: true

      - name: Decode Keystore
        run: |
          echo "${{ secrets.ANDROID_KEYSTORE_BASE64 }}" | base64 -d > android/app/keystore.jks

      - name: Create key.properties
        run: |
          cat > android/key.properties << EOF
          storePassword=${{ secrets.ANDROID_KEYSTORE_PASSWORD }}
          keyPassword=${{ secrets.ANDROID_KEY_PASSWORD }}
          keyAlias=${{ secrets.ANDROID_KEY_ALIAS }}
          storeFile=keystore.jks
          EOF

      - name: Get Dependencies
        run: flutter pub get

      - name: Build Android AAB (Production)
        run: |
          flutter build appbundle \
            --flavor production \
            --target lib/main_production.dart \
            --release

      - name: Upload AAB Artifact
        uses: actions/upload-artifact@v4
        with:
          name: android-production-aab
          path: build/app/outputs/bundle/productionRelease/app-production-release.aab
          retention-days: 30

  build-ios-prod:
    name: Build iOS (Production)
    runs-on: macos-latest
    timeout-minutes: 45
    environment: production
    needs: version-bump
    if: always() && (needs.version-bump.result == 'success' || needs.version-bump.result == 'skipped')

    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4
        with:
          fetch-depth: 0

      - name: Setup Flutter
        uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true

      - name: Setup Ruby
        uses: ruby/setup-ruby@v1
        with:
          ruby-version: '3.2'
          bundler-cache: true

      - name: Setup SSH Key for Match
        uses: webfactory/ssh-agent@v0.8.0
        with:
          ssh-private-key: ${{ secrets.MATCH_SSH_KEY }}

      - name: Get Dependencies
        run: flutter pub get

      - name: Install CocoaPods
        run: |
          cd ios
          bundle exec pod install

      - name: Run Fastlane (Production)
        env:
          MATCH_PASSWORD: ${{ secrets.MATCH_PASSWORD }}
          FASTLANE_PASSWORD: ${{ secrets.FASTLANE_PASSWORD }}
          FASTLANE_APPLE_APPLICATION_SPECIFIC_PASSWORD: ${{ secrets.APPLE_APP_SPECIFIC_PASSWORD }}
        run: |
          cd ios
          bundle exec fastlane build_production

      - name: Upload IPA Artifact
        uses: actions/upload-artifact@v4
        with:
          name: ios-production-ipa
          path: build/ios/ipa/*.ipa
          retention-days: 30

  deploy-production:
    name: Deploy to Stores
    runs-on: ubuntu-latest
    needs: [build-android-prod, build-ios-prod]
    environment: production

    steps:
      - name: Download Android Artifact
        uses: actions/download-artifact@v4
        with:
          name: android-production-aab
          path: android-build/

      - name: Deploy to Play Store
        uses: r0adkll/upload-google-play@v1
        with:
          serviceAccountJsonPlainText: ${{ secrets.PLAY_STORE_SERVICE_ACCOUNT_JSON }}
          packageName: com.smartdairy.mobile
          releaseFiles: android-build/*.aab
          track: internal
          status: completed
          whatsNewDirectory: distribution/whatsnew

      - name: Create GitHub Release
        uses: softprops/action-gh-release@v1
        with:
          files: |
            android-build/*.aab
          generate_release_notes: true
          prerelease: false
```

---

## 4. Code Signing

### 4.1 Android Signing

#### 4.1.1 Keystore Generation

```bash
# Generate production keystore
keytool -genkey -v \
  -keystore smartdairy-production.keystore \
  -alias smartdairy \
  -keyalg RSA \
  -keysize 2048 \
  -validity 10000 \
  -storepass <store_password> \
  -keypass <key_password> \
  -dname "CN=Smart Dairy Ltd, OU=Mobile Development, O=Smart Dairy, L=Nairobi, ST=Nairobi, C=KE"
```

#### 4.1.2 Keystore Configuration

```gradle
// android/app/build.gradle

android {
    // ...
    
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
            proguardFiles getDefaultProguardFile('proguard-android.txt'), 'proguard-rules.pro'
        }
    }
    
    flavorDimensions "env"
    
    productFlavors {
        dev {
            dimension "env"
            applicationIdSuffix ".dev"
            versionNameSuffix "-dev"
        }
        staging {
            dimension "env"
            applicationIdSuffix ".staging"
            versionNameSuffix "-staging"
        }
        production {
            dimension "env"
        }
    }
}
```

#### 4.1.3 GitHub Secrets for Android

| Secret Name | Description | Format |
|-------------|-------------|--------|
| `ANDROID_KEYSTORE_BASE64` | Base64 encoded keystore file | Base64 string |
| `ANDROID_KEYSTORE_PASSWORD` | Keystore password | Plain text |
| `ANDROID_KEY_PASSWORD` | Key password | Plain text |
| `ANDROID_KEY_ALIAS` | Key alias | Plain text |

### 4.2 iOS Signing

#### 4.2.1 Certificate Types

| Certificate | Purpose | Environment |
|-------------|---------|-------------|
| iOS Development | Debug builds on devices | Dev |
| iOS Distribution | App Store builds | Production |
| Ad Hoc | Internal distribution | Staging |

#### 4.2.2 Provisioning Profiles

| Profile | Type | App ID | Devices |
|---------|------|--------|---------|
| Smart Dairy Dev | Development | com.smartdairy.mobile.dev | Developer devices |
| Smart Dairy Staging | Ad Hoc | com.smartdairy.mobile.staging | QA devices |
| Smart Dairy Prod | App Store | com.smartdairy.mobile | App Store |

#### 4.2.3 Match Configuration

```ruby
# ios/fastlane/Matchfile

git_url("git@github.com:smartdairy/certificates.git")
storage_mode("git")

# Separate branches for different certificate types
git_branch("main")

# App identifiers for all environments
app_identifier([
  "com.smartdairy.mobile",
  "com.smartdairy.mobile.dev",
  "com.smartdairy.mobile.staging"
])

# Apple Developer credentials
username("dev@smartdairy.com")
team_id("XXXXXXXXXX")

# Enable readonly mode for CI
readonly(true)

# Force regenerate when devices change
force_for_new_devices(true)
```

#### 4.2.4 GitHub Secrets for iOS

| Secret Name | Description |
|-------------|-------------|
| `MATCH_SSH_KEY` | SSH private key for certificates repository |
| `MATCH_PASSWORD` | Passphrase for Match encryption |
| `FASTLANE_PASSWORD` | Apple ID password |
| `APPLE_APP_SPECIFIC_PASSWORD` | App-specific password for uploads |
| `FASTLANE_SESSION` | Fastlane session for 2FA |

---

## 5. Environment Management

### 5.1 Environment Files Structure

```
lib/
├── main.dart                 # Entry point selector
├── main_dev.dart             # Development environment
├── main_staging.dart         # Staging environment
├── main_production.dart      # Production environment
├── config/
│   ├── app_config.dart       # Configuration interface
│   ├── dev_config.dart       # Dev configuration
│   ├── staging_config.dart   # Staging configuration
│   └── prod_config.dart      # Production configuration
└── env/
    ├── env_dev.dart          # Dev constants
    ├── env_staging.dart      # Staging constants
    └── env_prod.dart         # Production constants
```

### 5.2 Environment Configuration

```dart
// lib/config/app_config.dart

abstract class AppConfig {
  String get apiBaseUrl;
  String get websocketUrl;
  bool get enableLogging;
  bool get enableCrashlytics;
  String get environmentName;
  String get appName;
}
```

```dart
// lib/config/dev_config.dart

import 'app_config.dart';

class DevConfig implements AppConfig {
  @override
  String get apiBaseUrl => 'https://dev-api.smartdairy.co.ke';
  
  @override
  String get websocketUrl => 'wss://dev-ws.smartdairy.co.ke';
  
  @override
  bool get enableLogging => true;
  
  @override
  bool get enableCrashlytics => false;
  
  @override
  String get environmentName => 'Development';
  
  @override
  String get appName => 'Smart Dairy Dev';
}
```

```dart
// lib/config/staging_config.dart

import 'app_config.dart';

class StagingConfig implements AppConfig {
  @override
  String get apiBaseUrl => 'https://staging-api.smartdairy.co.ke';
  
  @override
  String get websocketUrl => 'wss://staging-ws.smartdairy.co.ke';
  
  @override
  bool get enableLogging => true;
  
  @override
  bool get enableCrashlytics => true;
  
  @override
  String get environmentName => 'Staging';
  
  @override
  String get appName => 'Smart Dairy Staging';
}
```

```dart
// lib/config/prod_config.dart

import 'app_config.dart';

class ProdConfig implements AppConfig {
  @override
  String get apiBaseUrl => 'https://api.smartdairy.co.ke';
  
  @override
  String get websocketUrl => 'wss://ws.smartdairy.co.ke';
  
  @override
  bool get enableLogging => false;
  
  @override
  bool get enableCrashlytics => true;
  
  @override
  String get environmentName => 'Production';
  
  @override
  String get appName => 'Smart Dairy';
}
```

### 5.3 Main Entry Points

```dart
// lib/main_dev.dart

import 'package:flutter/material.dart';
import 'config/dev_config.dart';
import 'main_common.dart';

void main() {
  final config = DevConfig();
  mainCommon(config);
}
```

```dart
// lib/main_staging.dart

import 'package:flutter/material.dart';
import 'config/staging_config.dart';
import 'main_common.dart';

void main() {
  final config = StagingConfig();
  mainCommon(config);
}
```

```dart
// lib/main_production.dart

import 'package:flutter/material.dart';
import 'config/prod_config.dart';
import 'main_common.dart';

void main() {
  final config = ProdConfig();
  mainCommon(config);
}
```

```dart
// lib/main_common.dart

import 'package:flutter/material.dart';
import 'config/app_config.dart';
import 'app.dart';

void mainCommon(AppConfig config) {
  WidgetsFlutterBinding.ensureInitialized();
  
  // Initialize services based on config
  if (config.enableCrashlytics) {
    // Initialize Firebase Crashlytics
  }
  
  if (config.enableLogging) {
    // Initialize logging
  }
  
  runApp(SmartDairyApp(config: config));
}
```

### 5.4 GitHub Secrets Reference

```yaml
# Required GitHub Secrets

# API Keys
API_KEY_DEV: "dev-api-key"
API_KEY_STAGING: "staging-api-key"
API_KEY_PROD: "prod-api-key"

# Firebase
FIREBASE_ANDROID_APP_ID_DEV: "1:xxx:android:xxx"
FIREBASE_ANDROID_APP_ID_STAGING: "1:xxx:android:xxx"
FIREBASE_ANDROID_APP_ID_PROD: "1:xxx:android:xxx"
FIREBASE_IOS_APP_ID_DEV: "1:xxx:ios:xxx"
FIREBASE_IOS_APP_ID_STAGING: "1:xxx:ios:xxx"
FIREBASE_IOS_APP_ID_PROD: "1:xxx:ios:xxx"
FIREBASE_SERVICE_ACCOUNT_KEY: '{"type":"service_account",...}'

# Third-party Services
SENTRY_DSN: "https://xxx@sentry.io/xxx"
MAPS_API_KEY_ANDROID: "AIza..."
MAPS_API_KEY_IOS: "AIza..."
```

---

## 6. Build Automation

### 6.1 Version Management

#### 6.1.1 pubspec.yaml Versioning

```yaml
# pubspec.yaml

name: smart_dairy
version: 1.2.3+45  # Format: MAJOR.MINOR.PATCH+BUILD_NUMBER

# Version Bump Strategy:
# - MAJOR: Breaking changes, new architecture
# - MINOR: New features, backwards compatible
# - PATCH: Bug fixes, small improvements
# - BUILD_NUMBER: Auto-incremented by CI
```

#### 6.1.2 Version Bump Script

```bash
#!/bin/bash
# scripts/bump_version.sh

BUMP_TYPE=${1:-patch}  # major, minor, or patch

current_version=$(grep "^version:" pubspec.yaml | sed 's/version: //')
echo "Current version: $current_version"

# Parse version
IFS='.' read -r major minor_patch <<< "$current_version"
IFS='+' read -r minor patch_build <<< "$minor_patch"
IFS='+' read -r patch build <<< "$patch_build"

# Bump version
 case $BUMP_TYPE in
  major)
    major=$((major + 1))
    minor=0
    patch=0
    ;;
  minor)
    minor=$((minor + 1))
    patch=0
    ;;
  patch)
    patch=$((patch + 1))
    ;;
esac

build=$((build + 1))
new_version="$major.$minor.$patch+$build"

echo "New version: $new_version"

# Update pubspec.yaml
sed -i "s/^version: .*/version: $new_version/" pubspec.yaml

# Commit changes
git add pubspec.yaml
git commit -m "chore(release): bump version to $new_version"
```

#### 6.1.3 Automated Version in CI

```yaml
# .github/workflows/version-bump.yml

name: Version Bump

on:
  workflow_dispatch:
    inputs:
      bump_type:
        description: 'Version bump type'
        required: true
        default: 'patch'
        type: choice
        options:
          - patch
          - minor
          - major

jobs:
  bump:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Bump Version
        run: |
          chmod +x scripts/bump_version.sh
          ./scripts/bump_version.sh ${{ github.event.inputs.bump_type }}
      - name: Push Changes
        run: git push origin HEAD:main
```

### 6.2 Changelog Generation

#### 6.2.1 Changelog Configuration

```yaml
# .github_changelog_generator

user: smartdairy
project: mobile-app
since-tag: v1.0.0
unreleased: true
future-release: v1.3.0
exclude-labels: duplicate,question,invalid,wontfix
enhancement-label: "### New Features"
bug-label: "### Bug Fixes"
breaking-label: "### Breaking Changes"
```

#### 6.2.2 Changelog Workflow

```yaml
# .github/workflows/changelog.yml

name: Generate Changelog

on:
  release:
    types: [created]

jobs:
  changelog:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
        with:
          fetch-depth: 0

      - name: Generate Changelog
        uses: github-changelog-generator/github-changelog-generator@v1
        with:
          token: ${{ secrets.GITHUB_TOKEN }}

      - name: Commit Changelog
        run: |
          git config user.name "GitHub Actions"
          git config user.email "actions@github.com"
          git add CHANGELOG.md
          git commit -m "docs: update changelog"
          git push
```

### 6.3 Build Number Synchronization

```ruby
# ios/fastlane/Fastfile

# Sync build number with App Store Connect
private_lane :sync_build_number do
  build_number = app_store_build_number(
    live: false,
    app_identifier: "com.smartdairy.mobile"
  )
  
  increment_build_number(
    build_number: build_number + 1,
    xcodeproj: "Runner.xcodeproj"
  )
end
```

---

## 7. Test Automation

### 7.1 Test Structure

```
test/
├── unit/
│   ├── models/
│   │   ├── user_model_test.dart
│   │   ├── milk_production_test.dart
│   │   └── animal_model_test.dart
│   ├── repositories/
│   │   ├── auth_repository_test.dart
│   │   └── data_repository_test.dart
│   ├── blocs/
│   │   ├── auth_bloc_test.dart
│   │   └── dashboard_bloc_test.dart
│   └── services/
│       ├── api_service_test.dart
│       └── storage_service_test.dart
├── widget/
│   ├── screens/
│   │   ├── login_screen_test.dart
│   │   └── dashboard_screen_test.dart
│   ├── components/
│   │   ├── custom_button_test.dart
│   │   └── input_field_test.dart
│   └── flows/
│       ├── login_flow_test.dart
│       └── milk_entry_flow_test.dart
└── integration/
    ├── app_test.dart
    └── flows/
        ├── complete_milk_recording_test.dart
        └── animal_registration_test.dart
```

### 7.2 Unit Tests

```dart
// test/unit/models/user_model_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:smart_dairy/models/user_model.dart';

void main() {
  group('UserModel', () {
    test('should create UserModel from JSON', () {
      final json = {
        'id': '123',
        'email': 'farmer@example.com',
        'name': 'John Doe',
        'farmId': 'farm_001',
        'role': 'farmer',
      };

      final user = UserModel.fromJson(json);

      expect(user.id, '123');
      expect(user.email, 'farmer@example.com');
      expect(user.name, 'John Doe');
    });

    test('should convert UserModel to JSON', () {
      final user = UserModel(
        id: '123',
        email: 'farmer@example.com',
        name: 'John Doe',
        farmId: 'farm_001',
        role: UserRole.farmer,
      );

      final json = user.toJson();

      expect(json['id'], '123');
      expect(json['email'], 'farmer@example.com');
    });
  });
}
```

### 7.3 Widget Tests

```dart
// test/widget/screens/login_screen_test.dart

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:smart_dairy/screens/login_screen.dart';

void main() {
  group('LoginScreen', () {
    testWidgets('should display email and password fields', 
      (WidgetTester tester) async {
      await tester.pumpWidget(MaterialApp(home: LoginScreen()));

      expect(find.byType(TextField), findsNWidgets(2));
      expect(find.text('Email'), findsOneWidget);
      expect(find.text('Password'), findsOneWidget);
    });

    testWidgets('should show error for invalid email', 
      (WidgetTester tester) async {
      await tester.pumpWidget(MaterialApp(home: LoginScreen()));

      await tester.enterText(
        find.byKey(Key('email_field')), 
        'invalid-email'
      );
      await tester.tap(find.byType(ElevatedButton));
      await tester.pump();

      expect(find.text('Please enter a valid email'), findsOneWidget);
    });
  });
}
```

### 7.4 Integration Tests

```dart
// test/integration/app_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:smart_dairy/main.dart' as app;

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('App Integration Tests', () {
    testWidgets('complete login flow', (WidgetTester tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Enter credentials
      await tester.enterText(
        find.byKey(Key('email_field')), 
        'test@smartdairy.com'
      );
      await tester.enterText(
        find.byKey(Key('password_field')), 
        'password123'
      );

      // Tap login button
      await tester.tap(find.byKey(Key('login_button')));
      await tester.pumpAndSettle(Duration(seconds: 3));

      // Verify dashboard is shown
      expect(find.text('Dashboard'), findsOneWidget);
    });

    testWidgets('add milk production record', (WidgetTester tester) async {
      app.main();
      await tester.pumpAndSettle();

      // Navigate to milk entry
      await tester.tap(find.byIcon(Icons.add));
      await tester.pumpAndSettle();

      // Select animal
      await tester.tap(find.byKey(Key('animal_dropdown')));
      await tester.pumpAndSettle();
      await tester.tap(find.text('Cow #001').last);
      await tester.pumpAndSettle();

      // Enter quantity
      await tester.enterText(
        find.byKey(Key('quantity_field')), 
        '15.5'
      );

      // Save record
      await tester.tap(find.byKey(Key('save_button')));
      await tester.pumpAndSettle();

      // Verify success message
      expect(find.text('Record saved successfully'), findsOneWidget);
    });
  });
}
```

### 7.5 Test Automation in CI

```yaml
# .github/workflows/integration-tests.yml

name: Integration Tests

on:
  schedule:
    - cron: '0 2 * * *'  # Daily at 2 AM
  workflow_dispatch:

jobs:
  android-integration:
    runs-on: macos-latest
    timeout-minutes: 45
    
    steps:
      - uses: actions/checkout@v4
      
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
      
      - name: Start Android Emulator
        uses: reactivecircus/android-emulator-runner@v2
        with:
          api-level: 29
          arch: x86_64
          profile: pixel_4
          script: |
            flutter pub get
            flutter test integration_test/

  ios-integration:
    runs-on: macos-latest
    timeout-minutes: 45
    
    steps:
      - uses: actions/checkout@v4
      
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
      
      - name: Start iOS Simulator
        run: |
          device_id=$(xcrun simctl create test-device "iPhone 14" "com.apple.CoreSimulator.SimDeviceType.iPhone-14")
          xcrun simctl boot $device_id
          xcrun simctl list devices
      
      - name: Run Integration Tests
        run: |
          flutter pub get
          flutter test integration_test/ --device-id=$device_id
```

---

## 8. Code Quality

### 8.1 Dart Analysis Configuration

```yaml
# analysis_options.yaml

include: package:flutter_lints/flutter.yaml

analyzer:
  exclude:
    - "**/*.g.dart"
    - "**/*.freezed.dart"
    - "**/generated_plugin_registrant.dart"
  
  language:
    strict-casts: true
    strict-raw-types: true
  
  errors:
    missing_required_param: error
    missing_return: error
    invalid_assignment: warning
    unused_local_variable: warning
    avoid_print: warning

linter:
  rules:
    # Style Rules
    - prefer_single_quotes
    - prefer_const_constructors
    - prefer_const_literals_to_create_immutables
    - prefer_final_locals
    - prefer_final_in_for_each
    - avoid_unnecessary_containers
    - avoid_redundant_argument_values
    - avoid_returning_null
    - avoid_returning_this
    
    # Documentation
    - public_member_api_docs
    - package_api_docs
    
    # Performance
    - avoid_slow_async_io
    - avoid unnecessary_async
    
    # Error Prevention
    - avoid_catches_without_on_clauses
    - avoid_catching_errors
    - use_rethrow_when_possible
    - throw_in_finally
    
    # Flutter Specific
    - avoid_print
    - avoid_web_libraries_in_flutter
    - no_logic_in_create_state
    - prefer_const_constructors_in_immutables
    - prefer_extracting_callbacks
    - sized_box_for_whitespace
    - use_full_hex_values_for_flutter_colors
    - use_key_in_widget_constructors
```

### 8.2 Pre-commit Hooks

```yaml
# .pre-commit-config.yaml

repos:
  - repo: https://github.com/fluttercommunity/import_sorter
    rev: "main"
    hooks:
      - id: flutter-import-sorter

  - repo: local
    hooks:
      - id: dart-format
        name: Dart Format
        entry: dart format --set-exit-if-changed
        language: system
        files: \.dart$

      - id: flutter-analyze
        name: Flutter Analyze
        entry: flutter analyze
        language: system
        files: \.dart$
        pass_filenames: false

      - id: flutter-test
        name: Flutter Test
        entry: flutter test
        language: system
        files: \.dart$
        pass_filenames: false
```

### 8.3 Code Coverage

```yaml
# .github/workflows/coverage.yml

name: Code Coverage

on:
  push:
    branches: [ develop, main ]
  pull_request:
    branches: [ develop, main ]

jobs:
  coverage:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
      
      - name: Get Dependencies
        run: flutter pub get
      
      - name: Run Tests with Coverage
        run: flutter test --coverage
      
      - name: Upload to Codecov
        uses: codecov/codecov-action@v3
        with:
          files: coverage/lcov.info
          fail_ci_if_error: true
          verbose: true
      
      - name: Generate Coverage Report
        run: |
          dart pub global activate coverage_report
          coverage_report --lcov-file=coverage/lcov.info
      
      - name: Check Coverage Threshold
        run: |
          COVERAGE=$(lcov --summary coverage/lcov.info | grep "lines" | sed 's/.*: \(.*\)%/\1/')
          echo "Total coverage: $COVERAGE%"
          if (( $(echo "$COVERAGE < 80" | bc -l) )); then
            echo "Coverage below 80% threshold!"
            exit 1
          fi
```

### 8.4 Coverage Badge

```markdown
<!-- README.md -->

![Coverage](https://img.shields.io/codecov/c/github/smartdairy/mobile-app)
![Tests](https://img.shields.io/github/workflow/status/smartdairy/mobile-app/Unit%20Tests)
```

---

## 9. Distribution

### 9.1 TestFlight Distribution

```ruby
# ios/fastlane/Fastfile

platform :ios do
  desc "Build and upload to TestFlight"
  lane :upload_testflight_staging do
    # Setup signing
    match(type: "appstore", readonly: true)
    
    # Build app
    build_ios_app(
      scheme: "Staging",
      configuration: "Release-Staging",
      export_method: "app-store",
      output_directory: "build/ios/ipa",
      output_name: "SmartDairy_Staging.ipa"
    )
    
    # Upload to TestFlight
    upload_to_testflight(
      skip_waiting_for_build_processing: true,
      notify_external_testers: false,
      changelog: generate_changelog
    )
  end

  desc "Build and upload to TestFlight (Production)"
  lane :upload_testflight_prod do
    match(type: "appstore", readonly: true)
    
    build_ios_app(
      scheme: "Production",
      configuration: "Release-Production",
      export_method: "app-store",
      output_directory: "build/ios/ipa",
      output_name: "SmartDairy.ipa"
    )
    
    upload_to_testflight(
      skip_waiting_for_build_processing: false,
      notify_external_testers: true,
      distribute_external: true,
      groups: ["QA Team", "Beta Testers"],
      changelog: generate_changelog
    )
  end

  private

  def generate_changelog
    changelog_from_git_commits(
      between: ["HEAD~10", "HEAD"],
      pretty: "- %s",
      date_format: "short"
    )
  end
end
```

### 9.2 Firebase App Distribution

```yaml
# .github/workflows/deploy-firebase.yml

name: Deploy to Firebase

on:
  workflow_run:
    workflows: ["Build Staging"]
    types:
      - completed

jobs:
  deploy-android:
    if: ${{ github.event.workflow_run.conclusion == 'success' }}
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Download Artifact
        uses: actions/download-artifact@v4
        with:
          name: android-staging-apk
          path: apk/
      
      - name: Deploy to Firebase
        uses: wzieba/Firebase-Distribution-Github-Action@v1
        with:
          appId: ${{ secrets.FIREBASE_ANDROID_APP_ID_STAGING }}
          serviceCredentialsFileContent: ${{ secrets.FIREBASE_SERVICE_ACCOUNT_KEY }}
          groups: qa-team, developers, internal-testers
          file: apk/app-staging-release.apk
          releaseNotesFile: RELEASE_NOTES.txt
```

```ruby
# android/fastlane/Fastfile

platform :android do
  desc "Deploy to Firebase App Distribution"
  lane :deploy_firebase do
    firebase_app_distribution(
      app: ENV["FIREBASE_ANDROID_APP_ID"],
      firebase_cli_token: ENV["FIREBASE_TOKEN"],
      android_artifact_type: "APK",
      android_artifact_path: "../build/app/outputs/flutter-apk/app-staging-release.apk",
      groups: "qa-team, developers",
      release_notes: File.read("../RELEASE_NOTES.txt")
    )
  end
end
```

### 9.3 Google Play Console Distribution

```ruby
# android/fastlane/Fastfile

platform :android do
  desc "Deploy to Play Store Internal"
  lane :deploy_internal do
    upload_to_play_store(
      track: "internal",
      aab: "../build/app/outputs/bundle/productionRelease/app-production-release.aab",
      skip_upload_metadata: false,
      skip_upload_images: false,
      skip_upload_screenshots: false
    )
  end

  desc "Deploy to Play Store Alpha"
  lane :deploy_alpha do
    upload_to_play_store(
      track: "alpha",
      aab: "../build/app/outputs/bundle/productionRelease/app-production-release.aab",
      skip_upload_metadata: true,
      skip_upload_images: true,
      skip_upload_screenshots: true
    )
  end

  desc "Deploy to Play Store Beta"
  lane :deploy_beta do
    upload_to_play_store(
      track: "beta",
      aab: "../build/app/outputs/bundle/productionRelease/app-production-release.aab",
      skip_upload_metadata: true,
      skip_upload_images: true,
      skip_upload_screenshots: true
    )
  end

  desc "Deploy to Play Store Production"
  lane :deploy_production do
    upload_to_play_store(
      track: "production",
      aab: "../build/app/outputs/bundle/productionRelease/app-production-release.aab",
      skip_upload_metadata: false,
      skip_upload_images: false,
      skip_upload_screenshots: false
    )
  end

  desc "Promote Beta to Production"
  lane :promote_to_production do
    upload_to_play_store(
      track: "beta",
      track_promote_to: "production",
      skip_upload_changelogs: false
    )
  end
end
```

### 9.4 App Store Distribution

```ruby
# ios/fastlane/Fastfile

platform :ios do
  desc "Deploy to App Store"
  lane :deploy_appstore do
    match(type: "appstore", readonly: true)
    
    build_ios_app(
      scheme: "Production",
      configuration: "Release-Production",
      export_method: "app-store"
    )
    
    upload_to_app_store(
      force: true,
      skip_metadata: false,
      skip_screenshots: false,
      submit_for_review: false,
      automatic_release: false,
      precheck_include_in_app_purchases: false
    )
  end
end
```

---

## 10. Release Management

### 10.1 Release Process

```
┌───────────────────────────────────────────────────────────────────────────┐
│                        RELEASE PROCESS FLOW                                │
├───────────────────────────────────────────────────────────────────────────┤
│                                                                           │
│  1. FEATURE COMPLETION                                                    │
│     ├── All features merged to develop                                    │
│     └── Integration tests passing                                        │
│                              │                                            │
│                              ▼                                            │
│  2. RELEASE PREPARATION                                                   │
│     ├── Create release branch: release/v1.x.x                            │
│     ├── Update version in pubspec.yaml                                   │
│     ├── Update CHANGELOG.md                                              │
│     └── Run full regression tests                                        │
│                              │                                            │
│                              ▼                                            │
│  3. STAGING DEPLOYMENT                                                    │
│     ├── Build staging release                                            │
│     ├── Deploy to Firebase (Android)                                     │
│     ├── Upload to TestFlight (iOS)                                       │
│     └── QA validation                                                    │
│                              │                                            │
│                              ▼                                            │
│  4. PRODUCTION RELEASE                                                    │
│     ├── Merge release branch to main                                     │
│     ├── Tag release: v1.x.x                                              │
│     ├── Build production release                                         │
│     ├── Deploy to Play Store (Internal)                                  │
│     ├── Upload to App Store (TestFlight)                                 │
│     └── Create GitHub Release                                            │
│                              │                                            │
│                              ▼                                            │
│  5. ROLLOUT                                                               │
│     ├── Phased rollout (5% → 20% → 50% → 100%)                          │
│     ├── Monitor crash reports                                            │
│     └── Monitor analytics                                                │
│                                                                           │
└───────────────────────────────────────────────────────────────────────────┘
```

### 10.2 Release Branching Strategy

```
main ──────────────────────────────────────────────────────────────
       │                              │
       │         release/v1.2.0       │
       │         ─────────────        │
       │        /               \     │
 develop────────────────────────────────────────────────────────────
   │     \               /          │
   │      \             /           │
   │   feature/auth  feature/analytics
   │
 hotfix/v1.2.1
```

### 10.3 Versioning Strategy

| Version Type | Format | Example | Trigger |
|--------------|--------|---------|---------|
| Major | X.0.0 | 2.0.0 | Breaking API changes |
| Minor | x.Y.0 | 1.3.0 | New features |
| Patch | x.y.Z | 1.2.3 | Bug fixes |
| Build | x.y.z+B | 1.2.3+45 | CI build number |

### 10.4 Release Notes Template

```markdown
## Smart Dairy v{VERSION} Release Notes

### Release Date: {DATE}

### What's New
- Feature 1: Description
- Feature 2: Description

### Improvements
- Improvement 1: Description
- Improvement 2: Description

### Bug Fixes
- Fix 1: Description
- Fix 2: Description

### Known Issues
- Issue 1: Description and workaround

### Internal
- Technical updates
- Dependency upgrades
```

---

## 11. Notifications

### 11.1 Slack Integration

```yaml
# .github/workflows/notify-slack.yml

name: Slack Notifications

on:
  workflow_run:
    workflows: ["PR Checks", "Build Staging", "Build Production", "Deploy to Stores"]
    types:
      - completed

jobs:
  notify:
    runs-on: ubuntu-latest
    if: always()
    steps:
      - name: Notify Slack - Success
        if: ${{ github.event.workflow_run.conclusion == 'success' }}
        uses: slackapi/slack-github-action@v1
        with:
          payload: |
            {
              "text": "✅ *${{ github.event.workflow_run.name }}* succeeded",
              "blocks": [
                {
                  "type": "section",
                  "text": {
                    "type": "mrkdwn",
                    "text": "✅ *${{ github.event.workflow_run.name }}* completed successfully"
                  }
                },
                {
                  "type": "section",
                  "fields": [
                    {
                      "type": "mrkdwn",
                      "text": "*Branch:*\n${{ github.event.workflow_run.head_branch }}"
                    },
                    {
                      "type": "mrkdwn",
                      "text": "*Commit:*\n${{ github.event.workflow_run.head_commit.id }}"
                    },
                    {
                      "type": "mrkdwn",
                      "text": "*Author:*\n${{ github.event.workflow_run.head_commit.author.name }}"
                    },
                    {
                      "type": "mrkdwn",
                      "text": "*Duration:*\n${{ github.event.workflow_run.run_started_at }}"
                    }
                  ]
                },
                {
                  "type": "actions",
                  "elements": [
                    {
                      "type": "button",
                      "text": {
                        "type": "plain_text",
                        "text": "View Run"
                      },
                      "url": "${{ github.event.workflow_run.html_url }}"
                    }
                  ]
                }
              ]
            }
        env:
          SLACK_WEBHOOK_URL: ${{ secrets.SLACK_WEBHOOK_URL }}

      - name: Notify Slack - Failure
        if: ${{ github.event.workflow_run.conclusion == 'failure' }}
        uses: slackapi/slack-github-action@v1
        with:
          payload: |
            {
              "text": "❌ *${{ github.event.workflow_run.name }}* failed",
              "blocks": [
                {
                  "type": "section",
                  "text": {
                    "type": "mrkdwn",
                    "text": "❌ *${{ github.event.workflow_run.name }}* failed"
                  }
                },
                {
                  "type": "section",
                  "fields": [
                    {
                      "type": "mrkdwn",
                      "text": "*Branch:*\n${{ github.event.workflow_run.head_branch }}"
                    },
                    {
                      "type": "mrkdwn",
                      "text": "*Commit:*\n${{ github.event.workflow_run.head_commit.message }}"
                    },
                    {
                      "type": "mrkdwn",
                      "text": "*Author:*\n${{ github.event.workflow_run.head_commit.author.name }}"
                    },
                    {
                      "type": "mrkdwn",
                      "text": "*Failed Job:*\nCheck workflow run details"
                    }
                  ]
                },
                {
                  "type": "actions",
                  "elements": [
                    {
                      "type": "button",
                      "text": {
                        "type": "plain_text",
                        "text": "View Run"
                      },
                      "style": "danger",
                      "url": "${{ github.event.workflow_run.html_url }}"
                    },
                    {
                      "type": "button",
                      "text": {
                        "type": "plain_text",
                        "text": "View Commit"
                      },
                      "url": "${{ github.event.workflow_run.head_commit.url }}"
                    }
                  ]
                }
              ]
            }
        env:
          SLACK_WEBHOOK_URL: ${{ secrets.SLACK_WEBHOOK_URL }}
```

### 11.2 Email Notifications

```yaml
# .github/workflows/notify-email.yml

name: Email Notifications

on:
  release:
    types: [published]

jobs:
  notify:
    runs-on: ubuntu-latest
    steps:
      - name: Send Release Email
        uses: dawidd6/action-send-mail@v3
        with:
          server_address: smtp.gmail.com
          server_port: 587
          username: ${{ secrets.EMAIL_USERNAME }}
          password: ${{ secrets.EMAIL_PASSWORD }}
          subject: "Smart Dairy ${{ github.event.release.tag_name }} Released"
          to: mobile-team@smartdairy.co.ke,qa-team@smartdairy.co.ke
          from: ci-cd@smartdairy.co.ke
          html_body: |
            <h2>Smart Dairy ${{ github.event.release.tag_name }} Released</h2>
            <p>A new version of Smart Dairy has been released.</p>
            <h3>Release Notes:</h3>
            <p>${{ github.event.release.body }}</p>
            <p>
              <a href="${{ github.event.release.html_url }}">View Release</a>
            </p>
```

### 11.3 Deployment Status Notifications

```ruby
# fastlane/notify.rb

def notify_deployment(platform:, environment:, version:, status:)
  color = status == "success" ? "#36a64f" : "#ff0000"
  emoji = status == "success" ? "✅" : "❌"
  
  payload = {
    attachments: [{
      color: color,
      title: "#{emoji} Deployment #{status.upcase}: Smart Dairy #{version}",
      fields: [
        { title: "Platform", value: platform, short: true },
        { title: "Environment", value: environment, short: true },
        { title: "Version", value: version, short: true },
        { title: "Time", value: Time.now.strftime("%Y-%m-%d %H:%M"), short: true }
      ]
    }]
  }
  
  HTTParty.post(
    ENV["SLACK_WEBHOOK_URL"],
    body: payload.to_json,
    headers: { 'Content-Type' => 'application/json' }
  )
end
```

---

## 12. Rollback Strategy

### 12.1 Rollback Scenarios

| Scenario | Severity | Rollback Action | Time to Recovery |
|----------|----------|-----------------|------------------|
| Critical bug in production | P0 | Immediate version rollback | 15-30 min |
| Performance degradation | P1 | Gradual rollout halt + hotfix | 1-2 hours |
| Feature issue | P2 | Disable feature flag + fix | 2-4 hours |
| Minor UI issue | P3 | Schedule fix in next release | Next release |

### 12.2 Android Rollback

```ruby
# android/fastlane/Fastfile

desc "Rollback Play Store to previous version"
lane :rollback_production do
  # Get previous version code
  version_codes = google_play_track_version_codes(
    track: "production"
  )
  
  previous_version = version_codes[1]  # Second most recent
  
  # Promote previous version
  supply(
    track: "production",
    version_code: previous_version,
    skip_upload_apk: true,
    skip_upload_aab: true,
    skip_upload_metadata: true,
    skip_upload_images: true,
    skip_upload_screenshots: true
  )
  
  # Notify team
  notify_deployment(
    platform: "Android",
    environment: "Production",
    version: previous_version.to_s,
    status: "rollback"
  )
end
```

### 12.3 iOS Rollback

```ruby
# ios/fastlane/Fastfile

desc "Expire current TestFlight build"
lane :expire_build do
  # Expire problematic build
  spaceship = Spaceship::Tunes.login(ENV["FASTLANE_USER"])
  app = spaceship.application.find("com.smartdairy.mobile")
  
  # Expire specific build
  build = app.builds.find { |b| b.build_version == options[:build_number] }
  build.expire! if build
end
```

### 12.4 Emergency Hotfix Process

```
┌─────────────────────────────────────────────────────────────────┐
│                    HOTFIX PROCESS                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1. IDENTIFY ISSUE                                              │
│     └── Critical bug reported in production                     │
│                                                                 │
│  2. CREATE HOTFIX BRANCH                                        │
│     └── git checkout -b hotfix/v1.2.1 main                      │
│                                                                 │
│  3. FIX ISSUE                                                   │
│     └── Apply minimal fix                                       │
│     └── Write/update tests                                      │
│                                                                 │
│  4. FAST-TRACK TESTING                                          │
│     └── Unit tests                                              │
│     └── Critical path integration tests                         │
│                                                                 │
│  5. BUILD & DEPLOY                                              │
│     └── Skip normal staging deployment                          │
│     └── Direct production deployment                            │
│                                                                 │
│  6. MONITOR                                                     │
│     └── Watch crash reports                                     │
│     └── Monitor user feedback                                   │
│                                                                 │
│  7. MERGE BACK                                                  │
│     └── Merge hotfix to main                                    │
│     └── Cherry-pick to develop                                  │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 13. Monitoring

### 13.1 Build Metrics

```yaml
# .github/workflows/metrics.yml

name: Build Metrics

on:
  workflow_run:
    workflows: ["PR Checks", "Build Staging", "Build Production"]
    types:
      - completed

jobs:
  metrics:
    runs-on: ubuntu-latest
    steps:
      - name: Collect Build Metrics
        run: |
          cat > metrics.json << EOF
          {
            "workflow": "${{ github.event.workflow_run.name }}",
            "status": "${{ github.event.workflow_run.conclusion }}",
            "duration_seconds": ${{ github.event.workflow_run.run_duration_ms / 1000 }},
            "branch": "${{ github.event.workflow_run.head_branch }}",
            "timestamp": "${{ github.event.workflow_run.created_at }}",
            "runner_os": "${{ github.event.workflow_run.run_started_at }}"
          }
          EOF
      
      - name: Upload to Monitoring
        run: |
          curl -X POST ${{ secrets.METRICS_ENDPOINT }} \
            -H "Content-Type: application/json" \
            -d @metrics.json
```

### 13.2 Build Dashboard

| Metric | Target | Current | Trend |
|--------|--------|---------|-------|
| Build Success Rate | >95% | 98.5% | ↑ |
| Average Build Time | <20 min | 15 min | ↓ |
| Test Pass Rate | >90% | 94% | ↑ |
| Code Coverage | >80% | 83% | ↑ |
| Deployment Frequency | Daily | 1.2/day | ↑ |
| Lead Time | <2 days | 1.5 days | ↓ |
| Mean Time to Recovery | <1 hour | 45 min | ↓ |

### 13.3 Alerting Rules

```yaml
# .github/alerts.yml

alerts:
  - name: Build Failure Rate
    condition: failure_rate > 10%
    window: 1h
    severity: warning
    
  - name: Critical Build Failure
    condition: main_branch_failure
    severity: critical
    notify: ["mobile-lead", "devops-team"]
    
  - name: Long Build Time
    condition: build_duration > 30min
    severity: warning
    
  - name: Test Coverage Drop
    condition: coverage < 80%
    severity: warning
```

---

## 14. Security

### 14.1 Secret Management

#### 14.1.1 GitHub Secrets Organization

| Environment | Secrets |
|-------------|---------|
| Repository | Common configuration, non-sensitive |
| Staging | Staging API keys, test certificates |
| Production | Production API keys, signing certificates |

#### 14.1.2 Secret Rotation Schedule

| Secret Type | Rotation Frequency | Owner |
|-------------|-------------------|-------|
| API Keys | 90 days | Security Team |
| Signing Certificates | 1 year | Mobile Lead |
| Service Account Keys | 180 days | DevOps |
| SSH Keys | 1 year | DevOps |

### 14.2 Signing Security

```yaml
# Security checklist for signing

android_signing:
  - keystore_stored: "GitHub Secrets (Base64 encoded)"
  - keystore_password: "GitHub Secrets"
  - key_alias: "GitHub Secrets"
  - key_password: "GitHub Secrets (different from keystore)"
  - backup_location: "Secure corporate vault"
  
ios_signing:
  - match_repository: "Private encrypted git repo"
  - match_passphrase: "GitHub Secrets"
  - certificates: "Managed via Match, no local storage"
  - provisioning_profiles: "Auto-generated via Match"
```

### 14.3 CI/CD Security Hardening

```yaml
# .github/workflows/secure-build.yml

name: Secure Build

on:
  push:
    branches: [main, develop]

jobs:
  security-scan:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      # Dependency vulnerability scan
      - name: Run OWASP Dependency Check
        uses: dependency-check/Dependency-Check_Action@main
        with:
          project: 'smart-dairy-mobile'
          path: '.'
          format: 'ALL'

      # Secret scanning
      - name: Secret Detection
        uses: trufflesecurity/trufflehog@main
        with:
          path: ./
          base: main
          head: HEAD

      # SAST
      - name: Static Analysis
        uses: returntocorp/semgrep-action@v1
        with:
          config: >-
            p/security-audit
            p/owasp-mobile

      - name: Upload Security Report
        uses: actions/upload-artifact@v4
        with:
          name: security-report
          path: reports/
```

### 14.4 Access Control

| Role | PR Review | Staging Deploy | Production Deploy |
|------|-----------|----------------|-------------------|
| Developer | Required | Auto (after merge) | - |
| Mobile Lead | Required | Auto | Manual approval |
| DevOps | - | Auto | Manual approval |
| CTO | - | - | Required approval |

---

## 15. Troubleshooting

### 15.1 Common CI Issues

#### 15.1.1 Flutter Version Mismatch

**Symptom:** Build fails with "version solving failed"

**Solution:**
```yaml
- uses: subosito/flutter-action@v2
  with:
    flutter-version: '3.16.0'  # Lock to specific version
    channel: 'stable'
```

#### 15.1.2 iOS Code Signing Errors

**Symptom:** "No signing certificate found"

**Solution:**
```bash
# Reset Match certificates
cd ios
bundle exec fastlane match nuke development
bundle exec fastlane match development

# Or regenerate
cd ios
bundle exec fastlane match development --force
```

#### 15.1.3 Android Keystore Issues

**Symptom:** "Keystore was tampered with"

**Solution:**
```bash
# Verify keystore
keytool -list -v -keystore keystore.jks

# Regenerate if needed
keytool -genkey -v -keystore smartdairy.keystore -alias smartdairy -keyalg RSA -keysize 2048 -validity 10000
```

#### 15.1.4 CocoaPods Installation Failures

**Symptom:** "pod install failed"

**Solution:**
```yaml
- name: Clean Install CocoaPods
  run: |
    cd ios
    rm -rf Pods Podfile.lock
    bundle exec pod deintegrate
    bundle exec pod install --repo-update
```

### 15.2 Build Failure Checklist

```
□ Check Flutter version compatibility
□ Verify pubspec.yaml dependencies
□ Check Android SDK version
□ Verify iOS deployment target
□ Check code signing certificates
□ Verify provisioning profiles
□ Check keystore configuration
□ Verify environment variables
□ Check GitHub secrets are set
□ Review build logs for specific errors
□ Check if dependencies are cached properly
□ Verify runner OS compatibility
```

### 15.3 Debug Workflows

```yaml
# .github/workflows/debug.yml

name: Debug Build

on:
  workflow_dispatch:

jobs:
  debug:
    runs-on: macos-latest
    steps:
      - uses: actions/checkout@v4

      - name: Debug Environment
        run: |
          echo "Flutter version:"
          flutter --version
          echo ""
          echo "Available simulators:"
          xcrun simctl list devices
          echo ""
          echo "Xcode version:"
          xcodebuild -version
          echo ""
          echo "Ruby version:"
          ruby --version

      - name: Debug iOS Setup
        run: |
          cd ios
          ls -la
          cat Podfile
          bundle exec pod env
```

---

## 16. Appendices

### Appendix A: Complete Fastfile (Android)

```ruby
# android/fastlane/Fastfile

default_platform(:android)

platform :android do
  before_all do
    ENV["SLACK_URL"] = ENV["SLACK_WEBHOOK_URL"]
  end

  desc "Run all tests"
  lane :test do
    gradle(task: "test")
  end

  desc "Build debug APK"
  lane :build_debug do
    gradle(
      task: "assemble",
      build_type: "Debug",
      flavor: "dev"
    )
  end

  desc "Build release APK"
  lane :build_release do |options|
    flavor = options[:flavor] || "production"
    gradle(
      task: "assemble",
      build_type: "Release",
      flavor: flavor
    )
  end

  desc "Build release AAB"
  lane :build_bundle do |options|
    flavor = options[:flavor] || "production"
    gradle(
      task: "bundle",
      build_type: "Release",
      flavor: flavor
    )
  end

  desc "Deploy to Firebase App Distribution"
  lane :deploy_firebase do |options|
    flavor = options[:flavor] || "staging"
    
    firebase_app_distribution(
      app: ENV["FIREBASE_ANDROID_APP_ID_#{flavor.upcase}"],
      firebase_cli_token: ENV["FIREBASE_TOKEN"],
      android_artifact_type: "APK",
      android_artifact_path: "../build/app/outputs/flutter-apk/app-#{flavor}-release.apk",
      groups: options[:groups] || "qa-team, developers",
      release_notes: options[:release_notes] || File.read("../RELEASE_NOTES.txt")
    )
    
    slack(
      message: "Successfully deployed Android #{flavor} to Firebase",
      success: true
    )
  end

  desc "Deploy to Play Store Internal"
  lane :deploy_internal do
    upload_to_play_store(
      track: "internal",
      aab: "../build/app/outputs/bundle/productionRelease/app-production-release.aab",
      skip_upload_metadata: false,
      skip_upload_images: false,
      skip_upload_screenshots: false
    )
  end

  desc "Deploy to Play Store Alpha"
  lane :deploy_alpha do
    upload_to_play_store(
      track: "alpha",
      aab: "../build/app/outputs/bundle/productionRelease/app-production-release.aab"
    )
  end

  desc "Deploy to Play Store Beta"
  lane :deploy_beta do
    upload_to_play_store(
      track: "beta",
      aab: "../build/app/outputs/bundle/productionRelease/app-production-release.aab"
    )
  end

  desc "Deploy to Play Store Production"
  lane :deploy_production do
    upload_to_play_store(
      track: "production",
      aab: "../build/app/outputs/bundle/productionRelease/app-production-release.aab",
      skip_upload_metadata: false,
      skip_upload_images: false,
      skip_upload_screenshots: false
    )
    
    slack(
      message: "Successfully deployed Android to Play Store Production",
      success: true
    )
  end

  desc "Promote Beta to Production"
  lane :promote_to_production do
    upload_to_play_store(
      track: "beta",
      track_promote_to: "production"
    )
  end

  desc "Rollback production to previous version"
  lane :rollback_production do
    version_codes = google_play_track_version_codes(track: "production")
    previous_version = version_codes[1]
    
    supply(
      track: "production",
      version_code: previous_version,
      skip_upload_apk: true,
      skip_upload_aab: true,
      skip_upload_metadata: true,
      skip_upload_images: true,
      skip_upload_screenshots: true
    )
    
    slack(
      message: "Rolled back Android production to version #{previous_version}",
      success: true
    )
  end

  after_all do |lane|
    # This block is called, only if the executed lane was successful
  end

  error do |lane, exception|
    slack(
      message: exception.message,
      success: false
    )
  end
end
```

### Appendix B: Complete Fastfile (iOS)

```ruby
# ios/fastlane/Fastfile

default_platform(:ios)

platform :ios do
  before_all do
    setup_ci if ENV['CI']
  end

  desc "Run tests"
  lane :test do
    scan(
      scheme: "Runner",
      devices: ["iPhone 14"],
      clean: true
    )
  end

  desc "Build staging"
  lane :build_staging do
    match(type: "appstore", readonly: true)
    
    build_ios_app(
      scheme: "Staging",
      configuration: "Release-Staging",
      export_method: "app-store",
      output_directory: "build/ios/ipa",
      output_name: "SmartDairy_Staging.ipa",
      xcargs: "-allowProvisioningUpdates"
    )
  end

  desc "Build production"
  lane :build_production do
    match(type: "appstore", readonly: true)
    
    increment_build_number(xcodeproj: "Runner.xcodeproj")
    
    build_ios_app(
      scheme: "Production",
      configuration: "Release-Production",
      export_method: "app-store",
      output_directory: "build/ios/ipa",
      output_name: "SmartDairy.ipa",
      xcargs: "-allowProvisioningUpdates"
    )
  end

  desc "Upload to TestFlight (Staging)"
  lane :upload_testflight_staging do
    upload_to_testflight(
      ipa: "build/ios/ipa/SmartDairy_Staging.ipa",
      skip_waiting_for_build_processing: true,
      notify_external_testers: false,
      changelog: generate_changelog
    )
    
    slack(
      message: "Successfully uploaded iOS Staging to TestFlight",
      success: true
    )
  end

  desc "Upload to TestFlight (Production)"
  lane :upload_testflight_prod do
    upload_to_testflight(
      ipa: "build/ios/ipa/SmartDairy.ipa",
      skip_waiting_for_build_processing: false,
      notify_external_testers: true,
      distribute_external: true,
      groups: ["QA Team", "Beta Testers"],
      changelog: generate_changelog
    )
    
    slack(
      message: "Successfully uploaded iOS Production to TestFlight",
      success: true
    )
  end

  desc "Deploy to App Store"
  lane :deploy_appstore do
    match(type: "appstore", readonly: true)
    
    build_ios_app(
      scheme: "Production",
      configuration: "Release-Production",
      export_method: "app-store"
    )
    
    upload_to_app_store(
      force: true,
      skip_metadata: false,
      skip_screenshots: false,
      submit_for_review: true,
      automatic_release: false,
      precheck_include_in_app_purchases: false
    )
    
    slack(
      message: "Successfully deployed iOS to App Store",
      success: true
    )
  end

  desc "Register new device"
  lane :register_device do
    device_name = prompt(text: "Device name: ")
    device_udid = prompt(text: "Device UDID: ")
    
    register_device(
      name: device_name,
      udid: device_udid
    )
    
    match(type: "development", force_for_new_devices: true)
    match(type: "adhoc", force_for_new_devices: true)
  end

  desc "Sync certificates"
  lane :sync_certs do
    match(type: "development", readonly: true)
    match(type: "adhoc", readonly: true)
    match(type: "appstore", readonly: true)
  end

  private

  def generate_changelog
    changelog_from_git_commits(
      between: ["HEAD~10", "HEAD"],
      pretty: "- %s",
      date_format: "short"
    )
  end

  after_all do |lane|
    # Called on success
  end

  error do |lane, exception|
    slack(
      message: exception.message,
      success: false
    )
  end
end
```

### Appendix C: Complete GitHub Actions Workflow Collection

#### C.1 Complete PR Checks Workflow

```yaml
# .github/workflows/pr-checks.yml

name: PR Checks

on:
  pull_request:
    branches: [develop, main]
    paths:
      - 'lib/**'
      - 'test/**'
      - 'pubspec.yaml'
      - 'pubspec.lock'
      - '.github/workflows/**'

concurrency:
  group: ${{ github.workflow }}-${{ github.event.pull_request.number }}
  cancel-in-progress: true

jobs:
  analyze:
    name: Static Analysis
    runs-on: ubuntu-latest
    timeout-minutes: 10
    steps:
      - uses: actions/checkout@v4
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true
      - run: flutter pub get
      - run: flutter analyze --fatal-infos --fatal-warnings
      - run: dart format --set-exit-if-changed lib test

  unit-tests:
    name: Unit Tests
    runs-on: ubuntu-latest
    timeout-minutes: 15
    needs: analyze
    steps:
      - uses: actions/checkout@v4
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true
      - run: flutter pub get
      - run: flutter test --coverage
      - uses: codecov/codecov-action@v3
        with:
          files: coverage/lcov.info
          fail_ci_if_error: true
```

#### C.2 Complete Deployment Workflow

```yaml
# .github/workflows/deploy.yml

name: Deploy

on:
  workflow_dispatch:
    inputs:
      environment:
        description: 'Environment'
        required: true
        default: 'staging'
        type: choice
        options:
          - staging
          - production

jobs:
  deploy:
    runs-on: ${{ matrix.os }}
    strategy:
      matrix:
        platform: [android, ios]
        include:
          - platform: android
            os: ubuntu-latest
          - platform: ios
            os: macos-latest
    environment: ${{ github.event.inputs.environment }}
    
    steps:
      - uses: actions/checkout@v4
      
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.16.0'
          channel: 'stable'
          cache: true
      
      - uses: ruby/setup-ruby@v1
        with:
          ruby-version: '3.2'
          bundler-cache: true
      
      - name: Build and Deploy
        run: |
          flutter pub get
          cd ${{ matrix.platform }}
          bundle exec fastlane deploy_${{ github.event.inputs.environment }}
        env:
          MATCH_PASSWORD: ${{ secrets.MATCH_PASSWORD }}
          FASTLANE_PASSWORD: ${{ secrets.FASTLANE_PASSWORD }}
```

### Appendix D: Environment Configuration Files

#### D.1 Android build.gradle Configuration

```gradle
// android/app/build.gradle

def localProperties = new Properties()
def localPropertiesFile = rootProject.file('local.properties')
if (localPropertiesFile.exists()) {
    localPropertiesFile.withReader('UTF-8') { reader ->
        localProperties.load(reader)
    }
}

def flutterVersionCode = localProperties.getProperty('flutter.versionCode')
if (flutterVersionCode == null) {
    flutterVersionCode = '1'
}

def flutterVersionName = localProperties.getProperty('flutter.versionName')
if (flutterVersionName == null) {
    flutterVersionName = '1.0'
}

android {
    namespace "com.smartdairy.mobile"
    compileSdkVersion flutter.compileSdkVersion
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
        applicationId "com.smartdairy.mobile"
        minSdkVersion 21
        targetSdkVersion flutter.targetSdkVersion
        versionCode flutterVersionCode.toInteger()
        versionName flutterVersionName
        multiDexEnabled true
    }

    signingConfigs {
        release {
            keyAlias localProperties.getProperty('keyAlias')
            keyPassword localProperties.getProperty('keyPassword')
            storeFile localProperties.getProperty('storeFile') ? file(localProperties.getProperty('storeFile')) : null
            storePassword localProperties.getProperty('storePassword')
        }
    }

    buildTypes {
        release {
            signingConfig signingConfigs.release
            minifyEnabled true
            shrinkResources true
            proguardFiles getDefaultProguardFile('proguard-android.txt'), 'proguard-rules.pro'
        }
    }

    flavorDimensions "env"

    productFlavors {
        dev {
            dimension "env"
            applicationIdSuffix ".dev"
            versionNameSuffix "-dev"
            resValue "string", "app_name", "Smart Dairy Dev"
        }
        staging {
            dimension "env"
            applicationIdSuffix ".staging"
            versionNameSuffix "-staging"
            resValue "string", "app_name", "Smart Dairy Staging"
        }
        production {
            dimension "env"
            resValue "string", "app_name", "Smart Dairy"
        }
    }
}

flutter {
    source '../..'
}

dependencies {
    implementation "org.jetbrains.kotlin:kotlin-stdlib-jdk7:$kotlin_version"
    implementation 'androidx.multidex:multidex:2.0.1'
}
```

#### D.2 iOS Xcode Configuration

```ruby
# ios/Runner/Info.plist snippets for environments

# Development
dev_plist = {
  "CFBundleDisplayName" => "Smart Dairy Dev",
  "CFBundleIdentifier" => "com.smartdairy.mobile.dev",
  "FirebaseAppDelegateProxyEnabled" => false
}

# Staging
staging_plist = {
  "CFBundleDisplayName" => "Smart Dairy Staging",
  "CFBundleIdentifier" => "com.smartdairy.mobile.staging"
}

# Production
prod_plist = {
  "CFBundleDisplayName" => "Smart Dairy",
  "CFBundleIdentifier" => "com.smartdairy.mobile"
}
```

### Appendix E: Required GitHub Secrets

```yaml
# GitHub Repository Secrets Checklist

# Android Signing
ANDROID_KEYSTORE_BASE64: "<base64-encoded-keystore>"
ANDROID_KEYSTORE_PASSWORD: "<keystore-password>"
ANDROID_KEY_PASSWORD: "<key-password>"
ANDROID_KEY_ALIAS: "<key-alias>"

# iOS Signing
MATCH_SSH_KEY: "<ssh-private-key-for-certificates-repo>"
MATCH_PASSWORD: "<match-encryption-passphrase>"
FASTLANE_PASSWORD: "<apple-id-password>"
APPLE_APP_SPECIFIC_PASSWORD: "<app-specific-password>"

# Firebase
FIREBASE_ANDROID_APP_ID_DEV: "1:xxx:android:xxx"
FIREBASE_ANDROID_APP_ID_STAGING: "1:xxx:android:xxx"
FIREBASE_ANDROID_APP_ID_PROD: "1:xxx:android:xxx"
FIREBASE_IOS_APP_ID_DEV: "1:xxx:ios:xxx"
FIREBASE_IOS_APP_ID_STAGING: "1:xxx:ios:xxx"
FIREBASE_IOS_APP_ID_PROD: "1:xxx:ios:xxx"
FIREBASE_TOKEN: "<firebase-cli-token>"
FIREBASE_SERVICE_ACCOUNT_KEY: '{"type":"service_account",...}'

# Google Play
PLAY_STORE_SERVICE_ACCOUNT_JSON: '{"type":"service_account",...}'

# Slack
SLACK_WEBHOOK_URL: "https://hooks.slack.com/services/..."

# API Keys
API_KEY_DEV: "<dev-api-key>"
API_KEY_STAGING: "<staging-api-key>"
API_KEY_PROD: "<prod-api-key>"

# Monitoring
METRICS_ENDPOINT: "https://metrics.smartdairy.co.ke/ci-cd"
```

### Appendix F: CI/CD Glossary

| Term | Definition |
|------|------------|
| CI | Continuous Integration - Automated building and testing of code |
| CD | Continuous Deployment - Automated deployment of code |
| APK | Android Package Kit - Android app distribution format |
| AAB | Android App Bundle - Optimized Android distribution format |
| IPA | iOS App Store Package - iOS app distribution format |
| Fastlane | Build automation tool for mobile apps |
| Match | Fastlane tool for secure certificate management |
| TestFlight | Apple's beta testing platform |
| Firebase App Distribution | Google's beta testing platform |
| Keystore | Android signing certificate storage |
| Provisioning Profile | iOS app signing configuration |
| Workflow | GitHub Actions automation definition |
| Secret | Encrypted environment variable |
| Environment | Deployment target (dev, staging, production) |
| Flavor | Build variant with different configurations |

---

## Document Control

### Related Documents

| Document ID | Title | Relationship |
|-------------|-------|--------------|
| H-010 | Mobile App Architecture | References build structure |
| H-011 | API Integration Guide | References environment configuration |
| H-012 | Testing Strategy | References test automation |
| H-013 | Security Implementation | References security practices |

### References

1. [Flutter Documentation](https://docs.flutter.dev/)
2. [Fastlane Documentation](https://docs.fastlane.tools/)
3. [GitHub Actions Documentation](https://docs.github.com/en/actions)
4. [Firebase App Distribution](https://firebase.google.com/docs/app-distribution)
5. [Apple App Store Connect](https://developer.apple.com/app-store-connect/)
6. [Google Play Console](https://play.google.com/console/)

---

**End of Document H-015**

---

*Document prepared by DevOps Team*
*Smart Dairy Ltd*
*January 31, 2026*
