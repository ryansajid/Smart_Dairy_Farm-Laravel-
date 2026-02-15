# SMART DAIRY LTD.
## DATABASE SCHEMA SPECIFICATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | C-002 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Database Architect |
| **Owner** | Database Administrator |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Schema Overview](#2-schema-overview)
3. [Core Schema (core)](#3-core-schema-core)
4. [Farm Management Schema (farm)](#4-farm-management-schema-farm)
5. [Sales Schema (sales)](#5-sales-schema-sales)
6. [Inventory Schema (inventory)](#6-inventory-schema-inventory)
7. [Accounting Schema (accounting)](#7-accounting-schema-accounting)
8. [IoT Schema (iot)](#8-iot-schema-iot)
9. [Index Specifications](#9-index-specifications)
10. [Constraint Definitions](#10-constraint-definitions)
11. [Partitioning Details](#11-partitioning-details)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document provides the complete technical specification for the Smart Dairy database schema, including all table definitions, column specifications, indexes, constraints, and partitioning strategies.

### 1.2 Scope

| Schema | Tables | Description |
|--------|--------|-------------|
| core | 15+ | Base entities, users, partners, configuration |
| farm | 20+ | Animal management, production, health, breeding |
| sales | 12+ | Orders, quotations, subscriptions |
| inventory | 10+ | Stock, warehouses, movements |
| accounting | 18+ | Journal entries, accounts, payments |
| iot | 5+ | Sensor data, device management |

### 1.3 Naming Conventions

```
┌─────────────────────────────────────────────────────────────────┐
│                    NAMING CONVENTIONS                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  TABLES                                                          │
│  ├── Format: {schema}.{entity_name}                             │
│  ├── Plural for collections: farm.animals                       │
│  └── Singular for entities: farm.animal                         │
│                                                                  │
│  COLUMNS                                                         │
│  ├── Primary Key: id (BIGSERIAL)                                │
│  ├── Foreign Key: {table}_id (BIGINT)                           │
│  ├── Timestamps: created_at, updated_at (TIMESTAMP)             │
│  ├── Flags: is_{attribute}, has_{attribute} (BOOLEAN)           │
│  └── Status: status (VARCHAR with CHECK constraint)             │
│                                                                  │
│  INDEXES                                                         │
│  ├── Format: idx_{table}_{column(s)}                            │
│  └── Unique: uq_{table}_{column(s)}                             │
│                                                                  │
│  CONSTRAINTS                                                     │
│  ├── Primary: {table}_pkey                                      │
│  ├── Foreign: {table}_{column}_fkey                             │
│  └── Check: {table}_{column}_check                              │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. SCHEMA OVERVIEW

### 2.1 Database Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATABASE ARCHITECTURE                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │                    SMART_DAIRY_PROD                          ││
│  │                                                              ││
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐            ││
│  │  │  core   │ │  farm   │ │  sales  │ │iot (ts) │            ││
│  │  │         │ │         │ │         │ │         │            ││
│  │  │• partner│ │• animal │ │• order  │ │• sensor │            ││
│  │  │• users  │ │• milk   │ │• line   │ │• device │            ││
│  │  │• config │ │• breed  │ │• sub    │ │• alert  │            ││
│  │  └─────────┘ └─────────┘ └─────────┘ └─────────┘            ││
│  │                                                              ││
│  │  ┌─────────┐ ┌─────────┐                                     ││
│  │  │inventory│ │account. │                                     ││
│  │  │         │ │         │                                     ││
│  │  │• stock  │ │• journal│                                     ││
│  │  │• move   │ │• account│                                     ││
│  │  │• wareh. │ │• payment│                                     ││
│  │  └─────────┘ └─────────┘                                     ││
│  │                                                              ││
│  └─────────────────────────────────────────────────────────────┘│
│                              │                                   │
│                    ┌─────────┴─────────┐                        │
│                    ▼                   ▼                        │
│            ┌──────────────┐  ┌──────────────┐                  │
│            │  TimescaleDB │  │    Redis     │                  │
│            │  (Analytics) │  │   (Cache)    │                  │
│            └──────────────┘  └──────────────┘                  │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Character Set & Collation

```sql
-- Database-level settings
CREATE DATABASE smart_dairy_prod
    WITH ENCODING = 'UTF8'
    LC_COLLATE = 'en_US.UTF-8'
    LC_CTYPE = 'en_US.UTF-8'
    TEMPLATE = template0;

-- Enable required extensions
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";  -- For trigram search
CREATE EXTENSION IF NOT EXISTS "btree_gin"; -- For GIN indexes
```

---

## 3. CORE SCHEMA (core)

### 3.1 res_partner (Customers/Partners)

```sql
CREATE TABLE core.res_partner (
    -- Primary Key
    id BIGSERIAL PRIMARY KEY,
    
    -- Basic Information
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    mobile VARCHAR(50),
    
    -- Classification
    customer_type VARCHAR(50) NOT NULL DEFAULT 'b2c',
    is_company BOOLEAN NOT NULL DEFAULT FALSE,
    is_customer BOOLEAN NOT NULL DEFAULT FALSE,
    is_supplier BOOLEAN NOT NULL DEFAULT FALSE,
    is_farmer BOOLEAN NOT NULL DEFAULT FALSE,
    
    -- Company Details
    company_name VARCHAR(255),
    vat_number VARCHAR(50),
    trade_license VARCHAR(100),
    
    -- Financial
    credit_limit DECIMAL(15,2) NOT NULL DEFAULT 0,
    credit_used DECIMAL(15,2) NOT NULL DEFAULT 0,
    payment_term_id BIGINT,
    currency_id BIGINT,
    
    -- Address
    street VARCHAR(255),
    street2 VARCHAR(255),
    city VARCHAR(100),
    state_id BIGINT,
    zip VARCHAR(20),
    country_id BIGINT NOT NULL DEFAULT 1, -- Bangladesh
    partner_latitude DECIMAL(10,7),
    partner_longitude DECIMAL(10,7),
    
    -- B2B Specific
    business_type VARCHAR(100),
    tier_level VARCHAR(20) DEFAULT 'bronze',
    contract_start_date DATE,
    contract_end_date DATE,
    
    -- Status
    active BOOLEAN NOT NULL DEFAULT TRUE,
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_by BIGINT,
    updated_by BIGINT,
    
    -- Constraints
    CONSTRAINT uq_res_partner_email UNIQUE (email),
    CONSTRAINT uq_res_partner_mobile UNIQUE (mobile),
    CONSTRAINT ck_res_partner_customer_type CHECK (
        customer_type IN ('b2c', 'b2b', 'farmer', 'supplier', 'employee')
    ),
    CONSTRAINT ck_res_partner_tier_level CHECK (
        tier_level IN ('bronze', 'silver', 'gold', 'platinum')
    ),
    CONSTRAINT ck_res_partner_credit CHECK (credit_used <= credit_limit)
);

-- Indexes
CREATE INDEX idx_res_partner_name ON core.res_partner USING gin(name gin_trgm_ops);
CREATE INDEX idx_res_partner_type ON core.res_partner(customer_type);
CREATE INDEX idx_res_partner_company ON core.res_partner(is_company);
CREATE INDEX idx_res_partner_active ON core.res_partner(active);
CREATE INDEX idx_res_partner_tier ON core.res_partner(tier_level) WHERE customer_type = 'b2b';
```

### 3.2 res_users (System Users)

```sql
CREATE TABLE core.res_users (
    id BIGSERIAL PRIMARY KEY,
    
    -- Authentication
    login VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    api_key_hash VARCHAR(255),
    
    -- Profile
    partner_id BIGINT NOT NULL REFERENCES core.res_partner(id),
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    
    -- Status
    active BOOLEAN NOT NULL DEFAULT TRUE,
    login_attempts INTEGER NOT NULL DEFAULT 0,
    locked_until TIMESTAMP,
    
    -- Preferences
    lang VARCHAR(10) NOT NULL DEFAULT 'en_US',
    tz VARCHAR(50) NOT NULL DEFAULT 'Asia/Dhaka',
    notification_type VARCHAR(20) DEFAULT 'email',
    
    -- Security
    two_factor_enabled BOOLEAN NOT NULL DEFAULT FALSE,
    two_factor_secret VARCHAR(255),
    password_changed_at TIMESTAMP,
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP,
    
    -- Constraints
    CONSTRAINT uq_res_users_login UNIQUE (login),
    CONSTRAINT uq_res_users_email UNIQUE (email)
);

CREATE INDEX idx_res_users_partner ON core.res_users(partner_id);
CREATE INDEX idx_res_users_active ON core.res_users(active);
```

### 3.3 ir_config_parameter (System Configuration)

```sql
CREATE TABLE core.ir_config_parameter (
    id BIGSERIAL PRIMARY KEY,
    key VARCHAR(255) NOT NULL UNIQUE,
    value TEXT,
    description TEXT,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Essential configuration parameters
INSERT INTO core.ir_config_parameter (key, value, description) VALUES
('database.create_date', CURRENT_TIMESTAMP::TEXT, 'Database creation timestamp'),
('database.enterprise_code', '', 'Enterprise license code'),
('web.base.url', 'https://smartdairy.bd', 'Base URL for the application'),
('web.base.url.freeze', 'true', 'Prevent automatic URL detection'),
('mail.default.from', 'noreply@smartdairy.bd', 'Default sender email'),
('mail.catchall.domain', 'smartdairy.bd', 'Catchall domain for emails'),
('auth_signup.reset_password.validity', '24', 'Password reset validity in hours'),
('session.max_inactivity_seconds', '3600', 'Session timeout in seconds'),
('session.max_sessions_per_user', '5', 'Maximum concurrent sessions per user'),
('report.url', 'http://localhost:8069', 'URL for report generation'),
('geolocation.enabled', 'true', 'Enable geolocation features'),
('google_maps_api_key', '', 'Google Maps API key'),
('sms.gateway.enabled', 'true', 'Enable SMS gateway'),
('payment.acquirer.default', 'bkash', 'Default payment acquirer'),
('product.template.list_price.decimals', '2', 'Price decimal precision'),
('stock.warehouse.double.validation', 'true', 'Enable double validation for stock moves');
```

---

## 4. FARM MANAGEMENT SCHEMA (farm)

### 4.1 farm_breed (Animal Breeds)

```sql
CREATE TABLE farm.breed (
    id BIGSERIAL PRIMARY KEY,
    
    -- Identification
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE,
    
    -- Classification
    species VARCHAR(50) NOT NULL,
    origin_country VARCHAR(100),
    
    -- Characteristics
    avg_milk_yield_daily DECIMAL(5,2),
    avg_fat_percentage DECIMAL(4,2),
    avg_snf_percentage DECIMAL(4,2),
    maturity_age_months INTEGER,
    first_calving_age_months INTEGER,
    
    -- Description
    description TEXT,
    
    -- Status
    active BOOLEAN NOT NULL DEFAULT TRUE,
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT ck_breed_species CHECK (species IN ('cattle', 'buffalo', 'goat'))
);

CREATE INDEX idx_breed_species ON farm.breed(species);
CREATE INDEX idx_breed_active ON farm.breed(active);

-- Seed data for Bangladesh dairy breeds
INSERT INTO farm.breed (name, code, species, origin_country, avg_milk_yield_daily, avg_fat_percentage) VALUES
('Holstein Friesian', 'HF', 'cattle', 'Netherlands', 25.00, 3.8),
('Jersey', 'JY', 'cattle', 'Jersey', 18.00, 5.0),
('Sahiwal', 'SW', 'cattle', 'Pakistan', 12.00, 4.5),
('Red Chittagong', 'RC', 'cattle', 'Bangladesh', 4.50, 4.8),
('Pabna Cattle', 'PC', 'cattle', 'Bangladesh', 5.00, 4.5),
('Local Buffalo', 'LB', 'buffalo', 'Bangladesh', 6.00, 7.5),
('Murrah Buffalo', 'MB', 'buffalo', 'India', 10.00, 7.0),
('Black Bengal Goat', 'BG', 'goat', 'Bangladesh', 1.50, 4.5);
```

### 4.2 farm_animal (Animal Registry)

```sql
CREATE TABLE farm.animal (
    id BIGSERIAL PRIMARY KEY,
    
    -- Identification
    name VARCHAR(100) NOT NULL,
    rfid_tag VARCHAR(100),
    ear_tag VARCHAR(100),
    barcode VARCHAR(100),
    
    -- Classification
    species VARCHAR(50) NOT NULL,
    breed_id BIGINT NOT NULL REFERENCES farm.breed(id),
    gender VARCHAR(10) NOT NULL,
    
    -- Birth/Origin
    birth_date DATE,
    age_months INTEGER GENERATED ALWAYS AS (
        CASE 
            WHEN birth_date IS NOT NULL 
            THEN (EXTRACT(YEAR FROM AGE(CURRENT_DATE, birth_date)) * 12 +
                  EXTRACT(MONTH FROM AGE(CURRENT_DATE, birth_date)))::INTEGER
            ELSE NULL 
        END
    ) STORED,
    birth_weight_kg DECIMAL(6,2),
    birth_type VARCHAR(20) DEFAULT 'single', -- single, twin, triplet
    
    -- Parentage
    mother_id BIGINT REFERENCES farm.animal(id),
    father_id BIGINT REFERENCES farm.animal(id),
    
    -- Current Status
    status VARCHAR(50) NOT NULL DEFAULT 'calf',
    health_status VARCHAR(50) NOT NULL DEFAULT 'healthy',
    reproductive_status VARCHAR(50),
    
    -- Location
    barn_id BIGINT REFERENCES farm.barn(id),
    pen_number VARCHAR(50),
    
    -- Production
    current_lactation INTEGER NOT NULL DEFAULT 0,
    daily_milk_avg DECIMAL(6,2),
    lifetime_milk_total DECIMAL(10,2) DEFAULT 0,
    
    -- Purchase Information
    is_purchased BOOLEAN DEFAULT FALSE,
    purchase_date DATE,
    purchase_price DECIMAL(12,2),
    purchase_weight_kg DECIMAL(6,2),
    supplier_id BIGINT REFERENCES core.res_partner(id),
    
    -- Sale Information
    is_sold BOOLEAN DEFAULT FALSE,
    sale_date DATE,
    sale_price DECIMAL(12,2),
    sale_weight_kg DECIMAL(6,2),
    sale_reason VARCHAR(100),
    
    -- Images & Documents
    image VARCHAR(255),
    pedigree_document VARCHAR(255),
    
    -- Notes
    notes TEXT,
    
    -- Status
    active BOOLEAN NOT NULL DEFAULT TRUE,
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT uq_animal_rfid UNIQUE (rfid_tag),
    CONSTRAINT uq_animal_ear_tag UNIQUE (ear_tag),
    CONSTRAINT uq_animal_barcode UNIQUE (barcode),
    CONSTRAINT ck_animal_species CHECK (species IN ('cattle', 'buffalo', 'goat')),
    CONSTRAINT ck_animal_gender CHECK (gender IN ('male', 'female')),
    CONSTRAINT ck_animal_status CHECK (status IN (
        'calf', 'heifer', 'lactating', 'dry', 
        'pregnant', 'sold', 'deceased'
    )),
    CONSTRAINT ck_animal_health_status CHECK (health_status IN (
        'healthy', 'sick', 'treatment', 'quarantine', 'recovery'
    )),
    CONSTRAINT ck_animal_reproductive_status CHECK (reproductive_status IN (
        'open', 'pregnant', 'fresh', 'bred', 'dry'
    )),
    CONSTRAINT ck_animal_birth_type CHECK (birth_type IN ('single', 'twin', 'triplet')),
    CONSTRAINT ck_animal_no_self_parent CHECK (mother_id != id AND father_id != id)
);

-- Comprehensive indexes
CREATE INDEX idx_animal_name ON farm.animal USING gin(name gin_trgm_ops);
CREATE INDEX idx_animal_rfid ON farm.animal(rfid_tag);
CREATE INDEX idx_animal_ear_tag ON farm.animal(ear_tag);
CREATE INDEX idx_animal_species ON farm.animal(species);
CREATE INDEX idx_animal_breed ON farm.animal(breed_id);
CREATE INDEX idx_animal_status ON farm.animal(status);
CREATE INDEX idx_animal_health ON farm.animal(health_status);
CREATE INDEX idx_animal_location ON farm.animal(barn_id);
CREATE INDEX idx_animal_mother ON farm.animal(mother_id);
CREATE INDEX idx_animal_father ON farm.animal(father_id);
CREATE INDEX idx_animal_active ON farm.animal(active) WHERE active = TRUE;
CREATE INDEX idx_animal_current_lact ON farm.animal(current_lactation) WHERE status = 'lactating';
```

### 4.3 farm_milk_production (Partitioned Table)

```sql
-- Main partitioned table
CREATE TABLE farm.milk_production (
    id BIGSERIAL,
    animal_id BIGINT NOT NULL REFERENCES farm.animal(id),
    production_date DATE NOT NULL,
    session VARCHAR(20) NOT NULL, -- 'morning', 'evening', 'special'
    
    -- Quantity
    quantity_liters DECIMAL(8,3) NOT NULL,
    
    -- Quality Metrics
    fat_percentage DECIMAL(5,2),
    snf_percentage DECIMAL(5,2),
    protein_percentage DECIMAL(5,2),
    lactose_percentage DECIMAL(5,2),
    density DECIMAL(6,3),
    temperature DECIMAL(5,2),
    ph DECIMAL(4,2),
    
    -- Calculated
    total_solids DECIMAL(5,2) GENERATED ALWAYS AS (
        COALESCE(fat_percentage, 0) + COALESCE(snf_percentage, 0)
    ) STORED,
    snf_fat_ratio DECIMAL(5,2) GENERATED ALWAYS AS (
        CASE 
            WHEN fat_percentage > 0 THEN snf_percentage / fat_percentage 
            ELSE NULL 
        END
    ) STORED,
    
    -- Metadata
    device_id VARCHAR(100),
    recorded_by BIGINT REFERENCES core.res_users(id),
    notes TEXT,
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    PRIMARY KEY (id, production_date),
    CONSTRAINT ck_milk_session CHECK (session IN ('morning', 'evening', 'special')),
    CONSTRAINT ck_milk_quantity CHECK (quantity_liters > 0 AND quantity_liters <= 100),
    CONSTRAINT ck_milk_fat CHECK (fat_percentage IS NULL OR (fat_percentage >= 0 AND fat_percentage <= 15)),
    CONSTRAINT ck_milk_snf CHECK (snf_percentage IS NULL OR (snf_percentage >= 0 AND snf_percentage <= 15)),
    CONSTRAINT ck_milk_protein CHECK (protein_percentage IS NULL OR (protein_percentage >= 0 AND protein_percentage <= 10)),
    CONSTRAINT ck_milk_lactose CHECK (lactose_percentage IS NULL OR (lactose_percentage >= 0 AND lactose_percentage <= 10)),
    CONSTRAINT ck_milk_temp CHECK (temperature IS NULL OR (temperature >= 0 AND temperature <= 45)),
    CONSTRAINT ck_milk_ph CHECK (ph IS NULL OR (ph >= 5.5 AND ph <= 7.5))
) PARTITION BY RANGE (production_date);

-- Create quarterly partitions for 2024-2027
CREATE TABLE farm.milk_production_2024_q1 
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2024-01-01') TO ('2024-04-01');
    
CREATE TABLE farm.milk_production_2024_q2 
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2024-04-01') TO ('2024-07-01');
    
CREATE TABLE farm.milk_production_2024_q3 
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2024-07-01') TO ('2024-10-01');
    
CREATE TABLE farm.milk_production_2024_q4 
    PARTITION OF farm.milk_production
    FOR VALUES FROM ('2024-10-01') TO ('2025-01-01');

-- Indexes on partitioned table
CREATE INDEX idx_milk_animal_date ON farm.milk_production(animal_id, production_date);
CREATE INDEX idx_milk_date_session ON farm.milk_production(production_date, session);
CREATE INDEX idx_milk_device ON farm.milk_production(device_id);
CREATE INDEX idx_milk_recorded ON farm.milk_production(recorded_by);
```

### 4.4 farm_breeding_record

```sql
CREATE TABLE farm.breeding_record (
    id BIGSERIAL PRIMARY KEY,
    
    -- Animal Reference
    animal_id BIGINT NOT NULL REFERENCES farm.animal(id),
    
    -- Breeding Event
    date DATE NOT NULL,
    breeding_type VARCHAR(50) NOT NULL, -- 'ai', 'natural', 'et'
    
    -- Heat Detection
    heat_detected BOOLEAN DEFAULT FALSE,
    heat_date DATE,
    heat_signs VARCHAR(255),
    
    -- Insemination Details (for AI)
    semen_code VARCHAR(100),
    semen_batch VARCHAR(50),
    bull_name VARCHAR(255),
    bull_breed_id BIGINT REFERENCES farm.breed(id),
    semen_company_id BIGINT REFERENCES core.res_partner(id),
    ai_technician_id BIGINT REFERENCES core.res_partner(id),
    
    -- Natural Breeding
    bull_animal_id BIGINT REFERENCES farm.animal(id),
    
    -- Pregnancy Check
    pregnancy_check_date DATE,
    pregnancy_check_method VARCHAR(50), -- 'ultrasound', 'rectal', 'blood'
    pregnancy_status VARCHAR(50) DEFAULT 'pending', -- 'pending', 'pregnant', 'open'
    pregnancy_confirmed_by BIGINT REFERENCES core.res_users(id),
    
    -- Calving
    expected_calving_date DATE GENERATED ALWAYS AS (
        CASE 
            WHEN breeding_type = 'ai' AND date IS NOT NULL THEN date + INTERVAL '283 days'
            WHEN breeding_type = 'natural' AND date IS NOT NULL THEN date + INTERVAL '283 days'
            ELSE NULL 
        END
    ) STORED,
    actual_calving_date DATE,
    calving_ease VARCHAR(50), -- 'easy', 'assisted', 'difficult', 'c_section'
    calving_result VARCHAR(50), -- 'live_single', 'live_twin', 'stillborn'
    calf_id BIGINT REFERENCES farm.animal(id),
    
    -- Notes
    notes TEXT,
    
    -- Status
    status VARCHAR(50) NOT NULL DEFAULT 'in_progress', -- 'in_progress', 'confirmed', 'failed', 'calved'
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT ck_breeding_type CHECK (breeding_type IN ('ai', 'natural', 'et')),
    CONSTRAINT ck_pregnancy_status CHECK (pregnancy_status IN ('pending', 'pregnant', 'open')),
    CONSTRAINT ck_calving_ease CHECK (calving_ease IS NULL OR calving_ease IN ('easy', 'assisted', 'difficult', 'c_section')),
    CONSTRAINT ck_calving_result CHECK (calving_result IS NULL OR calving_result IN ('live_single', 'live_twin', 'live_triplet', 'stillborn', 'abortion'))
);

CREATE INDEX idx_breeding_animal ON farm.breeding_record(animal_id);
CREATE INDEX idx_breeding_date ON farm.breeding_record(date);
CREATE INDEX idx_breeding_status ON farm.breeding_record(status);
CREATE INDEX idx_breeding_pregnancy ON farm.breeding_record(pregnancy_status);
CREATE INDEX idx_breeding_expected_calving ON farm.breeding_record(expected_calving_date) 
    WHERE pregnancy_status = 'pregnant' AND actual_calving_date IS NULL;
```

---

## 5. SALES SCHEMA (sales)

### 5.1 sale_order

```sql
CREATE TABLE sales.order (
    id BIGSERIAL PRIMARY KEY,
    
    -- Order Identification
    name VARCHAR(50) NOT NULL UNIQUE,
    origin VARCHAR(100), -- Source (website, mobile, manual)
    
    -- Customer
    partner_id BIGINT NOT NULL REFERENCES core.res_partner(id),
    partner_invoice_id BIGINT REFERENCES core.res_partner(id),
    partner_shipping_id BIGINT REFERENCES core.res_partner(id),
    
    -- Dates
    order_date DATE NOT NULL DEFAULT CURRENT_DATE,
    confirmation_date TIMESTAMP,
    validity_date DATE,
    commitment_date DATE, -- Promised delivery date
    
    -- Order Type
    order_type VARCHAR(50) NOT NULL DEFAULT 'b2c', -- 'b2c', 'b2b', 'subscription'
    
    -- Financial
    currency_id BIGINT NOT NULL,
    pricelist_id BIGINT,
    payment_term_id BIGINT,
    payment_mode VARCHAR(50),
    
    -- Amounts
    amount_untaxed DECIMAL(15,2) NOT NULL DEFAULT 0,
    amount_tax DECIMAL(15,2) NOT NULL DEFAULT 0,
    amount_total DECIMAL(15,2) NOT NULL DEFAULT 0,
    amount_discount DECIMAL(15,2) NOT NULL DEFAULT 0,
    
    -- Discount
    discount_type VARCHAR(20), -- 'percentage', 'fixed'
    discount_value DECIMAL(10,2) DEFAULT 0,
    
    -- Subscription
    is_subscription BOOLEAN NOT NULL DEFAULT FALSE,
    subscription_start_date DATE,
    subscription_end_date DATE,
    subscription_frequency VARCHAR(50), -- 'daily', 'weekly', 'monthly'
    
    -- Delivery
    warehouse_id BIGINT,
    picking_policy VARCHAR(50) DEFAULT 'direct', -- 'direct', 'one'
    incoterm VARCHAR(50),
    
    -- Status
    state VARCHAR(50) NOT NULL DEFAULT 'draft',
    
    -- Sales Team
    user_id BIGINT REFERENCES core.res_users(id),
    team_id BIGINT,
    
    -- B2B Specific
    b2b_contract_id BIGINT,
    credit_approved BOOLEAN DEFAULT FALSE,
    credit_approved_by BIGINT REFERENCES core.res_users(id),
    credit_approved_date TIMESTAMP,
    
    -- Communication
    client_order_ref VARCHAR(100),
    note TEXT,
    
    -- Tracking
    source_id BIGINT, -- Marketing source
    campaign_id BIGINT,
    medium_id BIGINT,
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT ck_order_type CHECK (order_type IN ('b2c', 'b2b', 'subscription', 'pos')),
    CONSTRAINT ck_order_state CHECK (state IN (
        'draft', 'sent', 'sale', 'done', 'cancel'
    )),
    CONSTRAINT ck_order_discount_type CHECK (discount_type IS NULL OR discount_type IN ('percentage', 'fixed')),
    CONSTRAINT ck_order_subscription_freq CHECK (subscription_frequency IS NULL OR subscription_frequency IN ('daily', 'weekly', 'monthly', 'quarterly'))
);

-- Indexes
CREATE INDEX idx_order_name ON sales.order(name);
CREATE INDEX idx_order_partner ON sales.order(partner_id);
CREATE INDEX idx_order_date ON sales.order(order_date);
CREATE INDEX idx_order_state ON sales.order(state);
CREATE INDEX idx_order_type ON sales.order(order_type);
CREATE INDEX idx_order_user ON sales.order(user_id);
CREATE INDEX idx_order_subscription ON sales.order(is_subscription) WHERE is_subscription = TRUE;
CREATE INDEX idx_order_b2b_credit ON sales.order(order_type, credit_approved) 
    WHERE order_type = 'b2b' AND state IN ('draft', 'sent');
```

### 5.2 sale_order_line

```sql
CREATE TABLE sales.order_line (
    id BIGSERIAL PRIMARY KEY,
    
    -- Order Reference
    order_id BIGINT NOT NULL REFERENCES sales.order(id) ON DELETE CASCADE,
    sequence INTEGER DEFAULT 10,
    
    -- Product
    product_id BIGINT NOT NULL,
    product_uom_id BIGINT NOT NULL,
    
    -- Description
    name TEXT NOT NULL,
    
    -- Quantities
    product_uom_qty DECIMAL(12,3) NOT NULL DEFAULT 1,
    qty_delivered DECIMAL(12,3) NOT NULL DEFAULT 0,
    qty_invoiced DECIMAL(12,3) NOT NULL DEFAULT 0,
    qty_to_invoice DECIMAL(12,3) GENERATED ALWAYS AS (
        qty_delivered - qty_invoiced
    ) STORED,
    
    -- Pricing
    price_unit DECIMAL(15,2) NOT NULL DEFAULT 0,
    price_subtotal DECIMAL(15,2) NOT NULL DEFAULT 0,
    price_tax DECIMAL(15,2) NOT NULL DEFAULT 0,
    price_total DECIMAL(15,2) NOT NULL DEFAULT 0,
    
    -- Discount
    discount DECIMAL(5,2) NOT NULL DEFAULT 0,
    
    -- Tax
    tax_id BIGINT,
    
    -- Subscription
    is_subscription_line BOOLEAN NOT NULL DEFAULT FALSE,
    subscription_start_date DATE,
    subscription_end_date DATE,
    
    -- Route
    route_id BIGINT,
    
    -- Procurement
    procurement_group_id BIGINT,
    
    -- Status
    state VARCHAR(50),
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT ck_order_line_qty CHECK (product_uom_qty > 0),
    CONSTRAINT ck_order_line_price CHECK (price_unit >= 0),
    CONSTRAINT ck_order_line_discount CHECK (discount >= 0 AND discount <= 100)
);

CREATE INDEX idx_order_line_order ON sales.order_line(order_id);
CREATE INDEX idx_order_line_product ON sales.order_line(product_id);
CREATE INDEX idx_order_line_subscription ON sales.order_line(is_subscription_line) 
    WHERE is_subscription_line = TRUE;
```

---

## 6. INVENTORY SCHEMA (inventory)

### 6.1 stock_warehouse

```sql
CREATE TABLE inventory.warehouse (
    id BIGSERIAL PRIMARY KEY,
    
    -- Identification
    name VARCHAR(255) NOT NULL,
    code VARCHAR(20) NOT NULL,
    
    -- Location
    partner_id BIGINT REFERENCES core.res_partner(id),
    address TEXT,
    city VARCHAR(100),
    state_id BIGINT,
    country_id BIGINT,
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    
    -- Operations
    reception_steps VARCHAR(50) DEFAULT 'one_step', -- 'one_step', 'two_step', 'three_step'
    delivery_steps VARCHAR(50) DEFAULT 'ship_only', -- 'ship_only', 'pick_ship', 'pick_pack_ship'
    
    -- Configuration
    buy_to_resupply BOOLEAN DEFAULT TRUE,
    manufacture_to_resupply BOOLEAN DEFAULT FALSE,
    
    -- Status
    active BOOLEAN NOT NULL DEFAULT TRUE,
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT uq_warehouse_code UNIQUE (code)
);

CREATE INDEX idx_warehouse_code ON inventory.warehouse(code);
CREATE INDEX idx_warehouse_active ON inventory.warehouse(active);
```

### 6.2 stock_quant (Current Stock Levels)

```sql
CREATE TABLE inventory.quant (
    id BIGSERIAL PRIMARY KEY,
    
    -- Location
    product_id BIGINT NOT NULL,
    location_id BIGINT NOT NULL,
    lot_id BIGINT,
    package_id BIGINT,
    owner_id BIGINT REFERENCES core.res_partner(id),
    
    -- Quantities
    quantity DECIMAL(15,3) NOT NULL DEFAULT 0,
    reserved_quantity DECIMAL(15,3) NOT NULL DEFAULT 0,
    available_quantity DECIMAL(15,3) GENERATED ALWAYS AS (
        quantity - reserved_quantity
    ) STORED,
    
    -- Unit of Measure
    product_uom_id BIGINT NOT NULL,
    
    -- Inventory Date
    in_date TIMESTAMP,
    
    -- Tracking
    inventory_quantity DECIMAL(15,3),
    inventory_diff_quantity DECIMAL(15,3),
    inventory_date DATE,
    user_id BIGINT REFERENCES core.res_users(id),
    
    -- Constraints
    CONSTRAINT uq_quant_unique UNIQUE (product_id, location_id, lot_id, package_id, owner_id),
    CONSTRAINT ck_quant_quantity CHECK (quantity >= 0),
    CONSTRAINT ck_quant_reserved CHECK (reserved_quantity >= 0),
    CONSTRAINT ck_quant_available CHECK (available_quantity >= 0)
);

CREATE INDEX idx_quant_product ON inventory.quant(product_id);
CREATE INDEX idx_quant_location ON inventory.quant(location_id);
CREATE INDEX idx_quant_lot ON inventory.quant(lot_id);
CREATE INDEX idx_quant_low_stock ON inventory.quant(product_id, available_quantity) 
    WHERE available_quantity <= 10;
```

---

## 7. ACCOUNTING SCHEMA (accounting)

### 7.1 account_account (Chart of Accounts)

```sql
CREATE TABLE accounting.account (
    id BIGSERIAL PRIMARY KEY,
    
    -- Identification
    code VARCHAR(20) NOT NULL,
    name VARCHAR(255) NOT NULL,
    
    -- Classification
    account_type VARCHAR(50) NOT NULL,
    internal_type VARCHAR(50), -- 'receivable', 'payable', 'liquidity', 'other'
    internal_group VARCHAR(50), -- 'income', 'expense', 'asset', 'liability', 'equity'
    
    -- Configuration
    currency_id BIGINT,
    reconcile BOOLEAN NOT NULL DEFAULT FALSE,
    deprecated BOOLEAN NOT NULL DEFAULT FALSE,
    note TEXT,
    
    -- Hierarchy
    parent_id BIGINT REFERENCES accounting.account(id),
    
    -- Bangladesh-specific
    vat_applicable BOOLEAN DEFAULT FALSE,
    vat_category VARCHAR(50),
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT uq_account_code UNIQUE (code),
    CONSTRAINT ck_account_type CHECK (account_type IN (
        'asset_current', 'asset_non_current', 'asset_fixed',
        'liability_current', 'liability_non_current',
        'equity', 'income', 'expense', 'expense_depreciation'
    ))
);

CREATE INDEX idx_account_code ON accounting.account(code);
CREATE INDEX idx_account_type ON accounting.account(account_type);
CREATE INDEX idx_account_parent ON accounting.account(parent_id);
CREATE INDEX idx_account_reconcile ON accounting.account(reconcile) WHERE reconcile = TRUE;
```

### 7.2 account_move (Journal Entries)

```sql
CREATE TABLE accounting.move (
    id BIGSERIAL PRIMARY KEY,
    
    -- Identification
    name VARCHAR(50) NOT NULL UNIQUE,
    ref VARCHAR(255),
    
    -- Type
    move_type VARCHAR(50) NOT NULL, -- 'entry', 'out_invoice', 'in_invoice', 'out_refund', 'in_refund'
    
    -- Partner
    partner_id BIGINT REFERENCES core.res_partner(id),
    commercial_partner_id BIGINT REFERENCES core.res_partner(id),
    
    -- Dates
    date DATE NOT NULL,
    invoice_date DATE,
    invoice_date_due DATE,
    
    -- Currency
    currency_id BIGINT NOT NULL,
    company_currency_id BIGINT NOT NULL,
    
    -- Amounts
    amount_untaxed DECIMAL(15,2) NOT NULL DEFAULT 0,
    amount_tax DECIMAL(15,2) NOT NULL DEFAULT 0,
    amount_total DECIMAL(15,2) NOT NULL DEFAULT 0,
    amount_residual DECIMAL(15,2) NOT NULL DEFAULT 0,
    
    -- Payment
    payment_state VARCHAR(50) DEFAULT 'not_paid', -- 'not_paid', 'in_payment', 'paid', 'partial'
    
    -- Origin
    invoice_origin VARCHAR(255),
    invoice_partner_display_name VARCHAR(255),
    
    -- Narration
    narration TEXT,
    
    -- State
    state VARCHAR(50) NOT NULL DEFAULT 'draft', -- 'draft', 'posted', 'cancel'
    posted_before BOOLEAN DEFAULT FALSE,
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT ck_move_type CHECK (move_type IN (
        'entry', 'out_invoice', 'in_invoice', 'out_refund', 'in_refund',
        'out_receipt', 'in_receipt'
    )),
    CONSTRAINT ck_move_state CHECK (state IN ('draft', 'posted', 'cancel')),
    CONSTRAINT ck_payment_state CHECK (payment_state IN ('not_paid', 'in_payment', 'paid', 'partial', 'reversed', 'invoicing_legacy'))
);

CREATE INDEX idx_move_name ON accounting.move(name);
CREATE INDEX idx_move_partner ON accounting.move(partner_id);
CREATE INDEX idx_move_date ON accounting.move(date);
CREATE INDEX idx_move_type ON accounting.move(move_type);
CREATE INDEX idx_move_state ON accounting.move(state);
CREATE INDEX idx_move_payment ON accounting.move(payment_state);
CREATE INDEX idx_move_due_date ON accounting.move(invoice_date_due) 
    WHERE move_type IN ('out_invoice', 'in_invoice') AND payment_state != 'paid';
```

### 7.3 account_move_line (Journal Entry Lines)

```sql
CREATE TABLE accounting.move_line (
    id BIGSERIAL PRIMARY KEY,
    
    -- Move Reference
    move_id BIGINT NOT NULL REFERENCES accounting.move(id) ON DELETE CASCADE,
    
    -- Account
    account_id BIGINT NOT NULL REFERENCES accounting.account(id),
    
    -- Partner
    partner_id BIGINT REFERENCES core.res_partner(id),
    
    -- Description
    name TEXT,
    
    -- Analytics
    analytic_account_id BIGINT,
    analytic_tag_ids INTEGER[],
    
    -- Amounts (in move currency)
    debit DECIMAL(15,2) NOT NULL DEFAULT 0,
    credit DECIMAL(15,2) NOT NULL DEFAULT 0,
    balance DECIMAL(15,2) GENERATED ALWAYS AS (debit - credit) STORED,
    
    -- Amounts (in company currency)
    amount_currency DECIMAL(15,2),
    currency_id BIGINT,
    
    -- Reconciliation
    reconciled BOOLEAN NOT NULL DEFAULT FALSE,
    full_reconcile_id BIGINT,
    matched_debit_ids INTEGER[],
    matched_credit_ids INTEGER[],
    
    -- Tax
    tax_ids INTEGER[],
    tax_line_id BIGINT,
    tax_base_amount DECIMAL(15,2),
    tax_group_id BIGINT,
    
    -- Product
    product_id BIGINT,
    quantity DECIMAL(15,3),
    product_uom_id BIGINT,
    price_unit DECIMAL(15,2),
    discount DECIMAL(5,2),
    
    -- Origin
    date_maturity DATE,
    
    -- Audit
    create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT ck_move_line_debit_credit CHECK (
        (debit = 0 AND credit != 0) OR (debit != 0 AND credit = 0) OR (debit = 0 AND credit = 0)
    )
);

CREATE INDEX idx_move_line_move ON accounting.move_line(move_id);
CREATE INDEX idx_move_line_account ON accounting.move_line(account_id);
CREATE INDEX idx_move_line_partner ON accounting.move_line(partner_id);
CREATE INDEX idx_move_line_reconciled ON accounting.move_line(reconciled) WHERE reconciled = FALSE;
CREATE INDEX idx_move_line_maturity ON accounting.move_line(date_maturity) WHERE reconciled = FALSE;
```

---

## 8. IOT SCHEMA (iot)

### 8.1 iot_device (Device Registry)

```sql
CREATE TABLE iot.device (
    id BIGSERIAL PRIMARY KEY,
    
    -- Identification
    device_id VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    serial_number VARCHAR(100),
    
    -- Classification
    device_type VARCHAR(50) NOT NULL, -- 'sensor', 'controller', 'gateway'
    device_model VARCHAR(100),
    
    -- Location
    barn_id BIGINT REFERENCES farm.barn(id),
    animal_id BIGINT REFERENCES farm.animal(id),
    location_description TEXT,
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    
    -- Connectivity
    protocol VARCHAR(50), -- 'mqtt', 'http', 'lora', 'nbiot'
    ip_address INET,
    mac_address MACADDR,
    
    -- Configuration
    config JSONB DEFAULT '{}',
    firmware_version VARCHAR(50),
    
    -- Status
    status VARCHAR(50) NOT NULL DEFAULT 'active', -- 'active', 'inactive', 'maintenance', 'offline'
    last_seen_at TIMESTAMP,
    battery_level INTEGER,
    signal_strength INTEGER,
    
    -- Authentication
    auth_token_hash VARCHAR(255),
    cert_fingerprint VARCHAR(255),
    
    -- Metadata
    manufacturer VARCHAR(100),
    purchase_date DATE,
    warranty_expiry DATE,
    
    -- Notes
    notes TEXT,
    
    -- Audit
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT ck_device_type CHECK (device_type IN ('sensor', 'controller', 'gateway', 'camera')),
    CONSTRAINT ck_device_status CHECK (status IN ('active', 'inactive', 'maintenance', 'offline', 'decommissioned')),
    CONSTRAINT ck_device_battery CHECK (battery_level IS NULL OR (battery_level >= 0 AND battery_level <= 100)),
    CONSTRAINT ck_device_signal CHECK (signal_strength IS NULL OR (signal_strength >= -120 AND signal_strength <= 0))
);

CREATE INDEX idx_device_barn ON iot.device(barn_id);
CREATE INDEX idx_device_animal ON iot.device(animal_id);
CREATE INDEX idx_device_type ON iot.device(device_type);
CREATE INDEX idx_device_status ON iot.device(status);
CREATE INDEX idx_device_last_seen ON iot.device(last_seen_at);
```

### 8.2 iot_sensor_data (TimescaleDB Hypertable)

```sql
-- Enable TimescaleDB
CREATE EXTENSION IF NOT EXISTS timescaledb;

-- Create hypertable for time-series data
CREATE TABLE iot.sensor_data (
    time TIMESTAMPTZ NOT NULL,
    device_id VARCHAR(100) NOT NULL REFERENCES iot.device(device_id),
    sensor_type VARCHAR(50) NOT NULL, -- 'temperature', 'humidity', 'ph', 'milk_flow', 'activity'
    
    -- Location reference (for quick queries)
    barn_id BIGINT,
    animal_id BIGINT,
    
    -- Value
    value DECIMAL(10,3) NOT NULL,
    unit VARCHAR(20),
    
    -- Quality
    quality_score DECIMAL(3,2), -- 0.0 to 1.0
    is_anomaly BOOLEAN DEFAULT FALSE,
    
    -- Metadata
    metadata JSONB DEFAULT '{}',
    
    -- Processing status
    processed BOOLEAN DEFAULT FALSE,
    processed_at TIMESTAMP,
    
    -- Audit
    received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT ck_sensor_data_quality CHECK (quality_score IS NULL OR (quality_score >= 0 AND quality_score <= 1)),
    CONSTRAINT ck_sensor_type CHECK (sensor_type IN (
        'temperature', 'humidity', 'ph', 'conductivity', 'milk_flow',
        'activity', 'rumination', 'weight', 'location_gps', 'heartbeat'
    ))
);

-- Convert to hypertable with 1-day chunks
SELECT create_hypertable('iot.sensor_data', 'time', 
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Create indexes
CREATE INDEX idx_sensor_data_device_time ON iot.sensor_data(device_id, time DESC);
CREATE INDEX idx_sensor_data_type_time ON iot.sensor_data(sensor_type, time DESC);
CREATE INDEX idx_sensor_data_barn ON iot.sensor_data(barn_id, time DESC);
CREATE INDEX idx_sensor_data_animal ON iot.sensor_data(animal_id, time DESC) WHERE animal_id IS NOT NULL;
CREATE INDEX idx_sensor_data_anomaly ON iot.sensor_data(is_anomaly, time DESC) WHERE is_anomaly = TRUE;
CREATE INDEX idx_sensor_data_unprocessed ON iot.sensor_data(processed) WHERE processed = FALSE;

-- Set retention policy (2 years)
SELECT add_retention_policy('iot.sensor_data', INTERVAL '2 years');
```

---

## 9. INDEX SPECIFICATIONS

### 9.1 Index Categories

| Category | Type | Purpose | Examples |
|----------|------|---------|----------|
| **Primary** | B-tree | Unique row identification | `PRIMARY KEY` |
| **Foreign Key** | B-tree | Join optimization | `idx_{table}_{fk_column}` |
| **Search** | GIN/GiST | Full-text, trigram search | `idx_{table}_{column}_gin` |
| **Range** | B-tree | Date/number range queries | `idx_{table}_{date_column}` |
| **Partial** | B-tree | Filtered data access | `WHERE active = TRUE` |
| **Composite** | B-tree | Multi-column queries | `idx_{table}_{col1}_{col2}` |

### 9.2 Index Naming Convention

```sql
-- Standard B-tree index
CREATE INDEX idx_{table}_{column} ON {schema}.{table}({column});

-- Composite index (follow column order by selectivity)
CREATE INDEX idx_{table}_{col1}_{col2} ON {schema}.{table}({col1}, {col2});

-- Partial index (filtered)
CREATE INDEX idx_{table}_{column}_{filter} 
    ON {schema}.{table}({column}) 
    WHERE {condition};

-- GIN index for full-text
CREATE INDEX idx_{table}_{column}_gin 
    ON {schema}.{table} USING gin({column} gin_trgm_ops);

-- Unique index
CREATE UNIQUE INDEX uq_{table}_{column} 
    ON {schema}.{table}({column});
```

---

## 10. CONSTRAINT DEFINITIONS

### 10.1 Check Constraints by Category

```sql
-- Status constraints (standard pattern)
CONSTRAINT ck_{table}_status CHECK (
    status IN ('draft', 'active', 'inactive', 'archived')
);

-- Percentage constraints
CONSTRAINT ck_{table}_{field}_percent CHECK (
    {field} >= 0 AND {field} <= 100
);

-- Date constraints (not in future)
CONSTRAINT ck_{table}_{date_field} CHECK (
    {date_field} <= CURRENT_DATE
);

-- Positive amount
CONSTRAINT ck_{table}_{amount}_positive CHECK (
    {amount} >= 0
);

-- Relationship integrity (self-reference)
CONSTRAINT ck_{table}_no_self_ref CHECK (
    parent_id != id
);
```

### 10.2 Foreign Key Constraints

```sql
-- Standard foreign key with cascade delete
CONSTRAINT fk_{table}_{column} 
    FOREIGN KEY ({column}) 
    REFERENCES {ref_schema}.{ref_table}(id)
    ON DELETE CASCADE;

-- Foreign key with restrict delete
CONSTRAINT fk_{table}_{column} 
    FOREIGN KEY ({column}) 
    REFERENCES {ref_schema}.{ref_table}(id)
    ON DELETE RESTRICT;

-- Foreign key with set null
CONSTRAINT fk_{table}_{column} 
    FOREIGN KEY ({column}) 
    REFERENCES {ref_schema}.{ref_table}(id)
    ON DELETE SET NULL;
```

---

## 11. PARTITIONING DETAILS

### 11.1 Partitioning Strategy Summary

| Table | Partition Type | Key | Interval | Retention |
|-------|---------------|-----|----------|-----------|
| farm.milk_production | RANGE | production_date | Quarterly | 7 years |
| iot.sensor_data | Hypertable | time | Daily | 2 years |
| accounting.move | RANGE | date | Monthly | 7 years |
| sales.order | RANGE | order_date | Monthly | 7 years |

### 11.2 Automated Partition Management

```sql
-- Function to create future milk_production partitions
CREATE OR REPLACE FUNCTION farm.create_milk_production_partition()
RETURNS void AS $$
DECLARE
    start_date DATE;
    end_date DATE;
    partition_name TEXT;
    start_year INT;
    start_quarter INT;
BEGIN
    -- Create partitions for next 4 quarters
    FOR i IN 1..4 LOOP
        start_date := DATE_TRUNC('quarter', CURRENT_DATE + (i * INTERVAL '3 months'));
        end_date := start_date + INTERVAL '3 months';
        start_year := EXTRACT(YEAR FROM start_date);
        start_quarter := EXTRACT(QUARTER FROM start_date);
        partition_name := 'milk_production_' || start_year || '_q' || start_quarter;
        
        EXECUTE format(
            'CREATE TABLE IF NOT EXISTS farm.%I PARTITION OF farm.milk_production FOR VALUES FROM (%L) TO (%L)',
            partition_name, start_date, end_date
        );
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- Schedule via pg_cron (if available)
-- SELECT cron.schedule('create-partitions', '0 1 1 * *', 'SELECT farm.create_milk_production_partition()');
```

---

## 12. APPENDICES

### Appendix A: Complete Table Inventory

| Schema | Table | Purpose | Estimated Rows (Y1) |
|--------|-------|---------|---------------------|
| core | res_partner | Customers/suppliers | 10,000 |
| core | res_users | System users | 100 |
| core | ir_config_parameter | System config | 200 |
| farm | breed | Animal breeds | 10 |
| farm | animal | Animal registry | 500 |
| farm | milk_production | Daily milk records | 100,000 |
| farm | breeding_record | Breeding events | 1,000 |
| sales | order | Sales orders | 50,000 |
| sales | order_line | Order items | 200,000 |
| inventory | warehouse | Warehouses | 5 |
| inventory | quant | Stock levels | 1,000 |
| accounting | account | Chart of accounts | 200 |
| accounting | move | Journal entries | 500,000 |
| iot | device | IoT devices | 50 |
| iot | sensor_data | Sensor readings | 182,500,000 |

### Appendix B: Storage Estimates

| Schema | Tables | Y1 Size | Y2 Size | Y5 Size |
|--------|--------|---------|---------|---------|
| core | 15 | 500 MB | 1 GB | 3 GB |
| farm | 20 | 5 GB | 15 GB | 50 GB |
| sales | 12 | 2 GB | 5 GB | 15 GB |
| inventory | 10 | 500 MB | 1 GB | 3 GB |
| accounting | 18 | 3 GB | 8 GB | 25 GB |
| iot | 5 | 200 GB | 500 GB | 2 TB |
| **Total** | **80+** | **~210 GB** | **~530 GB** | **~2 TB** |

### Appendix C: Schema Creation Script

```sql
-- Complete schema setup script
-- Run this as database owner

-- Create schemas
CREATE SCHEMA IF NOT EXISTS core;
CREATE SCHEMA IF NOT EXISTS farm;
CREATE SCHEMA IF NOT EXISTS sales;
CREATE SCHEMA IF NOT EXISTS inventory;
CREATE SCHEMA IF NOT EXISTS accounting;
CREATE SCHEMA IF NOT EXISTS iot;

-- Set search path
ALTER DATABASE smart_dairy_prod SET search_path TO core, farm, sales, inventory, accounting, iot, public;

-- Grant permissions
GRANT USAGE ON SCHEMA core, farm, sales, inventory, accounting, iot TO app_user;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA core, farm, sales, inventory, accounting, iot TO app_user;
GRANT USAGE ON ALL SEQUENCES IN SCHEMA core, farm, sales, inventory, accounting, iot TO app_user;
```

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Database Architect | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
