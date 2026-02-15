# Milestone 110: Analytics Testing and Quality Assurance

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | PH11-M110-ANALYTICS-TESTING |
| **Version** | 1.0 |
| **Author** | Senior Technical Architect |
| **Created** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Final |
| **Classification** | Internal Use |
| **Parent Phase** | Phase 11 - Commerce Advanced Analytics |
| **Timeline** | Days 591-600 |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 110 is the final milestone of Phase 11, dedicated to comprehensive testing, quality assurance, and validation of all analytics modules delivered in Milestones 101-109. This milestone ensures that the entire analytics platform meets functional requirements, performance benchmarks, security standards, and business acceptance criteria before production deployment.

### 1.2 Testing Scope

| Module | Milestone Reference | Test Categories |
|--------|---------------------|-----------------|
| BI Platform | M101 | Infrastructure, Integration, Security |
| Executive Dashboards | M102 | Functional, Performance, UAT |
| Sales Analytics | M103 | Data Validation, Calculations, UAT |
| Farm Analytics | M104 | Data Validation, Calculations, UAT |
| Inventory Analytics | M105 | Data Validation, FEFO Logic, UAT |
| Customer Analytics | M106 | Model Validation, Calculations, UAT |
| Financial Analytics | M107 | Reconciliation, Compliance, UAT |
| Supply Chain Analytics | M108 | Data Validation, Calculations, UAT |
| Custom Reports | M109 | Functional, Export, UAT |

### 1.3 Quality Objectives

| Objective | Target | Measurement |
|-----------|--------|-------------|
| Test Coverage | > 90% | Code coverage reports |
| Defect Density | < 0.5 defects/KLOC | Defect tracking |
| Critical Defects | 0 at go-live | Severity classification |
| Performance | < 3s dashboard load | Load testing results |
| Data Accuracy | 99.99% | Reconciliation tests |
| UAT Acceptance | 100% sign-off | UAT test cases |

---

## 2. Test Strategy

### 2.1 Testing Levels

```
┌─────────────────────────────────────────────────────────────────┐
│                    TEST PYRAMID                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│                         ▲ UAT Tests                             │
│                        ▲▲▲ (Manual - Business Validation)       │
│                       ▲▲▲▲▲                                     │
│                      ▲▲▲▲▲▲▲ E2E Tests                         │
│                     ▲▲▲▲▲▲▲▲▲ (Automated - Critical Paths)     │
│                    ▲▲▲▲▲▲▲▲▲▲▲                                 │
│                   ▲▲▲▲▲▲▲▲▲▲▲▲▲ Integration Tests              │
│                  ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲ (API, Database, ETL)         │
│                 ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲                              │
│                ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲ Unit Tests                  │
│               ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲ (Calculations, Logic)     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Test Types Matrix

| Test Type | Scope | Automation | Responsibility |
|-----------|-------|------------|----------------|
| Unit Tests | Functions, Methods | 100% Automated | Developers |
| Integration Tests | APIs, ETL, Data Flow | 90% Automated | Developers + QA |
| Performance Tests | Load, Stress, Endurance | 100% Automated | QA + DevOps |
| Security Tests | OWASP, Access Control | 80% Automated | Security + QA |
| Data Validation | Reconciliation, Accuracy | 90% Automated | QA + Data Team |
| Regression Tests | Full Suite | 100% Automated | QA |
| UAT | Business Scenarios | Manual | Business Users |

---

## 3. Test Implementation

### 3.1 Unit Testing Framework

```python
# tests/unit/test_analytics_calculations.py
"""
Unit Tests for Analytics Calculations
Milestone 110 - Days 591-600
"""

import pytest
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
from decimal import Decimal
from unittest.mock import Mock, patch

# Import modules under test
from analytics.rfm_calculator import RFMCalculator
from analytics.clv_calculator import CLVCalculator
from analytics.churn_predictor import ChurnPredictor
from analytics.inventory_calculator import InventoryAnalyticsCalculator
from analytics.financial_calculator import FinancialMetricsCalculator


class TestRFMCalculator:
    """Tests for RFM score calculations."""

    @pytest.fixture
    def calculator(self):
        return RFMCalculator(Mock())

    @pytest.fixture
    def sample_transactions(self):
        """Sample transaction data for testing."""
        return pd.DataFrame({
            'customer_id': [1, 1, 1, 2, 2, 3],
            'order_date': [
                datetime.now() - timedelta(days=5),
                datetime.now() - timedelta(days=30),
                datetime.now() - timedelta(days=60),
                datetime.now() - timedelta(days=10),
                datetime.now() - timedelta(days=90),
                datetime.now() - timedelta(days=180)
            ],
            'order_value': [100, 150, 200, 500, 300, 50]
        })

    def test_recency_score_calculation(self, calculator):
        """Test recency score is inversely related to days since last order."""
        # Customer with recent order should have higher recency score
        assert calculator._calculate_recency_score(5) > calculator._calculate_recency_score(30)
        assert calculator._calculate_recency_score(1) == 5  # Most recent = highest score
        assert calculator._calculate_recency_score(365) == 1  # Oldest = lowest score

    def test_frequency_score_calculation(self, calculator):
        """Test frequency score increases with order count."""
        assert calculator._calculate_frequency_score(10) > calculator._calculate_frequency_score(2)
        assert calculator._calculate_frequency_score(1) == 1
        assert calculator._calculate_frequency_score(50) == 5

    def test_monetary_score_calculation(self, calculator):
        """Test monetary score increases with total spend."""
        assert calculator._calculate_monetary_score(10000) > calculator._calculate_monetary_score(1000)
        assert calculator._calculate_monetary_score(100) == 1
        assert calculator._calculate_monetary_score(100000) == 5

    def test_rfm_segment_assignment(self, calculator):
        """Test correct segment assignment based on RFM scores."""
        assert calculator.assign_rfm_segment('555') == 'Champions'
        assert calculator.assign_rfm_segment('111') == 'Lost'
        assert calculator.assign_rfm_segment('511') == 'Recent Customers'
        assert calculator.assign_rfm_segment('155') == 'Cant Lose Them'

    def test_rfm_score_format(self, calculator, sample_transactions):
        """Test RFM score is in correct format (3 digits)."""
        result = calculator.calculate_rfm_scores(sample_transactions, datetime.now())

        for _, row in result.iterrows():
            assert len(row['rfm_score']) == 3
            assert all(c in '12345' for c in row['rfm_score'])

    def test_handles_empty_dataframe(self, calculator):
        """Test graceful handling of empty data."""
        empty_df = pd.DataFrame()
        result = calculator.calculate_rfm_scores(empty_df, datetime.now())
        assert result.empty


class TestCLVCalculator:
    """Tests for Customer Lifetime Value calculations."""

    @pytest.fixture
    def calculator(self):
        return CLVCalculator(Mock())

    @pytest.fixture
    def sample_customer_history(self):
        return pd.DataFrame({
            'customer_id': [1, 2, 3],
            'first_order_date': [
                datetime.now() - timedelta(days=365),
                datetime.now() - timedelta(days=180),
                datetime.now() - timedelta(days=30)
            ],
            'total_orders': [12, 6, 2],
            'total_revenue': [2400, 1200, 400],
            'total_margin': [720, 360, 120]
        })

    def test_historical_clv_equals_total_revenue(self, calculator, sample_customer_history):
        """Test historical CLV equals actual revenue to date."""
        result = calculator.calculate_clv(sample_customer_history)

        for _, row in result.iterrows():
            assert row['historical_clv'] == row['total_revenue']

    def test_clv_confidence_increases_with_data(self, calculator, sample_customer_history):
        """Test CLV confidence score increases with more data points."""
        result = calculator.calculate_clv(sample_customer_history)

        # Customer 1 has most orders, should have highest confidence
        customer_1 = result[result['customer_id'] == 1].iloc[0]
        customer_3 = result[result['customer_id'] == 3].iloc[0]

        assert customer_1['clv_confidence_score'] > customer_3['clv_confidence_score']

    def test_predicted_clv_reasonable_bounds(self, calculator, sample_customer_history):
        """Test predicted CLV is within reasonable bounds."""
        result = calculator.calculate_clv(sample_customer_history)

        for _, row in result.iterrows():
            # Predicted 1-year CLV should be positive
            assert row['predicted_clv_1_year'] >= 0

            # Predicted lifetime should be >= 1-year
            assert row['predicted_clv_lifetime'] >= row['predicted_clv_1_year']

    def test_margin_percentage_calculation(self, calculator, sample_customer_history):
        """Test margin percentage calculated correctly."""
        result = calculator.calculate_clv(sample_customer_history)

        for _, row in result.iterrows():
            original = sample_customer_history[
                sample_customer_history['customer_id'] == row['customer_id']
            ].iloc[0]

            expected_margin_pct = (original['total_margin'] / original['total_revenue']) * 100
            assert abs(row['avg_margin_pct'] - expected_margin_pct) < 0.01


class TestChurnPredictor:
    """Tests for churn prediction model."""

    @pytest.fixture
    def predictor(self):
        return ChurnPredictor(Mock())

    @pytest.fixture
    def sample_churn_features(self):
        return pd.DataFrame({
            'customer_id': [1, 2, 3, 4],
            'days_since_last_order': [7, 45, 120, 200],
            'orders_30d': [3, 0, 0, 0],
            'orders_90d': [8, 2, 1, 0],
            'spend_30d': [500, 0, 0, 0],
            'spend_90d': [1500, 300, 100, 0],
            'total_orders': [50, 20, 5, 10]
        })

    def test_churn_score_range(self, predictor, sample_churn_features):
        """Test churn score is within 0-100 range."""
        result = predictor.calculate_churn_risk(sample_churn_features)

        for _, row in result.iterrows():
            assert 0 <= row['churn_risk_score'] <= 100

    def test_churn_probability_range(self, predictor, sample_churn_features):
        """Test churn probability is between 0 and 1."""
        result = predictor.calculate_churn_risk(sample_churn_features)

        for _, row in result.iterrows():
            assert 0 <= row['churn_probability'] <= 1

    def test_inactive_customers_high_risk(self, predictor, sample_churn_features):
        """Test customers with no recent activity have high churn risk."""
        result = predictor.calculate_churn_risk(sample_churn_features)

        # Customer 4 (200 days inactive, no orders) should be highest risk
        customer_4 = result[result['customer_id'] == 4].iloc[0]
        assert customer_4['churn_risk_category'] in ['High', 'Critical']

    def test_active_customers_low_risk(self, predictor, sample_churn_features):
        """Test recently active customers have low churn risk."""
        result = predictor.calculate_churn_risk(sample_churn_features)

        # Customer 1 (7 days, active) should be low risk
        customer_1 = result[result['customer_id'] == 1].iloc[0]
        assert customer_1['churn_risk_category'] == 'Low'

    def test_churn_factors_populated(self, predictor, sample_churn_features):
        """Test top churn factors are populated for at-risk customers."""
        result = predictor.calculate_churn_risk(sample_churn_features)

        high_risk = result[result['churn_risk_category'].isin(['High', 'Critical'])]

        for _, row in high_risk.iterrows():
            assert row['top_churn_factor_1'] is not None


class TestInventoryCalculations:
    """Tests for inventory analytics calculations."""

    @pytest.fixture
    def calculator(self):
        return InventoryAnalyticsCalculator()

    def test_aging_bucket_assignment(self, calculator):
        """Test correct aging bucket assignment."""
        assert calculator.calculate_aging_buckets(-5) == 'Current'
        assert calculator.calculate_aging_buckets(0) == 'Current'
        assert calculator.calculate_aging_buckets(15) == '1-30'
        assert calculator.calculate_aging_buckets(45) == '31-60'
        assert calculator.calculate_aging_buckets(75) == '61-90'
        assert calculator.calculate_aging_buckets(100) == '91-120'
        assert calculator.calculate_aging_buckets(150) == '120+'

    def test_freshness_score_calculation(self, calculator):
        """Test freshness score decreases as expiration approaches."""
        # Full shelf life remaining
        assert calculator.calculate_freshness_score(30, 30) == 100

        # Half shelf life remaining
        score_half = calculator.calculate_freshness_score(15, 30)
        assert 40 <= score_half <= 60

        # Expired
        assert calculator.calculate_freshness_score(0, 30) == 0
        assert calculator.calculate_freshness_score(-5, 30) == 0

    def test_reorder_point_calculation(self, calculator):
        """Test reorder point calculation."""
        # ROP = (Daily demand * Lead time) + Safety stock
        rop = calculator.calculate_reorder_point(
            avg_daily_demand=10,
            lead_time_days=5,
            safety_stock=20
        )
        assert rop == 70  # (10 * 5) + 20

    def test_safety_stock_calculation(self, calculator):
        """Test safety stock calculation with service level."""
        # At 95% service level, z-score ≈ 1.645
        ss = calculator.calculate_safety_stock(
            demand_std=10,
            lead_time_days=4,
            service_level=0.95
        )
        # SS = z * σ * √L ≈ 1.645 * 10 * 2 = 32.9
        assert 30 <= ss <= 35


class TestFinancialCalculations:
    """Tests for financial analytics calculations."""

    @pytest.fixture
    def calculator(self):
        return FinancialMetricsCalculator()

    @pytest.fixture
    def sample_balances(self):
        return pd.DataFrame({
            'account_type': ['bank', 'receivable', 'payable', 'inventory', 'equity'],
            'balance': [100000, 50000, -30000, 40000, -160000]
        })

    def test_current_ratio_calculation(self, calculator, sample_balances):
        """Test current ratio = Current Assets / Current Liabilities."""
        ratios = calculator.calculate_financial_ratios(sample_balances, pd.DataFrame())

        # Current assets: 100000 + 50000 + 40000 = 190000
        # Current liabilities: 30000
        # Current ratio: 190000 / 30000 ≈ 6.33
        assert ratios['current_ratio'] is not None
        assert ratios['current_ratio'] > 1  # Healthy ratio

    def test_working_capital_calculation(self, calculator, sample_balances):
        """Test working capital = Current Assets - Current Liabilities."""
        ratios = calculator.calculate_financial_ratios(sample_balances, pd.DataFrame())

        assert ratios['working_capital'] > 0  # Positive working capital

    def test_dso_dpo_calculations(self, calculator):
        """Test DSO and DPO calculations."""
        ar_aging = pd.DataFrame({'balance_amount': [50000]})
        ap_aging = pd.DataFrame({'balance_amount': [30000]})

        metrics = calculator.calculate_working_capital_metrics(
            ar_aging=ar_aging,
            ap_aging=ap_aging,
            revenue_30d=100000,
            cogs_30d=60000
        )

        # DSO = AR Balance / (Revenue/30) = 50000 / 3333.33 ≈ 15
        assert 10 <= metrics['days_sales_outstanding'] <= 20

        # DPO = AP Balance / (COGS/30) = 30000 / 2000 = 15
        assert 10 <= metrics['days_payables_outstanding'] <= 20
```

### 3.2 Integration Testing

```python
# tests/integration/test_etl_pipelines.py
"""
Integration Tests for ETL Pipelines
Milestone 110 - Days 591-600
"""

import pytest
from datetime import datetime, timedelta
import pandas as pd
from airflow.models import DagBag
from unittest.mock import patch, MagicMock


class TestSalesAnalyticsETL:
    """Integration tests for Sales Analytics ETL pipeline."""

    @pytest.fixture
    def dag_bag(self):
        return DagBag(dag_folder='airflow/dags', include_examples=False)

    def test_dag_loads_without_errors(self, dag_bag):
        """Test DAG file loads without import errors."""
        assert len(dag_bag.import_errors) == 0

    def test_sales_etl_dag_exists(self, dag_bag):
        """Test sales analytics DAG exists."""
        assert 'sales_analytics_etl' in dag_bag.dags

    def test_dag_has_correct_schedule(self, dag_bag):
        """Test DAG has expected schedule interval."""
        dag = dag_bag.dags['sales_analytics_etl']
        assert dag.schedule_interval == '0 4 * * *'

    def test_task_dependencies(self, dag_bag):
        """Test correct task dependencies."""
        dag = dag_bag.dags['sales_analytics_etl']

        extract_task = dag.get_task('extract_sales_data')
        transform_task = dag.get_task('transform_sales_data')
        load_task = dag.get_task('load_sales_data')

        # Extract -> Transform -> Load
        assert transform_task in extract_task.downstream_list
        assert load_task in transform_task.downstream_list

    @patch('airflow.providers.postgres.hooks.postgres.PostgresHook')
    def test_extract_returns_dataframe(self, mock_hook):
        """Test extract task returns valid dataframe."""
        # Setup mock
        mock_hook.return_value.get_pandas_df.return_value = pd.DataFrame({
            'order_id': [1, 2, 3],
            'revenue': [100, 200, 300]
        })

        from airflow.dags.sales_analytics_etl import extract_sales_data

        context = {'execution_date': datetime.now()}
        result = extract_sales_data(**context)

        assert result['status'] == 'success'
        assert result['records'] == 3


class TestDataWarehouseIntegration:
    """Integration tests for data warehouse."""

    @pytest.fixture
    def dw_connection(self):
        """Get test database connection."""
        from sqlalchemy import create_engine
        return create_engine('postgresql://test:test@localhost:5432/analytics_test')

    def test_fact_tables_exist(self, dw_connection):
        """Test all fact tables are created."""
        expected_tables = [
            'fact_sales',
            'fact_daily_milk_production',
            'fact_daily_inventory_snapshot',
            'fact_customer_rfm',
            'fact_customer_ltv',
            'fact_general_ledger',
            'fact_supplier_performance'
        ]

        inspector = inspect(dw_connection)
        actual_tables = inspector.get_table_names()

        for table in expected_tables:
            assert table in actual_tables, f"Missing table: {table}"

    def test_dimension_tables_populated(self, dw_connection):
        """Test dimension tables have data."""
        dimensions = ['dim_date', 'dim_product', 'dim_customer', 'dim_warehouse']

        for dim in dimensions:
            result = pd.read_sql(f"SELECT COUNT(*) as cnt FROM {dim}", dw_connection)
            assert result.iloc[0]['cnt'] > 0, f"Empty dimension: {dim}"

    def test_referential_integrity(self, dw_connection):
        """Test foreign key relationships are valid."""
        # Check fact_sales references valid dimension keys
        orphan_check = """
            SELECT COUNT(*) as orphans
            FROM fact_sales fs
            LEFT JOIN dim_product p ON fs.product_sk = p.product_sk
            WHERE p.product_sk IS NULL
        """
        result = pd.read_sql(orphan_check, dw_connection)
        assert result.iloc[0]['orphans'] == 0, "Orphan records found in fact_sales"


class TestAPIIntegration:
    """Integration tests for analytics APIs."""

    @pytest.fixture
    def client(self):
        """Get test API client."""
        from fastapi.testclient import TestClient
        from api.main import app
        return TestClient(app)

    def test_health_check(self, client):
        """Test API health endpoint."""
        response = client.get("/health")
        assert response.status_code == 200
        assert response.json()['status'] == 'healthy'

    def test_dashboard_api_returns_data(self, client):
        """Test dashboard API returns valid data."""
        response = client.get("/api/v1/dashboards/sales/summary")
        assert response.status_code == 200
        assert 'data' in response.json()

    def test_report_execution_api(self, client):
        """Test report execution API."""
        response = client.post(
            "/api/v1/reports/1/execute",
            json={
                "parameters": {"start_date": "2026-01-01", "end_date": "2026-01-31"},
                "output_format": "json"
            }
        )
        assert response.status_code in [200, 202]  # Success or accepted for async

    def test_unauthorized_access_rejected(self, client):
        """Test unauthorized requests are rejected."""
        response = client.get(
            "/api/v1/dashboards/sales/summary",
            headers={}  # No auth header
        )
        assert response.status_code == 401
```

### 3.3 Performance Testing

```python
# tests/performance/test_dashboard_performance.py
"""
Performance Tests for Analytics Dashboards
Milestone 110 - Days 591-600
"""

import pytest
import time
import statistics
from locust import HttpUser, task, between
from concurrent.futures import ThreadPoolExecutor, as_completed


class DashboardPerformanceTests:
    """Performance tests for dashboard load times."""

    @pytest.fixture
    def performance_thresholds(self):
        return {
            'dashboard_load_p95': 3000,  # 3 seconds
            'api_response_p95': 500,      # 500ms
            'report_generation_p95': 10000,  # 10 seconds
            'concurrent_users': 50
        }

    def test_sales_dashboard_load_time(self, client, performance_thresholds):
        """Test sales dashboard loads within threshold."""
        load_times = []

        for _ in range(10):
            start = time.time()
            response = client.get("/api/v1/dashboards/sales")
            load_times.append((time.time() - start) * 1000)

        p95_load_time = sorted(load_times)[int(len(load_times) * 0.95)]
        assert p95_load_time < performance_thresholds['dashboard_load_p95'], \
            f"P95 load time {p95_load_time}ms exceeds threshold"

    def test_concurrent_dashboard_access(self, client, performance_thresholds):
        """Test dashboard handles concurrent users."""

        def fetch_dashboard():
            start = time.time()
            response = client.get("/api/v1/dashboards/sales")
            return {
                'status': response.status_code,
                'time_ms': (time.time() - start) * 1000
            }

        with ThreadPoolExecutor(max_workers=performance_thresholds['concurrent_users']) as executor:
            futures = [executor.submit(fetch_dashboard) for _ in range(100)]
            results = [f.result() for f in as_completed(futures)]

        # Check all requests succeeded
        success_rate = sum(1 for r in results if r['status'] == 200) / len(results)
        assert success_rate >= 0.99, f"Success rate {success_rate} below 99%"

        # Check response times
        response_times = [r['time_ms'] for r in results]
        p95_time = sorted(response_times)[int(len(response_times) * 0.95)]
        assert p95_time < performance_thresholds['dashboard_load_p95']

    def test_large_dataset_query_performance(self, dw_connection):
        """Test query performance with large datasets."""
        # Test query that aggregates millions of rows
        query = """
            SELECT
                d.month_name,
                SUM(fs.revenue) as total_revenue,
                COUNT(*) as transaction_count
            FROM fact_sales fs
            JOIN dim_date d ON fs.date_sk = d.date_sk
            WHERE d.year_actual = 2025
            GROUP BY d.month_name
        """

        start = time.time()
        pd.read_sql(query, dw_connection)
        execution_time = (time.time() - start) * 1000

        assert execution_time < 5000, f"Query took {execution_time}ms, exceeds 5s threshold"


class LoadTestUser(HttpUser):
    """Locust load test user for stress testing."""

    wait_time = between(1, 3)

    @task(3)
    def view_sales_dashboard(self):
        self.client.get("/api/v1/dashboards/sales")

    @task(2)
    def view_inventory_dashboard(self):
        self.client.get("/api/v1/dashboards/inventory")

    @task(1)
    def execute_report(self):
        self.client.post(
            "/api/v1/reports/1/execute",
            json={"parameters": {}, "output_format": "json"}
        )

    @task(1)
    def search_customers(self):
        self.client.get("/api/v1/customers/search?q=test")
```

### 3.4 Data Validation Testing

```python
# tests/data_validation/test_data_accuracy.py
"""
Data Validation Tests
Milestone 110 - Days 591-600
"""

import pytest
import pandas as pd
from decimal import Decimal


class TestSalesDataAccuracy:
    """Tests for sales data accuracy and reconciliation."""

    @pytest.fixture
    def source_connection(self):
        """Connection to Odoo source database."""
        pass

    @pytest.fixture
    def dw_connection(self):
        """Connection to analytics data warehouse."""
        pass

    def test_sales_revenue_reconciliation(self, source_connection, dw_connection):
        """Test DW sales revenue matches source system."""
        # Get source totals
        source_query = """
            SELECT SUM(amount_total) as total_revenue
            FROM sale_order
            WHERE state IN ('sale', 'done')
              AND date_order >= '2026-01-01'
              AND date_order < '2026-02-01'
        """
        source_total = pd.read_sql(source_query, source_connection).iloc[0]['total_revenue']

        # Get DW totals
        dw_query = """
            SELECT SUM(revenue) as total_revenue
            FROM fact_sales fs
            JOIN dim_date d ON fs.date_sk = d.date_sk
            WHERE d.date_actual >= '2026-01-01'
              AND d.date_actual < '2026-02-01'
        """
        dw_total = pd.read_sql(dw_query, dw_connection).iloc[0]['total_revenue']

        # Allow 0.01% variance for rounding
        variance_pct = abs(dw_total - source_total) / source_total * 100
        assert variance_pct < 0.01, f"Revenue variance {variance_pct}% exceeds threshold"

    def test_transaction_count_reconciliation(self, source_connection, dw_connection):
        """Test DW transaction count matches source."""
        source_count = pd.read_sql(
            "SELECT COUNT(*) as cnt FROM sale_order WHERE state IN ('sale', 'done')",
            source_connection
        ).iloc[0]['cnt']

        dw_count = pd.read_sql(
            "SELECT COUNT(DISTINCT order_id) as cnt FROM fact_sales",
            dw_connection
        ).iloc[0]['cnt']

        assert source_count == dw_count, f"Count mismatch: source={source_count}, dw={dw_count}"


class TestInventoryDataAccuracy:
    """Tests for inventory data accuracy."""

    def test_stock_levels_match_source(self, source_connection, dw_connection):
        """Test inventory levels match Odoo stock quants."""
        source_query = """
            SELECT
                pp.id as product_id,
                SUM(sq.quantity) as qty_on_hand
            FROM stock_quant sq
            JOIN product_product pp ON sq.product_id = pp.id
            JOIN stock_location sl ON sq.location_id = sl.id
            WHERE sl.usage = 'internal'
            GROUP BY pp.id
        """
        source_stock = pd.read_sql(source_query, source_connection)

        dw_query = """
            SELECT
                p.product_id,
                SUM(fis.quantity_on_hand) as qty_on_hand
            FROM fact_daily_inventory_snapshot fis
            JOIN dim_product p ON fis.product_sk = p.product_sk
            JOIN dim_date d ON fis.date_sk = d.date_sk
            WHERE d.date_actual = CURRENT_DATE
            GROUP BY p.product_id
        """
        dw_stock = pd.read_sql(dw_query, dw_connection)

        # Merge and compare
        merged = source_stock.merge(dw_stock, on='product_id', suffixes=('_source', '_dw'))

        for _, row in merged.iterrows():
            assert row['qty_on_hand_source'] == row['qty_on_hand_dw'], \
                f"Stock mismatch for product {row['product_id']}"


class TestFinancialDataAccuracy:
    """Tests for financial data accuracy."""

    def test_trial_balance_balances(self, dw_connection):
        """Test trial balance debits equal credits."""
        query = """
            SELECT
                SUM(closing_debit) as total_debit,
                SUM(closing_credit) as total_credit
            FROM fact_trial_balance
            WHERE fiscal_period_sk = (
                SELECT MAX(fiscal_period_sk) FROM fact_trial_balance
            )
        """
        result = pd.read_sql(query, dw_connection).iloc[0]

        assert abs(result['total_debit'] - result['total_credit']) < 0.01, \
            "Trial balance does not balance"

    def test_ar_aging_matches_subledger(self, source_connection, dw_connection):
        """Test AR aging matches Odoo receivables."""
        source_query = """
            SELECT SUM(amount_residual) as total_ar
            FROM account_move
            WHERE move_type = 'out_invoice'
              AND state = 'posted'
              AND payment_state IN ('not_paid', 'partial')
        """
        source_ar = pd.read_sql(source_query, source_connection).iloc[0]['total_ar']

        dw_query = """
            SELECT SUM(balance_amount) as total_ar
            FROM fact_ar_aging
            WHERE snapshot_date_sk = (
                SELECT MAX(snapshot_date_sk) FROM fact_ar_aging
            )
        """
        dw_ar = pd.read_sql(dw_query, dw_connection).iloc[0]['total_ar']

        variance_pct = abs(dw_ar - source_ar) / source_ar * 100
        assert variance_pct < 0.01, f"AR variance {variance_pct}% exceeds threshold"
```

---

## 4. User Acceptance Testing

### 4.1 UAT Test Cases

| Test ID | Module | Test Case | Expected Result | Priority |
|---------|--------|-----------|-----------------|----------|
| UAT-101 | Executive Dashboard | CEO can view revenue KPIs | All KPIs display correctly | P0 |
| UAT-102 | Executive Dashboard | CFO can drill down to P&L details | Drill-down shows transaction details | P0 |
| UAT-103 | Sales Analytics | Sales manager views channel performance | Channel comparison chart displays | P0 |
| UAT-104 | Sales Analytics | Export sales report to Excel | Excel file downloads with data | P0 |
| UAT-105 | Farm Analytics | Farm manager views milk yield trends | Yield chart shows 30-day trend | P0 |
| UAT-106 | Farm Analytics | Animal health alerts trigger correctly | High SCC animals flagged | P0 |
| UAT-107 | Inventory Analytics | Warehouse manager sees stock levels | Real-time stock displayed | P0 |
| UAT-108 | Inventory Analytics | Expiration alerts show correct items | Items expiring in 7 days listed | P0 |
| UAT-109 | Customer Analytics | Marketing views customer segments | RFM segments displayed | P0 |
| UAT-110 | Customer Analytics | At-risk customers identified | Churn risk list accurate | P0 |
| UAT-111 | Financial Analytics | Accountant views trial balance | Balances match GL | P0 |
| UAT-112 | Financial Analytics | AR aging report accurate | Aging buckets correct | P0 |
| UAT-113 | Supply Chain | Procurement views supplier scores | Scorecards display correctly | P0 |
| UAT-114 | Supply Chain | OTIF metrics calculated | OTIF percentage accurate | P0 |
| UAT-115 | Custom Reports | User creates new report | Report saves and runs | P0 |
| UAT-116 | Custom Reports | Scheduled report delivers | Email received with attachment | P0 |

### 4.2 UAT Sign-off Template

```markdown
# User Acceptance Testing Sign-off

## Module: [Module Name]
## Tester: [Business User Name]
## Date: [Test Date]

### Test Summary
| Total Test Cases | Passed | Failed | Blocked |
|------------------|--------|--------|---------|
| [X]              | [X]    | [X]    | [X]     |

### Test Results

| Test ID | Result | Comments |
|---------|--------|----------|
| UAT-XXX | PASS/FAIL | [Notes] |

### Issues Found
| Issue ID | Description | Severity | Status |
|----------|-------------|----------|--------|
| [ID]     | [Desc]      | [Sev]    | [Status] |

### Sign-off
- [ ] All P0 test cases passed
- [ ] All critical issues resolved
- [ ] Performance acceptable
- [ ] Data accuracy verified

**Approved for Production:** YES / NO

**Signature:** ________________________
**Date:** ________________________
```

---

## 5. Daily Development Schedule

### Day 591-600: Testing Phase

| Day | Focus Area | Key Deliverables |
|-----|------------|------------------|
| 591 | Test Planning | Test strategy, test cases, environment setup |
| 592 | Unit Testing | Unit tests for all calculation modules |
| 593 | Unit Testing | Unit tests for ETL components |
| 594 | Integration Testing | ETL pipeline integration tests |
| 595 | Integration Testing | API integration tests |
| 596 | Performance Testing | Load testing, stress testing |
| 597 | Data Validation | Reconciliation tests, accuracy tests |
| 598 | Security Testing | Access control, penetration testing |
| 599 | UAT Execution | Business user testing |
| 600 | UAT Sign-off | Final validation, production readiness |

---

## 6. Test Metrics and Reporting

### 6.1 Test Coverage Report Template

```
=================================================================
               PHASE 11 ANALYTICS TEST COVERAGE REPORT
=================================================================
Generated: [Date]
Test Execution ID: [ID]

SUMMARY
-------
Total Test Cases:     [XXX]
Passed:               [XXX] ([XX]%)
Failed:               [XXX] ([XX]%)
Blocked:              [XXX] ([XX]%)
Not Executed:         [XXX] ([XX]%)

CODE COVERAGE
-------------
Module                  Lines     Covered    Coverage%
---------------------------------------------------------
sales_analytics         2,450     2,205      90.0%
farm_analytics          1,890     1,701      90.0%
inventory_analytics     2,100     1,890      90.0%
customer_analytics      1,750     1,575      90.0%
financial_analytics     2,300     2,070      90.0%
supply_chain_analytics  1,650     1,485      90.0%
custom_reports          1,200     1,080      90.0%
---------------------------------------------------------
TOTAL                   13,340    12,006     90.0%

DEFECT SUMMARY
--------------
Critical:    [X] (Target: 0)
High:        [X]
Medium:      [X]
Low:         [X]
Total:       [XX]

PERFORMANCE RESULTS
-------------------
Metric                  Target      Actual      Status
---------------------------------------------------------
Dashboard Load (P95)    < 3s        [X]s        PASS/FAIL
API Response (P95)      < 500ms     [X]ms       PASS/FAIL
Report Gen (P95)        < 10s       [X]s        PASS/FAIL
Concurrent Users        50          [X]         PASS/FAIL

RECOMMENDATION: [PASS / CONDITIONAL PASS / FAIL]
=================================================================
```

---

## 7. Production Readiness Checklist

### 7.1 Go-Live Checklist

| Category | Item | Status | Owner |
|----------|------|--------|-------|
| **Testing** | All unit tests passing | ☐ | Dev Team |
| **Testing** | Integration tests passing | ☐ | QA Team |
| **Testing** | Performance benchmarks met | ☐ | QA Team |
| **Testing** | Security scan completed | ☐ | Security |
| **Testing** | UAT signed off | ☐ | Business |
| **Data** | Data migration validated | ☐ | Data Team |
| **Data** | Reconciliation completed | ☐ | Finance |
| **Data** | Historical data loaded | ☐ | Data Team |
| **Infrastructure** | Production environment ready | ☐ | DevOps |
| **Infrastructure** | Monitoring configured | ☐ | DevOps |
| **Infrastructure** | Backup procedures tested | ☐ | DevOps |
| **Documentation** | User documentation complete | ☐ | Tech Writer |
| **Documentation** | Admin documentation complete | ☐ | Tech Writer |
| **Training** | End-user training completed | ☐ | Training |
| **Training** | Admin training completed | ☐ | Training |
| **Support** | Support procedures defined | ☐ | Support |
| **Support** | Escalation paths documented | ☐ | Support |

### 7.2 Final Sign-off

```
=================================================================
           PHASE 11 PRODUCTION DEPLOYMENT SIGN-OFF
=================================================================

Project: Smart Dairy Digital Portal - Phase 11 Analytics
Go-Live Date: [Date]

APPROVALS
---------

Technical Lead:
Name: _________________________ Signature: _____________ Date: _______

QA Lead:
Name: _________________________ Signature: _____________ Date: _______

Business Owner:
Name: _________________________ Signature: _____________ Date: _______

IT Operations:
Name: _________________________ Signature: _____________ Date: _______

Security Officer:
Name: _________________________ Signature: _____________ Date: _______

Project Manager:
Name: _________________________ Signature: _____________ Date: _______

DECLARATION
-----------
We confirm that Phase 11 Analytics Platform has completed all testing
requirements and is approved for production deployment.

=================================================================
```

---

## 8. Requirement Traceability Matrix

| Requirement ID | Description | Test Coverage | Status |
|----------------|-------------|---------------|--------|
| RFP-BI-001 | BI Platform deployment | TC-101-* | ☐ |
| RFP-DASH-001 | Executive dashboards | TC-102-* | ☐ |
| SRS-ANALYTICS-001-042 | All analytics requirements | Full test suite | ☐ |
| NFR-PERF-001 | 3-second load time | Performance tests | ☐ |
| NFR-SEC-001 | RBAC implementation | Security tests | ☐ |
| NFR-DATA-001 | Data accuracy 99.99% | Reconciliation tests | ☐ |

---

**Document End**

*Milestone 110: Analytics Testing and Quality Assurance - Days 591-600*

---

## Phase 11 Completion Summary

Phase 11 (Commerce - Advanced Analytics) delivers a comprehensive business intelligence and analytics platform comprising:

- **10 Milestones** spanning 100 development days (Days 501-600)
- **Apache Superset** BI platform with SSO integration
- **Star Schema** data warehouse on PostgreSQL 16
- **7 Analytics Modules**: Sales, Farm, Inventory, Customer, Financial, Supply Chain, Custom Reports
- **Predictive Models**: Yield forecasting, churn prediction, demand forecasting
- **Self-Service Reporting**: Report builder, scheduling, multi-format export
- **Comprehensive Testing**: Unit, integration, performance, security, UAT

**Phase 11 Timeline:**
- Start: Day 501
- End: Day 600
- Duration: 100 days
- Team: 3 Full-Stack Developers
