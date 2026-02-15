# E-006: SMS Gateway Integration Guide

## Smart Dairy Ltd. - Smart Web Portal System

---

| Field | Value |
|-------|-------|
| **Document ID** | E-006 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Status** | Draft |
| **Classification** | Internal Use |

---

## Document Control

| Version | Date | Author | Changes | Approved By |
|---------|------|--------|---------|-------------|
| 1.0 | 2026-01-31 | Integration Engineer | Initial version | CTO |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Provider Selection](#2-provider-selection)
3. [Prerequisites](#3-prerequisites)
4. [API Integration](#4-api-integration)
5. [Message Types](#5-message-types)
6. [Implementation](#6-implementation)
7. [Template Management](#7-template-management)
8. [Delivery Tracking](#8-delivery-tracking)
9. [Rate Limiting](#9-rate-limiting)
10. [Testing](#10-testing)
11. [Compliance](#11-compliance)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides a comprehensive guide for integrating SMS Gateway services into the Smart Dairy Web Portal System. It covers the technical implementation, best practices, and compliance requirements for SMS-based communication with customers, farmers, and delivery personnel.

### 1.2 Scope

This guide covers:
- SMS provider evaluation and selection
- REST API integration patterns
- Message template management
- Asynchronous message processing
- Delivery tracking and reporting
- Rate limiting and queue management
- Regulatory compliance (BTRC)

### 1.3 Use Cases

The SMS Gateway integration supports the following business scenarios:

| Use Case | Description | Frequency |
|----------|-------------|-----------|
| **OTP for Registration** | One-time passwords for user verification during signup | Per registration |
| **Password Reset** | Secure reset codes for forgotten passwords | On demand |
| **Order Confirmation** | Instant confirmation when order is placed | Per order |
| **Order Status Updates** | Notifications for status changes (confirmed, processing, shipped) | Per status change |
| **Delivery Notifications** | Updates when order is out for delivery or delivered | Per delivery event |
| **Payment Reminders** | Reminders for pending payments or subscription renewals | Scheduled |
| **Marketing Campaigns** | Promotional offers and product announcements | Campaign-based |
| **Farmer Notifications** | Collection schedules, payment updates for dairy farmers | Scheduled |

### 1.4 SMS Message Categories

```
┌─────────────────────────────────────────────────────────────┐
│                    SMS MESSAGE FLOW                         │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│   ┌──────────┐    ┌──────────┐    ┌──────────┐            │
│   │   OTP    │    │Transactional│   │Promotional│            │
│   │ Messages │    │  Messages   │   │ Messages  │            │
│   └────┬─────┘    └─────┬─────┘    └─────┬─────┘            │
│        │                │                │                   │
│        ▼                ▼                ▼                   │
│   ┌──────────┐    ┌──────────┐    ┌──────────┐            │
│   │ Priority │    │ Priority │    │ Priority │            │
│   │   HIGH   │    │  MEDIUM  │    │   LOW    │            │
│   └──────────┘    └──────────┘    └──────────┘            │
│                                                             │
│   • Registration      • Order Confirm    • Marketing       │
│   • Password Reset    • Status Updates   • Campaigns       │
│   • 2FA Auth          • Delivery         • Promotions      │
│                       • Payment          • Newsletters     │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. Provider Selection

### 2.1 Bangladesh SMS Gateway Providers

| Provider | API Type | Delivery Rate | Bengali Unicode | Pricing (per SMS) | Reliability |
|----------|----------|---------------|-----------------|-------------------|-------------|
| **Adn Telecom** | REST API | 95%+ | ✅ Full Support | BDT 0.25 - 0.40 | ⭐⭐⭐⭐⭐ |
| **SMS Banglahn** | REST API | 92%+ | ✅ Full Support | BDT 0.20 - 0.35 | ⭐⭐⭐⭐ |
| **BoomCast** | REST API | 94%+ | ✅ Full Support | BDT 0.22 - 0.38 | ⭐⭐⭐⭐⭐ |
| **SSL Wireless** | REST API | 93%+ | ✅ Full Support | BDT 0.23 - 0.37 | ⭐⭐⭐⭐ |
| **Robi/Airtel** | SMPP/REST | 90%+ | ✅ Full Support | BDT 0.30 - 0.45 | ⭐⭐⭐⭐ |

### 2.2 Provider Comparison Matrix

#### 2.2.1 Adn Telecom

**Strengths:**
- Highest delivery rate in Bangladesh
- Excellent API documentation
- 24/7 technical support
- Real-time delivery reports
- Masking (Sender ID) support

**Limitations:**
- Higher pricing tier
- Requires corporate documentation

**Best For:** OTP, critical transactional messages

```python
# Adn Telecom Configuration
ADN_CONFIG = {
    "base_url": "https://api.adnsms.com",
    "api_key": "your_api_key",
    "api_secret": "your_api_secret",
    "sender_id": "SmartDairy",  # Registered sender ID
    "timeout": 30,
}
```

#### 2.2.2 SMS Banglahn

**Strengths:**
- Cost-effective pricing
- Simple REST API
- Good delivery rates
- Quick onboarding

**Limitations:**
- Limited template management features
- Basic reporting

**Best For:** Marketing campaigns, bulk notifications

```python
# SMS Banglahn Configuration
SMSBANGLA_CONFIG = {
    "base_url": "https://api.smsbanglahn.com",
    "api_key": "your_api_key",
    "sender_id": "8809612345678",  # Or registered masking
    "timeout": 30,
}
```

#### 2.2.3 BoomCast

**Strengths:**
- Balanced price-performance
- Advanced analytics dashboard
- Multi-channel support (SMS, Viber, WhatsApp)
- Flexible API

**Limitations:**
- Complex pricing structure
- Learning curve for advanced features

**Best For:** Mixed transactional and promotional needs

```python
# BoomCast Configuration
BOOMCAST_CONFIG = {
    "base_url": "https://api.boomcast.io",
    "api_key": "your_api_key",
    "api_secret": "your_api_secret",
    "sender_id": "SmartDairy",
    "timeout": 30,
}
```

### 2.3 Recommended Provider Strategy

```
┌────────────────────────────────────────────────────────────────┐
│              MULTI-PROVIDER FALLBACK STRATEGY                  │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│   Primary: Adn Telecom (OTP, Critical Transactional)          │
│        │                                                       │
│        ├── Fallback 1: BoomCast (if Adn fails)                │
│        │                                                       │
│        └── Fallback 2: SMS Banglahn (bulk/promotional)        │
│                                                                │
│   Routing Logic:                                               │
│   ┌─────────────────┐    ┌─────────────────┐                 │
│   │ Message Priority │───▶│ Select Provider │                 │
│   └─────────────────┘    └─────────────────┘                 │
│           │                                                    │
│           ├── HIGH (OTP) ───────▶ Adn Telecom                │
│           ├── MEDIUM (Orders) ──▶ BoomCast / Adn             │
│           └── LOW (Marketing) ──▶ SMS Banglahn               │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

### 2.4 Provider Selection Decision

**Primary Recommendation: Adn Telecom**

Rationale:
1. Highest delivery rate critical for OTP and transactional messages
2. Excellent support for Bengali Unicode (essential for local customers)
3. Reliable masking/Sender ID support for brand recognition
4. Comprehensive delivery reporting for audit trails
5. 24/7 support aligns with Smart Dairy's operational hours

---

## 3. Prerequisites

### 3.1 API Credentials

Before integration, obtain the following from your SMS provider:

| Credential | Description | Security Level |
|------------|-------------|----------------|
| **API Key** | Unique identifier for your account | 🔴 High |
| **API Secret** | Authentication secret | 🔴 High |
| **Sender ID** | Display name (e.g., "SmartDairy") | 🟡 Medium |
| **Account SID** | Account identifier (some providers) | 🟡 Medium |

### 3.2 Sender ID Registration

#### 3.2.1 Requirements for Bangladesh (BTRC)

```
Sender ID Registration Checklist:
┌─────────────────────────────────────────────────────────────┐
│ □ Company Trade License                                     │
│ □ TIN Certificate                                           │
│ □ VAT Registration (if applicable)                          │
│ □ Company Authorization Letter                              │
│ □ Nature of Business Declaration                            │
│ □ Sample SMS Content (all templates)                        │
│ □ Approved Letterhead                                       │
│ □ Authorized Signatory ID Proof                             │
│ □ Application Form (Provider-specific)                      │
└─────────────────────────────────────────────────────────────┘

Processing Time: 5-10 business days
Cost: BDT 5,000 - 15,000 (one-time, varies by provider)
```

#### 3.2.2 Sender ID Options

| Type | Format | Example | Use Case |
|------|--------|---------|----------|
| **Brand Masking** | Alphabetic | "SmartDairy" | Primary brand identity |
| **Short Code** | 5-6 digits | "12345" | Premium services |
| **Long Code** | 11 digits | "8809612345678" | Default/generic |

### 3.3 Template Registration

#### 3.3.1 Why Templates Are Required

Bangladesh Telecommunication Regulatory Commission (BTRC) mandates pre-registration of SMS templates to prevent spam and ensure compliance.

#### 3.3.2 Template Registration Process

```
Template Registration Flow:
┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   Create    │───▶│   Submit    │───▶│   BTRC      │───▶│   Approved  │
│  Template   │    │  to Provider│    │   Review    │    │   / Rejected│
└─────────────┘    └─────────────┘    └─────────────┘    └─────────────┘
      │                                                   │
      │              Processing: 3-7 days                 │
      └───────────────────────────────────────────────────┘
```

#### 3.3.3 Required Template Categories

| Category | Count | Examples |
|----------|-------|----------|
| **OTP/Security** | 3 | Registration OTP, Password Reset, 2FA |
| **Order Notifications** | 5 | Confirmation, Processing, Shipped, Delivered, Cancelled |
| **Payment** | 3 | Payment Due, Payment Received, Refund |
| **Delivery** | 4 | Out for Delivery, Arriving Today, Delivered, Failed |
| **Marketing** | 5 | Promotions, New Products, Discounts, Referral, Newsletter |
| **Farmer Communications** | 4 | Collection Schedule, Payment, Quality Alert, Training |

### 3.4 Infrastructure Requirements

#### 3.4.1 Server Requirements

```python
# SMS Service Infrastructure Specs
SMS_SERVICE_SPECS = {
    "cpu_cores": 2,
    "memory_gb": 4,
    "storage_gb": 50,
    "network_mbps": 100,
    "os": "Ubuntu 22.04 LTS / Windows Server 2019+",
    "python_version": "3.10+",
    "redis_required": True,
    "celery_required": True,
}
```

#### 3.4.2 Dependencies

```bash
# Python Dependencies
pip install celery[redis]>=5.3.0
pip install requests>=2.31.0
pip install aiohttp>=3.9.0
pip install pydantic>=2.5.0
pip install tenacity>=8.2.0
pip install python-dotenv>=1.0.0
```

---

## 4. API Integration

### 4.1 REST API Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                     SMS API ARCHITECTURE                            │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│   ┌──────────────┐         ┌──────────────┐         ┌─────────────┐│
│   │  Smart Dairy │         │   SMS API    │         │  Provider   ││
│   │    Portal    │◄───────►│   Gateway    │◄───────►│   Server    ││
│   └──────────────┘         └──────────────┘         └─────────────┘│
│          │                        │                        │       │
│          │ HTTP/JSON              │ HTTPS/REST             │       │
│          │                        │                        │       │
│          ▼                        ▼                        ▼       │
│   ┌──────────────┐         ┌──────────────┐         ┌─────────────┐│
│   │   Celery     │         │   Retry      │         │  Delivery   ││
│   │   Workers    │         │   Logic      │         │   Reports   ││
│   └──────────────┘         └──────────────┘         └─────────────┘│
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 4.2 Authentication Methods

#### 4.2.1 API Key Authentication (Adn Telecom)

```python
"""
API Key Authentication for Adn Telecom
"""
import requests
import hashlib
import hmac
from datetime import datetime

class AdnAuth:
    """Authentication handler for Adn Telecom SMS API"""
    
    def __init__(self, api_key: str, api_secret: str):
        self.api_key = api_key
        self.api_secret = api_secret
    
    def generate_signature(self, timestamp: str) -> str:
        """Generate HMAC-SHA256 signature"""
        message = f"{self.api_key}{timestamp}"
        signature = hmac.new(
            self.api_secret.encode(),
            message.encode(),
            hashlib.sha256
        ).hexdigest()
        return signature
    
    def get_headers(self) -> dict:
        """Get authentication headers"""
        timestamp = datetime.utcnow().strftime("%Y-%m-%d %H:%M:%S")
        signature = self.generate_signature(timestamp)
        
        return {
            "Authorization": f"Bearer {self.api_key}",
            "X-API-Signature": signature,
            "X-Timestamp": timestamp,
            "Content-Type": "application/json",
        }

# Usage
auth = AdnAuth(
    api_key="your_api_key",
    api_secret="your_api_secret"
)
headers = auth.get_headers()
```

#### 4.2.2 JWT Authentication (BoomCast)

```python
"""
JWT Authentication for BoomCast
"""
import jwt
import time
from datetime import datetime, timedelta

class BoomCastAuth:
    """JWT Authentication handler for BoomCast API"""
    
    def __init__(self, api_key: str, api_secret: str):
        self.api_key = api_key
        self.api_secret = api_secret
        self._token = None
        self._token_expiry = None
    
    def generate_token(self) -> str:
        """Generate JWT token"""
        payload = {
            "iss": self.api_key,
            "iat": datetime.utcnow(),
            "exp": datetime.utcnow() + timedelta(hours=1),
            "sub": "sms_api_access"
        }
        
        token = jwt.encode(payload, self.api_secret, algorithm="HS256")
        self._token = token
        self._token_expiry = payload["exp"]
        return token
    
    def get_token(self) -> str:
        """Get valid token (refresh if expired)"""
        if not self._token or datetime.utcnow() >= self._token_expiry:
            return self.generate_token()
        return self._token
    
    def get_headers(self) -> dict:
        """Get authentication headers"""
        return {
            "Authorization": f"Bearer {self.get_token()}",
            "Content-Type": "application/json",
        }
```

### 4.3 API Endpoints

#### 4.3.1 Adn Telecom Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/secure/send-sms` | POST | Send single SMS |
| `/api/v1/secure/send-sms/bulk` | POST | Send bulk SMS |
| `/api/v1/secure/status/{message_id}` | GET | Check delivery status |
| `/api/v1/secure/balance` | GET | Check account balance |
| `/api/v1/secure/templates` | GET | List registered templates |

#### 4.3.2 Request/Response Examples

**Send Single SMS:**

```python
# Request
{
    "api_key": "your_api_key",
    "api_secret": "your_api_secret",
    "request_type": "SINGLE_SMS",
    "message_type": "UNICODE",  # or TEXT
    "mobile": "8801712345678",
    "message_body": "Your Smart Dairy OTP is: 123456",
    "sender_id": "SmartDairy",
    "template_id": "OTP_TEMPLATE_001",
    "campaign_name": "User_Registration"
}

# Success Response
{
    "status": "SUCCESS",
    "message": "SMS sent successfully",
    "message_id": "ADN20260131123456789",
    "reference_id": "REF123456",
    "timestamp": "2026-01-31T10:30:00Z",
    "parts": 1,
    "cost": 0.35
}

# Error Response
{
    "status": "FAILED",
    "error_code": "INVALID_TEMPLATE",
    "message": "Template ID not registered or approved",
    "timestamp": "2026-01-31T10:30:00Z"
}
```

**Send Bulk SMS:**

```python
# Request
{
    "api_key": "your_api_key",
    "api_secret": "your_api_secret",
    "request_type": "BULK_SMS",
    "message_type": "UNICODE",
    "sender_id": "SmartDairy",
    "template_id": "PROMO_TEMPLATE_001",
    "campaign_name": "Winter_Sale_2026",
    "schedule_time": "2026-02-01T09:00:00Z",  # Optional
    "messages": [
        {
            "mobile": "8801712345678",
            "message_body": "Hi John, get 20% off on fresh milk!",
            "custom_id": "USER_001"
        },
        {
            "mobile": "8801812345678",
            "message_body": "Hi Jane, get 20% off on fresh milk!",
            "custom_id": "USER_002"
        }
    ]
}

# Success Response
{
    "status": "SUCCESS",
    "campaign_id": "CAMP20260131001",
    "total_messages": 2,
    "accepted": 2,
    "rejected": 0,
    "estimated_cost": 0.70,
    "timestamp": "2026-01-31T10:30:00Z"
}
```

### 4.4 Error Codes

| Code | Description | Resolution |
|------|-------------|------------|
| `INVALID_API_KEY` | API key is invalid or expired | Check credentials |
| `INVALID_TEMPLATE` | Template not registered/approved | Register template |
| `INVALID_SENDER_ID` | Sender ID not approved | Complete registration |
| `INSUFFICIENT_BALANCE` | Account balance too low | Recharge account |
| `RATE_LIMIT_EXCEEDED` | Too many requests | Implement backoff |
| `INVALID_MOBILE` | Phone number format error | Validate numbers |
| `BLACKLISTED_NUMBER` | Number in DND list | Respect opt-out |
| `MESSAGE_TOO_LONG` | Exceeds character limit | Split or shorten |

---

## 5. Message Types

### 5.1 Message Categories

```
┌─────────────────────────────────────────────────────────────────────┐
│                      SMS MESSAGE TYPES                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐   │
│  │   OTP Messages  │  │  Transactional  │  │   Promotional   │   │
│  │                 │  │                 │  │                 │   │
│  │ • Registration  │  │ • Order Confirm │  │ • Marketing     │   │
│  │ • Password Reset│  │ • Status Update │  │ • Discounts     │   │
│  │ • 2FA Auth      │  │ • Delivery      │  │ • New Products  │   │
│  │                 │  │ • Payment       │  │ • Referrals     │   │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘   │
│                                                                     │
│  Priority: HIGH ─────► MEDIUM ─────► LOW                           │
│  Delivery: Instant ──► Within 1 min ─► Within 5 min                │
│  Time Window: 24/7 ──► 8AM-10PM ───► 9AM-8PM                       │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 5.2 OTP Messages

#### 5.2.1 OTP Templates

```python
# OTP Template Definitions
OTP_TEMPLATES = {
    "REGISTRATION_OTP": {
        "template_id": "OTP_REG_001",
        "template_text": "আপনার Smart Dairy ভেরিফিকেশন কোড: {otp}। কোডটি ৫ মিনিটের জন্য বৈধ। কাউকে শেয়ার করবেন না।",
        "template_text_en": "Your Smart Dairy verification code is: {otp}. Valid for 5 minutes. Do not share.",
        "length": 6,
        "expiry_minutes": 5,
        "max_attempts": 3,
    },
    "PASSWORD_RESET_OTP": {
        "template_id": "OTP_PASS_001",
        "template_text": "পাসওয়ার্ড রিসেট কোড: {otp}। এটি Smart Dairy-এর কাউকে দেবেন না।",
        "template_text_en": "Password reset code: {otp}. Never share this with anyone at Smart Dairy.",
        "length": 6,
        "expiry_minutes": 10,
        "max_attempts": 3,
    },
    "LOGIN_OTP": {
        "template_id": "OTP_LOGIN_001",
        "template_text": "Smart Dairy লগইন কোড: {otp}। এই কোডটি কেউ চাইলে দেবেন না।",
        "template_text_en": "Smart Dairy login code: {otp}. Never share this code if someone asks.",
        "length": 6,
        "expiry_minutes": 5,
        "max_attempts": 3,
    },
}
```

#### 5.2.2 OTP Service Implementation

```python
"""
OTP Service for Smart Dairy
Handles generation, storage, and verification of OTPs
"""
import random
import string
from datetime import datetime, timedelta
from typing import Optional, Tuple
import redis
from pydantic import BaseModel

class OTPConfig(BaseModel):
    """OTP Configuration"""
    length: int = 6
    expiry_minutes: int = 5
    max_attempts: int = 3
    cooldown_minutes: int = 1

class OTPService:
    """Service for managing OTP operations"""
    
    def __init__(self, redis_client: redis.Redis, config: OTPConfig = None):
        self.redis = redis_client
        self.config = config or OTPConfig()
    
    def _generate_otp(self, length: int = None) -> str:
        """Generate secure random OTP"""
        length = length or self.config.length
        # Use digits only for easy mobile input
        return ''.join(random.choices(string.digits, k=length))
    
    def _get_redis_key(self, phone: str, purpose: str) -> str:
        """Generate Redis key for OTP storage"""
        return f"otp:{purpose}:{phone}"
    
    def _get_cooldown_key(self, phone: str, purpose: str) -> str:
        """Generate Redis key for cooldown tracking"""
        return f"otp:cooldown:{purpose}:{phone}"
    
    async def generate_and_send(
        self,
        phone: str,
        purpose: str,
        template_id: str,
        language: str = "bn"  # 'bn' for Bengali, 'en' for English
    ) -> Tuple[bool, str]:
        """
        Generate OTP and send via SMS
        
        Args:
            phone: Phone number (880XXXXXXXXXX format)
            purpose: OTP purpose (registration, password_reset, login)
            template_id: Registered template ID
            language: Message language
            
        Returns:
            Tuple of (success, message)
        """
        # Check cooldown
        cooldown_key = self._get_cooldown_key(phone, purpose)
        if self.redis.exists(cooldown_key):
            ttl = self.redis.ttl(cooldown_key)
            return False, f"Please wait {ttl} seconds before requesting a new OTP"
        
        # Generate OTP
        otp = self._generate_otp()
        
        # Store in Redis with expiry
        otp_key = self._get_redis_key(phone, purpose)
        otp_data = {
            "code": otp,
            "attempts": 0,
            "created_at": datetime.utcnow().isoformat()
        }
        
        expiry_seconds = self.config.expiry_minutes * 60
        self.redis.hset(otp_key, mapping=otp_data)
        self.redis.expire(otp_key, expiry_seconds)
        
        # Set cooldown
        self.redis.setex(cooldown_key, self.config.cooldown_minutes * 60, "1")
        
        # Send SMS (via Celery task)
        from celery_tasks import send_otp_sms.delay
        send_otp_sms.delay(phone, otp, template_id, language)
        
        return True, "OTP sent successfully"
    
    async def verify(
        self,
        phone: str,
        purpose: str,
        code: str
    ) -> Tuple[bool, str]:
        """
        Verify OTP code
        
        Args:
            phone: Phone number
            purpose: OTP purpose
            code: OTP code to verify
            
        Returns:
            Tuple of (is_valid, message)
        """
        otp_key = self._get_redis_key(phone, purpose)
        
        # Check if OTP exists
        if not self.redis.exists(otp_key):
            return False, "OTP expired or not found. Please request a new one."
        
        # Get OTP data
        otp_data = self.redis.hgetall(otp_key)
        stored_code = otp_data.get(b"code", b"").decode()
        attempts = int(otp_data.get(b"attempts", 0))
        
        # Check max attempts
        if attempts >= self.config.max_attempts:
            self.redis.delete(otp_key)
            return False, "Maximum attempts exceeded. Please request a new OTP."
        
        # Verify code
        if code != stored_code:
            # Increment attempts
            self.redis.hincrby(otp_key, "attempts", 1)
            remaining = self.config.max_attempts - attempts - 1
            return False, f"Invalid OTP. {remaining} attempts remaining."
        
        # Success - delete OTP
        self.redis.delete(otp_key)
        return True, "OTP verified successfully"
    
    async def resend(
        self,
        phone: str,
        purpose: str,
        template_id: str,
        language: str = "bn"
    ) -> Tuple[bool, str]:
        """Resend OTP to user"""
        # Check if previous OTP exists
        otp_key = self._get_redis_key(phone, purpose)
        
        if self.redis.exists(otp_key):
            # Get remaining time
            ttl = self.redis.ttl(otp_key)
            if ttl > 0:
                # Allow resend with same code if not expired
                otp_data = self.redis.hgetall(otp_key)
                otp = otp_data.get(b"code", b"").decode()
                
                # Send SMS
                from celery_tasks import send_otp_sms.delay
                send_otp_sms.delay(phone, otp, template_id, language)
                
                return True, "OTP resent successfully"
        
        # Generate new OTP
        return await self.generate_and_send(phone, purpose, template_id, language)
```

### 5.3 Transactional Messages

#### 5.3.1 Order Notification Templates

```python
# Order Notification Templates
ORDER_TEMPLATES = {
    "ORDER_CONFIRMED": {
        "template_id": "ORD_CONF_001",
        "bn": "প্রিয় {customer_name}, আপনার অর্ডার #{order_id} সফলভাবে গৃহীত হয়েছে। মোট: ৳{total}। ট্র্যাক: {tracking_link}",
        "en": "Dear {customer_name}, your order #{order_id} has been confirmed. Total: BDT {total}. Track: {tracking_link}",
    },
    "ORDER_PROCESSING": {
        "template_id": "ORD_PROC_001",
        "bn": "আপনার অর্ডার #{order_id} এখন প্রস্তুত করা হচ্ছে। আপনি {delivery_time} এর মধ্যে ডেলিভারি পাবেন।",
        "en": "Your order #{order_id} is now being prepared. Expected delivery: {delivery_time}",
    },
    "ORDER_SHIPPED": {
        "template_id": "ORD_SHIP_001",
        "bn": "আপনার অর্ডার #{order_id} ডেলিভারির জন্য প্রস্তুত। ডেলিভারি বয়: {delivery_person} ({phone})",
        "en": "Your order #{order_id} is out for delivery. Delivery by: {delivery_person} ({phone})",
    },
    "ORDER_DELIVERED": {
        "template_id": "ORD_DEL_001",
        "bn": "আপনার অর্ডার #{order_id} সফলভাবে ডেলিভারি করা হয়েছে। ধন্যবাদ Smart Dairy বিশ্বাস করার জন্য!",
        "en": "Your order #{order_id} has been delivered. Thank you for choosing Smart Dairy!",
    },
    "ORDER_CANCELLED": {
        "template_id": "ORD_CANC_001",
        "bn": "আপনার অর্ডার #{order_id} বাতিল করা হয়েছে। রিফান্ড ৩-৫ কার্যদিবসের মধ্যে প্রক্রিয়া করা হবে।",
        "en": "Your order #{order_id} has been cancelled. Refund will be processed within 3-5 business days.",
    },
}
```

#### 5.3.2 Payment Notification Templates

```python
# Payment Notification Templates
PAYMENT_TEMPLATES = {
    "PAYMENT_DUE": {
        "template_id": "PAY_DUE_001",
        "bn": "প্রিয় {customer_name}, আপনার {subscription_name} সাবস্ক্রিপশনের পেমেন্ট ৳{amount} বাকি। দয়া করে {due_date} এর মধ্যে পরিশোধ করুন।",
        "en": "Dear {customer_name}, payment of BDT {amount} is due for {subscription_name}. Please pay by {due_date}.",
    },
    "PAYMENT_RECEIVED": {
        "template_id": "PAY_REC_001",
        "bn": "আপনার পেমেন্ট ৳{amount} গৃহীত হয়েছে। ট্রানজেকশন ID: {transaction_id}। ধন্যবাদ!",
        "en": "Your payment of BDT {amount} has been received. Txn ID: {transaction_id}. Thank you!",
    },
    "PAYMENT_FAILED": {
        "template_id": "PAY_FAIL_001",
        "bn": "আপনার পেমেন্ট ৳{amount} ব্যর্থ হয়েছে। কারণ: {reason}। অনুগ্রহ করে আবার চেষ্টা করুন অথবা সাপোর্টে যোগাযোগ করুন।",
        "en": "Your payment of BDT {amount} failed. Reason: {reason}. Please try again or contact support.",
    },
}
```

### 5.4 Promotional Messages

#### 5.4.1 Marketing Template Guidelines

```python
# Promotional Message Templates
PROMOTIONAL_TEMPLATES = {
    "WEEKLY_OFFER": {
        "template_id": "PROMO_WEEK_001",
        "bn": "এই সপ্তাহের বিশেষ অফার! {product_name} এ {discount}% ছাড়। কোড: {coupon_code}। অর্ডার করুন: {link}। আনসাবস্ক্রাইব করতে STOP লিখুন।",
        "en": "This week's special offer! {discount}% off on {product_name}. Code: {coupon_code}. Order: {link}. Reply STOP to unsubscribe.",
    },
    "NEW_PRODUCT_LAUNCH": {
        "template_id": "PROMO_NEW_001",
        "bn": "নতুন পণ্য এলো! {product_name} এখন Smart Dairy-তে। প্রথম অর্ডারে {discount}% ছাড়। বিস্তারিত: {link}",
        "en": "New product alert! {product_name} now available at Smart Dairy. Get {discount}% off on first order. Details: {link}",
    },
    "REFERRAL_PROGRAM": {
        "template_id": "PROMO_REF_001",
        "bn": "বন্ধুকে রেফার করুন, ৳{reward} বোনাস পান! আপনার কোড: {referral_code}। রেফার করুন: {link}",
        "en": "Refer a friend, earn BDT {reward} bonus! Your code: {referral_code}. Refer now: {link}",
    },
    "LOYALTY_REWARD": {
        "template_id": "PROMO_LOYAL_001",
        "bn": "প্রিয় গ্রাহক, আপনার {points} পয়েন্ট রয়েছে। ৳{reward_value} এর ছাড় পেতে কোড {code} ব্যবহার করুন।",
        "en": "Dear valued customer, you have {points} loyalty points. Use code {code} to get BDT {reward_value} discount.",
    },
}
```

#### 5.4.2 Promotional Message Constraints

| Constraint | Value | Description |
|------------|-------|-------------|
| **Time Window** | 9:00 AM - 8:00 PM | Allowed sending hours (BTRC) |
| **Frequency** | Max 3/week per user | Prevent spam |
| **Opt-out** | Required | STOP keyword support |
| **Consent** | Required | Explicit opt-in |
| **Content** | Approved templates only | No dynamic promotional content |

### 5.5 Unicode Bengali Messages

#### 5.5.1 Bengali SMS Encoding

```python
"""
Bengali SMS Encoding Handler
Handles Unicode encoding for Bengali text messages
"""
import unicodedata

class BengaliSMSEncoder:
    """Encoder for Bengali SMS messages"""
    
    # Bengali Unicode range: \u0980 - \u09FF
    BENGALI_RANGE = range(0x0980, 0x0A00)
    
    # SMS character limits
    GSM7_LIMIT = 160  # Standard GSM-7 encoding
    UNICODE_LIMIT = 70  # Unicode (UTF-16) encoding
    CONCATENATED_GSM7 = 153  # Per part for concatenated
    CONCATENATED_UNICODE = 67  # Per part for concatenated Unicode
    
    @classmethod
    def is_bengali(cls, text: str) -> bool:
        """Check if text contains Bengali characters"""
        for char in text:
            if ord(char) in cls.BENGALI_RANGE:
                return True
        return False
    
    @classmethod
    def count_parts(cls, text: str) -> int:
        """Calculate number of SMS parts needed"""
        is_unicode = cls.is_bengali(text) or not text.isascii()
        length = len(text)
        
        if not is_unicode:
            # GSM-7 encoding
            if length <= cls.GSM7_LIMIT:
                return 1
            return (length + cls.CONCATENATED_GSM7 - 1) // cls.CONCATENATED_GSM7
        else:
            # Unicode encoding
            if length <= cls.UNICODE_LIMIT:
                return 1
            return (length + cls.CONCATENATED_UNICODE - 1) // cls.CONCATENATED_UNICODE
    
    @classmethod
    def estimate_cost(cls, text: str, cost_per_sms: float = 0.35) -> float:
        """Estimate SMS cost based on parts"""
        parts = cls.count_parts(text)
        return parts * cost_per_sms
    
    @classmethod
    def normalize_bengali(cls, text: str) -> str:
        """Normalize Bengali text for SMS"""
        # Normalize to NFC form
        normalized = unicodedata.normalize('NFC', text)
        
        # Remove zero-width characters that may cause issues
        zero_width_chars = ['\u200B', '\u200C', '\u200D', '\uFEFF']
        for char in zero_width_chars:
            normalized = normalized.replace(char, '')
        
        return normalized

# Example usage
text_bn = "প্রিয় গ্রাহক, আপনার অর্ডার #12345 সফলভাবে গৃহীত হয়েছে।"
print(f"Is Bengali: {BengaliSMSEncoder.is_bengali(text_bn)}")
print(f"Parts needed: {BengaliSMSEncoder.count_parts(text_bn)}")
print(f"Estimated cost: BDT {BengaliSMSEncoder.estimate_cost(text_bn)}")
```

---

## 6. Implementation

### 6.1 Project Structure

```
smart_dairy_sms/
├── __init__.py
├── config.py                 # Configuration settings
├── models.py                 # Data models (Pydantic)
├── services/
│   ├── __init__.py
│   ├── sms_service.py        # Main SMS service
│   ├── otp_service.py        # OTP handling
│   ├── template_service.py   # Template management
│   └── provider/
│       ├── __init__.py
│       ├── base.py           # Abstract provider class
│       ├── adn_provider.py   # Adn Telecom implementation
│       ├── boomcast_provider.py
│       └── banglahn_provider.py
├── celery_tasks.py           # Celery background tasks
├── webhooks.py               # Webhook handlers
├── rate_limiter.py           # Rate limiting logic
├── queue_manager.py          # Message queue management
└── utils.py                  # Utility functions
```

### 6.2 Configuration

```python
"""
SMS Service Configuration
"""
from pydantic import BaseSettings, Field
from typing import Optional

class SMSConfig(BaseSettings):
    """SMS Service Configuration"""
    
    # Provider Configuration
    PRIMARY_PROVIDER: str = Field(default="adn", env="SMS_PRIMARY_PROVIDER")
    FALLBACK_PROVIDER: str = Field(default="boomcast", env="SMS_FALLBACK_PROVIDER")
    
    # Adn Telecom Settings
    ADN_API_KEY: str = Field(default="", env="ADN_API_KEY")
    ADN_API_SECRET: str = Field(default="", env="ADN_API_SECRET")
    ADN_BASE_URL: str = Field(default="https://api.adnsms.com", env="ADN_BASE_URL")
    ADN_SENDER_ID: str = Field(default="SmartDairy", env="ADN_SENDER_ID")
    
    # BoomCast Settings
    BOOMCAST_API_KEY: str = Field(default="", env="BOOMCAST_API_KEY")
    BOOMCAST_API_SECRET: str = Field(default="", env="BOOMCAST_API_SECRET")
    BOOMCAST_BASE_URL: str = Field(default="https://api.boomcast.io", env="BOOMCAST_BASE_URL")
    
    # SMS Banglahn Settings
    BANGLAHN_API_KEY: str = Field(default="", env="BANGLAHN_API_KEY")
    BANGLAHN_BASE_URL: str = Field(default="https://api.smsbanglahn.com", env="BANGLAHN_BASE_URL")
    
    # Redis Configuration (for rate limiting and queue)
    REDIS_URL: str = Field(default="redis://localhost:6379/0", env="REDIS_URL")
    
    # Celery Configuration
    CELERY_BROKER_URL: str = Field(default="redis://localhost:6379/1", env="CELERY_BROKER_URL")
    CELERY_RESULT_BACKEND: str = Field(default="redis://localhost:6379/2", env="CELERY_RESULT_BACKEND")
    
    # Rate Limiting
    RATE_LIMIT_PER_MINUTE: int = Field(default=100, env="SMS_RATE_LIMIT_PER_MINUTE")
    RATE_LIMIT_PER_HOUR: int = Field(default=1000, env="SMS_RATE_LIMIT_PER_HOUR")
    RATE_LIMIT_PER_DAY: int = Field(default=10000, env="SMS_RATE_LIMIT_PER_DAY")
    
    # Retry Configuration
    MAX_RETRIES: int = Field(default=3, env="SMS_MAX_RETRIES")
    RETRY_DELAY_SECONDS: int = Field(default=60, env="SMS_RETRY_DELAY_SECONDS")
    RETRY_BACKOFF_MULTIPLIER: int = Field(default=2, env="SMS_RETRY_BACKOFF_MULTIPLIER")
    
    # Webhook Configuration
    WEBHOOK_SECRET: str = Field(default="", env="SMS_WEBHOOK_SECRET")
    WEBHOOK_URL: str = Field(default="https://api.smartdairy.com/webhooks/sms", env="SMS_WEBHOOK_URL")
    
    # Feature Flags
    SMS_ENABLED: bool = Field(default=True, env="SMS_ENABLED")
    SANDBOX_MODE: bool = Field(default=False, env="SMS_SANDBOX_MODE")
    LOG_ALL_MESSAGES: bool = Field(default=True, env="SMS_LOG_ALL_MESSAGES")
    
    class Config:
        env_file = ".env"
        case_sensitive = True

# Global configuration instance
sms_config = SMSConfig()
```

### 6.3 Celery Tasks for Async Processing

```python
"""
Celery Tasks for SMS Processing
Handles asynchronous SMS sending and background tasks
"""
import logging
from typing import Optional, Dict, Any
from celery import Celery
from celery.exceptions import MaxRetriesExceededError

from .config import sms_config

# Initialize Celery
app = Celery('smart_dairy_sms')
app.config_from_object({
    'broker_url': sms_config.CELERY_BROKER_URL,
    'result_backend': sms_config.CELERY_RESULT_BACKEND,
    'task_serializer': 'json',
    'accept_content': ['json'],
    'result_serializer': 'json',
    'timezone': 'Asia/Dhaka',
    'enable_utc': True,
    'task_track_started': True,
    'task_time_limit': 300,
    'task_soft_time_limit': 240,
    'worker_prefetch_multiplier': 1,
    'worker_max_tasks_per_child': 1000,
})

logger = logging.getLogger(__name__)

# ============================================================================
# OTP Tasks
# ============================================================================

@app.task(bind=True, max_retries=3, default_retry_delay=60)
def send_otp_sms(
    self,
    phone: str,
    otp: str,
    template_id: str,
    language: str = "bn",
    custom_id: Optional[str] = None
) -> Dict[str, Any]:
    """Send OTP SMS asynchronously"""
    try:
        # Format phone number
        formatted_phone = format_phone_bangladesh(phone)
        
        # Get template and format message
        from .services.template_service import TemplateService
        template_service = TemplateService()
        message = template_service.format_template(
            template_id=template_id,
            language=language,
            otp=otp
        )
        
        # Initialize SMS service
        from .services.sms_service import SMSService
        sms_service = SMSService()
        
        # Run async send
        import asyncio
        response = asyncio.run(sms_service.send(
            to=formatted_phone,
            message=message,
            message_type="OTP",
            priority="HIGH",
            template_id=template_id,
            custom_id=custom_id,
            metadata={"otp": otp, "language": language}
        ))
        
        if response.success:
            logger.info(f"OTP sent successfully to {formatted_phone}")
            return {
                "success": True,
                "message_id": response.message_id,
                "phone": formatted_phone,
                "provider": response.provider
            }
        else:
            raise self.retry(exc=Exception(response.error_message))
            
    except MaxRetriesExceededError:
        logger.error(f"Max retries exceeded for OTP to {phone}")
        return {"success": False, "error": "Max retries exceeded", "phone": phone}
    except Exception as e:
        logger.exception(f"Exception sending OTP to {phone}")
        raise self.retry(exc=e)

# ============================================================================
# Transactional Message Tasks
# ============================================================================

@app.task(bind=True, max_retries=3, default_retry_delay=120)
def send_transactional_sms(
    self,
    phone: str,
    template_id: str,
    template_variables: Dict[str, Any],
    language: str = "bn",
    custom_id: Optional[str] = None
) -> Dict[str, Any]:
    """Send transactional SMS (order updates, delivery notifications)"""
    try:
        # Format phone
        formatted_phone = format_phone_bangladesh(phone)
        
        # Format message from template
        from .services.template_service import TemplateService
        template_service = TemplateService()
        message = template_service.format_template(
            template_id=template_id,
            language=language,
            **template_variables
        )
        
        # Send SMS
        from .services.sms_service import SMSService
        sms_service = SMSService()
        import asyncio
        response = asyncio.run(sms_service.send(
            to=formatted_phone,
            message=message,
            message_type="TRANSACTIONAL",
            priority="NORMAL",
            template_id=template_id,
            custom_id=custom_id,
            metadata=template_variables
        ))
        
        if response.success:
            return {"success": True, "message_id": response.message_id, "phone": formatted_phone}
        else:
            raise self.retry(exc=Exception(response.error_message))
            
    except Exception as e:
        logger.exception(f"Failed to send transactional SMS")
        raise self.retry(exc=e)

@app.task
def send_order_confirmation(
    order_id: str,
    customer_phone: str,
    customer_name: str,
    total_amount: float,
    tracking_link: str,
    language: str = "bn"
) -> Dict[str, Any]:
    """Send order confirmation SMS"""
    return send_transactional_sms.delay(
        phone=customer_phone,
        template_id="ORD_CONF_001",
        template_variables={
            "customer_name": customer_name,
            "order_id": order_id,
            "total": f"{total_amount:.2f}",
            "tracking_link": tracking_link
        },
        language=language,
        custom_id=f"order_confirm_{order_id}"
    )

@app.task
def send_delivery_notification(
    order_id: str,
    customer_phone: str,
    status: str,
    delivery_person: Optional[str] = None,
    delivery_phone: Optional[str] = None,
    language: str = "bn"
) -> Dict[str, Any]:
    """Send delivery status notification"""
    template_map = {
        "shipped": "ORD_SHIP_001",
        "out_for_delivery": "ORD_SHIP_001",
        "delivered": "ORD_DEL_001"
    }
    
    template_id = template_map.get(status, "ORD_SHIP_001")
    
    variables = {"order_id": order_id}
    if delivery_person:
        variables["delivery_person"] = delivery_person
    if delivery_phone:
        variables["phone"] = delivery_phone
    
    return send_transactional_sms.delay(
        phone=customer_phone,
        template_id=template_id,
        template_variables=variables,
        language=language,
        custom_id=f"delivery_{status}_{order_id}"
    )

# ============================================================================
# Bulk SMS Tasks
# ============================================================================

@app.task(bind=True, max_retries=2)
def send_bulk_promotional(
    self,
    recipient_list: list,
    template_id: str,
    campaign_name: str,
    language: str = "bn",
    scheduled_time: Optional[str] = None
) -> Dict[str, Any]:
    """Send bulk promotional SMS campaign"""
    try:
        from .services.template_service import TemplateService
        from .services.sms_service import SMSService, SMSMessage
        
        template_service = TemplateService()
        sms_service = SMSService()
        
        # Create message objects
        messages = []
        for recipient in recipient_list:
            phone = format_phone_bangladesh(recipient['phone'])
            message = template_service.format_template(
                template_id=template_id,
                language=language,
                **recipient.get('variables', {})
            )
            
            messages.append(SMSMessage(
                to=phone,
                message=message,
                template_id=template_id,
                message_type="PROMOTIONAL",
                priority="LOW",
                custom_id=recipient.get('custom_id'),
                metadata={
                    "campaign_name": campaign_name,
                    "scheduled_time": scheduled_time
                }
            ))
        
        # Send bulk
        import asyncio
        responses = asyncio.run(sms_service.send_bulk(messages))
        
        successful = sum(1 for r in responses if r.success)
        failed = len(responses) - successful
        
        logger.info(f"Bulk campaign '{campaign_name}': {successful} sent, {failed} failed")
        
        return {
            "campaign_name": campaign_name,
            "total": len(recipient_list),
            "successful": successful,
            "failed": failed,
            "template_id": template_id
        }
        
    except Exception as e:
        logger.exception(f"Bulk campaign failed: {campaign_name}")
        raise self.retry(exc=e)

# ============================================================================
# Scheduled Tasks
# ============================================================================

@app.task
def process_scheduled_sms():
    """Process scheduled SMS messages (runs every minute)"""
    logger.info("Processing scheduled SMS messages")
    pass

@app.task
def check_delivery_status():
    """Check pending message statuses (runs every 5 minutes)"""
    logger.info("Checking delivery statuses")
    pass

@app.task
def cleanup_old_logs(days: int = 90):
    """Clean up old SMS logs (runs daily)"""
    from datetime import datetime, timedelta
    cutoff_date = datetime.utcnow() - timedelta(days=days)
    logger.info(f"Cleaned up SMS logs older than {days} days")

# ============================================================================
# Webhook Processing Tasks
# ============================================================================

@app.task
def process_delivery_webhook(provider: str, payload: Dict[str, Any]):
    """Process delivery status webhook from provider"""
    try:
        logger.info(f"Received delivery report: {payload.get('message_id')} - {payload.get('status')}")
        
        # Handle failed deliveries
        if payload.get('status') == 'FAILED':
            handle_failed_delivery.delay(
                message_id=payload.get('message_id'),
                error_code=payload.get('error_code'),
                error_message=payload.get('error_message')
            )
    
    except Exception as e:
        logger.exception(f"Error processing webhook from {provider}")

@app.task
def handle_failed_delivery(
    message_id: str,
    error_code: str,
    error_message: Optional[str] = None
):
    """Handle failed delivery - may retry or alert"""
    retryable_codes = ["NETWORK_ERROR", "TIMEOUT", "PROVIDER_ERROR"]
    
    if error_code in retryable_codes:
        logger.info(f"Message {message_id} failed with retryable error: {error_code}")
    else:
        logger.error(f"Message {message_id} failed permanently: {error_code} - {error_message}")

# Celery Beat Schedule
celery_beat_schedule = {
    'process-scheduled-sms': {
        'task': 'celery_tasks.process_scheduled_sms',
        'schedule': 60.0,
    },
    'check-delivery-status': {
        'task': 'celery_tasks.check_delivery_status',
        'schedule': 300.0,
    },
    'cleanup-old-logs': {
        'task': 'celery_tasks.cleanup_old_logs',
        'schedule': 86400.0,
    },
}
```

---

## 7. Template Management

### 7.1 Template Structure

```python
"""
Template Management Service
Handles SMS template registration, validation, and formatting
"""
import re
from typing import Dict, List, Optional, Any
from dataclasses import dataclass, field
from enum import Enum

class TemplateStatus(Enum):
    """Template approval status"""
    DRAFT = "draft"
    PENDING = "pending"
    APPROVED = "approved"
    REJECTED = "rejected"
    SUSPENDED = "suspended"

class TemplateCategory(Enum):
    """Template categories"""
    OTP = "otp"
    TRANSACTIONAL = "transactional"
    PROMOTIONAL = "promotional"
    FARMER = "farmer"

@dataclass
class SMSTemplate:
    """SMS Template data structure"""
    template_id: str
    name: str
    category: TemplateCategory
    status: TemplateStatus
    content_bn: str  # Bengali content
    content_en: str  # English content
    variables: List[str] = field(default_factory=list)
    description: str = ""
    created_at: Optional[str] = None
    approved_at: Optional[str] = None
    provider_template_ids: Dict[str, str] = field(default_factory=dict)
    
    def validate_variables(self, **kwargs) -> bool:
        """Check if all required variables are provided"""
        missing = [var for var in self.variables if var not in kwargs]
        return len(missing) == 0
    
    def format(self, language: str = "bn", **kwargs) -> str:
        """Format template with variables"""
        content = self.content_bn if language == "bn" else self.content_en
        
        for var in self.variables:
            value = kwargs.get(var, f"{{{var}}}")
            content = content.replace(f"{{{var}}}", str(value))
        
        return content


class TemplateService:
    """Service for managing SMS templates"""
    
    # Built-in templates
    DEFAULT_TEMPLATES = {
        # OTP Templates
        "OTP_REG_001": SMSTemplate(
            template_id="OTP_REG_001",
            name="Registration OTP",
            category=TemplateCategory.OTP,
            status=TemplateStatus.APPROVED,
            content_bn="আপনার Smart Dairy ভেরিফিকেশন কোড: {otp}। কোডটি ৫ মিনিটের জন্য বৈধ। কাউকে শেয়ার করবেন না।",
            content_en="Your Smart Dairy verification code is: {otp}. Valid for 5 minutes. Do not share.",
            variables=["otp"],
            description="OTP for new user registration"
        ),
        "OTP_PASS_001": SMSTemplate(
            template_id="OTP_PASS_001",
            name="Password Reset OTP",
            category=TemplateCategory.OTP,
            status=TemplateStatus.APPROVED,
            content_bn="পাসওয়ার্ড রিসেট কোড: {otp}। এটি Smart Dairy-এর কাউকে দেবেন না।",
            content_en="Password reset code: {otp}. Never share this with anyone at Smart Dairy.",
            variables=["otp"],
            description="OTP for password reset"
        ),
        
        # Order Templates
        "ORD_CONF_001": SMSTemplate(
            template_id="ORD_CONF_001",
            name="Order Confirmation",
            category=TemplateCategory.TRANSACTIONAL,
            status=TemplateStatus.APPROVED,
            content_bn="প্রিয় {customer_name}, আপনার অর্ডার #{order_id} সফলভাবে গৃহীত হয়েছে। মোট: ৳{total}। ট্র্যাক: {tracking_link}",
            content_en="Dear {customer_name}, your order #{order_id} has been confirmed. Total: BDT {total}. Track: {tracking_link}",
            variables=["customer_name", "order_id", "total", "tracking_link"],
            description="Order confirmation message"
        ),
        "ORD_SHIP_001": SMSTemplate(
            template_id="ORD_SHIP_001",
            name="Order Shipped",
            category=TemplateCategory.TRANSACTIONAL,
            status=TemplateStatus.APPROVED,
            content_bn="আপনার অর্ডার #{order_id} ডেলিভারির জন্য প্রস্তুত। ডেলিভারি বয়: {delivery_person} ({phone})",
            content_en="Your order #{order_id} is out for delivery. Delivery by: {delivery_person} ({phone})",
            variables=["order_id", "delivery_person", "phone"],
            description="Order shipped notification"
        ),
        "ORD_DEL_001": SMSTemplate(
            template_id="ORD_DEL_001",
            name="Order Delivered",
            category=TemplateCategory.TRANSACTIONAL,
            status=TemplateStatus.APPROVED,
            content_bn="আপনার অর্ডার #{order_id} সফলভাবে ডেলিভারি করা হয়েছে। ধন্যবাদ Smart Dairy বিশ্বাস করার জন্য!",
            content_en="Your order #{order_id} has been delivered. Thank you for choosing Smart Dairy!",
            variables=["order_id"],
            description="Order delivered confirmation"
        ),
        
        # Payment Templates
        "PAY_DUE_001": SMSTemplate(
            template_id="PAY_DUE_001",
            name="Payment Due",
            category=TemplateCategory.TRANSACTIONAL,
            status=TemplateStatus.APPROVED,
            content_bn="প্রিয় {customer_name}, আপনার {subscription_name} সাবস্ক্রিপশনের পেমেন্ট ৳{amount} বাকি। দয়া করে {due_date} এর মধ্যে পরিশোধ করুন।",
            content_en="Dear {customer_name}, payment of BDT {amount} is due for {subscription_name}. Please pay by {due_date}.",
            variables=["customer_name", "subscription_name", "amount", "due_date"],
            description="Payment due reminder"
        ),
        "PAY_REC_001": SMSTemplate(
            template_id="PAY_REC_001",
            name="Payment Received",
            category=TemplateCategory.TRANSACTIONAL,
            status=TemplateStatus.APPROVED,
            content_bn="আপনার পেমেন্ট ৳{amount} গৃহীত হয়েছে। ট্রানজেকশন ID: {transaction_id}। ধন্যবাদ!",
            content_en="Your payment of BDT {amount} has been received. Txn ID: {transaction_id}. Thank you!",
            variables=["amount", "transaction_id"],
            description="Payment received confirmation"
        ),
        
        # Promotional Templates
        "PROMO_WEEK_001": SMSTemplate(
            template_id="PROMO_WEEK_001",
            name="Weekly Offer",
            category=TemplateCategory.PROMOTIONAL,
            status=TemplateStatus.APPROVED,
            content_bn="এই সপ্তাহের বিশেষ অফার! {product_name} এ {discount}% ছাড়। কোড: {coupon_code}। অর্ডার করুন: {link}। আনসাবস্ক্রাইব করতে STOP লিখুন।",
            content_en="This week's special offer! {discount}% off on {product_name}. Code: {coupon_code}. Order: {link}. Reply STOP to unsubscribe.",
            variables=["product_name", "discount", "coupon_code", "link"],
            description="Weekly promotional offer"
        ),
        "PROMO_REF_001": SMSTemplate(
            template_id="PROMO_REF_001",
            name="Referral Program",
            category=TemplateCategory.PROMOTIONAL,
            status=TemplateStatus.APPROVED,
            content_bn="বন্ধুকে রেফার করুন, ৳{reward} বোনাস পান! আপনার কোড: {referral_code}। রেফার করুন: {link}",
            content_en="Refer a friend, earn BDT {reward} bonus! Your code: {referral_code}. Refer now: {link}",
            variables=["reward", "referral_code", "link"],
            description="Referral program invitation"
        ),
        
        # Farmer Templates
        "FARM_COLLECT_001": SMSTemplate(
            template_id="FARM_COLLECT_001",
            name="Collection Schedule",
            category=TemplateCategory.FARMER,
            status=TemplateStatus.APPROVED,
            content_bn="প্রিয় কৃষক, আগামীকাল {time} এ আমরা দুধ সংগ্রহ করব। ধন্যবাদ।",
            content_en="Dear farmer, we will collect milk tomorrow at {time}. Thank you.",
            variables=["time"],
            description="Milk collection schedule"
        ),
        "FARM_PAY_001": SMSTemplate(
            template_id="FARM_PAY_001",
            name="Farmer Payment",
            category=TemplateCategory.FARMER,
            status=TemplateStatus.APPROVED,
            content_bn="আপনার দুধ বিক্রির টাকা ৳{amount} আপনার অ্যাকাউন্টে পাঠানো হয়েছে। Txn ID: {txn_id}",
            content_en="Your milk sale payment of BDT {amount} has been sent to your account. Txn ID: {txn_id}",
            variables=["amount", "txn_id"],
            description="Farmer payment notification"
        ),
    }
    
    def __init__(self):
        self.templates: Dict[str, SMSTemplate] = dict(self.DEFAULT_TEMPLATES)
    
    def get_template(self, template_id: str) -> Optional[SMSTemplate]:
        """Get template by ID"""
        return self.templates.get(template_id)
    
    def format_template(self, template_id: str, language: str = "bn", **kwargs) -> str:
        """Format a template with variables"""
        template = self.get_template(template_id)
        if not template:
            raise ValueError(f"Template not found: {template_id}")
        
        if not template.validate_variables(**kwargs):
            missing = [var for var in template.variables if var not in kwargs]
            raise ValueError(f"Missing variables: {missing}")
        
        return template.format(language=language, **kwargs)
    
    def validate_template_content(self, content: str) -> Dict[str, Any]:
        """Validate template content"""
        errors = []
        warnings = []
        
        # Check length
        from .utils import BengaliSMSEncoder
        parts = BengaliSMSEncoder.count_parts(content)
        if parts > 3:
            warnings.append(f"Message will use {parts} SMS parts - consider shortening")
        
        # Check for variable format
        variables = re.findall(r'\{(\w+)\}', content)
        
        # Check for required elements in promotional
        if "STOP" not in content.upper() and "আনসাবস্ক্রাইব" not in content:
            warnings.append("Promotional messages should include opt-out instructions")
        
        return {
            "valid": len(errors) == 0,
            "errors": errors,
            "warnings": warnings,
            "variables": variables,
            "estimated_parts": parts
        }
```

---

## 8. Delivery Tracking

### 8.1 Delivery Status Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                    DELIVERY STATUS LIFECYCLE                        │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│   ┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐        │
│   │ PENDING │───▶│  QUEUED │───▶│  SENT   │───▶│DELIVERED│        │
│   └─────────┘    └─────────┘    └────┬────┘    └─────────┘        │
│        │                              │                             │
│        │                              ▼                             │
│        │                         ┌─────────┐                        │
│        │                         │  FAILED │                        │
│        │                         └────┬────┘                        │
│        │                              │                             │
│        │                              ▼                             │
│        │                         ┌─────────┐    ┌─────────┐        │
│        └────────────────────────▶│  RETRY  │───▶│ EXPIRED │        │
│                                  └─────────┘    └─────────┘        │
│                                                                     │
│   Status Meanings:                                                  │
│   • PENDING   - Message created, not yet sent                       │
│   • QUEUED    - Accepted by provider, waiting to send               │
│   • SENT      - Message sent to carrier                             │
│   • DELIVERED - Successfully delivered to handset                   │
│   • FAILED    - Delivery failed (reason logged)                     │
│   • EXPIRED   - Message expired (e.g., phone off > 48hrs)           │
│   • REJECTED  - Rejected by carrier (DND, invalid, etc.)            │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### 8.2 Webhook Handler

```python
"""
Webhook Handlers for Delivery Status
Handles callbacks from SMS providers
"""
import hmac
import hashlib
import logging
from typing import Dict, Any, Optional
from fastapi import FastAPI, Request, HTTPException, Header
from fastapi.responses import JSONResponse

from .config import sms_config

logger = logging.getLogger(__name__)
app = FastAPI()

class WebhookHandler:
    """Base webhook handler"""
    
    def __init__(self, provider: str):
        self.provider = provider
        self.secret = sms_config.WEBHOOK_SECRET
    
    def verify_signature(self, body: bytes, signature: str, algorithm: str = "sha256") -> bool:
        """Verify webhook signature"""
        if not self.secret:
            return True
        
        expected = hmac.new(
            self.secret.encode(),
            body,
            getattr(hashlib, algorithm)
        ).hexdigest()
        
        return hmac.compare_digest(expected, signature)


class AdnWebhookHandler(WebhookHandler):
    """Adn Telecom webhook handler"""
    
    def __init__(self):
        super().__init__("adn")
    
    def parse_payload(self, data: Dict[str, Any]) -> Dict[str, Any]:
        """Parse Adn webhook payload"""
        status_mapping = {
            "DELIVERED": "delivered",
            "SENT": "sent",
            "FAILED": "failed",
            "EXPIRED": "expired",
            "REJECTED": "rejected",
            "PENDING": "pending"
        }
        
        return {
            "message_id": data.get("message_id"),
            "status": status_mapping.get(data.get("status"), "unknown"),
            "phone": data.get("mobile"),
            "delivered_at": data.get("delivered_at"),
            "error_code": data.get("error_code"),
            "error_message": data.get("error_message"),
            "raw_data": data
        }


# FastAPI routes for webhooks
@app.post("/webhooks/sms/adn")
async def adn_webhook(
    request: Request,
    x_signature: Optional[str] = Header(None)
):
    """Handle Adn Telecom delivery webhooks"""
    try:
        body = await request.body()
        data = await request.json()
        
        handler = AdnWebhookHandler()
        
        # Verify signature if provided
        if x_signature and not handler.verify_signature(body, x_signature):
            raise HTTPException(status_code=401, detail="Invalid signature")
        
        # Parse payload
        parsed = handler.parse_payload(data)
        
        # Process delivery report
        from celery_tasks import process_delivery_webhook
        process_delivery_webhook.delay("adn", data)
        
        logger.info(f"Received delivery report for {parsed['message_id']}: {parsed['status']}")
        
        return JSONResponse({"status": "received"})
        
    except Exception as e:
        logger.exception("Error processing Adn webhook")
        raise HTTPException(status_code=500, detail=str(e))


@app.post("/webhooks/sms/boomcast")
async def boomcast_webhook(request: Request):
    """Handle BoomCast delivery webhooks"""
    data = await request.json()
    from celery_tasks import process_delivery_webhook
    process_delivery_webhook.delay("boomcast", data)
    return JSONResponse({"status": "received"})
```

---

## 9. Rate Limiting

### 9.1 Rate Limiter Implementation

```python
"""
Rate Limiter for SMS Service
Implements sliding window rate limiting using Redis
"""
import time
from typing import Optional
import redis

class RateLimiter:
    """Redis-based rate limiter for SMS sending"""
    
    def __init__(
        self,
        redis_url: str,
        per_minute: int = 100,
        per_hour: int = 1000,
        per_day: int = 10000
    ):
        self.redis = redis.from_url(redis_url)
        self.limits = {
            "minute": {"max": per_minute, "window": 60},
            "hour": {"max": per_hour, "window": 3600},
            "day": {"max": per_day, "window": 86400}
        }
    
    def _get_key(self, identifier: str, window: str) -> str:
        """Generate Redis key for rate limit tracking"""
        timestamp = int(time.time())
        window_size = self.limits[window]["window"]
        window_bucket = timestamp // window_size
        return f"rate_limit:{identifier}:{window}:{window_bucket}"
    
    async def allow_send(
        self,
        phone: str,
        message_type: str = "transactional"
    ) -> bool:
        """Check if sending is allowed under rate limits"""
        # Different limits for different message types
        if message_type == "OTP":
            multiplier = 2
        elif message_type == "PROMOTIONAL":
            multiplier = 0.5
        else:
            multiplier = 1
        
        # Check all time windows
        for window, config in self.limits.items():
            key = self._get_key(phone, window)
            current = int(self.redis.get(key) or 0)
            
            limit = int(config["max"] * multiplier)
            
            if current >= limit:
                return False
        
        # Increment counters
        for window, config in self.limits.items():
            key = self._get_key(phone, window)
            pipe = self.redis.pipeline()
            pipe.incr(key)
            pipe.expire(key, config["window"] + 1)
            pipe.execute()
        
        return True
    
    async def get_remaining(self, phone: str) -> dict:
        """Get remaining quota for phone number"""
        remaining = {}
        
        for window, config in self.limits.items():
            key = self._get_key(phone, window)
            current = int(self.redis.get(key) or 0)
            remaining[window] = config["max"] - current
        
        return remaining
```

### 9.2 Queue Management

```python
"""
Message Queue Management
Handles message queuing and prioritization
"""
import json
import redis
import time
from typing import List, Optional, Dict, Any
from dataclasses import dataclass, asdict
from enum import Enum
from datetime import datetime, timedelta

class MessagePriority(Enum):
    """Message priority levels"""
    CRITICAL = 1    # OTP, alerts
    HIGH = 2        # Transactional
    NORMAL = 3      # Standard notifications
    LOW = 4         # Promotional

@dataclass
class QueuedMessage:
    """Message in queue"""
    id: str
    to: str
    message: str
    message_type: str
    priority: MessagePriority
    template_id: Optional[str]
    sender_id: Optional[str]
    scheduled_at: Optional[str]
    retry_count: int = 0
    max_retries: int = 3
    metadata: Optional[Dict] = None

class MessageQueue:
    """Redis-based message queue"""
    
    def __init__(self, redis_url: str):
        self.redis = redis.from_url(redis_url)
        self.queues = {
            MessagePriority.CRITICAL: "sms:queue:critical",
            MessagePriority.HIGH: "sms:queue:high",
            MessagePriority.NORMAL: "sms:queue:normal",
            MessagePriority.LOW: "sms:queue:low"
        }
        self.processing_set = "sms:processing"
        self.delayed_queue = "sms:delayed"
    
    async def enqueue(self, message: QueuedMessage) -> bool:
        """Add message to appropriate queue"""
        queue_name = self.queues.get(message.priority, self.queues[MessagePriority.NORMAL])
        
        if message.scheduled_at:
            score = self._datetime_to_score(message.scheduled_at)
            self.redis.zadd(self.delayed_queue, {json.dumps(asdict(message)): score})
        else:
            self.redis.lpush(queue_name, json.dumps(asdict(message)))
        
        return True
    
    async def dequeue(self, timeout: int = 5) -> Optional[QueuedMessage]:
        """Get next message from queue (priority order)"""
        for priority in [MessagePriority.CRITICAL, MessagePriority.HIGH, 
                        MessagePriority.NORMAL, MessagePriority.LOW]:
            queue_name = self.queues[priority]
            data = self.redis.rpop(queue_name)
            if data:
                return self._parse_message(data)
        
        keys = list(self.queues.values())
        result = self.redis.brpop(keys, timeout=timeout)
        
        if result:
            _, data = result
            return self._parse_message(data)
        
        return None
    
    def _parse_message(self, data: bytes) -> Optional[QueuedMessage]:
        """Parse message from JSON"""
        try:
            msg_dict = json.loads(data)
            msg_dict['priority'] = MessagePriority(msg_dict.get('priority', 3))
            return QueuedMessage(**msg_dict)
        except Exception as e:
            logger.error(f"Failed to parse message: {e}")
            return None
    
    def _datetime_to_score(self, dt_str: str) -> float:
        """Convert ISO datetime to Redis score"""
        dt = datetime.fromisoformat(dt_str.replace('Z', '+00:00'))
        return dt.timestamp()
```

---

## 10. Testing

### 10.1 Test Configuration

```python
"""
SMS Testing Configuration and Utilities
"""
import pytest
from unittest.mock import Mock, AsyncMock, patch

# Test configuration
TEST_CONFIG = {
    "SMS_ENABLED": True,
    "SANDBOX_MODE": True,
    "PRIMARY_PROVIDER": "adn",
    "ADN_API_KEY": "test_key",
    "ADN_API_SECRET": "test_secret",
    "ADN_SENDER_ID": "TEST",
}

# Test phone numbers (Bangladesh format)
TEST_PHONES = {
    "valid": [
        "8801712345678",
        "8801812345678",
        "8801912345678",
        "8801312345678",
        "01712345678",
    ],
    "invalid": [
        "123",
        "880171234567",
        "88017123456789",
        "8800712345678",
        "not_a_number",
        "",
    ]
}
```

### 10.2 Unit Tests

```python
"""
Unit Tests for SMS Service
"""
import pytest
import asyncio
from unittest.mock import Mock, AsyncMock, patch

class TestPhoneValidation:
    """Test phone number validation"""
    
    @pytest.mark.parametrize("phone,expected", [
        ("8801712345678", "8801712345678"),
        ("01712345678", "8801712345678"),
        ("1712345678", "8801712345678"),
        ("+8801712345678", "8801712345678"),
    ])
    def test_format_phone(self, phone, expected):
        """Test phone number formatting"""
        result = format_phone_bangladesh(phone)
        assert result == expected


class TestBengaliEncoding:
    """Test Bengali SMS encoding"""
    
    def test_is_bengali(self):
        """Test Bengali detection"""
        assert BengaliSMSEncoder.is_bengali("বাংলা") == True
        assert BengaliSMSEncoder.is_bengali("English") == False
        assert BengaliSMSEncoder.is_bengali("Mixed বাংলা") == True
    
    def test_count_parts_unicode(self):
        """Test SMS part counting for Bengali"""
        short = "হ্যালো"
        assert BengaliSMSEncoder.count_parts(short) == 1
        
        long = "বাংলাদেশের স্মার্ট ডেইরি ইন্ডাস্ট্রিতে আমরা আমাদের গ্রাহকদের সর্বোত্তম সেবা প্রদানে প্রতিশ্রুতিবদ্ধ।"
        assert BengaliSMSEncoder.count_parts(long) >= 2
```

---

## 11. Compliance

### 11.1 BTRC Regulations

#### 11.1.1 Regulatory Requirements

| Requirement | Description | Implementation |
|-------------|-------------|----------------|
| **Sender ID Registration** | All sender IDs must be registered and approved | Complete company documentation submission |
| **Template Registration** | All SMS content must use pre-approved templates | Template management system |
| **Opt-in Consent** | Explicit consent required for promotional messages | Consent tracking in user profiles |
| **Opt-out Mechanism** | Must honor STOP requests within 24 hours | Automated opt-out processing |
| **Time Restrictions** | Promotional SMS only 9AM-8PM | Scheduled sending with time checks |
| **DND Respect** | Must not send to DND-registered numbers | DND list synchronization |
| **Content Restrictions** | No gambling, adult, political content | Content filtering |
| **Data Retention** | Message logs retained for 1 year | Database retention policies |

#### 11.1.2 DND (Do Not Disturb) Handling

```python
"""
DND (Do Not Disturb) Compliance Module
Handles DND list checking and opt-out processing
"""
import redis
from typing import Optional, List
from datetime import datetime

class DNDComplianceManager:
    """Manages DND compliance"""
    
    def __init__(self, redis_url: str, db_connection=None):
        self.redis = redis.from_url(redis_url)
        self.db = db_connection
        self.dnd_set = "sms:dnd_list"
        self.opt_out_set = "sms:opt_out"
    
    def is_dnd_registered(self, phone: str) -> bool:
        """Check if phone is in DND list"""
        formatted_phone = self._format_phone(phone)
        return self.redis.sismember(self.dnd_set, formatted_phone)
    
    def is_opted_out(self, phone: str) -> bool:
        """Check if user has opted out"""
        formatted_phone = self._format_phone(phone)
        return self.redis.sismember(self.opt_out_set, formatted_phone)
    
    def can_send_promotional(self, phone: str) -> bool:
        """Check if promotional SMS can be sent"""
        return not (self.is_dnd_registered(phone) or self.is_opted_out(phone))
    
    def process_opt_out(self, phone: str) -> bool:
        """Process opt-out request"""
        formatted_phone = self._format_phone(phone)
        
        # Add to opt-out set
        self.redis.sadd(self.opt_out_set, formatted_phone)
        
        # Update database
        # self.db.execute("UPDATE users SET sms_opt_in = FALSE WHERE phone = %s", (formatted_phone,))
        
        # Log opt-out
        self._log_opt_out(formatted_phone)
        
        return True
    
    def process_opt_in(self, phone: str) -> bool:
        """Process opt-in request"""
        formatted_phone = self._format_phone(phone)
        
        # Remove from opt-out set
        self.redis.srem(self.opt_out_set, formatted_phone)
        
        # Update database
        # self.db.execute("UPDATE users SET sms_opt_in = TRUE WHERE phone = %s", (formatted_phone,))
        
        return True
    
    def _format_phone(self, phone: str) -> str:
        """Normalize phone number"""
        digits = ''.join(filter(str.isdigit, phone))
        if digits.startswith('88'):
            return digits
        elif digits.startswith('0'):
            return '88' + digits[1:]
        else:
            return '88' + digits
    
    def _log_opt_out(self, phone: str):
        """Log opt-out event"""
        timestamp = datetime.utcnow().isoformat()
        self.redis.hset("sms:opt_out_log", phone, timestamp)
```

### 11.2 Opt-out Processing

```python
"""
Opt-out keyword processing
Handles STOP and other opt-out keywords
"""

# Keywords that trigger opt-out (Bengali and English)
OPT_OUT_KEYWORDS = [
    "STOP", "UNSUBSCRIBE", "QUIT", "CANCEL", "END",
    "বন্ধ", "আনসাবস্ক্রাইব", "বাতিল", "থামুন"
]

# Keywords that trigger opt-in
OPT_IN_KEYWORDS = [
    "START", "SUBSCRIBE", "YES", "BEGIN",
    "শুরু", "সাবস্ক্রাইব", "হ্যাঁ"
]

class OptOutProcessor:
    """Process incoming SMS for opt-out/opt-in requests"""
    
    def __init__(self, dnd_manager: DNDComplianceManager):
        self.dnd_manager = dnd_manager
    
    def process_incoming_sms(self, from_phone: str, message: str) -> str:
        """
        Process incoming SMS from users
        
        Args:
            from_phone: Sender's phone number
            message: SMS content
            
        Returns:
            Response message (if any)
        """
        message_upper = message.strip().upper()
        
        # Check for opt-out
        if any(keyword.upper() in message_upper for keyword in OPT_OUT_KEYWORDS):
            self.dnd_manager.process_opt_out(from_phone)
            return "You have been unsubscribed from Smart Dairy SMS alerts."
        
        # Check for opt-in
        if any(keyword.upper() in message_upper for keyword in OPT_IN_KEYWORDS):
            self.dnd_manager.process_opt_in(from_phone)
            return "Welcome back! You are now subscribed to Smart Dairy SMS alerts."
        
        return None
```

---

## 12. Appendices

### Appendix A: Complete Code Examples

#### A.1 Django Integration Example

```python
# settings.py
SMS_CONFIG = {
    'PRIMARY_PROVIDER': 'adn',
    'ADN_API_KEY': env('ADN_API_KEY'),
    'ADN_API_SECRET': env('ADN_API_SECRET'),
    'ADN_SENDER_ID': 'SmartDairy',
    'REDIS_URL': env('REDIS_URL'),
}

# models.py
from django.db import models

class SMSLog(models.Model):
    message_id = models.CharField(max_length=100, unique=True)
    phone = models.CharField(max_length=20)
    message = models.TextField()
    message_type = models.CharField(max_length=20)
    status = models.CharField(max_length=20)
    provider = models.CharField(max_length=50)
    sent_at = models.DateTimeField(auto_now_add=True)
    delivered_at = models.DateTimeField(null=True, blank=True)
    cost = models.DecimalField(max_digits=10, decimal_places=4, null=True)

# views.py
from rest_framework.decorators import api_view
from rest_framework.response import Response

@api_view(['POST'])
def send_otp(request):
    phone = request.data.get('phone')
    
    from smart_dairy_sms.services.otp_service import OTPService
    otp_service = OTPService()
    
    success, message = otp_service.generate_and_send(
        phone=phone,
        purpose='registration',
        template_id='OTP_REG_001'
    )
    
    return Response({'success': success, 'message': message})
```

#### A.2 FastAPI Integration Example

```python
# main.py
from fastapi import FastAPI, Depends, HTTPException
from pydantic import BaseModel

app = FastAPI()

class SendSMSRequest(BaseModel):
    phone: str
    message: str
    message_type: str = "TRANSACTIONAL"
    template_id: Optional[str] = None

@app.post("/api/v1/sms/send")
async def send_sms(request: SendSMSRequest):
    from smart_dairy_sms.services.sms_service import sms_service
    
    response = await sms_service.send(
        to=request.phone,
        message=request.message,
        message_type=request.message_type,
        template_id=request.template_id
    )
    
    if not response.success:
        raise HTTPException(status_code=400, detail=response.error_message)
    
    return {
        "success": True,
        "message_id": response.message_id,
        "status": response.status.value
    }

@app.post("/api/v1/sms/otp/send")
async def send_otp(phone: str, purpose: str = "registration"):
    from smart_dairy_sms.services.otp_service import OTPService
    
    otp_service = OTPService()
    success, message = await otp_service.generate_and_send(
        phone=phone,
        purpose=purpose,
        template_id=f"OTP_{purpose.upper()}_001"
    )
    
    if not success:
        raise HTTPException(status_code=429, detail=message)
    
    return {"success": True, "message": message}

@app.post("/api/v1/sms/otp/verify")
async def verify_otp(phone: str, code: str, purpose: str = "registration"):
    from smart_dairy_sms.services.otp_service import OTPService
    
    otp_service = OTPService()
    is_valid, message = await otp_service.verify(phone, purpose, code)
    
    if not is_valid:
        raise HTTPException(status_code=400, detail=message)
    
    return {"success": True, "message": message}
```

### Appendix B: Environment Variables

```bash
# SMS Provider Configuration
SMS_PRIMARY_PROVIDER=adn
SMS_FALLBACK_PROVIDER=boomcast

# Adn Telecom
ADN_API_KEY=your_api_key_here
ADN_API_SECRET=your_api_secret_here
ADN_BASE_URL=https://api.adnsms.com
ADN_SENDER_ID=SmartDairy

# BoomCast
BOOMCAST_API_KEY=your_boomcast_key
BOOMCAST_API_SECRET=your_boomcast_secret
BOOMCAST_BASE_URL=https://api.boomcast.io

# Redis & Celery
REDIS_URL=redis://localhost:6379/0
CELERY_BROKER_URL=redis://localhost:6379/1
CELERY_RESULT_BACKEND=redis://localhost:6379/2

# Rate Limiting
SMS_RATE_LIMIT_PER_MINUTE=100
SMS_RATE_LIMIT_PER_HOUR=1000
SMS_RATE_LIMIT_PER_DAY=10000

# Retry Configuration
SMS_MAX_RETRIES=3
SMS_RETRY_DELAY_SECONDS=60
SMS_RETRY_BACKOFF_MULTIPLIER=2

# Webhooks
SMS_WEBHOOK_SECRET=your_webhook_secret
SMS_WEBHOOK_URL=https://api.smartdairy.com/webhooks/sms

# Feature Flags
SMS_ENABLED=true
SMS_SANDBOX_MODE=false
SMS_LOG_ALL_MESSAGES=true
```

### Appendix C: API Quick Reference

| Operation | Endpoint | Method | Auth |
|-----------|----------|--------|------|
| Send Single SMS | `/api/v1/sms/send` | POST | API Key |
| Send Bulk SMS | `/api/v1/sms/send/bulk` | POST | API Key |
| Send OTP | `/api/v1/sms/otp/send` | POST | API Key |
| Verify OTP | `/api/v1/sms/otp/verify` | POST | API Key |
| Check Status | `/api/v1/sms/status/{id}` | GET | API Key |
| Get Balance | `/api/v1/sms/balance` | GET | API Key |
| Delivery Webhook | `/webhooks/sms/{provider}` | POST | Signature |

### Appendix D: Troubleshooting Guide

| Issue | Possible Cause | Solution |
|-------|---------------|----------|
| SMS not delivered | Invalid phone number | Validate phone format (880XXXXXXXXXX) |
| | DND registered | Check DND status before sending |
| | Template not approved | Verify template registration |
| | Insufficient balance | Recharge provider account |
| High failure rate | Network issues | Enable fallback provider |
| | Invalid sender ID | Verify sender ID registration |
| Rate limit errors | Too many requests | Implement queue and backoff |
| Bengali text garbled | Encoding issue | Use Unicode (UTF-16) encoding |
| Webhook not received | Incorrect URL | Verify webhook endpoint |
| | Firewall blocking | Whitelist provider IPs |

### Appendix E: Cost Estimation

| Message Type | Volume/Month | Cost per SMS | Monthly Cost |
|--------------|--------------|--------------|--------------|
| OTP | 50,000 | BDT 0.35 | BDT 17,500 |
| Transactional | 100,000 | BDT 0.30 | BDT 30,000 |
| Promotional | 200,000 | BDT 0.25 | BDT 50,000 |
| **Total** | **350,000** | **Avg 0.28** | **BDT 97,500** |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Integration Engineer | _________________ | 2026-01-31 |
| Reviewer | CTO | _________________ | ___________ |
| Approver | Tech Lead | _________________ | ___________ |

---

*End of Document E-006: SMS Gateway Integration Guide*
