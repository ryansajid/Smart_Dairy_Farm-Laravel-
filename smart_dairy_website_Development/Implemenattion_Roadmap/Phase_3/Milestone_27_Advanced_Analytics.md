# Milestone 27: Advanced Analytics & Business Intelligence

## Smart Dairy Digital Smart Portal + ERP Implementation

### Days 261-270 | Phase 3 Part B: Advanced Features & Optimization

---

## Document Control

| Attribute | Value |
|-----------|-------|
| Document ID | SD-P3-MS27-001 |
| Version | 1.0 |
| Last Updated | 2025-01-15 |
| Status | Implementation Ready |
| Owner | Dev 2 (Full-Stack) |
| Reviewers | Dev 1, Dev 3, Project Manager |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Build comprehensive analytics and business intelligence platform enabling data-driven decision making through real-time dashboards, predictive analytics, and automated reporting for the Smart Dairy operations.**

### 1.2 Objectives

| # | Objective | Priority | Success Measure |
|---|-----------|----------|-----------------|
| 1 | Design data warehouse schema | Critical | Star schema implemented |
| 2 | Implement sales analytics | Critical | Revenue trends visible |
| 3 | Build production performance tracking | Critical | Yield metrics operational |
| 4 | Create inventory turnover analysis | High | Stock insights available |
| 5 | Develop customer lifetime value (CLV) | High | CLV calculations accurate |
| 6 | Build farm productivity dashboards | High | Farm KPIs displayed |
| 7 | Implement demand forecasting | Medium | 85%+ accuracy achieved |
| 8 | Create automated report scheduler | Medium | Reports auto-delivered |
| 9 | Build executive dashboard | High | C-level insights ready |
| 10 | Mobile analytics integration | Medium | Analytics in mobile app |

### 1.3 Key Deliverables

| Deliverable | Owner | Day | Acceptance Criteria |
|-------------|-------|-----|---------------------|
| Data Warehouse Schema | Dev 1 | 261-262 | Star schema deployed |
| ETL Pipeline | Dev 1 | 262-263 | Data loading automated |
| Sales Analytics Module | Dev 2 | 263-265 | Revenue dashboards live |
| Production Analytics | Dev 2 | 265-266 | Yield tracking operational |
| Customer Analytics | Dev 2 | 266-267 | CLV and segments visible |
| Farm Analytics | Dev 1 | 267-268 | Productivity metrics live |
| Forecasting Engine | Dev 1 | 268-269 | Predictions generated |
| Report Scheduler | Dev 2 | 269-270 | Auto-reports working |
| Mobile Analytics | Dev 3 | 266-269 | Charts in mobile app |
| Executive Dashboard | Dev 2 | 270 | C-level view complete |

### 1.4 Prerequisites

| Prerequisite | Source | Status |
|--------------|--------|--------|
| TimescaleDB operational | Milestone 26 | Required |
| IoT data flowing | Milestone 26 | Required |
| Sales order data | Phase 2 | Required |
| Production records | Phase 2 | Required |
| Customer profiles | Phase 2 | Required |

### 1.5 Success Criteria

- [ ] Data warehouse with 90-day historical data loaded
- [ ] Sales dashboards showing real-time and trend data
- [ ] Production efficiency metrics calculated automatically
- [ ] Customer segmentation with CLV scores
- [ ] Demand forecasting with >85% accuracy
- [ ] Automated weekly/monthly reports delivered
- [ ] Mobile analytics charts rendering <2 seconds

---

## 2. Requirements Traceability Matrix

### 2.1 BRD Requirements Mapping

| BRD Req ID | Requirement Description | Implementation | Day |
|------------|------------------------|----------------|-----|
| FR-B2B-024 | Advanced reporting and analytics | BI dashboards | 263-270 |
| REQ-RPT-001 | Sales performance reports | Sales analytics | 263-265 |
| REQ-RPT-002 | Production efficiency reports | Production module | 265-266 |
| REQ-RPT-003 | Inventory analysis | Turnover analysis | 266-267 |
| REQ-RPT-004 | Customer insights | CLV analytics | 266-267 |
| REQ-RPT-005 | Farm productivity | Farm dashboards | 267-268 |
| REQ-RPT-006 | Predictive analytics | Forecasting engine | 268-269 |
| REQ-RPT-007 | Automated reporting | Report scheduler | 269-270 |

### 2.2 Key Metrics Definition

| Category | Metric | Formula | Target |
|----------|--------|---------|--------|
| Sales | Revenue Growth | (Current - Previous) / Previous * 100 | >10% MoM |
| Sales | Average Order Value | Total Revenue / Order Count | >BDT 500 |
| Sales | Conversion Rate | Orders / Visitors * 100 | >3% |
| Production | Yield Rate | Output / Input * 100 | >95% |
| Production | Wastage Rate | Waste / Total * 100 | <2% |
| Inventory | Turnover Ratio | COGS / Avg Inventory | >12x/year |
| Customer | CLV | Avg Purchase * Frequency * Lifespan | Track |
| Customer | Churn Rate | Lost / Total * 100 | <5% |
| Farm | Milk/Cow/Day | Total Milk / Cow Count | >15L |

---

## 3. Technical Architecture

### 3.1 Analytics Data Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           DATA SOURCES                                       │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │   Odoo ERP   │  │ TimescaleDB  │  │   Website    │  │  Mobile App  │    │
│  │  (PostgreSQL)│  │   (IoT)      │  │  (Analytics) │  │  (Events)    │    │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘    │
│         │                 │                 │                 │            │
└─────────┼─────────────────┼─────────────────┼─────────────────┼────────────┘
          │                 │                 │                 │
          └────────────┬────┴─────────────────┴────────┬────────┘
                       │                               │
                       ▼                               ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           ETL LAYER                                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                    ┌─────────────────────────┐                              │
│                    │     Apache Airflow      │                              │
│                    │    (ETL Orchestrator)   │                              │
│                    └────────────┬────────────┘                              │
│                                 │                                           │
│         ┌───────────────────────┼───────────────────────┐                  │
│         ▼                       ▼                       ▼                  │
│  ┌─────────────┐       ┌─────────────┐       ┌─────────────┐              │
│  │   Extract   │   →   │  Transform  │   →   │    Load     │              │
│  │   (dbt)     │       │   (dbt)     │       │   (dbt)     │              │
│  └─────────────┘       └─────────────┘       └─────────────┘              │
└─────────────────────────────────────────────────────────────────────────────┘
                                  │
                                  ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                        DATA WAREHOUSE                                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                    ┌─────────────────────────┐                              │
│                    │   PostgreSQL (Warehouse)│                              │
│                    │      Star Schema        │                              │
│                    └────────────┬────────────┘                              │
│                                 │                                           │
│    ┌────────────────────────────┼────────────────────────────┐             │
│    │                            │                            │             │
│    ▼                            ▼                            ▼             │
│  ┌──────────┐            ┌──────────┐            ┌──────────┐            │
│  │   Fact   │            │   Fact   │            │   Fact   │            │
│  │  Sales   │            │Production│            │ Inventory│            │
│  └──────────┘            └──────────┘            └──────────┘            │
│       │                        │                        │                 │
│       └────────────────────────┼────────────────────────┘                 │
│                                │                                          │
│    ┌─────────┬─────────┬───────┴───────┬─────────┬─────────┐            │
│    ▼         ▼         ▼               ▼         ▼         ▼            │
│  ┌────┐   ┌────┐   ┌────┐         ┌────┐   ┌────┐   ┌────┐            │
│  │Dim │   │Dim │   │Dim │         │Dim │   │Dim │   │Dim │            │
│  │Date│   │Prod│   │Cust│         │Farm│   │Loc │   │Chan│            │
│  └────┘   └────┘   └────┘         └────┘   └────┘   └────┘            │
└─────────────────────────────────────────────────────────────────────────────┘
                                  │
                                  ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                        ANALYTICS LAYER                                       │
├─────────────────────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │   Metabase   │  │   Grafana    │  │  Custom API  │  │   ML Engine  │    │
│  │ (Dashboards) │  │ (Monitoring) │  │  (FastAPI)   │  │ (Forecasting)│    │
│  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘    │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. Day-by-Day Implementation

### Day 261-262: Data Warehouse Schema Design

#### Dev 1 Tasks (16 hours) - Backend Lead

**Task 1: Star Schema Design (8h)**

```sql
-- migrations/warehouse/001_create_dimensions.sql

-- =============================================================================
-- Smart Dairy Analytics Data Warehouse - Dimension Tables
-- =============================================================================

-- Date Dimension
CREATE TABLE dim_date (
    date_key INTEGER PRIMARY KEY,
    full_date DATE NOT NULL UNIQUE,
    day_of_week INTEGER NOT NULL,
    day_name VARCHAR(10) NOT NULL,
    day_of_month INTEGER NOT NULL,
    day_of_year INTEGER NOT NULL,
    week_of_year INTEGER NOT NULL,
    month_number INTEGER NOT NULL,
    month_name VARCHAR(10) NOT NULL,
    quarter INTEGER NOT NULL,
    year INTEGER NOT NULL,
    is_weekend BOOLEAN NOT NULL,
    is_holiday BOOLEAN DEFAULT FALSE,
    fiscal_year INTEGER NOT NULL,
    fiscal_quarter INTEGER NOT NULL
);

-- Populate date dimension (5 years)
INSERT INTO dim_date
SELECT
    TO_CHAR(datum, 'YYYYMMDD')::INTEGER AS date_key,
    datum AS full_date,
    EXTRACT(DOW FROM datum) AS day_of_week,
    TO_CHAR(datum, 'Day') AS day_name,
    EXTRACT(DAY FROM datum) AS day_of_month,
    EXTRACT(DOY FROM datum) AS day_of_year,
    EXTRACT(WEEK FROM datum) AS week_of_year,
    EXTRACT(MONTH FROM datum) AS month_number,
    TO_CHAR(datum, 'Month') AS month_name,
    EXTRACT(QUARTER FROM datum) AS quarter,
    EXTRACT(YEAR FROM datum) AS year,
    EXTRACT(DOW FROM datum) IN (0, 6) AS is_weekend,
    FALSE AS is_holiday,
    CASE WHEN EXTRACT(MONTH FROM datum) >= 7
         THEN EXTRACT(YEAR FROM datum) + 1
         ELSE EXTRACT(YEAR FROM datum)
    END AS fiscal_year,
    CASE WHEN EXTRACT(MONTH FROM datum) >= 7
         THEN EXTRACT(QUARTER FROM datum) - 2
         ELSE EXTRACT(QUARTER FROM datum) + 2
    END AS fiscal_quarter
FROM generate_series('2023-01-01'::DATE, '2028-12-31'::DATE, '1 day') AS datum;

-- Product Dimension
CREATE TABLE dim_product (
    product_key SERIAL PRIMARY KEY,
    product_id INTEGER NOT NULL,
    product_code VARCHAR(50) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    category_id INTEGER,
    category_name VARCHAR(100),
    subcategory_name VARCHAR(100),
    brand VARCHAR(100),
    unit_of_measure VARCHAR(20),
    unit_price DECIMAL(12, 2),
    cost_price DECIMAL(12, 2),
    is_active BOOLEAN DEFAULT TRUE,
    valid_from TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valid_to TIMESTAMP DEFAULT '9999-12-31',
    is_current BOOLEAN DEFAULT TRUE
);

CREATE INDEX idx_dim_product_id ON dim_product(product_id);
CREATE INDEX idx_dim_product_current ON dim_product(is_current) WHERE is_current;

-- Customer Dimension
CREATE TABLE dim_customer (
    customer_key SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL,
    customer_code VARCHAR(50),
    customer_name VARCHAR(255) NOT NULL,
    customer_type VARCHAR(50),  -- b2b, b2c
    segment VARCHAR(50),        -- premium, regular, new
    city VARCHAR(100),
    district VARCHAR(100),
    region VARCHAR(100),
    registration_date DATE,
    loyalty_tier VARCHAR(20),
    total_orders INTEGER DEFAULT 0,
    total_revenue DECIMAL(14, 2) DEFAULT 0,
    clv_score DECIMAL(10, 2),
    churn_risk_score DECIMAL(5, 2),
    valid_from TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valid_to TIMESTAMP DEFAULT '9999-12-31',
    is_current BOOLEAN DEFAULT TRUE
);

CREATE INDEX idx_dim_customer_id ON dim_customer(customer_id);
CREATE INDEX idx_dim_customer_segment ON dim_customer(segment);

-- Farm Dimension
CREATE TABLE dim_farm (
    farm_key SERIAL PRIMARY KEY,
    farm_id INTEGER NOT NULL,
    farm_code VARCHAR(50) NOT NULL,
    farm_name VARCHAR(255) NOT NULL,
    farm_type VARCHAR(50),
    owner_name VARCHAR(255),
    district VARCHAR(100),
    region VARCHAR(100),
    total_area_acres DECIMAL(10, 2),
    total_cattle INTEGER,
    milking_cattle INTEGER,
    registration_date DATE,
    certification_status VARCHAR(50),
    valid_from TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valid_to TIMESTAMP DEFAULT '9999-12-31',
    is_current BOOLEAN DEFAULT TRUE
);

-- Location/Warehouse Dimension
CREATE TABLE dim_location (
    location_key SERIAL PRIMARY KEY,
    location_id INTEGER NOT NULL,
    location_code VARCHAR(50),
    location_name VARCHAR(255) NOT NULL,
    location_type VARCHAR(50),  -- warehouse, store, distribution
    city VARCHAR(100),
    district VARCHAR(100),
    region VARCHAR(100),
    capacity_liters DECIMAL(12, 2),
    is_cold_storage BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE
);

-- Channel Dimension
CREATE TABLE dim_channel (
    channel_key SERIAL PRIMARY KEY,
    channel_code VARCHAR(50) NOT NULL UNIQUE,
    channel_name VARCHAR(100) NOT NULL,
    channel_type VARCHAR(50),  -- online, offline, mobile, b2b_portal
    description TEXT
);

INSERT INTO dim_channel (channel_code, channel_name, channel_type) VALUES
('WEB', 'Website', 'online'),
('MOBILE', 'Mobile App', 'mobile'),
('B2B', 'B2B Portal', 'b2b_portal'),
('POS', 'Point of Sale', 'offline'),
('CALL', 'Call Center', 'offline');

-- Time Dimension (for intraday analysis)
CREATE TABLE dim_time (
    time_key INTEGER PRIMARY KEY,
    hour INTEGER NOT NULL,
    minute INTEGER NOT NULL,
    time_of_day VARCHAR(20),  -- morning, afternoon, evening, night
    is_business_hour BOOLEAN
);

INSERT INTO dim_time
SELECT
    hour * 100 + minute AS time_key,
    hour,
    minute,
    CASE
        WHEN hour BETWEEN 6 AND 11 THEN 'morning'
        WHEN hour BETWEEN 12 AND 17 THEN 'afternoon'
        WHEN hour BETWEEN 18 AND 21 THEN 'evening'
        ELSE 'night'
    END AS time_of_day,
    hour BETWEEN 9 AND 18 AS is_business_hour
FROM generate_series(0, 23) AS hour
CROSS JOIN generate_series(0, 59, 15) AS minute;
```

**Task 2: Fact Tables (8h)**

```sql
-- migrations/warehouse/002_create_facts.sql

-- =============================================================================
-- Smart Dairy Analytics Data Warehouse - Fact Tables
-- =============================================================================

-- Sales Fact Table
CREATE TABLE fact_sales (
    sales_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    time_key INTEGER REFERENCES dim_time(time_key),
    product_key INTEGER NOT NULL REFERENCES dim_product(product_key),
    customer_key INTEGER NOT NULL REFERENCES dim_customer(customer_key),
    location_key INTEGER REFERENCES dim_location(location_key),
    channel_key INTEGER REFERENCES dim_channel(channel_key),

    -- Transaction identifiers
    order_id INTEGER NOT NULL,
    order_line_id INTEGER NOT NULL,

    -- Measures
    quantity DECIMAL(12, 3) NOT NULL,
    unit_price DECIMAL(12, 2) NOT NULL,
    discount_amount DECIMAL(12, 2) DEFAULT 0,
    tax_amount DECIMAL(12, 2) DEFAULT 0,
    gross_amount DECIMAL(14, 2) NOT NULL,
    net_amount DECIMAL(14, 2) NOT NULL,
    cost_amount DECIMAL(14, 2),
    profit_amount DECIMAL(14, 2),

    -- Flags
    is_subscription BOOLEAN DEFAULT FALSE,
    is_first_order BOOLEAN DEFAULT FALSE,
    is_returned BOOLEAN DEFAULT FALSE
);

CREATE INDEX idx_fact_sales_date ON fact_sales(date_key);
CREATE INDEX idx_fact_sales_product ON fact_sales(product_key);
CREATE INDEX idx_fact_sales_customer ON fact_sales(customer_key);
CREATE INDEX idx_fact_sales_order ON fact_sales(order_id);

-- Production Fact Table
CREATE TABLE fact_production (
    production_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    product_key INTEGER NOT NULL REFERENCES dim_product(product_key),
    location_key INTEGER NOT NULL REFERENCES dim_location(location_key),

    -- Production identifiers
    production_order_id INTEGER NOT NULL,
    batch_number VARCHAR(50),

    -- Measures
    planned_quantity DECIMAL(12, 3),
    actual_quantity DECIMAL(12, 3) NOT NULL,
    input_quantity DECIMAL(12, 3),
    wastage_quantity DECIMAL(12, 3) DEFAULT 0,
    yield_percentage DECIMAL(5, 2),

    -- Time measures
    planned_hours DECIMAL(6, 2),
    actual_hours DECIMAL(6, 2),

    -- Cost measures
    material_cost DECIMAL(14, 2),
    labor_cost DECIMAL(14, 2),
    overhead_cost DECIMAL(14, 2),
    total_cost DECIMAL(14, 2),
    unit_cost DECIMAL(12, 4),

    -- Quality
    quality_score INTEGER,
    is_passed_qc BOOLEAN DEFAULT TRUE
);

CREATE INDEX idx_fact_production_date ON fact_production(date_key);
CREATE INDEX idx_fact_production_product ON fact_production(product_key);

-- Inventory Fact Table (Daily Snapshot)
CREATE TABLE fact_inventory_daily (
    inventory_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    product_key INTEGER NOT NULL REFERENCES dim_product(product_key),
    location_key INTEGER NOT NULL REFERENCES dim_location(location_key),

    -- Measures
    opening_quantity DECIMAL(12, 3),
    received_quantity DECIMAL(12, 3) DEFAULT 0,
    sold_quantity DECIMAL(12, 3) DEFAULT 0,
    adjusted_quantity DECIMAL(12, 3) DEFAULT 0,
    closing_quantity DECIMAL(12, 3),

    -- Valuation
    unit_cost DECIMAL(12, 4),
    total_value DECIMAL(14, 2),

    -- Days metrics
    days_on_hand INTEGER,
    is_below_reorder BOOLEAN DEFAULT FALSE,
    is_expired BOOLEAN DEFAULT FALSE,

    UNIQUE(date_key, product_key, location_key)
);

CREATE INDEX idx_fact_inventory_date ON fact_inventory_daily(date_key);
CREATE INDEX idx_fact_inventory_product ON fact_inventory_daily(product_key);

-- Milk Collection Fact Table
CREATE TABLE fact_milk_collection (
    collection_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    time_key INTEGER REFERENCES dim_time(time_key),
    farm_key INTEGER NOT NULL REFERENCES dim_farm(farm_key),
    location_key INTEGER REFERENCES dim_location(location_key),

    -- Collection identifiers
    collection_id INTEGER NOT NULL,
    route_id INTEGER,

    -- Measures
    volume_liters DECIMAL(12, 3) NOT NULL,
    fat_percentage DECIMAL(5, 2),
    snf_percentage DECIMAL(5, 2),
    protein_percentage DECIMAL(5, 2),
    lactose_percentage DECIMAL(5, 2),
    temperature_celsius DECIMAL(5, 2),

    -- Quality
    quality_grade VARCHAR(10),
    somatic_cell_count INTEGER,
    bacterial_count INTEGER,
    is_rejected BOOLEAN DEFAULT FALSE,
    rejection_reason VARCHAR(100),

    -- Pricing
    base_price_per_liter DECIMAL(8, 2),
    fat_bonus DECIMAL(8, 2) DEFAULT 0,
    quality_bonus DECIMAL(8, 2) DEFAULT 0,
    total_amount DECIMAL(12, 2)
);

CREATE INDEX idx_fact_milk_date ON fact_milk_collection(date_key);
CREATE INDEX idx_fact_milk_farm ON fact_milk_collection(farm_key);

-- Customer Engagement Fact (Daily aggregates)
CREATE TABLE fact_customer_engagement (
    engagement_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    customer_key INTEGER NOT NULL REFERENCES dim_customer(customer_key),
    channel_key INTEGER REFERENCES dim_channel(channel_key),

    -- Measures
    page_views INTEGER DEFAULT 0,
    session_count INTEGER DEFAULT 0,
    session_duration_seconds INTEGER DEFAULT 0,
    products_viewed INTEGER DEFAULT 0,
    cart_additions INTEGER DEFAULT 0,
    cart_abandonment INTEGER DEFAULT 0,
    orders_placed INTEGER DEFAULT 0,
    reviews_submitted INTEGER DEFAULT 0,
    support_tickets INTEGER DEFAULT 0,

    UNIQUE(date_key, customer_key, channel_key)
);

-- Forecasts Fact Table
CREATE TABLE fact_forecast (
    forecast_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    product_key INTEGER NOT NULL REFERENCES dim_product(product_key),
    location_key INTEGER REFERENCES dim_location(location_key),

    -- Forecast metadata
    forecast_date DATE NOT NULL,  -- When forecast was made
    forecast_model VARCHAR(50),
    forecast_horizon_days INTEGER,

    -- Measures
    forecasted_quantity DECIMAL(12, 3),
    forecasted_revenue DECIMAL(14, 2),
    lower_bound DECIMAL(12, 3),
    upper_bound DECIMAL(12, 3),
    confidence_level DECIMAL(5, 2),

    -- Actuals (filled later)
    actual_quantity DECIMAL(12, 3),
    actual_revenue DECIMAL(14, 2),
    forecast_error DECIMAL(12, 3),
    mape DECIMAL(8, 4),  -- Mean Absolute Percentage Error

    UNIQUE(date_key, product_key, location_key, forecast_date)
);
```

#### Dev 2 Tasks (16 hours) - Full-Stack

**Task 1: ETL Pipeline with dbt (8h)**

```yaml
# dbt_project/dbt_project.yml

name: 'smartdairy_analytics'
version: '1.0.0'
config-version: 2

profile: 'smartdairy'

model-paths: ["models"]
analysis-paths: ["analyses"]
test-paths: ["tests"]
seed-paths: ["seeds"]
macro-paths: ["macros"]

target-path: "target"
clean-targets:
  - "target"
  - "dbt_packages"

vars:
  start_date: '2024-01-01'
  currency: 'BDT'

models:
  smartdairy_analytics:
    staging:
      +materialized: view
      +schema: staging
    intermediate:
      +materialized: ephemeral
    marts:
      +materialized: table
      +schema: analytics
```

```sql
-- dbt_project/models/staging/stg_orders.sql

{{
    config(
        materialized='view'
    )
}}

WITH source AS (
    SELECT * FROM {{ source('odoo', 'sale_order') }}
),

order_lines AS (
    SELECT * FROM {{ source('odoo', 'sale_order_line') }}
),

staged AS (
    SELECT
        so.id AS order_id,
        sol.id AS order_line_id,
        so.partner_id AS customer_id,
        sol.product_id,
        so.warehouse_id,
        so.date_order,
        so.state AS order_state,

        -- Measures
        sol.product_uom_qty AS quantity,
        sol.price_unit AS unit_price,
        sol.discount,
        (sol.price_unit * sol.product_uom_qty) AS gross_amount,
        sol.price_subtotal AS net_amount,
        sol.price_tax AS tax_amount,

        -- Metadata
        so.create_date,
        so.write_date,

        -- Derived
        CASE
            WHEN so.origin LIKE 'SUB%' THEN TRUE
            ELSE FALSE
        END AS is_subscription,

        ROW_NUMBER() OVER (
            PARTITION BY so.partner_id
            ORDER BY so.date_order
        ) = 1 AS is_first_order

    FROM source so
    JOIN order_lines sol ON sol.order_id = so.id
    WHERE so.state IN ('sale', 'done')
)

SELECT * FROM staged
```

```sql
-- dbt_project/models/marts/sales/fct_sales.sql

{{
    config(
        materialized='incremental',
        unique_key='sales_key',
        on_schema_change='sync_all_columns'
    )
}}

WITH orders AS (
    SELECT * FROM {{ ref('stg_orders') }}
    {% if is_incremental() %}
    WHERE write_date > (SELECT MAX(write_date) FROM {{ this }})
    {% endif %}
),

products AS (
    SELECT * FROM {{ ref('dim_product') }}
    WHERE is_current = TRUE
),

customers AS (
    SELECT * FROM {{ ref('dim_customer') }}
    WHERE is_current = TRUE
),

final AS (
    SELECT
        {{ dbt_utils.generate_surrogate_key(['o.order_id', 'o.order_line_id']) }} AS sales_key,
        TO_CHAR(o.date_order, 'YYYYMMDD')::INTEGER AS date_key,
        (EXTRACT(HOUR FROM o.date_order) * 100 +
         (EXTRACT(MINUTE FROM o.date_order)::INTEGER / 15) * 15)::INTEGER AS time_key,
        p.product_key,
        c.customer_key,
        NULL AS location_key,  -- To be mapped
        1 AS channel_key,  -- Default to web

        o.order_id,
        o.order_line_id,

        o.quantity,
        o.unit_price,
        (o.gross_amount * o.discount / 100) AS discount_amount,
        o.tax_amount,
        o.gross_amount,
        o.net_amount,
        (p.cost_price * o.quantity) AS cost_amount,
        (o.net_amount - (p.cost_price * o.quantity)) AS profit_amount,

        o.is_subscription,
        o.is_first_order,
        FALSE AS is_returned,

        o.write_date

    FROM orders o
    LEFT JOIN products p ON p.product_id = o.product_id
    LEFT JOIN customers c ON c.customer_id = o.customer_id
)

SELECT * FROM final
```

**Task 2: Analytics API Endpoints (8h)**

```python
# services/analytics_api/app/routers/sales.py

from fastapi import APIRouter, Depends, Query
from datetime import date, timedelta
from typing import Optional, List
from enum import Enum

from app.dependencies import get_warehouse_db
from app.schemas.sales import (
    SalesSummary,
    SalesTrend,
    TopProducts,
    SalesBreakdown,
)

router = APIRouter(prefix="/api/v1/analytics/sales", tags=["sales-analytics"])


class TimeGrain(str, Enum):
    DAY = "day"
    WEEK = "week"
    MONTH = "month"
    QUARTER = "quarter"


@router.get("/summary", response_model=SalesSummary)
async def get_sales_summary(
    start_date: date = Query(default=None),
    end_date: date = Query(default=None),
    db = Depends(get_warehouse_db),
):
    """Get sales summary with KPIs"""
    if not start_date:
        start_date = date.today() - timedelta(days=30)
    if not end_date:
        end_date = date.today()

    start_key = int(start_date.strftime('%Y%m%d'))
    end_key = int(end_date.strftime('%Y%m%d'))

    # Previous period for comparison
    period_days = (end_date - start_date).days
    prev_start = start_date - timedelta(days=period_days)
    prev_end = start_date - timedelta(days=1)
    prev_start_key = int(prev_start.strftime('%Y%m%d'))
    prev_end_key = int(prev_end.strftime('%Y%m%d'))

    async with db.acquire() as conn:
        # Current period metrics
        current = await conn.fetchrow("""
            SELECT
                COUNT(DISTINCT order_id) AS total_orders,
                COUNT(DISTINCT customer_key) AS unique_customers,
                SUM(quantity) AS total_quantity,
                SUM(net_amount) AS total_revenue,
                SUM(profit_amount) AS total_profit,
                AVG(net_amount) AS avg_order_value,
                SUM(CASE WHEN is_first_order THEN 1 ELSE 0 END) AS new_customers
            FROM fact_sales
            WHERE date_key BETWEEN $1 AND $2
        """, start_key, end_key)

        # Previous period metrics
        previous = await conn.fetchrow("""
            SELECT
                SUM(net_amount) AS total_revenue,
                COUNT(DISTINCT order_id) AS total_orders
            FROM fact_sales
            WHERE date_key BETWEEN $1 AND $2
        """, prev_start_key, prev_end_key)

    # Calculate growth rates
    revenue_growth = 0
    if previous['total_revenue'] and previous['total_revenue'] > 0:
        revenue_growth = (
            (current['total_revenue'] - previous['total_revenue'])
            / previous['total_revenue'] * 100
        )

    return SalesSummary(
        period_start=start_date,
        period_end=end_date,
        total_orders=current['total_orders'] or 0,
        unique_customers=current['unique_customers'] or 0,
        total_revenue=float(current['total_revenue'] or 0),
        total_profit=float(current['total_profit'] or 0),
        avg_order_value=float(current['avg_order_value'] or 0),
        new_customers=current['new_customers'] or 0,
        revenue_growth_percent=round(revenue_growth, 2),
        profit_margin=round(
            (current['total_profit'] / current['total_revenue'] * 100)
            if current['total_revenue'] else 0, 2
        ),
    )


@router.get("/trends", response_model=List[SalesTrend])
async def get_sales_trends(
    start_date: date = Query(default=None),
    end_date: date = Query(default=None),
    grain: TimeGrain = Query(default=TimeGrain.DAY),
    db = Depends(get_warehouse_db),
):
    """Get sales trends over time"""
    if not start_date:
        start_date = date.today() - timedelta(days=30)
    if not end_date:
        end_date = date.today()

    grain_sql = {
        TimeGrain.DAY: "d.full_date",
        TimeGrain.WEEK: "DATE_TRUNC('week', d.full_date)",
        TimeGrain.MONTH: "DATE_TRUNC('month', d.full_date)",
        TimeGrain.QUARTER: "DATE_TRUNC('quarter', d.full_date)",
    }

    async with db.acquire() as conn:
        data = await conn.fetch(f"""
            SELECT
                {grain_sql[grain]}::DATE AS period,
                COUNT(DISTINCT s.order_id) AS orders,
                SUM(s.net_amount) AS revenue,
                SUM(s.profit_amount) AS profit,
                COUNT(DISTINCT s.customer_key) AS customers
            FROM fact_sales s
            JOIN dim_date d ON d.date_key = s.date_key
            WHERE d.full_date BETWEEN $1 AND $2
            GROUP BY 1
            ORDER BY 1
        """, start_date, end_date)

    return [
        SalesTrend(
            period=row['period'],
            orders=row['orders'],
            revenue=float(row['revenue'] or 0),
            profit=float(row['profit'] or 0),
            customers=row['customers'],
        )
        for row in data
    ]


@router.get("/top-products", response_model=List[TopProducts])
async def get_top_products(
    start_date: date = Query(default=None),
    end_date: date = Query(default=None),
    limit: int = Query(default=10, le=50),
    metric: str = Query(default="revenue"),  # revenue, quantity, profit
    db = Depends(get_warehouse_db),
):
    """Get top performing products"""
    if not start_date:
        start_date = date.today() - timedelta(days=30)
    if not end_date:
        end_date = date.today()

    order_by = {
        "revenue": "total_revenue",
        "quantity": "total_quantity",
        "profit": "total_profit",
    }.get(metric, "total_revenue")

    async with db.acquire() as conn:
        data = await conn.fetch(f"""
            SELECT
                p.product_key,
                p.product_name,
                p.category_name,
                SUM(s.quantity) AS total_quantity,
                SUM(s.net_amount) AS total_revenue,
                SUM(s.profit_amount) AS total_profit,
                COUNT(DISTINCT s.order_id) AS order_count
            FROM fact_sales s
            JOIN dim_product p ON p.product_key = s.product_key
            JOIN dim_date d ON d.date_key = s.date_key
            WHERE d.full_date BETWEEN $1 AND $2
            GROUP BY p.product_key, p.product_name, p.category_name
            ORDER BY {order_by} DESC
            LIMIT $3
        """, start_date, end_date, limit)

    return [
        TopProducts(
            product_key=row['product_key'],
            product_name=row['product_name'],
            category=row['category_name'],
            quantity=float(row['total_quantity'] or 0),
            revenue=float(row['total_revenue'] or 0),
            profit=float(row['total_profit'] or 0),
            order_count=row['order_count'],
        )
        for row in data
    ]


@router.get("/by-channel")
async def get_sales_by_channel(
    start_date: date = Query(default=None),
    end_date: date = Query(default=None),
    db = Depends(get_warehouse_db),
):
    """Get sales breakdown by channel"""
    if not start_date:
        start_date = date.today() - timedelta(days=30)
    if not end_date:
        end_date = date.today()

    async with db.acquire() as conn:
        data = await conn.fetch("""
            SELECT
                c.channel_name,
                c.channel_type,
                COUNT(DISTINCT s.order_id) AS orders,
                SUM(s.net_amount) AS revenue,
                SUM(s.profit_amount) AS profit,
                COUNT(DISTINCT s.customer_key) AS customers
            FROM fact_sales s
            JOIN dim_channel c ON c.channel_key = s.channel_key
            JOIN dim_date d ON d.date_key = s.date_key
            WHERE d.full_date BETWEEN $1 AND $2
            GROUP BY c.channel_key, c.channel_name, c.channel_type
            ORDER BY revenue DESC
        """, start_date, end_date)

    total_revenue = sum(row['revenue'] or 0 for row in data)

    return {
        'breakdown': [
            {
                'channel': row['channel_name'],
                'channel_type': row['channel_type'],
                'orders': row['orders'],
                'revenue': float(row['revenue'] or 0),
                'profit': float(row['profit'] or 0),
                'customers': row['customers'],
                'percentage': round(
                    (row['revenue'] / total_revenue * 100) if total_revenue else 0, 2
                ),
            }
            for row in data
        ],
        'total_revenue': total_revenue,
    }
```

---

### Day 263-265: Sales & Production Analytics

#### Dev 2 Tasks (24 hours over 3 days)

**Customer Analytics with CLV**

```python
# services/analytics_api/app/routers/customers.py

from fastapi import APIRouter, Depends, Query
from datetime import date, timedelta
from typing import List

from app.dependencies import get_warehouse_db
from app.services.clv_calculator import CLVCalculator

router = APIRouter(prefix="/api/v1/analytics/customers", tags=["customer-analytics"])


@router.get("/segments")
async def get_customer_segments(
    db = Depends(get_warehouse_db),
):
    """Get customer segmentation analysis"""
    async with db.acquire() as conn:
        data = await conn.fetch("""
            WITH customer_metrics AS (
                SELECT
                    c.customer_key,
                    c.customer_name,
                    c.segment,
                    c.loyalty_tier,
                    c.clv_score,
                    COUNT(DISTINCT s.order_id) AS total_orders,
                    SUM(s.net_amount) AS total_revenue,
                    MAX(d.full_date) AS last_order_date,
                    MIN(d.full_date) AS first_order_date
                FROM dim_customer c
                LEFT JOIN fact_sales s ON s.customer_key = c.customer_key
                LEFT JOIN dim_date d ON d.date_key = s.date_key
                WHERE c.is_current = TRUE
                GROUP BY c.customer_key, c.customer_name, c.segment,
                         c.loyalty_tier, c.clv_score
            )
            SELECT
                segment,
                COUNT(*) AS customer_count,
                AVG(total_orders) AS avg_orders,
                AVG(total_revenue) AS avg_revenue,
                AVG(clv_score) AS avg_clv,
                SUM(total_revenue) AS segment_revenue
            FROM customer_metrics
            GROUP BY segment
            ORDER BY segment_revenue DESC
        """)

    return {
        'segments': [
            {
                'segment': row['segment'] or 'Unclassified',
                'customer_count': row['customer_count'],
                'avg_orders': round(row['avg_orders'] or 0, 1),
                'avg_revenue': round(float(row['avg_revenue'] or 0), 2),
                'avg_clv': round(float(row['avg_clv'] or 0), 2),
                'total_revenue': round(float(row['segment_revenue'] or 0), 2),
            }
            for row in data
        ]
    }


@router.get("/cohort-analysis")
async def get_cohort_analysis(
    months: int = Query(default=12, le=24),
    db = Depends(get_warehouse_db),
):
    """Get customer cohort retention analysis"""
    async with db.acquire() as conn:
        data = await conn.fetch("""
            WITH cohorts AS (
                SELECT
                    c.customer_key,
                    DATE_TRUNC('month', c.registration_date) AS cohort_month
                FROM dim_customer c
                WHERE c.is_current = TRUE
                  AND c.registration_date >= CURRENT_DATE - INTERVAL '%s months'
            ),
            monthly_activity AS (
                SELECT
                    s.customer_key,
                    DATE_TRUNC('month', d.full_date) AS activity_month
                FROM fact_sales s
                JOIN dim_date d ON d.date_key = s.date_key
                GROUP BY s.customer_key, DATE_TRUNC('month', d.full_date)
            ),
            cohort_data AS (
                SELECT
                    c.cohort_month,
                    EXTRACT(MONTH FROM AGE(a.activity_month, c.cohort_month)) AS months_since,
                    COUNT(DISTINCT c.customer_key) AS active_customers
                FROM cohorts c
                JOIN monthly_activity a ON a.customer_key = c.customer_key
                                       AND a.activity_month >= c.cohort_month
                GROUP BY c.cohort_month, months_since
            ),
            cohort_sizes AS (
                SELECT cohort_month, COUNT(*) AS cohort_size
                FROM cohorts
                GROUP BY cohort_month
            )
            SELECT
                cd.cohort_month,
                cs.cohort_size,
                cd.months_since,
                cd.active_customers,
                ROUND(cd.active_customers::NUMERIC / cs.cohort_size * 100, 1) AS retention_rate
            FROM cohort_data cd
            JOIN cohort_sizes cs ON cs.cohort_month = cd.cohort_month
            ORDER BY cd.cohort_month, cd.months_since
        """, months)

    # Transform into cohort matrix
    cohorts = {}
    for row in data:
        cohort = row['cohort_month'].strftime('%Y-%m')
        if cohort not in cohorts:
            cohorts[cohort] = {
                'cohort_month': cohort,
                'cohort_size': row['cohort_size'],
                'retention': {}
            }
        cohorts[cohort]['retention'][int(row['months_since'])] = float(row['retention_rate'])

    return {'cohorts': list(cohorts.values())}


@router.get("/clv-distribution")
async def get_clv_distribution(
    db = Depends(get_warehouse_db),
):
    """Get CLV score distribution"""
    async with db.acquire() as conn:
        data = await conn.fetch("""
            SELECT
                CASE
                    WHEN clv_score < 1000 THEN 'Low (<1000)'
                    WHEN clv_score < 5000 THEN 'Medium (1000-5000)'
                    WHEN clv_score < 15000 THEN 'High (5000-15000)'
                    ELSE 'Premium (15000+)'
                END AS clv_tier,
                COUNT(*) AS customer_count,
                AVG(clv_score) AS avg_clv,
                SUM(total_revenue) AS tier_revenue
            FROM dim_customer
            WHERE is_current = TRUE AND clv_score IS NOT NULL
            GROUP BY 1
            ORDER BY avg_clv
        """)

    return {
        'distribution': [
            {
                'tier': row['clv_tier'],
                'customer_count': row['customer_count'],
                'avg_clv': round(float(row['avg_clv']), 2),
                'total_revenue': round(float(row['tier_revenue'] or 0), 2),
            }
            for row in data
        ]
    }


# services/analytics_api/app/services/clv_calculator.py

from datetime import date, timedelta
import numpy as np


class CLVCalculator:
    """Calculate Customer Lifetime Value using BG/NBD model simplified"""

    def __init__(self, db_pool):
        self.db_pool = db_pool

    async def calculate_clv(self, customer_id: int) -> dict:
        """Calculate CLV for a specific customer"""
        async with self.db_pool.acquire() as conn:
            # Get customer transaction history
            history = await conn.fetch("""
                SELECT
                    d.full_date AS order_date,
                    SUM(s.net_amount) AS order_value
                FROM fact_sales s
                JOIN dim_date d ON d.date_key = s.date_key
                JOIN dim_customer c ON c.customer_key = s.customer_key
                WHERE c.customer_id = $1
                GROUP BY d.full_date
                ORDER BY d.full_date
            """, customer_id)

        if len(history) < 2:
            return {'clv': 0, 'confidence': 'low'}

        # Calculate RFM metrics
        order_dates = [r['order_date'] for r in history]
        order_values = [float(r['order_value']) for r in history]

        recency = (date.today() - order_dates[-1]).days
        frequency = len(order_dates)
        monetary = np.mean(order_values)

        # Calculate average time between purchases
        if frequency > 1:
            inter_purchase_times = [
                (order_dates[i+1] - order_dates[i]).days
                for i in range(len(order_dates) - 1)
            ]
            avg_ipt = np.mean(inter_purchase_times)
        else:
            avg_ipt = 365  # Default to yearly

        # Simple CLV calculation
        # Expected purchases per year
        purchases_per_year = 365 / avg_ipt if avg_ipt > 0 else 1

        # Customer lifespan estimate (years)
        # Based on recency - more recent = longer expected lifespan
        if recency < 30:
            lifespan = 3
        elif recency < 90:
            lifespan = 2
        elif recency < 180:
            lifespan = 1
        else:
            lifespan = 0.5

        # CLV = (Average Order Value) × (Purchase Frequency/Year) × (Lifespan)
        clv = monetary * purchases_per_year * lifespan

        # Apply discount rate (10% annually)
        discount_rate = 0.10
        discounted_clv = clv / (1 + discount_rate)

        return {
            'clv': round(discounted_clv, 2),
            'recency_days': recency,
            'frequency': frequency,
            'monetary_avg': round(monetary, 2),
            'expected_purchases_per_year': round(purchases_per_year, 1),
            'expected_lifespan_years': lifespan,
            'confidence': 'high' if frequency >= 5 else 'medium' if frequency >= 3 else 'low',
        }

    async def update_all_clv_scores(self):
        """Batch update CLV scores for all customers"""
        async with self.db_pool.acquire() as conn:
            await conn.execute("""
                WITH customer_rfm AS (
                    SELECT
                        c.customer_key,
                        c.customer_id,
                        CURRENT_DATE - MAX(d.full_date) AS recency,
                        COUNT(DISTINCT s.order_id) AS frequency,
                        AVG(s.net_amount) AS monetary
                    FROM dim_customer c
                    LEFT JOIN fact_sales s ON s.customer_key = c.customer_key
                    LEFT JOIN dim_date d ON d.date_key = s.date_key
                    WHERE c.is_current = TRUE
                    GROUP BY c.customer_key, c.customer_id
                ),
                clv_calc AS (
                    SELECT
                        customer_key,
                        -- Simple CLV formula
                        monetary * (365.0 / NULLIF(recency, 0)) *
                        CASE
                            WHEN recency < 30 THEN 3
                            WHEN recency < 90 THEN 2
                            WHEN recency < 180 THEN 1
                            ELSE 0.5
                        END / 1.10 AS clv_score
                    FROM customer_rfm
                    WHERE frequency > 0
                )
                UPDATE dim_customer c
                SET clv_score = clv.clv_score
                FROM clv_calc clv
                WHERE c.customer_key = clv.customer_key
            """)
```

#### Dev 3 Tasks (24 hours over Days 266-269)

**Mobile Analytics Charts**

```dart
// lib/features/analytics/presentation/pages/analytics_dashboard_page.dart

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:fl_chart/fl_chart.dart';
import '../bloc/analytics_bloc.dart';
import '../widgets/revenue_chart.dart';
import '../widgets/kpi_cards.dart';

class AnalyticsDashboardPage extends StatelessWidget {
  const AnalyticsDashboardPage({super.key});

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (context) => context.read<AnalyticsBloc>()
        ..add(const AnalyticsEvent.loadDashboard()),
      child: Scaffold(
        appBar: AppBar(
          title: const Text('Analytics'),
          actions: [
            IconButton(
              icon: const Icon(Icons.date_range),
              onPressed: () => _showDatePicker(context),
            ),
          ],
        ),
        body: BlocBuilder<AnalyticsBloc, AnalyticsState>(
          builder: (context, state) {
            if (state.isLoading) {
              return const Center(child: CircularProgressIndicator());
            }

            return RefreshIndicator(
              onRefresh: () async {
                context.read<AnalyticsBloc>().add(
                  const AnalyticsEvent.loadDashboard(),
                );
              },
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // KPI Cards
                    KPICardsRow(summary: state.salesSummary),
                    const SizedBox(height: 24),

                    // Revenue Chart
                    const Text(
                      'Revenue Trend',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 12),
                    SizedBox(
                      height: 250,
                      child: RevenueLineChart(data: state.revenueTrend),
                    ),
                    const SizedBox(height: 24),

                    // Top Products
                    const Text(
                      'Top Products',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 12),
                    TopProductsList(products: state.topProducts),
                    const SizedBox(height: 24),

                    // Channel Breakdown
                    const Text(
                      'Sales by Channel',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 12),
                    SizedBox(
                      height: 200,
                      child: ChannelPieChart(data: state.channelBreakdown),
                    ),
                  ],
                ),
              ),
            );
          },
        ),
      ),
    );
  }

  void _showDatePicker(BuildContext context) {
    showModalBottomSheet(
      context: context,
      builder: (context) => const DateRangeSelector(),
    );
  }
}

// lib/features/analytics/presentation/widgets/revenue_chart.dart

class RevenueLineChart extends StatelessWidget {
  final List<RevenueTrendPoint> data;

  const RevenueLineChart({super.key, required this.data});

  @override
  Widget build(BuildContext context) {
    if (data.isEmpty) {
      return const Center(child: Text('No data available'));
    }

    return LineChart(
      LineChartData(
        gridData: FlGridData(
          show: true,
          drawVerticalLine: false,
          horizontalInterval: _calculateInterval(),
          getDrawingHorizontalLine: (value) => FlLine(
            color: Colors.grey.withOpacity(0.2),
            strokeWidth: 1,
          ),
        ),
        titlesData: FlTitlesData(
          leftTitles: AxisTitles(
            sideTitles: SideTitles(
              showTitles: true,
              reservedSize: 60,
              getTitlesWidget: (value, meta) {
                return Text(
                  _formatCurrency(value),
                  style: const TextStyle(fontSize: 10),
                );
              },
            ),
          ),
          bottomTitles: AxisTitles(
            sideTitles: SideTitles(
              showTitles: true,
              reservedSize: 30,
              interval: (data.length / 5).ceil().toDouble(),
              getTitlesWidget: (value, meta) {
                final index = value.toInt();
                if (index >= 0 && index < data.length) {
                  return Text(
                    _formatDate(data[index].date),
                    style: const TextStyle(fontSize: 10),
                  );
                }
                return const SizedBox();
              },
            ),
          ),
          topTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
          rightTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
        ),
        borderData: FlBorderData(show: false),
        lineBarsData: [
          LineChartBarData(
            spots: data.asMap().entries.map((e) {
              return FlSpot(e.key.toDouble(), e.value.revenue);
            }).toList(),
            isCurved: true,
            color: Theme.of(context).primaryColor,
            barWidth: 3,
            isStrokeCapRound: true,
            dotData: const FlDotData(show: false),
            belowBarData: BarAreaData(
              show: true,
              color: Theme.of(context).primaryColor.withOpacity(0.1),
            ),
          ),
        ],
        lineTouchData: LineTouchData(
          touchTooltipData: LineTouchTooltipData(
            getTooltipItems: (touchedSpots) {
              return touchedSpots.map((spot) {
                final point = data[spot.x.toInt()];
                return LineTooltipItem(
                  '${_formatDate(point.date)}\n${_formatCurrency(point.revenue)}',
                  const TextStyle(color: Colors.white),
                );
              }).toList();
            },
          ),
        ),
      ),
    );
  }

  double _calculateInterval() {
    if (data.isEmpty) return 1000;
    final maxValue = data.map((e) => e.revenue).reduce((a, b) => a > b ? a : b);
    return (maxValue / 5).ceilToDouble();
  }

  String _formatCurrency(double value) {
    if (value >= 1000000) {
      return '৳${(value / 1000000).toStringAsFixed(1)}M';
    } else if (value >= 1000) {
      return '৳${(value / 1000).toStringAsFixed(0)}K';
    }
    return '৳${value.toStringAsFixed(0)}';
  }

  String _formatDate(DateTime date) {
    return '${date.day}/${date.month}';
  }
}

// lib/features/analytics/presentation/widgets/kpi_cards.dart

class KPICardsRow extends StatelessWidget {
  final SalesSummary? summary;

  const KPICardsRow({super.key, this.summary});

  @override
  Widget build(BuildContext context) {
    return GridView.count(
      crossAxisCount: 2,
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      mainAxisSpacing: 12,
      crossAxisSpacing: 12,
      childAspectRatio: 1.5,
      children: [
        KPICard(
          title: 'Revenue',
          value: '৳${_formatNumber(summary?.totalRevenue ?? 0)}',
          change: summary?.revenueGrowthPercent ?? 0,
          icon: Icons.attach_money,
        ),
        KPICard(
          title: 'Orders',
          value: '${summary?.totalOrders ?? 0}',
          change: null,
          icon: Icons.shopping_cart,
        ),
        KPICard(
          title: 'Avg Order',
          value: '৳${(summary?.avgOrderValue ?? 0).toStringAsFixed(0)}',
          change: null,
          icon: Icons.receipt_long,
        ),
        KPICard(
          title: 'Customers',
          value: '${summary?.uniqueCustomers ?? 0}',
          subtitle: '${summary?.newCustomers ?? 0} new',
          icon: Icons.people,
        ),
      ],
    );
  }

  String _formatNumber(double value) {
    if (value >= 1000000) {
      return '${(value / 1000000).toStringAsFixed(1)}M';
    } else if (value >= 1000) {
      return '${(value / 1000).toStringAsFixed(0)}K';
    }
    return value.toStringAsFixed(0);
  }
}

class KPICard extends StatelessWidget {
  final String title;
  final String value;
  final double? change;
  final String? subtitle;
  final IconData icon;

  const KPICard({
    super.key,
    required this.title,
    required this.value,
    this.change,
    this.subtitle,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  title,
                  style: TextStyle(
                    color: Colors.grey[600],
                    fontSize: 12,
                  ),
                ),
                Icon(icon, size: 20, color: Colors.grey[400]),
              ],
            ),
            Text(
              value,
              style: const TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
              ),
            ),
            if (change != null)
              Row(
                children: [
                  Icon(
                    change! >= 0 ? Icons.trending_up : Icons.trending_down,
                    size: 16,
                    color: change! >= 0 ? Colors.green : Colors.red,
                  ),
                  const SizedBox(width: 4),
                  Text(
                    '${change! >= 0 ? '+' : ''}${change!.toStringAsFixed(1)}%',
                    style: TextStyle(
                      fontSize: 12,
                      color: change! >= 0 ? Colors.green : Colors.red,
                    ),
                  ),
                ],
              )
            else if (subtitle != null)
              Text(
                subtitle!,
                style: TextStyle(
                  fontSize: 12,
                  color: Colors.grey[600],
                ),
              ),
          ],
        ),
      ),
    );
  }
}
```

---

### Day 268-270: Forecasting & Report Automation

#### Dev 1 Tasks (16 hours)

**Demand Forecasting Engine**

```python
# services/analytics_api/app/services/forecasting.py

import numpy as np
import pandas as pd
from datetime import date, timedelta
from typing import List, Dict, Tuple
from statsmodels.tsa.holtwinters import ExponentialSmoothing
from sklearn.metrics import mean_absolute_percentage_error
import asyncpg


class DemandForecaster:
    """Demand forecasting using Holt-Winters Exponential Smoothing"""

    def __init__(self, db_pool: asyncpg.Pool):
        self.db_pool = db_pool

    async def generate_forecast(
        self,
        product_id: int,
        horizon_days: int = 30,
        confidence_level: float = 0.95,
    ) -> Dict:
        """Generate demand forecast for a product"""
        # Get historical sales data
        history = await self._get_sales_history(product_id, days=365)

        if len(history) < 30:
            return {
                'status': 'insufficient_data',
                'message': 'Need at least 30 days of sales history',
            }

        # Prepare time series
        df = pd.DataFrame(history)
        df['date'] = pd.to_datetime(df['date'])
        df = df.set_index('date')
        df = df.resample('D').sum().fillna(0)

        # Fit Holt-Winters model
        try:
            model = ExponentialSmoothing(
                df['quantity'],
                seasonal_periods=7,  # Weekly seasonality
                trend='add',
                seasonal='add',
                damped_trend=True,
            ).fit(optimized=True)

            # Generate forecast
            forecast = model.forecast(horizon_days)

            # Calculate confidence intervals
            residuals = model.resid
            std_error = np.std(residuals)
            z_score = 1.96 if confidence_level == 0.95 else 1.645

            forecast_df = pd.DataFrame({
                'date': pd.date_range(
                    start=df.index[-1] + timedelta(days=1),
                    periods=horizon_days
                ),
                'forecast': forecast.values,
                'lower_bound': forecast.values - z_score * std_error,
                'upper_bound': forecast.values + z_score * std_error,
            })

            # Ensure non-negative
            forecast_df['forecast'] = forecast_df['forecast'].clip(lower=0)
            forecast_df['lower_bound'] = forecast_df['lower_bound'].clip(lower=0)

            # Calculate model accuracy on holdout
            accuracy = await self._calculate_model_accuracy(
                df['quantity'].values,
                model
            )

            return {
                'status': 'success',
                'product_id': product_id,
                'forecast_date': date.today().isoformat(),
                'horizon_days': horizon_days,
                'confidence_level': confidence_level,
                'model_accuracy_mape': round(accuracy, 2),
                'predictions': [
                    {
                        'date': row['date'].strftime('%Y-%m-%d'),
                        'forecast': round(row['forecast'], 2),
                        'lower_bound': round(row['lower_bound'], 2),
                        'upper_bound': round(row['upper_bound'], 2),
                    }
                    for _, row in forecast_df.iterrows()
                ],
                'summary': {
                    'total_forecasted': round(forecast_df['forecast'].sum(), 2),
                    'avg_daily': round(forecast_df['forecast'].mean(), 2),
                    'peak_day': forecast_df.loc[
                        forecast_df['forecast'].idxmax(), 'date'
                    ].strftime('%Y-%m-%d'),
                    'peak_quantity': round(forecast_df['forecast'].max(), 2),
                }
            }

        except Exception as e:
            return {
                'status': 'error',
                'message': str(e),
            }

    async def _get_sales_history(
        self,
        product_id: int,
        days: int = 365
    ) -> List[Dict]:
        """Get historical sales data for a product"""
        async with self.db_pool.acquire() as conn:
            data = await conn.fetch("""
                SELECT
                    d.full_date AS date,
                    COALESCE(SUM(s.quantity), 0) AS quantity
                FROM dim_date d
                LEFT JOIN fact_sales s ON s.date_key = d.date_key
                    AND s.product_key IN (
                        SELECT product_key FROM dim_product
                        WHERE product_id = $1 AND is_current = TRUE
                    )
                WHERE d.full_date BETWEEN CURRENT_DATE - $2 AND CURRENT_DATE - 1
                GROUP BY d.full_date
                ORDER BY d.full_date
            """, product_id, days)

        return [{'date': r['date'], 'quantity': float(r['quantity'])} for r in data]

    async def _calculate_model_accuracy(
        self,
        actual: np.ndarray,
        model,
        holdout_size: int = 7
    ) -> float:
        """Calculate MAPE on holdout set"""
        if len(actual) <= holdout_size:
            return 0.0

        train = actual[:-holdout_size]
        test = actual[-holdout_size:]

        # Refit on training data
        temp_model = ExponentialSmoothing(
            train,
            seasonal_periods=7,
            trend='add',
            seasonal='add',
            damped_trend=True,
        ).fit(optimized=True)

        predictions = temp_model.forecast(holdout_size)

        # Calculate MAPE (handle zeros)
        test_nonzero = test.copy()
        test_nonzero[test_nonzero == 0] = 0.1  # Small value to avoid division by zero

        mape = mean_absolute_percentage_error(test_nonzero, predictions) * 100
        return min(mape, 100)  # Cap at 100%

    async def batch_forecast_all_products(self, horizon_days: int = 30):
        """Generate forecasts for all active products"""
        async with self.db_pool.acquire() as conn:
            products = await conn.fetch("""
                SELECT DISTINCT product_id
                FROM dim_product
                WHERE is_current = TRUE AND is_active = TRUE
            """)

        results = []
        for product in products:
            forecast = await self.generate_forecast(
                product['product_id'],
                horizon_days
            )
            if forecast['status'] == 'success':
                results.append(forecast)

                # Store in fact_forecast
                await self._store_forecast(forecast)

        return {
            'total_products': len(products),
            'successful_forecasts': len(results),
            'avg_accuracy': round(
                np.mean([r['model_accuracy_mape'] for r in results]), 2
            ) if results else 0,
        }

    async def _store_forecast(self, forecast: Dict):
        """Store forecast results in data warehouse"""
        async with self.db_pool.acquire() as conn:
            for pred in forecast['predictions']:
                await conn.execute("""
                    INSERT INTO fact_forecast
                    (date_key, product_key, forecast_date, forecast_model,
                     forecast_horizon_days, forecasted_quantity,
                     lower_bound, upper_bound, confidence_level)
                    SELECT
                        TO_CHAR($1::DATE, 'YYYYMMDD')::INTEGER,
                        product_key,
                        $2,
                        'holt_winters',
                        $3,
                        $4,
                        $5,
                        $6,
                        $7
                    FROM dim_product
                    WHERE product_id = $8 AND is_current = TRUE
                    ON CONFLICT (date_key, product_key, location_key, forecast_date)
                    DO UPDATE SET
                        forecasted_quantity = EXCLUDED.forecasted_quantity,
                        lower_bound = EXCLUDED.lower_bound,
                        upper_bound = EXCLUDED.upper_bound
                """,
                    pred['date'],
                    forecast['forecast_date'],
                    forecast['horizon_days'],
                    pred['forecast'],
                    pred['lower_bound'],
                    pred['upper_bound'],
                    forecast['confidence_level'],
                    forecast['product_id'],
                )
```

#### Dev 2 Tasks (8 hours) - Day 269-270

**Report Scheduler**

```python
# services/analytics_api/app/services/report_scheduler.py

import asyncio
from datetime import datetime, date, timedelta
from typing import List, Dict, Optional
import jinja2
from weasyprint import HTML
import aiosmtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.application import MIMEApplication

from app.config import settings
from app.services.analytics import AnalyticsService


class ReportScheduler:
    """Automated report generation and delivery"""

    def __init__(self, db_pool, analytics_service: AnalyticsService):
        self.db_pool = db_pool
        self.analytics = analytics_service
        self.template_env = jinja2.Environment(
            loader=jinja2.FileSystemLoader('templates/reports'),
            autoescape=True,
        )

    async def generate_daily_sales_report(
        self,
        report_date: date = None
    ) -> bytes:
        """Generate daily sales summary PDF report"""
        if not report_date:
            report_date = date.today() - timedelta(days=1)

        # Gather data
        summary = await self.analytics.get_sales_summary(
            start_date=report_date,
            end_date=report_date,
        )
        top_products = await self.analytics.get_top_products(
            start_date=report_date,
            end_date=report_date,
            limit=10,
        )
        channel_breakdown = await self.analytics.get_sales_by_channel(
            start_date=report_date,
            end_date=report_date,
        )

        # Render template
        template = self.template_env.get_template('daily_sales.html')
        html_content = template.render(
            report_date=report_date.strftime('%B %d, %Y'),
            generated_at=datetime.now().strftime('%Y-%m-%d %H:%M'),
            summary=summary,
            top_products=top_products,
            channel_breakdown=channel_breakdown,
            company_name='Smart Dairy',
        )

        # Generate PDF
        pdf = HTML(string=html_content).write_pdf()
        return pdf

    async def generate_weekly_executive_report(
        self,
        week_ending: date = None
    ) -> bytes:
        """Generate weekly executive summary PDF"""
        if not week_ending:
            week_ending = date.today() - timedelta(days=date.today().weekday() + 1)

        week_start = week_ending - timedelta(days=6)
        prev_week_end = week_start - timedelta(days=1)
        prev_week_start = prev_week_end - timedelta(days=6)

        # Current week data
        current = await self.analytics.get_sales_summary(week_start, week_ending)
        previous = await self.analytics.get_sales_summary(prev_week_start, prev_week_end)

        # Trends
        daily_trends = await self.analytics.get_sales_trends(
            week_start, week_ending, grain='day'
        )

        # Top performers
        top_products = await self.analytics.get_top_products(
            week_start, week_ending, limit=5
        )
        top_customers = await self.analytics.get_top_customers(
            week_start, week_ending, limit=5
        )

        # Customer metrics
        customer_segments = await self.analytics.get_customer_segments()

        # Production metrics
        production = await self.analytics.get_production_summary(
            week_start, week_ending
        )

        template = self.template_env.get_template('weekly_executive.html')
        html_content = template.render(
            week_start=week_start.strftime('%B %d'),
            week_end=week_ending.strftime('%B %d, %Y'),
            current=current,
            previous=previous,
            daily_trends=daily_trends,
            top_products=top_products,
            top_customers=top_customers,
            customer_segments=customer_segments,
            production=production,
            company_name='Smart Dairy',
        )

        pdf = HTML(string=html_content).write_pdf()
        return pdf

    async def send_report_email(
        self,
        recipients: List[str],
        subject: str,
        body: str,
        attachment: bytes,
        attachment_name: str,
    ):
        """Send report via email"""
        msg = MIMEMultipart()
        msg['Subject'] = subject
        msg['From'] = settings.EMAIL_FROM
        msg['To'] = ', '.join(recipients)

        # Body
        msg.attach(MIMEText(body, 'html'))

        # Attachment
        pdf_attachment = MIMEApplication(attachment, _subtype='pdf')
        pdf_attachment.add_header(
            'Content-Disposition', 'attachment',
            filename=attachment_name
        )
        msg.attach(pdf_attachment)

        # Send
        await aiosmtplib.send(
            msg,
            hostname=settings.SMTP_HOST,
            port=settings.SMTP_PORT,
            username=settings.SMTP_USER,
            password=settings.SMTP_PASSWORD,
            use_tls=True,
        )

    async def run_scheduled_reports(self):
        """Run all scheduled reports"""
        today = date.today()

        # Daily reports (every day at 6 AM)
        daily_pdf = await self.generate_daily_sales_report()
        await self.send_report_email(
            recipients=settings.DAILY_REPORT_RECIPIENTS,
            subject=f'Smart Dairy - Daily Sales Report - {(today - timedelta(days=1)).strftime("%Y-%m-%d")}',
            body='<p>Please find attached the daily sales report.</p>',
            attachment=daily_pdf,
            attachment_name=f'daily_sales_{today - timedelta(days=1)}.pdf',
        )

        # Weekly reports (Monday)
        if today.weekday() == 0:
            weekly_pdf = await self.generate_weekly_executive_report()
            await self.send_report_email(
                recipients=settings.WEEKLY_REPORT_RECIPIENTS,
                subject=f'Smart Dairy - Weekly Executive Report - Week Ending {(today - timedelta(days=1)).strftime("%Y-%m-%d")}',
                body='<p>Please find attached the weekly executive summary.</p>',
                attachment=weekly_pdf,
                attachment_name=f'weekly_executive_{today - timedelta(days=1)}.pdf',
            )

        # Monthly reports (1st of month)
        if today.day == 1:
            prev_month_end = today - timedelta(days=1)
            prev_month_start = prev_month_end.replace(day=1)

            monthly_pdf = await self.generate_monthly_report(
                prev_month_start, prev_month_end
            )
            await self.send_report_email(
                recipients=settings.MONTHLY_REPORT_RECIPIENTS,
                subject=f'Smart Dairy - Monthly Report - {prev_month_end.strftime("%B %Y")}',
                body='<p>Please find attached the monthly performance report.</p>',
                attachment=monthly_pdf,
                attachment_name=f'monthly_report_{prev_month_end.strftime("%Y_%m")}.pdf',
            )
```

---

## 5. Milestone Summary

### Key Deliverables Completed

| # | Deliverable | Status |
|---|-------------|--------|
| 1 | Data warehouse star schema | ✅ |
| 2 | ETL pipeline with dbt | ✅ |
| 3 | Sales analytics API | ✅ |
| 4 | Customer CLV calculations | ✅ |
| 5 | Cohort analysis | ✅ |
| 6 | Demand forecasting | ✅ |
| 7 | Mobile analytics charts | ✅ |
| 8 | Automated report scheduler | ✅ |
| 9 | Executive dashboard | ✅ |

### Key Metrics Implemented

| Metric | Status | Target |
|--------|--------|--------|
| Revenue tracking | ✅ | Real-time |
| CLV scoring | ✅ | All customers |
| Forecast accuracy | ✅ | >85% MAPE |
| Report automation | ✅ | Daily/Weekly/Monthly |

### Dependencies for Next Milestone

- Analytics data available for farm management (Milestone 28)
- Performance baselines established for optimization (Milestone 29)

---

*Document Version: 1.0 | Last Updated: Day 270 | Next Review: Day 280*
