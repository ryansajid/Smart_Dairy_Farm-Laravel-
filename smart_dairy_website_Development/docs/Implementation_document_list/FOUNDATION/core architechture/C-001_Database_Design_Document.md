# SMART DAIRY LTD.
## DATABASE DESIGN DOCUMENT
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | C-001 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Database Architect |
| **Owner** | DBA |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Database Architecture](#2-database-architecture)
3. [Entity Relationship Diagrams](#3-entity-relationship-diagrams)
4. [Schema Design](#4-schema-design)
5. [Partitioning Strategy](#5-partitioning-strategy)
6. [Indexing Strategy](#6-indexing-strategy)
7. [Data Retention](#7-data-retention)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Database Design Document defines the complete database schema for the Smart Dairy ERP system, including table structures, relationships, constraints, and optimization strategies.

### 1.2 Database Technology

| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| **Primary Database** | PostgreSQL | 16.1 | Transactional data |
| **Time-Series** | TimescaleDB | 2.12 | IoT sensor data |
| **Cache** | Redis | 7.2 | Sessions, cache |
| **Search** | Elasticsearch | 8.11 | Full-text search |

---

## 2. DATABASE ARCHITECTURE

### 2.1 Schema Organization

```sql
-- Core schemas
CREATE SCHEMA IF NOT EXISTS core;        -- Base entities
CREATE SCHEMA IF NOT EXISTS farm;        -- Farm management
CREATE SCHEMA IF NOT EXISTS sales;       -- Sales & CRM
CREATE SCHEMA IF NOT EXISTS inventory;   -- Inventory & warehouse
CREATE SCHEMA IF NOT EXISTS accounting;  -- Finance & accounting
CREATE SCHEMA IF NOT EXISTS analytics;   -- Reporting & BI
```

### 2.2 Multi-Database Strategy

```
┌─────────────────────────────────────────────────────────────┐
│                    DATABASE ARCHITECTURE                     │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌─────────────────┐                                        │
│  │  PostgreSQL 16  │  Primary transactional database       │
│  │  ├─ Main DB     │  ├── All business data                │
│  │  ├─ Replica     │  ├── Read queries                     │
│  │  └─ TimescaleDB │  └── Time-series extension            │
│  └─────────────────┘                                        │
│                                                              │
│  ┌─────────────────┐  ┌─────────────────┐                   │
│  │  Redis Cluster  │  │ Elasticsearch   │                   │
│  │  ├─ Sessions    │  │  ├─ Products    │                   │
│  │  ├─ Cache       │  │  ├─ Customers   │                   │
│  │  └─ Queue       │  │  └─ Logs        │                   │
│  └─────────────────┘  └─────────────────┘                   │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## 3. ENTITY RELATIONSHIP DIAGRAMS

### 3.1 Core Farm Management ERD

```
┌──────────────────┐
│   farm_animal    │
├──────────────────┤
│ PK id            │
│    name          │
│    rfid_tag      │
│    species       │
│ FK breed_id      │
│    gender        │
│    birth_date    │
│    status        │
│ FK barn_id       │
│ FK mother_id     │
│ FK father_id     │
└────────┬─────────┘
         │
    ┌────┴────┬──────────┬──────────────┐
    │         │          │              │
    ▼         ▼          ▼              ▼
┌────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐
│breeding│ │ milk_    │ │ health_  │ │ animal_  │
│_record │ │ production│ │ _record  │ │ _location│
└────────┘ └──────────┘ └──────────┘ └──────────┘
```

### 3.2 Sales & Order ERD

```
┌──────────────────┐
│  res_partner     │
├──────────────────┤
│ PK id            │
│    name          │
│    email         │
│    phone         │
│    customer_type │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│   sale_order     │
├──────────────────┤
│ PK id            │
│ FK partner_id    │
│    order_date    │
│    state         │
│    amount_total  │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ sale_order_line  │
├──────────────────┤
│ PK id            │
│ FK order_id      │
│ FK product_id    │
│    quantity      │
│    price_unit    │
└──────────────────┘
```

---

## 4. SCHEMA DESIGN

### 4.1 Core Tables

#### farm_animal

```sql
CREATE TABLE farm.animal (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rfid_tag VARCHAR(100),
    ear_tag VARCHAR(100),
    species VARCHAR(50) NOT NULL DEFAULT 'cattle',
    breed_id BIGINT REFERENCES farm.breed(id),
    gender VARCHAR(10) NOT NULL,
    birth_date DATE,
    age_months INTEGER GENERATED ALWAYS AS (
        CASE 
            WHEN birth_date IS NOT NULL 
            THEN EXTRACT(YEAR FROM AGE(CURRENT_DATE, birth_date)) * 12 +
                 EXTRACT(MONTH FROM AGE(CURRENT_DATE, birth_date))
            ELSE NULL 
        END
    ) STORED,
    status VARCHAR(50) NOT NULL DEFAULT 'calf',
    barn_id BIGINT REFERENCES farm.barn(id),
    pen_number VARCHAR(50),
    current_lactation INTEGER DEFAULT 0,
    mother_id BIGINT REFERENCES farm.animal(id),
    father_id BIGINT REFERENCES farm.animal(id),
    health_status VARCHAR(50) DEFAULT 'healthy',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT unique_rfid UNIQUE (rfid_tag),
    CONSTRAINT valid_gender CHECK (gender IN ('male', 'female')),
    CONSTRAINT valid_status CHECK (status IN (
        'calf', 'heifer', 'lactating', 'dry', 
        'pregnant', 'sold', 'deceased'
    ))
);

CREATE INDEX idx_animal_rfid ON farm.animal(rfid_tag);
CREATE INDEX idx_animal_status ON farm.animal(status);
CREATE INDEX idx_animal_breed ON farm.animal(breed_id);
CREATE INDEX idx_animal_location ON farm.animal(barn_id);
```

#### farm_milk_production (Partitioned)

```sql
CREATE TABLE farm.milk_production (
    id BIGSERIAL,
    animal_id BIGINT NOT NULL REFERENCES farm.animal(id),
    production_date DATE NOT NULL,
    session VARCHAR(20) NOT NULL, -- 'morning', 'evening'
    quantity_liters DECIMAL(8,2) NOT NULL,
    fat_percentage DECIMAL(5,2),
    snf_percentage DECIMAL(5,2),
    protein_percentage DECIMAL(5,2),
    lactose_percentage DECIMAL(5,2),
    temperature DECIMAL(5,2),
    device_id VARCHAR(100),
    recorded_by BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id, production_date)
) PARTITION BY RANGE (production_date);

-- Create partitions for 2024-2026
CREATE TABLE farm.milk_production_2024_q1 
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2024-01-01') TO ('2024-04-01');
    
CREATE TABLE farm.milk_production_2024_q2 
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2024-04-01') TO ('2024-07-01');

-- Continue for all quarters...

CREATE INDEX idx_milk_animal_date ON farm.milk_production(animal_id, production_date);
CREATE INDEX idx_milk_date ON farm.milk_production(production_date);
```

#### sale_order

```sql
CREATE TABLE sales.order (
    id BIGSERIAL PRIMARY KEY,
    partner_id BIGINT NOT NULL REFERENCES core.res_partner(id),
    order_date DATE NOT NULL DEFAULT CURRENT_DATE,
    confirmation_date TIMESTAMP,
    state VARCHAR(50) NOT NULL DEFAULT 'draft',
    order_type VARCHAR(50) DEFAULT 'b2c', -- 'b2c', 'b2b', 'subscription'
    amount_untaxed DECIMAL(15,2) DEFAULT 0,
    amount_tax DECIMAL(15,2) DEFAULT 0,
    amount_total DECIMAL(15,2) DEFAULT 0,
    currency_id BIGINT REFERENCES core.currency(id),
    payment_term_id BIGINT,
    delivery_address_id BIGINT,
    user_id BIGINT,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT valid_state CHECK (state IN (
        'draft', 'sent', 'sale', 'done', 'cancel'
    ))
);

CREATE INDEX idx_order_partner ON sales.order(partner_id);
CREATE INDEX idx_order_date ON sales.order(order_date);
CREATE INDEX idx_order_state ON sales.order(state);
CREATE INDEX idx_order_type ON sales.order(order_type);
```

### 4.2 TimescaleDB for IoT Data

```sql
-- Enable TimescaleDB extension
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- IoT sensor data hypertable
CREATE TABLE iot.sensor_data (
    time TIMESTAMPTZ NOT NULL,
    device_id VARCHAR(100) NOT NULL,
    sensor_type VARCHAR(50) NOT NULL,
    location VARCHAR(100),
    value DECIMAL(10,3),
    unit VARCHAR(20),
    metadata JSONB
);

-- Convert to hypertable
SELECT create_hypertable('iot.sensor_data', 'time', 
    chunk_time_interval => INTERVAL '1 day');

-- Create indexes
CREATE INDEX idx_sensor_device_time ON iot.sensor_data(device_id, time DESC);
CREATE INDEX idx_sensor_type_time ON iot.sensor_data(sensor_type, time DESC);

-- Set retention policy (keep 2 years)
SELECT add_retention_policy('iot.sensor_data', INTERVAL '2 years');

-- Set continuous aggregation for daily averages
CREATE MATERIALIZED VIEW iot.sensor_daily_avg
WITH (timescaledb.continuous) AS
SELECT 
    time_bucket('1 day', time) AS bucket,
    device_id,
    sensor_type,
    AVG(value) as avg_value,
    MIN(value) as min_value,
    MAX(value) as max_value
FROM iot.sensor_data
GROUP BY bucket, device_id, sensor_type;
```

---

## 5. PARTITIONING STRATEGY

### 5.1 Partitioned Tables

| Table | Partition Type | Partition Key | Interval |
|-------|---------------|---------------|----------|
| milk_production | Range | production_date | Quarterly |
| sensor_data | Hypertable | time | Daily |
| audit_log | Range | created_at | Monthly |
| order_history | Range | order_date | Monthly |

### 5.2 Partition Management

```sql
-- Automated partition creation
CREATE OR REPLACE FUNCTION create_milk_partitions()
RETURNS void AS $$
DECLARE
    start_date DATE;
    end_date DATE;
    partition_name TEXT;
BEGIN
    FOR i IN 0..11 LOOP
        start_date := DATE_TRUNC('quarter', CURRENT_DATE) + (i * INTERVAL '3 months');
        end_date := start_date + INTERVAL '3 months';
        partition_name := 'farm.milk_production_' || TO_CHAR(start_date, 'YYYY_q');
        
        EXECUTE format(
            'CREATE TABLE IF NOT EXISTS %I PARTITION OF farm.milk_production
             FOR VALUES FROM (%L) TO (%L)',
            partition_name, start_date, end_date
        );
    END LOOP;
END;
$$ LANGUAGE plpgsql;
```

---

## 6. INDEXING STRATEGY

### 6.1 Index Categories

| Type | Tables | Columns | Purpose |
|------|--------|---------|---------|
| **Primary Key** | All | id | Uniqueness, clustering |
| **Foreign Key** | All | *_id | Join performance |
| **B-Tree** | Frequently queried | name, date, status | Range queries |
| **GIN** | JSONB columns | metadata | JSON queries |
| **Partial** | Large tables | status='active' | Filtered queries |

### 6.2 Critical Indexes

```sql
-- Farm Management
CREATE INDEX CONCURRENTLY idx_animal_active 
    ON farm.animal(id) WHERE status != 'deceased';

CREATE INDEX CONCURRENTLY idx_breeding_expected_calving 
    ON farm.breeding_record(expected_calving_date) 
    WHERE pregnancy_status = 'pregnant';

-- Sales
CREATE INDEX CONCURRENTLY idx_order_unprocessed 
    ON sales.order(id) 
    WHERE state IN ('draft', 'sent');

-- Inventory
CREATE INDEX CONCURRENTLY idx_stock_low 
    ON inventory.stock_quant(quantity) 
    WHERE quantity < min_quantity;
```

---

## 7. DATA RETENTION

### 7.1 Retention Policies

| Data Type | Retention | Archive Action |
|-----------|-----------|----------------|
| Transactional | 7 years | Move to archive |
| Audit Logs | 7 years | Compress, store |
| IoT Sensor | 2 years | Aggregate, delete |
| Session Data | 30 days | Delete |
| Cache | 24 hours | LRU eviction |
| Email Logs | 1 year | Compress |

### 7.2 Archive Strategy

```sql
-- Archive old orders
CREATE TABLE sales.order_archive (LIKE sales.order INCLUDING ALL);

-- Move data older than 2 years
INSERT INTO sales.order_archive
SELECT * FROM sales.order 
WHERE order_date < CURRENT_DATE - INTERVAL '2 years';

DELETE FROM sales.order 
WHERE order_date < CURRENT_DATE - INTERVAL '2 years';

-- Partition old archive table
CREATE TABLE sales.order_archive_2022 
    PARTITION OF sales.order_archive
    FOR VALUES FROM ('2022-01-01') TO ('2023-01-01');
```

---

**END OF DATABASE DESIGN DOCUMENT**
