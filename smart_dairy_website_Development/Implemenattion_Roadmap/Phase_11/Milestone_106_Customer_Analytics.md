# Milestone 106: Customer Analytics Module

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | PH11-M106-CUSTOMER-ANALYTICS |
| **Version** | 1.0 |
| **Author** | Senior Technical Architect |
| **Created** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Final |
| **Classification** | Internal Use |
| **Parent Phase** | Phase 11 - Commerce Advanced Analytics |
| **Timeline** | Days 551-560 |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 106 delivers a comprehensive Customer Analytics Module that provides deep insights into customer behavior, value, and engagement. This module implements advanced analytics including RFM (Recency, Frequency, Monetary) segmentation, Customer Lifetime Value (CLV) prediction, churn risk analysis, and customer journey mapping. These insights enable data-driven decisions for customer retention, acquisition optimization, and personalized marketing strategies.

### 1.2 Business Value Proposition

| Value Area | Expected Outcome | Measurement |
|------------|------------------|-------------|
| Customer Retention | 20-25% reduction in churn rate | Churn rate tracking |
| Revenue Growth | 15% increase from targeted campaigns | Segment performance |
| Marketing Efficiency | 30% improvement in campaign ROI | Campaign analytics |
| Customer Experience | 25% increase in NPS scores | Satisfaction metrics |
| Lifetime Value | 18% increase in average CLV | CLV trend analysis |

### 1.3 Strategic Alignment

This milestone directly supports:
- **RFP-CRM-001**: Customer 360-degree view
- **RFP-CRM-002**: Customer segmentation and targeting
- **BRD-MKT-001**: Marketing analytics and campaign optimization
- **SRS-ANALYTICS-025**: RFM segmentation analysis
- **SRS-ANALYTICS-026**: Customer lifetime value prediction
- **SRS-ANALYTICS-027**: Churn prediction modeling

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

| Category | Deliverable | Priority |
|----------|-------------|----------|
| Segmentation | RFM analysis and scoring | P0 |
| Segmentation | Behavioral clustering | P1 |
| Segmentation | Customer tier classification | P0 |
| Value Analytics | Customer Lifetime Value (CLV) calculation | P0 |
| Value Analytics | CLV prediction model | P1 |
| Value Analytics | Customer profitability analysis | P0 |
| Churn Analytics | Churn risk scoring | P0 |
| Churn Analytics | Churn prediction model | P1 |
| Churn Analytics | At-risk customer identification | P0 |
| Acquisition Analytics | Customer acquisition cost (CAC) | P0 |
| Acquisition Analytics | Acquisition channel effectiveness | P1 |
| Acquisition Analytics | Lead conversion funnel | P1 |
| Engagement Analytics | Customer activity tracking | P0 |
| Engagement Analytics | Loyalty program analytics | P1 |
| Engagement Analytics | NPS and satisfaction tracking | P1 |
| Journey Analytics | Customer journey mapping | P1 |
| Journey Analytics | Touchpoint analysis | P1 |

### 2.2 Out-of-Scope Items

- Marketing automation platform implementation
- CRM system replacement
- Customer service ticketing system
- Social media sentiment analysis
- External data enrichment services

### 2.3 Assumptions and Constraints

**Assumptions:**
- Customer transaction history is available (minimum 2 years)
- Customer master data is clean and deduplicated
- Order and sales data is integrated with customer records
- Feedback and NPS surveys are conducted regularly

**Constraints:**
- Must comply with data privacy regulations (GDPR, local laws)
- Customer PII must be anonymized in analytics views
- ML models must be explainable for business users
- Real-time scoring updates within 24 hours of transactions

---

## 3. Technical Architecture

### 3.1 Customer Analytics Data Model

```sql
-- =====================================================
-- CUSTOMER ANALYTICS STAR SCHEMA
-- Milestone 106 - Days 551-560
-- =====================================================

-- =====================================================
-- DIMENSION TABLES
-- =====================================================

-- Customer Segment Dimension
CREATE TABLE dim_customer_segment (
    segment_sk SERIAL PRIMARY KEY,
    segment_code VARCHAR(20) UNIQUE NOT NULL,
    segment_name VARCHAR(100) NOT NULL,
    segment_description TEXT,
    segment_type VARCHAR(30), -- 'rfm', 'tier', 'behavioral', 'value'
    parent_segment_sk INTEGER REFERENCES dim_customer_segment(segment_sk),
    segment_criteria TEXT,
    target_marketing_strategy TEXT,
    retention_priority INTEGER CHECK (retention_priority BETWEEN 1 AND 10),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Customer Tier Dimension
CREATE TABLE dim_customer_tier (
    tier_sk SERIAL PRIMARY KEY,
    tier_code VARCHAR(20) UNIQUE NOT NULL,
    tier_name VARCHAR(50) NOT NULL,
    tier_level INTEGER NOT NULL, -- 1=lowest, 5=highest
    min_annual_spend DECIMAL(14,2),
    max_annual_spend DECIMAL(14,2),
    min_order_frequency INTEGER,
    benefits_description TEXT,
    discount_percentage DECIMAL(5,2),
    priority_support BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Channel Dimension (for acquisition/interaction channels)
CREATE TABLE dim_interaction_channel (
    channel_sk SERIAL PRIMARY KEY,
    channel_code VARCHAR(20) UNIQUE NOT NULL,
    channel_name VARCHAR(100) NOT NULL,
    channel_type VARCHAR(30), -- 'acquisition', 'sales', 'support', 'marketing'
    channel_category VARCHAR(30), -- 'digital', 'physical', 'phone', 'partner'
    is_online BOOLEAN DEFAULT FALSE,
    cost_per_interaction DECIMAL(10,2),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Campaign Dimension
CREATE TABLE dim_campaign (
    campaign_sk SERIAL PRIMARY KEY,
    campaign_id INTEGER NOT NULL,
    campaign_code VARCHAR(30) UNIQUE NOT NULL,
    campaign_name VARCHAR(200) NOT NULL,
    campaign_type VARCHAR(50), -- 'acquisition', 'retention', 'reactivation', 'upsell', 'cross_sell'
    campaign_channel VARCHAR(50), -- 'email', 'sms', 'social', 'direct_mail', 'in_store'
    target_segment_sk INTEGER REFERENCES dim_customer_segment(segment_sk),
    start_date DATE,
    end_date DATE,
    budget DECIMAL(14,2),
    status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- FACT TABLES
-- =====================================================

-- Customer RFM Score Fact (Daily snapshot)
CREATE TABLE fact_customer_rfm (
    rfm_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    customer_sk INTEGER NOT NULL REFERENCES dim_customer(customer_sk),

    -- Recency Metrics
    days_since_last_order INTEGER,
    last_order_date DATE,
    recency_score INTEGER CHECK (recency_score BETWEEN 1 AND 5),

    -- Frequency Metrics
    order_count_365_days INTEGER,
    order_count_180_days INTEGER,
    order_count_90_days INTEGER,
    avg_days_between_orders DECIMAL(8,2),
    frequency_score INTEGER CHECK (frequency_score BETWEEN 1 AND 5),

    -- Monetary Metrics
    total_spend_365_days DECIMAL(14,2),
    total_spend_180_days DECIMAL(14,2),
    total_spend_90_days DECIMAL(14,2),
    avg_order_value DECIMAL(12,2),
    monetary_score INTEGER CHECK (monetary_score BETWEEN 1 AND 5),

    -- Combined RFM
    rfm_score VARCHAR(3), -- e.g., '555', '432'
    rfm_segment_sk INTEGER REFERENCES dim_customer_segment(segment_sk),
    rfm_composite_score INTEGER GENERATED ALWAYS AS
        (recency_score * 100 + frequency_score * 10 + monetary_score) STORED,

    -- Percentile Rankings
    recency_percentile DECIMAL(5,2),
    frequency_percentile DECIMAL(5,2),
    monetary_percentile DECIMAL(5,2),

    -- Period Changes
    rfm_score_change VARCHAR(6), -- Previous vs current, e.g., '444->432'
    segment_changed BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(date_sk, customer_sk)
);

CREATE INDEX idx_fact_rfm_date ON fact_customer_rfm(date_sk);
CREATE INDEX idx_fact_rfm_customer ON fact_customer_rfm(customer_sk);
CREATE INDEX idx_fact_rfm_segment ON fact_customer_rfm(rfm_segment_sk);
CREATE INDEX idx_fact_rfm_score ON fact_customer_rfm(rfm_score);

-- Customer Lifetime Value Fact
CREATE TABLE fact_customer_ltv (
    ltv_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    customer_sk INTEGER NOT NULL REFERENCES dim_customer(customer_sk),

    -- Historical Value
    tenure_days INTEGER,
    tenure_months INTEGER,
    total_historical_revenue DECIMAL(14,2),
    total_historical_orders INTEGER,
    total_historical_profit DECIMAL(14,2),

    -- Average Metrics
    avg_order_value DECIMAL(12,2),
    avg_orders_per_month DECIMAL(8,4),
    avg_margin_percentage DECIMAL(5,2),
    avg_monthly_revenue DECIMAL(12,2),

    -- CLV Calculations
    historical_clv DECIMAL(14,2), -- Actual value to date
    predicted_clv_1_year DECIMAL(14,2), -- Predicted next 12 months
    predicted_clv_3_year DECIMAL(14,2), -- Predicted next 36 months
    predicted_clv_lifetime DECIMAL(14,2), -- Total predicted lifetime
    clv_confidence_score DECIMAL(5,2), -- Model confidence 0-100

    -- Customer Profitability
    acquisition_cost DECIMAL(12,2),
    total_service_cost DECIMAL(12,2),
    net_customer_value DECIMAL(14,2),
    clv_to_cac_ratio DECIMAL(8,2),

    -- Value Tier
    tier_sk INTEGER REFERENCES dim_customer_tier(tier_sk),
    value_percentile DECIMAL(5,2),

    -- Trend
    clv_trend VARCHAR(20), -- 'increasing', 'stable', 'decreasing'
    clv_change_pct_30d DECIMAL(8,2),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(date_sk, customer_sk)
);

CREATE INDEX idx_fact_ltv_date ON fact_customer_ltv(date_sk);
CREATE INDEX idx_fact_ltv_customer ON fact_customer_ltv(customer_sk);
CREATE INDEX idx_fact_ltv_tier ON fact_customer_ltv(tier_sk);

-- Customer Churn Risk Fact
CREATE TABLE fact_customer_churn_risk (
    churn_risk_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    customer_sk INTEGER NOT NULL REFERENCES dim_customer(customer_sk),

    -- Activity Metrics
    days_since_last_order INTEGER,
    days_since_last_interaction INTEGER,
    orders_last_30_days INTEGER,
    orders_last_90_days INTEGER,
    spend_last_30_days DECIMAL(12,2),
    spend_last_90_days DECIMAL(12,2),

    -- Trend Indicators
    order_frequency_trend VARCHAR(20), -- 'increasing', 'stable', 'decreasing'
    spend_trend VARCHAR(20),
    engagement_trend VARCHAR(20),
    avg_order_interval_change_pct DECIMAL(8,2),

    -- Behavioral Flags
    declined_orders_count INTEGER DEFAULT 0,
    complaints_count INTEGER DEFAULT 0,
    returns_count_90_days INTEGER DEFAULT 0,
    support_tickets_open INTEGER DEFAULT 0,

    -- Churn Prediction
    churn_probability DECIMAL(5,4), -- 0.0000 to 1.0000
    churn_risk_score INTEGER CHECK (churn_risk_score BETWEEN 0 AND 100),
    churn_risk_category VARCHAR(20), -- 'Low', 'Medium', 'High', 'Critical'
    predicted_churn_date DATE,
    days_until_predicted_churn INTEGER,

    -- Model Features (Top contributors)
    top_churn_factor_1 VARCHAR(100),
    top_churn_factor_2 VARCHAR(100),
    top_churn_factor_3 VARCHAR(100),
    model_version VARCHAR(20),

    -- Intervention
    intervention_priority INTEGER CHECK (intervention_priority BETWEEN 1 AND 5),
    recommended_action VARCHAR(200),
    assigned_to INTEGER,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(date_sk, customer_sk)
);

CREATE INDEX idx_fact_churn_date ON fact_customer_churn_risk(date_sk);
CREATE INDEX idx_fact_churn_customer ON fact_customer_churn_risk(customer_sk);
CREATE INDEX idx_fact_churn_category ON fact_customer_churn_risk(churn_risk_category);

-- Customer Activity Fact
CREATE TABLE fact_customer_activity (
    activity_sk BIGSERIAL PRIMARY KEY,
    activity_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    activity_datetime TIMESTAMP NOT NULL,
    customer_sk INTEGER NOT NULL REFERENCES dim_customer(customer_sk),
    channel_sk INTEGER NOT NULL REFERENCES dim_interaction_channel(channel_sk),

    -- Activity Details
    activity_type VARCHAR(50), -- 'order', 'inquiry', 'complaint', 'feedback', 'login', 'browse'
    activity_subtype VARCHAR(50),
    activity_reference VARCHAR(100),

    -- Order-Related (if applicable)
    order_sk INTEGER,
    order_value DECIMAL(12,2),
    items_count INTEGER,

    -- Engagement Metrics
    session_duration_mins INTEGER,
    pages_viewed INTEGER,
    products_viewed INTEGER,

    -- Sentiment (if feedback/complaint)
    sentiment_score DECIMAL(3,2), -- -1 to +1
    nps_score INTEGER CHECK (nps_score BETWEEN 0 AND 10),

    -- Attribution
    campaign_sk INTEGER REFERENCES dim_campaign(campaign_sk),
    referral_source VARCHAR(100),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_activity_date ON fact_customer_activity(activity_date_sk);
CREATE INDEX idx_fact_activity_customer ON fact_customer_activity(customer_sk);
CREATE INDEX idx_fact_activity_type ON fact_customer_activity(activity_type);
CREATE INDEX idx_fact_activity_channel ON fact_customer_activity(channel_sk);

-- Customer Acquisition Fact
CREATE TABLE fact_customer_acquisition (
    acquisition_sk BIGSERIAL PRIMARY KEY,
    acquisition_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    customer_sk INTEGER NOT NULL REFERENCES dim_customer(customer_sk),
    channel_sk INTEGER NOT NULL REFERENCES dim_interaction_channel(channel_sk),
    campaign_sk INTEGER REFERENCES dim_campaign(campaign_sk),

    -- Acquisition Details
    acquisition_source VARCHAR(100),
    first_touchpoint VARCHAR(100),
    lead_source VARCHAR(100),
    referrer_customer_sk INTEGER REFERENCES dim_customer(customer_sk),

    -- Lead Journey
    lead_created_date DATE,
    first_contact_date DATE,
    conversion_date DATE,
    days_to_convert INTEGER,
    touchpoints_count INTEGER,

    -- First Order
    first_order_date DATE,
    first_order_value DECIMAL(12,2),
    first_order_items INTEGER,
    days_to_first_order INTEGER,

    -- Cost
    acquisition_cost DECIMAL(12,2),
    marketing_cost_attributed DECIMAL(12,2),
    sales_cost_attributed DECIMAL(12,2),

    -- Value Tracking
    revenue_30_days DECIMAL(12,2),
    revenue_90_days DECIMAL(12,2),
    revenue_365_days DECIMAL(12,2),
    orders_365_days INTEGER,
    cac_payback_days INTEGER,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(customer_sk)
);

CREATE INDEX idx_fact_acq_date ON fact_customer_acquisition(acquisition_date_sk);
CREATE INDEX idx_fact_acq_channel ON fact_customer_acquisition(channel_sk);
CREATE INDEX idx_fact_acq_campaign ON fact_customer_acquisition(campaign_sk);

-- Campaign Performance Fact
CREATE TABLE fact_campaign_performance (
    performance_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    campaign_sk INTEGER NOT NULL REFERENCES dim_campaign(campaign_sk),

    -- Reach Metrics
    audience_size INTEGER,
    impressions INTEGER,
    unique_reach INTEGER,

    -- Engagement Metrics
    opens INTEGER, -- For email
    clicks INTEGER,
    click_through_rate DECIMAL(5,4),
    engagement_rate DECIMAL(5,4),

    -- Conversion Metrics
    leads_generated INTEGER,
    conversions INTEGER,
    conversion_rate DECIMAL(5,4),
    orders_attributed INTEGER,
    revenue_attributed DECIMAL(14,2),

    -- Cost Metrics
    spend DECIMAL(12,2),
    cost_per_impression DECIMAL(10,4),
    cost_per_click DECIMAL(10,2),
    cost_per_lead DECIMAL(10,2),
    cost_per_acquisition DECIMAL(10,2),

    -- ROI
    roi_percentage DECIMAL(8,2),
    roas DECIMAL(8,2), -- Return on ad spend

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(date_sk, campaign_sk)
);

CREATE INDEX idx_fact_campaign_date ON fact_campaign_performance(date_sk);
CREATE INDEX idx_fact_campaign_id ON fact_campaign_performance(campaign_sk);
```

### 3.2 Customer Analytics ETL Pipeline

```python
# airflow/dags/customer_analytics_etl.py
"""
Customer Analytics ETL Pipeline
Milestone 106 - Days 551-560
Calculates customer metrics and scores
"""

from datetime import datetime, timedelta
from airflow import DAG
from airflow.operators.python import PythonOperator
from airflow.providers.postgres.hooks.postgres import PostgresHook
import pandas as pd
import numpy as np
from sklearn.preprocessing import StandardScaler
from sklearn.cluster import KMeans
from typing import Dict, Tuple
import logging

logger = logging.getLogger(__name__)

default_args = {
    'owner': 'analytics_team',
    'depends_on_past': False,
    'email': ['analytics@smartdairy.com'],
    'email_on_failure': True,
    'retries': 3,
    'retry_delay': timedelta(minutes=5),
}

dag = DAG(
    'customer_analytics_etl',
    default_args=default_args,
    description='Daily ETL for customer analytics',
    schedule_interval='0 5 * * *',  # Run at 5 AM daily
    start_date=datetime(2026, 1, 1),
    catchup=False,
    max_active_runs=1,
    tags=['customer', 'analytics', 'etl', 'phase11'],
)


class RFMCalculator:
    """Calculates RFM scores for customers."""

    def __init__(self, source_hook: PostgresHook):
        self.source_hook = source_hook

    def extract_transaction_data(self, as_of_date: datetime) -> pd.DataFrame:
        """Extract customer transaction data for RFM calculation."""
        query = """
            SELECT
                c.id AS customer_id,
                c.name AS customer_name,
                MAX(so.date_order) AS last_order_date,
                COUNT(DISTINCT so.id) AS order_count_365,
                COUNT(DISTINCT CASE
                    WHEN so.date_order >= %(date_180)s THEN so.id
                END) AS order_count_180,
                COUNT(DISTINCT CASE
                    WHEN so.date_order >= %(date_90)s THEN so.id
                END) AS order_count_90,
                SUM(so.amount_total) AS total_spend_365,
                SUM(CASE
                    WHEN so.date_order >= %(date_180)s THEN so.amount_total
                    ELSE 0
                END) AS total_spend_180,
                SUM(CASE
                    WHEN so.date_order >= %(date_90)s THEN so.amount_total
                    ELSE 0
                END) AS total_spend_90,
                AVG(so.amount_total) AS avg_order_value
            FROM res_partner c
            JOIN sale_order so ON c.id = so.partner_id
            WHERE so.state IN ('sale', 'done')
              AND so.date_order >= %(date_365)s
              AND so.date_order < %(as_of_date)s
              AND c.customer_rank > 0
            GROUP BY c.id, c.name
            HAVING COUNT(DISTINCT so.id) >= 1
        """

        params = {
            'as_of_date': as_of_date.date(),
            'date_365': (as_of_date - timedelta(days=365)).date(),
            'date_180': (as_of_date - timedelta(days=180)).date(),
            'date_90': (as_of_date - timedelta(days=90)).date()
        }

        return self.source_hook.get_pandas_df(query, parameters=params)

    def calculate_rfm_scores(
        self,
        df: pd.DataFrame,
        as_of_date: datetime
    ) -> pd.DataFrame:
        """Calculate RFM scores using quintile method."""

        if df.empty:
            return df

        # Calculate Recency (days since last order)
        df['last_order_date'] = pd.to_datetime(df['last_order_date'])
        df['days_since_last_order'] = (
            as_of_date - df['last_order_date']
        ).dt.days

        # Calculate quintiles for scoring
        # Recency: lower is better, so reverse the score
        df['recency_score'] = pd.qcut(
            df['days_since_last_order'].rank(method='first'),
            q=5,
            labels=[5, 4, 3, 2, 1]
        ).astype(int)

        # Frequency: higher is better
        df['frequency_score'] = pd.qcut(
            df['order_count_365'].rank(method='first'),
            q=5,
            labels=[1, 2, 3, 4, 5]
        ).astype(int)

        # Monetary: higher is better
        df['monetary_score'] = pd.qcut(
            df['total_spend_365'].rank(method='first'),
            q=5,
            labels=[1, 2, 3, 4, 5]
        ).astype(int)

        # Combined RFM score
        df['rfm_score'] = (
            df['recency_score'].astype(str) +
            df['frequency_score'].astype(str) +
            df['monetary_score'].astype(str)
        )

        # Calculate percentiles
        df['recency_percentile'] = df['days_since_last_order'].rank(pct=True) * 100
        df['frequency_percentile'] = df['order_count_365'].rank(pct=True) * 100
        df['monetary_percentile'] = df['total_spend_365'].rank(pct=True) * 100

        return df

    def assign_rfm_segment(self, rfm_score: str) -> str:
        """Assign customer segment based on RFM score."""
        r, f, m = int(rfm_score[0]), int(rfm_score[1]), int(rfm_score[2])

        if r >= 4 and f >= 4 and m >= 4:
            return 'Champions'
        elif r >= 3 and f >= 3 and m >= 4:
            return 'Loyal Customers'
        elif r >= 4 and f >= 1 and m >= 1:
            return 'Recent Customers'
        elif r >= 3 and f >= 3 and m >= 3:
            return 'Potential Loyalists'
        elif r >= 3 and f >= 1 and m >= 3:
            return 'Promising'
        elif r >= 2 and f >= 2 and m >= 2:
            return 'Needs Attention'
        elif r >= 2 and f >= 1 and m >= 1:
            return 'About to Sleep'
        elif r <= 2 and f >= 3 and m >= 3:
            return 'At Risk'
        elif r <= 2 and f >= 4 and m >= 4:
            return 'Cant Lose Them'
        elif r <= 2 and f <= 2 and m <= 2:
            return 'Hibernating'
        elif r <= 1 and f <= 1 and m <= 1:
            return 'Lost'
        else:
            return 'Others'


class CLVCalculator:
    """Calculates Customer Lifetime Value."""

    def __init__(self, source_hook: PostgresHook):
        self.source_hook = source_hook

    def extract_customer_history(self, as_of_date: datetime) -> pd.DataFrame:
        """Extract complete customer purchase history."""
        query = """
            WITH customer_orders AS (
                SELECT
                    c.id AS customer_id,
                    MIN(so.date_order) AS first_order_date,
                    MAX(so.date_order) AS last_order_date,
                    COUNT(DISTINCT so.id) AS total_orders,
                    SUM(so.amount_total) AS total_revenue,
                    SUM(so.margin) AS total_margin,
                    AVG(so.amount_total) AS avg_order_value
                FROM res_partner c
                JOIN sale_order so ON c.id = so.partner_id
                WHERE so.state IN ('sale', 'done')
                  AND so.date_order < %(as_of_date)s
                  AND c.customer_rank > 0
                GROUP BY c.id
            )
            SELECT
                co.*,
                EXTRACT(EPOCH FROM (%(as_of_date)s - co.first_order_date)) / 86400 AS tenure_days,
                CASE WHEN co.total_orders > 1
                    THEN EXTRACT(EPOCH FROM (co.last_order_date - co.first_order_date)) / 86400 / (co.total_orders - 1)
                    ELSE NULL
                END AS avg_order_interval_days
            FROM customer_orders co
        """

        return self.source_hook.get_pandas_df(
            query,
            parameters={'as_of_date': as_of_date.date()}
        )

    def calculate_clv(
        self,
        df: pd.DataFrame,
        discount_rate: float = 0.10,
        margin_rate: float = 0.30
    ) -> pd.DataFrame:
        """
        Calculate CLV using BG/NBD-like simplified model.

        Historical CLV = Total past revenue
        Predicted CLV = AOV * Purchase Frequency * Predicted Lifetime * Margin
        """

        if df.empty:
            return df

        # Calculate tenure in months
        df['tenure_months'] = df['tenure_days'] / 30.44

        # Calculate average monthly metrics
        df['avg_orders_per_month'] = np.where(
            df['tenure_months'] > 0,
            df['total_orders'] / df['tenure_months'],
            0
        )

        df['avg_monthly_revenue'] = np.where(
            df['tenure_months'] > 0,
            df['total_revenue'] / df['tenure_months'],
            0
        )

        # Historical CLV (actual value to date)
        df['historical_clv'] = df['total_revenue']

        # Predicted average margin
        df['avg_margin_pct'] = np.where(
            df['total_revenue'] > 0,
            df['total_margin'] / df['total_revenue'] * 100,
            margin_rate * 100
        )

        # Simplified CLV prediction
        # CLV = (AOV * Purchase Frequency * Margin) / (1 + Discount Rate - Retention Rate)
        # Using simplified retention estimation based on recency

        # Estimate retention rate based on order frequency
        df['estimated_retention'] = np.clip(
            0.5 + (df['avg_orders_per_month'] * 0.1),
            0.3, 0.95
        )

        # Monthly value
        monthly_value = df['avg_order_value'] * df['avg_orders_per_month'] * (df['avg_margin_pct'] / 100)

        # CLV formula with discount rate
        df['predicted_clv_1_year'] = monthly_value * 12 * df['estimated_retention']
        df['predicted_clv_3_year'] = monthly_value * 36 * np.power(df['estimated_retention'], 2)

        # Lifetime CLV (assuming 10 year horizon with decay)
        df['predicted_clv_lifetime'] = monthly_value * (
            df['estimated_retention'] / (1 + discount_rate - df['estimated_retention'])
        ) * 12

        # CLV confidence based on data quality
        df['clv_confidence_score'] = np.clip(
            50 + (df['total_orders'] * 2) + (df['tenure_months'] * 0.5),
            0, 100
        )

        return df


class ChurnPredictor:
    """Predicts customer churn risk."""

    def __init__(self, source_hook: PostgresHook):
        self.source_hook = source_hook

    def extract_churn_features(self, as_of_date: datetime) -> pd.DataFrame:
        """Extract features for churn prediction."""
        query = """
            WITH customer_metrics AS (
                SELECT
                    c.id AS customer_id,
                    -- Activity metrics
                    MAX(so.date_order) AS last_order_date,
                    %(as_of_date)s::DATE - MAX(so.date_order)::DATE AS days_since_last_order,
                    -- Recent activity
                    COUNT(CASE WHEN so.date_order >= %(date_30)s THEN so.id END) AS orders_30d,
                    COUNT(CASE WHEN so.date_order >= %(date_90)s THEN so.id END) AS orders_90d,
                    SUM(CASE WHEN so.date_order >= %(date_30)s THEN so.amount_total ELSE 0 END) AS spend_30d,
                    SUM(CASE WHEN so.date_order >= %(date_90)s THEN so.amount_total ELSE 0 END) AS spend_90d,
                    -- Historical baseline
                    AVG(so.amount_total) AS avg_order_value,
                    COUNT(so.id) AS total_orders,
                    -- Tenure
                    %(as_of_date)s::DATE - MIN(so.date_order)::DATE AS tenure_days
                FROM res_partner c
                JOIN sale_order so ON c.id = so.partner_id
                WHERE so.state IN ('sale', 'done')
                  AND c.customer_rank > 0
                GROUP BY c.id
            )
            SELECT
                cm.*,
                -- Trend calculations
                CASE
                    WHEN cm.orders_90d > 0 AND cm.orders_30d < (cm.orders_90d / 3.0) * 0.5 THEN 'decreasing'
                    WHEN cm.orders_90d > 0 AND cm.orders_30d > (cm.orders_90d / 3.0) * 1.5 THEN 'increasing'
                    ELSE 'stable'
                END AS order_frequency_trend,
                CASE
                    WHEN cm.spend_90d > 0 AND cm.spend_30d < (cm.spend_90d / 3.0) * 0.5 THEN 'decreasing'
                    WHEN cm.spend_90d > 0 AND cm.spend_30d > (cm.spend_90d / 3.0) * 1.5 THEN 'increasing'
                    ELSE 'stable'
                END AS spend_trend
            FROM customer_metrics cm
            WHERE cm.total_orders >= 2  -- Need at least 2 orders for churn analysis
        """

        params = {
            'as_of_date': as_of_date.date(),
            'date_30': (as_of_date - timedelta(days=30)).date(),
            'date_90': (as_of_date - timedelta(days=90)).date()
        }

        return self.source_hook.get_pandas_df(query, parameters=params)

    def calculate_churn_risk(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Calculate churn risk score using rule-based approach.
        For production, this would be replaced with ML model.
        """

        if df.empty:
            return df

        # Initialize churn score components
        df['recency_risk'] = np.clip(df['days_since_last_order'] / 90 * 30, 0, 30)

        df['frequency_risk'] = np.where(
            df['orders_30d'] == 0,
            np.where(df['orders_90d'] == 0, 30, 20),
            np.where(df['orders_30d'] < df['orders_90d'] / 3 * 0.5, 15, 0)
        )

        df['spend_risk'] = np.where(
            df['spend_30d'] == 0,
            np.where(df['spend_90d'] == 0, 25, 15),
            np.where(df['spend_30d'] < df['spend_90d'] / 3 * 0.5, 10, 0)
        )

        df['trend_risk'] = np.where(
            df['order_frequency_trend'] == 'decreasing', 15,
            np.where(df['spend_trend'] == 'decreasing', 10, 0)
        )

        # Combined churn risk score (0-100)
        df['churn_risk_score'] = np.clip(
            df['recency_risk'] + df['frequency_risk'] + df['spend_risk'] + df['trend_risk'],
            0, 100
        ).astype(int)

        # Convert to probability
        df['churn_probability'] = df['churn_risk_score'] / 100

        # Categorize risk
        df['churn_risk_category'] = pd.cut(
            df['churn_risk_score'],
            bins=[-1, 25, 50, 75, 100],
            labels=['Low', 'Medium', 'High', 'Critical']
        )

        # Identify top churn factors
        def get_top_factors(row):
            factors = []
            if row['recency_risk'] >= 20:
                factors.append(f"No orders in {row['days_since_last_order']} days")
            if row['frequency_risk'] >= 20:
                factors.append("Significant drop in order frequency")
            if row['spend_risk'] >= 15:
                factors.append("Declining spend")
            if row['trend_risk'] >= 10:
                factors.append("Negative trend in activity")
            return factors[:3]  # Top 3 factors

        factors = df.apply(get_top_factors, axis=1)
        df['top_churn_factor_1'] = factors.apply(lambda x: x[0] if len(x) > 0 else None)
        df['top_churn_factor_2'] = factors.apply(lambda x: x[1] if len(x) > 1 else None)
        df['top_churn_factor_3'] = factors.apply(lambda x: x[2] if len(x) > 2 else None)

        # Recommended action
        df['recommended_action'] = np.where(
            df['churn_risk_category'] == 'Critical', 'Immediate personal outreach',
            np.where(
                df['churn_risk_category'] == 'High', 'Send win-back campaign',
                np.where(
                    df['churn_risk_category'] == 'Medium', 'Include in retention program',
                    'Monitor engagement'
                )
            )
        )

        return df


# =============================================================================
# AIRFLOW TASK DEFINITIONS
# =============================================================================

def calculate_rfm_scores(**context):
    """Calculate daily RFM scores for all customers."""
    execution_date = context['execution_date']
    source_hook = PostgresHook(postgres_conn_id='odoo_postgres')
    target_hook = PostgresHook(postgres_conn_id='analytics_dw')

    calculator = RFMCalculator(source_hook)

    # Extract and calculate
    df = calculator.extract_transaction_data(execution_date)
    df = calculator.calculate_rfm_scores(df, execution_date)

    # Assign segments
    df['rfm_segment'] = df['rfm_score'].apply(calculator.assign_rfm_segment)

    logger.info(f"Calculated RFM scores for {len(df)} customers")

    # Store for next task
    context['ti'].xcom_push(key='rfm_data', value=df.to_json())

    return {'status': 'success', 'customers_processed': len(df)}


def calculate_customer_ltv(**context):
    """Calculate CLV for all customers."""
    execution_date = context['execution_date']
    source_hook = PostgresHook(postgres_conn_id='odoo_postgres')

    calculator = CLVCalculator(source_hook)

    df = calculator.extract_customer_history(execution_date)
    df = calculator.calculate_clv(df)

    logger.info(f"Calculated CLV for {len(df)} customers")

    context['ti'].xcom_push(key='clv_data', value=df.to_json())

    return {'status': 'success', 'customers_processed': len(df)}


def calculate_churn_risk(**context):
    """Calculate churn risk scores."""
    execution_date = context['execution_date']
    source_hook = PostgresHook(postgres_conn_id='odoo_postgres')

    predictor = ChurnPredictor(source_hook)

    df = predictor.extract_churn_features(execution_date)
    df = predictor.calculate_churn_risk(df)

    logger.info(f"Calculated churn risk for {len(df)} customers")

    # Log high-risk customers
    high_risk = df[df['churn_risk_category'].isin(['High', 'Critical'])]
    logger.warning(f"Found {len(high_risk)} high/critical risk customers")

    context['ti'].xcom_push(key='churn_data', value=df.to_json())

    return {'status': 'success', 'customers_processed': len(df), 'high_risk_count': len(high_risk)}


def load_customer_analytics(**context):
    """Load all customer analytics data to data warehouse."""
    ti = context['ti']
    execution_date = context['execution_date']
    target_hook = PostgresHook(postgres_conn_id='analytics_dw')

    # Load RFM data
    rfm_df = pd.read_json(ti.xcom_pull(key='rfm_data'))
    # ... loading logic ...

    logger.info("Customer analytics data loaded successfully")

    return {'status': 'success'}


# Task definitions
with dag:
    rfm_task = PythonOperator(
        task_id='calculate_rfm_scores',
        python_callable=calculate_rfm_scores,
    )

    clv_task = PythonOperator(
        task_id='calculate_customer_ltv',
        python_callable=calculate_customer_ltv,
    )

    churn_task = PythonOperator(
        task_id='calculate_churn_risk',
        python_callable=calculate_churn_risk,
    )

    load_task = PythonOperator(
        task_id='load_customer_analytics',
        python_callable=load_customer_analytics,
    )

    # Run RFM, CLV, and Churn in parallel, then load
    [rfm_task, clv_task, churn_task] >> load_task
```

---

## 4. Analytics Dashboards

### 4.1 Customer Segmentation Dashboard

```sql
-- =====================================================
-- CUSTOMER SEGMENTATION ANALYTICS
-- RFM and Behavioral Segmentation
-- =====================================================

-- 4.1.1 RFM Segment Summary
CREATE OR REPLACE VIEW vw_rfm_segment_summary AS
SELECT
    cs.segment_name AS rfm_segment,
    COUNT(DISTINCT fr.customer_sk) AS customer_count,
    ROUND(COUNT(DISTINCT fr.customer_sk)::DECIMAL /
          SUM(COUNT(DISTINCT fr.customer_sk)) OVER () * 100, 1) AS pct_of_customers,
    SUM(fr.total_spend_365_days) AS total_revenue_365,
    ROUND(AVG(fr.total_spend_365_days), 2) AS avg_revenue_per_customer,
    ROUND(AVG(fr.order_count_365_days), 1) AS avg_orders_per_customer,
    ROUND(AVG(fr.avg_order_value), 2) AS avg_order_value,
    ROUND(AVG(fr.days_since_last_order), 0) AS avg_days_since_last_order,
    -- Segment health indicators
    COUNT(CASE WHEN fr.days_since_last_order <= 30 THEN 1 END) AS active_last_30_days,
    COUNT(CASE WHEN fr.days_since_last_order > 90 THEN 1 END) AS inactive_over_90_days,
    -- Segment value tier
    CASE
        WHEN cs.segment_name IN ('Champions', 'Loyal Customers') THEN 'High Value'
        WHEN cs.segment_name IN ('Potential Loyalists', 'Recent Customers', 'Promising') THEN 'Growth Potential'
        WHEN cs.segment_name IN ('Needs Attention', 'About to Sleep') THEN 'At Risk'
        ELSE 'Recovery/Lost'
    END AS value_tier
FROM fact_customer_rfm fr
JOIN dim_customer_segment cs ON fr.rfm_segment_sk = cs.segment_sk
JOIN dim_date d ON fr.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
GROUP BY cs.segment_sk, cs.segment_name
ORDER BY total_revenue_365 DESC;

-- 4.1.2 RFM Score Distribution
CREATE OR REPLACE VIEW vw_rfm_score_distribution AS
SELECT
    fr.rfm_score,
    fr.recency_score,
    fr.frequency_score,
    fr.monetary_score,
    cs.segment_name,
    COUNT(DISTINCT fr.customer_sk) AS customer_count,
    SUM(fr.total_spend_365_days) AS total_revenue,
    AVG(fr.avg_order_value) AS avg_aov,
    -- Heatmap coordinates
    fr.recency_score AS r_axis,
    fr.frequency_score AS f_axis,
    SUM(fr.total_spend_365_days) AS heat_value
FROM fact_customer_rfm fr
LEFT JOIN dim_customer_segment cs ON fr.rfm_segment_sk = cs.segment_sk
JOIN dim_date d ON fr.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
GROUP BY fr.rfm_score, fr.recency_score, fr.frequency_score, fr.monetary_score, cs.segment_name
ORDER BY fr.rfm_score DESC;

-- 4.1.3 Segment Migration Analysis
CREATE OR REPLACE VIEW vw_segment_migration AS
WITH current_segments AS (
    SELECT customer_sk, rfm_segment_sk, rfm_score
    FROM fact_customer_rfm fr
    JOIN dim_date d ON fr.date_sk = d.date_sk
    WHERE d.date_actual = CURRENT_DATE
),
previous_segments AS (
    SELECT customer_sk, rfm_segment_sk, rfm_score
    FROM fact_customer_rfm fr
    JOIN dim_date d ON fr.date_sk = d.date_sk
    WHERE d.date_actual = CURRENT_DATE - INTERVAL '30 days'
)
SELECT
    ps_seg.segment_name AS from_segment,
    cs_seg.segment_name AS to_segment,
    COUNT(*) AS customers_migrated,
    -- Migration direction
    CASE
        WHEN cs_seg.retention_priority < ps_seg.retention_priority THEN 'Upgraded'
        WHEN cs_seg.retention_priority > ps_seg.retention_priority THEN 'Downgraded'
        ELSE 'Same Level'
    END AS migration_direction
FROM current_segments cs
JOIN previous_segments ps ON cs.customer_sk = ps.customer_sk
JOIN dim_customer_segment cs_seg ON cs.rfm_segment_sk = cs_seg.segment_sk
JOIN dim_customer_segment ps_seg ON ps.rfm_segment_sk = ps_seg.segment_sk
WHERE cs.rfm_segment_sk != ps.rfm_segment_sk
GROUP BY ps_seg.segment_name, cs_seg.segment_name, ps_seg.retention_priority, cs_seg.retention_priority
ORDER BY customers_migrated DESC;
```

### 4.2 Customer Value Dashboard

```sql
-- =====================================================
-- CUSTOMER LIFETIME VALUE ANALYTICS
-- CLV and Profitability Analysis
-- =====================================================

-- 4.2.1 CLV Tier Summary
CREATE OR REPLACE VIEW vw_clv_tier_summary AS
SELECT
    ct.tier_name,
    ct.tier_level,
    COUNT(DISTINCT fl.customer_sk) AS customer_count,
    ROUND(COUNT(DISTINCT fl.customer_sk)::DECIMAL /
          SUM(COUNT(DISTINCT fl.customer_sk)) OVER () * 100, 1) AS pct_of_customers,
    SUM(fl.historical_clv) AS total_historical_value,
    SUM(fl.predicted_clv_lifetime) AS total_predicted_value,
    ROUND(AVG(fl.historical_clv), 2) AS avg_historical_clv,
    ROUND(AVG(fl.predicted_clv_lifetime), 2) AS avg_predicted_clv,
    ROUND(AVG(fl.clv_to_cac_ratio), 2) AS avg_clv_cac_ratio,
    ROUND(AVG(fl.tenure_months), 1) AS avg_tenure_months,
    -- Value concentration
    ROUND(SUM(fl.historical_clv) /
          SUM(SUM(fl.historical_clv)) OVER () * 100, 1) AS pct_of_total_value
FROM fact_customer_ltv fl
JOIN dim_customer_tier ct ON fl.tier_sk = ct.tier_sk
JOIN dim_date d ON fl.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
GROUP BY ct.tier_sk, ct.tier_name, ct.tier_level
ORDER BY ct.tier_level DESC;

-- 4.2.2 CLV Distribution Analysis
CREATE OR REPLACE VIEW vw_clv_distribution AS
SELECT
    CASE
        WHEN fl.predicted_clv_lifetime < 1000 THEN 'Under $1K'
        WHEN fl.predicted_clv_lifetime < 5000 THEN '$1K - $5K'
        WHEN fl.predicted_clv_lifetime < 10000 THEN '$5K - $10K'
        WHEN fl.predicted_clv_lifetime < 25000 THEN '$10K - $25K'
        WHEN fl.predicted_clv_lifetime < 50000 THEN '$25K - $50K'
        ELSE 'Over $50K'
    END AS clv_bucket,
    COUNT(DISTINCT fl.customer_sk) AS customer_count,
    SUM(fl.historical_clv) AS total_historical_value,
    SUM(fl.predicted_clv_lifetime) AS total_predicted_value,
    AVG(fl.avg_order_value) AS avg_aov,
    AVG(fl.avg_orders_per_month) AS avg_monthly_orders,
    AVG(fl.tenure_months) AS avg_tenure_months
FROM fact_customer_ltv fl
JOIN dim_date d ON fl.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
GROUP BY
    CASE
        WHEN fl.predicted_clv_lifetime < 1000 THEN 'Under $1K'
        WHEN fl.predicted_clv_lifetime < 5000 THEN '$1K - $5K'
        WHEN fl.predicted_clv_lifetime < 10000 THEN '$5K - $10K'
        WHEN fl.predicted_clv_lifetime < 25000 THEN '$10K - $25K'
        WHEN fl.predicted_clv_lifetime < 50000 THEN '$25K - $50K'
        ELSE 'Over $50K'
    END
ORDER BY MIN(fl.predicted_clv_lifetime);

-- 4.2.3 Top Customers by CLV
CREATE OR REPLACE VIEW vw_top_customers_clv AS
SELECT
    c.customer_code,
    c.customer_name,
    ct.tier_name,
    cs.segment_name AS rfm_segment,
    fl.tenure_months,
    fl.total_historical_orders,
    fl.historical_clv,
    fl.predicted_clv_lifetime,
    fl.avg_order_value,
    fl.avg_orders_per_month,
    fl.clv_to_cac_ratio,
    fl.clv_trend,
    fl.value_percentile,
    -- Risk indicator from churn
    fcr.churn_risk_category,
    fcr.churn_risk_score
FROM fact_customer_ltv fl
JOIN dim_customer c ON fl.customer_sk = c.customer_sk
JOIN dim_customer_tier ct ON fl.tier_sk = ct.tier_sk
LEFT JOIN fact_customer_rfm fr ON fl.customer_sk = fr.customer_sk AND fl.date_sk = fr.date_sk
LEFT JOIN dim_customer_segment cs ON fr.rfm_segment_sk = cs.segment_sk
LEFT JOIN fact_customer_churn_risk fcr ON fl.customer_sk = fcr.customer_sk AND fl.date_sk = fcr.date_sk
JOIN dim_date d ON fl.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
ORDER BY fl.predicted_clv_lifetime DESC
LIMIT 100;
```

### 4.3 Churn Analytics Dashboard

```sql
-- =====================================================
-- CHURN RISK ANALYTICS
-- At-Risk Customer Identification
-- =====================================================

-- 4.3.1 Churn Risk Overview
CREATE OR REPLACE VIEW vw_churn_risk_overview AS
SELECT
    fcr.churn_risk_category,
    COUNT(DISTINCT fcr.customer_sk) AS customer_count,
    ROUND(COUNT(DISTINCT fcr.customer_sk)::DECIMAL /
          SUM(COUNT(DISTINCT fcr.customer_sk)) OVER () * 100, 1) AS pct_of_customers,
    -- Value at risk
    SUM(fl.predicted_clv_lifetime) AS total_clv_at_risk,
    AVG(fl.predicted_clv_lifetime) AS avg_clv_at_risk,
    -- Activity metrics
    AVG(fcr.days_since_last_order) AS avg_days_inactive,
    AVG(fcr.orders_last_90_days) AS avg_orders_90d,
    AVG(fcr.spend_last_90_days) AS avg_spend_90d,
    -- Trend breakdown
    COUNT(CASE WHEN fcr.order_frequency_trend = 'decreasing' THEN 1 END) AS declining_frequency,
    COUNT(CASE WHEN fcr.spend_trend = 'decreasing' THEN 1 END) AS declining_spend
FROM fact_customer_churn_risk fcr
LEFT JOIN fact_customer_ltv fl ON fcr.customer_sk = fl.customer_sk AND fcr.date_sk = fl.date_sk
JOIN dim_date d ON fcr.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
GROUP BY fcr.churn_risk_category
ORDER BY
    CASE fcr.churn_risk_category
        WHEN 'Critical' THEN 1
        WHEN 'High' THEN 2
        WHEN 'Medium' THEN 3
        ELSE 4
    END;

-- 4.3.2 At-Risk Customer List
CREATE OR REPLACE VIEW vw_at_risk_customers AS
SELECT
    c.customer_code,
    c.customer_name,
    c.email,
    ct.tier_name AS value_tier,
    fcr.churn_risk_category,
    fcr.churn_risk_score,
    fcr.churn_probability,
    fcr.days_since_last_order,
    fcr.orders_last_30_days,
    fcr.orders_last_90_days,
    fcr.spend_last_90_days,
    fcr.order_frequency_trend,
    fcr.spend_trend,
    fcr.top_churn_factor_1,
    fcr.top_churn_factor_2,
    fcr.top_churn_factor_3,
    fcr.recommended_action,
    fl.predicted_clv_lifetime AS value_at_risk,
    fl.historical_clv,
    fcr.intervention_priority,
    -- Contact info for outreach
    c.phone,
    c.city
FROM fact_customer_churn_risk fcr
JOIN dim_customer c ON fcr.customer_sk = c.customer_sk
LEFT JOIN fact_customer_ltv fl ON fcr.customer_sk = fl.customer_sk AND fcr.date_sk = fl.date_sk
LEFT JOIN dim_customer_tier ct ON fl.tier_sk = ct.tier_sk
JOIN dim_date d ON fcr.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
  AND fcr.churn_risk_category IN ('High', 'Critical')
ORDER BY fcr.intervention_priority ASC, fl.predicted_clv_lifetime DESC;

-- 4.3.3 Churn Factor Analysis
CREATE OR REPLACE VIEW vw_churn_factor_analysis AS
SELECT
    factor_name,
    COUNT(*) AS occurrence_count,
    ROUND(COUNT(*)::DECIMAL / SUM(COUNT(*)) OVER () * 100, 1) AS pct_of_total,
    AVG(fcr.churn_risk_score) AS avg_risk_score_when_present,
    SUM(fl.predicted_clv_lifetime) AS total_clv_affected
FROM (
    SELECT customer_sk, date_sk, top_churn_factor_1 AS factor_name FROM fact_customer_churn_risk WHERE top_churn_factor_1 IS NOT NULL
    UNION ALL
    SELECT customer_sk, date_sk, top_churn_factor_2 FROM fact_customer_churn_risk WHERE top_churn_factor_2 IS NOT NULL
    UNION ALL
    SELECT customer_sk, date_sk, top_churn_factor_3 FROM fact_customer_churn_risk WHERE top_churn_factor_3 IS NOT NULL
) factors
JOIN fact_customer_churn_risk fcr ON factors.customer_sk = fcr.customer_sk AND factors.date_sk = fcr.date_sk
LEFT JOIN fact_customer_ltv fl ON fcr.customer_sk = fl.customer_sk AND fcr.date_sk = fl.date_sk
JOIN dim_date d ON factors.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
GROUP BY factor_name
ORDER BY occurrence_count DESC;
```

### 4.4 Customer Acquisition Analytics

```sql
-- =====================================================
-- CUSTOMER ACQUISITION ANALYTICS
-- CAC, Channel Performance, Conversion
-- =====================================================

-- 4.4.1 Acquisition Channel Performance
CREATE OR REPLACE VIEW vw_acquisition_channel_performance AS
SELECT
    ic.channel_name,
    ic.channel_category,
    d.month_name,
    d.year_actual,
    COUNT(DISTINCT fa.customer_sk) AS customers_acquired,
    SUM(fa.acquisition_cost) AS total_acquisition_cost,
    AVG(fa.acquisition_cost) AS avg_cac,
    AVG(fa.days_to_convert) AS avg_days_to_convert,
    AVG(fa.first_order_value) AS avg_first_order_value,
    -- 365-day value
    SUM(fa.revenue_365_days) AS total_revenue_365,
    AVG(fa.revenue_365_days) AS avg_revenue_365,
    -- ROI
    ROUND(
        (SUM(fa.revenue_365_days) - SUM(fa.acquisition_cost)) /
        NULLIF(SUM(fa.acquisition_cost), 0) * 100, 1
    ) AS acquisition_roi_pct,
    -- Payback
    AVG(fa.cac_payback_days) AS avg_cac_payback_days
FROM fact_customer_acquisition fa
JOIN dim_interaction_channel ic ON fa.channel_sk = ic.channel_sk
JOIN dim_date d ON fa.acquisition_date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '12 months'
GROUP BY ic.channel_name, ic.channel_category, d.month_name, d.year_actual, d.month_of_year
ORDER BY d.year_actual DESC, d.month_of_year DESC, customers_acquired DESC;

-- 4.4.2 Acquisition Funnel
CREATE OR REPLACE VIEW vw_acquisition_funnel AS
WITH funnel_data AS (
    SELECT
        d.week_of_year,
        d.year_actual,
        ic.channel_name,
        COUNT(DISTINCT fa.customer_sk) AS leads,
        COUNT(DISTINCT CASE WHEN fa.first_contact_date IS NOT NULL THEN fa.customer_sk END) AS contacted,
        COUNT(DISTINCT CASE WHEN fa.conversion_date IS NOT NULL THEN fa.customer_sk END) AS converted,
        COUNT(DISTINCT CASE WHEN fa.first_order_date IS NOT NULL THEN fa.customer_sk END) AS ordered
    FROM fact_customer_acquisition fa
    JOIN dim_interaction_channel ic ON fa.channel_sk = ic.channel_sk
    JOIN dim_date d ON fa.acquisition_date_sk = d.date_sk
    WHERE d.date_actual >= CURRENT_DATE - INTERVAL '90 days'
    GROUP BY d.week_of_year, d.year_actual, ic.channel_name
)
SELECT
    year_actual,
    week_of_year,
    channel_name,
    leads,
    contacted,
    converted,
    ordered,
    ROUND(contacted::DECIMAL / NULLIF(leads, 0) * 100, 1) AS contact_rate,
    ROUND(converted::DECIMAL / NULLIF(contacted, 0) * 100, 1) AS conversion_rate,
    ROUND(ordered::DECIMAL / NULLIF(converted, 0) * 100, 1) AS order_rate,
    ROUND(ordered::DECIMAL / NULLIF(leads, 0) * 100, 1) AS overall_conversion_rate
FROM funnel_data
ORDER BY year_actual DESC, week_of_year DESC, leads DESC;

-- 4.4.3 Cohort Analysis
CREATE OR REPLACE VIEW vw_customer_cohort_analysis AS
WITH cohorts AS (
    SELECT
        DATE_TRUNC('month', d.date_actual) AS cohort_month,
        fa.customer_sk
    FROM fact_customer_acquisition fa
    JOIN dim_date d ON fa.acquisition_date_sk = d.date_sk
),
customer_activity AS (
    SELECT
        c.cohort_month,
        c.customer_sk,
        DATE_TRUNC('month', d.date_actual) AS activity_month,
        SUM(fs.revenue) AS monthly_revenue
    FROM cohorts c
    JOIN fact_sales fs ON c.customer_sk = fs.customer_sk
    JOIN dim_date d ON fs.date_sk = d.date_sk
    GROUP BY c.cohort_month, c.customer_sk, DATE_TRUNC('month', d.date_actual)
)
SELECT
    cohort_month,
    EXTRACT(MONTH FROM activity_month) - EXTRACT(MONTH FROM cohort_month) +
    (EXTRACT(YEAR FROM activity_month) - EXTRACT(YEAR FROM cohort_month)) * 12 AS months_since_acquisition,
    COUNT(DISTINCT customer_sk) AS active_customers,
    SUM(monthly_revenue) AS cohort_revenue,
    AVG(monthly_revenue) AS avg_revenue_per_customer
FROM customer_activity
WHERE activity_month >= cohort_month
GROUP BY cohort_month, activity_month
ORDER BY cohort_month, months_since_acquisition;
```

---

## 5. Daily Development Schedule

### Day 551: Customer Data Model Design

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Design customer analytics star schema | Schema documentation |
| 09:00-12:00 | Dev 2 | Review Odoo CRM/sales data structures | Data mapping |
| 09:00-12:00 | Dev 3 | Design customer dashboard wireframes | Figma prototypes |
| 13:00-15:00 | Dev 1 | Create segment and tier dimensions | DDL scripts |
| 13:00-15:00 | Dev 2 | Create channel and campaign dimensions | DDL scripts |
| 13:00-15:00 | Dev 3 | Design RFM visualization components | UI specifications |
| 15:00-17:00 | All | Schema review and refinement | Approved schema |

### Day 552: Fact Tables and RFM Implementation

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create RFM fact table | fact_customer_rfm |
| 09:00-12:00 | Dev 2 | Create CLV fact table | fact_customer_ltv |
| 09:00-12:00 | Dev 3 | Create churn risk fact table | fact_customer_churn_risk |
| 13:00-15:00 | Dev 1 | Implement RFM calculation logic | RFMCalculator class |
| 13:00-15:00 | Dev 2 | Create activity and acquisition facts | Fact tables |
| 13:00-15:00 | Dev 3 | Create campaign performance fact | fact_campaign_performance |
| 15:00-17:00 | Dev 1 | Implement RFM scoring algorithm | Scoring logic |
| 15:00-17:00 | Dev 2 | Implement segment assignment logic | Segmentation logic |
| 15:00-17:00 | Dev 3 | Build RFM heatmap visualization | Heatmap component |

### Day 553: CLV and Churn Model Development

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Implement CLV calculation model | CLVCalculator class |
| 09:00-12:00 | Dev 2 | Implement churn prediction model | ChurnPredictor class |
| 09:00-12:00 | Dev 3 | Build CLV dashboard in Superset | CLV dashboard |
| 13:00-15:00 | Dev 1 | Add CLV prediction logic | Prediction algorithm |
| 13:00-15:00 | Dev 2 | Add churn risk scoring | Risk scoring logic |
| 13:00-15:00 | Dev 3 | Build churn risk dashboard | Churn dashboard |
| 15:00-17:00 | Dev 1 | Implement tier assignment logic | Tier logic |
| 15:00-17:00 | Dev 2 | Add churn factor identification | Factor analysis |
| 15:00-17:00 | Dev 3 | Build at-risk customer list UI | List component |

### Day 554: ETL Pipeline Development

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Design customer analytics ETL DAG | DAG architecture |
| 09:00-12:00 | Dev 2 | Implement customer data extractors | Extractor classes |
| 09:00-12:00 | Dev 3 | Create ETL monitoring dashboard | Monitoring UI |
| 13:00-15:00 | Dev 1 | Implement RFM ETL task | RFM task |
| 13:00-15:00 | Dev 2 | Implement CLV ETL task | CLV task |
| 13:00-15:00 | Dev 3 | Implement churn ETL task | Churn task |
| 15:00-17:00 | Dev 1 | Configure parallel execution | DAG optimization |
| 15:00-17:00 | Dev 2 | Add data quality validations | DQ checks |
| 15:00-17:00 | Dev 3 | Add ETL error handling | Error handlers |

### Day 555: Segmentation Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create RFM segment summary view | vw_rfm_segment_summary |
| 09:00-12:00 | Dev 2 | Create segment migration view | vw_segment_migration |
| 09:00-12:00 | Dev 3 | Build segmentation dashboard | Segment dashboard |
| 13:00-15:00 | Dev 1 | Create RFM distribution view | vw_rfm_score_distribution |
| 13:00-15:00 | Dev 2 | Implement behavioral clustering | Clustering model |
| 13:00-15:00 | Dev 3 | Build segment comparison charts | Comparison charts |
| 15:00-17:00 | Dev 1 | Add segment trend tracking | Trend analysis |
| 15:00-17:00 | Dev 2 | Create segment recommendation engine | Recommendations |
| 15:00-17:00 | Dev 3 | Add segment drill-down functionality | Drill-down UI |

### Day 556: Value and Acquisition Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create CLV tier summary view | vw_clv_tier_summary |
| 09:00-12:00 | Dev 2 | Create acquisition channel view | vw_acquisition_channel_performance |
| 09:00-12:00 | Dev 3 | Build customer value dashboard | Value dashboard |
| 13:00-15:00 | Dev 1 | Create CLV distribution view | vw_clv_distribution |
| 13:00-15:00 | Dev 2 | Create acquisition funnel view | vw_acquisition_funnel |
| 13:00-15:00 | Dev 3 | Build acquisition analytics dashboard | Acquisition dashboard |
| 15:00-17:00 | Dev 1 | Create top customers view | vw_top_customers_clv |
| 15:00-17:00 | Dev 2 | Create cohort analysis view | vw_customer_cohort_analysis |
| 15:00-17:00 | Dev 3 | Build cohort visualization | Cohort charts |

### Day 557: Churn and Activity Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create churn risk overview view | vw_churn_risk_overview |
| 09:00-12:00 | Dev 2 | Create churn factor analysis view | vw_churn_factor_analysis |
| 09:00-12:00 | Dev 3 | Enhance churn risk dashboard | Churn dashboard v2 |
| 13:00-15:00 | Dev 1 | Create at-risk customers view | vw_at_risk_customers |
| 13:00-15:00 | Dev 2 | Create customer activity views | Activity views |
| 13:00-15:00 | Dev 3 | Build intervention priority list | Priority list UI |
| 15:00-17:00 | Dev 1 | Implement churn alerts | Alert configuration |
| 15:00-17:00 | Dev 2 | Add customer journey tracking | Journey tracking |
| 15:00-17:00 | Dev 3 | Build activity timeline component | Timeline UI |

### Day 558: Integration and API

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create customer analytics API | REST API endpoints |
| 09:00-12:00 | Dev 2 | Integrate with Odoo CRM module | Odoo integration |
| 09:00-12:00 | Dev 3 | Add export functionality | Export features |
| 13:00-15:00 | Dev 1 | Implement customer 360 API | 360-degree view API |
| 13:00-15:00 | Dev 2 | Add webhook for score updates | Webhook setup |
| 13:00-15:00 | Dev 3 | Build customer profile card | Profile UI |
| 15:00-17:00 | Dev 1 | Add real-time score updates | Real-time sync |
| 15:00-17:00 | Dev 2 | Integrate with marketing tools | Marketing integration |
| 15:00-17:00 | Dev 3 | Mobile-responsive optimization | Mobile CSS |

### Day 559: Optimization and Performance

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Query optimization | Optimized queries |
| 09:00-12:00 | Dev 2 | Add materialized view refresh | MV scheduling |
| 09:00-12:00 | Dev 3 | Implement dashboard caching | Redis caching |
| 13:00-15:00 | Dev 1 | Index tuning | Index optimization |
| 13:00-15:00 | Dev 2 | Add data anonymization | PII protection |
| 13:00-15:00 | Dev 3 | Performance monitoring | Performance metrics |
| 15:00-17:00 | All | Load testing | Performance report |

### Day 560: Testing and Documentation

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Unit tests for analytics models | 90%+ coverage |
| 09:00-12:00 | Dev 2 | Integration tests | Integration test suite |
| 09:00-12:00 | Dev 3 | UI/UX testing | Bug fixes |
| 13:00-15:00 | Dev 1 | Model accuracy validation | Validation report |
| 13:00-15:00 | Dev 2 | Edge case testing | Edge case coverage |
| 13:00-15:00 | Dev 3 | UAT support | UAT documentation |
| 15:00-17:00 | All | Documentation and sign-off | Technical docs |

---

## 6. Requirement Traceability Matrix

| Requirement ID | Requirement Description | Implementation | Test Case |
|----------------|------------------------|----------------|-----------|
| RFP-CRM-001 | Customer 360-degree view | Customer Profile API, Value Dashboard | TC-106-001 |
| RFP-CRM-002 | Customer segmentation | RFM Analysis, Segment Dashboard | TC-106-002 |
| BRD-MKT-001 | Marketing analytics | Campaign Performance, Acquisition Analytics | TC-106-003 |
| SRS-ANALYTICS-025 | RFM segmentation | fact_customer_rfm, RFMCalculator | TC-106-004 |
| SRS-ANALYTICS-026 | Customer lifetime value | fact_customer_ltv, CLVCalculator | TC-106-005 |
| SRS-ANALYTICS-027 | Churn prediction | fact_customer_churn_risk, ChurnPredictor | TC-106-006 |
| SRS-ANALYTICS-028 | Acquisition analytics | fact_customer_acquisition, Channel Performance | TC-106-007 |

---

## 7. Risk Register

| Risk ID | Description | Probability | Impact | Mitigation |
|---------|-------------|-------------|--------|------------|
| R106-001 | Insufficient customer history | Medium | High | Synthetic data generation, minimum tenure filters |
| R106-002 | CLV model accuracy | Medium | Medium | Regular model retraining, confidence scores |
| R106-003 | Churn prediction false positives | Medium | Medium | Threshold tuning, human validation workflow |
| R106-004 | Data privacy compliance | Low | High | PII anonymization, access controls |
| R106-005 | Duplicate customer records | Medium | Medium | Deduplication logic in ETL |

---

## 8. Quality Assurance Checklist

- [ ] RFM scores calculating correctly
- [ ] CLV predictions validated against actuals
- [ ] Churn predictions backtested
- [ ] PII properly anonymized
- [ ] All dashboards load within 3 seconds
- [ ] API endpoints documented and tested
- [ ] Mobile views functional
- [ ] Data refresh schedules operational

---

**Document End**

*Milestone 106: Customer Analytics Module - Days 551-560*
