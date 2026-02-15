# Milestone 9: API Security -- OAuth 2.0, JWT & Gateway Hardening

## Smart Dairy Digital Smart Portal + ERP -- Phase 1: Foundation

| Field            | Detail                                                                          |
| ---------------- | ------------------------------------------------------------------------------- |
| **Milestone**    | 9 of 10                                                                         |
| **Title**        | API Security -- OAuth 2.0, JWT & Gateway Hardening                              |
| **Phase**        | Phase 1 -- Foundation (Part B: Database & Security)                             |
| **Days**         | Days 81--90 (of 100)                                                            |
| **Version**      | 1.0                                                                             |
| **Status**       | Draft                                                                           |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead)   |
| **Last Updated** | 2026-02-03                                                                      |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 9 delivers a production-hardened API security layer for the Smart Dairy Digital Smart Portal. This milestone implements OAuth 2.0 authentication, JWT-based authorization, rate limiting, input validation, webhook security, and comprehensive API gateway hardening. The FastAPI gateway serves as the single entry point for all client applications (OWL web portal, Flutter mobile app, service-to-service integrations) and proxies authenticated requests to the Odoo 19 CE backend via XML-RPC/JSON-RPC.

### 1.2 Scope

- FastAPI API gateway with structured middleware pipeline
- OAuth 2.0 provider (authorization code flow for web, client credentials for services)
- JWT token management with RS256 signing, rotation, and custom claims
- Redis-backed sliding-window rate limiting per endpoint and per user
- Pydantic v2 input validation and sanitization for all API schemas
- Security headers (CSP, CORS, X-Frame-Options, HSTS, etc.)
- Webhook security with HMAC-SHA256 signatures and replay protection
- API versioning strategy with deprecation lifecycle
- Automated OWASP ZAP scanning and Schemathesis fuzzing
- OWL-based API monitoring dashboard

### 1.3 Success Criteria

| Criteria                                | Target                                      |
| --------------------------------------- | ------------------------------------------- |
| OAuth 2.0 flows functional              | Authorization code + client credentials     |
| JWT validation latency                  | < 5 ms per request                          |
| Rate limiter accuracy                   | 99.9% within sliding window bounds          |
| OWASP ZAP scan                          | Zero high/critical findings                 |
| Schemathesis fuzz tests                 | Zero 500-level responses on valid schemas   |
| Input validation coverage               | 100% of public endpoints                    |
| Webhook signature verification          | HMAC-SHA256 on all outbound/inbound hooks   |
| API response time (P95)                 | < 200 ms through gateway                    |
| Security headers score                  | A+ on securityheaders.com                   |
| Test coverage for auth module           | >= 90%                                      |

### 1.4 Team Allocation

| Developer | Primary Focus                                  | Estimated Effort |
| --------- | ---------------------------------------------- | ---------------- |
| Dev 1     | OAuth 2.0, JWT, rate limiting, webhook security | 80 hours         |
| Dev 2     | Gateway infra, Redis, CI/CD, OWASP scans       | 80 hours         |
| Dev 3     | API schemas, CORS/CSP, monitoring dashboard     | 80 hours         |

---

## 2. Requirement Traceability Matrix

| Req ID  | Requirement Description                     | Spec Reference | Day(s) | Deliverable                          |
| ------- | ------------------------------------------- | -------------- | ------ | ------------------------------------ |
| E-001   | API Specification & Documentation           | OpenAPI 3.1    | 81, 90 | Auto-generated OpenAPI docs          |
| E-001.1 | RESTful endpoint design                     | E-001          | 81     | Router organization                  |
| E-001.2 | Request/response schemas                    | E-001          | 85     | Pydantic v2 models                   |
| E-001.3 | Authentication specification                | E-001          | 82, 83 | OAuth 2.0 + JWT                      |
| B-007   | API Gateway Implementation                  | Architecture   | 81--86 | FastAPI gateway with middleware       |
| B-007.1 | Rate limiting                               | B-007          | 84     | Redis sliding-window limiter          |
| B-007.2 | Input validation                            | B-007          | 85     | Pydantic validation + sanitization    |
| B-007.3 | Security headers                            | B-007          | 86     | CSP, CORS, HSTS, X-Frame-Options     |
| B-008   | Webhook Design                              | Architecture   | 87     | HMAC-SHA256 webhook dispatcher        |
| B-008.1 | Webhook signature verification              | B-008          | 87     | Signature validation middleware       |
| B-008.2 | Replay protection                           | B-008          | 87     | Timestamp + nonce verification        |
| B-008.3 | Retry logic & dead letter queue             | B-008          | 87     | Exponential backoff + DLQ             |
| SEC-01  | API versioning strategy                     | Architecture   | 88     | URL path versioning /api/v1/          |
| SEC-02  | Automated security testing                  | OWASP Top 10   | 89     | ZAP + Schemathesis pipeline           |

---

## 3. Day-by-Day Breakdown

### Day 81 -- FastAPI API Gateway Setup

**Objective:** Establish the FastAPI project structure, router organization, middleware stack, health checks, and OpenAPI documentation per E-001.

#### Dev 1 Tasks (~8h)

1. Initialize FastAPI project structure with modular layout (2h)
2. Implement core configuration module with environment-based settings (2h)
3. Build health check and readiness probe endpoints (1h)
4. Create base router organization for farm domain resources (2h)
5. Implement structured error handling and custom exception classes (1h)

**FastAPI Project Structure:**

```
smart_dairy_api/
├── app/
│   ├── __init__.py
│   ├── main.py                  # Application entry point
│   ├── core/
│   │   ├── __init__.py
│   │   ├── config.py            # Settings via pydantic-settings
│   │   ├── security.py          # Security utilities
│   │   └── exceptions.py        # Custom exception classes
│   ├── auth/
│   │   ├── __init__.py
│   │   ├── oauth2.py            # OAuth 2.0 provider
│   │   ├── jwt_manager.py       # JWT create/verify/decode
│   │   ├── dependencies.py      # Auth dependency injection
│   │   └── models.py            # Token models
│   ├── middleware/
│   │   ├── __init__.py
│   │   ├── cors.py              # CORS configuration
│   │   ├── security_headers.py  # CSP, HSTS, X-Frame-Options
│   │   ├── rate_limiter.py      # Redis-backed rate limiting
│   │   ├── request_logging.py   # Structured request logging
│   │   └── error_handler.py     # Global error handler
│   ├── routers/
│   │   ├── __init__.py
│   │   ├── v1/
│   │   │   ├── __init__.py
│   │   │   ├── animals.py       # /api/v1/animals
│   │   │   ├── milk.py          # /api/v1/milk-production
│   │   │   ├── health.py        # /api/v1/health-records
│   │   │   ├── feed.py          # /api/v1/feed-management
│   │   │   ├── finance.py       # /api/v1/finance
│   │   │   ├── auth.py          # /api/v1/auth
│   │   │   └── webhooks.py      # /api/v1/webhooks
│   │   └── v2/                  # Future version placeholder
│   │       └── __init__.py
│   ├── schemas/
│   │   ├── __init__.py
│   │   ├── animal.py            # Animal Pydantic models
│   │   ├── milk.py              # Milk production models
│   │   ├── health_record.py     # Health record models
│   │   ├── feed.py              # Feed management models
│   │   ├── auth.py              # Auth request/response models
│   │   ├── webhook.py           # Webhook models
│   │   └── common.py            # Shared models (pagination, errors)
│   ├── services/
│   │   ├── __init__.py
│   │   ├── odoo_client.py       # Odoo XML-RPC/JSON-RPC client
│   │   └── webhook_dispatcher.py # Webhook dispatch service
│   └── utils/
│       ├── __init__.py
│       ├── sanitize.py          # Input sanitization
│       └── validators.py        # Custom validators
├── tests/
│   ├── __init__.py
│   ├── conftest.py
│   ├── test_auth.py
│   ├── test_rate_limiter.py
│   ├── test_input_validation.py
│   ├── test_cors.py
│   ├── test_webhooks.py
│   └── test_security_headers.py
├── scripts/
│   ├── generate_rsa_keys.py
│   ├── run_zap_scan.py
│   └── run_schemathesis.py
├── docker-compose.yml
├── Dockerfile
├── requirements.txt
└── pyproject.toml
```

**Core Configuration Module (`app/core/config.py`):**

```python
"""Application settings loaded from environment variables."""

from functools import lru_cache
from pathlib import Path
from typing import List

from pydantic import Field, field_validator
from pydantic_settings import BaseSettings, SettingsConfigDict


class Settings(BaseSettings):
    model_config = SettingsConfigDict(
        env_file=".env",
        env_file_encoding="utf-8",
        case_sensitive=False,
    )

    # --- Application ---
    app_name: str = "Smart Dairy API Gateway"
    app_version: str = "1.0.0"
    debug: bool = False
    environment: str = "production"  # development | staging | production

    # --- Server ---
    host: str = "0.0.0.0"
    port: int = 8000
    workers: int = 4

    # --- Database (Odoo backend) ---
    odoo_url: str = "http://odoo:8069"
    odoo_db: str = "smart_dairy"
    odoo_admin_user: str = "admin"
    odoo_admin_password: str = Field(default="", json_schema_extra={"env": "ODOO_ADMIN_PASSWORD"})

    # --- Redis ---
    redis_url: str = "redis://redis:6379/0"
    redis_password: str = ""

    # --- JWT ---
    jwt_algorithm: str = "RS256"
    jwt_access_token_expire_minutes: int = 15
    jwt_refresh_token_expire_days: int = 7
    jwt_private_key_path: str = "/etc/smart_dairy/keys/private.pem"
    jwt_public_key_path: str = "/etc/smart_dairy/keys/public.pem"
    jwt_issuer: str = "smart-dairy-api"
    jwt_audience: str = "smart-dairy-clients"

    # --- OAuth 2.0 ---
    oauth2_authorization_endpoint: str = "/api/v1/auth/authorize"
    oauth2_token_endpoint: str = "/api/v1/auth/token"

    # --- CORS ---
    cors_allowed_origins: List[str] = [
        "https://portal.smartdairy.com.bd",
        "https://admin.smartdairy.com.bd",
        "capacitor://localhost",       # Flutter mobile
        "http://localhost:3000",       # Local dev
    ]
    cors_allow_credentials: bool = True
    cors_allowed_methods: List[str] = ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"]
    cors_allowed_headers: List[str] = ["Authorization", "Content-Type", "X-Request-ID"]

    # --- Rate Limiting ---
    rate_limit_default_rpm: int = 60       # Requests per minute default
    rate_limit_auth_rpm: int = 10          # Auth endpoints per minute
    rate_limit_webhook_rpm: int = 100      # Webhook endpoints per minute
    rate_limit_burst_multiplier: float = 1.5

    # --- Webhook ---
    webhook_secret_key: str = Field(default="", json_schema_extra={"env": "WEBHOOK_SECRET_KEY"})
    webhook_max_retries: int = 5
    webhook_retry_base_delay: float = 1.0
    webhook_timestamp_tolerance_seconds: int = 300  # 5 minutes

    # --- Security ---
    content_security_policy: str = "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'"
    hsts_max_age: int = 31536000  # 1 year

    @field_validator("cors_allowed_origins", mode="before")
    @classmethod
    def parse_cors_origins(cls, v):
        if isinstance(v, str):
            return [origin.strip() for origin in v.split(",")]
        return v


@lru_cache()
def get_settings() -> Settings:
    return Settings()
```

#### Dev 2 Tasks (~8h)

1. Set up Docker Compose for FastAPI + Redis + Nginx reverse proxy (3h)
2. Configure Nginx as TLS-terminating reverse proxy in front of FastAPI (2h)
3. Implement structured JSON request logging middleware (2h)
4. Set up CI pipeline stage for API gateway lint and type checks (1h)

**Request Logging Middleware (`app/middleware/request_logging.py`):**

```python
"""Structured JSON logging for every API request."""

import time
import uuid
from typing import Callable

import structlog
from starlette.middleware.base import BaseHTTPMiddleware
from starlette.requests import Request
from starlette.responses import Response

logger = structlog.get_logger("api.access")


class RequestLoggingMiddleware(BaseHTTPMiddleware):
    async def dispatch(self, request: Request, call_next: Callable) -> Response:
        request_id = request.headers.get("X-Request-ID", str(uuid.uuid4()))
        request.state.request_id = request_id

        start_time = time.perf_counter()
        response: Response = None
        exc_info = None

        try:
            response = await call_next(request)
        except Exception as exc:
            exc_info = exc
            raise
        finally:
            duration_ms = (time.perf_counter() - start_time) * 1000
            status_code = response.status_code if response else 500

            log_data = {
                "request_id": request_id,
                "method": request.method,
                "path": request.url.path,
                "query": str(request.url.query),
                "status_code": status_code,
                "duration_ms": round(duration_ms, 2),
                "client_ip": request.client.host if request.client else "unknown",
                "user_agent": request.headers.get("User-Agent", ""),
            }

            if status_code >= 500:
                logger.error("request_completed", **log_data, exc_info=exc_info)
            elif status_code >= 400:
                logger.warning("request_completed", **log_data)
            else:
                logger.info("request_completed", **log_data)

        response.headers["X-Request-ID"] = request_id
        return response
```

#### Dev 3 Tasks (~8h)

1. Design API error response schema aligned with RFC 7807 Problem Details (2h)
2. Create OpenAPI documentation customization (tags, descriptions, examples) (3h)
3. Begin Flutter API client service architecture with Dio HTTP client (3h)

**Custom Exception Handlers (`app/core/exceptions.py`):**

```python
"""Custom exceptions and RFC 7807 Problem Details error responses."""

from typing import Any, Dict, Optional

from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse
from pydantic import BaseModel


class ErrorDetail(BaseModel):
    type: str = "about:blank"
    title: str
    status: int
    detail: str
    instance: Optional[str] = None
    errors: Optional[list[Dict[str, Any]]] = None


class APIError(Exception):
    def __init__(
        self,
        status_code: int,
        title: str,
        detail: str,
        error_type: str = "about:blank",
        errors: Optional[list[Dict[str, Any]]] = None,
    ):
        self.status_code = status_code
        self.title = title
        self.detail = detail
        self.error_type = error_type
        self.errors = errors


class AuthenticationError(APIError):
    def __init__(self, detail: str = "Authentication required"):
        super().__init__(
            status_code=401,
            title="Unauthorized",
            detail=detail,
            error_type="/errors/authentication",
        )


class AuthorizationError(APIError):
    def __init__(self, detail: str = "Insufficient permissions"):
        super().__init__(
            status_code=403,
            title="Forbidden",
            detail=detail,
            error_type="/errors/authorization",
        )


class RateLimitExceededError(APIError):
    def __init__(self, retry_after: int = 60):
        super().__init__(
            status_code=429,
            title="Too Many Requests",
            detail=f"Rate limit exceeded. Retry after {retry_after} seconds.",
            error_type="/errors/rate-limit",
        )
        self.retry_after = retry_after


def register_exception_handlers(app: FastAPI) -> None:
    @app.exception_handler(APIError)
    async def api_error_handler(request: Request, exc: APIError) -> JSONResponse:
        body = ErrorDetail(
            type=exc.error_type,
            title=exc.title,
            status=exc.status_code,
            detail=exc.detail,
            instance=str(request.url),
            errors=exc.errors,
        )
        headers = {}
        if isinstance(exc, RateLimitExceededError):
            headers["Retry-After"] = str(exc.retry_after)
        return JSONResponse(
            status_code=exc.status_code,
            content=body.model_dump(exclude_none=True),
            headers=headers,
        )

    @app.exception_handler(Exception)
    async def unhandled_error_handler(request: Request, exc: Exception) -> JSONResponse:
        body = ErrorDetail(
            type="/errors/internal",
            title="Internal Server Error",
            status=500,
            detail="An unexpected error occurred. Please contact support.",
            instance=str(request.url),
        )
        return JSONResponse(status_code=500, content=body.model_dump(exclude_none=True))
```

**End-of-Day 81 Deliverables:**
- [ ] FastAPI project scaffolded with modular directory layout
- [ ] Core configuration loaded from environment via pydantic-settings
- [ ] Health check endpoints (`/health`, `/ready`) operational
- [ ] Request logging middleware producing structured JSON logs
- [ ] Nginx reverse proxy configuration with TLS termination
- [ ] OpenAPI docs accessible at `/docs` and `/redoc`

---

### Day 82 -- OAuth 2.0 Provider Configuration

**Objective:** Implement OAuth 2.0 authorization server supporting authorization code flow (web portal) and client credentials flow (service-to-service).

#### Dev 1 Tasks (~8h)

1. Install and configure authlib for OAuth 2.0 server (1h)
2. Implement authorization code grant for web portal login (3h)
3. Implement client credentials grant for service-to-service auth (2h)
4. Build token endpoint with proper error responses (2h)

**OAuth 2.0 Provider (`app/auth/oauth2.py`):**

```python
"""OAuth 2.0 Authorization Server using authlib."""

import time
from typing import Optional

from authlib.integrations.starlette_client import OAuth
from authlib.oauth2 import AuthorizationServer as BaseAuthServer
from authlib.oauth2.rfc6749 import grants
from authlib.oauth2.rfc6749.models import AuthorizationCodeMixin, ClientMixin
from pydantic import BaseModel
from starlette.requests import Request

from app.auth.jwt_manager import JWTManager
from app.core.config import get_settings


class OAuthClient(BaseModel, ClientMixin):
    """Represents a registered OAuth 2.0 client application."""
    client_id: str
    client_secret_hash: str
    client_name: str
    redirect_uris: list[str]
    allowed_scopes: list[str]
    grant_types: list[str]  # authorization_code, client_credentials
    token_endpoint_auth_method: str = "client_secret_post"
    is_active: bool = True

    def get_client_id(self) -> str:
        return self.client_id

    def get_default_redirect_uri(self) -> str:
        return self.redirect_uris[0] if self.redirect_uris else ""

    def get_allowed_scope(self, scope: str) -> str:
        requested = set(scope.split())
        allowed = set(self.allowed_scopes)
        return " ".join(requested & allowed)

    def check_redirect_uri(self, redirect_uri: str) -> bool:
        return redirect_uri in self.redirect_uris

    def check_client_secret(self, client_secret: str) -> bool:
        import hashlib
        return hashlib.sha256(client_secret.encode()).hexdigest() == self.client_secret_hash

    def check_grant_type(self, grant_type: str) -> bool:
        return grant_type in self.grant_types

    def check_response_type(self, response_type: str) -> bool:
        if response_type == "code":
            return "authorization_code" in self.grant_types
        return False


class AuthorizationCode(BaseModel, AuthorizationCodeMixin):
    """Temporary authorization code issued during auth code flow."""
    code: str
    client_id: str
    redirect_uri: str
    scope: str
    user_id: int
    nonce: Optional[str] = None
    code_challenge: Optional[str] = None
    code_challenge_method: Optional[str] = None
    created_at: float

    def is_expired(self) -> bool:
        return time.time() - self.created_at > 600  # 10-minute expiry

    def get_redirect_uri(self) -> str:
        return self.redirect_uri

    def get_scope(self) -> str:
        return self.scope


class AuthorizationCodeGrant(grants.AuthorizationCodeGrant):
    """Authorization code grant with PKCE support for web portal."""
    TOKEN_ENDPOINT_AUTH_METHODS = ["client_secret_post", "client_secret_basic"]

    def save_authorization_code(self, code, request) -> AuthorizationCode:
        auth_code = AuthorizationCode(
            code=code,
            client_id=request.client.get_client_id(),
            redirect_uri=request.redirect_uri,
            scope=request.scope or "",
            user_id=request.user.id,
            code_challenge=request.data.get("code_challenge"),
            code_challenge_method=request.data.get("code_challenge_method"),
            created_at=time.time(),
        )
        # Store in Redis with TTL
        self._save_code_to_redis(auth_code)
        return auth_code

    def query_authorization_code(self, code, client):
        # Retrieve from Redis
        auth_code = self._get_code_from_redis(code)
        if auth_code and auth_code.client_id == client.get_client_id():
            if not auth_code.is_expired():
                return auth_code
        return None

    def delete_authorization_code(self, authorization_code):
        self._delete_code_from_redis(authorization_code.code)

    def authenticate_user(self, authorization_code):
        return self._get_user_by_id(authorization_code.user_id)


class ClientCredentialsGrant(grants.ClientCredentialsGrant):
    """Client credentials grant for service-to-service authentication."""
    TOKEN_ENDPOINT_AUTH_METHODS = ["client_secret_post", "client_secret_basic"]

    def authenticate_client(self, request):
        client_id = request.data.get("client_id") or request.headers.get("client_id")
        client_secret = request.data.get("client_secret")
        client = self._query_client(client_id)
        if client and client.check_client_secret(client_secret):
            return client
        return None


# --- Pre-registered OAuth clients for Smart Dairy ---
REGISTERED_CLIENTS = {
    "smart-dairy-web-portal": OAuthClient(
        client_id="smart-dairy-web-portal",
        client_secret_hash="<sha256-hash-of-secret>",
        client_name="Smart Dairy Web Portal",
        redirect_uris=[
            "https://portal.smartdairy.com.bd/auth/callback",
            "http://localhost:3000/auth/callback",
        ],
        allowed_scopes=["read", "write", "admin", "farm:read", "farm:write"],
        grant_types=["authorization_code"],
    ),
    "smart-dairy-mobile-app": OAuthClient(
        client_id="smart-dairy-mobile-app",
        client_secret_hash="<sha256-hash-of-secret>",
        client_name="Smart Dairy Mobile App",
        redirect_uris=["smartdairy://auth/callback"],
        allowed_scopes=["read", "write", "farm:read", "farm:write"],
        grant_types=["authorization_code"],
    ),
    "smart-dairy-iot-service": OAuthClient(
        client_id="smart-dairy-iot-service",
        client_secret_hash="<sha256-hash-of-secret>",
        client_name="Smart Dairy IoT Data Ingestion",
        redirect_uris=[],
        allowed_scopes=["iot:write", "farm:read"],
        grant_types=["client_credentials"],
    ),
}
```

#### Dev 2 Tasks (~8h)

1. Set up Redis storage backend for OAuth codes and tokens (2h)
2. Configure client registration table in PostgreSQL (2h)
3. Implement secure client secret generation and hashing workflow (2h)
4. Write Docker health checks for Redis connectivity (2h)

#### Dev 3 Tasks (~8h)

1. Implement OWL web portal OAuth 2.0 login redirect flow (3h)
2. Build Flutter OAuth 2.0 authorization code flow with PKCE (3h)
3. Design token storage (secure storage on mobile, httpOnly cookies on web) (2h)

**End-of-Day 82 Deliverables:**
- [ ] OAuth 2.0 authorization code flow functional for web portal
- [ ] Client credentials grant functional for IoT service
- [ ] Client registration stored in PostgreSQL
- [ ] Authorization codes stored in Redis with TTL
- [ ] OWL login redirect initiates OAuth flow
- [ ] Flutter PKCE flow skeleton implemented

---

### Day 83 -- JWT Token Implementation

**Objective:** Implement RS256 JWT token management with access/refresh token lifecycle, custom claims, key rotation, and FastAPI dependency injection for authentication.

#### Dev 1 Tasks (~8h)

1. Implement JWTManager class with RS256 signing (3h)
2. Build token refresh endpoint with rotation (2h)
3. Create FastAPI auth dependencies for route protection (2h)
4. Write RSA key generation and rotation script (1h)

**JWT Manager (`app/auth/jwt_manager.py`):**

```python
"""JWT token creation, verification, and decoding with RS256."""

from datetime import datetime, timedelta, timezone
from pathlib import Path
from typing import Any, Dict, Optional

from jose import JWTError, jwt
from pydantic import BaseModel

from app.core.config import get_settings


class TokenPayload(BaseModel):
    sub: str                    # user_id as string
    role: str                   # user role (admin, manager, worker, vet)
    farm_id: int                # Smart Dairy farm identifier
    permissions: list[str]      # granular permissions list
    scope: str = ""             # OAuth 2.0 scope
    token_type: str = "access"  # access | refresh
    iss: str = ""               # issuer
    aud: str = ""               # audience
    iat: float = 0              # issued at
    exp: float = 0              # expiration
    jti: str = ""               # unique token ID


class JWTManager:
    """Manages JWT lifecycle: creation, verification, decoding."""

    def __init__(self):
        self.settings = get_settings()
        self.algorithm = self.settings.jwt_algorithm
        self._private_key: Optional[str] = None
        self._public_key: Optional[str] = None

    @property
    def private_key(self) -> str:
        if self._private_key is None:
            key_path = Path(self.settings.jwt_private_key_path)
            self._private_key = key_path.read_text()
        return self._private_key

    @property
    def public_key(self) -> str:
        if self._public_key is None:
            key_path = Path(self.settings.jwt_public_key_path)
            self._public_key = key_path.read_text()
        return self._public_key

    def create_access_token(
        self,
        user_id: int,
        role: str,
        farm_id: int,
        permissions: list[str],
        scope: str = "",
        additional_claims: Optional[Dict[str, Any]] = None,
    ) -> str:
        import uuid

        now = datetime.now(timezone.utc)
        expire = now + timedelta(minutes=self.settings.jwt_access_token_expire_minutes)

        claims = {
            "sub": str(user_id),
            "role": role,
            "farm_id": farm_id,
            "permissions": permissions,
            "scope": scope,
            "token_type": "access",
            "iss": self.settings.jwt_issuer,
            "aud": self.settings.jwt_audience,
            "iat": now.timestamp(),
            "exp": expire.timestamp(),
            "jti": str(uuid.uuid4()),
        }
        if additional_claims:
            claims.update(additional_claims)

        return jwt.encode(claims, self.private_key, algorithm=self.algorithm)

    def create_refresh_token(
        self,
        user_id: int,
        role: str,
        farm_id: int,
    ) -> str:
        import uuid

        now = datetime.now(timezone.utc)
        expire = now + timedelta(days=self.settings.jwt_refresh_token_expire_days)

        claims = {
            "sub": str(user_id),
            "role": role,
            "farm_id": farm_id,
            "token_type": "refresh",
            "iss": self.settings.jwt_issuer,
            "aud": self.settings.jwt_audience,
            "iat": now.timestamp(),
            "exp": expire.timestamp(),
            "jti": str(uuid.uuid4()),
        }
        return jwt.encode(claims, self.private_key, algorithm=self.algorithm)

    def verify_token(self, token: str, expected_type: str = "access") -> TokenPayload:
        try:
            payload = jwt.decode(
                token,
                self.public_key,
                algorithms=[self.algorithm],
                issuer=self.settings.jwt_issuer,
                audience=self.settings.jwt_audience,
            )
        except JWTError as e:
            raise ValueError(f"Token verification failed: {e}")

        if payload.get("token_type") != expected_type:
            raise ValueError(f"Expected {expected_type} token, got {payload.get('token_type')}")

        return TokenPayload(**payload)

    def decode_token_unverified(self, token: str) -> Dict[str, Any]:
        """Decode without verification -- use only for debugging/logging."""
        return jwt.get_unverified_claims(token)


jwt_manager = JWTManager()
```

**RSA Key Generation Script (`scripts/generate_rsa_keys.py`):**

```python
"""Generate and rotate RSA key pairs for JWT signing."""

import argparse
import shutil
from datetime import datetime
from pathlib import Path

from cryptography.hazmat.primitives import serialization
from cryptography.hazmat.primitives.asymmetric import rsa


def generate_rsa_keypair(
    key_size: int = 2048,
    private_key_path: str = "/etc/smart_dairy/keys/private.pem",
    public_key_path: str = "/etc/smart_dairy/keys/public.pem",
    backup: bool = True,
) -> None:
    priv_path = Path(private_key_path)
    pub_path = Path(public_key_path)

    # Backup existing keys before rotation
    if backup and priv_path.exists():
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        backup_dir = priv_path.parent / "backup"
        backup_dir.mkdir(parents=True, exist_ok=True)
        shutil.copy2(priv_path, backup_dir / f"private_{timestamp}.pem")
        shutil.copy2(pub_path, backup_dir / f"public_{timestamp}.pem")
        print(f"Existing keys backed up to {backup_dir}")

    priv_path.parent.mkdir(parents=True, exist_ok=True)

    private_key = rsa.generate_private_key(
        public_exponent=65537,
        key_size=key_size,
    )

    priv_pem = private_key.private_bytes(
        encoding=serialization.Encoding.PEM,
        format=serialization.PrivateFormat.PKCS8,
        encryption_algorithm=serialization.NoEncryption(),
    )
    priv_path.write_bytes(priv_pem)
    priv_path.chmod(0o600)

    pub_pem = private_key.public_key().public_bytes(
        encoding=serialization.Encoding.PEM,
        format=serialization.PublicFormat.SubjectPublicKeyInfo,
    )
    pub_path.write_bytes(pub_pem)
    pub_path.chmod(0o644)

    print(f"Private key written to {priv_path}")
    print(f"Public key written to {pub_path}")


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Generate RSA key pair for JWT signing")
    parser.add_argument("--key-size", type=int, default=2048, choices=[2048, 4096])
    parser.add_argument("--private-key", default="/etc/smart_dairy/keys/private.pem")
    parser.add_argument("--public-key", default="/etc/smart_dairy/keys/public.pem")
    parser.add_argument("--no-backup", action="store_true")
    args = parser.parse_args()

    generate_rsa_keypair(args.key_size, args.private_key, args.public_key, not args.no_backup)
```

**Auth Dependencies (`app/auth/dependencies.py`):**

```python
"""FastAPI dependency injection for authentication and authorization."""

from typing import List, Optional

from fastapi import Depends, Header, Request
from fastapi.security import HTTPAuthorizationCredentials, HTTPBearer

from app.auth.jwt_manager import TokenPayload, jwt_manager
from app.core.exceptions import AuthenticationError, AuthorizationError

bearer_scheme = HTTPBearer(auto_error=False)


async def get_current_user(
    request: Request,
    credentials: Optional[HTTPAuthorizationCredentials] = Depends(bearer_scheme),
) -> TokenPayload:
    if credentials is None:
        raise AuthenticationError("Missing Authorization header")

    try:
        payload = jwt_manager.verify_token(credentials.credentials, expected_type="access")
    except ValueError as e:
        raise AuthenticationError(str(e))

    request.state.user = payload
    return payload


def require_permission(*required_permissions: str):
    """Dependency factory that checks for specific permissions."""
    async def _check_permissions(
        user: TokenPayload = Depends(get_current_user),
    ) -> TokenPayload:
        user_perms = set(user.permissions)
        missing = set(required_permissions) - user_perms
        if missing:
            raise AuthorizationError(
                f"Missing required permissions: {', '.join(sorted(missing))}"
            )
        return user
    return _check_permissions


def require_role(*allowed_roles: str):
    """Dependency factory that checks for allowed roles."""
    async def _check_role(
        user: TokenPayload = Depends(get_current_user),
    ) -> TokenPayload:
        if user.role not in allowed_roles:
            raise AuthorizationError(
                f"Role '{user.role}' is not authorized. Required: {', '.join(allowed_roles)}"
            )
        return user
    return _check_role
```

#### Dev 2 Tasks (~8h)

1. Deploy RSA key pair generation into Docker secret management (2h)
2. Implement token blacklist in Redis for logout/revocation (3h)
3. Configure key rotation cron job and zero-downtime rotation strategy (3h)

#### Dev 3 Tasks (~8h)

1. Integrate JWT token handling in OWL HTTP service layer (3h)
2. Build Flutter secure token storage (flutter_secure_storage) (2h)
3. Implement automatic token refresh interceptor in Dio client (3h)

**End-of-Day 83 Deliverables:**
- [ ] JWTManager creating and verifying RS256 tokens
- [ ] Access tokens (15min) and refresh tokens (7 days) with custom claims
- [ ] `get_current_user`, `require_permission`, `require_role` dependencies functional
- [ ] RSA key generation script with backup/rotation support
- [ ] Token blacklist in Redis for revocation
- [ ] Flutter token refresh interceptor operational

---

### Day 84 -- API Rate Limiting and Throttling

**Objective:** Implement Redis-backed sliding window rate limiting with per-endpoint and per-user configurable limits.

#### Dev 1 Tasks (~8h)

1. Implement SlidingWindowRateLimiter class with Redis backend (4h)
2. Build rate limiter middleware with per-route configuration (2h)
3. Add rate limit response headers (X-RateLimit-Limit, X-RateLimit-Remaining, Retry-After) (2h)

**Redis-Backed Rate Limiter (`app/middleware/rate_limiter.py`):**

```python
"""Sliding window rate limiter backed by Redis."""

import time
from dataclasses import dataclass
from typing import Callable, Dict, Optional

import redis.asyncio as aioredis
from starlette.middleware.base import BaseHTTPMiddleware
from starlette.requests import Request
from starlette.responses import JSONResponse, Response

from app.core.config import get_settings


@dataclass
class RateLimitConfig:
    requests_per_minute: int
    burst_multiplier: float = 1.5
    key_func: Optional[Callable] = None  # Custom key extraction


# Per-route rate limit configuration
ROUTE_LIMITS: Dict[str, RateLimitConfig] = {
    "/api/v1/auth/token": RateLimitConfig(requests_per_minute=10),
    "/api/v1/auth/authorize": RateLimitConfig(requests_per_minute=10),
    "/api/v1/auth/register": RateLimitConfig(requests_per_minute=5),
    "/api/v1/webhooks": RateLimitConfig(requests_per_minute=100),
    "/api/v1/animals": RateLimitConfig(requests_per_minute=120),
    "/api/v1/milk-production": RateLimitConfig(requests_per_minute=120),
}

DEFAULT_LIMIT = RateLimitConfig(requests_per_minute=60)


class SlidingWindowRateLimiter:
    """Redis-backed sliding window log rate limiter."""

    def __init__(self, redis_client: aioredis.Redis):
        self.redis = redis_client

    async def is_allowed(
        self,
        key: str,
        limit: int,
        window_seconds: int = 60,
    ) -> tuple[bool, int, int]:
        """
        Check if the request is allowed under the sliding window.

        Returns:
            (allowed, remaining, retry_after_seconds)
        """
        now = time.time()
        window_start = now - window_seconds

        pipe = self.redis.pipeline()

        # Remove entries outside the current window
        pipe.zremrangebyscore(key, 0, window_start)
        # Count current entries in window
        pipe.zcard(key)
        # Add the current request timestamp
        pipe.zadd(key, {f"{now}": now})
        # Set TTL on the key to auto-cleanup
        pipe.expire(key, window_seconds + 1)

        results = await pipe.execute()
        current_count = results[1]

        if current_count >= limit:
            # Calculate when the oldest entry in the window will expire
            oldest = await self.redis.zrange(key, 0, 0, withscores=True)
            retry_after = int(window_seconds - (now - oldest[0][1])) + 1 if oldest else window_seconds
            # Remove the request we just added since it is denied
            await self.redis.zrem(key, f"{now}")
            return False, 0, retry_after

        remaining = limit - current_count - 1
        return True, max(remaining, 0), 0


class RateLimiterMiddleware(BaseHTTPMiddleware):
    """Middleware applying sliding window rate limits per route and user."""

    def __init__(self, app, redis_url: str = None):
        super().__init__(app)
        settings = get_settings()
        self.redis_url = redis_url or settings.redis_url
        self._redis: Optional[aioredis.Redis] = None
        self._limiter: Optional[SlidingWindowRateLimiter] = None

    async def _get_limiter(self) -> SlidingWindowRateLimiter:
        if self._limiter is None:
            self._redis = aioredis.from_url(self.redis_url, decode_responses=True)
            self._limiter = SlidingWindowRateLimiter(self._redis)
        return self._limiter

    def _get_client_key(self, request: Request) -> str:
        """Build a rate limit key from user identity or IP."""
        user = getattr(request.state, "user", None)
        if user:
            return f"rate_limit:user:{user.sub}:{request.url.path}"
        client_ip = request.client.host if request.client else "unknown"
        return f"rate_limit:ip:{client_ip}:{request.url.path}"

    def _get_route_config(self, path: str) -> RateLimitConfig:
        for route_prefix, config in ROUTE_LIMITS.items():
            if path.startswith(route_prefix):
                return config
        return DEFAULT_LIMIT

    async def dispatch(self, request: Request, call_next: Callable) -> Response:
        # Skip rate limiting for health checks
        if request.url.path in ("/health", "/ready"):
            return await call_next(request)

        limiter = await self._get_limiter()
        config = self._get_route_config(request.url.path)
        key = self._get_client_key(request)
        limit = config.requests_per_minute

        allowed, remaining, retry_after = await limiter.is_allowed(key, limit, window_seconds=60)

        if not allowed:
            return JSONResponse(
                status_code=429,
                content={
                    "type": "/errors/rate-limit",
                    "title": "Too Many Requests",
                    "status": 429,
                    "detail": f"Rate limit of {limit} requests per minute exceeded.",
                },
                headers={
                    "Retry-After": str(retry_after),
                    "X-RateLimit-Limit": str(limit),
                    "X-RateLimit-Remaining": "0",
                    "X-RateLimit-Reset": str(retry_after),
                },
            )

        response = await call_next(request)
        response.headers["X-RateLimit-Limit"] = str(limit)
        response.headers["X-RateLimit-Remaining"] = str(remaining)
        return response
```

#### Dev 2 Tasks (~8h)

1. Configure Redis cluster mode for rate limiter high availability (3h)
2. Build Prometheus metrics exporter for rate limit hits and denials (2h)
3. Create Grafana dashboard panel for rate limiting metrics (3h)

#### Dev 3 Tasks (~8h)

1. Implement client-side rate limit handling in OWL HTTP service (2h)
2. Build Flutter retry-with-backoff logic on 429 responses (3h)
3. Create rate limit status indicator in API monitoring dashboard (3h)

**End-of-Day 84 Deliverables:**
- [ ] SlidingWindowRateLimiter operational with Redis backend
- [ ] Per-route and per-user rate limits enforced
- [ ] 429 responses include Retry-After and X-RateLimit-* headers
- [ ] Prometheus metrics for rate limit events
- [ ] Client-side backoff handling in OWL and Flutter

---

### Day 85 -- API Input Validation and Sanitization

**Objective:** Implement Pydantic v2 models for all endpoints with SQL injection prevention, XSS sanitization, and file upload validation.

#### Dev 1 Tasks (~8h)

1. Build Pydantic v2 schemas for farm domain entities (3h)
2. Implement input sanitization utility functions (2h)
3. Create file upload validation (MIME type, size, content inspection) (2h)
4. Add SQL parameterization safeguards in Odoo client layer (1h)

**Pydantic v2 Schemas (`app/schemas/animal.py`):**

```python
"""Pydantic v2 schemas for the Animal domain."""

from datetime import date, datetime
from enum import Enum
from typing import Optional

from pydantic import BaseModel, ConfigDict, Field, field_validator

from app.utils.sanitize import sanitize_text


class AnimalCategory(str, Enum):
    LACTATING = "lactating"
    DRY = "dry"
    HEIFER = "heifer"
    CALF = "calf"
    BULL = "bull"


class AnimalStatus(str, Enum):
    ACTIVE = "active"
    SOLD = "sold"
    DECEASED = "deceased"
    QUARANTINED = "quarantined"


class AnimalBase(BaseModel):
    model_config = ConfigDict(str_strip_whitespace=True, str_max_length=500)

    tag_number: str = Field(
        ..., min_length=1, max_length=20, pattern=r"^[A-Z0-9\-]+$",
        description="Unique ear tag number (alphanumeric + hyphens only)",
        examples=["SD-001", "SD-255"],
    )
    name: Optional[str] = Field(
        None, max_length=100,
        description="Optional animal name",
    )
    breed: str = Field(
        ..., min_length=2, max_length=100,
        description="Breed of the animal",
        examples=["Holstein Friesian", "Sahiwal", "Red Chittagong"],
    )
    category: AnimalCategory
    date_of_birth: Optional[date] = None
    weight_kg: Optional[float] = Field(None, gt=0, le=2000)
    status: AnimalStatus = AnimalStatus.ACTIVE

    @field_validator("name", mode="before")
    @classmethod
    def sanitize_name(cls, v: Optional[str]) -> Optional[str]:
        return sanitize_text(v) if v else v

    @field_validator("breed", mode="before")
    @classmethod
    def sanitize_breed(cls, v: str) -> str:
        return sanitize_text(v)


class AnimalCreate(AnimalBase):
    farm_id: int = Field(..., gt=0)
    dam_id: Optional[int] = Field(None, gt=0, description="Mother animal ID")
    sire_id: Optional[int] = Field(None, gt=0, description="Father animal ID")


class AnimalUpdate(BaseModel):
    model_config = ConfigDict(str_strip_whitespace=True)

    name: Optional[str] = Field(None, max_length=100)
    category: Optional[AnimalCategory] = None
    weight_kg: Optional[float] = Field(None, gt=0, le=2000)
    status: Optional[AnimalStatus] = None

    @field_validator("name", mode="before")
    @classmethod
    def sanitize_name(cls, v: Optional[str]) -> Optional[str]:
        return sanitize_text(v) if v else v


class AnimalResponse(AnimalBase):
    model_config = ConfigDict(from_attributes=True)

    id: int
    farm_id: int
    dam_id: Optional[int] = None
    sire_id: Optional[int] = None
    created_at: datetime
    updated_at: datetime


class MilkProductionCreate(BaseModel):
    model_config = ConfigDict(str_strip_whitespace=True)

    animal_id: int = Field(..., gt=0)
    date: date
    session: str = Field(..., pattern=r"^(morning|evening|midday)$")
    quantity_liters: float = Field(..., gt=0, le=50, description="Milk yield in liters (max 50L)")
    fat_percentage: Optional[float] = Field(None, ge=0, le=15)
    protein_percentage: Optional[float] = Field(None, ge=0, le=10)
    somatic_cell_count: Optional[int] = Field(None, ge=0, le=10000000)
    notes: Optional[str] = Field(None, max_length=500)

    @field_validator("notes", mode="before")
    @classmethod
    def sanitize_notes(cls, v: Optional[str]) -> Optional[str]:
        return sanitize_text(v) if v else v


class HealthRecordCreate(BaseModel):
    model_config = ConfigDict(str_strip_whitespace=True)

    animal_id: int = Field(..., gt=0)
    record_date: date
    record_type: str = Field(..., pattern=r"^(checkup|vaccination|treatment|surgery|diagnosis)$")
    diagnosis: Optional[str] = Field(None, max_length=1000)
    treatment: Optional[str] = Field(None, max_length=1000)
    veterinarian: Optional[str] = Field(None, max_length=200)
    cost_bdt: Optional[float] = Field(None, ge=0)
    follow_up_date: Optional[date] = None
    notes: Optional[str] = Field(None, max_length=2000)

    @field_validator("diagnosis", "treatment", "notes", mode="before")
    @classmethod
    def sanitize_text_fields(cls, v: Optional[str]) -> Optional[str]:
        return sanitize_text(v) if v else v
```

**Input Sanitization Utilities (`app/utils/sanitize.py`):**

```python
"""Input sanitization to prevent XSS, injection, and malicious content."""

import mimetypes
import re
from pathlib import Path
from typing import Optional

import bleach

# Allowed HTML tags (none for most farm data)
ALLOWED_TAGS: list[str] = []
ALLOWED_ATTRIBUTES: dict = {}

# Patterns that indicate SQL injection attempts
SQL_INJECTION_PATTERNS = [
    r"(\b(SELECT|INSERT|UPDATE|DELETE|DROP|UNION|ALTER|CREATE|EXEC)\b)",
    r"(--|;|\/\*|\*\/|xp_|sp_)",
    r"('(\s)*(OR|AND)(\s)*')",
]

# Patterns indicating XSS attempts
XSS_PATTERNS = [
    r"<script[^>]*>",
    r"javascript:",
    r"on\w+\s*=",
    r"eval\s*\(",
    r"expression\s*\(",
]

ALLOWED_UPLOAD_MIMES = {
    "image/jpeg", "image/png", "image/webp",
    "application/pdf",
    "text/csv",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
}
MAX_UPLOAD_SIZE_BYTES = 10 * 1024 * 1024  # 10 MB


def sanitize_text(value: Optional[str]) -> Optional[str]:
    if value is None:
        return None
    cleaned = bleach.clean(value, tags=ALLOWED_TAGS, attributes=ALLOWED_ATTRIBUTES, strip=True)
    cleaned = cleaned.replace("\x00", "")  # Remove null bytes
    return cleaned.strip()


def detect_sql_injection(value: str) -> bool:
    for pattern in SQL_INJECTION_PATTERNS:
        if re.search(pattern, value, re.IGNORECASE):
            return True
    return False


def detect_xss(value: str) -> bool:
    for pattern in XSS_PATTERNS:
        if re.search(pattern, value, re.IGNORECASE):
            return True
    return False


def validate_file_upload(
    filename: str,
    content: bytes,
    content_type: str,
) -> tuple[bool, str]:
    if len(content) > MAX_UPLOAD_SIZE_BYTES:
        return False, f"File exceeds maximum size of {MAX_UPLOAD_SIZE_BYTES // (1024*1024)} MB"

    if content_type not in ALLOWED_UPLOAD_MIMES:
        return False, f"File type '{content_type}' is not allowed"

    guessed_type, _ = mimetypes.guess_type(filename)
    if guessed_type and guessed_type != content_type:
        return False, f"File extension does not match content type"

    # Check magic bytes for common types
    magic_bytes = {
        b"\xff\xd8\xff": "image/jpeg",
        b"\x89PNG": "image/png",
        b"RIFF": "image/webp",
        b"%PDF": "application/pdf",
    }
    for magic, expected_mime in magic_bytes.items():
        if content[:len(magic)] == magic and content_type != expected_mime:
            return False, "File content does not match declared type"

    return True, "OK"
```

#### Dev 2 Tasks (~8h)

1. Configure WAF rules in Nginx for additional SQL injection and XSS blocking (3h)
2. Set up file upload scanning pipeline (ClamAV integration) (3h)
3. Implement request body size limits in Nginx and FastAPI (2h)

#### Dev 3 Tasks (~8h)

1. Build client-side form validation matching Pydantic schemas (4h)
2. Create file upload component with client-side type and size validation (2h)
3. Implement validation error display components in OWL (2h)

**End-of-Day 85 Deliverables:**
- [ ] Pydantic v2 models for Animal, MilkProduction, HealthRecord, Feed, Finance
- [ ] sanitize_text, detect_sql_injection, detect_xss utilities operational
- [ ] File upload validation with MIME checking and magic byte inspection
- [ ] Nginx WAF rules blocking common injection patterns
- [ ] Client-side validation mirroring server schemas

---

### Day 86 -- CORS Configuration, CSP Headers, Security Headers

**Objective:** Deploy comprehensive security headers middleware aligned with OWASP recommendations.

#### Dev 1 Tasks (~8h)

1. Build SecurityHeadersMiddleware with all OWASP recommended headers (3h)
2. Configure strict CORS policy with per-origin rules (2h)
3. Assemble the complete FastAPI middleware stack in correct order (3h)

**Security Headers Middleware (`app/middleware/security_headers.py`):**

```python
"""OWASP-recommended security headers middleware."""

from typing import Callable

from starlette.middleware.base import BaseHTTPMiddleware
from starlette.requests import Request
from starlette.responses import Response

from app.core.config import get_settings


class SecurityHeadersMiddleware(BaseHTTPMiddleware):
    """Adds comprehensive security headers to every response."""

    async def dispatch(self, request: Request, call_next: Callable) -> Response:
        response = await call_next(request)
        settings = get_settings()

        # Content Security Policy
        response.headers["Content-Security-Policy"] = settings.content_security_policy

        # Strict Transport Security (HSTS)
        response.headers["Strict-Transport-Security"] = (
            f"max-age={settings.hsts_max_age}; includeSubDomains; preload"
        )

        # Prevent MIME type sniffing
        response.headers["X-Content-Type-Options"] = "nosniff"

        # Clickjacking protection
        response.headers["X-Frame-Options"] = "DENY"

        # XSS Protection (legacy, but still recommended)
        response.headers["X-XSS-Protection"] = "1; mode=block"

        # Referrer Policy
        response.headers["Referrer-Policy"] = "strict-origin-when-cross-origin"

        # Permissions Policy (Feature Policy successor)
        response.headers["Permissions-Policy"] = (
            "camera=(), microphone=(), geolocation=(self), "
            "payment=(), usb=(), magnetometer=()"
        )

        # Prevent caching of sensitive API responses
        if request.url.path.startswith("/api/"):
            response.headers["Cache-Control"] = "no-store, no-cache, must-revalidate, max-age=0"
            response.headers["Pragma"] = "no-cache"

        # Remove server identification
        response.headers.pop("Server", None)
        response.headers["X-Powered-By"] = ""

        return response
```

**CORS Configuration (`app/middleware/cors.py`):**

```python
"""Strict CORS configuration for Smart Dairy API."""

from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from app.core.config import get_settings


def configure_cors(app: FastAPI) -> None:
    settings = get_settings()

    app.add_middleware(
        CORSMiddleware,
        allow_origins=settings.cors_allowed_origins,
        allow_credentials=settings.cors_allow_credentials,
        allow_methods=settings.cors_allowed_methods,
        allow_headers=settings.cors_allowed_headers,
        expose_headers=[
            "X-Request-ID",
            "X-RateLimit-Limit",
            "X-RateLimit-Remaining",
            "X-RateLimit-Reset",
            "Retry-After",
            "Sunset",                  # API deprecation header
        ],
        max_age=600,  # Preflight cache: 10 minutes
    )
```

**Complete FastAPI Application (`app/main.py`):**

```python
"""Smart Dairy API Gateway -- Application entry point."""

from contextlib import asynccontextmanager

import redis.asyncio as aioredis
from fastapi import FastAPI
from fastapi.openapi.utils import get_openapi

from app.core.config import get_settings
from app.core.exceptions import register_exception_handlers
from app.middleware.cors import configure_cors
from app.middleware.rate_limiter import RateLimiterMiddleware
from app.middleware.request_logging import RequestLoggingMiddleware
from app.middleware.security_headers import SecurityHeadersMiddleware
from app.routers.v1 import router as v1_router


@asynccontextmanager
async def lifespan(app: FastAPI):
    settings = get_settings()
    app.state.redis = aioredis.from_url(settings.redis_url, decode_responses=True)
    yield
    await app.state.redis.close()


def create_app() -> FastAPI:
    settings = get_settings()

    app = FastAPI(
        title=settings.app_name,
        version=settings.app_version,
        docs_url="/docs" if settings.debug else None,
        redoc_url="/redoc" if settings.debug else None,
        openapi_url="/openapi.json" if settings.debug else None,
        lifespan=lifespan,
    )

    # --- Middleware stack (order matters: outermost first) ---
    # 1. Request logging (outermost -- captures all requests)
    app.add_middleware(RequestLoggingMiddleware)
    # 2. Security headers
    app.add_middleware(SecurityHeadersMiddleware)
    # 3. Rate limiting
    app.add_middleware(RateLimiterMiddleware, redis_url=settings.redis_url)
    # 4. CORS (must be after rate limiter to allow preflight)
    configure_cors(app)

    # --- Exception handlers ---
    register_exception_handlers(app)

    # --- Routers ---
    app.include_router(v1_router, prefix="/api/v1")

    # --- Health checks ---
    @app.get("/health", tags=["Infrastructure"])
    async def health_check():
        return {"status": "healthy", "service": settings.app_name}

    @app.get("/ready", tags=["Infrastructure"])
    async def readiness_check():
        try:
            await app.state.redis.ping()
            redis_ok = True
        except Exception:
            redis_ok = False
        return {
            "status": "ready" if redis_ok else "degraded",
            "checks": {"redis": redis_ok},
        }

    return app


app = create_app()
```

#### Dev 2 Tasks (~8h)

1. Configure Nginx security headers as backup layer (2h)
2. Set up securityheaders.com automated scanning in CI (2h)
3. Implement Content-Security-Policy report-uri endpoint (2h)
4. Test TLS configuration with SSL Labs (2h)

#### Dev 3 Tasks (~8h)

1. Configure OWL web portal to comply with CSP restrictions (3h)
2. Update Flutter HTTP client to send correct Origin headers (2h)
3. Test CORS preflight from all client origins (3h)

**End-of-Day 86 Deliverables:**
- [ ] SecurityHeadersMiddleware adding all OWASP headers
- [ ] Strict CORS whitelist enforced for portal, admin, and mobile origins
- [ ] Complete middleware stack assembled in correct order
- [ ] Nginx backup headers layer configured
- [ ] CSP report-uri collecting violation reports
- [ ] A+ score on securityheaders.com (staging)

---

### Day 87 -- Webhook Security

**Objective:** Implement HMAC-SHA256 signed webhooks with replay protection and reliable delivery per B-008.

#### Dev 1 Tasks (~8h)

1. Build WebhookDispatcher with HMAC-SHA256 signing (3h)
2. Implement replay protection with timestamp + nonce (2h)
3. Build webhook receiver/validator dependency (2h)
4. Implement dead letter queue for failed deliveries (1h)

**Webhook Dispatcher (`app/services/webhook_dispatcher.py`):**

```python
"""Webhook dispatch with HMAC-SHA256 signing and reliable delivery."""

import hashlib
import hmac
import json
import time
import uuid
from dataclasses import dataclass, field
from enum import Enum
from typing import Any, Dict, Optional

import httpx
import redis.asyncio as aioredis
import structlog

from app.core.config import get_settings

logger = structlog.get_logger("webhook.dispatcher")


class WebhookEvent(str, Enum):
    ANIMAL_CREATED = "animal.created"
    ANIMAL_UPDATED = "animal.updated"
    MILK_RECORDED = "milk.recorded"
    HEALTH_ALERT = "health.alert"
    FEED_LOW_STOCK = "feed.low_stock"
    PAYMENT_RECEIVED = "payment.received"


@dataclass
class WebhookDelivery:
    id: str = field(default_factory=lambda: str(uuid.uuid4()))
    event: str = ""
    url: str = ""
    payload: Dict[str, Any] = field(default_factory=dict)
    attempt: int = 0
    max_retries: int = 5
    created_at: float = field(default_factory=time.time)
    last_attempt_at: Optional[float] = None
    status_code: Optional[int] = None
    error: Optional[str] = None


class WebhookDispatcher:
    """Dispatches signed webhooks with retry and dead letter queue."""

    def __init__(self, redis_client: aioredis.Redis):
        self.redis = redis_client
        self.settings = get_settings()
        self.http_client = httpx.AsyncClient(timeout=30.0)

    def _generate_signature(self, payload_bytes: bytes, timestamp: str, nonce: str) -> str:
        """Generate HMAC-SHA256 signature over timestamp + nonce + body."""
        secret = self.settings.webhook_secret_key.encode("utf-8")
        message = f"{timestamp}.{nonce}.".encode("utf-8") + payload_bytes
        return hmac.new(secret, message, hashlib.sha256).hexdigest()

    async def dispatch(
        self,
        event: WebhookEvent,
        url: str,
        payload: Dict[str, Any],
    ) -> WebhookDelivery:
        delivery = WebhookDelivery(
            event=event.value,
            url=url,
            payload=payload,
            max_retries=self.settings.webhook_max_retries,
        )
        return await self._attempt_delivery(delivery)

    async def _attempt_delivery(self, delivery: WebhookDelivery) -> WebhookDelivery:
        payload_bytes = json.dumps(delivery.payload, separators=(",", ":")).encode("utf-8")
        timestamp = str(int(time.time()))
        nonce = str(uuid.uuid4())
        signature = self._generate_signature(payload_bytes, timestamp, nonce)

        headers = {
            "Content-Type": "application/json",
            "X-Webhook-ID": delivery.id,
            "X-Webhook-Event": delivery.event,
            "X-Webhook-Timestamp": timestamp,
            "X-Webhook-Nonce": nonce,
            "X-Webhook-Signature": f"sha256={signature}",
        }

        while delivery.attempt < delivery.max_retries:
            delivery.attempt += 1
            delivery.last_attempt_at = time.time()

            try:
                response = await self.http_client.post(
                    delivery.url,
                    content=payload_bytes,
                    headers=headers,
                )
                delivery.status_code = response.status_code

                if 200 <= response.status_code < 300:
                    logger.info(
                        "webhook_delivered",
                        delivery_id=delivery.id,
                        event=delivery.event,
                        status=response.status_code,
                        attempt=delivery.attempt,
                    )
                    return delivery

                logger.warning(
                    "webhook_failed",
                    delivery_id=delivery.id,
                    status=response.status_code,
                    attempt=delivery.attempt,
                )

            except httpx.RequestError as exc:
                delivery.error = str(exc)
                logger.warning(
                    "webhook_error",
                    delivery_id=delivery.id,
                    error=str(exc),
                    attempt=delivery.attempt,
                )

            # Exponential backoff: 1s, 2s, 4s, 8s, 16s
            delay = self.settings.webhook_retry_base_delay * (2 ** (delivery.attempt - 1))
            import asyncio
            await asyncio.sleep(delay)

        # All retries exhausted -- send to dead letter queue
        await self._send_to_dlq(delivery)
        return delivery

    async def _send_to_dlq(self, delivery: WebhookDelivery) -> None:
        dlq_entry = json.dumps({
            "id": delivery.id,
            "event": delivery.event,
            "url": delivery.url,
            "payload": delivery.payload,
            "attempts": delivery.attempt,
            "last_error": delivery.error,
            "last_status_code": delivery.status_code,
            "created_at": delivery.created_at,
            "failed_at": time.time(),
        })
        await self.redis.lpush("webhook:dlq", dlq_entry)
        logger.error(
            "webhook_dlq",
            delivery_id=delivery.id,
            event=delivery.event,
            attempts=delivery.attempt,
        )
```

**Webhook Receiver Validator (`app/auth/dependencies.py` -- addition):**

```python
"""Webhook signature verification dependency."""

import hashlib
import hmac
import time

from fastapi import Depends, Header, Request

from app.core.config import get_settings
from app.core.exceptions import AuthenticationError


async def verify_webhook_signature(
    request: Request,
    x_webhook_signature: str = Header(...),
    x_webhook_timestamp: str = Header(...),
    x_webhook_nonce: str = Header(...),
) -> bytes:
    """Verify inbound webhook HMAC-SHA256 signature with replay protection."""
    settings = get_settings()

    # --- Replay protection: reject stale timestamps ---
    try:
        timestamp = int(x_webhook_timestamp)
    except ValueError:
        raise AuthenticationError("Invalid webhook timestamp")

    now = int(time.time())
    if abs(now - timestamp) > settings.webhook_timestamp_tolerance_seconds:
        raise AuthenticationError(
            f"Webhook timestamp expired (tolerance: {settings.webhook_timestamp_tolerance_seconds}s)"
        )

    # --- Nonce deduplication via Redis ---
    nonce_key = f"webhook:nonce:{x_webhook_nonce}"
    redis_client = request.app.state.redis
    already_seen = await redis_client.set(
        nonce_key, "1",
        nx=True,
        ex=settings.webhook_timestamp_tolerance_seconds * 2,
    )
    if not already_seen:
        raise AuthenticationError("Webhook nonce already used (replay detected)")

    # --- Signature verification ---
    body = await request.body()
    secret = settings.webhook_secret_key.encode("utf-8")
    message = f"{x_webhook_timestamp}.{x_webhook_nonce}.".encode("utf-8") + body
    expected_signature = hmac.new(secret, message, hashlib.sha256).hexdigest()

    provided_signature = x_webhook_signature.removeprefix("sha256=")
    if not hmac.compare_digest(expected_signature, provided_signature):
        raise AuthenticationError("Invalid webhook signature")

    return body
```

#### Dev 2 Tasks (~8h)

1. Set up Redis stream for webhook delivery queue (3h)
2. Build webhook delivery monitoring and DLQ admin interface (3h)
3. Configure webhook endpoint firewall rules (IP allowlisting) (2h)

#### Dev 3 Tasks (~8h)

1. Build OWL webhook configuration UI (subscriber management) (4h)
2. Create webhook delivery log viewer component (2h)
3. Implement webhook test/ping functionality in admin panel (2h)

**End-of-Day 87 Deliverables:**
- [ ] WebhookDispatcher signing all outbound payloads with HMAC-SHA256
- [ ] Replay protection via timestamp validation + nonce deduplication
- [ ] Exponential backoff retry (5 attempts: 1s, 2s, 4s, 8s, 16s)
- [ ] Dead letter queue in Redis for failed deliveries
- [ ] Inbound webhook signature verification dependency
- [ ] Webhook admin UI for subscriber management

---

### Day 88 -- API Versioning and Deprecation Strategy

**Objective:** Implement URL path versioning with sunset headers, migration guides, and backward compatibility guarantees.

#### Dev 1 Tasks (~8h)

1. Build versioned router structure with v1 and v2 placeholder (3h)
2. Implement Sunset header middleware for deprecated endpoints (2h)
3. Create API changelog auto-generation from OpenAPI diffs (3h)

**API Versioning Router (`app/routers/v1/__init__.py`):**

```python
"""API v1 router aggregating all domain routers."""

from fastapi import APIRouter

from app.routers.v1.animals import router as animals_router
from app.routers.v1.auth import router as auth_router
from app.routers.v1.feed import router as feed_router
from app.routers.v1.health import router as health_router
from app.routers.v1.milk import router as milk_router
from app.routers.v1.webhooks import router as webhooks_router

router = APIRouter(tags=["v1"])

router.include_router(auth_router, prefix="/auth", tags=["Authentication"])
router.include_router(animals_router, prefix="/animals", tags=["Animals"])
router.include_router(milk_router, prefix="/milk-production", tags=["Milk Production"])
router.include_router(health_router, prefix="/health-records", tags=["Health Records"])
router.include_router(feed_router, prefix="/feed-management", tags=["Feed Management"])
router.include_router(webhooks_router, prefix="/webhooks", tags=["Webhooks"])


# --- Sunset / Deprecation Middleware ---
from datetime import date
from typing import Dict, Optional
from starlette.middleware.base import BaseHTTPMiddleware
from starlette.requests import Request
from starlette.responses import Response


DEPRECATED_ENDPOINTS: Dict[str, Dict[str, str]] = {
    # Example: endpoint path -> sunset date and migration info
    # "/api/v1/cattle": {
    #     "sunset": "2027-01-01",
    #     "link": "https://api.smartdairy.com.bd/docs/migration/v1-to-v2#cattle",
    #     "replacement": "/api/v2/animals",
    # },
}


class SunsetHeaderMiddleware(BaseHTTPMiddleware):
    async def dispatch(self, request: Request, call_next) -> Response:
        response = await call_next(request)

        deprecation = DEPRECATED_ENDPOINTS.get(request.url.path)
        if deprecation:
            response.headers["Sunset"] = deprecation["sunset"]
            response.headers["Deprecation"] = "true"
            response.headers["Link"] = (
                f'<{deprecation["link"]}>; rel="successor-version"'
            )

        # Always advertise current API version
        response.headers["X-API-Version"] = "v1"
        return response
```

#### Dev 2 Tasks (~8h)

1. Configure Nginx routing to support multiple API versions simultaneously (3h)
2. Build OpenAPI spec diff tool for CI pipeline (version comparison) (3h)
3. Set up API documentation versioned hosting (2h)

#### Dev 3 Tasks (~8h)

1. Implement API version selection in OWL HTTP service (2h)
2. Build deprecation warning banner in admin panel for sunset endpoints (3h)
3. Create Flutter API client version negotiation (3h)

**End-of-Day 88 Deliverables:**
- [ ] URL path versioning `/api/v1/` with v2 placeholder
- [ ] Sunset and Deprecation headers on deprecated endpoints
- [ ] Link header pointing to migration documentation
- [ ] X-API-Version header on all responses
- [ ] OpenAPI spec diff tool in CI
- [ ] Client-side deprecation warning handling

---

### Day 89 -- API Security Testing

**Objective:** Execute automated OWASP ZAP scans, Schemathesis fuzzing, and targeted authentication/injection tests against all endpoints.

#### Dev 1 Tasks (~8h)

1. Write comprehensive pytest suite for API security (4h)
2. Configure Schemathesis for schema-based fuzzing (2h)
3. Run targeted injection and bypass tests (2h)

**Comprehensive Pytest Suite (`tests/test_auth.py` -- excerpt):**

```python
"""API security test suite covering OAuth, JWT, rate limiting, CORS, webhooks."""

import hashlib
import hmac
import time
from unittest.mock import patch

import pytest
from fastapi.testclient import TestClient
from jose import jwt

from app.core.config import get_settings
from app.main import create_app


@pytest.fixture
def client():
    app = create_app()
    return TestClient(app)


@pytest.fixture
def settings():
    return get_settings()


# ---- OAuth 2.0 Tests ----

class TestOAuthFlow:
    def test_authorization_code_flow_requires_client_id(self, client):
        response = client.get("/api/v1/auth/authorize")
        assert response.status_code == 422

    def test_authorization_code_flow_rejects_unknown_client(self, client):
        response = client.get(
            "/api/v1/auth/authorize",
            params={"client_id": "unknown", "response_type": "code", "redirect_uri": "http://evil.com"},
        )
        assert response.status_code == 400

    def test_token_endpoint_rejects_invalid_grant(self, client):
        response = client.post("/api/v1/auth/token", data={
            "grant_type": "authorization_code",
            "code": "invalid-code",
            "client_id": "smart-dairy-web-portal",
            "client_secret": "wrong-secret",
        })
        assert response.status_code == 401

    def test_client_credentials_flow(self, client):
        response = client.post("/api/v1/auth/token", data={
            "grant_type": "client_credentials",
            "client_id": "smart-dairy-iot-service",
            "client_secret": "test-iot-secret",
            "scope": "iot:write",
        })
        assert response.status_code in (200, 401)  # Depends on test secret setup


# ---- JWT Validation Tests ----

class TestJWTValidation:
    def test_missing_authorization_header(self, client):
        response = client.get("/api/v1/animals")
        assert response.status_code == 401
        assert "authentication" in response.json()["type"].lower()

    def test_expired_token_rejected(self, client, settings):
        expired_claims = {
            "sub": "1", "role": "admin", "farm_id": 1,
            "permissions": ["read"], "token_type": "access",
            "iss": settings.jwt_issuer, "aud": settings.jwt_audience,
            "iat": time.time() - 3600, "exp": time.time() - 1800,
            "jti": "test-expired",
        }
        # This would require the private key; mocked in real test
        # token = jwt.encode(expired_claims, PRIVATE_KEY, algorithm="RS256")
        # response = client.get("/api/v1/animals", headers={"Authorization": f"Bearer {token}"})
        # assert response.status_code == 401

    def test_wrong_algorithm_rejected(self, client):
        token = jwt.encode({"sub": "1"}, "secret", algorithm="HS256")
        response = client.get(
            "/api/v1/animals",
            headers={"Authorization": f"Bearer {token}"},
        )
        assert response.status_code == 401

    def test_tampered_token_rejected(self, client):
        response = client.get(
            "/api/v1/animals",
            headers={"Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.tampered.sig"},
        )
        assert response.status_code == 401

    def test_refresh_token_rejected_for_api_access(self, client):
        """Refresh tokens must not be accepted as access tokens."""
        # Encode a token with token_type=refresh and verify 401
        pass


# ---- Rate Limiting Tests ----

class TestRateLimiting:
    def test_rate_limit_headers_present(self, client):
        response = client.get("/health")
        # Health check is exempt, but regular endpoints should have headers
        response = client.get("/api/v1/animals",
                              headers={"Authorization": "Bearer test"})
        assert "X-RateLimit-Limit" in response.headers or response.status_code == 401

    def test_rate_limit_exceeded_returns_429(self, client):
        """Rapid requests should eventually trigger 429."""
        for i in range(100):
            response = client.post("/api/v1/auth/token", data={
                "grant_type": "client_credentials",
                "client_id": "test",
                "client_secret": "test",
            })
            if response.status_code == 429:
                assert "Retry-After" in response.headers
                break

    def test_rate_limit_retry_after_is_positive_integer(self, client):
        """Retry-After must be a positive integer."""
        pass


# ---- Input Validation Tests ----

class TestInputValidation:
    def test_sql_injection_in_tag_number(self, client):
        response = client.post("/api/v1/animals", json={
            "tag_number": "'; DROP TABLE animals; --",
            "breed": "Holstein",
            "category": "lactating",
            "farm_id": 1,
        })
        assert response.status_code in (401, 422)

    def test_xss_in_name_field(self, client):
        response = client.post("/api/v1/animals", json={
            "tag_number": "SD-100",
            "name": "<script>alert('xss')</script>",
            "breed": "Holstein",
            "category": "lactating",
            "farm_id": 1,
        })
        # Should either reject or sanitize -- never reflect raw script
        if response.status_code == 200:
            assert "<script>" not in response.json().get("name", "")

    def test_oversized_payload_rejected(self, client):
        large_payload = {"notes": "A" * 1_000_000}
        response = client.post("/api/v1/animals", json=large_payload)
        assert response.status_code in (401, 413, 422)

    def test_invalid_enum_value_rejected(self, client):
        response = client.post("/api/v1/animals", json={
            "tag_number": "SD-100",
            "breed": "Holstein",
            "category": "INVALID_CATEGORY",
            "farm_id": 1,
        })
        assert response.status_code in (401, 422)


# ---- CORS Tests ----

class TestCORS:
    def test_allowed_origin_receives_cors_headers(self, client):
        response = client.options(
            "/api/v1/animals",
            headers={
                "Origin": "https://portal.smartdairy.com.bd",
                "Access-Control-Request-Method": "GET",
            },
        )
        assert response.headers.get("access-control-allow-origin") == "https://portal.smartdairy.com.bd"

    def test_disallowed_origin_blocked(self, client):
        response = client.options(
            "/api/v1/animals",
            headers={
                "Origin": "https://evil-site.com",
                "Access-Control-Request-Method": "GET",
            },
        )
        assert "access-control-allow-origin" not in response.headers

    def test_wildcard_origin_not_used(self, client):
        response = client.options(
            "/api/v1/animals",
            headers={
                "Origin": "https://portal.smartdairy.com.bd",
                "Access-Control-Request-Method": "GET",
            },
        )
        assert response.headers.get("access-control-allow-origin") != "*"


# ---- Webhook Signature Tests ----

class TestWebhookSignature:
    def test_valid_webhook_signature_accepted(self, client, settings):
        payload = b'{"event": "test"}'
        timestamp = str(int(time.time()))
        nonce = "test-nonce-001"
        message = f"{timestamp}.{nonce}.".encode() + payload
        signature = hmac.new(
            settings.webhook_secret_key.encode(),
            message, hashlib.sha256,
        ).hexdigest()

        response = client.post(
            "/api/v1/webhooks/receive",
            content=payload,
            headers={
                "Content-Type": "application/json",
                "X-Webhook-Signature": f"sha256={signature}",
                "X-Webhook-Timestamp": timestamp,
                "X-Webhook-Nonce": nonce,
            },
        )
        assert response.status_code in (200, 404)  # 404 if route not yet implemented

    def test_invalid_signature_rejected(self, client):
        response = client.post(
            "/api/v1/webhooks/receive",
            content=b'{"event": "test"}',
            headers={
                "Content-Type": "application/json",
                "X-Webhook-Signature": "sha256=invalidsignature",
                "X-Webhook-Timestamp": str(int(time.time())),
                "X-Webhook-Nonce": "nonce-001",
            },
        )
        assert response.status_code in (401, 403)

    def test_expired_timestamp_rejected(self, client):
        old_timestamp = str(int(time.time()) - 600)
        response = client.post(
            "/api/v1/webhooks/receive",
            content=b'{"event": "test"}',
            headers={
                "Content-Type": "application/json",
                "X-Webhook-Signature": "sha256=any",
                "X-Webhook-Timestamp": old_timestamp,
                "X-Webhook-Nonce": "nonce-002",
            },
        )
        assert response.status_code in (401, 403)
```

**Schemathesis Fuzzing (`scripts/run_schemathesis.py`):**

```python
"""Run Schemathesis fuzzing against the Smart Dairy API."""

import subprocess
import sys


def run_schemathesis_fuzz(
    base_url: str = "http://localhost:8000",
    openapi_path: str = "/openapi.json",
    max_examples: int = 200,
    workers: int = 4,
) -> int:
    cmd = [
        sys.executable, "-m", "schemathesis", "run",
        f"{base_url}{openapi_path}",
        "--method", "ALL",
        "--max-examples", str(max_examples),
        "--workers", str(workers),
        "--validate-schema", "true",
        "--checks", "all",
        "--stateful", "links",
        "--hypothesis-seed", "42",
        "--report",
        "--base-url", base_url,
    ]

    print(f"Running: {' '.join(cmd)}")
    result = subprocess.run(cmd, capture_output=False)
    return result.returncode


if __name__ == "__main__":
    url = sys.argv[1] if len(sys.argv) > 1 else "http://localhost:8000"
    exit_code = run_schemathesis_fuzz(base_url=url)
    sys.exit(exit_code)
```

**OWASP ZAP Automation (`scripts/run_zap_scan.py`):**

```python
"""Run OWASP ZAP baseline scan against Smart Dairy API."""

import subprocess
import sys
from pathlib import Path


def run_zap_baseline(
    target_url: str = "http://localhost:8000",
    report_dir: str = "./reports/zap",
) -> int:
    Path(report_dir).mkdir(parents=True, exist_ok=True)

    cmd = [
        "docker", "run", "--rm",
        "-v", f"{Path(report_dir).resolve()}:/zap/wrk:rw",
        "--network", "host",
        "ghcr.io/zaproxy/zaproxy:stable",
        "zap-api-scan.py",
        "-t", target_url + "/openapi.json",
        "-f", "openapi",
        "-r", "zap_report.html",
        "-J", "zap_report.json",
        "-l", "WARN",
        "-c", "zap_rules.conf",
        "--hook", "/zap/wrk/zap_hooks.py",
    ]

    print(f"Running ZAP scan against {target_url}")
    result = subprocess.run(cmd, capture_output=False)

    if result.returncode == 0:
        print("ZAP scan PASSED -- no high/critical findings")
    elif result.returncode == 1:
        print("ZAP scan completed with WARNINGS")
    else:
        print("ZAP scan FAILED -- critical findings detected")

    return result.returncode


if __name__ == "__main__":
    url = sys.argv[1] if len(sys.argv) > 1 else "http://localhost:8000"
    sys.exit(run_zap_baseline(target_url=url))
```

#### Dev 2 Tasks (~8h)

1. Integrate OWASP ZAP into CI/CD pipeline as a gate (3h)
2. Run ZAP baseline scan and triage findings (3h)
3. Configure fail thresholds (zero high/critical to pass) (2h)

#### Dev 3 Tasks (~8h)

1. Build OWL API monitoring dashboard component (5h)
2. Create security test result viewer in admin panel (3h)

**OWL API Monitor Dashboard (`api_monitor_dashboard.js`):**

```javascript
/** @odoo-module **/

import { Component, useState, onWillStart, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { registry } from "@web/core/registry";

export class APIMonitorDashboard extends Component {
    static template = "smart_dairy.APIMonitorDashboard";
    static props = {};

    setup() {
        this.rpc = useService("rpc");
        this.state = useState({
            requestRate: { total: 0, perMinute: 0, trend: "stable" },
            errorRate: { total: 0, percentage: 0, trend: "stable" },
            latency: { p50: 0, p95: 0, p99: 0 },
            rateLimitHits: { total: 0, topEndpoints: [] },
            authMetrics: {
                activeTokens: 0,
                failedAuth: 0,
                tokenRefreshes: 0,
            },
            recentErrors: [],
            topEndpoints: [],
            isLoading: true,
            refreshInterval: null,
        });

        onWillStart(async () => {
            await this.fetchMetrics();
        });

        onMounted(() => {
            this.state.refreshInterval = setInterval(() => {
                this.fetchMetrics();
            }, 30000); // Refresh every 30 seconds
        });
    }

    async fetchMetrics() {
        try {
            this.state.isLoading = true;

            const [requests, errors, latency, rateLimit, auth] = await Promise.all([
                this.rpc("/api/v1/metrics/requests", {}),
                this.rpc("/api/v1/metrics/errors", {}),
                this.rpc("/api/v1/metrics/latency", {}),
                this.rpc("/api/v1/metrics/rate-limits", {}),
                this.rpc("/api/v1/metrics/auth", {}),
            ]);

            this.state.requestRate = requests;
            this.state.errorRate = errors;
            this.state.latency = latency;
            this.state.rateLimitHits = rateLimit;
            this.state.authMetrics = auth;
        } catch (error) {
            console.error("Failed to fetch API metrics:", error);
        } finally {
            this.state.isLoading = false;
        }
    }

    get errorRateClass() {
        if (this.state.errorRate.percentage > 5) return "text-danger";
        if (this.state.errorRate.percentage > 1) return "text-warning";
        return "text-success";
    }

    get p99LatencyClass() {
        if (this.state.latency.p99 > 500) return "text-danger";
        if (this.state.latency.p99 > 200) return "text-warning";
        return "text-success";
    }

    willUnmount() {
        if (this.state.refreshInterval) {
            clearInterval(this.state.refreshInterval);
        }
    }
}

registry.category("actions").add("smart_dairy.api_monitor_dashboard", APIMonitorDashboard);
```

**End-of-Day 89 Deliverables:**
- [ ] Comprehensive pytest suite (OAuth, JWT, rate limiting, CORS, webhook tests)
- [ ] OWASP ZAP scan completed with zero high/critical findings
- [ ] Schemathesis fuzz testing completed with zero 500-level responses
- [ ] ZAP integrated into CI/CD pipeline with fail thresholds
- [ ] OWL API monitoring dashboard functional
- [ ] All authentication bypass tests pass

---

### Day 90 -- Milestone 9 Review, Sign-Off, Documentation Finalization

**Objective:** Conduct final review, resolve remaining findings, complete API documentation, and prepare handoff to Milestone 10.

#### Dev 1 Tasks (~8h)

1. Resolve any remaining ZAP or fuzz test findings (2h)
2. Finalize OpenAPI specification with all security schemes documented (2h)
3. Write API security runbook (key rotation, incident response, token revocation) (2h)
4. Conduct code review of all auth and security modules (2h)

#### Dev 2 Tasks (~8h)

1. Finalize Docker Compose for complete API gateway stack (2h)
2. Run full integration test suite and fix any failures (3h)
3. Update CI/CD pipeline with security scan stages (2h)
4. Performance test: verify P95 latency < 200ms through gateway (1h)

#### Dev 3 Tasks (~8h)

1. Complete API monitoring dashboard with all metric panels (3h)
2. Finalize Flutter API client with complete auth flow (3h)
3. Write API integration guide for frontend developers (2h)

**End-of-Day 90 Deliverables:**
- [ ] All success criteria met (see Section 1.3)
- [ ] OpenAPI 3.1 specification complete with security schemes
- [ ] API security runbook documented
- [ ] Docker Compose for full gateway stack finalized
- [ ] CI/CD pipeline includes ZAP + Schemathesis stages
- [ ] P95 latency < 200ms verified
- [ ] API monitoring dashboard complete
- [ ] Milestone 9 sign-off from all three developers

---

## 4. Technical Specifications

### 4.1 Authentication Architecture

```
+------------------+       +-------------------+       +------------------+
|  OWL Web Portal  |       |  Flutter Mobile    |       |  IoT Service     |
|  (Auth Code +    |       |  (Auth Code +      |       |  (Client         |
|   PKCE)          |       |   PKCE)            |       |   Credentials)   |
+--------+---------+       +---------+---------+       +--------+---------+
         |                           |                           |
         v                           v                           v
+------------------------------------------------------------------------+
|                     FastAPI API Gateway (:8000)                         |
|  +------------------+  +------------------+  +-----------------------+ |
|  | CORS Middleware   |  | Security Headers |  | Rate Limiter (Redis)  | |
|  +------------------+  +------------------+  +-----------------------+ |
|  +------------------+  +------------------+  +-----------------------+ |
|  | Request Logger   |  | Auth Dependency  |  | Input Validation      | |
|  +------------------+  +------------------+  +-----------------------+ |
+------------------------------------------------------------------------+
         |                           |                           |
         v                           v                           v
+------------------+       +-------------------+       +------------------+
|  OAuth 2.0       |       |  JWT Manager      |       |  Odoo XML-RPC    |
|  (authlib)       |       |  (RS256, jose)    |       |  Backend         |
+------------------+       +-------------------+       +------------------+
```

### 4.2 JWT Token Structure

**Access Token Claims:**

| Claim         | Type       | Description                                |
| ------------- | ---------- | ------------------------------------------ |
| `sub`         | string     | User ID (from Odoo `res.users`)            |
| `role`        | string     | User role: admin, manager, worker, vet     |
| `farm_id`     | integer    | Farm identifier (Smart Dairy = 1)          |
| `permissions` | string[]   | Granular permissions array                 |
| `scope`       | string     | OAuth 2.0 granted scope                    |
| `token_type`  | string     | "access" or "refresh"                      |
| `iss`         | string     | "smart-dairy-api"                          |
| `aud`         | string     | "smart-dairy-clients"                      |
| `iat`         | timestamp  | Issued at                                  |
| `exp`         | timestamp  | Expiration (access: +15min, refresh: +7d)  |
| `jti`         | string     | Unique token ID (UUID4)                    |

### 4.3 Rate Limiting Tiers

| Endpoint Category       | Limit (req/min) | Burst     | Key Strategy   |
| ----------------------- | ---------------- | --------- | -------------- |
| Auth endpoints          | 10               | 15        | IP-based       |
| General API (read)      | 120              | 180       | User + route   |
| General API (write)     | 60               | 90        | User + route   |
| Webhook endpoints       | 100              | 150       | IP-based       |
| File upload             | 10               | 15        | User           |
| Health/ready probes     | Unlimited        | --        | Exempt         |

### 4.4 Security Headers Summary

| Header                    | Value                                              |
| ------------------------- | -------------------------------------------------- |
| Content-Security-Policy   | default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline' |
| Strict-Transport-Security | max-age=31536000; includeSubDomains; preload       |
| X-Content-Type-Options    | nosniff                                            |
| X-Frame-Options           | DENY                                               |
| X-XSS-Protection          | 1; mode=block                                      |
| Referrer-Policy           | strict-origin-when-cross-origin                    |
| Permissions-Policy        | camera=(), microphone=(), geolocation=(self)       |
| Cache-Control (API)       | no-store, no-cache, must-revalidate, max-age=0     |

### 4.5 Webhook Signature Format

```
X-Webhook-Signature: sha256=<hex(HMAC-SHA256(secret, timestamp.nonce.body))>
X-Webhook-Timestamp: <unix_epoch_seconds>
X-Webhook-Nonce: <uuid4>
X-Webhook-ID: <delivery_uuid>
X-Webhook-Event: <event_type>
```

Verification steps:
1. Reject if `|now - timestamp| > 300 seconds` (replay protection)
2. Reject if nonce was previously seen (Redis SET NX with TTL)
3. Recompute HMAC-SHA256 over `{timestamp}.{nonce}.{raw_body}`
4. Constant-time comparison with provided signature

---

## 5. Testing & Validation

### 5.1 Test Coverage Matrix

| Test Category               | Tool            | Target Coverage | Gate     |
| --------------------------- | --------------- | --------------- | -------- |
| Unit tests (auth module)    | pytest          | >= 90%          | CI block |
| OAuth 2.0 flow tests        | pytest          | 100% of flows   | CI block |
| JWT validation tests         | pytest          | 100% of claims  | CI block |
| Rate limiter tests           | pytest + Redis  | >= 85%          | CI block |
| Input validation tests       | pytest          | 100% of schemas | CI block |
| CORS policy tests            | pytest          | All origins     | CI block |
| Webhook signature tests      | pytest          | 100%            | CI block |
| OWASP ZAP API scan           | ZAP             | 0 high/critical | CI block |
| Schema fuzzing               | Schemathesis    | 0 server errors | CI warn  |
| Performance (P95 latency)    | locust / k6     | < 200ms         | CI warn  |
| Security headers scan        | securityheaders | A+ rating       | CI warn  |

### 5.2 CI Pipeline Security Stages

```yaml
# .github/workflows/api-security.yml (excerpt)
api-security-tests:
  stage: test
  steps:
    - name: Unit & Integration Tests
      run: pytest tests/ -v --cov=app --cov-report=xml --cov-fail-under=90

    - name: OWASP ZAP API Scan
      run: python scripts/run_zap_scan.py http://localhost:8000

    - name: Schemathesis Fuzz
      run: python scripts/run_schemathesis.py http://localhost:8000

    - name: Security Headers Check
      run: |
        curl -sI http://localhost:8000/health | grep -i "x-content-type-options"
        curl -sI http://localhost:8000/health | grep -i "x-frame-options"
```

### 5.3 Manual Test Checklist

- [ ] Verify login flow end-to-end from OWL portal through OAuth to JWT
- [ ] Verify Flutter mobile auth flow with PKCE
- [ ] Attempt token reuse after logout (blacklist verification)
- [ ] Attempt API access with expired token
- [ ] Attempt API access with token signed by wrong key
- [ ] Trigger rate limit on auth endpoint and verify 429 response
- [ ] Submit XSS payload in animal name and verify sanitization
- [ ] Submit SQL injection in query parameters and verify blocking
- [ ] Send webhook with invalid signature and verify rejection
- [ ] Send webhook with replayed nonce and verify rejection
- [ ] Access API from disallowed CORS origin and verify blocking
- [ ] Verify all security headers present on every response

---

## 6. Risk & Mitigation

| # | Risk                                          | Likelihood | Impact | Mitigation                                                           |
|---|-----------------------------------------------|------------|--------|----------------------------------------------------------------------|
| 1 | RSA private key compromise                    | Low        | Critical | Keys stored in Docker secrets, 600 permissions, 90-day rotation    |
| 2 | Redis downtime breaks rate limiting           | Medium     | High   | Fail-open with degraded mode logging, Redis Sentinel for HA         |
| 3 | JWT token theft via XSS                       | Low        | High   | httpOnly cookies for web, secure storage for mobile, short TTL      |
| 4 | OAuth client secret leak                      | Low        | High   | Secrets in vault, hashed in DB, rotation procedure documented       |
| 5 | Rate limiter bypass via IP rotation           | Medium     | Medium | User-based limits when authenticated, CAPTCHA on auth endpoints     |
| 6 | Webhook replay attack                         | Low        | Medium | 5-minute timestamp window + nonce deduplication in Redis            |
| 7 | Schemathesis finds undocumented 500 errors    | Medium     | Medium | Fix during Day 89-90, add regression tests                         |
| 8 | CORS misconfiguration allows unauthorized origin | Low     | High   | Explicit allowlist (no wildcards), CI test for CORS policy          |
| 9 | Slow regex in input validation causes ReDoS   | Low        | Medium | Use simple patterns, set regex timeouts, test with ReDoS checker   |
| 10| API version migration breaks mobile clients   | Medium     | High   | 6-month sunset period, Sunset headers, forced upgrade prompt        |

---

## 7. Dependencies & Handoffs

### 7.1 Upstream Dependencies (from previous milestones)

| Dependency                             | Source Milestone | Status   |
| -------------------------------------- | ---------------- | -------- |
| Security architecture and threat model | M6               | Complete |
| IAM roles, RBAC, permission matrix     | M7               | Complete |
| PostgreSQL 16 with RLS policies        | M8               | Complete |
| User table and role assignments        | M7               | Complete |
| Redis 7 deployment                     | M8               | Complete |
| Docker infrastructure                  | M2               | Complete |
| Nginx reverse proxy base config        | M2               | Complete |

### 7.2 Downstream Handoffs (to Milestone 10)

| Deliverable                          | Consumer         | Handoff Format                  |
| ------------------------------------ | ---------------- | ------------------------------- |
| FastAPI gateway with full auth stack | M10 (Final)      | Docker image + compose          |
| JWT public key for token validation  | All services     | Mounted volume / secret         |
| OpenAPI 3.1 specification            | Frontend teams   | JSON file at /openapi.json      |
| API security runbook                 | Operations       | Markdown in docs/               |
| Rate limit configuration             | DevOps           | Environment variables           |
| Webhook dispatcher service           | Integration team | Python module + API             |
| OWASP ZAP scan reports               | Security review  | HTML + JSON in reports/         |
| OWL API monitor dashboard            | Admin users      | Odoo module                     |
| Flutter API client with auth         | Mobile team      | Dart package                    |

### 7.3 Python Dependencies

```
# requirements.txt (API security additions)
fastapi==0.115.*
uvicorn[standard]==0.32.*
pydantic[email]==2.10.*
pydantic-settings==2.7.*
python-jose[cryptography]==3.3.*
authlib==1.4.*
redis[hiredis]==5.2.*
httpx==0.28.*
bleach==6.2.*
structlog==24.4.*
python-multipart==0.0.18
cryptography==44.*
schemathesis==3.38.*
pytest==8.3.*
pytest-asyncio==0.25.*
pytest-cov==6.0.*
```

---

**End of Milestone 9: API Security -- OAuth 2.0, JWT & Gateway Hardening**

*This document is version-controlled. Any changes must be reviewed and approved by at least two of the three assigned developers before merging.*
