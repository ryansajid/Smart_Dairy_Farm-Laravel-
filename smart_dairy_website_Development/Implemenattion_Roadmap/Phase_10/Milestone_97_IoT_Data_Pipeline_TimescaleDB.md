# Milestone 97: IoT Data Pipeline & TimescaleDB

## Smart Dairy Digital Smart Portal + ERP System (Odoo 19 CE)

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P10-M97-v1.0 |
| **Milestone** | 97 of 100 |
| **Phase** | Phase 10: Commerce - IoT Integration Core |
| **Days** | 711-720 (10 working days) |
| **Duration** | 2 weeks |
| **Developers** | 3 Full Stack Developers |
| **Total Effort** | 240 person-hours |
| **Status** | Planned |
| **Version** | 1.0 |
| **Last Updated** | 2026-02-04 |

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 97 establishes the high-throughput IoT data pipeline and TimescaleDB time-series storage infrastructure. This milestone creates the backbone for all IoT data ingestion, processing, storage, and query optimization to handle millions of data points from dairy farm sensors and devices.

### 1.2 Scope

This milestone covers:
- FastAPI-based data ingestion service
- TimescaleDB hypertable schema design
- Continuous aggregates for analytics
- Data compression and retention policies
- Stream processing with Apache Flink
- Data validation and quality layer
- Query optimization and indexing
- Data export and archival

### 1.3 Key Outcomes

| Outcome | Success Measure |
|---------|-----------------|
| Data Ingestion | 50,000+ rows/second peak capacity |
| Latency | <5 second end-to-end latency |
| Storage Efficiency | 10:1 compression ratio |
| Query Performance | <100ms for recent data queries |
| Data Retention | 90 days raw, 2 years aggregates |
| Availability | 99.9% uptime |

---

## 2. Objectives

| # | Objective | Priority | Measurable Target |
|---|-----------|----------|-------------------|
| O1 | Implement FastAPI data ingestion service | Critical | 50K rows/second throughput |
| O2 | Design and deploy TimescaleDB hypertable schema | Critical | All IoT data types covered |
| O3 | Create continuous aggregates for hourly/daily analytics | Critical | Automatic refresh |
| O4 | Implement data compression policies | High | 10:1 compression ratio |
| O5 | Set up retention policies | High | 90 days raw, 2 years aggregates |
| O6 | Build stream processing with Flink | Medium | Real-time anomaly detection |
| O7 | Create data validation layer | High | 99.9% data quality |
| O8 | Optimize query performance | High | <100ms response time |

---

## 3. Key Deliverables

| # | Deliverable | Description | Owner | Priority |
|---|-------------|-------------|-------|----------|
| D1 | Data Ingestion Service | FastAPI high-throughput service | Dev 2 | Critical |
| D2 | TimescaleDB Schema | Hypertable definitions | Dev 1 | Critical |
| D3 | Continuous Aggregates | Hourly/daily materialized views | Dev 1 | Critical |
| D4 | Compression Policies | TimescaleDB compression config | Dev 1 | High |
| D5 | Retention Policies | Data lifecycle management | Dev 1 | High |
| D6 | Flink Stream Processing | Real-time data processing | Dev 2 | Medium |
| D7 | Data Validation Service | Schema validation, quality checks | Dev 2 | High |
| D8 | Query Optimization | Indexes, query tuning | Dev 1 | High |
| D9 | Data Export Service | Bulk export functionality | Dev 2 | Medium |
| D10 | Pipeline Monitoring Dashboard | Grafana dashboards | Dev 3 | High |
| D11 | Data Quality Dashboard | Quality metrics view | Dev 3 | Medium |
| D12 | Pipeline Documentation | Technical documentation | All | High |

---

## 4. Prerequisites

### 4.1 From Previous Phases
| Prerequisite | Phase/Milestone | Status |
|--------------|-----------------|--------|
| PostgreSQL 16 database | Phase 1 | Complete |
| Docker infrastructure | Phase 1 | Complete |
| Kubernetes cluster | Phase 1 | Complete |
| Redis cache | Phase 6 | Complete |

### 4.2 From Phase 10
| Prerequisite | Milestone | Status |
|--------------|-----------|--------|
| MQTT broker | M91 | Complete |
| TimescaleDB initial setup | M91 | Complete |
| Environmental readings table | M94 | Complete |
| Activity data table | M95 | Complete |
| Feed consumption table | M96 | Complete |

---

## 5. Requirement Traceability

### 5.1 RFP Requirements
| RFP ID | Requirement | Implementation |
|--------|-------------|----------------|
| IOT-007 | Time-series data pipeline | Full implementation |
| IOT-007.1 | High-throughput ingestion | FastAPI service |
| IOT-007.2 | Data aggregation | Continuous aggregates |
| IOT-007.3 | Long-term storage | Retention policies |
| IOT-007.4 | Query performance | Index optimization |

### 5.2 SRS Technical Requirements
| SRS ID | Technical Requirement | Implementation |
|--------|----------------------|----------------|
| SRS-IOT-003 | 50K+ rows/sec ingestion | FastAPI + batch inserts |
| SRS-IOT-002 | <5 second latency | Stream processing |
| SRS-IOT-018 | 2+ year data retention | Tiered storage |
| SRS-IOT-019 | Query <100ms | Index optimization |

---

## 6. Day-by-Day Development Plan

### Day 711: TimescaleDB Schema Design

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design unified IoT data schema | Schema document |
| 2-5 | Create master hypertable definitions | SQL scripts |
| 5-7 | Implement partitioning strategy | Partition config |
| 7-8 | Document schema design | Schema docs |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Setup FastAPI project structure | Project scaffold |
| 3-6 | Design ingestion API endpoints | API design |
| 6-8 | Implement connection pooling | Connection pool |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-2 | Design pipeline monitoring wireframes | Figma mockups |
| 2-5 | Setup Grafana dashboards | Dashboard config |
| 5-8 | Create ingestion metrics panels | Grafana panels |

---

### Day 712: Hypertable Implementation

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create sensor_data hypertable | SQL migration |
| 3-5 | Create milk_production_realtime hypertable | SQL migration |
| 5-8 | Create animal_metrics hypertable | SQL migration |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement batch insert endpoints | FastAPI routes |
| 3-5 | Create async database writer | Async writer |
| 5-8 | Implement backpressure handling | Flow control |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create throughput monitoring panel | Grafana panel |
| 3-6 | Implement latency tracking | Latency metrics |
| 6-8 | Build error rate dashboard | Error dashboard |

---

### Day 713: Continuous Aggregates

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create 15-minute aggregates | Materialized views |
| 3-5 | Create hourly aggregates | Hourly views |
| 5-8 | Create daily aggregates | Daily views |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement refresh policies | Refresh config |
| 3-5 | Create aggregate query APIs | Query APIs |
| 5-8 | Build aggregate verification tests | Test suite |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create aggregate status panel | Status dashboard |
| 3-6 | Implement refresh monitoring | Refresh metrics |
| 6-8 | Build data lag indicators | Lag display |

---

### Day 714: Compression & Retention

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement compression policies | Compression config |
| 3-5 | Create retention policies | Retention config |
| 5-8 | Build data archival service | Archive service |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement chunk management | Chunk manager |
| 3-5 | Create data lifecycle jobs | Cron jobs |
| 5-8 | Build compression monitoring | Monitoring |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create storage metrics panel | Storage dashboard |
| 3-6 | Implement compression stats | Compression UI |
| 6-8 | Build retention calendar view | Calendar view |

---

### Day 715: Data Validation Layer

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Design validation rule engine | Rule engine |
| 3-5 | Implement schema validation | Schema validator |
| 5-8 | Create data quality metrics | Quality metrics |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement JSON schema validation | JSON validator |
| 3-5 | Create outlier detection | Outlier detector |
| 5-8 | Build data correction service | Correction service |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create data quality dashboard | Quality dashboard |
| 3-6 | Implement validation error view | Error view |
| 6-8 | Build quality trend charts | Trend charts |

---

### Day 716: Stream Processing (Flink)

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Setup Apache Flink cluster | Flink deployment |
| 3-6 | Create MQTT source connector | Source connector |
| 6-8 | Implement TimescaleDB sink | Sink connector |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create streaming pipeline | Flink job |
| 3-5 | Implement windowed aggregations | Window functions |
| 5-8 | Build anomaly detection job | Anomaly job |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create Flink monitoring dashboard | Flink dashboard |
| 3-6 | Implement job status tracking | Job status UI |
| 6-8 | Build backpressure indicators | Backpressure UI |

---

### Day 717: Query Optimization

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Analyze query patterns | Query analysis |
| 3-5 | Create optimized indexes | Index creation |
| 5-8 | Implement query caching | Cache layer |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Build query optimization service | Query optimizer |
| 3-5 | Create query plan analyzer | Plan analyzer |
| 5-8 | Implement slow query logging | Query logging |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create query performance panel | Performance UI |
| 3-6 | Implement slow query display | Slow query view |
| 6-8 | Build index usage dashboard | Index dashboard |

---

### Day 718: Data Export & Integration

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create bulk export service | Export service |
| 3-5 | Implement Odoo data sync | Sync service |
| 5-8 | Build export scheduling | Scheduler |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Implement CSV/JSON export | Export formats |
| 3-5 | Create API export endpoints | Export APIs |
| 5-8 | Build data download manager | Download service |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Create export wizard OWL | Export wizard |
| 3-6 | Implement export progress UI | Progress UI |
| 6-8 | Build download history view | History view |

---

### Day 719: Testing & Performance

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write unit tests | Unit tests |
| 3-5 | Create load tests | Load test suite |
| 5-8 | Perform query benchmarks | Benchmark results |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Test ingestion throughput | Throughput tests |
| 3-5 | Test data integrity | Integrity tests |
| 5-8 | Performance tuning | Optimization |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Dashboard testing | Dashboard tests |
| 3-5 | Load testing UI | UI load tests |
| 5-8 | End-to-end testing | E2E tests |

---

### Day 720: Documentation & Demo

#### Dev 1 (Backend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write schema documentation | Schema docs |
| 3-5 | Create query cookbook | Query guide |
| 5-8 | Final bug fixes | Stable release |

#### Dev 2 (Full-Stack) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write API documentation | API docs |
| 3-5 | Create deployment guide | Deployment guide |
| 5-8 | Prepare demo environment | Demo setup |

#### Dev 3 (Frontend Lead) - 8 hours
| Hours | Task | Deliverable |
|-------|------|-------------|
| 0-3 | Write dashboard user guide | User guide |
| 3-5 | Create demo scenarios | Demo materials |
| 5-8 | Conduct milestone demo | Demo presentation |

---

## 7. Technical Specifications

### 7.1 Master TimescaleDB Schema

```sql
-- Master IoT Data Pipeline Schema
-- smart_dairy_iot/data/timescaledb_master_schema.sql

-- Enable extensions
CREATE EXTENSION IF NOT EXISTS timescaledb;
CREATE EXTENSION IF NOT EXISTS pg_stat_statements;

-- Create IoT schema
CREATE SCHEMA IF NOT EXISTS iot;

-- =====================================================
-- MASTER SENSOR DATA HYPERTABLE
-- Central table for all sensor readings
-- =====================================================
CREATE TABLE IF NOT EXISTS iot.sensor_data (
    time TIMESTAMPTZ NOT NULL,

    -- Device identification
    device_uid VARCHAR(64) NOT NULL,
    device_type VARCHAR(32) NOT NULL,  -- milk_meter, temp_sensor, collar, dispenser
    farm_id INTEGER NOT NULL,

    -- Measurement
    metric_name VARCHAR(64) NOT NULL,  -- temperature, activity, rumination, yield
    metric_value DOUBLE PRECISION NOT NULL,
    metric_unit VARCHAR(16),

    -- Quality
    data_quality VARCHAR(16) DEFAULT 'valid',
    is_interpolated BOOLEAN DEFAULT FALSE,

    -- Context
    animal_id INTEGER,
    zone_id INTEGER,
    session_id VARCHAR(64),

    -- Device health
    battery_level SMALLINT,
    signal_strength SMALLINT,

    -- Metadata (JSONB for flexibility)
    metadata JSONB DEFAULT '{}'::jsonb
);

-- Convert to hypertable with 1-day chunks
SELECT create_hypertable(
    'iot.sensor_data',
    'time',
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Create composite indexes for common query patterns
CREATE INDEX IF NOT EXISTS idx_sensor_device_time
    ON iot.sensor_data (device_uid, time DESC);

CREATE INDEX IF NOT EXISTS idx_sensor_farm_type_time
    ON iot.sensor_data (farm_id, device_type, time DESC);

CREATE INDEX IF NOT EXISTS idx_sensor_animal_metric_time
    ON iot.sensor_data (animal_id, metric_name, time DESC)
    WHERE animal_id IS NOT NULL;

CREATE INDEX IF NOT EXISTS idx_sensor_zone_metric_time
    ON iot.sensor_data (zone_id, metric_name, time DESC)
    WHERE zone_id IS NOT NULL;

-- BRIN index for time-based range scans
CREATE INDEX IF NOT EXISTS idx_sensor_time_brin
    ON iot.sensor_data USING BRIN (time) WITH (pages_per_range = 32);

-- =====================================================
-- MILK PRODUCTION REALTIME HYPERTABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS iot.milk_production_realtime (
    time TIMESTAMPTZ NOT NULL,

    -- Identification
    milk_meter_id INTEGER NOT NULL,
    animal_id INTEGER NOT NULL,
    farm_id INTEGER NOT NULL,
    session_id VARCHAR(64) NOT NULL,

    -- Production metrics
    yield_kg DOUBLE PRECISION NOT NULL,
    duration_seconds INTEGER,
    flow_rate_kg_min DOUBLE PRECISION,

    -- Quality metrics
    conductivity_ms DOUBLE PRECISION,
    temperature_c DOUBLE PRECISION,
    blood_detected BOOLEAN DEFAULT FALSE,

    -- Fat and protein (if available)
    fat_percent DOUBLE PRECISION,
    protein_percent DOUBLE PRECISION,
    lactose_percent DOUBLE PRECISION,

    -- Health indicators
    somatic_cell_count INTEGER,
    mastitis_risk_score DOUBLE PRECISION,

    -- Session info
    session_start TIMESTAMPTZ,
    session_end TIMESTAMPTZ,
    milking_type VARCHAR(16),  -- morning, evening, midday

    -- Device health
    meter_status VARCHAR(16)
);

SELECT create_hypertable(
    'iot.milk_production_realtime',
    'time',
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

CREATE INDEX IF NOT EXISTS idx_milk_animal_time
    ON iot.milk_production_realtime (animal_id, time DESC);

CREATE INDEX IF NOT EXISTS idx_milk_farm_time
    ON iot.milk_production_realtime (farm_id, time DESC);

CREATE INDEX IF NOT EXISTS idx_milk_session
    ON iot.milk_production_realtime (session_id);

-- =====================================================
-- CONTINUOUS AGGREGATES
-- =====================================================

-- 15-minute sensor aggregates
CREATE MATERIALIZED VIEW IF NOT EXISTS iot.sensor_data_15min
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('15 minutes', time) AS bucket,
    device_uid,
    device_type,
    farm_id,
    metric_name,
    AVG(metric_value) AS avg_value,
    MIN(metric_value) AS min_value,
    MAX(metric_value) AS max_value,
    STDDEV(metric_value) AS stddev_value,
    COUNT(*) AS sample_count
FROM iot.sensor_data
GROUP BY bucket, device_uid, device_type, farm_id, metric_name
WITH NO DATA;

SELECT add_continuous_aggregate_policy('iot.sensor_data_15min',
    start_offset => INTERVAL '1 hour',
    end_offset => INTERVAL '15 minutes',
    schedule_interval => INTERVAL '15 minutes',
    if_not_exists => TRUE
);

-- Hourly sensor aggregates
CREATE MATERIALIZED VIEW IF NOT EXISTS iot.sensor_data_hourly
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 hour', time) AS bucket,
    device_uid,
    device_type,
    farm_id,
    metric_name,
    AVG(metric_value) AS avg_value,
    MIN(metric_value) AS min_value,
    MAX(metric_value) AS max_value,
    PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY metric_value) AS median_value,
    PERCENTILE_CONT(0.95) WITHIN GROUP (ORDER BY metric_value) AS p95_value,
    STDDEV(metric_value) AS stddev_value,
    COUNT(*) AS sample_count,
    SUM(CASE WHEN data_quality = 'valid' THEN 1 ELSE 0 END) AS valid_count
FROM iot.sensor_data
GROUP BY bucket, device_uid, device_type, farm_id, metric_name
WITH NO DATA;

SELECT add_continuous_aggregate_policy('iot.sensor_data_hourly',
    start_offset => INTERVAL '3 hours',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '1 hour',
    if_not_exists => TRUE
);

-- Daily sensor aggregates
CREATE MATERIALIZED VIEW IF NOT EXISTS iot.sensor_data_daily
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 day', time) AS bucket,
    device_uid,
    device_type,
    farm_id,
    metric_name,
    AVG(metric_value) AS avg_value,
    MIN(metric_value) AS min_value,
    MAX(metric_value) AS max_value,
    PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY metric_value) AS median_value,
    COUNT(*) AS sample_count,
    SUM(CASE WHEN data_quality != 'valid' THEN 1 ELSE 0 END) AS error_count
FROM iot.sensor_data
GROUP BY bucket, device_uid, device_type, farm_id, metric_name
WITH NO DATA;

SELECT add_continuous_aggregate_policy('iot.sensor_data_daily',
    start_offset => INTERVAL '3 days',
    end_offset => INTERVAL '1 day',
    schedule_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Daily milk production summary
CREATE MATERIALIZED VIEW IF NOT EXISTS iot.milk_production_daily
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 day', time) AS bucket,
    animal_id,
    farm_id,
    SUM(yield_kg) AS total_yield_kg,
    AVG(yield_kg) AS avg_yield_per_session,
    COUNT(*) AS milking_count,
    AVG(conductivity_ms) AS avg_conductivity,
    AVG(somatic_cell_count) AS avg_scc,
    MAX(mastitis_risk_score) AS max_mastitis_risk,
    AVG(fat_percent) AS avg_fat,
    AVG(protein_percent) AS avg_protein
FROM iot.milk_production_realtime
GROUP BY bucket, animal_id, farm_id
WITH NO DATA;

SELECT add_continuous_aggregate_policy('iot.milk_production_daily',
    start_offset => INTERVAL '3 days',
    end_offset => INTERVAL '1 day',
    schedule_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- =====================================================
-- COMPRESSION POLICIES
-- =====================================================

-- Compress raw data after 7 days
ALTER TABLE iot.sensor_data SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'device_uid, device_type, farm_id',
    timescaledb.compress_orderby = 'time DESC'
);

SELECT add_compression_policy('iot.sensor_data',
    INTERVAL '7 days',
    if_not_exists => TRUE
);

ALTER TABLE iot.milk_production_realtime SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'animal_id, farm_id',
    timescaledb.compress_orderby = 'time DESC'
);

SELECT add_compression_policy('iot.milk_production_realtime',
    INTERVAL '7 days',
    if_not_exists => TRUE
);

-- =====================================================
-- RETENTION POLICIES
-- =====================================================

-- Keep raw data for 90 days
SELECT add_retention_policy('iot.sensor_data',
    INTERVAL '90 days',
    if_not_exists => TRUE
);

SELECT add_retention_policy('iot.milk_production_realtime',
    INTERVAL '90 days',
    if_not_exists => TRUE
);

-- Keep 15-minute aggregates for 6 months
SELECT add_retention_policy('iot.sensor_data_15min',
    INTERVAL '180 days',
    if_not_exists => TRUE
);

-- Keep hourly aggregates for 1 year
SELECT add_retention_policy('iot.sensor_data_hourly',
    INTERVAL '365 days',
    if_not_exists => TRUE
);

-- Daily aggregates kept for 2+ years (no retention policy)

-- =====================================================
-- DATA QUALITY FUNCTIONS
-- =====================================================

CREATE OR REPLACE FUNCTION iot.validate_sensor_reading(
    p_metric_name VARCHAR,
    p_metric_value DOUBLE PRECISION
) RETURNS VARCHAR AS $$
BEGIN
    -- Temperature validation
    IF p_metric_name = 'temperature' THEN
        IF p_metric_value < -50 OR p_metric_value > 100 THEN
            RETURN 'invalid_range';
        END IF;
    -- Activity validation
    ELSIF p_metric_name = 'activity' THEN
        IF p_metric_value < 0 OR p_metric_value > 1000 THEN
            RETURN 'invalid_range';
        END IF;
    -- Milk yield validation
    ELSIF p_metric_name = 'yield' THEN
        IF p_metric_value < 0 OR p_metric_value > 100 THEN
            RETURN 'invalid_range';
        END IF;
    END IF;

    RETURN 'valid';
END;
$$ LANGUAGE plpgsql IMMUTABLE;

-- =====================================================
-- HELPER VIEWS
-- =====================================================

-- Current device status view
CREATE OR REPLACE VIEW iot.device_current_status AS
SELECT DISTINCT ON (device_uid)
    device_uid,
    device_type,
    farm_id,
    time AS last_seen,
    battery_level,
    signal_strength,
    CASE
        WHEN time > NOW() - INTERVAL '5 minutes' THEN 'online'
        WHEN time > NOW() - INTERVAL '1 hour' THEN 'degraded'
        ELSE 'offline'
    END AS status
FROM iot.sensor_data
ORDER BY device_uid, time DESC;

-- Farm daily summary view
CREATE OR REPLACE VIEW iot.farm_daily_summary AS
SELECT
    bucket AS date,
    farm_id,
    device_type,
    metric_name,
    avg_value,
    min_value,
    max_value,
    sample_count
FROM iot.sensor_data_daily
WHERE bucket >= NOW() - INTERVAL '30 days';
```

### 7.2 FastAPI Data Ingestion Service

```python
# iot_data_pipeline/main.py

from fastapi import FastAPI, HTTPException, BackgroundTasks, Depends
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field, validator
from typing import List, Optional, Dict, Any
from datetime import datetime
import asyncpg
import asyncio
import json
import logging
from contextlib import asynccontextmanager

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Database configuration
DATABASE_URL = "postgresql://odoo:odoo@localhost:5432/smart_dairy"
POOL_MIN_SIZE = 10
POOL_MAX_SIZE = 50
BATCH_SIZE = 1000
BATCH_TIMEOUT = 1.0  # seconds

# Global connection pool
db_pool = None

# Batch buffer
batch_buffer = []
batch_lock = asyncio.Lock()


class SensorReading(BaseModel):
    """Schema for individual sensor reading"""
    device_uid: str = Field(..., max_length=64)
    device_type: str = Field(..., max_length=32)
    farm_id: int
    metric_name: str = Field(..., max_length=64)
    metric_value: float
    metric_unit: Optional[str] = None
    timestamp: Optional[datetime] = None
    animal_id: Optional[int] = None
    zone_id: Optional[int] = None
    session_id: Optional[str] = None
    battery_level: Optional[int] = None
    signal_strength: Optional[int] = None
    metadata: Optional[Dict[str, Any]] = None

    @validator('metric_value')
    def validate_metric_value(cls, v, values):
        """Basic validation of metric values"""
        metric_name = values.get('metric_name', '')

        if metric_name == 'temperature':
            if v < -50 or v > 100:
                raise ValueError('Temperature out of valid range')
        elif metric_name == 'activity':
            if v < 0 or v > 1000:
                raise ValueError('Activity level out of valid range')
        elif metric_name == 'yield':
            if v < 0 or v > 100:
                raise ValueError('Yield out of valid range')

        return v


class BatchSensorData(BaseModel):
    """Schema for batch sensor data submission"""
    readings: List[SensorReading]


class MilkProductionReading(BaseModel):
    """Schema for milk production reading"""
    milk_meter_id: int
    animal_id: int
    farm_id: int
    session_id: str
    yield_kg: float
    duration_seconds: Optional[int] = None
    flow_rate_kg_min: Optional[float] = None
    conductivity_ms: Optional[float] = None
    temperature_c: Optional[float] = None
    blood_detected: bool = False
    fat_percent: Optional[float] = None
    protein_percent: Optional[float] = None
    lactose_percent: Optional[float] = None
    somatic_cell_count: Optional[int] = None
    mastitis_risk_score: Optional[float] = None
    session_start: Optional[datetime] = None
    session_end: Optional[datetime] = None
    milking_type: Optional[str] = None
    timestamp: Optional[datetime] = None


@asynccontextmanager
async def lifespan(app: FastAPI):
    """Application lifespan management"""
    global db_pool

    # Startup
    logger.info("Creating database connection pool...")
    db_pool = await asyncpg.create_pool(
        DATABASE_URL,
        min_size=POOL_MIN_SIZE,
        max_size=POOL_MAX_SIZE,
        command_timeout=60
    )
    logger.info("Database pool created")

    # Start batch processor
    asyncio.create_task(batch_processor())
    logger.info("Batch processor started")

    yield

    # Shutdown
    logger.info("Closing database pool...")
    await db_pool.close()
    logger.info("Database pool closed")


app = FastAPI(
    title="Smart Dairy IoT Data Pipeline",
    description="High-throughput IoT data ingestion service",
    version="1.0.0",
    lifespan=lifespan
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


async def batch_processor():
    """Background task to process batch buffer"""
    global batch_buffer

    while True:
        await asyncio.sleep(BATCH_TIMEOUT)

        async with batch_lock:
            if batch_buffer:
                readings_to_process = batch_buffer.copy()
                batch_buffer = []

        if readings_to_process:
            try:
                await insert_sensor_batch(readings_to_process)
                logger.info(f"Processed batch of {len(readings_to_process)} readings")
            except Exception as e:
                logger.error(f"Batch processing error: {e}")
                # Re-add to buffer for retry
                async with batch_lock:
                    batch_buffer.extend(readings_to_process)


async def insert_sensor_batch(readings: List[SensorReading]):
    """Insert batch of sensor readings into TimescaleDB"""
    async with db_pool.acquire() as conn:
        # Prepare data for COPY
        records = [
            (
                r.timestamp or datetime.utcnow(),
                r.device_uid,
                r.device_type,
                r.farm_id,
                r.metric_name,
                r.metric_value,
                r.metric_unit,
                'valid',  # data_quality
                False,  # is_interpolated
                r.animal_id,
                r.zone_id,
                r.session_id,
                r.battery_level,
                r.signal_strength,
                json.dumps(r.metadata) if r.metadata else '{}'
            )
            for r in readings
        ]

        # Use COPY for high-performance bulk insert
        await conn.copy_records_to_table(
            'sensor_data',
            schema_name='iot',
            records=records,
            columns=[
                'time', 'device_uid', 'device_type', 'farm_id',
                'metric_name', 'metric_value', 'metric_unit',
                'data_quality', 'is_interpolated',
                'animal_id', 'zone_id', 'session_id',
                'battery_level', 'signal_strength', 'metadata'
            ]
        )


@app.post("/api/v1/ingest/sensor")
async def ingest_single_sensor_reading(reading: SensorReading):
    """Ingest a single sensor reading (buffered)"""
    global batch_buffer

    async with batch_lock:
        batch_buffer.append(reading)

        # If buffer is full, process immediately
        if len(batch_buffer) >= BATCH_SIZE:
            readings_to_process = batch_buffer.copy()
            batch_buffer = []
            await insert_sensor_batch(readings_to_process)

    return {"status": "accepted", "message": "Reading queued for processing"}


@app.post("/api/v1/ingest/sensor/batch")
async def ingest_sensor_batch(data: BatchSensorData):
    """Ingest batch of sensor readings (direct insert)"""
    if len(data.readings) > 10000:
        raise HTTPException(
            status_code=400,
            detail="Batch size exceeds maximum of 10000"
        )

    try:
        await insert_sensor_batch(data.readings)
        return {
            "status": "success",
            "count": len(data.readings),
            "message": "Batch inserted successfully"
        }
    except Exception as e:
        logger.error(f"Batch insert error: {e}")
        raise HTTPException(status_code=500, detail=str(e))


@app.post("/api/v1/ingest/milk-production")
async def ingest_milk_production(reading: MilkProductionReading):
    """Ingest milk production reading"""
    async with db_pool.acquire() as conn:
        await conn.execute("""
            INSERT INTO iot.milk_production_realtime (
                time, milk_meter_id, animal_id, farm_id, session_id,
                yield_kg, duration_seconds, flow_rate_kg_min,
                conductivity_ms, temperature_c, blood_detected,
                fat_percent, protein_percent, lactose_percent,
                somatic_cell_count, mastitis_risk_score,
                session_start, session_end, milking_type
            ) VALUES (
                $1, $2, $3, $4, $5, $6, $7, $8, $9, $10,
                $11, $12, $13, $14, $15, $16, $17, $18, $19
            )
        """,
            reading.timestamp or datetime.utcnow(),
            reading.milk_meter_id,
            reading.animal_id,
            reading.farm_id,
            reading.session_id,
            reading.yield_kg,
            reading.duration_seconds,
            reading.flow_rate_kg_min,
            reading.conductivity_ms,
            reading.temperature_c,
            reading.blood_detected,
            reading.fat_percent,
            reading.protein_percent,
            reading.lactose_percent,
            reading.somatic_cell_count,
            reading.mastitis_risk_score,
            reading.session_start,
            reading.session_end,
            reading.milking_type
        )

    return {"status": "success", "message": "Milk production recorded"}


@app.get("/api/v1/query/sensor/latest/{device_uid}")
async def get_latest_sensor_reading(device_uid: str):
    """Get latest reading for a device"""
    async with db_pool.acquire() as conn:
        row = await conn.fetchrow("""
            SELECT time, device_type, metric_name, metric_value, metric_unit
            FROM iot.sensor_data
            WHERE device_uid = $1
            ORDER BY time DESC
            LIMIT 1
        """, device_uid)

        if not row:
            raise HTTPException(status_code=404, detail="Device not found")

        return dict(row)


@app.get("/api/v1/query/sensor/range")
async def query_sensor_range(
    device_uid: str,
    metric_name: str,
    start_time: datetime,
    end_time: datetime,
    aggregation: str = "raw"  # raw, 15min, hourly, daily
):
    """Query sensor data for a time range with optional aggregation"""
    async with db_pool.acquire() as conn:
        if aggregation == "raw":
            table = "iot.sensor_data"
            time_col = "time"
            value_col = "metric_value"
        elif aggregation == "15min":
            table = "iot.sensor_data_15min"
            time_col = "bucket"
            value_col = "avg_value"
        elif aggregation == "hourly":
            table = "iot.sensor_data_hourly"
            time_col = "bucket"
            value_col = "avg_value"
        elif aggregation == "daily":
            table = "iot.sensor_data_daily"
            time_col = "bucket"
            value_col = "avg_value"
        else:
            raise HTTPException(status_code=400, detail="Invalid aggregation")

        query = f"""
            SELECT {time_col} as time, {value_col} as value
            FROM {table}
            WHERE device_uid = $1
              AND metric_name = $2
              AND {time_col} >= $3
              AND {time_col} <= $4
            ORDER BY {time_col}
        """

        rows = await conn.fetch(query, device_uid, metric_name, start_time, end_time)

        return {
            "device_uid": device_uid,
            "metric_name": metric_name,
            "aggregation": aggregation,
            "data": [{"time": r["time"].isoformat(), "value": r["value"]} for r in rows]
        }


@app.get("/api/v1/stats/ingestion")
async def get_ingestion_stats():
    """Get ingestion statistics"""
    async with db_pool.acquire() as conn:
        stats = await conn.fetchrow("""
            SELECT
                (SELECT COUNT(*) FROM iot.sensor_data
                 WHERE time > NOW() - INTERVAL '1 hour') as readings_last_hour,
                (SELECT COUNT(*) FROM iot.sensor_data
                 WHERE time > NOW() - INTERVAL '24 hours') as readings_last_24h,
                (SELECT COUNT(DISTINCT device_uid) FROM iot.sensor_data
                 WHERE time > NOW() - INTERVAL '5 minutes') as active_devices
        """)

        return {
            "readings_last_hour": stats["readings_last_hour"],
            "readings_last_24h": stats["readings_last_24h"],
            "active_devices": stats["active_devices"],
            "buffer_size": len(batch_buffer)
        }


@app.get("/health")
async def health_check():
    """Health check endpoint"""
    try:
        async with db_pool.acquire() as conn:
            await conn.execute("SELECT 1")
        return {"status": "healthy", "database": "connected"}
    except Exception as e:
        return {"status": "unhealthy", "error": str(e)}


if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8001)
```

### 7.3 Apache Flink Streaming Job

```python
# iot_data_pipeline/flink_jobs/sensor_processor.py

from pyflink.datastream import StreamExecutionEnvironment
from pyflink.datastream.connectors.kafka import FlinkKafkaConsumer, FlinkKafkaProducer
from pyflink.datastream.functions import ProcessFunction, RuntimeContext
from pyflink.datastream.state import ValueStateDescriptor
from pyflink.common.serialization import SimpleStringSchema
from pyflink.common.typeinfo import Types
from pyflink.common import WatermarkStrategy
from pyflink.datastream.window import TumblingEventTimeWindows, SlidingEventTimeWindows
from pyflink.datastream.functions import AggregateFunction, WindowFunction
import json
from datetime import datetime, timedelta

# Anomaly detection thresholds
THRESHOLDS = {
    'temperature': {'min': -20, 'max': 50, 'rate_of_change': 5},  # °C per minute
    'activity': {'min': 0, 'max': 500, 'rate_of_change': 100},
    'yield': {'min': 0, 'max': 50, 'rate_of_change': 5},  # kg
}


class AnomalyDetector(ProcessFunction):
    """Detect anomalies in sensor readings"""

    def __init__(self):
        self.last_value_state = None
        self.last_time_state = None

    def open(self, runtime_context: RuntimeContext):
        self.last_value_state = runtime_context.get_state(
            ValueStateDescriptor("last_value", Types.FLOAT())
        )
        self.last_time_state = runtime_context.get_state(
            ValueStateDescriptor("last_time", Types.LONG())
        )

    def process_element(self, value, ctx: 'ProcessFunction.Context'):
        data = json.loads(value)
        metric_name = data.get('metric_name')
        metric_value = data.get('metric_value')
        timestamp = data.get('timestamp')

        anomalies = []

        # Check if metric has thresholds defined
        if metric_name in THRESHOLDS:
            thresholds = THRESHOLDS[metric_name]

            # Range check
            if metric_value < thresholds['min'] or metric_value > thresholds['max']:
                anomalies.append({
                    'type': 'out_of_range',
                    'metric': metric_name,
                    'value': metric_value,
                    'min': thresholds['min'],
                    'max': thresholds['max']
                })

            # Rate of change check
            last_value = self.last_value_state.value()
            last_time = self.last_time_state.value()

            if last_value is not None and last_time is not None:
                current_time = int(datetime.fromisoformat(timestamp).timestamp() * 1000)
                time_diff_minutes = (current_time - last_time) / 60000

                if time_diff_minutes > 0:
                    rate_of_change = abs(metric_value - last_value) / time_diff_minutes
                    if rate_of_change > thresholds['rate_of_change']:
                        anomalies.append({
                            'type': 'rate_of_change',
                            'metric': metric_name,
                            'rate': rate_of_change,
                            'threshold': thresholds['rate_of_change']
                        })

            # Update state
            self.last_value_state.update(metric_value)
            self.last_time_state.update(
                int(datetime.fromisoformat(timestamp).timestamp() * 1000)
            )

        # Emit anomalies
        for anomaly in anomalies:
            anomaly['device_uid'] = data.get('device_uid')
            anomaly['farm_id'] = data.get('farm_id')
            anomaly['timestamp'] = timestamp
            yield json.dumps(anomaly)

        # Also yield the original reading (marked as processed)
        data['processed'] = True
        data['anomaly_count'] = len(anomalies)
        yield json.dumps(data)


class MetricAggregator(AggregateFunction):
    """Aggregate metrics over time windows"""

    def create_accumulator(self):
        return {
            'sum': 0.0,
            'count': 0,
            'min': float('inf'),
            'max': float('-inf'),
            'values': []
        }

    def add(self, value, accumulator):
        data = json.loads(value)
        metric_value = data.get('metric_value', 0)

        accumulator['sum'] += metric_value
        accumulator['count'] += 1
        accumulator['min'] = min(accumulator['min'], metric_value)
        accumulator['max'] = max(accumulator['max'], metric_value)
        accumulator['values'].append(metric_value)

        return accumulator

    def get_result(self, accumulator):
        if accumulator['count'] == 0:
            return None

        # Calculate percentiles
        values = sorted(accumulator['values'])
        p50_idx = int(len(values) * 0.5)
        p95_idx = int(len(values) * 0.95)

        return {
            'avg': accumulator['sum'] / accumulator['count'],
            'min': accumulator['min'],
            'max': accumulator['max'],
            'count': accumulator['count'],
            'p50': values[p50_idx] if values else 0,
            'p95': values[p95_idx] if values else 0,
        }

    def merge(self, a, b):
        return {
            'sum': a['sum'] + b['sum'],
            'count': a['count'] + b['count'],
            'min': min(a['min'], b['min']),
            'max': max(a['max'], b['max']),
            'values': a['values'] + b['values']
        }


def create_sensor_processing_job():
    """Create and configure Flink streaming job"""

    # Set up execution environment
    env = StreamExecutionEnvironment.get_execution_environment()
    env.set_parallelism(4)

    # Configure checkpointing for fault tolerance
    env.enable_checkpointing(60000)  # Checkpoint every 60 seconds

    # Kafka consumer configuration
    kafka_props = {
        'bootstrap.servers': 'localhost:9092',
        'group.id': 'iot-sensor-processor',
        'auto.offset.reset': 'latest'
    }

    # Create Kafka consumer for raw sensor data
    sensor_consumer = FlinkKafkaConsumer(
        topics='iot.sensor.raw',
        deserialization_schema=SimpleStringSchema(),
        properties=kafka_props
    )

    # Create stream from Kafka
    sensor_stream = env.add_source(sensor_consumer)

    # Process for anomaly detection
    anomaly_stream = sensor_stream \
        .key_by(lambda x: json.loads(x).get('device_uid')) \
        .process(AnomalyDetector())

    # Split into anomalies and normal readings
    anomaly_output = anomaly_stream.filter(
        lambda x: json.loads(x).get('type') is not None
    )

    processed_output = anomaly_stream.filter(
        lambda x: json.loads(x).get('processed', False)
    )

    # Create Kafka producer for anomalies
    anomaly_producer = FlinkKafkaProducer(
        topic='iot.anomalies',
        serialization_schema=SimpleStringSchema(),
        producer_config={'bootstrap.servers': 'localhost:9092'}
    )

    anomaly_output.add_sink(anomaly_producer)

    # Create Kafka producer for processed readings
    processed_producer = FlinkKafkaProducer(
        topic='iot.sensor.processed',
        serialization_schema=SimpleStringSchema(),
        producer_config={'bootstrap.servers': 'localhost:9092'}
    )

    processed_output.add_sink(processed_producer)

    # Execute job
    env.execute("IoT Sensor Processing Job")


if __name__ == "__main__":
    create_sensor_processing_job()
```

---

## 8. Testing & Validation

### 8.1 Load Testing Script

```python
# iot_data_pipeline/tests/load_test.py

import asyncio
import aiohttp
import time
import random
from datetime import datetime
import statistics

API_URL = "http://localhost:8001/api/v1/ingest/sensor/batch"
BATCH_SIZE = 1000
NUM_BATCHES = 100
CONCURRENT_REQUESTS = 10

DEVICE_TYPES = ['temp_sensor', 'milk_meter', 'collar', 'dispenser']
METRICS = {
    'temp_sensor': ['temperature', 'humidity'],
    'milk_meter': ['yield', 'conductivity', 'flow_rate'],
    'collar': ['activity', 'rumination', 'rest'],
    'dispenser': ['dispensed_amount', 'hopper_level'],
}


def generate_reading(device_num: int, device_type: str):
    """Generate a random sensor reading"""
    metric_name = random.choice(METRICS[device_type])

    value_ranges = {
        'temperature': (0, 45),
        'humidity': (30, 95),
        'yield': (0, 15),
        'conductivity': (4, 8),
        'flow_rate': (0.5, 3),
        'activity': (0, 400),
        'rumination': (0, 60),
        'rest': (0, 14),
        'dispensed_amount': (0, 5),
        'hopper_level': (0, 100),
    }

    min_val, max_val = value_ranges.get(metric_name, (0, 100))

    return {
        "device_uid": f"{device_type}_{device_num:04d}",
        "device_type": device_type,
        "farm_id": random.randint(1, 10),
        "metric_name": metric_name,
        "metric_value": random.uniform(min_val, max_val),
        "timestamp": datetime.utcnow().isoformat(),
        "battery_level": random.randint(10, 100),
        "signal_strength": random.randint(-100, -30),
    }


def generate_batch(batch_size: int):
    """Generate a batch of readings"""
    readings = []
    for i in range(batch_size):
        device_type = random.choice(DEVICE_TYPES)
        device_num = random.randint(1, 1000)
        readings.append(generate_reading(device_num, device_type))
    return {"readings": readings}


async def send_batch(session: aiohttp.ClientSession, batch: dict):
    """Send a batch to the API"""
    start_time = time.time()
    try:
        async with session.post(API_URL, json=batch) as response:
            await response.json()
            return time.time() - start_time, response.status
    except Exception as e:
        return time.time() - start_time, 500


async def run_load_test():
    """Run the load test"""
    print(f"Starting load test: {NUM_BATCHES} batches of {BATCH_SIZE} readings")
    print(f"Concurrent requests: {CONCURRENT_REQUESTS}")
    print("-" * 50)

    async with aiohttp.ClientSession() as session:
        all_latencies = []
        errors = 0
        total_readings = 0

        start_time = time.time()

        # Create semaphore for concurrency control
        semaphore = asyncio.Semaphore(CONCURRENT_REQUESTS)

        async def bounded_send(batch):
            async with semaphore:
                return await send_batch(session, batch)

        # Generate and send all batches
        tasks = []
        for i in range(NUM_BATCHES):
            batch = generate_batch(BATCH_SIZE)
            tasks.append(bounded_send(batch))

        results = await asyncio.gather(*tasks)

        end_time = time.time()

        # Process results
        for latency, status in results:
            all_latencies.append(latency)
            if status != 200:
                errors += 1
            else:
                total_readings += BATCH_SIZE

        # Calculate statistics
        total_time = end_time - start_time
        throughput = total_readings / total_time
        avg_latency = statistics.mean(all_latencies)
        p95_latency = sorted(all_latencies)[int(len(all_latencies) * 0.95)]
        p99_latency = sorted(all_latencies)[int(len(all_latencies) * 0.99)]

        print(f"\nResults:")
        print(f"  Total time: {total_time:.2f}s")
        print(f"  Total readings: {total_readings:,}")
        print(f"  Throughput: {throughput:,.0f} readings/second")
        print(f"  Average latency: {avg_latency*1000:.1f}ms")
        print(f"  P95 latency: {p95_latency*1000:.1f}ms")
        print(f"  P99 latency: {p99_latency*1000:.1f}ms")
        print(f"  Errors: {errors}")


if __name__ == "__main__":
    asyncio.run(run_load_test())
```

### 8.2 Performance Targets

| Test Case | Target | Method |
|-----------|--------|--------|
| Batch insert throughput | 50,000 rows/second | Load test |
| Single reading latency | <100ms | API timing |
| Query response (recent) | <100ms | Database benchmark |
| Query response (aggregates) | <50ms | Database benchmark |
| Compression ratio | 10:1 | Storage comparison |
| Continuous aggregate refresh | <5 minutes | Refresh timing |

---

## 9. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Database performance degradation | Medium | High | Query optimization, indexes |
| Data loss during peak load | Low | High | Batch buffering, retries |
| Disk space exhaustion | Medium | High | Compression, retention policies |
| Flink job failures | Medium | Medium | Checkpointing, monitoring |
| API service overload | Medium | Medium | Rate limiting, auto-scaling |

---

## 10. Milestone Sign-off Checklist

| # | Criteria | Owner | Status |
|---|----------|-------|--------|
| 1 | TimescaleDB schema deployed | Dev 1 | ☐ |
| 2 | FastAPI ingestion service running | Dev 2 | ☐ |
| 3 | Continuous aggregates refreshing | Dev 1 | ☐ |
| 4 | Compression policies active | Dev 1 | ☐ |
| 5 | Retention policies configured | Dev 1 | ☐ |
| 6 | Flink streaming jobs deployed | Dev 2 | ☐ |
| 7 | Data validation working | Dev 2 | ☐ |
| 8 | Query performance optimized | Dev 1 | ☐ |
| 9 | Monitoring dashboards deployed | Dev 3 | ☐ |
| 10 | Load test passing (50K/sec) | All | ☐ |
| 11 | Documentation complete | All | ☐ |
| 12 | Demo completed successfully | All | ☐ |

---

**Document Revision History**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-04 | Development Team | Initial creation |

---

*This document is part of the Smart Dairy Digital Smart Portal + ERP System implementation roadmap.*
