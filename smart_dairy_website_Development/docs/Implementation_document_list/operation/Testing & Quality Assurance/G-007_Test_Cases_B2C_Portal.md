# SMART DAIRY LTD.
## TEST CASES - B2C E-COMMERCE PORTAL
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | G-007 |
| **Version** | 1.0 |
| **Date** | June 8, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Engineer |
| **Reviewer** | QA Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Test Case Overview](#2-test-case-overview)
3. [User Authentication Test Cases](#3-user-authentication-test-cases)
4. [Product Catalog Test Cases](#4-product-catalog-test-cases)
5. [Shopping Cart Test Cases](#5-shopping-cart-test-cases)
6. [Checkout Flow Test Cases](#6-checkout-flow-test-cases)
7. [Payment Gateway Test Cases](#7-payment-gateway-test-cases)
8. [Subscription Management Test Cases](#8-subscription-management-test-cases)
9. [Order Management Test Cases](#9-order-management-test-cases)
10. [Customer Account Test Cases](#10-customer-account-test-cases)
11. [Mobile App Test Cases](#11-mobile-app-test-cases)
12. [Negative & Edge Case Tests](#12-negative--edge-case-tests)
13. [Appendices](#13-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive test cases for the Smart Dairy B2C E-commerce Portal. The test cases ensure that all customer-facing e-commerce functionality works correctly, including product browsing, shopping cart, checkout, payments, subscriptions, and order management.

### 1.2 Scope

| Component | Coverage |
|-----------|----------|
| Customer Authentication | Registration, login, password reset, social login |
| Product Discovery | Search, filtering, categories, recommendations |
| Shopping Experience | Cart, wishlist, compare, quick view |
| Checkout Process | Address, delivery slots, payment selection |
| Payment Integration | bKash, Nagad, Rocket, Cards, COD |
| Subscriptions | Daily milk delivery, pause/resume, modifications |
| Order Management | Tracking, history, cancellations, returns |
| Customer Account | Profile, addresses, payment methods |
| Notifications | Email, SMS, push notifications |

### 1.3 Test Case Statistics

| Module | Total Cases | Critical | High | Medium | Low |
|--------|-------------|----------|------|--------|-----|
| User Authentication | 30 | 8 | 12 | 8 | 2 |
| Product Catalog | 35 | 6 | 14 | 12 | 3 |
| Shopping Cart | 25 | 5 | 10 | 8 | 2 |
| Checkout Flow | 30 | 8 | 12 | 8 | 2 |
| Payment Gateway | 40 | 12 | 16 | 10 | 2 |
| Subscription Management | 35 | 8 | 14 | 10 | 3 |
| Order Management | 25 | 6 | 10 | 7 | 2 |
| Customer Account | 20 | 4 | 8 | 6 | 2 |
| Mobile App | 25 | 6 | 10 | 7 | 2 |
| Negative & Edge Cases | 20 | 4 | 8 | 6 | 2 |
| **TOTAL** | **285** | **67** | **114** | **82** | **22** |

### 1.4 Test Environment

| Environment | URL | Purpose |
|-------------|-----|---------|
| QA | https://b2c-qa.smartdairybd.com | Functional testing |
| Staging | https://b2c-staging.smartdairybd.com | UAT |
| Production | https://shop.smartdairybd.com | Live validation |

---

## 2. USER AUTHENTICATION TEST CASES

### 2.1 Registration Tests

#### TC-B2C-001: Customer Registration with Email
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Customer Registration with Email |
| **Priority** | Critical |
| **Preconditions** | User not already registered with test email |

**Test Steps:**
1. Navigate to registration page
2. Enter valid name: "Rahim Khan"
3. Enter valid email: "rahim.khan.test@example.com"
4. Enter valid phone: "01712345678"
5. Enter password: "TestPass123!"
6. Confirm password: "TestPass123!"
7. Check terms and conditions checkbox
8. Click "Register" button

**Expected Results:**
- [ ] Registration form loads correctly
- [ ] All required fields marked with (*)
- [ ] Password strength indicator shows "Strong"
- [ ] Account created successfully
- [ ] Success message: "Account created! Please verify your email."
- [ ] Verification email sent to provided address
- [ ] User record created in database (status: pending_verification)

---

#### TC-B2C-002: Email Verification Process
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Email Verification Flow |
| **Priority** | Critical |
| **Preconditions** | Registration completed, email received |

**Test Steps:**
1. Open email client
2. Find verification email from Smart Dairy
3. Click verification link
4. Verify account activation

**Expected Results:**
- [ ] Email received within 2 minutes
- [ ] Email subject: "Verify your Smart Dairy account"
- [ ] Email contains branded template
- [ ] Verification link unique and time-limited (24 hours)
- [ ] Clicking link activates account
- [ ] Success page: "Your account is now verified"
- [ ] User can now log in

---

#### TC-B2C-003: Registration with Existing Email
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Registration with Duplicate Email |
| **Priority** | High |

**Test Steps:**
1. Attempt registration with existing email
2. Submit form
3. Observe error message

**Expected Results:**
- [ ] Form validation prevents submission
- [ ] Error message: "An account with this email already exists"
- [ ] Link to "Login" or "Forgot Password" provided
- [ ] No duplicate account created

---

#### TC-B2C-004: Phone OTP Registration
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Registration with Phone OTP |
| **Priority** | Critical |

**Test Steps:**
1. Enter phone number: "01712345679"
2. Request OTP
3. Enter received OTP
4. Complete registration

**Expected Results:**
- [ ] OTP sent within 30 seconds
- [ ] OTP is 6-digit numeric
- [ ] OTP expires after 5 minutes
- [ ] Registration completes after valid OTP
- [ ] Resend OTP option available after 60 seconds

---

### 2.2 Login Tests

#### TC-B2C-010: Valid Login
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Successful Login |
| **Priority** | Critical |
| **Preconditions** | Verified account exists |

**Test Steps:**
1. Navigate to login page
2. Enter valid email
3. Enter valid password
4. Click "Login"

**Expected Results:**
- [ ] Login page loads with email and password fields
- [ ] "Remember me" checkbox available
- [ ] Login successful
- [ ] Redirected to account dashboard or previous page
- [ ] Welcome message displayed
- [ ] Session created (cookie/localStorage)

---

#### TC-B2C-011: Invalid Login - Wrong Password
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Login with Incorrect Password |
| **Priority** | High |

**Test Steps:**
1. Enter valid email
2. Enter incorrect password
3. Click "Login"

**Expected Results:**
- [ ] Login fails
- [ ] Error message: "Invalid email or password"
- [ ] No indication which field is wrong (security)
- [ ] Account NOT locked after first attempt
- [ ] Login form remains with entered email

---

#### TC-B2C-012: Account Lockout After Failed Attempts
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Account Lockout Security |
| **Priority** | High |

**Test Steps:**
1. Enter valid email
2. Enter wrong password 5 consecutive times
3. Observe lockout behavior

**Expected Results:**
- [ ] Account locked after 5 failed attempts
- [ ] Error message: "Account temporarily locked. Please try again in 30 minutes."
- [ ] Or: "Account locked. Check your email to unlock."
- [ ] Unlock link sent via email
- [ ] Legitimate user can unlock account

---

#### TC-B2C-013: Password Reset Flow
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Password Reset Process |
| **Priority** | Critical |

**Test Steps:**
1. Click "Forgot Password"
2. Enter registered email
3. Submit request
4. Open reset email
5. Click reset link
6. Enter new password
7. Confirm reset

**Expected Results:**
- [ ] Password reset form accepts email
- [ ] Reset email sent within 2 minutes
- [ ] Reset link valid for 1 hour
- [ ] Link is single-use
- [ ] New password must meet complexity requirements
- [ ] Success message after reset
- [ ] Can login with new password
- [ ] Old password no longer works

---

### 2.3 Social Login Tests

#### TC-B2C-015: Google Login
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Google OAuth Login |
| **Priority** | Medium |

**Test Steps:**
1. Click "Sign in with Google"
2. Select Google account
3. Authorize Smart Dairy
4. Complete profile if first time

**Expected Results:**
- [ ] Google OAuth popup opens
- [ ] Account selection screen displays
- [ ] After authorization, logged in to Smart Dairy
- [ ] First-time users complete profile (phone, address)
- [ ] Email from Google used as account identifier

---

## 3. PRODUCT CATALOG TEST CASES

### 3.1 Product Browsing

#### TC-B2C-030: Product Category Navigation
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-001 |
| **Title** | Verify Product Category Pages |
| **Priority** | Critical |

**Test Steps:**
1. Navigate to shop homepage
2. Click "Fresh Milk" category
3. Verify products displayed
4. Check sub-category filters

**Expected Results:**
- [ ] Category page loads
- [ ] Category name displayed as H1
- [ ] Products filtered to show only Fresh Milk
- [ ] Product count displayed: "Showing 12 products"
- [ ] Sort and filter options available
- [ ] Breadcrumb: Home > Products > Fresh Milk

---

#### TC-B2C-031: Product Search with Autocomplete
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-003 |
| **Title** | Verify Product Search Functionality |
| **Priority** | Critical |

**Test Steps:**
1. Click search bar
2. Type "organi"
3. Wait for autocomplete
4. Select suggestion

**Expected Results:**
- [ ] Search bar accepts input
- [ ] Autocomplete suggestions appear after 2 characters
- [ ] Suggestions include: "Organic Milk", "Organic Yogurt"
- [ ] Clicking suggestion navigates to product
- [ ] Search results page shows relevant products
- [ ] "Did you mean?" for typos
- [ ] Empty search shows popular products

---

#### TC-B2C-032: Product Filter by Attributes
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-001 |
| **Title** | Verify Product Filtering |
| **Priority** | High |

**Test Steps:**
1. Navigate to product category
2. Apply price filter: ৳50 - ৳200
3. Apply type filter: "Organic"
4. Apply size filter: "1 Liter"

**Expected Results:**
- [ ] Filter sidebar/collapsible on mobile
- [ ] Active filters displayed as chips/tags
- [ ] Products update dynamically with filters
- [ ] URL updates with filter parameters
- [ ] Clear all filters option available
- [ ] Product count updates: "Showing 8 of 45 products"

---

#### TC-B2C-033: Product Sorting Options
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-001 |
| **Title** | Verify Product Sorting |
| **Priority** | Medium |

**Test Steps:**
1. Navigate to category with multiple products
2. Select sort option from dropdown
3. Verify product order

**Expected Results:**
| Sort Option | Expected Behavior |
|-------------|-------------------|
| Featured | Default order (manual/bestselling) |
| Price: Low to High | Ascending price order |
| Price: High to Low | Descending price order |
| Name: A-Z | Alphabetical order |
| Newest Arrivals | Recently added first |
| Best Selling | Highest sales first |
| Customer Rating | Highest rated first |

---

### 3.2 Product Detail Page

#### TC-B2C-040: Product Information Display
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-002 |
| **Title** | Verify Product Detail Page Content |
| **Priority** | Critical |

**Test Steps:**
1. Navigate to any product detail page
2. Verify all product information
3. Test image gallery

**Expected Results:**
- [ ] Product name prominently displayed
- [ ] Multiple product images in gallery
- [ ] Image zoom on click/hover
- [ ] Price: ৳95.00 (current price)
- [ ] Original price strikethrough if on sale
- [ ] Discount percentage displayed
- [ ] SKU displayed
- [ ] Availability status: "In Stock" / "Out of Stock"
- [ ] Short description
- [ ] Detailed description (tabs)
- [ ] Nutritional information table
- [ ] Storage instructions
- [ ] Shelf life
- [ ] Certifications (Organic, Halal)
- [ ] Customer reviews summary

---

#### TC-B2C-041: Product Variant Selection
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-002 |
| **Title** | Verify Product Variant Selection |
| **Priority** | High |

**Test Steps:**
1. Navigate to product with variants
2. Select different size
3. Verify price update

**Expected Results:**
- [ ] Variant selector dropdown/buttons visible
- [ ] Available sizes: 500ml, 1L, 2L
- [ ] Price updates when variant changed
- [ ] Stock status updates per variant
- [ ] Image may update for variant
- [ ] Selected variant highlighted

**Test Data - Saffron Organic Milk:**
| Size | Price | Stock |
|------|-------|-------|
| 500ml | ৳50 | In Stock |
| 1L | ৳95 | In Stock |
| 2L | ৳180 | Out of Stock |

---

## 4. SHOPPING CART TEST CASES

### 4.1 Cart Operations

#### TC-B2C-060: Add Product to Cart
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-004 |
| **Title** | Verify Add to Cart Functionality |
| **Priority** | Critical |

**Test Steps:**
1. Navigate to product page
2. Select quantity: 2
3. Click "Add to Cart"
4. Observe cart update

**Expected Results:**
- [ ] Success message: "Added to cart!"
- [ ] Cart icon updates with item count (badge)
- [ ] Mini-cart dropdown shows added item
- [ ] Product name, quantity, price displayed
- [ ] "View Cart" and "Checkout" buttons
- [ ] Continue shopping option
- [ ] Item persists on page refresh

---

#### TC-B2C-061: Update Cart Quantity
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-004 |
| **Title** | Verify Cart Quantity Update |
| **Priority** | High |

**Test Steps:**
1. Add product to cart
2. Go to cart page
3. Change quantity using +/- or input
4. Verify totals update

**Expected Results:**
- [ ] Quantity can be increased/decreased
- [ ] Subtotal updates automatically
- [ ] Maximum quantity limited by stock
- [ ] Error if quantity exceeds available stock
- [ ] "Update Cart" button if manual update required
- [ ] Cart total includes all items

---

#### TC-B2C-062: Remove Item from Cart
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-004 |
| **Title** | Verify Remove from Cart |
| **Priority** | High |

**Test Steps:**
1. Add multiple items to cart
2. Click remove (X) on one item
3. Confirm removal

**Expected Results:**
- [ ] Remove button visible for each item
- [ ] Confirmation prompt: "Remove this item?"
- [ ] Option to undo removal
- [ ] Cart total recalculates
- [ ] Empty cart message if last item removed

---

#### TC-B2C-063: Cart Persistence Across Sessions
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-004 |
| **Title** | Verify Cart Persistence |
| **Priority** | High |

**Test Steps:**
1. Add items to cart (not logged in)
2. Close browser
3. Reopen website
4. Check cart

**Expected Results:**
- [ ] Cart items saved in localStorage/cookies
- [ ] Items persist for 30 days (configurable)
- [ ] Cart merges when logging in
- [ ] No data loss between sessions

---

### 4.2 Cart Calculations

#### TC-B2C-065: Cart Total Calculation
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-004 |
| **Title** | Verify Cart Pricing Calculations |
| **Priority** | Critical |

**Test Steps:**
1. Add multiple products to cart
2. Apply coupon code
3. Verify all calculations

**Expected Results:**
| Field | Calculation |
|-------|-------------|
| Subtotal | Sum of (price × quantity) for all items |
| Discount | Coupon/sale discount amount |
| Delivery Fee | Based on location and order value |
| VAT (if applicable) | 5% of (subtotal - discount) |
| Total | Subtotal - Discount + Delivery + VAT |

**Test Data:**
| Item | Price | Qty | Subtotal |
|------|-------|-----|----------|
| Organic Milk 1L | ৳95 | 2 | ৳190 |
| Sweet Yogurt | ৳120 | 1 | ৳120 |
| **Subtotal** | | | **৳310** |
| Discount (10%) | | | -৳31 |
| Delivery | | | ৳50 |
| **Total** | | | **৳329** |

---

## 5. CHECKOUT FLOW TEST CASES

### 5.1 Checkout Process

#### TC-B2C-080: Complete Checkout - Registered User
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-001 |
| **Title** | Verify Full Checkout Flow |
| **Priority** | Critical |
| **Preconditions** | Logged in, items in cart, saved address |

**Test Steps:**
1. Go to cart
2. Click "Proceed to Checkout"
3. Select delivery address
4. Select delivery time slot
5. Select payment method
6. Review order
7. Place order

**Expected Results:**
- [ ] Checkout step indicator: Address → Delivery → Payment → Review
- [ ] Saved addresses available for selection
- [ ] Delivery slots available: Morning (7-9 AM), Evening (5-7 PM)
- [ ] Payment options displayed
- [ ] Order summary visible throughout
- [ ] Terms and conditions checkbox
- [ ] Order confirmation page with order number
- [ ] Confirmation email sent
- [ ] Confirmation SMS sent

---

#### TC-B2C-081: Guest Checkout
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-001 |
| **Title** | Verify Guest Checkout Flow |
| **Priority** | Critical |

**Test Steps:**
1. Add items to cart (not logged in)
2. Proceed to checkout
3. Select "Checkout as Guest"
4. Enter email, phone, address
5. Complete checkout

**Expected Results:**
- [ ] Guest checkout option available
- [ ] Email validation at checkout start
- [ ] Delivery address form with validation
- [ ] Option to create account after checkout
- [ ] Order confirmation to guest email
- [ ] Guest can track order via email link

---

#### TC-B2C-082: Add New Delivery Address
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-002 |
| **Title** | Verify New Address Addition at Checkout |
| **Priority** | High |

**Test Steps:**
1. At checkout, click "Add New Address"
2. Fill address form
3. Save address

**Expected Results:**
- [ ] Address form fields:
  - Full Name
  - Mobile Number
  - Address Line 1
  - Address Line 2 (optional)
  - Area/Thana
  - City
  - Postal Code
- [ ] Google Maps address picker
- [ ] Address type: Home/Office/Other
- [ ] Save for future orders checkbox
- [ ] Address validation (Bangladesh addresses)

---

#### TC-B2C-083: Delivery Slot Selection
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-003 |
| **Title** | Verify Delivery Time Slot Selection |
| **Priority** | High |

**Test Steps:**
1. Select delivery date
2. View available slots
3. Select preferred slot

**Expected Results:**
- [ ] Calendar shows next 7 days
- [ ] Available slots displayed per day
- [ ] Morning slot: 7:00 AM - 9:00 AM
- [ ] Evening slot: 5:00 PM - 7:00 PM
- [ ] Slots marked as available/unavailable
- [ ] Express delivery option (if available)

---

## 6. PAYMENT GATEWAY TEST CASES

### 6.1 bKash Payment Tests

#### TC-B2C-100: bKash Payment - Successful
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-002 |
| **Title** | Verify bKash Payment Success |
| **Priority** | Critical |

**Test Steps:**
1. At checkout, select bKash
2. Enter bKash number: 018XXXXXXXX
3. Click "Pay with bKash"
4. Enter OTP from bKash
5. Confirm payment

**Expected Results:**
- [ ] bKash payment option visible
- [ ] Number input with validation
- [ ] OTP sent to bKash number
- [ ] Payment processing indicator
- [ ] Success callback received
- [ ] Order status: "Payment Confirmed"
- [ ] bKash transaction ID recorded
- [ ] Confirmation SMS from bKash

---

#### TC-B2C-101: bKash Payment - Insufficient Balance
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-002 |
| **Title** | Verify bKash Insufficient Balance Handling |
| **Priority** | High |

**Test Steps:**
1. Select bKash payment
2. Enter valid bKash number with low balance
3. Attempt payment

**Expected Results:**
- [ ] Payment initiated
- [ ] bKash returns "Insufficient Balance" error
- [ ] User returned to payment selection
- [ ] Error message: "Insufficient balance. Please try another payment method."
- [ ] Order status remains "Pending Payment"
- [ ] Option to retry with different method

---

#### TC-B2C-102: bKash Payment - Wrong OTP
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-002 |
| **Title** | Verify bKash OTP Validation |
| **Priority** | High |

**Test Steps:**
1. Initiate bKash payment
2. Enter incorrect OTP
3. Verify error handling

**Expected Results:**
- [ ] Wrong OTP rejected
- [ ] Error: "Invalid OTP. Please try again."
- [ ] 3 retry attempts allowed
- [ ] Resend OTP option after 60 seconds
- [ ] After 3 failures, return to payment selection

---

### 6.2 Nagad Payment Tests

#### TC-B2C-110: Nagad Payment - Successful
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-002 |
| **Title** | Verify Nagad Payment Success |
| **Priority** | Critical |

**Test Steps:**
1. Select Nagad at checkout
2. Enter Nagad number
3. Confirm payment in Nagad app

**Expected Results:**
- [ ] Nagad payment flow initializes
- [ ] Merchant checkout API called
- [ ] User receives confirmation request on Nagad app
- [ ] PIN entry required on Nagad
- [ ] Payment confirmation received
- [ ] Order updated with payment status

---

### 6.3 Rocket Payment Tests

#### TC-B2C-120: Rocket Payment - USSD Flow
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-002 |
| **Title** | Verify Rocket USSD Payment Flow |
| **Priority** | High |

**Test Steps:**
1. Select Rocket payment
2. USSD code displayed: *322*MERCHANT*AMOUNT#
3. User dials code
4. Completes payment

**Expected Results:**
- [ ] Rocket option available in payment methods
- [ ] USSD code auto-generated
- [ ] Copy USSD button available
- [ ] Instructions for payment displayed
- [ ] Webhook updates order on completion
- [ ] Timeout after 15 minutes if not paid

---

### 6.4 Cash on Delivery Tests

#### TC-B2C-130: Cash on Delivery
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-002 |
| **Title** | Verify COD Order Placement |
| **Priority** | Critical |

**Test Steps:**
1. Select "Cash on Delivery"
2. Place order
3. Verify order confirmation

**Expected Results:**
- [ ] COD option available
- [ ] Additional fee displayed (if applicable): +৳30
- [ ] Order placed without prepayment
- [ ] Order status: "Confirmed - Cash on Delivery"
- [ ] Delivery instructions field available
- [ ] OTP verification for delivery

---

## 7. SUBSCRIPTION MANAGEMENT TEST CASES

### 7.1 Subscription Creation

#### TC-B2C-150: Create Milk Subscription
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-005 |
| **Title** | Verify Daily Milk Subscription Creation |
| **Priority** | Critical |

**Test Steps:**
1. Navigate to "Subscriptions" section
2. Select "Daily Fresh Milk 1L" plan
3. Choose quantity: 2 liters/day
4. Select delivery address
5. Choose time slot: 6-8 AM
6. Select payment method
7. Confirm subscription

**Expected Results:**
- [ ] Subscription plans displayed with pricing
- [ ] Monthly: ৳1,800 | Quarterly: ৳5,100 | Yearly: ৳19,440
- [ ] Quantity selector (1-5 liters)
- [ ] Delivery schedule calendar preview
- [ ] First payment processed
- [ ] Subscription ID generated
- [ ] Confirmation: "Your daily milk subscription is active!"

---

#### TC-B2C-151: Subscription Modification
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-005 |
| **Title** | Verify Subscription Modification |
| **Priority** | High |

**Test Steps:**
1. Go to "My Subscriptions"
2. Click on active subscription
3. Click "Modify Subscription"
4. Change quantity from 1L to 2L
5. Save changes

**Expected Results:**
- [ ] Current subscription details displayed
- [ ] Modify option available
- [ ] Can change: Quantity, Delivery time, Address
- [ ] New pricing calculated
- [ ] Changes effective from next billing cycle
- [ ] Confirmation message displayed

---

#### TC-B2C-152: Pause Subscription
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-005 |
| **Title** | Verify Subscription Pause Functionality |
| **Priority** | High |

**Test Steps:**
1. Go to subscription details
2. Click "Pause Subscription"
3. Select pause dates: July 1-10
4. Enter reason: "Vacation"
5. Confirm pause

**Expected Results:**
- [ ] Pause option clearly visible
- [ ] Date range selector for pause period
- [ ] Reason dropdown: Vacation, Financial, Other
- [ ] Maximum pause days displayed: 7 days per month
- [ ] Affected deliveries shown
- [ ] Confirmation: "Subscription paused from July 1-10"
- [ ] No deliveries scheduled for pause period
- [ ] Billing adjusted accordingly

---

#### TC-B2C-153: Resume Subscription
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-SHOP-005 |
| **Title** | Verify Subscription Resume |
| **Priority** | High |

**Test Steps:**
1. Navigate to paused subscription
2. Click "Resume Subscription"
3. Select resume date
4. Confirm

**Expected Results:**
- [ ] Resume button visible on paused subscription
- [ ] Option to resume immediately or schedule
- [ ] Deliveries resume from selected date
- [ ] Billing resumes accordingly
- [ ] Confirmation notification sent

---

## 8. ORDER MANAGEMENT TEST CASES

### 8.1 Order Tracking

#### TC-B2C-170: Order Status Tracking
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-003 |
| **Title** | Verify Order Status Tracking |
| **Priority** | Critical |

**Test Steps:**
1. Place an order
2. Go to "My Orders"
3. Click on order
4. View tracking details

**Expected Results:**
- [ ] Order list shows all orders
- [ ] Each order shows: Number, Date, Total, Status
- [ ] Status values: Pending, Confirmed, Processing, Shipped, Delivered, Cancelled
- [ ] Timeline view of order progress
- [ ] Estimated delivery date displayed
- [ ] Current status highlighted

---

#### TC-B2C-171: Real-time Delivery Tracking
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-003 |
| **Title** | Verify Live Delivery Tracking |
| **Priority** | High |

**Test Steps:**
1. Order out for delivery
2. Open order tracking
3. View live location

**Expected Results:**
- [ ] Map integration displays
- [ ] Delivery person location shown (if opted in)
- [ ] Estimated arrival time updates
- [ ] Delivery person contact info
- [ ] OTP required for delivery confirmation

---

### 8.2 Order Modifications

#### TC-B2C-175: Cancel Order
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-003 |
| **Title** | Verify Order Cancellation |
| **Priority** | High |

**Test Steps:**
1. Place order
2. Go to order details
3. Click "Cancel Order"
4. Select reason
5. Confirm cancellation

**Expected Results:**
- [ ] Cancel option available before processing
- [ ] Cancellation reasons dropdown
- [ ] Refund timeline displayed
- [ ] Confirmation required
- [ ] Status updated to "Cancelled"
- [ ] Refund initiated (if paid)

---

## 9. CUSTOMER ACCOUNT TEST CASES

### 9.1 Profile Management

#### TC-B2C-190: Update Profile Information
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-002 |
| **Title** | Verify Profile Update |
| **Priority** | High |

**Test Steps:**
1. Login to account
2. Go to "My Profile"
3. Update name, phone
4. Change password
5. Update preferences

**Expected Results:**
- [ ] Profile form loads with current data
- [ ] Name, email, phone editable
- [ ] Password change requires current password
- [ ] Email change requires verification
- [ ] Communication preferences: Email, SMS, WhatsApp
- [ ] Changes saved successfully

---

#### TC-B2C-191: Address Management
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-002 |
| **Title** | Verify Address Book Management |
| **Priority** | High |

**Test Steps:**
1. Go to "My Addresses"
2. Add new address
3. Set as default
4. Edit existing address
5. Delete address

**Expected Results:**
- [ ] List of saved addresses displayed
- [ ] Add new address button
- [ ] Each address: Type, Full address, Phone
- [ ] Default address marked
- [ ] Edit and delete options
- [ ] Maximum 5 addresses (configurable)

---

## 10. MOBILE APP TEST CASES

### 10.1 Mobile-Specific Features

#### TC-B2C-200: Push Notifications
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-005 |
| **Title** | Verify Push Notification Delivery |
| **Priority** | High |

**Test Steps:**
1. Install mobile app
2. Login
3. Enable notifications
4. Place order
5. Wait for notifications

**Expected Results:**
| Trigger | Notification Content |
|---------|---------------------|
| Order Placed | "Order #12345 confirmed! Track your delivery." |
| Out for Delivery | "Your order is on the way! ETA: 6:30 PM" |
| Delivered | "Order delivered! Rate your experience." |
| Subscription Renewal | "Your subscription renews tomorrow." |
| Promotional | "20% off on Yogurt this week!" |

---

#### TC-B2C-201: Biometric Login
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Fingerprint/Face ID Login |
| **Priority** | Medium |

**Test Steps:**
1. Enable biometric login in settings
2. Logout
3. Login with fingerprint

**Expected Results:**
- [ ] Biometric option available in settings
- [ ] Prompt for fingerprint/face on login
- [ ] Quick authentication
- [ ] Fallback to password if biometric fails

---

## 11. NEGATIVE & EDGE CASE TESTS

### 11.1 Error Handling

#### TC-B2C-220: Network Failure During Checkout
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-CHK-001 |
| **Title** | Verify Network Error Handling |
| **Priority** | High |

**Test Steps:**
1. Start checkout process
2. Disconnect network
3. Attempt to place order
4. Reconnect network

**Expected Results:**
- [ ] Error message: "Connection lost. Please check your internet."
- [ ] Cart contents preserved
- [ ] Form data not lost
- [ ] Retry option available
- [ ] Order can be completed after reconnect

---

#### TC-B2C-221: Concurrent Session Test
| Attribute | Value |
|-----------|-------|
| **Requirement ID** | FR-B2C-001 |
| **Title** | Verify Concurrent Login Handling |
| **Priority** | Medium |

**Test Steps:**
1. Login on Device A
2. Login same account on Device B
3. Check Device A session

**Expected Results:**
- [ ] Both sessions active (configurable)
- [ ] OR: Device A notified of new login
- [ ] Activity synced across devices
- [ ] Cart synced in real-time

---

### 11.2 Boundary Value Tests

| Test Case | Input | Expected Result |
|-----------|-------|-----------------|
| TC-B2C-230 | Quantity: 0 | Error: "Minimum quantity is 1" |
| TC-B2C-231 | Quantity: 1000 | Error: "Maximum quantity exceeded" |
| TC-B2C-232 | Password: 5 chars | Error: "Password must be at least 8 characters" |
| TC-B2C-233 | Phone: 12345 | Error: "Invalid phone number" |
| TC-B2C-234 | Email: no@at | Error: "Invalid email format" |

---

## 12. APPENDICES

### Appendix A: Test Data Sets

**Sample Customer Accounts:**
| Type | Email | Password | Status |
|------|-------|----------|--------|
| Verified | test.customer@example.com | TestPass123! | Active |
| New | new.user@example.com | - | Unverified |
| Locked | locked.user@example.com | WrongPass | Locked |

**Sample Products for Testing:**
| SKU | Name | Price | Stock |
|-----|------|-------|-------|
| SD-MILK-001 | Organic Milk 1L | ৳95 | 100 |
| SD-MILK-002 | Organic Milk 2L | ৳180 | 0 (Out of Stock) |
| SD-YOG-001 | Sweet Yogurt | ৳120 | 50 |

### Appendix B: Payment Gateway Test Credentials

| Gateway | Test Number | PIN/OTP |
|---------|-------------|---------|
| bKash Sandbox | 01812345678 | 123456 |
| Nagad Sandbox | 01912345678 | 1234 |
| Rocket Sandbox | 01712345678 | 1234 |

### Appendix C: Regression Test Suite

| # | Test Case | Priority |
|---|-----------|----------|
| 1 | Registration → Login → Browse → Add to Cart → Checkout → Payment | Critical |
| 2 | Subscription creation → Pause → Resume → Cancel | Critical |
| 3 | All payment methods (bKash, Nagad, Rocket, COD) | Critical |
| 4 | Order tracking end-to-end | High |
| 5 | Profile update and password change | High |

---

**END OF TEST CASES - B2C PORTAL**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | June 8, 2026 | QA Engineer | Initial version |
