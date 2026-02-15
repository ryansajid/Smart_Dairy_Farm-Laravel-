# SMART DAIRY LTD.
## bKASH INTEGRATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | E-002 |
| **Version** | 1.0 |
| **Date** | March 28, 2026 |
| **Author** | Backend Developer |
| **Owner** | Backend Developer |
| **Reviewer** | Tech Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [bKash Overview](#2-bkash-overview)
3. [Integration Architecture](#3-integration-architecture)
4. [Merchant Account Setup](#4-merchant-account-setup)
5. [API Implementation](#5-api-implementation)
6. [Checkout Flow](#6-checkout-flow)
7. [Webhook Handling](#7-webhook-handling)
8. [Refund and Dispute](#8-refund-and-dispute)
9. [Security Implementation](#9-security-implementation)
10. [Testing Strategy](#10-testing-strategy)
11. [Error Handling](#11-error-handling)
12. [Production Deployment](#12-production-deployment)
13. [Appendices](#13-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive technical guidelines for integrating bKash payment gateway into the Smart Dairy e-commerce platform. It covers the complete implementation of bKash Tokenized Checkout API for secure, seamless payment processing.

### 1.2 Scope

The integration covers:
- **Tokenized Checkout** - Secure payment flow with tokenization
- **Payment Creation** - Order-to-payment mapping
- **Transaction Execution** - Customer payment completion
- **Query Operations** - Payment status verification
- **Refund Processing** - Partial and full refunds
- **Webhook Handling** - Real-time payment notifications

### 1.3 Business Context

| Payment Scenario | Implementation Priority |
|-----------------|------------------------|
| B2C Online Orders | Primary |
| B2B Credit Payments | Secondary |
| Subscription Auto-renewal | Future Phase |
| Refund Processing | Primary |

---

## 2. bKASH OVERVIEW

### 2.1 bKash Tokenized Checkout

bKash Tokenized Checkout is a secure payment solution that allows customers to pay using their bKash account without sharing sensitive credentials with merchants.

**Key Features:**
- PCI DSS compliant tokenization
- No sensitive data stored on merchant servers
- Support for all bKash account types (Agent, Merchant, Customer)
- Real-time transaction status updates

### 2.2 API Environments

| Environment | Base URL | Purpose |
|-------------|----------|---------|
| **Sandbox** | `https://tokenized.sandbox.bka.sh` | Development & testing |
| **Production** | `https://tokenized.bka.sh` | Live transactions |

### 2.3 Transaction Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         bKASH PAYMENT FLOW                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐          │
│  │  Smart   │────▶│  Smart   │────▶│  bKash   │────▶│  bKash   │          │
│  │  Dairy   │     │  Dairy   │     │  Server  │     │  Payment │          │
│  │  Customer│     │  Backend │     │          │     │  Gateway │          │
│  └──────────┘     └──────────┘     └──────────┘     └──────────┘          │
│       │                │                │                │                  │
│       │ 1. Checkout    │                │                │                  │
│       │───────────────▶│                │                │                  │
│       │                │                │                │                  │
│       │                │ 2. Grant Token │                │                  │
│       │                │───────────────▶│                │                  │
│       │                │◀───────────────│                │                  │
│       │                │                │                │                  │
│       │                │ 3. Create Pay  │                │                  │
│       │                │───────────────▶│                │                  │
│       │                │◀───────────────│                │                  │
│       │                │                │                │                  │
│       │ 4. Payment URL │                │                │                  │
│       │◀───────────────│                │                │                  │
│       │                │                │                │                  │
│       │ 5. Authorize   │                │                │                  │
│       │─────────────────────────────────────────────────▶│                  │
│       │                │                │                │                  │
│       │ 6. Complete    │                │                │                  │
│       │◀─────────────────────────────────────────────────│                  │
│       │                │                │                │                  │
│       │                │ 7. Execute Pay │                │                  │
│       │                │───────────────▶│                │                  │
│       │                │◀───────────────│                │                  │
│       │                │                │                │                  │
│       │                │ 8. Query Pay   │                │                  │
│       │                │───────────────▶│                │                  │
│       │                │◀───────────────│                │                  │
│       │                │                │                │                  │
│       │ 9. Confirm     │                │                │                  │
│       │◀───────────────│                │                │                  │
│       │                │                │                │                  │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. INTEGRATION ARCHITECTURE

### 3.1 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         bKASH INTEGRATION ARCHITECTURE                       │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         PRESENTATION LAYER                             │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │ B2C Checkout │  │ B2B Portal   │  │ Mobile App   │               │  │
│  │  │ UI           │  │ Payment      │  │ Payment      │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         APPLICATION LAYER                              │  │
│  │  ┌─────────────────────────────────────────────────────────────────┐  │  │
│  │  │              FastAPI Payment Gateway Service                     │  │  │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐       │  │  │
│  │  │  │ Payment  │  │ bKash    │  │ Webhook  │  │ Refund   │       │  │  │
│  │  │  │ Controller│ │ Service  │  │ Handler  │  │ Service  │       │  │  │
│  │  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘       │  │  │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐                      │  │  │
│  │  │  │ Order    │  │ Payment  │  │ Transaction│                    │  │  │
│  │  │  │ Service  │  │ Repository│ │ Logger    │                    │  │  │
│  │  │  └──────────┘  └──────────┘  └──────────┘                      │  │  │
│  │  └─────────────────────────────────────────────────────────────────┘  │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         EXTERNAL SERVICES                              │  │
│  │  ┌──────────────────┐        ┌──────────────────┐                   │  │
│  │  │  bKash API       │        │  Smart Dairy     │                   │  │
│  │  │  (Tokenized      │◄──────►│  ERP (Odoo)      │                   │  │
│  │  │   Checkout)      │        │                  │                   │  │
│  │  └──────────────────┘        └──────────────────┘                   │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Technology Stack

| Component | Technology | Version |
|-----------|------------|---------|
| **Backend Framework** | FastAPI | 0.109+ |
| **HTTP Client** | httpx | 0.26+ |
| **Database** | PostgreSQL | 16.x |
| **ORM** | SQLAlchemy | 2.0+ |
| **Task Queue** | Celery | 5.3+ |
| **Cache** | Redis | 7.x |

---

## 4. MERCHANT ACCOUNT SETUP

### 4.1 Sandbox Account Configuration

```python
# config/payment_config.py

from pydantic_settings import BaseSettings
from typing import Literal

class BKashConfig(BaseSettings):
    """bKash payment gateway configuration"""
    
    # Environment
    environment: Literal['sandbox', 'production'] = 'sandbox'
    
    # API Credentials (from bKash merchant portal)
    app_key: str
    app_secret: str
    
    # Merchant Information
    merchant_id: str
    merchant_name: str = "Smart Dairy Ltd"
    
    # API Endpoints
    @property
    def base_url(self) -> str:
        if self.environment == 'sandbox':
            return "https://tokenized.sandbox.bka.sh/v1.2.0-beta"
        return "https://tokenized.bka.sh/v1.2.0-beta"
    
    # Token Settings
    token_ttl_minutes: int = 60
    
    # Transaction Settings
    currency: str = "BDT"
    min_transaction_amount: float = 10.0
    max_transaction_amount: float = 100000.0
    
    # Webhook
    webhook_secret: str
    webhook_url: str = "https://api.smartdairybd.com/webhooks/bkash"
    
    # Retry Settings
    max_retries: int = 3
    retry_delay_seconds: int = 5
    
    class Config:
        env_prefix = "BKASH_"

# Initialize config
bkash_config = BKashConfig()
```

### 4.2 Credential Security

```python
# Store credentials in environment variables or secrets manager
# .env file (DO NOT COMMIT TO GIT)

BKASH_ENVIRONMENT=sandbox
BKASH_APP_KEY=your_app_key_here
BKASH_APP_SECRET=your_app_secret_here
BKASH_MERCHANT_ID=your_merchant_id_here
BKASH_WEBHOOK_SECRET=your_webhook_secret_here
```

---

## 5. API IMPLEMENTATION

### 5.1 bKash Service Implementation

```python
# services/bkash_service.py

import httpx
import json
from datetime import datetime, timedelta
from typing import Optional, Dict, Any
from fastapi import HTTPException
import redis

class BKashService:
    """bKash Tokenized Checkout API integration"""
    
    def __init__(self, config: BKashConfig, redis_client: redis.Redis):
        self.config = config
        self.redis = redis_client
        self.http_client = httpx.AsyncClient(
            timeout=httpx.Timeout(30.0),
            headers={
                "Content-Type": "application/json",
                "Accept": "application/json",
            }
        )
    
    async def _get_access_token(self) -> str:
        """Get or refresh bKash access token"""
        cache_key = f"bkash_token:{self.config.environment}"
        
        # Check cache
        cached_token = self.redis.get(cache_key)
        if cached_token:
            return cached_token.decode()
        
        # Request new token
        url = f"{self.config.base_url}/token/checkout/token/grant"
        
        payload = {
            "app_key": self.config.app_key,
            "app_secret": self.config.app_secret,
        }
        
        headers = {
            "username": self.config.app_key,
            "password": self.config.app_secret,
        }
        
        response = await self.http_client.post(
            url,
            json=payload,
            headers=headers
        )
        
        if response.status_code != 200:
            raise BKashAPIError(f"Token grant failed: {response.text}")
        
        data = response.json()
        
        if data.get("statusCode") != "0000":
            raise BKashAPIError(f"Token error: {data.get('statusMessage')}")
        
        token = data["id_token"]
        expires_in = data.get("expires_in", 3600)
        
        # Cache token with 5 minute buffer
        cache_ttl = expires_in - 300
        self.redis.setex(cache_key, cache_ttl, token)
        
        return token
    
    async def _make_api_call(
        self,
        endpoint: str,
        payload: Dict[str, Any],
        auth_required: bool = True
    ) -> Dict[str, Any]:
        """Make authenticated API call to bKash"""
        url = f"{self.config.base_url}{endpoint}"
        
        headers = {
            "X-App-Key": self.config.app_key,
        }
        
        if auth_required:
            token = await self._get_access_token()
            headers["Authorization"] = f"Bearer {token}"
            headers["X-Id-Token"] = token
        
        response = await self.http_client.post(
            url,
            json=payload,
            headers=headers
        )
        
        if response.status_code != 200:
            raise BKashAPIError(f"API call failed: {response.status_code} - {response.text}")
        
        return response.json()
    
    async def create_payment(
        self,
        order_id: str,
        amount: float,
        customer_msisdn: Optional[str] = None,
        callback_url: Optional[str] = None,
        payer_reference: Optional[str] = None,
        merchant_invoice_number: Optional[str] = None,
    ) -> Dict[str, Any]:
        """
        Create a bKash payment
        
        Args:
            order_id: Internal order identifier
            amount: Payment amount in BDT
            customer_msisdn: Customer bKash number (optional for guest checkout)
            callback_url: URL for payment completion callback
            payer_reference: Reference for tokenized agreement (optional)
            merchant_invoice_number: Invoice number for reconciliation
        """
        # Validate amount
        if not (self.config.min_transaction_amount <= amount <= self.config.max_transaction_amount):
            raise ValueError(f"Amount must be between {self.config.min_transaction_amount} and {self.config.max_transaction_amount}")
        
        payload = {
            "mode": "0011",  # Tokenized Checkout
            "payerReference": payer_reference or f"SMARTDAIRY_{order_id}",
            "callbackURL": callback_url or self.config.webhook_url,
            "amount": str(amount),
            "currency": self.config.currency,
            "intent": "sale",
            "merchantInvoiceNumber": merchant_invoice_number or order_id,
        }
        
        # Add customer MSISDN if provided
        if customer_msisdn:
            payload["merchantCustomerPhone"] = self._format_phone(customer_msisdn)
        
        result = await self._make_api_call(
            "/token/checkout/create",
            payload
        )
        
        if result.get("statusCode") != "0000":
            raise BKashPaymentError(
                message=result.get("statusMessage", "Payment creation failed"),
                code=result.get("statusCode"),
                bkash_response=result
            )
        
        return {
            "payment_id": result["paymentID"],
            "bkash_url": result["bkashURL"],
            "callback_url": result["callbackURL"],
            "amount": amount,
            "currency": self.config.currency,
            "merchant_invoice_number": merchant_invoice_number or order_id,
            "transaction_status": result.get("transactionStatus"),
        }
    
    async def execute_payment(self, payment_id: str) -> Dict[str, Any]:
        """
        Execute payment after customer authorization
        
        Args:
            payment_id: Payment ID from create_payment
        """
        payload = {
            "paymentID": payment_id,
        }
        
        result = await self._make_api_call(
            "/token/checkout/execute",
            payload
        )
        
        if result.get("statusCode") != "0000":
            raise BKashPaymentError(
                message=result.get("statusMessage", "Payment execution failed"),
                code=result.get("statusCode"),
                bkash_response=result
            )
        
        return self._format_payment_response(result)
    
    async def query_payment(self, payment_id: str) -> Dict[str, Any]:
        """
        Query payment status
        
        Args:
            payment_id: Payment ID to query
        """
        payload = {
            "paymentID": payment_id,
        }
        
        result = await self._make_api_call(
            "/token/checkout/payment/status",
            payload
        )
        
        if result.get("statusCode") != "0000":
            raise BKashPaymentError(
                message=result.get("statusMessage", "Payment query failed"),
                code=result.get("statusCode"),
                bkash_response=result
            )
        
        return self._format_payment_response(result)
    
    async def refund_payment(
        self,
        payment_id: str,
        amount: float,
        trx_id: str,
        sku: Optional[str] = None,
        reason: Optional[str] = None,
    ) -> Dict[str, Any]:
        """
        Refund a completed payment
        
        Args:
            payment_id: Original payment ID
            amount: Refund amount (can be partial)
            trx_id: Original transaction ID
            sku: Product SKU (optional)
            reason: Refund reason
        """
        payload = {
            "paymentID": payment_id,
            "amount": str(amount),
            "trxID": trx_id,
            "sku": sku or "refund",
            "reason": reason or "Customer request",
        }
        
        result = await self._make_api_call(
            "/token/checkout/payment/refund",
            payload
        )
        
        if result.get("statusCode") != "0000":
            raise BKashRefundError(
                message=result.get("statusMessage", "Refund failed"),
                code=result.get("statusCode"),
                bkash_response=result
            )
        
        return {
            "refund_id": result.get("refundTrxID"),
            "original_trx_id": trx_id,
            "amount": amount,
            "currency": result.get("currency"),
            "status": result.get("transactionStatus"),
            "completed_time": result.get("completedTime"),
        }
    
    def _format_phone(self, phone: str) -> str:
        """Format phone number to bKash format"""
        # Remove all non-numeric characters
        phone = ''.join(filter(str.isdigit, phone))
        
        # Remove leading 880 or 0
        if phone.startswith('880'):
            phone = phone[3:]
        elif phone.startswith('0'):
            phone = phone[1:]
        
        # Add 880 prefix
        return f"880{phone}"
    
    def _format_payment_response(self, result: Dict[str, Any]) -> Dict[str, Any]:
        """Format bKash response to standardized format"""
        return {
            "payment_id": result.get("paymentID"),
            "transaction_id": result.get("trxID"),
            "amount": float(result.get("amount", 0)),
            "currency": result.get("currency"),
            "status": result.get("transactionStatus"),
            "customer_msisdn": result.get("customerMsisdn"),
            "merchant_invoice_number": result.get("merchantInvoiceNumber"),
            "transaction_time": result.get("paymentExecuteTime") or result.get("trxDate"),
            "intent": result.get("intent"),
        }


class BKashAPIError(Exception):
    """Base bKash API error"""
    pass


class BKashPaymentError(BKashAPIError):
    """Payment operation error"""
    def __init__(self, message: str, code: str, bkash_response: dict):
        super().__init__(message)
        self.code = code
        self.bkash_response = bkash_response


class BKashRefundError(BKashAPIError):
    """Refund operation error"""
    def __init__(self, message: str, code: str, bkash_response: dict):
        super().__init__(message)
        self.code = code
        self.bkash_response = bkash_response
```

### 5.2 Payment Controller

```python
# api/v1/payments/bkash_controller.py

from fastapi import APIRouter, Depends, HTTPException, BackgroundTasks, Request
from sqlalchemy.ext.asyncio import AsyncSession
from typing import Optional

router = APIRouter(prefix="/payments/bkash", tags=["bKash Payments"])

class CreatePaymentRequest(BaseModel):
    order_id: str
    amount: float = Field(..., gt=0)
    customer_phone: Optional[str] = None
    callback_url: Optional[str] = None
    customer_email: Optional[str] = None

class PaymentResponse(BaseModel):
    payment_id: str
    payment_url: str
    amount: float
    currency: str
    status: str
    expires_at: datetime

@router.post("/create", response_model=PaymentResponse)
async def create_bkash_payment(
    request: CreatePaymentRequest,
    background_tasks: BackgroundTasks,
    db: AsyncSession = Depends(get_db),
    bkash_service: BKashService = Depends(get_bkash_service),
    current_user: User = Depends(get_current_user),
):
    """Create a new bKash payment for an order"""
    
    # Validate order exists and belongs to user
    order = await OrderRepository.get_by_id(db, request.order_id)
    if not order:
        raise HTTPException(status_code=404, detail="Order not found")
    
    if order.user_id != current_user.id:
        raise HTTPException(status_code=403, detail="Not authorized for this order")
    
    if order.status != OrderStatus.PENDING:
        raise HTTPException(status_code=400, detail="Order is not in pending status")
    
    # Check for existing pending payment
    existing_payment = await PaymentRepository.get_pending_for_order(
        db, request.order_id, PaymentProvider.BKASH
    )
    
    if existing_payment:
        # Check if still valid
        if existing_payment.expires_at > datetime.utcnow():
            return PaymentResponse(
                payment_id=existing_payment.provider_payment_id,
                payment_url=existing_payment.payment_url,
                amount=existing_payment.amount,
                currency=existing_payment.currency,
                status=existing_payment.status.value,
                expires_at=existing_payment.expires_at,
            )
        else:
            # Expired, cancel it
            await PaymentRepository.update_status(
                db, existing_payment.id, PaymentStatus.EXPIRED
            )
    
    # Create bKash payment
    try:
        bkash_result = await bkash_service.create_payment(
            order_id=str(order.id),
            amount=float(order.total_amount),
            customer_msisdn=request.customer_phone,
            callback_url=request.callback_url,
            merchant_invoice_number=order.order_number,
        )
    except BKashPaymentError as e:
        logger.error(f"bKash payment creation failed: {e.message}")
        raise HTTPException(status_code=400, detail=e.message)
    
    # Save payment record
    payment = Payment(
        order_id=order.id,
        user_id=current_user.id,
        provider=PaymentProvider.BKASH,
        provider_payment_id=bkash_result["payment_id"],
        amount=bkash_result["amount"],
        currency=bkash_result["currency"],
        payment_url=bkash_result["bkash_url"],
        status=PaymentStatus.PENDING,
        expires_at=datetime.utcnow() + timedelta(hours=1),
        metadata={
            "merchant_invoice_number": bkash_result["merchant_invoice_number"],
            "customer_phone": request.customer_phone,
        }
    )
    
    await PaymentRepository.create(db, payment)
    
    # Schedule expiration check
    background_tasks.add_task(
        check_payment_expiration,
        payment.id,
        delay=3600  # 1 hour
    )
    
    return PaymentResponse(
        payment_id=payment.provider_payment_id,
        payment_url=payment.payment_url,
        amount=payment.amount,
        currency=payment.currency,
        status=payment.status.value,
        expires_at=payment.expires_at,
    )


@router.post("/execute/{payment_id}")
async def execute_bkash_payment(
    payment_id: str,
    db: AsyncSession = Depends(get_db),
    bkash_service: BKashService = Depends(get_bkash_service),
):
    """Execute payment after customer authorization"""
    
    # Get payment record
    payment = await PaymentRepository.get_by_provider_id(
        db, payment_id, PaymentProvider.BKASH
    )
    
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    if payment.status != PaymentStatus.PENDING:
        raise HTTPException(
            status_code=400, 
            detail=f"Payment is not in pending status: {payment.status}"
        )
    
    try:
        # Execute payment with bKash
        result = await bkash_service.execute_payment(payment_id)
        
        # Update payment record
        payment.status = PaymentStatus.COMPLETED
        payment.provider_transaction_id = result["transaction_id"]
        payment.completed_at = datetime.utcnow()
        payment.metadata.update({
            "customer_msisdn": result["customer_msisdn"],
            "transaction_time": result["transaction_time"],
        })
        
        await PaymentRepository.update(db, payment)
        
        # Update order status
        await OrderRepository.update_status(
            db, payment.order_id, OrderStatus.PAID
        )
        
        # Trigger post-payment tasks
        await process_successful_payment(payment)
        
        return {
            "status": "success",
            "payment_id": payment_id,
            "transaction_id": result["transaction_id"],
            "amount": result["amount"],
            "message": "Payment completed successfully",
        }
        
    except BKashPaymentError as e:
        # Update payment as failed
        payment.status = PaymentStatus.FAILED
        payment.metadata["error"] = {
            "code": e.code,
            "message": e.message,
        }
        await PaymentRepository.update(db, payment)
        
        logger.error(f"Payment execution failed: {e.message}")
        raise HTTPException(status_code=400, detail=e.message)


@router.get("/status/{payment_id}")
async def check_payment_status(
    payment_id: str,
    db: AsyncSession = Depends(get_db),
    bkash_service: BKashService = Depends(get_bkash_service),
):
    """Check payment status with bKash"""
    
    payment = await PaymentRepository.get_by_provider_id(
        db, payment_id, PaymentProvider.BKASH
    )
    
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    # If already completed/failed, return cached status
    if payment.status in [PaymentStatus.COMPLETED, PaymentStatus.FAILED]:
        return {
            "payment_id": payment_id,
            "status": payment.status.value,
            "transaction_id": payment.provider_transaction_id,
            "amount": payment.amount,
        }
    
    # Query bKash for current status
    try:
        result = await bkash_service.query_payment(payment_id)
        
        # Update local status if changed
        if result["status"] == "Completed" and payment.status != PaymentStatus.COMPLETED:
            payment.status = PaymentStatus.COMPLETED
            payment.provider_transaction_id = result["transaction_id"]
            payment.completed_at = datetime.utcnow()
            await PaymentRepository.update(db, payment)
            
            await OrderRepository.update_status(
                db, payment.order_id, OrderStatus.PAID
            )
        
        return {
            "payment_id": payment_id,
            "status": result["status"],
            "transaction_id": result.get("transaction_id"),
            "amount": result["amount"],
            "customer_msisdn": result.get("customer_msisdn"),
        }
        
    except BKashPaymentError as e:
        raise HTTPException(status_code=400, detail=e.message)


@router.post("/refund")
async def refund_bkash_payment(
    request: RefundRequest,
    db: AsyncSession = Depends(get_db),
    bkash_service: BKashService = Depends(get_bkash_service),
    current_user: User = Depends(get_current_user_admin),
):
    """Process refund for a completed bKash payment"""
    
    payment = await PaymentRepository.get_by_provider_id(
        db, request.payment_id, PaymentProvider.BKASH
    )
    
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    if payment.status != PaymentStatus.COMPLETED:
        raise HTTPException(status_code=400, detail="Payment is not completed")
    
    # Validate refund amount
    if request.amount > payment.amount:
        raise HTTPException(
            status_code=400, 
            detail="Refund amount cannot exceed payment amount"
        )
    
    try:
        result = await bkash_service.refund_payment(
            payment_id=request.payment_id,
            amount=request.amount,
            trx_id=payment.provider_transaction_id,
            reason=request.reason,
        )
        
        # Create refund record
        refund = Refund(
            payment_id=payment.id,
            order_id=payment.order_id,
            amount=request.amount,
            provider_refund_id=result["refund_id"],
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
            "refund_id": result["refund_id"],
            "amount": result["amount"],
            "message": "Refund processed successfully",
        }
        
    except BKashRefundError as e:
        logger.error(f"Refund failed: {e.message}")
        raise HTTPException(status_code=400, detail=e.message)
```

---

## 6. CHECKOUT FLOW

### 6.1 Customer Checkout Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     bKASH CHECKOUT USER FLOW                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌────────────┐    ┌────────────┐    ┌────────────┐    ┌────────────┐      │
│  │   Cart     │───▶│  Checkout  │───▶│   Order    │───▶│  Payment   │      │
│  │  Review    │    │   Page     │    │  Summary   │    │  Select    │      │
│  └────────────┘    └────────────┘    └────────────┘    └────────────┘      │
│                                                             │                │
│                                                             ▼                │
│  ┌────────────┐    ┌────────────┐    ┌────────────┐    ┌────────────┐      │
│  │  Success   │◀───│  Execute   │◀───│  Authorize │◀───│   bKash    │      │
│  │   Page     │    │  Payment   │    │  Payment   │    │  Checkout  │      │
│  └────────────┘    └────────────┘    └────────────┘    └────────────┘      │
│       │                                                                      │
│       │                                                                      │
│       ▼                                                                      │
│  ┌────────────┐    ┌────────────┐                                            │
│  │  Order     │───▶│  Receipt   │                                            │
│  │  Details   │    │  Email     │                                            │
│  └────────────┘    └────────────┘                                            │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 6.2 Frontend Integration

```javascript
// Frontend JavaScript integration example

class BKashCheckout {
  constructor(config) {
    this.apiBaseUrl = config.apiBaseUrl;
    this.orderId = config.orderId;
  }

  async initiatePayment() {
    try {
      // Step 1: Create payment on backend
      const response = await fetch(`${this.apiBaseUrl}/payments/bkash/create`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${this.getAuthToken()}`,
        },
        body: JSON.stringify({
          order_id: this.orderId,
          amount: this.getOrderTotal(),
          customer_phone: this.getCustomerPhone(),
          callback_url: `${window.location.origin}/payment/callback`,
        }),
      });

      const paymentData = await response.json();

      if (!response.ok) {
        throw new Error(paymentData.detail || 'Payment creation failed');
      }

      // Step 2: Redirect to bKash checkout
      this.redirectToBKash(paymentData.payment_url);

    } catch (error) {
      console.error('Payment initiation failed:', error);
      this.showError(error.message);
    }
  }

  redirectToBKash(bkashUrl) {
    // Open bKash checkout in same window or popup
    window.location.href = bkashUrl;
  }

  async handleCallback(paymentId) {
    // Called when customer returns from bKash
    try {
      const response = await fetch(
        `${this.apiBaseUrl}/payments/bkash/execute/${paymentId}`,
        {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${this.getAuthToken()}`,
          },
        }
      );

      const result = await response.json();

      if (result.status === 'success') {
        this.showSuccess(result);
      } else {
        this.showError(result.message);
      }
    } catch (error) {
      console.error('Payment execution failed:', error);
      this.showError('Payment could not be completed');
    }
  }
}
```

---

## 7. WEBHOOK HANDLING

### 7.1 Webhook Implementation

```python
# api/v1/webhooks/bkash_webhook.py

from fastapi import APIRouter, Request, HTTPException, Header
import hmac
import hashlib

router = APIRouter(prefix="/webhooks/bkash", tags=["bKash Webhooks"])

@router.post("/payment")
async def bkash_payment_webhook(
    request: Request,
    x_bkash_signature: str = Header(None),
    db: AsyncSession = Depends(get_db),
):
    """Handle bKash payment webhooks"""
    
    # Get raw body
    body = await request.body()
    payload = await request.json()
    
    # Verify webhook signature
    if not verify_webhook_signature(body, x_bkash_signature):
        logger.warning("Invalid webhook signature")
        raise HTTPException(status_code=401, detail="Invalid signature")
    
    event_type = payload.get("eventType")
    payment_data = payload.get("data", {})
    
    logger.info(f"Received bKash webhook: {event_type}")
    
    if event_type == "PAYMENT_COMPLETED":
        await handle_payment_completed(payment_data, db)
    elif event_type == "PAYMENT_FAILED":
        await handle_payment_failed(payment_data, db)
    elif event_type == "REFUND_COMPLETED":
        await handle_refund_completed(payment_data, db)
    else:
        logger.warning(f"Unhandled webhook event type: {event_type}")
    
    return {"status": "received"}

def verify_webhook_signature(body: bytes, signature: str) -> bool:
    """Verify bKash webhook signature"""
    if not signature:
        return False
    
    expected_signature = hmac.new(
        bkash_config.webhook_secret.encode(),
        body,
        hashlib.sha256
    ).hexdigest()
    
    return hmac.compare_digest(signature, expected_signature)

async def handle_payment_completed(data: dict, db: AsyncSession):
    """Process payment completed webhook"""
    payment_id = data.get("paymentID")
    
    payment = await PaymentRepository.get_by_provider_id(
        db, payment_id, PaymentProvider.BKASH
    )
    
    if not payment:
        logger.error(f"Payment not found for webhook: {payment_id}")
        return
    
    if payment.status == PaymentStatus.COMPLETED:
        # Already processed
        return
    
    # Update payment
    payment.status = PaymentStatus.COMPLETED
    payment.provider_transaction_id = data.get("trxID")
    payment.completed_at = datetime.utcnow()
    payment.metadata.update({
        "webhook_received_at": datetime.utcnow().isoformat(),
        "customer_msisdn": data.get("customerMsisdn"),
    })
    
    await PaymentRepository.update(db, payment)
    
    # Update order
    await OrderRepository.update_status(db, payment.order_id, OrderStatus.PAID)
    
    # Send confirmation email
    await send_payment_confirmation_email(payment)
    
    logger.info(f"Payment completed via webhook: {payment_id}")

async def handle_payment_failed(data: dict, db: AsyncSession):
    """Process payment failed webhook"""
    payment_id = data.get("paymentID")
    
    payment = await PaymentRepository.get_by_provider_id(
        db, payment_id, PaymentProvider.BKASH
    )
    
    if payment:
        payment.status = PaymentStatus.FAILED
        payment.metadata["failure_reason"] = data.get("errorMessage")
        await PaymentRepository.update(db, payment)
    
    logger.info(f"Payment failed webhook received: {payment_id}")
```

---

## 8. REFUND AND DISPUTE

### 8.1 Refund Process

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         REFUND PROCESS FLOW                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐          │
│  │ Customer │────▶│  Admin   │────▶│  Verify  │────▶│  Process │          │
│  │  Request │     │  Review  │     │  Eligible│     │  Refund  │          │
│  └──────────┘     └──────────┘     └──────────┘     └────┬─────┘          │
│                                                          │                   │
│                                                          ▼                   │
│                                                   ┌──────────────┐          │
│                                                   │  bKash API   │          │
│                                                   └──────┬───────┘          │
│                                                          │                   │
│                                                          ▼                   │
│  ┌──────────┐     ┌──────────┐     ┌──────────┐    ┌──────────────┐        │
│  │ Customer │◀────│  Email   │◀────│  Update  │◀───│   Success    │        │
│  │ Notified │     │ Receipt  │     │  Order   │    │              │        │
│  └──────────┘     └──────────┘     └──────────┘    └──────────────┘        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 8.2 Refund Eligibility Rules

| Scenario | Eligible | Time Limit | Conditions |
|----------|----------|------------|------------|
| Order cancelled before delivery | Full | 24 hours | - |
| Product quality issue | Full/Partial | 7 days | Photo evidence |
| Wrong product delivered | Full | 7 days | Return product |
| Late delivery | Partial | - | > 2 hours late |
| Customer changed mind | No | - | Perishable goods |

---

## 9. SECURITY IMPLEMENTATION

### 9.1 Security Checklist

| Layer | Measure | Implementation |
|-------|---------|----------------|
| **API Communication** | TLS 1.3 | HTTPS only |
| **Token Storage** | Encrypted at rest | Redis encryption |
| **Credentials** | Environment variables | Never in code |
| **Webhooks** | HMAC signature verification | SHA-256 |
| **Idempotency** | Request deduplication | Redis cache |
| **Rate Limiting** | API call throttling | 100 req/min |

### 9.2 Idempotency Implementation

```python
# middleware/idempotency.py

async def idempotency_middleware(request: Request, call_next):
    """Ensure idempotent payment operations"""
    
    idempotency_key = request.headers.get("Idempotency-Key")
    
    if idempotency_key and request.method == "POST":
        # Check if already processed
        cached = await redis.get(f"idempotency:{idempotency_key}")
        
        if cached:
            # Return cached response
            return JSONResponse(
                content=json.loads(cached),
                status_code=200
            )
        
        # Process request
        response = await call_next(request)
        
        # Cache successful response
        if response.status_code == 200:
            response_body = [section async for section in response.body_iterator]
            response.body_iterator = iterate_in_threadpool(iter(response_body))
            
            await redis.setex(
                f"idempotency:{idempotency_key}",
                86400,  # 24 hours
                response_body[0]
            )
        
        return response
    
    return await call_next(request)
```

---

## 10. TESTING STRATEGY

### 10.1 Test Wallet Credentials (Sandbox)

| Type | Number | PIN | OTP |
|------|--------|-----|-----|
| **Personal** | 01619754582 | 12121 | 123456 |
| **Personal** | 01619754583 | 12121 | 123456 |
| **Agent** | 01619754584 | 12121 | 123456 |
| **Merchant** | 01619754585 | 12121 | 123456 |

### 10.2 Test Scenarios

```python
# tests/integration/test_bkash_integration.py

@pytest.mark.asyncio
async def test_successful_payment_flow():
    """Test complete successful payment flow"""
    
    # 1. Create payment
    payment = await bkash_service.create_payment(
        order_id="TEST-001",
        amount=100.00,
        customer_msisdn="01619754582",
    )
    
    assert payment["payment_id"]
    assert payment["bkash_url"]
    
    # 2. Simulate successful authorization (mock)
    with mock.patch.object(bkash_service, '_make_api_call') as mock_call:
        mock_call.return_value = {
            "statusCode": "0000",
            "statusMessage": "Successful",
            "paymentID": payment["payment_id"],
            "trxID": "TRX123456",
            "amount": "100.00",
            "transactionStatus": "Completed",
        }
        
        result = await bkash_service.execute_payment(payment["payment_id"])
    
    assert result["status"] == "Completed"
    assert result["transaction_id"]


@pytest.mark.asyncio
async def test_insufficient_balance():
    """Test payment with insufficient balance"""
    
    with mock.patch.object(bkash_service, '_make_api_call') as mock_call:
        mock_call.return_value = {
            "statusCode": "2023",
            "statusMessage": "Insufficient Balance",
        }
        
        with pytest.raises(BKashPaymentError) as exc:
            await bkash_service.execute_payment("TEST-PAYMENT-ID")
        
        assert exc.value.code == "2023"
```

---

## 11. ERROR HANDLING

### 11.1 bKash Error Codes

| Code | Message | Action |
|------|---------|--------|
| 0000 | Success | Continue |
| 2001 | App Key not found | Check credentials |
| 2002 | App Secret not found | Check credentials |
| 2005 | Token expired | Refresh token |
| 2023 | Insufficient Balance | Inform customer |
| 2029 | Cancelled by user | Allow retry |
| 2031 | Declined by issuer | Contact bank |
| 2033 | Invalid PIN | Prompt retry |
| 2055 | Transaction timeout | Query status |

---

## 12. PRODUCTION DEPLOYMENT

### 12.1 Go-Live Checklist

- [ ] Production merchant account activated
- [ ] SSL certificate valid
- [ ] Webhook URL accessible from internet
- [ ] IP whitelisting configured (if required)
- [ ] Refund process documented for support team
- [ ] Transaction monitoring alerts configured
- [ ] Backup payment method available

### 12.2 Monitoring Dashboard

| Metric | Alert Threshold |
|--------|-----------------|
| Payment Success Rate | < 95% |
| Average Response Time | > 3s |
| Failed Transactions | > 5% |
| Refund Rate | > 10% |

---

## 13. APPENDICES

### Appendix A: API Reference Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/token/checkout/token/grant` | POST | Get access token |
| `/token/checkout/create` | POST | Create payment |
| `/token/checkout/execute` | POST | Execute payment |
| `/token/checkout/payment/status` | POST | Query payment |
| `/token/checkout/payment/refund` | POST | Refund payment |

### Appendix B: Transaction Limits

| Type | Minimum | Maximum |
|------|---------|---------|
| Single Transaction | ৳10 | ৳100,000 |
| Daily Limit (Customer) | - | ৳300,000 |
| Monthly Limit (Customer) | - | ৳1,000,000 |

---

**END OF bKASH INTEGRATION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Mar 28, 2026 | Backend Developer | Initial version |
