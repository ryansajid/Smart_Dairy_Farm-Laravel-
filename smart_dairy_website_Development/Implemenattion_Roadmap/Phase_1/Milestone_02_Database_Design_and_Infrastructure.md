# Milestone 02: Database Design & Core Infrastructure

## Smart Dairy Smart Portal + ERP System Implementation

---

**Document Version:** 1.0.0  
**Milestone Duration:** Days 11-20  
**Document Status:** DRAFT  
**Classification:** Technical Implementation Guide  
**Prepared By:** Smart Dairy Technical Architecture Team  
**Last Updated:** February 2026

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Milestone Overview and Objectives](#2-milestone-overview-and-objectives)
3. [Database Architecture Design (Days 11-13)](#3-database-architecture-design-days-11-13)
4. [Schema Design for Core Entities (Days 14-16)](#4-schema-design-for-core-entities-days-14-16)
5. [Infrastructure Hardening (Days 17-18)](#5-infrastructure-hardening-days-17-18)
6. [Data Migration Framework (Day 19)](#6-data-migration-framework-day-19)
7. [Milestone Review and Sign-off (Day 20)](#7-milestone-review-and-sign-off-day-20)
8. [Developer Task Assignments](#8-developer-task-assignments)
9. [Technical Appendices](#9-technical-appendices)
10. [Deliverables Checklist](#10-deliverables-checklist)

---

## 1. Executive Summary

Milestone 02 represents a critical foundation phase in the Smart Dairy Smart Portal + ERP System implementation. This milestone focuses on establishing the robust database architecture and core infrastructure components that will serve as the backbone for all subsequent development activities.

### Key Achievements Targeted

- Complete PostgreSQL 16 database schema design with 150+ tables
- Implementation of Odoo-specific data structures with Smart Dairy customizations
- TimescaleDB integration for IoT sensor data management
- Redis caching layer architecture
- Comprehensive backup and disaster recovery procedures
- Performance optimization with strategic indexing
- Data migration framework for legacy system transition

### Critical Success Factors

| Factor | Target Metric |
|--------|--------------|
| Database Availability | 99.99% uptime |
| Query Performance | <100ms for 95th percentile |
| Backup RTO | <4 hours |
| Backup RPO | <15 minutes |
| Data Integrity | 100% transactional consistency |

---

## 2. Milestone Overview and Objectives

### 2.1 Milestone Scope

**Duration:** Days 11-20 (10 working days)  
**Team Allocation:** 3 Full-time Developers  
**Primary Focus:** Database Layer Implementation

### 2.2 Strategic Objectives

#### Objective 1: Enterprise-Grade Database Foundation
Establish a scalable, secure, and high-performance PostgreSQL 16 database infrastructure capable of handling:
- 10,000+ concurrent B2B users
- 1 million+ daily IoT sensor readings
- 100,000+ daily transactions
- 50TB+ data growth over 5 years

#### Objective 2: Data Architecture Excellence
Design and implement:
- Normalized transactional schema (3NF)
- Optimized reporting structures
- Time-series data management
- Multi-tenant data isolation
- Comprehensive audit trails

#### Objective 3: Infrastructure Resilience
Implement:
- Automated backup systems
- Point-in-time recovery capabilities
- Database clustering for high availability
- Connection pooling and load balancing
- Monitoring and alerting frameworks

#### Objective 4: Migration Readiness
Prepare:
- Data migration scripts for legacy systems
- Validation and verification procedures
- Rollback mechanisms
- Data quality assurance frameworks

### 2.3 Milestone Dependencies

| Dependency | Source | Status |
|------------|--------|--------|
| Hardware provisioning | Infrastructure Team | Required Day 10 |
| PostgreSQL 16 installation | DevOps Team | Required Day 10 |
| Network security baseline | Security Team | Required Day 11 |
| Legacy data export | Data Team | Required Day 18 |

### 2.4 Risk Management

| Risk | Probability | Impact | Mitigation Strategy |
|------|-------------|--------|---------------------|
| Schema design changes | High | Medium | Extensive review process, versioning |
| Performance bottlenecks | Medium | High | Early benchmarking, load testing |
| Data migration failures | Low | Critical | Phased migration, validation scripts |
| Security vulnerabilities | Low | Critical | Security audit, penetration testing |

---

## 3. Database Architecture Design (Days 11-13)

### 3.1 Architecture Overview

The Smart Dairy database architecture follows a multi-layered approach designed for scalability, security, and performance. The architecture consists of four primary tiers:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           PRESENTATION LAYER                                 │
│    Web Application  │  Mobile App  │  B2B Portal  │  IoT Gateway           │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                         APPLICATION LAYER                                    │
│    Odoo Framework  │  Smart Dairy Modules  │  API Gateway  │  Workers       │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                         DATA ACCESS LAYER                                    │
│    Connection Pool  │  ORM Layer  │  Cache Layer (Redis)  │  Queue System   │
└─────────────────────────────────────────────────────────────────────────────┘
                                      │
                                      ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                          DATABASE LAYER                                      │
│    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐   │
│    │  PostgreSQL  │  │  TimescaleDB │  │     S3       │  │    Redis     │   │
│    │  (Primary)   │  │  (Time-Series)│  │  (Documents) │  │   (Cache)    │   │
│    └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘   │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 PostgreSQL 16 Configuration Architecture

#### 3.2.1 Server Configuration

```yaml
# postgresql.conf - Smart Dairy Production Configuration
# Generated for: PostgreSQL 16.2
# Hardware: 32 vCPU, 128GB RAM, 2TB NVMe SSD

# CONNECTION AND AUTHENTICATION
listen_addresses = '*'
port = 5432
max_connections = 500
superuser_reserved_connections = 10

# MEMORY CONFIGURATION
shared_buffers = 32GB                    # 25% of total RAM
effective_cache_size = 96GB              # 75% of total RAM
work_mem = 256MB                         # Per-operation memory
maintenance_work_mem = 2GB               # Maintenance operations
huge_pages = try                         # Enable huge pages if available

# WRITE-AHEAD LOGGING (WAL)
wal_level = replica
wal_buffers = 64MB
max_wal_size = 8GB
min_wal_size = 2GB
checkpoint_completion_target = 0.9
checkpoint_timeout = 10min
archive_mode = on
archive_command = 'cp %p /var/lib/postgresql/archive/%f'

# QUERY PLANNER
effective_io_concurrency = 200
random_page_cost = 1.1                   # For SSD storage
seq_page_cost = 1.0
default_statistics_target = 1000

# AUTOVACUUM
autovacuum = on
autovacuum_max_workers = 8
autovacuum_naptime = 1min
autovacuum_vacuum_scale_factor = 0.05
autovacuum_analyze_scale_factor = 0.025

# LOGGING
logging_collector = on
log_directory = '/var/log/postgresql'
log_filename = 'postgresql-%Y-%m-%d_%H%M%S.log'
log_rotation_age = 1d
log_rotation_size = 100MB
log_min_duration_statement = 1000        # Log slow queries (>1s)
log_line_prefix = '%t [%p]: [%l-1] user=%u,db=%d,app=%a,client=%h '
log_checkpoints = on
log_connections = on
log_disconnections = on
log_lock_waits = on

# REPLICATION
max_wal_senders = 10
max_replication_slots = 10
hot_standby = on
hot_standby_feedback = on

# QUERY TUNING
enable_partitionwise_join = on
enable_partitionwise_aggregate = on
jit = on
max_parallel_workers_per_gather = 8
max_parallel_workers = 16
max_parallel_maintenance_workers = 8

# SMART DAIRY SPECIFIC
custom.smart_dairy.application_name = 'SmartDairyERP'
custom.smart_dairy.version = '1.0.0'
```

#### 3.2.2 pg_hba.conf - Host-Based Authentication

```
# TYPE  DATABASE        USER            ADDRESS                 METHOD

# Local connections
local   all             postgres                                peer
local   smart_dairy     smart_dairy_app                         scram-sha-256
local   smart_dairy     smart_dairy_readonly                    scram-sha-256
local   smart_dairy     smart_dairy_backup                      scram-sha-256

# IPv4 local connections:
host    all             postgres        127.0.0.1/32            scram-sha-256
host    smart_dairy     smart_dairy_app 10.0.0.0/8              scram-sha-256
host    smart_dairy     smart_dairy_app 172.16.0.0/12           scram-sha-256
host    smart_dairy     smart_dairy_app 192.168.0.0/16          scram-sha-256

# Replication connections
host    replication     replicator      10.0.0.0/8              scram-sha-256
host    replication     replicator      172.16.0.0/12           scram-sha-256

# Monitoring connections
host    all             monitoring      10.0.1.0/24             scram-sha-256

# Deny all other connections
host    all             all             0.0.0.0/0               reject
```

### 3.3 Database Schema Architecture

#### 3.3.1 Schema Organization

The database is organized into logical schemas to separate concerns and enable fine-grained access control:

```sql
-- Core schema creation
CREATE SCHEMA IF NOT EXISTS smart_dairy_core;
COMMENT ON SCHEMA smart_dairy_core IS 'Core business entities and configurations';

CREATE SCHEMA IF NOT EXISTS smart_dairy_animals;
COMMENT ON SCHEMA smart_dairy_animals IS 'Animal management and livestock data';

CREATE SCHEMA IF NOT EXISTS smart_dairy_production;
COMMENT ON SCHEMA smart_dairy_production IS 'Milk production and quality data';

CREATE SCHEMA IF NOT EXISTS smart_dairy_health;
COMMENT ON SCHEMA smart_dairy_health IS 'Animal health and veterinary records';

CREATE SCHEMA IF NOT EXISTS smart_dairy_inventory;
COMMENT ON SCHEMA smart_dairy_inventory IS 'Inventory and supply chain management';

CREATE SCHEMA IF NOT EXISTS smart_dairy_sales;
COMMENT ON SCHEMA smart_dairy_sales IS 'Sales, orders, and B2B transactions';

CREATE SCHEMA IF NOT EXISTS smart_dairy_iot;
COMMENT ON SCHEMA smart_dairy_iot IS 'IoT sensor data and device management';

CREATE SCHEMA IF NOT EXISTS smart_dairy_b2b;
COMMENT ON SCHEMA smart_dairy_b2b IS 'B2B partner management and subscription data';

CREATE SCHEMA IF NOT EXISTS smart_dairy_audit;
COMMENT ON SCHEMA smart_dairy_audit IS 'Audit trails and compliance logging';

CREATE SCHEMA IF NOT EXISTS smart_dairy_reporting;
COMMENT ON SCHEMA smart_dairy_reporting IS 'Reporting and analytics views';

CREATE SCHEMA IF NOT EXISTS smart_dairy_system;
COMMENT ON SCHEMA smart_dairy_system IS 'System configuration and metadata';
```

#### 3.3.2 Tablespace Configuration

```sql
-- Create tablespaces for performance optimization
CREATE TABLESPACE smart_dairy_data
    LOCATION '/var/lib/postgresql/tablespaces/data';

CREATE TABLESPACE smart_dairy_index
    LOCATION '/var/lib/postgresql/tablespaces/index';

CREATE TABLESPACE smart_dairy_iot
    LOCATION '/var/lib/postgresql/tablespaces/iot';

CREATE TABLESPACE smart_dairy_archive
    LOCATION '/var/lib/postgresql/tablespaces/archive';

-- Set ownership
ALTER TABLESPACE smart_dairy_data OWNER TO postgres;
ALTER TABLESPACE smart_dairy_index OWNER TO postgres;
ALTER TABLESPACE smart_dairy_iot OWNER TO postgres;
ALTER TABLESPACE smart_dairy_archive OWNER TO postgres;
```

### 3.4 Entity-Relationship Architecture

#### 3.4.1 High-Level ER Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────────────┐
│                           SMART DAIRY DATABASE ARCHITECTURE                              │
└─────────────────────────────────────────────────────────────────────────────────────────┘

┌──────────────────┐      ┌──────────────────┐      ┌──────────────────┐
│  res_company     │      │  res_partner     │      │  res_users       │
│  (Odoo Core)     │      │  (Odoo Core)     │      │  (Odoo Core)     │
└────────┬─────────┘      └────────┬─────────┘      └────────┬─────────┘
         │                         │                         │
         │    ┌────────────────────┴─────────────────────────┘
         │    │
         ▼    ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              SMART DAIRY CUSTOM TABLES                               │
└─────────────────────────────────────────────────────────────────────────────────────┘

    ANIMAL MANAGEMENT CLUSTER                    PRODUCTION CLUSTER
    ┌─────────────────────┐                      ┌─────────────────────┐
    │  sd_animal          │                      │  sd_milk_session    │
    │  - id (PK)          │◄────────────────────►│  - id (PK)          │
    │  - tag_number       │     1:N              │  - animal_id (FK)   │
    │  - name             │                      │  - session_date     │
    │  - breed_id (FK)    │                      │  - quantity_kg      │
    │  - birth_date       │                      │  - fat_percentage   │
    │  - status           │                      │  - snf_percentage   │
    └─────────┬───────────┘                      │  - quality_grade    │
              │                                  └─────────────────────┘
              │
              │    ┌─────────────────────────────────────────────────────┐
              │    │                    HEALTH CLUSTER                    │
              │    ┌─────────────────────┐    ┌─────────────────────┐    │
              └───►│  sd_health_record   │    │  sd_vaccination      │    │
                   │  - id (PK)          │◄──►│  - id (PK)          │    │
                   │  - animal_id (FK)   │ N:M│  - animal_id (FK)   │    │
                   │  - diagnosis        │    │  - vaccine_type     │    │
                   │  - treatment        │    │  - date_administered│    │
                   │  - veterinarian_id  │    │  - next_due_date    │    │
                   └─────────────────────┘    └─────────────────────┘    │
                   ┌─────────────────────┐    ┌─────────────────────┐    │
                   │  sd_breeding_record │    │  sd_pregnancy_check │    │
                   │  - id (PK)          │    │  - id (PK)          │    │
                   │  - female_id (FK)   │    │  - breeding_id (FK) │    │
                   │  - male_id (FK)     │    │  - check_date       │    │
                   │  - breeding_date    │    │  - result           │    │
                   │  - method           │    │  - expected_due     │    │
                   └─────────────────────┘    └─────────────────────┘    │
                   ┌─────────────────────┐                               │
                   │  sd_calving_record  │                               │
                   │  - id (PK)          │                               │
                   │  - breeding_id (FK) │                               │
                   │  - calving_date     │                               │
                   │  - calf_id (FK)     │                               │
                   │  - calving_type     │                               │
                   │  - complications    │                               │
                   └─────────────────────┘                               │
                   └─────────────────────────────────────────────────────┘

    INVENTORY CLUSTER                            B2B SALES CLUSTER
    ┌─────────────────────┐                      ┌─────────────────────┐
    │  sd_feed_inventory  │                      │  sd_b2b_partner     │
    │  - id (PK)          │                      │  - id (PK)          │
    │  - product_id (FK)  │◄──────┐              │  - partner_id (FK)  │◄──┐
    │  - quantity_kg      │       │              │  - tier_level       │   │
    │  - warehouse_id     │       │              │  - credit_limit     │   │
    │  - expiry_date      │       │              │  - payment_terms    │   │
    └─────────────────────┘       │              │  - contract_start   │   │
    ┌─────────────────────┐       │              └─────────┬───────────┘   │
    │  sd_medicine_stock  │       │                        │               │
    │  - id (PK)          │       │                        │               │
    │  - product_id (FK)  │◄──────┘                        │               │
    │  - batch_number     │    PRODUCT MASTER              │               │
    │  - quantity_units   │    ┌─────────────────────┐     │               │
    │  - expiry_date      │◄──►│  product_product    │     │               │
    └─────────────────────┘    │  (Odoo Core)        │◄────┘               │
                               └─────────────────────┘                     │
                                                                          │
    ┌─────────────────────────────────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                            B2B SUBSCRIPTION CLUSTER                                  │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────┐    ┌─────────────────────────┐    ┌─────────────────────────┐
│  sd_subscription_plan   │    │  sd_subscription        │    │  sd_subscription_usage  │
│  - id (PK)              │◄──►│  - id (PK)              │◄──►│  - id (PK)              │
│  - plan_code            │ 1:N│  - plan_id (FK)         │ 1:N│  - subscription_id (FK) │
│  - plan_name            │    │  - partner_id (FK)      │    │  - usage_date           │
│  - monthly_quota_kg     │    │  - start_date           │    │  - quantity_ordered     │
│  - unit_price           │    │  - end_date             │    │  - quantity_delivered   │
│  - features             │    │  - status               │    │  - usage_charge         │
│  - is_active            │    │  - auto_renew           │    │  - overage_charge       │
└─────────────────────────┘    └─────────────────────────┘    └─────────────────────────┘

┌─────────────────────────┐    ┌─────────────────────────┐
│  sd_b2b_order           │    │  sd_delivery_schedule   │
│  - id (PK)              │◄──►│  - id (PK)              │
│  - subscription_id (FK) │ 1:N│  - order_id (FK)        │
│  - order_date           │    │  - delivery_date        │
│  - requested_quantity   │    │  - delivery_window      │
│  - status               │    │  - route_id (FK)        │
│  - priority             │    │  - driver_id (FK)       │
│  - special_instructions │    │  - actual_delivery_time │
└─────────────────────────┘    └─────────────────────────┘

    IOT SENSOR DATA CLUSTER (TimescaleDB Hypertables)
    ┌─────────────────────────────────────────────────────────────────────────────────┐
    │                                                                                  │
    │    ┌─────────────────────┐    ┌─────────────────────┐    ┌─────────────────────┐│
    │    │  iot_sensor_reading │    │  iot_sensor_device  │    │  iot_sensor_type    ││
    │    │  - time (PK)        │◄──►│  - id (PK)          │◄──►│  - id (PK)          ││
    │    │  - device_id (FK)   │ N:1│  - device_code      │ N:1│  - type_code        ││
    │    │  - reading_value    │    │  - sensor_type_id   │    │  - type_name        ││
    │    │  - reading_unit     │    │  - location_id      │    │  - unit_of_measure  ││
    │    │  - quality_flag     │    │  - installation_date│    │  - min_threshold    ││
    │    │  - metadata         │    │  - status           │    │  - max_threshold    ││
    │    │  - is_anomaly       │    │  - last_calibration │    │  - alert_enabled    ││
    │    └─────────────────────┘    └─────────────────────┘    └─────────────────────┘│
    │                                                                                  │
    │    ┌─────────────────────┐    ┌─────────────────────┐                           │
    │    │  iot_alert          │    │  iot_maintenance_log│                           │
    │    │  - id (PK)          │    │  - id (PK)          │                           │
    │    │  - device_id (FK)   │    │  - device_id (FK)   │                           │
    │    │  - alert_type       │    │  - maintenance_date │                           │
    │    │  - severity         │    │  - maintenance_type │                           │
    │    │  - triggered_at     │    │  - technician_id    │                           │
    │    │  - resolved_at      │    │  - notes            │                           │
    │    │  - resolution_notes │    │  - next_due_date    │                           │
    │    └─────────────────────┘    └─────────────────────┘                           │
    │                                                                                  │
    └─────────────────────────────────────────────────────────────────────────────────┘

    AUDIT AND COMPLIANCE CLUSTER
    ┌─────────────────────────────────────────────────────────────────────────────────────┐
│                                                                                      │
│    ┌─────────────────────┐    ┌─────────────────────┐    ┌─────────────────────┐    │
│    │  audit_log          │    │  data_change_log    │    │  user_activity_log  │    │
│    │  - id (PK)          │    │  - id (PK)          │    │  - id (PK)          │    │
│    │  - table_name       │    │  - table_name       │    │  - user_id (FK)     │    │
│    │  - record_id        │    │  - record_id        │    │  - activity_type    │    │
│    │  - action_type      │    │  - field_name       │    │  - activity_time    │    │
│    │  - old_values       │    │  - old_value        │    │  - ip_address       │    │
│    │  - new_values       │    │  - new_value        │    │  - user_agent       │    │
│    │  - performed_by     │    │  - changed_by       │    │  - session_id       │    │
│    │  - performed_at     │    │  - changed_at       │    │  - details          │    │
│    │  - session_info     │    │  - transaction_id   │    │  - risk_score       │    │
│    └─────────────────────┘    └─────────────────────┘    └─────────────────────┘    │
│                                                                                      │
│    ┌─────────────────────┐    ┌─────────────────────┐                               │
│    │  compliance_export  │    │  gdpr_data_request  │                               │
│    │  - id (PK)          │    │  - id (PK)          │                               │
│    │  - export_type      │    │  - partner_id (FK)  │                               │
│    │  - date_range_start │    │  - request_date     │                               │
│    │  - date_range_end   │    │  - request_type     │                               │
│    │  - generated_by     │    │  - status           │                               │
│    │  - generated_at     │    │  - completed_at     │                               │
│    │  - file_location    │    │  - file_location    │                               │
│    └─────────────────────┘    └─────────────────────┘                               │
│                                                                                      │
└─────────────────────────────────────────────────────────────────────────────────────┘

    BANGLADESH LOCALIZATION CLUSTER
    ┌─────────────────────────────────────────────────────────────────────────────────────┐
│                                                                                      │
│    ┌─────────────────────┐    ┌─────────────────────┐    ┌─────────────────────┐    │
│    │  bd_vat_registration│    │  bd_tds_config      │    │  bd_mushak_forms    │    │
│    │  - id (PK)          │    │  - id (PK)          │    │  - id (PK)          │    │
│    │  - partner_id (FK)  │    │  - section          │    │  - form_type        │    │
│    │  - bin_number       │    │  - threshold_amount │    │  - form_number      │    │
│    │  - vat_rate         │    │  - tds_rate         │    │  - submission_date  │    │
│    │  - effective_date   │    │  - effective_date   │    │  - period_start     │    │
│    │  - is_active        │    │  - is_active        │    │  - period_end       │    │
│    └─────────────────────┘    └─────────────────────┘    └─────────────────────┘    │
│                                                                                      │
│    ┌─────────────────────┐    ┌─────────────────────┐                               │
│    │  bd_bsti_compliance │    │  bd_dls_registration│                               │
│    │  - id (PK)          │    │  - id (PK)          │                               │
│    │  - product_id (FK)  │    │  - partner_id (FK)  │                               │
│    │  - bsti_license_no  │    │  - dls_license_no   │                               │
│    │  - issue_date       │    │  - issue_date       │                               │
│    │  - expiry_date      │    │  - expiry_date      │                               │
│    │  - renewal_status   │    │  - category         │                               │
│    └─────────────────────┘    └─────────────────────┘                               │
│                                                                                      │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

### 3.5 Partitioning Strategy

#### 3.5.1 IoT Data Partitioning (TimescaleDB)

```sql
-- Enable TimescaleDB extension
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- Create IoT sensor readings hypertable
CREATE TABLE smart_dairy_iot.sensor_readings (
    time TIMESTAMPTZ NOT NULL,
    device_id INTEGER NOT NULL,
    sensor_type VARCHAR(50) NOT NULL,
    reading_value DECIMAL(12, 4) NOT NULL,
    reading_unit VARCHAR(20) NOT NULL,
    location_id INTEGER,
    quality_flag VARCHAR(10) DEFAULT 'VALID',
    is_anomaly BOOLEAN DEFAULT FALSE,
    metadata JSONB,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Convert to hypertable with 1-day chunks
SELECT create_hypertable('smart_dairy_iot.sensor_readings', 'time', 
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Set retention policy (compress after 7 days, drop after 2 years)
SELECT add_retention_policy('smart_dairy_iot.sensor_readings', INTERVAL '2 years');
SELECT add_compression_policy('smart_dairy_iot.sensor_readings', INTERVAL '7 days');

-- Set compression settings
ALTER TABLE smart_dairy_iot.sensor_readings 
    SET (timescaledb.compress, 
         timescaledb.compress_segmentby = 'device_id, sensor_type',
         timescaledb.compress_orderby = 'time DESC'
    );

-- Create continuous aggregates for real-time analytics
CREATE MATERIALIZED VIEW smart_dairy_iot.sensor_readings_hourly
WITH (timescaledb.continuous) AS
SELECT 
    time_bucket('1 hour', time) AS bucket,
    device_id,
    sensor_type,
    AVG(reading_value) AS avg_value,
    MIN(reading_value) AS min_value,
    MAX(reading_value) AS max_value,
    COUNT(*) AS reading_count,
    COUNT(*) FILTER (WHERE is_anomaly = TRUE) AS anomaly_count
FROM smart_dairy_iot.sensor_readings
GROUP BY bucket, device_id, sensor_type;

-- Set refresh policy for continuous aggregate
SELECT add_continuous_aggregate_policy('smart_dairy_iot.sensor_readings_hourly',
    start_offset => INTERVAL '1 month',
    end_offset => INTERVAL '1 hour',
    schedule_interval => INTERVAL '10 minutes'
);
```

#### 3.5.2 Transactional Data Partitioning

```sql
-- Partition milk production sessions by month
CREATE TABLE smart_dairy_production.milk_sessions (
    id BIGSERIAL,
    animal_id INTEGER NOT NULL,
    session_date DATE NOT NULL,
    session_type VARCHAR(20) NOT NULL, -- MORNING, EVENING
    quantity_liters DECIMAL(8, 2) NOT NULL,
    fat_percentage DECIMAL(4, 2),
    snf_percentage DECIMAL(4, 2),
    protein_percentage DECIMAL(4, 2),
    lactose_percentage DECIMAL(4, 2),
    temperature_celsius DECIMAL(4, 1),
    ph_value DECIMAL(3, 2),
    density DECIMAL(5, 3),
    quality_grade VARCHAR(10),
    tested_by INTEGER,
    testing_timestamp TIMESTAMPTZ,
    notes TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    PRIMARY KEY (id, session_date)
) PARTITION BY RANGE (session_date);

-- Create partitions for current and future years
CREATE TABLE smart_dairy_production.milk_sessions_2026_01 
    PARTITION OF smart_dairy_production.milk_sessions
    FOR VALUES FROM ('2026-01-01') TO ('2026-02-01');

CREATE TABLE smart_dairy_production.milk_sessions_2026_02 
    PARTITION OF smart_dairy_production.milk_sessions
    FOR VALUES FROM ('2026-02-01') TO ('2026-03-01');

CREATE TABLE smart_dairy_production.milk_sessions_2026_03 
    PARTITION OF smart_dairy_production.milk_sessions
    FOR VALUES FROM ('2026-03-01') TO ('2026-04-01');

CREATE TABLE smart_dairy_production.milk_sessions_2026_04 
    PARTITION OF smart_dairy_production.milk_sessions
    FOR VALUES FROM ('2026-04-01') TO ('2026-05-01');

CREATE TABLE smart_dairy_production.milk_sessions_2026_05 
    PARTITION OF smart_dairy_production.milk_sessions
    FOR VALUES FROM ('2026-05-01') TO ('2026-06-01');

CREATE TABLE smart_dairy_production.milk_sessions_2026_06 
    PARTITION OF smart_dairy_production.milk_sessions
    FOR VALUES FROM ('2026-06-01') TO ('2026-07-01');

-- Automated partition creation function
CREATE OR REPLACE FUNCTION smart_dairy_production.create_milk_session_partition()
RETURNS void AS $$
DECLARE
    partition_date DATE;
    partition_name TEXT;
    start_date DATE;
    end_date DATE;
BEGIN
    -- Create partitions for next 3 months
    FOR i IN 1..3 LOOP
        partition_date := DATE_TRUNC('month', CURRENT_DATE + (i || ' months')::INTERVAL);
        partition_name := 'milk_sessions_' || TO_CHAR(partition_date, 'YYYY_MM');
        start_date := partition_date;
        end_date := partition_date + INTERVAL '1 month';
        
        EXECUTE format(
            'CREATE TABLE IF NOT EXISTS smart_dairy_production.%I 
             PARTITION OF smart_dairy_production.milk_sessions 
             FOR VALUES FROM (%L) TO (%L)',
            partition_name, start_date, end_date
        );
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- Schedule partition creation
SELECT cron.schedule('create-milk-partitions', '0 1 1 * *', 
    'SELECT smart_dairy_production.create_milk_session_partition()');
```

### 3.6 Redis Architecture

#### 3.6.1 Redis Data Structures

```yaml
# Redis Configuration for Smart Dairy
# redis.conf - Production Settings

# NETWORK
bind 0.0.0.0
port 6379
tcp-backlog 511
timeout 300
tcp-keepalive 300

# GENERAL
daemonize yes
supervised systemd
pidfile /var/run/redis/redis-server.pid
loglevel notice
logfile /var/log/redis/redis-server.log
databases 16

# SMART DAIRY DATABASE MAPPING
# DB 0: Session Cache
# DB 1: Application Cache
# DB 2: Rate Limiting
# DB 3: Real-time Analytics
# DB 4: Queue/Job Management
# DB 5: IoT Data Cache
# DB 6: B2B Subscription Cache
# DB 7: Search Index Cache

# MEMORY MANAGEMENT
maxmemory 16gb
maxmemory-policy allkeys-lru
maxmemory-samples 10

# PERSISTENCE
save 900 1
save 300 10
save 60 10000
stop-writes-on-bgsave-error yes
rdbcompression yes
rdbchecksum yes
dbfilename dump.rdb
dir /var/lib/redis

# AOF PERSISTENCE
appendonly yes
appendfilename "appendonly.aof"
appendfsync everysec
no-appendfsync-on-rewrite no
auto-aof-rewrite-percentage 100
auto-aof-rewrite-min-size 64mb

# REPLICATION
replica-serve-stale-data yes
replica-read-only yes
repl-diskless-sync no
repl-diskless-sync-delay 5
repl-ping-replica-period 10
repl-timeout 60
repl-disable-tcp-nodelay no

# SECURITY
requirepass ${REDIS_PASSWORD}
rename-command FLUSHDB ""
rename-command FLUSHALL ""

# CLIENTS
maxclients 10000

# LUA SCRIPTING
lua-time-limit 5000

# SLOW LOG
slowlog-log-slower-than 10000
slowlog-max-len 128

# LATENCY MONITOR
latency-monitor-threshold 100

# EVENT NOTIFICATION
notify-keyspace-events "Ex"
```

#### 3.6.2 Redis Key Naming Conventions

```
# Session Management
session:{session_id} -> Hash {user_id, company_id, permissions, expires_at}

# Application Cache
cache:product:{product_id} -> Hash {product data}
cache:partner:{partner_id} -> Hash {partner data}
cache:animal:{animal_id} -> Hash {animal data}
cache:pricing:{product_id}:{date} -> String {price}

# Rate Limiting
ratelimit:api:{client_id} -> String {request_count}
ratelimit:auth:{ip_address} -> String {attempt_count}
ratelimit:b2b:{partner_id} -> String {order_count}

# Real-time Analytics
analytics:production:daily:{date} -> Hash {total_volume, avg_quality, etc.}
analytics:sales:hourly:{hour} -> Hash {total_revenue, order_count, etc.}
analytics:inventory:{warehouse_id} -> Hash {stock_levels, alerts}

# Queue Management
queue:high:{job_id} -> Hash {job data}
queue:normal:{job_id} -> Hash {job data}
queue:low:{job_id} -> Hash {job data}
queue:scheduled:{timestamp}:{job_id} -> Hash {job data}

# IoT Data Cache
iot:device:{device_id}:latest -> Hash {last_reading, timestamp, status}
iot:device:{device_id}:aggregated:{hour} -> Hash {avg, min, max}
iot:alert:{device_id} -> List [alerts]

# B2B Subscription Cache
b2b:subscription:{partner_id} -> Hash {plan_details, quota_remaining, etc.}
b2b:quota:{partner_id}:{month} -> String {usage_count}
b2b:pricing:{partner_id}:{product_id} -> String {negotiated_price}

# Search Cache
search:results:{query_hash} -> String {cached_results}
search:suggestions:{prefix} -> List [suggestions]
search:recent:{user_id} -> List [recent_searches]
```

---

## 4. Schema Design for Core Entities (Days 14-16)

### 4.1 Master Data Tables

#### 4.1.1 Company and Partner Extensions

```sql
-- ============================================================================
-- TABLE: smart_dairy_core.company_settings
-- PURPOSE: Extended company configuration for Smart Dairy
-- ============================================================================
CREATE TABLE smart_dairy_core.company_settings (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id) ON DELETE CASCADE,
    
    -- Farm Information
    farm_type VARCHAR(50) NOT NULL DEFAULT 'DAIRY_FARM',
    farm_size_hectares DECIMAL(10, 2),
    total_animals_capacity INTEGER,
    milking_parlor_count INTEGER DEFAULT 1,
    
    -- Operational Settings
    milking_sessions_per_day INTEGER DEFAULT 2,
    first_session_time TIME DEFAULT '06:00:00',
    second_session_time TIME DEFAULT '16:00:00',
    third_session_time TIME,
    
    -- Quality Standards
    min_fat_percentage DECIMAL(4, 2) DEFAULT 3.5,
    min_snf_percentage DECIMAL(4, 2) DEFAULT 8.5,
    quality_testing_frequency VARCHAR(20) DEFAULT 'DAILY',
    
    -- Financial Settings
    default_currency_id INTEGER,
    fiscal_year_start_month INTEGER DEFAULT 7, -- July for Bangladesh
    default_payment_terms_days INTEGER DEFAULT 30,
    
    -- Compliance Settings
    vat_enabled BOOLEAN DEFAULT TRUE,
    vat_rate DECIMAL(5, 2) DEFAULT 15.00,
    tds_enabled BOOLEAN DEFAULT TRUE,
    bsti_compliance_required BOOLEAN DEFAULT TRUE,
    
    -- IoT Integration
    iot_enabled BOOLEAN DEFAULT FALSE,
    iot_data_retention_days INTEGER DEFAULT 730,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    
    CONSTRAINT unique_company_settings UNIQUE (company_id)
);

COMMENT ON TABLE smart_dairy_core.company_settings IS 
    'Extended configuration settings for dairy companies';

-- Index for common lookups
CREATE INDEX idx_company_settings_company ON smart_dairy_core.company_settings(company_id);

-- ============================================================================
-- TABLE: smart_dairy_core.partner_classification
-- PURPOSE: B2B partner tier and classification system
-- ============================================================================
CREATE TABLE smart_dairy_b2b.partner_classification (
    id SERIAL PRIMARY KEY,
    partner_id INTEGER NOT NULL REFERENCES res_partner(id) ON DELETE CASCADE,
    
    -- Classification
    partner_type VARCHAR(30) NOT NULL, -- RETAILER, WHOLESALER, PROCESSOR, EXPORTER
    tier_level VARCHAR(10) NOT NULL DEFAULT 'BRONZE', -- BRONZE, SILVER, GOLD, PLATINUM
    
    -- Credit and Payment
    credit_limit DECIMAL(15, 2) DEFAULT 0,
    credit_days INTEGER DEFAULT 0,
    payment_terms VARCHAR(50),
    security_deposit_amount DECIMAL(15, 2) DEFAULT 0,
    
    -- Pricing
    discount_percentage DECIMAL(5, 2) DEFAULT 0,
    special_pricing_agreement TEXT,
    minimum_order_quantity DECIMAL(10, 2) DEFAULT 0,
    
    -- Contract Details
    contract_start_date DATE,
    contract_end_date DATE,
    contract_renewal_reminder_days INTEGER DEFAULT 30,
    
    -- Performance Metrics
    monthly_quota_kg DECIMAL(12, 2),
    year_to_date_purchases DECIMAL(15, 2) DEFAULT 0,
    average_order_value DECIMAL(15, 2),
    on_time_payment_percentage DECIMAL(5, 2) DEFAULT 100,
    
    -- Status
    status VARCHAR(20) DEFAULT 'PENDING_APPROVAL',
    approval_date DATE,
    approved_by INTEGER,
    
    -- Delivery Preferences
    preferred_delivery_time_start TIME,
    preferred_delivery_time_end TIME,
    delivery_days TEXT[], -- Array of day names
    special_delivery_instructions TEXT,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    
    CONSTRAINT unique_partner_classification UNIQUE (partner_id)
);

COMMENT ON TABLE smart_dairy_b2b.partner_classification IS 
    'B2B partner tier classification and commercial terms';

CREATE INDEX idx_partner_classification_partner ON smart_dairy_b2b.partner_classification(partner_id);
CREATE INDEX idx_partner_classification_tier ON smart_dairy_b2b.partner_classification(tier_level);
CREATE INDEX idx_partner_classification_type ON smart_dairy_b2b.partner_classification(partner_type);
```

#### 4.1.2 Product Master Extensions

```sql
-- ============================================================================
-- TABLE: smart_dairy_core.product_dairy_attributes
-- PURPOSE: Dairy-specific product attributes
-- ============================================================================
CREATE TABLE smart_dairy_core.product_dairy_attributes (
    id SERIAL PRIMARY KEY,
    product_id INTEGER NOT NULL REFERENCES product_product(id) ON DELETE CASCADE,
    
    -- Product Classification
    dairy_product_type VARCHAR(50), -- RAW_MILK, PASTEURIZED, UHT, YOGURT, CHEESE, etc.
    processing_method VARCHAR(50),
    storage_type VARCHAR(30), -- REFRIGERATED, FROZEN, SHELF_STABLE
    shelf_life_days INTEGER,
    
    -- Quality Specifications
    target_fat_min DECIMAL(4, 2),
    target_fat_max DECIMAL(4, 2),
    target_snf_min DECIMAL(4, 2),
    target_snf_max DECIMAL(4, 2),
    target_protein_min DECIMAL(4, 2),
    target_ph_min DECIMAL(3, 2),
    target_ph_max DECIMAL(3, 2),
    
    -- Packaging
    packaging_type VARCHAR(50),
    packaging_size_liters DECIMAL(6, 2),
    units_per_case INTEGER,
    
    -- Pricing
    cost_calculation_method VARCHAR(30), -- FAT_BASED, SNF_BASED, FLAT_RATE
    base_price_per_liter DECIMAL(10, 2),
    fat_premium_rate DECIMAL(8, 4),
    snf_premium_rate DECIMAL(8, 4),
    
    -- B2B Specific
    b2b_enabled BOOLEAN DEFAULT FALSE,
    b2b_minimum_order DECIMAL(10, 2),
    b2b_pricing_tier_id INTEGER,
    
    -- Compliance
    bsti_license_required BOOLEAN DEFAULT FALSE,
    bsti_license_number VARCHAR(50),
    organic_certified BOOLEAN DEFAULT FALSE,
    halal_certified BOOLEAN DEFAULT TRUE,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    
    CONSTRAINT unique_product_dairy_attrs UNIQUE (product_id)
);

COMMENT ON TABLE smart_dairy_core.product_dairy_attributes IS 
    'Dairy-specific product attributes and quality specifications';

CREATE INDEX idx_product_dairy_attrs_product ON smart_dairy_core.product_dairy_attributes(product_id);
CREATE INDEX idx_product_dairy_attrs_type ON smart_dairy_core.product_dairy_attributes(dairy_product_type);

-- ============================================================================
-- TABLE: smart_dairy_core.product_pricing_history
-- PURPOSE: Track historical product pricing
-- ============================================================================
CREATE TABLE smart_dairy_core.product_pricing_history (
    id BIGSERIAL PRIMARY KEY,
    product_id INTEGER NOT NULL REFERENCES product_product(id),
    
    -- Pricing Details
    effective_date DATE NOT NULL,
    end_date DATE,
    price_type VARCHAR(20) NOT NULL, -- BASE, FAT_PREMIUM, SNF_PREMIUM, B2B_SPECIAL
    price_value DECIMAL(12, 4) NOT NULL,
    currency_id INTEGER,
    
    -- Context
    company_id INTEGER,
    partner_id INTEGER, -- For B2B special pricing
    minimum_quantity DECIMAL(10, 2),
    
    -- Reason
    change_reason VARCHAR(100),
    approved_by INTEGER,
    
    -- Audit
    created_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER
) PARTITION BY RANGE (effective_date);

COMMENT ON TABLE smart_dairy_core.product_pricing_history IS 
    'Historical record of product pricing changes';

CREATE INDEX idx_pricing_history_product ON smart_dairy_core.product_pricing_history(product_id);
CREATE INDEX idx_pricing_history_date ON smart_dairy_core.product_pricing_history(effective_date);
```

### 4.2 Animal Management Tables


#### 4.2.1 Animal Master Table

```sql
-- ============================================================================
-- TABLE: smart_dairy_animals.animal
-- PURPOSE: Central animal registry and lifecycle management
-- ============================================================================
CREATE TABLE smart_dairy_animals.animal (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    -- Identification
    internal_id VARCHAR(50) NOT NULL,
    tag_number VARCHAR(50) NOT NULL,
    rfid_tag VARCHAR(100),
    ear_tag_left VARCHAR(50),
    ear_tag_right VARCHAR(50),
    registration_number VARCHAR(100),
    
    -- Basic Information
    name VARCHAR(100),
    breed_id INTEGER REFERENCES smart_dairy_animals.breed(id),
    gender VARCHAR(10) NOT NULL CHECK (gender IN ('FEMALE', 'MALE', 'UNKNOWN')),
    animal_type VARCHAR(20) NOT NULL DEFAULT 'COW', -- COW, BUFFALO, CALF, BULL
    color_markings VARCHAR(100),
    
    -- Birth Information
    birth_date DATE,
    birth_weight_kg DECIMAL(6, 2),
    birth_type VARCHAR(20), -- SINGLE, TWIN, TRIPLET
    dam_id INTEGER REFERENCES smart_dairy_animals.animal(id),
    sire_id INTEGER REFERENCES smart_dairy_animals.animal(id),
    
    -- Origin
    origin_type VARCHAR(30), -- BORN_ON_FARM, PURCHASED, TRANSFERRED
    origin_date DATE,
    origin_source VARCHAR(200),
    purchase_price DECIMAL(12, 2),
    
    -- Current Status
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    lifecycle_stage VARCHAR(30), -- CALF, HEIFER, LACTATING, DRY, PREGNANT
    is_pregnant BOOLEAN DEFAULT FALSE,
    expected_calving_date DATE,
    
    -- Location
    barn_id INTEGER,
    stall_number VARCHAR(20),
    pasture_id INTEGER,
    
    -- Production Status
    current_lactation_number INTEGER DEFAULT 0,
    days_in_milk INTEGER DEFAULT 0,
    current_daily_yield DECIMAL(6, 2) DEFAULT 0,
    
    -- Health Status
    health_status VARCHAR(20) DEFAULT 'HEALTHY',
    quarantine_status VARCHAR(20) DEFAULT 'NONE',
    quarantine_end_date DATE,
    
    -- Financial
    current_value DECIMAL(12, 2),
    accumulated_cost DECIMAL(12, 2) DEFAULT 0,
    
    -- Important Dates
    first_calving_date DATE,
    last_calving_date DATE,
    last_breeding_date DATE,
    last_heat_date DATE,
    
    -- Deceased/Disposed
    disposal_date DATE,
    disposal_reason VARCHAR(100),
    disposal_method VARCHAR(50),
    disposal_value DECIMAL(10, 2),
    
    -- Media
    photo_attachment_id INTEGER,
    documents JSONB,
    
    -- Notes
    notes TEXT,
    special_needs TEXT,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    
    CONSTRAINT unique_animal_tag UNIQUE (company_id, tag_number),
    CONSTRAINT unique_animal_internal_id UNIQUE (company_id, internal_id)
) TABLESPACE smart_dairy_data;

COMMENT ON TABLE smart_dairy_animals.animal IS 
    'Central registry for all animals in the dairy farm';

-- Indexes for Animal Table
CREATE INDEX idx_animal_company ON smart_dairy_animals.animal(company_id);
CREATE INDEX idx_animal_tag ON smart_dairy_animals.animal(tag_number);
CREATE INDEX idx_animal_breed ON smart_dairy_animals.animal(breed_id);
CREATE INDEX idx_animal_status ON smart_dairy_animals.animal(status);
CREATE INDEX idx_animal_lifecycle ON smart_dairy_animals.animal(lifecycle_stage);
CREATE INDEX idx_animal_location ON smart_dairy_animals.animal(barn_id, stall_number);
CREATE INDEX idx_animal_birth_date ON smart_dairy_animals.animal(birth_date);
CREATE INDEX idx_animal_health ON smart_dairy_animals.animal(health_status);
CREATE INDEX idx_animal_pregnant ON smart_dairy_animals.animal(is_pregnant) WHERE is_pregnant = TRUE;

-- GIN index for JSONB documents
CREATE INDEX idx_animal_documents ON smart_dairy_animals.animal USING GIN (documents);

-- ============================================================================
-- TABLE: smart_dairy_animals.breed
-- PURPOSE: Animal breed master data
-- ============================================================================
CREATE TABLE smart_dairy_animals.breed (
    id SERIAL PRIMARY KEY,
    company_id INTEGER REFERENCES res_company(id),
    
    breed_code VARCHAR(20) NOT NULL,
    breed_name VARCHAR(100) NOT NULL,
    species VARCHAR(20) NOT NULL DEFAULT 'CATTLE', -- CATTLE, BUFFALO
    origin_country VARCHAR(100),
    
    -- Characteristics
    average_milk_yield_kg DECIMAL(8, 2),
    average_fat_percentage DECIMAL(4, 2),
    maturity_age_months INTEGER,
    average_weight_kg DECIMAL(6, 2),
    
    -- Description
    description TEXT,
    characteristics JSONB,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    is_purebred BOOLEAN DEFAULT TRUE,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    
    CONSTRAINT unique_breed_code UNIQUE (company_id, breed_code)
);

COMMENT ON TABLE smart_dairy_animals.breed IS 'Master data for animal breeds';

CREATE INDEX idx_breed_company ON smart_dairy_animals.breed(company_id);
CREATE INDEX idx_breed_species ON smart_dairy_animals.breed(species);

-- ============================================================================
-- TABLE: smart_dairy_animals.animal_lifecycle_events
-- PURPOSE: Track major lifecycle events for each animal
-- ============================================================================
CREATE TABLE smart_dairy_animals.animal_lifecycle_events (
    id BIGSERIAL PRIMARY KEY,
    animal_id INTEGER NOT NULL REFERENCES smart_dairy_animals.animal(id),
    
    event_type VARCHAR(50) NOT NULL,
    -- BIRTH, PURCHASE, SALE, DEATH, BREEDING, CALVING, WEANING, 
    -- FIRST_HEAT, FIRST_SERVICE, DRY_OFF, TRANSFER, EXPORT
    
    event_date DATE NOT NULL,
    event_time TIME,
    
    -- Related Entities
    related_animal_id INTEGER REFERENCES smart_dairy_animals.animal(id),
    partner_id INTEGER REFERENCES res_partner(id),
    
    -- Details
    description TEXT,
    weight_kg DECIMAL(6, 2),
    value DECIMAL(12, 2),
    location_from VARCHAR(100),
    location_to VARCHAR(100),
    
    -- Documents
    document_reference VARCHAR(100),
    attachment_ids INTEGER[],
    
    -- Additional Data
    metadata JSONB,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER
) TABLESPACE smart_dairy_data;

COMMENT ON TABLE smart_dairy_animals.animal_lifecycle_events IS 
    'Historical record of lifecycle events for each animal';

CREATE INDEX idx_lifecycle_animal ON smart_dairy_animals.animal_lifecycle_events(animal_id);
CREATE INDEX idx_lifecycle_type ON smart_dairy_animals.animal_lifecycle_events(event_type);
CREATE INDEX idx_lifecycle_date ON smart_dairy_animals.animal_lifecycle_events(event_date);
CREATE INDEX idx_lifecycle_animal_date ON smart_dairy_animals.animal_lifecycle_events(animal_id, event_date);
```

#### 4.2.2 Breeding and Reproduction Tables

```sql
-- ============================================================================
-- TABLE: smart_dairy_animals.breeding_record
-- PURPOSE: Track all breeding events and artificial insemination
-- ============================================================================
CREATE TABLE smart_dairy_animals.breeding_record (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    -- Female Animal (Dam)
    female_id INTEGER NOT NULL REFERENCES smart_dairy_animals.animal(id),
    female_tag_number VARCHAR(50) NOT NULL,
    lactation_number INTEGER,
    days_in_milk INTEGER,
    
    -- Male Animal or Semen
    male_type VARCHAR(20) NOT NULL, -- NATURAL, AI_SEXED, AI_CONVENTIONAL, EMBRYO
    male_id INTEGER REFERENCES smart_dairy_animals.animal(id),
    sire_tag_number VARCHAR(50),
    sire_breed_id INTEGER,
    semen_batch_id INTEGER REFERENCES smart_dairy_animals.semen_batch(id),
    semen_code VARCHAR(50),
    
    -- Breeding Details
    breeding_date DATE NOT NULL,
    breeding_time TIME,
    breeding_method VARCHAR(30), -- NATURAL, AI, ET
    inseminator_id INTEGER REFERENCES hr_employee(id),
    
    -- Heat Detection
    heat_date DATE,
    heat_signs TEXT[],
    heat_intensity VARCHAR(20),
    
    -- Cost
    breeding_cost DECIMAL(10, 2) DEFAULT 0,
    semen_cost DECIMAL(10, 2) DEFAULT 0,
    service_charge DECIMAL(10, 2) DEFAULT 0,
    
    -- Outcome
    confirmed_pregnant BOOLEAN,
    confirmation_date DATE,
    confirmation_method VARCHAR(30), -- PALPATION, ULTRASOUND, BLOOD_TEST
    confirmed_by INTEGER REFERENCES hr_employee(id),
    
    -- Pregnancy Result
    expected_calving_date DATE,
    actual_calving_date DATE,
    calving_result VARCHAR(30), -- SUCCESSFUL, ABORTION, STILLBIRTH
    calving_record_id INTEGER,
    
    -- Status
    status VARCHAR(20) DEFAULT 'PENDING',
    repeat_breeding BOOLEAN DEFAULT FALSE,
    repeat_count INTEGER DEFAULT 0,
    
    -- Notes
    notes TEXT,
    complications TEXT,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER
) TABLESPACE smart_dairy_data;

COMMENT ON TABLE smart_dairy_animals.breeding_record IS 
    'Comprehensive breeding and AI records';

CREATE INDEX idx_breeding_female ON smart_dairy_animals.breeding_record(female_id);
CREATE INDEX idx_breeding_male ON smart_dairy_animals.breeding_record(male_id);
CREATE INDEX idx_breeding_date ON smart_dairy_animals.breeding_record(breeding_date);
CREATE INDEX idx_breeding_status ON smart_dairy_animals.breeding_record(status);
CREATE INDEX idx_breeding_expected ON smart_dairy_animals.breeding_record(expected_calving_date);

-- ============================================================================
-- TABLE: smart_dairy_animals.semen_batch
-- PURPOSE: Semen inventory and batch management
-- ============================================================================
CREATE TABLE smart_dairy_animals.semen_batch (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    batch_code VARCHAR(50) NOT NULL,
    semen_code VARCHAR(50) NOT NULL,
    
    -- Sire Information
    sire_name VARCHAR(100),
    sire_breed_id INTEGER,
    sire_registration VARCHAR(100),
    sire_country VARCHAR(50),
    
    -- Product Details
    supplier_id INTEGER REFERENCES res_partner(id),
    manufacture_date DATE,
    expiry_date DATE,
    
    -- Genetic Information
    genetic_merit_score DECIMAL(5, 2),
    proof_details JSONB,
    
    -- Inventory
    initial_quantity INTEGER NOT NULL,
    available_quantity INTEGER NOT NULL,
    used_quantity INTEGER DEFAULT 0,
    discarded_quantity INTEGER DEFAULT 0,
    
    -- Storage
    tank_number VARCHAR(20),
    canister_number VARCHAR(20),
    cane_number VARCHAR(20),
    
    -- Quality
    quality_grade VARCHAR(10),
    motility_percentage DECIMAL(5, 2),
    concentration_m_per_ml DECIMAL(10, 2),
    
    -- Pricing
    unit_cost DECIMAL(10, 2),
    
    -- Status
    status VARCHAR(20) DEFAULT 'ACTIVE',
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    
    CONSTRAINT unique_semen_batch UNIQUE (company_id, batch_code)
);

COMMENT ON TABLE smart_dairy_animals.semen_batch IS 'Semen batch inventory management';

CREATE INDEX idx_semen_batch_company ON smart_dairy_animals.semen_batch(company_id);
CREATE INDEX idx_semen_batch_code ON smart_dairy_animals.semen_batch(semen_code);
CREATE INDEX idx_semen_expiry ON smart_dairy_animals.semen_batch(expiry_date);
CREATE INDEX idx_semen_supplier ON smart_dairy_animals.semen_batch(supplier_id);

-- ============================================================================
-- TABLE: smart_dairy_animals.pregnancy_check
-- PURPOSE: Pregnancy diagnosis records
-- ============================================================================
CREATE TABLE smart_dairy_animals.pregnancy_check (
    id SERIAL PRIMARY KEY,
    breeding_id INTEGER NOT NULL REFERENCES smart_dairy_animals.breeding_record(id),
    animal_id INTEGER NOT NULL REFERENCES smart_dairy_animals.animal(id),
    
    check_date DATE NOT NULL,
    check_method VARCHAR(30) NOT NULL, -- PALPATION, ULTRASOUND, BLOOD_PAG, MILK_PAG
    days_after_breeding INTEGER,
    
    -- Results
    result VARCHAR(20) NOT NULL, -- PREGNANT, OPEN, DOUBTFUL, EARLY_EMBRYONIC_DEATH
    pregnancy_stage VARCHAR(30), -- EARLY, MID, LATE
    expected_calving_date DATE,
    
    -- Details
    fetus_count INTEGER,
    fetus_viability VARCHAR(20),
    uterine_health VARCHAR(30),
    
    -- Personnel
    checked_by INTEGER REFERENCES hr_employee(id),
    veterinarian_id INTEGER REFERENCES res_partner(id),
    
    -- Cost
    check_cost DECIMAL(10, 2),
    
    -- Notes
    notes TEXT,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER
);

COMMENT ON TABLE smart_dairy_animals.pregnancy_check IS 'Pregnancy diagnosis records';

CREATE INDEX idx_preg_check_breeding ON smart_dairy_animals.pregnancy_check(breeding_id);
CREATE INDEX idx_preg_check_animal ON smart_dairy_animals.pregnancy_check(animal_id);
CREATE INDEX idx_preg_check_date ON smart_dairy_animals.pregnancy_check(check_date);

-- ============================================================================
-- TABLE: smart_dairy_animals.calving_record
-- PURPOSE: Calving event records and offspring registration
-- ============================================================================
CREATE TABLE smart_dairy_animals.calving_record (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    -- Parent Information
    dam_id INTEGER NOT NULL REFERENCES smart_dairy_animals.animal(id),
    breeding_id INTEGER REFERENCES smart_dairy_animals.breeding_record(id),
    
    -- Calving Details
    calving_date DATE NOT NULL,
    calving_time TIME,
    calving_type VARCHAR(20), -- NORMAL, ASSISTED, CESAREAN, DIFFICULT
    
    -- Location
    barn_id INTEGER,
    attended_by INTEGER REFERENCES hr_employee(id),
    veterinarian_id INTEGER REFERENCES res_partner(id),
    
    -- Calving Ease
    calving_ease_score INTEGER CHECK (calving_ease_score BETWEEN 1 AND 5),
    complications TEXT,
    treatment_given TEXT,
    
    -- Offspring Details
    offspring_count INTEGER NOT NULL DEFAULT 1,
    live_offspring INTEGER DEFAULT 1,
    stillborn_offspring INTEGER DEFAULT 0,
    
    -- Dam Post-Calving
    dam_condition VARCHAR(30),
    retained_placenta BOOLEAN DEFAULT FALSE,
    milk_fever BOOLEAN DEFAULT FALSE,
    mastitis BOOLEAN DEFAULT FALSE,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER
);

COMMENT ON TABLE smart_dairy_animals.calving_record IS 'Calving event records';

CREATE INDEX idx_calving_dam ON smart_dairy_animals.calving_record(dam_id);
CREATE INDEX idx_calving_breeding ON smart_dairy_animals.calving_record(breeding_id);
CREATE INDEX idx_calving_date ON smart_dairy_animals.calving_record(calving_date);

-- ============================================================================
-- TABLE: smart_dairy_animals.offspring
-- PURPOSE: Individual offspring records from calving
-- ============================================================================
CREATE TABLE smart_dairy_animals.offspring (
    id SERIAL PRIMARY KEY,
    calving_id INTEGER NOT NULL REFERENCES smart_dairy_animals.calving_record(id),
    
    -- Link to Animal Registry (if registered)
    animal_id INTEGER REFERENCES smart_dairy_animals.animal(id),
    
    -- Birth Details
    birth_sequence INTEGER NOT NULL, -- 1, 2, 3 for twins, triplets
    gender VARCHAR(10) NOT NULL,
    birth_weight_kg DECIMAL(6, 2),
    
    -- Health at Birth
    apgar_score INTEGER,
    vitality_score INTEGER,
    congenital_defects TEXT,
    
    -- Colostrum
    colostrum_fed BOOLEAN DEFAULT FALSE,
    colostrum_time_after_birth INTEGER, -- minutes
    colostrum_quantity_ml INTEGER,
    
    -- Weaning
    weaning_date DATE,
    weaning_weight_kg DECIMAL(6, 2),
    
    -- Status
    status VARCHAR(20) DEFAULT 'ALIVE',
    disposal_date DATE,
    disposal_reason VARCHAR(100),
    
    -- Notes
    notes TEXT,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER
);

COMMENT ON TABLE smart_dairy_animals.offspring IS 'Individual offspring details from calving';

CREATE INDEX idx_offspring_calving ON smart_dairy_animals.offspring(calving_id);
CREATE INDEX idx_offspring_animal ON smart_dairy_animals.offspring(animal_id);
```

### 4.3 Milk Production Tables

```sql
-- ============================================================================
-- TABLE: smart_dairy_production.milking_session
-- PURPOSE: Individual milking session records
-- ============================================================================
CREATE TABLE smart_dairy_production.milking_session (
    id BIGSERIAL,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    -- Animal Information
    animal_id INTEGER NOT NULL REFERENCES smart_dairy_animals.animal(id),
    animal_tag_number VARCHAR(50) NOT NULL,
    lactation_number INTEGER,
    days_in_milk INTEGER,
    
    -- Session Details
    session_date DATE NOT NULL,
    session_type VARCHAR(20) NOT NULL, -- MORNING, MIDDAY, EVENING, NIGHT
    session_number INTEGER, -- 1, 2, 3 for multiple milkings
    
    -- Timing
    entry_time TIMESTAMPTZ,
    start_time TIMESTAMPTZ,
    end_time TIMESTAMPTZ,
    duration_seconds INTEGER,
    
    -- Yield
    quantity_kg DECIMAL(8, 3) NOT NULL,
    quantity_liters DECIMAL(8, 3),
    flow_rate_avg DECIMAL(6, 2), -- kg/min
    flow_rate_peak DECIMAL(6, 2),
    
    -- Equipment
    milking_parlor_id INTEGER,
    milking_unit_number INTEGER,
    operator_id INTEGER REFERENCES hr_employee(id),
    
    -- Quality Measurements
    temperature_celsius DECIMAL(4, 1),
    ph_value DECIMAL(3, 2),
    conductivity DECIMAL(8, 2),
    
    -- Lab Results
    fat_percentage DECIMAL(4, 2),
    snf_percentage DECIMAL(4, 2),
    protein_percentage DECIMAL(4, 2),
    lactose_percentage DECIMAL(4, 2),
    total_solids_percentage DECIMAL(4, 2),
    density DECIMAL(5, 3),
    freezing_point DECIMAL(4, 2),
    
    -- Quality Indicators
    somatic_cell_count INTEGER, -- cells/ml
    bacterial_count INTEGER, -- CFU/ml
    antibiotic_residue BOOLEAN DEFAULT FALSE,
    adulteration_test VARCHAR(20), -- PASS, FAIL
    
    -- Quality Grade
    quality_grade VARCHAR(10), -- PREMIUM, GRADE_A, GRADE_B, REJECTED
    quality_rejection_reason TEXT,
    
    -- Financial
    unit_price DECIMAL(10, 4),
    total_value DECIMAL(10, 2),
    
    -- Status
    status VARCHAR(20) DEFAULT 'APPROVED',
    verified_by INTEGER REFERENCES res_users(id),
    verified_at TIMESTAMPTZ,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    
    PRIMARY KEY (id, session_date)
) PARTITION BY RANGE (session_date);

COMMENT ON TABLE smart_dairy_production.milking_session IS 
    'Individual animal milking session records with quality metrics';

-- Create initial partitions
CREATE TABLE smart_dairy_production.milking_session_2026_01 
    PARTITION OF smart_dairy_production.milking_session
    FOR VALUES FROM ('2026-01-01') TO ('2026-02-01');

CREATE INDEX idx_milking_animal ON smart_dairy_production.milking_session(animal_id);
CREATE INDEX idx_milking_date ON smart_dairy_production.milking_session(session_date);
CREATE INDEX idx_milking_animal_date ON smart_dairy_production.milking_session(animal_id, session_date);
CREATE INDEX idx_milking_session_type ON smart_dairy_production.milking_session(session_type);
CREATE INDEX idx_milking_quality ON smart_dairy_production.milking_session(quality_grade);

-- ============================================================================
-- TABLE: smart_dairy_production.daily_production_summary
-- PURPOSE: Aggregated daily production by animal
-- ============================================================================
CREATE TABLE smart_dairy_production.daily_production_summary (
    id BIGSERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    animal_id INTEGER NOT NULL REFERENCES smart_dairy_animals.animal(id),
    summary_date DATE NOT NULL,
    
    -- Session Counts
    sessions_count INTEGER DEFAULT 0,
    
    -- Total Yields
    total_quantity_kg DECIMAL(8, 3) DEFAULT 0,
    total_quantity_liters DECIMAL(8, 3) DEFAULT 0,
    
    -- Session Breakdown
    morning_yield_kg DECIMAL(8, 3) DEFAULT 0,
    midday_yield_kg DECIMAL(8, 3) DEFAULT 0,
    evening_yield_kg DECIMAL(8, 3) DEFAULT 0,
    night_yield_kg DECIMAL(8, 3) DEFAULT 0,
    
    -- Quality Averages
    avg_fat_percentage DECIMAL(4, 2),
    avg_snf_percentage DECIMAL(4, 2),
    avg_protein_percentage DECIMAL(4, 2),
    avg_somatic_cell_count INTEGER,
    
    -- Quality Weighted Averages
    weighted_fat_kg DECIMAL(6, 3),
    weighted_snf_kg DECIMAL(6, 3),
    weighted_protein_kg DECIMAL(6, 3),
    
    -- Financial
    total_value DECIMAL(10, 2) DEFAULT 0,
    avg_unit_price DECIMAL(10, 4),
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    
    CONSTRAINT unique_daily_summary UNIQUE (animal_id, summary_date)
);

COMMENT ON TABLE smart_dairy_production.daily_production_summary IS 
    'Daily aggregated milk production by animal';

CREATE INDEX idx_daily_summary_animal ON smart_dairy_production.daily_production_summary(animal_id);
CREATE INDEX idx_daily_summary_date ON smart_dairy_production.daily_production_summary(summary_date);
CREATE INDEX idx_daily_summary_company_date ON smart_dairy_production.daily_production_summary(company_id, summary_date);

-- ============================================================================
-- TABLE: smart_dairy_production.milk_collection_center
-- PURPOSE: External milk collection points
-- ============================================================================
CREATE TABLE smart_dairy_production.milk_collection_center (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    center_code VARCHAR(20) NOT NULL,
    center_name VARCHAR(100) NOT NULL,
    
    -- Location
    address TEXT,
    district VARCHAR(50),
    thana VARCHAR(50),
    village VARCHAR(100),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    
    -- Contact
    contact_person VARCHAR(100),
    contact_phone VARCHAR(20),
    contact_email VARCHAR(100),
    
    -- Operations
    operating_hours VARCHAR(100),
    collection_times TEXT[],
    
    -- Capacity
    daily_capacity_liters DECIMAL(10, 2),
    storage_capacity_liters DECIMAL(10, 2),
    
    -- Equipment
    has_cooling_facility BOOLEAN DEFAULT FALSE,
    has_quality_testing BOOLEAN DEFAULT FALSE,
    equipment_details JSONB,
    
    -- Status
    status VARCHAR(20) DEFAULT 'ACTIVE',
    start_date DATE,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    
    CONSTRAINT unique_collection_center UNIQUE (company_id, center_code)
);

COMMENT ON TABLE smart_dairy_production.milk_collection_center IS 
    'External milk collection center management';

-- ============================================================================
-- TABLE: smart_dairy_production.collection_center_delivery
-- PURPOSE: Milk deliveries from collection centers
-- ============================================================================
CREATE TABLE smart_dairy_production.collection_center_delivery (
    id BIGSERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    center_id INTEGER NOT NULL REFERENCES smart_dairy_production.milk_collection_center(id),
    delivery_date DATE NOT NULL,
    delivery_number VARCHAR(50) NOT NULL,
    
    -- Supplier Information (farmer)
    supplier_id INTEGER REFERENCES res_partner(id),
    supplier_name VARCHAR(100),
    
    -- Quantity
    quantity_liters DECIMAL(10, 2) NOT NULL,
    quantity_kg DECIMAL(10, 3),
    
    -- Quality at Collection
    fat_percentage DECIMAL(4, 2),
    snf_percentage DECIMAL(4, 2),
    density DECIMAL(5, 3),
    temperature_celsius DECIMAL(4, 1),
    
    -- Quality Grade
    grade VARCHAR(10),
    rejection_reason TEXT,
    accepted_quantity_liters DECIMAL(10, 2),
    
    -- Pricing
    unit_price DECIMAL(10, 4),
    fat_premium DECIMAL(10, 4),
    snf_premium DECIMAL(10, 4),
    total_amount DECIMAL(12, 2),
    
    -- Payment
    payment_status VARCHAR(20) DEFAULT 'PENDING',
    payment_date DATE,
    payment_reference VARCHAR(50),
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    
    CONSTRAINT unique_delivery_number UNIQUE (company_id, delivery_number)
);

COMMENT ON TABLE smart_dairy_production.collection_center_delivery IS 
    'Milk delivery records from collection centers';
```

### 4.4 Health and Veterinary Tables

```sql
-- ============================================================================
-- TABLE: smart_dairy_health.health_record
-- PURPOSE: Comprehensive animal health records
-- ============================================================================
CREATE TABLE smart_dairy_health.health_record (
    id BIGSERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    animal_id INTEGER NOT NULL REFERENCES smart_dairy_animals.animal(id),
    animal_tag_number VARCHAR(50) NOT NULL,
    
    -- Event Details
    record_date DATE NOT NULL,
    record_type VARCHAR(30) NOT NULL,
    -- ILLNESS, INJURY, ROUTINE_CHECK, VACCINATION, DEWORMING, 
    -- SURGERY, LAMENESS, MASTITIS, METRITIS, RETAINED_PLACENTA
    
    -- Condition
    condition_category VARCHAR(50),
    condition_description TEXT,
    severity VARCHAR(20), -- MILD, MODERATE, SEVERE, CRITICAL
    
    -- Clinical Signs
    body_temperature DECIMAL(4, 1),
    pulse_rate INTEGER,
    respiratory_rate INTEGER,
    rumen_motility VARCHAR(20),
    appetite_status VARCHAR(20),
    milk_production_change VARCHAR(20), -- NORMAL, DECREASED, STOPPED
    
    -- Diagnosis
    provisional_diagnosis TEXT,
    final_diagnosis TEXT,
    diagnostic_tests TEXT,
    test_results JSONB,
    
    -- Treatment
    treatment_plan TEXT,
    medications JSONB, -- Array of medication objects
    treatment_cost DECIMAL(10, 2),
    
    -- Personnel
    examined_by INTEGER REFERENCES hr_employee(id),
    veterinarian_id INTEGER REFERENCES res_partner(id),
    
    -- Outcome
    outcome VARCHAR(20), -- RECOVERED, IMPROVED, CHRONIC, DECEASED, CULLED
    recovery_date DATE,
    follow_up_required BOOLEAN DEFAULT FALSE,
    follow_up_date DATE,
    
    -- Impact
    milk_withdrawal_days INTEGER,
    meat_withdrawal_days INTEGER,
    breeding_withdrawal_days INTEGER,
    
    -- Notes
    notes TEXT,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER
) TABLESPACE smart_dairy_data;

COMMENT ON TABLE smart_dairy_health.health_record IS 
    'Comprehensive animal health and treatment records';

CREATE INDEX idx_health_animal ON smart_dairy_health.health_record(animal_id);
CREATE INDEX idx_health_date ON smart_dairy_health.health_record(record_date);
CREATE INDEX idx_health_type ON smart_dairy_health.health_record(record_type);
CREATE INDEX idx_health_outcome ON smart_dairy_health.health_record(outcome);

-- ============================================================================
-- TABLE: smart_dairy_health.vaccination_record
-- PURPOSE: Vaccination and immunization tracking
-- ============================================================================
CREATE TABLE smart_dairy_health.vaccination_record (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    animal_id INTEGER NOT NULL REFERENCES smart_dairy_animals.animal(id),
    
    -- Vaccine Details
    vaccine_id INTEGER NOT NULL REFERENCES smart_dairy_health.vaccine_master(id),
    vaccine_name VARCHAR(100) NOT NULL,
    disease_protected VARCHAR(100),
    
    -- Administration
    administration_date DATE NOT NULL,
    batch_number VARCHAR(50),
    expiry_date DATE,
    
    -- Dosage
    dosage_ml DECIMAL(5, 2),
    route_of_administration VARCHAR(30), -- SUBCUTANEOUS, INTRAMUSCULAR, ORAL, INTRANASAL
    site_of_injection VARCHAR(50),
    
    -- Personnel
    administered_by INTEGER REFERENCES hr_employee(id),
    veterinarian_id INTEGER REFERENCES res_partner(id),
    
    -- Next Dose
    next_due_date DATE,
    booster_required BOOLEAN DEFAULT FALSE,
    
    -- Reaction
    adverse_reaction BOOLEAN DEFAULT FALSE,
    reaction_description TEXT,
    
    -- Cost
    vaccine_cost DECIMAL(10, 2),
    service_cost DECIMAL(10, 2),
    
    -- Notes
    notes TEXT,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER
);

COMMENT ON TABLE smart_dairy_health.vaccination_record IS 'Vaccination tracking records';

CREATE INDEX idx_vaccination_animal ON smart_dairy_health.vaccination_record(animal_id);
CREATE INDEX idx_vaccination_date ON smart_dairy_health.vaccination_record(administration_date);
CREATE INDEX idx_vaccination_next ON smart_dairy_health.vaccination_record(next_due_date);

-- ============================================================================
-- TABLE: smart_dairy_health.vaccine_master
-- PURPOSE: Vaccine catalog and inventory
-- ============================================================================
CREATE TABLE smart_dairy_health.vaccine_master (
    id SERIAL PRIMARY KEY,
    company_id INTEGER REFERENCES res_company(id),
    
    vaccine_code VARCHAR(20) NOT NULL,
    vaccine_name VARCHAR(100) NOT NULL,
    
    -- Classification
    vaccine_type VARCHAR(30), -- LIVE_ATTENUATED, KILLED, TOXOID, SUBUNIT
    target_species VARCHAR(50),
    diseases_covered TEXT[],
    
    -- Schedule
    primary_doses INTEGER DEFAULT 1,
    primary_dose_interval_days INTEGER,
    booster_frequency_months INTEGER,
    
    -- Administration
    route VARCHAR(30),
    dosage_ml DECIMAL(5, 2),
    age_at_first_dose_weeks INTEGER,
    
    -- Storage
    storage_requirements VARCHAR(100),
    shelf_life_months INTEGER,
    
    -- Product Details
    manufacturer VARCHAR(100),
    supplier_id INTEGER REFERENCES res_partner(id),
    unit_cost DECIMAL(10, 2),
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    
    CONSTRAINT unique_vaccine_code UNIQUE (company_id, vaccine_code)
);

COMMENT ON TABLE smart_dairy_health.vaccine_master IS 'Master catalog of vaccines';

-- ============================================================================
-- TABLE: smart_dairy_health.mastitis_record
-- PURPOSE: Detailed mastitis cases and treatments
-- ============================================================================
CREATE TABLE smart_dairy_health.mastitis_record (
    id SERIAL PRIMARY KEY,
    health_record_id INTEGER REFERENCES smart_dairy_health.health_record(id),
    
    animal_id INTEGER NOT NULL REFERENCES smart_dairy_animals.animal(id),
    
    -- Case Details
    case_date DATE NOT NULL,
    quarter_affected VARCHAR(20), -- LF, LR, RF, RR, MULTIPLE
    severity VARCHAR(20), -- SUBCLINICAL, MILD, MODERATE, SEVERE, GANGRENOUS
    
    -- CMT Results
    cmt_score INTEGER CHECK (cmt_score BETWEEN 0 AND 3),
    cmt_quarters JSONB, -- Scores for each quarter
    
    -- Laboratory
    milk_culture_done BOOLEAN DEFAULT FALSE,
    culture_result VARCHAR(100),
    pathogen_identified VARCHAR(100),
    antibiotic_sensitivity JSONB,
    somatic_cell_count INTEGER,
    
    -- Treatment
    treatment_protocol TEXT,
    intramammary_antibiotic VARCHAR(100),
    systemic_antibiotic VARCHAR(100),
    supportive_care TEXT,
    
    -- Milk Discard
    milk_withdrawal_start DATE,
    milk_withdrawal_end DATE,
    milk_discarded_liters DECIMAL(8, 2),
    
    -- Outcome
    days_to_recovery INTEGER,
    recurrence BOOLEAN DEFAULT FALSE,
    chronic BOOLEAN DEFAULT FALSE,
    
    -- Economic Impact
    treatment_cost DECIMAL(10, 2),
    milk_loss_value DECIMAL(10, 2),
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER
);

COMMENT ON TABLE smart_dairy_health.mastitis_record IS 'Detailed mastitis case records';

CREATE INDEX idx_mastitis_animal ON smart_dairy_health.mastitis_record(animal_id);
CREATE INDEX idx_mastitis_date ON smart_dairy_health.mastitis_record(case_date);
CREATE INDEX idx_mastitis_severity ON smart_dairy_health.mastitis_record(severity);
```

### 4.5 Feed Management Tables

```sql
-- ============================================================================
-- TABLE: smart_dairy_inventory.feed_formula
-- PURPOSE: Feed formulation and recipe management
-- ============================================================================
CREATE TABLE smart_dairy_inventory.feed_formula (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    formula_code VARCHAR(20) NOT NULL,
    formula_name VARCHAR(100) NOT NULL,
    
    -- Classification
    feed_type VARCHAR(50), -- CONCENTRATE, ROUGHAGE, MINERAL, COMPLETE
    animal_category VARCHAR(50), -- LACTATING, DRY, CALF, HEIFER, BULL
    production_stage VARCHAR(50), -- EARLY_LACTATION, MID_LACTATION, LATE_LACTATION
    
    -- Nutritional Targets
    target_dm_percentage DECIMAL(5, 2),
    target_cp_percentage DECIMAL(5, 2),
    target_tdn_percentage DECIMAL(5, 2),
    target_ndf_percentage DECIMAL(5, 2),
    target_adf_percentage DECIMAL(5, 2),
    target_ca_percentage DECIMAL(5, 3),
    target_p_percentage DECIMAL(5, 3),
    target_energy_mcal DECIMAL(5, 3),
    
    -- Formulation Method
    formulation_method VARCHAR(30), -- LINEAR_PROGRAMMING, PEARSON_SQUARE, MANUAL
    cost_optimization BOOLEAN DEFAULT TRUE,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    effective_date DATE NOT NULL,
    revision_number INTEGER DEFAULT 1,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    
    CONSTRAINT unique_feed_formula UNIQUE (company_id, formula_code)
);

COMMENT ON TABLE smart_dairy_inventory.feed_formula IS 'Feed formulation recipes';

-- ============================================================================
-- TABLE: smart_dairy_inventory.feed_formula_ingredient
-- PURPOSE: Ingredients for each feed formula
-- ============================================================================
CREATE TABLE smart_dairy_inventory.feed_formula_ingredient (
    id SERIAL PRIMARY KEY,
    formula_id INTEGER NOT NULL REFERENCES smart_dairy_inventory.feed_formula(id),
    
    -- Ingredient
    ingredient_id INTEGER NOT NULL REFERENCES product_product(id),
    ingredient_name VARCHAR(100) NOT NULL,
    
    -- Inclusion Rate
    inclusion_percentage DECIMAL(6, 3) NOT NULL,
    inclusion_kg_per_100kg DECIMAL(6, 3),
    min_inclusion_percentage DECIMAL(6, 3),
    max_inclusion_percentage DECIMAL(6, 3),
    
    -- Cost
    ingredient_cost_per_kg DECIMAL(8, 4),
    
    -- Order
    sequence INTEGER,
    
    -- Notes
    notes TEXT,
    
    created_at TIMESTAMPTZ DEFAULT NOW()
);

COMMENT ON TABLE smart_dairy_inventory.feed_formula_ingredient IS 
    'Individual ingredients in feed formulations';

-- ============================================================================
-- TABLE: smart_dairy_inventory.feeding_record
-- PURPOSE: Daily feeding records per animal/group
-- ============================================================================
CREATE TABLE smart_dairy_inventory.feeding_record (
    id BIGSERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    feeding_date DATE NOT NULL,
    
    -- Animal/Group
    animal_id INTEGER REFERENCES smart_dairy_animals.animal(id),
    group_id INTEGER, -- For group feeding
    group_name VARCHAR(100),
    
    -- Feed Details
    feed_id INTEGER REFERENCES product_product(id),
    feed_name VARCHAR(100) NOT NULL,
    formula_id INTEGER REFERENCES smart_dairy_inventory.feed_formula(id),
    
    -- Quantities
    quantity_offered_kg DECIMAL(8, 3) NOT NULL,
    quantity_consumed_kg DECIMAL(8, 3),
    quantity_refused_kg DECIMAL(8, 3),
    consumption_percentage DECIMAL(5, 2),
    
    -- Feeding Schedule
    feeding_time TIME,
    feeding_number INTEGER, -- 1, 2, 3 for multiple feedings
    
    -- Body Condition
    body_condition_score DECIMAL(3, 1),
    weight_kg DECIMAL(6, 2),
    
    -- Efficiency
    feed_cost_per_day DECIMAL(10, 2),
    milk_per_kg_feed DECIMAL(5, 2),
    
    -- Notes
    notes TEXT,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER
);

COMMENT ON TABLE smart_dairy_inventory.feeding_record IS 'Daily animal feeding records';

CREATE INDEX idx_feeding_animal ON smart_dairy_inventory.feeding_record(animal_id);
CREATE INDEX idx_feeding_date ON smart_dairy_inventory.feeding_record(feeding_date);
CREATE INDEX idx_feeding_animal_date ON smart_dairy_inventory.feeding_record(animal_id, feeding_date);
```

### 4.6 B2B and Subscription Tables

```sql
-- ============================================================================
-- TABLE: smart_dairy_b2b.subscription_plan
-- PURPOSE: B2B subscription plan definitions
-- ============================================================================
CREATE TABLE smart_dairy_b2b.subscription_plan (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    plan_code VARCHAR(20) NOT NULL,
    plan_name VARCHAR(100) NOT NULL,
    plan_description TEXT,
    
    -- Plan Type
    plan_type VARCHAR(30), -- FIXED_QUOTA, FLEXIBLE, UNLIMITED
    billing_frequency VARCHAR(20), -- MONTHLY, QUARTERLY, ANNUAL
    
    -- Quota
    monthly_quota_kg DECIMAL(12, 2),
    minimum_order_quantity DECIMAL(10, 2),
    maximum_order_quantity DECIMAL(10, 2),
    
    -- Pricing
    base_price_per_liter DECIMAL(10, 4),
    discount_percentage DECIMAL(5, 2) DEFAULT 0,
    
    -- Overage
    allow_overage BOOLEAN DEFAULT TRUE,
    overage_limit_percentage DECIMAL(5, 2) DEFAULT 20,
    overage_price_premium DECIMAL(5, 2) DEFAULT 10,
    
    -- Commitment
    minimum_commitment_months INTEGER DEFAULT 12,
    early_termination_fee DECIMAL(12, 2),
    
    -- Benefits
    features JSONB,
    priority_delivery BOOLEAN DEFAULT FALSE,
    dedicated_support BOOLEAN DEFAULT FALSE,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    effective_date DATE NOT NULL,
    expiry_date DATE,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    
    CONSTRAINT unique_plan_code UNIQUE (company_id, plan_code)
);

COMMENT ON TABLE smart_dairy_b2b.subscription_plan IS 'B2B subscription plan definitions';

-- ============================================================================
-- TABLE: smart_dairy_b2b.subscription
-- PURPOSE: Active B2B customer subscriptions
-- ============================================================================
CREATE TABLE smart_dairy_b2b.subscription (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    subscription_number VARCHAR(50) NOT NULL,
    
    -- Customer
    partner_id INTEGER NOT NULL REFERENCES res_partner(id),
    partner_classification_id INTEGER REFERENCES smart_dairy_b2b.partner_classification(id),
    
    -- Plan
    plan_id INTEGER NOT NULL REFERENCES smart_dairy_b2b.subscription_plan(id),
    plan_code VARCHAR(20) NOT NULL,
    
    -- Contract Period
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    renewal_type VARCHAR(20) DEFAULT 'AUTO', -- AUTO, MANUAL, NONE
    
    -- Custom Pricing
    negotiated_price_per_liter DECIMAL(10, 4),
    negotiated_quota_kg DECIMAL(12, 2),
    negotiated_discount DECIMAL(5, 2),
    
    -- Delivery Schedule
    delivery_frequency VARCHAR(20), -- DAILY, ALTERNATE, WEEKLY
    preferred_delivery_days TEXT[],
    preferred_delivery_time_start TIME,
    preferred_delivery_time_end TIME,
    delivery_address_id INTEGER,
    
    -- Status
    status VARCHAR(20) DEFAULT 'DRAFT',
    -- DRAFT, PENDING_APPROVAL, ACTIVE, SUSPENDED, CANCELLED, EXPIRED
    
    -- Approval
    requested_by INTEGER REFERENCES res_users(id),
    approved_by INTEGER REFERENCES res_users(id),
    approved_date DATE,
    
    -- Cancellation
    cancellation_date DATE,
    cancellation_reason TEXT,
    cancelled_by INTEGER REFERENCES res_users(id),
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    
    CONSTRAINT unique_subscription_number UNIQUE (company_id, subscription_number)
);

COMMENT ON TABLE smart_dairy_b2b.subscription IS 'B2B customer subscription records';

CREATE INDEX idx_subscription_partner ON smart_dairy_b2b.subscription(partner_id);
CREATE INDEX idx_subscription_status ON smart_dairy_b2b.subscription(status);
CREATE INDEX idx_subscription_dates ON smart_dairy_b2b.subscription(start_date, end_date);

-- ============================================================================
-- TABLE: smart_dairy_b2b.subscription_usage
-- PURPOSE: Track monthly subscription usage
-- ============================================================================
CREATE TABLE smart_dairy_b2b.subscription_usage (
    id BIGSERIAL PRIMARY KEY,
    subscription_id INTEGER NOT NULL REFERENCES smart_dairy_b2b.subscription(id),
    
    usage_year INTEGER NOT NULL,
    usage_month INTEGER NOT NULL CHECK (usage_month BETWEEN 1 AND 12),
    
    -- Quota
    monthly_quota_kg DECIMAL(12, 2) NOT NULL,
    
    -- Usage
    ordered_quantity_kg DECIMAL(12, 2) DEFAULT 0,
    delivered_quantity_kg DECIMAL(12, 2) DEFAULT 0,
    returned_quantity_kg DECIMAL(12, 2) DEFAULT 0,
    net_quantity_kg DECIMAL(12, 2) DEFAULT 0,
    
    -- Overage
    overage_quantity_kg DECIMAL(12, 2) DEFAULT 0,
    overage_charge DECIMAL(12, 2) DEFAULT 0,
    
    -- Financial
    base_amount DECIMAL(12, 2) DEFAULT 0,
    discount_amount DECIMAL(12, 2) DEFAULT 0,
    tax_amount DECIMAL(12, 2) DEFAULT 0,
    total_amount DECIMAL(12, 2) DEFAULT 0,
    
    -- Billing
    invoiced_amount DECIMAL(12, 2) DEFAULT 0,
    paid_amount DECIMAL(12, 2) DEFAULT 0,
    invoice_ids INTEGER[],
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    
    CONSTRAINT unique_monthly_usage UNIQUE (subscription_id, usage_year, usage_month)
);

COMMENT ON TABLE smart_dairy_b2b.subscription_usage IS 'Monthly subscription usage tracking';

CREATE INDEX idx_usage_subscription ON smart_dairy_b2b.subscription_usage(subscription_id);
CREATE INDEX idx_usage_period ON smart_dairy_b2b.subscription_usage(usage_year, usage_month);

-- ============================================================================
-- TABLE: smart_dairy_b2b.b2b_order
-- PURPOSE: B2B customer orders
-- ============================================================================
CREATE TABLE smart_dairy_b2b.b2b_order (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    order_number VARCHAR(50) NOT NULL,
    
    -- Customer
    partner_id INTEGER NOT NULL REFERENCES res_partner(id),
    subscription_id INTEGER REFERENCES smart_dairy_b2b.subscription(id),
    
    -- Order Details
    order_date DATE NOT NULL,
    order_type VARCHAR(20) DEFAULT 'REGULAR', -- REGULAR, ADHOC, EMERGENCY
    priority VARCHAR(20) DEFAULT 'NORMAL', -- LOW, NORMAL, HIGH, URGENT
    
    -- Products
    product_id INTEGER NOT NULL REFERENCES product_product(id),
    product_name VARCHAR(100) NOT NULL,
    quantity_kg DECIMAL(10, 2) NOT NULL,
    quantity_liters DECIMAL(10, 2),
    
    -- Pricing
    unit_price DECIMAL(10, 4) NOT NULL,
    discount_percentage DECIMAL(5, 2) DEFAULT 0,
    line_total DECIMAL(12, 2) NOT NULL,
    
    -- Delivery
    requested_delivery_date DATE NOT NULL,
    requested_delivery_time_start TIME,
    requested_delivery_time_end TIME,
    delivery_address_id INTEGER,
    
    -- Special Instructions
    special_instructions TEXT,
    quality_requirements TEXT,
    packaging_requirements TEXT,
    
    -- Status Workflow
    status VARCHAR(30) DEFAULT 'DRAFT',
    -- DRAFT, CONFIRMED, SCHEDULED, IN_TRANSIT, DELIVERED, CANCELLED
    
    -- Related Documents
    sale_order_id INTEGER,
    picking_id INTEGER,
    invoice_id INTEGER,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER,
    
    CONSTRAINT unique_order_number UNIQUE (company_id, order_number)
);

COMMENT ON TABLE smart_dairy_b2b.b2b_order IS 'B2B customer orders';

CREATE INDEX idx_b2b_order_partner ON smart_dairy_b2b.b2b_order(partner_id);
CREATE INDEX idx_b2b_order_subscription ON smart_dairy_b2b.b2b_order(subscription_id);
CREATE INDEX idx_b2b_order_date ON smart_dairy_b2b.b2b_order(order_date);
CREATE INDEX idx_b2b_order_status ON smart_dairy_b2b.b2b_order(status);
CREATE INDEX idx_b2b_order_delivery ON smart_dairy_b2b.b2b_order(requested_delivery_date);
```

### 4.7 Bangladesh Localization Tables

```sql
-- ============================================================================
-- TABLE: smart_dairy_core.bd_vat_registration
-- PURPOSE: Bangladesh VAT BIN management
-- ============================================================================
CREATE TABLE smart_dairy_core.bd_vat_registration (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    partner_id INTEGER REFERENCES res_partner(id),
    
    -- BIN Details
    bin_number VARCHAR(50) NOT NULL,
    bin_issue_date DATE,
    bin_expiry_date DATE,
    
    -- Registration Details
    registered_name VARCHAR(200),
    trade_name VARCHAR(200),
    
    -- Address
    registered_address TEXT,
    division VARCHAR(50),
    district VARCHAR(50),
    thana VARCHAR(50),
    
    -- Tax Information
    default_vat_rate DECIMAL(5, 2) DEFAULT 15.00,
    is_vat_registered BOOLEAN DEFAULT TRUE,
    
    -- Turnover
    annual_turnover_bdt DECIMAL(15, 2),
    turnover_category VARCHAR(20), -- BELOW_80L, 80L_TO_3C, 3C_TO_5C, ABOVE_5C
    
    -- Documents
    vat_certificate_attachment_id INTEGER,
    
    -- Status
    status VARCHAR(20) DEFAULT 'ACTIVE',
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    
    CONSTRAINT unique_bin_number UNIQUE (company_id, bin_number)
);

COMMENT ON TABLE smart_dairy_core.bd_vat_registration IS 'Bangladesh VAT BIN registration';

-- ============================================================================
-- TABLE: smart_dairy_core.bd_tds_configuration
-- PURPOSE: Tax Deducted at Source configuration
-- ============================================================================
CREATE TABLE smart_dairy_core.bd_tds_configuration (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    section_code VARCHAR(20) NOT NULL, -- 52, 52AA, 52AAA, etc.
    section_description TEXT NOT NULL,
    
    -- Applicability
    applicable_to VARCHAR(50), -- SUPPLIER, CONTRACTOR, PROFESSIONAL, etc.
    
    -- Thresholds
    threshold_type VARCHAR(20), -- PER_TRANSACTION, ANNUAL, NONE
    threshold_amount DECIMAL(12, 2),
    
    -- Rates
    tds_rate_without_etin DECIMAL(5, 2),
    tds_rate_with_etin DECIMAL(5, 2),
    
    -- Effective Period
    effective_date DATE NOT NULL,
    expiry_date DATE,
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

COMMENT ON TABLE smart_dairy_core.bd_tds_configuration IS 'TDS configuration per NBR sections';

-- ============================================================================
-- TABLE: smart_dairy_core.bd_mushak_submission
-- PURPOSE: VAT return (Mushak) submission tracking
-- ============================================================================
CREATE TABLE smart_dairy_core.bd_mushak_submission (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    -- Submission Details
    form_type VARCHAR(20) NOT NULL, -- 9.1, 9.2, 6.1, 6.2, 6.3, etc.
    submission_period VARCHAR(20), -- MONTHLY, QUARTERLY, YEARLY
    
    -- Period
    period_year INTEGER NOT NULL,
    period_month INTEGER CHECK (period_month BETWEEN 1 AND 12),
    
    -- Submission
    submission_date DATE,
    submission_reference VARCHAR(100),
    submission_method VARCHAR(30), -- ONLINE, MANUAL
    
    -- Financial Summary
    total_sales_bdt DECIMAL(15, 2),
    total_purchases_bdt DECIMAL(15, 2),
    output_vat_bdt DECIMAL(12, 2),
    input_vat_bdt DECIMAL(12, 2),
    net_vat_payable_bdt DECIMAL(12, 2),
    
    -- Status
    status VARCHAR(20) DEFAULT 'DRAFT',
    -- DRAFT, SUBMITTED, APPROVED, REJECTED, AMENDED
    
    -- Documents
    mushak_file_attachment_id INTEGER,
    acknowledgment_attachment_id INTEGER,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    created_by INTEGER,
    updated_by INTEGER
);

COMMENT ON TABLE smart_dairy_core.bd_mushak_submission IS 'VAT return submission tracking';

-- ============================================================================
-- TABLE: smart_dairy_core.bd_bsti_compliance
-- PURPOSE: BSTI license and compliance tracking
-- ============================================================================
CREATE TABLE smart_dairy_core.bd_bsti_compliance (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    product_id INTEGER NOT NULL REFERENCES product_product(id),
    
    -- License Details
    license_number VARCHAR(50) NOT NULL,
    license_type VARCHAR(50), -- MANUFACTURING, IMPORT, SALES
    
    -- Standards
    bsti_standard_number VARCHAR(50),
    bsti_standard_name VARCHAR(200),
    
    -- Dates
    issue_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    renewal_application_date DATE,
    
    -- Status
    status VARCHAR(20) DEFAULT 'ACTIVE',
    
    -- Documents
    license_attachment_id INTEGER,
    test_reports JSONB,
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW(),
    
    CONSTRAINT unique_bsti_license UNIQUE (company_id, license_number)
);

COMMENT ON TABLE smart_dairy_core.bd_bsti_compliance IS 'BSTI license management';

-- ============================================================================
-- TABLE: smart_dairy_core.bd_dls_registration
-- PURPOSE: Department of Livestock Services registration
-- ============================================================================
CREATE TABLE smart_dairy_core.bd_dls_registration (
    id SERIAL PRIMARY KEY,
    company_id INTEGER NOT NULL REFERENCES res_company(id),
    
    partner_id INTEGER REFERENCES res_partner(id),
    
    -- Registration Details
    dls_license_number VARCHAR(50) NOT NULL,
    license_category VARCHAR(50), -- DAIRY_FARM, FEED_MILL, PROCESSING, TRADING
    
    -- Premises
    premises_address TEXT,
    premises_size_hectares DECIMAL(8, 2),
    animal_capacity INTEGER,
    
    -- Dates
    issue_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    last_inspection_date DATE,
    next_inspection_date DATE,
    
    -- Inspection Results
    inspection_grade VARCHAR(20), -- A, B, C, D
    inspection_remarks TEXT,
    
    -- Status
    status VARCHAR(20) DEFAULT 'ACTIVE',
    
    -- Audit Fields
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

COMMENT ON TABLE smart_dairy_core.bd_dls_registration IS 'DLS license and registration';
```

### 4.8 Audit and Logging Tables

```sql
-- ============================================================================
-- TABLE: smart_dairy_audit.data_change_log
-- PURPOSE: Comprehensive data change auditing
-- ============================================================================
CREATE TABLE smart_dairy_audit.data_change_log (
    id BIGSERIAL PRIMARY KEY,
    
    -- Event Details
    event_timestamp TIMESTAMPTZ DEFAULT NOW(),
    event_type VARCHAR(20) NOT NULL, -- INSERT, UPDATE, DELETE
    
    -- Table Information
    schema_name VARCHAR(50) NOT NULL,
    table_name VARCHAR(100) NOT NULL,
    
    -- Record Identification
    record_id VARCHAR(100) NOT NULL,
    primary_key_columns JSONB,
    
    -- Change Details
    changed_fields JSONB,
    old_values JSONB,
    new_values JSONB,
    
    -- Context
    user_id INTEGER,
    username VARCHAR(100),
    session_id VARCHAR(100),
    ip_address INET,
    user_agent TEXT,
    application_name VARCHAR(100),
    
    -- Transaction
    transaction_id BIGINT,
    transaction_timestamp TIMESTAMPTZ,
    
    -- Additional Context
    request_id VARCHAR(100),
    company_id INTEGER,
    operation_context VARCHAR(200)
) PARTITION BY RANGE (event_timestamp);

COMMENT ON TABLE smart_dairy_audit.data_change_log IS 'Comprehensive audit trail for all data changes';

-- Create monthly partitions for audit log
CREATE TABLE smart_dairy_audit.data_change_log_2026_01 
    PARTITION OF smart_dairy_audit.data_change_log
    FOR VALUES FROM ('2026-01-01') TO ('2026-02-01');

CREATE INDEX idx_audit_timestamp ON smart_dairy_audit.data_change_log(event_timestamp);
CREATE INDEX idx_audit_table ON smart_dairy_audit.data_change_log(schema_name, table_name);
CREATE INDEX idx_audit_record ON smart_dairy_audit.data_change_log(record_id);
CREATE INDEX idx_audit_user ON smart_dairy_audit.data_change_log(user_id);
CREATE INDEX idx_audit_transaction ON smart_dairy_audit.data_change_log(transaction_id);

-- Retention policy: Move to archive after 1 year, delete after 7 years
SELECT add_retention_policy('smart_dairy_audit.data_change_log', INTERVAL '7 years');

-- ============================================================================
-- TABLE: smart_dairy_audit.user_activity_log
-- PURPOSE: User action and activity logging
-- ============================================================================
CREATE TABLE smart_dairy_audit.user_activity_log (
    id BIGSERIAL PRIMARY KEY,
    
    activity_timestamp TIMESTAMPTZ DEFAULT NOW(),
    
    -- User
    user_id INTEGER,
    username VARCHAR(100),
    partner_id INTEGER,
    
    -- Activity
    activity_type VARCHAR(50) NOT NULL,
    -- LOGIN, LOGOUT, VIEW, CREATE, UPDATE, DELETE, EXPORT, PRINT, 
    -- APPROVE, REJECT, CANCEL, REPORT_VIEW, API_CALL
    
    activity_category VARCHAR(50),
    activity_description TEXT,
    
    -- Resource
    resource_type VARCHAR(50),
    resource_id VARCHAR(100),
    resource_name VARCHAR(200),
    
    -- Context
    module_name VARCHAR(50),
    action_name VARCHAR(100),
    record_count INTEGER,
    
    -- Performance
    execution_time_ms INTEGER,
    rows_affected INTEGER,
    
    -- Session
    session_id VARCHAR(100),
    ip_address INET,
    user_agent TEXT,
    geolocation JSONB,
    
    -- Security
    risk_score INTEGER DEFAULT 0,
    requires_review BOOLEAN DEFAULT FALSE,
    review_status VARCHAR(20),
    
    -- Additional
    request_payload JSONB,
    response_status VARCHAR(20),
    error_message TEXT,
    company_id INTEGER
) PARTITION BY RANGE (activity_timestamp);

COMMENT ON TABLE smart_dairy_audit.user_activity_log IS 'User activity and behavior logging';

CREATE INDEX idx_activity_timestamp ON smart_dairy_audit.user_activity_log(activity_timestamp);
CREATE INDEX idx_activity_user ON smart_dairy_audit.user_activity_log(user_id);
CREATE INDEX idx_activity_type ON smart_dairy_audit.user_activity_log(activity_type);

-- ============================================================================
-- TABLE: smart_dairy_audit.api_access_log
-- PURPOSE: API access and usage logging
-- ============================================================================
CREATE TABLE smart_dairy_audit.api_access_log (
    id BIGSERIAL PRIMARY KEY,
    
    request_timestamp TIMESTAMPTZ DEFAULT NOW(),
    
    -- Request
    request_id VARCHAR(100),
    http_method VARCHAR(10) NOT NULL,
    endpoint_path TEXT NOT NULL,
    query_parameters JSONB,
    request_headers JSONB,
    request_body_size_bytes INTEGER,
    
    -- Client
    api_key_id VARCHAR(100),
    client_id VARCHAR(100),
    partner_id INTEGER,
    
    -- Response
    response_status_code INTEGER,
    response_time_ms INTEGER,
    response_body_size_bytes INTEGER,
    
    -- Rate Limiting
    rate_limit_quota INTEGER,
    rate_limit_remaining INTEGER,
    rate_limit_reset_timestamp TIMESTAMPTZ,
    
    -- Network
    source_ip INET,
    user_agent TEXT,
    tls_version VARCHAR(10),
    
    -- Errors
    error_code VARCHAR(50),
    error_message TEXT,
    
    -- Cost (for monetized APIs)
    api_call_cost DECIMAL(10, 4)
) PARTITION BY RANGE (request_timestamp);

COMMENT ON TABLE smart_dairy_audit.api_access_log IS 'API access and usage logging';

CREATE INDEX idx_api_timestamp ON smart_dairy_audit.api_access_log(request_timestamp);
CREATE INDEX idx_api_endpoint ON smart_dairy_audit.api_access_log(endpoint_path);
CREATE INDEX idx_api_client ON smart_dairy_audit.api_access_log(client_id);
CREATE INDEX idx_api_status ON smart_dairy_audit.api_access_log(response_status_code);
```

