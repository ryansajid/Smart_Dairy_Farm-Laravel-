# Document H-010: Mobile Analytics Implementation

## Smart Dairy Ltd. - Smart Web Portal System

---

**Document Information**

| Field | Value |
|-------|-------|
| **Document ID** | H-010 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Product Manager |
| **Classification** | Technical Implementation Document |
| **Status** | Draft |

---

## Document Control

### Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Mobile Lead | Initial document creation |

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Mobile Lead | [Name] | _____________ | _____________ |
| Product Manager | [Name] | _____________ | _____________ |
| CTO | [Name] | _____________ | _____________ |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Firebase Analytics Setup](#2-firebase-analytics-setup)
3. [Custom Events](#3-custom-events)
4. [User Properties](#4-user-properties)
5. [Screen Tracking](#5-screen-tracking)
6. [Funnel Analysis](#6-funnel-analysis)
7. [E-commerce Tracking](#7-e-commerce-tracking)
8. [User Engagement](#8-user-engagement)
9. [Crashlytics Integration](#9-crashlytics-integration)
10. [Performance Monitoring](#10-performance-monitoring)
11. [A/B Testing Setup](#11-ab-testing-setup)
12. [Privacy & GDPR](#12-privacy--gdpr)
13. [Dashboard Configuration](#13-dashboard-configuration)
14. [Testing Analytics](#14-testing-analytics)
15. [Appendices](#15-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidelines for implementing mobile analytics in the Smart Dairy mobile application. It covers the complete analytics infrastructure including event tracking, user properties, funnel analysis, and privacy compliance.

### 1.2 Scope

The analytics implementation covers:
- User behavior tracking across all app screens
- E-commerce and subscription event tracking
- Farm management activity monitoring
- Performance and crash reporting
- User engagement and retention metrics
- A/B testing infrastructure

### 1.3 Analytics Strategy

#### 1.3.1 Objectives

| Objective | Metric | Target |
|-----------|--------|--------|
| User Acquisition | Daily Active Users (DAU) | 10,000+ |
| User Retention | Day 7 Retention | 40%+ |
| Conversion | Purchase Completion Rate | 15%+ |
| Engagement | Average Session Duration | 5+ minutes |
| Performance | App Crash-Free Rate | 99.9%+ |

#### 1.3.2 Key Performance Indicators (KPIs)

```
┌─────────────────────────────────────────────────────────────┐
│                    SMART DAIRY KPIs                         │
├─────────────────────────────────────────────────────────────┤
│  Acquisition        │  Organic installs, Cost per install   │
│  Activation         │  Registration completion rate         │
│  Retention          │  D1, D7, D30 retention rates          │
│  Revenue            │  ARPU, ARPPU, LTV                     │
│  Referral           │  Invite conversion rate               │
│  Engagement         │  Sessions per user, Session length    │
└─────────────────────────────────────────────────────────────┘
```

### 1.4 Privacy Compliance

#### 1.4.1 Regulatory Requirements

| Regulation | Requirements | Implementation |
|------------|--------------|----------------|
| GDPR | Consent, Data minimization, Right to erasure | Consent banner, Data retention policies |
| CCPA | Disclosure, Opt-out rights | Privacy policy, Opt-out mechanism |
| PIPEDA | Consent for collection | Explicit consent for analytics |
| LGPD | Purpose limitation | Clear purpose statement |

#### 1.4.2 Data Collection Principles

1. **Data Minimization**: Collect only necessary data
2. **Purpose Limitation**: Use data only for stated purposes
3. **Storage Limitation**: Retain data only as long as needed
4. **Accuracy**: Maintain accurate and up-to-date information
5. **Security**: Implement appropriate security measures

---

## 2. Firebase Analytics Setup

### 2.1 Prerequisites

```yaml
Requirements:
  - Firebase project created
  - Google Analytics 4 property linked
  - Mobile apps registered (iOS/Android)
  - google-services.json / GoogleService-Info.plist configured
  - Minimum SDK versions:
      Android: API 21 (Android 5.0)
      iOS: iOS 12.0
```

### 2.2 Configuration

#### 2.2.1 Android Configuration

**build.gradle (Project)**

```gradle
buildscript {
    dependencies {
        classpath 'com.google.gms:google-services:4.4.0'
        classpath 'com.google.firebase:firebase-crashlytics-gradle:2.9.9'
        classpath 'com.google.firebase:perf-plugin:1.4.2'
    }
}
```

**build.gradle (App Module)**

```gradle
plugins {
    id 'com.android.application'
    id 'com.google.gms.google-services'
    id 'com.google.firebase.crashlytics'
    id 'com.google.firebase.firebase-perf'
}

dependencies {
    // Firebase BoM
    implementation platform('com.google.firebase:firebase-bom:32.7.0')
    
    // Analytics
    implementation 'com.google.firebase:firebase-analytics'
    
    // Crashlytics
    implementation 'com.google.firebase:firebase-crashlytics'
    
    // Performance Monitoring
    implementation 'com.google.firebase:firebase-perf'
    
    // Remote Config (for A/B Testing)
    implementation 'com.google.firebase:firebase-config'
}
```

#### 2.2.2 iOS Configuration

**Podfile**

```ruby
platform :ios, '12.0'

target 'SmartDairy' do
  use_frameworks!
  
  # Firebase
  pod 'FirebaseAnalytics'
  pod 'FirebaseCrashlytics'
  pod 'FirebasePerformance'
  pod 'FirebaseRemoteConfig'
end

post_install do |installer|
  installer.pods_project.targets.each do |target|
    target.build_configurations.each do |config|
      config.build_settings['IPHONEOS_DEPLOYMENT_TARGET'] = '12.0'
    end
  end
end
```

### 2.3 Initialization

#### 2.3.1 Android Application Class

```kotlin
package com.smartdairy.app

import android.app.Application
import com.google.firebase.FirebaseApp
import com.google.firebase.analytics.FirebaseAnalytics
import com.google.firebase.analytics.ktx.analytics
import com.google.firebase.crashlytics.FirebaseCrashlytics
import com.google.firebase.ktx.Firebase
import com.google.firebase.perf.FirebasePerformance

class SmartDairyApplication : Application() {
    
    companion object {
        lateinit var analytics: FirebaseAnalytics
            private set
    }
    
    override fun onCreate() {
        super.onCreate()
        
        // Initialize Firebase
        FirebaseApp.initializeApp(this)
        
        // Initialize Analytics
        analytics = Firebase.analytics
        
        // Configure Analytics
        configureAnalytics()
        
        // Initialize Crashlytics
        initializeCrashlytics()
        
        // Initialize Performance Monitoring
        initializePerformanceMonitoring()
    }
    
    private fun configureAnalytics() {
        analytics.apply {
            // Set analytics collection enabled
            setAnalyticsCollectionEnabled(true)
            
            // Set session timeout duration (30 minutes)
            setSessionTimeoutDuration(1800000)
            
            // Set user consent status (check PrivacyManager)
            setConsent(
                mapOf(
                    FirebaseAnalytics.ConsentType.ANALYTICS_STORAGE to 
                        FirebaseAnalytics.ConsentStatus.GRANTED,
                    FirebaseAnalytics.ConsentType.AD_STORAGE to 
                        FirebaseAnalytics.ConsentStatus.DENIED
                )
            )
        }
    }
    
    private fun initializeCrashlytics() {
        FirebaseCrashlytics.getInstance().apply {
            setCustomKey("app_version", BuildConfig.VERSION_NAME)
            setCustomKey("build_number", BuildConfig.VERSION_CODE)
            isCrashlyticsCollectionEnabled = !BuildConfig.DEBUG
        }
    }
    
    private fun initializePerformanceMonitoring() {
        FirebasePerformance.getInstance().apply {
            isPerformanceCollectionEnabled = !BuildConfig.DEBUG
        }
    }
}
```

#### 2.3.2 iOS AppDelegate

```swift
import UIKit
import FirebaseCore
import FirebaseAnalytics
import FirebaseCrashlytics
import FirebasePerformance

@main
class AppDelegate: UIResponder, UIApplicationDelegate {
    
    func application(
        _ application: UIApplication,
        didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?
    ) -> Bool {
        // Initialize Firebase
        FirebaseApp.configure()
        
        // Configure Analytics
        configureAnalytics()
        
        // Initialize Crashlytics
        initializeCrashlytics()
        
        // Initialize Performance Monitoring
        initializePerformanceMonitoring()
        
        return true
    }
    
    private func configureAnalytics() {
        // Set analytics collection enabled
        Analytics.setAnalyticsCollectionEnabled(true)
        
        // Set user consent
        let consentSettings: [AnalyticsConsentType: AnalyticsConsentStatus] = [
            .analyticsStorage: .granted,
            .adStorage: .denied
        ]
        Analytics.setConsent(consentSettings)
    }
    
    private func initializeCrashlytics() {
        Crashlytics.crashlytics().setCustomValue(Bundle.main.infoDictionary?["CFBundleShortVersionString"] as? String ?? "unknown", 
                                                  forKey: "app_version")
        Crashlytics.crashlytics().setCustomValue(Bundle.main.infoDictionary?["CFBundleVersion"] as? String ?? "unknown", 
                                                  forKey: "build_number")
    }
    
    private func initializePerformanceMonitoring() {
        // Performance monitoring auto-starts with Firebase configuration
    }
}
```

---

## 3. Custom Events

### 3.1 Event Taxonomy

#### 3.1.1 Event Naming Convention

```
Format: {category}_{action}

Rules:
  - Use lowercase letters only
  - Use underscores for spaces
  - Maximum 40 characters for event name
  - Maximum 25 parameters per event
  - Maximum 100 characters for parameter names
  - Maximum 100 characters for string parameter values
```

### 3.2 User Action Events

#### 3.2.1 Authentication Events

| Event Name | Trigger | Parameters |
|------------|---------|------------|
| `login_success` | User successfully logs in | method, user_type |
| `login_failed` | Login attempt fails | method, error_code, reason |
| `register_success` | New user registration | method, user_type |
| `register_failed` | Registration fails | method, error_code, reason |
| `logout` | User logs out | session_duration |
| `password_reset` | Password reset requested | method |

```kotlin
// Event Constants - Authentication
object AuthEvents {
    const val LOGIN_SUCCESS = "login_success"
    const val LOGIN_FAILED = "login_failed"
    const val REGISTER_SUCCESS = "register_success"
    const val REGISTER_FAILED = "register_failed"
    const val LOGOUT = "logout"
    const val PASSWORD_RESET = "password_reset"
    
    object Params {
        const val METHOD = "method"
        const val USER_TYPE = "user_type"
        const val ERROR_CODE = "error_code"
        const val REASON = "reason"
        const val SESSION_DURATION = "session_duration"
    }
    
    object Methods {
        const val EMAIL = "email"
        const val PHONE = "phone"
        const val GOOGLE = "google"
        const val FACEBOOK = "facebook"
        const val APPLE = "apple"
        const val BIOMETRIC = "biometric"
    }
    
    object UserTypes {
        const val FARMER = "farmer"
        const val BUYER = "buyer"
        const val ADMIN = "admin"
        const val DELIVERY = "delivery"
    }
}

// Implementation Example
class AuthAnalytics {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    
    fun logLoginSuccess(method: String, userType: String) {
        analytics.logEvent(AuthEvents.LOGIN_SUCCESS) {
            param(AuthEvents.Params.METHOD, method)
            param(AuthEvents.Params.USER_TYPE, userType)
        }
    }
    
    fun logLoginFailed(method: String, errorCode: String, reason: String) {
        analytics.logEvent(AuthEvents.LOGIN_FAILED) {
            param(AuthEvents.Params.METHOD, method)
            param(AuthEvents.Params.ERROR_CODE, errorCode)
            param(AuthEvents.Params.REASON, reason)
        }
    }
    
    fun logRegisterSuccess(method: String, userType: String) {
        analytics.logEvent(AuthEvents.REGISTER_SUCCESS) {
            param(AuthEvents.Params.METHOD, method)
            param(AuthEvents.Params.USER_TYPE, userType)
        }
    }
    
    fun logLogout(sessionDurationMinutes: Long) {
        analytics.logEvent(AuthEvents.LOGOUT) {
            param(AuthEvents.Params.SESSION_DURATION, sessionDurationMinutes)
        }
    }
}
```

### 3.3 E-commerce Events

#### 3.3.1 Product Events

| Event Name | Trigger | Parameters |
|------------|---------|------------|
| `view_product` | User views product details | item_id, item_name, item_category, price |
| `product_added_to_cart` | Item added to cart | item_id, quantity, price |
| `product_removed_from_cart` | Item removed from cart | item_id, quantity |
| `view_cart` | Cart screen viewed | item_count, total_value |
| `begin_checkout` | Checkout initiated | item_count, total_value, currency |
| `checkout_progress` | Checkout step completed | step, step_name |
| `checkout_completed` | Purchase successful | transaction_id, value, currency |
| `checkout_cancelled` | Checkout abandoned | step, reason |

```kotlin
// Event Constants - E-commerce
object EcommerceEvents {
    const val VIEW_PRODUCT = "view_product"
    const val PRODUCT_ADDED_TO_CART = "product_added_to_cart"
    const val PRODUCT_REMOVED_FROM_CART = "product_removed_from_cart"
    const val VIEW_CART = "view_cart"
    const val BEGIN_CHECKOUT = "begin_checkout"
    const val CHECKOUT_PROGRESS = "checkout_progress"
    const val CHECKOUT_COMPLETED = "checkout_completed"
    const val CHECKOUT_CANCELLED = "checkout_cancelled"
    const val PURCHASE_REFUNDED = "purchase_refunded"
    
    object Params {
        // Item parameters
        const val ITEM_ID = "item_id"
        const val ITEM_NAME = "item_name"
        const val ITEM_CATEGORY = "item_category"
        const val ITEM_BRAND = "item_brand"
        const val PRICE = "price"
        const val QUANTITY = "quantity"
        const val ITEM_VARIANT = "item_variant"
        
        // Transaction parameters
        const val TRANSACTION_ID = "transaction_id"
        const val VALUE = "value"
        const val CURRENCY = "currency"
        const val TAX = "tax"
        const val SHIPPING = "shipping"
        const val COUPON = "coupon"
        
        // Cart parameters
        const val ITEM_COUNT = "item_count"
        const val TOTAL_VALUE = "total_value"
        
        // Checkout parameters
        const val STEP = "step"
        const val STEP_NAME = "step_name"
        const val REASON = "reason"
    }
    
    object Categories {
        const val FRESH_MILK = "fresh_milk"
        const val FERMENTED = "fermented"
        const val CHEESE = "cheese"
        const val BUTTER = "butter"
        const val ICE_CREAM = "ice_cream"
        const val YOGURT = "yogurt"
        const val SUPPLEMENTS = "supplements"
    }
    
    object CheckoutSteps {
        const val ADDRESS = 1
        const val DELIVERY = 2
        const val PAYMENT = 3
        const val REVIEW = 4
        const val CONFIRMATION = 5
    }
}

// Implementation Example
class EcommerceAnalytics {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    
    fun logViewProduct(product: Product) {
        analytics.logEvent(EcommerceEvents.VIEW_PRODUCT) {
            param(FirebaseAnalytics.Param.ITEMS, arrayOf(bundleOf(
                FirebaseAnalytics.Param.ITEM_ID to product.id,
                FirebaseAnalytics.Param.ITEM_NAME to product.name,
                FirebaseAnalytics.Param.ITEM_CATEGORY to product.category,
                FirebaseAnalytics.Param.ITEM_BRAND to product.brand,
                FirebaseAnalytics.Param.PRICE to product.price
            )))
            param(FirebaseAnalytics.Param.CURRENCY, "KES")
        }
    }
    
    fun logAddToCart(product: Product, quantity: Int) {
        analytics.logEvent(FirebaseAnalytics.Event.ADD_TO_CART) {
            param(FirebaseAnalytics.Param.ITEMS, arrayOf(bundleOf(
                FirebaseAnalytics.Param.ITEM_ID to product.id,
                FirebaseAnalytics.Param.ITEM_NAME to product.name,
                FirebaseAnalytics.Param.ITEM_CATEGORY to product.category,
                FirebaseAnalytics.Param.PRICE to product.price,
                FirebaseAnalytics.Param.QUANTITY to quantity
            )))
            param(FirebaseAnalytics.Param.CURRENCY, "KES")
            param(FirebaseAnalytics.Param.VALUE, product.price * quantity)
        }
    }
    
    fun logBeginCheckout(cart: Cart) {
        analytics.logEvent(FirebaseAnalytics.Event.BEGIN_CHECKOUT) {
            param(FirebaseAnalytics.Param.ITEMS, cart.items.map { item ->
                bundleOf(
                    FirebaseAnalytics.Param.ITEM_ID to item.productId,
                    FirebaseAnalytics.Param.ITEM_NAME to item.productName,
                    FirebaseAnalytics.Param.QUANTITY to item.quantity,
                    FirebaseAnalytics.Param.PRICE to item.unitPrice
                )
            }.toTypedArray())
            param(FirebaseAnalytics.Param.CURRENCY, "KES")
            param(FirebaseAnalytics.Param.VALUE, cart.totalValue)
        }
    }
    
    fun logPurchase(order: Order) {
        analytics.logEvent(FirebaseAnalytics.Event.PURCHASE) {
            param(FirebaseAnalytics.Param.TRANSACTION_ID, order.id)
            param(FirebaseAnalytics.Param.VALUE, order.totalAmount)
            param(FirebaseAnalytics.Param.CURRENCY, "KES")
            param(FirebaseAnalytics.Param.TAX, order.taxAmount)
            param(FirebaseAnalytics.Param.SHIPPING, order.shippingAmount)
            param(FirebaseAnalytics.Param.COUPON, order.couponCode ?: "")
            param(FirebaseAnalytics.Param.ITEMS, order.items.map { item ->
                bundleOf(
                    FirebaseAnalytics.Param.ITEM_ID to item.productId,
                    FirebaseAnalytics.Param.ITEM_NAME to item.productName,
                    FirebaseAnalytics.Param.ITEM_CATEGORY to item.category,
                    FirebaseAnalytics.Param.QUANTITY to item.quantity,
                    FirebaseAnalytics.Param.PRICE to item.unitPrice
                )
            }.toTypedArray())
        }
    }
}
```

### 3.4 Farm Management Events

#### 3.4.1 Core Farm Events

| Event Name | Trigger | Parameters |
|------------|---------|------------|
| `milk_recorded` | Milk production recorded | quantity, animal_id, session_id |
| `animal_registered` | New animal added | animal_type, breed, age |
| `animal_updated` | Animal info modified | animal_id, field_changed |
| `health_check_recorded` | Health check completed | animal_id, check_type, result |
| `vaccination_recorded` | Vaccination logged | animal_id, vaccine_type |
| `breeding_recorded` | Breeding event logged | animal_id, method |
| `farm_visit_scheduled` | Farm visit booked | visit_type, preferred_date |

```kotlin
// Event Constants - Farm Management
object FarmEvents {
    const val MILK_RECORDED = "milk_recorded"
    const val ANIMAL_REGISTERED = "animal_registered"
    const val ANIMAL_UPDATED = "animal_updated"
    const val ANIMAL_REMOVED = "animal_removed"
    const val HEALTH_CHECK_RECORDED = "health_check_recorded"
    const val VACCINATION_RECORDED = "vaccination_recorded"
    const val BREEDING_RECORDED = "breeding_recorded"
    const val FARM_VISIT_SCHEDULED = "farm_visit_scheduled"
    
    object Params {
        const val ANIMAL_ID = "animal_id"
        const val ANIMAL_TYPE = "animal_type"
        const val BREED = "breed"
        const val AGE = "age"
        const val QUANTITY = "quantity"
        const val MILKING_SESSION = "milking_session"
        const val SESSION_ID = "session_id"
        const val CHECK_TYPE = "check_type"
        const val RESULT = "result"
        const val VACCINE_TYPE = "vaccine_type"
        const val BREEDING_METHOD = "breeding_method"
        const val VISIT_TYPE = "visit_type"
        const val PREFERRED_DATE = "preferred_date"
        const val FIELD_CHANGED = "field_changed"
    }
    
    object AnimalTypes {
        const val COW = "cow"
        const val GOAT = "goat"
        const val SHEEP = "sheep"
        const val BUFFALO = "buffalo"
        const val CAMEL = "camel"
    }
    
    object MilkingSessions {
        const val MORNING = "morning"
        const val AFTERNOON = "afternoon"
        const val EVENING = "evening"
    }
    
    object CheckTypes {
        const val ROUTINE = "routine"
        const val ILLNESS = "illness"
        const val PREGNANCY = "pregnancy"
        const val POST_NATAL = "post_natal"
    }
}

// Implementation Example
class FarmAnalytics {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    
    fun logMilkRecorded(quantity: Double, animalId: String, session: String) {
        analytics.logEvent(FarmEvents.MILK_RECORDED) {
            param(FarmEvents.Params.QUANTITY, quantity)
            param(FarmEvents.Params.ANIMAL_ID, animalId)
            param(FarmEvents.Params.MILKING_SESSION, session)
        }
    }
    
    fun logAnimalRegistered(animalType: String, breed: String, age: Int) {
        analytics.logEvent(FarmEvents.ANIMAL_REGISTERED) {
            param(FarmEvents.Params.ANIMAL_TYPE, animalType)
            param(FarmEvents.Params.BREED, breed)
            param(FarmEvents.Params.AGE, age.toLong())
        }
    }
    
    fun logHealthCheckRecorded(animalId: String, checkType: String, result: String) {
        analytics.logEvent(FarmEvents.HEALTH_CHECK_RECORDED) {
            param(FarmEvents.Params.ANIMAL_ID, animalId)
            param(FarmEvents.Params.CHECK_TYPE, checkType)
            param(FarmEvents.Params.RESULT, result)
        }
    }
}
```

### 3.5 Order Events

#### 3.5.1 Order Lifecycle Events

| Event Name | Trigger | Parameters |
|------------|---------|------------|
| `order_placed` | New order created | order_id, order_value, item_count |
| `order_cancelled` | Order cancelled | order_id, reason, stage |
| `order_status_updated` | Status change | order_id, old_status, new_status |
| `order_delivered` | Delivery confirmed | order_id, delivery_time |
| `order_rated` | User rates order | order_id, rating, has_comment |

```kotlin
// Event Constants - Orders
object OrderEvents {
    const val ORDER_PLACED = "order_placed"
    const val ORDER_CANCELLED = "order_cancelled"
    const val ORDER_STATUS_UPDATED = "order_status_updated"
    const val ORDER_DELIVERED = "order_delivered"
    const val ORDER_RATED = "order_rated"
    
    object Params {
        const val ORDER_ID = "order_id"
        const val ORDER_VALUE = "order_value"
        const val ITEM_COUNT = "item_count"
        const val REASON = "reason"
        const val STAGE = "stage"
        const val OLD_STATUS = "old_status"
        const val NEW_STATUS = "new_status"
        const val DELIVERY_TIME = "delivery_time"
        const val RATING = "rating"
        const val HAS_COMMENT = "has_comment"
    }
    
    object OrderStatuses {
        const val PENDING = "pending"
        const val CONFIRMED = "confirmed"
        const val PROCESSING = "processing"
        const val SHIPPED = "shipped"
        const val DELIVERED = "delivered"
        const val CANCELLED = "cancelled"
    }
}
```

### 3.6 Subscription Events

#### 3.6.1 Subscription Lifecycle

| Event Name | Trigger | Parameters |
|------------|---------|------------|
| `subscription_created` | New subscription | plan_type, duration, amount |
| `subscription_modified` | Plan changed | old_plan, new_plan, reason |
| `subscription_cancelled` | Subscription ended | plan_type, reason, tenure_days |
| `subscription_renewed` | Auto-renewal | plan_type, amount |
| `payment_successful` | Payment processed | amount, method, subscription_id |
| `payment_failed` | Payment failed | amount, method, error_code |

```kotlin
// Event Constants - Subscriptions
object SubscriptionEvents {
    const val SUBSCRIPTION_CREATED = "subscription_created"
    const val SUBSCRIPTION_MODIFIED = "subscription_modified"
    const val SUBSCRIPTION_CANCELLED = "subscription_cancelled"
    const val SUBSCRIPTION_RENEWED = "subscription_renewed"
    const val PAYMENT_SUCCESSFUL = "payment_successful"
    const val PAYMENT_FAILED = "payment_failed"
    
    object Params {
        const val PLAN_TYPE = "plan_type"
        const val DURATION = "duration"
        const val AMOUNT = "amount"
        const val OLD_PLAN = "old_plan"
        const val NEW_PLAN = "new_plan"
        const val REASON = "reason"
        const val TENURE_DAYS = "tenure_days"
        const val METHOD = "method"
        const val SUBSCRIPTION_ID = "subscription_id"
        const val ERROR_CODE = "error_code"
    }
    
    object PlanTypes {
        const val BASIC = "basic"
        const val PREMIUM = "premium"
        const val ENTERPRISE = "enterprise"
        const val FARMER_PRO = "farmer_pro"
    }
    
    object PaymentMethods {
        const val MPESA = "mpesa"
        const val CARD = "card"
        const val BANK_TRANSFER = "bank_transfer"
        const val CASH = "cash"
    }
}
```

### 3.7 App Lifecycle Events

| Event Name | Trigger | Parameters |
|------------|---------|------------|
| `app_open` | App launched | launch_source, cold_start |
| `app_background` | App minimized | session_duration |
| `app_foreground` | App resumed | time_in_background |
| `search_performed` | Search executed | query, result_count, category |
| `filter_applied` | Filter used | filter_type, filter_value |
| `notification_received` | Push received | notification_type |
| `notification_opened` | Push tapped | notification_type |

---

## 4. User Properties

### 4.1 Demographic Properties

| Property Name | Type | Description | Values |
|---------------|------|-------------|--------|
| `user_type` | String | Type of user | farmer, buyer, admin, delivery |
| `age_group` | String | Age range | 18-24, 25-34, 35-44, 45-54, 55+ |
| `gender` | String | User gender | male, female, other, prefer_not_to_say |
| `location_region` | String | Geographic region | Central, Eastern, Western, etc. |
| `language` | String | Preferred language | en, sw, fr |

### 4.2 Business Properties

| Property Name | Type | Description |
|---------------|------|-------------|
| `farm_size` | String | Small, Medium, Large, Enterprise |
| `herd_size` | Number | Number of animals |
| `subscription_tier` | String | Basic, Premium, Enterprise |
| `account_age_days` | Number | Days since registration |
| `total_orders` | Number | Lifetime order count |
| `lifetime_value` | Number | Total revenue generated |

### 4.3 Behavioral Properties

| Property Name | Type | Description |
|---------------|------|-------------|
| `preferred_category` | String | Most purchased category |
| `avg_order_value` | Number | Average order amount |
| `last_active_date` | String | Date of last activity |
| `engagement_level` | String | Low, Medium, High, Power |
| `device_type` | String | Phone, Tablet |

### 4.4 Implementation

```kotlin
// User Properties Implementation
class UserPropertiesManager {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    
    // Set user ID (hashed for privacy)
    fun setUserId(userId: String) {
        val hashedId = hashUserId(userId)
        analytics.setUserId(hashedId)
    }
    
    // Set user type property
    fun setUserType(userType: String) {
        analytics.setUserProperty("user_type", userType)
    }
    
    // Set farm size property
    fun setFarmSize(farmSize: String) {
        analytics.setUserProperty("farm_size", farmSize)
    }
    
    // Set subscription tier
    fun setSubscriptionTier(tier: String) {
        analytics.setUserProperty("subscription_tier", tier)
    }
    
    // Set engagement level based on usage patterns
    fun setEngagementLevel(level: String) {
        analytics.setUserProperty("engagement_level", level)
    }
    
    // Set all properties at once
    fun setAllUserProperties(user: UserProfile) {
        analytics.apply {
            setUserProperty("user_type", user.userType)
            setUserProperty("farm_size", user.farmSize)
            setUserProperty("herd_size", user.herdSize.toString())
            setUserProperty("subscription_tier", user.subscriptionTier)
            setUserProperty("location_region", user.region)
            setUserProperty("preferred_category", user.preferredCategory)
            setUserProperty("engagement_level", calculateEngagementLevel(user))
        }
    }
    
    private fun calculateEngagementLevel(user: UserProfile): String {
        return when {
            user.sessionsPerWeek >= 5 && user.avgSessionDuration >= 10 -> "Power"
            user.sessionsPerWeek >= 3 && user.avgSessionDuration >= 5 -> "High"
            user.sessionsPerWeek >= 1 -> "Medium"
            else -> "Low"
        }
    }
    
    private fun hashUserId(userId: String): String {
        return MessageDigest.getInstance("SHA-256")
            .digest(userId.toByteArray())
            .joinToString("") { "%02x".format(it) }
    }
}
```

---

## 5. Screen Tracking

### 5.1 Automatic Screen Tracking

#### 5.1.1 Android Navigation Component

```kotlin
// Automatic screen tracking with Navigation Component
class AnalyticsNavigationListener : NavController.OnDestinationChangedListener {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    
    override fun onDestinationChanged(
        controller: NavController,
        destination: NavDestination,
        arguments: Bundle?
    ) {
        val screenName = destination.label?.toString() ?: destination.route ?: "unknown"
        val screenClass = destination.javaClass.simpleName
        
        analytics.logEvent(FirebaseAnalytics.Event.SCREEN_VIEW) {
            param(FirebaseAnalytics.Param.SCREEN_NAME, screenName)
            param(FirebaseAnalytics.Param.SCREEN_CLASS, screenClass)
        }
        
        // Log breadcrumb for Crashlytics
        FirebaseCrashlytics.getInstance().log("Screen viewed: $screenName")
    }
}

// Setup in MainActivity
class MainActivity : AppCompatActivity() {
    
    private lateinit var navController: NavController
    private val navigationListener = AnalyticsNavigationListener()
    
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        
        navController = findNavController(R.id.nav_host_fragment)
        navController.addOnDestinationChangedListener(navigationListener)
    }
    
    override fun onDestroy() {
        super.onDestroy()
        navController.removeOnDestinationChangedListener(navigationListener)
    }
}
```

#### 5.1.2 iOS SwiftUI

```swift
import SwiftUI
import FirebaseAnalytics

// View modifier for automatic screen tracking
struct AnalyticsTracker: ViewModifier {
    let screenName: String
    let screenClass: String
    
    func body(content: Content) -> some View {
        content
            .onAppear {
                Analytics.logEvent(AnalyticsEventScreenView, parameters: [
                    AnalyticsParameterScreenName: screenName,
                    AnalyticsParameterScreenClass: screenClass
                ])
            }
    }
}

extension View {
    func trackScreen(name: String, class screenClass: String = "UIViewController") -> some View {
        modifier(AnalyticsTracker(screenName: name, screenClass: screenClass))
    }
}

// Usage in SwiftUI views
struct HomeView: View {
    var body: some View {
        VStack {
            // View content
        }
        .trackScreen(name: "home_screen", class: "HomeView")
    }
}
```

### 5.2 Manual Screen Tracking

```kotlin
// Manual screen tracking for custom views/dialogs
class ScreenTracker {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    private var screenStartTime: Long = 0
    private var currentScreen: String = ""
    
    fun trackScreenView(screenName: String, screenClass: String) {
        // Track time on previous screen
        if (currentScreen.isNotEmpty() && screenStartTime > 0) {
            val duration = System.currentTimeMillis() - screenStartTime
            analytics.logEvent("screen_time") {
                param("screen_name", currentScreen)
                param("duration_ms", duration)
            }
        }
        
        // Track new screen
        analytics.logEvent(FirebaseAnalytics.Event.SCREEN_VIEW) {
            param(FirebaseAnalytics.Param.SCREEN_NAME, screenName)
            param(FirebaseAnalytics.Param.SCREEN_CLASS, screenClass)
        }
        
        currentScreen = screenName
        screenStartTime = System.currentTimeMillis()
        
        // Log breadcrumb
        FirebaseCrashlytics.getInstance().log("Screen viewed: $screenName")
    }
    
    fun trackModalView(modalName: String) {
        analytics.logEvent("modal_viewed") {
            param("modal_name", modalName)
        }
    }
    
    fun trackBottomSheetView(sheetName: String) {
        analytics.logEvent("bottom_sheet_viewed") {
            param("sheet_name", sheetName)
        }
    }
}
```

---

## 6. Funnel Analysis

### 6.1 Purchase Funnel

```
Purchase Funnel Stages:

┌─────────────────┐
│  View Product   │ ← 100% (Baseline)
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Add to Cart    │ ← Target: 30% conversion
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Begin Checkout  │ ← Target: 60% conversion
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Complete Info   │ ← Target: 80% conversion
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Purchase       │ ← Target: 90% conversion
└─────────────────┘
```

### 6.2 Onboarding Funnel

```kotlin
// Onboarding funnel events
object OnboardingFunnel {
    const val STEP_1_WELCOME = "onboarding_welcome"
    const val STEP_2_USER_TYPE = "onboarding_user_type"
    const val STEP_3_PROFILE = "onboarding_profile"
    const val STEP_4_FARM_INFO = "onboarding_farm_info"
    const val STEP_5_PREFERENCES = "onboarding_preferences"
    const val STEP_6_COMPLETE = "onboarding_complete"
    const val STEP_ABANDONED = "onboarding_abandoned"
    
    object Params {
        const val STEP_NUMBER = "step_number"
        const val TOTAL_STEPS = "total_steps"
        const val COMPLETION_RATE = "completion_rate"
        const val TIME_SPENT = "time_spent_seconds"
    }
}

class OnboardingAnalytics {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    private var funnelStartTime: Long = 0
    private var currentStep: Int = 0
    
    fun startOnboarding() {
        funnelStartTime = System.currentTimeMillis()
        currentStep = 1
    }
    
    fun logStepViewed(stepNumber: Int, stepName: String) {
        currentStep = stepNumber
        analytics.logEvent(stepName) {
            param(OnboardingFunnel.Params.STEP_NUMBER, stepNumber.toLong())
            param(OnboardingFunnel.Params.TOTAL_STEPS, 5)
        }
    }
    
    fun logStepCompleted(stepNumber: Int) {
        val completionRate = (stepNumber / 5.0 * 100).toInt()
        analytics.logEvent("onboarding_step_completed") {
            param(OnboardingFunnel.Params.STEP_NUMBER, stepNumber.toLong())
            param(OnboardingFunnel.Params.COMPLETION_RATE, completionRate.toLong())
        }
    }
    
    fun logOnboardingComplete() {
        val totalTime = (System.currentTimeMillis() - funnelStartTime) / 1000
        analytics.logEvent(OnboardingFunnel.STEP_6_COMPLETE) {
            param(OnboardingFunnel.Params.TIME_SPENT, totalTime)
        }
    }
    
    fun logOnboardingAbandoned(atStep: Int) {
        val timeSpent = (System.currentTimeMillis() - funnelStartTime) / 1000
        analytics.logEvent(OnboardingFunnel.STEP_ABANDONED) {
            param(OnboardingFunnel.Params.STEP_NUMBER, atStep.toLong())
            param(OnboardingFunnel.Params.TIME_SPENT, timeSpent)
        }
    }
}
```

### 6.3 Subscription Funnel

| Stage | Event | Target Conversion |
|-------|-------|-------------------|
| View Plans | `view_subscription_plans` | 100% |
| Select Plan | `select_subscription_plan` | 25% |
| Enter Payment | `begin_subscription_checkout` | 70% |
| Confirm Payment | `subscription_payment_initiated` | 90% |
| Success | `subscription_created` | 95% |

---

## 7. E-commerce Tracking

### 7.1 Product Impressions

```kotlin
// Track product impressions in lists
fun logProductImpression(products: List<Product>, listName: String) {
    analytics.logEvent(FirebaseAnalytics.Event.VIEW_ITEM_LIST) {
        param(FirebaseAnalytics.Param.ITEM_LIST_NAME, listName)
        param(FirebaseAnalytics.Param.ITEMS, products.map { product ->
            bundleOf(
                FirebaseAnalytics.Param.ITEM_ID to product.id,
                FirebaseAnalytics.Param.ITEM_NAME to product.name,
                FirebaseAnalytics.Param.ITEM_CATEGORY to product.category,
                FirebaseAnalytics.Param.ITEM_VARIANT to product.variant,
                FirebaseAnalytics.Param.PRICE to product.price,
                FirebaseAnalytics.Param.ITEM_LIST_NAME to listName,
                FirebaseAnalytics.Param.INDEX to products.indexOf(product)
            )
        }.toTypedArray())
    }
}
```

### 7.2 Promotion Tracking

```kotlin
// Track promotion views and clicks
object PromotionEvents {
    
    fun logPromotionView(promotion: Promotion) {
        analytics.logEvent(FirebaseAnalytics.Event.VIEW_PROMOTION) {
            param(FirebaseAnalytics.Param.PROMOTION_ID, promotion.id)
            param(FirebaseAnalytics.Param.PROMOTION_NAME, promotion.name)
            param(FirebaseAnalytics.Param.CREATIVE_NAME, promotion.creative)
            param(FirebaseAnalytics.Param.CREATIVE_SLOT, promotion.position)
            param(FirebaseAnalytics.Param.LOCATION_ID, promotion.screen)
        }
    }
    
    fun logPromotionClick(promotion: Promotion) {
        analytics.logEvent(FirebaseAnalytics.Event.SELECT_PROMOTION) {
            param(FirebaseAnalytics.Param.PROMOTION_ID, promotion.id)
            param(FirebaseAnalytics.Param.PROMOTION_NAME, promotion.name)
            param(FirebaseAnalytics.Param.CREATIVE_NAME, promotion.creative)
            param(FirebaseAnalytics.Param.CREATIVE_SLOT, promotion.position)
        }
    }
}
```

### 7.3 Refund Tracking

```kotlin
// Track refunds
fun logRefund(order: Order, items: List<OrderItem>) {
    analytics.logEvent(FirebaseAnalytics.Event.REFUND) {
        param(FirebaseAnalytics.Param.TRANSACTION_ID, order.id)
        param(FirebaseAnalytics.Param.VALUE, order.refundAmount)
        param(FirebaseAnalytics.Param.CURRENCY, "KES")
        param(FirebaseAnalytics.Param.ITEMS, items.map { item ->
            bundleOf(
                FirebaseAnalytics.Param.ITEM_ID to item.productId,
                FirebaseAnalytics.Param.QUANTITY to item.quantity,
                FirebaseAnalytics.Param.PRICE to item.unitPrice
            )
        }.toTypedArray())
    }
}
```

---

## 8. User Engagement

### 8.1 Session Tracking

```kotlin
// Session management
class SessionManager {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    private var sessionStartTime: Long = 0
    private var isActive = false
    
    fun startSession() {
        sessionStartTime = System.currentTimeMillis()
        isActive = true
        analytics.logEvent("session_start") {
            param("timestamp", sessionStartTime)
        }
    }
    
    fun endSession() {
        if (!isActive) return
        
        val sessionDuration = System.currentTimeMillis() - sessionStartTime
        analytics.logEvent("session_end") {
            param("duration_ms", sessionDuration)
            param("duration_minutes", sessionDuration / 60000)
        }
        isActive = false
    }
    
    fun getSessionDuration(): Long {
        return if (isActive) System.currentTimeMillis() - sessionStartTime else 0
    }
}
```

### 8.2 Retention Tracking

```kotlin
// Custom retention events
class RetentionAnalytics {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    private val prefs: SharedPreferences
    
    fun checkAndLogRetention(userId: String) {
        val firstOpenDate = prefs.getLong("first_open_date", 0)
        val lastActiveDate = prefs.getLong("last_active_date", 0)
        val today = Calendar.getInstance()
        
        if (firstOpenDate == 0L) {
            // First time user
            prefs.edit().putLong("first_open_date", today.timeInMillis).apply()
            analytics.logEvent("first_open")
        } else {
            val daysSinceFirstOpen = TimeUnit.MILLISECONDS.toDays(
                today.timeInMillis - firstOpenDate
            )
            
            // Log retention milestone
            when (daysSinceFirstOpen) {
                1L -> analytics.logEvent("retention_day_1")
                7L -> analytics.logEvent("retention_day_7")
                14L -> analytics.logEvent("retention_day_14")
                30L -> analytics.logEvent("retention_day_30")
                60L -> analytics.logEvent("retention_day_60")
                90L -> analytics.logEvent("retention_day_90")
            }
            
            // Check for re-engagement
            if (lastActiveDate > 0) {
                val daysSinceLastActive = TimeUnit.MILLISECONDS.toDays(
                    today.timeInMillis - lastActiveDate
                )
                if (daysSinceLastActive >= 7) {
                    analytics.logEvent("user_re_engaged") {
                        param("days_inactive", daysSinceLastActive)
                    }
                }
            }
        }
        
        prefs.edit().putLong("last_active_date", today.timeInMillis).apply()
    }
}
```

---

## 9. Crashlytics Integration

### 9.1 Crash Reporting

```kotlin
// Crashlytics configuration and usage
class CrashlyticsManager {
    
    private val crashlytics = FirebaseCrashlytics.getInstance()
    
    fun setUserIdentifier(userId: String) {
        crashlytics.setUserId(hashUserId(userId))
    }
    
    fun setCustomKeys(user: User) {
        crashlytics.apply {
            setCustomKey("user_type", user.userType)
            setCustomKey("subscription_tier", user.subscriptionTier)
            setCustomKey("farm_size", user.farmSize ?: "unknown")
            setCustomKey("app_version", BuildConfig.VERSION_NAME)
            setCustomKey("android_version", Build.VERSION.RELEASE)
            setCustomKey("device_model", Build.MODEL)
        }
    }
    
    fun logBreadcrumb(message: String) {
        crashlytics.log(message)
    }
    
    fun logBreadcrumb(category: String, action: String, message: String = "") {
        crashlytics.log("[$category] $action: $message")
    }
    
    fun recordException(throwable: Throwable, context: Map<String, String> = emptyMap()) {
        context.forEach { (key, value) ->
            crashlytics.setCustomKey(key, value)
        }
        crashlytics.recordException(throwable)
    }
    
    fun setCrashlyticsCollectionEnabled(enabled: Boolean) {
        crashlytics.isCrashlyticsCollectionEnabled = enabled
    }
}
```

### 9.2 Non-Fatal Error Reporting

```kotlin
// Track non-fatal errors
class ErrorTracker {
    
    private val crashlytics = FirebaseCrashlytics.getInstance()
    
    fun trackApiError(endpoint: String, errorCode: Int, errorMessage: String) {
        val exception = Exception("API Error: $endpoint")
        crashlytics.apply {
            setCustomKey("api_endpoint", endpoint)
            setCustomKey("error_code", errorCode)
            setCustomKey("error_message", errorMessage)
            recordException(exception)
        }
    }
    
    fun trackValidationError(screen: String, field: String, error: String) {
        val exception = Exception("Validation Error: $screen.$field")
        crashlytics.apply {
            setCustomKey("screen", screen)
            setCustomKey("field", field)
            setCustomKey("validation_error", error)
            recordException(exception)
        }
    }
    
    fun trackStateError(screen: String, expected: String, actual: String) {
        val exception = Exception("State Error: $screen")
        crashlytics.apply {
            setCustomKey("screen", screen)
            setCustomKey("expected_state", expected)
            setCustomKey("actual_state", actual)
            recordException(exception)
        }
    }
}
```

---

## 10. Performance Monitoring

### 10.1 Custom Traces

```kotlin
// Performance monitoring implementation
class PerformanceTracker {
    
    private val performance = FirebasePerformance.getInstance()
    
    // Screen load time tracking
    fun trackScreenLoad(screenName: String, loadTimeMs: Long) {
        val trace = performance.newTrace("screen_load_$screenName")
        trace.start()
        trace.putMetric("load_time_ms", loadTimeMs)
        trace.stop()
    }
    
    // API call tracking
    suspend fun <T> trackApiCall(
        endpoint: String,
        block: suspend () -> T
    ): T {
        val trace = performance.newTrace("api_call")
        trace.putAttribute("endpoint", endpoint)
        trace.start()
        
        val startTime = System.currentTimeMillis()
        return try {
            val result = block()
            val duration = System.currentTimeMillis() - startTime
            trace.putMetric("duration_ms", duration)
            trace.putAttribute("status", "success")
            result
        } catch (e: Exception) {
            trace.putAttribute("status", "error")
            trace.putAttribute("error_type", e.javaClass.simpleName)
            throw e
        } finally {
            trace.stop()
        }
    }
    
    // Database operation tracking
    fun trackDatabaseOperation(operation: String, table: String, durationMs: Long) {
        val trace = performance.newTrace("db_operation")
        trace.putAttribute("operation", operation)
        trace.putAttribute("table", table)
        trace.putMetric("duration_ms", durationMs)
        trace.start()
        trace.stop()
    }
    
    // Image loading tracking
    fun trackImageLoad(source: String, sizeBytes: Long, durationMs: Long) {
        val trace = performance.newTrace("image_load")
        trace.putAttribute("source", source)
        trace.putMetric("size_bytes", sizeBytes)
        trace.putMetric("duration_ms", durationMs)
        trace.start()
        trace.stop()
    }
}
```

### 10.2 HTTP Monitoring

```kotlin
// HTTP request monitoring interceptor
class PerformanceInterceptor : Interceptor {
    
    private val performance = FirebasePerformance.getInstance()
    
    override fun intercept(chain: Interceptor.Chain): Response {
        val request = chain.request()
        val url = request.url.toString()
        val method = request.method
        
        val metric = performance.newHttpMetric(url, method)
        
        metric.apply {
            setRequestPayloadSize(request.body?.contentLength() ?: 0)
            start()
        }
        
        return try {
            val response = chain.proceed(request)
            
            metric.apply {
                setHttpResponseCode(response.code)
                setResponseContentType(response.header("Content-Type") ?: "")
                setResponsePayloadSize(response.body?.contentLength() ?: 0)
                stop()
            }
            
            response
        } catch (e: Exception) {
            metric.putAttribute("error", e.message ?: "unknown")
            metric.stop()
            throw e
        }
    }
}
```

---

## 11. A/B Testing Setup

### 11.1 Firebase Remote Config

```kotlin
// Remote Config for A/B testing
class RemoteConfigManager {
    
    private val remoteConfig: FirebaseRemoteConfig = Firebase.remoteConfig
    
    init {
        val configSettings = remoteConfigSettings {
            minimumFetchIntervalInSeconds = if (BuildConfig.DEBUG) 60 else 3600
        }
        remoteConfig.setConfigSettingsAsync(configSettings)
        
        // Set default values
        remoteConfig.setDefaultsAsync(R.xml.remote_config_defaults)
    }
    
    suspend fun fetchAndActivate(): Boolean {
        return try {
            remoteConfig.fetchAndActivate().await()
        } catch (e: Exception) {
            false
        }
    }
    
    // A/B Test: Onboarding flow variant
    fun getOnboardingVariant(): String {
        return remoteConfig.getString("onboarding_variant")
    }
    
    // A/B Test: Home screen layout
    fun getHomeLayoutVariant(): String {
        return remoteConfig.getString("home_layout_variant")
    }
    
    // A/B Test: Pricing display
    fun getPricingDisplayVariant(): String {
        return remoteConfig.getString("pricing_display_variant")
    }
    
    // A/B Test: Push notification timing
    fun getPushNotificationDelayMinutes(): Long {
        return remoteConfig.getLong("push_notification_delay_minutes")
    }
    
    // Log experiment exposure
    fun logExperimentExposure(experimentId: String, variant: String) {
        Firebase.analytics.logEvent("experiment_exposure") {
            param("experiment_id", experimentId)
            param("variant", variant)
        }
    }
}
```

### 11.2 Experiment Tracking

```kotlin
// A/B Testing Analytics
class ABTestAnalytics {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    
    fun trackExperimentStart(experimentId: String, variant: String) {
        analytics.logEvent("experiment_started") {
            param("experiment_id", experimentId)
            param("variant", variant)
        }
    }
    
    fun trackExperimentConversion(experimentId: String, variant: String, goal: String) {
        analytics.logEvent("experiment_conversion") {
            param("experiment_id", experimentId)
            param("variant", variant)
            param("goal", goal)
        }
    }
    
    fun trackFeatureFlagEnabled(feature: String) {
        analytics.logEvent("feature_flag_enabled") {
            param("feature", feature)
        }
    }
}
```

---

## 12. Privacy & GDPR

### 12.1 Consent Management

```kotlin
// Privacy and consent management
class PrivacyManager {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    private val crashlytics = FirebaseCrashlytics.getInstance()
    private val performance = FirebasePerformance.getInstance()
    private val prefs: SharedPreferences
    
    companion object {
        const val KEY_ANALYTICS_CONSENT = "analytics_consent"
        const val KEY_CRASHLYTICS_CONSENT = "crashlytics_consent"
        const val KEY_PERFORMANCE_CONSENT = "performance_consent"
        const val KEY_ADVERTISING_CONSENT = "advertising_consent"
    }
    
    // Check if user has given consent
    fun hasAnalyticsConsent(): Boolean {
        return prefs.getBoolean(KEY_ANALYTICS_CONSENT, false)
    }
    
    // Request consent
    fun requestConsent(callback: (ConsentStatus) -> Unit) {
        // Show consent dialog and collect user choices
        showConsentDialog { analytics, crashlytics, performance, advertising ->
            saveConsent(analytics, crashlytics, performance, advertising)
            applyConsent()
            callback(ConsentStatus(analytics, crashlytics, performance, advertising))
        }
    }
    
    private fun saveConsent(
        analytics: Boolean,
        crashlytics: Boolean,
        performance: Boolean,
        advertising: Boolean
    ) {
        prefs.edit().apply {
            putBoolean(KEY_ANALYTICS_CONSENT, analytics)
            putBoolean(KEY_CRASHLYTICS_CONSENT, crashlytics)
            putBoolean(KEY_PERFORMANCE_CONSENT, performance)
            putBoolean(KEY_ADVERTISING_CONSENT, advertising)
            apply()
        }
    }
    
    fun applyConsent() {
        val analyticsConsent = if (hasAnalyticsConsent()) 
            FirebaseAnalytics.ConsentStatus.GRANTED 
        else 
            FirebaseAnalytics.ConsentStatus.DENIED
            
        val advertisingConsent = if (prefs.getBoolean(KEY_ADVERTISING_CONSENT, false))
            FirebaseAnalytics.ConsentStatus.GRANTED
        else
            FirebaseAnalytics.ConsentStatus.DENIED
        
        analytics.setConsent(mapOf(
            FirebaseAnalytics.ConsentType.ANALYTICS_STORAGE to analyticsConsent,
            FirebaseAnalytics.ConsentType.AD_STORAGE to advertisingConsent
        ))
        
        crashlytics.isCrashlyticsCollectionEnabled = 
            prefs.getBoolean(KEY_CRASHLYTICS_CONSENT, false)
        
        performance.isPerformanceCollectionEnabled = 
            prefs.getBoolean(KEY_PERFORMANCE_CONSENT, false)
    }
    
    // Data deletion request (GDPR Article 17)
    fun requestDataDeletion(userId: String) {
        // Log deletion request
        analytics.logEvent("data_deletion_requested") {
            param("user_id_hash", hashUserId(userId))
        }
        
        // Clear user data
        analytics.setUserId(null)
        clearUserProperties()
        
        // Reset consent
        prefs.edit().clear().apply()
        
        // Notify server for backend data deletion
        // apiService.requestDataDeletion(userId)
    }
    
    // Export user data (GDPR Article 20)
    fun exportUserData(userId: String): UserDataExport {
        return UserDataExport(
            userId = userId,
            analyticsEvents = exportAnalyticsEvents(userId),
            userProperties = exportUserProperties(userId),
            exportDate = Date()
        )
    }
    
    private fun clearUserProperties() {
        // Reset all user properties
        val properties = listOf(
            "user_type", "farm_size", "subscription_tier",
            "location_region", "preferred_category", "engagement_level"
        )
        properties.forEach { property ->
            analytics.setUserProperty(property, null)
        }
    }
    
    data class ConsentStatus(
        val analytics: Boolean,
        val crashlytics: Boolean,
        val performance: Boolean,
        val advertising: Boolean
    )
}
```

### 12.2 Data Retention

```kotlin
// Data retention policies
object DataRetentionPolicy {
    
    const val ANALYTICS_RETENTION_DAYS = 14 // Firebase Analytics default
    const val EXTENDED_RETENTION_DAYS = 26 // With user consent
    
    fun configureRetention(extended: Boolean) {
        val retentionDays = if (extended) EXTENDED_RETENTION_DAYS else ANALYTICS_RETENTION_DAYS
        
        Firebase.analytics.setConsent(mapOf(
            FirebaseAnalytics.ConsentType.ANALYTICS_STORAGE to 
                if (extended) FirebaseAnalytics.ConsentStatus.GRANTED 
                else FirebaseAnalytics.ConsentStatus.DENIED
        ))
    }
}
```

### 12.3 Privacy Manifest (iOS)

```xml
<!-- PrivacyInfo.xcprivacy -->
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>NSPrivacyCollectedDataTypes</key>
    <array>
        <dict>
            <key>NSPrivacyCollectedDataType</key>
            <string>NSPrivacyCollectedDataTypeUserID</string>
            <key>NSPrivacyCollectedDataTypeLinked</key>
            <true/>
            <key>NSPrivacyCollectedDataTypeTracking</key>
            <false/>
            <key>NSPrivacyCollectedDataTypePurposes</key>
            <array>
                <string>NSPrivacyCollectedDataTypePurposeAnalytics</string>
                <string>NSPrivacyCollectedDataTypePurposeAppFunctionality</string>
            </array>
        </dict>
        <dict>
            <key>NSPrivacyCollectedDataType</key>
            <string>NSPrivacyCollectedDataTypeProductInteraction</string>
            <key>NSPrivacyCollectedDataTypeLinked</key>
            <false/>
            <key>NSPrivacyCollectedDataTypeTracking</key>
            <false/>
            <key>NSPrivacyCollectedDataTypePurposes</key>
            <array>
                <string>NSPrivacyCollectedDataTypePurposeAnalytics</string>
            </array>
        </dict>
    </array>
    <key>NSPrivacyAccessedAPITypes</key>
    <array>
        <dict>
            <key>NSPrivacyAccessedAPIType</key>
            <string>NSPrivacyAccessedAPICategoryUserDefaults</string>
            <key>NSPrivacyAccessedAPITypeReasons</key>
            <array>
                <string>CA92.1</string>
            </array>
        </dict>
    </array>
</dict>
</plist>
```

---

## 13. Dashboard Configuration

### 13.1 Firebase Console Setup

#### 13.1.1 Custom Definitions

| Custom Dimension | Scope | Description |
|-----------------|-------|-------------|
| User Type | User | farmer, buyer, admin |
| Farm Size | User | Small, Medium, Large |
| Subscription Tier | User | Basic, Premium, Enterprise |
| Order Value Range | Event | 0-1000, 1000-5000, 5000+ |

#### 13.1.2 Custom Metrics

| Custom Metric | Scope | Unit |
|--------------|-------|------|
| Average Order Value | Event | KES |
| Milk Recorded (Daily) | Event | Liters |
| Session Duration | Event | Minutes |
| Farm Visit Bookings | Event | Count |

### 13.2 Custom Reports

```yaml
Reports:
  User_Acquisition:
    - Daily Active Users by User Type
    - New Users by Source
    - User Retention Cohorts
    
  E-commerce:
    - Revenue by Product Category
    - Conversion Funnel Analysis
    - Average Order Value Trends
    - Cart Abandonment Rate
    
  Farm_Management:
    - Daily Milk Production
    - Animal Health Check Frequency
    - Farm Visit Booking Rate
    
  Engagement:
    - Session Duration by User Type
    - Feature Usage Frequency
    - Screen Flow Analysis
    
  Technical:
    - Crash-Free Users Rate
    - API Response Times
    - Screen Load Performance
```

### 13.3 Alerts Configuration

| Alert Name | Condition | Threshold | Notification |
|------------|-----------|-----------|--------------|
| Crash Spike | Crash-free rate drops | < 99% | Email + Slack |
| Revenue Drop | Daily revenue | < 20% of avg | Email |
| DAU Drop | Daily active users | < 10% of avg | Email |
| API Latency | 95th percentile | > 2000ms | Slack |

---

## 14. Testing Analytics

### 14.1 Debug Mode

#### 14.1.1 Android Debug Mode

```kotlin
// Enable debug mode
class AnalyticsDebugHelper {
    
    companion object {
        fun enableDebugMode(context: Context) {
            // Enable verbose logging
            FirebaseAnalytics.getInstance(context).setAnalyticsCollectionEnabled(true)
            
            // Enable debug mode via ADB
            // adb shell setprop debug.firebase.analytics.app com.smartdairy.app
        }
        
        fun disableDebugMode() {
            // adb shell setprop debug.firebase.analytics.app .none.
        }
    }
}
```

#### 14.1.2 iOS Debug Mode

```swift
// Enable debug mode for iOS
class AnalyticsDebugHelper {
    static func enableDebugMode() {
        // Add -FIRAnalyticsDebugEnabled to scheme arguments
        // Or use: Analytics.setAnalyticsCollectionEnabled(true)
    }
}
```

### 14.2 Event Validation

```kotlin
// Event validation and testing
class AnalyticsValidator {
    
    private val analytics: FirebaseAnalytics = SmartDairyApplication.analytics
    
    fun validateAllEvents() {
        validateAuthEvents()
        validateEcommerceEvents()
        validateFarmEvents()
        validateOrderEvents()
        validateSubscriptionEvents()
    }
    
    private fun validateAuthEvents() {
        // Test login success
        analytics.logEvent(AuthEvents.LOGIN_SUCCESS) {
            param(AuthEvents.Params.METHOD, AuthEvents.Methods.EMAIL)
            param(AuthEvents.Params.USER_TYPE, AuthEvents.UserTypes.FARMER)
        }
        
        // Test login failure
        analytics.logEvent(AuthEvents.LOGIN_FAILED) {
            param(AuthEvents.Params.METHOD, AuthEvents.Methods.EMAIL)
            param(AuthEvents.Params.ERROR_CODE, "invalid_credentials")
            param(AuthEvents.Params.REASON, "Password mismatch")
        }
    }
    
    private fun validateEcommerceEvents() {
        val testProduct = Bundle().apply {
            putString(FirebaseAnalytics.Param.ITEM_ID, "TEST_001")
            putString(FirebaseAnalytics.Param.ITEM_NAME, "Test Product")
            putString(FirebaseAnalytics.Param.ITEM_CATEGORY, "test")
            putDouble(FirebaseAnalytics.Param.PRICE, 99.99)
        }
        
        analytics.logEvent(FirebaseAnalytics.Event.VIEW_ITEM) {
            param(FirebaseAnalytics.Param.ITEMS, arrayOf(testProduct))
        }
        
        analytics.logEvent(FirebaseAnalytics.Event.ADD_TO_CART) {
            param(FirebaseAnalytics.Param.ITEMS, arrayOf(testProduct))
            param(FirebaseAnalytics.Param.VALUE, 99.99)
        }
    }
    
    private fun validateFarmEvents() {
        analytics.logEvent(FarmEvents.MILK_RECORDED) {
            param(FarmEvents.Params.QUANTITY, 15.5)
            param(FarmEvents.Params.ANIMAL_ID, "TEST_COW_001")
            param(FarmEvents.Params.MILKING_SESSION, FarmEvents.MilkingSessions.MORNING)
        }
    }
    
    private fun validateOrderEvents() {
        analytics.logEvent(OrderEvents.ORDER_PLACED) {
            param(OrderEvents.Params.ORDER_ID, "TEST_ORDER_001")
            param(OrderEvents.Params.ORDER_VALUE, 1500.0)
            param(OrderEvents.Params.ITEM_COUNT, 3)
        }
    }
    
    private fun validateSubscriptionEvents() {
        analytics.logEvent(SubscriptionEvents.SUBSCRIPTION_CREATED) {
            param(SubscriptionEvents.Params.PLAN_TYPE, SubscriptionEvents.PlanTypes.PREMIUM)
            param(SubscriptionEvents.Params.DURATION, "monthly")
            param(SubscriptionEvents.Params.AMOUNT, 999.0)
        }
    }
}
```

### 14.3 DebugView Testing

```kotlin
// DebugView helper for real-time testing
class DebugViewHelper {
    
    fun runDebugSequence() {
        // Sequence 1: User onboarding
        logDebugEvent("debug_onboarding_start")
        logDebugEvent("debug_onboarding_step_1")
        logDebugEvent("debug_onboarding_step_2")
        logDebugEvent("debug_onboarding_complete")
        
        // Sequence 2: Purchase flow
        logDebugEvent("debug_product_view")
        logDebugEvent("debug_add_to_cart")
        logDebugEvent("debug_begin_checkout")
        logDebugEvent("debug_purchase_complete")
        
        // Sequence 3: Farm management
        logDebugEvent("debug_animal_registered")
        logDebugEvent("debug_milk_recorded")
        logDebugEvent("debug_health_check")
    }
    
    private fun logDebugEvent(eventName: String) {
        SmartDairyApplication.analytics.logEvent(eventName) {
            param("debug_timestamp", System.currentTimeMillis())
            param("debug_session", UUID.randomUUID().toString())
        }
    }
}
```

### 14.4 Unit Testing

```kotlin
// Unit tests for analytics
@Test
fun `test login success event logs correctly`() {
    // Given
    val method = "email"
    val userType = "farmer"
    
    // When
    authAnalytics.logLoginSuccess(method, userType)
    
    // Then
    verify(analytics).logEvent(
        eq(AuthEvents.LOGIN_SUCCESS),
        argThat { bundle ->
            bundle.getString(AuthEvents.Params.METHOD) == method &&
            bundle.getString(AuthEvents.Params.USER_TYPE) == userType
        }
    )
}

@Test
fun `test purchase event includes all required parameters`() {
    // Given
    val order = createTestOrder()
    
    // When
    ecommerceAnalytics.logPurchase(order)
    
    // Then
    verify(analytics).logEvent(
        eq(FirebaseAnalytics.Event.PURCHASE),
        argThat { bundle ->
            bundle.getString(FirebaseAnalytics.Param.TRANSACTION_ID) != null &&
            bundle.getDouble(FirebaseAnalytics.Param.VALUE) > 0 &&
            bundle.getString(FirebaseAnalytics.Param.CURRENCY) == "KES"
        }
    )
}
```

---

## 15. Appendices

### Appendix A: Complete Event Reference

#### A.1 Authentication Events

| Event | Parameters | Description |
|-------|------------|-------------|
| `login_success` | method, user_type | Successful authentication |
| `login_failed` | method, error_code, reason | Failed authentication |
| `register_success` | method, user_type | New account created |
| `register_failed` | method, error_code, reason | Registration failed |
| `logout` | session_duration | User logged out |
| `password_reset` | method | Password reset requested |
| `biometric_enabled` | biometric_type | Biometric auth enabled |
| `biometric_disabled` | reason | Biometric auth disabled |

#### A.2 E-commerce Events

| Event | Parameters | Description |
|-------|------------|-------------|
| `view_item_list` | item_list_name, items | Product list viewed |
| `select_item` | item_list_name, items | Product selected from list |
| `view_item` | items | Product detail viewed |
| `add_to_cart` | items, value, currency | Item added to cart |
| `remove_from_cart` | items, value | Item removed from cart |
| `view_cart` | item_count, total_value | Cart viewed |
| `begin_checkout` | items, value, currency | Checkout started |
| `add_shipping_info` | shipping_tier | Shipping selected |
| `add_payment_info` | payment_type | Payment method selected |
| `purchase` | transaction_id, value, items | Purchase completed |
| `refund` | transaction_id, value, items | Refund processed |
| `view_promotion` | promotion_id, creative_name | Promotion viewed |
| `select_promotion` | promotion_id, creative_name | Promotion clicked |

#### A.3 Farm Management Events

| Event | Parameters | Description |
|-------|------------|-------------|
| `milk_recorded` | quantity, animal_id, session | Milk production recorded |
| `milk_record_bulk` | total_quantity, animal_count | Bulk milk recording |
| `animal_registered` | animal_type, breed, age | New animal added |
| `animal_updated` | animal_id, field_changed | Animal info updated |
| `animal_removed` | animal_id, reason | Animal removed |
| `health_check_recorded` | animal_id, check_type, result | Health check logged |
| `vaccination_recorded` | animal_id, vaccine_type | Vaccination recorded |
| `breeding_recorded` | animal_id, method, sire_id | Breeding event logged |
| `calving_recorded` | dam_id, calf_details | Calving recorded |
| `farm_visit_scheduled` | visit_type, preferred_date | Farm visit booked |
| `farm_visit_completed` | visit_id, satisfaction | Farm visit completed |
| `feed_recorded` | feed_type, quantity, cost | Feed consumption logged |

#### A.4 Order Events

| Event | Parameters | Description |
|-------|------------|-------------|
| `order_placed` | order_id, order_value, item_count | New order created |
| `order_confirmed` | order_id, confirmation_time | Order confirmed |
| `order_processing` | order_id, processing_start | Order processing began |
| `order_shipped` | order_id, tracking_id | Order shipped |
| `order_delivered` | order_id, delivery_time | Order delivered |
| `order_cancelled` | order_id, reason, stage | Order cancelled |
| `order_returned` | order_id, reason, items | Order returned |
| `order_rated` | order_id, rating, has_comment | Order rated |
| `order_reordered` | original_order_id, new_order_id | Previous order repeated |

#### A.5 Subscription Events

| Event | Parameters | Description |
|-------|------------|-------------|
| `view_subscription_plans` | source | Pricing page viewed |
| `select_subscription_plan` | plan_type, duration | Plan selected |
| `subscription_created` | plan_type, duration, amount | Subscription started |
| `subscription_modified` | old_plan, new_plan, reason | Plan changed |
| `subscription_cancelled` | plan_type, reason, tenure_days | Subscription ended |
| `subscription_renewed` | plan_type, amount, auto_renew | Subscription renewed |
| `subscription_expired` | plan_type, days_overdue | Subscription expired |
| `payment_successful` | amount, method, subscription_id | Payment processed |
| `payment_failed` | amount, method, error_code, retry_count | Payment failed |
| `payment_retry_success` | original_failure_date | Failed payment recovered |

#### A.6 App Lifecycle Events

| Event | Parameters | Description |
|-------|------------|-------------|
| `app_open` | launch_source, cold_start | App launched |
| `app_update` | previous_version, new_version | App updated |
| `search_performed` | query, result_count, category | Search executed |
| `search_result_clicked` | query, result_position | Search result selected |
| `filter_applied` | filter_type, filter_value | Filter used |
| `sort_applied` | sort_by, sort_order | Sorting applied |
| `notification_received` | notification_type, campaign_id | Push received |
| `notification_opened` | notification_type, campaign_id | Push tapped |
| `notification_dismissed` | notification_type | Push dismissed |
| `deep_link_opened` | source, url | Deep link accessed |
| `share_initiated` | content_type, share_method | Share started |
| `share_completed` | content_type, share_method | Share completed |

### Appendix B: Analytics Service Class

```kotlin
// Complete Analytics Service
class AnalyticsService private constructor(context: Context) {
    
    private val analytics: FirebaseAnalytics = Firebase.analytics
    private val crashlytics = FirebaseCrashlytics.getInstance()
    
    // Sub-services
    val auth = AuthAnalytics(analytics)
    val ecommerce = EcommerceAnalytics(analytics)
    val farm = FarmAnalytics(analytics)
    val orders = OrderAnalytics(analytics)
    val subscriptions = SubscriptionAnalytics(analytics)
    val onboarding = OnboardingAnalytics(analytics)
    val engagement = EngagementAnalytics(analytics)
    val performance = PerformanceTracker()
    
    companion object {
        @Volatile
        private var instance: AnalyticsService? = null
        
        fun getInstance(context: Context): AnalyticsService {
            return instance ?: synchronized(this) {
                instance ?: AnalyticsService(context).also { instance = it }
            }
        }
    }
    
    // Global methods
    fun setUserId(userId: String?) {
        val hashedId = userId?.let { hashUserId(it) }
        analytics.setUserId(hashedId)
        crashlytics.setUserId(hashedId ?: "")
    }
    
    fun setUserProperty(name: String, value: String?) {
        analytics.setUserProperty(name, value)
    }
    
    fun logEvent(name: String, params: Bundle.() -> Unit = {}) {
        val bundle = Bundle()
        bundle.params()
        analytics.logEvent(name, bundle)
        
        // Also log as breadcrumb
        crashlytics.log("Event: $name, Params: ${bundle.toString()}")
    }
    
    fun logScreenView(screenName: String, screenClass: String) {
        analytics.logEvent(FirebaseAnalytics.Event.SCREEN_VIEW) {
            param(FirebaseAnalytics.Param.SCREEN_NAME, screenName)
            param(FirebaseAnalytics.Param.SCREEN_CLASS, screenClass)
        }
        crashlytics.log("Screen: $screenName")
    }
    
    private fun hashUserId(userId: String): String {
        return MessageDigest.getInstance("SHA-256")
            .digest(userId.toByteArray())
            .joinToString("") { "%02x".format(it) }
    }
}

// Usage in ViewModel/Activity
class MyViewModel(private val analytics: AnalyticsService) : ViewModel() {
    
    fun onLoginSuccess(user: User) {
        analytics.setUserId(user.id)
        analytics.auth.logLoginSuccess(AuthEvents.Methods.EMAIL, user.userType)
        analytics.setUserProperty("user_type", user.userType)
    }
    
    fun onPurchaseComplete(order: Order) {
        analytics.ecommerce.logPurchase(order)
        analytics.orders.logOrderPlaced(order)
    }
}
```

### Appendix C: Event Constants Reference

```kotlin
// Complete event constants
object AnalyticsConstants {
    
    object Events {
        // Auth
        const val LOGIN_SUCCESS = "login_success"
        const val LOGIN_FAILED = "login_failed"
        const val REGISTER_SUCCESS = "register_success"
        const val REGISTER_FAILED = "register_failed"
        const val LOGOUT = "logout"
        
        // E-commerce (using Firebase standard events)
        const val VIEW_ITEM = "view_item"
        const val ADD_TO_CART = "add_to_cart"
        const val REMOVE_FROM_CART = "remove_from_cart"
        const val BEGIN_CHECKOUT = "begin_checkout"
        const val PURCHASE = "purchase"
        const val REFUND = "refund"
        
        // Farm
        const val MILK_RECORDED = "milk_recorded"
        const val ANIMAL_REGISTERED = "animal_registered"
        const val HEALTH_CHECK_RECORDED = "health_check_recorded"
        const val VACCINATION_RECORDED = "vaccination_recorded"
        const val BREEDING_RECORDED = "breeding_recorded"
        
        // Orders
        const val ORDER_PLACED = "order_placed"
        const val ORDER_CANCELLED = "order_cancelled"
        const val ORDER_DELIVERED = "order_delivered"
        
        // Subscriptions
        const val SUBSCRIPTION_CREATED = "subscription_created"
        const val SUBSCRIPTION_MODIFIED = "subscription_modified"
        const val SUBSCRIPTION_CANCELLED = "subscription_cancelled"
        const val PAYMENT_SUCCESSFUL = "payment_successful"
        const val PAYMENT_FAILED = "payment_failed"
    }
    
    object Params {
        const val METHOD = "method"
        const val USER_TYPE = "user_type"
        const val ERROR_CODE = "error_code"
        const val REASON = "reason"
        const val ITEM_ID = "item_id"
        const val ITEM_NAME = "item_name"
        const val ITEM_CATEGORY = "item_category"
        const val PRICE = "price"
        const val QUANTITY = "quantity"
        const val VALUE = "value"
        const val CURRENCY = "currency"
        const val TRANSACTION_ID = "transaction_id"
        const val ORDER_ID = "order_id"
        const val PLAN_TYPE = "plan_type"
        const val ANIMAL_ID = "animal_id"
        const val CHECK_TYPE = "check_type"
    }
    
    object UserTypes {
        const val FARMER = "farmer"
        const val BUYER = "buyer"
        const val ADMIN = "admin"
        const val DELIVERY = "delivery"
    }
    
    object Methods {
        const val EMAIL = "email"
        const val PHONE = "phone"
        const val GOOGLE = "google"
        const val FACEBOOK = "facebook"
        const val APPLE = "apple"
        const val MPESA = "mpesa"
        const val CARD = "card"
    }
    
    object Categories {
        const val FRESH_MILK = "fresh_milk"
        const val CHEESE = "cheese"
        const val YOGURT = "yogurt"
        const val BUTTER = "butter"
        const val ICE_CREAM = "ice_cream"
    }
    
    object Plans {
        const val BASIC = "basic"
        const val PREMIUM = "premium"
        const val ENTERPRISE = "enterprise"
    }
}
```

### Appendix D: Privacy Compliance Checklist

```markdown
## Pre-Launch Privacy Checklist

### Consent
- [ ] Consent banner implemented
- [ ] Granular consent options provided
- [ ] Consent withdrawal mechanism available
- [ ] Consent records maintained

### Data Collection
- [ ] Data minimization principles applied
- [ ] Only necessary data collected
- [ ] Purpose limitation documented
- [ ] Retention periods defined

### User Rights
- [ ] Data export functionality implemented
- [ ] Data deletion process documented
- [ ] Right to rectification supported
- [ ] Privacy policy updated

### Technical
- [ ] User ID hashing implemented
- [ ] IP anonymization enabled
- [ ] Advertising identifiers not used without consent
- [ ] Debug logging disabled in production

### Documentation
- [ ] Privacy policy published
- [ ] Data processing agreement signed
- [ ] Data retention schedule documented
- [ ] Incident response plan prepared
```

---

## Document Sign-Off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author (Mobile Lead) | | | |
| Reviewer (Product Manager) | | | |
| Approver (CTO) | | | |

---

**Document Control**

- This document is the property of Smart Dairy Ltd.
- Unauthorized distribution is prohibited.
- All changes must be approved by the Mobile Lead and Product Manager.
- This document should be reviewed quarterly.

---

*End of Document H-010: Mobile Analytics Implementation*
