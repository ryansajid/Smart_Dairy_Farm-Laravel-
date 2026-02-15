# Milestone 125: Subscription Analytics

## Smart Dairy Digital Portal + ERP System
## Phase 12: Commerce - Subscription & Automation

---

## Document Control

| Attribute | Details |
|-----------|---------|
| **Document ID** | SD-P12-M125-v1.0 |
| **Version** | 1.0 |
| **Author** | Technical Architecture Team |
| **Created Date** | 2026-02-04 |
| **Last Modified** | 2026-02-04 |
| **Status** | Draft |
| **Parent Phase** | Phase 12: Commerce - Subscription & Automation |
| **Milestone Duration** | Days 641-650 (10 working days) |
| **Development Team** | 3 Full-Stack Developers |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 125 delivers a comprehensive subscription analytics platform that transforms raw subscription data into actionable business intelligence. This milestone implements advanced metrics calculation engines for MRR/ARR tracking, churn analysis, cohort retention studies, and customer lifetime value predictions.

The analytics platform integrates with Apache Superset (established in Phase 11) to provide real-time dashboards for executive decision-making, enabling Smart Dairy to monitor subscription health, predict churn, and optimize customer retention strategies.

### 1.2 Business Value Proposition

| Value Driver | Business Impact | Quantified Benefit |
|--------------|-----------------|-------------------|
| **Revenue Visibility** | Real-time MRR/ARR tracking | Accurate financial forecasting |
| **Churn Prevention** | Early warning system for at-risk customers | 20% reduction in churn |
| **Customer Insights** | CLV-based customer segmentation | 15% increase in upsell success |
| **Retention Optimization** | Cohort analysis for program effectiveness | 25% improvement in retention |
| **Predictive Intelligence** | ML-based churn prediction | 30% earlier intervention |

### 1.3 Strategic Alignment

**RFP Requirements Addressed:**
- REQ-ANA-001: Subscription analytics dashboards
- REQ-ANA-002: Revenue metrics tracking (MRR/ARR)
- REQ-ANA-003: Customer churn analysis
- REQ-ANA-004: Predictive analytics capabilities

**BRD Requirements Addressed:**
- BR-ANA-01: Real-time subscription health monitoring
- BR-ANA-02: Executive dashboard with KPIs
- BR-ANA-03: Automated reporting and alerts

**SRS Technical Requirements:**
- SRS-ANA-01: Dashboard load time <2 seconds
- SRS-ANA-02: Data refresh interval ≤15 minutes
- SRS-ANA-03: Support for 100+ concurrent dashboard users

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

#### Priority 0 (Must Have - Days 641-645)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D125-01 | MRR/ARR Calculation Engine | Automated monthly/annual recurring revenue calculation |
| D125-02 | Churn Rate Analytics | Logo churn and revenue churn metrics |
| D125-03 | Analytics Data Models | TimescaleDB hypertables for time-series data |
| D125-04 | Core Metrics API | REST endpoints for analytics data access |
| D125-05 | Executive Dashboard | High-level subscription health overview |

#### Priority 1 (Should Have - Days 646-648)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D125-06 | Cohort Retention Analysis | Monthly cohort tracking with retention curves |
| D125-07 | Customer Lifetime Value | CLV calculation with predictive modeling |
| D125-08 | Revenue Forecasting | Time-series forecasting for MRR projections |
| D125-09 | Health Score System | Composite subscription health scoring |
| D125-10 | Drill-down Dashboards | Detailed analytics views by segment |

#### Priority 2 (Nice to Have - Days 649-650)

| ID | Deliverable | Description |
|----|-------------|-------------|
| D125-11 | Churn Prediction Model | ML model for churn risk scoring |
| D125-12 | Anomaly Detection | Automated alerts for metric anomalies |
| D125-13 | Custom Report Builder | User-defined analytics reports |
| D125-14 | Data Export API | Bulk analytics data export |

### 2.2 Out-of-Scope Items

| Item | Reason | Future Phase |
|------|--------|--------------|
| Real-time streaming analytics | Complex infrastructure | Phase 14 |
| Advanced ML model training | Requires data science team | Phase 15 |
| Third-party BI tool integration | Beyond current scope | Post-MVP |
| Multi-tenant analytics isolation | Single tenant deployment | Phase 14 |

### 2.3 Assumptions

1. TimescaleDB extension is installed and configured on PostgreSQL 16
2. Apache Superset is operational from Phase 11 deployment
3. Subscription data from Milestones 121-124 is available and consistent
4. Celery workers are configured for scheduled analytics jobs
5. Minimum 6 months of historical subscription data for meaningful analysis

### 2.4 Constraints

1. Analytics calculations must not impact transactional database performance
2. Dashboard queries must complete within 2 seconds
3. Data freshness must be within 15 minutes of source
4. Storage budget for analytics data: 500GB initial allocation
5. Compute budget for ML models: Standard CPU (no GPU requirement)

---

## 3. Technical Architecture

### 3.1 Analytics Data Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                    SUBSCRIPTION ANALYTICS PLATFORM                   │
├─────────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌────────────┐ │
│  │  Executive  │  │   Churn     │  │   Cohort    │  │  Revenue   │ │
│  │  Dashboard  │  │  Analysis   │  │  Retention  │  │  Forecast  │ │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └─────┬──────┘ │
│         │                │                │                │        │
│  ┌──────┴────────────────┴────────────────┴────────────────┴──────┐ │
│  │                    APACHE SUPERSET                              │ │
│  │              (Visualization & Dashboard Layer)                  │ │
│  └──────────────────────────┬──────────────────────────────────────┘ │
│                             │                                        │
│  ┌──────────────────────────┴──────────────────────────────────────┐ │
│  │                    ANALYTICS API LAYER                          │ │
│  │         (FastAPI + Odoo Controllers + GraphQL)                  │ │
│  └──────────────────────────┬──────────────────────────────────────┘ │
│                             │                                        │
│  ┌──────────────────────────┴──────────────────────────────────────┐ │
│  │                 METRICS CALCULATION ENGINE                       │ │
│  │    ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────────────┐   │ │
│  │    │   MRR   │  │  Churn  │  │   CLV   │  │  Health Score   │   │ │
│  │    │ Engine  │  │ Engine  │  │ Engine  │  │     Engine      │   │ │
│  │    └─────────┘  └─────────┘  └─────────┘  └─────────────────┘   │ │
│  └──────────────────────────┬──────────────────────────────────────┘ │
│                             │                                        │
│  ┌──────────────────────────┴──────────────────────────────────────┐ │
│  │                    DATA STORAGE LAYER                           │ │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  │ │
│  │  │   TimescaleDB   │  │   PostgreSQL    │  │      Redis      │  │ │
│  │  │  (Time-Series)  │  │  (Aggregates)   │  │    (Cache)      │  │ │
│  │  └─────────────────┘  └─────────────────┘  └─────────────────┘  │ │
│  └─────────────────────────────────────────────────────────────────┘ │
│                             │                                        │
│  ┌──────────────────────────┴──────────────────────────────────────┐ │
│  │                    ETL PIPELINE (Celery + Airflow)              │ │
│  │         Source: Subscription DB → Transform → Analytics DB      │ │
│  └─────────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────────┘
```

### 3.2 Database Schema - TimescaleDB Hypertables

```sql
-- ============================================================
-- SUBSCRIPTION ANALYTICS - TIMESCALEDB HYPERTABLES
-- Milestone 125: Days 641-650
-- ============================================================

-- Enable TimescaleDB extension
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- ============================================================
-- 1. MRR DAILY SNAPSHOT HYPERTABLE
-- ============================================================
CREATE TABLE subscription_mrr_daily (
    snapshot_date DATE NOT NULL,
    company_id INTEGER NOT NULL DEFAULT 1,

    -- MRR Components
    total_mrr DECIMAL(15, 2) NOT NULL DEFAULT 0,
    new_mrr DECIMAL(15, 2) NOT NULL DEFAULT 0,
    expansion_mrr DECIMAL(15, 2) NOT NULL DEFAULT 0,
    contraction_mrr DECIMAL(15, 2) NOT NULL DEFAULT 0,
    churned_mrr DECIMAL(15, 2) NOT NULL DEFAULT 0,
    reactivation_mrr DECIMAL(15, 2) NOT NULL DEFAULT 0,

    -- Net MRR Movement
    net_new_mrr DECIMAL(15, 2) GENERATED ALWAYS AS (
        new_mrr + expansion_mrr + reactivation_mrr - contraction_mrr - churned_mrr
    ) STORED,

    -- Subscription Counts
    total_subscriptions INTEGER NOT NULL DEFAULT 0,
    new_subscriptions INTEGER NOT NULL DEFAULT 0,
    churned_subscriptions INTEGER NOT NULL DEFAULT 0,
    active_subscriptions INTEGER NOT NULL DEFAULT 0,
    paused_subscriptions INTEGER NOT NULL DEFAULT 0,

    -- ARPU Metrics
    arpu DECIMAL(10, 2) GENERATED ALWAYS AS (
        CASE WHEN active_subscriptions > 0
             THEN total_mrr / active_subscriptions
             ELSE 0
        END
    ) STORED,

    -- Segmentation
    mrr_by_plan JSONB DEFAULT '{}',
    mrr_by_product_category JSONB DEFAULT '{}',
    mrr_by_customer_segment JSONB DEFAULT '{}',
    mrr_by_region JSONB DEFAULT '{}',

    -- Metadata
    calculated_at TIMESTAMPTZ DEFAULT NOW(),
    calculation_duration_ms INTEGER,

    PRIMARY KEY (snapshot_date, company_id)
);

-- Convert to hypertable with daily partitioning
SELECT create_hypertable(
    'subscription_mrr_daily',
    'snapshot_date',
    chunk_time_interval => INTERVAL '1 month'
);

-- Compression policy for older data
ALTER TABLE subscription_mrr_daily SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'company_id'
);

SELECT add_compression_policy('subscription_mrr_daily', INTERVAL '3 months');

-- Retention policy (keep 3 years of daily data)
SELECT add_retention_policy('subscription_mrr_daily', INTERVAL '3 years');

-- ============================================================
-- 2. CHURN METRICS DAILY HYPERTABLE
-- ============================================================
CREATE TABLE subscription_churn_daily (
    snapshot_date DATE NOT NULL,
    company_id INTEGER NOT NULL DEFAULT 1,

    -- Logo Churn (Customer Count Based)
    customers_start_of_period INTEGER NOT NULL DEFAULT 0,
    customers_churned INTEGER NOT NULL DEFAULT 0,
    logo_churn_rate DECIMAL(8, 4) GENERATED ALWAYS AS (
        CASE WHEN customers_start_of_period > 0
             THEN (customers_churned::DECIMAL / customers_start_of_period) * 100
             ELSE 0
        END
    ) STORED,

    -- Revenue Churn (MRR Based)
    mrr_start_of_period DECIMAL(15, 2) NOT NULL DEFAULT 0,
    mrr_churned DECIMAL(15, 2) NOT NULL DEFAULT 0,
    revenue_churn_rate DECIMAL(8, 4) GENERATED ALWAYS AS (
        CASE WHEN mrr_start_of_period > 0
             THEN (mrr_churned / mrr_start_of_period) * 100
             ELSE 0
        END
    ) STORED,

    -- Net Revenue Churn (including expansion)
    mrr_expansion DECIMAL(15, 2) NOT NULL DEFAULT 0,
    mrr_contraction DECIMAL(15, 2) NOT NULL DEFAULT 0,
    net_revenue_churn DECIMAL(15, 2) GENERATED ALWAYS AS (
        mrr_churned + mrr_contraction - mrr_expansion
    ) STORED,
    net_revenue_churn_rate DECIMAL(8, 4),

    -- Churn Breakdown by Reason
    churn_by_reason JSONB DEFAULT '{}',
    -- Example: {"voluntary": 10, "involuntary": 5, "payment_failed": 3}

    -- Churn Breakdown by Segment
    churn_by_plan JSONB DEFAULT '{}',
    churn_by_tenure JSONB DEFAULT '{}',
    churn_by_region JSONB DEFAULT '{}',

    -- Reactivations
    reactivated_customers INTEGER NOT NULL DEFAULT 0,
    reactivated_mrr DECIMAL(15, 2) NOT NULL DEFAULT 0,

    -- Metadata
    calculated_at TIMESTAMPTZ DEFAULT NOW(),

    PRIMARY KEY (snapshot_date, company_id)
);

SELECT create_hypertable(
    'subscription_churn_daily',
    'snapshot_date',
    chunk_time_interval => INTERVAL '1 month'
);

-- ============================================================
-- 3. COHORT RETENTION HYPERTABLE
-- ============================================================
CREATE TABLE subscription_cohort_retention (
    cohort_month DATE NOT NULL,  -- First day of cohort month
    months_since_signup INTEGER NOT NULL,  -- 0, 1, 2, 3...
    company_id INTEGER NOT NULL DEFAULT 1,

    -- Cohort Size
    cohort_size INTEGER NOT NULL,
    cohort_mrr DECIMAL(15, 2) NOT NULL,

    -- Retention Metrics
    retained_customers INTEGER NOT NULL,
    retained_mrr DECIMAL(15, 2) NOT NULL,

    -- Retention Rates
    customer_retention_rate DECIMAL(8, 4) GENERATED ALWAYS AS (
        CASE WHEN cohort_size > 0
             THEN (retained_customers::DECIMAL / cohort_size) * 100
             ELSE 0
        END
    ) STORED,

    revenue_retention_rate DECIMAL(8, 4) GENERATED ALWAYS AS (
        CASE WHEN cohort_mrr > 0
             THEN (retained_mrr / cohort_mrr) * 100
             ELSE 0
        END
    ) STORED,

    -- Expansion (can be >100% for revenue retention)
    expanded_customers INTEGER NOT NULL DEFAULT 0,
    expansion_mrr DECIMAL(15, 2) NOT NULL DEFAULT 0,

    -- Gross Revenue Retention (GRR)
    grr DECIMAL(8, 4),

    -- Net Revenue Retention (NRR) - includes expansion
    nrr DECIMAL(8, 4),

    -- Metadata
    calculated_at TIMESTAMPTZ DEFAULT NOW(),

    PRIMARY KEY (cohort_month, months_since_signup, company_id)
);

SELECT create_hypertable(
    'subscription_cohort_retention',
    'cohort_month',
    chunk_time_interval => INTERVAL '1 year'
);

-- ============================================================
-- 4. CUSTOMER LIFETIME VALUE TABLE
-- ============================================================
CREATE TABLE customer_lifetime_value (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES res_partner(id),
    company_id INTEGER NOT NULL DEFAULT 1,

    -- Historical Value
    total_revenue DECIMAL(15, 2) NOT NULL DEFAULT 0,
    total_orders INTEGER NOT NULL DEFAULT 0,
    subscription_tenure_months INTEGER NOT NULL DEFAULT 0,
    first_subscription_date DATE,
    last_activity_date DATE,

    -- Current State
    current_mrr DECIMAL(15, 2) NOT NULL DEFAULT 0,
    active_subscriptions INTEGER NOT NULL DEFAULT 0,

    -- CLV Calculations
    historical_clv DECIMAL(15, 2) NOT NULL DEFAULT 0,
    predicted_clv DECIMAL(15, 2) NOT NULL DEFAULT 0,
    total_clv DECIMAL(15, 2) GENERATED ALWAYS AS (
        historical_clv + predicted_clv
    ) STORED,

    -- CLV Model Inputs
    avg_monthly_revenue DECIMAL(10, 2),
    predicted_lifetime_months INTEGER,
    churn_probability DECIMAL(5, 4),

    -- Customer Segment
    clv_segment VARCHAR(20),  -- 'high', 'medium', 'low', 'at_risk'
    clv_percentile INTEGER,

    -- Acquisition Cost (CAC)
    acquisition_cost DECIMAL(10, 2) DEFAULT 0,
    clv_to_cac_ratio DECIMAL(8, 2),

    -- Metadata
    calculated_at TIMESTAMPTZ DEFAULT NOW(),
    model_version VARCHAR(20),

    UNIQUE(customer_id, company_id)
);

CREATE INDEX idx_clv_customer ON customer_lifetime_value(customer_id);
CREATE INDEX idx_clv_segment ON customer_lifetime_value(clv_segment);
CREATE INDEX idx_clv_percentile ON customer_lifetime_value(clv_percentile);

-- ============================================================
-- 5. SUBSCRIPTION HEALTH SCORE TABLE
-- ============================================================
CREATE TABLE subscription_health_score (
    id SERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL,
    customer_id INTEGER NOT NULL,
    company_id INTEGER NOT NULL DEFAULT 1,
    score_date DATE NOT NULL,

    -- Overall Health Score (0-100)
    health_score INTEGER NOT NULL CHECK (health_score BETWEEN 0 AND 100),
    health_category VARCHAR(20) NOT NULL,  -- 'healthy', 'neutral', 'at_risk', 'critical'

    -- Component Scores (0-100 each)
    payment_score INTEGER NOT NULL DEFAULT 100,
    engagement_score INTEGER NOT NULL DEFAULT 50,
    tenure_score INTEGER NOT NULL DEFAULT 50,
    growth_score INTEGER NOT NULL DEFAULT 50,
    support_score INTEGER NOT NULL DEFAULT 100,

    -- Score Factors (detailed breakdown)
    score_factors JSONB DEFAULT '{}',
    -- Example: {
    --   "payment_failures_30d": 0,
    --   "days_since_last_order": 5,
    --   "subscription_age_months": 8,
    --   "mrr_change_30d": 150,
    --   "support_tickets_30d": 1
    -- }

    -- Risk Indicators
    churn_risk_score DECIMAL(5, 4),  -- 0.0000 to 1.0000
    days_to_predicted_churn INTEGER,
    risk_factors TEXT[],

    -- Recommendations
    recommended_actions JSONB DEFAULT '[]',

    -- Metadata
    calculated_at TIMESTAMPTZ DEFAULT NOW(),
    model_version VARCHAR(20),

    UNIQUE(subscription_id, score_date)
);

CREATE INDEX idx_health_subscription ON subscription_health_score(subscription_id);
CREATE INDEX idx_health_score ON subscription_health_score(health_score);
CREATE INDEX idx_health_category ON subscription_health_score(health_category);
CREATE INDEX idx_health_date ON subscription_health_score(score_date);

-- ============================================================
-- 6. REVENUE FORECAST TABLE
-- ============================================================
CREATE TABLE subscription_revenue_forecast (
    id SERIAL PRIMARY KEY,
    forecast_date DATE NOT NULL,
    target_date DATE NOT NULL,
    company_id INTEGER NOT NULL DEFAULT 1,

    -- Forecast Type
    forecast_type VARCHAR(20) NOT NULL,  -- 'mrr', 'arr', 'revenue'
    forecast_horizon VARCHAR(20) NOT NULL,  -- '30d', '90d', '1y'

    -- Forecasted Values
    forecasted_value DECIMAL(15, 2) NOT NULL,
    lower_bound DECIMAL(15, 2),  -- 95% CI lower
    upper_bound DECIMAL(15, 2),  -- 95% CI upper

    -- Forecast Components
    baseline_value DECIMAL(15, 2),
    growth_component DECIMAL(15, 2),
    churn_component DECIMAL(15, 2),
    seasonality_component DECIMAL(15, 2),

    -- Model Details
    model_type VARCHAR(50),  -- 'arima', 'prophet', 'linear', 'ensemble'
    model_confidence DECIMAL(5, 4),

    -- Assumptions
    assumptions JSONB DEFAULT '{}',

    -- Actual (filled in later for accuracy tracking)
    actual_value DECIMAL(15, 2),
    forecast_error DECIMAL(10, 4),

    -- Metadata
    created_at TIMESTAMPTZ DEFAULT NOW(),

    UNIQUE(forecast_date, target_date, forecast_type, company_id)
);

CREATE INDEX idx_forecast_target ON subscription_revenue_forecast(target_date);
CREATE INDEX idx_forecast_type ON subscription_revenue_forecast(forecast_type);

-- ============================================================
-- 7. ANALYTICS AGGREGATION TABLES (Materialized Views)
-- ============================================================

-- Monthly MRR Summary
CREATE MATERIALIZED VIEW mv_mrr_monthly AS
SELECT
    DATE_TRUNC('month', snapshot_date)::DATE AS month,
    company_id,
    AVG(total_mrr) AS avg_mrr,
    MAX(total_mrr) AS ending_mrr,
    SUM(new_mrr) AS total_new_mrr,
    SUM(churned_mrr) AS total_churned_mrr,
    SUM(expansion_mrr) AS total_expansion_mrr,
    SUM(contraction_mrr) AS total_contraction_mrr,
    AVG(active_subscriptions) AS avg_active_subscriptions,
    AVG(arpu) AS avg_arpu
FROM subscription_mrr_daily
GROUP BY DATE_TRUNC('month', snapshot_date), company_id
ORDER BY month DESC;

CREATE UNIQUE INDEX idx_mv_mrr_monthly ON mv_mrr_monthly(month, company_id);

-- Refresh function
CREATE OR REPLACE FUNCTION refresh_analytics_materialized_views()
RETURNS void AS $$
BEGIN
    REFRESH MATERIALIZED VIEW CONCURRENTLY mv_mrr_monthly;
END;
$$ LANGUAGE plpgsql;

-- ============================================================
-- 8. INDEXES FOR ANALYTICS QUERIES
-- ============================================================

-- MRR Daily indexes
CREATE INDEX idx_mrr_daily_date ON subscription_mrr_daily(snapshot_date DESC);
CREATE INDEX idx_mrr_daily_mrr ON subscription_mrr_daily(total_mrr);

-- Churn Daily indexes
CREATE INDEX idx_churn_daily_date ON subscription_churn_daily(snapshot_date DESC);
CREATE INDEX idx_churn_daily_rate ON subscription_churn_daily(logo_churn_rate);

-- Cohort indexes
CREATE INDEX idx_cohort_month ON subscription_cohort_retention(cohort_month);
CREATE INDEX idx_cohort_retention ON subscription_cohort_retention(customer_retention_rate);
```

### 3.3 Odoo Analytics Models

```python
# -*- coding: utf-8 -*-
"""
Subscription Analytics Models
Milestone 125: Subscription Analytics
Smart Dairy Digital Portal + ERP System
"""

from odoo import models, fields, api
from odoo.exceptions import ValidationError
from datetime import date, datetime, timedelta
from dateutil.relativedelta import relativedelta
from decimal import Decimal
import json
import logging

_logger = logging.getLogger(__name__)


class SubscriptionMRRSnapshot(models.Model):
    """Daily MRR Snapshot for Analytics"""
    _name = 'subscription.mrr.snapshot'
    _description = 'Subscription MRR Daily Snapshot'
    _order = 'snapshot_date desc'
    _rec_name = 'snapshot_date'

    snapshot_date = fields.Date(
        string='Snapshot Date',
        required=True,
        index=True
    )
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    # MRR Components
    total_mrr = fields.Monetary(
        string='Total MRR',
        currency_field='currency_id',
        readonly=True
    )
    new_mrr = fields.Monetary(
        string='New MRR',
        currency_field='currency_id',
        readonly=True
    )
    expansion_mrr = fields.Monetary(
        string='Expansion MRR',
        currency_field='currency_id',
        readonly=True
    )
    contraction_mrr = fields.Monetary(
        string='Contraction MRR',
        currency_field='currency_id',
        readonly=True
    )
    churned_mrr = fields.Monetary(
        string='Churned MRR',
        currency_field='currency_id',
        readonly=True
    )
    reactivation_mrr = fields.Monetary(
        string='Reactivation MRR',
        currency_field='currency_id',
        readonly=True
    )
    net_new_mrr = fields.Monetary(
        string='Net New MRR',
        currency_field='currency_id',
        compute='_compute_net_mrr',
        store=True
    )

    # Subscription Counts
    total_subscriptions = fields.Integer(
        string='Total Subscriptions',
        readonly=True
    )
    active_subscriptions = fields.Integer(
        string='Active Subscriptions',
        readonly=True
    )
    new_subscriptions = fields.Integer(
        string='New Subscriptions',
        readonly=True
    )
    churned_subscriptions = fields.Integer(
        string='Churned Subscriptions',
        readonly=True
    )
    paused_subscriptions = fields.Integer(
        string='Paused Subscriptions',
        readonly=True
    )

    # ARPU
    arpu = fields.Monetary(
        string='ARPU',
        currency_field='currency_id',
        compute='_compute_arpu',
        store=True
    )

    # Segmentation (stored as JSON)
    mrr_by_plan = fields.Text(
        string='MRR by Plan (JSON)',
        readonly=True
    )
    mrr_by_category = fields.Text(
        string='MRR by Category (JSON)',
        readonly=True
    )

    currency_id = fields.Many2one(
        'res.currency',
        related='company_id.currency_id',
        readonly=True
    )

    _sql_constraints = [
        ('unique_snapshot', 'UNIQUE(snapshot_date, company_id)',
         'Only one snapshot per day per company allowed')
    ]

    @api.depends('new_mrr', 'expansion_mrr', 'reactivation_mrr',
                 'contraction_mrr', 'churned_mrr')
    def _compute_net_mrr(self):
        for record in self:
            record.net_new_mrr = (
                record.new_mrr +
                record.expansion_mrr +
                record.reactivation_mrr -
                record.contraction_mrr -
                record.churned_mrr
            )

    @api.depends('total_mrr', 'active_subscriptions')
    def _compute_arpu(self):
        for record in self:
            if record.active_subscriptions > 0:
                record.arpu = record.total_mrr / record.active_subscriptions
            else:
                record.arpu = 0


class SubscriptionChurnMetrics(models.Model):
    """Daily Churn Metrics"""
    _name = 'subscription.churn.metrics'
    _description = 'Subscription Churn Daily Metrics'
    _order = 'snapshot_date desc'

    snapshot_date = fields.Date(
        string='Snapshot Date',
        required=True,
        index=True
    )
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    # Logo Churn
    customers_start = fields.Integer(
        string='Customers (Start)',
        readonly=True
    )
    customers_churned = fields.Integer(
        string='Customers Churned',
        readonly=True
    )
    logo_churn_rate = fields.Float(
        string='Logo Churn Rate (%)',
        compute='_compute_logo_churn',
        store=True,
        digits=(8, 4)
    )

    # Revenue Churn
    mrr_start = fields.Monetary(
        string='MRR (Start)',
        currency_field='currency_id',
        readonly=True
    )
    mrr_churned = fields.Monetary(
        string='MRR Churned',
        currency_field='currency_id',
        readonly=True
    )
    mrr_expansion = fields.Monetary(
        string='MRR Expansion',
        currency_field='currency_id',
        readonly=True
    )
    mrr_contraction = fields.Monetary(
        string='MRR Contraction',
        currency_field='currency_id',
        readonly=True
    )

    revenue_churn_rate = fields.Float(
        string='Revenue Churn Rate (%)',
        compute='_compute_revenue_churn',
        store=True,
        digits=(8, 4)
    )
    net_revenue_churn_rate = fields.Float(
        string='Net Revenue Churn Rate (%)',
        compute='_compute_net_revenue_churn',
        store=True,
        digits=(8, 4)
    )

    # Churn Breakdown
    churn_by_reason = fields.Text(
        string='Churn by Reason (JSON)',
        readonly=True
    )

    # Reactivations
    reactivated_customers = fields.Integer(
        string='Reactivated Customers',
        readonly=True
    )
    reactivated_mrr = fields.Monetary(
        string='Reactivated MRR',
        currency_field='currency_id',
        readonly=True
    )

    currency_id = fields.Many2one(
        'res.currency',
        related='company_id.currency_id',
        readonly=True
    )

    @api.depends('customers_start', 'customers_churned')
    def _compute_logo_churn(self):
        for record in self:
            if record.customers_start > 0:
                record.logo_churn_rate = (
                    record.customers_churned / record.customers_start
                ) * 100
            else:
                record.logo_churn_rate = 0

    @api.depends('mrr_start', 'mrr_churned')
    def _compute_revenue_churn(self):
        for record in self:
            if record.mrr_start > 0:
                record.revenue_churn_rate = (
                    record.mrr_churned / record.mrr_start
                ) * 100
            else:
                record.revenue_churn_rate = 0

    @api.depends('mrr_start', 'mrr_churned', 'mrr_expansion', 'mrr_contraction')
    def _compute_net_revenue_churn(self):
        for record in self:
            if record.mrr_start > 0:
                net_churn = (
                    record.mrr_churned +
                    record.mrr_contraction -
                    record.mrr_expansion
                )
                record.net_revenue_churn_rate = (net_churn / record.mrr_start) * 100
            else:
                record.net_revenue_churn_rate = 0


class CohortRetention(models.Model):
    """Cohort Retention Analysis"""
    _name = 'subscription.cohort.retention'
    _description = 'Subscription Cohort Retention'
    _order = 'cohort_month desc, months_since_signup'

    cohort_month = fields.Date(
        string='Cohort Month',
        required=True,
        help='First day of the cohort month'
    )
    months_since_signup = fields.Integer(
        string='Months Since Signup',
        required=True
    )
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    # Cohort Size
    cohort_size = fields.Integer(
        string='Cohort Size (Customers)',
        readonly=True
    )
    cohort_mrr = fields.Monetary(
        string='Cohort MRR',
        currency_field='currency_id',
        readonly=True
    )

    # Retention
    retained_customers = fields.Integer(
        string='Retained Customers',
        readonly=True
    )
    retained_mrr = fields.Monetary(
        string='Retained MRR',
        currency_field='currency_id',
        readonly=True
    )

    # Retention Rates
    customer_retention_rate = fields.Float(
        string='Customer Retention Rate (%)',
        compute='_compute_retention_rates',
        store=True,
        digits=(8, 2)
    )
    revenue_retention_rate = fields.Float(
        string='Revenue Retention Rate (%)',
        compute='_compute_retention_rates',
        store=True,
        digits=(8, 2)
    )

    # Net Revenue Retention
    expansion_mrr = fields.Monetary(
        string='Expansion MRR',
        currency_field='currency_id',
        readonly=True
    )
    nrr = fields.Float(
        string='Net Revenue Retention (%)',
        compute='_compute_nrr',
        store=True,
        digits=(8, 2)
    )

    currency_id = fields.Many2one(
        'res.currency',
        related='company_id.currency_id',
        readonly=True
    )

    @api.depends('cohort_size', 'retained_customers', 'cohort_mrr', 'retained_mrr')
    def _compute_retention_rates(self):
        for record in self:
            if record.cohort_size > 0:
                record.customer_retention_rate = (
                    record.retained_customers / record.cohort_size
                ) * 100
            else:
                record.customer_retention_rate = 0

            if record.cohort_mrr > 0:
                record.revenue_retention_rate = (
                    record.retained_mrr / record.cohort_mrr
                ) * 100
            else:
                record.revenue_retention_rate = 0

    @api.depends('cohort_mrr', 'retained_mrr', 'expansion_mrr')
    def _compute_nrr(self):
        for record in self:
            if record.cohort_mrr > 0:
                record.nrr = (
                    (record.retained_mrr + record.expansion_mrr) / record.cohort_mrr
                ) * 100
            else:
                record.nrr = 0


class CustomerLifetimeValue(models.Model):
    """Customer Lifetime Value Calculation"""
    _name = 'customer.lifetime.value'
    _description = 'Customer Lifetime Value'
    _order = 'total_clv desc'

    customer_id = fields.Many2one(
        'res.partner',
        string='Customer',
        required=True,
        ondelete='cascade',
        index=True
    )
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    # Historical Value
    total_revenue = fields.Monetary(
        string='Total Revenue',
        currency_field='currency_id',
        readonly=True
    )
    total_orders = fields.Integer(
        string='Total Orders',
        readonly=True
    )
    subscription_tenure_months = fields.Integer(
        string='Tenure (Months)',
        readonly=True
    )
    first_subscription_date = fields.Date(
        string='First Subscription',
        readonly=True
    )

    # Current State
    current_mrr = fields.Monetary(
        string='Current MRR',
        currency_field='currency_id',
        readonly=True
    )
    active_subscriptions = fields.Integer(
        string='Active Subscriptions',
        readonly=True
    )

    # CLV Calculations
    historical_clv = fields.Monetary(
        string='Historical CLV',
        currency_field='currency_id',
        readonly=True
    )
    predicted_clv = fields.Monetary(
        string='Predicted CLV',
        currency_field='currency_id',
        readonly=True
    )
    total_clv = fields.Monetary(
        string='Total CLV',
        currency_field='currency_id',
        compute='_compute_total_clv',
        store=True
    )

    # CLV Inputs
    avg_monthly_revenue = fields.Monetary(
        string='Avg Monthly Revenue',
        currency_field='currency_id',
        readonly=True
    )
    predicted_lifetime_months = fields.Integer(
        string='Predicted Lifetime (Months)',
        readonly=True
    )
    churn_probability = fields.Float(
        string='Churn Probability',
        digits=(5, 4),
        readonly=True
    )

    # Segmentation
    clv_segment = fields.Selection([
        ('high', 'High Value'),
        ('medium', 'Medium Value'),
        ('low', 'Low Value'),
        ('at_risk', 'At Risk')
    ], string='CLV Segment', readonly=True, index=True)

    clv_percentile = fields.Integer(
        string='CLV Percentile',
        readonly=True
    )

    # CAC
    acquisition_cost = fields.Monetary(
        string='Acquisition Cost',
        currency_field='currency_id'
    )
    clv_to_cac_ratio = fields.Float(
        string='CLV:CAC Ratio',
        compute='_compute_clv_cac_ratio',
        store=True,
        digits=(8, 2)
    )

    # Metadata
    calculated_at = fields.Datetime(
        string='Last Calculated',
        readonly=True
    )
    model_version = fields.Char(
        string='Model Version',
        readonly=True
    )

    currency_id = fields.Many2one(
        'res.currency',
        related='company_id.currency_id',
        readonly=True
    )

    _sql_constraints = [
        ('unique_customer', 'UNIQUE(customer_id, company_id)',
         'Only one CLV record per customer per company')
    ]

    @api.depends('historical_clv', 'predicted_clv')
    def _compute_total_clv(self):
        for record in self:
            record.total_clv = record.historical_clv + record.predicted_clv

    @api.depends('total_clv', 'acquisition_cost')
    def _compute_clv_cac_ratio(self):
        for record in self:
            if record.acquisition_cost > 0:
                record.clv_to_cac_ratio = record.total_clv / record.acquisition_cost
            else:
                record.clv_to_cac_ratio = 0


class SubscriptionHealthScore(models.Model):
    """Subscription Health Score"""
    _name = 'subscription.health.score'
    _description = 'Subscription Health Score'
    _order = 'score_date desc, health_score'

    subscription_id = fields.Many2one(
        'customer.subscription',
        string='Subscription',
        required=True,
        ondelete='cascade',
        index=True
    )
    customer_id = fields.Many2one(
        'res.partner',
        string='Customer',
        related='subscription_id.customer_id',
        store=True
    )
    score_date = fields.Date(
        string='Score Date',
        required=True,
        index=True
    )
    company_id = fields.Many2one(
        'res.company',
        string='Company',
        required=True,
        default=lambda self: self.env.company
    )

    # Overall Score
    health_score = fields.Integer(
        string='Health Score',
        required=True,
        help='0-100 score'
    )
    health_category = fields.Selection([
        ('healthy', 'Healthy'),
        ('neutral', 'Neutral'),
        ('at_risk', 'At Risk'),
        ('critical', 'Critical')
    ], string='Health Category', required=True, index=True)

    # Component Scores
    payment_score = fields.Integer(
        string='Payment Score',
        default=100
    )
    engagement_score = fields.Integer(
        string='Engagement Score',
        default=50
    )
    tenure_score = fields.Integer(
        string='Tenure Score',
        default=50
    )
    growth_score = fields.Integer(
        string='Growth Score',
        default=50
    )
    support_score = fields.Integer(
        string='Support Score',
        default=100
    )

    # Risk Assessment
    churn_risk_score = fields.Float(
        string='Churn Risk Score',
        digits=(5, 4),
        help='0.0 to 1.0'
    )
    days_to_predicted_churn = fields.Integer(
        string='Days to Predicted Churn'
    )
    risk_factors = fields.Text(
        string='Risk Factors (JSON)'
    )

    # Recommendations
    recommended_actions = fields.Text(
        string='Recommended Actions (JSON)'
    )

    # Metadata
    calculated_at = fields.Datetime(
        string='Calculated At',
        readonly=True,
        default=fields.Datetime.now
    )
    model_version = fields.Char(
        string='Model Version'
    )

    _sql_constraints = [
        ('unique_score', 'UNIQUE(subscription_id, score_date)',
         'Only one health score per subscription per day')
    ]

    @api.model
    def get_health_category(self, score):
        """Determine health category from score"""
        if score >= 80:
            return 'healthy'
        elif score >= 60:
            return 'neutral'
        elif score >= 40:
            return 'at_risk'
        else:
            return 'critical'
```

### 3.4 MRR Calculation Engine

```python
# -*- coding: utf-8 -*-
"""
MRR Calculation Engine
Milestone 125: Subscription Analytics
"""

from odoo import models, api
from datetime import date, timedelta
from dateutil.relativedelta import relativedelta
from collections import defaultdict
import logging

_logger = logging.getLogger(__name__)


class MRRCalculationEngine(models.AbstractModel):
    """Engine for calculating MRR metrics"""
    _name = 'mrr.calculation.engine'
    _description = 'MRR Calculation Engine'

    @api.model
    def calculate_daily_mrr(self, snapshot_date=None):
        """
        Calculate MRR snapshot for a given date

        MRR Components:
        - Total MRR: Sum of all active subscription MRR
        - New MRR: MRR from subscriptions started on this date
        - Expansion MRR: MRR increase from upgrades
        - Contraction MRR: MRR decrease from downgrades
        - Churned MRR: MRR lost from cancellations
        - Reactivation MRR: MRR from reactivated subscriptions
        """
        if not snapshot_date:
            snapshot_date = date.today()

        company = self.env.company
        previous_date = snapshot_date - timedelta(days=1)

        # Get subscription model
        Subscription = self.env['customer.subscription']

        # Calculate Total MRR (active subscriptions)
        active_subs = Subscription.search([
            ('status', 'in', ['active', 'paused']),
            ('start_date', '<=', snapshot_date),
            '|',
            ('end_date', '=', False),
            ('end_date', '>=', snapshot_date),
            ('company_id', '=', company.id)
        ])

        total_mrr = sum(sub.mrr for sub in active_subs)
        active_count = len(active_subs.filtered(lambda s: s.status == 'active'))
        paused_count = len(active_subs.filtered(lambda s: s.status == 'paused'))

        # Calculate New MRR (subscriptions started today)
        new_subs = Subscription.search([
            ('start_date', '=', snapshot_date),
            ('status', 'in', ['active', 'paused']),
            ('company_id', '=', company.id)
        ])
        new_mrr = sum(sub.mrr for sub in new_subs)
        new_count = len(new_subs)

        # Calculate Churned MRR (subscriptions ended today)
        churned_subs = Subscription.search([
            ('end_date', '=', snapshot_date),
            ('status', '=', 'cancelled'),
            ('company_id', '=', company.id)
        ])
        churned_mrr = sum(sub.mrr for sub in churned_subs)
        churned_count = len(churned_subs)

        # Calculate Expansion/Contraction MRR from modifications
        ModificationLog = self.env['subscription.modification.log']
        modifications = ModificationLog.search([
            ('modification_date', '=', snapshot_date),
            ('modification_type', 'in', ['upgrade', 'downgrade', 'quantity_change']),
            ('company_id', '=', company.id)
        ])

        expansion_mrr = 0
        contraction_mrr = 0

        for mod in modifications:
            mrr_change = mod.new_mrr - mod.previous_mrr
            if mrr_change > 0:
                expansion_mrr += mrr_change
            else:
                contraction_mrr += abs(mrr_change)

        # Calculate Reactivation MRR
        reactivated_subs = Subscription.search([
            ('reactivation_date', '=', snapshot_date),
            ('status', '=', 'active'),
            ('company_id', '=', company.id)
        ])
        reactivation_mrr = sum(sub.mrr for sub in reactivated_subs)

        # Calculate MRR by Plan
        mrr_by_plan = defaultdict(float)
        for sub in active_subs:
            plan_name = sub.plan_id.name if sub.plan_id else 'Unknown'
            mrr_by_plan[plan_name] += sub.mrr

        # Calculate MRR by Product Category
        mrr_by_category = defaultdict(float)
        for sub in active_subs:
            for line in sub.line_ids:
                category = line.product_id.categ_id.name if line.product_id.categ_id else 'Uncategorized'
                mrr_by_category[category] += line.mrr

        # Create or update snapshot
        MRRSnapshot = self.env['subscription.mrr.snapshot']
        existing = MRRSnapshot.search([
            ('snapshot_date', '=', snapshot_date),
            ('company_id', '=', company.id)
        ], limit=1)

        values = {
            'snapshot_date': snapshot_date,
            'company_id': company.id,
            'total_mrr': total_mrr,
            'new_mrr': new_mrr,
            'expansion_mrr': expansion_mrr,
            'contraction_mrr': contraction_mrr,
            'churned_mrr': churned_mrr,
            'reactivation_mrr': reactivation_mrr,
            'total_subscriptions': len(active_subs),
            'active_subscriptions': active_count,
            'new_subscriptions': new_count,
            'churned_subscriptions': churned_count,
            'paused_subscriptions': paused_count,
            'mrr_by_plan': str(dict(mrr_by_plan)),
            'mrr_by_category': str(dict(mrr_by_category)),
        }

        if existing:
            existing.write(values)
            return existing
        else:
            return MRRSnapshot.create(values)

    @api.model
    def calculate_arr(self, as_of_date=None):
        """Calculate Annual Recurring Revenue (MRR × 12)"""
        if not as_of_date:
            as_of_date = date.today()

        snapshot = self.env['subscription.mrr.snapshot'].search([
            ('snapshot_date', '<=', as_of_date),
            ('company_id', '=', self.env.company.id)
        ], order='snapshot_date desc', limit=1)

        if snapshot:
            return snapshot.total_mrr * 12
        return 0

    @api.model
    def calculate_mrr_growth_rate(self, period_days=30):
        """Calculate MRR growth rate over period"""
        today = date.today()
        period_start = today - timedelta(days=period_days)

        current = self.env['subscription.mrr.snapshot'].search([
            ('snapshot_date', '<=', today),
            ('company_id', '=', self.env.company.id)
        ], order='snapshot_date desc', limit=1)

        previous = self.env['subscription.mrr.snapshot'].search([
            ('snapshot_date', '<=', period_start),
            ('company_id', '=', self.env.company.id)
        ], order='snapshot_date desc', limit=1)

        if current and previous and previous.total_mrr > 0:
            return ((current.total_mrr - previous.total_mrr) / previous.total_mrr) * 100
        return 0

    @api.model
    def get_mrr_trend(self, days=90):
        """Get MRR trend data for charting"""
        end_date = date.today()
        start_date = end_date - timedelta(days=days)

        snapshots = self.env['subscription.mrr.snapshot'].search([
            ('snapshot_date', '>=', start_date),
            ('snapshot_date', '<=', end_date),
            ('company_id', '=', self.env.company.id)
        ], order='snapshot_date asc')

        return [{
            'date': s.snapshot_date.isoformat(),
            'total_mrr': s.total_mrr,
            'new_mrr': s.new_mrr,
            'churned_mrr': s.churned_mrr,
            'net_new_mrr': s.net_new_mrr,
            'active_subscriptions': s.active_subscriptions,
            'arpu': s.arpu
        } for s in snapshots]
```

### 3.5 Churn Analysis Engine

```python
# -*- coding: utf-8 -*-
"""
Churn Analysis Engine
Milestone 125: Subscription Analytics
"""

from odoo import models, api
from datetime import date, timedelta
from dateutil.relativedelta import relativedelta
from collections import defaultdict
import logging

_logger = logging.getLogger(__name__)


class ChurnAnalysisEngine(models.AbstractModel):
    """Engine for calculating churn metrics"""
    _name = 'churn.analysis.engine'
    _description = 'Churn Analysis Engine'

    @api.model
    def calculate_daily_churn(self, snapshot_date=None):
        """
        Calculate churn metrics for a given date

        Logo Churn: Percentage of customers lost
        Revenue Churn: Percentage of MRR lost
        Net Revenue Churn: Revenue churn minus expansion
        """
        if not snapshot_date:
            snapshot_date = date.today()

        company = self.env.company
        period_start = snapshot_date - timedelta(days=30)

        Subscription = self.env['customer.subscription']

        # Customers at start of period
        customers_start = Subscription.search_count([
            ('status', 'in', ['active', 'paused']),
            ('start_date', '<', period_start),
            '|',
            ('end_date', '=', False),
            ('end_date', '>=', period_start),
            ('company_id', '=', company.id)
        ])

        # MRR at start of period
        subs_at_start = Subscription.search([
            ('status', 'in', ['active', 'paused']),
            ('start_date', '<', period_start),
            '|',
            ('end_date', '=', False),
            ('end_date', '>=', period_start),
            ('company_id', '=', company.id)
        ])
        mrr_start = sum(s.mrr for s in subs_at_start)

        # Churned during period
        churned_subs = Subscription.search([
            ('end_date', '>=', period_start),
            ('end_date', '<=', snapshot_date),
            ('status', '=', 'cancelled'),
            ('company_id', '=', company.id)
        ])
        customers_churned = len(churned_subs)
        mrr_churned = sum(s.mrr for s in churned_subs)

        # Churn by reason
        churn_by_reason = defaultdict(int)
        Cancellation = self.env['subscription.cancellation']
        cancellations = Cancellation.search([
            ('cancellation_date', '>=', period_start),
            ('cancellation_date', '<=', snapshot_date),
            ('company_id', '=', company.id)
        ])
        for cancel in cancellations:
            reason = cancel.cancellation_reason or 'unknown'
            churn_by_reason[reason] += 1

        # Expansion and contraction during period
        ModificationLog = self.env['subscription.modification.log']
        modifications = ModificationLog.search([
            ('modification_date', '>=', period_start),
            ('modification_date', '<=', snapshot_date),
            ('company_id', '=', company.id)
        ])

        mrr_expansion = 0
        mrr_contraction = 0
        for mod in modifications:
            mrr_change = mod.new_mrr - mod.previous_mrr
            if mrr_change > 0:
                mrr_expansion += mrr_change
            elif mrr_change < 0:
                mrr_contraction += abs(mrr_change)

        # Reactivations
        reactivated = Subscription.search([
            ('reactivation_date', '>=', period_start),
            ('reactivation_date', '<=', snapshot_date),
            ('status', '=', 'active'),
            ('company_id', '=', company.id)
        ])
        reactivated_customers = len(reactivated)
        reactivated_mrr = sum(s.mrr for s in reactivated)

        # Create metrics record
        ChurnMetrics = self.env['subscription.churn.metrics']
        existing = ChurnMetrics.search([
            ('snapshot_date', '=', snapshot_date),
            ('company_id', '=', company.id)
        ], limit=1)

        values = {
            'snapshot_date': snapshot_date,
            'company_id': company.id,
            'customers_start': customers_start,
            'customers_churned': customers_churned,
            'mrr_start': mrr_start,
            'mrr_churned': mrr_churned,
            'mrr_expansion': mrr_expansion,
            'mrr_contraction': mrr_contraction,
            'churn_by_reason': str(dict(churn_by_reason)),
            'reactivated_customers': reactivated_customers,
            'reactivated_mrr': reactivated_mrr,
        }

        if existing:
            existing.write(values)
            return existing
        else:
            return ChurnMetrics.create(values)

    @api.model
    def calculate_cohort_retention(self, cohort_month, months_to_track=12):
        """
        Calculate retention metrics for a specific cohort

        Args:
            cohort_month: First day of the cohort month (date)
            months_to_track: How many months to track retention
        """
        company = self.env.company
        Subscription = self.env['customer.subscription']
        CohortRetention = self.env['subscription.cohort.retention']

        # Find customers who started in cohort month
        cohort_end = cohort_month + relativedelta(months=1) - timedelta(days=1)

        cohort_subs = Subscription.search([
            ('start_date', '>=', cohort_month),
            ('start_date', '<=', cohort_end),
            ('company_id', '=', company.id)
        ])

        if not cohort_subs:
            return []

        cohort_size = len(cohort_subs)
        cohort_mrr = sum(s.mrr for s in cohort_subs)
        cohort_customer_ids = cohort_subs.mapped('customer_id').ids

        results = []

        for month_offset in range(months_to_track + 1):
            check_date = cohort_month + relativedelta(months=month_offset)

            if check_date > date.today():
                break

            # Check which customers are still active
            active_subs = Subscription.search([
                ('customer_id', 'in', cohort_customer_ids),
                ('status', 'in', ['active', 'paused']),
                ('start_date', '<=', check_date),
                '|',
                ('end_date', '=', False),
                ('end_date', '>=', check_date),
                ('company_id', '=', company.id)
            ])

            retained_customers = len(set(active_subs.mapped('customer_id').ids))
            retained_mrr = sum(s.mrr for s in active_subs)

            # Calculate expansion MRR
            expansion_mrr = max(0, retained_mrr - cohort_mrr)

            # Create or update cohort record
            existing = CohortRetention.search([
                ('cohort_month', '=', cohort_month),
                ('months_since_signup', '=', month_offset),
                ('company_id', '=', company.id)
            ], limit=1)

            values = {
                'cohort_month': cohort_month,
                'months_since_signup': month_offset,
                'company_id': company.id,
                'cohort_size': cohort_size,
                'cohort_mrr': cohort_mrr,
                'retained_customers': retained_customers,
                'retained_mrr': retained_mrr,
                'expansion_mrr': expansion_mrr,
            }

            if existing:
                existing.write(values)
                results.append(existing)
            else:
                results.append(CohortRetention.create(values))

        return results

    @api.model
    def get_retention_matrix(self, months=12):
        """Get cohort retention matrix for visualization"""
        today = date.today()
        start_month = (today - relativedelta(months=months)).replace(day=1)

        matrix = []
        current_month = start_month

        while current_month <= today.replace(day=1):
            cohort_data = self.env['subscription.cohort.retention'].search([
                ('cohort_month', '=', current_month),
                ('company_id', '=', self.env.company.id)
            ], order='months_since_signup asc')

            if cohort_data:
                row = {
                    'cohort': current_month.strftime('%Y-%m'),
                    'size': cohort_data[0].cohort_size,
                    'retention': [c.customer_retention_rate for c in cohort_data]
                }
                matrix.append(row)

            current_month += relativedelta(months=1)

        return matrix
```

### 3.6 CLV Calculation Engine

```python
# -*- coding: utf-8 -*-
"""
Customer Lifetime Value Calculation Engine
Milestone 125: Subscription Analytics
"""

from odoo import models, api
from datetime import date, timedelta
from dateutil.relativedelta import relativedelta
import numpy as np
import logging

_logger = logging.getLogger(__name__)


class CLVCalculationEngine(models.AbstractModel):
    """Engine for calculating Customer Lifetime Value"""
    _name = 'clv.calculation.engine'
    _description = 'CLV Calculation Engine'

    # CLV Model Configuration
    DEFAULT_DISCOUNT_RATE = 0.10  # 10% annual discount rate
    DEFAULT_MARGIN = 0.70  # 70% gross margin

    @api.model
    def calculate_customer_clv(self, customer_id):
        """
        Calculate CLV for a specific customer

        CLV Formula (Simple):
        CLV = (ARPU × Gross Margin) / Churn Rate

        CLV Formula (Predictive):
        CLV = Historical Revenue + Σ(Predicted Monthly Revenue × (1-Churn)^n / (1+d)^n)
        where d = discount rate, n = month
        """
        company = self.env.company
        customer = self.env['res.partner'].browse(customer_id)

        if not customer.exists():
            return None

        Subscription = self.env['customer.subscription']

        # Get customer's subscriptions
        all_subs = Subscription.search([
            ('customer_id', '=', customer_id),
            ('company_id', '=', company.id)
        ])

        if not all_subs:
            return None

        active_subs = all_subs.filtered(lambda s: s.status in ['active', 'paused'])

        # Historical metrics
        first_sub = min(all_subs, key=lambda s: s.start_date)
        first_subscription_date = first_sub.start_date

        tenure_months = relativedelta(date.today(), first_subscription_date).months + \
                       relativedelta(date.today(), first_subscription_date).years * 12

        # Calculate total historical revenue
        Invoice = self.env['account.move']
        invoices = Invoice.search([
            ('partner_id', '=', customer_id),
            ('move_type', '=', 'out_invoice'),
            ('state', '=', 'posted'),
            ('company_id', '=', company.id)
        ])
        total_revenue = sum(inv.amount_total for inv in invoices)
        total_orders = len(invoices)

        # Current MRR
        current_mrr = sum(s.mrr for s in active_subs)
        active_count = len(active_subs)

        # Historical CLV (actual revenue earned)
        historical_clv = total_revenue

        # Calculate average monthly revenue
        if tenure_months > 0:
            avg_monthly_revenue = total_revenue / tenure_months
        else:
            avg_monthly_revenue = current_mrr

        # Get customer's churn probability
        churn_prob = self._estimate_churn_probability(customer_id)

        # Predicted lifetime in months
        if churn_prob > 0:
            predicted_lifetime = min(int(1 / churn_prob), 60)  # Cap at 5 years
        else:
            predicted_lifetime = 36  # Default 3 years

        # Calculate predicted CLV using DCF model
        predicted_clv = self._calculate_dcf_clv(
            monthly_revenue=current_mrr,
            churn_rate=churn_prob,
            months=predicted_lifetime,
            margin=self.DEFAULT_MARGIN,
            discount_rate=self.DEFAULT_DISCOUNT_RATE
        )

        # Determine CLV segment
        total_clv = historical_clv + predicted_clv
        clv_segment = self._determine_clv_segment(total_clv)

        # Calculate CLV percentile
        clv_percentile = self._calculate_clv_percentile(total_clv)

        # Create or update CLV record
        CLV = self.env['customer.lifetime.value']
        existing = CLV.search([
            ('customer_id', '=', customer_id),
            ('company_id', '=', company.id)
        ], limit=1)

        values = {
            'customer_id': customer_id,
            'company_id': company.id,
            'total_revenue': total_revenue,
            'total_orders': total_orders,
            'subscription_tenure_months': tenure_months,
            'first_subscription_date': first_subscription_date,
            'current_mrr': current_mrr,
            'active_subscriptions': active_count,
            'historical_clv': historical_clv,
            'predicted_clv': predicted_clv,
            'avg_monthly_revenue': avg_monthly_revenue,
            'predicted_lifetime_months': predicted_lifetime,
            'churn_probability': churn_prob,
            'clv_segment': clv_segment,
            'clv_percentile': clv_percentile,
            'calculated_at': date.today(),
            'model_version': 'v1.0'
        }

        if existing:
            existing.write(values)
            return existing
        else:
            return CLV.create(values)

    def _calculate_dcf_clv(self, monthly_revenue, churn_rate, months,
                           margin, discount_rate):
        """
        Calculate CLV using Discounted Cash Flow model

        CLV = Σ (Monthly Revenue × Margin × Survival Rate) / (1 + Monthly Discount)^n
        """
        monthly_discount = discount_rate / 12
        clv = 0

        for month in range(1, months + 1):
            # Survival probability at month n
            survival_rate = (1 - churn_rate) ** month

            # Discounted value
            discount_factor = (1 + monthly_discount) ** month

            # Monthly contribution
            monthly_value = (monthly_revenue * margin * survival_rate) / discount_factor
            clv += monthly_value

        return clv

    def _estimate_churn_probability(self, customer_id):
        """Estimate monthly churn probability for customer"""
        # Get customer's health score if available
        HealthScore = self.env['subscription.health.score']
        latest_score = HealthScore.search([
            ('customer_id', '=', customer_id),
            ('company_id', '=', self.env.company.id)
        ], order='score_date desc', limit=1)

        if latest_score and latest_score.churn_risk_score:
            return latest_score.churn_risk_score

        # Fall back to company average
        ChurnMetrics = self.env['subscription.churn.metrics']
        latest_churn = ChurnMetrics.search([
            ('company_id', '=', self.env.company.id)
        ], order='snapshot_date desc', limit=1)

        if latest_churn:
            return latest_churn.logo_churn_rate / 100

        # Default churn rate
        return 0.05  # 5% monthly

    def _determine_clv_segment(self, clv):
        """Determine CLV segment based on value thresholds"""
        # Thresholds in BDT (adjust based on business)
        if clv >= 100000:
            return 'high'
        elif clv >= 30000:
            return 'medium'
        elif clv >= 10000:
            return 'low'
        else:
            return 'at_risk'

    def _calculate_clv_percentile(self, clv):
        """Calculate CLV percentile among all customers"""
        CLV = self.env['customer.lifetime.value']
        all_clvs = CLV.search([
            ('company_id', '=', self.env.company.id)
        ])

        if not all_clvs:
            return 50

        clv_values = sorted([c.total_clv for c in all_clvs])
        count_below = sum(1 for v in clv_values if v < clv)

        return int((count_below / len(clv_values)) * 100)

    @api.model
    def calculate_all_clv(self):
        """Batch calculate CLV for all customers with subscriptions"""
        Subscription = self.env['customer.subscription']
        customer_ids = Subscription.search([
            ('company_id', '=', self.env.company.id)
        ]).mapped('customer_id').ids

        customer_ids = list(set(customer_ids))

        _logger.info(f"Calculating CLV for {len(customer_ids)} customers")

        for customer_id in customer_ids:
            try:
                self.calculate_customer_clv(customer_id)
            except Exception as e:
                _logger.error(f"Error calculating CLV for customer {customer_id}: {e}")

        return len(customer_ids)
```

### 3.7 Health Score Engine

```python
# -*- coding: utf-8 -*-
"""
Subscription Health Score Engine
Milestone 125: Subscription Analytics
"""

from odoo import models, api
from datetime import date, timedelta
import json
import logging

_logger = logging.getLogger(__name__)


class HealthScoreEngine(models.AbstractModel):
    """Engine for calculating subscription health scores"""
    _name = 'health.score.engine'
    _description = 'Subscription Health Score Engine'

    # Weight configuration for health score components
    WEIGHTS = {
        'payment': 0.30,
        'engagement': 0.25,
        'tenure': 0.15,
        'growth': 0.15,
        'support': 0.15
    }

    @api.model
    def calculate_subscription_health(self, subscription_id):
        """
        Calculate health score for a subscription

        Components:
        - Payment Score: Based on payment history
        - Engagement Score: Based on activity/usage
        - Tenure Score: Based on subscription age
        - Growth Score: Based on MRR changes
        - Support Score: Based on support ticket history
        """
        subscription = self.env['customer.subscription'].browse(subscription_id)
        if not subscription.exists():
            return None

        company = self.env.company
        score_date = date.today()

        # Calculate component scores
        payment_score = self._calculate_payment_score(subscription)
        engagement_score = self._calculate_engagement_score(subscription)
        tenure_score = self._calculate_tenure_score(subscription)
        growth_score = self._calculate_growth_score(subscription)
        support_score = self._calculate_support_score(subscription)

        # Calculate weighted overall score
        health_score = int(
            payment_score * self.WEIGHTS['payment'] +
            engagement_score * self.WEIGHTS['engagement'] +
            tenure_score * self.WEIGHTS['tenure'] +
            growth_score * self.WEIGHTS['growth'] +
            support_score * self.WEIGHTS['support']
        )

        # Determine health category
        health_category = self.env['subscription.health.score'].get_health_category(health_score)

        # Calculate churn risk
        churn_risk = self._calculate_churn_risk(
            payment_score, engagement_score, tenure_score, growth_score, support_score
        )

        # Generate risk factors
        risk_factors = self._identify_risk_factors(
            payment_score, engagement_score, tenure_score, growth_score, support_score
        )

        # Generate recommendations
        recommendations = self._generate_recommendations(risk_factors, health_category)

        # Score factors for transparency
        score_factors = {
            'payment_failures_30d': self._get_payment_failures(subscription, 30),
            'days_since_last_activity': self._get_days_since_activity(subscription),
            'subscription_age_months': self._get_tenure_months(subscription),
            'mrr_change_30d': self._get_mrr_change(subscription, 30),
            'support_tickets_30d': self._get_support_tickets(subscription, 30)
        }

        # Create or update health score record
        HealthScore = self.env['subscription.health.score']
        existing = HealthScore.search([
            ('subscription_id', '=', subscription_id),
            ('score_date', '=', score_date)
        ], limit=1)

        values = {
            'subscription_id': subscription_id,
            'score_date': score_date,
            'company_id': company.id,
            'health_score': health_score,
            'health_category': health_category,
            'payment_score': payment_score,
            'engagement_score': engagement_score,
            'tenure_score': tenure_score,
            'growth_score': growth_score,
            'support_score': support_score,
            'churn_risk_score': churn_risk,
            'risk_factors': json.dumps(risk_factors),
            'recommended_actions': json.dumps(recommendations),
            'calculated_at': date.today(),
            'model_version': 'v1.0'
        }

        if existing:
            existing.write(values)
            return existing
        else:
            return HealthScore.create(values)

    def _calculate_payment_score(self, subscription):
        """Score based on payment history (0-100)"""
        failures_30d = self._get_payment_failures(subscription, 30)
        failures_90d = self._get_payment_failures(subscription, 90)

        # Start with perfect score
        score = 100

        # Deduct for recent failures
        score -= failures_30d * 15
        score -= (failures_90d - failures_30d) * 5

        return max(0, min(100, score))

    def _calculate_engagement_score(self, subscription):
        """Score based on customer engagement (0-100)"""
        days_since_activity = self._get_days_since_activity(subscription)

        if days_since_activity <= 7:
            return 100
        elif days_since_activity <= 14:
            return 80
        elif days_since_activity <= 30:
            return 60
        elif days_since_activity <= 60:
            return 40
        else:
            return 20

    def _calculate_tenure_score(self, subscription):
        """Score based on subscription tenure (0-100)"""
        tenure_months = self._get_tenure_months(subscription)

        if tenure_months >= 24:
            return 100
        elif tenure_months >= 12:
            return 80
        elif tenure_months >= 6:
            return 60
        elif tenure_months >= 3:
            return 40
        else:
            return 20

    def _calculate_growth_score(self, subscription):
        """Score based on MRR growth (0-100)"""
        mrr_change = self._get_mrr_change(subscription, 90)

        if mrr_change > 0:
            return min(100, 70 + int(mrr_change / 100))  # Bonus for growth
        elif mrr_change == 0:
            return 50
        else:
            return max(0, 50 + int(mrr_change / 50))  # Penalty for contraction

    def _calculate_support_score(self, subscription):
        """Score based on support ticket history (0-100)"""
        tickets_30d = self._get_support_tickets(subscription, 30)

        if tickets_30d == 0:
            return 100
        elif tickets_30d <= 2:
            return 80
        elif tickets_30d <= 5:
            return 50
        else:
            return 30

    def _calculate_churn_risk(self, payment, engagement, tenure, growth, support):
        """Calculate churn risk score (0.0 to 1.0)"""
        # Simple logistic-style risk calculation
        # Lower scores = higher risk
        avg_score = (payment + engagement + tenure + growth + support) / 5

        # Convert to risk (inverse of score)
        risk = (100 - avg_score) / 100

        # Apply weights for critical factors
        if payment < 50:
            risk = min(1.0, risk * 1.5)
        if engagement < 40:
            risk = min(1.0, risk * 1.3)

        return round(risk, 4)

    def _identify_risk_factors(self, payment, engagement, tenure, growth, support):
        """Identify specific risk factors"""
        factors = []

        if payment < 70:
            factors.append('Payment issues detected')
        if engagement < 50:
            factors.append('Low engagement - inactive customer')
        if tenure < 30:
            factors.append('New customer - high churn risk period')
        if growth < 40:
            factors.append('Declining MRR')
        if support < 50:
            factors.append('High support ticket volume')

        return factors

    def _generate_recommendations(self, risk_factors, health_category):
        """Generate actionable recommendations"""
        recommendations = []

        if 'Payment issues' in str(risk_factors):
            recommendations.append({
                'action': 'Contact customer about payment method',
                'priority': 'high',
                'type': 'payment'
            })

        if 'Low engagement' in str(risk_factors):
            recommendations.append({
                'action': 'Send re-engagement email campaign',
                'priority': 'medium',
                'type': 'engagement'
            })

        if 'New customer' in str(risk_factors):
            recommendations.append({
                'action': 'Ensure onboarding completion',
                'priority': 'medium',
                'type': 'onboarding'
            })

        if 'Declining MRR' in str(risk_factors):
            recommendations.append({
                'action': 'Offer loyalty discount or upgrade incentive',
                'priority': 'high',
                'type': 'retention'
            })

        if health_category == 'critical':
            recommendations.insert(0, {
                'action': 'Immediate outreach by account manager',
                'priority': 'urgent',
                'type': 'intervention'
            })

        return recommendations

    # Helper methods
    def _get_payment_failures(self, subscription, days):
        """Get count of payment failures in last N days"""
        cutoff = date.today() - timedelta(days=days)
        PaymentTransaction = self.env['payment.transaction']
        return PaymentTransaction.search_count([
            ('subscription_id', '=', subscription.id),
            ('status', '=', 'failed'),
            ('create_date', '>=', cutoff)
        ])

    def _get_days_since_activity(self, subscription):
        """Get days since last customer activity"""
        # Check last order date
        if subscription.last_order_date:
            return (date.today() - subscription.last_order_date).days
        return 999

    def _get_tenure_months(self, subscription):
        """Get subscription tenure in months"""
        if subscription.start_date:
            delta = date.today() - subscription.start_date
            return delta.days // 30
        return 0

    def _get_mrr_change(self, subscription, days):
        """Get MRR change over N days"""
        # This would query modification logs
        return 0  # Simplified

    def _get_support_tickets(self, subscription, days):
        """Get support ticket count in last N days"""
        cutoff = date.today() - timedelta(days=days)
        Ticket = self.env.get('helpdesk.ticket')
        if Ticket:
            return Ticket.search_count([
                ('partner_id', '=', subscription.customer_id.id),
                ('create_date', '>=', cutoff)
            ])
        return 0

    @api.model
    def calculate_all_health_scores(self):
        """Batch calculate health scores for all active subscriptions"""
        Subscription = self.env['customer.subscription']
        active_subs = Subscription.search([
            ('status', 'in', ['active', 'paused']),
            ('company_id', '=', self.env.company.id)
        ])

        _logger.info(f"Calculating health scores for {len(active_subs)} subscriptions")

        for sub in active_subs:
            try:
                self.calculate_subscription_health(sub.id)
            except Exception as e:
                _logger.error(f"Error calculating health for subscription {sub.id}: {e}")

        return len(active_subs)
```

### 3.8 Analytics REST API Controller

```python
# -*- coding: utf-8 -*-
"""
Subscription Analytics API Controller
Milestone 125: Subscription Analytics
"""

from odoo import http
from odoo.http import request, Response
from datetime import date, datetime, timedelta
import json
import logging

_logger = logging.getLogger(__name__)


class SubscriptionAnalyticsController(http.Controller):
    """REST API for Subscription Analytics"""

    # =========================================================
    # MRR ENDPOINTS
    # =========================================================

    @http.route('/api/v1/analytics/mrr/current', type='json', auth='user', methods=['GET'])
    def get_current_mrr(self):
        """
        Get current MRR metrics

        Returns:
            {
                "total_mrr": 1500000.00,
                "arr": 18000000.00,
                "active_subscriptions": 1250,
                "arpu": 1200.00,
                "mrr_growth_rate": 5.2,
                "snapshot_date": "2026-02-04"
            }
        """
        try:
            MRRSnapshot = request.env['subscription.mrr.snapshot'].sudo()
            latest = MRRSnapshot.search([
                ('company_id', '=', request.env.company.id)
            ], order='snapshot_date desc', limit=1)

            if not latest:
                return {'error': 'No MRR data available'}

            mrr_engine = request.env['mrr.calculation.engine'].sudo()
            growth_rate = mrr_engine.calculate_mrr_growth_rate(30)

            return {
                'total_mrr': latest.total_mrr,
                'arr': latest.total_mrr * 12,
                'active_subscriptions': latest.active_subscriptions,
                'arpu': latest.arpu,
                'mrr_growth_rate': round(growth_rate, 2),
                'snapshot_date': latest.snapshot_date.isoformat(),
                'net_new_mrr': latest.net_new_mrr,
                'new_mrr': latest.new_mrr,
                'churned_mrr': latest.churned_mrr,
                'expansion_mrr': latest.expansion_mrr,
                'contraction_mrr': latest.contraction_mrr
            }
        except Exception as e:
            _logger.error(f"Error fetching current MRR: {e}")
            return {'error': str(e)}

    @http.route('/api/v1/analytics/mrr/trend', type='json', auth='user', methods=['GET'])
    def get_mrr_trend(self, days=90):
        """
        Get MRR trend data for charting

        Args:
            days: Number of days of history (default 90)

        Returns:
            {
                "trend": [
                    {"date": "2026-01-01", "total_mrr": 1400000, ...},
                    ...
                ]
            }
        """
        try:
            mrr_engine = request.env['mrr.calculation.engine'].sudo()
            trend = mrr_engine.get_mrr_trend(int(days))
            return {'trend': trend}
        except Exception as e:
            _logger.error(f"Error fetching MRR trend: {e}")
            return {'error': str(e)}

    @http.route('/api/v1/analytics/mrr/breakdown', type='json', auth='user', methods=['GET'])
    def get_mrr_breakdown(self, breakdown_by='plan'):
        """
        Get MRR breakdown by segment

        Args:
            breakdown_by: 'plan', 'category', 'region'

        Returns:
            {
                "breakdown": [
                    {"segment": "Daily Plan", "mrr": 500000, "percentage": 33.3},
                    ...
                ]
            }
        """
        try:
            MRRSnapshot = request.env['subscription.mrr.snapshot'].sudo()
            latest = MRRSnapshot.search([
                ('company_id', '=', request.env.company.id)
            ], order='snapshot_date desc', limit=1)

            if not latest:
                return {'error': 'No MRR data available'}

            if breakdown_by == 'plan':
                data = eval(latest.mrr_by_plan) if latest.mrr_by_plan else {}
            elif breakdown_by == 'category':
                data = eval(latest.mrr_by_category) if latest.mrr_by_category else {}
            else:
                return {'error': 'Invalid breakdown_by parameter'}

            total = sum(data.values())
            breakdown = []
            for segment, mrr in data.items():
                breakdown.append({
                    'segment': segment,
                    'mrr': mrr,
                    'percentage': round((mrr / total) * 100, 1) if total > 0 else 0
                })

            return {'breakdown': sorted(breakdown, key=lambda x: x['mrr'], reverse=True)}
        except Exception as e:
            _logger.error(f"Error fetching MRR breakdown: {e}")
            return {'error': str(e)}

    # =========================================================
    # CHURN ENDPOINTS
    # =========================================================

    @http.route('/api/v1/analytics/churn/current', type='json', auth='user', methods=['GET'])
    def get_current_churn(self):
        """
        Get current churn metrics

        Returns:
            {
                "logo_churn_rate": 3.5,
                "revenue_churn_rate": 2.8,
                "net_revenue_churn_rate": -1.2,
                "customers_churned": 45,
                "mrr_churned": 54000.00,
                "snapshot_date": "2026-02-04"
            }
        """
        try:
            ChurnMetrics = request.env['subscription.churn.metrics'].sudo()
            latest = ChurnMetrics.search([
                ('company_id', '=', request.env.company.id)
            ], order='snapshot_date desc', limit=1)

            if not latest:
                return {'error': 'No churn data available'}

            return {
                'logo_churn_rate': round(latest.logo_churn_rate, 2),
                'revenue_churn_rate': round(latest.revenue_churn_rate, 2),
                'net_revenue_churn_rate': round(latest.net_revenue_churn_rate, 2),
                'customers_churned': latest.customers_churned,
                'mrr_churned': latest.mrr_churned,
                'mrr_expansion': latest.mrr_expansion,
                'reactivated_customers': latest.reactivated_customers,
                'snapshot_date': latest.snapshot_date.isoformat()
            }
        except Exception as e:
            _logger.error(f"Error fetching churn metrics: {e}")
            return {'error': str(e)}

    @http.route('/api/v1/analytics/churn/by-reason', type='json', auth='user', methods=['GET'])
    def get_churn_by_reason(self):
        """Get churn breakdown by cancellation reason"""
        try:
            ChurnMetrics = request.env['subscription.churn.metrics'].sudo()
            latest = ChurnMetrics.search([
                ('company_id', '=', request.env.company.id)
            ], order='snapshot_date desc', limit=1)

            if not latest or not latest.churn_by_reason:
                return {'reasons': []}

            data = eval(latest.churn_by_reason)
            total = sum(data.values())

            reasons = []
            for reason, count in data.items():
                reasons.append({
                    'reason': reason,
                    'count': count,
                    'percentage': round((count / total) * 100, 1) if total > 0 else 0
                })

            return {'reasons': sorted(reasons, key=lambda x: x['count'], reverse=True)}
        except Exception as e:
            _logger.error(f"Error fetching churn by reason: {e}")
            return {'error': str(e)}

    # =========================================================
    # COHORT RETENTION ENDPOINTS
    # =========================================================

    @http.route('/api/v1/analytics/cohort/matrix', type='json', auth='user', methods=['GET'])
    def get_cohort_matrix(self, months=12):
        """
        Get cohort retention matrix

        Returns:
            {
                "matrix": [
                    {
                        "cohort": "2025-06",
                        "size": 150,
                        "retention": [100, 85, 78, 72, ...]
                    },
                    ...
                ]
            }
        """
        try:
            churn_engine = request.env['churn.analysis.engine'].sudo()
            matrix = churn_engine.get_retention_matrix(int(months))
            return {'matrix': matrix}
        except Exception as e:
            _logger.error(f"Error fetching cohort matrix: {e}")
            return {'error': str(e)}

    @http.route('/api/v1/analytics/cohort/<string:cohort_month>', type='json', auth='user', methods=['GET'])
    def get_cohort_details(self, cohort_month):
        """
        Get detailed retention data for a specific cohort

        Args:
            cohort_month: Format 'YYYY-MM'
        """
        try:
            cohort_date = datetime.strptime(cohort_month + '-01', '%Y-%m-%d').date()

            CohortRetention = request.env['subscription.cohort.retention'].sudo()
            cohort_data = CohortRetention.search([
                ('cohort_month', '=', cohort_date),
                ('company_id', '=', request.env.company.id)
            ], order='months_since_signup asc')

            if not cohort_data:
                return {'error': 'Cohort not found'}

            return {
                'cohort_month': cohort_month,
                'cohort_size': cohort_data[0].cohort_size,
                'cohort_mrr': cohort_data[0].cohort_mrr,
                'retention_data': [{
                    'month': c.months_since_signup,
                    'retained_customers': c.retained_customers,
                    'customer_retention_rate': round(c.customer_retention_rate, 1),
                    'retained_mrr': c.retained_mrr,
                    'revenue_retention_rate': round(c.revenue_retention_rate, 1),
                    'nrr': round(c.nrr, 1)
                } for c in cohort_data]
            }
        except Exception as e:
            _logger.error(f"Error fetching cohort details: {e}")
            return {'error': str(e)}

    # =========================================================
    # CLV ENDPOINTS
    # =========================================================

    @http.route('/api/v1/analytics/clv/summary', type='json', auth='user', methods=['GET'])
    def get_clv_summary(self):
        """
        Get CLV summary statistics

        Returns:
            {
                "avg_clv": 45000.00,
                "median_clv": 32000.00,
                "total_clv": 450000000.00,
                "segment_distribution": {...}
            }
        """
        try:
            CLV = request.env['customer.lifetime.value'].sudo()
            all_clv = CLV.search([
                ('company_id', '=', request.env.company.id)
            ])

            if not all_clv:
                return {'error': 'No CLV data available'}

            clv_values = [c.total_clv for c in all_clv]
            clv_values.sort()

            # Segment distribution
            segments = {'high': 0, 'medium': 0, 'low': 0, 'at_risk': 0}
            for c in all_clv:
                if c.clv_segment:
                    segments[c.clv_segment] += 1

            return {
                'avg_clv': round(sum(clv_values) / len(clv_values), 2),
                'median_clv': clv_values[len(clv_values) // 2],
                'total_clv': sum(clv_values),
                'customer_count': len(all_clv),
                'segment_distribution': segments,
                'top_10_percent_threshold': clv_values[int(len(clv_values) * 0.9)] if clv_values else 0
            }
        except Exception as e:
            _logger.error(f"Error fetching CLV summary: {e}")
            return {'error': str(e)}

    @http.route('/api/v1/analytics/clv/customer/<int:customer_id>', type='json', auth='user', methods=['GET'])
    def get_customer_clv(self, customer_id):
        """Get CLV details for a specific customer"""
        try:
            CLV = request.env['customer.lifetime.value'].sudo()
            clv = CLV.search([
                ('customer_id', '=', customer_id),
                ('company_id', '=', request.env.company.id)
            ], limit=1)

            if not clv:
                return {'error': 'CLV not found for customer'}

            return {
                'customer_id': customer_id,
                'total_clv': clv.total_clv,
                'historical_clv': clv.historical_clv,
                'predicted_clv': clv.predicted_clv,
                'clv_segment': clv.clv_segment,
                'clv_percentile': clv.clv_percentile,
                'tenure_months': clv.subscription_tenure_months,
                'current_mrr': clv.current_mrr,
                'churn_probability': clv.churn_probability,
                'clv_to_cac_ratio': clv.clv_to_cac_ratio
            }
        except Exception as e:
            _logger.error(f"Error fetching customer CLV: {e}")
            return {'error': str(e)}

    # =========================================================
    # HEALTH SCORE ENDPOINTS
    # =========================================================

    @http.route('/api/v1/analytics/health/summary', type='json', auth='user', methods=['GET'])
    def get_health_summary(self):
        """
        Get subscription health summary

        Returns:
            {
                "avg_health_score": 72,
                "distribution": {
                    "healthy": 650,
                    "neutral": 350,
                    "at_risk": 180,
                    "critical": 70
                },
                "at_risk_mrr": 250000.00
            }
        """
        try:
            HealthScore = request.env['subscription.health.score'].sudo()
            today = date.today()

            # Get latest scores for each subscription
            query = """
                SELECT DISTINCT ON (subscription_id)
                    subscription_id, health_score, health_category, churn_risk_score
                FROM subscription_health_score
                WHERE company_id = %s AND score_date <= %s
                ORDER BY subscription_id, score_date DESC
            """
            request.env.cr.execute(query, (request.env.company.id, today))
            scores = request.env.cr.dictfetchall()

            if not scores:
                return {'error': 'No health score data available'}

            # Calculate distribution
            distribution = {'healthy': 0, 'neutral': 0, 'at_risk': 0, 'critical': 0}
            total_score = 0

            for s in scores:
                distribution[s['health_category']] += 1
                total_score += s['health_score']

            return {
                'avg_health_score': round(total_score / len(scores)),
                'distribution': distribution,
                'total_subscriptions': len(scores),
                'at_risk_count': distribution['at_risk'] + distribution['critical']
            }
        except Exception as e:
            _logger.error(f"Error fetching health summary: {e}")
            return {'error': str(e)}

    @http.route('/api/v1/analytics/health/at-risk', type='json', auth='user', methods=['GET'])
    def get_at_risk_subscriptions(self, limit=50):
        """Get list of at-risk subscriptions requiring attention"""
        try:
            HealthScore = request.env['subscription.health.score'].sudo()
            today = date.today()

            at_risk = HealthScore.search([
                ('score_date', '=', today),
                ('health_category', 'in', ['at_risk', 'critical']),
                ('company_id', '=', request.env.company.id)
            ], order='health_score asc', limit=int(limit))

            return {
                'at_risk_subscriptions': [{
                    'subscription_id': h.subscription_id.id,
                    'subscription_name': h.subscription_id.name,
                    'customer_name': h.customer_id.name,
                    'health_score': h.health_score,
                    'health_category': h.health_category,
                    'churn_risk_score': h.churn_risk_score,
                    'risk_factors': json.loads(h.risk_factors) if h.risk_factors else [],
                    'recommended_actions': json.loads(h.recommended_actions) if h.recommended_actions else []
                } for h in at_risk]
            }
        except Exception as e:
            _logger.error(f"Error fetching at-risk subscriptions: {e}")
            return {'error': str(e)}

    # =========================================================
    # FORECAST ENDPOINTS
    # =========================================================

    @http.route('/api/v1/analytics/forecast/mrr', type='json', auth='user', methods=['GET'])
    def get_mrr_forecast(self, horizon='90d'):
        """
        Get MRR forecast

        Args:
            horizon: '30d', '90d', '1y'

        Returns:
            {
                "forecast": [
                    {"date": "2026-03-01", "forecasted_mrr": 1600000, "lower": 1500000, "upper": 1700000},
                    ...
                ]
            }
        """
        try:
            Forecast = request.env['subscription.revenue.forecast'].sudo()
            forecasts = Forecast.search([
                ('forecast_type', '=', 'mrr'),
                ('forecast_horizon', '=', horizon),
                ('company_id', '=', request.env.company.id)
            ], order='target_date asc')

            return {
                'forecast': [{
                    'target_date': f.target_date.isoformat(),
                    'forecasted_value': f.forecasted_value,
                    'lower_bound': f.lower_bound,
                    'upper_bound': f.upper_bound,
                    'model_confidence': f.model_confidence
                } for f in forecasts]
            }
        except Exception as e:
            _logger.error(f"Error fetching MRR forecast: {e}")
            return {'error': str(e)}

    # =========================================================
    # DASHBOARD ENDPOINTS
    # =========================================================

    @http.route('/api/v1/analytics/dashboard/executive', type='json', auth='user', methods=['GET'])
    def get_executive_dashboard(self):
        """
        Get all key metrics for executive dashboard

        Returns comprehensive metrics including:
        - MRR/ARR with trends
        - Churn metrics
        - Health distribution
        - Top-level CLV stats
        """
        try:
            # Aggregate all dashboard data
            mrr_data = self.get_current_mrr()
            churn_data = self.get_current_churn()
            health_data = self.get_health_summary()
            clv_data = self.get_clv_summary()

            return {
                'mrr': mrr_data,
                'churn': churn_data,
                'health': health_data,
                'clv': clv_data,
                'generated_at': datetime.now().isoformat()
            }
        except Exception as e:
            _logger.error(f"Error generating executive dashboard: {e}")
            return {'error': str(e)}
```

### 3.9 Celery Tasks for Analytics

```python
# -*- coding: utf-8 -*-
"""
Celery Tasks for Subscription Analytics
Milestone 125: Subscription Analytics
"""

from celery import shared_task
from datetime import date, timedelta
from dateutil.relativedelta import relativedelta
import logging

_logger = logging.getLogger(__name__)


@shared_task(bind=True, max_retries=3)
def calculate_daily_mrr_task(self):
    """
    Daily task to calculate MRR snapshot

    Schedule: Every day at 01:00 AM
    """
    try:
        from odoo import api, SUPERUSER_ID
        from odoo.registry import Registry

        registry = Registry.new('smart_dairy')
        with registry.cursor() as cr:
            env = api.Environment(cr, SUPERUSER_ID, {})

            mrr_engine = env['mrr.calculation.engine']
            snapshot = mrr_engine.calculate_daily_mrr(date.today())

            _logger.info(f"Daily MRR calculated: {snapshot.total_mrr}")
            return {'status': 'success', 'mrr': snapshot.total_mrr}

    except Exception as e:
        _logger.error(f"Error in daily MRR calculation: {e}")
        raise self.retry(exc=e, countdown=300)


@shared_task(bind=True, max_retries=3)
def calculate_daily_churn_task(self):
    """
    Daily task to calculate churn metrics

    Schedule: Every day at 01:30 AM
    """
    try:
        from odoo import api, SUPERUSER_ID
        from odoo.registry import Registry

        registry = Registry.new('smart_dairy')
        with registry.cursor() as cr:
            env = api.Environment(cr, SUPERUSER_ID, {})

            churn_engine = env['churn.analysis.engine']
            metrics = churn_engine.calculate_daily_churn(date.today())

            _logger.info(f"Daily churn calculated: {metrics.logo_churn_rate}%")
            return {'status': 'success', 'churn_rate': metrics.logo_churn_rate}

    except Exception as e:
        _logger.error(f"Error in daily churn calculation: {e}")
        raise self.retry(exc=e, countdown=300)


@shared_task(bind=True, max_retries=3)
def calculate_cohort_retention_task(self):
    """
    Weekly task to update cohort retention data

    Schedule: Every Sunday at 02:00 AM
    """
    try:
        from odoo import api, SUPERUSER_ID
        from odoo.registry import Registry

        registry = Registry.new('smart_dairy')
        with registry.cursor() as cr:
            env = api.Environment(cr, SUPERUSER_ID, {})

            churn_engine = env['churn.analysis.engine']

            # Calculate retention for last 12 cohorts
            today = date.today()
            for i in range(12):
                cohort_month = (today - relativedelta(months=i)).replace(day=1)
                churn_engine.calculate_cohort_retention(cohort_month, months_to_track=12)

            _logger.info("Cohort retention calculation completed")
            return {'status': 'success'}

    except Exception as e:
        _logger.error(f"Error in cohort retention calculation: {e}")
        raise self.retry(exc=e, countdown=600)


@shared_task(bind=True, max_retries=3)
def calculate_all_clv_task(self):
    """
    Weekly task to recalculate all customer CLV

    Schedule: Every Sunday at 03:00 AM
    """
    try:
        from odoo import api, SUPERUSER_ID
        from odoo.registry import Registry

        registry = Registry.new('smart_dairy')
        with registry.cursor() as cr:
            env = api.Environment(cr, SUPERUSER_ID, {})

            clv_engine = env['clv.calculation.engine']
            count = clv_engine.calculate_all_clv()

            _logger.info(f"CLV calculated for {count} customers")
            return {'status': 'success', 'customers_processed': count}

    except Exception as e:
        _logger.error(f"Error in CLV calculation: {e}")
        raise self.retry(exc=e, countdown=600)


@shared_task(bind=True, max_retries=3)
def calculate_all_health_scores_task(self):
    """
    Daily task to calculate subscription health scores

    Schedule: Every day at 02:00 AM
    """
    try:
        from odoo import api, SUPERUSER_ID
        from odoo.registry import Registry

        registry = Registry.new('smart_dairy')
        with registry.cursor() as cr:
            env = api.Environment(cr, SUPERUSER_ID, {})

            health_engine = env['health.score.engine']
            count = health_engine.calculate_all_health_scores()

            _logger.info(f"Health scores calculated for {count} subscriptions")
            return {'status': 'success', 'subscriptions_processed': count}

    except Exception as e:
        _logger.error(f"Error in health score calculation: {e}")
        raise self.retry(exc=e, countdown=300)


@shared_task(bind=True, max_retries=3)
def generate_mrr_forecast_task(self, horizon='90d'):
    """
    Weekly task to generate MRR forecasts

    Schedule: Every Monday at 04:00 AM
    """
    try:
        from odoo import api, SUPERUSER_ID
        from odoo.registry import Registry

        registry = Registry.new('smart_dairy')
        with registry.cursor() as cr:
            env = api.Environment(cr, SUPERUSER_ID, {})

            forecast_engine = env['revenue.forecast.engine']
            forecasts = forecast_engine.generate_mrr_forecast(horizon)

            _logger.info(f"MRR forecast generated for {horizon}")
            return {'status': 'success', 'horizon': horizon}

    except Exception as e:
        _logger.error(f"Error in MRR forecast generation: {e}")
        raise self.retry(exc=e, countdown=600)


@shared_task(bind=True)
def refresh_analytics_materialized_views_task(self):
    """
    Hourly task to refresh materialized views

    Schedule: Every hour at :15
    """
    try:
        from odoo import api, SUPERUSER_ID
        from odoo.registry import Registry

        registry = Registry.new('smart_dairy')
        with registry.cursor() as cr:
            cr.execute("SELECT refresh_analytics_materialized_views()")

            _logger.info("Analytics materialized views refreshed")
            return {'status': 'success'}

    except Exception as e:
        _logger.error(f"Error refreshing materialized views: {e}")
        return {'status': 'error', 'message': str(e)}


@shared_task(bind=True)
def send_churn_risk_alerts_task(self):
    """
    Daily task to send alerts for high-risk subscriptions

    Schedule: Every day at 08:00 AM
    """
    try:
        from odoo import api, SUPERUSER_ID
        from odoo.registry import Registry

        registry = Registry.new('smart_dairy')
        with registry.cursor() as cr:
            env = api.Environment(cr, SUPERUSER_ID, {})

            HealthScore = env['subscription.health.score']
            today = date.today()

            # Find critical subscriptions
            critical = HealthScore.search([
                ('score_date', '=', today),
                ('health_category', '=', 'critical')
            ])

            if critical:
                # Send notification to sales/support team
                notification_service = env['notification.service']
                notification_service.send_churn_risk_alert(critical)

                _logger.info(f"Churn risk alerts sent for {len(critical)} subscriptions")

            return {'status': 'success', 'alerts_sent': len(critical)}

    except Exception as e:
        _logger.error(f"Error sending churn risk alerts: {e}")
        return {'status': 'error', 'message': str(e)}


# Celery Beat Schedule Configuration
CELERYBEAT_SCHEDULE = {
    'calculate-daily-mrr': {
        'task': 'subscription_analytics.tasks.calculate_daily_mrr_task',
        'schedule': crontab(hour=1, minute=0),
    },
    'calculate-daily-churn': {
        'task': 'subscription_analytics.tasks.calculate_daily_churn_task',
        'schedule': crontab(hour=1, minute=30),
    },
    'calculate-health-scores': {
        'task': 'subscription_analytics.tasks.calculate_all_health_scores_task',
        'schedule': crontab(hour=2, minute=0),
    },
    'calculate-cohort-retention': {
        'task': 'subscription_analytics.tasks.calculate_cohort_retention_task',
        'schedule': crontab(hour=2, minute=0, day_of_week='sunday'),
    },
    'calculate-all-clv': {
        'task': 'subscription_analytics.tasks.calculate_all_clv_task',
        'schedule': crontab(hour=3, minute=0, day_of_week='sunday'),
    },
    'generate-mrr-forecast': {
        'task': 'subscription_analytics.tasks.generate_mrr_forecast_task',
        'schedule': crontab(hour=4, minute=0, day_of_week='monday'),
    },
    'refresh-materialized-views': {
        'task': 'subscription_analytics.tasks.refresh_analytics_materialized_views_task',
        'schedule': crontab(minute=15),
    },
    'send-churn-risk-alerts': {
        'task': 'subscription_analytics.tasks.send_churn_risk_alerts_task',
        'schedule': crontab(hour=8, minute=0),
    },
}
```

### 3.10 React Dashboard Components

```jsx
// src/components/analytics/ExecutiveDashboard.jsx
import React, { useState, useEffect } from 'react';
import { Card, Row, Col, Statistic, Spin, Alert, Tabs } from 'antd';
import {
  ArrowUpOutlined,
  ArrowDownOutlined,
  DollarOutlined,
  UserOutlined,
  WarningOutlined
} from '@ant-design/icons';
import { Line, Pie, Heatmap } from '@ant-design/charts';
import { analyticsApi } from '../../services/analyticsApi';
import MRRTrendChart from './MRRTrendChart';
import ChurnAnalysisPanel from './ChurnAnalysisPanel';
import CohortRetentionMatrix from './CohortRetentionMatrix';
import HealthScoreDistribution from './HealthScoreDistribution';
import './ExecutiveDashboard.css';

const { TabPane } = Tabs;

const ExecutiveDashboard = () => {
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [dashboardData, setDashboardData] = useState(null);
  const [refreshKey, setRefreshKey] = useState(0);

  useEffect(() => {
    fetchDashboardData();
    // Auto-refresh every 15 minutes
    const interval = setInterval(fetchDashboardData, 15 * 60 * 1000);
    return () => clearInterval(interval);
  }, [refreshKey]);

  const fetchDashboardData = async () => {
    try {
      setLoading(true);
      const response = await analyticsApi.getExecutiveDashboard();
      setDashboardData(response);
      setError(null);
    } catch (err) {
      setError('Failed to load dashboard data');
      console.error('Dashboard error:', err);
    } finally {
      setLoading(false);
    }
  };

  if (loading && !dashboardData) {
    return (
      <div className="dashboard-loading">
        <Spin size="large" tip="Loading analytics..." />
      </div>
    );
  }

  if (error) {
    return <Alert type="error" message={error} showIcon />;
  }

  const { mrr, churn, health, clv } = dashboardData;

  return (
    <div className="executive-dashboard">
      <div className="dashboard-header">
        <h1>Subscription Analytics Dashboard</h1>
        <span className="last-updated">
          Last updated: {new Date(dashboardData.generated_at).toLocaleString()}
        </span>
      </div>

      {/* Key Metrics Row */}
      <Row gutter={[16, 16]} className="metrics-row">
        <Col xs={24} sm={12} lg={6}>
          <Card className="metric-card mrr-card">
            <Statistic
              title="Monthly Recurring Revenue"
              value={mrr.total_mrr}
              precision={0}
              prefix="৳"
              suffix={
                <span className={mrr.mrr_growth_rate >= 0 ? 'positive' : 'negative'}>
                  {mrr.mrr_growth_rate >= 0 ? <ArrowUpOutlined /> : <ArrowDownOutlined />}
                  {Math.abs(mrr.mrr_growth_rate)}%
                </span>
              }
            />
            <div className="metric-subtitle">
              ARR: ৳{(mrr.arr / 10000000).toFixed(2)} Cr
            </div>
          </Card>
        </Col>

        <Col xs={24} sm={12} lg={6}>
          <Card className="metric-card subscriptions-card">
            <Statistic
              title="Active Subscriptions"
              value={mrr.active_subscriptions}
              prefix={<UserOutlined />}
            />
            <div className="metric-subtitle">
              ARPU: ৳{mrr.arpu?.toFixed(0) || 0}
            </div>
          </Card>
        </Col>

        <Col xs={24} sm={12} lg={6}>
          <Card className="metric-card churn-card">
            <Statistic
              title="Logo Churn Rate"
              value={churn.logo_churn_rate}
              precision={2}
              suffix="%"
              valueStyle={{ color: churn.logo_churn_rate > 5 ? '#cf1322' : '#3f8600' }}
            />
            <div className="metric-subtitle">
              Revenue Churn: {churn.revenue_churn_rate?.toFixed(2)}%
            </div>
          </Card>
        </Col>

        <Col xs={24} sm={12} lg={6}>
          <Card className="metric-card health-card">
            <Statistic
              title="Avg Health Score"
              value={health.avg_health_score}
              suffix="/100"
              valueStyle={{
                color: health.avg_health_score >= 70 ? '#3f8600' :
                       health.avg_health_score >= 50 ? '#faad14' : '#cf1322'
              }}
            />
            <div className="metric-subtitle">
              At Risk: {health.at_risk_count} subscriptions
            </div>
          </Card>
        </Col>
      </Row>

      {/* MRR Movement Row */}
      <Row gutter={[16, 16]} className="mrr-movement-row">
        <Col xs={12} sm={6}>
          <Card size="small">
            <Statistic
              title="New MRR"
              value={mrr.new_mrr}
              prefix="৳"
              valueStyle={{ color: '#52c41a', fontSize: '18px' }}
            />
          </Card>
        </Col>
        <Col xs={12} sm={6}>
          <Card size="small">
            <Statistic
              title="Expansion MRR"
              value={mrr.expansion_mrr}
              prefix="৳"
              valueStyle={{ color: '#1890ff', fontSize: '18px' }}
            />
          </Card>
        </Col>
        <Col xs={12} sm={6}>
          <Card size="small">
            <Statistic
              title="Contraction MRR"
              value={mrr.contraction_mrr}
              prefix="-৳"
              valueStyle={{ color: '#faad14', fontSize: '18px' }}
            />
          </Card>
        </Col>
        <Col xs={12} sm={6}>
          <Card size="small">
            <Statistic
              title="Churned MRR"
              value={mrr.churned_mrr}
              prefix="-৳"
              valueStyle={{ color: '#cf1322', fontSize: '18px' }}
            />
          </Card>
        </Col>
      </Row>

      {/* Charts Section */}
      <Tabs defaultActiveKey="mrr" className="dashboard-tabs">
        <TabPane tab="MRR Trends" key="mrr">
          <MRRTrendChart />
        </TabPane>
        <TabPane tab="Churn Analysis" key="churn">
          <ChurnAnalysisPanel />
        </TabPane>
        <TabPane tab="Cohort Retention" key="cohort">
          <CohortRetentionMatrix />
        </TabPane>
        <TabPane tab="Health Scores" key="health">
          <HealthScoreDistribution data={health} />
        </TabPane>
      </Tabs>
    </div>
  );
};

export default ExecutiveDashboard;
```

```jsx
// src/components/analytics/MRRTrendChart.jsx
import React, { useState, useEffect } from 'react';
import { Card, Select, Spin } from 'antd';
import { Line } from '@ant-design/charts';
import { analyticsApi } from '../../services/analyticsApi';

const { Option } = Select;

const MRRTrendChart = () => {
  const [loading, setLoading] = useState(true);
  const [trendData, setTrendData] = useState([]);
  const [days, setDays] = useState(90);

  useEffect(() => {
    fetchTrendData();
  }, [days]);

  const fetchTrendData = async () => {
    try {
      setLoading(true);
      const response = await analyticsApi.getMRRTrend(days);

      // Transform data for chart
      const chartData = [];
      response.trend.forEach(item => {
        chartData.push({ date: item.date, value: item.total_mrr, category: 'Total MRR' });
        chartData.push({ date: item.date, value: item.new_mrr, category: 'New MRR' });
        chartData.push({ date: item.date, value: item.churned_mrr, category: 'Churned MRR' });
      });

      setTrendData(chartData);
    } catch (error) {
      console.error('Error fetching MRR trend:', error);
    } finally {
      setLoading(false);
    }
  };

  const config = {
    data: trendData,
    xField: 'date',
    yField: 'value',
    seriesField: 'category',
    yAxis: {
      label: {
        formatter: (v) => `৳${(v / 1000).toFixed(0)}K`,
      },
    },
    legend: {
      position: 'top',
    },
    smooth: true,
    animation: {
      appear: {
        animation: 'path-in',
        duration: 1000,
      },
    },
    color: ['#1890ff', '#52c41a', '#cf1322'],
  };

  return (
    <Card
      title="MRR Trend"
      extra={
        <Select value={days} onChange={setDays} style={{ width: 120 }}>
          <Option value={30}>Last 30 days</Option>
          <Option value={90}>Last 90 days</Option>
          <Option value={180}>Last 6 months</Option>
          <Option value={365}>Last year</Option>
        </Select>
      }
    >
      {loading ? (
        <div style={{ textAlign: 'center', padding: '50px' }}>
          <Spin />
        </div>
      ) : (
        <Line {...config} height={400} />
      )}
    </Card>
  );
};

export default MRRTrendChart;
```

```jsx
// src/components/analytics/CohortRetentionMatrix.jsx
import React, { useState, useEffect } from 'react';
import { Card, Select, Spin, Table, Tooltip } from 'antd';
import { analyticsApi } from '../../services/analyticsApi';
import './CohortRetentionMatrix.css';

const CohortRetentionMatrix = () => {
  const [loading, setLoading] = useState(true);
  const [matrixData, setMatrixData] = useState([]);
  const [months, setMonths] = useState(12);

  useEffect(() => {
    fetchCohortData();
  }, [months]);

  const fetchCohortData = async () => {
    try {
      setLoading(true);
      const response = await analyticsApi.getCohortMatrix(months);
      setMatrixData(response.matrix);
    } catch (error) {
      console.error('Error fetching cohort data:', error);
    } finally {
      setLoading(false);
    }
  };

  const getRetentionColor = (rate) => {
    if (rate >= 90) return '#52c41a';
    if (rate >= 80) return '#73d13d';
    if (rate >= 70) return '#95de64';
    if (rate >= 60) return '#fadb14';
    if (rate >= 50) return '#ffc53d';
    if (rate >= 40) return '#ff7a45';
    return '#ff4d4f';
  };

  const columns = [
    {
      title: 'Cohort',
      dataIndex: 'cohort',
      key: 'cohort',
      fixed: 'left',
      width: 100,
    },
    {
      title: 'Size',
      dataIndex: 'size',
      key: 'size',
      width: 80,
    },
    ...Array.from({ length: 12 }, (_, i) => ({
      title: `M${i}`,
      dataIndex: ['retention', i],
      key: `month_${i}`,
      width: 60,
      render: (value) => {
        if (value === undefined) return '-';
        return (
          <Tooltip title={`${value.toFixed(1)}% retention`}>
            <div
              className="retention-cell"
              style={{ backgroundColor: getRetentionColor(value) }}
            >
              {value.toFixed(0)}%
            </div>
          </Tooltip>
        );
      },
    })),
  ];

  return (
    <Card
      title="Cohort Retention Matrix"
      extra={
        <Select value={months} onChange={setMonths} style={{ width: 150 }}>
          <Select.Option value={6}>Last 6 cohorts</Select.Option>
          <Select.Option value={12}>Last 12 cohorts</Select.Option>
          <Select.Option value={24}>Last 24 cohorts</Select.Option>
        </Select>
      }
    >
      {loading ? (
        <div style={{ textAlign: 'center', padding: '50px' }}>
          <Spin />
        </div>
      ) : (
        <Table
          columns={columns}
          dataSource={matrixData.map((row, i) => ({ ...row, key: i }))}
          pagination={false}
          scroll={{ x: 'max-content' }}
          size="small"
          className="cohort-table"
        />
      )}
    </Card>
  );
};

export default CohortRetentionMatrix;
```

```jsx
// src/components/analytics/HealthScoreDistribution.jsx
import React from 'react';
import { Card, Row, Col, Progress, List, Tag, Badge } from 'antd';
import { Pie } from '@ant-design/charts';
import {
  CheckCircleOutlined,
  MinusCircleOutlined,
  WarningOutlined,
  CloseCircleOutlined
} from '@ant-design/icons';

const HealthScoreDistribution = ({ data }) => {
  const { distribution, avg_health_score, total_subscriptions } = data;

  const pieData = [
    { type: 'Healthy', value: distribution.healthy, color: '#52c41a' },
    { type: 'Neutral', value: distribution.neutral, color: '#1890ff' },
    { type: 'At Risk', value: distribution.at_risk, color: '#faad14' },
    { type: 'Critical', value: distribution.critical, color: '#cf1322' },
  ];

  const pieConfig = {
    data: pieData,
    angleField: 'value',
    colorField: 'type',
    radius: 0.8,
    innerRadius: 0.6,
    label: {
      type: 'outer',
      content: '{name}: {percentage}',
    },
    color: ['#52c41a', '#1890ff', '#faad14', '#cf1322'],
    statistic: {
      title: {
        content: 'Total',
      },
      content: {
        content: total_subscriptions.toString(),
      },
    },
  };

  const getHealthIcon = (category) => {
    switch (category) {
      case 'healthy':
        return <CheckCircleOutlined style={{ color: '#52c41a' }} />;
      case 'neutral':
        return <MinusCircleOutlined style={{ color: '#1890ff' }} />;
      case 'at_risk':
        return <WarningOutlined style={{ color: '#faad14' }} />;
      case 'critical':
        return <CloseCircleOutlined style={{ color: '#cf1322' }} />;
      default:
        return null;
    }
  };

  return (
    <Row gutter={[16, 16]}>
      <Col xs={24} lg={12}>
        <Card title="Health Score Distribution">
          <Pie {...pieConfig} height={300} />
        </Card>
      </Col>

      <Col xs={24} lg={12}>
        <Card title="Health Breakdown">
          <div className="health-summary">
            <div className="avg-score">
              <Progress
                type="dashboard"
                percent={avg_health_score}
                strokeColor={{
                  '0%': '#cf1322',
                  '50%': '#faad14',
                  '100%': '#52c41a',
                }}
                format={(percent) => (
                  <div>
                    <div style={{ fontSize: '24px', fontWeight: 'bold' }}>{percent}</div>
                    <div style={{ fontSize: '12px' }}>Avg Score</div>
                  </div>
                )}
              />
            </div>

            <List
              itemLayout="horizontal"
              dataSource={[
                { category: 'healthy', label: 'Healthy (80-100)', count: distribution.healthy },
                { category: 'neutral', label: 'Neutral (60-79)', count: distribution.neutral },
                { category: 'at_risk', label: 'At Risk (40-59)', count: distribution.at_risk },
                { category: 'critical', label: 'Critical (0-39)', count: distribution.critical },
              ]}
              renderItem={(item) => (
                <List.Item>
                  <List.Item.Meta
                    avatar={getHealthIcon(item.category)}
                    title={item.label}
                  />
                  <Badge
                    count={item.count}
                    style={{
                      backgroundColor:
                        item.category === 'healthy' ? '#52c41a' :
                        item.category === 'neutral' ? '#1890ff' :
                        item.category === 'at_risk' ? '#faad14' : '#cf1322'
                    }}
                  />
                </List.Item>
              )}
            />
          </div>
        </Card>
      </Col>
    </Row>
  );
};

export default HealthScoreDistribution;
```

```javascript
// src/services/analyticsApi.js
import axios from 'axios';

const API_BASE = '/api/v1/analytics';

export const analyticsApi = {
  // MRR Endpoints
  getCurrentMRR: async () => {
    const response = await axios.get(`${API_BASE}/mrr/current`);
    return response.data;
  },

  getMRRTrend: async (days = 90) => {
    const response = await axios.get(`${API_BASE}/mrr/trend`, { params: { days } });
    return response.data;
  },

  getMRRBreakdown: async (breakdownBy = 'plan') => {
    const response = await axios.get(`${API_BASE}/mrr/breakdown`, {
      params: { breakdown_by: breakdownBy }
    });
    return response.data;
  },

  // Churn Endpoints
  getCurrentChurn: async () => {
    const response = await axios.get(`${API_BASE}/churn/current`);
    return response.data;
  },

  getChurnByReason: async () => {
    const response = await axios.get(`${API_BASE}/churn/by-reason`);
    return response.data;
  },

  // Cohort Endpoints
  getCohortMatrix: async (months = 12) => {
    const response = await axios.get(`${API_BASE}/cohort/matrix`, { params: { months } });
    return response.data;
  },

  getCohortDetails: async (cohortMonth) => {
    const response = await axios.get(`${API_BASE}/cohort/${cohortMonth}`);
    return response.data;
  },

  // CLV Endpoints
  getCLVSummary: async () => {
    const response = await axios.get(`${API_BASE}/clv/summary`);
    return response.data;
  },

  getCustomerCLV: async (customerId) => {
    const response = await axios.get(`${API_BASE}/clv/customer/${customerId}`);
    return response.data;
  },

  // Health Score Endpoints
  getHealthSummary: async () => {
    const response = await axios.get(`${API_BASE}/health/summary`);
    return response.data;
  },

  getAtRiskSubscriptions: async (limit = 50) => {
    const response = await axios.get(`${API_BASE}/health/at-risk`, { params: { limit } });
    return response.data;
  },

  // Forecast Endpoints
  getMRRForecast: async (horizon = '90d') => {
    const response = await axios.get(`${API_BASE}/forecast/mrr`, { params: { horizon } });
    return response.data;
  },

  // Dashboard
  getExecutiveDashboard: async () => {
    const response = await axios.get(`${API_BASE}/dashboard/executive`);
    return response.data;
  },
};

export default analyticsApi;
```

---

## 4. Day-by-Day Implementation Plan

### Day 641: Analytics Data Models & Infrastructure

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Design TimescaleDB schema for analytics | Schema design document |
| 2:00-4:00 | Create SQL migration scripts for hypertables | `subscription_mrr_daily`, `subscription_churn_daily` tables |
| 4:00-6:00 | Implement Odoo analytics models | `subscription.mrr.snapshot`, `subscription.churn.metrics` models |
| 6:00-7:30 | Configure TimescaleDB compression/retention policies | Compression and retention policies active |
| 7:30-8:00 | Code review and documentation | Technical documentation |

**Commands:**
```bash
# Enable TimescaleDB
psql -d smart_dairy -c "CREATE EXTENSION IF NOT EXISTS timescaledb;"

# Run migration
odoo-bin -d smart_dairy -u subscription_analytics --stop-after-init
```

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Set up Celery Beat for scheduled analytics tasks | Celery configuration |
| 2:00-4:00 | Configure Redis caching for analytics | Redis cache layer |
| 4:00-6:00 | Implement data ETL pipeline framework | ETL base classes |
| 6:00-7:30 | Set up monitoring for analytics jobs | Prometheus metrics |
| 7:30-8:00 | Security review of analytics data access | Security audit notes |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Design analytics dashboard wireframes | Figma/Sketch mockups |
| 2:00-4:00 | Set up React analytics module structure | Project scaffolding |
| 4:00-6:00 | Create base dashboard layout component | `ExecutiveDashboard.jsx` shell |
| 6:00-7:30 | Implement analytics API service layer | `analyticsApi.js` |
| 7:30-8:00 | Set up Chart.js/Ant Design Charts | Chart library configuration |

**Daily Deliverables:**
- [ ] TimescaleDB hypertables created
- [ ] Odoo analytics models implemented
- [ ] Celery Beat configured
- [ ] Dashboard wireframes approved
- [ ] React analytics module initialized

---

### Day 642: MRR Calculation Engine

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement MRR calculation engine core | `mrr.calculation.engine` model |
| 2:30-5:00 | Build daily MRR snapshot calculation | `calculate_daily_mrr()` method |
| 5:00-6:30 | Implement MRR component breakdown (new, expansion, churn) | MRR movement tracking |
| 6:30-8:00 | Create MRR by segment calculations | Plan/category/region breakdown |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create Celery task for daily MRR calculation | `calculate_daily_mrr_task` |
| 2:00-4:00 | Implement MRR REST API endpoints | `/api/v1/analytics/mrr/*` |
| 4:00-6:00 | Add API response caching | Redis cache for MRR data |
| 6:00-7:30 | Write unit tests for MRR calculations | Test coverage >80% |
| 7:30-8:00 | API documentation | OpenAPI spec update |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build MRR summary cards component | Key metrics display |
| 2:30-5:00 | Implement MRR trend line chart | `MRRTrendChart.jsx` |
| 5:00-7:00 | Create MRR breakdown pie chart | Segment distribution |
| 7:00-8:00 | Add loading states and error handling | UX polish |

**Daily Deliverables:**
- [ ] MRR calculation engine complete
- [ ] Daily MRR Celery task running
- [ ] MRR API endpoints functional
- [ ] MRR dashboard components rendering

---

### Day 643: Churn Analysis Engine

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Implement churn analysis engine | `churn.analysis.engine` model |
| 2:30-4:30 | Build logo churn calculation | Customer count churn |
| 4:30-6:30 | Build revenue churn calculation | MRR-based churn |
| 6:30-8:00 | Implement churn by reason analysis | Cancellation reason breakdown |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create Celery task for churn calculation | `calculate_daily_churn_task` |
| 2:00-4:00 | Implement churn REST API endpoints | `/api/v1/analytics/churn/*` |
| 4:00-6:00 | Add net revenue churn calculation | Expansion-adjusted churn |
| 6:00-7:30 | Write integration tests | Churn calculation tests |
| 7:30-8:00 | Performance optimization | Query optimization |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build churn metrics cards | Churn rate display |
| 2:30-5:00 | Implement churn trend chart | Historical churn view |
| 5:00-7:00 | Create churn by reason chart | Pie/bar chart |
| 7:00-8:00 | Build `ChurnAnalysisPanel.jsx` | Combined churn view |

**Daily Deliverables:**
- [ ] Churn analysis engine complete
- [ ] Logo and revenue churn calculated
- [ ] Churn API endpoints functional
- [ ] Churn dashboard panel complete

---

### Day 644: Cohort Retention Analysis

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Design cohort retention data model | `subscription.cohort.retention` |
| 2:30-5:00 | Implement cohort identification logic | Monthly cohort grouping |
| 5:00-7:00 | Build retention rate calculation | Customer/revenue retention |
| 7:00-8:00 | Implement NRR (Net Revenue Retention) | Expansion-inclusive retention |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create weekly cohort calculation task | `calculate_cohort_retention_task` |
| 2:00-4:00 | Implement cohort REST API | `/api/v1/analytics/cohort/*` |
| 4:00-6:00 | Build cohort matrix query optimization | Efficient matrix generation |
| 6:00-7:30 | Write cohort calculation tests | Test coverage |
| 7:30-8:00 | Document cohort methodology | Technical documentation |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build cohort retention matrix component | `CohortRetentionMatrix.jsx` |
| 3:00-5:30 | Implement heatmap visualization | Color-coded retention |
| 5:30-7:30 | Add cohort drill-down capability | Detailed cohort view |
| 7:30-8:00 | Mobile responsive adjustments | Responsive design |

**Daily Deliverables:**
- [ ] Cohort retention model implemented
- [ ] Monthly cohort calculation working
- [ ] Cohort matrix API functional
- [ ] Retention heatmap displaying

---

### Day 645: Customer Lifetime Value (CLV)

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Design CLV data model | `customer.lifetime.value` model |
| 2:00-4:00 | Implement historical CLV calculation | Revenue-based CLV |
| 4:00-6:00 | Build predictive CLV model (DCF) | Future value estimation |
| 6:00-7:30 | Implement CLV segmentation | High/medium/low/at-risk |
| 7:30-8:00 | Calculate CLV percentiles | Ranking system |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create weekly CLV calculation task | `calculate_all_clv_task` |
| 2:00-4:00 | Implement CLV REST API | `/api/v1/analytics/clv/*` |
| 4:00-6:00 | Build CLV:CAC ratio calculation | ROI metrics |
| 6:00-7:30 | Optimize batch CLV calculation | Performance tuning |
| 7:30-8:00 | Write CLV validation tests | Data quality tests |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build CLV summary dashboard | Aggregate CLV view |
| 2:30-5:00 | Create CLV distribution chart | Histogram/box plot |
| 5:00-7:00 | Implement customer CLV lookup | Individual CLV view |
| 7:00-8:00 | Add CLV segment filters | Filtering capability |

**Daily Deliverables:**
- [ ] CLV calculation engine complete
- [ ] Predictive CLV model working
- [ ] CLV API endpoints functional
- [ ] CLV dashboard components ready

---

### Day 646: Subscription Health Score

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Design health score data model | `subscription.health.score` model |
| 2:00-4:00 | Implement component score calculations | Payment, engagement, tenure, growth, support |
| 4:00-6:00 | Build weighted health score algorithm | Composite scoring |
| 6:00-7:30 | Implement health category assignment | Healthy/neutral/at-risk/critical |
| 7:30-8:00 | Create risk factor identification | Risk analysis |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create daily health score task | `calculate_all_health_scores_task` |
| 2:00-4:00 | Implement health score API | `/api/v1/analytics/health/*` |
| 4:00-6:00 | Build at-risk subscription alerts | Alert system |
| 6:00-7:30 | Write health score tests | Test coverage |
| 7:30-8:00 | Security review | Access control validation |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build health score distribution chart | `HealthScoreDistribution.jsx` |
| 2:30-5:00 | Create at-risk subscriptions list | Priority view |
| 5:00-7:00 | Implement health score details modal | Drill-down view |
| 7:00-8:00 | Add health trend sparklines | Trend indicators |

**Daily Deliverables:**
- [ ] Health score engine complete
- [ ] Daily health calculation running
- [ ] Health API endpoints functional
- [ ] Health dashboard components ready

---

### Day 647: Revenue Forecasting

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Design forecast data model | `subscription.revenue.forecast` model |
| 2:30-5:00 | Implement linear trend forecasting | Basic projection |
| 5:00-7:00 | Add seasonality adjustment | Seasonal patterns |
| 7:00-8:00 | Build confidence interval calculation | Uncertainty bounds |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Create weekly forecast task | `generate_mrr_forecast_task` |
| 2:00-4:00 | Implement forecast API | `/api/v1/analytics/forecast/*` |
| 4:00-6:00 | Add forecast accuracy tracking | Actual vs predicted |
| 6:00-7:30 | Write forecast validation tests | Model accuracy tests |
| 7:30-8:00 | Document forecasting methodology | Technical documentation |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-3:00 | Build forecast chart with confidence bands | Forecast visualization |
| 3:00-5:30 | Create forecast horizon selector | 30d/90d/1y options |
| 5:30-7:30 | Implement forecast vs actual comparison | Accuracy view |
| 7:30-8:00 | Add forecast export functionality | CSV/PDF export |

**Daily Deliverables:**
- [ ] Forecast engine complete
- [ ] Weekly forecast generation running
- [ ] Forecast API endpoints functional
- [ ] Forecast chart displaying

---

### Day 648: Churn Prediction Model

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Design churn prediction feature set | Feature engineering |
| 2:30-5:00 | Implement logistic regression churn model | Basic ML model |
| 5:00-7:00 | Build churn probability scoring | Risk scores |
| 7:00-8:00 | Create days-to-churn prediction | Timeline estimation |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Integrate churn prediction with health scores | Unified risk view |
| 2:00-4:00 | Build churn risk alert system | `send_churn_risk_alerts_task` |
| 4:00-6:00 | Implement model retraining pipeline | Weekly model update |
| 6:00-7:30 | Write prediction accuracy tests | Model validation |
| 7:30-8:00 | Performance optimization | Batch prediction tuning |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Build churn risk indicator component | Risk badges |
| 2:30-5:00 | Create churn prediction timeline | Days-to-churn display |
| 5:00-7:00 | Implement intervention recommendation UI | Action items |
| 7:00-8:00 | Add churn risk filters to customer list | Filtering capability |

**Daily Deliverables:**
- [ ] Churn prediction model implemented
- [ ] Risk alerts configured
- [ ] Prediction integrated with health scores
- [ ] Risk indicators displaying

---

### Day 649: Apache Superset Integration

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Configure Superset database connections | TimescaleDB connection |
| 2:30-5:00 | Create Superset datasets for analytics | MRR, churn, cohort datasets |
| 5:00-7:00 | Build executive summary dashboard | Pre-built dashboard |
| 7:00-8:00 | Configure dashboard refresh schedules | Auto-refresh |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Set up Superset SSO with Odoo | Authentication integration |
| 2:00-4:00 | Configure Superset row-level security | Data access control |
| 4:00-6:00 | Create materialized view refresh job | `refresh_analytics_materialized_views_task` |
| 6:00-7:30 | Set up Superset alerts | Threshold-based alerts |
| 7:30-8:00 | Document Superset setup | Admin documentation |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:30 | Embed Superset dashboards in Odoo | iframe integration |
| 2:30-5:00 | Create custom Superset chart plugins | Smart Dairy branding |
| 5:00-7:00 | Build Superset dashboard selector | Dashboard navigation |
| 7:00-8:00 | Mobile-optimize embedded dashboards | Responsive embedding |

**Daily Deliverables:**
- [ ] Superset connected to analytics DB
- [ ] Executive dashboard created in Superset
- [ ] SSO integration working
- [ ] Dashboards embedded in Odoo

---

### Day 650: Testing, Documentation & Deployment

#### Dev 1 Tasks (Backend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | End-to-end analytics calculation testing | E2E test suite |
| 2:00-4:00 | Data accuracy validation | Data quality checks |
| 4:00-6:00 | Performance benchmarking | Benchmark report |
| 6:00-7:30 | Fix identified issues | Bug fixes |
| 7:30-8:00 | Technical documentation review | Docs finalized |

#### Dev 2 Tasks (Full-Stack) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | API integration testing | API test suite |
| 2:00-4:00 | Load testing analytics endpoints | Performance report |
| 4:00-5:30 | Security penetration testing | Security report |
| 5:30-7:00 | Deploy to staging environment | Staging deployment |
| 7:00-8:00 | Create deployment runbook | Operations documentation |

#### Dev 3 Tasks (Frontend Lead) - 8 Hours

| Time | Task | Deliverable |
|------|------|-------------|
| 0:00-2:00 | Dashboard visual testing | Screenshot tests |
| 2:00-4:00 | Cross-browser compatibility testing | Browser test report |
| 4:00-5:30 | Accessibility testing (WCAG 2.1) | Accessibility report |
| 5:30-7:00 | User documentation | User guide |
| 7:00-8:00 | Demo preparation | Demo script |

**Daily Deliverables:**
- [ ] All tests passing
- [ ] Performance benchmarks met
- [ ] Security audit complete
- [ ] Staging deployment successful
- [ ] Documentation complete

---

## 5. Deliverables Checklist

### Technical Deliverables

| ID | Deliverable | Verification Method | Status |
|----|-------------|---------------------|--------|
| T125-01 | TimescaleDB hypertables | Schema verification | ☐ |
| T125-02 | MRR calculation engine | Unit tests passing | ☐ |
| T125-03 | Churn analysis engine | Integration tests | ☐ |
| T125-04 | Cohort retention calculation | Data validation | ☐ |
| T125-05 | CLV calculation engine | Accuracy testing | ☐ |
| T125-06 | Health score engine | Component tests | ☐ |
| T125-07 | Revenue forecast model | Prediction accuracy | ☐ |
| T125-08 | Churn prediction model | Model validation | ☐ |
| T125-09 | Analytics REST API | API tests passing | ☐ |
| T125-10 | Celery scheduled tasks | Task execution logs | ☐ |
| T125-11 | Executive dashboard | Visual verification | ☐ |
| T125-12 | Superset integration | SSO working | ☐ |

### Documentation Deliverables

| ID | Deliverable | Status |
|----|-------------|--------|
| D125-01 | Analytics data model documentation | ☐ |
| D125-02 | API specification (OpenAPI) | ☐ |
| D125-03 | Dashboard user guide | ☐ |
| D125-04 | Forecasting methodology | ☐ |
| D125-05 | Health score algorithm documentation | ☐ |

---

## 6. Success Criteria

### Functional Criteria

| Criteria | Target | Verification |
|----------|--------|--------------|
| MRR calculation accuracy | 99.9% | Compare with manual calculation |
| Churn rate accuracy | 99.5% | Audit against subscription data |
| Cohort retention tracking | 12 months | Matrix completeness |
| CLV prediction accuracy | ±15% | Historical validation |
| Health score correlation | >0.7 with churn | Statistical analysis |
| Forecast accuracy (30d) | ±10% | Actual vs predicted |

### Technical Criteria

| Criteria | Target | Verification |
|----------|--------|--------------|
| Dashboard load time | <2 seconds | Performance testing |
| API response time (P95) | <500ms | Load testing |
| Daily calculation time | <30 minutes | Job monitoring |
| Data freshness | <15 minutes | Timestamp validation |
| Concurrent users | 100+ | Stress testing |
| Data compression ratio | >70% | Storage analysis |

### Business Criteria

| Criteria | Target | Verification |
|----------|--------|--------------|
| Executive dashboard adoption | 100% leadership | Usage analytics |
| Churn intervention rate | 50% of at-risk | Action tracking |
| Report generation reduction | 80% | Time comparison |
| Decision-making speed | 40% faster | Stakeholder feedback |

---

## 7. Risk Register

| Risk ID | Description | Impact | Probability | Mitigation |
|---------|-------------|--------|-------------|------------|
| R125-01 | TimescaleDB performance issues | High | Medium | Query optimization, indexing |
| R125-02 | Inaccurate CLV predictions | Medium | Medium | Regular model validation |
| R125-03 | Dashboard complexity | Medium | Medium | User training, simplification |
| R125-04 | Data quality issues | High | Medium | Validation rules, monitoring |
| R125-05 | Celery job failures | Medium | Low | Retry logic, alerting |
| R125-06 | Superset SSO issues | Medium | Low | Fallback authentication |

---

## 8. Dependencies

### Internal Dependencies

| Dependency | Source | Required By |
|------------|--------|-------------|
| Subscription data | Milestone 121 | MRR calculation |
| Payment transaction data | Milestone 124 | Payment score |
| Cancellation data | Milestone 123 | Churn analysis |
| Customer data | Phase 3 | CLV calculation |

### External Dependencies

| Dependency | Provider | Purpose |
|------------|----------|---------|
| TimescaleDB | TimescaleDB Inc | Time-series storage |
| Apache Superset | Apache Foundation | Dashboard visualization |
| Redis | Redis Ltd | Caching layer |
| Celery | Celery Project | Task scheduling |

---

## 9. Quality Assurance

### Testing Strategy

| Test Type | Coverage Target | Tools |
|-----------|-----------------|-------|
| Unit Tests | >80% | pytest, unittest |
| Integration Tests | All APIs | pytest, requests |
| Performance Tests | Critical paths | Locust, k6 |
| Visual Tests | All dashboards | Percy, Chromatic |
| Accessibility Tests | WCAG 2.1 AA | axe, WAVE |

### Code Quality Standards

- PEP 8 compliance for Python code
- ESLint/Prettier for JavaScript/React
- SQL formatting with pgFormatter
- Minimum 80% test coverage
- All code reviewed before merge

---

## 10. Appendix

### A. Glossary

| Term | Definition |
|------|------------|
| **MRR** | Monthly Recurring Revenue - predictable revenue per month |
| **ARR** | Annual Recurring Revenue - MRR × 12 |
| **ARPU** | Average Revenue Per User |
| **Logo Churn** | Percentage of customers lost |
| **Revenue Churn** | Percentage of MRR lost |
| **NRR** | Net Revenue Retention - includes expansion |
| **GRR** | Gross Revenue Retention - excludes expansion |
| **CLV** | Customer Lifetime Value |
| **CAC** | Customer Acquisition Cost |
| **DCF** | Discounted Cash Flow |
| **Cohort** | Group of customers who started in same period |

### B. SQL Quick Reference

```sql
-- Get current MRR
SELECT total_mrr FROM subscription_mrr_daily
WHERE snapshot_date = CURRENT_DATE
ORDER BY snapshot_date DESC LIMIT 1;

-- Get monthly churn trend
SELECT
    DATE_TRUNC('month', snapshot_date) AS month,
    AVG(logo_churn_rate) AS avg_churn
FROM subscription_churn_daily
GROUP BY 1 ORDER BY 1;

-- Get cohort retention for specific month
SELECT * FROM subscription_cohort_retention
WHERE cohort_month = '2026-01-01'
ORDER BY months_since_signup;

-- Get at-risk subscriptions
SELECT s.*, h.health_score, h.churn_risk_score
FROM customer_subscription s
JOIN subscription_health_score h ON s.id = h.subscription_id
WHERE h.health_category IN ('at_risk', 'critical')
  AND h.score_date = CURRENT_DATE
ORDER BY h.health_score ASC;
```

---

**Document Version**: 1.0
**Last Updated**: 2026-02-04
**Next Review**: 2026-02-14
