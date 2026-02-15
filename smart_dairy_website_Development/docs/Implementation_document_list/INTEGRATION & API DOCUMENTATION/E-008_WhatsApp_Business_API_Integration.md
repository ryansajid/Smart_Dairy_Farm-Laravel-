# WhatsApp Business API Integration

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | E-008 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Classification** | Internal - Implementation Documentation |
| **Status** | Draft |

---

## Revision History

| Version | Date | Author | Changes | Approver |
|---------|------|--------|---------|----------|
| 1.0 | 2026-01-31 | Integration Engineer | Initial document creation | Pending |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [WhatsApp Business API Options](#2-whatsapp-business-api-options)
3. [Prerequisites](#3-prerequisites)
4. [Setup Process](#4-setup-process)
5. [Message Types](#5-message-types)
6. [Template Registration](#6-template-registration)
7. [Implementation](#7-implementation)
8. [Interactive Messages](#8-interactive-messages)
9. [Media Messages](#9-media-messages)
10. [Webhook Handling](#10-webhook-handling)
11. [Conversation-Based Pricing](#11-conversation-based-pricing)
12. [Analytics and Monitoring](#12-analytics-and-monitoring)
13. [Appendices](#13-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive technical guidance for integrating the WhatsApp Business API with Smart Dairy Ltd's systems to enable seamless customer communication, order management, and marketing capabilities.

### 1.2 Scope

The integration covers:
- **Customer Service**: Real-time support conversations and query resolution
- **Order Updates**: Automated order confirmations, tracking notifications, and delivery alerts
- **Marketing Campaigns**: Promotional broadcasts and product announcements (opt-in required)
- **Farm Visit Scheduling**: Appointment reminders and confirmations
- **Operational Notifications**: Payment confirmations, subscription updates

### 1.3 Business Value

| Metric | Expected Improvement |
|--------|---------------------|
| Customer Response Time | 70% reduction (from hours to minutes) |
| Order Confirmation Rate | 95%+ delivery rate vs 20% for email |
| Customer Satisfaction | 85%+ CSAT score through instant communication |
| Support Cost | 40% reduction through automation |
| Marketing Open Rate | 90%+ vs 20-25% for email campaigns |

### 1.4 Use Cases

#### Order Confirmation and Tracking
- Instant order confirmation upon purchase
- Real-time delivery status updates
- Tracking link sharing with estimated delivery times
- Delivery completion notifications with feedback requests

#### Customer Support Conversations
- 24/7 automated responses for common queries
- Seamless handoff to human agents when needed
- Multi-language support for regional customers
- Conversation history retention for context

#### Delivery Notifications
- "Out for delivery" alerts
- Driver contact information sharing
- Delivery attempt notifications
- Failed delivery rescheduling

#### Appointment Reminders
- Farm visit confirmations
- Pre-visit instructions and requirements
- Rescheduling options
- Visit completion follow-ups

#### Promotional Broadcasts
- Seasonal product announcements
- Special offers and discounts
- New product launches
- Loyalty program updates

---

## 2. WhatsApp Business API Options

### 2.1 Meta Cloud API (Direct)

Direct integration with Meta's Cloud API hosted on Meta's infrastructure.

**Advantages:**
- No hosting costs for WhatsApp infrastructure
- Automatic updates and maintenance by Meta
- Direct access to latest features
- Lower message costs
- Simpler setup process

**Considerations:**
- Requires technical expertise for integration
- Self-managed webhook infrastructure
- Direct compliance responsibility

**Recommended for:** Smart Dairy Ltd (cost-effective, scalable)

### 2.2 Business Solution Providers (BSPs)

Third-party providers like Twilio, MessageBird, 360dialog that offer WhatsApp API access.

**Advantages:**
- Additional features and dashboards
- Managed webhook handling
- Support services
- Multi-channel messaging options

**Considerations:**
- Higher per-message costs
- Dependency on third-party platform
- Potential feature lag behind Meta API

**Comparison:**

| Aspect | Meta Cloud API | BSP (Twilio) |
|--------|---------------|--------------|
| Setup Complexity | Medium | Low |
| Cost per Message | ~$0.005-0.08 | ~$0.008-0.12 |
| Hosting | Meta managed | Provider managed |
| Webhook Management | Self-hosted | Provider managed |
| Support | Community/Docs | Dedicated support |
| Feature Access | Immediate | May have delays |

### 2.3 Recommendation

**Primary:** Meta Cloud API for cost optimization and feature access.

**Rationale:**
- Smart Dairy's existing cloud infrastructure can host webhooks
- Development team capable of direct API integration
- Significant cost savings at scale (100,000+ messages/month)
- Full control over data and compliance

---

## 3. Prerequisites

### 3.1 Facebook Business Manager

**Requirements:**
- Active Facebook Business Manager account
- Verified business (business verification required)
- Admin access to manage WhatsApp Business API

**Setup Steps:**

1. **Create Business Manager Account**
   ```
   https://business.facebook.com/create
   ```
   - Use company email domain
   - Complete business profile

2. **Verify Business**
   - Submit business documentation (Business Registration Certificate)
   - Provide business address proof
   - Wait for Meta approval (1-5 business days)

3. **Add Smart Dairy Business Details**
   - Business Name: Smart Dairy Ltd
   - Business Category: Agriculture/Food & Beverage
   - Website: www.smartdairy.co.tz
   - Business Email: admin@smartdairy.co.tz

### 3.2 Phone Number Requirements

**Number Specifications:**
- Dedicated phone number (not personal WhatsApp)
- Can be landline or mobile
- Must not have active WhatsApp Business App
- Country code: +255 (Tanzania)

**Verification Process:**
1. Add number in WhatsApp Business Manager
2. Choose verification method: SMS or Voice Call
3. Enter verification code
4. Number is activated for API use

### 3.3 Display Name Approval

**Naming Guidelines:**
- Must match business name or brand
- Cannot contain generic terms only
- No excessive punctuation or emojis
- Must comply with WhatsApp Commerce Policy

**Smart Dairy Approved Names:**
- ✅ "Smart Dairy Ltd"
- ✅ "Smart Dairy Customer Care"
- ✅ "Smart Dairy Orders"
- ❌ "Dairy Products" (too generic)
- ❌ "Smart Dairy!!!" (excessive punctuation)

### 3.4 Technical Prerequisites

**Infrastructure:**
```
- Python 3.9+
- HTTPS-enabled webhook endpoint
- SSL certificate (valid, not self-signed)
- Public IP/domain for webhook callbacks
```

**Access Tokens:**
- System User access token with `whatsapp_business_messaging` permission
- Permanent token for production use
- Rotatable tokens for security

**System Requirements:**
```
- Webhook endpoint capacity: 100 requests/second minimum
- Message processing latency: < 500ms response time
- Storage: Conversation logs and media retention
- Backup: Redundant webhook endpoints recommended
```

---

## 4. Setup Process

### 4.1 WhatsApp Business Account (WABA) Creation

**Step-by-Step Setup:**

```python
# WABA Setup Checklist
WABA_SETUP_STEPS = [
    "1. Access Facebook Business Manager",
    "2. Navigate to WhatsApp Manager",
    "3. Create new WhatsApp Business Account",
    "4. Select 'Smart Dairy Ltd' as business",
    "5. Accept WhatsApp Business Terms of Service",
    "6. Add payment method for messaging charges",
    "7. Configure billing settings",
    "8. Assign system users and roles"
]
```

**Business Account Structure:**

```
Smart Dairy Ltd (Business Manager)
└── WhatsApp Business Account (WABA)
    ├── Phone Numbers (up to 25)
    │   ├── +255 XXX XXX XXX (Primary)
    │   └── +255 XXX XXX XXX (Backup)
    ├── Message Templates
    ├── WhatsApp Business Profile
    └── Users & Permissions
```

### 4.2 Phone Number Registration

```python
# Phone number registration via API
import requests

def register_phone_number(access_token, waba_id, phone_number):
    """
    Register a phone number with WhatsApp Business API
    """
    url = f"https://graph.facebook.com/v18.0/{waba_id}/phone_numbers"
    
    headers = {
        "Authorization": f"Bearer {access_token}",
        "Content-Type": "application/json"
    }
    
    data = {
        "cc": "255",  # Tanzania country code
        "phone_number": phone_number,
        "messaging_product": "whatsapp",
        "migrate_phone_number": False
    }
    
    response = requests.post(url, headers=headers, json=data)
    return response.json()
```

### 4.3 Business Profile Configuration

```python
# Configure business profile
BUSINESS_PROFILE = {
    "about": "Fresh dairy products delivered to your doorstep. Quality milk from Tanzanian farms.",
    "address": "Smart Dairy Farm, Arusha, Tanzania",
    "description": "Smart Dairy Ltd - Premium dairy products including fresh milk, yogurt, cheese, and traditional maziwa lala. Farm-to-table freshness.",
    "email": "support@smartdairy.co.tz",
    "websites": ["https://www.smartdairy.co.tz"],
    "vertical": "FOOD",  # Industry vertical
    "profile_picture_url": "https://www.smartdairy.co.tz/assets/logo-square.png"
}

def update_business_profile(access_token, phone_number_id, profile_data):
    """
    Update WhatsApp Business Profile
    """
    url = f"https://graph.facebook.com/v18.0/{phone_number_id}/whatsapp_business_profile"
    
    headers = {
        "Authorization": f"Bearer {access_token}",
        "Content-Type": "application/json"
    }
    
    response = requests.post(url, headers=headers, json=profile_data)
    return response.json()
```

### 4.4 Webhook Configuration

```python
# Webhook subscription configuration
WEBHOOK_CONFIG = {
    "object": "whatsapp_business_account",
    "callback_url": "https://api.smartdairy.co.tz/webhooks/whatsapp",
    "verify_token": "smart_dairy_webhook_verify_2026",
    "fields": [
        "messages",
        "message_template_status_update",
        "phone_number_quality_update",
        "account_review_update",
        "account_update",
        "template_category_update"
    ]
}

def subscribe_webhook(access_token, waba_id, config):
    """
    Subscribe to WhatsApp Business webhooks
    """
    url = f"https://graph.facebook.com/v18.0/{waba_id}/subscribed_apps"
    
    headers = {
        "Authorization": f"Bearer {access_token}",
        "Content-Type": "application/json"
    }
    
    response = requests.post(url, headers=headers, json=config)
    return response.json()
```

---

## 5. Message Types

### 5.1 Session Messages (Customer Care Window)

**Definition:** Free-form messages sent within a 24-hour window after customer-initiated conversation.

**Window Rules:**
- Opens: When customer sends any message
- Duration: 24 hours from last customer message
- Cost: No additional charges (only conversation fee)
- Content: No restrictions (within WhatsApp policies)

```python
# Session message window tracking
from datetime import datetime, timedelta

class ConversationWindow:
    def __init__(self, customer_phone):
        self.customer_phone = customer_phone
        self.window_open = False
        self.window_expires = None
        self.last_customer_message = None
    
    def customer_message_received(self):
        """Call when customer sends a message"""
        self.window_open = True
        self.last_customer_message = datetime.now()
        self.window_expires = datetime.now() + timedelta(hours=24)
    
    def can_send_session_message(self):
        """Check if we can send free-form messages"""
        if not self.window_open:
            return False
        return datetime.now() < self.window_expires
    
    def time_remaining(self):
        """Get remaining time in window"""
        if not self.window_open:
            return timedelta(0)
        remaining = self.window_expires - datetime.now()
        return max(remaining, timedelta(0))
```

### 5.2 Template Messages (Pre-approved)

**Definition:** Pre-approved message formats for initiating conversations or sending notifications outside the 24-hour window.

**Use Cases:**
- Order confirmations
- Delivery notifications
- Appointment reminders
- Marketing broadcasts (with opt-in)

**Requirements:**
- Must be approved by Meta before use
- Cannot be modified after sending
- Variable parameters must match template definition

```python
# Template message structure
TEMPLATE_MESSAGE = {
    "messaging_product": "whatsapp",
    "recipient_type": "individual",
    "to": "255712345678",
    "type": "template",
    "template": {
        "name": "order_confirmation_v2",
        "language": {
            "code": "en"
        },
        "components": [
            {
                "type": "header",
                "parameters": [
                    {
                        "type": "text",
                        "text": "ORD-2026-001234"
                    }
                ]
            },
            {
                "type": "body",
                "parameters": [
                    {"type": "text", "text": "John Doe"},
                    {"type": "text", "text": "Fresh Milk 1L x 3"},
                    {"type": "text", "text": "TZS 15,000"},
                    {"type": "text", "text": "January 31, 2026"}
                ]
            }
        ]
    }
}
```

### 5.3 Conversation Categories

| Category | Description | Example Use Case |
|----------|-------------|------------------|
| **Marketing** | Promotional content | New product launch, special offers |
| **Utility** | Transactional updates | Order confirmations, delivery updates |
| **Authentication** | OTP and security codes | Login verification, password reset |
| **Service** | Customer support | Issue resolution, general inquiries |

---

## 6. Template Registration

### 6.1 Template Creation Guidelines

**Structure Requirements:**

```
Header (Optional)
├── Text (max 60 characters)
├── Image/Video/Document
└── Location

Body (Required)
├── Text (max 1024 characters)
└── Variable placeholders: {{1}}, {{2}}, etc.

Footer (Optional)
└── Text (max 60 characters)

Buttons (Optional, max 3)
├── Quick Reply Buttons
├── Call-to-Action (URL or Phone)
└── Copy Code Button
```

### 6.2 Smart Dairy Template Library

#### Order Confirmation Template

```json
{
  "name": "order_confirmation_v2",
  "category": "UTILITY",
  "language": "en",
  "components": [
    {
      "type": "HEADER",
      "format": "TEXT",
      "text": "Order {{1}} Confirmed"
    },
    {
      "type": "BODY",
      "text": "Hi {{2}},\n\nThank you for your order!\n\n📦 Order Details:\n{{3}}\n\n💰 Total: {{4}}\n📅 Delivery Date: {{5}}\n\nTrack your order status anytime."
    },
    {
      "type": "FOOTER",
      "text": "Smart Dairy Ltd - Fresh from the farm"
    },
    {
      "type": "BUTTONS",
      "buttons": [
        {
          "type": "URL",
          "text": "Track Order",
          "url": "https://smartdairy.co.tz/orders/{{1}}"
        }
      ]
    }
  ]
}
```

#### Delivery Notification Template

```json
{
  "name": "delivery_out_for_delivery",
  "category": "UTILITY",
  "language": "en",
  "components": [
    {
      "type": "HEADER",
      "format": "TEXT",
      "text": "🚚 Out for Delivery"
    },
    {
      "type": "BODY",
      "text": "Hello {{1}},\n\nYour order {{2}} is out for delivery!\n\n📍 Delivery Address:\n{{3}}\n\n⏰ Expected Delivery: {{4}}\n👤 Driver: {{5}}\n📱 Contact: {{6}}"
    },
    {
      "type": "BUTTONS",
      "buttons": [
        {
          "type": "URL",
          "text": "Live Tracking",
          "url": "https://smartdairy.co.tz/track/{{2}}"
        },
        {
          "type": "PHONE_NUMBER",
          "text": "Call Driver",
          "phone_number": "{{7}}"
        }
      ]
    }
  ]
}
```

#### Appointment Reminder Template

```json
{
  "name": "farm_visit_reminder",
  "category": "UTILITY",
  "language": "en",
  "components": [
    {
      "type": "HEADER",
      "format": "TEXT",
      "text": "📅 Farm Visit Reminder"
    },
    {
      "type": "BODY",
      "text": "Hello {{1}},\n\nThis is a reminder about your upcoming farm visit:\n\n📍 Location: Smart Dairy Farm, {{2}}\n📅 Date: {{3}}\n⏰ Time: {{4}}\n\nPlease arrive 10 minutes early. Don't forget to bring:\n✓ Valid ID\n✓ Comfortable shoes\n✓ Hat/sun protection"
    },
    {
      "type": "BUTTONS",
      "buttons": [
        {
          "type": "QUICK_REPLY",
          "text": "Confirm Attendance"
        },
        {
          "type": "QUICK_REPLY",
          "text": "Reschedule"
        }
      ]
    }
  ]
}
```

#### Marketing Broadcast Template

```json
{
  "name": "promotional_offer",
  "category": "MARKETING",
  "language": "en",
  "components": [
    {
      "type": "HEADER",
      "format": "IMAGE",
      "example": {
        "header_handle": ["https://smartdairy.co.tz/images/promo-jan-2026.jpg"]
      }
    },
    {
      "type": "BODY",
      "text": "🎉 Special Offer for {{1}}!\n\nGet {{2}} off on all fresh dairy products this week!\n\n🥛 Fresh Milk - TZS {{3}}\n🥣 Natural Yogurt - TZS {{4}}\n🧀 Farm Cheese - TZS {{5}}\n\nUse code: {{6}}\nValid until {{7}}"
    },
    {
      "type": "FOOTER",
      "text": "Reply STOP to opt out"
    },
    {
      "type": "BUTTONS",
      "buttons": [
        {
          "type": "URL",
          "text": "Shop Now",
          "url": "https://smartdairy.co.tz/shop?utm_source=whatsapp"
        },
        {
          "type": "QUICK_REPLY",
          "text": "View Catalog"
        }
      ]
    }
  ]
}
```

### 6.3 Template Registration API

```python
import requests

def create_message_template(access_token, waba_id, template_data):
    """
    Create a new message template
    
    Args:
        access_token: Meta API access token
        waba_id: WhatsApp Business Account ID
        template_data: Template configuration dict
    """
    url = f"https://graph.facebook.com/v18.0/{waba_id}/message_templates"
    
    headers = {
        "Authorization": f"Bearer {access_token}",
        "Content-Type": "application/json"
    }
    
    response = requests.post(url, headers=headers, json=template_data)
    return response.json()

# Example usage
new_template = {
    "name": "payment_received",
    "category": "UTILITY",
    "language": "en",
    "components": [
        {
            "type": "BODY",
            "text": "Hi {{1}}, we have received your payment of {{2}} for order {{3}}. Thank you for choosing Smart Dairy!"
        }
    ]
}

# Submit for approval
# result = create_message_template(ACCESS_TOKEN, WABA_ID, new_template)
```

### 6.4 Template Management

```python
class TemplateManager:
    def __init__(self, access_token, waba_id):
        self.access_token = access_token
        self.waba_id = waba_id
        self.base_url = "https://graph.facebook.com/v18.0"
    
    def list_templates(self, limit=100):
        """List all message templates"""
        url = f"{self.base_url}/{self.waba_id}/message_templates"
        params = {
            "limit": limit,
            "access_token": self.access_token
        }
        
        response = requests.get(url, params=params)
        return response.json()
    
    def get_template(self, template_name):
        """Get specific template details"""
        templates = self.list_templates()
        for template in templates.get("data", []):
            if template["name"] == template_name:
                return template
        return None
    
    def delete_template(self, template_name):
        """Delete a message template"""
        url = f"{self.base_url}/{self.waba_id}/message_templates"
        params = {
            "name": template_name,
            "access_token": self.access_token
        }
        
        response = requests.delete(url, params=params)
        return response.json()
    
    def check_template_status(self, template_name):
        """Check approval status of template"""
        template = self.get_template(template_name)
        if template:
            return {
                "name": template["name"],
                "status": template["status"],  # APPROVED, PENDING, REJECTED
                "category": template["category"],
                "language": template["language"],
                "quality_score": template.get("quality_score", "UNKNOWN")
            }
        return None
```

---

## 7. Implementation

### 7.1 Python SDK Installation

```bash
# Install official Facebook Business SDK
pip install facebook-business

# Or use requests for direct API calls
pip install requests

# Additional dependencies
pip install python-dotenv
pip install pydantic
pip install fastapi  # For webhook server
pip install uvicorn
```

### 7.2 Configuration

```python
# config.py - WhatsApp Business API Configuration
import os
from dataclasses import dataclass
from dotenv import load_dotenv

load_dotenv()

@dataclass
class WhatsAppConfig:
    """WhatsApp Business API Configuration"""
    # API Credentials
    ACCESS_TOKEN: str = os.getenv("WHATSAPP_ACCESS_TOKEN")
    PHONE_NUMBER_ID: str = os.getenv("WHATSAPP_PHONE_NUMBER_ID")
    WABA_ID: str = os.getenv("WHATSAPP_WABA_ID")
    
    # API Endpoints
    API_VERSION: str = "v18.0"
    BASE_URL: str = "https://graph.facebook.com"
    
    # Webhook Configuration
    WEBHOOK_VERIFY_TOKEN: str = os.getenv("WEBHOOK_VERIFY_TOKEN")
    WEBHOOK_ENDPOINT: str = "/webhooks/whatsapp"
    
    # Business Profile
    BUSINESS_NAME: str = "Smart Dairy Ltd"
    DEFAULT_LANGUAGE: str = "en"
    
    @property
    def api_url(self):
        return f"{self.BASE_URL}/{self.API_VERSION}"
    
    def get_headers(self):
        return {
            "Authorization": f"Bearer {self.ACCESS_TOKEN}",
            "Content-Type": "application/json"
        }

# Initialize configuration
config = WhatsAppConfig()
```

### 7.3 Core Client Class

```python
# whatsapp_client.py
import requests
import json
from typing import Optional, Dict, List, Any
from config import WhatsAppConfig

class WhatsAppClient:
    """
    WhatsApp Business API Client for Smart Dairy Ltd
    """
    
    def __init__(self, config: WhatsAppConfig = None):
        self.config = config or WhatsAppConfig()
        self.session = requests.Session()
        self.session.headers.update(self.config.get_headers())
    
    def _make_request(self, method: str, endpoint: str, **kwargs) -> Dict:
        """Make authenticated API request"""
        url = f"{self.config.api_url}/{endpoint}"
        
        try:
            response = self.session.request(method, url, **kwargs)
            response.raise_for_status()
            return response.json()
        except requests.exceptions.HTTPError as e:
            error_data = response.json() if response.content else {}
            raise WhatsAppAPIError(f"API Error: {error_data}") from e
        except Exception as e:
            raise WhatsAppAPIError(f"Request failed: {str(e)}") from e
    
    # ==================== MESSAGE SENDING ====================
    
    def send_text_message(
        self, 
        to: str, 
        message: str,
        preview_url: bool = False
    ) -> Dict:
        """
        Send simple text message (session message)
        
        Args:
            to: Recipient phone number (with country code)
            message: Message text content
            preview_url: Enable URL preview
        """
        data = {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": self._format_phone(to),
            "type": "text",
            "text": {
                "preview_url": preview_url,
                "body": message
            }
        }
        
        return self._make_request(
            "POST",
            f"{self.config.PHONE_NUMBER_ID}/messages",
            json=data
        )
    
    def send_template_message(
        self,
        to: str,
        template_name: str,
        language_code: str = "en",
        components: List[Dict] = None
    ) -> Dict:
        """
        Send template message
        
        Args:
            to: Recipient phone number
            template_name: Name of approved template
            language_code: Language code (default: en)
            components: Template component parameters
        """
        data = {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": self._format_phone(to),
            "type": "template",
            "template": {
                "name": template_name,
                "language": {
                    "code": language_code
                }
            }
        }
        
        if components:
            data["template"]["components"] = components
        
        return self._make_request(
            "POST",
            f"{self.config.PHONE_NUMBER_ID}/messages",
            json=data
        )
    
    def send_order_confirmation(
        self,
        to: str,
        order_id: str,
        customer_name: str,
        order_details: str,
        total: str,
        delivery_date: str
    ) -> Dict:
        """Send order confirmation using template"""
        components = [
            {
                "type": "header",
                "parameters": [{"type": "text", "text": order_id}]
            },
            {
                "type": "body",
                "parameters": [
                    {"type": "text", "text": customer_name},
                    {"type": "text", "text": order_details},
                    {"type": "text", "text": total},
                    {"type": "text", "text": delivery_date}
                ]
            }
        ]
        
        return self.send_template_message(
            to=to,
            template_name="order_confirmation_v2",
            components=components
        )
    
    # ==================== UTILITY METHODS ====================
    
    @staticmethod
    def _format_phone(phone: str) -> str:
        """Format phone number (remove + and spaces)"""
        return phone.replace("+", "").replace(" ", "").replace("-", "")
    
    def get_message_status(self, message_id: str) -> Dict:
        """Get status of sent message"""
        return self._make_request("GET", message_id)


class WhatsAppAPIError(Exception):
    """Custom exception for WhatsApp API errors"""
    pass
```

### 7.4 FastAPI Webhook Server

```python
# webhook_server.py
from fastapi import FastAPI, Request, HTTPException, status
from fastapi.responses import PlainTextResponse
import hmac
import hashlib
import json
import logging
from typing import Dict, Any

from config import WhatsAppConfig
from message_handler import MessageHandler

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = FastAPI(title="Smart Dairy WhatsApp Webhook")
config = WhatsAppConfig()
message_handler = MessageHandler()

@app.get("/webhooks/whatsapp")
async def verify_webhook(request: Request):
    """
    Handle webhook verification from Meta
    Called when setting up webhook subscription
    """
    params = dict(request.query_params)
    
    mode = params.get("hub.mode")
    token = params.get("hub.verify_token")
    challenge = params.get("hub.challenge")
    
    if mode == "subscribe" and token == config.WEBHOOK_VERIFY_TOKEN:
        logger.info("Webhook verified successfully")
        return PlainTextResponse(content=challenge, status_code=200)
    
    logger.warning(f"Webhook verification failed: mode={mode}, token={token}")
    raise HTTPException(status_code=403, detail="Verification failed")

@app.post("/webhooks/whatsapp")
async def receive_webhook(request: Request):
    """
    Receive and process webhook events from WhatsApp
    """
    try:
        body = await request.body()
        payload = json.loads(body)
        
        # Verify signature if X-Hub-Signature-256 header present
        signature = request.headers.get("X-Hub-Signature-256")
        if signature and not verify_signature(body, signature):
            raise HTTPException(status_code=401, detail="Invalid signature")
        
        # Process webhook entries
        for entry in payload.get("entry", []):
            for change in entry.get("changes", []):
                value = change.get("value", {})
                
                # Handle different event types
                if "messages" in value:
                    await handle_incoming_messages(value["messages"], value.get("contacts", []))
                
                if "statuses" in value:
                    await handle_status_updates(value["statuses"])
                
                if "message_template_status_update" in value:
                    await handle_template_status_update(value["message_template_status_update"])
        
        return {"status": "processed"}
    
    except json.JSONDecodeError:
        logger.error("Invalid JSON payload")
        raise HTTPException(status_code=400, detail="Invalid JSON")
    except Exception as e:
        logger.error(f"Webhook processing error: {str(e)}")
        raise HTTPException(status_code=500, detail="Processing error")

def verify_signature(body: bytes, signature: str) -> bool:
    """Verify webhook signature from Meta"""
    expected = hmac.new(
        config.WEBHOOK_VERIFY_TOKEN.encode(),
        body,
        hashlib.sha256
    ).hexdigest()
    
    return hmac.compare_digest(f"sha256={expected}", signature)

async def handle_incoming_messages(messages: list, contacts: list):
    """Process incoming messages from customers"""
    for message in messages:
        try:
            # Extract sender info
            from_number = message.get("from")
            message_id = message.get("id")
            timestamp = message.get("timestamp")
            
            # Get message type and content
            msg_type = message.get("type")
            
            message_data = {
                "message_id": message_id,
                "from": from_number,
                "timestamp": timestamp,
                "type": msg_type,
                "contacts": contacts
            }
            
            # Handle different message types
            if msg_type == "text":
                message_data["content"] = message["text"]["body"]
                await message_handler.handle_text_message(message_data)
            
            elif msg_type == "image":
                message_data["media_id"] = message["image"]["id"]
                message_data["caption"] = message["image"].get("caption")
                await message_handler.handle_image_message(message_data)
            
            elif msg_type == "interactive":
                interactive = message["interactive"]
                message_data["interactive_type"] = interactive.get("type")
                message_data["response"] = interactive
                await message_handler.handle_interactive_message(message_data)
            
            elif msg_type == "button":
                message_data["button_payload"] = message["button"]["payload"]
                message_data["button_text"] = message["button"]["text"]
                await message_handler.handle_button_reply(message_data)
            
            elif msg_type == "order":
                # Handle product order from catalog
                message_data["order"] = message["order"]
                await message_handler.handle_order_message(message_data)
            
            logger.info(f"Processed {msg_type} message from {from_number}")
            
        except Exception as e:
            logger.error(f"Error handling message: {str(e)}")

async def handle_status_updates(statuses: list):
    """Process message status updates (sent, delivered, read)"""
    for status in statuses:
        try:
            status_data = {
                "message_id": status.get("id"),
                "status": status.get("status"),  # sent, delivered, read, failed
                "timestamp": status.get("timestamp"),
                "recipient_id": status.get("recipient_id"),
                "conversation": status.get("conversation"),
                "pricing": status.get("pricing")
            }
            
            # Update message status in database
            await message_handler.update_message_status(status_data)
            
            logger.info(f"Message {status_data['message_id']} status: {status_data['status']}")
            
        except Exception as e:
            logger.error(f"Error handling status update: {str(e)}")

async def handle_template_status_update(update: dict):
    """Handle template approval/rejection events"""
    logger.info(f"Template {update.get('template_name')} status: {update.get('event_type')}")
    # Update template status in database
    await message_handler.update_template_status(update)

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
```

---

## 8. Interactive Messages

### 8.1 Quick Reply Buttons

```python
def send_quick_reply_buttons(
    self,
    to: str,
    header_text: str,
    body_text: str,
    footer_text: str,
    buttons: List[Dict[str, str]]
) -> Dict:
    """
    Send message with quick reply buttons
    
    Args:
        buttons: List of {"id": "unique_id", "title": "Button Text"}
                 Max 3 buttons, title max 20 chars
    """
    data = {
        "messaging_product": "whatsapp",
        "recipient_type": "individual",
        "to": self._format_phone(to),
        "type": "interactive",
        "interactive": {
            "type": "button",
            "header": {
                "type": "text",
                "text": header_text
            },
            "body": {
                "text": body_text
            },
            "footer": {
                "text": footer_text
            },
            "action": {
                "buttons": [
                    {
                        "type": "reply",
                        "reply": {
                            "id": btn["id"],
                            "title": btn["title"][:20]  # Max 20 chars
                        }
                    }
                    for btn in buttons[:3]  # Max 3 buttons
                ]
            }
        }
    }
    
    return self._make_request(
        "POST",
        f"{self.config.PHONE_NUMBER_ID}/messages",
        json=data
    )

# Example: Customer Support Menu
def send_support_menu(self, to: str):
    """Send customer support options"""
    return self.send_quick_reply_buttons(
        to=to,
        header_text="🤝 Customer Support",
        body_text="How can we help you today? Select an option below:",
        footer_text="Smart Dairy Support Team",
        buttons=[
            {"id": "track_order", "title": "📦 Track Order"},
            {"id": "product_info", "title": "🥛 Products"},
            {"id": "speak_agent", "title": "👤 Speak to Agent"}
        ]
    )
```

### 8.2 List Messages

```python
def send_list_message(
    self,
    to: str,
    header_text: str,
    body_text: str,
    footer_text: str,
    button_text: str,
    sections: List[Dict]
) -> Dict:
    """
    Send interactive list message
    
    Args:
        sections: List of {"title": "Section Title", "rows": [{"id": "...", "title": "...", "description": "..."}]}
    """
    data = {
        "messaging_product": "whatsapp",
        "recipient_type": "individual",
        "to": self._format_phone(to),
        "type": "interactive",
        "interactive": {
            "type": "list",
            "header": {
                "type": "text",
                "text": header_text
            },
            "body": {
                "text": body_text
            },
            "footer": {
                "text": footer_text
            },
            "action": {
                "button": button_text[:20],  # Max 20 chars
                "sections": [
                    {
                        "title": section["title"][:24],  # Max 24 chars
                        "rows": [
                            {
                                "id": row["id"][:200],  # Max 200 chars
                                "title": row["title"][:24],  # Max 24 chars
                                "description": row.get("description", "")[:72]  # Max 72 chars
                            }
                            for row in section.get("rows", [])[:10]  # Max 10 rows per section
                        ]
                    }
                    for section in sections[:10]  # Max 10 sections
                ]
            }
        }
    }
    
    return self._make_request(
        "POST",
        f"{self.config.PHONE_NUMBER_ID}/messages",
        json=data
    )

# Example: Product Catalog List
def send_product_catalog(self, to: str):
    """Send product catalog as interactive list"""
    sections = [
        {
            "title": "🥛 Fresh Milk",
            "rows": [
                {"id": "milk_500ml", "title": "Fresh Milk 500ml", "description": "TZS 2,500 - Pasteurized fresh milk"},
                {"id": "milk_1l", "title": "Fresh Milk 1L", "description": "TZS 4,500 - Family size fresh milk"},
                {"id": "milk_2l", "title": "Fresh Milk 2L", "description": "TZS 8,500 - Bulk fresh milk"}
            ]
        },
        {
            "title": "🥣 Yogurt & Fermented",
            "rows": [
                {"id": "yogurt_natural", "title": "Natural Yogurt 500g", "description": "TZS 3,500 - Plain yogurt"},
                {"id": "yogurt_fruit", "title": "Fruit Yogurt 500g", "description": "TZS 4,000 - Strawberry/Mango"},
                {"id": "maziwa_lala", "title": "Maziwa Lala 1L", "description": "TZS 3,500 - Traditional fermented milk"}
            ]
        },
        {
            "title": "🧀 Cheese & Butter",
            "rows": [
                {"id": "cheese_cottage", "title": "Cottage Cheese 250g", "description": "TZS 6,500 - Fresh cottage cheese"},
                {"id": "butter_250g", "title": "Farm Butter 250g", "description": "TZS 5,500 - Salted butter"}
            ]
        }
    ]
    
    return self.send_list_message(
        to=to,
        header_text="🥛 Smart Dairy Catalog",
        body_text="Browse our fresh dairy products. Tap on any item to view details or add to cart.",
        footer_text="Free delivery on orders over TZS 50,000",
        button_text="View Products",
        sections=sections
    )
```

### 8.3 Call-to-Action Buttons

```python
def send_cta_url_button(
    self,
    to: str,
    header_text: str,
    body_text: str,
    footer_text: str,
    button_text: str,
    url: str
) -> Dict:
    """Send message with URL call-to-action button"""
    data = {
        "messaging_product": "whatsapp",
        "recipient_type": "individual",
        "to": self._format_phone(to),
        "type": "interactive",
        "interactive": {
            "type": "cta_url",
            "header": {
                "type": "text",
                "text": header_text
            },
            "body": {
                "text": body_text
            },
            "footer": {
                "text": footer_text
            },
            "action": {
                "name": "cta_url",
                "parameters": {
                    "display_text": button_text[:20],
                    "url": url
                }
            }
        }
    }
    
    return self._make_request(
        "POST",
        f"{self.config.PHONE_NUMBER_ID}/messages",
        json=data
    )

def send_track_order_cta(self, to: str, order_id: str):
    """Send order tracking CTA"""
    return self.send_cta_url_button(
        to=to,
        header_text="📦 Order Status",
        body_text=f"Your order {order_id} is on its way! Click below to track in real-time.",
        footer_text="Estimated delivery: Today by 6 PM",
        button_text="Track Order",
        url=f"https://smartdairy.co.tz/track/{order_id}"
    )
```

### 8.4 Handling Interactive Responses

```python
# message_handler.py
class MessageHandler:
    def __init__(self):
        self.client = WhatsAppClient()
    
    async def handle_interactive_message(self, message_data: dict):
        """Handle interactive message responses"""
        interactive_type = message_data.get("interactive_type")
        response = message_data.get("response", {})
        from_number = message_data.get("from")
        
        if interactive_type == "list_reply":
            list_reply = response.get("list_reply", {})
            selected_id = list_reply.get("id")
            selected_title = list_reply.get("title")
            
            await self.handle_list_selection(from_number, selected_id, selected_title)
        
        elif interactive_type == "button_reply":
            button_reply = response.get("button_reply", {})
            button_id = button_reply.get("id")
            button_title = button_reply.get("title")
            
            await self.handle_button_reply(from_number, button_id, button_title)
    
    async def handle_list_selection(self, phone: str, item_id: str, title: str):
        """Process product/catalog list selection"""
        # Product catalog selection
        if item_id.startswith("milk_") or item_id.startswith("yogurt_"):
            product_info = self.get_product_details(item_id)
            self.client.send_text_message(
                phone,
                f"*{product_info['name']}*\n\n"
                f"{product_info['description']}\n\n"
                f"💰 Price: {product_info['price']}\n"
                f"📦 Stock: {product_info['stock_status']}\n\n"
                f"Reply *ADD {item_id}* to add to cart or *MENU* for more options."
            )
        
        # Order tracking selection
        elif item_id.startswith("order_"):
            order_id = item_id.replace("order_", "")
            await self.send_order_details(phone, order_id)
    
    async def handle_button_reply(self, phone: str, button_id: str, title: str):
        """Process button click responses"""
        handlers = {
            "track_order": self.handle_track_order_request,
            "product_info": self.handle_product_info_request,
            "speak_agent": self.handle_agent_handoff,
            "confirm_attendance": self.handle_appointment_confirm,
            "reschedule": self.handle_appointment_reschedule
        }
        
        handler = handlers.get(button_id)
        if handler:
            await handler(phone)
        else:
            # Unknown button - default response
            self.client.send_text_message(
                phone,
                "Thank you for your response. How else can we help you today?"
            )
    
    async def handle_track_order_request(self, phone: str):
        """Handle track order button click"""
        # Get recent orders for customer
        orders = self.get_customer_orders(phone)
        
        if not orders:
            self.client.send_text_message(
                phone,
                "You don't have any active orders.\n\n"
                "Browse our products: https://smartdairy.co.tz/shop"
            )
            return
        
        # Send orders as list
        sections = [{
            "title": "Your Recent Orders",
            "rows": [
                {
                    "id": f"order_{order['id']}",
                    "title": f"Order {order['id'][:8]}",
                    "description": f"{order['status']} - {order['total']} - {order['date']}"
                }
                for order in orders[:5]
            ]
        }]
        
        self.client.send_list_message(
            to=phone,
            header_text="📦 Your Orders",
            body_text="Select an order to view tracking details:",
            footer_text="Smart Dairy Order Tracking",
            button_text="Select Order",
            sections=sections
        )
    
    async def handle_agent_handoff(self, phone: str):
        """Transfer to human agent"""
        # Notify customer
        self.client.send_text_message(
            phone,
            "👤 Connecting you to a customer service representative...\n\n"
            "Please describe your issue and an agent will assist you shortly.\n"
            "Average wait time: 2-3 minutes"
        )
        
        # Create support ticket
        self.create_support_ticket(phone, channel="whatsapp")
```

---

## 9. Media Messages

### 9.1 Sending Media Messages

```python
import mimetypes
from typing import BinaryIO

class MediaManager:
    """Handle media uploads and message sending"""
    
    def __init__(self, client: WhatsAppClient):
        self.client = client
    
    def upload_media(self, file_data: BinaryIO, mime_type: str = None) -> str:
        """
        Upload media to WhatsApp servers
        
        Returns:
            Media ID for use in messages
        """
        url = f"{self.client.config.api_url}/{self.client.config.PHONE_NUMBER_ID}/media"
        
        if not mime_type:
            mime_type = mimetypes.guess_type(file_data.name)[0] or "application/octet-stream"
        
        files = {
            "file": (file_data.name, file_data, mime_type),
            "messaging_product": (None, "whatsapp")
        }
        
        headers = {
            "Authorization": f"Bearer {self.client.config.ACCESS_TOKEN}"
        }
        
        response = requests.post(url, headers=headers, files=files)
        result = response.json()
        
        return result.get("id")
    
    def send_image_message(
        self,
        to: str,
        image_source: str,
        caption: str = None,
        is_media_id: bool = False
    ) -> Dict:
        """
        Send image message
        
        Args:
            image_source: URL or Media ID
            is_media_id: True if image_source is a Media ID, False if URL
        """
        image_param = {"id": image_source} if is_media_id else {"link": image_source}
        
        data = {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": self.client._format_phone(to),
            "type": "image",
            "image": image_param
        }
        
        if caption:
            data["image"]["caption"] = caption
        
        return self.client._make_request(
            "POST",
            f"{self.client.config.PHONE_NUMBER_ID}/messages",
            json=data
        )
    
    def send_document_message(
        self,
        to: str,
        document_source: str,
        filename: str = None,
        caption: str = None,
        is_media_id: bool = False
    ) -> Dict:
        """Send document (PDF, etc.)"""
        doc_param = {"id": document_source} if is_media_id else {"link": document_source}
        
        if filename:
            doc_param["filename"] = filename
        
        data = {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": self.client._format_phone(to),
            "type": "document",
            "document": doc_param
        }
        
        if caption:
            data["document"]["caption"] = caption
        
        return self.client._make_request(
            "POST",
            f"{self.client.config.PHONE_NUMBER_ID}/messages",
            json=data
        )
    
    def send_audio_message(
        self,
        to: str,
        audio_source: str,
        is_media_id: bool = False
    ) -> Dict:
        """Send audio message (voice note)"""
        audio_param = {"id": audio_source} if is_media_id else {"link": audio_source}
        
        data = {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": self.client._format_phone(to),
            "type": "audio",
            "audio": audio_param
        }
        
        return self.client._make_request(
            "POST",
            f"{self.client.config.PHONE_NUMBER_ID}/messages",
            json=data
        )
    
    def download_media(self, media_id: str) -> bytes:
        """Download media from WhatsApp servers"""
        # First get media URL
        url = f"{self.client.config.api_url}/{media_id}"
        headers = {"Authorization": f"Bearer {self.client.config.ACCESS_TOKEN}"}
        
        response = requests.get(url, headers=headers)
        media_info = response.json()
        
        # Download actual media
        media_url = media_info.get("url")
        if media_url:
            media_response = requests.get(media_url, headers=headers)
            return media_response.content
        
        return None
```

### 9.2 Order Confirmation with Invoice PDF

```python
def send_order_with_invoice(
    self,
    to: str,
    order_data: dict,
    invoice_pdf_path: str
) -> Dict:
    """Send order confirmation with invoice attachment"""
    # First send confirmation text
    confirmation_text = (
        f"✅ *Order Confirmed!*\n\n"
        f"Order ID: {order_data['order_id']}\n"
        f"Total: TZS {order_data['total']}\n"
        f"Estimated Delivery: {order_data['delivery_date']}\n\n"
        f"Your invoice is attached below."
    )
    
    self.client.send_text_message(to, confirmation_text)
    
    # Upload and send invoice
    with open(invoice_pdf_path, "rb") as pdf_file:
        media_id = self.upload_media(pdf_file, "application/pdf")
    
    return self.send_document_message(
        to=to,
        document_source=media_id,
        filename=f"Invoice_{order_data['order_id']}.pdf",
        caption=f"Invoice for Order {order_data['order_id']}",
        is_media_id=True
    )

# Send product image catalog
def send_product_image(self, to: str, product_id: str):
    """Send product image with details"""
    product = self.get_product_details(product_id)
    
    image_url = product.get("image_url")
    caption = (
        f"*{product['name']}*\n"
        f"{product['description']}\n\n"
        f"💰 Price: {product['price']}\n"
        f"⭐ Rating: {product['rating']}/5\n\n"
        f"Reply *BUY {product_id}* to order!"
    )
    
    return self.send_image_message(
        to=to,
        image_source=image_url,
        caption=caption
    )
```

---

## 10. Webhook Handling

### 10.1 Message Event Handling

```python
# webhook_handlers.py
from enum import Enum
from dataclasses import dataclass
from datetime import datetime

class MessageType(Enum):
    TEXT = "text"
    IMAGE = "image"
    DOCUMENT = "document"
    AUDIO = "audio"
    VIDEO = "video"
    LOCATION = "location"
    CONTACTS = "contacts"
    INTERACTIVE = "interactive"
    BUTTON = "button"
    ORDER = "order"

class MessageStatus(Enum):
    SENT = "sent"
    DELIVERED = "delivered"
    READ = "read"
    FAILED = "failed"

@dataclass
class IncomingMessage:
    message_id: str
    from_number: str
    timestamp: datetime
    message_type: MessageType
    content: dict
    profile_name: str = None
    wa_id: str = None

@dataclass
class MessageStatusUpdate:
    message_id: str
    status: MessageStatus
    timestamp: datetime
    recipient_id: str
    conversation_id: str = None
    pricing_model: str = None
    error_code: str = None
    error_message: str = None

class WebhookProcessor:
    def __init__(self, client: WhatsAppClient, db_connection):
        self.client = client
        self.db = db_connection
        self.conversation_manager = ConversationManager(db_connection)
    
    def process_message(self, payload: dict) -> IncomingMessage:
        """Process incoming message payload"""
        # Extract message data
        message = payload.get("messages", [{}])[0]
        contact = payload.get("contacts", [{}])[0]
        
        msg_type = MessageType(message.get("type", "text"))
        
        # Parse content based on type
        content = self._parse_content(message, msg_type)
        
        incoming = IncomingMessage(
            message_id=message.get("id"),
            from_number=message.get("from"),
            timestamp=datetime.fromtimestamp(int(message.get("timestamp"))),
            message_type=msg_type,
            content=content,
            profile_name=contact.get("profile", {}).get("name"),
            wa_id=contact.get("wa_id")
        )
        
        # Save to database
        self._save_message(incoming)
        
        # Update conversation window
        self.conversation_manager.update_window(incoming.from_number)
        
        return incoming
    
    def _parse_content(self, message: dict, msg_type: MessageType) -> dict:
        """Parse message content based on type"""
        parsers = {
            MessageType.TEXT: lambda m: {"body": m["text"]["body"]},
            MessageType.IMAGE: lambda m: {
                "media_id": m["image"]["id"],
                "caption": m["image"].get("caption"),
                "mime_type": m["image"].get("mime_type")
            },
            MessageType.DOCUMENT: lambda m: {
                "media_id": m["document"]["id"],
                "filename": m["document"].get("filename"),
                "caption": m["document"].get("caption")
            },
            MessageType.LOCATION: lambda m: {
                "latitude": m["location"]["latitude"],
                "longitude": m["location"]["longitude"],
                "name": m["location"].get("name"),
                "address": m["location"].get("address")
            },
            MessageType.INTERACTIVE: lambda m: {
                "type": m["interactive"].get("type"),
                "reply": m["interactive"].get("button_reply") or m["interactive"].get("list_reply")
            },
            MessageType.ORDER: lambda m: {
                "catalog_id": m["order"].get("catalog_id"),
                "product_items": m["order"].get("product_items", []),
                "text": m["order"].get("text")
            }
        }
        
        parser = parsers.get(msg_type, lambda m: {"raw": m})
        return parser(message)
    
    def process_status_update(self, status_data: dict) -> MessageStatusUpdate:
        """Process message status update"""
        status = MessageStatus(status_data.get("status"))
        
        update = MessageStatusUpdate(
            message_id=status_data.get("id"),
            status=status,
            timestamp=datetime.fromtimestamp(int(status_data.get("timestamp"))),
            recipient_id=status_data.get("recipient_id"),
            conversation_id=status_data.get("conversation", {}).get("id"),
            pricing_model=status_data.get("pricing", {}).get("pricing_model"),
            error_code=status_data.get("errors", [{}])[0].get("code") if status == MessageStatus.FAILED else None,
            error_message=status_data.get("errors", [{}])[0].get("message") if status == MessageStatus.FAILED else None
        )
        
        # Update message status in database
        self._update_message_status(update)
        
        # Handle specific statuses
        if status == MessageStatus.READ:
            self._handle_read_receipt(update)
        elif status == MessageStatus.FAILED:
            self._handle_delivery_failure(update)
        
        return update
    
    def _handle_read_receipt(self, update: MessageStatusUpdate):
        """Process read receipt for analytics"""
        # Calculate read latency
        sent_time = self._get_message_sent_time(update.message_id)
        if sent_time:
            read_latency = update.timestamp - sent_time
            self._log_metric("message_read_latency", read_latency.total_seconds())
    
    def _handle_delivery_failure(self, update: MessageStatusUpdate):
        """Handle failed message delivery"""
        # Log failure
        self._log_error("message_delivery_failed", {
            "message_id": update.message_id,
            "error_code": update.error_code,
            "error_message": update.error_message
        })
        
        # Notify admin if critical
        if update.error_code in ["131000", "131005"]:  # Invalid recipient or access denied
            self._alert_admin(f"Critical WhatsApp delivery failure: {update.error_code}")
```

### 10.2 Conversation Manager

```python
class ConversationManager:
    """Manage conversation windows and state"""
    
    def __init__(self, db_connection):
        self.db = db_connection
        self.windows = {}  # In-memory cache
    
    def update_window(self, phone_number: str):
        """Update conversation window on customer message"""
        now = datetime.now()
        window_expires = now + timedelta(hours=24)
        
        self.windows[phone_number] = {
            "open": True,
            "opened_at": now,
            "expires_at": window_expires,
            "last_customer_message": now
        }
        
        # Persist to database
        self._save_window(phone_number, window_expires)
    
    def can_send_session_message(self, phone_number: str) -> bool:
        """Check if session message can be sent"""
        # Check in-memory cache first
        window = self.windows.get(phone_number)
        if window:
            return datetime.now() < window["expires_at"]
        
        # Check database
        return self._check_window_in_db(phone_number)
    
    def get_conversation_category(self, phone_number: str) -> str:
        """Get current conversation category for pricing"""
        # Query active conversation from API or database
        # Returns: "marketing", "utility", "authentication", or "service"
        return self._get_active_conversation_category(phone_number)
```

---

## 11. Conversation-Based Pricing

### 11.1 Pricing Overview

WhatsApp Business API uses conversation-based pricing. Charges apply per conversation, not per message.

**Conversation Definition:**
- 24-hour messaging window
- Starts with first message (business or customer-initiated)
- All messages within 24 hours included in one charge

### 11.2 Conversation Categories and Rates

| Category | Rate (TZS) | Initiated By | Use Cases |
|----------|-----------|--------------|-----------|
| **Marketing** | ~150-200 | Business | Promotions, offers, product announcements |
| **Utility** | ~80-120 | Business | Order updates, delivery notifications, appointments |
| **Authentication** | ~60-100 | Business | OTP, verification codes, security alerts |
| **Service** | FREE | Customer | Support inquiries, general questions |

*Note: Rates are approximate and based on Meta's Tanzania pricing. Check current rates at developers.facebook.com.*

### 11.3 Conversation Pricing Logic

```python
# pricing_manager.py
from enum import Enum
from datetime import datetime, timedelta
from dataclasses import dataclass

class ConversationCategory(Enum):
    MARKETING = "marketing"
    UTILITY = "utility"
    AUTHENTICATION = "authentication"
    SERVICE = "service"

@dataclass
class Conversation:
    conversation_id: str
    phone_number: str
    category: ConversationCategory
    started_at: datetime
    expires_at: datetime
    is_open: bool = True
    message_count: int = 0
    cost_usd: float = 0.0

class PricingManager:
    """Manage conversation-based pricing"""
    
    # Current pricing (update from Meta regularly)
    PRICING = {
        "TZ": {  # Tanzania rates (USD)
            ConversationCategory.MARKETING: 0.0625,
            ConversationCategory.UTILITY: 0.0380,
            ConversationCategory.AUTHENTICATION: 0.0300,
            ConversationCategory.SERVICE: 0.0000  # Free
        }
    }
    
    def __init__(self, db_connection):
        self.db = db_connection
        self.conversations = {}
    
    def start_conversation(
        self,
        phone_number: str,
        category: ConversationCategory,
        initiated_by: str = "business"
    ) -> Conversation:
        """Start a new conversation or use existing"""
        
        # Check for existing open conversation
        existing = self._get_active_conversation(phone_number)
        if existing:
            return existing
        
        # Create new conversation
        now = datetime.now()
        conv = Conversation(
            conversation_id=self._generate_conv_id(),
            phone_number=phone_number,
            category=category,
            started_at=now,
            expires_at=now + timedelta(hours=24),
            cost_usd=self.PRICING["TZ"][category]
        )
        
        # Save and charge
        self._save_conversation(conv)
        self._charge_conversation(conv)
        
        return conv
    
    def categorize_outbound_message(
        self,
        template_name: str = None,
        message_content: str = None
    ) -> ConversationCategory:
        """Determine conversation category for outbound message"""
        
        # Check if using template
        if template_name:
            # Get template category from database
            template_category = self._get_template_category(template_name)
            if template_category:
                return ConversationCategory(template_category.lower())
        
        # Categorize based on content analysis
        content_lower = (message_content or "").lower()
        
        if any(word in content_lower for word in ["code", "otp", "verify", "password"]):
            return ConversationCategory.AUTHENTICATION
        
        if any(word in content_lower for word in ["order", "delivery", "payment", "invoice"]):
            return ConversationCategory.UTILITY
        
        if any(word in content_lower for word in ["offer", "discount", "sale", "promo"]):
            return ConversationCategory.MARKETING
        
        # Default to service for free-form customer-initiated
        return ConversationCategory.SERVICE
    
    def get_monthly_spend(self, year: int, month: int) -> dict:
        """Get monthly spend breakdown"""
        return self.db.query("""
            SELECT 
                category,
                COUNT(*) as conversation_count,
                SUM(cost_usd) as total_cost_usd,
                SUM(cost_usd * 2500) as total_cost_tzs  # Approximate exchange rate
            FROM conversations
            WHERE YEAR(started_at) = %s AND MONTH(started_at) = %s
            GROUP BY category
        """, (year, month))
    
    def estimate_cost(self, template_name: str, recipient_count: int) -> dict:
        """Estimate cost for bulk message campaign"""
        category = self._get_template_category(template_name)
        rate = self.PRICING["TZ"].get(ConversationCategory(category.lower()), 0)
        
        return {
            "template": template_name,
            "category": category,
            "recipients": recipient_count,
            "estimated_cost_usd": rate * recipient_count,
            "estimated_cost_tzs": rate * recipient_count * 2500,
            "per_conversation_rate_usd": rate
        }

# Pricing optimization recommendations
def get_pricing_optimization_tips():
    """Return best practices for cost optimization"""
    return [
        "Combine multiple updates into single message when possible",
        "Use Service conversations (free) by encouraging customer replies",
        "Schedule marketing messages during off-peak hours for better rates",
        "Batch authentication messages to minimize conversation starts",
        "Use template messages strategically - avoid sending multiple templates in same 24h window",
        "Monitor quality rating - low quality increases rates",
        "Use user's timezone to optimize conversation timing"
    ]
```

### 11.4 Cost Monitoring Dashboard

```python
# monitoring_dashboard.py
class CostDashboard:
    """Generate cost monitoring reports"""
    
    def __init__(self, pricing_manager: PricingManager):
        self.pm = pricing_manager
    
    def generate_daily_report(self, date: datetime = None):
        """Generate daily cost report"""
        if not date:
            date = datetime.now()
        
        report = {
            "date": date.strftime("%Y-%m-%d"),
            "summary": {
                "total_conversations": 0,
                "total_cost_usd": 0,
                "total_cost_tzs": 0,
                "avg_cost_per_conversation": 0
            },
            "breakdown": {},
            "top_recipients": [],
            "recommendations": []
        }
        
        # Get conversation data
        conversations = self.pm.get_conversations_for_date(date)
        
        # Calculate breakdown
        category_stats = {}
        for conv in conversations:
            cat = conv.category.value
            if cat not in category_stats:
                category_stats[cat] = {"count": 0, "cost": 0}
            category_stats[cat]["count"] += 1
            category_stats[cat]["cost"] += conv.cost_usd
        
        report["breakdown"] = category_stats
        report["summary"]["total_conversations"] = len(conversations)
        report["summary"]["total_cost_usd"] = sum(c.cost_usd for c in conversations)
        report["summary"]["total_cost_tzs"] = report["summary"]["total_cost_usd"] * 2500
        
        if conversations:
            report["summary"]["avg_cost_per_conversation"] = (
                report["summary"]["total_cost_usd"] / len(conversations)
            )
        
        return report
```

---

## 12. Analytics and Monitoring

### 12.1 Key Metrics

| Metric | Target | Description |
|--------|--------|-------------|
| Message Delivery Rate | >98% | Percentage of messages successfully delivered |
| Read Rate | >85% | Percentage of delivered messages read by recipient |
| Response Rate | >40% | Percentage of messages that receive a reply |
| Average Response Time | <5 minutes | Time to first response from business |
| Conversation Resolution Rate | >90% | Issues resolved within conversation |
| Template Approval Rate | >95% | Templates approved on first submission |
| Quality Rating | "Green" | Meta's quality assessment |

### 12.2 Analytics Implementation

```python
# analytics.py
from datetime import datetime, timedelta
from collections import defaultdict
import statistics

class WhatsAppAnalytics:
    """Analytics and reporting for WhatsApp Business API"""
    
    def __init__(self, db_connection):
        self.db = db_connection
    
    def get_delivery_analytics(self, start_date: datetime, end_date: datetime) -> dict:
        """Get message delivery statistics"""
        
        messages = self._get_messages_in_range(start_date, end_date)
        
        total = len(messages)
        delivered = sum(1 for m in messages if m.get("status") in ["delivered", "read"])
        read = sum(1 for m in messages if m.get("status") == "read")
        failed = sum(1 for m in messages if m.get("status") == "failed")
        
        # Calculate delivery times
        delivery_times = []
        for m in messages:
            if m.get("sent_at") and m.get("delivered_at"):
                dt = m["delivered_at"] - m["sent_at"]
                delivery_times.append(dt.total_seconds())
        
        return {
            "period": {
                "start": start_date.isoformat(),
                "end": end_date.isoformat()
            },
            "messages": {
                "total": total,
                "delivered": delivered,
                "read": read,
                "failed": failed
            },
            "rates": {
                "delivery_rate": round(delivered / total * 100, 2) if total else 0,
                "read_rate": round(read / delivered * 100, 2) if delivered else 0,
                "failure_rate": round(failed / total * 100, 2) if total else 0
            },
            "delivery_time": {
                "avg_seconds": round(statistics.mean(delivery_times), 2) if delivery_times else 0,
                "median_seconds": round(statistics.median(delivery_times), 2) if delivery_times else 0,
                "min_seconds": round(min(delivery_times), 2) if delivery_times else 0,
                "max_seconds": round(max(delivery_times), 2) if delivery_times else 0
            }
        }
    
    def get_conversation_analytics(self, days: int = 30) -> dict:
        """Get conversation metrics"""
        
        since = datetime.now() - timedelta(days=days)
        conversations = self._get_conversations_since(since)
        
        # Group by date
        daily = defaultdict(lambda: {"count": 0, "messages": 0, "resolved": 0})
        
        for conv in conversations:
            date_key = conv["started_at"].strftime("%Y-%m-%d")
            daily[date_key]["count"] += 1
            daily[date_key]["messages"] += conv.get("message_count", 0)
            if conv.get("resolved"):
                daily[date_key]["resolved"] += 1
        
        total_conversations = len(conversations)
        total_messages = sum(c.get("message_count", 0) for c in conversations)
        resolved = sum(1 for c in conversations if c.get("resolved"))
        
        return {
            "period_days": days,
            "conversations": {
                "total": total_conversations,
                "avg_per_day": round(total_conversations / days, 2),
                "resolution_rate": round(resolved / total_conversations * 100, 2) if total_conversations else 0
            },
            "messages": {
                "total": total_messages,
                "avg_per_conversation": round(total_messages / total_conversations, 2) if total_conversations else 0
            },
            "daily_breakdown": dict(daily)
        }
    
    def get_template_performance(self, template_name: str = None, days: int = 30) -> dict:
        """Get template message performance"""
        
        since = datetime.now() - timedelta(days=days)
        
        query = """
            SELECT 
                template_name,
                COUNT(*) as sent_count,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read_count
            FROM messages
            WHERE type = 'template' AND sent_at >= %s
        """
        params = [since]
        
        if template_name:
            query += " AND template_name = %s"
            params.append(template_name)
        
        query += " GROUP BY template_name"
        
        results = self.db.query(query, params)
        
        performance = []
        for row in results:
            sent = row["sent_count"]
            delivered = row["delivered"]
            read = row["read_count"]
            
            performance.append({
                "template_name": row["template_name"],
                "sent": sent,
                "delivered": delivered,
                "read": read,
                "delivery_rate": round(delivered / sent * 100, 2) if sent else 0,
                "read_rate": round(read / delivered * 100, 2) if delivered else 0
            })
        
        return {
            "period_days": days,
            "templates": performance
        }
    
    def get_quality_metrics(self) -> dict:
        """Get WhatsApp quality metrics from Meta API"""
        
        # Fetch from Meta Business API
        # This would typically be fetched periodically and cached
        
        return {
            "quality_rating": "GREEN",  # GREEN, YELLOW, RED
            "quality_score": 0.95,  # 0-1 scale
            "messaging_tier": "TIER_50",  # TIER_50, TIER_250, TIER_1K, etc.
            "daily_limit": 1000,
            "phone_number_status": "CONNECTED",
            "last_updated": datetime.now().isoformat()
        }
```

### 12.3 Alerting and Monitoring

```python
# monitoring.py
class WhatsAppMonitor:
    """Monitoring and alerting for WhatsApp Business API"""
    
    def __init__(self, analytics: WhatsAppAnalytics, client: WhatsAppClient):
        self.analytics = analytics
        self.client = client
        self.alert_thresholds = {
            "delivery_rate_min": 95,
            "read_rate_min": 70,
            "failure_rate_max": 5,
            "response_time_max": 300,  # seconds
            "quality_rating": "GREEN"
        }
    
    def run_health_check(self) -> dict:
        """Run comprehensive health check"""
        
        health = {
            "timestamp": datetime.now().isoformat(),
            "overall_status": "HEALTHY",
            "checks": {}
        }
        
        # Check message delivery rates
        delivery_stats = self.analytics.get_delivery_analytics(
            datetime.now() - timedelta(hours=24),
            datetime.now()
        )
        
        delivery_rate = delivery_stats["rates"]["delivery_rate"]
        delivery_check = {
            "status": "PASS" if delivery_rate >= self.alert_thresholds["delivery_rate_min"] else "FAIL",
            "rate": delivery_rate,
            "threshold": self.alert_thresholds["delivery_rate_min"]
        }
        health["checks"]["delivery_rate"] = delivery_check
        
        # Check quality rating
        quality = self.analytics.get_quality_metrics()
        quality_check = {
            "status": "PASS" if quality["quality_rating"] == self.alert_thresholds["quality_rating"] else "WARN",
            "rating": quality["quality_rating"],
            "expected": self.alert_thresholds["quality_rating"]
        }
        health["checks"]["quality_rating"] = quality_check
        
        # Check phone number status
        phone_status = self._check_phone_status()
        health["checks"]["phone_status"] = phone_status
        
        # Determine overall status
        if any(c["status"] == "FAIL" for c in health["checks"].values()):
            health["overall_status"] = "CRITICAL"
        elif any(c["status"] == "WARN" for c in health["checks"].values()):
            health["overall_status"] = "WARNING"
        
        return health
    
    def _check_phone_status(self) -> dict:
        """Check phone number status via API"""
        try:
            # Query phone number status
            result = self.client._make_request(
                "GET",
                f"{self.client.config.PHONE_NUMBER_ID}"
            )
            
            return {
                "status": "PASS",
                "phone_number": result.get("display_phone_number"),
                "quality_rating": result.get("quality_rating"),
                "messaging_limit": result.get("messaging_limit"),
                "verification_status": result.get("code_verification_status")
            }
        except Exception as e:
            return {
                "status": "FAIL",
                "error": str(e)
            }
    
    def send_alert(self, alert_type: str, message: str, severity: str = "warning"):
        """Send monitoring alert"""
        
        alert = {
            "type": alert_type,
            "severity": severity,  # info, warning, critical
            "message": message,
            "timestamp": datetime.now().isoformat()
        }
        
        # Send to monitoring channel (Slack, email, etc.)
        # Implementation depends on alerting infrastructure
        
        # Log alert
        logging.warning(f"WhatsApp Alert [{severity}]: {message}")
        
        return alert
```

---

## 13. Appendices

### Appendix A: Complete Code Examples

#### A.1 Full WhatsApp Client Implementation

```python
# complete_whatsapp_client.py
"""
Complete WhatsApp Business API Client for Smart Dairy Ltd
"""

import requests
import json
import logging
from typing import Optional, Dict, List, Any, BinaryIO
from dataclasses import dataclass
from datetime import datetime, timedelta
from enum import Enum

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class MessageType(Enum):
    TEXT = "text"
    IMAGE = "image"
    DOCUMENT = "document"
    AUDIO = "audio"
    VIDEO = "video"
    TEMPLATE = "template"
    LOCATION = "location"
    CONTACTS = "contacts"
    INTERACTIVE = "interactive"


class TemplateCategory(Enum):
    MARKETING = "MARKETING"
    UTILITY = "UTILITY"
    AUTHENTICATION = "AUTHENTICATION"


@dataclass
class WhatsAppConfig:
    """Configuration for WhatsApp Business API"""
    access_token: str
    phone_number_id: str
    waba_id: str
    api_version: str = "v18.0"
    base_url: str = "https://graph.facebook.com"
    webhook_verify_token: Optional[str] = None
    default_language: str = "en"
    
    @property
    def api_url(self) -> str:
        return f"{self.base_url}/{self.api_version}"
    
    @property
    def headers(self) -> Dict[str, str]:
        return {
            "Authorization": f"Bearer {self.access_token}",
            "Content-Type": "application/json"
        }


class WhatsAppClient:
    """
    Production-ready WhatsApp Business API Client
    
    Usage:
        config = WhatsAppConfig(
            access_token="YOUR_ACCESS_TOKEN",
            phone_number_id="PHONE_NUMBER_ID",
            waba_id="WABA_ID"
        )
        client = WhatsAppClient(config)
        client.send_text_message("255712345678", "Hello from Smart Dairy!")
    """
    
    def __init__(self, config: WhatsAppConfig):
        self.config = config
        self.session = requests.Session()
        self.session.headers.update(config.headers)
    
    # ============ Core API Methods ============
    
    def _make_request(
        self, 
        method: str, 
        endpoint: str, 
        **kwargs
    ) -> Dict[str, Any]:
        """Make authenticated API request with error handling"""
        url = f"{self.config.api_url}/{endpoint}"
        
        try:
            response = self.session.request(method, url, **kwargs)
            response.raise_for_status()
            return response.json()
        except requests.exceptions.HTTPError as e:
            error_data = response.json() if response.content else {}
            logger.error(f"API HTTP Error: {error_data}")
            raise WhatsAppAPIError(f"HTTP {e.response.status_code}: {error_data}") from e
        except requests.exceptions.RequestException as e:
            logger.error(f"Request Error: {str(e)}")
            raise WhatsAppAPIError(f"Request failed: {str(e)}") from e
    
    # ============ Message Sending ============
    
    def send_text_message(
        self,
        to: str,
        message: str,
        preview_url: bool = False,
        reply_to: Optional[str] = None
    ) -> Dict:
        """
        Send text message (session message)
        
        Args:
            to: Recipient phone number (with country code)
            message: Message text (max 4096 characters)
            preview_url: Enable URL preview
            reply_to: Message ID to reply to
        """
        data = {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": self._format_phone(to),
            "type": "text",
            "text": {
                "preview_url": preview_url,
                "body": message[:4096]
            }
        }
        
        if reply_to:
            data["context"] = {"message_id": reply_to}
        
        return self._make_request(
            "POST",
            f"{self.config.phone_number_id}/messages",
            json=data
        )
    
    def send_template_message(
        self,
        to: str,
        template_name: str,
        language_code: str = "en",
        components: Optional[List[Dict]] = None,
        reply_to: Optional[str] = None
    ) -> Dict:
        """
        Send template message
        
        Args:
            to: Recipient phone number
            template_name: Name of approved template
            language_code: Language code (default: en)
            components: Template component parameters
            reply_to: Message ID to reply to
        """
        data = {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": self._format_phone(to),
            "type": "template",
            "template": {
                "name": template_name,
                "language": {"code": language_code}
            }
        }
        
        if components:
            data["template"]["components"] = components
        
        if reply_to:
            data["context"] = {"message_id": reply_to}
        
        return self._make_request(
            "POST",
            f"{self.config.phone_number_id}/messages",
            json=data
        )
    
    # ============ Interactive Messages ============
    
    def send_quick_reply_buttons(
        self,
        to: str,
        body_text: str,
        buttons: List[Dict[str, str]],
        header_text: Optional[str] = None,
        footer_text: Optional[str] = None
    ) -> Dict:
        """
        Send message with quick reply buttons
        
        Args:
            buttons: List of {"id": "unique_id", "title": "Button Text"}
                     Max 3 buttons, title max 20 characters
        """
        interactive = {
            "type": "button",
            "body": {"text": body_text[:1024]},
            "action": {
                "buttons": [
                    {
                        "type": "reply",
                        "reply": {
                            "id": btn["id"][:256],
                            "title": btn["title"][:20]
                        }
                    }
                    for btn in buttons[:3]
                ]
            }
        }
        
        if header_text:
            interactive["header"] = {
                "type": "text",
                "text": header_text[:60]
            }
        
        if footer_text:
            interactive["footer"] = {"text": footer_text[:60]}
        
        data = {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": self._format_phone(to),
            "type": "interactive",
            "interactive": interactive
        }
        
        return self._make_request(
            "POST",
            f"{self.config.phone_number_id}/messages",
            json=data
        )
    
    def send_list_message(
        self,
        to: str,
        button_text: str,
        sections: List[Dict],
        body_text: str,
        header_text: Optional[str] = None,
        footer_text: Optional[str] = None
    ) -> Dict:
        """
        Send interactive list message
        
        Args:
            button_text: Button text to open list (max 20 chars)
            sections: List of sections with rows
        """
        interactive = {
            "type": "list",
            "body": {"text": body_text[:1024]},
            "action": {
                "button": button_text[:20],
                "sections": [
                    {
                        "title": section["title"][:24],
                        "rows": [
                            {
                                "id": row["id"][:200],
                                "title": row["title"][:24],
                                "description": row.get("description", "")[:72]
                            }
                            for row in section.get("rows", [])[:10]
                        ]
                    }
                    for section in sections[:10]
                ]
            }
        }
        
        if header_text:
            interactive["header"] = {
                "type": "text",
                "text": header_text[:60]
            }
        
        if footer_text:
            interactive["footer"] = {"text": footer_text[:60]}
        
        data = {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": self._format_phone(to),
            "type": "interactive",
            "interactive": interactive
        }
        
        return self._make_request(
            "POST",
            f"{self.config.phone_number_id}/messages",
            json=data
        )
    
    # ============ Media Messages ============
    
    def upload_media(self, file_data: BinaryIO, mime_type: str) -> str:
        """
        Upload media to WhatsApp servers
        
        Returns:
            Media ID for use in messages
        """
        url = f"{self.config.api_url}/{self.config.phone_number_id}/media"
        
        files = {
            "file": (file_data.name, file_data, mime_type),
            "messaging_product": (None, "whatsapp")
        }
        
        headers = {"Authorization": f"Bearer {self.config.access_token}"}
        response = requests.post(url, headers=headers, files=files)
        response.raise_for_status()
        
        return response.json().get("id")
    
    def send_image(
        self,
        to: str,
        image_source: str,
        caption: Optional[str] = None,
        is_media_id: bool = False
    ) -> Dict:
        """Send image message"""
        image_param = {"id": image_source} if is_media_id else {"link": image_source}
        
        data = {
            "messaging_product": "whatsapp",
            "recipient_type": "individual",
            "to": self._format_phone(to),
            "type": "image",
            "image": image_param
        }
        
        if caption:
            data["image"]["caption"] = caption[:1024]
        
        return self._make_request(
            "POST",
            f"{self.config.phone_number_id}/messages",
            json=data
        )
    
    # ============ Business Profile ============
    
    def get_business_profile(self) -> Dict:
        """Get WhatsApp Business Profile"""
        params = {"fields": "about,address,description,email,websites,vertical"}
        return self._make_request(
            "GET",
            f"{self.config.phone_number_id}/whatsapp_business_profile",
            params=params
        )
    
    def update_business_profile(self, profile_data: Dict) -> Dict:
        """Update WhatsApp Business Profile"""
        return self._make_request(
            "POST",
            f"{self.config.phone_number_id}/whatsapp_business_profile",
            json=profile_data
        )
    
    # ============ Template Management ============
    
    def create_template(self, template_data: Dict) -> Dict:
        """Create message template"""
        return self._make_request(
            "POST",
            f"{self.config.waba_id}/message_templates",
            json=template_data
        )
    
    def list_templates(self, limit: int = 100) -> Dict:
        """List all message templates"""
        return self._make_request(
            "GET",
            f"{self.config.waba_id}/message_templates",
            params={"limit": limit}
        )
    
    def delete_template(self, template_name: str) -> Dict:
        """Delete message template"""
        return self._make_request(
            "DELETE",
            f"{self.config.waba_id}/message_templates",
            params={"name": template_name}
        )
    
    # ============ Utility Methods ============
    
    @staticmethod
    def _format_phone(phone: str) -> str:
        """Format phone number (remove +, spaces, dashes)"""
        return phone.replace("+", "").replace(" ", "").replace("-", "")
    
    def get_message_status(self, message_id: str) -> Dict:
        """Get status of sent message"""
        return self._make_request("GET", message_id)


class WhatsAppAPIError(Exception):
    """Custom exception for WhatsApp API errors"""
    pass


# ============ Usage Example ============

if __name__ == "__main__":
    # Initialize client
    config = WhatsAppConfig(
        access_token="YOUR_ACCESS_TOKEN_HERE",
        phone_number_id="PHONE_NUMBER_ID_HERE",
        waba_id="WABA_ID_HERE",
        webhook_verify_token="YOUR_VERIFY_TOKEN"
    )
    
    client = WhatsAppClient(config)
    
    # Send text message
    result = client.send_text_message(
        to="255712345678",
        message="Hello from Smart Dairy! Your fresh milk order is confirmed."
    )
    print(f"Message sent: {result}")
    
    # Send template message
    template_result = client.send_template_message(
        to="255712345678",
        template_name="order_confirmation_v2",
        components=[
            {
                "type": "header",
                "parameters": [{"type": "text", "text": "ORD-2026-001"}]
            },
            {
                "type": "body",
                "parameters": [
                    {"type": "text", "text": "John Doe"},
                    {"type": "text", "text": "Fresh Milk 1L x 2"},
                    {"type": "text", "text": "TZS 9,000"},
                    {"type": "text", "text": "January 31, 2026"}
                ]
            }
        ]
    )
    print(f"Template sent: {template_result}")
```

### Appendix B: Template Formats

#### B.1 Complete Template Library

```json
{
  "smart_dairy_templates": {
    "order_confirmation_v2": {
      "name": "order_confirmation_v2",
      "category": "UTILITY",
      "language": "en",
      "components": [
        {
          "type": "HEADER",
          "format": "TEXT",
          "text": "Order {{1}} Confirmed ✓"
        },
        {
          "type": "BODY",
          "text": "Hi {{2}},\n\nThank you for your order! We've received your payment and are preparing your items.\n\n📦 Order Details:\n{{3}}\n\n💰 Total Amount: {{4}}\n📅 Expected Delivery: {{5}}\n\nWe'll notify you when your order is on the way!"
        },
        {
          "type": "FOOTER",
          "text": "Smart Dairy Ltd - Fresh from our farm to your home"
        },
        {
          "type": "BUTTONS",
          "buttons": [
            {
              "type": "URL",
              "text": "Track Order",
              "url": "https://smartdairy.co.tz/orders/{{1}}"
            }
          ]
        }
      ]
    },
    
    "delivery_out_for_delivery": {
      "name": "delivery_out_for_delivery",
      "category": "UTILITY",
      "language": "en",
      "components": [
        {
          "type": "HEADER",
          "format": "TEXT",
          "text": "🚚 Out for Delivery"
        },
        {
          "type": "BODY",
          "text": "Hello {{1}},\n\nGreat news! Your order {{2}} is out for delivery.\n\n📍 Delivery Address:\n{{3}}\n\n⏰ Expected Time: {{4}}\n👤 Driver: {{5}}\n📱 Driver Contact: {{6}}\n\nPlease ensure someone is available to receive the order."
        },
        {
          "type": "BUTTONS",
          "buttons": [
            {
              "type": "URL",
              "text": "Live Tracking",
              "url": "https://smartdairy.co.tz/track/{{2}}"
            },
            {
              "type": "PHONE_NUMBER",
              "text": "Call Driver",
              "phone_number": "{{7}}"
            }
          ]
        }
      ]
    },
    
    "delivery_completed": {
      "name": "delivery_completed",
      "category": "UTILITY",
      "language": "en",
      "components": [
        {
          "type": "HEADER",
          "format": "TEXT",
          "text": "✅ Order Delivered"
        },
        {
          "type": "BODY",
          "text": "Hi {{1}},\n\nYour order {{2}} has been successfully delivered!\n\n📦 Delivered Items:\n{{3}}\n\nWe hope you enjoy our fresh dairy products. Your feedback helps us serve you better."
        },
        {
          "type": "FOOTER",
          "text": "Thank you for choosing Smart Dairy!"
        },
        {
          "type": "BUTTONS",
          "buttons": [
            {
              "type": "URL",
              "text": "Rate Your Experience",
              "url": "https://smartdairy.co.tz/feedback/{{2}}"
            }
          ]
        }
      ]
    },
    
    "farm_visit_reminder": {
      "name": "farm_visit_reminder",
      "category": "UTILITY",
      "language": "en",
      "components": [
        {
          "type": "HEADER",
          "format": "TEXT",
          "text": "📅 Farm Visit Reminder"
        },
        {
          "type": "BODY",
          "text": "Hello {{1}},\n\nThis is a friendly reminder about your upcoming farm visit:\n\n📍 Location: Smart Dairy Farm, {{2}}\n📅 Date: {{3}}\n⏰ Time: {{4}}\n\nWhat to bring:\n✓ Valid ID\n✓ Comfortable closed shoes\n✓ Hat or cap for sun protection\n\nWe look forward to hosting you!"
        },
        {
          "type": "BUTTONS",
          "buttons": [
            {
              "type": "QUICK_REPLY",
              "text": "Confirm"
            },
            {
              "type": "QUICK_REPLY",
              "text": "Reschedule"
            },
            {
              "type": "QUICK_REPLY",
              "text": "Cancel"
            }
          ]
        }
      ]
    },
    
    "payment_received": {
      "name": "payment_received",
      "category": "UTILITY",
      "language": "en",
      "components": [
        {
          "type": "BODY",
          "text": "Hi {{1}},\n\nWe have received your payment of {{2}} for order {{3}}.\n\n✅ Payment Status: Confirmed\n🧾 Receipt Number: {{4}}\n\nThank you for your business!"
        },
        {
          "type": "FOOTER",
          "text": "Smart Dairy Ltd"
        }
      ]
    },
    
    "promotional_offer": {
      "name": "promotional_offer",
      "category": "MARKETING",
      "language": "en",
      "components": [
        {
          "type": "HEADER",
          "format": "IMAGE",
          "example": {
            "header_handle": ["https://smartdairy.co.tz/promo-example.jpg"]
          }
        },
        {
          "type": "BODY",
          "text": "🎉 Special Offer for {{1}}!\n\nGet {{2}} off all fresh dairy products this week only!\n\nFeatured Deals:\n🥛 Fresh Milk 1L - TZS {{3}}\n🥣 Natural Yogurt 500g - TZS {{4}}\n🧀 Farm Cheese 250g - TZS {{5}}\n\nUse promo code: *{{6}}*\nValid until {{7}}\n\nReply SHOP to browse or ORDER to place an order now!"
        },
        {
          "type": "FOOTER",
          "text": "Reply STOP to unsubscribe"
        },
        {
          "type": "BUTTONS",
          "buttons": [
            {
              "type": "URL",
              "text": "Shop Now",
              "url": "https://smartdairy.co.tz/shop?utm_source=whatsapp&utm_campaign={{6}}"
            },
            {
              "type": "QUICK_REPLY",
              "text": "View Catalog"
            }
          ]
        }
      ]
    },
    
    "otp_authentication": {
      "name": "otp_authentication",
      "category": "AUTHENTICATION",
      "language": "en",
      "components": [
        {
          "type": "BODY",
          "text": "{{1}} is your Smart Dairy verification code.\n\nThis code will expire in {{2}} minutes. Do not share this code with anyone."
        },
        {
          "type": "FOOTER",
          "text": "Smart Dairy Security"
        },
        {
          "type": "BUTTONS",
          "buttons": [
            {
              "type": "COPY_CODE",
              "example": ["123456"]
            }
          ]
        }
      ]
    },
    
    "low_stock_alert": {
      "name": "low_stock_alert",
      "category": "UTILITY",
      "language": "en",
      "components": [
        {
          "type": "HEADER",
          "format": "TEXT",
          "text": "⚠️ Low Stock Alert"
        },
        {
          "type": "BODY",
          "text": "Hi {{1}},\n\nThe product you regularly order is running low on stock:\n\n🥛 Product: {{2}}\n📦 Remaining Stock: {{3}} units\n\nPlace your order now to ensure you don't miss out!"
        },
        {
          "type": "BUTTONS",
          "buttons": [
            {
              "type": "URL",
              "text": "Order Now",
              "url": "https://smartdairy.co.tz/product/{{4}}"
            }
          ]
        }
      ]
    },
    
    "subscription_renewal": {
      "name": "subscription_renewal",
      "category": "UTILITY",
      "language": "en",
      "components": [
        {
          "type": "HEADER",
          "format": "TEXT",
          "text": "🔄 Subscription Renewal"
        },
        {
          "type": "BODY",
          "text": "Hi {{1}},\n\nYour {{2}} subscription is expiring soon.\n\n📅 Current Plan: {{3}}\n💰 Renewal Amount: {{4}}\n⏰ Expiry Date: {{5}}\n\nRenew now to continue enjoying fresh dairy delivered to your door!"
        },
        {
          "type": "BUTTONS",
          "buttons": [
            {
              "type": "URL",
              "text": "Renew Subscription",
              "url": "https://smartdairy.co.tz/subscription/renew"
            },
            {
              "type": "QUICK_REPLY",
              "text": "Modify Plan"
            }
          ]
        }
      ]
    }
  }
}
```

### Appendix C: Pricing Calculator

```python
# pricing_calculator.py
"""
WhatsApp Business API Pricing Calculator for Smart Dairy Ltd
"""

from dataclasses import dataclass
from typing import Dict, List
from enum import Enum


class ConversationCategory(Enum):
    MARKETING = "marketing"
    UTILITY = "utility"
    AUTHENTICATION = "authentication"
    SERVICE = "service"


# Current Meta pricing for Tanzania (USD per conversation)
# Update these rates regularly from developers.facebook.com
PRICING_RATES = {
    "TZ": {
        ConversationCategory.MARKETING: 0.0625,
        ConversationCategory.UTILITY: 0.0380,
        ConversationCategory.AUTHENTICATION: 0.0300,
        ConversationCategory.SERVICE: 0.0000  # Free
    }
}

EXCHANGE_RATE_TZS = 2500  # Approximate TZS per USD


@dataclass
class CampaignEstimate:
    """Cost estimate for a messaging campaign"""
    template_name: str
    category: ConversationCategory
    recipient_count: int
    cost_per_conversation_usd: float
    total_cost_usd: float
    total_cost_tzs: float


@dataclass
class MonthlyProjection:
    """Monthly cost projection"""
    month: str
    conversations: Dict[ConversationCategory, int]
    total_cost_usd: float
    total_cost_tzs: float
    breakdown: Dict[str, float]


class PricingCalculator:
    """Calculate WhatsApp Business API costs"""
    
    def __init__(self, country_code: str = "TZ"):
        self.country = country_code
        self.rates = PRICING_RATES.get(country_code, PRICING_RATES["TZ"])
    
    def calculate_campaign_cost(
        self,
        template_name: str,
        category: ConversationCategory,
        recipient_count: int
    ) -> CampaignEstimate:
        """Calculate cost for a single campaign"""
        
        rate = self.rates.get(category, 0)
        total_usd = rate * recipient_count
        
        return CampaignEstimate(
            template_name=template_name,
            category=category,
            recipient_count=recipient_count,
            cost_per_conversation_usd=rate,
            total_cost_usd=round(total_usd, 2),
            total_cost_tzs=round(total_usd * EXCHANGE_RATE_TZS, 2)
        )
    
    def project_monthly_cost(
        self,
        marketing_conversations: int = 0,
        utility_conversations: int = 0,
        authentication_conversations: int = 0,
        service_conversations: int = 0,
        month: str = ""
    ) -> MonthlyProjection:
        """Project monthly costs based on conversation volumes"""
        
        conversations = {
            ConversationCategory.MARKETING: marketing_conversations,
            ConversationCategory.UTILITY: utility_conversations,
            ConversationCategory.AUTHENTICATION: authentication_conversations,
            ConversationCategory.SERVICE: service_conversations
        }
        
        total_usd = sum(
            count * self.rates.get(category, 0)
            for category, count in conversations.items()
        )
        
        breakdown = {
            cat.value: round(count * self.rates.get(cat, 0), 2)
            for cat, count in conversations.items()
        }
        
        return MonthlyProjection(
            month=month,
            conversations=conversations,
            total_cost_usd=round(total_usd, 2),
            total_cost_tzs=round(total_usd * EXCHANGE_RATE_TZS, 2),
            breakdown=breakdown
        )
    
    def compare_scenarios(
        self,
        scenario_a: Dict,
        scenario_b: Dict,
        scenario_a_name: str = "Scenario A",
        scenario_b_name: str = "Scenario B"
    ) -> Dict:
        """Compare two pricing scenarios"""
        
        proj_a = self.project_monthly_cost(**scenario_a)
        proj_b = self.project_monthly_cost(**scenario_b)
        
        return {
            scenario_a_name: {
                "total_usd": proj_a.total_cost_usd,
                "total_tzs": proj_a.total_cost_tzs,
                "breakdown": proj_a.breakdown
            },
            scenario_b_name: {
                "total_usd": proj_b.total_cost_usd,
                "total_tzs": proj_b.total_cost_tzs,
                "breakdown": proj_b.breakdown
            },
            "difference_usd": round(proj_a.total_cost_usd - proj_b.total_cost_usd, 2),
            "difference_tzs": round(proj_a.total_cost_tzs - proj_b.total_cost_tzs, 2),
            "savings_percentage": round(
                (proj_a.total_cost_usd - proj_b.total_cost_usd) / proj_a.total_cost_usd * 100, 2
            ) if proj_a.total_cost_usd > 0 else 0
        }


# Example usage and Smart Dairy projections
if __name__ == "__main__":
    calc = PricingCalculator()
    
    print("=" * 60)
    print("SMART DAIRY - WhatsApp Business API Pricing Calculator")
    print("=" * 60)
    
    # Campaign examples
    print("\n--- Campaign Cost Estimates ---\n")
    
    campaigns = [
        ("Order Confirmations", ConversationCategory.UTILITY, 5000),
        ("Delivery Notifications", ConversationCategory.UTILITY, 4000),
        ("Monthly Newsletter", ConversationCategory.MARKETING, 10000),
        ("Promotional Blast", ConversationCategory.MARKETING, 15000),
        ("OTP Verification", ConversationCategory.AUTHENTICATION, 2000),
    ]
    
    for name, category, recipients in campaigns:
        estimate = calc.calculate_campaign_cost(name, category, recipients)
        print(f"{name}:")
        print(f"  Recipients: {estimate.recipient_count:,}")
        print(f"  Category: {estimate.category.value}")
        print(f"  Cost per conversation: ${estimate.cost_per_conversation_usd:.4f}")
        print(f"  Total cost: ${estimate.total_cost_usd:,.2f} USD / TZS {estimate.total_cost_tzs:,.0f}")
        print()
    
    # Monthly projection
    print("\n--- Monthly Projection (Conservative) ---\n")
    
    conservative = calc.project_monthly_cost(
        marketing_conversations=5000,
        utility_conversations=15000,
        authentication_conversations=2000,
        service_conversations=8000,
        month="February 2026"
    )
    
    print(f"Month: {conservative.month}")
    print(f"\nConversation Volumes:")
    for cat, count in conservative.conversations.items():
        print(f"  {cat.value:20s}: {count:6,} conv.")
    print(f"\nCost Breakdown:")
    for cat, cost in conservative.breakdown.items():
        print(f"  {cat:20s}: ${cost:8.2f}")
    print(f"\n{'TOTAL COST':20s}: ${conservative.total_cost_usd:8.2f} USD")
    print(f"{'TOTAL COST':20s}: TZS {conservative.total_cost_tzs:,.0f}")
    
    # Growth scenario
    print("\n--- Monthly Projection (Growth Scenario - 6 months) ---\n")
    
    growth = calc.project_monthly_cost(
        marketing_conversations=25000,
        utility_conversations=50000,
        authentication_conversations=5000,
        service_conversations=25000,
        month="August 2026"
    )
    
    print(f"Month: {growth.month}")
    print(f"\nConversation Volumes:")
    for cat, count in growth.conversations.items():
        print(f"  {cat.value:20s}: {count:6,} conv.")
    print(f"\n{'TOTAL COST':20s}: ${growth.total_cost_usd:8.2f} USD")
    print(f"{'TOTAL COST':20s}: TZS {growth.total_cost_tzs:,.0f}")
```

### Appendix D: Environment Configuration

```bash
# .env file for WhatsApp Business API Integration
# Smart Dairy Ltd - Configuration

# ============================================
# Meta/WhatsApp API Credentials
# ============================================
WHATSAPP_ACCESS_TOKEN=EAAG...
WHATSAPP_PHONE_NUMBER_ID=123456789012345
WHATSAPP_WABA_ID=987654321098765

# ============================================
# Webhook Configuration
# ============================================
WEBHOOK_VERIFY_TOKEN=smart_dairy_webhook_verify_2026
WEBHOOK_ENDPOINT=/webhooks/whatsapp
WEBHOOK_URL=https://api.smartdairy.co.tz/webhooks/whatsapp

# ============================================
# Server Configuration
# ============================================
HOST=0.0.0.0
PORT=8000
DEBUG=false

# ============================================
# Database Configuration
# ============================================
DB_HOST=localhost
DB_PORT=5432
DB_NAME=smartdairy_whatsapp
DB_USER=whatsapp_service
DB_PASSWORD=secure_password_here

# ============================================
# Redis Configuration (for caching)
# ============================================
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_DB=0

# ============================================
# Monitoring & Alerts
# ============================================
SLACK_WEBHOOK_URL=https://hooks.slack.com/services/...
ALERT_EMAIL=alerts@smartdairy.co.tz
ADMIN_PHONE=+255712345678

# ============================================
# Feature Flags
# ============================================
ENABLE_MARKETING_MESSAGES=true
ENABLE_AUTO_REPLY=true
ENABLE_MEDIA_MESSAGES=true
```

### Appendix E: API Response Codes

| Code | Status | Description | Action |
|------|--------|-------------|--------|
| 131000 | Failed | Generic error | Retry with exponential backoff |
| 131001 | Failed | Invalid recipient | Verify phone number format |
| 131005 | Failed | Access denied | Check access token permissions |
| 131008 | Failed | Parameter value invalid | Validate template parameters |
| 131009 | Failed | Template not found | Verify template name and approval |
| 131016 | Failed | API method not supported | Check API version |
| 131021 | Failed | Timeout | Retry request |
| 132000 | Failed | Template paused | Check template status |
| 132001 | Failed | Template disabled | Template rejected, create new |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Integration Engineer | _________________ | _______ |
| Owner | Tech Lead | _________________ | _______ |
| Reviewer | CTO | _________________ | _______ |

---

*End of Document E-008*

*Document Classification: Internal - Implementation Documentation*
*Smart Dairy Ltd - Copyright 2026*
