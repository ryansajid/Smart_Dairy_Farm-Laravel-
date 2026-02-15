# Smart Dairy Digital Ecosystem - API Specification Document
## RESTful API Design & Implementation Guide

**Version:** 2.0
**Date:** January 31, 2026
**API Version:** v1
**Base URL:** `https://api.smartdairy.com/v1` (Production)
**Base URL:** `http://localhost:5000/api/v1` (Development)

---

## TABLE OF CONTENTS

1. [API Overview](#1-api-overview)
2. [Authentication & Authorization](#2-authentication--authorization)
3. [Common Services API](#3-common-services-api)
4. [Website Module API (Module A)](#4-website-module-api)
5. [Marketplace Module API (Module B)](#5-marketplace-module-api)
6. [Livestock Module API (Module C)](#6-livestock-module-api)
7. [ERP Module API (Module D)](#7-erp-module-api)
8. [Mobile API (Module E)](#8-mobile-api)
9. [WebSocket Events](#9-websocket-events)
10. [Data Models](#10-data-models)
11. [Error Handling](#11-error-handling)
12. [Rate Limiting](#12-rate-limiting)
13. [Pagination & Filtering](#13-pagination--filtering)
14. [File Uploads](#14-file-uploads)
15. [API Versioning](#15-api-versioning)

---

## 1. API OVERVIEW

### 1.1 General Information

**API Style:** RESTful
**Data Format:** JSON
**Character Encoding:** UTF-8
**Date Format:** ISO 8601 (`2026-01-31T10:30:00Z`)
**HTTP Methods:** GET, POST, PUT, PATCH, DELETE
**Real-time:** Socket.IO (WebSocket with fallback)

### 1.2 Module Routing

All API endpoints are organized by module with the following prefix structure:

| Module | Route Prefix | Description |
|--------|-------------|-------------|
| Auth | `/api/v1/auth/*` | Authentication & session management |
| Common | `/api/v1/common/*` | Shared services (files, notifications, settings) |
| Website | `/api/v1/website/*` | B2C e-commerce, B2B portal, content |
| Marketplace | `/api/v1/marketplace/*` | Multi-vendor agro marketplace |
| Livestock | `/api/v1/livestock/*` | Livestock trading platform |
| ERP | `/api/v1/erp/*` | Dairy farm management |
| Mobile | `/api/v1/mobile/*` | Mobile-specific endpoints (sync, offline) |

### 1.3 Request Headers

**Required Headers:**
```http
Content-Type: application/json
Accept: application/json
```

**Authentication Headers:**
```http
Authorization: Bearer {access_token}
```

**Optional Headers:**
```http
X-Request-ID: {unique-request-id}
Accept-Language: en | bn
X-Module: website | marketplace | livestock | erp
X-Device-Type: web | mobile | tablet
```

### 1.4 Standard Response Structure

**Success Response:**
```json
{
  "success": true,
  "data": { },
  "message": "Operation successful",
  "timestamp": "2026-01-31T10:30:00Z"
}
```

**Success Response (with pagination):**
```json
{
  "success": true,
  "data": {
    "items": [],
    "pagination": {
      "currentPage": 1,
      "totalPages": 5,
      "totalItems": 47,
      "itemsPerPage": 10,
      "hasNext": true,
      "hasPrev": false
    }
  },
  "timestamp": "2026-01-31T10:30:00Z"
}
```

**Error Response:**
```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Invalid input data",
    "details": [
      { "field": "email", "message": "Invalid email format" }
    ]
  },
  "timestamp": "2026-01-31T10:30:00Z"
}
```

---

## 2. AUTHENTICATION & AUTHORIZATION

### 2.1 RBAC Permission Model

**Roles:**

| Role | Module Access | Description |
|------|--------------|-------------|
| `super_admin` | All modules | Full system access |
| `farm_manager` | ERP (full), Website (read) | Farm operations management |
| `farm_worker` | ERP (limited) | Data entry for farm operations |
| `veterinarian` | ERP (health/breeding) | Animal health and breeding |
| `marketplace_vendor` | Marketplace (seller) | Vendor dashboard and listings |
| `b2b_customer` | Website (B2B portal) | Business customer portal |
| `customer` | Website (B2C) | Consumer shopping |
| `livestock_buyer` | Livestock (buying) | Browse and purchase animals |
| `livestock_seller` | Livestock (selling) | List animals for sale |
| `content_manager` | Website (CMS) | Blog and content management |

**Permission Format:** `module:resource:action`

Examples: `erp:herd:read`, `erp:herd:write`, `marketplace:orders:manage`, `livestock:auctions:bid`

### 2.2 Auth Endpoints

#### POST /api/v1/auth/register
Register new user account.

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+8801712345678",
  "password": "SecurePass123!",
  "role": "customer",
  "acceptTerms": true
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "data": {
    "user": {
      "id": "usr_123456",
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "+8801712345678",
      "roles": ["customer"]
    },
    "verificationRequired": true
  },
  "message": "Verification email sent"
}
```

#### POST /api/v1/auth/login
Authenticate user and return JWT tokens.

**Request:**
```json
{
  "email": "john@example.com",
  "password": "SecurePass123!",
  "deviceType": "web"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "accessToken": "eyJhbGciOiJSUzI1NiIs...",
    "refreshToken": "eyJhbGciOiJSUzI1NiIs...",
    "expiresIn": 3600,
    "user": {
      "id": "usr_123456",
      "name": "John Doe",
      "email": "john@example.com",
      "roles": ["customer"],
      "permissions": ["website:products:read", "website:orders:create"]
    }
  }
}
```

#### POST /api/v1/auth/refresh-token
Refresh access token using refresh token.

**Request:**
```json
{ "refreshToken": "eyJhbGciOiJSUzI1NiIs..." }
```

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "accessToken": "eyJhbGciOiJSUzI1NiIs...",
    "expiresIn": 3600
  }
}
```

#### POST /api/v1/auth/logout
Invalidate current session tokens.

**Auth Required:** Yes

**Response:** `200 OK`
```json
{ "success": true, "message": "Logged out successfully" }
```

#### POST /api/v1/auth/forgot-password
Initiate password reset via email/SMS.

**Request:**
```json
{ "email": "john@example.com" }
```

#### POST /api/v1/auth/reset-password
Reset password with token.

**Request:**
```json
{
  "token": "reset_token_abc123",
  "newPassword": "NewSecurePass123!"
}
```

#### POST /api/v1/auth/verify-email
Verify email address with token.

**Request:**
```json
{ "token": "verify_token_abc123" }
```

#### POST /api/v1/auth/verify-phone
Verify phone number with OTP.

**Request:**
```json
{
  "phone": "+8801712345678",
  "otp": "123456"
}
```

---

## 3. COMMON SERVICES API

### 3.1 User Profile

#### GET /api/v1/common/users/me
Get current user profile with role-specific data.

**Auth Required:** Yes

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "user": {
      "id": "usr_123456",
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "+8801712345678",
      "avatar": "https://cdn.smartdairy.com/avatars/usr_123456.jpg",
      "roles": ["customer"],
      "permissions": ["website:products:read", "website:orders:create"],
      "isVerified": true,
      "preferredLanguage": "en",
      "totalOrders": 15,
      "loyaltyPoints": 450,
      "createdAt": "2026-01-15T08:00:00Z"
    }
  }
}
```

#### PUT /api/v1/common/users/me
Update user profile.

#### POST /api/v1/common/users/me/change-password
Change password.

#### GET /api/v1/common/users/me/addresses
Get saved addresses.

#### POST /api/v1/common/users/me/addresses
Add new address.

#### PUT /api/v1/common/users/me/addresses/:id
Update address.

#### DELETE /api/v1/common/users/me/addresses/:id
Delete address.

### 3.2 File Management

#### POST /api/v1/common/files/upload
Upload file (image, document, certificate).

**Auth Required:** Yes
**Content-Type:** `multipart/form-data`

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| file | binary | Yes | File data |
| module | string | Yes | Target module (website, marketplace, livestock, erp) |
| category | string | Yes | File category (product_image, animal_photo, health_cert, avatar) |

**Accepted Formats:** JPEG, PNG, WebP, PDF, CSV
**Max Size:** 10MB (images), 25MB (documents)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "id": "file_abc123",
    "url": "https://cdn.smartdairy.com/uploads/abc123.jpg",
    "thumbnailUrl": "https://cdn.smartdairy.com/uploads/abc123-thumb.jpg",
    "filename": "abc123.jpg",
    "originalName": "cow-photo.jpg",
    "size": 245678,
    "mimeType": "image/jpeg",
    "module": "erp",
    "category": "animal_photo"
  }
}
```

#### DELETE /api/v1/common/files/:id
Delete uploaded file.

### 3.3 Notifications

#### GET /api/v1/common/notifications
Get user notifications.

**Auth Required:** Yes
**Query Parameters:** `page`, `limit`, `unreadOnly` (boolean)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "notifications": [
      {
        "id": "notif_001",
        "type": "order_delivered",
        "title": "Order Delivered",
        "message": "Your order SD-2026-0001 has been delivered.",
        "module": "website",
        "isRead": false,
        "actionUrl": "/orders/ord_123456",
        "createdAt": "2026-01-31T15:20:00Z"
      }
    ],
    "unreadCount": 3,
    "pagination": { }
  }
}
```

#### PATCH /api/v1/common/notifications/:id/read
Mark notification as read.

#### PATCH /api/v1/common/notifications/read-all
Mark all notifications as read.

### 3.4 Payments

#### POST /api/v1/common/payments/initiate
Initiate payment for any module (website order, marketplace order, livestock purchase).

**Auth Required:** Yes

**Request:**
```json
{
  "module": "website",
  "orderId": "ord_123456",
  "amount": 221.00,
  "currency": "BDT",
  "method": "bkash",
  "returnUrl": "https://smartdairy.com/payment/callback"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "transactionId": "txn_abc123",
    "paymentUrl": "https://payment.bkash.com/checkout/...",
    "sessionId": "sess_xyz789",
    "expiresAt": "2026-01-31T11:00:00Z"
  }
}
```

#### POST /api/v1/common/payments/callback
Payment gateway callback (webhook).

#### GET /api/v1/common/payments/:transactionId/status
Check payment status.

### 3.5 Search (Cross-Module)

#### GET /api/v1/common/search
Unified search across modules.

**Query Parameters:**
- `q` (string, required) - Search query
- `modules` (string, optional) - Comma-separated: `website,marketplace,livestock`
- `limit` (integer, default: 10 per module)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "query": "milk",
    "results": {
      "website": {
        "products": [
          { "id": "prod_001", "name": "Fresh Whole Milk", "price": 95.00, "type": "product" }
        ],
        "count": 12
      },
      "marketplace": {
        "products": [
          { "id": "mkt_prod_001", "name": "Organic Milk Powder", "price": 450.00, "vendor": "AgriFresh", "type": "marketplace_product" }
        ],
        "count": 5
      },
      "livestock": {
        "listings": [],
        "count": 0
      }
    },
    "totalResults": 17
  }
}
```

### 3.6 Settings (Admin)

#### GET /api/v1/common/settings
Get system settings.

**Auth Required:** Yes (super_admin)

#### PUT /api/v1/common/settings
Update system settings.

---

## 4. WEBSITE MODULE API

### 4.1 Products (B2C)

#### GET /api/v1/website/products
List products with filtering and sorting.

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | integer | 1 | Page number |
| limit | integer | 12 | Items per page (max: 48) |
| category | string | - | Category slug |
| search | string | - | Search term |
| minPrice | number | - | Minimum price (BDT) |
| maxPrice | number | - | Maximum price (BDT) |
| inStock | boolean | - | Only in-stock items |
| sort | string | newest | `price_asc`, `price_desc`, `name_asc`, `newest`, `rating` |

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "products": [
      {
        "id": "prod_001",
        "name": "Fresh Whole Milk",
        "slug": "fresh-whole-milk",
        "sku": "MILK-1L-001",
        "description": "Fresh farm milk, 1 liter",
        "shortDescription": "Pure and fresh whole milk",
        "price": 95.00,
        "salePrice": null,
        "currency": "BDT",
        "category": { "id": "cat_001", "name": "Milk", "slug": "milk" },
        "images": [
          { "url": "https://cdn.smartdairy.com/products/milk-1l.jpg", "alt": "Fresh Whole Milk 1L", "isPrimary": true }
        ],
        "stock": 150,
        "inStock": true,
        "packageSize": "1L",
        "rating": 4.5,
        "reviewCount": 28,
        "createdAt": "2026-01-15T08:00:00Z"
      }
    ],
    "pagination": {
      "currentPage": 1,
      "totalPages": 5,
      "totalItems": 52,
      "itemsPerPage": 12,
      "hasNext": true,
      "hasPrev": false
    }
  }
}
```

#### GET /api/v1/website/products/:id
Get single product with full details, nutritional info, and related products.

#### GET /api/v1/website/products/slug/:slug
Get product by URL slug.

### 4.2 Categories

#### GET /api/v1/website/categories
List all product categories with product counts.

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "categories": [
      {
        "id": "cat_001",
        "name": "Milk",
        "slug": "milk",
        "description": "Fresh milk products",
        "image": "https://cdn.smartdairy.com/categories/milk.jpg",
        "productCount": 12,
        "order": 1,
        "parent": null,
        "children": []
      }
    ]
  }
}
```

### 4.3 Cart

#### GET /api/v1/website/cart
Get current user's cart (supports both Smart Dairy and marketplace items).

**Auth Required:** Yes (optional for guest via session cookie)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "cart": {
      "id": "cart_123",
      "items": [
        {
          "id": "item_001",
          "source": "website",
          "product": {
            "id": "prod_001",
            "name": "Fresh Whole Milk",
            "image": "https://cdn.smartdairy.com/products/milk-1l-thumb.jpg",
            "price": 95.00,
            "inStock": true
          },
          "quantity": 2,
          "subtotal": 190.00
        }
      ],
      "itemCount": 2,
      "subtotal": 190.00,
      "deliveryFee": 50.00,
      "discount": 0.00,
      "total": 240.00,
      "currency": "BDT"
    }
  }
}
```

#### POST /api/v1/website/cart/items
Add item to cart.

**Request:**
```json
{ "productId": "prod_001", "quantity": 2 }
```

#### PUT /api/v1/website/cart/items/:itemId
Update cart item quantity.

**Request:**
```json
{ "quantity": 3 }
```

#### DELETE /api/v1/website/cart/items/:itemId
Remove item from cart.

#### DELETE /api/v1/website/cart
Clear entire cart.

### 4.4 Orders (B2C)

#### POST /api/v1/website/orders
Create new order from cart.

**Auth Required:** Yes

**Request:**
```json
{
  "deliveryAddress": {
    "fullName": "John Doe",
    "phone": "+8801712345678",
    "addressLine1": "123 Main Street",
    "addressLine2": "Apartment 4B",
    "city": "Dhaka",
    "area": "Gulshan",
    "postalCode": "1212",
    "landmark": "Near ABC School"
  },
  "deliveryTimeSlot": "2026-02-01T14:00:00Z",
  "paymentMethod": "bkash",
  "specialInstructions": "Please call before delivery",
  "promoCode": "FIRST10"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "data": {
    "order": {
      "id": "ord_123456",
      "orderNumber": "SD-2026-0001",
      "status": "pending",
      "items": [],
      "subtotal": 190.00,
      "deliveryFee": 50.00,
      "discount": 19.00,
      "total": 221.00,
      "currency": "BDT",
      "paymentMethod": "bkash",
      "paymentStatus": "pending",
      "deliveryAddress": {},
      "estimatedDelivery": "2026-02-01T14:00:00Z",
      "createdAt": "2026-01-31T10:30:00Z"
    },
    "paymentUrl": "https://payment.bkash.com/checkout/..."
  },
  "message": "Order created successfully"
}
```

#### GET /api/v1/website/orders
List user's orders with filtering.

**Query Parameters:** `page`, `limit`, `status` (pending|confirmed|processing|out_for_delivery|delivered|cancelled)

#### GET /api/v1/website/orders/:id
Get full order details with timeline.

#### PATCH /api/v1/website/orders/:id/cancel
Cancel order (only if status is pending or confirmed).

#### POST /api/v1/website/orders/:id/reorder
Create new order with same items as previous order.

### 4.5 Subscriptions

#### GET /api/v1/website/subscriptions
List user's active subscriptions.

**Auth Required:** Yes

#### POST /api/v1/website/subscriptions
Create new product subscription.

**Request:**
```json
{
  "productId": "prod_001",
  "quantity": 2,
  "frequency": "daily",
  "deliveryAddressId": "addr_001",
  "startDate": "2026-02-01",
  "paymentMethod": "bkash"
}
```

#### PUT /api/v1/website/subscriptions/:id
Update subscription (quantity, frequency, pause/resume).

#### DELETE /api/v1/website/subscriptions/:id
Cancel subscription.

### 4.6 Reviews

#### GET /api/v1/website/products/:productId/reviews
Get product reviews with rating summary.

**Query Parameters:** `page`, `limit`, `rating` (1-5), `sort` (newest|helpful)

#### POST /api/v1/website/products/:productId/reviews
Create product review (must have purchased product).

**Auth Required:** Yes

**Request:**
```json
{
  "rating": 5,
  "title": "Excellent quality!",
  "comment": "Best milk I've ever tasted.",
  "images": ["file_abc123"]
}
```

### 4.7 Wishlist

#### GET /api/v1/website/wishlist
Get user's wishlist.

#### POST /api/v1/website/wishlist
Add product to wishlist.

**Request:**
```json
{ "productId": "prod_001" }
```

#### DELETE /api/v1/website/wishlist/:productId
Remove from wishlist.

### 4.8 B2B Portal

#### POST /api/v1/website/b2b/apply
Submit B2B application.

**Request:**
```json
{
  "companyName": "ABC Restaurant",
  "businessType": "restaurant",
  "tradeLicense": "file_abc123",
  "contactPerson": "Jane Smith",
  "phone": "+8801812345678",
  "email": "jane@abcrestaurant.com",
  "estimatedMonthlyVolume": "500L milk, 50kg yogurt",
  "deliveryLocations": [
    { "name": "Main Branch", "address": "123 Dhanmondi, Dhaka", "city": "Dhaka" }
  ]
}
```

#### GET /api/v1/website/b2b/account
Get B2B account details with custom pricing.

**Auth Required:** Yes (b2b_customer)

#### GET /api/v1/website/b2b/products
Get products with B2B pricing.

**Auth Required:** Yes (b2b_customer)

#### POST /api/v1/website/b2b/orders
Create B2B order (supports bulk ordering, CSV upload).

#### GET /api/v1/website/b2b/orders
List B2B orders.

#### POST /api/v1/website/b2b/quotes
Request a quote for custom order.

**Request:**
```json
{
  "items": [
    { "productId": "prod_001", "quantity": 500, "unit": "liters", "frequency": "daily" }
  ],
  "contractDuration": "6_months",
  "notes": "Need daily delivery before 7 AM"
}
```

#### GET /api/v1/website/b2b/quotes
List quotes and their status.

#### GET /api/v1/website/b2b/invoices
List B2B invoices.

#### GET /api/v1/website/b2b/invoices/:id/pdf
Download invoice PDF.

### 4.9 Blog & Content

#### GET /api/v1/website/blog/posts
List blog posts with category filtering.

**Query Parameters:** `page`, `limit`, `category`, `tag`, `search`, `language` (en|bn)

#### GET /api/v1/website/blog/posts/:slug
Get single blog post.

#### GET /api/v1/website/blog/categories
List blog categories.

#### GET /api/v1/website/pages/:slug
Get static page content (about, contact, privacy, etc.).

#### GET /api/v1/website/faq
Get FAQ items by category.

### 4.10 Virtual Tour

#### GET /api/v1/website/virtual-tour/scenes
Get virtual tour scenes and hotspots.

#### GET /api/v1/website/virtual-tour/live-feeds
Get live camera feed URLs.

#### GET /api/v1/website/farm-dashboard
Get real-time farm metrics for public display.

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "metrics": {
      "totalCattle": 260,
      "lactatingCows": 75,
      "todayMilkProduction": 892.5,
      "averageYieldPerCow": 11.9,
      "temperature": 28.5,
      "humidity": 72,
      "lastUpdated": "2026-01-31T10:00:00Z"
    }
  }
}
```

### 4.11 Contact & Newsletter

#### POST /api/v1/website/contact
Submit contact form.

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+8801712345678",
  "subject": "Product Inquiry",
  "message": "I'd like to know about bulk pricing...",
  "type": "inquiry"
}
```

#### POST /api/v1/website/newsletter/subscribe
Subscribe to newsletter.

**Request:**
```json
{ "email": "john@example.com", "language": "en" }
```

#### POST /api/v1/website/newsletter/unsubscribe
Unsubscribe from newsletter.

---

## 5. MARKETPLACE MODULE API

### 5.1 Vendor Management

#### POST /api/v1/marketplace/vendors/apply
Submit vendor application.

**Request:**
```json
{
  "businessName": "AgriFresh Supplies",
  "businessType": "agro_products",
  "ownerName": "Rahim Ahmed",
  "phone": "+8801912345678",
  "email": "rahim@agrifresh.com",
  "nidNumber": "1234567890123",
  "nidDocument": "file_nid_001",
  "tradeLicense": "file_tl_001",
  "bankAccountName": "Rahim Ahmed",
  "bankAccountNumber": "1234567890",
  "bankName": "Dutch Bangla Bank",
  "bankBranch": "Gulshan",
  "bkashNumber": "+8801912345678",
  "businessAddress": {
    "addressLine1": "45 Farm Road",
    "city": "Gazipur",
    "district": "Gazipur"
  },
  "productCategories": ["dairy_products", "agro_products"],
  "description": "Fresh farm produce supplier from Gazipur"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "data": {
    "applicationId": "vapp_001",
    "status": "pending_review"
  },
  "message": "Application submitted. You will be notified within 48 hours."
}
```

#### GET /api/v1/marketplace/vendors/me
Get vendor dashboard data.

**Auth Required:** Yes (marketplace_vendor)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "vendor": {
      "id": "vendor_001",
      "businessName": "AgriFresh Supplies",
      "status": "active",
      "rating": 4.7,
      "totalProducts": 25,
      "totalOrders": 150,
      "pendingOrders": 3,
      "totalRevenue": 125000.00,
      "pendingPayout": 8500.00,
      "commissionRate": 0.10,
      "joinedAt": "2026-06-15T00:00:00Z"
    },
    "recentOrders": [],
    "salesSummary": {
      "today": 2500.00,
      "thisWeek": 15000.00,
      "thisMonth": 45000.00
    }
  }
}
```

#### PUT /api/v1/marketplace/vendors/me
Update vendor profile.

### 5.2 Marketplace Products

#### GET /api/v1/marketplace/products
Browse marketplace products from all vendors.

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | integer | 1 | Page number |
| limit | integer | 20 | Items per page (max: 50) |
| category | string | - | Category slug |
| subcategory | string | - | Subcategory slug |
| search | string | - | Full-text search |
| minPrice | number | - | Minimum price |
| maxPrice | number | - | Maximum price |
| vendor | string | - | Vendor ID filter |
| location | string | - | Vendor location/district |
| rating | number | - | Minimum rating |
| sort | string | relevance | `price_asc`, `price_desc`, `newest`, `rating`, `bestselling` |

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "products": [
      {
        "id": "mkt_prod_001",
        "name": "Organic Rice (Miniket)",
        "slug": "organic-rice-miniket",
        "price": 85.00,
        "unit": "kg",
        "currency": "BDT",
        "category": { "id": "mcat_001", "name": "Rice & Grains", "slug": "rice-grains" },
        "images": [{ "url": "...", "isPrimary": true }],
        "vendor": {
          "id": "vendor_001",
          "name": "AgriFresh Supplies",
          "rating": 4.7,
          "location": "Gazipur"
        },
        "stock": 500,
        "inStock": true,
        "rating": 4.3,
        "reviewCount": 15,
        "soldCount": 230
      }
    ],
    "pagination": {},
    "filters": {
      "categories": [
        { "slug": "rice-grains", "name": "Rice & Grains", "count": 45 },
        { "slug": "dairy-products", "name": "Dairy Products", "count": 30 }
      ],
      "priceRange": { "min": 10, "max": 50000 }
    }
  }
}
```

#### GET /api/v1/marketplace/products/:id
Get marketplace product details.

#### POST /api/v1/marketplace/vendors/me/products
Create new product listing (vendor).

**Auth Required:** Yes (marketplace_vendor)

**Request:**
```json
{
  "name": "Organic Rice (Miniket)",
  "categoryId": "mcat_001",
  "description": "Premium quality organic Miniket rice from Gazipur farms.",
  "price": 85.00,
  "unit": "kg",
  "stock": 500,
  "minOrderQuantity": 1,
  "maxOrderQuantity": 100,
  "images": ["file_001", "file_002"],
  "weight": 1.0,
  "weightUnit": "kg",
  "tags": ["organic", "rice", "miniket"],
  "shippingInfo": {
    "weight": 1.0,
    "dimensions": { "length": 30, "width": 20, "height": 10 },
    "freeShippingAbove": 500
  }
}
```

#### PUT /api/v1/marketplace/vendors/me/products/:id
Update product listing.

#### DELETE /api/v1/marketplace/vendors/me/products/:id
Delete product listing.

#### POST /api/v1/marketplace/vendors/me/products/bulk-upload
Bulk upload products via CSV.

**Content-Type:** `multipart/form-data`

### 5.3 Marketplace Categories

#### GET /api/v1/marketplace/categories
Get marketplace category tree.

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "categories": [
      {
        "id": "mcat_001",
        "name": "Rice & Grains",
        "slug": "rice-grains",
        "icon": "grain",
        "productCount": 45,
        "children": [
          { "id": "mcat_001a", "name": "Rice", "slug": "rice", "productCount": 30 },
          { "id": "mcat_001b", "name": "Wheat", "slug": "wheat", "productCount": 15 }
        ]
      },
      {
        "id": "mcat_002",
        "name": "Farm Equipment",
        "slug": "farm-equipment",
        "icon": "tractor",
        "productCount": 80,
        "children": [
          { "id": "mcat_002a", "name": "Milking Machines", "slug": "milking-machines", "productCount": 12 },
          { "id": "mcat_002b", "name": "Cooling Tanks", "slug": "cooling-tanks", "productCount": 8 }
        ]
      }
    ]
  }
}
```

### 5.4 Marketplace Orders

#### POST /api/v1/marketplace/orders
Create marketplace order (supports multi-vendor cart).

**Auth Required:** Yes

**Request:**
```json
{
  "items": [
    { "productId": "mkt_prod_001", "quantity": 5 },
    { "productId": "mkt_prod_002", "quantity": 2 }
  ],
  "deliveryAddress": {
    "fullName": "John Doe",
    "phone": "+8801712345678",
    "addressLine1": "123 Main Street",
    "city": "Dhaka",
    "area": "Gulshan",
    "postalCode": "1212"
  },
  "paymentMethod": "bkash"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "data": {
    "orderId": "mkt_ord_001",
    "subOrders": [
      {
        "id": "mkt_sub_001",
        "vendor": { "id": "vendor_001", "name": "AgriFresh Supplies" },
        "items": [{ "productId": "mkt_prod_001", "quantity": 5, "subtotal": 425.00 }],
        "subtotal": 425.00,
        "shippingFee": 60.00,
        "status": "pending"
      },
      {
        "id": "mkt_sub_002",
        "vendor": { "id": "vendor_002", "name": "FarmTools BD" },
        "items": [{ "productId": "mkt_prod_002", "quantity": 2, "subtotal": 3500.00 }],
        "subtotal": 3500.00,
        "shippingFee": 150.00,
        "status": "pending"
      }
    ],
    "totalAmount": 4135.00,
    "paymentUrl": "https://payment.bkash.com/checkout/..."
  }
}
```

#### GET /api/v1/marketplace/orders
List buyer's marketplace orders.

#### GET /api/v1/marketplace/orders/:id
Get marketplace order details.

#### GET /api/v1/marketplace/vendors/me/orders
Get vendor's received orders.

**Auth Required:** Yes (marketplace_vendor)

#### PATCH /api/v1/marketplace/vendors/me/orders/:subOrderId/status
Update sub-order status (vendor action).

**Request:**
```json
{
  "status": "shipped",
  "trackingNumber": "TRK123456",
  "shippingProvider": "Pathao Courier"
}
```

### 5.5 Marketplace Reviews

#### GET /api/v1/marketplace/products/:id/reviews
Get marketplace product reviews.

#### POST /api/v1/marketplace/products/:id/reviews
Create marketplace product review.

#### GET /api/v1/marketplace/vendors/:vendorId/reviews
Get vendor reviews.

### 5.6 Disputes & Returns

#### POST /api/v1/marketplace/orders/:subOrderId/dispute
Raise dispute on sub-order.

**Auth Required:** Yes

**Request:**
```json
{
  "reason": "item_not_as_described",
  "description": "The rice quality is much lower than shown in photos.",
  "evidence": ["file_evidence_001"]
}
```

#### GET /api/v1/marketplace/disputes
List user's disputes.

#### POST /api/v1/marketplace/orders/:subOrderId/return
Request return.

### 5.7 Vendor Payouts

#### GET /api/v1/marketplace/vendors/me/payouts
Get vendor payout history.

**Auth Required:** Yes (marketplace_vendor)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "payouts": [
      {
        "id": "payout_001",
        "amount": 12500.00,
        "commission": 1250.00,
        "netAmount": 11250.00,
        "method": "bkash",
        "status": "completed",
        "periodStart": "2026-07-01",
        "periodEnd": "2026-07-07",
        "paidAt": "2026-07-09T10:00:00Z"
      }
    ],
    "pendingAmount": 8500.00,
    "nextPayoutDate": "2026-07-16",
    "pagination": {}
  }
}
```

#### GET /api/v1/marketplace/vendors/me/analytics
Get vendor sales analytics.

### 5.8 Marketplace Messaging

#### GET /api/v1/marketplace/conversations
List buyer-vendor conversations.

#### POST /api/v1/marketplace/conversations
Start conversation with vendor about a product.

**Request:**
```json
{
  "vendorId": "vendor_001",
  "productId": "mkt_prod_001",
  "message": "Is this available in 25kg bags?"
}
```

#### GET /api/v1/marketplace/conversations/:id/messages
Get conversation messages.

#### POST /api/v1/marketplace/conversations/:id/messages
Send message in conversation.

---

## 6. LIVESTOCK MODULE API

### 6.1 Animal Listings

#### GET /api/v1/livestock/listings
Browse animal listings.

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | integer | 1 | Page number |
| limit | integer | 20 | Items per page |
| category | string | - | `dairy_cow`, `bull`, `heifer`, `calf`, `goat`, `buffalo` |
| breed | string | - | Breed filter |
| minPrice | number | - | Min price (BDT) |
| maxPrice | number | - | Max price (BDT) |
| minAge | number | - | Min age in months |
| maxAge | number | - | Max age in months |
| location | string | - | District/division |
| listingType | string | - | `fixed_price`, `auction`, `negotiable` |
| verified | boolean | - | Only SD-verified animals |
| sort | string | newest | `price_asc`, `price_desc`, `newest`, `ending_soon` |

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "listings": [
      {
        "id": "lvs_001",
        "title": "Holstein Friesian Cross - 3rd Lactation",
        "category": "dairy_cow",
        "breed": "Holstein Friesian Cross",
        "age": { "years": 5, "months": 3 },
        "weight": 450,
        "weightUnit": "kg",
        "price": 180000.00,
        "currency": "BDT",
        "listingType": "fixed_price",
        "location": { "district": "Gazipur", "division": "Dhaka" },
        "images": [
          { "url": "...", "isPrimary": true, "angle": "side" }
        ],
        "seller": {
          "id": "usr_seller_001",
          "name": "Karim Farms",
          "rating": 4.8,
          "isVerified": true
        },
        "isVerified": true,
        "verificationBadge": "sd_verified",
        "milkYield": { "average": 18.5, "unit": "liters/day" },
        "pregnancyStatus": "not_pregnant",
        "vaccinationStatus": "up_to_date",
        "createdAt": "2026-01-25T08:00:00Z"
      }
    ],
    "pagination": {},
    "filters": {
      "categories": [
        { "slug": "dairy_cow", "name": "Dairy Cow", "count": 45 },
        { "slug": "bull", "name": "Bull", "count": 20 }
      ],
      "breeds": ["Holstein Friesian Cross", "Sahiwal", "Red Chittagong", "Pabna"],
      "priceRange": { "min": 20000, "max": 500000 }
    }
  }
}
```

#### GET /api/v1/livestock/listings/:id
Get full animal listing with health records, pedigree, and production data.

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "listing": {
      "id": "lvs_001",
      "title": "Holstein Friesian Cross - 3rd Lactation",
      "description": "Healthy dairy cow with excellent milk production record...",
      "category": "dairy_cow",
      "breed": "Holstein Friesian Cross",
      "sex": "female",
      "dateOfBirth": "2020-10-15",
      "age": { "years": 5, "months": 3 },
      "tagNumber": "SD-HF-0042",
      "color": "Black and White",
      "weight": 450,
      "bodyConditionScore": 3.5,
      "price": 180000.00,
      "listingType": "fixed_price",
      "pedigree": {
        "sire": { "name": "Champion Bull", "breed": "Holstein Friesian", "tagNumber": "HF-BULL-012" },
        "dam": { "name": "Star Cow", "breed": "Local Cross", "tagNumber": "SD-LC-089", "milkYield": 14.5 },
        "grandSire": { "name": "Import Bull", "breed": "Holstein Friesian" },
        "grandDam": { "name": "Mother Cow", "breed": "Sahiwal Cross" }
      },
      "productionData": {
        "currentLactation": 3,
        "averageDailyYield": 18.5,
        "peakYield": 24.0,
        "totalLactationYield305": 5200,
        "fatPercentage": 3.8,
        "snfPercentage": 8.5
      },
      "healthRecords": [
        {
          "type": "vaccination",
          "name": "FMD Vaccine",
          "date": "2025-12-01",
          "nextDue": "2026-06-01",
          "veterinarian": "Dr. Rahman"
        },
        {
          "type": "vaccination",
          "name": "Anthrax Vaccine",
          "date": "2025-11-15",
          "nextDue": "2026-11-15"
        }
      ],
      "images": [
        { "url": "...", "angle": "front", "isPrimary": false },
        { "url": "...", "angle": "side", "isPrimary": true },
        { "url": "...", "angle": "rear", "isPrimary": false },
        { "url": "...", "angle": "udder", "isPrimary": false }
      ],
      "certificates": [
        { "type": "health_certificate", "url": "...", "issuedBy": "DLS Gazipur", "issuedDate": "2026-01-20" }
      ],
      "seller": {
        "id": "usr_seller_001",
        "name": "Karim Farms",
        "phone": "+8801812345678",
        "location": { "district": "Gazipur", "division": "Dhaka" },
        "rating": 4.8,
        "totalSales": 25,
        "memberSince": "2026-01-01"
      },
      "location": { "district": "Gazipur", "division": "Dhaka" },
      "isVerified": true,
      "views": 234,
      "inquiries": 8,
      "createdAt": "2026-01-25T08:00:00Z"
    }
  }
}
```

#### POST /api/v1/livestock/listings
Create animal listing.

**Auth Required:** Yes (livestock_seller)

**Request:**
```json
{
  "title": "Holstein Friesian Cross - 3rd Lactation",
  "category": "dairy_cow",
  "breed": "Holstein Friesian Cross",
  "sex": "female",
  "dateOfBirth": "2020-10-15",
  "tagNumber": "SD-HF-0042",
  "color": "Black and White",
  "weight": 450,
  "bodyConditionScore": 3.5,
  "price": 180000.00,
  "listingType": "fixed_price",
  "description": "Healthy dairy cow...",
  "images": ["file_001", "file_002", "file_003", "file_004"],
  "healthCertificates": ["file_cert_001"],
  "sourceAnimalId": "erp_animal_042",
  "location": { "district": "Gazipur", "division": "Dhaka" }
}
```

#### PUT /api/v1/livestock/listings/:id
Update listing.

#### DELETE /api/v1/livestock/listings/:id
Delete listing.

### 6.2 Auctions

#### GET /api/v1/livestock/auctions
List active and upcoming auctions.

**Query Parameters:** `status` (active|upcoming|ended), `category`, `page`, `limit`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "auctions": [
      {
        "id": "auction_001",
        "listing": {
          "id": "lvs_002",
          "title": "Premium Sahiwal Bull",
          "breed": "Sahiwal",
          "images": [{ "url": "...", "isPrimary": true }]
        },
        "startingPrice": 150000.00,
        "currentBid": 185000.00,
        "bidCount": 12,
        "highestBidder": "usr_***456",
        "startTime": "2026-01-31T09:00:00Z",
        "endTime": "2026-01-31T18:00:00Z",
        "status": "active",
        "timeRemaining": 28800
      }
    ],
    "pagination": {}
  }
}
```

#### GET /api/v1/livestock/auctions/:id
Get auction details with bid history.

#### POST /api/v1/livestock/auctions/:id/bid
Place bid on auction (also via Socket.IO for real-time).

**Auth Required:** Yes (livestock_buyer)

**Request:**
```json
{
  "amount": 190000.00
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "bid": {
      "id": "bid_001",
      "amount": 190000.00,
      "isHighest": true,
      "auctionEndTime": "2026-01-31T18:00:00Z"
    }
  },
  "message": "Bid placed successfully"
}
```

### 6.3 Offers & Negotiations

#### POST /api/v1/livestock/listings/:id/offers
Make offer on negotiable listing.

**Auth Required:** Yes (livestock_buyer)

**Request:**
```json
{
  "amount": 165000.00,
  "message": "I am interested. Can we negotiate?"
}
```

#### GET /api/v1/livestock/offers
List sent/received offers.

#### PATCH /api/v1/livestock/offers/:id
Accept, reject, or counter offer.

**Request:**
```json
{
  "action": "counter",
  "counterAmount": 175000.00,
  "message": "My lowest is 175,000 BDT."
}
```

### 6.4 Livestock Transactions

#### POST /api/v1/livestock/transactions
Create transaction after price agreement.

**Auth Required:** Yes

**Request:**
```json
{
  "listingId": "lvs_001",
  "agreedPrice": 175000.00,
  "paymentMethod": "bkash",
  "transportRequired": true,
  "insuranceRequired": false,
  "deliveryAddress": {
    "district": "Manikganj",
    "upazila": "Singair",
    "village": "Balara",
    "landmark": "Near Balara Bazar"
  }
}
```

#### GET /api/v1/livestock/transactions
List user's transactions.

#### GET /api/v1/livestock/transactions/:id
Get transaction details with transport tracking.

### 6.5 Transport

#### GET /api/v1/livestock/transport/estimate
Get transport cost estimate.

**Query Parameters:** `fromDistrict`, `toDistrict`, `animalWeight`, `animalCount`, `vehicleType`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "estimates": [
      {
        "provider": "BD Animal Transport",
        "vehicleType": "covered_truck",
        "estimatedCost": 8000.00,
        "estimatedDuration": "4-6 hours",
        "distance": 120,
        "distanceUnit": "km",
        "insuranceOption": { "available": true, "cost": 2000.00 }
      }
    ]
  }
}
```

#### POST /api/v1/livestock/transport/book
Book transport for livestock transaction.

### 6.6 Verifications

#### POST /api/v1/livestock/listings/:id/verify
Request SD verification for a listing.

**Auth Required:** Yes (livestock_seller)

#### GET /api/v1/livestock/verifications/:id
Get verification status and report.

### 6.7 Favorites & Alerts

#### GET /api/v1/livestock/favorites
Get saved favorite listings.

#### POST /api/v1/livestock/favorites
Save listing to favorites.

#### POST /api/v1/livestock/alerts
Create price/availability alert.

**Request:**
```json
{
  "category": "dairy_cow",
  "breed": "Holstein Friesian Cross",
  "maxPrice": 200000,
  "minMilkYield": 15,
  "location": "Dhaka"
}
```

### 6.8 Inquiries

#### POST /api/v1/livestock/listings/:id/inquiries
Send inquiry to seller.

#### GET /api/v1/livestock/inquiries
List inquiries (sent/received).

---

## 7. ERP MODULE API

### 7.1 Herd Management

#### GET /api/v1/erp/animals
List all animals with filtering.

**Auth Required:** Yes (farm_manager | farm_worker | veterinarian)

**Query Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| page | integer | Page number |
| limit | integer | Items per page |
| group | string | Group filter (lactating, heifers, bulls, calves, dry) |
| breed | string | Breed filter |
| status | string | `active`, `sold`, `deceased`, `culled` |
| search | string | Search by tag number or name |
| sort | string | `tag_asc`, `tag_desc`, `age`, `yield` |

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "animals": [
      {
        "id": "animal_001",
        "tagNumber": "SD-HF-0042",
        "name": "Laxmi",
        "breed": "Holstein Friesian Cross",
        "sex": "female",
        "dateOfBirth": "2020-10-15",
        "age": { "years": 5, "months": 3 },
        "group": "lactating",
        "status": "active",
        "currentWeight": 450,
        "bodyConditionScore": 3.5,
        "photo": "https://cdn.smartdairy.com/animals/sd-hf-0042.jpg",
        "currentLactation": 3,
        "averageDailyYield": 18.5,
        "lastHealthEvent": "2026-01-15",
        "pregnancyStatus": "not_pregnant",
        "daysInMilk": 120
      }
    ],
    "summary": {
      "totalActive": 260,
      "lactating": 75,
      "heifers": 100,
      "bulls": 40,
      "calves": 30,
      "dry": 15
    },
    "pagination": {}
  }
}
```

#### GET /api/v1/erp/animals/:id
Get full animal record with history.

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "animal": {
      "id": "animal_001",
      "tagNumber": "SD-HF-0042",
      "name": "Laxmi",
      "breed": "Holstein Friesian Cross",
      "breedComposition": { "holsteinFriesian": 75, "local": 25 },
      "sex": "female",
      "dateOfBirth": "2020-10-15",
      "color": "Black and White",
      "group": "lactating",
      "status": "active",
      "sire": { "id": "animal_bull_01", "tagNumber": "SD-BULL-012", "name": "Champion", "breed": "Holstein Friesian" },
      "dam": { "id": "animal_089", "tagNumber": "SD-LC-089", "name": "Star", "breed": "Local Cross" },
      "weights": [
        { "date": "2026-01-15", "weight": 450, "bodyConditionScore": 3.5 },
        { "date": "2025-10-10", "weight": 445, "bodyConditionScore": 3.0 }
      ],
      "currentLactation": 3,
      "lactationSummary": [
        { "lactationNumber": 1, "totalYield305": 3800, "peakYield": 18.0, "calvingDate": "2022-11-20" },
        { "lactationNumber": 2, "totalYield305": 4500, "peakYield": 22.0, "calvingDate": "2024-01-10" },
        { "lactationNumber": 3, "totalYield305": null, "peakYield": 24.0, "calvingDate": "2025-10-03", "inProgress": true }
      ],
      "recentMilkRecords": [],
      "recentHealthEvents": [],
      "breedingHistory": [],
      "photos": [],
      "notes": ""
    }
  }
}
```

#### POST /api/v1/erp/animals
Register new animal.

**Auth Required:** Yes (farm_manager)

**Request:**
```json
{
  "tagNumber": "SD-HF-0100",
  "name": "Nandini",
  "breed": "Holstein Friesian Cross",
  "sex": "female",
  "dateOfBirth": "2026-01-15",
  "color": "Black and White",
  "group": "calves",
  "sireId": "animal_bull_01",
  "damId": "animal_042",
  "birthWeight": 35,
  "birthType": "normal",
  "photos": ["file_001"]
}
```

#### PUT /api/v1/erp/animals/:id
Update animal record.

#### POST /api/v1/erp/animals/:id/move
Move animal to different group.

**Request:**
```json
{
  "toGroup": "heifers",
  "reason": "Age-based transfer",
  "date": "2026-01-31"
}
```

#### POST /api/v1/erp/animals/:id/weight
Record animal weight.

**Request:**
```json
{
  "weight": 455,
  "bodyConditionScore": 3.5,
  "date": "2026-01-31",
  "method": "scale"
}
```

### 7.2 Milk Production

#### POST /api/v1/erp/milk/records
Record milk production (individual cow).

**Auth Required:** Yes (farm_worker | farm_manager)

**Request:**
```json
{
  "animalId": "animal_001",
  "session": "morning",
  "quantity": 9.5,
  "unit": "liters",
  "fatPercentage": 3.8,
  "snfPercentage": 8.5,
  "proteinPercentage": 3.2,
  "somaticCellCount": 150000,
  "recordDate": "2026-01-31",
  "recordedBy": "usr_worker_01"
}
```

#### POST /api/v1/erp/milk/records/batch
Record milk for multiple cows at once (mobile optimized).

**Request:**
```json
{
  "session": "morning",
  "recordDate": "2026-01-31",
  "records": [
    { "animalId": "animal_001", "quantity": 9.5 },
    { "animalId": "animal_002", "quantity": 8.0 },
    { "animalId": "animal_003", "quantity": 11.2 }
  ]
}
```

#### GET /api/v1/erp/milk/records
Get milk records with date range filtering.

**Query Parameters:** `startDate`, `endDate`, `animalId`, `session` (morning|evening), `page`, `limit`

#### GET /api/v1/erp/milk/summary/daily
Get daily milk production summary.

**Query Parameters:** `date` (default: today)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "date": "2026-01-31",
    "summary": {
      "totalMorning": 475.5,
      "totalEvening": 417.0,
      "totalDaily": 892.5,
      "cowsMilked": 75,
      "averagePerCow": 11.9,
      "targetDaily": 900,
      "targetAchievement": 99.2,
      "avgFat": 3.7,
      "avgSnf": 8.4
    },
    "topProducers": [
      { "animalId": "animal_015", "tagNumber": "SD-HF-0015", "name": "Rani", "total": 24.0 }
    ],
    "lowProducers": [
      { "animalId": "animal_062", "tagNumber": "SD-HF-0062", "name": "Maya", "total": 4.5, "alert": "20% below 7-day average" }
    ]
  }
}
```

#### GET /api/v1/erp/milk/summary/monthly
Get monthly milk production report.

#### POST /api/v1/erp/milk/bulk-tank
Record bulk tank collection.

**Request:**
```json
{
  "tankId": "tank_01",
  "session": "morning",
  "quantity": 475.5,
  "temperature": 4.2,
  "collectorName": "Rahim",
  "collectorCompany": "Milk Vita",
  "qualityMetrics": { "fat": 3.7, "snf": 8.4, "acidity": 0.14 },
  "recordDate": "2026-01-31"
}
```

#### POST /api/v1/erp/milk/quality-test
Record milk quality test result.

### 7.3 Health Management

#### POST /api/v1/erp/health/events
Record health event.

**Auth Required:** Yes (farm_worker | veterinarian | farm_manager)

**Request:**
```json
{
  "animalId": "animal_001",
  "eventType": "illness",
  "diagnosis": "Mastitis - mild",
  "symptoms": ["reduced_milk", "udder_swelling", "warm_quarter"],
  "affectedQuarter": "front_left",
  "severity": "mild",
  "treatment": "Intramammary antibiotics (Ceftizox)",
  "treatmentDuration": "3 days",
  "withdrawalPeriod": "4 days",
  "veterinarian": "Dr. Rahman",
  "cost": 1500.00,
  "notes": "Monitor for 7 days",
  "date": "2026-01-31"
}
```

#### GET /api/v1/erp/health/events
List health events with filtering.

**Query Parameters:** `animalId`, `eventType` (illness|injury|treatment|surgery|checkup), `startDate`, `endDate`, `page`, `limit`

#### GET /api/v1/erp/health/vaccinations/schedule
Get vaccination schedule with due/overdue tracking.

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "schedules": [
      {
        "vaccineName": "FMD",
        "protocol": "Every 6 months",
        "due": [
          { "animalId": "animal_001", "tagNumber": "SD-HF-0042", "dueDate": "2026-06-01", "status": "upcoming" },
          { "animalId": "animal_008", "tagNumber": "SD-HF-0008", "dueDate": "2026-01-25", "status": "overdue" }
        ],
        "totalDue": 15,
        "totalOverdue": 2
      },
      {
        "vaccineName": "Anthrax",
        "protocol": "Annual",
        "due": [],
        "totalDue": 0,
        "totalOverdue": 0
      }
    ],
    "summary": {
      "totalDueThisWeek": 8,
      "totalOverdue": 3,
      "upcomingThisMonth": 22
    }
  }
}
```

#### POST /api/v1/erp/health/vaccinations
Record vaccination.

**Request:**
```json
{
  "animalIds": ["animal_001", "animal_002", "animal_003"],
  "vaccineName": "FMD",
  "batchNumber": "FMD-2026-B001",
  "manufacturer": "LRI",
  "administeredBy": "Dr. Rahman",
  "route": "intramuscular",
  "dosage": "5ml",
  "cost": 50.00,
  "nextDueDate": "2026-07-31",
  "date": "2026-01-31"
}
```

#### POST /api/v1/erp/health/disease-incident
Report disease incident with quarantine management.

### 7.4 Breeding Management

#### POST /api/v1/erp/breeding/records
Record breeding event.

**Auth Required:** Yes (farm_manager | veterinarian)

**Request:**
```json
{
  "animalId": "animal_001",
  "method": "artificial_insemination",
  "sireId": "animal_bull_01",
  "semenBatch": "HF-SEMEN-2026-001",
  "inseminatorName": "AI Technician Karim",
  "date": "2026-01-31",
  "notes": "Second service this lactation"
}
```

#### GET /api/v1/erp/breeding/records
List breeding records.

#### POST /api/v1/erp/breeding/pregnancy-check
Record pregnancy check result.

**Request:**
```json
{
  "animalId": "animal_001",
  "method": "rectal_palpation",
  "result": "positive",
  "estimatedDaysPregnant": 45,
  "expectedCalvingDate": "2026-11-15",
  "checkedBy": "Dr. Rahman",
  "date": "2026-01-31"
}
```

#### POST /api/v1/erp/breeding/calving
Record calving event.

**Request:**
```json
{
  "damId": "animal_001",
  "calvingDate": "2026-01-31",
  "difficultyScore": 1,
  "calfDetails": {
    "sex": "female",
    "weight": 35,
    "status": "alive",
    "tagNumber": "SD-HF-0100",
    "name": "Nandini"
  },
  "complications": "none",
  "assistedBy": "Farm Staff"
}
```

#### GET /api/v1/erp/breeding/expected-calvings
Get expected calving schedule.

#### GET /api/v1/erp/breeding/kpis
Get breeding KPIs.

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "kpis": {
      "averageCalvingInterval": 13.2,
      "conceptionRate": 42.5,
      "servicesPerConception": 2.1,
      "voluntaryWaitingPeriod": 55,
      "pregnancyRate": 18.5,
      "abortionRate": 2.1,
      "calfSurvivalRate": 95.0,
      "period": "last_12_months"
    },
    "targets": {
      "calvingInterval": { "target": "12-13 months", "status": "above_target" },
      "conceptionRate": { "target": "40-50%", "status": "on_target" }
    }
  }
}
```

### 7.5 Feed Management

#### GET /api/v1/erp/feed/items
List feed inventory items.

#### POST /api/v1/erp/feed/items
Add feed item to library.

**Request:**
```json
{
  "name": "Napier Grass (Fresh)",
  "category": "green_fodder",
  "dryMatterPercent": 20,
  "crudeProteinPercent": 10.5,
  "tdnPercent": 55,
  "costPerKg": 3.50,
  "unit": "kg",
  "currentStock": 5000,
  "reorderLevel": 1000,
  "supplier": "Local Farm"
}
```

#### GET /api/v1/erp/feed/rations
List feed rations by group.

#### POST /api/v1/erp/feed/rations
Create/update feed ration formula.

**Request:**
```json
{
  "name": "Lactating Cow Ration - High Yield",
  "targetGroup": "lactating",
  "targetYield": "15+ liters/day",
  "ingredients": [
    { "feedItemId": "feed_001", "name": "Napier Grass", "quantity": 25, "unit": "kg" },
    { "feedItemId": "feed_002", "name": "Rice Straw", "quantity": 5, "unit": "kg" },
    { "feedItemId": "feed_003", "name": "Wheat Bran", "quantity": 3, "unit": "kg" },
    { "feedItemId": "feed_004", "name": "Mustard Oil Cake", "quantity": 1.5, "unit": "kg" },
    { "feedItemId": "feed_005", "name": "Rice Polish", "quantity": 2, "unit": "kg" }
  ],
  "totalDM": 15.2,
  "totalCP": 12.5,
  "totalTDN": 62,
  "estimatedCostPerDay": 185.00
}
```

#### POST /api/v1/erp/feed/consumption
Record daily feed consumption.

**Request:**
```json
{
  "groupId": "group_lactating",
  "rationId": "ration_001",
  "animalCount": 75,
  "date": "2026-01-31",
  "actualIngredients": [
    { "feedItemId": "feed_001", "quantityUsed": 1875 },
    { "feedItemId": "feed_002", "quantityUsed": 375 }
  ]
}
```

#### GET /api/v1/erp/feed/inventory
Get feed inventory with alerts.

### 7.6 Financial Management

#### GET /api/v1/erp/finance/transactions
List financial transactions.

**Query Parameters:** `type` (income|expense), `category`, `startDate`, `endDate`, `page`, `limit`

#### POST /api/v1/erp/finance/transactions
Record financial transaction.

**Request:**
```json
{
  "type": "income",
  "category": "milk_sales",
  "amount": 45000.00,
  "description": "Morning milk sold to Milk Vita",
  "date": "2026-01-31",
  "paymentMethod": "bank_transfer",
  "referenceNumber": "MV-2026-0131",
  "relatedModule": "milk_sales",
  "relatedId": "milk_sale_001"
}
```

#### GET /api/v1/erp/finance/milk-sales
List milk sales records.

#### POST /api/v1/erp/finance/milk-sales
Record milk sale.

#### GET /api/v1/erp/finance/cost-per-liter
Get cost per liter analysis.

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "period": "2026-01",
    "costPerLiter": {
      "feedCost": 28.50,
      "laborCost": 8.20,
      "veterinaryCost": 2.10,
      "overheadCost": 5.30,
      "totalCostPerLiter": 44.10,
      "sellingPricePerLiter": 65.00,
      "marginPerLiter": 20.90,
      "marginPercent": 32.2
    },
    "feedCostRatio": 64.6,
    "target": { "feedCostRatio": "< 55%", "status": "above_target" }
  }
}
```

#### GET /api/v1/erp/finance/profit-loss
Get monthly P&L report.

**Query Parameters:** `month` (YYYY-MM)

#### GET /api/v1/erp/finance/reports/:type/pdf
Download financial report as PDF.

### 7.7 HR & Attendance

#### GET /api/v1/erp/hr/employees
List employees.

#### POST /api/v1/erp/hr/employees
Add employee record.

#### POST /api/v1/erp/hr/attendance
Record attendance (mobile check-in/out).

**Request:**
```json
{
  "employeeId": "emp_001",
  "type": "check_in",
  "timestamp": "2026-01-31T06:00:00Z",
  "location": { "lat": 23.9001, "lng": 90.4125 },
  "method": "mobile_gps"
}
```

#### GET /api/v1/erp/hr/attendance
Get attendance records.

#### GET /api/v1/erp/hr/payroll/:month
Get monthly payroll summary.

#### POST /api/v1/erp/hr/payroll/:month/process
Process monthly payroll.

### 7.8 Inventory & Equipment

#### GET /api/v1/erp/inventory/items
List inventory items (medicines, supplies).

#### POST /api/v1/erp/inventory/items
Add inventory item.

#### POST /api/v1/erp/inventory/transactions
Record inventory transaction (purchase, usage, adjustment).

#### GET /api/v1/erp/inventory/alerts
Get low stock and expiry alerts.

#### GET /api/v1/erp/equipment
List farm equipment.

#### POST /api/v1/erp/equipment
Add equipment to asset register.

#### GET /api/v1/erp/equipment/:id/maintenance
Get maintenance history.

#### POST /api/v1/erp/equipment/:id/maintenance
Record maintenance event.

### 7.9 Suppliers & Purchase Orders

#### GET /api/v1/erp/suppliers
List suppliers.

#### POST /api/v1/erp/suppliers
Add supplier.

#### POST /api/v1/erp/purchase-orders
Create purchase order.

#### GET /api/v1/erp/purchase-orders
List purchase orders.

#### PATCH /api/v1/erp/purchase-orders/:id/receive
Mark PO as received.

### 7.10 ERP Dashboard & Reports

#### GET /api/v1/erp/dashboard
Get real-time ERP dashboard data.

**Auth Required:** Yes (farm_manager)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "herd": {
      "totalActive": 260,
      "lactating": 75,
      "dry": 15,
      "pregnant": 42,
      "calvingExpected30Days": 3
    },
    "production": {
      "todayTotal": 892.5,
      "todayTarget": 900,
      "yesterdayTotal": 885.0,
      "monthToDate": 27267.5,
      "averagePerCow": 11.9,
      "trend": "stable"
    },
    "health": {
      "overdueVaccinations": 3,
      "sickAnimals": 1,
      "inTreatment": 2,
      "quarantined": 0
    },
    "financial": {
      "mtdIncome": 1750000.00,
      "mtdExpense": 1200000.00,
      "mtdProfit": 550000.00,
      "costPerLiter": 44.10
    },
    "inventory": {
      "lowStockAlerts": 2,
      "expiringItems": 1
    },
    "tasks": {
      "overdueTasks": 3,
      "dueTodayTasks": 8
    },
    "lastUpdated": "2026-01-31T10:30:00Z"
  }
}
```

#### GET /api/v1/erp/reports/production
Get detailed production report.

**Query Parameters:** `period` (daily|weekly|monthly), `startDate`, `endDate`, `format` (json|pdf)

#### GET /api/v1/erp/reports/herd
Get herd composition and movement report.

#### GET /api/v1/erp/reports/reproduction
Get reproduction performance report.

#### GET /api/v1/erp/reports/financial
Get financial summary report.

#### GET /api/v1/erp/settings
Get ERP configuration settings.

#### PUT /api/v1/erp/settings
Update ERP settings (farm info, targets, alert preferences).

---

## 8. MOBILE API

### 8.1 Sync

#### POST /api/v1/mobile/sync/pull
Pull latest data from server to device.

**Auth Required:** Yes

**Request:**
```json
{
  "lastSyncTimestamp": "2026-01-31T06:00:00Z",
  "modules": ["erp"],
  "entities": ["animals", "vaccination_schedules", "today_tasks"]
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "animals": {
      "updated": [
        { "id": "animal_001", "tagNumber": "SD-HF-0042", "name": "Laxmi", "group": "lactating" }
      ],
      "deleted": [],
      "lastModified": "2026-01-31T09:30:00Z"
    },
    "vaccinationSchedules": {
      "updated": [],
      "deleted": [],
      "lastModified": "2026-01-30T14:00:00Z"
    },
    "serverTimestamp": "2026-01-31T10:30:00Z"
  }
}
```

#### POST /api/v1/mobile/sync/push
Push offline-recorded data to server.

**Request:**
```json
{
  "deviceId": "device_abc123",
  "records": [
    {
      "localId": "local_001",
      "entity": "milk_records",
      "action": "create",
      "data": {
        "animalId": "animal_001",
        "session": "morning",
        "quantity": 9.5,
        "recordDate": "2026-01-31"
      },
      "createdAt": "2026-01-31T06:15:00Z"
    },
    {
      "localId": "local_002",
      "entity": "health_events",
      "action": "create",
      "data": {
        "animalId": "animal_008",
        "eventType": "illness",
        "diagnosis": "Mild lameness"
      },
      "createdAt": "2026-01-31T06:30:00Z"
    }
  ]
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "synced": [
      { "localId": "local_001", "serverId": "milk_rec_12345", "status": "created" },
      { "localId": "local_002", "serverId": "health_evt_789", "status": "created" }
    ],
    "conflicts": [],
    "failed": [],
    "serverTimestamp": "2026-01-31T10:31:00Z"
  }
}
```

### 8.2 Push Notifications

#### POST /api/v1/mobile/devices/register
Register device for push notifications.

**Request:**
```json
{
  "deviceId": "device_abc123",
  "platform": "android",
  "pushToken": "fcm_token_xyz...",
  "appVersion": "1.0.0"
}
```

#### PUT /api/v1/mobile/devices/:deviceId/preferences
Update notification preferences.

**Request:**
```json
{
  "vaccinationReminders": true,
  "calvingAlerts": true,
  "milkAnomalies": true,
  "orderUpdates": true,
  "auctionAlerts": true,
  "marketplaceMessages": true
}
```

### 8.3 Quick Actions (Mobile-Optimized)

#### POST /api/v1/mobile/quick/milk-record
Simplified milk recording (scan tag + enter quantity).

**Request:**
```json
{
  "tagNumber": "SD-HF-0042",
  "quantity": 9.5,
  "session": "morning",
  "offline": false
}
```

#### POST /api/v1/mobile/quick/health-alert
Quick health alert from field.

**Request:**
```json
{
  "tagNumber": "SD-HF-0008",
  "alertType": "emergency",
  "symptoms": "Unable to stand, rapid breathing",
  "photos": ["file_001"],
  "location": { "lat": 23.9001, "lng": 90.4125 }
}
```

---

## 9. WEBSOCKET EVENTS

### 9.1 Connection

Connect via Socket.IO with JWT authentication:

```javascript
const socket = io('wss://api.smartdairy.com', {
  auth: { token: 'Bearer eyJhbGci...' },
  query: { module: 'livestock' }
});
```

### 9.2 Namespaces

| Namespace | Purpose | Users |
|-----------|---------|-------|
| `/livestock` | Auction bidding, listing updates | Buyers, sellers |
| `/erp` | IoT sensor data, farm alerts | Farm staff |
| `/marketplace` | Order updates, chat | Buyers, vendors |
| `/notifications` | Real-time notifications | All authenticated |

### 9.3 Livestock Auction Events

**Client -> Server:**

| Event | Payload | Description |
|-------|---------|-------------|
| `auction:join` | `{ auctionId }` | Join auction room |
| `auction:bid` | `{ auctionId, amount }` | Place bid |
| `auction:leave` | `{ auctionId }` | Leave auction room |

**Server -> Client:**

| Event | Payload | Description |
|-------|---------|-------------|
| `auction:bid_placed` | `{ auctionId, amount, bidder, timestamp }` | New bid notification |
| `auction:outbid` | `{ auctionId, newAmount }` | You were outbid |
| `auction:ending_soon` | `{ auctionId, remainingSeconds }` | Auction ending in <5 min |
| `auction:ended` | `{ auctionId, winningBid, winner }` | Auction concluded |

### 9.4 ERP IoT Events

**Server -> Client:**

| Event | Payload | Description |
|-------|---------|-------------|
| `iot:milk_reading` | `{ animalTag, session, quantity, timestamp }` | Real-time milk meter reading |
| `iot:temperature` | `{ location, value, unit, timestamp }` | Temperature sensor reading |
| `iot:tank_level` | `{ tankId, level, temperature, timestamp }` | Bulk tank status |
| `iot:alert` | `{ type, message, severity, timestamp }` | IoT alert (temp out of range, etc.) |

### 9.5 Notification Events

**Server -> Client:**

| Event | Payload | Description |
|-------|---------|-------------|
| `notification:new` | `{ id, type, title, message, module }` | New notification |
| `notification:count` | `{ unreadCount }` | Updated unread count |

---

## 10. DATA MODELS

### 10.1 Core Models

```typescript
// User (common schema)
interface User {
  id: string;
  name: string;
  email: string;
  phone: string;
  avatar?: string;
  roles: UserRole[];
  permissions: string[];
  isVerified: boolean;
  isActive: boolean;
  preferredLanguage: 'en' | 'bn';
  lastLogin?: Date;
  createdAt: Date;
  updatedAt: Date;
}

type UserRole = 'super_admin' | 'farm_manager' | 'farm_worker' | 'veterinarian'
  | 'marketplace_vendor' | 'b2b_customer' | 'customer'
  | 'livestock_buyer' | 'livestock_seller' | 'content_manager';
```

### 10.2 Website Models

```typescript
interface Product {
  id: string;
  name: string;
  slug: string;
  sku: string;
  description: string;
  shortDescription: string;
  price: number;
  salePrice?: number;
  currency: 'BDT';
  categoryId: string;
  images: ProductImage[];
  stock: number;
  inStock: boolean;
  packageSize: string;
  nutritionalInfo?: NutritionalInfo;
  rating: number;
  reviewCount: number;
  status: 'active' | 'draft' | 'archived';
  createdAt: Date;
  updatedAt: Date;
}

interface Order {
  id: string;
  orderNumber: string;
  userId: string;
  status: 'pending' | 'confirmed' | 'processing' | 'out_for_delivery' | 'delivered' | 'cancelled';
  items: OrderItem[];
  subtotal: number;
  deliveryFee: number;
  discount: number;
  total: number;
  paymentMethod: string;
  paymentStatus: 'pending' | 'paid' | 'failed' | 'refunded';
  deliveryAddress: Address;
  timeline: OrderTimelineEntry[];
  createdAt: Date;
  updatedAt: Date;
}
```

### 10.3 Marketplace Models

```typescript
interface Vendor {
  id: string;
  userId: string;
  businessName: string;
  businessType: string;
  status: 'pending' | 'active' | 'suspended' | 'rejected';
  rating: number;
  commissionRate: number;
  totalProducts: number;
  totalOrders: number;
  createdAt: Date;
}

interface MarketplaceProduct {
  id: string;
  vendorId: string;
  name: string;
  slug: string;
  categoryId: string;
  price: number;
  unit: string;
  stock: number;
  images: ProductImage[];
  rating: number;
  soldCount: number;
  status: 'active' | 'draft' | 'suspended';
  createdAt: Date;
}

interface MarketplaceOrder {
  id: string;
  buyerId: string;
  subOrders: SubOrder[];
  totalAmount: number;
  paymentStatus: string;
  createdAt: Date;
}

interface SubOrder {
  id: string;
  vendorId: string;
  items: OrderItem[];
  subtotal: number;
  shippingFee: number;
  commission: number;
  status: 'pending' | 'confirmed' | 'shipped' | 'delivered' | 'disputed' | 'returned';
}
```

### 10.4 Livestock Models

```typescript
interface AnimalListing {
  id: string;
  sellerId: string;
  title: string;
  category: 'dairy_cow' | 'bull' | 'heifer' | 'calf' | 'goat' | 'buffalo';
  breed: string;
  sex: 'male' | 'female';
  dateOfBirth: Date;
  weight: number;
  price: number;
  listingType: 'fixed_price' | 'auction' | 'negotiable';
  pedigree?: Pedigree;
  healthRecords: HealthRecord[];
  productionData?: ProductionData;
  images: ListingImage[];
  location: Location;
  isVerified: boolean;
  status: 'active' | 'sold' | 'expired' | 'withdrawn';
  createdAt: Date;
}

interface Auction {
  id: string;
  listingId: string;
  startingPrice: number;
  currentBid: number;
  bidCount: number;
  startTime: Date;
  endTime: Date;
  status: 'upcoming' | 'active' | 'ended' | 'cancelled';
}
```

### 10.5 ERP Models

```typescript
interface Animal {
  id: string;
  tagNumber: string;
  name?: string;
  breed: string;
  sex: 'male' | 'female';
  dateOfBirth: Date;
  group: 'lactating' | 'dry' | 'heifers' | 'bulls' | 'calves';
  status: 'active' | 'sold' | 'deceased' | 'culled';
  sireId?: string;
  damId?: string;
  currentWeight?: number;
  bodyConditionScore?: number;
  currentLactation?: number;
  pregnancyStatus?: 'not_pregnant' | 'pregnant' | 'confirmed';
  createdAt: Date;
}

interface MilkRecord {
  id: string;
  animalId: string;
  session: 'morning' | 'evening';
  quantity: number;
  fatPercentage?: number;
  snfPercentage?: number;
  recordDate: Date;
  recordedBy: string;
}

interface HealthEvent {
  id: string;
  animalId: string;
  eventType: 'illness' | 'injury' | 'treatment' | 'surgery' | 'checkup';
  diagnosis: string;
  treatment?: string;
  veterinarian?: string;
  cost?: number;
  date: Date;
}

interface BreedingRecord {
  id: string;
  animalId: string;
  method: 'natural' | 'artificial_insemination' | 'embryo_transfer';
  sireId: string;
  result?: 'pending' | 'pregnant' | 'not_pregnant' | 'aborted';
  date: Date;
}
```

---

## 11. ERROR HANDLING

### 11.1 Error Codes

| HTTP Status | Error Code | Description |
|-------------|------------|-------------|
| 400 | BAD_REQUEST | Invalid request data |
| 400 | VALIDATION_ERROR | Input validation failed |
| 401 | UNAUTHORIZED | Authentication required |
| 401 | INVALID_CREDENTIALS | Wrong email/password |
| 401 | TOKEN_EXPIRED | Access token expired |
| 401 | TOKEN_INVALID | Malformed or tampered token |
| 403 | FORBIDDEN | Insufficient permissions |
| 403 | ACCOUNT_SUSPENDED | Account is suspended |
| 403 | VENDOR_NOT_APPROVED | Vendor application pending |
| 404 | NOT_FOUND | Resource not found |
| 404 | PRODUCT_NOT_FOUND | Product does not exist |
| 404 | ORDER_NOT_FOUND | Order does not exist |
| 404 | ANIMAL_NOT_FOUND | Animal record not found |
| 409 | CONFLICT | Resource already exists |
| 409 | DUPLICATE_EMAIL | Email already registered |
| 409 | OUT_OF_STOCK | Product is out of stock |
| 409 | BID_TOO_LOW | Auction bid below current price |
| 409 | AUCTION_ENDED | Auction has already ended |
| 422 | UNPROCESSABLE | Request understood but cannot process |
| 429 | RATE_LIMIT_EXCEEDED | Too many requests |
| 500 | INTERNAL_ERROR | Server error |
| 502 | PAYMENT_GATEWAY_ERROR | Payment provider error |
| 503 | SERVICE_UNAVAILABLE | Service temporarily unavailable |

### 11.2 Module-Specific Error Codes

**Marketplace:**
| Code | Description |
|------|-------------|
| VENDOR_APPLICATION_REJECTED | Vendor was rejected |
| PRODUCT_LIMIT_REACHED | Max products per vendor |
| PAYOUT_MINIMUM_NOT_MET | Below minimum payout amount |

**Livestock:**
| Code | Description |
|------|-------------|
| LISTING_EXPIRED | Listing has expired |
| VERIFICATION_REQUIRED | SD verification needed |
| TRANSPORT_UNAVAILABLE | No transport providers |

**ERP:**
| Code | Description |
|------|-------------|
| DUPLICATE_MILK_RECORD | Record already exists for this session |
| ANIMAL_NOT_IN_HERD | Animal is not active |
| INSUFFICIENT_FEED_STOCK | Not enough feed inventory |

---

## 12. RATE LIMITING

### 12.1 Rate Limits by Module

| Module / Endpoint Type | Limit | Window |
|----------------------|-------|--------|
| Authentication | 5 requests | 15 minutes |
| Website - General | 100 requests | 15 minutes |
| Website - Cart | 50 requests | 15 minutes |
| Website - Order creation | 10 requests | 1 hour |
| Marketplace - Product listing | 30 requests | 15 minutes |
| Marketplace - Search | 60 requests | 15 minutes |
| Livestock - Bid placement | 30 requests | 15 minutes |
| ERP - Milk recording | 200 requests | 15 minutes |
| ERP - Dashboard | 60 requests | 15 minutes |
| Mobile - Sync push | 30 requests | 15 minutes |
| File upload | 20 requests | 15 minutes |

### 12.2 Rate Limit Headers

```http
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1706698800
```

---

## 13. PAGINATION & FILTERING

### 13.1 Standard Pagination

**Request Parameters:**
- `page`: Page number (default: 1, minimum: 1)
- `limit`: Items per page (default: 10, max: 100)

**Response:**
```json
{
  "pagination": {
    "currentPage": 1,
    "totalPages": 5,
    "totalItems": 47,
    "itemsPerPage": 10,
    "hasNext": true,
    "hasPrev": false
  }
}
```

### 13.2 Cursor-Based Pagination (for real-time feeds)

Used for: notifications, chat messages, audit logs.

**Request Parameters:**
- `cursor`: Opaque cursor from previous response
- `limit`: Items per page

**Response:**
```json
{
  "pagination": {
    "nextCursor": "eyJpZCI6MTIzfQ==",
    "prevCursor": null,
    "hasMore": true
  }
}
```

### 13.3 Date Range Filtering

For time-series data (milk records, financial transactions, attendance):

- `startDate`: ISO 8601 date (inclusive)
- `endDate`: ISO 8601 date (inclusive)
- `period`: Shortcut: `today`, `this_week`, `this_month`, `last_30_days`, `this_year`

---

## 14. FILE UPLOADS

### 14.1 Upload Endpoint

**POST /api/v1/common/files/upload**

**Content-Type:** `multipart/form-data`

### 14.2 File Constraints

| Category | Formats | Max Size | Max Dimensions |
|----------|---------|----------|----------------|
| Product images | JPEG, PNG, WebP | 5MB | 4000x4000px |
| Animal photos | JPEG, PNG, WebP | 10MB | 4000x4000px |
| Documents (NID, license) | JPEG, PNG, PDF | 10MB | - |
| Health certificates | PDF, JPEG, PNG | 10MB | - |
| CSV bulk upload | CSV | 25MB | - |
| Review images | JPEG, PNG, WebP | 5MB | 4000x4000px |

### 14.3 Image Processing

All uploaded images are automatically processed:
- Original stored as-is
- Thumbnail generated (200x200)
- Medium size generated (800x800)
- WebP conversion for CDN delivery
- EXIF data stripped for privacy

---

## 15. API VERSIONING

### 15.1 Strategy

- **Version in URL path:** `/api/v1/`, `/api/v2/`
- **Current version:** v1
- **Deprecation policy:** 6-month notice before removing old versions
- **Breaking changes:** New major version only

### 15.2 Backward Compatibility

Non-breaking changes within v1:
- Adding new endpoints
- Adding optional request fields
- Adding new response fields
- Adding new enum values

Breaking changes require v2:
- Removing endpoints
- Changing response structure
- Removing fields
- Changing field types

---

## APPENDIX A: API Endpoint Summary

### Total Endpoints by Module

| Module | GET | POST | PUT | PATCH | DELETE | Total |
|--------|-----|------|-----|-------|--------|-------|
| Auth | 0 | 7 | 0 | 0 | 0 | 7 |
| Common | 6 | 5 | 3 | 2 | 2 | 18 |
| Website | 22 | 12 | 4 | 1 | 4 | 43 |
| Marketplace | 14 | 10 | 2 | 1 | 1 | 28 |
| Livestock | 12 | 8 | 1 | 1 | 1 | 23 |
| ERP | 28 | 22 | 3 | 1 | 0 | 54 |
| Mobile | 2 | 4 | 1 | 0 | 0 | 7 |
| **Total** | **84** | **68** | **14** | **6** | **8** | **180** |

### WebSocket Namespaces: 4
### WebSocket Events: 12 (client + server)

---

## APPENDIX B: API Testing

### Postman Collection
Available at: `/docs/postman/SmartDairy_API_v2.postman_collection.json`

### Example cURL Commands

**Login:**
```bash
curl -X POST http://localhost:5000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@smartdairy.com","password":"SecurePass123!","deviceType":"web"}'
```

**Record Milk (ERP):**
```bash
curl -X POST http://localhost:5000/api/v1/erp/milk/records \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"animalId":"animal_001","session":"morning","quantity":9.5,"recordDate":"2026-01-31"}'
```

**Browse Marketplace:**
```bash
curl -X GET "http://localhost:5000/api/v1/marketplace/products?category=farm-equipment&sort=price_asc&limit=20" \
  -H "Accept: application/json"
```

**Place Livestock Bid:**
```bash
curl -X POST http://localhost:5000/api/v1/livestock/auctions/auction_001/bid \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"amount":190000}'
```

**Mobile Sync Push:**
```bash
curl -X POST http://localhost:5000/api/v1/mobile/sync/push \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d @offline_records.json
```

---

**Document Status:** Final
**API Status:** Design Phase
**Last Updated:** January 31, 2026
**Version:** 2.0

---

*This API specification covers the complete Smart Dairy Digital Ecosystem across all 5 modules (Website, Marketplace, Livestock, ERP, Mobile) with 180 REST endpoints and 4 WebSocket namespaces. Keep this document synchronized with implementation.*
