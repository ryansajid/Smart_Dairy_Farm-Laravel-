# SMART DAIRY LTD.
## API GATEWAY DESIGN DOCUMENT
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | B-007 |
| **Version** | 1.0 |
| **Date** | February 10, 2026 |
| **Author** | Solution Architect |
| **Owner** | Tech Lead |
| **Reviewer** | DevOps Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Architecture Overview](#2-architecture-overview)
3. [API Design Principles](#3-api-design-principles)
4. [FastAPI Implementation](#4-fastapi-implementation)
5. [Authentication & Authorization](#5-authentication--authorization)
6. [Middleware & Interceptors](#6-middleware--interceptors)
7. [Rate Limiting & Throttling](#7-rate-limiting--throttling)
8. [Error Handling](#8-error-handling)
9. [API Documentation](#9-api-documentation)
10. [Performance Optimization](#10-performance-optimization)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the architecture and implementation guidelines for the Smart Dairy API Gateway, built using FastAPI. The gateway serves as the central entry point for all external API consumers including mobile applications, IoT devices, B2B partners, and third-party integrations.

### 1.2 Scope

- RESTful API design and implementation
- GraphQL API support
- Webhook handling
- Mobile app APIs (Customer, Field Sales, Farmer)
- IoT data ingestion APIs
- B2B partner integration APIs
- Payment gateway callbacks

### 1.3 Technology Stack

| Component | Technology | Version |
|-----------|------------|---------|
| Framework | FastAPI | 0.104+ |
| Server | Uvicorn | 0.24+ |
| ASGI | Starlette | 0.27+ |
| Validation | Pydantic | v2 |
| Authentication | python-jose | 3.3+ |
| HTTP Client | httpx | 0.25+ |
| Caching | aioredis | 2.0+ |

---

## 2. ARCHITECTURE OVERVIEW

### 2.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              API GATEWAY ARCHITECTURE                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│   CLIENTS                              GATEWAY LAYER                         │
│   ┌─────────────┐                     ┌──────────────────────────────┐      │
│   │ Mobile Apps │────────────────────►│   Load Balancer (Nginx)      │      │
│   ├─────────────┤                     └──────────────┬───────────────┘      │
│   │  IoT Devices│                                    │                      │
│   ├─────────────┤                     ┌──────────────▼───────────────┐      │
│   │ B2B Partners│────────────────────►│   FastAPI Gateway            │      │
│   ├─────────────┤                     │  ┌────────────────────────┐  │      │
│   │   Web Apps  │────────────────────►│  │ Middleware Layer       │  │      │
│   └─────────────┘                     │  │ - CORS                 │  │      │
│                                       │  │ - Auth                 │  │      │
│                                       │  │ - Rate Limit           │  │      │
│                                       │  │ - Logging              │  │      │
│                                       │  └────────────────────────┘  │      │
│                                       │  ┌────────────────────────┐  │      │
│                                       │  │ Router Layer           │  │      │
│                                       │  │ /api/v1/auth          │  │      │
│                                       │  │ /api/v1/mobile        │  │      │
│                                       │  │ /api/v1/iot           │  │      │
│                                       │  │ /api/v1/b2b           │  │      │
│                                       │  │ /api/v1/payments      │  │      │
│                                       │  │ /graphql              │  │      │
│                                       │  └────────────────────────┘  │      │
│                                       │  ┌────────────────────────┐  │      │
│                                       │  │ Service Layer          │  │      │
│                                       │  │ - Business Logic       │  │      │
│                                       │  │ - Odoo Integration     │  │      │
│                                       │  │ - External APIs        │  │      │
│                                       │  └────────────────────────┘  │      │
│                                       └──────────────┬───────────────┘      │
│                                                      │                       │
│   RESPONSE                                          ▼                       │
│   ◄─────────────────────────────────────┌────────────────────┐              │
│                                         │  Data Layer        │              │
│                                         │  - PostgreSQL      │              │
│                                         │  - Redis           │              │
│                                         │  - Odoo (XML-RPC)  │              │
│                                         └────────────────────┘              │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Service Architecture

```python
# Project Structure
smart_api_gateway/
├── app/
│   ├── __init__.py
│   ├── main.py                    # FastAPI application entry
│   ├── config.py                  # Configuration management
│   ├── dependencies.py            # FastAPI dependencies
│   │
│   ├── api/
│   │   ├── __init__.py
│   │   ├── deps.py               # Common dependencies
│   │   ├── v1/
│   │   │   ├── __init__.py
│   │   │   ├── api.py            # v1 router aggregator
│   │   │   ├── auth.py           # Authentication endpoints
│   │   │   ├── mobile.py         # Mobile app APIs
│   │   │   ├── iot.py            # IoT data APIs
│   │   │   ├── b2b.py            # B2B portal APIs
│   │   │   ├── payments.py       # Payment callbacks
│   │   │   ├── webhooks.py       # Webhook handlers
│   │   │   └── health.py         # Health checks
│   │   └── graphql/
│   │       ├── __init__.py
│   │       └── schema.py         # GraphQL schema
│   │
│   ├── core/
│   │   ├── __init__.py
│   │   ├── security.py           # JWT, encryption
│   │   ├── exceptions.py         # Custom exceptions
│   │   ├── logging.py            # Structured logging
│   │   └── middleware.py         # Custom middleware
│   │
│   ├── models/
│   │   ├── __init__.py
│   │   ├── user.py               # Pydantic models
│   │   ├── token.py
│   │   ├── mobile.py
│   │   └── iot.py
│   │
│   ├── services/
│   │   ├── __init__.py
│   │   ├── odoo_service.py       # Odoo integration
│   │   ├── cache_service.py      # Redis operations
│   │   ├── notification_service.py
│   │   └── payment_service.py
│   │
│   └── utils/
│       ├── __init__.py
│       ├── validators.py
│       └── helpers.py
│
├── tests/
├── alembic/                      # Database migrations
├── docker/
├── requirements.txt
├── Dockerfile
└── docker-compose.yml
```

---

## 3. API DESIGN PRINCIPLES

### 3.1 RESTful Design Standards

| Principle | Implementation |
|-----------|----------------|
| **Resource Naming** | Plural nouns: `/api/v1/animals`, `/api/v1/orders` |
| **HTTP Methods** | GET (read), POST (create), PUT/PATCH (update), DELETE (remove) |
| **Status Codes** | Standard HTTP status codes |
| **Versioning** | URL versioning: `/api/v1/`, `/api/v2/` |
| **Content Type** | JSON (application/json) |
| **Pagination** | Offset-based with limit/offset or cursor-based |

### 3.2 URL Structure

```
/api/v1/{resource}/{id}/{sub-resource}

Examples:
/api/v1/animals                          # List all animals
/api/v1/animals/123                      # Get specific animal
/api/v1/animals/123/milk-production      # Get animal's milk records
/api/v1/orders                           # List orders
/api/v1/orders/456/invoice               # Get order invoice
```

### 3.3 Response Format

```python
# Standard Success Response
{
    "success": True,
    "data": {
        "id": 123,
        "name": "SD-001",
        "breed": "Holstein Friesian",
        "status": "lactating"
    },
    "meta": {
        "timestamp": "2026-02-10T14:30:00Z",
        "request_id": "req_abc123"
    }
}

# List Response with Pagination
{
    "success": True,
    "data": [
        {"id": 1, "name": "SD-001"},
        {"id": 2, "name": "SD-002"}
    ],
    "pagination": {
        "total": 250,
        "limit": 20,
        "offset": 0,
        "has_more": True
    },
    "meta": {
        "timestamp": "2026-02-10T14:30:00Z",
        "request_id": "req_def456"
    }
}

# Error Response
{
    "success": False,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "Invalid input data",
        "details": [
            {"field": "rfid_tag", "message": "RFID tag already exists"}
        ]
    },
    "meta": {
        "timestamp": "2026-02-10T14:30:00Z",
        "request_id": "req_ghi789"
    }
}
```

---

## 4. FASTAPI IMPLEMENTATION

### 4.1 Application Setup

```python
# app/main.py
from fastapi import FastAPI, Request
from fastapi.middleware.cors import CORSMiddleware
from fastapi.middleware.trustedhost import TrustedHostMiddleware
from fastapi.responses import JSONResponse
from contextlib import asynccontextmanager
import time

from app.api.v1.api import api_router
from app.core.config import settings
from app.core.exceptions import SmartDairyException
from app.core.logging import logger
from app.core.middleware import LoggingMiddleware, RateLimitMiddleware
from app.services.cache_service import CacheService
from app.services.odoo_service import OdooService


@asynccontextmanager
async def lifespan(app: FastAPI):
    """Application lifespan management"""
    # Startup
    logger.info("Starting up Smart Dairy API Gateway...")
    
    # Initialize connections
    app.state.cache = CacheService()
    app.state.odoo = OdooService()
    
    await app.state.cache.connect()
    await app.state.odoo.connect()
    
    logger.info("API Gateway started successfully")
    yield
    
    # Shutdown
    logger.info("Shutting down API Gateway...")
    await app.state.cache.disconnect()
    await app.state.odoo.disconnect()
    logger.info("API Gateway shutdown complete")


app = FastAPI(
    title="Smart Dairy API Gateway",
    description="Central API gateway for Smart Dairy ERP and integrations",
    version="1.0.0",
    docs_url="/docs" if settings.DEBUG else None,
    redoc_url="/redoc" if settings.DEBUG else None,
    openapi_url="/openapi.json" if settings.DEBUG else None,
    lifespan=lifespan
)

# Middleware (order matters)
app.add_middleware(TrustedHostMiddleware, allowed_hosts=settings.ALLOWED_HOSTS)
app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.CORS_ORIGINS,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)
app.add_middleware(LoggingMiddleware)
app.add_middleware(RateLimitMiddleware)

# Exception handlers
@app.exception_handler(SmartDairyException)
async def smart_dairy_exception_handler(request: Request, exc: SmartDairyException):
    logger.error(f"SmartDairyException: {exc.message}", extra={
        "request_id": request.state.request_id,
        "error_code": exc.code
    })
    return JSONResponse(
        status_code=exc.status_code,
        content={
            "success": False,
            "error": {
                "code": exc.code,
                "message": exc.message,
                "details": exc.details
            },
            "meta": {
                "timestamp": datetime.utcnow().isoformat(),
                "request_id": request.state.request_id
            }
        }
    )

@app.exception_handler(Exception)
async def general_exception_handler(request: Request, exc: Exception):
    logger.exception("Unhandled exception")
    return JSONResponse(
        status_code=500,
        content={
            "success": False,
            "error": {
                "code": "INTERNAL_ERROR",
                "message": "An unexpected error occurred"
            },
            "meta": {
                "timestamp": datetime.utcnow().isoformat(),
                "request_id": getattr(request.state, 'request_id', 'unknown')
            }
        }
    )

# Include routers
app.include_router(api_router, prefix=settings.API_V1_STR)

@app.get("/health")
async def health_check():
    return {"status": "healthy", "version": "1.0.0"}
```

### 4.2 Router Configuration

```python
# app/api/v1/api.py
from fastapi import APIRouter

from app.api.v1 import auth, mobile, iot, b2b, payments, webhooks, health

api_router = APIRouter()

api_router.include_router(auth.router, prefix="/auth", tags=["Authentication"])
api_router.include_router(mobile.router, prefix="/mobile", tags=["Mobile App"])
api_router.include_router(iot.router, prefix="/iot", tags=["IoT"])
api_router.include_router(b2b.router, prefix="/b2b", tags=["B2B Portal"])
api_router.include_router(payments.router, prefix="/payments", tags=["Payments"])
api_router.include_router(webhooks.router, prefix="/webhooks", tags=["Webhooks"])
api_router.include_router(health.router, prefix="/health", tags=["Health"])
```

### 4.3 Mobile API Implementation

```python
# app/api/v1/mobile.py
from fastapi import APIRouter, Depends, HTTPException, Query
from typing import List, Optional
from datetime import date

from app.api.deps import get_current_user, get_db
from app.models.mobile import (
    AnimalResponse, AnimalListResponse,
    MilkRecordCreate, MilkRecordResponse,
    OrderCreate, OrderResponse,
    SyncRequest, SyncResponse
)
from app.services.odoo_service import OdooService

router = APIRouter()


@router.get("/animals", response_model=AnimalListResponse)
async def list_animals(
    barn_id: Optional[int] = None,
    status: Optional[str] = None,
    limit: int = Query(20, ge=1, le=100),
    offset: int = Query(0, ge=0),
    current_user: dict = Depends(get_current_user),
    odoo: OdooService = Depends(get_odoo_service)
):
    """
    List animals with optional filtering.
    Supports pagination and filtering by barn or status.
    """
    domain = []
    if barn_id:
        domain.append(("barn_id", "=", barn_id))
    if status:
        domain.append(("status", "=", status))
    
    animals = await odoo.search_read(
        model="farm.animal",
        domain=domain,
        fields=["id", "name", "rfid_tag", "breed_id", "status", "daily_milk_avg"],
        limit=limit,
        offset=offset
    )
    
    total = await odoo.search_count("farm.animal", domain)
    
    return {
        "success": True,
        "data": animals,
        "pagination": {
            "total": total,
            "limit": limit,
            "offset": offset,
            "has_more": (offset + limit) < total
        }
    }


@router.get("/animals/{animal_id}", response_model=AnimalResponse)
async def get_animal(
    animal_id: int,
    current_user: dict = Depends(get_current_user),
    odoo: OdooService = Depends(get_odoo_service)
):
    """Get detailed information about a specific animal."""
    animal = await odoo.read(
        model="farm.animal",
        ids=[animal_id],
        fields=["id", "name", "rfid_tag", "breed_id", "status", 
                "birth_date", "daily_milk_avg", "health_status"]
    )
    
    if not animal:
        raise HTTPException(status_code=404, detail="Animal not found")
    
    return {"success": True, "data": animal[0]}


@router.post("/milk-production", response_model=MilkRecordResponse)
async def record_milk_production(
    record: MilkRecordCreate,
    current_user: dict = Depends(get_current_user),
    odoo: OdooService = Depends(get_odoo_service)
):
    """
    Record milk production for an animal.
    Validates animal exists and is in lactating status.
    """
    # Validate animal
    animal = await odoo.read(
        model="farm.animal",
        ids=[record.animal_id],
        fields=["status", "name"]
    )
    
    if not animal:
        raise HTTPException(status_code=404, detail="Animal not found")
    
    if animal[0]["status"] != "lactating":
        raise HTTPException(
            status_code=400, 
            detail=f"Animal {animal[0]['name']} is not in lactating status"
        )
    
    # Create record
    record_data = {
        "animal_id": record.animal_id,
        "production_date": record.production_date.isoformat(),
        "session": record.session,
        "quantity": record.quantity_liters,
        "fat_percentage": record.fat_percentage,
        "snf_percentage": record.snf_percentage,
        "recorded_by": current_user["id"]
    }
    
    created_id = await odoo.create("farm.milk.production", record_data)
    
    return {
        "success": True,
        "data": {"id": created_id, "message": "Milk production recorded successfully"}
    }


@router.post("/sync", response_model=SyncResponse)
async def sync_data(
    sync_request: SyncRequest,
    current_user: dict = Depends(get_current_user),
    odoo: OdooService = Depends(get_odoo_service),
    cache: CacheService = Depends(get_cache_service)
):
    """
    Synchronize offline data from mobile app.
    Handles conflicts and provides server updates.
    """
    results = {
        "created": [],
        "updated": [],
        "errors": [],
        "server_updates": []
    }
    
    # Process pending records
    for record in sync_request.pending_records:
        try:
            if record.action == "create":
                record_id = await odoo.create(record.model, record.data)
                results["created"].append({"local_id": record.local_id, "server_id": record_id})
            elif record.action == "update":
                await odoo.write(record.model, record.server_id, record.data)
                results["updated"].append({"server_id": record.server_id})
        except Exception as e:
            results["errors"].append({
                "local_id": record.local_id,
                "error": str(e)
            })
    
    # Get server updates since last sync
    last_sync = sync_request.last_sync_timestamp
    server_updates = await odoo.get_changes_since(last_sync)
    results["server_updates"] = server_updates
    
    return {
        "success": True,
        "data": results,
        "sync_timestamp": datetime.utcnow().isoformat()
    }
```

### 4.4 IoT API Implementation

```python
# app/api/v1/iot.py
from fastapi import APIRouter, Depends, HTTPException, BackgroundTasks
from typing import List
from datetime import datetime

from app.api.deps import verify_iot_device
from app.models.iot import SensorReading, DeviceRegistration, AlertConfig
from app.services.cache_service import CacheService
from app.services.notification_service import NotificationService

router = APIRouter()


@router.post("/readings/bulk")
async def ingest_sensor_data(
    readings: List[SensorReading],
    background_tasks: BackgroundTasks,
    device: dict = Depends(verify_iot_device),
    cache: CacheService = Depends(get_cache_service)
):
    """
    Bulk ingest sensor readings from IoT devices.
    Validates device authenticity and queues data for processing.
    """
    processed = 0
    errors = []
    
    for reading in readings:
        try:
            # Validate reading
            if not validate_reading(reading):
                errors.append({"reading": reading.dict(), "error": "Validation failed"})
                continue
            
            # Store in TimescaleDB via Odoo
            await cache.store_sensor_reading({
                "device_id": device["id"],
                "sensor_type": reading.sensor_type,
                "timestamp": reading.timestamp,
                "value": reading.value,
                "unit": reading.unit,
                "metadata": reading.metadata
            })
            
            processed += 1
            
            # Check alert thresholds in background
            background_tasks.add_task(
                check_alert_thresholds,
                device["id"],
                reading.sensor_type,
                reading.value
            )
            
        except Exception as e:
            errors.append({"reading": reading.dict(), "error": str(e)})
    
    return {
        "success": True,
        "data": {
            "processed": processed,
            "failed": len(errors),
            "errors": errors[:10]  # Return first 10 errors only
        }
    }


@router.post("/devices/register")
async def register_device(
    device: DeviceRegistration,
    current_user: dict = Depends(get_current_user),
    odoo: OdooService = Depends(get_odoo_service)
):
    """Register a new IoT device in the system."""
    device_data = {
        "name": device.name,
        "device_type": device.device_type,
        "serial_number": device.serial_number,
        "location_id": device.location_id,
        "api_key": generate_api_key(),
        "status": "active"
    }
    
    device_id = await odoo.create("iot.device", device_data)
    
    return {
        "success": True,
        "data": {
            "device_id": device_id,
            "api_key": device_data["api_key"],
            "message": "Device registered successfully"
        }
    }


async def check_alert_thresholds(device_id: str, sensor_type: str, value: float):
    """Background task to check if readings exceed thresholds."""
    # Fetch alert configuration
    alert_config = await get_alert_config(device_id, sensor_type)
    
    if alert_config:
        if value > alert_config.max_threshold or value < alert_config.min_threshold:
            await NotificationService.send_alert(
                device_id=device_id,
                sensor_type=sensor_type,
                value=value,
                threshold=alert_config.max_threshold if value > alert_config.max_threshold else alert_config.min_threshold
            )
```

---

## 5. AUTHENTICATION & AUTHORIZATION

### 5.1 JWT Implementation

```python
# app/core/security.py
from datetime import datetime, timedelta
from typing import Optional, Union
from jose import JWTError, jwt
from passlib.context import CryptContext
from pydantic import BaseModel

from app.core.config import settings

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

ALGORITHM = "HS256"


class TokenData(BaseModel):
    user_id: Optional[int] = None
    email: Optional[str] = None
    role: Optional[str] = None


def verify_password(plain_password: str, hashed_password: str) -> bool:
    return pwd_context.verify(plain_password, hashed_password)


def get_password_hash(password: str) -> str:
    return pwd_context.hash(password)


def create_access_token(data: dict, expires_delta: Optional[timedelta] = None):
    to_encode = data.copy()
    
    if expires_delta:
        expire = datetime.utcnow() + expires_delta
    else:
        expire = datetime.utcnow() + timedelta(minutes=settings.ACCESS_TOKEN_EXPIRE_MINUTES)
    
    to_encode.update({"exp": expire, "iat": datetime.utcnow(), "type": "access"})
    
    encoded_jwt = jwt.encode(to_encode, settings.SECRET_KEY, algorithm=ALGORITHM)
    return encoded_jwt


def create_refresh_token(data: dict):
    to_encode = data.copy()
    expire = datetime.utcnow() + timedelta(days=settings.REFRESH_TOKEN_EXPIRE_DAYS)
    to_encode.update({"exp": expire, "iat": datetime.utcnow(), "type": "refresh"})
    
    encoded_jwt = jwt.encode(to_encode, settings.SECRET_KEY, algorithm=ALGORITHM)
    return encoded_jwt


def decode_token(token: str) -> Optional[TokenData]:
    try:
        payload = jwt.decode(token, settings.SECRET_KEY, algorithms=[ALGORITHM])
        user_id: int = payload.get("sub")
        email: str = payload.get("email")
        role: str = payload.get("role")
        
        if user_id is None:
            return None
        
        return TokenData(user_id=user_id, email=email, role=role)
    except JWTError:
        return None
```

### 5.2 Authentication Endpoints

```python
# app/api/v1/auth.py
from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordRequestForm
from datetime import timedelta

from app.core.config import settings
from app.core.security import (
    create_access_token, create_refresh_token, verify_password, decode_token
)
from app.models.token import Token, TokenRefresh, UserLogin
from app.services.odoo_service import OdooService

router = APIRouter()


@router.post("/login", response_model=Token)
async def login(
    form_data: OAuth2PasswordRequestForm = Depends(),
    odoo: OdooService = Depends(get_odoo_service)
):
    """
    OAuth2 compatible token login, get an access token for future requests.
    Authenticates against Odoo user database.
    """
    # Authenticate with Odoo
    user = await odoo.authenticate(
        login=form_data.username,
        password=form_data.password
    )
    
    if not user:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Incorrect username or password",
            headers={"WWW-Authenticate": "Bearer"},
        )
    
    access_token_expires = timedelta(minutes=settings.ACCESS_TOKEN_EXPIRE_MINUTES)
    
    token_data = {
        "sub": str(user["id"]),
        "email": user["email"],
        "role": user.get("role", "user"),
        "company_id": user.get("company_id", 1)
    }
    
    access_token = create_access_token(
        data=token_data, expires_delta=access_token_expires
    )
    refresh_token = create_refresh_token(data=token_data)
    
    return {
        "access_token": access_token,
        "refresh_token": refresh_token,
        "token_type": "bearer",
        "expires_in": settings.ACCESS_TOKEN_EXPIRE_MINUTES * 60
    }


@router.post("/refresh", response_model=Token)
async def refresh_token(token_data: TokenRefresh):
    """Refresh access token using refresh token."""
    token_payload = decode_token(token_data.refresh_token)
    
    if not token_payload or token_payload.get("type") != "refresh":
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid refresh token"
        )
    
    # Create new tokens
    access_token_expires = timedelta(minutes=settings.ACCESS_TOKEN_EXPIRE_MINUTES)
    
    token_data_dict = {
        "sub": str(token_payload.user_id),
        "email": token_payload.email,
        "role": token_payload.role
    }
    
    new_access_token = create_access_token(
        data=token_data_dict, expires_delta=access_token_expires
    )
    new_refresh_token = create_refresh_token(data=token_data_dict)
    
    return {
        "access_token": new_access_token,
        "refresh_token": new_refresh_token,
        "token_type": "bearer",
        "expires_in": settings.ACCESS_TOKEN_EXPIRE_MINUTES * 60
    }


@router.post("/logout")
async def logout(
    current_user: dict = Depends(get_current_user),
    cache: CacheService = Depends(get_cache_service)
):
    """Logout user by blacklisting token."""
    # Add token to blacklist in Redis
    await cache.blacklist_token(current_user["token"])
    
    return {"success": True, "message": "Successfully logged out"}
```

---

## 6. MIDDLEWARE & INTERCEPTORS

### 6.1 Request Logging Middleware

```python
# app/core/middleware.py
import time
import uuid
from fastapi import Request
from starlette.middleware.base import BaseHTTPMiddleware

from app.core.logging import logger


class LoggingMiddleware(BaseHTTPMiddleware):
    """Log all API requests with timing and metadata."""
    
    async def dispatch(self, request: Request, call_next):
        request_id = str(uuid.uuid4())
        request.state.request_id = request_id
        
        start_time = time.time()
        
        # Log request
        logger.info(
            f"Request started",
            extra={
                "request_id": request_id,
                "method": request.method,
                "path": request.url.path,
                "client_ip": request.client.host,
                "user_agent": request.headers.get("user-agent")
            }
        )
        
        response = await call_next(request)
        
        process_time = time.time() - start_time
        
        # Log response
        logger.info(
            f"Request completed",
            extra={
                "request_id": request_id,
                "status_code": response.status_code,
                "duration_ms": round(process_time * 1000, 2)
            }
        )
        
        response.headers["X-Request-ID"] = request_id
        return response


class RateLimitMiddleware(BaseHTTPMiddleware):
    """Rate limiting based on client IP and user."""
    
    async def dispatch(self, request: Request, call_next):
        client_id = self._get_client_id(request)
        
        # Check rate limit
        is_allowed, retry_after = await self._check_rate_limit(client_id)
        
        if not is_allowed:
            return JSONResponse(
                status_code=429,
                content={
                    "success": False,
                    "error": {
                        "code": "RATE_LIMIT_EXCEEDED",
                        "message": "Rate limit exceeded",
                        "retry_after": retry_after
                    }
                }
            )
        
        return await call_next(request)
    
    def _get_client_id(self, request: Request) -> str:
        # Use API key if available, otherwise IP
        api_key = request.headers.get("X-API-Key")
        if api_key:
            return f"api:{api_key}"
        return f"ip:{request.client.host}"
    
    async def _check_rate_limit(self, client_id: str) -> tuple:
        # Implementation using Redis
        pass
```

---

## 7. RATE LIMITING & THROTTLING

### 7.1 Rate Limit Configuration

| Client Type | Requests/Minute | Burst | Daily Limit |
|-------------|-----------------|-------|-------------|
| Mobile App | 60 | 10 | 10,000 |
| IoT Device | 120 | 20 | 50,000 |
| B2B Partner | 300 | 50 | 100,000 |
| Webhook | 600 | 100 | 500,000 |
| Anonymous | 10 | 5 | 100 |

### 7.2 Rate Limit Implementation

```python
# app/core/rate_limit.py
import asyncio
from datetime import datetime, timedelta
from typing import Optional

import aioredis


class RateLimiter:
    """Token bucket rate limiter using Redis."""
    
    def __init__(self, redis: aioredis.Redis):
        self.redis = redis
    
    async def is_allowed(
        self, 
        key: str, 
        limit: int, 
        window: int,
        burst: Optional[int] = None
    ) -> tuple[bool, int]:
        """
        Check if request is allowed under rate limit.
        
        Returns:
            (allowed: bool, remaining: int)
        """
        burst = burst or limit
        now = datetime.utcnow()
        bucket_key = f"rate_limit:{key}"
        
        pipe = self.redis.pipeline()
        
        # Get current bucket state
        pipe.hmget(bucket_key, "tokens", "last_update")
        
        result = await pipe.execute()
        tokens_str, last_update_str = result[0]
        
        if tokens_str is None:
            # New bucket
            tokens = burst - 1
            last_update = now
        else:
            tokens = float(tokens_str)
            last_update = datetime.fromisoformat(last_update_str)
            
            # Add tokens based on time passed
            time_passed = (now - last_update).total_seconds()
            tokens_to_add = time_passed * (limit / window)
            tokens = min(burst, tokens + tokens_to_add)
        
        if tokens >= 1:
            # Request allowed
            tokens -= 1
            await self.redis.hmset(bucket_key, {
                "tokens": tokens,
                "last_update": now.isoformat()
            })
            await self.redis.expire(bucket_key, window * 2)
            return True, int(tokens)
        else:
            # Request denied
            await self.redis.hmset(bucket_key, {
                "tokens": tokens,
                "last_update": now.isoformat()
            })
            retry_after = int((1 - tokens) * (window / limit))
            return False, retry_after
```

---

## 8. ERROR HANDLING

### 8.1 Exception Hierarchy

```python
# app/core/exceptions.py

class SmartDairyException(Exception):
    """Base exception for Smart Dairy API."""
    
    def __init__(
        self, 
        message: str, 
        code: str = "GENERAL_ERROR",
        status_code: int = 400,
        details: dict = None
    ):
        self.message = message
        self.code = code
        self.status_code = status_code
        self.details = details or {}
        super().__init__(self.message)


class ValidationError(SmartDairyException):
    """Input validation error."""
    
    def __init__(self, message: str, details: dict = None):
        super().__init__(
            message=message,
            code="VALIDATION_ERROR",
            status_code=422,
            details=details
        )


class AuthenticationError(SmartDairyException):
    """Authentication failure."""
    
    def __init__(self, message: str = "Authentication failed"):
        super().__init__(
            message=message,
            code="AUTHENTICATION_ERROR",
            status_code=401
        )


class AuthorizationError(SmartDairyException):
    """Authorization failure."""
    
    def __init__(self, message: str = "Permission denied"):
        super().__init__(
            message=message,
            code="AUTHORIZATION_ERROR",
            status_code=403
        )


class NotFoundError(SmartDairyException):
    """Resource not found."""
    
    def __init__(self, resource: str, identifier: str):
        super().__init__(
            message=f"{resource} with id '{identifier}' not found",
            code="NOT_FOUND",
            status_code=404,
            details={"resource": resource, "id": identifier}
        )


class ConflictError(SmartDairyException):
    """Resource conflict."""
    
    def __init__(self, message: str):
        super().__init__(
            message=message,
            code="CONFLICT",
            status_code=409
        )


class RateLimitError(SmartDairyException):
    """Rate limit exceeded."""
    
    def __init__(self, retry_after: int):
        super().__init__(
            message="Rate limit exceeded",
            code="RATE_LIMIT_EXCEEDED",
            status_code=429,
            details={"retry_after": retry_after}
        )
```

---

## 9. API DOCUMENTATION

### 9.1 OpenAPI Configuration

```python
# app/main.py (continued)

app = FastAPI(
    title="Smart Dairy API Gateway",
    description="""
    Smart Dairy API Gateway provides comprehensive endpoints for:
    
    - **Mobile Applications**: Customer, Field Sales, and Farmer apps
    - **IoT Integration**: Sensor data ingestion and device management
    - **B2B Portal**: Wholesale ordering and account management
    - **Payment Processing**: Multiple payment gateway integrations
    - **Webhook Handlers**: Asynchronous event processing
    
    ## Authentication
    
    Most endpoints require authentication using JWT tokens:
    1. Obtain token via `/api/v1/auth/login`
    2. Include token in header: `Authorization: Bearer <token>`
    
    ## Rate Limiting
    
    API requests are rate-limited based on client type:
    - Mobile App: 60 requests/minute
    - IoT Device: 120 requests/minute
    - B2B Partner: 300 requests/minute
    
    ## Error Codes
    
    | Code | HTTP Status | Description |
    |------|-------------|-------------|
    | VALIDATION_ERROR | 422 | Input validation failed |
    | AUTHENTICATION_ERROR | 401 | Invalid credentials |
    | AUTHORIZATION_ERROR | 403 | Insufficient permissions |
    | NOT_FOUND | 404 | Resource not found |
    | RATE_LIMIT_EXCEEDED | 429 | Too many requests |
    """,
    version="1.0.0",
    contact={
        "name": "Smart Dairy Technical Team",
        "email": "tech@smartdairybd.com",
        "url": "https://smartdairybd.com/api-docs"
    },
    license_info={
        "name": "Private - Smart Dairy Ltd.",
    }
)
```

---

## 10. PERFORMANCE OPTIMIZATION

### 10.1 Optimization Strategies

| Strategy | Implementation | Expected Improvement |
|----------|---------------|---------------------|
| **Connection Pooling** | httpx AsyncClient with limits | 40% latency reduction |
| **Response Caching** | Redis caching for frequent queries | 60% response time |
| **Database Indexing** | Optimized Odoo queries | 50% query time |
| **Async Operations** | Full async/await pattern | 3x throughput |
| **Compression** | Gzip middleware | 70% bandwidth reduction |

### 10.2 Caching Implementation

```python
# app/services/cache_service.py
import json
from typing import Optional, Any
import aioredis


class CacheService:
    """Redis-based caching service."""
    
    def __init__(self):
        self.redis: Optional[aioredis.Redis] = None
    
    async def connect(self):
        self.redis = await aioredis.from_url(
            "redis://localhost:6379",
            encoding="utf-8",
            decode_responses=True
        )
    
    async def disconnect(self):
        if self.redis:
            await self.redis.close()
    
    async def get(self, key: str) -> Optional[Any]:
        value = await self.redis.get(key)
        if value:
            return json.loads(value)
        return None
    
    async def set(
        self, 
        key: str, 
        value: Any, 
        ttl: int = 300
    ):
        await self.redis.setex(
            key,
            ttl,
            json.dumps(value, default=str)
        )
    
    async def delete(self, key: str):
        await self.redis.delete(key)
    
    async def get_or_set(
        self, 
        key: str, 
        factory, 
        ttl: int = 300
    ):
        """Get from cache or compute and store."""
        value = await self.get(key)
        if value is None:
            value = await factory()
            await self.set(key, value, ttl)
        return value
```

---

## 11. APPENDICES

### Appendix A: Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `SECRET_KEY` | JWT signing key | Required |
| `DATABASE_URL` | PostgreSQL connection | Required |
| `REDIS_URL` | Redis connection | Required |
| `ODOO_URL` | Odoo instance URL | Required |
| `ODOO_DB` | Odoo database name | Required |
| `ACCESS_TOKEN_EXPIRE_MINUTES` | JWT access token TTL | 30 |
| `REFRESH_TOKEN_EXPIRE_DAYS` | JWT refresh token TTL | 7 |
| `RATE_LIMIT_DEFAULT` | Default rate limit | 60/min |
| `DEBUG` | Debug mode | False |

### Appendix B: HTTP Status Codes

| Code | Usage |
|------|-------|
| 200 | Successful GET, PUT, PATCH |
| 201 | Successful POST (resource created) |
| 204 | Successful DELETE |
| 400 | Bad request (malformed) |
| 401 | Unauthorized (authentication required) |
| 403 | Forbidden (permission denied) |
| 404 | Resource not found |
| 409 | Conflict (duplicate, etc.) |
| 422 | Validation error |
| 429 | Rate limit exceeded |
| 500 | Internal server error |

---

**END OF API GATEWAY DESIGN DOCUMENT**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | February 10, 2026 | Solution Architect | Initial version |
