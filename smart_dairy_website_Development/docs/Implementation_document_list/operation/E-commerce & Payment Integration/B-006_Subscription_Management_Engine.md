# SMART DAIRY LTD.
## SUBSCRIPTION MANAGEMENT ENGINE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-006 |
| **Version** | 1.0 |
| **Date** | May 16, 2026 |
| **Author** | Odoo Lead |
| **Owner** | Odoo Lead |
| **Reviewer** | Product Manager |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Architecture Overview](#2-architecture-overview)
3. [Data Models](#3-data-models)
4. [Subscription Plans](#4-subscription-plans)
5. [Billing Engine](#5-billing-engine)
6. [Delivery Scheduling](#6-delivery-scheduling)
7. [Payment Processing](#7-payment-processing)
8. [Customer Portal](#8-customer-portal)
9. [Admin Dashboard](#9-admin-dashboard)
10. [Notifications](#10-notifications)
11. [API Endpoints](#11-api-endpoints)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the comprehensive technical specification for the Smart Dairy Subscription Management Engine. The engine powers the subscription-based delivery service for fresh dairy products, enabling customers to receive daily, weekly, or custom-scheduled deliveries.

### 1.2 Business Context

Smart Dairy's subscription model is a core differentiator, offering:
- **Daily Fresh Milk Delivery** - 1L, 2L, or custom quantities
- **Weekly Bundles** - Milk + yogurt + butter combos
- **Custom Schedules** - Customer-defined delivery days
- **Pause/Resume Flexibility** - Vacation and travel management

### 1.3 Subscription Goals

| Metric | Target |
|--------|--------|
| **Subscription Adoption** | > 40% of B2C customers |
| **Monthly Churn Rate** | < 5% |
| **Average Subscription Value** | ৳3,500/month |
| **Delivery Success Rate** | > 98% |
| **Customer Satisfaction** | > 4.5/5 |

---

## 2. ARCHITECTURE OVERVIEW

### 2.1 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SUBSCRIPTION MANAGEMENT ARCHITECTURE                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         PRESENTATION LAYER                             │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │ Customer     │  │ Mobile App   │  │ Admin        │               │  │
│  │  │ Portal       │  │ Subscription │  │ Dashboard    │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         APPLICATION LAYER                              │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │ Subscription │  │ Billing      │  │ Delivery     │               │  │
│  │  │ Service      │  │ Engine       │  │ Scheduler    │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │ Plan Manager │  │ Payment      │  │ Notification │               │  │
│  │  │              │  │ Processor    │  │ Service      │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         DATA LAYER                                     │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │ Subscription │  │ Billing      │  │ Delivery     │               │  │
│  │  │ DB           │  │ Records      │  │ Schedule     │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Technology Stack

| Component | Technology | Purpose |
|-----------|------------|---------|
| **Core Platform** | Odoo 19 | Subscription management |
| **Scheduler** | Celery Beat | Recurring tasks |
| **Billing** | Odoo Invoicing | Invoice generation |
| **Payments** | Payment Gateway | Recurring charges |
| **Notifications** | Firebase/Email | Customer alerts |

---

## 3. DATA MODELS

### 3.1 Core Subscription Models

```python
# models/subscription.py

from odoo import models, fields, api, _
from datetime import datetime, timedelta
from dateutil.relativedelta import relativedelta

class SubscriptionPlan(models.Model):
    _name = 'subscription.plan'
    _description = 'Subscription Plan Template'
    _order = 'sequence'
    
    name = fields.Char(string='Plan Name', required=True, translate=True)
    code = fields.Char(string='Plan Code', required=True, index=True)
    description = fields.Text(string='Description', translate=True)
    
    # Plan Configuration
    plan_type = fields.Selection([
        ('daily', 'Daily Delivery'),
        ('weekly', 'Weekly Bundle'),
        ('custom', 'Custom Schedule'),
        ('alternate', 'Alternate Days'),
    ], string='Plan Type', required=True, default='daily')
    
    # Products
    product_ids = fields.Many2many('product.product', 
                                    string='Included Products',
                                    domain=[('is_subscription', '=', True)])
    
    # Pricing
    price_monthly = fields.Float(string='Monthly Price', required=True)
    price_quarterly = fields.Float(string='Quarterly Price')
    price_yearly = fields.Float(string='Yearly Price')
    
    # Delivery Schedule
    delivery_days = fields.Selection([
        ('all', 'All Days'),
        ('weekdays', 'Weekdays Only'),
        ('weekends', 'Weekends Only'),
        ('custom', 'Custom Days'),
    ], string='Delivery Days', default='all')
    
    custom_delivery_days = fields.Many2many('delivery.day', 
                                             string='Custom Delivery Days')
    
    delivery_time_slots = fields.Many2many('delivery.time.slot',
                                            string='Available Time Slots')
    
    # Features
    features = fields.Text(string='Plan Features')
    min_commitment_months = fields.Integer(string='Minimum Commitment', default=1)
    pause_allowed = fields.Boolean(string='Pause Allowed', default=True)
    max_pause_days_monthly = fields.Integer(string='Max Pause Days/Month', default=7)
    
    # Status
    active = fields.Boolean(string='Active', default=True)
    is_popular = fields.Boolean(string='Popular Plan')
    sequence = fields.Integer(string='Sequence', default=10)
    
    # Images
    image = fields.Binary(string='Plan Image')
    
    # Compute discount
    @api.depends('price_monthly', 'price_quarterly', 'price_yearly')
    def _compute_discounts(self):
        for plan in self:
            if plan.price_quarterly:
                plan.quarterly_discount = (
                    (plan.price_monthly * 3 - plan.price_quarterly) / 
                    (plan.price_monthly * 3) * 100
                )
            if plan.price_yearly:
                plan.yearly_discount = (
                    (plan.price_monthly * 12 - plan.price_yearly) / 
                    (plan.price_monthly * 12) * 100
                )


class CustomerSubscription(models.Model):
    _name = 'customer.subscription'
    _description = 'Customer Subscription'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'
    
    name = fields.Char(string='Subscription ID', 
                       default=lambda self: self.env['ir.sequence'].next_by_code('subscription'))
    
    # Relationships
    partner_id = fields.Many2one('res.partner', string='Customer', required=True)
    plan_id = fields.Many2one('subscription.plan', string='Subscription Plan', required=True)
    
    # Subscription Details
    state = fields.Selection([
        ('draft', 'Draft'),
        ('active', 'Active'),
        ('paused', 'Paused'),
        ('cancelled', 'Cancelled'),
        ('expired', 'Expired'),
    ], string='Status', default='draft', tracking=True)
    
    billing_cycle = fields.Selection([
        ('monthly', 'Monthly'),
        ('quarterly', 'Quarterly'),
        ('yearly', 'Yearly'),
    ], string='Billing Cycle', default='monthly', required=True)
    
    # Pricing
    recurring_amount = fields.Float(string='Recurring Amount', required=True)
    currency_id = fields.Many2one('res.currency', default=lambda self: self.env.company.currency_id)
    
    # Delivery Configuration
    delivery_address_id = fields.Many2one('res.partner', string='Delivery Address')
    delivery_time_slot = fields.Many2one('delivery.time.slot', string='Preferred Time')
    
    # Schedule
    start_date = fields.Date(string='Start Date', required=True, default=fields.Date.today)
    next_billing_date = fields.Date(string='Next Billing Date', compute='_compute_next_billing')
    end_date = fields.Date(string='End Date')
    
    # Custom Schedule (if applicable)
    custom_delivery_days = fields.Many2many('delivery.day', string='Custom Delivery Days')
    
    # Products Configuration
    subscription_line_ids = fields.One2many('subscription.line', 'subscription_id', 
                                            string='Subscription Items')
    
    # Pause History
    pause_history_ids = fields.One2many('subscription.pause', 'subscription_id', 
                                        string='Pause History')
    is_paused = fields.Boolean(string='Currently Paused', compute='_compute_is_paused')
    current_pause_end = fields.Date(string='Current Pause End Date')
    total_pause_days = fields.Integer(string='Total Pause Days', compute='_compute_total_pause')
    
    # Billing
    invoice_ids = fields.One2many('account.move', 'subscription_id', string='Invoices')
    last_invoice_date = fields.Date(string='Last Invoice Date')
    payment_method = fields.Selection([
        ('bkash', 'bKash'),
        ('nagad', 'Nagad'),
        ('rocket', 'Rocket'),
        ('cod', 'Cash on Delivery'),
        ('card', 'Card'),
    ], string='Payment Method')
    
    # Auto-renewal
    auto_renew = fields.Boolean(string='Auto Renew', default=True)
    
    # Metadata
    created_date = fields.Datetime(string='Created', default=fields.Datetime.now)
    activated_date = fields.Datetime(string('Activated'))
    cancelled_date = fields.Datetime(string('Cancelled'))
    cancellation_reason = fields.Text(string('Cancellation Reason')
    
    # Delivery Statistics
    total_deliveries = fields.Integer(string='Total Deliveries', default=0)
    successful_deliveries = fields.Integer(string='Successful Deliveries', default=0)
    missed_deliveries = fields.Integer(string='Missed Deliveries', default=0)
    
    @api.depends('billing_cycle', 'start_date', 'last_invoice_date')
    def _compute_next_billing(self):
        for sub in self:
            if not sub.last_invoice_date:
                sub.next_billing_date = sub.start_date
            else:
                if sub.billing_cycle == 'monthly':
                    sub.next_billing_date = sub.last_invoice_date + relativedelta(months=1)
                elif sub.billing_cycle == 'quarterly':
                    sub.next_billing_date = sub.last_invoice_date + relativedelta(months=3)
                elif sub.billing_cycle == 'yearly':
                    sub.next_billing_date = sub.last_invoice_date + relativedelta(years=1)
    
    @api.depends('pause_history_ids')
    def _compute_is_paused(self):
        today = fields.Date.today()
        for sub in self:
            active_pause = sub.pause_history_ids.filtered(
                lambda p: p.start_date <= today and (not p.end_date or p.end_date >= today)
            )
            sub.is_paused = bool(active_pause)
            sub.current_pause_end = active_pause[0].end_date if active_pause else False
    
    # Actions
    def action_activate(self):
        self.write({
            'state': 'active',
            'activated_date': fields.Datetime.now(),
        })
        self._create_initial_deliveries()
    
    def action_pause(self, start_date, end_date, reason=None):
        self.ensure_one()
        
        # Check pause limits
        month_pauses = self.pause_history_ids.filtered(
            lambda p: p.start_date.month == fields.Date.today().month
        )
        month_pause_days = sum(p.duration_days for p in month_pauses)
        new_pause_days = (end_date - start_date).days
        
        if month_pause_days + new_pause_days > self.plan_id.max_pause_days_monthly:
            raise UserError(_('Exceeds maximum pause days for this month'))
        
        # Create pause record
        self.env['subscription.pause'].create({
            'subscription_id': self.id,
            'start_date': start_date,
            'end_date': end_date,
            'reason': reason,
        })
        
        self.write({'state': 'paused'})
        
        # Adjust delivery schedule
        self._adjust_deliveries_for_pause(start_date, end_date)
    
    def action_resume(self):
        self.ensure_one()
        
        # Close current pause
        current_pause = self.pause_history_ids.filtered(
            lambda p: not p.end_date or p.end_date >= fields.Date.today()
        )
        if current_pause:
            current_pause[0].write({'end_date': fields.Date.today()})
        
        self.write({'state': 'active'})
    
    def action_cancel(self, reason=None):
        self.write({
            'state': 'cancelled',
            'cancelled_date': fields.Datetime.now(),
            'cancellation_reason': reason,
            'auto_renew': False,
        })
        
        # Cancel pending deliveries
        self._cancel_pending_deliveries()
    
    def _create_initial_deliveries(self):
        """Create delivery schedule for first month"""
        self.ensure_one()
        
        start = self.start_date
        end = start + relativedelta(months=1)
        
        current_date = start
        while current_date < end:
            # Check if delivery should occur on this date
            if self._should_deliver_on(current_date):
                self.env['subscription.delivery'].create({
                    'subscription_id': self.id,
                    'scheduled_date': current_date,
                    'state': 'scheduled',
                })
            current_date += timedelta(days=1)
    
    def _should_deliver_on(self, date):
        """Check if delivery should occur on given date"""
        self.ensure_one()
        
        plan = self.plan_id
        
        if plan.plan_type == 'daily':
            return True
        elif plan.plan_type == 'alternate':
            return (date - self.start_date).days % 2 == 0
        elif plan.plan_type == 'custom' and self.custom_delivery_days:
            day_name = date.strftime('%A')
            return day_name in self.custom_delivery_days.mapped('name')
        
        return True


class SubscriptionLine(models.Model):
    _name = 'subscription.line'
    _description = 'Subscription Line Item'
    
    subscription_id = fields.Many2one('customer.subscription', required=True)
    product_id = fields.Many2one('product.product', string='Product', required=True)
    quantity = fields.Float(string='Quantity', default=1.0)
    uom_id = fields.Many2one('uom.uom', string='Unit', related='product_id.uom_id')
    unit_price = fields.Float(string='Unit Price')
    subtotal = fields.Float(string='Subtotal', compute='_compute_subtotal')
    
    @api.depends('quantity', 'unit_price')
    def _compute_subtotal(self):
        for line in self:
            line.subtotal = line.quantity * line.unit_price


class SubscriptionPause(models.Model):
    _name = 'subscription.pause'
    _description = 'Subscription Pause History'
    _order = 'start_date desc'
    
    subscription_id = fields.Many2one('customer.subscription', required=True)
    start_date = fields.Date(string='Pause Start', required=True)
    end_date = fields.Date(string='Pause End')
    reason = fields.Selection([
        ('vacation', 'Vacation/Travel'),
        ('financial', 'Financial Reasons'),
        ('quality', 'Quality Concerns'),
        ('moving', 'Moving/Relocation'),
        ('other', 'Other'),
    ], string='Reason')
    notes = fields.Text(string='Notes')
    
    duration_days = fields.Integer(string='Duration (Days)', 
                                    compute='_compute_duration')
    
    @api.depends('start_date', 'end_date')
    def _compute_duration(self):
        for pause in self:
            if pause.end_date:
                pause.duration_days = (pause.end_date - pause.start_date).days + 1
            else:
                pause.duration_days = 0


class SubscriptionDelivery(models.Model):
    _name = 'subscription.delivery'
    _description = 'Subscription Delivery Schedule'
    _order = 'scheduled_date'
    
    subscription_id = fields.Many2one('customer.subscription', required=True)
    scheduled_date = fields.Date(string='Scheduled Date', required=True)
    actual_date = fields.Datetime(string('Actual Delivery Time'))
    
    state = fields.Selection([
        ('scheduled', 'Scheduled'),
        ('preparing', 'Preparing'),
        ('out_for_delivery', 'Out for Delivery'),
        ('delivered', 'Delivered'),
        ('failed', 'Failed'),
        ('skipped', 'Skipped'),
    ], string='Status', default='scheduled')
    
    delivery_person_id = fields.Many2one('res.users', string='Delivery Person')
    notes = fields.Text(string('Delivery Notes')
    customer_signature = fields.Binary(string='Customer Signature')
    
    # Quality Check
    temperature_check = fields.Float(string('Temperature (°C)')
    quality_ok = fields.Boolean(string('Quality OK')
    
    # Failure Reason
    failure_reason = fields.Selection([
        ('not_home', 'Customer Not Home'),
        ('wrong_address', 'Wrong Address'),
        ('refused', 'Customer Refused'),
        ('vehicle_issue', 'Vehicle Issue'),
        ('weather', 'Weather Conditions'),
        ('other', 'Other'),
    ], string='Failure Reason')
```

---

## 4. SUBSCRIPTION PLANS

### 4.1 Predefined Plans

| Plan Name | Type | Price | Description |
|-----------|------|-------|-------------|
| **Daily Fresh 1L** | Daily | ৳1,800/mo | 1 liter milk daily |
| **Daily Fresh 2L** | Daily | ৳3,500/mo | 2 liters milk daily |
| **Weekly Essentials** | Weekly | ৳2,200/mo | 7L milk + 2 yogurt weekly |
| **Family Pack** | Weekly | ৳4,500/mo | 14L milk + 4 yogurt + 2 butter weekly |
| **Custom Schedule** | Custom | Varies | Customer-defined delivery days |

### 4.2 Plan Configuration

```python
# Default plan setup
default_plans = [
    {
        'name': 'Daily Fresh 1L',
        'code': 'DAILY_1L',
        'plan_type': 'daily',
        'price_monthly': 1800,
        'price_quarterly': 5100,  # 5% discount
        'price_yearly': 19440,    # 10% discount
        'delivery_days': 'all',
        'features': '✓ Daily fresh delivery\n✓ Pause anytime\n✓ Free delivery\n✓ Quality guarantee',
        'pause_allowed': True,
        'max_pause_days_monthly': 7,
    },
    # ... more plans
]
```

---

## 5. BILLING ENGINE

### 5.1 Automated Billing

```python
# Automated billing via Celery Beat
from celery import shared_task

@shared_task
def generate_subscription_invoices():
    """Generate invoices for subscriptions due for billing"""
    today = fields.Date.today()
    
    # Find subscriptions due for billing
    subscriptions = env['customer.subscription'].search([
        ('state', '=', 'active'),
        ('next_billing_date', '<=', today),
        ('auto_renew', '=', True),
    ])
    
    for subscription in subscriptions:
        try:
            # Create invoice
            invoice = subscription._create_invoice()
            
            # Process payment if saved method exists
            if subscription.payment_method != 'cod':
                subscription._process_recurring_payment(invoice)
            
            # Update next billing date
            subscription._update_billing_date()
            
        except Exception as e:
            _logger.error(f"Billing failed for subscription {subscription.name}: {e}")
            # Notify customer and admin
            subscription._notify_billing_failure()


class CustomerSubscription(models.Model):
    # ... existing code ...
    
    def _create_invoice(self):
        """Create subscription invoice"""
        self.ensure_one()
        
        invoice_vals = {
            'partner_id': self.partner_id.id,
            'move_type': 'out_invoice',
            'subscription_id': self.id,
            'invoice_date': fields.Date.today(),
            'invoice_line_ids': [],
        }
        
        # Add subscription lines
        for line in self.subscription_line_ids:
            invoice_vals['invoice_line_ids'].append((0, 0, {
                'product_id': line.product_id.id,
                'quantity': line.quantity * 30,  # Monthly quantity
                'price_unit': line.unit_price,
            }))
        
        invoice = self.env['account.move'].create(invoice_vals)
        self.last_invoice_date = fields.Date.today()
        
        return invoice
    
    def _process_recurring_payment(self, invoice):
        """Process automatic payment for subscription"""
        self.ensure_one()
        
        payment_method = self.payment_method
        
        # Call appropriate payment gateway
        if payment_method == 'bkash':
            return self._charge_bkash(invoice)
        elif payment_method == 'nagad':
            return self._charge_nagad(invoice)
        elif payment_method == 'rocket':
            return self._charge_rocket(invoice)
        elif payment_method == 'card':
            return self._charge_card(invoice)
    
    def _charge_bkash(self, invoice):
        """Process bKash recurring payment"""
        bkash_service = self.env['bkash.service']
        
        result = bkash_service.create_payment(
            order_id=invoice.name,
            amount=invoice.amount_total,
            payer_reference=f"SUB_{self.id}",
        )
        
        # Store payment reference
        self.env['subscription.payment'].create({
            'subscription_id': self.id,
            'invoice_id': invoice.id,
            'provider': 'bkash',
            'reference': result['payment_id'],
            'amount': invoice.amount_total,
            'status': 'pending',
        })
```

---

## 6. DELIVERY SCHEDULING

### 6.1 Route Optimization

```python
class DeliveryScheduler(models.Model):
    _name = 'delivery.scheduler'
    
    def optimize_daily_routes(self, delivery_date):
        """Optimize delivery routes for a given date"""
        
        # Get all deliveries for the date
        deliveries = self.env['subscription.delivery'].search([
            ('scheduled_date', '=', delivery_date),
            ('state', 'in', ['scheduled', 'preparing']),
        ])
        
        # Group by area/zone
        zones = {}
        for delivery in deliveries:
            zone = delivery.subscription_id.delivery_address_id.delivery_zone
            if zone not in zones:
                zones[zone] = []
            zones[zone].append(delivery)
        
        # Optimize routes within each zone
        for zone, zone_deliveries in zones.items():
            optimized_route = self._optimize_route(zone_deliveries)
            
            # Assign sequence numbers
            for seq, delivery in enumerate(optimized_route, 1):
                delivery.write({
                    'delivery_sequence': seq,
                    'route_id': zone.id,
                })
    
    def _optimize_route(self, deliveries):
        """Simple nearest-neighbor route optimization"""
        # In production, use proper TSP algorithm or external service
        return sorted(deliveries, key=lambda d: (
            d.subscription_id.delivery_address_id.latitude,
            d.subscription_id.delivery_address_id.longitude
        ))
```

---

## 7. PAYMENT PROCESSING

### 7.1 Recurring Payment Support

| Payment Method | Recurring Support | Implementation |
|----------------|-------------------|----------------|
| **bKash** | Tokenized agreement | Store agreement ID |
| **Nagad** | Manual (monthly) | Send payment link |
| **Rocket** | Manual (monthly) | USSD reminder |
| **Card** | Tokenization | Store secure token |
| **COD** | N/A | Collect on delivery |

---

## 8. CUSTOMER PORTAL

### 8.1 Subscription Dashboard

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  MY SUBSCRIPTIONS                                                               │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  ACTIVE SUBSCRIPTION                                                        ││
│  │                                                                             ││
│  │  🥛 Daily Fresh 1L                                           [Manage ▼]   ││
│  │                                                                             ││
│  │  Status: ● Active since Jan 15, 2026                                       ││
│  │  Monthly: ৳1,800 (Auto-renews on Feb 15)                                   ││
│  │                                                                             ││
│  │  Delivery Schedule:                                                         ││
│  │  Every day at 6:00 AM - 8:00 AM                                            ││
│  │  45, Lake Drive, Gulshan-1, Dhaka                                          ││
│  │                                                                             ││
│  │  [Pause Subscription]  [Modify Plan]  [Cancel]                             ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  UPCOMING DELIVERIES                                                        ││
│  │                                                                             ││
│  │  Tomorrow, Jan 31    6:00 AM    1L Milk    [Modify ▼]                      ││
│  │  Thursday, Feb 1     6:00 AM    1L Milk    [Modify ▼]                      ││
│  │  Friday, Feb 2       6:00 AM    1L Milk    [Modify ▼]                      ││
│  │                                                                             ││
│  │  [View Full Calendar]                                                        ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐│
│  │  BILLING HISTORY                                                            ││
│  │                                                                             ││
│  │  Jan 15, 2026    Invoice #INV-2026-0012    ৳1,800    [Paid ✓]              ││
│  │  Dec 15, 2025    Invoice #INV-2025-0112    ৳1,800    [Paid ✓]              ││
│  │                                                                             ││
│  └─────────────────────────────────────────────────────────────────────────────┘│
│                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 9. ADMIN DASHBOARD

### 9.1 Subscription Analytics

| Metric | Description |
|--------|-------------|
| **Active Subscriptions** | Count of active subscriptions |
| **Monthly Recurring Revenue (MRR)** | Total monthly subscription value |
| **Churn Rate** | % of subscriptions cancelled |
| **Average Subscription Value** | Average monthly revenue per subscription |
| **Delivery Success Rate** | % of successful deliveries |
| **Pause Rate** | % of subscriptions currently paused |

---

## 10. NOTIFICATIONS

### 10.1 Automated Notifications

| Trigger | Channel | Timing |
|---------|---------|--------|
| Subscription Created | Email/SMS | Immediately |
| Upcoming Delivery | SMS/App | Evening before |
| Delivery Dispatched | SMS/App | Morning of delivery |
| Payment Due | Email/SMS | 3 days before |
| Payment Failed | Email/SMS | Immediately |
| Subscription Paused | Email | Immediately |
| Subscription Expiring | Email | 7 days before |

---

## 11. API ENDPOINTS

### 11.1 Subscription API

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/subscriptions` | GET | List customer subscriptions |
| `/api/v1/subscriptions` | POST | Create new subscription |
| `/api/v1/subscriptions/{id}` | GET | Get subscription details |
| `/api/v1/subscriptions/{id}/pause` | POST | Pause subscription |
| `/api/v1/subscriptions/{id}/resume` | POST | Resume subscription |
| `/api/v1/subscriptions/{id}/cancel` | POST | Cancel subscription |
| `/api/v1/subscriptions/{id}/deliveries` | GET | Get delivery schedule |

---

## 12. APPENDICES

### Appendix A: Delivery Time Slots

| Slot | Time | Availability |
|------|------|--------------|
| Early Morning | 6:00 AM - 8:00 AM | All areas |
| Morning | 8:00 AM - 10:00 AM | All areas |
| Afternoon | 2:00 PM - 4:00 PM | Select areas |
| Evening | 6:00 PM - 8:00 PM | Select areas |

### Appendix B: Pause Policy

| Plan Type | Max Pause Days/Month | Notice Required |
|-----------|---------------------|-----------------|
| Daily | 7 days | 24 hours |
| Weekly | 14 days | 48 hours |
| Custom | 7 days | 24 hours |

---

**END OF SUBSCRIPTION MANAGEMENT ENGINE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | May 16, 2026 | Odoo Lead | Initial version |
