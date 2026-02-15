# SMART DAIRY LTD.
## NAGAD INTEGRATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | E-003 |
| **Version** | 1.0 |
| **Date** | May 2, 2026 |
| **Author** | Backend Developer |
| **Owner** | Backend Developer |
| **Reviewer** | Tech Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Nagad Overview](#2-nagad-overview)
3. [Integration Architecture](#3-integration-architecture)
4. [Merchant Account Setup](#4-merchant-account-setup)
5. [API Implementation](#5-api-implementation)
6. [Checkout Flow](#6-checkout-flow)
7. [Webhook Handling](#7-webhook-handling)
8. [Refund Processing](#8-refund-processing)
9. [Security Implementation](#9-security-implementation)
10. [Testing Strategy](#10-testing-strategy)
11. [Error Handling](#11-error-handling)
12. [Production Deployment](#12-production-deployment)
13. [Appendices](#13-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive technical guidelines for integrating Nagad payment gateway into the Smart Dairy e-commerce platform. Nagad is Bangladesh Post Office's mobile financial service and one of the largest MFS providers in Bangladesh.

### 1.2 Scope

The integration covers:
- **Merchant Checkout API** - Secure payment initiation
- **Payment Verification** - Transaction confirmation
- **Refund API** - Partial and full refunds
- **Webhook Integration** - Real-time payment notifications
- **Transaction Query** - Payment status verification

### 1.3 Business Context

| Payment Scenario | Priority | Notes |
|-----------------|----------|-------|
| B2C Online Orders | Primary | Popular among rural customers |
| B2B Bulk Payments | Secondary | Higher transaction limits |
| Subscription Payments | Secondary | Recurring payment support |
| Refund Processing | Primary | Automated refund workflow |

---

## 2. NAGAD OVERVIEW

### 2.1 Nagad Merchant Checkout

Nagad Merchant Checkout allows customers to pay using their Nagad account through a secure, hosted checkout page. The integration uses API keys and merchant credentials for authentication.

**Key Features:**
- No sensitive data stored on merchant servers
- Support for all Nagad account types
- Real-time transaction confirmation
- Comprehensive refund capabilities
- Detailed transaction reporting

### 2.2 API Environments

| Environment | Base URL | Purpose |
|-------------|----------|---------|
| **Sandbox** | `https://sandbox.mynagad.com` | Development & testing |
| **Production** | `https://api.mynagad.com` | Live transactions |

### 2.3 Transaction Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         NAGAD PAYMENT FLOW                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐          │
│  │  Smart   │────▶│  Smart   │────▶│  Nagad   │────▶│  Nagad   │          │
│  │  Dairy   │     │  Dairy   │     │  Server  │     │  Payment │          │
│  │  Customer│     │  Backend │     │          │     │  Gateway │          │
│  └──────────┘     └──────────┘     └──────────┘     └──────────┘          │
│       │                │                │                │                  │
│       │ 1. Checkout    │                │                │                  │
│       │───────────────▶│                │                │                  │
│       │                │                │                │                  │
│       │                │ 2. Initialize  │                │                  │
│       │                │───────────────▶│                │                  │
│       │                │◀───────────────│                │                  │
│       │                │                │                │                  │
│       │ 3. Redirect URL│                │                │                  │
│       │◀───────────────│                │                │                  │
│       │                │                │                │                  │
│       │ 4. Pay (Nagad) │                │                │                  │
│       │─────────────────────────────────────────────────▶│                  │
│       │                │                │                │                  │
│       │ 5. Callback    │                │                │                  │
│       │◀─────────────────────────────────────────────────│                  │
│       │                │                │                │                  │
│       │                │ 6. Verify      │                │                  │
│       │                │───────────────▶│                │                  │
│       │                │◀───────────────│                │                  │
│       │                │                │                │                  │
│       │ 7. Confirm     │                │                │                  │
│       │◀───────────────│                │                │                  │
│       │                │                │                │                  │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. INTEGRATION ARCHITECTURE

### 3.1 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         NAGAD INTEGRATION ARCHITECTURE                       │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         PRESENTATION LAYER                             │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │ B2C Checkout │  │ Mobile App   │  │ B2B Portal   │               │  │
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
│  │  │  │ Payment  │  │ Nagad    │  │ Webhook  │  │ Refund   │       │  │  │
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
│  │  │  Nagad API       │        │  Smart Dairy     │                   │  │
│  │  │  (Merchant       │◄──────►│  ERP (Odoo)      │                   │  │
│  │  │   Checkout)      │        │                  │                   │  │
│  │  └──────────────────┘        └──────────────────┘                   │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. MERCHANT ACCOUNT SETUP

### 4.1 Sandbox Account Configuration

```python
# config/nagad_config.py

from pydantic_settings import BaseSettings
from typing import Literal

class NagadConfig(BaseSettings):
    """Nagad payment gateway configuration"""
    
    # Environment
    environment: Literal['sandbox', 'production'] = 'sandbox'
    
    # API Credentials (from Nagad merchant portal)
    merchant_id: str
    merchant_private_key: str
    nagad_public_key: str
    
    # API Settings
    api_version: str = "v1.0"
    
    # API Endpoints
    @property
    def base_url(self) -> str:
        if self.environment == 'sandbox':
            return "https://sandbox.mynagad.com/api"
        return "https://api.mynagad.com/api"
    
    # Transaction Settings
    currency: str = "BDT"
    min_transaction_amount: float = 10.0
    max_transaction_amount: float = 100000.0
    
    # Webhook
    webhook_secret: str
    callback_url: str = "https://api.smartdairybd.com/payments/nagad/callback"
    
    # Encryption Settings
    encryption_algorithm: str = "AES"
    signature_algorithm: str = "SHA256"
    
    # Retry Settings
    max_retries: int = 3
    retry_delay_seconds: int = 5
    
    class Config:
        env_prefix = "NAGAD_"

# Initialize config
nagad_config = NagadConfig()
```

### 4.2 Credential Security

```python
# Store credentials in environment variables
# .env file (DO NOT COMMIT TO GIT)

NAGAD_ENVIRONMENT=sandbox
NAGAD_MERCHANT_ID=your_merchant_id_here
NAGAD_MERCHANT_PRIVATE_KEY=-----BEGIN RSA PRIVATE KEY-----
...
-----END RSA PRIVATE KEY-----
NAGAD_NAGAD_PUBLIC_KEY=-----BEGIN PUBLIC KEY-----
...
-----END PUBLIC KEY-----
NAGAD_WEBHOOK_SECRET=your_webhook_secret_here
```

---

## 5. API IMPLEMENTATION

### 5.1 Nagad Service Implementation

```python
# services/nagad_service.py

import httpx
import json
import base64
import hashlib
import hmac
from datetime import datetime
from typing import Optional, Dict, Any
from Crypto.PublicKey import RSA
from Crypto.Signature import PKCS1_v1_5
from Crypto.Hash import SHA256
from Crypto.Cipher import PKCS1_OAEP

class NagadService:
    """Nagad Merchant Checkout API integration"""
    
    def __init__(self, config: NagadConfig):
        self.config = config
        self.http_client = httpx.AsyncClient(
            timeout=httpx.Timeout(30.0),
            headers={
                "Content-Type": "application/json",
                "Accept": "application/json",
            }
        )
        
        # Load keys
        self.merchant_private_key = RSA.import_key(config.merchant_private_key)
        self.nagad_public_key = RSA.import_key(config.nagad_public_key)
    
    def _generate_signature(self, data: str) -> str:
        """Generate RSA signature for request"""
        h = SHA256.new(data.encode())
        signer = PKCS1_v1_5.new(self.merchant_private_key)
        signature = signer.sign(h)
        return base64.b64encode(signature).decode()
    
    def _verify_signature(self, data: str, signature: str) -> bool:
        """Verify RSA signature from response"""
        try:
            h = SHA256.new(data.encode())
            verifier = PKCS1_v1_5.new(self.nagad_public_key)
            return verifier.verify(h, base64.b64decode(signature))
        except:
            return False
    
    def _generate_timestamp(self) -> str:
        """Generate ISO format timestamp"""
        return datetime.utcnow().strftime("%Y%m%d%H%M%S")
    
    def _generate_order_id(self, internal_order_id: str) -> str:
        """Generate Nagad-compatible order ID"""
        timestamp = datetime.utcnow().strftime("%Y%m%d%H%M%S")
        return f"SD{timestamp}{internal_order_id[-6:]}"
    
    async def _make_api_call(
        self,
        endpoint: str,
        payload: Dict[str, Any],
        headers: Optional[Dict] = None
    ) -> Dict[str, Any]:
        """Make authenticated API call to Nagad"""
        url = f"{self.config.base_url}/{endpoint}"
        
        # Generate signature
        data_string = json.dumps(payload, separators=(',', ':'))
        signature = self._generate_signature(data_string)
        
        request_headers = {
            "X-KM-Api-Version": self.config.api_version,
            "X-KM-Client-Type": "PC_WEB",
            "X-KM-Signature": signature,
            "X-KM-Timestamp": self._generate_timestamp(),
        }
        
        if headers:
            request_headers.update(headers)
        
        response = await self.http_client.post(
            url,
            json=payload,
            headers=request_headers
        )
        
        if response.status_code != 200:
            raise NagadAPIError(f"API call failed: {response.status_code} - {response.text}")
        
        result = response.json()
        
        # Verify response signature
        if "signature" in result:
            response_data = json.dumps({k: v for k, v in result.items() if k != "signature"})
            if not self._verify_signature(response_data, result["signature"]):
                raise NagadSecurityError("Invalid response signature")
        
        return result
    
    async def initialize_payment(
        self,
        order_id: str,
        amount: float,
        customer_phone: Optional[str] = None,
        callback_url: Optional[str] = None,
        additional_info: Optional[Dict] = None,
    ) -> Dict[str, Any]:
        """
        Initialize Nagad payment
        
        Args:
            order_id: Internal order identifier
            amount: Payment amount in BDT
            customer_phone: Customer phone number (optional)
            callback_url: URL for payment callback
            additional_info: Additional order information
        """
        # Validate amount
        if not (self.config.min_transaction_amount <= amount <= self.config.max_transaction_amount):
            raise ValueError(f"Amount must be between {self.config.min_transaction_amount} and {self.config.max_transaction_amount}")
        
        # Generate Nagad order ID
        nagad_order_id = self._generate_order_id(order_id)
        
        payload = {
            "merchantId": self.config.merchant_id,
            "orderId": nagad_order_id,
            "amount": str(amount),
            "currency": self.config.currency,
            "callbackUrl": callback_url or self.config.callback_url,
        }
        
        # Add customer info if provided
        if customer_phone:
            payload["customerPhoneNumber"] = self._format_phone(customer_phone)
        
        # Add additional info
        if additional_info:
            payload["additionInfo"] = json.dumps(additional_info)
        
        result = await self._make_api_call("checkout/initialize", payload)
        
        if result.get("errorCode"):
            raise NagadPaymentError(
                message=result.get("errorMessage", "Payment initialization failed"),
                code=result.get("errorCode"),
                nagad_response=result
            )
        
        return {
            "payment_reference_id": result["paymentReferenceId"],
            "nagad_order_id": nagad_order_id,
            "challenge": result.get("challenge"),
            "amount": amount,
            "currency": self.config.currency,
            "internal_order_id": order_id,
        }
    
    async def complete_payment(
        self,
        payment_reference_id: str,
        challenge: str,
        customer_phone: str,
    ) -> Dict[str, Any]:
        """
        Complete payment after customer authorization
        
        Args:
            payment_reference_id: Reference ID from initialize
            challenge: Challenge from initialize response
            customer_phone: Customer Nagad account phone
        """
        # Generate signature for challenge
        challenge_signature = self._generate_signature(challenge)
        
        payload = {
            "merchantId": self.config.merchant_id,
            "paymentReferenceId": payment_reference_id,
            "challenge": challenge,
            "challengeSignature": challenge_signature,
            "customerPhoneNumber": self._format_phone(customer_phone),
        }
        
        result = await self._make_api_call("checkout/complete", payload)
        
        if result.get("errorCode"):
            raise NagadPaymentError(
                message=result.get("errorMessage", "Payment completion failed"),
                code=result.get("errorCode"),
                nagad_response=result
            )
        
        return {
            "transaction_id": result.get("issuerPaymentRefNo"),
            "status": result.get("status"),
            "amount": result.get("amount"),
            "customer_phone": result.get("customerPhoneNumber"),
            "payment_reference_id": payment_reference_id,
        }
    
    async def verify_payment(self, payment_reference_id: str) -> Dict[str, Any]:
        """
        Verify payment status
        
        Args:
            payment_reference_id: Payment reference ID
        """
        payload = {
            "merchantId": self.config.merchant_id,
            "paymentReferenceId": payment_reference_id,
        }
        
        result = await self._make_api_call("checkout/verify", payload)
        
        if result.get("errorCode"):
            raise NagadPaymentError(
                message=result.get("errorMessage", "Payment verification failed"),
                code=result.get("errorCode"),
                nagad_response=result
            )
        
        return {
            "transaction_id": result.get("issuerPaymentRefNo"),
            "status": result.get("status"),
            "amount": result.get("amount"),
            "payment_reference_id": payment_reference_id,
            "verified_at": result.get("responseTimestamp"),
        }
    
    async def refund_payment(
        self,
        payment_reference_id: str,
        transaction_id: str,
        amount: float,
        reason: Optional[str] = None,
    ) -> Dict[str, Any]:
        """
        Refund a completed payment
        
        Args:
            payment_reference_id: Original payment reference
            transaction_id: Original transaction ID
            amount: Refund amount (can be partial)
            reason: Refund reason
        """
        payload = {
            "merchantId": self.config.merchant_id,
            "paymentReferenceId": payment_reference_id,
            "issuerPaymentRefNo": transaction_id,
            "amount": str(amount),
            "currency": self.config.currency,
            "refundReason": reason or "Customer request",
        }
        
        result = await self._make_api_call("checkout/refund", payload)
        
        if result.get("errorCode"):
            raise NagadRefundError(
                message=result.get("errorMessage", "Refund failed"),
                code=result.get("errorCode"),
                nagad_response=result
            )
        
        return {
            "refund_transaction_id": result.get("refundIssuerTxnNo"),
            "original_transaction_id": transaction_id,
            "amount": amount,
            "status": result.get("status"),
            "refunded_at": result.get("responseTimestamp"),
        }
    
    def _format_phone(self, phone: str) -> str:
        """Format phone number to Nagad format (8801XXXXXXXXX)"""
        # Remove all non-numeric characters
        phone = ''.join(filter(str.isdigit, phone))
        
        # Remove leading 880 or 0
        if phone.startswith('880'):
            phone = phone[3:]
        elif phone.startswith('0'):
            phone = phone[1:]
        
        # Add 880 prefix
        return f"880{phone}"


class NagadAPIError(Exception):
    """Base Nagad API error"""
    pass


class NagadPaymentError(NagadAPIError):
    """Payment operation error"""
    def __init__(self, message: str, code: str, nagad_response: dict):
        super().__init__(message)
        self.code = code
        self.nagad_response = nagad_response


class NagadSecurityError(NagadAPIError):
    """Security-related error"""
    pass


class NagadRefundError(NagadAPIError):
    """Refund operation error"""
    def __init__(self, message: str, code: str, nagad_response: dict):
        super().__init__(message)
        self.code = code
        self.nagad_response = nagad_response
```

### 5.2 Payment Controller

```python
# api/v1/payments/nagad_controller.py

from fastapi import APIRouter, Depends, HTTPException, BackgroundTasks, Request
from sqlalchemy.ext.asyncio import AsyncSession

router = APIRouter(prefix="/payments/nagad", tags=["Nagad Payments"])

class CreatePaymentRequest(BaseModel):
    order_id: str
    amount: float = Field(..., gt=0)
    customer_phone: str
    callback_url: Optional[str] = None

@router.post("/create")
async def create_nagad_payment(
    request: CreatePaymentRequest,
    background_tasks: BackgroundTasks,
    db: AsyncSession = Depends(get_db),
    nagad_service: NagadService = Depends(get_nagad_service),
    current_user: User = Depends(get_current_user_optional),
):
    """Create a new Nagad payment"""
    
    # Validate order
    order = await OrderRepository.get_by_id(db, request.order_id)
    if not order:
        raise HTTPException(status_code=404, detail="Order not found")
    
    if order.status != OrderStatus.PENDING:
        raise HTTPException(status_code=400, detail="Order is not pending")
    
    # Validate phone number
    if not validate_bangladesh_phone(request.customer_phone):
        raise HTTPException(status_code=400, detail="Invalid phone number")
    
    try:
        # Initialize payment with Nagad
        nagad_result = await nagad_service.initialize_payment(
            order_id=str(order.id),
            amount=float(order.total_amount),
            customer_phone=request.customer_phone,
            callback_url=request.callback_url,
            additional_info={
                "order_number": order.order_number,
                "customer_name": current_user.name if current_user else "Guest",
            }
        )
        
        # Save payment record
        payment = Payment(
            order_id=order.id,
            user_id=current_user.id if current_user else None,
            provider=PaymentProvider.NAGAD,
            provider_payment_id=nagad_result["payment_reference_id"],
            provider_order_id=nagad_result["nagad_order_id"],
            amount=nagad_result["amount"],
            currency=nagad_result["currency"],
            status=PaymentStatus.PENDING,
            challenge=nagad_result.get("challenge"),
            customer_phone=request.customer_phone,
            expires_at=datetime.utcnow() + timedelta(minutes=30),
        )
        
        await PaymentRepository.create(db, payment)
        
        return {
            "payment_reference_id": nagad_result["payment_reference_id"],
            "nagad_order_id": nagad_result["nagad_order_id"],
            "amount": nagad_result["amount"],
            "currency": nagad_result["currency"],
            "customer_phone": request.customer_phone,
            "status": "pending",
            "challenge": nagad_result.get("challenge"),
        }
        
    except NagadPaymentError as e:
        logger.error(f"Nagad payment creation failed: {e.message}")
        raise HTTPException(status_code=400, detail=e.message)


@router.post("/complete")
async def complete_nagad_payment(
    payment_reference_id: str,
    customer_phone: str,
    db: AsyncSession = Depends(get_db),
    nagad_service: NagadService = Depends(get_nagad_service),
):
    """Complete payment after customer authorization"""
    
    # Get payment record
    payment = await PaymentRepository.get_by_provider_id(
        db, payment_reference_id, PaymentProvider.NAGAD
    )
    
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    if payment.status != PaymentStatus.PENDING:
        raise HTTPException(status_code=400, detail="Payment is not pending")
    
    try:
        # Complete payment with Nagad
        result = await nagad_service.complete_payment(
            payment_reference_id=payment_reference_id,
            challenge=payment.challenge,
            customer_phone=customer_phone,
        )
        
        # Update payment record
        payment.status = PaymentStatus.COMPLETED
        payment.provider_transaction_id = result["transaction_id"]
        payment.completed_at = datetime.utcnow()
        await PaymentRepository.update(db, payment)
        
        # Update order status
        await OrderRepository.update_status(db, payment.order_id, OrderStatus.PAID)
        
        # Process order
        await process_successful_payment(payment)
        
        return {
            "status": "success",
            "payment_reference_id": payment_reference_id,
            "transaction_id": result["transaction_id"],
            "amount": result["amount"],
            "message": "Payment completed successfully",
        }
        
    except NagadPaymentError as e:
        # Update payment as failed
        payment.status = PaymentStatus.FAILED
        payment.metadata["error"] = {"code": e.code, "message": e.message}
        await PaymentRepository.update(db, payment)
        
        logger.error(f"Payment completion failed: {e.message}")
        raise HTTPException(status_code=400, detail=e.message)


@router.get("/verify/{payment_reference_id}")
async def verify_nagad_payment(
    payment_reference_id: str,
    db: AsyncSession = Depends(get_db),
    nagad_service: NagadService = Depends(get_nagad_service),
):
    """Verify payment status with Nagad"""
    
    payment = await PaymentRepository.get_by_provider_id(
        db, payment_reference_id, PaymentProvider.NAGAD
    )
    
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    try:
        result = await nagad_service.verify_payment(payment_reference_id)
        
        # Update if status changed
        if result["status"] == "Success" and payment.status != PaymentStatus.COMPLETED:
            payment.status = PaymentStatus.COMPLETED
            payment.provider_transaction_id = result["transaction_id"]
            payment.completed_at = datetime.utcnow()
            await PaymentRepository.update(db, payment)
            
            await OrderRepository.update_status(db, payment.order_id, OrderStatus.PAID)
        
        return {
            "payment_reference_id": payment_reference_id,
            "status": result["status"],
            "transaction_id": result.get("transaction_id"),
            "amount": result["amount"],
        }
        
    except NagadPaymentError as e:
        raise HTTPException(status_code=400, detail=e.message)
```

---

## 6. CHECKOUT FLOW

### 6.1 Frontend Integration

```javascript
// Frontend JavaScript integration

class NagadCheckout {
  constructor(config) {
    this.apiBaseUrl = config.apiBaseUrl;
  }

  async initiatePayment(orderId, amount, customerPhone) {
    try {
      const response = await fetch(`${this.apiBaseUrl}/payments/nagad/create`, {
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

      // Store payment reference for later
      localStorage.setItem('nagad_payment_ref', data.payment_reference_id);
      
      // Show Nagad payment instructions
      this.showNagadInstructions(data.payment_reference_id, customerPhone);
      
      return data;

    } catch (error) {
      console.error('Nagad payment initiation failed:', error);
      throw error;
    }
  }

  showNagadInstructions(paymentReferenceId, customerPhone) {
    // Display modal with instructions
    const modal = document.createElement('div');
    modal.innerHTML = `
      <div class="nagad-modal">
        <h3>Complete Payment with Nagad</h3>
        <p>Payment Reference: ${paymentReferenceId}</p>
        <ol>
          <li>Dial *167# from your phone</li>
          <li>Select "Merchant Pay"</li>
          <li>Enter Merchant ID: YOUR_MERCHANT_ID</li>
          <li>Enter Reference: ${paymentReferenceId}</li>
          <li>Confirm payment with PIN</li>
        </ol>
        <button onclick="nagadCheckout.checkStatus('${paymentReferenceId}')">
          I've Completed Payment
        </button>
      </div>
    `;
    document.body.appendChild(modal);
  }

  async checkStatus(paymentReferenceId) {
    try {
      const response = await fetch(
        `${this.apiBaseUrl}/payments/nagad/verify/${paymentReferenceId}`,
        {
          headers: {
            'Authorization': `Bearer ${this.getAuthToken()}`,
          },
        }
      );

      const result = await response.json();

      if (result.status === 'Success') {
        window.location.href = '/order/success';
      } else {
        alert('Payment not completed yet. Please complete the payment and try again.');
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
# api/v1/webhooks/nagad_webhook.py

from fastapi import APIRouter, Request, HTTPException, Header
import hmac
import hashlib

router = APIRouter(prefix="/webhooks/nagad", tags=["Nagad Webhooks"])

@router.post("/payment")
async def nagad_payment_webhook(
    request: Request,
    x_km_signature: str = Header(None),
    db: AsyncSession = Depends(get_db),
):
    """Handle Nagad payment webhooks"""
    
    body = await request.body()
    payload = await request.json()
    
    # Verify webhook signature
    if not verify_nagad_signature(body, x_km_signature):
        raise HTTPException(status_code=401, detail="Invalid signature")
    
    payment_reference_id = payload.get("paymentReferenceId")
    status = payload.get("status")
    
    logger.info(f"Received Nagad webhook: {payment_reference_id} - {status}")
    
    # Get payment record
    payment = await PaymentRepository.get_by_provider_id(
        db, payment_reference_id, PaymentProvider.NAGAD
    )
    
    if not payment:
        logger.error(f"Payment not found: {payment_reference_id}")
        return {"status": "received"}
    
    if status == "Success":
        # Update payment
        payment.status = PaymentStatus.COMPLETED
        payment.provider_transaction_id = payload.get("issuerPaymentRefNo")
        payment.completed_at = datetime.utcnow()
        await PaymentRepository.update(db, payment)
        
        # Update order
        await OrderRepository.update_status(db, payment.order_id, OrderStatus.PAID)
        
        # Send confirmation
        await send_payment_confirmation_email(payment)
        
    elif status == "Failed":
        payment.status = PaymentStatus.FAILED
        payment.metadata["failure_reason"] = payload.get("errorMessage")
        await PaymentRepository.update(db, payment)
    
    return {"status": "processed"}

def verify_nagad_signature(body: bytes, signature: str) -> bool:
    """Verify Nagad webhook signature"""
    if not signature:
        return False
    
    expected = hmac.new(
        nagad_config.webhook_secret.encode(),
        body,
        hashlib.sha256
    ).hexdigest()
    
    return hmac.compare_digest(signature, expected)
```

---

## 8. REFUND PROCESSING

```python
@router.post("/refund")
async def refund_nagad_payment(
    request: RefundRequest,
    db: AsyncSession = Depends(get_db),
    nagad_service: NagadService = Depends(get_nagad_service),
    current_user: User = Depends(get_current_user_admin),
):
    """Process refund for Nagad payment"""
    
    payment = await PaymentRepository.get_by_provider_id(
        db, request.payment_reference_id, PaymentProvider.NAGAD
    )
    
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    if payment.status != PaymentStatus.COMPLETED:
        raise HTTPException(status_code=400, detail="Payment not completed")
    
    try:
        result = await nagad_service.refund_payment(
            payment_reference_id=request.payment_reference_id,
            transaction_id=payment.provider_transaction_id,
            amount=request.amount,
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
        
    except NagadRefundError as e:
        logger.error(f"Refund failed: {e.message}")
        raise HTTPException(status_code=400, detail=e.message)
```

---

## 9. SECURITY IMPLEMENTATION

| Layer | Measure | Implementation |
|-------|---------|----------------|
| **API Communication** | TLS 1.3 | HTTPS only |
| **Request Signing** | RSA-SHA256 | All requests signed |
| **Response Verification** | Signature check | Verify all responses |
| **Key Storage** | Environment variables | Never in code |
| **Webhooks** | HMAC verification | SHA-256 |

---

## 10. TESTING STRATEGY

### 10.1 Sandbox Test Credentials

| Account Type | Phone Number | PIN | OTP |
|--------------|--------------|-----|-----|
| **Customer** | 01929999999 | 1234 | 123456 |
| **Merchant** | 01928888888 | 1234 | 123456 |

### 10.2 Test Scenarios

| Scenario | Steps | Expected Result |
|----------|-------|-----------------|
| **Successful Payment** | Initialize → Complete → Verify | Payment successful |
| **Insufficient Balance** | Attempt payment with low balance | Error code 2023 |
| **Invalid PIN** | Enter wrong PIN | Error code 2033 |
| **Timeout** | Wait > 5 minutes | Transaction expired |
| **Partial Refund** | Refund partial amount | Refund successful |

---

## 11. ERROR HANDLING

| Code | Message | Action |
|------|---------|--------|
| 000 | Success | Continue |
| 202 | Invalid credentials | Check keys |
| 203 | Insufficient balance | Notify customer |
| 205 | Transaction timeout | Retry |
| 208 | Already processed | Return cached result |
| 212 | Refund failed | Manual review |

---

## 12. PRODUCTION DEPLOYMENT

### 12.1 Go-Live Checklist

- [ ] Production merchant account activated
- [ ] Production keys configured
- [ ] Webhook URL whitelisted
- [ ] SSL certificate valid
- [ ] Error monitoring configured
- [ ] Support team trained on Nagad issues

---

**END OF NAGAD INTEGRATION GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | May 2, 2026 | Backend Developer | Initial version |
