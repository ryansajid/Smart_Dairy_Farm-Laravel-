# Smart Dairy Ltd. Webhook Integration Guide

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | E-018 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Classification** | Technical Documentation |
| **Status** | Approved |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Webhook Architecture](#2-webhook-architecture)
3. [Event Types](#3-event-types)
4. [Payload Structure](#4-payload-structure)
5. [Security](#5-security)
6. [Implementation](#6-implementation)
7. [Queue Management](#7-queue-management)
8. [Retry Logic](#8-retry-logic)
9. [Idempotency](#9-idempotency)
10. [Verification](#10-verification)
11. [Testing](#11-testing)
12. [Consumer Guidelines](#12-consumer-guidelines)
13. [Debugging](#13-debugging)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Overview

Webhooks provide a mechanism for real-time event notifications between Smart Dairy Ltd.'s systems and external integrations. Unlike polling-based APIs, webhooks push event data to registered endpoints as events occur, enabling:

- **Real-time synchronization** between systems
- **Reduced API load** and server resources
- **Immediate notification** of critical business events
- **Scalable event-driven architecture**

### 1.2 Purpose

This document provides comprehensive guidance for:
- Implementing webhook servers and consumers
- Securing webhook communications
- Managing reliable event delivery
- Troubleshooting webhook integrations

### 1.3 Scope

This guide covers:
- Webhook architecture and components
- Event types and payload structures
- Security implementations (HMAC, TLS, replay prevention)
- Python-based implementation examples
- Queue management and retry mechanisms
- Testing and debugging strategies

### 1.4 Terminology

| Term | Definition |
|------|------------|
| **Webhook** | HTTP callback triggered by system events |
| **Producer** | System that generates and sends webhooks |
| **Consumer** | System that receives and processes webhooks |
| **HMAC** | Hash-based Message Authentication Code |
| **DLQ** | Dead Letter Queue for failed messages |
| **Idempotency** | Property allowing multiple executions without side effects |

---

## 2. Webhook Architecture

### 2.1 High-Level Architecture

```
+------------------------------------------------------------------------+
|                    SMART DAIRY WEBHOOK ARCHITECTURE                    |
+------------------------------------------------------------------------+
|                                                                        |
|  +------------+      +------------+      +------------+               |
|  |   Event    |----->|  Webhook   |----->|  Message   |               |
|  |   Source   |      |  Producer  |      |   Queue    |               |
|  |            |      |  Service   |      |(Redis/RMQ) |               |
|  +------------+      +------------+      +------+-----+               |
|                                                 |                      |
|                                                 V                      |
|                                        +------------+                 |
|                                        |   Worker   |                 |
|                                        |    Pool    |                 |
|                                        +------+-----+                 |
|                                               |                        |
|                                               V                        |
|                                        +------------+                 |
|                                        |   HTTP     |                 |
|                                        | Dispatcher |                 |
|                                        +------+-----+                 |
|                                               |                        |
|                 +-----------------------------+                       |
|                 |                             |                        |
|                 V                             V                        |
|         +------------+              +------------+                   |
|         | Consumer A |              | Consumer B |                   |
|         | (Partner)  |              | (Internal) |                   |
|         +------------+              +------------+                   |
|                                                                        |
+------------------------------------------------------------------------+
```

### 2.2 Component Descriptions

#### 2.2.1 Event Source
- Core business systems generating events
- Order Management System
- Payment Gateway
- Logistics Platform
- User Authentication Service

#### 2.2.2 Webhook Producer Service
- Captures events from source systems
- Validates webhook subscriptions
- Constructs standardized payloads
- Enqueues messages for delivery

#### 2.2.3 Message Queue
- Provides reliable message buffering
- Supports delayed delivery and retries
- Implements priority queuing
- Ensures message persistence

#### 2.2.4 Worker Pool
- Consumes messages from queue
- Executes HTTP dispatch operations
- Handles retry logic
- Manages concurrency

#### 2.2.5 HTTP Dispatcher
- Performs actual HTTP POST requests
- Implements timeout handling
- Records delivery metrics
- Updates delivery status

### 2.3 Data Flow

1. **Event Generation**: Business event occurs in source system
2. **Event Capture**: Producer service receives event notification
3. **Payload Construction**: Standardized webhook payload created
4. **Queue Enqueue**: Message placed in delivery queue
5. **Worker Processing**: Worker picks up message from queue
6. **HTTP Delivery**: POST request sent to consumer endpoint
7. **Response Handling**: Consumer response processed
8. **Retry/Confirm**: Success confirmed or retry scheduled

---

## 3. Event Types

### 3.1 Event Catalog

| Event Type | Category | Description | Priority |
|------------|----------|-------------|----------|
| `order.created` | Order | New order placed | High |
| `order.updated` | Order | Order modified | Medium |
| `order.cancelled` | Order | Order cancelled | High |
| `payment.completed` | Payment | Payment successful | High |
| `payment.failed` | Payment | Payment declined | High |
| `payment.refunded` | Payment | Refund processed | Medium |
| `shipment.created` | Logistics | Shipment initiated | Medium |
| `shipment.updated` | Logistics | Tracking updated | Low |
| `shipment.delivered` | Logistics | Delivery confirmed | High |
| `user.registered` | User | New user account | Medium |
| `user.updated` | User | Profile updated | Low |
| `inventory.low` | Inventory | Stock below threshold | High |
| `product.updated` | Product | Product info changed | Low |

### 3.2 Event Schema Definitions

#### 3.2.1 Order Events

**order.created**
```json
{
  "event": "order.created",
  "timestamp": "2026-01-31T10:30:00Z",
  "data": {
    "order_id": "ORD-2026-001234",
    "customer_id": "CUST-56789",
    "items": [
      {
        "product_id": "PROD-MILK-001",
        "quantity": 2,
        "unit_price": 45.50,
        "total": 91.00
      }
    ],
    "subtotal": 91.00,
    "tax": 7.28,
    "total": 98.28,
    "currency": "USD",
    "status": "pending",
    "created_at": "2026-01-31T10:30:00Z"
  }
}
```

**order.cancelled**
```json
{
  "event": "order.cancelled",
  "timestamp": "2026-01-31T11:15:00Z",
  "data": {
    "order_id": "ORD-2026-001234",
    "customer_id": "CUST-56789",
    "reason": "customer_request",
    "cancelled_at": "2026-01-31T11:15:00Z",
    "refund_amount": 98.28
  }
}
```

#### 3.2.2 Payment Events

**payment.completed**
```json
{
  "event": "payment.completed",
  "timestamp": "2026-01-31T10:32:00Z",
  "data": {
    "payment_id": "PAY-789456",
    "order_id": "ORD-2026-001234",
    "amount": 98.28,
    "currency": "USD",
    "method": "credit_card",
    "transaction_id": "txn_1234567890",
    "processed_at": "2026-01-31T10:32:00Z"
  }
}
```

**payment.failed**
```json
{
  "event": "payment.failed",
  "timestamp": "2026-01-31T10:31:30Z",
  "data": {
    "payment_id": "PAY-789456",
    "order_id": "ORD-2026-001234",
    "amount": 98.28,
    "currency": "USD",
    "method": "credit_card",
    "error_code": "card_declined",
    "error_message": "Your card was declined.",
    "attempted_at": "2026-01-31T10:31:30Z"
  }
}
```

#### 3.2.3 Shipment Events

**shipment.delivered**
```json
{
  "event": "shipment.delivered",
  "timestamp": "2026-02-01T14:20:00Z",
  "data": {
    "shipment_id": "SHIP-345678",
    "order_id": "ORD-2026-001234",
    "carrier": "FastDelivery",
    "tracking_number": "FD123456789",
    "delivered_at": "2026-02-01T14:20:00Z",
    "recipient": "John Doe",
    "delivery_location": "Front Porch"
  }
}
```

**shipment.updated**
```json
{
  "event": "shipment.updated",
  "timestamp": "2026-02-01T08:00:00Z",
  "data": {
    "shipment_id": "SHIP-345678",
    "order_id": "ORD-2026-001234",
    "carrier": "FastDelivery",
    "tracking_number": "FD123456789",
    "status": "in_transit",
    "location": "Distribution Center - Chicago",
    "updated_at": "2026-02-01T08:00:00Z",
    "estimated_delivery": "2026-02-01T16:00:00Z"
  }
}
```

#### 3.2.4 User Events

**user.registered**
```json
{
  "event": "user.registered",
  "timestamp": "2026-01-31T09:00:00Z",
  "data": {
    "user_id": "USER-98765",
    "email": "customer@example.com",
    "first_name": "John",
    "last_name": "Doe",
    "phone": "+1-555-0123",
    "registered_at": "2026-01-31T09:00:00Z",
    "verification_status": "pending"
  }
}
```

---

## 4. Payload Structure

### 4.1 Standard Webhook Format

All webhook payloads follow a consistent envelope structure:

```json
{
  "id": "wh_1234567890abcdef",
  "event": "order.created",
  "timestamp": "2026-01-31T10:30:00Z",
  "webhook_id": "wh_sub_987654321",
  "attempt": 1,
  "data": {
    // Event-specific payload
  }
}
```

### 4.2 Event Envelope Fields

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `id` | string | Yes | Unique webhook delivery ID |
| `event` | string | Yes | Event type identifier |
| `timestamp` | ISO 8601 | Yes | Event occurrence time (UTC) |
| `webhook_id` | string | Yes | Subscription identifier |
| `attempt` | integer | Yes | Delivery attempt number |
| `data` | object | Yes | Event-specific data payload |

### 4.3 HTTP Headers

| Header | Value | Description |
|--------|-------|-------------|
| `Content-Type` | `application/json` | Payload format |
| `User-Agent` | `SmartDairy-Webhook/1.0` | Sender identification |
| `X-Webhook-ID` | `wh_123456...` | Unique delivery ID |
| `X-Event-Type` | `order.created` | Event type |
| `X-Webhook-Signature` | `sha256=...` | HMAC signature |
| `X-Webhook-Timestamp` | `1706701800` | Unix timestamp |
| `X-Webhook-Version` | `1.0` | Webhook API version |

---

## 5. Security

### 5.1 Security Overview

```
+------------------------------------------------------------------+
|                    WEBHOOK SECURITY LAYERS                       |
+------------------------------------------------------------------+
|                                                                   |
|  Layer 1: Transport Security                                      |
|  +-- TLS 1.2+ required                                            |
|  +-- Certificate validation                                       |
|  +-- HSTS enforcement                                             |
|                                                                   |
|  Layer 2: Authentication                                          |
|  +-- HMAC-SHA256 signature                                        |
|  +-- Timestamp validation                                         |
|  +-- Secret rotation                                              |
|                                                                   |
|  Layer 3: Access Control                                          |
|  +-- IP whitelisting                                              |
|  +-- Rate limiting                                                |
|  +-- Domain validation                                            |
|                                                                   |
|  Layer 4: Payload Protection                                      |
|  +-- Replay attack prevention                                     |
|  +-- Idempotency checks                                           |
|  +-- Payload encryption (optional)                                |
|                                                                   |
+------------------------------------------------------------------+
```

### 5.2 HMAC Signature Verification

```python
import hmac
import hashlib
import base64
import time
from typing import Optional

class WebhookSignatureVerifier:
    """Verifies webhook signatures and prevents replay attacks."""
    
    MAX_WEBHOOK_AGE = 300  # 5 minutes
    
    def __init__(self, secret: str):
        self.secret = secret
        self.received_ids = set()
    
    def verify_signature(
        self,
        payload: bytes,
        signature_header: str,
        timestamp_header: str,
        webhook_id: str
    ) -> bool:
        # Check for replay attack
        if webhook_id in self.received_ids:
            raise WebhookVerificationError("Duplicate webhook ID detected")
        
        # Validate timestamp
        try:
            timestamp = int(timestamp_header)
        except (ValueError, TypeError):
            raise WebhookVerificationError("Invalid timestamp format")
        
        current_time = int(time.time())
        age = abs(current_time - timestamp)
        
        if age > self.MAX_WEBHOOK_AGE:
            raise WebhookVerificationError(
                f"Webhook too old: {age}s > {self.MAX_WEBHOOK_AGE}s"
            )
        
        # Verify signature
        expected_signature = self._compute_signature(payload, timestamp_header)
        
        if not self._signatures_match(signature_header, expected_signature):
            raise WebhookVerificationError("Signature mismatch")
        
        self.received_ids.add(webhook_id)
        return True
    
    def _compute_signature(self, payload: bytes, timestamp: str) -> str:
        signed_content = f"{timestamp}.".encode() + payload
        signature = hmac.new(
            self.secret.encode('utf-8'),
            signed_content,
            hashlib.sha256
        ).digest()
        return f"sha256={base64.b64encode(signature).decode('utf-8')}"
    
    def _signatures_match(self, sig1: str, sig2: str) -> bool:
        return hmac.compare_digest(sig1, sig2)


class WebhookVerificationError(Exception):
    """Raised when webhook verification fails."""
    pass
```

### 5.3 TLS Requirements

```python
import ssl
import httpx

def create_secure_ssl_context() -> ssl.SSLContext:
    """Create SSL context with secure defaults."""
    context = ssl.create_default_context()
    
    # Minimum TLS 1.2
    context.minimum_version = ssl.TLSVersion.TLSv1_2
    
    # Disable insecure protocols
    context.options |= ssl.OP_NO_SSLv2
    context.options |= ssl.OP_NO_SSLv3
    context.options |= ssl.OP_NO_TLSv1
    context.options |= ssl.OP_NO_TLSv1_1
    
    # Certificate verification
    context.check_hostname = True
    context.verify_mode = ssl.CERT_REQUIRED
    
    return context
```

### 5.4 IP Whitelisting

```python
import ipaddress
from typing import List

class IPWhitelist:
    """IP whitelist validator for webhook consumers."""
    
    def __init__(self, allowed_ranges: List[str]):
        self.allowed_networks = [
            ipaddress.ip_network(range_str)
            for range_str in allowed_ranges
        ]
    
    def is_allowed(self, ip: str) -> bool:
        """Check if IP address is in allowed ranges."""
        try:
            addr = ipaddress.ip_address(ip)
            return any(addr in network for network in self.allowed_networks)
        except ValueError:
            return False


# Smart Dairy Production IPs
ALLOWED_IPS = [
    "203.0.113.0/24",
    "198.51.100.0/24",
]

whitelist = IPWhitelist(ALLOWED_IPS)
```

### 5.5 Replay Attack Prevention

```python
import redis

class RedisReplayPrevention:
    """Redis-based replay attack prevention."""
    
    def __init__(self, redis_client: redis.Redis, ttl: int = 3600):
        self.redis = redis_client
        self.ttl = ttl
        self.key_prefix = "webhook:processed"
    
    def is_processed(self, webhook_id: str) -> bool:
        """Check if webhook has been processed."""
        key = f"{self.key_prefix}:{webhook_id}"
        return self.redis.exists(key) == 1
    
    def mark_processed(self, webhook_id: str) -> None:
        """Mark webhook as processed."""
        key = f"{self.key_prefix}:{webhook_id}"
        self.redis.setex(key, self.ttl, "1")
    
    def validate_and_mark(self, webhook_id: str) -> bool:
        """Validate webhook is not a replay and mark as processed."""
        key = f"{self.key_prefix}:{webhook_id}"
        result = self.redis.set(key, "1", nx=True, ex=self.ttl)
        return result is not None
```

### 5.6 Secret Rotation

```python
from datetime import datetime, timedelta
from typing import Dict, Optional
import secrets
import hmac
import hashlib
import base64

class SecretManager:
    """Manages webhook secret rotation."""
    
    def __init__(self):
        self.secrets: Dict[str, Dict] = {}
        self.rotation_period = timedelta(days=90)
    
    def generate_secret(self, webhook_id: str) -> str:
        """Generate new cryptographically secure secret."""
        secret = secrets.token_urlsafe(32)
        
        self.secrets[webhook_id] = {
            "current": secret,
            "previous": None,
            "created_at": datetime.utcnow(),
            "rotated_at": None
        }
        
        return secret
    
    def rotate_secret(self, webhook_id: str) -> str:
        """Rotate secret, keeping previous for grace period."""
        if webhook_id not in self.secrets:
            raise ValueError(f"No secret found for {webhook_id}")
        
        old_secret = self.secrets[webhook_id]["current"]
        new_secret = secrets.token_urlsafe(32)
        
        self.secrets[webhook_id]["previous"] = old_secret
        self.secrets[webhook_id]["current"] = new_secret
        self.secrets[webhook_id]["rotated_at"] = datetime.utcnow()
        
        return new_secret
    
    def get_secret(self, webhook_id: str) -> Optional[str]:
        """Get current secret for webhook."""
        if webhook_id in self.secrets:
            return self.secrets[webhook_id]["current"]
        return None
    
    def verify_with_rotation(
        self,
        webhook_id: str,
        payload: bytes,
        signature: str,
        timestamp: str
    ) -> bool:
        """Verify signature using current or previous secret."""
        if webhook_id not in self.secrets:
            return False
        
        secret_data = self.secrets[webhook_id]
        
        # Try current secret
        if self._verify_signature(payload, signature, timestamp, secret_data["current"]):
            return True
        
        # Try previous secret (grace period)
        if secret_data["previous"]:
            return self._verify_signature(
                payload, signature, timestamp, secret_data["previous"]
            )
        
        return False
    
    def _verify_signature(self, payload: bytes, signature: str, timestamp: str, secret: str) -> bool:
        signed_content = f"{timestamp}.".encode() + payload
        expected = hmac.new(
            secret.encode(),
            signed_content,
            hashlib.sha256
        ).digest()
        expected_b64 = f"sha256={base64.b64encode(expected).decode()}"
        
        return hmac.compare_digest(signature, expected_b64)
```

---

## 6. Implementation

### 6.1 Webhook Server with FastAPI

```python
"""
Smart Dairy Webhook Server
FastAPI-based webhook receiver with signature verification
"""

from fastapi import FastAPI, Request, HTTPException, Depends, BackgroundTasks
from fastapi.responses import JSONResponse
from contextlib import asynccontextmanager
import logging
import json

from webhook_security import WebhookSignatureVerifier, WebhookVerificationError
from webhook_processor import WebhookProcessor
from idempotency import IdempotencyChecker
from event_dispatcher import EventDispatcher

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

WEBHOOK_SECRET = "whsec_your_webhook_secret_here"


@asynccontextmanager
async def lifespan(app: FastAPI):
    logger.info("Starting Webhook Server...")
    app.state.processor = WebhookProcessor()
    app.state.idempotency = IdempotencyChecker()
    app.state.dispatcher = EventDispatcher()
    yield
    logger.info("Shutting down Webhook Server...")


app = FastAPI(
    title="Smart Dairy Webhook Server",
    description="Webhook receiver for Smart Dairy event notifications",
    version="1.0.0",
    lifespan=lifespan
)


async def verify_webhook(request: Request) -> dict:
    signature = request.headers.get("X-Webhook-Signature")
    timestamp = request.headers.get("X-Webhook-Timestamp")
    webhook_id = request.headers.get("X-Webhook-ID")
    event_type = request.headers.get("X-Event-Type")
    
    if not all([signature, timestamp, webhook_id]):
        raise HTTPException(status_code=400, detail="Missing required headers")
    
    body = await request.body()
    verifier = WebhookSignatureVerifier(WEBHOOK_SECRET)
    
    try:
        verifier.verify_signature(body, signature, timestamp, webhook_id)
    except WebhookVerificationError as e:
        logger.warning(f"Webhook verification failed: {e}")
        raise HTTPException(status_code=401, detail=str(e))
    
    try:
        payload = json.loads(body)
    except json.JSONDecodeError:
        raise HTTPException(status_code=400, detail="Invalid JSON payload")
    
    return {
        "payload": payload,
        "webhook_id": webhook_id,
        "event_type": event_type,
        "timestamp": timestamp
    }


@app.post("/webhooks/smart-dairy")
async def receive_webhook(
    background_tasks: BackgroundTasks,
    verified: dict = Depends(verify_webhook)
):
    payload = verified["payload"]
    webhook_id = verified["webhook_id"]
    event_type = verified["event_type"]
    
    # Check idempotency
    if not app.state.idempotency.check_and_set(webhook_id):
        logger.info(f"Duplicate webhook received: {webhook_id}")
        return JSONResponse(
            status_code=200,
            content={"status": "already_processed", "webhook_id": webhook_id}
        )
    
    # Queue for async processing
    background_tasks.add_task(
        process_webhook_async,
        payload,
        webhook_id,
        event_type
    )
    
    logger.info(f"Webhook {webhook_id} of type {event_type} accepted")
    
    return JSONResponse(
        status_code=202,
        content={
            "status": "accepted",
            "webhook_id": webhook_id,
            "message": "Webhook queued for processing"
        }
    )


async def process_webhook_async(payload: dict, webhook_id: str, event_type: str):
    try:
        logger.info(f"Processing webhook {webhook_id}")
        result = await app.state.dispatcher.dispatch(event_type, payload)
        logger.info(f"Webhook {webhook_id} processed successfully: {result}")
    except Exception as e:
        logger.error(f"Failed to process webhook {webhook_id}: {e}")


@app.get("/health")
async def health_check():
    return {"status": "healthy", "service": "webhook-server"}


if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
```

### 6.2 Event Dispatcher

```python
"""
Event Dispatcher for Smart Dairy Webhooks
Routes events to appropriate handlers based on event type.
"""

from typing import Callable, Dict, Any
import logging
import asyncio

logger = logging.getLogger(__name__)


class EventDispatcher:
    """Dispatches webhook events to registered handlers."""
    
    def __init__(self):
        self.handlers: Dict[str, Callable] = {}
        self.register_default_handlers()
    
    def register(self, event_type: str, handler: Callable):
        self.handlers[event_type] = handler
        logger.info(f"Registered handler for {event_type}")
    
    def register_default_handlers(self):
        self.register("order.created", self.handle_order_created)
        self.register("order.updated", self.handle_order_updated)
        self.register("order.cancelled", self.handle_order_cancelled)
        self.register("payment.completed", self.handle_payment_completed)
        self.register("payment.failed", self.handle_payment_failed)
        self.register("shipment.delivered", self.handle_shipment_delivered)
        self.register("user.registered", self.handle_user_registered)
    
    async def dispatch(self, event_type: str, payload: Dict[str, Any]) -> Any:
        handler = self.handlers.get(event_type)
        
        if not handler:
            logger.warning(f"No handler registered for event type: {event_type}")
            return {"status": "no_handler", "event_type": event_type}
        
        try:
            if asyncio.iscoroutinefunction(handler):
                return await handler(payload)
            else:
                return handler(payload)
        except Exception as e:
            logger.error(f"Handler for {event_type} failed: {e}")
            raise
    
    async def handle_order_created(self, payload: Dict) -> Dict:
        order_id = payload.get("data", {}).get("order_id")
        logger.info(f"Processing new order: {order_id}")
        return {"status": "processed", "order_id": order_id}
    
    async def handle_order_updated(self, payload: Dict) -> Dict:
        order_id = payload.get("data", {}).get("order_id")
        logger.info(f"Processing order update: {order_id}")
        return {"status": "processed", "order_id": order_id}
    
    async def handle_order_cancelled(self, payload: Dict) -> Dict:
        order_id = payload.get("data", {}).get("order_id")
        reason = payload.get("data", {}).get("reason")
        logger.info(f"Processing order cancellation: {order_id}, reason: {reason}")
        return {"status": "cancelled", "order_id": order_id}
    
    async def handle_payment_completed(self, payload: Dict) -> Dict:
        payment_id = payload.get("data", {}).get("payment_id")
        order_id = payload.get("data", {}).get("order_id")
        amount = payload.get("data", {}).get("amount")
        logger.info(f"Payment completed: {payment_id} for order {order_id}, amount: {amount}")
        return {"status": "payment_recorded", "payment_id": payment_id}
    
    async def handle_payment_failed(self, payload: Dict) -> Dict:
        payment_id = payload.get("data", {}).get("payment_id")
        error_code = payload.get("data", {}).get("error_code")
        logger.info(f"Payment failed: {payment_id}, error: {error_code}")
        return {"status": "failure_recorded", "payment_id": payment_id}
    
    async def handle_shipment_delivered(self, payload: Dict) -> Dict:
        shipment_id = payload.get("data", {}).get("shipment_id")
        order_id = payload.get("data", {}).get("order_id")
        logger.info(f"Shipment delivered: {shipment_id} for order {order_id}")
        return {"status": "delivery_recorded", "shipment_id": shipment_id}
    
    async def handle_user_registered(self, payload: Dict) -> Dict:
        user_id = payload.get("data", {}).get("user_id")
        email = payload.get("data", {}).get("email")
        logger.info(f"New user registered: {user_id}, email: {email}")
        return {"status": "user_processed", "user_id": user_id}
```

### 6.3 Celery Tasks for Async Processing

```python
"""
Celery configuration for Smart Dairy webhook processing
"""

from celery import Celery
from celery.signals import task_failure, task_success
import logging

logger = logging.getLogger(__name__)

celery_app = Celery(
    "smart_dairy_webhooks",
    broker="redis://localhost:6379/0",
    backend="redis://localhost:6379/0",
    include=["webhook_tasks"]
)

celery_app.conf.update(
    task_serializer="json",
    accept_content=["json"],
    result_serializer="json",
    timezone="UTC",
    enable_utc=True,
    task_track_started=True,
    task_time_limit=300,
    task_soft_time_limit=240,
    worker_prefetch_multiplier=1,
    worker_max_tasks_per_child=1000,
    result_expires=3600,
    task_default_retry_delay=60,
    task_max_retries=5,
    task_routes={
        "webhook_tasks.process_webhook": {"queue": "webhooks"},
        "webhook_tasks.deliver_webhook": {"queue": "webhook_delivery"},
        "webhook_tasks.handle_retry": {"queue": "webhook_retries"},
    },
    task_annotations={
        "webhook_tasks.process_webhook": {"rate_limit": "100/s"},
        "webhook_tasks.deliver_webhook": {"rate_limit": "50/s"},
    }
)


@task_success.connect
def handle_task_success(sender=None, result=None, **kwargs):
    logger.info(f"Task {sender.name} completed successfully")


@task_failure.connect
def handle_task_failure(sender=None, task_id=None, exception=None, **kwargs):
    logger.error(f"Task {sender.name} failed: {exception}")
```

### 6.4 Webhook Tasks

```python
"""
Celery tasks for webhook processing
"""

from celery import Task
from celery.exceptions import SoftTimeLimitExceeded
import httpx
import json
import logging
from typing import Dict, Any
from datetime import datetime

from celery_config import celery_app

logger = logging.getLogger(__name__)


class WebhookTask(Task):
    def on_failure(self, exc, task_id, args, kwargs, einfo):
        logger.error(f"Task {task_id} failed: {exc}")
        super().on_failure(exc, task_id, args, kwargs, einfo)
    
    def on_retry(self, exc, task_id, args, kwargs, einfo):
        logger.warning(f"Task {task_id} retrying: {exc}")
        super().on_retry(exc, task_id, args, kwargs, einfo)


@celery_app.task(
    bind=True,
    base=WebhookTask,
    max_retries=5,
    default_retry_delay=60,
    autoretry_for=(Exception,),
    retry_backoff=True,
    retry_backoff_max=600,
    retry_jitter=True
)
def process_webhook(self, webhook_data: Dict[str, Any]):
    webhook_id = webhook_data.get("webhook_id")
    event_type = webhook_data.get("event_type")
    payload = webhook_data.get("payload")
    
    logger.info(f"Processing webhook {webhook_id}, event: {event_type}")
    
    try:
        if not payload or "data" not in payload:
            raise ValueError("Invalid webhook payload structure")
        
        result = process_event(event_type, payload)
        logger.info(f"Webhook {webhook_id} processed: {result}")
        return result
        
    except SoftTimeLimitExceeded:
        logger.error(f"Webhook {webhook_id} processing timed out")
        raise
    except Exception as exc:
        logger.error(f"Webhook {webhook_id} processing failed: {exc}")
        raise self.retry(exc=exc, countdown=calculate_retry_delay(self.request.retries))


@celery_app.task(
    bind=True,
    base=WebhookTask,
    max_retries=10,
    autoretry_for=(Exception,)
)
def deliver_webhook(
    self,
    subscription_id: str,
    endpoint_url: str,
    payload: Dict[str, Any],
    headers: Dict[str, str],
    secret: str
):
    import time
    import hmac
    import hashlib
    import base64
    
    webhook_id = headers.get("X-Webhook-ID")
    
    try:
        timestamp = str(int(time.time()))
        payload_bytes = json.dumps(payload).encode()
        
        signed_content = f"{timestamp}.".encode() + payload_bytes
        signature = hmac.new(
            secret.encode(),
            signed_content,
            hashlib.sha256
        ).digest()
        signature_b64 = base64.b64encode(signature).decode()
        
        headers["X-Webhook-Signature"] = f"sha256={signature_b64}"
        headers["X-Webhook-Timestamp"] = timestamp
        
        with httpx.Client(timeout=30.0) as client:
            response = client.post(
                endpoint_url,
                json=payload,
                headers=headers
            )
            
            logger.info(
                f"Webhook {webhook_id} delivered to {endpoint_url}: "
                f"status={response.status_code}"
            )
            
            if response.status_code >= 500:
                response.raise_for_status()
            elif response.status_code >= 400:
                logger.error(f"Client error {response.status_code}, not retrying")
                return {
                    "status": "failed",
                    "webhook_id": webhook_id,
                    "error": f"HTTP {response.status_code}"
                }
            
            return {
                "status": "delivered",
                "webhook_id": webhook_id,
                "http_status": response.status_code
            }
            
    except httpx.TimeoutException as exc:
        logger.warning(f"Webhook {webhook_id} delivery timed out")
        raise self.retry(exc=exc, countdown=calculate_retry_delay(self.request.retries))
    except Exception as exc:
        logger.error(f"Webhook {webhook_id} delivery failed: {exc}")
        raise self.retry(exc=exc, countdown=calculate_retry_delay(self.request.retries))


@celery_app.task
def handle_dead_letter(webhook_data: Dict[str, Any], failure_reason: str):
    webhook_id = webhook_data.get("webhook_id")
    logger.error(f"Webhook {webhook_id} moved to dead letter queue: {failure_reason}")
    
    store_in_dlq(webhook_data, failure_reason)
    notify_ops_team(webhook_id, failure_reason)
    
    return {"status": "moved_to_dlq", "webhook_id": webhook_id}


def calculate_retry_delay(retry_count: int) -> int:
    import random
    
    base_delay = 60
    max_delay = 3600
    delay = min(base_delay * (2 ** retry_count), max_delay)
    jitter = delay * 0.25
    delay = delay + random.uniform(-jitter, jitter)
    
    return int(delay)


def process_event(event_type: str, payload: Dict) -> Dict:
    processors = {
        "order.created": process_order_created,
        "payment.completed": process_payment_completed,
        "shipment.delivered": process_shipment_delivered,
        "user.registered": process_user_registered,
    }
    
    processor = processors.get(event_type)
    if processor:
        return processor(payload)
    
    return {"status": "unhandled", "event_type": event_type}


def process_order_created(payload: Dict) -> Dict:
    return {"event": "order.created", "processed": True}


def process_payment_completed(payload: Dict) -> Dict:
    return {"event": "payment.completed", "processed": True}


def process_shipment_delivered(payload: Dict) -> Dict:
    return {"event": "shipment.delivered", "processed": True}


def process_user_registered(payload: Dict) -> Dict:
    return {"event": "user.registered", "processed": True}


def store_in_dlq(webhook_data: Dict, failure_reason: str):
    pass


def notify_ops_team(webhook_id: str, failure_reason: str):
    pass
```

---

## 7. Queue Management

### 7.1 Redis Queue Implementation

```python
"""
Redis-based Queue Management for Smart Dairy Webhooks
"""

import redis
import json
import uuid
from datetime import datetime, timedelta
from typing import Dict, Any, Optional, List
from enum import Enum
import logging

logger = logging.getLogger(__name__)


class WebhookStatus(Enum):
    PENDING = "pending"
    PROCESSING = "processing"
    DELIVERED = "delivered"
    FAILED = "failed"
    RETRYING = "retrying"
    DEAD_LETTER = "dead_letter"


class RedisWebhookQueue:
    def __init__(self, redis_client: redis.Redis):
        self.redis = redis_client
        self.queue_key = "webhooks:queue"
        self.processing_key = "webhooks:processing"
        self.dlq_key = "webhooks:dlq"
        self.stats_key = "webhooks:stats"
    
    def enqueue(
        self,
        event_type: str,
        payload: Dict[str, Any],
        subscription_id: str,
        priority: int = 5
    ) -> str:
        webhook_id = f"wh_{uuid.uuid4().hex}"
        
        webhook_data = {
            "id": webhook_id,
            "event_type": event_type,
            "payload": payload,
            "subscription_id": subscription_id,
            "status": WebhookStatus.PENDING.value,
            "priority": priority,
            "created_at": datetime.utcnow().isoformat(),
            "attempt_count": 0,
            "next_retry_at": None
        }
        
        self.redis.hset(f"webhook:{webhook_id}", mapping={
            "data": json.dumps(webhook_data)
        })
        
        score = priority * 1000000 + datetime.utcnow().timestamp()
        self.redis.zadd(self.queue_key, {webhook_id: score})
        
        logger.info(f"Enqueued webhook {webhook_id} for {event_type}")
        
        return webhook_id
    
    def dequeue(self, timeout: int = 30) -> Optional[Dict[str, Any]]:
        result = self.redis.zpopmin(self.queue_key, count=1)
        
        if not result:
            return None
        
        webhook_id, _ = result[0]
        webhook_id = webhook_id.decode() if isinstance(webhook_id, bytes) else webhook_id
        
        data = self.redis.hget(f"webhook:{webhook_id}", "data")
        if not data:
            return None
        
        webhook_data = json.loads(data)
        webhook_data["status"] = WebhookStatus.PROCESSING.value
        
        self.redis.sadd(self.processing_key, webhook_id)
        self.redis.hset(f"webhook:{webhook_id}", "data", json.dumps(webhook_data))
        
        return webhook_data
    
    def mark_delivered(self, webhook_id: str, response_status: int):
        self.redis.srem(self.processing_key, webhook_id)
        
        data = self.redis.hget(f"webhook:{webhook_id}", "data")
        if data:
            webhook_data = json.loads(data)
            webhook_data["status"] = WebhookStatus.DELIVERED.value
            webhook_data["delivered_at"] = datetime.utcnow().isoformat()
            webhook_data["response_status"] = response_status
            
            self.redis.hset(f"webhook:{webhook_id}", "data", json.dumps(webhook_data))
            self.redis.expire(f"webhook:{webhook_id}", 7 * 24 * 3600)
        
        self.redis.hincrby(self.stats_key, "delivered_count", 1)
        logger.info(f"Webhook {webhook_id} marked as delivered")
    
    def mark_failed(
        self,
        webhook_id: str,
        error_message: str,
        retry_count: int
    ) -> bool:
        MAX_RETRIES = 10
        
        data = self.redis.hget(f"webhook:{webhook_id}", "data")
        if not data:
            return False
        
        webhook_data = json.loads(data)
        webhook_data["attempt_count"] = retry_count
        webhook_data["last_error"] = error_message
        
        if retry_count >= MAX_RETRIES:
            webhook_data["status"] = WebhookStatus.DEAD_LETTER.value
            webhook_data["moved_to_dlq_at"] = datetime.utcnow().isoformat()
            
            self.redis.hset(f"webhook:{webhook_id}", "data", json.dumps(webhook_data))
            self.redis.lpush(self.dlq_key, webhook_id)
            self.redis.srem(self.processing_key, webhook_id)
            
            logger.error(f"Webhook {webhook_id} moved to DLQ after {retry_count} retries")
            return False
        else:
            delay = min(60 * (2 ** retry_count), 3600)
            next_retry = datetime.utcnow() + timedelta(seconds=delay)
            
            webhook_data["status"] = WebhookStatus.RETRYING.value
            webhook_data["next_retry_at"] = next_retry.isoformat()
            
            self.redis.hset(f"webhook:{webhook_id}", "data", json.dumps(webhook_data))
            
            score = next_retry.timestamp()
            self.redis.zadd("webhooks:retry_queue", {webhook_id: score})
            self.redis.srem(self.processing_key, webhook_id)
            
            logger.info(f"Webhook {webhook_id} scheduled for retry {retry_count}")
            return True
    
    def get_stats(self) -> Dict[str, int]:
        stats = self.redis.hgetall(self.stats_key)
        return {
            k.decode() if isinstance(k, bytes) else k: 
            int(v) if isinstance(v, bytes) else int(v)
            for k, v in stats.items()
        }
    
    def get_dlq_contents(self, limit: int = 100) -> List[Dict[str, Any]]:
        webhook_ids = self.redis.lrange(self.dlq_key, 0, limit - 1)
        
        results = []
        for webhook_id in webhook_ids:
            webhook_id = webhook_id.decode() if isinstance(webhook_id, bytes) else webhook_id
            data = self.redis.hget(f"webhook:{webhook_id}", "data")
            if data:
                results.append(json.loads(data))
        
        return results
```

---

## 8. Retry Logic

### 8.1 Exponential Backoff Implementation

```python
"""
Retry Mechanism with Exponential Backoff for Smart Dairy Webhooks
"""

import random
import time
from typing import Callable, Any, Optional
from functools import wraps
from dataclasses import dataclass
from enum import Enum
import logging

logger = logging.getLogger(__name__)


class RetryStrategy(Enum):
    FIXED = "fixed"
    LINEAR = "linear"
    EXPONENTIAL = "exponential"
    EXPONENTIAL_WITH_JITTER = "exponential_with_jitter"


@dataclass
class RetryConfig:
    max_retries: int = 5
    base_delay: float = 1.0
    max_delay: float = 300.0
    strategy: RetryStrategy = RetryStrategy.EXPONENTIAL_WITH_JITTER
    retryable_exceptions: tuple = (Exception,)


class RetryMechanism:
    def __init__(self, config: RetryConfig = None):
        self.config = config or RetryConfig()
    
    def calculate_delay(self, attempt: int) -> float:
        strategy = self.config.strategy
        base = self.config.base_delay
        max_delay = self.config.max_delay
        
        if strategy == RetryStrategy.FIXED:
            delay = base
        elif strategy == RetryStrategy.LINEAR:
            delay = base * (attempt + 1)
        elif strategy == RetryStrategy.EXPONENTIAL:
            delay = base * (2 ** attempt)
        elif strategy == RetryStrategy.EXPONENTIAL_WITH_JITTER:
            exp_delay = base * (2 ** attempt)
            delay = random.uniform(0, min(exp_delay, max_delay))
        else:
            delay = base
        
        return min(delay, max_delay)
    
    def execute(self, func: Callable, *args, **kwargs) -> Any:
        last_exception = None
        
        for attempt in range(self.config.max_retries + 1):
            try:
                return func(*args, **kwargs)
            except self.config.retryable_exceptions as e:
                last_exception = e
                
                if attempt < self.config.max_retries:
                    delay = self.calculate_delay(attempt)
                    logger.warning(
                        f"Attempt {attempt + 1} failed: {e}. "
                        f"Retrying in {delay:.2f}s..."
                    )
                    time.sleep(delay)
                else:
                    logger.error(f"Max retries ({self.config.max_retries}) exceeded")
                    raise last_exception
        
        raise last_exception


def with_retry(
    max_retries: int = 5,
    base_delay: float = 1.0,
    max_delay: float = 300.0,
    strategy: RetryStrategy = RetryStrategy.EXPONENTIAL_WITH_JITTER,
    retryable_exceptions: tuple = (Exception,)
):
    def decorator(func: Callable) -> Callable:
        config = RetryConfig(
            max_retries=max_retries,
            base_delay=base_delay,
            max_delay=max_delay,
            strategy=strategy,
            retryable_exceptions=retryable_exceptions
        )
        retry_mechanism = RetryMechanism(config)
        
        @wraps(func)
        def wrapper(*args, **kwargs):
            return retry_mechanism.execute(func, *args, **kwargs)
        
        return wrapper
    
    return decorator


WEBHOOK_RETRY_CONFIG = RetryConfig(
    max_retries=10,
    base_delay=60.0,
    max_delay=3600.0,
    strategy=RetryStrategy.EXPONENTIAL_WITH_JITTER,
    retryable_exceptions=(
        ConnectionError,
        TimeoutError,
        Exception
    )
)


class WebhookRetryHandler:
    HTTP_RETRYABLE_STATUS = {408, 429, 500, 502, 503, 504}
    
    def __init__(self):
        self.retry_mechanism = RetryMechanism(WEBHOOK_RETRY_CONFIG)
    
    def should_retry(self, status_code: int, exception: Optional[Exception] = None) -> bool:
        if exception and isinstance(exception, (ConnectionError, TimeoutError)):
            return True
        
        if status_code in self.HTTP_RETRYABLE_STATUS:
            return True
        
        if 400 <= status_code < 500:
            return False
        
        return False
```

### 8.2 Dead Letter Queue Handler

```python
"""
Dead Letter Queue Handler for Smart Dairy Webhooks
"""

from typing import Dict, Any, List, Callable
from datetime import datetime, timedelta
import json
import logging

logger = logging.getLogger(__name__)


class DeadLetterQueueHandler:
    def __init__(self, storage_backend):
        self.storage = storage_backend
        self.notification_callbacks: List[Callable] = []
    
    def add_notification_callback(self, callback: Callable):
        self.notification_callbacks.append(callback)
    
    def move_to_dlq(
        self,
        webhook_id: str,
        payload: Dict[str, Any],
        failure_history: List[Dict],
        final_error: str
    ):
        dlq_entry = {
            "webhook_id": webhook_id,
            "payload": payload,
            "failure_history": failure_history,
            "final_error": final_error,
            "moved_at": datetime.utcnow().isoformat(),
            "status": "failed",
            "manual_review_status": "pending"
        }
        
        self.storage.store(dlq_entry)
        logger.error(f"Webhook {webhook_id} moved to DLQ: {final_error}")
        
        for callback in self.notification_callbacks:
            try:
                callback(dlq_entry)
            except Exception as e:
                logger.error(f"Notification callback failed: {e}")
    
    def get_dlq_entries(
        self,
        status: str = None,
        since: datetime = None,
        limit: int = 100
    ) -> List[Dict[str, Any]]:
        return self.storage.query(status=status, since=since, limit=limit)
    
    def retry_from_dlq(self, webhook_id: str) -> bool:
        entry = self.storage.get(webhook_id)
        
        if not entry:
            logger.error(f"DLQ entry {webhook_id} not found")
            return False
        
        entry["status"] = "retrying"
        entry["retry_requested_at"] = datetime.utcnow().isoformat()
        entry["manual_review_status"] = "approved"
        
        self.storage.update(entry)
        logger.info(f"Webhook {webhook_id} scheduled for retry from DLQ")
        
        return True
    
    def archive_entry(self, webhook_id: str):
        entry = self.storage.get(webhook_id)
        
        if entry:
            entry["status"] = "archived"
            entry["archived_at"] = datetime.utcnow().isoformat()
            self.storage.update(entry)
            logger.info(f"DLQ entry {webhook_id} archived")
```

---

## 9. Idempotency

### 9.1 Idempotency Checker Implementation

```python
"""
Idempotency Checker for Smart Dairy Webhooks
Prevents duplicate processing of webhook deliveries.
"""

import hashlib
import json
from typing import Optional, Dict, Any
from datetime import datetime, timedelta
from enum import Enum
import logging

logger = logging.getLogger(__name__)


class IdempotencyStatus(Enum):
    NEW = "new"
    PROCESSING = "processing"
    PROCESSED = "processed"
    FAILED = "failed"


class IdempotencyChecker:
    def __init__(self, redis_client, ttl: int = 86400):
        self.redis = redis_client
        self.ttl = ttl
        self.key_prefix = "idempotency:webhook"
    
    def _get_key(self, webhook_id: str) -> str:
        return f"{self.key_prefix}:{webhook_id}"
    
    def check(self, webhook_id: str) -> IdempotencyStatus:
        key = self._get_key(webhook_id)
        status = self.redis.get(key)
        
        if status is None:
            return IdempotencyStatus.NEW
        
        status_str = status.decode() if isinstance(status, bytes) else status
        return IdempotencyStatus(status_str)
    
    def check_and_set(self, webhook_id: str, payload: Dict[str, Any] = None) -> bool:
        key = self._get_key(webhook_id)
        
        result = self.redis.set(
            key,
            IdempotencyStatus.PROCESSING.value,
            nx=True,
            ex=self.ttl
        )
        
        if result:
            logger.info(f"Webhook {webhook_id} marked as processing")
            return True
        
        status = self.check(webhook_id)
        
        if status == IdempotencyStatus.PROCESSING:
            logger.warning(f"Webhook {webhook_id} is already being processed")
        elif status == IdempotencyStatus.PROCESSED:
            logger.info(f"Webhook {webhook_id} already processed (idempotent)")
        elif status == IdempotencyStatus.FAILED:
            self.redis.setex(key, self.ttl, IdempotencyStatus.PROCESSING.value)
            logger.info(f"Webhook {webhook_id} retrying after previous failure")
            return True
        
        return False
    
    def mark_processed(self, webhook_id: str, result: Dict[str, Any] = None):
        key = self._get_key(webhook_id)
        
        data = {
            "status": IdempotencyStatus.PROCESSED.value,
            "processed_at": datetime.utcnow().isoformat()
        }
        
        if result:
            data["result"] = result
        
        self.redis.setex(key, self.ttl, json.dumps(data))
        logger.info(f"Webhook {webhook_id} marked as processed")
    
    def mark_failed(self, webhook_id: str, error: str):
        key = self._get_key(webhook_id)
        
        data = {
            "status": IdempotencyStatus.FAILED.value,
            "failed_at": datetime.utcnow().isoformat(),
            "error": error
        }
        
        self.redis.setex(key, self.ttl, json.dumps(data))
        logger.error(f"Webhook {webhook_id} marked as failed: {error}")
    
    def get_processed_count(self) -> int:
        pattern = f"{self.key_prefix}:*"
        keys = self.redis.keys(pattern)
        return len(keys)
```

### 9.2 Idempotent Webhook Handler

```python
"""
Idempotent Webhook Handler Base Class
"""

from abc import ABC, abstractmethod
from typing import Dict, Any
import logging

logger = logging.getLogger(__name__)


class IdempotentWebhookHandler(ABC):
    def __init__(self, idempotency_checker):
        self.idempotency = idempotency_checker
    
    async def handle(self, webhook_id: str, payload: Dict[str, Any]) -> Dict[str, Any]:
        if not self.idempotency.check_and_set(webhook_id, payload):
            status = self.idempotency.check(webhook_id)
            
            if status == IdempotencyStatus.PROCESSED:
                return {
                    "status": "already_processed",
                    "webhook_id": webhook_id,
                    "idempotent": True
                }
            elif status == IdempotencyStatus.PROCESSING:
                return {
                    "status": "processing",
                    "webhook_id": webhook_id,
                    "message": "Webhook is being processed"
                }
        
        try:
            result = await self.process(payload)
            self.idempotency.mark_processed(webhook_id, result)
            
            return {
                "status": "processed",
                "webhook_id": webhook_id,
                "result": result
            }
            
        except Exception as e:
            logger.error(f"Webhook {webhook_id} processing failed: {e}")
            self.idempotency.mark_failed(webhook_id, str(e))
            raise
    
    @abstractmethod
    async def process(self, payload: Dict[str, Any]) -> Dict[str, Any]:
        """Process the webhook payload. Must be idempotent."""
        pass
```

---

## 10. Verification

### 10.1 Webhook Signature Validation Helper

```python
"""
Webhook Signature Validation Helper
Complete utility for validating Smart Dairy webhook signatures.
"""

import hmac
import hashlib
import base64
import time
from typing import Dict, Any, Optional, Union
from dataclasses import dataclass
import logging

logger = logging.getLogger(__name__)


@dataclass
class VerificationResult:
    valid: bool
    webhook_id: str
    event_type: str
    timestamp: int
    error_message: Optional[str] = None


class WebhookValidator:
    MAX_WEBHOOK_AGE_SECONDS = 300
    
    def __init__(
        self,
        secret: str,
        max_age: int = 300,
        signature_header: str = "X-Webhook-Signature",
        timestamp_header: str = "X-Webhook-Timestamp",
        webhook_id_header: str = "X-Webhook-ID",
        event_type_header: str = "X-Event-Type"
    ):
        self.secret = secret
        self.max_age = max_age
        self.headers = {
            "signature": signature_header,
            "timestamp": timestamp_header,
            "webhook_id": webhook_id_header,
            "event_type": event_type_header
        }
    
    def validate(self, headers: Dict[str, str], body: Union[str, bytes]) -> VerificationResult:
        sig_header = headers.get(self.headers["signature"])
        ts_header = headers.get(self.headers["timestamp"])
        webhook_id = headers.get(self.headers["webhook_id"])
        event_type = headers.get(self.headers["event_type"])
        
        if not all([sig_header, ts_header, webhook_id]):
            missing = []
            if not sig_header:
                missing.append(self.headers["signature"])
            if not ts_header:
                missing.append(self.headers["timestamp"])
            if not webhook_id:
                missing.append(self.headers["webhook_id"])
            
            return VerificationResult(
                valid=False,
                webhook_id=webhook_id or "unknown",
                event_type=event_type or "unknown",
                timestamp=0,
                error_message=f"Missing required headers: {', '.join(missing)}"
            )
        
        try:
            timestamp = int(ts_header)
        except (ValueError, TypeError):
            return VerificationResult(
                valid=False,
                webhook_id=webhook_id,
                event_type=event_type,
                timestamp=0,
                error_message=f"Invalid timestamp format: {ts_header}"
            )
        
        current_time = int(time.time())
        age = current_time - timestamp
        
        if abs(age) > self.max_age:
            return VerificationResult(
                valid=False,
                webhook_id=webhook_id,
                event_type=event_type,
                timestamp=timestamp,
                error_message=f"Webhook too old: {abs(age)}s > {self.max_age}s"
            )
        
        if isinstance(body, str):
            body_bytes = body.encode('utf-8')
        else:
            body_bytes = body
        
        expected_sig = self._compute_signature(body_bytes, str(timestamp))
        
        if not self._signatures_match(sig_header, expected_sig):
            return VerificationResult(
                valid=False,
                webhook_id=webhook_id,
                event_type=event_type,
                timestamp=timestamp,
                error_message="Signature mismatch"
            )
        
        return VerificationResult(
            valid=True,
            webhook_id=webhook_id,
            event_type=event_type,
            timestamp=timestamp
        )
    
    def _compute_signature(self, body: bytes, timestamp: str) -> str:
        signed_content = f"{timestamp}.".encode() + body
        
        signature = hmac.new(
            self.secret.encode('utf-8'),
            signed_content,
            hashlib.sha256
        ).digest()
        
        return f"sha256={base64.b64encode(signature).decode('utf-8')}"
    
    def _signatures_match(self, sig1: str, sig2: str) -> bool:
        return hmac.compare_digest(sig1, sig2)
    
    def generate_signature(self, payload: Union[str, bytes], timestamp: Optional[int] = None) -> Dict[str, str]:
        if timestamp is None:
            timestamp = int(time.time())
        
        if isinstance(payload, str):
            payload_bytes = payload.encode('utf-8')
        else:
            payload_bytes = payload
        
        signature = self._compute_signature(payload_bytes, str(timestamp))
        
        return {
            self.headers["signature"]: signature,
            self.headers["timestamp"]: str(timestamp),
            "Content-Type": "application/json"
        }
```

---

## 11. Testing

### 11.1 Local Testing with ngrok

```python
"""
Local Webhook Testing Utilities
Using ngrok for exposing local development server
"""

import subprocess
import requests
from typing import Optional
import logging

logger = logging.getLogger(__name__)


class NgrokTunnel:
    def __init__(self, port: int = 8000, auth_token: Optional[str] = None):
        self.port = port
        self.auth_token = auth_token
        self.process = None
        self.public_url = None
    
    def start(self) -> str:
        cmd = ["ngrok", "http", str(self.port), "--log=stdout"]
        
        if self.auth_token:
            cmd.extend(["--authtoken", self.auth_token])
        
        self.process = subprocess.Popen(
            cmd,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE
        )
        
        import time
        time.sleep(2)
        
        try:
            response = requests.get("http://localhost:4040/api/tunnels")
            tunnels = response.json()["tunnels"]
            
            for tunnel in tunnels:
                if tunnel["proto"] == "https":
                    self.public_url = tunnel["public_url"]
                    break
            
            if not self.public_url:
                self.public_url = tunnels[0]["public_url"]
            
            logger.info(f"ngrok tunnel started: {self.public_url}")
            return self.public_url
            
        except Exception as e:
            logger.error(f"Failed to get ngrok URL: {e}")
            self.stop()
            raise
    
    def stop(self):
        if self.process:
            self.process.terminate()
            self.process.wait()
            logger.info("ngrok tunnel stopped")
    
    def __enter__(self):
        self.start()
        return self
    
    def __exit__(self, exc_type, exc_val, exc_tb):
        self.stop()


if __name__ == "__main__":
    with NgrokTunnel(port=8000) as tunnel:
        print(f"Webhook endpoint: {tunnel.public_url}/webhooks/smart-dairy")
        print("Press Ctrl+C to stop...")
        
        try:
            while True:
                import time
                time.sleep(1)
        except KeyboardInterrupt:
            pass
```

### 11.2 Webhook Test Client

```python
"""
Webhook Test Client for Smart Dairy
Simulates webhook delivery for testing purposes
"""

import httpx
import json
import hmac
import hashlib
import base64
import time
from typing import Dict, Any, Optional
from dataclasses import dataclass
import asyncio
from datetime import datetime


@dataclass
class TestWebhook:
    event_type: str
    payload: Dict[str, Any]
    secret: str = "whsec_test_secret"


class WebhookTestClient:
    def __init__(self, base_url: str, secret: str = "whsec_test_secret"):
        self.base_url = base_url.rstrip("/")
        self.secret = secret
    
    def _generate_signature(self, payload: bytes, timestamp: str) -> str:
        signed_content = f"{timestamp}.".encode() + payload
        signature = hmac.new(
            self.secret.encode(),
            signed_content,
            hashlib.sha256
        ).digest()
        return f"sha256={base64.b64encode(signature).decode('utf-8')}"
    
    async def send_webhook(
        self,
        event_type: str,
        payload: Dict[str, Any],
        webhook_id: Optional[str] = None
    ) -> httpx.Response:
        timestamp = str(int(time.time()))
        webhook_id = webhook_id or f"wh_test_{int(time.time() * 1000)}"
        
        full_payload = {
            "id": webhook_id,
            "event": event_type,
            "timestamp": datetime.utcnow().isoformat() + "Z",
            "webhook_id": webhook_id,
            "attempt": 1,
            "data": payload
        }
        
        body = json.dumps(full_payload).encode()
        signature = self._generate_signature(body, timestamp)
        
        headers = {
            "Content-Type": "application/json",
            "User-Agent": "SmartDairy-Webhook/1.0",
            "X-Webhook-ID": webhook_id,
            "X-Event-Type": event_type,
            "X-Webhook-Signature": signature,
            "X-Webhook-Timestamp": timestamp,
            "X-Webhook-Version": "1.0"
        }
        
        async with httpx.AsyncClient() as client:
            response = await client.post(
                f"{self.base_url}/webhooks/smart-dairy",
                content=body,
                headers=headers
            )
            return response
    
    async def test_order_created(self, order_id: str = "ORD-TEST-001"):
        payload = {
            "order_id": order_id,
            "customer_id": "CUST-TEST-001",
            "items": [
                {
                    "product_id": "PROD-MILK-001",
                    "quantity": 2,
                    "unit_price": 45.50,
                    "total": 91.00
                }
            ],
            "subtotal": 91.00,
            "tax": 7.28,
            "total": 98.28,
            "currency": "USD",
            "status": "pending",
            "created_at": datetime.utcnow().isoformat()
        }
        return await self.send_webhook("order.created", payload)
    
    async def test_payment_completed(self, order_id: str = "ORD-TEST-001"):
        payload = {
            "payment_id": "PAY-TEST-001",
            "order_id": order_id,
            "amount": 98.28,
            "currency": "USD",
            "method": "credit_card",
            "transaction_id": "txn_test_12345",
            "processed_at": datetime.utcnow().isoformat()
        }
        return await self.send_webhook("payment.completed", payload)
    
    async def test_shipment_delivered(self, order_id: str = "ORD-TEST-001"):
        payload = {
            "shipment_id": "SHIP-TEST-001",
            "order_id": order_id,
            "carrier": "TestCarrier",
            "tracking_number": "TEST123456",
            "delivered_at": datetime.utcnow().isoformat(),
            "recipient": "Test Customer",
            "delivery_location": "Front Door"
        }
        return await self.send_webhook("shipment.delivered", payload)
    
    async def test_user_registered(self):
        payload = {
            "user_id": "USER-TEST-001",
            "email": "test@example.com",
            "first_name": "Test",
            "last_name": "User",
            "phone": "+1-555-TEST",
            "registered_at": datetime.utcnow().isoformat(),
            "verification_status": "pending"
        }
        return await self.send_webhook("user.registered", payload)


async def run_tests():
    client = WebhookTestClient("http://localhost:8000")
    
    print("Testing order.created...")
    response = await client.test_order_created()
    print(f"  Status: {response.status_code}")
    print(f"  Response: {response.text}")
    
    print("\nTesting payment.completed...")
    response = await client.test_payment_completed()
    print(f"  Status: {response.status_code}")
    
    print("\nTesting shipment.delivered...")
    response = await client.test_shipment_delivered()
    print(f"  Status: {response.status_code}")
    
    print("\nTesting user.registered...")
    response = await client.test_user_registered()
    print(f"  Status: {response.status_code}")


if __name__ == "__main__":
    asyncio.run(run_tests())
```

---

## 12. Consumer Guidelines

### 12.1 Best Practices for Webhook Consumers

#### 12.1.1 Endpoint Requirements

| Requirement | Specification | Reason |
|-------------|---------------|--------|
| HTTPS Only | TLS 1.2+ | Security |
| Response Time | < 5 seconds | Timeout prevention |
| Status Code | 200 OK | Acknowledgment |
| Idempotency | Required | Duplicate safety |
| Signature Verification | Required | Authentication |

#### 12.1.2 Implementation Checklist

```python
"""
Webhook Consumer Best Practices Implementation Template
"""

from fastapi import FastAPI, Request, HTTPException, BackgroundTasks
from fastapi.responses import JSONResponse
import hmac
import hashlib
import base64
import time
import json
import logging

logger = logging.getLogger(__name__)
app = FastAPI()

WEBHOOK_SECRET = "your_webhook_secret"
MAX_WEBHOOK_AGE = 300


@app.post("/webhook")
async def receive_webhook(request: Request, background_tasks: BackgroundTasks):
    body = await request.body()
    
    signature = request.headers.get("X-Webhook-Signature")
    timestamp = request.headers.get("X-Webhook-Timestamp")
    webhook_id = request.headers.get("X-Webhook-ID")
    
    if not all([signature, timestamp, webhook_id]):
        logger.warning("Missing required headers")
        raise HTTPException(status_code=400, detail="Missing required headers")
    
    try:
        ts = int(timestamp)
        current_time = int(time.time())
        if abs(current_time - ts) > MAX_WEBHOOK_AGE:
            logger.warning(f"Webhook too old: webhook_id={webhook_id}")
            raise HTTPException(status_code=400, detail="Webhook timestamp too old")
    except ValueError:
        raise HTTPException(status_code=400, detail="Invalid timestamp")
    
    expected_signature = compute_signature(body, timestamp, WEBHOOK_SECRET)
    if not hmac.compare_digest(signature, expected_signature):
        logger.warning(f"Invalid signature: webhook_id={webhook_id}")
        raise HTTPException(status_code=401, detail="Invalid signature")
    
    if is_duplicate(webhook_id):
        logger.info(f"Duplicate webhook ignored: {webhook_id}")
        return JSONResponse({"status": "already_processed"})
    
    try:
        payload = json.loads(body)
    except json.JSONDecodeError:
        raise HTTPException(status_code=400, detail="Invalid JSON")
    
    background_tasks.add_task(process_webhook, webhook_id, payload)
    
    return JSONResponse(
        status_code=202,
        content={"status": "accepted", "webhook_id": webhook_id}
    )


def compute_signature(body: bytes, timestamp: str, secret: str) -> str:
    signed_content = f"{timestamp}.".encode() + body
    signature = hmac.new(
        secret.encode(),
        signed_content,
        hashlib.sha256
    ).digest()
    return f"sha256={base64.b64encode(signature).decode('utf-8')}"


def is_duplicate(webhook_id: str) -> bool:
    # Implement with Redis/database
    pass


def mark_processed(webhook_id: str):
    # Implement with Redis/database
    pass


async def process_webhook(webhook_id: str, payload: dict):
    try:
        event_type = payload.get("event")
        
        if event_type == "order.created":
            await handle_order_created(payload)
        elif event_type == "payment.completed":
            await handle_payment_completed(payload)
        
        mark_processed(webhook_id)
        logger.info(f"Webhook processed successfully: {webhook_id}")
        
    except Exception as e:
        logger.error(f"Webhook processing failed: {webhook_id}, error: {e}")


async def handle_order_created(payload: dict):
    pass


async def handle_payment_completed(payload: dict):
    pass
```

### 12.2 Consumer Do's and Don'ts

| Do | Don't |
|----|-------|
| Return 202 Accepted immediately | Process synchronously and timeout |
| Verify signatures | Trust payload without verification |
| Implement idempotency | Process same webhook multiple times |
| Handle retries gracefully | Return 4xx for transient errors |
| Log all webhooks received | Silently drop webhooks |
| Use HTTPS only | Accept HTTP webhooks |
| Validate timestamps | Accept old webhooks |
| Implement circuit breakers | Keep failing endpoints active |

---

## 13. Debugging

### 13.1 Logging Strategy

```python
"""
Webhook Logging and Tracing for Smart Dairy
"""

import logging
import json
from datetime import datetime
from typing import Dict, Any
from contextvars import ContextVar
import uuid

correlation_id: ContextVar[str] = ContextVar('correlation_id', default=None)

logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(correlation_id)s - %(levelname)s - %(message)s'
)


class CorrelationIdFilter(logging.Filter):
    def filter(self, record):
        record.correlation_id = correlation_id.get() or "N/A"
        return True


logger = logging.getLogger("smart_dairy_webhooks")
logger.addFilter(CorrelationIdFilter())


class WebhookLogger:
    def __init__(self, logger_name: str = "smart_dairy_webhooks"):
        self.logger = logging.getLogger(logger_name)
    
    def set_correlation_id(self, cid: str = None):
        cid = cid or str(uuid.uuid4())
        correlation_id.set(cid)
        return cid
    
    def log_webhook_received(
        self,
        webhook_id: str,
        event_type: str,
        source_ip: str,
        headers: Dict[str, str]
    ):
        self.set_correlation_id(webhook_id)
        
        self.logger.info(
            "Webhook received",
            extra={
                "webhook_id": webhook_id,
                "event_type": event_type,
                "source_ip": source_ip,
                "user_agent": headers.get("User-Agent"),
                "webhook_version": headers.get("X-Webhook-Version")
            }
        )
    
    def log_signature_verification(self, webhook_id: str, valid: bool, error: str = None):
        if valid:
            self.logger.info(f"Signature verified: {webhook_id}")
        else:
            self.logger.warning(f"Signature verification failed: {webhook_id}, error: {error}")
    
    def log_processing_start(self, webhook_id: str, event_type: str):
        self.logger.info(f"Processing started: {webhook_id}, event: {event_type}")
    
    def log_processing_complete(
        self,
        webhook_id: str,
        success: bool,
        duration_ms: float,
        result: Any = None
    ):
        if success:
            self.logger.info(
                f"Processing completed: {webhook_id}, duration: {duration_ms}ms",
                extra={"result": result}
            )
        else:
            self.logger.error(f"Processing failed: {webhook_id}, duration: {duration_ms}ms")
```

### 13.2 Common Issues and Solutions

| Issue | Symptoms | Solution |
|-------|----------|----------|
| Signature Mismatch | 401 errors | Check secret, verify encoding |
| Timeout | 504 errors | Return 202 immediately, process async |
| Replay Attacks | Duplicate processing | Validate timestamps, check IDs |
| Rate Limiting | 429 errors | Implement exponential backoff |
| SSL Errors | Connection failed | Verify TLS version, certificate |
| JSON Parsing | 400 errors | Validate payload before parsing |

---

## 14. Appendices

### Appendix A: Complete Code Examples

#### A.1 Webhook Client (Producer)

```python
"""
Smart Dairy Webhook Client
Sends webhooks to registered subscribers
"""

import httpx
import json
import hmac
import hashlib
import base64
import time
from typing import Dict, Any, Optional
from datetime import datetime
import logging

logger = logging.getLogger(__name__)


class WebhookClient:
    def __init__(self, secret: str):
        self.secret = secret
    
    def send(
        self,
        endpoint: str,
        event_type: str,
        payload: Dict[str, Any],
        webhook_id: Optional[str] = None
    ) -> httpx.Response:
        webhook_id = webhook_id or f"wh_{int(time.time() * 1000)}"
        timestamp = str(int(time.time()))
        
        envelope = {
            "id": webhook_id,
            "event": event_type,
            "timestamp": datetime.utcnow().isoformat() + "Z",
            "webhook_id": webhook_id,
            "attempt": 1,
            "data": payload
        }
        
        body = json.dumps(envelope).encode()
        
        signed_content = f"{timestamp}.".encode() + body
        signature = hmac.new(
            self.secret.encode(),
            signed_content,
            hashlib.sha256
        ).digest()
        signature_b64 = base64.b64encode(signature).decode()
        
        headers = {
            "Content-Type": "application/json",
            "User-Agent": "SmartDairy-Webhook/1.0",
            "X-Webhook-ID": webhook_id,
            "X-Event-Type": event_type,
            "X-Webhook-Signature": f"sha256={signature_b64}",
            "X-Webhook-Timestamp": timestamp,
            "X-Webhook-Version": "1.0"
        }
        
        with httpx.Client(timeout=30.0) as client:
            response = client.post(endpoint, content=body, headers=headers)
            return response
```

### Appendix B: Event Catalog

| Event Type | Version | Description | Priority |
|------------|---------|-------------|----------|
| order.created | 1.0 | New order placed | High |
| order.updated | 1.0 | Order modified | Medium |
| order.cancelled | 1.0 | Order cancelled | High |
| order.refunded | 1.0 | Order refunded | Medium |
| payment.completed | 1.0 | Payment successful | High |
| payment.failed | 1.0 | Payment declined | High |
| payment.refunded | 1.0 | Refund processed | Medium |
| shipment.created | 1.0 | Shipment initiated | Medium |
| shipment.updated | 1.0 | Tracking updated | Low |
| shipment.delivered | 1.0 | Delivery confirmed | High |
| user.registered | 1.0 | New user account | Medium |
| user.updated | 1.0 | Profile updated | Low |
| inventory.low | 1.0 | Stock below threshold | High |
| product.updated | 1.0 | Product info changed | Low |

### Appendix C: Troubleshooting Guide

#### C.1 HTTP Status Codes

| Code | Meaning | Action |
|------|---------|--------|
| 200 | OK | Success |
| 202 | Accepted | Success (async processing) |
| 400 | Bad Request | Check payload format |
| 401 | Unauthorized | Verify signature |
| 403 | Forbidden | Check IP whitelist |
| 404 | Not Found | Verify endpoint URL |
| 408 | Request Timeout | Retry with backoff |
| 429 | Too Many Requests | Implement rate limiting |
| 500 | Server Error | Retry with backoff |
| 502 | Bad Gateway | Retry with backoff |
| 503 | Service Unavailable | Retry with backoff |
| 504 | Gateway Timeout | Retry with backoff |

### Appendix D: Quick Reference

#### D.1 Header Summary

```
Content-Type: application/json
User-Agent: SmartDairy-Webhook/1.0
X-Webhook-ID: wh_...
X-Event-Type: order.created
X-Webhook-Signature: sha256=...
X-Webhook-Timestamp: 1706701800
X-Webhook-Version: 1.0
```

#### D.2 Signature Algorithm

```
signed_content = timestamp + "." + request_body
signature = base64(hmac_sha256(signed_content, secret))
header_value = "sha256=" + signature
```

#### D.3 Retry Schedule

| Attempt | Delay | Total Elapsed |
|---------|-------|---------------|
| 1 | 1 min | 1 min |
| 2 | 2 min | 3 min |
| 3 | 4 min | 7 min |
| 4 | 8 min | 15 min |
| 5 | 15 min | 30 min |
| 6 | 30 min | 1 hr |
| 7+ | 1 hr | 2+ hrs |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | Integration Engineer | Initial release |

---

*End of Document E-018: Webhook Integration Guide*
