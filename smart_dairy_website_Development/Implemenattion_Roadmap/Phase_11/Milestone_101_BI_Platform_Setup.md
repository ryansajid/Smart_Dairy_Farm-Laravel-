# Milestone 101: BI Platform Setup

## Smart Dairy Digital Smart Portal + ERP System (Odoo 19 CE)

---

## Document Control

| Field                | Detail                                                        |
|----------------------|---------------------------------------------------------------|
| **Document ID**      | SD-IMPL-P11-M101-v1.0                                         |
| **Milestone**        | 101 of 110                                                    |
| **Title**            | BI Platform Setup                                             |
| **Phase**            | Phase 11: Commerce — Advanced Analytics                       |
| **Days**             | Days 501–510 (10 working days)                                |
| **Duration**         | 2 weeks                                                       |
| **Developers**       | 3 Full Stack Developers                                       |
| **Total Effort**     | 240 person-hours (3 devs × 10 days × 8 hours)                 |
| **Status**           | Planned                                                       |
| **Version**          | 1.0                                                           |
| **Last Updated**     | 2026-02-04                                                    |
| **Authors**          | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Frontend)    |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Objectives](#2-objectives)
3. [Key Deliverables](#3-key-deliverables)
4. [Requirement Traceability Matrix](#4-requirement-traceability-matrix)
5. [Day-by-Day Development Plan](#5-day-by-day-development-plan)
6. [Technical Specifications](#6-technical-specifications)
7. [Testing & Validation](#7-testing--validation)
8. [Risk & Mitigation](#8-risk--mitigation)
9. [Dependencies & Handoffs](#9-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 101 establishes the foundational Business Intelligence (BI) infrastructure for Smart Dairy. This includes deploying Apache Superset as the primary analytics platform, configuring data warehouse connections, implementing security controls, and building the ETL pipeline that will power all subsequent analytics milestones.

### 1.2 Scope

- Apache Superset 3.x installation and Docker deployment
- PostgreSQL 16 data warehouse with star schema design
- TimescaleDB connection for IoT time-series data
- Role-based access control (RBAC) with 6 custom roles
- Single Sign-On (SSO) integration with Odoo authentication
- ETL pipeline development using Apache Airflow
- Redis caching for query performance optimization
- Dashboard template library and chart configurations
- Query optimization and performance tuning

### 1.3 Key Outcomes

By the end of Milestone 101:
- Apache Superset is deployed and accessible to authorized users
- All data sources (Odoo PostgreSQL, TimescaleDB, Data Warehouse) are connected
- ETL pipelines are loading data into the data warehouse on schedule
- Security controls are in place with role-based access
- Dashboard templates are available for subsequent milestones
- Platform performance meets baseline requirements

---

## 2. Objectives

| # | Objective                                      | Priority   | Measurable Target                        |
|---|------------------------------------------------|------------|------------------------------------------|
| 1 | Deploy production-grade Apache Superset        | Critical   | Accessible via configured URL            |
| 2 | Configure PostgreSQL data warehouse            | Critical   | Star schema with 6 dimensions, 6 facts   |
| 3 | Connect TimescaleDB for IoT data               | High       | Connection verified, hypertables visible |
| 4 | Implement role-based access control            | Critical   | 6 roles configured and tested            |
| 5 | Integrate SSO with Odoo                        | High       | Users can login via Odoo credentials     |
| 6 | Build ETL pipeline with Airflow                | Critical   | DAGs running on schedule                 |
| 7 | Configure Redis caching                        | High       | Query caching operational                |
| 8 | Create dashboard templates                     | Medium     | 10+ chart templates available            |
| 9 | Optimize query performance                     | High       | Baseline queries < 500ms                 |
| 10| Complete platform documentation                | Medium     | Setup guide and admin docs complete      |

---

## 3. Key Deliverables

| # | Deliverable                              | Description                                                | Owner  | Priority |
|---|------------------------------------------|------------------------------------------------------------|--------|----------|
| 1 | Apache Superset Docker Deployment        | Production-ready Superset with Celery workers              | Dev 2  | Critical |
| 2 | Data Warehouse Schema                    | Star schema with dimension and fact tables                 | Dev 1  | Critical |
| 3 | TimescaleDB Connection                   | Configured connection for IoT time-series data             | Dev 1  | High     |
| 4 | RBAC Configuration                       | 6 custom roles with appropriate permissions                | Dev 2  | Critical |
| 5 | SSO Integration                          | Odoo OAuth integration for single sign-on                  | Dev 2  | High     |
| 6 | ETL Pipeline (Airflow DAGs)              | Daily/hourly data load pipelines                           | Dev 1  | Critical |
| 7 | Redis Cache Configuration                | Query result caching for performance                       | Dev 2  | High     |
| 8 | Dashboard Template Library               | Reusable chart and dashboard templates                     | Dev 3  | Medium   |
| 9 | Materialized Views                       | Pre-aggregated views for common queries                    | Dev 1  | High     |
| 10| Platform Documentation                   | Setup guide, admin manual, troubleshooting                 | All    | Medium   |

---

## 4. Requirement Traceability Matrix

| Req ID       | Source     | Requirement Description                          | Day(s) | Task Reference              |
|--------------|------------|--------------------------------------------------|--------|------------------------------|
| ANAL-001     | RFP        | Business Intelligence Platform                   | 1-10   | Entire milestone             |
| SRS-BI-001   | SRS        | Superset deployment on Docker                    | 1-2    | Docker setup, configuration  |
| SRS-BI-002   | SRS        | PostgreSQL data warehouse with star schema       | 3-4    | DW schema, ETL               |
| SRS-BI-004   | SRS        | Role-based access control                        | 5-6    | RBAC implementation          |
| SRS-BI-005   | SRS        | SSO integration with Odoo                        | 5-6    | OAuth integration            |
| SRS-BI-003   | SRS        | ETL pipeline with Airflow DAGs                   | 3-4    | Airflow DAG development      |
| NFR-PERF-01  | BRD        | Dashboard load time < 2 seconds                  | 9-10   | Performance tuning           |
| NFR-SEC-01   | BRD        | Data encrypted in transit                        | 5-6    | TLS configuration            |
| D-001 §2     | Impl Guide | Multi-environment strategy                       | 1-2    | Docker profiles              |
| D-003 §1-4   | Impl Guide | Docker Configuration                             | 1-2    | Docker Compose               |

---

## 5. Day-by-Day Development Plan

---

### Day 501: Apache Superset Installation

**Objective:** Install and configure Apache Superset with Docker deployment, including all required dependencies.

---

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Design Superset deployment architecture           | Architecture diagram document                  |
| 2-4   | Create superset_config.py configuration           | Configuration file with all settings           |
| 4-6   | Configure database connection settings            | Database connection pool configuration         |
| 6-8   | Set up Celery worker configuration                | Celery configuration for async queries         |

**superset_config.py:**
```python
# /opt/superset/superset_config.py
import os
from datetime import timedelta
from celery.schedules import crontab

# ============================================================
# GENERAL CONFIGURATION
# ============================================================

# Flask App Builder configuration
APP_NAME = "Smart Dairy Analytics"
APP_ICON = "/static/assets/images/smart_dairy_logo.png"

# Secret key for session management
SECRET_KEY = os.environ.get('SUPERSET_SECRET_KEY', 'your-secret-key-here')

# ============================================================
# DATABASE CONFIGURATION
# ============================================================

# Superset metadata database
SQLALCHEMY_DATABASE_URI = os.environ.get(
    'DATABASE_URL',
    'postgresql://superset:superset_password@postgres:5432/superset_meta'
)

# Database connection pool settings
SQLALCHEMY_POOL_SIZE = 10
SQLALCHEMY_POOL_TIMEOUT = 30
SQLALCHEMY_POOL_RECYCLE = 3600
SQLALCHEMY_MAX_OVERFLOW = 20

# ============================================================
# CACHE CONFIGURATION
# ============================================================

REDIS_HOST = os.environ.get('REDIS_HOST', 'redis')
REDIS_PORT = int(os.environ.get('REDIS_PORT', 6379))
REDIS_DB = int(os.environ.get('REDIS_DB', 0))

# Result cache for dashboard queries
CACHE_CONFIG = {
    'CACHE_TYPE': 'RedisCache',
    'CACHE_DEFAULT_TIMEOUT': 300,
    'CACHE_KEY_PREFIX': 'superset_results_',
    'CACHE_REDIS_HOST': REDIS_HOST,
    'CACHE_REDIS_PORT': REDIS_PORT,
    'CACHE_REDIS_DB': 1,
}

# Data cache for longer-term caching
DATA_CACHE_CONFIG = {
    'CACHE_TYPE': 'RedisCache',
    'CACHE_DEFAULT_TIMEOUT': 86400,  # 24 hours
    'CACHE_KEY_PREFIX': 'superset_data_',
    'CACHE_REDIS_HOST': REDIS_HOST,
    'CACHE_REDIS_PORT': REDIS_PORT,
    'CACHE_REDIS_DB': 2,
}

# Filter state cache
FILTER_STATE_CACHE_CONFIG = {
    'CACHE_TYPE': 'RedisCache',
    'CACHE_DEFAULT_TIMEOUT': 600,
    'CACHE_KEY_PREFIX': 'superset_filter_',
    'CACHE_REDIS_HOST': REDIS_HOST,
    'CACHE_REDIS_PORT': REDIS_PORT,
    'CACHE_REDIS_DB': 3,
}

# Explore form data cache
EXPLORE_FORM_DATA_CACHE_CONFIG = {
    'CACHE_TYPE': 'RedisCache',
    'CACHE_DEFAULT_TIMEOUT': 600,
    'CACHE_KEY_PREFIX': 'superset_explore_',
    'CACHE_REDIS_HOST': REDIS_HOST,
    'CACHE_REDIS_PORT': REDIS_PORT,
    'CACHE_REDIS_DB': 4,
}

# ============================================================
# CELERY CONFIGURATION
# ============================================================

class CeleryConfig:
    broker_url = f'redis://{REDIS_HOST}:{REDIS_PORT}/0'
    result_backend = f'redis://{REDIS_HOST}:{REDIS_PORT}/0'
    task_ignore_result = True
    task_acks_late = True
    worker_prefetch_multiplier = 1
    task_annotations = {
        'sql_lab.get_sql_results': {
            'rate_limit': '100/s',
        },
    }
    beat_schedule = {
        'email_reports': {
            'task': 'email_reports.send',
            'schedule': crontab(minute=1, hour='*'),
        },
        'cache_warmup': {
            'task': 'cache.warm_up_charts',
            'schedule': crontab(minute='*/30'),
        },
    }

CELERY_CONFIG = CeleryConfig

# ============================================================
# FEATURE FLAGS
# ============================================================

FEATURE_FLAGS = {
    # Core features
    'ENABLE_TEMPLATE_PROCESSING': True,
    'DASHBOARD_NATIVE_FILTERS': True,
    'DASHBOARD_CROSS_FILTERS': True,
    'DASHBOARD_VIRTUALIZATION': True,

    # Analytics features
    'DRILL_TO_DETAIL': True,
    'DRILL_BY': True,
    'HORIZONTAL_FILTER_BAR': True,

    # Advanced features
    'EMBEDDED_SUPERSET': True,
    'ALERT_REPORTS': True,
    'THUMBNAILS': True,
    'THUMBNAILS_SQLA_LISTENERS': True,

    # SQL Lab features
    'ENABLE_EXPLORE_DRAG_AND_DROP': True,
    'ENABLE_DND_WITH_CLICK_UX': True,

    # Security features
    'TAGGING_SYSTEM': True,
    'SHARE_QUERIES_VIA_KV_STORE': True,
}

# ============================================================
# SECURITY CONFIGURATION
# ============================================================

# CORS settings
ENABLE_CORS = True
CORS_OPTIONS = {
    'supports_credentials': True,
    'allow_headers': ['*'],
    'resources': ['*'],
    'origins': ['https://smartdairy.com', 'https://erp.smartdairy.com'],
}

# Session configuration
SESSION_COOKIE_HTTPONLY = True
SESSION_COOKIE_SECURE = True
SESSION_COOKIE_SAMESITE = 'Lax'

# CSRF protection
WTF_CSRF_ENABLED = True

# Row limit for queries
ROW_LIMIT = 50000
SQL_MAX_ROW = ROW_LIMIT

# Query timeout
SUPERSET_WEBSERVER_TIMEOUT = 300
SQLLAB_TIMEOUT = 300
SQLLAB_ASYNC_TIME_LIMIT_SEC = 600

# ============================================================
# EMAIL CONFIGURATION
# ============================================================

SMTP_HOST = os.environ.get('SMTP_HOST', 'smtp.smartdairy.com')
SMTP_STARTTLS = True
SMTP_SSL = False
SMTP_USER = os.environ.get('SMTP_USER', 'analytics@smartdairy.com')
SMTP_PORT = 587
SMTP_PASSWORD = os.environ.get('SMTP_PASSWORD', '')
SMTP_MAIL_FROM = 'Smart Dairy Analytics <analytics@smartdairy.com>'

# Report configuration
ENABLE_SCHEDULED_EMAIL_REPORTS = True
EMAIL_REPORTS_SUBJECT_PREFIX = "[Smart Dairy] "

# ============================================================
# UPLOAD CONFIGURATION
# ============================================================

UPLOAD_FOLDER = '/app/superset_home/uploads/'
ALLOWED_EXTENSIONS = {'csv', 'xlsx', 'xls', 'json'}

# ============================================================
# LOGGING CONFIGURATION
# ============================================================

LOG_FORMAT = '%(asctime)s:%(levelname)s:%(name)s:%(message)s'
LOG_LEVEL = 'INFO'

ENABLE_QUERY_LOG_OBFUSCATION = True
```

---

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-3   | Create Docker Compose configuration               | docker-compose.yml for Superset stack          |
| 3-5   | Configure Nginx reverse proxy                     | nginx.conf with SSL termination                |
| 5-7   | Set up Docker networking and volumes              | Docker network and persistent volumes          |
| 7-8   | Create environment variable templates             | .env.example with all required variables       |

**docker-compose.yml:**
```yaml
# docker-compose.yml - Smart Dairy Superset Stack
version: '3.8'

x-superset-defaults: &superset-defaults
  image: apache/superset:3.1.0
  env_file: .env
  restart: unless-stopped
  depends_on:
    - postgres
    - redis
  volumes:
    - ./superset_config.py:/app/pythonpath/superset_config.py
    - superset_home:/app/superset_home
  networks:
    - smartdairy_analytics

services:
  # ============================================================
  # SUPERSET WEB SERVER
  # ============================================================
  superset-web:
    <<: *superset-defaults
    container_name: smartdairy_superset_web
    command: ["/app/docker/docker-bootstrap.sh", "app-gunicorn"]
    ports:
      - "8088:8088"
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8088/health"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 60s

  # ============================================================
  # SUPERSET CELERY WORKER
  # ============================================================
  superset-worker:
    <<: *superset-defaults
    container_name: smartdairy_superset_worker
    command: ["/app/docker/docker-bootstrap.sh", "worker"]
    healthcheck:
      test: ["CMD", "celery", "-A", "superset.tasks.celery_app:app", "inspect", "ping"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 60s

  # ============================================================
  # SUPERSET CELERY BEAT SCHEDULER
  # ============================================================
  superset-beat:
    <<: *superset-defaults
    container_name: smartdairy_superset_beat
    command: ["/app/docker/docker-bootstrap.sh", "beat"]
    healthcheck:
      test: ["CMD", "pgrep", "-f", "celery beat"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 30s

  # ============================================================
  # SUPERSET INITIALIZATION
  # ============================================================
  superset-init:
    <<: *superset-defaults
    container_name: smartdairy_superset_init
    command: ["/app/docker/docker-init.sh"]
    depends_on:
      - postgres
      - redis
    healthcheck:
      disable: true

  # ============================================================
  # POSTGRESQL (SUPERSET METADATA)
  # ============================================================
  postgres:
    image: postgres:16-alpine
    container_name: smartdairy_superset_postgres
    restart: unless-stopped
    environment:
      POSTGRES_DB: superset_meta
      POSTGRES_USER: superset
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}
    volumes:
      - superset_postgres_data:/var/lib/postgresql/data
    healthcheck:
      test: ["CMD", "pg_isready", "-U", "superset", "-d", "superset_meta"]
      interval: 10s
      timeout: 5s
      retries: 5
    networks:
      - smartdairy_analytics

  # ============================================================
  # REDIS (CACHE & CELERY BROKER)
  # ============================================================
  redis:
    image: redis:7-alpine
    container_name: smartdairy_superset_redis
    restart: unless-stopped
    command: redis-server --appendonly yes
    volumes:
      - superset_redis_data:/data
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 5s
      retries: 5
    networks:
      - smartdairy_analytics

  # ============================================================
  # NGINX REVERSE PROXY
  # ============================================================
  nginx:
    image: nginx:alpine
    container_name: smartdairy_superset_nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx/nginx.conf:/etc/nginx/nginx.conf:ro
      - ./nginx/ssl:/etc/nginx/ssl:ro
      - superset_home:/app/superset_home:ro
    depends_on:
      - superset-web
    networks:
      - smartdairy_analytics

volumes:
  superset_home:
    driver: local
  superset_postgres_data:
    driver: local
  superset_redis_data:
    driver: local

networks:
  smartdairy_analytics:
    name: smartdairy_analytics
    driver: bridge
    external: true
```

---

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Design Smart Dairy branding for Superset          | Brand assets and color scheme                  |
| 2-4   | Create custom CSS for Smart Dairy theme           | custom_css.css for dashboard styling           |
| 4-6   | Configure favicon and logo assets                 | Brand images in static folder                  |
| 6-8   | Document UI customization options                 | UI customization guide                         |

**custom_css.css:**
```css
/* Smart Dairy Analytics - Custom Theme */

/* ============================================================
   ROOT VARIABLES - Smart Dairy Brand Colors
   ============================================================ */
:root {
  /* Primary Colors - Green (Dairy/Agriculture theme) */
  --smart-dairy-primary: #2E7D32;
  --smart-dairy-primary-light: #4CAF50;
  --smart-dairy-primary-dark: #1B5E20;

  /* Secondary Colors - Saffron (Brand color) */
  --smart-dairy-secondary: #FF9800;
  --smart-dairy-secondary-light: #FFB74D;
  --smart-dairy-secondary-dark: #F57C00;

  /* Neutral Colors */
  --smart-dairy-white: #FFFFFF;
  --smart-dairy-gray-100: #F5F5F5;
  --smart-dairy-gray-200: #EEEEEE;
  --smart-dairy-gray-300: #E0E0E0;
  --smart-dairy-gray-500: #9E9E9E;
  --smart-dairy-gray-700: #616161;
  --smart-dairy-gray-900: #212121;

  /* Status Colors */
  --smart-dairy-success: #4CAF50;
  --smart-dairy-warning: #FF9800;
  --smart-dairy-error: #F44336;
  --smart-dairy-info: #2196F3;
}

/* ============================================================
   NAVIGATION - Header and Sidebar
   ============================================================ */
.navbar {
  background-color: var(--smart-dairy-primary) !important;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.navbar-brand {
  font-weight: 600;
  color: var(--smart-dairy-white) !important;
}

.nav-link {
  color: rgba(255,255,255,0.9) !important;
}

.nav-link:hover {
  color: var(--smart-dairy-white) !important;
  background-color: var(--smart-dairy-primary-dark);
}

/* ============================================================
   DASHBOARD STYLING
   ============================================================ */
.dashboard-header {
  background-color: var(--smart-dairy-white);
  border-bottom: 1px solid var(--smart-dairy-gray-200);
  padding: 16px 24px;
}

.dashboard-title {
  font-weight: 600;
  color: var(--smart-dairy-gray-900);
}

.chart-container {
  background-color: var(--smart-dairy-white);
  border-radius: 8px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
  padding: 16px;
  margin-bottom: 16px;
}

.chart-header {
  font-weight: 500;
  color: var(--smart-dairy-gray-700);
  border-bottom: 1px solid var(--smart-dairy-gray-200);
  padding-bottom: 12px;
  margin-bottom: 16px;
}

/* ============================================================
   KPI CARDS
   ============================================================ */
.kpi-card {
  background: linear-gradient(135deg, var(--smart-dairy-primary) 0%, var(--smart-dairy-primary-dark) 100%);
  color: var(--smart-dairy-white);
  border-radius: 12px;
  padding: 24px;
  text-align: center;
}

.kpi-card .value {
  font-size: 2.5rem;
  font-weight: 700;
}

.kpi-card .label {
  font-size: 0.9rem;
  opacity: 0.9;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.kpi-card .trend-up {
  color: var(--smart-dairy-success);
}

.kpi-card .trend-down {
  color: var(--smart-dairy-error);
}

/* ============================================================
   FILTERS
   ============================================================ */
.filter-bar {
  background-color: var(--smart-dairy-gray-100);
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 16px;
}

.filter-item {
  margin-right: 16px;
}

/* ============================================================
   BUTTONS
   ============================================================ */
.btn-primary {
  background-color: var(--smart-dairy-primary) !important;
  border-color: var(--smart-dairy-primary) !important;
}

.btn-primary:hover {
  background-color: var(--smart-dairy-primary-dark) !important;
  border-color: var(--smart-dairy-primary-dark) !important;
}

.btn-secondary {
  background-color: var(--smart-dairy-secondary) !important;
  border-color: var(--smart-dairy-secondary) !important;
}

/* ============================================================
   RESPONSIVE DESIGN
   ============================================================ */
@media (max-width: 768px) {
  .dashboard-header {
    padding: 12px 16px;
  }

  .chart-container {
    padding: 12px;
    margin-bottom: 12px;
  }

  .kpi-card .value {
    font-size: 1.8rem;
  }
}
```

---

### Day 502: Database Connections & Data Sources

**Objective:** Configure connections to all data sources including Odoo PostgreSQL, data warehouse, and TimescaleDB.

---

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Create read-only database users for analytics     | SQL scripts for user creation                  |
| 2-4   | Configure Odoo PostgreSQL connection              | Database connection in Superset                |
| 4-6   | Configure data warehouse connection               | DW database connection in Superset             |
| 6-8   | Configure TimescaleDB connection                  | TimescaleDB connection in Superset             |

**database_users.sql:**
```sql
-- ============================================================
-- CREATE READ-ONLY USERS FOR ANALYTICS
-- ============================================================

-- ============================================================
-- 1. ODOO DATABASE READ-ONLY USER
-- ============================================================
\c smartdairy_odoo

-- Create read-only user
CREATE USER analytics_odoo_readonly WITH PASSWORD 'secure_analytics_password_2026';

-- Grant connect permission
GRANT CONNECT ON DATABASE smartdairy_odoo TO analytics_odoo_readonly;

-- Grant schema usage
GRANT USAGE ON SCHEMA public TO analytics_odoo_readonly;

-- Grant select on all existing tables
GRANT SELECT ON ALL TABLES IN SCHEMA public TO analytics_odoo_readonly;

-- Grant select on future tables
ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT SELECT ON TABLES TO analytics_odoo_readonly;

-- Specific table grants for key Odoo tables
GRANT SELECT ON
    sale_order, sale_order_line,
    purchase_order, purchase_order_line,
    account_move, account_move_line,
    stock_quant, stock_move, stock_picking,
    product_product, product_template,
    res_partner, res_users,
    hr_employee,
    crm_lead
TO analytics_odoo_readonly;

-- ============================================================
-- 2. DATA WAREHOUSE USER
-- ============================================================
\c smartdairy_dw

-- Create warehouse user
CREATE USER analytics_dw_user WITH PASSWORD 'secure_dw_password_2026';

-- Grant connect permission
GRANT CONNECT ON DATABASE smartdairy_dw TO analytics_dw_user;

-- Grant schema usage
GRANT USAGE ON SCHEMA public TO analytics_dw_user;

-- Grant select on all tables (read for dashboards)
GRANT SELECT ON ALL TABLES IN SCHEMA public TO analytics_dw_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT SELECT ON TABLES TO analytics_dw_user;

-- Grant execute on functions (for aggregations)
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA public TO analytics_dw_user;

-- ============================================================
-- 3. TIMESCALEDB USER
-- ============================================================
\c smartdairy_iot

-- Create TimescaleDB user
CREATE USER analytics_timescale_readonly WITH PASSWORD 'secure_timescale_password_2026';

-- Grant connect permission
GRANT CONNECT ON DATABASE smartdairy_iot TO analytics_timescale_readonly;

-- Grant schema usage
GRANT USAGE ON SCHEMA public TO analytics_timescale_readonly;

-- Grant select on all tables including hypertables
GRANT SELECT ON ALL TABLES IN SCHEMA public TO analytics_timescale_readonly;
ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT SELECT ON TABLES TO analytics_timescale_readonly;

-- Grant usage on TimescaleDB specific schemas
GRANT USAGE ON SCHEMA timescaledb_information TO analytics_timescale_readonly;

-- ============================================================
-- VERIFY PERMISSIONS
-- ============================================================
SELECT
    grantee, table_schema, table_name, privilege_type
FROM information_schema.table_privileges
WHERE grantee LIKE 'analytics_%'
ORDER BY grantee, table_schema, table_name;
```

---

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Configure connection pooling with PgBouncer       | PgBouncer configuration                        |
| 2-4   | Set up SSL/TLS for database connections           | SSL certificates and configuration             |
| 4-6   | Test all database connections                     | Connection test results                        |
| 6-8   | Document connection configurations                | Connection setup documentation                 |

---

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-3   | Create dataset configurations in Superset         | Datasets for key tables                        |
| 3-6   | Configure column labels and descriptions          | User-friendly column names                     |
| 6-8   | Set up calculated columns and metrics             | Common metrics defined                         |

---

### Day 503: Data Warehouse Schema Design

**Objective:** Design and implement the star schema for the analytics data warehouse.

---

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create dimension tables                           | 6 dimension tables DDL                         |
| 4-8   | Create fact tables                                | 6 fact tables DDL                              |

**star_schema.sql:**
```sql
-- ============================================================
-- SMART DAIRY ANALYTICS - DATA WAREHOUSE STAR SCHEMA
-- ============================================================

-- Switch to data warehouse database
\c smartdairy_dw

-- ============================================================
-- DIMENSION TABLES
-- ============================================================

-- ============================================================
-- DIM_DATE - Date Dimension
-- ============================================================
CREATE TABLE dim_date (
    date_key INTEGER PRIMARY KEY,
    full_date DATE NOT NULL UNIQUE,
    year INTEGER NOT NULL,
    quarter INTEGER NOT NULL,
    month INTEGER NOT NULL,
    month_name VARCHAR(20) NOT NULL,
    month_name_bn VARCHAR(30),
    week_of_year INTEGER NOT NULL,
    day_of_month INTEGER NOT NULL,
    day_of_week INTEGER NOT NULL,
    day_name VARCHAR(20) NOT NULL,
    day_name_bn VARCHAR(30),
    is_weekend BOOLEAN NOT NULL,
    is_holiday BOOLEAN DEFAULT FALSE,
    holiday_name VARCHAR(100),
    fiscal_year INTEGER NOT NULL,
    fiscal_quarter INTEGER NOT NULL,
    fiscal_month INTEGER NOT NULL,
    season VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Populate date dimension (2020-2030)
INSERT INTO dim_date
SELECT
    TO_CHAR(datum, 'YYYYMMDD')::INTEGER AS date_key,
    datum AS full_date,
    EXTRACT(YEAR FROM datum) AS year,
    EXTRACT(QUARTER FROM datum) AS quarter,
    EXTRACT(MONTH FROM datum) AS month,
    TO_CHAR(datum, 'Month') AS month_name,
    CASE EXTRACT(MONTH FROM datum)
        WHEN 1 THEN 'জানুয়ারি'
        WHEN 2 THEN 'ফেব্রুয়ারি'
        WHEN 3 THEN 'মার্চ'
        WHEN 4 THEN 'এপ্রিল'
        WHEN 5 THEN 'মে'
        WHEN 6 THEN 'জুন'
        WHEN 7 THEN 'জুলাই'
        WHEN 8 THEN 'আগস্ট'
        WHEN 9 THEN 'সেপ্টেম্বর'
        WHEN 10 THEN 'অক্টোবর'
        WHEN 11 THEN 'নভেম্বর'
        WHEN 12 THEN 'ডিসেম্বর'
    END AS month_name_bn,
    EXTRACT(WEEK FROM datum) AS week_of_year,
    EXTRACT(DAY FROM datum) AS day_of_month,
    EXTRACT(DOW FROM datum) AS day_of_week,
    TO_CHAR(datum, 'Day') AS day_name,
    CASE EXTRACT(DOW FROM datum)
        WHEN 0 THEN 'রবিবার'
        WHEN 1 THEN 'সোমবার'
        WHEN 2 THEN 'মঙ্গলবার'
        WHEN 3 THEN 'বুধবার'
        WHEN 4 THEN 'বৃহস্পতিবার'
        WHEN 5 THEN 'শুক্রবার'
        WHEN 6 THEN 'শনিবার'
    END AS day_name_bn,
    CASE WHEN EXTRACT(DOW FROM datum) IN (5, 6) THEN TRUE ELSE FALSE END AS is_weekend,
    FALSE AS is_holiday,
    NULL AS holiday_name,
    CASE
        WHEN EXTRACT(MONTH FROM datum) >= 7
        THEN EXTRACT(YEAR FROM datum)
        ELSE EXTRACT(YEAR FROM datum) - 1
    END AS fiscal_year,
    CASE
        WHEN EXTRACT(MONTH FROM datum) BETWEEN 7 AND 9 THEN 1
        WHEN EXTRACT(MONTH FROM datum) BETWEEN 10 AND 12 THEN 2
        WHEN EXTRACT(MONTH FROM datum) BETWEEN 1 AND 3 THEN 3
        ELSE 4
    END AS fiscal_quarter,
    CASE
        WHEN EXTRACT(MONTH FROM datum) >= 7
        THEN EXTRACT(MONTH FROM datum) - 6
        ELSE EXTRACT(MONTH FROM datum) + 6
    END AS fiscal_month,
    CASE
        WHEN EXTRACT(MONTH FROM datum) BETWEEN 3 AND 5 THEN 'Summer'
        WHEN EXTRACT(MONTH FROM datum) BETWEEN 6 AND 9 THEN 'Monsoon'
        WHEN EXTRACT(MONTH FROM datum) BETWEEN 10 AND 11 THEN 'Autumn'
        ELSE 'Winter'
    END AS season
FROM generate_series('2020-01-01'::DATE, '2030-12-31'::DATE, '1 day') AS datum;

CREATE INDEX idx_dim_date_full ON dim_date(full_date);
CREATE INDEX idx_dim_date_year_month ON dim_date(year, month);
CREATE INDEX idx_dim_date_fiscal ON dim_date(fiscal_year, fiscal_quarter);

-- ============================================================
-- DIM_CUSTOMER - Customer Dimension
-- ============================================================
CREATE TABLE dim_customer (
    customer_key SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL,
    customer_code VARCHAR(50),
    customer_name VARCHAR(255) NOT NULL,
    customer_name_bn VARCHAR(255),
    customer_type VARCHAR(50),
    customer_tier VARCHAR(50),
    customer_segment VARCHAR(50),
    company_name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    mobile VARCHAR(50),
    address_street VARCHAR(255),
    address_city VARCHAR(100),
    address_district VARCHAR(100),
    address_division VARCHAR(100),
    address_country VARCHAR(100) DEFAULT 'Bangladesh',
    registration_date DATE,
    first_purchase_date DATE,
    last_purchase_date DATE,
    is_b2b BOOLEAN DEFAULT FALSE,
    is_b2c BOOLEAN DEFAULT TRUE,
    is_wholesale BOOLEAN DEFAULT FALSE,
    is_retail BOOLEAN DEFAULT FALSE,
    credit_limit NUMERIC(12,2),
    payment_terms VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_system VARCHAR(50) DEFAULT 'odoo',
    UNIQUE(customer_id, source_system)
);

CREATE INDEX idx_dim_customer_id ON dim_customer(customer_id);
CREATE INDEX idx_dim_customer_type ON dim_customer(customer_type);
CREATE INDEX idx_dim_customer_tier ON dim_customer(customer_tier);
CREATE INDEX idx_dim_customer_city ON dim_customer(address_city);

-- ============================================================
-- DIM_PRODUCT - Product Dimension
-- ============================================================
CREATE TABLE dim_product (
    product_key SERIAL PRIMARY KEY,
    product_id INTEGER NOT NULL,
    product_code VARCHAR(50),
    product_name VARCHAR(255) NOT NULL,
    product_name_bn VARCHAR(255),
    product_category VARCHAR(100),
    product_subcategory VARCHAR(100),
    product_type VARCHAR(50),
    brand VARCHAR(100),
    unit_of_measure VARCHAR(50),
    package_size NUMERIC(10,2),
    package_unit VARCHAR(20),
    weight_kg NUMERIC(10,3),
    shelf_life_days INTEGER,
    storage_temp_min NUMERIC(5,2),
    storage_temp_max NUMERIC(5,2),
    is_organic BOOLEAN DEFAULT FALSE,
    is_perishable BOOLEAN DEFAULT TRUE,
    standard_cost NUMERIC(12,4),
    list_price NUMERIC(12,4),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_system VARCHAR(50) DEFAULT 'odoo',
    UNIQUE(product_id, source_system)
);

CREATE INDEX idx_dim_product_id ON dim_product(product_id);
CREATE INDEX idx_dim_product_category ON dim_product(product_category);
CREATE INDEX idx_dim_product_type ON dim_product(product_type);

-- ============================================================
-- DIM_FARM - Farm Dimension
-- ============================================================
CREATE TABLE dim_farm (
    farm_key SERIAL PRIMARY KEY,
    farm_id INTEGER NOT NULL,
    farm_code VARCHAR(50),
    farm_name VARCHAR(255) NOT NULL,
    farm_name_bn VARCHAR(255),
    farm_type VARCHAR(50),
    farm_category VARCHAR(50),
    location_address VARCHAR(255),
    location_village VARCHAR(100),
    location_upazila VARCHAR(100),
    location_district VARCHAR(100),
    location_division VARCHAR(100),
    latitude NUMERIC(10,6),
    longitude NUMERIC(10,6),
    total_area_acres NUMERIC(10,2),
    barn_count INTEGER,
    paddock_count INTEGER,
    capacity_animals INTEGER,
    owner_name VARCHAR(255),
    contact_phone VARCHAR(50),
    registration_date DATE,
    is_certified_organic BOOLEAN DEFAULT FALSE,
    certification_date DATE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_system VARCHAR(50) DEFAULT 'odoo',
    UNIQUE(farm_id, source_system)
);

CREATE INDEX idx_dim_farm_id ON dim_farm(farm_id);
CREATE INDEX idx_dim_farm_type ON dim_farm(farm_type);
CREATE INDEX idx_dim_farm_district ON dim_farm(location_district);

-- ============================================================
-- DIM_CHANNEL - Sales Channel Dimension
-- ============================================================
CREATE TABLE dim_channel (
    channel_key SERIAL PRIMARY KEY,
    channel_code VARCHAR(50) NOT NULL UNIQUE,
    channel_name VARCHAR(100) NOT NULL,
    channel_name_bn VARCHAR(100),
    channel_type VARCHAR(50),
    channel_category VARCHAR(50),
    description TEXT,
    is_online BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert standard channels
INSERT INTO dim_channel (channel_code, channel_name, channel_name_bn, channel_type, channel_category, is_online) VALUES
('B2C_ECOM', 'B2C E-commerce', 'বি২সি ই-কমার্স', 'Direct', 'B2C', TRUE),
('B2C_MOBILE', 'B2C Mobile App', 'বি২সি মোবাইল অ্যাপ', 'Direct', 'B2C', TRUE),
('B2C_PHONE', 'B2C Phone Orders', 'বি২সি ফোন অর্ডার', 'Direct', 'B2C', FALSE),
('B2B_PORTAL', 'B2B Portal', 'বি২বি পোর্টাল', 'Wholesale', 'B2B', TRUE),
('B2B_DIRECT', 'B2B Direct Sales', 'বি২বি ডাইরেক্ট সেলস', 'Wholesale', 'B2B', FALSE),
('RETAIL_POS', 'Retail Point of Sale', 'রিটেইল পিওএস', 'Retail', 'Retail', FALSE),
('WHOLESALE', 'Wholesale Distribution', 'পাইকারি বিতরণ', 'Wholesale', 'Wholesale', FALSE),
('SUBSCRIPTION', 'Subscription Delivery', 'সাবস্ক্রিপশন ডেলিভারি', 'Direct', 'Subscription', TRUE);

-- ============================================================
-- DIM_SUPPLIER - Supplier Dimension
-- ============================================================
CREATE TABLE dim_supplier (
    supplier_key SERIAL PRIMARY KEY,
    supplier_id INTEGER NOT NULL,
    supplier_code VARCHAR(50),
    supplier_name VARCHAR(255) NOT NULL,
    supplier_name_bn VARCHAR(255),
    supplier_type VARCHAR(50),
    supplier_category VARCHAR(50),
    company_name VARCHAR(255),
    contact_name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    address_street VARCHAR(255),
    address_city VARCHAR(100),
    address_district VARCHAR(100),
    payment_terms VARCHAR(50),
    credit_limit NUMERIC(12,2),
    lead_time_days INTEGER,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_system VARCHAR(50) DEFAULT 'odoo',
    UNIQUE(supplier_id, source_system)
);

CREATE INDEX idx_dim_supplier_id ON dim_supplier(supplier_id);
CREATE INDEX idx_dim_supplier_type ON dim_supplier(supplier_type);

-- ============================================================
-- FACT TABLES
-- ============================================================

-- ============================================================
-- FACT_SALES - Sales Transactions
-- ============================================================
CREATE TABLE fact_sales (
    sale_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    customer_key INTEGER NOT NULL REFERENCES dim_customer(customer_key),
    product_key INTEGER NOT NULL REFERENCES dim_product(product_key),
    channel_key INTEGER REFERENCES dim_channel(channel_key),

    order_id INTEGER NOT NULL,
    order_line_id INTEGER NOT NULL,
    order_number VARCHAR(50),
    order_date TIMESTAMP,
    delivery_date TIMESTAMP,

    quantity NUMERIC(12,3) NOT NULL,
    unit_price NUMERIC(12,4) NOT NULL,
    discount_percent NUMERIC(5,2) DEFAULT 0,
    discount_amount NUMERIC(12,4) DEFAULT 0,
    tax_percent NUMERIC(5,2) DEFAULT 0,
    tax_amount NUMERIC(12,4) DEFAULT 0,
    gross_amount NUMERIC(14,4) NOT NULL,
    net_amount NUMERIC(14,4) NOT NULL,

    cost_amount NUMERIC(14,4),
    profit_amount NUMERIC(14,4),
    profit_margin_percent NUMERIC(5,2),

    payment_method VARCHAR(50),
    payment_status VARCHAR(50),
    delivery_status VARCHAR(50),
    is_returned BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_system VARCHAR(50) DEFAULT 'odoo',
    UNIQUE(order_line_id, source_system)
);

CREATE INDEX idx_fact_sales_date ON fact_sales(date_key);
CREATE INDEX idx_fact_sales_customer ON fact_sales(customer_key);
CREATE INDEX idx_fact_sales_product ON fact_sales(product_key);
CREATE INDEX idx_fact_sales_channel ON fact_sales(channel_key);
CREATE INDEX idx_fact_sales_order ON fact_sales(order_id);
CREATE INDEX idx_fact_sales_order_date ON fact_sales(order_date);

-- ============================================================
-- FACT_PRODUCTION - Milk Production
-- ============================================================
CREATE TABLE fact_production (
    production_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    farm_key INTEGER NOT NULL REFERENCES dim_farm(farm_key),

    animal_id INTEGER NOT NULL,
    animal_tag VARCHAR(50),
    session_type VARCHAR(20),

    milk_yield_liters NUMERIC(10,3) NOT NULL,
    fat_percent NUMERIC(5,2),
    protein_percent NUMERIC(5,2),
    lactose_percent NUMERIC(5,2),
    snf_percent NUMERIC(5,2),
    somatic_cell_count INTEGER,
    bacteria_count INTEGER,
    quality_grade VARCHAR(10),
    temperature_celsius NUMERIC(5,2),

    feed_cost NUMERIC(12,4),
    labor_cost NUMERIC(12,4),
    production_cost NUMERIC(12,4),
    cost_per_liter NUMERIC(10,4),

    production_timestamp TIMESTAMP NOT NULL,
    batch_id VARCHAR(50),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_system VARCHAR(50) DEFAULT 'odoo'
);

CREATE INDEX idx_fact_production_date ON fact_production(date_key);
CREATE INDEX idx_fact_production_farm ON fact_production(farm_key);
CREATE INDEX idx_fact_production_animal ON fact_production(animal_id);
CREATE INDEX idx_fact_production_timestamp ON fact_production(production_timestamp);

-- ============================================================
-- FACT_INVENTORY - Inventory Snapshots
-- ============================================================
CREATE TABLE fact_inventory (
    inventory_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    product_key INTEGER NOT NULL REFERENCES dim_product(product_key),

    warehouse_id INTEGER NOT NULL,
    warehouse_name VARCHAR(100),
    location_id INTEGER,

    opening_quantity NUMERIC(12,3) DEFAULT 0,
    received_quantity NUMERIC(12,3) DEFAULT 0,
    sold_quantity NUMERIC(12,3) DEFAULT 0,
    adjusted_quantity NUMERIC(12,3) DEFAULT 0,
    wasted_quantity NUMERIC(12,3) DEFAULT 0,
    transferred_in NUMERIC(12,3) DEFAULT 0,
    transferred_out NUMERIC(12,3) DEFAULT 0,
    closing_quantity NUMERIC(12,3) NOT NULL,

    unit_cost NUMERIC(12,4),
    total_value NUMERIC(14,4),

    lot_number VARCHAR(50),
    expiry_date DATE,
    days_to_expiry INTEGER,
    days_in_stock INTEGER,

    snapshot_timestamp TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_system VARCHAR(50) DEFAULT 'odoo'
);

CREATE INDEX idx_fact_inventory_date ON fact_inventory(date_key);
CREATE INDEX idx_fact_inventory_product ON fact_inventory(product_key);
CREATE INDEX idx_fact_inventory_warehouse ON fact_inventory(warehouse_id);
CREATE INDEX idx_fact_inventory_expiry ON fact_inventory(expiry_date);

-- ============================================================
-- FACT_CUSTOMER_ACTIVITY - Customer Behavior
-- ============================================================
CREATE TABLE fact_customer_activity (
    activity_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    customer_key INTEGER NOT NULL REFERENCES dim_customer(customer_key),

    order_count INTEGER DEFAULT 0,
    order_value NUMERIC(14,4) DEFAULT 0,
    item_count INTEGER DEFAULT 0,

    website_visits INTEGER DEFAULT 0,
    mobile_visits INTEGER DEFAULT 0,
    page_views INTEGER DEFAULT 0,
    session_duration_minutes INTEGER DEFAULT 0,

    cart_adds INTEGER DEFAULT 0,
    cart_value NUMERIC(14,4) DEFAULT 0,
    abandoned_carts INTEGER DEFAULT 0,

    email_received INTEGER DEFAULT 0,
    email_opened INTEGER DEFAULT 0,
    email_clicked INTEGER DEFAULT 0,

    support_tickets INTEGER DEFAULT 0,
    complaints INTEGER DEFAULT 0,
    returns INTEGER DEFAULT 0,

    nps_score INTEGER,
    satisfaction_score INTEGER,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_system VARCHAR(50) DEFAULT 'odoo'
);

CREATE INDEX idx_fact_activity_date ON fact_customer_activity(date_key);
CREATE INDEX idx_fact_activity_customer ON fact_customer_activity(customer_key);

-- ============================================================
-- FACT_FINANCIAL - Financial Transactions
-- ============================================================
CREATE TABLE fact_financial (
    financial_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),

    account_id INTEGER NOT NULL,
    account_code VARCHAR(50),
    account_name VARCHAR(255),
    account_type VARCHAR(50),

    journal_id INTEGER,
    journal_name VARCHAR(100),
    move_id INTEGER,
    move_name VARCHAR(100),

    debit_amount NUMERIC(14,4) DEFAULT 0,
    credit_amount NUMERIC(14,4) DEFAULT 0,
    balance NUMERIC(14,4) DEFAULT 0,

    partner_id INTEGER,
    product_id INTEGER,

    transaction_date TIMESTAMP,
    fiscal_year INTEGER,
    fiscal_period INTEGER,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_system VARCHAR(50) DEFAULT 'odoo'
);

CREATE INDEX idx_fact_financial_date ON fact_financial(date_key);
CREATE INDEX idx_fact_financial_account ON fact_financial(account_id);
CREATE INDEX idx_fact_financial_fiscal ON fact_financial(fiscal_year, fiscal_period);

-- ============================================================
-- FACT_SUPPLY_CHAIN - Procurement & Delivery
-- ============================================================
CREATE TABLE fact_supply_chain (
    supply_chain_key BIGSERIAL PRIMARY KEY,
    date_key INTEGER NOT NULL REFERENCES dim_date(date_key),
    supplier_key INTEGER REFERENCES dim_supplier(supplier_key),
    product_key INTEGER REFERENCES dim_product(product_key),

    purchase_order_id INTEGER,
    purchase_order_number VARCHAR(50),
    purchase_line_id INTEGER,

    ordered_quantity NUMERIC(12,3),
    received_quantity NUMERIC(12,3),
    rejected_quantity NUMERIC(12,3),

    unit_price NUMERIC(12,4),
    total_amount NUMERIC(14,4),

    order_date DATE,
    expected_date DATE,
    received_date DATE,
    lead_time_days INTEGER,
    on_time_delivery BOOLEAN,
    in_full_delivery BOOLEAN,

    quality_score INTEGER,
    delivery_status VARCHAR(50),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    source_system VARCHAR(50) DEFAULT 'odoo'
);

CREATE INDEX idx_fact_supply_date ON fact_supply_chain(date_key);
CREATE INDEX idx_fact_supply_supplier ON fact_supply_chain(supplier_key);
CREATE INDEX idx_fact_supply_product ON fact_supply_chain(product_key);

-- ============================================================
-- MATERIALIZED VIEWS FOR PERFORMANCE
-- ============================================================

-- Daily Sales Summary
CREATE MATERIALIZED VIEW mv_daily_sales_summary AS
SELECT
    d.date_key,
    d.full_date,
    d.year,
    d.month,
    d.month_name,
    d.day_of_week,
    d.day_name,
    ch.channel_name,
    p.product_category,
    COUNT(DISTINCT f.customer_key) AS customer_count,
    COUNT(DISTINCT f.order_id) AS order_count,
    SUM(f.quantity) AS total_quantity,
    SUM(f.gross_amount) AS gross_revenue,
    SUM(f.discount_amount) AS total_discount,
    SUM(f.tax_amount) AS total_tax,
    SUM(f.net_amount) AS net_revenue,
    SUM(f.profit_amount) AS total_profit,
    AVG(f.profit_margin_percent) AS avg_margin
FROM fact_sales f
JOIN dim_date d ON f.date_key = d.date_key
JOIN dim_product p ON f.product_key = p.product_key
LEFT JOIN dim_channel ch ON f.channel_key = ch.channel_key
WHERE f.is_returned = FALSE
GROUP BY d.date_key, d.full_date, d.year, d.month, d.month_name,
         d.day_of_week, d.day_name, ch.channel_name, p.product_category;

CREATE UNIQUE INDEX idx_mv_daily_sales ON mv_daily_sales_summary(date_key, channel_name, product_category);

-- Refresh function
CREATE OR REPLACE FUNCTION refresh_analytics_views()
RETURNS void AS $$
BEGIN
    REFRESH MATERIALIZED VIEW CONCURRENTLY mv_daily_sales_summary;
END;
$$ LANGUAGE plpgsql;
```

---

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create database indexes for query performance     | Index creation scripts                         |
| 4-8   | Set up database monitoring and logging            | Monitoring configuration                       |

---

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Configure datasets in Superset                    | Dataset definitions                            |
| 4-8   | Create calculated columns and metrics             | Metric definitions                             |

---

### Day 504: ETL Pipeline Development - Part 1

**Objective:** Build Apache Airflow DAGs for extracting data from Odoo and loading into the data warehouse.

---

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create Airflow DAG for sales data ETL             | Sales ETL DAG                                  |
| 4-8   | Create Airflow DAG for production data ETL        | Production ETL DAG                             |

---

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create Airflow DAG for inventory data ETL         | Inventory ETL DAG                              |
| 4-8   | Create Airflow DAG for customer data ETL          | Customer ETL DAG                               |

---

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create Airflow DAG for dimension tables           | Dimension sync DAGs                            |
| 4-8   | Test and validate ETL pipelines                   | Test results                                   |

---

### Day 505: ETL Pipeline Development - Part 2

**Objective:** Complete ETL pipelines and implement data quality checks.

---

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create financial data ETL DAG                     | Financial ETL DAG                              |
| 4-8   | Create supply chain data ETL DAG                  | Supply chain ETL DAG                           |

---

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Implement data quality validation rules           | DQ validation scripts                          |
| 4-8   | Set up ETL monitoring and alerting                | Monitoring configuration                       |

---

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create materialized view refresh DAG              | MV refresh DAG                                 |
| 4-8   | Document ETL processes                            | ETL documentation                              |

---

### Day 506: Role-Based Access Control

**Objective:** Implement comprehensive RBAC with 6 custom roles for Smart Dairy analytics.

---

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Define role hierarchy and permissions             | Role definitions document                      |
| 4-8   | Create custom security manager                    | Security manager code                          |

---

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Configure row-level security                      | RLS policies                                   |
| 4-8   | Implement role-based dataset access               | Dataset permissions                            |

---

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Configure dashboard access by role                | Dashboard permissions                          |
| 4-8   | Test role-based access scenarios                  | Test results                                   |

---

### Day 507: SSO Integration with Odoo

**Objective:** Implement Single Sign-On between Odoo and Apache Superset.

---

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create OAuth provider in Odoo                     | OAuth configuration                            |
| 4-8   | Implement JWT token generation                    | JWT implementation                             |

---

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Configure Superset OAuth client                   | OAuth client config                            |
| 4-8   | Implement user synchronization                    | User sync logic                                |

---

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create login flow UI                              | Login UI components                            |
| 4-8   | Test SSO integration end-to-end                   | Test results                                   |

---

### Day 508: Dashboard Template Library

**Objective:** Create reusable dashboard and chart templates for consistent analytics design.

---

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create SQL query templates for common analytics   | Query template library                         |
| 4-8   | Create virtual datasets for aggregations          | Virtual datasets                               |

---

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create dashboard layout templates                 | Layout templates                               |
| 4-8   | Configure filter templates                        | Filter configurations                          |

---

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Create chart type templates                       | Chart templates                                |
| 4-8   | Create KPI card templates                         | KPI templates                                  |

---

### Day 509: Query Optimization & Caching

**Objective:** Optimize query performance and implement caching strategies.

---

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Analyze slow queries and optimize                 | Query optimization report                      |
| 4-8   | Create additional materialized views              | Performance-optimized views                    |

---

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Configure Redis caching policies                  | Cache configuration                            |
| 4-8   | Implement cache warming strategies                | Cache warming DAG                              |

---

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-4   | Optimize dashboard rendering                      | UI performance improvements                    |
| 4-8   | Test dashboard load times                         | Performance test results                       |

---

### Day 510: Testing & Documentation

**Objective:** Complete testing and documentation for Milestone 101.

---

#### Dev 1 (Backend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Run data accuracy validation tests                | Validation test results                        |
| 2-4   | Verify ETL pipeline reliability                   | ETL test results                               |
| 4-6   | Create technical documentation                    | Technical docs                                 |
| 6-8   | Review and approve milestone deliverables         | Milestone review                               |

---

#### Dev 2 (Full-Stack) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Run security and access control tests             | Security test results                          |
| 2-4   | Perform load testing                              | Load test results                              |
| 4-6   | Create admin documentation                        | Admin guide                                    |
| 6-8   | Prepare Milestone 101 sign-off                    | Sign-off document                              |

---

#### Dev 3 (Frontend Lead) — 8 hours

| Hours | Task                                              | Deliverable                                    |
|-------|---------------------------------------------------|------------------------------------------------|
| 0-2   | Run UI/UX testing                                 | UI test results                                |
| 2-4   | Test template usability                           | Usability test results                         |
| 4-6   | Create user documentation                         | User guide                                     |
| 6-8   | Demo Milestone 101 to stakeholders                | Demo completion                                |

---

## 6. Technical Specifications

### 6.1 Architecture Components

```
┌─────────────────────────────────────────────────────────────────────┐
│                        SMART DAIRY BI PLATFORM                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐             │
│  │   Nginx     │    │  Superset   │    │   Celery    │             │
│  │  (Reverse   │───▶│    Web      │◀──▶│  Workers    │             │
│  │   Proxy)    │    │   Server    │    │  (Async)    │             │
│  └─────────────┘    └─────────────┘    └─────────────┘             │
│         │                  │                  │                     │
│         │                  │                  │                     │
│         ▼                  ▼                  ▼                     │
│  ┌─────────────────────────────────────────────────────────┐       │
│  │                         REDIS                            │       │
│  │              (Cache + Celery Broker)                     │       │
│  └─────────────────────────────────────────────────────────┘       │
│                              │                                      │
│         ┌────────────────────┼────────────────────┐                │
│         │                    │                    │                 │
│         ▼                    ▼                    ▼                 │
│  ┌────────────┐      ┌────────────┐      ┌────────────┐           │
│  │ PostgreSQL │      │ PostgreSQL │      │TimescaleDB │           │
│  │   (Meta)   │      │   (DW)     │      │   (IoT)    │           │
│  └────────────┘      └────────────┘      └────────────┘           │
│                              │                    │                 │
│                              │                    │                 │
│  ┌─────────────────────────────────────────────────────────┐       │
│  │                     APACHE AIRFLOW                       │       │
│  │                    (ETL Orchestration)                   │       │
│  └─────────────────────────────────────────────────────────┘       │
│                              │                                      │
│                              ▼                                      │
│  ┌─────────────────────────────────────────────────────────┐       │
│  │                     ODOO DATABASE                        │       │
│  │                  (Source Data)                          │       │
│  └─────────────────────────────────────────────────────────┘       │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

### 6.2 Data Models

See star_schema.sql in Day 503 section for complete data model definitions.

### 6.3 API Endpoints

| Endpoint                          | Method | Description                       |
|-----------------------------------|--------|-----------------------------------|
| `/api/v1/dashboard/`              | GET    | List dashboards                   |
| `/api/v1/dashboard/{id}`          | GET    | Get dashboard details             |
| `/api/v1/chart/`                  | GET    | List charts                       |
| `/api/v1/chart/{id}/data`         | GET    | Get chart data                    |
| `/api/v1/dataset/`                | GET    | List datasets                     |
| `/api/v1/database/`               | GET    | List database connections         |
| `/api/v1/security/login`          | POST   | User authentication               |
| `/api/v1/report/`                 | GET    | List scheduled reports            |

### 6.4 Configuration Files

All configuration files are detailed in the Day-by-Day section above.

---

## 7. Testing & Validation

### 7.1 Unit Tests

| Test Category              | Test Cases                                        | Tool      |
|----------------------------|---------------------------------------------------|-----------|
| ETL Functions              | Data extraction, transformation, loading          | pytest    |
| Data Quality               | Completeness, accuracy, consistency               | pytest    |
| Security Functions         | Authentication, authorization                     | pytest    |

### 7.2 Integration Tests

| Test Category              | Test Cases                                        | Tool      |
|----------------------------|---------------------------------------------------|-----------|
| Database Connections       | All data sources accessible                       | pytest    |
| ETL Pipeline               | End-to-end data flow                              | Airflow   |
| SSO Integration            | Login flow, token validation                      | pytest    |
| API Endpoints              | All endpoints functional                          | Postman   |

### 7.3 UAT Criteria

| Criterion                  | Acceptance Criteria                               |
|----------------------------|---------------------------------------------------|
| Platform Accessibility     | Superset accessible via configured URL            |
| Data Connectivity          | All data sources connected and verified           |
| User Authentication        | SSO login working from Odoo                       |
| Role-Based Access          | All 6 roles functioning correctly                 |
| ETL Reliability            | DAGs running on schedule with > 99% success       |
| Query Performance          | Baseline queries < 500ms                          |

---

## 8. Risk & Mitigation

| Risk ID | Risk Description                              | Impact | Probability | Mitigation                                    |
|---------|-----------------------------------------------|--------|-------------|-----------------------------------------------|
| R101-01 | Superset installation issues                  | High   | Low         | Use official Docker images; follow docs       |
| R101-02 | Database connection failures                  | High   | Medium      | Test connections early; have fallback plan    |
| R101-03 | ETL pipeline failures                         | High   | Medium      | Implement retry logic; monitoring             |
| R101-04 | SSO integration complexity                    | Medium | Medium      | Allocate buffer time; document thoroughly     |
| R101-05 | Performance issues at scale                   | Medium | Medium      | Load test early; implement caching            |

---

## 9. Dependencies & Handoffs

### 9.1 Prerequisites from Previous Phases

| Dependency                        | Source      | Status    |
|-----------------------------------|-------------|-----------|
| Odoo PostgreSQL database access   | Phase 1     | Required  |
| TimescaleDB IoT database          | Phase 10    | Required  |
| Docker infrastructure             | Phase 1     | Required  |
| Network configuration             | Phase 1     | Required  |
| SSL certificates                  | Phase 1     | Required  |

### 9.2 Deliverables to Next Milestone

| Deliverable                       | Recipient           | Purpose                           |
|-----------------------------------|---------------------|-----------------------------------|
| Configured Superset platform      | Milestone 102       | Dashboard development             |
| Data warehouse with star schema   | Milestones 102-108  | Analytics data source             |
| ETL pipelines                     | Milestones 102-108  | Data refresh                      |
| Dashboard templates               | Milestone 102       | Executive dashboard creation      |
| Role-based access                 | Milestones 102-110  | User access control               |

---

**END OF MILESTONE 101 DOCUMENT**

---

*Document prepared for Smart Dairy Digital Smart Portal + ERP System*
*Milestone 101: BI Platform Setup*
*Days 501–510 (10 working days)*
*Version 1.0 — 2026-02-04*
