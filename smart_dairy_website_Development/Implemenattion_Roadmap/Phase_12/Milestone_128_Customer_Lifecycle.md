# Milestone 128: Customer Lifecycle Automation

## Smart Dairy Digital Portal + ERP System
## Phase 12: Commerce - Subscription & Automation

---

## Document Control

| Attribute | Details |
|-----------|---------|
| **Document ID** | SD-P12-M128-v1.0 |
| **Version** | 1.0 |
| **Author** | Technical Architecture Team |
| **Created Date** | 2026-02-04 |
| **Last Modified** | 2026-02-04 |
| **Status** | Draft |
| **Parent Phase** | Phase 12: Commerce - Subscription & Automation |
| **Milestone Duration** | Days 671-680 (10 working days) |
| **Development Team** | 3 Full-Stack Developers |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 128 implements a comprehensive customer lifecycle automation platform that manages customer relationships from acquisition through retention and win-back. The platform automates welcome sequences, onboarding journeys, engagement campaigns, retention interventions, and loyalty programs.

This milestone ensures customers receive the right communication at the right time throughout their journey with Smart Dairy, maximizing engagement, satisfaction, and lifetime value.

### 1.2 Business Value Proposition

| Value Driver | Business Impact | Quantified Benefit |
|--------------|-----------------|-------------------|
| **Onboarding Automation** | Faster customer activation | 40% faster time-to-value |
| **Engagement Campaigns** | Higher customer activity | 25% increase in orders |
| **Retention Programs** | Reduced churn | 30% churn reduction |
| **Win-back Campaigns** | Recover lost customers | 15% reactivation rate |
| **Loyalty Program** | Increased loyalty | 20% higher LTV |

### 1.3 Strategic Alignment

**RFP Requirements:**

- REQ-CLC-001: Customer onboarding automation
- REQ-CLC-002: Engagement trigger campaigns
- REQ-CLC-003: Churn prevention system
- REQ-CLC-004: Customer feedback collection

**BRD Requirements:**

- BR-CLC-01: 90% customer retention rate
- BR-CLC-02: NPS score >50
- BR-CLC-03: 80% onboarding completion

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

#### Priority 0 (Must Have - Days 671-675)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D128-01 | Customer Lifecycle Models | Lifecycle stages, journey tracking |
| D128-02 | Welcome Email Sequences | Multi-step welcome automation |
| D128-03 | Onboarding Engine | Tutorial tracking, milestone completion |
| D128-04 | Engagement Trigger System | Activity-based triggers |
| D128-05 | At-Risk Detection | Churn risk identification |

#### Priority 1 (Should Have - Days 676-678)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D128-06 | Retention Campaign Engine | Automated retention interventions |
| D128-07 | Win-back Campaigns | Churned customer reactivation |
| D128-08 | NPS Survey System | Automated feedback collection |
| D128-09 | Loyalty Program Engine | Points, tiers, rewards |
| D128-10 | Customer Journey Dashboard | Lifecycle analytics |

#### Priority 2 (Nice to Have - Days 679-680)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D128-11 | Milestone Celebrations | Achievement notifications |
| D128-12 | Anniversary Campaigns | Tenure-based rewards |
| D128-13 | Referral Program | Customer referral tracking |
| D128-14 | Lifecycle Reporting | Journey analytics |

### 2.2 Out-of-Scope

| Item | Reason | Future Phase |
|------|--------|--------------|
| Advanced ML predictions | Requires data science team | Phase 15 |
| Multi-brand lifecycle | Single brand focus | Post-MVP |
| Partner lifecycle | B2B scope different | Phase 14 |

---

## 3. Technical Architecture

### 3.1 Customer Lifecycle Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                 CUSTOMER LIFECYCLE AUTOMATION                        │
├─────────────────────────────────────────────────────────────────────┤
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                   LIFECYCLE STAGES                            │   │
│  │  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────┐ │   │
│  │  │ Acquired│→ │Onboarding│→│ Active  │→ │ At-Risk │→ │Churned │ │
│  │  └─────────┘  └─────────┘  └─────────┘  └─────────┘  └─────┘ │   │
│  │       ↓            ↓            ↓            ↓          ↓     │   │
│  │  [Welcome]   [Tutorials]  [Engagement] [Retention] [Win-back] │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                   AUTOMATION ENGINES                          │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │ Onboarding │  │ Engagement │  │ Retention  │  │ Loyalty │ │   │
│  │  │   Engine   │  │   Engine   │  │   Engine   │  │  Engine │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                   FEEDBACK SYSTEM                             │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │    NPS     │  │   CSAT     │  │  Surveys   │  │ Reviews │ │   │
│  │  │   Engine   │  │   Engine   │  │   Engine   │  │  Engine │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Database Schema

```sql
-- ============================================================
-- CUSTOMER LIFECYCLE - DATABASE SCHEMA
-- Milestone 128: Days 671-680
-- ============================================================

-- ============================================================
-- 1. LIFECYCLE STAGE TRACKING
-- ============================================================

CREATE TABLE customer_lifecycle (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,

    -- Current Stage
    current_stage VARCHAR(50) NOT NULL DEFAULT 'acquired',
    -- Stages: 'acquired', 'onboarding', 'active', 'engaged', 'at_risk', 'dormant', 'churned', 'won_back'

    previous_stage VARCHAR(50),
    stage_changed_at TIMESTAMPTZ DEFAULT NOW(),

    -- Lifecycle Metrics
    days_as_customer INTEGER DEFAULT 0,
    total_orders INTEGER DEFAULT 0,
    total_revenue DECIMAL(15, 2) DEFAULT 0,
    last_order_date DATE,
    days_since_last_order INTEGER,

    -- Engagement Score (0-100)
    engagement_score INTEGER DEFAULT 50,
    engagement_trend VARCHAR(20),  -- 'increasing', 'stable', 'decreasing'

    -- Health Indicators
    health_score INTEGER DEFAULT 50,
    churn_risk_score DECIMAL(5, 4) DEFAULT 0,
    clv_segment VARCHAR(20),

    -- Onboarding
    onboarding_status VARCHAR(20) DEFAULT 'not_started',
    onboarding_started_at TIMESTAMPTZ,
    onboarding_completed_at TIMESTAMPTZ,
    onboarding_completion_rate DECIMAL(5, 2) DEFAULT 0,

    -- Subscriptions
    has_active_subscription BOOLEAN DEFAULT FALSE,
    subscription_count INTEGER DEFAULT 0,
    subscription_mrr DECIMAL(10, 2) DEFAULT 0,

    -- Feedback
    nps_score INTEGER,
    nps_collected_at TIMESTAMPTZ,
    csat_score DECIMAL(3, 2),

    -- Loyalty
    loyalty_tier VARCHAR(20) DEFAULT 'bronze',
    loyalty_points INTEGER DEFAULT 0,
    loyalty_points_lifetime INTEGER DEFAULT 0,

    -- Timestamps
    first_order_date DATE,
    first_subscription_date DATE,
    churned_at TIMESTAMPTZ,
    won_back_at TIMESTAMPTZ,

    company_id INTEGER NOT NULL DEFAULT 1,
    updated_at TIMESTAMPTZ DEFAULT NOW(),

    UNIQUE(customer_id, company_id)
);

CREATE INDEX idx_lifecycle_customer ON customer_lifecycle(customer_id);
CREATE INDEX idx_lifecycle_stage ON customer_lifecycle(current_stage);
CREATE INDEX idx_lifecycle_risk ON customer_lifecycle(churn_risk_score DESC);
CREATE INDEX idx_lifecycle_engagement ON customer_lifecycle(engagement_score DESC);

-- Stage transition history
CREATE TABLE lifecycle_stage_history (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),
    lifecycle_id INTEGER NOT NULL REFERENCES customer_lifecycle(id),

    from_stage VARCHAR(50),
    to_stage VARCHAR(50) NOT NULL,

    trigger_type VARCHAR(50),  -- 'automatic', 'manual', 'event', 'scheduled'
    trigger_details JSONB,

    transitioned_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_stage_history_customer ON lifecycle_stage_history(customer_id);
CREATE INDEX idx_stage_history_time ON lifecycle_stage_history(transitioned_at);

-- ============================================================
-- 2. ONBOARDING SYSTEM
-- ============================================================

CREATE TABLE onboarding_journey (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,

    -- Journey Type
    journey_type VARCHAR(50) NOT NULL DEFAULT 'standard',
    -- Types: 'standard', 'subscription', 'b2b', 'premium'

    -- Steps Configuration
    steps JSONB NOT NULL,
    -- [
    --   {"id": "welcome", "name": "Welcome", "type": "email", "delay_hours": 0},
    --   {"id": "first_order", "name": "First Order", "type": "milestone", "target": "order_count >= 1"},
    --   {"id": "tutorial_1", "name": "Product Tutorial", "type": "content", "content_id": 1}
    -- ]

    total_steps INTEGER NOT NULL DEFAULT 0,

    -- Timing
    max_duration_days INTEGER DEFAULT 30,
    auto_complete_on_purchase BOOLEAN DEFAULT TRUE,

    is_active BOOLEAN DEFAULT TRUE,
    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Customer onboarding progress
CREATE TABLE customer_onboarding (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),
    journey_id INTEGER NOT NULL REFERENCES onboarding_journey(id),

    -- Progress
    status VARCHAR(20) NOT NULL DEFAULT 'in_progress',
    -- Status: 'in_progress', 'completed', 'skipped', 'expired'

    current_step_index INTEGER DEFAULT 0,
    completed_steps JSONB DEFAULT '[]',
    skipped_steps JSONB DEFAULT '[]',

    completion_rate DECIMAL(5, 2) DEFAULT 0,

    -- Timestamps
    started_at TIMESTAMPTZ DEFAULT NOW(),
    completed_at TIMESTAMPTZ,
    last_activity_at TIMESTAMPTZ,

    company_id INTEGER NOT NULL DEFAULT 1,

    UNIQUE(customer_id, journey_id)
);

CREATE INDEX idx_onboarding_customer ON customer_onboarding(customer_id);
CREATE INDEX idx_onboarding_status ON customer_onboarding(status);

-- Onboarding step completions
CREATE TABLE onboarding_step_completion (
    id SERIAL PRIMARY KEY,
    onboarding_id INTEGER NOT NULL REFERENCES customer_onboarding(id) ON DELETE CASCADE,
    step_id VARCHAR(50) NOT NULL,

    status VARCHAR(20) NOT NULL,  -- 'completed', 'skipped', 'pending'
    completed_at TIMESTAMPTZ,

    -- For content steps
    content_viewed BOOLEAN DEFAULT FALSE,
    time_spent_seconds INTEGER,

    -- For milestone steps
    milestone_value DECIMAL(15, 2),

    UNIQUE(onboarding_id, step_id)
);

-- ============================================================
-- 3. ENGAGEMENT CAMPAIGNS
-- ============================================================

CREATE TABLE engagement_campaign (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,

    campaign_type VARCHAR(50) NOT NULL,
    -- Types: 'welcome', 'onboarding', 're_engagement', 'milestone', 'anniversary', 'win_back'

    -- Trigger Configuration
    trigger_config JSONB NOT NULL,
    -- {
    --   "trigger_type": "stage_entry" | "event" | "schedule" | "milestone",
    --   "stage": "at_risk",
    --   "delay_hours": 24
    -- }

    -- Target Audience
    target_stage VARCHAR(50),
    target_segment_id INTEGER,
    target_conditions JSONB,

    -- Content
    email_sequence JSONB NOT NULL,
    -- [
    --   {"step": 1, "delay_hours": 0, "template_id": 1, "channel": "email"},
    --   {"step": 2, "delay_hours": 48, "template_id": 2, "channel": "email"},
    --   {"step": 3, "delay_hours": 168, "template_id": 3, "channel": "sms"}
    -- ]

    -- Offers
    include_offer BOOLEAN DEFAULT FALSE,
    offer_config JSONB,
    -- {"type": "discount", "value": 10, "code_prefix": "WINBACK"}

    -- Control
    is_active BOOLEAN DEFAULT TRUE,
    priority INTEGER DEFAULT 10,
    max_sends_per_customer INTEGER DEFAULT 1,

    -- Statistics
    total_enrolled INTEGER DEFAULT 0,
    total_completed INTEGER DEFAULT 0,
    total_converted INTEGER DEFAULT 0,

    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_engagement_type ON engagement_campaign(campaign_type);
CREATE INDEX idx_engagement_active ON engagement_campaign(is_active);

-- Customer enrollment in campaigns
CREATE TABLE campaign_enrollment (
    id SERIAL PRIMARY KEY,
    campaign_id INTEGER NOT NULL REFERENCES engagement_campaign(id),
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),

    status VARCHAR(20) NOT NULL DEFAULT 'active',
    -- Status: 'active', 'completed', 'converted', 'unsubscribed', 'cancelled'

    current_step INTEGER DEFAULT 1,
    steps_completed INTEGER DEFAULT 0,

    -- Timestamps
    enrolled_at TIMESTAMPTZ DEFAULT NOW(),
    completed_at TIMESTAMPTZ,
    converted_at TIMESTAMPTZ,

    -- Conversion Tracking
    conversion_order_id INTEGER,
    conversion_value DECIMAL(15, 2),

    -- Offer
    offer_code VARCHAR(50),
    offer_used BOOLEAN DEFAULT FALSE,

    company_id INTEGER NOT NULL DEFAULT 1,

    UNIQUE(campaign_id, customer_id)
);

CREATE INDEX idx_enrollment_campaign ON campaign_enrollment(campaign_id);
CREATE INDEX idx_enrollment_customer ON campaign_enrollment(customer_id);
CREATE INDEX idx_enrollment_status ON campaign_enrollment(status);

-- ============================================================
-- 4. NPS & FEEDBACK SYSTEM
-- ============================================================

CREATE TABLE nps_survey (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,

    -- Trigger
    trigger_type VARCHAR(50) NOT NULL,
    -- Types: 'post_order', 'post_delivery', 'periodic', 'milestone'

    trigger_config JSONB NOT NULL,

    -- Survey Configuration
    question_text TEXT NOT NULL DEFAULT 'How likely are you to recommend Smart Dairy to a friend or colleague?',
    followup_promoter TEXT DEFAULT 'What do you love about Smart Dairy?',
    followup_passive TEXT DEFAULT 'What could we do better?',
    followup_detractor TEXT DEFAULT 'What went wrong? How can we improve?',

    -- Settings
    is_active BOOLEAN DEFAULT TRUE,
    cooldown_days INTEGER DEFAULT 90,  -- Min days between surveys

    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- NPS Responses
CREATE TABLE nps_response (
    id SERIAL PRIMARY KEY,
    survey_id INTEGER NOT NULL REFERENCES nps_survey(id),
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),

    -- Score
    score INTEGER NOT NULL CHECK (score >= 0 AND score <= 10),
    category VARCHAR(20) NOT NULL,  -- 'promoter', 'passive', 'detractor'

    -- Followup
    followup_response TEXT,

    -- Context
    trigger_context JSONB,
    -- {"order_id": 123, "delivery_id": 456}

    responded_at TIMESTAMPTZ DEFAULT NOW(),
    company_id INTEGER NOT NULL DEFAULT 1
);

CREATE INDEX idx_nps_response_customer ON nps_response(customer_id);
CREATE INDEX idx_nps_response_category ON nps_response(category);
CREATE INDEX idx_nps_response_time ON nps_response(responded_at);

-- CSAT Surveys
CREATE TABLE csat_response (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),

    -- Context
    context_type VARCHAR(50) NOT NULL,  -- 'order', 'delivery', 'support', 'product'
    context_id INTEGER,

    -- Score (1-5 stars)
    score INTEGER NOT NULL CHECK (score >= 1 AND score <= 5),
    comment TEXT,

    responded_at TIMESTAMPTZ DEFAULT NOW(),
    company_id INTEGER NOT NULL DEFAULT 1
);

CREATE INDEX idx_csat_customer ON csat_response(customer_id);
CREATE INDEX idx_csat_context ON csat_response(context_type, context_id);

-- ============================================================
-- 5. LOYALTY PROGRAM
-- ============================================================

CREATE TABLE loyalty_program (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,

    -- Points Configuration
    points_per_currency DECIMAL(5, 2) DEFAULT 1.0,  -- Points per BDT spent
    points_currency VARCHAR(10) DEFAULT 'BDT',

    -- Earning Rules
    earning_rules JSONB NOT NULL,
    -- [
    --   {"type": "purchase", "points_per_unit": 1, "unit": "currency"},
    --   {"type": "referral", "points": 500},
    --   {"type": "review", "points": 50},
    --   {"type": "birthday", "points": 200}
    -- ]

    -- Tiers
    tiers JSONB NOT NULL,
    -- [
    --   {"name": "Bronze", "code": "bronze", "min_points": 0, "benefits": [...]},
    --   {"name": "Silver", "code": "silver", "min_points": 1000, "benefits": [...]},
    --   {"name": "Gold", "code": "gold", "min_points": 5000, "benefits": [...]},
    --   {"name": "Platinum", "code": "platinum", "min_points": 15000, "benefits": [...]}
    -- ]

    -- Redemption
    redemption_rules JSONB NOT NULL,
    -- [
    --   {"points": 100, "value": 10, "type": "discount"},
    --   {"points": 500, "reward_id": 1, "type": "product"}
    -- ]

    points_expiry_months INTEGER DEFAULT 12,

    is_active BOOLEAN DEFAULT TRUE,
    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Customer loyalty account
CREATE TABLE customer_loyalty (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),
    program_id INTEGER NOT NULL REFERENCES loyalty_program(id),

    -- Points
    points_balance INTEGER DEFAULT 0,
    points_lifetime INTEGER DEFAULT 0,
    points_redeemed INTEGER DEFAULT 0,
    points_expired INTEGER DEFAULT 0,

    -- Tier
    current_tier VARCHAR(50) DEFAULT 'bronze',
    tier_qualified_at TIMESTAMPTZ,
    next_tier_points INTEGER,

    -- Statistics
    total_orders INTEGER DEFAULT 0,
    total_spend DECIMAL(15, 2) DEFAULT 0,
    referral_count INTEGER DEFAULT 0,

    joined_at TIMESTAMPTZ DEFAULT NOW(),
    company_id INTEGER NOT NULL DEFAULT 1,

    UNIQUE(customer_id, program_id)
);

CREATE INDEX idx_loyalty_customer ON customer_loyalty(customer_id);
CREATE INDEX idx_loyalty_tier ON customer_loyalty(current_tier);
CREATE INDEX idx_loyalty_points ON customer_loyalty(points_balance DESC);

-- Points transactions
CREATE TABLE loyalty_transaction (
    id SERIAL PRIMARY KEY,
    loyalty_id INTEGER NOT NULL REFERENCES customer_loyalty(id),
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),

    transaction_type VARCHAR(20) NOT NULL,
    -- Types: 'earn', 'redeem', 'expire', 'adjust', 'transfer'

    points INTEGER NOT NULL,
    balance_after INTEGER NOT NULL,

    -- Source/Reference
    source_type VARCHAR(50),  -- 'order', 'referral', 'promotion', 'manual'
    source_id INTEGER,
    source_description VARCHAR(200),

    -- For redemptions
    redemption_type VARCHAR(50),
    redemption_value DECIMAL(10, 2),
    redemption_code VARCHAR(50),

    -- Expiry
    expires_at TIMESTAMPTZ,

    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_loyalty_trans_loyalty ON loyalty_transaction(loyalty_id);
CREATE INDEX idx_loyalty_trans_customer ON loyalty_transaction(customer_id);
CREATE INDEX idx_loyalty_trans_type ON loyalty_transaction(transaction_type);
CREATE INDEX idx_loyalty_trans_time ON loyalty_transaction(created_at);

-- ============================================================
-- 6. WIN-BACK CAMPAIGNS
-- ============================================================

CREATE TABLE winback_campaign (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,

    -- Target Criteria
    min_days_inactive INTEGER DEFAULT 30,
    max_days_inactive INTEGER DEFAULT 180,
    min_previous_orders INTEGER DEFAULT 1,
    min_previous_spend DECIMAL(10, 2),

    -- Exclusions
    exclude_conditions JSONB,

    -- Email Sequence
    email_sequence JSONB NOT NULL,

    -- Offers
    offer_type VARCHAR(50),  -- 'discount_percent', 'discount_fixed', 'free_delivery', 'bonus_points'
    offer_value DECIMAL(10, 2),
    offer_validity_days INTEGER DEFAULT 14,

    -- Settings
    is_active BOOLEAN DEFAULT TRUE,
    max_attempts INTEGER DEFAULT 3,
    cooldown_days INTEGER DEFAULT 90,

    -- Statistics
    total_targeted INTEGER DEFAULT 0,
    total_won_back INTEGER DEFAULT 0,
    total_revenue_recovered DECIMAL(15, 2) DEFAULT 0,

    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Win-back attempts
CREATE TABLE winback_attempt (
    id SERIAL PRIMARY KEY,
    campaign_id INTEGER NOT NULL REFERENCES winback_campaign(id),
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),

    status VARCHAR(20) NOT NULL DEFAULT 'active',
    -- Status: 'active', 'won_back', 'exhausted', 'unsubscribed'

    current_step INTEGER DEFAULT 1,
    emails_sent INTEGER DEFAULT 0,

    -- Offer
    offer_code VARCHAR(50),
    offer_used BOOLEAN DEFAULT FALSE,

    -- Timestamps
    started_at TIMESTAMPTZ DEFAULT NOW(),
    won_back_at TIMESTAMPTZ,

    -- Conversion
    conversion_order_id INTEGER,
    conversion_value DECIMAL(15, 2),

    company_id INTEGER NOT NULL DEFAULT 1
);

CREATE INDEX idx_winback_campaign ON winback_attempt(campaign_id);
CREATE INDEX idx_winback_customer ON winback_attempt(customer_id);
CREATE INDEX idx_winback_status ON winback_attempt(status);
```

### 3.3 Customer Lifecycle Engine

```python
# -*- coding: utf-8 -*-
"""
Customer Lifecycle Engine
Milestone 128: Customer Lifecycle Automation
"""

from odoo import models, fields, api
from datetime import datetime, timedelta
import json
import logging

_logger = logging.getLogger(__name__)


class CustomerLifecycle(models.Model):
    """Customer Lifecycle Tracking"""
    _name = 'customer.lifecycle'
    _description = 'Customer Lifecycle'
    _order = 'engagement_score desc'

    customer_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True,
        ondelete='cascade'
    )

    # Stage
    current_stage = fields.Selection([
        ('acquired', 'Acquired'),
        ('onboarding', 'Onboarding'),
        ('active', 'Active'),
        ('engaged', 'Highly Engaged'),
        ('at_risk', 'At Risk'),
        ('dormant', 'Dormant'),
        ('churned', 'Churned'),
        ('won_back', 'Won Back')
    ], string='Current Stage', default='acquired')

    previous_stage = fields.Char(string='Previous Stage')
    stage_changed_at = fields.Datetime(string='Stage Changed At')

    # Metrics
    days_as_customer = fields.Integer(string='Days as Customer')
    total_orders = fields.Integer(string='Total Orders')
    total_revenue = fields.Monetary(string='Total Revenue', currency_field='currency_id')
    last_order_date = fields.Date(string='Last Order Date')
    days_since_last_order = fields.Integer(
        string='Days Since Last Order',
        compute='_compute_days_since_order',
        store=True
    )

    # Scores
    engagement_score = fields.Integer(string='Engagement Score', default=50)
    health_score = fields.Integer(string='Health Score', default=50)
    churn_risk_score = fields.Float(string='Churn Risk Score', digits=(5, 4))

    # Onboarding
    onboarding_status = fields.Selection([
        ('not_started', 'Not Started'),
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('skipped', 'Skipped')
    ], string='Onboarding Status', default='not_started')

    onboarding_completion_rate = fields.Float(
        string='Onboarding Completion %',
        digits=(5, 2)
    )

    # Subscription
    has_active_subscription = fields.Boolean(string='Has Active Subscription')
    subscription_count = fields.Integer(string='Active Subscriptions')
    subscription_mrr = fields.Monetary(string='Subscription MRR', currency_field='currency_id')

    # Feedback
    nps_score = fields.Integer(string='NPS Score')
    nps_category = fields.Selection([
        ('promoter', 'Promoter'),
        ('passive', 'Passive'),
        ('detractor', 'Detractor')
    ], string='NPS Category')

    # Loyalty
    loyalty_tier = fields.Selection([
        ('bronze', 'Bronze'),
        ('silver', 'Silver'),
        ('gold', 'Gold'),
        ('platinum', 'Platinum')
    ], string='Loyalty Tier', default='bronze')

    loyalty_points = fields.Integer(string='Loyalty Points')

    currency_id = fields.Many2one(
        'res.currency',
        related='company_id.currency_id'
    )
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    @api.depends('last_order_date')
    def _compute_days_since_order(self):
        for record in self:
            if record.last_order_date:
                delta = datetime.now().date() - record.last_order_date
                record.days_since_last_order = delta.days
            else:
                record.days_since_last_order = 999

    def update_stage(self, new_stage, trigger_type='automatic', trigger_details=None):
        """Update customer lifecycle stage"""
        self.ensure_one()

        if self.current_stage == new_stage:
            return

        old_stage = self.current_stage

        self.write({
            'previous_stage': old_stage,
            'current_stage': new_stage,
            'stage_changed_at': datetime.now()
        })

        # Log transition
        self.env['lifecycle.stage.history'].create({
            'customer_id': self.customer_id.id,
            'lifecycle_id': self.id,
            'from_stage': old_stage,
            'to_stage': new_stage,
            'trigger_type': trigger_type,
            'trigger_details': json.dumps(trigger_details or {})
        })

        # Trigger stage-based campaigns
        self._trigger_stage_campaigns(new_stage)

    def _trigger_stage_campaigns(self, stage):
        """Trigger campaigns based on stage entry"""
        Campaign = self.env['engagement.campaign']
        campaigns = Campaign.search([
            ('is_active', '=', True),
            ('target_stage', '=', stage)
        ])

        for campaign in campaigns:
            self._enroll_in_campaign(campaign)

    def _enroll_in_campaign(self, campaign):
        """Enroll customer in a campaign"""
        Enrollment = self.env['campaign.enrollment']

        # Check if already enrolled
        existing = Enrollment.search([
            ('campaign_id', '=', campaign.id),
            ('customer_id', '=', self.customer_id.id)
        ], limit=1)

        if existing:
            return existing

        # Create enrollment
        enrollment = Enrollment.create({
            'campaign_id': campaign.id,
            'customer_id': self.customer_id.id,
            'status': 'active'
        })

        # Generate offer code if applicable
        if campaign.include_offer:
            enrollment.offer_code = self._generate_offer_code(campaign)

        return enrollment

    def _generate_offer_code(self, campaign):
        """Generate unique offer code"""
        import uuid
        config = campaign.offer_config or {}
        prefix = config.get('code_prefix', 'OFFER')
        unique = str(uuid.uuid4())[:8].upper()
        return f"{prefix}{unique}"


class LifecycleEngine(models.AbstractModel):
    """Lifecycle Management Engine"""
    _name = 'lifecycle.engine'
    _description = 'Lifecycle Engine'

    @api.model
    def evaluate_all_customers(self):
        """Evaluate and update lifecycle stage for all customers"""
        Lifecycle = self.env['customer.lifecycle']
        lifecycles = Lifecycle.search([])

        updated = 0
        for lc in lifecycles:
            try:
                new_stage = self._determine_stage(lc)
                if new_stage != lc.current_stage:
                    lc.update_stage(new_stage)
                    updated += 1
            except Exception as e:
                _logger.error(f"Error evaluating lifecycle {lc.id}: {e}")

        _logger.info(f"Evaluated {len(lifecycles)} customers, updated {updated}")
        return updated

    def _determine_stage(self, lifecycle):
        """Determine the appropriate lifecycle stage"""
        # Check for churned
        if self._is_churned(lifecycle):
            return 'churned'

        # Check for dormant
        if self._is_dormant(lifecycle):
            return 'dormant'

        # Check for at-risk
        if self._is_at_risk(lifecycle):
            return 'at_risk'

        # Check for engaged
        if self._is_engaged(lifecycle):
            return 'engaged'

        # Check for active
        if self._is_active(lifecycle):
            return 'active'

        # Check for onboarding
        if self._is_onboarding(lifecycle):
            return 'onboarding'

        return 'acquired'

    def _is_churned(self, lifecycle):
        """Check if customer is churned"""
        # No orders in 90+ days and no active subscription
        return (
            lifecycle.days_since_last_order >= 90 and
            not lifecycle.has_active_subscription
        )

    def _is_dormant(self, lifecycle):
        """Check if customer is dormant"""
        # No orders in 60-90 days
        return (
            60 <= lifecycle.days_since_last_order < 90 and
            not lifecycle.has_active_subscription
        )

    def _is_at_risk(self, lifecycle):
        """Check if customer is at risk"""
        # High churn risk or declining engagement
        return (
            lifecycle.churn_risk_score >= 0.6 or
            lifecycle.engagement_score < 30 or
            (30 <= lifecycle.days_since_last_order < 60)
        )

    def _is_engaged(self, lifecycle):
        """Check if customer is highly engaged"""
        return (
            lifecycle.engagement_score >= 80 and
            lifecycle.days_since_last_order < 14
        )

    def _is_active(self, lifecycle):
        """Check if customer is active"""
        return (
            lifecycle.days_since_last_order < 30 or
            lifecycle.has_active_subscription
        )

    def _is_onboarding(self, lifecycle):
        """Check if customer is still onboarding"""
        return (
            lifecycle.onboarding_status == 'in_progress' or
            (lifecycle.days_as_customer <= 30 and lifecycle.total_orders < 3)
        )

    @api.model
    def calculate_engagement_score(self, customer_id):
        """Calculate engagement score for a customer"""
        Lifecycle = self.env['customer.lifecycle']
        lifecycle = Lifecycle.search([
            ('customer_id', '=', customer_id)
        ], limit=1)

        if not lifecycle:
            return 50  # Default

        score = 0

        # Order frequency (0-30 points)
        if lifecycle.days_since_last_order < 7:
            score += 30
        elif lifecycle.days_since_last_order < 14:
            score += 25
        elif lifecycle.days_since_last_order < 30:
            score += 15
        elif lifecycle.days_since_last_order < 60:
            score += 5

        # Order count (0-25 points)
        if lifecycle.total_orders >= 20:
            score += 25
        elif lifecycle.total_orders >= 10:
            score += 20
        elif lifecycle.total_orders >= 5:
            score += 15
        elif lifecycle.total_orders >= 2:
            score += 10
        elif lifecycle.total_orders >= 1:
            score += 5

        # Subscription (0-25 points)
        if lifecycle.has_active_subscription:
            score += 25

        # Loyalty tier (0-10 points)
        tier_scores = {'bronze': 2, 'silver': 5, 'gold': 8, 'platinum': 10}
        score += tier_scores.get(lifecycle.loyalty_tier, 0)

        # NPS (0-10 points)
        if lifecycle.nps_category == 'promoter':
            score += 10
        elif lifecycle.nps_category == 'passive':
            score += 5

        return min(100, score)
```

### 3.4 Onboarding Engine

```python
# -*- coding: utf-8 -*-
"""
Onboarding Engine
Milestone 128: Customer Lifecycle Automation
"""

from odoo import models, fields, api
from datetime import datetime, timedelta
import json
import logging

_logger = logging.getLogger(__name__)


class OnboardingJourney(models.Model):
    """Onboarding Journey Definition"""
    _name = 'onboarding.journey'
    _description = 'Onboarding Journey'

    name = fields.Char(string='Journey Name', required=True)
    code = fields.Char(string='Code', required=True)
    description = fields.Text(string='Description')

    journey_type = fields.Selection([
        ('standard', 'Standard'),
        ('subscription', 'Subscription'),
        ('b2b', 'B2B'),
        ('premium', 'Premium')
    ], string='Journey Type', default='standard')

    steps = fields.Text(string='Steps Configuration (JSON)', required=True)
    total_steps = fields.Integer(string='Total Steps')

    max_duration_days = fields.Integer(string='Max Duration (Days)', default=30)
    auto_complete_on_purchase = fields.Boolean(
        string='Auto Complete on Purchase',
        default=True
    )

    is_active = fields.Boolean(string='Active', default=True)
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    @api.model
    def create(self, vals):
        if vals.get('steps'):
            steps = json.loads(vals['steps'])
            vals['total_steps'] = len(steps)
        return super().create(vals)

    def get_steps(self):
        """Get steps as list"""
        return json.loads(self.steps or '[]')


class CustomerOnboarding(models.Model):
    """Customer Onboarding Progress"""
    _name = 'customer.onboarding'
    _description = 'Customer Onboarding'
    _order = 'started_at desc'

    customer_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True
    )
    journey_id = fields.Many2one(
        'onboarding.journey',
        string='Journey',
        required=True
    )

    status = fields.Selection([
        ('in_progress', 'In Progress'),
        ('completed', 'Completed'),
        ('skipped', 'Skipped'),
        ('expired', 'Expired')
    ], string='Status', default='in_progress')

    current_step_index = fields.Integer(string='Current Step', default=0)
    completed_steps = fields.Text(string='Completed Steps', default='[]')
    skipped_steps = fields.Text(string='Skipped Steps', default='[]')

    completion_rate = fields.Float(
        string='Completion Rate',
        compute='_compute_completion_rate',
        store=True,
        digits=(5, 2)
    )

    started_at = fields.Datetime(string='Started At', default=fields.Datetime.now)
    completed_at = fields.Datetime(string='Completed At')
    last_activity_at = fields.Datetime(string='Last Activity At')

    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    @api.depends('completed_steps', 'journey_id.total_steps')
    def _compute_completion_rate(self):
        for record in self:
            total = record.journey_id.total_steps or 1
            completed = len(json.loads(record.completed_steps or '[]'))
            record.completion_rate = (completed / total) * 100

    def complete_step(self, step_id, data=None):
        """Mark a step as completed"""
        self.ensure_one()

        completed = json.loads(self.completed_steps or '[]')
        if step_id not in completed:
            completed.append(step_id)

        self.write({
            'completed_steps': json.dumps(completed),
            'current_step_index': self.current_step_index + 1,
            'last_activity_at': datetime.now()
        })

        # Log step completion
        self.env['onboarding.step.completion'].create({
            'onboarding_id': self.id,
            'step_id': step_id,
            'status': 'completed',
            'completed_at': datetime.now()
        })

        # Check if all steps completed
        if len(completed) >= self.journey_id.total_steps:
            self._complete_onboarding()

        # Process next step
        self._process_next_step()

    def skip_step(self, step_id, reason=None):
        """Skip a step"""
        self.ensure_one()

        skipped = json.loads(self.skipped_steps or '[]')
        if step_id not in skipped:
            skipped.append(step_id)

        self.write({
            'skipped_steps': json.dumps(skipped),
            'current_step_index': self.current_step_index + 1,
            'last_activity_at': datetime.now()
        })

        self.env['onboarding.step.completion'].create({
            'onboarding_id': self.id,
            'step_id': step_id,
            'status': 'skipped'
        })

        self._process_next_step()

    def _complete_onboarding(self):
        """Complete the onboarding"""
        self.write({
            'status': 'completed',
            'completed_at': datetime.now()
        })

        # Update lifecycle
        Lifecycle = self.env['customer.lifecycle']
        lifecycle = Lifecycle.search([
            ('customer_id', '=', self.customer_id.id)
        ], limit=1)

        if lifecycle:
            lifecycle.write({
                'onboarding_status': 'completed',
                'onboarding_completed_at': datetime.now(),
                'onboarding_completion_rate': self.completion_rate
            })

            # Transition to active if appropriate
            if lifecycle.current_stage == 'onboarding':
                lifecycle.update_stage('active', 'onboarding_complete')

        # Send completion email
        self._send_completion_email()

    def _process_next_step(self):
        """Process the next onboarding step"""
        steps = self.journey_id.get_steps()

        if self.current_step_index >= len(steps):
            return

        next_step = steps[self.current_step_index]
        step_type = next_step.get('type')

        if step_type == 'email':
            self._send_step_email(next_step)
        elif step_type == 'push':
            self._send_step_push(next_step)
        elif step_type == 'milestone':
            # Milestone steps are completed by events
            pass

    def _send_step_email(self, step):
        """Send email for a step"""
        template_id = step.get('template_id')
        if template_id:
            template = self.env['email.template.marketing'].browse(template_id)
            if template.exists():
                # Send email
                pass

    def _send_step_push(self, step):
        """Send push notification for a step"""
        # Push notification logic
        pass

    def _send_completion_email(self):
        """Send onboarding completion email"""
        # Completion email logic
        pass


class OnboardingService(models.AbstractModel):
    """Onboarding Service"""
    _name = 'onboarding.service'
    _description = 'Onboarding Service'

    @api.model
    def start_onboarding(self, customer_id, journey_code=None):
        """Start onboarding for a customer"""
        Journey = self.env['onboarding.journey']
        Onboarding = self.env['customer.onboarding']

        # Find appropriate journey
        if journey_code:
            journey = Journey.search([
                ('code', '=', journey_code),
                ('is_active', '=', True)
            ], limit=1)
        else:
            # Use default journey
            journey = Journey.search([
                ('journey_type', '=', 'standard'),
                ('is_active', '=', True)
            ], limit=1)

        if not journey:
            _logger.warning("No onboarding journey found")
            return None

        # Check if already onboarding
        existing = Onboarding.search([
            ('customer_id', '=', customer_id),
            ('journey_id', '=', journey.id),
            ('status', '=', 'in_progress')
        ], limit=1)

        if existing:
            return existing

        # Create onboarding
        onboarding = Onboarding.create({
            'customer_id': customer_id,
            'journey_id': journey.id,
            'status': 'in_progress'
        })

        # Update lifecycle
        Lifecycle = self.env['customer.lifecycle']
        lifecycle = Lifecycle.search([
            ('customer_id', '=', customer_id)
        ], limit=1)

        if lifecycle:
            lifecycle.write({
                'onboarding_status': 'in_progress',
                'onboarding_started_at': datetime.now()
            })
            lifecycle.update_stage('onboarding', 'onboarding_started')

        # Process first step
        onboarding._process_next_step()

        return onboarding

    @api.model
    def handle_milestone(self, customer_id, milestone_type, value=None):
        """Handle a milestone completion"""
        Onboarding = self.env['customer.onboarding']

        # Find active onboarding
        onboarding = Onboarding.search([
            ('customer_id', '=', customer_id),
            ('status', '=', 'in_progress')
        ], limit=1)

        if not onboarding:
            return

        # Check if current step is this milestone
        steps = onboarding.journey_id.get_steps()
        if onboarding.current_step_index >= len(steps):
            return

        current_step = steps[onboarding.current_step_index]

        if current_step.get('type') == 'milestone':
            step_milestone = current_step.get('milestone_type')
            if step_milestone == milestone_type:
                # Check target if specified
                target = current_step.get('target')
                if target and value is not None:
                    if not self._evaluate_target(target, value):
                        return

                onboarding.complete_step(current_step['id'], {'value': value})

    def _evaluate_target(self, target, value):
        """Evaluate milestone target"""
        # Simple evaluation: "order_count >= 1"
        if '>=' in target:
            parts = target.split('>=')
            target_value = int(parts[1].strip())
            return value >= target_value
        elif '>' in target:
            parts = target.split('>')
            target_value = int(parts[1].strip())
            return value > target_value
        return True
```

### 3.5 Loyalty Program Engine

```python
# -*- coding: utf-8 -*-
"""
Loyalty Program Engine
Milestone 128: Customer Lifecycle Automation
"""

from odoo import models, fields, api
from datetime import datetime, timedelta
import json
import logging

_logger = logging.getLogger(__name__)


class LoyaltyProgram(models.Model):
    """Loyalty Program"""
    _name = 'loyalty.program'
    _description = 'Loyalty Program'

    name = fields.Char(string='Program Name', required=True)
    code = fields.Char(string='Code', required=True)
    description = fields.Text(string='Description')

    points_per_currency = fields.Float(
        string='Points per BDT',
        default=1.0,
        digits=(5, 2)
    )

    earning_rules = fields.Text(string='Earning Rules (JSON)', required=True)
    tiers = fields.Text(string='Tiers (JSON)', required=True)
    redemption_rules = fields.Text(string='Redemption Rules (JSON)', required=True)

    points_expiry_months = fields.Integer(string='Points Expiry (Months)', default=12)

    is_active = fields.Boolean(string='Active', default=True)
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    def get_tiers(self):
        """Get tiers as list"""
        return json.loads(self.tiers or '[]')

    def get_earning_rules(self):
        """Get earning rules as list"""
        return json.loads(self.earning_rules or '[]')

    def get_redemption_rules(self):
        """Get redemption rules as list"""
        return json.loads(self.redemption_rules or '[]')


class CustomerLoyalty(models.Model):
    """Customer Loyalty Account"""
    _name = 'customer.loyalty'
    _description = 'Customer Loyalty'
    _order = 'points_balance desc'

    customer_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True
    )
    program_id = fields.Many2one(
        'loyalty.program',
        string='Program',
        required=True
    )

    # Points
    points_balance = fields.Integer(string='Points Balance', default=0)
    points_lifetime = fields.Integer(string='Lifetime Points', default=0)
    points_redeemed = fields.Integer(string='Points Redeemed', default=0)
    points_expired = fields.Integer(string='Points Expired', default=0)

    # Tier
    current_tier = fields.Selection([
        ('bronze', 'Bronze'),
        ('silver', 'Silver'),
        ('gold', 'Gold'),
        ('platinum', 'Platinum')
    ], string='Current Tier', default='bronze')

    tier_qualified_at = fields.Datetime(string='Tier Qualified At')
    next_tier_points = fields.Integer(string='Points to Next Tier')

    # Statistics
    total_orders = fields.Integer(string='Total Orders', default=0)
    total_spend = fields.Monetary(string='Total Spend', currency_field='currency_id')
    referral_count = fields.Integer(string='Referrals', default=0)

    # Relations
    transaction_ids = fields.One2many(
        'loyalty.transaction',
        'loyalty_id',
        string='Transactions'
    )

    joined_at = fields.Datetime(string='Joined At', default=fields.Datetime.now)

    currency_id = fields.Many2one(
        'res.currency',
        related='company_id.currency_id'
    )
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    _sql_constraints = [
        ('unique_customer_program', 'UNIQUE(customer_id, program_id)',
         'Customer already enrolled in this program')
    ]

    def earn_points(self, points, source_type, source_id=None, description=None):
        """Earn loyalty points"""
        self.ensure_one()

        if points <= 0:
            return

        new_balance = self.points_balance + points

        self.write({
            'points_balance': new_balance,
            'points_lifetime': self.points_lifetime + points
        })

        # Create transaction
        self.env['loyalty.transaction'].create({
            'loyalty_id': self.id,
            'customer_id': self.customer_id.id,
            'transaction_type': 'earn',
            'points': points,
            'balance_after': new_balance,
            'source_type': source_type,
            'source_id': source_id,
            'source_description': description,
            'expires_at': datetime.now() + timedelta(
                days=self.program_id.points_expiry_months * 30
            )
        })

        # Check tier upgrade
        self._check_tier_upgrade()

        return new_balance

    def redeem_points(self, points, redemption_type, value, code=None):
        """Redeem loyalty points"""
        self.ensure_one()

        if points <= 0:
            return False

        if points > self.points_balance:
            return False

        new_balance = self.points_balance - points

        self.write({
            'points_balance': new_balance,
            'points_redeemed': self.points_redeemed + points
        })

        # Create transaction
        self.env['loyalty.transaction'].create({
            'loyalty_id': self.id,
            'customer_id': self.customer_id.id,
            'transaction_type': 'redeem',
            'points': -points,
            'balance_after': new_balance,
            'redemption_type': redemption_type,
            'redemption_value': value,
            'redemption_code': code
        })

        return True

    def _check_tier_upgrade(self):
        """Check and upgrade tier if qualified"""
        tiers = self.program_id.get_tiers()

        # Sort tiers by min_points descending
        sorted_tiers = sorted(tiers, key=lambda t: t.get('min_points', 0), reverse=True)

        for tier in sorted_tiers:
            if self.points_lifetime >= tier.get('min_points', 0):
                tier_code = tier.get('code')
                if tier_code != self.current_tier:
                    self.write({
                        'current_tier': tier_code,
                        'tier_qualified_at': datetime.now()
                    })
                    self._notify_tier_upgrade(tier)
                break

        # Calculate points to next tier
        self._calculate_next_tier_points()

    def _calculate_next_tier_points(self):
        """Calculate points needed for next tier"""
        tiers = self.program_id.get_tiers()
        sorted_tiers = sorted(tiers, key=lambda t: t.get('min_points', 0))

        for tier in sorted_tiers:
            if tier.get('min_points', 0) > self.points_lifetime:
                self.next_tier_points = tier['min_points'] - self.points_lifetime
                return

        self.next_tier_points = 0  # Already at highest tier

    def _notify_tier_upgrade(self, tier):
        """Send tier upgrade notification"""
        # Send email and push notification
        pass


class LoyaltyTransaction(models.Model):
    """Loyalty Points Transaction"""
    _name = 'loyalty.transaction'
    _description = 'Loyalty Transaction'
    _order = 'created_at desc'

    loyalty_id = fields.Many2one(
        'customer.loyalty',
        string='Loyalty Account',
        required=True
    )
    customer_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True
    )

    transaction_type = fields.Selection([
        ('earn', 'Earn'),
        ('redeem', 'Redeem'),
        ('expire', 'Expire'),
        ('adjust', 'Adjustment'),
        ('transfer', 'Transfer')
    ], string='Type', required=True)

    points = fields.Integer(string='Points', required=True)
    balance_after = fields.Integer(string='Balance After', required=True)

    # Source
    source_type = fields.Char(string='Source Type')
    source_id = fields.Integer(string='Source ID')
    source_description = fields.Char(string='Description')

    # Redemption
    redemption_type = fields.Char(string='Redemption Type')
    redemption_value = fields.Monetary(string='Redemption Value', currency_field='currency_id')
    redemption_code = fields.Char(string='Redemption Code')

    expires_at = fields.Datetime(string='Expires At')
    created_at = fields.Datetime(string='Created At', default=fields.Datetime.now)

    currency_id = fields.Many2one(
        'res.currency',
        related='loyalty_id.currency_id'
    )


class LoyaltyService(models.AbstractModel):
    """Loyalty Service"""
    _name = 'loyalty.service'
    _description = 'Loyalty Service'

    @api.model
    def enroll_customer(self, customer_id, program_code=None):
        """Enroll customer in loyalty program"""
        Program = self.env['loyalty.program']
        Loyalty = self.env['customer.loyalty']

        # Find program
        if program_code:
            program = Program.search([
                ('code', '=', program_code),
                ('is_active', '=', True)
            ], limit=1)
        else:
            program = Program.search([
                ('is_active', '=', True)
            ], limit=1)

        if not program:
            return None

        # Check existing enrollment
        existing = Loyalty.search([
            ('customer_id', '=', customer_id),
            ('program_id', '=', program.id)
        ], limit=1)

        if existing:
            return existing

        # Create enrollment
        loyalty = Loyalty.create({
            'customer_id': customer_id,
            'program_id': program.id
        })

        # Award welcome bonus if configured
        earning_rules = program.get_earning_rules()
        for rule in earning_rules:
            if rule.get('type') == 'signup':
                loyalty.earn_points(
                    rule.get('points', 0),
                    'signup',
                    description='Welcome bonus'
                )
                break

        return loyalty

    @api.model
    def process_order(self, order_id):
        """Process loyalty points for an order"""
        order = self.env['sale.order'].browse(order_id)
        if not order.exists():
            return

        customer_id = order.partner_id.id

        # Find loyalty account
        Loyalty = self.env['customer.loyalty']
        loyalty = Loyalty.search([
            ('customer_id', '=', customer_id)
        ], limit=1)

        if not loyalty:
            # Auto-enroll
            loyalty = self.enroll_customer(customer_id)

        if not loyalty:
            return

        # Calculate points
        program = loyalty.program_id
        points = int(order.amount_total * program.points_per_currency)

        # Apply tier multiplier
        tier_multiplier = self._get_tier_multiplier(loyalty.current_tier, program)
        points = int(points * tier_multiplier)

        # Award points
        loyalty.earn_points(
            points,
            'order',
            order.id,
            f'Order {order.name}'
        )

        # Update statistics
        loyalty.write({
            'total_orders': loyalty.total_orders + 1,
            'total_spend': loyalty.total_spend + order.amount_total
        })

    def _get_tier_multiplier(self, tier, program):
        """Get points multiplier for tier"""
        tiers = program.get_tiers()
        for t in tiers:
            if t.get('code') == tier:
                benefits = t.get('benefits', [])
                for benefit in benefits:
                    if benefit.get('type') == 'points_multiplier':
                        return benefit.get('value', 1.0)
        return 1.0

    @api.model
    def expire_points(self):
        """Expire old points"""
        Transaction = self.env['loyalty.transaction']

        # Find expiring points
        expiring = Transaction.search([
            ('transaction_type', '=', 'earn'),
            ('expires_at', '<=', datetime.now()),
            ('points', '>', 0)
        ])

        for trans in expiring:
            loyalty = trans.loyalty_id

            # Check if points still available
            if loyalty.points_balance >= trans.points:
                loyalty.write({
                    'points_balance': loyalty.points_balance - trans.points,
                    'points_expired': loyalty.points_expired + trans.points
                })

                # Log expiration
                Transaction.create({
                    'loyalty_id': loyalty.id,
                    'customer_id': loyalty.customer_id.id,
                    'transaction_type': 'expire',
                    'points': -trans.points,
                    'balance_after': loyalty.points_balance,
                    'source_description': f'Points expired from {trans.created_at}'
                })

            # Mark original transaction as expired
            trans.write({'points': 0})

        return len(expiring)
```

---

## 4. Day-by-Day Implementation Plan

### Day 671: Lifecycle Data Models

#### Dev 1 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Design lifecycle database schema | Schema document |
| 2:30-5:00 | Create lifecycle tracking models | `customer.lifecycle` model |
| 5:00-7:00 | Implement stage history tracking | History model |
| 7:00-8:00 | Database migrations | SQL scripts |

#### Dev 2 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Build lifecycle evaluation engine | Stage determination |
| 2:00-4:00 | Create scheduled lifecycle jobs | Celery tasks |
| 4:00-6:00 | Implement engagement scoring | Score calculation |
| 6:00-8:00 | Write unit tests | Test coverage |

#### Dev 3 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Design lifecycle dashboard wireframes | UI mockups |
| 2:00-4:00 | Create lifecycle overview component | Dashboard view |
| 4:00-6:00 | Build stage distribution chart | Visualization |
| 6:00-8:00 | Implement customer stage card | Stage display |

---

### Day 672: Welcome & Onboarding

#### Dev 1 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement onboarding journey model | Journey model |
| 2:30-5:00 | Build customer onboarding tracking | Progress tracking |
| 5:00-7:00 | Create step completion logic | Step handling |
| 7:00-8:00 | Implement milestone triggers | Milestone events |

#### Dev 2 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Build onboarding service | Service layer |
| 2:00-4:00 | Create welcome email sequence | Email automation |
| 4:00-6:00 | Implement step scheduling | Timed steps |
| 6:00-8:00 | Write integration tests | Test coverage |

#### Dev 3 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build onboarding progress widget | Progress display |
| 3:00-5:30 | Create journey builder UI | Journey editor |
| 5:30-8:00 | Implement step configuration | Step forms |

---

### Day 673: Engagement Campaigns

#### Dev 1 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement engagement campaign model | Campaign model |
| 2:30-5:00 | Build campaign enrollment logic | Enrollment service |
| 5:00-7:00 | Create campaign sequence engine | Sequence processing |
| 7:00-8:00 | Implement conversion tracking | Conversion events |

#### Dev 2 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create campaign REST API | API endpoints |
| 2:00-4:00 | Build campaign scheduler | Scheduling service |
| 4:00-6:00 | Implement offer generation | Dynamic offers |
| 6:00-8:00 | Write campaign tests | Test coverage |

#### Dev 3 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build campaign builder UI | Campaign editor |
| 3:00-5:30 | Create enrollment list view | Enrollment display |
| 5:30-8:00 | Implement campaign analytics | Performance metrics |

---

### Day 674: Retention & At-Risk Detection

#### Dev 1 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement at-risk detection algorithm | Risk detection |
| 2:30-5:00 | Build retention campaign engine | Retention automation |
| 5:00-7:00 | Create intervention triggers | Auto-intervention |
| 7:00-8:00 | Implement escalation logic | Escalation rules |

#### Dev 2 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Integrate with health scores | Score integration |
| 2:00-4:00 | Build retention API | API endpoints |
| 4:00-6:00 | Create retention alerts | Alert system |
| 6:00-8:00 | Write retention tests | Test coverage |

#### Dev 3 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build at-risk customer list | At-risk display |
| 3:00-5:30 | Create intervention action panel | Action UI |
| 5:30-8:00 | Implement retention metrics | Metrics display |

---

### Day 675: Win-back Campaigns

#### Dev 1 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement win-back campaign model | Win-back model |
| 2:30-5:00 | Build churned customer identification | Churn detection |
| 5:00-7:00 | Create win-back sequence engine | Sequence processing |
| 7:00-8:00 | Implement reactivation tracking | Reactivation events |

#### Dev 2 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create win-back API | API endpoints |
| 2:00-4:00 | Build win-back offers | Offer generation |
| 4:00-6:00 | Implement cooldown logic | Cooldown handling |
| 6:00-8:00 | Write win-back tests | Test coverage |

#### Dev 3 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build win-back dashboard | Campaign view |
| 3:00-5:30 | Create churned customer list | Churn display |
| 5:30-8:00 | Implement win-back analytics | Performance metrics |

---

### Day 676: NPS & Feedback System

#### Dev 1 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement NPS survey model | Survey model |
| 2:30-5:00 | Build survey trigger system | Trigger automation |
| 5:00-7:00 | Create response collection | Response handling |
| 7:00-8:00 | Implement NPS calculation | Score calculation |

#### Dev 2 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create survey API | API endpoints |
| 2:00-4:00 | Build email survey delivery | Survey emails |
| 4:00-6:00 | Implement in-app surveys | Mobile surveys |
| 6:00-8:00 | Write feedback tests | Test coverage |

#### Dev 3 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build survey response UI | Survey forms |
| 3:00-5:30 | Create NPS dashboard | NPS display |
| 5:30-8:00 | Implement feedback trends | Trend charts |

---

### Day 677: Loyalty Program Core

#### Dev 1 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement loyalty program model | Program model |
| 2:30-5:00 | Build customer loyalty account | Account model |
| 5:00-7:00 | Create points earning engine | Earn logic |
| 7:00-8:00 | Implement tier calculation | Tier system |

#### Dev 2 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create loyalty API | API endpoints |
| 2:00-4:00 | Build order integration | Order points |
| 4:00-6:00 | Implement points expiry | Expiry job |
| 6:00-8:00 | Write loyalty tests | Test coverage |

#### Dev 3 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build loyalty dashboard | Member view |
| 3:00-5:30 | Create points history | Transaction display |
| 5:30-8:00 | Implement tier progress | Tier visualization |

---

### Day 678: Loyalty Redemption & Rewards

#### Dev 1 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement redemption engine | Redemption logic |
| 2:30-5:00 | Build reward catalog | Reward system |
| 5:00-7:00 | Create discount code generation | Code generation |
| 7:00-8:00 | Implement redemption limits | Limits and rules |

#### Dev 2 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create redemption API | API endpoints |
| 2:00-4:00 | Build checkout integration | Checkout points |
| 4:00-6:00 | Implement reward fulfillment | Reward delivery |
| 6:00-8:00 | Write redemption tests | Test coverage |

#### Dev 3 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build rewards catalog UI | Catalog display |
| 3:00-5:30 | Create redemption flow | Redemption UX |
| 5:30-8:00 | Implement reward history | History view |

---

### Day 679: Customer Journey Dashboard

#### Dev 1 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build journey analytics service | Analytics engine |
| 2:30-5:00 | Create cohort analysis | Cohort tracking |
| 5:00-7:00 | Implement lifecycle reporting | Report generation |
| 7:00-8:00 | Build export functionality | Data export |

#### Dev 2 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create analytics API | API endpoints |
| 2:00-4:00 | Build funnel metrics | Funnel analysis |
| 4:00-6:00 | Implement trend calculations | Trend data |
| 6:00-8:00 | Write analytics tests | Test coverage |

#### Dev 3 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build customer journey view | Journey visualization |
| 3:00-5:30 | Create cohort charts | Cohort display |
| 5:30-8:00 | Implement metrics cards | KPI cards |

---

### Day 680: Testing & Deployment

#### Dev 1 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | End-to-end testing | E2E tests |
| 2:00-4:00 | Performance benchmarking | Performance report |
| 4:00-6:00 | Bug fixes | Issue resolution |
| 6:00-8:00 | Documentation | Technical docs |

#### Dev 2 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Integration testing | API tests |
| 2:00-4:00 | Security testing | Security audit |
| 4:00-6:00 | Staging deployment | Deployment |
| 6:00-8:00 | Deployment runbook | Operations docs |

#### Dev 3 Tasks - 8 Hours
| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | UI testing | Visual tests |
| 2:00-4:00 | Cross-browser testing | Compatibility |
| 4:00-6:00 | User documentation | User guide |
| 6:00-8:00 | Demo preparation | Demo script |

---

## 5. Success Criteria

| Criteria | Target | Verification |
|----------|--------|--------------|
| Onboarding completion rate | 80% | Analytics |
| Customer retention rate | 90% | Churn tracking |
| Win-back success rate | 15% | Reactivation tracking |
| NPS score | >50 | Survey responses |
| Loyalty enrollment | 70% of customers | Enrollment count |

---

## 6. Risk Register

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Over-communication | Medium | Medium | Frequency caps |
| Incorrect stage assignment | High | Low | Regular validation |
| Points fraud | High | Low | Fraud detection |
| Survey fatigue | Medium | Medium | Cooldown periods |

---

**Document Version**: 1.0
**Last Updated**: 2026-02-04
