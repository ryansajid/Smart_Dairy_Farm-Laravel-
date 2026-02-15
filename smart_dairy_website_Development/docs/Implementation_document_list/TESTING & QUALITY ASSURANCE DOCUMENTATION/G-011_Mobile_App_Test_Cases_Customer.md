# Smart Dairy Ltd. - Mobile App Test Cases - Customer

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | G-011 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Lead |
| **Reviewer** | Mobile Development Lead |
| **Status** | Draft |

## Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | QA Engineer | Initial document creation |

## Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| QA Lead | [Name] | _____________ | _______ |
| Mobile Development Lead | [Name] | _____________ | _______ |
| Project Manager | [Name] | _____________ | _______ |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Test Environment](#2-test-environment)
3. [Test Case Summary](#3-test-case-summary)
4. [Test Cases](#4-test-cases)
   - 4.1 [User Registration & Login](#41-user-registration--login)
   - 4.2 [Product Catalog & Search](#42-product-catalog--search)
   - 4.3 [Shopping Cart](#43-shopping-cart)
   - 4.4 [Checkout & Payment](#44-checkout--payment)
   - 4.5 [Order Tracking](#45-order-tracking)
   - 4.6 [Subscription Management](#46-subscription-management)
   - 4.7 [User Profile](#47-user-profile)
   - 4.8 [Notifications (Push)](#48-notifications-push)
   - 4.9 [Customer Support](#49-customer-support)
   - 4.10 [Performance & Compatibility](#410-performance--compatibility)
5. [Test Execution Matrix](#5-test-execution-matrix)
6. [Defect Tracking](#6-defect-tracking)

---

## 1. Introduction

### 1.1 Purpose
This document provides comprehensive test cases for the Smart Dairy Ltd. Customer Mobile Application. It covers functional, non-functional, UI/UX, and compatibility testing scenarios for both iOS and Android platforms.

### 1.2 Scope
- **In Scope:** Customer-facing mobile application features
- **Platforms:** iOS 14+ (iPhone 12 and above), Android 10+
- **Languages:** English, Bengali (বাংলা)
- **Network Conditions:** Online, Offline, Poor connectivity

### 1.3 Target Audience
- QA Engineers
- Mobile Developers
- Product Managers
- Business Analysts

### 1.4 Test Case Priority Definition
| Priority | Description |
|----------|-------------|
| **P1 - Critical** | Core functionality; app cannot function without it |
| **P2 - High** | Important features; significantly impacts user experience |
| **P3 - Medium** | Standard features; nice to have but not critical |
| **P4 - Low** | Minor features; cosmetic or enhancement features |

---

## 2. Test Environment

### 2.1 Supported Devices

#### iOS Devices
| Device | OS Version | Screen Size | Test Priority |
|--------|------------|-------------|---------------|
| iPhone 15 Pro Max | iOS 17.x | 6.7" | High |
| iPhone 15 | iOS 17.x | 6.1" | High |
| iPhone 14 Pro | iOS 16.x | 6.1" | High |
| iPhone 14 | iOS 16.x | 6.1" | Medium |
| iPhone 13 | iOS 15.x | 6.1" | Medium |
| iPhone 12 | iOS 14.x | 6.1" | Medium |
| iPhone SE (3rd Gen) | iOS 16.x | 4.7" | Low |

#### Android Devices
| Device | OS Version | Screen Size | Test Priority |
|--------|------------|-------------|---------------|
| Samsung Galaxy S24 Ultra | Android 14 | 6.8" | High |
| Samsung Galaxy S23 | Android 14 | 6.1" | High |
| Google Pixel 8 Pro | Android 14 | 6.7" | High |
| OnePlus 12 | Android 14 | 6.82" | Medium |
| Xiaomi Redmi Note 13 | Android 13 | 6.67" | Medium |
| Samsung Galaxy A54 | Android 13 | 6.4" | Medium |
| Android Emulator (Various) | Android 10-14 | Various | Low |

### 2.2 Test Data Requirements
- Valid mobile numbers: 01XXXXXXXXX format (Bangladesh)
- Test payment accounts (bKash sandbox, Nagad sandbox)
- Sample products across all categories
- Test user accounts with various subscription statuses

### 2.3 Network Conditions
| Condition | Description |
|-----------|-------------|
| 4G/5G Full | Strong signal, fast data connection |
| 3G/4G Slow | Moderate speed, latency present |
| Poor Connectivity | Intermittent connection, packet loss |
| Offline | No network connection |
| WiFi | Stable high-speed connection |

---

## 3. Test Case Summary

| Feature Area | Total Test Cases | P1 | P2 | P3 | P4 |
|--------------|------------------|----|----|----|----|
| User Registration & Login | 10 | 6 | 3 | 1 | 0 |
| Product Catalog & Search | 10 | 3 | 4 | 2 | 1 |
| Shopping Cart | 8 | 4 | 3 | 1 | 0 |
| Checkout & Payment | 12 | 6 | 4 | 2 | 0 |
| Order Tracking | 6 | 3 | 2 | 1 | 0 |
| Subscription Management | 6 | 3 | 2 | 1 | 0 |
| User Profile | 6 | 2 | 2 | 2 | 0 |
| Notifications (Push) | 8 | 3 | 3 | 2 | 0 |
| Customer Support | 5 | 2 | 2 | 1 | 0 |
| Performance & Compatibility | 6 | 2 | 2 | 2 | 0 |
| **TOTAL** | **77** | **34** | **27** | **15** | **1** |

---

## 4. Test Cases

### 4.1 User Registration & Login

---

#### TC-MC-001: User Registration with Valid Mobile Number
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-001 |
| **Feature Area** | User Registration & Login |
| **Title** | Successful user registration with valid mobile number |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Launch app for first time | Splash screen displayed, followed by onboarding screens |
| 2 | Tap "Get Started" or "Register" button | Registration screen displayed |
| 3 | Enter valid mobile number: 01712345678 | Mobile number accepted, formatted correctly |
| 4 | Tap "Send OTP" button | OTP sent, countdown timer starts (60 seconds) |
| 5 | Enter valid OTP received via SMS | OTP verified, proceed to profile setup |
| 6 | Enter full name: "Rahim Ahmed" | Name field accepts input |
| 7 | Enter email: "rahim.ahmed@email.com" | Email validated and accepted |
| 8 | Set password (8+ chars, alphanumeric) | Password meets security requirements |
| 9 | Accept terms and conditions | Checkbox selected |
| 10 | Tap "Complete Registration" | Registration successful, redirected to home screen |

**Test Data:**
- Mobile: 01712345678
- Name: Rahim Ahmed
- Email: rahim.ahmed@email.com
- Password: TestPass123!

**Device Requirements:**
- iOS 14+ or Android 10+
- SMS receiving capability
- Internet connection

---

#### TC-MC-002: User Login with Valid Credentials
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-002 |
| **Feature Area** | User Registration & Login |
| **Title** | Successful login with registered mobile number and password |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Launch app | Login screen displayed |
| 2 | Enter registered mobile: 01712345678 | Mobile number accepted |
| 3 | Enter correct password | Password masked, accepted |
| 4 | Tap "Login" button | Login successful, home screen displayed |
| 5 | Verify session persistence | User remains logged in on app restart |

**Test Data:**
- Mobile: 01712345678
- Password: TestPass123!

**Preconditions:**
- User already registered
- App version 1.0+

---

#### TC-MC-003: Login with Invalid Password
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-003 |
| **Feature Area** | User Registration & Login |
| **Title** | Login attempt with incorrect password |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Launch app and navigate to login | Login screen displayed |
| 2 | Enter valid mobile: 01712345678 | Mobile number accepted |
| 3 | Enter incorrect password: WrongPass123 | Password entered |
| 4 | Tap "Login" button | Error message: "Invalid mobile number or password" |
| 5 | Attempt 4 more failed logins | Account temporarily locked after 5 attempts |
| 6 | Verify lockout message | Message: "Account locked. Try again after 30 minutes" |

**Test Data:**
- Valid Mobile: 01712345678
- Invalid Password: WrongPass123

---

#### TC-MC-004: Biometric Authentication - Fingerprint Login (Android)
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-004 |
| **Feature Area** | User Registration & Login |
| **Title** | Login using fingerprint biometric authentication on Android |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login with credentials first time | Logged in successfully |
| 2 | Navigate to Profile > Security Settings | Security settings displayed |
| 3 | Enable "Fingerprint Login" | System prompts for fingerprint |
| 4 | Authenticate with registered fingerprint | Fingerprint registered successfully |
| 5 | Logout and close app | App closed |
| 6 | Relaunch app | Biometric prompt appears |
| 7 | Scan registered fingerprint | Login successful, home screen displayed |

**Device Requirements:**
- Android device with fingerprint sensor
- Android 10+
- Fingerprint enrolled in device settings

---

#### TC-MC-005: Biometric Authentication - Face ID Login (iOS)
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-005 |
| **Feature Area** | User Registration & Login |
| **Title** | Login using Face ID biometric authentication on iOS |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login with credentials first time | Logged in successfully |
| 2 | Navigate to Profile > Security Settings | Security settings displayed |
| 3 | Enable "Face ID Login" | System prompts for Face ID permission |
| 4 | Authenticate with Face ID | Face ID registered successfully |
| 5 | Logout and close app | App closed |
| 6 | Relaunch app | Face ID prompt appears |
| 7 | Use Face ID authentication | Login successful, home screen displayed |

**Device Requirements:**
- iPhone with Face ID (iPhone X and above)
- Face ID enrolled in device settings

---

#### TC-MC-006: Password Reset Functionality
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-006 |
| **Feature Area** | User Registration & Login |
| **Title** | Successful password reset via OTP |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | On login screen, tap "Forgot Password?" | Password reset screen displayed |
| 2 | Enter registered mobile: 01712345678 | Mobile number accepted |
| 3 | Tap "Send OTP" | OTP sent to mobile number |
| 4 | Enter valid OTP | OTP verified |
| 5 | Enter new password: NewPass123! | Password meets requirements |
| 6 | Confirm new password | Passwords match validation passed |
| 7 | Tap "Reset Password" | Success message displayed |
| 8 | Login with new password | Login successful |

**Test Data:**
- Mobile: 01712345678
- New Password: NewPass123!

---

#### TC-MC-007: Language Switch - Bengali to English
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-007 |
| **Feature Area** | User Registration & Login |
| **Title** | Switch application language from Bengali to English |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Launch app with device language set to Bengali | App displays in Bengali (বাংলা) |
| 2 | Navigate to Profile > Settings > Language | Language options displayed |
| 3 | Select "English" | Language changed confirmation dialog shown |
| 4 | Confirm language change | All UI elements displayed in English |
| 5 | Navigate through app | Consistent English language throughout |
| 6 | Restart app | Language preference persisted |

**Device Requirements:**
- Bengali language support enabled

---

#### TC-MC-008: Offline Login - Cached Credentials
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-008 |
| **Feature Area** | User Registration & Login |
| **Title** | Login attempt in offline mode with cached session |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login while online | Session established successfully |
| 2 | Enable airplane mode / disconnect network | Network disconnected |
| 3 | Kill app and relaunch | App launches |
| 4 | Verify cached session | User remains logged in (limited functionality) |
| 5 | Attempt to browse cached products | Previously loaded products visible |
| 6 | Attempt to place order | Error: "Internet connection required" |

**Device Requirements:**
- Airplane mode capability

---

#### TC-MC-009: Invalid Mobile Number Format Validation
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-009 |
| **Feature Area** | User Registration & Login |
| **Title** | Mobile number format validation during registration |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to registration screen | Registration form displayed |
| 2 | Enter invalid mobile: 12345678 | Validation error: "Enter valid Bangladesh mobile number" |
| 3 | Enter invalid mobile: 0171234567 (10 digits) | Validation error: "Mobile number must be 11 digits" |
| 4 | Enter invalid mobile: 017123456789 (12 digits) | Validation error: "Mobile number must be 11 digits" |
| 5 | Enter valid format: 01712345678 | Accepted, no validation error |
| 6 | Enter with country code: +8801712345678 | Auto-formatted to 01712345678 |

**Test Data:**
- Invalid formats: 12345678, 0171234567, 017123456789
- Valid format: 01712345678

---

#### TC-MC-010: Session Timeout and Auto-logout
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-010 |
| **Feature Area** | User Registration & Login |
| **Title** | Automatic logout after session timeout period |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login to app | Successfully logged in |
| 2 | Keep app in background for 30 minutes | Session timeout threshold reached |
| 3 | Bring app to foreground | Session expired message displayed |
| 4 | Attempt to perform action | Redirected to login screen |
| 5 | Re-login required | Login screen with "Session expired" message |

**Preconditions:**
- Session timeout configured to 30 minutes

---

### 4.2 Product Catalog & Search

---

#### TC-MC-011: Browse Product Categories
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-011 |
| **Feature Area** | Product Catalog & Search |
| **Title** | Browse all product categories successfully |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login and navigate to Home | Home screen with category carousel displayed |
| 2 | Tap on "Fresh Milk" category | Products in Fresh Milk category displayed |
| 3 | Verify product listing | Products with images, names, prices shown |
| 4 | Navigate back and tap "Yogurt" | Yogurt products displayed |
| 5 | Navigate back and tap "Cheese" | Cheese products displayed |
| 6 | Scroll through all categories | All categories load correctly |

**Test Data:**
- Categories: Fresh Milk, Yogurt, Cheese, Butter, Ghee, Cream, Flavored Milk

---

#### TC-MC-012: Search Products by Name
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-012 |
| **Feature Area** | Product Catalog & Search |
| **Title** | Search for products using product name |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Tap search icon on home screen | Search bar activated |
| 2 | Type "full cream milk" | Search suggestions appear |
| 3 | Tap search button | Search results displayed |
| 4 | Verify results | Relevant products containing "full cream milk" shown |
| 5 | Verify no results case | "No products found" message for invalid search |

**Test Data:**
- Valid search: "full cream milk", "yogurt", "cheese"
- Invalid search: "xyzabc123"

---

#### TC-MC-013: Product Detail View
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-013 |
| **Feature Area** | Product Catalog & Search |
| **Title** | View detailed product information |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to product listing | Product list displayed |
| 2 | Tap on any product | Product detail screen opened |
| 3 | Verify product image | High-quality image displayed, zoomable |
| 4 | Verify product name and description | Correct information displayed |
| 5 | Verify price and unit | Price with ৳ symbol, unit (ml/gm) shown |
| 6 | Verify nutritional information | Nutrition facts displayed |
| 7 | Verify stock availability | "In Stock" or "Out of Stock" status shown |
| 8 | Verify "Add to Cart" button | Button enabled/disabled based on stock |

---

#### TC-MC-014: Filter Products by Price Range
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-014 |
| **Feature Area** | Product Catalog & Search |
| **Title** | Filter products by price range |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to product category | Category products displayed |
| 2 | Tap "Filter" button | Filter options displayed |
| 3 | Set minimum price: 50 | Minimum price set to 50 BDT |
| 4 | Set maximum price: 200 | Maximum price set to 200 BDT |
| 5 | Tap "Apply Filter" | Products filtered within price range |
| 6 | Verify results | Only products between ৳50-৳200 displayed |
| 7 | Tap "Clear Filter" | All products displayed again |

**Test Data:**
- Price range: ৳50 - ৳200

---

#### TC-MC-015: Sort Products by Price
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-015 |
| **Feature Area** | Product Catalog & Search |
| **Title** | Sort products by price (low to high / high to low) |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to product category | Category products displayed |
| 2 | Tap "Sort" dropdown | Sort options displayed |
| 3 | Select "Price: Low to High" | Products sorted by ascending price |
| 4 | Verify sort order | Lowest price products first |
| 5 | Select "Price: High to Low" | Products sorted by descending price |
| 6 | Verify sort order | Highest price products first |
| 7 | Select "Popularity" | Products sorted by popularity |

---

#### TC-MC-016: Search with Bengali Language
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-016 |
| **Feature Area** | Product Catalog & Search |
| **Title** | Search products using Bengali text input |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Switch app language to Bengali | Bengali UI displayed |
| 2 | Tap search icon | Search bar activated |
| 3 | Type "তাজা দুধ" (Fresh Milk) | Bengali text accepted in search |
| 4 | Tap search button | Relevant products displayed |
| 5 | Verify search results | Products matching Bengali query shown |

**Test Data:**
- Bengali search terms: "তাজা দুধ", "দই", "পনির"

---

#### TC-MC-017: Offline Product Catalog Access
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-017 |
| **Feature Area** | Product Catalog & Search |
| **Title** | Access cached product catalog in offline mode |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | While online, browse and load products | Products cached locally |
| 2 | Enable airplane mode | Network disconnected |
| 3 | Navigate to previously viewed categories | Cached products displayed |
| 4 | Attempt to view product details | Cached product details displayed |
| 5 | Display offline indicator | "Offline Mode" banner shown |

---

#### TC-MC-018: Product Image Zoom and Gallery
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-018 |
| **Feature Area** | Product Catalog & Search |
| **Title** | Product image zoom and gallery navigation |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open product detail page | Product details displayed |
| 2 | Tap on product image | Image gallery view opened |
| 3 | Swipe left/right | Navigate through multiple product images |
| 4 | Pinch to zoom | Image zooms in/out smoothly |
| 5 | Double-tap | Image zooms to fit/full |
| 6 | Tap close button | Return to product detail page |

---

#### TC-MC-019: Share Product via Deep Link
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-019 |
| **Feature Area** | Product Catalog & Search |
| **Title** | Share product using deep link |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open product detail page | Product details displayed |
| 2 | Tap "Share" button | Share options displayed |
| 3 | Select "Copy Link" | Deep link copied to clipboard |
| 4 | Open link in browser | Product page opens (or app opens if installed) |
| 5 | Share via WhatsApp | Link shared with preview |
| 6 | Tap shared link from another device | App opens to specific product |

**Test Data:**
- Deep link format: `smartdairy://product/12345`

---

#### TC-MC-020: Add Product to Wishlist/Favorites
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-020 |
| **Feature Area** | Product Catalog & Search |
| **Title** | Add and remove products from wishlist |
| **Priority** | P4 - Low |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open product detail page | Product details displayed |
| 2 | Tap heart icon (not filled) | Heart icon fills, "Added to wishlist" toast |
| 3 | Navigate to Profile > Wishlist | Product appears in wishlist |
| 4 | Return to product, tap filled heart | Heart icon empties, product removed |
| 5 | Verify wishlist | Product no longer in wishlist |

---

### 4.3 Shopping Cart

---

#### TC-MC-021: Add Product to Cart
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-021 |
| **Feature Area** | Shopping Cart |
| **Title** | Add single product to shopping cart |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to product listing | Product list displayed |
| 2 | Tap on a product | Product detail page opened |
| 3 | Select quantity: 2 | Quantity updated to 2 |
| 4 | Tap "Add to Cart" button | "Added to cart" confirmation shown |
| 5 | Verify cart badge | Cart icon shows "2" items |
| 6 | Navigate to Cart | Product with quantity 2 displayed in cart |

**Test Data:**
- Product: Fresh Milk 1L
- Quantity: 2

---

#### TC-MC-022: Update Cart Quantity
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-022 |
| **Feature Area** | Shopping Cart |
| **Title** | Update product quantity in cart |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add product to cart | Product in cart |
| 2 | Navigate to Cart page | Cart items displayed |
| 3 | Tap "+" button next to product | Quantity increases, price updates |
| 4 | Tap "+" until max stock reached | Cannot exceed available stock |
| 5 | Tap "-" button | Quantity decreases |
| 6 | Tap "-" until quantity is 1 | Minimum quantity is 1 |
| 7 | Verify total price calculation | Total = unit price × quantity |

**Test Data:**
- Initial quantity: 1
- Max stock: 10
- Unit price: ৳70

---

#### TC-MC-023: Remove Product from Cart
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-023 |
| **Feature Area** | Shopping Cart |
| **Title** | Remove product from shopping cart |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add multiple products to cart | Cart has 3 items |
| 2 | Navigate to Cart page | All items displayed |
| 3 | Swipe left on a product | Delete option revealed |
| 4 | Tap "Delete" or swipe fully | Product removed from cart |
| 5 | Verify removal confirmation | "Item removed" toast message |
| 6 | Verify cart total | Total recalculated correctly |
| 7 | Verify empty cart state | "Your cart is empty" when all removed |

---

#### TC-MC-024: Apply Promo Code
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-024 |
| **Feature Area** | Shopping Cart |
| **Title** | Apply valid and invalid promo codes |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add products to cart (total ৳500+) | Cart with items ready |
| 2 | Tap "Apply Promo Code" field | Promo code input activated |
| 3 | Enter valid code: "DAIRY20" | Code accepted |
| 4 | Tap "Apply" | Discount applied, new total shown |
| 5 | Verify discount calculation | 20% discount on eligible items |
| 6 | Enter invalid code: "INVALID" | Error: "Invalid promo code" |
| 7 | Enter expired code: "OLD2024" | Error: "Promo code expired" |

**Test Data:**
- Valid code: "DAIRY20" (20% off, min order ৳500)
- Invalid code: "INVALID"
- Expired code: "OLD2024"

---

#### TC-MC-025: Cart Persistence Across Sessions
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-025 |
| **Feature Area** | Shopping Cart |
| **Title** | Cart items persist after app restart |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add products to cart while logged in | Cart populated |
| 2 | Kill app completely | App closed |
| 3 | Relaunch app and login | User logged in |
| 4 | Navigate to Cart | Previous cart items still present |
| 5 | Verify quantities and selections | All details preserved |
| 6 | Logout and login as different user | Cart empty or shows user's cart |

---

#### TC-MC-026: Cart Synchronization Across Devices
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-026 |
| **Feature Area** | Shopping Cart |
| **Title** | Cart syncs when same account on multiple devices |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login on Device A, add items to cart | Cart populated on Device A |
| 2 | Login on Device B with same account | Cart items from Device A appear |
| 3 | Add new item on Device B | Item added |
| 4 | Refresh/pull-to-refresh on Device A | New item from Device B appears |
| 5 | Remove item on Device A | Item removed |
| 6 | Verify sync on Device B | Removal reflected on Device B |

**Device Requirements:**
- Two devices with same OS or different

---

#### TC-MC-027: Cart Maximum Quantity Limit
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-027 |
| **Feature Area** | Shopping Cart |
| **Title** | Maximum quantity limit enforcement in cart |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add product to cart | Product added |
| 2 | Attempt to increase quantity beyond 99 | Error: "Maximum 99 items allowed" |
| 3 | Verify stock-based limit | Cannot exceed available stock |
| 4 | Add 100th item via manual input | Validation prevents entry |

---

#### TC-MC-028: Cart Value Calculations
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-028 |
| **Feature Area** | Shopping Cart |
| **Title** | Accurate cart value calculations |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add multiple products with different prices | Products in cart |
| 2 | Verify subtotal | Sum of (price × quantity) for all items |
| 3 | Apply promo code | Discount calculated correctly |
| 4 | Verify delivery charge | Delivery fee added (৳50 or free if ৳500+) |
| 5 | Verify tax/VAT | 5% VAT calculated on subtotal |
| 6 | Verify final total | Subtotal - Discount + Delivery + VAT |

**Test Data:**
- Product A: ৳70 × 2 = ৳140
- Product B: ৳120 × 1 = ৳120
- Subtotal: ৳260
- Delivery: ৳50
- VAT (5%): ৳13
- Total: ৳323

---

### 4.4 Checkout & Payment

---

#### TC-MC-029: Checkout Process - Add Delivery Address
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-029 |
| **Feature Area** | Checkout & Payment |
| **Title** | Add new delivery address during checkout |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add items to cart and tap "Checkout" | Checkout flow initiated |
| 2 | Tap "Add New Address" | Address form displayed |
| 3 | Enter house/street: "12/A Mirpur Road" | Address line accepted |
| 4 | Select area: "Dhanmondi" | Area selected from dropdown |
| 5 | Select city: "Dhaka" | City selected |
| 6 | Enter landmark: "Near Abahani Field" | Landmark saved |
| 7 | Save address | Address saved and selected |

**Test Data:**
- Address: 12/A Mirpur Road
- Area: Dhanmondi
- City: Dhaka
- Landmark: Near Abahani Field

---

#### TC-MC-030: Payment via bKash
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-030 |
| **Feature Area** | Checkout & Payment |
| **Title** | Successful payment using bKash |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Proceed to checkout with items | Checkout page displayed |
| 2 | Select delivery address | Address selected |
| 3 | Select "bKash" as payment method | bKash option selected |
| 4 | Tap "Pay with bKash" | bKash payment gateway opens |
| 5 | Enter bKash number: 01912345678 | Number entered |
| 6 | Complete bKash authentication | OTP/pin verification |
| 7 | Confirm payment | Payment successful, order confirmed |
| 8 | Verify order confirmation screen | Order ID and details displayed |

**Test Data:**
- bKash number: 01912345678 (sandbox)
- OTP: 123456 (sandbox)

---

#### TC-MC-031: Payment via Nagad
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-031 |
| **Feature Area** | Checkout & Payment |
| **Title** | Successful payment using Nagad |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Proceed to checkout with items | Checkout page displayed |
| 2 | Select delivery address | Address selected |
| 3 | Select "Nagad" as payment method | Nagad option selected |
| 4 | Tap "Pay with Nagad" | Nagad payment gateway opens |
| 5 | Enter Nagad number: 01812345678 | Number entered |
| 6 | Complete Nagad authentication | PIN verification |
| 7 | Confirm payment | Payment successful, order confirmed |
| 8 | Verify order confirmation | Order details and receipt shown |

**Test Data:**
- Nagad number: 01812345678 (sandbox)
- PIN: 1234 (sandbox)

---

#### TC-MC-032: Cash on Delivery Payment
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-032 |
| **Feature Area** | Checkout & Payment |
| **Title** | Place order with Cash on Delivery |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Proceed to checkout | Checkout page displayed |
| 2 | Select delivery address | Address selected |
| 3 | Select "Cash on Delivery" | COD option selected |
| 4 | Review order summary | All details correct |
| 5 | Tap "Place Order" | Order placed successfully |
| 6 | Verify order confirmation | Order ID generated, confirmation shown |
| 7 | Verify SMS confirmation | Order confirmation SMS received |

---

#### TC-MC-033: Payment Failure and Retry
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-033 |
| **Feature Area** | Checkout & Payment |
| **Title** | Handle payment failure and retry |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Proceed to checkout | Checkout page displayed |
| 2 | Select payment method | Method selected |
| 3 | Enter incorrect PIN/OTP | Authentication fails |
| 4 | Verify error handling | Error: "Payment failed. Please try again" |
| 5 | Tap "Retry Payment" | Payment gateway reopens |
| 6 | Enter correct credentials | Payment successful |
| 7 | Verify order completion | Order confirmed |

---

#### TC-MC-034: Insufficient Balance Handling
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-034 |
| **Feature Area** | Checkout & Payment |
| **Title** | Handle insufficient balance in bKash/Nagad |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Create order with high value (৳5000+) | Order ready for payment |
| 2 | Select bKash payment | bKash gateway opens |
| 3 | Enter account with low balance | Credentials entered |
| 4 | Attempt payment | Error: "Insufficient balance" |
| 5 | Verify alternative options | Option to change payment method |
| 6 | Select different payment method | New method selected |
| 7 | Complete payment | Order placed successfully |

---

#### TC-MC-035: Order Summary Validation
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-035 |
| **Feature Area** | Checkout & Payment |
| **Title** | Validate order summary before payment |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add items to cart | Multiple items in cart |
| 2 | Proceed to checkout | Order summary displayed |
| 3 | Verify item details | Product names, quantities, prices correct |
| 4 | Verify delivery address | Complete address shown |
| 5 | Verify delivery slot | Selected time slot displayed |
| 6 | Verify payment method | Selected method shown |
| 7 | Verify all charges | Subtotal, discount, delivery, tax, total |
| 8 | Tap to edit cart | Returns to cart for modifications |

---

#### TC-MC-036: Save Card for Future Payments
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-036 |
| **Feature Area** | Checkout & Payment |
| **Title** | Save card details for faster checkout |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Complete checkout with card payment | Payment successful |
| 2 | Check "Save card for future" option | Option available and checked |
| 3 | Card saved | Card appears in saved cards list |
| 4 | Initiate new checkout | Checkout page displayed |
| 5 | Select saved card | Card details auto-filled |
| 6 | Complete CVV entry | Only CVV required |
| 7 | Complete payment | Faster checkout achieved |

---

#### TC-MC-037: Delivery Time Slot Selection
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-037 |
| **Feature Area** | Checkout & Payment |
| **Title** | Select preferred delivery time slot |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Proceed to checkout | Checkout page displayed |
| 2 | Navigate to delivery options | Time slot selection visible |
| 3 | View available slots | Slots for next 3 days displayed |
| 4 | Select date: Tomorrow | Tomorrow's slots shown |
| 5 | Select slot: 10:00 AM - 12:00 PM | Slot selected |
| 6 | Verify slot availability | Slot marked as selected |
| 7 | Complete order | Order placed with selected slot |

**Test Data:**
- Available slots: 9-11 AM, 11-1 PM, 2-4 PM, 4-6 PM, 6-8 PM

---

#### TC-MC-038: Delivery Address Validation
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-038 |
| **Feature Area** | Checkout & Payment |
| **Title** | Validate delivery address for serviceability |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add new address | Address form displayed |
| 2 | Enter address in non-serviceable area | Area outside Dhaka/Chittagong |
| 3 | Save address | Validation error: "Delivery not available" |
| 4 | Enter incomplete address | Missing house number |
| 5 | Save | Error: "Please enter complete address" |
| 6 | Enter valid serviceable address | Address saved successfully |

---

#### TC-MC-039: Checkout with Multiple Addresses
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-039 |
| **Feature Area** | Checkout & Payment |
| **Title** | Select from multiple saved addresses |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Ensure user has 3+ saved addresses | Addresses saved in profile |
| 2 | Proceed to checkout | Address selection screen |
| 3 | View list of addresses | All saved addresses displayed |
| 4 | Select different address | Address selected, marked as default for this order |
| 5 | Verify address change | Delivery details updated |
| 6 | Set new default address | Default preference updated |

---

#### TC-MC-040: Order Cancellation Before Payment
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-040 |
| **Feature Area** | Checkout & Payment |
| **Title** | Cancel order during checkout process |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Add items and proceed to checkout | Checkout in progress |
| 2 | Tap back button | Confirmation: "Discard checkout?" |
| 3 | Select "Save and Exit" | Cart preserved, checkout cancelled |
| 4 | Or select "Discard" | Cart items retained, progress lost |
| 5 | Verify cart state | Items still in cart |

---

### 4.5 Order Tracking

---

#### TC-MC-041: View Active Orders
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-041 |
| **Feature Area** | Order Tracking |
| **Title** | View list of active/pending orders |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login to app | User logged in |
| 2 | Tap "My Orders" from menu | Orders screen displayed |
| 3 | View "Active" tab | Active orders listed |
| 4 | Verify order details | Order ID, date, status, total visible |
| 5 | Pull to refresh | Orders refreshed |
| 6 | Scroll through list | All active orders displayed |

---

#### TC-MC-042: Real-time Order Status Tracking
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-042 |
| **Feature Area** | Order Tracking |
| **Title** | Track order status updates in real-time |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open active order details | Order tracking screen displayed |
| 2 | View status timeline | Current status highlighted |
| 3 | Verify status stages | Confirmed → Processing → Shipped → Out for Delivery → Delivered |
| 4 | Simulate status update | Status changes reflect in real-time |
| 5 | View estimated delivery time | ETA displayed based on status |
| 6 | Enable push notifications | Receive status update notifications |

**Order Status Flow:**
- Order Placed → Payment Confirmed → Processing → Packed → Shipped → Out for Delivery → Delivered

---

#### TC-MC-043: View Order History
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-043 |
| **Feature Area** | Order Tracking |
| **Title** | View completed and cancelled order history |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to My Orders | Orders screen displayed |
| 2 | Tap "History" tab | Completed orders listed |
| 3 | View delivered orders | Delivered orders with delivery date |
| 4 | View cancelled orders | Cancelled orders with reason |
| 5 | Tap on completed order | Full order details displayed |
| 6 | Tap "Reorder" button | Items added to cart |

---

#### TC-MC-044: Cancel Order After Placement
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-044 |
| **Feature Area** | Order Tracking |
| **Title** | Cancel order before processing/shipping |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Place a new order | Order confirmed |
| 2 | Go to My Orders > Active | Order listed as active |
| 3 | Tap on order | Order details displayed |
| 4 | Tap "Cancel Order" button | Cancellation reason selection |
| 5 | Select reason: "Changed my mind" | Reason selected |
| 6 | Confirm cancellation | Order cancelled, refund initiated |
| 7 | Verify order status | Status changed to "Cancelled" |
| 8 | Verify refund | Refund processed (for prepaid orders) |

**Cancellation Reasons:**
- Changed my mind
- Ordered by mistake
- Delivery time too long
- Found better price elsewhere
- Other

---

#### TC-MC-045: Delivery Boy Tracking
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-045 |
| **Feature Area** | Order Tracking |
| **Title** | Track delivery boy location on map |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Order status: "Out for Delivery" | Order at delivery stage |
| 2 | Open order details | Live tracking option available |
| 3 | Tap "Track Delivery" | Map view with delivery boy location |
| 4 | View real-time location | Delivery boy position updates |
| 5 | View estimated arrival | ETA based on distance |
| 6 | Call delivery boy | Call button initiates phone call |

**Device Requirements:**
- GPS enabled
- Location permissions granted

---

#### TC-MC-046: Order Receipt and Invoice
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-046 |
| **Feature Area** | Order Tracking |
| **Title** | Download order receipt and invoice |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open completed order details | Order details displayed |
| 2 | Tap "Download Invoice" | Invoice generation initiated |
| 3 | View invoice preview | PDF preview displayed |
| 4 | Tap "Share" | Share options displayed |
| 5 | Save to device | Invoice saved to Downloads |
| 6 | Verify invoice details | Order ID, items, amounts, tax details |

---

### 4.6 Subscription Management

---

#### TC-MC-047: Create New Subscription
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-047 |
| **Feature Area** | Subscription Management |
| **Title** | Create new product subscription |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to "Subscriptions" | Subscription screen displayed |
| 2 | Tap "Create New Subscription" | Product selection screen |
| 3 | Select product: "Fresh Milk 1L" | Product selected |
| 4 | Select quantity: 2 | Quantity set |
| 5 | Select frequency: "Daily" | Frequency selected |
| 6 | Select start date: Tomorrow | Start date set |
| 7 | Select delivery time: 7:00 AM | Time slot selected |
| 8 | Add delivery address | Address selected |
| 9 | Review and confirm | Subscription created successfully |
| 10 | Verify subscription in list | New subscription appears in list |

**Subscription Frequencies:**
- Daily
- Alternate Days
- Weekly (select days)
- Custom Schedule

---

#### TC-MC-048: Modify Existing Subscription
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-048 |
| **Feature Area** | Subscription Management |
| **Title** | Modify subscription quantity and schedule |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open existing subscription | Subscription details displayed |
| 2 | Tap "Edit" | Edit mode activated |
| 3 | Change quantity from 2 to 3 | Quantity updated |
| 4 | Change frequency to "Alternate Days" | Frequency updated |
| 5 | Change delivery time to 6:00 PM | Time updated |
| 6 | Save changes | Changes saved successfully |
| 7 | Verify updated subscription | New settings reflected |

---

#### TC-MC-049: Pause Subscription
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-049 |
| **Feature Area** | Subscription Management |
| **Title** | Pause subscription for specific dates |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open active subscription | Subscription details displayed |
| 2 | Tap "Pause Subscription" | Pause options displayed |
| 3 | Select pause duration: 7 days | Duration selected |
| 4 | Select pause start date: 3 days from now | Start date selected |
| 5 | Add reason: "Going on vacation" | Reason entered |
| 6 | Confirm pause | Subscription paused |
| 7 | Verify pause status | Status: "Paused until [date]" |

---

#### TC-MC-050: Resume Subscription
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-050 |
| **Feature Area** | Subscription Management |
| **Title** | Resume paused subscription |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open paused subscription | Subscription details with pause status |
| 2 | Tap "Resume Now" | Resume confirmation dialog |
| 3 | Confirm resumption | Subscription resumed immediately |
| 4 | Verify next delivery | Next delivery scheduled |
| 5 | Check order generation | New order created for next cycle |

---

#### TC-MC-051: Cancel Subscription
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-051 |
| **Feature Area** | Subscription Management |
| **Title** | Cancel active subscription |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open active subscription | Subscription details displayed |
| 2 | Tap "Cancel Subscription" | Cancellation reason selection |
| 3 | Select reason: "No longer needed" | Reason selected |
| 4 | Confirm cancellation | Subscription cancelled |
| 5 | Verify no future deliveries | No pending orders generated |
| 6 | Verify refund for prepaid | Prorated refund processed if applicable |

---

#### TC-MC-052: Subscription Payment Management
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-052 |
| **Feature Area** | Subscription Management |
| **Title** | Manage subscription payment method |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open subscription details | Details displayed |
| 2 | Tap "Payment Method" | Current payment method shown |
| 3 | Tap "Change Payment Method" | Available methods displayed |
| 4 | Select new bKash number | New number entered |
| 5 | Verify new number | OTP verification completed |
| 6 | Save new payment method | Method updated for future payments |
| 7 | Verify upcoming payment | Next payment uses new method |

---

### 4.7 User Profile

---

#### TC-MC-053: Update Profile Information
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-053 |
| **Feature Area** | User Profile |
| **Title** | Update user profile details |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile | Profile screen displayed |
| 2 | Tap "Edit Profile" | Edit mode activated |
| 3 | Update name: "Rahim Ahmed Khan" | Name updated |
| 4 | Update email: "newemail@email.com" | Email validated and updated |
| 5 | Change profile picture | Camera/gallery option shown |
| 6 | Select and crop new photo | Photo updated |
| 7 | Save changes | Changes saved successfully |
| 8 | Verify updates | Profile reflects new information |

---

#### TC-MC-054: Manage Delivery Addresses
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-054 |
| **Feature Area** | User Profile |
| **Title** | Add, edit, and delete delivery addresses |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile > Addresses | Address list displayed |
| 2 | Tap "Add New Address" | Address form displayed |
| 3 | Enter complete address details | Address added successfully |
| 4 | Set as default address | Default marker updated |
| 5 | Tap "Edit" on existing address | Edit form displayed |
| 6 | Modify address details | Changes saved |
| 7 | Tap "Delete" on address | Delete confirmation shown |
| 8 | Confirm deletion | Address removed from list |

---

#### TC-MC-055: Change Password
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-055 |
| **Feature Area** | User Profile |
| **Title** | Change account password |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile > Security | Security settings displayed |
| 2 | Tap "Change Password" | Password change form |
| 3 | Enter current password | Current password validated |
| 4 | Enter new password (8+ chars) | Strength indicator shown |
| 5 | Confirm new password | Match validation |
| 6 | Tap "Update Password" | Success message displayed |
| 7 | Verify with new password | Login with new password works |

**Test Data:**
- Current password: OldPass123!
- New password: NewPass456!

---

#### TC-MC-056: Language Preference Settings
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-056 |
| **Feature Area** | User Profile |
| **Title** | Change application language preference |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile > Settings | Settings screen displayed |
| 2 | Tap "Language" | Language options: English, বাংলা |
| 3 | Select "বাংলা" (Bengali) | Confirmation dialog shown |
| 4 | Confirm language change | App language changes to Bengali |
| 5 | Verify UI elements | All text in Bengali script |
| 6 | Switch back to English | Language toggles smoothly |
| 7 | Restart app | Preference persisted |

---

#### TC-MC-057: Notification Preferences
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-057 |
| **Feature Area** | User Profile |
| **Title** | Customize notification preferences |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile > Notifications | Notification settings displayed |
| 2 | Toggle "Order Updates" ON/OFF | Toggle works, preference saved |
| 3 | Toggle "Promotional Offers" ON/OFF | Toggle works, preference saved |
| 4 | Toggle "Subscription Reminders" ON/OFF | Toggle works, preference saved |
| 5 | Toggle "Delivery Notifications" ON/OFF | Toggle works, preference saved |
| 6 | Test disabled notification | No notification received for disabled type |

---

#### TC-MC-058: View Order Statistics
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-058 |
| **Feature Area** | User Profile |
| **Title** | View personal order statistics |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile | Profile screen displayed |
| 2 | View "My Statistics" section | Stats displayed |
| 3 | Verify total orders count | Total orders shown |
| 4 | Verify total amount spent | Amount in ৳ displayed |
| 5 | Verify favorite products | Most ordered products listed |
| 6 | View monthly breakdown | Orders by month chart |

---

### 4.8 Notifications (Push)

---

#### TC-MC-059: Push Notification - Order Confirmation
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-059 |
| **Feature Area** | Notifications (Push) |
| **Title** | Receive push notification for order confirmation |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Ensure push notifications enabled | Notifications permission granted |
| 2 | Place a new order | Order submitted |
| 3 | Wait for server processing | Push notification received |
| 4 | Verify notification content | "Order #12345 confirmed" |
| 5 | Tap notification | App opens to order details |
| 6 | Verify deep link | Correct order displayed |

---

#### TC-MC-060: Push Notification - Order Status Update
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-060 |
| **Feature Area** | Notifications (Push) |
| **Title** | Receive order status update notifications |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Have active order in processing | Order tracked |
| 2 | Wait for status change | Notification received |
| 3 | Verify status notification | "Order #12345 is out for delivery" |
| 4 | Tap notification | Opens order tracking page |
| 5 | Verify multiple status updates | Notifications for each status change |

**Notification Types:**
- Order Confirmed
- Payment Received
- Order Processing
- Out for Delivery
- Delivered
- Delivery Failed

---

#### TC-MC-061: Push Notification - Promotional Offers
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-061 |
| **Feature Area** | Notifications (Push) |
| **Title** | Receive promotional offer notifications |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Enable promotional notifications | Setting enabled |
| 2 | Server sends promotional push | Notification received |
| 3 | Verify notification content | Offer details, discount info |
| 4 | Verify rich media | Image loaded in notification |
| 5 | Tap notification | Opens promotional page/offers |
| 6 | Verify offer applies | Discount auto-applied or code shown |

---

#### TC-MC-062: Push Notification - Subscription Reminders
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-062 |
| **Feature Area** | Notifications (Push) |
| **Title** | Receive subscription delivery reminders |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Have active subscription | Subscription running |
| 2 | Day before delivery | Reminder notification received |
| 3 | Verify reminder content | "Your milk delivery is scheduled for tomorrow" |
| 4 | Tap to modify | Opens subscription management |
| 5 | Verify delivery day notification | "Your order is out for delivery" |

---

#### TC-MC-063: Notification Settings Management
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-063 |
| **Feature Area** | Notifications (Push) |
| **Title** | Enable/disable notification categories |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile > Notifications | Settings displayed |
| 2 | Disable "Promotional Offers" | Toggle OFF |
| 3 | Trigger promotional notification | No notification received |
| 4 | Enable "Promotional Offers" | Toggle ON |
| 5 | Trigger promotional notification | Notification received |
| 6 | Disable all notifications | System prompt shown |

---

#### TC-MC-064: Rich Push Notifications
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-064 |
| **Feature Area** | Notifications (Push) |
| **Title** | Receive rich media push notifications |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Trigger rich notification | Notification with image received |
| 2 | Verify notification appearance | Title, body, image visible |
| 3 | Expand notification | Full content visible |
| 4 | Verify action buttons | "Shop Now", "Dismiss" buttons |
| 5 | Tap action button | Specific action executed |

---

#### TC-MC-065: Notification Handling When App Open
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-065 |
| **Feature Area** | Notifications (Push) |
| **Title** | Handle push notification when app is in foreground |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Keep app open (foreground) | App active |
| 2 | Trigger push notification | In-app notification banner shown |
| 3 | Verify banner display | Toast/banner at top of screen |
| 4 | Tap banner | Relevant screen opened |
| 5 | Swipe away banner | Banner dismissed |

---

#### TC-MC-066: Deep Link from Notification
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-066 |
| **Feature Area** | Notifications (Push) |
| **Title** | Deep link navigation from push notification |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Receive order notification | Push notification displayed |
| 2 | Tap notification (app closed) | App launches |
| 3 | Verify deep link | Order details screen opened directly |
| 4 | Verify navigation stack | Back button goes to orders list |
| 5 | Test promotional deep link | Opens specific product/category |

---

### 4.9 Customer Support

---

#### TC-MC-067: Access Help Center/FAQ
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-067 |
| **Feature Area** | Customer Support |
| **Title** | Browse help center and FAQ section |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile > Help | Help center displayed |
| 2 | Browse FAQ categories | Categories listed |
| 3 | Tap "Ordering" category | Ordering FAQs displayed |
| 4 | Tap on a question | Answer expanded |
| 5 | Search for specific topic | Search results relevant |
| 6 | Tap "Was this helpful?" | Feedback recorded |

---

#### TC-MC-068: Contact Support via Chat
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-068 |
| **Feature Area** | Customer Support |
| **Title** | Initiate live chat with support agent |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile > Support | Support options displayed |
| 2 | Tap "Chat with Us" | Chat interface opened |
| 3 | Type initial message: "Order not delivered" | Message sent |
| 4 | Verify auto-response | Bot response received |
| 5 | Request human agent | Transferred to human support |
| 6 | Exchange messages | Real-time chat functional |
| 7 | Send image attachment | Image uploaded and sent |

---

#### TC-MC-069: Call Support Hotline
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-069 |
| **Feature Area** | Customer Support |
| **Title** | Call customer support hotline |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile > Support | Support options displayed |
| 2 | Tap "Call Support" | Confirmation dialog shown |
| 3 | Confirm call | Phone dialer opens with number |
| 4 | Verify phone number | +880 XXXX-XXXXXX displayed |
| 5 | Verify call timing | Support hours displayed |

---

#### TC-MC-070: Submit Support Ticket
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-070 |
| **Feature Area** | Customer Support |
| **Title** | Submit support ticket for issue |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to Profile > Support | Support screen displayed |
| 2 | Tap "Submit a Ticket" | Ticket form displayed |
| 3 | Select issue category: "Order Issue" | Category selected |
| 4 | Select order from dropdown | Order linked to ticket |
| 5 | Enter issue description | Description entered |
| 6 | Attach screenshot/image | Image attached |
| 7 | Submit ticket | Success message, ticket ID generated |
| 8 | View ticket status | Ticket appears in "My Tickets" |

---

#### TC-MC-071: Rate and Review Order
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-071 |
| **Feature Area** | Customer Support |
| **Title** | Rate delivered order and write review |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Navigate to delivered order | Order details displayed |
| 2 | Tap "Rate Order" | Rating screen displayed |
| 3 | Select star rating: 5 stars | Stars highlighted |
| 4 | Write review: "Great quality milk!" | Review text entered |
| 5 | Submit rating | Success message |
| 6 | Verify review posted | Review visible in order details |

---

### 4.10 Performance & Compatibility

---

#### TC-MC-072: App Launch Performance
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-072 |
| **Feature Area** | Performance & Compatibility |
| **Title** | Verify app launch time within acceptable limits |
| **Priority** | P1 - Critical |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Kill app completely | App not running |
| 2 | Tap app icon | App launch initiated |
| 3 | Measure cold start time | Launch time ≤ 3 seconds |
| 4 | Warm start: minimize and reopen | Launch time ≤ 1.5 seconds |
| 5 | Verify splash screen | Displayed during loading |
| 6 | Verify no ANR/crash | App launches successfully |

**Performance Criteria:**
- Cold start: ≤ 3 seconds
- Warm start: ≤ 1.5 seconds

---

#### TC-MC-073: API Response Time
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-073 |
| **Feature Area** | Performance & Compatibility |
| **Title** | Verify API response times under normal load |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login to app | Session established |
| 2 | Navigate to product catalog | Products loaded |
| 3 | Measure API response time | Category API ≤ 2 seconds |
| 4 | Search for products | Search API ≤ 1.5 seconds |
| 5 | Add to cart | Cart API ≤ 1 second |
| 6 | Checkout process | Checkout APIs ≤ 2 seconds each |

**API Performance Criteria:**
- GET requests: ≤ 2 seconds
- POST/PUT requests: ≤ 1.5 seconds
- Image loading: ≤ 3 seconds

---

#### TC-MC-074: Memory Usage and Battery
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-074 |
| **Feature Area** | Performance & Compatibility |
| **Title** | Monitor app memory usage and battery consumption |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Fresh app launch | Baseline memory recorded |
| 2 | Browse 50+ products | Memory increases gradually |
| 3 | Add items to cart | Memory stable |
| 4 | Complete checkout flow | Memory released appropriately |
| 5 | Background app for 1 hour | Memory usage optimized |
| 6 | Verify no memory leaks | Memory returns to baseline |

**Performance Criteria:**
- Memory usage: < 150MB average
- Battery: < 5% per hour of active use

---

#### TC-MC-075: Screen Rotation Handling
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-075 |
| **Feature Area** | Performance & Compatibility |
| **Title** | Handle device orientation changes |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Open app in portrait mode | Portrait layout displayed |
| 2 | Rotate device to landscape | Landscape layout adapted |
| 3 | Verify UI elements | All elements visible and functional |
| 4 | Rotate back to portrait | Portrait layout restored |
| 5 | Test on product detail page | Images and text reflow correctly |
| 6 | Test during checkout | Form fields maintain data |

---

#### TC-MC-076: App Update Handling
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-076 |
| **Feature Area** | Performance & Compatibility |
| **Title** | Handle app update scenarios gracefully |
| **Priority** | P2 - High |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Launch app with update available | Update prompt displayed |
| 2 | Tap "Update Now" | Redirects to Play Store/App Store |
| 3 | Or tap "Remind Me Later" | Prompt dismissed, app continues |
| 4 | Verify force update scenario | Critical update blocks app usage |
| 5 | Update app | App updates successfully |
| 6 | Verify data persistence | User data and preferences retained |

---

#### TC-MC-077: Cross-Platform Data Sync
| Field | Value |
|-------|-------|
| **Test Case ID** | TC-MC-077 |
| **Feature Area** | Performance & Compatibility |
| **Title** | Data synchronization across iOS and Android |
| **Priority** | P3 - Medium |

| Step | Action | Expected Result |
|------|--------|-----------------|
| 1 | Login on iOS device | Account synced |
| 2 | Add items to cart on iOS | Cart updated |
| 3 | Login on Android device with same account | Same account accessed |
| 4 | Verify cart sync | iOS cart items appear on Android |
| 5 | Place order from Android | Order created |
| 6 | Verify on iOS | Order appears in order history |

---

## 5. Test Execution Matrix

### Device Coverage Matrix

| Test Case ID | iPhone 15 Pro | iPhone 14 | iPhone 12 | Android 14 | Android 13 | Android 10 |
|--------------|---------------|-----------|-----------|------------|------------|------------|
| TC-MC-001 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-002 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-003 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-004 | - | - | - | ✓ | ✓ | ✓ |
| TC-MC-005 | ✓ | ✓ | ✓ | - | - | - |
| TC-MC-006 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-007 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-008 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-009 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-010 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-011 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-012 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-013 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-014 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-015 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-016 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-017 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-018 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-019 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-020 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-021 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-022 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-023 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-024 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-025 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-026 | ✓ | ✓ | - | ✓ | ✓ | - |
| TC-MC-027 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-028 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-029 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-030 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-031 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-032 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-033 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-034 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-035 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-036 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-037 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-038 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-039 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-040 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-041 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-042 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-043 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-044 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-045 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-046 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-047 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-048 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-049 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-050 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-051 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-052 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-053 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-054 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-055 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-056 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-057 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-058 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-059 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-060 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-061 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-062 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-063 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-064 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-065 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-066 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-067 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-068 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-069 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-070 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-071 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-072 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-073 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-074 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-075 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-076 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| TC-MC-077 | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |

### Language Coverage Matrix

| Test Case ID | English | Bengali (বাংলা) |
|--------------|---------|-----------------|
| TC-MC-001 | ✓ | ✓ |
| TC-MC-007 | ✓ | ✓ |
| TC-MC-016 | ✓ | ✓ |
| TC-MC-056 | ✓ | ✓ |
| UI Validation (All) | ✓ | ✓ |

---

## 6. Defect Tracking

### Defect Severity Levels

| Severity | Description |
|----------|-------------|
| **S1 - Critical** | App crash, data loss, security breach, payment failure |
| **S2 - High** | Major functionality broken, workaround not available |
| **S3 - Medium** | Partial functionality issue, workaround available |
| **S4 - Low** | Cosmetic issue, minor UI/UX problem |

### Defect Reporting Template

| Field | Description |
|-------|-------------|
| Defect ID | BUG-[Project]-[Number] |
| Test Case ID | Reference to failing test case |
| Title | Brief description of the issue |
| Severity | S1/S2/S3/S4 |
| Priority | P1/P2/P3/P4 |
| Environment | Device, OS version, App version |
| Steps to Reproduce | Detailed steps |
| Expected Result | What should happen |
| Actual Result | What actually happens |
| Attachments | Screenshots, videos, logs |
| Assigned To | Developer/Team |
| Status | New/In Progress/Fixed/Verified/Closed |

---

## Appendices

### Appendix A: Test Environment Setup

#### iOS Setup
1. Install Xcode latest version
2. Configure iOS simulators for supported devices
3. Install TestFlight for beta testing
4. Configure provisioning profiles

#### Android Setup
1. Install Android Studio
2. Configure Android Virtual Devices (AVDs)
3. Enable USB debugging on physical devices
4. Install APK for testing

### Appendix B: Test Data Requirements

#### User Accounts
- Test User 1: 01711111111 / Password123!
- Test User 2: 01722222222 / Password123!
- Test User 3: 01733333333 / Password123!

#### Payment Test Data (Sandbox)
- bKash: 01912345678 / OTP: 123456
- Nagad: 01812345678 / PIN: 1234

#### Product Test Data
- Fresh Milk 1L: ৳70
- Fresh Milk 500ml: ৳40
- Yogurt 500g: ৳120
- Cheese 200g: ৳180

### Appendix C: Network Testing Setup

#### Network Conditions Simulation
- **iOS**: Network Link Conditioner
- **Android**: Emulator network settings or Charles Proxy
- **Physical devices**: WiFi router with bandwidth controls

### Appendix D: Accessibility Testing Guidelines

#### VoiceOver (iOS) / TalkBack (Android)
- All interactive elements accessible
- Proper labels and hints
- Logical navigation order
- Dynamic content announcements

---

## Document Sign-off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Prepared By | QA Engineer | _____________ | January 31, 2026 |
| Reviewed By | QA Lead | _____________ | _______ |
| Approved By | Mobile Development Lead | _____________ | _______ |
| Approved By | Project Manager | _____________ | _______ |

---

**END OF DOCUMENT**

*Document ID: G-011 | Version: 1.0 | Date: January 31, 2026*
