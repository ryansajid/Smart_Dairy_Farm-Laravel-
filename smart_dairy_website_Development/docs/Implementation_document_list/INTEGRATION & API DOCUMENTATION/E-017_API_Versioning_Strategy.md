# API Versioning Strategy

---

**Document ID:** E-017  
**Version:** 1.0  
**Date:** January 31, 2026  
**Author:** Integration Engineer  
**Owner:** Tech Lead  
**Reviewer:** CTO  
**Classification:** Technical Specification

---

## Document Control

| Version | Date | Author | Changes | Status |
|---------|------|--------|---------|--------|
| 1.0 | 2026-01-31 | Integration Engineer | Initial document | Draft |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Versioning Approaches](#2-versioning-approaches)
3. [URL Path Versioning](#3-url-path-versioning)
4. [Version Lifecycle](#4-version-lifecycle)
5. [Breaking Changes](#5-breaking-changes)
6. [Non-Breaking Changes](#6-non-breaking-changes)
7. [Deprecation Strategy](#7-deprecation-strategy)
8. [Documentation](#8-documentation)
9. [Implementation](#9-implementation)
10. [Backward Compatibility](#10-backward-compatibility)
11. [Version Discovery](#11-version-discovery)
12. [Testing](#12-testing)
13. [Client Communication](#13-client-communication)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the API versioning strategy for Smart Dairy Ltd.'s Smart Web Portal System. It establishes guidelines for managing API evolution while maintaining stability for existing consumers.

### 1.2 Scope

This strategy applies to:
- REST API endpoints
- GraphQL APIs (introduced in v2)
- Webhook payloads
- API client libraries

### 1.3 Why API Versioning Matters

API versioning is critical for:

| Benefit | Description |
|---------|-------------|
| **Stability** | Existing integrations continue working during upgrades |
| **Innovation** | New features can be added without breaking existing clients |
| **Trust** | Developers can rely on consistent API behavior |
| **Migration Control** | Consumers upgrade on their own timeline |

### 1.4 Breaking vs Non-Breaking Changes

#### Breaking Changes (Major Version Bump)

Breaking changes require clients to modify their integration code:

- Removing or renaming fields, endpoints, or parameters
- Changing data types of existing fields
- Changing authentication mechanisms
- Modifying error response structures
- Changing HTTP methods for existing endpoints
- Removing or changing enum values
- Modifying default behaviors

#### Non-Breaking Changes (Minor/Patch)

Non-breaking changes are backward compatible:

- Adding new optional fields to responses
- Adding new endpoints
- Adding new optional query parameters
- Adding new enum values
- Expanding acceptable input formats
- Performance improvements
- Bug fixes that don't change documented behavior

---

## 2. Versioning Approaches

### 2.1 Comparison of Versioning Strategies

| Approach | Example | Pros | Cons | Recommendation |
|----------|---------|------|------|----------------|
| **URL Path** | `/v1/users` | Simple, cache-friendly, explicit | URL changes between versions | ✅ **Selected** |
| **Query Parameter** | `/users?api-version=1` | Clean URLs | Easy to miss, not cache-friendly | ❌ Not selected |
| **Header** | `API-Version: 1` | Clean URLs, explicit | Harder to test, less visible | ⚠️ Secondary |
| **Content Negotiation** | `Accept: application/vnd.api+json;version=1` | RESTful standard | Complex, hard to debug | ❌ Not selected |

### 2.2 Selection Rationale

Smart Dairy Ltd. selected **URL Path Versioning** as the primary approach because:

1. **Developer Experience**: Version is clearly visible in the URL
2. **Caching**: Different versions can be cached independently
3. **Testing**: Easy to test different versions via simple URL changes
4. **Documentation**: Natural fit for API documentation organization
5. **Industry Standard**: Widely adopted by major API providers

---

## 3. URL Path Versioning

### 3.1 URL Structure

```
https://api.smartdairy.com/v1/users
https://api.smartdairy.com/v2/users
```

### 3.2 Version Format

- Format: `v{major}` (e.g., `v1`, `v2`, `v3`)
- Major version increments indicate breaking changes
- Minor versions are NOT included in URL (handled via date-based updates)

### 3.3 Endpoint Examples

| Version | Endpoint | Description |
|---------|----------|-------------|
| v1 | `GET /v1/milk-production` | Current stable milk production API |
| v1 | `POST /v1/cattle` | Create cattle record |
| v2 | `GET /v2/milk-production` | Enhanced with GraphQL support |
| v2 | `GET /v2/health-monitoring` | New IoT sensor integration |
| v2 | `POST /v2/graphql` | GraphQL endpoint (v2 only) |

### 3.4 Default Version Behavior

```python
# No default version - explicit version required
# Requests without version return 404 or redirect to latest
```

---

## 4. Version Lifecycle

### 4.1 Lifecycle Phases

```
┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐
│  Alpha  │───▶│  Beta   │───▶│ Stable  │───▶│Deprecated│───▶│  Sunset  │
│ (0.x.x) │    │ (0.x.x) │    │ (1.x.x) │    │ (1.x.x)  │    │ (EOL)   │
└─────────┘    └─────────┘    └─────────┘    └─────────┘    └─────────┘
  2-4 weeks      4-8 weeks      12+ months      6-12 months    Deleted
```

### 4.2 Phase Definitions

| Phase | Description | Support Level | SLA |
|-------|-------------|---------------|-----|
| **Alpha** | Internal testing only | No support | No SLA |
| **Beta** | Limited external access | Best effort | No SLA |
| **Stable** | Production use | Full support | 99.9% uptime |
| **Deprecated** | Maintenance only | Critical bugs only | 99.5% uptime |
| **Sunset** | End of life, removed | None | N/A |

### 4.3 Version Timeline Template

| Version | Alpha | Beta | Stable | Deprecated | Sunset |
|---------|-------|------|--------|------------|--------|
| v1 | 2025-01-01 | 2025-02-01 | 2025-03-01 | 2026-09-01 | 2027-03-01 |
| v2 | 2026-01-01 | 2026-02-01 | 2026-04-01 | TBD | TBD |

---

## 5. Breaking Changes

### 5.1 When to Increment Major Version

A major version increment (v1 → v2) is required when:

- [ ] Removing any existing endpoint
- [ ] Removing or renaming response fields
- [ ] Changing data types (string → number, etc.)
- [ ] Making optional fields required
- [ ] Changing authentication requirements
- [ ] Modifying pagination behavior
- [ ] Changing webhook payload structure
- [ ] Removing support for specific query parameters

### 5.2 Breaking Change Documentation

Each breaking change must document:

1. **What changed** (specific field/endpoint differences)
2. **Why it changed** (business/technical justification)
3. **Migration path** (how to update client code)
4. **Timeline** (deprecation and sunset dates)

### 5.3 Breaking Changes in v2

| Change | v1 | v2 | Migration |
|--------|-----|-----|-----------|
| Authentication | API Key only | OAuth 2.0 + API Key | Update auth headers |
| Pagination | Offset-based | Cursor-based | Update pagination params |
| Milk Production | `/v1/milk-production` | `/v2/milk-production` | Update URL path |
| Response Format | Flat JSON | Nested with metadata | Parse new structure |
| GraphQL | Not available | Full support | New integration option |

---

## 6. Non-Breaking Changes

### 6.1 When to Add (Not Bump)

Non-breaking changes that DON'T require version bump:

- Adding new optional fields to responses
- Adding new endpoints
- Adding new optional query parameters
- Adding new enum values
- Adding new webhook event types
- Performance improvements
- Security enhancements

### 6.2 Response Field Addition Example

```python
# v1.0 Response
{
    "id": "cattle-001",
    "name": "Bessie",
    "breed": "Holstein"
}

# v1.1 Response (non-breaking addition)
{
    "id": "cattle-001",
    "name": "Bessie",
    "breed": "Holstein",
    "date_of_birth": "2020-03-15",  # New optional field
    "health_status": "healthy"       # New optional field
}
```

### 6.3 New Endpoint Addition

```python
# Adding new endpoint is non-breaking
# v1.0 has: GET /v1/cattle
# v1.1 adds: GET /v1/cattle/{id}/health-records (new endpoint)
```

### 6.4 Minor Version Tracking

While URL uses major versions only, minor changes are tracked:

- Via `X-API-Revision` header
- In changelog documentation
- In SDK version constraints

---

## 7. Deprecation Strategy

### 7.1 Deprecation Timeline

```
Deprecation Notice ── 6 months ──→ Sunset Warning ── 3 months ──→ End of Life
         │                              │                           │
         ▼                              ▼                           ▼
   Email announcement             Status page                Version removed
   Documentation update           Final warnings             Redirect to v+1
   Migration guide published
```

### 7.2 Advance Notice Requirements

| Notice Type | Timeline | Communication Channel |
|-------------|----------|----------------------|
| Deprecation Announcement | 12 months before sunset | Email, blog, status page |
| Migration Guide | With deprecation notice | Documentation |
| Sunset Warning | 3 months before sunset | Email, API headers |
| Final Notice | 1 month before sunset | Email, API headers, dashboard |

### 7.3 Migration Guide Requirements

Each deprecation must include:

1. Side-by-side code comparison (v1 vs v2)
2. Step-by-step migration instructions
3. Common pitfalls and solutions
4. Testing checklist
5. Support contact information

### 7.4 Deprecation Headers

```http
Deprecation: true
Sunset: Sat, 31 Oct 2026 00:00:00 GMT
Link: </v2/users>; rel="successor-version"
```

### 7.5 Sunset Policy

- Minimum support period: 18 months from stable release
- No extensions without CTO approval
- Final 30 days: Daily reminder emails to affected consumers

---

## 8. Documentation

### 8.1 Version Comparison Documentation

| Aspect | v1 | v2 |
|--------|-----|-----|
| Authentication | API Key | OAuth 2.0 + API Key |
| Pagination | Offset | Cursor |
| Rate Limit | 1000/hour | 5000/hour |
| GraphQL | ❌ | ✅ |
| Bulk Operations | ❌ | ✅ |
| Real-time WebSocket | ❌ | ✅ |

### 8.2 Changelog Format

```markdown
## Changelog

### v2.0.0 (2026-04-01)
- ✨ Added GraphQL endpoint
- ✨ Added real-time WebSocket support
- ✨ Added bulk operations
- 🔒 Enhanced authentication with OAuth 2.0
- ⚠️ Breaking: Changed pagination to cursor-based
- ⚠️ Breaking: Modified milk production response format

### v1.2.0 (2025-12-01)
- ✨ Added health monitoring endpoints
- ✨ Added IoT sensor data ingestion
- 🐛 Fixed cattle breed enumeration
- 📈 Improved query performance

### v1.1.0 (2025-06-01)
- ✨ Added financial reporting endpoints
- ✨ Added export to CSV/Excel
```

### 8.3 Migration Guide Structure

1. **Executive Summary** - Why upgrade
2. **Breaking Changes** - What will break
3. **Prerequisites** - What you need
4. **Step-by-Step Migration** - How to upgrade
5. **Code Examples** - Before/after samples
6. **Testing** - Validation checklist
7. **Rollback Plan** - How to revert
8. **FAQ** - Common questions
9. **Support** - How to get help

---

## 9. Implementation

### 9.1 Project Structure

```
app/
├── api/
│   ├── __init__.py
│   ├── v1/
│   │   ├── __init__.py
│   │   ├── router.py
│   │   ├── cattle.py
│   │   ├── milk_production.py
│   │   └── health.py
│   └── v2/
│       ├── __init__.py
│       ├── router.py
│       ├── cattle.py
│       ├── milk_production.py
│       ├── health.py
│       └── graphql/
├── core/
│   ├── config.py
│   └── versioning.py
├── middleware/
│   └── version_middleware.py
└── main.py
```

### 9.2 Versioned FastAPI Routers

```python
# app/api/v1/router.py
"""API v1 router configuration."""

from fastapi import APIRouter

from app.api.v1 import cattle, health, milk_production

api_router = APIRouter(prefix="/v1")

api_router.include_router(cattle.router, prefix="/cattle", tags=["cattle"])
api_router.include_router(
    milk_production.router, 
    prefix="/milk-production", 
    tags=["milk-production"]
)
api_router.include_router(health.router, prefix="/health", tags=["health"])
```

```python
# app/api/v2/router.py
"""API v2 router configuration with GraphQL support."""

from fastapi import APIRouter

from app.api.v2 import cattle, health, milk_production, graphql

api_router = APIRouter(prefix="/v2")

api_router.include_router(cattle.router, prefix="/cattle", tags=["cattle"])
api_router.include_router(
    milk_production.router, 
    prefix="/milk-production", 
    tags=["milk-production"]
)
api_router.include_router(health.router, prefix="/health", tags=["health"])
api_router.include_router(graphql.router, prefix="/graphql", tags=["graphql"])
```

### 9.3 Main Application Setup

```python
# app/main.py
"""Smart Dairy API main application."""

from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from app.api.v1.router import api_router as v1_router
from app.api.v2.router import api_router as v2_router
from app.middleware.version_middleware import VersionMiddleware
from app.core.versioning import get_version_info

app = FastAPI(
    title="Smart Dairy API",
    description="API for Smart Dairy Management System",
    version="2.0.0",
    docs_url="/docs",
    redoc_url="/redoc",
)

# Add version middleware
app.add_middleware(VersionMiddleware)

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Include versioned routers
app.include_router(v1_router)
app.include_router(v2_router)


@app.get("/")
async def root():
    """API root endpoint."""
    return {
        "name": "Smart Dairy API",
        "versions": ["v1", "v2"],
        "current": "v2",
        "documentation": "/docs"
    }


@app.get("/versions")
async def list_versions():
    """List all available API versions."""
    return get_version_info()
```

### 9.4 Version Middleware

```python
# app/middleware/version_middleware.py
"""Version middleware for handling deprecation headers."""

from fastapi import Request, Response
from starlette.middleware.base import BaseHTTPMiddleware

from app.core.versioning import (
    get_deprecation_info,
    get_version_lifecycle,
    is_deprecated,
)


class VersionMiddleware(BaseHTTPMiddleware):
    """Middleware to add version-related headers to responses."""
    
    async def dispatch(self, request: Request, call_next):
        """Process request and add version headers."""
        response: Response = await call_next(request)
        
        # Extract version from URL path
        version = self._extract_version(request.url.path)
        
        if version:
            # Add version info header
            response.headers["X-API-Version"] = version
            
            # Add deprecation headers if version is deprecated
            if is_deprecated(version):
                deprecation_info = get_deprecation_info(version)
                response.headers["Deprecation"] = "true"
                response.headers["Sunset"] = deprecation_info["sunset_date"]
                
                # Link to successor version
                successor = deprecation_info.get("successor")
                if successor:
                    response.headers["Link"] = (
                        f"</{successor}{request.url.path[len(version)+1:]}>; "
                        f'rel="successor-version"'
                    )
            
            # Add latest version header
            response.headers["X-API-Latest-Version"] = "v2"
            
            # Add revision info
            lifecycle = get_version_lifecycle(version)
            response.headers["X-API-Revision"] = lifecycle["revision"]
        
        return response
    
    def _extract_version(self, path: str) -> str | None:
        """Extract version from URL path."""
        parts = path.strip("/").split("/")
        if parts and parts[0].startswith("v"):
            return parts[0]
        return None
```

### 9.5 Core Versioning Utilities

```python
# app/core/versioning.py
"""Core versioning utilities and configuration."""

from datetime import datetime
from enum import Enum
from typing import Any


class VersionPhase(Enum):
    """API version lifecycle phases."""
    ALPHA = "alpha"
    BETA = "beta"
    STABLE = "stable"
    DEPRECATED = "deprecated"
    SUNSET = "sunset"


# Version registry with lifecycle information
VERSION_REGISTRY = {
    "v1": {
        "phase": VersionPhase.DEPRECATED,
        "revision": "1.2.3",
        "release_date": "2025-03-01",
        "deprecated_date": "2026-04-01",
        "sunset_date": "2026-10-01",
        "successor": "v2",
        "features": ["REST API", "Basic CRUD", "Webhook support"],
    },
    "v2": {
        "phase": VersionPhase.STABLE,
        "revision": "2.0.0",
        "release_date": "2026-04-01",
        "deprecated_date": None,
        "sunset_date": None,
        "successor": None,
        "features": [
            "GraphQL support",
            "WebSocket real-time",
            "Bulk operations",
            "OAuth 2.0",
            "Enhanced pagination"
        ],
    },
}


def get_version_info() -> dict[str, Any]:
    """Get information about all API versions."""
    return {
        "versions": [
            {
                "version": version,
                "phase": info["phase"].value,
                "revision": info["revision"],
                "release_date": info["release_date"],
                "deprecated": info["phase"] == VersionPhase.DEPRECATED,
                "sunset_date": info.get("sunset_date"),
                "documentation": f"/docs/{version}",
            }
            for version, info in VERSION_REGISTRY.items()
        ],
        "current": "v2",
        "recommended": "v2",
    }


def get_version_lifecycle(version: str) -> dict[str, Any]:
    """Get lifecycle information for a specific version."""
    if version not in VERSION_REGISTRY:
        return {}
    return VERSION_REGISTRY[version]


def is_deprecated(version: str) -> bool:
    """Check if a version is deprecated."""
    if version not in VERSION_REGISTRY:
        return False
    return VERSION_REGISTRY[version]["phase"] == VersionPhase.DEPRECATED


def get_deprecation_info(version: str) -> dict[str, Any]:
    """Get deprecation information for a version."""
    if not is_deprecated(version):
        return {}
    
    info = VERSION_REGISTRY[version]
    return {
        "deprecated_date": info["deprecated_date"],
        "sunset_date": info["sunset_date"],
        "successor": info.get("successor"),
        "migration_guide": f"/docs/migration/{version}-to-{info.get('successor')}",
    }


def get_latest_version() -> str:
    """Get the latest stable API version."""
    for version, info in reversed(VERSION_REGISTRY.items()):
        if info["phase"] == VersionPhase.STABLE:
            return version
    return list(VERSION_REGISTRY.keys())[-1]


def compare_versions(v1: str, v2: str) -> int:
    """
    Compare two version strings.
    
    Returns:
        -1 if v1 < v2, 0 if v1 == v2, 1 if v1 > v2
    """
    def parse_version(v: str) -> tuple[int, ...]:
        # Remove 'v' prefix and split by '.'
        parts = v.lstrip("v").split(".")
        return tuple(int(x) for x in parts)
    
    parsed_v1 = parse_version(v1)
    parsed_v2 = parse_version(v2)
    
    if parsed_v1 < parsed_v2:
        return -1
    elif parsed_v1 > parsed_v2:
        return 1
    return 0


def is_supported(version: str) -> bool:
    """Check if a version is still supported (not sunset)."""
    if version not in VERSION_REGISTRY:
        return False
    return VERSION_REGISTRY[version]["phase"] != VersionPhase.SUNSET
```

### 9.6 Shared Components Between Versions

```python
# app/core/shared_models.py
"""Shared Pydantic models used across API versions."""

from datetime import date, datetime
from typing import Optional

from pydantic import BaseModel, Field


class CattleBase(BaseModel):
    """Base cattle model - shared between versions."""
    name: str = Field(..., description="Cattle name")
    breed: str = Field(..., description="Cattle breed")
    tag_number: Optional[str] = Field(None, description="RFID tag number")


class MilkProductionBase(BaseModel):
    """Base milk production model - shared between versions."""
    cattle_id: str = Field(..., description="Reference to cattle")
    quantity_liters: float = Field(..., gt=0, description="Milk quantity in liters")
    milking_date: date = Field(..., description="Date of milking")
    milking_session: str = Field(..., description="Morning/Afternoon/Evening")


# Version-specific models extend the base
class CattleV1(CattleBase):
    """v1 cattle model."""
    id: str
    created_at: datetime


class CattleV2(CattleBase):
    """v2 cattle model with additional fields."""
    id: str
    date_of_birth: Optional[date] = None
    health_status: str = "healthy"
    last_vaccination: Optional[date] = None
    created_at: datetime
    updated_at: datetime
```

---

## 10. Backward Compatibility

### 10.1 Supporting Older Versions

```python
# app/core/compatibility.py
"""Backward compatibility utilities."""

from typing import Any, Callable
from functools import wraps

from fastapi import HTTPException, Request

from app.core.versioning import is_supported, compare_versions


class CompatibilityLayer:
    """Handles backward compatibility transformations."""
    
    @staticmethod
    def transform_v1_to_v2(data: dict[str, Any]) -> dict[str, Any]:
        """Transform v1 response format to v2."""
        # v1 uses flat structure, v2 uses nested metadata
        return {
            "data": data,
            "meta": {
                "version": "v2",
                "transformed": True,
                "source_version": "v1"
            }
        }
    
    @staticmethod
    def transform_v2_to_v1(data: dict[str, Any]) -> dict[str, Any]:
        """Transform v2 response format to v1."""
        # Extract flat data from v2 nested structure
        if "data" in data:
            return data["data"]
        return data
    
    @staticmethod
    def adapt_pagination_v1(params: dict[str, Any]) -> dict[str, Any]:
        """Adapt v1 offset pagination to v2 cursor pagination."""
        if "page" in params or "limit" in params:
            # Convert offset to cursor (simplified)
            return {
                "cursor": params.get("page", "0"),
                "limit": params.get("limit", 20)
            }
        return params


def version_adapter(target_version: str):
    """Decorator to adapt responses for specific API versions."""
    def decorator(func: Callable):
        @wraps(func)
        async def wrapper(*args, **kwargs):
            result = await func(*args, **kwargs)
            
            # Get version from request if available
            request = kwargs.get("request")
            if request and hasattr(request, "url"):
                version = request.url.path.split("/")[1] if "/" in request.url.path else "v2"
                
                if version == "v1" and target_version == "v2":
                    return CompatibilityLayer.transform_v2_to_v1(result)
            
            return result
        return wrapper
    return decorator
```

### 10.2 Deprecation Warnings

```python
# app/core/warnings.py
"""Deprecation warning utilities."""

import warnings
from typing import Any


class APIDeprecationWarning(DeprecationWarning):
    """Custom warning for API deprecations."""
    pass


class VersionWarningManager:
    """Manages deprecation warnings for API versions."""
    
    _warnings_issued: set[str] = set()
    
    @classmethod
    def warn_deprecated(cls, version: str, endpoint: str):
        """Issue a deprecation warning for a version/endpoint."""
        warning_key = f"{version}:{endpoint}"
        
        if warning_key not in cls._warnings_issued:
            warnings.warn(
                f"API version {version} endpoint '{endpoint}' is deprecated. "
                f"Please migrate to the latest version.",
                APIDeprecationWarning,
                stacklevel=2
            )
            cls._warnings_issued.add(warning_key)
    
    @classmethod
    def get_warning_response_header(cls, version: str) -> dict[str, str]:
        """Get warning header for HTTP responses."""
        return {
            "Warning": f'299 - "API version {version} is deprecated"',
            "X-API-Warning": f"Version {version} deprecated. Migrate to v2."
        }
```

---

## 11. Version Discovery

### 11.1 API Version Endpoint

```python
# app/api/common/version_endpoint.py
"""Common version discovery endpoints."""

from fastapi import APIRouter

from app.core.versioning import (
    get_version_info,
    get_version_lifecycle,
    get_latest_version,
)

router = APIRouter()


@router.get("/versions")
async def list_all_versions():
    """
    List all available API versions.
    
    Returns comprehensive information about all API versions,
    their lifecycle status, and links to documentation.
    """
    return get_version_info()


@router.get("/versions/current")
async def get_current_version():
    """Get the current/recommended API version."""
    return {
        "version": get_latest_version(),
        "documentation": f"/docs/{get_latest_version()}",
        "changelog": "/changelog",
    }


@router.get("/versions/{version}")
async def get_version_details(version: str):
    """Get detailed information about a specific version."""
    lifecycle = get_version_lifecycle(version)
    
    if not lifecycle:
        return {"error": "Version not found", "available": list(get_version_info()["versions"])}
    
    return {
        "version": version,
        "phase": lifecycle["phase"].value,
        "revision": lifecycle["revision"],
        "release_date": lifecycle["release_date"],
        "features": lifecycle["features"],
        "documentation": f"/docs/{version}",
        "endpoints": f"/{version}/",
    }
```

### 11.2 Version Response Headers

```python
# app/middleware/response_headers.py
"""Response header utilities for version information."""

from fastapi import Response

from app.core.versioning import get_latest_version


def add_version_headers(response: Response, version: str) -> None:
    """Add standard version headers to response."""
    
    # Current version of the response
    response.headers["X-API-Version"] = version
    
    # Latest available version
    response.headers["X-API-Latest-Version"] = get_latest_version()
    
    # API provider info
    response.headers["X-API-Provider"] = "Smart Dairy Ltd"
    
    # Support contact
    response.headers["X-API-Support"] = "api-support@smartdairy.com"
    
    # Documentation link
    response.headers["Link"] = f'</docs/{version}>; rel="documentation"'


def add_deprecation_headers(
    response: Response, 
    version: str, 
    sunset_date: str,
    successor: str
) -> None:
    """Add deprecation-specific headers."""
    response.headers["Deprecation"] = "true"
    response.headers["Sunset"] = sunset_date
    response.headers["Link"] = (
        f'</{successor}>; rel="successor-version", '
        f'</docs/migration/{version}-to-{successor}>; rel="migration-guide"'
    )
```

### 11.3 Health Check with Version

```python
# app/api/common/health.py
"""Health check endpoints with version information."""

from fastapi import APIRouter
from datetime import datetime

from app.core.versioning import get_version_info, get_latest_version

router = APIRouter()


@router.get("/health")
async def health_check():
    """
    Health check endpoint with version info.
    
    Returns current API health status and version information.
    """
    return {
        "status": "healthy",
        "timestamp": datetime.utcnow().isoformat(),
        "api": {
            "name": "Smart Dairy API",
            "current_version": get_latest_version(),
            "versions": [v["version"] for v in get_version_info()["versions"]],
        },
        "services": {
            "database": "healthy",
            "cache": "healthy",
            "queue": "healthy",
        }
    }
```

---

## 12. Testing

### 12.1 Testing Across Multiple Versions

```python
# tests/test_versioning.py
"""Tests for API versioning functionality."""

import pytest
from fastapi.testclient import TestClient

from app.main import app
from app.core.versioning import compare_versions, is_deprecated, is_supported

client = TestClient(app)


class TestVersionComparison:
    """Test version comparison utilities."""
    
    def test_compare_equal_versions(self):
        assert compare_versions("v1", "v1") == 0
        assert compare_versions("v1.0", "v1.0") == 0
    
    def test_compare_different_versions(self):
        assert compare_versions("v1", "v2") == -1
        assert compare_versions("v2", "v1") == 1
    
    def test_compare_with_patch(self):
        assert compare_versions("v1.0.0", "v1.0.1") == -1
        assert compare_versions("v2.1.0", "v2.0.9") == 1


class TestVersionDiscovery:
    """Test version discovery endpoints."""
    
    def test_list_versions(self):
        response = client.get("/versions")
        assert response.status_code == 200
        data = response.json()
        assert "versions" in data
        assert "current" in data
        assert "recommended" in data
    
    def test_version_headers(self):
        response = client.get("/v1/cattle")
        assert "X-API-Version" in response.headers
        assert "X-API-Latest-Version" in response.headers
    
    def test_health_check(self):
        response = client.get("/health")
        assert response.status_code == 200
        assert response.json()["api"]["current_version"] == "v2"


class TestDeprecationHeaders:
    """Test deprecation header functionality."""
    
    def test_deprecated_version_headers(self):
        """v1 should include deprecation headers."""
        response = client.get("/v1/cattle")
        assert response.status_code in [200, 404]  # 404 if no data
        
        if response.status_code == 200:
            assert response.headers.get("Deprecation") == "true"
            assert "Sunset" in response.headers
            assert "Link" in response.headers
    
    def test_stable_version_no_deprecation(self):
        """v2 should not have deprecation headers."""
        response = client.get("/v2/cattle")
        assert response.status_code in [200, 404]
        
        if response.status_code == 200:
            assert "Deprecation" not in response.headers


class TestCrossVersionCompatibility:
    """Test compatibility between versions."""
    
    def test_v1_v2_endpoint_structure(self):
        """Verify endpoints exist in both versions where applicable."""
        v1_endpoints = ["/v1/cattle", "/v1/milk-production"]
        v2_endpoints = ["/v2/cattle", "/v2/milk-production"]
        
        for endpoint in v1_endpoints:
            response = client.get(endpoint)
            assert response.status_code in [200, 401, 403, 404]  # Auth or empty is ok
        
        for endpoint in v2_endpoints:
            response = client.get(endpoint)
            assert response.status_code in [200, 401, 403, 404]
    
    def test_graphql_only_in_v2(self):
        """GraphQL endpoint should only exist in v2."""
        v1_response = client.get("/v1/graphql")
        v2_response = client.get("/v2/graphql")
        
        assert v1_response.status_code == 404
        assert v2_response.status_code in [200, 400, 401, 403]  # GraphQL accepts POST
```

### 12.2 Regression Testing

```python
# tests/test_regression.py
"""Regression tests for API versions."""

import pytest
from fastapi.testclient import TestClient

from app.main import app

client = TestClient(app)


class TestV1Regression:
    """Regression tests for v1 API."""
    
    @pytest.mark.parametrize("endpoint,method", [
        ("/v1/cattle", "GET"),
        ("/v1/cattle", "POST"),
        ("/v1/milk-production", "GET"),
        ("/v1/milk-production", "POST"),
    ])
    def test_v1_endpoints_stable(self, endpoint, method):
        """Verify v1 endpoints maintain expected behavior."""
        if method == "GET":
            response = client.get(endpoint)
        else:
            response = client.post(endpoint, json={})
        
        # Should not return 404 (endpoint should exist)
        assert response.status_code != 404
    
    def test_v1_response_format(self):
        """Verify v1 response format hasn't changed."""
        response = client.get("/v1/cattle")
        
        if response.status_code == 200:
            data = response.json()
            # v1 returns list or dict, not nested with metadata
            assert "data" not in data or isinstance(data, list)


class TestV2Regression:
    """Regression tests for v2 API."""
    
    def test_v2_enhanced_response_format(self):
        """Verify v2 response format with metadata."""
        response = client.get("/v2/cattle")
        
        if response.status_code == 200:
            data = response.json()
            # v2 uses nested response with metadata
            if isinstance(data, dict):
                assert "data" in data or "items" in data
    
    def test_v2_pagination_cursor(self):
        """Verify v2 uses cursor pagination."""
        response = client.get("/v2/cattle")
        
        if response.status_code == 200:
            data = response.json()
            if isinstance(data, dict) and "pagination" in data:
                pagination = data["pagination"]
                # v2 uses cursor, not offset
                assert "cursor" in pagination or "next_cursor" in pagination
                assert "page" not in pagination
```

### 12.3 Contract Testing

```python
# tests/test_contract.py
"""API contract tests."""

import pytest
from fastapi.testclient import TestClient

from app.main import app

client = TestClient(app)


class TestV1Contract:
    """Verify v1 API contract is maintained."""
    
    REQUIRED_FIELDS = {
        "/v1/cattle": ["id", "name", "breed"],
        "/v1/milk-production": ["id", "cattle_id", "quantity_liters"],
    }
    
    def test_v1_cattle_fields(self):
        """Verify v1 cattle response contains required fields."""
        response = client.get("/v1/cattle")
        
        if response.status_code == 200:
            data = response.json()
            if isinstance(data, list) and len(data) > 0:
                for field in self.REQUIRED_FIELDS["/v1/cattle"]:
                    assert field in data[0], f"Missing required field: {field}"


class TestV2Contract:
    """Verify v2 API contract."""
    
    def test_v2_nested_response(self):
        """Verify v2 uses nested response structure."""
        response = client.get("/v2/cattle")
        
        if response.status_code == 200:
            data = response.json()
            assert isinstance(data, dict)
            assert "data" in data
            assert "meta" in data or "pagination" in data
```

---

## 13. Client Communication

### 13.1 Developer Newsletter

**Smart Dairy API Newsletter - Version 2.0 Release**

```
Subject: [ACTION REQUIRED] Smart Dairy API v2.0 Now Available - v1 Deprecation Notice

Dear Smart Dairy API Consumer,

We're excited to announce the release of Smart Dairy API v2.0! This version 
brings powerful new features including GraphQL support, real-time WebSockets, 
and improved authentication.

⚠️ IMPORTANT: API v1 will be deprecated on October 1, 2026.

Key Dates:
- April 1, 2026: v2.0 Stable Release
- April 1, 2026: v1 enters deprecation period
- October 1, 2026: v1 Sunset (End of Life)

What's New in v2.0:
✨ GraphQL endpoint for flexible queries
✨ Real-time WebSocket support
✨ OAuth 2.0 authentication
✨ Cursor-based pagination
✨ Bulk operations

Breaking Changes:
⚠️ Authentication: API Key → OAuth 2.0
⚠️ Pagination: Offset → Cursor-based
⚠️ Response format changes

Migration Resources:
📖 Migration Guide: https://docs.smartdairy.com/migration/v1-to-v2
📊 Breaking Changes: https://docs.smartdairy.com/changelog/v2
💬 Support: api-support@smartdairy.com

Best regards,
Smart Dairy API Team
```

### 13.2 API Status Page Integration

```python
# app/core/status_page.py
"""API Status page integration utilities."""

from datetime import datetime
from typing import Any


class StatusPageNotifier:
    """Handles notifications to API status page."""
    
    @staticmethod
    def create_incident(
        title: str,
        body: str,
        status: str = "investigating",
        components: list[str] = None
    ) -> dict[str, Any]:
        """Create a status page incident."""
        # Integration with status page provider (e.g., StatusPage.io)
        return {
            "incident": {
                "name": title,
                "status": status,
                "body": body,
                "components": components or [],
                "created_at": datetime.utcnow().isoformat(),
            }
        }
    
    @staticmethod
    def create_maintenance_event(
        title: str,
        scheduled_for: datetime,
        scheduled_until: datetime,
        body: str
    ) -> dict[str, Any]:
        """Schedule a maintenance window."""
        return {
            "scheduled_maintenance": {
                "name": title,
                "scheduled_for": scheduled_for.isoformat(),
                "scheduled_until": scheduled_until.isoformat(),
                "body": body,
            }
        }
    
    @staticmethod
    def announce_deprecation(
        version: str,
        sunset_date: datetime,
        affected_components: list[str]
    ) -> dict[str, Any]:
        """Create deprecation announcement."""
        return {
            "incident": {
                "name": f"API {version} Deprecation Notice",
                "status": "monitoring",
                "body": (
                    f"API version {version} is deprecated and will sunset on "
                    f"{sunset_date.strftime('%Y-%m-%d')}. Please migrate to the "
                    f"latest version. See migration guide for details."
                ),
                "components": affected_components,
            }
        }
```

### 13.3 Webhook Notifications

```python
# app/core/notifications.py
"""Webhook and notification utilities."""

import httpx
from datetime import datetime
from typing import Any

from app.core.config import settings


class APINotifier:
    """Sends notifications to API consumers."""
    
    WEBHOOK_URLS: list[str] = []
    
    @classmethod
    async def notify_deprecation(
        cls,
        version: str,
        sunset_date: datetime,
        consumer_webhooks: list[str]
    ):
        """Send deprecation notice to registered webhooks."""
        payload = {
            "event": "api.deprecation_notice",
            "timestamp": datetime.utcnow().isoformat(),
            "data": {
                "version": version,
                "sunset_date": sunset_date.isoformat(),
                "migration_guide": f"https://docs.smartdairy.com/migration/{version}",
                "message": (
                    f"API version {version} is deprecated. "
                    f"Please migrate before {sunset_date.strftime('%Y-%m-%d')}."
                )
            }
        }
        
        await cls._send_to_webhooks(payload, consumer_webhooks)
    
    @classmethod
    async def notify_new_version(
        cls,
        version: str,
        features: list[str],
        consumer_webhooks: list[str]
    ):
        """Notify about new API version."""
        payload = {
            "event": "api.new_version",
            "timestamp": datetime.utcnow().isoformat(),
            "data": {
                "version": version,
                "features": features,
                "documentation": f"https://docs.smartdairy.com/{version}",
                "changelog": "https://docs.smartdairy.com/changelog"
            }
        }
        
        await cls._send_to_webhooks(payload, consumer_webhooks)
    
    @classmethod
    async def _send_to_webhooks(
        cls, 
        payload: dict[str, Any], 
        webhooks: list[str]
    ):
        """Send payload to all registered webhooks."""
        async with httpx.AsyncClient() as client:
            for webhook in webhooks:
                try:
                    await client.post(
                        webhook,
                        json=payload,
                        timeout=30.0
                    )
                except Exception as e:
                    # Log error but don't fail
                    print(f"Failed to notify webhook {webhook}: {e}")
```

---

## 14. Appendices

### Appendix A: Version Policy

#### A.1 Version Numbering

Smart Dairy API follows **Semantic Versioning** principles:

| Component | Format | Example | When Changed |
|-----------|--------|---------|--------------|
| Major | Integer | v1, v2 | Breaking changes |
| Minor | Integer | v1.1, v1.2 | New features (backward compatible) |
| Patch | Integer | v1.1.0, v1.1.1 | Bug fixes |

#### A.2 Support Policy

| Version Status | Support Level | Response Time |
|----------------|---------------|---------------|
| Stable | Full support | 24 hours |
| Deprecated | Critical only | 72 hours |
| Sunset | No support | N/A |

#### A.3 Deprecation Timeline

- **Minimum Deprecation Period**: 6 months
- **Minimum Sunset Notice**: 30 days
- **Maximum Support Period**: 24 months after stable release of successor

### Appendix B: Code Examples

#### B.1 Client SDK Version Handling

```python
# examples/client_sdk.py
"""Example client SDK with version handling."""

import requests
from typing import Any, Optional


class SmartDairyClient:
    """Smart Dairy API Client with version awareness."""
    
    BASE_URL = "https://api.smartdairy.com"
    
    def __init__(self, api_key: str, version: str = "v2"):
        self.api_key = api_key
        self.version = version
        self.session = requests.Session()
        self.session.headers.update({
            "Authorization": f"Bearer {api_key}",
            "Accept": "application/json"
        })
    
    def _make_request(
        self, 
        method: str, 
        endpoint: str, 
        **kwargs
    ) -> dict[str, Any]:
        """Make API request with version prefix."""
        url = f"{self.BASE_URL}/{self.version}{endpoint}"
        
        response = self.session.request(method, url, **kwargs)
        
        # Check for deprecation warnings
        if "Deprecation" in response.headers:
            print(f"WARNING: API version {self.version} is deprecated.")
            print(f"Sunset date: {response.headers.get('Sunset')}")
            print(f"Migration guide: {response.headers.get('Link')}")
        
        response.raise_for_status()
        return response.json()
    
    def get_cattle(self, cattle_id: Optional[str] = None) -> dict[str, Any]:
        """Get cattle information."""
        endpoint = f"/cattle/{cattle_id}" if cattle_id else "/cattle"
        return self._make_request("GET", endpoint)
    
    def create_cattle(self, data: dict[str, Any]) -> dict[str, Any]:
        """Create new cattle record."""
        return self._make_request("POST", "/cattle", json=data)
    
    def get_versions(self) -> dict[str, Any]:
        """Get available API versions."""
        url = f"{self.BASE_URL}/versions"
        response = self.session.get(url)
        return response.json()
```

#### B.2 GraphQL Client Example (v2 only)

```python
# examples/graphql_client.py
"""GraphQL client example for v2."""

import requests


class SmartDairyGraphQL:
    """Smart Dairy GraphQL Client (v2 only)."""
    
    ENDPOINT = "https://api.smartdairy.com/v2/graphql"
    
    def __init__(self, access_token: str):
        self.access_token = access_token
    
    def query(self, query: str, variables: dict = None) -> dict:
        """Execute GraphQL query."""
        response = requests.post(
            self.ENDPOINT,
            headers={
                "Authorization": f"Bearer {self.access_token}",
                "Content-Type": "application/json"
            },
            json={"query": query, "variables": variables or {}}
        )
        return response.json()
    
    def get_cattle_with_production(self, cattle_id: str) -> dict:
        """Get cattle with milk production data."""
        query = """
        query GetCattle($id: ID!) {
            cattle(id: $id) {
                id
                name
                breed
                milkProduction(limit: 10) {
                    quantityLiters
                    milkingDate
                    milkingSession
                }
            }
        }
        """
        return self.query(query, {"id": cattle_id})
```

#### B.3 Migration Script Example

```python
# examples/migration_script.py
"""Example migration script from v1 to v2."""

import asyncio
from typing import Any

from smart_dairy_client import SmartDairyClientV1, SmartDairyClientV2


async def migrate_data():
    """Migrate data from v1 to v2."""
    
    # Initialize both clients
    v1_client = SmartDairyClientV1(api_key="old_key")
    v2_client = SmartDairyClientV2(
        client_id="new_client_id",
        client_secret="new_client_secret"
    )
    
    # Get cattle from v1
    print("Fetching cattle from v1...")
    v1_cattle = v1_client.get_cattle()
    
    # Transform and create in v2
    print("Creating cattle in v2...")
    for cattle in v1_cattle:
        v2_data = transform_v1_to_v2(cattle)
        try:
            result = v2_client.create_cattle(v2_data)
            print(f"Migrated cattle: {result['id']}")
        except Exception as e:
            print(f"Failed to migrate {cattle['id']}: {e}")
    
    print("Migration complete!")


def transform_v1_to_v2(v1_data: dict[str, Any]) -> dict[str, Any]:
    """Transform v1 data format to v2."""
    return {
        "name": v1_data["name"],
        "breed": v1_data["breed"],
        "tag_number": v1_data.get("rfid_tag"),
        # v2 has additional optional fields
        "date_of_birth": v1_data.get("birth_date"),
    }


if __name__ == "__main__":
    asyncio.run(migrate_data())
```

#### B.4 Migration Guide Generator

```python
# scripts/generate_migration_guide.py
"""Generate migration guide from version differences."""

import json
from datetime import datetime
from typing import Any


class MigrationGuideGenerator:
    """Generate migration guide documentation."""
    
    def __init__(self, from_version: str, to_version: str):
        self.from_version = from_version
        self.to_version = to_version
    
    def generate(
        self,
        breaking_changes: list[dict],
        new_features: list[dict],
        deprecated_features: list[str]
    ) -> str:
        """Generate markdown migration guide."""
        
        lines = [
            f"# Migration Guide: {self.from_version} to {self.to_version}",
            "",
            f"**Generated:** {datetime.now().strftime('%Y-%m-%d')}",
            "",
            "## Overview",
            "",
            f"This guide helps you migrate from API {self.from_version} to {self.to_version}.",
            "",
            "## Breaking Changes",
            "",
        ]
        
        # Breaking changes
        for change in breaking_changes:
            lines.extend([
                f"### {change['title']}",
                "",
                f"**Impact:** {change['impact']}",
                "",
                "**Before ({self.from_version}):**",
                "```python",
                change['before'],
                "```",
                "",
                f"**After ({self.to_version}):**",
                "```python",
                change['after'],
                "```",
                "",
                f"**Migration:** {change['migration_steps']}",
                "",
            ])
        
        # New features
        lines.extend([
            "## New Features",
            "",
        ])
        
        for feature in new_features:
            lines.extend([
                f"### {feature['name']}",
                "",
                feature['description'],
                "",
                "```python",
                feature['example'],
                "```",
                "",
            ])
        
        # Deprecated features
        if deprecated_features:
            lines.extend([
                "## Deprecated Features",
                "",
                "The following features are deprecated and will be removed in future versions:",
                "",
            ])
            for feature in deprecated_features:
                lines.append(f"- {feature}")
            lines.append("")
        
        # Testing section
        lines.extend([
            "## Testing Your Migration",
            "",
            "### Pre-Migration Checklist",
            "",
            "- [ ] Review all breaking changes",
            "- [ ] Update authentication mechanism",
            "- [ ] Test in staging environment",
            "- [ ] Update API client libraries",
            "",
            "### Post-Migration Checklist",
            "",
            "- [ ] Verify all endpoints respond correctly",
            "- [ ] Check response format changes",
            "- [ ] Test error handling",
            "- [ ] Verify webhook integrations",
            "- [ ] Performance testing",
            "",
            "## Rollback Plan",
            "",
            "If issues occur after migration:",
            "",
            "1. Revert API client configuration to previous version",
            "2. Update DNS/load balancer if applicable",
            "3. Contact support if data inconsistency detected",
            "",
            "## Support",
            "",
            "- **Documentation:** https://docs.smartdairy.com",
            "- **Support Email:** api-support@smartdairy.com",
            "- **Status Page:** https://status.smartdairy.com",
            "",
        ])
        
        return "\n".join(lines)
    
    def save(self, content: str, filename: str = None):
        """Save migration guide to file."""
        if filename is None:
            filename = f"migration_{self.from_version}_to_{self.to_version}.md"
        
        with open(filename, "w") as f:
            f.write(content)
        
        print(f"Migration guide saved to: {filename}")


# Example usage
def main():
    """Generate v1 to v2 migration guide."""
    
    generator = MigrationGuideGenerator("v1", "v2")
    
    breaking_changes = [
        {
            "title": "Authentication Method Changed",
            "impact": "High - All API calls affected",
            "before": """# v1: API Key in header
headers = {
    "X-API-Key": "your-api-key"
}""",
            "after": """# v2: OAuth 2.0 Bearer token
headers = {
    "Authorization": "Bearer your-access-token"
}""",
            "migration_steps": "Update authentication to use OAuth 2.0 flow. See auth guide."
        },
        {
            "title": "Pagination Changed to Cursor-Based",
            "impact": "Medium - List endpoints affected",
            "before": """# v1: Offset pagination
GET /v1/cattle?page=1&limit=20""",
            "after": """# v2: Cursor pagination
GET /v2/cattle?cursor=eyJpZCI6IDEwfQ==&limit=20""",
            "migration_steps": "Update pagination logic to use cursors from response."
        },
    ]
    
    new_features = [
        {
            "name": "GraphQL Support",
            "description": "Query exactly the data you need with GraphQL.",
            "example": """query {
  cattle(id: "123") {
    name
    milkProduction(limit: 5) {
      quantityLiters
    }
  }
}"""
        },
        {
            "name": "Bulk Operations",
            "description": "Perform bulk create/update operations.",
            "example": """POST /v2/cattle/bulk
{
  "operations": [
    {"action": "create", "data": {...}},
    {"action": "update", "id": "123", "data": {...}}
  ]
}"""
        },
    ]
    
    deprecated_features = [
        "Offset-based pagination (use cursor-based)",
        "XML response format (use JSON)",
    ]
    
    guide = generator.generate(
        breaking_changes=breaking_changes,
        new_features=new_features,
        deprecated_features=deprecated_features
    )
    
    generator.save(guide)


if __name__ == "__main__":
    main()
```

### Appendix C: Deprecation Timeline Template

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    API Version Deprecation Timeline                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Version: v1                                                                 │
│  Current Status: Deprecated                                                  │
│  Deprecation Date: April 1, 2026                                            │
│  Sunset Date: October 1, 2026                                               │
│  Successor: v2                                                               │
│                                                                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                            Timeline                                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  March 1, 2026                                                               │
│  ├─ Deprecation announcement email sent                                      │
│  ├─ Status page updated                                                      │
│  ├─ Migration guide published                                                │
│  └─ Documentation updated with deprecation notices                           │
│                                                                              │
│  April 1, 2026                                                               │
│  ├─ v2.0 stable release                                                      │
│  ├─ v1 enters deprecated status                                              │
│  ├─ Deprecation header added to all v1 responses                             │
│  └─ Warning logged for v1 requests                                           │
│                                                                              │
│  July 1, 2026 (T-3 months)                                                   │
│  ├─ Sunset warning email sent to active v1 users                             │
│  ├─ Updated migration guide with common issues                               │
│  └─ Support team prioritizes v1 migration questions                          │
│                                                                              │
│  September 1, 2026 (T-1 month)                                               │
│  ├─ Final notice email sent                                                  │
│  ├─ Daily reminder emails to remaining v1 users                              │
│  ├─ Dashboard banner for affected accounts                                   │
│  └─ Support hotline for migration assistance                                 │
│                                                                              │
│  October 1, 2026                                                             │
│  ├─ v1 endpoints return 410 Gone                                             │
│  ├─ Redirect to migration guide                                              │
│  ├─ Support ticket auto-redirects to migration resources                     │
│  └─ Final sunset notification                                                │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Appendix D: Version Comparison Matrix

| Feature | v1 | v2 | Notes |
|---------|-----|-----|-------|
| **REST Endpoints** | ✅ | ✅ | All v1 endpoints available in v2 |
| **GraphQL** | ❌ | ✅ | v2 only |
| **WebSocket** | ❌ | ✅ | Real-time updates in v2 |
| **Authentication** | API Key | OAuth 2.0 + API Key | v2 supports both |
| **Pagination** | Offset | Cursor | Better performance in v2 |
| **Rate Limit** | 1000/hr | 5000/hr | Higher limits in v2 |
| **Bulk Operations** | ❌ | ✅ | Batch operations in v2 |
| **Webhook Events** | 5 types | 15 types | More events in v2 |
| **Response Format** | Flat JSON | Nested JSON | Metadata included in v2 |
| **SDK Support** | Python, JS | Python, JS, Go, Java | More SDKs for v2 |
| **Status** | Deprecated | Stable | v1 sunsets Oct 2026 |

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Integration Engineer | _________________ | _______ |
| Owner | Tech Lead | _________________ | _______ |
| Reviewer | CTO | _________________ | _______ |

---

*Document ID: E-017 | Version: 1.0 | Date: January 31, 2026*
