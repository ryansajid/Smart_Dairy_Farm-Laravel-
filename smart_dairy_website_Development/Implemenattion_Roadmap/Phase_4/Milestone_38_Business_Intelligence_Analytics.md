# Milestone 38 - Business Intelligence & Analytics

## Smart Dairy Smart Portal + ERP System Implementation Roadmap

**Phase 4: Advanced Features & Optimization**
**Duration: Days 371-380 (10 Days)**
**Status: Planned**
**Last Updated: 2026-02-02**

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [BI Architecture](#2-bi-architecture)
3. [Daily Task Allocation](#3-daily-task-allocation)
4. [Data Warehouse Design](#4-data-warehouse-design)
5. [ETL Pipeline](#5-etl-pipeline)
6. [Analytics Models](#6-analytics-models)
7. [Dashboard Specifications](#7-dashboard-specifications)
8. [KPI Definitions](#8-kpi-definitions)
9. [API & Integration](#9-api--integration)
10. [Testing & Validation](#10-testing--validation)
11. [Appendices](#11-appendices)

---

## 1. Executive Summary

### 1.1 BI Objectives

The Business Intelligence & Analytics milestone aims to transform raw operational data into actionable insights that drive strategic decision-making across Smart Dairy's entire value chain. This milestone establishes a robust analytics foundation that enables data-driven decision-making at every organizational level.

#### Primary Objectives

1. **Unified Data Platform**: Create a centralized data warehouse consolidating data from all operational systems including farm management, production, sales, finance, and supply chain modules into a single source of truth.

2. **Real-time Analytics**: Implement near real-time data processing capabilities enabling stakeholders to monitor operations, identify trends, and respond to opportunities or challenges as they emerge.

3. **Self-Service Analytics**: Empower business users with intuitive self-service reporting and analysis tools reducing dependency on technical teams for routine reporting needs.

4. **Predictive Capabilities**: Deploy machine learning models that forecast demand, predict production outcomes, identify potential equipment failures, and optimize pricing strategies.

5. **Operational Excellence**: Provide comprehensive visibility into farm operations, production efficiency, supply chain performance, and financial metrics to drive continuous improvement initiatives.

#### Strategic Goals

| Goal | Target | Measurement |
|------|--------|-------------|
| Data Consolidation | 100% source system coverage | All ERP modules feeding warehouse |
| Report Generation Time | < 5 seconds for standard reports | Average query response time |
| User Adoption | 85% active users within 90 days | Monthly active user metric |
| Forecast Accuracy | > 90% for demand forecasting | MAPE (Mean Absolute Percentage Error) |
| Decision Latency | 50% reduction in decision time | Time-to-insight measurement |

### 1.2 Analytics Capabilities

The BI platform delivers comprehensive analytics capabilities organized into four distinct categories:

#### Descriptive Analytics
- Historical performance tracking
- Trend analysis across all business dimensions
- Comparative analysis (period-over-period, year-over-year)
- Drill-down capabilities from summary to transaction level

#### Diagnostic Analytics
- Root cause analysis tools
- Correlation analysis between variables
- Exception reporting with automated alerts
- Pattern recognition in operational data

#### Predictive Analytics
- Machine learning models for demand forecasting
- Production yield prediction algorithms
- Customer churn prediction
- Price elasticity modeling
- Equipment failure prediction

#### Prescriptive Analytics
- Optimization recommendations
- Scenario modeling and what-if analysis
- Automated decision suggestions
- Resource allocation optimization

### 1.3 Success Metrics

#### Technical Metrics

| Metric | Baseline | Target | Measurement Method |
|--------|----------|--------|-------------------|
| Data Freshness | 24 hours | < 15 minutes | ETL latency monitoring |
| Query Performance | N/A | < 3 seconds p95 | Query execution logs |
| System Uptime | N/A | 99.9% | Infrastructure monitoring |
| Data Accuracy | N/A | 99.95% | Reconciliation reports |
| Concurrent Users | N/A | 500+ | Load testing |

#### Business Metrics

| Metric | Baseline | Target | Measurement Period |
|--------|----------|--------|-------------------|
| Report Usage | Manual reports | 1000+ reports/month | Analytics audit logs |
| Time to Insight | 2-3 days | < 30 minutes | User surveys |
| Decision Quality | Qualitative | 25% improvement | Business outcome tracking |
| Forecast Accuracy | Manual estimation | 90%+ accuracy | Forecast vs. actual comparison |
| Cost Savings | N/A | 15% operational cost reduction | Financial analysis |

#### User Adoption Metrics

| Metric | Target | Measurement |
|--------|--------|-------------|
| Active Users | 200+ monthly active users | Login analytics |
| Dashboard Views | 5000+ monthly views | Usage analytics |
| Self-Service Rate | 70% of reports self-served | Report creation logs |
| User Satisfaction | 4.5/5.0 rating | Quarterly surveys |
| Training Completion | 100% of target users | LMS tracking |

---

## 2. BI Architecture

### 2.1 Data Warehouse Architecture

The Smart Dairy BI platform employs a modern data warehouse architecture built on ClickHouse, optimized for high-performance analytical queries on time-series and dimensional data.

#### Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           DATA SOURCES LAYER                                │
├──────────────┬──────────────┬──────────────┬──────────────┬─────────────────┤
│   ERP Core   │  Farm Mgmt   │   IoT/Sensors│   External   │   Mobile Apps   │
│   (PostgreSQL)│  (PostgreSQL)│   (MQTT/Kafka)│   (APIs)    │   (Events)      │
└──────┬───────┴──────┬───────┴──────┬───────┴──────┬───────┴────────┬────────┘
       │              │              │              │                │
       └──────────────┴──────────────┴──────────────┴────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                         DATA INGESTION LAYER                                │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐ │
│  │ Change Data │  │  Streaming  │  │   Batch     │  │    API Connectors   │ │
│  │   Capture   │  │  Ingestion  │  │   Loader    │  │                     │ │
│  │  (Debezium) │  │  (Kafka)    │  │  (Airbyte)  │  │  (Custom Extractors)│ │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────────┬──────────┘ │
└─────────┼────────────────┼────────────────┼────────────────────┼────────────┘
          │                │                │                    │
          └────────────────┴────────────────┴────────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                      DATA PROCESSING LAYER (ETL)                            │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐ │
│  │   Apache    │  │  dbt (Data  │  │  Spark      │  │   Data Quality      │ │
│  │   Airflow   │  │  Build Tool)│  │  Processing │  │   (Great Expectations)│
│  │  (Orchestration)│ (Transform) │  │  (Heavy ML) │  │                     │ │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────────┬──────────┘ │
└─────────┼────────────────┼────────────────┼────────────────────┼────────────┘
          │                │                │                    │
          └────────────────┴────────────────┴────────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                      DATA STORAGE LAYER                                     │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    CLICKHOUSE DATA WAREHOUSE                        │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │   │
│  │  │   Raw Data  │  │   ODS       │  │   DWD       │  │   ADS       │ │   │
│  │  │   Layer     │  │  (Operational)│  │ (Data Warehouse)│  │ (Application)│ │   │
│  │  │  (Staging)  │  │   Data Store │  │   Detail     │  │   Data Store│ │   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘ │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    FEATURE STORE (Feast)                            │   │
│  │     ML Features | Real-time Features | Historical Features          │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                      ANALYTICS LAYER                                        │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐ │
│  │  Superset   │  │  Jupyter    │  │  ML Serving │  │   Custom Apps       │ │
│  │  (Dashboards)│  │  Notebooks  │  │  (KServe)   │  │   (React/Vue)       │ │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────────┬──────────┘ │
└─────────┼────────────────┼────────────────┼────────────────────┼────────────┘
          │                │                │                    │
          └────────────────┴────────────────┴────────────────────┘
                                   │
                                   ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                      CONSUMPTION LAYER                                      │
│       Executives    │    Analysts    │    Operations    │    External       │
│       Board Room    │    Data Team   │    Farm Managers │    Partners       │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### Data Flow Architecture

The data warehouse follows a layered architecture approach with distinct zones for different stages of data processing:

**Layer 1: Data Source Zone**
- ERP Core Database (PostgreSQL): Transactional data
- Farm Management System: Animal records, production data
- IoT/Sensor Layer: Real-time telemetry from farm equipment
- External APIs: Weather data, market prices, regulatory feeds
- Mobile Applications: User interactions, field data collection

**Layer 2: Data Ingestion Zone**
- Change Data Capture (CDC) using Debezium for real-time database replication
- Apache Kafka for streaming data ingestion
- Apache Airflow for batch ETL orchestration
- Custom API connectors for external data sources

**Layer 3: Data Processing Zone**
- Staging area for raw data validation
- dbt (Data Build Tool) for SQL-based transformations
- Apache Spark for heavy machine learning preprocessing
- Data quality checks using Great Expectations

**Layer 4: Data Storage Zone**
- ClickHouse for high-performance analytical storage
- Feature Store (Feast) for ML feature management
- Redis for real-time caching
- Object storage for archival

**Layer 5: Data Consumption Zone**
- Apache Superset for interactive dashboards
- Jupyter notebooks for ad-hoc analysis
- ML model serving infrastructure
- Embedded analytics in ERP application

#### Infrastructure Components

| Component | Technology | Purpose | Configuration |
|-----------|------------|---------|---------------|
| Data Warehouse | ClickHouse | Analytical storage | 3-node cluster, sharded by date |
| Orchestration | Apache Airflow | ETL workflow management | 2 scheduler + 3 worker nodes |
| Stream Processing | Apache Kafka | Real-time data streaming | 5 brokers, 3 ZooKeeper |
| CDC Engine | Debezium | Database change capture | Connector per source DB |
| Transformation | dbt | SQL-based transformations | Cloud IDE + CLI |
| ML Platform | MLflow + KServe | Model training & serving | Kubernetes-native |
| Visualization | Apache Superset | Dashboards & exploration | 2 application servers |
| Feature Store | Feast | ML feature management | Redis + PostgreSQL backend |

### 2.2 ETL Pipeline Design

The ETL (Extract, Transform, Load) pipeline is designed for scalability, reliability, and real-time capabilities to support both batch and streaming analytics requirements.

#### Pipeline Architecture

```
┌──────────────────────────────────────────────────────────────────────────────┐
│                         ETL PIPELINE ARCHITECTURE                            │
└──────────────────────────────────────────────────────────────────────────────┘

EXTRACT PHASE
┌─────────────────────────────────────────────────────────────────────────────┐
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐   │
│  │  PostgreSQL │    │   MongoDB   │    │   Kafka     │    │   APIs      │   │
│  │    CDC      │    │   Change    │    │   Streams   │    │   Polling   │   │
│  │  (Debezium) │    │   Streams   │    │  (IoT/Data) │    │  (REST/SOAP)│   │
│  └──────┬──────┘    └──────┬──────┘    └──────┬──────┘    └──────┬──────┘   │
│         │                  │                  │                  │          │
│         └──────────────────┴──────────────────┴──────────────────┘          │
│                                   │                                         │
│                                   ▼                                         │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                     LANDING ZONE (Raw Data)                         │   │
│  │    JSON/Avro/Parquet files partitioned by source and date          │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────┘
                                     │
                                     ▼
TRANSFORM PHASE
┌─────────────────────────────────────────────────────────────────────────────┐
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                    APACHE AIRFLOW ORCHESTRATION                     │   │
│  │                                                                     │   │
│  │  DAG 1: Daily Full Load              DAG 2: Hourly Incremental     │   │
│  │  ┌──────────────┐                    ┌──────────────┐              │   │
│  │  │ extract_full │                    │ extract_incr │              │   │
│  │  └──────┬───────┘                    └──────┬───────┘              │   │
│  │         │                                   │                      │   │
│  │         ▼                                   ▼                      │   │
│  │  ┌──────────────┐                    ┌──────────────┐              │   │
│  │  │   validate   │                    │   validate   │              │   │
│  │  └──────┬───────┘                    └──────┬───────┘              │   │
│  │         │                                   │                      │   │
│  │         ▼                                   ▼                      │   │
│  │  ┌──────────────┐                    ┌──────────────┐              │   │
│  │  │  transform   │                    │  transform   │              │   │
│  │  │   (dbt)      │                    │   (dbt)      │              │   │
│  │  └──────┬───────┘                    └──────┬───────┘              │   │
│  │         │                                   │                      │   │
│  │         ▼                                   ▼                      │   │
│  │  ┌──────────────┐                    ┌──────────────┐              │   │
│  │  │    test      │                    │    test      │              │   │
│  │  └──────┬───────┘                    └──────┬───────┘              │   │
│  │         │                                   │                      │   │
│  │         ▼                                   ▼                      │   │
│  │  ┌──────────────┐                    ┌──────────────┐              │   │
│  │  │    load      │                    │    load      │              │   │
│  │  └──────────────┘                    └──────────────┘              │   │
│  │                                                                     │   │
│  │  DAG 3: Real-time Streaming (Kafka Connect)                        │   │
│  │  ┌──────────────┐                                                   │   │
│  │  │ Kafka Source │──►│ Transform │──►│ ClickHouse Sink │            │   │
│  │  │   Connectors │   │  (Kafka    │   │   Connectors    │            │   │
│  │  └──────────────┘   │   Streams) │   └─────────────────┘            │   │
│  │                     └─────────────┘                                 │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────┘
                                     │
                                     ▼
LOAD PHASE
┌─────────────────────────────────────────────────────────────────────────────┐
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                     CLICKHOUSE TARGET TABLES                        │   │
│  │                                                                     │   │
│  │  Layer 1: ODS (Operational Data Store)                             │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │ ods_sales   │  │ ods_production│  │ods_inventory│  │ ods_finance ││   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘│   │
│  │                                                                     │   │
│  │  Layer 2: DWD (Data Warehouse Detail)                              │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │dwd_sales_fact│  │dwd_production│  │dwd_inventory│  │dwd_finance  ││   │
│  │  │             │  │   _fact     │  │   _fact     │  │   _fact     ││   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘│   │
│  │                                                                     │   │
│  │  Layer 3: DWS (Data Warehouse Service/Aggregation)                 │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │dws_daily_agg│  │dws_weekly_agg│  │dws_monthly_│  │dws_yearly_  ││   │
│  │  │             │  │             │  │   agg       │  │   agg       ││   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘│   │
│  │                                                                     │   │
│  │  Layer 4: ADS (Application Data Store)                             │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │ ads_exec_   │  │ ads_sales_  │  │ ads_farm_   │  │ ads_finance_││   │
│  │  │   kpi       │  │   dashboard │  │   analytics │  │   reports   ││   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘│   │
│  └─────────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### ETL Processing Layers

**1. Extraction Layer**

The extraction layer is responsible for pulling data from various source systems with minimal impact on operational performance.

| Source Type | Extraction Method | Frequency | Volume/Day |
|-------------|------------------|-----------|------------|
| ERP PostgreSQL | CDC (Debezium) | Real-time | ~2M records |
| Farm Database | CDC (Debezium) | Real-time | ~5M records |
| IoT Sensors | Kafka Connect | Real-time | ~50M events |
| External APIs | Airflow Operators | Hourly | ~100K records |
| Mobile Events | Kafka Producer | Real-time | ~1M events |

**Extraction Configuration:**

```yaml
# Debezium CDC Configuration
source_connectors:
  erp_sales_connector:
    connector.class: io.debezium.connector.postgresql.PostgresConnector
    database.hostname: erp-db.smartdairy.internal
    database.port: 5432
    database.user: debezium
    database.password: ${DB_PASSWORD}
    database.dbname: erp_production
    database.server.name: smart_dairy_erp
    table.include.list: public.sales_orders,public.invoices,public.payments
    plugin.name: pgoutput
    slot.name: debezium_erp_slot
    publication.name: dbz_publication
    snapshot.mode: initial
    
  farm_management_connector:
    connector.class: io.debezium.connector.postgresql.PostgresConnector
    database.hostname: farm-db.smartdairy.internal
    database.port: 5432
    database.user: debezium
    database.password: ${DB_PASSWORD}
    database.dbname: farm_management
    database.server.name: smart_dairy_farm
    table.include.list: public.animals,public.milk_production,public.health_records
    plugin.name: pgoutput
    slot.name: debezium_farm_slot
    publication.name: dbz_publication
    snapshot.mode: initial

sink_connectors:
  clickhouse_erp_sink:
    connector.class: com.clickhouse.kafka.connect.ClickHouseSinkConnector
    tasks.max: 4
    topics: smart_dairy_erp.public.sales_orders,smart_dairy_erp.public.invoices
    clickhouse.server.url: http://clickhouse:8123
    clickhouse.database: smart_dairy_ods
    clickhouse.username: etl_user
    clickhouse.password: ${CH_PASSWORD}
    table.create: true
    batch.size: 1000
    retry.on.failure: true
    max.retries: 3
```

**2. Transformation Layer**

The transformation layer uses dbt (Data Build Tool) for SQL-based data transformations with version control, testing, and documentation.

```sql
-- dbt project structure
smart_dairy_dbt/
├── dbt_project.yml
├── profiles.yml
├── packages.yml
├── analyses/
├── macros/
│   ├── date_spine.sql
│   ├── generate_schema_name.sql
│   ├── tests/
│   └── utils/
├── models/
│   ├── staging/
│   │   ├── _sources.yml
│   │   ├── stg_erp_sales_orders.sql
│   │   ├── stg_erp_invoices.sql
│   │   ├── stg_farm_animals.sql
│   │   └── stg_farm_milk_production.sql
│   ├── intermediate/
│   │   ├── int_sales_enriched.sql
│   │   ├── int_production_daily.sql
│   │   └── int_animal_performance.sql
│   ├── marts/
│   │   ├── sales/
│   │   │   ├── fct_sales_orders.sql
│   │   │   ├── dim_customers.sql
│   │   │   └── dim_products.sql
│   │   ├── production/
│   │   │   ├── fct_milk_production.sql
│   │   │   ├── dim_animals.sql
│   │   │   └── dim_farms.sql
│   │   └── finance/
│   │       ├── fct_transactions.sql
│   │       └── dim_accounts.sql
│   └── reports/
│       ├── rpt_executive_summary.sql
│       ├── rpt_sales_performance.sql
│       └── rpt_production_efficiency.sql
└── seeds/
    ├── regions.csv
    ├── product_categories.csv
    └── cost_center_mapping.csv
```

**Staging Model Example:**

```sql
-- models/staging/stg_erp_sales_orders.sql

{{ config(
    materialized='view',
    schema='staging',
    tags=['sales', 'daily']
) }}

WITH source AS (
    SELECT *
    FROM {{ source('erp', 'sales_orders') }}
    WHERE deleted_at IS NULL
),

renamed AS (
    SELECT
        -- Primary key
        order_id,
        
        -- Foreign keys
        customer_id,
        product_id,
        sales_rep_id,
        
        -- Order attributes
        order_number,
        order_status,
        order_type,
        
        -- Monetary fields
        quantity,
        unit_price,
        discount_amount,
        tax_amount,
        total_amount,
        
        -- Timestamps
        order_date,
        expected_delivery_date,
        actual_delivery_date,
        created_at,
        updated_at,
        
        -- Metadata
        _etl_loaded_at
        
    FROM source
)

SELECT * FROM renamed
```

**Mart Model Example:**

```sql
-- models/marts/sales/fct_sales_orders.sql

{{ config(
    materialized='incremental',
    schema='marts',
    unique_key='order_sk',
    tags=['sales', 'fact_table'],
    partition_by={
        'field': 'order_date',
        'data_type': 'date',
        'granularity': 'day'
    }
) }}

WITH orders AS (
    SELECT * FROM {{ ref('stg_erp_sales_orders') }}
),

customers AS (
    SELECT * FROM {{ ref('dim_customers') }}
),

products AS (
    SELECT * FROM {{ ref('dim_products') }}
),

enriched AS (
    SELECT
        -- Surrogate key
        {{ dbt_utils.generate_surrogate_key(['orders.order_id']) }} AS order_sk,
        
        -- Natural key
        orders.order_id,
        
        -- Foreign keys (surrogate)
        customers.customer_sk,
        products.product_sk,
        
        -- Degenerate dimensions
        orders.order_number,
        orders.order_status,
        orders.order_type,
        
        -- Measures
        orders.quantity,
        orders.unit_price,
        orders.discount_amount,
        orders.tax_amount,
        orders.total_amount,
        
        -- Calculated measures
        (orders.total_amount - orders.discount_amount) AS net_revenue,
        (orders.unit_price * orders.quantity) AS gross_amount,
        
        -- Dates
        orders.order_date,
        orders.expected_delivery_date,
        orders.actual_delivery_date,
        
        -- Delivery metrics
        DATEDIFF(day, orders.order_date, orders.actual_delivery_date) AS fulfillment_days,
        CASE 
            WHEN orders.actual_delivery_date <= orders.expected_delivery_date THEN 'On Time'
            ELSE 'Late'
        END AS delivery_performance,
        
        -- Metadata
        orders.created_at,
        orders.updated_at,
        CURRENT_TIMESTAMP() AS _dbt_loaded_at
        
    FROM orders
    LEFT JOIN customers ON orders.customer_id = customers.customer_id
    LEFT JOIN products ON orders.product_id = products.product_id
)

SELECT * FROM enriched

{% if is_incremental() %}
WHERE order_date >= (SELECT MAX(order_date) FROM {{ this }})
{% endif %}
```

**3. Loading Layer**

ClickHouse serves as the primary analytical database with optimized schema design for fast queries.

```sql
-- ClickHouse Schema Definition for Sales Fact Table

-- Create database
CREATE DATABASE IF NOT EXISTS smart_dairy_marts;

-- Sales fact table with MergeTree engine
CREATE TABLE IF NOT EXISTS smart_dairy_marts.fct_sales_orders (
    -- Surrogate key
    order_sk UInt64,
    
    -- Natural key
    order_id UInt64,
    
    -- Foreign keys
    customer_sk UInt64,
    product_sk UInt64,
    sales_rep_sk UInt64,
    
    -- Degenerate dimensions
    order_number String,
    order_status LowCardinality(String),
    order_type LowCardinality(String),
    
    -- Measures
    quantity Decimal(18, 4),
    unit_price Decimal(18, 4),
    discount_amount Decimal(18, 4),
    tax_amount Decimal(18, 4),
    total_amount Decimal(18, 4),
    net_revenue Decimal(18, 4),
    gross_amount Decimal(18, 4),
    
    -- Dates
    order_date Date,
    order_datetime DateTime,
    expected_delivery_date Date,
    actual_delivery_date Nullable(Date),
    
    -- Delivery metrics
    fulfillment_days Nullable(Int32),
    delivery_performance LowCardinality(String),
    
    -- Metadata
    created_at DateTime,
    updated_at DateTime,
    _dbt_loaded_at DateTime DEFAULT now()
)
ENGINE = MergeTree()
PARTITION BY toYYYYMM(order_date)
ORDER BY (order_date, customer_sk, product_sk)
TTL order_date + INTERVAL 3 YEAR
SETTINGS index_granularity = 8192;

-- AggregatingMergeTree for pre-aggregated daily sales
CREATE TABLE IF NOT EXISTS smart_dairy_marts.agg_daily_sales (
    order_date Date,
    customer_sk UInt64,
    product_sk UInt64,
    sales_region LowCardinality(String),
    
    -- Aggregated measures
    total_orders AggregateFunction(count, UInt64),
    total_quantity AggregateFunction(sum, Decimal(18, 4)),
    total_revenue AggregateFunction(sum, Decimal(18, 4)),
    avg_order_value AggregateFunction(avg, Decimal(18, 4)),
    max_order_value AggregateFunction(max, Decimal(18, 4)),
    min_order_value AggregateFunction(min, Decimal(18, 4))
)
ENGINE = AggregatingMergeTree()
PARTITION BY toYYYYMM(order_date)
ORDER BY (order_date, customer_sk, product_sk, sales_region);

-- Materialized view for automatic aggregation
CREATE MATERIALIZED VIEW IF NOT EXISTS smart_dairy_marts.mv_daily_sales
TO smart_dairy_marts.agg_daily_sales
AS
SELECT
    order_date,
    customer_sk,
    product_sk,
    multiIf(
        customer_sk % 5 = 0, 'North',
        customer_sk % 5 = 1, 'South',
        customer_sk % 5 = 2, 'East',
        customer_sk % 5 = 3, 'West',
        'Central'
    ) AS sales_region,
    
    countState(order_id) AS total_orders,
    sumState(quantity) AS total_quantity,
    sumState(net_revenue) AS total_revenue,
    avgState(net_revenue) AS avg_order_value,
    maxState(net_revenue) AS max_order_value,
    minState(net_revenue) AS min_order_value
FROM smart_dairy_marts.fct_sales_orders
GROUP BY order_date, customer_sk, product_sk;
```

### 2.3 Analytics Engine

The analytics engine provides query processing, caching, and optimization capabilities for dashboard and reporting workloads.

#### Query Engine Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      ANALYTICS QUERY ENGINE                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      QUERY LAYER                                    │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │   SQL       │  │   MDX       │  │   Custom    │  │   REST      ││   │
│  │  │  Interface  │  │  Interface  │  │   DSL       │  │   API       ││   │
│  │  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘│   │
│  │         └─────────────────┴─────────────────┴─────────────────┘     │   │
│  │                                   │                                  │   │
│  │                                   ▼                                  │   │
│  │  ┌─────────────────────────────────────────────────────────────┐   │   │
│  │  │              QUERY PARSER & OPTIMIZER                       │   │   │
│  │  │  - Syntax validation    - Query rewriting                   │   │   │
│  │  │  - Index selection      - Cost-based optimization           │   │   │
│  │  └─────────────────────────────┬───────────────────────────────┘   │   │
│  │                                │                                    │   │
│  │                                ▼                                    │   │
│  │  ┌─────────────────────────────────────────────────────────────┐   │   │
│  │  │              QUERY CACHE LAYER                              │   │   │
│  │  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐ │   │   │
│  │  │  │  Result     │  │  Metadata   │  │  Query Pattern      │ │   │   │
│  │  │  │  Cache      │  │  Cache      │  │  Cache              │ │   │   │
│  │  │  │  (Redis)    │  │  (Redis)    │  │  (ML-based)         │ │   │   │
│  │  │  └─────────────┘  └─────────────┘  └─────────────────────┘ │   │   │
│  │  └─────────────────────────────┬───────────────────────────────┘   │   │
│  │                                │                                    │   │
│  │                                ▼                                    │   │
│  │  ┌─────────────────────────────────────────────────────────────┐   │   │
│  │  │              EXECUTION ENGINE                               │   │   │
│  │  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐ │   │   │
│  │  │  │  ClickHouse │  │  Spark      │  │  Pre-computed       │ │   │   │
│  │  │  │  Direct     │  │  (Complex   │  │  Aggregates         │ │   │   │
│  │  │  │  Query      │  │  Analytics) │  │  (Cube)             │ │   │   │
│  │  │  └─────────────┘  └─────────────┘  └─────────────────────┘ │   │   │
│  │  └─────────────────────────────────────────────────────────────┘   │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### Query Optimization Strategies

1. **Materialized Views**: Pre-computed aggregates for common queries
2. **Projection Optimization**: Denormalized projections for specific query patterns
3. **Query Result Caching**: Redis-based cache for repeated queries
4. **Connection Pooling**: Reuse database connections for efficiency
5. **Query Rewriting**: Automatic transformation to use pre-aggregated data

```python
# Query Engine Configuration (Python/SQLAlchemy)

class AnalyticsQueryEngine:
    """
    High-performance analytics query engine with caching and optimization.
    """
    
    def __init__(self, clickhouse_client, redis_client):
        self.ch_client = clickhouse_client
        self.redis = redis_client
        self.cache_ttl = 300  # 5 minutes default
        
    async def execute_query(self, query: str, params: dict = None, 
                           use_cache: bool = True) -> pd.DataFrame:
        """
        Execute analytics query with caching and optimization.
        """
        # Generate cache key
        cache_key = self._generate_cache_key(query, params)
        
        # Check cache
        if use_cache:
            cached_result = await self._get_from_cache(cache_key)
            if cached_result is not None:
                return pd.read_json(cached_result)
        
        # Optimize query
        optimized_query = self._optimize_query(query)
        
        # Execute query
        start_time = time.time()
        result = await self.ch_client.query(optimized_query, params)
        execution_time = time.time() - start_time
        
        # Cache result
        if use_cache and execution_time > 1.0:  # Cache slow queries
            await self._set_cache(cache_key, result.to_json(), self.cache_ttl)
        
        # Log query metrics
        await self._log_query_metrics(query, execution_time, result.shape[0])
        
        return result
    
    def _optimize_query(self, query: str) -> str:
        """
        Apply query optimizations.
        """
        # Use materialized views where applicable
        optimizations = [
            (r'FROM\s+fct_sales_orders', 'FROM fct_sales_orders FINAL'),
            (r'SELECT\s+\*\s+FROM\s+agg_daily_sales',
             'SELECT order_date, customer_sk, product_sk, ' +
             'countMerge(total_orders) as total_orders, ' +
             'sumMerge(total_revenue) as total_revenue ' +
             'FROM agg_daily_sales'),
        ]
        
        for pattern, replacement in optimizations:
            query = re.sub(pattern, replacement, query, flags=re.IGNORECASE)
        
        return query
    
    def _generate_cache_key(self, query: str, params: dict) -> str:
        """
        Generate deterministic cache key for query.
        """
        key_data = f"{query}:{json.dumps(params, sort_keys=True)}"
        return f"analytics:query:{hashlib.sha256(key_data.encode()).hexdigest()[:16]}"
```

### 2.4 Dashboard Framework

The dashboard framework is built on Apache Superset with custom extensions for Smart Dairy's specific requirements.

#### Superset Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    APACHE SUPERSET DASHBOARD FRAMEWORK                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      FRONTEND LAYER (React)                         │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │  Dashboard  │  │   Explore   │  │   SQL Lab   │  │   Alerts    ││   │
│  │  │   Builder   │  │   (Charts)  │  │  (Ad-hoc)   │  │  & Reports  ││   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘│   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │  Chart      │  │   Filter    │  │   Native    │  │   Custom    ││   │
│  │  │  Components │  │   Controls  │  │   Filters   │  │   Plugins   ││   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘│   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    │                                        │
│                                    ▼                                        │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      API LAYER (Flask)                              │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │  Dataset    │  │   Chart     │  │  Dashboard  │  │   Query     ││   │
│  │  │    API      │  │    API      │  │    API      │  │    API      ││   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘│   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │  Security   │  │   Cache     │  │  Async      │  │   Export    ││   │
│  │  │  Manager    │  │   Manager   │  │  Queries    │  │   Service   ││   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘│   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    │                                        │
│                                    ▼                                        │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      BACKEND SERVICES                               │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │  Metadata   │  │   Query     │  │   Celery    │  │   Cache     ││   │
│  │  │  Database   │  │   Engine    │  │   Workers   │  │   (Redis)   ││   │
│  │  │ (PostgreSQL)│  │             │  │             │  │             ││   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘│   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### Custom Superset Configuration

```python
# superset_config.py - Smart Dairy Custom Configuration

import os
from celery.schedules import crontab
from cachelib.redis import RedisCache

# Database Configuration
SQLALCHEMY_DATABASE_URI = 'postgresql://superset:${DB_PASSWORD}@postgres:5432/superset_metadata'

# ClickHouse Database Connection
DATABASES = {
    'smart_dairy_clickhouse': {
        'SQLALCHEMY_URI': 'clickhouse+native://analytics_user:${CH_PASSWORD}@clickhouse:9000/smart_dairy_marts',
        'CACHE_DEFAULT_TIMEOUT': 300,
        'CACHE_CONFIG': {
            'CACHE_TYPE': 'RedisCache',
            'CACHE_DEFAULT_TIMEOUT': 300,
            'CACHE_KEY_PREFIX': 'superset_clickhouse_',
            'CACHE_REDIS_URL': 'redis://redis:6379/1'
        }
    }
}

# Cache Configuration
CACHE_CONFIG = {
    'CACHE_TYPE': 'RedisCache',
    'CACHE_DEFAULT_TIMEOUT': 86400,
    'CACHE_KEY_PREFIX': 'superset_results_',
    'CACHE_REDIS_URL': 'redis://redis:6379/0'
}

DATA_CACHE_CONFIG = {
    'CACHE_TYPE': 'RedisCache',
    'CACHE_DEFAULT_TIMEOUT': 86400,
    'CACHE_KEY_PREFIX': 'superset_data_',
    'CACHE_REDIS_URL': 'redis://redis:6379/2'
}

# Feature Flags
FEATURE_FLAGS = {
    'ENABLE_TEMPLATE_PROCESSING': True,
    'ENABLE_TEMPLATE_REMOVE_FILTERS': True,
    'DASHBOARD_NATIVE_FILTERS': True,
    'DASHBOARD_CROSS_FILTERS': True,
    'DASHBOARD_RBAC': True,
    'EMBEDDED_SUPERSET': True,
    'ALERT_REPORTS': True,
    'ALERTS_ATTACH_REPORTS': True,
    'ALLOW_ADHOC_SUBQUERY': True,
    'DRILL_TO_DETAIL': True,
    'HORIZONTAL_FILTER_BAR': True,
}

# Celery Configuration for Async Queries
class CeleryConfig:
    broker_url = 'redis://redis:6379/3'
    result_backend = 'redis://redis:6379/4'
    
    imports = (
        'superset.sql_lab',
        'superset.tasks.scheduler',
    )
    
    beat_schedule = {
        'cache-warmup-daily': {
            'task': 'cache-warmup',
            'schedule': crontab(minute=0, hour=6),
            'kwargs': {
                'strategy_name': 'top_n_dashboards',
                'top_n': 10,
                'since': '7 days ago',
            },
        },
        'email-reports-daily': {
            'task': 'email_reports.schedule_hourly',
            'schedule': crontab(minute=0, hour='*'),
        },
        'alerts-check': {
            'task': 'alerts.check',
            'schedule': crontab(minute='*/5'),
        },
    }

CELERY_CONFIG = CeleryConfig

# Security Configuration
AUTH_TYPE = 'AUTH_DB'
SESSION_COOKIE_HTTPONLY = True
SESSION_COOKIE_SECURE = True
SESSION_COOKIE_SAMESITE = 'Lax'

# Custom Time Grain Functions for ClickHouse
TIME_GRAIN_FUNCTIONS = {
    'PT1M': 'toStartOfMinute({col})',
    'PT5M': 'toStartOfFiveMinutes({col})',
    'PT10M': 'toStartOfTenMinutes({col})',
    'PT15M': 'toStartOfFifteenMinutes({col})',
    'PT30M': 'toStartOfInterval({col}, INTERVAL 30 minute)',
    'PT1H': 'toStartOfHour({col})',
    'P1D': 'toStartOfDay({col})',
    'P1W': 'toStartOfWeek({col})',
    'P1M': 'toStartOfMonth({col})',
    'P3M': 'toStartOfQuarter({col})',
    'P1Y': 'toStartOfYear({col})',
}

# Custom Jinja Context for Dashboards
JINJA_CONTEXT_ADDONS = {
    'current_fiscal_year': lambda: get_current_fiscal_year(),
    'current_quarter': lambda: get_current_quarter(),
    'farm_regions': lambda: get_farm_regions(),
    'product_categories': lambda: get_product_categories(),
}

# Thumbnail Configuration
THUMBNAIL_SELENIUM_USER = 'admin'
THUMBNAIL_CACHE_CONFIG = {
    'CACHE_TYPE': 'RedisCache',
    'CACHE_DEFAULT_TIMEOUT': 86400,
    'CACHE_KEY_PREFIX': 'superset_thumbnails_',
    'CACHE_REDIS_URL': 'redis://redis:6379/5'
}

# Query Cost Estimation
QUERY_COST_FORMATTERS_BY_ENGINE = {
    'clickhouse': lambda cost: f"Estimated: {cost} rows scanned"
}

# Mapbox Token for Geospatial Visualizations
MAPBOX_API_KEY = os.environ.get('MAPBOX_API_KEY', '')
```

### 2.5 ML Pipeline

The machine learning pipeline supports predictive analytics with automated model training, deployment, and monitoring.

#### ML Pipeline Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      MACHINE LEARNING PIPELINE                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      DATA PREPARATION                               │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │  Feature    │  │   Feature   │  │   Data      │  │   Feature   ││   │
│  │  │  Store      │  │   Engineer  │  │   Split     │  │   Store     ││   │
│  │  │  (Feast)    │  │   (Spark)   │  │   (Train/Test)│  │   (Feast)   ││   │
│  │  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘│   │
│  │         └─────────────────┴─────────────────┴─────────────────┘     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    │                                        │
│                                    ▼                                        │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      MODEL TRAINING (MLflow)                        │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │  Experiment │  │   Model     │  │  Hyperpara- │  │   Model     ││   │
│  │  │  Tracking   │  │   Training  │  │  meter Tuning│  │   Registry  ││   │
│  │  │             │  │  (AutoML)   │  │  (Optuna)   │  │             ││   │
│  │  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘│   │
│  │         └─────────────────┴─────────────────┴─────────────────┘     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    │                                        │
│                                    ▼                                        │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      MODEL SERVING (KServe)                         │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │  Inference  │  │   Batch     │  │   A/B       │  │   Model     ││   │
│  │  │  Services   │  │   Prediction│  │   Testing   │  │   Monitoring││   │
│  │  │  (REST/gRPC)│  │             │  │             │  │             ││   │
│  │  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘│   │
│  │         └─────────────────┴─────────────────┴─────────────────┘     │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                    │                                        │
│                                    ▼                                        │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │                      PREDICTION CONSUMPTION                         │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐│   │
│  │  │  Dashboard  │  │   Embedded  │  │   Alerts    │  │   API       ││   │
│  │  │  Display    │  │   Predictions│  │   & Actions │  │   Webhooks  ││   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘│   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

#### ML Pipeline Code

```python
# ml_pipeline/demand_forecasting.py

import mlflow
import mlflow.sklearn
from feast import FeatureStore
from sklearn.ensemble import GradientBoostingRegressor, RandomForestRegressor
from sklearn.model_selection import TimeSeriesSplit, GridSearchCV
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import optuna

class DemandForecastingPipeline:
    """
    ML Pipeline for dairy product demand forecasting.
    """
    
    def __init__(self, feature_store_path: str):
        self.feature_store = FeatureStore(repo_path=feature_store_path)
        self.mlflow_tracking_uri = "http://mlflow:5000"
        mlflow.set_tracking_uri(self.mlflow_tracking_uri)
        mlflow.set_experiment("smart_dairy_demand_forecasting")
        
    def fetch_training_data(self, start_date: datetime, end_date: datetime) -> pd.DataFrame:
        """
        Fetch historical features from Feast feature store.
        """
        # Define entity dataframe
        entity_df = pd.DataFrame({
            'product_id': self._get_active_products(),
            'region': self._get_all_regions(),
            'event_timestamp': pd.date_range(start=start_date, end=end_date, freq='D')
        })
        
        # Retrieve features from feature store
        training_df = self.feature_store.get_historical_features(
            entity_df=entity_df,
            features=[
                "sales_features:daily_sales_7d_avg",
                "sales_features:daily_sales_30d_avg",
                "sales_features:weekly_sales_last_4w",
                "sales_features:month_over_month_growth",
                "sales_features:seasonal_index",
                "weather_features:temperature_avg",
                "weather_features:precipitation_mm",
                "calendar_features:is_weekend",
                "calendar_features:is_holiday",
                "calendar_features:days_to_holiday",
                "price_features:current_price",
                "price_features:price_change_7d",
                "competitor_features:competitor_price_avg",
                "marketing_features:promotion_active",
                "marketing_features:marketing_spend_7d"
            ]
        ).to_df()
        
        return training_df
    
    def engineer_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Additional feature engineering beyond Feast features.
        """
        df = df.copy()
        
        # Lag features
        df['sales_lag_1d'] = df.groupby('product_id')['daily_sales_7d_avg'].shift(1)
        df['sales_lag_7d'] = df.groupby('product_id')['daily_sales_7d_avg'].shift(7)
        df['sales_lag_30d'] = df.groupby('product_id')['daily_sales_7d_avg'].shift(30)
        
        # Rolling statistics
        df['sales_rolling_mean_7d'] = df.groupby('product_id')['daily_sales_7d_avg'].transform(
            lambda x: x.rolling(window=7, min_periods=1).mean()
        )
        df['sales_rolling_std_7d'] = df.groupby('product_id')['daily_sales_7d_avg'].transform(
            lambda x: x.rolling(window=7, min_periods=1).std()
        )
        
        # Price elasticity proxy
        df['price_elasticity_proxy'] = df['price_change_7d'] * df['month_over_month_growth']
        
        # Weather-sales interaction
        df['temp_sales_interaction'] = df['temperature_avg'] * df['seasonal_index']
        
        # Competition intensity
        df['price_competition_gap'] = df['current_price'] - df['competitor_price_avg']
        
        # Fill missing values
        df = df.fillna(method='ffill').fillna(0)
        
        return df
    
    def train_model(self, df: pd.DataFrame, model_type: str = 'gradient_boosting'):
        """
        Train demand forecasting model with hyperparameter tuning.
        """
        # Prepare features and target
        feature_cols = [col for col in df.columns if col not in [
            'product_id', 'region', 'event_timestamp', 'daily_sales_7d_avg', 'target'
        ]]
        
        X = df[feature_cols]
        y = df['target']  # Next day's sales
        
        # Time series cross-validation
        tscv = TimeSeriesSplit(n_splits=5)
        
        with mlflow.start_run(run_name=f"demand_forecast_{model_type}_{datetime.now().strftime('%Y%m%d')}"):
            
            # Hyperparameter optimization with Optuna
            def objective(trial):
                if model_type == 'gradient_boosting':
                    params = {
                        'n_estimators': trial.suggest_int('n_estimators', 50, 300),
                        'learning_rate': trial.suggest_float('learning_rate', 0.01, 0.3),
                        'max_depth': trial.suggest_int('max_depth', 3, 10),
                        'min_samples_split': trial.suggest_int('min_samples_split', 2, 20),
                        'min_samples_leaf': trial.suggest_int('min_samples_leaf', 1, 10),
                        'subsample': trial.suggest_float('subsample', 0.6, 1.0)
                    }
                    model = GradientBoostingRegressor(**params, random_state=42)
                else:
                    params = {
                        'n_estimators': trial.suggest_int('n_estimators', 50, 300),
                        'max_depth': trial.suggest_int('max_depth', 5, 30),
                        'min_samples_split': trial.suggest_int('min_samples_split', 2, 20),
                        'min_samples_leaf': trial.suggest_int('min_samples_leaf', 1, 10)
                    }
                    model = RandomForestRegressor(**params, random_state=42)
                
                # Cross-validation
                scores = []
                for train_idx, val_idx in tscv.split(X):
                    X_train, X_val = X.iloc[train_idx], X.iloc[val_idx]
                    y_train, y_val = y.iloc[train_idx], y.iloc[val_idx]
                    
                    model.fit(X_train, y_train)
                    y_pred = model.predict(X_val)
                    mae = mean_absolute_error(y_val, y_pred)
                    scores.append(mae)
                
                return np.mean(scores)
            
            # Run optimization
            study = optuna.create_study(direction='minimize')
            study.optimize(objective, n_trials=50)
            
            # Train final model with best parameters
            best_params = study.best_params
            
            if model_type == 'gradient_boosting':
                final_model = GradientBoostingRegressor(**best_params, random_state=42)
            else:
                final_model = RandomForestRegressor(**best_params, random_state=42)
            
            # Train on full dataset
            final_model.fit(X, y)
            
            # Calculate metrics
            y_pred = final_model.predict(X)
            metrics = {
                'mae': mean_absolute_error(y, y_pred),
                'rmse': np.sqrt(mean_squared_error(y, y_pred)),
                'r2': r2_score(y, y_pred),
                'mape': np.mean(np.abs((y - y_pred) / y)) * 100
            }
            
            # Log parameters and metrics
            mlflow.log_params(best_params)
            mlflow.log_metrics(metrics)
            
            # Log feature importance
            feature_importance = pd.DataFrame({
                'feature': feature_cols,
                'importance': final_model.feature_importances_
            }).sort_values('importance', ascending=False)
            
            mlflow.log_artifact(
                feature_importance.to_csv(index=False),
                "feature_importance.csv"
            )
            
            # Log model
            mlflow.sklearn.log_model(
                final_model,
                "model",
                registered_model_name="demand_forecasting_model"
            )
            
            # Log model signature
            signature = mlflow.models.infer_signature(X, y_pred)
            mlflow.sklearn.log_model(
                final_model,
                "model_with_signature",
                signature=signature,
                input_example=X.head(5)
            )
            
            return final_model, metrics
    
    def predict(self, product_id: str, region: str, 
                forecast_horizon: int = 30) -> pd.DataFrame:
        """
        Generate demand forecast for specific product and region.
        """
        # Load production model
        model = mlflow.sklearn.load_model(
            model_uri="models:/demand_forecasting_model/Production"
        )
        
        # Fetch online features
        entity_rows = [{"product_id": product_id, "region": region}]
        
        features = self.feature_store.get_online_features(
            features=[
                "sales_features:daily_sales_7d_avg",
                "sales_features:daily_sales_30d_avg",
                "sales_features:seasonal_index",
                "weather_features:temperature_avg",
                "calendar_features:is_weekend",
                "calendar_features:is_holiday",
                "price_features:current_price",
                "marketing_features:promotion_active"
            ],
            entity_rows=entity_rows
        ).to_df()
        
        # Generate predictions for forecast horizon
        predictions = []
        current_date = datetime.now()
        
        for day in range(forecast_horizon):
            forecast_date = current_date + timedelta(days=day)
            
            # Prepare feature vector
            feature_vector = features.copy()
            feature_vector['days_ahead'] = day
            feature_vector['forecast_date'] = forecast_date
            
            # Make prediction
            pred = model.predict(feature_vector)[0]
            
            predictions.append({
                'product_id': product_id,
                'region': region,
                'forecast_date': forecast_date,
                'predicted_demand': max(0, pred),  # Ensure non-negative
                'prediction_confidence': self._calculate_confidence(day)
            })
        
        return pd.DataFrame(predictions)
    
    def _calculate_confidence(self, days_ahead: int) -> float:
        """
        Calculate prediction confidence based on forecast horizon.
        """
        # Confidence decreases as we forecast further
        return max(0.5, 1.0 - (days_ahead * 0.015))
```


---

## 3. Daily Task Allocation

### Day 371: Project Kickoff and Data Warehouse Foundation

#### Developer 1 (Lead Dev - Data Architecture)

**Morning Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D1-371-1 | Initialize ClickHouse cluster configuration | 2h | `clickhouse_config.yml` |
| D1-371-2 | Design data warehouse layer architecture | 2h | Architecture diagram |

**Detailed Task: ClickHouse Cluster Configuration**

```yaml
# config/clickhouse/cluster_config.yml
cluster:
  name: smart_dairy_bi_cluster
  
  # Cluster topology
  topology:
    shards:
      - shard_id: 1
        weight: 1
        replicas:
          - host: clickhouse-shard1-replica1.smartdairy.internal
            port: 9000
          - host: clickhouse-shard1-replica2.smartdairy.internal
            port: 9000
      - shard_id: 2
        weight: 1
        replicas:
          - host: clickhouse-shard2-replica1.smartdairy.internal
            port: 9000
          - host: clickhouse-shard2-replica2.smartdairy.internal
            port: 9000
      - shard_id: 3
        weight: 1
        replicas:
          - host: clickhouse-shard3-replica1.smartdairy.internal
            port: 9000
          - host: clickhouse-shard3-replica2.smartdairy.internal
            port: 9000
  
  # Distributed DDL settings
  settings:
    distributed_ddl:
      path: /clickhouse/task_queue/ddl
      profile: default
    
  # Storage policies
  storage_policies:
    default_policy:
      volumes:
        - name: hot_storage
          disk: default
          max_data_part_size_bytes: 1073741824  # 1GB
          perform_ttl_move_on_insert: true
        - name: cold_storage
          disk: s3_disk
          perform_ttl_move_on_insert: false
    
  # S3 disk configuration for cold storage
  s3_disk:
    type: s3
    endpoint: https://s3.smartdairy.internal/clickhouse-data/
    access_key_id: ${S3_ACCESS_KEY}
    secret_access_key: ${S3_SECRET_KEY}
    region: us-east-1
    metadata_path: /var/lib/clickhouse/disks/s3/
```

**Afternoon Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D1-371-3 | Define database schemas for ODS layer | 2h | SQL schema files |
| D1-371-4 | Configure user roles and permissions | 1.5h | RBAC configuration |
| D1-371-5 | Daily standup and documentation | 0.5h | Status report |

**ClickHouse Database Schema Definition**

```sql
-- scripts/clickhouse/01_create_databases.sql
-- Day 371: Initial database creation for Smart Dairy BI

-- Create databases for different layers
CREATE DATABASE IF NOT EXISTS smart_dairy_ods;
CREATE DATABASE IF NOT EXISTS smart_dairy_dwd;
CREATE DATABASE IF NOT EXISTS smart_dairy_dws;
CREATE DATABASE IF NOT EXISTS smart_dairy_ads;

-- Create monitoring database
CREATE DATABASE IF NOT EXISTS system_metrics;

-- Configure settings for each database
-- ODS Layer: Raw data, shorter retention
ALTER DATABASE smart_dairy_ods SETTINGS 
    max_table_size_to_drop = 0,
    max_partition_size_to_drop = 0;

-- DWD Layer: Detailed facts and dimensions
ALTER DATABASE smart_dairy_dwd SETTINGS 
    max_table_size_to_drop = 0,
    max_partition_size_to_drop = 0;

-- DWS Layer: Aggregations
ALTER DATABASE smart_dairy_dws SETTINGS 
    max_table_size_to_drop = 0,
    max_partition_size_to_drop = 0;

-- ADS Layer: Application data
ALTER DATABASE smart_dairy_ads SETTINGS 
    max_table_size_to_drop = 0,
    max_partition_size_to_drop = 0;
```

---

#### Developer 2 (Backend Dev - Data Engineering)

**Morning Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D2-371-1 | Set up Apache Airflow environment | 2h | Airflow deployment |
| D2-371-2 | Configure source database connections | 2h | Connection configs |

**Airflow Configuration**

```python
# airflow/config/airflow.cfg
[core]
dags_folder = /opt/airflow/dags
base_log_folder = /opt/airflow/logs
remote_logging = True
remote_log_conn_id = s3_logs_connection
remote_base_log_folder = s3://smart-dairy-airflow-logs/
encrypt_s3_logs = True

executor = CeleryExecutor
parallelism = 32
max_active_tasks_per_dag = 16
dag_concurrency = 16
max_active_runs_per_dag = 3

load_examples = False
plugins_folder = /opt/airflow/plugins

[database]
sql_alchemy_conn = postgresql+psycopg2://airflow:${DB_PASSWORD}@postgres:5432/airflow
sql_alchemy_pool_size = 10
sql_alchemy_max_overflow = 20
sql_alchemy_pool_recycle = 3600

[celery]
broker_url = redis://:${REDIS_PASSWORD}@redis:6379/0
result_backend = db+postgresql://airflow:${DB_PASSWORD}@postgres:5432/airflow
worker_concurrency = 8
worker_prefetch_multiplier = 1
task_track_started = True
task_adoption_timeout = 600

[celery_broker_transport_options]
visibility_timeout = 43200

[scheduler]
min_file_process_interval = 30
dag_dir_list_interval = 300
catchup_by_default = False
max_tis_per_query = 512
use_row_level_locking = True

[elasticsearch]
json_format = True
log_id_template = {dag_id}-{task_id}-{execution_date}-{try_number}

[elasticsearch_configs]
use_ssl = False
verify_certs = True
```

**Afternoon Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D2-371-3 | Implement first source data extractor | 2h | Extraction DAG |
| D2-371-4 | Write data validation tests | 1.5h | Test suite |
| D2-371-5 | Code review and documentation | 0.5h | Documentation |

```python
# airflow/dags/extract_erp_sales_orders.py
"""
DAG for extracting sales orders from ERP database.
Day 371: Initial extraction pipeline implementation.
"""

from airflow import DAG
from airflow.providers.postgres.operators.postgres import PostgresOperator
from airflow.providers.clickhouse.operators.clickhouse import ClickHouseOperator
from airflow.operators.python import PythonOperator
from airflow.sensors.external_task import ExternalTaskSensor
from airflow.utils.dates import days_ago
from airflow.models import Variable
from datetime import datetime, timedelta
import logging

# Configure logging
logger = logging.getLogger(__name__)

# DAG default arguments
default_args = {
    'owner': 'data_engineering',
    'depends_on_past': False,
    'email': ['data-team@smartdairy.com'],
    'email_on_failure': True,
    'email_on_retry': False,
    'retries': 3,
    'retry_delay': timedelta(minutes=5),
    'execution_timeout': timedelta(hours=2),
}

# DAG definition
dag = DAG(
    'extract_erp_sales_orders',
    default_args=default_args,
    description='Extract sales orders from ERP to ClickHouse ODS',
    schedule_interval='0 */6 * * *',  # Every 6 hours
    start_date=days_ago(1),
    tags=['erp', 'sales', 'extraction'],
    catchup=False,
    max_active_runs=1,
    concurrency=4,
)

# Task 1: Check source database connectivity
check_source_db = PostgresOperator(
    task_id='check_source_connection',
    postgres_conn_id='erp_postgres_conn',
    sql="""
    SELECT 
        COUNT(*) as total_orders,
        MAX(updated_at) as last_update
    FROM sales_orders
    WHERE updated_at >= '{{ ds }}'::timestamp - INTERVAL '7 days';
    """,
    dag=dag,
)

# Task 2: Extract incremental data to staging
def extract_sales_orders(**context):
    """
    Extract sales orders incrementally based on last extraction time.
    """
    from airflow.providers.postgres.hooks.postgres import PostgresHook
    from airflow.providers.clickhouse.hooks.clickhouse import ClickHouseHook
    import pandas as pd
    from io import StringIO
    
    execution_date = context['execution_date']
    ds = context['ds']
    
    # Get last extraction timestamp
    ch_hook = ClickHouseHook(clickhouse_conn_id='clickhouse_conn')
    last_extract_result = ch_hook.get_records(f"""
        SELECT MAX(_extracted_at) as last_extract
        FROM smart_dairy_ods.sales_orders
        WHERE order_date >= '{ds}'::Date - 30
    """)
    
    last_extract = last_extract_result[0][0] if last_extract_result[0][0] else '1970-01-01'
    logger.info(f"Extracting records updated since: {last_extract}")
    
    # Extract from PostgreSQL
    pg_hook = PostgresHook(postgres_conn_id='erp_postgres_conn')
    sql = f"""
    SELECT 
        order_id,
        order_number,
        customer_id,
        product_id,
        quantity,
        unit_price,
        discount_amount,
        tax_amount,
        total_amount,
        order_status,
        order_date,
        expected_delivery_date,
        actual_delivery_date,
        created_at,
        updated_at,
        deleted_at,
        '{execution_date}'::timestamp as _extracted_at,
        'incremental' as _extraction_type
    FROM sales_orders
    WHERE updated_at > '{last_extract}'::timestamp
        OR created_at > '{last_extract}'::timestamp
    ORDER BY updated_at ASC
    """
    
    # Get data in chunks to handle large datasets
    chunk_size = 10000
    total_rows = 0
    
    for chunk_df in pg_hook.get_pandas_df_by_chunks(sql, chunksize=chunk_size):
        if not chunk_df.empty:
            # Convert timestamps to proper format
            timestamp_cols = ['order_date', 'expected_delivery_date', 'actual_delivery_date', 
                            'created_at', 'updated_at', 'deleted_at', '_extracted_at']
            for col in timestamp_cols:
                if col in chunk_df.columns:
                    chunk_df[col] = pd.to_datetime(chunk_df[col], errors='coerce')
            
            # Insert into ClickHouse
            ch_hook.insert_df(
                database='smart_dairy_ods',
                table='sales_orders',
                df=chunk_df,
                replace=False
            )
            total_rows += len(chunk_df)
            logger.info(f"Inserted {len(chunk_df)} rows, total: {total_rows}")
    
    # Push metrics to XCom
    context['ti'].xcom_push(key='extracted_rows', value=total_rows)
    logger.info(f"Total rows extracted: {total_rows}")
    
    return f"Successfully extracted {total_rows} sales orders"

extract_task = PythonOperator(
    task_id='extract_sales_orders',
    python_callable=extract_sales_orders,
    provide_context=True,
    dag=dag,
)

# Task 3: Validate data quality
def validate_extracted_data(**context):
    """
    Validate extracted data for quality issues.
    """
    from airflow.providers.clickhouse.hooks.clickhouse import ClickHouseHook
    
    ti = context['ti']
    extracted_rows = ti.xcom_pull(task_ids='extract_sales_orders', key='extracted_rows')
    ds = context['ds']
    
    ch_hook = ClickHouseHook(clickhouse_conn_id='clickhouse_conn')
    
    # Quality checks
    checks = {
        'null_order_ids': f"""
            SELECT COUNT(*) 
            FROM smart_dairy_ods.sales_orders 
            WHERE order_id IS NULL 
                AND toDate(_extracted_at) = '{ds}'::Date
        """,
        'negative_amounts': f"""
            SELECT COUNT(*) 
            FROM smart_dairy_ods.sales_orders 
            WHERE total_amount < 0 
                AND toDate(_extracted_at) = '{ds}'::Date
        """,
        'future_orders': f"""
            SELECT COUNT(*) 
            FROM smart_dairy_ods.sales_orders 
            WHERE order_date > today() + 30
                AND toDate(_extracted_at) = '{ds}'::Date
        """,
    }
    
    validation_results = {}
    for check_name, check_sql in checks.items():
        result = ch_hook.get_records(check_sql)
        violation_count = result[0][0] if result else 0
        validation_results[check_name] = violation_count
        
        if violation_count > 0:
            logger.warning(f"Data quality check '{check_name}' failed with {violation_count} violations")
    
    # Push results
    ti.xcom_push(key='validation_results', value=validation_results)
    
    # Fail if critical issues found
    if validation_results.get('null_order_ids', 0) > 0:
        raise ValueError(f"Critical: Found {validation_results['null_order_ids']} records with null order IDs")
    
    return validation_results

validate_task = PythonOperator(
    task_id='validate_data',
    python_callable=validate_extracted_data,
    provide_context=True,
    dag=dag,
)

# Task 4: Update extraction metadata
update_metadata = ClickHouseOperator(
    task_id='update_extraction_metadata',
    clickhouse_conn_id='clickhouse_conn',
    sql="""
    INSERT INTO smart_dairy_ads.etl_metadata (
        dag_id,
        run_id,
        execution_date,
        source_system,
        source_table,
        target_table,
        records_processed,
        extraction_type,
        status,
        completed_at
    )
    SELECT 
        '{{ dag.dag_id }}' as dag_id,
        '{{ run_id }}' as run_id,
        '{{ execution_date }}'::DateTime as execution_date,
        'erp_postgres' as source_system,
        'sales_orders' as source_table,
        'smart_dairy_ods.sales_orders' as target_table,
        {{ ti.xcom_pull(task_ids='extract_sales_orders', key='extracted_rows') }} as records_processed,
        'incremental' as extraction_type,
        'success' as status,
        now() as completed_at;
    """,
    dag=dag,
)

# Define task dependencies
check_source_db >> extract_task >> validate_task >> update_metadata
```

---

#### Developer 3 (Frontend Dev - Dashboards)

**Morning Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D3-371-1 | Set up Superset development environment | 2h | Local Superset instance |
| D3-371-2 | Create database connections in Superset | 2h | Connection configurations |

**Superset Database Connection Setup**

```python
# superset/connections/clickhouse_connection.py
"""
Smart Dairy ClickHouse connection configuration for Superset.
Day 371: Database connection setup.
"""

from superset.models.core import Database
from superset import db
import logging

logger = logging.getLogger(__name__)

# ClickHouse connection configuration
CLICKHOUSE_CONNECTION_CONFIG = {
    'database_name': 'Smart Dairy ClickHouse',
    'sqlalchemy_uri': 'clickhouse+native://analytics_user:${CH_PASSWORD}@clickhouse:9000/smart_dairy_marts',
    'cache_timeout': 300,
    'expose_in_sqllab': True,
    'allow_run_async': True,
    'allow_ctas': True,
    'allow_cvas': True,
    'allow_dml': True,
    'allow_file_upload': False,
    'extra': {
        'metadata_params': {},
        'engine_params': {
            'connect_args': {
                'send_receive_timeout': 300,
                'sync_request_timeout': 5,
                'compress': True,
                'settings': {
                    'max_execution_time': 300,
                    'max_memory_usage': 20000000000,  # 20GB
                }
            }
        },
        'metadata_cache_timeout': {
            'schema_cache_timeout': 86400,
            'table_cache_timeout': 86400,
        },
        'schemas_allowed_for_file_upload': [],
        'cost_estimate_enabled': True,
        'allows_virtual_table_explore': True,
    }
}

def create_clickhouse_connection():
    """
    Create ClickHouse database connection in Superset.
    """
    try:
        # Check if connection already exists
        existing = db.session.query(Database).filter_by(
            database_name=CLICKHOUSE_CONNECTION_CONFIG['database_name']
        ).first()
        
        if existing:
            logger.info(f"Connection {CLICKHOUSE_CONNECTION_CONFIG['database_name']} already exists")
            return existing
        
        # Create new connection
        database = Database(
            database_name=CLICKHOUSE_CONNECTION_CONFIG['database_name'],
            sqlalchemy_uri=CLICKHOUSE_CONNECTION_CONFIG['sqlalchemy_uri'],
            cache_timeout=CLICKHOUSE_CONNECTION_CONFIG['cache_timeout'],
            expose_in_sqllab=CLICKHOUSE_CONNECTION_CONFIG['expose_in_sqllab'],
            allow_run_async=CLICKHOUSE_CONNECTION_CONFIG['allow_run_async'],
            allow_ctas=CLICKHOUSE_CONNECTION_CONFIG['allow_ctas'],
            allow_cvas=CLICKHOUSE_CONNECTION_CONFIG['allow_cvas'],
            allow_dml=CLICKHOUSE_CONNECTION_CONFIG['allow_dml'],
            allow_file_upload=CLICKHOUSE_CONNECTION_CONFIG['allow_file_upload'],
            extra=json.dumps(CLICKHOUSE_CONNECTION_CONFIG['extra'])
        )
        
        db.session.add(database)
        db.session.commit()
        
        logger.info(f"Created ClickHouse connection: {database.database_name}")
        return database
        
    except Exception as e:
        logger.error(f"Failed to create ClickHouse connection: {str(e)}")
        db.session.rollback()
        raise

# Test connection function
def test_clickhouse_connection(database_id: int) -> bool:
    """
    Test the ClickHouse database connection.
    """
    try:
        database = db.session.query(Database).get(database_id)
        if not database:
            logger.error(f"Database with ID {database_id} not found")
            return False
        
        # Test connection
        with database.get_sqla_engine() as engine:
            result = engine.execute("SELECT 1").fetchone()
            if result and result[0] == 1:
                logger.info("ClickHouse connection test successful")
                return True
            
        return False
        
    except Exception as e:
        logger.error(f"ClickHouse connection test failed: {str(e)}")
        return False
```

**Afternoon Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D3-371-3 | Create first sample chart component | 2h | React chart component |
| D3-371-4 | Design dashboard layout structure | 1.5h | Layout mockups |
| D3-371-5 | Team sync and documentation | 0.5h | Meeting notes |

```typescript
// frontend/src/components/charts/SalesTrendChart.tsx
/**
 * Sales Trend Chart Component
 * Day 371: Initial chart component implementation
 */

import React, { useEffect, useState, useCallback } from 'react';
import { Line } from 'react-chartjs-2';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
  ChartData,
  ChartOptions
} from 'chart.js';
import { format, subDays } from 'date-fns';
import { useAnalyticsQuery } from '../../hooks/useAnalyticsQuery';
import { ChartSkeleton } from './ChartSkeleton';
import { DateRangePicker } from '../common/DateRangePicker';
import { ExportButton } from '../common/ExportButton';

// Register Chart.js components
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

// Types
interface SalesTrendData {
  date: string;
  total_revenue: number;
  order_count: number;
  average_order_value: number;
}

interface SalesTrendChartProps {
  productId?: string;
  region?: string;
  className?: string;
}

export const SalesTrendChart: React.FC<SalesTrendChartProps> = ({
  productId,
  region,
  className = ''
}) => {
  // State
  const [dateRange, setDateRange] = useState({
    startDate: subDays(new Date(), 30),
    endDate: new Date()
  });
  
  // Fetch data using custom hook
  const { data, loading, error, refetch } = useAnalyticsQuery<SalesTrendData[]>({
    endpoint: '/api/analytics/sales-trend',
    params: {
      startDate: dateRange.startDate.toISOString(),
      endDate: dateRange.endDate.toISOString(),
      productId,
      region,
      granularity: 'daily'
    },
    refreshInterval: 300000 // 5 minutes
  });

  // Chart data transformation
  const chartData: ChartData<'line'> = {
    labels: data?.map(d => format(new Date(d.date), 'MMM dd')) || [],
    datasets: [
      {
        label: 'Revenue',
        data: data?.map(d => d.total_revenue) || [],
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        fill: true,
        tension: 0.4,
        pointRadius: 3,
        pointHoverRadius: 6,
        yAxisID: 'y'
      },
      {
        label: 'Order Count',
        data: data?.map(d => d.order_count) || [],
        borderColor: 'rgb(16, 185, 129)',
        backgroundColor: 'transparent',
        borderDash: [5, 5],
        tension: 0.4,
        pointRadius: 2,
        pointHoverRadius: 4,
        yAxisID: 'y1'
      }
    ]
  };

  // Chart options
  const chartOptions: ChartOptions<'line'> = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
      mode: 'index',
      intersect: false
    },
    plugins: {
      title: {
        display: true,
        text: 'Sales Trend Analysis',
        font: { size: 16, weight: 'bold' },
        padding: { top: 10, bottom: 20 }
      },
      legend: {
        display: true,
        position: 'top',
        align: 'end',
        labels: {
          usePointStyle: true,
          padding: 20
        }
      },
      tooltip: {
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        padding: 12,
        cornerRadius: 8,
        callbacks: {
          label: (context) => {
            const label = context.dataset.label || '';
            const value = context.parsed.y;
            if (label === 'Revenue') {
              return `${label}: $${value.toLocaleString()}`;
            }
            return `${label}: ${value.toLocaleString()}`;
          }
        }
      }
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: {
          maxTicksLimit: 10,
          maxRotation: 45
        }
      },
      y: {
        type: 'linear',
        display: true,
        position: 'left',
        title: {
          display: true,
          text: 'Revenue ($)'
        },
        grid: {
          color: 'rgba(0, 0, 0, 0.05)'
        },
        ticks: {
          callback: (value) => `$${Number(value).toLocaleString()}`
        }
      },
      y1: {
        type: 'linear',
        display: true,
        position: 'right',
        title: {
          display: true,
          text: 'Order Count'
        },
        grid: { display: false },
        ticks: {
          callback: (value) => Number(value).toLocaleString()
        }
      }
    }
  };

  // Export handlers
  const handleExportCSV = useCallback(() => {
    if (!data) return;
    
    const csvContent = [
      ['Date', 'Revenue', 'Order Count', 'AOV'].join(','),
      ...data.map(row => [
        row.date,
        row.total_revenue,
        row.order_count,
        row.average_order_value
      ].join(','))
    ].join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `sales-trend-${format(new Date(), 'yyyy-MM-dd')}.csv`;
    link.click();
    window.URL.revokeObjectURL(url);
  }, [data]);

  // Render
  if (loading && !data) {
    return <ChartSkeleton className={className} height={400} />;
  }

  if (error) {
    return (
      <div className={`p-4 bg-red-50 border border-red-200 rounded-lg ${className}`}>
        <p className="text-red-600">Error loading chart: {error.message}</p>
        <button 
          onClick={refetch}
          className="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          Retry
        </button>
      </div>
    );
  }

  return (
    <div className={`bg-white rounded-lg shadow-sm border border-gray-200 p-6 ${className}`}>
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h3 className="text-lg font-semibold text-gray-900">Sales Trend</h3>
        
        <div className="flex items-center gap-3">
          <DateRangePicker
            startDate={dateRange.startDate}
            endDate={dateRange.endDate}
            onChange={setDateRange}
            presets={[
              { label: '7 Days', days: 7 },
              { label: '30 Days', days: 30 },
              { label: '90 Days', days: 90 }
            ]}
          />
          <ExportButton 
            onExport={handleExportCSV}
            label="Export CSV"
          />
        </div>
      </div>

      {/* Chart */}
      <div className="relative h-80">
        <Line data={chartData} options={chartOptions} />
      </div>

      {/* Summary Stats */}
      {data && (
        <div className="mt-6 grid grid-cols-3 gap-4 pt-4 border-t border-gray-100">
          <div>
            <p className="text-sm text-gray-500">Total Revenue</p>
            <p className="text-xl font-bold text-gray-900">
              ${data.reduce((sum, d) => sum + d.total_revenue, 0).toLocaleString()}
            </p>
          </div>
          <div>
            <p className="text-sm text-gray-500">Total Orders</p>
            <p className="text-xl font-bold text-gray-900">
              {data.reduce((sum, d) => sum + d.order_count, 0).toLocaleString()}
            </p>
          </div>
          <div>
            <p className="text-sm text-gray-500">Avg. Order Value</p>
            <p className="text-xl font-bold text-gray-900">
              ${Math.round(
                data.reduce((sum, d) => sum + d.total_revenue, 0) /
                data.reduce((sum, d) => sum + d.order_count, 0)
              ).toLocaleString()}
            </p>
          </div>
        </div>
      )}
    </div>
  );
};

export default SalesTrendChart;
```

---

### Day 372: Data Warehouse Schema Implementation

#### Developer 1 (Lead Dev - Data Architecture)

**Morning Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D1-372-1 | Create dimension table schemas | 2h | SQL DDL files |
| D1-372-2 | Create fact table schemas | 2h | SQL DDL files |

**Dimension Table Schemas**

```sql
-- scripts/clickhouse/02_dimension_tables.sql
-- Day 372: Dimension table creation for Smart Dairy BI

USE smart_dairy_dwd;

-- ============================================
-- CUSTOMER DIMENSION
-- ============================================
CREATE TABLE IF NOT EXISTS dim_customers (
    customer_sk UInt64,
    customer_id UInt64,
    customer_code String,
    customer_name String,
    customer_type LowCardinality(String),  -- Individual, Business, Government
    customer_segment LowCardinality(String),  -- Premium, Standard, Basic
    
    -- Contact Information
    primary_email String,
    primary_phone String,
    
    -- Address Information
    address_line1 String,
    address_line2 String,
    city String,
    state_province String,
    postal_code String,
    country LowCardinality(String) DEFAULT 'Kenya',
    region LowCardinality(String),  -- North, South, East, West, Central
    
    -- Geographic coordinates for mapping
    latitude Float64,
    longitude Float64,
    
    -- Business Information
    tax_id String,
    credit_limit Decimal(18, 4),
    payment_terms LowCardinality(String),
    
    -- Customer metrics
    first_order_date Date,
    last_order_date Date,
    total_lifetime_value Decimal(18, 4),
    total_orders UInt32,
    
    -- SCD Type 2 columns
    valid_from DateTime DEFAULT '1970-01-01 00:00:00',
    valid_to DateTime DEFAULT '9999-12-31 23:59:59',
    is_current UInt8 DEFAULT 1,
    
    -- Metadata
    created_at DateTime DEFAULT now(),
    updated_at DateTime DEFAULT now(),
    source_system LowCardinality(String) DEFAULT 'erp'
)
ENGINE = ReplacingMergeTree(updated_at)
PARTITION BY toYYYYMM(created_at)
ORDER BY (customer_sk, valid_from)
SETTINGS index_granularity = 8192;

-- Customer dimension lookup view
CREATE VIEW IF NOT EXISTS dim_customers_current AS
SELECT *
FROM dim_customers
WHERE is_current = 1
FINAL;

-- ============================================
-- PRODUCT DIMENSION
-- ============================================
CREATE TABLE IF NOT EXISTS dim_products (
    product_sk UInt64,
    product_id UInt64,
    product_code String,
    product_name String,
    product_description String,
    
    -- Categorization
    category_id UInt64,
    category_name LowCardinality(String),
    subcategory_id UInt64,
    subcategory_name LowCardinality(String),
    product_family LowCardinality(String),
    
    -- Product type
    product_type LowCardinality(String),  -- Raw Milk, Pasteurized, UHT, Cheese, Butter, Yogurt, etc.
    fat_content LowCardinality(String),  -- Full Cream, Low Fat, Skim
    packaging_type LowCardinality(String),  -- Bottle, Carton, Pouch, Bulk
    unit_of_measure LowCardinality(String),  -- Liter, Kg, Pack
    
    -- Pricing
    standard_cost Decimal(18, 4),
    list_price Decimal(18, 4),
    
    -- Attributes
    shelf_life_days UInt16,
    storage_temperature_min Decimal(5, 2),
    storage_temperature_max Decimal(5, 2),
    is_organic UInt8 DEFAULT 0,
    is_halal UInt8 DEFAULT 0,
    
    -- Status
    product_status LowCardinality(String) DEFAULT 'Active',  -- Active, Discontinued, Pending
    launch_date Date,
    discontinuation_date Nullable(Date),
    
    -- SCD Type 2 columns
    valid_from DateTime DEFAULT '1970-01-01 00:00:00',
    valid_to DateTime DEFAULT '9999-12-31 23:59:59',
    is_current UInt8 DEFAULT 1,
    
    -- Metadata
    created_at DateTime DEFAULT now(),
    updated_at DateTime DEFAULT now(),
    source_system LowCardinality(String) DEFAULT 'erp'
)
ENGINE = ReplacingMergeTree(updated_at)
PARTITION BY toYYYYMM(created_at)
ORDER BY (product_sk, valid_from)
SETTINGS index_granularity = 8192;

-- ============================================
-- TIME DIMENSION
-- ============================================
CREATE TABLE IF NOT EXISTS dim_date (
    date_key UInt32,
    full_date Date,
    day_of_week UInt8,
    day_name LowCardinality(String),
    day_of_month UInt8,
    day_of_year UInt16,
    
    week_of_year UInt8,
    week_start_date Date,
    week_end_date Date,
    
    month_number UInt8,
    month_name LowCardinality(String),
    month_start_date Date,
    month_end_date Date,
    days_in_month UInt8,
    
    quarter UInt8,
    quarter_name LowCardinality(String),
    quarter_start_date Date,
    quarter_end_date Date,
    
    year_number UInt16,
    year_start_date Date,
    year_end_date Date,
    
    -- Fiscal calendar (April - March)
    fiscal_year UInt16,
    fiscal_quarter UInt8,
    fiscal_month UInt8,
    
    -- Business flags
    is_weekend UInt8,
    is_holiday UInt8,
    holiday_name LowCardinality(String),
    is_working_day UInt8,
    
    -- Kenyan calendar specifics
    is_ramadan UInt8,
    is_eid_al_fitr UInt8,
    is_eid_al_adha UInt8,
    is_madaraka_day UInt8,
    is_mashujaa_day UInt8,
    is_jamhuri_day UInt8,
    
    -- Prior period references
    same_day_last_year Date,
    same_day_last_month Date
)
ENGINE = MergeTree()
PARTITION BY year_number
ORDER BY (date_key)
SETTINGS index_granularity = 8192;

-- Populate date dimension
INSERT INTO dim_date
SELECT
    toYYYYMMDD(d) as date_key,
    d as full_date,
    toDayOfWeek(d) as day_of_week,
    ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][toDayOfWeek(d)] as day_name,
    toDayOfMonth(d) as day_of_month,
    toDayOfYear(d) as day_of_year,
    toWeek(d) as week_of_year,
    toMonday(d) as week_start_date,
    toMonday(d) + 6 as week_end_date,
    toMonth(d) as month_number,
    ['January', 'February', 'March', 'April', 'May', 'June', 
     'July', 'August', 'September', 'October', 'November', 'December'][toMonth(d)] as month_name,
    toStartOfMonth(d) as month_start_date,
    toLastDayOfMonth(d) as month_end_date,
    toDayOfMonth(toLastDayOfMonth(d)) as days_in_month,
    toQuarter(d) as quarter,
    ['Q1', 'Q2', 'Q3', 'Q4'][toQuarter(d)] as quarter_name,
    toStartOfQuarter(d) as quarter_start_date,
    toLastDayOfQuarter(d) as quarter_end_date,
    toYear(d) as year_number,
    toStartOfYear(d) as year_start_date,
    toLastDayOfYear(d) as year_end_date,
    -- Fiscal year: April - March
    multiIf(
        toMonth(d) >= 4, toYear(d),
        toYear(d) - 1
    ) as fiscal_year,
    multiIf(
        toMonth(d) IN (4, 5, 6), 1,
        toMonth(d) IN (7, 8, 9), 2,
        toMonth(d) IN (10, 11, 12), 3,
        4
    ) as fiscal_quarter,
    multiIf(
        toMonth(d) >= 4, toMonth(d) - 3,
        toMonth(d) + 9
    ) as fiscal_month,
    toDayOfWeek(d) IN (6, 7) as is_weekend,
    0 as is_holiday,  -- Populate separately with actual holidays
    '' as holiday_name,
    toDayOfWeek(d) NOT IN (6, 7) as is_working_day,
    0 as is_ramadan,
    0 as is_eid_al_fitr,
    0 as is_eid_al_adha,
    toMonth(d) = 6 AND toDayOfMonth(d) = 1 as is_madaraka_day,
    toMonth(d) = 10 AND toDayOfMonth(d) = 20 as is_mashujaa_day,
    toMonth(d) = 12 AND toDayOfMonth(d) = 12 as is_jamhuri_day,
    d - INTERVAL 1 YEAR as same_day_last_year,
    d - INTERVAL 1 MONTH as same_day_last_month
FROM (
    SELECT arrayJoin(range(
        toUInt32(toDate('2020-01-01')),
        toUInt32(toDate('2030-12-31'))
    )) as days_since_epoch,
    toDate(days_since_epoch) as d
);

-- ============================================
-- FARM/LOCATION DIMENSION
-- ============================================
CREATE TABLE IF NOT EXISTS dim_farms (
    farm_sk UInt64,
    farm_id UInt64,
    farm_code String,
    farm_name String,
    
    -- Location
    address String,
    city String,
    county LowCardinality(String),
    region LowCardinality(String),
    country LowCardinality(String) DEFAULT 'Kenya',
    latitude Float64,
    longitude Float64,
    
    -- Farm characteristics
    farm_type LowCardinality(String),  -- Company Owned, Contracted, Cooperative
    total_acres Float64,
    grazing_acres Float64,
    barn_capacity UInt32,
    
    -- Herd information
    total_cows UInt32,
    milking_cows UInt32,
    dry_cows UInt32,
    heifers UInt32,
    calves UInt32,
    
    -- Production capacity
    daily_capacity_liters Float64,
    current_daily_production Float64,
    
    -- Quality certifications
    is_kdb_certified UInt8 DEFAULT 0,  -- Kenya Dairy Board
    is_organic_certified UInt8 DEFAULT 0,
    is_haccp_certified UInt8 DEFAULT 0,
    
    -- Management
    farm_manager_name String,
    farm_manager_contact String,
    
    -- SCD Type 2 columns
    valid_from DateTime DEFAULT '1970-01-01 00:00:00',
    valid_to DateTime DEFAULT '9999-12-31 23:59:59',
    is_current UInt8 DEFAULT 1,
    
    -- Metadata
    created_at DateTime DEFAULT now(),
    updated_at DateTime DEFAULT now(),
    source_system LowCardinality(String) DEFAULT 'farm_management'
)
ENGINE = ReplacingMergeTree(updated_at)
PARTITION BY toYYYYMM(created_at)
ORDER BY (farm_sk, valid_from)
SETTINGS index_granularity = 8192;

-- ============================================
-- ANIMAL DIMENSION
-- ============================================
CREATE TABLE IF NOT EXISTS dim_animals (
    animal_sk UInt64,
    animal_id UInt64,
    animal_tag String,
    
    -- Animal details
    breed LowCardinality(String),  -- Friesian, Ayrshire, Guernsey, Jersey, Cross
    gender LowCardinality(String),  -- Female, Male
    birth_date Date,
    
    -- Genetic information
    sire_id Nullable(UInt64),
    dam_id Nullable(UInt64),
    
    -- Current status
    current_status LowCardinality(String),  -- Lactating, Dry, Pregnant, Sold, Deceased
    current_farm_sk UInt64,
    
    -- Production metrics (current lactation)
    current_lactation_number UInt8,
    current_lactation_start_date Nullable(Date),
    
    -- SCD Type 2 columns
    valid_from DateTime DEFAULT '1970-01-01 00:00:00',
    valid_to DateTime DEFAULT '9999-12-31 23:59:59',
    is_current UInt8 DEFAULT 1,
    
    -- Metadata
    created_at DateTime DEFAULT now(),
    updated_at DateTime DEFAULT now(),
    source_system LowCardinality(String) DEFAULT 'farm_management'
)
ENGINE = ReplacingMergeTree(updated_at)
PARTITION BY toYYYYMM(created_at)
ORDER BY (animal_sk, valid_from)
SETTINGS index_granularity = 8192;

-- ============================================
-- EMPLOYEE/SALES REP DIMENSION
-- ============================================
CREATE TABLE IF NOT EXISTS dim_employees (
    employee_sk UInt64,
    employee_id UInt64,
    employee_code String,
    first_name String,
    last_name String,
    full_name String,
    
    -- Contact
    email String,
    phone String,
    
    -- Employment
    department LowCardinality(String),
    job_title LowCardinality(String),
    manager_sk Nullable(UInt64),
    
    -- Sales specific
    sales_region LowCardinality(String),
    sales_territory String,
    commission_rate Decimal(5, 4),
    
    -- Status
    employment_status LowCardinality(String) DEFAULT 'Active',
    hire_date Date,
    termination_date Nullable(Date),
    
    -- SCD Type 2 columns
    valid_from DateTime DEFAULT '1970-01-01 00:00:00',
    valid_to DateTime DEFAULT '9999-12-31 23:59:59',
    is_current UInt8 DEFAULT 1,
    
    -- Metadata
    created_at DateTime DEFAULT now(),
    updated_at DateTime DEFAULT now(),
    source_system LowCardinality(String) DEFAULT 'hr_system'
)
ENGINE = ReplacingMergeTree(updated_at)
PARTITION BY toYYYYMM(created_at)
ORDER BY (employee_sk, valid_from)
SETTINGS index_granularity = 8192;
```

**Afternoon Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D1-372-3 | Implement SCD Type 2 handling | 2h | SCD transformation logic |
| D1-372-4 | Set up table partitioning strategy | 1.5h | Partition configuration |
| D1-372-5 | Code review and team sync | 0.5h | Review notes |

---

#### Developer 2 (Backend Dev - Data Engineering)

**Morning Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D2-372-1 | Implement CDC pipeline with Debezium | 2h | Debezium connectors |
| D2-372-2 | Configure Kafka Connect for streaming | 2h | Kafka Connect config |

**Debezium CDC Configuration**

```json
{
  "name": "smart-dairy-erp-cdc-connector",
  "config": {
    "connector.class": "io.debezium.connector.postgresql.PostgresConnector",
    "database.hostname": "erp-db.smartdairy.internal",
    "database.port": "5432",
    "database.user": "debezium",
    "database.password": "${secrets:debezium:password}",
    "database.dbname": "erp_production",
    "database.server.name": "smart_dairy_erp",
    "plugin.name": "pgoutput",
    "slot.name": "debezium_erp_slot_v1",
    "publication.name": "dbz_publication",
    "snapshot.mode": "initial",
    "snapshot.fetch.size": 10000,
    "tombstones.on.delete": true,
    
    "table.include.list": [
      "public.customers",
      "public.products",
      "public.sales_orders",
      "public.sales_order_items",
      "public.invoices",
      "public.payments",
      "public.inventory_transactions",
      "public.purchase_orders"
    ],
    
    "column.exclude.list": [
      "public.customers.password_hash",
      "public.customers.security_question"
    ],
    
    "transforms": "route,unwrap",
    "transforms.route.type": "org.apache.kafka.connect.transforms.RegexRouter",
    "transforms.route.regex": "([^.]+)\\.([^.]+)\\.([^.]+)",
    "transforms.route.replacement": "cdc_$3",
    
    "transforms.unwrap.type": "io.debezium.transforms.ExtractNewRecordState",
    "transforms.unwrap.drop.tombstones": false,
    "transforms.unwrap.delete.handling.mode": "rewrite",
    "transforms.unwrap.add.fields": "op,ts_ms,source.ts_ms",
    
    "key.converter": "org.apache.kafka.connect.json.JsonConverter",
    "key.converter.schemas.enable": false,
    "value.converter": "org.apache.kafka.connect.json.JsonConverter",
    "value.converter.schemas.enable": false,
    
    "errors.tolerance": "all",
    "errors.log.enable": true,
    "errors.log.include.messages": true,
    "errors.deadletterqueue.topic.name": "cdc_errors",
    "errors.deadletterqueue.topic.replication.factor": 3,
    "errors.deadletterqueue.context.headers.enable": true,
    
    "heartbeat.interval.ms": 10000,
    "heartbeat.action.query": "INSERT INTO debezium_heartbeat (id, last_heartbeat) VALUES (1, NOW()) ON CONFLICT (id) DO UPDATE SET last_heartbeat = NOW();",
    
    "max.batch.size": 2048,
    "max.queue.size": 8192,
    "poll.interval.ms": 1000,
    
    "topic.creation.default.replication.factor": 3,
    "topic.creation.default.partitions": 6,
    "topic.creation.default.cleanup.policy": "delete",
    "topic.creation.default.retention.ms": 604800000
  }
}
```

**Afternoon Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D2-372-3 | Implement data transformation dbt models | 2h | dbt staging models |
| D2-372-4 | Write data quality tests | 1.5h | dbt tests |
| D2-372-5 | Documentation | 0.5h | Model documentation |

---

#### Developer 3 (Frontend Dev - Dashboards)

**Morning Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D3-372-1 | Create KPI card components | 2h | React KPI components |
| D3-372-2 | Implement filter components | 2h | Filter UI components |

**KPI Card Component**

```typescript
// frontend/src/components/kpi/KPICard.tsx
/**
 * KPI Card Component
 * Day 372: KPI visualization component
 */

import React from 'react';
import { ArrowUpIcon, ArrowDownIcon, MinusIcon } from '@heroicons/react/24/solid';
import { Tooltip } from '../common/Tooltip';

// Types
interface KPICardProps {
  title: string;
  value: number | string;
  format?: 'currency' | 'percentage' | 'number' | 'compact';
  previousValue?: number;
  target?: number;
  trend?: 'up' | 'down' | 'neutral';
  trendValue?: number;
  prefix?: string;
  suffix?: string;
  decimals?: number;
  description?: string;
  loading?: boolean;
  size?: 'sm' | 'md' | 'lg';
  color?: 'blue' | 'green' | 'red' | 'yellow' | 'purple' | 'gray';
  sparklineData?: number[];
  onClick?: () => void;
}

// Format number based on type
const formatValue = (
  value: number,
  format: KPICardProps['format'],
  decimals: number,
  prefix?: string,
  suffix?: string
): string => {
  let formatted = '';
  
  switch (format) {
    case 'currency':
      formatted = new Intl.NumberFormat('en-KE', {
        style: 'currency',
        currency: 'KES',
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
      }).format(value);
      break;
      
    case 'percentage':
      formatted = `${value.toFixed(decimals)}%`;
      break;
      
    case 'compact':
      formatted = new Intl.NumberFormat('en-KE', {
        notation: 'compact',
        compactDisplay: 'short',
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
      }).format(value);
      break;
      
    case 'number':
    default:
      formatted = new Intl.NumberFormat('en-KE', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
      }).format(value);
  }
  
  if (prefix) formatted = `${prefix}${formatted}`;
  if (suffix) formatted = `${formatted}${suffix}`;
  
  return formatted;
};

// Calculate trend
const calculateTrend = (current: number, previous: number): { direction: 'up' | 'down' | 'neutral'; percentage: number } => {
  if (previous === 0) return { direction: 'neutral', percentage: 0 };
  
  const change = ((current - previous) / Math.abs(previous)) * 100;
  
  return {
    direction: change > 0 ? 'up' : change < 0 ? 'down' : 'neutral',
    percentage: Math.abs(change)
  };
};

// Color configurations
const colorConfig = {
  blue: { bg: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-900', subtext: 'text-blue-600' },
  green: { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-900', subtext: 'text-green-600' },
  red: { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-900', subtext: 'text-red-600' },
  yellow: { bg: 'bg-yellow-50', border: 'border-yellow-200', text: 'text-yellow-900', subtext: 'text-yellow-600' },
  purple: { bg: 'bg-purple-50', border: 'border-purple-200', text: 'text-purple-900', subtext: 'text-purple-600' },
  gray: { bg: 'bg-gray-50', border: 'border-gray-200', text: 'text-gray-900', subtext: 'text-gray-600' }
};

// Size configurations
const sizeConfig = {
  sm: { card: 'p-4', title: 'text-sm', value: 'text-2xl', icon: 'w-4 h-4' },
  md: { card: 'p-6', title: 'text-sm', value: 'text-3xl', icon: 'w-5 h-5' },
  lg: { card: 'p-8', title: 'text-base', value: 'text-4xl', icon: 'w-6 h-6' }
};

export const KPICard: React.FC<KPICardProps> = ({
  title,
  value,
  format = 'number',
  previousValue,
  target,
  trend: propTrend,
  trendValue,
  prefix,
  suffix,
  decimals = 0,
  description,
  loading = false,
  size = 'md',
  color = 'blue',
  sparklineData,
  onClick
}) => {
  // Calculate trend if not provided
  const numericValue = typeof value === 'string' ? parseFloat(value) : value;
  const calculatedTrend = previousValue !== undefined 
    ? calculateTrend(numericValue, previousValue)
    : null;
  
  const trend = propTrend || calculatedTrend?.direction || 'neutral';
  const trendPercentage = trendValue || calculatedTrend?.percentage || 0;
  
  // Colors and sizes
  const colors = colorConfig[color];
  const sizes = sizeConfig[size];
  
  // Progress towards target
  const targetProgress = target ? Math.min((numericValue / target) * 100, 100) : null;
  
  // Loading state
  if (loading) {
    return (
      <div className={`${sizes.card} ${colors.bg} border ${colors.border} rounded-lg animate-pulse`}>
        <div className="h-4 w-24 bg-gray-200 rounded mb-2"></div>
        <div className="h-8 w-32 bg-gray-200 rounded"></div>
      </div>
    );
  }
  
  return (
    <div 
      className={`
        ${sizes.card} 
        ${colors.bg} 
        border ${colors.border} 
        rounded-lg 
        transition-all duration-200
        ${onClick ? 'cursor-pointer hover:shadow-md hover:scale-[1.02]' : ''}
      `}
      onClick={onClick}
    >
      {/* Title */}
      <div className="flex items-center justify-between mb-2">
        <h4 className={`${sizes.title} font-medium ${colors.subtext}`}>
          {title}
        </h4>
        {description && (
          <Tooltip content={description}>
            <span className="text-gray-400 cursor-help">ⓘ</span>
          </Tooltip>
        )}
      </div>
      
      {/* Main Value */}
      <div className={`${sizes.value} font-bold ${colors.text} mb-2`}>
        {formatValue(numericValue, format, decimals, prefix, suffix)}
      </div>
      
      {/* Trend */}
      {(previousValue !== undefined || propTrend) && (
        <div className="flex items-center gap-2 mb-2">
          <span className={`
            inline-flex items-center gap-1 text-sm font-medium
            ${trend === 'up' ? 'text-green-600' : trend === 'down' ? 'text-red-600' : 'text-gray-500'}
          `}>
            {trend === 'up' && <ArrowUpIcon className={sizes.icon} />}
            {trend === 'down' && <ArrowDownIcon className={sizes.icon} />}
            {trend === 'neutral' && <MinusIcon className={sizes.icon} />}
            {trendPercentage.toFixed(1)}%
          </span>
          <span className="text-sm text-gray-500">
            vs previous period
          </span>
        </div>
      )}
      
      {/* Target Progress */}
      {target !== undefined && targetProgress !== null && (
        <div className="mt-3">
          <div className="flex justify-between text-sm mb-1">
            <span className="text-gray-500">Target</span>
            <span className={colors.subtext}>{targetProgress.toFixed(0)}%</span>
          </div>
          <div className="w-full bg-gray-200 rounded-full h-2">
            <div 
              className={`h-2 rounded-full transition-all duration-500 ${
                targetProgress >= 100 ? 'bg-green-500' : 
                targetProgress >= 75 ? 'bg-blue-500' : 
                targetProgress >= 50 ? 'bg-yellow-500' : 'bg-red-500'
              }`}
              style={{ width: `${targetProgress}%` }}
            ></div>
          </div>
          <div className="text-xs text-gray-400 mt-1">
            Target: {formatValue(target, format, decimals, prefix, suffix)}
          </div>
        </div>
      )}
      
      {/* Sparkline */}
      {sparklineData && sparklineData.length > 0 && (
        <div className="mt-3 h-10">
          <Sparkline data={sparklineData} color={colors.subtext} />
        </div>
      )}
    </div>
  );
};

// Sparkline component
const Sparkline: React.FC<{ data: number[]; color: string }> = ({ data, color }) => {
  const min = Math.min(...data);
  const max = Math.max(...data);
  const range = max - min || 1;
  
  const points = data.map((value, index) => {
    const x = (index / (data.length - 1)) * 100;
    const y = 100 - ((value - min) / range) * 100;
    return `${x},${y}`;
  }).join(' ');
  
  return (
    <svg viewBox="0 0 100 100" preserveAspectRatio="none" className="w-full h-full">
      <polyline
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        points={points}
        className={color}
      />
    </svg>
  );
};

export default KPICard;
```

**Afternoon Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D3-372-3 | Create dashboard layout grid system | 2h | Grid layout component |
| D3-372-4 | Implement dashboard state management | 1.5h | State management hooks |
| D3-372-5 | Testing and bug fixes | 0.5h | Test results |

---

### Day 373: Fact Tables and Aggregations

#### Developer 1 (Lead Dev - Data Architecture)

**Morning Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D1-373-1 | Create sales fact table | 2h | SQL DDL |
| D1-373-2 | Create production fact table | 2h | SQL DDL |

**Sales Fact Table**

```sql
-- scripts/clickhouse/03_fact_tables.sql
-- Day 373: Fact table creation

USE smart_dairy_dwd;

-- ============================================
-- SALES FACT TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS fct_sales (
    -- Surrogate key
    sales_sk UInt64,
    
    -- Foreign keys
    date_sk UInt32,
    customer_sk UInt64,
    product_sk UInt64,
    sales_rep_sk UInt64,
    farm_sk UInt64,
    
    -- Degenerate dimensions
    order_id UInt64,
    order_number String,
    order_line_id UInt64,
    invoice_id Nullable(UInt64),
    invoice_number Nullable(String),
    
    -- Order attributes
    order_type LowCardinality(String),  -- Standard, Rush, Contract, Subscription
    sales_channel LowCardinality(String),  -- Direct, Distributor, Online, Retail
    order_status LowCardinality(String),  -- Pending, Confirmed, Shipped, Delivered, Cancelled
    
    -- Quantities
    quantity_ordered Decimal(18, 4),
    quantity_shipped Decimal(18, 4),
    quantity_delivered Decimal(18, 4),
    quantity_returned Decimal(18, 4) DEFAULT 0,
    
    -- Pricing
    unit_list_price Decimal(18, 4),
    unit_selling_price Decimal(18, 4),
    unit_cost Decimal(18, 4),
    
    -- Monetary amounts
    gross_amount Decimal(18, 4),
    discount_amount Decimal(18, 4),
    net_amount Decimal(18, 4),
    tax_amount Decimal(18, 4),
    shipping_amount Decimal(18, 4),
    total_amount Decimal(18, 4),
    
    -- Cost and profit
    total_cost Decimal(18, 4),
    gross_profit Decimal(18, 4),
    gross_margin_pct Decimal(5, 4),
    
    -- Dates
    order_date Date,
    order_datetime DateTime,
    promised_delivery_date Nullable(Date),
    actual_ship_date Nullable(Date),
    actual_delivery_date Nullable(Date),
    invoice_date Nullable(Date),
    payment_date Nullable(Date),
    
    -- Delivery metrics
    days_to_ship Nullable(Int32),
    days_to_deliver Nullable(Int32),
    delivery_on_time_flag UInt8,
    
    -- Payment metrics
    payment_terms_days UInt16,
    days_to_payment Nullable(Int32),
    payment_on_time_flag Nullable(UInt8),
    
    -- Metadata
    created_at DateTime DEFAULT now(),
    updated_at DateTime DEFAULT now(),
    _etl_batch_id String,
    _source_system LowCardinality(String) DEFAULT 'erp'
)
ENGINE = MergeTree()
PARTITION BY toYYYYMM(order_date)
ORDER BY (order_date, customer_sk, product_sk)
TTL order_date + INTERVAL 5 YEAR TO VOLUME 'cold_storage'
SETTINGS index_granularity = 8192;

-- Aggregating merge tree for daily sales aggregates
CREATE TABLE IF NOT EXISTS agg_daily_sales (
    date_sk UInt32,
    order_date Date,
    
    -- Dimensions
    customer_sk UInt64,
    product_sk UInt64,
    sales_rep_sk UInt64,
    sales_region LowCardinality(String),
    sales_channel LowCardinality(String),
    
    -- Aggregated measures
    total_orders AggregateFunction(count, UInt64),
    total_order_lines AggregateFunction(count, UInt64),
    total_quantity AggregateFunction(sum, Decimal(18, 4)),
    total_gross_amount AggregateFunction(sum, Decimal(18, 4)),
    total_discount_amount AggregateFunction(sum, Decimal(18, 4)),
    total_net_amount AggregateFunction(sum, Decimal(18, 4)),
    total_tax_amount AggregateFunction(sum, Decimal(18, 4)),
    total_shipping_amount AggregateFunction(sum, Decimal(18, 4)),
    total_revenue AggregateFunction(sum, Decimal(18, 4)),
    total_cost AggregateFunction(sum, Decimal(18, 4)),
    total_profit AggregateFunction(sum, Decimal(18, 4)),
    
    -- Average measures
    avg_order_value AggregateFunction(avg, Decimal(18, 4)),
    avg_unit_price AggregateFunction(avg, Decimal(18, 4)),
    avg_margin_pct AggregateFunction(avg, Decimal(5, 4)),
    
    -- Count distinct
    unique_customers AggregateFunction(uniq, UInt64),
    unique_products AggregateFunction(uniq, UInt64),
    
    -- Quantiles
    revenue_p50 AggregateFunction(quantiles(0.5), Decimal(18, 4)),
    revenue_p90 AggregateFunction(quantiles(0.9), Decimal(18, 4)),
    revenue_p99 AggregateFunction(quantiles(0.99), Decimal(18, 4))
)
ENGINE = AggregatingMergeTree()
PARTITION BY toYYYYMM(order_date)
ORDER BY (order_date, customer_sk, product_sk, sales_region, sales_channel);

-- Materialized view for automatic aggregation
CREATE MATERIALIZED VIEW IF NOT EXISTS mv_daily_sales
TO agg_daily_sales
AS
SELECT
    date_sk,
    order_date,
    customer_sk,
    product_sk,
    sales_rep_sk,
    multiIf(
        customer_sk % 5 = 0, 'North',
        customer_sk % 5 = 1, 'South',
        customer_sk % 5 = 2, 'East',
        customer_sk % 5 = 3, 'West',
        'Central'
    ) as sales_region,
    sales_channel,
    
    countState(order_id) as total_orders,
    countState(order_line_id) as total_order_lines,
    sumState(quantity_ordered) as total_quantity,
    sumState(gross_amount) as total_gross_amount,
    sumState(discount_amount) as total_discount_amount,
    sumState(net_amount) as total_net_amount,
    sumState(tax_amount) as total_tax_amount,
    sumState(shipping_amount) as total_shipping_amount,
    sumState(total_amount) as total_revenue,
    sumState(total_cost) as total_cost,
    sumState(gross_profit) as total_profit,
    
    avgState(total_amount) as avg_order_value,
    avgState(unit_selling_price) as avg_unit_price,
    avgState(gross_margin_pct) as avg_margin_pct,
    
    uniqState(customer_sk) as unique_customers,
    uniqState(product_sk) as unique_products,
    
    quantilesState(0.5, 0.9, 0.99)(total_amount) as revenue_p50
FROM fct_sales
GROUP BY date_sk, order_date, customer_sk, product_sk, sales_rep_sk, sales_channel;

-- ============================================
-- PRODUCTION FACT TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS fct_production (
    -- Surrogate key
    production_sk UInt64,
    
    -- Foreign keys
    date_sk UInt32,
    production_date Date,
    farm_sk UInt64,
    animal_sk Nullable(UInt64),
    
    -- Production session
    session_id UInt64,
    session_type LowCardinality(String),  -- Morning, Evening, Night
    
    -- Milk production
    milk_volume_liters Decimal(18, 4),
    milk_weight_kg Decimal(18, 4),
    fat_percentage Decimal(5, 2),
    protein_percentage Decimal(5, 2),
    lactose_percentage Decimal(5, 2),
    solids_non_fat_pct Decimal(5, 2),
    somatic_cell_count UInt32,  -- Cells per mL
    bacterial_count UInt32,  -- CFU per mL
    temperature_celsius Decimal(4, 2),
    ph_level Decimal(4, 2),
    
    -- Quality grade
    quality_grade LowCardinality(String),  -- Premium, Grade A, Grade B, Reject
    
    -- Animal metrics
    animal_count_milked UInt32,
    avg_yield_per_animal Decimal(10, 4),
    
    -- Time metrics
    milking_start_time DateTime,
    milking_end_time DateTime,
    milking_duration_minutes UInt16,
    
    -- Equipment
    equipment_id String,
    parlor_unit LowCardinality(String),
    
    -- Metadata
    created_at DateTime DEFAULT now(),
    _etl_batch_id String,
    _source_system LowCardinality(String) DEFAULT 'farm_mgmt'
)
ENGINE = MergeTree()
PARTITION BY toYYYYMM(production_date)
ORDER BY (production_date, farm_sk, session_type)
TTL production_date + INTERVAL 3 YEAR;

-- ============================================
-- INVENTORY FACT TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS fct_inventory (
    -- Surrogate key
    inventory_sk UInt64,
    
    -- Foreign keys
    date_sk UInt32,
    transaction_date Date,
    product_sk UInt64,
    farm_sk Nullable(UInt64),
    warehouse_sk Nullable(UInt64),
    
    -- Transaction
    transaction_id UInt64,
    transaction_type LowCardinality(String),  -- Receipt, Issue, Transfer, Adjustment, Conversion
    document_number String,
    
    -- Quantities
    quantity Decimal(18, 4),
    unit_of_measure LowCardinality(String),
    quantity_in_base_uom Decimal(18, 4),
    
    -- Inventory status
    quantity_on_hand Decimal(18, 4),
    quantity_reserved Decimal(18, 4),
    quantity_available Decimal(18, 4),
    
    -- Values
    unit_cost Decimal(18, 4),
    total_cost Decimal(18, 4),
    moving_avg_cost Decimal(18, 4),
    
    -- Lot/Serial
    lot_number Nullable(String),
    serial_number Nullable(String),
    expiry_date Nullable(Date),
    
    -- Location
    storage_location String,
    bin_location String,
    
    -- References
    source_document_type Nullable(String),
    source_document_id Nullable(UInt64),
    
    -- Metadata
    created_at DateTime DEFAULT now(),
    _etl_batch_id String,
    _source_system LowCardinality(String) DEFAULT 'erp'
)
ENGINE = MergeTree()
PARTITION BY toYYYYMM(transaction_date)
ORDER BY (transaction_date, product_sk, transaction_type)
TTL transaction_date + INTERVAL 7 YEAR;

-- ============================================
-- FINANCIAL FACT TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS fct_gl_transactions (
    -- Surrogate key
    gl_sk UInt64,
    
    -- Foreign keys
    date_sk UInt32,
    posting_date Date,
    fiscal_period_sk UInt32,
    account_sk UInt64,
    cost_center_sk UInt64,
    project_sk Nullable(UInt64),
    
    -- Document
    document_id UInt64,
    document_number String,
    document_type LowCardinality(String),  -- Journal, Invoice, Payment, Adjustment
    
    -- Amounts
    debit_amount Decimal(18, 4),
    credit_amount Decimal(18, 4),
    net_amount Decimal(18, 4),
    
    -- Amount in reporting currency
    debit_amount_reporting Decimal(18, 4),
    credit_amount_reporting Decimal(18, 4),
    net_amount_reporting Decimal(18, 4),
    exchange_rate Decimal(18, 8),
    
    -- Account information
    account_code String,
    account_name String,
    account_type LowCardinality(String),  -- Asset, Liability, Equity, Revenue, Expense
    account_category LowCardinality(String),
    
    -- References
    source_system LowCardinality(String),
    source_document_id String,
    
    -- Narrative
    description String,
    reference_number String,
    
    -- Approval
    entered_by String,
    approved_by Nullable(String),
    
    -- Metadata
    created_at DateTime DEFAULT now(),
    _etl_batch_id String
)
ENGINE = MergeTree()
PARTITION BY toYYYYMM(posting_date)
ORDER BY (posting_date, account_sk, document_type)
TTL posting_date + INTERVAL 10 YEAR;
```

**Afternoon Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D1-373-3 | Create materialized views for aggregations | 2h | MV definitions |
| D1-373-4 | Configure TTL and archival policies | 1.5h | Retention config |
| D1-373-5 | Team sync and review | 0.5h | Status update |

---

#### Developer 2 (Backend Dev - Data Engineering)

**Morning Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D2-373-1 | Implement fact table load procedures | 2h | SQL procedures |
| D2-373-2 | Create incremental load logic | 2h | ETL logic |

**Incremental Load Logic**

```python
# etl/loaders/fact_table_loader.py
"""
Fact table incremental loader with change data capture.
Day 373: Incremental loading implementation.
"""

import logging
from datetime import datetime, timedelta
from typing import List, Dict, Optional
import pandas as pd
from clickhouse_driver import Client as ClickHouseClient
from sqlalchemy import create_engine, text
from dataclasses import dataclass

logger = logging.getLogger(__name__)

@dataclass
class LoadConfig:
    """Configuration for fact table loading."""
    source_table: str
    target_table: str
    source_db: str
    target_db: str = 'smart_dairy_dwd'
    batch_size: int = 10000
    watermark_column: str = 'updated_at'
    key_columns: List[str] = None
    
class FactTableLoader:
    """
    Handles incremental loading of fact tables with CDC support.
    """
    
    def __init__(self, clickhouse_client: ClickHouseClient, pg_connection_string: str):
        self.ch_client = clickhouse_client
        self.pg_engine = create_engine(pg_connection_string)
        
    def get_watermark(self, target_table: str) -> Optional[datetime]:
        """
        Get the last successful load timestamp.
        """
        query = f"""
        SELECT MAX({target_table}_watermark) as watermark
        FROM smart_dairy_ads.etl_watermarks
        WHERE target_table = '{target_table}'
        """
        
        result = self.ch_client.execute(query)
        if result and result[0][0]:
            return result[0][0]
        return None
    
    def update_watermark(self, target_table: str, watermark: datetime, 
                        records_processed: int, status: str = 'success'):
        """
        Update the watermark after successful load.
        """
        query = f"""
        INSERT INTO smart_dairy_ads.etl_watermarks
        (target_table, watermark_value, records_processed, status, created_at)
        VALUES
        ('{target_table}', '{watermark}', {records_processed}, '{status}', now())
        """
        
        self.ch_client.execute(query)
        logger.info(f"Updated watermark for {target_table}: {watermark}")
    
    def extract_incremental(self, config: LoadConfig, 
                           watermark: Optional[datetime] = None) -> pd.DataFrame:
        """
        Extract incremental changes from source.
        """
        watermark_filter = ""
        if watermark:
            watermark_filter = f"WHERE {config.watermark_column} > '{watermark}'"
        
        query = f"""
        SELECT *
        FROM {config.source_table}
        {watermark_filter}
        ORDER BY {config.watermark_column} ASC
        """
        
        logger.info(f"Extracting from {config.source_table} with watermark: {watermark}")
        
        chunks = []
        with self.pg_engine.connect() as conn:
            for chunk in pd.read_sql(query, conn, chunksize=config.batch_size):
                chunks.append(chunk)
                logger.debug(f"Fetched chunk of {len(chunk)} records")
        
        if chunks:
            return pd.concat(chunks, ignore_index=True)
        return pd.DataFrame()
    
    def transform_sales_fact(self, df: pd.DataFrame, 
                            dimension_mappings: Dict[str, Dict]) -> pd.DataFrame:
        """
        Transform source data to sales fact format.
        """
        # Lookup surrogate keys
        df['customer_sk'] = df['customer_id'].map(dimension_mappings.get('customer', {}))
        df['product_sk'] = df['product_id'].map(dimension_mappings.get('product', {}))
        df['sales_rep_sk'] = df['sales_rep_id'].map(dimension_mappings.get('employee', {}))
        df['date_sk'] = pd.to_datetime(df['order_date']).dt.strftime('%Y%m%d').astype(int)
        
        # Calculate derived measures
        df['gross_amount'] = df['quantity'] * df['unit_price']
        df['net_amount'] = df['gross_amount'] - df['discount_amount']
        df['total_amount'] = df['net_amount'] + df['tax_amount'] + df['shipping_amount']
        df['gross_profit'] = df['net_amount'] - (df['quantity'] * df['unit_cost'])
        df['gross_margin_pct'] = df['gross_profit'] / df['net_amount']
        
        # Calculate delivery metrics
        df['days_to_ship'] = (pd.to_datetime(df['actual_ship_date']) - 
                             pd.to_datetime(df['order_date'])).dt.days
        df['days_to_deliver'] = (pd.to_datetime(df['actual_delivery_date']) - 
                                pd.to_datetime(df['order_date'])).dt.days
        df['delivery_on_time_flag'] = (
            pd.to_datetime(df['actual_delivery_date']) <= 
            pd.to_datetime(df['promised_delivery_date'])
        ).astype(int)
        
        return df
    
    def load_to_clickhouse(self, df: pd.DataFrame, target_table: str, 
                          database: str = 'smart_dairy_dwd'):
        """
        Load DataFrame to ClickHouse.
        """
        if df.empty:
            logger.warning(f"No data to load to {target_table}")
            return 0
        
        # Convert NaN to None for ClickHouse
        df = df.where(pd.notnull(df), None)
        
        # Prepare columns
        columns = ','.join(df.columns)
        
        # Insert in batches
        total_inserted = 0
        batch_size = 10000
        
        for i in range(0, len(df), batch_size):
            batch = df.iloc[i:i+batch_size]
            
            # Convert to values
            values = []
            for _, row in batch.iterrows():
                row_values = []
                for val in row:
                    if val is None:
                        row_values.append('NULL')
                    elif isinstance(val, str):
                        row_values.append(f"'{val.replace(\"'\", \"''\")}'")
                    elif isinstance(val, (int, float)):
                        row_values.append(str(val))
                    elif isinstance(val, datetime):
                        row_values.append(f"'{val.strftime('%Y-%m-%d %H:%M:%S')}'")
                    else:
                        row_values.append(f"'{str(val)}'")
                values.append(f"({','.join(row_values)})")
            
            query = f"""
            INSERT INTO {database}.{target_table} ({columns})
            VALUES {','.join(values)}
            """
            
            self.ch_client.execute(query)
            total_inserted += len(batch)
            logger.info(f"Inserted {total_inserted}/{len(df)} records")
        
        return total_inserted
    
    def load_fact_table(self, config: LoadConfig, 
                       dimension_mappings: Dict[str, Dict]) -> Dict:
        """
        Main entry point for fact table loading.
        """
        start_time = datetime.now()
        
        try:
            # Get watermark
            watermark = self.get_watermark(config.target_table)
            
            # Extract
            df = self.extract_incremental(config, watermark)
            
            if df.empty:
                logger.info(f"No new data for {config.target_table}")
                return {
                    'status': 'success',
                    'records_processed': 0,
                    'duration_seconds': (datetime.now() - start_time).total_seconds()
                }
            
            # Transform
            if config.target_table == 'fct_sales':
                df = self.transform_sales_fact(df, dimension_mappings)
            
            # Generate surrogate keys
            df['sales_sk'] = range(
                self.get_max_sk(config.target_table) + 1,
                self.get_max_sk(config.target_table) + 1 + len(df)
            )
            
            # Load
            records_inserted = self.load_to_clickhouse(df, config.target_table, config.target_db)
            
            # Update watermark
            new_watermark = df[config.watermark_column].max()
            self.update_watermark(config.target_table, new_watermark, records_inserted)
            
            duration = (datetime.now() - start_time).total_seconds()
            
            logger.info(f"Successfully loaded {records_inserted} records to {config.target_table} in {duration:.2f}s")
            
            return {
                'status': 'success',
                'records_processed': records_inserted,
                'duration_seconds': duration,
                'watermark': new_watermark
            }
            
        except Exception as e:
            logger.error(f"Failed to load {config.target_table}: {str(e)}")
            self.update_watermark(config.target_table, watermark or datetime.min, 0, 'failed')
            raise
    
    def get_max_sk(self, table_name: str) -> int:
        """
        Get the maximum surrogate key from target table.
        """
        # Extract primary key column name
        sk_column = f"{table_name.replace('fct_', '').replace('dim_', '')}_sk"
        
        query = f"""
        SELECT MAX({sk_column}) 
        FROM smart_dairy_dwd.{table_name}
        """
        
        result = self.ch_client.execute(query)
        return result[0][0] if result and result[0][0] else 0
```

**Afternoon Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D2-373-3 | Implement slowly changing dimensions handling | 2h | SCD logic |
| D2-373-4 | Create data reconciliation checks | 1.5h | Reconciliation reports |
| D2-373-5 | Documentation | 0.5h | Documentation |

---

#### Developer 3 (Frontend Dev - Dashboards)

**Morning Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D3-373-1 | Create executive dashboard layout | 2h | Dashboard component |
| D3-373-2 | Implement chart data fetching hooks | 2h | Custom hooks |

**Executive Dashboard Component**

```typescript
// frontend/src/pages/dashboards/ExecutiveDashboard.tsx
/**
 * Executive Dashboard
 * Day 373: Executive KPI dashboard implementation
 */

import React, { useState, useCallback } from 'react';
import { useQuery } from '@tanstack/react-query';
import { format, subDays, startOfMonth, endOfMonth } from 'date-fns';
import { KPICard } from '../../components/kpi/KPICard';
import { SalesTrendChart } from '../../components/charts/SalesTrendChart';
import { RevenueByRegionChart } from '../../components/charts/RevenueByRegionChart';
import { ProductionOverviewChart } from '../../components/charts/ProductionOverviewChart';
import { DateRangePicker } from '../../components/common/DateRangePicker';
import { DashboardFilter } from '../../components/filters/DashboardFilter';
import { AlertBanner } from '../../components/common/AlertBanner';
import { analyticsApi } from '../../services/analyticsApi';

// Types
interface ExecutiveKPIs {
  totalRevenue: number;
  revenuePreviousPeriod: number;
  totalOrders: number;
  ordersPreviousPeriod: number;
  totalProduction: number;
  productionPreviousPeriod: number;
  grossMargin: number;
  grossMarginPreviousPeriod: number;
  activeCustomers: number;
  customersPreviousPeriod: number;
}

interface DateRange {
  startDate: Date;
  endDate: Date;
}

const ExecutiveDashboard: React.FC = () => {
  // State
  const [dateRange, setDateRange] = useState<DateRange>({
    startDate: startOfMonth(new Date()),
    endDate: endOfMonth(new Date())
  });
  
  const [filters, setFilters] = useState({
    region: 'all',
    productCategory: 'all',
    farm: 'all'
  });

  // Fetch executive KPIs
  const { data: kpis, isLoading: kpisLoading, error: kpisError } = useQuery({
    queryKey: ['executiveKPIs', dateRange, filters],
    queryFn: () => analyticsApi.getExecutiveKPIs({
      startDate: dateRange.startDate.toISOString(),
      endDate: dateRange.endDate.toISOString(),
      ...filters
    }),
    refetchInterval: 5 * 60 * 1000, // 5 minutes
    staleTime: 2 * 60 * 1000 // 2 minutes
  });

  // Fetch alerts
  const { data: alerts } = useQuery({
    queryKey: ['alerts', dateRange],
    queryFn: () => analyticsApi.getAlerts({
      severity: ['high', 'critical'],
      startDate: dateRange.startDate.toISOString()
    }),
    refetchInterval: 60 * 1000 // 1 minute
  });

  // Filter handlers
  const handleFilterChange = useCallback((key: string, value: string) => {
    setFilters(prev => ({ ...prev, [key]: value }));
  }, []);

  // Date range presets
  const datePresets = [
    { label: 'Today', days: 0 },
    { label: 'Last 7 Days', days: 7 },
    { label: 'Last 30 Days', days: 30 },
    { label: 'This Month', value: 'current_month' },
    { label: 'Last Month', value: 'last_month' },
    { label: 'This Quarter', value: 'current_quarter' },
    { label: 'YTD', value: 'ytd' }
  ];

  return (
    <div className="min-h-screen bg-gray-50 p-6">
      {/* Header */}
      <div className="mb-8">
        <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Executive Dashboard</h1>
            <p className="text-gray-500 mt-1">
              Real-time business performance overview
            </p>
          </div>
          
          <div className="flex items-center gap-3">
            <DateRangePicker
              startDate={dateRange.startDate}
              endDate={dateRange.endDate}
              onChange={setDateRange}
              presets={datePresets}
            />
            <button 
              className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
              onClick={() => window.print()}
            >
              Export Report
            </button>
          </div>
        </div>
      </div>

      {/* Alerts */}
      {alerts && alerts.length > 0 && (
        <div className="mb-6 space-y-2">
          {alerts.map((alert: any) => (
            <AlertBanner
              key={alert.id}
              severity={alert.severity}
              title={alert.title}
              message={alert.message}
              onDismiss={() => {}}
            />
          ))}
        </div>
      )}

      {/* Filters */}
      <div className="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div className="flex flex-wrap gap-4">
          <DashboardFilter
            label="Region"
            value={filters.region}
            options={[
              { value: 'all', label: 'All Regions' },
              { value: 'north', label: 'North' },
              { value: 'south', label: 'South' },
              { value: 'east', label: 'East' },
              { value: 'west', label: 'West' },
              { value: 'central', label: 'Central' }
            ]}
            onChange={(value) => handleFilterChange('region', value)}
          />
          
          <DashboardFilter
            label="Product Category"
            value={filters.productCategory}
            options={[
              { value: 'all', label: 'All Categories' },
              { value: 'raw_milk', label: 'Raw Milk' },
              { value: 'pasteurized', label: 'Pasteurized' },
              { value: 'uht', label: 'UHT' },
              { value: 'cheese', label: 'Cheese' },
              { value: 'butter', label: 'Butter' },
              { value: 'yogurt', label: 'Yogurt' }
            ]}
            onChange={(value) => handleFilterChange('productCategory', value)}
          />
          
          <DashboardFilter
            label="Farm"
            value={filters.farm}
            options={[
              { value: 'all', label: 'All Farms' },
              { value: 'farm_001', label: 'Nairobi Central' },
              { value: 'farm_002', label: 'Kiambu Highlands' },
              { value: 'farm_003', label: 'Nakuru Valley' }
            ]}
            onChange={(value) => handleFilterChange('farm', value)}
          />
        </div>
      </div>

      {/* KPI Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <KPICard
          title="Total Revenue"
          value={kpis?.totalRevenue || 0}
          previousValue={kpis?.revenuePreviousPeriod}
          format="currency"
          loading={kpisLoading}
          color="blue"
          size="md"
        />
        
        <KPICard
          title="Total Orders"
          value={kpis?.totalOrders || 0}
          previousValue={kpis?.ordersPreviousPeriod}
          format="number"
          loading={kpisLoading}
          color="green"
          size="md"
        />
        
        <KPICard
          title="Production Volume"
          value={kpis?.totalProduction || 0}
          previousValue={kpis?.productionPreviousPeriod}
          format="compact"
          suffix=" L"
          loading={kpisLoading}
          color="purple"
          size="md"
        />
        
        <KPICard
          title="Gross Margin"
          value={kpis?.grossMargin || 0}
          previousValue={kpis?.grossMarginPreviousPeriod}
          format="percentage"
          decimals={2}
          loading={kpisLoading}
          color="yellow"
          size="md"
        />
        
        <KPICard
          title="Active Customers"
          value={kpis?.activeCustomers || 0}
          previousValue={kpis?.customersPreviousPeriod}
          format="number"
          loading={kpisLoading}
          color="gray"
          size="md"
        />
      </div>

      {/* Charts Row 1 */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <SalesTrendChart
          startDate={dateRange.startDate}
          endDate={dateRange.endDate}
          region={filters.region !== 'all' ? filters.region : undefined}
          productCategory={filters.productCategory !== 'all' ? filters.productCategory : undefined}
        />
        
        <RevenueByRegionChart
          startDate={dateRange.startDate}
          endDate={dateRange.endDate}
          productCategory={filters.productCategory !== 'all' ? filters.productCategory : undefined}
        />
      </div>

      {/* Charts Row 2 */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <ProductionOverviewChart
          farmId={filters.farm !== 'all' ? filters.farm : undefined}
          className="lg:col-span-2"
        />
        
        <div className="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">Top Products</h3>
          {/* Top products table or list */}
          <div className="space-y-3">
            {[1, 2, 3, 4, 5].map((i) => (
              <div key={i} className="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <div className="flex items-center gap-3">
                  <span className="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-medium flex items-center justify-center">
                    {i}
                  </span>
                  <span className="text-sm text-gray-700">Product {i}</span>
                </div>
                <span className="text-sm font-medium text-gray-900">
                  ${(100000 - i * 15000).toLocaleString()}
                </span>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Footer */}
      <div className="mt-8 text-center text-sm text-gray-400">
        Last updated: {format(new Date(), 'MMM dd, yyyy HH:mm')}
      </div>
    </div>
  );
};

export default ExecutiveDashboard;
```

**Afternoon Tasks (4 hours)**

| Task ID | Task Description | Duration | Deliverable |
|---------|-----------------|----------|-------------|
| D3-373-3 | Create sales analytics dashboard | 2h | Sales dashboard |
| D3-373-4 | Implement responsive layout | 1.5h | Responsive design |
| D3-373-5 | Testing | 0.5h | Unit tests |

---

### Day 374-375: Dashboard Development and ML Pipeline Setup

Given the extensive content already written, I'll continue with the remaining days in a condensed format:



#### Day 374 Tasks

**Developer 1: ML Pipeline Architecture and Feature Store**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D1-374-1 | Set up Feast feature store | 3h | Feature store config |
| D1-374-2 | Define feature definitions | 3h | Feature definitions |
| D1-374-3 | Configure MLflow tracking | 2h | MLflow setup |
| D1-374-4 | Create model training pipeline | 2h | Training pipeline |

**Developer 2: ETL Pipeline Optimization**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D2-374-1 | Optimize ClickHouse queries | 3h | Query optimization |
| D2-374-2 | Implement data partitioning | 2h | Partitioning strategy |
| D2-374-3 | Set up monitoring and alerting | 2h | Monitoring setup |
| D2-374-4 | Create data quality dashboards | 1h | Quality reports |

**Developer 3: Farm Operations Dashboard**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D3-374-1 | Create farm production charts | 3h | Production charts |
| D3-374-2 | Implement animal performance metrics | 3h | Animal metrics |
| D3-374-3 | Create feed efficiency dashboard | 2h | Feed dashboard |

---

#### Day 375 Tasks

**Developer 1: Predictive Models Development**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D1-375-1 | Implement demand forecasting model | 3h | Forecasting model |
| D1-375-2 | Create customer segmentation model | 3h | Segmentation model |
| D1-375-3 | Build churn prediction model | 2h | Churn model |

**Developer 2: Data Pipeline Hardening**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D2-375-1 | Implement error handling | 2h | Error handling |
| D2-375-2 | Create retry mechanisms | 2h | Retry logic |
| D2-375-3 | Set up dead letter queues | 2h | DLQ implementation |
| D2-375-4 | Document pipeline operations | 2h | Operations guide |

**Developer 3: Financial Dashboard**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D3-375-1 | Create P&L dashboard | 3h | P&L dashboard |
| D3-375-2 | Implement cash flow visualization | 3h | Cash flow charts |
| D3-375-3 | Build budget variance reports | 2h | Variance reports |

---

### Day 376-377: Advanced Analytics and Reporting

#### Day 376 Tasks

**Developer 1: Anomaly Detection and Alerting**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D1-376-1 | Implement anomaly detection algorithms | 3h | Anomaly detection |
| D1-376-2 | Create alert rule engine | 3h | Alert engine |
| D1-376-3 | Build notification system | 2h | Notifications |

**Developer 2: Self-Service Reporting**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D2-376-1 | Create report builder interface | 3h | Report builder |
| D2-376-2 | Implement query builder | 3h | Query builder |
| D2-376-3 | Add export functionality | 2h | Export features |

**Developer 3: Custom Report Builder UI**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D3-376-1 | Create drag-and-drop interface | 3h | DnD interface |
| D3-376-2 | Implement field selector | 3h | Field selector |
| D3-376-3 | Add visualization options | 2h | Viz options |

---

#### Day 377 Tasks

**Developer 1: Model Serving and Deployment**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D1-377-1 | Set up KServe model serving | 3h | KServe config |
| D1-377-2 | Deploy models to production | 3h | Model deployment |
| D1-377-3 | Implement A/B testing | 2h | A/B testing |

**Developer 2: API Development**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D2-377-1 | Create analytics REST API | 3h | REST API |
| D2-377-2 | Implement GraphQL endpoint | 3h | GraphQL API |
| D2-377-3 | Add API authentication | 2h | Auth implementation |

**Developer 3: Mobile Analytics**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D3-377-1 | Create mobile-responsive dashboards | 3h | Mobile layouts |
| D3-377-2 | Implement touch gestures | 2h | Touch support |
| D3-377-3 | Optimize for mobile performance | 3h | Performance optimization |

---

### Day 378-379: Testing and Integration

#### Day 378 Tasks

**Developer 1: Integration Testing**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D1-378-1 | Create integration test suite | 3h | Integration tests |
| D1-378-2 | Test ML pipeline end-to-end | 3h | ML pipeline tests |
| D1-378-3 | Validate prediction accuracy | 2h | Accuracy validation |

**Developer 2: Performance Testing**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D2-378-1 | Conduct load testing | 3h | Load tests |
| D2-378-2 | Optimize query performance | 3h | Query optimization |
| D2-378-3 | Test concurrent user scenarios | 2h | Concurrency tests |

**Developer 3: User Acceptance Testing**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D3-378-1 | Create UAT test cases | 2h | UAT cases |
| D3-378-2 | Execute UAT with stakeholders | 4h | UAT execution |
| D3-378-3 | Document feedback and issues | 2h | Feedback doc |

---

#### Day 379 Tasks

**Developer 1: Documentation and Handover**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D1-379-1 | Write technical documentation | 3h | Tech docs |
| D1-379-2 | Create model documentation | 2h | Model docs |
| D1-379-3 | Document operational procedures | 3h | Ops procedures |

**Developer 2: Security and Compliance**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D2-379-1 | Implement data masking | 2h | Data masking |
| D2-379-2 | Set up audit logging | 2h | Audit logs |
| D2-379-3 | Conduct security review | 2h | Security review |
| D2-379-4 | Create compliance reports | 2h | Compliance docs |

**Developer 3: Final UI Polish**

| Task ID | Description | Duration | Deliverable |
|---------|-------------|----------|-------------|
| D3-379-1 | Polish UI components | 3h | UI polish |
| D3-379-2 | Fix accessibility issues | 2h | Accessibility |
| D3-379-3 | Optimize bundle size | 2h | Bundle optimization |
| D3-379-4 | Create user guide | 1h | User guide |

---

### Day 380: Final Deployment and Launch

**All Developers - Deployment Day**

| Time | Activity | Owner | Deliverable |
|------|----------|-------|-------------|
| 08:00-09:00 | Pre-deployment checklist review | All | Checklist |
| 09:00-10:00 | Deploy to staging | Dev 2 | Staging deployment |
| 10:00-11:00 | Staging validation | All | Validation report |
| 11:00-12:00 | Deploy to production | All | Production deployment |
| 12:00-13:00 | Post-deployment verification | All | Verification report |
| 13:00-14:00 | Lunch break | - | - |
| 14:00-15:00 | User training session | Dev 3 | Training complete |
| 15:00-16:00 | Final documentation handover | All | Documentation |
| 16:00-17:00 | Milestone retrospective | All | Retrospective doc |

---

## 4. Data Warehouse Design

### 4.1 Complete ClickHouse Schema

```sql
-- =====================================================
-- SMART DAIRY DATA WAREHOUSE - COMPLETE SCHEMA
-- =====================================================

-- Drop and recreate databases
DROP DATABASE IF EXISTS smart_dairy_ods;
DROP DATABASE IF EXISTS smart_dairy_dwd;
DROP DATABASE IF EXISTS smart_dairy_dws;
DROP DATABASE IF EXISTS smart_dairy_ads;

CREATE DATABASE smart_dairy_ods;
CREATE DATABASE smart_dairy_dwd;
CREATE DATABASE smart_dairy_dws;
CREATE DATABASE smart_dairy_ads;

-- =====================================================
-- ODS LAYER - OPERATIONAL DATA STORE
-- =====================================================

-- Raw sales orders from ERP
CREATE TABLE smart_dairy_ods.sales_orders (
    order_id UInt64,
    order_number String,
    customer_id UInt64,
    product_id UInt64,
    sales_rep_id UInt64,
    quantity Decimal(18, 4),
    unit_price Decimal(18, 4),
    discount_amount Decimal(18, 4),
    tax_amount Decimal(18, 4),
    total_amount Decimal(18, 4),
    order_status String,
    order_type String,
    order_date Date,
    expected_delivery_date Nullable(Date),
    actual_delivery_date Nullable(Date),
    created_at DateTime,
    updated_at DateTime,
    deleted_at Nullable(DateTime),
    _extracted_at DateTime DEFAULT now(),
    _extraction_type String DEFAULT 'incremental',
    _source_system String DEFAULT 'erp'
) ENGINE = MergeTree()
PARTITION BY toYYYYMM(order_date)
ORDER BY (order_date, customer_id)
TTL order_date + INTERVAL 1 YEAR;

-- Raw milk production records
CREATE TABLE smart_dairy_ods.milk_production (
    production_id UInt64,
    animal_id UInt64,
    farm_id UInt64,
    session_id UInt64,
    session_type String,
    production_date Date,
    milk_volume_liters Decimal(18, 4),
    fat_percentage Decimal(5, 2),
    protein_percentage Decimal(5, 2),
    quality_grade String,
    recorded_at DateTime,
    _extracted_at DateTime DEFAULT now()
) ENGINE = MergeTree()
PARTITION BY toYYYYMM(production_date)
ORDER BY (production_date, farm_id);

-- Raw inventory transactions
CREATE TABLE smart_dairy_ods.inventory_transactions (
    transaction_id UInt64,
    product_id UInt64,
    warehouse_id UInt64,
    transaction_type String,
    quantity Decimal(18, 4),
    unit_cost Decimal(18, 4),
    transaction_date Date,
    lot_number Nullable(String),
    _extracted_at DateTime DEFAULT now()
) ENGINE = MergeTree()
PARTITION BY toYYYYMM(transaction_date)
ORDER BY (transaction_date, product_id);

-- =====================================================
-- DWD LAYER - DATA WAREHOUSE DETAIL
-- =====================================================

-- Dimension tables are already defined in Day 372

-- Sales fact table is already defined in Day 373

-- Production fact table
CREATE TABLE smart_dairy_dwd.fct_milk_production (
    production_sk UInt64,
    date_sk UInt32,
    production_date Date,
    farm_sk UInt64,
    animal_sk UInt64,
    session_type LowCardinality(String),
    milk_volume_liters Decimal(18, 4),
    fat_kg Decimal(18, 4),
    protein_kg Decimal(18, 4),
    quality_score Decimal(5, 2),
    somatic_cell_count UInt32,
    temperature_celsius Decimal(4, 2),
    created_at DateTime DEFAULT now()
) ENGINE = MergeTree()
PARTITION BY toYYYYMM(production_date)
ORDER BY (production_date, farm_sk)
TTL production_date + INTERVAL 3 YEAR;

-- Inventory snapshot fact
CREATE TABLE smart_dairy_dwd.fct_inventory_snapshot (
    snapshot_sk UInt64,
    snapshot_date Date,
    date_sk UInt32,
    product_sk UInt64,
    warehouse_sk UInt64,
    quantity_on_hand Decimal(18, 4),
    quantity_reserved Decimal(18, 4),
    quantity_available Decimal(18, 4),
    inventory_value Decimal(18, 4),
    days_of_supply Decimal(8, 2),
    created_at DateTime DEFAULT now()
) ENGINE = MergeTree()
PARTITION BY toYYYYMM(snapshot_date)
ORDER BY (snapshot_date, product_sk);

-- =====================================================
-- DWS LAYER - DATA WAREHOUSE SERVICE (AGGREGATIONS)
-- =====================================================

-- Daily sales aggregation
CREATE TABLE smart_dairy_dws.agg_sales_daily (
    date_sk UInt32,
    report_date Date,
    sales_region LowCardinality(String),
    product_category LowCardinality(String),
    sales_channel LowCardinality(String),
    
    total_orders UInt64,
    total_quantity Decimal(18, 4),
    gross_revenue Decimal(18, 4),
    net_revenue Decimal(18, 4),
    total_cost Decimal(18, 4),
    gross_profit Decimal(18, 4),
    gross_margin_pct Decimal(5, 4),
    
    unique_customers UInt32,
    unique_products UInt32,
    
    avg_order_value Decimal(18, 4),
    avg_unit_price Decimal(18, 4),
    
    created_at DateTime DEFAULT now()
) ENGINE = SummingMergeTree()
PARTITION BY toYYYYMM(report_date)
ORDER BY (report_date, sales_region, product_category)
TTL report_date + INTERVAL 2 YEAR;

-- Monthly production aggregation
CREATE TABLE smart_dairy_dws.agg_production_monthly (
    year_month UInt32,
    fiscal_year UInt16,
    fiscal_month UInt8,
    farm_sk UInt64,
    
    total_volume_liters Decimal(18, 4),
    avg_fat_pct Decimal(5, 2),
    avg_protein_pct Decimal(5, 2),
    quality_premium_pct Decimal(5, 2),
    
    total_milking_sessions UInt32,
    avg_volume_per_session Decimal(10, 4),
    
    created_at DateTime DEFAULT now()
) ENGINE = SummingMergeTree()
PARTITION BY fiscal_year
ORDER BY (year_month, farm_sk)
TTL toDate(concat(toString(fiscal_year + 3), '-03-31'));

-- Customer metrics aggregation
CREATE TABLE smart_dairy_dws.agg_customer_metrics (
    date_sk UInt32,
    metric_date Date,
    customer_sk UInt64,
    
    lifetime_value Decimal(18, 4),
    total_orders_lifetime UInt32,
    avg_order_value Decimal(18, 4),
    last_order_date Date,
    days_since_last_order UInt16,
    
    recency_score UInt8,
    frequency_score UInt8,
    monetary_score UInt8,
    rfm_segment LowCardinality(String),
    
    churn_probability Decimal(5, 4),
    churn_risk_level LowCardinality(String),
    
    created_at DateTime DEFAULT now()
) ENGINE = ReplacingMergeTree(created_at)
PARTITION BY toYYYYMM(metric_date)
ORDER BY (metric_date, customer_sk);

-- =====================================================
-- ADS LAYER - APPLICATION DATA STORE
-- =====================================================

-- Executive KPI table
CREATE TABLE smart_dairy_ads.kpi_executive (
    report_date Date,
    date_grain LowCardinality(String),
    
    -- Revenue metrics
    revenue_total Decimal(18, 4),
    revenue_target Decimal(18, 4),
    revenue_achievement_pct Decimal(5, 4),
    revenue_yoy_growth_pct Decimal(8, 4),
    revenue_mom_growth_pct Decimal(8, 4),
    
    -- Order metrics
    orders_total UInt32,
    orders_target UInt32,
    aov Decimal(18, 4),
    
    -- Production metrics
    production_volume_liters Decimal(18, 4),
    production_capacity_utilization_pct Decimal(5, 4),
    
    -- Financial metrics
    gross_margin_pct Decimal(5, 4),
    operating_margin_pct Decimal(5, 4),
    net_margin_pct Decimal(5, 4),
    
    -- Customer metrics
    active_customers UInt32,
    new_customers UInt32,
    customer_retention_rate Decimal(5, 4),
    nps_score Decimal(4, 2),
    
    updated_at DateTime DEFAULT now()
) ENGINE = ReplacingMergeTree(updated_at)
PARTITION BY toYYYYMM(report_date)
ORDER BY (report_date, date_grain);

-- Alert configuration table
CREATE TABLE smart_dairy_ads.alert_configurations (
    alert_id UInt64,
    alert_name String,
    alert_type LowCardinality(String),
    severity LowCardinality(String),
    
    metric_name String,
    threshold_operator LowCardinality(String),
    threshold_value Decimal(18, 4),
    
    notification_channels Array(String),
    recipients Array(String),
    
    is_active UInt8 DEFAULT 1,
    created_at DateTime DEFAULT now(),
    updated_at DateTime DEFAULT now()
) ENGINE = ReplacingMergeTree(updated_at)
ORDER BY alert_id;

-- Alert history
CREATE TABLE smart_dairy_ads.alert_history (
    alert_instance_id UInt64,
    alert_id UInt64,
    triggered_at DateTime,
    resolved_at Nullable(DateTime),
    
    metric_value Decimal(18, 4),
    threshold_value Decimal(18, 4),
    message String,
    
    status LowCardinality(String),
    acknowledged_by Nullable(String),
    acknowledged_at Nullable(DateTime)
) ENGINE = MergeTree()
PARTITION BY toYYYYMM(triggered_at)
ORDER BY (triggered_at, alert_id);

-- ETL metadata tracking
CREATE TABLE smart_dairy_ads.etl_metadata (
    batch_id String,
    dag_id String,
    run_id String,
    execution_date DateTime,
    
    source_system String,
    source_table String,
    target_table String,
    
    records_processed UInt64,
    records_inserted UInt64,
    records_updated UInt64,
    records_failed UInt64,
    
    started_at DateTime,
    completed_at DateTime,
    duration_seconds UInt32,
    
    status LowCardinality(String),
    error_message Nullable(String)
) ENGINE = MergeTree()
PARTITION BY toYYYYMM(execution_date)
ORDER BY (execution_date, dag_id);
```

### 4.2 Data Retention Policies

```yaml
# config/clickhouse/retention_policies.yml
retention_policies:
  ods_layer:
    default_ttl: "1 YEAR"
    tables:
      sales_orders:
        ttl: "2 YEAR"
        archive_to: "s3://smart-dairy-archive/ods/sales/"
      milk_production:
        ttl: "1 YEAR"
        compress_after: "6 MONTH"
      inventory_transactions:
        ttl: "3 YEAR"
  
  dwd_layer:
    default_ttl: "5 YEAR"
    tables:
      fct_sales:
        ttl: "7 YEAR TO VOLUME 'cold_storage'"
        optimize_after: "1 DAY"
      fct_milk_production:
        ttl: "5 YEAR"
      dim_customers:
        ttl: "FOREVER"  # Keep dimension history
      dim_products:
        ttl: "FOREVER"
  
  dws_layer:
    default_ttl: "3 YEAR"
    tables:
      agg_sales_daily:
        ttl: "5 YEAR"
        materialize_after: "1 HOUR"
      agg_production_monthly:
        ttl: "10 YEAR"  # Keep long-term trends
  
  ads_layer:
    default_ttl: "2 YEAR"
    tables:
      kpi_executive:
        ttl: "FOREVER"  # Keep all KPI history
      alert_history:
        ttl: "1 YEAR"
```

---

## 5. ETL Pipeline

### 5.1 Complete ETL Architecture

```yaml
# airflow/dags/smart_dairy_etl_pipeline.yml
# Complete ETL pipeline configuration

dags:
  # Daily full refresh DAG
  daily_full_refresh:
    schedule: "0 2 * * *"  # 2 AM daily
    tasks:
      - name: extract_dimensions
        type: postgres_to_clickhouse
        source: erp_postgres
        tables:
          - customers
          - products
          - employees
        target_db: smart_dairy_ods
      
      - name: transform_dimensions
        type: dbt_run
        models:
          - staging_dimensions
          - dim_customers
          - dim_products
          - dim_employees
        depends_on: [extract_dimensions]
      
      - name: extract_sales_incremental
        type: cdc_stream
        source: erp_postgres
        table: sales_orders
        target: smart_dairy_ods.sales_orders
        depends_on: [transform_dimensions]
      
      - name: transform_sales
        type: dbt_run
        models:
          - stg_sales_orders
          - fct_sales
        depends_on: [extract_sales_incremental]
      
      - name: aggregate_sales
        type: clickhouse_materialize
        views:
          - mv_daily_sales
          - mv_monthly_sales
        depends_on: [transform_sales]
      
      - name: update_kpis
        type: python_callable
        module: etl.jobs.update_kpis
        depends_on: [aggregate_sales]
  
  # Hourly incremental DAG
  hourly_incremental:
    schedule: "0 * * * *"  # Every hour
    tasks:
      - name: stream_production_data
        type: kafka_to_clickhouse
        topic: farm.production
        target: smart_dairy_ods.milk_production
      
      - name: process_iot_sensors
        type: spark_job
        script: etl/jobs/process_sensors.py
        cluster: analytics_spark
  
  # Real-time streaming DAG
  realtime_streaming:
    schedule: "@continuous"
    tasks:
      - name: sales_stream_processor
        type: kafka_streams
        application: SalesStreamProcessor
        topics:
          - cdc_sales_orders
          - cdc_invoices
  
  # Weekly aggregation DAG
  weekly_aggregation:
    schedule: "0 3 * * 1"  # Monday 3 AM
    tasks:
      - name: compute_weekly_metrics
        type: clickhouse_query
        queries:
          - sql/weekly_sales_summary.sql
          - sql/weekly_production_summary.sql
      
      - name: generate_weekly_reports
        type: python_callable
        module: etl.jobs.weekly_reports
```

### 5.2 ETL Error Handling and Monitoring

```python
# etl/utils/error_handler.py
"""
ETL Error Handling and Recovery System
"""

import logging
from enum import Enum
from datetime import datetime
from typing import Optional, Dict, Any
import json
from dataclasses import dataclass, asdict

logger = logging.getLogger(__name__)

class ErrorSeverity(Enum):
    WARNING = "warning"
    ERROR = "error"
    CRITICAL = "critical"

class ErrorCategory(Enum):
    SOURCE_UNAVAILABLE = "source_unavailable"
    DATA_QUALITY = "data_quality"
    TRANSFORMATION_FAILED = "transformation_failed"
    TARGET_UNAVAILABLE = "target_unavailable"
    TIMEOUT = "timeout"
    UNKNOWN = "unknown"

@dataclass
class ETLException(Exception):
    """Custom ETL Exception with rich context."""
    message: str
    severity: ErrorSeverity
    category: ErrorCategory
    task_id: str
    dag_id: str
    execution_date: datetime
    context: Dict[str, Any]
    original_exception: Optional[Exception] = None
    
    def to_dict(self) -> Dict:
        return {
            'message': self.message,
            'severity': self.severity.value,
            'category': self.category.value,
            'task_id': self.task_id,
            'dag_id': self.dag_id,
            'execution_date': self.execution_date.isoformat(),
            'context': self.context,
            'original_error': str(self.original_exception) if self.original_exception else None
        }

class ErrorHandler:
    """Centralized error handling for ETL pipeline."""
    
    def __init__(self, clickhouse_client, alert_service):
        self.ch_client = clickhouse_client
        self.alert_service = alert_service
        
    def handle_error(self, error: ETLException, retry_count: int = 0) -> str:
        """
        Handle ETL error with appropriate recovery action.
        Returns: action_taken (retry, skip, fail)
        """
        # Log to ClickHouse
        self._log_error(error)
        
        # Determine action based on severity and retry count
        if error.severity == ErrorSeverity.CRITICAL:
            action = self._handle_critical_error(error)
        elif error.severity == ErrorSeverity.ERROR:
            action = self._handle_error(error, retry_count)
        else:
            action = self._handle_warning(error)
        
        # Send alert if needed
        if error.severity in [ErrorSeverity.ERROR, ErrorSeverity.CRITICAL]:
            self._send_alert(error)
        
        logger.error(f"ETL Error handled: {error.message}, Action: {action}")
        return action
    
    def _log_error(self, error: ETLException):
        """Log error to ClickHouse for analysis."""
        query = """
        INSERT INTO smart_dairy_ads.etl_errors
        (error_id, timestamp, severity, category, dag_id, task_id, 
         execution_date, message, context, stack_trace)
        VALUES
        (generateUUIDv4(), now(), %(severity)s, %(category)s, %(dag_id)s, 
         %(task_id)s, %(execution_date)s, %(message)s, %(context)s, %(stack_trace)s)
        """
        
        self.ch_client.execute(query, {
            'severity': error.severity.value,
            'category': error.category.value,
            'dag_id': error.dag_id,
            'task_id': error.task_id,
            'execution_date': error.execution_date,
            'message': error.message,
            'context': json.dumps(error.context),
            'stack_trace': self._get_stack_trace(error.original_exception)
        })
    
    def _handle_critical_error(self, error: ETLException) -> str:
        """Handle critical errors - stop pipeline."""
        # Notify on-call
        self.alert_service.send_page(
            message=f"CRITICAL ETL Failure: {error.dag_id}.{error.task_id}",
            details=error.to_dict()
        )
        return "fail"
    
    def _handle_error(self, error: ETLException, retry_count: int) -> str:
        """Handle regular errors with retry logic."""
        max_retries = self._get_max_retries(error.category)
        
        if retry_count < max_retries:
            # Exponential backoff
            backoff_seconds = min(2 ** retry_count * 60, 3600)  # Max 1 hour
            logger.info(f"Scheduling retry {retry_count + 1}/{max_retries} in {backoff_seconds}s")
            return "retry"
        else:
            # Move to dead letter queue
            self._send_to_dlq(error)
            return "skip"
    
    def _handle_warning(self, error: ETLException) -> str:
        """Handle warnings - log and continue."""
        logger.warning(f"ETL Warning: {error.message}")
        return "continue"
    
    def _get_max_retries(self, category: ErrorCategory) -> int:
        """Get max retry count based on error category."""
        retry_policy = {
            ErrorCategory.SOURCE_UNAVAILABLE: 5,
            ErrorCategory.TARGET_UNAVAILABLE: 5,
            ErrorCategory.TIMEOUT: 3,
            ErrorCategory.DATA_QUALITY: 0,  # Don't retry data quality issues
            ErrorCategory.TRANSFORMATION_FAILED: 1,
            ErrorCategory.UNKNOWN: 2
        }
        return retry_policy.get(category, 2)
    
    def _send_to_dlq(self, error: ETLException):
        """Send failed record to dead letter queue."""
        # Implementation for DLQ
        pass
    
    def _send_alert(self, error: ETLException):
        """Send alert notification."""
        self.alert_service.send_alert(
            severity=error.severity.value,
            title=f"ETL Error: {error.task_id}",
            message=error.message,
            metadata=error.context
        )
    
    def _get_stack_trace(self, exc: Optional[Exception]) -> str:
        """Extract stack trace from exception."""
        if exc is None:
            return ""
        import traceback
        return traceback.format_exc()
```

---

## 6. Analytics Models

### 6.1 Sales Forecasting Model

```python
# ml_models/sales_forecasting.py
"""
Sales Demand Forecasting Model using Prophet and XGBoost ensemble.
"""

import pandas as pd
import numpy as np
from prophet import Prophet
from xgboost import XGBRegressor
from sklearn.ensemble import GradientBoostingRegressor
from sklearn.metrics import mean_absolute_error, mean_squared_error
import mlflow
from typing import Tuple, Dict, List
import warnings
warnings.filterwarnings('ignore')

class SalesForecastingModel:
    """
    Ensemble forecasting model combining Prophet for seasonality
    and XGBoost for feature-based predictions.
    """
    
    def __init__(self, forecast_horizon: int = 30):
        self.forecast_horizon = forecast_horizon
        self.prophet_model = None
        self.xgboost_model = None
        self.meta_learner = None
        self.feature_cols = None
        
    def prepare_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Create time-series features for ML model.
        """
        df = df.copy()
        df['ds'] = pd.to_datetime(df['ds'])
        
        # Time-based features
        df['year'] = df['ds'].dt.year
        df['month'] = df['ds'].dt.month
        df['day'] = df['ds'].dt.day
        df['dayofweek'] = df['ds'].dt.dayofweek
        df['dayofyear'] = df['ds'].dt.dayofyear
        df['weekofyear'] = df['ds'].dt.isocalendar().week
        df['quarter'] = df['ds'].dt.quarter
        
        # Cyclical encoding for periodic features
        df['month_sin'] = np.sin(2 * np.pi * df['month'] / 12)
        df['month_cos'] = np.cos(2 * np.pi * df['month'] / 12)
        df['dayofweek_sin'] = np.sin(2 * np.pi * df['dayofweek'] / 7)
        df['dayofweek_cos'] = np.cos(2 * np.pi * df['dayofweek'] / 7)
        
        # Lag features
        for lag in [1, 7, 14, 30]:
            df[f'sales_lag_{lag}'] = df['y'].shift(lag)
        
        # Rolling statistics
        for window in [7, 14, 30]:
            df[f'sales_rolling_mean_{window}'] = df['y'].rolling(window=window).mean()
            df[f'sales_rolling_std_{window}'] = df['y'].rolling(window=window).std()
        
        # Growth rate
        df['sales_growth_rate'] = df['y'].pct_change()
        
        # Expanding statistics
        df['sales_expanding_mean'] = df['y'].expanding().mean()
        
        return df.dropna()
    
    def train(self, df: pd.DataFrame, test_size: int = 30) -> Dict:
        """
        Train ensemble forecasting model.
        """
        with mlflow.start_run(run_name="sales_forecast_ensemble"):
            # Split data
            train_df = df.iloc[:-test_size]
            test_df = df.iloc[-test_size:]
            
            # Train Prophet model
            self.prophet_model = Prophet(
                yearly_seasonality=True,
                weekly_seasonality=True,
                daily_seasonality=False,
                seasonality_mode='multiplicative',
                changepoint_prior_scale=0.05,
                holidays=self._get_kenyan_holidays()
            )
            
            # Add custom seasonalities
            self.prophet_model.add_seasonality(
                name='monthly', period=30.5, fourier_order=5
            )
            
            self.prophet_model.fit(train_df[['ds', 'y']])
            
            # Get Prophet predictions for training meta-learner
            prophet_train_pred = self.prophet_model.predict(train_df[['ds']])['yhat'].values
            prophet_test_pred = self.prophet_model.predict(test_df[['ds']])['yhat'].values
            
            # Prepare features for XGBoost
            feature_df = self.prepare_features(df)
            self.feature_cols = [c for c in feature_df.columns if c not in ['ds', 'y']]
            
            train_features = feature_df.iloc[:-test_size]
            test_features = feature_df.iloc[-test_size:]
            
            # Train XGBoost
            self.xgboost_model = XGBRegressor(
                n_estimators=200,
                max_depth=6,
                learning_rate=0.1,
                subsample=0.8,
                colsample_bytree=0.8,
                random_state=42
            )
            
            self.xgboost_model.fit(
                train_features[self.feature_cols],
                train_features['y']
            )
            
            # Get XGBoost predictions
            xgb_train_pred = self.xgboost_model.predict(train_features[self.feature_cols])
            xgb_test_pred = self.xgboost_model.predict(test_features[self.feature_cols])
            
            # Train meta-learner (ensemble weights)
            ensemble_features_train = np.column_stack([
                prophet_train_pred,
                xgb_train_pred
            ])
            
            ensemble_features_test = np.column_stack([
                prophet_test_pred,
                xgb_test_pred
            ])
            
            self.meta_learner = GradientBoostingRegressor(
                n_estimators=100,
                max_depth=3,
                random_state=42
            )
            self.meta_learner.fit(ensemble_features_train, train_df['y'].values)
            
            # Evaluate
            ensemble_pred = self.meta_learner.predict(ensemble_features_test)
            
            metrics = {
                'mae': mean_absolute_error(test_df['y'], ensemble_pred),
                'rmse': np.sqrt(mean_squared_error(test_df['y'], ensemble_pred)),
                'mape': np.mean(np.abs((test_df['y'] - ensemble_pred) / test_df['y'])) * 100,
                'prophet_mape': np.mean(np.abs((test_df['y'] - prophet_test_pred) / test_df['y'])) * 100,
                'xgboost_mape': np.mean(np.abs((test_df['y'] - xgb_test_pred) / test_df['y'])) * 100
            }
            
            # Log to MLflow
            mlflow.log_params({
                'forecast_horizon': self.forecast_horizon,
                'prophet_changepoint_prior': 0.05,
                'xgboost_n_estimators': 200,
                'test_size': test_size
            })
            
            mlflow.log_metrics(metrics)
            
            # Log models
            mlflow.prophet.log_model(self.prophet_model, "prophet_model")
            mlflow.xgboost.log_model(self.xgboost_model, "xgboost_model")
            mlflow.sklearn.log_model(self.meta_learner, "meta_learner")
            
            return metrics
    
    def predict(self, future_dates: pd.DataFrame, 
                external_features: pd.DataFrame = None) -> pd.DataFrame:
        """
        Generate forecasts for future dates.
        """
        # Prophet prediction
        prophet_future = self.prophet_model.predict(future_dates)
        prophet_pred = prophet_future['yhat'].values
        prophet_lower = prophet_future['yhat_lower'].values
        prophet_upper = prophet_future['yhat_upper'].values
        
        # XGBoost prediction
        if external_features is not None:
            feature_df = self.prepare_features(external_features)
            xgb_pred = self.xgboost_model.predict(feature_df[self.feature_cols])
        else:
            xgb_pred = prophet_pred  # Fallback
        
        # Ensemble prediction
        ensemble_features = np.column_stack([prophet_pred, xgb_pred])
        ensemble_pred = self.meta_learner.predict(ensemble_features)
        
        # Create forecast dataframe
        forecast = pd.DataFrame({
            'ds': future_dates['ds'],
            'forecast': ensemble_pred,
            'prophet_component': prophet_pred,
            'xgboost_component': xgb_pred,
            'lower_bound': prophet_lower,
            'upper_bound': prophet_upper
        })
        
        return forecast
    
    def _get_kenyan_holidays(self) -> pd.DataFrame:
        """
        Kenyan holidays for Prophet.
        """
        holidays = pd.DataFrame({
            'holiday': 'new_year',
            'ds': pd.to_datetime(['2024-01-01', '2025-01-01', '2026-01-01']),
            'lower_window': 0,
            'upper_window': 0
        })
        
        # Add more Kenyan holidays
        easter_dates = ['2024-03-31', '2024-04-01', '2025-04-20', '2025-04-21']
        easter_df = pd.DataFrame({
            'holiday': 'easter',
            'ds': pd.to_datetime(easter_dates),
            'lower_window': -1,
            'upper_window': 0
        })
        
        holidays = pd.concat([holidays, easter_df])
        
        return holidays
```

### 6.2 Customer Segmentation Model

```python
# ml_models/customer_segmentation.py
"""
RFM-based customer segmentation with K-Means clustering.
"""

import pandas as pd
import numpy as np
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import silhouette_score
import mlflow
import matplotlib.pyplot as plt
import seaborn as sns

class CustomerSegmentationModel:
    """
    Customer segmentation using RFM (Recency, Frequency, Monetary) analysis
    combined with behavioral clustering.
    """
    
    def __init__(self, n_segments: int = 5):
        self.n_segments = n_segments
        self.kmeans_model = None
        self.scaler = StandardScaler()
        self.segment_labels = {
            0: 'Champions',
            1: 'Loyal Customers',
            2: 'Potential Loyalists',
            3: 'At Risk',
            4: 'Lost Customers'
        }
    
    def calculate_rfm(self, df: pd.DataFrame, 
                      customer_col: str = 'customer_id',
                      date_col: str = 'order_date',
                      amount_col: str = 'total_amount') -> pd.DataFrame:
        """
        Calculate RFM metrics for each customer.
        """
        df[date_col] = pd.to_datetime(df[date_col])
        
        # Calculate reference date (day after last transaction)
        reference_date = df[date_col].max() + pd.Timedelta(days=1)
        
        rfm = df.groupby(customer_col).agg({
            date_col: lambda x: (reference_date - x.max()).days,  # Recency
            customer_col: 'count',  # Frequency
            amount_col: 'sum'  # Monetary
        }).reset_index()
        
        rfm.columns = ['customer_id', 'recency', 'frequency', 'monetary']
        
        # Calculate RFM scores (1-5 scale)
        rfm['r_score'] = pd.qcut(rfm['recency'], 5, labels=[5,4,3,2,1])
        rfm['f_score'] = pd.qcut(rfm['frequency'].rank(method='first'), 5, labels=[1,2,3,4,5])
        rfm['m_score'] = pd.qcut(rfm['monetary'], 5, labels=[1,2,3,4,5])
        
        # Combined RFM score
        rfm['rfm_score'] = rfm['r_score'].astype(str) + \
                          rfm['f_score'].astype(str) + \
                          rfm['m_score'].astype(str)
        
        return rfm
    
    def train(self, rfm_df: pd.DataFrame) -> Dict:
        """
        Train segmentation model.
        """
        with mlflow.start_run(run_name="customer_segmentation"):
            # Select features for clustering
            features = ['recency', 'frequency', 'monetary']
            X = rfm_df[features].copy()
            
            # Log transform monetary for normalization
            X['monetary'] = np.log1p(X['monetary'])
            
            # Scale features
            X_scaled = self.scaler.fit_transform(X)
            
            # Find optimal clusters using elbow method and silhouette
            scores = []
            silhouette_scores = []
            K_range = range(2, 10)
            
            for k in K_range:
                kmeans = KMeans(n_clusters=k, random_state=42, n_init=10)
                kmeans.fit(X_scaled)
                scores.append(kmeans.inertia_)
                silhouette_scores.append(silhouette_score(X_scaled, kmeans.labels_))
            
            # Use configured number of clusters
            self.kmeans_model = KMeans(
                n_clusters=self.n_segments,
                random_state=42,
                n_init=10
            )
            
            rfm_df['segment'] = self.kmeans_model.fit_predict(X_scaled)
            
            # Calculate segment characteristics
            segment_analysis = rfm_df.groupby('segment').agg({
                'recency': ['mean', 'median'],
                'frequency': ['mean', 'median'],
                'monetary': ['mean', 'median'],
                'customer_id': 'count'
            }).round(2)
            
            # Calculate silhouette score
            final_silhouette = silhouette_score(X_scaled, rfm_df['segment'])
            
            # Assign segment names based on characteristics
            segment_means = rfm_df.groupby('segment')[['recency', 'frequency', 'monetary']].mean()
            
            # Sort by value (low recency, high frequency, high monetary = best)
            segment_ranking = (
                segment_means['recency'].rank(ascending=True) * 0.25 +
                segment_means['frequency'].rank(ascending=False) * 0.35 +
                segment_means['monetary'].rank(ascending=False) * 0.40
            )
            
            # Map segments to names
            segment_mapping = {
                segment_ranking.idxmax(): 'Champions',
                segment_ranking.nlargest(2).index[-1]: 'Loyal Customers',
                segment_ranking.idxmin(): 'Lost Customers',
            }
            
            # Assign remaining
            unassigned = set(range(self.n_segments)) - set(segment_mapping.keys())
            for i, seg in enumerate(unassigned):
                segment_mapping[seg] = ['Potential Loyalists', 'At Risk', 'New Customers'][i]
            
            rfm_df['segment_name'] = rfm_df['segment'].map(segment_mapping)
            
            # Log metrics
            metrics = {
                'silhouette_score': final_silhouette,
                'inertia': self.kmeans_model.inertia_,
                'n_customers': len(rfm_df),
                'avg_recency': rfm_df['recency'].mean(),
                'avg_frequency': rfm_df['frequency'].mean(),
                'avg_monetary': rfm_df['monetary'].mean()
            }
            
            mlflow.log_params({
                'n_segments': self.n_segments,
                'features': ','.join(features)
            })
            
            mlflow.log_metrics(metrics)
            
            # Log artifacts
            self._create_visualizations(rfm_df)
            mlflow.log_artifact("segment_distribution.png")
            mlflow.log_artifact("rfm_heatmap.png")
            
            # Save model
            mlflow.sklearn.log_model(self.kmeans_model, "segmentation_model")
            
            return {
                'metrics': metrics,
                'segment_analysis': segment_analysis.to_dict(),
                'segment_mapping': segment_mapping
            }
    
    def predict_segment(self, customer_data: pd.DataFrame) -> pd.DataFrame:
        """
        Predict segments for new customers.
        """
        features = ['recency', 'frequency', 'monetary']
        X = customer_data[features].copy()
        X['monetary'] = np.log1p(X['monetary'])
        X_scaled = self.scaler.transform(X)
        
        customer_data['segment'] = self.kmeans_model.predict(X_scaled)
        
        return customer_data
    
    def _create_visualizations(self, rfm_df: pd.DataFrame):
        """Create and save visualization plots."""
        fig, axes = plt.subplots(2, 2, figsize=(15, 12))
        
        # Segment distribution
        rfm_df['segment_name'].value_counts().plot(
            kind='bar', ax=axes[0,0], title='Customer Segment Distribution'
        )
        
        # RFM heatmap
        pivot = rfm_df.groupby('segment_name')[['recency', 'frequency', 'monetary']].mean()
        sns.heatmap(pivot, annot=True, fmt='.0f', ax=axes[0,1], cmap='YlOrRd')
        axes[0,1].set_title('Average RFM by Segment')
        
        # Monetary distribution
        rfm_df.boxplot(column='monetary', by='segment_name', ax=axes[1,0])
        axes[1,0].set_title('Monetary Value Distribution by Segment')
        
        # Recency vs Frequency scatter
        for segment in rfm_df['segment_name'].unique():
            segment_data = rfm_df[rfm_df['segment_name'] == segment]
            axes[1,1].scatter(
                segment_data['recency'], 
                segment_data['frequency'],
                label=segment, alpha=0.6
            )
        axes[1,1].set_xlabel('Recency (days)')
        axes[1,1].set_ylabel('Frequency (orders)')
        axes[1,1].set_title('Customer Segments: Recency vs Frequency')
        axes[1,1].legend()
        
        plt.tight_layout()
        plt.savefig("segment_analysis.png", dpi=150)
        plt.close()
```

---

## 7. Dashboard Specifications

### 7.1 Dashboard Layout Specifications

```typescript
// dashboard_specs/layouts.ts
/**
 * Dashboard Layout Specifications
 * Grid system: 12 columns, responsive breakpoints
 */

export interface DashboardLayout {
  id: string;
  name: string;
  description: string;
  gridConfig: GridConfig;
  sections: DashboardSection[];
  filters: DashboardFilter[];
  refreshInterval: number; // seconds
}

export interface GridConfig {
  columns: number;
  rowHeight: number; // pixels
  breakpoints: {
    mobile: number;
    tablet: number;
    desktop: number;
    wide: number;
  };
}

export interface DashboardSection {
  id: string;
  title: string;
  layout: 'grid' | 'tabs' | 'accordion';
  components: DashboardComponent[];
  collapsible: boolean;
  defaultCollapsed: boolean;
}

export interface DashboardComponent {
  id: string;
  type: 'kpi_card' | 'chart' | 'table' | 'map' | 'filter' | 'text';
  title?: string;
  position: {
    x: number;
    y: number;
    w: number;
    h: number;
  };
  config: ComponentConfig;
  dataSource: DataSourceConfig;
  refreshInterval?: number;
}

// Executive Dashboard Layout
export const executiveDashboardLayout: DashboardLayout = {
  id: 'executive-dashboard',
  name: 'Executive Dashboard',
  description: 'High-level business performance overview for executives',
  gridConfig: {
    columns: 12,
    rowHeight: 80,
    breakpoints: {
      mobile: 480,
      tablet: 768,
      desktop: 1024,
      wide: 1440
    }
  },
  sections: [
    {
      id: 'kpis',
      title: 'Key Performance Indicators',
      layout: 'grid',
      collapsible: false,
      defaultCollapsed: false,
      components: [
        {
          id: 'kpi-revenue',
          type: 'kpi_card',
          title: 'Total Revenue',
          position: { x: 0, y: 0, w: 2, h: 2 },
          config: {
            metric: 'total_revenue',
            format: 'currency',
            comparison: 'previous_period',
            sparkline: true
          },
          dataSource: {
            endpoint: '/api/kpis/revenue',
            params: { period: 'current' }
          },
          refreshInterval: 300
        },
        {
          id: 'kpi-orders',
          type: 'kpi_card',
          title: 'Total Orders',
          position: { x: 2, y: 0, w: 2, h: 2 },
          config: {
            metric: 'total_orders',
            format: 'number',
            comparison: 'previous_period'
          },
          dataSource: {
            endpoint: '/api/kpis/orders',
            params: { period: 'current' }
          },
          refreshInterval: 300
        },
        {
          id: 'kpi-production',
          type: 'kpi_card',
          title: 'Production Volume',
          position: { x: 4, y: 0, w: 2, h: 2 },
          config: {
            metric: 'production_volume',
            format: 'volume',
            unit: 'L',
            comparison: 'previous_period'
          },
          dataSource: {
            endpoint: '/api/kpis/production',
            params: { period: 'current' }
          },
          refreshInterval: 600
        },
        {
          id: 'kpi-margin',
          type: 'kpi_card',
          title: 'Gross Margin',
          position: { x: 6, y: 0, w: 2, h: 2 },
          config: {
            metric: 'gross_margin_pct',
            format: 'percentage',
            comparison: 'previous_period',
            target: 0.35
          },
          dataSource: {
            endpoint: '/api/kpis/margin',
            params: { period: 'current' }
          },
          refreshInterval: 600
        },
        {
          id: 'kpi-customers',
          type: 'kpi_card',
          title: 'Active Customers',
          position: { x: 8, y: 0, w: 2, h: 2 },
          config: {
            metric: 'active_customers',
            format: 'number',
            comparison: 'previous_period'
          },
          dataSource: {
            endpoint: '/api/kpis/customers',
            params: { period: 'current' }
          },
          refreshInterval: 300
        },
        {
          id: 'kpi-satisfaction',
          type: 'kpi_card',
          title: 'Customer NPS',
          position: { x: 10, y: 0, w: 2, h: 2 },
          config: {
            metric: 'nps_score',
            format: 'number',
            min: -100,
            max: 100
          },
          dataSource: {
            endpoint: '/api/kpis/nps',
            params: { period: 'current' }
          },
          refreshInterval: 3600
        }
      ]
    },
    {
      id: 'trends',
      title: 'Trend Analysis',
      layout: 'grid',
      collapsible: true,
      defaultCollapsed: false,
      components: [
        {
          id: 'chart-sales-trend',
          type: 'chart',
          title: 'Revenue Trend',
          position: { x: 0, y: 2, w: 6, h: 4 },
          config: {
            chartType: 'line',
            xAxis: 'date',
            yAxis: ['revenue', 'orders'],
            dualAxis: true,
            timeGranularity: 'day',
            showLegend: true,
            enableZoom: true
          },
          dataSource: {
            endpoint: '/api/charts/sales-trend',
            params: { days: 30 }
          },
          refreshInterval: 300
        },
        {
          id: 'chart-revenue-by-region',
          type: 'chart',
          title: 'Revenue by Region',
          position: { x: 6, y: 2, w: 6, h: 4 },
          config: {
            chartType: 'bar',
            xAxis: 'region',
            yAxis: 'revenue',
            stacked: false,
            horizontal: false
          },
          dataSource: {
            endpoint: '/api/charts/revenue-by-region',
            params: {}
          },
          refreshInterval: 600
        }
      ]
    },
    {
      id: 'production',
      title: 'Production Overview',
      layout: 'grid',
      collapsible: true,
      defaultCollapsed: false,
      components: [
        {
          id: 'chart-production',
          type: 'chart',
          title: 'Daily Production by Farm',
          position: { x: 0, y: 6, w: 8, h: 4 },
          config: {
            chartType: 'area',
            xAxis: 'date',
            yAxis: 'volume_liters',
            series: 'farm_name',
            stacked: true
          },
          dataSource: {
            endpoint: '/api/charts/daily-production',
            params: { days: 30 }
          },
          refreshInterval: 600
        },
        {
          id: 'table-top-products',
          type: 'table',
          title: 'Top Products by Revenue',
          position: { x: 8, y: 6, w: 4, h: 4 },
          config: {
            columns: [
              { field: 'product_name', header: 'Product', sortable: true },
              { field: 'revenue', header: 'Revenue', format: 'currency', sortable: true },
              { field: 'growth', header: 'Growth %', format: 'percentage' }
            ],
            pageSize: 10,
            sortField: 'revenue',
            sortOrder: 'desc'
          },
          dataSource: {
            endpoint: '/api/tables/top-products',
            params: { limit: 10 }
          },
          refreshInterval: 600
        }
      ]
    }
  ],
  filters: [
    {
      id: 'date-range',
      type: 'date_range',
      label: 'Date Range',
      defaultValue: { start: '-30d', end: 'today' },
      presets: ['today', 'yesterday', 'last_7_days', 'last_30_days', 'this_month', 'last_month']
    },
    {
      id: 'region',
      type: 'multi_select',
      label: 'Region',
      options: ['North', 'South', 'East', 'West', 'Central'],
      defaultValue: ['all']
    },
    {
      id: 'product-category',
      type: 'single_select',
      label: 'Product Category',
      options: ['All', 'Raw Milk', 'Pasteurized', 'UHT', 'Cheese', 'Butter', 'Yogurt'],
      defaultValue: 'All'
    },
    {
      id: 'farm',
      type: 'multi_select',
      label: 'Farm',
      optionsEndpoint: '/api/farms/list',
      defaultValue: ['all']
    }
  ],
  refreshInterval: 300
};
```

### 7.2 Chart Component Library

```typescript
// components/charts/ChartRegistry.tsx
/**
 * Chart Component Registry
 * All available chart types for dashboards
 */

import React from 'react';
import { LineChart } from './LineChart';
import { BarChart } from './BarChart';
import { PieChart } from './PieChart';
import { AreaChart } from './AreaChart';
import { ScatterChart } from './ScatterChart';
import { HeatmapChart } from './HeatmapChart';
import { GaugeChart } from './GaugeChart';
import { FunnelChart } from './FunnelChart';
import { TableChart } from './TableChart';
import { MetricCard } from './MetricCard';
import { MapChart } from './MapChart';

export type ChartType = 
  | 'line' | 'bar' | 'pie' | 'area' | 'scatter' 
  | 'heatmap' | 'gauge' | 'funnel' | 'table' | 'metric' | 'map';

export interface ChartComponentProps {
  data: any[];
  config: ChartConfig;
  width: number;
  height: number;
  onClick?: (data: any) => void;
  onBrushChange?: (range: [Date, Date]) => void;
}

export interface ChartConfig {
  type: ChartType;
  xAxis?: string;
  yAxis?: string | string[];
  series?: string;
  color?: string | string[];
  stacked?: boolean;
  horizontal?: boolean;
  showLegend?: boolean;
  showGrid?: boolean;
  showTooltip?: boolean;
  dualAxis?: boolean;
  timeGranularity?: 'hour' | 'day' | 'week' | 'month' | 'quarter' | 'year';
  aggregation?: 'sum' | 'avg' | 'count' | 'min' | 'max';
  format?: {
    x?: string;
    y?: string;
    tooltip?: string;
  };
  annotations?: Annotation[];
  thresholds?: Threshold[];
}

export const ChartRegistry: Record<ChartType, React.FC<ChartComponentProps>> = {
  line: LineChart,
  bar: BarChart,
  pie: PieChart,
  area: AreaChart,
  scatter: ScatterChart,
  heatmap: HeatmapChart,
  gauge: GaugeChart,
  funnel: FunnelChart,
  table: TableChart,
  metric: MetricCard,
  map: MapChart
};

// Chart configuration defaults
export const ChartDefaults: Record<ChartType, Partial<ChartConfig>> = {
  line: {
    showLegend: true,
    showGrid: true,
    showTooltip: true,
    timeGranularity: 'day'
  },
  bar: {
    stacked: false,
    horizontal: false,
    showLegend: true
  },
  pie: {
    showLegend: true,
    showTooltip: true
  },
  area: {
    stacked: true,
    showLegend: true
  },
  scatter: {
    showTooltip: true
  },
  heatmap: {
    showTooltip: true
  },
  gauge: {},
  funnel: {
    showTooltip: true
  },
  table: {},
  metric: {},
  map: {
    showTooltip: true
  }
};
```

---

## 8. KPI Definitions

### 8.1 Business KPIs

```yaml
# config/kpis/business_kpis.yml
kpis:
  # Revenue KPIs
  total_revenue:
    name: "Total Revenue"
    description: "Gross revenue from all sales transactions"
    category: "financial"
    unit: "KES"
    format: "currency"
    calculation: |
      SUM(sales.total_amount)
    aggregation: "sum"
    time_grains: ["day", "week", "month", "quarter", "year"]
    dimensions: ["region", "product_category", "sales_channel", "customer_segment"]
    targets:
      monthly: 50000000  # 50M KES
      yearly: 600000000  # 600M KES
    alerts:
      - condition: "value < target * 0.8"
        severity: "warning"
        message: "Revenue below 80% of target"
      - condition: "yoy_growth < -0.1"
        severity: "critical"
        message: "Revenue declining year-over-year"

  net_revenue:
    name: "Net Revenue"
    description: "Revenue after discounts and returns"
    category: "financial"
    unit: "KES"
    format: "currency"
    calculation: |
      SUM(sales.total_amount - sales.discount_amount - sales.returns)
    aggregation: "sum"

  average_order_value:
    name: "Average Order Value"
    description: "Average monetary value per order"
    category: "sales"
    unit: "KES"
    format: "currency"
    calculation: |
      SUM(sales.total_amount) / COUNT(DISTINCT sales.order_id)
    aggregation: "avg"
    target: 15000  # 15K KES

  # Production KPIs
  daily_production_volume:
    name: "Daily Production Volume"
    description: "Total milk production volume per day"
    category: "production"
    unit: "L"
    format: "volume"
    calculation: |
      SUM(production.milk_volume_liters)
    aggregation: "sum"
    time_grains: ["day", "week", "month"]
    dimensions: ["farm", "session", "quality_grade"]
    targets:
      daily: 50000  # 50,000 L
    alerts:
      - condition: "value < target * 0.9"
        severity: "warning"
        message: "Production below target"

  production_capacity_utilization:
    name: "Capacity Utilization"
    description: "Percentage of maximum production capacity being used"
    category: "production"
    unit: "%"
    format: "percentage"
    calculation: |
      (SUM(production.milk_volume_liters) / SUM(farm.daily_capacity_liters)) * 100
    aggregation: "avg"
    target: 0.85
    alert_thresholds:
      warning_low: 0.70
      critical_low: 0.60
      warning_high: 0.95

  # Customer KPIs
  active_customers:
    name: "Active Customers"
    description: "Number of unique customers with orders in period"
    category: "customer"
    unit: "count"
    format: "number"
    calculation: |
      COUNT(DISTINCT sales.customer_id)
    aggregation: "count_distinct"
    time_grains: ["day", "week", "month", "quarter"]
    dimensions: ["region", "customer_segment", "acquisition_channel"]

  customer_acquisition_cost:
    name: "Customer Acquisition Cost"
    description: "Average cost to acquire a new customer"
    category: "customer"
    unit: "KES"
    format: "currency"
    calculation: |
      SUM(marketing.spend) / COUNT(DISTINCT new_customers.customer_id)
    aggregation: "avg"
    target: 5000

  customer_lifetime_value:
    name: "Customer Lifetime Value"
    description: "Predicted total revenue from a customer relationship"
    category: "customer"
    unit: "KES"
    format: "currency"
    calculation: |
      (AVG(order_value) * PURCHASE_FREQUENCY * LIFESPAN) - ACQUISITION_COST
    model: "predictive"
    refresh_frequency: "weekly"

  # Quality KPIs
  quality_premium_rate:
    name: "Quality Premium Rate"
    description: "Percentage of production meeting premium quality standards"
    category: "quality"
    unit: "%"
    format: "percentage"
    calculation: |
      (COUNT(CASE WHEN quality_grade = 'Premium' THEN 1 END) / COUNT(*)) * 100
    aggregation: "avg"
    target: 0.90

  somatic_cell_count_avg:
    name: "Average Somatic Cell Count"
    description: "Average somatic cells per milliliter"
    category: "quality"
    unit: "cells/mL"
    format: "number"
    calculation: |
      AVG(production.somatic_cell_count)
    aggregation: "avg"
    target: 200000
    alert_thresholds:
      warning: 300000
      critical: 400000

  # Financial KPIs
  gross_margin:
    name: "Gross Margin"
    description: "Revenue minus cost of goods sold"
    category: "financial"
    unit: "%"
    format: "percentage"
    calculation: |
      ((SUM(sales.total_amount) - SUM(sales.cogs)) / SUM(sales.total_amount)) * 100
    aggregation: "avg"
    target: 0.35
    alert_thresholds:
      warning: 0.30
      critical: 0.25

  operating_margin:
    name: "Operating Margin"
    description: "Operating income as percentage of revenue"
    category: "financial"
    unit: "%"
    format: "percentage"
    calculation: |
      (SUM(operating_income) / SUM(revenue)) * 100
    aggregation: "avg"
    target: 0.20

  cash_conversion_cycle:
    name: "Cash Conversion Cycle"
    description: "Days to convert inventory and receivables to cash"
    category: "financial"
    unit: "days"
    format: "number"
    calculation: |
      DIO + DSO - DPO
    components:
      DIO: "Days Inventory Outstanding"
      DSO: "Days Sales Outstanding"
      DPO: "Days Payables Outstanding"
    target: 30

  # Operational KPIs
  order_fulfillment_rate:
    name: "Order Fulfillment Rate"
    description: "Percentage of orders fulfilled on time and in full"
    category: "operations"
    unit: "%"
    format: "percentage"
    calculation: |
      (COUNT(CASE WHEN delivered_on_time AND delivered_in_full THEN 1 END) / COUNT(*)) * 100
    aggregation: "avg"
    target: 0.95

  inventory_turnover:
    name: "Inventory Turnover"
    description: "Number of times inventory is sold and replaced"
    category: "operations"
    unit: "ratio"
    format: "number"
    calculation: |
      SUM(cogs) / AVG(inventory_value)
    aggregation: "avg"
    target: 12

  feed_conversion_ratio:
    name: "Feed Conversion Ratio"
    description: "Kilograms of feed per liter of milk produced"
    category: "operations"
    unit: "kg/L"
    format: "ratio"
    calculation: |
      SUM(feed_consumed_kg) / SUM(milk_produced_liters)
    aggregation: "avg"
    target: 0.4
    alert_thresholds:
      warning: 0.5
      critical: 0.6
```

### 8.2 KPI Calculation SQL

```sql
-- =====================================================
-- KPI CALCULATION STORED PROCEDURES
-- =====================================================

-- Procedure to calculate all daily KPIs
CREATE OR REPLACE PROCEDURE smart_dairy_ads.calculate_daily_kpis(
    IN calculation_date Date
)
LANGUAGE SQL
AS $$
    -- Revenue KPIs
    INSERT INTO smart_dairy_ads.daily_kpi_values
    SELECT 
        calculation_date as kpi_date,
        'total_revenue' as kpi_name,
        'financial' as kpi_category,
        toString(SUM(total_amount)) as kpi_value,
        'KES' as unit,
        now() as calculated_at
    FROM smart_dairy_dwd.fct_sales
    WHERE order_date = calculation_date;

    -- Order count
    INSERT INTO smart_dairy_ads.daily_kpi_values
    SELECT 
        calculation_date,
        'total_orders',
        'sales',
        toString(COUNT(DISTINCT order_id)),
        'count',
        now()
    FROM smart_dairy_dwd.fct_sales
    WHERE order_date = calculation_date;

    -- Production volume
    INSERT INTO smart_dairy_ads.daily_kpi_values
    SELECT 
        calculation_date,
        'daily_production_volume',
        'production',
        toString(SUM(milk_volume_liters)),
        'L',
        now()
    FROM smart_dairy_dwd.fct_milk_production
    WHERE production_date = calculation_date;

    -- Gross margin
    INSERT INTO smart_dairy_ads.daily_kpi_values
    SELECT 
        calculation_date,
        'gross_margin_pct',
        'financial',
        toString(AVG(gross_margin_pct)),
        'percentage',
        now()
    FROM smart_dairy_dwd.fct_sales
    WHERE order_date = calculation_date;

    -- Active customers
    INSERT INTO smart_dairy_ads.daily_kpi_values
    SELECT 
        calculation_date,
        'active_customers',
        'customer',
        toString(COUNT(DISTINCT customer_sk)),
        'count',
        now()
    FROM smart_dairy_dwd.fct_sales
    WHERE order_date = calculation_date;
$$;
```

---

## 9. API & Integration

### 9.1 Analytics API Specification

```yaml
# api/analytics_api_spec.yml
openapi: 3.0.3
info:
  title: Smart Dairy Analytics API
  version: 1.0.0
  description: RESTful API for accessing analytics data and dashboards

servers:
  - url: https://api.smartdairy.com/v1
    description: Production server
  - url: https://staging-api.smartdairy.com/v1
    description: Staging server

security:
  - bearerAuth: []
  - apiKeyAuth: []

paths:
  /analytics/kpis:
    get:
      summary: Get KPI values
      description: Retrieve current and historical KPI values
      parameters:
        - name: kpi_names
          in: query
          schema:
            type: array
            items:
              type: string
          description: List of KPI names to retrieve
        - name: start_date
          in: query
          required: true
          schema:
            type: string
            format: date
        - name: end_date
          in: query
          required: true
          schema:
            type: string
            format: date
        - name: granularity
          in: query
          schema:
            type: string
            enum: [day, week, month, quarter, year]
          default: day
        - name: filters
          in: query
          schema:
            type: object
            properties:
              region:
                type: array
                items:
                  type: string
              product_category:
                type: array
                items:
                  type: string
      responses:
        '200':
          description: KPI values retrieved successfully
          content:
            application/json:
              schema:
                type: object
                properties:
                  kpis:
                    type: array
                    items:
                      $ref: '#/components/schemas/KPIValue'
                  metadata:
                    $ref: '#/components/schemas/QueryMetadata'

  /analytics/charts/{chart_type}:
    get:
      summary: Get chart data
      description: Retrieve data for specific chart types
      parameters:
        - name: chart_type
          in: path
          required: true
          schema:
            type: string
            enum: [sales-trend, revenue-by-region, production-overview, 
                   customer-segmentation, inventory-levels]
        - name: start_date
          in: query
          required: true
          schema:
            type: string
            format: date
        - name: end_date
          in: query
          required: true
          schema:
            type: string
            format: date
        - name: filters
          in: query
          schema:
            type: object
      responses:
        '200':
          description: Chart data retrieved successfully
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ChartData'

  /analytics/reports:
    post:
      summary: Generate custom report
      description: Generate a custom report based on specified parameters
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/ReportRequest'
      responses:
        '202':
          description: Report generation started
          content:
            application/json:
              schema:
                type: object
                properties:
                  report_id:
                    type: string
                  status:
                    type: string
                    enum: [queued, processing, completed, failed]
                  estimated_completion:
                    type: string
                    format: date-time

  /analytics/reports/{report_id}:
    get:
      summary: Get report status and results
      parameters:
        - name: report_id
          in: path
          required: true
          schema:
            type: string
      responses:
        '200':
          description: Report status retrieved
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ReportResult'

  /analytics/predictions:
    post:
      summary: Get predictions from ML models
      description: Retrieve predictions from deployed ML models
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                model:
                  type: string
                  enum: [demand_forecast, customer_churn, price_optimization]
                parameters:
                  type: object
      responses:
        '200':
          description: Predictions retrieved
          content:
            application/json:
              schema:
                type: object
                properties:
                  predictions:
                    type: array
                  confidence_intervals:
                    type: object
                  model_version:
                    type: string

  /analytics/export:
    post:
      summary: Export data
      description: Export analytics data in various formats
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                query:
                  type: string
                format:
                  type: string
                  enum: [csv, xlsx, json, pdf]
                filters:
                  type: object
      responses:
        '202':
          description: Export job started
          content:
            application/json:
              schema:
                type: object
                properties:
                  export_id:
                    type: string
                  download_url:
                    type: string
                    format: uri

  /embed/dashboard/{dashboard_id}:
    get:
      summary: Get embeddable dashboard token
      description: Generate a secure token for embedding dashboards
      parameters:
        - name: dashboard_id
          in: path
          required: true
          schema:
            type: string
        - name: user_attributes
          in: query
          schema:
            type: object
      responses:
        '200':
          description: Embed token generated
          content:
            application/json:
              schema:
                type: object
                properties:
                  embed_token:
                    type: string
                  expires_at:
                    type: string
                    format: date-time
                  dashboard_url:
                    type: string
                    format: uri

components:
  schemas:
    KPIValue:
      type: object
      properties:
        kpi_name:
          type: string
        kpi_category:
          type: string
        value:
          type: number
        previous_value:
          type: number
        change_percentage:
          type: number
        target:
          type: number
        unit:
          type: string
        timestamp:
          type: string
          format: date-time
        dimensions:
          type: object

    ChartData:
      type: object
      properties:
        chart_type:
          type: string
        data:
          type: array
          items:
            type: object
        series:
          type: array
          items:
            type: string
        x_axis:
          type: object
        y_axis:
          type: object
        metadata:
          $ref: '#/components/schemas/QueryMetadata'

    ReportRequest:
      type: object
      properties:
        report_name:
          type: string
        report_type:
          type: string
          enum: [tabular, chart, dashboard, export]
        data_sources:
          type: array
          items:
            type: string
        filters:
          type: object
        fields:
          type: array
          items:
            type: string
        sort:
          type: array
          items:
            type: object
        format:
          type: string
          enum: [csv, xlsx, pdf, json]
        schedule:
          type: object
          properties:
            frequency:
              type: string
            recipients:
              type: array
              items:
                type: string

    ReportResult:
      type: object
      properties:
        report_id:
          type: string
        status:
          type: string
        data:
          type: array
        summary:
          type: object
        download_url:
          type: string
          format: uri
        generated_at:
          type: string
          format: date-time

    QueryMetadata:
      type: object
      properties:
        execution_time_ms:
          type: integer
        rows_returned:
          type: integer
        cache_hit:
          type: boolean
        query_id:
          type: string

  securitySchemes:
    bearerAuth:
      type: http
      scheme: bearer
      bearerFormat: JWT
    apiKeyAuth:
      type: apiKey
      in: header
      name: X-API-Key
```

### 9.2 API Implementation

```python
# api/analytics_api.py
"""
FastAPI implementation of Analytics API
"""

from fastapi import FastAPI, Depends, HTTPException, Query, BackgroundTasks
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from pydantic import BaseModel, Field
from typing import List, Optional, Dict, Any
from datetime import date, datetime
import pandas as pd
import json

from .auth import verify_token, get_current_user
from .cache import CacheManager
from .query_builder import ClickHouseQueryBuilder
from .models import KPIValue, ChartData, ReportRequest, ReportResult

app = FastAPI(title="Smart Dairy Analytics API", version="1.0.0")
security = HTTPBearer()
cache = CacheManager()
query_builder = ClickHouseQueryBuilder()

@app.get("/analytics/kpis", response_model=List[KPIValue])
async def get_kpis(
    kpi_names: Optional[List[str]] = Query(None),
    start_date: date = Query(...),
    end_date: date = Query(...),
    granularity: str = Query("day"),
    filters: Optional[str] = None,
    current_user: Dict = Depends(get_current_user)
):
    """
    Retrieve KPI values for the specified date range.
    """
    cache_key = f"kpis:{kpi_names}:{start_date}:{end_date}:{granularity}:{filters}"
    
    # Check cache
    cached_result = await cache.get(cache_key)
    if cached_result:
        return json.loads(cached_result)
    
    # Parse filters
    filter_dict = json.loads(filters) if filters else {}
    
    # Build and execute query
    queries = []
    for kpi_name in kpi_names or ['total_revenue', 'total_orders']:
        query = query_builder.build_kpi_query(
            kpi_name=kpi_name,
            start_date=start_date,
            end_date=end_date,
            granularity=granularity,
            filters=filter_dict
        )
        queries.append(query)
    
    # Execute queries
    results = []
    for query in queries:
        df = query_builder.execute(query)
        results.extend(df.to_dict('records'))
    
    # Cache results
    await cache.set(cache_key, json.dumps(results), ttl=300)
    
    return results

@app.get("/analytics/charts/{chart_type}", response_model=ChartData)
async def get_chart_data(
    chart_type: str,
    start_date: date = Query(...),
    end_date: date = Query(...),
    filters: Optional[str] = None,
    current_user: Dict = Depends(get_current_user)
):
    """
    Retrieve data for specific chart types.
    """
    filter_dict = json.loads(filters) if filters else {}
    
    # Build chart-specific query
    query = query_builder.build_chart_query(
        chart_type=chart_type,
        start_date=start_date,
        end_date=end_date,
        filters=filter_dict
    )
    
    df = query_builder.execute(query)
    
    return ChartData(
        chart_type=chart_type,
        data=df.to_dict('records'),
        series=df.columns.tolist(),
        metadata={
            "execution_time_ms": query.execution_time,
            "rows_returned": len(df)
        }
    )

@app.post("/analytics/reports")
async def create_report(
    request: ReportRequest,
    background_tasks: BackgroundTasks,
    current_user: Dict = Depends(get_current_user)
):
    """
    Generate a custom report asynchronously.
    """
    report_id = generate_report_id()
    
    # Start report generation in background
    background_tasks.add_task(
        generate_report_task,
        report_id=report_id,
        request=request,
        user_id=current_user['id']
    )
    
    return {
        "report_id": report_id,
        "status": "queued",
        "estimated_completion": datetime.now() + timedelta(minutes=5)
    }

@app.get("/analytics/reports/{report_id}", response_model=ReportResult)
async def get_report_status(
    report_id: str,
    current_user: Dict = Depends(get_current_user)
):
    """
    Get report status and results.
    """
    report_status = await get_report_status_from_db(report_id)
    
    if not report_status:
        raise HTTPException(status_code=404, detail="Report not found")
    
    if report_status['status'] == 'completed':
        return ReportResult(
            report_id=report_id,
            status='completed',
            download_url=report_status['download_url'],
            generated_at=report_status['completed_at']
        )
    
    return ReportResult(
        report_id=report_id,
        status=report_status['status']
    )

@app.post("/analytics/predictions")
async def get_predictions(
    model: str,
    parameters: Dict[str, Any],
    current_user: Dict = Depends(get_current_user)
):
    """
    Get predictions from ML models.
    """
    from .ml_serving import ModelServer
    
    model_server = ModelServer()
    
    try:
        predictions = model_server.predict(model, parameters)
        return {
            "predictions": predictions['values'],
            "confidence_intervals": predictions.get('intervals'),
            "model_version": predictions['model_version']
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/analytics/export")
async def export_data(
    query: str,
    format: str = "csv",
    filters: Optional[str] = None,
    background_tasks: BackgroundTasks = None,
    current_user: Dict = Depends(get_current_user)
):
    """
    Export data in specified format.
    """
    export_id = generate_export_id()
    
    background_tasks.add_task(
        export_data_task,
        export_id=export_id,
        query=query,
        format=format,
        filters=json.loads(filters) if filters else {},
        user_id=current_user['id']
    )
    
    return {
        "export_id": export_id,
        "status": "processing"
    }

@app.get("/embed/dashboard/{dashboard_id}")
async def get_embed_token(
    dashboard_id: str,
    user_attributes: Optional[str] = None,
    current_user: Dict = Depends(get_current_user)
):
    """
    Generate embed token for dashboards.
    """
    from .embed import generate_embed_token
    
    attrs = json.loads(user_attributes) if user_attributes else {}
    
    token_data = generate_embed_token(
        dashboard_id=dashboard_id,
        user_id=current_user['id'],
        user_attributes=attrs,
        expiry_hours=24
    )
    
    return {
        "embed_token": token_data['token'],
        "expires_at": token_data['expires_at'],
        "dashboard_url": f"/embed/dashboard/{dashboard_id}?token={token_data['token']}"
    }
```

---

## 10. Testing & Validation

### 10.1 Test Strategy

```yaml
# testing/test_strategy.yml
test_strategy:
  levels:
    unit:
      scope: "Individual functions and components"
      tools: [pytest, jest, unittest]
      coverage_target: 80%
      
    integration:
      scope: "Component interactions"
      tools: [pytest-integration, testcontainers]
      test_data: "synthetic"
      
    e2e:
      scope: "Complete user workflows"
      tools: [cypress, playwright]
      environments: [staging]
      
    performance:
      scope: "Load and stress testing"
      tools: [k6, locust, jmeter]
      scenarios:
        - name: "concurrent_users"
          users: [50, 100, 500]
          duration: "10m"
        - name: "dashboard_load"
          target_response_time: "3s"
        - name: "query_performance"
          target_query_time: "2s"
      
    data_quality:
      scope: "Data accuracy and completeness"
      tools: [great_expectations, dbt_tests]
      checks:
        - completeness
        - uniqueness
        - referential_integrity
        - freshness
        - distribution

test_cases:
  data_ingestion:
    - name: "CDC captures all changes"
      steps:
        - Insert record in source
        - Wait for CDC propagation
        - Verify record in target
      expected: "Record matches with metadata"
      
    - name: "Incremental load watermark"
      steps:
        - Note current watermark
        - Add new records
        - Run incremental load
        - Verify watermark updated
      expected: "Only new records loaded"
      
  transformations:
    - name: "SCD Type 2 dimension updates"
      steps:
        - Create initial dimension record
        - Update source record
        - Run transformation
        - Query dimension history
      expected: "Historical record preserved, new current record created"
      
    - name: "Fact table aggregation accuracy"
      steps:
        - Load known test data
        - Trigger aggregation
        - Compare aggregated values
      expected: "Aggregations match expected values"
      
  dashboards:
    - name: "Filter propagation"
      steps:
        - Apply date filter
        - Verify all charts update
        - Check KPI recalculation
      expected: "All components reflect filter changes"
      
    - name: "Drill-down navigation"
      steps:
        - Click on chart data point
        - Verify detail view loads
        - Check filter context preserved
      expected: "Navigation works with context"
      
  ml_models:
    - name: "Model prediction range"
      steps:
        - Send test prediction request
        - Verify response format
        - Check values within bounds
      expected: "Valid predictions returned"
      
    - name: "Model version fallback"
      steps:
        - Request with invalid version
        - Verify fallback to stable version
      expected: "Graceful degradation"
```

### 10.2 Data Quality Tests

```python
# tests/data_quality/test_data_quality.py
"""
Data Quality Tests using Great Expectations
"""

import great_expectations as gx
from great_expectations.core.batch import RuntimeBatchRequest
import pandas as pd
import pytest

class TestDataQuality:
    """Data quality validation tests."""
    
    @pytest.fixture(scope='class')
    def context(self):
        """GE context fixture."""
        return gx.get_context()
    
    def test_sales_fact_completeness(self, context):
        """Verify sales fact has no null critical fields."""
        batch_request = RuntimeBatchRequest(
            datasource_name="clickhouse_datasource",
            data_asset_name="fct_sales",
            runtime_parameters={"query": "SELECT * FROM smart_dairy_dwd.fct_sales LIMIT 10000"},
            batch_identifiers={"default_identifier_name": "default_identifier"}
        )
        
        validator = context.get_validator(
            batch_request=batch_request,
            expectation_suite_name="sales_fact_suite"
        )
        
        # Critical fields should not be null
        result = validator.expect_column_values_to_not_be_null(
            column="order_id",
            meta={"notes": "Order ID is required"}
        )
        assert result["success"], "Order ID contains null values"
        
        result = validator.expect_column_values_to_not_be_null(
            column="total_amount",
            meta={"notes": "Amount is required for revenue calculation"}
        )
        assert result["success"], "Total amount contains null values"
    
    def test_customer_dimension_uniqueness(self, context):
        """Verify customer dimension has unique keys."""
        batch_request = RuntimeBatchRequest(
            datasource_name="clickhouse_datasource",
            data_asset_name="dim_customers",
            runtime_parameters={"query": "SELECT * FROM smart_dairy_dwd.dim_customers WHERE is_current = 1"},
            batch_identifiers={"default_identifier_name": "default_identifier"}
        )
        
        validator = context.get_validator(
            batch_request=batch_request,
            expectation_suite_name="customer_dimension_suite"
        )
        
        result = validator.expect_column_values_to_be_unique(
            column="customer_id",
            meta={"notes": "Customer ID should be unique for current records"}
        )
        assert result["success"], "Duplicate customer IDs found"
    
    def test_referential_integrity(self, context):
        """Test referential integrity between fact and dimensions."""
        # Check that all foreign keys in fact table exist in dimension
        query = """
        SELECT COUNT(*) as orphan_count
        FROM smart_dairy_dwd.fct_sales f
        LEFT JOIN smart_dairy_dwd.dim_customers d 
            ON f.customer_sk = d.customer_sk AND d.is_current = 1
        WHERE d.customer_sk IS NULL
        """
        
        batch_request = RuntimeBatchRequest(
            datasource_name="clickhouse_datasource",
            data_asset_name="referential_integrity",
            runtime_parameters={"query": query},
            batch_identifiers={"default_identifier_name": "default_identifier"}
        )
        
        validator = context.get_validator(batch_request=batch_request)
        
        result = validator.expect_column_values_to_equal(
            column="orphan_count",
            value=0,
            meta={"notes": "No orphan records allowed"}
        )
        assert result["success"], "Orphan records found in fact table"
    
    def test_data_freshness(self, context):
        """Verify data is up to date."""
        query = """
        SELECT MAX(order_date) as max_date, 
               dateDiff('day', max_date, today()) as days_lag
        FROM smart_dairy_dwd.fct_sales
        """
        
        batch_request = RuntimeBatchRequest(
            datasource_name="clickhouse_datasource",
            data_asset_name="freshness_check",
            runtime_parameters={"query": query},
            batch_identifiers={"default_identifier_name": "default_identifier"}
        )
        
        validator = context.get_validator(batch_request=batch_request)
        
        result = validator.expect_column_values_to_be_between(
            column="days_lag",
            min_value=0,
            max_value=2,
            meta={"notes": "Data should be at most 2 days behind"}
        )
        assert result["success"], "Data is stale (more than 2 days old)"
    
    def test_financial_totals_balance(self, context):
        """Verify financial totals balance."""
        query = """
        SELECT 
            SUM(debit_amount) as total_debits,
            SUM(credit_amount) as total_credits,
            ABS(total_debits - total_credits) as difference
        FROM smart_dairy_dwd.fct_gl_transactions
        WHERE posting_date >= today() - 30
        """
        
        batch_request = RuntimeBatchRequest(
            datasource_name="clickhouse_datasource",
            data_asset_name="financial_balance",
            runtime_parameters={"query": query},
            batch_identifiers={"default_identifier_name": "default_identifier"}
        )
        
        validator = context.get_validator(batch_request=batch_request)
        
        result = validator.expect_column_values_to_be_between(
            column="difference",
            min_value=0,
            max_value=0.01,
            meta={"notes": "Debits must equal credits"}
        )
        assert result["success"], "Financial totals do not balance"

@pytest.mark.performance
class TestQueryPerformance:
    """Query performance tests."""
    
    def test_executive_kpi_query_performance(self, clickhouse_client):
        """Executive KPI query should complete within 2 seconds."""
        import time
        
        query = """
        SELECT 
            SUM(total_amount) as revenue,
            COUNT(DISTINCT order_id) as orders,
            AVG(gross_margin_pct) as margin
        FROM smart_dairy_dwd.fct_sales
        WHERE order_date >= today() - 30
        """
        
        start_time = time.time()
        result = clickhouse_client.execute(query)
        elapsed_time = time.time() - start_time
        
        assert elapsed_time < 2.0, f"Query took {elapsed_time:.2f}s, expected < 2s"
    
    def test_dashboard_chart_query_performance(self, clickhouse_client):
        """Dashboard chart queries should complete within 3 seconds."""
        import time
        
        query = """
        SELECT 
            order_date,
            SUM(total_amount) as revenue,
            COUNT(DISTINCT order_id) as orders
        FROM smart_dairy_dwd.fct_sales
        WHERE order_date >= today() - 90
        GROUP BY order_date
        ORDER BY order_date
        """
        
        start_time = time.time()
        result = clickhouse_client.execute(query)
        elapsed_time = time.time() - start_time
        
        assert elapsed_time < 3.0, f"Query took {elapsed_time:.2f}s, expected < 3s"
```

---

## 11. Appendices

### 11.1 SQL Query Reference

```sql
-- =====================================================
-- APPENDIX A: COMMON SQL QUERIES
-- =====================================================

-- A1. Daily Sales Summary
SELECT 
    order_date,
    COUNT(DISTINCT order_id) as total_orders,
    SUM(quantity) as total_quantity,
    SUM(gross_amount) as gross_revenue,
    SUM(discount_amount) as total_discounts,
    SUM(net_amount) as net_revenue,
    SUM(tax_amount) as total_tax,
    SUM(total_amount) as total_revenue,
    SUM(total_cost) as total_cost,
    SUM(gross_profit) as gross_profit,
    AVG(gross_margin_pct) as avg_margin_pct,
    COUNT(DISTINCT customer_sk) as unique_customers
FROM smart_dairy_dwd.fct_sales
WHERE order_date >= today() - 30
GROUP BY order_date
ORDER BY order_date DESC;

-- A2. Monthly Production by Farm
SELECT 
    toStartOfMonth(production_date) as month,
    d.farm_name,
    SUM(milk_volume_liters) as total_volume,
    AVG(fat_percentage) as avg_fat_pct,
    AVG(protein_percentage) as avg_protein_pct,
    SUM(milk_volume_liters * fat_percentage / 100) as total_fat_kg,
    COUNT(DISTINCT animal_sk) as animals_milked,
    AVG(milk_volume_liters) / COUNT(DISTINCT animal_sk) as avg_per_animal
FROM smart_dairy_dwd.fct_milk_production f
JOIN smart_dairy_dwd.dim_farms d ON f.farm_sk = d.farm_sk
WHERE production_date >= today() - 365
GROUP BY month, d.farm_name
ORDER BY month DESC, total_volume DESC;

-- A3. Customer RFM Analysis
WITH customer_stats AS (
    SELECT 
        customer_sk,
        dateDiff('day', MAX(order_date), today()) as recency,
        COUNT(DISTINCT order_id) as frequency,
        SUM(total_amount) as monetary
    FROM smart_dairy_dwd.fct_sales
    WHERE order_date >= today() - 365
    GROUP BY customer_sk
),
rfm_scores AS (
    SELECT 
        *,
        NTILE(5) OVER (ORDER BY recency DESC) as r_score,
        NTILE(5) OVER (ORDER BY frequency) as f_score,
        NTILE(5) OVER (ORDER BY monetary) as m_score
    FROM customer_stats
)
SELECT 
    customer_sk,
    recency,
    frequency,
    monetary,
    r_score,
    f_score,
    m_score,
    toString(r_score) || toString(f_score) || toString(m_score) as rfm_score,
    CASE 
        WHEN r_score >= 4 AND f_score >= 4 AND m_score >= 4 THEN 'Champions'
        WHEN r_score >= 3 AND f_score >= 3 AND m_score >= 3 THEN 'Loyal Customers'
        WHEN r_score >= 4 AND f_score <= 2 THEN 'New Customers'
        WHEN r_score <= 2 AND f_score >= 3 THEN 'At Risk'
        WHEN r_score <= 2 AND f_score <= 2 THEN 'Lost'
        ELSE 'Potential Loyalists'
    END as segment
FROM rfm_scores;

-- A4. Inventory Valuation
SELECT 
    d.product_name,
    d.category_name,
    i.warehouse_sk,
    SUM(i.quantity_on_hand) as total_units,
    AVG(i.unit_cost) as avg_unit_cost,
    SUM(i.inventory_value) as total_value,
    AVG(i.days_of_supply) as avg_days_supply,
    CASE 
        WHEN AVG(i.days_of_supply) < 7 THEN 'Critical Low'
        WHEN AVG(i.days_of_supply) < 14 THEN 'Low'
        WHEN AVG(i.days_of_supply) > 60 THEN 'Overstock'
        ELSE 'Normal'
    END as stock_status
FROM smart_dairy_dwd.fct_inventory_snapshot i
JOIN smart_dairy_dwd.dim_products d ON i.product_sk = d.product_sk
WHERE i.snapshot_date = (SELECT MAX(snapshot_date) FROM smart_dairy_dwd.fct_inventory_snapshot)
GROUP BY d.product_name, d.category_name, i.warehouse_sk
ORDER BY total_value DESC;

-- A5. Financial P&L Summary
SELECT 
    toStartOfMonth(posting_date) as month,
    account_type,
    account_category,
    SUM(debit_amount) as total_debits,
    SUM(credit_amount) as total_credits,
    SUM(net_amount) as net_amount
FROM smart_dairy_dwd.fct_gl_transactions
WHERE posting_date >= toStartOfYear(today())
GROUP BY month, account_type, account_category
ORDER BY month, account_type, account_category;

-- A6. YoY Growth Analysis
SELECT 
    toMonth(order_date) as month,
    toYear(order_date) as year,
    SUM(total_amount) as revenue,
    LAG(SUM(total_amount)) OVER (PARTITION BY toMonth(order_date) ORDER BY toYear(order_date)) as prev_year_revenue,
    ((revenue - prev_year_revenue) / prev_year_revenue) * 100 as yoy_growth_pct
FROM smart_dairy_dwd.fct_sales
WHERE order_date >= today() - INTERVAL 2 YEAR
GROUP BY month, year
ORDER BY year, month;
```

### 11.2 Configuration Reference

```yaml
# config/reference_config.yml
# Complete configuration reference for all BI components

# ClickHouse Configuration
clickhouse:
  cluster:
    shards: 3
    replicas_per_shard: 2
  memory_settings:
    max_memory_usage: 20000000000  # 20GB
    max_bytes_before_external_sort: 5000000000
    max_bytes_before_external_group_by: 5000000000
  performance:
    max_concurrent_queries: 100
    max_threads: 16
    use_uncompressed_cache: true

# Airflow Configuration
airflow:
  executor: CeleryExecutor
  workers: 4
  scheduler:
    min_file_process_interval: 30
    dag_dir_list_interval: 300
  celery:
    broker_url: redis://redis:6379/0
    result_backend: db+postgresql://airflow:password@postgres:5432/airflow
    worker_concurrency: 8

# Superset Configuration
superset:
  cache:
    type: redis
    timeout: 300
  sqlalchemy_pool_size: 10
  sqlalchemy_max_overflow: 20
  webserver_workers: 4
  
# MLflow Configuration
mlflow:
  backend_store_uri: postgresql://mlflow:password@postgres:5432/mlflow
  default_artifact_root: s3://smart-dairy-mlflow/
  tracking_server:
    host: 0.0.0.0
    port: 5000
    workers: 4

# Feature Store (Feast) Configuration
feast:
  project: smart_dairy
  provider: local
  online_store:
    type: redis
    connection_string: redis://redis:6379/1
  offline_store:
    type: clickhouse
    host: clickhouse
    port: 9000
    database: smart_dairy_feature_store

# Monitoring Configuration
monitoring:
  prometheus:
    retention: 15d
    scrape_interval: 15s
  grafana:
    admin_user: admin
    dashboards_path: /var/lib/grafana/dashboards
  alerts:
    channels:
      - name: slack
        type: slack
        webhook_url: ${SLACK_WEBHOOK_URL}
      - name: email
        type: email
        recipients: ["ops@smartdairy.com"]
```

### 11.3 Glossary

| Term | Definition |
|------|------------|
| **BI** | Business Intelligence - technologies and practices for collecting, integrating, analyzing, and presenting business information |
| **CDC** | Change Data Capture - technique for tracking changes in source databases |
| **ClickHouse** | Open-source column-oriented DBMS for online analytical processing |
| **dbt** | Data Build Tool - transformation workflow tool |
| **DWD** | Data Warehouse Detail - layer containing detailed fact and dimension tables |
| **DWS** | Data Warehouse Service - layer containing aggregated data for reporting |
| **ETL** | Extract, Transform, Load - process for moving data from source to target |
| **Fact Table** | Central table in star schema containing quantitative data for analysis |
| **Feast** | Open-source feature store for machine learning |
| **KPI** | Key Performance Indicator - measurable value demonstrating effectiveness |
| **Materialized View** | Pre-computed query result stored for performance |
| **MLflow** | Platform for managing the machine learning lifecycle |
| **ODS** | Operational Data Store - layer for raw, unprocessed data |
| **RFM** | Recency, Frequency, Monetary - customer segmentation technique |
| **SCD** | Slowly Changing Dimension - handling changing dimension attributes |
| **Superset** | Open-source data visualization and exploration platform |
| **TTL** | Time To Live - data retention policy |

---

## Document Information

**Document Version:** 1.0  
**Last Updated:** 2026-02-02  
**Author:** Smart Dairy Engineering Team  
**Reviewers:** Data Engineering Team, BI Team, Product Management  

**Change Log:**
| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-02 | Engineering Team | Initial document creation |

---

*End of Milestone 38 - Business Intelligence & Analytics Document*
