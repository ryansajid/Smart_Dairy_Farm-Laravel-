# Milestone 62: Customer Account Management

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P7-M62-v1.0 |
| **Milestone** | 62 - Customer Account Management |
| **Phase** | Phase 7: B2C E-Commerce Core |
| **Days** | 361-370 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

### Authors & Contributors

| Role | Name | Responsibility |
|------|------|----------------|
| Technical Architect | Lead Architect | Architecture, Standards |
| Backend Lead | Dev 1 | Customer APIs, Loyalty System |
| Full-Stack Developer | Dev 2 | Address Management, Security |
| Frontend Lead | Dev 3 | Dashboard UI, OWL Components |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Objectives](#2-objectives)
3. [Key Deliverables](#3-key-deliverables)
4. [Prerequisites](#4-prerequisites)
5. [Requirement Traceability](#5-requirement-traceability)
6. [Day-by-Day Development Plan](#6-day-by-day-development-plan)
7. [Technical Specifications](#7-technical-specifications)
8. [Testing Requirements](#8-testing-requirements)
9. [Risk Mitigation](#9-risk-mitigation)
10. [Sign-off Checklist](#10-sign-off-checklist)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Build a comprehensive customer account management system with personalized dashboard, loyalty program integration, and secure account features to enhance customer engagement and retention.**

### 1.2 Context

Milestone 62 focuses on creating a robust customer account experience that goes beyond basic registration and login. This milestone delivers personalized dashboards, loyalty points tracking, referral programs, and advanced account security features. The goal is to increase customer lifetime value through engagement features and make account management seamless.

### 1.3 Scope Summary

| Area | Included |
|------|----------|
| Customer Dashboard | ✅ Order summary, quick actions, personalization |
| Address Management | ✅ Multiple addresses, GPS autocomplete, validation |
| Payment Methods | ✅ Saved cards (tokenized), wallet integration |
| Order History | ✅ Filtering, reorder functionality, tracking |
| Loyalty Program | ✅ Points earning, redemption, tier system |
| Referral System | ✅ Referral codes, tracking, rewards |
| Notifications | ✅ Email, SMS, push preferences |
| Account Security | ✅ 2FA, password policies, session management |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Implement customer dashboard with order overview | Dashboard loads < 1.5s |
| O2 | Build address book with GPS autocomplete | Address save rate > 95% |
| O3 | Enable secure payment method storage | PCI DSS tokenization compliant |
| O4 | Create order history with filtering and reorder | Reorder conversion > 15% |
| O5 | Launch loyalty points system | Points calculation 100% accurate |
| O6 | Implement referral program | Referral tracking functional |
| O7 | Build notification preferences center | Opt-out rate < 10% |
| O8 | Add 2FA and security features | 2FA enrollment > 30% |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Customer dashboard backend APIs | Dev 1 | Python/Odoo | Day 362 |
| D2 | Loyalty points calculation engine | Dev 1 | Python | Day 364 |
| D3 | Address management with geolocation | Dev 2 | Python/API | Day 363 |
| D4 | 2FA authentication system | Dev 2 | Python/TOTP | Day 367 |
| D5 | Customer dashboard OWL component | Dev 3 | JavaScript | Day 365 |
| D6 | Order history with reorder UI | Dev 3 | OWL/QWeb | Day 366 |
| D7 | Referral program backend | Dev 1 | Python | Day 368 |
| D8 | Notification preferences API | Dev 2 | Python | Day 369 |
| D9 | Account settings UI | Dev 3 | OWL/QWeb | Day 369 |
| D10 | Integration tests & documentation | All | Python/MD | Day 370 |

---

## 4. Prerequisites

### 4.1 Technical Prerequisites

| Prerequisite | Source | Status |
|--------------|--------|--------|
| Customer authentication system | Phase 5 | Required |
| Order management models | Phase 5 | Required |
| Payment gateway integration | Phase 5 | Required |
| Elasticsearch service | Milestone 61 | Required |
| Redis cache configured | Milestone 61 | Required |
| Email service (SendGrid/SES) | Infrastructure | Required |
| SMS gateway configured | Infrastructure | Required |

### 4.2 Knowledge Prerequisites

- Odoo 19 customer portal architecture
- OWL 2.0 reactive components
- TOTP (Time-based One-Time Password) implementation
- PCI DSS tokenization requirements
- Google Places API for address autocomplete

---

## 5. Requirement Traceability

### 5.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| RFP-B2C-4.2.1-001 | Customer dashboard with order summary | D1, D5 |
| RFP-B2C-4.2.1-002 | Address book management | D3 |
| RFP-B2C-4.2.1-003 | Saved payment methods | D3 |
| RFP-B2C-4.2.2-001 | Loyalty points system | D2 |
| RFP-B2C-4.2.2-002 | Referral program | D7 |
| RFP-B2C-4.2.3-001 | Order history access | D6 |
| RFP-B2C-4.2.4-001 | 2FA authentication | D4 |
| RFP-B2C-4.2.4-002 | Notification preferences | D8 |

### 5.2 BRD Requirements

| BRD Req ID | Description | Deliverable |
|------------|-------------|-------------|
| BRD-FR-B2C-002 | Customer account management | All |
| BRD-FR-B2C-002.1 | Personal information update | D1, D5 |
| BRD-FR-B2C-002.2 | Multiple delivery addresses | D3 |
| BRD-FR-B2C-002.3 | Order tracking from account | D6 |
| BRD-FR-B2C-002.4 | Loyalty program participation | D2, D7 |

### 5.3 SRS Requirements

| SRS Req ID | Description | Deliverable |
|------------|-------------|-------------|
| SRS-REQ-B2C-002 | Customer API endpoints | D1 |
| SRS-REQ-B2C-002.1 | GET /api/v1/customer/dashboard | D1 |
| SRS-REQ-B2C-002.2 | CRUD /api/v1/customer/addresses | D3 |
| SRS-REQ-B2C-002.3 | GET /api/v1/customer/orders | D6 |
| SRS-REQ-B2C-002.4 | POST /api/v1/customer/loyalty/redeem | D2 |

---

## 6. Day-by-Day Development Plan

### Day 361 (Phase Day 11): Customer Model Extensions & Dashboard Foundation

#### Day 361 Objectives
- Extend res.partner model for B2C customer features
- Create loyalty points model structure
- Set up customer dashboard API foundation
- Begin dashboard UI scaffolding

---

#### Dev 1 (Backend Lead) - Day 361

**Focus: Customer Model Extensions**

**Hour 1-2: Customer Model Enhancement**

```python
# File: smart_dairy_ecommerce/models/customer_extended.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import hashlib
import secrets

class ResPartner(models.Model):
    _inherit = 'res.partner'

    # B2C Customer Fields
    is_b2c_customer = fields.Boolean(
        string='B2C Customer',
        default=False,
        index=True
    )
    customer_code = fields.Char(
        string='Customer Code',
        readonly=True,
        copy=False,
        index=True
    )

    # Loyalty Program
    loyalty_points = fields.Integer(
        string='Loyalty Points',
        default=0,
        tracking=True
    )
    loyalty_tier_id = fields.Many2one(
        'loyalty.tier',
        string='Loyalty Tier',
        compute='_compute_loyalty_tier',
        store=True
    )
    lifetime_points = fields.Integer(
        string='Lifetime Points Earned',
        default=0,
        readonly=True
    )

    # Referral Program
    referral_code = fields.Char(
        string='Referral Code',
        readonly=True,
        copy=False,
        index=True
    )
    referred_by_id = fields.Many2one(
        'res.partner',
        string='Referred By',
        domain=[('is_b2c_customer', '=', True)]
    )
    referral_count = fields.Integer(
        string='Successful Referrals',
        compute='_compute_referral_count',
        store=True
    )

    # Account Security
    two_factor_enabled = fields.Boolean(
        string='2FA Enabled',
        default=False
    )
    two_factor_secret = fields.Char(
        string='2FA Secret',
        groups='base.group_system'
    )
    last_password_change = fields.Datetime(
        string='Last Password Change'
    )
    failed_login_attempts = fields.Integer(
        string='Failed Login Attempts',
        default=0
    )
    account_locked_until = fields.Datetime(
        string='Account Locked Until'
    )

    # Notification Preferences
    notify_order_updates = fields.Boolean(
        string='Order Update Notifications',
        default=True
    )
    notify_promotions = fields.Boolean(
        string='Promotional Notifications',
        default=True
    )
    notify_loyalty = fields.Boolean(
        string='Loyalty Points Notifications',
        default=True
    )
    preferred_notification_channel = fields.Selection([
        ('email', 'Email'),
        ('sms', 'SMS'),
        ('push', 'Push Notification'),
        ('all', 'All Channels')
    ], string='Preferred Channel', default='email')

    # Statistics (Computed)
    total_orders = fields.Integer(
        string='Total Orders',
        compute='_compute_order_stats',
        store=True
    )
    total_spent = fields.Monetary(
        string='Total Spent',
        compute='_compute_order_stats',
        store=True,
        currency_field='currency_id'
    )
    average_order_value = fields.Monetary(
        string='Average Order Value',
        compute='_compute_order_stats',
        store=True,
        currency_field='currency_id'
    )
    last_order_date = fields.Datetime(
        string='Last Order Date',
        compute='_compute_order_stats',
        store=True
    )

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('is_b2c_customer'):
                vals['customer_code'] = self._generate_customer_code()
                vals['referral_code'] = self._generate_referral_code()
        return super().create(vals_list)

    def _generate_customer_code(self):
        """Generate unique customer code: SD-CUST-XXXXXX"""
        sequence = self.env['ir.sequence'].next_by_code('b2c.customer.code')
        return f"SD-CUST-{sequence or secrets.token_hex(3).upper()}"

    def _generate_referral_code(self):
        """Generate unique referral code"""
        while True:
            code = secrets.token_urlsafe(6).upper()[:8]
            if not self.search_count([('referral_code', '=', code)]):
                return code

    @api.depends('loyalty_points')
    def _compute_loyalty_tier(self):
        tiers = self.env['loyalty.tier'].search([], order='min_points desc')
        for partner in self:
            partner.loyalty_tier_id = False
            for tier in tiers:
                if partner.lifetime_points >= tier.min_points:
                    partner.loyalty_tier_id = tier
                    break

    @api.depends('referred_by_id')
    def _compute_referral_count(self):
        for partner in self:
            partner.referral_count = self.search_count([
                ('referred_by_id', '=', partner.id),
                ('is_b2c_customer', '=', True)
            ])

    @api.depends('sale_order_ids.state', 'sale_order_ids.amount_total')
    def _compute_order_stats(self):
        for partner in self:
            orders = partner.sale_order_ids.filtered(
                lambda o: o.state in ('sale', 'done')
            )
            partner.total_orders = len(orders)
            partner.total_spent = sum(orders.mapped('amount_total'))
            partner.average_order_value = (
                partner.total_spent / partner.total_orders
                if partner.total_orders else 0
            )
            partner.last_order_date = max(
                orders.mapped('date_order')
            ) if orders else False
```

**Hour 3-4: Loyalty Tier Model**

```python
# File: smart_dairy_ecommerce/models/loyalty_tier.py

from odoo import models, fields, api

class LoyaltyTier(models.Model):
    _name = 'loyalty.tier'
    _description = 'Customer Loyalty Tier'
    _order = 'min_points desc'

    name = fields.Char(
        string='Tier Name',
        required=True,
        translate=True
    )
    code = fields.Char(
        string='Tier Code',
        required=True
    )
    min_points = fields.Integer(
        string='Minimum Lifetime Points',
        required=True
    )
    color = fields.Char(
        string='Badge Color',
        default='#6c757d'
    )
    icon = fields.Char(
        string='Icon Class',
        default='fa-star'
    )

    # Benefits
    points_multiplier = fields.Float(
        string='Points Multiplier',
        default=1.0,
        help='Multiply earned points by this factor'
    )
    discount_percentage = fields.Float(
        string='Tier Discount %',
        default=0.0
    )
    free_shipping_threshold = fields.Monetary(
        string='Free Shipping Threshold',
        currency_field='currency_id'
    )
    priority_support = fields.Boolean(
        string='Priority Support',
        default=False
    )
    early_access = fields.Boolean(
        string='Early Access to Sales',
        default=False
    )
    birthday_bonus_points = fields.Integer(
        string='Birthday Bonus Points',
        default=0
    )

    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    # Statistics
    member_count = fields.Integer(
        string='Members',
        compute='_compute_member_count'
    )

    description = fields.Html(
        string='Benefits Description',
        translate=True
    )
    active = fields.Boolean(default=True)

    _sql_constraints = [
        ('unique_code', 'UNIQUE(code)', 'Tier code must be unique'),
        ('unique_min_points', 'UNIQUE(min_points)', 'Min points must be unique'),
    ]

    def _compute_member_count(self):
        for tier in self:
            tier.member_count = self.env['res.partner'].search_count([
                ('loyalty_tier_id', '=', tier.id),
                ('is_b2c_customer', '=', True)
            ])

    @api.model
    def init_default_tiers(self):
        """Initialize default loyalty tiers"""
        tiers = [
            {
                'name': 'Bronze',
                'code': 'BRONZE',
                'min_points': 0,
                'color': '#CD7F32',
                'points_multiplier': 1.0,
                'discount_percentage': 0,
            },
            {
                'name': 'Silver',
                'code': 'SILVER',
                'min_points': 1000,
                'color': '#C0C0C0',
                'points_multiplier': 1.25,
                'discount_percentage': 5,
                'birthday_bonus_points': 100,
            },
            {
                'name': 'Gold',
                'code': 'GOLD',
                'min_points': 5000,
                'color': '#FFD700',
                'points_multiplier': 1.5,
                'discount_percentage': 10,
                'priority_support': True,
                'birthday_bonus_points': 250,
            },
            {
                'name': 'Platinum',
                'code': 'PLATINUM',
                'min_points': 15000,
                'color': '#E5E4E2',
                'points_multiplier': 2.0,
                'discount_percentage': 15,
                'priority_support': True,
                'early_access': True,
                'birthday_bonus_points': 500,
            },
        ]
        for tier_data in tiers:
            existing = self.search([('code', '=', tier_data['code'])])
            if not existing:
                self.create(tier_data)
```

**Hour 5-6: Loyalty Points Transaction Model**

```python
# File: smart_dairy_ecommerce/models/loyalty_transaction.py

from odoo import models, fields, api
from datetime import datetime, timedelta

class LoyaltyTransaction(models.Model):
    _name = 'loyalty.transaction'
    _description = 'Loyalty Points Transaction'
    _order = 'create_date desc'

    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True,
        index=True,
        ondelete='cascade'
    )
    transaction_type = fields.Selection([
        ('earn', 'Points Earned'),
        ('redeem', 'Points Redeemed'),
        ('expire', 'Points Expired'),
        ('bonus', 'Bonus Points'),
        ('referral', 'Referral Reward'),
        ('adjustment', 'Manual Adjustment'),
    ], string='Type', required=True, index=True)

    points = fields.Integer(
        string='Points',
        required=True,
        help='Positive for earn/bonus, negative for redeem/expire'
    )
    balance_after = fields.Integer(
        string='Balance After',
        readonly=True
    )

    # Related Records
    order_id = fields.Many2one(
        'sale.order',
        string='Related Order'
    )
    referred_partner_id = fields.Many2one(
        'res.partner',
        string='Referred Customer'
    )

    description = fields.Char(string='Description')
    expiry_date = fields.Date(
        string='Points Expiry Date',
        compute='_compute_expiry_date',
        store=True
    )
    expired = fields.Boolean(
        string='Expired',
        default=False
    )

    # Audit
    created_by_id = fields.Many2one(
        'res.users',
        string='Created By',
        default=lambda self: self.env.user
    )

    @api.depends('create_date', 'transaction_type')
    def _compute_expiry_date(self):
        expiry_months = int(self.env['ir.config_parameter'].sudo().get_param(
            'loyalty.points_expiry_months', '12'
        ))
        for record in self:
            if record.transaction_type == 'earn' and record.create_date:
                record.expiry_date = record.create_date.date() + timedelta(days=expiry_months * 30)
            else:
                record.expiry_date = False

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            partner = self.env['res.partner'].browse(vals['partner_id'])
            new_balance = partner.loyalty_points + vals.get('points', 0)
            vals['balance_after'] = new_balance

        records = super().create(vals_list)

        # Update partner balances
        for record in records:
            record.partner_id.sudo().write({
                'loyalty_points': record.balance_after
            })
            if record.transaction_type == 'earn':
                record.partner_id.sudo().write({
                    'lifetime_points': record.partner_id.lifetime_points + record.points
                })

        return records

    @api.model
    def expire_points_cron(self):
        """Cron job to expire old points"""
        today = fields.Date.today()
        expiring = self.search([
            ('expiry_date', '<=', today),
            ('expired', '=', False),
            ('transaction_type', '=', 'earn'),
            ('points', '>', 0)
        ])

        for transaction in expiring:
            if transaction.partner_id.loyalty_points > 0:
                expire_points = min(transaction.points, transaction.partner_id.loyalty_points)
                self.create({
                    'partner_id': transaction.partner_id.id,
                    'transaction_type': 'expire',
                    'points': -expire_points,
                    'description': f'Points expired from transaction {transaction.id}'
                })
            transaction.expired = True
```

**Hour 7-8: Customer Dashboard API Controller**

```python
# File: smart_dairy_ecommerce/controllers/customer_dashboard.py

from odoo import http
from odoo.http import request
import json

class CustomerDashboardController(http.Controller):

    @http.route('/api/v1/customer/dashboard', type='json', auth='user', methods=['GET'])
    def get_dashboard(self, **kwargs):
        """Get customer dashboard data"""
        partner = request.env.user.partner_id

        if not partner.is_b2c_customer:
            return {'error': 'Not a B2C customer', 'code': 403}

        # Get recent orders
        recent_orders = request.env['sale.order'].sudo().search([
            ('partner_id', '=', partner.id),
            ('state', 'in', ['sale', 'done'])
        ], limit=5, order='date_order desc')

        # Get pending orders (not delivered)
        pending_orders = request.env['sale.order'].sudo().search([
            ('partner_id', '=', partner.id),
            ('state', '=', 'sale'),
            ('delivery_status', '!=', 'delivered')
        ])

        # Loyalty tier benefits
        tier = partner.loyalty_tier_id
        tier_data = None
        if tier:
            tier_data = {
                'name': tier.name,
                'code': tier.code,
                'color': tier.color,
                'icon': tier.icon,
                'discount': tier.discount_percentage,
                'multiplier': tier.points_multiplier,
            }

        # Points to next tier
        next_tier = request.env['loyalty.tier'].sudo().search([
            ('min_points', '>', partner.lifetime_points)
        ], limit=1, order='min_points asc')

        points_to_next = 0
        next_tier_name = None
        if next_tier:
            points_to_next = next_tier.min_points - partner.lifetime_points
            next_tier_name = next_tier.name

        return {
            'success': True,
            'data': {
                'customer': {
                    'name': partner.name,
                    'email': partner.email,
                    'phone': partner.phone,
                    'customer_code': partner.customer_code,
                    'member_since': partner.create_date.isoformat() if partner.create_date else None,
                },
                'statistics': {
                    'total_orders': partner.total_orders,
                    'total_spent': partner.total_spent,
                    'average_order_value': partner.average_order_value,
                    'last_order_date': partner.last_order_date.isoformat() if partner.last_order_date else None,
                },
                'loyalty': {
                    'current_points': partner.loyalty_points,
                    'lifetime_points': partner.lifetime_points,
                    'tier': tier_data,
                    'points_to_next_tier': points_to_next,
                    'next_tier_name': next_tier_name,
                },
                'referral': {
                    'code': partner.referral_code,
                    'successful_referrals': partner.referral_count,
                    'referral_link': f"{request.httprequest.host_url}shop?ref={partner.referral_code}",
                },
                'recent_orders': [{
                    'id': order.id,
                    'name': order.name,
                    'date': order.date_order.isoformat(),
                    'amount': order.amount_total,
                    'state': order.state,
                    'item_count': len(order.order_line),
                } for order in recent_orders],
                'pending_orders_count': len(pending_orders),
                'notifications': {
                    'order_updates': partner.notify_order_updates,
                    'promotions': partner.notify_promotions,
                    'loyalty': partner.notify_loyalty,
                    'channel': partner.preferred_notification_channel,
                },
                'security': {
                    'two_factor_enabled': partner.two_factor_enabled,
                    'last_password_change': partner.last_password_change.isoformat() if partner.last_password_change else None,
                }
            }
        }

    @http.route('/api/v1/customer/profile', type='json', auth='user', methods=['PUT'])
    def update_profile(self, **kwargs):
        """Update customer profile information"""
        partner = request.env.user.partner_id

        allowed_fields = ['name', 'phone', 'street', 'street2', 'city', 'zip']
        update_vals = {}

        for field in allowed_fields:
            if field in kwargs:
                update_vals[field] = kwargs[field]

        if update_vals:
            partner.sudo().write(update_vals)

        return {
            'success': True,
            'message': 'Profile updated successfully'
        }
```

---

#### Dev 2 (Full-Stack) - Day 361

**Focus: Address Management Foundation**

**Hour 1-2: Customer Address Model**

```python
# File: smart_dairy_ecommerce/models/customer_address.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import re

class CustomerAddress(models.Model):
    _name = 'customer.address'
    _description = 'Customer Delivery Address'
    _order = 'is_default desc, create_date desc'

    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True,
        ondelete='cascade',
        index=True
    )

    # Address Type
    address_type = fields.Selection([
        ('home', 'Home'),
        ('office', 'Office'),
        ('other', 'Other')
    ], string='Address Type', default='home', required=True)

    label = fields.Char(
        string='Address Label',
        help='e.g., "Main Office", "Parents House"'
    )

    # Contact
    recipient_name = fields.Char(
        string='Recipient Name',
        required=True
    )
    phone = fields.Char(
        string='Phone Number',
        required=True
    )
    alternate_phone = fields.Char(
        string='Alternate Phone'
    )

    # Address Details
    street = fields.Char(
        string='Street Address',
        required=True
    )
    street2 = fields.Char(
        string='Apartment/Suite/Floor'
    )
    area = fields.Char(
        string='Area/Locality'
    )
    city = fields.Char(
        string='City',
        required=True
    )
    district = fields.Char(
        string='District'
    )
    division = fields.Selection([
        ('dhaka', 'Dhaka'),
        ('chittagong', 'Chittagong'),
        ('rajshahi', 'Rajshahi'),
        ('khulna', 'Khulna'),
        ('barisal', 'Barisal'),
        ('sylhet', 'Sylhet'),
        ('rangpur', 'Rangpur'),
        ('mymensingh', 'Mymensingh')
    ], string='Division', required=True)
    zip_code = fields.Char(string='Postal Code')
    country_id = fields.Many2one(
        'res.country',
        string='Country',
        default=lambda self: self.env.ref('base.bd')
    )

    # Geolocation
    latitude = fields.Float(
        string='Latitude',
        digits=(10, 7)
    )
    longitude = fields.Float(
        string='Longitude',
        digits=(10, 7)
    )
    google_place_id = fields.Char(
        string='Google Place ID'
    )

    # Delivery
    delivery_instructions = fields.Text(
        string='Delivery Instructions'
    )
    is_default = fields.Boolean(
        string='Default Address',
        default=False
    )
    is_billing = fields.Boolean(
        string='Use for Billing',
        default=False
    )

    # Validation
    is_verified = fields.Boolean(
        string='Address Verified',
        default=False
    )
    verification_date = fields.Datetime(
        string='Verification Date'
    )

    active = fields.Boolean(default=True)

    # Computed
    full_address = fields.Char(
        string='Full Address',
        compute='_compute_full_address'
    )

    @api.depends('street', 'street2', 'area', 'city', 'district', 'division', 'zip_code')
    def _compute_full_address(self):
        for address in self:
            parts = filter(None, [
                address.street,
                address.street2,
                address.area,
                address.city,
                address.district,
                dict(address._fields['division'].selection).get(address.division),
                address.zip_code
            ])
            address.full_address = ', '.join(parts)

    @api.constrains('phone', 'alternate_phone')
    def _check_phone_format(self):
        bd_phone_pattern = r'^(\+880|880|0)?1[3-9]\d{8}$'
        for record in self:
            if record.phone and not re.match(bd_phone_pattern, record.phone.replace(' ', '').replace('-', '')):
                raise ValidationError('Invalid phone number format. Use Bangladeshi format.')
            if record.alternate_phone and not re.match(bd_phone_pattern, record.alternate_phone.replace(' ', '').replace('-', '')):
                raise ValidationError('Invalid alternate phone format.')

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            # If this is set as default, unset other defaults
            if vals.get('is_default'):
                self.search([
                    ('partner_id', '=', vals.get('partner_id')),
                    ('is_default', '=', True)
                ]).write({'is_default': False})
        return super().create(vals_list)

    def write(self, vals):
        if vals.get('is_default'):
            self.search([
                ('partner_id', 'in', self.mapped('partner_id').ids),
                ('is_default', '=', True),
                ('id', 'not in', self.ids)
            ]).write({'is_default': False})
        return super().write(vals)

    def set_as_default(self):
        """Set this address as the default"""
        self.ensure_one()
        self.search([
            ('partner_id', '=', self.partner_id.id),
            ('is_default', '=', True),
            ('id', '!=', self.id)
        ]).write({'is_default': False})
        self.is_default = True
```

**Hour 3-4: Address API Controller**

```python
# File: smart_dairy_ecommerce/controllers/address_controller.py

from odoo import http
from odoo.http import request
from odoo.exceptions import ValidationError
import json

class AddressController(http.Controller):

    @http.route('/api/v1/customer/addresses', type='json', auth='user', methods=['GET'])
    def get_addresses(self, **kwargs):
        """Get all customer addresses"""
        partner = request.env.user.partner_id
        addresses = request.env['customer.address'].sudo().search([
            ('partner_id', '=', partner.id),
            ('active', '=', True)
        ])

        return {
            'success': True,
            'data': [{
                'id': addr.id,
                'type': addr.address_type,
                'label': addr.label,
                'recipient_name': addr.recipient_name,
                'phone': addr.phone,
                'street': addr.street,
                'street2': addr.street2,
                'area': addr.area,
                'city': addr.city,
                'district': addr.district,
                'division': addr.division,
                'zip_code': addr.zip_code,
                'latitude': addr.latitude,
                'longitude': addr.longitude,
                'delivery_instructions': addr.delivery_instructions,
                'is_default': addr.is_default,
                'is_billing': addr.is_billing,
                'full_address': addr.full_address,
            } for addr in addresses]
        }

    @http.route('/api/v1/customer/addresses', type='json', auth='user', methods=['POST'])
    def create_address(self, **kwargs):
        """Create new customer address"""
        partner = request.env.user.partner_id

        required_fields = ['recipient_name', 'phone', 'street', 'city', 'division']
        for field in required_fields:
            if not kwargs.get(field):
                return {'error': f'{field} is required', 'code': 400}

        # Check address limit
        address_count = request.env['customer.address'].sudo().search_count([
            ('partner_id', '=', partner.id),
            ('active', '=', True)
        ])
        max_addresses = int(request.env['ir.config_parameter'].sudo().get_param(
            'customer.max_addresses', '10'
        ))
        if address_count >= max_addresses:
            return {'error': f'Maximum {max_addresses} addresses allowed', 'code': 400}

        try:
            address = request.env['customer.address'].sudo().create({
                'partner_id': partner.id,
                'address_type': kwargs.get('address_type', 'home'),
                'label': kwargs.get('label'),
                'recipient_name': kwargs['recipient_name'],
                'phone': kwargs['phone'],
                'alternate_phone': kwargs.get('alternate_phone'),
                'street': kwargs['street'],
                'street2': kwargs.get('street2'),
                'area': kwargs.get('area'),
                'city': kwargs['city'],
                'district': kwargs.get('district'),
                'division': kwargs['division'],
                'zip_code': kwargs.get('zip_code'),
                'latitude': kwargs.get('latitude'),
                'longitude': kwargs.get('longitude'),
                'google_place_id': kwargs.get('google_place_id'),
                'delivery_instructions': kwargs.get('delivery_instructions'),
                'is_default': kwargs.get('is_default', False),
                'is_billing': kwargs.get('is_billing', False),
            })

            return {
                'success': True,
                'data': {'id': address.id},
                'message': 'Address created successfully'
            }
        except ValidationError as e:
            return {'error': str(e), 'code': 400}

    @http.route('/api/v1/customer/addresses/<int:address_id>', type='json', auth='user', methods=['PUT'])
    def update_address(self, address_id, **kwargs):
        """Update existing address"""
        partner = request.env.user.partner_id
        address = request.env['customer.address'].sudo().search([
            ('id', '=', address_id),
            ('partner_id', '=', partner.id)
        ], limit=1)

        if not address:
            return {'error': 'Address not found', 'code': 404}

        updatable_fields = [
            'address_type', 'label', 'recipient_name', 'phone', 'alternate_phone',
            'street', 'street2', 'area', 'city', 'district', 'division',
            'zip_code', 'latitude', 'longitude', 'delivery_instructions',
            'is_default', 'is_billing'
        ]

        update_vals = {k: v for k, v in kwargs.items() if k in updatable_fields}

        try:
            address.write(update_vals)
            return {
                'success': True,
                'message': 'Address updated successfully'
            }
        except ValidationError as e:
            return {'error': str(e), 'code': 400}

    @http.route('/api/v1/customer/addresses/<int:address_id>', type='json', auth='user', methods=['DELETE'])
    def delete_address(self, address_id, **kwargs):
        """Delete (archive) address"""
        partner = request.env.user.partner_id
        address = request.env['customer.address'].sudo().search([
            ('id', '=', address_id),
            ('partner_id', '=', partner.id)
        ], limit=1)

        if not address:
            return {'error': 'Address not found', 'code': 404}

        address.active = False

        # If deleted was default, set another as default
        if address.is_default:
            next_default = request.env['customer.address'].sudo().search([
                ('partner_id', '=', partner.id),
                ('active', '=', True)
            ], limit=1)
            if next_default:
                next_default.is_default = True

        return {
            'success': True,
            'message': 'Address deleted successfully'
        }

    @http.route('/api/v1/customer/addresses/<int:address_id>/set-default', type='json', auth='user', methods=['POST'])
    def set_default_address(self, address_id, **kwargs):
        """Set address as default"""
        partner = request.env.user.partner_id
        address = request.env['customer.address'].sudo().search([
            ('id', '=', address_id),
            ('partner_id', '=', partner.id)
        ], limit=1)

        if not address:
            return {'error': 'Address not found', 'code': 404}

        address.set_as_default()

        return {
            'success': True,
            'message': 'Default address updated'
        }
```

**Hour 5-6: Google Places Integration Service**

```python
# File: smart_dairy_ecommerce/services/google_places_service.py

from odoo import models, api
import requests
import logging

_logger = logging.getLogger(__name__)

class GooglePlacesService(models.AbstractModel):
    _name = 'google.places.service'
    _description = 'Google Places API Integration'

    @api.model
    def get_api_key(self):
        """Get Google Places API key from config"""
        return self.env['ir.config_parameter'].sudo().get_param(
            'google.places_api_key', ''
        )

    @api.model
    def autocomplete(self, query, location=None, radius=50000):
        """
        Get place autocomplete suggestions

        Args:
            query: Search text
            location: Optional dict with lat/lng for bias
            radius: Search radius in meters (default 50km)
        """
        api_key = self.get_api_key()
        if not api_key:
            _logger.warning('Google Places API key not configured')
            return []

        url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json'
        params = {
            'input': query,
            'key': api_key,
            'components': 'country:bd',  # Restrict to Bangladesh
            'language': 'en',
            'types': 'address'
        }

        if location:
            params['location'] = f"{location['lat']},{location['lng']}"
            params['radius'] = radius

        try:
            response = requests.get(url, params=params, timeout=5)
            data = response.json()

            if data.get('status') == 'OK':
                return [{
                    'place_id': p['place_id'],
                    'description': p['description'],
                    'main_text': p['structured_formatting'].get('main_text'),
                    'secondary_text': p['structured_formatting'].get('secondary_text'),
                } for p in data.get('predictions', [])]
            else:
                _logger.error(f"Google Places API error: {data.get('status')}")
                return []
        except Exception as e:
            _logger.error(f"Google Places API request failed: {e}")
            return []

    @api.model
    def get_place_details(self, place_id):
        """
        Get detailed place information including coordinates

        Args:
            place_id: Google Place ID
        """
        api_key = self.get_api_key()
        if not api_key:
            return None

        url = 'https://maps.googleapis.com/maps/api/place/details/json'
        params = {
            'place_id': place_id,
            'key': api_key,
            'fields': 'formatted_address,geometry,address_components,name'
        }

        try:
            response = requests.get(url, params=params, timeout=5)
            data = response.json()

            if data.get('status') == 'OK':
                result = data.get('result', {})
                geometry = result.get('geometry', {}).get('location', {})

                # Parse address components
                components = {}
                for component in result.get('address_components', []):
                    types = component.get('types', [])
                    if 'street_number' in types:
                        components['street_number'] = component['long_name']
                    elif 'route' in types:
                        components['route'] = component['long_name']
                    elif 'sublocality_level_1' in types or 'sublocality' in types:
                        components['area'] = component['long_name']
                    elif 'locality' in types:
                        components['city'] = component['long_name']
                    elif 'administrative_area_level_2' in types:
                        components['district'] = component['long_name']
                    elif 'administrative_area_level_1' in types:
                        components['division'] = component['long_name']
                    elif 'postal_code' in types:
                        components['zip_code'] = component['long_name']

                return {
                    'place_id': place_id,
                    'formatted_address': result.get('formatted_address'),
                    'latitude': geometry.get('lat'),
                    'longitude': geometry.get('lng'),
                    'street': f"{components.get('street_number', '')} {components.get('route', '')}".strip(),
                    'area': components.get('area'),
                    'city': components.get('city'),
                    'district': components.get('district'),
                    'division': components.get('division'),
                    'zip_code': components.get('zip_code'),
                }
            return None
        except Exception as e:
            _logger.error(f"Google Places details request failed: {e}")
            return None

    @api.model
    def reverse_geocode(self, lat, lng):
        """
        Get address from coordinates

        Args:
            lat: Latitude
            lng: Longitude
        """
        api_key = self.get_api_key()
        if not api_key:
            return None

        url = 'https://maps.googleapis.com/maps/api/geocode/json'
        params = {
            'latlng': f'{lat},{lng}',
            'key': api_key,
            'language': 'en'
        }

        try:
            response = requests.get(url, params=params, timeout=5)
            data = response.json()

            if data.get('status') == 'OK' and data.get('results'):
                return self.get_place_details(data['results'][0]['place_id'])
            return None
        except Exception as e:
            _logger.error(f"Reverse geocode request failed: {e}")
            return None
```

**Hour 7-8: Places API HTTP Endpoints**

```python
# File: smart_dairy_ecommerce/controllers/places_controller.py

from odoo import http
from odoo.http import request

class PlacesController(http.Controller):

    @http.route('/api/v1/places/autocomplete', type='json', auth='public', methods=['POST'])
    def autocomplete(self, **kwargs):
        """Get address autocomplete suggestions"""
        query = kwargs.get('query', '')
        if len(query) < 3:
            return {'success': True, 'data': []}

        location = None
        if kwargs.get('latitude') and kwargs.get('longitude'):
            location = {
                'lat': kwargs['latitude'],
                'lng': kwargs['longitude']
            }

        service = request.env['google.places.service'].sudo()
        suggestions = service.autocomplete(query, location)

        return {
            'success': True,
            'data': suggestions
        }

    @http.route('/api/v1/places/details', type='json', auth='public', methods=['POST'])
    def get_details(self, **kwargs):
        """Get place details by place_id"""
        place_id = kwargs.get('place_id')
        if not place_id:
            return {'error': 'place_id is required', 'code': 400}

        service = request.env['google.places.service'].sudo()
        details = service.get_place_details(place_id)

        if details:
            return {'success': True, 'data': details}
        return {'error': 'Place not found', 'code': 404}

    @http.route('/api/v1/places/reverse-geocode', type='json', auth='public', methods=['POST'])
    def reverse_geocode(self, **kwargs):
        """Get address from coordinates"""
        lat = kwargs.get('latitude')
        lng = kwargs.get('longitude')

        if lat is None or lng is None:
            return {'error': 'latitude and longitude are required', 'code': 400}

        service = request.env['google.places.service'].sudo()
        address = service.reverse_geocode(lat, lng)

        if address:
            return {'success': True, 'data': address}
        return {'error': 'Could not determine address', 'code': 404}
```

---

#### Dev 3 (Frontend Lead) - Day 361

**Focus: Dashboard UI Foundation**

**Hour 1-2: Customer Dashboard OWL Component Structure**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/customer_dashboard/customer_dashboard.js

import { Component, useState, onWillStart, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { _t } from "@web/core/l10n/translation";

export class CustomerDashboard extends Component {
    static template = "smart_dairy_ecommerce.CustomerDashboard";
    static props = {};

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            error: null,
            customer: null,
            statistics: null,
            loyalty: null,
            referral: null,
            recentOrders: [],
            pendingOrdersCount: 0,
            activeTab: 'overview',
        });

        onWillStart(async () => {
            await this.loadDashboardData();
        });
    }

    async loadDashboardData() {
        try {
            this.state.loading = true;
            this.state.error = null;

            const result = await this.rpc('/api/v1/customer/dashboard', {});

            if (result.success) {
                const data = result.data;
                this.state.customer = data.customer;
                this.state.statistics = data.statistics;
                this.state.loyalty = data.loyalty;
                this.state.referral = data.referral;
                this.state.recentOrders = data.recent_orders;
                this.state.pendingOrdersCount = data.pending_orders_count;
            } else {
                this.state.error = result.error || 'Failed to load dashboard';
            }
        } catch (error) {
            console.error('Dashboard load error:', error);
            this.state.error = 'Failed to connect to server';
        } finally {
            this.state.loading = false;
        }
    }

    setActiveTab(tab) {
        this.state.activeTab = tab;
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('en-BD', {
            style: 'currency',
            currency: 'BDT',
            minimumFractionDigits: 0,
        }).format(amount);
    }

    formatDate(dateString) {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('en-BD', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    getOrderStatusBadgeClass(state) {
        const classes = {
            'draft': 'badge-secondary',
            'sent': 'badge-info',
            'sale': 'badge-primary',
            'done': 'badge-success',
            'cancel': 'badge-danger',
        };
        return classes[state] || 'badge-secondary';
    }

    get loyaltyProgress() {
        if (!this.state.loyalty || !this.state.loyalty.points_to_next_tier) {
            return 100;
        }
        const current = this.state.loyalty.lifetime_points;
        const nextTierMin = current + this.state.loyalty.points_to_next_tier;
        const prevTierMin = this.state.loyalty.tier?.min_points || 0;
        const progress = ((current - prevTierMin) / (nextTierMin - prevTierMin)) * 100;
        return Math.min(Math.max(progress, 0), 100);
    }

    async copyReferralLink() {
        if (this.state.referral?.referral_link) {
            try {
                await navigator.clipboard.writeText(this.state.referral.referral_link);
                this.notification.add(_t("Referral link copied to clipboard!"), {
                    type: "success",
                });
            } catch (err) {
                this.notification.add(_t("Failed to copy link"), {
                    type: "danger",
                });
            }
        }
    }
}
```

**Hour 3-4: Dashboard QWeb Template**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_ecommerce/static/src/xml/customer_dashboard.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_ecommerce.CustomerDashboard">
        <div class="sd-customer-dashboard">
            <!-- Loading State -->
            <t t-if="state.loading">
                <div class="sd-dashboard-loading text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading your dashboard...</p>
                </div>
            </t>

            <!-- Error State -->
            <t t-elif="state.error">
                <div class="alert alert-danger m-4">
                    <i class="fa fa-exclamation-triangle me-2"/>
                    <span t-esc="state.error"/>
                    <button class="btn btn-sm btn-outline-danger ms-3"
                            t-on-click="loadDashboardData">
                        Retry
                    </button>
                </div>
            </t>

            <!-- Dashboard Content -->
            <t t-else="">
                <!-- Welcome Header -->
                <div class="sd-dashboard-header bg-primary text-white p-4 rounded-top">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1">
                                Welcome back, <t t-esc="state.customer?.name"/>!
                            </h2>
                            <p class="mb-0 opacity-75">
                                Customer since <t t-esc="formatDate(state.customer?.member_since)"/>
                                <span class="mx-2">•</span>
                                <span t-esc="state.customer?.customer_code"/>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <t t-if="state.loyalty?.tier">
                                <div class="sd-tier-badge d-inline-block px-3 py-2 rounded"
                                     t-attf-style="background-color: #{state.loyalty.tier.color}20; border: 2px solid #{state.loyalty.tier.color};">
                                    <i t-attf-class="fa #{state.loyalty.tier.icon} me-2"
                                       t-attf-style="color: #{state.loyalty.tier.color};"/>
                                    <span class="fw-bold" t-attf-style="color: #{state.loyalty.tier.color};">
                                        <t t-esc="state.loyalty.tier.name"/> Member
                                    </span>
                                </div>
                            </t>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Cards -->
                <div class="row g-3 p-4">
                    <div class="col-6 col-lg-3">
                        <div class="sd-stat-card card h-100">
                            <div class="card-body text-center">
                                <div class="sd-stat-icon bg-primary-subtle text-primary rounded-circle mx-auto mb-3">
                                    <i class="fa fa-shopping-bag fa-lg"/>
                                </div>
                                <h3 class="mb-1" t-esc="state.statistics?.total_orders || 0"/>
                                <p class="text-muted mb-0 small">Total Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="sd-stat-card card h-100">
                            <div class="card-body text-center">
                                <div class="sd-stat-icon bg-success-subtle text-success rounded-circle mx-auto mb-3">
                                    <i class="fa fa-money fa-lg"/>
                                </div>
                                <h3 class="mb-1" t-esc="formatCurrency(state.statistics?.total_spent || 0)"/>
                                <p class="text-muted mb-0 small">Total Spent</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="sd-stat-card card h-100">
                            <div class="card-body text-center">
                                <div class="sd-stat-icon bg-warning-subtle text-warning rounded-circle mx-auto mb-3">
                                    <i class="fa fa-star fa-lg"/>
                                </div>
                                <h3 class="mb-1" t-esc="state.loyalty?.current_points || 0"/>
                                <p class="text-muted mb-0 small">Loyalty Points</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="sd-stat-card card h-100">
                            <div class="card-body text-center">
                                <div class="sd-stat-icon bg-info-subtle text-info rounded-circle mx-auto mb-3">
                                    <i class="fa fa-truck fa-lg"/>
                                </div>
                                <h3 class="mb-1" t-esc="state.pendingOrdersCount || 0"/>
                                <p class="text-muted mb-0 small">Pending Orders</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs px-4">
                    <li class="nav-item">
                        <a t-attf-class="nav-link #{state.activeTab === 'overview' ? 'active' : ''}"
                           href="#" t-on-click.prevent="() => this.setActiveTab('overview')">
                            <i class="fa fa-dashboard me-1"/> Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a t-attf-class="nav-link #{state.activeTab === 'orders' ? 'active' : ''}"
                           href="#" t-on-click.prevent="() => this.setActiveTab('orders')">
                            <i class="fa fa-list me-1"/> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a t-attf-class="nav-link #{state.activeTab === 'loyalty' ? 'active' : ''}"
                           href="#" t-on-click.prevent="() => this.setActiveTab('loyalty')">
                            <i class="fa fa-gift me-1"/> Loyalty
                        </a>
                    </li>
                    <li class="nav-item">
                        <a t-attf-class="nav-link #{state.activeTab === 'referral' ? 'active' : ''}"
                           href="#" t-on-click.prevent="() => this.setActiveTab('referral')">
                            <i class="fa fa-users me-1"/> Referrals
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content p-4">
                    <!-- Overview Tab -->
                    <t t-if="state.activeTab === 'overview'">
                        <t t-call="smart_dairy_ecommerce.DashboardOverviewTab"/>
                    </t>

                    <!-- Orders Tab -->
                    <t t-if="state.activeTab === 'orders'">
                        <t t-call="smart_dairy_ecommerce.DashboardOrdersTab"/>
                    </t>

                    <!-- Loyalty Tab -->
                    <t t-if="state.activeTab === 'loyalty'">
                        <t t-call="smart_dairy_ecommerce.DashboardLoyaltyTab"/>
                    </t>

                    <!-- Referral Tab -->
                    <t t-if="state.activeTab === 'referral'">
                        <t t-call="smart_dairy_ecommerce.DashboardReferralTab"/>
                    </t>
                </div>
            </t>
        </div>
    </t>

    <!-- Overview Tab Template -->
    <t t-name="smart_dairy_ecommerce.DashboardOverviewTab">
        <div class="row">
            <!-- Recent Orders -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Orders</h5>
                        <a href="/my/orders" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <t t-if="state.recentOrders.length">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order</th>
                                            <th>Date</th>
                                            <th>Items</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <t t-foreach="state.recentOrders" t-as="order" t-key="order.id">
                                            <tr>
                                                <td>
                                                    <a t-attf-href="/my/orders/#{order.id}">
                                                        <t t-esc="order.name"/>
                                                    </a>
                                                </td>
                                                <td t-esc="formatDate(order.date)"/>
                                                <td t-esc="order.item_count"/>
                                                <td t-esc="formatCurrency(order.amount)"/>
                                                <td>
                                                    <span t-attf-class="badge #{getOrderStatusBadgeClass(order.state)}">
                                                        <t t-esc="order.state"/>
                                                    </span>
                                                </td>
                                            </tr>
                                        </t>
                                    </tbody>
                                </table>
                            </div>
                        </t>
                        <t t-else="">
                            <div class="text-center py-4">
                                <i class="fa fa-shopping-cart fa-3x text-muted mb-3"/>
                                <p class="text-muted">No orders yet</p>
                                <a href="/shop" class="btn btn-primary">Start Shopping</a>
                            </div>
                        </t>
                    </div>
                </div>
            </div>

            <!-- Loyalty Summary -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Loyalty Progress</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h2 class="text-primary mb-0">
                                <t t-esc="state.loyalty?.current_points || 0"/>
                            </h2>
                            <p class="text-muted">Available Points</p>
                        </div>

                        <t t-if="state.loyalty?.next_tier_name">
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-primary"
                                     role="progressbar"
                                     t-attf-style="width: #{loyaltyProgress}%"/>
                            </div>
                            <p class="text-muted small text-center">
                                <t t-esc="state.loyalty.points_to_next_tier"/> points to
                                <span class="fw-bold" t-esc="state.loyalty.next_tier_name"/>
                            </p>
                        </t>
                        <t t-else="">
                            <p class="text-success text-center">
                                <i class="fa fa-trophy me-1"/>
                                You've reached the highest tier!
                            </p>
                        </t>

                        <hr/>

                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Lifetime Points:</span>
                            <span class="fw-bold" t-esc="state.loyalty?.lifetime_points || 0"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </t>
</templates>
```

**Hour 5-6: Dashboard SCSS Styles**

```scss
// File: smart_dairy_ecommerce/static/src/scss/customer_dashboard.scss

.sd-customer-dashboard {
    background: #f8f9fa;
    min-height: 100vh;

    .sd-dashboard-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
    }

    .sd-tier-badge {
        backdrop-filter: blur(10px);
    }

    .sd-stat-card {
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s, box-shadow 0.2s;

        &:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sd-stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }

    .nav-tabs {
        border-bottom: 2px solid #e9ecef;

        .nav-link {
            border: none;
            color: #6c757d;
            padding: 1rem 1.5rem;
            font-weight: 500;

            &:hover {
                color: #0d6efd;
                background: transparent;
            }

            &.active {
                color: #0d6efd;
                background: transparent;
                border-bottom: 2px solid #0d6efd;
                margin-bottom: -2px;
            }
        }
    }

    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border-radius: 0.5rem;
    }
}
```

**Hour 7-8: Module Registration & Assets**

```python
# File: smart_dairy_ecommerce/__manifest__.py (updated assets section)

{
    'name': 'Smart Dairy E-Commerce',
    'version': '19.0.1.0.0',
    'category': 'Website/E-commerce',
    'summary': 'B2C E-Commerce Platform for Smart Dairy',
    'depends': ['website_sale', 'sale_management', 'portal'],
    'data': [
        'security/ir.model.access.csv',
        'data/loyalty_tier_data.xml',
        'views/customer_dashboard_templates.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_dairy_ecommerce/static/src/scss/customer_dashboard.scss',
            'smart_dairy_ecommerce/static/src/js/customer_dashboard/**/*.js',
            'smart_dairy_ecommerce/static/src/xml/customer_dashboard.xml',
        ],
    },
    'installable': True,
    'application': True,
    'license': 'LGPL-3',
}
```

```xml
<!-- File: smart_dairy_ecommerce/data/loyalty_tier_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <data noupdate="1">
        <record id="loyalty_tier_bronze" model="loyalty.tier">
            <field name="name">Bronze</field>
            <field name="code">BRONZE</field>
            <field name="min_points">0</field>
            <field name="color">#CD7F32</field>
            <field name="icon">fa-star-o</field>
            <field name="points_multiplier">1.0</field>
            <field name="discount_percentage">0</field>
        </record>

        <record id="loyalty_tier_silver" model="loyalty.tier">
            <field name="name">Silver</field>
            <field name="code">SILVER</field>
            <field name="min_points">1000</field>
            <field name="color">#C0C0C0</field>
            <field name="icon">fa-star-half-o</field>
            <field name="points_multiplier">1.25</field>
            <field name="discount_percentage">5</field>
            <field name="birthday_bonus_points">100</field>
        </record>

        <record id="loyalty_tier_gold" model="loyalty.tier">
            <field name="name">Gold</field>
            <field name="code">GOLD</field>
            <field name="min_points">5000</field>
            <field name="color">#FFD700</field>
            <field name="icon">fa-star</field>
            <field name="points_multiplier">1.5</field>
            <field name="discount_percentage">10</field>
            <field name="priority_support" eval="True"/>
            <field name="birthday_bonus_points">250</field>
        </record>

        <record id="loyalty_tier_platinum" model="loyalty.tier">
            <field name="name">Platinum</field>
            <field name="code">PLATINUM</field>
            <field name="min_points">15000</field>
            <field name="color">#E5E4E2</field>
            <field name="icon">fa-diamond</field>
            <field name="points_multiplier">2.0</field>
            <field name="discount_percentage">15</field>
            <field name="priority_support" eval="True"/>
            <field name="early_access" eval="True"/>
            <field name="birthday_bonus_points">500</field>
        </record>
    </data>
</odoo>
```

---

#### Day 361 End-of-Day Deliverables

| # | Deliverable | Status | Owner |
|---|-------------|--------|-------|
| 1 | Customer model extensions (res.partner) | ✅ | Dev 1 |
| 2 | Loyalty tier model | ✅ | Dev 1 |
| 3 | Loyalty transaction model | ✅ | Dev 1 |
| 4 | Customer dashboard API endpoint | ✅ | Dev 1 |
| 5 | Customer address model | ✅ | Dev 2 |
| 6 | Address CRUD API endpoints | ✅ | Dev 2 |
| 7 | Google Places service integration | ✅ | Dev 2 |
| 8 | Places API HTTP endpoints | ✅ | Dev 2 |
| 9 | Dashboard OWL component scaffold | ✅ | Dev 3 |
| 10 | Dashboard QWeb template | ✅ | Dev 3 |
| 11 | Dashboard SCSS styles | ✅ | Dev 3 |
| 12 | Module manifest & assets config | ✅ | Dev 3 |

---

### Day 362 (Phase Day 12): Loyalty Points Engine & Order History

#### Day 362 Objectives
- Complete loyalty points earning and redemption logic
- Build order history with filtering capabilities
- Create reorder functionality
- Implement loyalty points API endpoints

---

#### Dev 1 (Backend Lead) - Day 362

**Focus: Loyalty Points Earning Engine**

**Hour 1-3: Points Earning Rules Model**

```python
# File: smart_dairy_ecommerce/models/loyalty_earning_rule.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError

class LoyaltyEarningRule(models.Model):
    _name = 'loyalty.earning.rule'
    _description = 'Loyalty Points Earning Rule'
    _order = 'sequence, id'

    name = fields.Char(string='Rule Name', required=True)
    sequence = fields.Integer(string='Sequence', default=10)
    active = fields.Boolean(default=True)

    rule_type = fields.Selection([
        ('purchase', 'Purchase Amount'),
        ('product', 'Specific Product'),
        ('category', 'Product Category'),
        ('first_order', 'First Order Bonus'),
        ('birthday', 'Birthday Bonus'),
        ('review', 'Product Review'),
        ('referral', 'Referral Reward'),
    ], string='Rule Type', required=True, default='purchase')

    # Purchase Rules
    points_per_currency = fields.Float(
        string='Points per BDT',
        default=1.0,
        help='Points earned per 1 BDT spent'
    )
    minimum_amount = fields.Monetary(
        string='Minimum Order Amount',
        currency_field='currency_id'
    )
    maximum_points = fields.Integer(
        string='Maximum Points per Order',
        default=0,
        help='0 = unlimited'
    )

    # Product/Category Rules
    product_ids = fields.Many2many(
        'product.product',
        string='Applicable Products'
    )
    category_ids = fields.Many2many(
        'product.category',
        string='Applicable Categories'
    )
    bonus_points = fields.Integer(
        string='Bonus Points',
        default=0
    )
    bonus_multiplier = fields.Float(
        string='Points Multiplier',
        default=1.0
    )

    # Date Restrictions
    date_from = fields.Date(string='Valid From')
    date_to = fields.Date(string='Valid To')

    # Customer Restrictions
    tier_ids = fields.Many2many(
        'loyalty.tier',
        string='Applicable Tiers',
        help='Leave empty for all tiers'
    )
    new_customer_only = fields.Boolean(
        string='New Customers Only',
        default=False
    )

    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )

    description = fields.Text(string='Description')

    @api.constrains('points_per_currency', 'bonus_multiplier')
    def _check_positive_values(self):
        for rule in self:
            if rule.points_per_currency < 0:
                raise ValidationError('Points per currency must be positive')
            if rule.bonus_multiplier < 0:
                raise ValidationError('Bonus multiplier must be positive')


class LoyaltyPointsService(models.AbstractModel):
    _name = 'loyalty.points.service'
    _description = 'Loyalty Points Calculation Service'

    @api.model
    def calculate_order_points(self, order):
        """
        Calculate loyalty points for an order
        Returns dict with breakdown
        """
        partner = order.partner_id
        if not partner.is_b2c_customer:
            return {'total': 0, 'breakdown': []}

        tier = partner.loyalty_tier_id
        tier_multiplier = tier.points_multiplier if tier else 1.0

        points_breakdown = []
        total_points = 0

        # Get applicable rules
        today = fields.Date.today()
        rules = self.env['loyalty.earning.rule'].search([
            ('active', '=', True),
            '|', ('date_from', '=', False), ('date_from', '<=', today),
            '|', ('date_to', '=', False), ('date_to', '>=', today),
        ], order='sequence')

        for rule in rules:
            # Check tier restrictions
            if rule.tier_ids and tier.id not in rule.tier_ids.ids:
                continue

            # Check new customer restriction
            if rule.new_customer_only and partner.total_orders > 1:
                continue

            points = 0

            if rule.rule_type == 'purchase':
                if order.amount_total >= rule.minimum_amount:
                    points = int(order.amount_total * rule.points_per_currency)
                    if rule.maximum_points and points > rule.maximum_points:
                        points = rule.maximum_points

            elif rule.rule_type == 'product':
                for line in order.order_line:
                    if line.product_id.id in rule.product_ids.ids:
                        points += rule.bonus_points

            elif rule.rule_type == 'category':
                for line in order.order_line:
                    if line.product_id.categ_id.id in rule.category_ids.ids:
                        line_points = int(line.price_subtotal * rule.bonus_multiplier)
                        points += line_points

            elif rule.rule_type == 'first_order':
                if partner.total_orders == 0:
                    points = rule.bonus_points

            if points > 0:
                points_breakdown.append({
                    'rule': rule.name,
                    'points': points,
                    'type': rule.rule_type,
                })
                total_points += points

        # Apply tier multiplier
        final_points = int(total_points * tier_multiplier)

        return {
            'base_points': total_points,
            'tier_multiplier': tier_multiplier,
            'total': final_points,
            'breakdown': points_breakdown,
        }

    @api.model
    def award_order_points(self, order):
        """Award points for a completed order"""
        if order.loyalty_points_awarded:
            return False

        calculation = self.calculate_order_points(order)
        if calculation['total'] > 0:
            self.env['loyalty.transaction'].sudo().create({
                'partner_id': order.partner_id.id,
                'transaction_type': 'earn',
                'points': calculation['total'],
                'order_id': order.id,
                'description': f"Points earned from order {order.name}",
            })
            order.sudo().write({
                'loyalty_points_awarded': True,
                'loyalty_points_amount': calculation['total'],
            })
        return True

    @api.model
    def redeem_points(self, partner_id, points, order_id=None):
        """Redeem loyalty points"""
        partner = self.env['res.partner'].browse(partner_id)

        if points <= 0:
            return {'error': 'Invalid points amount'}

        if partner.loyalty_points < points:
            return {'error': 'Insufficient points balance'}

        # Check minimum redemption
        min_redeem = int(self.env['ir.config_parameter'].sudo().get_param(
            'loyalty.min_redemption_points', '100'
        ))
        if points < min_redeem:
            return {'error': f'Minimum redemption is {min_redeem} points'}

        # Calculate discount value
        points_value = float(self.env['ir.config_parameter'].sudo().get_param(
            'loyalty.points_to_currency_rate', '0.1'
        ))
        discount_amount = points * points_value

        self.env['loyalty.transaction'].sudo().create({
            'partner_id': partner_id,
            'transaction_type': 'redeem',
            'points': -points,
            'order_id': order_id,
            'description': f"Points redeemed for BDT {discount_amount:.2f} discount",
        })

        return {
            'success': True,
            'points_redeemed': points,
            'discount_amount': discount_amount,
            'new_balance': partner.loyalty_points,
        }
```

**Hour 4-5: Sale Order Loyalty Integration**

```python
# File: smart_dairy_ecommerce/models/sale_order_loyalty.py

from odoo import models, fields, api

class SaleOrder(models.Model):
    _inherit = 'sale.order'

    # Loyalty Points
    loyalty_points_awarded = fields.Boolean(
        string='Points Awarded',
        default=False,
        copy=False
    )
    loyalty_points_amount = fields.Integer(
        string='Points Earned',
        default=0,
        copy=False
    )
    loyalty_points_redeemed = fields.Integer(
        string='Points Redeemed',
        default=0,
        copy=False
    )
    loyalty_discount = fields.Monetary(
        string='Loyalty Discount',
        currency_field='currency_id',
        default=0,
        copy=False
    )

    # Referral Tracking
    referral_code_used = fields.Char(
        string='Referral Code Used',
        copy=False
    )
    referred_by_id = fields.Many2one(
        'res.partner',
        string='Referred By',
        copy=False
    )

    def _compute_potential_points(self):
        """Calculate potential points before order completion"""
        service = self.env['loyalty.points.service']
        for order in self:
            if order.state in ('draft', 'sent'):
                calculation = service.calculate_order_points(order)
                order.potential_points = calculation['total']
            else:
                order.potential_points = 0

    potential_points = fields.Integer(
        string='Potential Points',
        compute='_compute_potential_points'
    )

    def action_confirm(self):
        """Override to handle referral tracking"""
        res = super().action_confirm()
        for order in self:
            if order.referral_code_used:
                referrer = self.env['res.partner'].search([
                    ('referral_code', '=', order.referral_code_used),
                    ('is_b2c_customer', '=', True)
                ], limit=1)
                if referrer and referrer.id != order.partner_id.id:
                    order.referred_by_id = referrer
                    # First order from referred customer
                    if order.partner_id.total_orders == 0:
                        order.partner_id.referred_by_id = referrer
        return res

    def _action_done(self):
        """Award points when order is completed"""
        res = super()._action_done()
        service = self.env['loyalty.points.service']
        for order in self:
            # Award purchase points
            service.award_order_points(order)

            # Award referral points if applicable
            if order.referred_by_id and not order.partner_id.referred_by_id.id == order.referred_by_id.id:
                # This is a repeat order, skip referral bonus
                pass
            elif order.referred_by_id:
                referral_points = int(self.env['ir.config_parameter'].sudo().get_param(
                    'loyalty.referral_reward_points', '500'
                ))
                self.env['loyalty.transaction'].sudo().create({
                    'partner_id': order.referred_by_id.id,
                    'transaction_type': 'referral',
                    'points': referral_points,
                    'referred_partner_id': order.partner_id.id,
                    'description': f"Referral reward for {order.partner_id.name}'s first order",
                })
        return res

    def apply_loyalty_points(self, points_to_redeem):
        """Apply loyalty points as discount"""
        self.ensure_one()
        if self.state != 'draft':
            return {'error': 'Can only apply points to draft orders'}

        service = self.env['loyalty.points.service']
        result = service.redeem_points(
            self.partner_id.id,
            points_to_redeem,
            self.id
        )

        if result.get('success'):
            self.write({
                'loyalty_points_redeemed': points_to_redeem,
                'loyalty_discount': result['discount_amount'],
            })
            # Create discount line
            self._create_loyalty_discount_line(result['discount_amount'])

        return result

    def _create_loyalty_discount_line(self, amount):
        """Create order line for loyalty discount"""
        discount_product = self.env.ref(
            'smart_dairy_ecommerce.product_loyalty_discount',
            raise_if_not_found=False
        )
        if not discount_product:
            discount_product = self.env['product.product'].create({
                'name': 'Loyalty Points Discount',
                'type': 'service',
                'list_price': 0,
                'sale_ok': True,
                'purchase_ok': False,
            })

        self.env['sale.order.line'].create({
            'order_id': self.id,
            'product_id': discount_product.id,
            'name': f'Loyalty Discount ({self.loyalty_points_redeemed} points)',
            'product_uom_qty': 1,
            'price_unit': -amount,
        })
```

**Hour 6-8: Loyalty API Endpoints**

```python
# File: smart_dairy_ecommerce/controllers/loyalty_controller.py

from odoo import http
from odoo.http import request

class LoyaltyController(http.Controller):

    @http.route('/api/v1/customer/loyalty/balance', type='json', auth='user', methods=['GET'])
    def get_balance(self, **kwargs):
        """Get loyalty points balance and tier info"""
        partner = request.env.user.partner_id

        tier = partner.loyalty_tier_id
        next_tier = request.env['loyalty.tier'].sudo().search([
            ('min_points', '>', partner.lifetime_points)
        ], limit=1, order='min_points asc')

        return {
            'success': True,
            'data': {
                'current_points': partner.loyalty_points,
                'lifetime_points': partner.lifetime_points,
                'tier': {
                    'name': tier.name if tier else 'None',
                    'code': tier.code if tier else None,
                    'color': tier.color if tier else None,
                    'multiplier': tier.points_multiplier if tier else 1.0,
                    'discount': tier.discount_percentage if tier else 0,
                } if tier else None,
                'next_tier': {
                    'name': next_tier.name,
                    'points_needed': next_tier.min_points - partner.lifetime_points,
                } if next_tier else None,
                'points_value_rate': float(request.env['ir.config_parameter'].sudo().get_param(
                    'loyalty.points_to_currency_rate', '0.1'
                )),
            }
        }

    @http.route('/api/v1/customer/loyalty/history', type='json', auth='user', methods=['GET'])
    def get_history(self, **kwargs):
        """Get loyalty points transaction history"""
        partner = request.env.user.partner_id
        page = kwargs.get('page', 1)
        limit = min(kwargs.get('limit', 20), 50)
        offset = (page - 1) * limit

        domain = [('partner_id', '=', partner.id)]

        # Filter by type
        if kwargs.get('type'):
            domain.append(('transaction_type', '=', kwargs['type']))

        transactions = request.env['loyalty.transaction'].sudo().search(
            domain, limit=limit, offset=offset, order='create_date desc'
        )
        total = request.env['loyalty.transaction'].sudo().search_count(domain)

        return {
            'success': True,
            'data': {
                'transactions': [{
                    'id': t.id,
                    'type': t.transaction_type,
                    'points': t.points,
                    'balance_after': t.balance_after,
                    'description': t.description,
                    'date': t.create_date.isoformat(),
                    'order_name': t.order_id.name if t.order_id else None,
                } for t in transactions],
                'pagination': {
                    'page': page,
                    'limit': limit,
                    'total': total,
                    'pages': (total + limit - 1) // limit,
                }
            }
        }

    @http.route('/api/v1/customer/loyalty/calculate', type='json', auth='user', methods=['POST'])
    def calculate_points(self, **kwargs):
        """Calculate potential points for cart amount"""
        amount = kwargs.get('amount', 0)
        partner = request.env.user.partner_id

        if amount <= 0:
            return {'success': True, 'data': {'points': 0}}

        # Create temporary order for calculation
        tier = partner.loyalty_tier_id
        tier_multiplier = tier.points_multiplier if tier else 1.0

        # Base calculation
        base_rate = float(request.env['ir.config_parameter'].sudo().get_param(
            'loyalty.base_points_per_currency', '1.0'
        ))
        base_points = int(amount * base_rate)
        total_points = int(base_points * tier_multiplier)

        return {
            'success': True,
            'data': {
                'base_points': base_points,
                'multiplier': tier_multiplier,
                'total_points': total_points,
            }
        }

    @http.route('/api/v1/customer/loyalty/redeem', type='json', auth='user', methods=['POST'])
    def redeem_points(self, **kwargs):
        """Redeem points for order discount"""
        points = kwargs.get('points', 0)
        order_id = kwargs.get('order_id')

        if not order_id:
            return {'error': 'order_id is required', 'code': 400}

        order = request.env['sale.order'].sudo().browse(order_id)
        if not order.exists() or order.partner_id.id != request.env.user.partner_id.id:
            return {'error': 'Order not found', 'code': 404}

        result = order.apply_loyalty_points(points)
        return result if 'error' not in result else {'error': result['error'], 'code': 400}
```

---

#### Dev 2 (Full-Stack) - Day 362

**Focus: Order History Backend**

**Hour 1-4: Order History API**

```python
# File: smart_dairy_ecommerce/controllers/order_history_controller.py

from odoo import http
from odoo.http import request
from datetime import datetime, timedelta

class OrderHistoryController(http.Controller):

    @http.route('/api/v1/customer/orders', type='json', auth='user', methods=['GET'])
    def get_orders(self, **kwargs):
        """Get paginated order history with filters"""
        partner = request.env.user.partner_id
        page = kwargs.get('page', 1)
        limit = min(kwargs.get('limit', 10), 50)
        offset = (page - 1) * limit

        domain = [('partner_id', '=', partner.id)]

        # Status filter
        status = kwargs.get('status')
        if status:
            if status == 'pending':
                domain.append(('state', 'in', ['draft', 'sent']))
            elif status == 'processing':
                domain.append(('state', '=', 'sale'))
            elif status == 'completed':
                domain.append(('state', '=', 'done'))
            elif status == 'cancelled':
                domain.append(('state', '=', 'cancel'))

        # Date range filter
        date_from = kwargs.get('date_from')
        date_to = kwargs.get('date_to')
        if date_from:
            domain.append(('date_order', '>=', date_from))
        if date_to:
            domain.append(('date_order', '<=', date_to))

        # Quick date filters
        date_filter = kwargs.get('date_filter')
        if date_filter:
            today = datetime.now().date()
            if date_filter == 'last_30_days':
                domain.append(('date_order', '>=', today - timedelta(days=30)))
            elif date_filter == 'last_90_days':
                domain.append(('date_order', '>=', today - timedelta(days=90)))
            elif date_filter == 'this_year':
                domain.append(('date_order', '>=', datetime(today.year, 1, 1)))

        # Search by order number
        search = kwargs.get('search')
        if search:
            domain.append(('name', 'ilike', search))

        # Sort
        sort = kwargs.get('sort', 'date_desc')
        order_map = {
            'date_desc': 'date_order desc',
            'date_asc': 'date_order asc',
            'amount_desc': 'amount_total desc',
            'amount_asc': 'amount_total asc',
        }
        order = order_map.get(sort, 'date_order desc')

        orders = request.env['sale.order'].sudo().search(
            domain, limit=limit, offset=offset, order=order
        )
        total = request.env['sale.order'].sudo().search_count(domain)

        return {
            'success': True,
            'data': {
                'orders': [self._format_order_summary(o) for o in orders],
                'pagination': {
                    'page': page,
                    'limit': limit,
                    'total': total,
                    'pages': (total + limit - 1) // limit,
                }
            }
        }

    @http.route('/api/v1/customer/orders/<int:order_id>', type='json', auth='user', methods=['GET'])
    def get_order_detail(self, order_id, **kwargs):
        """Get detailed order information"""
        partner = request.env.user.partner_id
        order = request.env['sale.order'].sudo().search([
            ('id', '=', order_id),
            ('partner_id', '=', partner.id)
        ], limit=1)

        if not order:
            return {'error': 'Order not found', 'code': 404}

        return {
            'success': True,
            'data': self._format_order_detail(order)
        }

    @http.route('/api/v1/customer/orders/<int:order_id>/reorder', type='json', auth='user', methods=['POST'])
    def reorder(self, order_id, **kwargs):
        """Create new order from previous order"""
        partner = request.env.user.partner_id
        original_order = request.env['sale.order'].sudo().search([
            ('id', '=', order_id),
            ('partner_id', '=', partner.id),
            ('state', 'in', ['sale', 'done'])
        ], limit=1)

        if not original_order:
            return {'error': 'Order not found', 'code': 404}

        # Create new draft order
        new_order = request.env['sale.order'].sudo().create({
            'partner_id': partner.id,
            'origin': f'Reorder from {original_order.name}',
        })

        # Copy order lines
        unavailable_products = []
        for line in original_order.order_line:
            product = line.product_id
            if not product.active or not product.sale_ok:
                unavailable_products.append(product.name)
                continue

            # Check stock
            if product.type == 'product':
                available_qty = product.with_context(warehouse=new_order.warehouse_id.id).free_qty
                qty = min(line.product_uom_qty, available_qty) if available_qty > 0 else 0
                if qty == 0:
                    unavailable_products.append(f"{product.name} (out of stock)")
                    continue
            else:
                qty = line.product_uom_qty

            request.env['sale.order.line'].sudo().create({
                'order_id': new_order.id,
                'product_id': product.id,
                'product_uom_qty': qty,
                'product_uom': line.product_uom.id,
            })

        if not new_order.order_line:
            new_order.unlink()
            return {
                'error': 'No products available for reorder',
                'unavailable': unavailable_products,
                'code': 400
            }

        return {
            'success': True,
            'data': {
                'order_id': new_order.id,
                'order_name': new_order.name,
                'items_count': len(new_order.order_line),
                'unavailable_products': unavailable_products,
            },
            'message': 'Order created successfully' + (
                f'. {len(unavailable_products)} products unavailable.' if unavailable_products else ''
            )
        }

    def _format_order_summary(self, order):
        """Format order for list view"""
        return {
            'id': order.id,
            'name': order.name,
            'date': order.date_order.isoformat() if order.date_order else None,
            'state': order.state,
            'state_label': dict(order._fields['state'].selection).get(order.state),
            'amount_total': order.amount_total,
            'currency': order.currency_id.symbol,
            'items_count': len(order.order_line),
            'items_preview': [
                {
                    'name': line.product_id.name,
                    'image_url': f'/web/image/product.product/{line.product_id.id}/image_128',
                } for line in order.order_line[:3]
            ],
            'can_reorder': order.state in ('sale', 'done'),
            'loyalty_points_earned': order.loyalty_points_amount,
        }

    def _format_order_detail(self, order):
        """Format order for detail view"""
        return {
            'id': order.id,
            'name': order.name,
            'date': order.date_order.isoformat() if order.date_order else None,
            'state': order.state,
            'state_label': dict(order._fields['state'].selection).get(order.state),

            # Amounts
            'amount_untaxed': order.amount_untaxed,
            'amount_tax': order.amount_tax,
            'amount_total': order.amount_total,
            'currency': order.currency_id.symbol,
            'loyalty_discount': order.loyalty_discount,

            # Lines
            'lines': [{
                'id': line.id,
                'product_id': line.product_id.id,
                'product_name': line.product_id.name,
                'product_image': f'/web/image/product.product/{line.product_id.id}/image_256',
                'quantity': line.product_uom_qty,
                'uom': line.product_uom.name,
                'unit_price': line.price_unit,
                'subtotal': line.price_subtotal,
                'discount': line.discount,
            } for line in order.order_line if line.product_id],

            # Shipping
            'shipping_address': self._format_address(order.partner_shipping_id),
            'billing_address': self._format_address(order.partner_invoice_id),

            # Payment
            'payment_method': order.payment_term_id.name if order.payment_term_id else None,

            # Loyalty
            'loyalty_points_earned': order.loyalty_points_amount,
            'loyalty_points_redeemed': order.loyalty_points_redeemed,

            # Actions
            'can_reorder': order.state in ('sale', 'done'),
            'can_cancel': order.state in ('draft', 'sent'),
            'can_modify': order.state == 'draft',
        }

    def _format_address(self, partner):
        if not partner:
            return None
        return {
            'name': partner.name,
            'street': partner.street,
            'street2': partner.street2,
            'city': partner.city,
            'phone': partner.phone,
        }
```

**Hour 5-8: Order Tracking & Status Updates**

```python
# File: smart_dairy_ecommerce/models/order_tracking.py

from odoo import models, fields, api
from datetime import datetime

class SaleOrderTracking(models.Model):
    _name = 'sale.order.tracking'
    _description = 'Order Tracking History'
    _order = 'create_date desc'

    order_id = fields.Many2one(
        'sale.order',
        string='Order',
        required=True,
        ondelete='cascade',
        index=True
    )
    status = fields.Selection([
        ('confirmed', 'Order Confirmed'),
        ('processing', 'Processing'),
        ('packed', 'Packed'),
        ('shipped', 'Shipped'),
        ('out_for_delivery', 'Out for Delivery'),
        ('delivered', 'Delivered'),
        ('cancelled', 'Cancelled'),
        ('returned', 'Returned'),
    ], string='Status', required=True)

    description = fields.Char(string='Description')
    location = fields.Char(string='Location')
    notes = fields.Text(string='Notes')

    # Carrier Info
    carrier_id = fields.Many2one('delivery.carrier', string='Carrier')
    tracking_number = fields.Char(string='Tracking Number')
    tracking_url = fields.Char(string='Tracking URL')

    # Timestamps
    timestamp = fields.Datetime(
        string='Event Time',
        default=fields.Datetime.now
    )

    created_by_id = fields.Many2one(
        'res.users',
        string='Created By',
        default=lambda self: self.env.user
    )


class SaleOrder(models.Model):
    _inherit = 'sale.order'

    # Tracking Fields
    tracking_ids = fields.One2many(
        'sale.order.tracking',
        'order_id',
        string='Tracking History'
    )
    current_tracking_status = fields.Selection([
        ('confirmed', 'Order Confirmed'),
        ('processing', 'Processing'),
        ('packed', 'Packed'),
        ('shipped', 'Shipped'),
        ('out_for_delivery', 'Out for Delivery'),
        ('delivered', 'Delivered'),
        ('cancelled', 'Cancelled'),
        ('returned', 'Returned'),
    ], string='Tracking Status', compute='_compute_tracking_status', store=True)

    delivery_tracking_number = fields.Char(string='Tracking Number')
    estimated_delivery_date = fields.Date(string='Estimated Delivery')

    @api.depends('tracking_ids.status')
    def _compute_tracking_status(self):
        for order in self:
            latest = order.tracking_ids[:1]
            order.current_tracking_status = latest.status if latest else False

    def add_tracking_event(self, status, description=None, **kwargs):
        """Add a tracking event to the order"""
        self.ensure_one()
        return self.env['sale.order.tracking'].create({
            'order_id': self.id,
            'status': status,
            'description': description or dict(
                self.env['sale.order.tracking']._fields['status'].selection
            ).get(status),
            'location': kwargs.get('location'),
            'notes': kwargs.get('notes'),
            'carrier_id': kwargs.get('carrier_id'),
            'tracking_number': kwargs.get('tracking_number'),
            'tracking_url': kwargs.get('tracking_url'),
            'timestamp': kwargs.get('timestamp', fields.Datetime.now()),
        })

    def action_confirm(self):
        res = super().action_confirm()
        for order in self:
            order.add_tracking_event('confirmed', 'Your order has been confirmed')
        return res
```

---

#### Dev 3 (Frontend Lead) - Day 362

**Focus: Order History UI Components**

**Hour 1-4: Order History OWL Component**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/order_history/order_history.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class OrderHistory extends Component {
    static template = "smart_dairy_ecommerce.OrderHistory";

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            orders: [],
            pagination: { page: 1, limit: 10, total: 0, pages: 0 },
            filters: {
                status: '',
                dateFilter: '',
                search: '',
                sort: 'date_desc',
            },
            selectedOrder: null,
            showDetail: false,
            reordering: false,
        });

        onWillStart(async () => {
            await this.loadOrders();
        });
    }

    async loadOrders(page = 1) {
        try {
            this.state.loading = true;
            const result = await this.rpc('/api/v1/customer/orders', {
                page,
                limit: this.state.pagination.limit,
                status: this.state.filters.status || undefined,
                date_filter: this.state.filters.dateFilter || undefined,
                search: this.state.filters.search || undefined,
                sort: this.state.filters.sort,
            });

            if (result.success) {
                this.state.orders = result.data.orders;
                this.state.pagination = result.data.pagination;
            }
        } catch (error) {
            this.notification.add("Failed to load orders", { type: "danger" });
        } finally {
            this.state.loading = false;
        }
    }

    async viewOrderDetail(orderId) {
        try {
            const result = await this.rpc(`/api/v1/customer/orders/${orderId}`, {});
            if (result.success) {
                this.state.selectedOrder = result.data;
                this.state.showDetail = true;
            }
        } catch (error) {
            this.notification.add("Failed to load order details", { type: "danger" });
        }
    }

    closeDetail() {
        this.state.showDetail = false;
        this.state.selectedOrder = null;
    }

    async reorder(orderId) {
        if (this.state.reordering) return;

        try {
            this.state.reordering = true;
            const result = await this.rpc(`/api/v1/customer/orders/${orderId}/reorder`, {});

            if (result.success) {
                this.notification.add(result.message, { type: "success" });
                window.location.href = `/shop/cart`;
            } else {
                this.notification.add(result.error, { type: "warning" });
            }
        } catch (error) {
            this.notification.add("Failed to create reorder", { type: "danger" });
        } finally {
            this.state.reordering = false;
        }
    }

    applyFilters() {
        this.loadOrders(1);
    }

    clearFilters() {
        this.state.filters = {
            status: '',
            dateFilter: '',
            search: '',
            sort: 'date_desc',
        };
        this.loadOrders(1);
    }

    goToPage(page) {
        if (page >= 1 && page <= this.state.pagination.pages) {
            this.loadOrders(page);
        }
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('en-BD', {
            style: 'currency',
            currency: 'BDT',
            minimumFractionDigits: 0,
        }).format(amount);
    }

    formatDate(dateString) {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('en-BD', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    }

    getStatusClass(state) {
        const classes = {
            'draft': 'bg-secondary',
            'sent': 'bg-info',
            'sale': 'bg-primary',
            'done': 'bg-success',
            'cancel': 'bg-danger',
        };
        return classes[state] || 'bg-secondary';
    }
}
```

**Hour 5-8: Order History QWeb Template**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_ecommerce/static/src/xml/order_history.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_ecommerce.OrderHistory">
        <div class="sd-order-history">
            <!-- Filters Bar -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small">Status</label>
                            <select class="form-select" t-model="state.filters.status">
                                <option value="">All Orders</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Date Range</label>
                            <select class="form-select" t-model="state.filters.dateFilter">
                                <option value="">All Time</option>
                                <option value="last_30_days">Last 30 Days</option>
                                <option value="last_90_days">Last 90 Days</option>
                                <option value="this_year">This Year</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Search</label>
                            <input type="text" class="form-control"
                                   placeholder="Order number..."
                                   t-model="state.filters.search"/>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary me-2" t-on-click="applyFilters">
                                <i class="fa fa-search me-1"/> Search
                            </button>
                            <button class="btn btn-outline-secondary" t-on-click="clearFilters">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading -->
            <t t-if="state.loading">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"/>
                </div>
            </t>

            <!-- Orders List -->
            <t t-elif="state.orders.length">
                <div class="sd-orders-list">
                    <t t-foreach="state.orders" t-as="order" t-key="order.id">
                        <div class="card mb-3 sd-order-card">
                            <div class="card-header bg-light">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <span class="fw-bold" t-esc="order.name"/>
                                        <span class="text-muted ms-3">
                                            <t t-esc="formatDate(order.date)"/>
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <span t-attf-class="badge #{getStatusClass(order.state)}">
                                            <t t-esc="order.state_label"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex gap-2">
                                            <t t-foreach="order.items_preview" t-as="item" t-key="item_index">
                                                <img t-att-src="item.image_url"
                                                     t-att-alt="item.name"
                                                     class="sd-order-item-thumb rounded"
                                                     width="48" height="48"/>
                                            </t>
                                            <t t-if="order.items_count > 3">
                                                <div class="sd-order-more-items rounded d-flex align-items-center justify-content-center">
                                                    +<t t-esc="order.items_count - 3"/>
                                                </div>
                                            </t>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">
                                            <t t-esc="order.items_count"/> items
                                        </p>
                                    </div>
                                    <div class="col-md-3 text-md-center">
                                        <p class="h5 mb-0">
                                            <t t-esc="formatCurrency(order.amount_total)"/>
                                        </p>
                                        <t t-if="order.loyalty_points_earned">
                                            <p class="text-success small mb-0">
                                                +<t t-esc="order.loyalty_points_earned"/> points
                                            </p>
                                        </t>
                                    </div>
                                    <div class="col-md-3 text-md-end">
                                        <button class="btn btn-outline-primary btn-sm me-2"
                                                t-on-click="() => this.viewOrderDetail(order.id)">
                                            <i class="fa fa-eye me-1"/> View
                                        </button>
                                        <t t-if="order.can_reorder">
                                            <button class="btn btn-primary btn-sm"
                                                    t-att-disabled="state.reordering"
                                                    t-on-click="() => this.reorder(order.id)">
                                                <i class="fa fa-refresh me-1"/> Reorder
                                            </button>
                                        </t>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </t>
                </div>

                <!-- Pagination -->
                <t t-if="state.pagination.pages > 1">
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li t-attf-class="page-item #{state.pagination.page === 1 ? 'disabled' : ''}">
                                <a class="page-link" href="#"
                                   t-on-click.prevent="() => this.goToPage(state.pagination.page - 1)">
                                    Previous
                                </a>
                            </li>
                            <t t-foreach="Array.from({length: state.pagination.pages}, (_, i) => i + 1)" t-as="p" t-key="p">
                                <li t-attf-class="page-item #{state.pagination.page === p ? 'active' : ''}">
                                    <a class="page-link" href="#"
                                       t-on-click.prevent="() => this.goToPage(p)">
                                        <t t-esc="p"/>
                                    </a>
                                </li>
                            </t>
                            <li t-attf-class="page-item #{state.pagination.page === state.pagination.pages ? 'disabled' : ''}">
                                <a class="page-link" href="#"
                                   t-on-click.prevent="() => this.goToPage(state.pagination.page + 1)">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </t>
            </t>

            <!-- Empty State -->
            <t t-else="">
                <div class="text-center py-5">
                    <i class="fa fa-shopping-bag fa-4x text-muted mb-3"/>
                    <h4>No orders found</h4>
                    <p class="text-muted">Start shopping to see your orders here</p>
                    <a href="/shop" class="btn btn-primary">Browse Products</a>
                </div>
            </t>

            <!-- Order Detail Modal -->
            <t t-if="state.showDetail and state.selectedOrder">
                <t t-call="smart_dairy_ecommerce.OrderDetailModal"/>
            </t>
        </div>
    </t>

    <!-- Order Detail Modal Template -->
    <t t-name="smart_dairy_ecommerce.OrderDetailModal">
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Order <t t-esc="state.selectedOrder.name"/>
                        </h5>
                        <button type="button" class="btn-close" t-on-click="closeDetail"/>
                    </div>
                    <div class="modal-body">
                        <!-- Order Items -->
                        <h6 class="mb-3">Items</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <t t-foreach="state.selectedOrder.lines" t-as="line" t-key="line.id">
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img t-att-src="line.product_image"
                                                         class="rounded me-2"
                                                         width="40" height="40"/>
                                                    <span t-esc="line.product_name"/>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <t t-esc="line.quantity"/>
                                                <t t-esc="line.uom"/>
                                            </td>
                                            <td class="text-end" t-esc="formatCurrency(line.unit_price)"/>
                                            <td class="text-end" t-esc="formatCurrency(line.subtotal)"/>
                                        </tr>
                                    </t>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end">Subtotal:</td>
                                        <td class="text-end" t-esc="formatCurrency(state.selectedOrder.amount_untaxed)"/>
                                    </tr>
                                    <tr t-if="state.selectedOrder.loyalty_discount">
                                        <td colspan="3" class="text-end text-success">Loyalty Discount:</td>
                                        <td class="text-end text-success">
                                            -<t t-esc="formatCurrency(state.selectedOrder.loyalty_discount)"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">Tax:</td>
                                        <td class="text-end" t-esc="formatCurrency(state.selectedOrder.amount_tax)"/>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end">Total:</td>
                                        <td class="text-end" t-esc="formatCurrency(state.selectedOrder.amount_total)"/>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Shipping Address -->
                        <t t-if="state.selectedOrder.shipping_address">
                            <h6 class="mt-4 mb-3">Shipping Address</h6>
                            <address class="mb-0">
                                <strong t-esc="state.selectedOrder.shipping_address.name"/><br/>
                                <t t-esc="state.selectedOrder.shipping_address.street"/><br/>
                                <t t-if="state.selectedOrder.shipping_address.street2">
                                    <t t-esc="state.selectedOrder.shipping_address.street2"/><br/>
                                </t>
                                <t t-esc="state.selectedOrder.shipping_address.city"/><br/>
                                <i class="fa fa-phone me-1"/>
                                <t t-esc="state.selectedOrder.shipping_address.phone"/>
                            </address>
                        </t>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" t-on-click="closeDetail">Close</button>
                        <t t-if="state.selectedOrder.can_reorder">
                            <button class="btn btn-primary"
                                    t-att-disabled="state.reordering"
                                    t-on-click="() => this.reorder(state.selectedOrder.id)">
                                <i class="fa fa-refresh me-1"/> Reorder
                            </button>
                        </t>
                    </div>
                </div>
            </div>
        </div>
    </t>
</templates>
```

---

#### Day 362 End-of-Day Deliverables

| # | Deliverable | Status | Owner |
|---|-------------|--------|-------|
| 1 | Loyalty earning rules model | ✅ | Dev 1 |
| 2 | Points calculation service | ✅ | Dev 1 |
| 3 | Sale order loyalty integration | ✅ | Dev 1 |
| 4 | Loyalty API endpoints | ✅ | Dev 1 |
| 5 | Order history API with filters | ✅ | Dev 2 |
| 6 | Reorder functionality | ✅ | Dev 2 |
| 7 | Order tracking model | ✅ | Dev 2 |
| 8 | Order history OWL component | ✅ | Dev 3 |
| 9 | Order history QWeb template | ✅ | Dev 3 |
| 10 | Order detail modal | ✅ | Dev 3 |

---

### Day 363 (Phase Day 13): Address Book UI & Referral System

#### Day 363 Objectives
- Complete address book frontend with GPS autocomplete
- Implement referral program backend
- Build notification preferences system

---

#### Dev 1 (Backend Lead) - Day 363

**Focus: Referral Program Implementation**

**Hour 1-4: Referral System Models**

```python
# File: smart_dairy_ecommerce/models/referral_program.py

from odoo import models, fields, api
from datetime import datetime, timedelta

class ReferralReward(models.Model):
    _name = 'referral.reward'
    _description = 'Referral Reward Configuration'

    name = fields.Char(string='Reward Name', required=True)
    active = fields.Boolean(default=True)

    reward_type = fields.Selection([
        ('points', 'Loyalty Points'),
        ('discount', 'Discount Coupon'),
        ('cashback', 'Cashback'),
    ], string='Reward Type', default='points', required=True)

    # For referrer (existing customer)
    referrer_points = fields.Integer(string='Referrer Points', default=500)
    referrer_discount = fields.Float(string='Referrer Discount %', default=10)

    # For referee (new customer)
    referee_points = fields.Integer(string='New Customer Points', default=200)
    referee_discount = fields.Float(string='New Customer Discount %', default=15)

    # Conditions
    min_order_amount = fields.Monetary(
        string='Minimum First Order',
        currency_field='currency_id',
        default=500
    )
    max_referrals_per_month = fields.Integer(
        string='Max Referrals per Month',
        default=10,
        help='0 = unlimited'
    )
    reward_on = fields.Selection([
        ('signup', 'On Signup'),
        ('first_order', 'On First Order'),
    ], string='Reward On', default='first_order')

    currency_id = fields.Many2one(
        'res.currency',
        default=lambda self: self.env.company.currency_id
    )


class ReferralTracking(models.Model):
    _name = 'referral.tracking'
    _description = 'Referral Tracking'
    _order = 'create_date desc'

    referrer_id = fields.Many2one(
        'res.partner',
        string='Referrer',
        required=True,
        index=True
    )
    referee_id = fields.Many2one(
        'res.partner',
        string='Referred Customer',
        required=True,
        index=True
    )
    referral_code = fields.Char(
        string='Code Used',
        required=True
    )

    status = fields.Selection([
        ('pending', 'Pending'),
        ('qualified', 'Qualified'),
        ('rewarded', 'Rewarded'),
        ('expired', 'Expired'),
    ], string='Status', default='pending', index=True)

    # Reward tracking
    referrer_reward_type = fields.Selection([
        ('points', 'Points'),
        ('discount', 'Discount'),
        ('cashback', 'Cashback'),
    ], string='Referrer Reward Type')
    referrer_reward_amount = fields.Float(string='Referrer Reward')
    referrer_reward_date = fields.Datetime(string='Referrer Rewarded On')

    referee_reward_type = fields.Selection([
        ('points', 'Points'),
        ('discount', 'Discount'),
        ('cashback', 'Cashback'),
    ], string='Referee Reward Type')
    referee_reward_amount = fields.Float(string='Referee Reward')
    referee_reward_date = fields.Datetime(string='Referee Rewarded On')

    # Order reference
    qualifying_order_id = fields.Many2one(
        'sale.order',
        string='Qualifying Order'
    )

    @api.model
    def process_referral_signup(self, referee_partner, referral_code):
        """Process referral when new customer signs up"""
        referrer = self.env['res.partner'].search([
            ('referral_code', '=', referral_code),
            ('is_b2c_customer', '=', True)
        ], limit=1)

        if not referrer or referrer.id == referee_partner.id:
            return False

        # Check existing referral
        existing = self.search([
            ('referee_id', '=', referee_partner.id)
        ], limit=1)
        if existing:
            return False

        # Check monthly limit
        reward_config = self.env['referral.reward'].search([
            ('active', '=', True)
        ], limit=1)

        if reward_config and reward_config.max_referrals_per_month > 0:
            month_start = datetime.now().replace(day=1, hour=0, minute=0, second=0)
            month_referrals = self.search_count([
                ('referrer_id', '=', referrer.id),
                ('create_date', '>=', month_start),
                ('status', '!=', 'expired')
            ])
            if month_referrals >= reward_config.max_referrals_per_month:
                return False

        # Create tracking record
        tracking = self.create({
            'referrer_id': referrer.id,
            'referee_id': referee_partner.id,
            'referral_code': referral_code,
            'status': 'pending',
        })

        # Link referee to referrer
        referee_partner.write({
            'referred_by_id': referrer.id
        })

        # Award signup rewards if configured
        if reward_config and reward_config.reward_on == 'signup':
            tracking._award_rewards(reward_config)

        return tracking

    def _award_rewards(self, reward_config):
        """Award referral rewards to both parties"""
        self.ensure_one()

        if self.status == 'rewarded':
            return

        # Award referrer
        if reward_config.reward_type == 'points':
            self.env['loyalty.transaction'].sudo().create({
                'partner_id': self.referrer_id.id,
                'transaction_type': 'referral',
                'points': reward_config.referrer_points,
                'referred_partner_id': self.referee_id.id,
                'description': f"Referral reward for {self.referee_id.name}",
            })
            self.referrer_reward_type = 'points'
            self.referrer_reward_amount = reward_config.referrer_points

        # Award referee
        if reward_config.reward_type == 'points':
            self.env['loyalty.transaction'].sudo().create({
                'partner_id': self.referee_id.id,
                'transaction_type': 'bonus',
                'points': reward_config.referee_points,
                'description': "Welcome bonus for joining via referral",
            })
            self.referee_reward_type = 'points'
            self.referee_reward_amount = reward_config.referee_points

        self.write({
            'status': 'rewarded',
            'referrer_reward_date': fields.Datetime.now(),
            'referee_reward_date': fields.Datetime.now(),
        })

    @api.model
    def process_first_order(self, order):
        """Process referral reward on first order completion"""
        partner = order.partner_id
        if not partner.referred_by_id:
            return

        tracking = self.search([
            ('referee_id', '=', partner.id),
            ('status', '=', 'pending')
        ], limit=1)

        if not tracking:
            return

        reward_config = self.env['referral.reward'].search([
            ('active', '=', True),
            ('reward_on', '=', 'first_order')
        ], limit=1)

        if not reward_config:
            return

        # Check minimum order amount
        if order.amount_total < reward_config.min_order_amount:
            return

        tracking.qualifying_order_id = order
        tracking.status = 'qualified'
        tracking._award_rewards(reward_config)
```

**Hour 5-8: Referral API Endpoints**

```python
# File: smart_dairy_ecommerce/controllers/referral_controller.py

from odoo import http
from odoo.http import request

class ReferralController(http.Controller):

    @http.route('/api/v1/customer/referral/info', type='json', auth='user', methods=['GET'])
    def get_referral_info(self, **kwargs):
        """Get customer's referral information"""
        partner = request.env.user.partner_id

        # Get referral stats
        referrals = request.env['referral.tracking'].sudo().search([
            ('referrer_id', '=', partner.id)
        ])

        pending = referrals.filtered(lambda r: r.status == 'pending')
        rewarded = referrals.filtered(lambda r: r.status == 'rewarded')

        total_earned = sum(
            r.referrer_reward_amount for r in rewarded
            if r.referrer_reward_type == 'points'
        )

        # Get reward config
        reward_config = request.env['referral.reward'].sudo().search([
            ('active', '=', True)
        ], limit=1)

        return {
            'success': True,
            'data': {
                'referral_code': partner.referral_code,
                'referral_link': f"{request.httprequest.host_url}shop?ref={partner.referral_code}",
                'statistics': {
                    'total_referrals': len(referrals),
                    'pending_referrals': len(pending),
                    'successful_referrals': len(rewarded),
                    'total_points_earned': int(total_earned),
                },
                'reward_info': {
                    'referrer_reward': reward_config.referrer_points if reward_config else 0,
                    'referee_reward': reward_config.referee_points if reward_config else 0,
                    'min_order': reward_config.min_order_amount if reward_config else 0,
                    'reward_on': reward_config.reward_on if reward_config else 'first_order',
                } if reward_config else None,
                'recent_referrals': [{
                    'name': r.referee_id.name[:20] + '...' if len(r.referee_id.name) > 20 else r.referee_id.name,
                    'date': r.create_date.isoformat(),
                    'status': r.status,
                    'reward': r.referrer_reward_amount if r.referrer_reward_type == 'points' else 0,
                } for r in referrals[:5]],
            }
        }

    @http.route('/api/v1/referral/validate', type='json', auth='public', methods=['POST'])
    def validate_code(self, **kwargs):
        """Validate a referral code"""
        code = kwargs.get('code', '').strip().upper()

        if not code:
            return {'valid': False, 'error': 'Code is required'}

        referrer = request.env['res.partner'].sudo().search([
            ('referral_code', '=', code),
            ('is_b2c_customer', '=', True)
        ], limit=1)

        if not referrer:
            return {'valid': False, 'error': 'Invalid referral code'}

        reward_config = request.env['referral.reward'].sudo().search([
            ('active', '=', True)
        ], limit=1)

        return {
            'valid': True,
            'data': {
                'referrer_name': referrer.name.split()[0],  # First name only
                'reward': reward_config.referee_points if reward_config else 0,
                'reward_type': 'points',
            }
        }

    @http.route('/api/v1/referral/share', type='json', auth='user', methods=['POST'])
    def share_referral(self, **kwargs):
        """Generate share content for referral"""
        partner = request.env.user.partner_id
        channel = kwargs.get('channel', 'copy')

        referral_link = f"{request.httprequest.host_url}shop?ref={partner.referral_code}"

        reward_config = request.env['referral.reward'].sudo().search([
            ('active', '=', True)
        ], limit=1)
        reward_points = reward_config.referee_points if reward_config else 0

        share_text = f"Join Smart Dairy using my referral code {partner.referral_code} and get {reward_points} bonus points! {referral_link}"

        share_data = {
            'code': partner.referral_code,
            'link': referral_link,
            'text': share_text,
        }

        if channel == 'whatsapp':
            share_data['url'] = f"https://wa.me/?text={share_text.replace(' ', '%20')}"
        elif channel == 'facebook':
            share_data['url'] = f"https://www.facebook.com/sharer/sharer.php?u={referral_link}"
        elif channel == 'email':
            subject = "Join Smart Dairy and get bonus points!"
            share_data['url'] = f"mailto:?subject={subject}&body={share_text}"

        return {
            'success': True,
            'data': share_data
        }
```

---

#### Dev 2 (Full-Stack) - Day 363

**Focus: Notification Preferences System**

**Hour 1-4: Notification Preferences Model & API**

```python
# File: smart_dairy_ecommerce/models/notification_preferences.py

from odoo import models, fields, api

class NotificationPreference(models.Model):
    _name = 'notification.preference'
    _description = 'Customer Notification Preferences'

    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True,
        ondelete='cascade',
        index=True
    )

    # Channel Preferences
    email_enabled = fields.Boolean(string='Email Notifications', default=True)
    sms_enabled = fields.Boolean(string='SMS Notifications', default=True)
    push_enabled = fields.Boolean(string='Push Notifications', default=True)

    # Notification Types
    order_confirmation = fields.Boolean(string='Order Confirmation', default=True)
    order_shipped = fields.Boolean(string='Order Shipped', default=True)
    order_delivered = fields.Boolean(string='Order Delivered', default=True)
    order_cancelled = fields.Boolean(string='Order Cancelled', default=True)

    promotional_offers = fields.Boolean(string='Promotional Offers', default=True)
    flash_sales = fields.Boolean(string='Flash Sales', default=True)
    new_products = fields.Boolean(string='New Product Launches', default=False)

    loyalty_updates = fields.Boolean(string='Loyalty Points Updates', default=True)
    loyalty_expiry = fields.Boolean(string='Points Expiry Reminders', default=True)
    tier_changes = fields.Boolean(string='Tier Status Changes', default=True)

    price_drops = fields.Boolean(string='Wishlist Price Drops', default=True)
    back_in_stock = fields.Boolean(string='Back in Stock Alerts', default=True)

    newsletter = fields.Boolean(string='Newsletter', default=True)

    # Quiet Hours
    quiet_hours_enabled = fields.Boolean(string='Enable Quiet Hours', default=False)
    quiet_start = fields.Float(string='Quiet Start (Hour)', default=22.0)
    quiet_end = fields.Float(string='Quiet End (Hour)', default=8.0)

    # Frequency
    digest_frequency = fields.Selection([
        ('realtime', 'Real-time'),
        ('daily', 'Daily Digest'),
        ('weekly', 'Weekly Digest'),
    ], string='Digest Frequency', default='realtime')

    _sql_constraints = [
        ('unique_partner', 'UNIQUE(partner_id)', 'Each customer can have only one preference record'),
    ]

    @api.model
    def get_or_create(self, partner_id):
        """Get or create notification preferences for a partner"""
        pref = self.search([('partner_id', '=', partner_id)], limit=1)
        if not pref:
            pref = self.create({'partner_id': partner_id})
        return pref


class NotificationPreferenceController(http.Controller):

    @http.route('/api/v1/customer/notifications/preferences', type='json', auth='user', methods=['GET'])
    def get_preferences(self, **kwargs):
        """Get notification preferences"""
        partner = request.env.user.partner_id
        pref = request.env['notification.preference'].sudo().get_or_create(partner.id)

        return {
            'success': True,
            'data': {
                'channels': {
                    'email': pref.email_enabled,
                    'sms': pref.sms_enabled,
                    'push': pref.push_enabled,
                },
                'orders': {
                    'confirmation': pref.order_confirmation,
                    'shipped': pref.order_shipped,
                    'delivered': pref.order_delivered,
                    'cancelled': pref.order_cancelled,
                },
                'marketing': {
                    'promotional_offers': pref.promotional_offers,
                    'flash_sales': pref.flash_sales,
                    'new_products': pref.new_products,
                    'newsletter': pref.newsletter,
                },
                'loyalty': {
                    'updates': pref.loyalty_updates,
                    'expiry': pref.loyalty_expiry,
                    'tier_changes': pref.tier_changes,
                },
                'alerts': {
                    'price_drops': pref.price_drops,
                    'back_in_stock': pref.back_in_stock,
                },
                'quiet_hours': {
                    'enabled': pref.quiet_hours_enabled,
                    'start': pref.quiet_start,
                    'end': pref.quiet_end,
                },
                'digest_frequency': pref.digest_frequency,
            }
        }

    @http.route('/api/v1/customer/notifications/preferences', type='json', auth='user', methods=['PUT'])
    def update_preferences(self, **kwargs):
        """Update notification preferences"""
        partner = request.env.user.partner_id
        pref = request.env['notification.preference'].sudo().get_or_create(partner.id)

        # Map API fields to model fields
        field_mapping = {
            'channels.email': 'email_enabled',
            'channels.sms': 'sms_enabled',
            'channels.push': 'push_enabled',
            'orders.confirmation': 'order_confirmation',
            'orders.shipped': 'order_shipped',
            'orders.delivered': 'order_delivered',
            'orders.cancelled': 'order_cancelled',
            'marketing.promotional_offers': 'promotional_offers',
            'marketing.flash_sales': 'flash_sales',
            'marketing.new_products': 'new_products',
            'marketing.newsletter': 'newsletter',
            'loyalty.updates': 'loyalty_updates',
            'loyalty.expiry': 'loyalty_expiry',
            'loyalty.tier_changes': 'tier_changes',
            'alerts.price_drops': 'price_drops',
            'alerts.back_in_stock': 'back_in_stock',
            'quiet_hours.enabled': 'quiet_hours_enabled',
            'quiet_hours.start': 'quiet_start',
            'quiet_hours.end': 'quiet_end',
            'digest_frequency': 'digest_frequency',
        }

        update_vals = {}
        for api_field, model_field in field_mapping.items():
            parts = api_field.split('.')
            value = kwargs
            for part in parts:
                if isinstance(value, dict) and part in value:
                    value = value[part]
                else:
                    value = None
                    break
            if value is not None:
                update_vals[model_field] = value

        if update_vals:
            pref.write(update_vals)

        return {
            'success': True,
            'message': 'Preferences updated successfully'
        }
```

**Hour 5-8: Address Book OWL Component**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/address_book/address_book.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class AddressBook extends Component {
    static template = "smart_dairy_ecommerce.AddressBook";

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            addresses: [],
            showForm: false,
            editingAddress: null,
            formData: this.getEmptyForm(),
            saving: false,
            placeSuggestions: [],
            showSuggestions: false,
        });

        onWillStart(async () => {
            await this.loadAddresses();
        });
    }

    getEmptyForm() {
        return {
            address_type: 'home',
            label: '',
            recipient_name: '',
            phone: '',
            alternate_phone: '',
            street: '',
            street2: '',
            area: '',
            city: '',
            district: '',
            division: 'dhaka',
            zip_code: '',
            delivery_instructions: '',
            is_default: false,
            latitude: null,
            longitude: null,
            google_place_id: null,
        };
    }

    async loadAddresses() {
        try {
            this.state.loading = true;
            const result = await this.rpc('/api/v1/customer/addresses', {});
            if (result.success) {
                this.state.addresses = result.data;
            }
        } catch (error) {
            this.notification.add("Failed to load addresses", { type: "danger" });
        } finally {
            this.state.loading = false;
        }
    }

    openAddForm() {
        this.state.formData = this.getEmptyForm();
        this.state.editingAddress = null;
        this.state.showForm = true;
    }

    openEditForm(address) {
        this.state.formData = { ...address };
        this.state.editingAddress = address.id;
        this.state.showForm = true;
    }

    closeForm() {
        this.state.showForm = false;
        this.state.editingAddress = null;
        this.state.formData = this.getEmptyForm();
    }

    async saveAddress() {
        if (this.state.saving) return;

        // Validation
        const required = ['recipient_name', 'phone', 'street', 'city', 'division'];
        for (const field of required) {
            if (!this.state.formData[field]) {
                this.notification.add(`${field.replace('_', ' ')} is required`, { type: "warning" });
                return;
            }
        }

        try {
            this.state.saving = true;
            let result;

            if (this.state.editingAddress) {
                result = await this.rpc(`/api/v1/customer/addresses/${this.state.editingAddress}`, {
                    ...this.state.formData
                });
            } else {
                result = await this.rpc('/api/v1/customer/addresses', {
                    ...this.state.formData
                });
            }

            if (result.success) {
                this.notification.add(result.message || "Address saved", { type: "success" });
                this.closeForm();
                await this.loadAddresses();
            } else {
                this.notification.add(result.error, { type: "danger" });
            }
        } catch (error) {
            this.notification.add("Failed to save address", { type: "danger" });
        } finally {
            this.state.saving = false;
        }
    }

    async deleteAddress(addressId) {
        if (!confirm("Are you sure you want to delete this address?")) return;

        try {
            const result = await this.rpc(`/api/v1/customer/addresses/${addressId}`, {});
            if (result.success) {
                this.notification.add("Address deleted", { type: "success" });
                await this.loadAddresses();
            }
        } catch (error) {
            this.notification.add("Failed to delete address", { type: "danger" });
        }
    }

    async setDefault(addressId) {
        try {
            const result = await this.rpc(`/api/v1/customer/addresses/${addressId}/set-default`, {});
            if (result.success) {
                this.notification.add("Default address updated", { type: "success" });
                await this.loadAddresses();
            }
        } catch (error) {
            this.notification.add("Failed to update default", { type: "danger" });
        }
    }

    async searchPlaces(query) {
        if (query.length < 3) {
            this.state.placeSuggestions = [];
            this.state.showSuggestions = false;
            return;
        }

        try {
            const result = await this.rpc('/api/v1/places/autocomplete', { query });
            if (result.success) {
                this.state.placeSuggestions = result.data;
                this.state.showSuggestions = result.data.length > 0;
            }
        } catch (error) {
            console.error('Place search error:', error);
        }
    }

    async selectPlace(place) {
        try {
            const result = await this.rpc('/api/v1/places/details', {
                place_id: place.place_id
            });

            if (result.success) {
                const data = result.data;
                this.state.formData.street = data.street || '';
                this.state.formData.area = data.area || '';
                this.state.formData.city = data.city || '';
                this.state.formData.district = data.district || '';
                this.state.formData.zip_code = data.zip_code || '';
                this.state.formData.latitude = data.latitude;
                this.state.formData.longitude = data.longitude;
                this.state.formData.google_place_id = data.place_id;

                // Map division name to selection value
                if (data.division) {
                    const divisionMap = {
                        'Dhaka Division': 'dhaka',
                        'Chittagong Division': 'chittagong',
                        'Rajshahi Division': 'rajshahi',
                        'Khulna Division': 'khulna',
                        'Barisal Division': 'barisal',
                        'Sylhet Division': 'sylhet',
                        'Rangpur Division': 'rangpur',
                        'Mymensingh Division': 'mymensingh',
                    };
                    this.state.formData.division = divisionMap[data.division] || 'dhaka';
                }
            }
        } catch (error) {
            console.error('Place details error:', error);
        } finally {
            this.state.showSuggestions = false;
        }
    }

    async useCurrentLocation() {
        if (!navigator.geolocation) {
            this.notification.add("Geolocation not supported", { type: "warning" });
            return;
        }

        navigator.geolocation.getCurrentPosition(
            async (position) => {
                try {
                    const result = await this.rpc('/api/v1/places/reverse-geocode', {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                    });

                    if (result.success) {
                        const data = result.data;
                        this.state.formData.street = data.street || '';
                        this.state.formData.area = data.area || '';
                        this.state.formData.city = data.city || '';
                        this.state.formData.latitude = data.latitude;
                        this.state.formData.longitude = data.longitude;
                        this.notification.add("Location detected", { type: "success" });
                    }
                } catch (error) {
                    this.notification.add("Failed to get address", { type: "danger" });
                }
            },
            (error) => {
                this.notification.add("Location access denied", { type: "warning" });
            }
        );
    }

    getAddressTypeIcon(type) {
        const icons = {
            'home': 'fa-home',
            'office': 'fa-building',
            'other': 'fa-map-marker',
        };
        return icons[type] || 'fa-map-marker';
    }
}
```

---

#### Dev 3 (Frontend Lead) - Day 363

**Focus: Address Book QWeb Template & Referral UI**

**Hour 1-4: Address Book Template**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_ecommerce/static/src/xml/address_book.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_ecommerce.AddressBook">
        <div class="sd-address-book">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">My Addresses</h4>
                <button class="btn btn-primary" t-on-click="openAddForm">
                    <i class="fa fa-plus me-1"/> Add New Address
                </button>
            </div>

            <!-- Loading -->
            <t t-if="state.loading">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"/>
                </div>
            </t>

            <!-- Address List -->
            <t t-elif="state.addresses.length">
                <div class="row g-3">
                    <t t-foreach="state.addresses" t-as="address" t-key="address.id">
                        <div class="col-md-6">
                            <div t-attf-class="card h-100 #{address.is_default ? 'border-primary' : ''}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            <i t-attf-class="fa #{getAddressTypeIcon(address.type)} me-2 text-muted"/>
                                            <span class="fw-bold" t-esc="address.label || address.type"/>
                                            <t t-if="address.is_default">
                                                <span class="badge bg-primary ms-2">Default</span>
                                            </t>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted"
                                                    data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"/>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="#"
                                                       t-on-click.prevent="() => this.openEditForm(address)">
                                                        <i class="fa fa-edit me-2"/> Edit
                                                    </a>
                                                </li>
                                                <t t-if="!address.is_default">
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="#"
                                                           t-on-click.prevent="() => this.setDefault(address.id)">
                                                            <i class="fa fa-check me-2"/> Set as Default
                                                        </a>
                                                    </li>
                                                </t>
                                                <li><hr class="dropdown-divider"/></li>
                                                <li>
                                                    <a class="dropdown-item text-danger"
                                                       href="#"
                                                       t-on-click.prevent="() => this.deleteAddress(address.id)">
                                                        <i class="fa fa-trash me-2"/> Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <p class="fw-bold mb-1" t-esc="address.recipient_name"/>
                                    <p class="text-muted small mb-1" t-esc="address.full_address"/>
                                    <p class="text-muted small mb-0">
                                        <i class="fa fa-phone me-1"/>
                                        <t t-esc="address.phone"/>
                                    </p>

                                    <t t-if="address.delivery_instructions">
                                        <p class="text-muted small mt-2 mb-0 fst-italic">
                                            <i class="fa fa-info-circle me-1"/>
                                            <t t-esc="address.delivery_instructions"/>
                                        </p>
                                    </t>
                                </div>
                            </div>
                        </div>
                    </t>
                </div>
            </t>

            <!-- Empty State -->
            <t t-else="">
                <div class="text-center py-5">
                    <i class="fa fa-map-marker fa-4x text-muted mb-3"/>
                    <h5>No addresses saved</h5>
                    <p class="text-muted">Add your first delivery address</p>
                    <button class="btn btn-primary" t-on-click="openAddForm">
                        Add Address
                    </button>
                </div>
            </t>

            <!-- Address Form Modal -->
            <t t-if="state.showForm">
                <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <t t-if="state.editingAddress">Edit Address</t>
                                    <t t-else="">Add New Address</t>
                                </h5>
                                <button type="button" class="btn-close" t-on-click="closeForm"/>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <!-- Address Type -->
                                    <div class="col-md-6">
                                        <label class="form-label">Address Type</label>
                                        <select class="form-select" t-model="state.formData.address_type">
                                            <option value="home">Home</option>
                                            <option value="office">Office</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Label (Optional)</label>
                                        <input type="text" class="form-control"
                                               placeholder="e.g., Parents House"
                                               t-model="state.formData.label"/>
                                    </div>

                                    <!-- Contact Info -->
                                    <div class="col-md-6">
                                        <label class="form-label">Recipient Name *</label>
                                        <input type="text" class="form-control"
                                               t-model="state.formData.recipient_name" required="1"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone Number *</label>
                                        <input type="tel" class="form-control"
                                               placeholder="01XXXXXXXXX"
                                               t-model="state.formData.phone" required="1"/>
                                    </div>

                                    <!-- Location Button -->
                                    <div class="col-12">
                                        <button class="btn btn-outline-primary btn-sm"
                                                type="button"
                                                t-on-click="useCurrentLocation">
                                            <i class="fa fa-location-arrow me-1"/>
                                            Use Current Location
                                        </button>
                                    </div>

                                    <!-- Address Search -->
                                    <div class="col-12 position-relative">
                                        <label class="form-label">Search Address</label>
                                        <input type="text" class="form-control"
                                               placeholder="Start typing to search..."
                                               t-on-input="(e) => this.searchPlaces(e.target.value)"/>
                                        <t t-if="state.showSuggestions">
                                            <ul class="list-group position-absolute w-100 shadow-sm"
                                                style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                                <t t-foreach="state.placeSuggestions" t-as="place" t-key="place.place_id">
                                                    <li class="list-group-item list-group-item-action"
                                                        t-on-click="() => this.selectPlace(place)">
                                                        <i class="fa fa-map-marker me-2 text-muted"/>
                                                        <span t-esc="place.description"/>
                                                    </li>
                                                </t>
                                            </ul>
                                        </t>
                                    </div>

                                    <!-- Street Address -->
                                    <div class="col-12">
                                        <label class="form-label">Street Address *</label>
                                        <input type="text" class="form-control"
                                               t-model="state.formData.street" required="1"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Apartment/Floor</label>
                                        <input type="text" class="form-control"
                                               t-model="state.formData.street2"/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Area/Locality</label>
                                        <input type="text" class="form-control"
                                               t-model="state.formData.area"/>
                                    </div>

                                    <!-- City/Division -->
                                    <div class="col-md-4">
                                        <label class="form-label">City *</label>
                                        <input type="text" class="form-control"
                                               t-model="state.formData.city" required="1"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">District</label>
                                        <input type="text" class="form-control"
                                               t-model="state.formData.district"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Division *</label>
                                        <select class="form-select" t-model="state.formData.division">
                                            <option value="dhaka">Dhaka</option>
                                            <option value="chittagong">Chittagong</option>
                                            <option value="rajshahi">Rajshahi</option>
                                            <option value="khulna">Khulna</option>
                                            <option value="barisal">Barisal</option>
                                            <option value="sylhet">Sylhet</option>
                                            <option value="rangpur">Rangpur</option>
                                            <option value="mymensingh">Mymensingh</option>
                                        </select>
                                    </div>

                                    <!-- Delivery Instructions -->
                                    <div class="col-12">
                                        <label class="form-label">Delivery Instructions</label>
                                        <textarea class="form-control" rows="2"
                                                  placeholder="Any special instructions for delivery..."
                                                  t-model="state.formData.delivery_instructions"/>
                                    </div>

                                    <!-- Default Checkbox -->
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                   id="isDefault"
                                                   t-model="state.formData.is_default"/>
                                            <label class="form-check-label" for="isDefault">
                                                Set as default delivery address
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" t-on-click="closeForm">Cancel</button>
                                <button class="btn btn-primary"
                                        t-att-disabled="state.saving"
                                        t-on-click="saveAddress">
                                    <t t-if="state.saving">
                                        <span class="spinner-border spinner-border-sm me-1"/>
                                    </t>
                                    Save Address
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </t>
        </div>
    </t>
</templates>
```

**Hour 5-8: Referral Program UI Component**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/referral/referral_program.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class ReferralProgram extends Component {
    static template = "smart_dairy_ecommerce.ReferralProgram";

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            referralCode: '',
            referralLink: '',
            statistics: null,
            rewardInfo: null,
            recentReferrals: [],
            sharing: false,
        });

        onWillStart(async () => {
            await this.loadReferralInfo();
        });
    }

    async loadReferralInfo() {
        try {
            this.state.loading = true;
            const result = await this.rpc('/api/v1/customer/referral/info', {});

            if (result.success) {
                const data = result.data;
                this.state.referralCode = data.referral_code;
                this.state.referralLink = data.referral_link;
                this.state.statistics = data.statistics;
                this.state.rewardInfo = data.reward_info;
                this.state.recentReferrals = data.recent_referrals;
            }
        } catch (error) {
            this.notification.add("Failed to load referral info", { type: "danger" });
        } finally {
            this.state.loading = false;
        }
    }

    async copyCode() {
        try {
            await navigator.clipboard.writeText(this.state.referralCode);
            this.notification.add("Referral code copied!", { type: "success" });
        } catch (err) {
            this.notification.add("Failed to copy", { type: "danger" });
        }
    }

    async copyLink() {
        try {
            await navigator.clipboard.writeText(this.state.referralLink);
            this.notification.add("Referral link copied!", { type: "success" });
        } catch (err) {
            this.notification.add("Failed to copy", { type: "danger" });
        }
    }

    async shareVia(channel) {
        try {
            this.state.sharing = true;
            const result = await this.rpc('/api/v1/referral/share', { channel });

            if (result.success && result.data.url) {
                window.open(result.data.url, '_blank');
            }
        } catch (error) {
            this.notification.add("Failed to share", { type: "danger" });
        } finally {
            this.state.sharing = false;
        }
    }

    getStatusBadgeClass(status) {
        const classes = {
            'pending': 'bg-warning',
            'qualified': 'bg-info',
            'rewarded': 'bg-success',
            'expired': 'bg-secondary',
        };
        return classes[status] || 'bg-secondary';
    }

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-BD', {
            month: 'short',
            day: 'numeric',
        });
    }
}
```

---

#### Day 363 End-of-Day Deliverables

| # | Deliverable | Status | Owner |
|---|-------------|--------|-------|
| 1 | Referral program models | ✅ | Dev 1 |
| 2 | Referral tracking system | ✅ | Dev 1 |
| 3 | Referral API endpoints | ✅ | Dev 1 |
| 4 | Notification preferences model | ✅ | Dev 2 |
| 5 | Notification preferences API | ✅ | Dev 2 |
| 6 | Address book OWL component | ✅ | Dev 2 |
| 7 | Address book QWeb template | ✅ | Dev 3 |
| 8 | GPS autocomplete integration | ✅ | Dev 3 |
| 9 | Referral program UI | ✅ | Dev 3 |

---

### Day 364-365: 2FA Security & Payment Methods

#### Day 364-365 Objectives
- Implement TOTP-based 2FA authentication
- Build saved payment methods with tokenization
- Create account security settings UI

---

#### Dev 1 & Dev 2 - Days 364-365

**Focus: Two-Factor Authentication System**

```python
# File: smart_dairy_ecommerce/models/two_factor_auth.py

from odoo import models, fields, api
from odoo.exceptions import AccessDenied
import pyotp
import qrcode
import io
import base64

class TwoFactorAuth(models.Model):
    _name = 'two.factor.auth'
    _description = 'Two-Factor Authentication'

    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True,
        ondelete='cascade'
    )
    secret = fields.Char(
        string='TOTP Secret',
        groups='base.group_system'
    )
    is_enabled = fields.Boolean(
        string='Enabled',
        default=False
    )
    backup_codes = fields.Text(
        string='Backup Codes',
        groups='base.group_system'
    )
    enabled_date = fields.Datetime(
        string='Enabled Date'
    )

    @api.model
    def generate_secret(self):
        """Generate new TOTP secret"""
        return pyotp.random_base32()

    def get_totp_uri(self):
        """Get TOTP URI for QR code"""
        self.ensure_one()
        totp = pyotp.TOTP(self.secret)
        return totp.provisioning_uri(
            name=self.partner_id.email,
            issuer_name='Smart Dairy'
        )

    def get_qr_code(self):
        """Generate QR code as base64 image"""
        self.ensure_one()
        uri = self.get_totp_uri()

        qr = qrcode.QRCode(version=1, box_size=10, border=5)
        qr.add_data(uri)
        qr.make(fit=True)

        img = qr.make_image(fill_color="black", back_color="white")
        buffer = io.BytesIO()
        img.save(buffer, format='PNG')
        return base64.b64encode(buffer.getvalue()).decode()

    def verify_code(self, code):
        """Verify TOTP code"""
        self.ensure_one()
        if not self.secret:
            return False
        totp = pyotp.TOTP(self.secret)
        return totp.verify(code, valid_window=1)

    def generate_backup_codes(self):
        """Generate backup codes"""
        import secrets
        codes = [secrets.token_hex(4).upper() for _ in range(10)]
        self.backup_codes = ','.join(codes)
        return codes

    def verify_backup_code(self, code):
        """Verify and consume backup code"""
        self.ensure_one()
        if not self.backup_codes:
            return False

        codes = self.backup_codes.split(',')
        if code.upper() in codes:
            codes.remove(code.upper())
            self.backup_codes = ','.join(codes)
            return True
        return False


class TwoFactorAuthController(http.Controller):

    @http.route('/api/v1/customer/2fa/setup', type='json', auth='user', methods=['POST'])
    def setup_2fa(self, **kwargs):
        """Initialize 2FA setup"""
        partner = request.env.user.partner_id

        # Check if already enabled
        existing = request.env['two.factor.auth'].sudo().search([
            ('partner_id', '=', partner.id),
            ('is_enabled', '=', True)
        ])
        if existing:
            return {'error': '2FA is already enabled', 'code': 400}

        # Create or update 2FA record
        tfa = request.env['two.factor.auth'].sudo().search([
            ('partner_id', '=', partner.id)
        ])
        secret = request.env['two.factor.auth'].generate_secret()

        if tfa:
            tfa.write({'secret': secret, 'is_enabled': False})
        else:
            tfa = request.env['two.factor.auth'].sudo().create({
                'partner_id': partner.id,
                'secret': secret,
            })

        return {
            'success': True,
            'data': {
                'secret': secret,
                'qr_code': f"data:image/png;base64,{tfa.get_qr_code()}",
            }
        }

    @http.route('/api/v1/customer/2fa/verify', type='json', auth='user', methods=['POST'])
    def verify_2fa(self, **kwargs):
        """Verify and enable 2FA"""
        code = kwargs.get('code', '')
        partner = request.env.user.partner_id

        tfa = request.env['two.factor.auth'].sudo().search([
            ('partner_id', '=', partner.id)
        ])

        if not tfa or not tfa.secret:
            return {'error': 'Please setup 2FA first', 'code': 400}

        if tfa.verify_code(code):
            backup_codes = tfa.generate_backup_codes()
            tfa.write({
                'is_enabled': True,
                'enabled_date': fields.Datetime.now(),
            })
            partner.sudo().write({'two_factor_enabled': True})

            return {
                'success': True,
                'message': '2FA enabled successfully',
                'backup_codes': backup_codes,
            }

        return {'error': 'Invalid verification code', 'code': 400}

    @http.route('/api/v1/customer/2fa/disable', type='json', auth='user', methods=['POST'])
    def disable_2fa(self, **kwargs):
        """Disable 2FA"""
        code = kwargs.get('code', '')
        password = kwargs.get('password', '')
        partner = request.env.user.partner_id

        # Verify password
        try:
            request.env['res.users'].sudo()._check_credentials(
                request.env.user.id, password, {}
            )
        except AccessDenied:
            return {'error': 'Invalid password', 'code': 400}

        tfa = request.env['two.factor.auth'].sudo().search([
            ('partner_id', '=', partner.id),
            ('is_enabled', '=', True)
        ])

        if not tfa:
            return {'error': '2FA is not enabled', 'code': 400}

        if not tfa.verify_code(code):
            return {'error': 'Invalid 2FA code', 'code': 400}

        tfa.write({
            'is_enabled': False,
            'secret': False,
            'backup_codes': False,
        })
        partner.sudo().write({'two_factor_enabled': False})

        return {
            'success': True,
            'message': '2FA disabled successfully'
        }

    @http.route('/api/v1/customer/2fa/status', type='json', auth='user', methods=['GET'])
    def get_2fa_status(self, **kwargs):
        """Get 2FA status"""
        partner = request.env.user.partner_id

        tfa = request.env['two.factor.auth'].sudo().search([
            ('partner_id', '=', partner.id)
        ])

        return {
            'success': True,
            'data': {
                'enabled': tfa.is_enabled if tfa else False,
                'enabled_date': tfa.enabled_date.isoformat() if tfa and tfa.enabled_date else None,
                'backup_codes_remaining': len(tfa.backup_codes.split(',')) if tfa and tfa.backup_codes else 0,
            }
        }
```

---

#### Dev 3 - Days 364-365

**Focus: Security Settings UI**

```javascript
/** @odoo-module **/
// File: smart_dairy_ecommerce/static/src/js/security/security_settings.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class SecuritySettings extends Component {
    static template = "smart_dairy_ecommerce.SecuritySettings";

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            twoFactorEnabled: false,
            twoFactorEnabledDate: null,
            backupCodesRemaining: 0,
            showSetup2FA: false,
            setupStep: 1,
            qrCode: '',
            secret: '',
            verificationCode: '',
            backupCodes: [],
            processing: false,
            showChangePassword: false,
            passwordForm: {
                current: '',
                new: '',
                confirm: '',
            },
        });

        onWillStart(async () => {
            await this.load2FAStatus();
        });
    }

    async load2FAStatus() {
        try {
            this.state.loading = true;
            const result = await this.rpc('/api/v1/customer/2fa/status', {});

            if (result.success) {
                this.state.twoFactorEnabled = result.data.enabled;
                this.state.twoFactorEnabledDate = result.data.enabled_date;
                this.state.backupCodesRemaining = result.data.backup_codes_remaining;
            }
        } catch (error) {
            console.error('Failed to load 2FA status:', error);
        } finally {
            this.state.loading = false;
        }
    }

    async startSetup2FA() {
        try {
            this.state.processing = true;
            const result = await this.rpc('/api/v1/customer/2fa/setup', {});

            if (result.success) {
                this.state.qrCode = result.data.qr_code;
                this.state.secret = result.data.secret;
                this.state.showSetup2FA = true;
                this.state.setupStep = 1;
            } else {
                this.notification.add(result.error, { type: "danger" });
            }
        } catch (error) {
            this.notification.add("Failed to start 2FA setup", { type: "danger" });
        } finally {
            this.state.processing = false;
        }
    }

    async verify2FA() {
        if (!this.state.verificationCode || this.state.verificationCode.length !== 6) {
            this.notification.add("Please enter a 6-digit code", { type: "warning" });
            return;
        }

        try {
            this.state.processing = true;
            const result = await this.rpc('/api/v1/customer/2fa/verify', {
                code: this.state.verificationCode,
            });

            if (result.success) {
                this.state.backupCodes = result.backup_codes;
                this.state.setupStep = 2;
                this.notification.add("2FA enabled successfully!", { type: "success" });
            } else {
                this.notification.add(result.error, { type: "danger" });
            }
        } catch (error) {
            this.notification.add("Verification failed", { type: "danger" });
        } finally {
            this.state.processing = false;
        }
    }

    finishSetup() {
        this.state.showSetup2FA = false;
        this.state.twoFactorEnabled = true;
        this.state.verificationCode = '';
        this.state.backupCodes = [];
        this.load2FAStatus();
    }

    async changePassword() {
        const { current, new: newPass, confirm } = this.state.passwordForm;

        if (!current || !newPass || !confirm) {
            this.notification.add("All fields are required", { type: "warning" });
            return;
        }

        if (newPass !== confirm) {
            this.notification.add("New passwords don't match", { type: "warning" });
            return;
        }

        if (newPass.length < 8) {
            this.notification.add("Password must be at least 8 characters", { type: "warning" });
            return;
        }

        try {
            this.state.processing = true;
            const result = await this.rpc('/api/v1/customer/security/change-password', {
                current_password: current,
                new_password: newPass,
            });

            if (result.success) {
                this.notification.add("Password changed successfully", { type: "success" });
                this.state.showChangePassword = false;
                this.state.passwordForm = { current: '', new: '', confirm: '' };
            } else {
                this.notification.add(result.error, { type: "danger" });
            }
        } catch (error) {
            this.notification.add("Failed to change password", { type: "danger" });
        } finally {
            this.state.processing = false;
        }
    }

    copyBackupCodes() {
        const codes = this.state.backupCodes.join('\n');
        navigator.clipboard.writeText(codes);
        this.notification.add("Backup codes copied!", { type: "success" });
    }
}
```

---

### Day 366-370: Integration, Testing & Documentation

#### Days 366-370 Summary

| Day | Focus | Key Deliverables |
|-----|-------|------------------|
| 366 | Dashboard tab integration | Combined dashboard with all tabs |
| 367 | API integration testing | All endpoints tested |
| 368 | Unit tests | 80%+ coverage |
| 369 | UI polish & accessibility | WCAG 2.1 compliance |
| 370 | Documentation & handoff | API docs, user guide |

---

## 7. Technical Specifications

### 7.1 API Endpoints Summary

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/v1/customer/dashboard` | GET | User | Dashboard data |
| `/api/v1/customer/profile` | PUT | User | Update profile |
| `/api/v1/customer/addresses` | GET/POST | User | Address CRUD |
| `/api/v1/customer/addresses/<id>` | PUT/DELETE | User | Single address |
| `/api/v1/customer/orders` | GET | User | Order history |
| `/api/v1/customer/orders/<id>/reorder` | POST | User | Reorder |
| `/api/v1/customer/loyalty/balance` | GET | User | Points balance |
| `/api/v1/customer/loyalty/redeem` | POST | User | Redeem points |
| `/api/v1/customer/referral/info` | GET | User | Referral info |
| `/api/v1/customer/2fa/*` | Various | User | 2FA management |
| `/api/v1/places/*` | POST | Public | GPS autocomplete |

### 7.2 Database Models

| Model | Description |
|-------|-------------|
| `res.partner` (extended) | B2C customer fields |
| `customer.address` | Multiple addresses |
| `loyalty.tier` | Tier definitions |
| `loyalty.transaction` | Points history |
| `loyalty.earning.rule` | Points rules |
| `referral.tracking` | Referral records |
| `notification.preference` | Notification settings |
| `two.factor.auth` | 2FA configuration |

---

## 8. Testing Requirements

### 8.1 Unit Tests

```python
# File: smart_dairy_ecommerce/tests/test_loyalty.py

from odoo.tests.common import TransactionCase

class TestLoyaltyPoints(TransactionCase):

    def setUp(self):
        super().setUp()
        self.partner = self.env['res.partner'].create({
            'name': 'Test Customer',
            'email': 'test@example.com',
            'is_b2c_customer': True,
        })

    def test_points_earning(self):
        """Test points are earned correctly"""
        self.env['loyalty.transaction'].create({
            'partner_id': self.partner.id,
            'transaction_type': 'earn',
            'points': 100,
            'description': 'Test earning',
        })
        self.assertEqual(self.partner.loyalty_points, 100)

    def test_points_redemption(self):
        """Test points redemption"""
        # Add points first
        self.partner.loyalty_points = 500
        service = self.env['loyalty.points.service']
        result = service.redeem_points(self.partner.id, 200)
        self.assertTrue(result.get('success'))
        self.assertEqual(self.partner.loyalty_points, 300)

    def test_tier_calculation(self):
        """Test tier is calculated correctly"""
        self.partner.lifetime_points = 6000
        self.partner._compute_loyalty_tier()
        self.assertEqual(self.partner.loyalty_tier_id.code, 'GOLD')
```

### 8.2 Coverage Targets

| Area | Target |
|------|--------|
| Customer APIs | > 85% |
| Loyalty system | > 90% |
| Address management | > 80% |
| 2FA authentication | > 85% |

---

## 9. Risk Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Google Places API quota | Medium | Medium | Implement caching, fallback manual entry |
| 2FA adoption low | Medium | Low | In-app education, incentives |
| Points calculation errors | Low | High | Extensive testing, audit trail |
| Address validation failures | Medium | Medium | Graceful fallback, manual override |

---

## 10. Sign-off Checklist

### 10.1 Functional Requirements

- [ ] Customer dashboard displays all sections
- [ ] Address CRUD with GPS autocomplete works
- [ ] Loyalty points calculate correctly
- [ ] Referral codes generate and track
- [ ] 2FA setup and verification functional
- [ ] Order history with filtering works
- [ ] Reorder functionality tested
- [ ] Notification preferences save correctly

### 10.2 Non-Functional Requirements

- [ ] Dashboard loads < 1.5 seconds
- [ ] API response time < 300ms (p95)
- [ ] Mobile responsive design
- [ ] WCAG 2.1 AA compliance
- [ ] Security audit passed

### 10.3 Documentation

- [ ] API documentation complete
- [ ] Code comments adequate
- [ ] User guide drafted
- [ ] Handoff notes prepared

---

**Document End**

*Last Updated: 2025-01-15*
*Version: 1.0*
