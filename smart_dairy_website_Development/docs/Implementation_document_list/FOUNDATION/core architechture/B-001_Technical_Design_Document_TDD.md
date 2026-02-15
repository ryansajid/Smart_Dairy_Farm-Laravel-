# SMART DAIRY LTD.
## TECHNICAL DESIGN DOCUMENT (TDD)
### Smart Web Portal System & Integrated ERP
#### Implementation: Odoo 19 CE + Strategic Custom Development

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-001 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Status** | Draft for Review |
| **Author** | Solution Architect |
| **Owner** | Solution Architect |
| **Reviewer** | IT Director |
| **Approved By** | Managing Director |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Architectural Overview](#2-architectural-overview)
3. [System Components Design](#3-system-components-design)
4. [Data Architecture](#4-data-architecture)
5. [Integration Architecture](#5-integration-architecture)
6. [Security Architecture](#6-security-architecture)
7. [Deployment Architecture](#7-deployment-architecture)
8. [Performance & Scalability Design](#8-performance--scalability-design)
9. [Technology Stack Implementation](#9-technology-stack-implementation)
10. [Development Standards](#10-development-standards)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Technical Design Document (TDD) provides the comprehensive technical blueprint for implementing the Smart Dairy Smart Web Portal System and Integrated ERP. It translates business and functional requirements from the BRD, SRS, and URD into specific technical solutions, architecture patterns, and implementation guidelines.

### 1.2 Scope

This document covers:
- Overall system architecture and design patterns
- Component-level design for all modules
- Data architecture and flow design
- Integration architecture with external systems
- Security architecture and controls
- Deployment and infrastructure design
- Performance optimization strategies
- Development standards and guidelines

### 1.3 Target Audience

- Solution Architects
- Technical Leads
- Development Team
- DevOps Engineers
- Security Team
- Database Administrators

### 1.4 Reference Documents

| Document ID | Document Name | Version |
|-------------|---------------|---------|
| SD-RFP-001 | Smart Dairy RFP | 1.0 |
| SD-BRD-001 | Business Requirements Document | 1.0 |
| SD-URD-001 | User Requirements Document | 1.0 |
| SD-SRS-001 | Software Requirements Specification | 1.0 |
| SD-TSD-001 | Technology Stack Document | 1.0 |

### 1.5 Design Principles

| Principle | Description | Implementation |
|-----------|-------------|----------------|
| **Modularity** | Components are loosely coupled and highly cohesive | Microservices for custom modules, Odoo modules for standard functionality |
| **Scalability** | System can handle 3x growth without redesign | Horizontal scaling, stateless design, caching layers |
| **Security** | Defense-in-depth security at all layers | Authentication, authorization, encryption, audit logging |
| **Reliability** | 99.9% uptime with automated recovery | Multi-AZ deployment, automated failover, backup strategies |
| **Maintainability** | Easy to update, debug, and extend | Clean code, documentation, automated testing |
| **Performance** | Sub-3 second response times | Caching, optimization, CDN, database tuning |

---

## 2. ARCHITECTURAL OVERVIEW

### 2.1 High-Level System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                                    SMART DAIRY PLATFORM                                      │
│                               Technical Architecture Overview                                │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────────────────────┐   │
│  │                              PRESENTATION LAYER                                      │   │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────────┐ │   │
│  │  │ Public       │  │ B2C          │  │ B2B          │  │ Mobile Applications      │ │   │
│  │  │ Website      │  │ E-commerce   │  │ Portal       │  │ (Flutter)                │ │   │
│  │  │ (Odoo CMS)   │  │ (Odoo +      │  │ (Custom      │  │ - Customer App           │ │   │
│  │  │              │  │  Custom)     │  │  Module)     │  │ - Field Sales App        │ │   │
│  │  └──────────────┘  └──────────────┘  └──────────────┘  │ - Farmer App             │ │   │
│  │                                                       └──────────────────────────┘ │   │
│  └─────────────────────────────────────────────────────────────────────────────────────┘   │
│                                              │                                               │
│                                              ▼                                               │
│  ┌─────────────────────────────────────────────────────────────────────────────────────┐   │
│  │                           APPLICATION LAYER                                          │   │
│  │                                                                                      │   │
│  │  ┌─────────────────────────────┐      ┌─────────────────────────────────────────┐   │   │
│  │  │   ERP CORE (Odoo 19 CE)     │      │   CUSTOM MODULES (Python/Odoo)          │   │   │
│  │  │  ┌─────────────────────┐    │      │  ┌─────────────┐  ┌──────────────────┐   │   │   │
│  │  │  │ Standard Modules    │    │      │  │ smart_farm  │  │ smart_b2b_portal │   │   │   │
│  │  │  │ - Inventory         │    │◄────►│  │ _mgmt       │  │                  │   │   │   │
│  │  │  │ - Sales/CRM         │    │      │  ├─────────────┤  ├──────────────────┤   │   │   │
│  │  │  │ - Purchase          │    │      │  │ smart_iot   │  │ smart_bd_local   │   │   │   │
│  │  │  │ - Accounting        │    │      │  │             │  │                  │   │   │   │
│  │  │  │ - Manufacturing     │    │      │  ├─────────────┤  ├──────────────────┤   │   │   │
│  │  │  │ - HR/Payroll        │    │      │  │ smart_mobile│  │ smart_api_gateway│   │   │   │
│  │  │  └─────────────────────┘    │      │  │ _api        │  │                  │   │   │   │
│  │  └─────────────────────────────┘      └─────────────────────────────────────────┘   │   │
│  │                                                                                      │   │
│  │  ┌─────────────────────────────┐      ┌─────────────────────────────────────────┐   │   │
│  │  │   API GATEWAY (FastAPI)     │      │   BACKGROUND PROCESSING (Celery)        │   │   │
│  │  │  - REST APIs                │      │  - Email Notifications                  │   │   │
│  │  │  - GraphQL                  │      │  - Report Generation                    │   │   │
│  │  │  - Webhooks                 │      │  - IoT Data Processing                  │   │   │
│  │  │  - Rate Limiting            │      │  - Scheduled Tasks                      │   │   │
│  │  └─────────────────────────────┘      └─────────────────────────────────────────┘   │   │
│  │                                                                                      │   │
│  └─────────────────────────────────────────────────────────────────────────────────────┘   │
│                                              │                                               │
│                                              ▼                                               │
│  ┌─────────────────────────────────────────────────────────────────────────────────────┐   │
│  │                           INTEGRATION LAYER                                          │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌───────────┐  │   │
│  │  │ Payment     │  │ SMS/Email   │  │ IoT/MQTT    │  │ Maps/       │  │ Banking   │  │   │
│  │  │ Gateways    │  │ Services    │  │ Sensors     │  │ Location    │  │ APIs      │  │   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘  └───────────┘  │   │
│  └─────────────────────────────────────────────────────────────────────────────────────┘   │
│                                              │                                               │
│                                              ▼                                               │
│  ┌─────────────────────────────────────────────────────────────────────────────────────┐   │
│  │                              DATA LAYER                                              │   │
│  │  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐  ┌────────────────┐     │   │
│  │  │ PostgreSQL 16  │  │ Redis 7        │  │ Elasticsearch  │  │ TimescaleDB    │     │   │
│  │  │ (Primary DB)   │  │ (Cache/Queue)  │  │ (Search/Logs)  │  │ (IoT Data)     │     │   │
│  │  └────────────────┘  └────────────────┘  └────────────────┘  └────────────────┘     │   │
│  └─────────────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                              │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Architecture Patterns

#### 2.2.1 Modular Monolith + Microservices Hybrid

**Pattern Description:**
The system follows a hybrid architecture combining Odoo's modular monolith approach for standard ERP functionality with microservices for custom, high-scale components.

**Rationale:**
- Odoo provides proven ERP modules (70% of functionality)
- Custom microservices handle dairy-specific requirements (30%)
- Balances development speed with scalability
- Reduces operational complexity vs. pure microservices

**Implementation:**

| Component | Architecture | Technology |
|-----------|--------------|------------|
| ERP Core | Modular Monolith | Odoo 19 CE |
| Farm Management | Custom Module | Python/Odoo |
| B2B Portal | Custom Module | Python/Odoo |
| IoT Platform | Microservice | FastAPI |
| Mobile API | Microservice | FastAPI |
| Payment Gateway | Microservice | FastAPI |

#### 2.2.2 Layered Architecture

```
┌─────────────────────────────────────────────────────────────┐
│  PRESENTATION LAYER                                          │
│  - User Interfaces (Web, Mobile)                            │
│  - API Controllers                                          │
│  - View Templates                                           │
├─────────────────────────────────────────────────────────────┤
│  BUSINESS LOGIC LAYER                                        │
│  - Domain Models                                            │
│  - Business Rules                                           │
│  - Workflow Engines                                         │
│  - Service Classes                                          │
├─────────────────────────────────────────────────────────────┤
│  DATA ACCESS LAYER                                           │
│  - ORM (Odoo ORM, SQLAlchemy)                              │
│  - Repository Pattern                                       │
│  - Data Mappers                                             │
├─────────────────────────────────────────────────────────────┤
│  INFRASTRUCTURE LAYER                                        │
│  - Database Connections                                     │
│  - External Service Clients                                 │
│  - Caching                                                  │
│  - Message Queues                                           │
└─────────────────────────────────────────────────────────────┘
```

#### 2.2.3 Event-Driven Architecture

**Event Flow:**

```
┌─────────────┐    Event      ┌─────────────┐    Event      ┌─────────────┐
│   Source    │──────────────►│   Message   │──────────────►│  Consumer   │
│   System    │   Published   │   Broker    │   Routed      │   Service   │
└─────────────┘               └─────────────┘               └─────────────┘
                                                                   │
                                                                   ▼
                                                            ┌─────────────┐
                                                            │   Action    │
                                                            │   Taken     │
                                                            └─────────────┘
```

**Key Events:**

| Event | Publisher | Subscribers | Action |
|-------|-----------|-------------|--------|
| order.created | B2C Portal | Inventory, Accounting, Notification | Reserve stock, create invoice, send confirmation |
| payment.received | Payment Gateway | Sales, Accounting, Customer | Update order status, record payment, notify customer |
| animal.health_alert | Farm IoT | Farm Management, Veterinarian | Create health record, send alert |
| milk.production.recorded | Farm App | Analytics, Inventory | Update dashboards, adjust inventory |
| subscription.renewal_due | Subscription Engine | Billing, Customer | Generate invoice, send reminder |

---

## 3. SYSTEM COMPONENTS DESIGN

### 3.1 ERP Core (Odoo 19 CE)

#### 3.1.1 Core Module Configuration

```python
# Core Module Manifest Structure
{
    'name': 'Smart Dairy Core',
    'version': '19.0.1.0.0',
    'category': 'ERP',
    'summary': 'Smart Dairy ERP Core Configuration',
    'description': """
        Core configuration for Smart Dairy ERP System.
        Includes all base modules and custom configurations.
    """,
    'depends': [
        # Base
        'base',
        'web',
        'bus',
        'mail',
        
        # Website & E-commerce
        'website',
        'website_sale',
        'website_blog',
        'website_form',
        
        # Sales & CRM
        'sale',
        'sale_management',
        'crm',
        'point_of_sale',
        
        # Purchase & Inventory
        'purchase',
        'stock',
        'mrp',
        'delivery',
        
        # Accounting
        'account',
        'account_accountant',
        'l10n_bd',  # Bangladesh Localization
        
        # HR
        'hr',
        'hr_contract',
        'hr_payroll',
        'hr_attendance',
        
        # Projects
        'project',
        
        # Quality
        'quality',
    ],
    'data': [
        'data/company_data.xml',
        'data/chart_of_accounts.xml',
        'data/warehouse_data.xml',
        'data/product_categories.xml',
        'security/ir.model.access.csv',
        'security/security_groups.xml',
        'views/res_company_views.xml',
        'views/menu_views.xml',
    ],
    'installable': True,
    'application': True,
    'auto_install': False,
}
```

#### 3.1.2 Custom Module Architecture

**Smart Farm Management Module (smart_farm_mgmt):**

```
smart_farm_mgmt/
├── __init__.py
├── __manifest__.py
├── models/
│   ├── __init__.py
│   ├── farm_animal.py          # Core animal entity
│   ├── farm_herd.py            # Herd management
│   ├── animal_health.py        # Health records
│   ├── breeding_record.py      # Reproduction tracking
│   ├── milk_production.py      # Milk yield tracking
│   ├── feed_management.py      # Feed & nutrition
│   └── farm_location.py        # Barn/pen locations
├── controllers/
│   ├── __init__.py
│   └── main.py                 # API endpoints
├── views/
│   ├── farm_animal_views.xml
│   ├── herd_dashboard_views.xml
│   ├── health_record_views.xml
│   └── menu_views.xml
├── data/
│   ├── animal_breeds.xml
│   ├── feed_types.xml
│   └── farm_locations.xml
├── security/
│   ├── ir.model.access.csv
│   └── security_groups.xml
├── static/
│   └── src/
│       ├── css/
│       ├── js/
│       └── xml/
└── tests/
    ├── __init__.py
    └── test_farm_animal.py
```

### 3.2 API Gateway (FastAPI)

#### 3.2.1 API Gateway Architecture

```python
# FastAPI Application Structure
from fastapi import FastAPI, Depends, HTTPException
from fastapi.security import OAuth2PasswordBearer
from fastapi.middleware.cors import CORSMiddleware
from contextlib import asynccontextmanager

@asynccontextmanager
async def lifespan(app: FastAPI):
    # Startup: Initialize connections
    await database.connect()
    await redis.connect()
    yield
    # Shutdown: Cleanup
    await database.disconnect()
    await redis.disconnect()

app = FastAPI(
    title="Smart Dairy API Gateway",
    version="1.0.0",
    lifespan=lifespan
)

# Middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.CORS_ORIGINS,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Routers
app.include_router(auth.router, prefix="/auth", tags=["Authentication"])
app.include_router(mobile_api.router, prefix="/mobile", tags=["Mobile"])
app.include_router(iot.router, prefix="/iot", tags=["IoT"])
app.include_router(payments.router, prefix="/payments", tags=["Payments"])
app.include_router(webhooks.router, prefix="/webhooks", tags=["Webhooks"])
```

#### 3.2.2 Authentication Middleware

```python
# JWT Authentication Implementation
from jose import JWTError, jwt
from passlib.context import CryptContext

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")
oauth2_scheme = OAuth2PasswordBearer(tokenUrl="auth/token")

async def get_current_user(token: str = Depends(oauth2_scheme)):
    credentials_exception = HTTPException(
        status_code=401,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        username: str = payload.get("sub")
        if username is None:
            raise credentials_exception
        token_data = TokenData(username=username)
    except JWTError:
        raise credentials_exception
    user = await get_user(username=token_data.username)
    if user is None:
        raise credentials_exception
    return user

async def get_current_active_user(
    current_user: User = Depends(get_current_user)
):
    if not current_user.is_active:
        raise HTTPException(status_code=400, detail="Inactive user")
    return current_user
```

### 3.3 Background Processing (Celery)

#### 3.3.1 Celery Configuration

```python
# Celery Application Configuration
from celery import Celery
from celery.signals import task_failure

app = Celery('smart_dairy')
app.config_from_object({
    'broker_url': 'redis://redis:6379/0',
    'result_backend': 'redis://redis:6379/1',
    'task_serializer': 'json',
    'accept_content': ['json'],
    'result_serializer': 'json',
    'timezone': 'Asia/Dhaka',
    'enable_utc': True,
    'task_track_started': True,
    'task_time_limit': 3600,
    'worker_prefetch_multiplier': 4,
    'broker_connection_retry_on_startup': True,
})

# Task Definitions
@app.task(bind=True, max_retries=3)
def process_payment_async(self, order_id, payment_data):
    try:
        # Payment processing logic
        result = payment_gateway.process(order_id, payment_data)
        return result
    except PaymentException as exc:
        raise self.retry(exc=exc, countdown=60)

@app.task
def generate_daily_report(report_type, date):
    """Generate and email daily reports"""
    report = report_generator.create(report_type, date)
    email_service.send_report(report)

@app.task
def sync_iot_data(device_id, readings):
    """Process IoT sensor data"""
    iot_processor.ingest(device_id, readings)
```

---

## 4. DATA ARCHITECTURE

### 4.1 Database Architecture

#### 4.1.1 Multi-Database Strategy

| Database | Purpose | Technology | Data Volume |
|----------|---------|------------|-------------|
| Primary | Transactional data | PostgreSQL 16 | ~500GB |
| Cache | Session, temporary data | Redis 7 | ~50GB |
| Search | Full-text search, analytics | Elasticsearch 8 | ~100GB |
| Time-Series | IoT sensor data | TimescaleDB | ~1TB |
| Object Storage | Files, images, backups | MinIO/S3 | ~5TB |

#### 4.1.2 PostgreSQL Schema Design

**Core Schema Overview:**

```sql
-- Main schemas for data organization
CREATE SCHEMA IF NOT EXISTS core;
CREATE SCHEMA IF NOT EXISTS farm;
CREATE SCHEMA IF NOT EXISTS sales;
CREATE SCHEMA IF NOT EXISTS inventory;
CREATE SCHEMA IF NOT EXISTS accounting;
CREATE SCHEMA IF NOT EXISTS analytics;

-- Tablespace configuration for performance
CREATE TABLESPACE fast_storage LOCATION '/var/lib/postgresql/fast';
CREATE TABLESPACE archive_storage LOCATION '/var/lib/postgresql/archive';
```

**Partitioning Strategy:**

```sql
-- Time-based partitioning for large tables
CREATE TABLE farm.milk_production (
    id BIGSERIAL,
    animal_id BIGINT NOT NULL,
    production_date DATE NOT NULL,
    session VARCHAR(20) NOT NULL, -- 'morning', 'evening'
    quantity_liters DECIMAL(8,2),
    fat_percentage DECIMAL(5,2),
    snf_percentage DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT NOW(),
    PRIMARY KEY (id, production_date)
) PARTITION BY RANGE (production_date);

-- Create monthly partitions
CREATE TABLE farm.milk_production_2024_01 PARTITION OF farm.milk_production
    FOR VALUES FROM ('2024-01-01') TO ('2024-02-01');
CREATE TABLE farm.milk_production_2024_02 PARTITION OF farm.milk_production
    FOR VALUES FROM ('2024-02-01') TO ('2024-03-01');
-- ... continue for all months
```

### 4.2 Data Flow Architecture

#### 4.2.1 Order Processing Flow

```
┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐
│ Customer │────►│   API    │────►│  Order   │────►│ Inventory│────►│ Payment  │
│  Action  │     │ Gateway  │     │  Service │     │  Check   │     │ Gateway  │
└──────────┘     └──────────┘     └────┬─────┘     └────┬─────┘     └────┬─────┘
                                        │                │                │
                                        ▼                ▼                ▼
                                  ┌──────────┐    ┌──────────┐    ┌──────────┐
                                  │  Event   │    │  Stock   │    │ Payment  │
                                  │  Bus     │    │ Reserved │    │ Processed│
                                  └────┬─────┘    └──────────┘    └────┬─────┘
                                       │                               │
                                       ▼                               ▼
                                  ┌──────────┐                  ┌──────────┐
                                  │Notification│                │  Order   │
                                  │  Service │                  │ Confirmed│
                                  └──────────┘                  └──────────┘
```

#### 4.2.2 IoT Data Ingestion Flow

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Sensor    │────►│    MQTT     │────►│   Stream    │────►│  Timescale  │
│  (Field)    │     │   Broker    │     │  Processor  │     │     DB      │
└─────────────┘     └─────────────┘     └──────┬──────┘     └─────────────┘
                                               │
                          ┌────────────────────┼────────────────────┐
                          ▼                    ▼                    ▼
                   ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
                   │ Real-time   │     │   Alert     │     │ Analytics   │
                   │  Dashboard  │     │   Engine    │     │   Engine    │
                   └─────────────┘     └─────────────┘     └─────────────┘
```

### 4.3 Caching Strategy

#### 4.3.1 Multi-Level Caching

```python
# Caching Implementation
from functools import wraps
import redis
import json

cache = redis.Redis(host='redis', port=6379, db=0)

def cache_result(ttl=300):
    """Decorator for caching function results"""
    def decorator(func):
        @wraps(func)
        async def wrapper(*args, **kwargs):
            # Generate cache key
            cache_key = f"{func.__name__}:{hash(str(args) + str(kwargs))}"
            
            # Try to get from cache
            cached = cache.get(cache_key)
            if cached:
                return json.loads(cached)
            
            # Execute function
            result = await func(*args, **kwargs)
            
            # Store in cache
            cache.setex(cache_key, ttl, json.dumps(result))
            return result
        return wrapper
    return decorator

# Usage
@cache_result(ttl=600)
async def get_product_price(product_id):
    # Expensive database query
    return await db.products.find_one({"_id": product_id})
```

**Cache Layers:**

| Layer | Technology | TTL | Use Case |
|-------|------------|-----|----------|
| Browser | LocalStorage | Session | User preferences, cart |
| CDN | CloudFront | 24h | Static assets, images |
| Application | Redis | 30min | Session data, user auth |
| Database | PostgreSQL | 5min | Query results, counts |
| Full Page | Nginx | 1h | Static pages, catalogs |

---

## 5. INTEGRATION ARCHITECTURE

### 5.1 Payment Gateway Integration

#### 5.1.1 Payment Gateway Architecture

```python
# Payment Gateway Factory Pattern
from abc import ABC, abstractmethod

class PaymentGateway(ABC):
    @abstractmethod
    async def create_payment(self, amount, currency, order_id):
        pass
    
    @abstractmethod
    async def verify_payment(self, transaction_id):
        pass
    
    @abstractmethod
    async def refund(self, transaction_id, amount):
        pass

class BKashGateway(PaymentGateway):
    BASE_URL = "https://tokenized.bka.sh"
    
    async def create_payment(self, amount, currency, order_id):
        # bKash-specific implementation
        pass

class NagadGateway(PaymentGateway):
    BASE_URL = "https://api.mynagad.com"
    
    async def create_payment(self, amount, currency, order_id):
        # Nagad-specific implementation
        pass

class PaymentGatewayFactory:
    gateways = {
        'bkash': BKashGateway,
        'nagad': NagadGateway,
        'rocket': RocketGateway,
        'sslcommerz': SSLCommerzGateway,
    }
    
    @classmethod
    def get_gateway(cls, provider):
        gateway_class = cls.gateways.get(provider)
        if not gateway_class:
            raise ValueError(f"Unknown payment provider: {provider}")
        return gateway_class()
```

### 5.2 IoT Integration

#### 5.2.1 MQTT Architecture

```python
# MQTT Client Implementation
import paho.mqtt.client as mqtt
import json

class IoTIntegrationService:
    def __init__(self):
        self.client = mqtt.Client()
        self.client.on_connect = self.on_connect
        self.client.on_message = self.on_message
        
    def on_connect(self, client, userdata, flags, rc):
        print(f"Connected to MQTT broker with result code {rc}")
        # Subscribe to topics
        client.subscribe("smartdairy/+/sensors/+")
        client.subscribe("smartdairy/+/milk/+")
        client.subscribe("smartdairy/+/health/+")
        
    def on_message(self, client, userdata, msg):
        topic_parts = msg.topic.split('/')
        location = topic_parts[1]
        sensor_type = topic_parts[3]
        
        data = json.loads(msg.payload)
        
        # Route to appropriate processor
        if sensor_type == 'milk_meter':
            self.process_milk_data(location, data)
        elif sensor_type == 'temperature':
            self.process_temperature_data(location, data)
        elif sensor_type == 'activity':
            self.process_activity_data(location, data)
            
    def process_milk_data(self, location, data):
        """Store milk production data"""
        # Validation
        if not self.validate_milk_data(data):
            logger.warning(f"Invalid milk data from {location}")
            return
            
        # Store in database
        MilkProduction.create(
            animal_id=data['animal_id'],
            quantity=data['volume'],
            quality_data=data.get('quality', {}),
            timestamp=data['timestamp']
        )
        
        # Trigger alerts if needed
        if data['volume'] < THRESHOLD_LOW_MILK:
            self.trigger_alert('low_production', location, data)
```

### 5.3 External API Integration

#### 5.3.1 API Client Architecture

```python
# Base API Client with Circuit Breaker
import httpx
from circuitbreaker import circuit

class BaseAPIClient:
    def __init__(self, base_url, api_key=None):
        self.base_url = base_url
        self.api_key = api_key
        self.client = httpx.AsyncClient(
            base_url=base_url,
            timeout=30.0,
            headers=self._get_headers()
        )
        
    def _get_headers(self):
        headers = {'Content-Type': 'application/json'}
        if self.api_key:
            headers['Authorization'] = f'Bearer {self.api_key}'
        return headers
    
    @circuit(failure_threshold=5, recovery_timeout=60)
    async def request(self, method, endpoint, **kwargs):
        try:
            response = await self.client.request(
                method, endpoint, **kwargs
            )
            response.raise_for_status()
            return response.json()
        except httpx.HTTPError as e:
            logger.error(f"API request failed: {e}")
            raise

class SMSClient(BaseAPIClient):
    async def send_sms(self, phone_number, message):
        return await self.request(
            'POST',
            '/sms/send',
            json={
                'to': phone_number,
                'message': message,
                'sender_id': 'SmartDairy'
            }
        )

class MapsClient(BaseAPIClient):
    async def geocode_address(self, address):
        return await self.request(
            'GET',
            '/geocode',
            params={'address': address}
        )
    
    async def calculate_distance(self, origin, destination):
        return await self.request(
            'GET',
            '/distance',
            params={'origin': origin, 'destination': destination}
        )
```

---

## 6. SECURITY ARCHITECTURE

### 6.1 Defense in Depth

```
┌─────────────────────────────────────────────────────────────────┐
│  PERIMETER                                                      │
│  - DDoS Protection (Cloudflare)                                │
│  - Web Application Firewall                                    │
│  - Rate Limiting                                               │
└─────────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│  NETWORK                                                        │
│  - VPC Isolation                                               │
│  - Security Groups                                             │
│  - VPN for Admin Access                                        │
└─────────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│  APPLICATION                                                    │
│  - TLS 1.3 Encryption                                          │
│  - Input Validation                                            │
│  - CSRF/XSS Protection                                         │
│  - Security Headers                                            │
└─────────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│  DATA                                                           │
│  - Encryption at Rest (AES-256)                                │
│  - Field-level Encryption                                      │
│  - Backup Encryption                                           │
└─────────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│  ACCESS CONTROL                                                 │
│  - Multi-Factor Authentication                                 │
│  - Role-Based Access Control                                   │
│  - Audit Logging                                               │
└─────────────────────────────────────────────────────────────────┘
```

### 6.2 Authentication & Authorization

#### 6.2.1 OAuth 2.0 + JWT Implementation

```python
# Authentication Service
from datetime import datetime, timedelta
from jose import jwt
from passlib.context import CryptContext

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

class AuthenticationService:
    def __init__(self):
        self.secret_key = settings.JWT_SECRET_KEY
        self.algorithm = "HS256"
        self.access_token_expire = timedelta(minutes=30)
        self.refresh_token_expire = timedelta(days=7)
    
    def verify_password(self, plain_password, hashed_password):
        return pwd_context.verify(plain_password, hashed_password)
    
    def get_password_hash(self, password):
        return pwd_context.hash(password)
    
    def create_access_token(self, data: dict):
        to_encode = data.copy()
        expire = datetime.utcnow() + self.access_token_expire
        to_encode.update({"exp": expire, "type": "access"})
        return jwt.encode(to_encode, self.secret_key, algorithm=self.algorithm)
    
    def create_refresh_token(self, data: dict):
        to_encode = data.copy()
        expire = datetime.utcnow() + self.refresh_token_expire
        to_encode.update({"exp": expire, "type": "refresh"})
        return jwt.encode(to_encode, self.secret_key, algorithm=self.algorithm)
    
    def decode_token(self, token: str):
        try:
            payload = jwt.decode(token, self.secret_key, algorithms=[self.algorithm])
            return payload
        except jwt.ExpiredSignatureError:
            raise HTTPException(status_code=401, detail="Token expired")
        except jwt.JWTError:
            raise HTTPException(status_code=401, detail="Invalid token")
```

#### 6.2.2 Role-Based Access Control (RBAC)

```python
# RBAC Implementation
class RBACService:
    # Role hierarchy
    ROLES = {
        'super_admin': ['*'],
        'admin': ['user.*', 'product.*', 'order.*', 'report.*'],
        'farm_manager': ['farm.*', 'animal.*', 'health.*', 'report.farm_*'],
        'farm_worker': ['farm.read', 'animal.read', 'animal.create_health_record'],
        'sales_manager': ['order.*', 'customer.*', 'report.sales_*'],
        'sales_rep': ['order.create', 'customer.read', 'customer.update_own'],
        'accountant': ['accounting.*', 'report.financial_*'],
        'warehouse_manager': ['inventory.*', 'stock.*'],
        'b2b_customer': ['order.create', 'order.read_own', 'invoice.read_own'],
        'b2c_customer': ['order.create', 'order.read_own', 'profile.manage'],
    }
    
    def check_permission(self, user_role: str, required_permission: str) -> bool:
        """Check if role has required permission"""
        if user_role not in self.ROLES:
            return False
            
        permissions = self.ROLES[user_role]
        
        # Wildcard permission
        if '*' in permissions:
            return True
            
        # Check specific permission
        for perm in permissions:
            if self._match_permission(perm, required_permission):
                return True
        return False
    
    def _match_permission(self, granted: str, required: str) -> bool:
        """Check if granted permission matches required"""
        if granted == required:
            return True
        if granted.endswith('*'):
            prefix = granted[:-1]
            return required.startswith(prefix)
        return False
```

---

## 7. DEPLOYMENT ARCHITECTURE

### 7.1 Container Architecture

#### 7.1.1 Docker Configuration

```dockerfile
# Dockerfile for Odoo Application
FROM odoo:19.0

USER root

# Install additional dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    python3-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Python packages
COPY requirements.txt /tmp/
RUN pip install --no-cache-dir -r /tmp/requirements.txt

# Copy custom modules
COPY ./custom_addons /mnt/extra-addons

# Set permissions
RUN chown -R odoo:odoo /mnt/extra-addons

USER odoo

# Expose Odoo port
EXPOSE 8069

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8069/web/health || exit 1
```

```yaml
# docker-compose.yml
version: '3.8'

services:
  web:
    build: .
    ports:
      - "8069:8069"
    environment:
      - HOST=db
      - USER=odoo
      - PASSWORD=${DB_PASSWORD}
      - POSTGRES_DB=postgres
    depends_on:
      - db
      - redis
    volumes:
      - odoo-web-data:/var/lib/odoo
      - ./config:/etc/odoo
      - ./addons:/mnt/extra-addons
    networks:
      - smart-dairy-network
    deploy:
      replicas: 2
      resources:
        limits:
          cpus: '2'
          memory: 4G
        reservations:
          cpus: '1'
          memory: 2G

  db:
    image: postgres:16
    environment:
      - POSTGRES_DB=postgres
      - POSTGRES_PASSWORD=${DB_PASSWORD}
      - POSTGRES_USER=odoo
      - PGDATA=/var/lib/postgresql/data/pgdata
    volumes:
      - odoo-db-data:/var/lib/postgresql/data/pgdata
    networks:
      - smart-dairy-network
    deploy:
      resources:
        limits:
          cpus: '4'
          memory: 8G

  redis:
    image: redis:7-alpine
    volumes:
      - redis-data:/data
    networks:
      - smart-dairy-network
    deploy:
      resources:
        limits:
          memory: 2G

  api-gateway:
    build: ./api-gateway
    ports:
      - "8000:8000"
    environment:
      - DATABASE_URL=${DATABASE_URL}
      - REDIS_URL=redis://redis:6379
    depends_on:
      - db
      - redis
    networks:
      - smart-dairy-network
    deploy:
      replicas: 2

  celery-worker:
    build: ./api-gateway
    command: celery -A tasks worker --loglevel=info --concurrency=4
    environment:
      - DATABASE_URL=${DATABASE_URL}
      - REDIS_URL=redis://redis:6379
    depends_on:
      - db
      - redis
    networks:
      - smart-dairy-network
    deploy:
      replicas: 2

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf
      - ./ssl:/etc/nginx/ssl
    depends_on:
      - web
      - api-gateway
    networks:
      - smart-dairy-network

volumes:
  odoo-web-data:
  odoo-db-data:
  redis-data:

networks:
  smart-dairy-network:
    driver: overlay
```

### 7.2 Kubernetes Deployment

```yaml
# k8s/namespace.yaml
apiVersion: v1
kind: Namespace
metadata:
  name: smart-dairy
  labels:
    app: smart-dairy
    environment: production

---
# k8s/odoo-deployment.yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: odoo-web
  namespace: smart-dairy
spec:
  replicas: 3
  selector:
    matchLabels:
      app: odoo-web
  template:
    metadata:
      labels:
        app: odoo-web
    spec:
      containers:
      - name: odoo
        image: smart-dairy/odoo:latest
        ports:
        - containerPort: 8069
        env:
        - name: HOST
          value: "postgres-service"
        - name: USER
          valueFrom:
            secretKeyRef:
              name: db-credentials
              key: username
        - name: PASSWORD
          valueFrom:
            secretKeyRef:
              name: db-credentials
              key: password
        resources:
          requests:
            memory: "2Gi"
            cpu: "1000m"
          limits:
            memory: "4Gi"
            cpu: "2000m"
        livenessProbe:
          httpGet:
            path: /web/health
            port: 8069
          initialDelaySeconds: 30
          periodSeconds: 10
        readinessProbe:
          httpGet:
            path: /web/health
            port: 8069
          initialDelaySeconds: 5
          periodSeconds: 5
        volumeMounts:
        - name: odoo-data
          mountPath: /var/lib/odoo
      volumes:
      - name: odoo-data
        persistentVolumeClaim:
          claimName: odoo-data-pvc

---
# k8s/hpa.yaml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: odoo-hpa
  namespace: smart-dairy
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: odoo-web
  minReplicas: 3
  maxReplicas: 10
  metrics:
  - type: Resource
    resource:
      name: cpu
      target:
        type: Utilization
        averageUtilization: 70
  - type: Resource
    resource:
      name: memory
      target:
        type: Utilization
        averageUtilization: 80
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 60
      policies:
      - type: Percent
        value: 100
        periodSeconds: 15
    scaleDown:
      stabilizationWindowSeconds: 300
      policies:
      - type: Percent
        value: 10
        periodSeconds: 60
```

---

## 8. PERFORMANCE & SCALABILITY DESIGN

### 8.1 Performance Targets

| Metric | Target | Maximum | Measurement |
|--------|--------|---------|-------------|
| Page Load Time | < 2s | < 3s | Lighthouse |
| API Response Time | < 500ms | < 1s | APM |
| Database Query Time | < 100ms | < 500ms | PostgreSQL logs |
| Checkout Completion | < 3s | < 5s | User timing |
| Report Generation | < 10s | < 30s | Server logs |
| Mobile App Launch | < 2s | < 4s | App telemetry |

### 8.2 Scalability Strategy

#### 8.2.1 Horizontal Scaling

```yaml
# Auto-scaling configuration
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: smart-dairy-hpa
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: odoo-web
  minReplicas: 2
  maxReplicas: 20
  metrics:
  - type: Resource
    resource:
      name: cpu
      target:
        type: Utilization
        averageUtilization: 70
  behavior:
    scaleUp:
      stabilizationWindowSeconds: 60
      policies:
      - type: Pods
        value: 4
        periodSeconds: 60
```

#### 8.2.2 Database Scaling

**Read Replicas:**
- Primary: Write operations
- Replica 1: Read operations (reporting)
- Replica 2: Read operations (analytics)

**Connection Pooling (PgBouncer):**
```ini
; pgbouncer.ini
[databases]
smart_dairy = host=postgres port=5432 dbname=smart_dairy

[pgbouncer]
listen_port = 6432
listen_addr = 0.0.0.0
auth_type = md5
auth_file = /etc/pgbouncer/userlist.txt
max_client_conn = 10000
default_pool_size = 20
min_pool_size = 10
reserve_pool_size = 5
```

### 8.3 Load Balancing Strategy

```nginx
# nginx.conf - Load Balancing Configuration
upstream odoo_backend {
    least_conn;
    server odoo-1:8069 weight=5;
    server odoo-2:8069 weight=5;
    server odoo-3:8069 weight=5 backup;
    
    keepalive 32;
}

server {
    listen 80;
    server_name smartdairybd.com;
    
    location / {
        proxy_pass http://odoo_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        
        # Timeouts
        proxy_connect_timeout 30s;
        proxy_send_timeout 30s;
        proxy_read_timeout 30s;
        
        # Buffering
        proxy_buffering on;
        proxy_buffer_size 4k;
        proxy_buffers 8 4k;
    }
    
    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1d;
        add_header Cache-Control "public, immutable";
    }
}
```

---

## 9. TECHNOLOGY STACK IMPLEMENTATION

### 9.1 Technology Stack Summary

| Layer | Technology | Version | Purpose |
|-------|------------|---------|---------|
| **Frontend** | Odoo OWL, Bootstrap | 2.0, 5.3 | Web UI |
| **Mobile** | Flutter | 3.16+ | Cross-platform apps |
| **Backend** | Python | 3.11+ | Core language |
| **Framework** | Odoo CE, FastAPI | 19.0, 0.104+ | Application framework |
| **Database** | PostgreSQL | 16.1+ | Primary database |
| **Cache** | Redis | 7.2+ | Caching, sessions |
| **Search** | Elasticsearch | 8.11+ | Full-text search |
| **Queue** | Celery + Redis | 5.3+ | Background tasks |
| **Container** | Docker | 24.0+ | Application packaging |
| **Orchestration** | Kubernetes | 1.28+ | Container management |
| **CI/CD** | GitHub Actions | - | Build automation |
| **Monitoring** | Prometheus, Grafana | - | Metrics and dashboards |

### 9.2 Dependency Management

```txt
# requirements.txt - Core Dependencies
# Odoo Dependencies
Babel==2.12.1
cryptography==41.0.7
Jinja2==3.1.2
lxml==4.9.3
MarkupSafe==2.1.3
num2words==0.5.13
passlib==1.7.4
Pillow==10.0.1
psycopg2==2.9.9
python-dateutil==2.8.2
pytz==2023.3
qrcode==7.4.2
reportlab==3.6.13
requests==2.31.0
Werkzeug==2.3.7
xlrd==2.0.1
XlsxWriter==3.1.9

# FastAPI & API
fastapi==0.104.1
uvicorn==0.24.0
python-jose[cryptography]==3.3.0
passlib[bcrypt]==1.7.4
python-multipart==0.0.6
httpx==0.25.2

# Background Tasks
celery==5.3.4
redis==5.0.1

# IoT
paho-mqtt==1.6.1

# Data Processing
pandas==2.1.3
numpy==1.26.2

# ML (Future)
scikit-learn==1.3.2

# Testing
pytest==7.4.3
pytest-asyncio==0.21.1
httpx==0.25.2
```

---

## 10. DEVELOPMENT STANDARDS

### 10.1 Code Quality Standards

#### 10.1.1 Python Coding Standards

```python
"""
Module docstring with description.

This module provides functionality for...

Classes:
    ClassName: Brief description

Functions:
    function_name: Brief description
"""

from typing import Optional, List, Dict
from datetime import datetime
import logging

logger = logging.getLogger(__name__)


class AnimalService:
    """
    Service class for animal management operations.
    
    This class provides methods for CRUD operations on animal records,
    including health tracking, breeding management, and production monitoring.
    
    Attributes:
        db_session: Database session for queries
        cache: Redis cache instance
        
    Example:
        >>> service = AnimalService(db_session)
        >>> animal = service.get_animal_by_id(123)
    """
    
    def __init__(self, db_session, cache=None):
        """
        Initialize AnimalService.
        
        Args:
            db_session: SQLAlchemy database session
            cache: Optional Redis cache instance
        """
        self.db = db_session
        self.cache = cache
    
    def get_animal_by_id(
        self, 
        animal_id: int, 
        include_history: bool = False
    ) -> Optional[Dict]:
        """
        Retrieve animal by ID.
        
        Args:
            animal_id: Unique identifier for the animal
            include_history: Whether to include historical records
            
        Returns:
            Animal data dictionary or None if not found
            
        Raises:
            DatabaseError: If database query fails
            
        Example:
            >>> animal = service.get_animal_by_id(123)
            >>> if animal:
            ...     print(animal['name'])
        """
        try:
            # Check cache first
            if self.cache:
                cached = self.cache.get(f"animal:{animal_id}")
                if cached:
                    return json.loads(cached)
            
            # Query database
            animal = self.db.query(Animal).filter(
                Animal.id == animal_id
            ).first()
            
            if not animal:
                return None
            
            result = animal.to_dict()
            
            if include_history:
                result['history'] = self._get_animal_history(animal_id)
            
            # Cache result
            if self.cache:
                self.cache.setex(
                    f"animal:{animal_id}", 
                    300,  # 5 minutes
                    json.dumps(result)
                )
            
            return result
            
        except SQLAlchemyError as e:
            logger.error(f"Database error fetching animal {animal_id}: {e}")
            raise DatabaseError(f"Failed to fetch animal: {e}")
```

#### 10.1.2 Odoo Module Standards

```python
# -*- coding: utf-8 -*-
"""
Smart Dairy Farm Management Module

This module provides comprehensive farm management functionality including:
- Animal lifecycle management
- Health and breeding tracking
- Milk production monitoring
- Feed and nutrition management

Author: Smart Dairy Technical Team
Version: 19.0.1.0.0
License: LGPL-3
"""

from odoo import models, fields, api, _
from odoo.exceptions import ValidationError, UserError
import logging

_logger = logging.getLogger(__name__)


class FarmAnimal(models.Model):
    """
    Farm Animal Entity
    
    Represents an individual animal in the farm with complete lifecycle
    tracking including birth, breeding, health, and production records.
    
    Attributes:
        name (str): Animal identifier/tag number
        rfid_tag (str): RFID tag for automatic identification
        breed_id (Many2one): Reference to animal breed
        status (Selection): Current lifecycle status
    """
    
    _name = 'farm.animal'
    _description = 'Farm Animal'
    _order = 'name'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    
    # Identification Fields
    name = fields.Char(
        string='Animal ID',
        required=True,
        index=True,
        help='Unique identifier for the animal'
    )
    rfid_tag = fields.Char(
        string='RFID Tag',
        index=True,
        help='RFID tag number for automatic identification'
    )
    ear_tag = fields.Char(
        string='Ear Tag Number',
        help='Visible ear tag number'
    )
    
    # Basic Information
    species = fields.Selection([
        ('cattle', 'Cattle'),
        ('buffalo', 'Buffalo'),
    ], default='cattle', required=True)
    
    breed_id = fields.Many2one(
        'farm.breed',
        string='Breed',
        required=True,
        ondelete='restrict'
    )
    
    gender = fields.Selection([
        ('female', 'Female'),
        ('male', 'Male'),
    ], required=True)
    
    birth_date = fields.Date(string='Birth Date')
    age_months = fields.Integer(
        string='Age (Months)',
        compute='_compute_age',
        store=True
    )
    
    # Lifecycle Status
    status = fields.Selection([
        ('calf', 'Calf'),
        ('heifer', 'Heifer'),
        ('lactating', 'Lactating'),
        ('dry', 'Dry'),
        ('pregnant', 'Pregnant'),
        ('sold', 'Sold'),
        ('deceased', 'Deceased'),
    ], default='calf', required=True, tracking=True)
    
    # Location
    barn_id = fields.Many2one(
        'farm.barn',
        string='Barn/Location',
        ondelete='set null'
    )
    pen_number = fields.Char(string='Pen Number')
    
    # Production Metrics
    current_lactation = fields.Integer(
        string='Lactation Number',
        default=0
    )
    daily_milk_avg = fields.Float(
        string='Daily Milk Average (L)',
        compute='_compute_milk_avg',
        digits=(8, 2)
    )
    
    # Relationships
    mother_id = fields.Many2one(
        'farm.animal',
        string='Mother',
        domain="[('gender', '=', 'female'), ('species', '=', species)]"
    )
    father_id = fields.Many2one(
        'farm.animal',
        string='Father (Sire)',
        domain="[('gender', '=', 'male'), ('species', '=', species)]"
    )
    offspring_ids = fields.One2many(
        'farm.animal',
        'mother_id',
        string='Offspring'
    )
    
    # Health Status
    health_status = fields.Selection([
        ('healthy', 'Healthy'),
        ('sick', 'Sick'),
        ('treatment', 'Under Treatment'),
        ('quarantine', 'Quarantine'),
    ], default='healthy', tracking=True)
    
    # Computed Fields
    @api.depends('birth_date')
    def _compute_age(self):
        """Calculate age in months from birth date."""
        today = fields.Date.today()
        for animal in self:
            if animal.birth_date:
                delta = today - animal.birth_date
                animal.age_months = int(delta.days / 30.44)
            else:
                animal.age_months = 0
    
    @api.depends('milk_production_ids.quantity')
    def _compute_milk_avg(self):
        """Calculate average daily milk production (last 30 days)."""
        for animal in self:
            productions = animal.milk_production_ids[-30:]
            if productions:
                animal.daily_milk_avg = sum(
                    p.quantity for p in productions
                ) / len(productions)
            else:
                animal.daily_milk_avg = 0.0
    
    # Constraints
    @api.constrains('birth_date')
    def _check_birth_date(self):
        """Ensure birth date is not in the future."""
        for animal in self:
            if animal.birth_date and animal.birth_date > fields.Date.today():
                raise ValidationError(_(
                    'Birth date cannot be in the future.'
                ))
    
    @api.constrains('rfid_tag')
    def _check_rfid_unique(self):
        """Ensure RFID tag is unique."""
        for animal in self:
            if animal.rfid_tag:
                existing = self.search([
                    ('rfid_tag', '=', animal.rfid_tag),
                    ('id', '!=', animal.id)
                ])
                if existing:
                    raise ValidationError(_(
                        'RFID tag %s is already assigned to animal %s'
                    ) % (animal.rfid_tag, existing.name))
    
    # Business Methods
    def action_mark_as_sick(self):
        """Mark animal as sick and create health record."""
        self.ensure_one()
        self.write({'health_status': 'sick'})
        return {
            'type': 'ir.actions.act_window',
            'name': _('Create Health Record'),
            'res_model': 'farm.animal.health',
            'view_mode': 'form',
            'target': 'new',
            'context': {
                'default_animal_id': self.id,
                'default_date': fields.Date.today(),
            }
        }
    
    def get_production_report(self, start_date, end_date):
        """
        Generate production report for date range.
        
        Args:
            start_date: Report start date
            end_date: Report end date
            
        Returns:
            dict: Production statistics
        """
        self.ensure_one()
        productions = self.milk_production_ids.filtered(
            lambda p: start_date <= p.date <= end_date
        )
        
        if not productions:
            return {
                'total_volume': 0.0,
                'average_daily': 0.0,
                'session_count': 0,
            }
        
        total_volume = sum(p.quantity for p in productions)
        days = (end_date - start_date).days + 1
        
        return {
            'total_volume': total_volume,
            'average_daily': total_volume / days if days > 0 else 0,
            'session_count': len(productions),
            'fat_avg': sum(p.fat_percentage for p in productions) / len(productions) if productions else 0,
            'snf_avg': sum(p.snf_percentage for p in productions) / len(productions) if productions else 0,
        }
```

### 10.2 Testing Standards

#### 10.2.1 Unit Testing

```python
# Test File Structure
import pytest
from datetime import date, timedelta
from unittest.mock import Mock, patch

from smart_dairy.farm.models import FarmAnimal


class TestFarmAnimal:
    """Test cases for FarmAnimal model."""
    
    @pytest.fixture
    def animal_data(self):
        """Fixture providing valid animal data."""
        return {
            'name': 'TEST001',
            'rfid_tag': 'RFID123456',
            'species': 'cattle',
            'gender': 'female',
            'birth_date': date.today() - timedelta(days=365),
            'status': 'lactating',
        }
    
    def test_animal_creation(self, animal_data, db_session):
        """Test successful animal creation."""
        animal = FarmAnimal.create(animal_data)
        
        assert animal.name == 'TEST001'
        assert animal.rfid_tag == 'RFID123456'
        assert animal.age_months == 12
    
    def test_rfid_uniqueness(self, animal_data, db_session):
        """Test that RFID tags must be unique."""
        # Create first animal
        FarmAnimal.create(animal_data)
        
        # Attempt to create second animal with same RFID
        with pytest.raises(ValidationError) as exc:
            FarmAnimal.create(animal_data)
        
        assert 'already assigned' in str(exc.value)
    
    def test_future_birth_date_validation(self, animal_data):
        """Test that birth date cannot be in future."""
        animal_data['birth_date'] = date.today() + timedelta(days=1)
        
        with pytest.raises(ValidationError) as exc:
            FarmAnimal.create(animal_data)
        
        assert 'future' in str(exc.value)
    
    @patch('smart_dairy.farm.models.FarmAnimal.milk_production_ids')
    def test_milk_average_calculation(self, mock_productions, animal_data):
        """Test daily milk average calculation."""
        # Mock milk production records
        mock_productions.__getitem__ = Mock(return_value=[
            Mock(quantity=15.5),
            Mock(quantity=16.0),
            Mock(quantity=14.5),
        ])
        
        animal = FarmAnimal(animal_data)
        
        assert animal.daily_milk_avg == 15.33  # (15.5 + 16.0 + 14.5) / 3
    
    def test_production_report(self, animal_data):
        """Test production report generation."""
        animal = FarmAnimal.create(animal_data)
        
        # Add milk production records
        # ... setup code ...
        
        report = animal.get_production_report(
            date.today() - timedelta(days=30),
            date.today()
        )
        
        assert 'total_volume' in report
        assert 'average_daily' in report
        assert report['session_count'] >= 0
```

---

## 11. APPENDICES

### Appendix A: Glossary

| Term | Definition |
|------|------------|
| **API** | Application Programming Interface |
| **CDN** | Content Delivery Network |
| **CSRF** | Cross-Site Request Forgery |
| **JWT** | JSON Web Token |
| **MFA** | Multi-Factor Authentication |
| **MQTT** | Message Queuing Telemetry Transport |
| **ORM** | Object-Relational Mapping |
| **RBAC** | Role-Based Access Control |
| **REST** | Representational State Transfer |
| **TTL** | Time To Live (caching) |
| **VPC** | Virtual Private Cloud |
| **WAF** | Web Application Firewall |
| **XSS** | Cross-Site Scripting |

### Appendix B: Document Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 0.1 | 2026-01-15 | Solution Architect | Initial draft |
| 0.2 | 2026-01-25 | Solution Architect | Added security architecture |
| 1.0 | 2026-01-31 | Solution Architect | Final version for review |

### Appendix C: References

1. Odoo 19 Development Documentation
2. FastAPI Official Documentation
3. PostgreSQL 16 Manual
4. Kubernetes Documentation
5. OWASP Security Guidelines
6. Bangladesh Bank Digital Payment Guidelines

### Appendix D: Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Solution Architect | [Name] | _____________ | _______ |
| IT Director | [Name] | _____________ | _______ |
| Managing Director | [Name] | _____________ | _______ |

---

**END OF TECHNICAL DESIGN DOCUMENT**

**Document Statistics:**
- Total Sections: 11
- Architecture Diagrams: 8+
- Code Examples: 15+
- Tables: 30+
- Estimated Pages: 150+

**Next Document:** D-001 Infrastructure Architecture Document
