# Milestone 122: Database Optimization

## Smart Dairy Digital Smart Portal + ERP - Phase 13: Performance & AI Optimization

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 122 of 150 (2 of 10 in Phase 13)                                       |
| **Title**        | Database Optimization                                                  |
| **Phase**        | Phase 13 - Optimization: Performance & AI                              |
| **Days**         | Days 606-610 (of 750 total)                                            |
| **Duration**     | 5 working days                                                         |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Frontend Lead)        |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

Optimize PostgreSQL database performance through strategic indexing, query optimization, table partitioning, and connection pooling to achieve <50ms query response time for 90% of queries while supporting 1000+ concurrent users.

### 1.2 Objectives

1. Implement 50+ performance indexes based on baseline analysis
2. Optimize top 20 slow queries identified in Milestone 121
3. Implement table partitioning for orders, logs, and IoT data tables
4. Configure and optimize connection pooling (PgBouncer)
5. Eliminate all identified N+1 query patterns
6. Implement database vacuum and maintenance automation
7. Set up read replicas for reporting queries
8. Achieve 90% of queries under 50ms target

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| Performance Index Migration       | Dev 1  | SQL migrations    | 606     |
| Composite Index Strategy          | Dev 1  | SQL + Docs        | 606     |
| Query Optimization Scripts        | Dev 1  | Python/SQL        | 607     |
| N+1 Query Fixes                   | Dev 1  | Python modules    | 607     |
| Table Partitioning Setup          | Dev 2  | SQL migrations    | 608     |
| PgBouncer Configuration           | Dev 2  | Config files      | 608     |
| Read Replica Setup                | Dev 2  | Docker/Config     | 609     |
| Vacuum Automation                 | Dev 2  | Cron/SQL          | 609     |
| Query Result Caching (ORM level)  | Dev 1  | Python module     | 610     |
| Database Optimization Report      | All    | Markdown/PDF      | 610     |

### 1.4 Prerequisites

- Milestone 121 complete with baseline report
- Slow query list from baseline analysis
- Missing index recommendations
- Database superuser access
- Maintenance window scheduled for migrations

### 1.5 Success Criteria

- [ ] 90% of queries execute in <50ms (measured via pg_stat_statements)
- [ ] All identified N+1 patterns eliminated
- [ ] Table partitioning operational for orders (by month) and IoT data (by day)
- [ ] Connection pooling handling 500+ concurrent connections
- [ ] Read replica lag <1 second
- [ ] Automated vacuum running on schedule
- [ ] Zero performance regression on existing queries

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-PERF-002 | RFP    | Support 1000+ concurrent users           | 608     | Connection pooling          |
| BRD-SCALE-001| BRD    | Support 800+ cattle, 3000L/day           | 606-610 | Query optimization          |
| SRS-PERF-002 | SRS    | Database query time <50ms (P90)          | 606-610 | All optimizations           |
| IMPL-DB-01   | Guide  | Index optimization strategy              | 606     | Index migrations            |
| IMPL-DB-02   | Guide  | Query optimization patterns              | 607     | Query rewrites              |
| IMPL-DB-03   | Guide  | Partitioning for large tables            | 608     | Partitioning setup          |

---

## 3. Day-by-Day Breakdown

### Day 606 - Index Strategy Implementation

**Objective:** Deploy comprehensive indexing strategy based on baseline analysis findings.

#### Dev 1 - Backend Lead (8h)

**Task 1: Core Performance Indexes (4h)**

```sql
-- database/migrations/20260206_001_performance_indexes.sql

-- ============================================
-- USERS & AUTHENTICATION INDEXES
-- ============================================
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_users_email_active
ON res_users(login) WHERE active = true;

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_users_company_active
ON res_users(company_id, active) INCLUDE (login, name);

-- ============================================
-- PRODUCT CATALOG INDEXES
-- ============================================
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_product_template_active_sale
ON product_template(sale_ok, active)
WHERE sale_ok = true AND active = true;

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_product_template_category
ON product_template(categ_id, list_price)
WHERE active = true;

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_product_template_search
ON product_template USING gin(to_tsvector('english', name || ' ' || COALESCE(description, '')));

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_product_product_barcode
ON product_product(barcode) WHERE barcode IS NOT NULL;

-- ============================================
-- SALES ORDER INDEXES
-- ============================================
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sale_order_partner_state
ON sale_order(partner_id, state, date_order DESC)
WHERE state NOT IN ('cancel', 'draft');

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sale_order_date_state
ON sale_order(date_order DESC, state)
INCLUDE (partner_id, amount_total);

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sale_order_user_team
ON sale_order(user_id, team_id, state);

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sale_order_line_product
ON sale_order_line(product_id, order_id)
INCLUDE (product_uom_qty, price_total);

-- ============================================
-- INVENTORY INDEXES
-- ============================================
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_stock_quant_product_location
ON stock_quant(product_id, location_id)
WHERE quantity > 0;

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_stock_quant_lot
ON stock_quant(lot_id, product_id)
WHERE lot_id IS NOT NULL;

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_stock_move_state_product
ON stock_move(state, product_id, date)
WHERE state NOT IN ('cancel', 'draft');

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_stock_picking_state_date
ON stock_picking(state, scheduled_date DESC)
WHERE state NOT IN ('cancel', 'done');

-- ============================================
-- FARM MANAGEMENT INDEXES
-- ============================================
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_farm_animal_status
ON farm_animal(status, farm_id)
WHERE status = 'active';

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_farm_animal_rfid
ON farm_animal(rfid_tag) WHERE rfid_tag IS NOT NULL;

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_farm_animal_ear_tag
ON farm_animal(ear_tag);

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_farm_milk_record_animal_date
ON farm_milk_record(animal_id, record_date DESC)
INCLUDE (morning_yield, evening_yield);

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_farm_health_record_animal
ON farm_health_record(animal_id, record_date DESC, record_type);

-- ============================================
-- ACCOUNTING INDEXES
-- ============================================
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_account_move_partner_state
ON account_move(partner_id, state, invoice_date DESC)
WHERE move_type IN ('out_invoice', 'out_refund');

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_account_move_line_account
ON account_move_line(account_id, date DESC)
WHERE parent_state = 'posted';

CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_account_payment_partner
ON account_payment(partner_id, state, date DESC);
```

**Task 2: Composite & Covering Indexes (4h)**

```sql
-- database/migrations/20260206_002_composite_indexes.sql

-- ============================================
-- DASHBOARD QUERY INDEXES
-- ============================================

-- Sales Dashboard: Revenue by period
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sale_order_dashboard
ON sale_order(company_id, date_order DESC, state)
INCLUDE (amount_total, partner_id, user_id)
WHERE state IN ('sale', 'done');

-- Farm Dashboard: Production summary
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_farm_production_dashboard
ON farm_milk_record(farm_id, record_date DESC)
INCLUDE (total_yield, fat_percentage, protein_percentage);

-- Inventory Dashboard: Stock levels
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_inventory_dashboard
ON stock_quant(company_id, location_id, product_id)
INCLUDE (quantity, reserved_quantity)
WHERE quantity > 0;

-- ============================================
-- REPORTING INDEXES
-- ============================================

-- Sales Report: By product category
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_sale_report_category
ON sale_order_line(create_date DESC)
INCLUDE (product_id, product_uom_qty, price_subtotal);

-- Customer Report: Purchase history
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_customer_purchase_history
ON sale_order(partner_id, date_order DESC)
INCLUDE (amount_total, state)
WHERE state IN ('sale', 'done');

-- ============================================
-- SEARCH OPTIMIZATION INDEXES
-- ============================================

-- Partner search
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_partner_search
ON res_partner USING gin(
    to_tsvector('english',
        COALESCE(name, '') || ' ' ||
        COALESCE(email, '') || ' ' ||
        COALESCE(phone, '') || ' ' ||
        COALESCE(mobile, '')
    )
);

-- Animal search
CREATE INDEX CONCURRENTLY IF NOT EXISTS idx_animal_search
ON farm_animal USING gin(
    to_tsvector('english',
        COALESCE(name, '') || ' ' ||
        COALESCE(ear_tag, '') || ' ' ||
        COALESCE(rfid_tag, '')
    )
);
```

#### Dev 2 - Full-Stack Developer (8h)

**Task 1: Index Deployment Automation (4h)**

```python
# scripts/deploy_indexes.py
import psycopg2
import os
import time
import logging
from pathlib import Path
from typing import List, Dict

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class IndexDeployer:
    """Deploy database indexes with safety checks"""

    def __init__(self, connection_string: str):
        self.connection_string = connection_string
        self.deployed_indexes: List[str] = []
        self.failed_indexes: List[Dict] = []

    def deploy_migration(self, migration_file: Path) -> bool:
        """Deploy a migration file with individual index tracking"""
        logger.info(f"Deploying migration: {migration_file.name}")

        with open(migration_file, 'r') as f:
            sql_content = f.read()

        # Split into individual statements
        statements = self._split_statements(sql_content)

        conn = psycopg2.connect(self.connection_string)
        conn.autocommit = True  # Required for CONCURRENTLY

        try:
            for stmt in statements:
                if not stmt.strip():
                    continue

                index_name = self._extract_index_name(stmt)
                logger.info(f"Creating index: {index_name}")

                start_time = time.time()
                try:
                    with conn.cursor() as cur:
                        cur.execute(stmt)

                    duration = time.time() - start_time
                    logger.info(f"  Created in {duration:.2f}s")
                    self.deployed_indexes.append(index_name)

                except psycopg2.Error as e:
                    duration = time.time() - start_time
                    logger.error(f"  Failed after {duration:.2f}s: {e}")
                    self.failed_indexes.append({
                        'index': index_name,
                        'error': str(e),
                        'statement': stmt[:200]
                    })

            return len(self.failed_indexes) == 0

        finally:
            conn.close()

    def _split_statements(self, sql: str) -> List[str]:
        """Split SQL into individual statements"""
        statements = []
        current = []

        for line in sql.split('\n'):
            stripped = line.strip()
            if stripped.startswith('--') or not stripped:
                continue
            current.append(line)
            if stripped.endswith(';'):
                statements.append('\n'.join(current))
                current = []

        return statements

    def _extract_index_name(self, stmt: str) -> str:
        """Extract index name from CREATE INDEX statement"""
        import re
        match = re.search(r'INDEX\s+(?:CONCURRENTLY\s+)?(?:IF\s+NOT\s+EXISTS\s+)?(\w+)', stmt, re.IGNORECASE)
        return match.group(1) if match else 'unknown'

    def verify_indexes(self) -> Dict[str, bool]:
        """Verify all indexes exist and are valid"""
        conn = psycopg2.connect(self.connection_string)
        results = {}

        try:
            with conn.cursor() as cur:
                for index_name in self.deployed_indexes:
                    cur.execute("""
                        SELECT indexrelid::regclass, indisvalid
                        FROM pg_index
                        WHERE indexrelid::regclass::text = %s
                    """, (index_name,))

                    row = cur.fetchone()
                    results[index_name] = row[1] if row else False

        finally:
            conn.close()

        return results

    def generate_report(self) -> str:
        """Generate deployment report"""
        report = ["# Index Deployment Report\n"]
        report.append(f"**Date:** {time.strftime('%Y-%m-%d %H:%M:%S')}\n")
        report.append(f"**Deployed:** {len(self.deployed_indexes)}\n")
        report.append(f"**Failed:** {len(self.failed_indexes)}\n\n")

        if self.deployed_indexes:
            report.append("## Successfully Deployed\n")
            for idx in self.deployed_indexes:
                report.append(f"- {idx}\n")

        if self.failed_indexes:
            report.append("\n## Failed Indexes\n")
            for fail in self.failed_indexes:
                report.append(f"- **{fail['index']}**: {fail['error']}\n")

        return ''.join(report)


if __name__ == '__main__':
    deployer = IndexDeployer(os.environ['DATABASE_URL'])

    migrations_dir = Path('./database/migrations')
    for migration in sorted(migrations_dir.glob('20260206_*.sql')):
        deployer.deploy_migration(migration)

    # Verify and report
    verification = deployer.verify_indexes()
    print(deployer.generate_report())
```

**Task 2: Index Usage Monitoring (4h)**

```python
# backend/src/monitoring/index_monitor.py
from typing import Dict, List, Any
import psycopg2
from psycopg2.extras import RealDictCursor
import logging

logger = logging.getLogger(__name__)

class IndexMonitor:
    """Monitor index usage and health"""

    def __init__(self, connection_string: str):
        self.connection_string = connection_string

    def get_unused_indexes(self, min_size_mb: float = 1.0) -> List[Dict[str, Any]]:
        """Find indexes that are not being used"""
        query = """
            SELECT
                schemaname,
                relname as table_name,
                indexrelname as index_name,
                pg_size_pretty(pg_relation_size(indexrelid)) as index_size,
                pg_relation_size(indexrelid) as size_bytes,
                idx_scan as index_scans,
                idx_tup_read,
                idx_tup_fetch
            FROM pg_stat_user_indexes
            WHERE idx_scan = 0
            AND pg_relation_size(indexrelid) > %s * 1024 * 1024
            ORDER BY pg_relation_size(indexrelid) DESC
        """

        with psycopg2.connect(self.connection_string) as conn:
            with conn.cursor(cursor_factory=RealDictCursor) as cur:
                cur.execute(query, (min_size_mb,))
                return [dict(row) for row in cur.fetchall()]

    def get_duplicate_indexes(self) -> List[Dict[str, Any]]:
        """Find potentially duplicate indexes"""
        query = """
            SELECT
                pg_size_pretty(sum(pg_relation_size(idx))::bigint) as total_size,
                array_agg(idx) as indexes,
                array_agg(pg_size_pretty(pg_relation_size(idx))) as sizes
            FROM (
                SELECT
                    indexrelid::regclass as idx,
                    (indrelid::text || E'\n' || indclass::text || E'\n' ||
                     indkey::text || E'\n' || coalesce(indexprs::text, '') ||
                     E'\n' || coalesce(indpred::text, '')) as key
                FROM pg_index
            ) sub
            GROUP BY key
            HAVING count(*) > 1
            ORDER BY sum(pg_relation_size(idx)) DESC
        """

        with psycopg2.connect(self.connection_string) as conn:
            with conn.cursor(cursor_factory=RealDictCursor) as cur:
                cur.execute(query)
                return [dict(row) for row in cur.fetchall()]

    def get_index_bloat(self, threshold_pct: float = 30.0) -> List[Dict[str, Any]]:
        """Find indexes with significant bloat"""
        query = """
            WITH index_stats AS (
                SELECT
                    nspname as schema_name,
                    tblname as table_name,
                    idxname as index_name,
                    bs*(relpages)::bigint as real_size,
                    bs*(relpages-est_pages)::bigint as bloat_size,
                    100 * (relpages-est_pages)::float / relpages as bloat_ratio
                FROM (
                    SELECT
                        coalesce(1 + ceil(reltuples/floor((bs-pageopqdata-pagehdr)/(4+nulldatahdrwidth)::float)), 0) as est_pages,
                        bs, nspname, tblname, idxname, relpages
                    FROM (
                        SELECT
                            maxalign, bs, nspname, tblname, idxname, reltuples, relpages, relam,
                            (index_tuple_hdr_bm + maxalign -
                             CASE WHEN index_tuple_hdr_bm%maxalign = 0 THEN maxalign
                             ELSE index_tuple_hdr_bm%maxalign END
                             + nulldatawidth + maxalign -
                             CASE WHEN nulldatawidth%maxalign = 0 THEN maxalign
                             ELSE nulldatawidth%maxalign END
                            )::numeric as nulldatahdrwidth,
                            pagehdr, pageopqdata
                        FROM (
                            SELECT
                                i.nspname, i.tblname, i.idxname, i.reltuples, i.relpages, i.relam,
                                current_setting('block_size')::numeric as bs,
                                CASE WHEN version() ~ 'mingw32' OR version() ~ '64-bit|x86_64|ppc64|ia64|amd64'
                                     THEN 8 ELSE 4 END as maxalign,
                                24 as pagehdr,
                                16 as pageopqdata,
                                CASE WHEN max(coalesce(s.null_frac,0)) = 0 THEN 2
                                     ELSE 2 + (( 32 + 8 - 1 ) / 8) END as index_tuple_hdr_bm,
                                sum((1-coalesce(s.null_frac, 0)) * coalesce(s.avg_width, 1024)) as nulldatawidth
                            FROM pg_stat_user_indexes i
                            JOIN pg_index idx ON i.indexrelid = idx.indexrelid
                            JOIN pg_attribute a ON a.attrelid = i.indexrelid
                            LEFT JOIN pg_stats s ON s.tablename = i.tblname AND s.attname = a.attname
                            WHERE a.attnum > 0
                            GROUP BY 1,2,3,4,5,6,7,8,9,10
                        ) sub1
                    ) sub2
                ) sub3
                WHERE relpages > 0
            )
            SELECT * FROM index_stats
            WHERE bloat_ratio > %s
            ORDER BY bloat_size DESC
            LIMIT 20
        """

        with psycopg2.connect(self.connection_string) as conn:
            with conn.cursor(cursor_factory=RealDictCursor) as cur:
                cur.execute(query, (threshold_pct,))
                return [dict(row) for row in cur.fetchall()]

    def generate_health_report(self) -> Dict[str, Any]:
        """Generate comprehensive index health report"""
        return {
            'unused_indexes': self.get_unused_indexes(),
            'duplicate_indexes': self.get_duplicate_indexes(),
            'bloated_indexes': self.get_index_bloat(),
            'recommendations': self._generate_recommendations()
        }

    def _generate_recommendations(self) -> List[str]:
        """Generate index optimization recommendations"""
        recommendations = []

        unused = self.get_unused_indexes()
        if unused:
            total_size = sum(idx['size_bytes'] for idx in unused)
            recommendations.append(
                f"Consider dropping {len(unused)} unused indexes to save "
                f"{total_size / 1024 / 1024:.1f}MB"
            )

        duplicates = self.get_duplicate_indexes()
        if duplicates:
            recommendations.append(
                f"Review {len(duplicates)} potential duplicate index groups"
            )

        bloated = self.get_index_bloat()
        if bloated:
            recommendations.append(
                f"REINDEX {len(bloated)} indexes with >30% bloat"
            )

        return recommendations
```

#### Dev 3 - Frontend Lead (8h)

**Task 1: Query Result Caching UI Components (4h)**

```tsx
// frontend/src/components/admin/DatabaseOptimization.tsx
import React, { useState } from 'react';
import { Card, Table, Button, Tag, Progress, Tabs, Alert, Statistic, Row, Col } from 'antd';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';

interface IndexInfo {
  index_name: string;
  table_name: string;
  index_size: string;
  index_scans: number;
  usage_status: string;
}

interface QueryStats {
  query: string;
  calls: number;
  total_time_ms: number;
  avg_time_ms: number;
  max_time_ms: number;
}

const DatabaseOptimizationDashboard: React.FC = () => {
  const queryClient = useQueryClient();
  const [activeTab, setActiveTab] = useState('overview');

  const { data: indexStats, isLoading: indexLoading } = useQuery({
    queryKey: ['index-stats'],
    queryFn: () => fetch('/api/v1/admin/database/indexes').then(r => r.json())
  });

  const { data: queryStats, isLoading: queryLoading } = useQuery({
    queryKey: ['query-stats'],
    queryFn: () => fetch('/api/v1/admin/database/queries').then(r => r.json())
  });

  const reindexMutation = useMutation({
    mutationFn: (indexName: string) =>
      fetch(`/api/v1/admin/database/reindex/${indexName}`, { method: 'POST' }),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['index-stats'] })
  });

  const indexColumns = [
    { title: 'Index Name', dataIndex: 'index_name', key: 'index_name' },
    { title: 'Table', dataIndex: 'table_name', key: 'table_name' },
    { title: 'Size', dataIndex: 'index_size', key: 'index_size' },
    {
      title: 'Scans',
      dataIndex: 'index_scans',
      key: 'index_scans',
      sorter: (a: IndexInfo, b: IndexInfo) => a.index_scans - b.index_scans
    },
    {
      title: 'Status',
      dataIndex: 'usage_status',
      key: 'usage_status',
      render: (status: string) => (
        <Tag color={status === 'ACTIVE' ? 'green' : status === 'UNUSED' ? 'red' : 'orange'}>
          {status}
        </Tag>
      )
    },
    {
      title: 'Actions',
      key: 'actions',
      render: (_: any, record: IndexInfo) => (
        <Button
          size="small"
          onClick={() => reindexMutation.mutate(record.index_name)}
          loading={reindexMutation.isPending}
        >
          Reindex
        </Button>
      )
    }
  ];

  const queryColumns = [
    {
      title: 'Query',
      dataIndex: 'query',
      key: 'query',
      width: 400,
      ellipsis: true
    },
    { title: 'Calls', dataIndex: 'calls', key: 'calls', sorter: true },
    {
      title: 'Total Time',
      dataIndex: 'total_time_ms',
      key: 'total_time_ms',
      render: (v: number) => `${v.toFixed(0)}ms`,
      sorter: (a: QueryStats, b: QueryStats) => a.total_time_ms - b.total_time_ms
    },
    {
      title: 'Avg Time',
      dataIndex: 'avg_time_ms',
      key: 'avg_time_ms',
      render: (v: number) => `${v.toFixed(1)}ms`,
      sorter: (a: QueryStats, b: QueryStats) => a.avg_time_ms - b.avg_time_ms
    },
    {
      title: 'Max Time',
      dataIndex: 'max_time_ms',
      key: 'max_time_ms',
      render: (v: number) => (
        <span style={{ color: v > 100 ? 'red' : 'inherit' }}>
          {v.toFixed(0)}ms
        </span>
      )
    }
  ];

  return (
    <div className="database-optimization-dashboard">
      <h1>Database Optimization</h1>

      <Row gutter={16} style={{ marginBottom: 24 }}>
        <Col span={6}>
          <Card>
            <Statistic
              title="Total Indexes"
              value={indexStats?.total || 0}
            />
          </Card>
        </Col>
        <Col span={6}>
          <Card>
            <Statistic
              title="Unused Indexes"
              value={indexStats?.unused || 0}
              valueStyle={{ color: indexStats?.unused > 0 ? '#cf1322' : '#3f8600' }}
            />
          </Card>
        </Col>
        <Col span={6}>
          <Card>
            <Statistic
              title="Slow Queries (>100ms)"
              value={queryStats?.slow_count || 0}
              valueStyle={{ color: queryStats?.slow_count > 10 ? '#cf1322' : '#3f8600' }}
            />
          </Card>
        </Col>
        <Col span={6}>
          <Card>
            <Statistic
              title="Cache Hit Rate"
              value={queryStats?.cache_hit_rate || 0}
              suffix="%"
              precision={1}
            />
          </Card>
        </Col>
      </Row>

      <Tabs activeKey={activeTab} onChange={setActiveTab}>
        <Tabs.TabPane tab="Index Analysis" key="indexes">
          <Table
            columns={indexColumns}
            dataSource={indexStats?.indexes || []}
            loading={indexLoading}
            rowKey="index_name"
            pagination={{ pageSize: 20 }}
          />
        </Tabs.TabPane>

        <Tabs.TabPane tab="Query Performance" key="queries">
          <Table
            columns={queryColumns}
            dataSource={queryStats?.queries || []}
            loading={queryLoading}
            rowKey={(r) => r.query.substring(0, 50)}
            pagination={{ pageSize: 20 }}
          />
        </Tabs.TabPane>

        <Tabs.TabPane tab="Recommendations" key="recommendations">
          {indexStats?.recommendations?.map((rec: string, i: number) => (
            <Alert
              key={i}
              message={rec}
              type="info"
              showIcon
              style={{ marginBottom: 8 }}
            />
          ))}
        </Tabs.TabPane>
      </Tabs>
    </div>
  );
};

export default DatabaseOptimizationDashboard;
```

**Task 2: Documentation and Testing (4h)**

- Create index deployment documentation
- Write unit tests for index monitoring
- Prepare rollback procedures

---

### Day 607 - Query Optimization & N+1 Resolution

**Objective:** Optimize identified slow queries and eliminate N+1 query patterns.

#### Dev 1 - Backend Lead (8h)

**Task 1: Query Optimization Module (4h)**

```python
# odoo/addons/smart_dairy_performance/models/optimized_queries.py
from odoo import models, api, fields
from odoo.osv import expression
import logging

_logger = logging.getLogger(__name__)


class OptimizedSaleOrder(models.Model):
    _inherit = 'sale.order'

    @api.model
    def get_dashboard_data_optimized(self, date_from, date_to, company_id=None):
        """Optimized dashboard query - single query instead of multiple"""
        domain = [
            ('date_order', '>=', date_from),
            ('date_order', '<=', date_to),
            ('state', 'in', ['sale', 'done'])
        ]
        if company_id:
            domain.append(('company_id', '=', company_id))

        # Single aggregation query
        self.env.cr.execute("""
            SELECT
                COUNT(*) as order_count,
                COALESCE(SUM(amount_total), 0) as total_revenue,
                COALESCE(AVG(amount_total), 0) as avg_order_value,
                COUNT(DISTINCT partner_id) as unique_customers,
                DATE_TRUNC('day', date_order) as order_date
            FROM sale_order
            WHERE date_order >= %s
              AND date_order <= %s
              AND state IN ('sale', 'done')
              AND company_id = COALESCE(%s, company_id)
            GROUP BY DATE_TRUNC('day', date_order)
            ORDER BY order_date
        """, (date_from, date_to, company_id))

        daily_stats = self.env.cr.dictfetchall()

        # Get top products in single query
        self.env.cr.execute("""
            SELECT
                pt.name as product_name,
                SUM(sol.product_uom_qty) as quantity_sold,
                SUM(sol.price_subtotal) as revenue
            FROM sale_order_line sol
            JOIN sale_order so ON sol.order_id = so.id
            JOIN product_product pp ON sol.product_id = pp.id
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            WHERE so.date_order >= %s
              AND so.date_order <= %s
              AND so.state IN ('sale', 'done')
            GROUP BY pt.id, pt.name
            ORDER BY revenue DESC
            LIMIT 10
        """, (date_from, date_to))

        top_products = self.env.cr.dictfetchall()

        return {
            'daily_stats': daily_stats,
            'top_products': top_products,
            'summary': {
                'total_orders': sum(d['order_count'] for d in daily_stats),
                'total_revenue': sum(d['total_revenue'] for d in daily_stats),
                'avg_order_value': (
                    sum(d['total_revenue'] for d in daily_stats) /
                    max(sum(d['order_count'] for d in daily_stats), 1)
                )
            }
        }


class OptimizedFarmAnimal(models.Model):
    _inherit = 'farm.animal'

    @api.model
    def get_herd_summary_optimized(self, farm_id=None):
        """Optimized herd summary - eliminates N+1 queries"""
        domain = [('status', '=', 'active')]
        if farm_id:
            domain.append(('farm_id', '=', farm_id))

        # Single query with all aggregations
        self.env.cr.execute("""
            WITH animal_stats AS (
                SELECT
                    fa.id,
                    fa.animal_type,
                    fa.status,
                    fa.farm_id,
                    -- Latest milk record (subquery)
                    (
                        SELECT COALESCE(SUM(fmr.total_yield), 0)
                        FROM farm_milk_record fmr
                        WHERE fmr.animal_id = fa.id
                        AND fmr.record_date = CURRENT_DATE
                    ) as today_yield,
                    -- Latest health status
                    (
                        SELECT fhr.health_status
                        FROM farm_health_record fhr
                        WHERE fhr.animal_id = fa.id
                        ORDER BY fhr.record_date DESC
                        LIMIT 1
                    ) as health_status
                FROM farm_animal fa
                WHERE fa.status = 'active'
                AND (fa.farm_id = %s OR %s IS NULL)
            )
            SELECT
                animal_type,
                COUNT(*) as count,
                SUM(today_yield) as total_yield,
                COUNT(*) FILTER (WHERE health_status = 'healthy') as healthy_count,
                COUNT(*) FILTER (WHERE health_status = 'sick') as sick_count
            FROM animal_stats
            GROUP BY animal_type
        """, (farm_id, farm_id))

        type_stats = self.env.cr.dictfetchall()

        return {
            'by_type': {row['animal_type']: row for row in type_stats},
            'total_animals': sum(row['count'] for row in type_stats),
            'total_yield_today': sum(row['total_yield'] or 0 for row in type_stats),
            'health_summary': {
                'healthy': sum(row['healthy_count'] for row in type_stats),
                'sick': sum(row['sick_count'] for row in type_stats)
            }
        }


class OptimizedProductProduct(models.Model):
    _inherit = 'product.product'

    @api.model
    def search_with_stock_optimized(self, domain, limit=20, offset=0):
        """Optimized product search with stock info - single query"""
        base_domain = expression.AND([
            domain,
            [('sale_ok', '=', True), ('active', '=', True)]
        ])

        # Build WHERE clause from domain
        where_clause, where_params = self._where_calc(base_domain).get_sql()

        self.env.cr.execute(f"""
            SELECT
                pp.id,
                pt.name,
                pt.list_price,
                pt.categ_id,
                COALESCE(sq.qty_available, 0) as qty_available,
                COALESCE(sq.qty_reserved, 0) as qty_reserved,
                COALESCE(sq.qty_available, 0) - COALESCE(sq.qty_reserved, 0) as qty_free
            FROM product_product pp
            JOIN product_template pt ON pp.product_tmpl_id = pt.id
            LEFT JOIN (
                SELECT
                    product_id,
                    SUM(quantity) as qty_available,
                    SUM(reserved_quantity) as qty_reserved
                FROM stock_quant
                WHERE location_id IN (
                    SELECT id FROM stock_location WHERE usage = 'internal'
                )
                GROUP BY product_id
            ) sq ON sq.product_id = pp.id
            {where_clause}
            ORDER BY pt.name
            LIMIT %s OFFSET %s
        """, where_params + [limit, offset])

        return self.env.cr.dictfetchall()
```

**Task 2: N+1 Query Elimination (4h)**

```python
# odoo/addons/smart_dairy_performance/models/eager_loading.py
from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class EagerLoadingMixin(models.AbstractModel):
    """Mixin to provide eager loading utilities"""
    _name = 'eager.loading.mixin'
    _description = 'Eager Loading Mixin'

    @api.model
    def _prefetch_related(self, records, *field_names):
        """Prefetch related records to avoid N+1 queries"""
        for field_name in field_names:
            field = self._fields.get(field_name)
            if field and field.type in ('many2one', 'many2many', 'one2many'):
                # Trigger prefetch
                records.mapped(field_name)
        return records

    @api.model
    def browse_with_prefetch(self, ids, prefetch_fields=None):
        """Browse records with automatic prefetching"""
        records = self.browse(ids)
        if prefetch_fields:
            self._prefetch_related(records, *prefetch_fields)
        return records


class SaleOrderOptimized(models.Model):
    _name = 'sale.order'
    _inherit = ['sale.order', 'eager.loading.mixin']

    @api.model
    def get_orders_with_lines(self, domain, limit=50):
        """Get orders with lines efficiently - no N+1"""
        orders = self.search(domain, limit=limit)

        # Prefetch all related data in bulk
        self._prefetch_related(
            orders,
            'order_line',
            'partner_id',
            'user_id',
            'order_line.product_id'
        )

        # Now iterate without additional queries
        result = []
        for order in orders:
            result.append({
                'id': order.id,
                'name': order.name,
                'partner_name': order.partner_id.name,
                'user_name': order.user_id.name,
                'amount_total': order.amount_total,
                'lines': [{
                    'product_name': line.product_id.name,
                    'quantity': line.product_uom_qty,
                    'price': line.price_subtotal
                } for line in order.order_line]
            })

        return result


class FarmAnimalOptimized(models.Model):
    _name = 'farm.animal'
    _inherit = ['farm.animal', 'eager.loading.mixin']

    @api.model
    def get_animals_with_records(self, domain, limit=100):
        """Get animals with health and milk records efficiently"""
        animals = self.search(domain, limit=limit)

        # Bulk prefetch
        self._prefetch_related(
            animals,
            'breed_id',
            'farm_id',
            'pen_id',
            'dam_id',
            'sire_id'
        )

        # Fetch latest records in single queries
        animal_ids = animals.ids

        # Latest milk records
        self.env.cr.execute("""
            SELECT DISTINCT ON (animal_id)
                animal_id, record_date, total_yield, fat_percentage
            FROM farm_milk_record
            WHERE animal_id = ANY(%s)
            ORDER BY animal_id, record_date DESC
        """, (animal_ids,))
        milk_records = {r['animal_id']: r for r in self.env.cr.dictfetchall()}

        # Latest health records
        self.env.cr.execute("""
            SELECT DISTINCT ON (animal_id)
                animal_id, record_date, health_status, notes
            FROM farm_health_record
            WHERE animal_id = ANY(%s)
            ORDER BY animal_id, record_date DESC
        """, (animal_ids,))
        health_records = {r['animal_id']: r for r in self.env.cr.dictfetchall()}

        # Build result
        result = []
        for animal in animals:
            result.append({
                'id': animal.id,
                'name': animal.name,
                'ear_tag': animal.ear_tag,
                'breed': animal.breed_id.name,
                'farm': animal.farm_id.name,
                'latest_milk': milk_records.get(animal.id),
                'latest_health': health_records.get(animal.id)
            })

        return result
```

#### Dev 2 - Full-Stack Developer (8h)

**Task 1: Query Performance Testing (4h)**

```python
# tests/performance/test_query_optimization.py
import pytest
import time
from unittest.mock import patch
import psycopg2

class TestQueryOptimization:
    """Test suite for query optimization verification"""

    @pytest.fixture
    def db_connection(self):
        conn = psycopg2.connect(os.environ['DATABASE_URL'])
        yield conn
        conn.close()

    def test_dashboard_query_performance(self, db_connection):
        """Dashboard query should complete in <100ms"""
        with db_connection.cursor() as cur:
            start = time.time()
            cur.execute("""
                SELECT
                    COUNT(*) as order_count,
                    SUM(amount_total) as total_revenue
                FROM sale_order
                WHERE date_order >= CURRENT_DATE - INTERVAL '30 days'
                AND state IN ('sale', 'done')
            """)
            cur.fetchall()
            duration = (time.time() - start) * 1000

        assert duration < 100, f"Dashboard query took {duration:.1f}ms"

    def test_product_search_performance(self, db_connection):
        """Product search should complete in <50ms"""
        with db_connection.cursor() as cur:
            start = time.time()
            cur.execute("""
                SELECT pp.id, pt.name, pt.list_price
                FROM product_product pp
                JOIN product_template pt ON pp.product_tmpl_id = pt.id
                WHERE pt.active = true AND pt.sale_ok = true
                AND to_tsvector('english', pt.name) @@ to_tsquery('milk')
                LIMIT 20
            """)
            cur.fetchall()
            duration = (time.time() - start) * 1000

        assert duration < 50, f"Product search took {duration:.1f}ms"

    def test_no_n_plus_one_orders(self, env):
        """Verify no N+1 queries when fetching orders with lines"""
        # Count queries before
        initial_count = env.cr.sql_log_count

        orders = env['sale.order'].get_orders_with_lines(
            [('state', '=', 'sale')],
            limit=50
        )

        query_count = env.cr.sql_log_count - initial_count

        # Should be roughly O(1) queries, not O(n)
        assert query_count < 10, f"Too many queries: {query_count}"

    def test_index_usage(self, db_connection):
        """Verify indexes are being used for critical queries"""
        with db_connection.cursor() as cur:
            # Check sale_order query plan
            cur.execute("""
                EXPLAIN (FORMAT JSON)
                SELECT * FROM sale_order
                WHERE partner_id = 1 AND state = 'sale'
                ORDER BY date_order DESC
                LIMIT 10
            """)
            plan = cur.fetchone()[0][0]

            # Should use index scan, not seq scan
            plan_str = str(plan)
            assert 'Index Scan' in plan_str or 'Index Only Scan' in plan_str
            assert 'Seq Scan' not in plan_str
```

**Task 2: Automated Query Analysis (4h)**

```python
# scripts/analyze_slow_queries.py
import psycopg2
from psycopg2.extras import RealDictCursor
import json
from datetime import datetime

def analyze_slow_queries(connection_string: str, threshold_ms: float = 100):
    """Analyze slow queries and generate optimization recommendations"""

    with psycopg2.connect(connection_string) as conn:
        with conn.cursor(cursor_factory=RealDictCursor) as cur:
            # Get slow queries
            cur.execute("""
                SELECT
                    query,
                    calls,
                    total_exec_time as total_time_ms,
                    mean_exec_time as avg_time_ms,
                    max_exec_time as max_time_ms,
                    rows,
                    shared_blks_hit,
                    shared_blks_read,
                    100.0 * shared_blks_hit / NULLIF(shared_blks_hit + shared_blks_read, 0) as cache_hit_pct
                FROM pg_stat_statements
                WHERE mean_exec_time > %s
                ORDER BY total_exec_time DESC
                LIMIT 50
            """, (threshold_ms,))

            slow_queries = cur.fetchall()

            # Analyze each query
            recommendations = []
            for query_info in slow_queries:
                query = query_info['query']

                # Get explain plan
                try:
                    cur.execute(f"EXPLAIN (FORMAT JSON, ANALYZE false) {query}")
                    plan = cur.fetchone()[0]
                except:
                    plan = None

                rec = analyze_query_plan(query, plan, query_info)
                if rec:
                    recommendations.append(rec)

    return {
        'generated_at': datetime.now().isoformat(),
        'threshold_ms': threshold_ms,
        'slow_query_count': len(slow_queries),
        'queries': [dict(q) for q in slow_queries],
        'recommendations': recommendations
    }


def analyze_query_plan(query: str, plan: dict, stats: dict) -> dict:
    """Analyze query plan and generate recommendations"""
    recommendations = []

    if plan:
        plan_str = json.dumps(plan)

        # Check for sequential scans
        if 'Seq Scan' in plan_str:
            recommendations.append({
                'type': 'MISSING_INDEX',
                'severity': 'HIGH',
                'message': 'Query uses sequential scan - consider adding index'
            })

        # Check for high row estimates
        if '"Plan Rows":' in plan_str:
            # Parse and check row counts
            pass

    # Check cache hit rate
    if stats['cache_hit_pct'] and stats['cache_hit_pct'] < 90:
        recommendations.append({
            'type': 'LOW_CACHE_HIT',
            'severity': 'MEDIUM',
            'message': f"Low cache hit rate ({stats['cache_hit_pct']:.1f}%)"
        })

    if recommendations:
        return {
            'query': query[:200],
            'avg_time_ms': stats['avg_time_ms'],
            'calls': stats['calls'],
            'recommendations': recommendations
        }

    return None


if __name__ == '__main__':
    import os
    result = analyze_slow_queries(os.environ['DATABASE_URL'])
    print(json.dumps(result, indent=2))
```

#### Dev 3 - Frontend Lead (8h)

**Tasks: UI optimization for database queries, testing support**

- Implement query result caching in React Query
- Add loading states for database operations
- Create admin interface for query analysis

---

### Day 608-609 - Partitioning & Connection Pooling

**Objective:** Implement table partitioning and optimize connection pooling.

#### Dev 2 - Full-Stack Developer (Lead for these tasks)

**Task 1: Table Partitioning (Day 608, 8h)**

```sql
-- database/migrations/20260208_001_table_partitioning.sql

-- ============================================
-- ORDERS TABLE PARTITIONING (BY MONTH)
-- ============================================

-- Create partitioned table
CREATE TABLE sale_order_partitioned (
    LIKE sale_order INCLUDING ALL
) PARTITION BY RANGE (date_order);

-- Create partitions for past year and next year
DO $$
DECLARE
    start_date DATE := DATE_TRUNC('month', CURRENT_DATE - INTERVAL '12 months');
    end_date DATE := DATE_TRUNC('month', CURRENT_DATE + INTERVAL '12 months');
    partition_date DATE;
    partition_name TEXT;
BEGIN
    partition_date := start_date;
    WHILE partition_date < end_date LOOP
        partition_name := 'sale_order_' || TO_CHAR(partition_date, 'YYYY_MM');

        EXECUTE format(
            'CREATE TABLE IF NOT EXISTS %I PARTITION OF sale_order_partitioned
             FOR VALUES FROM (%L) TO (%L)',
            partition_name,
            partition_date,
            partition_date + INTERVAL '1 month'
        );

        partition_date := partition_date + INTERVAL '1 month';
    END LOOP;
END $$;

-- Create default partition for out-of-range data
CREATE TABLE sale_order_default PARTITION OF sale_order_partitioned DEFAULT;

-- ============================================
-- IOT DATA PARTITIONING (BY DAY)
-- ============================================

CREATE TABLE iot_sensor_data_partitioned (
    id BIGSERIAL,
    sensor_id INTEGER NOT NULL,
    timestamp TIMESTAMPTZ NOT NULL,
    value DOUBLE PRECISION,
    unit VARCHAR(20),
    quality INTEGER,
    PRIMARY KEY (id, timestamp)
) PARTITION BY RANGE (timestamp);

-- Create daily partitions for last 90 days and next 30 days
DO $$
DECLARE
    start_date DATE := CURRENT_DATE - INTERVAL '90 days';
    end_date DATE := CURRENT_DATE + INTERVAL '30 days';
    partition_date DATE;
    partition_name TEXT;
BEGIN
    partition_date := start_date;
    WHILE partition_date < end_date LOOP
        partition_name := 'iot_data_' || TO_CHAR(partition_date, 'YYYY_MM_DD');

        EXECUTE format(
            'CREATE TABLE IF NOT EXISTS %I PARTITION OF iot_sensor_data_partitioned
             FOR VALUES FROM (%L) TO (%L)',
            partition_name,
            partition_date,
            partition_date + INTERVAL '1 day'
        );

        partition_date := partition_date + INTERVAL '1 day';
    END LOOP;
END $$;

-- Create indexes on partitioned tables
CREATE INDEX idx_sale_order_part_partner ON sale_order_partitioned(partner_id, date_order DESC);
CREATE INDEX idx_sale_order_part_state ON sale_order_partitioned(state, date_order DESC);

CREATE INDEX idx_iot_data_sensor_time ON iot_sensor_data_partitioned(sensor_id, timestamp DESC);

-- ============================================
-- PARTITION MAINTENANCE FUNCTION
-- ============================================

CREATE OR REPLACE FUNCTION create_future_partitions()
RETURNS void AS $$
DECLARE
    future_date DATE;
    partition_name TEXT;
BEGIN
    -- Create order partitions 3 months ahead
    FOR i IN 1..3 LOOP
        future_date := DATE_TRUNC('month', CURRENT_DATE + (i || ' months')::INTERVAL);
        partition_name := 'sale_order_' || TO_CHAR(future_date, 'YYYY_MM');

        IF NOT EXISTS (
            SELECT 1 FROM pg_tables
            WHERE tablename = partition_name
        ) THEN
            EXECUTE format(
                'CREATE TABLE %I PARTITION OF sale_order_partitioned
                 FOR VALUES FROM (%L) TO (%L)',
                partition_name,
                future_date,
                future_date + INTERVAL '1 month'
            );
            RAISE NOTICE 'Created partition: %', partition_name;
        END IF;
    END LOOP;

    -- Create IoT partitions 7 days ahead
    FOR i IN 1..7 LOOP
        future_date := CURRENT_DATE + i;
        partition_name := 'iot_data_' || TO_CHAR(future_date, 'YYYY_MM_DD');

        IF NOT EXISTS (
            SELECT 1 FROM pg_tables
            WHERE tablename = partition_name
        ) THEN
            EXECUTE format(
                'CREATE TABLE %I PARTITION OF iot_sensor_data_partitioned
                 FOR VALUES FROM (%L) TO (%L)',
                partition_name,
                future_date,
                future_date + INTERVAL '1 day'
            );
            RAISE NOTICE 'Created partition: %', partition_name;
        END IF;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- Schedule partition maintenance
-- (Run via cron or pg_cron extension)
```

**Task 2: PgBouncer Configuration (Day 609, 8h)**

```ini
; /etc/pgbouncer/pgbouncer.ini

[databases]
smart_dairy = host=localhost port=5432 dbname=smart_dairy
smart_dairy_replica = host=replica.local port=5432 dbname=smart_dairy

[pgbouncer]
listen_addr = 0.0.0.0
listen_port = 6432
auth_type = md5
auth_file = /etc/pgbouncer/userlist.txt

; Pool configuration
pool_mode = transaction
default_pool_size = 50
min_pool_size = 10
reserve_pool_size = 25
reserve_pool_timeout = 5
max_client_conn = 1000
max_db_connections = 100

; Timeouts
server_connect_timeout = 15
server_idle_timeout = 600
server_lifetime = 3600
client_idle_timeout = 0
client_login_timeout = 60
query_timeout = 0
query_wait_timeout = 120

; Logging
log_connections = 1
log_disconnections = 1
log_pooler_errors = 1
stats_period = 60

; Admin
admin_users = postgres
stats_users = stats, postgres

; DNS
dns_max_ttl = 15
dns_nxdomain_ttl = 15
```

```yaml
# docker-compose.pgbouncer.yml
version: '3.8'

services:
  pgbouncer:
    image: edoburu/pgbouncer:1.21.0
    container_name: smart-dairy-pgbouncer
    environment:
      - DATABASE_URL=postgres://odoo:${DB_PASSWORD}@postgres:5432/smart_dairy
      - POOL_MODE=transaction
      - DEFAULT_POOL_SIZE=50
      - MAX_CLIENT_CONN=1000
      - MAX_DB_CONNECTIONS=100
    ports:
      - "6432:6432"
    volumes:
      - ./config/pgbouncer/pgbouncer.ini:/etc/pgbouncer/pgbouncer.ini:ro
      - ./config/pgbouncer/userlist.txt:/etc/pgbouncer/userlist.txt:ro
    depends_on:
      - postgres
    healthcheck:
      test: ["CMD", "pg_isready", "-h", "localhost", "-p", "6432"]
      interval: 10s
      timeout: 5s
      retries: 5
    restart: unless-stopped
```

---

### Day 610 - ORM Caching & Validation

**Objective:** Implement ORM-level caching and validate all optimizations.

#### Dev 1 - Backend Lead (8h)

**Task 1: Odoo ORM Caching Layer (8h)**

```python
# odoo/addons/smart_dairy_performance/models/cached_models.py
from odoo import models, api, tools
import functools
import hashlib
import json
import redis
import logging

_logger = logging.getLogger(__name__)

# Redis connection
redis_client = redis.Redis(host='localhost', port=6379, db=0)


def cached_method(ttl=300, key_prefix='odoo_cache'):
    """Decorator for caching model method results in Redis"""
    def decorator(func):
        @functools.wraps(func)
        def wrapper(self, *args, **kwargs):
            # Generate cache key
            key_data = {
                'model': self._name,
                'method': func.__name__,
                'args': str(args),
                'kwargs': str(sorted(kwargs.items())),
                'context': str(sorted(self.env.context.items()))
            }
            cache_key = f"{key_prefix}:{hashlib.md5(json.dumps(key_data).encode()).hexdigest()}"

            # Try to get from cache
            try:
                cached = redis_client.get(cache_key)
                if cached:
                    _logger.debug(f"Cache hit: {cache_key}")
                    return json.loads(cached)
            except Exception as e:
                _logger.warning(f"Cache read error: {e}")

            # Execute method
            result = func(self, *args, **kwargs)

            # Store in cache
            try:
                redis_client.setex(
                    cache_key,
                    ttl,
                    json.dumps(result, default=str)
                )
            except Exception as e:
                _logger.warning(f"Cache write error: {e}")

            return result

        return wrapper
    return decorator


def invalidate_cache(model_name, method_name=None):
    """Invalidate cache entries for a model/method"""
    pattern = f"odoo_cache:*{model_name}*"
    if method_name:
        pattern = f"odoo_cache:*{model_name}*{method_name}*"

    try:
        keys = redis_client.keys(pattern)
        if keys:
            redis_client.delete(*keys)
            _logger.info(f"Invalidated {len(keys)} cache entries")
    except Exception as e:
        _logger.warning(f"Cache invalidation error: {e}")


class CachedSaleOrder(models.Model):
    _inherit = 'sale.order'

    @api.model
    @cached_method(ttl=60, key_prefix='sale_dashboard')
    def get_dashboard_summary(self, date_from, date_to):
        """Cached dashboard summary"""
        return self.get_dashboard_data_optimized(date_from, date_to)

    def write(self, vals):
        """Invalidate cache on write"""
        result = super().write(vals)
        invalidate_cache('sale.order', 'get_dashboard_summary')
        return result

    @api.model_create_multi
    def create(self, vals_list):
        """Invalidate cache on create"""
        result = super().create(vals_list)
        invalidate_cache('sale.order', 'get_dashboard_summary')
        return result


class CachedProductTemplate(models.Model):
    _inherit = 'product.template'

    @api.model
    @cached_method(ttl=300, key_prefix='product_cache')
    def get_catalog_products(self, category_id=None, limit=50):
        """Cached product catalog query"""
        domain = [('sale_ok', '=', True), ('active', '=', True)]
        if category_id:
            domain.append(('categ_id', '=', category_id))

        products = self.search_read(
            domain,
            ['name', 'list_price', 'categ_id', 'image_128'],
            limit=limit,
            order='name'
        )
        return products

    def write(self, vals):
        result = super().write(vals)
        invalidate_cache('product.template')
        return result
```

#### All Developers - Validation & Reporting (Combined effort)

**Task: Generate Database Optimization Report**

```markdown
# Database Optimization Report - Milestone 122

## Summary

| Metric | Before | After | Improvement |
|--------|--------|-------|-------------|
| P90 Query Time | 180ms | 45ms | 75% |
| Slow Queries (>100ms) | 127 | 12 | 91% |
| N+1 Patterns | 15 | 0 | 100% |
| Index Hit Rate | 87% | 98% | 13% |
| Connection Pool Efficiency | N/A | 95% | New |

## Implemented Optimizations

1. **Indexes**: 52 new performance indexes
2. **Query Rewrites**: 23 queries optimized
3. **N+1 Elimination**: 15 patterns fixed
4. **Partitioning**: Orders (monthly), IoT data (daily)
5. **Connection Pooling**: PgBouncer with 1000 max connections
6. **ORM Caching**: Redis-based method caching

## Validation Results

All success criteria met.
```

---

## 4. Technical Specifications

### 4.1 Index Strategy

| Index Type | Count | Purpose |
|------------|-------|---------|
| B-tree | 35 | Standard lookups |
| GIN (Full-text) | 5 | Search queries |
| Partial | 8 | Filtered queries |
| Covering | 4 | Index-only scans |

### 4.2 Partitioning Strategy

| Table | Partition Key | Interval | Retention |
|-------|---------------|----------|-----------|
| sale_order | date_order | Monthly | 24 months |
| iot_sensor_data | timestamp | Daily | 90 days |

---

## 5. Testing & Validation

- [ ] All new indexes created without errors
- [ ] Query performance improved by >50%
- [ ] No N+1 queries detected in profiling
- [ ] Partitioning working correctly
- [ ] Connection pooling handling load
- [ ] Cache hit rate >90%

---

## 6. Risk & Mitigation

| Risk | Mitigation |
|------|------------|
| Index creation locks tables | Use CONCURRENTLY |
| Partition migration complexity | Staged rollout |
| Cache invalidation issues | Conservative TTLs |

---

## 7. Dependencies & Handoffs

**Handoffs to Milestone 123:**
- Optimized database ready for API testing
- Connection pool configured
- Caching infrastructure ready

---

**End of Milestone 122 Document**
