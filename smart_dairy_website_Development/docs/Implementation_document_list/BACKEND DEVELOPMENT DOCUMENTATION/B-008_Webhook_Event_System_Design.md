# SMART DAIRY LTD.
## WEBHOOK & EVENT SYSTEM DESIGN
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-008 |
| **Version** | 1.0 |
| **Date** | February 18, 2026 |
| **Author** | Solution Architect |
| **Owner** | Tech Lead |
| **Reviewer** | DevOps Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Event Architecture](#2-event-architecture)
3. [Event Types & Schema](#3-event-types--schema)
4. [Webhook Implementation](#4-webhook-implementation)
5. [Event Processing](#5-event-processing)
6. [Retry & Error Handling](#6-retry--error-handling)
7. [Security](#7-security)
8. [Monitoring & Observability](#8-monitoring--observability)
9. [Appendices](#9-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the architecture and implementation of Smart Dairy's Event-Driven Architecture (EDA) and Webhook system. It enables real-time notifications, asynchronous processing, and integration with external systems.

### 1.2 Scope

- Internal event bus for decoupled communication
- Webhook delivery system for external integrations
- Event sourcing for audit trails
- Asynchronous job processing
- Real-time notifications

### 1.3 Event Flow Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         EVENT-DRIVEN ARCHITECTURE                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  PRODUCERS                    EVENT BUS                    CONSUMERS        │
│  ┌───────────┐               ┌──────────────┐            ┌──────────────┐   │
│  │ Odoo ERP  │──────────────►│   RabbitMQ   │───────────►│  Webhook     │   │
│  │ Modules   │               │   Exchange   │            │  Delivery    │   │
│  └───────────┘               └──────┬───────┘            └──────────────┘   │
│  ┌───────────┐                      │                      ┌──────────────┐   │
│  │ Mobile    │─────────────────────►│─────────────────────►│  Email       │   │
│  │ Apps      │                      │                      │  Service     │   │
│  └───────────┘                      │                      └──────────────┘   │
│  ┌───────────┐                      │                      ┌──────────────┐   │
│  │ IoT       │─────────────────────►│─────────────────────►│  SMS         │   │
│  │ Sensors   │                      │                      │  Service     │   │
│  └───────────┘                      │                      └──────────────┘   │
│                                     │                      ┌──────────────┐   │
│                                     │─────────────────────►│  Analytics   │   │
│                                     │                      │  Pipeline    │   │
│                                     │                      └──────────────┘   │
│                              ┌──────▼───────┐                                │
│                              │  Dead Letter │                                │
│                              │  Queue (DLQ) │                                │
│                              └──────────────┘                                │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 2. EVENT ARCHITECTURE

### 2.1 Event Bus Components

| Component | Technology | Purpose |
|-----------|------------|---------|
| **Message Broker** | RabbitMQ 3.12 | Reliable message delivery |
| **Exchange Type** | Topic | Pattern-based routing |
| **Queue Type** | Classic (Durable) | Persistent messages |
| **Dead Letter Exchange** | DLX | Failed message handling |
| **TTL** | Per-message | Automatic expiration |

### 2.2 Exchange Configuration

```python
# Event Exchange Configuration
EXCHANGES = {
    'smart_dairy.events': {
        'type': 'topic',
        'durable': True,
        'auto_delete': False,
        'description': 'Main event exchange'
    },
    'smart_dairy.webhooks': {
        'type': 'direct',
        'durable': True,
        'auto_delete': False,
        'description': 'Webhook delivery exchange'
    },
    'smart_dairy.delayed': {
        'type': 'x-delayed-message',
        'durable': True,
        'auto_delete': False,
        'description': 'Delayed message exchange'
    }
}

# Queue Bindings
QUEUE_BINDINGS = {
    'webhook.delivery': {
        'exchange': 'smart_dairy.webhooks',
        'routing_key': 'webhook.*',
        'durable': True,
        'arguments': {
            'x-dead-letter-exchange': 'smart_dairy.dlx',
            'x-dead-letter-routing-key': 'webhook.failed'
        }
    },
    'notification.email': {
        'exchange': 'smart_dairy.events',
        'routing_key': 'notification.email',
        'durable': True
    },
    'notification.sms': {
        'exchange': 'smart_dairy.events',
        'routing_key': 'notification.sms',
        'durable': True
    }
}
```

### 2.3 Event Schema

```python
# Base Event Schema
from pydantic import BaseModel, Field
from datetime import datetime
from typing import Optional, Dict, Any
from enum import Enum

class EventPriority(str, Enum):
    LOW = "low"
    NORMAL = "normal"
    HIGH = "high"
    CRITICAL = "critical"

class Event(BaseModel):
    """Base event schema for all Smart Dairy events."""
    
    # Event identification
    event_id: str = Field(..., description="Unique event UUID")
    event_type: str = Field(..., description="Event type (e.g., order.created)")
    event_version: str = Field(default="1.0", description="Event schema version")
    
    # Timing
    timestamp: datetime = Field(default_factory=datetime.utcnow)
    published_at: Optional[datetime] = None
    
    # Source
    source: str = Field(..., description="Service that generated the event")
    source_id: Optional[str] = Field(None, description="Entity ID in source system")
    
    # Context
    tenant_id: Optional[int] = Field(None, description="Company/tenant ID")
    user_id: Optional[int] = Field(None, description="User who triggered the event")
    session_id: Optional[str] = None
    
    # Content
    payload: Dict[str, Any] = Field(default_factory=dict)
    metadata: Dict[str, Any] = Field(default_factory=dict)
    
    # Priority
    priority: EventPriority = EventPriority.NORMAL
    
    class Config:
        json_encoders = {
            datetime: lambda v: v.isoformat()
        }

# Example: Order Created Event
class OrderCreatedPayload(BaseModel):
    order_id: int
    order_number: str
    customer_id: int
    customer_email: str
    amount_total: float
    currency: str = "BDT"
    items: list
    status: str = "draft"

class OrderCreatedEvent(Event):
    """Event fired when a new order is created."""
    event_type: str = "order.created"
    payload: OrderCreatedPayload
```

---

## 3. EVENT TYPES & SCHEMA

### 3.1 Business Events

| Event Type | Description | Producer | Consumers |
|------------|-------------|----------|-----------|
| `order.created` | New order placed | Odoo Sales | Inventory, Accounting, Notifications |
| `order.confirmed` | Order confirmed | Odoo Sales | Warehouse, Shipping |
| `order.cancelled` | Order cancelled | Odoo Sales | Inventory, Payments |
| `order.delivered` | Order delivered | Delivery App | Customer, Analytics |
| `payment.received` | Payment completed | Payment Gateway | Sales, Accounting |
| `payment.failed` | Payment failed | Payment Gateway | Sales, Customer |
| `invoice.generated` | Invoice created | Odoo Accounting | Customer, Tax |
| `inventory.low` | Stock below threshold | Inventory Module | Procurement, Alerts |

### 3.2 Farm Management Events

| Event Type | Description | Producer | Consumers |
|------------|-------------|----------|-----------|
| `animal.registered` | New animal added | Farm Module | RFID System |
| `milk.recorded` | Milk production recorded | Farm App | Analytics, Quality |
| `health.alert` | Health issue detected | IoT/Health | Veterinarian, Manager |
| `breeding.due` | Breeding due date | Farm Module | Farm Workers |
| `calving.expected` | Calving approaching | Farm Module | Farm Manager |
| `feed.low` | Feed stock low | Inventory | Procurement |

### 3.3 IoT Events

| Event Type | Description | Producer | Consumers |
|------------|-------------|----------|-----------|
| `sensor.temperature` | Temperature reading | IoT Device | Monitoring, Alerts |
| `sensor.humidity` | Humidity reading | IoT Device | Monitoring |
| `sensor.milk_volume` | Milk meter reading | Milk Meter | Production |
| `device.offline` | Device disconnected | IoT Gateway | Support, Alerts |
| `device.battery_low` | Low battery alert | IoT Device | Maintenance |

---

## 4. WEBHOOK IMPLEMENTATION

### 4.1 Webhook Registration

```python
# models/webhook.py
from pydantic import BaseModel, Field, HttpUrl
from typing import List, Optional
from enum import Enum
from datetime import datetime

class WebhookStatus(str, Enum):
    ACTIVE = "active"
    INACTIVE = "inactive"
    SUSPENDED = "suspended"  # Due to repeated failures

class Webhook(BaseModel):
    """Webhook subscription model."""
    
    id: str
    tenant_id: int
    name: str
    url: HttpUrl
    
    # Events
    events: List[str] = Field(..., description="Event types to subscribe")
    event_filter: Optional[str] = None  # JSON filter expression
    
    # Security
    secret: str = Field(..., description="HMAC signing secret")
    signature_header: str = "X-Webhook-Signature"
    
    # Status
    status: WebhookStatus = WebhookStatus.ACTIVE
    created_at: datetime = Field(default_factory=datetime.utcnow)
    updated_at: Optional[datetime] = None
    
    # Delivery settings
    retry_count: int = 3
    retry_interval: int = 60  # seconds
    timeout: int = 30  # seconds
    
    # Rate limiting
    rate_limit: int = 100  # events per minute
    
    # Metadata
    description: Optional[str] = None
    created_by: int


# Odoo Model Implementation
class WebhookSubscription(models.Model):
    _name = 'webhook.subscription'
    _description = 'Webhook Subscription'
    _inherit = ['mail.thread']
    
    name = fields.Char(string='Name', required=True)
    url = fields.Char(string='Endpoint URL', required=True)
    
    events = fields.Many2many(
        'webhook.event.type',
        string='Subscribed Events'
    )
    
    secret = fields.Char(
        string='Secret Key',
        default=lambda self: secrets.token_hex(32),
        readonly=True
    )
    
    status = fields.Selection([
        ('active', 'Active'),
        ('inactive', 'Inactive'),
        ('suspended', 'Suspended'),
    ], default='active', tracking=True)
    
    retry_count = fields.Integer(default=3)
    timeout = fields.Integer(default=30, string='Timeout (seconds)')
    
    # Statistics
    delivery_count = fields.Integer(readonly=True)
    success_count = fields.Integer(readonly=True)
    failure_count = fields.Integer(readonly=True)
    last_delivery = fields.Datetime(readonly=True)
    
    def action_test(self):
        """Send test webhook payload."""
        self.env['webhook.delivery'].create({
            'subscription_id': self.id,
            'event_type': 'test.event',
            'payload': {'test': True, 'timestamp': fields.Datetime.now()},
        }).deliver()
```

### 4.2 Webhook Delivery Service

```python
# services/webhook_service.py
import hmac
import hashlib
import json
import asyncio
from datetime import datetime
from typing import Dict, Any

import httpx
from app.core.logging import logger

class WebhookDeliveryService:
    """Service for delivering webhooks to external endpoints."""
    
    def __init__(self):
        self.client = httpx.AsyncClient(
            timeout=30.0,
            limits=httpx.Limits(max_connections=100, max_keepalive_connections=20)
        )
    
    async def deliver(
        self,
        webhook: Webhook,
        event: Event,
        delivery_id: str
    ) -> Dict[str, Any]:
        """
        Deliver webhook to endpoint.
        
        Returns:
            Dict with status, response_code, response_body, delivered_at
        """
        # Prepare payload
        payload = self._build_payload(event, delivery_id)
        body = json.dumps(payload, default=str)
        
        # Generate signature
        signature = self._generate_signature(body, webhook.secret)
        
        # Prepare headers
        headers = {
            'Content-Type': 'application/json',
            'User-Agent': 'SmartDairy-Webhook/1.0',
            'X-Webhook-ID': delivery_id,
            'X-Webhook-Event': event.event_type,
            'X-Webhook-Timestamp': str(int(datetime.utcnow().timestamp())),
            webhook.signature_header: signature,
        }
        
        try:
            response = await self.client.post(
                str(webhook.url),
                content=body,
                headers=headers,
                follow_redirects=False
            )
            
            result = {
                'status': 'success' if response.status_code < 400 else 'failed',
                'response_code': response.status_code,
                'response_body': response.text[:1000],  # Limit stored response
                'delivered_at': datetime.utcnow(),
            }
            
        except httpx.TimeoutException:
            result = {
                'status': 'timeout',
                'error': 'Request timed out',
                'delivered_at': datetime.utcnow(),
            }
        except httpx.ConnectError as e:
            result = {
                'status': 'connection_error',
                'error': str(e),
                'delivered_at': datetime.utcnow(),
            }
        except Exception as e:
            result = {
                'status': 'error',
                'error': str(e),
                'delivered_at': datetime.utcnow(),
            }
        
        return result
    
    def _build_payload(self, event: Event, delivery_id: str) -> Dict:
        """Build webhook payload with event data."""
        return {
            'webhook_id': delivery_id,
            'event': event.event_type,
            'event_id': event.event_id,
            'timestamp': event.timestamp.isoformat(),
            'data': event.payload,
        }
    
    def _generate_signature(self, payload: str, secret: str) -> str:
        """Generate HMAC-SHA256 signature."""
        signature = hmac.new(
            secret.encode('utf-8'),
            payload.encode('utf-8'),
            hashlib.sha256
        ).hexdigest()
        return f"sha256={signature}"
    
    async def close(self):
        await self.client.aclose()
```

### 4.3 Webhook Verification (Consumer Side)

```python
# utils/webhook_verification.py
import hmac
import hashlib

class WebhookVerifier:
    """Verify incoming webhook signatures."""
    
    @staticmethod
    def verify_signature(
        payload: bytes,
        signature: str,
        secret: str,
        algorithm: str = 'sha256'
    ) -> bool:
        """
        Verify webhook signature.
        
        Args:
            payload: Raw request body
            signature: Signature from header (e.g., 'sha256=abc123')
            secret: Shared secret
            algorithm: Hash algorithm
            
        Returns:
            True if signature is valid
        """
        if '=' in signature:
            sig_algo, sig_hash = signature.split('=', 1)
            if sig_algo != algorithm:
                return False
        else:
            sig_hash = signature
        
        expected = hmac.new(
            secret.encode(),
            payload,
            getattr(hashlib, algorithm)
        ).hexdigest()
        
        return hmac.compare_digest(sig_hash, expected)
```

---

## 5. EVENT PROCESSING

### 5.1 Event Publisher

```python
# services/event_publisher.py
import json
from datetime import datetime
from typing import Dict, Any, Optional

import aio_pika

class EventPublisher:
    """Publish events to RabbitMQ."""
    
    def __init__(self, connection_url: str):
        self.connection_url = connection_url
        self.connection: Optional[aio_pika.RobustConnection] = None
        self.channel: Optional[aio_pika.Channel] = None
    
    async def connect(self):
        self.connection = await aio_pika.connect_robust(self.connection_url)
        self.channel = await self.connection.channel()
        
        # Declare exchange
        await self.channel.declare_exchange(
            'smart_dairy.events',
            aio_pika.ExchangeType.TOPIC,
            durable=True
        )
    
    async def publish(
        self,
        event: Event,
        routing_key: Optional[str] = None
    ):
        """Publish event to message bus."""
        if not self.channel:
            raise RuntimeError("Publisher not connected")
        
        routing_key = routing_key or event.event_type.replace('.', '.')
        
        message = aio_pika.Message(
            body=json.dumps(event.dict(), default=str).encode(),
            content_type='application/json',
            delivery_mode=aio_pika.DeliveryMode.PERSISTENT,
            timestamp=datetime.utcnow(),
            message_id=event.event_id,
            correlation_id=event.session_id,
            headers={
                'event_type': event.event_type,
                'tenant_id': event.tenant_id,
                'priority': event.priority.value
            }
        )
        
        exchange = await self.channel.get_exchange('smart_dairy.events')
        await exchange.publish(message, routing_key=routing_key)
        
        logger.info(f"Event published: {event.event_type}", extra={
            'event_id': event.event_id,
            'routing_key': routing_key
        })
    
    async def close(self):
        if self.connection:
            await self.connection.close()
```

### 5.2 Event Consumer

```python
# workers/event_consumer.py
import asyncio
import json
from typing import Callable, Dict

import aio_pika

class EventConsumer:
    """Consume events from RabbitMQ."""
    
    def __init__(self, connection_url: str):
        self.connection_url = connection_url
        self.handlers: Dict[str, Callable] = {}
    
    def register_handler(self, event_type: str, handler: Callable):
        """Register an event handler."""
        self.handlers[event_type] = handler
    
    async def start(self):
        """Start consuming events."""
        connection = await aio_pika.connect_robust(self.connection_url)
        channel = await connection.channel()
        
        # Set QoS
        await channel.set_qos(prefetch_count=10)
        
        # Declare queue
        queue = await channel.declare_queue(
            'webhook.delivery',
            durable=True,
            arguments={
                'x-dead-letter-exchange': 'smart_dairy.dlx',
                'x-dead-letter-routing-key': 'webhook.failed'
            }
        )
        
        # Bind to exchange
        exchange = await channel.get_exchange('smart_dairy.events')
        await queue.bind(exchange, routing_key='#')
        
        # Start consuming
        await queue.consume(self._process_message)
        
        logger.info("Event consumer started")
    
    async def _process_message(self, message: aio_pika.IncomingMessage):
        """Process incoming message."""
        async with message.process():
            try:
                body = json.loads(message.body)
                event_type = body.get('event_type')
                
                handler = self.handlers.get(event_type)
                if handler:
                    await handler(body)
                else:
                    logger.warning(f"No handler for event type: {event_type}")
                    
            except Exception as e:
                logger.exception("Error processing message")
                # Message will be requeued or sent to DLX
                raise
```

---

## 6. RETRY & ERROR HANDLING

### 6.1 Retry Strategy

| Attempt | Delay | Action on Failure |
|---------|-------|-------------------|
| 1st | Immediate | Retry with backoff |
| 2nd | 30 seconds | Retry |
| 3rd | 2 minutes | Retry |
| 4th | 10 minutes | Move to DLQ |

### 6.2 Retry Implementation

```python
# services/retry_service.py
import asyncio
from datetime import datetime, timedelta
from typing import Callable

class RetryService:
    """Handle retry logic for failed webhooks."""
    
    BACKOFF_DELAYS = [30, 120, 600]  # seconds
    MAX_RETRIES = 3
    
    async def schedule_retry(
        self,
        delivery_id: str,
        attempt: int,
        deliver_func: Callable
    ):
        """Schedule a retry with exponential backoff."""
        if attempt >= self.MAX_RETRIES:
            await self._move_to_dlq(delivery_id)
            return
        
        delay = self.BACKOFF_DELAYS[attempt - 1]
        
        logger.info(
            f"Scheduling retry {attempt} for delivery {delivery_id} "
            f"in {delay} seconds"
        )
        
        # Schedule via delayed message exchange
        await self._schedule_delayed(delivery_id, delay, attempt)
    
    async def _schedule_delayed(
        self,
        delivery_id: str,
        delay: int,
        attempt: int
    ):
        """Schedule delayed retry using RabbitMQ delayed exchange."""
        message = aio_pika.Message(
            body=json.dumps({
                'delivery_id': delivery_id,
                'attempt': attempt
            }).encode(),
            headers={'x-delay': delay * 1000}  # milliseconds
        )
        
        await self.channel.publish(
            message,
            exchange='smart_dairy.delayed',
            routing_key='webhook.retry'
        )
```

### 6.3 Dead Letter Queue Handling

```python
# workers/dlq_handler.py

class DLQHandler:
    """Process messages from Dead Letter Queue."""
    
    async def process_dlq_message(self, message: dict):
        """Handle failed webhook delivery."""
        delivery_id = message['delivery_id']
        failure_reason = message.get('error')
        
        # Update webhook status if repeated failures
        webhook_id = message['webhook_id']
        failure_count = await self.get_failure_count(webhook_id)
        
        if failure_count > 10:
            await self.suspend_webhook(webhook_id)
            await self.notify_admin(webhook_id, failure_reason)
        
        # Store for manual review
        await self.store_failed_delivery(delivery_id, message)
    
    async def suspend_webhook(self, webhook_id: str):
        """Suspend webhook due to repeated failures."""
        # Update webhook status
        await self.db.execute(
            "UPDATE webhook_subscription SET status = 'suspended' WHERE id = %s",
            (webhook_id,)
        )
    
    async def notify_admin(self, webhook_id: str, reason: str):
        """Notify admin about suspended webhook."""
        await self.event_publisher.publish(Event(
            event_type='webhook.suspended',
            payload={
                'webhook_id': webhook_id,
                'reason': reason,
                'action_required': 'Review and reactivate webhook'
            }
        ))
```

---

## 7. SECURITY

### 7.1 Security Measures

| Measure | Implementation |
|---------|----------------|
| **TLS** | HTTPS only for webhook endpoints |
| **Authentication** | HMAC signature verification |
| **Replay Prevention** | Timestamp validation (±5 minutes) |
| **IP Whitelisting** | Optional IP restriction per webhook |
| **Payload Size** | Max 1MB payload limit |
| **Secret Rotation** | Automatic 90-day rotation |

### 7.2 Security Implementation

```python
# security/webhook_security.py
from datetime import datetime, timedelta

class WebhookSecurity:
    """Security utilities for webhooks."""
    
    MAX_PAYLOAD_SIZE = 1024 * 1024  # 1MB
    TIMESTAMP_TOLERANCE = 300  # 5 minutes
    
    @staticmethod
    def validate_timestamp(timestamp: int) -> bool:
        """Validate webhook timestamp to prevent replay attacks."""
        now = int(datetime.utcnow().timestamp())
        diff = abs(now - timestamp)
        return diff <= WebhookSecurity.TIMESTAMP_TOLERANCE
    
    @staticmethod
    def validate_payload_size(payload: bytes) -> bool:
        """Check payload size limit."""
        return len(payload) <= WebhookSecurity.MAX_PAYLOAD_SIZE
    
    @staticmethod
    def generate_secret() -> str:
        """Generate secure webhook secret."""
        return secrets.token_urlsafe(32)
```

---

## 8. MONITORING & OBSERVABILITY

### 8.1 Metrics

| Metric | Description | Alert Threshold |
|--------|-------------|-----------------|
| `webhook_delivery_total` | Total webhook deliveries | - |
| `webhook_delivery_success` | Successful deliveries | < 95% |
| `webhook_delivery_latency` | Delivery latency (p99) | > 5s |
| `webhook_retry_total` | Retry attempts | > 10% |
| `dlq_messages` | Messages in DLQ | > 0 |

### 8.2 Monitoring Dashboard

```python
# monitoring/metrics.py
from prometheus_client import Counter, Histogram, Gauge

# Counters
webhook_deliveries = Counter(
    'webhook_delivery_total',
    'Total webhook deliveries',
    ['webhook_id', 'event_type', 'status']
)

# Histograms
delivery_latency = Histogram(
    'webhook_delivery_latency_seconds',
    'Webhook delivery latency',
    ['webhook_id'],
    buckets=[.01, .025, .05, .1, .25, .5, 1, 2.5, 5, 10]
)

# Gauges
active_webhooks = Gauge(
    'webhook_active_subscriptions',
    'Number of active webhook subscriptions'
)
```

---

## 9. APPENDICES

### Appendix A: Event Type Registry

| Category | Event Types | Pattern |
|----------|-------------|---------|
| Order | created, confirmed, cancelled, shipped, delivered | `order.{action}` |
| Payment | initiated, completed, failed, refunded | `payment.{action}` |
| Animal | registered, updated, sold, deceased | `animal.{action}` |
| Milk | recorded, quality_checked | `milk.{action}` |
| Health | alert, treatment_started, recovered | `health.{action}` |

### Appendix B: HTTP Response Codes

| Code | Webhook Action |
|------|----------------|
| 2xx | Success - No retry |
| 3xx | Follow redirect (max 3) |
| 4xx | Client error - No retry |
| 5xx | Server error - Retry |
| Timeout | Retry |

---

**END OF WEBHOOK & EVENT SYSTEM DESIGN**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 18, 2026 | Solution Architect | Initial version |
