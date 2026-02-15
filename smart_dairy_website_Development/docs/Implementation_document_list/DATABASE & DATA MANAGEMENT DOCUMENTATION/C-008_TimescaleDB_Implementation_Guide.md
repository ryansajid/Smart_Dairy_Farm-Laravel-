# SMART DAIRY LTD.
## TIMESCALEDB IMPLEMENTATION GUIDE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | C-008 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Database Architect |
| **Owner** | Database Administrator |
| **Reviewer** | CTO |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [TimescaleDB Architecture](#2-timescaledb-architecture)
3. [Installation & Configuration](#3-installation--configuration)
4. [Hypertable Design](#4-hypertable-design)
5. [Data Modeling](#5-data-modeling)
6. [Continuous Aggregates](#6-continuous-aggregates)
7. [Compression Strategy](#7-compression-strategy)
8. [Query Optimization](#8-query-optimization)
9. [Monitoring & Maintenance](#9-monitoring--maintenance)
10. [Troubleshooting](#10-troubleshooting)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This guide provides comprehensive instructions for implementing TimescaleDB to manage time-series IoT data for the Smart Dairy ERP system. It covers installation, configuration, data modeling, and optimization strategies.

### 1.2 Why TimescaleDB

```
┌─────────────────────────────────────────────────────────────────┐
│                    WHY TIMESCALEDB FOR SMART DAIRY               │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  CHALLENGE: 2M+ IoT data points/day by Year 2                   │
│                                                                  │
│  PostgreSQL Limitations:                                        │
│  • Table bloat with frequent inserts                            │
│  • Slow time-range queries                                      │
│  • Expensive DELETE for data retention                          │
│  • Limited partitioning support                                 │
│                                                                  │
│  TimescaleDB Advantages:                                        │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ ✓ Automatic partitioning (chunks)                       │   │
│  │ ✓ Optimized time-series queries                         │   │
│  │ ✓ Efficient data compression (90%+ reduction)           │   │
│  │ ✓ Continuous aggregates for real-time analytics         │   │
│  │ ✓ Native retention policies                             │   │
│  │ ✓ Full SQL compatibility                                │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                  │
│  Use Cases at Smart Dairy:                                      │
│  • IoT sensor data (temperature, humidity, milk flow)           │
│  • Animal activity monitoring                                   │
│  • Environmental conditions in barns                            │
│  • Equipment performance metrics                                │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Performance Targets

| Metric | Target | PostgreSQL | TimescaleDB |
|--------|--------|------------|-------------|
| **Insert Rate** | 50K+ rows/sec | 5K/sec | 300K/sec |
| **Time-range Query** | < 100ms | 5-10s | 50ms |
| **Storage Efficiency** | > 90% compression | N/A | 95%+ |
| **Data Retention** | Automated | Manual | Native |

---

## 2. TIMESCALEDB ARCHITECTURE

### 2.1 Hypertable Concept

```
┌─────────────────────────────────────────────────────────────────┐
│                    HYPERTABLE ARCHITECTURE                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Traditional Table (Monolithic)                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  sensor_data (100M rows)                                  │   │
│  │  ├─ Slow queries on time ranges                           │   │
│  │  ├─ Table bloat issues                                    │   │
│  │  └─ Expensive deletes                                     │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
│  TimescaleDB Hypertable (Partitioned)                            │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐   │
│  │ Chunk 1 │ │ Chunk 2 │ │ Chunk 3 │ │ Chunk 4 │ │ Chunk N │   │
│  │ (Day 1) │ │ (Day 2) │ │ (Day 3) │ │ (Day 4) │ │ (Day N) │   │
│  │ 100K    │ │ 100K    │ │ 100K    │ │ 100K    │ │ 100K    │   │
│  │ rows    │ │ rows    │ │ rows    │ │ rows    │ │ rows    │   │
│  └─────────┘ └─────────┘ └─────────┘ └─────────┘ └─────────┘   │
│       │           │           │           │           │         │
│       └───────────┴───────────┴───────────┴───────────┘         │
│                         │                                        │
│              ┌──────────┴──────────┐                            │
│              │  Continuous         │                            │
│              │  Aggregates         │                            │
│              │  (Hourly/Daily)     │                            │
│              └─────────────────────┘                            │
│                                                                  │
│  Benefits:                                                       │
│  • Query planner accesses only relevant chunks                  │
│  • Old chunks compressed for storage savings                    │
│  • Expired chunks dropped efficiently                           │
│  • Parallel query execution                                     │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Architecture Components

```
┌─────────────────────────────────────────────────────────────────┐
│                    TIMESCALEDB ARCHITECTURE                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                    SMART_DAIRY_ANALYTICS                   │   │
│  │                      (TimescaleDB Database)                │   │
│  │                                                            │   │
│  │  ┌────────────────────────────────────────────────────┐  │   │
│  │  │              HYPERTABLES                            │  │   │
│  │  │  ┌──────────────┐  ┌──────────────┐              │  │   │
│  │  │  │sensor_data   │  │milk_flow_data│              │  │   │
│  │  │  │              │  │              │              │  │   │
│  │  │  │• temperature │  │• flow rate   │              │  │   │
│  │  │  │• humidity    │  │• total vol   │              │  │   │
│  │  │  │• ph          │  │• duration    │              │  │   │
│  │  │  └──────────────┘  └──────────────┘              │  │   │
│  │  └────────────────────────────────────────────────────┘  │   │
│  │                            │                             │   │
│  │  ┌─────────────────────────┴──────────────────────────┐  │   │
│  │  │           CONTINUOUS AGGREGATES                     │  │   │
│  │  │  ┌──────────────┐  ┌──────────────┐              │  │   │
│  │  │  │sensor_hourly │  │milk_daily    │              │  │   │
│  │  │  │_summary      │  │_summary      │              │  │   │
│  │  │  └──────────────┘  └──────────────┘              │  │   │
│  │  └────────────────────────────────────────────────────┘  │   │
│  │                                                            │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│           ┌──────────────────┼──────────────────┐                │
│           │                  │                  │                │
│           ▼                  ▼                  ▼                │
│    ┌────────────┐    ┌────────────┐    ┌────────────┐           │
│    │  IoT       │    │  Analytics │    │  Alerts    │           │
│    │  Ingestion │    │  Dashboard │    │  Engine    │           │
│    │            │    │            │    │            │           │
│    │  MQTT/API  │    │  Grafana   │    │  Threshold │           │
│    │  50K/s     │    │  Reports   │    │  Rules     │           │
│    └────────────┘    └────────────┘    └────────────┘           │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. INSTALLATION & CONFIGURATION

### 3.1 Installation

```bash
# Method 1: Install via PostgreSQL packages (Recommended for Ubuntu/Debian)
# Add TimescaleDB repository
sudo apt-get update
sudo apt-get install -y gnupg postgresql-common apt-transport-https lsb-release wget
sudo /usr/share/postgresql-common/pgdg/apt.postgresql.org.sh

# Add TimescaleDB repository
echo "deb https://packagecloud.io/timescale/timescaledb/ubuntu/ $(lsb_release -c -s) main" | \
    sudo tee /etc/apt/sources.list.d/timescaledb.list

# Add TimescaleDB GPG key
wget --quiet -O - https://packagecloud.io/timescale/timescaledb/gpgkey | sudo apt-key add -

# Install TimescaleDB for PostgreSQL 16
sudo apt-get update
sudo apt-get install -y timescaledb-postgresql-16

# Tune PostgreSQL for TimescaleDB
sudo timescaledb-tune --quiet --yes

# Restart PostgreSQL
sudo systemctl restart postgresql

# Verify installation
sudo -u postgres psql -c "SELECT * FROM pg_available_extensions WHERE name = 'timescaledb';"
```

### 3.2 Database Setup

```sql
-- Create analytics database
CREATE DATABASE smart_dairy_analytics 
    WITH ENCODING = 'UTF8' 
    LC_COLLATE = 'en_US.UTF-8';

-- Connect to database\c smart_dairy_analytics

-- Enable TimescaleDB extension
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- Verify installation
SELECT timescaledb_pre_restore();  -- Should succeed if properly installed
SELECT timescaledb_post_restore();

-- Check version
SELECT extversion FROM pg_extension WHERE extname = 'timescaledb';
```

### 3.3 Configuration

```sql
-- timescaledb.conf settings (add to postgresql.conf)

-- Memory settings for time-series workloads
shared_preload_libraries = 'timescaledb,pg_stat_statements'

-- Chunk sizing (optimal: 25% of memory, 7-14 days of data per chunk)
-- For 500K rows/day @ 100 bytes/row = 50MB/day
-- Target chunk: ~1GB → chunk_time_interval = 20 days

-- Parallel query settings
max_parallel_workers_per_gather = 4
max_parallel_workers = 8
max_worker_processes = 8

-- Autovacuum (less critical for append-only time-series)
autovacuum_vacuum_scale_factor = 0.1
autovacuum_analyze_scale_factor = 0.05
```

---

## 4. HYPERTABLE DESIGN

### 4.1 Sensor Data Hypertable

```sql
-- Main sensor data hypertable
CREATE TABLE iot.sensor_data (
    time TIMESTAMPTZ NOT NULL,
    device_id VARCHAR(100) NOT NULL,
    sensor_type VARCHAR(50) NOT NULL,
    
    -- Location references
    barn_id BIGINT,
    animal_id BIGINT,
    
    -- Measurement
    value DECIMAL(10,3) NOT NULL,
    unit VARCHAR(20),
    
    -- Quality indicators
    quality_score DECIMAL(3,2),  -- 0.0 to 1.0
    is_anomaly BOOLEAN DEFAULT FALSE,
    
    -- Metadata (flexible schema)
    metadata JSONB DEFAULT '{}',
    
    -- Processing status
    processed BOOLEAN DEFAULT FALSE,
    processed_at TIMESTAMP,
    
    -- Audit
    received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Convert to hypertable
-- Chunk size: 1 day (optimal for IoT data)
SELECT create_hypertable(
    'iot.sensor_data',
    'time',
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Create indexes (optimized for time-series queries)
CREATE INDEX idx_sensor_device_time 
    ON iot.sensor_data(device_id, time DESC);

CREATE INDEX idx_sensor_type_time 
    ON iot.sensor_data(sensor_type, time DESC);

CREATE INDEX idx_sensor_barn_time 
    ON iot.sensor_data(barn_id, time DESC) 
    WHERE barn_id IS NOT NULL;

CREATE INDEX idx_sensor_animal_time 
    ON iot.sensor_data(animal_id, time DESC) 
    WHERE animal_id IS NOT NULL;

CREATE INDEX idx_sensor_anomaly 
    ON iot.sensor_data(time DESC) 
    WHERE is_anomaly = TRUE;

-- Partial index for unprocessed data (for batch processing)
CREATE INDEX idx_sensor_unprocessed 
    ON iot.sensor_data(time) 
    WHERE processed = FALSE;
```

### 4.2 Milk Flow Data Hypertable

```sql
-- Specialized hypertable for milk flow meters
CREATE TABLE iot.milk_flow_data (
    time TIMESTAMPTZ NOT NULL,
    device_id VARCHAR(100) NOT NULL,
    animal_id BIGINT NOT NULL,
    
    -- Flow measurements
    flow_rate_lpm DECIMAL(6,2),  -- Liters per minute
    total_volume DECIMAL(8,3),   -- Total liters this session
    
    -- Session info
    session_type VARCHAR(20),    -- 'morning', 'evening', 'special'
    duration_seconds INTEGER,
    
    -- Quality metrics
    temperature_c DECIMAL(4,1),
    conductivity DECIMAL(6,2),
    
    -- Device status
    device_status VARCHAR(50),
    battery_level INTEGER,
    
    -- Audit
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SELECT create_hypertable(
    'iot.milk_flow_data',
    'time',
    chunk_time_interval => INTERVAL '1 day'
);

CREATE INDEX idx_milk_flow_animal_time 
    ON iot.milk_flow_data(animal_id, time DESC);

CREATE INDEX idx_milk_flow_session 
    ON iot.milk_flow_data(session_type, time DESC);
```

### 4.3 Hypertable Best Practices

| Aspect | Recommendation | Rationale |
|--------|----------------|-----------|
| **Chunk Time** | 1 day for high-frequency data | Balance between chunk count and size |
| **Chunk Size** | Target 500MB - 2GB per chunk | Optimal for compression and queries |
| **Partitioning** | Time only (no space partitioning) | Simpler, sufficient for our scale |
| **Indexes** | Time-first composite indexes | Matches query patterns |
| **Data Types** | Use TIMESTAMPTZ not TIMESTAMP | Handles timezone correctly |

---

## 5. DATA MODELING

### 5.1 IoT Data Model

```
┌─────────────────────────────────────────────────────────────────┐
│                    IOT DATA MODEL                                │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  DEVICE (iot.device)                                            │
│  ├── device_id (PK)                                             │
│  ├── device_type (sensor, gateway, controller)                  │
│  ├── barn_id (FK)                                               │
│  ├── animal_id (FK) - for animal-worn sensors                   │
│  ├── protocol (mqtt, http, lora)                                │
│  └── status (active, inactive, maintenance)                     │
│                                                                  │
│  SENSOR_DATA (iot.sensor_data) - Hypertable                     │
│  ├── time (partition key)                                       │
│  ├── device_id (FK)                                             │
│  ├── sensor_type (temperature, humidity, ph, activity)          │
│  ├── value                                                      │
│  ├── unit                                                       │
│  ├── quality_score                                              │
│  ├── is_anomaly                                                 │
│  └── metadata (JSONB for flexibility)                           │
│                                                                  │
│  SENSOR_TYPE ENUM                                               │
│  ├── temperature (barn/environment)                             │
│  ├── humidity                                                   │
│  ├── ph (milk quality)                                          │
│  ├── conductivity                                               │
│  ├── milk_flow                                                  │
│  ├── activity (animal movement)                                 │
│  ├── rumination                                                 │
│  ├── weight                                                     │
│  └── heartbeat                                                  │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 5.2 Metadata Schema (JSONB)

```sql
-- Example metadata structures by sensor type

-- Temperature sensor
{
    "location": "barn_a_section_3",
    "height_meters": 2.5,
    "calibration_date": "2024-01-15",
    "calibration_offset": 0.2
}

-- Animal activity sensor
{
    "collar_id": "CL-12345",
    "animal_tag": "HF-001",
    "battery_voltage": 3.7,
    "signal_strength": -75
}

-- Milk flow sensor
{
    "stall_number": 5,
    "operator_id": 42,
    "cleaning_cycle": 156,
    "calibration_liters": 20.0
}

-- Query metadata examples
-- Find sensors needing calibration
SELECT device_id, metadata->>'calibration_date'
FROM iot.sensor_data
WHERE metadata->>'calibration_date' < '2024-01-01'::text;

-- Aggregate by barn section
SELECT 
    metadata->>'location' as location,
    AVG(value) as avg_temp
FROM iot.sensor_data
WHERE sensor_type = 'temperature'
  AND time > NOW() - INTERVAL '1 day'
GROUP BY metadata->>'location';
```

---

## 6. CONTINUOUS AGGREGATES

### 6.1 Hourly Sensor Summary

```sql
-- Continuous aggregate: Hourly sensor statistics
CREATE MATERIALIZED VIEW iot.sensor_hourly_summary
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 hour', time) as bucket,
    device_id,
    sensor_type,
    barn_id,
    
    -- Statistics
    AVG(value) as avg_value,
    MIN(value) as min_value,
    MAX(value) as max_value,
    STDDEV(value) as stddev_value,
    COUNT(*) as sample_count,
    
    -- Quality metrics
    AVG(quality_score) as avg_quality,
    COUNT(*) FILTER (WHERE is_anomaly) as anomaly_count,
    
    -- First and last values (for interpolation)
    FIRST(value, time) as first_value,
    LAST(value, time) as last_value
    
FROM iot.sensor_data
GROUP BY bucket, device_id, sensor_type, barn_id;

-- Policy: Refresh recent data every 15 minutes
SELECT add_continuous_aggregate_policy('iot.sensor_hourly_summary',
    start_offset => INTERVAL '1 month',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '15 minutes'
);

-- Retention: Keep 2 years of raw, 7 years of hourly aggregates
SELECT add_retention_policy('iot.sensor_data', INTERVAL '2 years');
SELECT add_retention_policy('iot.sensor_hourly_summary', INTERVAL '7 years');
```

### 6.2 Daily Milk Production Aggregate

```sql
-- Daily milk production summary per animal
CREATE MATERIALIZED VIEW iot.milk_daily_summary
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 day', time) as day,
    animal_id,
    
    -- Session summaries
    COUNT(DISTINCT session_type) as sessions_count,
    SUM(total_volume) FILTER (WHERE session_type = 'morning') as morning_volume,
    SUM(total_volume) FILTER (WHERE session_type = 'evening') as evening_volume,
    SUM(total_volume) as total_volume,
    
    -- Flow statistics
    AVG(flow_rate_lpm) as avg_flow_rate,
    MAX(flow_rate_lpm) as peak_flow_rate,
    AVG(duration_seconds)/60 as avg_duration_minutes,
    
    -- Quality
    AVG(temperature_c) as avg_temperature,
    AVG(conductivity) as avg_conductivity
    
FROM iot.milk_flow_data
GROUP BY day, animal_id;

-- Refresh policy
SELECT add_continuous_aggregate_policy('iot.milk_daily_summary',
    start_offset => INTERVAL '1 month',
    end_offset => INTERVAL '1 day',
    schedule_interval => INTERVAL '1 hour'
);

-- Long retention for production records
SELECT add_retention_policy('iot.milk_daily_summary', INTERVAL '7 years');
```

### 6.3 Barn Environment Aggregate

```sql
-- Barn environment conditions (hourly)
CREATE MATERIALIZED VIEW iot.barn_environment_hourly
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 hour', time) as hour,
    barn_id,
    
    -- Temperature
    AVG(value) FILTER (WHERE sensor_type = 'temperature') as avg_temp,
    MIN(value) FILTER (WHERE sensor_type = 'temperature') as min_temp,
    MAX(value) FILTER (WHERE sensor_type = 'temperature') as max_temp,
    
    -- Humidity
    AVG(value) FILTER (WHERE sensor_type = 'humidity') as avg_humidity,
    
    -- Count active sensors
    COUNT(DISTINCT device_id) as active_sensors,
    
    -- Alert conditions
    COUNT(*) FILTER (WHERE is_anomaly) as alert_count
    
FROM iot.sensor_data
WHERE sensor_type IN ('temperature', 'humidity')
GROUP BY hour, barn_id;

SELECT add_continuous_aggregate_policy('iot.barn_environment_hourly',
    start_offset => INTERVAL '3 months',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '15 minutes'
);
```

---

## 7. COMPRESSION STRATEGY

### 7.1 Compression Configuration

```sql
-- Enable compression on sensor_data hypertable
ALTER TABLE iot.sensor_data SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'device_id, sensor_type',
    timescaledb.compress_orderby = 'time DESC'
);

-- Compression policy: Compress chunks older than 7 days
SELECT add_compression_policy('iot.sensor_data', INTERVAL '7 days');

-- For milk flow data (less compressible due to variable values)
ALTER TABLE iot.milk_flow_data SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'animal_id, session_type',
    timescaledb.compress_orderby = 'time DESC'
);

SELECT add_compression_policy('iot.milk_flow_data', INTERVAL '14 days');
```

### 7.2 Compression Settings Explained

| Setting | Purpose | Recommended Value |
|---------|---------|-------------------|
| **compress_segmentby** | Group similar data for better compression | High-cardinality columns (device_id) |
| **compress_orderby** | Order within compressed segment | time DESC (recent first) |
| **Compression Age** | When to compress | 7-14 days (balance query vs storage) |

### 7.3 Compression Statistics

```sql
-- Monitor compression ratios
SELECT
    hypertable_name,
    chunk_name,
    pg_size_pretty(before_compression_total_bytes) as before,
    pg_size_pretty(after_compression_total_bytes) as after,
    round(
        (1 - after_compression_total_bytes::numeric / 
         before_compression_total_bytes) * 100, 2
    ) as compression_ratio
FROM timescaledb_information.compression_settings cs
JOIN timescaledb_information.chunks ch ON cs.hypertable_name = ch.hypertable_name
WHERE compression_enabled = TRUE
ORDER BY before_compression_total_bytes DESC;

-- Expected results:
-- sensor_data: ~95% compression (repetitive values)
-- milk_flow_data: ~80% compression (more variable)
```

---

## 8. QUERY OPTIMIZATION

### 8.1 Time-Series Query Patterns

```sql
-- Pattern 1: Recent data (uses last few chunks)
SELECT * FROM iot.sensor_data
WHERE time > NOW() - INTERVAL '1 hour'
  AND device_id = 'TEMP-001'
ORDER BY time DESC;

-- Pattern 2: Time range query (hits specific chunks)
SELECT 
    time_bucket('1 hour', time) as hour,
    AVG(value) as avg_temp
FROM iot.sensor_data
WHERE time BETWEEN '2024-01-01' AND '2024-01-31'
  AND sensor_type = 'temperature'
  AND barn_id = 1
GROUP BY hour
ORDER BY hour;

-- Pattern 3: Continuous aggregate query (much faster)
SELECT * FROM iot.sensor_hourly_summary
WHERE bucket BETWEEN '2024-01-01' AND '2024-01-31'
  AND sensor_type = 'temperature'
  AND barn_id = 1
ORDER BY bucket;

-- Pattern 4: Real-time anomaly detection
SELECT 
    device_id,
    sensor_type,
    value,
    time,
    (value - avg_value) / NULLIF(stddev_value, 0) as z_score
FROM iot.sensor_data s
JOIN LATERAL (
    SELECT AVG(value) as avg_value, STDDEV(value) as stddev_value
    FROM iot.sensor_data
    WHERE device_id = s.device_id
      AND sensor_type = s.sensor_type
      AND time > NOW() - INTERVAL '24 hours'
) stats ON TRUE
WHERE s.time > NOW() - INTERVAL '5 minutes'
  AND ABS((value - avg_value) / NULLIF(stddev_value, 0)) > 3;
```

### 8.2 Performance Tips

| Tip | Implementation | Impact |
|-----|----------------|--------|
| **Always include time filter** | `WHERE time > NOW() - INTERVAL 'X'` | Chunk exclusion |
| **Use continuous aggregates** | Query materialized views | 10-100x faster |
| **Avoid SELECT *** | Select specific columns | Less I/O |
| **Use partial indexes** | `WHERE processed = FALSE` | Faster targeted queries |
| **Batch inserts** | Use COPY or multi-row INSERT | Higher throughput |

---

## 9. MONITORING & MAINTENANCE

### 9.1 Key Metrics to Monitor

```sql
-- Chunk health monitoring
SELECT 
    hypertable_schema,
    hypertable_name,
    chunk_name,
    range_start,
    range_end,
    pg_size_pretty(pg_total_relation_size(chunk_schema || '.' || chunk_name)) as size,
    is_compressed,
    chunk_creation_time
FROM timescaledb_information.chunks
ORDER BY range_start DESC
LIMIT 20;

-- Hypertable statistics
SELECT 
    hypertable_name,
    num_dimensions,
    num_chunks,
    compression_enabled,
    retention_enabled
FROM timescaledb_information.hypertables;

-- Continuous aggregate health
SELECT 
    view_name,
    refresh_lag,
    last_run_started_at,
    last_run_status,
    materialization_hypertable_name
FROM timescaledb_information.continuous_aggregates;
```

### 9.2 Maintenance Procedures

```sql
-- Weekly: Analyze hypertables
ANALYZE iot.sensor_data;
ANALYZE iot.milk_flow_data;

-- Monthly: Reindex if needed
REINDEX INDEX CONCURRENTLY idx_sensor_device_time;

-- Quarterly: Check for data gaps
SELECT 
    device_id,
    sensor_type,
    time_bucket('1 day', time) as day,
    COUNT(*) as readings
FROM iot.sensor_data
WHERE time > NOW() - INTERVAL '30 days'
GROUP BY device_id, sensor_type, day
HAVING COUNT(*) < 24  -- Expect at least hourly readings
ORDER BY day DESC, readings;

-- Check compression status
SELECT 
    count(*) as total_chunks,
    count(*) FILTER (WHERE is_compressed) as compressed_chunks,
    round(100.0 * count(*) FILTER (WHERE is_compressed) / count(*), 2) as compression_pct
FROM timescaledb_information.chunks
WHERE hypertable_name = 'sensor_data';
```

### 9.3 Alerting Rules

```yaml
# prometheus_alert_rules.yml
groups:
  - name: timescaledb_alerts
    rules:
      - alert: TimescaleDBHighCompressionLag
        expr: |
          timescaledb_compression_stats_compression_lag_seconds > 86400
        for: 1h
        labels:
          severity: warning
        annotations:
          summary: "TimescaleDB compression is lagging"
          
      - alert: TimescaleDBContinuousAggregateStale
        expr: |
          time() - timescaledb_cagg_stats_last_run_completed_at > 3600
        for: 30m
        labels:
          severity: critical
        annotations:
          summary: "Continuous aggregate refresh is stale"
          
      - alert: TimescaleDBChunkTooLarge
        expr: |
          timescaledb_chunk_stats_total_bytes / 1024 / 1024 / 1024 > 2
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "TimescaleDB chunk exceeds 2GB"
```

---

## 10. TROUBLESHOOTING

### 10.1 Common Issues

| Issue | Symptom | Solution |
|-------|---------|----------|
| **Slow queries** | Queries on large time ranges are slow | Use continuous aggregates; add time filters |
| **High storage** | Disk filling up | Enable compression; check retention policies |
| **Insert failures** | Write errors during peak load | Batch inserts; increase chunk size |
| **Stale aggregates** | Dashboard shows old data | Check continuous aggregate policies |
| **Lock contention** | Concurrent insert/query conflicts | Use smaller transactions; avoid long queries |

### 10.2 Diagnostic Queries

```sql
-- Check query plan for chunk exclusion
EXPLAIN (ANALYZE, BUFFERS)
SELECT * FROM iot.sensor_data
WHERE time > NOW() - INTERVAL '1 day'
  AND device_id = 'DEV-001';
-- Look for: "Chunks: N/N" in plan (should exclude old chunks)

-- Find slow queries
SELECT 
    query,
    calls,
    mean_exec_time,
    total_exec_time
FROM pg_stat_statements
WHERE query LIKE '%sensor_data%'
ORDER BY mean_exec_time DESC
LIMIT 10;

-- Check for lock waits
SELECT 
    blocked_locks.pid AS blocked_pid,
    blocked_activity.usename AS blocked_user,
    blocking_locks.pid AS blocking_pid,
    blocking_activity.usename AS blocking_user,
    blocked_activity.query AS blocked_statement
FROM pg_catalog.pg_locks blocked_locks
JOIN pg_catalog.pg_stat_activity blocked_activity ON blocked_activity.pid = blocked_locks.pid
JOIN pg_catalog.pg_locks blocking_locks ON blocking_locks.locktype = blocked_locks.locktype
JOIN pg_catalog.pg_stat_activity blocking_activity ON blocking_activity.pid = blocking_locks.pid
WHERE NOT blocked_locks.granted;
```

---

## 11. APPENDICES

### Appendix A: Data Ingestion Examples

```python
# Python data ingestion using psycopg2
import psycopg2
from psycopg2.extras import execute_values

def bulk_insert_sensor_data(conn, data_batch):
    """Efficient bulk insert of sensor readings"""
    
    query = """
        INSERT INTO iot.sensor_data 
        (time, device_id, sensor_type, barn_id, value, unit, quality_score)
        VALUES %s
        ON CONFLICT (time, device_id) DO NOTHING
    """
    
    with conn.cursor() as cur:
        execute_values(cur, query, data_batch, page_size=1000)
    conn.commit()

# Example batch format
batch = [
    ('2024-01-31 10:00:00+00', 'TEMP-001', 'temperature', 1, 24.5, 'celsius', 0.95),
    ('2024-01-31 10:00:00+00', 'HUM-001', 'humidity', 1, 65.0, 'percent', 0.92),
    # ... more rows
]
```

### Appendix B: Capacity Planning

| Metric | Year 1 | Year 2 | Year 5 |
|--------|--------|--------|--------|
| **Devices** | 50 | 200 | 500 |
| **Data Points/Day** | 500K | 2M | 5M |
| **Raw Data/Day** | 50 MB | 200 MB | 500 MB |
| **Compressed/Year** | ~2 GB | ~8 GB | ~20 GB |
| **Aggregates/Year** | ~500 MB | ~2 GB | ~5 GB |
| **Total Storage** | ~3 GB | ~12 GB | ~30 GB |

### Appendix C: Quick Reference

```sql
-- Essential commands quick reference

-- Create hypertable
SELECT create_hypertable('table_name', 'time_column');

-- Enable compression
ALTER TABLE table_name SET (timescaledb.compress);
SELECT add_compression_policy('table_name', INTERVAL '7 days');

-- Set retention
SELECT add_retention_policy('table_name', INTERVAL '2 years');

-- Create continuous aggregate
CREATE MATERIALIZED VIEW agg_name WITH (timescaledb.continuous) AS ...;
SELECT add_continuous_aggregate_policy('agg_name', ...);

-- Manual chunk operations
SELECT compress_chunk('_timescaledb_internal._hyper_1_1_chunk');
SELECT decompress_chunk('_timescaledb_internal._hyper_1_1_chunk');
SELECT drop_chunks('table_name', INTERVAL '6 months');
```

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Database Architect | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
