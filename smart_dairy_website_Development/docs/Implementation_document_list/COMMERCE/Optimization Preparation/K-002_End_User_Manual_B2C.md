# Smart Dairy B2C End User Manual
**Document ID:** K-002  
**Project:** Smart Dairy Web Portal System  
**Version:** 1.0  
**Date:** January 31, 2026  
**Owner:** Tech Writer  
**Reviewer:** Product Manager  
**Status:** Draft  
**Due Date:** Week 36

---

## Document Control

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 31-Jan-2026 | Tech Writer | Initial Draft |
| 1.1 | TBD | Product Manager | Review Comments |
| 2.0 | TBD | Tech Writer | Final Version |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Getting Started](#2-getting-started)
3. [Browsing Products](#3-browsing-products)
4. [Shopping Cart](#4-shopping-cart)
5. [Checkout & Payment](#5-checkout--payment)
6. [Order Management](#6-order-management)
7. [Account Management](#7-account-management)
8. [Subscription Services](#8-subscription-services)
9. [Mobile App Guide](#9-mobile-app-guide)
10. [Payment Methods](#10-payment-methods)
11. [Troubleshooting](#11-troubleshooting)
12. [FAQ](#12-faq)
13. [Contact Support](#13-contact-support)

---

## 1. Introduction

### 1.1 Welcome to Smart Dairy B2C

Welcome to the Smart Dairy Business-to-Consumer (B2C) E-commerce Portal! This user manual will guide you through using our online platform to purchase fresh, organic dairy products delivered directly to your doorstep.

**What You Can Do:**
- Browse and purchase fresh dairy products
- Choose from multiple delivery options
- Pay securely using bKash, Nagad, or credit/debit cards
- Track your orders in real-time
- Subscribe to regular deliveries
- Manage your account and preferences

### 1.2 System Requirements

**For Web Browser:**
- Chrome 90+ or Firefox 88+ or Safari 14+ or Edge 90+
- JavaScript enabled
- Screen resolution: 1024x768 or higher
- Stable internet connection (broadband recommended)

**For Mobile App:**
- iOS 13.0+ or Android 8.0+
- 50MB free storage space
- Internet connection (Wi-Fi or 4G/LTE)

### 1.3 About Smart Dairy Products

**Our Product Categories:**
- **Milk Products:** Fresh cow milk, flavored milk, organic milk
- **Yoghurt:** Plain, flavored, Greek, probiotic yoghurt
- **Cheese:** Cheddar, mozzarella, specialty cheeses
- **Butter:** Salted, unsalted, flavored butter
- **Cream:** Fresh cream, whipping cream, sour cream
- **Dairy Desserts:** Puddings, custards, ice cream
- **Specialty Products:** Ghee, paneer, khoya

---

## 2. Getting Started

### 2.1 Registration

#### 2.1.1 Creating a New Account

**Step-by-Step:**

1. **Visit the Portal**
   - Go to: https://shop.smartdairy.com.bd
   - Click "Sign Up" button in the top right corner

2. **Fill Registration Form**
   ```
   Required Fields:
   - Full Name: Enter your complete legal name
   - Mobile Number: Bangladesh format (e.g., 017XXXXXXXX)
   - Email Address: Valid email for notifications
   - Password: Minimum 8 characters with 1 uppercase, 1 lowercase, 1 number
   
   Optional Fields:
   - Date of Birth
   - Gender
   ```

3. **Verify Mobile Number**
   - Enter the 6-digit OTP sent to your mobile
   - OTP is valid for 5 minutes
   - If expired, click "Resend OTP"

4. **Complete Profile**
   - Add delivery address
   - Set preferences (language, notifications)
   - Agree to Terms & Conditions
   - Click "Create Account"

**Success:** You will receive a welcome SMS and email with your account details.

#### 2.1.2 Account Types

| Account Type | Benefits | Eligibility |
|-------------|----------|-------------|
| **Standard** | Regular delivery, product access | All customers |
| **Premium** | Free delivery, priority support, exclusive offers | BDT 5,000+ monthly purchases |
| **VIP** | Priority delivery, dedicated support, special pricing | BDT 20,000+ monthly purchases |

### 2.2 Login

#### 2.2.1 Logging In

**Steps:**

1. Click "Sign In" button
2. Enter mobile number or email
3. Enter password
4. Click "Sign In"

**Login Options:**
- **Mobile + Password:** Primary login method
- **Email + Password:** Alternative method
- **Social Login:** Google, Facebook (optional)

#### 2.2.2 Forgot Password

**Resetting Your Password:**

1. Click "Forgot Password?" on login page
2. Enter registered mobile number or email
3. Receive OTP on mobile
4. Enter OTP
5. Set new password
6. Confirm new password

**Password Requirements:**
- Minimum 8 characters
- At least 1 uppercase letter
- At least 1 lowercase letter
- At least 1 number
- Cannot reuse last 3 passwords

### 2.3 First-Time Setup

#### 2.3.1 Adding Delivery Addresses

**Adding Your First Address:**

1. After login, you'll be prompted to add address
2. Fill in address details:
   ```
   Address Fields:
   - Address Line 1: House/Flat number, street name
   - Address Line 2: Area, landmark (optional)
   - City: Select from dropdown (Dhaka, Chittagong, etc.)
   - Area: Select area within city
   - Postal Code: Enter postal code
   - Delivery Instructions: Special notes (e.g., "Ring twice", "Leave with guard")
   - Label: Home, Office, Other
   ```

3. Set as default address (optional)
4. Click "Save Address"

**Address Validation:**
- Address will be checked against delivery zones
- You'll see available delivery time slots
- Minimum order amount may apply for some areas

#### 2.3.2 Setting Preferences

**Customize Your Experience:**

1. Go to "My Account" → "Preferences"
2. Configure:
   - Language: English or Bengali
   - Currency: BDT (fixed)
   - Notifications: SMS, Email, Push
   - Marketing: Subscribe to offers and promotions
   - Newsletter: Receive weekly product updates

---

## 3. Browsing Products

### 3.1 Homepage

#### 3.1.1 Homepage Sections

```
┌─────────────────────────────────────────────────────────────┐
│                    SMART DAIRY HEADER                    │
├─────────────────────────────────────────────────────────────┤
│  Search Bar [🔍]          Cart [🛒]  Account [👤]     │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │        FEATURED PRODUCTS (Carousel)              │   │
│  │   [Milk] [Yoghurt] [Cheese] [Butter]          │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │        CATEGORIES                               │   │
│  │  🥛 Milk Products  🍶 Yoghurt  🧀 Cheese     │   │
│  │  🧈 Butter  🍦 Desserts  🥛 Specialty      │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │        SPECIAL OFFERS                           │   │
│  │  [Buy 2 Get 1 Free]  [Free Delivery]        │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │        NEW ARRIVALS                            │   │
│  │  [Product 1]  [Product 2]  [Product 3]          │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

#### 3.1.2 Navigation Menu

**Main Navigation:**
- **Home:** Return to homepage
- **Shop:** Browse all products
- **Categories:** View product categories
- **Offers:** View current promotions
- **About Us:** Company information
- **Contact:** Contact information and form

**Quick Links:**
- Track Order
- Delivery Information
- Return Policy
- FAQ
- Blog

### 3.2 Product Search

#### 3.2.1 Using the Search Bar

**Basic Search:**
1. Click on search icon or press Ctrl+K
2. Type product name or keyword
3. Press Enter or click search button

**Search Examples:**
- "Fresh milk"
- "Cheddar cheese"
- "Yoghurt 1L"
- "Organic butter"

#### 3.2.2 Advanced Search Filters

**Available Filters:**

| Filter | Options | Description |
|--------|---------|-------------|
| **Category** | Milk, Yoghurt, Cheese, Butter, etc. | Product type |
| **Brand** | Smart Dairy, Partner Brands | Manufacturer |
| **Price Range** | BDT 0 - BDT 5000+ | Price filter slider |
| **Pack Size** | 250ml, 500ml, 1L, 2L, etc. | Product quantity |
| **Organic** | Yes, No | Organic certification |
| **In Stock** | Only show available | Availability filter |
| **Rating** | 4+ stars, 3+ stars | Customer rating |
| **Discount** | 10%+, 25%+, 50%+ | Discount percentage |

**Using Filters:**
1. Perform a search or browse category
2. Click "Filters" button
3. Select desired filters
4. Click "Apply Filters"
5. Results update automatically

**Saving Filter Combinations:**
- After applying filters, click "Save This Search"
- Name your saved search
- Access later from "My Account" → "Saved Searches"

### 3.3 Product Categories

#### 3.3.1 Category Navigation

**Category Tree:**
```
Dairy Products
├── Milk Products
│   ├── Fresh Cow Milk
│   │   ├── 250ml
│   │   ├── 500ml
│   │   ├── 1L
│   │   └── 2L
│   ├── Flavored Milk
│   │   ├── Chocolate
│   │   ├── Strawberry
│   │   └── Badam
│   └── Organic Milk
│       ├── 500ml
│       └── 1L
│
├── Yoghurt
│   ├── Plain Yoghurt
│   ├── Flavored Yoghurt
│   │   ├── Mango
│   │   ├── Strawberry
│   │   └── Peach
│   ├── Greek Yoghurt
│   └── Probiotic Yoghurt
│
├── Cheese
│   ├── Cheddar
│   ├── Mozzarella
│   ├── Cream Cheese
│   └── Specialty Cheese
│
├── Butter
│   ├── Salted Butter
│   ├── Unsalted Butter
│   └── Flavored Butter
│
├── Desserts
│   ├── Puddings
│   ├── Custards
│   └── Ice Cream
│
└── Specialty
    ├── Ghee
    ├── Paneer
    └── Khoya
```

#### 3.3.2 Category Pages

**Category Page Features:**
- **Category Banner:** Promotional image and description
- **Subcategories:** Quick navigation to subcategories
- **Featured Products:** Top products in category
- **Product Grid:** All products with pagination (20 per page)
- **Sort Options:**
  - Relevance (default)
  - Price: Low to High
  - Price: High to Low
  - Newest First
  - Best Selling
  - Rating (High to Low)

### 3.4 Product Details

#### 3.4.1 Product Information Page

**Page Layout:**
```
┌─────────────────────────────────────────────────────────────┐
│  ← Back to Results           Share ♥ Heart Compare         │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌──────────────┐  ┌──────────────────────────────┐     │
│  │              │  │ Product Name                 │     │
│  │   PRODUCT    │  │ Brand: Smart Dairy         │     │
│  │   IMAGES     │  │ SKU: SD-MILK-500         │     │
│  │              │  │ Rating: ★★★★☆ (4.2/5)   │     │
│  │   [Image 1]  │  │ 1,234 reviews            │     │
│  │   [Image 2]  │  │                            │     │
│  │   [Image 3]  │  │ Price: BDT 65.00          │     │
│  │   [Image 4]  │  │ Pack Size: 500ml           │     │
│  │              │  │ Stock: 124 available        │     │
│  │              │  │                            │     │
│  │   ⬅⬅⬅⬅     │  │ ★★★★★ (Add to Wishlist)    │     │
│  │              │  │                            │     │
│  └──────────────┘  └──────────────────────────────┘     │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐     │
│  │ Quantity: [ - ] 1 [ + ]   Add to Cart      │     │
│  └─────────────────────────────────────────────────────┘     │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐     │
│  │ TABS: Description | Reviews | Delivery Info    │     │
│  └─────────────────────────────────────────────────────┘     │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐     │
│  │ PRODUCT DESCRIPTION                              │     │
│  │                                                  │     │
│  │ Fresh, pure cow milk sourced from our own farms.   │     │
│  │ Pasteurized and homogenized for safety and quality.│     │
│  │ Contains essential nutrients: calcium, protein,     │     │
│  │ vitamins A, B12.                                    │     │
│  │                                                  │     │
│  │ Nutrition per 100ml:                               │     │
│  │ - Energy: 65 kcal                                   │     │
│  │ - Protein: 3.2g                                   │     │
│  │ - Fat: 3.5g                                      │     │
│  │ - Calcium: 120mg                                  │     │
│  │                                                  │     │
│  │ Storage: Keep refrigerated (4°C or below).           │     │
│  │ Shelf life: 4 days from opening.                   │     │
│  └─────────────────────────────────────────────────────┘     │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐     │
│  │ CUSTOMER REVIEWS (1,234)                     │     │
│  │                                                  │     │
│  │ ★★★★★ "Excellent quality!" - Rahim A. (2d ago) │     │
│  │ "Always fresh milk. Love the taste!" - Fatima (5d)│     │
│  │                                                  │     │
│  │ See all reviews →                             │     │
│  └─────────────────────────────────────────────────────┘     │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐     │
│  │ RELATED PRODUCTS                                  │     │
│  │ [Yoghurt 500ml] [Cheese 200g] [Butter 250g] │     │
│  └─────────────────────────────────────────────────────┘     │
└─────────────────────────────────────────────────────────────┘
```

#### 3.4.2 Product Information

**Key Product Details:**

| Information | Description |
|-------------|-------------|
| **Product Name** | Full product name |
| **Brand** | Manufacturer (Smart Dairy or partner) |
| **SKU** | Stock Keeping Unit (unique identifier) |
| **Price** | Current price in BDT |
| **Pack Size** | Quantity/weight of product |
| **Stock Status** | Available quantity or out of stock |
| **Rating** | Average customer rating (1-5 stars) |
| **Reviews Count** | Total number of reviews |
| **Delivery Time** | Estimated delivery for your area |
| **Return Policy** | Return eligibility for this product |

#### 3.4.3 Image Gallery

**Image Features:**
- Multiple product images (4-6 images)
- Zoom functionality (click to enlarge)
- Thumbnail navigation
- Image slider with arrows

#### 3.4.4 Product Actions

**Available Actions:**
- **Add to Cart:** Add selected quantity to cart
- **Buy Now:** Add to cart and proceed to checkout
- **Add to Wishlist:** Save for later purchase
- **Add to Compare:** Compare with other products
- **Share:** Share product via social media or email
- **Report Issue:** Report product quality or listing issue

---

## 4. Shopping Cart

### 4.1 Viewing Your Cart

#### 4.1.1 Accessing the Cart

**Ways to View Cart:**
1. Click cart icon (🛒) in header
2. Cart icon shows item count badge
3. Cart displays as a dropdown preview
4. Click "View Cart" to see full cart

#### 4.1.2 Cart Page

**Cart Page Layout:**
```
┌─────────────────────────────────────────────────────────────┐
│                    SHOPPING CART                    3 items │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ DELIVERY ADDRESS                           [Change]  │   │
│  │ Home: 123 Street Name, Area, City, Postal Code  │   │
│  │ Estimated Delivery: Tomorrow, 9 AM - 12 PM       │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ CART ITEMS                                        │   │
│  │                                                  │   │
│  │ ┌───────────────────────────────────────────────┐   │   │
│  │ │ [X]                            [Image]      │   │   │
│  │ │ Fresh Cow Milk 500ml                      │   │   │
│  │ │ SKU: SD-MILK-500                         │   │   │
│  │ │ BDT 65.00          [-] 2 [+]  BDT 130.00  │   │   │
│  │ │                                            │   │   │
│  │ │ In Stock ✅  Delivery by tomorrow  ❌ Remove  │   │   │
│  │ └───────────────────────────────────────────────┘   │   │
│  │                                                  │   │
│  │ ┌───────────────────────────────────────────────┐   │   │
│  │ │ [X]                            [Image]      │   │   │
│  │ │ Strawberry Yoghurt 500ml                  │   │   │
│  │ │ SKU: SD-YOG-500                          │   │   │
│  │ │ BDT 45.00          [-] 1 [+]  BDT 45.00   │   │   │
│  │ │                                            │   │   │
│  │ │ In Stock ✅  Delivery by tomorrow  ❌ Remove  │   │   │
│  │ └───────────────────────────────────────────────┘   │   │
│  │                                                  │   │
│  │ ┌───────────────────────────────────────────────┐   │   │
│  │ │ [X]                            [Image]      │   │   │
│  │ │ Cheddar Cheese 200g                       │   │   │
│  │ │ SKU: SD-CHD-200                         │   │   │
│  │ │ BDT 180.00         [-] 1 [+]  BDT 180.00  │   │   │
│  │ │                                            │   │   │
│  │ │ In Stock ✅  Delivery by tomorrow  ❌ Remove  │   │   │
│  │ └───────────────────────────────────────────────┘   │   │
│  │                                                  │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ORDER SUMMARY                                    │   │
│  │                                                  │   │
│  │ Subtotal (3 items)                      BDT 355.00 │   │
│  │ Delivery Fee                              BDT 40.00 │   │
│  │ Discount (Applied)                        BDT -20.00 │   │
│  │ ─────────────────────────────────────────────────    │   │
│  │ Total Amount                                BDT 375.00 │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ PROMO CODE                    [Apply Code]          │   │
│  │                                                  │   │
│  │ Available Offers:                               │   │
│  │ ✓ Free delivery on orders above BDT 500        │   │
│  │ ✓ Get 10% off on 5L milk pack              │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │                              [Continue Shopping]  │   │
│  │                    [Proceed to Checkout →]        │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

### 4.2 Managing Cart Items

#### 4.2.1 Updating Quantities

**Change Quantity:**
1. Click "+" button to increase quantity
2. Click "-" button to decrease quantity
3. Minimum quantity is 1
4. Maximum quantity is 99 per product
5. Price updates automatically

#### 4.2.2 Removing Items

**Remove Single Item:**
- Click "Remove" button next to item
- Confirmation dialog appears
- Click "Confirm" to remove

**Remove All Items:**
- Click "Clear Cart" at bottom of cart
- Confirmation dialog appears
- Click "Confirm" to clear entire cart

#### 4.2.3 Moving Items to Wishlist

**Save for Later:**
1. Click "Move to Wishlist" button
2. Item removed from cart
3. Item added to wishlist
4. Access wishlist from account menu

### 4.3 Applying Promo Codes

#### 4.3.1 Promo Code Types

| Code Type | Discount | Conditions |
|-----------|----------|------------|
| **Flat Discount** | BDT 50 off | Minimum order BDT 200 |
| **Percentage Discount** | 10% off | Minimum order BDT 500 |
| **Free Delivery** | No delivery fee | Minimum order BDT 300 |
| **Buy X Get Y** | Buy 2 Get 1 Free | Specific products only |
| **Category Discount** | 15% off category | Category-specific |

#### 4.3.2 Applying a Code

**Steps:**
1. Locate "Promo Code" section in cart
2. Enter promo code (case-sensitive)
3. Click "Apply Code"
4. Discount reflected in order summary

**Multiple Codes:**
- Only one promo code can be applied at a time
- To apply different code, remove current code first

**Code Expiration:**
- Check code validity period
- Expired codes cannot be applied
- You'll receive error message for invalid codes

### 4.4 Delivery Scheduling

#### 4.4.1 Delivery Time Slots

**Available Time Slots:**
- Morning: 7 AM - 10 AM
- Midday: 10 AM - 1 PM
- Afternoon: 1 PM - 4 PM
- Evening: 4 PM - 7 PM
- Night: 7 PM - 10 PM

**Delivery Information:**
- Same-day delivery available before 4 PM
- Next-day delivery for orders after 4 PM
- Sunday deliveries available (additional fee)
- Select preferred slot during checkout

#### 4.4.2 Delivery Fees

| Order Value | Delivery Fee |
|-------------|--------------|
| BDT 0 - BDT 299 | BDT 50 |
| BDT 300 - BDT 499 | BDT 40 |
| BDT 500 - BDT 999 | BDT 30 |
| BDT 1000+ | FREE |

**Special Delivery:**
- Express delivery: Additional BDT 30 (2-hour window)
- Sunday delivery: Additional BDT 20
- Time slot selection: Additional BDT 10

---

## 5. Checkout & Payment

### 5.1 Checkout Process

#### 5.1.1 Checkout Steps Overview

```
Checkout Flow:
┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐
│ Step 1  │→ │ Step 2  │→ │ Step 3  │→ │ Step 4  │→ │ Step 5  │
│Address  │  │ Delivery│  │Payment  │  │Review  │  │Confirm │
└─────────┘  └─────────┘  └─────────┘  └─────────┘  └─────────┘
```

#### 5.1.2 Step 1: Delivery Address

**Address Selection:**
1. Select from saved addresses
2. Or add new address
3. Set as default for future orders
4. Click "Continue to Delivery"

**Address Validation:**
- System checks if address is within delivery zone
- If outside zone, you'll be notified
- Delivery charges calculated based on zone

#### 5.1.3 Step 2: Delivery Options

**Delivery Options:**

| Option | Description | Timeframe | Fee |
|--------|-------------|-----------|-----|
| **Standard** | Regular delivery | Next day | As per fee schedule |
| **Express** | Priority delivery | Same day (if before 4 PM) | +BDT 30 |
| **Scheduled** | Choose specific slot | Select date/time | +BDT 10 |
| **Pickup** | Collect from store | Today or tomorrow | FREE |

**Select Delivery:**
1. Choose preferred delivery option
2. Select date (if scheduling)
3. Select time slot (if applicable)
4. Click "Continue to Payment"

#### 5.1.4 Step 3: Payment Method

**Available Payment Methods:**

| Method | Description | Fee |
|--------|-------------|------|
| **bKash** | Mobile wallet | FREE |
| **Nagad** | Mobile wallet | FREE |
| **SSLCommerz** | Credit/Debit Card | FREE |
| **Cash on Delivery** | Pay on delivery | +BDT 20 |

#### 5.1.5 Step 4: Review Order

**Order Review Page:**

```
┌─────────────────────────────────────────────────────────────┐
│                    ORDER REVIEW                         │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ DELIVERY INFORMATION                            │   │
│  │ Name: Rahim Ahmed                                 │   │
│  │ Address: 123 Street Name, Area, City              │   │
│  │ Phone: 017XXXXXXXX                                │   │
│  │ Delivery: Tomorrow, 10 AM - 1 PM                   │   │
│  │ Method: Standard                                   │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ORDER ITEMS (3)                                 │   │
│  │                                                  │   │
│  │ 1x Fresh Cow Milk 500ml        BDT 65.00       │   │
│  │ 1x Strawberry Yoghurt 500ml      BDT 45.00       │   │
│  │ 1x Cheddar Cheese 200g         BDT 180.00      │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ PAYMENT METHOD                                   │   │
│  │ ✓ bKash (Mobile: 017XXXXXXXX)                │   │
│  │ [Change Payment Method]                          │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ORDER SUMMARY                                   │   │
│  │                                                  │   │
│  │ Subtotal (3 items)                      BDT 355.00 │   │
│  │ Delivery Fee                              BDT 40.00 │   │
│  │ Payment Method Fee                         BDT 0.00  │   │
│  │ Discount (WELCOME10)                      BDT -35.50 │   │
│  │ ─────────────────────────────────────────────────    │   │
│  │ Total Amount                                BDT 359.50 │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ TERMS & CONDITIONS                             │   │
│  │ ✓ I agree to the Terms & Conditions              │   │
│  │ ✓ I agree to the Privacy Policy                │   │
│  │ ✓ I agree to the Return Policy                  │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  [← Back to Payment]      [Place Order →]              │
└─────────────────────────────────────────────────────────────┘
```

#### 5.1.6 Step 5: Place Order

**Final Confirmation:**
1. Review all order details
2. Accept terms and conditions
3. Click "Place Order"
4. Order confirmation displayed

### 5.2 Payment Methods

#### 5.2.1 bKash Payment

**Paying with bKash:**

1. Select "bKash" as payment method
2. Enter bKash mobile number
3. Click "Proceed to bKash"
4. Redirected to bKash payment page
5. Enter bKash PIN
6. Confirm payment
7. Redirected back to Smart Dairy
8. Order confirmed

**bKash Requirements:**
- bKash account registered in your name
- Sufficient balance in account
- bKash mobile number verified

**Troubleshooting bKash:**
- If redirected back without confirmation: Check bKash app for transaction
- If payment failed: Try again or use different method
- If timeout occurs: Contact bKash customer care (16247)

#### 5.2.2 Nagad Payment

**Paying with Nagad:**

1. Select "Nagad" as payment method
2. Enter Nagad mobile number
3. Click "Proceed to Nagad"
4. Redirected to Nagad payment page
5. Enter Nagad PIN
6. Confirm payment
7. Redirected back to Smart Dairy
8. Order confirmed

**Nagad Requirements:**
- Nagad account registered in your name
- Sufficient balance in account
- Nagad mobile number verified

**Troubleshooting Nagad:**
- If redirected back without confirmation: Check Nagad app for transaction
- If payment failed: Try again or use different method
- If timeout occurs: Contact Nagad customer care (16123)

#### 5.2.3 SSLCommerz (Card Payment)

**Paying with Credit/Debit Card:**

1. Select "SSLCommerz" as payment method
2. Click "Proceed to Payment"
3. Redirected to SSLCommerz secure payment page
4. Enter card details:
   ```
   Card Details:
   - Card Number: 16-digit card number
   - Expiry Date: MM/YY
   - CVV: 3-digit security code
   - Cardholder Name: Name on card
   ```
5. Click "Pay Now"
6. 3D Secure verification (if enabled)
7. Payment processed
8. Redirected back to Smart Dairy
9. Order confirmed

**Card Payment Features:**
- Secure SSL/TLS encryption
- 3D Secure for additional security
- Support for Visa, MasterCard, American Express
- Multiple payment attempts allowed

**Troubleshooting Card Payment:**
- If card declined: Check card balance and limit
- If 3D Secure fails: Contact bank
- If timeout occurs: Try again or use different method
- For multiple failed attempts: Contact bank

#### 5.2.4 Cash on Delivery (COD)

**Paying Cash on Delivery:**

1. Select "Cash on Delivery" as payment method
2. Note: Additional BDT 20 COD fee applies
3. Click "Proceed to Checkout"
4. Complete order
5. Pay cash to delivery agent

**COD Notes:**
- Exact amount appreciated
- Change limited up to BDT 100
- Delivery agent will provide receipt
- Ensure someone available at delivery address

### 5.3 Order Confirmation

#### 5.3.1 Confirmation Page

**After Placing Order:**

```
┌─────────────────────────────────────────────────────────────┐
│              ✓ ORDER PLACED SUCCESSFULLY              │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Thank you, Rahim Ahmed!                                 │
│                                                             │
│  Your order #ORD-2026-001234 has been placed.           │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ORDER DETAILS                                     │   │
│  │                                                  │   │
│  │ Order Number: ORD-2026-001234                 │   │
│  │ Order Date: January 31, 2026                   │   │
│  │ Order Time: 11:30 AM                         │   │
│  │ Total Amount: BDT 359.50                      │   │
│  │ Payment Method: bKash                          │   │
│  │ Payment Status: Paid ✓                        │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ DELIVERY SCHEDULE                                │   │
│  │                                                  │   │
│  │ Delivery Date: February 1, 2026               │   │
│  │ Delivery Time: 10 AM - 1 PM                    │   │
│  │ Delivery Address: 123 Street Name, Area, City     │   │
│  │ Delivery Contact: 017XXXXXXXX                   │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ORDER ITEMS (3)                                 │   │
│  │                                                  │   │
│  │ 1x Fresh Cow Milk 500ml                        │   │
│  │ 2x Strawberry Yoghurt 500ml                      │   │
│  │ 1x Cheddar Cheese 200g                         │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ WHAT'S NEXT                                      │   │
│  │                                                  │   │
│  │ ✓ Order confirmation sent to your mobile           │   │
│  │ ✓ Email confirmation sent to your email            │   │
│  │ ✓ Order tracking available in My Orders          │   │
│  │                                                  │   │
│  │ You will receive updates:                         │   │
│  │ • When order is picked up                       │   │
│  │ • When order is out for delivery               │   │
│  │ • When order is delivered                        │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ QUICK ACTIONS                                    │   │
│  │                                                  │   │
│  │ [Track Order]  [View Order History]  [Shop More] │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

#### 5.3.2 Confirmation Notifications

**You Will Receive:**
1. **SMS Confirmation:** Order number and details
2. **Email Confirmation:** Detailed receipt
3. **WhatsApp:** Order status (if enabled)
4. **App Push:** Real-time updates (if app installed)

**Confirmation Includes:**
- Order number
- Order date and time
- Items ordered
- Total amount paid
- Delivery date and time
- Payment method used

---

## 6. Order Management

### 6.1 Viewing Orders

#### 6.1.1 My Orders Page

**Accessing Your Orders:**
1. Click on account icon (👤)
2. Select "My Orders" from menu
3. View all your orders

**Order List Page:**
```
┌─────────────────────────────────────────────────────────────┐
│                    MY ORDERS                           5 orders│
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Filter: [All Orders ▼]  Sort: [Newest First ▼]     │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ORDER #ORD-2026-001234                          │   │
│  │                                                  │   │
│  │ Items: 3 items                         │   │
│  │ Total: BDT 359.50                           │   │
│  │                                                  │   │
│  │ Status: Out for Delivery 🚚                      │   │
│  │ Date: Feb 1, 2026                    │   │
│  │                                                  │   │
│  │ [Track Order]  [View Details]  [Reorder]        │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ORDER #ORD-2026-001189                          │   │
│  │                                                  │   │
│  │ Items: 2 items                         │   │
│  │ Total: BDT 120.00                           │   │
│  │                                                  │   │
│  │ Status: Delivered ✓                        │   │
│  │ Date: Jan 28, 2026                   │   │
│  │                                                  │   │
│  │ [Track Order]  [View Details]  [Reorder]  [Rate]│   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  [Load More]                                             │
└─────────────────────────────────────────────────────────────┘
```

#### 6.1.2 Order Status Meanings

| Status | Icon | Description |
|--------|------|-------------|
| **Pending** | ⏳ | Order received, awaiting processing |
| **Confirmed** | ✓ | Order confirmed, being prepared |
| **Packed** | 📦 | Order packed, awaiting pickup |
| **Shipped** | 🚚 | Order picked up, on the way |
| **Out for Delivery** | 🚛 | Order at delivery location |
| **Delivered** | ✅ | Order delivered successfully |
| **Cancelled** | ❌ | Order cancelled by customer or system |
| **Returned** | ↩️ | Order returned by customer |

### 6.2 Tracking Orders

#### 6.2.1 Order Tracking Page

**Track Your Order:**
1. From order list, click "Track Order"
2. Or go to "Track Order" from menu
3. Enter order number and mobile
4. View tracking details

**Tracking Timeline:**
```
┌─────────────────────────────────────────────────────────────┐
│              ORDER TRACKING                          │
│              ORD-2026-001234                          │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Current Status: Out for Delivery 🚛                   │
│  Estimated Delivery: Today, 11:30 AM - 12:30 PM       │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ TRACKING TIMELINE                              │   │
│  │                                                  │   │
│  │ ✓ Order Confirmed                              │   │
│  │   Jan 31, 11:30 AM                             │   │
│  │                                                  │   │
│  │ ✓ Order Packed                                 │   │
│  │   Feb 1, 8:00 AM                               │   │
│  │                                                  │   │
│  │ ✓ Picked Up by Delivery Agent                  │   │
│  │   Feb 1, 9:30 AM                               │   │
│  │   Agent: Karim (01812345678)                 │   │
│  │                                                  │   │
│  │ → Out for Delivery                             │   │
│  │   Feb 1, 10:15 AM                              │   │
│  │   Location: Near your address                    │   │
│  │                                                  │   │
│  │ ○ Delivered                                     │   │
│  │   Expected: Feb 1, 11:30 AM - 12:30 PM        │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ DELIVERY AGENT                                 │   │
│  │                                                  │   │
│  │ Name: Karim Rahman                               │   │
│  │ Phone: 01812345678                              │   │
│  │ Vehicle: Motorcycle                             │   │
│  │                                                  │   │
│  │ [Call Agent]  [Message Agent]                   │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

#### 6.2.2 Real-Time Updates

**Receive Updates Via:**
- SMS: Delivery status updates
- WhatsApp: Real-time location tracking
- App Push: Notification on status change
- Email: Summary updates

**Notifications:**
1. When order is confirmed
2. When order is packed
3. When delivery agent picks up
4. When order is out for delivery
5. When order is delivered

### 6.3 Order Details

#### 6.3.1 Viewing Order Details

**Order Details Page:**

```
┌─────────────────────────────────────────────────────────────┐
│              ORDER DETAILS                          │
│              ORD-2026-001234                          │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ORDER INFORMATION                                │   │
│  │                                                  │   │
│  │ Order Number: ORD-2026-001234                 │   │
│  │ Order Date: January 31, 2026                   │   │
│  │ Order Time: 11:30 AM                         │   │
│  │ Status: Out for Delivery 🚛                      │   │
│  │ Payment Method: bKash                          │   │
│  │ Payment ID: BKSH-123456789                      │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ DELIVERY INFORMATION                            │   │
│  │                                                  │   │
│  │ Name: Rahim Ahmed                                 │   │
│  │ Address: 123 Street Name, Area, City              │   │
│  │ Phone: 017XXXXXXXX                                │   │
│  │ Date: February 1, 2026                          │   │
│  │ Time: 10 AM - 1 PM                             │   │
│  │ Instructions: Ring twice                          │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ORDER ITEMS                                     │   │
│  │                                                  │   │
│  │ 1x Fresh Cow Milk 500ml              BDT 65.00  │   │
│  │ 1x Strawberry Yoghurt 500ml          BDT 45.00  │   │
│  │ 1x Cheddar Cheese 200g               BDT 180.00 │   │
│  │ ─────────────────────────────────────────────────    │   │
│  │ Subtotal (3 items)                   BDT 355.00 │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ PAYMENT SUMMARY                                │   │
│  │                                                  │   │
│  │ Subtotal                                BDT 355.00 │   │
│  │ Delivery Fee                              BDT 40.00 │   │
│  │ Discount (WELCOME10)                    BDT -35.50 │   │
│  │ ─────────────────────────────────────────────────    │   │
│  │ Total Amount                             BDT 359.50 │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  [Download Invoice]  [Print Invoice]  [Reorder]        │
└─────────────────────────────────────────────────────────────┘
```

### 6.4 Reordering

#### 6.4.1 Quick Reorder

**Reorder Previous Orders:**
1. Go to "My Orders"
2. Find order you want to repeat
3. Click "Reorder" button
4. Items added to cart
5. Review and checkout

**What Gets Reordered:**
- All items from previous order
- Same quantities
- Current prices (may differ from previous order)

#### 6.4.2 Order Templates

**Save Frequently Ordered Items:**
1. Go to order details
2. Click "Save as Template"
3. Name your template (e.g., "Weekly Milk Order")
4. Template saved to your account
5. Access from "My Account" → "Order Templates"

**Using Templates:**
1. Go to "Order Templates"
2. Select template
3. Adjust quantities if needed
4. Add to cart
5. Checkout

### 6.5 Cancellation & Returns

#### 6.5.1 Cancelling Orders

**Cancellation Policy:**
- Cancel within 1 hour of placing order: Full refund
- Cancel 1-4 hours after: 10% cancellation fee
- Cancel 4+ hours after: Cannot cancel (use return process)

**How to Cancel:**
1. Go to "My Orders"
2. Find order to cancel
3. Click "Cancel Order"
4. Select cancellation reason
5. Submit cancellation request
6. Receive confirmation

**Cancellation Reasons:**
- Changed mind
- Wrong items ordered
- Delivery timing not suitable
- Found better price elsewhere
- Other (specify)

#### 6.5.2 Returning Items

**Return Policy:**
- Dairy products: Return within 24 hours of delivery
- Non-perishable items: Return within 7 days
- Item must be unopened and in original packaging
- Proof of purchase required

**Return Process:**
1. Go to "My Orders"
2. Find order with item to return
3. Click "Return Item"
4. Select items to return
5. Select return reason
6. Upload photos (if damaged/wrong item)
7. Submit return request
8. Receive return confirmation

**Return Reasons:**
- Damaged product
- Wrong item delivered
- Expired product
- Quality issue
- Other (specify)

**Refund Process:**
- Return inspected within 48 hours
- Refund processed within 5-7 business days
- Refund to original payment method
- BDT 30 pickup fee may apply for non-defective returns

---

## 7. Account Management

### 7.1 Profile Settings

#### 7.1.1 Personal Information

**Update Your Profile:**
1. Go to "My Account"
2. Select "Profile Settings"
3. Edit information:
   ```
   Profile Fields:
   - Full Name: Legal name
   - Mobile Number: Cannot change primary number
   - Email: Contact email
   - Date of Birth: Optional
   - Gender: Optional
   ```
4. Click "Save Changes"

**Verification Required:**
- Changing mobile: Verify with OTP
- Changing email: Verify with email link

#### 7.1.2 Address Book

**Managing Addresses:**
1. Go to "My Account" → "Address Book"
2. View all saved addresses
3. Actions available:
   - **Edit:** Modify address details
   - **Delete:** Remove address
   - **Set Default:** Make primary address
   - **Add New:** Add new address

**Address Limits:**
- Maximum 5 addresses per account

### 7.2 Payment Methods

#### 7.2.1 Saved Payment Methods

**Managing Payment Options:**
1. Go to "My Account" → "Payment Methods"
2. View saved methods:
   - bKash accounts (up to 3)
   - Nagad accounts (up to 3)
   - Credit/Debit cards (up to 5)

**Actions:**
- **Add:** Link new payment method
- **Edit:** Update card expiry
- **Delete:** Remove payment method
- **Set Default:** Primary payment option

**Security Note:**
- Card numbers stored securely with SSLCommerz
- Only last 4 digits displayed
- Full card required for each transaction

#### 7.2.2 Adding New Payment Method

**Add bKash:**
1. Click "Add bKash Account"
2. Enter mobile number
3. Receive OTP
4. Verify with OTP
5. Account linked

**Add Credit/Debit Card:**
1. Click "Add Card"
2. Enter card details (during checkout)
3. Save for future use
4. Card stored securely with SSLCommerz

### 7.3 Notification Settings

#### 7.3.1 Managing Notifications

**Notification Preferences:**
1. Go to "My Account" → "Notifications"
2. Configure preferences:
   ```
   Notification Types:
   - Order Updates: SMS, Email, Push, WhatsApp
   - Promotional Offers: SMS, Email, Push
   - Account Alerts: SMS, Email, Push
   - Newsletter: Email only
   ```

**Opting Out:**
- Unsubscribe from all: Disable all notifications
- Unsubscribe specific: Disable selected types
- Marketing only: Keep order updates, stop promotions

### 7.4 Privacy & Security

#### 7.4.1 Password Management

**Change Password:**
1. Go to "My Account" → "Security"
2. Click "Change Password"
3. Enter current password
4. Enter new password
5. Confirm new password
6. Click "Update Password"

**Password Requirements:**
- Minimum 8 characters
- At least 1 uppercase
- At least 1 lowercase
- At least 1 number
- Cannot reuse last 3 passwords

#### 7.4.2 Two-Factor Authentication

**Enable 2FA:**
1. Go to "My Account" → "Security"
2. Enable "Two-Factor Authentication"
3. Select method:
   - SMS OTP (recommended)
   - Authenticator App (Google Authenticator)
4. Verify setup with test login

**2FA for Sensitive Actions:**
- Password changes
- Adding new payment methods
- Changing email/mobile
- Large orders (BDT 5000+)

#### 7.4.3 Account Deletion

**Delete Account:**
1. Go to "My Account" → "Privacy"
2. Click "Delete Account"
3. Read warning message
4. Confirm deletion
5. Provide reason (optional)
6. Submit deletion request

**Account Deletion:**
- Takes 30 days to complete
- Data removed from active systems
- Cannot be undone
- Order history kept for 7 years (legal requirement)

---

## 8. Subscription Services

### 8.1 Subscribing to Products

#### 8.1.1 Subscription Options

**Available Subscriptions:**

| Subscription Type | Frequency | Discount | Minimum Order |
|-----------------|-----------|----------|----------------|
| **Daily** | Every day | 10% | BDT 200 |
| **Weekly** | Every week | 8% | BDT 500 |
| **Bi-Weekly** | Every 2 weeks | 6% | BDT 500 |
| **Monthly** | Every month | 5% | BDT 1000 |

#### 8.1.2 Creating a Subscription

**Subscribe to a Product:**
1. Find product you want regularly
2. Click "Subscribe" button
3. Configure subscription:
   ```
   Subscription Settings:
   - Quantity: Number of items per delivery
   - Frequency: Daily, weekly, bi-weekly, monthly
   - Start Date: First delivery date
   - Delivery Time: Preferred time slot
   - Payment Method: Auto-pay or pay-per-delivery
   ```
4. Review total per delivery
5. Confirm subscription

**Subscription Benefits:**
- Automatic deliveries (no need to reorder)
- Discount on subscribed products
- Priority delivery
- Flexible schedule changes
- Easy pause/cancel

### 8.2 Managing Subscriptions

#### 8.2.1 My Subscriptions Page

**View Your Subscriptions:**
1. Go to "My Account" → "My Subscriptions"
2. View all active subscriptions
3. Manage each subscription

**Subscription Information:**
```
┌─────────────────────────────────────────────────────────────┐
│              MY SUBSCRIPTIONS                         2 active │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ SUBSCRIPTION: Fresh Milk Daily                     │   │
│  │                                                  │   │
│  │ Product: Fresh Cow Milk 500ml (x2)              │   │
│  │ Frequency: Daily                                    │   │
│  │ Quantity: 2 bottles per day                        │   │
│  │ Discount: 10%                                      │   │
│  │ Price per delivery: BDT 117.00                     │   │
│  │                                                  │   │
│  │ Next Delivery: Feb 2, 2026, 7-10 AM              │   │
│  │ Delivery Address: Home                              │   │
│  │ Payment: Auto-pay via bKash                        │   │
│  │                                                  │   │
│  │ Status: Active ✓                                   │   │
│  │                                                  │   │
│  │ [Edit]  [Pause]  [Skip Delivery]  [Cancel]     │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ SUBSCRIPTION: Weekly Yoghurt Pack                 │   │
│  │                                                  │   │
│  │ Product: Mixed Yoghurt Pack (4 bottles)            │   │
│  │ Frequency: Weekly                                   │   │
│  │ Quantity: 1 pack per week                          │   │
│  │ Discount: 8%                                       │   │
│  │ Price per delivery: BDT 165.60                     │   │
│  │                                                  │   │
│  │ Next Delivery: Feb 5, 2026, 10 AM-1 PM          │   │
│  │ Delivery Address: Office                             │   │
│  │ Payment: Pay-per-delivery via COD                  │   │
│  │                                                  │   │
│  │ Status: Active ✓                                   │   │
│  │                                                  │   │
│  │ [Edit]  [Pause]  [Skip Delivery]  [Cancel]     │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

#### 8.2.2 Modifying Subscriptions

**Edit Subscription:**
1. Click "Edit" on subscription
2. Change:
   - Quantity (increase/decrease)
   - Frequency (daily → weekly, etc.)
   - Delivery day/time
   - Delivery address
   - Payment method
3. Click "Update Subscription"

**Skip Delivery:**
1. Click "Skip Delivery"
2. Select date(s) to skip
3. Confirm skip
4. Rescheduled to next regular delivery

**Pause Subscription:**
1. Click "Pause"
2. Select pause duration:
   - 1 week
   - 2 weeks
   - 1 month
   - Until specified date
3. Confirm pause
4. Delivery resumes after pause period

**Cancel Subscription:**
1. Click "Cancel"
2. Select cancellation reason
3. Confirm cancellation
4. Final delivery processed
5. No further charges

---

## 9. Mobile App Guide

### 9.1 App Installation

#### 9.1.1 Downloading the App

**iOS App:**
1. Open App Store
2. Search "Smart Dairy"
3. Tap "Get" or download icon
4. Wait for installation
5. Open app

**Android App:**
1. Open Google Play Store
2. Search "Smart Dairy"
3. Tap "Install"
4. Wait for installation
5. Open app

**Alternative Download:**
- Visit shop.smartdairy.com.bd/mobile
- Scan QR code for direct download
- Download APK for Android

### 9.2 App Features

#### 9.2.1 Mobile-Only Features

**Enhanced Features:**
- **Push Notifications:** Real-time order updates
- **GPS Location:** Auto-detect delivery address
- **Barcode Scanner:** Quick product lookup
- **Voice Search:** Search by voice command
- **Offline Mode:** Browse products offline
- **Face ID/Touch ID:** Secure login
- **Dark Mode:** Eye-friendly interface

#### 9.2.2 App Navigation

**Bottom Navigation Bar:**
```
┌─────────────────────────────────────────────────────────────┐
│                   SMART DAIRY HEADER                   │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  [Search Bar]                                   [Cart 3] │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │                                                 │   │
│  │              CATEGORY CAROUSEL                   │   │
│  │                                                 │   │
│  │         [Product Cards Grid]                      │   │
│  │                                                 │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │  🏠 Home      🛍️ Shop      📦 Orders      👤 More  │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

**Navigation Options:**
- **Home:** Homepage with featured products
- **Shop:** Browse all products and categories
- **Orders:** View order history and track orders
- **More:** Account settings, support, preferences

### 9.3 App-Specific Functions

#### 9.3.1 Offline Mode

**Using Offline Mode:**
1. App automatically caches visited pages
2. Browse products without internet
3. Add items to cart
4. Connect to internet
5. Sync cart and complete checkout

**Limitations:**
- Cannot checkout offline
- Cannot view real-time stock
- Cannot process payments

#### 9.3.2 Location Services

**GPS Features:**
- Auto-detect current location
- Show nearest stores
- Calculate delivery time
- Share location with delivery agent

**Enabling Location:**
1. Go to app settings
2. Enable "Location Services"
3. Choose "Always Allow" for best experience
4. Grant permission when prompted

---

## 10. Payment Methods

### 10.1 bKash

#### 10.1.1 Setup & Verification

**Link bKash Account:**
1. Go to "My Account" → "Payment Methods"
2. Click "Add bKash"
3. Enter bKash mobile number
4. Receive 6-digit OTP
5. Enter OTP to verify
6. Account linked successfully

**Payment Limits (bKash):**
- Minimum: BDT 10
- Maximum: BDT 50,000 per transaction
- Daily limit: BDT 200,000
- Monthly limit: BDT 1,000,000

#### 10.1.2 Making Payments

**Payment Flow:**
1. Select bKash at checkout
2. Confirm mobile number
3. Click "Pay with bKash"
4. Redirected to bKash gateway
5. Enter bKash PIN
6. Confirm payment
7. Return to Smart Dairy

**Post-Payment:**
- Receipt sent to bKash app
- SMS confirmation from bKash
- Smart Dairy order confirmed

**Troubleshooting:**
- **Payment Failed:** Check bKash balance, try again
- **Timeout:** Contact bKash customer care (16247)
- **Transaction Pending:** Wait 5 minutes, check bKash app

### 10.2 Nagad

#### 10.2.1 Setup & Verification

**Link Nagad Account:**
1. Go to "My Account" → "Payment Methods"
2. Click "Add Nagad"
3. Enter Nagad mobile number
4. Receive 6-digit OTP
5. Enter OTP to verify
6. Account linked successfully

**Payment Limits (Nagad):**
- Minimum: BDT 10
- Maximum: BDT 50,000 per transaction
- Daily limit: BDT 200,000
- Monthly limit: BDT 1,000,000

#### 10.2.2 Making Payments

**Payment Flow:**
1. Select Nagad at checkout
2. Confirm mobile number
3. Click "Pay with Nagad"
4. Redirected to Nagad gateway
5. Enter Nagad PIN
6. Confirm payment
7. Return to Smart Dairy

**Post-Payment:**
- Receipt sent to Nagad app
- SMS confirmation from Nagad
- Smart Dairy order confirmed

**Troubleshooting:**
- **Payment Failed:** Check Nagad balance, try again
- **Timeout:** Contact Nagad customer care (16123)
- **Transaction Pending:** Wait 5 minutes, check Nagad app

### 10.3 SSLCommerz (Cards)

#### 10.3.1 Supported Cards

**Accepted Cards:**
- Visa (Credit & Debit)
- MasterCard (Credit & Debit)
- American Express (Credit only)
- VISA Electron
- Maestro

**International Cards:**
- Not supported (Bangladesh-issued cards only)

#### 10.3.2 Card Payment Flow

**Payment Process:**
1. Select "Card Payment" at checkout
2. Redirected to SSLCommerz
3. Enter card details:
   ```
   Card Information:
   - Card Number: 16 digits
   - Expiry Date: MM/YY
   - CVV/CVC: 3 digits (back of card)
   - Cardholder Name: As on card
   ```
4. Click "Pay Now"
5. 3D Secure authentication (if enabled)
   - Bank sends OTP to mobile
   - Enter OTP to verify
6. Payment processed
7. Redirected to Smart Dairy

**3D Secure:**
- Additional security layer
- OTP sent to registered mobile
- Prevents unauthorized transactions
- Mandatory for some banks

**Troubleshooting:**
- **Card Declined:** Check balance, contact bank
- **3D Secure Failed:** Contact bank, ensure mobile registered
- **Timeout:** Try again or use different payment method
- **Multiple Attempts:** Wait 15 minutes before retrying

---

## 11. Troubleshooting

### 11.1 Common Issues

#### 11.1.1 Login Problems

**Issue: Cannot Login**
**Solutions:**
- Check username/password
- Reset password if forgotten
- Clear browser cache and cookies
- Try different browser
- Check internet connection
- Contact support if issue persists

**Issue: OTP Not Received**
**Solutions:**
- Wait 2 minutes for OTP
- Check mobile network
- Click "Resend OTP"
- Ensure correct mobile number
- Try SMS alternative

#### 11.1.2 Payment Issues

**Issue: Payment Failed**
**Solutions:**
- Check account balance
- Verify payment details
- Try different payment method
- Check if payment gateway is operational
- Contact payment provider support

**Issue: Payment Successful but Order Not Confirmed**
**Solutions:**
- Wait 5 minutes for system update
- Refresh order status page
- Check email for confirmation
- Contact support with payment ID

#### 11.1.3 Delivery Issues

**Issue: Order Not Delivered**
**Solutions:**
- Track order status
- Check delivery time slot
- Wait until end of delivery window
- Contact delivery agent
- Contact Smart Dairy support

**Issue: Wrong Items Delivered**
**Solutions:**
- Take photos of delivered items
- Contact support immediately
- Do not consume products
- Return will be arranged
- Correct items delivered within 24 hours

**Issue: Damaged Products**
**Solutions:**
- Take photos of damage
- Contact support immediately
- Do not consume products
- Return arranged free of charge
- Replacement delivered within 24 hours

### 11.2 Browser Issues

#### 11.2.1 Page Not Loading

**Solutions:**
1. Refresh the page (F5)
2. Clear browser cache
3. Clear browser cookies
4. Disable browser extensions
5. Try incognito/private mode
6. Check internet connection
7. Try different browser

**Clearing Cache (Chrome):**
1. Click three dots menu (⋮)
2. Select "Settings"
3. Click "Privacy and security"
4. Click "Clear browsing data"
5. Select "Cached images and files"
6. Click "Clear data"

#### 11.2.2 Mobile Site Issues

**Solutions:**
1. Update browser app
2. Clear browser data
3. Try desktop site (desktop view)
4. Download mobile app
5. Check app for updates

### 11.3 App Issues

#### 11.3.1 App Not Opening

**Solutions:**
1. Close and reopen app
2. Restart phone
3. Update app to latest version
4. Uninstall and reinstall
5. Check phone compatibility
6. Clear app cache

#### 11.3.2 App Crashing

**Solutions:**
1. Update app to latest version
2. Clear app data
3. Reinstall app
4. Check phone storage space
5. Report crash to support

---

## 12. FAQ

### 12.1 General Questions

**Q1: What are your operating hours?**
A: Our online store is open 24/7. Customer support is available from 8 AM to 10 PM, 7 days a week.

**Q2: Do you deliver to my area?**
A: We deliver to Dhaka, Chittagong, Sylhet, Khulna, and major cities. Enter your address during checkout to check availability.

**Q3: What is the minimum order amount?**
A: Minimum order is BDT 200 for standard delivery. Some areas may have different minimum amounts.

**Q4: Can I place an order for someone else?**
A: Yes! You can add multiple delivery addresses to your account and select the recipient's address during checkout.

### 12.2 Product Questions

**Q5: Are your products organic?**
A: Yes! All our dairy products are sourced from organic farms and certified organic.

**Q6: How fresh are your products?**
A: Our products are delivered within 4 hours of milking. All products have clear expiry dates.

**Q7: Do you offer lactose-free options?**
A: Yes, we have lactose-free milk and yoghurt options available.

**Q8: Can I request custom quantities?**
A: For bulk orders (10+ units), contact our sales team for custom pricing and quantities.

### 12.3 Payment Questions

**Q9: What payment methods do you accept?**
A: We accept bKash, Nagad, credit/debit cards (Visa, MasterCard), and cash on delivery.

**Q10: Is it safe to pay online?**
A: Yes! All payments are secured with SSL/TLS encryption and processed through certified payment gateways.

**Q11: Can I pay in installments?**
A: Currently, we do not offer installment options. Full payment is required at checkout.

**Q12: Do you offer refunds?**
A: Yes, refunds are processed within 5-7 business days for returned items or cancelled orders.

### 12.4 Delivery Questions

**Q13: What are your delivery charges?**
A: Delivery charges start from BDT 30 and vary by order value and location. Free delivery on orders above BDT 1000.

**Q14: Can I choose a specific delivery time?**
A: Yes! You can select your preferred time slot (morning, afternoon, or evening) during checkout.

**Q15: What if I'm not home for delivery?**
A: The delivery agent will attempt to contact you. If unreachable, products will be returned to our facility for redelivery.

**Q16: Can I pick up my order instead of delivery?**
A: Yes! Store pickup is free. Select "Pickup" option during checkout and choose a convenient time.

### 12.5 Account Questions

**Q17: How do I reset my password?**
A: Click "Forgot Password" on login page, enter your mobile number or email, and follow the instructions.

**Q18: Can I have multiple delivery addresses?**
A: Yes! You can save up to 5 delivery addresses in your account and select any during checkout.

**Q19: How do I unsubscribe from marketing emails?**
A: Go to "My Account" → "Notifications" and disable "Promotional Offers".

**Q20: Can I delete my account?**
A: Yes, go to "My Account" → "Privacy" → "Delete Account". This cannot be undone.

---

## 13. Contact Support

### 13.1 Support Channels

#### 13.1.1 Contact Information

**Smart Dairy Customer Support**

| Channel | Contact Information | Hours |
|---------|-------------------|-------|
| **Phone** | +880 1700-XXXX | 8 AM - 10 PM (7 days) |
| **WhatsApp** | +880 1700-XXXX | 8 AM - 10 PM (7 days) |
| **Email** | support@smartdairy.com.bd | 24/7 response within 24 hours |
| **Live Chat** | Available on website | 8 AM - 10 PM (7 days) |
| **Social Media** | @SmartDairyBD | 24/7 |

#### 13.1.2 Contact Form

**Using the Contact Form:**
1. Go to "Contact Us" page
2. Fill in details:
   ```
   Contact Form Fields:
   - Name: Your name
   - Email: Your email address
   - Phone: Mobile number
   - Order Number: (if applicable)
   - Category: Select issue type
   - Subject: Brief description
   - Message: Detailed explanation
   - Attachment: Upload screenshots/photos
   ```
3. Click "Submit"
4. Receive confirmation
5. Support team responds within 24 hours

**Issue Categories:**
- Order Issue
- Payment Issue
- Delivery Issue
- Product Quality
- Account Issue
- Technical Issue
- Feedback
- Other

### 13.2 Getting Help

#### 13.2.1 Before Contacting Support

**Try These First:**
1. Check this user manual
2. Review FAQ section
4. Check order status in "My Orders"
5. Verify payment in payment app

#### 13.2.2 Information to Provide

**When Contacting Support, Have Ready:**
- Order number (if applicable)
- Mobile number or email used for account
- Description of issue
- Screenshots or photos (if relevant)
- Date and time of issue
- Browser or app version

#### 13.2.3 Response Times

**Expected Response Times:**

| Issue Type | Response Time | Resolution Time |
|------------|----------------|-----------------|
| **Order Issues** | Within 2 hours | Within 24 hours |
| **Payment Issues** | Within 1 hour | Within 12 hours |
| **Delivery Issues** | Within 1 hour | Within 4 hours |
| **Product Quality** | Within 2 hours | Within 24 hours |
| **Account Issues** | Within 4 hours | Within 24 hours |
| **Technical Issues** | Within 4 hours | Within 48 hours |
| **General Inquiries** | Within 24 hours | Varies |

### 13.3 Feedback

#### 13.3.1 Providing Feedback

**Share Your Experience:**
1. Go to "Contact Us" → "Feedback"
2. Rate your experience:
   ```
   Feedback Categories:
   - Overall Experience: 1-5 stars
   - Product Quality: 1-5 stars
   - Delivery Service: 1-5 stars
   - Website/App Experience: 1-5 stars
   - Customer Support: 1-5 stars
   ```
3. Write detailed comments
4. Submit feedback

**We Value Your Feedback:**
- Improves our services
- Helps us understand your needs
- Drives product improvements
- Enhances customer experience

#### 13.3.2 Reporting Issues

**Report Quality or Safety Issues:**
- **Food Safety:** Report immediately via phone (priority)
- **Product Quality:** Report within 24 hours
- **Delivery Problems:** Report within 4 hours
- **Website Issues:** Report any time

**Urgent Issues:**
- Call: +880 1700-XXXX (priority line)
- WhatsApp: +880 1700-XXXX (instant response)

---

**Document End**

*This B2C End User Manual is your comprehensive guide to using the Smart Dairy E-commerce Portal. For the latest updates and additional information, visit shop.smartdairy.com.bd or download our mobile app. For support, contact us at support@smartdairy.com.bd or call +880 1700-XXXX.*

**Version History:**
- v1.0: Initial release (January 31, 2026)
- Future updates will be published with release notes

**Happy Shopping with Smart Dairy! 🥛**