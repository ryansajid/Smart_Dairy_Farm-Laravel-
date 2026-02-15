# Milestone 107: Financial Analytics Module

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | PH11-M107-FINANCIAL-ANALYTICS |
| **Version** | 1.0 |
| **Author** | Senior Technical Architect |
| **Created** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Final |
| **Classification** | Internal Use |
| **Parent Phase** | Phase 11 - Commerce Advanced Analytics |
| **Timeline** | Days 561-570 |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 107 delivers a comprehensive Financial Analytics Module that provides deep insights into the organization's financial performance. This module implements P&L analysis, balance sheet analytics, cash flow forecasting, budget variance analysis, and financial KPIs. It enables CFO-level decision making with real-time financial visibility, trend analysis, and predictive capabilities.

### 1.2 Business Value Proposition

| Value Area | Expected Outcome | Measurement |
|------------|------------------|-------------|
| Financial Visibility | Real-time P&L and balance sheet | Data freshness < 24 hours |
| Cash Management | 25% improvement in cash forecasting | Forecast accuracy |
| Cost Control | 15% reduction in unplanned expenses | Budget variance |
| Working Capital | 20% improvement in DSO/DPO | Working capital metrics |
| Decision Speed | 50% faster financial close | Close cycle time |

### 1.3 Strategic Alignment

This milestone directly supports:
- **RFP-FIN-001**: Real-time financial reporting
- **RFP-FIN-002**: Budget vs actual analysis
- **BRD-FIN-001**: CFO dashboard and KPIs
- **SRS-ANALYTICS-030**: Profit and Loss analytics
- **SRS-ANALYTICS-031**: Cash flow forecasting
- **SRS-ANALYTICS-032**: Financial ratio analysis

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

| Category | Deliverable | Priority |
|----------|-------------|----------|
| P&L Analytics | Revenue analysis by dimension | P0 |
| P&L Analytics | Cost structure analysis | P0 |
| P&L Analytics | Margin analysis (gross, operating, net) | P0 |
| P&L Analytics | Period comparison (MoM, YoY) | P0 |
| Balance Sheet | Asset composition analysis | P1 |
| Balance Sheet | Liability structure | P1 |
| Balance Sheet | Working capital analytics | P0 |
| Cash Flow | Cash flow statement analytics | P0 |
| Cash Flow | Cash flow forecasting | P1 |
| Cash Flow | Liquidity analysis | P1 |
| Budgeting | Budget vs actual variance | P0 |
| Budgeting | Forecast vs actual | P0 |
| Budgeting | Cost center analysis | P1 |
| AR/AP Analytics | Receivables aging analysis | P0 |
| AR/AP Analytics | Payables aging analysis | P0 |
| AR/AP Analytics | Collection efficiency | P1 |
| Financial Ratios | Liquidity ratios | P0 |
| Financial Ratios | Profitability ratios | P0 |
| Financial Ratios | Efficiency ratios | P1 |

### 2.2 Out-of-Scope Items

- Statutory financial report generation
- Tax calculation and filing
- Audit trail system replacement
- Multi-GAAP consolidation
- External financial system integration

---

## 3. Technical Architecture

### 3.1 Financial Analytics Data Model

```sql
-- =====================================================
-- FINANCIAL ANALYTICS STAR SCHEMA
-- Milestone 107 - Days 561-570
-- =====================================================

-- =====================================================
-- DIMENSION TABLES
-- =====================================================

-- Chart of Accounts Dimension
CREATE TABLE dim_account (
    account_sk SERIAL PRIMARY KEY,
    account_id INTEGER NOT NULL,
    account_code VARCHAR(20) UNIQUE NOT NULL,
    account_name VARCHAR(200) NOT NULL,
    account_type VARCHAR(50), -- 'asset', 'liability', 'equity', 'income', 'expense'
    account_category VARCHAR(50), -- More detailed classification
    parent_account_sk INTEGER REFERENCES dim_account(account_sk),
    account_level INTEGER,
    account_path VARCHAR(500),
    is_reconcilable BOOLEAN DEFAULT FALSE,
    is_bank_account BOOLEAN DEFAULT FALSE,
    is_cash_account BOOLEAN DEFAULT FALSE,
    normal_balance VARCHAR(10), -- 'debit', 'credit'
    currency_code VARCHAR(3) DEFAULT 'USD',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_dim_account_type ON dim_account(account_type);
CREATE INDEX idx_dim_account_category ON dim_account(account_category);

-- Cost Center Dimension
CREATE TABLE dim_cost_center (
    cost_center_sk SERIAL PRIMARY KEY,
    cost_center_id INTEGER NOT NULL,
    cost_center_code VARCHAR(20) UNIQUE NOT NULL,
    cost_center_name VARCHAR(100) NOT NULL,
    cost_center_type VARCHAR(30), -- 'department', 'project', 'location', 'product_line'
    parent_cost_center_sk INTEGER REFERENCES dim_cost_center(cost_center_sk),
    manager_name VARCHAR(100),
    budget_owner VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Budget Dimension
CREATE TABLE dim_budget (
    budget_sk SERIAL PRIMARY KEY,
    budget_id INTEGER NOT NULL,
    budget_code VARCHAR(30) UNIQUE NOT NULL,
    budget_name VARCHAR(200) NOT NULL,
    budget_type VARCHAR(30), -- 'operating', 'capital', 'cash', 'sales'
    fiscal_year INTEGER NOT NULL,
    budget_period VARCHAR(20), -- 'annual', 'quarterly', 'monthly'
    version VARCHAR(20), -- 'original', 'revised_q1', 'revised_q2', etc.
    status VARCHAR(20), -- 'draft', 'approved', 'locked'
    approved_date DATE,
    approved_by VARCHAR(100),
    total_budget_amount DECIMAL(16,2),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Fiscal Period Dimension
CREATE TABLE dim_fiscal_period (
    fiscal_period_sk SERIAL PRIMARY KEY,
    fiscal_year INTEGER NOT NULL,
    fiscal_quarter INTEGER NOT NULL,
    fiscal_month INTEGER NOT NULL,
    fiscal_week INTEGER,
    period_name VARCHAR(50), -- 'FY2026-Q1-M01'
    period_start_date DATE NOT NULL,
    period_end_date DATE NOT NULL,
    is_closed BOOLEAN DEFAULT FALSE,
    closed_date TIMESTAMP,
    days_in_period INTEGER,
    is_current_period BOOLEAN DEFAULT FALSE,
    UNIQUE(fiscal_year, fiscal_month)
);

-- =====================================================
-- FACT TABLES
-- =====================================================

-- General Ledger Fact (Journal Entry Lines)
CREATE TABLE fact_general_ledger (
    gl_sk BIGSERIAL PRIMARY KEY,
    posting_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    account_sk INTEGER NOT NULL REFERENCES dim_account(account_sk),
    cost_center_sk INTEGER REFERENCES dim_cost_center(cost_center_sk),
    fiscal_period_sk INTEGER REFERENCES dim_fiscal_period(fiscal_period_sk),

    -- Journal Entry Reference
    journal_entry_id INTEGER NOT NULL,
    journal_entry_number VARCHAR(50),
    journal_type VARCHAR(30), -- 'sale', 'purchase', 'cash', 'general', 'closing'

    -- Amount
    debit_amount DECIMAL(16,2) DEFAULT 0,
    credit_amount DECIMAL(16,2) DEFAULT 0,
    balance_amount DECIMAL(16,2) GENERATED ALWAYS AS (debit_amount - credit_amount) STORED,
    currency_code VARCHAR(3) DEFAULT 'USD',
    amount_currency DECIMAL(16,2), -- Amount in original currency
    exchange_rate DECIMAL(12,6) DEFAULT 1,

    -- Description
    line_description TEXT,
    reference VARCHAR(100),
    source_document VARCHAR(100),

    -- Related Entities
    partner_sk INTEGER,
    product_sk INTEGER,

    -- Reconciliation
    is_reconciled BOOLEAN DEFAULT FALSE,
    reconcile_id INTEGER,

    -- Audit
    created_by INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_gl_date ON fact_general_ledger(posting_date_sk);
CREATE INDEX idx_fact_gl_account ON fact_general_ledger(account_sk);
CREATE INDEX idx_fact_gl_cost_center ON fact_general_ledger(cost_center_sk);
CREATE INDEX idx_fact_gl_period ON fact_general_ledger(fiscal_period_sk);

-- Trial Balance Fact (Period Snapshot)
CREATE TABLE fact_trial_balance (
    trial_balance_sk BIGSERIAL PRIMARY KEY,
    fiscal_period_sk INTEGER NOT NULL REFERENCES dim_fiscal_period(fiscal_period_sk),
    account_sk INTEGER NOT NULL REFERENCES dim_account(account_sk),
    cost_center_sk INTEGER REFERENCES dim_cost_center(cost_center_sk),

    -- Balances
    opening_debit DECIMAL(16,2) DEFAULT 0,
    opening_credit DECIMAL(16,2) DEFAULT 0,
    opening_balance DECIMAL(16,2) DEFAULT 0,
    period_debit DECIMAL(16,2) DEFAULT 0,
    period_credit DECIMAL(16,2) DEFAULT 0,
    period_movement DECIMAL(16,2) DEFAULT 0,
    closing_debit DECIMAL(16,2) DEFAULT 0,
    closing_credit DECIMAL(16,2) DEFAULT 0,
    closing_balance DECIMAL(16,2) DEFAULT 0,

    -- YTD
    ytd_debit DECIMAL(16,2) DEFAULT 0,
    ytd_credit DECIMAL(16,2) DEFAULT 0,
    ytd_balance DECIMAL(16,2) DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(fiscal_period_sk, account_sk, cost_center_sk)
);

CREATE INDEX idx_fact_tb_period ON fact_trial_balance(fiscal_period_sk);
CREATE INDEX idx_fact_tb_account ON fact_trial_balance(account_sk);

-- Budget Fact
CREATE TABLE fact_budget (
    budget_fact_sk BIGSERIAL PRIMARY KEY,
    budget_sk INTEGER NOT NULL REFERENCES dim_budget(budget_sk),
    fiscal_period_sk INTEGER NOT NULL REFERENCES dim_fiscal_period(fiscal_period_sk),
    account_sk INTEGER NOT NULL REFERENCES dim_account(account_sk),
    cost_center_sk INTEGER REFERENCES dim_cost_center(cost_center_sk),

    -- Budget Amounts
    budget_amount DECIMAL(16,2) NOT NULL,
    budget_quantity DECIMAL(12,2),

    -- Actual (populated by ETL)
    actual_amount DECIMAL(16,2),
    actual_quantity DECIMAL(12,2),

    -- Variance
    variance_amount DECIMAL(16,2) GENERATED ALWAYS AS
        (COALESCE(actual_amount, 0) - budget_amount) STORED,
    variance_percentage DECIMAL(8,2),

    -- Forecast (rolling)
    forecast_amount DECIMAL(16,2),
    forecast_variance DECIMAL(16,2),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(budget_sk, fiscal_period_sk, account_sk, cost_center_sk)
);

CREATE INDEX idx_fact_budget_period ON fact_budget(fiscal_period_sk);
CREATE INDEX idx_fact_budget_account ON fact_budget(account_sk);

-- Accounts Receivable Aging Fact
CREATE TABLE fact_ar_aging (
    ar_aging_sk BIGSERIAL PRIMARY KEY,
    snapshot_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    customer_sk INTEGER NOT NULL REFERENCES dim_customer(customer_sk),
    invoice_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),

    -- Invoice Details
    invoice_id INTEGER NOT NULL,
    invoice_number VARCHAR(50),
    invoice_date DATE,
    due_date DATE,

    -- Amounts
    original_amount DECIMAL(14,2),
    paid_amount DECIMAL(14,2) DEFAULT 0,
    balance_amount DECIMAL(14,2),
    currency_code VARCHAR(3) DEFAULT 'USD',

    -- Aging
    days_outstanding INTEGER,
    aging_bucket VARCHAR(20), -- 'Current', '1-30', '31-60', '61-90', '91-120', '120+'

    -- Aging Bucket Amounts
    current_amount DECIMAL(14,2) DEFAULT 0,
    bucket_1_30 DECIMAL(14,2) DEFAULT 0,
    bucket_31_60 DECIMAL(14,2) DEFAULT 0,
    bucket_61_90 DECIMAL(14,2) DEFAULT 0,
    bucket_91_120 DECIMAL(14,2) DEFAULT 0,
    bucket_over_120 DECIMAL(14,2) DEFAULT 0,

    -- Collection Status
    collection_status VARCHAR(20), -- 'normal', 'follow_up', 'dispute', 'legal', 'write_off'
    last_payment_date DATE,
    last_contact_date DATE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_ar_date ON fact_ar_aging(snapshot_date_sk);
CREATE INDEX idx_fact_ar_customer ON fact_ar_aging(customer_sk);
CREATE INDEX idx_fact_ar_bucket ON fact_ar_aging(aging_bucket);

-- Accounts Payable Aging Fact
CREATE TABLE fact_ap_aging (
    ap_aging_sk BIGSERIAL PRIMARY KEY,
    snapshot_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    supplier_sk INTEGER NOT NULL REFERENCES dim_supplier(supplier_sk),
    invoice_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),

    -- Invoice Details
    invoice_id INTEGER NOT NULL,
    invoice_number VARCHAR(50),
    invoice_date DATE,
    due_date DATE,

    -- Amounts
    original_amount DECIMAL(14,2),
    paid_amount DECIMAL(14,2) DEFAULT 0,
    balance_amount DECIMAL(14,2),
    currency_code VARCHAR(3) DEFAULT 'USD',

    -- Aging
    days_outstanding INTEGER,
    aging_bucket VARCHAR(20),

    -- Aging Bucket Amounts
    current_amount DECIMAL(14,2) DEFAULT 0,
    bucket_1_30 DECIMAL(14,2) DEFAULT 0,
    bucket_31_60 DECIMAL(14,2) DEFAULT 0,
    bucket_61_90 DECIMAL(14,2) DEFAULT 0,
    bucket_91_120 DECIMAL(14,2) DEFAULT 0,
    bucket_over_120 DECIMAL(14,2) DEFAULT 0,

    -- Payment Terms
    payment_terms VARCHAR(50),
    early_payment_discount_pct DECIMAL(5,2),
    discount_due_date DATE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_ap_date ON fact_ap_aging(snapshot_date_sk);
CREATE INDEX idx_fact_ap_supplier ON fact_ap_aging(supplier_sk);

-- Cash Flow Fact
CREATE TABLE fact_cash_flow (
    cash_flow_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    account_sk INTEGER NOT NULL REFERENCES dim_account(account_sk), -- Cash/Bank account

    -- Cash Position
    opening_balance DECIMAL(16,2),
    closing_balance DECIMAL(16,2),

    -- Operating Activities
    cash_from_customers DECIMAL(16,2) DEFAULT 0,
    cash_to_suppliers DECIMAL(16,2) DEFAULT 0,
    cash_to_employees DECIMAL(16,2) DEFAULT 0,
    other_operating_cash DECIMAL(16,2) DEFAULT 0,
    net_operating_cash_flow DECIMAL(16,2),

    -- Investing Activities
    capex_payments DECIMAL(16,2) DEFAULT 0,
    asset_sale_proceeds DECIMAL(16,2) DEFAULT 0,
    investment_purchases DECIMAL(16,2) DEFAULT 0,
    investment_proceeds DECIMAL(16,2) DEFAULT 0,
    net_investing_cash_flow DECIMAL(16,2),

    -- Financing Activities
    loan_proceeds DECIMAL(16,2) DEFAULT 0,
    loan_repayments DECIMAL(16,2) DEFAULT 0,
    equity_issued DECIMAL(16,2) DEFAULT 0,
    dividends_paid DECIMAL(16,2) DEFAULT 0,
    net_financing_cash_flow DECIMAL(16,2),

    -- Net Change
    net_cash_change DECIMAL(16,2),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(date_sk, account_sk)
);

CREATE INDEX idx_fact_cash_date ON fact_cash_flow(date_sk);

-- Financial Ratios Fact (Monthly calculation)
CREATE TABLE fact_financial_ratios (
    ratio_sk BIGSERIAL PRIMARY KEY,
    fiscal_period_sk INTEGER NOT NULL REFERENCES dim_fiscal_period(fiscal_period_sk),
    calculation_date DATE NOT NULL,

    -- Liquidity Ratios
    current_ratio DECIMAL(8,4),
    quick_ratio DECIMAL(8,4),
    cash_ratio DECIMAL(8,4),
    working_capital DECIMAL(16,2),

    -- Profitability Ratios
    gross_margin_pct DECIMAL(8,4),
    operating_margin_pct DECIMAL(8,4),
    net_margin_pct DECIMAL(8,4),
    return_on_assets DECIMAL(8,4),
    return_on_equity DECIMAL(8,4),
    ebitda_margin_pct DECIMAL(8,4),

    -- Efficiency Ratios
    asset_turnover DECIMAL(8,4),
    inventory_turnover DECIMAL(8,4),
    receivables_turnover DECIMAL(8,4),
    payables_turnover DECIMAL(8,4),
    days_sales_outstanding DECIMAL(8,2),
    days_payables_outstanding DECIMAL(8,2),
    days_inventory_outstanding DECIMAL(8,2),
    cash_conversion_cycle DECIMAL(8,2),

    -- Leverage Ratios
    debt_to_equity DECIMAL(8,4),
    debt_to_assets DECIMAL(8,4),
    interest_coverage DECIMAL(8,4),

    -- Component Values (for drill-down)
    total_revenue DECIMAL(16,2),
    gross_profit DECIMAL(16,2),
    operating_income DECIMAL(16,2),
    net_income DECIMAL(16,2),
    total_assets DECIMAL(16,2),
    total_liabilities DECIMAL(16,2),
    total_equity DECIMAL(16,2),
    current_assets DECIMAL(16,2),
    current_liabilities DECIMAL(16,2),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(fiscal_period_sk)
);

CREATE INDEX idx_fact_ratios_period ON fact_financial_ratios(fiscal_period_sk);
```

### 3.2 Financial Analytics ETL Pipeline

```python
# airflow/dags/financial_analytics_etl.py
"""
Financial Analytics ETL Pipeline
Milestone 107 - Days 561-570
"""

from datetime import datetime, timedelta
from airflow import DAG
from airflow.operators.python import PythonOperator
from airflow.providers.postgres.hooks.postgres import PostgresHook
import pandas as pd
import numpy as np
from typing import Dict, List, Optional
from decimal import Decimal
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
    'financial_analytics_etl',
    default_args=default_args,
    description='Daily ETL for financial analytics',
    schedule_interval='0 6 * * *',  # Run at 6 AM daily
    start_date=datetime(2026, 1, 1),
    catchup=False,
    tags=['financial', 'analytics', 'etl', 'phase11'],
)


class FinancialDataExtractor:
    """Extracts financial data from Odoo accounting module."""

    def __init__(self, source_conn_id: str = 'odoo_postgres'):
        self.source_hook = PostgresHook(postgres_conn_id=source_conn_id)

    def extract_journal_entries(self, execution_date: datetime) -> pd.DataFrame:
        """Extract journal entry lines for the target date."""
        query = """
            SELECT
                aml.id,
                aml.date AS posting_date,
                am.id AS journal_entry_id,
                am.name AS journal_entry_number,
                aj.type AS journal_type,
                aa.id AS account_id,
                aa.code AS account_code,
                aa.name AS account_name,
                aat.type AS account_type,
                aml.analytic_distribution,
                aml.debit,
                aml.credit,
                aml.balance,
                aml.amount_currency,
                aml.currency_id,
                rc.name AS currency_code,
                aml.name AS line_description,
                aml.ref AS reference,
                am.ref AS source_document,
                aml.partner_id,
                rp.name AS partner_name,
                aml.product_id,
                aml.reconciled AS is_reconciled,
                aml.full_reconcile_id AS reconcile_id,
                aml.create_uid AS created_by
            FROM account_move_line aml
            JOIN account_move am ON aml.move_id = am.id
            JOIN account_journal aj ON am.journal_id = aj.id
            JOIN account_account aa ON aml.account_id = aa.id
            JOIN account_account_type aat ON aa.account_type = aat.id
            LEFT JOIN res_currency rc ON aml.currency_id = rc.id
            LEFT JOIN res_partner rp ON aml.partner_id = rp.id
            WHERE aml.date = %(target_date)s
              AND am.state = 'posted'
            ORDER BY aml.id
        """
        return self.source_hook.get_pandas_df(
            query,
            parameters={'target_date': execution_date.date()}
        )

    def extract_account_balances(self, as_of_date: datetime) -> pd.DataFrame:
        """Extract account balances as of a date."""
        query = """
            SELECT
                aa.id AS account_id,
                aa.code AS account_code,
                aa.name AS account_name,
                aat.type AS account_type,
                SUM(aml.debit) AS total_debit,
                SUM(aml.credit) AS total_credit,
                SUM(aml.balance) AS balance
            FROM account_move_line aml
            JOIN account_move am ON aml.move_id = am.id
            JOIN account_account aa ON aml.account_id = aa.id
            JOIN account_account_type aat ON aa.account_type = aat.id
            WHERE aml.date <= %(as_of_date)s
              AND am.state = 'posted'
            GROUP BY aa.id, aa.code, aa.name, aat.type
            ORDER BY aa.code
        """
        return self.source_hook.get_pandas_df(
            query,
            parameters={'as_of_date': as_of_date.date()}
        )

    def extract_open_invoices_ar(self, as_of_date: datetime) -> pd.DataFrame:
        """Extract open customer invoices for AR aging."""
        query = """
            SELECT
                ai.id AS invoice_id,
                ai.name AS invoice_number,
                ai.invoice_date,
                ai.invoice_date_due AS due_date,
                rp.id AS customer_id,
                rp.name AS customer_name,
                ai.amount_total AS original_amount,
                ai.amount_residual AS balance_amount,
                ai.amount_total - ai.amount_residual AS paid_amount,
                ai.currency_id,
                rc.name AS currency_code,
                ai.payment_state,
                (%(as_of_date)s::DATE - ai.invoice_date_due::DATE) AS days_overdue
            FROM account_move ai
            JOIN res_partner rp ON ai.partner_id = rp.id
            LEFT JOIN res_currency rc ON ai.currency_id = rc.id
            WHERE ai.move_type IN ('out_invoice', 'out_refund')
              AND ai.state = 'posted'
              AND ai.payment_state IN ('not_paid', 'partial')
              AND ai.invoice_date <= %(as_of_date)s
            ORDER BY ai.invoice_date
        """
        return self.source_hook.get_pandas_df(
            query,
            parameters={'as_of_date': as_of_date.date()}
        )

    def extract_open_invoices_ap(self, as_of_date: datetime) -> pd.DataFrame:
        """Extract open vendor bills for AP aging."""
        query = """
            SELECT
                ai.id AS invoice_id,
                ai.name AS invoice_number,
                ai.invoice_date,
                ai.invoice_date_due AS due_date,
                rp.id AS supplier_id,
                rp.name AS supplier_name,
                ai.amount_total AS original_amount,
                ai.amount_residual AS balance_amount,
                ai.amount_total - ai.amount_residual AS paid_amount,
                rc.name AS currency_code,
                ai.payment_state,
                (%(as_of_date)s::DATE - ai.invoice_date_due::DATE) AS days_overdue
            FROM account_move ai
            JOIN res_partner rp ON ai.partner_id = rp.id
            LEFT JOIN res_currency rc ON ai.currency_id = rc.id
            WHERE ai.move_type IN ('in_invoice', 'in_refund')
              AND ai.state = 'posted'
              AND ai.payment_state IN ('not_paid', 'partial')
              AND ai.invoice_date <= %(as_of_date)s
            ORDER BY ai.invoice_date
        """
        return self.source_hook.get_pandas_df(
            query,
            parameters={'as_of_date': as_of_date.date()}
        )

    def extract_budget_data(self, fiscal_year: int) -> pd.DataFrame:
        """Extract budget data for the fiscal year."""
        query = """
            SELECT
                cb.id AS budget_id,
                cb.name AS budget_name,
                cbl.date_from,
                cbl.date_to,
                aa.id AS account_id,
                aa.code AS account_code,
                ad.id AS analytic_account_id,
                ad.name AS cost_center_name,
                cbl.planned_amount AS budget_amount,
                cbl.practical_amount AS actual_amount
            FROM crossovered_budget cb
            JOIN crossovered_budget_lines cbl ON cb.id = cbl.crossovered_budget_id
            JOIN account_account aa ON cbl.general_budget_id = aa.id
            LEFT JOIN account_analytic_account ad ON cbl.analytic_account_id = ad.id
            WHERE cb.state = 'validate'
              AND EXTRACT(YEAR FROM cbl.date_from) = %(fiscal_year)s
            ORDER BY cbl.date_from, aa.code
        """
        return self.source_hook.get_pandas_df(
            query,
            parameters={'fiscal_year': fiscal_year}
        )


class FinancialMetricsCalculator:
    """Calculates financial metrics and ratios."""

    @staticmethod
    def calculate_aging_buckets(days_overdue: int) -> str:
        """Determine aging bucket based on days overdue."""
        if days_overdue <= 0:
            return 'Current'
        elif days_overdue <= 30:
            return '1-30'
        elif days_overdue <= 60:
            return '31-60'
        elif days_overdue <= 90:
            return '61-90'
        elif days_overdue <= 120:
            return '91-120'
        else:
            return '120+'

    @staticmethod
    def calculate_financial_ratios(
        balances: pd.DataFrame,
        income_statement: pd.DataFrame
    ) -> Dict[str, float]:
        """Calculate financial ratios from balance sheet and P&L data."""

        ratios = {}

        # Extract balance sheet components
        current_assets = balances[
            balances['account_type'].isin(['receivable', 'bank', 'cash', 'current_assets'])
        ]['balance'].sum()

        current_liabilities = balances[
            balances['account_type'].isin(['payable', 'current_liabilities'])
        ]['balance'].abs().sum()

        inventory = balances[
            balances['account_type'] == 'stock'
        ]['balance'].sum()

        cash = balances[
            balances['account_type'].isin(['bank', 'cash'])
        ]['balance'].sum()

        total_assets = balances[
            balances['account_type'].str.contains('asset', na=False)
        ]['balance'].sum()

        total_liabilities = balances[
            balances['account_type'].str.contains('liability|payable', na=False)
        ]['balance'].abs().sum()

        total_equity = balances[
            balances['account_type'].str.contains('equity', na=False)
        ]['balance'].sum()

        # Liquidity Ratios
        ratios['current_ratio'] = current_assets / current_liabilities if current_liabilities else None
        ratios['quick_ratio'] = (current_assets - inventory) / current_liabilities if current_liabilities else None
        ratios['cash_ratio'] = cash / current_liabilities if current_liabilities else None
        ratios['working_capital'] = current_assets - current_liabilities

        # Extract P&L components
        if not income_statement.empty:
            revenue = income_statement[
                income_statement['account_type'] == 'income'
            ]['balance'].abs().sum()

            cogs = income_statement[
                income_statement['account_type'] == 'expense_direct_cost'
            ]['balance'].sum()

            operating_expenses = income_statement[
                income_statement['account_type'] == 'expense'
            ]['balance'].sum()

            gross_profit = revenue - cogs
            operating_income = gross_profit - operating_expenses
            net_income = revenue - cogs - operating_expenses  # Simplified

            # Profitability Ratios
            ratios['gross_margin_pct'] = (gross_profit / revenue * 100) if revenue else None
            ratios['operating_margin_pct'] = (operating_income / revenue * 100) if revenue else None
            ratios['net_margin_pct'] = (net_income / revenue * 100) if revenue else None
            ratios['return_on_assets'] = (net_income / total_assets * 100) if total_assets else None
            ratios['return_on_equity'] = (net_income / total_equity * 100) if total_equity else None

            # Store component values
            ratios['total_revenue'] = revenue
            ratios['gross_profit'] = gross_profit
            ratios['operating_income'] = operating_income
            ratios['net_income'] = net_income

        ratios['total_assets'] = total_assets
        ratios['total_liabilities'] = total_liabilities
        ratios['total_equity'] = total_equity
        ratios['current_assets'] = current_assets
        ratios['current_liabilities'] = current_liabilities

        # Leverage Ratios
        ratios['debt_to_equity'] = total_liabilities / total_equity if total_equity else None
        ratios['debt_to_assets'] = total_liabilities / total_assets if total_assets else None

        return ratios

    @staticmethod
    def calculate_working_capital_metrics(
        ar_aging: pd.DataFrame,
        ap_aging: pd.DataFrame,
        revenue_30d: float,
        cogs_30d: float
    ) -> Dict[str, float]:
        """Calculate working capital efficiency metrics."""

        metrics = {}

        # Days Sales Outstanding (DSO)
        ar_balance = ar_aging['balance_amount'].sum() if not ar_aging.empty else 0
        daily_revenue = revenue_30d / 30 if revenue_30d else 1
        metrics['days_sales_outstanding'] = ar_balance / daily_revenue if daily_revenue else None

        # Days Payables Outstanding (DPO)
        ap_balance = ap_aging['balance_amount'].sum() if not ap_aging.empty else 0
        daily_cogs = cogs_30d / 30 if cogs_30d else 1
        metrics['days_payables_outstanding'] = ap_balance / daily_cogs if daily_cogs else None

        # Cash Conversion Cycle (simplified)
        dso = metrics.get('days_sales_outstanding', 0) or 0
        dpo = metrics.get('days_payables_outstanding', 0) or 0
        dio = 30  # Placeholder for days inventory outstanding
        metrics['cash_conversion_cycle'] = dso + dio - dpo

        return metrics


class CashFlowCalculator:
    """Calculates cash flow components."""

    def __init__(self, source_hook: PostgresHook):
        self.source_hook = source_hook

    def calculate_daily_cash_flow(
        self,
        target_date: datetime,
        cash_account_ids: List[int]
    ) -> Dict[str, Decimal]:
        """Calculate cash flow components for a day."""

        query = """
            WITH cash_movements AS (
                SELECT
                    aml.date,
                    aml.account_id,
                    aa.code AS account_code,
                    aat.type AS account_type,
                    am.move_type,
                    aj.type AS journal_type,
                    aml.debit,
                    aml.credit,
                    aml.balance,
                    rp.customer_rank,
                    rp.supplier_rank
                FROM account_move_line aml
                JOIN account_move am ON aml.move_id = am.id
                JOIN account_journal aj ON am.journal_id = aj.id
                JOIN account_account aa ON aml.account_id = aa.id
                JOIN account_account_type aat ON aa.account_type = aat.id
                LEFT JOIN res_partner rp ON aml.partner_id = rp.id
                WHERE aml.date = %(target_date)s
                  AND am.state = 'posted'
            )
            SELECT
                -- Cash from customers
                COALESCE(SUM(CASE
                    WHEN account_type IN ('bank', 'cash') AND customer_rank > 0
                    THEN debit - credit
                END), 0) AS cash_from_customers,

                -- Cash to suppliers
                COALESCE(SUM(CASE
                    WHEN account_type IN ('bank', 'cash') AND supplier_rank > 0
                    THEN credit - debit
                END), 0) AS cash_to_suppliers,

                -- Cash to employees (payroll)
                COALESCE(SUM(CASE
                    WHEN journal_type = 'general' AND account_code LIKE '62%%'
                    THEN credit
                END), 0) AS cash_to_employees,

                -- Capex
                COALESCE(SUM(CASE
                    WHEN account_type IN ('bank', 'cash') AND move_type = 'entry'
                        AND account_code LIKE '2%%'  -- Fixed assets
                    THEN credit
                END), 0) AS capex_payments,

                -- Loan activity
                COALESCE(SUM(CASE
                    WHEN account_code LIKE '44%%' -- Long-term debt
                    THEN debit - credit
                END), 0) AS loan_activity

            FROM cash_movements
        """

        result = self.source_hook.get_first(
            query,
            parameters={'target_date': target_date.date()}
        )

        if result:
            return {
                'cash_from_customers': result[0] or 0,
                'cash_to_suppliers': result[1] or 0,
                'cash_to_employees': result[2] or 0,
                'capex_payments': result[3] or 0,
                'loan_activity': result[4] or 0
            }
        return {}


# =============================================================================
# AIRFLOW TASKS
# =============================================================================

def extract_gl_data(**context):
    """Extract general ledger data."""
    execution_date = context['execution_date']
    extractor = FinancialDataExtractor()

    gl_data = extractor.extract_journal_entries(execution_date)
    logger.info(f"Extracted {len(gl_data)} GL entries")

    context['ti'].xcom_push(key='gl_data', value=gl_data.to_json())
    return {'status': 'success', 'records': len(gl_data)}


def calculate_ar_aging(**context):
    """Calculate AR aging analysis."""
    execution_date = context['execution_date']
    extractor = FinancialDataExtractor()
    calculator = FinancialMetricsCalculator()

    ar_data = extractor.extract_open_invoices_ar(execution_date)

    if not ar_data.empty:
        ar_data['aging_bucket'] = ar_data['days_overdue'].apply(
            calculator.calculate_aging_buckets
        )

        # Assign amounts to buckets
        for idx, row in ar_data.iterrows():
            bucket = row['aging_bucket']
            amount = row['balance_amount']

            ar_data.loc[idx, 'current_amount'] = amount if bucket == 'Current' else 0
            ar_data.loc[idx, 'bucket_1_30'] = amount if bucket == '1-30' else 0
            ar_data.loc[idx, 'bucket_31_60'] = amount if bucket == '31-60' else 0
            ar_data.loc[idx, 'bucket_61_90'] = amount if bucket == '61-90' else 0
            ar_data.loc[idx, 'bucket_91_120'] = amount if bucket == '91-120' else 0
            ar_data.loc[idx, 'bucket_over_120'] = amount if bucket == '120+' else 0

    logger.info(f"Calculated AR aging for {len(ar_data)} invoices")
    context['ti'].xcom_push(key='ar_aging', value=ar_data.to_json())

    return {'status': 'success', 'total_ar': ar_data['balance_amount'].sum() if not ar_data.empty else 0}


def calculate_ap_aging(**context):
    """Calculate AP aging analysis."""
    execution_date = context['execution_date']
    extractor = FinancialDataExtractor()
    calculator = FinancialMetricsCalculator()

    ap_data = extractor.extract_open_invoices_ap(execution_date)

    if not ap_data.empty:
        ap_data['aging_bucket'] = ap_data['days_overdue'].apply(
            calculator.calculate_aging_buckets
        )

    logger.info(f"Calculated AP aging for {len(ap_data)} invoices")
    context['ti'].xcom_push(key='ap_aging', value=ap_data.to_json())

    return {'status': 'success', 'total_ap': ap_data['balance_amount'].sum() if not ap_data.empty else 0}


def calculate_financial_ratios(**context):
    """Calculate monthly financial ratios."""
    execution_date = context['execution_date']

    # Only calculate on first of month
    if execution_date.day != 1:
        logger.info("Skipping ratio calculation - not first of month")
        return {'status': 'skipped'}

    extractor = FinancialDataExtractor()
    calculator = FinancialMetricsCalculator()

    # Get balance sheet data
    balances = extractor.extract_account_balances(execution_date)

    # Get P&L data for the month
    month_start = execution_date.replace(day=1) - timedelta(days=1)
    month_start = month_start.replace(day=1)

    # Calculate ratios
    ratios = calculator.calculate_financial_ratios(balances, balances)

    logger.info(f"Calculated {len(ratios)} financial ratios")
    context['ti'].xcom_push(key='financial_ratios', value=ratios)

    return {'status': 'success', 'ratios': ratios}


def load_financial_data(**context):
    """Load financial analytics data to warehouse."""
    ti = context['ti']
    target_hook = PostgresHook(postgres_conn_id='analytics_dw')

    # Load GL data, AR/AP aging, ratios...
    logger.info("Financial data loaded successfully")

    return {'status': 'success'}


# Task definitions
with dag:
    extract_gl = PythonOperator(
        task_id='extract_gl_data',
        python_callable=extract_gl_data,
    )

    ar_aging = PythonOperator(
        task_id='calculate_ar_aging',
        python_callable=calculate_ar_aging,
    )

    ap_aging = PythonOperator(
        task_id='calculate_ap_aging',
        python_callable=calculate_ap_aging,
    )

    ratios = PythonOperator(
        task_id='calculate_financial_ratios',
        python_callable=calculate_financial_ratios,
    )

    load_data = PythonOperator(
        task_id='load_financial_data',
        python_callable=load_financial_data,
    )

    [extract_gl, ar_aging, ap_aging, ratios] >> load_data
```

---

## 4. Analytics Dashboards

### 4.1 Profit & Loss Dashboard

```sql
-- =====================================================
-- PROFIT & LOSS ANALYTICS
-- Income Statement Analysis
-- =====================================================

-- 4.1.1 P&L Summary by Period
CREATE OR REPLACE VIEW vw_pl_summary AS
SELECT
    fp.fiscal_year,
    fp.fiscal_month,
    fp.period_name,
    -- Revenue
    SUM(CASE WHEN a.account_type = 'income' THEN ftb.closing_balance * -1 ELSE 0 END) AS total_revenue,
    -- Cost of Goods Sold
    SUM(CASE WHEN a.account_type = 'expense_direct_cost' THEN ftb.closing_balance ELSE 0 END) AS cogs,
    -- Gross Profit
    SUM(CASE WHEN a.account_type = 'income' THEN ftb.closing_balance * -1 ELSE 0 END) -
    SUM(CASE WHEN a.account_type = 'expense_direct_cost' THEN ftb.closing_balance ELSE 0 END) AS gross_profit,
    -- Operating Expenses
    SUM(CASE WHEN a.account_type = 'expense' AND a.account_category NOT IN ('depreciation', 'interest')
        THEN ftb.closing_balance ELSE 0 END) AS operating_expenses,
    -- Operating Income
    SUM(CASE WHEN a.account_type = 'income' THEN ftb.closing_balance * -1 ELSE 0 END) -
    SUM(CASE WHEN a.account_type IN ('expense_direct_cost', 'expense') THEN ftb.closing_balance ELSE 0 END) AS operating_income,
    -- Depreciation
    SUM(CASE WHEN a.account_category = 'depreciation' THEN ftb.closing_balance ELSE 0 END) AS depreciation,
    -- Interest Expense
    SUM(CASE WHEN a.account_category = 'interest' THEN ftb.closing_balance ELSE 0 END) AS interest_expense,
    -- Net Income
    SUM(CASE WHEN a.account_type = 'income' THEN ftb.closing_balance * -1
             WHEN a.account_type LIKE 'expense%' THEN ftb.closing_balance * -1
             ELSE 0 END) AS net_income,
    -- Margins
    ROUND(
        (SUM(CASE WHEN a.account_type = 'income' THEN ftb.closing_balance * -1 ELSE 0 END) -
         SUM(CASE WHEN a.account_type = 'expense_direct_cost' THEN ftb.closing_balance ELSE 0 END)) /
        NULLIF(SUM(CASE WHEN a.account_type = 'income' THEN ftb.closing_balance * -1 ELSE 0 END), 0) * 100,
        2
    ) AS gross_margin_pct
FROM fact_trial_balance ftb
JOIN dim_account a ON ftb.account_sk = a.account_sk
JOIN dim_fiscal_period fp ON ftb.fiscal_period_sk = fp.fiscal_period_sk
WHERE a.account_type IN ('income', 'expense', 'expense_direct_cost')
GROUP BY fp.fiscal_year, fp.fiscal_month, fp.period_name, fp.fiscal_period_sk
ORDER BY fp.fiscal_year DESC, fp.fiscal_month DESC;

-- 4.1.2 Revenue by Category
CREATE OR REPLACE VIEW vw_revenue_by_category AS
SELECT
    fp.period_name,
    a.account_category,
    a.account_name,
    SUM(ftb.period_credit - ftb.period_debit) AS revenue,
    SUM(ftb.ytd_credit - ftb.ytd_debit) AS ytd_revenue,
    -- Period comparison
    LAG(SUM(ftb.period_credit - ftb.period_debit)) OVER (
        PARTITION BY a.account_sk ORDER BY fp.fiscal_year, fp.fiscal_month
    ) AS prev_period_revenue,
    -- YoY comparison
    LAG(SUM(ftb.period_credit - ftb.period_debit), 12) OVER (
        PARTITION BY a.account_sk ORDER BY fp.fiscal_year, fp.fiscal_month
    ) AS same_period_last_year
FROM fact_trial_balance ftb
JOIN dim_account a ON ftb.account_sk = a.account_sk
JOIN dim_fiscal_period fp ON ftb.fiscal_period_sk = fp.fiscal_period_sk
WHERE a.account_type = 'income'
GROUP BY fp.period_name, fp.fiscal_year, fp.fiscal_month, a.account_sk, a.account_category, a.account_name
ORDER BY revenue DESC;

-- 4.1.3 Cost Structure Analysis
CREATE OR REPLACE VIEW vw_cost_structure AS
SELECT
    fp.period_name,
    a.account_category,
    a.account_name,
    SUM(ftb.period_debit - ftb.period_credit) AS cost,
    -- Percentage of revenue
    ROUND(
        SUM(ftb.period_debit - ftb.period_credit) /
        NULLIF((SELECT SUM(ftb2.period_credit - ftb2.period_debit)
                FROM fact_trial_balance ftb2
                JOIN dim_account a2 ON ftb2.account_sk = a2.account_sk
                WHERE a2.account_type = 'income'
                  AND ftb2.fiscal_period_sk = fp.fiscal_period_sk), 0) * 100,
        2
    ) AS pct_of_revenue,
    -- Cost category
    CASE
        WHEN a.account_type = 'expense_direct_cost' THEN 'Direct Cost'
        WHEN a.account_category IN ('payroll', 'salaries') THEN 'Personnel'
        WHEN a.account_category IN ('rent', 'utilities') THEN 'Facilities'
        WHEN a.account_category = 'marketing' THEN 'Marketing'
        ELSE 'Other Operating'
    END AS cost_category
FROM fact_trial_balance ftb
JOIN dim_account a ON ftb.account_sk = a.account_sk
JOIN dim_fiscal_period fp ON ftb.fiscal_period_sk = fp.fiscal_period_sk
WHERE a.account_type IN ('expense', 'expense_direct_cost')
GROUP BY fp.period_name, fp.fiscal_period_sk, fp.fiscal_year, fp.fiscal_month,
         a.account_sk, a.account_category, a.account_name, a.account_type
ORDER BY cost DESC;
```

### 4.2 AR/AP Aging Dashboard

```sql
-- =====================================================
-- ACCOUNTS RECEIVABLE/PAYABLE ANALYTICS
-- Aging and Collection Analysis
-- =====================================================

-- 4.2.1 AR Aging Summary
CREATE OR REPLACE VIEW vw_ar_aging_summary AS
SELECT
    d.date_actual AS snapshot_date,
    COUNT(DISTINCT ar.customer_sk) AS customers_with_balance,
    COUNT(DISTINCT ar.invoice_id) AS open_invoices,
    SUM(ar.balance_amount) AS total_ar_balance,
    SUM(ar.current_amount) AS current_amount,
    SUM(ar.bucket_1_30) AS days_1_30,
    SUM(ar.bucket_31_60) AS days_31_60,
    SUM(ar.bucket_61_90) AS days_61_90,
    SUM(ar.bucket_91_120) AS days_91_120,
    SUM(ar.bucket_over_120) AS over_120_days,
    -- Aging percentages
    ROUND(SUM(ar.current_amount) / NULLIF(SUM(ar.balance_amount), 0) * 100, 1) AS current_pct,
    ROUND((SUM(ar.bucket_61_90) + SUM(ar.bucket_91_120) + SUM(ar.bucket_over_120)) /
          NULLIF(SUM(ar.balance_amount), 0) * 100, 1) AS overdue_60_plus_pct,
    -- Average days outstanding
    ROUND(AVG(ar.days_outstanding), 0) AS avg_days_outstanding,
    -- Collection risk
    SUM(CASE WHEN ar.aging_bucket IN ('91-120', '120+') THEN ar.balance_amount ELSE 0 END) AS high_risk_amount
FROM fact_ar_aging ar
JOIN dim_date d ON ar.snapshot_date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
GROUP BY d.date_actual;

-- 4.2.2 AR by Customer
CREATE OR REPLACE VIEW vw_ar_by_customer AS
SELECT
    c.customer_code,
    c.customer_name,
    c.credit_limit,
    COUNT(DISTINCT ar.invoice_id) AS open_invoices,
    SUM(ar.balance_amount) AS total_balance,
    SUM(ar.current_amount) AS current_amount,
    SUM(ar.bucket_1_30) AS days_1_30,
    SUM(ar.bucket_31_60) AS days_31_60,
    SUM(ar.bucket_61_90 + ar.bucket_91_120 + ar.bucket_over_120) AS overdue_60_plus,
    MAX(ar.days_outstanding) AS max_days_outstanding,
    AVG(ar.days_outstanding) AS avg_days_outstanding,
    -- Credit utilization
    ROUND(SUM(ar.balance_amount) / NULLIF(c.credit_limit, 0) * 100, 1) AS credit_utilization_pct,
    -- Risk classification
    CASE
        WHEN SUM(ar.bucket_over_120) > 0 THEN 'High Risk'
        WHEN SUM(ar.bucket_61_90 + ar.bucket_91_120) > SUM(ar.balance_amount) * 0.3 THEN 'Medium Risk'
        ELSE 'Low Risk'
    END AS risk_classification
FROM fact_ar_aging ar
JOIN dim_customer c ON ar.customer_sk = c.customer_sk
JOIN dim_date d ON ar.snapshot_date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
GROUP BY c.customer_sk, c.customer_code, c.customer_name, c.credit_limit
ORDER BY total_balance DESC;

-- 4.2.3 AP Aging Summary
CREATE OR REPLACE VIEW vw_ap_aging_summary AS
SELECT
    d.date_actual AS snapshot_date,
    COUNT(DISTINCT ap.supplier_sk) AS suppliers_with_balance,
    COUNT(DISTINCT ap.invoice_id) AS open_bills,
    SUM(ap.balance_amount) AS total_ap_balance,
    SUM(ap.current_amount) AS current_amount,
    SUM(ap.bucket_1_30) AS days_1_30,
    SUM(ap.bucket_31_60) AS days_31_60,
    SUM(ap.bucket_61_90) AS days_61_90,
    SUM(ap.bucket_91_120) AS days_91_120,
    SUM(ap.bucket_over_120) AS over_120_days,
    -- Early payment opportunities
    SUM(CASE WHEN ap.discount_due_date >= CURRENT_DATE THEN ap.balance_amount ELSE 0 END) AS eligible_for_discount,
    AVG(ap.days_outstanding) AS avg_days_outstanding
FROM fact_ap_aging ap
JOIN dim_date d ON ap.snapshot_date_sk = d.date_sk
WHERE d.date_actual = CURRENT_DATE
GROUP BY d.date_actual;
```

### 4.3 Budget Variance Dashboard

```sql
-- =====================================================
-- BUDGET VARIANCE ANALYTICS
-- Budget vs Actual Analysis
-- =====================================================

-- 4.3.1 Budget Variance Summary
CREATE OR REPLACE VIEW vw_budget_variance_summary AS
SELECT
    b.budget_name,
    fp.period_name,
    a.account_category,
    cc.cost_center_name,
    fb.budget_amount,
    fb.actual_amount,
    fb.variance_amount,
    ROUND(fb.variance_amount / NULLIF(fb.budget_amount, 0) * 100, 1) AS variance_pct,
    -- Variance status
    CASE
        WHEN fb.variance_amount < 0 AND a.account_type = 'expense' THEN 'Favorable'
        WHEN fb.variance_amount > 0 AND a.account_type = 'expense' THEN 'Unfavorable'
        WHEN fb.variance_amount > 0 AND a.account_type = 'income' THEN 'Favorable'
        WHEN fb.variance_amount < 0 AND a.account_type = 'income' THEN 'Unfavorable'
        ELSE 'On Target'
    END AS variance_status,
    -- Materiality flag
    CASE
        WHEN ABS(fb.variance_amount / NULLIF(fb.budget_amount, 0)) > 0.1 THEN TRUE
        ELSE FALSE
    END AS is_material
FROM fact_budget fb
JOIN dim_budget b ON fb.budget_sk = b.budget_sk
JOIN dim_fiscal_period fp ON fb.fiscal_period_sk = fp.fiscal_period_sk
JOIN dim_account a ON fb.account_sk = a.account_sk
LEFT JOIN dim_cost_center cc ON fb.cost_center_sk = cc.cost_center_sk
ORDER BY ABS(fb.variance_amount) DESC;

-- 4.3.2 Cost Center Budget Performance
CREATE OR REPLACE VIEW vw_cost_center_budget_performance AS
SELECT
    cc.cost_center_name,
    cc.cost_center_type,
    cc.manager_name,
    fp.fiscal_year,
    SUM(fb.budget_amount) AS total_budget,
    SUM(fb.actual_amount) AS total_actual,
    SUM(fb.variance_amount) AS total_variance,
    ROUND(SUM(fb.variance_amount) / NULLIF(SUM(fb.budget_amount), 0) * 100, 1) AS variance_pct,
    -- Budget utilization
    ROUND(SUM(fb.actual_amount) / NULLIF(SUM(fb.budget_amount), 0) * 100, 1) AS budget_utilization_pct,
    -- Remaining budget
    SUM(fb.budget_amount) - SUM(fb.actual_amount) AS remaining_budget,
    -- Performance rating
    CASE
        WHEN SUM(fb.actual_amount) <= SUM(fb.budget_amount) * 0.9 THEN 'Under Budget'
        WHEN SUM(fb.actual_amount) <= SUM(fb.budget_amount) THEN 'On Target'
        WHEN SUM(fb.actual_amount) <= SUM(fb.budget_amount) * 1.1 THEN 'Slightly Over'
        ELSE 'Over Budget'
    END AS performance_rating
FROM fact_budget fb
JOIN dim_cost_center cc ON fb.cost_center_sk = cc.cost_center_sk
JOIN dim_fiscal_period fp ON fb.fiscal_period_sk = fp.fiscal_period_sk
GROUP BY cc.cost_center_sk, cc.cost_center_name, cc.cost_center_type, cc.manager_name, fp.fiscal_year
ORDER BY total_variance DESC;
```

### 4.4 Financial Ratios Dashboard

```sql
-- =====================================================
-- FINANCIAL RATIOS DASHBOARD
-- Key Financial KPIs and Trends
-- =====================================================

-- 4.4.1 Financial Ratios Trend
CREATE OR REPLACE VIEW vw_financial_ratios_trend AS
SELECT
    fp.period_name,
    fp.fiscal_year,
    fp.fiscal_month,
    fr.current_ratio,
    fr.quick_ratio,
    fr.gross_margin_pct,
    fr.operating_margin_pct,
    fr.net_margin_pct,
    fr.return_on_assets,
    fr.return_on_equity,
    fr.debt_to_equity,
    fr.days_sales_outstanding,
    fr.days_payables_outstanding,
    fr.cash_conversion_cycle,
    -- Trend indicators
    fr.current_ratio - LAG(fr.current_ratio) OVER (ORDER BY fp.fiscal_year, fp.fiscal_month) AS current_ratio_change,
    fr.gross_margin_pct - LAG(fr.gross_margin_pct) OVER (ORDER BY fp.fiscal_year, fp.fiscal_month) AS margin_change,
    -- Benchmark comparison
    CASE WHEN fr.current_ratio >= 2.0 THEN 'Good'
         WHEN fr.current_ratio >= 1.0 THEN 'Adequate'
         ELSE 'Concern' END AS liquidity_assessment,
    CASE WHEN fr.debt_to_equity <= 1.0 THEN 'Conservative'
         WHEN fr.debt_to_equity <= 2.0 THEN 'Moderate'
         ELSE 'Aggressive' END AS leverage_assessment
FROM fact_financial_ratios fr
JOIN dim_fiscal_period fp ON fr.fiscal_period_sk = fp.fiscal_period_sk
ORDER BY fp.fiscal_year DESC, fp.fiscal_month DESC;

-- 4.4.2 Working Capital Dashboard
CREATE OR REPLACE VIEW vw_working_capital_dashboard AS
SELECT
    fp.period_name,
    fr.working_capital,
    fr.current_assets,
    fr.current_liabilities,
    fr.current_ratio,
    fr.quick_ratio,
    fr.days_sales_outstanding AS dso,
    fr.days_payables_outstanding AS dpo,
    fr.days_inventory_outstanding AS dio,
    fr.cash_conversion_cycle AS ccc,
    -- Component breakdown
    (SELECT SUM(balance_amount) FROM fact_ar_aging
     WHERE snapshot_date_sk = (SELECT date_sk FROM dim_date WHERE date_actual = fp.period_end_date)) AS ar_balance,
    (SELECT SUM(balance_amount) FROM fact_ap_aging
     WHERE snapshot_date_sk = (SELECT date_sk FROM dim_date WHERE date_actual = fp.period_end_date)) AS ap_balance,
    -- Trends
    fr.days_sales_outstanding - LAG(fr.days_sales_outstanding) OVER (ORDER BY fp.fiscal_year, fp.fiscal_month) AS dso_change,
    fr.cash_conversion_cycle - LAG(fr.cash_conversion_cycle) OVER (ORDER BY fp.fiscal_year, fp.fiscal_month) AS ccc_change
FROM fact_financial_ratios fr
JOIN dim_fiscal_period fp ON fr.fiscal_period_sk = fp.fiscal_period_sk
ORDER BY fp.fiscal_year DESC, fp.fiscal_month DESC;
```

---

## 5. Daily Development Schedule

### Day 561-570: Financial Analytics Implementation

| Day | Focus Area | Key Deliverables |
|-----|------------|------------------|
| 561 | Schema Design | Financial star schema, account dimensions, fiscal periods |
| 562 | GL and Trial Balance | fact_general_ledger, fact_trial_balance, GL ETL |
| 563 | AR/AP Analytics | Aging fact tables, aging calculation logic |
| 564 | Budget Analytics | Budget fact tables, variance calculations |
| 565 | Cash Flow Analytics | Cash flow fact table, cash flow ETL |
| 566 | Financial Ratios | Ratio calculations, trend analysis |
| 567 | P&L Dashboard | Revenue, cost, margin analytics views |
| 568 | Working Capital Dashboard | DSO, DPO, CCC dashboards |
| 569 | Integration & API | Financial API endpoints, Odoo integration |
| 570 | Testing & Documentation | Unit tests, documentation, sign-off |

---

## 6. Requirement Traceability Matrix

| Requirement ID | Requirement Description | Implementation | Test Case |
|----------------|------------------------|----------------|-----------|
| RFP-FIN-001 | Real-time financial reporting | P&L Dashboard, Trial Balance | TC-107-001 |
| RFP-FIN-002 | Budget vs actual analysis | Budget Variance Dashboard | TC-107-002 |
| BRD-FIN-001 | CFO dashboard and KPIs | Financial Ratios Dashboard | TC-107-003 |
| SRS-ANALYTICS-030 | Profit and Loss analytics | vw_pl_summary, Cost Structure | TC-107-004 |
| SRS-ANALYTICS-031 | Cash flow forecasting | fact_cash_flow, Cash Flow ETL | TC-107-005 |
| SRS-ANALYTICS-032 | Financial ratio analysis | fact_financial_ratios | TC-107-006 |
| SRS-ANALYTICS-033 | AR/AP aging analysis | fact_ar_aging, fact_ap_aging | TC-107-007 |

---

## 7. Risk Register

| Risk ID | Description | Probability | Impact | Mitigation |
|---------|-------------|-------------|--------|------------|
| R107-001 | GL data reconciliation issues | Medium | High | Daily reconciliation checks, variance alerts |
| R107-002 | Multi-currency complexity | Medium | Medium | Standard exchange rate source, variance tracking |
| R107-003 | Month-end close timing | Low | High | Clear cutoff procedures, preliminary vs final reports |
| R107-004 | Budget version control | Low | Medium | Version tracking, approval workflow |

---

## 8. Quality Assurance Checklist

- [ ] Trial balance totals equal (debits = credits)
- [ ] AR aging matches subledger
- [ ] AP aging matches subledger
- [ ] Financial ratios calculated correctly
- [ ] Budget variance calculations verified
- [ ] Period comparisons accurate
- [ ] All dashboards load within 3 seconds
- [ ] Data freshness < 24 hours

---

**Document End**

*Milestone 107: Financial Analytics Module - Days 561-570*
