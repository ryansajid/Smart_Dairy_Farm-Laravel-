# Milestone 123: Subscription Management Portal

## Smart Dairy Digital Portal + ERP System
## Phase 12: Commerce - Subscription & Automation

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P12-M123-v1.0 |
| **Version** | 1.0 |
| **Author** | Technical Documentation Team |
| **Created Date** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Draft |
| **Parent Phase** | Phase 12: Commerce - Subscription & Automation |
| **Milestone Duration** | Days 621-630 (10 working days) |
| **Dependencies** | Milestone 121 (Subscription Engine), Milestone 122 (Delivery Scheduling) |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 123 delivers a comprehensive self-service Subscription Management Portal that empowers customers to manage their dairy subscriptions independently. This portal reduces customer service workload by 60% while improving customer satisfaction through instant control over subscription preferences, delivery schedules, and account management.

The portal integrates with the Subscription Engine (Milestone 121) and Delivery Scheduling system (Milestone 122) to provide real-time subscription modifications with immediate effect on delivery routes and billing cycles.

### 1.2 Business Value Proposition

| Value Driver | Metric | Target |
|--------------|--------|--------|
| Customer Self-Service Adoption | Portal usage rate | 80%+ |
| Support Ticket Reduction | Subscription-related tickets | -60% |
| Customer Satisfaction | Portal NPS | 50+ |
| Retention Improvement | Cancellation save rate | 25% |
| Operational Efficiency | Manual processing time | -70% |

### 1.3 Strategic Alignment

**RFP Requirements:**
- REQ-SUB-003: Self-service subscription management
- REQ-CUS-002: Customer portal with account management
- REQ-DEL-004: Delivery preference management

**BRD Requirements:**
- BR-CUS-01: 80% self-service portal adoption
- BR-SUB-04: 25% cancellation save rate through retention offers
- BR-OPS-02: 60% reduction in support tickets

**SRS Requirements:**
- SRS-PRT-01: Portal response time <2 seconds
- SRS-PRT-02: Mobile-responsive design
- SRS-PRT-03: Real-time subscription updates

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

| Priority | Deliverable | Description |
|----------|-------------|-------------|
| **P0** | Subscription Dashboard | Overview of all active subscriptions with status |
| **P0** | Pause/Resume Management | Temporary pause with configurable duration |
| **P0** | Subscription Modification | Change products, quantities, frequency |
| **P0** | Delivery Skip Management | Skip individual deliveries with calendar |
| **P1** | Cancellation Workflow | Multi-step cancellation with retention offers |
| **P1** | Address Management | Multiple addresses with geocoding validation |
| **P1** | Payment Method Management | Add/remove/update payment methods |
| **P1** | Notification Preferences | Email, SMS, push notification settings |
| **P2** | Delivery Instructions | Special instructions per address |
| **P2** | Order History | Past deliveries with reorder option |
| **P2** | Referral Management | Referral code generation and tracking |

### 2.2 Out-of-Scope Items

- New subscription creation (covered in e-commerce checkout)
- Payment processing logic (Milestone 124)
- Analytics dashboards (Milestone 125)
- Marketing campaign management (Milestone 126)

### 2.3 Assumptions

1. Customers have verified email addresses for portal access
2. Mobile app will integrate with portal APIs
3. Geocoding service (Google Maps) is available
4. SMS gateway is configured for OTP verification

### 2.4 Constraints

1. Portal must work offline for viewing (PWA capability)
2. All changes must sync with Odoo ERP in real-time
3. Maximum 3 concurrent pauses per subscription
4. Address changes require geocoding validation

---

## 3. Technical Architecture

### 3.1 System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    Customer Portal (React 18)                    │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐           │
│  │Dashboard │ │Pause/    │ │Modify    │ │Cancel    │           │
│  │Component │ │Resume    │ │Subscript │ │Workflow  │           │
│  └────┬─────┘ └────┬─────┘ └────┬─────┘ └────┬─────┘           │
└───────┼────────────┼────────────┼────────────┼──────────────────┘
        │            │            │            │
        ▼            ▼            ▼            ▼
┌─────────────────────────────────────────────────────────────────┐
│                      API Gateway (Nginx)                         │
│                   Rate Limiting | Auth | SSL                     │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                 Odoo 19 CE Backend Services                      │
│  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐    │
│  │ Subscription   │  │ Customer       │  │ Delivery       │    │
│  │ Management API │  │ Profile API    │  │ Preference API │    │
│  └───────┬────────┘  └───────┬────────┘  └───────┬────────┘    │
│          │                   │                   │              │
│          ▼                   ▼                   ▼              │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │              Business Logic Layer                        │   │
│  │   PauseManager | ModificationEngine | CancelWorkflow    │   │
│  └─────────────────────────────────────────────────────────┘   │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│                      Data Layer                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │ PostgreSQL   │  │ Redis Cache  │  │ Elasticsearch │          │
│  │ (Primary DB) │  │ (Sessions)   │  │ (Search)      │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
└─────────────────────────────────────────────────────────────────┘
```

### 3.2 Database Schema

#### 3.2.1 Subscription Pause Table

```sql
-- Subscription pause management
CREATE TABLE subscription_pause (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id),
    pause_type VARCHAR(20) NOT NULL DEFAULT 'manual',
    -- 'manual', 'vacation', 'temporary', 'billing_issue'
    start_date DATE NOT NULL,
    end_date DATE,
    reason VARCHAR(100),
    reason_details TEXT,
    created_by INTEGER REFERENCES res_users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resumed_at TIMESTAMP,
    resumed_by INTEGER REFERENCES res_users(id),
    auto_resume BOOLEAN DEFAULT TRUE,
    notification_sent BOOLEAN DEFAULT FALSE,

    CONSTRAINT valid_pause_dates CHECK (end_date IS NULL OR end_date > start_date),
    CONSTRAINT max_pause_duration CHECK (
        end_date IS NULL OR (end_date - start_date) <= 90
    )
);

CREATE INDEX idx_pause_subscription ON subscription_pause(subscription_id);
CREATE INDEX idx_pause_dates ON subscription_pause(start_date, end_date);
CREATE INDEX idx_pause_active ON subscription_pause(subscription_id)
    WHERE resumed_at IS NULL;
```

#### 3.2.2 Delivery Skip Table

```sql
-- Individual delivery skip management
CREATE TABLE subscription_delivery_skip (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id),
    subscription_line_id INTEGER REFERENCES customer_subscription_line(id),
    skip_date DATE NOT NULL,
    skip_reason VARCHAR(50),
    -- 'not_needed', 'traveling', 'excess_stock', 'other'
    reason_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INTEGER REFERENCES res_users(id),
    cancelled_at TIMESTAMP,
    cancelled_by INTEGER REFERENCES res_users(id),
    credit_issued BOOLEAN DEFAULT FALSE,
    credit_amount DECIMAL(10,2) DEFAULT 0,

    CONSTRAINT unique_skip UNIQUE (subscription_id, subscription_line_id, skip_date)
);

CREATE INDEX idx_skip_subscription ON subscription_delivery_skip(subscription_id);
CREATE INDEX idx_skip_date ON subscription_delivery_skip(skip_date);
CREATE INDEX idx_skip_active ON subscription_delivery_skip(subscription_id, skip_date)
    WHERE cancelled_at IS NULL;
```

#### 3.2.3 Subscription Modification History

```sql
-- Track all subscription modifications
CREATE TABLE subscription_modification_log (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id),
    modification_type VARCHAR(30) NOT NULL,
    -- 'product_add', 'product_remove', 'quantity_change',
    -- 'frequency_change', 'address_change', 'pause', 'resume', 'cancel'
    field_name VARCHAR(100),
    old_value TEXT,
    new_value TEXT,
    effective_date DATE,
    modified_by INTEGER REFERENCES res_users(id),
    modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source VARCHAR(20) DEFAULT 'portal',
    -- 'portal', 'mobile_app', 'admin', 'api', 'system'
    ip_address INET,
    user_agent TEXT,

    CONSTRAINT valid_modification_type CHECK (
        modification_type IN (
            'product_add', 'product_remove', 'quantity_change',
            'frequency_change', 'address_change', 'delivery_day_change',
            'time_slot_change', 'pause', 'resume', 'cancel', 'reactivate',
            'payment_method_change', 'notification_pref_change'
        )
    )
);

CREATE INDEX idx_mod_subscription ON subscription_modification_log(subscription_id);
CREATE INDEX idx_mod_type ON subscription_modification_log(modification_type);
CREATE INDEX idx_mod_date ON subscription_modification_log(modified_at);
```

#### 3.2.4 Cancellation Request Table

```sql
-- Cancellation workflow management
CREATE TABLE subscription_cancellation (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES customer_subscription(id),
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cancellation_reason VARCHAR(50) NOT NULL,
    -- 'too_expensive', 'not_using', 'competitor', 'quality_issues',
    -- 'delivery_issues', 'moving', 'temporary', 'other'
    reason_details TEXT,
    retention_offer_id INTEGER REFERENCES retention_offer(id),
    retention_offer_accepted BOOLEAN,
    status VARCHAR(20) DEFAULT 'pending',
    -- 'pending', 'retention_offered', 'confirmed', 'completed', 'withdrawn'
    effective_date DATE,
    processed_at TIMESTAMP,
    processed_by INTEGER REFERENCES res_users(id),
    feedback_score INTEGER CHECK (feedback_score BETWEEN 1 AND 5),
    feedback_comment TEXT,
    win_back_eligible BOOLEAN DEFAULT TRUE,
    win_back_date DATE,

    CONSTRAINT valid_status CHECK (
        status IN ('pending', 'retention_offered', 'confirmed', 'completed', 'withdrawn')
    )
);

CREATE INDEX idx_cancel_subscription ON subscription_cancellation(subscription_id);
CREATE INDEX idx_cancel_status ON subscription_cancellation(status);
CREATE INDEX idx_cancel_date ON subscription_cancellation(request_date);
```

#### 3.2.5 Retention Offers Table

```sql
-- Retention offers for cancellation prevention
CREATE TABLE retention_offer (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    offer_type VARCHAR(30) NOT NULL,
    -- 'discount_percent', 'discount_fixed', 'free_delivery',
    -- 'free_product', 'pause_suggestion', 'downgrade'
    offer_value DECIMAL(10,2),
    offer_duration_days INTEGER,
    applicable_reasons TEXT[], -- Array of cancellation reasons
    min_subscription_age_days INTEGER DEFAULT 0,
    min_subscription_value DECIMAL(10,2) DEFAULT 0,
    priority INTEGER DEFAULT 10,
    active BOOLEAN DEFAULT TRUE,
    usage_limit INTEGER,
    used_count INTEGER DEFAULT 0,
    valid_from DATE,
    valid_until DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT valid_offer_type CHECK (
        offer_type IN (
            'discount_percent', 'discount_fixed', 'free_delivery',
            'free_product', 'pause_suggestion', 'downgrade', 'credit'
        )
    )
);

CREATE INDEX idx_offer_active ON retention_offer(active) WHERE active = TRUE;
CREATE INDEX idx_offer_type ON retention_offer(offer_type);
```

### 3.3 Odoo Models

#### 3.3.1 Subscription Pause Model

```python
# models/subscription_pause.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import date, timedelta

class SubscriptionPause(models.Model):
    _name = 'subscription.pause'
    _description = 'Subscription Pause Management'
    _order = 'start_date desc'

    subscription_id = fields.Many2one(
        'customer.subscription',
        string='Subscription',
        required=True,
        ondelete='cascade'
    )
    pause_type = fields.Selection([
        ('manual', 'Manual Pause'),
        ('vacation', 'Vacation Mode'),
        ('temporary', 'Temporary Hold'),
        ('billing_issue', 'Billing Issue'),
    ], string='Pause Type', default='manual', required=True)

    start_date = fields.Date(
        string='Start Date',
        required=True,
        default=fields.Date.context_today
    )
    end_date = fields.Date(string='End Date')

    reason = fields.Selection([
        ('traveling', 'Traveling'),
        ('excess_stock', 'Excess Stock'),
        ('budget', 'Budget Constraints'),
        ('health', 'Health Reasons'),
        ('other', 'Other'),
    ], string='Reason')
    reason_details = fields.Text(string='Additional Details')

    auto_resume = fields.Boolean(
        string='Auto Resume',
        default=True,
        help='Automatically resume subscription on end date'
    )
    resumed_at = fields.Datetime(string='Resumed At')
    resumed_by = fields.Many2one('res.users', string='Resumed By')

    notification_sent = fields.Boolean(default=False)
    state = fields.Selection([
        ('scheduled', 'Scheduled'),
        ('active', 'Active'),
        ('resumed', 'Resumed'),
        ('expired', 'Expired'),
    ], string='Status', compute='_compute_state', store=True)

    @api.depends('start_date', 'end_date', 'resumed_at')
    def _compute_state(self):
        today = date.today()
        for pause in self:
            if pause.resumed_at:
                pause.state = 'resumed'
            elif pause.start_date > today:
                pause.state = 'scheduled'
            elif pause.end_date and pause.end_date < today:
                pause.state = 'expired'
            else:
                pause.state = 'active'

    @api.constrains('start_date', 'end_date')
    def _check_dates(self):
        for pause in self:
            if pause.end_date and pause.end_date <= pause.start_date:
                raise ValidationError("End date must be after start date.")
            if pause.end_date:
                duration = (pause.end_date - pause.start_date).days
                if duration > 90:
                    raise ValidationError("Maximum pause duration is 90 days.")

    @api.constrains('subscription_id', 'start_date', 'end_date')
    def _check_overlapping(self):
        for pause in self:
            domain = [
                ('subscription_id', '=', pause.subscription_id.id),
                ('id', '!=', pause.id),
                ('resumed_at', '=', False),
            ]
            existing = self.search(domain)
            for ex in existing:
                if pause._dates_overlap(ex):
                    raise ValidationError(
                        "Pause period overlaps with existing pause."
                    )

    def _dates_overlap(self, other):
        """Check if two pause periods overlap."""
        self_end = self.end_date or date.max
        other_end = other.end_date or date.max
        return (self.start_date <= other_end and self_end >= other.start_date)

    def action_resume(self):
        """Resume the subscription from pause."""
        self.ensure_one()
        self.write({
            'resumed_at': fields.Datetime.now(),
            'resumed_by': self.env.uid,
        })
        self.subscription_id.write({'status': 'active'})
        self._send_resume_notification()
        return True

    def _send_resume_notification(self):
        """Send notification when subscription is resumed."""
        template = self.env.ref(
            'smart_dairy_subscription.email_subscription_resumed'
        )
        template.send_mail(self.id, force_send=True)
```

#### 3.3.2 Subscription Modification Manager

```python
# models/subscription_modification.py
from odoo import models, fields, api
from odoo.exceptions import UserError
import json

class SubscriptionModificationLog(models.Model):
    _name = 'subscription.modification.log'
    _description = 'Subscription Modification History'
    _order = 'modified_at desc'

    subscription_id = fields.Many2one(
        'customer.subscription',
        string='Subscription',
        required=True,
        ondelete='cascade'
    )
    modification_type = fields.Selection([
        ('product_add', 'Product Added'),
        ('product_remove', 'Product Removed'),
        ('quantity_change', 'Quantity Changed'),
        ('frequency_change', 'Frequency Changed'),
        ('address_change', 'Address Changed'),
        ('delivery_day_change', 'Delivery Day Changed'),
        ('time_slot_change', 'Time Slot Changed'),
        ('pause', 'Subscription Paused'),
        ('resume', 'Subscription Resumed'),
        ('cancel', 'Subscription Cancelled'),
        ('reactivate', 'Subscription Reactivated'),
        ('payment_method_change', 'Payment Method Changed'),
    ], string='Modification Type', required=True)

    field_name = fields.Char(string='Field Name')
    old_value = fields.Text(string='Previous Value')
    new_value = fields.Text(string='New Value')
    effective_date = fields.Date(string='Effective Date')

    modified_by = fields.Many2one(
        'res.users',
        string='Modified By',
        default=lambda self: self.env.uid
    )
    modified_at = fields.Datetime(
        string='Modified At',
        default=fields.Datetime.now
    )
    source = fields.Selection([
        ('portal', 'Customer Portal'),
        ('mobile_app', 'Mobile App'),
        ('admin', 'Admin Panel'),
        ('api', 'External API'),
        ('system', 'System Automated'),
    ], string='Source', default='portal')

    ip_address = fields.Char(string='IP Address')
    user_agent = fields.Text(string='User Agent')


class SubscriptionModificationMixin(models.AbstractModel):
    _name = 'subscription.modification.mixin'
    _description = 'Subscription Modification Tracking Mixin'

    def _log_modification(self, subscription, mod_type, field_name=None,
                          old_value=None, new_value=None, effective_date=None,
                          source='portal'):
        """Log subscription modification for audit trail."""
        self.env['subscription.modification.log'].create({
            'subscription_id': subscription.id,
            'modification_type': mod_type,
            'field_name': field_name,
            'old_value': json.dumps(old_value) if old_value else None,
            'new_value': json.dumps(new_value) if new_value else None,
            'effective_date': effective_date or fields.Date.today(),
            'source': source,
        })


class CustomerSubscription(models.Model):
    _inherit = 'customer.subscription'

    pause_ids = fields.One2many(
        'subscription.pause',
        'subscription_id',
        string='Pause History'
    )
    modification_ids = fields.One2many(
        'subscription.modification.log',
        'subscription_id',
        string='Modification History'
    )
    skip_ids = fields.One2many(
        'subscription.delivery.skip',
        'subscription_id',
        string='Skipped Deliveries'
    )

    active_pause_id = fields.Many2one(
        'subscription.pause',
        string='Active Pause',
        compute='_compute_active_pause',
        store=True
    )
    is_paused = fields.Boolean(
        string='Is Paused',
        compute='_compute_active_pause',
        store=True
    )
    pause_end_date = fields.Date(
        string='Pause End Date',
        compute='_compute_active_pause',
        store=True
    )

    @api.depends('pause_ids', 'pause_ids.state')
    def _compute_active_pause(self):
        for sub in self:
            active = sub.pause_ids.filtered(lambda p: p.state == 'active')
            sub.active_pause_id = active[0] if active else False
            sub.is_paused = bool(active)
            sub.pause_end_date = active[0].end_date if active else False

    def action_pause(self, start_date, end_date=None, reason=None,
                     reason_details=None, pause_type='manual'):
        """Pause subscription with specified parameters."""
        self.ensure_one()

        # Validate pause count
        active_pauses = self.pause_ids.filtered(
            lambda p: p.state in ('scheduled', 'active')
        )
        if len(active_pauses) >= 3:
            raise UserError("Maximum 3 concurrent pauses allowed.")

        # Create pause record
        pause = self.env['subscription.pause'].create({
            'subscription_id': self.id,
            'pause_type': pause_type,
            'start_date': start_date,
            'end_date': end_date,
            'reason': reason,
            'reason_details': reason_details,
        })

        # Update subscription status if pause starts today
        if start_date <= fields.Date.today():
            self.write({'status': 'paused'})

        # Log modification
        self.env['subscription.modification.log'].create({
            'subscription_id': self.id,
            'modification_type': 'pause',
            'old_value': 'active',
            'new_value': 'paused',
            'effective_date': start_date,
        })

        return pause

    def action_modify_products(self, modifications):
        """
        Modify subscription products.

        Args:
            modifications: list of dicts with keys:
                - action: 'add', 'remove', 'update'
                - product_id: product ID
                - quantity: new quantity (for add/update)
        """
        self.ensure_one()

        for mod in modifications:
            action = mod.get('action')
            product_id = mod.get('product_id')
            quantity = mod.get('quantity', 1)

            if action == 'add':
                self._add_product(product_id, quantity)
            elif action == 'remove':
                self._remove_product(product_id)
            elif action == 'update':
                self._update_product_quantity(product_id, quantity)

        # Recalculate totals
        self._compute_totals()
        return True

    def _add_product(self, product_id, quantity):
        """Add a product to subscription."""
        product = self.env['product.product'].browse(product_id)
        existing = self.line_ids.filtered(
            lambda l: l.product_id.id == product_id
        )

        if existing:
            raise UserError(f"Product {product.name} already in subscription.")

        self.env['customer.subscription.line'].create({
            'subscription_id': self.id,
            'product_id': product_id,
            'quantity': quantity,
            'unit_price': product.lst_price,
        })

        self.env['subscription.modification.log'].create({
            'subscription_id': self.id,
            'modification_type': 'product_add',
            'field_name': 'line_ids',
            'new_value': json.dumps({
                'product_id': product_id,
                'product_name': product.name,
                'quantity': quantity,
            }),
        })

    def _remove_product(self, product_id):
        """Remove a product from subscription."""
        line = self.line_ids.filtered(lambda l: l.product_id.id == product_id)
        if not line:
            raise UserError("Product not found in subscription.")

        if len(self.line_ids) <= 1:
            raise UserError(
                "Cannot remove last product. Cancel subscription instead."
            )

        old_data = {
            'product_id': line.product_id.id,
            'product_name': line.product_id.name,
            'quantity': line.quantity,
        }

        line.unlink()

        self.env['subscription.modification.log'].create({
            'subscription_id': self.id,
            'modification_type': 'product_remove',
            'field_name': 'line_ids',
            'old_value': json.dumps(old_data),
        })

    def _update_product_quantity(self, product_id, new_quantity):
        """Update product quantity in subscription."""
        line = self.line_ids.filtered(lambda l: l.product_id.id == product_id)
        if not line:
            raise UserError("Product not found in subscription.")

        old_quantity = line.quantity
        line.write({'quantity': new_quantity})

        self.env['subscription.modification.log'].create({
            'subscription_id': self.id,
            'modification_type': 'quantity_change',
            'field_name': f'line_{line.id}_quantity',
            'old_value': str(old_quantity),
            'new_value': str(new_quantity),
        })
```

#### 3.3.3 Delivery Skip Model

```python
# models/subscription_delivery_skip.py
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import date, timedelta

class SubscriptionDeliverySkip(models.Model):
    _name = 'subscription.delivery.skip'
    _description = 'Subscription Delivery Skip'
    _order = 'skip_date desc'

    subscription_id = fields.Many2one(
        'customer.subscription',
        string='Subscription',
        required=True,
        ondelete='cascade'
    )
    subscription_line_id = fields.Many2one(
        'customer.subscription.line',
        string='Specific Product',
        help='Leave empty to skip entire delivery'
    )
    skip_date = fields.Date(
        string='Skip Date',
        required=True
    )
    skip_reason = fields.Selection([
        ('not_needed', 'Not Needed'),
        ('traveling', 'Traveling'),
        ('excess_stock', 'Excess Stock'),
        ('other', 'Other'),
    ], string='Reason', default='not_needed')
    reason_note = fields.Text(string='Notes')

    cancelled_at = fields.Datetime(string='Cancelled At')
    cancelled_by = fields.Many2one('res.users', string='Cancelled By')

    credit_issued = fields.Boolean(default=False)
    credit_amount = fields.Monetary(
        string='Credit Amount',
        currency_field='currency_id'
    )
    currency_id = fields.Many2one(
        related='subscription_id.currency_id'
    )

    state = fields.Selection([
        ('scheduled', 'Scheduled'),
        ('applied', 'Applied'),
        ('cancelled', 'Cancelled'),
    ], string='Status', compute='_compute_state', store=True)

    @api.depends('skip_date', 'cancelled_at')
    def _compute_state(self):
        today = date.today()
        for skip in self:
            if skip.cancelled_at:
                skip.state = 'cancelled'
            elif skip.skip_date < today:
                skip.state = 'applied'
            else:
                skip.state = 'scheduled'

    @api.constrains('skip_date')
    def _check_skip_date(self):
        for skip in self:
            # Must be at least 24 hours in advance
            min_date = date.today() + timedelta(days=1)
            if skip.skip_date < min_date:
                raise ValidationError(
                    "Skip date must be at least 24 hours in advance."
                )
            # Cannot skip more than 30 days ahead
            max_date = date.today() + timedelta(days=30)
            if skip.skip_date > max_date:
                raise ValidationError(
                    "Cannot skip deliveries more than 30 days in advance."
                )

    @api.constrains('subscription_id', 'subscription_line_id', 'skip_date')
    def _check_duplicate(self):
        for skip in self:
            domain = [
                ('subscription_id', '=', skip.subscription_id.id),
                ('skip_date', '=', skip.skip_date),
                ('cancelled_at', '=', False),
                ('id', '!=', skip.id),
            ]
            if skip.subscription_line_id:
                domain.append(
                    ('subscription_line_id', '=', skip.subscription_line_id.id)
                )
            else:
                domain.append(('subscription_line_id', '=', False))

            if self.search_count(domain):
                raise ValidationError(
                    "Skip already exists for this date and product."
                )

    def action_cancel_skip(self):
        """Cancel a scheduled skip."""
        self.ensure_one()
        if self.state != 'scheduled':
            raise ValidationError("Can only cancel scheduled skips.")

        self.write({
            'cancelled_at': fields.Datetime.now(),
            'cancelled_by': self.env.uid,
        })
        return True

    def action_issue_credit(self):
        """Issue credit for the skipped delivery."""
        self.ensure_one()
        if self.credit_issued:
            return False

        # Calculate credit amount
        if self.subscription_line_id:
            amount = (
                self.subscription_line_id.unit_price *
                self.subscription_line_id.quantity
            )
        else:
            amount = self.subscription_id.amount_per_delivery

        # Create credit note
        self.subscription_id.partner_id.write({
            'credit_balance': (
                self.subscription_id.partner_id.credit_balance + amount
            )
        })

        self.write({
            'credit_issued': True,
            'credit_amount': amount,
        })
        return True
```

### 3.4 REST API Specifications

#### 3.4.1 Subscription Portal Controller

```python
# controllers/subscription_portal.py
from odoo import http
from odoo.http import request
import json

class SubscriptionPortalController(http.Controller):

    @http.route('/api/v1/portal/subscriptions', type='json',
                auth='user', methods=['GET'])
    def get_customer_subscriptions(self):
        """Get all subscriptions for logged-in customer."""
        partner = request.env.user.partner_id
        subscriptions = request.env['customer.subscription'].search([
            ('partner_id', '=', partner.id),
        ])
        return {
            'success': True,
            'data': [self._serialize_subscription(s) for s in subscriptions]
        }

    @http.route('/api/v1/portal/subscriptions/<int:sub_id>', type='json',
                auth='user', methods=['GET'])
    def get_subscription_detail(self, sub_id):
        """Get detailed subscription information."""
        subscription = self._get_subscription(sub_id)
        return {
            'success': True,
            'data': self._serialize_subscription_detail(subscription)
        }

    @http.route('/api/v1/portal/subscriptions/<int:sub_id>/pause',
                type='json', auth='user', methods=['POST'])
    def pause_subscription(self, sub_id, **kwargs):
        """Pause a subscription."""
        subscription = self._get_subscription(sub_id)

        start_date = kwargs.get('start_date')
        end_date = kwargs.get('end_date')
        reason = kwargs.get('reason')
        reason_details = kwargs.get('reason_details')

        pause = subscription.action_pause(
            start_date=start_date,
            end_date=end_date,
            reason=reason,
            reason_details=reason_details
        )
        return {
            'success': True,
            'data': {'pause_id': pause.id, 'message': 'Subscription paused'}
        }

    @http.route('/api/v1/portal/subscriptions/<int:sub_id>/resume',
                type='json', auth='user', methods=['POST'])
    def resume_subscription(self, sub_id):
        """Resume a paused subscription."""
        subscription = self._get_subscription(sub_id)

        if not subscription.is_paused:
            return {'success': False, 'error': 'Subscription is not paused'}

        subscription.active_pause_id.action_resume()
        return {'success': True, 'message': 'Subscription resumed'}

    @http.route('/api/v1/portal/subscriptions/<int:sub_id>/modify',
                type='json', auth='user', methods=['POST'])
    def modify_subscription(self, sub_id, **kwargs):
        """Modify subscription products/quantities."""
        subscription = self._get_subscription(sub_id)
        modifications = kwargs.get('modifications', [])

        subscription.action_modify_products(modifications)
        return {
            'success': True,
            'data': self._serialize_subscription_detail(subscription)
        }

    @http.route('/api/v1/portal/subscriptions/<int:sub_id>/skip',
                type='json', auth='user', methods=['POST'])
    def skip_delivery(self, sub_id, **kwargs):
        """Skip a delivery date."""
        subscription = self._get_subscription(sub_id)

        skip = request.env['subscription.delivery.skip'].create({
            'subscription_id': subscription.id,
            'subscription_line_id': kwargs.get('line_id'),
            'skip_date': kwargs.get('skip_date'),
            'skip_reason': kwargs.get('reason', 'not_needed'),
            'reason_note': kwargs.get('note'),
        })
        return {'success': True, 'data': {'skip_id': skip.id}}

    @http.route('/api/v1/portal/subscriptions/<int:sub_id>/cancel',
                type='json', auth='user', methods=['POST'])
    def initiate_cancellation(self, sub_id, **kwargs):
        """Initiate subscription cancellation."""
        subscription = self._get_subscription(sub_id)
        reason = kwargs.get('reason')

        # Create cancellation request
        cancellation = request.env['subscription.cancellation'].create({
            'subscription_id': subscription.id,
            'cancellation_reason': reason,
            'reason_details': kwargs.get('reason_details'),
        })

        # Get applicable retention offer
        offer = self._get_retention_offer(subscription, reason)
        if offer:
            cancellation.write({
                'retention_offer_id': offer.id,
                'status': 'retention_offered',
            })
            return {
                'success': True,
                'data': {
                    'cancellation_id': cancellation.id,
                    'retention_offer': self._serialize_offer(offer)
                }
            }

        return {
            'success': True,
            'data': {'cancellation_id': cancellation.id, 'retention_offer': None}
        }

    def _get_subscription(self, sub_id):
        """Get subscription with ownership check."""
        partner = request.env.user.partner_id
        subscription = request.env['customer.subscription'].search([
            ('id', '=', sub_id),
            ('partner_id', '=', partner.id),
        ], limit=1)
        if not subscription:
            raise ValueError("Subscription not found")
        return subscription

    def _serialize_subscription(self, subscription):
        """Serialize subscription for API response."""
        return {
            'id': subscription.id,
            'name': subscription.name,
            'status': subscription.status,
            'plan_name': subscription.plan_id.name,
            'frequency': subscription.plan_id.subscription_type,
            'total_amount': subscription.recurring_total,
            'next_delivery': str(subscription.next_delivery_date),
            'is_paused': subscription.is_paused,
            'pause_end_date': str(subscription.pause_end_date) if subscription.pause_end_date else None,
        }

    def _serialize_subscription_detail(self, subscription):
        """Serialize detailed subscription for API response."""
        base = self._serialize_subscription(subscription)
        base.update({
            'lines': [{
                'id': line.id,
                'product_id': line.product_id.id,
                'product_name': line.product_id.name,
                'quantity': line.quantity,
                'unit_price': line.unit_price,
                'subtotal': line.subtotal,
            } for line in subscription.line_ids],
            'delivery_address': self._serialize_address(subscription.delivery_address_id),
            'time_slot': subscription.time_slot_id.name if subscription.time_slot_id else None,
            'upcoming_skips': [{
                'id': skip.id,
                'date': str(skip.skip_date),
                'reason': skip.skip_reason,
            } for skip in subscription.skip_ids.filtered(lambda s: s.state == 'scheduled')],
        })
        return base

    def _get_retention_offer(self, subscription, reason):
        """Get best retention offer for cancellation."""
        offers = request.env['retention_offer'].search([
            ('active', '=', True),
            '|',
            ('valid_from', '=', False),
            ('valid_from', '<=', fields.Date.today()),
            '|',
            ('valid_until', '=', False),
            ('valid_until', '>=', fields.Date.today()),
        ], order='priority asc')

        for offer in offers:
            if reason in (offer.applicable_reasons or []):
                sub_age = (fields.Date.today() - subscription.start_date).days
                if sub_age >= offer.min_subscription_age_days:
                    if subscription.recurring_total >= offer.min_subscription_value:
                        return offer
        return None
```

---

## 4. Day-by-Day Implementation Plan

### Day 621: Portal Foundation & Authentication

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Design portal database schema | Schema document with ERD |
| 2-4h | Implement subscription_pause table | Migration file, indexes |
| 4-6h | Implement subscription_delivery_skip table | Migration file, indexes |
| 6-8h | Create subscription_modification_log table | Audit trail schema |

**Code Deliverable - Schema Migration:**
```python
# migrations/123_001_portal_tables.py
def migrate(cr, version):
    cr.execute("""
        CREATE TABLE IF NOT EXISTS subscription_pause (
            id SERIAL PRIMARY KEY,
            subscription_id INTEGER NOT NULL,
            pause_type VARCHAR(20) DEFAULT 'manual',
            start_date DATE NOT NULL,
            end_date DATE,
            reason VARCHAR(100),
            reason_details TEXT,
            auto_resume BOOLEAN DEFAULT TRUE,
            resumed_at TIMESTAMP,
            notification_sent BOOLEAN DEFAULT FALSE,
            create_uid INTEGER,
            create_date TIMESTAMP DEFAULT NOW(),
            write_uid INTEGER,
            write_date TIMESTAMP DEFAULT NOW()
        );

        CREATE INDEX idx_pause_sub ON subscription_pause(subscription_id);
        CREATE INDEX idx_pause_dates ON subscription_pause(start_date, end_date);
    """)
```

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Setup portal authentication middleware | JWT token handler |
| 2-4h | Implement customer session management | Session model, Redis config |
| 4-6h | Create API rate limiting configuration | Nginx config, Redis limits |
| 6-8h | Setup API documentation (OpenAPI) | Swagger spec for portal APIs |

**Code Deliverable - Auth Middleware:**
```python
# middleware/portal_auth.py
from odoo import http
from odoo.http import request
import jwt
from functools import wraps

class PortalAuthMiddleware:
    SECRET_KEY = 'your-secret-key'  # From config

    @staticmethod
    def require_customer_auth(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            token = request.httprequest.headers.get('Authorization')
            if not token or not token.startswith('Bearer '):
                return {'error': 'Unauthorized', 'code': 401}

            try:
                payload = jwt.decode(
                    token[7:],
                    PortalAuthMiddleware.SECRET_KEY,
                    algorithms=['HS256']
                )
                request.customer_id = payload.get('customer_id')
                request.partner_id = payload.get('partner_id')
            except jwt.ExpiredSignatureError:
                return {'error': 'Token expired', 'code': 401}
            except jwt.InvalidTokenError:
                return {'error': 'Invalid token', 'code': 401}

            return func(*args, **kwargs)
        return wrapper
```

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Setup React portal project structure | Project scaffold, routing |
| 2-4h | Design portal UI/UX wireframes | Figma designs |
| 4-6h | Implement authentication components | Login, OTP verification |
| 6-8h | Create portal layout and navigation | Header, sidebar, footer |

**Code Deliverable - Portal Layout:**
```tsx
// src/components/layout/PortalLayout.tsx
import React from 'react';
import { Outlet, NavLink } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';

export const PortalLayout: React.FC = () => {
  const { customer, logout } = useAuth();

  const navItems = [
    { path: '/subscriptions', label: 'My Subscriptions', icon: 'package' },
    { path: '/deliveries', label: 'Deliveries', icon: 'truck' },
    { path: '/payments', label: 'Payments', icon: 'credit-card' },
    { path: '/profile', label: 'Profile', icon: 'user' },
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      <header className="bg-white shadow-sm border-b">
        <div className="max-w-7xl mx-auto px-4 py-4 flex justify-between">
          <h1 className="text-xl font-semibold text-green-700">
            Smart Dairy Portal
          </h1>
          <div className="flex items-center gap-4">
            <span>{customer?.name}</span>
            <button onClick={logout} className="text-gray-600">
              Logout
            </button>
          </div>
        </div>
      </header>

      <div className="flex">
        <nav className="w-64 bg-white min-h-screen border-r p-4">
          {navItems.map(item => (
            <NavLink
              key={item.path}
              to={item.path}
              className={({ isActive }) =>
                `flex items-center gap-3 px-4 py-3 rounded-lg mb-2 ${
                  isActive ? 'bg-green-50 text-green-700' : 'text-gray-600'
                }`
              }
            >
              <Icon name={item.icon} />
              {item.label}
            </NavLink>
          ))}
        </nav>

        <main className="flex-1 p-6">
          <Outlet />
        </main>
      </div>
    </div>
  );
};
```

#### Day 621 Deliverables Checklist
- [ ] Portal database schema created
- [ ] Authentication middleware implemented
- [ ] React portal project initialized
- [ ] Portal layout component completed
- [ ] API documentation started

---

### Day 622: Subscription Dashboard & Listing

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement SubscriptionPause Odoo model | Python model file |
| 2-4h | Implement SubscriptionDeliverySkip model | Python model file |
| 4-6h | Create subscription listing API endpoint | /api/v1/portal/subscriptions |
| 6-8h | Add subscription detail API endpoint | /api/v1/portal/subscriptions/{id} |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create subscription serializers | JSON response formatters |
| 2-4h | Implement subscription caching | Redis cache layer |
| 4-6h | Add unit tests for listing APIs | pytest test cases |
| 6-8h | Setup API monitoring and logging | Structured logging config |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create subscription list component | SubscriptionList.tsx |
| 2-4h | Design subscription card component | SubscriptionCard.tsx |
| 4-6h | Implement subscription status badges | StatusBadge component |
| 6-8h | Add skeleton loaders and error states | Loading/Error components |

**Code Deliverable - Subscription Card:**
```tsx
// src/components/subscriptions/SubscriptionCard.tsx
import React from 'react';
import { Subscription } from '@/types';
import { formatCurrency, formatDate } from '@/utils';

interface SubscriptionCardProps {
  subscription: Subscription;
  onManage: (id: number) => void;
}

export const SubscriptionCard: React.FC<SubscriptionCardProps> = ({
  subscription,
  onManage,
}) => {
  const statusColors = {
    active: 'bg-green-100 text-green-800',
    paused: 'bg-yellow-100 text-yellow-800',
    cancelled: 'bg-red-100 text-red-800',
    pending: 'bg-blue-100 text-blue-800',
  };

  return (
    <div className="bg-white rounded-lg shadow-sm border p-6">
      <div className="flex justify-between items-start mb-4">
        <div>
          <h3 className="text-lg font-semibold">{subscription.plan_name}</h3>
          <p className="text-gray-500 text-sm">
            {subscription.frequency} delivery
          </p>
        </div>
        <span className={`px-3 py-1 rounded-full text-sm ${
          statusColors[subscription.status]
        }`}>
          {subscription.status}
        </span>
      </div>

      <div className="space-y-2 mb-4">
        <div className="flex justify-between text-sm">
          <span className="text-gray-600">Amount</span>
          <span className="font-medium">
            {formatCurrency(subscription.total_amount)}/delivery
          </span>
        </div>
        <div className="flex justify-between text-sm">
          <span className="text-gray-600">Next Delivery</span>
          <span className="font-medium">
            {formatDate(subscription.next_delivery)}
          </span>
        </div>
        {subscription.is_paused && (
          <div className="flex justify-between text-sm">
            <span className="text-gray-600">Paused Until</span>
            <span className="font-medium text-yellow-600">
              {formatDate(subscription.pause_end_date)}
            </span>
          </div>
        )}
      </div>

      <button
        onClick={() => onManage(subscription.id)}
        className="w-full py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
      >
        Manage Subscription
      </button>
    </div>
  );
};
```

#### Day 622 Deliverables Checklist
- [ ] Pause and Skip Odoo models implemented
- [ ] Subscription listing API functional
- [ ] Subscription detail API functional
- [ ] Frontend subscription list page completed
- [ ] API caching layer added

---

### Day 623: Pause/Resume Functionality

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement pause validation logic | Max 3 pauses, date validation |
| 2-4h | Create pause/resume API endpoints | POST /pause, POST /resume |
| 4-6h | Add pause notification triggers | Email/SMS on pause events |
| 6-8h | Implement auto-resume scheduler | Celery task for auto-resume |

**Code Deliverable - Auto-Resume Task:**
```python
# tasks/subscription_tasks.py
from celery import shared_task
from odoo import api, SUPERUSER_ID
from datetime import date

@shared_task
def process_auto_resume():
    """Process subscriptions scheduled for auto-resume today."""
    with api.Environment.manage():
        env = api.Environment(cr, SUPERUSER_ID, {})

        pauses = env['subscription.pause'].search([
            ('auto_resume', '=', True),
            ('end_date', '=', date.today()),
            ('resumed_at', '=', False),
        ])

        for pause in pauses:
            try:
                pause.action_resume()
                # Send notification
                pause._send_resume_notification()
            except Exception as e:
                # Log error, continue with others
                _logger.error(f"Auto-resume failed for pause {pause.id}: {e}")

        return f"Processed {len(pauses)} auto-resumes"
```

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Add pause API validation tests | Integration test suite |
| 2-4h | Implement pause history tracking | Modification log entries |
| 4-6h | Setup notification templates | Email/SMS templates |
| 6-8h | Add pause/resume webhook events | Event publishing |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create pause modal component | PauseModal.tsx |
| 2-4h | Implement date picker for pause | DateRangePicker integration |
| 4-6h | Build resume confirmation dialog | ResumeDialog.tsx |
| 6-8h | Add pause history view | PauseHistory.tsx |

**Code Deliverable - Pause Modal:**
```tsx
// src/components/subscriptions/PauseModal.tsx
import React, { useState } from 'react';
import { Dialog } from '@headlessui/react';
import { DatePicker } from '@/components/common';
import { useSubscriptionMutation } from '@/hooks';

interface PauseModalProps {
  isOpen: boolean;
  onClose: () => void;
  subscriptionId: number;
}

const pauseReasons = [
  { value: 'traveling', label: 'Traveling' },
  { value: 'excess_stock', label: 'Excess Stock' },
  { value: 'budget', label: 'Budget Constraints' },
  { value: 'health', label: 'Health Reasons' },
  { value: 'other', label: 'Other' },
];

export const PauseModal: React.FC<PauseModalProps> = ({
  isOpen,
  onClose,
  subscriptionId,
}) => {
  const [startDate, setStartDate] = useState<Date | null>(null);
  const [endDate, setEndDate] = useState<Date | null>(null);
  const [reason, setReason] = useState('');
  const [details, setDetails] = useState('');

  const { pauseSubscription, isLoading } = useSubscriptionMutation();

  const handleSubmit = async () => {
    if (!startDate || !reason) return;

    await pauseSubscription({
      subscriptionId,
      startDate: startDate.toISOString().split('T')[0],
      endDate: endDate?.toISOString().split('T')[0],
      reason,
      reasonDetails: details,
    });
    onClose();
  };

  return (
    <Dialog open={isOpen} onClose={onClose} className="relative z-50">
      <div className="fixed inset-0 bg-black/30" aria-hidden="true" />

      <div className="fixed inset-0 flex items-center justify-center p-4">
        <Dialog.Panel className="bg-white rounded-xl p-6 w-full max-w-md">
          <Dialog.Title className="text-lg font-semibold mb-4">
            Pause Subscription
          </Dialog.Title>

          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium mb-1">
                Start Date *
              </label>
              <DatePicker
                selected={startDate}
                onChange={setStartDate}
                minDate={new Date()}
                placeholderText="Select start date"
              />
            </div>

            <div>
              <label className="block text-sm font-medium mb-1">
                End Date (Optional)
              </label>
              <DatePicker
                selected={endDate}
                onChange={setEndDate}
                minDate={startDate || new Date()}
                maxDate={startDate ? addDays(startDate, 90) : undefined}
                placeholderText="Auto-resume date"
              />
              <p className="text-xs text-gray-500 mt-1">
                Maximum 90 days. Leave empty for indefinite pause.
              </p>
            </div>

            <div>
              <label className="block text-sm font-medium mb-1">
                Reason *
              </label>
              <select
                value={reason}
                onChange={(e) => setReason(e.target.value)}
                className="w-full border rounded-lg px-3 py-2"
              >
                <option value="">Select a reason</option>
                {pauseReasons.map((r) => (
                  <option key={r.value} value={r.value}>
                    {r.label}
                  </option>
                ))}
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium mb-1">
                Additional Details
              </label>
              <textarea
                value={details}
                onChange={(e) => setDetails(e.target.value)}
                className="w-full border rounded-lg px-3 py-2"
                rows={3}
                placeholder="Any additional information..."
              />
            </div>
          </div>

          <div className="flex gap-3 mt-6">
            <button
              onClick={onClose}
              className="flex-1 py-2 border rounded-lg"
            >
              Cancel
            </button>
            <button
              onClick={handleSubmit}
              disabled={!startDate || !reason || isLoading}
              className="flex-1 py-2 bg-yellow-500 text-white rounded-lg disabled:opacity-50"
            >
              {isLoading ? 'Pausing...' : 'Pause Subscription'}
            </button>
          </div>
        </Dialog.Panel>
      </div>
    </Dialog>
  );
};
```

#### Day 623 Deliverables Checklist
- [ ] Pause/Resume APIs implemented
- [ ] Pause validation logic complete
- [ ] Auto-resume scheduler configured
- [ ] Pause modal UI completed
- [ ] Notification templates created

---

### Day 624: Subscription Modification Features

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement product add/remove logic | Subscription modification methods |
| 2-4h | Create quantity update functionality | Quantity change with validation |
| 4-6h | Build frequency change handler | Frequency modification logic |
| 6-8h | Add modification API endpoints | PUT /subscriptions/{id}/modify |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement proration calculator | Billing adjustment service |
| 2-4h | Add modification audit logging | Modification log entries |
| 4-6h | Create modification validation rules | Business rule engine |
| 6-8h | Write integration tests | Modification API tests |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Build product selector component | ProductSelector.tsx |
| 2-4h | Create quantity adjuster UI | QuantityControl.tsx |
| 4-6h | Implement modification preview | ModificationPreview.tsx |
| 6-8h | Add confirmation flow | ModifyConfirmation.tsx |

**Code Deliverable - Product Selector:**
```tsx
// src/components/subscriptions/ProductSelector.tsx
import React, { useState } from 'react';
import { Product, SubscriptionLine } from '@/types';
import { useProducts } from '@/hooks';

interface ProductSelectorProps {
  currentLines: SubscriptionLine[];
  onAdd: (productId: number, quantity: number) => void;
  onRemove: (lineId: number) => void;
  onQuantityChange: (lineId: number, quantity: number) => void;
}

export const ProductSelector: React.FC<ProductSelectorProps> = ({
  currentLines,
  onAdd,
  onRemove,
  onQuantityChange,
}) => {
  const { products, isLoading } = useProducts({ category: 'subscription' });
  const [showAddProduct, setShowAddProduct] = useState(false);

  const availableProducts = products?.filter(
    p => !currentLines.some(l => l.product_id === p.id)
  );

  return (
    <div className="space-y-4">
      <h3 className="font-semibold">Subscription Products</h3>

      {/* Current Products */}
      <div className="space-y-3">
        {currentLines.map(line => (
          <div key={line.id} className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div className="flex items-center gap-3">
              <img src={line.product_image} className="w-12 h-12 rounded" />
              <div>
                <p className="font-medium">{line.product_name}</p>
                <p className="text-sm text-gray-500">
                  ৳{line.unit_price} per unit
                </p>
              </div>
            </div>

            <div className="flex items-center gap-4">
              <div className="flex items-center border rounded">
                <button
                  onClick={() => onQuantityChange(line.id, line.quantity - 1)}
                  disabled={line.quantity <= 1}
                  className="px-3 py-1 hover:bg-gray-100 disabled:opacity-50"
                >
                  -
                </button>
                <span className="px-4 py-1 border-x">{line.quantity}</span>
                <button
                  onClick={() => onQuantityChange(line.id, line.quantity + 1)}
                  className="px-3 py-1 hover:bg-gray-100"
                >
                  +
                </button>
              </div>

              {currentLines.length > 1 && (
                <button
                  onClick={() => onRemove(line.id)}
                  className="text-red-500 hover:text-red-700"
                >
                  Remove
                </button>
              )}
            </div>
          </div>
        ))}
      </div>

      {/* Add Product Section */}
      {!showAddProduct ? (
        <button
          onClick={() => setShowAddProduct(true)}
          className="w-full py-3 border-2 border-dashed rounded-lg text-green-600"
        >
          + Add Product
        </button>
      ) : (
        <div className="border rounded-lg p-4">
          <h4 className="font-medium mb-3">Select Product to Add</h4>
          <div className="grid grid-cols-2 gap-3">
            {availableProducts?.map(product => (
              <button
                key={product.id}
                onClick={() => {
                  onAdd(product.id, 1);
                  setShowAddProduct(false);
                }}
                className="flex items-center gap-3 p-3 border rounded-lg hover:border-green-500"
              >
                <img src={product.image} className="w-10 h-10 rounded" />
                <div className="text-left">
                  <p className="font-medium text-sm">{product.name}</p>
                  <p className="text-xs text-gray-500">৳{product.price}/unit</p>
                </div>
              </button>
            ))}
          </div>
          <button
            onClick={() => setShowAddProduct(false)}
            className="mt-3 text-sm text-gray-500"
          >
            Cancel
          </button>
        </div>
      )}
    </div>
  );
};
```

#### Day 624 Deliverables Checklist
- [ ] Product add/remove APIs functional
- [ ] Quantity modification working
- [ ] Proration calculator implemented
- [ ] Frontend product selector completed
- [ ] Modification preview showing billing impact

---

### Day 625: Delivery Skip Management

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create delivery skip API endpoints | POST/DELETE /skip |
| 2-4h | Implement skip calendar logic | Available dates calculation |
| 4-6h | Build credit issuance for skips | Credit balance updates |
| 6-8h | Add skip notification system | Skip confirmation emails |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Implement skip validation rules | Business rule validation |
| 2-4h | Create skip impact calculator | Billing impact service |
| 4-6h | Build upcoming deliveries query | Delivery schedule API |
| 6-8h | Add skip API tests | pytest test cases |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Design skip calendar component | DeliveryCalendar.tsx |
| 2-4h | Build skip selection interface | SkipSelector.tsx |
| 4-6h | Create skip confirmation modal | SkipConfirmation.tsx |
| 6-8h | Add upcoming deliveries view | UpcomingDeliveries.tsx |

**Code Deliverable - Delivery Calendar:**
```tsx
// src/components/subscriptions/DeliveryCalendar.tsx
import React, { useState } from 'react';
import { Calendar } from 'react-calendar';
import { useSubscriptionDeliveries, useSkipDelivery } from '@/hooks';

interface DeliveryCalendarProps {
  subscriptionId: number;
}

export const DeliveryCalendar: React.FC<DeliveryCalendarProps> = ({
  subscriptionId,
}) => {
  const { deliveries, skippedDates, isLoading } = useSubscriptionDeliveries(subscriptionId);
  const { skipDelivery, cancelSkip, isSkipping } = useSkipDelivery();
  const [selectedDate, setSelectedDate] = useState<Date | null>(null);

  const isDeliveryDate = (date: Date) => {
    return deliveries?.some(d =>
      new Date(d.date).toDateString() === date.toDateString()
    );
  };

  const isSkipped = (date: Date) => {
    return skippedDates?.some(d =>
      new Date(d).toDateString() === date.toDateString()
    );
  };

  const canSkip = (date: Date) => {
    const minDate = new Date();
    minDate.setDate(minDate.getDate() + 1); // 24h advance
    return date >= minDate && isDeliveryDate(date) && !isSkipped(date);
  };

  const handleDateClick = (date: Date) => {
    if (isDeliveryDate(date)) {
      setSelectedDate(date);
    }
  };

  const handleSkip = async (reason: string) => {
    if (!selectedDate) return;
    await skipDelivery({
      subscriptionId,
      skipDate: selectedDate.toISOString().split('T')[0],
      reason,
    });
    setSelectedDate(null);
  };

  const tileClassName = ({ date }: { date: Date }) => {
    if (isSkipped(date)) return 'bg-red-100 text-red-800';
    if (isDeliveryDate(date)) return 'bg-green-100 text-green-800';
    return '';
  };

  return (
    <div className="space-y-4">
      <h3 className="font-semibold">Delivery Calendar</h3>

      <div className="flex gap-4 text-sm">
        <span className="flex items-center gap-2">
          <span className="w-3 h-3 rounded bg-green-500" /> Scheduled
        </span>
        <span className="flex items-center gap-2">
          <span className="w-3 h-3 rounded bg-red-500" /> Skipped
        </span>
      </div>

      <Calendar
        onChange={handleDateClick}
        value={selectedDate}
        tileClassName={tileClassName}
        minDate={new Date()}
        maxDate={new Date(Date.now() + 30 * 24 * 60 * 60 * 1000)}
      />

      {selectedDate && (
        <SkipConfirmationModal
          date={selectedDate}
          canSkip={canSkip(selectedDate)}
          isSkipped={isSkipped(selectedDate)}
          onSkip={handleSkip}
          onCancelSkip={() => cancelSkip({ subscriptionId, date: selectedDate })}
          onClose={() => setSelectedDate(null)}
        />
      )}
    </div>
  );
};
```

#### Day 625 Deliverables Checklist
- [ ] Skip delivery APIs implemented
- [ ] Skip calendar showing delivery dates
- [ ] Credit issuance for skips working
- [ ] Skip notifications configured
- [ ] Frontend calendar component completed

---

### Day 626: Cancellation Workflow

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Design cancellation state machine | State transitions diagram |
| 2-4h | Implement cancellation request API | POST /cancel |
| 4-6h | Create retention offer selection | Offer matching algorithm |
| 6-8h | Build cancellation confirmation | Confirmation API endpoint |

**Code Deliverable - Cancellation Service:**
```python
# services/cancellation_service.py
from odoo import api, models
from datetime import date, timedelta

class CancellationService(models.AbstractModel):
    _name = 'subscription.cancellation.service'
    _description = 'Subscription Cancellation Service'

    def initiate_cancellation(self, subscription_id, reason, reason_details=None):
        """Initiate subscription cancellation process."""
        subscription = self.env['customer.subscription'].browse(subscription_id)

        # Create cancellation request
        cancellation = self.env['subscription.cancellation'].create({
            'subscription_id': subscription_id,
            'cancellation_reason': reason,
            'reason_details': reason_details,
            'status': 'pending',
        })

        # Get retention offer
        offer = self._get_best_retention_offer(subscription, reason)
        if offer:
            cancellation.write({
                'retention_offer_id': offer.id,
                'status': 'retention_offered',
            })

        return cancellation

    def _get_best_retention_offer(self, subscription, reason):
        """Get the best retention offer for this cancellation."""
        offers = self.env['retention_offer'].search([
            ('active', '=', True),
            '|', ('valid_from', '=', False), ('valid_from', '<=', date.today()),
            '|', ('valid_until', '=', False), ('valid_until', '>=', date.today()),
        ], order='priority asc')

        subscription_age = (date.today() - subscription.start_date).days

        for offer in offers:
            # Check if reason matches
            if offer.applicable_reasons and reason not in offer.applicable_reasons:
                continue
            # Check subscription age
            if subscription_age < offer.min_subscription_age_days:
                continue
            # Check subscription value
            if subscription.recurring_total < offer.min_subscription_value:
                continue
            # Check usage limit
            if offer.usage_limit and offer.used_count >= offer.usage_limit:
                continue
            return offer
        return None

    def accept_retention_offer(self, cancellation_id):
        """Accept retention offer and withdraw cancellation."""
        cancellation = self.env['subscription.cancellation'].browse(cancellation_id)
        offer = cancellation.retention_offer_id

        if not offer:
            raise ValueError("No retention offer to accept")

        # Apply offer
        self._apply_retention_offer(cancellation.subscription_id, offer)

        # Update cancellation
        cancellation.write({
            'status': 'withdrawn',
            'retention_offer_accepted': True,
        })

        # Increment offer usage
        offer.write({'used_count': offer.used_count + 1})

        return True

    def _apply_retention_offer(self, subscription, offer):
        """Apply retention offer to subscription."""
        if offer.offer_type == 'discount_percent':
            subscription.write({
                'discount_percent': offer.offer_value,
                'discount_end_date': date.today() + timedelta(days=offer.offer_duration_days),
            })
        elif offer.offer_type == 'discount_fixed':
            subscription.write({
                'discount_fixed': offer.offer_value,
                'discount_end_date': date.today() + timedelta(days=offer.offer_duration_days),
            })
        elif offer.offer_type == 'free_delivery':
            subscription.write({
                'free_delivery_count': int(offer.offer_value),
            })
        elif offer.offer_type == 'credit':
            subscription.partner_id.write({
                'credit_balance': subscription.partner_id.credit_balance + offer.offer_value,
            })

    def confirm_cancellation(self, cancellation_id, feedback_score=None, feedback_comment=None):
        """Confirm and process cancellation."""
        cancellation = self.env['subscription.cancellation'].browse(cancellation_id)
        subscription = cancellation.subscription_id

        # Calculate effective date (end of current billing period)
        effective_date = subscription.current_period_end

        cancellation.write({
            'status': 'confirmed',
            'effective_date': effective_date,
            'feedback_score': feedback_score,
            'feedback_comment': feedback_comment,
        })

        # Schedule subscription deactivation
        subscription.write({
            'scheduled_cancellation_date': effective_date,
            'cancellation_reason': cancellation.cancellation_reason,
        })

        # Send cancellation confirmation email
        self._send_cancellation_confirmation(cancellation)

        return cancellation

    def _send_cancellation_confirmation(self, cancellation):
        """Send cancellation confirmation email."""
        template = self.env.ref('smart_dairy_subscription.email_cancellation_confirmed')
        template.send_mail(cancellation.id, force_send=True)
```

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create retention offer templates | Offer data seed |
| 2-4h | Implement offer application logic | Discount/credit application |
| 4-6h | Build feedback collection system | NPS survey integration |
| 6-8h | Add cancellation analytics events | Event tracking |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Design cancellation wizard | Multi-step form |
| 2-4h | Build reason selection step | CancelReasonStep.tsx |
| 4-6h | Create retention offer display | RetentionOffer.tsx |
| 6-8h | Add feedback collection form | CancelFeedback.tsx |

#### Day 626 Deliverables Checklist
- [ ] Cancellation initiation API working
- [ ] Retention offer matching implemented
- [ ] Cancellation wizard UI completed
- [ ] Feedback collection integrated
- [ ] Offer acceptance flow working

---

### Day 627: Address & Delivery Preferences

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create address management APIs | CRUD endpoints for addresses |
| 2-4h | Implement geocoding integration | Google Maps API integration |
| 4-6h | Build delivery zone validation | Zone boundary check |
| 6-8h | Add time slot preference APIs | Time slot selection endpoints |

**Code Deliverable - Address Management:**
```python
# controllers/address_controller.py
from odoo import http
from odoo.http import request
import requests

class AddressController(http.Controller):

    GOOGLE_GEOCODE_URL = 'https://maps.googleapis.com/maps/api/geocode/json'

    @http.route('/api/v1/portal/addresses', type='json', auth='user', methods=['GET'])
    def get_addresses(self):
        """Get all addresses for the customer."""
        partner = request.env.user.partner_id
        addresses = request.env['res.partner'].search([
            ('parent_id', '=', partner.id),
            ('type', '=', 'delivery'),
        ])
        return {
            'success': True,
            'data': [self._serialize_address(a) for a in addresses]
        }

    @http.route('/api/v1/portal/addresses', type='json', auth='user', methods=['POST'])
    def create_address(self, **kwargs):
        """Create a new delivery address."""
        partner = request.env.user.partner_id

        # Geocode the address
        geocode_result = self._geocode_address(kwargs)
        if not geocode_result['success']:
            return geocode_result

        # Check delivery zone
        zone = self._find_delivery_zone(
            geocode_result['latitude'],
            geocode_result['longitude']
        )
        if not zone:
            return {
                'success': False,
                'error': 'Address is outside delivery zones'
            }

        # Create address
        address = request.env['res.partner'].create({
            'parent_id': partner.id,
            'type': 'delivery',
            'name': kwargs.get('label', 'Delivery Address'),
            'street': kwargs.get('street'),
            'street2': kwargs.get('street2'),
            'city': kwargs.get('city'),
            'zip': kwargs.get('postal_code'),
            'partner_latitude': geocode_result['latitude'],
            'partner_longitude': geocode_result['longitude'],
            'delivery_zone_id': zone.id,
            'delivery_instructions': kwargs.get('instructions'),
        })

        return {
            'success': True,
            'data': self._serialize_address(address)
        }

    @http.route('/api/v1/portal/addresses/<int:address_id>', type='json',
                auth='user', methods=['PUT'])
    def update_address(self, address_id, **kwargs):
        """Update an existing address."""
        address = self._get_address(address_id)

        update_vals = {}
        if 'street' in kwargs:
            # Re-geocode if address changed
            geocode_result = self._geocode_address(kwargs)
            if not geocode_result['success']:
                return geocode_result

            zone = self._find_delivery_zone(
                geocode_result['latitude'],
                geocode_result['longitude']
            )
            if not zone:
                return {'success': False, 'error': 'Address is outside delivery zones'}

            update_vals.update({
                'street': kwargs.get('street'),
                'street2': kwargs.get('street2'),
                'city': kwargs.get('city'),
                'zip': kwargs.get('postal_code'),
                'partner_latitude': geocode_result['latitude'],
                'partner_longitude': geocode_result['longitude'],
                'delivery_zone_id': zone.id,
            })

        if 'instructions' in kwargs:
            update_vals['delivery_instructions'] = kwargs['instructions']

        if 'label' in kwargs:
            update_vals['name'] = kwargs['label']

        address.write(update_vals)
        return {'success': True, 'data': self._serialize_address(address)}

    @http.route('/api/v1/portal/addresses/<int:address_id>/set-default',
                type='json', auth='user', methods=['POST'])
    def set_default_address(self, address_id):
        """Set address as default delivery address."""
        address = self._get_address(address_id)
        partner = request.env.user.partner_id

        # Remove default from other addresses
        request.env['res.partner'].search([
            ('parent_id', '=', partner.id),
            ('type', '=', 'delivery'),
        ]).write({'is_default_delivery': False})

        address.write({'is_default_delivery': True})
        return {'success': True}

    def _geocode_address(self, address_data):
        """Geocode address using Google Maps API."""
        api_key = request.env['ir.config_parameter'].sudo().get_param(
            'google_maps_api_key'
        )

        address_str = ', '.join(filter(None, [
            address_data.get('street'),
            address_data.get('street2'),
            address_data.get('city'),
            address_data.get('postal_code'),
            'Bangladesh'
        ]))

        response = requests.get(self.GOOGLE_GEOCODE_URL, params={
            'address': address_str,
            'key': api_key,
        })

        result = response.json()
        if result['status'] != 'OK' or not result['results']:
            return {'success': False, 'error': 'Could not geocode address'}

        location = result['results'][0]['geometry']['location']
        return {
            'success': True,
            'latitude': location['lat'],
            'longitude': location['lng'],
            'formatted_address': result['results'][0]['formatted_address'],
        }

    def _find_delivery_zone(self, lat, lng):
        """Find delivery zone for coordinates."""
        zones = request.env['delivery.zone'].search([('active', '=', True)])
        for zone in zones:
            if zone.contains_point(lat, lng):
                return zone
        return None

    def _get_address(self, address_id):
        """Get address with ownership check."""
        partner = request.env.user.partner_id
        address = request.env['res.partner'].search([
            ('id', '=', address_id),
            ('parent_id', '=', partner.id),
            ('type', '=', 'delivery'),
        ], limit=1)
        if not address:
            raise ValueError("Address not found")
        return address

    def _serialize_address(self, address):
        """Serialize address for API response."""
        return {
            'id': address.id,
            'label': address.name,
            'street': address.street,
            'street2': address.street2,
            'city': address.city,
            'postal_code': address.zip,
            'latitude': address.partner_latitude,
            'longitude': address.partner_longitude,
            'zone_name': address.delivery_zone_id.name if address.delivery_zone_id else None,
            'instructions': address.delivery_instructions,
            'is_default': address.is_default_delivery,
        }
```

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Setup Google Maps API integration | API key config, client setup |
| 2-4h | Implement zone polygon queries | PostGIS spatial queries |
| 4-6h | Build address validation service | Format validation |
| 6-8h | Add address change impact analysis | Delivery schedule impact |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create address list component | AddressList.tsx |
| 2-4h | Build address form with map | AddressForm.tsx with map picker |
| 4-6h | Implement time slot selector | TimeSlotPicker.tsx |
| 6-8h | Add delivery instruction editor | InstructionsEditor.tsx |

#### Day 627 Deliverables Checklist
- [ ] Address CRUD APIs implemented
- [ ] Geocoding integration working
- [ ] Delivery zone validation active
- [ ] Address management UI completed
- [ ] Time slot selection functional

---

### Day 628: Notification Preferences & Order History

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create notification preference model | Preference storage schema |
| 2-4h | Build preference update APIs | PATCH /preferences |
| 4-6h | Implement order history query | Paginated history endpoint |
| 6-8h | Add reorder functionality | Reorder from history |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Setup notification channels | Email, SMS, Push config |
| 2-4h | Implement preference-based routing | Notification router |
| 4-6h | Build order detail serializer | Order history response |
| 6-8h | Add preference sync with mobile | Firebase sync |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create notification settings UI | NotificationSettings.tsx |
| 2-4h | Build order history list | OrderHistory.tsx |
| 4-6h | Design order detail view | OrderDetail.tsx |
| 6-8h | Add reorder button and flow | ReorderButton.tsx |

**Code Deliverable - Notification Settings:**
```tsx
// src/components/settings/NotificationSettings.tsx
import React from 'react';
import { useNotificationPreferences, useUpdatePreferences } from '@/hooks';
import { Switch } from '@/components/common';

export const NotificationSettings: React.FC = () => {
  const { preferences, isLoading } = useNotificationPreferences();
  const { updatePreference, isUpdating } = useUpdatePreferences();

  const channels = [
    { key: 'email', label: 'Email', description: 'Receive updates via email' },
    { key: 'sms', label: 'SMS', description: 'Receive text messages' },
    { key: 'push', label: 'Push Notifications', description: 'Mobile app notifications' },
  ];

  const notificationTypes = [
    { key: 'delivery_reminder', label: 'Delivery Reminders', description: 'Day before delivery' },
    { key: 'delivery_status', label: 'Delivery Updates', description: 'Real-time delivery tracking' },
    { key: 'billing', label: 'Billing Notifications', description: 'Payment and invoice alerts' },
    { key: 'promotions', label: 'Offers & Promotions', description: 'Special deals and discounts' },
    { key: 'product_updates', label: 'Product Updates', description: 'New products and changes' },
  ];

  if (isLoading) return <div>Loading...</div>;

  return (
    <div className="space-y-8">
      <div>
        <h3 className="text-lg font-semibold mb-4">Notification Channels</h3>
        <div className="space-y-4">
          {channels.map(channel => (
            <div key={channel.key} className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <div>
                <p className="font-medium">{channel.label}</p>
                <p className="text-sm text-gray-500">{channel.description}</p>
              </div>
              <Switch
                checked={preferences?.channels?.[channel.key] ?? true}
                onChange={(checked) => updatePreference(`channels.${channel.key}`, checked)}
                disabled={isUpdating}
              />
            </div>
          ))}
        </div>
      </div>

      <div>
        <h3 className="text-lg font-semibold mb-4">Notification Types</h3>
        <div className="space-y-4">
          {notificationTypes.map(type => (
            <div key={type.key} className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <div>
                <p className="font-medium">{type.label}</p>
                <p className="text-sm text-gray-500">{type.description}</p>
              </div>
              <Switch
                checked={preferences?.types?.[type.key] ?? true}
                onChange={(checked) => updatePreference(`types.${type.key}`, checked)}
                disabled={isUpdating}
              />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};
```

#### Day 628 Deliverables Checklist
- [ ] Notification preference APIs working
- [ ] Order history pagination implemented
- [ ] Reorder functionality complete
- [ ] Notification settings UI finished
- [ ] Preference sync with mobile app

---

### Day 629: Payment Method Management & Mobile Optimization

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Create payment method management APIs | CRUD for payment methods |
| 2-4h | Implement tokenization service | Card/wallet token storage |
| 4-6h | Build default payment method logic | Default selection |
| 6-8h | Add payment method validation | Validation service |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Integrate bKash/Nagad APIs | Mobile money integration |
| 2-4h | Setup SSLCommerz tokenization | Card tokenization |
| 4-6h | Implement mobile API optimization | Response compression |
| 6-8h | Add PWA service worker | Offline capability |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Build payment method list | PaymentMethods.tsx |
| 2-4h | Create add payment form | AddPaymentMethod.tsx |
| 4-6h | Optimize for mobile screens | Responsive improvements |
| 6-8h | Add PWA manifest and icons | PWA configuration |

#### Day 629 Deliverables Checklist
- [ ] Payment method CRUD APIs complete
- [ ] bKash/Nagad integration working
- [ ] Mobile-optimized portal
- [ ] PWA configuration complete
- [ ] Tokenization service functional

---

### Day 630: Integration Testing & Portal Launch

#### Dev 1 Tasks (Backend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Write end-to-end API tests | E2E test suite |
| 2-4h | Performance test portal APIs | Load test results |
| 4-6h | Fix identified issues | Bug fixes |
| 6-8h | API documentation finalization | OpenAPI spec complete |

#### Dev 2 Tasks (Full-Stack) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Security audit of portal | Security checklist |
| 2-4h | Setup portal monitoring | Grafana dashboards |
| 4-6h | Configure error tracking | Sentry integration |
| 6-8h | Deploy portal to staging | Staging deployment |

#### Dev 3 Tasks (Frontend Lead) - 8 hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0-2h | Cross-browser testing | Browser compatibility |
| 2-4h | Accessibility audit | WCAG compliance |
| 4-6h | Performance optimization | Lighthouse score >90 |
| 6-8h | User acceptance testing support | UAT support |

#### Day 630 Deliverables Checklist
- [ ] E2E test suite passing
- [ ] Security audit completed
- [ ] Portal deployed to staging
- [ ] Documentation complete
- [ ] UAT sign-off obtained

---

## 5. Deliverables Checklist

### 5.1 Technical Deliverables

| Deliverable | Verification Method | Status |
|-------------|---------------------|--------|
| Subscription Dashboard API | API tests passing | [ ] |
| Pause/Resume functionality | Integration tests | [ ] |
| Subscription modification APIs | Unit + E2E tests | [ ] |
| Delivery skip calendar | UI tests | [ ] |
| Cancellation workflow | Workflow tests | [ ] |
| Retention offer system | Business logic tests | [ ] |
| Address management | Geocoding tests | [ ] |
| Notification preferences | Preference tests | [ ] |
| Payment method management | Payment tests | [ ] |
| Portal UI components | Visual regression | [ ] |

### 5.2 Documentation Deliverables

| Document | Owner | Status |
|----------|-------|--------|
| Portal API Documentation | Dev 1 | [ ] |
| User Guide | Dev 3 | [ ] |
| Admin Guide | Dev 2 | [ ] |
| Integration Guide | Dev 2 | [ ] |

---

## 6. Success Criteria

### 6.1 Functional Criteria

| Criterion | Target | Measurement |
|-----------|--------|-------------|
| Self-service adoption | 80% of customers | Portal usage analytics |
| Pause functionality | <30s to pause | API response time |
| Modification success | 99% success rate | Error rate monitoring |
| Skip processing | Real-time effect | Delivery schedule sync |
| Cancellation save rate | 25% via retention | Offer acceptance rate |

### 6.2 Technical Criteria

| Criterion | Target | Measurement |
|-----------|--------|-------------|
| Portal load time | <2 seconds | Lighthouse metrics |
| API response time | <500ms (P95) | APM monitoring |
| Uptime | 99.9% | Uptime monitoring |
| Mobile performance | Score >90 | Lighthouse mobile |
| Error rate | <0.1% | Error tracking |

### 6.3 Business Criteria

| Criterion | Target | Measurement |
|-----------|--------|-------------|
| Support ticket reduction | -60% | Ticket volume |
| Customer satisfaction | NPS 50+ | Survey results |
| Portal engagement | 3+ sessions/month | Analytics |
| Feature adoption | 70% use modifications | Feature usage |

---

## 7. Risk Register

| Risk ID | Description | Impact | Probability | Mitigation |
|---------|-------------|--------|-------------|------------|
| R123-01 | Geocoding API rate limits | Medium | Low | Caching, fallback provider |
| R123-02 | Payment tokenization failures | High | Low | Retry logic, manual fallback |
| R123-03 | Customer confusion with portal | Medium | Medium | UX testing, help tooltips |
| R123-04 | Mobile performance issues | Medium | Medium | Performance optimization |
| R123-05 | Session security vulnerabilities | High | Low | Security audit, JWT best practices |
| R123-06 | Retention offer abuse | Medium | Low | Usage limits, validation |

---

## 8. Dependencies

### 8.1 Internal Dependencies

| Dependency | Source | Impact |
|------------|--------|--------|
| Subscription Engine | Milestone 121 | Portal uses subscription APIs |
| Delivery Scheduling | Milestone 122 | Skip affects delivery routes |
| Customer data model | Phase 5 | Customer profiles |
| Product catalog | Phase 8 | Product information |

### 8.2 External Dependencies

| Dependency | Provider | Impact |
|------------|----------|--------|
| Geocoding API | Google Maps | Address validation |
| Payment gateways | bKash, Nagad, SSLCommerz | Payment methods |
| SMS gateway | Twilio/Local | OTP, notifications |
| Email service | SendGrid/SES | Email notifications |

---

## 9. Quality Assurance

### 9.1 Testing Strategy

| Test Type | Coverage | Tool |
|-----------|----------|------|
| Unit Tests | 80% code coverage | pytest, Jest |
| Integration Tests | All API endpoints | pytest |
| E2E Tests | Critical user flows | Cypress |
| Performance Tests | Load scenarios | Locust |
| Security Tests | OWASP Top 10 | OWASP ZAP |
| Accessibility Tests | WCAG 2.1 AA | axe-core |

### 9.2 Code Quality Standards

- ESLint/Prettier for frontend code
- Black/isort for Python code
- Pre-commit hooks enforced
- Code review required for all PRs
- Documentation for all public APIs

---

## 10. Appendix

### 10.1 API Endpoint Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| /api/v1/portal/subscriptions | GET | List customer subscriptions |
| /api/v1/portal/subscriptions/{id} | GET | Get subscription detail |
| /api/v1/portal/subscriptions/{id}/pause | POST | Pause subscription |
| /api/v1/portal/subscriptions/{id}/resume | POST | Resume subscription |
| /api/v1/portal/subscriptions/{id}/modify | PUT | Modify subscription |
| /api/v1/portal/subscriptions/{id}/skip | POST | Skip delivery |
| /api/v1/portal/subscriptions/{id}/cancel | POST | Initiate cancellation |
| /api/v1/portal/addresses | GET/POST | Manage addresses |
| /api/v1/portal/addresses/{id} | PUT/DELETE | Update/delete address |
| /api/v1/portal/payment-methods | GET/POST | Manage payment methods |
| /api/v1/portal/preferences | GET/PATCH | Notification preferences |
| /api/v1/portal/orders | GET | Order history |

### 10.2 Glossary

| Term | Definition |
|------|------------|
| Pause | Temporary suspension of subscription deliveries |
| Skip | One-time omission of a scheduled delivery |
| Retention Offer | Incentive offered to prevent cancellation |
| Geocoding | Converting address to latitude/longitude |
| Tokenization | Secure storage of payment credentials |

---

**Document Version**: 1.0
**Last Updated**: 2026-02-04
**Status**: Draft
**Next Review**: Prior to Milestone 124 kickoff
```

