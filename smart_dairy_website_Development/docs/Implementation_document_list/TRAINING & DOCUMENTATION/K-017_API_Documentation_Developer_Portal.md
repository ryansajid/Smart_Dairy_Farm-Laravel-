# Smart Dairy Ltd. - API Documentation (Developer Portal)

---

| **Field** | **Details** |
|-----------|-------------|
| **Document ID** | K-017 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | API Architect |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Classification** | Public - External Developer Portal |
| **Target Audience** | External Developers, Integration Partners, System Integrators |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Authentication](#2-authentication)
3. [API Endpoints by Category](#3-api-endpoints-by-category)
4. [Request/Response Format](#4-requestresponse-format)
5. [SDK Documentation](#5-sdk-documentation)
6. [Code Examples](#6-code-examples)
7. [Webhooks](#7-webhooks)
8. [Testing](#8-testing)
9. [Changelog](#9-changelog)
10. [Best Practices](#10-best-practices)
11. [Support](#11-support)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Developer Portal Overview

Welcome to the Smart Dairy Ltd. Developer Portal. This comprehensive API documentation enables external developers and integration partners to seamlessly connect with Smart Dairy's Smart Web Portal System and integrated ERP platform.

Smart Dairy's API-first architecture provides secure, scalable, and well-documented endpoints for:
- **B2C E-commerce Integration** - Build custom storefronts and mobile apps
- **B2B Marketplace Connectivity** - Integrate wholesale ordering systems
- **Farm Management Data Access** - Access herd, production, and inventory data
- **Supply Chain Integration** - Connect logistics and distribution partners
- **Third-Party Services** - Payment gateways, shipping providers, and more

### 1.2 Base URLs

| Environment | Base URL | Description |
|-------------|----------|-------------|
| Production | `https://api.smartdairybd.com/v1` | Live production environment |
| Sandbox | `https://sandbox-api.smartdairybd.com/v1` | Testing environment |
| EU Region | `https://eu-api.smartdairybd.com/v1` | European data residency |
| Asia Region | `https://asia-api.smartdairybd.com/v1` | Asian data residency |

### 1.3 Getting Started

#### Quick Start Guide

**Step 1: Register for Developer Account**
```
Visit: https://developers.smartdairybd.com/register
Required: Company details, use case description, expected API volume
Approval: 1-2 business days
```

**Step 2: Create Application**
```bash
# After registration, create your first application
curl -X POST https://developers.smartdairybd.com/apps \
  -H "Authorization: Bearer {developer_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "My Integration App",
    "description": "B2B order integration",
    "environment": "sandbox"
  }'
```

**Step 3: Obtain API Credentials**
- API Key: For server-to-server authentication
- OAuth Client ID/Secret: For OAuth 2.0 flows
- Webhook Secret: For webhook verification

**Step 4: Make Your First API Call**
```bash
curl -X GET https://sandbox-api.smartdairybd.com/v1/products \
  -H "X-API-Key: your_api_key_here" \
  -H "Accept: application/json"
```

### 1.4 API Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        SMART DAIRY API ARCHITECTURE                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│   ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐       │
│   │   B2C App   │  │   B2B App   │  │  Partner    │  │  Mobile App │       │
│   └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘       │
│          │                │                │                │               │
│          └────────────────┴────────────────┴────────────────┘               │
│                                     │                                        │
│   ┌─────────────────────────────────────────────────────────────────────┐   │
│   │                      API GATEWAY (Kong/AWS)                          │   │
│   │  • Rate Limiting  • Authentication  • SSL/TLS  • Load Balancing     │   │
│   └─────────────────────────────────────────────────────────────────────┘   │
│                                     │                                        │
│   ┌─────────────────────────────────────────────────────────────────────┐   │
│   │                    SMART DAIRY API LAYER                             │   │
│   ├─────────────────┬─────────────────┬─────────────────┬───────────────┤   │
│   │  Public APIs    │  Partner APIs   │  Webhook APIs   │  Admin APIs   │   │
│   │  • Products     │  • Orders       │  • Events       │  • Users      │   │
│   │  • Inventory    │  • Customers    │  • Subscriptions│  • Reports    │   │
│   │  • Farm Data    │  • Billing      │  • Notifications│  • Config     │   │
│   └─────────────────┴─────────────────┴─────────────────┴───────────────┘   │
│                                     │                                        │
│   ┌─────────────────────────────────────────────────────────────────────┐   │
│   │                  BACKEND SERVICES (Microservices)                    │   │
│   │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐       │   │
│   │  │  Auth   │ │Product  │ │  Order  │ │  Farm   │ │ Notify  │       │   │
│   │  │ Service │ │Service  │ │ Service │ │ Service │ │ Service │       │   │
│   │  └─────────┘ └─────────┘ └─────────┘ └─────────┘ └─────────┘       │   │
│   └─────────────────────────────────────────────────────────────────────┘   │
│                                     │                                        │
│   ┌─────────────────────────────────────────────────────────────────────┐   │
│   │                      DATA LAYER                                      │   │
│   │  • PostgreSQL (Primary)  • Redis (Cache)  • S3 (Storage)            │   │
│   │  • MongoDB (Logs)        • Elasticsearch (Search)                   │   │
│   └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 2. Authentication

### 2.1 Authentication Overview

Smart Dairy API supports multiple authentication methods:

| Method | Use Case | Security Level |
|--------|----------|----------------|
| **API Key** | Server-to-server, internal services | High |
| **OAuth 2.0** | Third-party applications, user authorization | Very High |
| **JWT Tokens** | Session-based API access | High |
| **Webhook Signature** | Webhook payload verification | High |

### 2.2 API Key Setup

API Keys are the simplest authentication method for server-to-server integrations.

#### Generating API Keys

```bash
# Create a new API key
curl -X POST https://developers.smartdairybd.com/api-keys \
  -H "Authorization: Bearer {developer_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Production API Key",
    "environment": "production",
    "scopes": ["products:read", "orders:write", "inventory:read"],
    "rate_limit": 10000,
    "allowed_ips": ["203.0.113.0/24"]
  }'
```

#### Using API Keys

```bash
# Header-based authentication (recommended)
curl -X GET https://api.smartdairybd.com/v1/products \
  -H "X-API-Key: sd_live_abc123xyz789"

# Query parameter (for simple GET requests only)
curl -X GET "https://api.smartdairybd.com/v1/products?api_key=sd_live_abc123xyz789"
```

#### API Key Format

| Prefix | Environment | Example |
|--------|-------------|---------|
| `sd_live_` | Production | `sd_live_a1b2c3d4e5f6` |
| `sd_test_` | Sandbox | `sd_test_x9y8z7w6v5u4` |
| `sd_dev_` | Development | `sd_dev_p3q4r5s6t7u8` |

#### API Key Best Practices

```python
# Example: Secure API key storage and usage
import os
from functools import wraps

class SmartDairyAPI:
    def __init__(self):
        # Load from environment variable
        self.api_key = os.environ.get('SMART_DAIRY_API_KEY')
        self.base_url = os.environ.get('SMART_DAIRY_BASE_URL')
        
        if not self.api_key:
            raise ValueError("SMART_DAIRY_API_KEY environment variable not set")
    
    def _get_headers(self):
        return {
            'X-API-Key': self.api_key,
            'Content-Type': 'application/json',
            'User-Agent': 'MyApp/1.0'
        }
    
    def get_products(self):
        import requests
        response = requests.get(
            f"{self.base_url}/products",
            headers=self._get_headers()
        )
        response.raise_for_status()
        return response.json()
```

### 2.3 OAuth 2.0 Flow

For third-party applications requiring user authorization, Smart Dairy supports OAuth 2.0 with PKCE extension.

#### Supported OAuth 2.0 Flows

| Flow | Use Case | Description |
|------|----------|-------------|
| **Authorization Code** | Web applications | Server-side apps with confidential clients |
| **Authorization Code + PKCE** | Mobile/SPA | Public clients, enhanced security |
| **Client Credentials** | Machine-to-machine | Service accounts, background jobs |
| **Refresh Token** | Long-lived sessions | Obtain new access tokens |

#### OAuth 2.0 Authorization Code Flow

```
┌─────────┐                                    ┌─────────────┐
│  User   │                                    │   Client    │
│ (Browser)│                                   │ Application │
└────┬────┘                                    └──────┬──────┘
     │                                               │
     │  1. Initiate Authorization                    │
     │  GET /oauth/authorize?                        │
     │    response_type=code&                        │
     │    client_id=CLIENT_ID&                       │
     │    redirect_uri=REDIRECT_URI&                 │
     │    scope=products:read orders:write&          │
     │    state=STATE                                │
     │◄──────────────────────────────────────────────┤
     │                                               │
     │  2. User Login & Consent                      │
     │  (Login page displayed)                       │
     │  User grants permission                       │
     │──────────────────────────────────────────────►│
     │                                               │
     │  3. Authorization Response                    │
     │  Redirect to: redirect_uri?                   │
     │    code=AUTH_CODE&                            │
     │    state=STATE                                │
     │◄──────────────────────────────────────────────┤
     │                                               │
┌────┴────┐                                    ┌──────┴──────┐
│  User   │                                    │   Client    │
│ (Browser)│                                   │  Backend    │
└────┬────┘                                    └──────┬──────┘
     │                                               │
     │              4. Exchange Code for Token       │
     │              POST /oauth/token                │
     │              grant_type=authorization_code    │
     │              code=AUTH_CODE                   │
     │              client_id=CLIENT_ID              │
     │              client_secret=CLIENT_SECRET      │
     │              redirect_uri=REDIRECT_URI        │
     │◄──────────────────────────────────────────────┤
     │                                               │
     │              5. Token Response                │
     │              {                                │
     │                "access_token": "eyJ...",      │
     │                "token_type": "Bearer",        │
     │                "expires_in": 3600,            │
     │                "refresh_token": "rt_...",     │
     │                "scope": "products:read"       │
     │              }                                │
     │──────────────────────────────────────────────►│
```

#### OAuth 2.0 Implementation Example

```javascript
// Node.js OAuth 2.0 client implementation
const { AuthorizationCode } = require('simple-oauth2');

const config = {
  client: {
    id: process.env.SMART_DAIRY_CLIENT_ID,
    secret: process.env.SMART_DAIRY_CLIENT_SECRET
  },
  auth: {
    tokenHost: 'https://api.smartdairybd.com',
    authorizePath: '/oauth/authorize',
    tokenPath: '/oauth/token'
  }
};

const client = new AuthorizationCode(config);

// Step 1: Generate authorization URL
function getAuthorizationUrl() {
  return client.authorizeURL({
    redirect_uri: 'https://yourapp.com/callback',
    scope: 'products:read orders:write customers:read',
    state: generateRandomState()
  });
}

// Step 2: Exchange code for token
async function getTokenFromCode(code) {
  const tokenParams = {
    code,
    redirect_uri: 'https://yourapp.com/callback'
  };
  
  try {
    const accessToken = await client.getToken(tokenParams);
    return accessToken.token;
  } catch (error) {
    console.error('Access Token Error:', error.message);
    throw error;
  }
}

// Step 3: Use access token
async function makeAuthenticatedRequest(accessToken, endpoint) {
  const response = await fetch(`https://api.smartdairybd.com/v1${endpoint}`, {
    headers: {
      'Authorization': `Bearer ${accessToken.access_token}`,
      'Content-Type': 'application/json'
    }
  });
  return response.json();
}
```

#### OAuth 2.0 Scopes

| Scope | Description | Access Level |
|-------|-------------|--------------|
| `public:read` | Read public product/catalog data | Public |
| `products:read` | Read product details | Authenticated |
| `products:write` | Create/update products | Partner |
| `orders:read` | Read order information | Authenticated |
| `orders:write` | Create/modify orders | Authenticated |
| `customers:read` | Read customer data | Authenticated |
| `customers:write` | Modify customer data | Authenticated |
| `inventory:read` | Read inventory levels | Partner |
| `inventory:write` | Update inventory | Partner |
| `farm:read` | Read farm data | Internal/Partner |
| `webhooks:manage` | Manage webhook subscriptions | Partner |
| `admin` | Full administrative access | Admin |

### 2.4 JWT Tokens

JSON Web Tokens (JWT) are used for session-based authentication and internal service communication.

#### JWT Token Structure

```
Header (Base64Url encoded):
{
  "alg": "RS256",
  "typ": "JWT",
  "kid": "key-id-123"
}

Payload (Base64Url encoded):
{
  "iss": "https://api.smartdairybd.com",
  "sub": "user_12345",
  "aud": "smart-dairy-api",
  "exp": 1738368000,
  "iat": 1738364400,
  "scope": "products:read orders:write",
  "org_id": "org_67890",
  "tier": "enterprise"
}

Signature: RSASHA256(
  base64UrlEncode(header) + "." +
  base64UrlEncode(payload),
  private_key
)
```

#### JWT Token Validation

```python
import jwt
from cryptography.hazmat.primitives import serialization
import requests

class JWTValidator:
    def __init__(self):
        self.jwks_url = "https://api.smartdairybd.com/.well-known/jwks.json"
        self.issuer = "https://api.smartdairybd.com"
        self.audience = "smart-dairy-api"
        self._jwks = None
    
    def _get_jwks(self):
        if not self._jwks:
            response = requests.get(self.jwks_url)
            self._jwks = response.json()
        return self._jwks
    
    def validate_token(self, token: str) -> dict:
        """
        Validate and decode JWT token
        Returns decoded payload if valid
        Raises exception if invalid
        """
        try:
            # Get signing key from JWKS
            jwks = self._get_jwks()
            
            # Find the key that matches the token's kid
            unverified_header = jwt.get_unverified_header(token)
            key = self._get_signing_key(jwks, unverified_header['kid'])
            
            # Validate and decode
            payload = jwt.decode(
                token,
                key=key,
                algorithms=['RS256'],
                audience=self.audience,
                issuer=self.issuer
            )
            
            return payload
            
        except jwt.ExpiredSignatureError:
            raise ValueError("Token has expired")
        except jwt.InvalidTokenError as e:
            raise ValueError(f"Invalid token: {str(e)}")
    
    def _get_signing_key(self, jwks, kid):
        for key_data in jwks['keys']:
            if key_data['kid'] == kid:
                # Convert JWK to PEM
                from jwt.algorithms import RSAAlgorithm
                return RSAAlgorithm.from_jwk(key_data)
        raise ValueError(f"Key {kid} not found in JWKS")
```

### 2.5 Rate Limiting

To ensure fair usage and system stability, all API requests are subject to rate limiting.

#### Rate Limit Tiers

| Tier | Requests/Minute | Requests/Hour | Requests/Day | Burst |
|------|-----------------|---------------|--------------|-------|
| **Free** | 60 | 1,000 | 10,000 | 10 |
| **Starter** | 300 | 10,000 | 100,000 | 50 |
| **Professional** | 1,000 | 50,000 | 500,000 | 200 |
| **Enterprise** | 5,000 | 250,000 | 2,500,000 | 1,000 |
| **Custom** | Negotiable | Negotiable | Negotiable | Custom |

#### Rate Limit Headers

Every API response includes rate limit information in the headers:

```http
HTTP/1.1 200 OK
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1738368000
X-RateLimit-Window: 60
Retry-After: 30

{
  "data": [...]
}
```

| Header | Description |
|--------|-------------|
| `X-RateLimit-Limit` | Maximum requests allowed in the current window |
| `X-RateLimit-Remaining` | Requests remaining in current window |
| `X-RateLimit-Reset` | Unix timestamp when the limit resets |
| `X-RateLimit-Window` | Window size in seconds |
| `Retry-After` | Seconds to wait before retry (only on 429) |

#### Rate Limit Response

When you exceed the rate limit, the API returns HTTP 429:

```http
HTTP/1.1 429 Too Many Requests
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 0
X-RateLimit-Reset: 1738368000
Retry-After: 30
Content-Type: application/json

{
  "error": {
    "code": "RATE_LIMIT_EXCEEDED",
    "message": "API rate limit exceeded. Please wait 30 seconds before retrying.",
    "retry_after": 30,
    "limit": 1000,
    "window": 60,
    "documentation_url": "https://developers.smartdairybd.com/docs/rate-limits"
  }
}
```

#### Handling Rate Limits

```python
import time
import requests
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry

class RateLimitedClient:
    def __init__(self, api_key, max_retries=3):
        self.api_key = api_key
        self.base_url = "https://api.smartdairybd.com/v1"
        self.session = requests.Session()
        
        # Configure retry strategy
        retry_strategy = Retry(
            total=max_retries,
            backoff_factor=1,
            status_forcelist=[429, 500, 502, 503, 504],
            allowed_methods=["HEAD", "GET", "OPTIONS", "POST", "PUT", "DELETE"]
        )
        
        adapter = HTTPAdapter(max_retries=retry_strategy)
        self.session.mount("https://", adapter)
    
    def request(self, method, endpoint, **kwargs):
        headers = kwargs.pop('headers', {})
        headers['X-API-Key'] = self.api_key
        
        response = self.session.request(
            method,
            f"{self.base_url}{endpoint}",
            headers=headers,
            **kwargs
        )
        
        # Check rate limit headers
        remaining = int(response.headers.get('X-RateLimit-Remaining', 0))
        if remaining < 10:
            reset_time = int(response.headers.get('X-RateLimit-Reset', 0))
            self._handle_low_rate_limit(reset_time)
        
        response.raise_for_status()
        return response
    
    def _handle_low_rate_limit(self, reset_time):
        """Pause briefly when approaching rate limit"""
        wait_time = max(0, reset_time - int(time.time()))
        if wait_time > 0:
            print(f"Rate limit low. Waiting {wait_time} seconds...")
            time.sleep(min(wait_time, 5))  # Cap wait at 5 seconds
```

---

## 3. API Endpoints by Category

### 3.1 Authentication API

Base Path: `/auth`

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/auth/token` | Obtain access token | Client Credentials |
| POST | `/auth/refresh` | Refresh access token | Refresh Token |
| POST | `/auth/revoke` | Revoke token | Access Token |
| GET | `/auth/me` | Get current user info | Access Token |
| POST | `/auth/logout` | Logout user | Access Token |

#### POST /auth/token

**Request:**
```bash
curl -X POST https://api.smartdairybd.com/v1/auth/token \
  -H "Content-Type: application/json" \
  -d '{
    "grant_type": "client_credentials",
    "client_id": "your_client_id",
    "client_secret": "your_client_secret",
    "scope": "products:read orders:write"
  }'
```

**Response:**
```json
{
  "access_token": "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9...",
  "token_type": "Bearer",
  "expires_in": 3600,
  "scope": "products:read orders:write",
  "created_at": "2026-01-31T10:00:00Z"
}
```

#### GET /auth/me

**Request:**
```bash
curl -X GET https://api.smartdairybd.com/v1/auth/me \
  -H "Authorization: Bearer {access_token}"
```

**Response:**
```json
{
  "user": {
    "id": "usr_123456789",
    "email": "developer@partner.com",
    "name": "Partner Developer",
    "organization": {
      "id": "org_987654321",
      "name": "Partner Company Ltd.",
      "tier": "professional",
      "status": "active"
    },
    "scopes": ["products:read", "orders:write", "customers:read"],
    "created_at": "2026-01-15T08:30:00Z",
    "last_login": "2026-01-31T09:45:00Z"
  }
}
```

### 3.2 Products API

Base Path: `/products`

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/products` | List all products | API Key |
| GET | `/products/{id}` | Get product by ID | API Key |
| GET | `/products/{id}/inventory` | Get product inventory | API Key |
| GET | `/products/categories` | List categories | Public |
| GET | `/products/search` | Search products | API Key |
| POST | `/products` | Create product | Partner |
| PUT | `/products/{id}` | Update product | Partner |
| DELETE | `/products/{id}` | Delete product | Partner |

#### GET /products

**Request Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `category` | string | No | Filter by category ID |
| `status` | string | No | Filter by status: `active`, `inactive`, `draft` |
| `min_price` | number | No | Minimum price filter |
| `max_price` | number | No | Maximum price filter |
| `in_stock` | boolean | No | Filter by stock availability |
| `organic` | boolean | No | Filter organic products |
| `page` | integer | No | Page number (default: 1) |
| `limit` | integer | No | Items per page (default: 20, max: 100) |
| `sort` | string | No | Sort field (default: `-created_at`) |

**Request:**
```bash
curl -X GET "https://api.smartdairybd.com/v1/products?category=dairy&in_stock=true&page=1&limit=10" \
  -H "X-API-Key: {api_key}"
```

**Response:**
```json
{
  "data": [
    {
      "id": "prod_safron_milk_1l",
      "sku": "SAF-MILK-1L",
      "name": {
        "en": "Saffron Organic Milk",
        "bn": "স্যাফরন জৈব দুধ"
      },
      "description": "100% fresh, pure organic whole milk - antibiotic-free, preservative-free",
      "category": {
        "id": "cat_dairy",
        "name": "Fresh Milk"
      },
      "pricing": {
        "currency": "BDT",
        "unit_price": 120.00,
        "unit": "1L",
        "bulk_pricing": [
          {
            "min_quantity": 10,
            "unit_price": 110.00,
            "discount_percentage": 8.33
          },
          {
            "min_quantity": 50,
            "unit_price": 100.00,
            "discount_percentage": 16.67
          }
        ]
      },
      "inventory": {
        "available_quantity": 500,
        "reserved_quantity": 50,
        "reorder_level": 100,
        "warehouse_id": "wh_dhaka_main"
      },
      "attributes": {
        "organic": true,
        "antibiotic_free": true,
        "preservative_free": true,
        "fat_content": "3.5%",
        "shelf_life_days": 7,
        "storage_temp_celsius": 4
      },
      "images": [
        {
          "url": "https://cdn.smartdairybd.com/products/saffron-milk-1l.jpg",
          "alt": "Saffron Organic Milk 1L",
          "is_primary": true
        }
      ],
      "certifications": [
        {
          "name": "Dairy Farmers of Bangladesh Quality Milk",
          "logo_url": "https://cdn.smartdairybd.com/certifications/dfb-logo.png",
          "valid_until": "2026-12-31"
        }
      ],
      "status": "active",
      "created_at": "2025-06-15T10:00:00Z",
      "updated_at": "2026-01-25T14:30:00Z"
    }
  ],
  "pagination": {
    "total": 24,
    "page": 1,
    "limit": 10,
    "total_pages": 3,
    "has_next": true,
    "has_prev": false
  },
  "meta": {
    "request_id": "req_abc123xyz",
    "timestamp": "2026-01-31T10:00:00Z"
  }
}
```

#### POST /products

**Request:**
```bash
curl -X POST https://api.smartdairybd.com/v1/products \
  -H "X-API-Key: {api_key}" \
  -H "Content-Type: application/json" \
  -d '{
    "sku": "SAF-YOGURT-500G",
    "name": {
      "en": "Saffron Sweet Yogurt",
      "bn": "স্যাফরন মিষ্টি দই"
    },
    "description": "Pure yogurt crafted from 100% organic milk",
    "category_id": "cat_fermented",
    "pricing": {
      "currency": "BDT",
      "unit_price": 85.00,
      "unit": "500g"
    },
    "attributes": {
      "organic": true,
      "fat_content": "4.0%",
      "shelf_life_days": 14,
      "storage_temp_celsius": 4
    },
    "status": "active"
  }'
```

**Response:**
```json
{
  "data": {
    "id": "prod_safron_yogurt_500g",
    "sku": "SAF-YOGURT-500G",
    "name": {
      "en": "Saffron Sweet Yogurt",
      "bn": "স্যাফরন মিষ্টি দই"
    },
    "status": "active",
    "created_at": "2026-01-31T10:05:00Z",
    "updated_at": "2026-01-31T10:05:00Z"
  },
  "meta": {
    "request_id": "req_def456uvw",
    "timestamp": "2026-01-31T10:05:00Z"
  }
}
```

### 3.3 Orders API

Base Path: `/orders`

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/orders` | List orders | API Key |
| GET | `/orders/{id}` | Get order by ID | API Key |
| POST | `/orders` | Create new order | API Key |
| PUT | `/orders/{id}` | Update order | API Key |
| DELETE | `/orders/{id}` | Cancel order | API Key |
| GET | `/orders/{id}/status` | Get order status | API Key |
| POST | `/orders/{id}/cancel` | Cancel order | API Key |
| GET | `/orders/{id}/tracking` | Get tracking info | API Key |
| GET | `/orders/{id}/invoice` | Get invoice | API Key |

#### POST /orders

**Request:**
```bash
curl -X POST https://api.smartdairybd.com/v1/orders \
  -H "X-API-Key: {api_key}" \
  -H "Content-Type: application/json" \
  -d '{
    "customer": {
      "id": "cust_12345",
      "email": "customer@example.com",
      "phone": "+8801712345678"
    },
    "order_type": "b2c",
    "items": [
      {
        "product_id": "prod_safron_milk_1l",
        "sku": "SAF-MILK-1L",
        "quantity": 5,
        "unit_price": 120.00,
        "subscription": {
          "is_subscription": true,
          "frequency": "daily",
          "start_date": "2026-02-01",
          "end_date": "2026-02-28"
        }
      },
      {
        "product_id": "prod_safron_yogurt_500g",
        "sku": "SAF-YOGURT-500G",
        "quantity": 2,
        "unit_price": 85.00
      }
    ],
    "shipping_address": {
      "name": "John Doe",
      "address_line_1": "123 Gulshan Avenue",
      "address_line_2": "Apartment 4B",
      "city": "Dhaka",
      "postal_code": "1212",
      "country": "Bangladesh",
      "phone": "+8801712345678",
      "delivery_instructions": "Leave with security guard"
    },
    "billing_address": {
      "name": "John Doe",
      "address_line_1": "123 Gulshan Avenue",
      "city": "Dhaka",
      "postal_code": "1212",
      "country": "Bangladesh"
    },
    "payment": {
      "method": "bkash",
      "currency": "BDT"
    },
    "delivery": {
      "preferred_date": "2026-02-01",
      "preferred_time_slot": "morning",
      "is_express": false
    },
    "metadata": {
      "source": "api",
      "referrer": "mobile_app",
      "notes": "Please handle with care"
    }
  }'
```

**Response:**
```json
{
  "data": {
    "id": "ord_sd_20260131_001",
    "order_number": "SD-20260131-001",
    "status": "pending_payment",
    "customer": {
      "id": "cust_12345",
      "email": "customer@example.com"
    },
    "items": [
      {
        "id": "item_001",
        "product_id": "prod_safron_milk_1l",
        "product_name": "Saffron Organic Milk",
        "quantity": 5,
        "unit_price": 120.00,
        "total": 600.00,
        "subscription_id": "sub_001"
      },
      {
        "id": "item_002",
        "product_id": "prod_safron_yogurt_500g",
        "product_name": "Saffron Sweet Yogurt",
        "quantity": 2,
        "unit_price": 85.00,
        "total": 170.00
      }
    ],
    "pricing": {
      "subtotal": 770.00,
      "discount": 0.00,
      "delivery_fee": 50.00,
      "tax": 0.00,
      "total": 820.00,
      "currency": "BDT"
    },
    "shipping_address": {
      "name": "John Doe",
      "address_line_1": "123 Gulshan Avenue",
      "city": "Dhaka",
      "postal_code": "1212",
      "country": "Bangladesh"
    },
    "payment": {
      "method": "bkash",
      "status": "pending",
      "payment_url": "https://payment.smartdairybd.com/pay/ord_sd_20260131_001"
    },
    "delivery": {
      "preferred_date": "2026-02-01",
      "preferred_time_slot": "morning",
      "estimated_delivery": "2026-02-01T08:00:00Z"
    },
    "timestamps": {
      "created_at": "2026-01-31T10:15:00Z",
      "updated_at": "2026-01-31T10:15:00Z",
      "expires_at": "2026-01-31T10:45:00Z"
    },
    "webhook_events": [
      "order.created",
      "order.paid",
      "order.shipped",
      "order.delivered"
    ]
  },
  "meta": {
    "request_id": "req_ghi789rst",
    "timestamp": "2026-01-31T10:15:00Z"
  }
}
```

#### GET /orders/{id}

**Response:**
```json
{
  "data": {
    "id": "ord_sd_20260131_001",
    "order_number": "SD-20260131-001",
    "status": "shipped",
    "status_history": [
      {
        "status": "created",
        "timestamp": "2026-01-31T10:15:00Z",
        "note": "Order created via API"
      },
      {
        "status": "paid",
        "timestamp": "2026-01-31T10:18:00Z",
        "note": "Payment confirmed via bKash"
      },
      {
        "status": "processing",
        "timestamp": "2026-01-31T10:20:00Z"
      },
      {
        "status": "shipped",
        "timestamp": "2026-01-31T14:30:00Z",
        "tracking_number": "SD-DEL-001234",
        "carrier": "Smart Dairy Logistics"
      }
    ],
    "items": [...],
    "pricing": {...},
    "tracking": {
      "carrier": "Smart Dairy Logistics",
      "tracking_number": "SD-DEL-001234",
      "tracking_url": "https://track.smartdairybd.com/SD-DEL-001234",
      "current_status": "out_for_delivery",
      "estimated_delivery": "2026-02-01T08:00:00Z",
      "events": [
        {
          "status": "picked_up",
          "location": "Dhaka Warehouse",
          "timestamp": "2026-01-31T14:30:00Z"
        },
        {
          "status": "in_transit",
          "location": "Gulshan Hub",
          "timestamp": "2026-01-31T16:45:00Z"
        },
        {
          "status": "out_for_delivery",
          "location": "Gulshan Area",
          "timestamp": "2026-02-01T07:00:00Z"
        }
      ]
    }
  }
}
```

### 3.4 Customers API

Base Path: `/customers`

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/customers` | List customers | Partner |
| GET | `/customers/{id}` | Get customer | Partner |
| POST | `/customers` | Create customer | API Key |
| PUT | `/customers/{id}` | Update customer | API Key |
| GET | `/customers/{id}/orders` | Get customer orders | Partner |
| GET | `/customers/{id}/subscriptions` | Get subscriptions | API Key |
| POST | `/customers/{id}/addresses` | Add address | API Key |
| GET | `/customers/{id}/loyalty` | Get loyalty points | API Key |

#### GET /customers/{id}

**Response:**
```json
{
  "data": {
    "id": "cust_12345",
    "customer_number": "CUST-12345",
    "type": "individual",
    "profile": {
      "first_name": "John",
      "last_name": "Doe",
      "email": "john.doe@example.com",
      "phone": "+8801712345678",
      "date_of_birth": "1985-05-15",
      "gender": "male"
    },
    "addresses": [
      {
        "id": "addr_001",
        "type": "home",
        "name": "Home",
        "address_line_1": "123 Gulshan Avenue",
        "address_line_2": "Apartment 4B",
        "city": "Dhaka",
        "postal_code": "1212",
        "country": "Bangladesh",
        "is_default": true
      }
    ],
    "preferences": {
      "language": "en",
      "communication": {
        "email": true,
        "sms": true,
        "push": true
      },
      "dietary_restrictions": []
    },
    "subscriptions": {
      "active_count": 2,
      "subscriptions": [
        {
          "id": "sub_001",
          "product_name": "Saffron Organic Milk",
          "frequency": "daily",
          "quantity": 2,
          "next_delivery": "2026-02-01",
          "status": "active"
        }
      ]
    },
    "loyalty": {
      "points_balance": 1250,
      "lifetime_points": 5000,
      "tier": "gold",
      "tier_progress": 75
    },
    "order_summary": {
      "total_orders": 45,
      "total_spent": 45250.00,
      "average_order_value": 1005.56,
      "first_order_date": "2025-03-15",
      "last_order_date": "2026-01-28"
    },
    "status": "active",
    "created_at": "2025-03-15T08:00:00Z",
    "updated_at": "2026-01-28T18:30:00Z"
  }
}
```

### 3.5 Inventory API

Base Path: `/inventory`

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/inventory` | List inventory | Partner |
| GET | `/inventory/{product_id}` | Get product inventory | API Key |
| PUT | `/inventory/{product_id}` | Update inventory | Partner |
| POST | `/inventory/adjust` | Adjust inventory | Partner |
| GET | `/inventory/warehouses` | List warehouses | Partner |
| GET | `/inventory/movements` | Get stock movements | Partner |

#### GET /inventory/{product_id}

**Response:**
```json
{
  "data": {
    "product_id": "prod_safron_milk_1l",
    "sku": "SAF-MILK-1L",
    "name": "Saffron Organic Milk",
    "total_quantity": 1500,
    "available_quantity": 1200,
    "reserved_quantity": 300,
    "reorder_level": 500,
    "reorder_quantity": 1000,
    "unit_of_measure": "liter",
    "warehouses": [
      {
        "id": "wh_dhaka_main",
        "name": "Dhaka Main Warehouse",
        "quantity": 1000,
        "available": 800,
        "reserved": 200,
        "location": {
          "zone": "Cold Storage A",
          "aisle": "A1",
          "shelf": "S3"
        },
        "last_counted": "2026-01-30T10:00:00Z"
      },
      {
        "id": "wh_chittagong",
        "name": "Chittagong Warehouse",
        "quantity": 500,
        "available": 400,
        "reserved": 100,
        "last_counted": "2026-01-29T14:00:00Z"
      }
    ],
    "batches": [
      {
        "batch_id": "BATCH-20260130-001",
        "quantity": 800,
        "production_date": "2026-01-30",
        "expiry_date": "2026-02-06",
        "fat_percentage": 3.5,
        "snf_percentage": 8.5
      },
      {
        "batch_id": "BATCH-20260129-002",
        "quantity": 400,
        "production_date": "2026-01-29",
        "expiry_date": "2026-02-05",
        "fat_percentage": 3.6,
        "snf_percentage": 8.6
      }
    ],
    "movements": {
      "last_24h": {
        "incoming": 1000,
        "outgoing": 450,
        "adjustments": -5
      }
    }
  }
}
```

### 3.6 Farm Data API

Base Path: `/farm`

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/farm/animals` | List cattle | Partner |
| GET | `/farm/animals/{id}` | Get animal details | Partner |
| GET | `/farm/animals/{id}/health` | Get health records | Partner |
| GET | `/farm/animals/{id}/production` | Get milk production | Partner |
| GET | `/farm/production/daily` | Get daily production | Partner |
| GET | `/farm/production/analytics` | Production analytics | Partner |
| GET | `/farm/feed/inventory` | Feed inventory | Partner |
| GET | `/farm/health/alerts` | Health alerts | Partner |

#### GET /farm/animals/{id}

**Response:**
```json
{
  "data": {
    "id": "cattle_sd_001",
    "tag_number": "SD-001",
    "rfid": "E200341502001080",
    "name": "Lakshmi",
    "breed": {
      "primary": "Holstein Friesian",
      "percentage": 75
    },
    "type": "dairy",
    "gender": "female",
    "birth_date": "2020-03-15",
    "age_years": 5,
    "origin": "farm_born",
    "mother_id": "cattle_sd_045",
    "father_id": "bull_001",
    "current_status": {
      "state": "lactating",
      "lactation_number": 3,
      "days_in_milk": 120,
      "pregnancy_status": "not_pregnant"
    },
    "physical": {
      "weight_kg": 550,
      "height_cm": 145,
      "body_condition_score": 3.5,
      "color": "black_white"
    },
    "production": {
      "current_daily_yield_liters": 28.5,
      "peak_yield_liters": 35.0,
      "lifetime_yield_liters": 28500,
      "fat_percentage": 3.8,
      "snf_percentage": 8.6,
      "protein_percentage": 3.4
    },
    "health": {
      "last_vet_check": "2026-01-25",
      "next_vaccination": "2026-02-15",
      "health_status": "healthy",
      "recent_treatments": []
    },
    "location": {
      "shed": "Shed A",
      "stall": "A-12",
      "pasture": "Pasture 1"
    },
    "images": [
      {
        "url": "https://cdn.smartdairybd.com/farm/cattle/sd-001.jpg",
        "type": "profile",
        "taken_date": "2025-12-01"
      }
    ],
    "created_at": "2020-03-15T08:00:00Z",
    "updated_at": "2026-01-31T06:00:00Z"
  }
}
```

#### GET /farm/production/daily

**Response:**
```json
{
  "data": {
    "date": "2026-01-31",
    "total_milking_cows": 75,
    "total_milked": 68,
    "dry_cows": 5,
    "sick_cows": 2,
    "production_summary": {
      "total_yield_liters": 920,
      "average_per_cow_liters": 13.53,
      "fat_percentage": 3.6,
      "snf_percentage": 8.5,
      "protein_percentage": 3.3,
      "density": 1.032
    },
    "milking_sessions": [
      {
        "session": "morning",
        "time": "05:00-08:00",
        "yield_liters": 380,
        "cows_milked": 68,
        "average_liters": 13.8
      },
      {
        "session": "evening",
        "time": "16:00-19:00",
        "yield_liters": 340,
        "cows_milked": 66,
        "average_liters": 13.1
      }
    ],
    "quality_metrics": {
      "avg_fat": 3.6,
      "avg_snf": 8.5,
      "avg_protein": 3.3,
      "somatic_cell_count": 180000,
      "bacterial_count": 15000
    },
    "comparison": {
      "vs_yesterday": {
        "yield_change_percent": 2.1,
        "quality_change_percent": 0.5
      },
      "vs_last_week_avg": {
        "yield_change_percent": 1.8,
        "quality_change_percent": -0.2
      }
    }
  }
}
```

### 3.7 Webhooks

Base Path: `/webhooks`

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/webhooks` | List webhook subscriptions | Partner |
| POST | `/webhooks` | Create webhook subscription | Partner |
| GET | `/webhooks/{id}` | Get webhook details | Partner |
| PUT | `/webhooks/{id}` | Update webhook | Partner |
| DELETE | `/webhooks/{id}` | Delete webhook | Partner |
| POST | `/webhooks/{id}/test` | Test webhook | Partner |
| GET | `/webhooks/{id}/deliveries` | Get delivery logs | Partner |

#### POST /webhooks

**Request:**
```bash
curl -X POST https://api.smartdairybd.com/v1/webhooks \
  -H "X-API-Key: {api_key}" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://your-app.com/webhooks/smart-dairy",
    "events": [
      "order.created",
      "order.paid",
      "order.shipped",
      "order.delivered",
      "order.cancelled",
      "inventory.low_stock",
      "product.updated"
    ],
    "description": "Order and inventory notifications",
    "active": true,
    "secret": "whsec_your_webhook_secret",
    "metadata": {
      "environment": "production",
      "version": "v1"
    },
    "retry_policy": {
      "max_retries": 5,
      "retry_interval_seconds": 60,
      "timeout_seconds": 30
    }
  }'
```

**Response:**
```json
{
  "data": {
    "id": "whk_abc123def456",
    "url": "https://your-app.com/webhooks/smart-dairy",
    "events": [
      "order.created",
      "order.paid",
      "order.shipped",
      "order.delivered",
      "order.cancelled",
      "inventory.low_stock",
      "product.updated"
    ],
    "description": "Order and inventory notifications",
    "active": true,
    "secret_prefix": "whsec_yo...",
    "metadata": {
      "environment": "production",
      "version": "v1"
    },
    "created_at": "2026-01-31T11:00:00Z",
    "updated_at": "2026-01-31T11:00:00Z"
  }
}
```

---

## 4. Request/Response Format

### 4.1 JSON Standards

All API requests and responses use JSON format with UTF-8 encoding.

#### Request Headers

```http
Content-Type: application/json
Accept: application/json
X-API-Key: {your_api_key}
X-Request-ID: {unique_request_id}  # Optional, for idempotency
```

#### Response Structure

All responses follow a consistent structure:

**Success Response (2xx):**
```json
{
  "data": { ... },           // Response data (varies by endpoint)
  "meta": {                  // Metadata
    "request_id": "req_abc123",
    "timestamp": "2026-01-31T10:00:00Z",
    "api_version": "v1"
  },
  "pagination": {            // Only for list endpoints
    "total": 100,
    "page": 1,
    "limit": 20,
    "total_pages": 5,
    "has_next": true,
    "has_prev": false
  }
}
```

**Error Response (4xx/5xx):**
```json
{
  "error": {
    "code": "INVALID_REQUEST",
    "message": "The request could not be processed",
    "details": [
      {
        "field": "email",
        "message": "Invalid email format"
      }
    ],
    "request_id": "req_def456",
    "timestamp": "2026-01-31T10:00:00Z",
    "documentation_url": "https://developers.smartdairybd.com/docs/errors/INVALID_REQUEST"
  }
}
```

### 4.2 Error Codes

| Code | HTTP Status | Description |
|------|-------------|-------------|
| `BAD_REQUEST` | 400 | Malformed request syntax |
| `UNAUTHORIZED` | 401 | Authentication required or failed |
| `FORBIDDEN` | 403 | Insufficient permissions |
| `NOT_FOUND` | 404 | Resource not found |
| `METHOD_NOT_ALLOWED` | 405 | HTTP method not allowed |
| `CONFLICT` | 409 | Resource conflict |
| `UNPROCESSABLE_ENTITY` | 422 | Validation error |
| `RATE_LIMIT_EXCEEDED` | 429 | Too many requests |
| `INTERNAL_SERVER_ERROR` | 500 | Server error |
| `SERVICE_UNAVAILABLE` | 503 | Service temporarily unavailable |

#### Error Code Reference

| Error Code | Category | Description |
|------------|----------|-------------|
| `INVALID_API_KEY` | Authentication | The provided API key is invalid or expired |
| `MISSING_API_KEY` | Authentication | API key header is missing |
| `INVALID_TOKEN` | Authentication | The access token is invalid or expired |
| `INSUFFICIENT_SCOPE` | Authorization | Token does not have required scope |
| `RESOURCE_NOT_FOUND` | Not Found | The requested resource does not exist |
| `VALIDATION_ERROR` | Validation | Request data failed validation |
| `DUPLICATE_RESOURCE` | Conflict | Resource already exists |
| `RATE_LIMIT_EXCEEDED` | Rate Limit | API rate limit has been exceeded |
| `PAYMENT_REQUIRED` | Payment | Payment processing failed |
| `INSUFFICIENT_INVENTORY` | Inventory | Not enough inventory for order |

### 4.3 Pagination

List endpoints support pagination using cursor-based or offset-based methods.

#### Offset Pagination (Default)

**Request:**
```bash
GET /products?page=2&limit=50
```

**Response:**
```json
{
  "data": [...],
  "pagination": {
    "total": 250,
    "page": 2,
    "limit": 50,
    "total_pages": 5,
    "has_next": true,
    "has_prev": true
  }
}
```

#### Cursor Pagination (Recommended for Large Datasets)

**Request:**
```bash
GET /orders?cursor=eyJpZCI6Im9yZF8xMjM0NTYiLCJjcmVhdGVkX2F0IjoiMjAyNi0wMS0zMFQxMDowMDowMFoifQ&limit=50
```

**Response:**
```json
{
  "data": [...],
  "pagination": {
    "next_cursor": "eyJpZCI6Im9yZF85ODc2NTQiLCJjcmVhdGVkX2F0IjoiMjAyNi0wMS0yOVQxNjozMDowMFoifQ",
    "prev_cursor": null,
    "has_more": true
  }
}
```

#### Pagination Best Practices

```python
class PaginatedClient:
    def get_all_products(self):
        """Fetch all products using cursor pagination"""
        all_products = []
        cursor = None
        
        while True:
            params = {'limit': 100}
            if cursor:
                params['cursor'] = cursor
            
            response = self.request('GET', '/products', params=params)
            data = response.json()
            
            all_products.extend(data['data'])
            
            if not data['pagination']['has_more']:
                break
                
            cursor = data['pagination']['next_cursor']
        
        return all_products
```

### 4.4 Filtering

Most list endpoints support advanced filtering capabilities.

#### Filter Operators

| Operator | Description | Example |
|----------|-------------|---------|
| `eq` | Equal | `status:eq:active` |
| `ne` | Not equal | `status:ne:deleted` |
| `gt` | Greater than | `price:gt:100` |
| `gte` | Greater than or equal | `price:gte:100` |
| `lt` | Less than | `price:lt:500` |
| `lte` | Less than or equal | `price:lte:500` |
| `in` | In array | `status:in:active,pending` |
| `nin` | Not in array | `status:nin:deleted` |
| `like` | Pattern match | `name:like:*milk*` |
| `between` | Between range | `price:between:100,500` |

#### Filter Examples

```bash
# Simple filter
GET /products?category=dairy

# Multiple filters
GET /products?category=dairy&status:active&price:lte:200

# Date range filter
GET /orders?created_at:between:2026-01-01,2026-01-31

# Array filter
GET /orders?status:in:pending,processing,shipped

# Combined with sorting and pagination
GET /products?category=dairy&status:active&sort=-created_at&page=1&limit=20
```

---

## 5. SDK Documentation

### 5.1 Python SDK

#### Installation

```bash
pip install smartdairy-sdk
```

#### Quick Start

```python
from smartdairy import SmartDairyClient

# Initialize client
client = SmartDairyClient(
    api_key="your_api_key",
    environment="sandbox"  # or "production"
)

# List products
products = client.products.list(
    category="dairy",
    in_stock=True,
    limit=10
)

# Create order
order = client.orders.create({
    "customer": {"email": "customer@example.com"},
    "items": [
        {"product_id": "prod_123", "quantity": 2}
    ],
    "shipping_address": {...}
})
```

#### Full Python SDK Example

```python
from smartdairy import SmartDairyClient
from smartdairy.exceptions import SmartDairyError, RateLimitError

class SmartDairyIntegration:
    def __init__(self, api_key, environment='sandbox'):
        self.client = SmartDairyClient(
            api_key=api_key,
            environment=environment,
            timeout=30,
            max_retries=3
        )
    
    def sync_products(self):
        """Sync all products to local database"""
        try:
            products = self.client.products.list_all()
            for product in products:
                self._save_to_local_db(product)
            return len(products)
        except RateLimitError as e:
            # Handle rate limiting
            time.sleep(e.retry_after)
            return self.sync_products()
        except SmartDairyError as e:
            logger.error(f"Smart Dairy API error: {e}")
            raise
    
    def create_subscription_order(self, customer_id, product_id, frequency):
        """Create a subscription order"""
        subscription = self.client.subscriptions.create({
            "customer_id": customer_id,
            "product_id": product_id,
            "frequency": frequency,  # daily, weekly, monthly
            "start_date": datetime.now().isoformat(),
            "quantity": 1
        })
        return subscription
    
    def get_inventory_alert(self, product_id, threshold=10):
        """Check if inventory is below threshold"""
        inventory = self.client.inventory.get(product_id)
        if inventory.available_quantity < threshold:
            return {
                "alert": True,
                "product_id": product_id,
                "current_stock": inventory.available_quantity,
                "threshold": threshold
            }
        return {"alert": False}
```

### 5.2 JavaScript SDK

#### Installation

```bash
npm install @smartdairy/sdk
# or
yarn add @smartdairy/sdk
```

#### Quick Start

```javascript
import { SmartDairyClient } from '@smartdairy/sdk';

// Initialize client
const client = new SmartDairyClient({
  apiKey: 'your_api_key',
  environment: 'sandbox', // or 'production'
});

// List products
const products = await client.products.list({
  category: 'dairy',
  inStock: true,
  limit: 10
});

// Create order
const order = await client.orders.create({
  customer: { email: 'customer@example.com' },
  items: [{ productId: 'prod_123', quantity: 2 }],
  shippingAddress: { ... }
});
```

#### Full JavaScript/TypeScript SDK Example

```typescript
import { SmartDairyClient, Product, Order, WebhookEvent } from '@smartdairy/sdk';

interface OrderItem {
  productId: string;
  quantity: number;
}

class SmartDairyService {
  private client: SmartDairyClient;

  constructor(apiKey: string, environment: 'sandbox' | 'production' = 'sandbox') {
    this.client = new SmartDairyClient({
      apiKey,
      environment,
      timeout: 30000,
    });
  }

  async getProductsByCategory(category: string): Promise<Product[]> {
    try {
      const response = await this.client.products.list({
        category,
        status: 'active',
        limit: 100,
      });
      return response.data;
    } catch (error) {
      console.error('Failed to fetch products:', error);
      throw error;
    }
  }

  async createBulkOrder(
    customerEmail: string,
    items: OrderItem[],
    deliveryDate: string
  ): Promise<Order> {
    const order = await this.client.orders.create({
      customer: { email: customerEmail },
      items: items.map(item => ({
        productId: item.productId,
        quantity: item.quantity,
      })),
      delivery: {
        preferredDate: deliveryDate,
        preferredTimeSlot: 'morning',
      },
    });

    return order;
  }

  async setupWebhook(url: string, events: WebhookEvent[]): Promise<void> {
    await this.client.webhooks.create({
      url,
      events,
      active: true,
    });
  }

  async handleWebhook(payload: any, signature: string): Promise<void> {
    const isValid = this.client.webhooks.verifySignature(payload, signature);
    if (!isValid) {
      throw new Error('Invalid webhook signature');
    }

    switch (payload.event) {
      case 'order.created':
        await this.processNewOrder(payload.data);
        break;
      case 'order.paid':
        await this.processPaymentConfirmation(payload.data);
        break;
      case 'inventory.low_stock':
        await this.notifyLowStock(payload.data);
        break;
    }
  }
}

export default SmartDairyService;
```

### 5.3 PHP SDK

#### Installation

```bash
composer require smartdairy/sdk
```

#### Quick Start

```php
<?php
require_once 'vendor/autoload.php';

use SmartDairy\Client;

// Initialize client
$client = new Client([
    'api_key' => 'your_api_key',
    'environment' => 'sandbox',
]);

// List products
$products = $client->products->list([
    'category' => 'dairy',
    'in_stock' => true,
    'limit' => 10
]);

// Create order
$order = $client->orders->create([
    'customer' => ['email' => 'customer@example.com'],
    'items' => [
        ['product_id' => 'prod_123', 'quantity' => 2]
    ]
]);
```

#### Full PHP SDK Example

```php
<?php
namespace App\Services;

use SmartDairy\Client;
use SmartDairy\Exceptions\SmartDairyException;
use SmartDairy\Exceptions\RateLimitException;
use Psr\Log\LoggerInterface;

class SmartDairyIntegration
{
    private Client $client;
    private LoggerInterface $logger;

    public function __construct(string $apiKey, LoggerInterface $logger, string $environment = 'sandbox')
    {
        $this->client = new Client([
            'api_key' => $apiKey,
            'environment' => $environment,
            'timeout' => 30,
        ]);
        $this->logger = $logger;
    }

    public function syncInventory(): int
    {
        try {
            $products = $this->client->products->listAll();
            
            foreach ($products as $product) {
                $inventory = $this->client->inventory->get($product['id']);
                $this->updateLocalInventory($product['id'], $inventory);
            }
            
            return count($products);
        } catch (RateLimitException $e) {
            $this->logger->warning('Rate limit hit, waiting...');
            sleep($e->getRetryAfter());
            return $this->syncInventory();
        } catch (SmartDairyException $e) {
            $this->logger->error('Smart Dairy API error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createRecurringOrder(
        string $customerId,
        array $items,
        string $frequency
    ): array {
        return $this->client->subscriptions->create([
            'customer_id' => $customerId,
            'items' => $items,
            'frequency' => $frequency,
            'start_date' => date('Y-m-d'),
        ]);
    }

    public function getDailyProductionReport(string $date): array
    {
        return $this->client->farm->production->daily($date);
    }

    private function updateLocalInventory(string $productId, array $inventory): void
    {
        // Update local database
    }
}
```

### 5.4 Java SDK

#### Installation

**Maven:**
```xml
<dependency>
    <groupId>com.smartdairy</groupId>
    <artifactId>smartdairy-sdk</artifactId>
    <version>1.0.0</version>
</dependency>
```

**Gradle:**
```gradle
implementation 'com.smartdairy:smartdairy-sdk:1.0.0'
```

#### Quick Start

```java
import com.smartdairy.SmartDairyClient;
import com.smartdairy.models.Product;
import com.smartdairy.models.Order;

public class SmartDairyExample {
    public static void main(String[] args) {
        // Initialize client
        SmartDairyClient client = new SmartDairyClient.Builder()
            .apiKey("your_api_key")
            .environment(SmartDairyClient.Environment.SANDBOX)
            .build();

        // List products
        List<Product> products = client.products()
            .list(new ProductListRequest()
                .category("dairy")
                .inStock(true)
                .limit(10)
            );

        // Create order
        Order order = client.orders()
            .create(new OrderCreateRequest()
                .customer(new Customer().email("customer@example.com"))
                .addItem(new OrderItem().productId("prod_123").quantity(2))
            );
    }
}
```

#### Full Java SDK Example

```java
package com.example.smartdairy;

import com.smartdairy.SmartDairyClient;
import com.smartdairy.exceptions.SmartDairyException;
import com.smartdairy.exceptions.RateLimitException;
import com.smartdairy.models.*;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.time.LocalDate;
import java.util.List;

@Service
public class SmartDairyIntegrationService {
    private static final Logger logger = LoggerFactory.getLogger(SmartDairyIntegrationService.class);
    
    private final SmartDairyClient client;
    
    public SmartDairyIntegrationService(@Value("${smartdairy.api-key}") String apiKey,
                                        @Value("${smartdairy.environment}") String environment) {
        this.client = new SmartDairyClient.Builder()
            .apiKey(apiKey)
            .environment(SmartDairyClient.Environment.valueOf(environment.toUpperCase()))
            .timeout(30)
            .build();
    }
    
    public List<Product> getActiveProducts(String category) {
        try {
            return client.products()
                .list(new ProductListRequest()
                    .category(category)
                    .status("active")
                    .limit(100)
                );
        } catch (SmartDairyException e) {
            logger.error("Failed to fetch products: {}", e.getMessage());
            throw new RuntimeException("Product fetch failed", e);
        }
    }
    
    public Order createOrder(String customerEmail, List<OrderItem> items, LocalDate deliveryDate) {
        try {
            return client.orders()
                .create(new OrderCreateRequest()
                    .customer(new Customer().email(customerEmail))
                    .items(items)
                    .delivery(new Delivery()
                        .preferredDate(deliveryDate)
                        .preferredTimeSlot(Delivery.TimeSlot.MORNING)
                    )
                );
        } catch (SmartDairyException e) {
            logger.error("Failed to create order: {}", e.getMessage());
            throw new RuntimeException("Order creation failed", e);
        }
    }
    
    public DailyProductionReport getDailyProductionReport(LocalDate date) {
        try {
            return client.farm()
                .production()
                .daily(date);
        } catch (SmartDairyException e) {
            logger.error("Failed to fetch production report: {}", e.getMessage());
            throw new RuntimeException("Production report fetch failed", e);
        }
    }
    
    public WebhookSubscription setupWebhook(String url, List<String> events) {
        try {
            return client.webhooks()
                .create(new WebhookCreateRequest()
                    .url(url)
                    .events(events)
                    .active(true)
                );
        } catch (SmartDairyException e) {
            logger.error("Failed to setup webhook: {}", e.getMessage());
            throw new RuntimeException("Webhook setup failed", e);
        }
    }
}
```

---

## 6. Code Examples

### 6.1 cURL Examples

#### Authentication

```bash
# Get access token
curl -X POST https://api.smartdairybd.com/v1/auth/token \
  -H "Content-Type: application/json" \
  -d '{
    "grant_type": "client_credentials",
    "client_id": "your_client_id",
    "client_secret": "your_client_secret",
    "scope": "products:read orders:write"
  }'
```

#### Products

```bash
# List all products
curl -X GET "https://api.smartdairybd.com/v1/products?limit=10" \
  -H "X-API-Key: {api_key}"

# Get specific product
curl -X GET https://api.smartdairybd.com/v1/products/prod_safron_milk_1l \
  -H "X-API-Key: {api_key}"

# Search products
curl -X GET "https://api.smartdairybd.com/v1/products/search?q=milk&organic=true" \
  -H "X-API-Key: {api_key}"
```

#### Orders

```bash
# Create order
curl -X POST https://api.smartdairybd.com/v1/orders \
  -H "X-API-Key: {api_key}" \
  -H "Content-Type: application/json" \
  -d '{
    "customer": {"email": "customer@example.com"},
    "items": [
      {"product_id": "prod_safron_milk_1l", "quantity": 5}
    ],
    "shipping_address": {
      "name": "John Doe",
      "address_line_1": "123 Gulshan Avenue",
      "city": "Dhaka",
      "postal_code": "1212",
      "country": "Bangladesh"
    }
  }'

# Get order status
curl -X GET https://api.smartdairybd.com/v1/orders/ord_sd_20260131_001 \
  -H "X-API-Key: {api_key}"

# Cancel order
curl -X POST https://api.smartdairybd.com/v1/orders/ord_sd_20260131_001/cancel \
  -H "X-API-Key: {api_key}" \
  -H "Content-Type: application/json" \
  -d '{"reason": "Customer request"}'
```

#### Webhooks

```bash
# Create webhook subscription
curl -X POST https://api.smartdairybd.com/v1/webhooks \
  -H "X-API-Key: {api_key}" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://your-app.com/webhook",
    "events": ["order.created", "order.paid"],
    "secret": "your_webhook_secret"
  }'

# List webhooks
curl -X GET https://api.smartdairybd.com/v1/webhooks \
  -H "X-API-Key: {api_key}"

# Test webhook
curl -X POST https://api.smartdairybd.com/v1/webhooks/{webhook_id}/test \
  -H "X-API-Key: {api_key}"
```

### 6.2 Python Examples

#### Complete Integration Script

```python
#!/usr/bin/env python3
"""
Smart Dairy API Integration Example
Complete workflow demonstrating product sync, order creation, and webhook handling
"""

import os
import json
import hmac
import hashlib
import requests
from datetime import datetime, timedelta
from typing import Dict, List, Optional

class SmartDairyAPI:
    def __init__(self, api_key: str, environment: str = 'sandbox'):
        self.api_key = api_key
        self.base_url = f"https://{environment}-api.smartdairybd.com/v1"
        self.session = requests.Session()
        self.session.headers.update({
            'X-API-Key': api_key,
            'Content-Type': 'application/json',
            'User-Agent': 'SmartDairyIntegration/1.0'
        })
    
    def get_products(self, category: Optional[str] = None, 
                     in_stock: bool = True) -> List[Dict]:
        """Fetch products with optional filtering"""
        params = {'in_stock': in_stock, 'limit': 100}
        if category:
            params['category'] = category
        
        response = self.session.get(f"{self.base_url}/products", params=params)
        response.raise_for_status()
        return response.json()['data']
    
    def create_order(self, customer_email: str, items: List[Dict],
                     shipping_address: Dict) -> Dict:
        """Create a new order"""
        payload = {
            'customer': {'email': customer_email},
            'items': items,
            'shipping_address': shipping_address,
            'payment': {'method': 'bkash'},
            'delivery': {
                'preferred_date': (datetime.now() + timedelta(days=1)).strftime('%Y-%m-%d'),
                'preferred_time_slot': 'morning'
            }
        }
        
        response = self.session.post(f"{self.base_url}/orders", json=payload)
        response.raise_for_status()
        return response.json()['data']
    
    def get_order(self, order_id: str) -> Dict:
        """Get order details"""
        response = self.session.get(f"{self.base_url}/orders/{order_id}")
        response.raise_for_status()
        return response.json()['data']
    
    def create_subscription(self, customer_id: str, product_id: str,
                           frequency: str, quantity: int = 1) -> Dict:
        """Create a subscription order"""
        payload = {
            'customer_id': customer_id,
            'product_id': product_id,
            'frequency': frequency,
            'quantity': quantity,
            'start_date': datetime.now().strftime('%Y-%m-%d')
        }
        
        response = self.session.post(f"{self.base_url}/subscriptions", json=payload)
        response.raise_for_status()
        return response.json()['data']


class WebhookHandler:
    def __init__(self, webhook_secret: str):
        self.webhook_secret = webhook_secret
    
    def verify_signature(self, payload: bytes, signature: str) -> bool:
        """Verify webhook signature"""
        expected = hmac.new(
            self.webhook_secret.encode(),
            payload,
            hashlib.sha256
        ).hexdigest()
        return hmac.compare_digest(expected, signature)
    
    def process_webhook(self, payload: Dict) -> None:
        """Process incoming webhook"""
        event_type = payload.get('event')
        data = payload.get('data')
        
        handlers = {
            'order.created': self._handle_order_created,
            'order.paid': self._handle_order_paid,
            'order.shipped': self._handle_order_shipped,
            'inventory.low_stock': self._handle_low_stock,
        }
        
        handler = handlers.get(event_type)
        if handler:
            handler(data)
        else:
            print(f"Unhandled event type: {event_type}")
    
    def _handle_order_created(self, data: Dict):
        print(f"New order created: {data['order_number']}")
        # Send notification to fulfillment team
    
    def _handle_order_paid(self, data: Dict):
        print(f"Order paid: {data['order_number']}")
        # Update order status in internal system
    
    def _handle_order_shipped(self, data: Dict):
        print(f"Order shipped: {data['order_number']}")
        # Send tracking notification to customer
    
    def _handle_low_stock(self, data: Dict):
        print(f"Low stock alert: {data['product_name']} - {data['available_quantity']} remaining")
        # Send alert to procurement team


# Usage Example
def main():
    # Initialize API client
    api = SmartDairyAPI(
        api_key=os.environ['SMART_DAIRY_API_KEY'],
        environment='sandbox'
    )
    
    # 1. Get available products
    print("Fetching products...")
    products = api.get_products(category='dairy')
    print(f"Found {len(products)} products")
    
    # 2. Create an order
    print("\nCreating order...")
    order = api.create_order(
        customer_email='customer@example.com',
        items=[
            {'product_id': 'prod_safron_milk_1l', 'quantity': 5},
            {'product_id': 'prod_safron_yogurt_500g', 'quantity': 2}
        ],
        shipping_address={
            'name': 'John Doe',
            'address_line_1': '123 Gulshan Avenue',
            'city': 'Dhaka',
            'postal_code': '1212',
            'country': 'Bangladesh',
            'phone': '+8801712345678'
        }
    )
    print(f"Order created: {order['order_number']}")
    
    # 3. Check order status
    print("\nChecking order status...")
    order_details = api.get_order(order['id'])
    print(f"Status: {order_details['status']}")

if __name__ == '__main__':
    main()
```

### 6.3 JavaScript Examples

#### Node.js Express Webhook Handler

```javascript
const express = require('express');
const crypto = require('crypto');
const axios = require('axios');

const app = express();
app.use(express.raw({ type: 'application/json' }));

class SmartDairyIntegration {
  constructor(apiKey, environment = 'sandbox') {
    this.apiKey = apiKey;
    this.baseUrl = `https://${environment}-api.smartdairybd.com/v1`;
    this.client = axios.create({
      baseURL: this.baseUrl,
      headers: {
        'X-API-Key': apiKey,
        'Content-Type': 'application/json'
      }
    });
  }

  async getProducts(category = null, inStock = true) {
    const params = { in_stock: inStock, limit: 100 };
    if (category) params.category = category;
    
    const response = await this.client.get('/products', { params });
    return response.data.data;
  }

  async createOrder(customerEmail, items, shippingAddress) {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const payload = {
      customer: { email: customerEmail },
      items,
      shipping_address: shippingAddress,
      payment: { method: 'bkash' },
      delivery: {
        preferred_date: tomorrow.toISOString().split('T')[0],
        preferred_time_slot: 'morning'
      }
    };
    
    const response = await this.client.post('/orders', payload);
    return response.data.data;
  }

  async createWebhook(url, events) {
    const payload = {
      url,
      events,
      active: true
    };
    
    const response = await this.client.post('/webhooks', payload);
    return response.data.data;
  }
}

// Webhook verification middleware
function verifyWebhookSignature(secret) {
  return (req, res, next) => {
    const signature = req.headers['x-webhook-signature'];
    if (!signature) {
      return res.status(401).json({ error: 'Missing signature' });
    }

    const expected = crypto
      .createHmac('sha256', secret)
      .update(req.body)
      .digest('hex');

    if (!crypto.timingSafeEqual(
      Buffer.from(signature),
      Buffer.from(expected)
    )) {
      return res.status(401).json({ error: 'Invalid signature' });
    }

    req.body = JSON.parse(req.body);
    next();
  };
}

// Webhook handler
app.post('/webhooks/smart-dairy',
  verifyWebhookSignature(process.env.WEBHOOK_SECRET),
  async (req, res) => {
    const { event, data } = req.body;
    
    console.log(`Received webhook: ${event}`);
    
    try {
      switch (event) {
        case 'order.created':
          await handleOrderCreated(data);
          break;
        case 'order.paid':
          await handleOrderPaid(data);
          break;
        case 'order.shipped':
          await handleOrderShipped(data);
          break;
        case 'inventory.low_stock':
          await handleLowStock(data);
          break;
        default:
          console.log(`Unhandled event: ${event}`);
      }
      
      res.status(200).json({ received: true });
    } catch (error) {
      console.error('Webhook processing error:', error);
      res.status(500).json({ error: 'Processing failed' });
    }
  }
);

// Event handlers
async function handleOrderCreated(order) {
  console.log(`New order: ${order.order_number}`);
  // Notify fulfillment system
}

async function handleOrderPaid(order) {
  console.log(`Order paid: ${order.order_number}`);
  // Update inventory, notify warehouse
}

async function handleOrderShipped(order) {
  console.log(`Order shipped: ${order.order_number}`);
  // Send tracking to customer
}

async function handleLowStock(data) {
  console.log(`Low stock: ${data.product_name}`);
  // Alert procurement team
}

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Webhook server running on port ${PORT}`);
});
```

### 6.4 Mobile Integration

#### React Native Example

```javascript
// SmartDairyAPI.js
import AsyncStorage from '@react-native-async-storage/async-storage';

const BASE_URL = 'https://api.smartdairybd.com/v1';

class SmartDairyAPI {
  constructor() {
    this.apiKey = null;
    this.authToken = null;
  }

  async initialize() {
    this.apiKey = await AsyncStorage.getItem('smartdairy_api_key');
    this.authToken = await AsyncStorage.getItem('smartdairy_auth_token');
  }

  async setCredentials(apiKey) {
    this.apiKey = apiKey;
    await AsyncStorage.setItem('smartdairy_api_key', apiKey);
  }

  async request(endpoint, options = {}) {
    const url = `${BASE_URL}${endpoint}`;
    const headers = {
      'Content-Type': 'application/json',
      'X-API-Key': this.apiKey,
      ...options.headers
    };

    if (this.authToken) {
      headers['Authorization'] = `Bearer ${this.authToken}`;
    }

    const response = await fetch(url, {
      ...options,
      headers
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.error?.message || 'Request failed');
    }

    return response.json();
  }

  // Products
  async getProducts(category = null, page = 1) {
    const params = new URLSearchParams({ page: String(page), limit: '20' });
    if (category) params.append('category', category);
    
    return this.request(`/products?${params}`);
  }

  async searchProducts(query) {
    return this.request(`/products/search?q=${encodeURIComponent(query)}`);
  }

  // Orders
  async createOrder(orderData) {
    return this.request('/orders', {
      method: 'POST',
      body: JSON.stringify(orderData)
    });
  }

  async getOrders(page = 1) {
    return this.request(`/orders?page=${page}`);
  }

  async getOrder(orderId) {
    return this.request(`/orders/${orderId}`);
  }

  // Subscriptions
  async createSubscription(subscriptionData) {
    return this.request('/subscriptions', {
      method: 'POST',
      body: JSON.stringify(subscriptionData)
    });
  }

  async getSubscriptions() {
    return this.request('/subscriptions');
  }

  async pauseSubscription(subscriptionId) {
    return this.request(`/subscriptions/${subscriptionId}/pause`, {
      method: 'POST'
    });
  }

  async resumeSubscription(subscriptionId) {
    return this.request(`/subscriptions/${subscriptionId}/resume`, {
      method: 'POST'
    });
  }
}

export default new SmartDairyAPI();
```

#### Flutter Example

```dart
// smart_dairy_api.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class SmartDairyAPI {
  static const String baseUrl = 'https://api.smartdairybd.com/v1';
  String? _apiKey;
  String? _authToken;

  Future<void> initialize() async {
    final prefs = await SharedPreferences.getInstance();
    _apiKey = prefs.getString('smartdairy_api_key');
    _authToken = prefs.getString('smartdairy_auth_token');
  }

  Future<void> setCredentials(String apiKey) async {
    _apiKey = apiKey;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('smartdairy_api_key', apiKey);
  }

  Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    'X-API-Key': _apiKey ?? '',
    if (_authToken != null) 'Authorization': 'Bearer $_authToken',
  };

  Future<Map<String, dynamic>> _request(
    String method,
    String endpoint, {
    Map<String, dynamic>? body,
  }) async {
    final url = Uri.parse('$baseUrl$endpoint');
    final response = await http.Request(method, url)
      ..headers.addAll(_headers)
      ..body = body != null ? jsonEncode(body) : '';

    final streamedResponse = await response.send();
    final responseData = await http.Response.fromStream(streamedResponse);

    if (responseData.statusCode >= 200 && responseData.statusCode < 300) {
      return jsonDecode(responseData.body);
    } else {
      final error = jsonDecode(responseData.body);
      throw Exception(error['error']?['message'] ?? 'Request failed');
    }
  }

  // Products
  Future<Map<String, dynamic>> getProducts({
    String? category,
    int page = 1,
    int limit = 20,
  }) async {
    final params = <String, String>{
      'page': page.toString(),
      'limit': limit.toString(),
      if (category != null) 'category': category,
    };
    final queryString = params.entries
      .map((e) => '${e.key}=${Uri.encodeComponent(e.value)}')
      .join('&');
    return _request('GET', '/products?$queryString');
  }

  Future<Map<String, dynamic>> searchProducts(String query) async {
    return _request('GET', '/products/search?q=${Uri.encodeComponent(query)}');
  }

  // Orders
  Future<Map<String, dynamic>> createOrder(Map<String, dynamic> orderData) async {
    return _request('POST', '/orders', body: orderData);
  }

  Future<Map<String, dynamic>> getOrders({int page = 1}) async {
    return _request('GET', '/orders?page=$page');
  }

  Future<Map<String, dynamic>> getOrder(String orderId) async {
    return _request('GET', '/orders/$orderId');
  }

  // Subscriptions
  Future<Map<String, dynamic>> createSubscription(
    Map<String, dynamic> subscriptionData,
  ) async {
    return _request('POST', '/subscriptions', body: subscriptionData);
  }

  Future<Map<String, dynamic>> getSubscriptions() async {
    return _request('GET', '/subscriptions');
  }
}
```

---

## 7. Webhooks

### 7.1 Event Types

| Event Category | Event Name | Description |
|----------------|------------|-------------|
| **Order Events** | `order.created` | New order created |
| | `order.paid` | Order payment confirmed |
| | `order.processing` | Order being prepared |
| | `order.shipped` | Order dispatched |
| | `order.delivered` | Order delivered |
| | `order.cancelled` | Order cancelled |
| | `order.refunded` | Order refunded |
| **Subscription Events** | `subscription.created` | New subscription |
| | `subscription.activated` | Subscription activated |
| | `subscription.paused` | Subscription paused |
| | `subscription.resumed` | Subscription resumed |
| | `subscription.cancelled` | Subscription cancelled |
| | `subscription.payment_failed` | Subscription payment failed |
| **Product Events** | `product.created` | New product added |
| | `product.updated` | Product details updated |
| | `product.deleted` | Product removed |
| | `product.price_changed` | Product price changed |
| **Inventory Events** | `inventory.updated` | Inventory quantity changed |
| | `inventory.low_stock` | Low stock alert |
| | `inventory.out_of_stock` | Product out of stock |
| | `inventory.back_in_stock` | Product back in stock |
| **Customer Events** | `customer.created` | New customer registered |
| | `customer.updated` | Customer profile updated |
| **Farm Events** | `farm.production.daily` | Daily production report |
| | `farm.animal.health_alert` | Animal health alert |
| | `farm.milking.completed` | Milking session completed |

### 7.2 Payload Formats

#### Order Event Payload

```json
{
  "id": "evt_abc123def456",
  "event": "order.paid",
  "created_at": "2026-01-31T10:18:00Z",
  "data": {
    "id": "ord_sd_20260131_001",
    "order_number": "SD-20260131-001",
    "status": "paid",
    "previous_status": "pending_payment",
    "customer": {
      "id": "cust_12345",
      "email": "customer@example.com",
      "name": "John Doe"
    },
    "items": [
      {
        "product_id": "prod_safron_milk_1l",
        "product_name": "Saffron Organic Milk",
        "quantity": 5,
        "unit_price": 120.00,
        "total": 600.00
      }
    ],
    "pricing": {
      "subtotal": 770.00,
      "delivery_fee": 50.00,
      "total": 820.00,
      "currency": "BDT"
    },
    "payment": {
      "method": "bkash",
      "transaction_id": "trx_bkash_abc123",
      "paid_at": "2026-01-31T10:18:00Z"
    },
    "shipping_address": {
      "name": "John Doe",
      "address_line_1": "123 Gulshan Avenue",
      "city": "Dhaka",
      "postal_code": "1212",
      "country": "Bangladesh"
    },
    "metadata": {
      "source": "api"
    }
  }
}
```

#### Inventory Event Payload

```json
{
  "id": "evt_def456ghi789",
  "event": "inventory.low_stock",
  "created_at": "2026-01-31T14:00:00Z",
  "data": {
    "product_id": "prod_safron_milk_1l",
    "sku": "SAF-MILK-1L",
    "product_name": "Saffron Organic Milk",
    "warehouse_id": "wh_dhaka_main",
    "warehouse_name": "Dhaka Main Warehouse",
    "available_quantity": 45,
    "reorder_level": 100,
    "reorder_quantity": 500,
    "last_restock_date": "2026-01-28",
    "suggested_action": "Place purchase order"
  }
}
```

#### Subscription Event Payload

```json
{
  "id": "evt_ghi789jkl012",
  "event": "subscription.payment_failed",
  "created_at": "2026-01-31T06:00:00Z",
  "data": {
    "id": "sub_12345",
    "customer": {
      "id": "cust_12345",
      "email": "customer@example.com",
      "name": "John Doe"
    },
    "product": {
      "id": "prod_safron_milk_1l",
      "name": "Saffron Organic Milk"
    },
    "frequency": "daily",
    "quantity": 2,
    "next_delivery_date": "2026-02-01",
    "payment_failure": {
      "reason": "insufficient_funds",
      "retry_count": 1,
      "max_retries": 3,
      "next_retry_at": "2026-01-31T12:00:00Z"
    }
  }
}
```

### 7.3 Security and Verification

Webhook payloads are signed using HMAC-SHA256 to ensure authenticity.

#### Signature Header

```http
POST /webhooks/smart-dairy HTTP/1.1
Host: your-app.com
Content-Type: application/json
X-Webhook-Signature: sha256=a1b2c3d4e5f6...
X-Webhook-Event: order.paid
X-Webhook-ID: evt_abc123def456
X-Webhook-Timestamp: 1738368000

{...payload...}
```

#### Verification Example

```python
import hmac
import hashlib
import time

def verify_webhook(payload: bytes, signature: str, secret: str, timestamp: str) -> bool:
    """
    Verify webhook signature and timestamp
    
    Args:
        payload: Raw request body
        signature: Signature from X-Webhook-Signature header
        secret: Webhook secret
        timestamp: Timestamp from X-Webhook-Timestamp header
    
    Returns:
        bool: True if valid
    """
    # Check timestamp (reject if older than 5 minutes)
    current_time = int(time.time())
    webhook_time = int(timestamp)
    if abs(current_time - webhook_time) > 300:
        raise ValueError("Webhook timestamp too old")
    
    # Extract signature value (remove 'sha256=' prefix)
    sig_value = signature.split('=')[1] if '=' in signature else signature
    
    # Compute expected signature
    expected = hmac.new(
        secret.encode('utf-8'),
        payload,
        hashlib.sha256
    ).hexdigest()
    
    # Compare signatures
    return hmac.compare_digest(sig_value, expected)

# Flask example
from flask import Flask, request, jsonify

app = Flask(__name__)
WEBHOOK_SECRET = "your_webhook_secret"

@app.route('/webhooks/smart-dairy', methods=['POST'])
def handle_webhook():
    signature = request.headers.get('X-Webhook-Signature')
    timestamp = request.headers.get('X-Webhook-Timestamp')
    
    if not signature or not timestamp:
        return jsonify({'error': 'Missing headers'}), 401
    
    try:
        if not verify_webhook(request.data, signature, WEBHOOK_SECRET, timestamp):
            return jsonify({'error': 'Invalid signature'}), 401
    except ValueError as e:
        return jsonify({'error': str(e)}), 401
    
    # Process webhook
    payload = request.get_json()
    event_type = payload['event']
    
    # Handle event
    process_event(event_type, payload['data'])
    
    return jsonify({'received': True}), 200
```

---

## 8. Testing

### 8.1 Sandbox Environment

The Sandbox environment provides a safe space to test API integrations without affecting production data.

**Sandbox URL:** `https://sandbox-api.smartdairybd.com/v1`

**Features:**
- Isolated test data
- Simulated payment processing
- Test webhook events
- Reset capability

### 8.2 Test Credentials

#### API Keys

| Environment | API Key | Purpose |
|-------------|---------|---------|
| Sandbox | `sd_test_demo123456789` | General testing |
| Sandbox | `sd_test_products_read` | Product-only access |
| Sandbox | `sd_test_orders_full` | Order management |

#### Test Customer

```json
{
  "email": "test.customer@example.com",
  "password": "TestPass123!",
  "customer_id": "cust_test_001"
}
```

#### Test Payment Methods

| Payment Method | Test Number/Credential | Result |
|----------------|------------------------|--------|
| bKash | 01711111111 | Success |
| bKash | 01722222222 | Insufficient funds |
| Nagad | 01733333333 | Success |
| Card | 4111 1111 1111 1111 | Success |
| Card | 4000 0000 0000 0002 | Declined |

### 8.3 Postman Collection

Download the official Postman collection:

```bash
curl -O https://developers.smartdairybd.com/downloads/postman-collection.json
```

**Collection includes:**
- All API endpoints
- Environment variables
- Example requests
- Test scripts

**Environment Variables:**

| Variable | Sandbox Value | Production Value |
|----------|---------------|------------------|
| `base_url` | `https://sandbox-api.smartdairybd.com/v1` | `https://api.smartdairybd.com/v1` |
| `api_key` | `sd_test_...` | `sd_live_...` |
| `client_id` | `test_client_...` | `live_client_...` |
| `client_secret` | `test_secret_...` | `live_secret_...` |

### 8.4 Test Scenarios

```python
# test_smart_dairy_api.py
import pytest
from smart_dairy import SmartDairyClient

class TestSmartDairyAPI:
    @pytest.fixture
    def client(self):
        return SmartDairyClient(
            api_key="sd_test_demo123456789",
            environment="sandbox"
        )
    
    def test_list_products(self, client):
        response = client.products.list(limit=10)
        assert len(response.data) <= 10
        assert response.pagination.total > 0
    
    def test_get_product(self, client):
        product = client.products.get("prod_safron_milk_1l")
        assert product.id == "prod_safron_milk_1l"
        assert product.pricing.currency == "BDT"
    
    def test_create_order(self, client):
        order = client.orders.create({
            "customer": {"email": "test@example.com"},
            "items": [
                {"product_id": "prod_safron_milk_1l", "quantity": 2}
            ],
            "shipping_address": {
                "name": "Test Customer",
                "address_line_1": "123 Test Street",
                "city": "Dhaka",
                "postal_code": "1212",
                "country": "Bangladesh"
            }
        })
        assert order.status == "pending_payment"
        assert len(order.items) == 1
    
    def test_order_lifecycle(self, client):
        # Create order
        order = client.orders.create({...})
        assert order.status == "pending_payment"
        
        # Simulate payment
        client.orders.simulate_payment(order.id, "success")
        order = client.orders.get(order.id)
        assert order.status == "paid"
        
        # Simulate shipping
        client.orders.simulate_shipment(order.id)
        order = client.orders.get(order.id)
        assert order.status == "shipped"
        
        # Simulate delivery
        client.orders.simulate_delivery(order.id)
        order = client.orders.get(order.id)
        assert order.status == "delivered"
    
    def test_webhook_signature_verification(self):
        from smart_dairy.webhooks import verify_signature
        
        payload = b'{"event":"order.paid","data":{"id":"ord_123"}}'
        secret = "test_secret"
        signature = "sha256=" + hmac.new(
            secret.encode(),
            payload,
            hashlib.sha256
        ).hexdigest()
        
        assert verify_signature(payload, signature, secret)
```

---

## 9. Changelog

### 9.1 Version History

| Version | Date | Changes | Status |
|---------|------|---------|--------|
| 1.0.0 | 2026-01-31 | Initial API release | Current |

### 9.2 Breaking Changes

Breaking changes will be announced at least 90 days in advance through:
- Developer newsletter
- API changelog
- Webhook notifications
- Dashboard announcements

#### Deprecation Policy

1. **Deprecation Notice**: 90 days before removal
2. **Sunset Header**: API responses include `Sunset` header
3. **Migration Guide**: Published for all breaking changes
4. **Support Period**: Legacy versions supported for 6 months

```http
HTTP/1.1 200 OK
Sunset: Sat, 31 Apr 2026 00:00:00 GMT
Deprecation: true
Warning: 299 - "This endpoint is deprecated. Use /v2/products instead."
```

### 9.3 API Versioning Strategy

```
/api/v1/products     # Current stable version
/api/v2/products     # Next version (upcoming)
```

---

## 10. Best Practices

### 10.1 Error Handling

```python
from smart_dairy.exceptions import (
    SmartDairyError,
    RateLimitError,
    AuthenticationError,
    ValidationError,
    NotFoundError
)

def handle_api_call(func):
    """Decorator for comprehensive error handling"""
    def wrapper(*args, **kwargs):
        try:
            return func(*args, **kwargs)
        except RateLimitError as e:
            # Implement exponential backoff
            time.sleep(e.retry_after)
            return func(*args, **kwargs)
        except AuthenticationError as e:
            # Refresh credentials and retry
            refresh_credentials()
            return func(*args, **kwargs)
        except ValidationError as e:
            # Log validation errors for debugging
            logger.error(f"Validation error: {e.errors}")
            raise
        except NotFoundError as e:
            # Handle missing resources gracefully
            logger.warning(f"Resource not found: {e.resource_id}")
            return None
        except SmartDairyError as e:
            # Log and alert for unexpected errors
            logger.error(f"API error: {e}")
            alert_ops_team(e)
            raise
    return wrapper
```

### 10.2 Retry Logic

```python
import time
from functools import wraps

def retry_with_backoff(max_retries=3, base_delay=1):
    """Exponential backoff retry decorator"""
    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            for attempt in range(max_retries):
                try:
                    return func(*args, **kwargs)
                except RateLimitError as e:
                    if attempt == max_retries - 1:
                        raise
                    delay = min(e.retry_after, base_delay * (2 ** attempt))
                    time.sleep(delay)
                except (ConnectionError, TimeoutError) as e:
                    if attempt == max_retries - 1:
                        raise
                    delay = base_delay * (2 ** attempt)
                    time.sleep(delay)
            return None
        return wrapper
    return decorator

@retry_with_backoff(max_retries=3)
def fetch_products():
    return client.products.list()
```

### 10.3 Caching

```python
import functools
import hashlib
import json
from datetime import datetime, timedelta

class APICache:
    def __init__(self, ttl_seconds=300):
        self.cache = {}
        self.ttl = ttl_seconds
    
    def get_key(self, func_name, args, kwargs):
        """Generate cache key from function call"""
        key_data = json.dumps({
            'func': func_name,
            'args': args,
            'kwargs': kwargs
        }, sort_keys=True)
        return hashlib.md5(key_data.encode()).hexdigest()
    
    def get(self, key):
        if key in self.cache:
            value, expiry = self.cache[key]
            if datetime.now() < expiry:
                return value
            del self.cache[key]
        return None
    
    def set(self, key, value):
        self.cache[key] = (value, datetime.now() + timedelta(seconds=self.ttl))
    
    def cached(self, ttl=None):
        def decorator(func):
            @functools.wraps(func)
            def wrapper(*args, **kwargs):
                cache_key = self.get_key(func.__name__, args, kwargs)
                cached = self.get(cache_key)
                if cached:
                    return cached
                
                result = func(*args, **kwargs)
                self.set(cache_key, result)
                return result
            return wrapper
        return decorator

# Usage
cache = APICache(ttl_seconds=300)

@cache.cached(ttl=600)
def get_product_details(product_id):
    return client.products.get(product_id)
```

---

## 11. Support

### 11.1 Developer Forum

Join the Smart Dairy Developer Community:
- **URL**: https://developers.smartdairybd.com/forum
- **Slack**: https://smartdairy-dev.slack.com
- **Discord**: https://discord.gg/smartdairy

### 11.2 Contact Information

| Support Channel | Details | Response Time |
|-----------------|---------|---------------|
| **Technical Support** | api-support@smartdairybd.com | 24 hours |
| **Emergency Hotline** | +880 1234 567 890 | 4 hours |
| **Account Management** | partner@smartdairybd.com | 1 business day |
| **Sales Inquiries** | sales@smartdairybd.com | 1 business day |

### 11.3 Service Level Agreement (SLA)

| Tier | Uptime Guarantee | API Response Time | Support Hours |
|------|------------------|-------------------|---------------|
| **Free** | 99.0% | < 2s avg | Community only |
| **Starter** | 99.5% | < 1s avg | Business hours |
| **Professional** | 99.9% | < 500ms avg | 24/5 |
| **Enterprise** | 99.99% | < 200ms avg | 24/7 |

**Credit Policy:**
- < 99.9% uptime: 10% credit
- < 99.0% uptime: 25% credit
- < 95.0% uptime: 50% credit

### 11.4 Status Page

Monitor API status: https://status.smartdairybd.com

**Subscribe to notifications:**
- Email alerts
- Slack notifications
- Webhook notifications
- SMS alerts (Enterprise only)

---

## 12. Appendices

### 12.1 OpenAPI Specification

The complete OpenAPI 3.0 specification is available at:

```
https://api.smartdairybd.com/openapi.json
```

**Generate client SDKs:**

```bash
# Using OpenAPI Generator
openapi-generator generate \
  -i https://api.smartdairybd.com/openapi.json \
  -g python \
  -o ./smartdairy-python-sdk

# Available generators: python, javascript, typescript, php, java, go, ruby, etc.
```

### 12.2 Error Code Reference

| Error Code | HTTP Status | Description | Resolution |
|------------|-------------|-------------|------------|
| `INVALID_REQUEST` | 400 | Malformed request | Check request format |
| `MISSING_FIELD` | 400 | Required field missing | Include all required fields |
| `INVALID_FORMAT` | 400 | Invalid data format | Check data types |
| `UNAUTHORIZED` | 401 | Authentication failed | Check API key/token |
| `FORBIDDEN` | 403 | Insufficient permissions | Check scopes |
| `NOT_FOUND` | 404 | Resource not found | Verify resource ID |
| `CONFLICT` | 409 | Resource conflict | Check for duplicates |
| `UNPROCESSABLE` | 422 | Validation failed | Check field values |
| `RATE_LIMITED` | 429 | Rate limit exceeded | Implement backoff |
| `SERVER_ERROR` | 500 | Internal server error | Retry or contact support |
| `SERVICE_UNAVAILABLE` | 503 | Service temporarily down | Retry with backoff |

### 12.3 Rate Limit Headers

| Header | Example | Description |
|--------|---------|-------------|
| `X-RateLimit-Limit` | `1000` | Request limit per window |
| `X-RateLimit-Remaining` | `999` | Requests remaining |
| `X-RateLimit-Reset` | `1738368000` | Reset timestamp (Unix) |
| `X-RateLimit-Window` | `60` | Window size (seconds) |
| `X-RateLimit-Tier` | `professional` | Current rate limit tier |
| `Retry-After` | `30` | Seconds to wait (on 429) |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | API Architect | Initial release |

---

*This document is proprietary to Smart Dairy Ltd. Unauthorized distribution is prohibited.*

**Smart Dairy Ltd.**
- Website: https://smartdairybd.com
- Developer Portal: https://developers.smartdairybd.com
- API Status: https://status.smartdairybd.com
- Support: api-support@smartdairybd.com

*© 2026 Smart Dairy Ltd. All rights reserved.*
