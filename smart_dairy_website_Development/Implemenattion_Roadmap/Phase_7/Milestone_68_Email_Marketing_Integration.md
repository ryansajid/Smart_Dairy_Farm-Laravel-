# Milestone 68: Email Marketing Integration

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P7-M68-v1.0 |
| **Milestone** | 68 - Email Marketing Integration |
| **Phase** | Phase 7: B2C E-Commerce Core |
| **Days** | 421-430 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement comprehensive email marketing automation with newsletter subscription, abandoned cart recovery, post-purchase sequences, promotional campaigns, and integration with SendGrid/AWS SES for reliable email delivery.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Newsletter | Double opt-in subscription |
| Abandoned Cart | Recovery email sequence |
| Post-Purchase | Order confirmation, review request |
| Campaigns | Promotional email management |
| Templates | Responsive email templates |
| Unsubscribe | GDPR-compliant opt-out |
| Analytics | Open rates, click tracking |
| A/B Testing | Subject line testing |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Newsletter subscription | Double opt-in rate > 90% |
| O2 | Abandoned cart recovery | Recovery rate > 15% |
| O3 | Email delivery | Delivery rate > 98% |
| O4 | Open rates | Average > 25% |
| O5 | Click-through rates | Average > 5% |
| O6 | Unsubscribe process | < 3 clicks to unsubscribe |
| O7 | Template rendering | Mobile-friendly 100% |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Due Day |
|---|-------------|-------|---------|
| D1 | Newsletter subscription model | Dev 1 | Day 423 |
| D2 | Abandoned cart service | Dev 1 | Day 425 |
| D3 | Email campaign model | Dev 1 | Day 426 |
| D4 | SendGrid/SES integration | Dev 2 | Day 424 |
| D5 | Email template engine | Dev 2 | Day 426 |
| D6 | Subscription widget UI | Dev 3 | Day 424 |
| D7 | Campaign dashboard | Dev 3 | Day 427 |
| D8 | A/B testing framework | Dev 2 | Day 428 |
| D9 | Analytics dashboard | Dev 3 | Day 429 |
| D10 | Integration tests | All | Day 430 |

---

## 4. Requirement Traceability

### 4.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| RFP-B2C-4.6.1-001 | Newsletter subscription | D1, D6 |
| RFP-B2C-4.6.1-002 | Abandoned cart emails | D2 |
| RFP-B2C-4.6.2-001 | Email campaigns | D3, D7 |
| RFP-B2C-4.6.2-002 | Email templates | D5 |
| RFP-B2C-4.6.3-001 | Email analytics | D9 |
| RFP-B2C-4.6.3-002 | Unsubscribe management | D1 |

---

## 5. Day-by-Day Development Plan

### Day 421-424: Newsletter & Email Service

#### Dev 1 (Backend Lead)

**Newsletter Subscription Model**

```python
# File: smart_dairy_ecommerce/models/newsletter.py

from odoo import models, fields, api
import hashlib
import secrets

class NewsletterSubscription(models.Model):
    _name = 'newsletter.subscription'
    _description = 'Newsletter Subscription'

    email = fields.Char(
        string='Email',
        required=True,
        index=True
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer'
    )

    # Status
    state = fields.Selection([
        ('pending', 'Pending Confirmation'),
        ('confirmed', 'Confirmed'),
        ('unsubscribed', 'Unsubscribed'),
    ], string='Status', default='pending')

    # Confirmation
    confirmation_token = fields.Char(
        string='Confirmation Token',
        default=lambda self: secrets.token_urlsafe(32)
    )
    confirmed_at = fields.Datetime()
    confirmation_ip = fields.Char()

    # Unsubscribe
    unsubscribe_token = fields.Char(
        default=lambda self: secrets.token_urlsafe(32)
    )
    unsubscribed_at = fields.Datetime()
    unsubscribe_reason = fields.Text()

    # Preferences
    frequency = fields.Selection([
        ('daily', 'Daily'),
        ('weekly', 'Weekly'),
        ('monthly', 'Monthly'),
    ], default='weekly')

    categories = fields.Many2many(
        'product.category',
        string='Interested Categories'
    )

    _sql_constraints = [
        ('unique_email', 'UNIQUE(email)', 'Email already subscribed'),
    ]

    @api.model
    def subscribe(self, email, **kwargs):
        existing = self.search([('email', '=', email)], limit=1)

        if existing:
            if existing.state == 'confirmed':
                return {'error': 'Already subscribed'}
            elif existing.state == 'unsubscribed':
                existing.write({
                    'state': 'pending',
                    'confirmation_token': secrets.token_urlsafe(32),
                })
                self._send_confirmation_email(existing)
                return {'success': True, 'message': 'Please confirm your email'}
            else:
                self._send_confirmation_email(existing)
                return {'success': True, 'message': 'Confirmation email resent'}

        subscription = self.create({
            'email': email,
            'partner_id': kwargs.get('partner_id'),
        })
        self._send_confirmation_email(subscription)
        return {'success': True, 'message': 'Please check your email to confirm'}

    def confirm(self, token):
        subscription = self.search([
            ('confirmation_token', '=', token),
            ('state', '=', 'pending')
        ], limit=1)

        if not subscription:
            return {'error': 'Invalid or expired token'}

        subscription.write({
            'state': 'confirmed',
            'confirmed_at': fields.Datetime.now(),
        })
        return {'success': True}

    def unsubscribe(self, token, reason=None):
        subscription = self.search([
            ('unsubscribe_token', '=', token)
        ], limit=1)

        if not subscription:
            return {'error': 'Invalid token'}

        subscription.write({
            'state': 'unsubscribed',
            'unsubscribed_at': fields.Datetime.now(),
            'unsubscribe_reason': reason,
        })
        return {'success': True}

    def _send_confirmation_email(self, subscription):
        template = self.env.ref('smart_dairy_ecommerce.email_newsletter_confirm')
        if template:
            template.send_mail(subscription.id, force_send=True)
```

**Abandoned Cart Recovery**

```python
# File: smart_dairy_ecommerce/models/abandoned_cart.py

from odoo import models, fields, api
from datetime import datetime, timedelta

class AbandonedCartRecovery(models.Model):
    _name = 'abandoned.cart.recovery'
    _description = 'Abandoned Cart Recovery'

    cart_session_id = fields.Many2one(
        'cart.session',
        string='Cart Session',
        required=True
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer'
    )
    email = fields.Char(string='Email')

    cart_value = fields.Monetary(
        string='Cart Value',
        currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    # Email sequence
    email_1_sent = fields.Boolean(default=False)
    email_1_sent_at = fields.Datetime()
    email_2_sent = fields.Boolean(default=False)
    email_2_sent_at = fields.Datetime()
    email_3_sent = fields.Boolean(default=False)
    email_3_sent_at = fields.Datetime()

    # Recovery
    recovered = fields.Boolean(default=False)
    recovered_order_id = fields.Many2one('sale.order')
    recovered_at = fields.Datetime()

    @api.model
    def process_abandoned_carts(self):
        """Cron job to send abandoned cart emails"""
        now = datetime.now()

        # Email 1: 1 hour after abandonment
        hour_ago = now - timedelta(hours=1)
        carts_1h = self.search([
            ('email_1_sent', '=', False),
            ('recovered', '=', False),
            ('cart_session_id.last_activity', '<=', hour_ago),
            ('cart_session_id.last_activity', '>=', hour_ago - timedelta(hours=1)),
        ])
        for cart in carts_1h:
            self._send_recovery_email(cart, 1)

        # Email 2: 24 hours after abandonment
        day_ago = now - timedelta(hours=24)
        carts_24h = self.search([
            ('email_1_sent', '=', True),
            ('email_2_sent', '=', False),
            ('recovered', '=', False),
            ('cart_session_id.last_activity', '<=', day_ago),
        ])
        for cart in carts_24h:
            self._send_recovery_email(cart, 2)

        # Email 3: 72 hours with discount
        three_days_ago = now - timedelta(hours=72)
        carts_72h = self.search([
            ('email_2_sent', '=', True),
            ('email_3_sent', '=', False),
            ('recovered', '=', False),
            ('cart_session_id.last_activity', '<=', three_days_ago),
        ])
        for cart in carts_72h:
            self._send_recovery_email(cart, 3)

    def _send_recovery_email(self, cart, sequence):
        template_map = {
            1: 'smart_dairy_ecommerce.email_abandoned_cart_1',
            2: 'smart_dairy_ecommerce.email_abandoned_cart_2',
            3: 'smart_dairy_ecommerce.email_abandoned_cart_3',
        }
        template = self.env.ref(template_map.get(sequence))
        if template:
            template.send_mail(cart.id, force_send=True)
            cart.write({
                f'email_{sequence}_sent': True,
                f'email_{sequence}_sent_at': fields.Datetime.now(),
            })
```

#### Dev 2 (Full-Stack)

**SendGrid Integration**

```python
# File: smart_dairy_ecommerce/services/email_service.py

from odoo import models, api
import requests
import json

class EmailService(models.AbstractModel):
    _name = 'email.service'
    _description = 'Email Delivery Service'

    @api.model
    def send_email(self, to, subject, html_content, **kwargs):
        provider = self.env['ir.config_parameter'].sudo().get_param(
            'email.provider', 'sendgrid'
        )

        if provider == 'sendgrid':
            return self._send_sendgrid(to, subject, html_content, **kwargs)
        elif provider == 'ses':
            return self._send_ses(to, subject, html_content, **kwargs)
        else:
            return self._send_smtp(to, subject, html_content, **kwargs)

    def _send_sendgrid(self, to, subject, html_content, **kwargs):
        api_key = self.env['ir.config_parameter'].sudo().get_param(
            'sendgrid.api_key'
        )
        from_email = self.env['ir.config_parameter'].sudo().get_param(
            'email.from_address', 'noreply@smartdairy.com'
        )

        data = {
            'personalizations': [{
                'to': [{'email': to}],
                'subject': subject,
            }],
            'from': {'email': from_email, 'name': 'Smart Dairy'},
            'content': [{'type': 'text/html', 'value': html_content}],
        }

        # Add tracking
        data['tracking_settings'] = {
            'click_tracking': {'enable': True},
            'open_tracking': {'enable': True},
        }

        response = requests.post(
            'https://api.sendgrid.com/v3/mail/send',
            headers={
                'Authorization': f'Bearer {api_key}',
                'Content-Type': 'application/json',
            },
            data=json.dumps(data)
        )

        return response.status_code in [200, 202]

    def _send_ses(self, to, subject, html_content, **kwargs):
        import boto3

        config = self.env['ir.config_parameter'].sudo()
        client = boto3.client(
            'ses',
            region_name=config.get_param('aws.region', 'ap-south-1'),
            aws_access_key_id=config.get_param('aws.access_key'),
            aws_secret_access_key=config.get_param('aws.secret_key'),
        )

        response = client.send_email(
            Source=config.get_param('email.from_address'),
            Destination={'ToAddresses': [to]},
            Message={
                'Subject': {'Data': subject},
                'Body': {'Html': {'Data': html_content}},
            }
        )

        return response['ResponseMetadata']['HTTPStatusCode'] == 200
```

#### Dev 3 (Frontend Lead)

**Newsletter Widget**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/newsletter/newsletter_widget.js

import { Component, useState } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class NewsletterWidget extends Component {
    static template = "smart_dairy_ecommerce.NewsletterWidget";

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            email: '',
            submitting: false,
            submitted: false,
        });
    }

    async subscribe() {
        if (!this.state.email || !this.validateEmail(this.state.email)) {
            this.notification.add("Please enter a valid email", { type: "warning" });
            return;
        }

        this.state.submitting = true;

        try {
            const result = await this.rpc('/api/v1/newsletter/subscribe', {
                email: this.state.email,
            });

            if (result.success) {
                this.state.submitted = true;
                this.notification.add(result.message, { type: "success" });
            } else {
                this.notification.add(result.error, { type: "danger" });
            }
        } catch (error) {
            this.notification.add("Subscription failed", { type: "danger" });
        } finally {
            this.state.submitting = false;
        }
    }

    validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
}
```

---

### Day 425-430: Campaigns, Analytics & Testing

The remaining days cover:
- Email campaign model and scheduling
- A/B testing framework
- Analytics tracking (opens, clicks)
- Campaign dashboard UI
- Email template editor
- Integration testing

---

## 6. Technical Specifications

### 6.1 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/newsletter/subscribe` | POST | Subscribe to newsletter |
| `/api/v1/newsletter/confirm/<token>` | GET | Confirm subscription |
| `/api/v1/newsletter/unsubscribe/<token>` | GET | Unsubscribe |
| `/api/v1/email/track/open/<id>` | GET | Track email open |
| `/api/v1/email/track/click/<id>` | GET | Track link click |

### 6.2 Email Templates

| Template | Trigger | Content |
|----------|---------|---------|
| Newsletter Confirm | Subscription | Double opt-in |
| Abandoned Cart 1 | 1 hour | Reminder |
| Abandoned Cart 2 | 24 hours | Urgency |
| Abandoned Cart 3 | 72 hours | Discount offer |
| Order Confirmation | Order placed | Order details |
| Review Request | 7 days post-delivery | Review prompt |

---

## 7. Sign-off Checklist

- [ ] Newsletter subscription works
- [ ] Double opt-in confirmed
- [ ] Abandoned cart emails sent
- [ ] Recovery tracking works
- [ ] SendGrid/SES integration complete
- [ ] Unsubscribe functional
- [ ] Email templates responsive
- [ ] Analytics tracking accurate
- [ ] A/B testing operational

---

**Document End**

*Last Updated: 2025-01-15*
*Version: 1.0*
