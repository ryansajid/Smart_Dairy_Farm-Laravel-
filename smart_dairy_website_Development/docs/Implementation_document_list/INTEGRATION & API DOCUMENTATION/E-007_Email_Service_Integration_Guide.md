# E-007: Email Service Integration Guide

## Smart Dairy Ltd. Smart Web Portal System

---

**Document Control**

| Field | Value |
|-------|-------|
| **Document ID** | E-007 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Integration Engineer |
| **Owner** | Tech Lead |
| **Reviewer** | CTO |
| **Status** | Approved |
| **Classification** | Internal Use |

**Revision History**

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | 2026-01-31 | Integration Engineer | Initial document creation |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Provider Selection](#2-provider-selection)
3. [Prerequisites](#3-prerequisites)
4. [SMTP vs API](#4-smtp-vs-api)
5. [Template System](#5-template-system)
6. [Email Types](#6-email-types)
7. [Implementation](#7-implementation)
8. [Personalization](#8-personalization)
9. [Analytics](#9-analytics)
10. [Deliverability](#10-deliverability)
11. [Testing](#11-testing)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidance for integrating email services into the Smart Dairy Smart Web Portal System. It covers transactional emails, marketing campaigns, and system alerts with best practices for implementation, deliverability, and analytics.

### 1.2 Email Categories

| Category | Description | Priority | Volume |
|----------|-------------|----------|--------|
| **Transactional** | Immediate, user-triggered emails (order confirmations, password resets) | High | Medium |
| **Marketing** | Scheduled campaigns, newsletters, promotions | Medium | High |
| **System Alerts** | Internal team notifications, monitoring alerts | Critical | Low |

### 1.3 Business Requirements

- **Reliability**: 99.9% delivery rate for transactional emails
- **Speed**: Sub-second API response times
- **Scalability**: Support for 100,000+ emails/day
- **Compliance**: GDPR, CAN-SPAM, and local regulations
- **Tracking**: Open rates, click tracking, bounce handling

---

## 2. Provider Selection

### 2.1 Comparison Matrix

| Feature | SendGrid | AWS SES | Mailgun |
|---------|----------|---------|---------|
| **Free Tier** | 100 emails/day | 62,000 emails/month | 5,000 emails/month |
| **Pricing (100K)** | $90/month | $10/month | $80/month |
| **API Quality** | Excellent | Good | Excellent |
| **Template Editor** | Visual + Code | Code only | Code only |
| **Deliverability** | Excellent | Good | Excellent |
| **Analytics** | Advanced | Basic | Advanced |
| **SMTP Relay** | Yes | Yes | Yes |
| **Webhooks** | Comprehensive | Basic | Comprehensive |
| **Support** | 24/7 (paid) | Business hours | 24/7 (paid) |

### 2.2 Recommendation

**Primary: SendGrid**

- Superior template management with visual editor
- Advanced analytics and deliverability tools
- Excellent Python SDK support
- Proven track record for transactional emails

**Secondary: AWS SES**

- Cost-effective for high volume
- Good for bulk marketing campaigns
- Integration with AWS ecosystem

### 2.3 Provider Configuration

```python
# config/email.py
from dataclasses import dataclass
from typing import Optional

@dataclass
class EmailProviderConfig:
    name: str
    api_key: str
    from_email: str
    from_name: str
    webhook_secret: Optional[str] = None
    sandbox_mode: bool = False

# SendGrid Configuration
SENDGRID_CONFIG = EmailProviderConfig(
    name="sendgrid",
    api_key="SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    from_email="noreply@smartdairy.co.ke",
    from_name="Smart Dairy Ltd.",
    webhook_secret="whsec_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
)

# AWS SES Configuration (fallback)
AWS_SES_CONFIG = EmailProviderConfig(
    name="aws_ses",
    api_key="AKIA...",
    from_email="noreply@smartdairy.co.ke",
    from_name="Smart Dairy Ltd."
)
```

---

## 3. Prerequisites

### 3.1 Domain Verification

Before sending emails, complete domain authentication:

1. **Register sending domain**: `smartdairy.co.ke`
2. **Verify domain ownership** via DNS TXT record
3. **Configure subdomains**:
   - `mail.smartdairy.co.ke` - Transactional emails
   - `newsletter.smartdairy.co.ke` - Marketing emails
   - `alerts.smartdairy.co.ke` - System alerts

### 3.2 DNS Records Setup

#### SPF Record (Sender Policy Framework)

```
Type: TXT
Name: @
Value: v=spf1 include:sendgrid.net include:amazonses.com -all
TTL: 3600
```

#### DKIM Record (DomainKeys Identified Mail)

SendGrid generates unique DKIM records:

```
Type: TXT
Name: s1._domainkey.smartdairy.co.ke
Value: k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC...
TTL: 3600

Type: TXT
Name: s2._domainkey.smartdairy.co.ke
Value: k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC...
TTL: 3600
```

#### DMARC Record (Domain-based Message Authentication)

```
Type: TXT
Name: _dmarc.smartdairy.co.ke
Value: v=DMARC1; p=quarantine; rua=mailto:dmarc@smartdairy.co.ke; pct=100; adkim=r; aspf=r
TTL: 3600
```

#### MX Record (for bounce handling)

```
Type: MX
Name: mail.smartdairy.co.ke
Value: 10 mx.sendgrid.net
Priority: 10
TTL: 3600
```

### 3.3 DNS Verification Script

```python
# scripts/verify_dns.py
import dns.resolver
import sys

def verify_spf(domain: str) -> bool:
    """Verify SPF record exists and is valid."""
    try:
        answers = dns.resolver.resolve(domain, 'TXT')
        for rdata in answers:
            if 'v=spf1' in str(rdata):
                return True
        return False
    except Exception as e:
        print(f"SPF verification failed: {e}")
        return False

def verify_dkim(domain: str, selector: str = 's1') -> bool:
    """Verify DKIM record exists."""
    try:
        dkim_domain = f"{selector}._domainkey.{domain}"
        answers = dns.resolver.resolve(dkim_domain, 'TXT')
        return len(answers) > 0
    except Exception as e:
        print(f"DKIM verification failed: {e}")
        return False

def verify_dmarc(domain: str) -> bool:
    """Verify DMARC record exists."""
    try:
        dmarc_domain = f"_dmarc.{domain}"
        answers = dns.resolver.resolve(dmarc_domain, 'TXT')
        for rdata in answers:
            if 'v=DMARC1' in str(rdata):
                return True
        return False
    except Exception as e:
        print(f"DMARC verification failed: {e}")
        return False

if __name__ == "__main__":
    DOMAIN = "smartdairy.co.ke"
    
    checks = {
        "SPF": verify_spf(DOMAIN),
        "DKIM": verify_dkim(DOMAIN),
        "DMARC": verify_dmarc(DOMAIN)
    }
    
    all_passed = all(checks.values())
    
    print(f"\nDNS Verification Results for {DOMAIN}:")
    print("-" * 40)
    for check, passed in checks.items():
        status = "✓ PASS" if passed else "✗ FAIL"
        print(f"{check}: {status}")
    
    sys.exit(0 if all_passed else 1)
```

---

## 4. SMTP vs API

### 4.1 When to Use Each

| Use Case | Recommended | Reason |
|----------|-------------|--------|
| Transactional emails | API | Better error handling, faster, templates |
| Marketing bulk sends | API | Batching, scheduling, analytics |
| Legacy integrations | SMTP | Compatibility with existing systems |
| Quick prototyping | SMTP | No code changes needed |
| System monitoring | API | Structured logging, retry logic |

### 4.2 API Configuration

```python
# services/email_api.py
from sendgrid import SendGridAPIClient
from sendgrid.helpers.mail import Mail, Email, To, Content, Attachment, FileContent, FileName, FileType, Disposition
from python_http_client.exceptions import HTTPError
import base64

class EmailAPIClient:
    def __init__(self, api_key: str, from_email: str, from_name: str):
        self.client = SendGridAPIClient(api_key)
        self.from_email = Email(from_email, from_name)
    
    def send_email(
        self,
        to_email: str,
        subject: str,
        html_content: str,
        text_content: str = None,
        attachments: list = None,
        categories: list = None
    ) -> dict:
        """Send email via SendGrid API."""
        message = Mail(
            from_email=self.from_email,
            to_emails=To(to_email),
            subject=subject,
            html_content=html_content
        )
        
        if text_content:
            message.content = Content("text/plain", text_content)
        
        if attachments:
            for attachment in attachments:
                message.add_attachment(attachment)
        
        if categories:
            message.categories = categories
        
        try:
            response = self.client.send(message)
            return {
                "success": response.status_code == 202,
                "status_code": response.status_code,
                "message_id": response.headers.get('X-Message-Id'),
                "body": response.body
            }
        except HTTPError as e:
            return {
                "success": False,
                "error": str(e),
                "status_code": e.status_code if hasattr(e, 'status_code') else None
            }
```

### 4.3 SMTP Configuration

```python
# services/email_smtp.py
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.base import MIMEBase
from email import encoders
from typing import List, Dict

class EmailSMTPClient:
    def __init__(
        self,
        host: str = "smtp.sendgrid.net",
        port: int = 587,
        username: str = "apikey",
        password: str = None,
        use_tls: bool = True
    ):
        self.host = host
        self.port = port
        self.username = username
        self.password = password
        self.use_tls = use_tls
    
    def send_email(
        self,
        from_email: str,
        to_emails: List[str],
        subject: str,
        html_content: str,
        text_content: str = None,
        attachments: List[Dict] = None
    ) -> dict:
        """Send email via SMTP."""
        msg = MIMEMultipart('alternative')
        msg['Subject'] = subject
        msg['From'] = from_email
        msg['To'] = ', '.join(to_emails)
        
        # Attach text version
        if text_content:
            msg.attach(MIMEText(text_content, 'plain'))
        
        # Attach HTML version
        msg.attach(MIMEText(html_content, 'html'))
        
        # Handle attachments
        if attachments:
            for attachment in attachments:
                part = MIMEBase('application', 'octet-stream')
                part.set_payload(attachment['content'])
                encoders.encode_base64(part)
                part.add_header(
                    'Content-Disposition',
                    f"attachment; filename= {attachment['filename']}"
                )
                msg.attach(part)
        
        try:
            with smtplib.SMTP(self.host, self.port) as server:
                if self.use_tls:
                    server.starttls()
                server.login(self.username, self.password)
                server.sendmail(from_email, to_emails, msg.as_string())
            
            return {"success": True, "message": "Email sent successfully"}
        except Exception as e:
            return {"success": False, "error": str(e)}
```

### 4.4 Configuration Selection

```python
# services/email_factory.py
from django.conf import settings
from .email_api import EmailAPIClient
from .email_smtp import EmailSMTPClient

def get_email_client(client_type: str = "api"):
    """Factory function to get appropriate email client."""
    if client_type == "api":
        return EmailAPIClient(
            api_key=settings.SENDGRID_API_KEY,
            from_email=settings.DEFAULT_FROM_EMAIL,
            from_name=settings.DEFAULT_FROM_NAME
        )
    elif client_type == "smtp":
        return EmailSMTPClient(
            host=settings.EMAIL_HOST,
            port=settings.EMAIL_PORT,
            username=settings.EMAIL_HOST_USER,
            password=settings.EMAIL_HOST_PASSWORD,
            use_tls=settings.EMAIL_USE_TLS
        )
    else:
        raise ValueError(f"Unknown client type: {client_type}")
```

---

## 5. Template System

### 5.1 Template Structure

```
templates/email/
├── base.html              # Base template with branding
├── transactional/         # Transactional email templates
│   ├── welcome.html
│   ├── order_confirmation.html
│   ├── password_reset.html
│   └── delivery_notification.html
├── marketing/             # Marketing email templates
│   ├── newsletter.html
│   ├── promotional.html
│   └── product_launch.html
└── system/                # System alert templates
    ├── error_alert.html
    ├── daily_report.html
    └── security_notice.html
```

### 5.2 Base Template

```html
<!-- templates/email/base.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{% block title %}{% endblock %}</title>
    <style>
        /* Reset styles */
        body { margin: 0; padding: 0; min-width: 100%; font-family: Arial, sans-serif; }
        table { border-spacing: 0; }
        td { padding: 0; }
        img { border: 0; display: block; }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .container { width: 100% !important; }
            .content { padding: 20px !important; }
            .hide-mobile { display: none !important; }
        }
        
        /* Brand colors */
        .brand-primary { color: #1E5631; }
        .brand-secondary { color: #4A7C59; }
        .brand-accent { color: #F4A261; }
        .bg-brand { background-color: #1E5631; }
        
        /* Typography */
        h1 { font-size: 28px; line-height: 1.3; margin: 0 0 20px; }
        h2 { font-size: 22px; line-height: 1.3; margin: 0 0 15px; }
        p { font-size: 16px; line-height: 1.6; margin: 0 0 15px; color: #333; }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #1E5631;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn:hover { background-color: #4A7C59; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4;">
    <table role="presentation" class="container" width="600" align="center" style="margin: 0 auto;">
        <!-- Header -->
        <tr>
            <td class="bg-brand" style="padding: 20px; text-align: center;">
                <img src="https://smartdairy.co.ke/static/logo-white.png" 
                     alt="Smart Dairy" width="200" style="margin: 0 auto;">
            </td>
        </tr>
        
        <!-- Main Content -->
        <tr>
            <td class="content" style="padding: 40px; background-color: #ffffff;">
                {% block content %}{% endblock %}
            </td>
        </tr>
        
        <!-- Footer -->
        <tr>
            <td style="padding: 20px; background-color: #f4f4f4; text-align: center;">
                <p style="font-size: 12px; color: #666;">
                    © {% now "Y" %} Smart Dairy Ltd. All rights reserved.<br>
                    P.O. Box 12345, Nairobi, Kenya<br>
                    <a href="{{ unsubscribe_url }}" style="color: #666;">Unsubscribe</a> | 
                    <a href="{{ preferences_url }}" style="color: #666;">Email Preferences</a>
                </p>
                <p style="font-size: 12px; color: #999;">
                    This email was sent to {{ recipient_email }}
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
```

### 5.3 Jinja2 Template Rendering

```python
# services/template_renderer.py
from jinja2 import Environment, FileSystemLoader, select_autoescape
from datetime import datetime
import os

class EmailTemplateRenderer:
    def __init__(self, templates_dir: str = "templates/email"):
        self.env = Environment(
            loader=FileSystemLoader(templates_dir),
            autoescape=select_autoescape(['html', 'xml'])
        )
        # Add custom filters
        self.env.filters['currency'] = self.format_currency
        self.env.filters['date'] = self.format_date
    
    @staticmethod
    def format_currency(value: float, currency: str = "KES") -> str:
        return f"{currency} {value:,.2f}"
    
    @staticmethod
    def format_date(value: datetime, format: str = "%B %d, %Y") -> str:
        return value.strftime(format)
    
    def render(self, template_name: str, context: dict) -> str:
        """Render email template with context."""
        template = self.env.get_template(template_name)
        return template.render(**context)
    
    def render_welcome_email(self, user_data: dict) -> str:
        """Render welcome email template."""
        context = {
            "user_name": user_data.get('first_name', 'Valued Customer'),
            "login_url": "https://portal.smartdairy.co.ke/login",
            "support_email": "support@smartdairy.co.ke",
            "recipient_email": user_data.get('email'),
            "unsubscribe_url": f"https://portal.smartdairy.co.ke/unsubscribe?token={user_data.get('token')}",
            "preferences_url": f"https://portal.smartdairy.co.ke/preferences?token={user_data.get('token')}"
        }
        return self.render("transactional/welcome.html", context)
    
    def render_order_confirmation(self, order_data: dict) -> str:
        """Render order confirmation email."""
        context = {
            "customer_name": order_data.get('customer_name'),
            "order_id": order_data.get('order_id'),
            "order_date": order_data.get('order_date'),
            "items": order_data.get('items', []),
            "subtotal": order_data.get('subtotal'),
            "tax": order_data.get('tax'),
            "total": order_data.get('total'),
            "delivery_date": order_data.get('delivery_date'),
            "tracking_url": order_data.get('tracking_url'),
            "recipient_email": order_data.get('customer_email'),
            "unsubscribe_url": f"https://portal.smartdairy.co.ke/unsubscribe",
            "preferences_url": f"https://portal.smartdairy.co.ke/preferences"
        }
        return self.render("transactional/order_confirmation.html", context)
```

### 5.4 Responsive Design Best Practices

```html
<!-- Example: Order Confirmation Template -->
{% extends "email/base.html" %}

{% block title %}Order Confirmation - Smart Dairy{% endblock %}

{% block content %}
<h1 class="brand-primary">Thank You for Your Order!</h1>

<p>Dear {{ customer_name }},</p>

<p>We're processing your order <strong>#{{ order_id }}</strong> placed on {{ order_date|date }}.</p>

<!-- Order Summary Table -->
<table width="100%" style="margin: 20px 0; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f4f4f4;">
            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #1E5631;">Product</th>
            <th style="padding: 10px; text-align: center; border-bottom: 2px solid #1E5631;">Qty</th>
            <th style="padding: 10px; text-align: right; border-bottom: 2px solid #1E5631;">Price</th>
        </tr>
    </thead>
    <tbody>
        {% for item in items %}
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                {{ item.name }}
            </td>
            <td style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">
                {{ item.quantity }}
            </td>
            <td style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">
                {{ item.price|currency }}
            </td>
        </tr>
        {% endfor %}
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="padding: 10px; text-align: right;"><strong>Subtotal:</strong></td>
            <td style="padding: 10px; text-align: right;">{{ subtotal|currency }}</td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 10px; text-align: right;"><strong>Tax:</strong></td>
            <td style="padding: 10px; text-align: right;">{{ tax|currency }}</td>
        </tr>
        <tr style="background-color: #f4f4f4;">
            <td colspan="2" style="padding: 10px; text-align: right;"><strong>Total:</strong></td>
            <td style="padding: 10px; text-align: right;" class="brand-primary">
                <strong>{{ total|currency }}</strong>
            </td>
        </tr>
    </tfoot>
</table>

<p style="text-align: center; margin: 30px 0;">
    <a href="{{ tracking_url }}" class="btn">Track Your Order</a>
</p>

<p>Expected delivery: <strong>{{ delivery_date|date }}</strong></p>

<p>If you have any questions, please contact us at <a href="mailto:support@smartdairy.co.ke">support@smartdairy.co.ke</a>.</p>

<p>Best regards,<br>The Smart Dairy Team</p>
{% endblock %}
```

---

## 6. Email Types

### 6.1 Transactional Emails

| Email Type | Trigger | Priority | Template |
|------------|---------|----------|----------|
| Welcome | Account registration | High | `transactional/welcome.html` |
| Order Confirmation | Order placed | Critical | `transactional/order_confirmation.html` |
| Payment Receipt | Payment received | High | `transactional/payment_receipt.html` |
| Password Reset | Reset requested | Critical | `transactional/password_reset.html` |
| Delivery Notification | Order dispatched | High | `transactional/delivery_notification.html` |
| Account Verification | Email verification | Critical | `transactional/verify_email.html` |

### 6.2 Marketing Emails

| Email Type | Frequency | Audience | Template |
|------------|-----------|----------|----------|
| Weekly Newsletter | Weekly | All subscribers | `marketing/newsletter.html` |
| Product Promotions | Bi-weekly | Segmented | `marketing/promotional.html` |
| New Product Launch | As needed | Targeted | `marketing/product_launch.html` |
| Re-engagement | Monthly | Inactive users | `marketing/re_engagement.html` |
| Seasonal Campaigns | Quarterly | All subscribers | `marketing/seasonal.html` |

### 6.3 System Alerts

| Alert Type | Recipients | Urgency | Channel |
|------------|------------|---------|---------|
| Server Errors | DevOps Team | Critical | Email + SMS |
| Database Issues | DBA Team | Critical | Email + Slack |
| Security Breach | Security Team | Critical | Email + SMS + Call |
| Daily Reports | Management | Low | Email only |
| Backup Failures | IT Team | High | Email + Slack |
| API Rate Limits | Dev Team | Medium | Email |

---

## 7. Implementation

### 7.1 Celery Task Configuration

```python
# celery_config.py
from celery import Celery
from celery.signals import task_failure
import os

app = Celery('smart_dairy_email')
app.config_from_object({
    'broker_url': os.getenv('REDIS_URL', 'redis://localhost:6379/0'),
    'result_backend': os.getenv('REDIS_URL', 'redis://localhost:6379/0'),
    'task_serializer': 'json',
    'accept_content': ['json'],
    'result_serializer': 'json',
    'timezone': 'Africa/Nairobi',
    'enable_utc': True,
    'task_routes': {
        'email.send_transactional': {'queue': 'email.transactional'},
        'email.send_marketing': {'queue': 'email.marketing'},
        'email.send_bulk': {'queue': 'email.bulk'},
    },
    'task_default_queue': 'email.transactional',
    'worker_prefetch_multiplier': 1,
    'task_acks_late': True,
})

@task_failure.connect
def handle_task_failure(sender, task_id, exception, args, kwargs, traceback, einfo, **kw):
    """Handle task failures and log to monitoring."""
    from services.monitoring import alert_ops_team
    alert_ops_team(
        title=f"Email Task Failed: {sender.name}",
        message=f"Task {task_id} failed with: {exception}"
    )
```

### 7.2 Email Service with Celery

```python
# services/email_service.py
from celery import shared_task
from django.conf import settings
from .email_api import EmailAPIClient
from .template_renderer import EmailTemplateRenderer
from .models import EmailLog, EmailTemplate
import logging

logger = logging.getLogger(__name__)

class EmailService:
    def __init__(self):
        self.client = EmailAPIClient(
            api_key=settings.SENDGRID_API_KEY,
            from_email=settings.DEFAULT_FROM_EMAIL,
            from_name=settings.DEFAULT_FROM_NAME
        )
        self.renderer = EmailTemplateRenderer()
    
    def send_transactional(
        self,
        to_email: str,
        template_key: str,
        context: dict,
        attachments: list = None
    ) -> dict:
        """Send transactional email asynchronously."""
        return send_transactional_email.delay(
            to_email=to_email,
            template_key=template_key,
            context=context,
            attachments=attachments
        )
    
    def send_marketing(
        self,
        to_emails: list,
        template_key: str,
        context: dict,
        scheduled_at: str = None
    ) -> dict:
        """Send marketing email asynchronously."""
        return send_marketing_email.apply_async(
            args=[to_emails, template_key, context],
            eta=scheduled_at
        )
    
    def send_bulk(
        self,
        recipient_list: list,
        template_key: str,
        context: dict,
        batch_size: int = 100
    ) -> str:
        """Send bulk emails in batches."""
        job_id = f"bulk_{template_key}_{datetime.now().timestamp()}"
        
        for i in range(0, len(recipient_list), batch_size):
            batch = recipient_list[i:i + batch_size]
            send_bulk_email.delay(
                job_id=job_id,
                batch=batch,
                template_key=template_key,
                context=context
            )
        
        return job_id

@shared_task(bind=True, max_retries=3, default_retry_delay=60)
def send_transactional_email(self, to_email: str, template_key: str, context: dict, attachments: list = None):
    """Celery task for sending transactional emails."""
    try:
        service = EmailService()
        template = EmailTemplate.objects.get(key=template_key, is_active=True)
        
        # Render template
        html_content = service.renderer.render(template.file_path, context)
        text_content = strip_tags(html_content)
        
        # Send email
        result = service.client.send_email(
            to_email=to_email,
            subject=template.subject,
            html_content=html_content,
            text_content=text_content,
            attachments=attachments,
            categories=['transactional', template_key]
        )
        
        # Log email
        EmailLog.objects.create(
            recipient=to_email,
            template=template_key,
            message_id=result.get('message_id'),
            status='sent' if result['success'] else 'failed',
            error_message=result.get('error')
        )
        
        if not result['success']:
            raise self.retry(exc=Exception(result.get('error')))
        
        return result
        
    except Exception as exc:
        logger.error(f"Failed to send transactional email: {exc}")
        raise self.retry(exc=exc)

@shared_task(bind=True, max_retries=2, default_retry_delay=300)
def send_marketing_email(self, to_emails: list, template_key: str, context: dict):
    """Celery task for sending marketing emails."""
    try:
        service = EmailService()
        template = EmailTemplate.objects.get(key=template_key, is_active=True)
        
        # Send to each recipient with personalization
        for email in to_emails:
            personalized_context = {**context, 'recipient_email': email}
            html_content = service.renderer.render(template.file_path, personalized_context)
            
            result = service.client.send_email(
                to_email=email,
                subject=template.subject,
                html_content=html_content,
                categories=['marketing', template_key]
            )
            
            # Log with delay to avoid DB overload
            EmailLog.objects.create(
                recipient=email,
                template=template_key,
                message_id=result.get('message_id'),
                status='sent' if result['success'] else 'failed'
            )
        
        return {"sent": len(to_emails)}
        
    except Exception as exc:
        logger.error(f"Failed to send marketing email: {exc}")
        raise self.retry(exc=exc)

@shared_task
def send_bulk_email(job_id: str, batch: list, template_key: str, context: dict):
    """Celery task for bulk email batches."""
    service = EmailService()
    template = EmailTemplate.objects.get(key=template_key, is_active=True)
    
    success_count = 0
    for email in batch:
        try:
            personalized_context = {**context, 'recipient_email': email}
            html_content = service.renderer.render(template.file_path, personalized_context)
            
            result = service.client.send_email(
                to_email=email,
                subject=template.subject,
                html_content=html_content,
                categories=['bulk', template_key, job_id]
            )
            
            if result['success']:
                success_count += 1
                
        except Exception as e:
            logger.error(f"Failed to send to {email}: {e}")
    
    # Update bulk job status
    BulkEmailJob.objects.filter(job_id=job_id).update(
        sent_count=F('sent_count') + success_count
    )
    
    return {"job_id": job_id, "batch_sent": success_count}
```

### 7.3 Attachment Handling

```python
# services/attachments.py
from sendgrid.helpers.mail import Attachment, FileContent, FileName, FileType, Disposition
import base64
import mimetypes
from pathlib import Path

class AttachmentHandler:
    ALLOWED_EXTENSIONS = {'.pdf', '.jpg', '.jpeg', '.png', '.doc', '.docx'}
    MAX_FILE_SIZE = 10 * 1024 * 1024  # 10MB
    
    @classmethod
    def create_attachment(
        cls,
        file_path: str = None,
        file_content: bytes = None,
        filename: str = None,
        content_id: str = None
    ) -> Attachment:
        """Create SendGrid attachment object."""
        if file_path:
            path = Path(file_path)
            if path.suffix.lower() not in cls.ALLOWED_EXTENSIONS:
                raise ValueError(f"File type not allowed: {path.suffix}")
            
            file_content = path.read_bytes()
            filename = filename or path.name
        
        if len(file_content) > cls.MAX_FILE_SIZE:
            raise ValueError(f"File exceeds maximum size of {cls.MAX_FILE_SIZE} bytes")
        
        encoded = base64.b64encode(file_content).decode()
        
        attachment = Attachment()
        attachment.file_content = FileContent(encoded)
        attachment.file_name = FileName(filename)
        attachment.file_type = FileType(mimetypes.guess_type(filename)[0] or 'application/octet-stream')
        
        if content_id:
            attachment.disposition = Disposition('inline')
            attachment.content_id = content_id
        else:
            attachment.disposition = Disposition('attachment')
        
        return attachment
    
    @classmethod
    def create_invoice_attachment(cls, invoice_data: dict) -> Attachment:
        """Generate and attach PDF invoice."""
        from services.pdf_generator import generate_invoice_pdf
        
        pdf_content = generate_invoice_pdf(invoice_data)
        return cls.create_attachment(
            file_content=pdf_content,
            filename=f"Invoice_{invoice_data['invoice_number']}.pdf"
        )
```

---

## 8. Personalization

### 8.1 Dynamic Content System

```python
# services/personalization.py
from typing import Dict, Any
from datetime import datetime, timedelta

class EmailPersonalizer:
    def __init__(self, user: 'User'):
        self.user = user
        self.segments = self._get_user_segments()
    
    def _get_user_segments(self) -> list:
        """Determine user segments for targeted content."""
        segments = []
        
        # Purchase behavior
        if self.user.orders.count() == 0:
            segments.append('new_customer')
        elif self.user.orders.filter(created_at__gte=datetime.now()-timedelta(days=30)).exists():
            segments.append('active_customer')
        else:
            segments.append('inactive_customer')
        
        # Order value
        avg_order = self.user.orders.avg('total')
        if avg_order > 10000:
            segments.append('high_value')
        elif avg_order > 5000:
            segments.append('medium_value')
        
        # Product preferences
        top_category = self.user.orders.values('items__product__category').annotate(
            count=Count('id')
        ).order_by('-count').first()
        
        if top_category:
            segments.append(f"prefers_{top_category['items__product__category']}")
        
        return segments
    
    def get_personalized_content(self, email_type: str) -> Dict[str, Any]:
        """Get personalized content based on user segments."""
        content = {
            'greeting': self._get_greeting(),
            'recommendations': self._get_recommendations(),
            'offers': self._get_personalized_offers(),
            'content_blocks': self._get_content_blocks(email_type)
        }
        return content
    
    def _get_greeting(self) -> str:
        """Generate personalized greeting."""
        hour = datetime.now().hour
        time_greeting = "Good morning" if 5 <= hour < 12 else \
                       "Good afternoon" if 12 <= hour < 17 else \
                       "Good evening"
        
        return f"{time_greeting}, {self.user.first_name or 'Valued Customer'}"
    
    def _get_recommendations(self) -> list:
        """Get product recommendations based on purchase history."""
        from products.models import Product
        
        # Collaborative filtering or simple category match
        purchased_categories = self.user.orders.values_list(
            'items__product__category', flat=True
        ).distinct()
        
        recommendations = Product.objects.filter(
            category__in=purchased_categories,
            is_active=True
        ).exclude(
            id__in=self.user.orders.values_list('items__product_id', flat=True)
        ).order_by('-popularity')[:4]
        
        return recommendations
    
    def _get_personalized_offers(self) -> list:
        """Get personalized offers based on segments."""
        offers = []
        
        if 'new_customer' in self.segments:
            offers.append({
                'title': 'Welcome Offer',
                'discount': '20% off your first order',
                'code': 'WELCOME20'
            })
        
        if 'high_value' in self.segments:
            offers.append({
                'title': 'VIP Exclusive',
                'discount': 'Free delivery on orders over KES 5,000',
                'code': 'VIPFREE'
            })
        
        if 'inactive_customer' in self.segments:
            offers.append({
                'title': 'We Miss You!',
                'discount': '15% off to welcome you back',
                'code': 'COMEBACK15'
            })
        
        return offers
    
    def _get_content_blocks(self, email_type: str) -> list:
        """Get dynamic content blocks for email."""
        blocks = []
        
        if email_type == 'newsletter':
            if 'prefers_dairy' in self.segments:
                blocks.append({
                    'title': 'Fresh Arrivals',
                    'content': 'Check out our new organic milk varieties!'
                })
            
            if 'prefers_fodder' in self.segments:
                blocks.append({
                    'title': 'Fodder Tips',
                    'content': 'Best practices for silage storage this season.'
                })
        
        return blocks
```

### 8.2 User Segmentation

```python
# models/segments.py
from django.db import models
from django.contrib.auth import get_user_model

User = get_user_model()

class UserSegment(models.Model):
    name = models.CharField(max_length=100, unique=True)
    description = models.TextField()
    criteria = models.JSONField(help_text="Segment criteria as JSON")
    users = models.ManyToManyField(User, related_name='segments', blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    
    class Meta:
        db_table = 'user_segments'
    
    def __str__(self):
        return self.name

class SegmentRuleEngine:
    """Engine for evaluating user segments."""
    
    RULES = {
        'order_count': lambda user, value: user.orders.count() >= value,
        'days_since_last_order': lambda user, value: (
            (timezone.now() - user.orders.latest('created_at').created_at).days >= value
            if user.orders.exists() else True
        ),
        'total_spent': lambda user, value: (
            user.orders.aggregate(total=Sum('total'))['total'] or 0
        ) >= value,
        'preferred_category': lambda user, value: (
            user.orders.filter(items__product__category=value).exists()
        ),
        'registration_date': lambda user, value: (
            user.date_joined >= timezone.now() - timedelta(days=value)
        ),
    }
    
    @classmethod
    def evaluate_segment(cls, user: User, criteria: dict) -> bool:
        """Evaluate if user matches segment criteria."""
        for rule, value in criteria.items():
            if rule in cls.RULES:
                if not cls.RULES[rule](user, value):
                    return False
        return True
    
    @classmethod
    def update_user_segments(cls, user: User):
        """Update user's segment memberships."""
        for segment in UserSegment.objects.all():
            matches = cls.evaluate_segment(user, segment.criteria)
            
            if matches:
                segment.users.add(user)
            else:
                segment.users.remove(user)
```

---

## 9. Analytics

### 9.1 Email Event Tracking

```python
# services/analytics.py
from django.db import models
from django.utils import timezone

class EmailAnalytics(models.Model):
    message_id = models.CharField(max_length=255, unique=True)
    recipient = models.EmailField()
    template = models.CharField(max_length=100)
    category = models.CharField(max_length=50)
    
    # Status tracking
    sent_at = models.DateTimeField(null=True, blank=True)
    delivered_at = models.DateTimeField(null=True, blank=True)
    opened_at = models.DateTimeField(null=True, blank=True)
    clicked_at = models.DateTimeField(null=True, blank=True)
    bounced_at = models.DateTimeField(null=True, blank=True)
    complained_at = models.DateTimeField(null=True, blank=True)
    unsubscribed_at = models.DateTimeField(null=True, blank=True)
    
    # Engagement metrics
    open_count = models.PositiveIntegerField(default=0)
    click_count = models.PositiveIntegerField(default=0)
    clicked_links = models.JSONField(default=list)
    
    # Device/location info
    user_agent = models.TextField(blank=True)
    ip_address = models.GenericIPAddressField(null=True, blank=True)
    
    class Meta:
        db_table = 'email_analytics'
        indexes = [
            models.Index(fields=['template', 'sent_at']),
            models.Index(fields=['recipient', 'sent_at']),
            models.Index(fields=['category']),
        ]

class AnalyticsService:
    """Service for processing email analytics."""
    
    @staticmethod
    def process_webhook_event(event_data: dict):
        """Process incoming webhook event from email provider."""
        event = event_data.get('event')
        message_id = event_data.get('sg_message_id', '').split('.')[0]
        
        try:
            analytics = EmailAnalytics.objects.get(message_id=message_id)
        except EmailAnalytics.DoesNotExist:
            return
        
        timestamp = timezone.datetime.fromtimestamp(
            event_data.get('timestamp'), tz=timezone.utc
        )
        
        if event == 'delivered':
            analytics.delivered_at = timestamp
        elif event == 'open':
            analytics.opened_at = analytics.opened_at or timestamp
            analytics.open_count += 1
            analytics.user_agent = event_data.get('useragent', '')
        elif event == 'click':
            analytics.clicked_at = analytics.clicked_at or timestamp
            analytics.click_count += 1
            analytics.clicked_links.append({
                'url': event_data.get('url'),
                'timestamp': timestamp.isoformat()
            })
        elif event == 'bounce':
            analytics.bounced_at = timestamp
            analytics.bounce_reason = event_data.get('reason', '')
        elif event == 'spamreport':
            analytics.complained_at = timestamp
        elif event == 'unsubscribe':
            analytics.unsubscribed_at = timestamp
        
        analytics.save()
    
    @staticmethod
    def get_campaign_stats(template: str, start_date: date, end_date: date) -> dict:
        """Get campaign performance statistics."""
        queryset = EmailAnalytics.objects.filter(
            template=template,
            sent_at__date__range=[start_date, end_date]
        )
        
        total = queryset.count()
        if total == 0:
            return {"error": "No data found for the specified period"}
        
        delivered = queryset.filter(delivered_at__isnull=False).count()
        opened = queryset.filter(open_count__gt=0).count()
        clicked = queryset.filter(click_count__gt=0).count()
        bounced = queryset.filter(bounced_at__isnull=False).count()
        complained = queryset.filter(complained_at__isnull=False).count()
        
        return {
            "total_sent": total,
            "delivered": delivered,
            "delivery_rate": round(delivered / total * 100, 2) if total > 0 else 0,
            "opened": opened,
            "open_rate": round(opened / delivered * 100, 2) if delivered > 0 else 0,
            "clicked": clicked,
            "click_rate": round(clicked / delivered * 100, 2) if delivered > 0 else 0,
            "bounced": bounced,
            "bounce_rate": round(bounced / total * 100, 2) if total > 0 else 0,
            "complained": complained,
            "complaint_rate": round(complained / delivered * 100, 4) if delivered > 0 else 0,
            "period": f"{start_date} to {end_date}"
        }
```

### 9.2 Bounce Handling Webhook

```python
# views/webhooks.py
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.views.decorators.http import require_http_methods
import json

@csrf_exempt
@require_http_methods(["POST"])
def sendgrid_webhook(request):
    """Handle SendGrid event webhooks."""
    try:
        events = json.loads(request.body)
        
        for event_data in events:
            event = event_data.get('event')
            email = event_data.get('email')
            
            # Process analytics
            AnalyticsService.process_webhook_event(event_data)
            
            # Handle bounces
            if event in ['bounce', 'dropped']:
                handle_bounce(email, event_data)
            
            # Handle spam complaints
            elif event == 'spamreport':
                handle_spam_complaint(email)
            
            # Handle unsubscribes
            elif event == 'unsubscribe':
                handle_unsubscribe(email)
        
        return JsonResponse({"status": "processed"})
        
    except Exception as e:
        logger.error(f"Webhook processing error: {e}")
        return JsonResponse({"error": str(e)}, status=400)

def handle_bounce(email: str, event_data: dict):
    """Handle email bounce."""
    bounce_type = event_data.get('type')  # 'bounce' or 'blocked'
    reason = event_data.get('reason', '')
    status = event_data.get('status', '')
    
    # Log bounce
    EmailBounce.objects.create(
        email=email,
        bounce_type=bounce_type,
        reason=reason,
        status=status,
        raw_data=event_data
    )
    
    # Update user profile
    UserProfile.objects.filter(email=email).update(
        email_bounced=True,
        email_bounce_reason=reason,
        email_bounced_at=timezone.now()
    )
    
    # Hard bounce - suppress email
    if bounce_type == 'bounce' and status.startswith('5'):
        EmailSuppression.objects.get_or_create(
            email=email,
            defaults={'reason': 'hard_bounce', 'source': 'webhook'}
        )

def handle_spam_complaint(email: str):
    """Handle spam complaint."""
    EmailSuppression.objects.get_or_create(
        email=email,
        defaults={'reason': 'spam_complaint', 'source': 'webhook'}
    )
    
    UserProfile.objects.filter(email=email).update(
        email_suppressed=True,
        email_suppression_reason='spam_complaint'
    )

def handle_unsubscribe(email: str):
    """Handle unsubscribe request."""
    UserProfile.objects.filter(email=email).update(
        marketing_emails=False,
        unsubscribed_at=timezone.now()
    )
```

---

## 10. Deliverability

### 10.1 Spam Score Checking

```python
# services/deliverability.py
import requests
from typing import Dict, Any

class SpamChecker:
    """Check email content for spam indicators."""
    
    SPAM_TRIGGERS = [
        'free', 'winner', 'cash', 'prize', 'urgent', 'act now',
        'limited time', 'click here', 'buy now', 'order now',
        'credit card', 'no obligation', 'risk free', 'call now',
        'act immediately', 'exclusive deal', 'special promotion'
    ]
    
    @classmethod
    def check_content(cls, subject: str, html_content: str, text_content: str) -> Dict[str, Any]:
        """Check email content for spam indicators."""
        results = {
            'score': 0,
            'issues': [],
            'recommendations': [],
            'passed': True
        }
        
        # Check subject line
        subject_lower = subject.lower()
        for trigger in cls.SPAM_TRIGGERS:
            if trigger in subject_lower:
                results['score'] += 2
                results['issues'].append(f"Subject contains spam trigger: '{trigger}'")
        
        # Check for excessive caps in subject
        caps_ratio = sum(1 for c in subject if c.isupper()) / len(subject) if subject else 0
        if caps_ratio > 0.5:
            results['score'] += 3
            results['issues'].append("Subject has too many capital letters")
        
        # Check for exclamation marks
        if subject.count('!') > 1:
            results['score'] += 1
            results['issues'].append("Subject has multiple exclamation marks")
        
        # Check HTML content
        html_lower = html_content.lower()
        for trigger in cls.SPAM_TRIGGERS:
            if trigger in html_lower:
                results['score'] += 1
        
        # Check for image-to-text ratio
        img_count = html_content.count('<img')
        text_length = len(text_content or strip_tags(html_content))
        if img_count > 0 and text_length / img_count < 100:
            results['score'] += 2
            results['issues'].append("Image-to-text ratio too high")
        
        # Check for proper unsubscribe link
        if 'unsubscribe' not in html_lower:
            results['score'] += 5
            results['issues'].append("Missing unsubscribe link")
        
        # Determine pass/fail
        if results['score'] >= 5:
            results['passed'] = False
            results['recommendations'].append("Review and revise content before sending")
        
        return results
    
    @classmethod
    def validate_before_send(cls, template_key: str, context: dict) -> bool:
        """Validate email before sending."""
        from services.template_renderer import EmailTemplateRenderer
        
        renderer = EmailTemplateRenderer()
        template = EmailTemplate.objects.get(key=template_key)
        
        html_content = renderer.render(template.file_path, context)
        text_content = strip_tags(html_content)
        
        results = cls.check_content(template.subject, html_content, text_content)
        
        if not results['passed']:
            logger.warning(f"Email failed spam check: {results['issues']}")
            
            # Alert marketing team for marketing emails
            if template.category == 'marketing':
                alert_marketing_team(template_key, results)
        
        return results['passed']
```

### 10.2 Reputation Management

```python
# services/reputation.py
from django.core.cache import cache
from datetime import datetime, timedelta

class ReputationManager:
    """Manage sender reputation across email providers."""
    
    DAILY_LIMITS = {
        'transactional': 50000,
        'marketing': 100000,
        'bulk': 500000
    }
    
    COMPLAINT_THRESHOLD = 0.001  # 0.1%
    BOUNCE_THRESHOLD = 0.05      # 5%
    
    @classmethod
    def check_limits(cls, category: str, count: int) -> bool:
        """Check if sending within daily limits."""
        today = datetime.now().strftime('%Y-%m-%d')
        cache_key = f"email_count:{category}:{today}"
        
        current_count = cache.get(cache_key, 0)
        limit = cls.DAILY_LIMITS.get(category, 10000)
        
        if current_count + count > limit:
            return False
        
        cache.set(cache_key, current_count + count, 86400)
        return True
    
    @classmethod
    def get_reputation_status(cls) -> dict:
        """Get current sender reputation status."""
        last_24h = timezone.now() - timedelta(hours=24)
        
        sent = EmailAnalytics.objects.filter(sent_at__gte=last_24h).count()
        bounced = EmailAnalytics.objects.filter(bounced_at__gte=last_24h).count()
        complained = EmailAnalytics.objects.filter(complained_at__gte=last_24h).count()
        
        bounce_rate = bounced / sent if sent > 0 else 0
        complaint_rate = complained / sent if sent > 0 else 0
        
        status = {
            'health': 'good',
            'bounce_rate': round(bounce_rate * 100, 2),
            'complaint_rate': round(complaint_rate * 100, 4),
            'warnings': []
        }
        
        if bounce_rate > cls.BOUNCE_THRESHOLD:
            status['health'] = 'poor'
            status['warnings'].append(f"Bounce rate ({status['bounce_rate']}%) exceeds threshold")
        elif bounce_rate > cls.BOUNCE_THRESHOLD * 0.5:
            status['health'] = 'fair'
            status['warnings'].append("Bounce rate elevated")
        
        if complaint_rate > cls.COMPLAINT_THRESHOLD:
            status['health'] = 'critical'
            status['warnings'].append(f"Complaint rate ({status['complaint_rate']}%) exceeds threshold")
        
        return status
    
    @classmethod
    def warmup_ip(cls, new_ip: str):
        """Implement IP warmup schedule."""
        warmup_schedule = [
            (1, 50),    # Day 1: 50 emails
            (2, 100),   # Day 2: 100 emails
            (3, 500),   # Day 3: 500 emails
            (4, 1000),  # Day 4: 1,000 emails
            (5, 5000),  # Day 5: 5,000 emails
            (6, 10000), # Day 6: 10,000 emails
            (7, 20000), # Day 7: 20,000 emails
            (8, 50000), # Day 8: 50,000 emails
        ]
        
        for day, limit in warmup_schedule:
            cache.set(f"ip_warmup:{new_ip}:{day}", limit, 86400 * 8)
```

---

## 11. Testing

### 11.1 Email Capture in Development

```python
# settings/development.py

# Email backend for development
EMAIL_BACKEND = 'services.email_backends.FileEmailBackend'
EMAIL_FILE_PATH = '/tmp/app-messages'

# Alternative: Console backend
# EMAIL_BACKEND = 'django.core.mail.backends.console.EmailBackend'

# For testing with MailHog
# EMAIL_HOST = 'localhost'
# EMAIL_PORT = 1025
```

```python
# services/email_backends.py
from django.core.mail.backends.base import BaseEmailBackend
from django.core.mail import EmailMessage
import json
import os
from datetime import datetime

class FileEmailBackend(BaseEmailBackend):
    """Email backend that writes emails to files for development."""
    
    def __init__(self, file_path=None, **kwargs):
        super().__init__(**kwargs)
        self.file_path = file_path or '/tmp/app-messages'
        os.makedirs(self.file_path, exist_ok=True)
    
    def send_messages(self, email_messages):
        """Write email messages to files."""
        count = 0
        for message in email_messages:
            self._write_message(message)
            count += 1
        return count
    
    def _write_message(self, message: EmailMessage):
        """Write single email to file."""
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S_%f')
        filename = f"email_{timestamp}.json"
        filepath = os.path.join(self.file_path, filename)
        
        data = {
            'subject': message.subject,
            'from': message.from_email,
            'to': message.to,
            'cc': message.cc,
            'bcc': message.bcc,
            'body': message.body,
            'alternatives': message.alternatives,
            'attachments': [
                {'filename': att[0], 'size': len(att[1]) if att[1] else 0}
                for att in (message.attachments or [])
            ],
            'headers': message.extra_headers,
            'timestamp': datetime.now().isoformat()
        }
        
        # Also save HTML version separately for easy viewing
        if message.alternatives:
            for content, mimetype in message.alternatives:
                if mimetype == 'text/html':
                    html_path = os.path.join(self.file_path, f"email_{timestamp}.html")
                    with open(html_path, 'w') as f:
                        f.write(content)
        
        with open(filepath, 'w') as f:
            json.dump(data, f, indent=2)
```

### 11.2 Email Preview Tools

```python
# views/preview.py
from django.shortcuts import render
from django.http import HttpResponse
from django.contrib.admin.views.decorators import staff_member_required
from services.template_renderer import EmailTemplateRenderer
from services.personalization import EmailPersonalizer

@staff_member_required
def preview_email(request, template_key: str):
    """Preview email template with test data."""
    renderer = EmailTemplateRenderer()
    
    # Test contexts for different templates
    test_contexts = {
        'welcome': {
            'user_name': 'John Doe',
            'login_url': 'https://portal.smartdairy.co.ke/login',
            'support_email': 'support@smartdairy.co.ke',
            'recipient_email': 'john@example.com',
            'unsubscribe_url': '#',
            'preferences_url': '#'
        },
        'order_confirmation': {
            'customer_name': 'Jane Smith',
            'order_id': 'ORD-2026-001234',
            'order_date': datetime.now(),
            'items': [
                {'name': 'Fresh Milk 1L', 'quantity': 10, 'price': 120},
                {'name': 'Organic Yoghurt 500g', 'quantity': 5, 'price': 180},
            ],
            'subtotal': 2100,
            'tax': 252,
            'total': 2352,
            'delivery_date': datetime.now() + timedelta(days=2),
            'tracking_url': '#',
            'recipient_email': 'jane@example.com',
            'unsubscribe_url': '#',
            'preferences_url': '#'
        },
        'password_reset': {
            'user_name': 'John Doe',
            'reset_url': 'https://portal.smartdairy.co.ke/reset?token=abc123',
            'expiry_hours': 24,
            'recipient_email': 'john@example.com',
            'unsubscribe_url': '#',
            'preferences_url': '#'
        }
    }
    
    context = test_contexts.get(template_key, {})
    
    # Allow custom context via query params
    for key, value in request.GET.items():
        context[key] = value
    
    template_path = f"transactional/{template_key}.html"
    html_content = renderer.render(template_path, context)
    
    return HttpResponse(html_content)

@staff_member_required
def email_preview_index(request):
    """List all available email templates for preview."""
    templates = [
        {'key': 'welcome', 'name': 'Welcome Email', 'category': 'Transactional'},
        {'key': 'order_confirmation', 'name': 'Order Confirmation', 'category': 'Transactional'},
        {'key': 'password_reset', 'name': 'Password Reset', 'category': 'Transactional'},
        {'key': 'delivery_notification', 'name': 'Delivery Notification', 'category': 'Transactional'},
        {'key': 'newsletter', 'name': 'Weekly Newsletter', 'category': 'Marketing'},
        {'key': 'promotional', 'name': 'Promotional Email', 'category': 'Marketing'},
    ]
    
    return render(request, 'admin/email_preview_index.html', {
        'templates': templates
    })
```

### 11.3 Email Testing Checklist

| Test Category | Test Case | Expected Result |
|---------------|-----------|-----------------|
| **Rendering** | Test in Gmail | Correct display |
| **Rendering** | Test in Outlook | Correct display |
| **Rendering** | Test in Apple Mail | Correct display |
| **Rendering** | Test on mobile | Responsive layout |
| **Content** | Verify links work | All links functional |
| **Content** | Check images load | Images display with alt text |
| **Personalization** | Merge fields populate | {{user_name}} shows correctly |
| **Deliverability** | Spam score < 3 | Passes spam filters |
| **Accessibility** | Screen reader test | Content accessible |
| **Performance** | Load time < 3s | Email renders quickly |

---

## 12. Appendices

### Appendix A: Template Examples

#### A.1 Welcome Email Template

```html
{% extends "email/base.html" %}

{% block title %}Welcome to Smart Dairy!{% endblock %}

{% block content %}
<h1 class="brand-primary">Welcome to Smart Dairy, {{ user_name }}!</h1>

<p>Thank you for joining Kenya's leading dairy marketplace. We're excited to help you streamline your dairy business operations.</p>

<h2 class="brand-secondary">Get Started in 3 Easy Steps:</h2>

<table width="100%" style="margin: 20px 0;">
    <tr>
        <td style="padding: 15px; background-color: #f9f9f9; border-left: 4px solid #1E5631;">
            <strong>1. Complete Your Profile</strong><br>
            Add your farm details and preferences.
        </td>
    </tr>
    <tr><td height="10"></td></tr>
    <tr>
        <td style="padding: 15px; background-color: #f9f9f9; border-left: 4px solid #1E5631;">
            <strong>2. Browse Products</strong><br>
            Explore our catalog of quality dairy products and supplies.
        </td>
    </tr>
    <tr><td height="10"></td></tr>
    <tr>
        <td style="padding: 15px; background-color: #f9f9f9; border-left: 4px solid #1E5631;">
            <strong>3. Place Your First Order</strong><br>
            Enjoy fast delivery and competitive prices.
        </td>
    </tr>
</table>

<p style="text-align: center; margin: 30px 0;">
    <a href="{{ login_url }}" class="btn">Access Your Account</a>
</p>

<p>If you need any assistance, our support team is here to help at <a href="mailto:{{ support_email }}">{{ support_email }}</a>.</p>

<p>Best regards,<br><strong>The Smart Dairy Team</strong></p>
{% endblock %}
```

#### A.2 Password Reset Template

```html
{% extends "email/base.html" %}

{% block title %}Password Reset Request{% endblock %}

{% block content %}
<h1 class="brand-primary">Password Reset</h1>

<p>Hi {{ user_name }},</p>

<p>We received a request to reset your password for your Smart Dairy account. Click the button below to set a new password:</p>

<p style="text-align: center; margin: 30px 0;">
    <a href="{{ reset_url }}" class="btn">Reset Password</a>
</p>

<p>Or copy and paste this link into your browser:</p>
<p style="word-break: break-all; background-color: #f4f4f4; padding: 10px;">
    {{ reset_url }}
</p>

<p><strong>This link will expire in {{ expiry_hours }} hours.</strong></p>

<p>If you didn't request this password reset, please ignore this email or contact support if you have concerns.</p>

<p>Best regards,<br><strong>The Smart Dairy Team</strong></p>

<hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

<p style="font-size: 12px; color: #666;">
    For security reasons, this password reset link can only be used once and expires after {{ expiry_hours }} hours.
</p>
{% endblock %}
```

### Appendix B: DNS Records Reference

#### B.1 Complete DNS Configuration

```
; SPF Record
smartdairy.co.ke.    IN    TXT    "v=spf1 include:sendgrid.net include:amazonses.com -all"

; DKIM Records (SendGrid)
s1._domainkey.smartdairy.co.ke.    IN    TXT    "k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC..."
s2._domainkey.smartdairy.co.ke.    IN    TXT    "k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC..."

; DMARC Record
_dmarc.smartdairy.co.ke.    IN    TXT    "v=DMARC1; p=quarantine; rua=mailto:dmarc@smartdairy.co.ke; pct=100"

; Custom Return-Path (SendGrid)
[rpcode].smartdairy.co.ke.    IN    MX    10    [rpcode].smtp.sendgrid.net

; Subdomain Configuration
mail.smartdairy.co.ke.      IN    CNAME    sendgrid.net
newsletter.smartdairy.co.ke. IN    CNAME    sendgrid.net
```

### Appendix C: Environment Configuration

```python
# .env.example

# SendGrid Configuration
SENDGRID_API_KEY=SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
SENDGRID_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

# AWS SES Configuration (fallback)
AWS_ACCESS_KEY_ID=AKIA...
AWS_SECRET_ACCESS_KEY=...
AWS_REGION=us-east-1

# Email Settings
DEFAULT_FROM_EMAIL=noreply@smartdairy.co.ke
DEFAULT_FROM_NAME="Smart Dairy Ltd."
SUPPORT_EMAIL=support@smartdairy.co.ke

# Development
EMAIL_BACKEND=services.email_backends.FileEmailBackend
EMAIL_FILE_PATH=/tmp/app-messages

# Production
# EMAIL_BACKEND=django.core.mail.backends.smtp.EmailBackend
# EMAIL_HOST=smtp.sendgrid.net
# EMAIL_PORT=587
# EMAIL_USE_TLS=True
# EMAIL_HOST_USER=apikey
# EMAIL_HOST_PASSWORD=${SENDGRID_API_KEY}
```

### Appendix D: API Quick Reference

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/email/send` | POST | Send single email |
| `/api/v1/email/bulk` | POST | Send bulk emails |
| `/api/v1/email/templates` | GET | List templates |
| `/api/v1/email/templates/{id}` | GET | Get template |
| `/api/v1/analytics/campaigns` | GET | Campaign statistics |
| `/api/v1/analytics/reputation` | GET | Sender reputation |
| `/webhooks/sendgrid` | POST | SendGrid events |
| `/admin/email/preview/{key}` | GET | Preview template |

---

## Document Control

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Integration Engineer | _____________ | ________ |
| Reviewer | CTO | _____________ | ________ |
| Approver | Tech Lead | _____________ | ________ |

### Distribution

| Version | Distributed To | Date |
|---------|---------------|------|
| 1.0 | Development Team | 2026-01-31 |
| 1.0 | DevOps Team | 2026-01-31 |
| 1.0 | QA Team | 2026-01-31 |

---

*End of Document*

---

**Smart Dairy Ltd.**  
Innovating Dairy Farming in Kenya  
📧 info@smartdairy.co.ke | 🌐 www.smartdairy.co.ke
