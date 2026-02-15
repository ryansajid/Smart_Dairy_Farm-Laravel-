# L-009: Database Administration Guide

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | L-009 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | DBA |
| **Owner** | DBA |
| **Reviewer** | IT Director |
| **Classification** | Internal Use |
| **Status** | Approved |

---

## Document Control

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | DBA | Initial release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Database Architecture](#2-database-architecture)
3. [Daily Operations](#3-daily-operations)
4. [Weekly Maintenance](#4-weekly-maintenance)
5. [Monthly Maintenance](#5-monthly-maintenance)
6. [User Management](#6-user-management)
7. [Performance Monitoring](#7-performance-monitoring)
8. [Troubleshooting](#8-troubleshooting)
9. [Security](#9-security)
10. [Maintenance Windows](#10-maintenance-windows)
11. [Emergency Procedures](#11-emergency-procedures)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides comprehensive guidelines for the administration, maintenance, and operation of the Smart Dairy Ltd. database infrastructure. It serves as the primary reference for Database Administrators (DBAs) responsible for ensuring database availability, performance, and security.

### 1.2 Scope

This guide covers:
- PostgreSQL 16 database administration
- TimescaleDB time-series data management
- Primary-standby replication management
- Connection pooling with PgBouncer
- Routine maintenance procedures
- Performance monitoring and optimization
- Security management
- Backup and recovery operations
- Troubleshooting procedures

### 1.3 Database Environment Overview

| Component | Specification |
|-----------|--------------|
| **Primary Database** | PostgreSQL 16.x |
| **Extensions** | TimescaleDB 2.x, PostGIS, pg_stat_statements |
| **Replication** | Streaming replication (1 standby) |
| **Connection Pooler** | PgBouncer 1.21 |
| **Operating System** | Ubuntu 22.04 LTS |
| **Storage** | SSD-based, 500GB minimum |
| **Memory** | 32GB RAM (production) |

### 1.4 Key Database Instances

| Instance | Host | Port | Purpose |
|----------|------|------|---------|
| Production Primary | db-primary.smartdairy.local | 5432 | Primary write/read |
| Production Standby | db-standby.smartdairy.local | 5432 | Hot standby, read replica |
| PgBouncer | pgbouncer.smartdairy.local | 6432 | Connection pooling |

---

## 2. Database Architecture

### 2.1 PostgreSQL 16 Setup

#### 2.1.1 Installation Requirements

```bash
# System requirements check
vm.overcommit_memory = 2
vm.overcommit_ratio = 80
kernel.shmmax = 17179869184
kernel.shmall = 4194304
```

#### 2.1.2 Key Configuration Parameters (postgresql.conf)

```ini
# Connection Settings
max_connections = 200
superuser_reserved_connections = 3
listen_addresses = '*'
port = 5432

# Memory Settings
shared_buffers = 8GB
effective_cache_size = 24GB
work_mem = 64MB
maintenance_work_mem = 2GB
dynamic_shared_memory_type = posix

# WAL Settings
wal_level = replica
wal_buffers = 16MB
max_wal_size = 4GB
min_wal_size = 1GB
checkpoint_completion_target = 0.9
archive_mode = on
archive_command = 'cp %p /var/lib/postgresql/archive/%f'

# Query Planning
effective_io_concurrency = 200
random_page_cost = 1.1

# Logging
log_destination = 'stderr,csvlog'
logging_collector = on
log_directory = 'log'
log_filename = 'postgresql-%Y-%m-%d_%H%M%S.log'
log_rotation_age = 1d
log_rotation_size = 100MB
log_min_duration_statement = 1000
log_checkpoints = on
log_connections = on
log_disconnections = on
log_lock_waits = on
log_temp_files = 0

# Extensions
shared_preload_libraries = 'pg_stat_statements,timescaledb,pg_prewarm'
pg_stat_statements.max = 10000
pg_stat_statements.track = all
```

#### 2.1.3 pg_hba.conf Access Control

```
# TYPE  DATABASE        USER            ADDRESS                 METHOD

# Local connections
local   all             postgres                                peer
local   all             all                                     md5

# IPv4 local connections
host    all             all             127.0.0.1/32            md5
host    all             all             10.0.0.0/8              md5
host    replication     replicator      10.0.0.0/8              md5

# Application-specific
host    smart_dairy     app_user        10.0.1.0/24             md5
host    smart_dairy     readonly_user   10.0.2.0/24             md5
```

### 2.2 Primary-Standby Replication

#### 2.2.1 Replication Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    Smart Dairy Database Cluster                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│   ┌──────────────────┐          ┌──────────────────┐          │
│   │   PRIMARY        │          │   STANDBY        │          │
│   │   db-primary     │◄─────────│   db-standby     │          │
│   │   Port: 5432     │  WAL     │   Port: 5432     │          │
│   │   Read/Write     │ Stream   │   Read-Only      │          │
│   └────────┬─────────┘          └──────────────────┘          │
│            │                                                    │
│            ▼                                                    │
│   ┌──────────────────┐                                         │
│   │   PgBouncer      │                                         │
│   │   Port: 6432     │                                         │
│   │   Connection     │                                         │
│   │   Pooling        │                                         │
│   └────────┬─────────┘                                         │
│            │                                                    │
│            ▼                                                    │
│   ┌──────────────────┐                                         │
│   │   Applications   │                                         │
│   └──────────────────┘                                         │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

#### 2.2.2 Setting Up Streaming Replication

**On Primary:**

```sql
-- Create replication user
CREATE USER replicator WITH REPLICATION ENCRYPTED PASSWORD 'secure_password';

-- Update pg_hba.conf
-- host replication replicator 10.0.0.0/8 md5

-- Create base backup
SELECT pg_create_physical_replication_slot('standby_slot');
```

**On Standby:**

```bash
# Stop PostgreSQL
sudo systemctl stop postgresql

# Clear data directory
rm -rf /var/lib/postgresql/16/main/*

# Base backup from primary
pg_basebackup -h db-primary.smartdairy.local -D /var/lib/postgresql/16/main \
  -U replicator -P -v -R -X stream -S standby_slot

# Create standby.signal
touch /var/lib/postgresql/16/main/standby.signal

# Start PostgreSQL
sudo systemctl start postgresql
```

#### 2.2.3 Replication Monitoring

```sql
-- Check replication status on primary
SELECT 
    client_addr,
    state,
    sent_lsn,
    write_lsn,
    flush_lsn,
    replay_lsn,
    write_lag,
    flush_lag,
    replay_lag
FROM pg_stat_replication;

-- Check replication status on standby
SELECT 
    now() - pg_last_xact_replay_timestamp() AS replication_lag,
    pg_is_in_recovery() AS is_standby,
    pg_last_wal_receive_lsn() AS receive_lsn,
    pg_last_wal_replay_lsn() AS replay_lsn;
```

### 2.3 TimescaleDB Extension

#### 2.3.1 Installation and Setup

```sql
-- Install TimescaleDB extension
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- Verify installation
SELECT * FROM timescaledb_information.hypertables;
```

#### 2.3.2 Hypertable Configuration

```sql
-- Create hypertable for sensor data
CREATE TABLE sensor_readings (
    time TIMESTAMPTZ NOT NULL,
    sensor_id INTEGER,
    temperature DECIMAL(5,2),
    humidity DECIMAL(5,2),
    location VARCHAR(50)
);

-- Convert to hypertable
SELECT create_hypertable('sensor_readings', 'time', 
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE);

-- Enable compression
ALTER TABLE sensor_readings SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'sensor_id,location',
    timescaledb.compress_orderby = 'time DESC'
);

-- Set compression policy
SELECT add_compression_policy('sensor_readings', INTERVAL '7 days');

-- Set retention policy (keep 90 days)
SELECT add_retention_policy('sensor_readings', INTERVAL '90 days');
```

#### 2.3.3 TimescaleDB Maintenance

```sql
-- Check chunk sizes
SELECT 
    chunk_name,
    hypertable_name,
    range_start,
    range_end,
    pg_size_pretty(total_bytes) AS size
FROM chunks_detailed_size('sensor_readings')
ORDER BY range_start DESC;

-- Manual compression of old chunks
SELECT compress_chunk(i) 
FROM show_chunks('sensor_readings', older_than => INTERVAL '7 days') i
WHERE NOT is_compressed(i);

-- Reindex hypertable
REINDEX TABLE sensor_readings;
```

### 2.4 Connection Pooling (PgBouncer)

#### 2.4.1 PgBouncer Configuration (pgbouncer.ini)

```ini
[databases]
smart_dairy = host=db-primary.smartdairy.local port=5432 dbname=smart_dairy
smart_dairy_standby = host=db-standby.smartdairy.local port=5432 dbname=smart_dairy

[pgbouncer]
listen_port = 6432
listen_addr = *
auth_type = md5
auth_file = /etc/pgbouncer/userlist.txt
pool_mode = transaction
max_client_conn = 1000
default_pool_size = 50
min_pool_size = 10
reserve_pool_size = 10
reserve_pool_timeout = 3
max_db_connections = 100
max_user_connections = 100
server_idle_timeout = 600
server_lifetime = 3600
server_connect_timeout = 15
query_timeout = 0
query_wait_timeout = 120
client_idle_timeout = 0
client_login_timeout = 60

# Log settings
logfile = /var/log/pgbouncer/pgbouncer.log
pidfile = /var/run/pgbouncer/pgbouncer.pid
admin_users = postgres,dba
stats_users = dba,monitoring

# TLS settings (optional)
client_tls_sslmode = prefer
client_tls_key_file = /etc/ssl/private/pgbouncer.key
client_tls_cert_file = /etc/ssl/certs/pgbouncer.crt
```

#### 2.4.2 User Authentication (userlist.txt)

```
"postgres" "SCRAM-SHA-256$..."
"app_user" "SCRAM-SHA-256$..."
"readonly_user" "SCRAM-SHA-256$..."
"dba" "SCRAM-SHA-256$..."
```

#### 2.4.3 PgBouncer Management

```bash
# Connect to admin console
psql -p 6432 pgbouncer -U dba

# Show pools
SHOW POOLS;

# Show stats
SHOW STATS;

# Show clients
SHOW CLIENTS;

# Show servers
SHOW SERVERS;

# Reload configuration
RELOAD;

# Pause all connections
PAUSE;

# Resume connections
RESUME;

# Kill a specific client
KILL <client_id>;
```

---

## 3. Daily Operations

### 3.1 Daily Checklist

| Task | Time | Owner | SLA |
|------|------|-------|-----|
| Database connectivity check | 08:00 | Automated | 99.9% |
| Replication lag check | 08:00 | Automated | < 5 seconds |
| Disk space check | 08:00 | Automated | < 80% |
| Error log review | 09:00 | DBA | Same day |
| Connection pool check | 09:00 | DBA | Same day |
| Backup verification | 10:00 | Automated | Daily |
| Performance metrics review | 14:00 | DBA | Same day |

### 3.2 Health Checks

#### 3.2.1 Database Connectivity Check

```bash
#!/bin/bash
# health_check.sh

HOST="db-primary.smartdairy.local"
PORT="5432"
DB="smart_dairy"
USER="monitoring"

# Test connectivity
psql -h $HOST -p $PORT -d $DB -U $USER -c "SELECT 1;" > /dev/null 2>&1

if [ $? -eq 0 ]; then
    echo "$(date): Database is healthy"
    exit 0
else
    echo "$(date): CRITICAL - Database connectivity issue"
    # Send alert
    exit 1
fi
```

#### 3.2.2 Database Health Query

```sql
-- Comprehensive health check
WITH health_check AS (
    SELECT 
        -- Database info
        current_database() AS database,
        version() AS version,
        
        -- Connection info
        (SELECT count(*) FROM pg_stat_activity) AS active_connections,
        (SELECT setting::int FROM pg_settings WHERE name = 'max_connections') AS max_connections,
        
        -- Replication lag
        (SELECT 
            CASE 
                WHEN pg_is_in_recovery() 
                THEN extract(epoch from (now() - pg_last_xact_replay_timestamp()))
                ELSE 0 
            END) AS replication_lag_seconds,
        
        -- Database size
        (SELECT pg_database_size(current_database())) AS db_size_bytes,
        
        -- Transaction stats
        (SELECT xact_commit FROM pg_stat_database WHERE datname = current_database()) AS xact_commit,
        (SELECT xact_rollback FROM pg_stat_database WHERE datname = current_database()) AS xact_rollback,
        
        -- Cache hit ratio
        (SELECT 
            round(blks_hit * 100.0 / nullif(blks_hit + blks_read, 0), 2)
         FROM pg_stat_database 
         WHERE datname = current_database()) AS cache_hit_ratio,
         
        -- Deadlocks
        (SELECT deadlocks FROM pg_stat_database WHERE datname = current_database()) AS deadlocks
)
SELECT 
    database,
    split_part(version, ' ', 2) AS pg_version,
    active_connections,
    max_connections,
    round(active_connections * 100.0 / max_connections, 2) AS connection_pct,
    round(replication_lag_seconds, 2) AS replication_lag_sec,
    pg_size_pretty(db_size_bytes) AS database_size,
    cache_hit_ratio || '%' AS cache_hit_ratio,
    deadlocks
FROM health_check;
```

### 3.3 Log Monitoring

#### 3.3.1 Critical Error Patterns

```bash
#!/bin/bash
# log_monitor.sh

LOG_DIR="/var/log/postgresql"
ALERT_PATTERNS=(
    "FATAL:"
    "PANIC:"
    "ERROR:.*connection"
    "could not receive data"
    "terminating connection"
    "out of memory"
    "deadlock detected"
    "checkpoint request failed"
    "replication slot"
)

for pattern in "${ALERT_PATTERNS[@]}"; do
    count=$(grep -c "$pattern" $LOG_DIR/postgresql-*.log 2>/dev/null | awk -F: '{sum+=$2} END {print sum}')
    if [ "$count" -gt 0 ]; then
        echo "ALERT: Found $count occurrences of '$pattern' in logs"
    fi
done
```

#### 3.3.2 Log Analysis Query

```sql
-- Query pg_log files (if using CSV format)
SELECT 
    log_time,
    error_severity,
    sql_state_code,
    message,
    detail,
    hint,
    query
FROM pg_log
WHERE log_time > now() - interval '24 hours'
    AND error_severity IN ('FATAL', 'PANIC', 'ERROR')
ORDER BY log_time DESC
LIMIT 100;
```

### 3.4 Space Monitoring

#### 3.4.1 Disk Space Monitoring

```sql
-- Table and index sizes
SELECT 
    schemaname || '.' || relname AS relation,
    pg_size_pretty(pg_relation_size(C.oid)) AS size,
    pg_size_pretty(pg_indexes_size(C.oid)) AS indexes_size,
    pg_size_pretty(pg_total_relation_size(C.oid)) AS total_size,
    n_live_tup AS live_tuples,
    n_dead_tup AS dead_tuples,
    round(n_dead_tup * 100.0 / nullif(n_live_tup + n_dead_tup, 0), 2) AS dead_tuple_pct
FROM pg_class C
LEFT JOIN pg_namespace N ON N.oid = C.relnamespace
LEFT JOIN pg_stat_user_tables S ON S.relid = C.oid
WHERE C.relkind IN ('r', 'm')
    AND N.nspname NOT IN ('pg_catalog', 'information_schema')
ORDER BY pg_total_relation_size(C.oid) DESC
LIMIT 20;
```

#### 3.4.2 Tablespace Monitoring

```sql
-- Tablespace usage
SELECT 
    spcname AS tablespace,
    pg_size_pretty(pg_tablespace_size(spcname)) AS size,
    pg_tablespace_location(oid) AS location
FROM pg_tablespace;

-- Database size growth trend
SELECT 
    datname,
    pg_size_pretty(pg_database_size(datname)) AS current_size,
    stats_reset,
    xact_commit AS total_transactions
FROM pg_stat_database
WHERE datname NOT IN ('template0', 'template1');
```

---

## 4. Weekly Maintenance

### 4.1 Weekly Checklist

| Day | Task | Estimated Duration | Downtime |
|-----|------|-------------------|----------|
| Sunday 02:00 | VACUUM ANALYZE - Small tables | 30 min | None |
| Sunday 03:00 | Index maintenance - B-tree | 1 hour | Minimal |
| Wednesday 02:00 | Update statistics | 30 min | None |
| Friday 18:00 | PgBouncer pool review | 15 min | None |

### 4.2 Vacuum and Analyze

#### 4.2.1 Weekly VACUUM Script

```bash
#!/bin/bash
# weekly_vacuum.sh

DB_NAME="smart_dairy"
LOG_FILE="/var/log/postgresql/weekly_vacuum_$(date +%Y%m%d).log"

# Tables to vacuum (excluding large TimescaleDB chunks)
TABLES=(
    "public.users"
    "public.devices"
    "public.alerts"
    "public.configurations"
)

echo "Starting weekly VACUUM ANALYZE at $(date)" >> $LOG_FILE

for table in "${TABLES[@]}"; do
    echo "Processing $table..." >> $LOG_FILE
    psql -d $DB_NAME -c "VACUUM ANALYZE $table;" >> $LOG_FILE 2>&1
    sleep 5
done

echo "Completed at $(date)" >> $LOG_FILE
```

#### 4.2.2 Vacuum Progress Monitoring

```sql
-- Monitor VACUUM progress
SELECT 
    p.pid,
    now() - a.xact_start AS duration,
    p.query,
    p.state,
    p.wait_event_type,
    p.wait_event
FROM pg_stat_activity a
JOIN pg_stat_progress_vacuum p USING (pid)
WHERE a.query LIKE '%VACUUM%';

-- Check vacuum stats for tables
SELECT 
    schemaname,
    relname,
    last_vacuum,
    last_autovacuum,
    last_analyze,
    last_autoanalyze,
    vacuum_count,
    autovacuum_count,
    analyze_count,
    n_tup_ins,
    n_tup_upd,
    n_tup_del,
    n_dead_tup
FROM pg_stat_user_tables
WHERE schemaname NOT IN ('pg_catalog', 'information_schema')
ORDER BY n_dead_tup DESC
LIMIT 20;
```

### 4.3 Index Maintenance

#### 4.3.1 Index Bloat Detection

```sql
-- Check index bloat
SELECT 
    schemaname,
    relname AS table_name,
    indexrelname AS index_name,
    pg_size_pretty(pg_relation_size(indexrelid)) AS index_size,
    idx_scan AS index_scans,
    idx_tup_read,
    idx_tup_fetch,
    round(
        (pg_relation_size(indexrelid) - 
         (pg_relation_size(relid) * 
          (reltuples / nullif(pg_relation_size(relid) / 8192.0, 0)))) 
        * 100.0 / pg_relation_size(indexrelid), 2
    ) AS bloat_pct
FROM pg_stat_user_indexes
JOIN pg_class ON pg_class.oid = pg_stat_user_indexes.relid
WHERE schemaname NOT IN ('pg_catalog', 'information_schema')
ORDER BY pg_relation_size(indexrelid) DESC
LIMIT 20;
```

#### 4.3.2 Index Rebuild Script

```sql
-- Rebuild bloated indexes concurrently
DO $$
DECLARE
    r RECORD;
BEGIN
    FOR r IN 
        SELECT 
            schemaname,
            relname,
            indexrelname
        FROM pg_stat_user_indexes
        WHERE pg_relation_size(indexrelid) > 100000000  -- > 100MB
        ORDER BY pg_relation_size(indexrelid) DESC
        LIMIT 5
    LOOP
        RAISE NOTICE 'Rebuilding %.%', r.schemaname, r.indexrelname;
        EXECUTE format('REINDEX INDEX CONCURRENTLY %I.%I', 
                       r.schemaname, r.indexrelname);
    END LOOP;
END $$;
```

### 4.4 Statistics Update

```sql
-- Update statistics for all tables
DO $$
DECLARE
    r RECORD;
BEGIN
    FOR r IN 
        SELECT schemaname, relname
        FROM pg_stat_user_tables
        WHERE schemaname NOT IN ('pg_catalog', 'information_schema')
        ORDER BY n_tup_ins + n_tup_upd DESC
        LIMIT 50
    LOOP
        EXECUTE format('ANALYZE %I.%I', r.schemaname, r.relname);
    END LOOP;
END $$;
```

---

## 5. Monthly Maintenance

### 5.1 Monthly Checklist

| Task | Week | Duration | Impact |
|------|------|----------|--------|
| Deep VACUUM FULL | Week 1 | 2-4 hours | Exclusive lock |
| Partition management | Week 2 | 1 hour | Minimal |
| User access audit | Week 3 | 30 min | None |
| Configuration review | Week 4 | 1 hour | Restart required |
| Disaster recovery test | Week 4 | 4 hours | Standby only |

### 5.2 Deep Vacuum

#### 5.2.1 VACUUM FULL Planning

```sql
-- Identify tables needing VACUUM FULL
SELECT 
    schemaname || '.' || relname AS table_name,
    pg_size_pretty(pg_relation_size(relid)) AS table_size,
    pg_size_pretty(pg_total_relation_size(relid)) AS total_size,
    n_live_tup,
    n_dead_tup,
    round(n_dead_tup * 100.0 / nullif(n_live_tup, 0), 2) AS dead_ratio_pct,
    last_vacuum,
    last_autovacuum
FROM pg_stat_user_tables
WHERE n_dead_tup > 10000
    OR (n_dead_tup * 100.0 / nullif(n_live_tup, 0)) > 20
ORDER BY n_dead_tup DESC;
```

#### 5.2.2 VACUUM FULL Procedure

```bash
#!/bin/bash
# monthly_deep_vacuum.sh
# WARNING: This requires exclusive locks - schedule during maintenance window

DB_NAME="smart_dairy"
MAINTENANCE_TABLES=(
    "public.archive_logs"
    "public.audit_trail"
)

echo "Starting deep VACUUM at $(date)"

for table in "${MAINTENANCE_TABLES[@]}"; do
    echo "Processing $table..."
    
    # Check table size before
    size_before=$(psql -d $DB_NAME -t -c "SELECT pg_size_pretty(pg_relation_size('$table'));")
    echo "Size before: $size_before"
    
    # Perform VACUUM FULL
    psql -d $DB_NAME -c "VACUUM FULL ANALYZE $table;"
    
    # Check table size after
    size_after=$(psql -d $DB_NAME -t -c "SELECT pg_size_pretty(pg_relation_size('$table'));")
    echo "Size after: $size_after"
done

echo "Completed at $(date)"
```

### 5.3 Partition Management

#### 5.3.1 TimescaleDB Partition Review

```sql
-- List all chunks and their compression status
SELECT 
    h.hypertable_name,
    c.chunk_name,
    c.range_start,
    c.range_end,
    pg_size_pretty(d.total_bytes) AS size,
    c.is_compressed,
    c.compression_status
FROM timescaledb_information.hypertables h
JOIN timescaledb_information.chunks c ON c.hypertable_name = h.hypertable_name
CROSS JOIN LATERAL chunks_detailed_size(c.chunk_name::regclass) d
WHERE c.range_start < now() - interval '7 days'
ORDER BY c.range_start DESC;
```

#### 5.3.2 Partition Maintenance Script

```sql
-- Manual chunk compression for old data
DO $$
DECLARE
    chunk_rec RECORD;
BEGIN
    FOR chunk_rec IN 
        SELECT chunk_name
        FROM timescaledb_information.chunks
        WHERE range_end < now() - interval '14 days'
            AND is_compressed = false
        ORDER BY range_start
        LIMIT 10
    LOOP
        RAISE NOTICE 'Compressing chunk: %', chunk_rec.chunk_name;
        PERFORM compress_chunk(chunk_rec.chunk_name::regclass);
    END LOOP;
END $$;

-- Drop old compressed chunks (if retention policy allows)
-- SELECT drop_chunks('sensor_readings', older_than => interval '90 days');
```

### 5.4 User Audit

#### 5.4.1 User Access Review

```sql
-- List all database users
SELECT 
    rolname AS username,
    rolcreatedb AS can_create_db,
    rolcreaterole AS can_create_role,
    rolsuper AS is_superuser,
    rolconnlimit AS connection_limit,
    COALESCE(
        (SELECT string_agg(privilege_type, ', ')
         FROM information_schema.table_privileges 
         WHERE grantee = rolname),
        'No table privileges'
    ) AS table_privileges,
    rolvaliduntil AS password_expires
FROM pg_roles
WHERE rolname NOT LIKE 'pg_%'
    AND rolname NOT LIKE 'rds%'
ORDER BY rolname;

-- Check for inactive users
SELECT 
    usename AS username,
    count(*) AS session_count,
    max(backend_start) AS last_session_start,
    max(state_change) AS last_activity
FROM pg_stat_activity
GROUP BY usename
ORDER BY max(state_change) NULLS LAST;
```

#### 5.4.2 Privilege Review

```sql
-- Review table-level privileges
SELECT 
    grantee,
    table_schema,
    table_name,
    string_agg(privilege_type, ', ' ORDER BY privilege_type) AS privileges
FROM information_schema.table_privileges
WHERE grantee NOT IN ('postgres', 'PUBLIC')
    AND table_schema NOT IN ('pg_catalog', 'information_schema')
GROUP BY grantee, table_schema, table_name
ORDER BY grantee, table_schema, table_name;
```

---

## 6. User Management

### 6.1 Creating Users

#### 6.1.1 Application User Creation

```sql
-- Create application read-write user
CREATE USER app_user WITH 
    LOGIN 
    PASSWORD 'secure_random_password'
    CONNECTION LIMIT 100
    VALID UNTIL '2027-01-31';

-- Grant schema usage
GRANT USAGE ON SCHEMA public TO app_user;
GRANT USAGE ON SCHEMA timescaledb_information TO app_user;

-- Grant table privileges
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO app_user;
GRANT USAGE ON ALL SEQUENCES IN SCHEMA public TO app_user;

-- Grant future objects
ALTER DEFAULT PRIVILEGES IN SCHEMA public 
    GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO app_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public 
    GRANT USAGE ON SEQUENCES TO app_user;
```

#### 6.1.2 Read-Only User Creation

```sql
-- Create read-only user for reporting
CREATE USER readonly_user WITH 
    LOGIN 
    PASSWORD 'secure_random_password'
    CONNECTION LIMIT 50
    VALID UNTIL '2027-01-31';

-- Grant read access
GRANT USAGE ON SCHEMA public TO readonly_user;
GRANT SELECT ON ALL TABLES IN SCHEMA public TO readonly_user;

-- Future tables
ALTER DEFAULT PRIVILEGES IN SCHEMA public 
    GRANT SELECT ON TABLES TO readonly_user;

-- Optional: Restrict sensitive tables
REVOKE SELECT ON public.users, public.payment_info FROM readonly_user;
```

### 6.2 Granting Permissions

#### 6.2.1 Role-Based Access Control

```sql
-- Create roles
CREATE ROLE app_read;
CREATE ROLE app_write;
CREATE ROLE app_admin;

-- Grant privileges to roles
GRANT USAGE ON SCHEMA public TO app_read, app_write, app_admin;
GRANT SELECT ON ALL TABLES IN SCHEMA public TO app_read;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO app_write;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO app_admin;

-- Grant roles to users
GRANT app_read TO readonly_user;
GRANT app_write TO app_user;
GRANT app_admin TO admin_user;
```

#### 6.2.2 Object-Level Permissions

```sql
-- Grant specific column access
GRANT SELECT (id, name, email, created_at) ON users TO reporting_user;

-- Grant execute on specific functions
GRANT EXECUTE ON FUNCTION get_sensor_summary(integer, timestamptz, timestamptz) TO app_user;

-- Grant specific schema access
GRANT USAGE ON SCHEMA analytics TO analytics_user;
GRANT SELECT ON ALL TABLES IN SCHEMA analytics TO analytics_user;
```

### 6.3 Role Management

#### 6.3.1 Role Hierarchy

```
┌─────────────────────────────────────────────────────────────┐
│                    ROLE HIERARCHY                          │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│   ┌──────────────┐                                         │
│   │  SUPERUSER   │  (postgres, dba)                        │
│   └──────┬───────┘                                         │
│          │                                                  │
│   ┌──────┴───────┐                                         │
│   │  APP_ADMIN   │  (Full app access)                      │
│   └──────┬───────┘                                         │
│          │                                                  │
│   ┌──────┴───────┐                                         │
│   │  APP_WRITE   │  (CRUD operations)                      │
│   └──────┬───────┘                                         │
│          │                                                  │
│   ┌──────┴───────┐                                         │
│   │  APP_READ    │  (SELECT only)                          │
│   └──────────────┘                                         │
│                                                             │
│   Special Roles:                                            │
│   - REPLICATOR: Replication only                            │
│   - MONITORING: Stats and health checks                     │
│   - BACKUP: Backup operations                               │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

#### 6.3.2 Password Management

```sql
-- Change password
ALTER USER app_user WITH PASSWORD 'new_secure_password';

-- Set password expiration
ALTER USER app_user VALID UNTIL '2027-01-31';

-- Lock/unlock account
ALTER USER app_user NOLOGIN;  -- Lock
ALTER USER app_user LOGIN;    -- Unlock

-- Password policy check
SELECT 
    rolname,
    CASE 
        WHEN rolvaliduntil IS NULL THEN 'No expiration'
        WHEN rolvaliduntil < now() + interval '30 days' THEN 'Expires soon: ' || rolvaliduntil::text
        ELSE 'Valid until: ' || rolvaliduntil::text
    END AS password_status
FROM pg_roles
WHERE rolcanlogin = true
    AND rolname NOT LIKE 'pg_%'
ORDER BY rolvaliduntil NULLS LAST;
```

---

## 7. Performance Monitoring

### 7.1 Query Performance

#### 7.1.1 Slow Query Analysis

```sql
-- Top slow queries (using pg_stat_statements)
SELECT 
    queryid,
    substring(query, 1, 100) AS query_preview,
    calls,
    round(total_exec_time::numeric, 2) AS total_time_ms,
    round(mean_exec_time::numeric, 4) AS avg_time_ms,
    round(stddev_exec_time::numeric, 4) AS stddev_time_ms,
    rows,
    round((100 * total_exec_time / sum(total_exec_time) OVER ())::numeric, 2) AS pct_total_time
FROM pg_stat_statements
WHERE query NOT LIKE '%pg_stat_statements%'
ORDER BY total_exec_time DESC
LIMIT 20;
```

#### 7.1.2 Query Performance Dashboard

```sql
-- Real-time query performance
SELECT 
    pid,
    now() - query_start AS duration,
    state,
    wait_event_type,
    wait_event,
    left(query, 100) AS query_preview
FROM pg_stat_activity
WHERE state != 'idle'
    AND query NOT LIKE '%pg_stat_activity%'
ORDER BY duration DESC;
```

### 7.2 Lock Monitoring

#### 7.2.1 Active Locks

```sql
-- Current locks
SELECT 
    l.locktype,
    l.database,
    l.relation::regclass AS table_name,
    l.page,
    l.tuple,
    l.virtualxid,
    l.transactionid,
    l.classid,
    l.granted,
    a.usename,
    a.application_name,
    a.client_addr,
    a.state,
    left(a.query, 100) AS query
FROM pg_locks l
JOIN pg_stat_activity a ON l.pid = a.pid
WHERE NOT l.granted
    OR l.locktype = 'relation'
ORDER BY l.granted, l.relation::regclass::text;
```

#### 7.2.2 Blocking Queries

```sql
-- Find blocking queries
SELECT 
    blocked_locks.pid AS blocked_pid,
    blocked_activity.usename AS blocked_user,
    blocking_locks.pid AS blocking_pid,
    blocking_activity.usename AS blocking_user,
    blocked_activity.query AS blocked_statement,
    blocking_activity.query AS blocking_statement,
    blocked_activity.application_name AS blocked_app,
    blocking_activity.application_name AS blocking_app
FROM pg_catalog.pg_locks blocked_locks
JOIN pg_catalog.pg_stat_activity blocked_activity 
    ON blocked_activity.pid = blocked_locks.pid
JOIN pg_catalog.pg_locks blocking_locks 
    ON blocking_locks.locktype = blocked_locks.locktype
    AND blocking_locks.relation = blocked_locks.relation
    AND blocking_locks.pid != blocked_locks.pid
JOIN pg_catalog.pg_stat_activity blocking_activity 
    ON blocking_activity.pid = blocking_locks.pid
WHERE NOT blocked_locks.granted;
```

### 7.3 Connection Monitoring

#### 7.3.1 Connection Statistics

```sql
-- Connection summary
SELECT 
    datname AS database,
    usename AS username,
    application_name,
    client_addr,
    state,
    count(*) AS connection_count
FROM pg_stat_activity
GROUP BY datname, usename, application_name, client_addr, state
ORDER BY count(*) DESC;

-- Connection state breakdown
SELECT 
    state,
    count(*),
    round(100.0 * count(*) / sum(count(*)) OVER (), 2) AS percentage
FROM pg_stat_activity
GROUP BY state
ORDER BY count(*) DESC;
```

#### 7.3.2 Connection Pool Monitoring

```sql
-- Long-running connections
SELECT 
    pid,
    usename,
    application_name,
    backend_start,
    xact_start,
    now() - xact_start AS xact_duration,
    state,
    left(query, 100) AS query
FROM pg_stat_activity
WHERE xact_start IS NOT NULL
    AND now() - xact_start > interval '5 minutes'
ORDER BY xact_duration DESC;
```

---

## 8. Troubleshooting

### 8.1 Slow Queries

#### 8.1.1 Slow Query Troubleshooting Flowchart

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         SLOW QUERY DETECTED                             │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Check: Is the query using an index?                                    │
│  Query: EXPLAIN (ANALYZE, BUFFERS) SELECT ...                           │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                    ┌───────────────┴───────────────┐
                    │                               │
                   YES                              NO
                    │                               │
                    ▼                               ▼
┌───────────────────────────────┐   ┌───────────────────────────────────────┐
│  Check: Index efficiency      │   │  Create appropriate index             │
│  - Seq scan on large tables?  │   │  - B-tree for equality/range          │
│  - Index scan slow?           │   │  - GIN for array/json                 │
│                               │   │  - BRIN for time-series               │
└───────────────────────────────┘   └───────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Check: Are statistics up to date?                                      │
│  Query: SELECT last_analyze FROM pg_stat_user_tables                    │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                    ┌───────────────┴───────────────┐
                    │                               │
               RECENT                            STALE
                    │                               │
                    ▼                               ▼
┌───────────────────────────────┐   ┌───────────────────────────────────────┐
│  Check: Query structure       │   │  Run: ANALYZE table_name              │
│  - Unnecessary joins?         │   │  Consider: increasing                 │
│  - Suboptimal WHERE clauses?  │   │  default_statistics_target            │
└───────────────────────────────┘   └───────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Check: Resource contention                                             │
│  - Lock waits? (pg_locks)                                               │
│  - High CPU/IO? (system metrics)                                        │
│  - Connection pool saturated?                                           │
└─────────────────────────────────────────────────────────────────────────┘
```

#### 8.1.2 Query Optimization Queries

```sql
-- Check for missing indexes
SELECT 
    schemaname,
    relname AS table_name,
    seq_scan - idx_scan AS too_much_seq,
    CASE WHEN seq_scan - idx_scan > 0 
        THEN 'Missing Index?' 
        ELSE 'OK' 
    END AS status,
    pg_size_pretty(pg_relation_size(relid)) AS table_size
FROM pg_stat_user_tables
WHERE seq_scan - idx_scan > 100
ORDER BY too_much_seq DESC
LIMIT 10;

-- Unused indexes
SELECT 
    schemaname,
    relname AS table,
    indexrelname AS index,
    pg_size_pretty(pg_relation_size(indexrelid)) AS index_size,
    idx_scan AS index_scans
FROM pg_stat_user_indexes
WHERE idx_scan = 0 
    AND pg_relation_size(indexrelid) > 1000000
ORDER BY pg_relation_size(indexrelid) DESC;
```

### 8.2 Lock Contention

#### 8.2.1 Lock Troubleshooting Flowchart

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    LOCK CONTENTION DETECTED                             │
│                    (High wait_event_type = Lock)                        │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Identify blocking and blocked sessions                                 │
│  Query: blocking_queries.sql (from section 7.2.2)                       │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Check: Is the blocking query long-running?                             │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                    ┌───────────────┴───────────────┐
                    │                               │
                   YES                              NO
                    │                               │
                    ▼                               ▼
┌───────────────────────────────┐   ┌───────────────────────────────────────┐
│  Check: Can it be optimized?  │   │  Check: Transaction isolation         │
│  - Reduce transaction scope   │   │  - Is it holding locks unnecessarily? │
│  - Add missing indexes        │   │  - Are autocommits enabled?           │
│  - Kill if necessary          │   │  - Application issue?                 │
└───────────────────────────────┘   └───────────────────────────────────────┘
                    │                               │
                    └───────────────┬───────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Resolution Actions:                                                    │
│  1. Optimize or terminate blocking query                                │
│  2. Adjust application transaction logic                                │
│  3. Consider lowering lock_timeout                                      │
│  4. Enable log_lock_waits for monitoring                                │
└─────────────────────────────────────────────────────────────────────────┘
```

#### 8.2.2 Lock Resolution Commands

```sql
-- Terminate a blocking session (use with caution!)
SELECT pg_terminate_backend(blocking_pid) 
FROM (
    SELECT DISTINCT blocking_locks.pid AS blocking_pid
    FROM pg_catalog.pg_locks blocked_locks
    JOIN pg_catalog.pg_locks blocking_locks 
        ON blocking_locks.locktype = blocked_locks.locktype
        AND blocking_locks.relation = blocked_locks.relation
        AND blocking_locks.pid != blocked_locks.pid
    WHERE NOT blocked_locks.granted
) blockers;

-- Check for deadlocks
SELECT 
    deadlocks,
    stats_reset
FROM pg_stat_database
WHERE datname = current_database();
```

### 8.3 Replication Lag

#### 8.3.1 Replication Troubleshooting Flowchart

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    REPLICATION LAG DETECTED                             │
│                    (replay_lag > 5 seconds)                             │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Check: Network connectivity between primary and standby                │
│  - ping db-primary from standby                                         │
│  - Check bandwidth utilization                                          │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                    ┌───────────────┴───────────────┐
                    │                               │
               HEALTHY                          ISSUE
                    │                               │
                    ▼                               ▼
┌───────────────────────────────┐   ┌───────────────────────────────────────┐
│  Check: WAL generation rate   │   │  Fix: Network issues                  │
│  - Is primary under heavy     │   │  - Increase bandwidth                 │
│    write load?                │   │  - Check firewall rules               │
│  - Large transactions?        │   │  - Review network latency             │
└───────────────────────────────┘   └───────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Check: Standby performance                                             │
│  - High CPU on standby?                                                 │
│  - Disk I/O saturated?                                                  │
│  - Queries on standby blocking replay?                                  │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                    ┌───────────────┴───────────────┐
                    │                               │
                NORMAL                           ISSUE
                    │                               │
                    ▼                               ▼
┌───────────────────────────────┐   ┌───────────────────────────────────────┐
│  Monitor and wait             │   │  Actions:                             │
│  Lag should resolve           │   │  1. Reduce standby query load         │
│  when load decreases          │   │  2. Scale up standby resources        │
│                               │   │  3. Consider synchronous_commit       │
│                               │   │    settings review                    │
└───────────────────────────────┘   └───────────────────────────────────────┘
```

#### 8.3.2 Replication Diagnostics

```sql
-- Detailed replication status
SELECT 
    client_addr,
    state,
    sent_lsn,
    write_lsn,
    flush_lsn,
    replay_lsn,
    pg_size_pretty(pg_wal_lsn_diff(sent_lsn, replay_lsn)) AS replication_lag_bytes,
    extract(epoch from write_lag) AS write_lag_sec,
    extract(epoch from flush_lag) AS flush_lag_sec,
    extract(epoch from replay_lag) AS replay_lag_sec,
    sync_state
FROM pg_stat_replication;

-- WAL files on primary
SELECT 
    count(*) AS wal_files,
    pg_size_pretty(count(*) * 16 * 1024 * 1024) AS total_size
FROM pg_ls_waldir()
WHERE name ~ '^[0-9A-F]{24}$';

-- Check replication slot status
SELECT 
    slot_name,
    plugin,
    slot_type,
    database,
    active,
    restart_lsn,
    confirmed_flush_lsn,
    pg_size_pretty(pg_wal_lsn_diff(pg_current_wal_lsn(), restart_lsn)) AS lag
FROM pg_replication_slots;
```

---

## 9. Security

### 9.1 Access Control

#### 9.1.1 Authentication Methods

| Method | Use Case | Security Level |
|--------|----------|----------------|
| scram-sha-256 | Local/remote users | High |
| md5 | Legacy compatibility | Medium |
| peer | Local OS authentication | High (local only) |
| ident | Trusted network | Low-Medium |
| ldap | Enterprise integration | High |
| certificate | High-security environments | Very High |

#### 9.1.2 Access Control Best Practices

```sql
-- Remove unnecessary privileges from PUBLIC
REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON ALL TABLES IN SCHEMA public FROM PUBLIC;

-- Create dedicated schema for application data
CREATE SCHEMA app_data AUTHORIZATION app_admin;

-- Use row-level security for multi-tenant data
CREATE POLICY tenant_isolation ON sensor_readings
    USING (tenant_id = current_setting('app.current_tenant')::int);

ALTER TABLE sensor_readings ENABLE ROW LEVEL SECURITY;
```

### 9.2 Encryption

#### 9.2.1 Data at Rest

```bash
# Enable SSL in postgresql.conf
ssl = on
ssl_cert_file = '/etc/ssl/certs/server.crt'
ssl_key_file = '/etc/ssl/private/server.key'
ssl_ca_file = '/etc/ssl/certs/ca.crt'
ssl_crl_file = ''

# Force SSL for specific users (pg_hba.conf)
hostssl all             all             0.0.0.0/0               scram-sha-256
```

#### 9.2.2 Column-Level Encryption

```sql
-- Using pgcrypto for sensitive data
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Encrypt sensitive column
ALTER TABLE users ADD COLUMN ssn_encrypted bytea;

-- Encrypt data
UPDATE users 
SET ssn_encrypted = pgp_sym_encrypt(ssn, current_setting('app.encryption_key'))
WHERE ssn IS NOT NULL;

-- Decrypt for authorized access
CREATE FUNCTION get_ssn(user_id int) RETURNS text AS $$
BEGIN
    -- Check if user has permission
    IF NOT has_permission(current_user, 'view_ssn') THEN
        RAISE EXCEPTION 'Permission denied';
    END IF;
    
    RETURN pgp_sym_decrypt(ssn_encrypted, current_setting('app.encryption_key'))
    FROM users WHERE id = user_id;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;
```

### 9.3 Audit Logging

#### 9.3.1 pgaudit Configuration

```ini
# postgresql.conf
shared_preload_libraries = 'pg_stat_statements,timescaledb,pgaudit'

# pgaudit settings
pgaudit.log = 'write, ddl'
pgaudit.log_catalog = off
pgaudit.log_parameter = on
pgaudit.log_relation = on
pgaudit.log_statement_once = off
pgaudit.role = 'auditor'
```

#### 9.3.2 Audit Query Examples

```sql
-- View audit logs (when using pgaudit)
-- Logs are written to PostgreSQL log file with AUDIT prefix

-- Custom audit table for critical operations
CREATE TABLE audit_log (
    id SERIAL PRIMARY KEY,
    event_time TIMESTAMPTZ DEFAULT now(),
    user_name TEXT,
    action TEXT,
    table_name TEXT,
    old_data JSONB,
    new_data JSONB,
    ip_address INET
);

-- Audit trigger function
CREATE OR REPLACE FUNCTION audit_trigger()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        INSERT INTO audit_log (user_name, action, table_name, old_data)
        VALUES (current_user, TG_OP, TG_TABLE_NAME, to_jsonb(OLD));
        RETURN OLD;
    ELSIF TG_OP = 'UPDATE' THEN
        INSERT INTO audit_log (user_name, action, table_name, old_data, new_data)
        VALUES (current_user, TG_OP, TG_TABLE_NAME, to_jsonb(OLD), to_jsonb(NEW));
        RETURN NEW;
    ELSIF TG_OP = 'INSERT' THEN
        INSERT INTO audit_log (user_name, action, table_name, new_data)
        VALUES (current_user, TG_OP, TG_TABLE_NAME, to_jsonb(NEW));
        RETURN NEW;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;
```

---

## 10. Maintenance Windows

### 10.1 Scheduled Maintenance Calendar

| Window | Frequency | Duration | Activities |
|--------|-----------|----------|------------|
| Sundays 02:00-06:00 | Weekly | 4 hours | Index rebuild, statistics update |
| First Sunday 00:00-04:00 | Monthly | 4 hours | Deep vacuum, configuration changes |
| Last Saturday 22:00-02:00 | Quarterly | 4 hours | Major upgrades, DR testing |

### 10.2 Maintenance Procedures

#### 10.2.1 Pre-Maintenance Checklist

```markdown
□ Notify stakeholders (24 hours in advance)
□ Verify backup completion
□ Check replication lag (< 1 second)
□ Confirm standby is current
□ Prepare rollback plan
□ Update monitoring dashboards (maintenance mode)
□ Document current configuration
```

#### 10.2.2 Post-Maintenance Verification

```sql
-- Post-maintenance health check
SELECT 
    'Connectivity' AS check_item,
    CASE WHEN count(*) > 0 THEN 'PASS' ELSE 'FAIL' END AS status
FROM pg_stat_activity
UNION ALL
SELECT 
    'Replication' AS check_item,
    CASE WHEN count(*) > 0 THEN 'PASS' ELSE 'FAIL' END AS status
FROM pg_stat_replication
WHERE replay_lag < interval '5 seconds'
UNION ALL
SELECT 
    'Standby Active' AS check_item,
    CASE WHEN pg_is_in_recovery() THEN 'PASS (standby)' ELSE 'PASS (primary)' END;
```

### 10.3 Communication Template

```
Subject: [MAINTENANCE] Smart Dairy Database - Scheduled Maintenance

Maintenance Window: [DATE] [START_TIME] - [END_TIME] [TIMEZONE]
Duration: [X] hours
Impact: [Brief description of impact]
Affected Systems: [List of affected services]

Activities:
- [Activity 1]
- [Activity 2]

User Actions Required:
- Save work before [START_TIME]
- [Any other required actions]

Rollback Plan:
[Description of rollback procedure if needed]

Contact: dba@smartdairy.com for urgent issues
```

---

## 11. Emergency Procedures

### 11.1 Incident Severity Levels

| Level | Description | Response Time | Escalation |
|-------|-------------|---------------|------------|
| P1 - Critical | Database down, data corruption | 15 minutes | Immediate - IT Director |
| P2 - High | Severe performance degradation, replication broken | 30 minutes | IT Director within 1 hour |
| P3 - Medium | Minor performance issues, non-critical errors | 2 hours | Next business day |
| P4 - Low | Questions, feature requests | 1 business day | None |

### 11.2 Database Failover Procedure

#### 11.2.1 Automatic Failover (Patroni)

```bash
# Check cluster status
patronictl -c /etc/patroni/patroni.yml list

# Manual failover (if needed)
patronictl -c /etc/patroni/patroni.yml failover smart-dairy-cluster

# Switchover (planned maintenance)
patronictl -c /etc/patroni/patroni.yml switchover smart-dairy-cluster
```

#### 11.2.2 Manual Failover (Without Patroni)

```bash
#!/bin/bash
# manual_failover.sh

# Step 1: Stop primary if it's still running (split-brain prevention)
ssh db-primary "sudo systemctl stop postgresql"

# Step 2: Promote standby
ssh db-standby "su - postgres -c '/usr/lib/postgresql/16/bin/pg_ctl promote -D /var/lib/postgresql/16/main'"

# Step 3: Update PgBouncer to point to new primary
# Edit pgbouncer.ini and reload
ssh pgbouncer "sudo systemctl reload pgbouncer"

# Step 4: Verify new primary
psql -h db-standby -c "SELECT pg_is_in_recovery();"  # Should return 'f'
```

### 11.3 Data Recovery Procedures

#### 11.3.1 Point-in-Time Recovery (PITR)

```bash
#!/bin/bash
# pitr_recovery.sh

RECOVERY_TIME="2026-01-31 14:30:00"
BACKUP_DIR="/backup/base"
ARCHIVE_DIR="/backup/archive"
DATA_DIR="/var/lib/postgresql/16/main"

# Step 1: Stop PostgreSQL
sudo systemctl stop postgresql

# Step 2: Clean data directory
rm -rf $DATA_DIR/*

# Step 3: Restore base backup
tar -xzf $BACKUP_DIR/base_backup_latest.tar.gz -C $DATA_DIR

# Step 4: Create recovery configuration
cat > $DATA_DIR/postgresql.auto.conf << EOF
restore_command = 'cp $ARCHIVE_DIR/%f %p'
recovery_target_time = '$RECOVERY_TIME'
recovery_target_action = 'promote'
EOF

touch $DATA_DIR/recovery.signal

# Step 5: Start PostgreSQL
sudo systemctl start postgresql

# Monitor recovery progress
tail -f /var/log/postgresql/postgresql-*.log
```

#### 11.3.2 Table-Level Recovery

```sql
-- Using pg_dump for specific table recovery
pg_dump -h db-primary -t schema.table_name smart_dairy > table_backup.sql

-- Restore to different schema for verification
CREATE SCHEMA recovery_temp;
psql -c "SET search_path = recovery_temp;" -f table_backup.sql

-- After verification, merge data
INSERT INTO original_schema.table_name 
SELECT * FROM recovery_temp.table_name 
WHERE condition;

-- Clean up
DROP SCHEMA recovery_temp CASCADE;
```

### 11.4 Emergency Contacts

| Role | Name | Phone | Email | Escalation Time |
|------|------|-------|-------|-----------------|
| Primary DBA | [Name] | [Phone] | dba@smartdairy.com | - |
| Secondary DBA | [Name] | [Phone] | dba2@smartdairy.com | 15 min |
| IT Director | [Name] | [Phone] | it.director@smartdairy.com | 30 min |
| CTO | [Name] | [Phone] | cto@smartdairy.com | 1 hour |
| Vendor Support | PostgreSQL Pro | +1-xxx-xxx-xxxx | support@postgresql.pro | - |

---

## 12. Appendices

### Appendix A: Daily Checklist Script

```bash
#!/bin/bash
# daily_checklist.sh

# Configuration
DB_NAME="smart_dairy"
LOG_DIR="/var/log/postgresql/daily_checks"
ALERT_EMAIL="dba@smartdairy.com"

mkdir -p $LOG_DIR
DATE=$(date +%Y%m%d)
LOG_FILE="$LOG_DIR/daily_check_$DATE.log"

echo "=========================================" >> $LOG_FILE
echo "Daily Database Check - $(date)" >> $LOG_FILE
echo "=========================================" >> $LOG_FILE

# Check 1: Database Connectivity
echo "[1/8] Checking database connectivity..." >> $LOG_FILE
if psql -d $DB_NAME -c "SELECT 1;" > /dev/null 2>&1; then
    echo "  ✓ Database connectivity: OK" >> $LOG_FILE
else
    echo "  ✗ Database connectivity: FAILED" >> $LOG_FILE
    echo "ALERT: Database connectivity issue" | mail -s "CRITICAL: DB Connectivity" $ALERT_EMAIL
fi

# Check 2: Replication Lag
echo "[2/8] Checking replication lag..." >> $LOG_FILE
LAG=$(psql -d $DB_NAME -t -c "SELECT extract(epoch from replay_lag)::int FROM pg_stat_replication LIMIT 1;" 2>/dev/null | xargs)
if [ -z "$LAG" ] || [ "$LAG" = "" ]; then
    echo "  ✗ Replication: No active replication" >> $LOG_FILE
elif [ "$LAG" -lt 5 ]; then
    echo "  ✓ Replication lag: ${LAG}s (OK)" >> $LOG_FILE
else
    echo "  ⚠ Replication lag: ${LAG}s (Warning)" >> $LOG_FILE
fi

# Check 3: Disk Space
echo "[3/8] Checking disk space..." >> $LOG_FILE
DISK_USAGE=$(df /var/lib/postgresql | tail -1 | awk '{print $5}' | sed 's/%//')
if [ "$DISK_USAGE" -lt 80 ]; then
    echo "  ✓ Disk usage: ${DISK_USAGE}% (OK)" >> $LOG_FILE
elif [ "$DISK_USAGE" -lt 90 ]; then
    echo "  ⚠ Disk usage: ${DISK_USAGE}% (Warning)" >> $LOG_FILE
else
    echo "  ✗ Disk usage: ${DISK_USAGE}% (CRITICAL)" >> $LOG_FILE
    echo "ALERT: Disk space critical" | mail -s "CRITICAL: Disk Space" $ALERT_EMAIL
fi

# Check 4: Connection Count
echo "[4/8] Checking connection count..." >> $LOG_FILE
CONN_COUNT=$(psql -d $DB_NAME -t -c "SELECT count(*) FROM pg_stat_activity;" | xargs)
MAX_CONN=$(psql -d $DB_NAME -t -c "SELECT setting::int FROM pg_settings WHERE name = 'max_connections';" | xargs)
CONN_PCT=$((CONN_COUNT * 100 / MAX_CONN))
if [ "$CONN_PCT" -lt 80 ]; then
    echo "  ✓ Connections: $CONN_COUNT/$MAX_CONN (${CONN_PCT}%)" >> $LOG_FILE
else
    echo "  ⚠ Connections: $CONN_COUNT/$MAX_CONN (${CONN_PCT}%) - High" >> $LOG_FILE
fi

# Check 5: Cache Hit Ratio
echo "[5/8] Checking cache hit ratio..." >> $LOG_FILE
CACHE_HIT=$(psql -d $DB_NAME -t -c "SELECT round(blks_hit * 100.0 / nullif(blks_hit + blks_read, 0), 2) FROM pg_stat_database WHERE datname = '$DB_NAME';" | xargs)
if (( $(echo "$CACHE_HIT > 95" | bc -l) )); then
    echo "  ✓ Cache hit ratio: ${CACHE_HIT}%" >> $LOG_FILE
else
    echo "  ⚠ Cache hit ratio: ${CACHE_HIT}% (Low)" >> $LOG_FILE
fi

# Check 6: Dead Tuples
echo "[6/8] Checking dead tuples..." >> $LOG_FILE
DEAD_TUP=$(psql -d $DB_NAME -t -c "SELECT sum(n_dead_tup) FROM pg_stat_user_tables;" | xargs)
echo "  Total dead tuples: $DEAD_TUP" >> $LOG_FILE

# Check 7: Lock Waits
echo "[7/8] Checking lock waits..." >> $LOG_FILE
LOCK_WAITS=$(psql -d $DB_NAME -t -c "SELECT count(*) FROM pg_locks WHERE NOT granted;" | xargs)
if [ "$LOCK_WAITS" -eq 0 ]; then
    echo "  ✓ No waiting locks" >> $LOG_FILE
else
    echo "  ⚠ Waiting locks: $LOCK_WAITS" >> $LOG_FILE
fi

# Check 8: Error Log Review
echo "[8/8] Checking error logs..." >> $LOG_FILE
ERRORS=$(grep -c "ERROR\|FATAL\|PANIC" /var/log/postgresql/postgresql-$DATE*.log 2>/dev/null || echo "0")
echo "  Errors/Fatal/Panic in logs: $ERRORS" >> $LOG_FILE

echo "" >> $LOG_FILE
echo "Check completed at $(date)" >> $LOG_FILE
echo "=========================================" >> $LOG_FILE

# Send summary
tail -30 $LOG_FILE | mail -s "Daily DB Check Summary - $DATE" $ALERT_EMAIL
```

### Appendix B: Monitoring Queries Reference

```sql
-- B.1 Top 10 Tables by Size
SELECT 
    schemaname || '.' || relname AS table_name,
    pg_size_pretty(pg_total_relation_size(relid)) AS total_size,
    n_live_tup AS row_count
FROM pg_stat_user_tables
ORDER BY pg_total_relation_size(relid) DESC
LIMIT 10;

-- B.2 Index Usage Statistics
SELECT 
    schemaname,
    relname AS table_name,
    indexrelname AS index_name,
    idx_scan AS times_used,
    pg_size_pretty(pg_relation_size(indexrelid)) AS index_size
FROM pg_stat_user_indexes
ORDER BY idx_scan DESC
LIMIT 20;

-- B.3 Long-Running Queries
SELECT 
    pid,
    now() - query_start AS duration,
    usename,
    state,
    left(query, 200) AS query_preview
FROM pg_stat_activity
WHERE state = 'active'
    AND now() - query_start > interval '1 minute'
ORDER BY duration DESC;

-- B.4 Table Bloat Estimate
SELECT 
    schemaname,
    relname,
    n_dead_tup,
    n_live_tup,
    round(n_dead_tup * 100.0 / nullif(n_live_tup, 0), 2) AS dead_pct
FROM pg_stat_user_tables
WHERE n_dead_tup > 1000
ORDER BY n_dead_tup DESC
LIMIT 10;

-- B.5 Statement Statistics Summary
SELECT 
    substring(query, 1, 50) AS query_type,
    count(*) AS calls,
    round(sum(total_exec_time)::numeric, 2) AS total_time,
    round(avg(mean_exec_time)::numeric, 4) AS avg_time
FROM pg_stat_statements
GROUP BY substring(query, 1, 50)
ORDER BY sum(total_exec_time) DESC
LIMIT 10;
```

### Appendix C: Troubleshooting Runbooks

#### C.1 Database Won't Start

```markdown
## Symptom
PostgreSQL service fails to start

## Diagnosis Steps
1. Check logs: `journalctl -u postgresql -n 100`
2. Verify data directory permissions
3. Check for lock files: `ls -la $PGDATA/*.lock`
4. Verify port availability: `netstat -tlnp | grep 5432`

## Common Causes & Solutions

### Permission Issues
```bash
sudo chown -R postgres:postgres /var/lib/postgresql/16/main
sudo chmod 700 /var/lib/postgresql/16/main
```

### Corrupted Configuration
```bash
# Restore from backup
cp /etc/postgresql/16/main/postgresql.conf.backup /etc/postgresql/16/main/postgresql.conf
sudo systemctl restart postgresql
```

### Disk Full
```bash
# Check disk usage
df -h
# Free up WAL files if archived
pg_archivecleanup /var/lib/postgresql/archive $(pg_controldata | grep "Latest checkpoint's REDO WAL file" | awk '{print $6}')
```
```

#### C.2 High CPU Usage

```markdown
## Symptom
PostgreSQL consuming high CPU

## Diagnosis Queries
```sql
-- Find top CPU-consuming queries
SELECT pid, usename, application_name, 
       now() - query_start AS duration,
       state, left(query, 100) AS query
FROM pg_stat_activity
WHERE state = 'active'
ORDER BY now() - query_start DESC;
```

## Solutions
1. Terminate runaway queries:
   ```sql
   SELECT pg_terminate_backend(<pid>);
   ```

2. Analyze and vacuum affected tables:
   ```sql
   VACUUM ANALYZE table_name;
   ```

3. Check for missing indexes:
   ```sql
   -- See Appendix B.1
   ```
```

#### C.3 Memory Issues

```markdown
## Symptom
Out of memory errors, excessive swapping

## Diagnosis
```sql
-- Check shared memory usage
SELECT 
    name, 
    setting, 
    unit,
    pg_size_pretty(setting::bigint * 8192) AS memory_size
FROM pg_settings 
WHERE name IN ('shared_buffers', 'work_mem', 'maintenance_work_mem', 'effective_cache_size');

-- Check connection count
SELECT count(*) FROM pg_stat_activity;
```

## Solutions
1. Reduce work_mem if too high
2. Implement connection pooling (PgBouncer)
3. Increase system RAM or add swap
4. Adjust kernel parameters:
   ```bash
   vm.overcommit_memory = 2
   vm.overcommit_ratio = 80
   ```
```

### Appendix D: Maintenance Scripts

#### D.1 Weekly Maintenance Script

```bash
#!/bin/bash
# weekly_maintenance.sh

DB_NAME="smart_dairy"
LOG_FILE="/var/log/postgresql/weekly_maintenance_$(date +%Y%m%d).log"

exec > >(tee -a "$LOG_FILE")
exec 2>&1

echo "Starting weekly maintenance at $(date)"

# 1. Analyze all tables
echo "[1/5] Running ANALYZE..."
psql -d $DB_NAME -c "DO \$\$
DECLARE r RECORD;
BEGIN
    FOR r IN SELECT schemaname, relname FROM pg_stat_user_tables 
             WHERE schemaname NOT IN ('pg_catalog', 'information_schema')
    LOOP
        EXECUTE format('ANALYZE %I.%I', r.schemaname, r.relname);
    END LOOP;
END \$\$;"

# 2. Reindex concurrently (top 5 largest indexes)
echo "[2/5] Reindexing large indexes..."
psql -d $DB_NAME -c "
DO \$\$
DECLARE r RECORD;
BEGIN
    FOR r IN 
        SELECT schemaname, indexrelname 
        FROM pg_stat_user_indexes 
        WHERE pg_relation_size(indexrelid) > 100000000
        ORDER BY pg_relation_size(indexrelid) DESC
        LIMIT 5
    LOOP
        EXECUTE format('REINDEX INDEX CONCURRENTLY %I.%I', 
                      r.schemaname, r.indexrelname);
    END LOOP;
END \$\$;"

# 3. Vacuum small tables
echo "[3/5] Vacuuming small tables..."
psql -d $DB_NAME -c "
DO \$\$
DECLARE r RECORD;
BEGIN
    FOR r IN 
        SELECT schemaname, relname 
        FROM pg_stat_user_tables 
        WHERE pg_relation_size(relid) < 100000000
          AND n_dead_tup > 1000
    LOOP
        EXECUTE format('VACUUM ANALYZE %I.%I', r.schemaname, r.relname);
    END LOOP;
END \$\$;"

# 4. Reset statistics
echo "[4/5] Resetting statistics..."
psql -d $DB_NAME -c "SELECT pg_stat_reset();"

# 5. Log summary
echo "[5/5] Generating summary..."
psql -d $DB_NAME -c "
SELECT 
    schemaname || '.' || relname AS table_name,
    pg_size_pretty(pg_total_relation_size(relid)) AS size,
    n_live_tup,
    n_dead_tup,
    last_vacuum,
    last_analyze
FROM pg_stat_user_tables
ORDER BY pg_total_relation_size(relid) DESC
LIMIT 10;"

echo "Weekly maintenance completed at $(date)"
```

#### D.2 Backup Verification Script

```bash
#!/bin/bash
# backup_verify.sh

BACKUP_DIR="/backup/latest"
TEST_RESTORE_DIR="/tmp/restore_test"
DATE=$(date +%Y%m%d)

# Clean up test directory
rm -rf $TEST_RESTORE_DIR
mkdir -p $TEST_RESTORE_DIR

# Extract backup
echo "Extracting backup..."
tar -xzf $BACKUP_DIR/base_backup_$DATE.tar.gz -C $TEST_RESTORE_DIR

# Verify PostgreSQL can start with this data
echo "Testing data integrity..."
/usr/lib/postgresql/16/bin/pg_ctl -D $TEST_RESTORE_DIR start -l $TEST_RESTORE_DIR/logfile

# Run pg_dump to verify logical integrity
echo "Creating test dump..."
/usr/lib/postgresql/16/bin/pg_dumpall -d "host=localhost port=5433 dbname=postgres" > /tmp/test_dump.sql 2>/dev/null

# Cleanup
/usr/lib/postgresql/16/bin/pg_ctl -D $TEST_RESTORE_DIR stop
rm -rf $TEST_RESTORE_DIR

# Check dump size
DUMP_SIZE=$(stat -c%s /tmp/test_dump.sql 2>/dev/null || echo "0")
if [ "$DUMP_SIZE" -gt 1000 ]; then
    echo "Backup verification: PASSED"
    exit 0
else
    echo "Backup verification: FAILED"
    exit 1
fi
```

### Appendix E: Configuration Reference

#### E.1 postgresql.conf Quick Reference

| Parameter | Production | Development | Description |
|-----------|------------|-------------|-------------|
| max_connections | 200 | 100 | Maximum concurrent connections |
| shared_buffers | 8GB | 2GB | Shared memory for caching |
| effective_cache_size | 24GB | 6GB | Estimated OS cache size |
| work_mem | 64MB | 16MB | Memory per query operation |
| maintenance_work_mem | 2GB | 512MB | Memory for maintenance ops |
| wal_buffers | 16MB | 4MB | WAL buffer size |
| max_wal_size | 4GB | 1GB | Maximum WAL size before checkpoint |
| checkpoint_completion_target | 0.9 | 0.9 | Checkpoint spread target |
| random_page_cost | 1.1 | 4.0 | Cost of random page fetch |
| effective_io_concurrency | 200 | 1 | Concurrent disk I/O operations |
| log_min_duration_statement | 1000 | 0 | Log queries > this duration (ms) |

#### E.2 pg_hba.conf Templates

```
# Template: Secure Internal Network
# TYPE  DATABASE        USER            ADDRESS                 METHOD
hostssl all             all             10.0.0.0/8              scram-sha-256

# Template: Application Servers Only
# TYPE  DATABASE        USER            ADDRESS                 METHOD
host    smart_dairy     app_user        10.0.1.0/24             scram-sha-256
host    smart_dairy     readonly_user   10.0.2.0/24             scram-sha-256

# Template: Administrative Access
# TYPE  DATABASE        USER            ADDRESS                 METHOD
local   all             postgres                                peer
hostssl all             dba             10.0.0.0/8              scram-sha-256
```

---

## Document Control

### Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | DBA | | January 31, 2026 |
| Reviewer | IT Director | | |
| Approver | CTO | | |

### Distribution List

| Name | Role | Location |
|------|------|----------|
| DBA Team | Operations | IT Operations Center |
| IT Director | Management | Head Office |
| DevOps Team | Engineering | Remote |
| Security Officer | Compliance | Head Office |

### Related Documents

| Document ID | Title | Relationship |
|-------------|-------|--------------|
| L-001 | System Architecture Guide | Reference |
| L-002 | Security Policies | Reference |
| L-003 | Backup and Recovery Procedures | Reference |
| L-010 | Incident Response Plan | Reference |

### Glossary

| Term | Definition |
|------|------------|
| DBA | Database Administrator |
| HA | High Availability |
| PITR | Point-in-Time Recovery |
| VACUUM | PostgreSQL storage reclamation process |
| WAL | Write-Ahead Logging |
| LSN | Log Sequence Number |
| PgBouncer | Lightweight connection pooler for PostgreSQL |
| TimescaleDB | Time-series database extension for PostgreSQL |
| Hypertable | TimescaleDB abstraction for partitioned time-series tables |
| Chunk | Partition of a hypertable |

---

*End of Document*

---

**Document Classification:** Internal Use Only  
**Next Review Date:** July 31, 2026  
**Retention Period:** 5 years from date of supersession
