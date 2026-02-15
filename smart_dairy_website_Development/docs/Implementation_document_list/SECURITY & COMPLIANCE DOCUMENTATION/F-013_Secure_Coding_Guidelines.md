# F-013: Secure Coding Guidelines

---

## Document Information

| Field | Value |
|-------|-------|
| **Document ID** | F-013 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Lead |
| **Owner** | Development Lead |
| **Reviewer** | CTO |
| **Classification** | Internal Use |
| **Status** | Approved |

---

## Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Security Lead | Initial Release |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [OWASP Top 10 2021](#2-owasp-top-10-2021)
3. [Input Validation](#3-input-validation)
4. [Output Encoding](#4-output-encoding)
5. [Authentication & Session Security](#5-authentication--session-security)
6. [Access Control](#6-access-control)
7. [Cryptographic Practices](#7-cryptographic-practices)
8. [Error Handling & Logging](#8-error-handling--logging)
9. [Data Protection](#9-data-protection)
10. [Communication Security](#10-communication-security)
11. [API Security](#11-api-security)
12. [File Handling](#12-file-handling)
13. [Configuration Security](#13-configuration-security)
14. [Database Security](#14-database-security)
15. [Mobile Security](#15-mobile-security)
16. [Code Review Process](#16-code-review-process)
17. [Static Analysis](#17-static-analysis)
18. [Dynamic Testing](#18-dynamic-testing)
19. [Dependency Management](#19-dependency-management)
20. [Appendices](#20-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document establishes the secure coding standards for Smart Dairy Ltd.'s software development lifecycle (SDLC). It provides guidelines, best practices, and actionable recommendations for developing secure applications across all technology stacks used within the organization.

### 1.2 Scope

This document applies to:
- All software development projects at Smart Dairy Ltd.
- Internal and external development teams
- Mobile, web, and backend applications
- Third-party integrations and APIs
- Database and infrastructure configurations

### 1.3 Secure Software Development Lifecycle (SSDLC)

Smart Dairy Ltd. implements a comprehensive SSDLC framework:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     SECURE SOFTWARE DEVELOPMENT LIFECYCLE                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐   │
│  │  REQUIREMENTS │──▶│    DESIGN    │──▶│DEVELOPMENT │──▶│    TEST     │   │
│  │   ANALYSIS   │    │             │    │             │    │             │   │
│  └──────┬──────┘    └──────┬──────┘    └──────┬──────┘    └──────┬──────┘   │
│         │                  │                  │                  │          │
│         ▼                  ▼                  ▼                  ▼          │
│  • Security Req.     • Threat Model      • Secure Coding    • SAST/DAST    │
│  • Compliance Req.   • Architecture      • Code Review      • Pen Testing  │
│  • Risk Assessment   • Security Design   • Static Analysis   • Security QA  │
│                                                                              │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐                      │
│  │  DEPLOYMENT  │◀───│   RELEASE   │◀───│   VERIFY    │                      │
│  └─────────────┘    └─────────────┘    └─────────────┘                      │
│         │                  │                  │                             │
│         ▼                  ▼                  ▼                             │
│  • Secure Config.    • Final Sign-off    • Security Cert.                   │
│  • Secrets Mgmt.     • Change Mgmt.      • Compliance Check                 │
│  • Monitoring        • Rollback Plan     • Documentation                    │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │                    OPERATIONS & MAINTENANCE                          │    │
│  │  • Continuous Monitoring  • Incident Response  • Patch Management   │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 1.4 Technology Stack Overview

Smart Dairy's technology stack includes:

| Layer | Technologies |
|-------|-------------|
| **Backend** | Python (Odoo 19, FastAPI), PostgreSQL |
| **Frontend** | JavaScript/TypeScript (Vue.js/React) |
| **Mobile** | Dart (Flutter) for Android/iOS |
| **Database** | PostgreSQL |
| **Infrastructure** | Docker, Kubernetes, Cloud (AWS/Azure) |

---

## 2. OWASP Top 10 2021

### 2.1 Overview

The OWASP Top 10 represents the most critical security risks to web applications. Smart Dairy developers must understand and mitigate these risks.

### 2.2 A01:2021 - Broken Access Control

**Description:** Access control enforces policy such that users cannot act outside of their intended permissions.

**Common Vulnerabilities:**
- Violation of least privilege
- Bypassing access control checks
- Privilege escalation
- Insecure direct object references (IDOR)

**Mitigation Strategies:**

| Strategy | Implementation |
|----------|---------------|
| Deny by Default | Reject all requests except public resources |
| Principle of Least Privilege | Grant minimum necessary permissions |
| Server-Side Enforcement | Never trust client-side access control |
| Rate Limiting | Prevent automated attacks |

**Python (Odoo) Example:**
```python
# ✅ SECURE: Proper access control in Odoo
from odoo import models, fields, api
from odoo.exceptions import AccessError

class FarmRecord(models.Model):
    _name = 'smart_dairy.farm_record'
    
    @api.model
    def create(self, vals):
        # Check user has create permission
        if not self.env.user.has_group('smart_dairy.group_farm_manager'):
            raise AccessError("You don't have permission to create farm records.")
        return super(FarmRecord, self).create(vals)
    
    def write(self, vals):
        # Ensure users can only modify their own records
        for record in self:
            if record.create_uid != self.env.user and \
               not self.env.user.has_group('smart_dairy.group_farm_admin'):
                raise AccessError("You can only modify your own records.")
        return super(FarmRecord, self).write(vals)
```

**Python (FastAPI) Example:**
```python
# ✅ SECURE: Role-based access control in FastAPI
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from jose import JWTError, jwt

oauth2_scheme = OAuth2PasswordBearer(tokenUrl="token")

async def get_current_user(token: str = Depends(oauth2_scheme)):
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        user_id: str = payload.get("sub")
        if user_id is None:
            raise credentials_exception
    except JWTError:
        raise credentials_exception
    return user_id

def require_role(required_role: str):
    def role_checker(current_user: User = Depends(get_current_user)):
        if required_role not in current_user.roles:
            raise HTTPException(
                status_code=status.HTTP_403_FORBIDDEN,
                detail=f"Role '{required_role}' required"
            )
        return current_user
    return role_checker

@app.get("/api/admin/farm-data")
async def get_farm_data(
    current_user: User = Depends(require_role("farm_admin"))
):
    # Only accessible to farm admins
    return {"data": farm_data}
```

### 2.3 A02:2021 - Cryptographic Failures

**Description:** Failures related to cryptography, including data protection in transit and at rest.

**Common Vulnerabilities:**
- Transmitting data in cleartext
- Weak cryptographic algorithms
- Improper certificate validation
- Hardcoded cryptographic keys

**Mitigation Strategies:**

| Data State | Required Protection |
|-----------|---------------------|
| In Transit | TLS 1.2 or higher |
| At Rest | AES-256 encryption |
| Passwords | Argon2id or bcrypt hashing |
| API Keys | Secure vault storage |

**Python Example:**
```python
# ✅ SECURE: Proper password hashing
from argon2 import PasswordHasher
from cryptography.fernet import Fernet

# Password hashing
ph = PasswordHasher(
    time_cost=3,      # Number of iterations
    memory_cost=65536, # 64 MB
    parallelism=1,
    hash_len=32,
    salt_len=16
)

def hash_password(password: str) -> str:
    return ph.hash(password)

def verify_password(password: str, hash: str) -> bool:
    try:
        ph.verify(hash, password)
        return True
    except Exception:
        return False

# Data encryption
class DataEncryption:
    def __init__(self, key: bytes):
        self.cipher = Fernet(key)
    
    def encrypt_pii(self, data: str) -> bytes:
        """Encrypt PII data"""
        return self.cipher.encrypt(data.encode())
    
    def decrypt_pii(self, token: bytes) -> str:
        """Decrypt PII data"""
        return self.cipher.decrypt(token).decode()
```

**JavaScript/TypeScript Example:**
```typescript
// ✅ SECURE: Environment-based encryption key management
import crypto from 'crypto';

class EncryptionService {
  private algorithm = 'aes-256-gcm';
  private key: Buffer;

  constructor() {
    // Key should be loaded from secure vault/environment
    const keyHex = process.env.ENCRYPTION_KEY;
    if (!keyHex || keyHex.length !== 64) {
      throw new Error('Invalid encryption key configuration');
    }
    this.key = Buffer.from(keyHex, 'hex');
  }

  encrypt(text: string): { encrypted: string; iv: string; authTag: string } {
    const iv = crypto.randomBytes(16);
    const cipher = crypto.createCipheriv(this.algorithm, this.key, iv);
    
    let encrypted = cipher.update(text, 'utf8', 'hex');
    encrypted += cipher.final('hex');
    
    return {
      encrypted,
      iv: iv.toString('hex'),
      authTag: cipher.getAuthTag().toString('hex')
    };
  }

  decrypt(encrypted: string, iv: string, authTag: string): string {
    const decipher = crypto.createDecipheriv(
      this.algorithm,
      this.key,
      Buffer.from(iv, 'hex')
    );
    decipher.setAuthTag(Buffer.from(authTag, 'hex'));
    
    let decrypted = decipher.update(encrypted, 'hex', 'utf8');
    decrypted += decipher.final('utf8');
    
    return decrypted;
  }
}
```

### 2.4 A03:2021 - Injection

**Description:** Injection flaws occur when untrusted data is sent to an interpreter as part of a command or query.

**Types:**
- SQL Injection
- NoSQL Injection
- Command Injection
- LDAP Injection
- Expression Language Injection

**Mitigation Strategies:**

| Technique | Description |
|-----------|-------------|
| Parameterized Queries | Use prepared statements with bound variables |
| ORM Usage | Use ORM frameworks that escape queries |
| Input Validation | Validate and sanitize all user inputs |
| Least Privilege | Database accounts with minimal permissions |

**Python (PostgreSQL) Example:**
```python
# ✅ SECURE: Parameterized queries in Python
import psycopg2
from psycopg2 import sql

# ❌ VULNERABLE - Never do this
def get_user_unsafe(user_id):
    query = f"SELECT * FROM users WHERE id = {user_id}"  # SQL Injection risk!
    cursor.execute(query)

# ✅ SECURE - Use parameterized queries
def get_user_safe(user_id: int):
    query = "SELECT * FROM users WHERE id = %s"
    cursor.execute(query, (user_id,))  # Parameterized
    return cursor.fetchone()

# ✅ SECURE - Using psycopg2.sql for dynamic identifiers
def search_table(table_name: str, column: str, value: str):
    # Sanitize table/column names (not user data)
    safe_table = sql.Identifier(table_name)
    safe_column = sql.Identifier(column)
    
    query = sql.SQL("SELECT * FROM {} WHERE {} = %s").format(
        safe_table, safe_column
    )
    cursor.execute(query, (value,))  # Value is still parameterized
    return cursor.fetchall()

# ✅ SECURE - Odoo ORM (automatically parameterized)
def get_farm_records(self, cow_id):
    # Odoo ORM automatically escapes values
    records = self.env['farm.health.record'].search([
        ('cow_id', '=', cow_id),
        ('record_date', '>=', '2024-01-01')
    ])
    return records
```

**JavaScript/TypeScript Example:**
```typescript
// ✅ SECURE: Using ORM with parameterized queries
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

// ❌ VULNERABLE - Raw query without parameterization
async function getUserUnsafe(userId: string) {
  const query = `SELECT * FROM users WHERE id = ${userId}`;
  return await prisma.$queryRawUnsafe(query);  // DANGEROUS!
}

// ✅ SECURE - Using ORM methods (automatically parameterized)
async function getUserSafe(userId: string) {
  return await prisma.user.findUnique({
    where: { id: userId }
  });
}

// ✅ SECURE - Raw query with parameterization
async function getUserRawSafe(userId: string) {
  return await prisma.$queryRaw`
    SELECT * FROM users WHERE id = ${userId}
  `;  // Prisma automatically parameterizes this
}
```

### 2.5 A04:2021 - Insecure Design

**Description:** Missing or ineffective control design, representing a broad category representing different weaknesses.

**Common Issues:**
- Business logic flaws
- Missing security controls in design
- Insufficient threat modeling
- Insecure workflows

**Mitigation Strategies:**
- Implement threat modeling
- Use secure design patterns
- Establish security requirements
- Implement defense in depth

### 2.6 A05:2021 - Security Misconfiguration

**Description:** Improper configuration of security settings, frameworks, and libraries.

**Common Issues:**
- Default credentials
- Unnecessary features enabled
- Verbose error messages
- Missing security headers

**Security Headers Configuration:**

**Python (FastAPI) Example:**
```python
# ✅ SECURE: Security headers middleware
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from starlette.middleware.base import BaseHTTPMiddleware

class SecurityHeadersMiddleware(BaseHTTPMiddleware):
    async def dispatch(self, request, call_next):
        response = await call_next(request)
        
        # Content Security Policy
        response.headers['Content-Security-Policy'] = (
            "default-src 'self'; "
            "script-src 'self' 'unsafe-inline'; "
            "style-src 'self' 'unsafe-inline'; "
            "img-src 'self' data: https:; "
            "font-src 'self'; "
            "connect-src 'self' https://api.smartdairybd.com; "
            "frame-ancestors 'none'; "
            "base-uri 'self'; "
            "form-action 'self';"
        )
        
        # Security headers
        response.headers['X-Content-Type-Options'] = 'nosniff'
        response.headers['X-Frame-Options'] = 'DENY'
        response.headers['X-XSS-Protection'] = '1; mode=block'
        response.headers['Referrer-Policy'] = 'strict-origin-when-cross-origin'
        response.headers['Permissions-Policy'] = 'geolocation=(), microphone=(), camera=()'
        response.headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains'
        
        return response

app = FastAPI()
app.add_middleware(SecurityHeadersMiddleware)

# CORS - restrict to known origins
app.add_middleware(
    CORSMiddleware,
    allow_origins=["https://smartdairybd.com", "https://app.smartdairybd.com"],
    allow_credentials=True,
    allow_methods=["GET", "POST", "PUT", "DELETE"],
    allow_headers=["*"],
    max_age=3600,
)
```

**Nginx Configuration:**
```nginx
# ✅ SECURE: Nginx security headers
server {
    listen 443 ssl http2;
    server_name api.smartdairybd.com;
    
    # SSL Configuration
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers 'ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256';
    ssl_prefer_server_ciphers off;
    
    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Content-Security-Policy "default-src 'self'; script-src 'self'; object-src 'none';" always;
    
    # Hide server version
    server_tokens off;
    
    # Rate limiting
    limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
    limit_req zone=api burst=20 nodelay;
}
```

### 2.7 A06:2021 - Vulnerable and Outdated Components

**Description:** Using components with known vulnerabilities or components that are no longer maintained.

**Mitigation Strategies:**
- Maintain inventory of all components
- Subscribe to security bulletins
- Use dependency scanning tools
- Establish update policies

### 2.8 A07:2021 - Identification and Authentication Failures

**Description:** Failures related to user identity, authentication, and session management.

**Common Issues:**
- Weak password policies
- Brute force vulnerability
- Weak session tokens
- Missing MFA

**Implementation:**

**Python (FastAPI) Example:**
```python
# ✅ SECURE: Multi-factor authentication
from fastapi import APIRouter, Depends, HTTPException, status
from pyotp import TOTP
import qrcode
import io
import base64

class MFAService:
    def generate_secret(self) -> str:
        """Generate new MFA secret for user"""
        return pyotp.random_base32()
    
    def generate_qr_code(self, user_email: str, secret: str) -> str:
        """Generate QR code for MFA setup"""
        totp = TOTP(secret)
        uri = totp.provisioning_uri(
            name=user_email,
            issuer_name="Smart Dairy Ltd."
        )
        
        qr = qrcode.make(uri)
        buffered = io.BytesIO()
        qr.save(buffered, format="PNG")
        return base64.b64encode(buffered.getvalue()).decode()
    
    def verify_token(self, secret: str, token: str) -> bool:
        """Verify MFA token"""
        totp = TOTP(secret)
        return totp.verify(token, valid_window=1)

# Login with MFA
@app.post("/auth/login")
async def login(credentials: LoginRequest):
    user = authenticate_user(credentials.email, credentials.password)
    if not user:
        raise HTTPException(status_code=401, detail="Invalid credentials")
    
    if user.mfa_enabled:
        # Return temporary token for MFA verification
        temp_token = create_temp_token(user.id)
        return {
            "requires_mfa": True,
            "temp_token": temp_token
        }
    
    return create_access_token(user)

@app.post("/auth/verify-mfa")
async def verify_mfa(verify_request: MFAVerifyRequest):
    # Verify temp token
    user_id = verify_temp_token(verify_request.temp_token)
    if not user_id:
        raise HTTPException(status_code=401, detail="Invalid or expired token")
    
    # Verify MFA code
    user = await get_user(user_id)
    mfa_service = MFAService()
    
    if not mfa_service.verify_token(user.mfa_secret, verify_request.code):
        await log_security_event("MFA_FAILURE", user_id)
        raise HTTPException(status_code=401, detail="Invalid MFA code")
    
    return create_access_token(user)
```

### 2.9 A08:2021 - Software and Data Integrity Failures

**Description:** Making assumptions about software updates, critical data, and CI/CD pipelines without verifying integrity.

**Mitigation:**
- Digital signatures for updates
- Integrity verification for dependencies
- Secure CI/CD pipelines
- Code signing

### 2.10 A09:2021 - Security Logging and Monitoring Failures

**Description:** Insufficient logging, detection, monitoring, and incident response.

**Implementation:**

**Python Example:**
```python
# ✅ SECURE: Comprehensive security logging
import logging
import json
from datetime import datetime
from functools import wraps

class SecurityLogger:
    def __init__(self):
        self.logger = logging.getLogger('security')
        self.logger.setLevel(logging.INFO)
        
        # Structured logging handler
        handler = logging.StreamHandler()
        formatter = logging.Formatter(
            '%(asctime)s - %(name)s - %(levelname)s - %(message)s'
        )
        handler.setFormatter(formatter)
        self.logger.addHandler(handler)
    
    def log_auth_event(self, event_type: str, user_id: str, success: bool, 
                       ip_address: str, user_agent: str, details: dict = None):
        """Log authentication events"""
        log_entry = {
            "timestamp": datetime.utcnow().isoformat(),
            "event_type": event_type,
            "user_id": user_id,
            "success": success,
            "ip_address": ip_address,
            "user_agent": user_agent,
            "details": details or {}
        }
        
        level = logging.INFO if success else logging.WARNING
        self.logger.log(level, json.dumps(log_entry))
    
    def log_data_access(self, user_id: str, resource: str, action: str, 
                        record_id: str = None):
        """Log sensitive data access"""
        log_entry = {
            "timestamp": datetime.utcnow().isoformat(),
            "event_type": "DATA_ACCESS",
            "user_id": user_id,
            "resource": resource,
            "action": action,
            "record_id": record_id
        }
        self.logger.info(json.dumps(log_entry))

# Decorator for audit logging
def audit_log(action: str, resource: str):
    def decorator(func):
        @wraps(func)
        async def wrapper(*args, **kwargs):
            # Get current user from context
            current_user = kwargs.get('current_user')
            
            security_logger = SecurityLogger()
            
            try:
                result = await func(*args, **kwargs)
                
                # Log successful access
                security_logger.log_data_access(
                    user_id=current_user.id if current_user else "anonymous",
                    resource=resource,
                    action=action,
                    record_id=kwargs.get('id')
                )
                
                return result
            except Exception as e:
                # Log failed access attempt
                security_logger.logger.warning(json.dumps({
                    "event_type": "ACCESS_DENIED",
                    "user_id": current_user.id if current_user else "anonymous",
                    "resource": resource,
                    "action": action,
                    "error": str(e)
                }))
                raise
        return wrapper
    return decorator
```

### 2.11 A10:2021 - Server-Side Request Forgery (SSRF)

**Description:** Occurs when a web application fetches a remote resource without validating the user-supplied URL.

**Mitigation:**

```python
# ✅ SECURE: SSRF prevention
import ipaddress
import re
from urllib.parse import urlparse

class SSRFProtection:
    # Deny lists for private/reserved IPs
    DENIED_NETWORKS = [
        ipaddress.ip_network('127.0.0.0/8'),      # Loopback
        ipaddress.ip_network('10.0.0.0/8'),       # Private
        ipaddress.ip_network('172.16.0.0/12'),    # Private
        ipaddress.ip_network('192.168.0.0/16'),   # Private
        ipaddress.ip_network('169.254.0.0/16'),   # Link-local
        ipaddress.ip_network('0.0.0.0/8'),        # Current network
        ipaddress.ip_network('::1/128'),          # IPv6 loopback
        ipaddress.ip_network('fc00::/7'),         # IPv6 private
        ipaddress.ip_network('fe80::/10'),        # IPv6 link-local
    ]
    
    DENIED_PORTS = [22, 23, 25, 53, 110, 143, 3306, 3389, 5432, 6379, 27017]
    
    @classmethod
    def is_safe_url(cls, url: str) -> bool:
        """Validate URL is safe from SSRF"""
        try:
            parsed = urlparse(url)
            
            # Only allow http/https
            if parsed.scheme not in ('http', 'https'):
                return False
            
            # Check for private IP
            hostname = parsed.hostname
            if not hostname:
                return False
            
            # Check if hostname is an IP address
            try:
                ip = ipaddress.ip_address(hostname)
                for network in cls.DENIED_NETWORKS:
                    if ip in network:
                        return False
            except ValueError:
                # Not an IP, check for localhost variations
                if re.match(r'^localhost\.?$', hostname, re.I):
                    return False
            
            # Check port
            port = parsed.port
            if port and port in cls.DENIED_PORTS:
                return False
            
            return True
            
        except Exception:
            return False

# Usage
import requests

def fetch_external_data(url: str):
    if not SSRFProtection.is_safe_url(url):
        raise ValueError("URL not allowed for security reasons")
    
    # Use timeout and redirects limit
    response = requests.get(
        url, 
        timeout=10,
        allow_redirects=False,  # Prevent redirect-based SSRF
        headers={'User-Agent': 'SmartDairy-API/1.0'}
    )
    return response.json()
```

---

## 3. Input Validation

### 3.1 Principles

All user input must be validated before processing. Implement defense in depth with validation at multiple layers.

### 3.2 Validation Layers

```
┌─────────────────────────────────────────────────────────────────┐
│                     INPUT VALIDATION LAYERS                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Layer 1: Client-Side        Basic format validation            │
│  (JavaScript)                (UX only, NOT security)            │
│                                                                  │
│  Layer 2: API Gateway        Schema validation, rate limiting   │
│                              Request size limits                │
│                                                                  │
│  Layer 3: Application        Business logic validation          │
│                              Whitelist validation               │
│                                                                  │
│  Layer 4: Database           Constraint enforcement             │
│                              Type checking                      │
└─────────────────────────────────────────────────────────────────┘
```

### 3.3 Whitelist Validation

**Python Example:**
```python
# ✅ SECURE: Whitelist validation
from enum import Enum
import re
from pydantic import BaseModel, validator, Field

class ProductCategory(str, Enum):
    MILK = "milk"
    YOGURT = "yogurt"
    CHEESE = "cheese"
    BUTTER = "butter"
    BEEF = "beef"

class ProductCreate(BaseModel):
    name: str = Field(..., min_length=1, max_length=100)
    category: ProductCategory
    price: float = Field(..., gt=0)
    description: str = Field(default="", max_length=1000)
    
    @validator('name')
    def validate_name(cls, v):
        # Allow only alphanumeric, spaces, and common punctuation
        if not re.match(r'^[\w\s\-\.]+$', v):
            raise ValueError('Name contains invalid characters')
        return v.strip()
    
    @validator('description')
    def sanitize_description(cls, v):
        # Remove potential HTML/script tags
        v = re.sub(r'<script.*?>.*?</script>', '', v, flags=re.DOTALL | re.I)
        v = re.sub(r'<.*?javascript:.*?>', '', v, flags=re.I)
        return v

# Usage with FastAPI
@app.post("/api/products")
async def create_product(product: ProductCreate):
    # product is already validated by Pydantic
    return await save_product(product)
```

**JavaScript/TypeScript Example:**
```typescript
// ✅ SECURE: Input validation with Zod
import { z } from 'zod';

// Define strict schemas
const ProductSchema = z.object({
  name: z.string()
    .min(1)
    .max(100)
    .regex(/^[\w\s\-\.]+$/, 'Invalid characters in name'),
  category: z.enum(['milk', 'yogurt', 'cheese', 'butter', 'beef']),
  price: z.number().positive(),
  description: z.string().max(1000).optional(),
  quantity: z.number().int().min(0).max(10000)
});

type ProductInput = z.infer<typeof ProductSchema>;

// Validate function
function validateProduct(input: unknown): ProductInput {
  return ProductSchema.parse(input);  // Throws on invalid
}

// Sanitization helpers
const Sanitizers = {
  // Remove HTML tags
  stripHtml(input: string): string {
    return input.replace(/<[^>]*>/g, '');
  },
  
  // Normalize whitespace
  normalizeWhitespace(input: string): string {
    return input.trim().replace(/\s+/g, ' ');
  },
  
  // Escape special regex characters
  escapeRegex(input: string): string {
    return input.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }
};
```

### 3.4 Date and Time Validation

```python
# ✅ SECURE: Date validation for farm records
from datetime import datetime, timedelta
from pydantic import validator

class MilkProductionRecord(BaseModel):
    cow_id: str
    production_date: datetime
    quantity_liters: float
    
    @validator('production_date')
    def validate_date(cls, v):
        now = datetime.utcnow()
        
        # No future dates
        if v > now:
            raise ValueError('Production date cannot be in the future')
        
        # No dates older than 30 days (business rule)
        if v < now - timedelta(days=30):
            raise ValueError('Production date cannot be older than 30 days')
        
        return v
    
    @validator('quantity_liters')
    def validate_quantity(cls, v):
        # Realistic milk production range
        if v < 0 or v > 50:  # liters per day
            raise ValueError('Quantity must be between 0 and 50 liters')
        return round(v, 2)  # Round to 2 decimal places
```

### 3.5 File Path Validation

```python
# ✅ SECURE: File path validation
import os
from pathlib import Path

class FileValidator:
    ALLOWED_EXTENSIONS = {'.pdf', '.jpg', '.jpeg', '.png', '.csv'}
    MAX_FILE_SIZE = 10 * 1024 * 1024  # 10MB
    
    @staticmethod
    def validate_filename(filename: str) -> bool:
        """Validate filename is safe"""
        # Check for path traversal attempts
        if '..' in filename or '/' in filename or '\\' in filename:
            return False
        
        # Check extension
        ext = Path(filename).suffix.lower()
        return ext in FileValidator.ALLOWED_EXTENSIONS
    
    @staticmethod
    def sanitize_filename(filename: str) -> str:
        """Sanitize filename for safe storage"""
        # Remove any path components
        filename = os.path.basename(filename)
        
        # Keep only safe characters
        import re
        filename = re.sub(r'[^\w\-\.]', '_', filename)
        
        # Ensure reasonable length
        if len(filename) > 100:
            name, ext = os.path.splitext(filename)
            filename = name[:96] + ext
        
        return filename
```

---

## 4. Output Encoding

### 4.1 Cross-Site Scripting (XSS) Prevention

**Context-Aware Encoding:**

| Context | Encoding Method | Example |
|---------|----------------|---------|
| HTML Body | HTML Entity Encoding | `<` → `&lt;` |
| HTML Attribute | Attribute Encoding | `"` → `&quot;` |
| JavaScript | JavaScript Encoding | `'` → `\x27` |
| CSS | CSS Encoding | `<` → `\3c` |
| URL | URL Encoding | ` ` → `%20` |

**Python Example:**
```python
# ✅ SECURE: Output encoding
import html
import urllib.parse
import json

class OutputEncoder:
    @staticmethod
    def encode_html(text: str) -> str:
        """Encode for HTML body context"""
        return html.escape(text, quote=True)
    
    @staticmethod
    def encode_html_attribute(text: str) -> str:
        """Encode for HTML attribute context"""
        # More restrictive for attributes
        encoded = html.escape(text, quote=True)
        # Additional protection for event handlers
        encoded = encoded.replace('javascript:', '')
        encoded = encoded.replace('data:', '')
        return encoded
    
    @staticmethod
    def encode_javascript(text: str) -> str:
        """Encode for JavaScript context"""
        # Use JSON encoding which safely handles all JS contexts
        return json.dumps(text)
    
    @staticmethod
    def encode_url(text: str) -> str:
        """Encode for URL context"""
        return urllib.parse.quote(text, safe='')

# Template usage (Jinja2)
"""
{{ user_input | e }}  # HTML escape
"""
```

**JavaScript/TypeScript Example:**
```typescript
// ✅ SECURE: Output encoding utilities
class OutputEncoder {
  // HTML entity encoding
  static htmlEncode(text: string): string {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
  
  // For setting text content (safe)
  static setTextContent(element: HTMLElement, text: string): void {
    element.textContent = text;  // Automatically escaped
  }
  
  // For setting HTML (use with caution)
  static setInnerHTML(element: HTMLElement, html: string): void {
    // Use DOMPurify for sanitization
    const clean = DOMPurify.sanitize(html, {
      ALLOWED_TAGS: ['b', 'i', 'em', 'strong', 'p', 'br'],
      ALLOWED_ATTR: []
    });
    element.innerHTML = clean;
  }
  
  // JavaScript context encoding
  static jsEncode(text: string): string {
    return JSON.stringify(text);
  }
}

// Vue.js Safe Rendering
// Use {{ }} for text interpolation (auto-escaped)
// Use v-html only with sanitized content
<template>
  <div>
    <!-- Safe - auto-escaped -->
    <p>{{ userInput }}</p>
    
    <!-- Dangerous - only use with sanitized content -->
    <div v-html="sanitizedHtml"></div>
  </div>
</template>
```

### 4.2 Content Security Policy (CSP)

```python
# ✅ SECURE: CSP Nonce generation
import secrets

class CSPNonce:
    @staticmethod
    def generate() -> str:
        """Generate CSP nonce"""
        return secrets.token_urlsafe(16)

# HTML template with nonce
"""
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Security-Policy" 
          content="script-src 'self' 'nonce-{nonce}'; 
                   style-src 'self' 'nonce-{nonce}';">
    <style nonce="{nonce}">
        /* Inline styles */
    </style>
</head>
<body>
    <script nonce="{nonce}">
        // Inline scripts
    </script>
</body>
</html>
"""
```

---

## 5. Authentication & Session Security

### 5.1 Password Policy

**Requirements:**
- Minimum 12 characters
- Mix of uppercase, lowercase, numbers, special characters
- No common passwords (check against breach databases)
- Password history (prevent reuse of last 5 passwords)
- Maximum age: 90 days for privileged accounts

```python
# ✅ SECURE: Password validation
import re
import requests
from zxcvbn import zxcvbn

class PasswordValidator:
    MIN_LENGTH = 12
    MAX_LENGTH = 128
    
    # Common weak passwords (expand as needed)
    COMMON_PASSWORDS = {
        'password', '123456', 'qwerty', 'admin',
        'smartdairy', 'dairy123', 'farm2024'
    }
    
    @classmethod
    def validate(cls, password: str, user_email: str) -> tuple[bool, list[str]]:
        errors = []
        
        # Length check
        if len(password) < cls.MIN_LENGTH:
            errors.append(f"Password must be at least {cls.MIN_LENGTH} characters")
        if len(password) > cls.MAX_LENGTH:
            errors.append(f"Password must not exceed {cls.MAX_LENGTH} characters")
        
        # Complexity requirements
        if not re.search(r'[A-Z]', password):
            errors.append("Password must contain at least one uppercase letter")
        if not re.search(r'[a-z]', password):
            errors.append("Password must contain at least one lowercase letter")
        if not re.search(r'\d', password):
            errors.append("Password must contain at least one number")
        if not re.search(r'[!@#$%^&*(),.?":{}|<>]', password):
            errors.append("Password must contain at least one special character")
        
        # Common password check
        if password.lower() in cls.COMMON_PASSWORDS:
            errors.append("Password is too common")
        
        # Check against user information
        local_part = user_email.split('@')[0].lower()
        if local_part in password.lower():
            errors.append("Password cannot contain your email address")
        
        # Strength estimation
        strength = zxcvbn(password, user_inputs=[user_email])
        if strength['score'] < 3:
            errors.append("Password is too weak. Try using a longer passphrase.")
        
        return len(errors) == 0, errors
```

### 5.2 Session Management

```python
# ✅ SECURE: Session management
import secrets
import redis
from datetime import datetime, timedelta
from typing import Optional

class SessionManager:
    SESSION_TIMEOUT = 1800  # 30 minutes
    ABSOLUTE_TIMEOUT = 28800  # 8 hours
    
    def __init__(self):
        self.redis = redis.Redis(host='localhost', port=6379, db=0)
    
    def create_session(self, user_id: str, ip_address: str, 
                       user_agent: str) -> str:
        """Create new secure session"""
        session_id = secrets.token_urlsafe(32)
        
        session_data = {
            'user_id': user_id,
            'created_at': datetime.utcnow().isoformat(),
            'last_activity': datetime.utcnow().isoformat(),
            'ip_address': ip_address,
            'user_agent_hash': hash(user_agent),
        }
        
        # Store in Redis with expiration
        self.redis.setex(
            f"session:{session_id}",
            self.ABSOLUTE_TIMEOUT,
            json.dumps(session_data)
        )
        
        return session_id
    
    def validate_session(self, session_id: str, ip_address: str,
                         user_agent: str) -> Optional[dict]:
        """Validate and refresh session"""
        data = self.redis.get(f"session:{session_id}")
        if not data:
            return None
        
        session = json.loads(data)
        
        # Check IP binding (optional, for high-security)
        # if session['ip_address'] != ip_address:
        #     self.destroy_session(session_id)
        #     return None
        
        # Check idle timeout
        last_activity = datetime.fromisoformat(session['last_activity'])
        if datetime.utcnow() - last_activity > timedelta(seconds=self.SESSION_TIMEOUT):
            self.destroy_session(session_id)
            return None
        
        # Update last activity
        session['last_activity'] = datetime.utcnow().isoformat()
        ttl = self.redis.ttl(f"session:{session_id}")
        self.redis.setex(
            f"session:{session_id}",
            ttl,
            json.dumps(session)
        )
        
        return session
    
    def destroy_session(self, session_id: str):
        """Destroy session (logout)"""
        self.redis.delete(f"session:{session_id}")
    
    def destroy_all_user_sessions(self, user_id: str):
        """Destroy all sessions for user (password change, security event)"""
        # Implementation depends on indexing strategy
        pass
```

### 5.3 JWT Token Security

```python
# ✅ SECURE: JWT implementation
from datetime import datetime, timedelta
from jose import JWTError, jwt
from passlib.context import CryptContext

class JWTManager:
    def __init__(self, secret_key: str, algorithm: str = "HS256"):
        self.secret_key = secret_key
        self.algorithm = algorithm
    
    def create_access_token(self, user_id: str, roles: list[str],
                           expires_delta: timedelta = None) -> str:
        """Create access token with short expiry"""
        if expires_delta:
            expire = datetime.utcnow() + expires_delta
        else:
            expire = datetime.utcnow() + timedelta(minutes=15)
        
        payload = {
            "sub": user_id,
            "roles": roles,
            "type": "access",
            "iat": datetime.utcnow(),
            "exp": expire,
            "jti": secrets.token_urlsafe(16)  # Unique token ID
        }
        
        return jwt.encode(payload, self.secret_key, algorithm=self.algorithm)
    
    def create_refresh_token(self, user_id: str) -> str:
        """Create refresh token with longer expiry"""
        expire = datetime.utcnow() + timedelta(days=7)
        
        payload = {
            "sub": user_id,
            "type": "refresh",
            "exp": expire,
            "jti": secrets.token_urlsafe(16)
        }
        
        return jwt.encode(payload, self.secret_key, algorithm=self.algorithm)
    
    def decode_token(self, token: str, expected_type: str = None) -> dict:
        """Decode and validate token"""
        try:
            payload = jwt.decode(
                token, 
                self.secret_key, 
                algorithms=[self.algorithm]
            )
            
            # Verify token type
            if expected_type and payload.get("type") != expected_type:
                raise JWTError("Invalid token type")
            
            return payload
            
        except jwt.ExpiredSignatureError:
            raise JWTError("Token has expired")
        except JWTError:
            raise JWTError("Invalid token")
```

---

## 6. Access Control

### 6.1 Role-Based Access Control (RBAC)

Smart Dairy implements a hierarchical RBAC system:

```
┌─────────────────────────────────────────────────────────────────┐
│                  SMART DAIRY RBAC HIERARCHY                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  System Administrator                                            │
│  └── Full system access                                          │
│                                                                  │
│  Farm Manager                                                    │
│  ├── Herd Management                                             │
│  ├── Health Records                                              │
│  ├── Production Reports                                          │
│  └── Staff Management                                            │
│                                                                  │
│  Veterinarian                                                    │
│  ├── Health Records (Read/Write)                                 │
│  ├── Treatment Plans                                             │
│  └── Medical Reports                                             │
│                                                                  │
│  Farm Worker                                                     │
│  ├── Daily Production Entry                                      │
│  ├── Basic Health Observations                                   │
│  └── Task Management                                             │
│                                                                  │
│  Sales Manager                                                   │
│  ├── B2B Portal Management                                       │
│  ├── Order Management                                            │
│  └── Customer Management                                         │
│                                                                  │
│  B2B Customer                                                    │
│  ├── View Own Orders                                             │
│  ├── Place Orders                                                │
│  └── View Invoices                                               │
│                                                                  │
│  B2C Customer                                                    │
│  ├── Shop Products                                               │
│  ├── Manage Orders                                               │
│  └── Subscription Management                                     │
└─────────────────────────────────────────────────────────────────┘
```

**Implementation:**

```python
# ✅ SECURE: RBAC implementation
from enum import Enum, auto
from functools import wraps
from fastapi import HTTPException, status

class Permission(Enum):
    # Farm Management
    HERD_VIEW = auto()
    HERD_CREATE = auto()
    HERD_UPDATE = auto()
    HERD_DELETE = auto()
    
    HEALTH_VIEW = auto()
    HEALTH_CREATE = auto()
    HEALTH_UPDATE = auto()
    
    PRODUCTION_VIEW = auto()
    PRODUCTION_CREATE = auto()
    
    # Sales
    ORDER_VIEW_ALL = auto()
    ORDER_VIEW_OWN = auto()
    ORDER_CREATE = auto()
    ORDER_UPDATE = auto()
    
    # Users
    USER_MANAGE = auto()
    ROLE_MANAGE = auto()

# Role to permission mapping
ROLE_PERMISSIONS = {
    "system_admin": list(Permission),
    "farm_manager": [
        Permission.HERD_VIEW, Permission.HERD_CREATE, 
        Permission.HERD_UPDATE, Permission.HEALTH_VIEW,
        Permission.HEALTH_CREATE, Permission.HEALTH_UPDATE,
        Permission.PRODUCTION_VIEW, Permission.PRODUCTION_CREATE,
        Permission.USER_MANAGE
    ],
    "veterinarian": [
        Permission.HEALTH_VIEW, Permission.HEALTH_CREATE,
        Permission.HEALTH_UPDATE, Permission.HERD_VIEW
    ],
    "farm_worker": [
        Permission.PRODUCTION_CREATE, Permission.HERD_VIEW,
        Permission.HEALTH_VIEW
    ],
    "sales_manager": [
        Permission.ORDER_VIEW_ALL, Permission.ORDER_UPDATE
    ],
    "b2b_customer": [
        Permission.ORDER_VIEW_OWN, Permission.ORDER_CREATE
    ],
    "b2c_customer": [
        Permission.ORDER_VIEW_OWN, Permission.ORDER_CREATE
    ]
}

def require_permissions(*permissions: Permission):
    """Decorator to require specific permissions"""
    def decorator(func):
        @wraps(func)
        async def wrapper(*args, **kwargs):
            current_user = kwargs.get('current_user')
            if not current_user:
                raise HTTPException(
                    status_code=status.HTTP_401_UNAUTHORIZED,
                    detail="Authentication required"
                )
            
            user_permissions = set()
            for role in current_user.roles:
                user_permissions.update(ROLE_PERMISSIONS.get(role, []))
            
            if not all(p in user_permissions for p in permissions):
                raise HTTPException(
                    status_code=status.HTTP_403_FORBIDDEN,
                    detail="Insufficient permissions"
                )
            
            return await func(*args, **kwargs)
        return wrapper
    return decorator

# Usage
@app.post("/api/farm/cattle")
@require_permissions(Permission.HERD_CREATE)
async def create_cattle(
    data: CattleCreate,
    current_user: User = Depends(get_current_user)
):
    return await create_cattle_record(data)
```

### 6.2 Object-Level Permissions

```python
# ✅ SECURE: Object-level access control
class ObjectLevelPermission:
    """Check if user can access specific object"""
    
    @staticmethod
    def can_view_order(user: User, order: Order) -> bool:
        """Check if user can view specific order"""
        if Permission.ORDER_VIEW_ALL in user.permissions:
            return True
        if Permission.ORDER_VIEW_OWN in user.permissions:
            return order.customer_id == user.id
        return False
    
    @staticmethod
    def can_modify_order(user: User, order: Order) -> bool:
        """Check if user can modify order"""
        # Sales managers can modify any order
        if Permission.ORDER_UPDATE in user.permissions:
            return True
        
        # Customers can only modify their own pending orders
        if order.customer_id == user.id and order.status == "pending":
            return True
        
        return False

# Middleware to check object permissions
async def get_order_if_allowed(
    order_id: str,
    current_user: User = Depends(get_current_user)
) -> Order:
    order = await get_order(order_id)
    if not order:
        raise HTTPException(status_code=404, detail="Order not found")
    
    if not ObjectLevelPermission.can_view_order(current_user, order):
        # Return 404 to prevent ID enumeration
        raise HTTPException(status_code=404, detail="Order not found")
    
    return order
```

---

## 7. Cryptographic Practices

### 7.1 Encryption Standards

| Use Case | Algorithm | Key Size | Mode |
|----------|-----------|----------|------|
| Data at Rest | AES | 256 bits | GCM |
| Data in Transit | TLS | 2048+ bits | 1.2+ |
| Password Hashing | Argon2id | - | - |
| Key Derivation | PBKDF2/Argon2 | - | - |
| Digital Signatures | ECDSA/RSA | 256/2048 bits | - |

### 7.2 Secure Key Management

```python
# ✅ SECURE: Key management with AWS KMS
import boto3
from cryptography.fernet import Fernet
import base64

class KeyManager:
    def __init__(self):
        self.kms_client = boto3.client('kms', region_name='ap-south-1')
        self.key_id = "arn:aws:kms:ap-south-1:ACCOUNT:key/KEY-ID"
    
    def generate_data_key(self) -> dict:
        """Generate data key encrypted with KMS"""
        response = self.kms_client.generate_data_key(
            KeyId=self.key_id,
            KeySpec='AES_256'
        )
        return {
            'plaintext': response['Plaintext'],  # Use for encryption, then discard
            'encrypted': response['CiphertextBlob']  # Store this
        }
    
    def decrypt_data_key(self, encrypted_key: bytes) -> bytes:
        """Decrypt data key using KMS"""
        response = self.kms_client.decrypt(
            CiphertextBlob=encrypted_key,
            KeyId=self.key_id
        )
        return response['Plaintext']
    
    def encrypt_pii(self, data: str, encrypted_key: bytes) -> dict:
        """Encrypt PII using envelope encryption"""
        # Decrypt data key
        data_key = self.decrypt_data_key(encrypted_key)
        
        # Encrypt data
        f = Fernet(base64.urlsafe_b64encode(data_key))
        encrypted_data = f.encrypt(data.encode())
        
        # Clear plaintext key from memory
        import ctypes
        ctypes.memset(id(data_key) + 20, 0, len(data_key))
        
        return {
            'encrypted_data': encrypted_data,
            'encrypted_key': encrypted_key  # Store with encrypted data
        }
```

### 7.3 Password Hashing

```python
# ✅ SECURE: Argon2 password hashing
from argon2 import PasswordHasher, Type
from argon2.exceptions import VerifyMismatchError

class PasswordHasher:
    def __init__(self):
        self.ph = PasswordHasher(
            time_cost=3,           # Number of iterations
            memory_cost=65536,     # 64 MB
            parallelism=4,         # Parallel threads
            hash_len=32,           # Hash output length
            salt_len=16,           # Salt length
            type=Type.ID           # Argon2id (resistant to side-channel and GPU attacks)
        )
    
    def hash_password(self, password: str) -> str:
        """Hash a password"""
        return self.ph.hash(password)
    
    def verify_password(self, password: str, hash_string: str) -> bool:
        """Verify a password against hash"""
        try:
            self.ph.verify(hash_string, password)
            
            # Check if rehash needed (parameters upgraded)
            if self.ph.check_needs_rehash(hash_string):
                # Schedule rehash in background
                pass
            
            return True
        except VerifyMismatchError:
            return False
```

---

## 8. Error Handling & Logging

### 8.1 Secure Error Handling

**Principles:**
1. Never expose sensitive information in error messages
2. Log detailed errors internally
3. Return generic messages to users
4. Use unique error IDs for tracking

```python
# ✅ SECURE: Error handling
import uuid
import traceback
from fastapi import Request
from fastapi.responses import JSONResponse

class SecureErrorHandler:
    def __init__(self, logger: SecurityLogger):
        self.logger = logger
    
    async def handle_exception(self, request: Request, exc: Exception) -> JSONResponse:
        """Handle exceptions securely"""
        error_id = str(uuid.uuid4())
        
        # Log full error details internally
        self.logger.logger.error({
            "error_id": error_id,
            "error_type": type(exc).__name__,
            "error_message": str(exc),
            "traceback": traceback.format_exc(),
            "path": request.url.path,
            "method": request.method,
            "client_ip": request.client.host,
            "user_agent": request.headers.get("user-agent"),
            "user_id": getattr(request.state, 'user_id', None)
        })
        
        # Return safe error to client
        if isinstance(exc, HTTPException):
            return JSONResponse(
                status_code=exc.status_code,
                content={
                    "error": {
                        "code": exc.status_code,
                        "message": exc.detail,
                        "error_id": error_id
                    }
                }
            )
        
        # Generic error for unexpected exceptions
        return JSONResponse(
            status_code=500,
            content={
                "error": {
                    "code": 500,
                    "message": "An internal error occurred. Please try again later.",
                    "error_id": error_id
                }
            }
        )

# Exception classes
class SecurityException(Exception):
    """Base security exception"""
    pass

class AuthenticationException(SecurityException):
    """Authentication failure"""
    pass

class AuthorizationException(SecurityException):
    """Authorization failure"""
    pass

class ValidationException(SecurityException):
    """Input validation failure"""
    pass
```

### 8.2 Audit Logging

```python
# ✅ SECURE: Comprehensive audit logging
class AuditLogger:
    """Audit logging for compliance and security monitoring"""
    
    def __init__(self, logger: logging.Logger):
        self.logger = logger
    
    def log_authentication(self, event: str, user_id: str, success: bool,
                           ip_address: str, user_agent: str,
                           failure_reason: str = None):
        """Log authentication events"""
        self.logger.info({
            "event_type": "AUTHENTICATION",
            "event": event,  # LOGIN, LOGOUT, MFA, PASSWORD_CHANGE
            "user_id": user_id,
            "success": success,
            "ip_address": ip_address,
            "user_agent": user_agent,
            "timestamp": datetime.utcnow().isoformat(),
            "failure_reason": failure_reason
        })
    
    def log_authorization(self, user_id: str, resource: str, action: str,
                          result: str, reason: str = None):
        """Log authorization decisions"""
        self.logger.info({
            "event_type": "AUTHORIZATION",
            "user_id": user_id,
            "resource": resource,
            "action": action,
            "result": result,  # ALLOW, DENY
            "reason": reason,
            "timestamp": datetime.utcnow().isoformat()
        })
    
    def log_data_modification(self, user_id: str, table: str, record_id: str,
                              action: str, old_values: dict = None,
                              new_values: dict = None):
        """Log data modifications (PII/sensitive data)"""
        # Don't log actual values for sensitive fields
        sensitive_fields = {'password', 'ssn', 'credit_card', 'phone'}
        
        def mask_sensitive(data: dict) -> dict:
            if not data:
                return data
            return {
                k: "***MASKED***" if k in sensitive_fields else v
                for k, v in data.items()
            }
        
        self.logger.info({
            "event_type": "DATA_MODIFICATION",
            "user_id": user_id,
            "table": table,
            "record_id": record_id,
            "action": action,
            "old_values": mask_sensitive(old_values),
            "new_values": mask_sensitive(new_values),
            "timestamp": datetime.utcnow().isoformat()
        })
    
    def log_security_event(self, event_type: str, severity: str,
                           description: str, user_id: str = None,
                           ip_address: str = None):
        """Log security events (suspicious activity, anomalies)"""
        log_entry = {
            "event_type": "SECURITY_EVENT",
            "security_event": event_type,
            "severity": severity,
            "description": description,
            "user_id": user_id,
            "ip_address": ip_address,
            "timestamp": datetime.utcnow().isoformat()
        }
        
        if severity in ('HIGH', 'CRITICAL'):
            self.logger.critical(log_entry)
            # Trigger alert to security team
            self._send_security_alert(log_entry)
        else:
            self.logger.warning(log_entry)
```

---

## 9. Data Protection

### 9.1 PII Handling

**Data Classification:**

| Classification | Examples | Handling |
|----------------|----------|----------|
| Public | Product info, farm location | No special handling |
| Internal | Employee names, roles | Access control |
| Confidential | Customer orders, sales data | Encryption at rest, access logs |
| Restricted | Customer PII, payment data | Encryption + strict access control |

```python
# ✅ SECURE: PII handling
from enum import Enum
from typing import Optional

class DataClassification(Enum):
    PUBLIC = "public"
    INTERNAL = "internal"
    CONFIDENTIAL = "confidential"
    RESTRICTED = "restricted"

# Field-level classification
PII_FIELDS = {
    'customer_name': DataClassification.RESTRICTED,
    'email': DataClassification.RESTRICTED,
    'phone': DataClassification.RESTRICTED,
    'address': DataClassification.RESTRICTED,
    'order_amount': DataClassification.CONFIDENTIAL,
    'product_name': DataClassification.PUBLIC,
}

class PIIHandler:
    """Handle PII data securely"""
    
    @staticmethod
    def mask_pii(data: dict, user_clearance: DataClassification) -> dict:
        """Mask PII based on user clearance level"""
        result = {}
        for field, value in data.items():
            classification = PII_FIELDS.get(field, DataClassification.PUBLIC)
            
            if classification == DataClassification.RESTRICTED:
                if user_clearance != DataClassification.RESTRICTED:
                    result[field] = "***REDACTED***"
                else:
                    result[field] = value
            elif classification == DataClassification.CONFIDENTIAL:
                if user_clearance in (DataClassification.RESTRICTED, 
                                      DataClassification.CONFIDENTIAL):
                    result[field] = value
                else:
                    result[field] = "***REDACTED***"
            else:
                result[field] = value
        
        return result
    
    @staticmethod
    def encrypt_restricted_fields(data: dict, encryption_service) -> dict:
        """Encrypt restricted fields before storage"""
        result = data.copy()
        for field, classification in PII_FIELDS.items():
            if classification == DataClassification.RESTRICTED and field in result:
                result[field] = encryption_service.encrypt_pii(str(result[field]))
        return result
```

### 9.2 Data Retention

```python
# ✅ SECURE: Data retention management
from datetime import datetime, timedelta
from typing import List

class DataRetentionPolicy:
    """Enforce data retention policies"""
    
    POLICIES = {
        'audit_logs': timedelta(days=2555),  # 7 years (regulatory)
        'order_history': timedelta(days=2555),  # 7 years
        'customer_sessions': timedelta(days=30),
        'failed_login_attempts': timedelta(days=90),
        'password_reset_tokens': timedelta(days=1),
        'payment_logs': timedelta(days=2555),  # 7 years
        'farm_production_data': timedelta(days=3650),  # 10 years
    }
    
    @classmethod
    def get_retention_period(cls, data_type: str) -> timedelta:
        return cls.POLICIES.get(data_type, timedelta(days=365))
    
    @classmethod
    def should_archive(cls, created_at: datetime, data_type: str) -> bool:
        """Check if data should be archived"""
        retention = cls.get_retention_period(data_type)
        return datetime.utcnow() - created_at > retention
    
    @classmethod
    def anonymize_customer_data(cls, customer_id: str) -> dict:
        """Anonymize customer data for GDPR/privacy compliance"""
        return {
            "customer_id": f"ANON_{hash(customer_id) % 1000000}",
            "name": "***ANONYMIZED***",
            "email": None,
            "phone": None,
            "address": None,
            "anonymized_at": datetime.utcnow().isoformat()
        }
```

---

## 10. Communication Security

### 10.1 TLS Configuration

**Minimum Requirements:**
- TLS 1.2 or higher
- Strong cipher suites only
- Valid certificates from trusted CA
- Certificate pinning for mobile apps

```python
# ✅ SECURE: TLS configuration for requests
import ssl
import certifi
import requests
from requests.adapters import HTTPAdapter
from urllib3.util.ssl_ import create_urllib3_context

class TLSAdapter(HTTPAdapter):
    """Custom TLS adapter with secure defaults"""
    
    def init_poolmanager(self, *args, **kwargs):
        context = create_urllib3_context()
        
        # Minimum TLS 1.2
        context.minimum_version = ssl.TLSVersion.TLSv1_2
        
        # Only strong cipher suites
        context.set_ciphers(
            'ECDHE+AESGCM:ECDHE+CHACHA20:DHE+AESGCM:DHE+CHACHA20:!aNULL:!MD5:!DSS'
        )
        
        kwargs['ssl_context'] = context
        return super().init_poolmanager(*args, **kwargs)

# Usage
session = requests.Session()
session.mount('https://', TLSAdapter())

# Verify certificates
response = session.get(
    'https://api.smartdairybd.com',
    verify=certifi.where(),  # Use system CA bundle
    timeout=30
)
```

### 10.2 Certificate Pinning (Mobile)

```dart
// ✅ SECURE: Certificate pinning in Flutter
import 'package:http/io_client.dart';
import 'dart:io';

class PinnedHttpClient {
  static HttpClient createPinnedClient() {
    final context = SecurityContext(withTrustedRoots: true);
    
    // Add certificate pinning
    final client = HttpClient(context: context)
      ..badCertificateCallback = (X509Certificate cert, String host, int port) {
        // Pin specific certificate or public key
        final expectedFingerprint = 
            'sha256/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=';
        
        final certFingerprint = _getCertificateFingerprint(cert);
        
        if (host == 'api.smartdairybd.com') {
          return certFingerprint == expectedFingerprint;
        }
        
        // Reject all other certificates
        return false;
      };
    
    return client;
  }
  
  static String _getCertificateFingerprint(X509Certificate cert) {
    // Implementation to extract certificate fingerprint
    return 'sha256/${base64Encode(cert.sha256)}';
  }
}

// Usage
final client = IOClient(PinnedHttpClient.createPinnedClient());
```

---

## 11. API Security

### 11.1 REST API Security

```python
# ✅ SECURE: API security middleware
from fastapi import FastAPI, Request, HTTPException
from fastapi.middleware.trustedhost import TrustedHostMiddleware
from slowapi import Limiter, _rate_limit_exceeded_handler
from slowapi.util import get_remote_address
from slowapi.errors import RateLimitExceeded

# Rate limiting
limiter = Limiter(key_func=get_remote_address)

app = FastAPI()
app.state.limiter = limiter
app.add_exception_handler(RateLimitExceeded, _rate_limit_exceeded_handler)

# Trusted hosts
app.add_middleware(
    TrustedHostMiddleware,
    allowed_hosts=["smartdairybd.com", "*.smartdairybd.com", "localhost"]
)

# API versioning
@app.get("/api/v1/products")
@limiter.limit("100/minute")
async def get_products(request: Request):
    pass

# Request size limiting
@app.middleware("http")
async def limit_request_size(request: Request, call_next):
    body = await request.body()
    if len(body) > 10 * 1024 * 1024:  # 10MB limit
        raise HTTPException(status_code=413, detail="Request too large")
    return await call_next(request)
```

### 11.2 GraphQL Security

```python
# ✅ SECURE: GraphQL security
import strawberry
from strawberry.permission import BasePermission
from typing import List

# Query depth limiting
MAX_QUERY_DEPTH = 10

class DepthLimiter:
    @staticmethod
    def check_depth(selection_set, current_depth: int = 0) -> int:
        if current_depth > MAX_QUERY_DEPTH:
            raise Exception(f"Query exceeds maximum depth of {MAX_QUERY_DEPTH}")
        
        max_depth = current_depth
        for selection in selection_set.selections:
            if hasattr(selection, 'selection_set') and selection.selection_set:
                depth = DepthLimiter.check_depth(
                    selection.selection_set, 
                    current_depth + 1
                )
                max_depth = max(max_depth, depth)
        
        return max_depth

# Field-level permissions
class IsAuthenticated(BasePermission):
    message = "User must be authenticated"
    
    def has_permission(self, source, info, **kwargs) -> bool:
        return info.context.get("user") is not None

class IsFarmManager(BasePermission):
    message = "User must be a farm manager"
    
    def has_permission(self, source, info, **kwargs) -> bool:
        user = info.context.get("user")
        return user and "farm_manager" in user.roles

@strawberry.type
class Query:
    @strawberry.field(permission_classes=[IsAuthenticated])
    def cattle(self, info) -> List[Cattle]:
        return get_cattle()
    
    @strawberry.field(permission_classes=[IsFarmManager])
    def production_reports(self, info, start_date: str, end_date: str) -> List[Report]:
        return get_production_reports(start_date, end_date)
```

### 11.3 API Authentication

```python
# ✅ SECURE: API key authentication
import secrets
from datetime import datetime, timedelta

class APIKeyManager:
    """Manage API keys for B2B integrations"""
    
    def generate_api_key(self, customer_id: str, scopes: list) -> dict:
        """Generate new API key"""
        api_key = f"sdk_{secrets.token_urlsafe(32)}"
        api_secret = secrets.token_urlsafe(32)
        
        # Hash the secret for storage
        secret_hash = hashlib.sha256(api_secret.encode()).hexdigest()
        
        key_data = {
            "key_id": api_key,
            "secret_hash": secret_hash,
            "customer_id": customer_id,
            "scopes": scopes,
            "created_at": datetime.utcnow(),
            "expires_at": datetime.utcnow() + timedelta(days=365),
            "last_used": None,
            "is_active": True
        }
        
        # Store key_data in database
        
        return {
            "api_key": api_key,
            "api_secret": api_secret,  # Only shown once
            "expires_at": key_data["expires_at"]
        }
    
    def authenticate_request(self, api_key: str, api_secret: str) -> dict:
        """Authenticate API request"""
        key_data = self._get_key_data(api_key)
        
        if not key_data or not key_data["is_active"]:
            raise AuthenticationException("Invalid API key")
        
        if datetime.utcnow() > key_data["expires_at"]:
            raise AuthenticationException("API key expired")
        
        # Verify secret
        secret_hash = hashlib.sha256(api_secret.encode()).hexdigest()
        if not secrets.compare_digest(secret_hash, key_data["secret_hash"]):
            # Log failed attempt
            self._log_failed_auth(api_key)
            raise AuthenticationException("Invalid API secret")
        
        # Update last used
        self._update_last_used(api_key)
        
        return key_data
```

---

## 12. File Handling

### 12.1 Secure File Upload

```python
# ✅ SECURE: File upload handling
import magic
from pathlib import Path
import hashlib

class SecureFileUpload:
    ALLOWED_TYPES = {
        'image/jpeg': ['.jpg', '.jpeg'],
        'image/png': ['.png'],
        'application/pdf': ['.pdf'],
        'text/csv': ['.csv'],
    }
    MAX_FILE_SIZE = 10 * 1024 * 1024  # 10MB
    
    def __init__(self, upload_dir: str):
        self.upload_dir = Path(upload_dir)
        self.upload_dir.mkdir(parents=True, exist_ok=True)
    
    def validate_upload(self, file_content: bytes, filename: str) -> tuple[bool, str]:
        """Validate uploaded file"""
        # Check file size
        if len(file_content) > self.MAX_FILE_SIZE:
            return False, "File too large"
        
        # Detect MIME type
        detected_type = magic.from_buffer(file_content, mime=True)
        
        if detected_type not in self.ALLOWED_TYPES:
            return False, f"File type not allowed: {detected_type}"
        
        # Verify extension matches content
        ext = Path(filename).suffix.lower()
        if ext not in self.ALLOWED_TYPES[detected_type]:
            return False, "File extension does not match content"
        
        # Check for embedded scripts in images (polyglot detection)
        if detected_type.startswith('image/'):
            if self._contains_script(file_content):
                return False, "Suspicious content detected"
        
        return True, "Valid"
    
    def _contains_script(self, content: bytes) -> bool:
        """Check if image contains embedded scripts"""
        # Check for common script markers
        script_markers = [b'<script', b'javascript:', b'<%', b'<?php']
        content_lower = content.lower()
        return any(marker in content_lower for marker in script_markers)
    
    def save_file(self, file_content: bytes, original_filename: str) -> dict:
        """Save uploaded file securely"""
        # Generate safe filename
        file_hash = hashlib.sha256(file_content).hexdigest()[:16]
        ext = Path(original_filename).suffix.lower()
        safe_filename = f"{file_hash}{ext}"
        
        # Store in subdirectory based on hash prefix (avoid too many files in one dir)
        subdir = self.upload_dir / file_hash[:2]
        subdir.mkdir(exist_ok=True)
        
        file_path = subdir / safe_filename
        
        # Check for path traversal
        if not str(file_path).startswith(str(self.upload_dir)):
            raise SecurityException("Path traversal attempt detected")
        
        # Save file
        with open(file_path, 'wb') as f:
            f.write(file_content)
        
        return {
            "stored_filename": safe_filename,
            "file_path": str(file_path),
            "file_hash": file_hash,
            "size": len(file_content)
        }
```

### 12.2 Path Traversal Prevention

```python
# ✅ SECURE: Path traversal prevention
import os
from pathlib import Path

class PathSecurity:
    """Prevent path traversal attacks"""
    
    @staticmethod
    def safe_join(base_path: str, *paths: str) -> str:
        """Safely join paths, preventing traversal"""
        base = Path(base_path).resolve()
        
        # Join all path components
        final_path = base.joinpath(*paths).resolve()
        
        # Ensure the result is within base_path
        if not str(final_path).startswith(str(base)):
            raise SecurityException("Path traversal attempt detected")
        
        return str(final_path)
    
    @staticmethod
    def sanitize_filename(filename: str) -> str:
        """Sanitize filename for safe storage"""
        # Remove path components
        filename = os.path.basename(filename)
        
        # Remove null bytes
        filename = filename.replace('\x00', '')
        
        # Replace dangerous characters
        filename = re.sub(r'[<>:"|?*]', '_', filename)
        
        # Prevent hidden files
        if filename.startswith('.'):
            filename = '_' + filename
        
        return filename

# Usage
try:
    safe_path = PathSecurity.safe_join(
        "/var/uploads/documents",
        user_provided_filename
    )
except SecurityException:
    # Handle attack attempt
    pass
```

---

## 13. Configuration Security

### 13.1 Secrets Management

```python
# ✅ SECURE: Secrets management
import os
from typing import Optional
import hvac  # HashiCorp Vault client

class SecretsManager:
    """Manage secrets securely using HashiCorp Vault"""
    
    def __init__(self):
        self.client = hvac.Client(
            url=os.environ['VAULT_ADDR'],
            token=os.environ['VAULT_TOKEN']
        )
    
    def get_secret(self, path: str, key: str) -> Optional[str]:
        """Retrieve secret from Vault"""
        try:
            response = self.client.secrets.kv.v2.read_secret_version(
                path=path
            )
            return response['data']['data'].get(key)
        except Exception as e:
            # Log error, don't expose details
            logging.error(f"Failed to retrieve secret: {path}")
            return None
    
    def rotate_database_credentials(self, role: str) -> dict:
        """Generate dynamic database credentials"""
        response = self.client.secrets.database.generate_credentials(
            name=role
        )
        return {
            "username": response['data']['username'],
            "password": response['data']['password'],
            "lease_id": response['lease_id'],
            "lease_duration": response['lease_duration']
        }

# Environment-based configuration (development only)
class AppConfig:
    """Application configuration"""
    
    # Database
    DATABASE_URL: str = os.environ.get('DATABASE_URL')
    DATABASE_POOL_SIZE: int = int(os.environ.get('DATABASE_POOL_SIZE', '10'))
    
    # Security
    SECRET_KEY: str = os.environ.get('SECRET_KEY')
    JWT_ALGORITHM: str = os.environ.get('JWT_ALGORITHM', 'HS256')
    JWT_EXPIRATION: int = int(os.environ.get('JWT_EXPIRATION', '900'))
    
    # Features
    DEBUG: bool = os.environ.get('DEBUG', 'false').lower() == 'true'
    
    @classmethod
    def validate(cls):
        """Validate required configuration"""
        required = ['DATABASE_URL', 'SECRET_KEY']
        missing = [var for var in required if not getattr(cls, var)]
        if missing:
            raise ValueError(f"Missing required environment variables: {missing}")
```

### 13.2 Secure Defaults

```python
# ✅ SECURE: Secure default configurations
SECURE_DEFAULTS = {
    # Database
    'database': {
        'ssl_mode': 'require',
        'connect_timeout': 10,
        'application_name': 'smart_dairy_app',
    },
    
    # Sessions
    'session': {
        'cookie_secure': True,       # HTTPS only
        'cookie_httponly': True,     # No JavaScript access
        'cookie_samesite': 'Strict', # CSRF protection
        'max_age': 1800,             # 30 minutes
    },
    
    # Passwords
    'password': {
        'min_length': 12,
        'require_uppercase': True,
        'require_lowercase': True,
        'require_numbers': True,
        'require_special': True,
        'max_age_days': 90,
        'history_count': 5,
    },
    
    # Rate Limiting
    'rate_limit': {
        'login_attempts': 5,
        'login_window': 300,  # 5 minutes
        'api_calls': 100,
        'api_window': 60,     # 1 minute
    },
    
    # File Upload
    'upload': {
        'max_size': 10 * 1024 * 1024,  # 10MB
        'allowed_extensions': ['.jpg', '.jpeg', '.png', '.pdf', '.csv'],
        'scan_virus': True,
    }
}
```

---

## 14. Database Security

### 14.1 SQL Injection Prevention

See Section 2.4 (A03: Injection) for detailed examples.

### 14.2 Odoo ORM Security

```python
# ✅ SECURE: Odoo ORM best practices
from odoo import models, fields, api
from odoo.exceptions import AccessError, ValidationError

class MilkProduction(models.Model):
    _name = 'smart_dairy.milk_production'
    _description = 'Milk Production Record'
    
    cow_id = fields.Many2one('smart_dairy.cattle', required=True)
    production_date = fields.Date(required=True)
    quantity_liters = fields.Float(required=True)
    fat_percentage = fields.Float()
    snf_percentage = fields.Float()
    recorded_by = fields.Many2one('res.users', default=lambda self: self.env.user)
    
    # Access control rules
    @api.model
    def create(self, vals):
        # Check create permission
        if not self.env.user.has_group('smart_dairy.group_farm_worker'):
            raise AccessError("You don't have permission to create production records.")
        
        # Validate cow belongs to user's farm
        cow = self.env['smart_dairy.cattle'].browse(vals.get('cow_id'))
        if cow.farm_id != self.env.user.farm_id:
            raise AccessError("You can only record production for your farm's cattle.")
        
        return super(MilkProduction, self).create(vals)
    
    def write(self, vals):
        # Check update permission
        if not self.env.user.has_group('smart_dairy.group_farm_manager'):
            # Workers can only update their own records
            for record in self:
                if record.recorded_by != self.env.user:
                    raise AccessError("You can only modify your own records.")
        
        # Prevent modification after approval
        for record in self:
            if record.state == 'approved' and not self.env.user.has_group('smart_dairy.group_farm_admin'):
                raise ValidationError("Approved records cannot be modified.")
        
        return super(MilkProduction, self).write(vals)
    
    def unlink(self):
        # Only admins can delete
        if not self.env.user.has_group('smart_dairy.group_farm_admin'):
            raise AccessError("Only administrators can delete production records.")
        return super(MilkProduction, self).unlink()
```

### 14.3 PostgreSQL Security

```sql
-- ✅ SECURE: PostgreSQL security setup

-- Create application roles
CREATE ROLE smartdairy_app_read;
CREATE ROLE smartdairy_app_write;
CREATE ROLE smartdairy_app_admin;

-- Grant appropriate permissions
GRANT USAGE ON SCHEMA public TO smartdairy_app_read;
GRANT SELECT ON ALL TABLES IN SCHEMA public TO smartdairy_app_read;

GRANT USAGE ON SCHEMA public TO smartdairy_app_write;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO smartdairy_app_write;

-- Row Level Security (RLS)
ALTER TABLE milk_production ENABLE ROW LEVEL SECURITY;

-- Policy: Users can only see their farm's data
CREATE POLICY farm_isolation_policy ON milk_production
    USING (farm_id = current_setting('app.current_farm_id')::INTEGER);

-- Create application user with minimal privileges
CREATE USER smartdairy_app WITH PASSWORD 'strong_random_password';
GRANT smartdairy_app_write TO smartdairy_app;

-- Prevent direct connections from app user to sensitive tables
REVOKE ALL ON TABLE user_credentials FROM smartdairy_app;
GRANT SELECT (user_id, last_login) ON TABLE user_credentials TO smartdairy_app;

-- Audit logging
CREATE TABLE audit_log (
    id SERIAL PRIMARY KEY,
    table_name TEXT NOT NULL,
    record_id INTEGER,
    action TEXT NOT NULL,
    old_data JSONB,
    new_data JSONB,
    changed_by INTEGER,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trigger for automatic audit logging
CREATE OR REPLACE FUNCTION audit_trigger()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'DELETE') THEN
        INSERT INTO audit_log (table_name, record_id, action, old_data, changed_by)
        VALUES (TG_TABLE_NAME, OLD.id, 'DELETE', row_to_json(OLD), current_setting('app.current_user_id')::INTEGER);
        RETURN OLD;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO audit_log (table_name, record_id, action, old_data, new_data, changed_by)
        VALUES (TG_TABLE_NAME, NEW.id, 'UPDATE', row_to_json(OLD), row_to_json(NEW), current_setting('app.current_user_id')::INTEGER);
        RETURN NEW;
    ELSIF (TG_OP = 'INSERT') THEN
        INSERT INTO audit_log (table_name, record_id, action, new_data, changed_by)
        VALUES (TG_TABLE_NAME, NEW.id, 'INSERT', row_to_json(NEW), current_setting('app.current_user_id')::INTEGER);
        RETURN NEW;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

-- Apply audit trigger to sensitive tables
CREATE TRIGGER milk_production_audit
    AFTER INSERT OR UPDATE OR DELETE ON milk_production
    FOR EACH ROW EXECUTE FUNCTION audit_trigger();
```

---

## 15. Mobile Security

### 15.1 Flutter/Dart Secure Coding

```dart
// ✅ SECURE: Secure storage in Flutter
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:encrypt/encrypt.dart' as encrypt;

class SecureStorage {
  static const _storage = FlutterSecureStorage(
    aOptions: AndroidOptions(
      encryptedSharedPreferences: true,
      keyCipherAlgorithm: KeyCipherAlgorithm.RSA_ECB_PKCS1Padding,
      storageCipherAlgorithm: StorageCipherAlgorithm.AES_GCM_NoPadding,
    ),
    iOptions: IOSOptions(
      accountName: 'flutter_secure_storage_service',
      accessibility: KeychainAccessibility.first_unlock_this_device,
    ),
  );
  
  // Store sensitive data
  static Future<void> storeToken(String token) async {
    await _storage.write(key: 'auth_token', value: token);
  }
  
  static Future<String?> getToken() async {
    return await _storage.read(key: 'auth_token');
  }
  
  static Future<void> deleteToken() async {
    await _storage.delete(key: 'auth_token');
  }
  
  // Store encrypted data
  static Future<void> storeEncrypted(String key, String value, String encryptionKey) async {
    final keyBytes = encrypt.Key.fromUtf8(encryptionKey.padRight(32, '0').substring(0, 32));
    final iv = encrypt.IV.fromSecureRandom(16);
    final encrypter = encrypt.Encrypter(encrypt.AES(keyBytes, mode: encrypt.AESMode.gcm));
    
    final encrypted = encrypter.encrypt(value, iv: iv);
    final combined = '${iv.base64}:${encrypted.base64}';
    
    await _storage.write(key: key, value: combined);
  }
}

// ✅ SECURE: API communication
import 'package:http/http.dart' as http;
import 'dart:convert';

class SecureApiClient {
  static const String baseUrl = 'https://api.smartdairybd.com';
  
  static Future<Map<String, String>> _getHeaders() async {
    final token = await SecureStorage.getToken();
    return {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer $token',
      'X-Client-Version': '1.0.0',
      'X-Device-ID': await _getDeviceId(),
    };
  }
  
  static Future<http.Response> get(String endpoint) async {
    final headers = await _getHeaders();
    final response = await http.get(
      Uri.parse('$baseUrl$endpoint'),
      headers: headers,
    );
    
    // Check for unauthorized
    if (response.statusCode == 401) {
      await SecureStorage.deleteToken();
      // Navigate to login
    }
    
    return response;
  }
  
  static Future<http.Response> post(String endpoint, Map<String, dynamic> body) async {
    final headers = await _getHeaders();
    
    // Validate input
    _validateInput(body);
    
    return await http.post(
      Uri.parse('$baseUrl$endpoint'),
      headers: headers,
      body: jsonEncode(body),
    );
  }
  
  static void _validateInput(Map<String, dynamic> data) {
    // Check for injection attempts
    data.forEach((key, value) {
      if (value is String) {
        // Prevent common injection patterns
        final dangerousPatterns = [
          RegExp(r'<script', caseSensitive: false),
          RegExp(r'javascript:', caseSensitive: false),
          RegExp(r'on\w+\s*=', caseSensitive: false), // event handlers
        ];
        
        for (final pattern in dangerousPatterns) {
          if (pattern.hasMatch(value)) {
            throw SecurityException('Potentially dangerous input detected');
          }
        }
      }
    });
  }
}

// ✅ SECURE: Certificate pinning
import 'package:http/io_client.dart';
import 'dart:io';

class PinnedHttpClient {
  static HttpClient createSecureClient() {
    final context = SecurityContext(withTrustedRoots: true);
    
    return HttpClient(context: context)
      ..badCertificateCallback = (X509Certificate cert, String host, int port) {
        // Only allow specific certificate for production API
        if (host == 'api.smartdairybd.com') {
          // Compare certificate fingerprint
          final fingerprint = _calculateFingerprint(cert);
          return fingerprint == _expectedFingerprint;
        }
        return false;
      };
  }
  
  static String _calculateFingerprint(X509Certificate cert) {
    // Implementation to calculate SHA-256 fingerprint
    return 'sha256/${base64Encode(cert.sha256)}';
  }
  
  static const String _expectedFingerprint = 
      'sha256/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=';
}

// ✅ SECURE: Biometric authentication
import 'package:local_auth/local_auth.dart';

class BiometricAuth {
  static final _localAuth = LocalAuthentication();
  
  static Future<bool> isAvailable() async {
    final isDeviceSupported = await _localAuth.isDeviceSupported();
    final canCheckBiometrics = await _localAuth.canCheckBiometrics;
    return isDeviceSupported && canCheckBiometrics;
  }
  
  static Future<bool> authenticate() async {
    final isAvailable = await BiometricAuth.isAvailable();
    if (!isAvailable) return false;
    
    try {
      return await _localAuth.authenticate(
        localizedReason: 'Authenticate to access Smart Dairy',
        authMessages: const [
          AndroidAuthMessages(
            signInTitle: 'Biometric Authentication',
            cancelButton: 'Cancel',
          ),
          IOSAuthMessages(
            cancelButton: 'Cancel',
          ),
        ],
        options: const AuthenticationOptions(
          useErrorDialogs: true,
          stickyAuth: true,
          biometricOnly: false, // Allow fallback to PIN
        ),
      );
    } catch (e) {
      return false;
    }
  }
}
```

### 15.2 Mobile Security Checklist

| Category | Requirement | Implementation |
|----------|-------------|----------------|
| **Storage** | No sensitive data in SharedPreferences | Use FlutterSecureStorage |
| **Storage** | No sensitive data in logs | Sanitize logs before output |
| **Storage** | Encrypted local database | Use SQLCipher for SQLite |
| **Network** | TLS 1.2+ only | Configure HTTP client |
| **Network** | Certificate pinning | Implement pinning |
| **Network** | No cleartext traffic | `android:usesCleartextTraffic="false"` |
| **Auth** | Biometric authentication | Implement LocalAuth |
| **Auth** | Token refresh | Automatic token renewal |
| **Code** | Root/jailbreak detection | Implement SafetyNet/DeviceCheck |
| **Code** | Obfuscation | Enable ProGuard/R8 |

---

## 16. Code Review Process

### 16.1 Security Review Checklist

#### Pre-Commit Checklist

- [ ] No hardcoded secrets (passwords, API keys, tokens)
- [ ] No debug logging of sensitive data
- [ ] Input validation implemented for all user inputs
- [ ] Output encoding implemented for dynamic content
- [ ] SQL queries use parameterized statements
- [ ] Access control checks in place for sensitive operations
- [ ] Error handling doesn't leak sensitive information
- [ ] Security headers configured
- [ ] Rate limiting implemented for APIs
- [ ] Dependencies scanned for vulnerabilities

#### Code Review Security Checklist

| Check | Priority | Description |
|-------|----------|-------------|
| Input Validation | Critical | All inputs validated and sanitized |
| Authentication | Critical | Proper auth checks for protected resources |
| Authorization | Critical | RBAC checks for operations |
| SQL Injection | Critical | Parameterized queries only |
| XSS Prevention | High | Output encoding for all contexts |
| CSRF Protection | High | Tokens for state-changing operations |
| Secrets Management | Critical | No hardcoded credentials |
| Error Handling | High | No information leakage |
| Logging | Medium | Security events logged |
| Dependencies | Medium | No known vulnerabilities |

### 16.2 Secure Code Review Process

```
┌─────────────────────────────────────────────────────────────────┐
│                 SECURITY CODE REVIEW PROCESS                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. Developer Submits PR                                         │
│     ├── Self-review using checklist                              │
│     └── Run SAST tools locally                                   │
│                                                                  │
│  2. Automated Scanning                                           │
│     ├── SAST (SonarQube, Semgrep)                                │
│     ├── Dependency scan (Snyk, OWASP DC)                         │
│     ├── Secret detection (GitLeaks)                              │
│     └── Linting with security rules                              │
│                                                                  │
│  3. Peer Review                                                  │
│     ├── Code quality review                                      │
│     ├── Functional review                                        │
│     └── Security spot-check                                      │
│                                                                  │
│  4. Security Review (for high-risk changes)                      │
│     ├── Architecture review                                      │
│     ├── Threat model validation                                  │
│     └── Security test cases review                               │
│                                                                  │
│  5. Approval & Merge                                             │
│     ├── All checks pass                                          │
│     ├── Security sign-off                                        │
│     └── Merge to main                                            │
└─────────────────────────────────────────────────────────────────┘
```

---

## 17. Static Analysis

### 17.1 SAST Tools Configuration

**SonarQube Configuration:**
```yaml
# sonar-project.properties
sonar.projectKey=smart-dairy
sonar.projectName=Smart Dairy Portal
sonar.sources=src
sonar.language=py,js,ts,dart

# Security profile
sonar.profile.python=Sonar way with Security
sonar.profile.javascript=Sonar way with Security
sonar.profile.typescript=Sonar way with Security

# Exclusions
sonar.exclusions=**/tests/**,**/migrations/**,**/*.test.js

# Coverage
sonar.python.coverage.reportPaths=coverage.xml
sonar.javascript.lcov.reportPaths=coverage/lcov.info
```

**Semgrep Rules:**
```yaml
# .semgrep.yml
rules:
  # Security rules
  - id: detect-sql-injection
    pattern-either:
      - pattern: |
          $QUERY = "..." + $X
          ...
          $DB.execute($QUERY)
    languages: [python]
    message: "Potential SQL injection detected"
    severity: ERROR
  
  - id: detect-hardcoded-secrets
    pattern-regex: (?i)(password|secret|key|token)\s*=\s*["'][^"']{8,}["']
    languages: [python, javascript, typescript]
    message: "Potential hardcoded secret detected"
    severity: WARNING
  
  - id: detect-insecure-crypto
    pattern: hashlib.md5(...)
    languages: [python]
    message: "MD5 is insecure, use SHA-256 or better"
    severity: ERROR

# Import additional rulesets
include:
  - "p/owasp-top-ten"
  - "p/cwe-top-25"
  - "p/security-audit"
```

### 17.2 CI/CD Integration

```yaml
# .github/workflows/security.yml
name: Security Scan

on: [push, pull_request]

jobs:
  sast:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Run Semgrep
        uses: returntocorp/semgrep-action@v1
        with:
          config: >-
            p/security-audit
            p/owasp-top-ten
            p/cwe-top-25
      
      - name: Run SonarQube
        uses: sonarsource/sonarqube-scan-action@master
        env:
          SONAR_TOKEN: ${{ secrets.SONAR_TOKEN }}
          SONAR_HOST_URL: ${{ secrets.SONAR_HOST_URL }}
  
  dependency-check:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Python dependencies scan
        uses: pypa/gh-action-pip-audit@v1.0.0
        with:
          inputs: requirements.txt
      
      - name: JavaScript dependencies scan
        run: npx audit-ci --moderate
  
  secret-scan:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
        with:
          fetch-depth: 0
      
      - name: Run GitLeaks
        uses: gitleaks/gitleaks-action@v2
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
```

---

## 18. Dynamic Testing

### 18.1 DAST Tools

**OWASP ZAP Configuration:**
```yaml
# zap-full-scan.yaml
---
name: "OWASP ZAP Full Scan"

jobs:
  zap-full-scan:
    runs-on: ubuntu-latest
    steps:
      - name: ZAP Full Scan
        uses: zaproxy/action-full-scan@v0.7.0
        with:
          token: ${{ secrets.GITHUB_TOKEN }}
          docker_name: 'ghcr.io/zaproxy/zaproxy:stable'
          target: 'https://staging.smartdairybd.com'
          rules_file_name: '.zap/rules.tsv'
          cmd_options: >
            -a
            -j
            -config view.mode=attack
            -config api.disablekey=true
```

**Burp Suite Enterprise Integration:**
```python
# Automated DAST with Burp
from burp_api import BurpEnterprise

def run_security_scan(target_url: str):
    burp = BurpEnterprise(
        url="https://burp-enterprise.smartdairybd.com",
        api_key=os.environ['BURP_API_KEY']
    )
    
    # Create and run scan
    scan = burp.create_scan(
        name=f"Security Scan - {target_url}",
        target=target_url,
        scan_configuration=[
            "Crawl strategy - fastest",
            "Audit checks - all"
        ]
    )
    
    # Wait for completion and get results
    results = scan.wait_for_completion(timeout=3600)
    
    # Process critical and high findings
    critical_issues = [i for i in results.issues if i.severity in ('Critical', 'High')]
    
    if critical_issues:
        # Fail the build
        raise SecurityException(f"{len(critical_issues)} critical security issues found")
```

### 18.2 Security Test Cases

```python
# ✅ SECURE: Security test examples
import pytest
from fastapi.testclient import TestClient

class TestSecurity:
    """Security-focused test cases"""
    
    def test_sql_injection_protection(self, client: TestClient):
        """Test that SQL injection attempts are blocked"""
        malicious_input = "'; DROP TABLE users; --"
        
        response = client.get(f"/api/cattle?name={malicious_input}")
        
        # Should return empty results or 400, not 500
        assert response.status_code in (200, 400)
        
        # Verify table still exists
        response = client.get("/api/cattle")
        assert response.status_code == 200
    
    def test_xss_prevention(self, client: TestClient):
        """Test that XSS payloads are sanitized"""
        xss_payload = "<script>alert('xss')</script>"
        
        response = client.post("/api/feedback", json={
            "message": xss_payload
        })
        
        # Get the stored feedback
        response = client.get("/api/feedback")
        content = response.text
        
        # Script tag should be encoded or removed
        assert "<script>" not in content
    
    def test_authentication_required(self, client: TestClient):
        """Test that protected endpoints require authentication"""
        protected_endpoints = [
            "/api/admin/users",
            "/api/farm/production",
            "/api/orders/all"
        ]
        
        for endpoint in protected_endpoints:
            response = client.get(endpoint)
            assert response.status_code == 401, f"{endpoint} should require auth"
    
    def test_rate_limiting(self, client: TestClient):
        """Test that rate limiting is enforced"""
        # Make many rapid requests
        for _ in range(150):  # Assuming 100/minute limit
            response = client.get("/api/products")
        
        # Should be rate limited
        assert response.status_code == 429
    
    def test_idor_protection(self, authenticated_client: TestClient):
        """Test protection against IDOR attacks"""
        # Try to access another user's order
        response = authenticated_client.get("/api/orders/99999")
        
        # Should return 404 (not found) not 403 (forbidden)
        # to prevent ID enumeration
        assert response.status_code == 404
    
    def test_csrf_protection(self, client: TestClient):
        """Test CSRF token validation"""
        response = client.post("/api/orders", json={
            "product_id": 1,
            "quantity": 10
        }, headers={
            # Missing CSRF token
        })
        
        assert response.status_code == 403
```

---

## 19. Dependency Management

### 19.1 Vulnerability Scanning

```bash
# Python dependencies
pip install safety
safety check -r requirements.txt

# JavaScript dependencies
npm audit
npm audit fix

# Or use audit-ci for CI/CD
npx audit-ci --moderate

# Dart/Flutter dependencies
flutter pub audit
```

### 19.2 Dependency Update Policy

| Severity | Response Time | Action |
|----------|--------------|--------|
| Critical | 24 hours | Emergency patch |
| High | 7 days | Scheduled update |
| Medium | 30 days | Regular maintenance |
| Low | Next release | Planned update |

### 19.3 Software Bill of Materials (SBOM)

```python
# Generate SBOM
import json
import subprocess

def generate_sbom():
    # Python SBOM
    pip_list = subprocess.run(
        ['pip', 'list', '--format=json'],
        capture_output=True, text=True
    )
    python_packages = json.loads(pip_list.stdout)
    
    # JavaScript SBOM
    npm_ls = subprocess.run(
        ['npm', 'ls', '--json'],
        capture_output=True, text=True
    )
    npm_packages = json.loads(npm_ls.stdout)
    
    sbom = {
        "project": "smart-dairy-portal",
        "generated_at": datetime.utcnow().isoformat(),
        "components": {
            "python": python_packages,
            "javascript": npm_packages
        }
    }
    
    with open('sbom.json', 'w') as f:
        json.dump(sbom, f, indent=2)
```

---

## 20. Appendices

### Appendix A: Secure Code Examples

#### A.1 Complete Authentication Flow

```python
# ✅ SECURE: Complete authentication implementation
from fastapi import APIRouter, Depends, HTTPException, status, Request
from fastapi.security import OAuth2PasswordBearer, OAuth2PasswordRequestForm
from pydantic import BaseModel, EmailStr, validator
import secrets
from datetime import datetime, timedelta
from jose import JWTError, jwt

router = APIRouter(prefix="/auth")

# Models
class UserRegister(BaseModel):
    email: EmailStr
    password: str
    full_name: str
    
    @validator('password')
    def validate_password(cls, v, values):
        if 'email' in values:
            is_valid, errors = PasswordValidator.validate(v, values['email'])
            if not is_valid:
                raise ValueError(', '.join(errors))
        return v

class TokenResponse(BaseModel):
    access_token: str
    refresh_token: str
    token_type: str = "bearer"
    expires_in: int

# JWT Configuration
SECRET_KEY = os.environ['JWT_SECRET_KEY']
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 15
REFRESH_TOKEN_EXPIRE_DAYS = 7

# Rate limiting storage (use Redis in production)
login_attempts = {}
MAX_ATTEMPTS = 5
LOCKOUT_DURATION = 900  # 15 minutes

def check_rate_limit(identifier: str) -> bool:
    """Check if identifier is rate limited"""
    now = datetime.utcnow()
    if identifier in login_attempts:
        attempts, locked_until = login_attempts[identifier]
        if locked_until and now < locked_until:
            return False  # Still locked
        if locked_until and now >= locked_until:
            # Reset after lockout
            del login_attempts[identifier]
    return True

def record_failed_attempt(identifier: str):
    """Record failed login attempt"""
    now = datetime.utcnow()
    if identifier not in login_attempts:
        login_attempts[identifier] = [1, None]
    else:
        login_attempts[identifier][0] += 1
        if login_attempts[identifier][0] >= MAX_ATTEMPTS:
            login_attempts[identifier][1] = now + timedelta(seconds=LOCKOUT_DURATION)

@router.post("/register", response_model=dict)
async def register(user_data: UserRegister, request: Request):
    """Register new user"""
    # Check if email already exists
    if await get_user_by_email(user_data.email):
        # Return same message as success to prevent enumeration
        return {"message": "Registration request received"}
    
    # Hash password
    password_hash = PasswordHasher().hash_password(user_data.password)
    
    # Create user
    user = await create_user(
        email=user_data.email,
        password_hash=password_hash,
        full_name=user_data.full_name
    )
    
    # Log registration
    await log_security_event(
        event="USER_REGISTERED",
        user_id=user.id,
        ip_address=request.client.host
    )
    
    # Send verification email
    await send_verification_email(user.email)
    
    return {"message": "Registration successful. Please verify your email."}

@router.post("/login", response_model=TokenResponse)
async def login(
    request: Request,
    form_data: OAuth2PasswordRequestForm = Depends()
):
    """Authenticate user and return tokens"""
    identifier = f"{request.client.host}:{form_data.username}"
    
    # Check rate limiting
    if not check_rate_limit(identifier):
        raise HTTPException(
            status_code=status.HTTP_429_TOO_MANY_REQUESTS,
            detail="Too many login attempts. Please try again later."
        )
    
    # Authenticate
    user = await authenticate_user(form_data.username, form_data.password)
    
    if not user:
        record_failed_attempt(identifier)
        
        # Log failed attempt
        await log_security_event(
            event="LOGIN_FAILED",
            email=form_data.username,
            ip_address=request.client.host,
            reason="Invalid credentials"
        )
        
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid email or password",
            headers={"WWW-Authenticate": "Bearer"},
        )
    
    # Check if email verified
    if not user.email_verified:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Email not verified"
        )
    
    # Check if MFA required
    if user.mfa_enabled:
        temp_token = create_temp_token(user.id)
        return {"requires_mfa": True, "temp_token": temp_token}
    
    # Create tokens
    access_token_expires = timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    access_token = create_access_token(
        data={"sub": user.id, "roles": user.roles},
        expires_delta=access_token_expires
    )
    refresh_token = create_refresh_token(user.id)
    
    # Log successful login
    await log_security_event(
        event="LOGIN_SUCCESS",
        user_id=user.id,
        ip_address=request.client.host,
        user_agent=request.headers.get("user-agent")
    )
    
    # Clear rate limit on success
    if identifier in login_attempts:
        del login_attempts[identifier]
    
    return TokenResponse(
        access_token=access_token,
        refresh_token=refresh_token,
        expires_in=ACCESS_TOKEN_EXPIRE_MINUTES * 60
    )

@router.post("/refresh", response_model=TokenResponse)
async def refresh_token(refresh_token: str):
    """Refresh access token"""
    try:
        payload = jwt.decode(refresh_token, SECRET_KEY, algorithms=[ALGORITHM])
        if payload.get("type") != "refresh":
            raise HTTPException(status_code=401, detail="Invalid token type")
        
        user_id = payload.get("sub")
        user = await get_user_by_id(user_id)
        
        if not user or not user.is_active:
            raise HTTPException(status_code=401, detail="User not found")
        
        # Create new tokens
        access_token_expires = timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
        access_token = create_access_token(
            data={"sub": user.id, "roles": user.roles},
            expires_delta=access_token_expires
        )
        new_refresh_token = create_refresh_token(user.id)
        
        return TokenResponse(
            access_token=access_token,
            refresh_token=new_refresh_token,
            expires_in=ACCESS_TOKEN_EXPIRE_MINUTES * 60
        )
        
    except JWTError:
        raise HTTPException(status_code=401, detail="Invalid refresh token")

@router.post("/logout")
async def logout(token: str = Depends(oauth2_scheme)):
    """Logout user (revoke token)"""
    # Add token to blacklist (implement with Redis)
    await revoke_token(token)
    return {"message": "Logged out successfully"}
```

### Appendix B: Tools Reference

| Category | Tool | Purpose |
|----------|------|---------|
| **SAST** | SonarQube | Code quality and security analysis |
| **SAST** | Semgrep | Lightweight static analysis |
| **SAST** | Bandit | Python security linter |
| **SAST** | ESLint Security | JavaScript security rules |
| **DAST** | OWASP ZAP | Web app security scanning |
| **DAST** | Burp Suite | Professional web app testing |
| **Dependencies** | Snyk | Vulnerability scanning |
| **Dependencies** | OWASP DC | Dependency check |
| **Dependencies** | Safety | Python dependency scanning |
| **Secrets** | GitLeaks | Secret detection |
| **Secrets** | TruffleHog | Secret scanning |
| **Secrets** | Vault | Secret management |
| **Crypto** | OpenSSL | Cryptographic operations |
| **Crypto** | cryptography (Python) | Python crypto library |

### Appendix C: Security Checklist

#### Pre-Deployment Checklist

- [ ] All SAST scans pass with no critical/high findings
- [ ] Dependency scan shows no vulnerabilities
- [ ] No hardcoded secrets in code
- [ ] Security headers configured
- [ ] TLS properly configured
- [ ] Rate limiting enabled
- [ ] Input validation implemented
- [ ] Output encoding implemented
- [ ] Authentication/authorization tested
- [ ] Error handling reviewed
- [ ] Logging configured
- [ ] Secrets management verified
- [ ] Database security configured
- [ ] API security validated
- [ ] Mobile security checked

#### Compliance Checklist (FSSAI/BFSA)

- [ ] Audit logging for all data modifications
- [ ] Data retention policies implemented
- [ ] Access control for traceability data
- [ ] Secure data transmission
- [ ] Backup and recovery tested
- [ ] Incident response plan in place

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author (Security Lead) | | | January 31, 2026 |
| Owner (Development Lead) | | | January 31, 2026 |
| Reviewer (CTO) | | | January 31, 2026 |
| Approver (Managing Director) | | | January 31, 2026 |

---

*This document is the property of Smart Dairy Ltd. Unauthorized distribution or modification is prohibited.*

*© 2026 Smart Dairy Ltd. All Rights Reserved.*
