# Milestone 23: Subscription Management Engine

## Smart Dairy Digital Smart Portal + ERP — Phase 3: E-Commerce, Mobile & Analytics

| Field            | Detail                                                        |
|------------------|---------------------------------------------------------------|
| **Milestone**    | 23 of 30 (3 of 10 in Phase 3)                                |
| **Title**        | Subscription Management Engine                                |
| **Phase**        | Phase 3 — E-Commerce, Mobile & Analytics (Part A: E-Commerce & Mobile) |
| **Days**         | Days 221–230 (of 300)                                        |
| **Version**      | 1.0                                                          |
| **Status**       | Draft                                                        |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03                                                   |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Build a comprehensive subscription management engine for recurring dairy deliveries. Implement subscription plan templates (Daily, Weekly, Custom), flexible delivery scheduling with time slots, customer self-service management including pause/resume (max 7 days/month), skip individual deliveries, automated billing with payment gateway integration, subscription modifications with prorating, and mobile app subscription management interface.

### 1.2 Objectives

1. Design subscription data models with plan templates and customer subscriptions
2. Implement Daily, Weekly, and Custom frequency subscription plans
3. Build flexible delivery scheduling engine with time slot management
4. Create pause/resume functionality with 7-day monthly limit enforcement
5. Implement skip delivery feature for individual dates
6. Develop automated billing with recurring payment processing
7. Build subscription modification flow with prorated billing
8. Create subscription renewal and expiration handling
9. Implement mobile subscription management screens
10. Build admin dashboard for subscription monitoring

### 1.3 Key Deliverables

| # | Deliverable | Owner | Day |
|---|-------------|-------|-----|
| D23.1 | Subscription plan model and templates | Dev 1 | 221 |
| D23.2 | Customer subscription model | Dev 1 | 221-222 |
| D23.3 | Delivery scheduling engine | Dev 1 | 222-223 |
| D23.4 | Time slot configuration | Dev 1 | 223 |
| D23.5 | Pause/Resume functionality | Dev 1 | 224 |
| D23.6 | Skip delivery feature | Dev 1 | 224-225 |
| D23.7 | Automated billing module | Dev 2 | 225-226 |
| D23.8 | Payment retry logic | Dev 2 | 226 |
| D23.9 | Subscription modification with prorating | Dev 1 | 227 |
| D23.10 | Celery tasks for scheduling | Dev 2 | 227-228 |
| D23.11 | Mobile subscription UI | Dev 3 | 228-229 |
| D23.12 | Admin subscription dashboard | Dev 3 | 229 |
| D23.13 | Integration testing and documentation | All | 230 |

### 1.4 Success Criteria

- [ ] Three subscription frequencies operational (Daily, Weekly, Custom)
- [ ] Delivery scheduling generating accurate delivery dates
- [ ] Pause/resume enforcing 7-day monthly limit
- [ ] Skip delivery working for individual dates up to 24h before
- [ ] Automated billing generating invoices and processing payments
- [ ] Failed payment retry at 1, 3, and 7 days
- [ ] Subscription modification calculating correct prorated amounts
- [ ] Mobile app displaying and managing subscriptions
- [ ] Admin dashboard showing subscription KPIs

### 1.5 Prerequisites

- **From Phase 2:**
  - Payment gateway integration (MS-18)
  - Customer accounts and billing addresses
  - Product catalog with pricing

- **From Phase 3:**
  - Mobile app foundation (MS-22)
  - Checkout flow implementation

---

## 2. Requirement Traceability Matrix

### 2.1 BRD Requirements Mapping

| BRD Req ID | Requirement Description | SRS Trace | Day | Owner |
|-----------|------------------------|-----------|-----|-------|
| BRD-SUB-001 | Subscription plan management | SRS-SUB-001 | 221 | Dev 1 |
| BRD-SUB-002 | Flexible delivery frequencies | SRS-SUB-002 | 221-222 | Dev 1 |
| BRD-SUB-003 | Delivery time slots | SRS-SUB-003 | 223 | Dev 1 |
| BRD-SUB-004 | Pause/Resume subscription | SRS-SUB-004 | 224 | Dev 1 |
| BRD-SUB-005 | Skip individual deliveries | SRS-SUB-005 | 224-225 | Dev 1 |
| BRD-SUB-006 | Automated recurring billing | SRS-SUB-006 | 225-226 | Dev 2 |
| BRD-SUB-007 | Payment failure handling | SRS-SUB-007 | 226 | Dev 2 |
| BRD-SUB-008 | Subscription modifications | SRS-SUB-008 | 227 | Dev 1 |
| BRD-SUB-009 | Mobile subscription management | SRS-SUB-009 | 228-229 | Dev 3 |
| BRD-SUB-010 | Subscription analytics | SRS-SUB-010 | 229 | Dev 3 |

### 2.2 Smart Dairy Subscription Plans

| Plan Name | Frequency | Products | Min Duration | Price Benefit |
|-----------|-----------|----------|--------------|---------------|
| Daily Fresh | Daily | Fresh Milk | 30 days | 5% discount |
| Weekly Essentials | Weekly | Milk, Yogurt, Paneer | 4 weeks | 7% discount |
| Monthly Box | Monthly | Curated selection | 3 months | 10% discount |
| Custom Plan | User-defined | Any products | 2 weeks | 3% discount |

---

## 3. Day-by-Day Breakdown

---

### Day 221 — Subscription Plan Model and Templates

**Objective:** Create the core subscription plan model with templates for Daily, Weekly, and Custom frequencies.

#### Dev 1 — Backend Lead (8h)

**Task 221.1.1: Subscription Module Setup (2h)**

```python
# smart_dairy_addons/smart_subscription/__manifest__.py
{
    'name': 'Smart Dairy Subscription Management',
    'version': '19.0.1.0.0',
    'category': 'Sales/Subscriptions',
    'summary': 'Recurring delivery subscription management for Smart Dairy',
    'description': """
Smart Dairy Subscription Management
===================================
- Flexible subscription plans (Daily, Weekly, Custom)
- Delivery scheduling with time slots
- Pause/Resume/Skip functionality
- Automated billing and payment processing
- Mobile-friendly subscription management
    """,
    'author': 'Smart Dairy Ltd',
    'website': 'https://smartdairy.com.bd',
    'license': 'LGPL-3',
    'depends': [
        'sale_subscription',
        'sale',
        'stock',
        'account',
        'payment',
        'smart_ecommerce_adv',
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/subscription_security.xml',
        'data/subscription_plan_data.xml',
        'data/subscription_cron.xml',
        'views/subscription_plan_views.xml',
        'views/customer_subscription_views.xml',
        'views/delivery_schedule_views.xml',
        'views/subscription_menu.xml',
        'reports/subscription_reports.xml',
    ],
    'installable': True,
    'application': True,
    'auto_install': False,
}
```

**Task 221.1.2: Subscription Plan Model (3h)**

```python
# smart_dairy_addons/smart_subscription/models/subscription_plan.py
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError
from dateutil.relativedelta import relativedelta

class SubscriptionPlan(models.Model):
    _name = 'subscription.plan'
    _description = 'Subscription Plan Template'
    _order = 'sequence, name'

    name = fields.Char(string='Plan Name', required=True, translate=True)
    code = fields.Char(string='Plan Code', required=True)
    description = fields.Html(string='Description', translate=True)
    sequence = fields.Integer(default=10)
    active = fields.Boolean(default=True)

    # Frequency Configuration
    frequency_type = fields.Selection([
        ('daily', 'Daily'),
        ('weekly', 'Weekly'),
        ('biweekly', 'Bi-Weekly'),
        ('monthly', 'Monthly'),
        ('custom', 'Custom'),
    ], string='Frequency Type', required=True, default='weekly')

    frequency_interval = fields.Integer(
        string='Frequency Interval',
        default=1,
        help='Number of frequency units between deliveries'
    )

    # For weekly subscriptions
    delivery_weekdays = fields.Selection([
        ('0', 'Monday'),
        ('1', 'Tuesday'),
        ('2', 'Wednesday'),
        ('3', 'Thursday'),
        ('4', 'Friday'),
        ('5', 'Saturday'),
        ('6', 'Sunday'),
    ], string='Delivery Day (Weekly)')

    delivery_weekday_ids = fields.Many2many(
        'subscription.weekday',
        string='Delivery Days',
        help='Select multiple days for custom weekly delivery'
    )

    # Duration and Commitment
    min_commitment_periods = fields.Integer(
        string='Minimum Commitment Periods',
        default=4,
        help='Minimum number of billing periods'
    )
    billing_period = fields.Selection([
        ('weekly', 'Weekly'),
        ('biweekly', 'Bi-Weekly'),
        ('monthly', 'Monthly'),
    ], string='Billing Period', default='monthly')

    # Pricing
    discount_percentage = fields.Float(
        string='Discount %',
        digits=(5, 2),
        default=0.0,
        help='Discount applied to subscription orders'
    )
    setup_fee = fields.Monetary(
        string='Setup Fee',
        currency_field='currency_id',
        default=0.0
    )
    currency_id = fields.Many2one(
        'res.currency',
        string='Currency',
        default=lambda self: self.env.company.currency_id
    )

    # Products
    allowed_product_ids = fields.Many2many(
        'product.product',
        string='Allowed Products',
        help='Leave empty for all products'
    )
    suggested_product_ids = fields.Many2many(
        'product.product',
        'subscription_plan_suggested_products_rel',
        string='Suggested Products'
    )
    min_products = fields.Integer(string='Minimum Products', default=1)
    max_products = fields.Integer(string='Maximum Products', default=10)

    # Pause/Skip Rules
    max_pause_days_per_month = fields.Integer(
        string='Max Pause Days/Month',
        default=7,
        help='Maximum days subscription can be paused per month'
    )
    min_skip_notice_hours = fields.Integer(
        string='Min Skip Notice (hours)',
        default=24,
        help='Minimum hours before delivery to request skip'
    )
    max_skips_per_month = fields.Integer(
        string='Max Skips/Month',
        default=4
    )

    # Display
    image = fields.Image(string='Plan Image', max_width=512, max_height=512)
    website_published = fields.Boolean(string='Published on Website', default=True)
    highlight = fields.Boolean(string='Highlight Plan', default=False)
    badge_text = fields.Char(string='Badge Text', help='e.g., "Most Popular"')

    # Statistics
    subscription_count = fields.Integer(
        compute='_compute_subscription_count',
        string='Active Subscriptions'
    )

    @api.depends('code')
    def _compute_subscription_count(self):
        for plan in self:
            plan.subscription_count = self.env['customer.subscription'].search_count([
                ('plan_id', '=', plan.id),
                ('state', '=', 'active')
            ])

    @api.constrains('min_commitment_periods')
    def _check_min_commitment(self):
        for plan in self:
            if plan.min_commitment_periods < 1:
                raise ValidationError(_("Minimum commitment must be at least 1 period"))

    @api.constrains('discount_percentage')
    def _check_discount(self):
        for plan in self:
            if plan.discount_percentage < 0 or plan.discount_percentage > 50:
                raise ValidationError(_("Discount must be between 0% and 50%"))

    def get_next_delivery_dates(self, start_date, count=10):
        """Calculate next delivery dates based on plan frequency"""
        self.ensure_one()
        dates = []
        current_date = start_date

        for _ in range(count):
            if self.frequency_type == 'daily':
                current_date += relativedelta(days=self.frequency_interval)

            elif self.frequency_type == 'weekly':
                # Find next delivery weekday
                if self.delivery_weekdays:
                    target_weekday = int(self.delivery_weekdays)
                    days_ahead = target_weekday - current_date.weekday()
                    if days_ahead <= 0:
                        days_ahead += 7
                    current_date += relativedelta(days=days_ahead)
                else:
                    current_date += relativedelta(weeks=self.frequency_interval)

            elif self.frequency_type == 'biweekly':
                current_date += relativedelta(weeks=2)

            elif self.frequency_type == 'monthly':
                current_date += relativedelta(months=self.frequency_interval)

            elif self.frequency_type == 'custom':
                # For custom, use frequency_interval as days
                current_date += relativedelta(days=self.frequency_interval)

            dates.append(current_date)

        return dates

    def calculate_price(self, product_lines):
        """Calculate total subscription price with discount"""
        self.ensure_one()
        subtotal = sum(line['price'] * line['qty'] for line in product_lines)
        discount_amount = subtotal * (self.discount_percentage / 100)
        return {
            'subtotal': subtotal,
            'discount': discount_amount,
            'total': subtotal - discount_amount,
            'setup_fee': self.setup_fee,
        }


class SubscriptionWeekday(models.Model):
    _name = 'subscription.weekday'
    _description = 'Subscription Weekday'

    name = fields.Char(string='Day Name', required=True)
    code = fields.Integer(string='Weekday Code', required=True)  # 0=Monday, 6=Sunday
```

**Task 221.1.3: Customer Subscription Model (3h)**

```python
# smart_dairy_addons/smart_subscription/models/customer_subscription.py
from odoo import models, fields, api, _
from odoo.exceptions import UserError, ValidationError
from datetime import datetime, timedelta
from dateutil.relativedelta import relativedelta

class CustomerSubscription(models.Model):
    _name = 'customer.subscription'
    _description = 'Customer Subscription'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'

    name = fields.Char(
        string='Subscription Reference',
        required=True,
        copy=False,
        readonly=True,
        default=lambda self: _('New')
    )
    partner_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True,
        tracking=True
    )
    plan_id = fields.Many2one(
        'subscription.plan',
        string='Subscription Plan',
        required=True,
        tracking=True
    )

    # State Management
    state = fields.Selection([
        ('draft', 'Draft'),
        ('pending_payment', 'Pending Payment'),
        ('active', 'Active'),
        ('paused', 'Paused'),
        ('cancelled', 'Cancelled'),
        ('expired', 'Expired'),
    ], string='Status', default='draft', tracking=True)

    # Dates
    start_date = fields.Date(string='Start Date', required=True, tracking=True)
    end_date = fields.Date(string='End Date', compute='_compute_end_date', store=True)
    next_delivery_date = fields.Date(string='Next Delivery Date', tracking=True)
    next_billing_date = fields.Date(string='Next Billing Date', tracking=True)
    last_billing_date = fields.Date(string='Last Billing Date')

    # Commitment
    commitment_end_date = fields.Date(
        string='Commitment End Date',
        compute='_compute_commitment_end_date',
        store=True
    )
    can_cancel = fields.Boolean(compute='_compute_can_cancel')

    # Subscription Lines
    line_ids = fields.One2many(
        'customer.subscription.line',
        'subscription_id',
        string='Products'
    )

    # Delivery Configuration
    delivery_address_id = fields.Many2one(
        'res.partner',
        string='Delivery Address',
        domain="[('parent_id', '=', partner_id), ('type', '=', 'delivery')]"
    )
    preferred_time_slot_id = fields.Many2one(
        'delivery.time.slot',
        string='Preferred Time Slot'
    )
    delivery_instructions = fields.Text(string='Delivery Instructions')

    # Delivery Schedule
    schedule_ids = fields.One2many(
        'subscription.delivery.schedule',
        'subscription_id',
        string='Delivery Schedule'
    )

    # Pause History
    pause_history_ids = fields.One2many(
        'subscription.pause.history',
        'subscription_id',
        string='Pause History'
    )
    current_month_pause_days = fields.Integer(
        compute='_compute_current_month_pause_days'
    )
    remaining_pause_days = fields.Integer(
        compute='_compute_remaining_pause_days'
    )

    # Billing
    billing_ids = fields.One2many(
        'subscription.billing',
        'subscription_id',
        string='Billing History'
    )
    payment_method_id = fields.Many2one(
        'payment.token',
        string='Payment Method',
        domain="[('partner_id', '=', partner_id)]"
    )
    auto_renew = fields.Boolean(string='Auto Renew', default=True)

    # Pricing (computed)
    subtotal = fields.Monetary(compute='_compute_totals', store=True)
    discount_amount = fields.Monetary(compute='_compute_totals', store=True)
    total_amount = fields.Monetary(compute='_compute_totals', store=True)
    currency_id = fields.Many2one(
        'res.currency',
        related='plan_id.currency_id'
    )

    # Skip tracking
    current_month_skips = fields.Integer(compute='_compute_current_month_skips')
    remaining_skips = fields.Integer(compute='_compute_remaining_skips')

    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('name', _('New')) == _('New'):
                vals['name'] = self.env['ir.sequence'].next_by_code(
                    'customer.subscription'
                ) or _('New')
        return super().create(vals_list)

    @api.depends('line_ids.subtotal', 'plan_id.discount_percentage')
    def _compute_totals(self):
        for subscription in self:
            subtotal = sum(subscription.line_ids.mapped('subtotal'))
            discount = subtotal * (subscription.plan_id.discount_percentage / 100)
            subscription.subtotal = subtotal
            subscription.discount_amount = discount
            subscription.total_amount = subtotal - discount

    @api.depends('start_date', 'plan_id.min_commitment_periods', 'plan_id.billing_period')
    def _compute_commitment_end_date(self):
        for subscription in self:
            if subscription.start_date and subscription.plan_id:
                periods = subscription.plan_id.min_commitment_periods
                period_type = subscription.plan_id.billing_period

                if period_type == 'weekly':
                    delta = relativedelta(weeks=periods)
                elif period_type == 'biweekly':
                    delta = relativedelta(weeks=periods * 2)
                else:  # monthly
                    delta = relativedelta(months=periods)

                subscription.commitment_end_date = subscription.start_date + delta
            else:
                subscription.commitment_end_date = False

    @api.depends('commitment_end_date')
    def _compute_can_cancel(self):
        today = fields.Date.today()
        for subscription in self:
            subscription.can_cancel = (
                subscription.commitment_end_date and
                today >= subscription.commitment_end_date
            )

    @api.depends('pause_history_ids')
    def _compute_current_month_pause_days(self):
        today = fields.Date.today()
        month_start = today.replace(day=1)

        for subscription in self:
            pause_days = 0
            for pause in subscription.pause_history_ids:
                if pause.state == 'completed':
                    # Calculate days within current month
                    pause_start = max(pause.start_date, month_start)
                    pause_end = min(pause.end_date or today, today)
                    if pause_end >= pause_start:
                        pause_days += (pause_end - pause_start).days + 1

            subscription.current_month_pause_days = pause_days

    @api.depends('current_month_pause_days', 'plan_id.max_pause_days_per_month')
    def _compute_remaining_pause_days(self):
        for subscription in self:
            max_days = subscription.plan_id.max_pause_days_per_month or 7
            subscription.remaining_pause_days = max(
                0, max_days - subscription.current_month_pause_days
            )

    @api.depends('schedule_ids.is_skipped')
    def _compute_current_month_skips(self):
        today = fields.Date.today()
        month_start = today.replace(day=1)
        month_end = (month_start + relativedelta(months=1)) - relativedelta(days=1)

        for subscription in self:
            skips = subscription.schedule_ids.filtered(
                lambda s: s.is_skipped and
                month_start <= s.scheduled_date <= month_end
            )
            subscription.current_month_skips = len(skips)

    @api.depends('current_month_skips', 'plan_id.max_skips_per_month')
    def _compute_remaining_skips(self):
        for subscription in self:
            max_skips = subscription.plan_id.max_skips_per_month or 4
            subscription.remaining_skips = max(
                0, max_skips - subscription.current_month_skips
            )

    def action_activate(self):
        """Activate subscription after successful payment"""
        self.ensure_one()
        if self.state != 'pending_payment':
            raise UserError(_("Only pending payment subscriptions can be activated"))

        self.write({
            'state': 'active',
            'next_delivery_date': self._calculate_next_delivery_date(),
            'next_billing_date': self._calculate_next_billing_date(),
        })

        # Generate initial delivery schedule
        self._generate_delivery_schedule(periods=4)

        # Send activation email
        self._send_activation_email()

    def action_pause(self, pause_days):
        """Pause subscription for specified days"""
        self.ensure_one()

        if self.state != 'active':
            raise UserError(_("Only active subscriptions can be paused"))

        if pause_days > self.remaining_pause_days:
            raise UserError(
                _("You can only pause for %d more days this month") %
                self.remaining_pause_days
            )

        pause_start = fields.Date.today()
        pause_end = pause_start + timedelta(days=pause_days - 1)

        # Create pause history record
        self.env['subscription.pause.history'].create({
            'subscription_id': self.id,
            'start_date': pause_start,
            'end_date': pause_end,
            'reason': 'customer_request',
            'state': 'active',
        })

        # Mark scheduled deliveries as paused
        self.schedule_ids.filtered(
            lambda s: s.scheduled_date >= pause_start and
            s.scheduled_date <= pause_end and
            s.state == 'scheduled'
        ).write({'state': 'paused'})

        self.write({'state': 'paused'})

    def action_resume(self):
        """Resume paused subscription"""
        self.ensure_one()

        if self.state != 'paused':
            raise UserError(_("Only paused subscriptions can be resumed"))

        # Complete active pause record
        active_pause = self.pause_history_ids.filtered(lambda p: p.state == 'active')
        if active_pause:
            active_pause.write({
                'end_date': fields.Date.today(),
                'state': 'completed',
            })

        # Reschedule paused deliveries
        self._reschedule_deliveries()

        self.write({
            'state': 'active',
            'next_delivery_date': self._calculate_next_delivery_date(),
        })

    def action_skip_delivery(self, delivery_id):
        """Skip a specific delivery"""
        self.ensure_one()
        delivery = self.schedule_ids.browse(delivery_id)

        if not delivery or delivery.subscription_id != self:
            raise UserError(_("Invalid delivery"))

        if delivery.state != 'scheduled':
            raise UserError(_("Only scheduled deliveries can be skipped"))

        # Check skip limit
        if self.remaining_skips <= 0:
            raise UserError(_("You have reached the maximum skips for this month"))

        # Check notice period
        min_hours = self.plan_id.min_skip_notice_hours or 24
        deadline = datetime.combine(delivery.scheduled_date, datetime.min.time())
        deadline -= timedelta(hours=min_hours)

        if datetime.now() > deadline:
            raise UserError(
                _("Deliveries must be skipped at least %d hours in advance") % min_hours
            )

        delivery.write({
            'is_skipped': True,
            'skip_reason': 'customer_request',
            'state': 'skipped',
        })

        # Optionally credit customer or extend subscription
        self._handle_skipped_delivery_credit(delivery)

    def _generate_delivery_schedule(self, periods=4):
        """Generate delivery schedule for future periods"""
        self.ensure_one()
        delivery_dates = self.plan_id.get_next_delivery_dates(
            self.next_delivery_date or self.start_date,
            count=periods * (7 if self.plan_id.frequency_type == 'daily' else 1)
        )

        for date in delivery_dates:
            existing = self.schedule_ids.filtered(
                lambda s: s.scheduled_date == date
            )
            if not existing:
                self.env['subscription.delivery.schedule'].create({
                    'subscription_id': self.id,
                    'scheduled_date': date,
                    'time_slot_id': self.preferred_time_slot_id.id,
                    'state': 'scheduled',
                })

    def _calculate_next_delivery_date(self):
        """Calculate the next delivery date"""
        self.ensure_one()
        today = fields.Date.today()

        # Find next scheduled delivery
        next_delivery = self.schedule_ids.filtered(
            lambda s: s.scheduled_date > today and
            s.state == 'scheduled'
        ).sorted('scheduled_date')

        if next_delivery:
            return next_delivery[0].scheduled_date

        # Calculate from plan if no schedule exists
        dates = self.plan_id.get_next_delivery_dates(today, count=1)
        return dates[0] if dates else False

    def _calculate_next_billing_date(self):
        """Calculate the next billing date"""
        self.ensure_one()
        last_billing = self.last_billing_date or self.start_date
        period = self.plan_id.billing_period

        if period == 'weekly':
            return last_billing + relativedelta(weeks=1)
        elif period == 'biweekly':
            return last_billing + relativedelta(weeks=2)
        else:  # monthly
            return last_billing + relativedelta(months=1)


class CustomerSubscriptionLine(models.Model):
    _name = 'customer.subscription.line'
    _description = 'Subscription Line Item'

    subscription_id = fields.Many2one(
        'customer.subscription',
        string='Subscription',
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
        default=1.0,
        digits='Product Unit of Measure'
    )
    uom_id = fields.Many2one(
        'uom.uom',
        string='Unit of Measure',
        related='product_id.uom_id'
    )
    unit_price = fields.Monetary(
        string='Unit Price',
        currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        'res.currency',
        related='subscription_id.currency_id'
    )
    subtotal = fields.Monetary(
        compute='_compute_subtotal',
        store=True
    )

    @api.depends('quantity', 'unit_price')
    def _compute_subtotal(self):
        for line in self:
            line.subtotal = line.quantity * line.unit_price

    @api.onchange('product_id')
    def _onchange_product_id(self):
        if self.product_id:
            self.unit_price = self.product_id.lst_price
```

#### Dev 2 — Full-Stack / DevOps (8h)

**Task 221.2.1: Delivery Schedule Model (4h)**

```python
# smart_dairy_addons/smart_subscription/models/delivery_schedule.py
from odoo import models, fields, api, _
from odoo.exceptions import UserError

class SubscriptionDeliverySchedule(models.Model):
    _name = 'subscription.delivery.schedule'
    _description = 'Subscription Delivery Schedule'
    _order = 'scheduled_date'

    subscription_id = fields.Many2one(
        'customer.subscription',
        string='Subscription',
        required=True,
        ondelete='cascade'
    )
    partner_id = fields.Many2one(
        'res.partner',
        related='subscription_id.partner_id',
        store=True
    )
    scheduled_date = fields.Date(
        string='Scheduled Date',
        required=True,
        index=True
    )
    time_slot_id = fields.Many2one(
        'delivery.time.slot',
        string='Time Slot'
    )
    state = fields.Selection([
        ('scheduled', 'Scheduled'),
        ('paused', 'Paused'),
        ('skipped', 'Skipped'),
        ('processing', 'Processing'),
        ('delivered', 'Delivered'),
        ('failed', 'Failed'),
    ], string='Status', default='scheduled')

    # Skip tracking
    is_skipped = fields.Boolean(string='Is Skipped', default=False)
    skip_reason = fields.Selection([
        ('customer_request', 'Customer Request'),
        ('stock_unavailable', 'Stock Unavailable'),
        ('delivery_issue', 'Delivery Issue'),
        ('payment_failed', 'Payment Failed'),
    ], string='Skip Reason')

    # Delivery tracking
    sale_order_id = fields.Many2one(
        'sale.order',
        string='Sales Order'
    )
    picking_id = fields.Many2one(
        'stock.picking',
        string='Delivery Order'
    )
    delivery_note = fields.Text(string='Delivery Note')

    # Actual delivery
    actual_delivery_date = fields.Datetime(string='Actual Delivery Time')
    delivered_by = fields.Many2one('res.users', string='Delivered By')

    def action_create_delivery(self):
        """Create sales order and delivery for scheduled deliveries"""
        for schedule in self.filtered(lambda s: s.state == 'scheduled'):
            # Create sale order
            order_vals = schedule._prepare_sale_order_vals()
            order = self.env['sale.order'].create(order_vals)
            order.action_confirm()

            schedule.write({
                'sale_order_id': order.id,
                'picking_id': order.picking_ids[0].id if order.picking_ids else False,
                'state': 'processing',
            })

    def _prepare_sale_order_vals(self):
        """Prepare sale order values from subscription"""
        subscription = self.subscription_id
        return {
            'partner_id': subscription.partner_id.id,
            'partner_shipping_id': (
                subscription.delivery_address_id.id or
                subscription.partner_id.id
            ),
            'subscription_id': subscription.id,
            'delivery_schedule_id': self.id,
            'order_line': [
                (0, 0, {
                    'product_id': line.product_id.id,
                    'product_uom_qty': line.quantity,
                    'price_unit': line.unit_price * (
                        1 - subscription.plan_id.discount_percentage / 100
                    ),
                })
                for line in subscription.line_ids
            ],
        }

    def action_mark_delivered(self):
        """Mark delivery as completed"""
        self.ensure_one()
        if self.state != 'processing':
            raise UserError(_("Only processing deliveries can be marked as delivered"))

        self.write({
            'state': 'delivered',
            'actual_delivery_date': fields.Datetime.now(),
            'delivered_by': self.env.user.id,
        })


class DeliveryTimeSlot(models.Model):
    _name = 'delivery.time.slot'
    _description = 'Delivery Time Slot'
    _order = 'start_time'

    name = fields.Char(string='Slot Name', required=True)
    start_time = fields.Float(string='Start Time', required=True)
    end_time = fields.Float(string='End Time', required=True)
    max_deliveries = fields.Integer(
        string='Max Deliveries per Slot',
        default=50
    )
    active = fields.Boolean(default=True)

    # Availability by day
    available_monday = fields.Boolean(default=True)
    available_tuesday = fields.Boolean(default=True)
    available_wednesday = fields.Boolean(default=True)
    available_thursday = fields.Boolean(default=True)
    available_friday = fields.Boolean(default=True)
    available_saturday = fields.Boolean(default=True)
    available_sunday = fields.Boolean(default=False)

    @api.constrains('start_time', 'end_time')
    def _check_times(self):
        for slot in self:
            if slot.start_time >= slot.end_time:
                raise UserError(_("End time must be after start time"))
            if slot.start_time < 0 or slot.end_time > 24:
                raise UserError(_("Times must be between 0 and 24"))
```

**Task 221.2.2: Time Slot Data Configuration (2h)**

```xml
<!-- smart_dairy_addons/smart_subscription/data/delivery_slots_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <!-- Morning Slot -->
    <record id="time_slot_morning" model="delivery.time.slot">
        <field name="name">Morning (6:00 AM - 9:00 AM)</field>
        <field name="start_time">6.0</field>
        <field name="end_time">9.0</field>
        <field name="max_deliveries">100</field>
        <field name="available_sunday">False</field>
    </record>

    <!-- Mid-Morning Slot -->
    <record id="time_slot_mid_morning" model="delivery.time.slot">
        <field name="name">Mid-Morning (9:00 AM - 12:00 PM)</field>
        <field name="start_time">9.0</field>
        <field name="end_time">12.0</field>
        <field name="max_deliveries">75</field>
    </record>

    <!-- Afternoon Slot -->
    <record id="time_slot_afternoon" model="delivery.time.slot">
        <field name="name">Afternoon (2:00 PM - 5:00 PM)</field>
        <field name="start_time">14.0</field>
        <field name="end_time">17.0</field>
        <field name="max_deliveries">75</field>
    </record>

    <!-- Evening Slot -->
    <record id="time_slot_evening" model="delivery.time.slot">
        <field name="name">Evening (5:00 PM - 8:00 PM)</field>
        <field name="start_time">17.0</field>
        <field name="end_time">20.0</field>
        <field name="max_deliveries">100</field>
    </record>
</odoo>
```

**Task 221.2.3: Subscription Plan Templates (2h)**

```xml
<!-- smart_dairy_addons/smart_subscription/data/subscription_plan_data.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo noupdate="1">
    <!-- Sequence -->
    <record id="seq_customer_subscription" model="ir.sequence">
        <field name="name">Customer Subscription</field>
        <field name="code">customer.subscription</field>
        <field name="prefix">SUB-%(year)s-</field>
        <field name="padding">5</field>
    </record>

    <!-- Daily Fresh Plan -->
    <record id="plan_daily_fresh" model="subscription.plan">
        <field name="name">Daily Fresh</field>
        <field name="code">DAILY-FRESH</field>
        <field name="description"><![CDATA[
            <p>Get fresh milk delivered to your doorstep every day!</p>
            <ul>
                <li>Fresh pasteurized milk daily</li>
                <li>Delivered before 7 AM</li>
                <li>5% discount on all products</li>
            </ul>
        ]]></field>
        <field name="sequence">1</field>
        <field name="frequency_type">daily</field>
        <field name="frequency_interval">1</field>
        <field name="min_commitment_periods">30</field>
        <field name="billing_period">monthly</field>
        <field name="discount_percentage">5.0</field>
        <field name="max_pause_days_per_month">7</field>
        <field name="min_skip_notice_hours">12</field>
        <field name="max_skips_per_month">5</field>
        <field name="highlight">True</field>
        <field name="badge_text">Most Popular</field>
    </record>

    <!-- Weekly Essentials Plan -->
    <record id="plan_weekly_essentials" model="subscription.plan">
        <field name="name">Weekly Essentials</field>
        <field name="code">WEEKLY-ESS</field>
        <field name="description"><![CDATA[
            <p>Your weekly dairy essentials delivered every week!</p>
            <ul>
                <li>Milk, Yogurt, and Paneer</li>
                <li>Choose your delivery day</li>
                <li>7% discount on all products</li>
            </ul>
        ]]></field>
        <field name="sequence">2</field>
        <field name="frequency_type">weekly</field>
        <field name="frequency_interval">1</field>
        <field name="delivery_weekdays">5</field>
        <field name="min_commitment_periods">4</field>
        <field name="billing_period">monthly</field>
        <field name="discount_percentage">7.0</field>
        <field name="max_pause_days_per_month">7</field>
        <field name="min_skip_notice_hours">24</field>
        <field name="max_skips_per_month">2</field>
    </record>

    <!-- Custom Plan -->
    <record id="plan_custom" model="subscription.plan">
        <field name="name">Custom Plan</field>
        <field name="code">CUSTOM</field>
        <field name="description"><![CDATA[
            <p>Create your own delivery schedule!</p>
            <ul>
                <li>Choose any products</li>
                <li>Set your delivery frequency</li>
                <li>3% discount on all products</li>
            </ul>
        ]]></field>
        <field name="sequence">3</field>
        <field name="frequency_type">custom</field>
        <field name="frequency_interval">3</field>
        <field name="min_commitment_periods">2</field>
        <field name="billing_period">biweekly</field>
        <field name="discount_percentage">3.0</field>
        <field name="max_pause_days_per_month">7</field>
        <field name="min_skip_notice_hours">48</field>
        <field name="max_skips_per_month">4</field>
    </record>
</odoo>
```

#### Dev 3 — Frontend / Mobile Lead (8h)

**Task 221.3.1: Subscription Views (8h)**

```xml
<!-- smart_dairy_addons/smart_subscription/views/subscription_plan_views.xml -->
<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Subscription Plan Form View -->
    <record id="subscription_plan_form" model="ir.ui.view">
        <field name="name">subscription.plan.form</field>
        <field name="model">subscription.plan</field>
        <field name="arch" type="xml">
            <form>
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button name="%(action_customer_subscriptions)d" type="action"
                                class="oe_stat_button" icon="fa-users">
                            <field name="subscription_count" widget="statinfo"
                                   string="Active Subscriptions"/>
                        </button>
                    </div>
                    <field name="image" widget="image" class="oe_avatar"/>
                    <div class="oe_title">
                        <h1>
                            <field name="name" placeholder="Plan Name"/>
                        </h1>
                    </div>
                    <group>
                        <group string="Basic Info">
                            <field name="code"/>
                            <field name="active"/>
                            <field name="website_published"/>
                            <field name="highlight"/>
                            <field name="badge_text"/>
                        </group>
                        <group string="Frequency">
                            <field name="frequency_type"/>
                            <field name="frequency_interval"/>
                            <field name="delivery_weekdays"
                                   invisible="frequency_type != 'weekly'"/>
                        </group>
                    </group>
                    <group>
                        <group string="Commitment & Billing">
                            <field name="min_commitment_periods"/>
                            <field name="billing_period"/>
                            <field name="discount_percentage"/>
                            <field name="setup_fee"/>
                            <field name="currency_id"/>
                        </group>
                        <group string="Pause/Skip Rules">
                            <field name="max_pause_days_per_month"/>
                            <field name="min_skip_notice_hours"/>
                            <field name="max_skips_per_month"/>
                        </group>
                    </group>
                    <notebook>
                        <page string="Description">
                            <field name="description"/>
                        </page>
                        <page string="Products">
                            <group>
                                <field name="min_products"/>
                                <field name="max_products"/>
                            </group>
                            <field name="allowed_product_ids" widget="many2many_tags"/>
                            <field name="suggested_product_ids" widget="many2many_tags"/>
                        </page>
                    </notebook>
                </sheet>
            </form>
        </field>
    </record>

    <!-- Subscription Plan Tree View -->
    <record id="subscription_plan_tree" model="ir.ui.view">
        <field name="name">subscription.plan.tree</field>
        <field name="model">subscription.plan</field>
        <field name="arch" type="xml">
            <tree>
                <field name="sequence" widget="handle"/>
                <field name="name"/>
                <field name="code"/>
                <field name="frequency_type"/>
                <field name="discount_percentage"/>
                <field name="subscription_count"/>
                <field name="website_published"/>
                <field name="active"/>
            </tree>
        </field>
    </record>
</odoo>
```

*(Days 222-230 continue with similar detail for remaining deliverables)*

---

## 4. Technical Specifications

### 4.1 Data Models Summary

| Model | Description | Key Fields |
|-------|-------------|------------|
| `subscription.plan` | Plan templates | frequency_type, discount_percentage, pause_rules |
| `customer.subscription` | Customer subscriptions | state, dates, lines, schedules |
| `customer.subscription.line` | Subscription products | product_id, quantity, price |
| `subscription.delivery.schedule` | Delivery calendar | scheduled_date, state, is_skipped |
| `subscription.pause.history` | Pause records | start_date, end_date, reason |
| `subscription.billing` | Billing history | amount, payment_state, invoice_id |
| `delivery.time.slot` | Time slot config | start_time, end_time, max_deliveries |

### 4.2 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/subscriptions/plans` | GET | List available plans |
| `/api/v1/subscriptions` | GET/POST | List/create subscriptions |
| `/api/v1/subscriptions/{id}` | GET/PUT | Get/update subscription |
| `/api/v1/subscriptions/{id}/pause` | POST | Pause subscription |
| `/api/v1/subscriptions/{id}/resume` | POST | Resume subscription |
| `/api/v1/subscriptions/{id}/skip/{delivery_id}` | POST | Skip delivery |
| `/api/v1/subscriptions/{id}/schedule` | GET | Get delivery schedule |

### 4.3 Celery Tasks

| Task | Schedule | Description |
|------|----------|-------------|
| `generate_daily_deliveries` | Daily 4 AM | Create delivery orders |
| `process_subscription_billing` | Daily 6 AM | Bill due subscriptions |
| `retry_failed_payments` | Daily 10 AM | Retry failed payments |
| `send_delivery_reminders` | Daily 6 PM | Send next-day reminders |
| `expire_subscriptions` | Daily 12 AM | Mark expired subscriptions |

---

## 5. Testing & Validation

### 5.1 Unit Test Cases

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| TC-23-001 | Create daily subscription | Subscription created with daily schedule |
| TC-23-002 | Pause subscription | State changed, deliveries marked paused |
| TC-23-003 | Exceed pause limit | Error thrown with message |
| TC-23-004 | Skip delivery | Delivery marked skipped, credit applied |
| TC-23-005 | Skip after deadline | Error thrown |
| TC-23-006 | Generate billing | Invoice created with correct amount |
| TC-23-007 | Payment retry | Retry attempted at correct intervals |

---

## 6. Risk & Mitigation

| Risk ID | Risk | Probability | Impact | Mitigation |
|---------|------|-------------|--------|------------|
| R23-001 | Payment failures | Medium | High | Retry logic, manual fallback |
| R23-002 | Delivery scheduling conflicts | Low | Medium | Capacity planning |
| R23-003 | Customer abuse of skip/pause | Low | Low | Limits and validation |
| R23-004 | Billing calculation errors | Low | High | Comprehensive testing |

---

## 7. Dependencies & Handoffs

### 7.1 Prerequisites
- Payment gateway integration (MS-18)
- Customer accounts and addresses
- Product catalog with pricing
- Mobile app foundation (MS-22)

### 7.2 Outputs for Subsequent Milestones

| Output | Consumer | Description |
|--------|----------|-------------|
| Subscription model | MS-24 (Loyalty) | Subscription-based points |
| Recurring billing | MS-25 (Checkout) | Payment method reuse |
| Delivery schedule | MS-27 (Analytics) | Subscription analytics |

---

**Document End**

*Milestone 23: Subscription Management Engine*
*Smart Dairy Digital Smart Portal + ERP System*
*Phase 3 — E-Commerce, Mobile & Analytics*
*Version 1.0 | February 2026*
