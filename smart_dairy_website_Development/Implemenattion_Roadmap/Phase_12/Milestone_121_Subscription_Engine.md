# Milestone 121: Subscription Engine

## Smart Dairy Digital Smart Portal + ERP System
## Phase 12: Commerce — Subscription & Automation

---

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | PH12-M121-SUBSCRIPTION-ENGINE |
| **Version** | 1.0 |
| **Author** | Senior Technical Architect |
| **Created** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Final |
| **Classification** | Internal Use |
| **Parent Phase** | Phase 12 - Commerce Subscription & Automation |
| **Timeline** | Days 601-610 |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 121 delivers the core Subscription Engine that powers Smart Dairy's recurring revenue business model. This foundational module enables customers to subscribe to daily, weekly, or monthly dairy product deliveries with flexible pricing, trial periods, and product bundles. The engine supports the subscription lifecycle from creation through modification, renewal, and eventual cancellation.

### 1.2 Business Value Proposition

| Value Area | Expected Outcome | Measurement |
|------------|------------------|-------------|
| Recurring Revenue | 30% of revenue from subscriptions | MRR tracking |
| Customer Retention | 90%+ annual retention rate | Churn analytics |
| Operational Efficiency | 50% reduction in order processing | Automation metrics |
| Customer Convenience | Daily fresh milk delivery | Subscription count |
| Predictable Demand | Improved inventory planning | Forecast accuracy |

### 1.3 Strategic Alignment

This milestone directly supports:
- **RFP-SUB-001**: Flexible subscription plans (daily/weekly/monthly)
- **BRD-SUB-01**: Support 10,000+ active subscriptions
- **BRD-SUB-02**: 30% revenue from subscriptions
- **SRS-SUB-01**: Subscription processing < 5 seconds
- **SRS-SUB-02**: Support daily/weekly/monthly frequencies

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

| Category | Deliverable | Priority |
|----------|-------------|----------|
| Data Models | Subscription Plan model with frequencies | P0 |
| Data Models | Customer Subscription model with lifecycle | P0 |
| Data Models | Subscription Line items model | P0 |
| Data Models | Product Bundle configuration | P1 |
| Business Logic | Pricing engine (fixed, per-unit, tiered) | P0 |
| Business Logic | Trial period management | P0 |
| Business Logic | Upgrade/downgrade with proration | P1 |
| Business Logic | Subscription state machine | P0 |
| API | Subscription CRUD REST endpoints | P0 |
| API | Mobile app subscription integration | P0 |
| Database | Schema design and migrations | P0 |
| Database | Indexes for performance | P0 |
| Testing | Unit tests (90%+ coverage) | P0 |
| Testing | Integration tests for API | P0 |
| Documentation | Technical documentation | P1 |
| Documentation | API documentation | P1 |

### 2.2 Out-of-Scope Items

- Delivery scheduling (Milestone 122)
- Billing and payment processing (Milestone 124)
- Subscription analytics dashboards (Milestone 125)
- Customer self-service portal UI (Milestone 123)
- Marketing automation integration (Milestone 126)

### 2.3 Assumptions and Constraints

**Assumptions:**
- Product catalog from Phase 3 is complete and accessible
- Customer database from Phase 7 is clean and available
- Odoo 19 CE is installed and configured
- Development environment is set up per Phase 1

**Constraints:**
- Subscription processing must complete in < 5 seconds
- Must support minimum 10,000 concurrent subscriptions
- API response time < 500ms (P95)
- Must integrate with existing Odoo product and partner models

---

## 3. Technical Architecture

### 3.1 Subscription Data Model

```sql
-- =====================================================
-- SUBSCRIPTION ENGINE SCHEMA
-- Milestone 121 - Days 601-610
-- =====================================================

-- =====================================================
-- SUBSCRIPTION PLAN TABLE
-- Defines available subscription options
-- =====================================================

CREATE TABLE subscription_plan (
    id SERIAL PRIMARY KEY,

    -- Basic Information
    name VARCHAR(100) NOT NULL,
    plan_code VARCHAR(20) UNIQUE NOT NULL,
    description TEXT,

    -- Subscription Type
    subscription_type VARCHAR(20) NOT NULL CHECK (
        subscription_type IN ('daily', 'weekly', 'monthly', 'custom')
    ),

    -- Frequency Settings
    delivery_frequency_days INTEGER DEFAULT 1,
    delivery_day_of_week INTEGER CHECK (delivery_day_of_week BETWEEN 0 AND 6),
    delivery_day_of_month INTEGER CHECK (delivery_day_of_month BETWEEN 1 AND 31),

    -- Duration Settings
    minimum_commitment_months INTEGER DEFAULT 0,
    trial_period_days INTEGER DEFAULT 0,

    -- Pricing
    pricing_model VARCHAR(20) NOT NULL CHECK (
        pricing_model IN ('fixed', 'per_unit', 'tiered')
    ),
    base_price DECIMAL(12, 2) DEFAULT 0,
    discount_percentage DECIMAL(5, 2) DEFAULT 0,

    -- Subscription Options
    allow_pause BOOLEAN DEFAULT TRUE,
    max_pause_days INTEGER DEFAULT 30,
    allow_skip_delivery BOOLEAN DEFAULT TRUE,
    max_consecutive_skips INTEGER DEFAULT 3,
    allow_modification BOOLEAN DEFAULT TRUE,
    modification_deadline_hours INTEGER DEFAULT 48,

    -- Auto-renewal
    auto_renewal BOOLEAN DEFAULT TRUE,
    renewal_reminder_days INTEGER DEFAULT 7,

    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    is_published BOOLEAN DEFAULT FALSE,
    display_order INTEGER DEFAULT 0,

    -- Metadata
    created_by INTEGER REFERENCES res_users(id),
    write_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_subscription_plan_code ON subscription_plan(plan_code);
CREATE INDEX idx_subscription_plan_type ON subscription_plan(subscription_type);
CREATE INDEX idx_subscription_plan_active ON subscription_plan(is_active, is_published);

-- =====================================================
-- SUBSCRIPTION PLAN PRODUCT TABLE
-- Products available in each plan
-- =====================================================

CREATE TABLE subscription_plan_product (
    id SERIAL PRIMARY KEY,
    plan_id INTEGER NOT NULL REFERENCES subscription_plan(id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES product_product(id),

    -- Product Configuration
    is_required BOOLEAN DEFAULT FALSE,
    is_default_selected BOOLEAN DEFAULT TRUE,
    min_quantity DECIMAL(10, 2) DEFAULT 1,
    max_quantity DECIMAL(10, 2) DEFAULT 100,
    default_quantity DECIMAL(10, 2) DEFAULT 1,

    -- Pricing Override
    price_override DECIMAL(12, 2),
    discount_percentage DECIMAL(5, 2) DEFAULT 0,

    -- Display
    display_order INTEGER DEFAULT 0,

    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(plan_id, product_id)
);

CREATE INDEX idx_plan_product_plan ON subscription_plan_product(plan_id);
CREATE INDEX idx_plan_product_product ON subscription_plan_product(product_id);

-- =====================================================
-- SUBSCRIPTION PLAN BUNDLE TABLE
-- Pre-configured product bundles
-- =====================================================

CREATE TABLE subscription_bundle (
    id SERIAL PRIMARY KEY,
    plan_id INTEGER NOT NULL REFERENCES subscription_plan(id) ON DELETE CASCADE,

    name VARCHAR(100) NOT NULL,
    bundle_code VARCHAR(20) NOT NULL,
    description TEXT,

    -- Pricing
    bundle_price DECIMAL(12, 2) NOT NULL,
    savings_amount DECIMAL(12, 2) DEFAULT 0,
    savings_percentage DECIMAL(5, 2) DEFAULT 0,

    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    display_order INTEGER DEFAULT 0,

    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(plan_id, bundle_code)
);

CREATE TABLE subscription_bundle_line (
    id SERIAL PRIMARY KEY,
    bundle_id INTEGER NOT NULL REFERENCES subscription_bundle(id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES product_product(id),
    quantity DECIMAL(10, 2) NOT NULL DEFAULT 1,

    UNIQUE(bundle_id, product_id)
);

-- =====================================================
-- TIERED PRICING TABLE
-- For tiered pricing model
-- =====================================================

CREATE TABLE subscription_pricing_tier (
    id SERIAL PRIMARY KEY,
    plan_id INTEGER NOT NULL REFERENCES subscription_plan(id) ON DELETE CASCADE,

    min_quantity DECIMAL(10, 2) NOT NULL,
    max_quantity DECIMAL(10, 2),
    price_per_unit DECIMAL(12, 4) NOT NULL,

    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT valid_quantity_range CHECK (
        max_quantity IS NULL OR min_quantity < max_quantity
    )
);

CREATE INDEX idx_pricing_tier_plan ON subscription_pricing_tier(plan_id);

-- =====================================================
-- CUSTOMER SUBSCRIPTION TABLE
-- Individual customer subscriptions
-- =====================================================

CREATE TABLE customer_subscription (
    id SERIAL PRIMARY KEY,

    -- Subscription Identifier
    subscription_number VARCHAR(20) UNIQUE NOT NULL,

    -- Customer Information
    partner_id INTEGER NOT NULL REFERENCES res_partner(id),

    -- Plan Information
    plan_id INTEGER NOT NULL REFERENCES subscription_plan(id),
    bundle_id INTEGER REFERENCES subscription_bundle(id),

    -- Dates
    start_date DATE NOT NULL,
    trial_end_date DATE,
    commitment_end_date DATE,
    next_delivery_date DATE,
    next_billing_date DATE,
    end_date DATE,

    -- Delivery Details
    delivery_address_id INTEGER REFERENCES res_partner(id),
    delivery_time_slot_id INTEGER,
    delivery_instructions TEXT,

    -- Billing Details
    invoice_address_id INTEGER REFERENCES res_partner(id),
    payment_method_id INTEGER,
    currency_id INTEGER REFERENCES res_currency(id),

    -- Pricing
    recurring_amount DECIMAL(12, 2) NOT NULL DEFAULT 0,
    discount_amount DECIMAL(12, 2) DEFAULT 0,
    tax_amount DECIMAL(12, 2) DEFAULT 0,
    total_amount DECIMAL(12, 2) NOT NULL DEFAULT 0,

    -- State Management
    state VARCHAR(20) NOT NULL DEFAULT 'draft' CHECK (
        state IN ('draft', 'active', 'paused', 'cancelled', 'expired')
    ),

    -- Pause Information
    pause_start_date DATE,
    pause_end_date DATE,
    pause_reason TEXT,

    -- Cancellation Information
    cancellation_date DATE,
    cancellation_reason TEXT,
    cancellation_feedback TEXT,

    -- Auto-renewal
    auto_renew BOOLEAN DEFAULT TRUE,

    -- Health Metrics
    health_score INTEGER DEFAULT 100,
    churn_risk VARCHAR(20) DEFAULT 'low' CHECK (
        churn_risk IN ('low', 'medium', 'high')
    ),

    -- Statistics
    total_deliveries INTEGER DEFAULT 0,
    successful_deliveries INTEGER DEFAULT 0,
    skipped_deliveries INTEGER DEFAULT 0,
    failed_deliveries INTEGER DEFAULT 0,
    total_revenue DECIMAL(14, 2) DEFAULT 0,

    -- Source Tracking
    source_channel VARCHAR(50),
    campaign_id INTEGER,
    referral_code VARCHAR(50),

    -- Metadata
    company_id INTEGER REFERENCES res_company(id),
    created_by INTEGER REFERENCES res_users(id),
    write_uid INTEGER REFERENCES res_users(id),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_customer_sub_number ON customer_subscription(subscription_number);
CREATE INDEX idx_customer_sub_partner ON customer_subscription(partner_id);
CREATE INDEX idx_customer_sub_plan ON customer_subscription(plan_id);
CREATE INDEX idx_customer_sub_state ON customer_subscription(state);
CREATE INDEX idx_customer_sub_next_delivery ON customer_subscription(next_delivery_date);
CREATE INDEX idx_customer_sub_next_billing ON customer_subscription(next_billing_date);
CREATE INDEX idx_customer_sub_churn_risk ON customer_subscription(churn_risk);

-- =====================================================
-- SUBSCRIPTION LINE TABLE
-- Products in each subscription
-- =====================================================

CREATE TABLE customer_subscription_line (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES product_product(id),

    -- Quantity
    quantity DECIMAL(10, 2) NOT NULL DEFAULT 1,
    uom_id INTEGER REFERENCES uom_uom(id),

    -- Pricing
    price_unit DECIMAL(12, 4) NOT NULL,
    discount_percentage DECIMAL(5, 2) DEFAULT 0,
    tax_ids INTEGER[],
    subtotal DECIMAL(12, 2) NOT NULL,

    -- Status
    is_active BOOLEAN DEFAULT TRUE,

    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    write_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_sub_line_subscription ON customer_subscription_line(subscription_id);
CREATE INDEX idx_sub_line_product ON customer_subscription_line(product_id);

-- =====================================================
-- SUBSCRIPTION ACTIVITY LOG
-- Audit trail of all subscription changes
-- =====================================================

CREATE TABLE subscription_activity_log (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id) ON DELETE CASCADE,

    activity_type VARCHAR(50) NOT NULL,
    activity_description TEXT NOT NULL,

    -- State Change
    old_state VARCHAR(20),
    new_state VARCHAR(20),

    -- Value Changes
    old_value JSONB,
    new_value JSONB,

    -- Context
    triggered_by VARCHAR(50), -- 'customer', 'system', 'admin'
    user_id INTEGER REFERENCES res_users(id),
    ip_address VARCHAR(45),
    user_agent TEXT,

    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_sub_activity_subscription ON subscription_activity_log(subscription_id);
CREATE INDEX idx_sub_activity_type ON subscription_activity_log(activity_type);
CREATE INDEX idx_sub_activity_date ON subscription_activity_log(create_date);

-- =====================================================
-- SUBSCRIPTION CUSTOM SCHEDULE
-- For custom frequency subscriptions
-- =====================================================

CREATE TABLE subscription_custom_schedule (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id) ON DELETE CASCADE,

    delivery_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,

    -- Modifications
    is_skipped BOOLEAN DEFAULT FALSE,
    skip_reason TEXT,

    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(subscription_id, delivery_date)
);

CREATE INDEX idx_custom_schedule_sub ON subscription_custom_schedule(subscription_id);
CREATE INDEX idx_custom_schedule_date ON subscription_custom_schedule(delivery_date);

-- =====================================================
-- SUBSCRIPTION SEQUENCE
-- For generating subscription numbers
-- =====================================================

CREATE SEQUENCE subscription_number_seq START 1000;
```

### 3.2 Odoo Model Implementation

```python
# odoo/addons/smart_dairy_subscription/models/subscription_plan.py
"""
Subscription Plan Model
Milestone 121 - Days 601-610
Defines subscription plans with pricing and configuration
"""

from odoo import models, fields, api
from odoo.exceptions import ValidationError, UserError
from datetime import datetime, timedelta
from dateutil.relativedelta import relativedelta
import logging

_logger = logging.getLogger(__name__)


class SubscriptionPlan(models.Model):
    _name = 'subscription.plan'
    _description = 'Subscription Plan'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'display_order, name'

    # ==========================================
    # Basic Information
    # ==========================================

    name = fields.Char(
        string='Plan Name',
        required=True,
        tracking=True,
        translate=True
    )

    plan_code = fields.Char(
        string='Plan Code',
        required=True,
        copy=False,
        readonly=True,
        default=lambda self: self._generate_plan_code()
    )

    description = fields.Html(
        string='Description',
        translate=True,
        help='Public description shown to customers'
    )

    internal_notes = fields.Text(
        string='Internal Notes',
        help='Notes visible only to staff'
    )

    # ==========================================
    # Subscription Type & Frequency
    # ==========================================

    subscription_type = fields.Selection([
        ('daily', 'Daily Delivery'),
        ('weekly', 'Weekly Delivery'),
        ('monthly', 'Monthly Delivery'),
        ('custom', 'Custom Schedule'),
    ], string='Subscription Type', required=True, default='daily', tracking=True)

    delivery_frequency_days = fields.Integer(
        string='Deliver Every (Days)',
        default=1,
        help='For daily subscriptions, deliver every N days'
    )

    delivery_day_of_week = fields.Selection([
        ('0', 'Monday'),
        ('1', 'Tuesday'),
        ('2', 'Wednesday'),
        ('3', 'Thursday'),
        ('4', 'Friday'),
        ('5', 'Saturday'),
        ('6', 'Sunday'),
    ], string='Delivery Day', help='For weekly subscriptions')

    delivery_day_of_month = fields.Integer(
        string='Delivery Day of Month',
        help='For monthly subscriptions (1-31)'
    )

    # ==========================================
    # Duration & Commitment
    # ==========================================

    minimum_commitment_months = fields.Integer(
        string='Minimum Commitment (Months)',
        default=0,
        help='Minimum subscription period before cancellation allowed'
    )

    trial_period_days = fields.Integer(
        string='Trial Period (Days)',
        default=0,
        help='Free trial period duration (0 = no trial)'
    )

    trial_discount_percentage = fields.Float(
        string='Trial Discount %',
        default=100.0,
        help='Discount during trial period (100 = free trial)'
    )

    # ==========================================
    # Pricing Configuration
    # ==========================================

    pricing_model = fields.Selection([
        ('fixed', 'Fixed Price'),
        ('per_unit', 'Per Unit Price'),
        ('tiered', 'Tiered Pricing'),
    ], string='Pricing Model', default='fixed', required=True, tracking=True)

    base_price = fields.Float(
        string='Base Price',
        digits='Product Price',
        tracking=True,
        help='Base subscription price (for fixed pricing)'
    )

    discount_percentage = fields.Float(
        string='Subscription Discount %',
        default=0.0,
        help='Discount compared to retail price'
    )

    currency_id = fields.Many2one(
        'res.currency',
        string='Currency',
        default=lambda self: self.env.company.currency_id
    )

    pricing_tier_ids = fields.One2many(
        'subscription.pricing.tier',
        'plan_id',
        string='Pricing Tiers',
        help='For tiered pricing model'
    )

    # ==========================================
    # Product Configuration
    # ==========================================

    product_ids = fields.Many2many(
        'product.product',
        'subscription_plan_product_rel',
        'plan_id',
        'product_id',
        string='Available Products',
        domain=[('sale_ok', '=', True)]
    )

    plan_product_ids = fields.One2many(
        'subscription.plan.product',
        'plan_id',
        string='Product Configuration'
    )

    bundle_ids = fields.One2many(
        'subscription.bundle',
        'plan_id',
        string='Product Bundles'
    )

    allow_product_customization = fields.Boolean(
        string='Allow Product Customization',
        default=True,
        help='Allow customers to modify products in their subscription'
    )

    # ==========================================
    # Subscription Options
    # ==========================================

    allow_pause = fields.Boolean(
        string='Allow Pause',
        default=True
    )

    max_pause_days = fields.Integer(
        string='Max Pause Days',
        default=30
    )

    allow_skip_delivery = fields.Boolean(
        string='Allow Skip Delivery',
        default=True
    )

    max_consecutive_skips = fields.Integer(
        string='Max Consecutive Skips',
        default=3
    )

    allow_modification = fields.Boolean(
        string='Allow Modification',
        default=True
    )

    modification_deadline_hours = fields.Integer(
        string='Modification Deadline (Hours)',
        default=48,
        help='Hours before delivery when modifications are no longer allowed'
    )

    # ==========================================
    # Auto-renewal Settings
    # ==========================================

    auto_renewal = fields.Boolean(
        string='Auto-renewal Enabled',
        default=True
    )

    renewal_reminder_days = fields.Integer(
        string='Renewal Reminder (Days Before)',
        default=7
    )

    # ==========================================
    # Status & Display
    # ==========================================

    is_active = fields.Boolean(
        string='Active',
        default=True,
        tracking=True
    )

    is_published = fields.Boolean(
        string='Published on Website',
        default=False,
        tracking=True
    )

    display_order = fields.Integer(
        string='Display Order',
        default=10
    )

    image = fields.Image(
        string='Plan Image',
        max_width=512,
        max_height=512
    )

    color = fields.Integer(
        string='Color Index'
    )

    # ==========================================
    # Statistics (Computed)
    # ==========================================

    subscriber_count = fields.Integer(
        string='Active Subscribers',
        compute='_compute_subscriber_stats',
        store=True
    )

    monthly_recurring_revenue = fields.Float(
        string='MRR',
        compute='_compute_subscriber_stats',
        store=True,
        help='Monthly Recurring Revenue'
    )

    total_subscriptions = fields.Integer(
        string='Total Subscriptions',
        compute='_compute_subscriber_stats'
    )

    # ==========================================
    # Constraints
    # ==========================================

    _sql_constraints = [
        ('plan_code_unique', 'UNIQUE(plan_code)', 'Plan code must be unique!'),
        ('discount_range', 'CHECK(discount_percentage >= 0 AND discount_percentage <= 100)',
         'Discount must be between 0 and 100%'),
        ('trial_discount_range', 'CHECK(trial_discount_percentage >= 0 AND trial_discount_percentage <= 100)',
         'Trial discount must be between 0 and 100%'),
    ]

    # ==========================================
    # Computed Methods
    # ==========================================

    @api.model
    def _generate_plan_code(self):
        """Generate unique plan code"""
        sequence = self.env['ir.sequence'].next_by_code('subscription.plan')
        return sequence or f"PLAN{datetime.now().strftime('%Y%m%d%H%M%S')}"

    @api.depends('subscription_type', 'delivery_frequency_days')
    def _compute_subscriber_stats(self):
        """Compute subscription statistics"""
        for plan in self:
            subscriptions = self.env['customer.subscription'].search([
                ('plan_id', '=', plan.id)
            ])

            active_subscriptions = subscriptions.filtered(
                lambda s: s.state == 'active'
            )

            plan.subscriber_count = len(active_subscriptions)
            plan.total_subscriptions = len(subscriptions)

            # Calculate MRR
            total_mrr = 0
            for sub in active_subscriptions:
                if plan.subscription_type == 'daily':
                    # Daily subscription: multiply by ~30 days
                    total_mrr += sub.recurring_amount * (30 / (plan.delivery_frequency_days or 1))
                elif plan.subscription_type == 'weekly':
                    # Weekly: multiply by ~4.33 weeks
                    total_mrr += sub.recurring_amount * 4.33
                elif plan.subscription_type == 'monthly':
                    total_mrr += sub.recurring_amount
                else:
                    # Custom: estimate based on delivery_frequency_days
                    if plan.delivery_frequency_days:
                        deliveries_per_month = 30 / plan.delivery_frequency_days
                        total_mrr += sub.recurring_amount * deliveries_per_month

            plan.monthly_recurring_revenue = total_mrr

    # ==========================================
    # Validation
    # ==========================================

    @api.constrains('delivery_day_of_month')
    def _check_delivery_day_of_month(self):
        for plan in self:
            if plan.delivery_day_of_month and not (1 <= plan.delivery_day_of_month <= 31):
                raise ValidationError('Delivery day of month must be between 1 and 31')

    @api.constrains('subscription_type', 'delivery_day_of_week', 'delivery_day_of_month')
    def _check_frequency_config(self):
        for plan in self:
            if plan.subscription_type == 'weekly' and not plan.delivery_day_of_week:
                raise ValidationError('Weekly subscription requires delivery day of week')
            if plan.subscription_type == 'monthly' and not plan.delivery_day_of_month:
                raise ValidationError('Monthly subscription requires delivery day of month')

    @api.constrains('pricing_model', 'base_price', 'pricing_tier_ids')
    def _check_pricing_config(self):
        for plan in self:
            if plan.pricing_model == 'fixed' and plan.base_price <= 0:
                raise ValidationError('Fixed pricing requires a base price greater than 0')
            if plan.pricing_model == 'tiered' and not plan.pricing_tier_ids:
                raise ValidationError('Tiered pricing requires at least one pricing tier')

    # ==========================================
    # Business Methods
    # ==========================================

    def calculate_price(self, products_qty):
        """
        Calculate subscription price based on pricing model

        :param products_qty: dict of {product_id: quantity}
        :return: dict with price breakdown
        """
        self.ensure_one()

        subtotal = 0.0
        discount_amount = 0.0

        if self.pricing_model == 'fixed':
            subtotal = self.base_price

        elif self.pricing_model == 'per_unit':
            for product_id, qty in products_qty.items():
                product = self.env['product.product'].browse(product_id)
                # Check for price override in plan_product_ids
                plan_product = self.plan_product_ids.filtered(
                    lambda p: p.product_id.id == product_id
                )
                if plan_product and plan_product.price_override:
                    unit_price = plan_product.price_override
                else:
                    unit_price = product.lst_price
                subtotal += unit_price * qty

        elif self.pricing_model == 'tiered':
            total_qty = sum(products_qty.values())
            applicable_tier = None

            for tier in self.pricing_tier_ids.sorted('min_quantity', reverse=True):
                if total_qty >= tier.min_quantity:
                    if tier.max_quantity is None or total_qty <= tier.max_quantity:
                        applicable_tier = tier
                        break

            if applicable_tier:
                subtotal = total_qty * applicable_tier.price_per_unit
            else:
                # Fallback to base price
                subtotal = self.base_price

        # Apply subscription discount
        if self.discount_percentage > 0:
            discount_amount = subtotal * (self.discount_percentage / 100)

        return {
            'subtotal': subtotal,
            'discount_percentage': self.discount_percentage,
            'discount_amount': discount_amount,
            'total': subtotal - discount_amount,
            'currency_id': self.currency_id.id,
        }

    def get_next_delivery_date(self, from_date=None):
        """
        Calculate next delivery date based on subscription type

        :param from_date: Starting date (defaults to today)
        :return: date object
        """
        self.ensure_one()
        from_date = from_date or fields.Date.today()

        if self.subscription_type == 'daily':
            return from_date + timedelta(days=self.delivery_frequency_days or 1)

        elif self.subscription_type == 'weekly':
            target_weekday = int(self.delivery_day_of_week or 0)
            days_ahead = target_weekday - from_date.weekday()
            if days_ahead <= 0:
                days_ahead += 7
            return from_date + timedelta(days=days_ahead)

        elif self.subscription_type == 'monthly':
            next_month = from_date + relativedelta(months=1)
            day = min(self.delivery_day_of_month or 1, 28)  # Safe day
            return next_month.replace(day=day)

        else:  # custom
            return from_date + timedelta(days=self.delivery_frequency_days or 7)

    def action_publish(self):
        """Publish plan to website"""
        self.ensure_one()
        if not self.product_ids and not self.bundle_ids:
            raise UserError('Cannot publish plan without products or bundles')
        self.is_published = True

    def action_unpublish(self):
        """Remove plan from website"""
        self.is_published = False

    def action_view_subscriptions(self):
        """View all subscriptions for this plan"""
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': f'Subscriptions - {self.name}',
            'res_model': 'customer.subscription',
            'view_mode': 'tree,form',
            'domain': [('plan_id', '=', self.id)],
            'context': {'default_plan_id': self.id},
        }


class SubscriptionPlanProduct(models.Model):
    _name = 'subscription.plan.product'
    _description = 'Subscription Plan Product Configuration'
    _order = 'display_order, id'

    plan_id = fields.Many2one(
        'subscription.plan',
        string='Plan',
        required=True,
        ondelete='cascade'
    )

    product_id = fields.Many2one(
        'product.product',
        string='Product',
        required=True,
        domain=[('sale_ok', '=', True)]
    )

    product_name = fields.Char(
        related='product_id.name',
        string='Product Name'
    )

    is_required = fields.Boolean(
        string='Required',
        default=False,
        help='Product must be included in subscription'
    )

    is_default_selected = fields.Boolean(
        string='Default Selected',
        default=True,
        help='Product is selected by default'
    )

    min_quantity = fields.Float(
        string='Min Quantity',
        default=1.0,
        digits='Product Unit of Measure'
    )

    max_quantity = fields.Float(
        string='Max Quantity',
        default=100.0,
        digits='Product Unit of Measure'
    )

    default_quantity = fields.Float(
        string='Default Quantity',
        default=1.0,
        digits='Product Unit of Measure'
    )

    price_override = fields.Float(
        string='Price Override',
        digits='Product Price',
        help='Custom price for this product in this plan (leave empty for standard price)'
    )

    discount_percentage = fields.Float(
        string='Discount %',
        default=0.0
    )

    display_order = fields.Integer(
        string='Display Order',
        default=10
    )

    _sql_constraints = [
        ('plan_product_unique', 'UNIQUE(plan_id, product_id)',
         'Product already exists in this plan!')
    ]


class SubscriptionPricingTier(models.Model):
    _name = 'subscription.pricing.tier'
    _description = 'Subscription Pricing Tier'
    _order = 'min_quantity'

    plan_id = fields.Many2one(
        'subscription.plan',
        string='Plan',
        required=True,
        ondelete='cascade'
    )

    min_quantity = fields.Float(
        string='Min Quantity',
        required=True,
        digits='Product Unit of Measure'
    )

    max_quantity = fields.Float(
        string='Max Quantity',
        digits='Product Unit of Measure',
        help='Leave empty for unlimited'
    )

    price_per_unit = fields.Float(
        string='Price per Unit',
        required=True,
        digits='Product Price'
    )

    @api.constrains('min_quantity', 'max_quantity')
    def _check_quantity_range(self):
        for tier in self:
            if tier.max_quantity and tier.min_quantity >= tier.max_quantity:
                raise ValidationError('Max quantity must be greater than min quantity')


class SubscriptionBundle(models.Model):
    _name = 'subscription.bundle'
    _description = 'Subscription Product Bundle'
    _order = 'display_order, name'

    plan_id = fields.Many2one(
        'subscription.plan',
        string='Plan',
        required=True,
        ondelete='cascade'
    )

    name = fields.Char(
        string='Bundle Name',
        required=True,
        translate=True
    )

    bundle_code = fields.Char(
        string='Bundle Code',
        required=True
    )

    description = fields.Text(
        string='Description',
        translate=True
    )

    line_ids = fields.One2many(
        'subscription.bundle.line',
        'bundle_id',
        string='Products'
    )

    bundle_price = fields.Float(
        string='Bundle Price',
        required=True,
        digits='Product Price'
    )

    regular_price = fields.Float(
        string='Regular Price',
        compute='_compute_regular_price',
        digits='Product Price',
        help='Total price without bundle discount'
    )

    savings_amount = fields.Float(
        string='Savings',
        compute='_compute_savings',
        digits='Product Price'
    )

    savings_percentage = fields.Float(
        string='Savings %',
        compute='_compute_savings'
    )

    is_active = fields.Boolean(
        string='Active',
        default=True
    )

    display_order = fields.Integer(
        string='Display Order',
        default=10
    )

    image = fields.Image(
        string='Bundle Image',
        max_width=256,
        max_height=256
    )

    _sql_constraints = [
        ('bundle_code_unique', 'UNIQUE(plan_id, bundle_code)',
         'Bundle code must be unique within plan!')
    ]

    @api.depends('line_ids.subtotal')
    def _compute_regular_price(self):
        for bundle in self:
            bundle.regular_price = sum(bundle.line_ids.mapped('subtotal'))

    @api.depends('bundle_price', 'regular_price')
    def _compute_savings(self):
        for bundle in self:
            bundle.savings_amount = bundle.regular_price - bundle.bundle_price
            if bundle.regular_price > 0:
                bundle.savings_percentage = (bundle.savings_amount / bundle.regular_price) * 100
            else:
                bundle.savings_percentage = 0


class SubscriptionBundleLine(models.Model):
    _name = 'subscription.bundle.line'
    _description = 'Subscription Bundle Line'

    bundle_id = fields.Many2one(
        'subscription.bundle',
        string='Bundle',
        required=True,
        ondelete='cascade'
    )

    product_id = fields.Many2one(
        'product.product',
        string='Product',
        required=True
    )

    quantity = fields.Float(
        string='Quantity',
        required=True,
        default=1.0,
        digits='Product Unit of Measure'
    )

    price_unit = fields.Float(
        string='Unit Price',
        related='product_id.lst_price',
        digits='Product Price'
    )

    subtotal = fields.Float(
        string='Subtotal',
        compute='_compute_subtotal',
        digits='Product Price'
    )

    @api.depends('quantity', 'price_unit')
    def _compute_subtotal(self):
        for line in self:
            line.subtotal = line.quantity * line.price_unit
```

### 3.3 Customer Subscription Model

```python
# odoo/addons/smart_dairy_subscription/models/customer_subscription.py
"""
Customer Subscription Model
Milestone 121 - Days 601-610
Individual customer subscriptions with lifecycle management
"""

from odoo import models, fields, api
from odoo.exceptions import ValidationError, UserError
from datetime import datetime, timedelta, date
from dateutil.relativedelta import relativedelta
import logging

_logger = logging.getLogger(__name__)


class CustomerSubscription(models.Model):
    _name = 'customer.subscription'
    _description = 'Customer Subscription'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'

    # ==========================================
    # Identification
    # ==========================================

    name = fields.Char(
        string='Subscription Number',
        required=True,
        copy=False,
        readonly=True,
        default='New'
    )

    display_name = fields.Char(
        string='Display Name',
        compute='_compute_display_name'
    )

    # ==========================================
    # Customer Information
    # ==========================================

    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True,
        tracking=True,
        domain=[('customer_rank', '>', 0)]
    )

    partner_name = fields.Char(
        related='partner_id.name',
        string='Customer Name'
    )

    partner_email = fields.Char(
        related='partner_id.email',
        string='Email'
    )

    partner_phone = fields.Char(
        related='partner_id.phone',
        string='Phone'
    )

    # ==========================================
    # Plan Configuration
    # ==========================================

    plan_id = fields.Many2one(
        'subscription.plan',
        string='Subscription Plan',
        required=True,
        tracking=True
    )

    plan_type = fields.Selection(
        related='plan_id.subscription_type',
        string='Plan Type',
        store=True
    )

    bundle_id = fields.Many2one(
        'subscription.bundle',
        string='Selected Bundle',
        domain="[('plan_id', '=', plan_id)]"
    )

    # ==========================================
    # Dates
    # ==========================================

    start_date = fields.Date(
        string='Start Date',
        required=True,
        default=fields.Date.today,
        tracking=True
    )

    trial_end_date = fields.Date(
        string='Trial End Date',
        compute='_compute_trial_end_date',
        store=True
    )

    is_in_trial = fields.Boolean(
        string='In Trial Period',
        compute='_compute_is_in_trial'
    )

    commitment_end_date = fields.Date(
        string='Commitment End Date',
        compute='_compute_commitment_end_date',
        store=True
    )

    next_delivery_date = fields.Date(
        string='Next Delivery',
        tracking=True
    )

    next_billing_date = fields.Date(
        string='Next Billing Date',
        tracking=True
    )

    end_date = fields.Date(
        string='End Date',
        tracking=True
    )

    # ==========================================
    # Delivery Configuration
    # ==========================================

    delivery_address_id = fields.Many2one(
        'res.partner',
        string='Delivery Address',
        domain="['|', ('id', '=', partner_id), ('parent_id', '=', partner_id)]"
    )

    delivery_time_slot_id = fields.Many2one(
        'delivery.time.slot',
        string='Preferred Time Slot'
    )

    delivery_instructions = fields.Text(
        string='Delivery Instructions'
    )

    alternate_receiver_name = fields.Char(
        string='Alternate Receiver'
    )

    alternate_receiver_phone = fields.Char(
        string='Alternate Receiver Phone'
    )

    # ==========================================
    # Billing Configuration
    # ==========================================

    invoice_address_id = fields.Many2one(
        'res.partner',
        string='Invoice Address',
        domain="['|', ('id', '=', partner_id), ('parent_id', '=', partner_id)]"
    )

    payment_method_id = fields.Many2one(
        'payment.token',
        string='Payment Method',
        domain="[('partner_id', '=', partner_id)]"
    )

    currency_id = fields.Many2one(
        'res.currency',
        string='Currency',
        default=lambda self: self.env.company.currency_id
    )

    # ==========================================
    # Products
    # ==========================================

    line_ids = fields.One2many(
        'customer.subscription.line',
        'subscription_id',
        string='Subscription Products'
    )

    product_count = fields.Integer(
        string='Product Count',
        compute='_compute_product_count'
    )

    # ==========================================
    # Pricing
    # ==========================================

    subtotal = fields.Float(
        string='Subtotal',
        compute='_compute_amounts',
        store=True,
        digits='Product Price'
    )

    discount_amount = fields.Float(
        string='Discount',
        compute='_compute_amounts',
        store=True,
        digits='Product Price'
    )

    tax_amount = fields.Float(
        string='Taxes',
        compute='_compute_amounts',
        store=True,
        digits='Product Price'
    )

    recurring_amount = fields.Float(
        string='Recurring Amount',
        compute='_compute_amounts',
        store=True,
        tracking=True,
        digits='Product Price'
    )

    # ==========================================
    # State Management
    # ==========================================

    state = fields.Selection([
        ('draft', 'Draft'),
        ('active', 'Active'),
        ('paused', 'Paused'),
        ('cancelled', 'Cancelled'),
        ('expired', 'Expired'),
    ], string='Status', default='draft', required=True, tracking=True)

    state_changed_date = fields.Datetime(
        string='State Changed',
        default=fields.Datetime.now
    )

    # ==========================================
    # Pause Information
    # ==========================================

    pause_start_date = fields.Date(
        string='Pause Start'
    )

    pause_end_date = fields.Date(
        string='Pause End'
    )

    pause_reason = fields.Text(
        string='Pause Reason'
    )

    # ==========================================
    # Cancellation Information
    # ==========================================

    cancellation_date = fields.Date(
        string='Cancellation Date'
    )

    cancellation_reason = fields.Selection([
        ('too_expensive', 'Too Expensive'),
        ('not_needed', 'No Longer Needed'),
        ('quality_issues', 'Quality Issues'),
        ('delivery_issues', 'Delivery Problems'),
        ('moving', 'Moving/Relocating'),
        ('competitor', 'Switching to Competitor'),
        ('temporary', 'Temporary Break'),
        ('other', 'Other Reason'),
    ], string='Cancellation Reason')

    cancellation_feedback = fields.Text(
        string='Cancellation Feedback'
    )

    retention_offered = fields.Boolean(
        string='Retention Offer Made',
        default=False
    )

    retention_offer_accepted = fields.Boolean(
        string='Retention Offer Accepted',
        default=False
    )

    # ==========================================
    # Auto-renewal
    # ==========================================

    auto_renew = fields.Boolean(
        string='Auto-renew',
        default=True,
        tracking=True
    )

    # ==========================================
    # Health Metrics
    # ==========================================

    health_score = fields.Integer(
        string='Health Score',
        compute='_compute_health_score',
        store=True,
        help='0-100 score indicating subscription health'
    )

    churn_risk = fields.Selection([
        ('low', 'Low Risk'),
        ('medium', 'Medium Risk'),
        ('high', 'High Risk'),
    ], string='Churn Risk', compute='_compute_churn_risk', store=True)

    # ==========================================
    # Statistics
    # ==========================================

    total_deliveries = fields.Integer(
        string='Total Deliveries',
        default=0
    )

    successful_deliveries = fields.Integer(
        string='Successful Deliveries',
        default=0
    )

    skipped_deliveries = fields.Integer(
        string='Skipped Deliveries',
        default=0
    )

    failed_deliveries = fields.Integer(
        string='Failed Deliveries',
        default=0
    )

    total_revenue = fields.Float(
        string='Total Revenue',
        digits='Product Price',
        default=0
    )

    subscription_age_days = fields.Integer(
        string='Subscription Age (Days)',
        compute='_compute_subscription_age'
    )

    # ==========================================
    # Source Tracking
    # ==========================================

    source_channel = fields.Selection([
        ('website', 'Website'),
        ('mobile_app', 'Mobile App'),
        ('phone', 'Phone Order'),
        ('sales_rep', 'Sales Representative'),
        ('referral', 'Customer Referral'),
        ('campaign', 'Marketing Campaign'),
    ], string='Source Channel')

    campaign_id = fields.Many2one(
        'utm.campaign',
        string='Campaign'
    )

    referral_code = fields.Char(
        string='Referral Code'
    )

    # ==========================================
    # Related Records
    # ==========================================

    delivery_ids = fields.One2many(
        'subscription.delivery',
        'subscription_id',
        string='Deliveries'
    )

    invoice_ids = fields.One2many(
        'account.move',
        'subscription_id',
        string='Invoices'
    )

    activity_log_ids = fields.One2many(
        'subscription.activity.log',
        'subscription_id',
        string='Activity Log'
    )

    # ==========================================
    # Company
    # ==========================================

    company_id = fields.Many2one(
        'res.company',
        string='Company',
        default=lambda self: self.env.company
    )

    # ==========================================
    # Model Methods
    # ==========================================

    @api.model
    def create(self, vals):
        """Create subscription with auto-generated number"""
        if vals.get('name', 'New') == 'New':
            vals['name'] = self.env['ir.sequence'].next_by_code(
                'customer.subscription'
            ) or 'New'

        subscription = super().create(vals)

        # Log creation activity
        subscription._log_activity('created', 'Subscription created')

        return subscription

    def write(self, vals):
        """Track state changes"""
        if 'state' in vals:
            vals['state_changed_date'] = fields.Datetime.now()
        return super().write(vals)

    # ==========================================
    # Computed Fields
    # ==========================================

    def _compute_display_name(self):
        for subscription in self:
            subscription.display_name = f"{subscription.name} - {subscription.partner_name}"

    @api.depends('line_ids')
    def _compute_product_count(self):
        for subscription in self:
            subscription.product_count = len(subscription.line_ids)

    @api.depends('start_date', 'plan_id.trial_period_days')
    def _compute_trial_end_date(self):
        for subscription in self:
            if subscription.start_date and subscription.plan_id.trial_period_days > 0:
                subscription.trial_end_date = subscription.start_date + \
                    timedelta(days=subscription.plan_id.trial_period_days)
            else:
                subscription.trial_end_date = False

    def _compute_is_in_trial(self):
        today = fields.Date.today()
        for subscription in self:
            subscription.is_in_trial = (
                subscription.trial_end_date and
                subscription.trial_end_date >= today
            )

    @api.depends('start_date', 'plan_id.minimum_commitment_months')
    def _compute_commitment_end_date(self):
        for subscription in self:
            if subscription.start_date and subscription.plan_id.minimum_commitment_months > 0:
                subscription.commitment_end_date = subscription.start_date + \
                    relativedelta(months=subscription.plan_id.minimum_commitment_months)
            else:
                subscription.commitment_end_date = False

    @api.depends('line_ids.subtotal', 'plan_id.discount_percentage')
    def _compute_amounts(self):
        for subscription in self:
            subtotal = sum(subscription.line_ids.mapped('subtotal'))
            discount_pct = subscription.plan_id.discount_percentage or 0

            # Apply trial discount if applicable
            if subscription.is_in_trial:
                discount_pct = subscription.plan_id.trial_discount_percentage or discount_pct

            discount_amount = subtotal * (discount_pct / 100)

            # Calculate taxes (simplified - would normally use tax computation)
            tax_amount = (subtotal - discount_amount) * 0.05  # Example 5% tax

            subscription.subtotal = subtotal
            subscription.discount_amount = discount_amount
            subscription.tax_amount = tax_amount
            subscription.recurring_amount = subtotal - discount_amount + tax_amount

    @api.depends('total_deliveries', 'skipped_deliveries', 'failed_deliveries',
                 'state', 'start_date')
    def _compute_health_score(self):
        for subscription in self:
            score = 100

            # Reduce for skipped deliveries
            if subscription.total_deliveries > 0:
                skip_rate = subscription.skipped_deliveries / subscription.total_deliveries
                score -= int(skip_rate * 30)

            # Reduce for failed deliveries
            if subscription.total_deliveries > 0:
                fail_rate = subscription.failed_deliveries / subscription.total_deliveries
                score -= int(fail_rate * 20)

            # Reduce if paused
            if subscription.state == 'paused':
                score -= 30

            # Bonus for long-term subscriptions
            if subscription.start_date:
                age_months = (fields.Date.today() - subscription.start_date).days / 30
                score += min(int(age_months * 2), 20)

            subscription.health_score = max(0, min(100, score))

    @api.depends('health_score')
    def _compute_churn_risk(self):
        for subscription in self:
            if subscription.health_score >= 70:
                subscription.churn_risk = 'low'
            elif subscription.health_score >= 40:
                subscription.churn_risk = 'medium'
            else:
                subscription.churn_risk = 'high'

    def _compute_subscription_age(self):
        today = fields.Date.today()
        for subscription in self:
            if subscription.start_date:
                subscription.subscription_age_days = (today - subscription.start_date).days
            else:
                subscription.subscription_age_days = 0

    # ==========================================
    # State Transition Methods
    # ==========================================

    def action_activate(self):
        """Activate subscription from draft state"""
        for subscription in self:
            if subscription.state != 'draft':
                raise UserError('Can only activate subscriptions in draft state')

            if not subscription.line_ids:
                raise UserError('Cannot activate subscription without products')

            # Calculate next delivery date
            subscription.next_delivery_date = subscription.plan_id.get_next_delivery_date(
                subscription.start_date
            )

            # Set next billing date (same as start for first billing)
            subscription.next_billing_date = subscription.start_date

            subscription.state = 'active'
            subscription._log_activity('activated', 'Subscription activated')

            # Send welcome email
            subscription._send_welcome_email()

    def action_pause(self):
        """Open pause wizard"""
        self.ensure_one()
        if self.state != 'active':
            raise UserError('Can only pause active subscriptions')

        if not self.plan_id.allow_pause:
            raise UserError('This subscription plan does not allow pausing')

        return {
            'type': 'ir.actions.act_window',
            'name': 'Pause Subscription',
            'res_model': 'subscription.pause.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_subscription_id': self.id}
        }

    def action_do_pause(self, end_date, reason=None):
        """Execute pause operation"""
        self.ensure_one()

        pause_duration = (end_date - fields.Date.today()).days
        if pause_duration > self.plan_id.max_pause_days:
            raise UserError(
                f'Maximum pause duration is {self.plan_id.max_pause_days} days'
            )

        self.write({
            'state': 'paused',
            'pause_start_date': fields.Date.today(),
            'pause_end_date': end_date,
            'pause_reason': reason,
        })

        self._log_activity(
            'paused',
            f'Subscription paused until {end_date}',
            {'reason': reason}
        )

    def action_resume(self):
        """Resume paused subscription"""
        for subscription in self:
            if subscription.state != 'paused':
                raise UserError('Can only resume paused subscriptions')

            subscription.write({
                'state': 'active',
                'pause_start_date': False,
                'pause_end_date': False,
                'pause_reason': False,
            })

            # Recalculate next delivery
            subscription.next_delivery_date = subscription.plan_id.get_next_delivery_date()

            subscription._log_activity('resumed', 'Subscription resumed')

    def action_cancel(self):
        """Open cancellation wizard"""
        self.ensure_one()
        if self.state not in ['active', 'paused']:
            raise UserError('Can only cancel active or paused subscriptions')

        # Check commitment period
        if (self.commitment_end_date and
            self.commitment_end_date > fields.Date.today()):
            raise UserError(
                f'Cannot cancel during commitment period. '
                f'Commitment ends on {self.commitment_end_date}'
            )

        return {
            'type': 'ir.actions.act_window',
            'name': 'Cancel Subscription',
            'res_model': 'subscription.cancel.wizard',
            'view_mode': 'form',
            'target': 'new',
            'context': {'default_subscription_id': self.id}
        }

    def action_do_cancel(self, reason, feedback=None):
        """Execute cancellation"""
        self.ensure_one()

        self.write({
            'state': 'cancelled',
            'cancellation_date': fields.Date.today(),
            'cancellation_reason': reason,
            'cancellation_feedback': feedback,
            'end_date': fields.Date.today(),
        })

        self._log_activity(
            'cancelled',
            f'Subscription cancelled: {reason}',
            {'reason': reason, 'feedback': feedback}
        )

        # Send cancellation confirmation
        self._send_cancellation_email()

    # ==========================================
    # Business Methods
    # ==========================================

    def modify_products(self, new_lines):
        """
        Modify subscription products

        :param new_lines: list of dicts with product_id, quantity, etc.
        """
        self.ensure_one()

        if self.state not in ['draft', 'active']:
            raise UserError('Can only modify draft or active subscriptions')

        if not self.plan_id.allow_modification:
            raise UserError('This plan does not allow modifications')

        # Check modification deadline
        if self.next_delivery_date:
            deadline = self.next_delivery_date - timedelta(
                hours=self.plan_id.modification_deadline_hours
            )
            if fields.Datetime.now() > deadline:
                raise UserError(
                    f'Modification deadline has passed. '
                    f'Changes will apply from the following delivery.'
                )

        old_lines = [(line.product_id.id, line.quantity) for line in self.line_ids]

        # Clear existing lines and create new ones
        self.line_ids.unlink()

        for line_data in new_lines:
            self.env['customer.subscription.line'].create({
                'subscription_id': self.id,
                **line_data
            })

        new_lines_data = [(line.product_id.id, line.quantity) for line in self.line_ids]

        self._log_activity(
            'modified',
            'Subscription products modified',
            {'old_lines': old_lines, 'new_lines': new_lines_data}
        )

    def skip_delivery(self, delivery_date):
        """
        Skip a specific delivery

        :param delivery_date: date to skip
        """
        self.ensure_one()

        if not self.plan_id.allow_skip_delivery:
            raise UserError('This plan does not allow skipping deliveries')

        # Check consecutive skips
        recent_skips = self.env['subscription.delivery'].search_count([
            ('subscription_id', '=', self.id),
            ('state', '=', 'skipped'),
            ('scheduled_date', '>=', fields.Date.today() - timedelta(days=30))
        ])

        if recent_skips >= self.plan_id.max_consecutive_skips:
            raise UserError(
                f'Maximum {self.plan_id.max_consecutive_skips} consecutive skips allowed'
            )

        # Mark delivery as skipped
        delivery = self.env['subscription.delivery'].search([
            ('subscription_id', '=', self.id),
            ('scheduled_date', '=', delivery_date),
            ('state', '=', 'scheduled')
        ], limit=1)

        if delivery:
            delivery.state = 'skipped'

        self.skipped_deliveries += 1

        self._log_activity('skipped', f'Delivery skipped for {delivery_date}')

    def upgrade_plan(self, new_plan_id):
        """
        Upgrade to a different plan

        :param new_plan_id: ID of the new plan
        """
        self.ensure_one()

        new_plan = self.env['subscription.plan'].browse(new_plan_id)
        if not new_plan:
            raise UserError('Invalid plan')

        old_plan = self.plan_id

        # Calculate proration if needed
        proration = self._calculate_proration(new_plan)

        self.plan_id = new_plan_id

        self._log_activity(
            'upgraded',
            f'Plan upgraded from {old_plan.name} to {new_plan.name}',
            {'old_plan': old_plan.name, 'new_plan': new_plan.name, 'proration': proration}
        )

        return proration

    def _calculate_proration(self, new_plan):
        """
        Calculate proration for plan change

        :param new_plan: new subscription.plan record
        :return: dict with proration details
        """
        self.ensure_one()

        if not self.next_billing_date:
            return {'credit': 0, 'charge': 0}

        today = fields.Date.today()
        days_remaining = (self.next_billing_date - today).days

        if days_remaining <= 0:
            return {'credit': 0, 'charge': 0}

        # Calculate daily rate for old and new plans
        old_daily_rate = self.recurring_amount / 30  # Simplified
        new_daily_rate = new_plan.base_price / 30  # Simplified

        credit = old_daily_rate * days_remaining
        charge = new_daily_rate * days_remaining

        return {
            'credit': credit,
            'charge': charge,
            'net_amount': charge - credit,
            'days_remaining': days_remaining
        }

    # ==========================================
    # Helper Methods
    # ==========================================

    def _log_activity(self, activity_type, description, data=None):
        """Log subscription activity"""
        self.env['subscription.activity.log'].create({
            'subscription_id': self.id,
            'activity_type': activity_type,
            'activity_description': description,
            'old_state': self._origin.state if hasattr(self, '_origin') else None,
            'new_state': self.state,
            'old_value': data.get('old_value') if data else None,
            'new_value': data.get('new_value') if data else None,
            'triggered_by': 'system',  # Would be 'customer' or 'admin' based on context
            'user_id': self.env.user.id,
        })

    def _send_welcome_email(self):
        """Send welcome email to customer"""
        template = self.env.ref(
            'smart_dairy_subscription.email_template_subscription_welcome',
            raise_if_not_found=False
        )
        if template:
            template.send_mail(self.id, force_send=True)

    def _send_cancellation_email(self):
        """Send cancellation confirmation email"""
        template = self.env.ref(
            'smart_dairy_subscription.email_template_subscription_cancelled',
            raise_if_not_found=False
        )
        if template:
            template.send_mail(self.id, force_send=True)

    # ==========================================
    # Cron Jobs
    # ==========================================

    @api.model
    def cron_auto_resume_paused(self):
        """Auto-resume subscriptions whose pause period has ended"""
        today = fields.Date.today()
        subscriptions = self.search([
            ('state', '=', 'paused'),
            ('pause_end_date', '<=', today)
        ])

        for subscription in subscriptions:
            try:
                subscription.action_resume()
            except Exception as e:
                _logger.error(f"Error resuming subscription {subscription.name}: {e}")

    @api.model
    def cron_expire_subscriptions(self):
        """Mark subscriptions as expired if end date has passed"""
        today = fields.Date.today()
        subscriptions = self.search([
            ('state', '=', 'active'),
            ('end_date', '<', today),
            ('auto_renew', '=', False)
        ])

        for subscription in subscriptions:
            subscription.write({
                'state': 'expired'
            })
            subscription._log_activity('expired', 'Subscription expired')


class CustomerSubscriptionLine(models.Model):
    _name = 'customer.subscription.line'
    _description = 'Subscription Product Line'
    _order = 'sequence, id'

    subscription_id = fields.Many2one(
        'customer.subscription',
        string='Subscription',
        required=True,
        ondelete='cascade'
    )

    sequence = fields.Integer(
        string='Sequence',
        default=10
    )

    product_id = fields.Many2one(
        'product.product',
        string='Product',
        required=True,
        domain=[('sale_ok', '=', True)]
    )

    product_name = fields.Char(
        related='product_id.name',
        string='Product Name'
    )

    product_image = fields.Image(
        related='product_id.image_128',
        string='Image'
    )

    quantity = fields.Float(
        string='Quantity',
        required=True,
        default=1.0,
        digits='Product Unit of Measure'
    )

    uom_id = fields.Many2one(
        'uom.uom',
        string='Unit of Measure',
        related='product_id.uom_id'
    )

    price_unit = fields.Float(
        string='Unit Price',
        required=True,
        digits='Product Price'
    )

    discount_percentage = fields.Float(
        string='Discount %',
        default=0.0
    )

    tax_ids = fields.Many2many(
        'account.tax',
        string='Taxes'
    )

    subtotal = fields.Float(
        string='Subtotal',
        compute='_compute_subtotal',
        store=True,
        digits='Product Price'
    )

    is_active = fields.Boolean(
        string='Active',
        default=True
    )

    @api.depends('quantity', 'price_unit', 'discount_percentage')
    def _compute_subtotal(self):
        for line in self:
            price = line.price_unit * (1 - (line.discount_percentage / 100))
            line.subtotal = line.quantity * price

    @api.onchange('product_id')
    def _onchange_product_id(self):
        if self.product_id:
            self.price_unit = self.product_id.lst_price
            self.uom_id = self.product_id.uom_id


class SubscriptionActivityLog(models.Model):
    _name = 'subscription.activity.log'
    _description = 'Subscription Activity Log'
    _order = 'create_date desc'

    subscription_id = fields.Many2one(
        'customer.subscription',
        string='Subscription',
        required=True,
        ondelete='cascade'
    )

    activity_type = fields.Selection([
        ('created', 'Created'),
        ('activated', 'Activated'),
        ('modified', 'Modified'),
        ('paused', 'Paused'),
        ('resumed', 'Resumed'),
        ('cancelled', 'Cancelled'),
        ('expired', 'Expired'),
        ('upgraded', 'Upgraded'),
        ('downgraded', 'Downgraded'),
        ('skipped', 'Delivery Skipped'),
        ('payment_failed', 'Payment Failed'),
        ('payment_success', 'Payment Successful'),
        ('other', 'Other'),
    ], string='Activity Type', required=True)

    activity_description = fields.Text(
        string='Description',
        required=True
    )

    old_state = fields.Char(string='Previous State')
    new_state = fields.Char(string='New State')

    old_value = fields.Char(string='Previous Value')
    new_value = fields.Char(string='New Value')

    triggered_by = fields.Selection([
        ('customer', 'Customer'),
        ('system', 'System'),
        ('admin', 'Administrator'),
    ], string='Triggered By', default='system')

    user_id = fields.Many2one(
        'res.users',
        string='User'
    )

    ip_address = fields.Char(string='IP Address')
    user_agent = fields.Text(string='User Agent')

    create_date = fields.Datetime(
        string='Date',
        default=fields.Datetime.now
    )
```

---

## 4. Day-by-Day Implementation Plan

### Day 601: Subscription Plan Core Model

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create Odoo module structure | `smart_dairy_subscription/__init__.py`, `__manifest__.py` |
| 2-4h | Implement SubscriptionPlan model | `models/subscription_plan.py` |
| 4-6h | Implement SubscriptionPlanProduct model | `models/subscription_plan.py` |
| 6-7h | Create database schema and indexes | SQL migrations |
| 7-8h | Write unit tests for plan model | `tests/test_subscription_plan.py` |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Setup module security and access rights | `security/ir.model.access.csv` |
| 2-4h | Create plan data validation | Validation methods |
| 4-6h | Implement sequence generation | `data/ir_sequence.xml` |
| 6-8h | Setup CI/CD for module testing | GitHub Actions workflow |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create subscription plan form view | `views/subscription_plan_views.xml` |
| 3-5h | Create subscription plan tree view | `views/subscription_plan_views.xml` |
| 5-7h | Design plan configuration wizard | `wizard/plan_config_wizard.xml` |
| 7-8h | Add menu items and actions | `views/menu.xml` |

**Daily Deliverables:**
- [ ] SubscriptionPlan Odoo model with all fields
- [ ] Basic CRUD operations working
- [ ] Plan form and list views functional
- [ ] Security rules configured
- [ ] Database migrations created

---

### Day 602: Pricing Engine Implementation

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement SubscriptionPricingTier model | `models/pricing_tier.py` |
| 2-4h | Develop pricing calculation logic | `services/pricing_engine.py` |
| 4-6h | Implement fixed pricing calculation | `services/pricing_engine.py` |
| 6-7h | Implement tiered pricing calculation | `services/pricing_engine.py` |
| 7-8h | Write pricing calculation tests | `tests/test_pricing.py` |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement per-unit pricing calculation | `services/pricing_engine.py` |
| 2-4h | Create price override handling | Price override logic |
| 4-6h | Develop discount calculation | Discount methods |
| 6-8h | Integration tests for pricing | `tests/test_pricing_integration.py` |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create pricing tier management UI | `views/pricing_tier_views.xml` |
| 3-5h | Build price preview component | OWL component |
| 5-7h | Develop plan comparison widget | `static/src/js/plan_comparison.js` |
| 7-8h | Style pricing displays | SCSS files |

**Daily Deliverables:**
- [ ] Pricing engine supporting fixed, per-unit, tiered models
- [ ] Discount and override calculations working
- [ ] Pricing tier management UI complete
- [ ] Price preview showing accurate totals

---

### Day 603: Customer Subscription Model

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Implement CustomerSubscription model | `models/customer_subscription.py` |
| 3-5h | Implement CustomerSubscriptionLine model | `models/customer_subscription.py` |
| 5-7h | Create subscription state machine | State transition methods |
| 7-8h | Write subscription model tests | `tests/test_customer_subscription.py` |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement subscription validation | Validation methods |
| 2-4h | Create subscription number sequence | Sequence configuration |
| 4-6h | Setup activity logging model | `models/subscription_activity_log.py` |
| 6-8h | Implement audit trail | Activity logging |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create subscription form view | `views/customer_subscription_views.xml` |
| 3-5h | Build subscription list view | `views/customer_subscription_views.xml` |
| 5-7h | Design subscription status badges | Status components |
| 7-8h | Create subscription kanban view | Kanban configuration |

**Daily Deliverables:**
- [ ] CustomerSubscription model complete with all fields
- [ ] Subscription line items management working
- [ ] State machine transitions implemented
- [ ] Activity logging capturing all changes

---

### Day 604: Subscription Lifecycle Management

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement subscription activation | `action_activate()` method |
| 2-4h | Implement pause/resume functionality | `action_pause()`, `action_resume()` |
| 4-6h | Implement cancellation workflow | `action_cancel()` method |
| 6-8h | Implement subscription expiration | Expiration logic |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create pause wizard | `wizard/subscription_pause_wizard.py` |
| 2-4h | Create cancellation wizard | `wizard/subscription_cancel_wizard.py` |
| 4-6h | Implement retention offer logic | Retention methods |
| 6-8h | Write lifecycle transition tests | `tests/test_lifecycle.py` |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Build pause wizard UI | `wizard/subscription_pause_wizard_views.xml` |
| 3-5h | Build cancellation wizard UI | `wizard/subscription_cancel_wizard_views.xml` |
| 5-7h | Create lifecycle timeline component | Timeline widget |
| 7-8h | Add status change animations | CSS transitions |

**Daily Deliverables:**
- [ ] All lifecycle state transitions working
- [ ] Pause/resume with date tracking
- [ ] Cancellation with reason capture
- [ ] Wizards providing smooth UX

---

### Day 605: Product Bundles & Trial Periods

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Implement SubscriptionBundle model | `models/subscription_bundle.py` |
| 3-5h | Implement bundle pricing logic | Bundle price calculation |
| 5-7h | Develop trial period management | Trial period logic |
| 7-8h | Write bundle and trial tests | `tests/test_bundles.py` |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement trial conversion logic | Trial to paid conversion |
| 2-4h | Create trial expiry notifications | Notification system |
| 4-6h | Implement bundle discount calculation | Savings calculation |
| 6-8h | Integration tests for trials | `tests/test_trial_integration.py` |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create bundle management UI | `views/subscription_bundle_views.xml` |
| 3-5h | Build bundle selection component | OWL component |
| 5-7h | Design trial period indicator | Trial UI elements |
| 7-8h | Create savings display widget | Savings component |

**Daily Deliverables:**
- [ ] Bundle model with product grouping
- [ ] Bundle pricing with savings calculation
- [ ] Trial period with automatic conversion
- [ ] Bundle selection UI complete

---

### Day 606: Upgrade/Downgrade & Proration

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Implement plan change logic | `upgrade_plan()`, `downgrade_plan()` |
| 3-6h | Develop proration calculation | `_calculate_proration()` method |
| 6-8h | Handle mid-cycle plan changes | Mid-cycle logic |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create plan change wizard | `wizard/plan_change_wizard.py` |
| 2-4h | Implement proration preview | Preview calculation |
| 4-6h | Handle credit/charge generation | Credit/charge logic |
| 6-8h | Write proration tests | `tests/test_proration.py` |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Build plan change wizard UI | Wizard views |
| 3-5h | Create proration preview component | Preview UI |
| 5-7h | Design plan comparison UI | Comparison layout |
| 7-8h | Add confirmation dialogs | Dialog components |

**Daily Deliverables:**
- [ ] Plan upgrade/downgrade working
- [ ] Accurate proration calculations
- [ ] Credit/charge handling
- [ ] User-friendly plan change UI

---

### Day 607: Subscription REST API

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create subscription API controller | `controllers/subscription_api.py` |
| 3-5h | Implement GET endpoints (list, detail) | GET /subscriptions, GET /subscriptions/{id} |
| 5-7h | Implement POST endpoint (create) | POST /subscriptions |
| 7-8h | Implement PUT endpoint (update) | PUT /subscriptions/{id} |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement DELETE endpoint (cancel) | DELETE /subscriptions/{id} |
| 2-4h | Create action endpoints (pause, resume) | POST /subscriptions/{id}/pause |
| 4-6h | Implement authentication | JWT/API key auth |
| 6-8h | API rate limiting | Rate limit middleware |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-4h | Generate API documentation | OpenAPI/Swagger spec |
| 4-6h | Create API test collection | Postman collection |
| 6-8h | Build API documentation page | API docs UI |

**Daily Deliverables:**
- [ ] Full CRUD API for subscriptions
- [ ] Action endpoints (pause, resume, skip)
- [ ] Authentication and rate limiting
- [ ] Comprehensive API documentation

```python
# controllers/subscription_api.py
"""
Subscription REST API
Milestone 121 - Day 607
"""

from odoo import http
from odoo.http import request
import json
import logging

_logger = logging.getLogger(__name__)


class SubscriptionAPI(http.Controller):

    # ==========================================
    # List Subscriptions
    # ==========================================

    @http.route(
        '/api/v1/subscriptions',
        type='json',
        auth='user',
        methods=['GET'],
        csrf=False
    )
    def list_subscriptions(self, **kwargs):
        """
        List subscriptions for the current user

        Query params:
        - state: filter by state (active, paused, cancelled)
        - page: page number (default 1)
        - limit: items per page (default 20, max 100)
        """
        partner = request.env.user.partner_id

        domain = [('partner_id', '=', partner.id)]

        # Apply state filter
        state = kwargs.get('state')
        if state:
            domain.append(('state', '=', state))

        # Pagination
        page = int(kwargs.get('page', 1))
        limit = min(int(kwargs.get('limit', 20)), 100)
        offset = (page - 1) * limit

        Subscription = request.env['customer.subscription']

        total = Subscription.search_count(domain)
        subscriptions = Subscription.search(
            domain,
            limit=limit,
            offset=offset,
            order='create_date desc'
        )

        return {
            'success': True,
            'data': {
                'subscriptions': [self._serialize_subscription(s) for s in subscriptions],
                'pagination': {
                    'page': page,
                    'limit': limit,
                    'total': total,
                    'pages': (total + limit - 1) // limit
                }
            }
        }

    # ==========================================
    # Get Subscription Detail
    # ==========================================

    @http.route(
        '/api/v1/subscriptions/<int:subscription_id>',
        type='json',
        auth='user',
        methods=['GET'],
        csrf=False
    )
    def get_subscription(self, subscription_id, **kwargs):
        """Get subscription details"""
        partner = request.env.user.partner_id

        subscription = request.env['customer.subscription'].search([
            ('id', '=', subscription_id),
            ('partner_id', '=', partner.id)
        ], limit=1)

        if not subscription:
            return {'success': False, 'error': 'Subscription not found'}

        return {
            'success': True,
            'data': self._serialize_subscription(subscription, detailed=True)
        }

    # ==========================================
    # Create Subscription
    # ==========================================

    @http.route(
        '/api/v1/subscriptions',
        type='json',
        auth='user',
        methods=['POST'],
        csrf=False
    )
    def create_subscription(self, **kwargs):
        """
        Create a new subscription

        Body:
        {
            "plan_id": 1,
            "bundle_id": null,
            "lines": [
                {"product_id": 1, "quantity": 2},
                {"product_id": 2, "quantity": 1}
            ],
            "delivery_address_id": 1,
            "delivery_time_slot_id": 1,
            "delivery_instructions": "Leave at door",
            "start_date": "2026-02-10",
            "auto_renew": true
        }
        """
        partner = request.env.user.partner_id

        try:
            # Validate plan
            plan_id = kwargs.get('plan_id')
            plan = request.env['subscription.plan'].browse(plan_id)
            if not plan or not plan.is_active or not plan.is_published:
                return {'success': False, 'error': 'Invalid plan'}

            # Create subscription
            subscription_vals = {
                'partner_id': partner.id,
                'plan_id': plan_id,
                'bundle_id': kwargs.get('bundle_id'),
                'delivery_address_id': kwargs.get('delivery_address_id') or partner.id,
                'delivery_time_slot_id': kwargs.get('delivery_time_slot_id'),
                'delivery_instructions': kwargs.get('delivery_instructions'),
                'start_date': kwargs.get('start_date'),
                'auto_renew': kwargs.get('auto_renew', True),
                'source_channel': 'mobile_app' if 'mobile' in request.httprequest.user_agent.string.lower() else 'website',
            }

            subscription = request.env['customer.subscription'].create(subscription_vals)

            # Add lines
            for line_data in kwargs.get('lines', []):
                product = request.env['product.product'].browse(line_data['product_id'])
                request.env['customer.subscription.line'].create({
                    'subscription_id': subscription.id,
                    'product_id': product.id,
                    'quantity': line_data.get('quantity', 1),
                    'price_unit': product.lst_price,
                })

            # Auto-activate if payment method available
            # subscription.action_activate()

            return {
                'success': True,
                'data': self._serialize_subscription(subscription, detailed=True)
            }

        except Exception as e:
            _logger.error(f"Error creating subscription: {e}")
            return {'success': False, 'error': str(e)}

    # ==========================================
    # Update Subscription
    # ==========================================

    @http.route(
        '/api/v1/subscriptions/<int:subscription_id>',
        type='json',
        auth='user',
        methods=['PUT'],
        csrf=False
    )
    def update_subscription(self, subscription_id, **kwargs):
        """Update subscription details"""
        partner = request.env.user.partner_id

        subscription = request.env['customer.subscription'].search([
            ('id', '=', subscription_id),
            ('partner_id', '=', partner.id)
        ], limit=1)

        if not subscription:
            return {'success': False, 'error': 'Subscription not found'}

        try:
            # Update allowed fields
            update_vals = {}

            if 'delivery_address_id' in kwargs:
                update_vals['delivery_address_id'] = kwargs['delivery_address_id']
            if 'delivery_time_slot_id' in kwargs:
                update_vals['delivery_time_slot_id'] = kwargs['delivery_time_slot_id']
            if 'delivery_instructions' in kwargs:
                update_vals['delivery_instructions'] = kwargs['delivery_instructions']
            if 'auto_renew' in kwargs:
                update_vals['auto_renew'] = kwargs['auto_renew']

            if update_vals:
                subscription.write(update_vals)

            # Update lines if provided
            if 'lines' in kwargs:
                subscription.modify_products(kwargs['lines'])

            return {
                'success': True,
                'data': self._serialize_subscription(subscription, detailed=True)
            }

        except Exception as e:
            _logger.error(f"Error updating subscription: {e}")
            return {'success': False, 'error': str(e)}

    # ==========================================
    # Subscription Actions
    # ==========================================

    @http.route(
        '/api/v1/subscriptions/<int:subscription_id>/pause',
        type='json',
        auth='user',
        methods=['POST'],
        csrf=False
    )
    def pause_subscription(self, subscription_id, **kwargs):
        """
        Pause a subscription

        Body:
        {
            "end_date": "2026-03-01",
            "reason": "Traveling"
        }
        """
        partner = request.env.user.partner_id

        subscription = request.env['customer.subscription'].search([
            ('id', '=', subscription_id),
            ('partner_id', '=', partner.id)
        ], limit=1)

        if not subscription:
            return {'success': False, 'error': 'Subscription not found'}

        try:
            from datetime import datetime
            end_date = datetime.strptime(kwargs['end_date'], '%Y-%m-%d').date()

            subscription.action_do_pause(
                end_date=end_date,
                reason=kwargs.get('reason')
            )

            return {
                'success': True,
                'data': self._serialize_subscription(subscription)
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route(
        '/api/v1/subscriptions/<int:subscription_id>/resume',
        type='json',
        auth='user',
        methods=['POST'],
        csrf=False
    )
    def resume_subscription(self, subscription_id, **kwargs):
        """Resume a paused subscription"""
        partner = request.env.user.partner_id

        subscription = request.env['customer.subscription'].search([
            ('id', '=', subscription_id),
            ('partner_id', '=', partner.id)
        ], limit=1)

        if not subscription:
            return {'success': False, 'error': 'Subscription not found'}

        try:
            subscription.action_resume()
            return {
                'success': True,
                'data': self._serialize_subscription(subscription)
            }
        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route(
        '/api/v1/subscriptions/<int:subscription_id>/skip',
        type='json',
        auth='user',
        methods=['POST'],
        csrf=False
    )
    def skip_delivery(self, subscription_id, **kwargs):
        """
        Skip a delivery

        Body:
        {
            "delivery_date": "2026-02-15"
        }
        """
        partner = request.env.user.partner_id

        subscription = request.env['customer.subscription'].search([
            ('id', '=', subscription_id),
            ('partner_id', '=', partner.id)
        ], limit=1)

        if not subscription:
            return {'success': False, 'error': 'Subscription not found'}

        try:
            from datetime import datetime
            delivery_date = datetime.strptime(kwargs['delivery_date'], '%Y-%m-%d').date()

            subscription.skip_delivery(delivery_date)

            return {
                'success': True,
                'data': self._serialize_subscription(subscription)
            }
        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route(
        '/api/v1/subscriptions/<int:subscription_id>/cancel',
        type='json',
        auth='user',
        methods=['POST'],
        csrf=False
    )
    def cancel_subscription(self, subscription_id, **kwargs):
        """
        Cancel a subscription

        Body:
        {
            "reason": "too_expensive",
            "feedback": "Looking for more affordable options"
        }
        """
        partner = request.env.user.partner_id

        subscription = request.env['customer.subscription'].search([
            ('id', '=', subscription_id),
            ('partner_id', '=', partner.id)
        ], limit=1)

        if not subscription:
            return {'success': False, 'error': 'Subscription not found'}

        try:
            subscription.action_do_cancel(
                reason=kwargs.get('reason'),
                feedback=kwargs.get('feedback')
            )

            return {
                'success': True,
                'data': self._serialize_subscription(subscription)
            }
        except Exception as e:
            return {'success': False, 'error': str(e)}

    # ==========================================
    # Helper Methods
    # ==========================================

    def _serialize_subscription(self, subscription, detailed=False):
        """Serialize subscription to JSON"""
        data = {
            'id': subscription.id,
            'name': subscription.name,
            'plan': {
                'id': subscription.plan_id.id,
                'name': subscription.plan_id.name,
                'type': subscription.plan_type,
            },
            'state': subscription.state,
            'start_date': str(subscription.start_date) if subscription.start_date else None,
            'next_delivery_date': str(subscription.next_delivery_date) if subscription.next_delivery_date else None,
            'recurring_amount': subscription.recurring_amount,
            'currency': subscription.currency_id.name,
            'is_in_trial': subscription.is_in_trial,
            'auto_renew': subscription.auto_renew,
            'health_score': subscription.health_score,
            'churn_risk': subscription.churn_risk,
        }

        if detailed:
            data.update({
                'trial_end_date': str(subscription.trial_end_date) if subscription.trial_end_date else None,
                'commitment_end_date': str(subscription.commitment_end_date) if subscription.commitment_end_date else None,
                'next_billing_date': str(subscription.next_billing_date) if subscription.next_billing_date else None,
                'subtotal': subscription.subtotal,
                'discount_amount': subscription.discount_amount,
                'tax_amount': subscription.tax_amount,
                'delivery_address': self._serialize_address(subscription.delivery_address_id),
                'delivery_instructions': subscription.delivery_instructions,
                'products': [
                    {
                        'id': line.product_id.id,
                        'name': line.product_name,
                        'quantity': line.quantity,
                        'price_unit': line.price_unit,
                        'subtotal': line.subtotal,
                    }
                    for line in subscription.line_ids
                ],
                'statistics': {
                    'total_deliveries': subscription.total_deliveries,
                    'successful_deliveries': subscription.successful_deliveries,
                    'skipped_deliveries': subscription.skipped_deliveries,
                    'total_revenue': subscription.total_revenue,
                    'subscription_age_days': subscription.subscription_age_days,
                },
                'pause_info': {
                    'pause_start_date': str(subscription.pause_start_date) if subscription.pause_start_date else None,
                    'pause_end_date': str(subscription.pause_end_date) if subscription.pause_end_date else None,
                    'pause_reason': subscription.pause_reason,
                } if subscription.state == 'paused' else None,
            })

        return data

    def _serialize_address(self, partner):
        """Serialize address"""
        if not partner:
            return None
        return {
            'id': partner.id,
            'name': partner.name,
            'street': partner.street,
            'street2': partner.street2,
            'city': partner.city,
            'zip': partner.zip,
            'phone': partner.phone,
        }
```

---

### Day 608: Mobile App Integration

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Create mobile-specific API endpoints | Mobile API routes |
| 3-5h | Implement subscription summary endpoint | Summary API |
| 5-7h | Add push notification hooks | Notification triggers |
| 7-8h | API response optimization | Response caching |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement JWT authentication for mobile | JWT auth |
| 2-4h | Create device token management | Device registration |
| 4-6h | Setup Firebase integration | Firebase configuration |
| 6-8h | Mobile API security testing | Security tests |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-4h | Create Flutter subscription service | Dart service class |
| 4-6h | Implement subscription state management | BLoC/Provider |
| 6-8h | Build subscription list widget | Flutter widgets |

**Daily Deliverables:**
- [ ] Mobile-optimized API endpoints
- [ ] JWT authentication working
- [ ] Firebase push notification setup
- [ ] Flutter service layer complete

---

### Day 609: Health Score & Churn Risk

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Implement health score calculation | `_compute_health_score()` |
| 3-6h | Develop churn risk algorithm | `_compute_churn_risk()` |
| 6-8h | Create health score factors | Scoring factors |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Setup health score cron job | Scheduled recalculation |
| 2-4h | Implement risk threshold alerts | Alert system |
| 4-6h | Create at-risk customer report | Risk report |
| 6-8h | Write health score tests | `tests/test_health_score.py` |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Design health score indicator | Visual indicator |
| 3-5h | Build churn risk dashboard widget | Dashboard widget |
| 5-7h | Create at-risk customer list view | List view |
| 7-8h | Add health score tooltips | Tooltip explanations |

**Daily Deliverables:**
- [ ] Health score algorithm implemented
- [ ] Churn risk classification working
- [ ] Visual indicators on subscription views
- [ ] At-risk customer identification

---

### Day 610: Testing & Documentation

**[Dev 1 - Backend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | Complete unit test suite | 90%+ coverage |
| 3-5h | Integration testing | Integration tests |
| 5-7h | Performance testing | Performance benchmarks |
| 7-8h | Bug fixes and optimization | Bug fixes |

**[Dev 2 - Full-Stack] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Security testing | Security scan results |
| 2-4h | API load testing | Load test results |
| 4-6h | End-to-end testing | E2E test suite |
| 6-8h | CI/CD pipeline finalization | Pipeline complete |

**[Dev 3 - Frontend Lead] - 8 hours**

| Time | Task | Deliverable |
|------|------|-------------|
| 0-3h | UI testing | UI test results |
| 3-5h | Write user documentation | User guide |
| 5-7h | Create technical documentation | Technical docs |
| 7-8h | Milestone review and sign-off | Sign-off document |

**Daily Deliverables:**
- [ ] 90%+ test coverage achieved
- [ ] All critical paths tested
- [ ] Documentation complete
- [ ] Milestone 121 sign-off

---

## 5. Deliverables Checklist

### 5.1 Technical Deliverables

| # | Deliverable | Status | Verification |
|---|-------------|--------|--------------|
| 1 | SubscriptionPlan Odoo model | [ ] | Model tests pass |
| 2 | CustomerSubscription model | [ ] | Model tests pass |
| 3 | CustomerSubscriptionLine model | [ ] | Model tests pass |
| 4 | SubscriptionBundle model | [ ] | Model tests pass |
| 5 | Pricing engine (fixed/tiered/per-unit) | [ ] | Calculation tests pass |
| 6 | Trial period management | [ ] | Trial tests pass |
| 7 | Upgrade/downgrade with proration | [ ] | Proration tests pass |
| 8 | Subscription REST API | [ ] | API tests pass |
| 9 | Health score calculation | [ ] | Score tests pass |
| 10| Activity logging | [ ] | Audit trail verified |

### 5.2 Documentation Deliverables

| # | Deliverable | Status | Location |
|---|-------------|--------|----------|
| 1 | API documentation | [ ] | `/docs/api/subscription.md` |
| 2 | Technical architecture | [ ] | `/docs/architecture/subscription.md` |
| 3 | User guide | [ ] | `/docs/user/subscription_guide.md` |
| 4 | Admin guide | [ ] | `/docs/admin/subscription_admin.md` |
| 5 | Data dictionary | [ ] | `/docs/data/subscription_schema.md` |

### 5.3 Test Artifacts

| # | Deliverable | Status | Coverage |
|---|-------------|--------|----------|
| 1 | Unit tests | [ ] | > 90% |
| 2 | Integration tests | [ ] | API coverage |
| 3 | Performance tests | [ ] | < 5s processing |
| 4 | Security tests | [ ] | OWASP compliance |

---

## 6. Success Criteria

### 6.1 Functional Criteria

- [ ] Subscription plans created with daily/weekly/monthly frequencies
- [ ] Customers can subscribe to plans with product selection
- [ ] Pricing engine calculates correctly for all models
- [ ] Trial periods activate and track correctly
- [ ] Upgrade/downgrade calculates proration accurately
- [ ] Pause/resume functionality works within limits
- [ ] Cancellation captures reason and feedback
- [ ] API returns correct responses for all endpoints

### 6.2 Technical Criteria

- [ ] Subscription processing < 5 seconds
- [ ] API response time < 500ms (P95)
- [ ] Database queries optimized with indexes
- [ ] 90%+ unit test coverage
- [ ] Zero critical security vulnerabilities
- [ ] Mobile API authentication working

### 6.3 Business Criteria

- [ ] Supports 10,000+ concurrent subscriptions
- [ ] Health score identifies at-risk customers
- [ ] Activity log captures all changes
- [ ] Pricing transparency for customers

---

## 7. Risk Register

| Risk ID | Description | Impact | Probability | Mitigation |
|---------|-------------|--------|-------------|------------|
| R121-01 | Complex pricing edge cases | Medium | Medium | Extensive testing, clear documentation |
| R121-02 | State machine complexity | Medium | Low | State diagram, thorough testing |
| R121-03 | Performance at scale | High | Medium | Database optimization, caching |
| R121-04 | API backward compatibility | Medium | Low | API versioning, deprecation policy |
| R121-05 | Mobile integration issues | Medium | Medium | Early integration testing |

---

## 8. Dependencies

### 8.1 Internal Dependencies

| Dependency | Source | Required For |
|------------|--------|--------------|
| Product catalog | Phase 3 | Product selection |
| Customer database | Phase 7 | Customer subscriptions |
| Odoo base modules | Phase 1 | Model inheritance |

### 8.2 External Dependencies

| Dependency | Provider | Status |
|------------|----------|--------|
| Odoo 19 CE | Odoo SA | Available |
| PostgreSQL 16 | PostgreSQL | Available |

---

## 9. Quality Assurance

### 9.1 Code Review Checklist

- [ ] All models follow Odoo coding standards
- [ ] Methods have docstrings
- [ ] SQL injection prevention
- [ ] Input validation on all endpoints
- [ ] Error handling with proper messages
- [ ] Logging for debugging

### 9.2 Testing Requirements

| Test Type | Tool | Target |
|-----------|------|--------|
| Unit Tests | pytest | 90% coverage |
| API Tests | pytest + requests | All endpoints |
| Performance | Locust | < 5s processing |
| Security | Bandit, OWASP ZAP | No critical issues |

---

**END OF MILESTONE 121: SUBSCRIPTION ENGINE**

---

*Document prepared for Smart Dairy Digital Smart Portal + ERP System*
*Phase 12: Commerce — Subscription & Automation*
*Milestone 121: Subscription Engine (Days 601-610)*
*Version 1.0 — 2026-02-04*
