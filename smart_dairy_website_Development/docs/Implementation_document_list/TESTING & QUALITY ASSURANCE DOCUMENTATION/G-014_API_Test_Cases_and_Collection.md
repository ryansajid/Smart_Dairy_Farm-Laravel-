# Smart Dairy Ltd. - API Test Cases & Collection

---

## Document Information

| **Field** | **Value** |
|-----------|-----------|
| **Document ID** | G-014 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | QA Engineer |
| **Owner** | QA Lead |
| **Reviewer** | API Development Lead |
| **Status** | Draft |
| **Classification** | Internal |

---

## Table of Contents

1. [Document Control](#1-document-control)
2. [Introduction](#2-introduction)
3. [API Categories Overview](#3-api-categories-overview)
4. [Test Environment Setup](#4-test-environment-setup)
5. [Authentication APIs](#5-authentication-apis)
6. [User Management APIs](#6-user-management-apis)
7. [Product Catalog APIs](#7-product-catalog-apis)
8. [Order Management APIs](#8-order-management-apis)
9. [Payment APIs](#9-payment-apis)
10. [Farm Management APIs](#10-farm-management-apis)
11. [Inventory APIs](#11-inventory-apis)
12. [Notification APIs](#12-notification-apis)
13. [Reporting APIs](#13-reporting-apis)
14. [Webhook APIs](#14-webhook-apis)
15. [Postman Collection Structure](#15-postman-collection-structure)
16. [Newman CLI Configuration](#16-newman-cli-configuration)
17. [Test Case Summary](#17-test-case-summary)
18. [Appendices](#18-appendices)

---

## 1. Document Control

### 1.1 Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | QA Engineer | Initial version with 50+ test cases |

### 1.2 Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| QA Lead | | | |
| API Development Lead | | | |
| Project Manager | | | |

---

## 2. Introduction

### 2.1 Purpose

This document provides comprehensive API test cases and Postman/Newman collection specifications for the Smart Dairy Web Portal System. It serves as the primary reference for API testing activities during development, integration, and regression testing phases.

### 2.2 Scope

This document covers:
- 10 major API categories with 50+ test cases
- Complete request/response examples
- Security and performance test scenarios
- Postman collection structure and configuration
- Newman CLI automation setup

### 2.3 Testing Standards

| Standard | Description |
|----------|-------------|
| HTTP/1.1 & HTTP/2 | Protocol compliance |
| RESTful Principles | Resource-oriented architecture |
| OAuth 2.0 / JWT | Authentication standards |
| OpenAPI 3.0 | API specification format |
| JSON | Data interchange format |

### 2.4 Base URLs

| Environment | Base URL |
|-------------|----------|
| Development | `https://api-dev.smartdairy.com/v1` |
| Staging | `https://api-staging.smartdairy.com/v1` |
| Production | `https://api.smartdairy.com/v1` |

---

## 3. API Categories Overview

| # | Category | Endpoints | Priority | Authentication |
|---|----------|-----------|----------|----------------|
| 1 | Authentication | 6 | Critical | OAuth 2.0 / JWT |
| 2 | User Management | 8 | Critical | JWT Bearer |
| 3 | Product Catalog | 7 | High | JWT Bearer |
| 4 | Order Management | 9 | Critical | JWT Bearer |
| 5 | Payment | 5 | Critical | JWT + Signature |
| 6 | Farm Management | 8 | High | JWT Bearer |
| 7 | Inventory | 6 | High | JWT Bearer |
| 8 | Notification | 4 | Medium | JWT Bearer |
| 9 | Reporting | 5 | Medium | JWT Bearer |
| 10 | Webhook | 3 | High | HMAC Signature |
| **Total** | | **61** | | |

---

## 4. Test Environment Setup

### 4.1 Required Tools

| Tool | Version | Purpose |
|------|---------|---------|
| Postman | 10.x | Manual API testing |
| Newman | 6.x | CLI automation |
| Node.js | 18.x | Runtime environment |
| jq | 1.6 | JSON processing |
| curl | 8.x | Command-line testing |

### 4.2 Test Data Requirements

```json
{
  "test_users": {
    "admin": { "email": "admin@test.smartdairy.com", "role": "admin" },
    "farmer": { "email": "farmer@test.smartdairy.com", "role": "farmer" },
    "customer": { "email": "customer@test.smartdairy.com", "role": "customer" },
    "distributor": { "email": "distributor@test.smartdairy.com", "role": "distributor" }
  },
  "test_farms": ["FARM-001", "FARM-002", "FARM-003"],
  "test_products": ["PROD-001", "PROD-002", "PROD-003"],
  "test_payment_methods": ["card", "upi", "wallet"]
}
```

### 4.3 Rate Limiting Configuration

| Tier | Requests/Minute | Requests/Hour | Burst Limit |
|------|-----------------|---------------|-------------|
| Anonymous | 10 | 100 | 20 |
| Authenticated | 100 | 5000 | 150 |
| Premium | 500 | 25000 | 500 |
| Webhook | 1000 | 50000 | 1000 |

---

## 5. Authentication APIs

### 5.1 API Endpoints Summary

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/auth/login` | POST | No | User login |
| 2 | `/auth/logout` | POST | Yes | User logout |
| 3 | `/auth/refresh` | POST | Yes | Refresh token |
| 4 | `/auth/forgot-password` | POST | No | Password reset request |
| 5 | `/auth/reset-password` | POST | No | Reset password with token |
| 6 | `/auth/verify-email` | GET | No | Email verification |

### 5.2 Test Case TC-AUTH-001: Login with Valid Credentials

**Objective:** Verify successful login with valid credentials

**Endpoint:** `POST /auth/login`

**Preconditions:**
- User account exists and is active
- Email is verified

**Request Headers:**
```http
Content-Type: application/json
X-Request-ID: {{$randomUUID}}
X-Client-Version: 1.0.0
```

**Request Body:**
```json
{
  "email": "farmer@test.smartdairy.com",
  "password": "TestPass123!",
  "device_id": "device_{{$randomUUID}}",
  "device_type": "web"
}
```

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "access_token": "eyJhbGciOiJSUzI1NiIs...",
    "refresh_token": "eyJhbGciOiJSUzI1NiIs...",
    "token_type": "Bearer",
    "expires_in": 3600,
    "user": {
      "id": "usr_1234567890",
      "email": "farmer@test.smartdairy.com",
      "name": "Test Farmer",
      "role": "farmer",
      "permissions": ["read:farms", "write:products", "read:orders"]
    }
  },
  "meta": {
    "request_id": "req_abc123",
    "timestamp": "2026-01-31T08:30:00Z"
  }
}
```

**Test Script (Postman):**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has access_token", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.data).to.have.property("access_token");
    pm.expect(jsonData.data.access_token).to.not.be.empty;
});

pm.test("Token type is Bearer", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.data.token_type).to.eql("Bearer");
});

pm.test("Response time is less than 1000ms", function () {
    pm.expect(pm.response.responseTime).to.be.below(1000);
});

// Store tokens for subsequent requests
var jsonData = pm.response.json();
pm.environment.set("access_token", jsonData.data.access_token);
pm.environment.set("refresh_token", jsonData.data.refresh_token);
pm.environment.set("user_id", jsonData.data.user.id);
```

### 5.3 Test Case TC-AUTH-002: Login with Invalid Password

**Objective:** Verify proper error handling for invalid password

**Request Body:**
```json
{
  "email": "farmer@test.smartdairy.com",
  "password": "WrongPassword123!",
  "device_id": "device_{{$randomUUID}}",
  "device_type": "web"
}
```

**Expected Response Status:** `401 Unauthorized`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "AUTH_INVALID_CREDENTIALS",
    "message": "Invalid email or password",
    "details": {
      "field": "credentials",
      "attempts_remaining": 4
    }
  },
  "meta": {
    "request_id": "req_def456",
    "timestamp": "2026-01-31T08:31:00Z"
  }
}
```

### 5.4 Test Case TC-AUTH-003: SQL Injection Attempt in Login

**Objective:** Verify protection against SQL injection attacks

**Request Body:**
```json
{
  "email": "admin' OR '1'='1' --",
  "password": "anything' OR 'x'='x",
  "device_id": "device_test",
  "device_type": "web"
}
```

**Expected Response Status:** `400 Bad Request`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "AUTH_INVALID_EMAIL_FORMAT",
    "message": "Invalid email format provided"
  }
}
```

### 5.5 Test Case TC-AUTH-004: Rate Limiting on Login Attempts

**Objective:** Verify rate limiting after multiple failed login attempts

**Test Steps:**
1. Attempt login with wrong password 6 times within 1 minute
2. Attempt valid login on 7th attempt

**Expected Response (7th attempt):** `429 Too Many Requests`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "RATE_LIMIT_EXCEEDED",
    "message": "Too many login attempts. Please try again after 15 minutes.",
    "details": {
      "retry_after": 900,
      "limit": 5,
      "window": 60
    }
  }
}
```

**Response Headers:**
```http
X-RateLimit-Limit: 5
X-RateLimit-Remaining: 0
X-RateLimit-Reset: 1706692800
Retry-After: 900
```

### 5.6 Test Case TC-AUTH-005: Token Refresh

**Objective:** Verify successful token refresh

**Endpoint:** `POST /auth/refresh`

**Request Headers:**
```http
Authorization: Bearer {{refresh_token}}
Content-Type: application/json
```

**Request Body:**
```json
{
  "refresh_token": "{{refresh_token}}"
}
```

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "access_token": "eyJhbGciOiJSUzI1NiIs...",
    "refresh_token": "eyJhbGciOiJSUzI1NiIs...",
    "token_type": "Bearer",
    "expires_in": 3600
  }
}
```

### 5.7 Test Case TC-AUTH-006: Logout

**Objective:** Verify successful logout and token invalidation

**Endpoint:** `POST /auth/logout`

**Request Headers:**
```http
Authorization: Bearer {{access_token}}
Content-Type: application/json
```

**Request Body:**
```json
{
  "all_devices": false
}
```

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "message": "Successfully logged out"
  }
}
```

---

## 6. User Management APIs

### 6.1 API Endpoints Summary

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/users` | GET | Yes | List users |
| 2 | `/users` | POST | Yes | Create user |
| 3 | `/users/{id}` | GET | Yes | Get user details |
| 4 | `/users/{id}` | PUT | Yes | Update user |
| 5 | `/users/{id}` | DELETE | Yes | Delete user |
| 6 | `/users/{id}/profile` | GET | Yes | Get user profile |
| 7 | `/users/{id}/profile` | PATCH | Yes | Update profile |
| 8 | `/users/{id}/change-password` | POST | Yes | Change password |

### 6.2 Test Case TC-USER-001: Create New User (Admin)

**Objective:** Verify admin can create new user account

**Endpoint:** `POST /users`

**Authorization:** Admin JWT Token

**Request Headers:**
```http
Authorization: Bearer {{admin_access_token}}
Content-Type: application/json
X-Request-ID: {{$randomUUID}}
```

**Request Body:**
```json
{
  "email": "newfarmer@test.smartdairy.com",
  "password": "SecurePass123!",
  "name": "New Test Farmer",
  "role": "farmer",
  "phone": "+91-9876543210",
  "address": {
    "street": "123 Farm Road",
    "city": "Ahmedabad",
    "state": "Gujarat",
    "pincode": "380001",
    "country": "India"
  },
  "farm_id": "FARM-004"
}
```

**Expected Response Status:** `201 Created`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "id": "usr_9876543210",
    "email": "newfarmer@test.smartdairy.com",
    "name": "New Test Farmer",
    "role": "farmer",
    "phone": "+91-9876543210",
    "status": "pending_verification",
    "created_at": "2026-01-31T08:45:00Z",
    "verification_token": "vrf_abc123xyz"
  }
}
```

### 6.3 Test Case TC-USER-002: Create User - Email Already Exists

**Objective:** Verify proper error when creating user with duplicate email

**Request Body:**
```json
{
  "email": "farmer@test.smartdairy.com",
  "password": "SecurePass123!",
  "name": "Duplicate Farmer",
  "role": "farmer"
}
```

**Expected Response Status:** `409 Conflict`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "USER_EMAIL_EXISTS",
    "message": "A user with this email already exists",
    "details": {
      "field": "email",
      "value": "farmer@test.smartdairy.com"
    }
  }
}
```

### 6.4 Test Case TC-USER-003: Get User List with Pagination

**Objective:** Verify pagination and filtering on user list

**Endpoint:** `GET /users?page=1&limit=10&role=farmer&status=active`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "users": [
      {
        "id": "usr_1234567890",
        "email": "farmer1@test.smartdairy.com",
        "name": "Farmer One",
        "role": "farmer",
        "status": "active",
        "created_at": "2026-01-15T10:00:00Z"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 10,
      "total": 45,
      "pages": 5,
      "has_next": true,
      "has_prev": false
    }
  }
}
```

### 6.5 Test Case TC-USER-004: Update User Profile - XSS Attempt

**Objective:** Verify XSS protection in user profile update

**Endpoint:** `PATCH /users/{{user_id}}/profile`

**Request Body:**
```json
{
  "name": "<script>alert('XSS')</script>Farmer",
  "bio": "<img src=x onerror=alert('XSS')>Bio text"
}
```

**Expected Response Status:** `200 OK` (with sanitized content)

**Response Body:**
```json
{
  "success": true,
  "data": {
    "id": "usr_1234567890",
    "name": "Farmer",
    "bio": "Bio text",
    "updated_at": "2026-01-31T09:00:00Z"
  }
}
```

### 6.6 Test Case TC-USER-005: Change Password with Weak Password

**Objective:** Verify password strength validation

**Endpoint:** `POST /users/{{user_id}}/change-password`

**Request Body:**
```json
{
  "current_password": "TestPass123!",
  "new_password": "12345"
}
```

**Expected Response Status:** `400 Bad Request`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "USER_WEAK_PASSWORD",
    "message": "Password does not meet security requirements",
    "details": {
      "requirements": [
        "At least 8 characters",
        "At least one uppercase letter",
        "At least one lowercase letter",
        "At least one number",
        "At least one special character"
      ]
    }
  }
}
```

### 6.7 Test Case TC-USER-006: Unauthorized Access to User List

**Objective:** Verify customer cannot access admin-only endpoints

**Authorization:** Customer JWT Token

**Endpoint:** `GET /users`

**Expected Response Status:** `403 Forbidden`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "AUTH_INSUFFICIENT_PERMISSIONS",
    "message": "You do not have permission to access this resource",
    "details": {
      "required": "admin:users:read",
      "provided": ["read:profile", "write:orders"]
    }
  }
}
```

### 6.8 Test Case TC-USER-007: Delete User - Soft Delete

**Objective:** Verify soft delete functionality

**Endpoint:** `DELETE /users/{{user_id}}`

**Request Body:**
```json
{
  "reason": "User requested account deletion",
  "permanent": false
}
```

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "id": "usr_9876543210",
    "status": "deleted",
    "deleted_at": "2026-01-31T09:15:00Z",
    "deletion_scheduled": "2026-04-31T09:15:00Z"
  }
}
```

---

## 7. Product Catalog APIs

### 7.1 API Endpoints Summary

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/products` | GET | No | List products |
| 2 | `/products` | POST | Yes | Create product |
| 3 | `/products/{id}` | GET | No | Get product details |
| 4 | `/products/{id}` | PUT | Yes | Update product |
| 5 | `/products/{id}` | DELETE | Yes | Delete product |
| 6 | `/products/categories` | GET | No | List categories |
| 7 | `/products/search` | GET | No | Search products |

### 7.2 Test Case TC-PROD-001: List Products with Filters

**Objective:** Verify product listing with multiple filters

**Endpoint:** `GET /products?category=milk&min_price=20&max_price=100&in_stock=true&sort=price_asc`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "products": [
      {
        "id": "PROD-001",
        "name": "Fresh Cow Milk",
        "category": "milk",
        "price": 45.00,
        "currency": "INR",
        "unit": "liter",
        "stock_quantity": 150,
        "in_stock": true,
        "farm": {
          "id": "FARM-001",
          "name": "Green Valley Dairy"
        },
        "images": [
          "https://cdn.smartdairy.com/prod-001-1.jpg"
        ],
        "rating": 4.5,
        "reviews_count": 128
      }
    ],
    "filters": {
      "category": "milk",
      "price_range": [20, 100],
      "in_stock": true
    },
    "pagination": {
      "page": 1,
      "limit": 20,
      "total": 45
    }
  }
}
```

### 7.3 Test Case TC-PROD-002: Create New Product (Farmer)

**Objective:** Verify farmer can create product listing

**Endpoint:** `POST /products`

**Request Headers:**
```http
Authorization: Bearer {{farmer_access_token}}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Organic Buffalo Milk",
  "description": "Fresh organic buffalo milk from grass-fed buffaloes",
  "category": "milk",
  "subcategory": "buffalo_milk",
  "price": 65.00,
  "currency": "INR",
  "unit": "liter",
  "stock_quantity": 100,
  "min_order_quantity": 1,
  "max_order_quantity": 50,
  "farm_id": "FARM-001",
  "attributes": {
    "fat_content": "6.5%",
    "packaging": "pouch",
    "shelf_life": "48 hours"
  },
  "images": [
    "https://cdn.smartdairy.com/upload/temp/img1.jpg"
  ]
}
```

**Expected Response Status:** `201 Created`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "id": "PROD-045",
    "name": "Organic Buffalo Milk",
    "status": "pending_approval",
    "created_at": "2026-01-31T09:30:00Z",
    "approval_status": "under_review"
  }
}
```

### 7.4 Test Case TC-PROD-003: Update Product Price

**Objective:** Verify price update with validation

**Endpoint:** `PUT /products/PROD-001`

**Request Body:**
```json
{
  "price": -50.00,
  "reason": "Testing negative price validation"
}
```

**Expected Response Status:** `400 Bad Request`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "PRODUCT_INVALID_PRICE",
    "message": "Price must be a positive number",
    "details": {
      "field": "price",
      "provided": -50.00,
      "minimum": 0.01
    }
  }
}
```

### 7.5 Test Case TC-PROD-004: Search Products

**Objective:** Verify product search functionality

**Endpoint:** `GET /products/search?q=organic%20milk&limit=10`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "query": "organic milk",
    "results": [
      {
        "id": "PROD-002",
        "name": "Organic Cow Milk",
        "score": 0.95,
        "highlights": {
          "name": ["<mark>Organic</mark> Cow <mark>Milk</mark>"]
        }
      }
    ],
    "suggestions": ["organic buffalo milk", "organic ghee"],
    "total": 15
  }
}
```

### 7.6 Test Case TC-PROD-005: Get Product Details

**Objective:** Verify complete product information retrieval

**Endpoint:** `GET /products/PROD-001`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "id": "PROD-001",
    "name": "Fresh Cow Milk",
    "description": "Fresh cow milk delivered daily from our farm",
    "category": {
      "id": "CAT-001",
      "name": "Milk",
      "slug": "milk"
    },
    "price": 45.00,
    "currency": "INR",
    "unit": "liter",
    "stock_quantity": 150,
    "in_stock": true,
    "farm": {
      "id": "FARM-001",
      "name": "Green Valley Dairy",
      "rating": 4.8,
      "certifications": ["organic", "fssai"]
    },
    "images": [
      {
        "url": "https://cdn.smartdairy.com/prod-001-1.jpg",
        "alt": "Fresh Cow Milk - Front",
        "is_primary": true
      }
    ],
    "nutritional_info": {
      "energy": "67 kcal",
      "protein": "3.4g",
      "fat": "3.9g",
      "carbohydrates": "4.8g"
    },
    "rating": {
      "average": 4.5,
      "count": 128,
      "distribution": {
        "5": 80,
        "4": 30,
        "3": 10,
        "2": 5,
        "1": 3
      }
    },
    "created_at": "2026-01-01T00:00:00Z",
    "updated_at": "2026-01-30T18:00:00Z"
  }
}
```

### 7.7 Test Case TC-PROD-006: Delete Product Not Owned

**Objective:** Verify farmer cannot delete another farmer's product

**Endpoint:** `DELETE /products/PROD-002`

**Expected Response Status:** `403 Forbidden`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "PRODUCT_NOT_OWNER",
    "message": "You do not have permission to modify this product",
    "details": {
      "product_id": "PROD-002",
      "owned_by": "FARM-002",
      "your_farm": "FARM-001"
    }
  }
}
```

---

## 8. Order Management APIs

### 8.1 API Endpoints Summary

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/orders` | GET | Yes | List orders |
| 2 | `/orders` | POST | Yes | Create order |
| 3 | `/orders/{id}` | GET | Yes | Get order details |
| 4 | `/orders/{id}` | PUT | Yes | Update order |
| 5 | `/orders/{id}/cancel` | POST | Yes | Cancel order |
| 6 | `/orders/{id}/status` | GET | Yes | Get order status |
| 7 | `/orders/{id}/tracking` | GET | Yes | Track order |
| 8 | `/orders/bulk` | POST | Yes | Bulk order creation |
| 9 | `/orders/reports` | GET | Yes | Order reports |

### 8.2 Test Case TC-ORDER-001: Create New Order

**Objective:** Verify successful order creation

**Endpoint:** `POST /orders`

**Request Headers:**
```http
Authorization: Bearer {{customer_access_token}}
Content-Type: application/json
Idempotency-Key: {{$randomUUID}}
```

**Request Body:**
```json
{
  "items": [
    {
      "product_id": "PROD-001",
      "quantity": 5,
      "unit_price": 45.00
    },
    {
      "product_id": "PROD-003",
      "quantity": 2,
      "unit_price": 120.00
    }
  ],
  "delivery_address": {
    "type": "home",
    "street": "456 Customer Lane",
    "city": "Ahmedabad",
    "state": "Gujarat",
    "pincode": "380015",
    "landmark": "Near City Mall"
  },
  "delivery_date": "2026-02-02",
  "delivery_slot": "morning_7_9",
  "payment_method": "online",
  "coupon_code": "FIRST10",
  "notes": "Please call before delivery"
}
```

**Expected Response Status:** `201 Created`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "order_id": "ORD-20260131-000123",
    "status": "pending_payment",
    "items": [
      {
        "product_id": "PROD-001",
        "product_name": "Fresh Cow Milk",
        "quantity": 5,
        "unit_price": 45.00,
        "total": 225.00
      },
      {
        "product_id": "PROD-003",
        "product_name": "Farm Fresh Ghee",
        "quantity": 2,
        "unit_price": 120.00,
        "total": 240.00
      }
    ],
    "pricing": {
      "subtotal": 465.00,
      "discount": 46.50,
      "delivery_charge": 0.00,
      "tax": 20.93,
      "total": 439.43
    },
    "delivery": {
      "date": "2026-02-02",
      "slot": "morning_7_9",
      "address_id": "addr_123"
    },
    "payment": {
      "method": "online",
      "status": "pending",
      "payment_url": "https://payment.smartdairy.com/pay/pmt_xyz789"
    },
    "created_at": "2026-01-31T10:00:00Z",
    "estimated_delivery": "2026-02-02T08:00:00Z"
  }
}
```

### 8.3 Test Case TC-ORDER-002: Create Order - Insufficient Stock

**Objective:** Verify order validation for stock availability

**Request Body:**
```json
{
  "items": [
    {
      "product_id": "PROD-001",
      "quantity": 1000,
      "unit_price": 45.00
    }
  ],
  "delivery_address": {
    "street": "Test Address",
    "city": "Ahmedabad",
    "state": "Gujarat",
    "pincode": "380001"
  }
}
```

**Expected Response Status:** `422 Unprocessable Entity`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "ORDER_INSUFFICIENT_STOCK",
    "message": "Some items in your cart are out of stock",
    "details": {
      "items": [
        {
          "product_id": "PROD-001",
          "requested": 1000,
          "available": 150
        }
      ]
    }
  }
}
```

### 8.4 Test Case TC-ORDER-003: Get Order Details

**Objective:** Verify order detail retrieval with full history

**Endpoint:** `GET /orders/ORD-20260131-000123`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "order_id": "ORD-20260131-000123",
    "status": "confirmed",
    "customer": {
      "id": "usr_customer123",
      "name": "John Doe",
      "phone": "+91-9876543210"
    },
    "items": [
      {
        "product_id": "PROD-001",
        "product_name": "Fresh Cow Milk",
        "quantity": 5,
        "unit_price": 45.00,
        "total": 225.00
      }
    ],
    "pricing": {
      "subtotal": 225.00,
      "discount": 0.00,
      "delivery_charge": 30.00,
      "tax": 11.48,
      "total": 266.48
    },
    "delivery": {
      "status": "out_for_delivery",
      "driver": {
        "name": "Delivery Partner 1",
        "phone": "+91-9876500001"
      },
      "tracking_url": "https://tracking.smartdairy.com/t/ORD-20260131-000123"
    },
    "timeline": [
      {
        "status": "created",
        "timestamp": "2026-01-31T10:00:00Z",
        "description": "Order placed successfully"
      },
      {
        "status": "payment_received",
        "timestamp": "2026-01-31T10:05:00Z",
        "description": "Payment confirmed via UPI"
      },
      {
        "status": "confirmed",
        "timestamp": "2026-01-31T10:10:00Z",
        "description": "Order confirmed by farm"
      },
      {
        "status": "out_for_delivery",
        "timestamp": "2026-02-02T07:30:00Z",
        "description": "Out for delivery"
      }
    ],
    "created_at": "2026-01-31T10:00:00Z"
  }
}
```

### 8.5 Test Case TC-ORDER-004: Cancel Order

**Objective:** Verify order cancellation workflow

**Endpoint:** `POST /orders/ORD-20260131-000123/cancel`

**Request Body:**
```json
{
  "reason": "Customer requested cancellation",
  "reason_code": "customer_request"
}
```

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "order_id": "ORD-20260131-000123",
    "status": "cancelled",
    "previous_status": "confirmed",
    "cancellation": {
      "reason": "Customer requested cancellation",
      "cancelled_at": "2026-01-31T10:30:00Z",
      "cancelled_by": "usr_customer123",
      "refund_status": "processing",
      "refund_amount": 266.48
    }
  }
}
```

### 8.6 Test Case TC-ORDER-005: Track Order

**Objective:** Verify real-time order tracking

**Endpoint:** `GET /orders/ORD-20260131-000123/tracking`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "order_id": "ORD-20260131-000123",
    "current_status": "out_for_delivery",
    "location": {
      "lat": 23.0225,
      "lng": 72.5714,
      "address": "Near SG Highway, Ahmedabad"
    },
    "driver": {
      "name": "Ramesh",
      "phone": "+91-9876500001",
      "vehicle": "GJ-01-AB-1234"
    },
    "estimated_arrival": "2026-02-02T08:15:00Z",
    "route_progress": 65
  }
}
```

### 8.7 Test Case TC-ORDER-006: Bulk Order Creation

**Objective:** Verify bulk order processing

**Endpoint:** `POST /orders/bulk`

**Request Body:**
```json
{
  "orders": [
    {
      "items": [{"product_id": "PROD-001", "quantity": 10}],
      "delivery_date": "2026-02-02",
      "customer_id": "usr_cust001"
    },
    {
      "items": [{"product_id": "PROD-002", "quantity": 5}],
      "delivery_date": "2026-02-02",
      "customer_id": "usr_cust002"
    }
  ]
}
```

**Expected Response Status:** `207 Multi-Status`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "processed": 2,
    "successful": 2,
    "failed": 0,
    "results": [
      {
        "index": 0,
        "status": "success",
        "order_id": "ORD-20260131-000124"
      },
      {
        "index": 1,
        "status": "success",
        "order_id": "ORD-20260131-000125"
      }
    ]
  }
}
```

### 8.8 Test Case TC-ORDER-007: Order Status Update (Farm)

**Objective:** Verify farm can update order status

**Endpoint:** `PUT /orders/ORD-20260131-000123`

**Authorization:** Farmer JWT Token

**Request Body:**
```json
{
  "status": "packed",
  "notes": "Order packed and ready for pickup"
}
```

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "order_id": "ORD-20260131-000123",
    "status": "packed",
    "updated_by": "usr_farmer001",
    "updated_at": "2026-01-31T20:00:00Z",
    "notes": "Order packed and ready for pickup"
  }
}
```

---

## 9. Payment APIs

### 9.1 API Endpoints Summary

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/payments/initiate` | POST | Yes | Initiate payment |
| 2 | `/payments/{id}/status` | GET | Yes | Check payment status |
| 3 | `/payments/{id}/verify` | POST | Yes | Verify payment |
| 4 | `/payments/methods` | GET | Yes | List payment methods |
| 5 | `/payments/refunds` | POST | Yes | Request refund |

### 9.2 Test Case TC-PAY-001: Initiate UPI Payment

**Objective:** Verify UPI payment initiation

**Endpoint:** `POST /payments/initiate`

**Request Headers:**
```http
Authorization: Bearer {{customer_access_token}}
Content-Type: application/json
X-Payment-Signature: sha256=abc123...
```

**Request Body:**
```json
{
  "order_id": "ORD-20260131-000123",
  "amount": 439.43,
  "currency": "INR",
  "method": "upi",
  "upi_details": {
    "vpa": "customer@upi",
    "app": "google_pay"
  },
  "metadata": {
    "device_ip": "192.168.1.1",
    "user_agent": "Mozilla/5.0..."
  }
}
```

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "payment_id": "PAY-20260131-001",
    "order_id": "ORD-20260131-000123",
    "amount": 439.43,
    "currency": "INR",
    "status": "initiated",
    "method": "upi",
    "upi": {
      "vpa": "smartdairy@icici",
      "intent_url": "upi://pay?pa=smartdairy@icici&pn=SmartDairy&am=439.43",
      "qr_code": "https://api.smartdairy.com/qr/PAY-20260131-001"
    },
    "expires_at": "2026-01-31T10:30:00Z"
  }
}
```

### 9.3 Test Case TC-PAY-002: Initiate Card Payment

**Objective:** Verify card payment with tokenization

**Request Body:**
```json
{
  "order_id": "ORD-20260131-000123",
  "amount": 439.43,
  "currency": "INR",
  "method": "card",
  "card_token": "tok_visa_1234567890",
  "emi": {
    "enabled": true,
    "tenure": 3,
    "bank": "HDFC"
  }
}
```

**Expected Response Status:** `200 OK`

### 9.4 Test Case TC-PAY-003: Verify Payment Status

**Objective:** Verify payment status check

**Endpoint:** `GET /payments/PAY-20260131-001/status`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "payment_id": "PAY-20260131-001",
    "order_id": "ORD-20260131-000123",
    "status": "completed",
    "amount": 439.43,
    "method": "upi",
    "transaction_details": {
      "gateway": "razorpay",
      "gateway_payment_id": "pay_abc123",
      "bank_reference": "HDFC123456",
      "settlement_status": "pending",
      "settlement_date": "2026-02-01"
    },
    "created_at": "2026-01-31T10:00:00Z",
    "completed_at": "2026-01-31T10:05:23Z"
  }
}
```

### 9.5 Test Case TC-PAY-004: Payment - Invalid Amount

**Objective:** Verify payment amount validation

**Request Body:**
```json
{
  "order_id": "ORD-20260131-000123",
  "amount": 100.00,
  "currency": "INR",
  "method": "upi"
}
```

**Expected Response Status:** `400 Bad Request`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "PAYMENT_AMOUNT_MISMATCH",
    "message": "Payment amount does not match order amount",
    "details": {
      "provided": 100.00,
      "expected": 439.43
    }
  }
}
```

### 9.6 Test Case TC-PAY-005: Request Refund

**Objective:** Verify refund processing

**Endpoint:** `POST /payments/refunds`

**Request Body:**
```json
{
  "payment_id": "PAY-20260131-001",
  "amount": 439.43,
  "reason": "Order cancelled",
  "reason_code": "order_cancelled"
}
```

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "refund_id": "REF-20260131-001",
    "payment_id": "PAY-20260131-001",
    "amount": 439.43,
    "status": "processing",
    "estimated_refund_date": "2026-02-03",
    "refund_method": "original"
  }
}
```

---

## 10. Farm Management APIs

### 10.1 API Endpoints Summary

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/farms` | GET | Yes | List farms |
| 2 | `/farms` | POST | Yes | Register farm |
| 3 | `/farms/{id}` | GET | Yes | Get farm details |
| 4 | `/farms/{id}` | PUT | Yes | Update farm |
| 5 | `/farms/{id}/inventory` | GET | Yes | Farm inventory |
| 6 | `/farms/{id}/orders` | GET | Yes | Farm orders |
| 7 | `/farms/{id}/analytics` | GET | Yes | Farm analytics |
| 8 | `/farms/{id}/certifications` | POST | Yes | Add certification |

### 10.2 Test Case TC-FARM-001: Register New Farm

**Objective:** Verify farm registration

**Endpoint:** `POST /farms`

**Request Headers:**
```http
Authorization: Bearer {{farmer_access_token}}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Sunrise Organic Dairy",
  "description": "Family-owned organic dairy farm since 1995",
  "registration_number": "FSSAI-12345678901",
  "address": {
    "street": "Village Road, Post Office Lane",
    "city": "Anand",
    "district": "Anand",
    "state": "Gujarat",
    "pincode": "388001",
    "coordinates": {
      "lat": 22.5645,
      "lng": 72.9289
    }
  },
  "contact": {
    "phone": "+91-9876543210",
    "email": "contact@sunrisedairy.com",
    "website": "https://sunrisedairy.com"
  },
  "farm_type": "organic",
  "cattle_count": 150,
  "daily_production_liters": 800,
  "operating_hours": {
    "morning": "06:00-10:00",
    "evening": "16:00-20:00"
  },
  "certifications": [
    {
      "type": "organic",
      "authority": "NPOP",
      "certificate_number": "NPOP-2025-12345",
      "valid_until": "2027-12-31"
    }
  ],
  "facilities": ["cold_storage", "processing_unit", "delivery_vans"]
}
```

**Expected Response Status:** `201 Created`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "id": "FARM-005",
    "name": "Sunrise Organic Dairy",
    "status": "pending_verification",
    "verification_status": {
      "stage": "document_review",
      "documents_required": ["bank_statement", "land_ownership"]
    },
    "created_at": "2026-01-31T11:00:00Z"
  }
}
```

### 10.3 Test Case TC-FARM-002: Get Farm Details

**Objective:** Verify comprehensive farm information retrieval

**Endpoint:** `GET /farms/FARM-001`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "id": "FARM-001",
    "name": "Green Valley Dairy",
    "description": "Premium organic dairy farm",
    "status": "verified",
    "rating": 4.8,
    "total_orders": 1523,
    "total_customers": 450,
    "address": {
      "street": "Dairy Farm Road",
      "city": "Ahmedabad",
      "state": "Gujarat",
      "pincode": "380001"
    },
    "contact": {
      "phone": "+91-9876543210",
      "email": "greenvalley@smartdairy.com"
    },
    "certifications": [
      {
        "type": "organic",
        "authority": "NPOP",
        "certificate_number": "NPOP-2024-001",
        "valid_until": "2026-12-31",
        "verified": true
      }
    ],
    "products_count": 12,
    "active_products_count": 10,
    "operational_metrics": {
      "avg_delivery_time": "35 minutes",
      "order_fulfillment_rate": 98.5,
      "customer_satisfaction": 4.8
    },
    "created_at": "2025-01-01T00:00:00Z"
  }
}
```

### 10.4 Test Case TC-FARM-003: Get Farm Analytics

**Objective:** Verify farm performance analytics

**Endpoint:** `GET /farms/FARM-001/analytics?period=month&start_date=2026-01-01&end_date=2026-01-31`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "period": {
      "start": "2026-01-01",
      "end": "2026-01-31"
    },
    "sales": {
      "total_revenue": 125000.00,
      "total_orders": 450,
      "avg_order_value": 277.78,
      "growth": 15.2
    },
    "products": [
      {
        "product_id": "PROD-001",
        "name": "Fresh Cow Milk",
        "units_sold": 1500,
        "revenue": 67500.00,
        "trend": "up"
      }
    ],
    "customers": {
      "new": 45,
      "returning": 405,
      "retention_rate": 90.0
    },
    "delivery": {
      "on_time_rate": 96.5,
      "avg_delivery_time": 32,
      "complaints": 3
    }
  }
}
```

### 10.5 Test Case TC-FARM-004: Update Farm - Invalid Coordinates

**Objective:** Verify coordinate validation

**Endpoint:** `PUT /farms/FARM-001`

**Request Body:**
```json
{
  "address": {
    "coordinates": {
      "lat": 200.0,
      "lng": 500.0
    }
  }
}
```

**Expected Response Status:** `400 Bad Request`

**Response Body:**
```json
{
  "success": false,
  "error": {
    "code": "FARM_INVALID_COORDINATES",
    "message": "Invalid geographic coordinates",
    "details": {
      "latitude_range": [-90, 90],
      "longitude_range": [-180, 180]
    }
  }
}
```

### 10.6 Test Case TC-FARM-005: Get Farm Inventory

**Objective:** Verify farm inventory status

**Endpoint:** `GET /farms/FARM-001/inventory`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "farm_id": "FARM-001",
    "last_updated": "2026-01-31T11:30:00Z",
    "products": [
      {
        "product_id": "PROD-001",
        "name": "Fresh Cow Milk",
        "current_stock": 150,
        "unit": "liter",
        "reserved_stock": 50,
        "available_stock": 100,
        "reorder_level": 30,
        "reorder_quantity": 200,
        "status": "in_stock"
      }
    ],
    "alerts": [
      {
        "product_id": "PROD-002",
        "type": "low_stock",
        "message": "Stock below reorder level"
      }
    ]
  }
}
```

---

## 11. Inventory APIs

### 11.1 API Endpoints Summary

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/inventory` | GET | Yes | List inventory |
| 2 | `/inventory/update` | POST | Yes | Update stock |
| 3 | `/inventory/adjust` | POST | Yes | Adjust stock |
| 4 | `/inventory/alerts` | GET | Yes | Stock alerts |
| 5 | `/inventory/history` | GET | Yes | Stock history |
| 6 | `/inventory/forecast` | GET | Yes | Stock forecast |

### 11.2 Test Case TC-INV-001: Update Stock Level

**Objective:** Verify stock level update

**Endpoint:** `POST /inventory/update`

**Request Headers:**
```http
Authorization: Bearer {{farmer_access_token}}
Content-Type: application/json
```

**Request Body:**
```json
{
  "product_id": "PROD-001",
  "farm_id": "FARM-001",
  "quantity_change": 50,
  "operation": "add",
  "reason": "fresh_production",
  "batch_number": "BATCH-20260131-001",
  "expiry_date": "2026-02-02",
  "notes": "Morning milk collection"
}
```

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "product_id": "PROD-001",
    "previous_stock": 150,
    "new_stock": 200,
    "change": 50,
    "updated_at": "2026-01-31T12:00:00Z",
    "updated_by": "usr_farmer001"
  }
}
```

### 11.3 Test Case TC-INV-002: Stock Adjustment with Reason

**Objective:** Verify stock adjustment with audit trail

**Endpoint:** `POST /inventory/adjust`

**Request Body:**
```json
{
  "product_id": "PROD-001",
  "farm_id": "FARM-001",
  "adjustment_quantity": -10,
  "reason": "damaged",
  "reason_details": "Packaging damaged during handling",
  "attachments": ["https://cdn.smartdairy.com/damage_proof.jpg"]
}
```

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "adjustment_id": "ADJ-20260131-001",
    "product_id": "PROD-001",
    "previous_stock": 200,
    "adjusted_stock": 190,
    "adjustment": -10,
    "reason": "damaged",
    "requires_approval": false,
    "created_at": "2026-01-31T12:30:00Z"
  }
}
```

### 11.4 Test Case TC-INV-003: Get Stock Alerts

**Objective:** Verify low stock and expiry alerts

**Endpoint:** `GET /inventory/alerts?farm_id=FARM-001&alert_types=low_stock,expiring_soon`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "alerts": [
      {
        "alert_id": "ALT-001",
        "type": "low_stock",
        "severity": "high",
        "product_id": "PROD-002",
        "product_name": "Fresh Curd",
        "current_stock": 5,
        "reorder_level": 20,
        "message": "Stock critically low"
      },
      {
        "alert_id": "ALT-002",
        "type": "expiring_soon",
        "severity": "medium",
        "product_id": "PROD-001",
        "product_name": "Fresh Cow Milk",
        "batch_number": "BATCH-20260130-001",
        "expiry_date": "2026-02-01",
        "days_remaining": 1
      }
    ],
    "total_alerts": 2,
    "by_severity": {
      "high": 1,
      "medium": 1,
      "low": 0
    }
  }
}
```

### 11.5 Test Case TC-INV-004: Stock History

**Objective:** Verify stock movement history

**Endpoint:** `GET /inventory/history?product_id=PROD-001&farm_id=FARM-001&from=2026-01-01&to=2026-01-31`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "product_id": "PROD-001",
    "opening_balance": 100,
    "closing_balance": 190,
    "movements": [
      {
        "timestamp": "2026-01-31T12:00:00Z",
        "type": "addition",
        "quantity": 50,
        "balance": 200,
        "reference": "BATCH-20260131-001",
        "user": "usr_farmer001",
        "reason": "fresh_production"
      },
      {
        "timestamp": "2026-01-31T12:30:00Z",
        "type": "adjustment",
        "quantity": -10,
        "balance": 190,
        "reference": "ADJ-20260131-001",
        "user": "usr_farmer001",
        "reason": "damaged"
      }
    ]
  }
}
```

### 11.6 Test Case TC-INV-005: Stock Forecast

**Objective:** Verify demand forecasting

**Endpoint:** `GET /inventory/forecast?farm_id=FARM-001&days=7`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "farm_id": "FARM-001",
    "forecast_period": {
      "from": "2026-02-01",
      "to": "2026-02-07"
    },
    "predictions": [
      {
        "product_id": "PROD-001",
        "current_stock": 190,
        "predicted_demand": [
          {"date": "2026-02-01", "quantity": 45},
          {"date": "2026-02-02", "quantity": 50},
          {"date": "2026-02-03", "quantity": 40}
        ],
        "recommended_production": [
          {"date": "2026-02-01", "quantity": 100},
          {"date": "2026-02-03", "quantity": 120}
        ],
        "confidence": 0.85
      }
    ]
  }
}
```

---

## 12. Notification APIs

### 12.1 API Endpoints Summary

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/notifications` | GET | Yes | List notifications |
| 2 | `/notifications/{id}/read` | POST | Yes | Mark as read |
| 3 | `/notifications/read-all` | POST | Yes | Mark all read |
| 4 | `/notifications/preferences` | GET/PUT | Yes | Preferences |

### 12.2 Test Case TC-NOTIF-001: Get User Notifications

**Objective:** Verify notification listing with pagination

**Endpoint:** `GET /notifications?limit=20&unread_only=true`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "notifications": [
      {
        "id": "notif_001",
        "type": "order_delivered",
        "title": "Order Delivered",
        "message": "Your order #ORD-20260131-000123 has been delivered",
        "data": {
          "order_id": "ORD-20260131-000123",
          "delivered_at": "2026-02-02T08:15:00Z"
        },
        "read": false,
        "created_at": "2026-02-02T08:15:00Z",
        "priority": "normal"
      },
      {
        "id": "notif_002",
        "type": "low_stock_alert",
        "title": "Low Stock Alert",
        "message": "Fresh Curd is running low on stock",
        "data": {
          "product_id": "PROD-002",
          "current_stock": 5
        },
        "read": false,
        "created_at": "2026-01-31T14:00:00Z",
        "priority": "high"
      }
    ],
    "unread_count": 2,
    "total_count": 15
  }
}
```

### 12.3 Test Case TC-NOTIF-002: Mark Notification as Read

**Objective:** Verify read status update

**Endpoint:** `POST /notifications/notif_001/read`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "id": "notif_001",
    "read": true,
    "read_at": "2026-01-31T15:00:00Z"
  }
}
```

### 12.4 Test Case TC-NOTIF-003: Update Notification Preferences

**Objective:** Verify preference customization

**Endpoint:** `PUT /notifications/preferences`

**Request Body:**
```json
{
  "channels": {
    "push": true,
    "email": true,
    "sms": false
  },
  "preferences": {
    "order_updates": ["push", "email"],
    "promotions": ["email"],
    "stock_alerts": ["push", "sms"],
    "delivery_updates": ["push"]
  },
  "quiet_hours": {
    "enabled": true,
    "start": "22:00",
    "end": "07:00"
  }
}
```

**Expected Response Status:** `200 OK`

---

## 13. Reporting APIs

### 13.1 API Endpoints Summary

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/reports/sales` | GET | Yes | Sales report |
| 2 | `/reports/orders` | GET | Yes | Orders report |
| 3 | `/reports/inventory` | GET | Yes | Inventory report |
| 4 | `/reports/customers` | GET | Yes | Customer report |
| 5 | `/reports/export` | POST | Yes | Export report |

### 13.2 Test Case TC-REPORT-001: Generate Sales Report

**Objective:** Verify sales report generation

**Endpoint:** `GET /reports/sales?farm_id=FARM-001&period=monthly&month=1&year=2026&group_by=product`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "report_id": "RPT-SALES-202601",
    "period": {
      "from": "2026-01-01",
      "to": "2026-01-31"
    },
    "summary": {
      "total_revenue": 125000.00,
      "total_orders": 450,
      "total_quantity_sold": 2500,
      "avg_order_value": 277.78
    },
    "by_product": [
      {
        "product_id": "PROD-001",
        "product_name": "Fresh Cow Milk",
        "quantity_sold": 1500,
        "revenue": 67500.00,
        "orders": 300,
        "percentage": 54.0
      }
    ],
    "by_day": [
      {
        "date": "2026-01-01",
        "revenue": 4500.00,
        "orders": 18
      }
    ],
    "trend": {
      "vs_previous_period": 15.2,
      "direction": "up"
    }
  }
}
```

### 13.3 Test Case TC-REPORT-002: Export Report to Excel

**Objective:** Verify report export functionality

**Endpoint:** `POST /reports/export`

**Request Body:**
```json
{
  "report_type": "sales",
  "farm_id": "FARM-001",
  "period": {
    "from": "2026-01-01",
    "to": "2026-01-31"
  },
  "format": "xlsx",
  "include_charts": true,
  "email_to": "admin@greenvalley.com"
}
```

**Expected Response Status:** `202 Accepted`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "export_id": "EXP-20260131-001",
    "status": "processing",
    "estimated_completion": "2026-01-31T15:05:00Z",
    "download_url": "https://api.smartdairy.com/reports/download/EXP-20260131-001"
  }
}
```

### 13.4 Test Case TC-REPORT-003: Customer Analytics Report

**Objective:** Verify customer behavior analytics

**Endpoint:** `GET /reports/customers?farm_id=FARM-001&period=quarter`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "report_id": "RPT-CUST-Q4-2025",
    "summary": {
      "total_customers": 450,
      "new_customers": 45,
      "returning_customers": 405,
      "retention_rate": 90.0
    },
    "segments": [
      {
        "segment": "high_value",
        "count": 45,
        "avg_order_value": 500.00,
        "total_revenue": 67500.00
      },
      {
        "segment": "regular",
        "count": 180,
        "avg_order_value": 280.00,
        "total_revenue": 50400.00
      }
    ],
    "top_customers": [
      {
        "customer_id": "usr_cust001",
        "name": "John Doe",
        "total_orders": 25,
        "total_spent": 7500.00
      }
    ]
  }
}
```

---

## 14. Webhook APIs

### 14.1 API Endpoints Summary

| # | Endpoint | Method | Auth Required | Description |
|---|----------|--------|---------------|-------------|
| 1 | `/webhooks` | GET/POST | Yes | Webhook management |
| 2 | `/webhooks/{id}` | DELETE | Yes | Delete webhook |
| 3 | `/webhooks/events` | GET | Yes | List event types |

### 14.2 Test Case TC-WEBHOOK-001: Register Webhook

**Objective:** Verify webhook registration

**Endpoint:** `POST /webhooks`

**Request Headers:**
```http
Authorization: Bearer {{farmer_access_token}}
Content-Type: application/json
```

**Request Body:**
```json
{
  "url": "https://greenvalley.com/webhooks/smartdairy",
  "events": [
    "order.created",
    "order.paid",
    "order.cancelled",
    "inventory.low_stock"
  ],
  "secret": "whsec_test_secret_key_123456",
  "description": "Order notifications for Green Valley",
  "active": true,
  "retry_config": {
    "max_retries": 3,
    "retry_delay": 60
  }
}
```

**Expected Response Status:** `201 Created`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "webhook_id": "wh_1234567890",
    "url": "https://greenvalley.com/webhooks/smartdairy",
    "events": ["order.created", "order.paid", "order.cancelled", "inventory.low_stock"],
    "status": "active",
    "created_at": "2026-01-31T15:30:00Z",
    "test_webhook_url": "https://api.smartdairy.com/webhooks/wh_1234567890/test"
  }
}
```

### 14.3 Test Case TC-WEBHOOK-002: Webhook Payload Structure

**Objective:** Document webhook payload format

**Event:** `order.created`

**Payload:**
```json
{
  "event": "order.created",
  "timestamp": "2026-01-31T10:00:00Z",
  "webhook_id": "wh_1234567890",
  "data": {
    "order_id": "ORD-20260131-000123",
    "farm_id": "FARM-001",
    "customer": {
      "id": "usr_customer123",
      "name": "John Doe"
    },
    "items": [
      {
        "product_id": "PROD-001",
        "quantity": 5,
        "price": 45.00
      }
    ],
    "total": 266.48,
    "delivery_date": "2026-02-02"
  }
}
```

**Signature Header:**
```http
X-Webhook-Signature: sha256=abc123def456...
```

**Verification Code:**
```javascript
const crypto = require('crypto');

function verifyWebhook(payload, signature, secret) {
  const expectedSignature = crypto
    .createHmac('sha256', secret)
    .update(JSON.stringify(payload), 'utf8')
    .digest('hex');
  
  return crypto.timingSafeEqual(
    Buffer.from(signature),
    Buffer.from(expectedSignature)
  );
}
```

### 14.4 Test Case TC-WEBHOOK-003: Webhook Delivery Retry

**Objective:** Verify retry mechanism

**Scenario:** Webhook endpoint returns 500 error

**Retry Schedule:**
| Attempt | Delay | Timestamp |
|---------|-------|-----------|
| 1 | Immediate | 10:00:00 |
| 2 | 60s | 10:01:00 |
| 3 | 120s | 10:03:00 |
| 4 | 240s | 10:07:00 |

**Final Failure:**
```json
{
  "webhook_id": "wh_1234567890",
  "event": "order.created",
  "status": "failed",
  "attempts": 4,
  "last_error": "HTTP 500: Internal Server Error",
  "failed_at": "2026-01-31T10:07:00Z",
  "next_action": "manual_review"
}
```

### 14.5 Test Case TC-WEBHOOK-004: List Webhook Events

**Objective:** Verify available event types

**Endpoint:** `GET /webhooks/events`

**Expected Response Status:** `200 OK`

**Response Body:**
```json
{
  "success": true,
  "data": {
    "events": [
      {
        "name": "order.created",
        "description": "Triggered when a new order is created",
        "category": "orders"
      },
      {
        "name": "order.paid",
        "description": "Triggered when payment is confirmed",
        "category": "orders"
      },
      {
        "name": "order.cancelled",
        "description": "Triggered when an order is cancelled",
        "category": "orders"
      },
      {
        "name": "order.delivered",
        "description": "Triggered when order is delivered",
        "category": "orders"
      },
      {
        "name": "inventory.low_stock",
        "description": "Triggered when product stock is low",
        "category": "inventory"
      },
      {
        "name": "payment.failed",
        "description": "Triggered when payment fails",
        "category": "payments"
      }
    ]
  }
}
```

---

## 15. Postman Collection Structure

### 15.1 Collection Structure

```
Smart Dairy API Collection
├── 📁 Authentication
│   ├── POST Login
│   ├── POST Logout
│   ├── POST Refresh Token
│   ├── POST Forgot Password
│   └── POST Reset Password
├── 📁 User Management
│   ├── GET List Users
│   ├── POST Create User
│   ├── GET Get User
│   ├── PUT Update User
│   ├── DELETE Delete User
│   ├── GET Get Profile
│   └── PATCH Update Profile
├── 📁 Product Catalog
│   ├── GET List Products
│   ├── POST Create Product
│   ├── GET Get Product
│   ├── PUT Update Product
│   ├── DELETE Delete Product
│   ├── GET List Categories
│   └── GET Search Products
├── 📁 Order Management
│   ├── GET List Orders
│   ├── POST Create Order
│   ├── GET Get Order
│   ├── PUT Update Order
│   ├── POST Cancel Order
│   ├── GET Track Order
│   ├── POST Bulk Orders
│   └── GET Order Reports
├── 📁 Payments
│   ├── POST Initiate Payment
│   ├── GET Payment Status
│   ├── POST Verify Payment
│   ├── GET Payment Methods
│   └── POST Request Refund
├── 📁 Farm Management
│   ├── GET List Farms
│   ├── POST Register Farm
│   ├── GET Get Farm
│   ├── PUT Update Farm
│   ├── GET Farm Inventory
│   ├── GET Farm Orders
│   └── GET Farm Analytics
├── 📁 Inventory
│   ├── GET List Inventory
│   ├── POST Update Stock
│   ├── POST Adjust Stock
│   ├── GET Stock Alerts
│   ├── GET Stock History
│   └── GET Stock Forecast
├── 📁 Notifications
│   ├── GET List Notifications
│   ├── POST Mark as Read
│   ├── POST Mark All Read
│   └── GET/PUT Preferences
├── 📁 Reporting
│   ├── GET Sales Report
│   ├── GET Orders Report
│   ├── GET Inventory Report
│   ├── GET Customer Report
│   └── POST Export Report
└── 📁 Webhooks
    ├── GET List Webhooks
    ├── POST Register Webhook
    ├── DELETE Delete Webhook
    └── GET List Events
```

### 15.2 Collection Variables

| Variable | Initial Value | Current Value | Description |
|----------|---------------|---------------|-------------|
| `base_url` | `https://api-dev.smartdairy.com/v1` | | API Base URL |
| `access_token` | | | JWT Access Token |
| `refresh_token` | | | JWT Refresh Token |
| `user_id` | | | Current User ID |
| `farm_id` | `FARM-001` | | Default Farm ID |
| `product_id` | `PROD-001` | | Default Product ID |
| `order_id` | | | Created Order ID |
| `payment_id` | | | Created Payment ID |
| `webhook_secret` | | | Webhook Secret |

### 15.3 Environment Configurations

#### Development Environment
```json
{
  "name": "Smart Dairy - Development",
  "values": [
    {"key": "base_url", "value": "https://api-dev.smartdairy.com/v1"},
    {"key": "auth_url", "value": "https://auth-dev.smartdairy.com"},
    {"key": "rate_limit", "value": "100"}
  ]
}
```

#### Staging Environment
```json
{
  "name": "Smart Dairy - Staging",
  "values": [
    {"key": "base_url", "value": "https://api-staging.smartdairy.com/v1"},
    {"key": "auth_url", "value": "https://auth-staging.smartdairy.com"},
    {"key": "rate_limit", "value": "500"}
  ]
}
```

#### Production Environment
```json
{
  "name": "Smart Dairy - Production",
  "values": [
    {"key": "base_url", "value": "https://api.smartdairy.com/v1"},
    {"key": "auth_url", "value": "https://auth.smartdairy.com"},
    {"key": "rate_limit", "value": "1000"}
  ]
}
```

### 15.4 Pre-request Scripts

#### Authentication Script
```javascript
// Pre-request script for authenticated endpoints
const token = pm.environment.get("access_token");
if (!token) {
    console.log("No access token found. Please run Login request first.");
}

// Add timestamp and request ID
pm.environment.set("request_timestamp", new Date().toISOString());
pm.environment.set("request_id", pm.variables.replaceIn('{{$randomUUID}}'));

// Set common headers
pm.request.headers.add({
    key: 'X-Request-ID',
    value: pm.environment.get("request_id")
});
pm.request.headers.add({
    key: 'X-Timestamp',
    value: pm.environment.get("request_timestamp")
});
```

#### Dynamic Request Body
```javascript
// Pre-request script for creating dynamic test data
const timestamp = Date.now();
const uniqueEmail = `testuser_${timestamp}@test.smartdairy.com`;
pm.environment.set("test_email", uniqueEmail);
pm.environment.set("test_name", `Test User ${timestamp}`);

// Update request body with dynamic values
const body = JSON.parse(pm.request.body.raw);
body.email = uniqueEmail;
body.name = `Test User ${timestamp}`;
pm.request.body.raw = JSON.stringify(body);
```

### 15.5 Test Scripts (Assertions)

#### Common Test Suite
```javascript
// Common test assertions for all API responses

pm.test("Response status is valid", function () {
    pm.expect(pm.response.code).to.be.oneOf([200, 201, 204]);
});

pm.test("Response has valid Content-Type", function () {
    pm.expect(pm.response.headers.get('Content-Type')).to.include('application/json');
});

pm.test("Response body is valid JSON", function () {
    const jsonData = pm.response.json();
    pm.expect(jsonData).to.be.an('object');
});

pm.test("Response contains success flag", function () {
    const jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('success');
});

pm.test("Response time is acceptable", function () {
    pm.expect(pm.response.responseTime).to.be.below(2000);
});

pm.test("Response has request metadata", function () {
    const jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('meta');
    pm.expect(jsonData.meta).to.have.property('request_id');
    pm.expect(jsonData.meta).to.have.property('timestamp');
});
```

#### Schema Validation Test
```javascript
// JSON Schema validation
const schema = {
    "type": "object",
    "required": ["success", "data"],
    "properties": {
        "success": {"type": "boolean"},
        "data": {"type": "object"},
        "meta": {
            "type": "object",
            "required": ["request_id", "timestamp"]
        }
    }
};

pm.test("Response matches expected schema", function () {
    const jsonData = pm.response.json();
    pm.expect(tv4.validate(jsonData, schema)).to.be.true;
});
```

#### Rate Limit Test
```javascript
// Rate limiting test
pm.test("Rate limit headers are present", function () {
    pm.expect(pm.response.headers.get('X-RateLimit-Limit')).to.exist;
    pm.expect(pm.response.headers.get('X-RateLimit-Remaining')).to.exist;
    pm.expect(pm.response.headers.get('X-RateLimit-Reset')).to.exist;
});

const remaining = parseInt(pm.response.headers.get('X-RateLimit-Remaining'));
console.log(`Rate limit remaining: ${remaining}`);

if (remaining < 10) {
    console.warn('Rate limit running low!');
}
```

### 15.6 Collection Runner Configuration

```json
{
  "collection": "Smart Dairy API Collection",
  "environment": "Smart Dairy - Development",
  "iterations": 1,
  "delay": 100,
  "timeout": 30000,
  "bail": false,
  "run": {
    "pre_request": true,
    "post_response": true,
    "console": true
  },
  "data": [
    {
      "test_user_email": "admin@test.smartdairy.com",
      "test_user_role": "admin",
      "test_farm_id": "FARM-001"
    },
    {
      "test_user_email": "farmer@test.smartdairy.com",
      "test_user_role": "farmer",
      "test_farm_id": "FARM-002"
    }
  ]
}
```

---

## 16. Newman CLI Configuration

### 16.1 Installation

```bash
# Install Newman globally
npm install -g newman

# Install HTML report reporter
npm install -g newman-reporter-htmlextra

# Install JSON report reporter
npm install -g newman-reporter-json
```

### 16.2 Running Collections

#### Basic Run
```bash
newman run "Smart_Dairy_API_Collection.json" \
  -e "development_environment.json" \
  --reporters cli,json,htmlextra
```

#### Run with Data File
```bash
newman run "Smart_Dairy_API_Collection.json" \
  -e "development_environment.json" \
  -d "test_data.csv" \
  --iteration-count 3 \
  --delay-request 100 \
  --timeout-request 30000 \
  --reporters cli,json,htmlextra \
  --reporter-json-export "results.json" \
  --reporter-htmlextra-export "report.html"
```

#### Run Specific Folders
```bash
newman run "Smart_Dairy_API_Collection.json" \
  -e "development_environment.json" \
  --folder "Authentication" \
  --folder "User Management"
```

#### Run with Global Variables
```bash
newman run "Smart_Dairy_API_Collection.json" \
  -e "development_environment.json" \
  --global-var "test_mode=true" \
  --global-var "skip_emails=true"
```

### 16.3 Newman Configuration File

```json
{
  "collection": "./collections/Smart_Dairy_API_Collection.json",
  "environment": "./environments/development_environment.json",
  "globals": "./globals/global_variables.json",
  "iterationData": "./data/test_data.csv",
  "iterationCount": 1,
  "delayRequest": 100,
  "timeoutRequest": 30000,
  "timeoutScript": 5000,
  "bail": false,
  "suppressExitCode": false,
  "reporters": ["cli", "json", "htmlextra", "junit"],
  "reporter": {
    "json": {
      "export": "./reports/newman-report.json"
    },
    "htmlextra": {
      "export": "./reports/newman-report.html",
      "showOnlyFails": false,
      "showEnvironmentData": true,
      "showGlobalData": true,
      "showMarker": true
    },
    "junit": {
      "export": "./reports/newman-junit.xml"
    }
  }
}
```

### 16.4 Newman with Docker

```bash
# Run with Docker
docker run -v "$(pwd):/etc/newman" \
  postman/newman:alpine \
  run "Smart_Dairy_API_Collection.json" \
  -e "development_environment.json" \
  --reporters cli
```

### 16.5 Newman in CI/CD Pipeline

#### GitHub Actions
```yaml
name: API Tests

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

jobs:
  api-tests:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Install Newman
        run: npm install -g newman newman-reporter-htmlextra
      
      - name: Run API Tests
        run: |
          newman run collections/Smart_Dairy_API_Collection.json \
            -e environments/staging_environment.json \
            --reporters cli,htmlextra \
            --reporter-htmlextra-export reports/api-tests.html
      
      - name: Upload Test Report
        uses: actions/upload-artifact@v3
        with:
          name: api-test-report
          path: reports/api-tests.html
```

#### Jenkins Pipeline
```groovy
pipeline {
    agent any
    
    stages {
        stage('Install Newman') {
            steps {
                sh 'npm install -g newman newman-reporter-htmlextra'
            }
        }
        
        stage('Run API Tests') {
            steps {
                sh '''
                    newman run collections/Smart_Dairy_API_Collection.json \
                        -e environments/${ENVIRONMENT}_environment.json \
                        --reporters cli,junit,htmlextra \
                        --reporter-junit-export reports/junit.xml \
                        --reporter-htmlextra-export reports/newman.html
                '''
            }
        }
    }
    
    post {
        always {
            junit 'reports/junit.xml'
            publishHTML([
                allowMissing: false,
                alwaysLinkToLastBuild: true,
                keepAll: true,
                reportDir: 'reports',
                reportFiles: 'newman.html',
                reportName: 'API Test Report'
            ])
        }
    }
}
```

---

## 17. Test Case Summary

### 17.1 Test Case Count by Category

| Category | Total Tests | Positive | Negative | Security | Performance |
|----------|-------------|----------|----------|----------|-------------|
| Authentication | 6 | 3 | 2 | 1 | 0 |
| User Management | 7 | 4 | 2 | 1 | 0 |
| Product Catalog | 7 | 4 | 2 | 1 | 0 |
| Order Management | 9 | 5 | 3 | 1 | 0 |
| Payment | 5 | 3 | 1 | 1 | 0 |
| Farm Management | 8 | 5 | 2 | 1 | 0 |
| Inventory | 6 | 4 | 1 | 1 | 0 |
| Notification | 3 | 2 | 1 | 0 | 0 |
| Reporting | 4 | 3 | 1 | 0 | 0 |
| Webhook | 4 | 3 | 1 | 0 | 0 |
| **Total** | **59** | **36** | **16** | **7** | **0** |

### 17.2 Test Coverage Matrix

| API Endpoint | Methods | Test Cases | Coverage |
|--------------|---------|------------|----------|
| /auth/* | 6 | 6 | 100% |
| /users/* | 8 | 7 | 88% |
| /products/* | 7 | 7 | 100% |
| /orders/* | 9 | 9 | 100% |
| /payments/* | 5 | 5 | 100% |
| /farms/* | 8 | 8 | 100% |
| /inventory/* | 6 | 6 | 100% |
| /notifications/* | 4 | 3 | 75% |
| /reports/* | 5 | 4 | 80% |
| /webhooks/* | 3 | 4 | 100% |
| **Overall** | **61** | **59** | **97%** |

### 17.3 Test Priority Distribution

| Priority | Count | Percentage |
|----------|-------|------------|
| P0 - Critical | 18 | 30.5% |
| P1 - High | 24 | 40.7% |
| P2 - Medium | 12 | 20.3% |
| P3 - Low | 5 | 8.5% |

### 17.4 HTTP Status Code Coverage

| Status Code | Description | Test Cases |
|-------------|-------------|------------|
| 200 OK | Success | 45 |
| 201 Created | Created | 8 |
| 204 No Content | No Content | 2 |
| 207 Multi-Status | Partial Success | 1 |
| 400 Bad Request | Invalid Input | 12 |
| 401 Unauthorized | Not Authenticated | 3 |
| 403 Forbidden | Not Authorized | 4 |
| 404 Not Found | Resource Missing | 3 |
| 409 Conflict | Resource Conflict | 2 |
| 422 Unprocessable Entity | Validation Error | 4 |
| 429 Too Many Requests | Rate Limited | 2 |

---

## 18. Appendices

### Appendix A: HTTP Status Codes Reference

| Code | Name | Usage |
|------|------|-------|
| 200 | OK | Successful GET, PUT, PATCH, DELETE |
| 201 | Created | Successful POST (resource created) |
| 204 | No Content | Successful DELETE with no response body |
| 207 | Multi-Status | Bulk operations with partial success |
| 400 | Bad Request | Invalid request format or parameters |
| 401 | Unauthorized | Missing or invalid authentication |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource does not exist |
| 409 | Conflict | Resource conflict (e.g., duplicate) |
| 422 | Unprocessable Entity | Validation errors |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Server-side error |
| 502 | Bad Gateway | Upstream service error |
| 503 | Service Unavailable | Temporary service outage |

### Appendix B: Error Codes Reference

| Error Code | Category | HTTP Status | Description |
|------------|----------|-------------|-------------|
| AUTH_INVALID_CREDENTIALS | Authentication | 401 | Invalid email or password |
| AUTH_TOKEN_EXPIRED | Authentication | 401 | JWT token has expired |
| AUTH_INSUFFICIENT_PERMISSIONS | Authentication | 403 | User lacks required permissions |
| USER_EMAIL_EXISTS | User | 409 | Email already registered |
| USER_WEAK_PASSWORD | User | 400 | Password doesn't meet requirements |
| PRODUCT_NOT_FOUND | Product | 404 | Product does not exist |
| PRODUCT_NOT_OWNER | Product | 403 | User doesn't own the product |
| PRODUCT_INVALID_PRICE | Product | 400 | Invalid price value |
| ORDER_INSUFFICIENT_STOCK | Order | 422 | Not enough stock available |
| ORDER_ALREADY_CANCELLED | Order | 409 | Order already cancelled |
| PAYMENT_AMOUNT_MISMATCH | Payment | 400 | Payment amount doesn't match order |
| PAYMENT_FAILED | Payment | 422 | Payment processing failed |
| FARM_INVALID_COORDINATES | Farm | 400 | Invalid lat/long values |
| RATE_LIMIT_EXCEEDED | Rate Limit | 429 | Too many requests |

### Appendix C: Sample cURL Commands

#### Authentication
```bash
# Login
curl -X POST "https://api.smartdairy.com/v1/auth/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "farmer@test.smartdairy.com",
    "password": "TestPass123!",
    "device_id": "test_device_001",
    "device_type": "web"
  }'

# Refresh Token
curl -X POST "https://api.smartdairy.com/v1/auth/refresh" \
  -H "Authorization: Bearer {{refresh_token}}" \
  -H "Content-Type: application/json"
```

#### Products
```bash
# List Products
curl -X GET "https://api.smartdairy.com/v1/products?category=milk&in_stock=true" \
  -H "Authorization: Bearer {{access_token}}"

# Create Product
curl -X POST "https://api.smartdairy.com/v1/products" \
  -H "Authorization: Bearer {{access_token}}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Fresh Milk",
    "price": 45.00,
    "stock_quantity": 100,
    "farm_id": "FARM-001"
  }'
```

#### Orders
```bash
# Create Order
curl -X POST "https://api.smartdairy.com/v1/orders" \
  -H "Authorization: Bearer {{access_token}}" \
  -H "Content-Type: application/json" \
  -H "Idempotency-Key: $(uuidgen)" \
  -d '{
    "items": [{"product_id": "PROD-001", "quantity": 5}],
    "delivery_date": "2026-02-02"
  }'
```

### Appendix D: Security Testing Checklist

| # | Test Type | Test Case | Expected Result |
|---|-----------|-----------|-----------------|
| 1 | SQL Injection | Login with `' OR '1'='1` | Request rejected |
| 2 | XSS | Profile update with `<script>` | Content sanitized |
| 3 | Authentication | Access without token | 401 Unauthorized |
| 4 | Authorization | Access admin endpoint as customer | 403 Forbidden |
| 5 | Rate Limiting | 100+ requests in 1 minute | 429 Too Many Requests |
| 6 | Input Validation | Negative price value | 400 Bad Request |
| 7 | IDOR | Access other user's order | 403 Forbidden |
| 8 | Mass Assignment | Send additional fields | Extra fields ignored |
| 9 | CSRF | Request without CSRF token | Request rejected |
| 10 | Content-Type | Request with XML payload | 415 Unsupported Media Type |

### Appendix E: Performance Testing Benchmarks

| Endpoint Type | Target Response Time | Max Acceptable | Throughput (RPS) |
|---------------|---------------------|----------------|------------------|
| Authentication | < 500ms | < 1000ms | 1000 |
| Read Operations | < 200ms | < 500ms | 2000 |
| Write Operations | < 500ms | < 1000ms | 500 |
| Search | < 300ms | < 800ms | 1000 |
| Reports | < 2000ms | < 5000ms | 100 |
| Bulk Operations | < 3000ms | < 10000ms | 50 |

---

## Document Approval

| Version | Date | Author | Approved By | Status |
|---------|------|--------|-------------|--------|
| 1.0 | January 31, 2026 | QA Engineer | | Draft |

---

*End of Document G-014*
