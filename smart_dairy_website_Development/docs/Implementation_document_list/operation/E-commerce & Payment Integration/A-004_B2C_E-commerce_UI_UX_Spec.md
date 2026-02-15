# SMART DAIRY LTD.
## B2C E-COMMERCE UI/UX SPECIFICATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | A-004 |
| **Version** | 1.0 |
| **Date** | April 25, 2026 |
| **Author** | UX Lead |
| **Owner** | UX Lead |
| **Reviewer** | Product Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [User Personas](#2-user-personas)
3. [Information Architecture](#3-information-architecture)
4. [Homepage Design](#4-homepage-design)
5. [Product Catalog](#5-product-catalog)
6. [Product Detail Page](#6-product-detail-page)
7. [Shopping Cart & Checkout](#7-shopping-cart--checkout)
8. [Customer Account](#8-customer-account)
9. [Subscription Management](#9-subscription-management)
10. [Mobile Design](#10-mobile-design)
11. [Design Specifications](#11-design-specifications)
12. [Accessibility](#12-accessibility)
13. [Appendices](#13-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the comprehensive UI/UX specifications for the Smart Dairy B2C E-commerce Platform. It provides detailed guidelines for the customer-facing online store where consumers can purchase fresh dairy products, manage subscriptions, and track orders.

### 1.2 Platform Scope

| Feature Area | Description |
|--------------|-------------|
| **Product Catalog** | Browse and search dairy products with categories |
| **Shopping Cart** | Add, modify, and review items before purchase |
| **Checkout** | Streamlined multi-step checkout with multiple payment options |
| **Customer Account** | Profile management, order history, addresses |
| **Subscriptions** | Daily/weekly milk delivery subscriptions |
| **Order Tracking** | Real-time order status and delivery tracking |
| **Customer Support** | FAQ, chat support, contact forms |

### 1.3 Key Business Goals

| Goal | Metric Target |
|------|---------------|
| **Conversion Rate** | > 3% of visitors complete purchase |
| **Cart Abandonment** | < 60% (industry benchmark) |
| **Mobile Orders** | > 70% of total orders |
| **Subscription Rate** | > 30% of customers opt for subscription |
| **Customer Satisfaction** | > 4.5/5 rating |

---

## 2. USER PERSONAS

### 2.1 Primary Persona: Sarah - Working Mother

```
┌─────────────────────────────────────────────────────────────────┐
│  PERSONA: SARAH RAHMAN                                          │
├─────────────────────────────────────────────────────────────────┤
│  Age: 32 | Occupation: Marketing Manager | Location: Dhaka      │
│  Family: Husband + 2 children (ages 5 and 8)                    │
│  Income: ৳80,000/month | Tech Comfort: High                     │
├─────────────────────────────────────────────────────────────────┤
│  GOALS:                                                         │
│  • Order fresh organic milk for family daily                    │
│  • Save time on grocery shopping                                │
│  • Ensure quality food for children                             │
│  • Manage household budget efficiently                          │
│                                                                 │
│  PAIN POINTS:                                                   │
│  • Unreliable milk delivery times                               │
│  • Quality concerns with local vendors                          │
│  • Forget to place orders regularly                             │
│  • Difficult to track spending on dairy                         │
│                                                                 │
│  NEEDS:                                                         │
│  • Subscription for automatic daily delivery                    │
│  • Flexible delivery time slots                                 │
│  • Easy pause/resume for vacations                              │
│  • Multiple payment options                                     │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Secondary Persona: Karim - Health Conscious Professional

```
┌─────────────────────────────────────────────────────────────────┐
│  PERSONA: KARIM AHMED                                           │
├─────────────────────────────────────────────────────────────────┤
│  Age: 28 | Occupation: Software Engineer | Location: Gulshan    │
│  Status: Single | Income: ৳120,000/month                        │
│  Tech Comfort: Very High | Primary Device: iPhone               │
├─────────────────────────────────────────────────────────────────┤
│  GOALS:                                                         │
│  • Buy organic, antibiotic-free dairy products                  │
│  • Try new premium products (cheese, yogurt varieties)          │
│  • Quick ordering with minimal steps                            │
│  • Track nutritional information                                │
│                                                                 │
│  PAIN POINTS:                                                   │
│  • Limited organic dairy options in local stores                │
│  • No transparency on product sourcing                          │
│  • Complicated checkout processes                               │
│                                                                 │
│  NEEDS:                                                         │
│  • Detailed product information and certifications              │
│  • Quick reorder functionality                                  │
│  • Scheduled deliveries for convenience                         │
│  • Digital receipts and order history                           │
└─────────────────────────────────────────────────────────────────┘
```

### 2.3 Tertiary Persona: Rina - Elderly Homemaker

```
┌─────────────────────────────────────────────────────────────────┐
│  PERSONA: RINA BEGUM                                            │
├─────────────────────────────────────────────────────────────────┤
│  Age: 58 | Occupation: Retired | Location: Narayanganj          │
│  Family: Husband, visiting children                             │
│  Tech Comfort: Low | Primary Device: Android phone (large text) │
├─────────────────────────────────────────────────────────────────┤
│  GOALS:                                                         │
│  • Order fresh milk without visiting market                     │
│  • Simple, easy-to-use interface                                │
│  • Cash on delivery option                                      │
│  • Call support when needed                                     │
│                                                                 │
│  PAIN POINTS:                                                   │
│  • Small text difficult to read                                 │
│  • Confused by too many options                                 │
│  • Concerns about online payment security                       │
│                                                                 │
│  NEEDS:                                                         │
│  • Large, high-contrast interface elements                      │
│  • Bengali language support                                     │
│  • Simple 3-step ordering process                               │
│  • Cash on delivery payment option                              │
│  • Easy access to phone support                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. INFORMATION ARCHITECTURE

### 3.1 Site Map

```
smartdairybd.com (B2C Store)
│
├── Home
│   ├── Hero Banner
│   ├── Featured Products
│   ├── Subscription CTA
│   ├── Quality Promise
│   └── Testimonials
│
├── Shop
│   ├── All Products
│   ├── Categories
│   │   ├── Fresh Milk
│   │   ├── Yogurt & Fermented
│   │   ├── Butter & Ghee
│   │   ├── Cheese
│   │   ├── Meat Products
│   │   └── Gift Packs
│   ├── Filters
│   │   ├── Price Range
│   │   ├── Dietary Preferences
│   │   └── Availability
│   └── Search Results
│
├── Product Detail
│   ├── Product Images
│   ├── Product Info
│   ├── Price & Quantity
│   ├── Delivery Options
│   ├── Reviews
│   └── Related Products
│
├── Cart
│   ├── Item List
│   ├── Quantity Update
│   ├── Promo Code
│   └── Cart Summary
│
├── Checkout
│   ├── Login/Guest
│   ├── Delivery Address
│   ├── Delivery Schedule
│   ├── Payment Method
│   └── Order Review
│
├── Subscription
│   ├── Plan Selection
│   ├── Schedule Setup
│   ├── Delivery Preferences
│   └── Management Dashboard
│
├── My Account
│   ├── Profile
│   ├── Orders
│   ├── Subscriptions
│   ├── Addresses
│   ├── Payment Methods
│   └── Support Tickets
│
├── Order Tracking
│   └── Real-time Status
│
└── Support
    ├── FAQ
    ├── Delivery Info
    ├── Return Policy
    ├── Contact Us
    └── Live Chat
```

### 3.2 Navigation Structure

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  [LOGO]    Shop ▼    Subscription    About    [Search]    [Account] [Cart]     │
│            - Milk            - How it Works                                    │
│            - Yogurt          - Plans                                           │
│            - Butter          - Benefits                                        │
│            - Cheese                                                            │
│            - All Products                                                      │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. HOMEPAGE DESIGN

### 4.1 Homepage Layout

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  [LOGO]    Shop ▼    Subscription    About    [Search]    [Account] [Cart]     │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │                                                                             ││
│  │                    HERO SECTION                                             ││
│  │                                                                             ││
│  │   ┌─────────────────────────────────┐  ┌───────────────────────────────┐   ││
│  │   │                                 │  │                               │   ││
│  │   │   100% ORGANIC                  │  │     [PRODUCT IMAGE]           │   ││
│  │   │   DAIRY DELIVERED               │  │                               │   ││
│  │   │   TO YOUR DOOR                  │  │                               │   ││
│  │   │                                 │  │                               │   ││
│  │   │   Fresh from our farm to your   │  │                               │   ││
│  │   │   family. No preservatives.     │  │                               │   ││
│  │   │   No antibiotics. Just pure.    │  │                               │   ││
│  │   │                                 │  │                               │   ││
│  │   │   [Shop Now]  [Subscribe & Save]│  │                               │   ││
│  │   │                                 │  │                               │   ││
│  │   └─────────────────────────────────┘  └───────────────────────────────┘   ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  WHY CHOOSE SAFFRON                                                        ││
│  │                                                                             ││
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐   ││
│  │  │    🌿        │  │    🥛        │  │    🚚        │  │    🏆        │   ││
│  │  │              │  │              │  │              │  │              │   ││
│  │  │ 100% Organic│  │ Farm Fresh   │  │ Daily        │  │ Quality      │   ││
│  │  │ Certified   │  │ Daily        │  │ Delivery     │  │ Guaranteed   │   ││
│  │  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘   ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  SHOP BY CATEGORY                                    [View All →]          ││
│  │                                                                             ││
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐                   ││
│  │  │ [IMAGE]  │  │ [IMAGE]  │  │ [IMAGE]  │  │ [IMAGE]  │                   ││
│  │  │          │  │          │  │          │  │          │                   ││
│  │  │ FRESH    │  │ YOGURT   │  │ BUTTER   │  │ CHEESE   │                   ││
│  │  │ MILK     │  │          │  │ & GHEE   │  │          │                   ││
│  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘                   ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  SUBSCRIPTION PLANS                                                        ││
│  │                                                                             ││
│  │  ┌──────────────────────┐  ┌──────────────────────┐                        ││
│  │  │   DAILY MILK         │  │   WEEKLY BUNDLE      │                        ││
│  │  │                      │  │                      │                        ││
│  │  │   ৳1,800/month       │  │   ৳3,500/month       │                        ││
│  │  │                      │  │                      │                        ││
│  │  │   ✓ 1L milk daily    │  │   ✓ 7L milk          │                        ││
│  │  │   ✓ Free delivery    │  │   ✓ 2 yogurt packs   │                        ││
│  │  │   ✓ Pause anytime    │  │   ✓ 1 butter pack    │                        ││
│  │  │                      │  │   ✓ Save 15%         │                        ││
│  │  │   [Subscribe Now]    │  │   [Subscribe Now]    │                        ││
│  │  └──────────────────────┘  └──────────────────────┘                        ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  FEATURED PRODUCTS                                    [View All →]         ││
│  │                                                                             ││
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐                   ││
│  │  │ [IMAGE]  │  │ [IMAGE]  │  │ [IMAGE]  │  │ [IMAGE]  │                   ││
│  │  │          │  │          │  │          │  │          │                   ││
│  │  │ Saffron  │  │ Saffron  │  │ Saffron  │  │ Saffron  │                   ││
│  │  │ Organic  │  │ Sweet    │  │ Pure     │  │ Organic  │                   ││
│  │  │ Milk 1L  │  │ Yogurt   │  │ Butter   │  │ Beef 2kg │                   ││
│  │  │          │  │ 500g     │  │ 200g     │  │          │                   ││
│  │  │ ★★★★★    │  │ ★★★★★    │  │ ★★★★☆    │  │ ★★★★★    │                   ││
│  │  │          │  │          │  │          │  │          │                   ││
│  │  │ ৳120     │  │ ৳150     │  │ ৳180     │  │ ৳1,200   │                   ││
│  │  │          │  │          │  │          │  │          │                   ││
│  │  │ [Add]    │  │ [Add]    │  │ [Add]    │  │ [Add]    │                   ││
│  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘                   ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  HOW IT WORKS                                                              ││
│  │                                                                             ││
│  │  1. Choose → 2. Subscribe → 3. Deliver → 4. Enjoy                           ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  CUSTOMER TESTIMONIALS                                                     ││
│  │                                                                             ││
│  │  "The quality is amazing! My kids love the taste." - Sarah R.              ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  [FOOTER]                                                                  ││
│  │  Products | Company | Support | Connect                                     ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 5. PRODUCT CATALOG

### 5.1 Product Listing Page

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  Breadcrumb: Home > Shop > Fresh Milk                                           │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  FRESH MILK                              Showing 12 products                   │
│                                                                                  │
│  ┌──────────┐  ┌─────────────────────────────────────────────────────────────┐ │
│  │          │  │  Sort by: [Featured ▼]     [Grid ▦] [List ☰]              │ │
│  │ FILTERS  │  │                                                            │ │
│  │          │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐      │ │
│  │ Price    │  │  │ [IMAGE]  │ │ [IMAGE]  │ │ [IMAGE]  │ │ [IMAGE]  │      │ │
│  │ ───────  │  │  │          │ │          │ │          │ │          │      │ │
│  │ ●──────  │  │  │ Organic  │ │ Full     │ │ Low Fat  │ │ Full     │      │ │
│  │ ৳50-৳200│  │  │ Milk 1L  │ │ Cream 1L │ │ Milk 1L  │ │ Cream 2L │      │ │
│  │          │  │  │          │ │          │ │          │ │          │      │ │
│  │ Dietary  │  │  │ ★★★★★    │ │ ★★★★☆    │ │ ★★★★★    │ │ ★★★★★    │      │ │
│  │ ☑ Organic│  │  │          │ │          │ │          │ │          │      │ │
│  │ ☐ Low Fat│  │  │ ৳120     │ │ ৳130     │ │ ৳115     │ │ ৳240     │      │ │
│  │ ☑ Fresh  │  │  │          │ │          │ │          │ │          │      │ │
│  │          │  │  │ [Add]    │ │ [Add]    │ │ [Add]    │ │ [Add]    │      │ │
│  │ Rating   │  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘      │ │
│  │ ★★★★☆ &↑ │  │                                                            │ │
│  │          │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐      │ │
│  │          │  │  │ [IMAGE]  │ │ [IMAGE]  │ │ [IMAGE]  │ │ [IMAGE]  │      │ │
│  │ [Apply]  │  │  │          │ │          │ │          │ │          │      │ │
│  │ [Reset]  │  │  │ ...      │ │ ...      │ │ ...      │ │ ...      │      │ │
│  │          │  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘      │ │
│  └──────────┘  │                                                            │ │
│                │  [< Previous]  1  2  3  ...  5  [Next >]                    │ │
│                └─────────────────────────────────────────────────────────────┘ │
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### 5.2 Product Card Design

```
┌─────────────────────────────────────────┐
│  ┌─────────────────────────────────┐   │
│  │                                 │   │
│  │      [PRODUCT IMAGE]            │   │
│  │      (Hover: alternate view)    │   │
│  │                                 │   │
│  │      🏷️ SAVE 15%               │   │  <- Badge (optional)
│  │                                 │   │
│  └─────────────────────────────────┘   │
│                                         │
│  SAFFRON ORGANIC MILK                  │
│  100% Pure | Fresh Daily               │
│                                         │
│  ★★★★★ (4.8) · 256 reviews             │
│                                         │
│  ৳120  ~~৳140~~                        │
│                                         │
│  ✓ In Stock                            │
│                                         │
│  ┌─────────────────────────────────┐   │
│  │  [🛒 ADD TO CART]               │   │
│  └─────────────────────────────────┘   │
│                                         │
└─────────────────────────────────────────┘
```

---

## 6. PRODUCT DETAIL PAGE

### 6.1 Product Detail Layout

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  Breadcrumb: Home > Shop > Fresh Milk > Saffron Organic Milk 1L                 │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌──────────────────────────┐  ┌─────────────────────────────────────────────┐ │
│  │                          │  │                                             │ │
│  │   ┌──────────────────┐   │  │   SAFFRON ORGANIC MILK                     │ │
│  │   │                  │   │  │   100% Pure, Farm-Fresh Daily              │ │
│  │   │   MAIN IMAGE     │   │  │                                            │ │
│  │   │   (Zoom on hover)│   │  │   ★★★★★ 4.8/5 (256 reviews)               │ │
│  │   │                  │   │  │                                            │ │
│  │   └──────────────────┘   │  │   ৳120                                     │ │
│  │                          │  │   ~~৳140~~  You save ৳20 (14%)            │ │
│  │   [thumb1] [thumb2] [t3] │  │                                            │ │
│  │                          │  │   Size: [500ml ○] [1L ●] [2L ○]            │ │
│  └──────────────────────────┘  │                                            │ │
│                                │   Quantity: [ - 2 + ]                      │ │
│                                │                                            │ │
│                                │   ✓ In Stock (available for subscription)  │ │
│                                │   🚚 Free delivery on orders over ৳500     │ │
│                                │                                            │ │
│                                │   [🛒 ADD TO CART]  [⚡ BUY NOW]           │ │
│                                │                                            │ │
│                                │   [🔄 SUBSCRIBE & SAVE 10%]                │ │
│                                │                                            │ │
│                                │   Delivery: Tomorrow, 6-8 AM               │ │
│                                │   Pincode: [1020 ✓]                        │ │
│                                │                                            │ │
│                                └─────────────────────────────────────────────┘ │
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  [Description] [Nutrition] [Reviews] [Delivery]                            ││
│  ├─────────────────────────────────────────────────────────────────────────────┤│
│  │                                                                             ││
│  │  DESCRIPTION                                                                ││
│  │  Our flagship organic milk comes directly from grass-fed cows at our       ││
│  │  Narayanganj farm. No antibiotics, no preservatives, no hormones.          ││
│  │                                                                             ││
│  │  ✓ 100% Organic Certified                                                   ││
│  │  ✓ Antibiotic-free                                                          ││
│  │  ✓ Daily fresh delivery                                                     ││
│  │  ✓ Cold-chain maintained                                                    ││
│  │                                                                             ││
│  │  SHELF LIFE: 48 hours refrigerated                                         ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  CUSTOMERS ALSO BOUGHT                                                      ││
│  │  [Product cards...]                                                         ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 7. SHOPPING CART & CHECKOUT

### 7.1 Shopping Cart Page

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  YOUR CART (3 items)                                                            │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌─────────────────────────────────────────────┐  ┌───────────────────────────┐│
│  │                                             │  │   ORDER SUMMARY           ││
│  │   ┌──────────┬───────────────────────────┐  │  │                           ││
│  │   │ [IMAGE]  │ Saffron Organic Milk 1L   │  │  │   Subtotal (3 items)     ││
│  │   │          │ ৳120 × 2                 │  │  │   ৳490                   ││
│  │   │          │ Quantity: [ - 2 + ]       │  │  │                           ││
│  │   │          │ [Remove] [Save for later] │  │  │   Shipping               ││
│  │   └──────────┴───────────────────────────┘  │  │   ৳50                    ││
│  │                                             │  │   (Free above ৳500)      ││
│  │   ┌──────────┬───────────────────────────┐  │  │                           ││
│  │   │ [IMAGE]  │ Saffron Sweet Yogurt 500g │  │  │   Discount (WELCOME10)   ││
│  │   │          │ ৳150 × 1                 │  │  │   -৳49                   ││
│  │   │          │ Quantity: [ - 1 + ]       │  │  │                           ││
│  │   │          │ [Remove] [Save for later] │  │  │   ─────────────────────  ││
│  │   └──────────┴───────────────────────────┘  │  │   TOTAL                  ││
│  │                                             │  │   ৳491                   ││
│  │   [🎁 Add Promo Code]                       │  │                           ││
│  │   [Enter code...] [Apply]                   │  │   You save ৳49           ││
│  │                                             │  │                           ││
│  │   [🛒 Continue Shopping]                    │  │   [Proceed to Checkout]  ││
│  │                                             │  │                           ││
│  └─────────────────────────────────────────────┘  └───────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  SAVED FOR LATER                                                            ││
│  │  [Product cards...]                                                         ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### 7.2 Checkout Flow

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  CHECKOUT                                              Secure Checkout 🔒       │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌─────────────────────────────────────────────┐  ┌───────────────────────────┐│
│  │  1️⃣ DELIVERY ADDRESS                        │  │   ORDER SUMMARY           ││
│  │                                             │  │                           ││
│  │  [Radio] 🏠 Home                            │  │   [Product list...]      ││
│  │      45, Lake Drive, Gulshan-1, Dhaka      │  │                           ││
│  │      Contact: 01712345678                   │  │   Subtotal:    ৳490      ││
│  │                                             │  │   Shipping:    ৳50       ││
│  │  [Radio] 🏢 Office                          │  │   Discount:   -৳49       ││
│  │      Smart Technologies BD Ltd.            │  │   ─────────────          ││
│  │      Jahir Smart Tower, Kafrul, Dhaka      │  │   Total:       ৳491      ││
│  │                                             │  │                           ││
│  │  [+ Add New Address]                        │  │                           ││
│  │                                             │  │                           ││
│  ├─────────────────────────────────────────────┤  │                           ││
│  │  2️⃣ DELIVERY SCHEDULE                       │  │                           ││
│  │                                             │  │                           ││
│  │  Delivery Date: [Tomorrow ▼]                │  │                           ││
│  │                                             │  │                           ││
│  │  Time Slot:                                 │  │                           ││
│  │  [○] 6:00 AM - 8:00 AM (Morning)           │  │                           ││
│  │  [●] 2:00 PM - 4:00 PM (Afternoon)         │  │                           ││
│  │  [○] 6:00 PM - 8:00 PM (Evening)           │  │                           ││
│  │                                             │  │                           ││
│  │  📝 Delivery Instructions:                  │  │                           ││
│  │  [Ring doorbell, leave at reception...]    │  │                           ││
│  │                                             │  │                           ││
│  ├─────────────────────────────────────────────┤  │                           ││
│  │  3️⃣ PAYMENT METHOD                          │  │                           ││
│  │                                             │  │                           ││
│  │  [Radio] 📱 bKash                           │  │                           ││
│  │      Pay with bKash account                 │  │                           ││
│  │                                             │  │                           │
│  │  [Radio] 📱 Nagad                           │  │                           ││
│  │      Pay with Nagad wallet                  │  │                           ││
│  │                                             │  │                           ││
│  │  [Radio] 📱 Rocket                          │  │                           ││
│  │      Pay with Rocket account                │  │                           ││
│  │                                             │  │                           ││
│  │  [Radio] 💵 Cash on Delivery                │  │                           ││
│  │      Pay when you receive (+৳20 fee)       │  │                           ││
│  │                                             │  │                           ││
│  ├─────────────────────────────────────────────┤  │                           ││
│  │                                             │  │                           ││
│  │  ☑️ I agree to the Terms & Conditions       │  │                           ││
│  │                                             │  │                           ││
│  │  [PLACE ORDER - ৳491]                       │  │                           ││
│  │                                             │  │                           ││
│  └─────────────────────────────────────────────┘  └───────────────────────────┘│
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 8. CUSTOMER ACCOUNT

### 8.1 Account Dashboard

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  MY ACCOUNT                                                                     │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌────────────┐  ┌─────────────────────────────────────────────────────────────┐│
│  │            │  │  WELCOME BACK, SARAH!                                       ││
│  │  👤 Profile│  │                                                             ││
│  │  📦 Orders │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐       ││
│  │  🔄 Subs   │  │  │ 3        │ │ 1        │ │ 2        │ │ 0        │       ││
│  │  📍 Address│  │  │ Pending  │ │ Shipped  │ │ Delivered│ │ Cancelled│       ││
│  │  💳 Payment│  │  └──────────┘ └──────────┘ └──────────┘ └──────────┘       ││
│  │  🎟 Support│  │                                                             ││
│  │  ⚙️ Settings│  │  RECENT ORDERS                                             ││
│  │            │  │  ┌───────────────────────────────────────────────────────┐ ││
│  │            │  │  │ #ORD-2024-0012    Today    ৳490    [Track →]         │ ││
│  │            │  │  │ #ORD-2024-0011    Yesterday ৳850    [Track →]         │ ││
│  │            │  │  │ #ORD-2024-0010    2 days ago ৳320    [Delivered ✓]    │ ││
│  │            │  │  └───────────────────────────────────────────────────────┘ ││
│  │            │  │                                                             ││
│  │            │  │  MY SUBSCRIPTIONS                                          ││
│  │            │  │  ┌───────────────────────────────────────────────────────┐ ││
│  │            │  │  │ Daily Milk Plan    Active    Next: Tomorrow 6AM      │ ││
│  │            │  │  │ [Manage →]  [Pause]                                   │ ││
│  │            │  │  └───────────────────────────────────────────────────────┘ ││
│  │            │  │                                                             ││
│  └────────────┘  └─────────────────────────────────────────────────────────────┘│
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 9. SUBSCRIPTION MANAGEMENT

### 9.1 Subscription Page

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  MY SUBSCRIPTIONS                                                               │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  ACTIVE SUBSCRIPTIONS                                                       ││
│  │                                                                             ││
│  │  ┌───────────────────────────────────────────────────────────────────────┐ ││
│  │  │  🥛 DAILY MILK SUBSCRIPTION                                           │ ││
│  │  │                                                                       │ ││
│  │  │  Product: Saffron Organic Milk 1L                                    │ ││
│  │  │  Schedule: Daily at 6:00 AM                                          │ ││
│  │  │  Quantity: 2 bottles/day                                             │ ││
│  │  │  Next Delivery: Tomorrow, 6:00 AM                                    │ ││
│  │  │  Monthly Cost: ৳7,200                                                │ ││
│  │  │                                                                       │ ││
│  │  │  [Modify Plan]  [Pause]  [Cancel]                                    │ ││
│  │  └───────────────────────────────────────────────────────────────────────┘ ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  SUBSCRIPTION CALENDAR                                                      ││
│  │                                                                             ││
│  │      Sun    Mon    Tue    Wed    Thu    Fri    Sat                         ││
│  │      14     15     16     17     18     19     20                          ││
│  │      ✓      ✓      ✓      ✓      ✓      ✓      ✓                           ││
│  │      (delivered)                                                          ││
│  │                                                                             ││
│  │      21     22     23     24     25     26     27                          ││
│  │      ✓      ⏸      ✓      ✓      ✓      ✓      ✓                           ││
│  │    (today) (PAUSED)                                                       ││
│  │                                                                             ││
│  │  Legend: ✓ Delivered  ⏸ Paused  📦 Scheduled                               ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  UPCOMING DELIVERIES                                                        ││
│  │                                                                             ││
│  │  Tomorrow, 6:00 AM - 2 bottles                                              ││
│  │  Wednesday, 6:00 AM - 2 bottles                                             ││
│  │  Thursday, 6:00 AM - 2 bottles                                              ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 10. MOBILE DESIGN

### 10.1 Mobile Homepage

```
┌─────────────────────────┐
│  ≡  [LOGO]    🔍 🛒 👤 │
├─────────────────────────┤
│                         │
│  ┌─────────────────────┐│
│  │  FRESH ORGANIC      ││
│  │  DAIRY DAILY        ││
│  │                     ││
│  │  [Shop Now]         ││
│  └─────────────────────┘│
│                         │
│  CATEGORIES             ││
│  [🥛] [🥣] [🧈] [🧀]   ││
│  Milk  Yogurt Butter  Ch │
│                         │
│  FEATURED               ││
│  ┌────┐ ┌────┐ ┌────┐  ││
│  │IMG │ │IMG │ │IMG │  ││
│  │Milk│ │Yog │ │But │  ││
│  │৳120│ │৳150│ │৳180│  ││
│  └────┘ └────┘ └────┘  ││
│                         │
│  SUBSCRIBE & SAVE       ││
│  [Learn More →]         ││
│                         │
├─────────────────────────┤
│  🏠  📦  🔄  🛒  👤    │
│ Home Shop Subs Cart Acc │
└─────────────────────────┘
```

### 10.2 Mobile Product Detail

```
┌─────────────────────────┐
│  ← SAFFRON ORGANIC MILK │
├─────────────────────────┤
│                         │
│  ┌─────────────────────┐│
│  │                     ││
│  │   [PRODUCT IMAGE]   ││
│  │                     ││
│  └─────────────────────┘│
│  ● ○ ○ ○                │
│                         │
│  100% ORGANIC MILK      │
│  ★★★★★ (256)            │
│                         │
│  ৳120  ~~৳140~~         │
│                         │
│  Size: 1L               │
│  [500ml] [1L] [2L]      │
│                         │
│  Quantity: [ - 2 + ]    │
│                         │
│  ✓ In Stock             │
│  🚚 Free delivery >৳500 │
│                         │
│  ┌─────────────────────┐│
│  │   🛒 ADD TO CART    ││
│  └─────────────────────┘│
│                         │
│  [🔄 SUBSCRIBE & SAVE]  │
│                         │
│  DESCRIPTION            │
│  Our flagship organic   │
│  milk from grass-fed... │
│                         │
│  NUTRITION INFO         │
│  [View →]               │
│                         │
└─────────────────────────┘
```

---

## 11. DESIGN SPECIFICATIONS

### 11.1 Color Palette

| Token | Hex | Usage |
|-------|-----|-------|
| **Primary Green** | #2E7D32 | Primary CTAs, brand elements |
| **Primary Light** | #4CAF50 | Hover states, highlights |
| **Primary Dark** | #1B5E20 | Active states |
| **Accent Gold** | #FFD700 | Premium badges, subscription |
| **Success** | #4CAF50 | Success states, stock available |
| **Warning** | #FF9800 | Low stock, attention |
| **Error** | #F44336 | Errors, out of stock |
| **Text Primary** | #212121 | Headings, primary text |
| **Text Secondary** | #757575 | Secondary text |
| **Background** | #FAFAFA | Page background |
| **Card** | #FFFFFF | Card backgrounds |
| **Border** | #E0E0E0 | Dividers, borders |

### 11.2 Typography Scale

| Element | Size | Weight | Line Height |
|---------|------|--------|-------------|
| Display | 48px | 700 | 1.2 |
| H1 | 32px | 700 | 1.3 |
| H2 | 24px | 600 | 1.4 |
| H3 | 20px | 600 | 1.4 |
| Body Large | 18px | 400 | 1.6 |
| Body | 16px | 400 | 1.6 |
| Body Small | 14px | 400 | 1.5 |
| Caption | 12px | 400 | 1.4 |
| Price | 24px | 700 | 1.2 |
| Price Old | 18px | 400 | 1.2 | strikethrough |

### 11.3 Component Specifications

**Buttons:**
```
Primary Button:
- Background: #2E7D32
- Text: White
- Padding: 16px 32px
- Border-radius: 8px
- Hover: #4CAF50
- Active: #1B5E20

Secondary Button:
- Background: White
- Border: 1px solid #2E7D32
- Text: #2E7D32
- Padding: 16px 32px
- Border-radius: 8px
```

**Cards:**
```
Product Card:
- Background: White
- Border-radius: 12px
- Shadow: 0 2px 8px rgba(0,0,0,0.08)
- Padding: 16px
- Hover Shadow: 0 8px 24px rgba(0,0,0,0.12)
```

---

## 12. ACCESSIBILITY

### 12.1 Accessibility Requirements

| Requirement | Implementation |
|-------------|----------------|
| **Color Contrast** | Minimum 4.5:1 for text |
| **Focus States** | Visible focus rings on all interactive elements |
| **Alt Text** | Descriptive alt text for all images |
| **ARIA Labels** | Proper labeling for screen readers |
| **Keyboard Navigation** | Full keyboard accessibility |
| **Touch Targets** | Minimum 44x44px on mobile |

### 12.2 Bengali Language Support

| Element | English | Bengali |
|---------|---------|---------|
| Add to Cart | Add to Cart | কার্টে যোগ করুন |
| Buy Now | Buy Now | এখনই কিনুন |
| Subscribe | Subscribe | সাবস্ক্রাইব করুন |
| Checkout | Checkout | চেকআউট |
| Delivery | Delivery | ডেলিভারি |
| Free Shipping | Free Shipping | বিনামূল্যে শিপিং |

---

## 13. APPENDICES

### Appendix A: Responsive Breakpoints

| Breakpoint | Width | Layout |
|------------|-------|--------|
| Mobile | < 640px | Single column |
| Tablet | 640-1024px | Two columns |
| Desktop | > 1024px | Multi-column |

### Appendix B: Image Specifications

| Type | Dimensions | Format | Max Size |
|------|------------|--------|----------|
| Product Main | 800x800 | WebP | 200KB |
| Product Thumbnail | 300x300 | WebP | 50KB |
| Hero Banner | 1920x600 | WebP | 500KB |
| Category Card | 400x400 | WebP | 100KB |

---

**END OF B2C E-COMMERCE UI/UX SPECIFICATION**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Apr 25, 2026 | UX Lead | Initial version |
