# Milestone 126: Marketing Automation

## Smart Dairy Digital Portal + ERP System
## Phase 12: Commerce - Subscription & Automation

---

## Document Control

| Attribute | Details |
|-----------|---------|
| **Document ID** | SD-P12-M126-v1.0 |
| **Version** | 1.0 |
| **Author** | Technical Architecture Team |
| **Created Date** | 2026-02-04 |
| **Last Modified** | 2026-02-04 |
| **Status** | Draft |
| **Parent Phase** | Phase 12: Commerce - Subscription & Automation |
| **Milestone Duration** | Days 651-660 (10 working days) |
| **Development Team** | 3 Full-Stack Developers |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 126 implements a comprehensive marketing automation platform that enables Smart Dairy to engage customers through personalized, behavior-driven campaigns. The platform includes email campaign management, abandoned cart recovery, product recommendations, A/B testing, and dynamic content personalization.

This milestone transforms Smart Dairy's marketing from manual, one-size-fits-all communications to automated, data-driven customer engagement that increases conversion rates, reduces churn, and maximizes customer lifetime value.

### 1.2 Business Value Proposition

| Value Driver | Business Impact | Quantified Benefit |
|--------------|-----------------|-------------------|
| **Abandoned Cart Recovery** | Recover lost sales automatically | 15-25% cart recovery rate |
| **Email Campaign Automation** | Reduce manual marketing effort | 60% time savings |
| **Personalized Recommendations** | Increase cross-sell/upsell | 20% higher AOV |
| **A/B Testing** | Data-driven optimization | 30% better conversion |
| **Behavioral Triggers** | Right message at right time | 40% higher engagement |

### 1.3 Strategic Alignment

**RFP Requirements Addressed:**

- REQ-MKT-001: Email campaign management platform
- REQ-MKT-002: Abandoned cart recovery automation
- REQ-MKT-003: Product recommendation engine
- REQ-MKT-004: Marketing analytics dashboard

**BRD Requirements Addressed:**

- BR-MKT-01: 300%+ marketing campaign ROI
- BR-MKT-02: 80% email deliverability rate
- BR-MKT-03: Automated customer engagement workflows

**SRS Technical Requirements:**

- SRS-MKT-01: Email delivery <2 seconds
- SRS-MKT-02: Recommendation latency <100ms
- SRS-MKT-03: Support 100,000+ email sends per day

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

#### Priority 0 (Must Have - Days 651-655)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D126-01 | Campaign Data Models | Email campaigns, templates, segments, triggers |
| D126-02 | Email Template Engine | Dynamic template rendering with personalization |
| D126-03 | Campaign Builder | Visual campaign creation interface |
| D126-04 | Abandoned Cart Recovery | Automated recovery email sequences |
| D126-05 | Email Delivery Service | Integration with email providers (SendGrid/SES) |

#### Priority 1 (Should Have - Days 656-658)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D126-06 | Product Recommendation Engine | Collaborative filtering and content-based |
| D126-07 | Behavioral Trigger System | Event-driven campaign triggers |
| D126-08 | A/B Testing Framework | Split testing for campaigns |
| D126-09 | Dynamic Content Personalization | Customer-specific content blocks |
| D126-10 | Campaign Analytics Dashboard | Performance tracking and reporting |

#### Priority 2 (Nice to Have - Days 659-660)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D126-11 | SMS Campaign Support | Multi-channel marketing |
| D126-12 | Push Notification Campaigns | Mobile app marketing |
| D126-13 | Campaign Calendar View | Marketing schedule visualization |
| D126-14 | ROI Attribution | Revenue attribution to campaigns |

### 2.2 Out-of-Scope Items

| Item | Reason | Future Phase |
|------|--------|--------------|
| Social media automation | Requires third-party APIs | Phase 14 |
| Influencer management | Different business function | Post-MVP |
| Paid advertising integration | Complex attribution | Phase 15 |
| Multi-language campaigns | i18n in separate phase | Phase 14 |

### 2.3 Assumptions

1. SendGrid or Amazon SES account is provisioned for email delivery
2. Customer email addresses are verified and consent is obtained
3. Product catalog and customer data from previous phases are available
4. Redis is configured for campaign queue management
5. Subscription analytics from Milestone 125 are operational

### 2.4 Constraints

1. Email sending rate limited by provider (100K/day initial)
2. GDPR/privacy compliance for marketing communications
3. Anti-spam regulations (CAN-SPAM, CASL compliance)
4. Template size limit: 100KB per email
5. A/B test minimum sample size: 1000 recipients

---

## 3. Technical Architecture

### 3.1 Marketing Automation Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                   MARKETING AUTOMATION PLATFORM                      │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                     CAMPAIGN MANAGEMENT                       │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │  Campaign  │  │  Template  │  │  Segment   │  │   A/B   │ │   │
│  │  │  Builder   │  │  Designer  │  │  Builder   │  │  Test   │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                │                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                    AUTOMATION ENGINE                          │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │ Behavioral │  │ Abandoned  │  │  Drip      │  │ Event   │ │   │
│  │  │  Triggers  │  │   Cart     │  │ Campaigns  │  │ Handler │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                │                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                 PERSONALIZATION ENGINE                        │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │  Product   │  │  Dynamic   │  │  Customer  │  │ Content │ │   │
│  │  │   Recs     │  │  Content   │  │  Segments  │  │  Blocks │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                │                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                   DELIVERY LAYER                              │   │
│  │  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌──────────────────┐ │   │
│  │  │  Email  │  │   SMS   │  │  Push   │  │  Rate Limiter    │ │   │
│  │  │ Service │  │ Service │  │ Service │  │  & Queue Manager │ │   │
│  │  └─────────┘  └─────────┘  └─────────┘  └──────────────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
│                                │                                     │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                   ANALYTICS & REPORTING                       │   │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │   │
│  │  │  Campaign  │  │   Email    │  │   ROI      │  │ A/B Test│ │   │
│  │  │  Metrics   │  │  Tracking  │  │ Attribution│  │ Results │ │   │
│  │  └────────────┘  └────────────┘  └────────────┘  └─────────┘ │   │
│  └──────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Database Schema

```sql
-- ============================================================
-- MARKETING AUTOMATION - DATABASE SCHEMA
-- Milestone 126: Days 651-660
-- ============================================================

-- ============================================================
-- 1. EMAIL CAMPAIGN TABLES
-- ============================================================

CREATE TABLE email_campaign (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    campaign_type VARCHAR(50) NOT NULL,
    -- Types: 'one_time', 'automated', 'drip', 'triggered', 'ab_test'

    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    -- Status: 'draft', 'scheduled', 'running', 'paused', 'completed', 'cancelled'

    -- Scheduling
    scheduled_date TIMESTAMPTZ,
    start_date TIMESTAMPTZ,
    end_date TIMESTAMPTZ,

    -- Targeting
    segment_id INTEGER REFERENCES customer_segment(id),
    target_audience JSONB DEFAULT '{}',
    -- {"filters": [...], "exclusions": [...]}

    -- Content
    template_id INTEGER REFERENCES email_template(id),
    subject_line VARCHAR(200),
    preview_text VARCHAR(200),
    from_name VARCHAR(100),
    from_email VARCHAR(200),
    reply_to_email VARCHAR(200),

    -- A/B Testing
    is_ab_test BOOLEAN DEFAULT FALSE,
    ab_test_config JSONB,
    -- {"variants": [...], "test_size": 0.2, "winning_metric": "open_rate"}

    -- Settings
    settings JSONB DEFAULT '{}',
    -- {"track_opens": true, "track_clicks": true, "utm_params": {...}}

    -- Statistics (denormalized for quick access)
    total_recipients INTEGER DEFAULT 0,
    total_sent INTEGER DEFAULT 0,
    total_delivered INTEGER DEFAULT 0,
    total_opened INTEGER DEFAULT 0,
    total_clicked INTEGER DEFAULT 0,
    total_bounced INTEGER DEFAULT 0,
    total_unsubscribed INTEGER DEFAULT 0,
    total_complained INTEGER DEFAULT 0,

    -- Revenue Attribution
    attributed_revenue DECIMAL(15, 2) DEFAULT 0,
    attributed_orders INTEGER DEFAULT 0,

    -- Metadata
    created_by INTEGER REFERENCES res_users(id),
    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_campaign_status ON email_campaign(status);
CREATE INDEX idx_campaign_type ON email_campaign(campaign_type);
CREATE INDEX idx_campaign_scheduled ON email_campaign(scheduled_date);

-- ============================================================
-- 2. EMAIL TEMPLATE TABLES
-- ============================================================

CREATE TABLE email_template (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    template_type VARCHAR(50) NOT NULL,
    -- Types: 'marketing', 'transactional', 'notification', 'system'

    -- Content
    subject_template VARCHAR(500),
    html_content TEXT NOT NULL,
    text_content TEXT,

    -- Design
    design_json JSONB,  -- For drag-drop editor state
    thumbnail_url VARCHAR(500),

    -- Personalization
    merge_tags TEXT[],  -- ['{{customer_name}}', '{{order_total}}']
    dynamic_content_blocks JSONB DEFAULT '[]',

    -- Settings
    is_active BOOLEAN DEFAULT TRUE,
    is_system BOOLEAN DEFAULT FALSE,
    category VARCHAR(100),

    -- Metadata
    created_by INTEGER REFERENCES res_users(id),
    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_template_type ON email_template(template_type);
CREATE INDEX idx_template_category ON email_template(category);

-- ============================================================
-- 3. CUSTOMER SEGMENT TABLES
-- ============================================================

CREATE TABLE customer_segment (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    segment_type VARCHAR(50) NOT NULL,
    -- Types: 'static', 'dynamic', 'rfm', 'behavioral'

    -- Filter Criteria (for dynamic segments)
    filter_criteria JSONB DEFAULT '{}',
    -- {
    --   "conditions": [
    --     {"field": "total_orders", "operator": ">=", "value": 5},
    --     {"field": "last_order_date", "operator": ">=", "value": "-30d"}
    --   ],
    --   "logic": "AND"
    -- }

    -- RFM Segments
    rfm_config JSONB,
    -- {"recency_score": [5], "frequency_score": [4,5], "monetary_score": [4,5]}

    -- Statistics
    customer_count INTEGER DEFAULT 0,
    last_calculated_at TIMESTAMPTZ,

    -- Settings
    is_active BOOLEAN DEFAULT TRUE,
    auto_refresh BOOLEAN DEFAULT TRUE,
    refresh_interval_hours INTEGER DEFAULT 24,

    -- Metadata
    created_by INTEGER REFERENCES res_users(id),
    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Static segment membership
CREATE TABLE segment_membership (
    id SERIAL PRIMARY KEY,
    segment_id INTEGER NOT NULL REFERENCES customer_segment(id) ON DELETE CASCADE,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    added_at TIMESTAMPTZ DEFAULT NOW(),
    source VARCHAR(50),  -- 'manual', 'import', 'automation', 'api'

    UNIQUE(segment_id, customer_id)
);

CREATE INDEX idx_segment_membership ON segment_membership(segment_id, customer_id);

-- ============================================================
-- 4. CAMPAIGN RECIPIENT & TRACKING
-- ============================================================

CREATE TABLE campaign_recipient (
    id SERIAL PRIMARY KEY,
    campaign_id INTEGER NOT NULL REFERENCES email_campaign(id) ON DELETE CASCADE,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),
    email_address VARCHAR(200) NOT NULL,

    -- Personalization Data (snapshot at send time)
    personalization_data JSONB DEFAULT '{}',

    -- A/B Test Variant
    ab_variant VARCHAR(10),  -- 'A', 'B', 'C', etc.

    -- Status
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    -- Status: 'pending', 'sent', 'delivered', 'bounced', 'failed'

    -- Timestamps
    queued_at TIMESTAMPTZ,
    sent_at TIMESTAMPTZ,
    delivered_at TIMESTAMPTZ,

    -- Bounce Info
    bounce_type VARCHAR(20),  -- 'hard', 'soft'
    bounce_reason TEXT,

    -- External References
    message_id VARCHAR(200),  -- Provider message ID

    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_recipient_campaign ON campaign_recipient(campaign_id);
CREATE INDEX idx_recipient_customer ON campaign_recipient(customer_id);
CREATE INDEX idx_recipient_status ON campaign_recipient(status);
CREATE INDEX idx_recipient_message ON campaign_recipient(message_id);

-- Email engagement tracking
CREATE TABLE email_engagement (
    id SERIAL PRIMARY KEY,
    recipient_id INTEGER NOT NULL REFERENCES campaign_recipient(id) ON DELETE CASCADE,
    campaign_id INTEGER NOT NULL REFERENCES email_campaign(id) ON DELETE CASCADE,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),

    engagement_type VARCHAR(20) NOT NULL,
    -- Types: 'open', 'click', 'unsubscribe', 'complaint'

    -- Click details
    link_url TEXT,
    link_id VARCHAR(50),

    -- Context
    user_agent TEXT,
    ip_address INET,
    device_type VARCHAR(20),  -- 'desktop', 'mobile', 'tablet'
    email_client VARCHAR(100),

    -- Timestamp
    engaged_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_engagement_recipient ON email_engagement(recipient_id);
CREATE INDEX idx_engagement_campaign ON email_engagement(campaign_id);
CREATE INDEX idx_engagement_type ON email_engagement(engagement_type);
CREATE INDEX idx_engagement_time ON email_engagement(engaged_at);

-- ============================================================
-- 5. ABANDONED CART TABLES
-- ============================================================

CREATE TABLE abandoned_cart (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER REFERENCES res_partner(id),
    session_id VARCHAR(100),  -- For guest users

    -- Cart Details
    cart_items JSONB NOT NULL,
    -- [{"product_id": 1, "name": "...", "quantity": 2, "price": 100}]
    cart_total DECIMAL(15, 2) NOT NULL,
    currency_id INTEGER,

    -- Customer Info
    email_address VARCHAR(200),
    phone_number VARCHAR(20),

    -- Recovery Status
    recovery_status VARCHAR(20) NOT NULL DEFAULT 'abandoned',
    -- Status: 'abandoned', 'recovery_started', 'recovered', 'expired', 'excluded'

    -- Recovery Tracking
    recovery_emails_sent INTEGER DEFAULT 0,
    last_email_sent_at TIMESTAMPTZ,
    recovered_at TIMESTAMPTZ,
    recovered_order_id INTEGER,

    -- Timestamps
    cart_created_at TIMESTAMPTZ NOT NULL,
    abandoned_at TIMESTAMPTZ NOT NULL,
    expires_at TIMESTAMPTZ,

    -- Metadata
    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_abandoned_cart_customer ON abandoned_cart(customer_id);
CREATE INDEX idx_abandoned_cart_status ON abandoned_cart(recovery_status);
CREATE INDEX idx_abandoned_cart_abandoned ON abandoned_cart(abandoned_at);

-- Abandoned cart recovery sequence
CREATE TABLE cart_recovery_sequence (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,

    -- Sequence Configuration
    steps JSONB NOT NULL,
    -- [
    --   {"delay_hours": 1, "template_id": 10, "channel": "email"},
    --   {"delay_hours": 24, "template_id": 11, "channel": "email"},
    --   {"delay_hours": 72, "template_id": 12, "channel": "email", "include_discount": true}
    -- ]

    -- Discount Settings
    discount_enabled BOOLEAN DEFAULT FALSE,
    discount_type VARCHAR(20),  -- 'percentage', 'fixed'
    discount_value DECIMAL(10, 2),
    discount_code_prefix VARCHAR(20),

    -- Settings
    min_cart_value DECIMAL(10, 2),
    exclude_subscriptions BOOLEAN DEFAULT FALSE,

    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- ============================================================
-- 6. BEHAVIORAL TRIGGER TABLES
-- ============================================================

CREATE TABLE marketing_trigger (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    trigger_type VARCHAR(50) NOT NULL,
    -- Types: 'event', 'date', 'segment_entry', 'segment_exit', 'inactivity'

    -- Trigger Configuration
    event_name VARCHAR(100),  -- For event triggers
    trigger_config JSONB NOT NULL,
    -- {
    --   "event": "subscription_created",
    --   "conditions": [{"field": "plan_type", "operator": "eq", "value": "premium"}],
    --   "delay_minutes": 60
    -- }

    -- Action
    action_type VARCHAR(50) NOT NULL,  -- 'send_email', 'send_sms', 'add_to_segment', 'webhook'
    action_config JSONB NOT NULL,
    -- {"template_id": 5, "from_email": "...", "subject": "..."}

    -- Limits
    frequency_cap INTEGER,  -- Max triggers per customer
    frequency_period_days INTEGER,  -- Within this period

    -- Status
    is_active BOOLEAN DEFAULT TRUE,

    -- Statistics
    total_triggered INTEGER DEFAULT 0,
    last_triggered_at TIMESTAMPTZ,

    -- Metadata
    created_by INTEGER REFERENCES res_users(id),
    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_trigger_type ON marketing_trigger(trigger_type);
CREATE INDEX idx_trigger_event ON marketing_trigger(event_name);
CREATE INDEX idx_trigger_active ON marketing_trigger(is_active);

-- Trigger execution log
CREATE TABLE trigger_execution_log (
    id SERIAL PRIMARY KEY,
    trigger_id INTEGER NOT NULL REFERENCES marketing_trigger(id),
    customer_id INTEGER REFERENCES res_partner(id),

    -- Execution Details
    event_data JSONB,
    execution_status VARCHAR(20) NOT NULL,
    -- Status: 'success', 'failed', 'skipped', 'rate_limited'

    error_message TEXT,

    -- Result
    action_result JSONB,
    -- {"email_sent": true, "message_id": "..."}

    executed_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_trigger_log_trigger ON trigger_execution_log(trigger_id);
CREATE INDEX idx_trigger_log_customer ON trigger_execution_log(customer_id);
CREATE INDEX idx_trigger_log_time ON trigger_execution_log(executed_at);

-- ============================================================
-- 7. PRODUCT RECOMMENDATION TABLES
-- ============================================================

CREATE TABLE product_recommendation (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),
    product_id INTEGER NOT NULL REFERENCES product_product(id),

    recommendation_type VARCHAR(50) NOT NULL,
    -- Types: 'collaborative', 'content_based', 'trending', 'frequently_bought', 'personalized'

    score DECIMAL(5, 4) NOT NULL,  -- 0.0000 to 1.0000
    rank INTEGER,

    -- Context
    context_type VARCHAR(50),  -- 'homepage', 'product_page', 'cart', 'email'
    context_product_id INTEGER,  -- If recommending based on a product

    -- Explanation
    recommendation_reason TEXT,

    -- Validity
    valid_from TIMESTAMPTZ DEFAULT NOW(),
    valid_until TIMESTAMPTZ,

    -- Tracking
    impressions INTEGER DEFAULT 0,
    clicks INTEGER DEFAULT 0,
    purchases INTEGER DEFAULT 0,

    created_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_rec_customer ON product_recommendation(customer_id);
CREATE INDEX idx_rec_product ON product_recommendation(product_id);
CREATE INDEX idx_rec_type ON product_recommendation(recommendation_type);
CREATE INDEX idx_rec_score ON product_recommendation(score DESC);

-- Collaborative filtering model data
CREATE TABLE product_similarity (
    id SERIAL PRIMARY KEY,
    product_id INTEGER NOT NULL REFERENCES product_product(id),
    similar_product_id INTEGER NOT NULL REFERENCES product_product(id),

    similarity_score DECIMAL(5, 4) NOT NULL,
    similarity_type VARCHAR(50) NOT NULL,
    -- Types: 'purchase_history', 'view_history', 'category', 'attribute'

    calculated_at TIMESTAMPTZ DEFAULT NOW(),

    UNIQUE(product_id, similar_product_id, similarity_type)
);

CREATE INDEX idx_similarity_product ON product_similarity(product_id);
CREATE INDEX idx_similarity_score ON product_similarity(similarity_score DESC);

-- ============================================================
-- 8. A/B TEST TABLES
-- ============================================================

CREATE TABLE ab_test (
    id SERIAL PRIMARY KEY,
    campaign_id INTEGER REFERENCES email_campaign(id),
    name VARCHAR(200) NOT NULL,
    description TEXT,

    -- Test Configuration
    test_type VARCHAR(50) NOT NULL,
    -- Types: 'subject_line', 'content', 'send_time', 'from_name'

    variants JSONB NOT NULL,
    -- [
    --   {"id": "A", "name": "Control", "subject": "...", "weight": 0.5},
    --   {"id": "B", "name": "Variant B", "subject": "...", "weight": 0.5}
    -- ]

    -- Test Settings
    test_size_percentage DECIMAL(5, 2) DEFAULT 20.00,
    winning_metric VARCHAR(50) DEFAULT 'open_rate',
    -- Metrics: 'open_rate', 'click_rate', 'conversion_rate', 'revenue'

    confidence_level DECIMAL(5, 2) DEFAULT 95.00,
    min_sample_size INTEGER DEFAULT 1000,

    -- Status
    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    -- Status: 'draft', 'running', 'completed', 'cancelled'

    -- Results
    winning_variant VARCHAR(10),
    statistical_significance BOOLEAN,
    confidence_achieved DECIMAL(5, 2),
    results JSONB,

    -- Timestamps
    started_at TIMESTAMPTZ,
    completed_at TIMESTAMPTZ,

    company_id INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- ============================================================
-- 9. MARKETING ANALYTICS TABLES
-- ============================================================

CREATE TABLE campaign_daily_stats (
    id SERIAL PRIMARY KEY,
    campaign_id INTEGER NOT NULL REFERENCES email_campaign(id),
    stat_date DATE NOT NULL,

    -- Send Stats
    sent INTEGER DEFAULT 0,
    delivered INTEGER DEFAULT 0,
    bounced INTEGER DEFAULT 0,

    -- Engagement Stats
    unique_opens INTEGER DEFAULT 0,
    total_opens INTEGER DEFAULT 0,
    unique_clicks INTEGER DEFAULT 0,
    total_clicks INTEGER DEFAULT 0,

    -- Negative Actions
    unsubscribes INTEGER DEFAULT 0,
    complaints INTEGER DEFAULT 0,

    -- Rates (calculated)
    open_rate DECIMAL(5, 2),
    click_rate DECIMAL(5, 2),
    click_to_open_rate DECIMAL(5, 2),
    unsubscribe_rate DECIMAL(5, 2),

    -- Revenue
    attributed_revenue DECIMAL(15, 2) DEFAULT 0,
    attributed_orders INTEGER DEFAULT 0,

    calculated_at TIMESTAMPTZ DEFAULT NOW(),

    UNIQUE(campaign_id, stat_date)
);

CREATE INDEX idx_campaign_stats_date ON campaign_daily_stats(stat_date);

-- Email deliverability metrics
CREATE TABLE email_deliverability_stats (
    id SERIAL PRIMARY KEY,
    stat_date DATE NOT NULL,

    -- Volume
    total_sent INTEGER DEFAULT 0,
    total_delivered INTEGER DEFAULT 0,
    total_bounced INTEGER DEFAULT 0,

    -- Bounce Breakdown
    hard_bounces INTEGER DEFAULT 0,
    soft_bounces INTEGER DEFAULT 0,

    -- Deliverability Rates
    delivery_rate DECIMAL(5, 2),
    bounce_rate DECIMAL(5, 2),

    -- Reputation
    complaint_rate DECIMAL(5, 4),

    -- By Provider
    stats_by_provider JSONB DEFAULT '{}',
    -- {"gmail": {"sent": 1000, "delivered": 980}, ...}

    company_id INTEGER NOT NULL DEFAULT 1,

    UNIQUE(stat_date, company_id)
);

-- ============================================================
-- 10. SUBSCRIPTION PREFERENCES
-- ============================================================

CREATE TABLE email_subscription_preference (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),
    email_address VARCHAR(200) NOT NULL,

    -- Global Preferences
    is_subscribed BOOLEAN DEFAULT TRUE,
    unsubscribed_at TIMESTAMPTZ,
    unsubscribe_reason TEXT,

    -- Category Preferences
    category_preferences JSONB DEFAULT '{}',
    -- {"marketing": true, "product_updates": true, "newsletters": false}

    -- Frequency Preferences
    preferred_frequency VARCHAR(20) DEFAULT 'normal',
    -- 'low', 'normal', 'high'

    -- Channel Preferences
    email_enabled BOOLEAN DEFAULT TRUE,
    sms_enabled BOOLEAN DEFAULT FALSE,
    push_enabled BOOLEAN DEFAULT FALSE,

    -- Consent
    consent_given_at TIMESTAMPTZ,
    consent_source VARCHAR(100),
    consent_ip_address INET,

    company_id INTEGER NOT NULL DEFAULT 1,
    updated_at TIMESTAMPTZ DEFAULT NOW(),

    UNIQUE(customer_id, company_id)
);

CREATE INDEX idx_subscription_pref_email ON email_subscription_preference(email_address);
CREATE INDEX idx_subscription_pref_subscribed ON email_subscription_preference(is_subscribed);
```

### 3.3 Odoo Marketing Models

```python
# -*- coding: utf-8 -*-
"""
Marketing Automation Models
Milestone 126: Marketing Automation
Smart Dairy Digital Portal + ERP System
"""

from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import datetime, timedelta
import json
import logging

_logger = logging.getLogger(__name__)


class EmailCampaign(models.Model):
    """Email Marketing Campaign"""
    _name = 'email.campaign'
    _description = 'Email Marketing Campaign'
    _order = 'scheduled_date desc, id desc'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(string='Campaign Name', required=True, tracking=True)
    description = fields.Text(string='Description')

    campaign_type = fields.Selection([
        ('one_time', 'One-Time Send'),
        ('automated', 'Automated'),
        ('drip', 'Drip Campaign'),
        ('triggered', 'Triggered'),
        ('ab_test', 'A/B Test')
    ], string='Campaign Type', required=True, default='one_time')

    status = fields.Selection([
        ('draft', 'Draft'),
        ('scheduled', 'Scheduled'),
        ('running', 'Running'),
        ('paused', 'Paused'),
        ('completed', 'Completed'),
        ('cancelled', 'Cancelled')
    ], string='Status', default='draft', tracking=True)

    # Scheduling
    scheduled_date = fields.Datetime(string='Scheduled Date')
    start_date = fields.Datetime(string='Start Date')
    end_date = fields.Datetime(string='End Date')

    # Targeting
    segment_id = fields.Many2one(
        'customer.segment',
        string='Target Segment'
    )
    target_audience = fields.Text(string='Target Audience (JSON)')
    estimated_recipients = fields.Integer(
        string='Estimated Recipients',
        compute='_compute_estimated_recipients'
    )

    # Content
    template_id = fields.Many2one(
        'email.template.marketing',
        string='Email Template',
        required=True
    )
    subject_line = fields.Char(string='Subject Line', size=200)
    preview_text = fields.Char(string='Preview Text', size=200)
    from_name = fields.Char(string='From Name', default='Smart Dairy')
    from_email = fields.Char(string='From Email')
    reply_to_email = fields.Char(string='Reply-To Email')

    # A/B Testing
    is_ab_test = fields.Boolean(string='Is A/B Test', default=False)
    ab_test_id = fields.Many2one('ab.test', string='A/B Test')

    # Settings
    track_opens = fields.Boolean(string='Track Opens', default=True)
    track_clicks = fields.Boolean(string='Track Clicks', default=True)
    utm_source = fields.Char(string='UTM Source', default='email')
    utm_medium = fields.Char(string='UTM Medium', default='marketing')
    utm_campaign = fields.Char(string='UTM Campaign')

    # Statistics
    total_recipients = fields.Integer(string='Total Recipients', readonly=True)
    total_sent = fields.Integer(string='Total Sent', readonly=True)
    total_delivered = fields.Integer(string='Total Delivered', readonly=True)
    total_opened = fields.Integer(string='Total Opened', readonly=True)
    total_clicked = fields.Integer(string='Total Clicked', readonly=True)
    total_bounced = fields.Integer(string='Total Bounced', readonly=True)
    total_unsubscribed = fields.Integer(string='Total Unsubscribed', readonly=True)

    # Rates
    open_rate = fields.Float(
        string='Open Rate (%)',
        compute='_compute_rates',
        digits=(5, 2)
    )
    click_rate = fields.Float(
        string='Click Rate (%)',
        compute='_compute_rates',
        digits=(5, 2)
    )
    bounce_rate = fields.Float(
        string='Bounce Rate (%)',
        compute='_compute_rates',
        digits=(5, 2)
    )

    # Revenue Attribution
    attributed_revenue = fields.Monetary(
        string='Attributed Revenue',
        currency_field='currency_id',
        readonly=True
    )
    attributed_orders = fields.Integer(
        string='Attributed Orders',
        readonly=True
    )

    # Relations
    recipient_ids = fields.One2many(
        'campaign.recipient',
        'campaign_id',
        string='Recipients'
    )
    engagement_ids = fields.One2many(
        'email.engagement',
        'campaign_id',
        string='Engagements'
    )

    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )
    currency_id = fields.Many2one(
        'res.currency',
        related='company_id.currency_id'
    )
    created_by = fields.Many2one(
        'res.users',
        string='Created By',
        default=lambda self: self.env.user
    )

    @api.depends('segment_id', 'target_audience')
    def _compute_estimated_recipients(self):
        for record in self:
            if record.segment_id:
                record.estimated_recipients = record.segment_id.customer_count
            else:
                record.estimated_recipients = 0

    @api.depends('total_sent', 'total_opened', 'total_clicked', 'total_bounced')
    def _compute_rates(self):
        for record in self:
            if record.total_sent > 0:
                record.open_rate = (record.total_opened / record.total_sent) * 100
                record.click_rate = (record.total_clicked / record.total_sent) * 100
                record.bounce_rate = (record.total_bounced / record.total_sent) * 100
            else:
                record.open_rate = 0
                record.click_rate = 0
                record.bounce_rate = 0

    def action_schedule(self):
        """Schedule campaign for sending"""
        self.ensure_one()
        if not self.scheduled_date:
            raise ValidationError("Please set a scheduled date.")
        if self.scheduled_date <= datetime.now():
            raise ValidationError("Scheduled date must be in the future.")

        self._prepare_recipients()
        self.write({'status': 'scheduled'})

    def action_send_now(self):
        """Send campaign immediately"""
        self.ensure_one()
        self._prepare_recipients()
        self.write({
            'status': 'running',
            'start_date': datetime.now()
        })
        self._queue_campaign_emails()

    def action_pause(self):
        """Pause running campaign"""
        self.write({'status': 'paused'})

    def action_resume(self):
        """Resume paused campaign"""
        self.write({'status': 'running'})

    def action_cancel(self):
        """Cancel campaign"""
        self.write({'status': 'cancelled'})

    def _prepare_recipients(self):
        """Prepare recipient list from segment"""
        self.ensure_one()
        Recipient = self.env['campaign.recipient']

        # Clear existing pending recipients
        Recipient.search([
            ('campaign_id', '=', self.id),
            ('status', '=', 'pending')
        ]).unlink()

        # Get customers from segment
        if self.segment_id:
            customers = self.segment_id._get_segment_customers()
        else:
            customers = self.env['res.partner'].search([
                ('email', '!=', False),
                ('is_company', '=', False)
            ])

        # Filter by subscription preferences
        subscribed_customers = customers.filtered(
            lambda c: self._is_customer_subscribed(c)
        )

        # Create recipient records
        for customer in subscribed_customers:
            personalization = self._get_personalization_data(customer)
            Recipient.create({
                'campaign_id': self.id,
                'customer_id': customer.id,
                'email_address': customer.email,
                'personalization_data': json.dumps(personalization),
                'status': 'pending'
            })

        self.total_recipients = len(subscribed_customers)

    def _is_customer_subscribed(self, customer):
        """Check if customer is subscribed to marketing emails"""
        Preference = self.env['email.subscription.preference']
        pref = Preference.search([
            ('customer_id', '=', customer.id),
            ('company_id', '=', self.company_id.id)
        ], limit=1)

        if pref:
            return pref.is_subscribed and pref.email_enabled
        return True  # Default to subscribed if no preference set

    def _get_personalization_data(self, customer):
        """Get personalization data for customer"""
        return {
            'customer_name': customer.name,
            'first_name': customer.name.split()[0] if customer.name else '',
            'email': customer.email,
            'customer_id': customer.id,
        }

    def _queue_campaign_emails(self):
        """Queue emails for sending via Celery"""
        from .tasks import send_campaign_emails_task
        send_campaign_emails_task.delay(self.id)


class EmailTemplateMarketing(models.Model):
    """Marketing Email Template"""
    _name = 'email.template.marketing'
    _description = 'Marketing Email Template'

    name = fields.Char(string='Template Name', required=True)
    description = fields.Text(string='Description')

    template_type = fields.Selection([
        ('marketing', 'Marketing'),
        ('transactional', 'Transactional'),
        ('notification', 'Notification'),
        ('system', 'System')
    ], string='Template Type', default='marketing')

    # Content
    subject_template = fields.Char(string='Subject Template', size=500)
    html_content = fields.Html(string='HTML Content', required=True)
    text_content = fields.Text(string='Plain Text Content')

    # Design
    design_json = fields.Text(string='Design JSON')
    thumbnail = fields.Binary(string='Thumbnail')

    # Personalization
    merge_tags = fields.Text(string='Merge Tags')
    dynamic_content_blocks = fields.Text(string='Dynamic Content Blocks')

    # Settings
    is_active = fields.Boolean(string='Active', default=True)
    is_system = fields.Boolean(string='System Template', default=False)
    category = fields.Char(string='Category')

    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    def render_template(self, personalization_data):
        """Render template with personalization"""
        from jinja2 import Template

        html_template = Template(self.html_content or '')
        text_template = Template(self.text_content or '')
        subject_template = Template(self.subject_template or '')

        return {
            'subject': subject_template.render(**personalization_data),
            'html': html_template.render(**personalization_data),
            'text': text_template.render(**personalization_data)
        }


class CustomerSegment(models.Model):
    """Customer Segment for Targeting"""
    _name = 'customer.segment'
    _description = 'Customer Segment'

    name = fields.Char(string='Segment Name', required=True)
    description = fields.Text(string='Description')

    segment_type = fields.Selection([
        ('static', 'Static'),
        ('dynamic', 'Dynamic'),
        ('rfm', 'RFM-Based'),
        ('behavioral', 'Behavioral')
    ], string='Segment Type', required=True, default='dynamic')

    # Filter Criteria
    filter_criteria = fields.Text(
        string='Filter Criteria (JSON)',
        default='{}'
    )

    # RFM Configuration
    rfm_config = fields.Text(string='RFM Configuration (JSON)')

    # Statistics
    customer_count = fields.Integer(
        string='Customer Count',
        compute='_compute_customer_count',
        store=True
    )
    last_calculated_at = fields.Datetime(string='Last Calculated')

    # Settings
    is_active = fields.Boolean(string='Active', default=True)
    auto_refresh = fields.Boolean(string='Auto Refresh', default=True)
    refresh_interval_hours = fields.Integer(
        string='Refresh Interval (Hours)',
        default=24
    )

    # Relations
    membership_ids = fields.One2many(
        'segment.membership',
        'segment_id',
        string='Memberships'
    )

    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    @api.depends('membership_ids', 'filter_criteria')
    def _compute_customer_count(self):
        for record in self:
            if record.segment_type == 'static':
                record.customer_count = len(record.membership_ids)
            else:
                record.customer_count = len(record._get_segment_customers())

    def _get_segment_customers(self):
        """Get customers matching segment criteria"""
        self.ensure_one()

        if self.segment_type == 'static':
            return self.membership_ids.mapped('customer_id')

        # Dynamic segment - evaluate filter criteria
        criteria = json.loads(self.filter_criteria or '{}')
        domain = self._build_domain_from_criteria(criteria)

        return self.env['res.partner'].search(domain)

    def _build_domain_from_criteria(self, criteria):
        """Convert JSON criteria to Odoo domain"""
        domain = [
            ('email', '!=', False),
            ('is_company', '=', False),
            ('company_id', '=', self.company_id.id)
        ]

        conditions = criteria.get('conditions', [])
        logic = criteria.get('logic', 'AND')

        for condition in conditions:
            field = condition.get('field')
            operator = condition.get('operator')
            value = condition.get('value')

            # Map operators
            op_map = {
                'eq': '=',
                'neq': '!=',
                'gt': '>',
                'gte': '>=',
                'lt': '<',
                'lte': '<=',
                'contains': 'ilike',
                'in': 'in'
            }

            odoo_op = op_map.get(operator, '=')
            domain.append((field, odoo_op, value))

        return domain

    def action_refresh(self):
        """Manually refresh segment"""
        self._compute_customer_count()
        self.last_calculated_at = datetime.now()


class CampaignRecipient(models.Model):
    """Campaign Recipient"""
    _name = 'campaign.recipient'
    _description = 'Campaign Recipient'

    campaign_id = fields.Many2one(
        'email.campaign',
        string='Campaign',
        required=True,
        ondelete='cascade'
    )
    customer_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True
    )
    email_address = fields.Char(string='Email Address', required=True)

    personalization_data = fields.Text(string='Personalization Data')
    ab_variant = fields.Char(string='A/B Variant', size=10)

    status = fields.Selection([
        ('pending', 'Pending'),
        ('sent', 'Sent'),
        ('delivered', 'Delivered'),
        ('bounced', 'Bounced'),
        ('failed', 'Failed')
    ], string='Status', default='pending')

    # Timestamps
    queued_at = fields.Datetime(string='Queued At')
    sent_at = fields.Datetime(string='Sent At')
    delivered_at = fields.Datetime(string='Delivered At')

    # Bounce Info
    bounce_type = fields.Selection([
        ('hard', 'Hard Bounce'),
        ('soft', 'Soft Bounce')
    ], string='Bounce Type')
    bounce_reason = fields.Text(string='Bounce Reason')

    # External Reference
    message_id = fields.Char(string='Message ID', size=200)


class EmailEngagement(models.Model):
    """Email Engagement Tracking"""
    _name = 'email.engagement'
    _description = 'Email Engagement'

    recipient_id = fields.Many2one(
        'campaign.recipient',
        string='Recipient',
        required=True,
        ondelete='cascade'
    )
    campaign_id = fields.Many2one(
        'email.campaign',
        string='Campaign',
        required=True
    )
    customer_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True
    )

    engagement_type = fields.Selection([
        ('open', 'Open'),
        ('click', 'Click'),
        ('unsubscribe', 'Unsubscribe'),
        ('complaint', 'Complaint')
    ], string='Engagement Type', required=True)

    # Click Details
    link_url = fields.Text(string='Link URL')
    link_id = fields.Char(string='Link ID', size=50)

    # Context
    user_agent = fields.Text(string='User Agent')
    ip_address = fields.Char(string='IP Address', size=45)
    device_type = fields.Selection([
        ('desktop', 'Desktop'),
        ('mobile', 'Mobile'),
        ('tablet', 'Tablet'),
        ('unknown', 'Unknown')
    ], string='Device Type', default='unknown')
    email_client = fields.Char(string='Email Client', size=100)

    engaged_at = fields.Datetime(
        string='Engaged At',
        default=fields.Datetime.now
    )
```

### 3.4 Abandoned Cart Recovery Service

```python
# -*- coding: utf-8 -*-
"""
Abandoned Cart Recovery Service
Milestone 126: Marketing Automation
"""

from odoo import models, api
from datetime import datetime, timedelta
import json
import logging

_logger = logging.getLogger(__name__)


class AbandonedCartService(models.AbstractModel):
    """Service for abandoned cart detection and recovery"""
    _name = 'abandoned.cart.service'
    _description = 'Abandoned Cart Recovery Service'

    # Configuration
    CART_ABANDON_THRESHOLD_MINUTES = 30
    CART_EXPIRY_DAYS = 30

    @api.model
    def detect_abandoned_carts(self):
        """
        Detect and record abandoned shopping carts

        A cart is considered abandoned if:
        1. Has items but no checkout completed
        2. No activity for CART_ABANDON_THRESHOLD_MINUTES
        3. Not already marked as abandoned
        """
        threshold = datetime.now() - timedelta(
            minutes=self.CART_ABANDON_THRESHOLD_MINUTES
        )

        # Find carts with items but no recent activity
        SaleOrder = self.env['sale.order']
        abandoned_orders = SaleOrder.search([
            ('state', '=', 'draft'),
            ('cart_quantity', '>', 0),
            ('write_date', '<', threshold),
            ('is_abandoned', '=', False)
        ])

        AbandonedCart = self.env['abandoned.cart']
        created_count = 0

        for order in abandoned_orders:
            # Skip if customer opted out
            if order.partner_id and not self._can_send_recovery(order.partner_id):
                continue

            # Create abandoned cart record
            cart_items = []
            for line in order.order_line:
                cart_items.append({
                    'product_id': line.product_id.id,
                    'name': line.product_id.name,
                    'quantity': line.product_uom_qty,
                    'price': line.price_unit,
                    'subtotal': line.price_subtotal
                })

            AbandonedCart.create({
                'customer_id': order.partner_id.id if order.partner_id else False,
                'session_id': order.session_id if hasattr(order, 'session_id') else None,
                'cart_items': json.dumps(cart_items),
                'cart_total': order.amount_total,
                'currency_id': order.currency_id.id,
                'email_address': order.partner_id.email if order.partner_id else None,
                'recovery_status': 'abandoned',
                'cart_created_at': order.create_date,
                'abandoned_at': datetime.now(),
                'expires_at': datetime.now() + timedelta(days=self.CART_EXPIRY_DAYS),
                'company_id': order.company_id.id
            })

            # Mark order as abandoned
            order.write({'is_abandoned': True})
            created_count += 1

        _logger.info(f"Detected {created_count} abandoned carts")
        return created_count

    @api.model
    def process_recovery_sequences(self):
        """
        Process abandoned carts through recovery sequences

        Sends recovery emails based on configured sequences
        """
        AbandonedCart = self.env['abandoned.cart']
        Sequence = self.env['cart.recovery.sequence']

        # Get active recovery sequence
        sequence = Sequence.search([
            ('is_active', '=', True)
        ], limit=1)

        if not sequence:
            _logger.warning("No active cart recovery sequence found")
            return 0

        steps = json.loads(sequence.steps)
        processed_count = 0

        # Get carts eligible for recovery
        eligible_carts = AbandonedCart.search([
            ('recovery_status', 'in', ['abandoned', 'recovery_started']),
            ('email_address', '!=', False),
            ('expires_at', '>', datetime.now())
        ])

        for cart in eligible_carts:
            # Determine which step to send
            current_step = self._get_current_step(cart, steps)

            if current_step is None:
                continue  # All steps completed

            # Check if enough time has passed for this step
            if not self._should_send_step(cart, current_step):
                continue

            # Send recovery email
            success = self._send_recovery_email(cart, current_step, sequence)

            if success:
                cart.write({
                    'recovery_status': 'recovery_started',
                    'recovery_emails_sent': cart.recovery_emails_sent + 1,
                    'last_email_sent_at': datetime.now()
                })
                processed_count += 1

        _logger.info(f"Processed {processed_count} cart recovery emails")
        return processed_count

    def _can_send_recovery(self, customer):
        """Check if customer can receive recovery emails"""
        Preference = self.env['email.subscription.preference']
        pref = Preference.search([
            ('customer_id', '=', customer.id)
        ], limit=1)

        if pref:
            return pref.is_subscribed and pref.email_enabled
        return True

    def _get_current_step(self, cart, steps):
        """Determine which recovery step to send next"""
        emails_sent = cart.recovery_emails_sent

        if emails_sent >= len(steps):
            return None  # All steps completed

        return steps[emails_sent]

    def _should_send_step(self, cart, step):
        """Check if enough time has passed to send this step"""
        delay_hours = step.get('delay_hours', 0)

        if cart.recovery_emails_sent == 0:
            # First email - check from abandoned time
            reference_time = cart.abandoned_at
        else:
            # Subsequent emails - check from last email
            reference_time = cart.last_email_sent_at

        required_time = reference_time + timedelta(hours=delay_hours)
        return datetime.now() >= required_time

    def _send_recovery_email(self, cart, step, sequence):
        """Send recovery email for a cart"""
        try:
            Template = self.env['email.template.marketing']
            template = Template.browse(step.get('template_id'))

            if not template.exists():
                _logger.error(f"Template {step.get('template_id')} not found")
                return False

            # Prepare personalization data
            cart_items = json.loads(cart.cart_items)
            personalization = {
                'customer_name': cart.customer_id.name if cart.customer_id else 'Valued Customer',
                'cart_items': cart_items,
                'cart_total': cart.cart_total,
                'cart_url': self._get_cart_recovery_url(cart),
                'currency_symbol': cart.currency_id.symbol if cart.currency_id else '৳'
            }

            # Add discount if configured
            if step.get('include_discount') and sequence.discount_enabled:
                discount_code = self._generate_discount_code(cart, sequence)
                personalization['discount_code'] = discount_code
                personalization['discount_value'] = sequence.discount_value
                personalization['discount_type'] = sequence.discount_type

            # Render template
            rendered = template.render_template(personalization)

            # Send email
            EmailService = self.env['email.delivery.service']
            success = EmailService.send_email(
                to_email=cart.email_address,
                subject=rendered['subject'],
                html_content=rendered['html'],
                text_content=rendered['text'],
                from_email=self.env.company.email,
                from_name='Smart Dairy',
                tags=['cart_recovery', f'step_{cart.recovery_emails_sent + 1}']
            )

            return success

        except Exception as e:
            _logger.error(f"Error sending recovery email for cart {cart.id}: {e}")
            return False

    def _get_cart_recovery_url(self, cart):
        """Generate cart recovery URL"""
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')
        return f"{base_url}/shop/cart/recover/{cart.id}"

    def _generate_discount_code(self, cart, sequence):
        """Generate unique discount code for cart recovery"""
        import uuid
        prefix = sequence.discount_code_prefix or 'RECOVER'
        unique_part = str(uuid.uuid4())[:8].upper()
        return f"{prefix}{unique_part}"

    @api.model
    def mark_cart_recovered(self, cart_id, order_id):
        """Mark cart as recovered when order is placed"""
        cart = self.env['abandoned.cart'].browse(cart_id)
        if cart.exists():
            cart.write({
                'recovery_status': 'recovered',
                'recovered_at': datetime.now(),
                'recovered_order_id': order_id
            })
            return True
        return False

    @api.model
    def cleanup_expired_carts(self):
        """Mark expired carts"""
        AbandonedCart = self.env['abandoned.cart']
        expired = AbandonedCart.search([
            ('recovery_status', 'in', ['abandoned', 'recovery_started']),
            ('expires_at', '<', datetime.now())
        ])

        expired.write({'recovery_status': 'expired'})
        _logger.info(f"Marked {len(expired)} carts as expired")
        return len(expired)
```

### 3.5 Product Recommendation Engine

```python
# -*- coding: utf-8 -*-
"""
Product Recommendation Engine
Milestone 126: Marketing Automation
"""

from odoo import models, api
from datetime import datetime, timedelta
from collections import defaultdict
import math
import logging

_logger = logging.getLogger(__name__)


class ProductRecommendationEngine(models.AbstractModel):
    """Engine for generating product recommendations"""
    _name = 'product.recommendation.engine'
    _description = 'Product Recommendation Engine'

    @api.model
    def get_recommendations(self, customer_id, context_type='homepage',
                           context_product_id=None, limit=10):
        """
        Get personalized product recommendations for a customer

        Args:
            customer_id: Customer to get recommendations for
            context_type: Where recommendations will be shown
            context_product_id: Product being viewed (for product page context)
            limit: Number of recommendations to return

        Returns:
            List of recommended products with scores
        """
        recommendations = []

        # Strategy 1: Collaborative Filtering (based on similar customers)
        collab_recs = self._get_collaborative_recommendations(
            customer_id, limit=limit
        )
        recommendations.extend(collab_recs)

        # Strategy 2: Content-Based (based on customer's purchase history)
        content_recs = self._get_content_based_recommendations(
            customer_id, limit=limit
        )
        recommendations.extend(content_recs)

        # Strategy 3: Similar Products (for product page context)
        if context_product_id:
            similar_recs = self._get_similar_products(
                context_product_id, limit=limit
            )
            recommendations.extend(similar_recs)

        # Strategy 4: Frequently Bought Together
        if context_product_id:
            fbt_recs = self._get_frequently_bought_together(
                context_product_id, limit=limit
            )
            recommendations.extend(fbt_recs)

        # Strategy 5: Trending Products (fallback)
        trending_recs = self._get_trending_products(limit=limit)
        recommendations.extend(trending_recs)

        # Deduplicate and rank
        final_recs = self._deduplicate_and_rank(recommendations, limit)

        # Store recommendations for tracking
        self._store_recommendations(customer_id, final_recs, context_type)

        return final_recs

    def _get_collaborative_recommendations(self, customer_id, limit=10):
        """
        Collaborative filtering: Find products bought by similar customers

        Uses item-based collaborative filtering:
        1. Find products the customer has purchased
        2. Find other customers who bought those products
        3. Recommend products those similar customers bought
        """
        recommendations = []

        # Get customer's purchase history
        customer_products = self._get_customer_purchase_history(customer_id)

        if not customer_products:
            return recommendations

        # Find similar customers
        similar_customers = self._find_similar_customers(
            customer_id, customer_products
        )

        if not similar_customers:
            return recommendations

        # Get products bought by similar customers but not by this customer
        SaleOrderLine = self.env['sale.order.line']
        similar_purchases = SaleOrderLine.read_group(
            [
                ('order_id.partner_id', 'in', similar_customers),
                ('order_id.state', 'in', ['sale', 'done']),
                ('product_id', 'not in', customer_products)
            ],
            ['product_id'],
            ['product_id']
        )

        for purchase in similar_purchases[:limit]:
            product_id = purchase['product_id'][0]
            product = self.env['product.product'].browse(product_id)

            if product.exists() and product.sale_ok:
                score = purchase['product_id_count'] / len(similar_customers)
                recommendations.append({
                    'product_id': product_id,
                    'product': product,
                    'score': min(1.0, score),
                    'recommendation_type': 'collaborative',
                    'reason': 'Customers like you also bought'
                })

        return recommendations

    def _get_content_based_recommendations(self, customer_id, limit=10):
        """
        Content-based filtering: Recommend products similar to what customer bought

        Based on:
        - Same category
        - Similar price range
        - Similar attributes
        """
        recommendations = []

        # Get customer's preferred categories and price ranges
        preferences = self._analyze_customer_preferences(customer_id)

        if not preferences:
            return recommendations

        # Find products matching preferences
        Product = self.env['product.product']
        domain = [
            ('sale_ok', '=', True),
            ('active', '=', True)
        ]

        # Filter by preferred categories
        if preferences.get('categories'):
            domain.append(('categ_id', 'in', preferences['categories']))

        # Filter by price range
        if preferences.get('avg_price'):
            avg_price = preferences['avg_price']
            domain.append(('list_price', '>=', avg_price * 0.5))
            domain.append(('list_price', '<=', avg_price * 2.0))

        # Exclude already purchased products
        purchased = self._get_customer_purchase_history(customer_id)
        if purchased:
            domain.append(('id', 'not in', purchased))

        products = Product.search(domain, limit=limit * 2)

        for product in products[:limit]:
            score = self._calculate_content_score(product, preferences)
            recommendations.append({
                'product_id': product.id,
                'product': product,
                'score': score,
                'recommendation_type': 'content_based',
                'reason': 'Based on your purchase history'
            })

        return recommendations

    def _get_similar_products(self, product_id, limit=10):
        """Get products similar to a given product"""
        recommendations = []

        # Check pre-computed similarity
        Similarity = self.env['product.similarity']
        similar = Similarity.search([
            ('product_id', '=', product_id)
        ], order='similarity_score desc', limit=limit)

        for sim in similar:
            product = sim.similar_product_id
            if product.sale_ok and product.active:
                recommendations.append({
                    'product_id': product.id,
                    'product': product,
                    'score': sim.similarity_score,
                    'recommendation_type': 'similar',
                    'reason': 'Similar to what you\'re viewing'
                })

        return recommendations

    def _get_frequently_bought_together(self, product_id, limit=5):
        """Get products frequently bought together with a given product"""
        recommendations = []

        # Find orders containing this product
        SaleOrderLine = self.env['sale.order.line']
        order_ids = SaleOrderLine.search([
            ('product_id', '=', product_id),
            ('order_id.state', 'in', ['sale', 'done'])
        ]).mapped('order_id').ids

        if not order_ids:
            return recommendations

        # Find other products in those orders
        co_purchases = SaleOrderLine.read_group(
            [
                ('order_id', 'in', order_ids),
                ('product_id', '!=', product_id)
            ],
            ['product_id'],
            ['product_id']
        )

        total_orders = len(order_ids)

        for purchase in co_purchases[:limit]:
            product = self.env['product.product'].browse(purchase['product_id'][0])
            if product.exists() and product.sale_ok:
                # Calculate co-occurrence score
                co_occurrence = purchase['product_id_count'] / total_orders
                recommendations.append({
                    'product_id': product.id,
                    'product': product,
                    'score': co_occurrence,
                    'recommendation_type': 'frequently_bought',
                    'reason': 'Frequently bought together'
                })

        return recommendations

    def _get_trending_products(self, limit=10, days=7):
        """Get trending products based on recent sales"""
        recommendations = []

        cutoff = datetime.now() - timedelta(days=days)

        SaleOrderLine = self.env['sale.order.line']
        trending = SaleOrderLine.read_group(
            [
                ('order_id.date_order', '>=', cutoff),
                ('order_id.state', 'in', ['sale', 'done'])
            ],
            ['product_id'],
            ['product_id'],
            orderby='product_id_count desc',
            limit=limit
        )

        max_count = trending[0]['product_id_count'] if trending else 1

        for item in trending:
            product = self.env['product.product'].browse(item['product_id'][0])
            if product.exists() and product.sale_ok:
                score = item['product_id_count'] / max_count
                recommendations.append({
                    'product_id': product.id,
                    'product': product,
                    'score': score * 0.5,  # Lower weight for trending
                    'recommendation_type': 'trending',
                    'reason': 'Trending now'
                })

        return recommendations

    def _get_customer_purchase_history(self, customer_id):
        """Get list of product IDs customer has purchased"""
        SaleOrderLine = self.env['sale.order.line']
        lines = SaleOrderLine.search([
            ('order_id.partner_id', '=', customer_id),
            ('order_id.state', 'in', ['sale', 'done'])
        ])
        return lines.mapped('product_id').ids

    def _find_similar_customers(self, customer_id, customer_products, limit=50):
        """Find customers with similar purchase patterns"""
        SaleOrderLine = self.env['sale.order.line']

        # Find customers who bought the same products
        similar_lines = SaleOrderLine.search([
            ('product_id', 'in', customer_products),
            ('order_id.partner_id', '!=', customer_id),
            ('order_id.state', 'in', ['sale', 'done'])
        ])

        # Count overlap per customer
        customer_overlap = defaultdict(int)
        for line in similar_lines:
            customer_overlap[line.order_id.partner_id.id] += 1

        # Sort by overlap and return top customers
        sorted_customers = sorted(
            customer_overlap.items(),
            key=lambda x: x[1],
            reverse=True
        )

        return [c[0] for c in sorted_customers[:limit]]

    def _analyze_customer_preferences(self, customer_id):
        """Analyze customer's purchase preferences"""
        SaleOrderLine = self.env['sale.order.line']
        lines = SaleOrderLine.search([
            ('order_id.partner_id', '=', customer_id),
            ('order_id.state', 'in', ['sale', 'done'])
        ])

        if not lines:
            return {}

        # Analyze categories
        categories = lines.mapped('product_id.categ_id').ids
        category_counts = defaultdict(int)
        for line in lines:
            category_counts[line.product_id.categ_id.id] += 1

        top_categories = sorted(
            category_counts.items(),
            key=lambda x: x[1],
            reverse=True
        )[:5]

        # Analyze price range
        prices = [l.price_unit for l in lines]
        avg_price = sum(prices) / len(prices) if prices else 0

        return {
            'categories': [c[0] for c in top_categories],
            'avg_price': avg_price,
            'total_orders': len(set(lines.mapped('order_id').ids))
        }

    def _calculate_content_score(self, product, preferences):
        """Calculate content-based recommendation score"""
        score = 0.5  # Base score

        # Category match bonus
        if product.categ_id.id in preferences.get('categories', []):
            score += 0.3

        # Price range match bonus
        avg_price = preferences.get('avg_price', 0)
        if avg_price > 0:
            price_diff = abs(product.list_price - avg_price) / avg_price
            if price_diff < 0.2:
                score += 0.2
            elif price_diff < 0.5:
                score += 0.1

        return min(1.0, score)

    def _deduplicate_and_rank(self, recommendations, limit):
        """Remove duplicates and rank recommendations"""
        seen_products = set()
        unique_recs = []

        # Sort by score
        sorted_recs = sorted(
            recommendations,
            key=lambda x: x['score'],
            reverse=True
        )

        for rec in sorted_recs:
            if rec['product_id'] not in seen_products:
                seen_products.add(rec['product_id'])
                unique_recs.append(rec)

                if len(unique_recs) >= limit:
                    break

        # Add rank
        for i, rec in enumerate(unique_recs):
            rec['rank'] = i + 1

        return unique_recs

    def _store_recommendations(self, customer_id, recommendations, context_type):
        """Store recommendations for tracking"""
        Recommendation = self.env['product.recommendation']

        for rec in recommendations:
            Recommendation.create({
                'customer_id': customer_id,
                'product_id': rec['product_id'],
                'recommendation_type': rec['recommendation_type'],
                'score': rec['score'],
                'rank': rec['rank'],
                'context_type': context_type,
                'recommendation_reason': rec.get('reason', ''),
                'valid_until': datetime.now() + timedelta(days=1)
            })
```

---

## 4. Day-by-Day Implementation Plan

### Day 651: Campaign Data Models & Infrastructure

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Design marketing automation database schema | Schema design document |
| 2:00-4:00 | Create SQL migrations for campaign tables | Campaign, template, segment tables |
| 4:00-6:00 | Implement Odoo campaign models | `email.campaign`, `email.template.marketing` |
| 6:00-7:30 | Create customer segment model | `customer.segment` model |
| 7:30-8:00 | Code review and documentation | Technical docs |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Set up email delivery service integration | SendGrid/SES configuration |
| 2:00-4:00 | Configure Celery tasks for campaign processing | Task definitions |
| 4:00-6:00 | Implement email tracking infrastructure | Open/click tracking |
| 6:00-7:30 | Set up Redis queues for email delivery | Queue configuration |
| 7:30-8:00 | Security review | Access control setup |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Design campaign builder wireframes | UI mockups |
| 2:00-4:00 | Set up React marketing module | Project structure |
| 4:00-6:00 | Create campaign list component | `CampaignList.jsx` |
| 6:00-7:30 | Build campaign status badges | Status indicators |
| 7:30-8:00 | Set up API service | `marketingApi.js` |

**Daily Deliverables:**
- [ ] Campaign database tables created
- [ ] Odoo models implemented
- [ ] Email service configured
- [ ] Campaign list UI rendering

---

### Day 652: Email Template Engine

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement template rendering engine | Jinja2-based renderer |
| 2:30-5:00 | Build merge tag processing | Personalization system |
| 5:00-7:00 | Create dynamic content blocks | Conditional content |
| 7:00-8:00 | Template validation logic | Syntax validation |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Implement template API endpoints | CRUD operations |
| 2:00-4:00 | Build template preview service | Preview generation |
| 4:00-6:00 | Create template duplication feature | Clone functionality |
| 6:00-7:30 | Write template unit tests | Test coverage |
| 7:30-8:00 | API documentation | OpenAPI spec |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build email template editor | `TemplateEditor.jsx` |
| 3:00-5:30 | Implement merge tag insertion | Tag picker UI |
| 5:30-7:30 | Create template preview panel | Live preview |
| 7:30-8:00 | Mobile preview mode | Responsive preview |

**Daily Deliverables:**
- [ ] Template engine operational
- [ ] Merge tag processing working
- [ ] Template editor UI complete
- [ ] Preview functionality ready

---

### Day 653: Campaign Builder

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement campaign workflow engine | State machine |
| 2:30-5:00 | Build recipient preparation logic | Segment evaluation |
| 5:00-7:00 | Create A/B test variant assignment | Variant splitting |
| 7:00-8:00 | Campaign scheduling logic | Scheduler service |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Implement campaign REST API | Full CRUD + actions |
| 2:00-4:00 | Build campaign validation service | Pre-send checks |
| 4:00-6:00 | Create send estimation endpoint | Recipient counting |
| 6:00-7:30 | Write campaign API tests | Integration tests |
| 7:30-8:00 | Performance optimization | Query optimization |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build campaign builder wizard | Multi-step form |
| 3:00-5:00 | Create segment selector component | Audience targeting |
| 5:00-7:00 | Implement scheduling interface | Date/time picker |
| 7:00-8:00 | Add A/B test configuration UI | Variant setup |

**Daily Deliverables:**
- [ ] Campaign builder wizard complete
- [ ] Recipient preparation working
- [ ] Scheduling functional
- [ ] A/B test setup UI ready

---

### Day 654: Abandoned Cart Recovery

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement abandoned cart detection | Detection service |
| 2:30-5:00 | Build recovery sequence engine | Multi-step sequences |
| 5:00-7:00 | Create discount code generation | Dynamic discounts |
| 7:00-8:00 | Recovery tracking logic | Conversion tracking |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create Celery tasks for cart detection | Scheduled detection |
| 2:00-4:00 | Build recovery email sending task | Email delivery |
| 4:00-6:00 | Implement cart recovery URL handler | Recovery links |
| 6:00-7:30 | Write abandoned cart tests | Test coverage |
| 7:30-8:00 | Performance testing | Load testing |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build abandoned cart dashboard | Analytics view |
| 2:30-5:00 | Create recovery sequence builder | Sequence configuration |
| 5:00-7:00 | Implement cart recovery email templates | Template library |
| 7:00-8:00 | Add recovery metrics display | KPI cards |

**Daily Deliverables:**
- [ ] Cart detection working
- [ ] Recovery sequences functional
- [ ] Discount codes generating
- [ ] Dashboard displaying metrics

---

### Day 655: Email Delivery & Tracking

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement email delivery service | SendGrid integration |
| 2:30-5:00 | Build bounce handling | Bounce processing |
| 5:00-7:00 | Create unsubscribe processing | Opt-out handling |
| 7:00-8:00 | Complaint handling | Spam complaint processing |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Implement webhook handlers | Event webhooks |
| 2:00-4:00 | Build click tracking service | Link wrapping |
| 4:00-6:00 | Create open tracking pixel | Tracking pixel |
| 6:00-7:30 | Rate limiting implementation | Throttling |
| 7:30-8:00 | Security audit | Webhook security |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build campaign analytics dashboard | Metrics display |
| 2:30-5:00 | Create engagement timeline | Event timeline |
| 5:00-7:00 | Implement click map visualization | Link heatmap |
| 7:00-8:00 | Add deliverability metrics | Provider breakdown |

**Daily Deliverables:**
- [ ] Email delivery operational
- [ ] Tracking working
- [ ] Webhook handlers complete
- [ ] Analytics dashboard ready

---

### Day 656: Product Recommendation Engine

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement collaborative filtering | Similar customers algorithm |
| 2:30-5:00 | Build content-based recommendations | Preference analysis |
| 5:00-7:00 | Create frequently bought together | Co-purchase analysis |
| 7:00-8:00 | Trending products algorithm | Sales velocity |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create recommendation API | REST endpoints |
| 2:00-4:00 | Build product similarity job | Batch calculation |
| 4:00-6:00 | Implement recommendation caching | Redis caching |
| 6:00-7:30 | Write recommendation tests | Accuracy tests |
| 7:30-8:00 | Performance optimization | Query tuning |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build recommendation carousel | Product slider |
| 2:30-5:00 | Create "Frequently Bought Together" | Bundle display |
| 5:00-7:00 | Implement recommendation widgets | Email widgets |
| 7:00-8:00 | Add recommendation tracking | Click tracking |

**Daily Deliverables:**
- [ ] Recommendation algorithms working
- [ ] API endpoints functional
- [ ] Frontend widgets complete
- [ ] Tracking implemented

---

### Day 657: Behavioral Triggers

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Design trigger system architecture | Event-driven design |
| 2:30-5:00 | Implement event trigger processing | Event handlers |
| 5:00-7:00 | Build segment entry/exit triggers | Segment monitoring |
| 7:00-8:00 | Create inactivity triggers | Dormancy detection |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create trigger evaluation service | Condition evaluation |
| 2:00-4:00 | Build trigger execution engine | Action execution |
| 4:00-6:00 | Implement frequency capping | Rate limiting |
| 6:00-7:30 | Write trigger tests | Test coverage |
| 7:30-8:00 | Monitoring setup | Trigger metrics |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build trigger builder interface | Visual builder |
| 3:00-5:30 | Create condition configurator | Rule builder |
| 5:30-7:30 | Implement trigger testing UI | Test mode |
| 7:30-8:00 | Add trigger analytics | Performance view |

**Daily Deliverables:**
- [ ] Trigger system operational
- [ ] Event processing working
- [ ] Builder UI complete
- [ ] Frequency capping active

---

### Day 658: A/B Testing Framework

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement A/B test engine | Test management |
| 2:30-5:00 | Build variant assignment logic | Random assignment |
| 5:00-7:00 | Create statistical significance calculator | Chi-square test |
| 7:00-8:00 | Winner selection algorithm | Auto-winner detection |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create A/B test API | CRUD + results |
| 2:00-4:00 | Build test result aggregation | Metrics calculation |
| 4:00-6:00 | Implement auto-send winner | Automation |
| 6:00-7:30 | Write A/B test tests | Statistical tests |
| 7:30-8:00 | Documentation | Methodology docs |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build A/B test configuration UI | Variant editor |
| 2:30-5:00 | Create test results dashboard | Comparison view |
| 5:00-7:00 | Implement statistical charts | Confidence intervals |
| 7:00-8:00 | Add winner announcement UI | Results display |

**Daily Deliverables:**
- [ ] A/B test engine complete
- [ ] Statistical calculations working
- [ ] Results dashboard ready
- [ ] Auto-winner functional

---

### Day 659: Campaign Analytics & Reporting

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build campaign analytics aggregation | Daily stats |
| 2:30-5:00 | Implement revenue attribution | Order tracking |
| 5:00-7:00 | Create deliverability reporting | Provider metrics |
| 7:00-8:00 | ROI calculation engine | ROI metrics |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create analytics API endpoints | Reporting APIs |
| 2:00-4:00 | Build export functionality | CSV/PDF export |
| 4:00-6:00 | Implement scheduled reports | Report automation |
| 6:00-7:30 | Write analytics tests | Data accuracy tests |
| 7:30-8:00 | Performance optimization | Query optimization |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build campaign performance dashboard | Executive view |
| 3:00-5:30 | Create trend charts | Time-series charts |
| 5:30-7:30 | Implement comparison reports | A/B comparison |
| 7:30-8:00 | Add export buttons | Download functionality |

**Daily Deliverables:**
- [ ] Analytics aggregation working
- [ ] Revenue attribution complete
- [ ] Dashboard displaying metrics
- [ ] Export functionality ready

---

### Day 660: Testing, Integration & Deployment

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | End-to-end campaign testing | E2E test suite |
| 2:00-4:00 | Email deliverability testing | SPF/DKIM verification |
| 4:00-6:00 | Performance benchmarking | Load testing |
| 6:00-7:30 | Bug fixes | Issue resolution |
| 7:30-8:00 | Documentation finalization | Technical docs |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Integration testing | API integration tests |
| 2:00-4:00 | Security testing | Penetration testing |
| 4:00-5:30 | Staging deployment | Staging environment |
| 5:30-7:00 | UAT support | User testing |
| 7:00-8:00 | Deployment runbook | Operations docs |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | UI testing | Visual regression tests |
| 2:00-4:00 | Cross-browser testing | Browser compatibility |
| 4:00-5:30 | Accessibility testing | WCAG compliance |
| 5:30-7:00 | User documentation | User guide |
| 7:00-8:00 | Demo preparation | Demo script |

**Daily Deliverables:**
- [ ] All tests passing
- [ ] Security audit complete
- [ ] Staging deployment successful
- [ ] Documentation finalized

---

## 5. Deliverables Checklist

### Technical Deliverables

| ID | Deliverable | Verification Method | Status |
|----|-------------|---------------------|--------|
| T126-01 | Email campaign models | Schema verification | ☐ |
| T126-02 | Template rendering engine | Unit tests | ☐ |
| T126-03 | Campaign builder workflow | Integration tests | ☐ |
| T126-04 | Abandoned cart recovery | E2E tests | ☐ |
| T126-05 | Email delivery service | Delivery verification | ☐ |
| T126-06 | Product recommendations | Accuracy testing | ☐ |
| T126-07 | Behavioral triggers | Trigger execution tests | ☐ |
| T126-08 | A/B testing framework | Statistical validation | ☐ |
| T126-09 | Campaign analytics | Data accuracy tests | ☐ |
| T126-10 | Marketing REST APIs | API tests | ☐ |

### Documentation Deliverables

| ID | Deliverable | Status |
|----|-------------|--------|
| D126-01 | Marketing automation API documentation | ☐ |
| D126-02 | Template design guidelines | ☐ |
| D126-03 | Campaign best practices guide | ☐ |
| D126-04 | A/B testing methodology | ☐ |

---

## 6. Success Criteria

### Functional Criteria

| Criteria | Target | Verification |
|----------|--------|--------------|
| Campaign delivery rate | >98% | Delivery logs |
| Abandoned cart detection | <1 hour | Timing tests |
| Cart recovery rate | 15-25% | Recovery tracking |
| Recommendation accuracy | >60% CTR improvement | A/B tests |
| Trigger execution time | <5 seconds | Performance tests |

### Technical Criteria

| Criteria | Target | Verification |
|----------|--------|--------------|
| Email sending throughput | 100K/day | Load testing |
| Recommendation latency | <100ms | Performance tests |
| Campaign creation time | <30 seconds | UX testing |
| API response time (P95) | <500ms | Load testing |

### Business Criteria

| Criteria | Target | Verification |
|----------|--------|--------------|
| Campaign ROI | 300%+ | Revenue attribution |
| Email open rate | >20% | Analytics |
| Click-through rate | >3% | Analytics |
| Marketing team efficiency | 60% time savings | Process audit |

---

## 7. Risk Register

| Risk ID | Description | Impact | Probability | Mitigation |
|---------|-------------|--------|-------------|------------|
| R126-01 | Email deliverability issues | High | Medium | SPF/DKIM setup, warm-up |
| R126-02 | Spam complaints | High | Medium | Proper opt-in, easy unsubscribe |
| R126-03 | Recommendation accuracy | Medium | Medium | Multiple algorithms, A/B testing |
| R126-04 | Trigger storm | Medium | Low | Rate limiting, circuit breakers |
| R126-05 | Email provider API limits | Medium | Medium | Multiple providers, queuing |

---

## 8. Dependencies

### Internal Dependencies

| Dependency | Source | Required By |
|------------|--------|-------------|
| Customer data | Phase 3 | Segmentation |
| Product catalog | Phase 6 | Recommendations |
| Order data | Phase 8 | Cart recovery |
| Subscription data | Milestone 121-125 | Triggers |

### External Dependencies

| Dependency | Provider | Purpose |
|------------|----------|---------|
| Email service | SendGrid/SES | Email delivery |
| Redis | Redis Ltd | Queue management |
| Celery | Celery Project | Task processing |

---

**Document Version**: 1.0
**Last Updated**: 2026-02-04
**Next Review**: 2026-02-14
