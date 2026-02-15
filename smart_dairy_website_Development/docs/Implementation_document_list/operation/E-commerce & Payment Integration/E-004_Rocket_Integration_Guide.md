# SMART DAIRY LTD.
## ROCKET (DBBL MOBILE BANKING) INTEGRATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | E-004 |
| **Version** | 1.0 |
| **Date** | May 9, 2026 |
| **Author** | Backend Developer |
| **Owner** | Backend Developer |
| **Reviewer** | Tech Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Rocket Overview](#2-rocket-overview)
3. [Integration Architecture](#3-integration-architecture)
4. [Merchant Account Setup](#4-merchant-account-setup)
5. [API Implementation](#5-api-implementation)
6. [Checkout Flow](#6-checkout-flow)
7. [Webhook Handling](#7-webhook-handling)
8. [Refund Processing](#8-refund-processing)
9. [Security Implementation](#9-security-implementation)
10. [Testing Strategy](#10-testing-strategy)
11. [Error Handling](#11-error-handling)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive technical guidelines for integrating Rocket (Dutch-Bangla Bank Mobile Banking) payment gateway into the Smart Dairy e-commerce platform. Rocket is one of the leading mobile financial services in Bangladesh, especially popular in rural areas.

### 1.2 Scope

The integration covers:
- **Rocket Cash Out API** - Payment acceptance from Rocket accounts
- **Rocket Send Money API** - Direct fund transfers
- **Transaction Verification** - Payment confirmation
- **Refund Processing** - Automated refunds
- **Webhook Integration** - Real-time status updates

### 1.3 Business Context

| Payment Scenario | Priority | Notes |
|-----------------|----------|-------|
| B2C Online Orders | Primary | Popular in semi-urban areas |
| Cash Out Payments | Primary | Agent-based collection |
| B2B Payments | Secondary | Bulk transfers |
| Refund Processing | Primary | Direct to customer account |

---

## 2. ROCKET OVERVIEW

### 2.1 Rocket Payment Gateway

Rocket (powered by Dutch-Bangla Bank) provides mobile banking services with widespread agent network coverage across Bangladesh. The merchant integration allows businesses to accept payments directly from Rocket accounts.

**Key Features:**
- Direct account-to-account transfers
- Agent-assisted cash-out options
- Wide rural network coverage
- Lower transaction fees for small amounts
- USSD-based payments for feature phones

### 2.2 API Environments

| Environment | Base URL | Purpose |
|-------------|----------|---------|
| **Sandbox** | `https://sandbox.rocket.com.bd` | Development & testing |
| **Production** | `https://api.rocket.com.bd` | Live transactions |

### 2.3 Transaction Methods

| Method | Description | Use Case |
|--------|-------------|----------|
| **Rocket Cash Out** | Customer sends to merchant wallet | Primary method |
| **Rocket Send Money** | Direct account transfer | Registered users |
| **USSD Payment** | *322# based payment | Feature phones |
| **QR Payment** | Scan and pay | Mobile app users |

---

## 3. INTEGRATION ARCHITECTURE

### 3.1 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ROCKET INTEGRATION ARCHITECTURE                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         PRESENTATION LAYER                             │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │ B2C Checkout │  │ USSD Flow    │  │ Mobile App   │               │  │
│  │  │ UI           │  │ Handler      │  │ QR Payment   │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         APPLICATION LAYER                              │  │
│  │  ┌─────────────────────────────────────────────────────────────────┐  │  │
│  │  │              FastAPI Payment Gateway Service                     │  │  │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐       │  │  │
│  │  │  │ Payment  │  │ Rocket   │  │ Webhook  │  │ Refund   │       │  │  │
│  │  │  │ Controller│ │ Service  │  │ Handler  │  │ Service  │       │  │  │
│  │  │  └──────────┘  └──────────┘  └──────────┘  └──────────┘       │  │  │
│  │  │  ┌──────────┐  ┌──────────┐  ┌──────────┐                      │  │  │
│  │  │  │ USSD     │  │ Payment  │  │ Transaction│                    │  │  │
│  │  │  │ Handler  │  │ Repository│ │ Logger    │                    │  │  │
│  │  │  └──────────┘  └──────────┘  └──────────┘                      │  │  │
│  │  └─────────────────────────────────────────────────────────────────┘  │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         EXTERNAL SERVICES                              │  │
│  │  ┌──────────────────┐        ┌──────────────────┐                   │  │
│  │  │  Rocket API      │        │  Smart Dairy     │                   │  │
│  │  │  (DBBL MFS)      │◄──────►│  ERP (Odoo)      │                   │  │
│  │  └──────────────────┘        └──────────────────┘                   │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. MERCHANT ACCOUNT SETUP

### 4.1 Sandbox Configuration

```python
# config/rocket_config.py

from pydantic_settings import BaseSettings
from typing import Literal

class RocketConfig(BaseSettings):
    """Rocket payment gateway configuration"""
    
    # Environment
    environment: Literal['sandbox', 'production'] = 'sandbox'
    
    # API Credentials (from DBBL/Rocket merchant portal)
    merchant_id: str
    api_key: str
    api_secret: str
    
    # Rocket Wallet Details
    rocket_wallet_number: str  # Merchant's Rocket wallet
    
    # API Endpoints
    @property
    def base_url(self) -> str:
        if self.environment == 'sandbox':
            return "https://sandbox.rocket.com.bd/api/v1"
        return "https://api.rocket.com.bd/api/v1"
    
    # Transaction Settings
    currency: str = "BDT"
    min_transaction_amount: float = 10.0
    max_transaction_amount: float = 50000.0
    
    # Webhook
    webhook_secret: str
    callback_url: str = "https://api.smartdairybd.com/payments/rocket/callback"
    
    # USSD Settings
    ussd_short_code: str = "322"  # *322#
    
    # Retry Settings
    max_retries: int = 3
    retry_delay_seconds: int = 5
    
    class Config:
        env_prefix = "ROCKET_"

# Initialize config
rocket_config = RocketConfig()
```

---

## 5. API IMPLEMENTATION

### 5.1 Rocket Service Implementation

```python
# services/rocket_service.py

import httpx
import json
import hmac
import hashlib
import base64
from datetime import datetime
from typing import Optional, Dict, Any

class RocketService:
    """Rocket (DBBL Mobile Banking) API integration"""
    
    def __init__(self, config: RocketConfig):
        self.config = config
        self.http_client = httpx.AsyncClient(
            timeout=httpx.Timeout(30.0),
            headers={
                "Content-Type": "application/json",
                "Accept": "application/json",
            }
        )
    
    def _generate_signature(self, data: str) -> str:
        """Generate HMAC-SHA256 signature"""
        signature = hmac.new(
            self.config.api_secret.encode(),
            data.encode(),
            hashlib.sha256
        ).digest()
        return base64.b64encode(signature).decode()
    
    def _generate_timestamp(self) -> str:
        """Generate ISO format timestamp"""
        return datetime.utcnow().strftime("%Y-%m-%dT%H:%M:%SZ")
    
    def _format_phone(self, phone: str) -> str:
        """Format phone number to Rocket format"""
        phone = ''.join(filter(str.isdigit, phone))
        
        if phone.startswith('880'):
            phone = phone[3:]
        elif phone.startswith('0'):
            phone = phone[1:]
        
        return phone
    
    async def _make_api_call(
        self,
        endpoint: str,
        payload: Dict[str, Any],
    ) -> Dict[str, Any]:
        """Make authenticated API call to Rocket"""
        url = f"{self.config.base_url}/{endpoint}"
        
        # Prepare request
        timestamp = self._generate_timestamp()
        data_string = json.dumps(payload, separators=(',', ':'))
        signature = self._generate_signature(data_string)
        
        headers = {
            "X-Rocket-Merchant-ID": self.config.merchant_id,
            "X-Rocket-API-Key": self.config.api_key,
            "X-Rocket-Timestamp": timestamp,
            "X-Rocket-Signature": signature,
        }
        
        response = await self.http_client.post(
            url,
            json=payload,
            headers=headers
        )
        
        if response.status_code != 200:
            raise RocketAPIError(f"API call failed: {response.status_code} - {response.text}")
        
        result = response.json()
        
        # Verify response signature
        if "signature" in result:
            response_data = json.dumps({k: v for k, v in result.items() if k != "signature"})
            expected_signature = self._generate_signature(response_data)
            if not hmac.compare_digest(expected_signature, result["signature"]):
                raise RocketSecurityError("Invalid response signature")
        
        return result
    
    async def create_payment_request(
        self,
        order_id: str,
        amount: float,
        customer_phone: str,
        callback_url: Optional[str] = None,
        description: Optional[str] = None,
    ) -> Dict[str, Any]:
        """
        Create Rocket payment request
        
        Args:
            order_id: Internal order identifier
            amount: Payment amount
            customer_phone: Customer Rocket wallet number
            callback_url: Callback URL
            description: Payment description
        """
        if not (self.config.min_transaction_amount <= amount <= self.config.max_transaction_amount):
            raise ValueError(f"Amount must be between {self.config.min_transaction_amount} and {self.config.max_transaction_amount}")
        
        payload = {
            "merchantId": self.config.merchant_id,
            "merchantWallet": self.config.rocket_wallet_number,
            "orderId": order_id,
            "amount": str(amount),
            "currency": self.config.currency,
            "customerPhone": self._format_phone(customer_phone),
            "callbackUrl": callback_url or self.config.callback_url,
            "description": description or f"Smart Dairy Order #{order_id}",
            "timestamp": self._generate_timestamp(),
        }
        
        result = await self._make_api_call("payment/request", payload)
        
        if result.get("status") != "SUCCESS":
            raise RocketPaymentError(
                message=result.get("message", "Payment request failed"),
                code=result.get("errorCode"),
                rocket_response=result
            )
        
        return {
            "transaction_id": result["transactionId"],
            "order_id": order_id,
            "amount": amount,
            "currency": self.config.currency,
            "customer_phone": customer_phone,
            "status": result["status"],
            "ussd_code": f"*322*{self.config.rocket_wallet_number}*{amount}#",
            "qr_code_url": result.get("qrCodeUrl"),
            "expiry_time": result.get("expiryTime"),
        }
    
    async def verify_payment(self, transaction_id: str) -> Dict[str, Any]:
        """
        Verify payment status
        
        Args:
            transaction_id: Rocket transaction ID
        """
        payload = {
            "merchantId": self.config.merchant_id,
            "transactionId": transaction_id,
            "timestamp": self._generate_timestamp(),
        }
        
        result = await self._make_api_call("payment/verify", payload)
        
        if result.get("status") != "SUCCESS":
            raise RocketPaymentError(
                message=result.get("message", "Verification failed"),
                code=result.get("errorCode"),
                rocket_response=result
            )
        
        return {
            "transaction_id": transaction_id,
            "status": result["paymentStatus"],
            "amount": result.get("amount"),
            "customer_phone": result.get("customerPhone"),
            "transaction_time": result.get("transactionTime"),
        }
    
    async def process_refund(
        self,
        transaction_id: str,
        amount: float,
        customer_phone: str,
        reason: Optional[str] = None,
    ) -> Dict[str, Any]:
        """
        Process refund to customer
        
        Args:
            transaction_id: Original transaction ID
            amount: Refund amount
            customer_phone: Customer phone
            reason: Refund reason
        """
        payload = {
            "merchantId": self.config.merchant_id,
            "originalTransactionId": transaction_id,
            "amount": str(amount),
            "currency": self.config.currency,
            "customerPhone": self._format_phone(customer_phone),
            "reason": reason or "Customer refund",
            "timestamp": self._generate_timestamp(),
        }
        
        result = await self._make_api_call("payment/refund", payload)
        
        if result.get("status") != "SUCCESS":
            raise RocketRefundError(
                message=result.get("message", "Refund failed"),
                code=result.get("errorCode"),
                rocket_response=result
            )
        
        return {
            "refund_transaction_id": result["refundTransactionId"],
            "original_transaction_id": transaction_id,
            "amount": amount,
            "status": result["status"],
            "refund_time": result.get("refundTime"),
        }


class RocketAPIError(Exception):
    """Base Rocket API error"""
    pass


class RocketPaymentError(RocketAPIError):
    """Payment operation error"""
    def __init__(self, message: str, code: str, rocket_response: dict):
        super().__init__(message)
        self.code = code
        self.rocket_response = rocket_response


class RocketSecurityError(RocketAPIError):
    """Security-related error"""
    pass


class RocketRefundError(RocketAPIError):
    """Refund operation error"""
    def __init__(self, message: str, code: str, rocket_response: dict):
        super().__init__(message)
        self.code = code
        self.rocket_response = rocket_response
```

### 5.2 Payment Controller

```python
# api/v1/payments/rocket_controller.py

from fastapi import APIRouter, Depends, HTTPException, BackgroundTasks
from sqlalchemy.ext.asyncio import AsyncSession

router = APIRouter(prefix="/payments/rocket", tags=["Rocket Payments"])

@router.post("/create")
async def create_rocket_payment(
    request: CreatePaymentRequest,
    background_tasks: BackgroundTasks,
    db: AsyncSession = Depends(get_db),
    rocket_service: RocketService = Depends(get_rocket_service),
    current_user: User = Depends(get_current_user_optional),
):
    """Create Rocket payment request"""
    
    order = await OrderRepository.get_by_id(db, request.order_id)
    if not order:
        raise HTTPException(status_code=404, detail="Order not found")
    
    try:
        result = await rocket_service.create_payment_request(
            order_id=str(order.id),
            amount=float(order.total_amount),
            customer_phone=request.customer_phone,
            description=f"Smart Dairy - Order {order.order_number}",
        )
        
        # Save payment record
        payment = Payment(
            order_id=order.id,
            user_id=current_user.id if current_user else None,
            provider=PaymentProvider.ROCKET,
            provider_payment_id=result["transaction_id"],
            amount=result["amount"],
            currency=result["currency"],
            status=PaymentStatus.PENDING,
            customer_phone=request.customer_phone,
            expires_at=datetime.utcnow() + timedelta(minutes=30),
        )
        
        await PaymentRepository.create(db, payment)
        
        return {
            "transaction_id": result["transaction_id"],
            "amount": result["amount"],
            "customer_phone": result["customer_phone"],
            "ussd_code": result["ussd_code"],
            "qr_code_url": result.get("qr_code_url"),
            "instructions": "Dial the USSD code or scan QR code to complete payment",
        }
        
    except RocketPaymentError as e:
        raise HTTPException(status_code=400, detail=e.message)


@router.get("/verify/{transaction_id}")
async def verify_rocket_payment(
    transaction_id: str,
    db: AsyncSession = Depends(get_db),
    rocket_service: RocketService = Depends(get_rocket_service),
):
    """Verify payment status"""
    
    payment = await PaymentRepository.get_by_provider_id(
        db, transaction_id, PaymentProvider.ROCKET
    )
    
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    try:
        result = await rocket_service.verify_payment(transaction_id)
        
        if result["status"] == "COMPLETED" and payment.status != PaymentStatus.COMPLETED:
            payment.status = PaymentStatus.COMPLETED
            payment.completed_at = datetime.utcnow()
            await PaymentRepository.update(db, payment)
            
            await OrderRepository.update_status(db, payment.order_id, OrderStatus.PAID)
        
        return result
        
    except RocketPaymentError as e:
        raise HTTPException(status_code=400, detail=e.message)
```

---

## 6. CHECKOUT FLOW

### 6.1 USSD Payment Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ROCKET USSD PAYMENT FLOW                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Customer initiates payment:                                                 │
│                                                                              │
│  1. Customer dials: *322*MERCHANT_NO*AMOUNT#                                 │
│     Example: *322*017XXXXXXXX*500#                                           │
│                                                                              │
│  2. Customer receives PIN prompt:                                            │
│     "Enter your Rocket PIN:"                                                 │
│                                                                              │
│  3. Customer enters PIN                                                      │
│                                                                              │
│  4. Customer confirms payment:                                               │
│     "Pay ৳500 to Smart Dairy?"                                               │
│     1. Yes                                                                   │
│     2. No                                                                    │
│                                                                              │
│  5. Payment confirmation received                                            │
│     "Payment successful. TxnID: ABC123"                                      │
│                                                                              │
│  6. Webhook notifies Smart Dairy backend                                     │
│                                                                              │
│  7. Order status updated to PAID                                             │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 6.2 Frontend Integration

```javascript
// Frontend JavaScript for Rocket

class RocketCheckout {
  constructor(config) {
    this.apiBaseUrl = config.apiBaseUrl;
    this.merchantWallet = config.merchantWallet;
  }

  async initiatePayment(orderId, amount, customerPhone) {
    try {
      const response = await fetch(`${this.apiBaseUrl}/payments/rocket/create`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${this.getAuthToken()}`,
        },
        body: JSON.stringify({
          order_id: orderId,
          amount: amount,
          customer_phone: customerPhone,
        }),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.detail || 'Payment creation failed');
      }

      // Show payment options
      this.showPaymentOptions(data);
      
      return data;

    } catch (error) {
      console.error('Rocket payment initiation failed:', error);
      throw error;
    }
  }

  showPaymentOptions(paymentData) {
    const modal = document.createElement('div');
    modal.className = 'rocket-payment-modal';
    modal.innerHTML = `
      <div class="modal-content">
        <h3>Pay with Rocket</h3>
        <p>Amount: ৳${paymentData.amount}</p>
        
        <div class="payment-options">
          <div class="option">
            <h4>Option 1: USSD</h4>
            <p>Dial: <strong>${paymentData.ussd_code}</strong></p>
            <button onclick="this.copyToClipboard('${paymentData.ussd_code}')">
              Copy Code
            </button>
          </div>
          
          <div class="option">
            <h4>Option 2: QR Code</h4>
            <img src="${paymentData.qr_code_url}" alt="Scan to Pay">
            <p>Scan with Rocket app</p>
          </div>
        </div>
        
        <button onclick="rocketCheckout.checkStatus('${paymentData.transaction_id}')">
          I've Completed Payment
        </button>
      </div>
    `;
    document.body.appendChild(modal);
  }

  copyToClipboard(text) {
    navigator.clipboard.writeText(text);
    alert('USSD code copied!');
  }

  async checkStatus(transactionId) {
    try {
      const response = await fetch(
        `${this.apiBaseUrl}/payments/rocket/verify/${transactionId}`,
        {
          headers: {
            'Authorization': `Bearer ${this.getAuthToken()}`,
          },
        }
      );

      const result = await response.json();

      if (result.status === 'COMPLETED') {
        window.location.href = '/order/success';
      } else {
        alert('Payment not confirmed yet. Please try again in a moment.');
      }
    } catch (error) {
      console.error('Status check failed:', error);
    }
  }
}
```

---

## 7. WEBHOOK HANDLING

```python
# api/v1/webhooks/rocket_webhook.py

from fastapi import APIRouter, Request, HTTPException, Header
import hmac
import hashlib
import base64

router = APIRouter(prefix="/webhooks/rocket", tags=["Rocket Webhooks"])

@router.post("/payment")
async def rocket_payment_webhook(
    request: Request,
    x_rocket_signature: str = Header(None),
    db: AsyncSession = Depends(get_db),
):
    """Handle Rocket payment webhooks"""
    
    body = await request.body()
    payload = await request.json()
    
    # Verify signature
    if not verify_rocket_signature(body, x_rocket_signature):
        raise HTTPException(status_code=401, detail="Invalid signature")
    
    transaction_id = payload.get("transactionId")
    status = payload.get("paymentStatus")
    
    logger.info(f"Rocket webhook: {transaction_id} - {status}")
    
    payment = await PaymentRepository.get_by_provider_id(
        db, transaction_id, PaymentProvider.ROCKET
    )
    
    if not payment:
        logger.error(f"Payment not found: {transaction_id}")
        return {"status": "received"}
    
    if status == "COMPLETED":
        payment.status = PaymentStatus.COMPLETED
        payment.completed_at = datetime.utcnow()
        await PaymentRepository.update(db, payment)
        
        await OrderRepository.update_status(db, payment.order_id, OrderStatus.PAID)
        await send_payment_confirmation_email(payment)
        
    elif status == "FAILED":
        payment.status = PaymentStatus.FAILED
        payment.metadata["failure_reason"] = payload.get("failureReason")
        await PaymentRepository.update(db, payment)
    
    return {"status": "processed"}

def verify_rocket_signature(body: bytes, signature: str) -> bool:
    """Verify Rocket webhook signature"""
    if not signature:
        return False
    
    expected = hmac.new(
        rocket_config.webhook_secret.encode(),
        body,
        hashlib.sha256
    ).digest()
    
    return hmac.compare_digest(
        base64.b64encode(expected).decode(),
        signature
    )
```

---

## 8. REFUND PROCESSING

```python
@router.post("/refund")
async def refund_rocket_payment(
    request: RefundRequest,
    db: AsyncSession = Depends(get_db),
    rocket_service: RocketService = Depends(get_rocket_service),
    current_user: User = Depends(get_current_user_admin),
):
    """Process Rocket refund"""
    
    payment = await PaymentRepository.get_by_provider_id(
        db, request.transaction_id, PaymentProvider.ROCKET
    )
    
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    try:
        result = await rocket_service.process_refund(
            transaction_id=request.transaction_id,
            amount=request.amount,
            customer_phone=payment.customer_phone,
            reason=request.reason,
        )
        
        # Create refund record
        refund = Refund(
            payment_id=payment.id,
            order_id=payment.order_id,
            amount=request.amount,
            provider_refund_id=result["refund_transaction_id"],
            status=RefundStatus.COMPLETED,
            reason=request.reason,
            processed_by=current_user.id,
        )
        
        await RefundRepository.create(db, refund)
        
        return {
            "status": "success",
            "refund_id": result["refund_transaction_id"],
            "amount": result["amount"],
        }
        
    except RocketRefundError as e:
        logger.error(f"Refund failed: {e.message}")
        raise HTTPException(status_code=400, detail=e.message)
```

---

## 9. SECURITY IMPLEMENTATION

| Layer | Measure | Implementation |
|-------|---------|----------------|
| **API Communication** | TLS 1.3 | HTTPS only |
| **Request Signing** | HMAC-SHA256 | All requests signed |
| **Key Storage** | Environment variables | Secure storage |
| **Webhooks** | HMAC verification | SHA-256 |

---

## 10. TESTING STRATEGY

### 10.1 Test Credentials

| Wallet Type | Number | PIN |
|-------------|--------|-----|
| **Customer** | 01999999999 | 1234 |
| **Merchant** | 01998888888 | 1234 |

### 10.2 Test Scenarios

| Scenario | Steps | Expected Result |
|----------|-------|-----------------|
| **USSD Payment** | Dial *322*...# | Payment successful |
| **Invalid PIN** | Enter wrong PIN | Error message |
| **Insufficient Balance** | Pay with empty wallet | Insufficient funds error |
| **Refund** | Process refund | Refund successful |

---

## 11. ERROR HANDLING

| Code | Message | Action |
|------|---------|--------|
| 000 | Success | Continue |
| 101 | Invalid merchant | Check credentials |
| 102 | Invalid amount | Validate amount |
| 201 | Insufficient balance | Notify customer |
| 301 | Transaction timeout | Retry |
| 401 | Refund failed | Manual review |

---

## 12. APPENDICES

### Appendix A: Transaction Limits

| Type | Minimum | Maximum |
|------|---------|---------|
| Single Transaction | ৳10 | ৳50,000 |
| Daily Limit | - | ৳100,000 |

---

**END OF ROCKET INTEGRATION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | May 9, 2026 | Backend Developer | Initial version |
