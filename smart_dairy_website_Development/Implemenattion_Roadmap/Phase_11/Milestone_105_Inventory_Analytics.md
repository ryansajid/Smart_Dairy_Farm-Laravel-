# Milestone 105: Inventory Analytics Module

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | PH11-M105-INVENTORY-ANALYTICS |
| **Version** | 1.0 |
| **Author** | Senior Technical Architect |
| **Created** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Final |
| **Classification** | Internal Use |
| **Parent Phase** | Phase 11 - Commerce Advanced Analytics |
| **Timeline** | Days 541-550 |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 105 delivers a comprehensive Inventory Analytics Module designed specifically for dairy operations. This module provides real-time visibility into stock levels, product freshness tracking, inventory turnover optimization, and demand-driven replenishment analytics. Given the perishable nature of dairy products, this module emphasizes shelf-life management, cold chain integrity, and waste minimization.

### 1.2 Business Value Proposition

| Value Area | Expected Outcome | Measurement |
|------------|------------------|-------------|
| Waste Reduction | 30-40% decrease in expired product losses | Shrinkage tracking |
| Stock Optimization | 20% reduction in carrying costs | Inventory turnover ratio |
| Service Level | 99%+ product availability | Stockout frequency |
| Freshness Compliance | 100% FEFO adherence | Expiration compliance rate |
| Working Capital | 15% reduction in inventory investment | Days inventory outstanding |

### 1.3 Strategic Alignment

This milestone directly supports:
- **RFP-INV-001**: Real-time inventory visibility across locations
- **RFP-INV-002**: Perishable goods lifecycle management
- **BRD-OPS-007**: Inventory optimization and waste reduction
- **SRS-ANALYTICS-020**: Inventory turnover and ABC analysis
- **SRS-ANALYTICS-021**: Demand forecasting for replenishment

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

| Category | Deliverable | Priority |
|----------|-------------|----------|
| Stock Monitoring | Real-time stock level dashboards | P0 |
| Stock Monitoring | Multi-location inventory visibility | P0 |
| Stock Monitoring | Stock aging analysis | P0 |
| Turnover Analytics | Inventory turnover ratio calculations | P0 |
| Turnover Analytics | ABC/XYZ classification | P0 |
| Turnover Analytics | Dead stock identification | P1 |
| Freshness Management | Expiration date tracking | P0 |
| Freshness Management | FEFO compliance monitoring | P0 |
| Freshness Management | Shelf-life utilization analysis | P1 |
| Replenishment | Reorder point analytics | P0 |
| Replenishment | Safety stock optimization | P0 |
| Replenishment | Demand forecasting integration | P1 |
| Waste Analytics | Shrinkage tracking and analysis | P0 |
| Waste Analytics | Spoilage root cause analysis | P1 |
| Waste Analytics | Write-off trend monitoring | P1 |
| Cold Chain | Temperature excursion monitoring | P1 |
| Cold Chain | Cold chain compliance reports | P1 |

### 2.2 Out-of-Scope Items

- Warehouse management system (WMS) implementation
- Barcode/RFID hardware procurement
- Physical inventory count automation
- Third-party logistics integration
- Automated picking/packing systems

### 2.3 Assumptions and Constraints

**Assumptions:**
- Odoo inventory module is configured with lot/serial tracking
- Expiration dates are captured at goods receipt
- Multiple warehouse locations are defined in Odoo
- IoT temperature sensors provide data via existing integration

**Constraints:**
- Must support SKUs with varying shelf lives (1 day to 6 months)
- Real-time updates required for perishable items
- Historical data retention: 3 years for compliance
- Must integrate with existing Odoo inventory operations

---

## 3. Technical Architecture

### 3.1 Inventory Analytics Data Model

```sql
-- =====================================================
-- INVENTORY ANALYTICS STAR SCHEMA
-- Milestone 105 - Days 541-550
-- =====================================================

-- =====================================================
-- DIMENSION TABLES
-- =====================================================

-- Warehouse/Location Dimension
CREATE TABLE dim_warehouse (
    warehouse_sk SERIAL PRIMARY KEY,
    warehouse_id INTEGER NOT NULL,
    warehouse_code VARCHAR(20) UNIQUE NOT NULL,
    warehouse_name VARCHAR(100) NOT NULL,
    warehouse_type VARCHAR(30), -- 'raw_material', 'finished_goods', 'cold_storage', 'distribution'
    address_line1 VARCHAR(200),
    address_city VARCHAR(100),
    address_state VARCHAR(50),
    address_country VARCHAR(50),
    storage_capacity_units INTEGER,
    temperature_controlled BOOLEAN DEFAULT FALSE,
    min_temperature_celsius DECIMAL(5,2),
    max_temperature_celsius DECIMAL(5,2),
    manager_name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    valid_from TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valid_to TIMESTAMP DEFAULT '9999-12-31'::TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Storage Location Dimension (within warehouse)
CREATE TABLE dim_storage_location (
    location_sk SERIAL PRIMARY KEY,
    location_id INTEGER NOT NULL,
    warehouse_sk INTEGER NOT NULL REFERENCES dim_warehouse(warehouse_sk),
    location_code VARCHAR(30) UNIQUE NOT NULL,
    location_name VARCHAR(100),
    location_type VARCHAR(30), -- 'shelf', 'bin', 'pallet', 'cold_room', 'floor'
    zone VARCHAR(50), -- 'receiving', 'storage', 'picking', 'shipping'
    aisle VARCHAR(10),
    rack VARCHAR(10),
    shelf_level VARCHAR(10),
    bin_number VARCHAR(10),
    max_capacity_units INTEGER,
    current_utilization_pct DECIMAL(5,2),
    is_temperature_monitored BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_dim_location_warehouse ON dim_storage_location(warehouse_sk);

-- Product Category Dimension (for inventory classification)
CREATE TABLE dim_product_category (
    category_sk SERIAL PRIMARY KEY,
    category_id INTEGER NOT NULL,
    category_code VARCHAR(20) UNIQUE NOT NULL,
    category_name VARCHAR(100) NOT NULL,
    parent_category_sk INTEGER REFERENCES dim_product_category(category_sk),
    category_level INTEGER,
    category_path VARCHAR(500), -- 'Dairy > Milk > Fresh Milk'
    is_perishable BOOLEAN DEFAULT TRUE,
    default_shelf_life_days INTEGER,
    storage_requirements VARCHAR(200),
    handling_instructions TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Lot/Batch Dimension
CREATE TABLE dim_lot (
    lot_sk SERIAL PRIMARY KEY,
    lot_id INTEGER NOT NULL,
    lot_number VARCHAR(50) UNIQUE NOT NULL,
    product_sk INTEGER NOT NULL REFERENCES dim_product(product_sk),
    production_date DATE,
    expiration_date DATE NOT NULL,
    best_before_date DATE,
    shelf_life_days INTEGER,
    remaining_shelf_life_days INTEGER,
    shelf_life_utilization_pct DECIMAL(5,2),
    supplier_lot_number VARCHAR(50),
    supplier_sk INTEGER REFERENCES dim_supplier(supplier_sk),
    quality_grade VARCHAR(20),
    quality_test_date DATE,
    quality_test_result VARCHAR(50),
    is_recalled BOOLEAN DEFAULT FALSE,
    recall_reason TEXT,
    lot_status VARCHAR(20), -- 'available', 'quarantine', 'expired', 'consumed'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_dim_lot_product ON dim_lot(product_sk);
CREATE INDEX idx_dim_lot_expiration ON dim_lot(expiration_date);
CREATE INDEX idx_dim_lot_status ON dim_lot(lot_status);

-- Inventory Transaction Type Dimension
CREATE TABLE dim_inventory_transaction_type (
    transaction_type_sk SERIAL PRIMARY KEY,
    transaction_code VARCHAR(20) UNIQUE NOT NULL,
    transaction_name VARCHAR(100) NOT NULL,
    transaction_category VARCHAR(30), -- 'inbound', 'outbound', 'adjustment', 'transfer'
    affects_quantity VARCHAR(10), -- 'increase', 'decrease', 'none'
    requires_lot BOOLEAN DEFAULT FALSE,
    requires_approval BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- FACT TABLES
-- =====================================================

-- Daily Inventory Snapshot Fact
CREATE TABLE fact_daily_inventory_snapshot (
    snapshot_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    product_sk INTEGER NOT NULL REFERENCES dim_product(product_sk),
    warehouse_sk INTEGER NOT NULL REFERENCES dim_warehouse(warehouse_sk),
    location_sk INTEGER REFERENCES dim_storage_location(location_sk),

    -- Quantity Metrics
    quantity_on_hand DECIMAL(12,2) NOT NULL,
    quantity_reserved DECIMAL(12,2) DEFAULT 0,
    quantity_available DECIMAL(12,2) GENERATED ALWAYS AS
        (quantity_on_hand - COALESCE(quantity_reserved, 0)) STORED,
    quantity_in_transit DECIMAL(12,2) DEFAULT 0,
    quantity_on_order DECIMAL(12,2) DEFAULT 0,

    -- Valuation
    unit_cost DECIMAL(12,4),
    total_value DECIMAL(14,2),
    unit_of_measure VARCHAR(20),

    -- Stock Level Indicators
    reorder_point DECIMAL(12,2),
    safety_stock DECIMAL(12,2),
    max_stock_level DECIMAL(12,2),
    is_below_reorder_point BOOLEAN,
    is_below_safety_stock BOOLEAN,
    is_overstocked BOOLEAN,
    days_of_supply DECIMAL(8,2),

    -- Freshness Metrics
    total_lots INTEGER,
    lots_expiring_7_days INTEGER,
    lots_expiring_30_days INTEGER,
    lots_expired INTEGER,
    oldest_lot_age_days INTEGER,
    avg_remaining_shelf_life_days DECIMAL(8,2),
    freshness_score DECIMAL(5,2), -- 0-100 score

    -- Metadata
    snapshot_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(date_sk, product_sk, warehouse_sk, location_sk)
);

CREATE INDEX idx_fact_inv_snapshot_date ON fact_daily_inventory_snapshot(date_sk);
CREATE INDEX idx_fact_inv_snapshot_product ON fact_daily_inventory_snapshot(product_sk);
CREATE INDEX idx_fact_inv_snapshot_warehouse ON fact_daily_inventory_snapshot(warehouse_sk);

-- Inventory Transaction Fact
CREATE TABLE fact_inventory_transaction (
    transaction_sk BIGSERIAL PRIMARY KEY,
    transaction_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    transaction_datetime TIMESTAMP NOT NULL,
    product_sk INTEGER NOT NULL REFERENCES dim_product(product_sk),
    warehouse_sk INTEGER NOT NULL REFERENCES dim_warehouse(warehouse_sk),
    from_location_sk INTEGER REFERENCES dim_storage_location(location_sk),
    to_location_sk INTEGER REFERENCES dim_storage_location(location_sk),
    lot_sk INTEGER REFERENCES dim_lot(lot_sk),
    transaction_type_sk INTEGER NOT NULL REFERENCES dim_inventory_transaction_type(transaction_type_sk),

    -- Transaction Details
    transaction_reference VARCHAR(50),
    source_document_type VARCHAR(30), -- 'purchase_order', 'sales_order', 'production', 'adjustment'
    source_document_id INTEGER,
    source_document_number VARCHAR(50),

    -- Quantity and Value
    quantity DECIMAL(12,2) NOT NULL,
    unit_of_measure VARCHAR(20),
    unit_cost DECIMAL(12,4),
    total_value DECIMAL(14,2),

    -- Related Entities
    partner_sk INTEGER, -- Customer or supplier
    order_sk INTEGER,

    -- Quality/Freshness at Transaction
    lot_remaining_shelf_life_days INTEGER,
    lot_expiration_date DATE,

    -- User and Audit
    performed_by INTEGER,
    approved_by INTEGER,
    notes TEXT,

    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_inv_txn_date ON fact_inventory_transaction(transaction_date_sk);
CREATE INDEX idx_fact_inv_txn_product ON fact_inventory_transaction(product_sk);
CREATE INDEX idx_fact_inv_txn_lot ON fact_inventory_transaction(lot_sk);
CREATE INDEX idx_fact_inv_txn_type ON fact_inventory_transaction(transaction_type_sk);

-- Inventory Aging Fact
CREATE TABLE fact_inventory_aging (
    aging_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    product_sk INTEGER NOT NULL REFERENCES dim_product(product_sk),
    warehouse_sk INTEGER NOT NULL REFERENCES dim_warehouse(warehouse_sk),
    lot_sk INTEGER NOT NULL REFERENCES dim_lot(lot_sk),

    -- Lot Details
    lot_number VARCHAR(50),
    production_date DATE,
    expiration_date DATE,
    receipt_date DATE,

    -- Aging Metrics
    days_in_inventory INTEGER,
    days_until_expiration INTEGER,
    shelf_life_total_days INTEGER,
    shelf_life_consumed_pct DECIMAL(5,2),
    shelf_life_remaining_pct DECIMAL(5,2),

    -- Quantity
    quantity_on_hand DECIMAL(12,2),
    unit_cost DECIMAL(12,4),
    total_value DECIMAL(14,2),

    -- Aging Buckets
    aging_bucket VARCHAR(30), -- 'Fresh (0-7)', 'Good (8-14)', 'Aging (15-30)', 'Near Expiry', 'Expired'
    expiration_risk_level VARCHAR(20), -- 'Low', 'Medium', 'High', 'Critical', 'Expired'

    -- FEFO Compliance
    fefo_sequence INTEGER, -- Order in which lot should be picked
    is_fefo_compliant BOOLEAN,

    UNIQUE(date_sk, product_sk, warehouse_sk, lot_sk)
);

CREATE INDEX idx_fact_aging_date ON fact_inventory_aging(date_sk);
CREATE INDEX idx_fact_aging_expiration ON fact_inventory_aging(expiration_date);
CREATE INDEX idx_fact_aging_bucket ON fact_inventory_aging(aging_bucket);

-- Inventory Shrinkage/Loss Fact
CREATE TABLE fact_inventory_shrinkage (
    shrinkage_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    product_sk INTEGER NOT NULL REFERENCES dim_product(product_sk),
    warehouse_sk INTEGER NOT NULL REFERENCES dim_warehouse(warehouse_sk),
    lot_sk INTEGER REFERENCES dim_lot(lot_sk),

    -- Shrinkage Details
    shrinkage_type VARCHAR(30), -- 'expired', 'damaged', 'theft', 'administrative', 'sampling'
    shrinkage_reason TEXT,
    shrinkage_datetime TIMESTAMP,

    -- Quantity and Value Lost
    quantity_lost DECIMAL(12,2) NOT NULL,
    unit_cost DECIMAL(12,4),
    value_lost DECIMAL(14,2),
    recovery_value DECIMAL(14,2) DEFAULT 0, -- If any salvage value
    net_loss DECIMAL(14,2),

    -- Root Cause Analysis
    root_cause_category VARCHAR(50), -- 'storage_conditions', 'handling', 'forecasting', 'supplier_quality'
    preventable BOOLEAN,
    corrective_action TEXT,

    -- Approval
    reported_by INTEGER,
    approved_by INTEGER,
    approval_date DATE,

    -- Reference
    adjustment_reference VARCHAR(50),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_shrinkage_date ON fact_inventory_shrinkage(date_sk);
CREATE INDEX idx_fact_shrinkage_type ON fact_inventory_shrinkage(shrinkage_type);
CREATE INDEX idx_fact_shrinkage_product ON fact_inventory_shrinkage(product_sk);

-- Inventory Turnover Fact (Monthly Aggregation)
CREATE TABLE fact_inventory_turnover (
    turnover_sk BIGSERIAL PRIMARY KEY,
    month_sk INTEGER NOT NULL, -- Links to dim_date at month grain
    year_month VARCHAR(7) NOT NULL, -- 'YYYY-MM' format
    product_sk INTEGER NOT NULL REFERENCES dim_product(product_sk),
    warehouse_sk INTEGER NOT NULL REFERENCES dim_warehouse(warehouse_sk),
    category_sk INTEGER REFERENCES dim_product_category(category_sk),

    -- Movement Metrics
    opening_stock_qty DECIMAL(12,2),
    opening_stock_value DECIMAL(14,2),
    receipts_qty DECIMAL(12,2),
    receipts_value DECIMAL(14,2),
    issues_qty DECIMAL(12,2), -- Total outbound
    issues_value DECIMAL(14,2),
    adjustments_qty DECIMAL(12,2),
    adjustments_value DECIMAL(14,2),
    closing_stock_qty DECIMAL(12,2),
    closing_stock_value DECIMAL(14,2),

    -- Turnover Calculations
    average_inventory_qty DECIMAL(12,2),
    average_inventory_value DECIMAL(14,2),
    cost_of_goods_sold DECIMAL(14,2),
    inventory_turnover_ratio DECIMAL(8,4),
    days_inventory_outstanding DECIMAL(8,2),

    -- Velocity Classification
    abc_classification VARCHAR(1), -- 'A', 'B', 'C'
    xyz_classification VARCHAR(1), -- 'X', 'Y', 'Z' (demand variability)
    abc_xyz_segment VARCHAR(2), -- 'AX', 'AY', 'AZ', 'BX', etc.

    -- Efficiency Metrics
    stockout_days INTEGER DEFAULT 0,
    overstock_days INTEGER DEFAULT 0,
    service_level_pct DECIMAL(5,2),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(year_month, product_sk, warehouse_sk)
);

CREATE INDEX idx_fact_turnover_month ON fact_inventory_turnover(year_month);
CREATE INDEX idx_fact_turnover_product ON fact_inventory_turnover(product_sk);
CREATE INDEX idx_fact_turnover_abc ON fact_inventory_turnover(abc_classification);

-- Cold Chain Temperature Monitoring Fact
CREATE TABLE fact_cold_chain_monitoring (
    monitoring_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    timestamp_recorded TIMESTAMP NOT NULL,
    warehouse_sk INTEGER NOT NULL REFERENCES dim_warehouse(warehouse_sk),
    location_sk INTEGER REFERENCES dim_storage_location(location_sk),
    sensor_id VARCHAR(50),

    -- Temperature Reading
    temperature_celsius DECIMAL(5,2) NOT NULL,
    humidity_percentage DECIMAL(5,2),

    -- Thresholds
    min_threshold_celsius DECIMAL(5,2),
    max_threshold_celsius DECIMAL(5,2),

    -- Excursion Detection
    is_excursion BOOLEAN DEFAULT FALSE,
    excursion_type VARCHAR(20), -- 'above_max', 'below_min'
    excursion_duration_minutes INTEGER,
    excursion_severity VARCHAR(20), -- 'minor', 'moderate', 'severe', 'critical'

    -- Impact Assessment
    products_at_risk_count INTEGER,
    estimated_value_at_risk DECIMAL(14,2),

    -- Response
    alert_sent BOOLEAN DEFAULT FALSE,
    alert_acknowledged BOOLEAN DEFAULT FALSE,
    corrective_action_taken TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_cold_chain_date ON fact_cold_chain_monitoring(date_sk);
CREATE INDEX idx_fact_cold_chain_excursion ON fact_cold_chain_monitoring(is_excursion);
CREATE INDEX idx_fact_cold_chain_warehouse ON fact_cold_chain_monitoring(warehouse_sk);
```

### 3.2 ETL Pipeline for Inventory Data

```python
# airflow/dags/inventory_analytics_etl.py
"""
Inventory Analytics ETL Pipeline
Milestone 105 - Days 541-550
Extracts inventory data and loads into analytics data warehouse
"""

from datetime import datetime, timedelta
from airflow import DAG
from airflow.operators.python import PythonOperator
from airflow.providers.postgres.hooks.postgres import PostgresHook
from airflow.utils.task_group import TaskGroup
import pandas as pd
import numpy as np
from typing import Dict, List, Tuple, Optional
from scipy import stats
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
    'inventory_analytics_etl',
    default_args=default_args,
    description='Daily ETL for inventory analytics data warehouse',
    schedule_interval='0 3 * * *',  # Run at 3 AM daily
    start_date=datetime(2026, 1, 1),
    catchup=False,
    max_active_runs=1,
    tags=['inventory', 'analytics', 'etl', 'phase11'],
)


class InventoryDataExtractor:
    """Extracts inventory data from Odoo source database."""

    def __init__(self, source_conn_id: str = 'odoo_postgres'):
        self.source_hook = PostgresHook(postgres_conn_id=source_conn_id)

    def extract_stock_quants(self, execution_date: datetime) -> pd.DataFrame:
        """Extract current stock levels by location and lot."""
        query = """
            SELECT
                sq.id,
                sq.product_id,
                pt.name AS product_name,
                pt.default_code AS product_code,
                pc.name AS category_name,
                sq.location_id,
                sl.name AS location_name,
                sl.complete_name AS location_path,
                sw.name AS warehouse_name,
                sq.lot_id,
                spl.name AS lot_number,
                spl.expiration_date,
                spl.use_date AS best_before_date,
                spl.create_date AS lot_receipt_date,
                sq.quantity,
                sq.reserved_quantity,
                sq.inventory_quantity_auto_apply,
                pt.standard_price AS unit_cost,
                pt.uom_id,
                uom.name AS uom_name,
                sq.in_date,
                sq.write_date
            FROM stock_quant sq
            JOIN product_product pp ON sq.product_id = pp.id
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            LEFT JOIN product_category pc ON pt.categ_id = pc.id
            JOIN stock_location sl ON sq.location_id = sl.id
            LEFT JOIN stock_warehouse sw ON sl.warehouse_id = sw.id
            LEFT JOIN stock_lot spl ON sq.lot_id = spl.id
            LEFT JOIN uom_uom uom ON pt.uom_id = uom.id
            WHERE sl.usage = 'internal'
              AND sq.quantity != 0
            ORDER BY sq.id
        """
        return self.source_hook.get_pandas_df(query)

    def extract_stock_moves(self, execution_date: datetime) -> pd.DataFrame:
        """Extract stock movements for the target date."""
        query = """
            SELECT
                sm.id,
                sm.date AS move_datetime,
                sm.product_id,
                pt.name AS product_name,
                pt.default_code AS product_code,
                sm.location_id AS from_location_id,
                sl_from.complete_name AS from_location_name,
                sm.location_dest_id AS to_location_id,
                sl_to.complete_name AS to_location_name,
                sm.lot_ids,
                sm.product_uom_qty AS quantity,
                pt.standard_price AS unit_cost,
                sm.origin,
                sm.reference,
                sm.state,
                CASE
                    WHEN sl_from.usage = 'supplier' THEN 'receipt'
                    WHEN sl_to.usage = 'customer' THEN 'delivery'
                    WHEN sl_from.usage = 'production' THEN 'production_receipt'
                    WHEN sl_to.usage = 'production' THEN 'production_issue'
                    WHEN sl_from.usage = 'internal' AND sl_to.usage = 'internal' THEN 'transfer'
                    WHEN sl_to.usage = 'inventory' THEN 'adjustment_out'
                    WHEN sl_from.usage = 'inventory' THEN 'adjustment_in'
                    ELSE 'other'
                END AS move_type,
                sm.create_uid,
                ru.name AS created_by_name
            FROM stock_move sm
            JOIN product_product pp ON sm.product_id = pp.id
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            JOIN stock_location sl_from ON sm.location_id = sl_from.id
            JOIN stock_location sl_to ON sm.location_dest_id = sl_to.id
            LEFT JOIN res_users ru ON sm.create_uid = ru.id
            WHERE DATE(sm.date) = %(target_date)s
              AND sm.state = 'done'
            ORDER BY sm.date
        """
        target_date = execution_date.date()
        return self.source_hook.get_pandas_df(query, parameters={'target_date': target_date})

    def extract_lot_expiration_data(self) -> pd.DataFrame:
        """Extract all active lots with expiration information."""
        query = """
            SELECT
                spl.id AS lot_id,
                spl.name AS lot_number,
                pp.id AS product_id,
                pt.name AS product_name,
                pt.default_code AS product_code,
                spl.create_date AS receipt_date,
                spl.expiration_date,
                spl.use_date AS best_before_date,
                spl.removal_date,
                spl.alert_date,
                COALESCE(
                    SUM(sq.quantity) FILTER (WHERE sl.usage = 'internal'),
                    0
                ) AS quantity_on_hand,
                pt.standard_price AS unit_cost
            FROM stock_lot spl
            JOIN product_product pp ON spl.product_id = pp.id
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            LEFT JOIN stock_quant sq ON spl.id = sq.lot_id
            LEFT JOIN stock_location sl ON sq.location_id = sl.id
            WHERE spl.expiration_date IS NOT NULL
            GROUP BY spl.id, spl.name, pp.id, pt.name, pt.default_code,
                     spl.create_date, spl.expiration_date, spl.use_date,
                     spl.removal_date, spl.alert_date, pt.standard_price
            HAVING COALESCE(SUM(sq.quantity) FILTER (WHERE sl.usage = 'internal'), 0) > 0
            ORDER BY spl.expiration_date
        """
        return self.source_hook.get_pandas_df(query)

    def extract_inventory_adjustments(self, execution_date: datetime) -> pd.DataFrame:
        """Extract inventory adjustments/losses."""
        query = """
            SELECT
                sml.id,
                sml.date AS adjustment_date,
                sml.product_id,
                pt.name AS product_name,
                pt.default_code AS product_code,
                sml.lot_id,
                spl.name AS lot_number,
                sml.location_id,
                sl.complete_name AS location_name,
                sw.name AS warehouse_name,
                sml.qty_done AS quantity,
                pt.standard_price AS unit_cost,
                sm.origin AS reference,
                CASE
                    WHEN sl_dest.scrap_location THEN 'scrap'
                    WHEN sm.inventory_id IS NOT NULL THEN 'count_adjustment'
                    ELSE 'other_adjustment'
                END AS adjustment_type,
                ru.name AS performed_by
            FROM stock_move_line sml
            JOIN stock_move sm ON sml.move_id = sm.id
            JOIN product_product pp ON sml.product_id = pp.id
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            JOIN stock_location sl ON sml.location_id = sl.id
            JOIN stock_location sl_dest ON sml.location_dest_id = sl_dest.id
            LEFT JOIN stock_warehouse sw ON sl.warehouse_id = sw.id
            LEFT JOIN stock_lot spl ON sml.lot_id = spl.id
            LEFT JOIN res_users ru ON sm.create_uid = ru.id
            WHERE DATE(sml.date) = %(target_date)s
              AND (sl_dest.usage = 'inventory' OR sl_dest.scrap_location = TRUE)
              AND sm.state = 'done'
            ORDER BY sml.date
        """
        target_date = execution_date.date()
        return self.source_hook.get_pandas_df(query, parameters={'target_date': target_date})


class InventoryAnalyticsCalculator:
    """Calculates inventory analytics metrics."""

    @staticmethod
    def calculate_turnover_metrics(
        movements_df: pd.DataFrame,
        snapshots_df: pd.DataFrame,
        period_start: datetime,
        period_end: datetime
    ) -> pd.DataFrame:
        """Calculate inventory turnover metrics for a period."""

        # Get opening and closing stock
        opening_stock = snapshots_df[
            snapshots_df['snapshot_date'] == period_start
        ].groupby('product_sk').agg({
            'quantity_on_hand': 'sum',
            'total_value': 'sum'
        }).rename(columns={
            'quantity_on_hand': 'opening_qty',
            'total_value': 'opening_value'
        })

        closing_stock = snapshots_df[
            snapshots_df['snapshot_date'] == period_end
        ].groupby('product_sk').agg({
            'quantity_on_hand': 'sum',
            'total_value': 'sum'
        }).rename(columns={
            'quantity_on_hand': 'closing_qty',
            'total_value': 'closing_value'
        })

        # Calculate issues (COGS proxy)
        issues = movements_df[
            movements_df['move_type'].isin(['delivery', 'production_issue'])
        ].groupby('product_sk').agg({
            'quantity': 'sum',
            'total_value': 'sum'
        }).rename(columns={
            'quantity': 'issues_qty',
            'total_value': 'cogs'
        })

        # Merge all metrics
        turnover = opening_stock.join(closing_stock, how='outer').join(issues, how='outer')
        turnover = turnover.fillna(0)

        # Calculate turnover ratio
        turnover['avg_inventory_qty'] = (turnover['opening_qty'] + turnover['closing_qty']) / 2
        turnover['avg_inventory_value'] = (turnover['opening_value'] + turnover['closing_value']) / 2

        turnover['turnover_ratio'] = turnover['cogs'] / turnover['avg_inventory_value'].replace(0, np.nan)
        turnover['days_inventory_outstanding'] = 365 / turnover['turnover_ratio'].replace(0, np.nan)

        return turnover.reset_index()

    @staticmethod
    def calculate_abc_classification(
        turnover_df: pd.DataFrame,
        value_column: str = 'cogs'
    ) -> pd.DataFrame:
        """Perform ABC analysis based on consumption value."""

        df = turnover_df.copy()
        df = df[df[value_column] > 0].sort_values(value_column, ascending=False)

        # Calculate cumulative percentage
        total_value = df[value_column].sum()
        df['cumulative_value'] = df[value_column].cumsum()
        df['cumulative_pct'] = (df['cumulative_value'] / total_value) * 100

        # Assign ABC class
        def assign_abc(cum_pct):
            if cum_pct <= 80:
                return 'A'
            elif cum_pct <= 95:
                return 'B'
            else:
                return 'C'

        df['abc_class'] = df['cumulative_pct'].apply(assign_abc)

        return df

    @staticmethod
    def calculate_xyz_classification(
        daily_demand_df: pd.DataFrame,
        product_column: str = 'product_sk',
        demand_column: str = 'quantity'
    ) -> pd.DataFrame:
        """Perform XYZ analysis based on demand variability."""

        # Calculate coefficient of variation for each product
        cv_by_product = daily_demand_df.groupby(product_column)[demand_column].agg([
            'mean', 'std', 'count'
        ])
        cv_by_product['cv'] = cv_by_product['std'] / cv_by_product['mean'].replace(0, np.nan)

        # Assign XYZ class based on CV
        def assign_xyz(cv):
            if pd.isna(cv) or cv < 0.5:
                return 'X'  # Stable demand
            elif cv < 1.0:
                return 'Y'  # Variable demand
            else:
                return 'Z'  # Highly variable demand

        cv_by_product['xyz_class'] = cv_by_product['cv'].apply(assign_xyz)

        return cv_by_product.reset_index()

    @staticmethod
    def calculate_freshness_score(
        remaining_shelf_life_days: int,
        total_shelf_life_days: int
    ) -> float:
        """Calculate freshness score (0-100)."""
        if total_shelf_life_days <= 0:
            return 0.0

        remaining_pct = (remaining_shelf_life_days / total_shelf_life_days) * 100

        # Apply scoring curve (penalize items closer to expiration more heavily)
        if remaining_pct >= 75:
            return min(100, remaining_pct)
        elif remaining_pct >= 50:
            return remaining_pct * 0.9
        elif remaining_pct >= 25:
            return remaining_pct * 0.7
        elif remaining_pct > 0:
            return remaining_pct * 0.5
        else:
            return 0.0

    @staticmethod
    def calculate_reorder_point(
        avg_daily_demand: float,
        lead_time_days: int,
        safety_stock: float
    ) -> float:
        """Calculate reorder point."""
        return (avg_daily_demand * lead_time_days) + safety_stock

    @staticmethod
    def calculate_safety_stock(
        demand_std: float,
        lead_time_days: int,
        service_level: float = 0.95
    ) -> float:
        """Calculate safety stock based on service level."""
        z_score = stats.norm.ppf(service_level)
        return z_score * demand_std * np.sqrt(lead_time_days)


class InventoryDataLoader:
    """Loads inventory analytics data into data warehouse."""

    def __init__(self, target_conn_id: str = 'analytics_dw'):
        self.target_hook = PostgresHook(postgres_conn_id=target_conn_id)

    def load_daily_snapshot(self, df: pd.DataFrame, target_date: datetime) -> int:
        """Load daily inventory snapshot."""
        if df.empty:
            return 0

        # Delete existing snapshot for the date
        delete_query = """
            DELETE FROM fact_daily_inventory_snapshot
            WHERE date_sk = (SELECT date_sk FROM dim_date WHERE date_actual = %s)
        """
        self.target_hook.run(delete_query, parameters=[target_date.date()])

        rows_loaded = 0
        for _, row in df.iterrows():
            insert_query = """
                INSERT INTO fact_daily_inventory_snapshot (
                    date_sk, product_sk, warehouse_sk, location_sk,
                    quantity_on_hand, quantity_reserved, quantity_in_transit, quantity_on_order,
                    unit_cost, total_value, unit_of_measure,
                    reorder_point, safety_stock, max_stock_level,
                    is_below_reorder_point, is_below_safety_stock, is_overstocked,
                    days_of_supply,
                    total_lots, lots_expiring_7_days, lots_expiring_30_days, lots_expired,
                    oldest_lot_age_days, avg_remaining_shelf_life_days, freshness_score
                )
                SELECT
                    d.date_sk,
                    %s, %s, %s,
                    %s, %s, %s, %s,
                    %s, %s, %s,
                    %s, %s, %s,
                    %s, %s, %s,
                    %s,
                    %s, %s, %s, %s,
                    %s, %s, %s
                FROM dim_date d
                WHERE d.date_actual = %s
            """
            try:
                self.target_hook.run(insert_query, parameters=[
                    row['product_sk'], row['warehouse_sk'], row.get('location_sk'),
                    row['quantity_on_hand'], row.get('quantity_reserved', 0),
                    row.get('quantity_in_transit', 0), row.get('quantity_on_order', 0),
                    row.get('unit_cost'), row.get('total_value'), row.get('uom'),
                    row.get('reorder_point'), row.get('safety_stock'), row.get('max_stock'),
                    row.get('below_reorder', False), row.get('below_safety', False),
                    row.get('overstocked', False),
                    row.get('days_of_supply'),
                    row.get('total_lots', 0), row.get('lots_exp_7', 0),
                    row.get('lots_exp_30', 0), row.get('lots_expired', 0),
                    row.get('oldest_lot_age'), row.get('avg_shelf_life'),
                    row.get('freshness_score'),
                    target_date.date()
                ])
                rows_loaded += 1
            except Exception as e:
                logger.error(f"Error loading snapshot row: {e}")

        return rows_loaded

    def load_shrinkage_records(self, df: pd.DataFrame) -> int:
        """Load inventory shrinkage/loss records."""
        if df.empty:
            return 0

        rows_loaded = 0
        for _, row in df.iterrows():
            insert_query = """
                INSERT INTO fact_inventory_shrinkage (
                    date_sk, product_sk, warehouse_sk, lot_sk,
                    shrinkage_type, shrinkage_reason, shrinkage_datetime,
                    quantity_lost, unit_cost, value_lost, net_loss,
                    root_cause_category, preventable,
                    reported_by, adjustment_reference
                )
                SELECT
                    d.date_sk,
                    %s, %s, %s,
                    %s, %s, %s,
                    %s, %s, %s, %s,
                    %s, %s,
                    %s, %s
                FROM dim_date d
                WHERE d.date_actual = %s
            """
            try:
                self.target_hook.run(insert_query, parameters=[
                    row['product_sk'], row['warehouse_sk'], row.get('lot_sk'),
                    row['shrinkage_type'], row.get('reason'), row['adjustment_datetime'],
                    row['quantity'], row.get('unit_cost'), row.get('value_lost'),
                    row.get('net_loss'),
                    row.get('root_cause'), row.get('preventable', True),
                    row.get('reported_by'), row.get('reference'),
                    row['adjustment_datetime'].date()
                ])
                rows_loaded += 1
            except Exception as e:
                logger.error(f"Error loading shrinkage record: {e}")

        return rows_loaded


# =============================================================================
# AIRFLOW TASK DEFINITIONS
# =============================================================================

def extract_inventory_data(**context):
    """Extract all inventory data."""
    execution_date = context['execution_date']
    extractor = InventoryDataExtractor()

    data = {
        'stock_quants': extractor.extract_stock_quants(execution_date),
        'stock_moves': extractor.extract_stock_moves(execution_date),
        'lot_expiration': extractor.extract_lot_expiration_data(),
        'adjustments': extractor.extract_inventory_adjustments(execution_date)
    }

    for key, df in data.items():
        context['ti'].xcom_push(key=key, value=df.to_json())
        logger.info(f"Extracted {len(df)} {key} records")

    return {'status': 'success'}


def transform_inventory_data(**context):
    """Transform inventory data with analytics calculations."""
    ti = context['ti']
    calculator = InventoryAnalyticsCalculator()

    # Retrieve extracted data
    stock_quants = pd.read_json(ti.xcom_pull(key='stock_quants'))
    lot_data = pd.read_json(ti.xcom_pull(key='lot_expiration'))

    # Calculate freshness scores for lots
    today = context['execution_date'].date()
    if not lot_data.empty and 'expiration_date' in lot_data.columns:
        lot_data['days_remaining'] = (
            pd.to_datetime(lot_data['expiration_date']).dt.date - today
        ).apply(lambda x: x.days if hasattr(x, 'days') else 0)

        lot_data['shelf_life_days'] = (
            pd.to_datetime(lot_data['expiration_date']) -
            pd.to_datetime(lot_data['receipt_date'])
        ).dt.days

        lot_data['freshness_score'] = lot_data.apply(
            lambda row: calculator.calculate_freshness_score(
                row['days_remaining'],
                row['shelf_life_days']
            ),
            axis=1
        )

    ti.xcom_push(key='transformed_lots', value=lot_data.to_json())
    ti.xcom_push(key='transformed_quants', value=stock_quants.to_json())

    return {'status': 'success'}


def load_inventory_snapshots(**context):
    """Load inventory snapshot data."""
    ti = context['ti']
    execution_date = context['execution_date']
    loader = InventoryDataLoader()

    quants = pd.read_json(ti.xcom_pull(key='transformed_quants'))
    rows_loaded = loader.load_daily_snapshot(quants, execution_date)

    logger.info(f"Loaded {rows_loaded} inventory snapshot records")
    return {'status': 'success', 'rows_loaded': rows_loaded}


def calculate_turnover_metrics(**context):
    """Calculate monthly turnover metrics."""
    execution_date = context['execution_date']

    # Only run on first day of month
    if execution_date.day != 1:
        logger.info("Skipping turnover calculation - not first of month")
        return {'status': 'skipped'}

    target_hook = PostgresHook(postgres_conn_id='analytics_dw')

    turnover_query = """
        INSERT INTO fact_inventory_turnover (
            month_sk, year_month, product_sk, warehouse_sk, category_sk,
            opening_stock_qty, opening_stock_value,
            receipts_qty, receipts_value,
            issues_qty, issues_value,
            closing_stock_qty, closing_stock_value,
            average_inventory_qty, average_inventory_value,
            cost_of_goods_sold, inventory_turnover_ratio, days_inventory_outstanding
        )
        WITH monthly_movements AS (
            SELECT
                ft.product_sk,
                ft.warehouse_sk,
                SUM(CASE WHEN itt.transaction_category = 'inbound' THEN ft.quantity ELSE 0 END) AS receipts_qty,
                SUM(CASE WHEN itt.transaction_category = 'inbound' THEN ft.total_value ELSE 0 END) AS receipts_value,
                SUM(CASE WHEN itt.transaction_category = 'outbound' THEN ft.quantity ELSE 0 END) AS issues_qty,
                SUM(CASE WHEN itt.transaction_category = 'outbound' THEN ft.total_value ELSE 0 END) AS issues_value
            FROM fact_inventory_transaction ft
            JOIN dim_inventory_transaction_type itt ON ft.transaction_type_sk = itt.transaction_type_sk
            JOIN dim_date d ON ft.transaction_date_sk = d.date_sk
            WHERE d.year_actual = EXTRACT(YEAR FROM %(month_start)s::DATE)
              AND d.month_of_year = EXTRACT(MONTH FROM %(month_start)s::DATE)
            GROUP BY ft.product_sk, ft.warehouse_sk
        ),
        opening_closing AS (
            SELECT
                product_sk, warehouse_sk,
                FIRST_VALUE(quantity_on_hand) OVER w AS opening_qty,
                FIRST_VALUE(total_value) OVER w AS opening_value,
                LAST_VALUE(quantity_on_hand) OVER w AS closing_qty,
                LAST_VALUE(total_value) OVER w AS closing_value
            FROM fact_daily_inventory_snapshot fis
            JOIN dim_date d ON fis.date_sk = d.date_sk
            WHERE d.year_actual = EXTRACT(YEAR FROM %(month_start)s::DATE)
              AND d.month_of_year = EXTRACT(MONTH FROM %(month_start)s::DATE)
            WINDOW w AS (PARTITION BY product_sk, warehouse_sk ORDER BY d.date_actual
                        ROWS BETWEEN UNBOUNDED PRECEDING AND UNBOUNDED FOLLOWING)
        )
        SELECT
            (SELECT date_sk FROM dim_date WHERE date_actual = %(month_start)s::DATE),
            TO_CHAR(%(month_start)s::DATE, 'YYYY-MM'),
            COALESCE(mm.product_sk, oc.product_sk),
            COALESCE(mm.warehouse_sk, oc.warehouse_sk),
            p.category_id,
            COALESCE(oc.opening_qty, 0),
            COALESCE(oc.opening_value, 0),
            COALESCE(mm.receipts_qty, 0),
            COALESCE(mm.receipts_value, 0),
            COALESCE(mm.issues_qty, 0),
            COALESCE(mm.issues_value, 0),
            COALESCE(oc.closing_qty, 0),
            COALESCE(oc.closing_value, 0),
            (COALESCE(oc.opening_qty, 0) + COALESCE(oc.closing_qty, 0)) / 2,
            (COALESCE(oc.opening_value, 0) + COALESCE(oc.closing_value, 0)) / 2,
            COALESCE(mm.issues_value, 0),
            CASE WHEN (COALESCE(oc.opening_value, 0) + COALESCE(oc.closing_value, 0)) / 2 > 0
                THEN COALESCE(mm.issues_value, 0) / ((COALESCE(oc.opening_value, 0) + COALESCE(oc.closing_value, 0)) / 2)
                ELSE NULL END,
            CASE WHEN COALESCE(mm.issues_value, 0) > 0
                THEN 30 / (COALESCE(mm.issues_value, 0) / NULLIF((COALESCE(oc.opening_value, 0) + COALESCE(oc.closing_value, 0)) / 2, 0))
                ELSE NULL END
        FROM monthly_movements mm
        FULL OUTER JOIN opening_closing oc ON mm.product_sk = oc.product_sk AND mm.warehouse_sk = oc.warehouse_sk
        LEFT JOIN dim_product p ON COALESCE(mm.product_sk, oc.product_sk) = p.product_sk
        ON CONFLICT (year_month, product_sk, warehouse_sk) DO UPDATE SET
            issues_qty = EXCLUDED.issues_qty,
            issues_value = EXCLUDED.issues_value,
            inventory_turnover_ratio = EXCLUDED.inventory_turnover_ratio
    """

    # Previous month
    month_start = (execution_date.replace(day=1) - timedelta(days=1)).replace(day=1)
    target_hook.run(turnover_query, parameters={'month_start': month_start})

    logger.info(f"Calculated turnover metrics for {month_start.strftime('%Y-%m')}")
    return {'status': 'success'}


# Task definitions
with dag:
    extract_task = PythonOperator(
        task_id='extract_inventory_data',
        python_callable=extract_inventory_data,
    )

    transform_task = PythonOperator(
        task_id='transform_inventory_data',
        python_callable=transform_inventory_data,
    )

    load_snapshot_task = PythonOperator(
        task_id='load_inventory_snapshots',
        python_callable=load_inventory_snapshots,
    )

    turnover_task = PythonOperator(
        task_id='calculate_turnover_metrics',
        python_callable=calculate_turnover_metrics,
    )

    extract_task >> transform_task >> load_snapshot_task >> turnover_task
```

---

## 4. Analytics Dashboards and Visualizations

### 4.1 Stock Level Dashboard

```sql
-- =====================================================
-- INVENTORY STOCK LEVEL ANALYTICS
-- Apache Superset Dashboard Data Sources
-- =====================================================

-- 4.1.1 Current Stock Overview KPI
CREATE OR REPLACE VIEW vw_current_stock_overview AS
SELECT
    w.warehouse_name,
    p.product_code,
    p.product_name,
    pc.category_name,
    fis.quantity_on_hand,
    fis.quantity_available,
    fis.quantity_reserved,
    fis.quantity_in_transit,
    fis.total_value,
    fis.unit_of_measure,
    fis.days_of_supply,
    -- Stock status indicators
    CASE
        WHEN fis.is_below_safety_stock THEN 'Critical - Below Safety Stock'
        WHEN fis.is_below_reorder_point THEN 'Warning - Below Reorder Point'
        WHEN fis.is_overstocked THEN 'Alert - Overstocked'
        ELSE 'Normal'
    END AS stock_status,
    -- Stock health color
    CASE
        WHEN fis.is_below_safety_stock THEN 'red'
        WHEN fis.is_below_reorder_point THEN 'orange'
        WHEN fis.is_overstocked THEN 'yellow'
        ELSE 'green'
    END AS status_color,
    fis.reorder_point,
    fis.safety_stock,
    fis.max_stock_level,
    -- Freshness metrics
    fis.freshness_score,
    fis.avg_remaining_shelf_life_days,
    fis.lots_expiring_7_days,
    fis.lots_expiring_30_days
FROM fact_daily_inventory_snapshot fis
JOIN dim_product p ON fis.product_sk = p.product_sk
JOIN dim_warehouse w ON fis.warehouse_sk = w.warehouse_sk
LEFT JOIN dim_product_category pc ON p.category_id = pc.category_sk
JOIN dim_date d ON fis.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
ORDER BY
    CASE
        WHEN fis.is_below_safety_stock THEN 1
        WHEN fis.is_below_reorder_point THEN 2
        WHEN fis.is_overstocked THEN 3
        ELSE 4
    END,
    fis.days_of_supply;

-- 4.1.2 Stock Trend Analysis
CREATE OR REPLACE VIEW vw_stock_trend_analysis AS
SELECT
    d.date_actual,
    d.day_of_week_name,
    d.week_of_year,
    w.warehouse_name,
    p.product_code,
    p.product_name,
    fis.quantity_on_hand,
    fis.total_value,
    -- Period comparisons
    LAG(fis.quantity_on_hand, 1) OVER (
        PARTITION BY fis.product_sk, fis.warehouse_sk ORDER BY d.date_actual
    ) AS prev_day_qty,
    LAG(fis.quantity_on_hand, 7) OVER (
        PARTITION BY fis.product_sk, fis.warehouse_sk ORDER BY d.date_actual
    ) AS prev_week_qty,
    -- Stock change
    fis.quantity_on_hand - LAG(fis.quantity_on_hand, 1) OVER (
        PARTITION BY fis.product_sk, fis.warehouse_sk ORDER BY d.date_actual
    ) AS daily_change,
    -- Rolling averages
    AVG(fis.quantity_on_hand) OVER (
        PARTITION BY fis.product_sk, fis.warehouse_sk
        ORDER BY d.date_actual
        ROWS BETWEEN 6 PRECEDING AND CURRENT ROW
    ) AS rolling_7day_avg,
    AVG(fis.quantity_on_hand) OVER (
        PARTITION BY fis.product_sk, fis.warehouse_sk
        ORDER BY d.date_actual
        ROWS BETWEEN 29 PRECEDING AND CURRENT ROW
    ) AS rolling_30day_avg
FROM fact_daily_inventory_snapshot fis
JOIN dim_product p ON fis.product_sk = p.product_sk
JOIN dim_warehouse w ON fis.warehouse_sk = w.warehouse_sk
JOIN dim_date d ON fis.date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '90 days'
ORDER BY d.date_actual DESC, w.warehouse_name, p.product_name;

-- 4.1.3 Multi-Location Stock Distribution
CREATE OR REPLACE VIEW vw_stock_distribution AS
SELECT
    p.product_code,
    p.product_name,
    SUM(fis.quantity_on_hand) AS total_qty,
    SUM(fis.total_value) AS total_value,
    COUNT(DISTINCT fis.warehouse_sk) AS locations_count,
    jsonb_agg(
        jsonb_build_object(
            'warehouse', w.warehouse_name,
            'quantity', fis.quantity_on_hand,
            'value', fis.total_value,
            'freshness', fis.freshness_score
        )
        ORDER BY fis.quantity_on_hand DESC
    ) AS distribution_by_location,
    -- Concentration index (Herfindahl)
    SUM(POWER(fis.quantity_on_hand / NULLIF(SUM(fis.quantity_on_hand) OVER (PARTITION BY p.product_sk), 0), 2)) AS concentration_index
FROM fact_daily_inventory_snapshot fis
JOIN dim_product p ON fis.product_sk = p.product_sk
JOIN dim_warehouse w ON fis.warehouse_sk = w.warehouse_sk
JOIN dim_date d ON fis.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
GROUP BY p.product_sk, p.product_code, p.product_name
ORDER BY total_value DESC;
```

### 4.2 Expiration and Freshness Analytics

```sql
-- =====================================================
-- EXPIRATION AND FRESHNESS ANALYTICS
-- Shelf Life Management Dashboards
-- =====================================================

-- 4.2.1 Expiration Risk Dashboard
CREATE OR REPLACE VIEW vw_expiration_risk_dashboard AS
SELECT
    w.warehouse_name,
    p.product_code,
    p.product_name,
    l.lot_number,
    l.production_date,
    l.expiration_date,
    fa.days_until_expiration,
    fa.quantity_on_hand,
    fa.total_value,
    fa.aging_bucket,
    fa.expiration_risk_level,
    -- Urgency metrics
    CASE
        WHEN fa.days_until_expiration <= 0 THEN 'EXPIRED - Immediate Action'
        WHEN fa.days_until_expiration <= 3 THEN 'CRITICAL - 3 Days or Less'
        WHEN fa.days_until_expiration <= 7 THEN 'HIGH - 1 Week or Less'
        WHEN fa.days_until_expiration <= 14 THEN 'MEDIUM - 2 Weeks or Less'
        WHEN fa.days_until_expiration <= 30 THEN 'LOW - 1 Month or Less'
        ELSE 'NORMAL'
    END AS urgency_level,
    -- Value at risk
    CASE
        WHEN fa.days_until_expiration <= 7 THEN fa.total_value
        ELSE 0
    END AS value_at_immediate_risk,
    -- Recommended action
    CASE
        WHEN fa.days_until_expiration <= 0 THEN 'Write-off or Donate'
        WHEN fa.days_until_expiration <= 3 THEN 'Emergency Markdown / Flash Sale'
        WHEN fa.days_until_expiration <= 7 THEN 'Promotional Pricing'
        WHEN fa.days_until_expiration <= 14 THEN 'Prioritize for FEFO Picking'
        ELSE 'Standard Handling'
    END AS recommended_action,
    fa.fefo_sequence,
    fa.is_fefo_compliant
FROM fact_inventory_aging fa
JOIN dim_product p ON fa.product_sk = p.product_sk
JOIN dim_warehouse w ON fa.warehouse_sk = w.warehouse_sk
JOIN dim_lot l ON fa.lot_sk = l.lot_sk
JOIN dim_date d ON fa.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
  AND fa.quantity_on_hand > 0
ORDER BY fa.days_until_expiration ASC, fa.total_value DESC;

-- 4.2.2 Freshness Score Summary
CREATE OR REPLACE VIEW vw_freshness_score_summary AS
SELECT
    w.warehouse_name,
    pc.category_name,
    COUNT(DISTINCT p.product_sk) AS products_count,
    SUM(fis.quantity_on_hand) AS total_quantity,
    SUM(fis.total_value) AS total_value,
    ROUND(AVG(fis.freshness_score), 1) AS avg_freshness_score,
    MIN(fis.freshness_score) AS min_freshness_score,
    MAX(fis.freshness_score) AS max_freshness_score,
    -- Freshness distribution
    COUNT(CASE WHEN fis.freshness_score >= 80 THEN 1 END) AS excellent_count,
    COUNT(CASE WHEN fis.freshness_score >= 60 AND fis.freshness_score < 80 THEN 1 END) AS good_count,
    COUNT(CASE WHEN fis.freshness_score >= 40 AND fis.freshness_score < 60 THEN 1 END) AS fair_count,
    COUNT(CASE WHEN fis.freshness_score >= 20 AND fis.freshness_score < 40 THEN 1 END) AS poor_count,
    COUNT(CASE WHEN fis.freshness_score < 20 THEN 1 END) AS critical_count,
    -- Value at risk by freshness
    SUM(CASE WHEN fis.freshness_score < 40 THEN fis.total_value ELSE 0 END) AS value_at_risk
FROM fact_daily_inventory_snapshot fis
JOIN dim_product p ON fis.product_sk = p.product_sk
JOIN dim_warehouse w ON fis.warehouse_sk = w.warehouse_sk
LEFT JOIN dim_product_category pc ON p.category_id = pc.category_sk
JOIN dim_date d ON fis.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
  AND fis.quantity_on_hand > 0
GROUP BY w.warehouse_name, pc.category_name
ORDER BY avg_freshness_score ASC;

-- 4.2.3 Shelf Life Utilization Analysis
CREATE OR REPLACE VIEW vw_shelf_life_utilization AS
SELECT
    p.product_code,
    p.product_name,
    pc.category_name,
    pc.default_shelf_life_days AS expected_shelf_life,
    COUNT(DISTINCT l.lot_sk) AS lots_analyzed,
    -- Actual shelf life utilization
    ROUND(AVG(
        CASE WHEN fa.shelf_life_consumed_pct > 0
        THEN (100 - fa.shelf_life_remaining_pct)
        END
    ), 1) AS avg_utilization_at_consumption_pct,
    -- Shelf life remaining when picked
    ROUND(AVG(fa.shelf_life_remaining_pct), 1) AS avg_remaining_at_pick_pct,
    ROUND(AVG(fa.days_until_expiration), 0) AS avg_days_remaining_at_pick,
    -- Waste indicators
    COUNT(CASE WHEN fa.days_until_expiration <= 0 THEN 1 END) AS expired_lots_count,
    SUM(CASE WHEN fa.days_until_expiration <= 0 THEN fa.total_value ELSE 0 END) AS expired_value,
    -- FEFO compliance
    ROUND(
        COUNT(CASE WHEN fa.is_fefo_compliant THEN 1 END)::DECIMAL /
        NULLIF(COUNT(*), 0) * 100, 1
    ) AS fefo_compliance_pct
FROM fact_inventory_aging fa
JOIN dim_product p ON fa.product_sk = p.product_sk
JOIN dim_lot l ON fa.lot_sk = l.lot_sk
LEFT JOIN dim_product_category pc ON p.category_id = pc.category_sk
JOIN dim_date d ON fa.date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '30 days'
GROUP BY p.product_sk, p.product_code, p.product_name,
         pc.category_name, pc.default_shelf_life_days
HAVING COUNT(DISTINCT l.lot_sk) >= 5  -- Minimum sample size
ORDER BY avg_utilization_at_consumption_pct DESC;
```

### 4.3 Inventory Turnover Analytics

```sql
-- =====================================================
-- INVENTORY TURNOVER AND EFFICIENCY ANALYTICS
-- ABC/XYZ Classification and Optimization
-- =====================================================

-- 4.3.1 Turnover Performance Dashboard
CREATE OR REPLACE VIEW vw_turnover_performance AS
SELECT
    ft.year_month,
    w.warehouse_name,
    p.product_code,
    p.product_name,
    pc.category_name,
    ft.opening_stock_value,
    ft.closing_stock_value,
    ft.cost_of_goods_sold,
    ft.average_inventory_value,
    ft.inventory_turnover_ratio,
    ft.days_inventory_outstanding,
    ft.abc_classification,
    ft.xyz_classification,
    ft.abc_xyz_segment,
    ft.stockout_days,
    ft.overstock_days,
    ft.service_level_pct,
    -- Turnover benchmarking
    CASE
        WHEN ft.inventory_turnover_ratio >= 12 THEN 'Excellent (12+ turns/year)'
        WHEN ft.inventory_turnover_ratio >= 6 THEN 'Good (6-12 turns/year)'
        WHEN ft.inventory_turnover_ratio >= 3 THEN 'Average (3-6 turns/year)'
        WHEN ft.inventory_turnover_ratio >= 1 THEN 'Slow (1-3 turns/year)'
        ELSE 'Dead Stock (<1 turn/year)'
    END AS turnover_category,
    -- DIO benchmarking
    CASE
        WHEN ft.days_inventory_outstanding <= 30 THEN 'Fast Moving'
        WHEN ft.days_inventory_outstanding <= 60 THEN 'Moderate'
        WHEN ft.days_inventory_outstanding <= 90 THEN 'Slow'
        ELSE 'Very Slow / Dead Stock'
    END AS velocity_category
FROM fact_inventory_turnover ft
JOIN dim_product p ON ft.product_sk = p.product_sk
JOIN dim_warehouse w ON ft.warehouse_sk = w.warehouse_sk
LEFT JOIN dim_product_category pc ON ft.category_sk = pc.category_sk
ORDER BY ft.year_month DESC, ft.cost_of_goods_sold DESC;

-- 4.3.2 ABC/XYZ Classification Matrix
CREATE OR REPLACE VIEW vw_abc_xyz_matrix AS
WITH latest_classification AS (
    SELECT
        product_sk,
        warehouse_sk,
        abc_classification,
        xyz_classification,
        abc_xyz_segment,
        cost_of_goods_sold,
        average_inventory_value,
        inventory_turnover_ratio,
        service_level_pct
    FROM fact_inventory_turnover
    WHERE year_month = TO_CHAR(CURRENT_DATE - INTERVAL '1 month', 'YYYY-MM')
)
SELECT
    lc.abc_xyz_segment,
    lc.abc_classification,
    lc.xyz_classification,
    COUNT(DISTINCT lc.product_sk) AS products_count,
    SUM(lc.cost_of_goods_sold) AS total_cogs,
    SUM(lc.average_inventory_value) AS total_inventory_value,
    ROUND(AVG(lc.inventory_turnover_ratio), 2) AS avg_turnover,
    ROUND(AVG(lc.service_level_pct), 1) AS avg_service_level,
    -- Management strategy
    CASE lc.abc_xyz_segment
        WHEN 'AX' THEN 'Continuous review, JIT, minimize safety stock'
        WHEN 'AY' THEN 'Regular review, moderate safety stock'
        WHEN 'AZ' THEN 'Close monitoring, demand analysis needed'
        WHEN 'BX' THEN 'Periodic review, standard safety stock'
        WHEN 'BY' THEN 'Regular review, increase safety stock'
        WHEN 'BZ' THEN 'Reduce variety, simplify'
        WHEN 'CX' THEN 'Minimize attention, auto-replenishment'
        WHEN 'CY' THEN 'Low priority, bulk ordering'
        WHEN 'CZ' THEN 'Consider discontinuation'
        ELSE 'Standard management'
    END AS recommended_strategy,
    -- Reorder policy
    CASE lc.abc_xyz_segment
        WHEN 'AX' THEN 'Fixed order quantity, short intervals'
        WHEN 'AY' THEN 'Fixed period, variable quantity'
        WHEN 'AZ' THEN 'Order on demand'
        WHEN 'BX' THEN 'Economic order quantity'
        WHEN 'BY' THEN 'EOQ with safety stock buffer'
        WHEN 'BZ' THEN 'Min-max system'
        WHEN 'CX' THEN 'Two-bin system'
        WHEN 'CY' THEN 'Periodic review, large orders'
        WHEN 'CZ' THEN 'Make to order only'
        ELSE 'Standard EOQ'
    END AS reorder_policy
FROM latest_classification lc
GROUP BY lc.abc_xyz_segment, lc.abc_classification, lc.xyz_classification
ORDER BY
    CASE lc.abc_classification WHEN 'A' THEN 1 WHEN 'B' THEN 2 ELSE 3 END,
    CASE lc.xyz_classification WHEN 'X' THEN 1 WHEN 'Y' THEN 2 ELSE 3 END;

-- 4.3.3 Dead Stock Analysis
CREATE OR REPLACE VIEW vw_dead_stock_analysis AS
SELECT
    w.warehouse_name,
    p.product_code,
    p.product_name,
    pc.category_name,
    fis.quantity_on_hand,
    fis.total_value,
    fis.days_of_supply,
    -- Last movement analysis
    (
        SELECT MAX(d2.date_actual)
        FROM fact_inventory_transaction fit
        JOIN dim_date d2 ON fit.transaction_date_sk = d2.date_sk
        WHERE fit.product_sk = p.product_sk
          AND fit.warehouse_sk = w.warehouse_sk
    ) AS last_movement_date,
    CURRENT_DATE - (
        SELECT MAX(d2.date_actual)
        FROM fact_inventory_transaction fit
        JOIN dim_date d2 ON fit.transaction_date_sk = d2.date_sk
        WHERE fit.product_sk = p.product_sk
          AND fit.warehouse_sk = w.warehouse_sk
    ) AS days_since_last_movement,
    -- Recommendations
    CASE
        WHEN CURRENT_DATE - (
            SELECT MAX(d2.date_actual)
            FROM fact_inventory_transaction fit
            JOIN dim_date d2 ON fit.transaction_date_sk = d2.date_sk
            WHERE fit.product_sk = p.product_sk
        ) > 180 THEN 'Write-off candidate'
        WHEN CURRENT_DATE - (
            SELECT MAX(d2.date_actual)
            FROM fact_inventory_transaction fit
            JOIN dim_date d2 ON fit.transaction_date_sk = d2.date_sk
            WHERE fit.product_sk = p.product_sk
        ) > 90 THEN 'Clearance sale'
        WHEN CURRENT_DATE - (
            SELECT MAX(d2.date_actual)
            FROM fact_inventory_transaction fit
            JOIN dim_date d2 ON fit.transaction_date_sk = d2.date_sk
            WHERE fit.product_sk = p.product_sk
        ) > 60 THEN 'Promotional markdown'
        ELSE 'Monitor closely'
    END AS recommended_action
FROM fact_daily_inventory_snapshot fis
JOIN dim_product p ON fis.product_sk = p.product_sk
JOIN dim_warehouse w ON fis.warehouse_sk = w.warehouse_sk
LEFT JOIN dim_product_category pc ON p.category_id = pc.category_sk
JOIN dim_date d ON fis.date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
  AND fis.quantity_on_hand > 0
  AND fis.days_of_supply > 90
ORDER BY fis.total_value DESC;
```

### 4.4 Shrinkage and Waste Analytics

```sql
-- =====================================================
-- SHRINKAGE AND WASTE ANALYTICS
-- Loss Prevention and Root Cause Analysis
-- =====================================================

-- 4.4.1 Shrinkage Summary Dashboard
CREATE OR REPLACE VIEW vw_shrinkage_summary AS
SELECT
    d.year_actual,
    d.month_name,
    w.warehouse_name,
    fs.shrinkage_type,
    COUNT(*) AS incidents_count,
    SUM(fs.quantity_lost) AS total_quantity_lost,
    SUM(fs.value_lost) AS total_value_lost,
    SUM(fs.net_loss) AS total_net_loss,
    AVG(fs.value_lost) AS avg_loss_per_incident,
    -- Root cause breakdown
    COUNT(CASE WHEN fs.root_cause_category = 'storage_conditions' THEN 1 END) AS storage_issues,
    COUNT(CASE WHEN fs.root_cause_category = 'handling' THEN 1 END) AS handling_issues,
    COUNT(CASE WHEN fs.root_cause_category = 'forecasting' THEN 1 END) AS forecasting_issues,
    COUNT(CASE WHEN fs.root_cause_category = 'supplier_quality' THEN 1 END) AS supplier_issues,
    -- Preventability
    ROUND(
        COUNT(CASE WHEN fs.preventable THEN 1 END)::DECIMAL / NULLIF(COUNT(*), 0) * 100, 1
    ) AS preventable_pct,
    SUM(CASE WHEN fs.preventable THEN fs.value_lost ELSE 0 END) AS preventable_value_lost
FROM fact_inventory_shrinkage fs
JOIN dim_warehouse w ON fs.warehouse_sk = w.warehouse_sk
JOIN dim_date d ON fs.date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '12 months'
GROUP BY d.year_actual, d.month_name, d.month_of_year, w.warehouse_name, fs.shrinkage_type
ORDER BY d.year_actual DESC, d.month_of_year DESC, total_value_lost DESC;

-- 4.4.2 Shrinkage by Product Analysis
CREATE OR REPLACE VIEW vw_shrinkage_by_product AS
SELECT
    p.product_code,
    p.product_name,
    pc.category_name,
    COUNT(*) AS shrinkage_incidents,
    SUM(fs.quantity_lost) AS total_qty_lost,
    SUM(fs.value_lost) AS total_value_lost,
    -- Compare to sales
    (
        SELECT SUM(fit.quantity)
        FROM fact_inventory_transaction fit
        JOIN dim_inventory_transaction_type itt ON fit.transaction_type_sk = itt.transaction_type_sk
        JOIN dim_date d2 ON fit.transaction_date_sk = d2.date_sk
        WHERE fit.product_sk = p.product_sk
          AND itt.transaction_category = 'outbound'
          AND d2.date_actual >= CURRENT_DATE - INTERVAL '12 months'
    ) AS total_sold_qty,
    -- Shrinkage rate
    ROUND(
        SUM(fs.quantity_lost) /
        NULLIF((
            SELECT SUM(fit.quantity)
            FROM fact_inventory_transaction fit
            JOIN dim_inventory_transaction_type itt ON fit.transaction_type_sk = itt.transaction_type_sk
            JOIN dim_date d2 ON fit.transaction_date_sk = d2.date_sk
            WHERE fit.product_sk = p.product_sk
              AND itt.transaction_category = 'outbound'
              AND d2.date_actual >= CURRENT_DATE - INTERVAL '12 months'
        ), 0) * 100, 2
    ) AS shrinkage_rate_pct,
    -- By type
    SUM(CASE WHEN fs.shrinkage_type = 'expired' THEN fs.value_lost ELSE 0 END) AS expired_loss,
    SUM(CASE WHEN fs.shrinkage_type = 'damaged' THEN fs.value_lost ELSE 0 END) AS damaged_loss,
    SUM(CASE WHEN fs.shrinkage_type = 'theft' THEN fs.value_lost ELSE 0 END) AS theft_loss
FROM fact_inventory_shrinkage fs
JOIN dim_product p ON fs.product_sk = p.product_sk
LEFT JOIN dim_product_category pc ON p.category_id = pc.category_sk
JOIN dim_date d ON fs.date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '12 months'
GROUP BY p.product_sk, p.product_code, p.product_name, pc.category_name
HAVING COUNT(*) >= 3  -- Minimum incidents for pattern analysis
ORDER BY total_value_lost DESC;

-- 4.4.3 Expiration Loss Trend
CREATE OR REPLACE VIEW vw_expiration_loss_trend AS
SELECT
    d.date_actual,
    d.week_of_year,
    d.month_name,
    w.warehouse_name,
    COUNT(*) AS expired_lots_count,
    SUM(fs.quantity_lost) AS expired_quantity,
    SUM(fs.value_lost) AS expired_value,
    -- Rolling metrics
    SUM(SUM(fs.value_lost)) OVER (
        PARTITION BY w.warehouse_sk
        ORDER BY d.date_actual
        ROWS BETWEEN 6 PRECEDING AND CURRENT ROW
    ) AS rolling_7day_loss,
    AVG(SUM(fs.value_lost)) OVER (
        PARTITION BY w.warehouse_sk
        ORDER BY d.date_actual
        ROWS BETWEEN 29 PRECEDING AND CURRENT ROW
    ) AS rolling_30day_avg_daily_loss,
    -- Compare to inventory value
    (
        SELECT SUM(fis.total_value)
        FROM fact_daily_inventory_snapshot fis
        WHERE fis.warehouse_sk = w.warehouse_sk
          AND fis.date_sk = d.date_sk
    ) AS inventory_value_on_date,
    ROUND(
        SUM(fs.value_lost) /
        NULLIF((
            SELECT SUM(fis.total_value)
            FROM fact_daily_inventory_snapshot fis
            WHERE fis.warehouse_sk = w.warehouse_sk
              AND fis.date_sk = d.date_sk
        ), 0) * 100, 4
    ) AS expiration_rate_pct
FROM fact_inventory_shrinkage fs
JOIN dim_warehouse w ON fs.warehouse_sk = w.warehouse_sk
JOIN dim_date d ON fs.date_sk = d.date_sk
WHERE fs.shrinkage_type = 'expired'
  AND d.date_actual >= CURRENT_DATE - INTERVAL '90 days'
GROUP BY d.date_actual, d.date_sk, d.week_of_year, d.month_name,
         w.warehouse_sk, w.warehouse_name
ORDER BY d.date_actual DESC;
```

---

## 5. Daily Development Schedule

### Day 541: Inventory Data Model Design

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 (Backend Lead) | Design inventory analytics star schema | Schema documentation |
| 09:00-12:00 | Dev 2 (Full-Stack) | Review Odoo inventory module structure | Integration analysis |
| 09:00-12:00 | Dev 3 (Frontend Lead) | Design inventory dashboard wireframes | Figma prototypes |
| 13:00-15:00 | Dev 1 | Create warehouse and location dimensions | DDL scripts |
| 13:00-15:00 | Dev 2 | Create lot and transaction type dimensions | DDL scripts |
| 13:00-15:00 | Dev 3 | Design stock level indicators and alerts | UI specifications |
| 15:00-17:00 | All | Schema review and approval | Final schema |

**Day 541 Acceptance Criteria:**
- [ ] All dimension tables created
- [ ] Schema reviewed and approved
- [ ] Dashboard wireframes completed

### Day 542: Fact Tables and Core ETL

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create daily inventory snapshot fact | fact_daily_inventory_snapshot |
| 09:00-12:00 | Dev 2 | Create inventory transaction fact | fact_inventory_transaction |
| 09:00-12:00 | Dev 3 | Create aging and shrinkage facts | fact_inventory_aging, fact_inventory_shrinkage |
| 13:00-15:00 | Dev 1 | Create turnover fact table | fact_inventory_turnover |
| 13:00-15:00 | Dev 2 | Create cold chain monitoring fact | fact_cold_chain_monitoring |
| 13:00-15:00 | Dev 3 | Design ETL DAG architecture | DAG documentation |
| 15:00-17:00 | Dev 1 | Implement stock quant extractor | Python extractor |
| 15:00-17:00 | Dev 2 | Implement stock move extractor | Python extractor |
| 15:00-17:00 | Dev 3 | Implement lot expiration extractor | Python extractor |

**Day 542 Acceptance Criteria:**
- [ ] All fact tables created with indexes
- [ ] Core extractors functional
- [ ] ETL architecture documented

### Day 543: ETL Pipeline Completion

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Implement data transformation layer | Transformer classes |
| 09:00-12:00 | Dev 2 | Implement freshness score calculations | Freshness calculator |
| 09:00-12:00 | Dev 3 | Implement turnover metrics calculator | Turnover calculator |
| 13:00-15:00 | Dev 1 | Implement snapshot loader | Loader class |
| 13:00-15:00 | Dev 2 | Implement transaction loader | Loader class |
| 13:00-15:00 | Dev 3 | Implement shrinkage loader | Loader class |
| 15:00-17:00 | Dev 1 | Configure Airflow DAG scheduling | Scheduled DAG |
| 15:00-17:00 | Dev 2 | Add ETL error handling | Error handling |
| 15:00-17:00 | Dev 3 | Create ETL monitoring dashboard | Monitoring UI |

**Day 543 Acceptance Criteria:**
- [ ] Complete ETL pipeline functional
- [ ] Data transformations validated
- [ ] ETL monitoring operational

### Day 544: Stock Level Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create stock overview KPI view | vw_current_stock_overview |
| 09:00-12:00 | Dev 2 | Create stock trend analysis view | vw_stock_trend_analysis |
| 09:00-12:00 | Dev 3 | Build stock level dashboard in Superset | Stock dashboard |
| 13:00-15:00 | Dev 1 | Create multi-location distribution view | vw_stock_distribution |
| 13:00-15:00 | Dev 2 | Implement reorder point alerts | Alert logic |
| 13:00-15:00 | Dev 3 | Build stock heatmap visualization | Heatmap component |
| 15:00-17:00 | Dev 1 | Add days-of-supply calculations | DOS calculations |
| 15:00-17:00 | Dev 2 | Implement safety stock recommendations | Recommendation engine |
| 15:00-17:00 | Dev 3 | Add dashboard filters and drill-downs | Filter components |

**Day 544 Acceptance Criteria:**
- [ ] Stock level dashboard operational
- [ ] Reorder alerts functional
- [ ] Multi-location visibility complete

### Day 545: Expiration and Freshness Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create expiration risk dashboard view | vw_expiration_risk_dashboard |
| 09:00-12:00 | Dev 2 | Create freshness score summary view | vw_freshness_score_summary |
| 09:00-12:00 | Dev 3 | Build expiration management dashboard | Expiration dashboard |
| 13:00-15:00 | Dev 1 | Create shelf life utilization view | vw_shelf_life_utilization |
| 13:00-15:00 | Dev 2 | Implement FEFO compliance tracking | FEFO logic |
| 13:00-15:00 | Dev 3 | Build freshness visualization | Freshness charts |
| 15:00-17:00 | Dev 1 | Create expiration alerts system | Alert configuration |
| 15:00-17:00 | Dev 2 | Add aging bucket analysis | Aging analysis |
| 15:00-17:00 | Dev 3 | Build lot-level drill-down | Drill-down UI |

**Day 545 Acceptance Criteria:**
- [ ] Expiration dashboard complete
- [ ] Freshness scoring operational
- [ ] FEFO compliance tracking active

### Day 546: Turnover and Classification Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create turnover performance view | vw_turnover_performance |
| 09:00-12:00 | Dev 2 | Implement ABC classification logic | ABC calculator |
| 09:00-12:00 | Dev 3 | Build turnover analytics dashboard | Turnover dashboard |
| 13:00-15:00 | Dev 1 | Create ABC/XYZ matrix view | vw_abc_xyz_matrix |
| 13:00-15:00 | Dev 2 | Implement XYZ classification logic | XYZ calculator |
| 13:00-15:00 | Dev 3 | Build classification matrix visualization | Matrix chart |
| 15:00-17:00 | Dev 1 | Create dead stock analysis view | vw_dead_stock_analysis |
| 15:00-17:00 | Dev 2 | Add management strategy recommendations | Strategy engine |
| 15:00-17:00 | Dev 3 | Build dead stock actionable list | Action list UI |

**Day 546 Acceptance Criteria:**
- [ ] Turnover metrics calculated correctly
- [ ] ABC/XYZ classification operational
- [ ] Dead stock identification complete

### Day 547: Shrinkage and Waste Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create shrinkage summary view | vw_shrinkage_summary |
| 09:00-12:00 | Dev 2 | Create shrinkage by product view | vw_shrinkage_by_product |
| 09:00-12:00 | Dev 3 | Build waste analytics dashboard | Waste dashboard |
| 13:00-15:00 | Dev 1 | Create expiration loss trend view | vw_expiration_loss_trend |
| 13:00-15:00 | Dev 2 | Implement root cause analysis | RCA logic |
| 13:00-15:00 | Dev 3 | Build shrinkage trend visualizations | Trend charts |
| 15:00-17:00 | Dev 1 | Add preventability analysis | Analysis queries |
| 15:00-17:00 | Dev 2 | Create corrective action tracking | Action tracking |
| 15:00-17:00 | Dev 3 | Build loss prevention insights UI | Insights panel |

**Day 547 Acceptance Criteria:**
- [ ] Shrinkage tracking operational
- [ ] Root cause analysis functional
- [ ] Loss prevention insights available

### Day 548: Cold Chain and Integration

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create cold chain monitoring views | Cold chain views |
| 09:00-12:00 | Dev 2 | Implement temperature excursion detection | Excursion detection |
| 09:00-12:00 | Dev 3 | Build cold chain dashboard | Cold chain dashboard |
| 13:00-15:00 | Dev 1 | Create inventory API endpoints | REST API |
| 13:00-15:00 | Dev 2 | Integrate with Odoo inventory module | Odoo integration |
| 13:00-15:00 | Dev 3 | Add export functionality | Export features |
| 15:00-17:00 | Dev 1 | Implement real-time stock updates | Real-time sync |
| 15:00-17:00 | Dev 2 | Add webhook notifications | Webhook setup |
| 15:00-17:00 | Dev 3 | Mobile-responsive optimization | Mobile CSS |

**Day 548 Acceptance Criteria:**
- [ ] Cold chain monitoring active
- [ ] API endpoints functional
- [ ] Odoo integration complete

### Day 549: Optimization and Performance

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Query performance optimization | Optimized queries |
| 09:00-12:00 | Dev 2 | Add materialized view scheduling | MV refresh jobs |
| 09:00-12:00 | Dev 3 | Implement dashboard caching | Redis caching |
| 13:00-15:00 | Dev 1 | Index optimization | Index tuning |
| 13:00-15:00 | Dev 2 | Add data quality checks | DQ validations |
| 13:00-15:00 | Dev 3 | Add dashboard performance monitoring | Performance metrics |
| 15:00-17:00 | All | Load testing and optimization | Performance report |

**Day 549 Acceptance Criteria:**
- [ ] All dashboards load within 3 seconds
- [ ] Queries optimized
- [ ] Caching implemented

### Day 550: Testing and Documentation

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Unit tests for ETL components | 90%+ coverage |
| 09:00-12:00 | Dev 2 | Integration tests | Integration test suite |
| 09:00-12:00 | Dev 3 | UI/UX testing | Bug fixes |
| 13:00-15:00 | Dev 1 | Data accuracy validation | Validation report |
| 13:00-15:00 | Dev 2 | Edge case testing | Edge case coverage |
| 13:00-15:00 | Dev 3 | User acceptance testing support | UAT support |
| 15:00-17:00 | All | Documentation and sign-off | Technical docs |

**Day 550 Acceptance Criteria:**
- [ ] All tests passing
- [ ] Documentation complete
- [ ] Stakeholder sign-off obtained

---

## 6. Requirement Traceability Matrix

| Requirement ID | Requirement Description | Implementation | Test Case |
|----------------|------------------------|----------------|-----------|
| RFP-INV-001 | Real-time inventory visibility | vw_current_stock_overview, Stock Dashboard | TC-105-001 |
| RFP-INV-002 | Perishable goods lifecycle | Expiration Dashboard, Freshness Scoring | TC-105-002 |
| BRD-OPS-007 | Inventory optimization | ABC/XYZ Classification, Turnover Analysis | TC-105-003 |
| SRS-ANALYTICS-020 | Inventory turnover and ABC | fact_inventory_turnover, vw_abc_xyz_matrix | TC-105-004 |
| SRS-ANALYTICS-021 | Demand forecasting | Reorder Point Calculations, Safety Stock | TC-105-005 |
| SRS-ANALYTICS-022 | Shrinkage tracking | fact_inventory_shrinkage, Waste Dashboard | TC-105-006 |
| SRS-ANALYTICS-023 | FEFO compliance | vw_expiration_risk_dashboard, FEFO Logic | TC-105-007 |
| SRS-ANALYTICS-024 | Cold chain monitoring | fact_cold_chain_monitoring, Excursion Alerts | TC-105-008 |

---

## 7. Risk Register

| Risk ID | Description | Probability | Impact | Mitigation |
|---------|-------------|-------------|--------|------------|
| R105-001 | Incomplete lot tracking in source | Medium | High | Data gap analysis, manual lot assignment workflow |
| R105-002 | High volume of transactions | Medium | Medium | Partitioning, incremental processing |
| R105-003 | Temperature sensor data gaps | Medium | Medium | Interpolation, manual override capability |
| R105-004 | Complex multi-location transfers | Low | Medium | Detailed transfer tracking logic |
| R105-005 | ABC/XYZ misclassification | Low | Medium | Regular recalculation, manual override |

---

## 8. Quality Assurance Checklist

### 8.1 Data Quality
- [ ] Stock levels match Odoo source
- [ ] Expiration dates accurately tracked
- [ ] Transaction history complete
- [ ] Lot traceability verified

### 8.2 Performance
- [ ] Dashboard load time < 3 seconds
- [ ] ETL completes within window
- [ ] API response time < 500ms
- [ ] Concurrent user testing passed

### 8.3 Functionality
- [ ] All KPIs calculating correctly
- [ ] Alerts triggering appropriately
- [ ] Exports generating correctly
- [ ] Mobile views functional

---

## 9. Appendices

### Appendix A: Glossary

| Term | Definition |
|------|------------|
| FEFO | First Expired, First Out - inventory picking method |
| ABC Analysis | Classification by value contribution |
| XYZ Analysis | Classification by demand variability |
| DIO | Days Inventory Outstanding |
| Safety Stock | Buffer inventory to prevent stockouts |
| Reorder Point | Inventory level triggering replenishment |
| Shrinkage | Inventory loss from any cause |

### Appendix B: Reference Documents

- Phase_11_Index_Executive_Summary.md
- Milestone_101_BI_Platform_Setup.md
- Smart Dairy RFP Document
- Odoo Inventory Module Documentation

---

**Document End**

*Milestone 105: Inventory Analytics Module - Days 541-550*
