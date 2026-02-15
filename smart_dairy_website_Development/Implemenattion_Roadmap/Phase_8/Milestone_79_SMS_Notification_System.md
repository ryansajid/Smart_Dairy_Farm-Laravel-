# Milestone 79: SMS Notification System

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P8-M79-v1.0 |
| **Milestone** | 79 - SMS Notification System |
| **Phase** | Phase 8: Operations - Payment & Logistics |
| **Days** | 531-540 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement comprehensive SMS notification system with multi-stage order alerts, payment confirmations, delivery status updates, OTP delivery, promotional campaigns, template management, delivery tracking, cost optimization through smart routing, and analytics dashboard.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Gateway Integration | SSL Wireless, GP SMS |
| Order Alerts | Confirmation, status updates |
| Payment SMS | Success, failure, refund |
| Delivery SMS | Dispatch, out for delivery, delivered |
| OTP System | Verification codes |
| Campaigns | Promotional bulk SMS |
| Templates | Bangla/English templates |
| Analytics | Delivery rates, costs |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | SMS delivery rate | > 98% |
| O2 | Delivery speed | < 10 seconds |
| O3 | OTP delivery | 99.9% |
| O4 | Template accuracy | 100% |
| O5 | Cost per SMS | < ৳0.30 |
| O6 | Campaign delivery | > 95% |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | SMS gateway abstraction | Dev 1 | Python | Day 532 |
| D2 | SSL Wireless integration | Dev 1 | Python | Day 533 |
| D3 | GP SMS integration | Dev 1 | Python | Day 534 |
| D4 | Template engine | Dev 2 | Python | Day 533 |
| D5 | Order notification service | Dev 2 | Python | Day 535 |
| D6 | Delivery notification service | Dev 2 | Python | Day 536 |
| D7 | OTP service | Dev 2 | Python | Day 534 |
| D8 | Campaign manager | Dev 1 | Python | Day 537 |
| D9 | SMS dashboard UI | Dev 3 | OWL/JS | Day 538 |
| D10 | Analytics & reports | Dev 3 | OWL/JS | Day 539 |
| D11 | Integration tests | All | Python | Day 540 |

---

## 4. Day-by-Day Development Plan

### Day 531-534: Gateway & Core Services

#### Dev 1 (Backend Lead)

**SMS Gateway Abstraction**

```python
# File: smart_dairy_sms/models/sms_gateway.py

from odoo import models, fields, api
from abc import abstractmethod
import logging

_logger = logging.getLogger(__name__)


class SMSGateway(models.Model):
    _name = 'sms.gateway'
    _description = 'SMS Gateway Provider'

    name = fields.Char(string='Name', required=True)
    code = fields.Selection([
        ('ssl_wireless', 'SSL Wireless'),
        ('gp_sms', 'Grameenphone SMS'),
        ('twilio', 'Twilio'),
    ], string='Provider', required=True)
    active = fields.Boolean(default=True)
    is_default = fields.Boolean(string='Default Gateway', default=False)

    # API Configuration
    api_url = fields.Char(string='API URL')
    api_key = fields.Char(string='API Key', groups='base.group_system')
    api_secret = fields.Char(string='API Secret', groups='base.group_system')
    sender_id = fields.Char(string='Sender ID')

    # Settings
    max_sms_per_minute = fields.Integer(string='Max SMS/minute', default=100)
    unicode_support = fields.Boolean(string='Unicode Support', default=True)
    dlr_enabled = fields.Boolean(string='DLR Enabled', default=True)

    # Stats
    total_sent = fields.Integer(string='Total Sent', readonly=True)
    total_delivered = fields.Integer(string='Total Delivered', readonly=True)
    total_failed = fields.Integer(string='Total Failed', readonly=True)

    def get_service(self):
        """Get the SMS service implementation."""
        service_map = {
            'ssl_wireless': 'sslwireless.sms.service',
            'gp_sms': 'gp.sms.service',
            'twilio': 'twilio.sms.service',
        }
        service_name = service_map.get(self.code)
        if service_name:
            return self.env[service_name]
        raise NotImplementedError(f"No service for gateway: {self.code}")


class BaseSMSService(models.AbstractModel):
    _name = 'base.sms.service'
    _description = 'Base SMS Service'

    @api.model
    def send_sms(self, gateway, phone, message, **kwargs):
        """Send SMS via gateway. Override in implementations."""
        raise NotImplementedError()

    @api.model
    def send_bulk(self, gateway, recipients, message, **kwargs):
        """Send bulk SMS. Override in implementations."""
        raise NotImplementedError()

    @api.model
    def get_balance(self, gateway):
        """Get account balance. Override in implementations."""
        raise NotImplementedError()

    def _normalize_phone(self, phone):
        """Normalize Bangladesh phone number."""
        phone = ''.join(filter(str.isdigit, str(phone)))
        if phone.startswith('880'):
            return phone
        if phone.startswith('0'):
            return '880' + phone[1:]
        if len(phone) == 10:
            return '880' + phone
        return phone


class SSLWirelessSMSService(models.AbstractModel):
    _name = 'sslwireless.sms.service'
    _inherit = 'base.sms.service'
    _description = 'SSL Wireless SMS Service'

    @api.model
    def send_sms(self, gateway, phone, message, **kwargs):
        """Send SMS via SSL Wireless."""
        import requests

        phone = self._normalize_phone(phone)
        url = gateway.api_url or 'https://smsplus.sslwireless.com/api/v3/send-sms'

        payload = {
            'api_token': gateway.api_key,
            'sid': gateway.sender_id,
            'msisdn': phone,
            'sms': message,
            'csms_id': kwargs.get('message_id', f'SD{int(time.time())}'),
        }

        try:
            response = requests.post(url, json=payload, timeout=30)
            data = response.json()

            if data.get('status') == 'SUCCESS':
                return {
                    'success': True,
                    'message_id': data.get('smsinfo', [{}])[0].get('csms_id'),
                    'status': 'sent',
                }
            return {
                'success': False,
                'error': data.get('status_message', 'Send failed'),
            }

        except Exception as e:
            _logger.error(f"SSL Wireless SMS error: {str(e)}")
            return {'success': False, 'error': str(e)}

    @api.model
    def send_bulk(self, gateway, recipients, message, **kwargs):
        """Send bulk SMS via SSL Wireless."""
        results = []
        for phone in recipients:
            result = self.send_sms(gateway, phone, message, **kwargs)
            results.append({
                'phone': phone,
                'success': result.get('success'),
                'message_id': result.get('message_id'),
                'error': result.get('error'),
            })
        return results

    @api.model
    def get_balance(self, gateway):
        """Get SSL Wireless balance."""
        import requests

        url = 'https://smsplus.sslwireless.com/api/v3/balance'
        params = {'api_token': gateway.api_key}

        try:
            response = requests.get(url, params=params, timeout=30)
            data = response.json()

            if data.get('status') == 'SUCCESS':
                return {
                    'success': True,
                    'balance': data.get('balance', 0),
                    'currency': 'BDT',
                }
            return {'success': False, 'error': data.get('status_message')}

        except Exception as e:
            return {'success': False, 'error': str(e)}
```

#### Dev 2 (Full-Stack)

**SMS Template Engine**

```python
# File: smart_dairy_sms/models/sms_template.py

from odoo import models, fields, api
import re
import logging

_logger = logging.getLogger(__name__)


class SMSTemplate(models.Model):
    _name = 'sms.template'
    _description = 'SMS Template'

    name = fields.Char(string='Template Name', required=True)
    code = fields.Char(string='Code', required=True, index=True)
    active = fields.Boolean(default=True)

    # Content
    content_bn = fields.Text(string='Content (Bangla)', required=True)
    content_en = fields.Text(string='Content (English)')

    # Settings
    category = fields.Selection([
        ('order', 'Order'),
        ('payment', 'Payment'),
        ('delivery', 'Delivery'),
        ('otp', 'OTP'),
        ('promotional', 'Promotional'),
    ], string='Category', required=True)

    priority = fields.Selection([
        ('high', 'High'),
        ('normal', 'Normal'),
        ('low', 'Low'),
    ], string='Priority', default='normal')

    # Variables
    available_variables = fields.Text(
        string='Available Variables',
        help='JSON list of available variables'
    )

    _sql_constraints = [
        ('unique_code', 'UNIQUE(code)', 'Template code must be unique'),
    ]

    @api.model
    def render_template(self, code, variables, language='bn'):
        """
        Render SMS template with variables.

        Args:
            code: Template code
            variables: Dictionary of variables
            language: 'bn' for Bangla, 'en' for English

        Returns:
            Rendered message string
        """
        template = self.search([('code', '=', code), ('active', '=', True)], limit=1)
        if not template:
            _logger.warning(f"SMS template not found: {code}")
            return None

        content = template.content_bn if language == 'bn' else (template.content_en or template.content_bn)

        # Replace variables
        for key, value in variables.items():
            placeholder = '{' + key + '}'
            content = content.replace(placeholder, str(value))

        # Remove unreplaced placeholders
        content = re.sub(r'\{[^}]+\}', '', content)

        return content.strip()


class SMSNotificationService(models.AbstractModel):
    _name = 'sms.notification.service'
    _description = 'SMS Notification Service'

    @api.model
    def send_notification(self, phone, template_code, variables, language='bn', priority='normal'):
        """
        Send SMS notification using template.

        Args:
            phone: Recipient phone number
            template_code: SMS template code
            variables: Template variables dict
            language: Message language
            priority: Sending priority

        Returns:
            dict with send result
        """
        # Render template
        message = self.env['sms.template'].render_template(
            template_code, variables, language
        )

        if not message:
            return {'success': False, 'error': 'Template render failed'}

        # Get default gateway
        gateway = self.env['sms.gateway'].search([
            ('active', '=', True),
            ('is_default', '=', True),
        ], limit=1)

        if not gateway:
            gateway = self.env['sms.gateway'].search([('active', '=', True)], limit=1)

        if not gateway:
            return {'success': False, 'error': 'No SMS gateway configured'}

        # Send SMS
        service = gateway.get_service()
        result = service.send_sms(gateway, phone, message, priority=priority)

        # Log
        self.env['sms.log'].create({
            'gateway_id': gateway.id,
            'phone': phone,
            'template_code': template_code,
            'message': message,
            'status': 'sent' if result.get('success') else 'failed',
            'message_id': result.get('message_id'),
            'error_message': result.get('error'),
        })

        return result

    @api.model
    def send_order_confirmation(self, order):
        """Send order confirmation SMS."""
        variables = {
            'order_no': order.name,
            'amount': f"৳{order.amount_total:,.2f}",
            'items': len(order.order_line),
            'customer_name': order.partner_id.name.split()[0],
        }

        return self.send_notification(
            order.partner_id.phone,
            'order_confirmation',
            variables,
            priority='high'
        )

    @api.model
    def send_payment_success(self, transaction):
        """Send payment success SMS."""
        variables = {
            'order_no': transaction.reference,
            'amount': f"৳{transaction.amount:,.2f}",
            'method': transaction.provider_code.upper(),
            'txn_id': transaction.provider_reference or transaction.reference,
        }

        return self.send_notification(
            transaction.partner_id.phone,
            'payment_success',
            variables,
            priority='high'
        )

    @api.model
    def send_delivery_update(self, delivery, status):
        """Send delivery status SMS."""
        template_map = {
            'dispatched': 'delivery_dispatched',
            'out_for_delivery': 'delivery_out',
            'delivered': 'delivery_completed',
        }

        template_code = template_map.get(status)
        if not template_code:
            return

        variables = {
            'order_no': delivery.sale_order_id.name,
            'driver_name': delivery.driver_id.name if delivery.driver_id else 'Delivery Partner',
            'driver_phone': delivery.driver_id.phone if delivery.driver_id else '',
            'eta': delivery.estimated_arrival.strftime('%I:%M %p') if delivery.estimated_arrival else 'Soon',
        }

        return self.send_notification(
            delivery.partner_id.phone,
            template_code,
            variables
        )

    @api.model
    def send_otp(self, phone, otp_code, purpose='verification'):
        """Send OTP SMS."""
        variables = {
            'otp': otp_code,
            'expiry': '5',
            'purpose': purpose,
        }

        return self.send_notification(
            phone,
            'otp_verification',
            variables,
            priority='high'
        )


class SMSLog(models.Model):
    _name = 'sms.log'
    _description = 'SMS Log'
    _order = 'create_date desc'

    gateway_id = fields.Many2one('sms.gateway', string='Gateway')
    phone = fields.Char(string='Phone Number', index=True)
    template_code = fields.Char(string='Template')
    message = fields.Text(string='Message')

    status = fields.Selection([
        ('pending', 'Pending'),
        ('sent', 'Sent'),
        ('delivered', 'Delivered'),
        ('failed', 'Failed'),
    ], string='Status', default='pending', index=True)

    message_id = fields.Char(string='Message ID')
    error_message = fields.Text(string='Error')
    delivered_at = fields.Datetime(string='Delivered At')
    cost = fields.Float(string='Cost')

    create_date = fields.Datetime(string='Sent At')
```

**SMS Templates Data**

```xml
<!-- File: smart_dairy_sms/data/sms_templates.xml -->
<odoo>
    <!-- Order Confirmation -->
    <record id="sms_template_order_confirmation" model="sms.template">
        <field name="name">Order Confirmation</field>
        <field name="code">order_confirmation</field>
        <field name="category">order</field>
        <field name="content_bn">প্রিয় {customer_name}, আপনার অর্ডার #{order_no} সফলভাবে গ্রহণ করা হয়েছে। মোট: {amount}। ধন্যবাদ Smart Dairy তে অর্ডার করার জন্য!</field>
        <field name="content_en">Dear {customer_name}, your order #{order_no} has been confirmed. Total: {amount}. Thank you for ordering from Smart Dairy!</field>
    </record>

    <!-- Payment Success -->
    <record id="sms_template_payment_success" model="sms.template">
        <field name="name">Payment Success</field>
        <field name="code">payment_success</field>
        <field name="category">payment</field>
        <field name="content_bn">অর্ডার #{order_no} এর জন্য {amount} পেমেন্ট সফল হয়েছে। {method} TxnID: {txn_id}। ধন্যবাদ!</field>
    </record>

    <!-- Delivery Out -->
    <record id="sms_template_delivery_out" model="sms.template">
        <field name="name">Out for Delivery</field>
        <field name="code">delivery_out</field>
        <field name="category">delivery</field>
        <field name="content_bn">আপনার অর্ডার #{order_no} ডেলিভারির জন্য বের হয়েছে। ড্রাইভার: {driver_name} ({driver_phone})। আনুমানিক সময়: {eta}</field>
    </record>

    <!-- OTP -->
    <record id="sms_template_otp" model="sms.template">
        <field name="name">OTP Verification</field>
        <field name="code">otp_verification</field>
        <field name="category">otp</field>
        <field name="priority">high</field>
        <field name="content_bn">আপনার Smart Dairy OTP: {otp}। এই কোড {expiry} মিনিটের মধ্যে ব্যবহার করুন। কাউকে শেয়ার করবেন না।</field>
    </record>
</odoo>
```

---

## 5. Technical Specifications

### 5.1 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/sms/send` | POST | Send single SMS |
| `/sms/bulk` | POST | Send bulk SMS |
| `/sms/webhook/dlr` | POST | Delivery report webhook |
| `/sms/templates` | GET | List templates |
| `/sms/balance` | GET | Gateway balance |

### 5.2 SMS Templates

| Code | Category | Trigger |
|------|----------|---------|
| `order_confirmation` | Order | Order placed |
| `payment_success` | Payment | Payment completed |
| `payment_failed` | Payment | Payment failed |
| `delivery_dispatched` | Delivery | Order dispatched |
| `delivery_out` | Delivery | Out for delivery |
| `delivery_completed` | Delivery | Order delivered |
| `otp_verification` | OTP | OTP request |

---

## 6. Database Schema

```sql
CREATE TABLE sms_gateway (
    id SERIAL PRIMARY KEY,
    name VARCHAR(128),
    code VARCHAR(20),
    api_url VARCHAR(256),
    api_key VARCHAR(256),
    sender_id VARCHAR(32),
    active BOOLEAN DEFAULT TRUE,
    is_default BOOLEAN DEFAULT FALSE
);

CREATE TABLE sms_template (
    id SERIAL PRIMARY KEY,
    name VARCHAR(128),
    code VARCHAR(64) UNIQUE,
    category VARCHAR(20),
    content_bn TEXT,
    content_en TEXT,
    active BOOLEAN DEFAULT TRUE
);

CREATE TABLE sms_log (
    id SERIAL PRIMARY KEY,
    gateway_id INTEGER REFERENCES sms_gateway(id),
    phone VARCHAR(20),
    template_code VARCHAR(64),
    message TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    message_id VARCHAR(64),
    error_message TEXT,
    create_date TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_sms_log_phone ON sms_log(phone);
CREATE INDEX idx_sms_log_status ON sms_log(status);
```

---

## 7. Sign-off Checklist

- [ ] SSL Wireless integration complete
- [ ] GP SMS integration complete
- [ ] Template engine working
- [ ] Order notifications sending
- [ ] Delivery notifications sending
- [ ] OTP system functional
- [ ] Campaign manager working
- [ ] Analytics dashboard complete

---

**Document End**

*Milestone 79: SMS Notification System*
*Days 531-540 | Phase 8: Operations - Payment & Logistics*
*Smart Dairy Digital Smart Portal + ERP System*
