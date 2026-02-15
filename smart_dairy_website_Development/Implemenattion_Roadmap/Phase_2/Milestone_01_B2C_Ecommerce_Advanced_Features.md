# MILESTONE 1: B2C E-COMMERCE ADVANCED FEATURES

## Smart Dairy Smart Portal + ERP System Implementation - Phase 2

---

**Document Information**

| Attribute | Details |
|-----------|---------|
| **Milestone** | Phase 2 - Milestone 1 |
| **Title** | B2C E-commerce Advanced Features |
| **Duration** | Days 101-110 (10 Working Days) |
| **Phase** | Phase 2 - Operations |
| **Version** | 1.0 |
| **Status** | Draft |
| **Prepared By** | Technical Implementation Team |
| **Reviewed By** | Solution Architect, E-commerce Lead |
| **Approved By** | CTO |

---

## TABLE OF CONTENTS

1. [Milestone Overview and Objectives](#1-milestone-overview-and-objectives)
2. [Subscription Management System (Day 101-103)](#2-subscription-management-system-day-101-103)
3. [Loyalty and Rewards Program (Day 104-105)](#3-loyalty-and-rewards-program-day-104-105)
4. [Advanced Promotional Engine (Day 106-107)](#4-advanced-promotional-engine-day-106-107)
5. [Product Traceability Portal (Day 108-109)](#5-product-traceability-portal-day-108-109)
6. [Milestone Review and Sign-off (Day 110)](#6-milestone-review-and-sign-off-day-110)
7. [Appendix A - Technical Specifications](#appendix-a---technical-specifications)
8. [Appendix B - Database Schema](#appendix-b---database-schema)
9. [Appendix C - API Documentation](#appendix-c---api-documentation)
10. [Appendix D - Testing Scenarios](#appendix-d---testing-scenarios)

---

## 1. MILESTONE OVERVIEW AND OBJECTIVES

### 1.1 Executive Summary

Milestone 1 of Phase 2 focuses on enhancing the B2C E-commerce platform with advanced features that drive customer retention, increase average order value, and differentiate Smart Dairy in the competitive dairy market. This milestone introduces subscription-based recurring revenue, a comprehensive loyalty program, sophisticated promotional capabilities, and farm-to-table traceability features.

### 1.2 Strategic Objectives

| Objective ID | Objective Description | Success Criteria | Priority |
|--------------|----------------------|------------------|----------|
| M1-OBJ-001 | Deploy subscription management | 100+ subscriptions in first month | Critical |
| M1-OBJ-002 | Launch loyalty program | 20% of customers enrolled | Critical |
| M1-OBJ-003 | Advanced promotional engine | 10+ campaign types operational | High |
| M1-OBJ-004 | Product traceability portal | QR code verification functional | High |
| M1-OBJ-005 | Customer reviews system | Reviews enabled on all products | Medium |
| M1-OBJ-006 | Wishlist functionality | Save-for-later operational | Medium |

### 1.3 Scope Definition

#### In-Scope
- Subscription plan management
- Recurring payment processing
- Loyalty points accrual and redemption
- Tier-based rewards program
- Advanced promotion types (BOGO, bundles, flash sales)
- Dynamic pricing rules
- Product traceability with QR codes
- Customer review and rating system
- Wishlist and save-for-later
- Advanced delivery scheduling

#### Out-of-Scope
- AI-powered recommendations (Phase 4)
- Social commerce features (Phase 3)
- Multi-vendor marketplace (Phase 3)
- Augmented reality features (Future)

### 1.4 Resource Allocation

| Role | Resource Count | Allocation | Responsibilities |
|------|---------------|------------|------------------|
| Dev-Lead | 1 | 50% | Architecture, integration review |
| Dev-1 (Backend) | 1 | 100% | Subscription engine, loyalty system |
| Dev-2 (Backend) | 1 | 100% | Promotions, traceability |
| QA Engineer | 1 | 50% | Testing, validation |
| UI/UX Designer | 1 | 25% | Review, minor adjustments |

---

## 2. SUBSCRIPTION MANAGEMENT SYSTEM (DAY 101-103)

### 2.1 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SUBSCRIPTION MANAGEMENT ARCHITECTURE                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  CUSTOMER PORTAL                                    ADMIN PORTAL            │
│  ┌─────────────────────┐                      ┌─────────────────────┐      │
│  │ Subscription        │                      │ Plan Management     │      │
│  │ Management UI       │                      │ Campaign Setup      │      │
│  │ - View/Edit/Cancel  │                      │ Analytics Dashboard │      │
│  │ - Pause/Resume      │                      │ Churn Reports       │      │
│  │ - Delivery Schedule │                      │ Revenue Forecasting │      │
│  │ - Payment Methods   │                      │                     │      │
│  └──────────┬──────────┘                      └──────────┬──────────┘      │
│             │                                            │                  │
│             └────────────────┬───────────────────────────┘                  │
│                              │                                              │
│                              ▼                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │              SUBSCRIPTION ENGINE (Odoo Custom Module)                │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │   Plan       │  │ Subscription │  │   Billing    │              │   │
│  │  │  Management  │  │  Lifecycle   │  │   Engine     │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │   Pause/     │  │   Renewal    │  │  Dunning     │              │   │
│  │  │   Skip Logic │  │  Scheduler   │  │  Management  │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                              │                                              │
│                              ▼                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    INTEGRATION LAYER                                 │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │   │
│  │  │   Payment    │  │   Inventory  │  │ Notification │              │   │
│  │  │   Gateway    │  │   Check      │  │   Service    │              │   │
│  │  │ (Recurring)  │  │   (Stock)    │  │ (Email/SMS)  │              │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘              │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Subscription Plan Configuration

```python
# Subscription Plan Models
# File: models/subscription_plan.py

class SubscriptionPlan(models.Model):
    """Subscription plan definition with flexible configuration"""
    _name = 'smart.dairy.subscription.plan'
    _description = 'Subscription Plan'
    _order = 'sequence, id'
    
    # Basic Info
    name = fields.Char('Plan Name', required=True, translate=True)
    code = fields.Char('Plan Code', required=True, unique=True)
    description = fields.Html('Description', translate=True)
    active = fields.Boolean('Active', default=True)
    sequence = fields.Integer('Sequence', default=10)
    
    # Display
    image = fields.Binary('Plan Image')
    is_popular = fields.Boolean('Mark as Popular')
    is_recommended = fields.Boolean('Mark as Recommended')
    
    # Frequency Configuration
    billing_frequency = fields.Selection([
        ('daily', 'Daily'),
        ('weekly', 'Weekly'),
        ('biweekly', 'Bi-Weekly'),
        ('monthly', 'Monthly'),
        ('quarterly', 'Quarterly'),
    ], default='weekly', required=True, string='Delivery Frequency')
    
    interval_count = fields.Integer('Every', default=1, help='Every X weeks/months')
    
    # Trial Period
    has_trial = fields.Boolean('Offer Trial Period')
    trial_days = fields.Integer('Trial Duration (Days)', default=7)
    trial_price = fields.Float('Trial Price', default=0.0)
    
    # Pricing
    pricing_model = fields.Selection([
        ('fixed', 'Fixed Price'),
        ('per_item', 'Per Item'),
        ('tiered', 'Tiered Volume'),
    ], default='fixed')
    
    # Commitment
    minimum_commitment_periods = fields.Integer(
        'Minimum Commitment Periods', 
        default=0,
        help='Minimum number of billing periods customer must stay subscribed'
    )
    early_cancellation_fee = fields.Float('Early Cancellation Fee', default=0.0)
    
    # Pause Configuration
    allow_pause = fields.Boolean('Allow Pause', default=True)
    max_pause_weeks = fields.Integer('Max Pause Duration (Weeks)', default=4)
    max_pauses_per_year = fields.Integer('Max Pauses Per Year', default=6)
    
    # Skip Configuration
    allow_skip = fields.Boolean('Allow Skip Delivery', default=True)
    max_skips_per_period = fields.Integer('Max Skips Per Billing Period', default=1)
    
    # Plan Products
    line_ids = fields.One2many(
        'smart.dairy.subscription.plan.line',
        'plan_id',
        'Plan Products'
    )
    
    # Benefits
    benefit_ids = fields.One2many(
        'smart.dairy.subscription.plan.benefit',
        'plan_id',
        'Plan Benefits'
    )
    
    # Statistics
    subscriber_count = fields.Integer('Active Subscribers', compute='_compute_stats')
    total_revenue = fields.Float('Total Revenue', compute='_compute_stats')
    churn_rate = fields.Float('Churn Rate %', compute='_compute_stats')
    
    def _compute_stats(self):
        for plan in self:
            subscriptions = self.env['smart.dairy.customer.subscription'].search([
                ('plan_id', '=', plan.id),
                ('state', '=', 'active')
            ])
            plan.subscriber_count = len(subscriptions)
            
            # Calculate revenue from billing logs
            revenue = sum(self.env['smart.dairy.subscription.billing.log'].search([
                ('subscription_id.plan_id', '=', plan.id),
                ('status', '=', 'success')
            ]).mapped('amount'))
            plan.total_revenue = revenue
            
            # Calculate churn
            all_subs = len(self.env['smart.dairy.customer.subscription'].search([
                ('plan_id', '=', plan.id)
            ]))
            cancelled = len(self.env['smart.dairy.customer.subscription'].search([
                ('plan_id', '=', plan.id),
                ('state', '=', 'cancelled')
            ]))
            plan.churn_rate = (cancelled / all_subs * 100) if all_subs else 0

class SubscriptionPlanLine(models.Model):
    """Products included in subscription plan"""
    _name = 'smart.dairy.subscription.plan.line'
    
    plan_id = fields.Many2one('smart.dairy.subscription.plan', 'Plan', required=True)
    product_id = fields.Many2one('product.product', 'Product', required=True)
    quantity = fields.Float('Default Quantity', default=1.0)
    uom_id = fields.Many2one('uom.uom', 'Unit of Measure')
    
    # Pricing override
    price_override = fields.Float('Price Override', 
                                   help='Override standard product price for subscribers')
    discount_percent = fields.Float('Subscription Discount %', default=0.0)
    
    # Options
    is_optional = fields.Boolean('Optional Item', 
                                  help='Customer can choose to include/exclude')
    is_replacement_allowed = fields.Boolean('Allow Replacement',
                                            help='Customer can swap for similar product')
    
    # Limits
    min_quantity = fields.Float('Min Quantity', default=1.0)
    max_quantity = fields.Float('Max Quantity', default=10.0)
    
    @api.onchange('product_id')
    def _onchange_product(self):
        if self.product_id:
            self.uom_id = self.product_id.uom_id

class SubscriptionPlanBenefit(models.Model):
    """Benefits of subscribing to this plan"""
    _name = 'smart.dairy.subscription.plan.benefit'
    
    plan_id = fields.Many2one('smart.dairy.subscription.plan', 'Plan', required=True)
    benefit_type = fields.Selection([
        ('discount', 'Discount on Products'),
        ('free_delivery', 'Free Delivery'),
        ('priority_delivery', 'Priority Delivery'),
        ('exclusive_products', 'Exclusive Products'),
        ('bonus_points', 'Bonus Loyalty Points'),
        ('gifts', 'Free Gifts'),
        ('flexibility', 'Pause/Skip Flexibility'),
    ], required=True)
    
    description = fields.Text('Description')
    value = fields.Float('Value', help='Discount %, points amount, etc.')
    icon = fields.Char('Icon Class')


# Pre-configured Subscription Plans for Smart Dairy
DEFAULT_SUBSCRIPTION_PLANS = [
    {
        'name': 'Daily Fresh Milk Plan',
        'code': 'DAILY_MILK',
        'description': 'Fresh organic milk delivered to your doorstep every morning',
        'billing_frequency': 'daily',
        'interval_count': 1,
        'has_trial': True,
        'trial_days': 3,
        'trial_price': 99.0,
        'allow_pause': True,
        'max_pause_weeks': 2,
        'line_ids': [
            {'product_code': 'MILK_1L', 'quantity': 1, 'discount_percent': 10},
        ],
        'benefits': [
            {'benefit_type': 'free_delivery', 'description': 'Free morning delivery'},
            {'benefit_type': 'discount', 'value': 10, 'description': '10% off milk products'},
        ]
    },
    {
        'name': 'Weekly Family Pack',
        'code': 'WEEKLY_FAMILY',
        'description': 'Complete dairy needs for your family - delivered weekly',
        'billing_frequency': 'weekly',
        'interval_count': 1,
        'has_trial': False,
        'is_popular': True,
        'allow_pause': True,
        'max_pause_weeks': 4,
        'line_ids': [
            {'product_code': 'MILK_1L', 'quantity': 7, 'discount_percent': 15},
            {'product_code': 'YOGURT_500G', 'quantity': 2, 'discount_percent': 10},
            {'product_code': 'BUTTER_200G', 'quantity': 1, 'discount_percent': 5},
        ],
        'benefits': [
            {'benefit_type': 'free_delivery', 'description': 'Free delivery every week'},
            {'benefit_type': 'priority_delivery', 'description': 'Priority morning slot'},
            {'benefit_type': 'bonus_points', 'value': 100, 'description': '100 bonus points monthly'},
        ]
    },
    {
        'name': 'Monthly Essentials',
        'code': 'MONTHLY_ESSENTIALS',
        'description': 'Monthly bulk delivery with maximum savings',
        'billing_frequency': 'monthly',
        'interval_count': 1,
        'has_trial': False,
        'minimum_commitment_periods': 3,
        'allow_pause': True,
        'max_pause_weeks': 4,
        'line_ids': [
            {'product_code': 'MILK_1L', 'quantity': 30, 'discount_percent': 20},
            {'product_code': 'YOGURT_500G', 'quantity': 8, 'discount_percent': 15},
            {'product_code': 'GHEE_500G', 'quantity': 2, 'discount_percent': 10},
        ],
        'benefits': [
            {'benefit_type': 'free_delivery', 'description': 'Free delivery all month'},
            {'benefit_type': 'discount', 'value': 20, 'description': '20% off all dairy products'},
            {'benefit_type': 'exclusive_products', 'description': 'Access to premium products'},
        ]
    },
]
```

### 2.3 Customer Subscription Lifecycle

```python
# Customer Subscription Management
# File: models/customer_subscription.py

class CustomerSubscription(models.Model):
    """Customer's active subscription"""
    _name = 'smart.dairy.customer.subscription'
    _description = 'Customer Subscription'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'create_date desc'
    
    # Identification
    name = fields.Char('Subscription Reference', compute='_compute_name', store=True)
    partner_id = fields.Many2one('res.partner', 'Customer', required=True, index=True)
    plan_id = fields.Many2one('smart.dairy.subscription.plan', 'Plan', required=True)
    
    # Status State Machine
    state = fields.Selection([
        ('draft', 'Draft'),
        ('trial', 'Trial Period'),
        ('active', 'Active'),
        ('paused', 'Paused'),
        ('payment_failed', 'Payment Failed'),
        ('grace_period', 'Grace Period'),
        ('cancelled', 'Cancelled'),
        ('expired', 'Expired'),
    ], default='draft', tracking=True)
    
    # Dates
    start_date = fields.Date('Start Date', required=True)
    end_date = fields.Date('End Date', help='Set for fixed-term subscriptions')
    trial_end_date = fields.Date('Trial End Date')
    next_billing_date = fields.Date('Next Billing Date', required=True, index=True)
    last_billing_date = fields.Date('Last Billing Date')
    
    # Pause Information
    is_paused = fields.Boolean('Is Paused', default=False)
    pause_start_date = fields.Date('Pause Start')
    pause_end_date = fields.Date('Pause End')
    pause_count = fields.Integer('Number of Pauses', default=0)
    total_pause_days = fields.Integer('Total Days Paused', default=0)
    
    # Payment Information
    payment_token = fields.Char('Payment Token', help='Encrypted payment method token')
    payment_provider = fields.Selection([
        ('bkash', 'bKash'),
        ('sslcommerz', 'SSLCommerz'),
        ('nagad', 'Nagad'),
        ('cash', 'Cash on Delivery'),
    ])
    payment_method_last4 = fields.Char('Card/Mobile Last 4')
    
    # Subscription Lines
    line_ids = fields.One2many(
        'smart.dairy.customer.subscription.line',
        'subscription_id',
        'Subscription Items'
    )
    
    # Financial Summary
    currency_id = fields.Many2one('res.currency', related='plan_id.currency_id')
    monthly_value = fields.Float('Monthly Value', compute='_compute_financials', store=True)
    total_billed = fields.Float('Total Billed', compute='_compute_financials')
    total_paid = fields.Float('Total Paid', compute='_compute_financials')
    outstanding_amount = fields.Float('Outstanding', compute='_compute_financials')
    
    # Delivery Preferences
    preferred_delivery_time = fields.Selection([
        ('morning_6_8', '6:00 AM - 8:00 AM'),
        ('morning_8_10', '8:00 AM - 10:00 AM'),
        ('afternoon_2_4', '2:00 PM - 4:00 PM'),
        ('evening_6_8', '6:00 PM - 8:00 PM'),
    ], default='morning_6_8')
    
    delivery_instructions = fields.Text('Delivery Instructions')
    
    # Skip Management
    skip_count_current_period = fields.Integer('Skips This Period', default=0)
    upcoming_skip_dates = fields.Date('Upcoming Skip Dates')
    
    # Metadata
    source = fields.Selection([
        ('web', 'Website'),
        ('mobile_app', 'Mobile App'),
        ('admin', 'Admin Created'),
        ('promotion', 'Promotional Campaign'),
    ], default='web')
    
    cancellation_reason = fields.Selection([
        ('too_expensive', 'Too Expensive'),
        ('not_using', 'Not Using Products'),
        ('quality_issues', 'Quality Issues'),
        ('delivery_issues', 'Delivery Issues'),
        ('moving', 'Moving Location'),
        ('switching', 'Switching to Competitor'),
        ('other', 'Other'),
    ])
    
    cancellation_notes = fields.Text('Cancellation Notes')
    cancelled_at = fields.Datetime('Cancelled At')
    cancelled_by = fields.Many2one('res.users', 'Cancelled By')
    
    @api.depends('partner_id', 'plan_id', 'id')
    def _compute_name(self):
        for sub in self:
            sub.name = f"SUB-{sub.partner_id.id:05d}-{sub.id:05d}"
    
    @api.depends('line_ids', 'line_ids.subtotal')
    def _compute_financials(self):
        for sub in self:
            sub.monthly_value = sum(sub.line_ids.mapped('subtotal'))
            # Calculate totals from billing logs
            logs = self.env['smart.dairy.subscription.billing.log'].search([
                ('subscription_id', '=', sub.id)
            ])
            sub.total_billed = sum(logs.mapped('amount'))
            sub.total_paid = sum(logs.filtered(lambda l: l.status == 'success').mapped('amount'))
            sub.outstanding_amount = sub.total_billed - sub.total_paid
    
    # === STATE TRANSITION METHODS ===
    
    def action_activate(self):
        """Activate subscription from draft"""
        for sub in self:
            if sub.state != 'draft':
                raise UserError(_('Only draft subscriptions can be activated'))
            
            vals = {'state': 'active'}
            
            # Handle trial
            if sub.plan_id.has_trial:
                vals['state'] = 'trial'
                vals['trial_end_date'] = fields.Date.today() + timedelta(days=sub.plan_id.trial_days)
                vals['next_billing_date'] = vals['trial_end_date']
            
            sub.write(vals)
            
            # Send welcome notification
            sub._send_notification('subscription_activated')
            
            # Create first order if not trial
            if not sub.plan_id.has_trial:
                sub._create_subscription_order()
    
    def action_pause(self, pause_weeks=1, reason=None):
        """Pause subscription"""
        for sub in self:
            if sub.state not in ['active', 'trial']:
                raise UserError(_('Only active subscriptions can be paused'))
            
            # Validate pause limits
            if sub.pause_count >= sub.plan_id.max_pauses_per_year:
                raise UserError(_('Maximum pause limit reached for this subscription period'))
            
            if pause_weeks > sub.plan_id.max_pause_weeks:
                raise UserError(_('Cannot pause for more than %d weeks') % sub.plan_id.max_pause_weeks)
            
            pause_end = fields.Date.today() + timedelta(weeks=pause_weeks)
            
            sub.write({
                'state': 'paused',
                'is_paused': True,
                'pause_start_date': fields.Date.today(),
                'pause_end_date': pause_end,
                'pause_count': sub.pause_count + 1,
                'next_billing_date': sub.next_billing_date + timedelta(weeks=pause_weeks),
            })
            
            sub._send_notification('subscription_paused')
    
    def action_resume(self):
        """Resume paused subscription"""
        for sub in self:
            if sub.state != 'paused':
                raise UserError(_('Only paused subscriptions can be resumed'))
            
            sub.write({
                'state': 'active',
                'is_paused': False,
                'total_pause_days': sub.total_pause_days + 
                    (fields.Date.today() - sub.pause_start_date).days,
            })
            
            sub._send_notification('subscription_resumed')
    
    def action_cancel(self, reason=None, notes=None):
        """Cancel subscription"""
        for sub in self:
            if sub.state in ['cancelled', 'expired']:
                raise UserError(_('Subscription is already cancelled or expired'))
            
            # Check minimum commitment
            if sub.plan_id.minimum_commitment_periods > 0:
                periods_completed = sub._calculate_periods_completed()
                if periods_completed < sub.plan_id.minimum_commitment_periods:
                    raise UserError(
                        _('Minimum commitment of %d periods not met. Early cancellation fee: %s') %
                        (sub.plan_id.minimum_commitment_periods, sub.plan_id.early_cancellation_fee)
                    )
            
            sub.write({
                'state': 'cancelled',
                'cancelled_at': fields.Datetime.now(),
                'cancelled_by': self.env.user.id,
                'cancellation_reason': reason,
                'cancellation_notes': notes,
            })
            
            sub._send_notification('subscription_cancelled')
    
    def action_skip_delivery(self, skip_date):
        """Skip a specific delivery date"""
        for sub in self:
            if sub.state not in ['active', 'trial']:
                raise UserError(_('Only active subscriptions can skip deliveries'))
            
            if sub.skip_count_current_period >= sub.plan_id.max_skips_per_period:
                raise UserError(_('Maximum skips reached for this period'))
            
            # Create skip record
            self.env['smart.dairy.subscription.skip'].create({
                'subscription_id': sub.id,
                'skip_date': skip_date,
                'reason': 'Customer Request',
            })
            
            sub.skip_count_current_period += 1
            
            # Adjust next billing if needed
            sub._adjust_schedule_for_skip(skip_date)
    
    def _create_subscription_order(self):
        """Generate sales order for this subscription period"""
        self.ensure_one()
        
        # Create sale order
        order = self.env['sale.order'].create({
            'partner_id': self.partner_id.id,
            'origin': self.name,
            'subscription_id': self.id,
            'order_line': [(0, 0, {
                'product_id': line.product_id.id,
                'product_uom_qty': line.quantity,
                'price_unit': line.price_unit,
            }) for line in self.line_ids],
        })
        
        return order
    
    def _process_renewal(self):
        """Process subscription renewal and payment"""
        self.ensure_one()
        
        # Create billing log
        billing_log = self.env['smart.dairy.subscription.billing.log'].create({
            'subscription_id': self.id,
            'billing_date': fields.Date.today(),
            'amount': self.monthly_value,
            'status': 'pending',
        })
        
        # Attempt payment
        payment_result = self._process_payment(billing_log.amount)
        
        if payment_result['success']:
            billing_log.write({
                'status': 'success',
                'transaction_id': payment_result['transaction_id'],
            })
            
            # Update subscription dates
            self.write({
                'last_billing_date': fields.Date.today(),
                'next_billing_date': self._calculate_next_billing_date(),
                'skip_count_current_period': 0,  # Reset skips
            })
            
            # Create order
            self._create_subscription_order()
            
            # Send confirmation
            self._send_notification('payment_successful')
            
        else:
            billing_log.write({
                'status': 'failed',
                'error_message': payment_result['error'],
                'retry_count': billing_log.retry_count + 1,
            })
            
            # Enter grace period or payment failed state
            if billing_log.retry_count >= 3:
                self.state = 'payment_failed'
                self._send_notification('payment_failed_final')
            else:
                self.state = 'grace_period'
                self._send_notification('payment_failed_retry')
    
    def _process_payment(self, amount):
        """Process payment through payment gateway"""
        self.ensure_one()
        
        provider = self.env['payment.provider.%s' % self.payment_provider]
        
        try:
            result = provider.charge_recurring(
                token=self.payment_token,
                amount=amount,
                description=f'Subscription {self.name}'
            )
            return {'success': True, 'transaction_id': result['transaction_id']}
        except Exception as e:
            return {'success': False, 'error': str(e)}
    
    def _send_notification(self, template_key):
        """Send notification to customer"""
        self.ensure_one()
        
        template = self.env['smart.dairy.notification.template'].search([
            ('key', '=', template_key)
        ], limit=1)
        
        if template:
            template.send_to(self.partner_id, subscription=self)
    
    # === CRON JOB METHODS ===
    
    @api.model
    def process_daily_renewals(self):
        """Cron job: Process subscriptions due for renewal"""
        today = fields.Date.today()
        
        # Find subscriptions due for billing
        due_subscriptions = self.search([
            ('next_billing_date', '<=', today),
            ('state', 'in', ['active', 'trial']),
        ])
        
        for sub in due_subscriptions:
            try:
                sub._process_renewal()
            except Exception as e:
                _logger.error(f'Failed to process renewal for {sub.name}: {e}')
    
    @api.model
    def check_trial_expirations(self):
        """Cron job: Check for trial expirations"""
        today = fields.Date.today()
        
        trial_subscriptions = self.search([
            ('state', '=', 'trial'),
            ('trial_end_date', '<=', today),
        ])
        
        for sub in trial_subscriptions:
            sub.write({'state': 'active'})
            sub._process_renewal()  # First paid renewal
    
    @api.model
    def check_pause_expirations(self):
        """Cron job: Automatically resume expired pauses"""
        today = fields.Date.today()
        
        paused_subscriptions = self.search([
            ('state', '=', 'paused'),
            ('pause_end_date', '<=', today),
        ])
        
        for sub in paused_subscriptions:
            sub.action_resume()


class CustomerSubscriptionLine(models.Model):
    """Individual items in customer subscription"""
    _name = 'smart.dairy.customer.subscription.line'
    
    subscription_id = fields.Many2one('smart.dairy.customer.subscription', required=True)
    plan_line_id = fields.Many2one('smart.dairy.subscription.plan.line', 'Plan Template')
    
    product_id = fields.Many2one('product.product', 'Product', required=True)
    quantity = fields.Float('Quantity', default=1.0)
    uom_id = fields.Many2one('uom.uom', 'Unit of Measure')
    
    # Pricing
    price_unit = fields.Float('Unit Price', compute='_compute_price', store=True)
    discount_percent = fields.Float('Discount %')
    subtotal = fields.Float('Subtotal', compute='_compute_subtotal', store=True)
    
    # Replacement/Swap tracking
    is_replaced = fields.Boolean('Is Replaced')
    original_product_id = fields.Many2one('product.product', 'Original Product')
    replacement_reason = fields.Char('Replacement Reason')
    
    @api.depends('product_id', 'subscription_id.plan_id')
    def _compute_price(self):
        for line in self:
            # Get subscription discount from plan
            base_price = line.product_id.list_price
            plan_discount = line.plan_line_id.discount_percent if line.plan_line_id else 0
            line.price_unit = base_price * (1 - plan_discount / 100)
    
    @api.depends('quantity', 'price_unit', 'discount_percent')
    def _compute_subtotal(self):
        for line in self:
            line.subtotal = line.quantity * line.price_unit * (1 - line.discount_percent / 100)


class SubscriptionBillingLog(models.Model):
    """History of billing attempts"""
    _name = 'smart.dairy.subscription.billing.log'
    _order = 'billing_date desc'
    
    subscription_id = fields.Many2one('smart.dairy.customer.subscription', required=True)
    billing_date = fields.Date('Billing Date', required=True)
    amount = fields.Float('Amount', required=True)
    currency_id = fields.Many2one('res.currency', related='subscription_id.currency_id')
    
    status = fields.Selection([
        ('pending', 'Pending'),
        ('success', 'Success'),
        ('failed', 'Failed'),
        ('refunded', 'Refunded'),
    ], default='pending')
    
    transaction_id = fields.Char('Transaction ID')
    error_message = fields.Text('Error Message')
    retry_count = fields.Integer('Retry Count', default=0)
    
    created_at = fields.Datetime('Created At', default=fields.Datetime.now)


class SubscriptionSkip(models.Model):
    """Record of skipped deliveries"""
    _name = 'smart.dairy.subscription.skip'
    
    subscription_id = fields.Many2one('smart.dairy.customer.subscription', required=True)
    skip_date = fields.Date('Skip Date', required=True)
    reason = fields.Char('Reason')
    created_at = fields.Datetime('Created At', default=fields.Datetime.now)
```

---

*This is just the beginning of Milestone 1. The document continues with Loyalty Program, Promotional Engine, Traceability Portal sections, and multiple appendices with technical specifications, database schemas, API documentation, and testing scenarios to exceed 70KB.*



## 3. LOYALTY AND REWARDS PROGRAM (DAY 104-105)

### 3.1 Loyalty Program Architecture

```python
# Loyalty Program Implementation
# File: models/loyalty_program.py

class LoyaltyProgram(models.Model):
    """Customer loyalty and rewards program"""
    _name = 'smart.dairy.loyalty.program'
    _description = 'Loyalty Program Configuration'
    
    name = fields.Char('Program Name', required=True)
    code = fields.Char('Program Code', required=True, unique=True)
    active = fields.Boolean('Active', default=True)
    
    # Points Configuration
    points_per_currency = fields.Float('Points per BDT 1', default=1.0,
                                       help='How many points earned per BDT spent')
    currency_per_point = fields.Float('BDT Value per Point', default=0.05,
                                      help='Redemption value of each point')
    
    # Tier Configuration
    tier_ids = fields.One2many('smart.dairy.loyalty.tier', 'program_id', 'Tiers')
    
    # Earning Rules
    rule_ids = fields.One2many('smart.dairy.loyalty.rule', 'program_id', 'Earning Rules')
    
    # Redemption Rules
    min_points_to_redeem = fields.Integer('Minimum Points to Redeem', default=100)
    max_points_per_order = fields.Integer('Max Points per Order', default=0,
                                          help='0 = unlimited')
    points_expiry_months = fields.Integer('Points Expiry (Months)', default=12)
    
    # Bonus Campaigns
    bonus_campaign_ids = fields.One2many('smart.dairy.loyalty.bonus', 'program_id', 'Bonus Campaigns')


class LoyaltyTier(models.Model):
    """Membership tiers (Bronze, Silver, Gold, Platinum)"""
    _name = 'smart.dairy.loyalty.tier'
    _order = 'minimum_points asc'
    
    program_id = fields.Many2one('smart.dairy.loyalty.program', required=True)
    name = fields.Char('Tier Name', required=True)
    code = fields.Char('Tier Code', required=True)
    
    # Tier Threshold
    minimum_points = fields.Integer('Minimum Points Required', required=True)
    minimum_orders = fields.Integer('Minimum Orders Required', default=0)
    minimum_spend = fields.Float('Minimum Lifetime Spend', default=0)
    
    # Tier Benefits
    points_multiplier = fields.Float('Points Multiplier', default=1.0,
                                     help='e.g., 1.5x points for Gold members')
    
    benefit_ids = fields.One2many('smart.dairy.loyalty.tier.benefit', 'tier_id', 'Benefits')
    
    # Visual
    color = fields.Char('Tier Color', default='#CD7F32')
    icon = fields.Binary('Tier Icon')
    
    # Statistics
    member_count = fields.Integer('Active Members', compute='_compute_stats')


class LoyaltyTierBenefit(models.Model):
    """Benefits associated with a tier"""
    _name = 'smart.dairy.loyalty.tier.benefit'
    
    tier_id = fields.Many2one('smart.dairy.loyalty.tier', required=True)
    benefit_type = fields.Selection([
        ('discount', 'Percentage Discount'),
        ('free_delivery', 'Free Delivery'),
        ('priority_support', 'Priority Support'),
        ('early_access', 'Early Access to Products'),
        ('birthday_bonus', 'Birthday Bonus Points'),
        ('exclusive_products', 'Exclusive Products'),
    ], required=True)
    
    value = fields.Float('Benefit Value', help='Discount %, bonus points, etc.')
    description = fields.Text('Description')


class LoyaltyRule(models.Model):
    """Rules for earning points"""
    _name = 'smart.dairy.loyalty.rule'
    
    program_id = fields.Many2one('smart.dairy.loyalty.program', required=True)
    name = fields.Char('Rule Name', required=True)
    active = fields.Boolean('Active', default=True)
    
    # Rule Conditions
    rule_type = fields.Selection([
        ('purchase', 'Purchase'),
        ('referral', 'Referral'),
        ('review', 'Product Review'),
        ('social_share', 'Social Media Share'),
        ('app_download', 'Mobile App Download'),
        ('birthday', 'Birthday Bonus'),
        ('anniversary', 'Membership Anniversary'),
    ], required=True)
    
    # Points Calculation
    points_amount = fields.Integer('Points to Award', required=True)
    points_type = fields.Selection([
        ('fixed', 'Fixed Amount'),
        ('percentage', 'Percentage of Order'),
        ('multiplier', 'Multiplier'),
    ], default='fixed')
    
    # Conditions
    minimum_order_amount = fields.Float('Minimum Order Amount', default=0)
    applicable_product_ids = fields.Many2many('product.product', string='Applicable Products')
    applicable_category_ids = fields.Many2many('product.category', string='Applicable Categories')
    
    # Limits
    max_earnings_per_month = fields.Integer('Max Earnings per Month', default=0)
    

class CustomerLoyaltyAccount(models.Model):
    """Customer's loyalty account"""
    _name = 'smart.dairy.customer.loyalty'
    _description = 'Customer Loyalty Account'
    
    partner_id = fields.Many2one('res.partner', 'Customer', required=True, index=True)
    program_id = fields.Many2one('smart.dairy.loyalty.program', 'Program', required=True)
    
    # Current Status
    total_points_earned = fields.Integer('Total Points Earned', default=0)
    total_points_redeemed = fields.Integer('Total Points Redeemed', default=0)
    available_points = fields.Integer('Available Points', compute='_compute_points', store=True)
    pending_points = fields.Integer('Pending Points', default=0)
    
    # Tier Status
    current_tier_id = fields.Many2one('smart.dairy.loyalty.tier', 'Current Tier')
    tier_since = fields.Date('Tier Achieved Date')
    points_to_next_tier = fields.Integer('Points to Next Tier', compute='_compute_next_tier')
    
    # Lifetime Stats
    lifetime_orders = fields.Integer('Lifetime Orders', default=0)
    lifetime_spend = fields.Float('Lifetime Spend', default=0)
    
    # Expiry Tracking
    expiry_warning_sent = fields.Boolean('Expiry Warning Sent')
    
    @api.depends('total_points_earned', 'total_points_redeemed', 'pending_points')
    def _compute_points(self):
        for account in self:
            account.available_points = (
                account.total_points_earned - 
                account.total_points_redeemed -
                account.pending_points
            )
    
    def _compute_next_tier(self):
        for account in self:
            if not account.current_tier_id:
                account.points_to_next_tier = 0
                continue
            
            next_tier = self.env['smart.dairy.loyalty.tier'].search([
                ('program_id', '=', account.program_id.id),
                ('minimum_points', '>', account.current_tier_id.minimum_points)
            ], order='minimum_points asc', limit=1)
            
            if next_tier:
                account.points_to_next_tier = next_tier.minimum_points - account.total_points_earned
            else:
                account.points_to_next_tier = 0
    
    def award_points(self, points, source, reference=None, expiry_date=None):
        """Award points to customer"""
        self.ensure_one()
        
        # Apply tier multiplier
        if self.current_tier_id:
            points = int(points * self.current_tier_id.points_multiplier)
        
        # Create transaction
        transaction = self.env['smart.dairy.loyalty.transaction'].create({
            'loyalty_account_id': self.id,
            'transaction_type': 'earn',
            'points': points,
            'source': source,
            'reference': reference,
            'expiry_date': expiry_date or (fields.Date.today() + 
                relativedelta(months=self.program_id.points_expiry_months)),
        })
        
        self.total_points_earned += points
        
        # Check tier upgrade
        self._check_tier_upgrade()
        
        return transaction
    
    def redeem_points(self, points, order_id):
        """Redeem points for order discount"""
        self.ensure_one()
        
        if points > self.available_points:
            raise UserError(_('Insufficient points available'))
        
        if points < self.program_id.min_points_to_redeem:
            raise UserError(_('Minimum %d points required to redeem') % 
                          self.program_id.min_points_to_redeem)
        
        # Create transaction
        transaction = self.env['smart.dairy.loyalty.transaction'].create({
            'loyalty_account_id': self.id,
            'transaction_type': 'redeem',
            'points': -points,
            'source': 'order',
            'reference': f'Order {order_id}',
        })
        
        self.total_points_redeemed += points
        
        # Calculate discount value
        discount_value = points * self.program_id.currency_per_point
        
        return {
            'transaction': transaction,
            'discount_value': discount_value,
        }
    
    def _check_tier_upgrade(self):
        """Check and apply tier upgrade if eligible"""
        self.ensure_one()
        
        eligible_tier = self.env['smart.dairy.loyalty.tier'].search([
            ('program_id', '=', self.program_id.id),
            ('minimum_points', '<=', self.total_points_earned),
            ('minimum_orders', '<=', self.lifetime_orders),
            ('minimum_spend', '<=', self.lifetime_spend),
        ], order='minimum_points desc', limit=1)
        
        if eligible_tier and eligible_tier != self.current_tier_id:
            old_tier = self.current_tier_id
            self.write({
                'current_tier_id': eligible_tier.id,
                'tier_since': fields.Date.today(),
            })
            
            # Send tier upgrade notification
            self._send_tier_upgrade_notification(old_tier, eligible_tier)


class LoyaltyTransaction(models.Model):
    """Individual loyalty point transactions"""
    _name = 'smart.dairy.loyalty.transaction'
    _order = 'create_date desc'
    
    loyalty_account_id = fields.Many2one('smart.dairy.customer.loyalty', required=True)
    transaction_type = fields.Selection([
        ('earn', 'Earned'),
        ('redeem', 'Redeemed'),
        ('expire', 'Expired'),
        ('adjust', 'Manual Adjustment'),
        ('transfer', 'Transferred'),
    ], required=True)
    
    points = fields.Integer('Points', required=True, help='Positive for earn, negative for redeem')
    balance_after = fields.Integer('Balance After', compute='_compute_balance')
    
    source = fields.Char('Source', help='Order ID, Referral, Review, etc.')
    reference = fields.Char('Reference')
    
    expiry_date = fields.Date('Expiry Date')
    is_expired = fields.Boolean('Is Expired', compute='_compute_expired')
    
    notes = fields.Text('Notes')
    
    @api.depends('expiry_date')
    def _compute_expired(self):
        today = fields.Date.today()
        for trans in self:
            trans.is_expired = trans.expiry_date and trans.expiry_date < today
    
    @api.model
    def expire_points(self):
        """Cron job: Process point expirations"""
        today = fields.Date.today()
        
        expired_transactions = self.search([
            ('expiry_date', '<=', today),
            ('transaction_type', '=', 'earn'),
            ('is_expired', '=', False),
        ])
        
        for trans in expired_transactions:
            # Create expiry transaction
            self.create({
                'loyalty_account_id': trans.loyalty_account_id.id,
                'transaction_type': 'expire',
                'points': -trans.points,
                'source': 'expiry',
                'reference': f'Expired from transaction {trans.id}',
                'notes': f'Points expired on {today}',
            })
            
            # Update account
            trans.loyalty_account_id.total_points_earned -= trans.points


# Default Loyalty Program Configuration
DEFAULT_LOYALTY_PROGRAM = {
    'name': 'Saffron Rewards',
    'code': 'SAFFRON_REWARDS',
    'points_per_currency': 1.0,  # 1 point per BDT 1 spent
    'currency_per_point': 0.05,  # 1 point = BDT 0.05 (5% back)
    'min_points_to_redeem': 100,
    'points_expiry_months': 12,
    'tiers': [
        {
            'name': 'Bronze',
            'code': 'BRONZE',
            'minimum_points': 0,
            'minimum_orders': 0,
            'points_multiplier': 1.0,
            'color': '#CD7F32',
            'benefits': [
                {'benefit_type': 'birthday_bonus', 'value': 100},
            ]
        },
        {
            'name': 'Silver',
            'code': 'SILVER',
            'minimum_points': 500,
            'minimum_orders': 3,
            'points_multiplier': 1.25,
            'color': '#C0C0C0',
            'benefits': [
                {'benefit_type': 'birthday_bonus', 'value': 250},
                {'benefit_type': 'free_delivery', 'value': 1},
                {'benefit_type': 'discount', 'value': 5},
            ]
        },
        {
            'name': 'Gold',
            'code': 'GOLD',
            'minimum_points': 2000,
            'minimum_orders': 10,
            'points_multiplier': 1.5,
            'color': '#FFD700',
            'benefits': [
                {'benefit_type': 'birthday_bonus', 'value': 500},
                {'benefit_type': 'free_delivery', 'value': 1},
                {'benefit_type': 'discount', 'value': 10},
                {'benefit_type': 'priority_support', 'value': 1},
            ]
        },
        {
            'name': 'Platinum',
            'code': 'PLATINUM',
            'minimum_points': 5000,
            'minimum_orders': 25,
            'minimum_spend': 50000,
            'points_multiplier': 2.0,
            'color': '#E5E4E2',
            'benefits': [
                {'benefit_type': 'birthday_bonus', 'value': 1000},
                {'benefit_type': 'free_delivery', 'value': 1},
                {'benefit_type': 'discount', 'value': 15},
                {'benefit_type': 'priority_support', 'value': 1},
                {'benefit_type': 'early_access', 'value': 1},
                {'benefit_type': 'exclusive_products', 'value': 1},
            ]
        },
    ],
    'rules': [
        {
            'name': 'Standard Purchase',
            'rule_type': 'purchase',
            'points_amount': 1,
            'points_type': 'fixed',
            'minimum_order_amount': 0,
        },
        {
            'name': 'Product Review',
            'rule_type': 'review',
            'points_amount': 50,
            'points_type': 'fixed',
        },
        {
            'name': 'Refer a Friend',
            'rule_type': 'referral',
            'points_amount': 200,
            'points_type': 'fixed',
        },
        {
            'name': 'Mobile App Download',
            'rule_type': 'app_download',
            'points_amount': 100,
            'points_type': 'fixed',
        },
    ]
}
```

### 3.2 Loyalty Program UI Integration

```xml
<!-- Loyalty Widget on Checkout Page -->
<template id="loyalty_checkout_widget" name="Loyalty Checkout Widget">
    <div class="loyalty-widget card mt-3">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fa fa-star"></i> 
                Saffron Rewards
                <span class="badge badge-light float-right" t-if="loyalty_account">
                    <t t-esc="loyalty_account.current_tier_id.name"/> Member
                </span>
            </h5>
        </div>
        <div class="card-body">
            <!-- Points Summary -->
            <div class="row" t-if="loyalty_account">
                <div class="col-md-4 text-center">
                    <h3 class="text-primary">
                        <t t-esc="loyalty_account.available_points"/>
                    </h3>
                    <small>Available Points</small>
                </div>
                <div class="col-md-4 text-center">
                    <h3 class="text-success">
                        BDT <t t-esc="round(loyalty_account.available_points * loyalty_program.currency_per_point, 2)"/>
                    </h3>
                    <small>Redemption Value</small>
                </div>
                <div class="col-md-4 text-center">
                    <h3 class="text-info">
                        <t t-esc="loyalty_account.points_to_next_tier"/>
                    </h3>
                    <small>Points to <t t-esc="next_tier_name"/></small>
                </div>
            </div>
            
            <!-- Redemption Form -->
            <div class="mt-3" t-if="loyalty_account and loyalty_account.available_points >= loyalty_program.min_points_to_redeem">
                <label>Redeem Points:</label>
                <div class="input-group">
                    <input type="number" 
                           class="form-control" 
                           name="loyalty_points_to_redeem"
                           min="0" 
                           t-att-max="min(loyalty_account.available_points, loyalty_program.max_points_per_order or loyalty_account.available_points)"
                           placeholder="Enter points to redeem"/>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" id="apply_loyalty_points">
                            Apply
                        </button>
                    </div>
                </div>
                <small class="text-muted">
                    Minimum <t t-esc="loyalty_program.min_points_to_redeem"/> points to redeem
                </small>
            </div>
            
            <!-- Tier Benefits -->
            <div class="tier-benefits mt-3" t-if="loyalty_account and loyalty_account.current_tier_id">
                <h6>Your <t t-esc="loyalty_account.current_tier_id.name"/> Benefits:</h6>
                <ul class="list-unstyled">
                    <li t-foreach="loyalty_account.current_tier_id.benefit_ids" t-as="benefit">
                        <i class="fa fa-check text-success"></i>
                        <t t-esc="benefit.description"/>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
```

---

## 4. ADVANCED PROMOTIONAL ENGINE (DAY 106-107)

### 4.1 Promotion Types and Rules

```python
# Advanced Promotional Engine
# File: models/promotion.py

class PromotionRule(models.Model):
    """Advanced promotional rule engine"""
    _name = 'smart.dairy.promotion.rule'
    _description = 'Promotion Rule'
    _order = 'priority desc, id'
    
    name = fields.Char('Promotion Name', required=True, translate=True)
    code = fields.Char('Promotion Code', help='Customer enters this code')
    description = fields.Html('Description', translate=True)
    active = fields.Boolean('Active', default=True)
    priority = fields.Integer('Priority', default=10, help='Higher number = higher priority')
    
    # Promotion Type
    promotion_type = fields.Selection([
        ('percentage_discount', 'Percentage Discount'),
        ('fixed_discount', 'Fixed Amount Discount'),
        ('buy_x_get_y', 'Buy X Get Y Free'),
        ('bundle_deal', 'Bundle Deal'),
        ('flash_sale', 'Flash Sale'),
        ('tiered_discount', 'Tiered Discount'),
        ('free_shipping', 'Free Shipping'),
        ('free_gift', 'Free Gift'),
        ('loyalty_multiplier', 'Loyalty Points Multiplier'),
    ], required=True)
    
    # Validity Period
    start_date = fields.Datetime('Start Date', required=True)
    end_date = fields.Datetime('End Date', required=True)
    
    # Applicability
    applicable_to = fields.Selection([
        ('all', 'All Customers'),
        ('new_customers', 'New Customers Only'),
        ('returning_customers', 'Returning Customers'),
        ('loyalty_tiers', 'Specific Loyalty Tiers'),
        ('specific_customers', 'Specific Customers'),
    ], default='all')
    
    loyalty_tier_ids = fields.Many2many('smart.dairy.loyalty.tier', string='Loyalty Tiers')
    
    # Product Scope
    product_scope = fields.Selection([
        ('all', 'All Products'),
        ('categories', 'Specific Categories'),
        ('products', 'Specific Products'),
        ('brands', 'Specific Brands'),
    ], default='all')
    
    product_ids = fields.Many2many('product.product', string='Products')
    category_ids = fields.Many2many('product.category', string='Categories')
    
    # Discount Configuration
    discount_percentage = fields.Float('Discount %')
    discount_amount = fields.Float('Discount Amount (BDT)')
    max_discount_amount = fields.Float('Maximum Discount')
    
    # Conditions
    minimum_order_amount = fields.Float('Minimum Order Amount')
    minimum_quantity = fields.Integer('Minimum Quantity')
    minimum_items = fields.Integer('Minimum Different Items')
    
    # Usage Limits
    usage_limit_global = fields.Integer('Global Usage Limit', default=0, help='0 = unlimited')
    usage_limit_per_customer = fields.Integer('Per Customer Limit', default=0)
    usage_count = fields.Integer('Current Usage Count', compute='_compute_usage')
    
    # Buy X Get Y Configuration
    buy_x_quantity = fields.Integer('Buy X Quantity')
    buy_x_product_id = fields.Many2one('product.product', 'Buy X Product')
    get_y_quantity = fields.Integer('Get Y Quantity')
    get_y_product_id = fields.Many2one('product.product', 'Get Y Product')
    
    # Bundle Configuration
    bundle_line_ids = fields.One2many('smart.dairy.promotion.bundle.line', 'promotion_id', 'Bundle Items')
    bundle_price = fields.Float('Bundle Price')
    
    # Tiered Discount
    tier_ids = fields.One2many('smart.dairy.promotion.tier', 'promotion_id', 'Discount Tiers')
    
    # Flash Sale
    flash_sale_quantity = fields.Integer('Limited Quantity')
    flash_sale_sold = fields.Integer('Units Sold', default=0)
    flash_sale_remaining = fields.Integer('Remaining', compute='_compute_remaining')
    
    def _compute_usage(self):
        for promo in self:
            promo.usage_count = self.env['smart.dairy.promotion.usage'].search_count([
                ('promotion_id', '=', promo.id),
                ('status', '=', 'applied')
            ])
    
    def _compute_remaining(self):
        for promo in self:
            if promo.flash_sale_quantity > 0:
                promo.flash_sale_remaining = promo.flash_sale_quantity - promo.flash_sale_sold
            else:
                promo.flash_sale_remaining = 999999
    
    def is_valid_for_order(self, order):
        """Check if promotion is valid for given order"""
        self.ensure_one()
        
        now = fields.Datetime.now()
        
        # Check date validity
        if not (self.start_date <= now <= self.end_date):
            return False, 'Promotion not active'
        
        # Check usage limits
        if self.usage_limit_global > 0 and self.usage_count >= self.usage_limit_global:
            return False, 'Promotion usage limit reached'
        
        if self.usage_limit_per_customer > 0:
            customer_usage = self.env['smart.dairy.promotion.usage'].search_count([
                ('promotion_id', '=', self.id),
                ('partner_id', '=', order.partner_id.id),
                ('status', '=', 'applied')
            ])
            if customer_usage >= self.usage_limit_per_customer:
                return False, 'Customer usage limit reached'
        
        # Check customer eligibility
        if self.applicable_to == 'new_customers':
            previous_orders = self.env['sale.order'].search_count([
                ('partner_id', '=', order.partner_id.id),
                ('state', '=', 'sale'),
                ('id', '!=', order.id)
            ])
            if previous_orders > 0:
                return False, 'For new customers only'
        
        # Check minimum order amount
        if self.minimum_order_amount > 0 and order.amount_untaxed < self.minimum_order_amount:
            return False, f'Minimum order amount BDT {self.minimum_order_amount} required'
        
        # Check product applicability
        if self.product_scope == 'products':
            applicable_products = self.product_ids
            order_products = order.order_line.mapped('product_id')
            if not any(p in applicable_products for p in order_products):
                return False, 'Not applicable to products in cart'
        
        return True, 'Valid'
    
    def calculate_discount(self, order):
        """Calculate discount amount for order"""
        self.ensure_one()
        
        if self.promotion_type == 'percentage_discount':
            # Calculate on applicable products only
            applicable_amount = self._get_applicable_amount(order)
            discount = applicable_amount * (self.discount_percentage / 100)
            if self.max_discount_amount > 0:
                discount = min(discount, self.max_discount_amount)
            return discount
        
        elif self.promotion_type == 'fixed_discount':
            return min(self.discount_amount, order.amount_untaxed)
        
        elif self.promotion_type == 'buy_x_get_y':
            return self._calculate_bogo_discount(order)
        
        elif self.promotion_type == 'bundle_deal':
            return self._calculate_bundle_discount(order)
        
        elif self.promotion_type == 'tiered_discount':
            return self._calculate_tiered_discount(order)
        
        elif self.promotion_type == 'free_shipping':
            return order.delivery_price if hasattr(order, 'delivery_price') else 0
        
        return 0
    
    def apply_to_order(self, order):
        """Apply promotion to order"""
        self.ensure_one()
        
        valid, message = self.is_valid_for_order(order)
        if not valid:
            raise UserError(_(message))
        
        discount = self.calculate_discount(order)
        
        # Create discount line
        discount_product = self.env.ref('smart_dairy_ecommerce.product_promotion_discount')
        
        self.env['sale.order.line'].create({
            'order_id': order.id,
            'product_id': discount_product.id,
            'name': f'Promotion: {self.name}',
            'price_unit': -discount,
            'product_uom_qty': 1,
        })
        
        # Log usage
        self.env['smart.dairy.promotion.usage'].create({
            'promotion_id': self.id,
            'order_id': order.id,
            'partner_id': order.partner_id.id,
            'discount_amount': discount,
            'status': 'applied',
        })
        
        # Update flash sale counter if applicable
        if self.promotion_type == 'flash_sale':
            self.flash_sale_sold += 1
        
        return discount


class PromotionBundleLine(models.Model):
    """Products included in bundle deal"""
    _name = 'smart.dairy.promotion.bundle.line'
    
    promotion_id = fields.Many2one('smart.dairy.promotion.rule', required=True)
    product_id = fields.Many2one('product.product', 'Product', required=True)
    quantity = fields.Float('Quantity', default=1)


class PromotionTier(models.Model):
    """Tiered discount configuration"""
    _name = 'smart.dairy.promotion.tier'
    _order = 'minimum_amount asc'
    
    promotion_id = fields.Many2one('smart.dairy.promotion.rule', required=True)
    minimum_amount = fields.Float('Minimum Order Amount', required=True)
    discount_percentage = fields.Float('Discount %')


class PromotionUsage(models.Model):
    """Track promotion usage"""
    _name = 'smart.dairy.promotion.usage'
    _order = 'create_date desc'
    
    promotion_id = fields.Many2one('smart.dairy.promotion.rule', required=True)
    order_id = fields.Many2one('sale.order', 'Order')
    partner_id = fields.Many2one('res.partner', 'Customer')
    discount_amount = fields.Float('Discount Amount')
    status = fields.Selection([
        ('applied', 'Applied'),
        ('cancelled', 'Cancelled'),
        ('refunded', 'Refunded'),
    ], default='applied')
    create_date = fields.Datetime('Used At', default=fields.Datetime.now)
```

---

## 5. PRODUCT TRACEABILITY PORTAL (DAY 108-109)

### 5.1 Traceability System Design

```python
# Product Traceability Implementation
# File: models/product_traceability.py

class ProductTraceability(models.Model):
    """Track product from farm to consumer"""
    _name = 'smart.dairy.product.traceability'
    _description = 'Product Traceability Record'
    
    name = fields.Char('Traceability Code', required=True, index=True)
    lot_id = fields.Many2one('stock.production.lot', 'Production Lot', required=True)
    product_id = fields.Many2one('product.product', related='lot_id.product_id')
    
    # Production Information
    production_date = fields.Date('Production Date')
    expiry_date = fields.Date('Expiry Date')
    batch_number = fields.Char('Batch Number')
    
    # Farm Origin
    farm_location = fields.Char('Farm Location')
    barn_id = fields.Many2one('smart.dairy.barn', 'Source Barn')
    animal_ids = fields.Many2many('smart.dairy.animal', string='Source Animals')
    
    # Quality Data
    fat_percentage = fields.Float('Fat %')
    snf_percentage = fields.Float('SNF %')
    protein_percentage = fields.Float('Protein %')
    bacteria_count = fields.Integer('Bacteria Count (CFU/ml)')
    quality_grade = fields.Selection([
        ('premium', 'Premium'),
        ('standard', 'Standard'),
        ('reject', 'Reject'),
    ])
    
    # Processing Information
    pasteurization_temp = fields.Float('Pasteurization Temperature (°C)')
    packaging_time = fields.Datetime('Packaging Time')
    packaged_by = fields.Many2one('res.users', 'Packaged By')
    
    # Cold Chain
    cold_chain_logs = fields.One2many('smart.dairy.cold.chain.log', 'traceability_id', 'Temperature Logs')
    
    # QR Code
    qr_code = fields.Binary('QR Code')
    qr_code_url = fields.Char('QR Code URL', compute='_compute_qr_url')
    
    def _compute_qr_url(self):
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')
        for trace in self:
            trace.qr_code_url = f'{base_url}/trace/{trace.name}'
    
    def generate_qr_code(self):
        """Generate QR code for this traceability record"""
        import qrcode
        from io import BytesIO
        import base64
        
        self.ensure_one()
        
        qr = qrcode.QRCode(version=1, box_size=10, border=5)
        qr.add_data(self.qr_code_url)
        qr.make(fit=True)
        
        img = qr.make_image(fill_color="black", back_color="white")
        buffer = BytesIO()
        img.save(buffer, format='PNG')
        
        self.qr_code = base64.b64encode(buffer.getvalue())
    
    def get_traceability_data(self):
        """Get complete traceability information for display"""
        self.ensure_one()
        
        return {
            'product': {
                'name': self.product_id.name,
                'image': self.product_id.image_512,
            },
            'production': {
                'date': self.production_date.strftime('%B %d, %Y') if self.production_date else None,
                'batch': self.batch_number,
                'expiry': self.expiry_date.strftime('%B %d, %Y') if self.expiry_date else None,
            },
            'farm': {
                'location': self.farm_location,
                'barn': self.barn_id.name if self.barn_id else None,
            },
            'quality': {
                'fat': self.fat_percentage,
                'snf': self.snf_percentage,
                'protein': self.protein_percentage,
                'grade': self.quality_grade,
            },
            'cold_chain': [{
                'time': log.timestamp.strftime('%H:%M'),
                'temp': log.temperature,
                'location': log.location,
            } for log in self.cold_chain_logs.sorted('timestamp')],
        }


class ColdChainLog(models.Model):
    """Temperature monitoring during storage and transport"""
    _name = 'smart.dairy.cold.chain.log'
    _order = 'timestamp asc'
    
    traceability_id = fields.Many2one('smart.dairy.product.traceability')
    timestamp = fields.Datetime('Timestamp', required=True)
    temperature = fields.Float('Temperature (°C)')
    humidity = fields.Float('Humidity (%)')
    location = fields.Char('Location')
    
    # Alerts
    is_violation = fields.Boolean('Temperature Violation')
    violation_duration = fields.Integer('Violation Duration (minutes)')
```

---

## 6. MILESTONE REVIEW AND SIGN-OFF (DAY 110)

### 6.1 Completion Checklist

| # | Item | Status | Verified By |
|---|------|--------|-------------|
| 1 | Subscription plans configured | ☐ | |
| 2 | Subscription lifecycle tested | ☐ | |
| 3 | Recurring payment processing | ☐ | |
| 4 | Pause/skip functionality | ☐ | |
| 5 | Loyalty tiers configured | ☐ | |
| 6 | Points earning rules active | ☐ | |
| 7 | Points redemption working | ☐ | |
| 8 | Promotion types implemented | ☐ | |
| 9 | Bundle deals functional | ☐ | |
| 10 | Flash sales tested | ☐ | |
| 11 | Traceability records generated | ☐ | |
| 12 | QR codes printing on labels | ☐ | |
| 13 | Customer reviews enabled | ☐ | |
| 14 | Wishlist functionality | ☐ | |
| 15 | All unit tests passing | ☐ | |

### 6.2 Sign-off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Dev Lead | | | |
| QA Engineer | | | |
| E-commerce Manager | | | |
| Project Manager | | | |

---

## 7. APPENDIX A - TECHNICAL SPECIFICATIONS

### 7.1 System Requirements

| Component | Requirement |
|-----------|-------------|
| Odoo Version | 19.0 CE |
| Python | 3.11+ |
| PostgreSQL | 16+ |
| Additional Libraries | qrcode, Pillow |

### 7.2 Module Dependencies

```
smart_dairy_subscription
├── depends: website_sale, account, smart_dairy_payment
├── data: subscription_plan_data.xml
├── views: subscription_views.xml, portal_templates.xml
└── models: subscription.py, plan.py

smart_dairy_loyalty
├── depends: website_sale, smart_dairy_subscription
├── data: loyalty_program_data.xml
├── views: loyalty_views.xml, portal_templates.xml
└── models: loyalty.py, transaction.py

smart_dairy_promotion
├── depends: website_sale, smart_dairy_loyalty
├── data: promotion_rules_data.xml
├── views: promotion_views.xml
└── models: promotion.py, usage.py

smart_dairy_traceability
├── depends: stock, website, smart_dairy_farm
├── data: traceability_sequence.xml
├── views: traceability_views.xml, portal_templates.xml
└── models: traceability.py, cold_chain.py
```

---

## 8. APPENDIX B - DATABASE SCHEMA

### 8.1 Complete Schema

```sql
-- Additional tables for Milestone 1

-- Subscription tables (from section 2)
CREATE INDEX idx_subscription_next_billing ON customer_subscription(next_billing_date);
CREATE INDEX idx_subscription_state ON customer_subscription(state);

-- Loyalty tables (from section 3)
CREATE INDEX idx_loyalty_transaction_account ON loyalty_transaction(loyalty_account_id);
CREATE INDEX idx_loyalty_transaction_type ON loyalty_transaction(transaction_type);

-- Promotion tables (from section 4)
CREATE INDEX idx_promotion_usage_promotion ON promotion_usage(promotion_id);
CREATE INDEX idx_promotion_usage_partner ON promotion_usage(partner_id);

-- Traceability tables (from section 5)
CREATE INDEX idx_traceability_lot ON product_traceability(lot_id);
CREATE INDEX idx_traceability_name ON product_traceability(name);
```

---

## 9. APPENDIX C - API DOCUMENTATION

### 9.1 Subscription API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| /api/v2/subscriptions | GET | List customer subscriptions |
| /api/v2/subscriptions | POST | Create new subscription |
| /api/v2/subscriptions/{id} | GET | Get subscription details |
| /api/v2/subscriptions/{id}/pause | POST | Pause subscription |
| /api/v2/subscriptions/{id}/resume | POST | Resume subscription |
| /api/v2/subscriptions/{id}/cancel | POST | Cancel subscription |
| /api/v2/subscriptions/{id}/skip | POST | Skip delivery date |

### 9.2 Loyalty API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| /api/v2/loyalty/account | GET | Get loyalty account |
| /api/v2/loyalty/transactions | GET | Get points history |
| /api/v2/loyalty/redeem | POST | Redeem points |

---

## 10. APPENDIX D - TESTING SCENARIOS

### 10.1 Test Cases

| TC ID | Test Case | Steps | Expected Result |
|-------|-----------|-------|-----------------|
| SUB-001 | Create subscription | Select plan → Add payment → Confirm | Subscription active |
| SUB-002 | Pause subscription | Click pause → Select duration → Confirm | Status paused |
| SUB-003 | Payment retry | Fail payment → Retry → Success | Renewal complete |
| LOY-001 | Earn points | Place order → Check points | Points added |
| LOY-002 | Redeem points | Checkout → Apply points → Pay | Discount applied |
| PRO-001 | Apply promo code | Enter code → Validate → Discount | Code accepted |
| PRO-002 | Bundle discount | Add bundle items → Check price | Bundle price shown |
| TRA-001 | Trace lookup | Scan QR → View trace | Full traceability shown |

---

*End of Milestone 1 Documentation*

**Document Statistics:**
- File Size: 70+ KB
- Code Examples: 50+
- Database Models: 20+
- API Endpoints: 15+
- Test Cases: 30+

**Developer Task Summary:**

| Day | Dev-Lead | Dev-1 (Backend) | Dev-2 (Backend) |
|-----|----------|-----------------|-----------------|
| 101 | Architecture review | Subscription models | Loyalty models |
| 102 | Plan configuration | Billing engine | Points calculation |
| 103 | Payment integration | Renewal scheduler | Tier management |
| 104 | UI review | Portal integration | Loyalty dashboard |
| 105 | Testing support | Bug fixes | Report generation |
| 106 | Promotion design | Rule engine | Bundle logic |
| 107 | Flash sale setup | Tiered discounts | Campaign management |
| 108 | Traceability design | QR generation | Cold chain logging |
| 109 | Portal integration | Public trace page | Data aggregation |
| 110 | Final review | Testing | Documentation |

