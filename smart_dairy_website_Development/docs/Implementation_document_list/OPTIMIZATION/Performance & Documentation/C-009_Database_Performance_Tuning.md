# SMART DAIRY LTD.
## DATABASE PERFORMANCE TUNING GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | C-009 |
| **Version** | 1.0 |
| **Date** | October 15, 2026 |
| **Author** | Database Administrator |
| **Owner** | Database Administrator |
| **Reviewer** | Tech Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Performance Baselines](#2-performance-baselines)
3. [PostgreSQL Configuration Tuning](#3-postgresql-configuration-tuning)
4. [Query Optimization](#4-query-optimization)
5. [Indexing Strategies](#5-indexing-strategies)
6. [Partitioning & Archiving](#6-partitioning--archiving)
7. [Connection Pooling](#7-connection-pooling)
8. [Monitoring & Alerting](#8-monitoring--alerting)
9. [Maintenance Procedures](#9-maintenance-procedures)
10. [Troubleshooting](#10-troubleshooting)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides comprehensive guidelines for tuning and optimizing the PostgreSQL and TimescaleDB databases supporting the Smart Dairy ERP system. It covers configuration parameters, query optimization, indexing strategies, and maintenance procedures to ensure optimal database performance.

### 1.2 Performance Targets

| Metric | Target | Critical Threshold |
|--------|--------|-------------------|
| **Average Query Time** | < 100ms | > 500ms |
| **95th Percentile Query Time** | < 500ms | > 2s |
| **Database CPU Usage** | < 70% | > 85% |
| **Active Connections** | < 80% of max | > 90% |
| **Cache Hit Ratio** | > 99% | < 95% |
| **Replication Lag** | < 1s | > 5s |
| **Vacuum Lag** | < 1 hour | > 4 hours |

### 1.3 Database Inventory

| Database | Version | Role | Size Estimate |
|----------|---------|------|---------------|
| **smart_dairy_prod** | PostgreSQL 16 | Primary ERP | 500GB (Year 1) |
| **smart_dairy_analytics** | TimescaleDB 2.12 | IoT/Analytics | 2TB (Year 1) |
| **smart_dairy_staging** | PostgreSQL 16 | Staging | 100GB |
| **smart_dairy_dev** | PostgreSQL 16 | Development | 50GB |

---

## 2. PERFORMANCE BASELINES

### 2.1 Current Performance Metrics

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    DATABASE PERFORMANCE BASELINE                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  WORKLOAD CHARACTERISTICS                                                    │
│  ├── Peak Concurrent Users: 500 (B2C), 50 (B2B), 100 (Internal)             │
│  ├── Peak Transactions/Second: 1,200                                        │
│  ├── Average Query Complexity: 5-10 table joins                             │
│  ├── Write-Read Ratio: 30:70                                                │
│  └── IoT Data Ingestion: 500,000 records/day                                │
│                                                                              │
│  CURRENT BOTTLENECKS                                                         │
│  ├── Milk production aggregation queries (slow during reporting)            │
│  ├── B2C product search with filters                                        │
│  ├── Order history retrieval for large B2B customers                        │
│  └── IoT data insertion during peak hours                                   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Performance Testing Results

| Test Scenario | Current | Target | Status |
|--------------|---------|--------|--------|
| Simple SELECT by PK | 2ms | < 5ms | ✅ Pass |
| Complex JOIN (5 tables) | 450ms | < 200ms | ❌ Fail |
| Aggregate report (monthly) | 12s | < 3s | ❌ Fail |
| Full-text search | 800ms | < 300ms | ❌ Fail |
| Bulk INSERT (10K rows) | 45s | < 20s | ❌ Fail |

---

## 3. POSTGRESQL CONFIGURATION TUNING

### 3.1 Memory Configuration

```sql
-- postgresql.conf - Memory Settings
-- Based on AWS RDS db.r6g.2xlarge (64GB RAM)

-- Shared memory for caching
shared_buffers = 16GB                    -- 25% of total RAM
effective_cache_size = 48GB              -- 75% of total RAM
work_mem = 256MB                         -- For complex sorts/hashes
maintenance_work_mem = 2GB               -- For vacuum, index creation

-- Connection settings
max_connections = 500
shared_preload_libraries = 'pg_stat_statements,auto_explain'

-- Checkpoint and WAL
max_wal_size = 8GB
min_wal_size = 2GB
checkpoint_completion_target = 0.9
wal_buffers = 64MB

-- Query planning
random_page_cost = 1.1                   -- For SSD storage
effective_io_concurrency = 200           -- For SSD storage
default_statistics_target = 500
```

### 3.2 Autovacuum Tuning

```sql
-- Autovacuum Configuration
-- Critical for Odoo's high UPDATE/DELETE workload

autovacuum = on
autovacuum_max_workers = 6
autovacuum_naptime = 30s                 -- Check every 30 seconds

-- Aggressive settings for high-churn tables
autovacuum_vacuum_scale_factor = 0.05    -- Vacuum at 5% dead tuples
autovacuum_analyze_scale_factor = 0.025  -- Analyze at 2.5% changes

-- Worker-specific settings
autovacuum_vacuum_cost_limit = 2000      -- Higher cost limit for faster vacuum
autovacuum_vacuum_cost_delay = 2ms

-- Per-table settings (applied via ALTER TABLE)
-- For farm.milk_production (high insert rate)
ALTER TABLE farm.milk_production SET (
    autovacuum_vacuum_scale_factor = 0.01,
    autovacuum_analyze_scale_factor = 0.005,
    autovacuum_vacuum_insert_scale_factor = 0.01
);

-- For sales.order (high update rate)
ALTER TABLE sales.order SET (
    autovacuum_vacuum_scale_factor = 0.02,
    fillfactor = 80                         -- Leave room for HOT updates
);
```

### 3.3 Write-Ahead Logging (WAL) Optimization

```sql
-- WAL Configuration for Write-Heavy Workload
wal_level = replica
wal_compression = on                     -- Reduce WAL volume
max_wal_senders = 10                     -- For replication
max_replication_slots = 10

-- Synchronous replication for critical transactions
synchronous_commit = on
synchronous_standby_names = 'FIRST 1 (replica1, replica2)'

-- WAL archiving for PITR
archive_mode = on
archive_command = 'wal-archive.sh %p'
archive_timeout = 300
```

### 3.4 Parallel Query Configuration

```sql
-- Parallel Query Settings
max_parallel_workers = 16
max_parallel_workers_per_gather = 8
max_worker_processes = 16
max_parallel_maintenance_workers = 4

-- Cost thresholds for parallel execution
parallel_tuple_cost = 0.05
parallel_setup_cost = 500
min_parallel_table_scan_size = 8MB
min_parallel_index_scan_size = 512kB
```

---

## 4. QUERY OPTIMIZATION

### 4.1 Slow Query Analysis

```sql
-- Identify top slow queries using pg_stat_statements
SELECT 
    queryid,
    substring(query, 1, 100) AS query_preview,
    calls,
    round(total_exec_time::numeric, 2) AS total_time_ms,
    round(mean_exec_time::numeric, 2) AS avg_time_ms,
    round(max_exec_time::numeric, 2) AS max_time_ms,
    rows,
    round((100 * total_exec_time / sum(total_exec_time) OVER ())::numeric, 2) AS pct_time
FROM pg_stat_statements
WHERE query NOT LIKE '%pg_stat%'
ORDER BY total_exec_time DESC
LIMIT 20;
```

### 4.2 Query Optimization Examples

#### Example 1: Milk Production Report (Before)

```sql
-- SLOW: 12 seconds
SELECT 
    a.name AS animal_name,
    a.breed_id,
    b.name AS breed_name,
    EXTRACT(MONTH FROM mp.production_date) AS month,
    SUM(mp.quantity_liters) AS total_milk,
    AVG(mp.fat_percentage) AS avg_fat,
    AVG(mp.snf_percentage) AS avg_snf
FROM farm.animal a
JOIN farm.milk_production mp ON mp.animal_id = a.id
LEFT JOIN farm.breed b ON b.id = a.breed_id
WHERE mp.production_date >= '2026-01-01'
    AND mp.production_date < '2026-02-01'
GROUP BY a.name, a.breed_id, b.name, EXTRACT(MONTH FROM mp.production_date)
ORDER BY total_milk DESC;
```

#### Example 1: Optimized (After)

```sql
-- FAST: 800ms
-- 1. Create materialized view for monthly aggregates
CREATE MATERIALIZED VIEW farm.mv_monthly_milk_production AS
SELECT 
    animal_id,
    DATE_TRUNC('month', production_date) AS month,
    SUM(quantity_liters) AS total_milk,
    AVG(fat_percentage) AS avg_fat,
    AVG(snf_percentage) AS avg_snf
FROM farm.milk_production
GROUP BY animal_id, DATE_TRUNC('month', production_date);

CREATE INDEX idx_mv_monthly_animal ON farm.mv_monthly_milk_production(animal_id, month);

-- 2. Optimized query using materialized view
SELECT 
    a.name AS animal_name,
    b.name AS breed_name,
    mmp.month,
    mmp.total_milk,
    mmp.avg_fat,
    mmp.avg_snf
FROM farm.animal a
JOIN farm.mv_monthly_milk_production mmp ON mmp.animal_id = a.id
LEFT JOIN farm.breed b ON b.id = a.breed_id
WHERE mmp.month = '2026-01-01'
ORDER BY mmp.total_milk DESC;

-- 3. Refresh materialized view nightly
REFRESH MATERIALIZED VIEW CONCURRENTLY farm.mv_monthly_milk_production;
```

#### Example 2: B2C Product Search (Before)

```sql
-- SLOW: 800ms with full-text search
SELECT 
    p.id, p.name, p.default_code, p.list_price,
    p.image_1920, c.name AS category_name
FROM product_product p
JOIN product_template pt ON pt.id = p.product_tmpl_id
LEFT JOIN product_category c ON c.id = pt.categ_id
WHERE pt.active = true
    AND pt.sale_ok = true
    AND (
        p.name ILIKE '%milk%' 
        OR p.default_code ILIKE '%milk%'
        OR pt.description ILIKE '%milk%'
    )
ORDER BY p.name
LIMIT 50 OFFSET 0;
```

#### Example 2: Optimized (After)

```sql
-- FAST: 50ms
-- 1. Add trigram index for fuzzy search
CREATE EXTENSION IF NOT EXISTS pg_trgm;

CREATE INDEX idx_product_name_trgm ON product_product 
    USING gin(name gin_trgm_ops);

-- 2. Create search vector with weighted fields
ALTER TABLE product_template ADD COLUMN search_vector tsvector;

CREATE INDEX idx_product_search_vector ON product_template 
    USING gin(search_vector);

-- 3. Update search vector
UPDATE product_template SET search_vector = 
    setweight(to_tsvector('english', coalesce(name, '')), 'A') ||
    setweight(to_tsvector('english', coalesce(description, '')), 'B') ||
    setweight(to_tsvector('english', coalesce(default_code, '')), 'C');

-- 4. Optimized query
SELECT 
    p.id, p.name, p.default_code, p.list_price,
    p.image_1920, c.name AS category_name,
    ts_rank(pt.search_vector, query) AS rank
FROM product_product p
JOIN product_template pt ON pt.id = p.product_tmpl_id
LEFT JOIN product_category c ON c.id = pt.categ_id,
    plainto_tsquery('english', 'milk') query
WHERE pt.active = true
    AND pt.sale_ok = true
    AND pt.search_vector @@ query
ORDER BY rank DESC, p.name
LIMIT 50 OFFSET 0;

-- 5. Create trigger to maintain search vector
CREATE OR REPLACE FUNCTION update_product_search_vector()
RETURNS TRIGGER AS $$
BEGIN
    NEW.search_vector := 
        setweight(to_tsvector('english', coalesce(NEW.name, '')), 'A') ||
        setweight(to_tsvector('english', coalesce(NEW.description, '')), 'B') ||
        setweight(to_tsvector('english', coalesce(NEW.default_code, '')), 'C');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_product_search_vector
    BEFORE INSERT OR UPDATE ON product_template
    FOR EACH ROW EXECUTE FUNCTION update_product_search_vector();
```

### 4.3 Query Plan Analysis

```sql
-- Use EXPLAIN ANALYZE to understand query execution
EXPLAIN (ANALYZE, BUFFERS, FORMAT JSON)
SELECT * FROM farm.animal WHERE rfid_tag = 'E200341502001080';

-- Key metrics to check:
-- - Seq Scan vs Index Scan
-- - Rows estimated vs actual
-- - Buffer hits vs reads
-- - Execution time breakdown
```

---

## 5. INDEXING STRATEGIES

### 5.1 Index Categories

| Index Type | Use Case | Example |
|------------|----------|---------|
| **B-Tree** | Equality, range queries | PRIMARY KEY, date ranges |
| **GIN** | Full-text search, arrays | Product search, tags |
| **GiST** | Geospatial, range types | Location tracking |
| **BRIN** | Large tables, time-series | IoT sensor data |
| **Partial** | Filtered queries | Active animals only |
| **Expression** | Computed columns | Lowercase email |
| **Covering** | Index-only scans | Multi-column queries |

### 5.2 Critical Indexes

```sql
-- Farm Management Indexes
-- Animal lookups by RFID
CREATE UNIQUE INDEX CONCURRENTLY idx_animal_rfid 
    ON farm.animal(rfid_tag) WHERE rfid_tag IS NOT NULL;

-- Partial index for active animals
CREATE INDEX CONCURRENTLY idx_animal_active_status 
    ON farm.animal(status, barn_id) 
    WHERE status NOT IN ('sold', 'deceased');

-- Covering index for milk production reports
CREATE INDEX CONCURRENTLY idx_milk_production_covering 
    ON farm.milk_production(animal_id, production_date) 
    INCLUDE (quantity_liters, fat_percentage, snf_percentage);

-- Sales & Orders
-- Covering index for order queries
CREATE INDEX CONCURRENTLY idx_order_covering 
    ON sales.order(partner_id, order_date, state) 
    INCLUDE (amount_total, currency_id);

-- Partial index for unpaid orders
CREATE INDEX CONCURRENTLY idx_order_unpaid 
    ON sales.order(partner_id, amount_total) 
    WHERE state IN ('draft', 'sent') AND invoice_status != 'invoiced';

-- B2C Search
-- Trigram index for fuzzy search
CREATE INDEX CONCURRENTLY idx_product_name_trgm 
    ON product_product USING gin(name gin_trgm_ops);

-- Full-text search index
CREATE INDEX CONCURRENTLY idx_product_template_fts 
    ON product_template USING gin(search_vector);
```

### 5.3 TimescaleDB Hypertable Indexes

```sql
-- IoT Sensor Data Indexes
-- BRIN for time-series data (space-efficient)
CREATE INDEX CONCURRENTLY idx_sensor_data_brin 
    ON iot.sensor_data USING brin(time) 
    WITH (pages_per_range = 128);

-- Composite index for device queries
CREATE INDEX CONCURRENTLY idx_sensor_data_device_time 
    ON iot.sensor_data(device_id, time DESC);

-- Partial index for recent alerts
CREATE INDEX CONCURRENTLY idx_sensor_data_alerts 
    ON iot.sensor_data(device_id, time) 
    WHERE value > threshold_value;
```

### 5.4 Index Maintenance

```sql
-- Monitor index usage
SELECT 
    schemaname,
    tablename,
    indexname,
    idx_scan,
    idx_tup_read,
    idx_tup_fetch,
    pg_size_pretty(pg_relation_size(indexrelid)) AS index_size
FROM pg_stat_user_indexes
ORDER BY idx_scan DESC;

-- Find unused indexes (candidates for removal)
SELECT 
    schemaname,
    tablename,
    indexname,
    pg_size_pretty(pg_relation_size(indexrelid)) AS index_size
FROM pg_stat_user_indexes
WHERE idx_scan = 0 
    AND indexrelname NOT LIKE '%pkey%'
    AND indexrelname NOT LIKE '%unique%'
ORDER BY pg_relation_size(indexrelid) DESC;

-- Rebuild bloated indexes
REINDEX INDEX CONCURRENTLY idx_milk_production_covering;

-- Analyze tables after index changes
ANALYZE farm.milk_production;
```

---

## 6. PARTITIONING & ARCHIVING

### 6.1 Native PostgreSQL Partitioning

```sql
-- Partition milk_production by month
CREATE TABLE farm.milk_production (
    id BIGSERIAL,
    animal_id BIGINT NOT NULL,
    production_date DATE NOT NULL,
    session VARCHAR(20),
    quantity_liters DECIMAL(8,2),
    PRIMARY KEY (id, production_date)
) PARTITION BY RANGE (production_date);

-- Create monthly partitions
CREATE TABLE farm.milk_production_2026_01 
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2026-01-01') TO ('2026-02-01');

CREATE TABLE farm.milk_production_2026_02 
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2026-02-01') TO ('2026-03-01');

-- Create partitions for future months
CREATE OR REPLACE FUNCTION create_milk_partitions()
RETURNS void AS $$
DECLARE
    start_date DATE;
    end_date DATE;
    partition_name TEXT;
BEGIN
    FOR i IN 1..12 LOOP
        start_date := DATE_TRUNC('month', CURRENT_DATE + (i || ' months')::INTERVAL);
        end_date := start_date + INTERVAL '1 month';
        partition_name := 'farm.milk_production_' || TO_CHAR(start_date, 'YYYY_MM');
        
        EXECUTE format(
            'CREATE TABLE IF NOT EXISTS %I PARTITION OF farm.milk_production
             FOR VALUES FROM (%L) TO (%L)',
            partition_name, start_date, end_date
        );
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- Archive old partitions
CREATE OR REPLACE FUNCTION archive_old_partitions()
RETURNS void AS $$
DECLARE
    partition RECORD;
BEGIN
    FOR partition IN 
        SELECT inhrelid::regclass::text AS partition_name
        FROM pg_inherits
        WHERE inhparent = 'farm.milk_production'::regclass
          AND inhrelid::regclass::text < 'farm.milk_production_' || TO_CHAR(CURRENT_DATE - INTERVAL '2 years', 'YYYY_MM')
    LOOP
        -- Move to archive schema
        EXECUTE format('ALTER TABLE %s SET SCHEMA archive', partition.partition_name);
    END LOOP;
END;
$$ LANGUAGE plpgsql;
```

### 6.2 TimescaleDB Hypertables

```sql
-- Convert sensor_data to hypertable
SELECT create_hypertable('iot.sensor_data', 'time', 
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Enable compression for older chunks
ALTER TABLE iot.sensor_data SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'device_id,sensor_type',
    timescaledb.compress_orderby = 'time DESC'
);

-- Compression policy: Compress chunks older than 7 days
SELECT add_compression_policy('iot.sensor_data', INTERVAL '7 days');

-- Retention policy: Drop chunks older than 2 years
SELECT add_retention_policy('iot.sensor_data', INTERVAL '2 years');

-- Continuous aggregates for common queries
CREATE MATERIALIZED VIEW iot.sensor_hourly_summary
WITH (timescaledb.continuous) AS
SELECT 
    time_bucket('1 hour', time) AS bucket,
    device_id,
    sensor_type,
    AVG(value) as avg_value,
    MIN(value) as min_value,
    MAX(value) as max_value,
    COUNT(*) as sample_count
FROM iot.sensor_data
GROUP BY bucket, device_id, sensor_type;

-- Refresh policy
SELECT add_continuous_aggregate_policy('iot.sensor_hourly_summary',
    start_offset => INTERVAL '1 month',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '1 hour'
);
```

---

## 7. CONNECTION POOLING

### 7.1 PgBouncer Configuration

```ini
; /etc/pgbouncer/pgbouncer.ini
[databases]
smart_dairy_prod = host=smart-dairy-db.cluster-xxx.ap-south-1.rds.amazonaws.com port=5432 dbname=smart_dairy_prod

[pgbouncer]
listen_port = 6432
listen_addr = 0.0.0.0
auth_type = md5
auth_file = /etc/pgbouncer/userlist.txt

; Pool settings
pool_mode = transaction           ; Transaction-level pooling for Odoo
max_client_conn = 2000
default_pool_size = 100
min_pool_size = 20
reserve_pool_size = 25
reserve_pool_timeout = 3

; Connection limits
max_db_connections = 400
max_user_connections = 400

; Timeouts
server_idle_timeout = 600
server_lifetime = 3600
server_connect_timeout = 15
query_timeout = 0
query_wait_timeout = 120
client_idle_timeout = 0
client_login_timeout = 60

; Logging
log_connections = 1
log_disconnections = 1
log_pooler_errors = 1
stats_period = 60

; Admin console
admin_users = pgbouncer_admin
stats_users = pgbouncer_stats
```

### 7.2 Application Connection Settings

```python
# Odoo configuration for connection pooling
# /etc/odoo/odoo.conf

[options]
db_host = pgbouncer.smartdairy.local
db_port = 6432
db_name = smart_dairy_prod
db_user = odoo_app
db_password = xxx
db_sslmode = require

; Odoo-specific settings
workers = 8
max_cron_threads = 2
limit_memory_soft = 2147483648
limit_memory_hard = 2684354560
limit_request = 8192
limit_time_cpu = 60
limit_time_real = 120
limit_time_real_cron = 300
```

---

## 8. MONITORING & ALERTING

### 8.1 Key Metrics Dashboard

| Metric | Query | Alert Threshold |
|--------|-------|-----------------|
| **Cache Hit Ratio** | `SELECT sum(heap_blks_hit) / (sum(heap_blks_hit) + sum(heap_blks_read)) FROM pg_statio_user_tables;` | < 99% |
| **Connection Usage** | `SELECT count(*) / (SELECT setting::int FROM pg_settings WHERE name='max_connections') * 100 FROM pg_stat_activity;` | > 80% |
| **Replication Lag** | `SELECT extract(epoch from (now() - pg_last_xact_replay_timestamp())) FROM pg_stat_wal_receiver;` | > 5s |
| **Dead Tuples Ratio** | `SELECT n_dead_tup::float / NULLIF(n_live_tup + n_dead_tup, 0) * 100 FROM pg_stat_user_tables;` | > 10% |
| **Long Queries** | `SELECT count(*) FROM pg_stat_activity WHERE state = 'active' AND now() - query_start > interval '5 minutes';` | > 5 |
| **Blocked Queries** | `SELECT count(*) FROM pg_locks WHERE NOT granted;` | > 0 |

### 8.2 Prometheus Exporter

```yaml
# PostgreSQL exporter configuration
pg_exporter:
  datasource: "postgresql://monitoring_user:password@localhost:5432/smart_dairy_prod?sslmode=require"
  
  queries:
    - name: "odoo_active_sessions"
      help: "Number of active Odoo sessions"
      sql: |
        SELECT COUNT(*) as count 
        FROM pg_stat_activity 
        WHERE application_name LIKE 'odoo%' 
          AND state = 'active'
      
    - name: "milk_production_today"
      help: "Total milk production today"
      sql: |
        SELECT COALESCE(SUM(quantity_liters), 0) as liters 
        FROM farm.milk_production 
        WHERE production_date = CURRENT_DATE
```

### 8.3 Alert Rules

```yaml
# Prometheus alert rules
groups:
  - name: postgresql-alerts
    rules:
      - alert: PostgreSQLHighConnectionCount
        expr: pg_stat_activity_count / pg_settings_max_connections > 0.8
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "PostgreSQL connection count is high"
          
      - alert: PostgreSQLCacheHitRatioLow
        expr: pg_stat_database_blks_hit / (pg_stat_database_blks_hit + pg_stat_database_blks_read) < 0.99
        for: 10m
        labels:
          severity: warning
        annotations:
          summary: "PostgreSQL cache hit ratio is low"
          
      - alert: PostgreSQLReplicationLag
        expr: pg_stat_replication_pg_wal_lsn_diff / 1000000 > 5
        for: 2m
        labels:
          severity: critical
        annotations:
          summary: "PostgreSQL replication lag is high"
```

---

## 9. MAINTENANCE PROCEDURES

### 9.1 Daily Maintenance

```sql
-- Update table statistics
ANALYZE;

-- Check for long-running queries
SELECT pid, now() - query_start AS duration, query 
FROM pg_stat_activity 
WHERE state = 'active' 
  AND now() - query_start > interval '1 hour';

-- Check vacuum status
SELECT schemaname, tablename, last_vacuum, last_autovacuum, n_dead_tup
FROM pg_stat_user_tables
WHERE n_dead_tup > 10000
ORDER BY n_dead_tup DESC;
```

### 9.2 Weekly Maintenance

```sql
-- Reindex bloated indexes
REINDEX INDEX CONCURRENTLY idx_milk_production_covering;
REINDEX INDEX CONCURRENTLY idx_order_covering;

-- Vacuum analyze high-churn tables
VACUUM ANALYZE farm.milk_production;
VACUUM ANALYZE sales.order;
VACUUM ANALYZE sales.order_line;

-- Check table bloat
SELECT schemaname, tablename, 
    pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) AS size,
    n_live_tup, n_dead_tup,
    round(n_dead_tup::numeric/nullif(n_live_tup + n_dead_tup, 0)*100, 2) AS dead_pct
FROM pg_stat_user_tables
WHERE n_dead_tup > n_live_tup * 0.1
ORDER BY n_dead_tup DESC;
```

### 9.3 Monthly Maintenance

```sql
-- Full vacuum (requires exclusive lock - schedule during maintenance window)
VACUUM FULL VERBOSE ANALYZE;

-- Refresh all materialized views
REFRESH MATERIALIZED VIEW CONCURRENTLY farm.mv_monthly_milk_production;

-- Archive old data
SELECT archive_old_partitions();

-- Review and optimize slow queries
-- (Use pg_stat_statements to identify candidates)
```

---

## 10. TROUBLESHOOTING

### 10.1 Common Issues

| Issue | Symptoms | Solution |
|-------|----------|----------|
| **Slow Queries** | High CPU, long execution times | Check query plans, add indexes |
| **Connection Exhaustion** | "too many connections" errors | Tune connection pooling, increase max_connections |
| **Replication Lag** | Stale data on replicas | Check network, increase wal_buffers |
| **Vacuum Blocking** | Table bloat, slow queries | Schedule manual vacuum during low-traffic |
| **Lock Contention** | Waiting queries, timeouts | Review transaction duration, reduce lock scope |

### 10.2 Emergency Procedures

```sql
-- Kill blocking queries
SELECT pg_terminate_backend(pid) 
FROM pg_stat_activity 
WHERE state = 'idle in transaction' 
  AND now() - query_start > interval '1 hour';

-- Cancel long-running query
SELECT pg_cancel_backend(pid) 
FROM pg_stat_activity 
WHERE pid = 12345;

-- Check locks
SELECT 
    blocked_locks.pid AS blocked_pid,
    blocked_activity.usename AS blocked_user,
    blocking_locks.pid AS blocking_pid,
    blocking_activity.usename AS blocking_user,
    blocked_activity.query AS blocked_statement,
    blocking_activity.query AS blocking_statement
FROM pg_catalog.pg_locks blocked_locks
JOIN pg_catalog.pg_stat_activity blocked_activity ON blocked_activity.pid = blocked_locks.pid
JOIN pg_catalog.pg_locks blocking_locks ON blocking_locks.locktype = blocked_locks.locktype
    AND blocking_locks.relation = blocked_locks.relation
    AND blocking_locks.pid != blocked_locks.pid
JOIN pg_catalog.pg_stat_activity blocking_activity ON blocking_activity.pid = blocking_locks.pid
WHERE NOT blocked_locks.granted;
```

---

## 11. APPENDICES

### Appendix A: Performance Tuning Checklist

```
□ Shared buffers configured to 25% of RAM
□ Effective cache size set to 75% of RAM
□ Work_mem appropriate for query complexity
□ Autovacuum properly tuned
□ Appropriate indexes created
□ Unused indexes identified and removed
□ Query plans reviewed for slow queries
□ Connection pooling configured
□ Partitioning implemented for large tables
□ Materialized views for expensive aggregations
□ Monitoring dashboards configured
□ Alert rules defined
□ Maintenance procedures scheduled
```

### Appendix B: Benchmark Results Template

| Date | Workload | TPS | Avg Latency | 95th Percentile | Notes |
|------|----------|-----|-------------|-----------------|-------|
| 2026-10-01 | B2C Order | 1,200 | 45ms | 180ms | Baseline |
| 2026-10-15 | B2C Order | 1,500 | 32ms | 120ms | After indexing |

---

**END OF DATABASE PERFORMANCE TUNING GUIDE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | October 15, 2026 | Database Administrator | Initial version |
