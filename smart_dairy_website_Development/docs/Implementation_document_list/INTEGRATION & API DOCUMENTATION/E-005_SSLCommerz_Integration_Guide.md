# SMART DAIRY LTD.
## SSLCOMMERZ INTEGRATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | E-005 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Prerequisites](#2-prerequisites)
3. [Integration Architecture](#3-integration-architecture)
4. [Payment Flow](#4-payment-flow)
5. [API Reference](#5-api-reference)
6. [Integration Implementation](#6-integration-implementation)
7. [Webhook Handling](#7-webhook-handling)
8. [Error Handling](#8-error-handling)
9. [Testing](#9-testing)
10. [Security](#10-security)
11. [Troubleshooting](#11-troubleshooting)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Overview

SSLCommerz is Bangladesh's leading payment aggregator, providing a unified payment gateway that supports multiple payment methods through a single integration. It enables Smart Dairy to accept payments from various channels including mobile financial services (MFS), cards, and internet banking.

**Key Features:**
- Single integration for multiple payment methods (bKash, Nagad, Rocket, Cards, Internet Banking)
- Hosted payment page for PCI DSS compliance
- Real-time transaction processing
- Comprehensive IPN (Instant Payment Notification) system
- Built-in fraud detection and risk management
- Multi-currency support (BDT primary)

### 1.2 Supported Payment Methods

| Payment Method | Type | Availability |
|---------------|------|-------------|
| **bKash** | MFS | Primary |
| **Nagad** | MFS | Primary |
| **Rocket** | MFS | Primary |
| **Visa** | Card | Primary |
| **MasterCard** | Card | Primary |
| **Amex** | Card | Secondary |
| **Internet Banking** | Bank | Secondary |
| **Mobile Banking** | Bank | Secondary |

### 1.3 Transaction Types

| Type | Code | Description |
|------|------|-------------|
| **SALE** | SALE | Immediate charge transaction |
| **CAPTURE** | CAPTURE | Capture authorized amount |
| **VOID** | VOID | Cancel authorized transaction |
| **REFUND** | REFUND | Partial or full refund |
| **QUERY** | QUERY | Transaction status inquiry |

### 1.4 Business Context

| Payment Scenario | Priority | Notes |
|-----------------|----------|-------|
| B2C Online Orders | Primary | All payment methods supported |
| B2B Bulk Payments | Primary | Higher limits with bank transfers |
| Subscription Payments | Secondary | Recurring via agreement |
| Refund Processing | Primary | Automated via API |

---

## 2. PREREQUISITES

### 2.1 Merchant Account Requirements

Before integration, the following must be completed:

| Requirement | Description | Timeline |
|-------------|-------------|----------|
| **Business Registration** | Valid trade license | 1-2 weeks |
| **Bank Account** | Corporate account for settlements | 1 week |
| **Website/Mobile App** | Live or staging environment | - |
| **Privacy Policy** | GDPR/Bangladesh compliance | - |
| **Terms of Service** | Payment terms documented | - |

### 2.2 SSLCommerz Credentials

Upon merchant account approval, SSLCommerz provides:

```python
# config/sslcommerz_config.py

from pydantic_settings import BaseSettings
from typing import Literal

class SSLCommerzConfig(BaseSettings):
    """SSLCommerz payment gateway configuration"""
    
    # Environment
    environment: Literal['sandbox', 'production'] = 'sandbox'
    
    # API Credentials (from SSLCommerz merchant portal)
    store_id: str
    store_password: str
    
    # API Endpoints
    @property
    def base_url(self) -> str:
        if self.environment == 'sandbox':
            return "https://sandbox.sslcommerz.com"
        return "https://securepay.sslcommerz.com"
    
    @property
    def validation_url(self) -> str:
        return f"{self.base_url}/validator/api/validationserverAPI.php"
    
    @property
    def payment_url(self) -> str:
        return f"{self.base_url}/gwprocess/v4/api.php"
    
    @property
    def refund_url(self) -> str:
        return f"{self.base_url}/validator/api/merchantTransIDvalidationAPI.php"
    
    @property
    def transaction_query_url(self) -> str:
        return f"{self.base_url}/validator/api/merchantTransIDvalidationAPI.php"
    
    # Transaction Settings
    currency: str = "BDT"
    min_transaction_amount: float = 10.0
    max_transaction_amount: float = 500000.0
    
    # Integration Settings
    success_url: str = "https://api.smartdairybd.com/payments/sslcommerz/success"
    fail_url: str = "https://api.smartdairybd.com/payments/sslcommerz/fail"
    cancel_url: str = "https://api.smartdairybd.com/payments/sslcommerz/cancel"
    ipn_url: str = "https://api.smartdairybd.com/webhooks/sslcommerz/ipn"
    
    # Risk Settings
    risk_level: str = "0"  # 0 = Normal, 1 = High
    
    # EMI Settings
    emi_option: int = 0  # 0 = No EMI, 1 = EMI enabled
    
    # Retry Settings
    max_retries: int = 3
    retry_delay_seconds: int = 5
    
    class Config:
        env_prefix = "SSLCOMMERZ_"

# Initialize config
sslcommerz_config = SSLCommerzConfig()
```

### 2.3 Environment Variables

```bash
# .env file (DO NOT COMMIT TO GIT)

# SSLCommerz Configuration
SSLCOMMERZ_ENVIRONMENT=sandbox
SSLCOMMERZ_STORE_ID=smartdairy_sandbox
SSLCOMMERZ_STORE_PASSWORD=sandbox_password_here

# URLs
SSLCOMMERZ_SUCCESS_URL=https://api.smartdairybd.com/payments/sslcommerz/success
SSLCOMMERZ_FAIL_URL=https://api.smartdairybd.com/payments/sslcommerz/fail
SSLCOMMERZ_CANCEL_URL=https://api.smartdairybd.com/payments/sslcommerz/cancel
SSLCOMMERZ_IPN_URL=https://api.smartdairybd.com/webhooks/sslcommerz/ipn
```

### 2.4 IP Whitelisting

For production, whitelist these IPs:

| Environment | IP Addresses |
|-------------|--------------|
| **Sandbox** | 103.9.185.10, 103.9.185.11 |
| **Production** | 103.9.185.20, 103.9.185.21, 103.9.185.22 |

---

## 3. INTEGRATION ARCHITECTURE

### 3.1 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      SSLCOMMERZ INTEGRATION ARCHITECTURE                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         PRESENTATION LAYER                             │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │   B2C Web    │  │  Mobile App  │  │   B2B Web    │               │  │
│  │  │   Checkout   │  │   (Flutter)  │  │   Portal     │               │  │
│  │  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘               │  │
│  └─────────┼─────────────────┼─────────────────┼─────────────────────────┘  │
│            │                 │                 │                            │
│            ▼                 ▼                 ▼                            │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         APPLICATION LAYER                              │  │
│  │  ┌─────────────────────────────────────────────────────────────────┐  │  │
│  │  │              FastAPI Payment Gateway Service                     │  │  │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐       │  │  │
│  │  │  │ Payment  │  │SSLCommerz│  │   IPN    │  │ Refund   │       │  │  │
│  │  │  │Controller│  │ Service  │  │ Handler  │  │ Service  │       │  │  │
│  │  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘       │  │  │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐                      │  │  │
│  │  │  │  Order   │  │ Payment  │  │ Transaction│                    │  │  │
│  │  │  │ Service  │  │Repository│  │  Logger    │                    │  │  │
│  │  │  └──────────┘  └──────────┘  └──────────┘                      │  │  │
│  │  └─────────────────────────────────────────────────────────────────┘  │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         EXTERNAL SERVICES                              │  │
│  │  ┌──────────────────┐        ┌──────────────────┐                   │  │
│  │  │  SSLCommerz      │        │  Smart Dairy     │                   │  │
│  │  │  Payment Gateway │◄──────►│  ERP (Odoo)      │                   │  │
│  │  └──────────────────┘        └──────────────────┘                   │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Integration Options

| Option | Description | Use Case |
|--------|-------------|----------|
| **Hosted Payment Page** | Redirect to SSLCommerz | Web checkout |
| **API Integration** | Direct API calls | Mobile apps |
| **iFrame Integration** | Embedded payment form | Seamless checkout |

### 3.3 Technology Stack

| Component | Technology | Version |
|-----------|------------|---------|
| **Backend Framework** | FastAPI | 0.109+ |
| **HTTP Client** | httpx | 0.26+ |
| **Database** | PostgreSQL | 16.x |
| **ORM** | SQLAlchemy | 2.0+ |
| **Cache** | Redis | 7.x |
| **Mobile** | Flutter | 3.x |

---

## 4. PAYMENT FLOW

### 4.1 Complete Payment Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      SSLCOMMERZ PAYMENT FLOW                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐          │
│  │  Smart   │────▶│  Smart   │────▶│SSLCommerz│────▶│  Bank/   │          │
│  │  Dairy   │     │  Dairy   │     │  Gateway │     │  MFS     │          │
│  │  Customer│     │  Backend │     │          │     │          │          │
│  └──────────┘     └──────────┘     └──────────┘     └──────────┘          │
│       │                │                │                │                  │
│       │ 1. Initiate    │                │                │                  │
│       │───────────────▶│                │                │                  │
│       │                │                │                │                  │
│       │                │ 2. Create      │                │                  │
│       │                │    Session     │                │                  │
│       │                │───────────────▶│                │                  │
│       │                │◀───────────────│                │                  │
│       │                │                │                │                  │
│       │ 3. Gateway URL │                │                │                  │
│       │◀───────────────│                │                │                  │
│       │                │                │                │                  │
│       │ 4. Redirect    │                │                │                  │
│       │─────────────────────────────────▶│                │                  │
│       │                │                │                │                  │
│       │ 5. Select & Pay│                │                │                  │
│       │───────────────────────────────────────────────▶│                  │
│       │                │                │                │                  │
│       │                │ 6. IPN Notify  │                │                  │
│       │                │◀───────────────│                │                  │
│       │                │                │                │                  │
│       │ 7. Validate    │                │                │                  │
│       │                │───────────────▶│                │                  │
│       │                │◀───────────────│                │                  │
│       │                │                │                │                  │
│       │ 8. Callback    │                │                │                  │
│       │◀─────────────────────────────────│                │                  │
│       │                │                │                │                  │
│       │ 9. Confirm     │                │                │                  │
│       │◀───────────────│                │                │                  │
│       │                │                │                │                  │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.2 Flow Description

| Step | Action | API/Endpoint |
|------|--------|--------------|
| 1 | Customer initiates checkout | `POST /payments/sslcommerz/initiate` |
| 2 | Backend creates session with SSLCommerz | `POST /gwprocess/v4/api.php` |
| 3 | SSLCommerz returns gateway URL | Response field: `GatewayPageURL` |
| 4 | Customer redirected to payment page | HTTP 302 Redirect |
| 5 | Customer selects payment method and pays | SSLCommerz hosted page |
| 6 | SSLCommerz sends IPN to backend | `POST /webhooks/sslcommerz/ipn` |
| 7 | Backend validates transaction | `GET /validator/api/validationserverAPI.php` |
| 8 | Customer redirected to success/fail URL | Browser redirect |
| 9 | Backend confirms order status | `POST /payments/sslcommerz/verify` |

---

## 5. API REFERENCE

### 5.1 Session Creation API

**Endpoint:** `POST /gwprocess/v4/api.php`

**Request Parameters:**

| Parameter | Required | Type | Description |
|-----------|----------|------|-------------|
| `store_id` | Yes | string | Merchant store ID |
| `store_passwd` | Yes | string | Store password |
| `total_amount` | Yes | decimal | Payment amount |
| `currency` | Yes | string | Currency code (BDT) |
| `tran_id` | Yes | string | Unique transaction ID |
| `success_url` | Yes | string | Success redirect URL |
| `fail_url` | Yes | string | Failure redirect URL |
| `cancel_url` | Yes | string | Cancellation URL |
| `ipn_url` | No | string | IPN webhook URL |
| `cus_name` | Yes | string | Customer name |
| `cus_email` | Yes | string | Customer email |
| `cus_add1` | Yes | string | Customer address |
| `cus_city` | Yes | string | Customer city |
| `cus_country` | Yes | string | Country code (BD) |
| `cus_phone` | Yes | string | Customer phone |
| `shipping_method` | No | string | Shipping method |
| `num_of_item` | No | int | Number of items |
| `product_name` | No | string | Product name(s) |
| `product_category` | No | string | Category |
| `product_profile` | No | string | Profile type |

**Response Fields:**

| Field | Type | Description |
|-------|------|-------------|
| `status` | string | SUCCESS / FAILED |
| `sessionkey` | string | Session identifier |
| `GatewayPageURL` | string | Payment page URL |
| `failedreason` | string | Error message if failed |

### 5.2 Order Validation API

**Endpoint:** `GET /validator/api/validationserverAPI.php`

**Request Parameters:**

| Parameter | Required | Description |
|-----------|----------|-------------|
| `val_id` | Yes | Validation ID from IPN |
| `store_id` | Yes | Store ID |
| `store_passwd` | Yes | Store password |
| `v` | No | API version (1) |
| `format` | No | Response format (json/xml) |

**Response Fields:**

| Field | Description |
|-------|-------------|
| `status` | VALID / VALIDATED / INVALID |
| `tran_id` | Transaction ID |
| `tran_date` | Transaction date |
| `val_id` | Validation ID |
| `amount` | Transaction amount |
| `store_amount` | Amount after fee |
| `card_type` | Payment method used |
| `card_no` | Masked card number |
| `card_issuer` | Card issuer bank |
| `card_brand` | Card brand |
| `card_issuer_country` | Issuer country |
| `currency` | Currency |
| `risk_level` | Risk assessment |
| `risk_title` | Risk description |

### 5.3 Transaction Query API

**Endpoint:** `GET /validator/api/merchantTransIDvalidationAPI.php`

**Request Parameters:**

| Parameter | Required | Description |
|-----------|----------|-------------|
| `store_id` | Yes | Store ID |
| `store_passwd` | Yes | Store password |
| `request_type` | Yes | JSON / XML |
| `tran_id` | Yes | Transaction ID |

### 5.4 Refund API

**Endpoint:** `GET /validator/api/merchantTransIDvalidationAPI.php`

**Request Parameters:**

| Parameter | Required | Description |
|-----------|----------|-------------|
| `store_id` | Yes | Store ID |
| `store_passwd` | Yes | Store password |
| `refund_amount` | Yes | Amount to refund |
| `refund_remarks` | Yes | Refund reason |
| `bank_tran_id` | Yes | Bank transaction ID |
| `refund_ref_id` | Yes | Reference ID |

---

## 6. INTEGRATION IMPLEMENTATION

### 6.1 SSLCommerz Service Implementation

```python
# services/sslcommerz_service.py

import httpx
import hashlib
import json
from datetime import datetime, timedelta
from typing import Optional, Dict, Any, Literal
from fastapi import HTTPException
import redis

class SSLCommerzService:
    """SSLCommerz payment gateway integration service"""
    
    def __init__(self, config: SSLCommerzConfig, redis_client: redis.Redis):
        self.config = config
        self.redis = redis_client
        self.http_client = httpx.AsyncClient(
            timeout=httpx.Timeout(60.0),
            headers={
                "Content-Type": "application/x-www-form-urlencoded",
                "Accept": "application/json",
            }
        )
    
    def _generate_transaction_id(self, order_id: str) -> str:
        """Generate unique transaction ID"""
        timestamp = datetime.utcnow().strftime("%Y%m%d%H%M%S")
        return f"SD{timestamp}{order_id[-6:]}"
    
    async def create_session(
        self,
        order_id: str,
        amount: float,
        customer_info: Dict[str, str],
        product_info: Optional[Dict[str, Any]] = None,
        shipping_info: Optional[Dict[str, str]] = None,
        emi_option: int = 0,
        allowed_bin: Optional[str] = None,
        multi_card_name: Optional[str] = None,
    ) -> Dict[str, Any]:
        """
        Create SSLCommerz payment session
        
        Args:
            order_id: Internal order identifier
            amount: Payment amount
            customer_info: Customer details (name, email, phone, address)
            product_info: Product details
            shipping_info: Shipping details
            emi_option: Enable EMI (0/1)
            allowed_bin: Allowed card BINs
            multi_card_name: Allowed payment methods
        """
        # Validate amount
        if not (self.config.min_transaction_amount <= amount <= self.config.max_transaction_amount):
            raise ValueError(
                f"Amount must be between {self.config.min_transaction_amount} "
                f"and {self.config.max_transaction_amount}"
            )
        
        # Generate transaction ID
        tran_id = self._generate_transaction_id(order_id)
        
        # Build payload
        payload = {
            # Store credentials
            "store_id": self.config.store_id,
            "store_passwd": self.config.store_password,
            
            # Transaction details
            "total_amount": f"{amount:.2f}",
            "currency": self.config.currency,
            "tran_id": tran_id,
            
            # Callback URLs
            "success_url": self.config.success_url,
            "fail_url": self.config.fail_url,
            "cancel_url": self.config.cancel_url,
            "ipn_url": self.config.ipn_url,
            
            # Customer info (required)
            "cus_name": customer_info.get("name", "Customer"),
            "cus_email": customer_info.get("email", "customer@example.com"),
            "cus_add1": customer_info.get("address", "Dhaka"),
            "cus_city": customer_info.get("city", "Dhaka"),
            "cus_country": customer_info.get("country", "Bangladesh"),
            "cus_phone": customer_info.get("phone", "01XXXXXXXXX"),
            
            # Shipping info (optional)
            "shipping_method": shipping_info.get("method", "NO") if shipping_info else "NO",
            "num_of_item": product_info.get("quantity", 1) if product_info else 1,
            
            # Product info
            "product_name": product_info.get("name", "Smart Dairy Products") if product_info else "Smart Dairy Products",
            "product_category": product_info.get("category", "Dairy") if product_info else "Dairy",
            "product_profile": product_info.get("profile", "general") if product_info else "general",
            
            # EMI settings
            "emi_option": emi_option,
            
            # Risk settings
            "risk_level": self.config.risk_level,
            
            # Additional customer info
            "cus_add2": customer_info.get("address2", ""),
            "cus_state": customer_info.get("state", ""),
            "cus_postcode": customer_info.get("postcode", ""),
            "cus_fax": customer_info.get("fax", ""),
        }
        
        # Add shipping details if provided
        if shipping_info:
            payload.update({
                "ship_name": shipping_info.get("name", ""),
                "ship_add1": shipping_info.get("address", ""),
                "ship_add2": shipping_info.get("address2", ""),
                "ship_city": shipping_info.get("city", ""),
                "ship_state": shipping_info.get("state", ""),
                "ship_postcode": shipping_info.get("postcode", ""),
                "ship_country": shipping_info.get("country", "Bangladesh"),
            })
        
        # Add allowed BIN if specified
        if allowed_bin:
            payload["allowed_bin"] = allowed_bin
        
        # Add multi card name if specified
        if multi_card_name:
            payload["multi_card_name"] = multi_card_name
        
        # Make API call
        response = await self.http_client.post(
            self.config.payment_url,
            data=payload
        )
        
        if response.status_code != 200:
            raise SSLCommerzAPIError(f"API call failed: {response.status_code}")
        
        result = response.json()
        
        if result.get("status") != "SUCCESS":
            raise SSLCommerzSessionError(
                message=result.get("failedreason", "Session creation failed"),
                response=result
            )
        
        return {
            "session_key": result.get("sessionkey"),
            "gateway_url": result.get("GatewayPageURL"),
            "transaction_id": tran_id,
            "amount": amount,
            "currency": self.config.currency,
            "internal_order_id": order_id,
            "status": "created",
        }
    
    async def validate_transaction(
        self,
        val_id: str,
        transaction_id: Optional[str] = None,
    ) -> Dict[str, Any]:
        """
        Validate transaction with SSLCommerz
        
        Args:
            val_id: Validation ID from IPN
            transaction_id: Original transaction ID for verification
        """
        params = {
            "val_id": val_id,
            "store_id": self.config.store_id,
            "store_passwd": self.config.store_password,
            "v": "1",
            "format": "json",
        }
        
        response = await self.http_client.get(
            self.config.validation_url,
            params=params
        )
        
        if response.status_code != 200:
            raise SSLCommerzAPIError(f"Validation failed: {response.status_code}")
        
        result = response.json()
        
        # Verify transaction ID matches
        if transaction_id and result.get("tran_id") != transaction_id:
            raise SSLCommerzSecurityError("Transaction ID mismatch")
        
        # Check validation status
        status = result.get("status")
        if status not in ["VALID", "VALIDATED"]:
            raise SSLCommerzValidationError(
                message=f"Invalid transaction status: {status}",
                response=result
            )
        
        return {
            "status": status,
            "transaction_id": result.get("tran_id"),
            "validation_id": result.get("val_id"),
            "amount": float(result.get("amount", 0)),
            "store_amount": float(result.get("store_amount", 0)),
            "currency": result.get("currency"),
            "payment_method": result.get("card_type"),
            "payment_gateway": result.get("card_issuer"),
            "card_brand": result.get("card_brand"),
            "bank_transaction_id": result.get("bank_tran_id"),
            "transaction_date": result.get("tran_date"),
            "risk_level": result.get("risk_level"),
            "risk_title": result.get("risk_title"),
            "val_id": result.get("val_id"),
            "validated_at": datetime.utcnow().isoformat(),
        }
    
    async def query_transaction(
        self,
        transaction_id: str,
    ) -> Dict[str, Any]:
        """
        Query transaction status
        
        Args:
            transaction_id: Transaction ID to query
        """
        params = {
            "store_id": self.config.store_id,
            "store_passwd": self.config.store_password,
            "request_type": "JSON",
            "tran_id": transaction_id,
        }
        
        response = await self.http_client.get(
            self.config.transaction_query_url,
            params=params
        )
        
        if response.status_code != 200:
            raise SSLCommerzAPIError(f"Query failed: {response.status_code}")
        
        result = response.json()
        
        if result.get("status") != "SUCCESS":
            raise SSLCommerzQueryError(
                message=result.get("reason", "Query failed"),
                response=result
            )
        
        # Parse element if present
        element = result.get("element", [])
        if element and len(element) > 0:
            tx_data = element[0]
            return {
                "transaction_id": tx_data.get("tran_id"),
                "status": tx_data.get("status"),
                "amount": float(tx_data.get("amount", 0)),
                "currency": tx_data.get("currency"),
                "payment_method": tx_data.get("card_type"),
                "transaction_date": tx_data.get("tran_date"),
            }
        
        return result
    
    async def refund_transaction(
        self,
        bank_tran_id: str,
        refund_amount: float,
        refund_ref_id: str,
        reason: str = "Customer request",
    ) -> Dict[str, Any]:
        """
        Process refund for a transaction
        
        Args:
            bank_tran_id: Bank transaction ID
            refund_amount: Amount to refund
            refund_ref_id: Unique refund reference
            reason: Refund reason
        """
        params = {
            "store_id": self.config.store_id,
            "store_passwd": self.config.store_password,
            "request_type": "JSON",
            "bank_tran_id": bank_tran_id,
            "refund_amount": f"{refund_amount:.2f}",
            "refund_remarks": reason[:255],  # Max 255 chars
            "refund_ref_id": refund_ref_id,
        }
        
        response = await self.http_client.get(
            self.config.refund_url,
            params=params
        )
        
        if response.status_code != 200:
            raise SSLCommerzAPIError(f"Refund failed: {response.status_code}")
        
        result = response.json()
        
        if result.get("status") != "SUCCESS":
            raise SSLCommerzRefundError(
                message=result.get("reason", "Refund failed"),
                response=result
            )
        
        return {
            "status": result.get("status"),
            "refund_ref_id": refund_ref_id,
            "bank_tran_id": bank_tran_id,
            "refund_amount": refund_amount,
            "refunded_at": datetime.utcnow().isoformat(),
        }
    
    def verify_hash(
        self,
        data: Dict[str, Any],
        verify_key: str,
    ) -> bool:
        """
        Verify IPN hash signature
        
        Args:
            data: IPN data received
            verify_key: Store password for verification
        """
        received_sign = data.get("verify_sign")
        received_sign_sha2 = data.get("verify_sign_sha2")
        
        if not received_sign and not received_sign_sha2:
            return False
        
        # Create sorted parameter string (excluding verify_sign fields)
        params = {k: v for k, v in data.items() if not k.startswith("verify_sign")}
        sorted_params = sorted(params.items())
        
        # Concatenate values
        value_string = verify_key
        for key, value in sorted_params:
            value_string += str(value)
        
        # Generate MD5 hash
        calculated_sign = hashlib.md5(value_string.encode()).hexdigest()
        
        # Compare
        return calculated_sign == received_sign


class SSLCommerzAPIError(Exception):
    """Base SSLCommerz API error"""
    pass


class SSLCommerzSessionError(SSLCommerzAPIError):
    """Session creation error"""
    def __init__(self, message: str, response: dict):
        super().__init__(message)
        self.response = response


class SSLCommerzValidationError(SSLCommerzAPIError):
    """Transaction validation error"""
    def __init__(self, message: str, response: dict):
        super().__init__(message)
        self.response = response


class SSLCommerzQueryError(SSLCommerzAPIError):
    """Transaction query error"""
    def __init__(self, message: str, response: dict):
        super().__init__(message)
        self.response = response


class SSLCommerzRefundError(SSLCommerzAPIError):
    """Refund processing error"""
    def __init__(self, message: str, response: dict):
        super().__init__(message)
        self.response = response


class SSLCommerzSecurityError(SSLCommerzAPIError):
    """Security validation error"""
    pass
```

### 6.2 Payment Controller

```python
# api/v1/payments/sslcommerz_controller.py

from fastapi import APIRouter, Depends, HTTPException, BackgroundTasks, Request
from sqlalchemy.ext.asyncio import AsyncSession
from pydantic import BaseModel, Field
from typing import Optional
from datetime import datetime, timedelta

router = APIRouter(prefix="/payments/sslcommerz", tags=["SSLCommerz Payments"])


class InitiatePaymentRequest(BaseModel):
    order_id: str
    customer_name: str
    customer_email: str
    customer_phone: str
    customer_address: str = "Dhaka"
    customer_city: str = "Dhaka"
    customer_country: str = "Bangladesh"
    emi_option: int = Field(0, ge=0, le=1)


class PaymentResponse(BaseModel):
    transaction_id: str
    gateway_url: str
    amount: float
    currency: str
    expires_at: datetime


@router.post("/initiate", response_model=PaymentResponse)
async def initiate_sslcommerz_payment(
    request: InitiatePaymentRequest,
    background_tasks: BackgroundTasks,
    db: AsyncSession = Depends(get_db),
    sslcommerz_service: SSLCommerzService = Depends(get_sslcommerz_service),
    current_user: User = Depends(get_current_user_optional),
):
    """Initiate SSLCommerz payment session"""
    
    # Validate order
    order = await OrderRepository.get_by_id(db, request.order_id)
    if not order:
        raise HTTPException(status_code=404, detail="Order not found")
    
    if order.status != OrderStatus.PENDING:
        raise HTTPException(status_code=400, detail="Order is not pending")
    
    # Check for existing pending payment
    existing = await PaymentRepository.get_pending_for_order(
        db, request.order_id, PaymentProvider.SSLCOMMERZ
    )
    
    if existing and existing.expires_at > datetime.utcnow():
        return PaymentResponse(
            transaction_id=existing.provider_transaction_id,
            gateway_url=existing.payment_url,
            amount=existing.amount,
            currency=existing.currency,
            expires_at=existing.expires_at,
        )
    
    try:
        # Create SSLCommerz session
        result = await sslcommerz_service.create_session(
            order_id=str(order.id),
            amount=float(order.total_amount),
            customer_info={
                "name": request.customer_name,
                "email": request.customer_email,
                "phone": request.customer_phone,
                "address": request.customer_address,
                "city": request.customer_city,
                "country": request.customer_country,
            },
            product_info={
                "name": f"Order #{order.order_number}",
                "category": "Dairy Products",
                "quantity": order.total_items,
            },
            emi_option=request.emi_option,
        )
        
        # Save payment record
        payment = Payment(
            order_id=order.id,
            user_id=current_user.id if current_user else None,
            provider=PaymentProvider.SSLCOMMERZ,
            provider_payment_id=result["session_key"],
            provider_transaction_id=result["transaction_id"],
            amount=result["amount"],
            currency=result["currency"],
            payment_url=result["gateway_url"],
            status=PaymentStatus.PENDING,
            expires_at=datetime.utcnow() + timedelta(hours=1),
        )
        
        await PaymentRepository.create(db, payment)
        
        # Schedule expiration check
        background_tasks.add_task(
            check_payment_expiration,
            payment.id,
            delay=3600
        )
        
        return PaymentResponse(
            transaction_id=result["transaction_id"],
            gateway_url=result["gateway_url"],
            amount=result["amount"],
            currency=result["currency"],
            expires_at=payment.expires_at,
        )
        
    except SSLCommerzSessionError as e:
        logger.error(f"SSLCommerz session creation failed: {e}")
        raise HTTPException(status_code=400, detail=str(e))


@router.post("/success")
async def sslcommerz_success_callback(
    request: Request,
    db: AsyncSession = Depends(get_db),
    sslcommerz_service: SSLCommerzService = Depends(get_sslcommerz_service),
):
    """Handle successful payment callback"""
    
    # Parse form data
    form_data = await request.form()
    data = dict(form_data)
    
    tran_id = data.get("tran_id")
    val_id = data.get("val_id")
    
    if not tran_id or not val_id:
        raise HTTPException(status_code=400, detail="Missing transaction data")
    
    # Get payment record
    payment = await PaymentRepository.get_by_transaction_id(db, tran_id)
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    try:
        # Validate transaction
        validation = await sslcommerz_service.validate_transaction(
            val_id=val_id,
            transaction_id=tran_id,
        )
        
        # Update payment record
        payment.status = PaymentStatus.COMPLETED
        payment.provider_validation_id = validation["validation_id"]
        payment.payment_method = validation["payment_method"]
        payment.bank_transaction_id = validation["bank_transaction_id"]
        payment.completed_at = datetime.utcnow()
        payment.metadata.update({
            "validation_response": validation,
            "risk_level": validation.get("risk_level"),
        })
        
        await PaymentRepository.update(db, payment)
        
        # Update order status
        await OrderRepository.update_status(db, payment.order_id, OrderStatus.PAID)
        
        # Process successful payment
        await process_successful_payment(payment)
        
        return {
            "status": "success",
            "transaction_id": tran_id,
            "amount": validation["amount"],
            "message": "Payment completed successfully",
        }
        
    except SSLCommerzValidationError as e:
        logger.error(f"Payment validation failed: {e}")
        raise HTTPException(status_code=400, detail="Payment validation failed")


@router.post("/fail")
async def sslcommerz_fail_callback(
    request: Request,
    db: AsyncSession = Depends(get_db),
):
    """Handle failed payment callback"""
    
    form_data = await request.form()
    data = dict(form_data)
    
    tran_id = data.get("tran_id")
    error_message = data.get("error", "Payment failed")
    
    if tran_id:
        payment = await PaymentRepository.get_by_transaction_id(db, tran_id)
        if payment:
            payment.status = PaymentStatus.FAILED
            payment.metadata["failure_reason"] = error_message
            await PaymentRepository.update(db, payment)
    
    return {
        "status": "failed",
        "transaction_id": tran_id,
        "message": error_message,
    }


@router.post("/cancel")
async def sslcommerz_cancel_callback(
    request: Request,
    db: AsyncSession = Depends(get_db),
):
    """Handle cancelled payment callback"""
    
    form_data = await request.form()
    data = dict(form_data)
    
    tran_id = data.get("tran_id")
    
    if tran_id:
        payment = await PaymentRepository.get_by_transaction_id(db, tran_id)
        if payment:
            payment.status = PaymentStatus.CANCELLED
            await PaymentRepository.update(db, payment)
    
    return {
        "status": "cancelled",
        "transaction_id": tran_id,
        "message": "Payment was cancelled by customer",
    }


@router.get("/status/{transaction_id}")
async def check_sslcommerz_status(
    transaction_id: str,
    db: AsyncSession = Depends(get_db),
    sslcommerz_service: SSLCommerzService = Depends(get_sslcommerz_service),
):
    """Check transaction status with SSLCommerz"""
    
    payment = await PaymentRepository.get_by_transaction_id(db, transaction_id)
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    try:
        result = await sslcommerz_service.query_transaction(transaction_id)
        
        return {
            "transaction_id": transaction_id,
            "internal_status": payment.status.value,
            "gateway_status": result.get("status"),
            "amount": result.get("amount"),
            "payment_method": result.get("payment_method"),
        }
        
    except SSLCommerzQueryError as e:
        raise HTTPException(status_code=400, detail=str(e))


@router.post("/refund")
async def refund_sslcommerz_payment(
    request: RefundRequest,
    db: AsyncSession = Depends(get_db),
    sslcommerz_service: SSLCommerzService = Depends(get_sslcommerz_service),
    current_user: User = Depends(get_current_user_admin),
):
    """Process refund for SSLCommerz payment"""
    
    payment = await PaymentRepository.get_by_id(db, request.payment_id)
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    if payment.provider != PaymentProvider.SSLCOMMERZ:
        raise HTTPException(status_code=400, detail="Not an SSLCommerz payment")
    
    if payment.status != PaymentStatus.COMPLETED:
        raise HTTPException(status_code=400, detail="Payment not completed")
    
    if not payment.bank_transaction_id:
        raise HTTPException(status_code=400, detail="Bank transaction ID not found")
    
    # Validate refund amount
    if request.amount > payment.amount:
        raise HTTPException(status_code=400, detail="Refund amount exceeds payment")
    
    # Generate refund reference
    refund_ref_id = f"REF{datetime.utcnow().strftime('%Y%m%d%H%M%S')}{payment.id[:6]}"
    
    try:
        result = await sslcommerz_service.refund_transaction(
            bank_tran_id=payment.bank_transaction_id,
            refund_amount=request.amount,
            refund_ref_id=refund_ref_id,
            reason=request.reason,
        )
        
        # Create refund record
        refund = Refund(
            payment_id=payment.id,
            order_id=payment.order_id,
            amount=request.amount,
            provider_refund_id=refund_ref_id,
            status=RefundStatus.COMPLETED,
            reason=request.reason,
            processed_by=current_user.id,
        )
        
        await RefundRepository.create(db, refund)
        
        # Update payment refund status
        payment.refunded_amount = (payment.refunded_amount or 0) + request.amount
        if payment.refunded_amount >= payment.amount:
            payment.status = PaymentStatus.REFUNDED
        else:
            payment.status = PaymentStatus.PARTIALLY_REFUNDED
        
        await PaymentRepository.update(db, payment)
        
        return {
            "status": "success",
            "refund_id": refund_ref_id,
            "amount": request.amount,
            "message": "Refund processed successfully",
        }
        
    except SSLCommerzRefundError as e:
        logger.error(f"Refund failed: {e}")
        raise HTTPException(status_code=400, detail=str(e))
```

### 6.3 Mobile Integration (Flutter)

```dart
// lib/services/sslcommerz_service.dart

import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:url_launcher/url_launcher.dart';
import 'package:webview_flutter/webview_flutter.dart';

class SSLCommerzService {
  final String baseUrl;
  final String authToken;

  SSLCommerzService({
    required this.baseUrl,
    required this.authToken,
  });

  Future<PaymentSession> initiatePayment({
    required String orderId,
    required String customerName,
    required String customerEmail,
    required String customerPhone,
    String customerAddress = 'Dhaka',
    String customerCity = 'Dhaka',
    String customerCountry = 'Bangladesh',
    int emiOption = 0,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/payments/sslcommerz/initiate'),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $authToken',
      },
      body: jsonEncode({
        'order_id': orderId,
        'customer_name': customerName,
        'customer_email': customerEmail,
        'customer_phone': customerPhone,
        'customer_address': customerAddress,
        'customer_city': customerCity,
        'customer_country': customerCountry,
        'emi_option': emiOption,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return PaymentSession.fromJson(data);
    } else {
      throw Exception('Failed to initiate payment: ${response.body}');
    }
  }

  Future<void> launchPaymentGateway(String gatewayUrl) async {
    // For mobile apps, use WebView or external browser
    final uri = Uri.parse(gatewayUrl);
    
    if (await canLaunchUrl(uri)) {
      await launchUrl(
        uri,
        mode: LaunchMode.externalApplication,
      );
    } else {
      throw Exception('Could not launch payment gateway');
    }
  }

  Future<PaymentStatus> checkStatus(String transactionId) async {
    final response = await http.get(
      Uri.parse('$baseUrl/payments/sslcommerz/status/$transactionId'),
      headers: {
        'Authorization': 'Bearer $authToken',
      },
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return PaymentStatus.fromJson(data);
    } else {
      throw Exception('Failed to check payment status');
    }
  }
}

// Payment Session Model
class PaymentSession {
  final String transactionId;
  final String gatewayUrl;
  final double amount;
  final String currency;
  final DateTime expiresAt;

  PaymentSession({
    required this.transactionId,
    required this.gatewayUrl,
    required this.amount,
    required this.currency,
    required this.expiresAt,
  });

  factory PaymentSession.fromJson(Map<String, dynamic> json) {
    return PaymentSession(
      transactionId: json['transaction_id'],
      gatewayUrl: json['gateway_url'],
      amount: json['amount'].toDouble(),
      currency: json['currency'],
      expiresAt: DateTime.parse(json['expires_at']),
    );
  }
}

// Payment Status Model
class PaymentStatus {
  final String transactionId;
  final String internalStatus;
  final String? gatewayStatus;
  final double? amount;
  final String? paymentMethod;

  PaymentStatus({
    required this.transactionId,
    required this.internalStatus,
    this.gatewayStatus,
    this.amount,
    this.paymentMethod,
  });

  factory PaymentStatus.fromJson(Map<String, dynamic> json) {
    return PaymentStatus(
      transactionId: json['transaction_id'],
      internalStatus: json['internal_status'],
      gatewayStatus: json['gateway_status'],
      amount: json['amount']?.toDouble(),
      paymentMethod: json['payment_method'],
    );
  }

  bool get isCompleted => internalStatus == 'completed';
  bool get isFailed => internalStatus == 'failed';
  bool get isPending => internalStatus == 'pending';
}

// WebView for in-app payment
class SSLCommerzWebView extends StatelessWidget {
  final String gatewayUrl;
  final VoidCallback onSuccess;
  final VoidCallback onFailure;
  final VoidCallback onCancel;

  const SSLCommerzWebView({
    Key? key,
    required this.gatewayUrl,
    required this.onSuccess,
    required this.onFailure,
    required this.onCancel,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return WebView(
      initialUrl: gatewayUrl,
      javascriptMode: JavascriptMode.unrestricted,
      navigationDelegate: (NavigationRequest request) {
        if (request.url.contains('/payments/sslcommerz/success')) {
          onSuccess();
          return NavigationDecision.prevent;
        } else if (request.url.contains('/payments/sslcommerz/fail')) {
          onFailure();
          return NavigationDecision.prevent;
        } else if (request.url.contains('/payments/sslcommerz/cancel')) {
          onCancel();
          return NavigationDecision.prevent;
        }
        return NavigationDecision.navigate;
      },
    );
  }
}
```

---

## 7. WEBHOOK HANDLING

### 7.1 IPN Handler Implementation

```python
# api/v1/webhooks/sslcommerz_webhook.py

from fastapi import APIRouter, Request, HTTPException, Header
from sqlalchemy.ext.asyncio import AsyncSession
import hashlib
import logging

router = APIRouter(prefix="/webhooks/sslcommerz", tags=["SSLCommerz Webhooks"])
logger = logging.getLogger(__name__)


@router.post("/ipn")
async def sslcommerz_ipn_handler(
    request: Request,
    db: AsyncSession = Depends(get_db),
    sslcommerz_service: SSLCommerzService = Depends(get_sslcommerz_service),
):
    """
    Handle SSLCommerz IPN (Instant Payment Notification)
    
    This endpoint receives real-time payment notifications from SSLCommerz.
    It validates the notification and updates the payment status.
    """
    
    # Parse form data
    form_data = await request.form()
    ipn_data = dict(form_data)
    
    logger.info(f"Received SSLCommerz IPN: {ipn_data.get('tran_id')}")
    
    # Verify IPN signature
    if not sslcommerz_service.verify_hash(ipn_data, sslcommerz_config.store_password):
        logger.warning("IPN hash verification failed")
        raise HTTPException(status_code=400, detail="Invalid signature")
    
    # Extract key fields
    tran_id = ipn_data.get("tran_id")
    val_id = ipn_data.get("val_id")
    status = ipn_data.get("status")
    
    if not tran_id or not val_id:
        logger.error("Missing required IPN fields")
        return {"status": "error", "message": "Missing fields"}
    
    # Get payment record
    payment = await PaymentRepository.get_by_transaction_id(db, tran_id)
    
    if not payment:
        logger.error(f"Payment not found for transaction: {tran_id}")
        return {"status": "received"}  # Acknowledge but don't retry
    
    # Skip if already processed
    if payment.status in [PaymentStatus.COMPLETED, PaymentStatus.REFUNDED]:
        logger.info(f"Payment already processed: {tran_id}")
        return {"status": "already_processed"}
    
    try:
        # Validate transaction with SSLCommerz
        validation = await sslcommerz_service.validate_transaction(
            val_id=val_id,
            transaction_id=tran_id,
        )
        
        if validation["status"] in ["VALID", "VALIDATED"]:
            # Update payment as completed
            payment.status = PaymentStatus.COMPLETED
            payment.provider_validation_id = validation["validation_id"]
            payment.payment_method = validation["payment_method"]
            payment.bank_transaction_id = validation["bank_transaction_id"]
            payment.completed_at = datetime.utcnow()
            payment.metadata.update({
                "ipn_data": ipn_data,
                "validation_response": validation,
            })
            
            await PaymentRepository.update(db, payment)
            
            # Update order status
            await OrderRepository.update_status(db, payment.order_id, OrderStatus.PAID)
            
            # Process order fulfillment
            await process_successful_payment(payment)
            
            # Send confirmation notifications
            await send_payment_confirmation_notifications(payment)
            
            logger.info(f"Payment completed via IPN: {tran_id}")
            
        else:
            logger.warning(f"Invalid validation status: {validation['status']}")
            
    except Exception as e:
        logger.error(f"IPN processing error: {e}")
        # Return 200 to prevent SSLCommerz from retrying
        # Log for manual investigation
    
    return {"status": "processed"}


@router.post("/ipn/test")
async def test_ipn_handler(
    request: Request,
    api_key: str = Header(None),
):
    """Test IPN endpoint for development"""
    
    # Verify test API key
    if api_key != settings.IPN_TEST_API_KEY:
        raise HTTPException(status_code=401, detail="Invalid API key")
    
    form_data = await request.form()
    data = dict(form_data)
    
    logger.info(f"Test IPN received: {json.dumps(data, indent=2)}")
    
    return {
        "status": "test_received",
        "data": data,
    }


async def process_successful_payment(payment: Payment):
    """Process order fulfillment after successful payment"""
    
    # Update inventory
    await InventoryService.reserve_stock(payment.order_id)
    
    # Create delivery order
    await DeliveryService.create_delivery_order(payment.order_id)
    
    # Update ERP (Odoo)
    await ERPService.create_sales_invoice(payment.order_id)
    
    # Send notifications
    await NotificationService.send_order_confirmation(payment.order_id)


async def send_payment_confirmation_notifications(payment: Payment):
    """Send confirmation notifications to customer"""
    
    order = await OrderRepository.get_by_id(payment.order_id)
    user = await UserRepository.get_by_id(payment.user_id) if payment.user_id else None
    
    if user:
        # Email notification
        await EmailService.send_payment_confirmation(
            to_email=user.email,
            order_number=order.order_number,
            amount=payment.amount,
            payment_method=payment.payment_method,
        )
        
        # SMS notification
        if user.phone:
            await SMSService.send_payment_confirmation(
                phone=user.phone,
                order_number=order.order_number,
                amount=payment.amount,
            )
        
        # Push notification
        await PushNotificationService.send(
            user_id=user.id,
            title="Payment Successful",
            body=f"Your payment of ৳{payment.amount} for order #{order.order_number} was successful.",
        )
```

### 7.2 IPN Retry Logic

```python
# services/ipn_retry_service.py

from celery import Celery
from celery.exceptions import MaxRetriesExceededError

app = Celery('sslcommerz_ipn')

@app.task(bind=True, max_retries=5)
def retry_ipn_validation(self, tran_id: str, val_id: str):
    """
    Retry IPN validation with exponential backoff
    """
    try:
        # Attempt validation
        validation = validate_transaction_sync(val_id, tran_id)
        
        if validation["status"] in ["VALID", "VALIDATED"]:
            process_validation_success(validation)
            return {"status": "success"}
        else:
            raise Exception(f"Invalid status: {validation['status']}")
            
    except Exception as exc:
        # Calculate backoff: 2^retry_count * 60 seconds
        countdown = 60 * (2 ** self.request.retries)
        
        try:
            self.retry(exc=exc, countdown=countdown)
        except MaxRetriesExceededError:
            # Log for manual intervention
            logger.error(f"Max retries exceeded for transaction: {tran_id}")
            alert_ops_team(tran_id, val_id, str(exc))
            return {"status": "max_retries_exceeded"}


def validate_transaction_sync(val_id: str, tran_id: str) -> Dict:
    """Synchronous wrapper for transaction validation"""
    import asyncio
    return asyncio.run(sslcommerz_service.validate_transaction(val_id, tran_id))
```

---

## 8. ERROR HANDLING

### 8.1 Common Error Codes

| Error Code | Description | Resolution |
|------------|-------------|------------|
| `1000` | Invalid Store ID | Check credentials |
| `1001` | Invalid Store Password | Check credentials |
| `1002` | Invalid Transaction ID | Ensure unique ID |
| `1003` | Amount too low | Minimum ৳10 |
| `1004` | Amount too high | Exceeds limit |
| `1005` | Currency not supported | Use BDT |
| `1006` | Invalid URL | Check callback URLs |
| `2000` | Session expired | Create new session |
| `2001` | Duplicate transaction ID | Generate new ID |
| `3000` | Validation failed | Retry validation |
| `4000` | Refund failed | Check transaction status |
| `9999` | System error | Contact support |

### 8.2 Error Handling Implementation

```python
# utils/sslcommerz_error_handler.py

from enum import Enum
from typing import Dict, Optional
import logging

logger = logging.getLogger(__name__)


class SSLCommerzErrorCode(Enum):
    INVALID_STORE_ID = "1000"
    INVALID_PASSWORD = "1001"
    INVALID_TRAN_ID = "1002"
    AMOUNT_TOO_LOW = "1003"
    AMOUNT_TOO_HIGH = "1004"
    INVALID_CURRENCY = "1005"
    INVALID_URL = "1006"
    SESSION_EXPIRED = "2000"
    DUPLICATE_TRAN_ID = "2001"
    VALIDATION_FAILED = "3000"
    REFUND_FAILED = "4000"
    SYSTEM_ERROR = "9999"


class SSLCommerzErrorHandler:
    """Handle SSLCommerz errors with appropriate responses"""
    
    ERROR_MESSAGES = {
        SSLCommerzErrorCode.INVALID_STORE_ID: {
            "message": "Invalid merchant credentials",
            "action": "Check store ID configuration",
            "retryable": False,
        },
        SSLCommerzErrorCode.INVALID_PASSWORD: {
            "message": "Invalid merchant credentials",
            "action": "Check store password configuration",
            "retryable": False,
        },
        SSLCommerzErrorCode.INVALID_TRAN_ID: {
            "message": "Invalid transaction ID format",
            "action": "Generate new transaction ID",
            "retryable": True,
        },
        SSLCommerzErrorCode.AMOUNT_TOO_LOW: {
            "message": "Transaction amount is below minimum",
            "action": "Amount must be at least ৳10",
            "retryable": False,
        },
        SSLCommerzErrorCode.AMOUNT_TOO_HIGH: {
            "message": "Transaction amount exceeds limit",
            "action": "Split into multiple transactions",
            "retryable": False,
        },
        SSLCommerzErrorCode.SESSION_EXPIRED: {
            "message": "Payment session has expired",
            "action": "Create new payment session",
            "retryable": True,
        },
        SSLCommerzErrorCode.DUPLICATE_TRAN_ID: {
            "message": "Transaction ID already exists",
            "action": "Generate unique transaction ID",
            "retryable": True,
        },
        SSLCommerzErrorCode.VALIDATION_FAILED: {
            "message": "Transaction validation failed",
            "action": "Retry with exponential backoff",
            "retryable": True,
        },
        SSLCommerzErrorCode.REFUND_FAILED: {
            "message": "Refund could not be processed",
            "action": "Verify transaction is completed",
            "retryable": True,
        },
        SSLCommerzErrorCode.SYSTEM_ERROR: {
            "message": "SSLCommerz system error",
            "action": "Contact SSLCommerz support",
            "retryable": True,
        },
    }
    
    @classmethod
    def handle_error(
        cls,
        error_code: str,
        error_message: Optional[str] = None,
        context: Optional[Dict] = None,
    ) -> Dict:
        """
        Handle SSLCommerz error and return standardized response
        """
        try:
            code_enum = SSLCommerzErrorCode(error_code)
        except ValueError:
            code_enum = SSLCommerzErrorCode.SYSTEM_ERROR
        
        error_info = cls.ERROR_MESSAGES.get(code_enum, {
            "message": "Unknown error",
            "action": "Contact support",
            "retryable": False,
        })
        
        response = {
            "error_code": error_code,
            "error_message": error_message or error_info["message"],
            "suggested_action": error_info["action"],
            "retryable": error_info["retryable"],
            "context": context or {},
        }
        
        # Log error
        logger.error(
            f"SSLCommerz Error [{error_code}]: {response['error_message']}",
            extra={"context": context}
        )
        
        return response
    
    @classmethod
    def should_retry(cls, error_code: str, retry_count: int, max_retries: int = 3) -> bool:
        """
        Determine if error should be retried
        """
        if retry_count >= max_retries:
            return False
        
        try:
            code_enum = SSLCommerzErrorCode(error_code)
            error_info = cls.ERROR_MESSAGES.get(code_enum, {})
            return error_info.get("retryable", False)
        except ValueError:
            return False


# Circuit breaker pattern for resilience
class CircuitBreaker:
    """Circuit breaker for SSLCommerz API calls"""
    
    def __init__(
        self,
        failure_threshold: int = 5,
        recovery_timeout: int = 60,
        expected_exception: type = SSLCommerzAPIError,
    ):
        self.failure_threshold = failure_threshold
        self.recovery_timeout = recovery_timeout
        self.expected_exception = expected_exception
        
        self.failure_count = 0
        self.last_failure_time = None
        self.state = "CLOSED"  # CLOSED, OPEN, HALF_OPEN
    
    def can_execute(self) -> bool:
        if self.state == "CLOSED":
            return True
        
        if self.state == "OPEN":
            if time.time() - self.last_failure_time > self.recovery_timeout:
                self.state = "HALF_OPEN"
                return True
            return False
        
        return True  # HALF_OPEN
    
    def record_success(self):
        self.failure_count = 0
        self.state = "CLOSED"
    
    def record_failure(self):
        self.failure_count += 1
        self.last_failure_time = time.time()
        
        if self.failure_count >= self.failure_threshold:
            self.state = "OPEN"
            logger.error("Circuit breaker opened for SSLCommerz API")
```

### 8.3 Refund Process

```python
# services/refund_service.py

class RefundService:
    """Handle refund processing with proper state management"""
    
    async def process_refund(
        self,
        payment_id: str,
        amount: float,
        reason: str,
        requested_by: str,
    ) -> Dict:
        """
        Process refund with validation and idempotency
        """
        
        # Check idempotency
        existing_refund = await RefundRepository.get_by_request(
            payment_id=payment_id,
            amount=amount,
            requested_by=requested_by,
        )
        
        if existing_refund:
            return {
                "status": "already_processed",
                "refund_id": existing_refund.provider_refund_id,
            }
        
        # Validate payment
        payment = await PaymentRepository.get_by_id(payment_id)
        
        if not payment:
            raise RefundError("Payment not found")
        
        if payment.status != PaymentStatus.COMPLETED:
            raise RefundError("Payment not completed")
        
        # Check refund window (e.g., 30 days)
        refund_window_days = 30
        if datetime.utcnow() - payment.completed_at > timedelta(days=refund_window_days):
            raise RefundError(f"Refund window expired ({refund_window_days} days)")
        
        # Check available amount
        available = payment.amount - (payment.refunded_amount or 0)
        if amount > available:
            raise RefundError(f"Refund amount exceeds available balance: ৳{available}")
        
        # Process refund
        refund_ref_id = f"REF{datetime.utcnow().strftime('%Y%m%d%H%M%S')}{payment_id[:6]}"
        
        try:
            result = await sslcommerz_service.refund_transaction(
                bank_tran_id=payment.bank_transaction_id,
                refund_amount=amount,
                refund_ref_id=refund_ref_id,
                reason=reason,
            )
            
            # Create refund record
            refund = Refund(
                payment_id=payment_id,
                order_id=payment.order_id,
                amount=amount,
                provider_refund_id=refund_ref_id,
                status=RefundStatus.COMPLETED,
                reason=reason,
                processed_by=requested_by,
                processed_at=datetime.utcnow(),
            )
            
            await RefundRepository.create(refund)
            
            # Update payment
            payment.refunded_amount = (payment.refunded_amount or 0) + amount
            
            if payment.refunded_amount >= payment.amount:
                payment.status = PaymentStatus.REFUNDED
            else:
                payment.status = PaymentStatus.PARTIALLY_REFUNDED
            
            await PaymentRepository.update(payment)
            
            # Update order if fully refunded
            if payment.status == PaymentStatus.REFUNDED:
                await OrderRepository.update_status(
                    payment.order_id,
                    OrderStatus.REFUNDED
                )
            
            return {
                "status": "success",
                "refund_id": refund_ref_id,
                "amount": amount,
            }
            
        except SSLCommerzRefundError as e:
            # Log and alert
            logger.error(f"Refund failed: {e}")
            await AlertService.send_refund_failure_alert(
                payment_id=payment_id,
                error=str(e),
            )
            raise RefundError(f"Gateway error: {e}")
```

---

## 9. TESTING

### 9.1 Sandbox Environment

| Environment | URL | Purpose |
|-------------|-----|---------|
| **Gateway** | `https://sandbox.sslcommerz.com` | Payment processing |
| **Validation** | `https://sandbox.sslcommerz.com/validator` | Transaction validation |
| **Refund** | `https://sandbox.sslcommerz.com/validator` | Refund processing |

### 9.2 Test Credentials

```python
# Test configuration for sandbox
SSLCOMMERZ_TEST_CONFIG = {
    "store_id": "smartdairy_sandbox",
    "store_password": "smartdairy_sandbox_pass",
    "base_url": "https://sandbox.sslcommerz.com",
}
```

### 9.3 Test Card Numbers

| Card Type | Number | Expiry | CVV | OTP |
|-----------|--------|--------|-----|-----|
| **Visa** | 4111111111111111 | 12/25 | 123 | 123456 |
| **MasterCard** | 5555555555554444 | 12/25 | 123 | 123456 |
| **Amex** | 378282246310005 | 12/25 | 1234 | 123456 |

### 9.4 Test MFS Accounts

| Provider | Account | PIN | OTP |
|----------|---------|-----|-----|
| **bKash** | 01929999999 | 1234 | 123456 |
| **Nagad** | 01928888888 | 1234 | 123456 |
| **Rocket** | 01927777777 | 1234 | 123456 |

### 9.5 Test Scenarios

| Scenario | Steps | Expected Result |
|----------|-------|-----------------|
| **Successful Payment** | Initiate → Pay → IPN → Verify | Order confirmed |
| **Failed Payment** | Initiate → Cancel | Order cancelled |
| **Insufficient Balance** | Initiate → Pay with low balance | Payment declined |
| **Invalid Card** | Use test card 4000000000000002 | Declined |
| **Timeout** | Wait > 10 minutes | Session expired |
| **Partial Refund** | Refund partial amount | Partial refund success |
| **Full Refund** | Refund full amount | Full refund success |
| **Duplicate ID** | Use same transaction ID | Error 2001 |
| **Invalid Amount** | Amount < ৳10 | Error 1003 |
| **IPN Validation** | Simulate IPN → Verify hash | Validated |

### 9.6 Integration Tests

```python
# tests/test_sslcommerz_integration.py

import pytest
from httpx import AsyncClient
from unittest.mock import patch, AsyncMock

@pytest.mark.asyncio
class TestSSLCommerzIntegration:
    
    async def test_payment_session_creation(self, client: AsyncClient):
        """Test payment session creation"""
        
        response = await client.post(
            "/payments/sslcommerz/initiate",
            json={
                "order_id": "order_123",
                "customer_name": "Test Customer",
                "customer_email": "test@example.com",
                "customer_phone": "01999999999",
            },
            headers={"Authorization": "Bearer test_token"},
        )
        
        assert response.status_code == 200
        data = response.json()
        assert "transaction_id" in data
        assert "gateway_url" in data
        assert data["currency"] == "BDT"
    
    async def test_success_callback(self, client: AsyncClient):
        """Test successful payment callback"""
        
        # Mock validation
        with patch.object(
            SSLCommerzService,
            "validate_transaction",
            return_value={
                "status": "VALID",
                "tran_id": "TEST123",
                "amount": 1000.00,
            }
        ):
            response = await client.post(
                "/payments/sslcommerz/success",
                data={
                    "tran_id": "TEST123",
                    "val_id": "VAL123456",
                },
            )
            
            assert response.status_code == 200
            data = response.json()
            assert data["status"] == "success"
    
    async def test_ipn_handler(self, client: AsyncClient):
        """Test IPN webhook handling"""
        
        ipn_data = {
            "tran_id": "TEST123",
            "val_id": "VAL123456",
            "status": "VALID",
            "amount": "1000.00",
            "verify_sign": "test_signature",
            "verify_sign_sha2": "test_signature_sha2",
        }
        
        with patch.object(
            SSLCommerzService,
            "verify_hash",
            return_value=True,
        ):
            response = await client.post(
                "/webhooks/sslcommerz/ipn",
                data=ipn_data,
            )
            
            assert response.status_code == 200
    
    async def test_refund(self, client: AsyncClient):
        """Test refund processing"""
        
        # Create completed payment first
        payment = await create_test_payment(status="completed")
        
        with patch.object(
            SSLCommerzService,
            "refund_transaction",
            return_value={"status": "SUCCESS"},
        ):
            response = await client.post(
                "/payments/sslcommerz/refund",
                json={
                    "payment_id": payment.id,
                    "amount": 500.00,
                    "reason": "Test refund",
                },
                headers={"Authorization": "Bearer admin_token"},
            )
            
            assert response.status_code == 200
            data = response.json()
            assert data["status"] == "success"
```

---

## 10. SECURITY

### 10.1 Hash Validation

```python
# utils/hash_validator.py

import hashlib
from typing import Dict

class SSLCommerzHashValidator:
    """Validate SSLCommerz IPN hash signatures"""
    
    @staticmethod
    def validate_ipn_hash(
        data: Dict[str, str],
        store_password: str,
    ) -> bool:
        """
        Validate IPN hash signature
        
        SSLCommerz generates hash by concatenating:
        store_passwd + all parameter values (sorted by key, excluding verify_sign)
        
        Args:
            data: IPN data received
            store_password: Store password for validation
            
        Returns:
            True if hash is valid
        """
        received_md5 = data.get("verify_sign")
        received_sha2 = data.get("verify_sign_sha2")
        
        if not received_md5 and not received_sha2:
            return False
        
        # Filter out verify_sign fields
        params = {
            k: v for k, v in data.items()
            if not k.startswith("verify_sign") and v is not None
        }
        
        # Sort by key
        sorted_params = sorted(params.items())
        
        # Build value string
        value_string = store_password
        for key, value in sorted_params:
            value_string += str(value)
        
        # Calculate hashes
        calculated_md5 = hashlib.md5(value_string.encode()).hexdigest()
        
        # Verify (prefer SHA2 if available)
        if received_sha2:
            calculated_sha2 = hashlib.sha256(value_string.encode()).hexdigest()
            return calculated_sha2 == received_sha2
        
        return calculated_md5 == received_md5
    
    @staticmethod
    def generate_hash(
        params: Dict[str, str],
        store_password: str,
        algorithm: str = "sha256",
    ) -> str:
        """
        Generate hash for API requests (if needed)
        
        Args:
            params: Request parameters
            store_password: Store password
            algorithm: Hash algorithm
            
        Returns:
            Generated hash
        """
        # Sort params
        sorted_params = sorted(params.items())
        
        # Build string
        value_string = store_password
        for key, value in sorted_params:
            value_string += str(value)
        
        # Generate hash
        if algorithm == "sha256":
            return hashlib.sha256(value_string.encode()).hexdigest()
        return hashlib.md5(value_string.encode()).hexdigest()
```

### 10.2 Store ID Validation

```python
# middleware/sslcommerz_security.py

from fastapi import Request, HTTPException
from typing import List
import ipaddress

class SSLCommerzSecurityMiddleware:
    """Security middleware for SSLCommerz endpoints"""
    
    # Whitelisted SSLCommerz IPs
    ALLOWED_IPS = [
        "103.9.185.10",
        "103.9.185.11",
        "103.9.185.20",
        "103.9.185.21",
        "103.9.185.22",
    ]
    
    async def __call__(self, request: Request, call_next):
        """
        Validate requests to SSLCommerz endpoints
        """
        path = request.url.path
        
        # Only apply to SSLCommerz endpoints
        if not path.startswith("/webhooks/sslcommerz"):
            return await call_next(request)
        
        # IP validation for IPN
        if path == "/webhooks/sslcommerz/ipn":
            client_ip = self._get_client_ip(request)
            
            if not self._is_ip_allowed(client_ip):
                logger.warning(f"Blocked IPN from unauthorized IP: {client_ip}")
                raise HTTPException(status_code=403, detail="Unauthorized IP")
        
        return await call_next(request)
    
    def _get_client_ip(self, request: Request) -> str:
        """Extract client IP from request"""
        
        # Check X-Forwarded-For
        forwarded = request.headers.get("X-Forwarded-For")
        if forwarded:
            return forwarded.split(",")[0].strip()
        
        # Check X-Real-IP
        real_ip = request.headers.get("X-Real-IP")
        if real_ip:
            return real_ip
        
        # Fall back to direct connection
        return request.client.host
    
    def _is_ip_allowed(self, ip: str) -> bool:
        """Check if IP is in whitelist"""
        return ip in self.ALLOWED_IPS
```

### 10.3 Data Encryption

```python
# utils/encryption.py

from cryptography.fernet import Fernet
from cryptography.hazmat.primitives import hashes
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2HMAC
import base64
import os

class PaymentDataEncryption:
    """Encrypt sensitive payment data at rest"""
    
    def __init__(self, master_key: str):
        self.cipher = self._create_cipher(master_key)
    
    def _create_cipher(self, master_key: str) -> Fernet:
        """Create Fernet cipher from master key"""
        kdf = PBKDF2HMAC(
            algorithm=hashes.SHA256(),
            length=32,
            salt=os.urandom(16),
            iterations=480000,
        )
        key = base64.urlsafe_b64encode(kdf.derive(master_key.encode()))
        return Fernet(key)
    
    def encrypt(self, data: str) -> str:
        """Encrypt sensitive data"""
        return self.cipher.encrypt(data.encode()).decode()
    
    def decrypt(self, encrypted: str) -> str:
        """Decrypt sensitive data"""
        return self.cipher.decrypt(encrypted.encode()).decode()
    
    def encrypt_card_data(self, card_number: str) -> Dict:
        """
        Encrypt card data with masking
        
        Returns encrypted data and masked display
        """
        masked = f"****-****-****-{card_number[-4:]}"
        encrypted = self.encrypt(card_number)
        
        return {
            "encrypted": encrypted,
            "masked": masked,
            "last_four": card_number[-4:],
        }
```

### 10.4 Security Checklist

| Check | Implementation | Status |
|-------|---------------|--------|
| HTTPS Only | All endpoints use TLS 1.3 | Required |
| Hash Validation | Verify all IPN signatures | Required |
| IP Whitelisting | Only allow SSLCommerz IPs for IPN | Required |
| Credential Storage | Environment variables / Secrets manager | Required |
| Data Encryption | Encrypt sensitive data at rest | Required |
| Input Validation | Validate all input parameters | Required |
| Rate Limiting | Limit payment attempts | Recommended |
| Audit Logging | Log all payment operations | Required |
| PII Protection | Mask sensitive customer data | Required |

---

## 11. TROUBLESHOOTING

### 11.1 Common Issues

| Issue | Symptoms | Cause | Solution |
|-------|----------|-------|----------|
| **Session Creation Failed** | Error 1000/1001 | Invalid credentials | Check store_id and store_password |
| **Duplicate Transaction ID** | Error 2001 | Reused tran_id | Generate unique IDs with timestamp |
| **IPN Not Received** | Order stuck pending | Firewall/block | Whitelist SSLCommerz IPs |
| **Hash Mismatch** | IPN rejected | Wrong password | Verify store_password |
| **Timeout** | Connection error | Network issue | Increase timeout, implement retry |
| **Refund Failed** | Error 4000 | Transaction not settled | Wait 24 hours for settlement |
| **Invalid Amount** | Error 1003/1004 | Out of range | Validate amount before API call |
| **Currency Error** | Error 1005 | Wrong currency | Use BDT only |

### 11.2 Debug Mode

```python
# config/debug.py

class SSLCommerzDebug:
    """Debug utilities for SSLCommerz integration"""
    
    @staticmethod
    def log_request(payload: Dict, endpoint: str):
        """Log API request (sanitized)"""
        
        # Mask sensitive fields
        sanitized = {
            k: "***" if k in ["store_passwd", "store_password"] else v
            for k, v in payload.items()
        }
        
        logger.debug(f"SSLCommerz Request to {endpoint}: {sanitized}")
    
    @staticmethod
    def log_response(response: Dict, endpoint: str):
        """Log API response"""
        
        logger.debug(f"SSLCommerz Response from {endpoint}: {response}")
    
    @staticmethod
    def validate_configuration(config: SSLCommerzConfig) -> List[str]:
        """Validate configuration and return issues"""
        
        issues = []
        
        if not config.store_id:
            issues.append("store_id is missing")
        
        if not config.store_password:
            issues.append("store_password is missing")
        
        if config.store_id == "test":
            issues.append("Using default test store_id")
        
        if "http:" in config.success_url:
            issues.append("Using HTTP instead of HTTPS for success_url")
        
        if config.max_transaction_amount > 500000:
            issues.append("max_transaction_amount exceeds SSLCommerz limit")
        
        return issues
```

### 11.3 Monitoring and Alerts

```python
# monitoring/sslcommerz_alerts.py

from datadog import statsd
import sentry_sdk

class SSLCommerzMonitor:
    """Monitor SSLCommerz integration health"""
    
    def record_payment_attempt(self, payment_method: str):
        """Record payment attempt metric"""
        statsd.increment(
            "sslcommerz.payment.attempt",
            tags=[f"method:{payment_method}"]
        )
    
    def record_payment_success(self, payment_method: str, amount: float):
        """Record successful payment"""
        statsd.increment(
            "sslcommerz.payment.success",
            tags=[f"method:{payment_method}"]
        )
        statsd.histogram(
            "sslcommerz.payment.amount",
            amount,
            tags=[f"method:{payment_method}"]
        )
    
    def record_payment_failure(self, error_code: str, payment_method: str):
        """Record payment failure"""
        statsd.increment(
            "sslcommerz.payment.failure",
            tags=[f"error:{error_code}", f"method:{payment_method}"]
        )
    
    def record_refund(self, amount: float):
        """Record refund"""
        statsd.increment("sslcommerz.refund")
        statsd.histogram("sslcommerz.refund.amount", amount)
    
    def alert_high_failure_rate(self, failure_rate: float):
        """Alert if failure rate exceeds threshold"""
        
        if failure_rate > 0.1:  # 10% threshold
            sentry_sdk.capture_message(
                f"High SSLCommerz failure rate: {failure_rate:.2%}",
                level="warning"
            )
```

---

## 12. APPENDICES

### Appendix A: Complete Integration Code

```python
# Complete FastAPI application for SSLCommerz integration

from fastapi import FastAPI, Depends, HTTPException, Request, BackgroundTasks
from fastapi.middleware.cors import CORSMiddleware
from sqlalchemy.ext.asyncio import AsyncSession
from contextlib import asynccontextmanager
import redis.asyncio as redis

# Database setup
from database import get_db, init_db

# Services
from services.sslcommerz_service import SSLCommerzService, SSLCommerzConfig

# Configuration
sslcommerz_config = SSLCommerzConfig()

# Redis client
redis_client = redis.Redis(
    host="localhost",
    port=6379,
    db=0,
    decode_responses=True,
)

# SSLCommerz service instance
sslcommerz_service = SSLCommerzService(sslcommerz_config, redis_client)

@asynccontextmanager
async def lifespan(app: FastAPI):
    """Application lifespan handler"""
    await init_db()
    yield
    await redis_client.close()

app = FastAPI(
    title="Smart Dairy Payment API",
    description="SSLCommerz Payment Integration",
    version="1.0.0",
    lifespan=lifespan,
)

# CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Dependency injection
def get_sslcommerz_service():
    return sslcommerz_service

# Include routers
from api.v1.payments import sslcommerz_controller
from api.v1.webhooks import sslcommerz_webhook

app.include_router(sslcommerz_controller.router, prefix="/api/v1")
app.include_router(sslcommerz_webhook.router, prefix="/api/v1")

@app.get("/health")
async def health_check():
    """Health check endpoint"""
    return {
        "status": "healthy",
        "service": "sslcommerz-integration",
        "version": "1.0.0",
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
```

### Appendix B: Database Schema

```sql
-- Payments table
CREATE TABLE payments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id UUID NOT NULL REFERENCES orders(id),
    user_id UUID REFERENCES users(id),
    
    -- Provider info
    provider VARCHAR(50) NOT NULL,
    provider_payment_id VARCHAR(255),
    provider_transaction_id VARCHAR(255),
    provider_validation_id VARCHAR(255),
    
    -- Payment details
    amount DECIMAL(12, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'BDT',
    payment_method VARCHAR(50),
    bank_transaction_id VARCHAR(255),
    
    -- Status
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    refunded_amount DECIMAL(12, 2) DEFAULT 0,
    
    -- URLs
    payment_url TEXT,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    completed_at TIMESTAMP,
    
    -- Metadata
    metadata JSONB DEFAULT '{}',
    challenge TEXT,
    customer_phone VARCHAR(20),
    
    -- Indexes
    CONSTRAINT valid_status CHECK (status IN (
        'pending', 'completed', 'failed', 'cancelled',
        'expired', 'refunded', 'partially_refunded'
    ))
);

CREATE INDEX idx_payments_order ON payments(order_id);
CREATE INDEX idx_payments_transaction ON payments(provider_transaction_id);
CREATE INDEX idx_payments_status ON payments(status);

-- Refunds table
CREATE TABLE refunds (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    payment_id UUID NOT NULL REFERENCES payments(id),
    order_id UUID NOT NULL REFERENCES orders(id),
    
    amount DECIMAL(12, 2) NOT NULL,
    provider_refund_id VARCHAR(255),
    status VARCHAR(50) NOT NULL,
    reason TEXT,
    
    processed_by UUID REFERENCES users(id),
    processed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    metadata JSONB DEFAULT '{}'
);

CREATE INDEX idx_refunds_payment ON refunds(payment_id);
```

### Appendix C: Test Credentials Summary

| Credential | Sandbox Value | Production Value |
|------------|---------------|------------------|
| **Store ID** | smartdairy_sandbox | Provided by SSLCommerz |
| **Store Password** | smartdairy_sandbox_pass | Provided by SSLCommerz |
| **Test bKash** | 01929999999 / 1234 | - |
| **Test Nagad** | 01928888888 / 1234 | - |
| **Test Rocket** | 01927777777 / 1234 | - |
| **Test Visa** | 4111111111111111 | - |
| **Test MasterCard** | 5555555555554444 | - |

### Appendix D: Environment Configuration

```bash
# .env.example

# SSLCommerz Settings
SSLCOMMERZ_ENVIRONMENT=sandbox
SSLCOMMERZ_STORE_ID=your_store_id
SSLCOMMERZ_STORE_PASSWORD=your_store_password

# Callback URLs
SSLCOMMERZ_SUCCESS_URL=https://api.smartdairybd.com/api/v1/payments/sslcommerz/success
SSLCOMMERZ_FAIL_URL=https://api.smartdairybd.com/api/v1/payments/sslcommerz/fail
SSLCOMMERZ_CANCEL_URL=https://api.smartdairybd.com/api/v1/payments/sslcommerz/cancel
SSLCOMMERZ_IPN_URL=https://api.smartdairybd.com/api/v1/webhooks/sslcommerz/ipn

# Database
DATABASE_URL=postgresql://user:pass@localhost/smartdairy

# Redis
REDIS_URL=redis://localhost:6379/0

# Encryption
PAYMENT_ENCRYPTION_KEY=your_32_byte_encryption_key

# Monitoring
SENTRY_DSN=your_sentry_dsn
DATADOG_API_KEY=your_datadog_key
```

### Appendix E: API Endpoints Summary

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| POST | `/payments/sslcommerz/initiate` | Create payment session | User |
| POST | `/payments/sslcommerz/success` | Success callback | None |
| POST | `/payments/sslcommerz/fail` | Failure callback | None |
| POST | `/payments/sslcommerz/cancel` | Cancel callback | None |
| GET | `/payments/sslcommerz/status/{id}` | Check status | User |
| POST | `/payments/sslcommerz/refund` | Process refund | Admin |
| POST | `/webhooks/sslcommerz/ipn` | IPN webhook | IP Whitelist |

---

**Document Control:**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Integration Engineer | Initial release |

---

*This document is proprietary to Smart Dairy Ltd. Unauthorized distribution is prohibited.*
