# SMART DAIRY LTD.
## API SPECIFICATION DOCUMENT
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | E-001 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Tech Lead |

---

## 1. API ARCHITECTURE

### 1.1 API Gateway Overview

```
┌─────────────────────────────────────────────────────────────┐
│                       API GATEWAY                            │
│                     (FastAPI + Nginx)                        │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │   REST API  │  │  GraphQL    │  │     Webhooks        │  │
│  │   /api/v1   │  │  /graphql   │  │    /webhooks        │  │
│  └──────┬──────┘  └──────┬──────┘  └──────────┬──────────┘  │
│         │                │                     │             │
│         └────────────────┴─────────────────────┘             │
│                          │                                   │
│         ┌────────────────┴────────────────┐                  │
│         ▼                                 ▼                  │
│  ┌──────────────┐               ┌──────────────────┐        │
│  │ Auth Layer   │               │ Rate Limiting    │        │
│  │ (JWT/OAuth2) │               │ (Redis)          │        │
│  └──────────────┘               └──────────────────┘        │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### 1.2 Base URL

| Environment | Base URL |
|-------------|----------|
| Production | `https://api.smartdairybd.com/v1` |
| Staging | `https://api-staging.smartdairybd.com/v1` |
| Development | `https://api-dev.smartdairybd.com/v1` |

---

## 2. AUTHENTICATION

### 2.1 OAuth 2.0 Flow

```http
# Request Token
POST /auth/token
Content-Type: application/x-www-form-urlencoded

grant_type=password&
username=user@example.com&
password=securepassword&
client_id=smart-dairy-mobile&
client_secret=client_secret_here

# Response
{
    "access_token": "eyJhbGciOiJIUzI1NiIs...",
    "token_type": "bearer",
    "expires_in": 1800,
    "refresh_token": "dGhpcyBpcyBhIHJlZnJlc2g..."
}
```

### 2.2 Using Tokens

```http
GET /api/v1/animals
Authorization: Bearer eyJhbGciOiJIUzI1NiIs...
Content-Type: application/json
```

---

## 3. CORE API ENDPOINTS

### 3.1 Farm Management API

#### Animals

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/farm/animals` | List all animals |
| GET | `/farm/animals/{id}` | Get animal by ID |
| POST | `/farm/animals` | Create new animal |
| PUT | `/farm/animals/{id}` | Update animal |
| DELETE | `/farm/animals/{id}` | Delete animal |
| GET | `/farm/animals/{id}/production` | Get milk production history |
| GET | `/farm/animals/{id}/health` | Get health records |

**Request/Response Examples:**

```http
POST /api/v1/farm/animals
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "SD001",
    "rfid_tag": "E200341502001080",
    "species": "cattle",
    "breed_id": 1,
    "gender": "female",
    "birth_date": "2020-03-15",
    "barn_id": 2
}

# Response 201 Created
{
    "id": 123,
    "name": "SD001",
    "rfid_tag": "E200341502001080",
    "species": "cattle",
    "breed": {
        "id": 1,
        "name": "Holstein Friesian"
    },
    "gender": "female",
    "birth_date": "2020-03-15",
    "status": "lactating",
    "age_months": 47,
    "daily_milk_avg": 22.5,
    "created_at": "2024-01-31T10:30:00Z"
}
```

#### Milk Production

```http
POST /api/v1/farm/milk-production
Authorization: Bearer {token}
Content-Type: application/json

{
    "animal_id": 123,
    "session": "morning",
    "quantity_liters": 18.5,
    "fat_percentage": 4.2,
    "snf_percentage": 8.5,
    "production_date": "2024-01-31"
}

# Response 201 Created
{
    "id": 456,
    "animal_id": 123,
    "animal_name": "SD001",
    "session": "morning",
    "quantity_liters": 18.5,
    "fat_percentage": 4.2,
    "snf_percentage": 8.5,
    "total_solids": 12.7,
    "production_date": "2024-01-31",
    "recorded_by": {
        "id": 10,
        "name": "Rahim Ahmed"
    },
    "created_at": "2024-01-31T08:30:00Z"
}
```

### 3.2 Sales API

#### Orders

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/sales/orders` | List orders |
| GET | `/sales/orders/{id}` | Get order details |
| POST | `/sales/orders` | Create order |
| PUT | `/sales/orders/{id}` | Update order |
| POST | `/sales/orders/{id}/confirm` | Confirm order |
| POST | `/sales/orders/{id}/cancel` | Cancel order |

```http
POST /api/v1/sales/orders
Authorization: Bearer {token}
Content-Type: application/json

{
    "partner_id": 456,
    "order_type": "b2c",
    "order_lines": [
        {
            "product_id": 100,
            "quantity": 5,
            "price_unit": 120.00
        },
        {
            "product_id": 101,
            "quantity": 2,
            "price_unit": 250.00
        }
    ],
    "delivery_address_id": 789
}

# Response 201 Created
{
    "id": 10001,
    "name": "SO/2024/0001",
    "partner": {
        "id": 456,
        "name": "Tahmina Islam",
        "phone": "+8801712345678"
    },
    "order_date": "2024-01-31",
    "state": "draft",
    "order_type": "b2c",
    "amount_untaxed": 1100.00,
    "amount_tax": 110.00,
    "amount_total": 1210.00,
    "currency": "BDT",
    "order_lines": [
        {
            "id": 1,
            "product": {
                "id": 100,
                "name": "Saffron Organic Milk 1L"
            },
            "quantity": 5,
            "price_unit": 120.00,
            "price_subtotal": 600.00
        }
    ],
    "created_at": "2024-01-31T14:30:00Z"
}
```

### 3.3 Mobile API

#### Sync Endpoints

```http
# Get data for offline sync
GET /api/v1/mobile/sync/farm-data
Authorization: Bearer {token}

# Response
{
    "animals": [...],
    "breeds": [...],
    "barns": [...],
    "last_sync": "2024-01-31T00:00:00Z"
}

# Post offline data
POST /api/v1/mobile/sync/upload
Authorization: Bearer {token}
Content-Type: application/json

{
    "milk_records": [...],
    "health_records": [...],
    "device_timestamp": "2024-01-31T08:00:00Z"
}
```

### 3.4 Payment API

```http
POST /api/v1/payments/create
Authorization: Bearer {token}
Content-Type: application/json

{
    "order_id": 10001,
    "amount": 1210.00,
    "currency": "BDT",
    "provider": "bkash",
    "callback_url": "https://smartdairybd.com/payment/callback"
}

# Response
{
    "payment_id": "PAY123456",
    "provider": "bkash",
    "amount": 1210.00,
    "currency": "BDT",
    "status": "pending",
    "checkout_url": "https://checkout.bka.sh/...",
    "expires_at": "2024-01-31T15:00:00Z"
}
```

---

## 4. ERROR HANDLING

### 4.1 Error Response Format

```json
{
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "Invalid input data",
        "details": [
            {
                "field": "quantity_liters",
                "message": "Must be greater than 0"
            }
        ],
        "request_id": "req_123456789"
    }
}
```

### 4.2 HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 409 | Conflict |
| 422 | Unprocessable Entity |
| 429 | Too Many Requests |
| 500 | Internal Server Error |

---

## 5. RATE LIMITING

```http
# Response Headers
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1640995200

# Exceeded
429 Too Many Requests
Retry-After: 60
```

---

**END OF API SPECIFICATION DOCUMENT**
