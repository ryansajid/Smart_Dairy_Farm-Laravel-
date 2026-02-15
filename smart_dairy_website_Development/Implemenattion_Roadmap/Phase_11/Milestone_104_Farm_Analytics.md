# Milestone 104: Farm Analytics Module

## Document Control

| Attribute | Value |
|-----------|-------|
| **Document ID** | PH11-M104-FARM-ANALYTICS |
| **Version** | 1.0 |
| **Author** | Senior Technical Architect |
| **Created** | 2026-02-04 |
| **Last Updated** | 2026-02-04 |
| **Status** | Final |
| **Classification** | Internal Use |
| **Parent Phase** | Phase 11 - Commerce Advanced Analytics |
| **Timeline** | Days 531-540 |

---

## 1. Executive Summary

### 1.1 Milestone Overview

Milestone 104 delivers a comprehensive Farm Analytics Module that provides deep operational intelligence across all dairy farming operations. This module transforms raw farm data—including milk production, animal health, feed consumption, and breeding records—into actionable insights that drive productivity improvements, cost optimization, and sustainable farming practices.

### 1.2 Business Value Proposition

| Value Area | Expected Outcome | Measurement |
|------------|------------------|-------------|
| Yield Optimization | 12-15% increase in milk yield per animal | Liters/animal/day tracking |
| Cost Reduction | 8-10% decrease in feed costs | Cost per liter metric |
| Animal Welfare | 20% reduction in health incidents | Health event tracking |
| Breeding Efficiency | 15% improvement in conception rates | Breeding success analytics |
| Resource Planning | 25% better resource allocation | Utilization dashboards |

### 1.3 Strategic Alignment

This milestone directly supports:
- **RFP-FARM-001**: Real-time farm performance monitoring
- **BRD-OPS-003**: Operational efficiency analytics
- **SRS-ANALYTICS-012**: Predictive yield modeling
- **SRS-ANALYTICS-013**: Animal health trend analysis
- **SRS-ANALYTICS-014**: Feed efficiency optimization

---

## 2. Detailed Scope Statement

### 2.1 In-Scope Deliverables

| Category | Deliverable | Priority |
|----------|-------------|----------|
| Yield Analytics | Per-animal milk yield tracking and analysis | P0 |
| Yield Analytics | Herd-level production benchmarking | P0 |
| Yield Analytics | Seasonal pattern identification | P1 |
| Cost Analytics | Cost per liter calculation engine | P0 |
| Cost Analytics | Feed cost allocation models | P0 |
| Cost Analytics | Labor cost distribution | P1 |
| Health Analytics | Animal health trend monitoring | P0 |
| Health Analytics | Disease outbreak prediction | P1 |
| Health Analytics | Vaccination compliance tracking | P1 |
| Breeding Analytics | Conception rate analytics | P0 |
| Breeding Analytics | Genetic performance tracking | P1 |
| Breeding Analytics | Calving interval optimization | P1 |
| Predictive Models | Yield forecasting (30/60/90 days) | P0 |
| Predictive Models | Health risk scoring | P1 |
| Predictive Models | Feed requirement prediction | P1 |

### 2.2 Out-of-Scope Items

- IoT sensor hardware procurement and installation
- Veterinary diagnostic systems integration
- Genetic testing laboratory interfaces
- Weather station hardware deployment
- Third-party farm management software migration

### 2.3 Assumptions and Constraints

**Assumptions:**
- Animal identification data (ear tags, RFID) is standardized
- Historical milk collection records are available (minimum 12 months)
- Feed inventory data is tracked in Odoo
- Veterinary records are digitized

**Constraints:**
- Must support offline data collection for remote farms
- Real-time sync latency must not exceed 5 minutes
- Historical data retention: 7 years minimum
- Must comply with agricultural data privacy regulations

---

## 3. Technical Architecture

### 3.1 Farm Analytics Data Model

```sql
-- =====================================================
-- FARM ANALYTICS STAR SCHEMA
-- Milestone 104 - Days 531-540
-- =====================================================

-- =====================================================
-- DIMENSION TABLES
-- =====================================================

-- Animal Dimension
CREATE TABLE dim_animal (
    animal_sk SERIAL PRIMARY KEY,
    animal_id VARCHAR(50) NOT NULL,
    animal_code VARCHAR(20) UNIQUE NOT NULL,
    ear_tag_number VARCHAR(30),
    rfid_tag VARCHAR(50),
    animal_name VARCHAR(100),
    breed_id INTEGER REFERENCES dim_breed(breed_sk),
    birth_date DATE,
    acquisition_date DATE,
    acquisition_type VARCHAR(20), -- 'born', 'purchased', 'transferred'
    dam_animal_sk INTEGER REFERENCES dim_animal(animal_sk),
    sire_animal_sk INTEGER REFERENCES dim_animal(animal_sk),
    gender VARCHAR(10) CHECK (gender IN ('female', 'male')),
    current_status VARCHAR(20), -- 'active', 'dry', 'culled', 'sold', 'deceased'
    lactation_number INTEGER DEFAULT 0,
    current_farm_sk INTEGER REFERENCES dim_farm(farm_sk),
    current_pen_location VARCHAR(50),
    is_current BOOLEAN DEFAULT TRUE,
    valid_from TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valid_to TIMESTAMP DEFAULT '9999-12-31'::TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_dim_animal_code ON dim_animal(animal_code);
CREATE INDEX idx_dim_animal_farm ON dim_animal(current_farm_sk);
CREATE INDEX idx_dim_animal_status ON dim_animal(current_status);

-- Breed Dimension
CREATE TABLE dim_breed (
    breed_sk SERIAL PRIMARY KEY,
    breed_code VARCHAR(20) UNIQUE NOT NULL,
    breed_name VARCHAR(100) NOT NULL,
    breed_type VARCHAR(50), -- 'dairy', 'dual_purpose', 'beef'
    origin_country VARCHAR(50),
    avg_milk_yield_liters DECIMAL(8,2),
    avg_fat_percentage DECIMAL(5,2),
    avg_protein_percentage DECIMAL(5,2),
    avg_lactation_days INTEGER,
    heat_tolerance_rating INTEGER CHECK (heat_tolerance_rating BETWEEN 1 AND 10),
    disease_resistance_rating INTEGER CHECK (disease_resistance_rating BETWEEN 1 AND 10),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Feed Type Dimension
CREATE TABLE dim_feed_type (
    feed_type_sk SERIAL PRIMARY KEY,
    feed_code VARCHAR(20) UNIQUE NOT NULL,
    feed_name VARCHAR(100) NOT NULL,
    feed_category VARCHAR(50), -- 'concentrate', 'roughage', 'supplement', 'mineral'
    dry_matter_percentage DECIMAL(5,2),
    crude_protein_percentage DECIMAL(5,2),
    energy_mj_per_kg DECIMAL(8,2),
    cost_per_kg DECIMAL(10,2),
    unit_of_measure VARCHAR(20),
    supplier_id INTEGER,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Health Event Type Dimension
CREATE TABLE dim_health_event_type (
    health_event_type_sk SERIAL PRIMARY KEY,
    event_code VARCHAR(20) UNIQUE NOT NULL,
    event_name VARCHAR(100) NOT NULL,
    event_category VARCHAR(50), -- 'disease', 'injury', 'vaccination', 'treatment', 'examination'
    severity_level VARCHAR(20), -- 'minor', 'moderate', 'severe', 'critical'
    typical_recovery_days INTEGER,
    affects_production BOOLEAN DEFAULT FALSE,
    is_contagious BOOLEAN DEFAULT FALSE,
    quarantine_required BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Lactation Dimension
CREATE TABLE dim_lactation (
    lactation_sk SERIAL PRIMARY KEY,
    animal_sk INTEGER NOT NULL REFERENCES dim_animal(animal_sk),
    lactation_number INTEGER NOT NULL,
    calving_date DATE NOT NULL,
    dry_off_date DATE,
    lactation_status VARCHAR(20), -- 'active', 'completed', 'aborted'
    days_in_milk INTEGER,
    peak_yield_liters DECIMAL(8,2),
    peak_yield_day INTEGER,
    total_yield_liters DECIMAL(12,2),
    avg_daily_yield DECIMAL(8,2),
    projected_305_day_yield DECIMAL(12,2),
    calving_ease_score INTEGER CHECK (calving_ease_score BETWEEN 1 AND 5),
    calf_animal_sk INTEGER REFERENCES dim_animal(animal_sk),
    calf_birth_weight_kg DECIMAL(6,2),
    is_current BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(animal_sk, lactation_number)
);

CREATE INDEX idx_dim_lactation_animal ON dim_lactation(animal_sk);
CREATE INDEX idx_dim_lactation_status ON dim_lactation(lactation_status);

-- =====================================================
-- FACT TABLES
-- =====================================================

-- Daily Milk Production Fact
CREATE TABLE fact_daily_milk_production (
    production_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    animal_sk INTEGER NOT NULL REFERENCES dim_animal(animal_sk),
    farm_sk INTEGER NOT NULL REFERENCES dim_farm(farm_sk),
    lactation_sk INTEGER REFERENCES dim_lactation(lactation_sk),

    -- Morning Milking
    morning_yield_liters DECIMAL(8,2) DEFAULT 0,
    morning_fat_percentage DECIMAL(5,2),
    morning_protein_percentage DECIMAL(5,2),
    morning_somatic_cell_count INTEGER,
    morning_milking_duration_mins INTEGER,
    morning_milking_time TIME,

    -- Evening Milking
    evening_yield_liters DECIMAL(8,2) DEFAULT 0,
    evening_fat_percentage DECIMAL(5,2),
    evening_protein_percentage DECIMAL(5,2),
    evening_somatic_cell_count INTEGER,
    evening_milking_duration_mins INTEGER,
    evening_milking_time TIME,

    -- Daily Totals
    total_daily_yield_liters DECIMAL(8,2) GENERATED ALWAYS AS
        (COALESCE(morning_yield_liters, 0) + COALESCE(evening_yield_liters, 0)) STORED,
    avg_fat_percentage DECIMAL(5,2),
    avg_protein_percentage DECIMAL(5,2),
    avg_somatic_cell_count INTEGER,

    -- Quality Indicators
    milk_quality_grade VARCHAR(5), -- 'A', 'B', 'C', 'rejected'
    rejection_reason VARCHAR(100),

    -- Lactation Context
    days_in_milk INTEGER,
    lactation_week INTEGER,

    -- Metadata
    data_source VARCHAR(50), -- 'automated', 'manual', 'imported'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(date_sk, animal_sk)
);

CREATE INDEX idx_fact_milk_date ON fact_daily_milk_production(date_sk);
CREATE INDEX idx_fact_milk_animal ON fact_daily_milk_production(animal_sk);
CREATE INDEX idx_fact_milk_farm ON fact_daily_milk_production(farm_sk);
CREATE INDEX idx_fact_milk_lactation ON fact_daily_milk_production(lactation_sk);

-- Feed Consumption Fact
CREATE TABLE fact_feed_consumption (
    consumption_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    animal_sk INTEGER REFERENCES dim_animal(animal_sk), -- NULL for group feeding
    farm_sk INTEGER NOT NULL REFERENCES dim_farm(farm_sk),
    feed_type_sk INTEGER NOT NULL REFERENCES dim_feed_type(feed_type_sk),
    pen_location VARCHAR(50),

    -- Consumption Metrics
    quantity_kg DECIMAL(10,2) NOT NULL,
    dry_matter_intake_kg DECIMAL(10,2),
    cost_amount DECIMAL(12,2),

    -- Group Feeding Context
    feeding_group_id VARCHAR(50),
    animals_in_group INTEGER,
    per_animal_quantity_kg DECIMAL(10,2),

    -- Feeding Schedule
    feeding_session VARCHAR(20), -- 'morning', 'midday', 'evening', 'ad_libitum'
    feeding_time TIME,

    -- Efficiency Metrics
    feed_conversion_ratio DECIMAL(8,4), -- kg feed / kg milk

    -- Metadata
    recorded_by INTEGER,
    data_source VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_feed_date ON fact_feed_consumption(date_sk);
CREATE INDEX idx_fact_feed_animal ON fact_feed_consumption(animal_sk);
CREATE INDEX idx_fact_feed_type ON fact_feed_consumption(feed_type_sk);

-- Animal Health Event Fact
CREATE TABLE fact_animal_health_event (
    health_event_sk BIGSERIAL PRIMARY KEY,
    event_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    animal_sk INTEGER NOT NULL REFERENCES dim_animal(animal_sk),
    farm_sk INTEGER NOT NULL REFERENCES dim_farm(farm_sk),
    health_event_type_sk INTEGER NOT NULL REFERENCES dim_health_event_type(health_event_type_sk),

    -- Event Details
    event_datetime TIMESTAMP NOT NULL,
    diagnosis_code VARCHAR(20),
    diagnosis_description TEXT,
    symptoms TEXT,
    body_condition_score DECIMAL(3,1) CHECK (body_condition_score BETWEEN 1 AND 5),
    temperature_celsius DECIMAL(4,1),

    -- Treatment Information
    treatment_given TEXT,
    medication_administered TEXT,
    dosage_amount DECIMAL(10,2),
    dosage_unit VARCHAR(20),
    withdrawal_period_days INTEGER,
    milk_withdrawal_end_date DATE,
    meat_withdrawal_end_date DATE,

    -- Veterinary Information
    veterinarian_id INTEGER,
    veterinarian_name VARCHAR(100),
    external_vet_practice VARCHAR(100),

    -- Outcome Tracking
    event_status VARCHAR(20), -- 'ongoing', 'resolved', 'chronic', 'fatal'
    resolution_date DATE,
    resolution_notes TEXT,

    -- Cost Information
    treatment_cost DECIMAL(12,2),
    veterinary_fee DECIMAL(12,2),
    medication_cost DECIMAL(12,2),
    total_event_cost DECIMAL(12,2),

    -- Production Impact
    estimated_milk_loss_liters DECIMAL(10,2),
    days_out_of_production INTEGER,

    -- Metadata
    reported_by INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_health_date ON fact_animal_health_event(event_date_sk);
CREATE INDEX idx_fact_health_animal ON fact_animal_health_event(animal_sk);
CREATE INDEX idx_fact_health_type ON fact_animal_health_event(health_event_type_sk);
CREATE INDEX idx_fact_health_status ON fact_animal_health_event(event_status);

-- Breeding Event Fact
CREATE TABLE fact_breeding_event (
    breeding_event_sk BIGSERIAL PRIMARY KEY,
    event_date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    animal_sk INTEGER NOT NULL REFERENCES dim_animal(animal_sk),
    farm_sk INTEGER NOT NULL REFERENCES dim_farm(farm_sk),

    -- Heat Detection
    heat_detected_datetime TIMESTAMP,
    heat_detection_method VARCHAR(50), -- 'visual', 'activity_monitor', 'milk_progesterone', 'pedometer'
    heat_intensity_score INTEGER CHECK (heat_intensity_score BETWEEN 1 AND 5),

    -- Breeding Details
    breeding_datetime TIMESTAMP NOT NULL,
    breeding_method VARCHAR(30), -- 'AI', 'natural', 'ET'
    sire_animal_sk INTEGER REFERENCES dim_animal(animal_sk),
    sire_code VARCHAR(50),
    sire_breed VARCHAR(50),
    semen_batch_number VARCHAR(50),
    straw_code VARCHAR(50),
    technician_id INTEGER,
    technician_name VARCHAR(100),

    -- Pregnancy Confirmation
    pregnancy_check_date DATE,
    pregnancy_check_method VARCHAR(50), -- 'rectal_palpation', 'ultrasound', 'blood_test'
    pregnancy_confirmed BOOLEAN,
    estimated_calving_date DATE,
    fetal_count INTEGER,
    fetal_gender VARCHAR(20),

    -- Outcome
    breeding_outcome VARCHAR(30), -- 'confirmed_pregnant', 'open', 'early_embryonic_death', 'abortion', 'pending'
    days_to_conception INTEGER,
    services_per_conception INTEGER,

    -- Cost Information
    semen_cost DECIMAL(10,2),
    technician_fee DECIMAL(10,2),
    total_breeding_cost DECIMAL(12,2),

    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fact_breeding_date ON fact_breeding_event(event_date_sk);
CREATE INDEX idx_fact_breeding_animal ON fact_breeding_event(animal_sk);
CREATE INDEX idx_fact_breeding_outcome ON fact_breeding_event(breeding_outcome);

-- Daily Farm Aggregates Fact
CREATE TABLE fact_daily_farm_aggregate (
    aggregate_sk BIGSERIAL PRIMARY KEY,
    date_sk INTEGER NOT NULL REFERENCES dim_date(date_sk),
    farm_sk INTEGER NOT NULL REFERENCES dim_farm(farm_sk),

    -- Herd Counts
    total_animals INTEGER,
    milking_animals INTEGER,
    dry_animals INTEGER,
    heifers INTEGER,
    calves INTEGER,
    bulls INTEGER,

    -- Production Aggregates
    total_milk_yield_liters DECIMAL(12,2),
    avg_yield_per_animal DECIMAL(8,2),
    avg_fat_percentage DECIMAL(5,2),
    avg_protein_percentage DECIMAL(5,2),
    avg_somatic_cell_count INTEGER,
    milk_quality_a_percentage DECIMAL(5,2),

    -- Feed Aggregates
    total_feed_consumed_kg DECIMAL(12,2),
    total_feed_cost DECIMAL(12,2),
    avg_feed_per_animal_kg DECIMAL(8,2),
    herd_feed_conversion_ratio DECIMAL(8,4),

    -- Health Aggregates
    health_events_count INTEGER,
    animals_under_treatment INTEGER,
    animals_in_withdrawal INTEGER,
    vaccination_compliance_pct DECIMAL(5,2),

    -- Breeding Aggregates
    heats_detected INTEGER,
    breedings_performed INTEGER,
    pregnancies_confirmed INTEGER,
    conception_rate_pct DECIMAL(5,2),

    -- Cost Aggregates
    total_daily_cost DECIMAL(12,2),
    cost_per_liter DECIMAL(8,4),

    -- Weather Context (from external integration)
    avg_temperature_celsius DECIMAL(5,1),
    humidity_percentage DECIMAL(5,1),
    heat_stress_index DECIMAL(5,2),

    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE(date_sk, farm_sk)
);

CREATE INDEX idx_fact_farm_agg_date ON fact_daily_farm_aggregate(date_sk);
CREATE INDEX idx_fact_farm_agg_farm ON fact_daily_farm_aggregate(farm_sk);
```

### 3.2 ETL Pipeline for Farm Data

```python
# airflow/dags/farm_analytics_etl.py
"""
Farm Analytics ETL Pipeline
Milestone 104 - Days 531-540
Extracts farm operational data and loads into analytics data warehouse
"""

from datetime import datetime, timedelta
from airflow import DAG
from airflow.operators.python import PythonOperator
from airflow.providers.postgres.hooks.postgres import PostgresHook
from airflow.providers.postgres.operators.postgres import PostgresOperator
from airflow.utils.task_group import TaskGroup
import pandas as pd
import numpy as np
from typing import Dict, List, Any
import logging

logger = logging.getLogger(__name__)

# DAG Configuration
default_args = {
    'owner': 'analytics_team',
    'depends_on_past': False,
    'email': ['analytics@smartdairy.com'],
    'email_on_failure': True,
    'email_on_retry': False,
    'retries': 3,
    'retry_delay': timedelta(minutes=5),
    'execution_timeout': timedelta(hours=2),
}

dag = DAG(
    'farm_analytics_etl',
    default_args=default_args,
    description='Daily ETL for farm analytics data warehouse',
    schedule_interval='0 4 * * *',  # Run at 4 AM daily
    start_date=datetime(2026, 1, 1),
    catchup=False,
    max_active_runs=1,
    tags=['farm', 'analytics', 'etl', 'phase11'],
)


class FarmDataExtractor:
    """Extracts farm operational data from Odoo source database."""

    def __init__(self, source_conn_id: str = 'odoo_postgres'):
        self.source_hook = PostgresHook(postgres_conn_id=source_conn_id)

    def extract_animal_data(self, execution_date: datetime) -> pd.DataFrame:
        """Extract animal master data with changes since last sync."""
        query = """
            SELECT
                a.id AS animal_id,
                a.name AS animal_code,
                a.ear_tag_number,
                a.rfid_tag,
                a.display_name AS animal_name,
                b.name AS breed_name,
                a.birth_date,
                a.acquisition_date,
                a.acquisition_type,
                dam.name AS dam_code,
                sire.name AS sire_code,
                a.gender,
                a.state AS current_status,
                a.lactation_number,
                f.name AS farm_name,
                a.pen_location,
                a.write_date
            FROM farm_animal a
            LEFT JOIN farm_breed b ON a.breed_id = b.id
            LEFT JOIN farm_animal dam ON a.dam_id = dam.id
            LEFT JOIN farm_animal sire ON a.sire_id = sire.id
            LEFT JOIN farm_farm f ON a.farm_id = f.id
            WHERE a.write_date >= %(last_sync)s
               OR a.create_date >= %(last_sync)s
            ORDER BY a.id
        """
        last_sync = execution_date - timedelta(days=1)
        return self.source_hook.get_pandas_df(query, parameters={'last_sync': last_sync})

    def extract_milk_production(self, execution_date: datetime) -> pd.DataFrame:
        """Extract daily milk production records."""
        query = """
            SELECT
                mp.id,
                mp.production_date,
                a.name AS animal_code,
                f.name AS farm_name,
                mp.morning_yield,
                mp.morning_fat_pct,
                mp.morning_protein_pct,
                mp.morning_scc,
                mp.morning_duration_mins,
                mp.morning_time,
                mp.evening_yield,
                mp.evening_fat_pct,
                mp.evening_protein_pct,
                mp.evening_scc,
                mp.evening_duration_mins,
                mp.evening_time,
                mp.quality_grade,
                mp.rejection_reason,
                mp.days_in_milk,
                mp.data_source
            FROM farm_milk_production mp
            JOIN farm_animal a ON mp.animal_id = a.id
            JOIN farm_farm f ON mp.farm_id = f.id
            WHERE mp.production_date = %(target_date)s
            ORDER BY mp.id
        """
        target_date = execution_date.date()
        return self.source_hook.get_pandas_df(query, parameters={'target_date': target_date})

    def extract_feed_consumption(self, execution_date: datetime) -> pd.DataFrame:
        """Extract feed consumption records."""
        query = """
            SELECT
                fc.id,
                fc.consumption_date,
                a.name AS animal_code,
                f.name AS farm_name,
                ft.name AS feed_name,
                ft.category AS feed_category,
                fc.quantity_kg,
                fc.dry_matter_intake_kg,
                fc.cost_amount,
                fc.feeding_group_id,
                fc.animals_in_group,
                fc.feeding_session,
                fc.feeding_time,
                u.name AS recorded_by_name,
                fc.data_source
            FROM farm_feed_consumption fc
            JOIN farm_farm f ON fc.farm_id = f.id
            JOIN farm_feed_type ft ON fc.feed_type_id = ft.id
            LEFT JOIN farm_animal a ON fc.animal_id = a.id
            LEFT JOIN res_users u ON fc.recorded_by = u.id
            WHERE fc.consumption_date = %(target_date)s
            ORDER BY fc.id
        """
        target_date = execution_date.date()
        return self.source_hook.get_pandas_df(query, parameters={'target_date': target_date})

    def extract_health_events(self, execution_date: datetime) -> pd.DataFrame:
        """Extract animal health events."""
        query = """
            SELECT
                he.id,
                he.event_datetime,
                a.name AS animal_code,
                f.name AS farm_name,
                het.name AS event_type_name,
                het.category AS event_category,
                het.severity_level,
                he.diagnosis_code,
                he.diagnosis_description,
                he.symptoms,
                he.body_condition_score,
                he.temperature_celsius,
                he.treatment_given,
                he.medication_administered,
                he.dosage_amount,
                he.dosage_unit,
                he.withdrawal_period_days,
                he.milk_withdrawal_end_date,
                he.veterinarian_name,
                he.event_status,
                he.resolution_date,
                he.treatment_cost,
                he.veterinary_fee,
                he.medication_cost,
                he.estimated_milk_loss_liters,
                he.days_out_of_production
            FROM farm_health_event he
            JOIN farm_animal a ON he.animal_id = a.id
            JOIN farm_farm f ON he.farm_id = f.id
            JOIN farm_health_event_type het ON he.event_type_id = het.id
            WHERE DATE(he.event_datetime) = %(target_date)s
               OR (he.event_status = 'ongoing' AND he.write_date >= %(last_sync)s)
            ORDER BY he.id
        """
        target_date = execution_date.date()
        last_sync = execution_date - timedelta(days=1)
        return self.source_hook.get_pandas_df(
            query,
            parameters={'target_date': target_date, 'last_sync': last_sync}
        )

    def extract_breeding_events(self, execution_date: datetime) -> pd.DataFrame:
        """Extract breeding and reproduction events."""
        query = """
            SELECT
                be.id,
                be.event_date,
                a.name AS animal_code,
                f.name AS farm_name,
                be.heat_detected_datetime,
                be.heat_detection_method,
                be.heat_intensity_score,
                be.breeding_datetime,
                be.breeding_method,
                sire.name AS sire_code,
                be.semen_batch_number,
                be.straw_code,
                be.technician_name,
                be.pregnancy_check_date,
                be.pregnancy_check_method,
                be.pregnancy_confirmed,
                be.estimated_calving_date,
                be.breeding_outcome,
                be.days_to_conception,
                be.services_per_conception,
                be.semen_cost,
                be.technician_fee
            FROM farm_breeding_event be
            JOIN farm_animal a ON be.animal_id = a.id
            JOIN farm_farm f ON be.farm_id = f.id
            LEFT JOIN farm_animal sire ON be.sire_id = sire.id
            WHERE be.event_date = %(target_date)s
               OR (be.breeding_outcome = 'pending' AND be.write_date >= %(last_sync)s)
            ORDER BY be.id
        """
        target_date = execution_date.date()
        last_sync = execution_date - timedelta(days=1)
        return self.source_hook.get_pandas_df(
            query,
            parameters={'target_date': target_date, 'last_sync': last_sync}
        )


class FarmDataTransformer:
    """Transforms extracted farm data for analytics warehouse."""

    @staticmethod
    def calculate_lactation_metrics(milk_df: pd.DataFrame) -> pd.DataFrame:
        """Calculate lactation-specific metrics."""
        if milk_df.empty:
            return milk_df

        # Calculate total daily yield
        milk_df['total_daily_yield'] = (
            milk_df['morning_yield'].fillna(0) +
            milk_df['evening_yield'].fillna(0)
        )

        # Calculate weighted average fat/protein
        milk_df['avg_fat_pct'] = (
            (milk_df['morning_yield'].fillna(0) * milk_df['morning_fat_pct'].fillna(0) +
             milk_df['evening_yield'].fillna(0) * milk_df['evening_fat_pct'].fillna(0)) /
            milk_df['total_daily_yield'].replace(0, np.nan)
        )

        milk_df['avg_protein_pct'] = (
            (milk_df['morning_yield'].fillna(0) * milk_df['morning_protein_pct'].fillna(0) +
             milk_df['evening_yield'].fillna(0) * milk_df['evening_protein_pct'].fillna(0)) /
            milk_df['total_daily_yield'].replace(0, np.nan)
        )

        # Calculate average SCC
        milk_df['avg_scc'] = (
            (milk_df['morning_scc'].fillna(0) + milk_df['evening_scc'].fillna(0)) / 2
        ).astype(int)

        # Calculate lactation week
        milk_df['lactation_week'] = (milk_df['days_in_milk'] // 7) + 1

        return milk_df

    @staticmethod
    def calculate_feed_efficiency(
        milk_df: pd.DataFrame,
        feed_df: pd.DataFrame
    ) -> pd.DataFrame:
        """Calculate feed conversion ratios."""
        if milk_df.empty or feed_df.empty:
            return feed_df

        # Aggregate daily milk per animal
        daily_milk = milk_df.groupby(['production_date', 'animal_code']).agg({
            'total_daily_yield': 'sum'
        }).reset_index()

        # Aggregate daily feed per animal
        daily_feed = feed_df.groupby(['consumption_date', 'animal_code']).agg({
            'quantity_kg': 'sum',
            'dry_matter_intake_kg': 'sum'
        }).reset_index()

        # Merge and calculate FCR
        merged = daily_feed.merge(
            daily_milk,
            left_on=['consumption_date', 'animal_code'],
            right_on=['production_date', 'animal_code'],
            how='left'
        )

        merged['feed_conversion_ratio'] = (
            merged['dry_matter_intake_kg'] /
            merged['total_daily_yield'].replace(0, np.nan)
        )

        return merged

    @staticmethod
    def enrich_health_events(health_df: pd.DataFrame) -> pd.DataFrame:
        """Enrich health events with calculated fields."""
        if health_df.empty:
            return health_df

        # Calculate total event cost
        health_df['total_event_cost'] = (
            health_df['treatment_cost'].fillna(0) +
            health_df['veterinary_fee'].fillna(0) +
            health_df['medication_cost'].fillna(0)
        )

        # Calculate production impact value (estimated)
        avg_milk_price = 0.45  # Price per liter
        health_df['production_impact_value'] = (
            health_df['estimated_milk_loss_liters'].fillna(0) * avg_milk_price
        )

        return health_df

    @staticmethod
    def calculate_breeding_metrics(breeding_df: pd.DataFrame) -> pd.DataFrame:
        """Calculate breeding efficiency metrics."""
        if breeding_df.empty:
            return breeding_df

        # Calculate total breeding cost
        breeding_df['total_breeding_cost'] = (
            breeding_df['semen_cost'].fillna(0) +
            breeding_df['technician_fee'].fillna(0)
        )

        # Flag successful conceptions
        breeding_df['is_successful'] = (
            breeding_df['breeding_outcome'] == 'confirmed_pregnant'
        ).astype(int)

        return breeding_df


class FarmDataLoader:
    """Loads transformed farm data into analytics data warehouse."""

    def __init__(self, target_conn_id: str = 'analytics_dw'):
        self.target_hook = PostgresHook(postgres_conn_id=target_conn_id)

    def get_or_create_dimension_key(
        self,
        table: str,
        lookup_column: str,
        lookup_value: Any,
        sk_column: str = None
    ) -> int:
        """Get surrogate key for dimension lookup, creating if necessary."""
        if sk_column is None:
            sk_column = f"{table.replace('dim_', '')}_sk"

        query = f"""
            SELECT {sk_column} FROM {table}
            WHERE {lookup_column} = %s AND is_current = TRUE
            LIMIT 1
        """
        result = self.target_hook.get_first(query, parameters=[lookup_value])

        if result:
            return result[0]

        # Return -1 for unknown dimension members
        logger.warning(f"Dimension lookup failed: {table}.{lookup_column} = {lookup_value}")
        return -1

    def load_milk_production(self, df: pd.DataFrame) -> int:
        """Load milk production facts."""
        if df.empty:
            return 0

        rows_loaded = 0
        for _, row in df.iterrows():
            try:
                # Resolve dimension keys
                date_sk = self.get_or_create_dimension_key(
                    'dim_date', 'date_actual', row['production_date'], 'date_sk'
                )
                animal_sk = self.get_or_create_dimension_key(
                    'dim_animal', 'animal_code', row['animal_code']
                )
                farm_sk = self.get_or_create_dimension_key(
                    'dim_farm', 'farm_name', row['farm_name']
                )

                if -1 in [date_sk, animal_sk, farm_sk]:
                    logger.warning(f"Skipping milk record - missing dimension: {row['animal_code']}")
                    continue

                # Upsert fact record
                upsert_query = """
                    INSERT INTO fact_daily_milk_production (
                        date_sk, animal_sk, farm_sk,
                        morning_yield_liters, morning_fat_percentage, morning_protein_percentage,
                        morning_somatic_cell_count, morning_milking_duration_mins, morning_milking_time,
                        evening_yield_liters, evening_fat_percentage, evening_protein_percentage,
                        evening_somatic_cell_count, evening_milking_duration_mins, evening_milking_time,
                        avg_fat_percentage, avg_protein_percentage, avg_somatic_cell_count,
                        milk_quality_grade, rejection_reason, days_in_milk, lactation_week,
                        data_source
                    ) VALUES (
                        %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
                    )
                    ON CONFLICT (date_sk, animal_sk) DO UPDATE SET
                        morning_yield_liters = EXCLUDED.morning_yield_liters,
                        evening_yield_liters = EXCLUDED.evening_yield_liters,
                        avg_fat_percentage = EXCLUDED.avg_fat_percentage,
                        avg_protein_percentage = EXCLUDED.avg_protein_percentage
                """

                self.target_hook.run(upsert_query, parameters=[
                    date_sk, animal_sk, farm_sk,
                    row.get('morning_yield'), row.get('morning_fat_pct'), row.get('morning_protein_pct'),
                    row.get('morning_scc'), row.get('morning_duration_mins'), row.get('morning_time'),
                    row.get('evening_yield'), row.get('evening_fat_pct'), row.get('evening_protein_pct'),
                    row.get('evening_scc'), row.get('evening_duration_mins'), row.get('evening_time'),
                    row.get('avg_fat_pct'), row.get('avg_protein_pct'), row.get('avg_scc'),
                    row.get('quality_grade'), row.get('rejection_reason'),
                    row.get('days_in_milk'), row.get('lactation_week'),
                    row.get('data_source')
                ])
                rows_loaded += 1

            except Exception as e:
                logger.error(f"Error loading milk production: {e}")
                continue

        return rows_loaded


# =============================================================================
# AIRFLOW TASK DEFINITIONS
# =============================================================================

def extract_farm_data(**context):
    """Extract all farm operational data."""
    execution_date = context['execution_date']
    extractor = FarmDataExtractor()

    data = {
        'animals': extractor.extract_animal_data(execution_date),
        'milk_production': extractor.extract_milk_production(execution_date),
        'feed_consumption': extractor.extract_feed_consumption(execution_date),
        'health_events': extractor.extract_health_events(execution_date),
        'breeding_events': extractor.extract_breeding_events(execution_date)
    }

    # Store in XCom
    for key, df in data.items():
        context['ti'].xcom_push(key=key, value=df.to_json())
        logger.info(f"Extracted {len(df)} {key} records")

    return {'status': 'success', 'records': {k: len(v) for k, v in data.items()}}


def transform_farm_data(**context):
    """Transform extracted farm data."""
    ti = context['ti']
    transformer = FarmDataTransformer()

    # Retrieve from XCom
    milk_df = pd.read_json(ti.xcom_pull(key='milk_production'))
    feed_df = pd.read_json(ti.xcom_pull(key='feed_consumption'))
    health_df = pd.read_json(ti.xcom_pull(key='health_events'))
    breeding_df = pd.read_json(ti.xcom_pull(key='breeding_events'))

    # Apply transformations
    milk_df = transformer.calculate_lactation_metrics(milk_df)
    feed_df = transformer.calculate_feed_efficiency(milk_df, feed_df)
    health_df = transformer.enrich_health_events(health_df)
    breeding_df = transformer.calculate_breeding_metrics(breeding_df)

    # Store transformed data
    ti.xcom_push(key='transformed_milk', value=milk_df.to_json())
    ti.xcom_push(key='transformed_feed', value=feed_df.to_json())
    ti.xcom_push(key='transformed_health', value=health_df.to_json())
    ti.xcom_push(key='transformed_breeding', value=breeding_df.to_json())

    return {'status': 'success'}


def load_farm_data(**context):
    """Load transformed data into data warehouse."""
    ti = context['ti']
    loader = FarmDataLoader()

    # Load each dataset
    milk_df = pd.read_json(ti.xcom_pull(key='transformed_milk'))
    milk_loaded = loader.load_milk_production(milk_df)

    logger.info(f"Loaded {milk_loaded} milk production records")

    return {'status': 'success', 'milk_loaded': milk_loaded}


def calculate_daily_aggregates(**context):
    """Calculate daily farm-level aggregates."""
    execution_date = context['execution_date']
    target_hook = PostgresHook(postgres_conn_id='analytics_dw')

    aggregate_query = """
        INSERT INTO fact_daily_farm_aggregate (
            date_sk, farm_sk,
            total_animals, milking_animals, dry_animals,
            total_milk_yield_liters, avg_yield_per_animal,
            avg_fat_percentage, avg_protein_percentage, avg_somatic_cell_count,
            total_feed_consumed_kg, total_feed_cost, herd_feed_conversion_ratio,
            health_events_count, animals_under_treatment
        )
        SELECT
            d.date_sk,
            f.farm_sk,
            COUNT(DISTINCT a.animal_sk) AS total_animals,
            COUNT(DISTINCT CASE WHEN a.current_status = 'active' THEN a.animal_sk END) AS milking_animals,
            COUNT(DISTINCT CASE WHEN a.current_status = 'dry' THEN a.animal_sk END) AS dry_animals,
            COALESCE(SUM(mp.total_daily_yield_liters), 0) AS total_milk_yield,
            COALESCE(AVG(mp.total_daily_yield_liters), 0) AS avg_yield_per_animal,
            AVG(mp.avg_fat_percentage) AS avg_fat_pct,
            AVG(mp.avg_protein_percentage) AS avg_protein_pct,
            AVG(mp.avg_somatic_cell_count)::INTEGER AS avg_scc,
            COALESCE(SUM(fc.quantity_kg), 0) AS total_feed,
            COALESCE(SUM(fc.cost_amount), 0) AS total_feed_cost,
            CASE WHEN SUM(mp.total_daily_yield_liters) > 0
                 THEN SUM(fc.quantity_kg) / SUM(mp.total_daily_yield_liters)
                 ELSE NULL END AS fcr,
            COUNT(DISTINCT he.health_event_sk) AS health_events,
            COUNT(DISTINCT CASE WHEN he.event_status = 'ongoing' THEN he.animal_sk END) AS under_treatment
        FROM dim_date d
        CROSS JOIN dim_farm f
        LEFT JOIN dim_animal a ON a.current_farm_sk = f.farm_sk AND a.is_current = TRUE
        LEFT JOIN fact_daily_milk_production mp ON mp.date_sk = d.date_sk AND mp.animal_sk = a.animal_sk
        LEFT JOIN fact_feed_consumption fc ON fc.date_sk = d.date_sk AND fc.farm_sk = f.farm_sk
        LEFT JOIN fact_animal_health_event he ON he.event_date_sk = d.date_sk AND he.farm_sk = f.farm_sk
        WHERE d.date_actual = %(target_date)s
        GROUP BY d.date_sk, f.farm_sk
        ON CONFLICT (date_sk, farm_sk) DO UPDATE SET
            total_milk_yield_liters = EXCLUDED.total_milk_yield_liters,
            avg_yield_per_animal = EXCLUDED.avg_yield_per_animal,
            total_feed_consumed_kg = EXCLUDED.total_feed_consumed_kg
    """

    target_hook.run(aggregate_query, parameters={'target_date': execution_date.date()})
    logger.info("Daily farm aggregates calculated successfully")


# Task definitions
with dag:

    extract_task = PythonOperator(
        task_id='extract_farm_data',
        python_callable=extract_farm_data,
    )

    transform_task = PythonOperator(
        task_id='transform_farm_data',
        python_callable=transform_farm_data,
    )

    load_task = PythonOperator(
        task_id='load_farm_data',
        python_callable=load_farm_data,
    )

    aggregate_task = PythonOperator(
        task_id='calculate_daily_aggregates',
        python_callable=calculate_daily_aggregates,
    )

    # Task dependencies
    extract_task >> transform_task >> load_task >> aggregate_task
```

---

## 4. Analytics Dashboards and Visualizations

### 4.1 Herd Performance Dashboard

```sql
-- =====================================================
-- HERD PERFORMANCE ANALYTICS QUERIES
-- Apache Superset Dashboard Data Sources
-- =====================================================

-- 4.1.1 Herd Production Overview KPI
CREATE OR REPLACE VIEW vw_herd_production_kpi AS
SELECT
    f.farm_name,
    d.date_actual,
    d.week_of_year,
    d.month_name,
    fa.total_animals,
    fa.milking_animals,
    fa.total_milk_yield_liters,
    fa.avg_yield_per_animal,
    fa.avg_fat_percentage,
    fa.avg_protein_percentage,
    fa.avg_somatic_cell_count,
    -- Period comparisons
    LAG(fa.total_milk_yield_liters, 7) OVER (
        PARTITION BY f.farm_sk ORDER BY d.date_actual
    ) AS yield_7_days_ago,
    LAG(fa.total_milk_yield_liters, 30) OVER (
        PARTITION BY f.farm_sk ORDER BY d.date_actual
    ) AS yield_30_days_ago,
    -- YoY comparison
    LAG(fa.total_milk_yield_liters, 365) OVER (
        PARTITION BY f.farm_sk ORDER BY d.date_actual
    ) AS yield_1_year_ago,
    -- Yield change percentages
    ROUND(
        ((fa.total_milk_yield_liters - LAG(fa.total_milk_yield_liters, 7) OVER (
            PARTITION BY f.farm_sk ORDER BY d.date_actual
        )) / NULLIF(LAG(fa.total_milk_yield_liters, 7) OVER (
            PARTITION BY f.farm_sk ORDER BY d.date_actual
        ), 0)) * 100, 2
    ) AS wow_yield_change_pct,
    -- Quality score (composite metric)
    ROUND(
        (100 - (fa.avg_somatic_cell_count::DECIMAL / 4000) * 100) * 0.4 +
        (fa.avg_fat_percentage / 4.5) * 100 * 0.3 +
        (fa.avg_protein_percentage / 3.5) * 100 * 0.3,
        1
    ) AS milk_quality_score
FROM fact_daily_farm_aggregate fa
JOIN dim_farm f ON fa.farm_sk = f.farm_sk
JOIN dim_date d ON fa.date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '365 days';

-- 4.1.2 Individual Animal Performance Ranking
CREATE OR REPLACE VIEW vw_animal_performance_ranking AS
WITH animal_30day_stats AS (
    SELECT
        a.animal_sk,
        a.animal_code,
        a.animal_name,
        b.breed_name,
        l.lactation_number,
        l.days_in_milk AS current_dim,
        f.farm_name,
        AVG(mp.total_daily_yield_liters) AS avg_daily_yield,
        SUM(mp.total_daily_yield_liters) AS total_30day_yield,
        AVG(mp.avg_fat_percentage) AS avg_fat,
        AVG(mp.avg_protein_percentage) AS avg_protein,
        AVG(mp.avg_somatic_cell_count) AS avg_scc,
        COUNT(*) AS milking_days,
        MAX(mp.total_daily_yield_liters) AS peak_daily_yield,
        STDDEV(mp.total_daily_yield_liters) AS yield_consistency
    FROM fact_daily_milk_production mp
    JOIN dim_animal a ON mp.animal_sk = a.animal_sk
    JOIN dim_farm f ON mp.farm_sk = f.farm_sk
    LEFT JOIN dim_breed b ON a.breed_id = b.breed_sk
    LEFT JOIN dim_lactation l ON mp.lactation_sk = l.lactation_sk
    JOIN dim_date d ON mp.date_sk = d.date_sk
    WHERE d.date_actual >= CURRENT_DATE - INTERVAL '30 days'
      AND a.current_status = 'active'
    GROUP BY a.animal_sk, a.animal_code, a.animal_name, b.breed_name,
             l.lactation_number, l.days_in_milk, f.farm_name
)
SELECT
    *,
    -- Performance ranking within farm
    RANK() OVER (PARTITION BY farm_name ORDER BY avg_daily_yield DESC) AS farm_yield_rank,
    -- Performance ranking within breed
    RANK() OVER (PARTITION BY breed_name ORDER BY avg_daily_yield DESC) AS breed_yield_rank,
    -- Composite performance score
    ROUND(
        (avg_daily_yield / NULLIF(MAX(avg_daily_yield) OVER (), 0)) * 40 +
        (1 - (avg_scc / 400000.0)) * 30 +
        (avg_fat / 5.0) * 15 +
        (avg_protein / 4.0) * 15,
        2
    ) AS performance_score
FROM animal_30day_stats
ORDER BY performance_score DESC;

-- 4.1.3 Lactation Curve Analysis
CREATE OR REPLACE VIEW vw_lactation_curve_analysis AS
SELECT
    a.animal_code,
    b.breed_name,
    l.lactation_number,
    mp.days_in_milk,
    mp.lactation_week,
    mp.total_daily_yield_liters AS daily_yield,
    AVG(mp.total_daily_yield_liters) OVER (
        PARTITION BY a.animal_sk, l.lactation_sk
        ORDER BY mp.days_in_milk
        ROWS BETWEEN 3 PRECEDING AND 3 FOLLOWING
    ) AS smoothed_yield,
    -- Standard lactation curve benchmark (Wood's model approximation)
    CASE
        WHEN mp.days_in_milk <= 60 THEN 25 * POWER(mp.days_in_milk::DECIMAL / 60, 0.2) * EXP(-0.003 * mp.days_in_milk)
        ELSE 28 * EXP(-0.004 * (mp.days_in_milk - 60))
    END AS benchmark_yield,
    -- Deviation from benchmark
    mp.total_daily_yield_liters - (
        CASE
            WHEN mp.days_in_milk <= 60 THEN 25 * POWER(mp.days_in_milk::DECIMAL / 60, 0.2) * EXP(-0.003 * mp.days_in_milk)
            ELSE 28 * EXP(-0.004 * (mp.days_in_milk - 60))
        END
    ) AS benchmark_deviation
FROM fact_daily_milk_production mp
JOIN dim_animal a ON mp.animal_sk = a.animal_sk
JOIN dim_lactation l ON mp.lactation_sk = l.lactation_sk
LEFT JOIN dim_breed b ON a.breed_id = b.breed_sk
WHERE l.lactation_status = 'active'
ORDER BY a.animal_code, mp.days_in_milk;

-- 4.1.4 Breed Performance Comparison
CREATE OR REPLACE VIEW vw_breed_performance_comparison AS
SELECT
    b.breed_name,
    COUNT(DISTINCT a.animal_sk) AS animal_count,
    ROUND(AVG(mp.total_daily_yield_liters), 2) AS avg_daily_yield,
    ROUND(STDDEV(mp.total_daily_yield_liters), 2) AS yield_std_dev,
    ROUND(AVG(mp.avg_fat_percentage), 2) AS avg_fat_pct,
    ROUND(AVG(mp.avg_protein_percentage), 2) AS avg_protein_pct,
    ROUND(AVG(mp.avg_somatic_cell_count), 0) AS avg_scc,
    ROUND(AVG(CASE WHEN l.lactation_number = 1 THEN mp.total_daily_yield_liters END), 2) AS first_lact_avg,
    ROUND(AVG(CASE WHEN l.lactation_number = 2 THEN mp.total_daily_yield_liters END), 2) AS second_lact_avg,
    ROUND(AVG(CASE WHEN l.lactation_number >= 3 THEN mp.total_daily_yield_liters END), 2) AS mature_lact_avg,
    -- Breed benchmark comparison
    b.avg_milk_yield_liters AS breed_benchmark,
    ROUND(AVG(mp.total_daily_yield_liters) - b.avg_milk_yield_liters, 2) AS vs_benchmark
FROM fact_daily_milk_production mp
JOIN dim_animal a ON mp.animal_sk = a.animal_sk
JOIN dim_breed b ON a.breed_id = b.breed_sk
LEFT JOIN dim_lactation l ON mp.lactation_sk = l.lactation_sk
JOIN dim_date d ON mp.date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '90 days'
GROUP BY b.breed_sk, b.breed_name, b.avg_milk_yield_liters
ORDER BY avg_daily_yield DESC;
```

### 4.2 Feed Efficiency Analytics

```sql
-- =====================================================
-- FEED EFFICIENCY ANALYTICS
-- Cost Per Liter & Feed Conversion Analysis
-- =====================================================

-- 4.2.1 Daily Cost Per Liter Analysis
CREATE OR REPLACE VIEW vw_daily_cost_per_liter AS
WITH daily_costs AS (
    SELECT
        d.date_sk,
        d.date_actual,
        f.farm_sk,
        f.farm_name,
        -- Feed costs
        COALESCE(SUM(fc.cost_amount), 0) AS feed_cost,
        COALESCE(SUM(fc.quantity_kg), 0) AS feed_quantity_kg,
        -- Labor costs (estimated based on animal count)
        COUNT(DISTINCT a.animal_sk) * 2.50 AS daily_labor_cost, -- $2.50 per animal per day
        -- Overhead allocation
        COUNT(DISTINCT a.animal_sk) * 1.00 AS daily_overhead_cost
    FROM dim_date d
    CROSS JOIN dim_farm f
    LEFT JOIN dim_animal a ON a.current_farm_sk = f.farm_sk AND a.is_current = TRUE
    LEFT JOIN fact_feed_consumption fc ON fc.date_sk = d.date_sk AND fc.farm_sk = f.farm_sk
    WHERE d.date_actual >= CURRENT_DATE - INTERVAL '90 days'
    GROUP BY d.date_sk, d.date_actual, f.farm_sk, f.farm_name
),
daily_production AS (
    SELECT
        mp.date_sk,
        mp.farm_sk,
        SUM(mp.total_daily_yield_liters) AS total_yield,
        COUNT(DISTINCT mp.animal_sk) AS milking_animals
    FROM fact_daily_milk_production mp
    GROUP BY mp.date_sk, mp.farm_sk
)
SELECT
    dc.date_actual,
    dc.farm_name,
    dc.feed_cost,
    dc.daily_labor_cost,
    dc.daily_overhead_cost,
    dc.feed_cost + dc.daily_labor_cost + dc.daily_overhead_cost AS total_daily_cost,
    COALESCE(dp.total_yield, 0) AS total_yield_liters,
    CASE
        WHEN dp.total_yield > 0
        THEN ROUND((dc.feed_cost + dc.daily_labor_cost + dc.daily_overhead_cost) / dp.total_yield, 4)
        ELSE NULL
    END AS cost_per_liter,
    CASE
        WHEN dp.total_yield > 0
        THEN ROUND(dc.feed_cost / dp.total_yield, 4)
        ELSE NULL
    END AS feed_cost_per_liter,
    -- Rolling averages
    AVG(CASE WHEN dp.total_yield > 0
        THEN (dc.feed_cost + dc.daily_labor_cost + dc.daily_overhead_cost) / dp.total_yield
        ELSE NULL END) OVER (
        PARTITION BY dc.farm_sk
        ORDER BY dc.date_actual
        ROWS BETWEEN 6 PRECEDING AND CURRENT ROW
    ) AS rolling_7day_cpl
FROM daily_costs dc
LEFT JOIN daily_production dp ON dc.date_sk = dp.date_sk AND dc.farm_sk = dp.farm_sk
ORDER BY dc.date_actual DESC, dc.farm_name;

-- 4.2.2 Feed Conversion Ratio by Animal
CREATE OR REPLACE VIEW vw_feed_conversion_by_animal AS
WITH animal_fcr AS (
    SELECT
        a.animal_sk,
        a.animal_code,
        a.animal_name,
        b.breed_name,
        f.farm_name,
        d.week_of_year,
        d.year_actual,
        SUM(fc.quantity_kg) AS weekly_feed_kg,
        SUM(fc.dry_matter_intake_kg) AS weekly_dmi_kg,
        SUM(mp.total_daily_yield_liters) AS weekly_milk_liters,
        CASE
            WHEN SUM(mp.total_daily_yield_liters) > 0
            THEN ROUND(SUM(fc.quantity_kg) / SUM(mp.total_daily_yield_liters), 3)
            ELSE NULL
        END AS fcr_kg_per_liter,
        CASE
            WHEN SUM(mp.total_daily_yield_liters) > 0
            THEN ROUND(SUM(fc.dry_matter_intake_kg) / SUM(mp.total_daily_yield_liters), 3)
            ELSE NULL
        END AS dmi_fcr
    FROM fact_feed_consumption fc
    JOIN dim_animal a ON fc.animal_sk = a.animal_sk
    JOIN dim_farm f ON fc.farm_sk = f.farm_sk
    LEFT JOIN dim_breed b ON a.breed_id = b.breed_sk
    JOIN dim_date d ON fc.date_sk = d.date_sk
    LEFT JOIN fact_daily_milk_production mp ON mp.animal_sk = a.animal_sk AND mp.date_sk = fc.date_sk
    WHERE d.date_actual >= CURRENT_DATE - INTERVAL '12 weeks'
    GROUP BY a.animal_sk, a.animal_code, a.animal_name, b.breed_name, f.farm_name,
             d.week_of_year, d.year_actual
)
SELECT
    *,
    -- FCR ranking within farm (lower is better)
    RANK() OVER (PARTITION BY farm_name, week_of_year ORDER BY fcr_kg_per_liter ASC) AS farm_fcr_rank,
    -- FCR efficiency category
    CASE
        WHEN fcr_kg_per_liter < 0.8 THEN 'Excellent'
        WHEN fcr_kg_per_liter < 1.0 THEN 'Good'
        WHEN fcr_kg_per_liter < 1.2 THEN 'Average'
        WHEN fcr_kg_per_liter < 1.5 THEN 'Below Average'
        ELSE 'Poor'
    END AS fcr_category
FROM animal_fcr
WHERE fcr_kg_per_liter IS NOT NULL
ORDER BY year_actual DESC, week_of_year DESC, fcr_kg_per_liter ASC;

-- 4.2.3 Feed Type Efficiency Analysis
CREATE OR REPLACE VIEW vw_feed_type_efficiency AS
SELECT
    ft.feed_name,
    ft.feed_category,
    COUNT(DISTINCT fc.animal_sk) AS animals_fed,
    SUM(fc.quantity_kg) AS total_quantity_kg,
    SUM(fc.cost_amount) AS total_cost,
    ROUND(AVG(fc.cost_amount / NULLIF(fc.quantity_kg, 0)), 2) AS avg_cost_per_kg,
    -- Correlation with milk yield
    CORR(fc.quantity_kg, mp.total_daily_yield_liters) AS yield_correlation,
    -- Average yield for animals receiving this feed
    ROUND(AVG(mp.total_daily_yield_liters), 2) AS avg_yield_liters,
    -- Cost efficiency (milk value per feed cost)
    ROUND(AVG(mp.total_daily_yield_liters * 0.45) / NULLIF(AVG(fc.cost_amount), 0), 2) AS milk_value_per_feed_cost
FROM fact_feed_consumption fc
JOIN dim_feed_type ft ON fc.feed_type_sk = ft.feed_type_sk
JOIN dim_date d ON fc.date_sk = d.date_sk
LEFT JOIN fact_daily_milk_production mp ON mp.animal_sk = fc.animal_sk AND mp.date_sk = fc.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '30 days'
  AND fc.animal_sk IS NOT NULL
GROUP BY ft.feed_type_sk, ft.feed_name, ft.feed_category
HAVING COUNT(*) >= 50  -- Minimum sample size
ORDER BY milk_value_per_feed_cost DESC;
```

### 4.3 Animal Health Analytics

```sql
-- =====================================================
-- ANIMAL HEALTH TREND ANALYTICS
-- Disease Prevention & Treatment Effectiveness
-- =====================================================

-- 4.3.1 Health Event Summary Dashboard
CREATE OR REPLACE VIEW vw_health_event_summary AS
SELECT
    f.farm_name,
    het.event_name,
    het.event_category,
    het.severity_level,
    d.month_name,
    d.year_actual,
    COUNT(*) AS event_count,
    COUNT(DISTINCT he.animal_sk) AS animals_affected,
    SUM(he.total_event_cost) AS total_cost,
    AVG(he.total_event_cost) AS avg_cost_per_event,
    SUM(he.estimated_milk_loss_liters) AS total_milk_loss,
    AVG(he.days_out_of_production) AS avg_days_lost,
    -- Resolution metrics
    COUNT(CASE WHEN he.event_status = 'resolved' THEN 1 END) AS resolved_count,
    ROUND(COUNT(CASE WHEN he.event_status = 'resolved' THEN 1 END)::DECIMAL / COUNT(*) * 100, 1) AS resolution_rate,
    AVG(CASE WHEN he.resolution_date IS NOT NULL
        THEN he.resolution_date - DATE(he.event_datetime) END) AS avg_resolution_days
FROM fact_animal_health_event he
JOIN dim_farm f ON he.farm_sk = f.farm_sk
JOIN dim_health_event_type het ON he.health_event_type_sk = het.health_event_type_sk
JOIN dim_date d ON he.event_date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '12 months'
GROUP BY f.farm_name, het.event_name, het.event_category, het.severity_level,
         d.month_name, d.year_actual, d.month_of_year
ORDER BY d.year_actual DESC, d.month_of_year DESC, event_count DESC;

-- 4.3.2 Disease Outbreak Early Warning
CREATE OR REPLACE VIEW vw_disease_outbreak_warning AS
WITH daily_health_events AS (
    SELECT
        d.date_actual,
        f.farm_sk,
        f.farm_name,
        het.event_name,
        het.is_contagious,
        COUNT(*) AS daily_cases,
        COUNT(DISTINCT he.animal_sk) AS animals_affected
    FROM fact_animal_health_event he
    JOIN dim_farm f ON he.farm_sk = f.farm_sk
    JOIN dim_health_event_type het ON he.health_event_type_sk = het.health_event_type_sk
    JOIN dim_date d ON he.event_date_sk = d.date_sk
    WHERE het.event_category = 'disease'
      AND d.date_actual >= CURRENT_DATE - INTERVAL '30 days'
    GROUP BY d.date_actual, f.farm_sk, f.farm_name, het.event_name, het.is_contagious
),
baseline_rates AS (
    SELECT
        f.farm_sk,
        het.event_name,
        AVG(daily_count) AS baseline_daily_rate,
        STDDEV(daily_count) AS rate_std_dev
    FROM (
        SELECT
            he.farm_sk,
            het.event_name,
            d.date_actual,
            COUNT(*) AS daily_count
        FROM fact_animal_health_event he
        JOIN dim_health_event_type het ON he.health_event_type_sk = het.health_event_type_sk
        JOIN dim_date d ON he.event_date_sk = d.date_sk
        WHERE het.event_category = 'disease'
          AND d.date_actual BETWEEN CURRENT_DATE - INTERVAL '90 days' AND CURRENT_DATE - INTERVAL '30 days'
        GROUP BY he.farm_sk, het.event_name, d.date_actual
    ) historical
    JOIN dim_farm f ON historical.farm_sk = f.farm_sk
    GROUP BY f.farm_sk, het.event_name
)
SELECT
    dhe.date_actual,
    dhe.farm_name,
    dhe.event_name,
    dhe.is_contagious,
    dhe.daily_cases,
    dhe.animals_affected,
    ROUND(br.baseline_daily_rate, 2) AS baseline_rate,
    ROUND(br.rate_std_dev, 2) AS std_dev,
    -- Z-score for anomaly detection
    ROUND((dhe.daily_cases - br.baseline_daily_rate) / NULLIF(br.rate_std_dev, 0), 2) AS z_score,
    -- Alert level
    CASE
        WHEN (dhe.daily_cases - br.baseline_daily_rate) / NULLIF(br.rate_std_dev, 0) > 3 THEN 'CRITICAL'
        WHEN (dhe.daily_cases - br.baseline_daily_rate) / NULLIF(br.rate_std_dev, 0) > 2 THEN 'WARNING'
        WHEN (dhe.daily_cases - br.baseline_daily_rate) / NULLIF(br.rate_std_dev, 0) > 1.5 THEN 'WATCH'
        ELSE 'NORMAL'
    END AS alert_level
FROM daily_health_events dhe
LEFT JOIN baseline_rates br ON dhe.farm_sk = br.farm_sk AND dhe.event_name = br.event_name
WHERE dhe.date_actual >= CURRENT_DATE - INTERVAL '7 days'
ORDER BY
    CASE
        WHEN (dhe.daily_cases - br.baseline_daily_rate) / NULLIF(br.rate_std_dev, 0) > 3 THEN 1
        WHEN (dhe.daily_cases - br.baseline_daily_rate) / NULLIF(br.rate_std_dev, 0) > 2 THEN 2
        ELSE 3
    END,
    dhe.date_actual DESC;

-- 4.3.3 Somatic Cell Count Trend Analysis
CREATE OR REPLACE VIEW vw_scc_trend_analysis AS
SELECT
    a.animal_code,
    a.animal_name,
    f.farm_name,
    d.date_actual,
    mp.avg_somatic_cell_count AS daily_scc,
    -- Rolling average SCC
    AVG(mp.avg_somatic_cell_count) OVER (
        PARTITION BY a.animal_sk
        ORDER BY d.date_actual
        ROWS BETWEEN 6 PRECEDING AND CURRENT ROW
    ) AS rolling_7day_scc,
    -- SCC trend direction
    mp.avg_somatic_cell_count - LAG(mp.avg_somatic_cell_count, 7) OVER (
        PARTITION BY a.animal_sk ORDER BY d.date_actual
    ) AS scc_7day_change,
    -- SCC category (EU standards)
    CASE
        WHEN mp.avg_somatic_cell_count < 200000 THEN 'Excellent'
        WHEN mp.avg_somatic_cell_count < 300000 THEN 'Good'
        WHEN mp.avg_somatic_cell_count < 400000 THEN 'Acceptable'
        WHEN mp.avg_somatic_cell_count < 500000 THEN 'High - Monitor'
        ELSE 'Critical - Treatment Required'
    END AS scc_category,
    -- Mastitis risk score
    CASE
        WHEN mp.avg_somatic_cell_count > 500000 THEN 5
        WHEN mp.avg_somatic_cell_count > 400000 AND
             LAG(mp.avg_somatic_cell_count, 1) OVER (PARTITION BY a.animal_sk ORDER BY d.date_actual) > 400000 THEN 4
        WHEN mp.avg_somatic_cell_count > 300000 THEN 3
        WHEN mp.avg_somatic_cell_count > 200000 THEN 2
        ELSE 1
    END AS mastitis_risk_score
FROM fact_daily_milk_production mp
JOIN dim_animal a ON mp.animal_sk = a.animal_sk
JOIN dim_farm f ON mp.farm_sk = f.farm_sk
JOIN dim_date d ON mp.date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '60 days'
  AND a.current_status = 'active'
ORDER BY a.animal_code, d.date_actual;
```

### 4.4 Breeding Analytics

```sql
-- =====================================================
-- BREEDING & REPRODUCTION ANALYTICS
-- Fertility Management & Genetic Progress
-- =====================================================

-- 4.4.1 Conception Rate Dashboard
CREATE OR REPLACE VIEW vw_conception_rate_dashboard AS
SELECT
    f.farm_name,
    d.month_name,
    d.year_actual,
    COUNT(*) AS total_breedings,
    COUNT(CASE WHEN be.pregnancy_confirmed = TRUE THEN 1 END) AS confirmed_pregnancies,
    ROUND(
        COUNT(CASE WHEN be.pregnancy_confirmed = TRUE THEN 1 END)::DECIMAL /
        NULLIF(COUNT(*), 0) * 100,
        1
    ) AS conception_rate_pct,
    -- By breeding method
    ROUND(
        COUNT(CASE WHEN be.breeding_method = 'AI' AND be.pregnancy_confirmed = TRUE THEN 1 END)::DECIMAL /
        NULLIF(COUNT(CASE WHEN be.breeding_method = 'AI' THEN 1 END), 0) * 100,
        1
    ) AS ai_conception_rate,
    ROUND(
        COUNT(CASE WHEN be.breeding_method = 'natural' AND be.pregnancy_confirmed = TRUE THEN 1 END)::DECIMAL /
        NULLIF(COUNT(CASE WHEN be.breeding_method = 'natural' THEN 1 END), 0) * 100,
        1
    ) AS natural_conception_rate,
    -- Cost metrics
    SUM(be.total_breeding_cost) AS total_breeding_cost,
    ROUND(
        SUM(be.total_breeding_cost) /
        NULLIF(COUNT(CASE WHEN be.pregnancy_confirmed = TRUE THEN 1 END), 0),
        2
    ) AS cost_per_pregnancy,
    -- Services per conception
    ROUND(AVG(be.services_per_conception) FILTER (WHERE be.pregnancy_confirmed = TRUE), 2) AS avg_services_per_conception
FROM fact_breeding_event be
JOIN dim_farm f ON be.farm_sk = f.farm_sk
JOIN dim_date d ON be.event_date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '12 months'
  AND be.breeding_outcome != 'pending'
GROUP BY f.farm_name, d.month_name, d.year_actual, d.month_of_year
ORDER BY d.year_actual DESC, d.month_of_year DESC;

-- 4.4.2 Calving Interval Analysis
CREATE OR REPLACE VIEW vw_calving_interval_analysis AS
WITH calving_history AS (
    SELECT
        a.animal_sk,
        a.animal_code,
        a.animal_name,
        b.breed_name,
        f.farm_name,
        l.lactation_number,
        l.calving_date,
        LAG(l.calving_date) OVER (PARTITION BY a.animal_sk ORDER BY l.lactation_number) AS previous_calving_date,
        l.calving_date - LAG(l.calving_date) OVER (PARTITION BY a.animal_sk ORDER BY l.lactation_number) AS calving_interval_days
    FROM dim_lactation l
    JOIN dim_animal a ON l.animal_sk = a.animal_sk
    JOIN dim_farm f ON a.current_farm_sk = f.farm_sk
    LEFT JOIN dim_breed b ON a.breed_id = b.breed_sk
    WHERE l.lactation_status IN ('active', 'completed')
)
SELECT
    farm_name,
    breed_name,
    COUNT(DISTINCT animal_sk) AS animals_analyzed,
    ROUND(AVG(calving_interval_days), 0) AS avg_calving_interval,
    MIN(calving_interval_days) AS min_interval,
    MAX(calving_interval_days) AS max_interval,
    ROUND(STDDEV(calving_interval_days), 1) AS interval_std_dev,
    -- Target analysis (365-400 days optimal)
    COUNT(CASE WHEN calving_interval_days BETWEEN 365 AND 400 THEN 1 END) AS optimal_intervals,
    ROUND(
        COUNT(CASE WHEN calving_interval_days BETWEEN 365 AND 400 THEN 1 END)::DECIMAL /
        NULLIF(COUNT(*), 0) * 100,
        1
    ) AS optimal_interval_pct,
    COUNT(CASE WHEN calving_interval_days > 450 THEN 1 END) AS extended_intervals
FROM calving_history
WHERE calving_interval_days IS NOT NULL
GROUP BY farm_name, breed_name
ORDER BY avg_calving_interval;

-- 4.4.3 Sire Performance Analysis
CREATE OR REPLACE VIEW vw_sire_performance_analysis AS
SELECT
    COALESCE(be.sire_code, 'Unknown') AS sire_code,
    be.sire_breed,
    COUNT(*) AS total_breedings,
    COUNT(CASE WHEN be.pregnancy_confirmed = TRUE THEN 1 END) AS successful_breedings,
    ROUND(
        COUNT(CASE WHEN be.pregnancy_confirmed = TRUE THEN 1 END)::DECIMAL /
        NULLIF(COUNT(*), 0) * 100,
        1
    ) AS conception_rate,
    -- Offspring metrics (from linked calves)
    COUNT(DISTINCT l.calf_animal_sk) AS calves_born,
    AVG(l.calf_birth_weight_kg) AS avg_calf_birth_weight,
    AVG(l.calving_ease_score) AS avg_calving_ease,
    -- Daughter performance (if mature)
    (
        SELECT ROUND(AVG(mp2.total_daily_yield_liters), 2)
        FROM dim_animal daughter
        JOIN fact_daily_milk_production mp2 ON daughter.animal_sk = mp2.animal_sk
        JOIN dim_date d2 ON mp2.date_sk = d2.date_sk
        WHERE daughter.sire_animal_sk IN (
            SELECT DISTINCT a.animal_sk FROM dim_animal a WHERE a.animal_code = be.sire_code
        )
        AND d2.date_actual >= CURRENT_DATE - INTERVAL '90 days'
    ) AS daughter_avg_yield
FROM fact_breeding_event be
LEFT JOIN dim_lactation l ON l.animal_sk = be.animal_sk
    AND l.calving_date = be.estimated_calving_date
JOIN dim_date d ON be.event_date_sk = d.date_sk
WHERE d.date_actual >= CURRENT_DATE - INTERVAL '24 months'
GROUP BY be.sire_code, be.sire_breed
HAVING COUNT(*) >= 10  -- Minimum breedings for statistical significance
ORDER BY conception_rate DESC;
```

---

## 5. Predictive Analytics Models

### 5.1 Yield Forecasting Model

```python
# analytics/models/yield_forecasting.py
"""
Milk Yield Forecasting Model
Milestone 104 - Days 531-540
Machine learning model for predicting individual animal milk yields
"""

import pandas as pd
import numpy as np
from typing import Dict, List, Tuple, Optional
from datetime import datetime, timedelta
from sklearn.ensemble import GradientBoostingRegressor, RandomForestRegressor
from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.model_selection import TimeSeriesSplit, cross_val_score
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
import joblib
import logging
from dataclasses import dataclass

logger = logging.getLogger(__name__)


@dataclass
class YieldPrediction:
    """Container for yield prediction results."""
    animal_code: str
    prediction_date: datetime
    predicted_yield: float
    confidence_lower: float
    confidence_upper: float
    model_version: str
    feature_importance: Dict[str, float]


class MilkYieldForecaster:
    """
    Machine learning model for forecasting individual animal milk yields.
    Uses historical production data, lactation curves, and environmental factors.
    """

    def __init__(self, model_path: Optional[str] = None):
        self.model = None
        self.scaler = StandardScaler()
        self.breed_encoder = LabelEncoder()
        self.model_version = "1.0.0"
        self.feature_names = []

        if model_path:
            self.load_model(model_path)

    def prepare_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Prepare feature set for model training/prediction.

        Features include:
        - Lactation stage features
        - Historical yield patterns
        - Animal characteristics
        - Environmental factors
        - Seasonal patterns
        """
        features = pd.DataFrame()

        # Lactation stage features
        features['days_in_milk'] = df['days_in_milk']
        features['lactation_week'] = df['days_in_milk'] // 7
        features['lactation_number'] = df['lactation_number']
        features['is_first_lactation'] = (df['lactation_number'] == 1).astype(int)

        # Days in milk polynomial features (capture lactation curve shape)
        features['dim_squared'] = df['days_in_milk'] ** 2
        features['dim_log'] = np.log1p(df['days_in_milk'])
        features['dim_sqrt'] = np.sqrt(df['days_in_milk'])

        # Historical yield features (lagged values)
        for lag in [1, 3, 7, 14, 30]:
            features[f'yield_lag_{lag}d'] = df.groupby('animal_code')['total_daily_yield'].shift(lag)

        # Rolling statistics
        for window in [7, 14, 30]:
            features[f'yield_rolling_mean_{window}d'] = df.groupby('animal_code')['total_daily_yield'].transform(
                lambda x: x.rolling(window, min_periods=1).mean()
            )
            features[f'yield_rolling_std_{window}d'] = df.groupby('animal_code')['total_daily_yield'].transform(
                lambda x: x.rolling(window, min_periods=1).std()
            )

        # Yield trend (slope of last 7 days)
        features['yield_trend_7d'] = df.groupby('animal_code')['total_daily_yield'].transform(
            lambda x: x.rolling(7, min_periods=3).apply(
                lambda y: np.polyfit(range(len(y)), y, 1)[0] if len(y) > 1 else 0
            )
        )

        # Breed encoding
        if 'breed_name' in df.columns:
            features['breed_encoded'] = self.breed_encoder.fit_transform(
                df['breed_name'].fillna('Unknown')
            )

        # Animal age features
        if 'birth_date' in df.columns and 'production_date' in df.columns:
            features['animal_age_months'] = (
                (pd.to_datetime(df['production_date']) - pd.to_datetime(df['birth_date'])).dt.days / 30
            ).astype(int)

        # Milk quality features
        if 'avg_fat_pct' in df.columns:
            features['avg_fat_pct'] = df['avg_fat_pct'].fillna(df['avg_fat_pct'].mean())
        if 'avg_protein_pct' in df.columns:
            features['avg_protein_pct'] = df['avg_protein_pct'].fillna(df['avg_protein_pct'].mean())
        if 'avg_scc' in df.columns:
            features['avg_scc'] = df['avg_scc'].fillna(df['avg_scc'].median())
            features['scc_log'] = np.log1p(features['avg_scc'])

        # Seasonal features
        if 'production_date' in df.columns:
            date_col = pd.to_datetime(df['production_date'])
            features['day_of_year'] = date_col.dt.dayofyear
            features['month'] = date_col.dt.month
            features['is_summer'] = date_col.dt.month.isin([6, 7, 8]).astype(int)
            features['is_winter'] = date_col.dt.month.isin([12, 1, 2]).astype(int)

            # Cyclical encoding of day of year
            features['day_sin'] = np.sin(2 * np.pi * features['day_of_year'] / 365)
            features['day_cos'] = np.cos(2 * np.pi * features['day_of_year'] / 365)

        # Weather/environmental features (if available)
        if 'temperature_celsius' in df.columns:
            features['temperature'] = df['temperature_celsius']
            features['heat_stress'] = (df['temperature_celsius'] > 25).astype(int)

        # Store feature names
        self.feature_names = features.columns.tolist()

        return features

    def train(
        self,
        train_data: pd.DataFrame,
        target_column: str = 'total_daily_yield',
        optimize_hyperparams: bool = True
    ) -> Dict[str, float]:
        """
        Train the yield forecasting model.

        Args:
            train_data: Historical production data
            target_column: Name of target variable column
            optimize_hyperparams: Whether to perform hyperparameter optimization

        Returns:
            Dictionary of training metrics
        """
        logger.info("Preparing features for training...")
        X = self.prepare_features(train_data)
        y = train_data[target_column]

        # Remove rows with NaN in features or target
        valid_mask = ~(X.isna().any(axis=1) | y.isna())
        X = X[valid_mask]
        y = y[valid_mask]

        logger.info(f"Training on {len(X)} samples with {len(self.feature_names)} features")

        # Scale features
        X_scaled = self.scaler.fit_transform(X)

        # Initialize model
        if optimize_hyperparams:
            # Simplified hyperparameter search
            best_score = float('-inf')
            best_params = {}

            for n_estimators in [100, 200]:
                for max_depth in [5, 7, 10]:
                    for learning_rate in [0.05, 0.1]:
                        model = GradientBoostingRegressor(
                            n_estimators=n_estimators,
                            max_depth=max_depth,
                            learning_rate=learning_rate,
                            random_state=42,
                            n_iter_no_change=10
                        )

                        tscv = TimeSeriesSplit(n_splits=3)
                        scores = cross_val_score(model, X_scaled, y, cv=tscv, scoring='r2')
                        mean_score = scores.mean()

                        if mean_score > best_score:
                            best_score = mean_score
                            best_params = {
                                'n_estimators': n_estimators,
                                'max_depth': max_depth,
                                'learning_rate': learning_rate
                            }

            logger.info(f"Best hyperparameters: {best_params}")
            self.model = GradientBoostingRegressor(**best_params, random_state=42)
        else:
            self.model = GradientBoostingRegressor(
                n_estimators=200,
                max_depth=7,
                learning_rate=0.1,
                random_state=42
            )

        # Train final model
        self.model.fit(X_scaled, y)

        # Calculate training metrics
        y_pred = self.model.predict(X_scaled)
        metrics = {
            'mae': mean_absolute_error(y, y_pred),
            'rmse': np.sqrt(mean_squared_error(y, y_pred)),
            'r2': r2_score(y, y_pred),
            'mape': np.mean(np.abs((y - y_pred) / y)) * 100
        }

        logger.info(f"Training metrics: MAE={metrics['mae']:.2f}, R2={metrics['r2']:.4f}")

        return metrics

    def predict(
        self,
        data: pd.DataFrame,
        return_confidence: bool = True,
        confidence_level: float = 0.95
    ) -> List[YieldPrediction]:
        """
        Generate yield predictions for given data.

        Args:
            data: Data for prediction
            return_confidence: Whether to calculate confidence intervals
            confidence_level: Confidence level for intervals

        Returns:
            List of YieldPrediction objects
        """
        if self.model is None:
            raise ValueError("Model not trained. Call train() first.")

        X = self.prepare_features(data)
        X_scaled = self.scaler.transform(X)

        # Point predictions
        predictions = self.model.predict(X_scaled)

        # Feature importance
        importance = dict(zip(self.feature_names, self.model.feature_importances_))
        top_features = dict(sorted(importance.items(), key=lambda x: x[1], reverse=True)[:10])

        results = []
        for idx, (_, row) in enumerate(data.iterrows()):
            pred_value = predictions[idx]

            # Simple confidence interval based on historical variance
            std_estimate = data['total_daily_yield'].std() * 0.1  # Simplified
            z_score = 1.96 if confidence_level == 0.95 else 1.645

            prediction = YieldPrediction(
                animal_code=row.get('animal_code', 'Unknown'),
                prediction_date=row.get('production_date', datetime.now()),
                predicted_yield=round(pred_value, 2),
                confidence_lower=round(pred_value - z_score * std_estimate, 2),
                confidence_upper=round(pred_value + z_score * std_estimate, 2),
                model_version=self.model_version,
                feature_importance=top_features
            )
            results.append(prediction)

        return results

    def save_model(self, path: str):
        """Save trained model to disk."""
        model_data = {
            'model': self.model,
            'scaler': self.scaler,
            'breed_encoder': self.breed_encoder,
            'feature_names': self.feature_names,
            'model_version': self.model_version
        }
        joblib.dump(model_data, path)
        logger.info(f"Model saved to {path}")

    def load_model(self, path: str):
        """Load trained model from disk."""
        model_data = joblib.load(path)
        self.model = model_data['model']
        self.scaler = model_data['scaler']
        self.breed_encoder = model_data['breed_encoder']
        self.feature_names = model_data['feature_names']
        self.model_version = model_data['model_version']
        logger.info(f"Model loaded from {path}")


class HerdYieldForecaster:
    """
    Aggregate herd-level yield forecasting.
    Combines individual animal predictions for farm-wide forecasts.
    """

    def __init__(self, individual_forecaster: MilkYieldForecaster):
        self.individual_forecaster = individual_forecaster

    def forecast_herd(
        self,
        herd_data: pd.DataFrame,
        forecast_horizon: int = 30
    ) -> pd.DataFrame:
        """
        Generate herd-level yield forecasts.

        Args:
            herd_data: Current herd production data
            forecast_horizon: Days to forecast ahead

        Returns:
            DataFrame with daily herd-level forecasts
        """
        forecasts = []

        for day_offset in range(1, forecast_horizon + 1):
            # Project each animal forward
            projected_data = herd_data.copy()
            projected_data['days_in_milk'] += day_offset
            projected_data['production_date'] = pd.to_datetime(
                projected_data['production_date']
            ) + timedelta(days=day_offset)

            # Get individual predictions
            predictions = self.individual_forecaster.predict(projected_data)

            # Aggregate
            total_yield = sum(p.predicted_yield for p in predictions)
            avg_yield = total_yield / len(predictions) if predictions else 0

            forecasts.append({
                'forecast_date': projected_data['production_date'].iloc[0],
                'days_ahead': day_offset,
                'predicted_total_yield': round(total_yield, 2),
                'predicted_avg_yield': round(avg_yield, 2),
                'animals_forecasted': len(predictions)
            })

        return pd.DataFrame(forecasts)
```

---

## 6. Daily Development Schedule

### Day 531: Farm Data Model Design

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 (Backend Lead) | Design farm analytics star schema | Schema documentation |
| 09:00-12:00 | Dev 2 (Full-Stack) | Review existing Odoo farm module data structures | Gap analysis report |
| 09:00-12:00 | Dev 3 (Frontend Lead) | Design farm analytics dashboard wireframes | Figma prototypes |
| 13:00-15:00 | Dev 1 | Create dimension tables (animal, breed, feed_type) | DDL scripts executed |
| 13:00-15:00 | Dev 2 | Map Odoo fields to dimension attributes | Mapping document |
| 13:00-15:00 | Dev 3 | Design KPI card components for farm metrics | Component specifications |
| 15:00-17:00 | All | Schema review meeting and refinement | Approved schema design |

**Day 531 Acceptance Criteria:**
- [ ] All dimension tables created with proper constraints
- [ ] Odoo to DW field mapping documented
- [ ] Dashboard wireframes approved by stakeholders

### Day 532: Fact Tables and ETL Foundation

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create milk production fact table | fact_daily_milk_production table |
| 09:00-12:00 | Dev 2 | Create feed consumption fact table | fact_feed_consumption table |
| 09:00-12:00 | Dev 3 | Implement date dimension population script | Date dim with 10-year range |
| 13:00-15:00 | Dev 1 | Create health event fact table | fact_animal_health_event table |
| 13:00-15:00 | Dev 2 | Create breeding event fact table | fact_breeding_event table |
| 13:00-15:00 | Dev 3 | Create daily aggregate fact table | fact_daily_farm_aggregate table |
| 15:00-17:00 | Dev 1 | Design ETL DAG structure | Airflow DAG skeleton |
| 15:00-17:00 | Dev 2 | Implement source data extractors | Python extractor classes |
| 15:00-17:00 | Dev 3 | Set up ETL logging and monitoring | Logging configuration |

**Day 532 Acceptance Criteria:**
- [ ] All fact tables created with proper indexes
- [ ] ETL DAG structure defined
- [ ] Source extractors functional

### Day 533: ETL Pipeline Development

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Implement animal data extraction | Animal ETL task |
| 09:00-12:00 | Dev 2 | Implement milk production extraction | Milk production ETL task |
| 09:00-12:00 | Dev 3 | Implement feed consumption extraction | Feed ETL task |
| 13:00-15:00 | Dev 1 | Implement health event extraction | Health ETL task |
| 13:00-15:00 | Dev 2 | Implement breeding event extraction | Breeding ETL task |
| 13:00-15:00 | Dev 3 | Implement data transformation layer | Transformer classes |
| 15:00-17:00 | Dev 1 | Implement dimension key lookup/upsert | SCD Type 2 handlers |
| 15:00-17:00 | Dev 2 | Implement fact table loaders | Loader classes |
| 15:00-17:00 | Dev 3 | ETL error handling and retry logic | Error handlers |

**Day 533 Acceptance Criteria:**
- [ ] All extraction tasks functional
- [ ] Transformation logic validated
- [ ] Loaders successfully writing to DW

### Day 534: Herd Performance Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create herd production KPI views | vw_herd_production_kpi |
| 09:00-12:00 | Dev 2 | Create animal performance ranking view | vw_animal_performance_ranking |
| 09:00-12:00 | Dev 3 | Build herd overview dashboard in Superset | Herd dashboard |
| 13:00-15:00 | Dev 1 | Create lactation curve analysis view | vw_lactation_curve_analysis |
| 13:00-15:00 | Dev 2 | Create breed comparison view | vw_breed_performance_comparison |
| 13:00-15:00 | Dev 3 | Build animal drill-down components | Interactive charts |
| 15:00-17:00 | Dev 1 | Implement yield trend calculations | Trend materialized view |
| 15:00-17:00 | Dev 2 | Add benchmark comparison logic | Benchmark queries |
| 15:00-17:00 | Dev 3 | Add dashboard filters (farm, date range, breed) | Filter components |

**Day 534 Acceptance Criteria:**
- [ ] Herd KPI dashboard operational
- [ ] Animal ranking view displaying correctly
- [ ] Lactation curve visualizations complete

### Day 535: Cost and Feed Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create cost per liter calculation view | vw_daily_cost_per_liter |
| 09:00-12:00 | Dev 2 | Create feed conversion ratio view | vw_feed_conversion_by_animal |
| 09:00-12:00 | Dev 3 | Build cost analytics dashboard | Cost dashboard |
| 13:00-15:00 | Dev 1 | Create feed type efficiency view | vw_feed_type_efficiency |
| 13:00-15:00 | Dev 2 | Implement labor cost allocation model | Cost model |
| 13:00-15:00 | Dev 3 | Build feed efficiency visualizations | FCR charts |
| 15:00-17:00 | Dev 1 | Create cost trend analysis queries | Trend queries |
| 15:00-17:00 | Dev 2 | Add cost comparison by farm/period | Comparison views |
| 15:00-17:00 | Dev 3 | Implement cost alert thresholds | Alert configuration |

**Day 535 Acceptance Criteria:**
- [ ] Cost per liter tracking operational
- [ ] Feed efficiency dashboard complete
- [ ] Cost alerts configured

### Day 536: Health Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create health event summary view | vw_health_event_summary |
| 09:00-12:00 | Dev 2 | Create disease outbreak warning view | vw_disease_outbreak_warning |
| 09:00-12:00 | Dev 3 | Build health analytics dashboard | Health dashboard |
| 13:00-15:00 | Dev 1 | Create SCC trend analysis view | vw_scc_trend_analysis |
| 13:00-15:00 | Dev 2 | Implement mastitis risk scoring | Risk calculation |
| 13:00-15:00 | Dev 3 | Build disease heatmap visualization | Heatmap component |
| 15:00-17:00 | Dev 1 | Create vaccination compliance tracking | Compliance view |
| 15:00-17:00 | Dev 2 | Add treatment effectiveness analysis | Treatment analysis |
| 15:00-17:00 | Dev 3 | Configure health alert notifications | Alert system |

**Day 536 Acceptance Criteria:**
- [ ] Health dashboard operational
- [ ] Disease early warning functional
- [ ] SCC monitoring active

### Day 537: Breeding Analytics

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Create conception rate dashboard view | vw_conception_rate_dashboard |
| 09:00-12:00 | Dev 2 | Create calving interval analysis view | vw_calving_interval_analysis |
| 09:00-12:00 | Dev 3 | Build breeding analytics dashboard | Breeding dashboard |
| 13:00-15:00 | Dev 1 | Create sire performance analysis view | vw_sire_performance_analysis |
| 13:00-15:00 | Dev 2 | Implement genetic progress tracking | Genetic metrics |
| 13:00-15:00 | Dev 3 | Build reproduction calendar view | Calendar component |
| 15:00-17:00 | Dev 1 | Add heat detection analytics | Heat detection view |
| 15:00-17:00 | Dev 2 | Create breeding cost analysis | Cost breakdown |
| 15:00-17:00 | Dev 3 | Build breeding recommendation engine UI | Recommendation display |

**Day 537 Acceptance Criteria:**
- [ ] Breeding dashboard complete
- [ ] Conception rate tracking accurate
- [ ] Sire performance analysis functional

### Day 538: Predictive Models

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Implement yield forecasting model | MilkYieldForecaster class |
| 09:00-12:00 | Dev 2 | Prepare training data pipeline | Data preparation scripts |
| 09:00-12:00 | Dev 3 | Design prediction result displays | Forecast UI components |
| 13:00-15:00 | Dev 1 | Train and validate yield model | Trained model artifact |
| 13:00-15:00 | Dev 2 | Implement herd-level forecasting | HerdYieldForecaster class |
| 13:00-15:00 | Dev 3 | Build yield forecast dashboard | Forecast dashboard |
| 15:00-17:00 | Dev 1 | Add model serving API endpoint | Flask/FastAPI endpoint |
| 15:00-17:00 | Dev 2 | Implement model retraining pipeline | Airflow retraining DAG |
| 15:00-17:00 | Dev 3 | Add prediction confidence displays | Confidence intervals UI |

**Day 538 Acceptance Criteria:**
- [ ] Yield forecasting model trained (R² > 0.85)
- [ ] Prediction API deployed
- [ ] Forecast dashboard operational

### Day 539: Integration and Optimization

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Optimize slow-running queries | Query optimization |
| 09:00-12:00 | Dev 2 | Add materialized view refresh scheduling | MV refresh jobs |
| 09:00-12:00 | Dev 3 | Implement dashboard caching | Redis cache layer |
| 13:00-15:00 | Dev 1 | Create farm analytics API endpoints | REST API |
| 13:00-15:00 | Dev 2 | Implement real-time data streaming | Streaming pipeline |
| 13:00-15:00 | Dev 3 | Add export functionality (PDF, Excel) | Export features |
| 15:00-17:00 | Dev 1 | Integration with Odoo farm module | Odoo integration |
| 15:00-17:00 | Dev 2 | Add data quality monitoring | DQ checks |
| 15:00-17:00 | Dev 3 | Mobile-responsive adjustments | Responsive CSS |

**Day 539 Acceptance Criteria:**
- [ ] All dashboards load within 3 seconds
- [ ] Exports functional
- [ ] Mobile views operational

### Day 540: Testing and Documentation

| Time Block | Developer | Task | Deliverable |
|------------|-----------|------|-------------|
| 09:00-12:00 | Dev 1 | Unit tests for ETL pipeline | 90%+ coverage |
| 09:00-12:00 | Dev 2 | Integration tests for data flow | Integration test suite |
| 09:00-12:00 | Dev 3 | UI/UX testing and fixes | Bug fixes |
| 13:00-15:00 | Dev 1 | Performance testing | Load test results |
| 13:00-15:00 | Dev 2 | Data validation testing | Validation report |
| 13:00-15:00 | Dev 3 | User acceptance testing support | UAT support |
| 15:00-17:00 | All | Documentation and knowledge transfer | Technical documentation |
| 15:00-17:00 | All | Milestone review and sign-off | Sign-off document |

**Day 540 Acceptance Criteria:**
- [ ] All tests passing
- [ ] Performance benchmarks met
- [ ] Documentation complete
- [ ] Stakeholder sign-off obtained

---

## 7. Requirement Traceability Matrix

| Requirement ID | Requirement Description | Implementation | Test Case |
|----------------|------------------------|----------------|-----------|
| RFP-FARM-001 | Real-time farm performance monitoring | vw_herd_production_kpi, Herd Dashboard | TC-104-001 |
| RFP-FARM-002 | Individual animal tracking | dim_animal, vw_animal_performance_ranking | TC-104-002 |
| BRD-OPS-003 | Operational efficiency analytics | Cost per liter views, FCR analysis | TC-104-003 |
| BRD-OPS-005 | Feed management optimization | vw_feed_type_efficiency, Feed Dashboard | TC-104-004 |
| SRS-ANALYTICS-012 | Predictive yield modeling | MilkYieldForecaster, Forecast API | TC-104-005 |
| SRS-ANALYTICS-013 | Animal health trend analysis | vw_health_event_summary, Health Dashboard | TC-104-006 |
| SRS-ANALYTICS-014 | Feed efficiency optimization | vw_feed_conversion_by_animal | TC-104-007 |
| SRS-ANALYTICS-015 | Breeding program analytics | vw_conception_rate_dashboard, Breeding Dashboard | TC-104-008 |
| SRS-ANALYTICS-016 | Disease outbreak prediction | vw_disease_outbreak_warning, Alert System | TC-104-009 |
| SRS-ANALYTICS-017 | Cost per liter tracking | vw_daily_cost_per_liter, Cost Dashboard | TC-104-010 |

---

## 8. Risk Register

| Risk ID | Description | Probability | Impact | Mitigation |
|---------|-------------|-------------|--------|------------|
| R104-001 | Incomplete historical farm data | Medium | High | Data gap analysis, synthetic data generation for training |
| R104-002 | IoT sensor data latency | Medium | Medium | Buffer implementation, async processing |
| R104-003 | Model accuracy below target | Medium | High | Ensemble methods, additional feature engineering |
| R104-004 | Complex lactation curve variations | Low | Medium | Multiple curve models by breed/parity |
| R104-005 | Data quality issues in health records | High | Medium | Data validation rules, manual review workflow |

---

## 9. Quality Assurance Checklist

### 9.1 Code Quality
- [ ] All Python code passes flake8 linting
- [ ] SQL queries optimized with EXPLAIN ANALYZE
- [ ] Unit test coverage > 85%
- [ ] Code review completed for all PRs

### 9.2 Data Quality
- [ ] Dimension referential integrity validated
- [ ] Fact table aggregations verified against source
- [ ] Null handling tested for all calculations
- [ ] Historical data completeness verified

### 9.3 Performance
- [ ] Dashboard load time < 3 seconds
- [ ] ETL pipeline completes within 2 hours
- [ ] API response time < 500ms
- [ ] Concurrent user load tested (50+ users)

### 9.4 Security
- [ ] Role-based access control implemented
- [ ] Sensitive data masked appropriately
- [ ] Audit logging enabled
- [ ] SQL injection prevention verified

---

## 10. Appendices

### Appendix A: Glossary

| Term | Definition |
|------|------------|
| DIM (Days in Milk) | Number of days since an animal's most recent calving |
| FCR (Feed Conversion Ratio) | Kg of feed required to produce 1 kg of milk |
| SCC (Somatic Cell Count) | Indicator of milk quality and udder health |
| Lactation Curve | Pattern of milk production over a lactation period |
| Conception Rate | Percentage of breedings resulting in pregnancy |
| Calving Interval | Days between successive calvings |

### Appendix B: Reference Documents

- Phase_11_Index_Executive_Summary.md
- Milestone_101_BI_Platform_Setup.md
- Smart Dairy RFP Document
- Smart Dairy BRD Document
- Smart Dairy SRS Document

---

**Document End**

*Milestone 104: Farm Analytics Module - Days 531-540*
