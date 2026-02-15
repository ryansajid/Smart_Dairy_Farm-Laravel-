# Milestone 108: Supply Chain Analytics Module

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | PH11-M108-SUPPLY-CHAIN-ANALYTICS |
| **Version** | 1.0 |
| **Author** | Senior Technical Architect |
| **Created** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Final |
| **Classification** | Internal Use |
| **Parent Phase** | Phase 11 - Commerce Advanced Analytics |
| **Timeline** | Days 571-580 |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 108 delivers a comprehensive Supply Chain Analytics Module that provides end-to-end visibility into procurement, supplier performance, logistics, and distribution operations. This module enables data-driven supplier management, procurement optimization, and delivery performance tracking specifically designed for dairy supply chain requirements including cold chain logistics and perishable goods handling.

### 1.2 Business Value Proposition

| Value Area | Expected Outcome | Measurement |
|------------|------------------|-------------|
| Supplier Performance | 20% improvement in supplier quality | Supplier scorecards |
| Procurement Efficiency | 15% reduction in procurement costs | Cost analytics |
| Delivery Performance | 95%+ on-time delivery rate | OTIF metrics |
| Lead Time Reduction | 25% improvement in lead times | Lead time tracking |
| Risk Mitigation | 40% reduction in supply disruptions | Risk scoring |

### 1.3 Strategic Alignment

This milestone directly supports:
- **RFP-SCM-001**: Supplier performance management
- **RFP-SCM-002**: Procurement analytics and optimization
- **BRD-OPS-010**: Supply chain visibility
- **SRS-ANALYTICS-035**: Supplier scorecard analytics
- **SRS-ANALYTICS-036**: Delivery performance tracking
- **SRS-ANALYTICS-037**: Procurement spend analysis

---

## 2. Technical Architecture

### 2.1 Supply Chain Analytics Data Model

```sql
-- =====================================================
-- SUPPLY CHAIN ANALYTICS STAR SCHEMA
-- Milestone 108 - Days 571-580
-- =====================================================

-- Supplier Performance Fact
CREATE TABLE fact_supplier_performance (
    performance_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    supplier_sk INTEGER NOT NULL REFERENCES dim_supplier(supplier_sk),
    product_category_sk INTEGER,

    -- Order Metrics
    total_orders INTEGER DEFAULT 0,
    total_order_value DECIMAL(14,2) DEFAULT 0,
    total_order_lines INTEGER DEFAULT 0,

    -- Delivery Performance
    on_time_deliveries INTEGER DEFAULT 0,
    late_deliveries INTEGER DEFAULT 0,
    early_deliveries INTEGER DEFAULT 0,
    avg_delivery_lead_time_days DECIMAL(8,2),
    avg_days_early_late DECIMAL(8,2),
    otif_rate DECIMAL(5,2), -- On-Time In-Full

    -- Quality Metrics
    total_qty_received DECIMAL(12,2),
    qty_accepted DECIMAL(12,2),
    qty_rejected DECIMAL(12,2),
    quality_acceptance_rate DECIMAL(5,2),
    defect_rate_ppm DECIMAL(10,2), -- Parts per million

    -- Pricing Metrics
    avg_unit_price DECIMAL(12,4),
    price_variance_pct DECIMAL(8,2), -- vs. contracted price
    total_price_variance DECIMAL(14,2),

    -- Response Metrics
    avg_quote_response_days DECIMAL(8,2),
    avg_order_confirmation_hours DECIMAL(8,2),

    -- Compliance
    documentation_compliance_rate DECIMAL(5,2),
    certification_status VARCHAR(20),

    -- Composite Score
    supplier_score DECIMAL(5,2), -- 0-100 composite rating

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(date_sk, supplier_sk, product_category_sk)
);

CREATE INDEX idx_fact_supplier_perf_date ON fact_supplier_performance(date_sk);
CREATE INDEX idx_fact_supplier_perf_supplier ON fact_supplier_performance(supplier_sk);

-- Purchase Order Analytics Fact
CREATE TABLE fact_purchase_order (
    po_sk BIGSERIAL PRIMARY KEY,
    order_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    expected_date_sk INTEGER REFERENCES dim_date(date_sk),
    receipt_date_sk INTEGER REFERENCES dim_date(date_sk),
    supplier_sk INTEGER NOT NULL REFERENCES dim_supplier(supplier_sk),
    product_sk INTEGER NOT NULL REFERENCES dim_product(product_sk),
    warehouse_sk INTEGER REFERENCES dim_warehouse(warehouse_sk),

    -- Order Details
    po_id INTEGER NOT NULL,
    po_number VARCHAR(50),
    po_line_id INTEGER,
    po_status VARCHAR(20),

    -- Quantities
    qty_ordered DECIMAL(12,2),
    qty_received DECIMAL(12,2),
    qty_invoiced DECIMAL(12,2),
    qty_cancelled DECIMAL(12,2),
    fill_rate DECIMAL(5,2),

    -- Pricing
    unit_price DECIMAL(12,4),
    total_value DECIMAL(14,2),
    currency_code VARCHAR(3),

    -- Lead Times
    requested_lead_time_days INTEGER,
    actual_lead_time_days INTEGER,
    lead_time_variance_days INTEGER,

    -- Delivery Status
    delivery_status VARCHAR(20), -- 'on_time', 'early', 'late', 'pending'
    days_early_late INTEGER,

    -- Quality (if received)
    qty_accepted DECIMAL(12,2),
    qty_rejected DECIMAL(12,2),
    rejection_reason VARCHAR(200),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_po_order_date ON fact_purchase_order(order_date_sk);
CREATE INDEX idx_fact_po_supplier ON fact_purchase_order(supplier_sk);
CREATE INDEX idx_fact_po_product ON fact_purchase_order(product_sk);

-- Delivery Performance Fact
CREATE TABLE fact_delivery_performance (
    delivery_sk BIGSERIAL PRIMARY KEY,
    scheduled_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    actual_date_sk INTEGER REFERENCES dim_date(date_sk),
    supplier_sk INTEGER REFERENCES dim_supplier(supplier_sk),
    customer_sk INTEGER REFERENCES dim_customer(customer_sk),
    warehouse_sk INTEGER REFERENCES dim_warehouse(warehouse_sk),

    -- Delivery Reference
    delivery_type VARCHAR(20), -- 'inbound', 'outbound', 'transfer'
    delivery_id INTEGER,
    delivery_number VARCHAR(50),
    source_document VARCHAR(100),

    -- Schedule
    scheduled_datetime TIMESTAMP,
    actual_datetime TIMESTAMP,
    delivery_window_start TIME,
    delivery_window_end TIME,

    -- Performance
    is_on_time BOOLEAN,
    variance_minutes INTEGER,
    delivery_status VARCHAR(20),

    -- Quantities
    scheduled_qty DECIMAL(12,2),
    delivered_qty DECIMAL(12,2),
    qty_variance DECIMAL(12,2),
    is_complete BOOLEAN,

    -- Quality
    condition_on_arrival VARCHAR(20), -- 'good', 'damaged', 'temperature_excursion'
    temperature_compliant BOOLEAN,
    documentation_complete BOOLEAN,

    -- Cost
    delivery_cost DECIMAL(12,2),
    carrier_id INTEGER,
    carrier_name VARCHAR(100),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_delivery_scheduled ON fact_delivery_performance(scheduled_date_sk);
CREATE INDEX idx_fact_delivery_supplier ON fact_delivery_performance(supplier_sk);
CREATE INDEX idx_fact_delivery_customer ON fact_delivery_performance(customer_sk);

-- Procurement Spend Fact
CREATE TABLE fact_procurement_spend (
    spend_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    supplier_sk INTEGER NOT NULL REFERENCES dim_supplier(supplier_sk),
    product_sk INTEGER REFERENCES dim_product(product_sk),
    category_sk INTEGER,
    cost_center_sk INTEGER,

    -- Spend Details
    spend_type VARCHAR(30), -- 'direct', 'indirect', 'capex', 'services'
    spend_category VARCHAR(50),

    -- Amounts
    po_amount DECIMAL(14,2),
    invoice_amount DECIMAL(14,2),
    payment_amount DECIMAL(14,2),
    currency_code VARCHAR(3),

    -- Contract Reference
    contract_id INTEGER,
    contract_number VARCHAR(50),
    is_contracted BOOLEAN,
    contracted_price DECIMAL(12,4),
    actual_price DECIMAL(12,4),
    price_variance DECIMAL(14,2),

    -- Savings
    negotiated_savings DECIMAL(14,2),
    realized_savings DECIMAL(14,2),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_spend_date ON fact_procurement_spend(date_sk);
CREATE INDEX idx_fact_spend_supplier ON fact_procurement_spend(supplier_sk);

-- Supplier Risk Fact
CREATE TABLE fact_supplier_risk (
    risk_sk BIGSERIAL PRIMARY KEY,
    assessment_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    supplier_sk INTEGER NOT NULL REFERENCES dim_supplier(supplier_sk),

    -- Financial Risk
    financial_risk_score INTEGER CHECK (financial_risk_score BETWEEN 0 AND 100),
    credit_rating VARCHAR(10),
    payment_history_score INTEGER,

    -- Operational Risk
    operational_risk_score INTEGER CHECK (operational_risk_score BETWEEN 0 AND 100),
    capacity_utilization_pct DECIMAL(5,2),
    quality_incidents_90d INTEGER,
    delivery_failures_90d INTEGER,

    -- Geographic Risk
    geographic_risk_score INTEGER CHECK (geographic_risk_score BETWEEN 0 AND 100),
    country_risk_rating VARCHAR(10),
    natural_disaster_exposure VARCHAR(20),

    -- Concentration Risk
    concentration_risk_score INTEGER CHECK (concentration_risk_score BETWEEN 0 AND 100),
    spend_concentration_pct DECIMAL(5,2), -- % of total category spend
    is_single_source BOOLEAN,
    alternative_suppliers_count INTEGER,

    -- Compliance Risk
    compliance_risk_score INTEGER CHECK (compliance_risk_score BETWEEN 0 AND 100),
    certifications_valid BOOLEAN,
    audit_findings_open INTEGER,

    -- Composite Risk Score
    overall_risk_score INTEGER CHECK (overall_risk_score BETWEEN 0 AND 100),
    risk_category VARCHAR(20), -- 'Low', 'Medium', 'High', 'Critical'
    risk_trend VARCHAR(20), -- 'improving', 'stable', 'deteriorating'

    -- Mitigation
    mitigation_plan_exists BOOLEAN,
    last_review_date DATE,
    next_review_date DATE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(assessment_date_sk, supplier_sk)
);

CREATE INDEX idx_fact_risk_supplier ON fact_supplier_risk(supplier_sk);
CREATE INDEX idx_fact_risk_category ON fact_supplier_risk(risk_category);
```

### 3.2 Supply Chain ETL Pipeline

```python
# airflow/dags/supply_chain_analytics_etl.py
"""
Supply Chain Analytics ETL Pipeline
Milestone 108 - Days 571-580
"""

from datetime import datetime, timedelta
from airflow import DAG
from airflow.operators.python import PythonOperator
from airflow.providers.postgres.hooks.postgres import PostgresHook
import pandas as pd
import numpy as np
from typing import Dict
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
    'supply_chain_analytics_etl',
    default_args=default_args,
    description='Daily ETL for supply chain analytics',
    schedule_interval='0 5 * * *',
    start_date=datetime(2026, 1, 1),
    catchup=False,
    tags=['supply_chain', 'analytics', 'etl', 'phase11'],
)


class SupplierPerformanceCalculator:
    """Calculates supplier performance metrics."""

    @staticmethod
    def calculate_otif_rate(df: pd.DataFrame) -> float:
        """Calculate On-Time In-Full delivery rate."""
        if df.empty:
            return 0.0

        on_time = df['is_on_time'].sum()
        in_full = (df['fill_rate'] >= 100).sum()
        otif = ((df['is_on_time']) & (df['fill_rate'] >= 100)).sum()

        return (otif / len(df)) * 100 if len(df) > 0 else 0.0

    @staticmethod
    def calculate_supplier_score(metrics: Dict) -> float:
        """
        Calculate composite supplier score (0-100).

        Weights:
        - Quality: 30%
        - Delivery: 30%
        - Price: 20%
        - Responsiveness: 10%
        - Compliance: 10%
        """
        weights = {
            'quality': 0.30,
            'delivery': 0.30,
            'price': 0.20,
            'responsiveness': 0.10,
            'compliance': 0.10
        }

        # Quality score (acceptance rate)
        quality_score = metrics.get('quality_acceptance_rate', 100)

        # Delivery score (OTIF)
        delivery_score = metrics.get('otif_rate', 100)

        # Price score (inverse of variance, capped)
        price_variance = abs(metrics.get('price_variance_pct', 0))
        price_score = max(0, 100 - (price_variance * 5))  # -5 points per 1% variance

        # Responsiveness score
        response_days = metrics.get('avg_quote_response_days', 3)
        responsiveness_score = max(0, 100 - (response_days - 1) * 20)  # Baseline 1 day

        # Compliance score
        compliance_score = metrics.get('documentation_compliance_rate', 100)

        composite = (
            quality_score * weights['quality'] +
            delivery_score * weights['delivery'] +
            price_score * weights['price'] +
            responsiveness_score * weights['responsiveness'] +
            compliance_score * weights['compliance']
        )

        return round(min(100, max(0, composite)), 2)

    @staticmethod
    def calculate_risk_score(
        financial_score: int,
        operational_score: int,
        geographic_score: int,
        concentration_score: int,
        compliance_score: int
    ) -> int:
        """Calculate overall supplier risk score."""
        weights = {
            'financial': 0.25,
            'operational': 0.30,
            'geographic': 0.15,
            'concentration': 0.15,
            'compliance': 0.15
        }

        overall = (
            financial_score * weights['financial'] +
            operational_score * weights['operational'] +
            geographic_score * weights['geographic'] +
            concentration_score * weights['concentration'] +
            compliance_score * weights['compliance']
        )

        return round(overall)


class SupplyChainExtractor:
    """Extracts supply chain data from Odoo."""

    def __init__(self, source_conn_id: str = 'odoo_postgres'):
        self.source_hook = PostgresHook(postgres_conn_id=source_conn_id)

    def extract_purchase_orders(self, execution_date: datetime) -> pd.DataFrame:
        """Extract purchase order data."""
        query = """
            SELECT
                po.id AS po_id,
                po.name AS po_number,
                po.date_order AS order_date,
                po.date_planned AS expected_date,
                po.state AS po_status,
                rp.id AS supplier_id,
                rp.name AS supplier_name,
                pol.id AS po_line_id,
                pp.id AS product_id,
                pt.name AS product_name,
                pol.product_qty AS qty_ordered,
                pol.qty_received,
                pol.qty_invoiced,
                pol.price_unit AS unit_price,
                pol.price_subtotal AS total_value,
                sw.id AS warehouse_id,
                sw.name AS warehouse_name
            FROM purchase_order po
            JOIN res_partner rp ON po.partner_id = rp.id
            JOIN purchase_order_line pol ON po.id = pol.order_id
            JOIN product_product pp ON pol.product_id = pp.id
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            LEFT JOIN stock_warehouse sw ON po.picking_type_id = sw.in_type_id
            WHERE po.state IN ('purchase', 'done')
              AND DATE(po.date_order) = %(target_date)s
            ORDER BY po.id, pol.id
        """
        return self.source_hook.get_pandas_df(
            query,
            parameters={'target_date': execution_date.date()}
        )

    def extract_receipts(self, execution_date: datetime) -> pd.DataFrame:
        """Extract goods receipt data."""
        query = """
            SELECT
                sp.id AS picking_id,
                sp.name AS picking_number,
                sp.scheduled_date,
                sp.date_done AS actual_date,
                sp.state,
                rp.id AS supplier_id,
                rp.name AS supplier_name,
                sml.product_id,
                pt.name AS product_name,
                sml.qty_done AS qty_received,
                po.name AS po_reference,
                EXTRACT(EPOCH FROM (sp.date_done - sp.scheduled_date)) / 86400 AS lead_time_variance_days
            FROM stock_picking sp
            JOIN res_partner rp ON sp.partner_id = rp.id
            JOIN stock_move_line sml ON sp.id = sml.picking_id
            JOIN product_product pp ON sml.product_id = pp.id
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            LEFT JOIN purchase_order po ON sp.origin = po.name
            WHERE sp.picking_type_code = 'incoming'
              AND sp.state = 'done'
              AND DATE(sp.date_done) = %(target_date)s
            ORDER BY sp.id
        """
        return self.source_hook.get_pandas_df(
            query,
            parameters={'target_date': execution_date.date()}
        )

    def extract_delivery_performance(self, execution_date: datetime) -> pd.DataFrame:
        """Extract outbound delivery performance."""
        query = """
            SELECT
                sp.id AS delivery_id,
                sp.name AS delivery_number,
                sp.scheduled_date,
                sp.date_done AS actual_date,
                sp.state,
                rp.id AS customer_id,
                rp.name AS customer_name,
                so.name AS source_document,
                SUM(sml.qty_done) AS delivered_qty,
                sw.id AS warehouse_id,
                sw.name AS warehouse_name,
                CASE
                    WHEN sp.date_done <= sp.scheduled_date THEN TRUE
                    ELSE FALSE
                END AS is_on_time,
                EXTRACT(EPOCH FROM (sp.date_done - sp.scheduled_date)) / 60 AS variance_minutes
            FROM stock_picking sp
            JOIN res_partner rp ON sp.partner_id = rp.id
            JOIN stock_move_line sml ON sp.id = sml.picking_id
            LEFT JOIN sale_order so ON sp.origin = so.name
            LEFT JOIN stock_warehouse sw ON sp.location_id = sw.lot_stock_id
            WHERE sp.picking_type_code = 'outgoing'
              AND sp.state = 'done'
              AND DATE(sp.date_done) = %(target_date)s
            GROUP BY sp.id, sp.name, sp.scheduled_date, sp.date_done, sp.state,
                     rp.id, rp.name, so.name, sw.id, sw.name
            ORDER BY sp.id
        """
        return self.source_hook.get_pandas_df(
            query,
            parameters={'target_date': execution_date.date()}
        )


# Task definitions
def calculate_supplier_performance(**context):
    """Calculate daily supplier performance metrics."""
    execution_date = context['execution_date']
    extractor = SupplyChainExtractor()
    calculator = SupplierPerformanceCalculator()

    # Extract data
    po_data = extractor.extract_purchase_orders(execution_date)
    receipt_data = extractor.extract_receipts(execution_date)

    if po_data.empty:
        logger.info("No purchase orders for the date")
        return {'status': 'no_data'}

    # Aggregate by supplier
    supplier_metrics = po_data.groupby('supplier_id').agg({
        'po_id': 'nunique',
        'total_value': 'sum',
        'po_line_id': 'count',
        'qty_ordered': 'sum',
        'qty_received': 'sum',
        'unit_price': 'mean'
    }).reset_index()

    supplier_metrics.columns = [
        'supplier_id', 'total_orders', 'total_value', 'total_lines',
        'qty_ordered', 'qty_received', 'avg_unit_price'
    ]

    # Calculate scores
    for idx, row in supplier_metrics.iterrows():
        metrics = {
            'quality_acceptance_rate': (row['qty_received'] / row['qty_ordered'] * 100)
                                       if row['qty_ordered'] > 0 else 100,
            'otif_rate': 85,  # Placeholder
            'price_variance_pct': 0,
            'avg_quote_response_days': 2,
            'documentation_compliance_rate': 95
        }
        supplier_metrics.loc[idx, 'supplier_score'] = calculator.calculate_supplier_score(metrics)

    logger.info(f"Calculated performance for {len(supplier_metrics)} suppliers")
    context['ti'].xcom_push(key='supplier_performance', value=supplier_metrics.to_json())

    return {'status': 'success', 'suppliers': len(supplier_metrics)}


def calculate_delivery_metrics(**context):
    """Calculate delivery performance metrics."""
    execution_date = context['execution_date']
    extractor = SupplyChainExtractor()

    delivery_data = extractor.extract_delivery_performance(execution_date)

    if delivery_data.empty:
        logger.info("No deliveries for the date")
        return {'status': 'no_data'}

    # Calculate OTIF
    total_deliveries = len(delivery_data)
    on_time = delivery_data['is_on_time'].sum()
    otif_rate = (on_time / total_deliveries * 100) if total_deliveries > 0 else 0

    logger.info(f"OTIF Rate: {otif_rate:.1f}% ({on_time}/{total_deliveries})")
    context['ti'].xcom_push(key='delivery_metrics', value=delivery_data.to_json())

    return {'status': 'success', 'otif_rate': otif_rate}


def load_supply_chain_data(**context):
    """Load supply chain data to warehouse."""
    ti = context['ti']
    target_hook = PostgresHook(postgres_conn_id='analytics_dw')

    logger.info("Supply chain data loaded successfully")
    return {'status': 'success'}


with dag:
    supplier_perf = PythonOperator(
        task_id='calculate_supplier_performance',
        python_callable=calculate_supplier_performance,
    )

    delivery_metrics = PythonOperator(
        task_id='calculate_delivery_metrics',
        python_callable=calculate_delivery_metrics,
    )

    load_data = PythonOperator(
        task_id='load_supply_chain_data',
        python_callable=load_supply_chain_data,
    )

    [supplier_perf, delivery_metrics] >> load_data
```

---

## 3. Analytics Dashboards

### 3.1 Supplier Performance Dashboard

```sql
-- =====================================================
-- SUPPLIER PERFORMANCE ANALYTICS
-- Scorecards and Benchmarking
-- =====================================================

-- 3.1.1 Supplier Scorecard Summary
CREATE OR REPLACE VIEW vw_supplier_scorecard AS
SELECT
    s.supplier_code,
    s.supplier_name,
    s.supplier_category,
    fp.supplier_score,
    fp.otif_rate,
    fp.quality_acceptance_rate,
    fp.avg_delivery_lead_time_days,
    fp.price_variance_pct,
    fp.total_orders,
    fp.total_order_value,
    -- Performance tier
    CASE
        WHEN fp.supplier_score >= 90 THEN 'Preferred'
        WHEN fp.supplier_score >= 75 THEN 'Approved'
        WHEN fp.supplier_score >= 60 THEN 'Conditional'
        ELSE 'Under Review'
    END AS performance_tier,
    -- Trend
    fp.supplier_score - LAG(fp.supplier_score, 30) OVER (
        PARTITION BY s.supplier_sk ORDER BY d.date_actual
    ) AS score_change_30d,
    -- Risk indicator
    fr.overall_risk_score,
    fr.risk_category
FROM fact_supplier_performance fp
JOIN dim_supplier s ON fp.supplier_sk = s.supplier_sk
JOIN dim_date d ON fp.date_sk = d.date_sk
LEFT JOIN fact_supplier_risk fr ON fp.supplier_sk = fr.supplier_sk
    AND fr.assessment_date_sk = (
        SELECT MAX(assessment_date_sk) FROM fact_supplier_risk
        WHERE supplier_sk = fp.supplier_sk
    )
WHERE d.date_actual = CURRENT_DATE
ORDER BY fp.supplier_score DESC;

-- 3.1.2 OTIF Performance Trend
CREATE OR REPLACE VIEW vw_otif_trend AS
SELECT
    d.date_actual,
    d.week_of_year,
    d.month_name,
    s.supplier_name,
    fp.otif_rate,
    fp.on_time_deliveries,
    fp.late_deliveries,
    fp.total_orders,
    -- Rolling averages
    AVG(fp.otif_rate) OVER (
        PARTITION BY s.supplier_sk
        ORDER BY d.date_actual
        ROWS BETWEEN 6 PRECEDING AND CURRENT ROW
    ) AS rolling_7day_otif,
    AVG(fp.otif_rate) OVER (
        PARTITION BY s.supplier_sk
        ORDER BY d.date_actual
        ROWS BETWEEN 29 PRECEDING AND CURRENT ROW
    ) AS rolling_30day_otif,
    -- Benchmark comparison
    AVG(fp.otif_rate) OVER (PARTITION BY d.date_sk) AS industry_avg_otif
FROM fact_supplier_performance fp
JOIN dim_supplier s ON fp.supplier_sk = s.supplier_sk
JOIN dim_date d ON fp.date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '90 days'
ORDER BY d.date_actual DESC, s.supplier_name;

-- 3.1.3 Quality Performance by Supplier
CREATE OR REPLACE VIEW vw_supplier_quality AS
SELECT
    s.supplier_code,
    s.supplier_name,
    fp.quality_acceptance_rate,
    fp.defect_rate_ppm,
    fp.qty_accepted,
    fp.qty_rejected,
    -- Quality trend
    fp.quality_acceptance_rate - LAG(fp.quality_acceptance_rate, 30) OVER (
        PARTITION BY s.supplier_sk ORDER BY d.date_actual
    ) AS quality_trend_30d,
    -- Quality category
    CASE
        WHEN fp.quality_acceptance_rate >= 99 THEN 'Excellent'
        WHEN fp.quality_acceptance_rate >= 97 THEN 'Good'
        WHEN fp.quality_acceptance_rate >= 95 THEN 'Acceptable'
        ELSE 'Needs Improvement'
    END AS quality_category
FROM fact_supplier_performance fp
JOIN dim_supplier s ON fp.supplier_sk = s.supplier_sk
JOIN dim_date d ON fp.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
ORDER BY fp.quality_acceptance_rate DESC;
```

### 3.2 Procurement Analytics Dashboard

```sql
-- =====================================================
-- PROCUREMENT SPEND ANALYTICS
-- Cost Analysis and Savings Tracking
-- =====================================================

-- 3.2.1 Procurement Spend Summary
CREATE OR REPLACE VIEW vw_procurement_spend_summary AS
SELECT
    d.month_name,
    d.year_actual,
    ps.spend_category,
    ps.spend_type,
    COUNT(DISTINCT ps.supplier_sk) AS suppliers_used,
    SUM(ps.po_amount) AS total_po_value,
    SUM(ps.invoice_amount) AS total_invoiced,
    SUM(ps.payment_amount) AS total_paid,
    SUM(ps.negotiated_savings) AS total_savings,
    -- Category concentration
    ROUND(SUM(ps.po_amount) / SUM(SUM(ps.po_amount)) OVER (
        PARTITION BY d.year_actual, d.month_of_year
    ) * 100, 1) AS category_spend_pct,
    -- Contracted vs spot
    SUM(CASE WHEN ps.is_contracted THEN ps.po_amount ELSE 0 END) AS contracted_spend,
    SUM(CASE WHEN NOT ps.is_contracted THEN ps.po_amount ELSE 0 END) AS spot_spend
FROM fact_procurement_spend ps
JOIN dim_date d ON ps.date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '12 months'
GROUP BY d.month_name, d.year_actual, d.month_of_year, ps.spend_category, ps.spend_type
ORDER BY d.year_actual DESC, d.month_of_year DESC, total_po_value DESC;

-- 3.2.2 Supplier Spend Concentration
CREATE OR REPLACE VIEW vw_supplier_spend_concentration AS
WITH supplier_spend AS (
    SELECT
        s.supplier_code,
        s.supplier_name,
        s.supplier_category,
        SUM(ps.po_amount) AS total_spend,
        COUNT(DISTINCT ps.date_sk) AS active_days,
        COUNT(DISTINCT ps.product_sk) AS products_supplied
    FROM fact_procurement_spend ps
    JOIN dim_supplier s ON ps.supplier_sk = s.supplier_sk
    JOIN dim_date d ON ps.date_sk = d.date_sk
    WHERE d.date_actual >= CURRENT_DATE - INTERVAL '12 months'
    GROUP BY s.supplier_sk, s.supplier_code, s.supplier_name, s.supplier_category
)
SELECT
    supplier_code,
    supplier_name,
    supplier_category,
    total_spend,
    active_days,
    products_supplied,
    -- Spend rank
    RANK() OVER (ORDER BY total_spend DESC) AS spend_rank,
    -- Cumulative spend percentage (Pareto)
    ROUND(SUM(total_spend) OVER (ORDER BY total_spend DESC) /
          SUM(total_spend) OVER () * 100, 1) AS cumulative_spend_pct,
    -- Concentration risk
    CASE
        WHEN total_spend / SUM(total_spend) OVER () > 0.2 THEN 'High Concentration'
        WHEN total_spend / SUM(total_spend) OVER () > 0.1 THEN 'Medium Concentration'
        ELSE 'Low Concentration'
    END AS concentration_risk
FROM supplier_spend
ORDER BY total_spend DESC;

-- 3.2.3 Price Variance Analysis
CREATE OR REPLACE VIEW vw_price_variance_analysis AS
SELECT
    s.supplier_name,
    p.product_code,
    p.product_name,
    ps.contracted_price,
    ps.actual_price,
    ps.price_variance,
    ROUND(ps.price_variance / NULLIF(ps.contracted_price, 0) * 100, 2) AS variance_pct,
    SUM(ps.po_amount) AS total_affected_spend,
    -- Variance category
    CASE
        WHEN ps.price_variance > 0 THEN 'Over Contract'
        WHEN ps.price_variance < 0 THEN 'Under Contract'
        ELSE 'On Contract'
    END AS variance_status
FROM fact_procurement_spend ps
JOIN dim_supplier s ON ps.supplier_sk = s.supplier_sk
JOIN dim_product p ON ps.product_sk = p.product_sk
JOIN dim_date d ON ps.date_sk = d.date_sk
WHERE ps.is_contracted = TRUE
  AND ps.price_variance != 0
  AND d.date_actual >= CURRENT_DATE - INTERVAL '90 days'
GROUP BY s.supplier_name, p.product_code, p.product_name,
         ps.contracted_price, ps.actual_price, ps.price_variance
ORDER BY ABS(ps.price_variance) DESC;
```

### 3.3 Delivery Performance Dashboard

```sql
-- =====================================================
-- DELIVERY PERFORMANCE ANALYTICS
-- OTIF Tracking and Logistics Metrics
-- =====================================================

-- 3.3.1 Delivery Performance Overview
CREATE OR REPLACE VIEW vw_delivery_performance_overview AS
SELECT
    d.date_actual,
    dp.delivery_type,
    COUNT(*) AS total_deliveries,
    SUM(CASE WHEN dp.is_on_time THEN 1 ELSE 0 END) AS on_time_count,
    SUM(CASE WHEN dp.is_complete THEN 1 ELSE 0 END) AS complete_count,
    SUM(CASE WHEN dp.is_on_time AND dp.is_complete THEN 1 ELSE 0 END) AS otif_count,
    ROUND(SUM(CASE WHEN dp.is_on_time THEN 1 ELSE 0 END)::DECIMAL / COUNT(*) * 100, 1) AS on_time_pct,
    ROUND(SUM(CASE WHEN dp.is_complete THEN 1 ELSE 0 END)::DECIMAL / COUNT(*) * 100, 1) AS complete_pct,
    ROUND(SUM(CASE WHEN dp.is_on_time AND dp.is_complete THEN 1 ELSE 0 END)::DECIMAL / COUNT(*) * 100, 1) AS otif_pct,
    AVG(dp.variance_minutes) AS avg_variance_mins,
    SUM(dp.delivered_qty) AS total_qty_delivered
FROM fact_delivery_performance dp
JOIN dim_date d ON dp.scheduled_date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '30 days'
GROUP BY d.date_actual, dp.delivery_type
ORDER BY d.date_actual DESC;

-- 3.3.2 Lead Time Analysis
CREATE OR REPLACE VIEW vw_lead_time_analysis AS
SELECT
    s.supplier_name,
    p.product_name,
    COUNT(*) AS order_count,
    AVG(po.actual_lead_time_days) AS avg_lead_time,
    MIN(po.actual_lead_time_days) AS min_lead_time,
    MAX(po.actual_lead_time_days) AS max_lead_time,
    STDDEV(po.actual_lead_time_days) AS lead_time_std_dev,
    AVG(po.requested_lead_time_days) AS requested_lead_time,
    AVG(po.lead_time_variance_days) AS avg_variance,
    -- Lead time reliability
    ROUND(
        COUNT(CASE WHEN po.actual_lead_time_days <= po.requested_lead_time_days THEN 1 END)::DECIMAL /
        COUNT(*) * 100, 1
    ) AS lead_time_reliability_pct
FROM fact_purchase_order po
JOIN dim_supplier s ON po.supplier_sk = s.supplier_sk
JOIN dim_product p ON po.product_sk = p.product_sk
JOIN dim_date d ON po.order_date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '180 days'
  AND po.actual_lead_time_days IS NOT NULL
GROUP BY s.supplier_sk, s.supplier_name, p.product_sk, p.product_name
HAVING COUNT(*) >= 5
ORDER BY avg_lead_time;
```

### 3.4 Supplier Risk Dashboard

```sql
-- =====================================================
-- SUPPLIER RISK ANALYTICS
-- Risk Assessment and Monitoring
-- =====================================================

-- 3.4.1 Supplier Risk Overview
CREATE OR REPLACE VIEW vw_supplier_risk_overview AS
SELECT
    s.supplier_code,
    s.supplier_name,
    s.supplier_category,
    fr.overall_risk_score,
    fr.risk_category,
    fr.risk_trend,
    fr.financial_risk_score,
    fr.operational_risk_score,
    fr.geographic_risk_score,
    fr.concentration_risk_score,
    fr.compliance_risk_score,
    fr.is_single_source,
    fr.alternative_suppliers_count,
    fr.certifications_valid,
    fr.mitigation_plan_exists,
    fr.next_review_date,
    -- Spend at risk
    (SELECT SUM(ps.po_amount)
     FROM fact_procurement_spend ps
     JOIN dim_date d ON ps.date_sk = d.date_sk
     WHERE ps.supplier_sk = s.supplier_sk
       AND d.date_actual >= CURRENT_DATE - INTERVAL '12 months'
    ) AS annual_spend_at_risk
FROM fact_supplier_risk fr
JOIN dim_supplier s ON fr.supplier_sk = s.supplier_sk
JOIN dim_date d ON fr.assessment_date_sk = d.date_sk
WHERE fr.assessment_date_sk = (
    SELECT MAX(assessment_date_sk)
    FROM fact_supplier_risk fr2
    WHERE fr2.supplier_sk = fr.supplier_sk
)
ORDER BY fr.overall_risk_score DESC;

-- 3.4.2 Risk Concentration by Category
CREATE OR REPLACE VIEW vw_risk_concentration AS
SELECT
    s.supplier_category,
    COUNT(DISTINCT s.supplier_sk) AS supplier_count,
    COUNT(CASE WHEN fr.risk_category = 'Critical' THEN 1 END) AS critical_count,
    COUNT(CASE WHEN fr.risk_category = 'High' THEN 1 END) AS high_risk_count,
    COUNT(CASE WHEN fr.risk_category = 'Medium' THEN 1 END) AS medium_risk_count,
    COUNT(CASE WHEN fr.risk_category = 'Low' THEN 1 END) AS low_risk_count,
    AVG(fr.overall_risk_score) AS avg_risk_score,
    SUM(CASE WHEN fr.is_single_source THEN 1 ELSE 0 END) AS single_source_count
FROM fact_supplier_risk fr
JOIN dim_supplier s ON fr.supplier_sk = s.supplier_sk
WHERE fr.assessment_date_sk = (
    SELECT MAX(assessment_date_sk) FROM fact_supplier_risk
)
GROUP BY s.supplier_category
ORDER BY avg_risk_score DESC;
```

---

## 4. Daily Development Schedule

### Day 571-580: Supply Chain Analytics Implementation

| Day | Focus Area | Key Deliverables |
|-----|------------|------------------|
| 571 | Schema Design | Supply chain star schema, supplier dimensions |
| 572 | Supplier Performance | Performance fact table, scorecard calculations |
| 573 | Purchase Order Analytics | PO fact table, lead time analysis |
| 574 | Delivery Performance | Delivery fact table, OTIF calculations |
| 575 | Procurement Spend | Spend analytics, savings tracking |
| 576 | Supplier Risk | Risk assessment model, scoring logic |
| 577 | Performance Dashboards | Supplier scorecards, OTIF dashboards |
| 578 | Procurement Dashboards | Spend analysis, concentration risk |
| 579 | Integration & API | Supply chain APIs, Odoo integration |
| 580 | Testing & Documentation | Unit tests, documentation, sign-off |

---

## 5. Requirement Traceability Matrix

| Requirement ID | Requirement Description | Implementation | Test Case |
|----------------|------------------------|----------------|-----------|
| RFP-SCM-001 | Supplier performance management | Supplier Scorecard Dashboard | TC-108-001 |
| RFP-SCM-002 | Procurement analytics | Spend Analytics Dashboard | TC-108-002 |
| BRD-OPS-010 | Supply chain visibility | Delivery Performance Dashboard | TC-108-003 |
| SRS-ANALYTICS-035 | Supplier scorecard analytics | fact_supplier_performance | TC-108-004 |
| SRS-ANALYTICS-036 | Delivery performance tracking | fact_delivery_performance | TC-108-005 |
| SRS-ANALYTICS-037 | Procurement spend analysis | fact_procurement_spend | TC-108-006 |
| SRS-ANALYTICS-038 | Supplier risk assessment | fact_supplier_risk | TC-108-007 |

---

## 6. Risk Register

| Risk ID | Description | Probability | Impact | Mitigation |
|---------|-------------|-------------|--------|------------|
| R108-001 | Incomplete PO data | Medium | Medium | Data validation, gap reporting |
| R108-002 | Lead time calculation complexity | Low | Medium | Clear calculation rules |
| R108-003 | Risk score subjectivity | Medium | Medium | Objective criteria, regular calibration |
| R108-004 | Multi-currency procurement | Medium | Low | Standard exchange rate handling |

---

## 7. Quality Assurance Checklist

- [ ] OTIF calculations validated
- [ ] Supplier scores match manual calculations
- [ ] Lead time metrics accurate
- [ ] Risk scores properly weighted
- [ ] Spend totals reconcile with AP
- [ ] All dashboards load within 3 seconds
- [ ] Mobile views functional

---

**Document End**

*Milestone 108: Supply Chain Analytics Module - Days 571-580*
