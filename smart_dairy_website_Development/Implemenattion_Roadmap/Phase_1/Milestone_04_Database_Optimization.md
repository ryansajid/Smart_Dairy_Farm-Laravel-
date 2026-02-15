# Milestone 4: Database Performance Optimization & Caching

## Smart Dairy Digital Smart Portal + ERP — Phase 1: Foundation

| Field | Detail |
|---|---|
| **Milestone** | 4 of 10 |
| **Title** | Database Performance Optimization & Caching |
| **Phase** | Phase 1 — Foundation (Part A: Infrastructure & Core Setup) |
| **Days** | Days 31–40 (of 100) |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03 |

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

### 1.1 Purpose

Milestone 4 takes the database schemas and core models established in Milestones 1–3 and applies rigorous performance engineering. Smart Dairy's PostgreSQL 16 instance will be tuned for the operational reality of 255 cattle, 75 lactating cows producing approximately 900 litres of milk per day, and the resulting transactional volume across eight schemas (`core`, `farm`, `sales`, `inventory`, `accounting`, `analytics`, `audit`, `iot`). Redis 7 is introduced as the caching layer. Backup, recovery, security hardening, connection pooling, monitoring, and load testing round out the milestone.

### 1.2 Success Criteria

| Criterion | Target |
|---|---|
| Average query response time (95th percentile) | < 50 ms |
| Cache hit ratio (Redis) | > 85 % after warm-up |
| Backup RPO (Recovery Point Objective) | < 15 minutes (WAL archiving) |
| Backup RTO (Recovery Time Objective) | < 30 minutes (PITR) |
| Connection pool utilisation under load | < 70 % at 50 concurrent users |
| Zero data-loss failover drill | Pass |
| K6 load test — sustained 50 VUs for 5 min | All endpoints < 200 ms p95 |
| Grafana dashboard operational | All panels green |

### 1.3 Scope

- PostgreSQL 16 configuration tuning for production workloads
- Indexing strategy across all eight schemas
- Redis 7 session cache, query result cache, ORM cache layer
- Automated backup with pg_dump, WAL archiving, PITR
- Database security hardening (row-level security, encrypted connections)
- pgBouncer connection pooling
- Prometheus + Grafana monitoring stack for database metrics
- K6 load testing and capacity planning
- ETL scripts for future legacy data migration
- OWL-based database monitoring dashboard component
- Runbooks and knowledge transfer documentation

---

## 2. Requirement Traceability Matrix

| Req ID | Requirement | Source Document | Days Addressed | Deliverable |
|---|---|---|---|---|
| C-001 | Database Design — schema performance | Database Design Document | 31, 32, 36 | Tuned config, indexes, connection pooling |
| C-003 | Data Dictionary — field-level indexing | Data Dictionary | 32 | Index creation scripts |
| C-009 | Performance — sub-100 ms queries | Performance Requirements | 31, 32, 33, 37 | Baseline report, optimised queries, cache layer, load test results |
| C-010 | Backup & Recovery | Disaster Recovery Plan | 34 | Backup scripts, WAL config, PITR runbook |
| C-011 | Security — database hardening | Security Policy | 35 | RLS policies, pg_hba.conf, SSL |
| C-012 | Monitoring & Alerting | Operations Handbook | 36 | Prometheus exporter, Grafana dashboards |
| C-014 | Data Migration | Migration Plan | 38 | ETL scripts, validation queries |
| C-015 | Documentation | Project Standards | 39, 40 | Runbooks, knowledge transfer sessions |

---

## 3. Day-by-Day Breakdown

### Day 31 — Database Performance Baseline Analysis

**Objective:** Establish measurable baselines before any optimisation work begins.

#### Dev 1 — Backend Lead (~8 h)

| Hours | Task |
|---|---|
| 2 h | Enable and configure `pg_stat_statements` extension; set `pg_stat_statements.track = all` |
| 2 h | Run baseline `EXPLAIN ANALYZE` on the 20 most critical queries (milk production reports, animal lookups, breeding schedules, inventory aggregations) |
| 2 h | Capture `pg_stat_user_tables` and `pg_stat_user_indexes` snapshots; document seq-scan vs index-scan ratios |
| 2 h | Configure slow query logging (`log_min_duration_statement = 100`) and collect initial slow query inventory |

```sql
-- Enable pg_stat_statements
CREATE EXTENSION IF NOT EXISTS pg_stat_statements;

-- Top 20 slowest queries by mean execution time
SELECT
    queryid,
    LEFT(query, 120)                          AS query_preview,
    calls,
    round(total_exec_time::numeric, 2)        AS total_ms,
    round(mean_exec_time::numeric, 2)         AS mean_ms,
    round(stddev_exec_time::numeric, 2)       AS stddev_ms,
    rows,
    round((shared_blks_hit * 100.0 /
        NULLIF(shared_blks_hit + shared_blks_read, 0))::numeric, 2)
                                              AS cache_hit_pct
FROM pg_stat_statements
ORDER BY mean_exec_time DESC
LIMIT 20;

-- Table-level sequential scan analysis
SELECT
    schemaname,
    relname,
    seq_scan,
    seq_tup_read,
    idx_scan,
    idx_tup_fetch,
    CASE WHEN (seq_scan + idx_scan) > 0
         THEN round(100.0 * idx_scan / (seq_scan + idx_scan), 2)
         ELSE 0
    END AS idx_scan_pct
FROM pg_stat_user_tables
WHERE schemaname IN ('core','farm','sales','inventory','accounting','analytics','audit','iot')
ORDER BY seq_tup_read DESC;
```

#### Dev 2 — Full-Stack/DevOps (~8 h)

| Hours | Task |
|---|---|
| 3 h | Deploy PostgreSQL 16 production configuration file with baseline tuning |
| 3 h | Set up log rotation for PostgreSQL slow query logs (`logrotate.d/postgresql`) |
| 2 h | Prepare Docker Compose service definitions for Redis 7, pgBouncer (stubs for Day 33/36) |

```ini
# postgresql.conf — Production Tuning for Smart Dairy
# Server: 8 CPU cores, 32 GB RAM, NVMe SSD

# --- Memory ---
shared_buffers              = 8GB           # 25% of RAM
effective_cache_size         = 24GB          # 75% of RAM
work_mem                     = 64MB          # per-sort/hash operation
maintenance_work_mem         = 1GB           # VACUUM, CREATE INDEX
wal_buffers                  = 64MB          # 1/8 of shared_buffers cap

# --- Connections ---
max_connections              = 200
superuser_reserved_connections = 3

# --- WAL ---
wal_level                    = replica
max_wal_size                 = 2GB
min_wal_size                 = 512MB
checkpoint_completion_target = 0.9
archive_mode                 = on
archive_command              = 'cp %p /var/lib/postgresql/wal_archive/%f'

# --- Query Planner ---
random_page_cost             = 1.1          # SSD storage
effective_io_concurrency     = 200          # SSD concurrent I/O
default_statistics_target    = 200

# --- Logging ---
log_min_duration_statement   = 100          # ms — log queries slower than 100 ms
log_checkpoints              = on
log_connections              = on
log_disconnections           = on
log_lock_waits               = on
log_temp_files               = 0

# --- Autovacuum ---
autovacuum_max_workers       = 4
autovacuum_naptime           = 30s
autovacuum_vacuum_cost_delay = 2ms

# --- Extensions ---
shared_preload_libraries     = 'pg_stat_statements,auto_explain'
pg_stat_statements.track     = all
auto_explain.log_min_duration = 200
```

#### Dev 3 — Frontend/Mobile Lead (~8 h)

| Hours | Task |
|---|---|
| 3 h | Research OWL component patterns for real-time dashboard display (WebSocket vs polling) |
| 3 h | Wireframe the Database Monitor dashboard: query latency chart, cache hit ratio gauge, active connections bar |
| 2 h | Set up Chart.js / ECharts integration scaffolding in OWL for later use on Day 36 |

#### End-of-Day 31 Deliverables

- [ ] `pg_stat_statements` enabled and baseline snapshot saved to `docs/baselines/day31_pg_stats.csv`
- [ ] Slow query inventory document with `EXPLAIN ANALYZE` outputs
- [ ] Production `postgresql.conf` committed to `deploy/postgres/postgresql.conf`
- [ ] Dashboard wireframes in Figma/sketch committed to `docs/wireframes/db_monitor_v1.png`

---

### Day 32 — Indexing Strategy

**Objective:** Create targeted indexes to eliminate sequential scans on high-traffic tables.

#### Dev 1 — Backend Lead (~8 h)

| Hours | Task |
|---|---|
| 3 h | Design and create B-Tree indexes for primary lookup patterns |
| 2 h | Design and create GIN indexes for JSONB and full-text search columns |
| 2 h | Design partial and composite indexes for filtered queries |
| 1 h | Re-run baseline queries with `EXPLAIN ANALYZE` and compare |

```sql
-- ============================================================
-- B-Tree Indexes — Primary Key and Foreign Key Lookups
-- ============================================================

-- farm.animal: lookup by status (most queries filter on active animals)
CREATE INDEX CONCURRENTLY idx_farm_animal_status
    ON farm.animal (status);

-- farm.animal: lookup by tag number (unique collar/ear tag)
CREATE UNIQUE INDEX CONCURRENTLY idx_farm_animal_tag
    ON farm.animal (tag_number);

-- farm.milk_production: composite for daily production reports
CREATE INDEX CONCURRENTLY idx_farm_milk_prod_animal_date
    ON farm.milk_production (animal_id, production_date DESC);

-- farm.milk_production: date range scans for reporting
CREATE INDEX CONCURRENTLY idx_farm_milk_prod_date
    ON farm.milk_production (production_date DESC);

-- farm.health_record: animal health history lookups
CREATE INDEX CONCURRENTLY idx_farm_health_animal_date
    ON farm.health_record (animal_id, record_date DESC);

-- farm.breeding_record: breeding schedule queries
CREATE INDEX CONCURRENTLY idx_farm_breeding_animal_status
    ON farm.breeding_record (animal_id, breeding_status);

-- sales.order: customer order lookups
CREATE INDEX CONCURRENTLY idx_sales_order_customer_date
    ON sales."order" (customer_id, order_date DESC);

-- inventory.stock_move: product movement tracking
CREATE INDEX CONCURRENTLY idx_inventory_stock_product
    ON inventory.stock_move (product_id, move_date DESC);

-- ============================================================
-- GIN Indexes — JSONB and Full-Text Search
-- ============================================================

-- farm.animal: metadata JSONB field (vaccination records, custom attributes)
CREATE INDEX CONCURRENTLY idx_farm_animal_metadata_gin
    ON farm.animal USING GIN (metadata jsonb_path_ops);

-- farm.health_record: diagnosis details JSONB
CREATE INDEX CONCURRENTLY idx_farm_health_details_gin
    ON farm.health_record USING GIN (details jsonb_path_ops);

-- iot.sensor_reading: payload JSONB (temperature, humidity, weight)
CREATE INDEX CONCURRENTLY idx_iot_sensor_payload_gin
    ON iot.sensor_reading USING GIN (payload jsonb_path_ops);

-- Full-text search on animal names and notes
CREATE INDEX CONCURRENTLY idx_farm_animal_fts
    ON farm.animal USING GIN (
        to_tsvector('english', coalesce(name, '') || ' ' || coalesce(notes, ''))
    );

-- ============================================================
-- Partial Indexes — Active Records Only
-- ============================================================

-- Only active animals (avoids indexing archived/sold/deceased)
CREATE INDEX CONCURRENTLY idx_farm_animal_active
    ON farm.animal (id, name, tag_number)
    WHERE status = 'active';

-- Only open sales orders
CREATE INDEX CONCURRENTLY idx_sales_order_open
    ON sales."order" (id, customer_id, order_date)
    WHERE state IN ('draft', 'confirmed', 'processing');

-- Only pending health treatments
CREATE INDEX CONCURRENTLY idx_farm_health_pending
    ON farm.health_record (animal_id, scheduled_date)
    WHERE treatment_status = 'pending';

-- ============================================================
-- Composite Indexes — Common Multi-Column Queries
-- ============================================================

-- Daily milk production summary by date and shift
CREATE INDEX CONCURRENTLY idx_farm_milk_prod_date_shift
    ON farm.milk_production (production_date, shift, animal_id);

-- Accounting entries by period
CREATE INDEX CONCURRENTLY idx_accounting_entry_period
    ON accounting.journal_entry (fiscal_year, period, entry_date DESC);
```

#### Dev 2 — Full-Stack/DevOps (~8 h)

| Hours | Task |
|---|---|
| 3 h | Write an index deployment migration script with rollback support |
| 3 h | Configure `auto_explain` for development environment to catch missing indexes |
| 2 h | Add index creation to CI pipeline — validate indexes exist in test database |

#### Dev 3 — Frontend/Mobile Lead (~8 h)

| Hours | Task |
|---|---|
| 4 h | Build OWL component skeleton for `DatabaseMonitor` widget |
| 4 h | Implement mock data layer for dashboard charts pending real Prometheus integration |

#### End-of-Day 32 Deliverables

- [ ] All index creation scripts in `deploy/postgres/indexes/`
- [ ] Before/after `EXPLAIN ANALYZE` comparison document
- [ ] Migration script with rollback in `migrations/004_indexes.py`
- [ ] OWL `DatabaseMonitor` component skeleton committed

---

### Day 33 — Redis 7 Caching Implementation

**Objective:** Introduce Redis 7 as the caching layer for sessions, query results, and ORM object cache.

#### Dev 1 — Backend Lead (~8 h)

| Hours | Task |
|---|---|
| 2 h | Implement Python cache decorator for Odoo service methods |
| 2 h | Implement session store backed by Redis |
| 2 h | Build ORM cache invalidation layer (model write/unlink hooks) |
| 2 h | Write unit tests for cache hit/miss/invalidation |

```python
# addons/smart_dairy_cache/utils/redis_client.py
"""Redis 7 client wrapper for Smart Dairy caching layer."""

import json
import functools
import hashlib
import logging
from typing import Any, Callable, Optional

import redis

_logger = logging.getLogger(__name__)

# ---------------------------------------------------------------------------
# Redis Connection Pool
# ---------------------------------------------------------------------------

_pool: Optional[redis.ConnectionPool] = None


def get_redis_pool() -> redis.ConnectionPool:
    """Return a shared Redis connection pool (singleton)."""
    global _pool
    if _pool is None:
        _pool = redis.ConnectionPool(
            host="redis",
            port=6379,
            db=0,
            max_connections=50,
            decode_responses=True,
            socket_connect_timeout=5,
            socket_timeout=5,
            retry_on_timeout=True,
        )
    return _pool


def get_redis() -> redis.Redis:
    """Return a Redis client from the shared pool."""
    return redis.Redis(connection_pool=get_redis_pool())


# ---------------------------------------------------------------------------
# Cache Decorator
# ---------------------------------------------------------------------------

def cache_result(prefix: str, ttl: int = 300, key_builder: Optional[Callable] = None):
    """Decorator that caches function results in Redis.

    Args:
        prefix: Cache key namespace (e.g. 'milk_production').
        ttl: Time-to-live in seconds (default 5 min).
        key_builder: Optional callable(args, kwargs) -> str for custom keys.
    """
    def decorator(func: Callable) -> Callable:
        @functools.wraps(func)
        def wrapper(*args, **kwargs) -> Any:
            r = get_redis()

            # Build cache key
            if key_builder:
                raw_key = key_builder(args, kwargs)
            else:
                raw_key = f"{args[1:]}:{sorted(kwargs.items())}"
            cache_key = f"sd:{prefix}:{hashlib.sha256(raw_key.encode()).hexdigest()}"

            # Try cache hit
            try:
                cached = r.get(cache_key)
                if cached is not None:
                    _logger.debug("Cache HIT: %s", cache_key)
                    return json.loads(cached)
            except redis.RedisError:
                _logger.warning("Redis read error, falling back to DB")

            # Cache miss — execute and store
            result = func(*args, **kwargs)
            try:
                r.setex(cache_key, ttl, json.dumps(result, default=str))
                _logger.debug("Cache SET: %s (ttl=%ds)", cache_key, ttl)
            except redis.RedisError:
                _logger.warning("Redis write error, result not cached")

            return result
        return wrapper
    return decorator


# ---------------------------------------------------------------------------
# Session Store
# ---------------------------------------------------------------------------

class RedisSessionStore:
    """HTTP session store backed by Redis."""

    PREFIX = "sd:session:"
    TTL = 86400  # 24 hours

    def __init__(self):
        self._redis = get_redis()

    def save(self, session_id: str, data: dict) -> None:
        key = f"{self.PREFIX}{session_id}"
        self._redis.setex(key, self.TTL, json.dumps(data, default=str))

    def load(self, session_id: str) -> Optional[dict]:
        key = f"{self.PREFIX}{session_id}"
        raw = self._redis.get(key)
        return json.loads(raw) if raw else None

    def delete(self, session_id: str) -> None:
        self._redis.delete(f"{self.PREFIX}{session_id}")

    def touch(self, session_id: str) -> None:
        """Refresh TTL on access."""
        self._redis.expire(f"{self.PREFIX}{session_id}", self.TTL)


# ---------------------------------------------------------------------------
# Cache Invalidation Helpers
# ---------------------------------------------------------------------------

def invalidate_prefix(prefix: str) -> int:
    """Delete all cache keys matching a prefix (e.g. after model write).

    Uses SCAN to avoid blocking Redis with KEYS command.
    Returns the number of keys deleted.
    """
    r = get_redis()
    pattern = f"sd:{prefix}:*"
    deleted = 0
    cursor = 0
    while True:
        cursor, keys = r.scan(cursor=cursor, match=pattern, count=200)
        if keys:
            deleted += r.delete(*keys)
        if cursor == 0:
            break
    _logger.info("Invalidated %d keys for prefix '%s'", deleted, prefix)
    return deleted
```

```python
# addons/smart_dairy_cache/models/cache_mixin.py
"""Odoo ORM cache mixin — auto-invalidates Redis on write/unlink."""

from odoo import models, api
from ..utils.redis_client import invalidate_prefix

import logging
_logger = logging.getLogger(__name__)


class CacheMixin(models.AbstractModel):
    _name = 'smart_dairy.cache.mixin'
    _description = 'Redis Cache Invalidation Mixin'

    # Override in concrete models, e.g. 'milk_production'
    _cache_prefix = None

    @api.model_create_multi
    def create(self, vals_list):
        records = super().create(vals_list)
        self._invalidate_cache()
        return records

    def write(self, vals):
        result = super().write(vals)
        self._invalidate_cache()
        return result

    def unlink(self):
        self._invalidate_cache()
        return super().unlink()

    def _invalidate_cache(self):
        prefix = self._cache_prefix or self._name.replace('.', '_')
        try:
            invalidate_prefix(prefix)
        except Exception:
            _logger.warning(
                "Failed to invalidate cache for %s", prefix, exc_info=True
            )
```

#### Dev 2 — Full-Stack/DevOps (~8 h)

| Hours | Task |
|---|---|
| 3 h | Deploy Redis 7 container with persistence (AOF + RDB), configure `redis.conf` |
| 3 h | Set up Redis Sentinel or simple health checks in Docker Compose |
| 2 h | Write Redis monitoring integration (Prometheus redis_exporter) |

#### Dev 3 — Frontend/Mobile Lead (~8 h)

| Hours | Task |
|---|---|
| 4 h | Add cache hit ratio gauge and latency sparkline to `DatabaseMonitor` OWL component |
| 4 h | Implement OWL service layer for fetching metrics from the monitoring API |

#### End-of-Day 33 Deliverables

- [ ] Redis 7 running in Docker with persistence
- [ ] `smart_dairy_cache` addon with decorator, session store, and mixin
- [ ] Unit tests passing for cache hit/miss/invalidation
- [ ] Redis Sentinel or health check configuration committed

---

### Day 34 — Database Backup & Recovery

**Objective:** Implement automated backup with WAL archiving and verify PITR per C-010.

#### Dev 1 — Backend Lead (~8 h)

| Hours | Task |
|---|---|
| 2 h | Write automated pg_dump backup script with retention policy |
| 3 h | Configure WAL archiving and test continuous archiving |
| 3 h | Write and verify PITR restore procedure; perform restore drill |

```bash
#!/usr/bin/env bash
# deploy/scripts/backup_database.sh
# Automated PostgreSQL backup for Smart Dairy
# Schedule via cron: 0 2 * * * /opt/smart_dairy/deploy/scripts/backup_database.sh

set -euo pipefail

# ---- Configuration ----
DB_NAME="smart_dairy"
DB_USER="odoo"
BACKUP_DIR="/var/backups/postgresql"
WAL_ARCHIVE="/var/lib/postgresql/wal_archive"
RETENTION_DAYS=30
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="${BACKUP_DIR}/${DB_NAME}_${TIMESTAMP}.sql.gz"
LOG_FILE="/var/log/smart_dairy/backup.log"

# ---- Ensure directories ----
mkdir -p "${BACKUP_DIR}" "${WAL_ARCHIVE}"

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $*" | tee -a "${LOG_FILE}"
}

# ---- Full Backup (pg_dump) ----
log "Starting full backup of ${DB_NAME}..."

pg_dump \
    --host=localhost \
    --port=5432 \
    --username="${DB_USER}" \
    --format=custom \
    --compress=6 \
    --verbose \
    --file="${BACKUP_FILE}" \
    "${DB_NAME}" 2>>"${LOG_FILE}"

BACKUP_SIZE=$(du -h "${BACKUP_FILE}" | cut -f1)
log "Backup complete: ${BACKUP_FILE} (${BACKUP_SIZE})"

# ---- Verify Backup Integrity ----
log "Verifying backup integrity..."
pg_restore --list "${BACKUP_FILE}" > /dev/null 2>&1
if [ $? -eq 0 ]; then
    log "Backup verification PASSED"
else
    log "ERROR: Backup verification FAILED"
    exit 1
fi

# ---- Cleanup Old Backups ----
log "Cleaning backups older than ${RETENTION_DAYS} days..."
DELETED=$(find "${BACKUP_DIR}" -name "${DB_NAME}_*.sql.gz" -mtime +${RETENTION_DAYS} -delete -print | wc -l)
log "Deleted ${DELETED} old backup(s)"

# ---- WAL Archive Cleanup ----
log "Cleaning WAL archives older than ${RETENTION_DAYS} days..."
find "${WAL_ARCHIVE}" -name "*.backup" -mtime +${RETENTION_DAYS} -delete
find "${WAL_ARCHIVE}" -name "0000*" -mtime +${RETENTION_DAYS} -delete

log "Backup procedure complete."
```

```bash
#!/usr/bin/env bash
# deploy/scripts/pitr_restore.sh
# Point-In-Time Recovery procedure for Smart Dairy
# Usage: ./pitr_restore.sh "2026-02-03 14:30:00"

set -euo pipefail

TARGET_TIME="${1:?Usage: $0 'YYYY-MM-DD HH:MM:SS'}"
PG_DATA="/var/lib/postgresql/16/main"
WAL_ARCHIVE="/var/lib/postgresql/wal_archive"
BACKUP_DIR="/var/backups/postgresql"
LATEST_BACKUP=$(ls -t "${BACKUP_DIR}"/smart_dairy_*.sql.gz | head -1)

echo "=== Smart Dairy PITR Restore ==="
echo "Target time : ${TARGET_TIME}"
echo "Base backup  : ${LATEST_BACKUP}"
echo "WAL archive  : ${WAL_ARCHIVE}"
echo ""
read -p "This will STOP PostgreSQL and REPLACE the data directory. Continue? [y/N] " confirm
[ "${confirm}" = "y" ] || exit 1

# 1. Stop PostgreSQL
echo "Stopping PostgreSQL..."
systemctl stop postgresql

# 2. Back up current data directory
echo "Backing up current data directory..."
mv "${PG_DATA}" "${PG_DATA}.old.$(date +%s)"

# 3. Restore base backup
echo "Restoring base backup..."
mkdir -p "${PG_DATA}"
pg_restore --dbname=smart_dairy --clean --if-exists "${LATEST_BACKUP}"

# 4. Create recovery configuration
cat > "${PG_DATA}/postgresql.auto.conf" <<EOF
restore_command = 'cp ${WAL_ARCHIVE}/%f %p'
recovery_target_time = '${TARGET_TIME}'
recovery_target_action = 'promote'
EOF

# 5. Create recovery signal file
touch "${PG_DATA}/recovery.signal"

# 6. Start PostgreSQL (enters recovery mode)
echo "Starting PostgreSQL in recovery mode..."
systemctl start postgresql

echo "PITR restore initiated. Monitor logs: journalctl -u postgresql -f"
echo "After recovery completes, verify data and remove recovery.signal."
```

#### Dev 2 — Full-Stack/DevOps (~8 h)

| Hours | Task |
|---|---|
| 3 h | Set up cron jobs for backup scripts, configure log rotation |
| 3 h | Integrate backup success/failure alerts into Slack/email notifications |
| 2 h | Document backup architecture diagram |

#### Dev 3 — Frontend/Mobile Lead (~8 h)

| Hours | Task |
|---|---|
| 4 h | Build backup status panel in admin dashboard (last backup time, size, status) |
| 4 h | Implement notification component for backup failures |

#### End-of-Day 34 Deliverables

- [ ] `backup_database.sh` and `pitr_restore.sh` in `deploy/scripts/`
- [ ] WAL archiving configured and tested
- [ ] PITR restore drill completed successfully and documented
- [ ] Cron schedule and alerting configured
- [ ] Backup status panel wireframe/component

---

### Day 35 — Database Security Hardening

**Objective:** Apply row-level security, encrypted connections, and access control per C-011.

#### Dev 1 — Backend Lead (~8 h)

| Hours | Task |
|---|---|
| 3 h | Implement row-level security policies for multi-tenant isolation |
| 3 h | Configure SSL/TLS for PostgreSQL connections |
| 2 h | Harden `pg_hba.conf` — restrict access by IP, enforce `scram-sha-256` |

```sql
-- Row-Level Security: restrict farm data access by user role
ALTER TABLE farm.animal ENABLE ROW LEVEL SECURITY;

-- Farm managers see all animals
CREATE POLICY farm_manager_all ON farm.animal
    FOR ALL
    TO farm_manager_role
    USING (true);

-- Farm workers see only active animals in their assigned section
CREATE POLICY farm_worker_section ON farm.animal
    FOR SELECT
    TO farm_worker_role
    USING (
        status = 'active'
        AND section_id IN (
            SELECT section_id FROM core.user_section_assignment
            WHERE user_id = current_setting('app.current_user_id')::int
        )
    );

-- Auditors get read-only access to all records
CREATE POLICY auditor_readonly ON farm.animal
    FOR SELECT
    TO auditor_role
    USING (true);

-- Apply similar RLS on milk_production
ALTER TABLE farm.milk_production ENABLE ROW LEVEL SECURITY;

CREATE POLICY milk_prod_manager ON farm.milk_production
    FOR ALL
    TO farm_manager_role
    USING (true);

CREATE POLICY milk_prod_worker ON farm.milk_production
    FOR SELECT
    TO farm_worker_role
    USING (
        animal_id IN (
            SELECT id FROM farm.animal
            WHERE section_id IN (
                SELECT section_id FROM core.user_section_assignment
                WHERE user_id = current_setting('app.current_user_id')::int
            )
        )
    );
```

```ini
# pg_hba.conf — Smart Dairy Production
# TYPE  DATABASE        USER            ADDRESS                 METHOD

# Local socket connections
local   all             postgres                                peer
local   all             odoo                                    scram-sha-256

# IPv4 local connections — application server
hostssl smart_dairy     odoo            10.0.1.0/24             scram-sha-256

# IPv4 — pgBouncer
hostssl smart_dairy     pgbouncer       10.0.1.10/32            scram-sha-256

# IPv4 — monitoring (read-only user)
hostssl smart_dairy     monitoring      10.0.1.20/32            scram-sha-256

# Reject everything else
host    all             all             0.0.0.0/0               reject
hostssl all             all             0.0.0.0/0               reject
```

#### Dev 2 — Full-Stack/DevOps (~8 h)

| Hours | Task |
|---|---|
| 3 h | Generate and deploy SSL certificates for PostgreSQL (self-signed for dev, Let's Encrypt for prod) |
| 3 h | Configure Docker secrets management for database credentials |
| 2 h | Audit and document all database user roles and permissions |

#### Dev 3 — Frontend/Mobile Lead (~8 h)

| Hours | Task |
|---|---|
| 4 h | Implement security audit log viewer component in OWL admin panel |
| 4 h | Build role-based UI visibility helpers based on backend RLS policies |

#### End-of-Day 35 Deliverables

- [ ] RLS policies applied and tested on `farm.animal`, `farm.milk_production`
- [ ] SSL/TLS connections enforced in `pg_hba.conf`
- [ ] Docker secrets configured for DB credentials
- [ ] Security audit log viewer component

---

### Day 36 — Connection Pooling & Monitoring

**Objective:** Deploy pgBouncer for connection pooling and Prometheus + Grafana for database monitoring.

#### Dev 1 — Backend Lead (~8 h)

| Hours | Task |
|---|---|
| 3 h | Configure pgBouncer in transaction pooling mode |
| 3 h | Set up Prometheus PostgreSQL exporter with custom query metrics |
| 2 h | Write VACUUM and ANALYZE automation scripts |

```ini
; deploy/pgbouncer/pgbouncer.ini
[databases]
smart_dairy = host=postgres port=5432 dbname=smart_dairy
              auth_user=pgbouncer
              pool_size=25
              min_pool_size=5
              reserve_pool_size=5

[pgbouncer]
listen_addr          = 0.0.0.0
listen_port          = 6432
auth_type            = scram-sha-256
auth_file            = /etc/pgbouncer/userlist.txt

; Pool settings
pool_mode            = transaction
default_pool_size    = 25
min_pool_size        = 5
reserve_pool_size    = 5
reserve_pool_timeout = 3
max_client_conn      = 400
max_db_connections   = 100

; Timeouts
server_idle_timeout  = 300
client_idle_timeout  = 600
query_timeout        = 30
query_wait_timeout   = 60

; Logging
log_connections      = 1
log_disconnections   = 1
log_pooler_errors    = 1
stats_period         = 60

; TLS
client_tls_sslmode   = require
client_tls_cert_file = /etc/pgbouncer/server.crt
client_tls_key_file  = /etc/pgbouncer/server.key
server_tls_sslmode   = require
```

```yaml
# deploy/prometheus/postgres_exporter_queries.yaml
# Custom queries for Smart Dairy monitoring

pg_smart_dairy_milk_production:
  query: |
    SELECT
      count(*) as total_records,
      sum(quantity_litres) as total_litres,
      avg(quantity_litres) as avg_litres
    FROM farm.milk_production
    WHERE production_date = CURRENT_DATE
  metrics:
    - total_records:
        usage: "GAUGE"
        description: "Total milk production records today"
    - total_litres:
        usage: "GAUGE"
        description: "Total milk produced today (litres)"
    - avg_litres:
        usage: "GAUGE"
        description: "Average milk per record today (litres)"

pg_smart_dairy_active_animals:
  query: |
    SELECT count(*) as active_count
    FROM farm.animal
    WHERE status = 'active'
  metrics:
    - active_count:
        usage: "GAUGE"
        description: "Number of active animals"

pg_smart_dairy_cache_hit_ratio:
  query: |
    SELECT
      round(100.0 * sum(blks_hit) /
        NULLIF(sum(blks_hit) + sum(blks_read), 0), 2) as ratio
    FROM pg_stat_database
    WHERE datname = 'smart_dairy'
  metrics:
    - ratio:
        usage: "GAUGE"
        description: "Database buffer cache hit ratio"

pg_smart_dairy_table_bloat:
  query: |
    SELECT
      schemaname || '.' || relname as table_name,
      n_dead_tup,
      n_live_tup,
      CASE WHEN n_live_tup > 0
           THEN round(100.0 * n_dead_tup / n_live_tup, 2)
           ELSE 0
      END as dead_ratio
    FROM pg_stat_user_tables
    WHERE schemaname IN ('farm', 'sales', 'inventory')
    ORDER BY n_dead_tup DESC
    LIMIT 10
  metrics:
    - table_name:
        usage: "LABEL"
        description: "Table name"
    - n_dead_tup:
        usage: "GAUGE"
        description: "Dead tuples"
    - dead_ratio:
        usage: "GAUGE"
        description: "Dead tuple ratio percentage"
```

```bash
#!/usr/bin/env bash
# deploy/scripts/vacuum_analyze.sh
# Automated VACUUM and ANALYZE for Smart Dairy
# Schedule: 0 3 * * * (daily at 3 AM)

set -euo pipefail

DB_NAME="smart_dairy"
DB_USER="odoo"
LOG="/var/log/smart_dairy/vacuum.log"

log() { echo "[$(date '+%Y-%m-%d %H:%M:%S')] $*" >> "${LOG}"; }

SCHEMAS=("farm" "sales" "inventory" "accounting" "analytics" "audit" "iot" "core")

log "=== Starting VACUUM ANALYZE cycle ==="

for schema in "${SCHEMAS[@]}"; do
    TABLES=$(psql -U "${DB_USER}" -d "${DB_NAME}" -t -c \
        "SELECT tablename FROM pg_tables WHERE schemaname='${schema}';")

    for table in ${TABLES}; do
        log "VACUUM ANALYZE ${schema}.${table}"
        psql -U "${DB_USER}" -d "${DB_NAME}" -c \
            "VACUUM (ANALYZE, VERBOSE) ${schema}.${table};" >> "${LOG}" 2>&1
    done
done

# Targeted aggressive vacuum on high-churn tables
log "VACUUM FULL on high-churn tables..."
HIGH_CHURN=("farm.milk_production" "iot.sensor_reading" "audit.log")
for table in "${HIGH_CHURN[@]}"; do
    DEAD=$(psql -U "${DB_USER}" -d "${DB_NAME}" -t -c \
        "SELECT n_dead_tup FROM pg_stat_user_tables
         WHERE schemaname='$(echo $table | cut -d. -f1)'
         AND relname='$(echo $table | cut -d. -f2)';")
    if [ "${DEAD:-0}" -gt 10000 ]; then
        log "VACUUM FULL ${table} (dead tuples: ${DEAD})"
        psql -U "${DB_USER}" -d "${DB_NAME}" -c "VACUUM FULL ${table};"
    fi
done

log "=== VACUUM ANALYZE cycle complete ==="
```

#### Dev 2 — Full-Stack/DevOps (~8 h)

| Hours | Task |
|---|---|
| 3 h | Deploy pgBouncer container, integrate into Docker Compose |
| 3 h | Deploy Prometheus with PostgreSQL exporter and Redis exporter |
| 2 h | Build Grafana dashboards for database metrics |

```json
{
  "dashboard": {
    "title": "Smart Dairy — Database Monitor",
    "uid": "sd-db-monitor",
    "timezone": "Asia/Dhaka",
    "panels": [
      {
        "title": "Query Response Time (p95)",
        "type": "timeseries",
        "gridPos": { "h": 8, "w": 12, "x": 0, "y": 0 },
        "targets": [
          {
            "expr": "histogram_quantile(0.95, rate(pg_stat_statements_seconds_bucket{datname='smart_dairy'}[5m]))",
            "legendFormat": "p95 latency"
          }
        ],
        "fieldConfig": {
          "defaults": { "unit": "s", "thresholds": { "steps": [
            { "color": "green", "value": null },
            { "color": "yellow", "value": 0.05 },
            { "color": "red", "value": 0.1 }
          ]}}
        }
      },
      {
        "title": "Buffer Cache Hit Ratio",
        "type": "gauge",
        "gridPos": { "h": 8, "w": 6, "x": 12, "y": 0 },
        "targets": [
          {
            "expr": "pg_smart_dairy_cache_hit_ratio",
            "legendFormat": "Cache Hit %"
          }
        ],
        "fieldConfig": {
          "defaults": { "unit": "percent", "min": 0, "max": 100, "thresholds": { "steps": [
            { "color": "red", "value": null },
            { "color": "yellow", "value": 80 },
            { "color": "green", "value": 95 }
          ]}}
        }
      },
      {
        "title": "Active Connections",
        "type": "stat",
        "gridPos": { "h": 8, "w": 6, "x": 18, "y": 0 },
        "targets": [
          {
            "expr": "pg_stat_activity_count{datname='smart_dairy'}",
            "legendFormat": "Connections"
          }
        ]
      },
      {
        "title": "Redis Cache Hit Rate",
        "type": "gauge",
        "gridPos": { "h": 8, "w": 6, "x": 0, "y": 8 },
        "targets": [
          {
            "expr": "rate(redis_keyspace_hits_total[5m]) / (rate(redis_keyspace_hits_total[5m]) + rate(redis_keyspace_misses_total[5m])) * 100",
            "legendFormat": "Redis Hit %"
          }
        ]
      },
      {
        "title": "Dead Tuples by Table",
        "type": "bargauge",
        "gridPos": { "h": 8, "w": 12, "x": 6, "y": 8 },
        "targets": [
          {
            "expr": "pg_smart_dairy_table_bloat{table_name=~'farm.*|sales.*'}",
            "legendFormat": "{{ table_name }}"
          }
        ]
      },
      {
        "title": "Daily Milk Production (Litres)",
        "type": "stat",
        "gridPos": { "h": 8, "w": 6, "x": 18, "y": 8 },
        "targets": [
          {
            "expr": "pg_smart_dairy_milk_production_total_litres",
            "legendFormat": "Litres Today"
          }
        ]
      }
    ],
    "refresh": "30s",
    "time": { "from": "now-6h", "to": "now" }
  }
}
```

#### Dev 3 — Frontend/Mobile Lead (~8 h)

| Hours | Task |
|---|---|
| 4 h | Build live OWL `DatabaseMonitor` dashboard component using real Prometheus endpoints |
| 4 h | Implement auto-refresh and alert badge for anomalous metrics |

```javascript
/** @odoo-module */
// addons/smart_dairy_monitor/static/src/components/database_monitor/database_monitor.js

import { Component, useState, onMounted, onWillUnmount } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";

export class DatabaseMonitor extends Component {
    static template = "smart_dairy_monitor.DatabaseMonitor";
    static props = {};

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            queryLatencyP95: 0,
            cacheHitRatio: 0,
            activeConnections: 0,
            redisCacheHitRate: 0,
            dailyMilkLitres: 0,
            deadTuples: [],
            lastUpdated: null,
            loading: true,
            error: null,
        });

        this._intervalId = null;

        onMounted(() => {
            this.fetchMetrics();
            this._intervalId = setInterval(() => this.fetchMetrics(), 30000);
        });

        onWillUnmount(() => {
            if (this._intervalId) {
                clearInterval(this._intervalId);
            }
        });
    }

    async fetchMetrics() {
        try {
            const data = await this.rpc("/api/v1/monitoring/db-metrics", {});
            Object.assign(this.state, {
                queryLatencyP95: data.query_latency_p95_ms,
                cacheHitRatio: data.cache_hit_ratio,
                activeConnections: data.active_connections,
                redisCacheHitRate: data.redis_hit_rate,
                dailyMilkLitres: data.daily_milk_litres,
                deadTuples: data.dead_tuples_top5 || [],
                lastUpdated: new Date().toLocaleTimeString(),
                loading: false,
                error: null,
            });

            // Alert on anomalies
            if (data.cache_hit_ratio < 80) {
                this.notification.add(
                    `Buffer cache hit ratio is low: ${data.cache_hit_ratio}%`,
                    { type: "warning", sticky: false }
                );
            }
        } catch (err) {
            this.state.error = err.message;
            this.state.loading = false;
        }
    }

    get latencyClass() {
        if (this.state.queryLatencyP95 < 50) return "text-success";
        if (this.state.queryLatencyP95 < 100) return "text-warning";
        return "text-danger";
    }

    get cacheClass() {
        if (this.state.cacheHitRatio >= 95) return "text-success";
        if (this.state.cacheHitRatio >= 80) return "text-warning";
        return "text-danger";
    }
}

registry.category("actions").add("smart_dairy_monitor.db_dashboard", DatabaseMonitor);
```

#### End-of-Day 36 Deliverables

- [ ] pgBouncer deployed and Odoo connected through connection pool
- [ ] Prometheus collecting PostgreSQL and Redis metrics
- [ ] Grafana dashboard JSON imported and panels operational
- [ ] VACUUM/ANALYZE automation script scheduled
- [ ] OWL `DatabaseMonitor` component rendering live data

---

### Day 37 — Load Testing & Capacity Planning

**Objective:** Validate performance under simulated production load with K6.

#### Dev 1 — Backend Lead (~8 h)

| Hours | Task |
|---|---|
| 3 h | Write K6 test scripts for Odoo XML-RPC endpoints |
| 3 h | Write K6 test scripts for custom FastAPI endpoints |
| 2 h | Analyse results, identify bottlenecks, document capacity plan |

```javascript
// tests/load/k6_odoo_xmlrpc.js
// K6 load test — Odoo XML-RPC milk production endpoints

import http from 'k6/http';
import { check, sleep, group } from 'k6';
import { Rate, Trend } from 'k6/metrics';

const errorRate = new Rate('errors');
const milkQueryDuration = new Trend('milk_query_duration');

export const options = {
    stages: [
        { duration: '1m',  target: 10 },   // ramp up
        { duration: '3m',  target: 50 },   // sustained load
        { duration: '1m',  target: 100 },  // peak
        { duration: '1m',  target: 0 },    // ramp down
    ],
    thresholds: {
        http_req_duration: ['p(95)<200'],   // 95% under 200 ms
        errors: ['rate<0.05'],              // < 5% error rate
    },
};

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8069';
const DB_NAME  = __ENV.DB_NAME  || 'smart_dairy';
const USERNAME = __ENV.USERNAME || 'admin';
const PASSWORD = __ENV.PASSWORD || 'admin';

function xmlrpcCall(url, method, params) {
    const payload = `<?xml version="1.0"?>
    <methodCall>
        <methodName>${method}</methodName>
        <params>${params}</params>
    </methodCall>`;

    return http.post(url, payload, {
        headers: { 'Content-Type': 'text/xml' },
    });
}

function authenticate() {
    const params = `
        <param><value><string>${DB_NAME}</string></value></param>
        <param><value><string>${USERNAME}</string></value></param>
        <param><value><string>${PASSWORD}</string></value></param>
        <param><value><struct></struct></value></param>`;

    const res = xmlrpcCall(`${BASE_URL}/xmlrpc/2/common`, 'authenticate', params);
    const match = res.body.match(/<int>(\d+)<\/int>/);
    return match ? parseInt(match[1]) : null;
}

export default function () {
    const uid = authenticate();

    group('Milk Production Queries', function () {
        // Search today's milk production records
        const start = Date.now();
        const searchParams = `
            <param><value><string>${DB_NAME}</string></value></param>
            <param><value><int>${uid}</int></value></param>
            <param><value><string>${PASSWORD}</string></value></param>
            <param><value><string>farm.milk_production</string></value></param>
            <param><value><string>search_read</string></value></param>
            <param><value><array><data>
                <value><array><data>
                    <value><array><data>
                        <value><string>production_date</string></value>
                        <value><string>=</string></value>
                        <value><string>2026-02-03</string></value>
                    </data></array></value>
                </data></array></value>
            </data></array></value>
            <param><value><struct>
                <member><name>fields</name><value><array><data>
                    <value><string>animal_id</string></value>
                    <value><string>quantity_litres</string></value>
                    <value><string>shift</string></value>
                </data></array></value></member>
                <member><name>limit</name><value><int>100</int></value></member>
            </struct></value></param>`;

        const res = xmlrpcCall(`${BASE_URL}/xmlrpc/2/object`, 'execute_kw', searchParams);
        milkQueryDuration.add(Date.now() - start);

        check(res, {
            'milk search status 200': (r) => r.status === 200,
            'milk search has data': (r) => r.body.includes('<array>'),
        });
        errorRate.add(res.status !== 200);
    });

    group('Animal Lookup', function () {
        const searchParams = `
            <param><value><string>${DB_NAME}</string></value></param>
            <param><value><int>${uid}</int></value></param>
            <param><value><string>${PASSWORD}</string></value></param>
            <param><value><string>farm.animal</string></value></param>
            <param><value><string>search_read</string></value></param>
            <param><value><array><data>
                <value><array><data>
                    <value><array><data>
                        <value><string>status</string></value>
                        <value><string>=</string></value>
                        <value><string>active</string></value>
                    </data></array></value>
                </data></array></value>
            </data></array></value>
            <param><value><struct>
                <member><name>fields</name><value><array><data>
                    <value><string>name</string></value>
                    <value><string>tag_number</string></value>
                    <value><string>breed</string></value>
                </data></array></value></member>
            </struct></value></param>`;

        const res = xmlrpcCall(`${BASE_URL}/xmlrpc/2/object`, 'execute_kw', searchParams);

        check(res, {
            'animal search status 200': (r) => r.status === 200,
        });
        errorRate.add(res.status !== 200);
    });

    sleep(1);
}
```

#### Dev 2 — Full-Stack/DevOps (~8 h)

| Hours | Task |
|---|---|
| 3 h | Set up K6 in CI pipeline for regression testing |
| 3 h | Configure resource monitoring during load tests (CPU, memory, I/O) |
| 2 h | Document capacity planning spreadsheet (current vs projected growth) |

#### Dev 3 — Frontend/Mobile Lead (~8 h)

| Hours | Task |
|---|---|
| 4 h | Load test the OWL frontend with simulated concurrent dashboard sessions |
| 4 h | Profile OWL component rendering times, optimise re-renders |

#### End-of-Day 37 Deliverables

- [ ] K6 test scripts in `tests/load/`
- [ ] Load test results report with p50, p95, p99 latencies
- [ ] Bottleneck analysis document
- [ ] Capacity planning spreadsheet

---

### Day 38 — Data Migration Planning

**Objective:** Prepare ETL scripts and validation for legacy data migration.

#### Dev 1 — Backend Lead (~8 h)

| Hours | Task |
|---|---|
| 4 h | Write ETL Python scripts for legacy data mapping |
| 2 h | Build data validation and integrity check queries |
| 2 h | Create migration rollback procedures |

```python
# migrations/etl/legacy_migration.py
"""ETL pipeline for migrating legacy Smart Dairy data to Odoo 19 CE schemas."""

import csv
import logging
from datetime import datetime
from pathlib import Path
from typing import Generator

import psycopg2
from psycopg2.extras import execute_batch

_logger = logging.getLogger(__name__)

# ---------------------------------------------------------------------------
# Configuration
# ---------------------------------------------------------------------------

LEGACY_DATA_DIR = Path("/opt/smart_dairy/migrations/data")
BATCH_SIZE = 500

DSN = {
    "host": "localhost",
    "port": 5432,
    "dbname": "smart_dairy",
    "user": "odoo",
    "password": "",  # loaded from env/secrets
}


# ---------------------------------------------------------------------------
# Helper: CSV Reader
# ---------------------------------------------------------------------------

def read_csv(filename: str) -> Generator[dict, None, None]:
    """Yield rows from a legacy CSV file as dictionaries."""
    filepath = LEGACY_DATA_DIR / filename
    _logger.info("Reading legacy file: %s", filepath)
    with open(filepath, newline="", encoding="utf-8-sig") as f:
        reader = csv.DictReader(f)
        for row in reader:
            yield {k.strip().lower(): v.strip() for k, v in row.items()}


# ---------------------------------------------------------------------------
# Animal Migration
# ---------------------------------------------------------------------------

def migrate_animals(conn) -> int:
    """Migrate legacy animal records to farm.animal."""
    _logger.info("=== Migrating Animals ===")

    insert_sql = """
        INSERT INTO farm.animal (
            name, tag_number, breed, date_of_birth, gender,
            status, weight_kg, section_id, metadata,
            created_at, legacy_id
        ) VALUES (
            %(name)s, %(tag_number)s, %(breed)s, %(dob)s, %(gender)s,
            %(status)s, %(weight)s, %(section_id)s, %(metadata)s::jsonb,
            NOW(), %(legacy_id)s
        )
        ON CONFLICT (tag_number) DO UPDATE SET
            name = EXCLUDED.name,
            status = EXCLUDED.status,
            weight_kg = EXCLUDED.weight_kg;
    """

    rows = []
    count = 0

    for row in read_csv("legacy_animals.csv"):
        rows.append({
            "name": row.get("animal_name", ""),
            "tag_number": row["tag_no"],
            "breed": row.get("breed", "Local"),
            "dob": _parse_date(row.get("birth_date")),
            "gender": _map_gender(row.get("sex", "F")),
            "status": _map_status(row.get("current_status", "active")),
            "weight": float(row.get("weight_kg", 0)) or None,
            "section_id": int(row.get("section", 1)),
            "metadata": '{"source": "legacy_migration"}',
            "legacy_id": row.get("id"),
        })

        if len(rows) >= BATCH_SIZE:
            count += _execute_batch(conn, insert_sql, rows)
            rows.clear()

    if rows:
        count += _execute_batch(conn, insert_sql, rows)

    _logger.info("Migrated %d animal records", count)
    return count


# ---------------------------------------------------------------------------
# Milk Production Migration
# ---------------------------------------------------------------------------

def migrate_milk_production(conn) -> int:
    """Migrate legacy milk production records to farm.milk_production."""
    _logger.info("=== Migrating Milk Production ===")

    insert_sql = """
        INSERT INTO farm.milk_production (
            animal_id, production_date, shift,
            quantity_litres, fat_percentage, snf_percentage,
            created_at, legacy_id
        )
        SELECT
            a.id, %(prod_date)s, %(shift)s,
            %(quantity)s, %(fat)s, %(snf)s,
            NOW(), %(legacy_id)s
        FROM farm.animal a
        WHERE a.tag_number = %(tag_number)s
        ON CONFLICT DO NOTHING;
    """

    rows = []
    count = 0

    for row in read_csv("legacy_milk_production.csv"):
        rows.append({
            "tag_number": row["animal_tag"],
            "prod_date": _parse_date(row["date"]),
            "shift": _map_shift(row.get("milking_time", "morning")),
            "quantity": float(row.get("litres", 0)),
            "fat": float(row.get("fat_pct", 0)) or None,
            "snf": float(row.get("snf_pct", 0)) or None,
            "legacy_id": row.get("id"),
        })

        if len(rows) >= BATCH_SIZE:
            count += _execute_batch(conn, insert_sql, rows)
            rows.clear()

    if rows:
        count += _execute_batch(conn, insert_sql, rows)

    _logger.info("Migrated %d milk production records", count)
    return count


# ---------------------------------------------------------------------------
# Validation Queries
# ---------------------------------------------------------------------------

VALIDATION_QUERIES = {
    "animal_count_match": """
        SELECT
            (SELECT count(*) FROM farm.animal WHERE legacy_id IS NOT NULL) AS migrated,
            %(expected_count)s AS expected;
    """,
    "milk_production_no_orphans": """
        SELECT count(*) AS orphan_count
        FROM farm.milk_production mp
        LEFT JOIN farm.animal a ON a.id = mp.animal_id
        WHERE a.id IS NULL;
    """,
    "duplicate_tags": """
        SELECT tag_number, count(*)
        FROM farm.animal
        GROUP BY tag_number
        HAVING count(*) > 1;
    """,
    "null_critical_fields": """
        SELECT count(*) AS null_records
        FROM farm.animal
        WHERE tag_number IS NULL OR name IS NULL;
    """,
}


def run_validations(conn) -> dict:
    """Run post-migration validation queries and return results."""
    results = {}
    cur = conn.cursor()
    for name, query in VALIDATION_QUERIES.items():
        try:
            if "%(expected_count)s" in query:
                # Count legacy source rows
                legacy_count = sum(1 for _ in read_csv("legacy_animals.csv"))
                cur.execute(query, {"expected_count": legacy_count})
            else:
                cur.execute(query)
            results[name] = cur.fetchall()
            _logger.info("Validation [%s]: %s", name, results[name])
        except Exception as e:
            results[name] = f"ERROR: {e}"
            _logger.error("Validation [%s] failed: %s", name, e)
    return results


# ---------------------------------------------------------------------------
# Helpers
# ---------------------------------------------------------------------------

def _execute_batch(conn, sql, rows) -> int:
    cur = conn.cursor()
    execute_batch(cur, sql, rows, page_size=BATCH_SIZE)
    conn.commit()
    return len(rows)


def _parse_date(value: str):
    if not value:
        return None
    for fmt in ("%Y-%m-%d", "%d/%m/%Y", "%d-%m-%Y", "%m/%d/%Y"):
        try:
            return datetime.strptime(value, fmt).date()
        except ValueError:
            continue
    _logger.warning("Unparseable date: %s", value)
    return None


def _map_gender(value: str) -> str:
    return {"m": "male", "f": "female", "male": "male", "female": "female"}.get(
        value.lower(), "female"
    )


def _map_status(value: str) -> str:
    mapping = {
        "active": "active", "alive": "active",
        "sold": "sold", "dead": "deceased", "deceased": "deceased",
        "dry": "dry", "culled": "culled",
    }
    return mapping.get(value.lower(), "active")


def _map_shift(value: str) -> str:
    mapping = {"morning": "morning", "evening": "evening", "noon": "noon",
               "am": "morning", "pm": "evening"}
    return mapping.get(value.lower(), "morning")


# ---------------------------------------------------------------------------
# Main Entry Point
# ---------------------------------------------------------------------------

def run_migration():
    """Execute the full ETL pipeline."""
    _logger.info("Starting Smart Dairy legacy data migration...")
    conn = psycopg2.connect(**DSN)

    try:
        animal_count = migrate_animals(conn)
        milk_count = migrate_milk_production(conn)

        _logger.info("Migration complete: %d animals, %d milk records",
                      animal_count, milk_count)

        results = run_validations(conn)
        _logger.info("Validation results: %s", results)

    except Exception:
        conn.rollback()
        _logger.exception("Migration FAILED — rolled back")
        raise
    finally:
        conn.close()


if __name__ == "__main__":
    logging.basicConfig(level=logging.INFO)
    run_migration()
```

#### Dev 2 — Full-Stack/DevOps (~8 h)

| Hours | Task |
|---|---|
| 3 h | Set up migration staging environment (separate Docker Compose profile) |
| 3 h | Build migration CI job: run ETL, validate, rollback on failure |
| 2 h | Create legacy data sample fixtures for testing |

#### Dev 3 — Frontend/Mobile Lead (~8 h)

| Hours | Task |
|---|---|
| 4 h | Build migration progress UI (progress bar, record counts, error log) |
| 4 h | Implement data validation report viewer component |

#### End-of-Day 38 Deliverables

- [ ] ETL scripts in `migrations/etl/`
- [ ] Validation queries passing on sample data
- [ ] Migration staging environment operational
- [ ] Migration progress UI component

---

### Day 39 — Documentation, Knowledge Transfer, Runbooks

**Objective:** Consolidate all Milestone 4 work into operational runbooks and conduct knowledge transfer.

#### Dev 1 — Backend Lead (~8 h)

| Hours | Task |
|---|---|
| 3 h | Write database operations runbook (backup, restore, failover, VACUUM) |
| 3 h | Document indexing strategy rationale and maintenance guide |
| 2 h | Conduct knowledge transfer session with Dev 2 and Dev 3 |

#### Dev 2 — Full-Stack/DevOps (~8 h)

| Hours | Task |
|---|---|
| 3 h | Write monitoring and alerting runbook (Prometheus, Grafana, alert rules) |
| 3 h | Document Docker Compose service topology and networking |
| 2 h | Create incident response playbook for database emergencies |

#### Dev 3 — Frontend/Mobile Lead (~8 h)

| Hours | Task |
|---|---|
| 3 h | Document OWL `DatabaseMonitor` component API and extension points |
| 3 h | Write frontend performance monitoring guide |
| 2 h | Participate in knowledge transfer session |

#### End-of-Day 39 Deliverables

- [ ] Database operations runbook in `docs/runbooks/database_operations.md`
- [ ] Monitoring runbook in `docs/runbooks/monitoring_alerting.md`
- [ ] Incident response playbook in `docs/runbooks/incident_response.md`
- [ ] Knowledge transfer session completed and recorded

---

### Day 40 — Milestone 4 Review & Sign-Off

**Objective:** Validate all deliverables, run final benchmarks, and obtain sign-off.

#### Dev 1 — Backend Lead (~8 h)

| Hours | Task |
|---|---|
| 3 h | Run final performance benchmarks and compare against Day 31 baselines |
| 3 h | Address any remaining issues from the review checklist |
| 2 h | Present performance improvement report to team |

#### Dev 2 — Full-Stack/DevOps (~8 h)

| Hours | Task |
|---|---|
| 3 h | Run full CI/CD pipeline end-to-end; ensure all tests pass |
| 3 h | Verify backup/restore drill one final time |
| 2 h | Tag release `v0.4.0-milestone4` |

#### Dev 3 — Frontend/Mobile Lead (~8 h)

| Hours | Task |
|---|---|
| 3 h | Final polish on `DatabaseMonitor` dashboard |
| 3 h | Run frontend performance audit (Lighthouse, bundle size) |
| 2 h | Sign-off on UI deliverables |

#### End-of-Day 40 Deliverables

- [ ] Performance benchmark report: before vs after comparison
- [ ] All success criteria met (see Section 1.2)
- [ ] Git tag `v0.4.0-milestone4` created
- [ ] Milestone 4 sign-off document signed by all three developers

---

## 4. Technical Specifications

### 4.1 PostgreSQL 16 Configuration Summary

| Parameter | Value | Rationale |
|---|---|---|
| `shared_buffers` | 8 GB | 25% of 32 GB RAM |
| `effective_cache_size` | 24 GB | 75% of RAM — planner hint |
| `work_mem` | 64 MB | Allows complex sorts in-memory |
| `maintenance_work_mem` | 1 GB | Fast VACUUM and index builds |
| `wal_buffers` | 64 MB | Sufficient for write-ahead log throughput |
| `max_connections` | 200 | Behind pgBouncer (400 client-side) |
| `random_page_cost` | 1.1 | NVMe SSD storage |
| `effective_io_concurrency` | 200 | SSD parallel reads |
| `checkpoint_completion_target` | 0.9 | Spread checkpoint writes |
| `autovacuum_max_workers` | 4 | Parallel autovacuum across schemas |

### 4.2 Redis 7 Configuration Summary

| Parameter | Value |
|---|---|
| `maxmemory` | 2 GB |
| `maxmemory-policy` | `allkeys-lru` |
| `appendonly` | `yes` |
| `appendfsync` | `everysec` |
| `save` | `900 1`, `300 10`, `60 10000` |
| `tcp-keepalive` | 300 |

### 4.3 pgBouncer Configuration Summary

| Parameter | Value |
|---|---|
| `pool_mode` | `transaction` |
| `default_pool_size` | 25 |
| `max_client_conn` | 400 |
| `max_db_connections` | 100 |
| `server_idle_timeout` | 300 s |
| `query_timeout` | 30 s |

### 4.4 Connection Pooling Python Integration

```python
# addons/smart_dairy_core/db/pool.py
"""Application-level connection pooling via psycopg2 pool."""

import logging
from contextlib import contextmanager

from psycopg2 import pool as pg_pool

_logger = logging.getLogger(__name__)

_connection_pool = None


def init_pool(minconn: int = 5, maxconn: int = 25, **dsn_kwargs):
    """Initialise the application connection pool."""
    global _connection_pool
    if _connection_pool is None:
        _connection_pool = pg_pool.ThreadedConnectionPool(
            minconn=minconn,
            maxconn=maxconn,
            host=dsn_kwargs.get("host", "pgbouncer"),
            port=dsn_kwargs.get("port", 6432),
            dbname=dsn_kwargs.get("dbname", "smart_dairy"),
            user=dsn_kwargs.get("user", "odoo"),
            password=dsn_kwargs.get("password", ""),
            options="-c search_path=core,farm,sales,inventory,accounting,analytics,audit,iot,public",
        )
        _logger.info("Connection pool initialised (min=%d, max=%d)", minconn, maxconn)


def get_pool() -> pg_pool.ThreadedConnectionPool:
    if _connection_pool is None:
        raise RuntimeError("Connection pool not initialised. Call init_pool() first.")
    return _connection_pool


@contextmanager
def get_connection():
    """Context manager that borrows a connection and returns it to the pool."""
    p = get_pool()
    conn = p.getconn()
    try:
        yield conn
        conn.commit()
    except Exception:
        conn.rollback()
        raise
    finally:
        p.putconn(conn)


def close_pool():
    """Shut down the connection pool gracefully."""
    global _connection_pool
    if _connection_pool is not None:
        _connection_pool.closeall()
        _connection_pool = None
        _logger.info("Connection pool closed")
```

### 4.5 Partitioned Table Strategy

The `farm.milk_production` table is partitioned quarterly to keep partition sizes manageable and improve query performance for date-range scans.

```sql
-- Quarterly partitions for milk_production (created during schema setup, validated here)
-- Partition for Q1 2026
CREATE TABLE IF NOT EXISTS farm.milk_production_2026_q1
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2026-01-01') TO ('2026-04-01');

-- Partition for Q2 2026
CREATE TABLE IF NOT EXISTS farm.milk_production_2026_q2
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2026-04-01') TO ('2026-07-01');

-- Partition for Q3 2026
CREATE TABLE IF NOT EXISTS farm.milk_production_2026_q3
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2026-07-01') TO ('2026-10-01');

-- Partition for Q4 2026
CREATE TABLE IF NOT EXISTS farm.milk_production_2026_q4
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2026-10-01') TO ('2027-01-01');

-- Automate partition creation for upcoming quarters
CREATE OR REPLACE FUNCTION farm.create_milk_production_partition()
RETURNS void LANGUAGE plpgsql AS $$
DECLARE
    next_q_start DATE;
    next_q_end   DATE;
    part_name    TEXT;
BEGIN
    next_q_start := date_trunc('quarter', CURRENT_DATE + INTERVAL '3 months');
    next_q_end   := next_q_start + INTERVAL '3 months';
    part_name    := 'farm.milk_production_' ||
                    to_char(next_q_start, 'YYYY') || '_q' ||
                    extract(quarter FROM next_q_start)::text;

    IF NOT EXISTS (
        SELECT 1 FROM pg_class c JOIN pg_namespace n ON n.oid = c.relnamespace
        WHERE n.nspname = 'farm' AND c.relname = split_part(part_name, '.', 2)
    ) THEN
        EXECUTE format(
            'CREATE TABLE %s PARTITION OF farm.milk_production FOR VALUES FROM (%L) TO (%L)',
            part_name, next_q_start, next_q_end
        );
        RAISE NOTICE 'Created partition: %', part_name;
    END IF;
END;
$$;
```

---

## 5. Testing & Validation

### 5.1 Test Categories

| Category | Tool | Scope | Pass Criteria |
|---|---|---|---|
| Unit Tests | `pytest` | Cache decorator, session store, invalidation, ETL helpers | 100% pass, > 90% coverage |
| Integration Tests | `pytest` + PostgreSQL | Index effectiveness, RLS policies, connection pooling | All queries use indexes; RLS blocks unauthorized access |
| Performance Tests | K6 | XML-RPC, FastAPI endpoints | p95 < 200 ms at 50 VUs |
| Backup/Restore | Manual drill | pg_dump, WAL, PITR | Successful restore to target timestamp |
| Security Tests | `pg_hba.conf` audit | Connection rejection, SSL enforcement | All non-SSL connections rejected |
| Migration Tests | `pytest` | ETL scripts on sample data | Zero orphans, zero duplicates, count matches |

### 5.2 Performance Benchmark Template

| Metric | Baseline (Day 31) | Target | Actual (Day 40) | Status |
|---|---|---|---|---|
| `farm.animal` lookup by tag (avg ms) | ___ | < 5 ms | ___ | ___ |
| `farm.milk_production` daily report (avg ms) | ___ | < 50 ms | ___ | ___ |
| `farm.health_record` search (avg ms) | ___ | < 30 ms | ___ | ___ |
| Buffer cache hit ratio | ___ | > 95% | ___ | ___ |
| Redis cache hit ratio | ___ | > 85% | ___ | ___ |
| Sequential scan ratio (farm schema) | ___ | < 5% | ___ | ___ |
| Connection pool utilisation at 50 VUs | ___ | < 70% | ___ | ___ |

### 5.3 Acceptance Checklist

- [ ] All indexes created and validated with `EXPLAIN ANALYZE`
- [ ] Redis caching layer operational with measurable hit ratio
- [ ] pgBouncer connection pooling verified under load
- [ ] Backup and PITR restore drill passed
- [ ] RLS policies tested with multiple roles
- [ ] SSL/TLS enforced for all database connections
- [ ] Grafana dashboards displaying real-time metrics
- [ ] K6 load tests passing all thresholds
- [ ] ETL scripts validated on sample data
- [ ] VACUUM/ANALYZE automation scheduled and verified
- [ ] All runbooks reviewed and committed
- [ ] Milestone sign-off document completed

---

## 6. Risk & Mitigation

| # | Risk | Probability | Impact | Mitigation |
|---|---|---|---|---|
| R1 | Index bloat on `farm.milk_production` due to high insert rate | Medium | Medium | Quarterly partitioning limits partition size; regular `REINDEX CONCURRENTLY` |
| R2 | Redis cache stale data causing incorrect dashboard values | Medium | High | TTL-based expiry (5 min) plus active invalidation on ORM write/unlink |
| R3 | pgBouncer connection exhaustion under unexpected load spike | Low | High | `reserve_pool_size=5`, alerting at 80% utilisation, auto-scaling plan |
| R4 | WAL archive disk space exhaustion | Medium | Critical | Monitoring alert at 80% disk; automated cleanup of WAL segments older than retention |
| R5 | PITR restore fails due to corrupted WAL segment | Low | Critical | Daily backup verification; test restore on weekly schedule; off-site replication |
| R6 | RLS policy too restrictive, blocking legitimate Odoo ORM queries | Medium | Medium | Thorough testing with all application roles before production deployment |
| R7 | K6 load test reveals sub-optimal query plan under concurrency | Medium | Medium | Captured in Day 37 analysis; create targeted indexes or query rewrites |
| R8 | Legacy data migration introduces duplicate or orphan records | Medium | High | Pre-migration data cleansing; validation queries run post-ETL; rollback procedure |
| R9 | Redis outage causes session loss for all active users | Low | High | Redis persistence (AOF + RDB); fallback to database-backed sessions |
| R10 | Monitoring stack (Prometheus/Grafana) resource overhead | Low | Low | Lightweight exporters; separate monitoring container with resource limits |

---

## 7. Dependencies & Handoffs

### 7.1 Upstream Dependencies (from previous milestones)

| Dependency | Source Milestone | Status Required |
|---|---|---|
| PostgreSQL 16 deployed with all eight schemas | Milestone 1 | Complete |
| Core Odoo models (farm.animal, farm.milk_production, etc.) | Milestone 2 | Complete |
| Docker Compose base infrastructure | Milestone 1 | Complete |
| CI/CD pipeline operational | Milestone 3 | Complete |
| Data Dictionary (C-003) finalised | Milestone 2 | Complete |

### 7.2 Downstream Handoffs (to subsequent milestones)

| Deliverable | Target Milestone | Consumer |
|---|---|---|
| Optimised database with indexes | Milestone 5 (API Development) | Dev 1 — queries will leverage indexes |
| Redis caching layer and `CacheMixin` | Milestone 5 (API Development) | Dev 1 — API responses cached via decorator |
| pgBouncer connection pool | Milestone 5 (API Development) | Dev 1 — all API connections through pool |
| Grafana monitoring dashboards | Milestone 6 (Testing & QA) | Dev 2 — performance regression monitoring |
| K6 load test scripts | Milestone 6 (Testing & QA) | Dev 2 — baseline for regression testing |
| ETL migration scripts | Milestone 7 (Data Migration) | Dev 1 — production migration execution |
| OWL `DatabaseMonitor` component | Milestone 5 (Frontend Integration) | Dev 3 — embed in admin dashboard |
| Backup and recovery runbooks | Milestone 8 (Operations) | Dev 2 — operational procedures |
| Security hardening (RLS, SSL) | Milestone 9 (Security Audit) | All — security compliance verification |

### 7.3 External Dependencies

| Dependency | Owner | Risk if Unavailable |
|---|---|---|
| Legacy data CSV exports from Smart Dairy farm management | Farm Manager | ETL scripts tested with sample data only |
| SSL certificates for production | DevOps (Dev 2) | Self-signed used in dev/staging |
| Production server provisioning (8 cores, 32 GB RAM) | Infrastructure Team | Load test results may not reflect production |

---

## Appendix A: File Structure

```
smart_dairy/
├── addons/
│   ├── smart_dairy_cache/
│   │   ├── __init__.py
│   │   ├── __manifest__.py
│   │   ├── models/
│   │   │   ├── __init__.py
│   │   │   └── cache_mixin.py
│   │   └── utils/
│   │       ├── __init__.py
│   │       └── redis_client.py
│   ├── smart_dairy_core/
│   │   └── db/
│   │       ├── __init__.py
│   │       └── pool.py
│   └── smart_dairy_monitor/
│       └── static/src/components/
│           └── database_monitor/
│               ├── database_monitor.js
│               ├── database_monitor.xml
│               └── database_monitor.scss
├── deploy/
│   ├── postgres/
│   │   ├── postgresql.conf
│   │   ├── pg_hba.conf
│   │   └── indexes/
│   │       └── 004_create_indexes.sql
│   ├── pgbouncer/
│   │   ├── pgbouncer.ini
│   │   └── userlist.txt
│   ├── prometheus/
│   │   ├── prometheus.yml
│   │   └── postgres_exporter_queries.yaml
│   ├── grafana/
│   │   └── dashboards/
│   │       └── sd_db_monitor.json
│   ├── redis/
│   │   └── redis.conf
│   └── scripts/
│       ├── backup_database.sh
│       ├── pitr_restore.sh
│       └── vacuum_analyze.sh
├── migrations/
│   ├── 004_indexes.py
│   └── etl/
│       ├── legacy_migration.py
│       └── data/
│           ├── legacy_animals.csv
│           └── legacy_milk_production.csv
├── tests/
│   ├── load/
│   │   └── k6_odoo_xmlrpc.js
│   └── unit/
│       ├── test_cache_decorator.py
│       ├── test_session_store.py
│       └── test_etl_migration.py
└── docs/
    ├── baselines/
    │   └── day31_pg_stats.csv
    ├── runbooks/
    │   ├── database_operations.md
    │   ├── monitoring_alerting.md
    │   └── incident_response.md
    └── wireframes/
        └── db_monitor_v1.png
```

---

## Appendix B: Glossary

| Term | Definition |
|---|---|
| **AOF** | Append-Only File — Redis persistence mode that logs every write operation |
| **B-Tree** | Balanced tree index — default PostgreSQL index type for equality and range queries |
| **GIN** | Generalized Inverted Index — PostgreSQL index for composite types (JSONB, full-text) |
| **K6** | Open-source load testing tool by Grafana Labs |
| **LRU** | Least Recently Used — cache eviction policy |
| **ORM** | Object-Relational Mapping — Odoo's data access layer |
| **OWL** | Odoo Web Library — component framework for Odoo frontend |
| **pgBouncer** | Lightweight connection pooler for PostgreSQL |
| **PITR** | Point-In-Time Recovery — restoring a database to a specific timestamp using WAL archives |
| **RLS** | Row-Level Security — PostgreSQL feature restricting row access per user/role |
| **RPO** | Recovery Point Objective — maximum acceptable data loss measured in time |
| **RTO** | Recovery Time Objective — maximum acceptable downtime for recovery |
| **VU** | Virtual User — simulated concurrent user in load testing |
| **WAL** | Write-Ahead Log — PostgreSQL transaction log enabling crash recovery and replication |

---

*Document version 1.0 — Smart Dairy Digital Smart Portal + ERP — Milestone 4 of 10*
