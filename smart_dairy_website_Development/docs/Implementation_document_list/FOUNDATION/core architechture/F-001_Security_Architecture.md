# SMART DAIRY LTD.
## SECURITY ARCHITECTURE DOCUMENT
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | F-001 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Status** | Draft for Review |
| **Author** | Security Lead |
| **Owner** | Security Lead |
| **Reviewer** | IT Director |
| **Approved By** | Managing Director |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Security Strategy](#2-security-strategy)
3. [Defense in Depth Architecture](#3-defense-in-depth-architecture)
4. [Authentication & Authorization](#4-authentication--authorization)
5. [Data Protection](#5-data-protection)
6. [Application Security](#6-application-security)
7. [Network Security](#7-network-security)
8. [Compliance & Auditing](#8-compliance--auditing)
9. [Incident Response](#9-incident-response)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This Security Architecture Document defines the comprehensive security framework for the Smart Dairy Smart Web Portal System and Integrated ERP. It establishes security controls, policies, and procedures to protect sensitive data, ensure regulatory compliance, and maintain business continuity.

### 1.2 Security Objectives

| Objective | Target | Measurement |
|-----------|--------|-------------|
| **Confidentiality** | Prevent unauthorized data access | Zero data breaches |
| **Integrity** | Ensure data accuracy | 100% data validation |
| **Availability** | Maintain system uptime | 99.9% availability |
| **Compliance** | Meet regulatory requirements | 100% audit pass |

### 1.3 Threat Model

**Primary Threats:**

| Threat | Likelihood | Impact | Mitigation |
|--------|------------|--------|------------|
| SQL Injection | Medium | High | ORM, parameterized queries |
| XSS Attacks | Medium | High | Output encoding, CSP |
| Credential Theft | Medium | High | MFA, strong passwords |
| DDoS Attacks | Low | High | WAF, rate limiting |
| Insider Threats | Low | High | RBAC, audit logging |
| Data Exfiltration | Low | Critical | Encryption, DLP |

---

## 2. SECURITY STRATEGY

### 2.1 Security Principles

1. **Defense in Depth**: Multiple layers of security controls
2. **Least Privilege**: Minimum necessary access rights
3. **Zero Trust**: Verify every request, never trust implicitly
4. **Secure by Default**: Security enabled from the start
5. **Fail Secure**: Safe failure modes

### 2.2 Security Governance

```
┌─────────────────────────────────────────────────────────────┐
│              SECURITY GOVERNANCE STRUCTURE                   │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌─────────────────┐                                        │
│  │  CISO/IT Dir    │  Security Strategy & Oversight        │
│  └────────┬────────┘                                        │
│           │                                                  │
│     ┌─────┴─────┬─────────────┬─────────────┐              │
│     │           │             │             │              │
│     ▼           ▼             ▼             ▼              │
│  ┌──────┐  ┌──────┐    ┌──────────┐  ┌──────────┐         │
│  │AppSec│  │NetSec│    │Compliance│  │Incident  │         │
│  │Team  │  │Team  │    │  Team    │  │Response  │         │
│  └──────┘  └──────┘    └──────────┘  └──────────┘         │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## 3. DEFENSE IN DEPTH ARCHITECTURE

### 3.1 Layered Security Model

```
┌─────────────────────────────────────────────────────────────────┐
│ LAYER 1: PERIMETER SECURITY                                     │
│ • DDoS Protection (AWS Shield)                                 │
│ • Web Application Firewall (WAF)                               │
│ • Rate Limiting                                                │
│ • Bot Detection                                                │
├─────────────────────────────────────────────────────────────────┤
│ LAYER 2: NETWORK SECURITY                                       │
│ • VPC Isolation                                                │
│ • Security Groups                                              │
│ • Network ACLs                                                 │
│ • VPN for Admin Access                                         │
├─────────────────────────────────────────────────────────────────┤
│ LAYER 3: APPLICATION SECURITY                                   │
│ • TLS 1.3 Encryption                                           │
│ • Input Validation                                             │
│ • CSRF/XSS Protection                                          │
│ • Security Headers                                             │
├─────────────────────────────────────────────────────────────────┤
│ LAYER 4: DATA SECURITY                                          │
│ • Encryption at Rest (AES-256)                                 │
│ • Encryption in Transit                                        │
│ • Field-level Encryption                                       │
│ • Tokenization (Payment Data)                                  │
├─────────────────────────────────────────────────────────────────┤
│ LAYER 5: ACCESS CONTROL                                         │
│ • Multi-Factor Authentication                                  │
│ • Role-Based Access Control                                    │
│ • Privileged Access Management                                 │
│ • Session Management                                           │
├─────────────────────────────────────────────────────────────────┤
│ LAYER 6: MONITORING & RESPONSE                                  │
│ • Audit Logging                                                │
│ • SIEM Integration                                             │
│ • Anomaly Detection                                            │
│ • Incident Response                                            │
└─────────────────────────────────────────────────────────────────┘
```

---

## 4. AUTHENTICATION & AUTHORIZATION

### 4.1 Authentication Architecture

**Multi-Factor Authentication (MFA):**

| Factor Type | Implementation | Use Case |
|-------------|----------------|----------|
| **Knowledge** | Password + Security Questions | All users |
| **Possession** | TOTP (Google Authenticator) | Admins, Finance |
| **Inherence** | Biometric (Mobile apps) | Mobile users |
| **Context** | Device fingerprint, Location | Risk-based |

**MFA Enrollment Matrix:**

| User Role | Password | TOTP | SMS | Biometric |
|-----------|----------|------|-----|-----------|
| Super Admin | Required | Required | Optional | Optional |
| Admin | Required | Required | Optional | - |
| Finance | Required | Required | Optional | - |
| Manager | Required | Optional | Optional | - |
| Standard User | Required | Optional | - | - |
| B2B Customer | Required | Optional | - | - |
| B2C Customer | Required | - | Optional | Mobile |

### 4.2 OAuth 2.0 + JWT Implementation

```python
# Authentication Flow
from datetime import datetime, timedelta
from jose import jwt, JWTError
from passlib.context import CryptContext

# Password hashing
pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")

# JWT Configuration
SECRET_KEY = "your-secret-key-here"  # From environment/Secrets Manager
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 30
REFRESH_TOKEN_EXPIRE_DAYS = 7

class AuthService:
    """Authentication and authorization service."""
    
    def verify_password(self, plain: str, hashed: str) -> bool:
        """Verify password against hash."""
        return pwd_context.verify(plain, hashed)
    
    def hash_password(self, password: str) -> str:
        """Hash password."""
        return pwd_context.hash(password)
    
    def create_access_token(
        self, 
        data: dict, 
        expires_delta: timedelta = None
    ) -> str:
        """Create JWT access token."""
        to_encode = data.copy()
        expire = datetime.utcnow() + (
            expires_delta or timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
        )
        to_encode.update({
            "exp": expire,
            "iat": datetime.utcnow(),
            "type": "access"
        })
        return jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)
    
    def create_refresh_token(self, data: dict) -> str:
        """Create JWT refresh token."""
        to_encode = data.copy()
        expire = datetime.utcnow() + timedelta(days=REFRESH_TOKEN_EXPIRE_DAYS)
        to_encode.update({
            "exp": expire,
            "iat": datetime.utcnow(),
            "type": "refresh"
        })
        return jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)
    
    def decode_token(self, token: str) -> dict:
        """Decode and verify JWT token."""
        try:
            payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
            return payload
        except JWTError as e:
            raise AuthenticationError(f"Invalid token: {e}")

# FastAPI Dependency
from fastapi import Depends, HTTPException
from fastapi.security import OAuth2PasswordBearer

oauth2_scheme = OAuth2PasswordBearer(tokenUrl="auth/token")

async def get_current_user(token: str = Depends(oauth2_scheme)):
    """Get current authenticated user."""
    credentials_exception = HTTPException(
        status_code=401,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )
    
    auth_service = AuthService()
    try:
        payload = auth_service.decode_token(token)
        username: str = payload.get("sub")
        if username is None:
            raise credentials_exception
    except JWTError:
        raise credentials_exception
    
    user = await get_user_by_username(username)
    if user is None:
        raise credentials_exception
    
    return user

async def get_current_active_user(
    current_user: User = Depends(get_current_user)
):
    """Get current active user."""
    if not current_user.is_active:
        raise HTTPException(status_code=400, detail="Inactive user")
    return current_user
```

### 4.3 Role-Based Access Control (RBAC)

```python
# RBAC Implementation
from enum import Enum
from functools import wraps

class Permission(Enum):
    # Farm Management
    FARM_VIEW = "farm.view"
    FARM_CREATE = "farm.create"
    FARM_UPDATE = "farm.update"
    FARM_DELETE = "farm.delete"
    
    # Animal Management
    ANIMAL_VIEW = "animal.view"
    ANIMAL_CREATE = "animal.create"
    ANIMAL_UPDATE = "animal.update"
    ANIMAL_DELETE = "animal.delete"
    
    # Health Records
    HEALTH_VIEW = "health.view"
    HEALTH_CREATE = "health.create"
    HEALTH_UPDATE = "health.update"
    
    # Sales
    ORDER_VIEW = "order.view"
    ORDER_CREATE = "order.create"
    ORDER_UPDATE = "order.update"
    ORDER_DELETE = "order.delete"
    
    # B2B
    B2B_VIEW = "b2b.view"
    B2B_MANAGE = "b2b.manage"
    
    # Admin
    ADMIN_ACCESS = "admin.access"
    USER_MANAGE = "user.manage"
    SETTINGS_MANAGE = "settings.manage"

# Role Definitions
ROLES = {
    "super_admin": set(Permission),  # All permissions
    
    "admin": {
        Permission.FARM_VIEW, Permission.FARM_CREATE, Permission.FARM_UPDATE,
        Permission.ANIMAL_VIEW, Permission.ANIMAL_CREATE, Permission.ANIMAL_UPDATE,
        Permission.HEALTH_VIEW, Permission.HEALTH_CREATE, Permission.HEALTH_UPDATE,
        Permission.ORDER_VIEW, Permission.ORDER_CREATE, Permission.ORDER_UPDATE,
        Permission.B2B_VIEW, Permission.B2B_MANAGE,
        Permission.USER_MANAGE, Permission.SETTINGS_MANAGE,
    },
    
    "farm_manager": {
        Permission.FARM_VIEW, Permission.FARM_CREATE, Permission.FARM_UPDATE,
        Permission.ANIMAL_VIEW, Permission.ANIMAL_CREATE, Permission.ANIMAL_UPDATE,
        Permission.HEALTH_VIEW, Permission.HEALTH_CREATE, Permission.HEALTH_UPDATE,
    },
    
    "farm_worker": {
        Permission.FARM_VIEW,
        Permission.ANIMAL_VIEW,
        Permission.HEALTH_VIEW, Permission.HEALTH_CREATE,
    },
    
    "sales_manager": {
        Permission.ORDER_VIEW, Permission.ORDER_CREATE, Permission.ORDER_UPDATE, Permission.ORDER_DELETE,
        Permission.B2B_VIEW, Permission.B2B_MANAGE,
    },
    
    "sales_rep": {
        Permission.ORDER_VIEW, Permission.ORDER_CREATE, Permission.ORDER_UPDATE,
    },
    
    "accountant": {
        Permission.ORDER_VIEW,
    },
    
    "b2b_customer": {
        Permission.ORDER_VIEW, Permission.ORDER_CREATE,
    },
    
    "b2c_customer": {
        Permission.ORDER_CREATE,
    },
}

class RBACService:
    """Role-Based Access Control service."""
    
    @staticmethod
    def has_permission(user_role: str, permission: Permission) -> bool:
        """Check if role has permission."""
        if user_role not in ROLES:
            return False
        return permission in ROLES[user_role]
    
    @staticmethod
    def has_any_permission(user_role: str, permissions: list) -> bool:
        """Check if role has any of the permissions."""
        return any(
            RBACService.has_permission(user_role, p) 
            for p in permissions
        )
    
    @staticmethod
    def has_all_permissions(user_role: str, permissions: list) -> bool:
        """Check if role has all permissions."""
        return all(
            RBACService.has_permission(user_role, p) 
            for p in permissions
        )

# Decorator for permission checks
def require_permission(permission: Permission):
    """Decorator to require specific permission."""
    def decorator(func):
        @wraps(func)
        async def wrapper(*args, **kwargs):
            # Get current user from request context
            user = kwargs.get('current_user')
            if not user:
                raise HTTPException(status_code=401, detail="Not authenticated")
            
            if not RBACService.has_permission(user.role, permission):
                raise HTTPException(
                    status_code=403, 
                    detail=f"Permission denied: {permission.value}"
                )
            
            return await func(*args, **kwargs)
        return wrapper
    return decorator

# Usage in FastAPI
from fastapi import APIRouter, Depends

router = APIRouter()

@router.post("/animals")
@require_permission(Permission.ANIMAL_CREATE)
async def create_animal(
    animal_data: AnimalCreate,
    current_user: User = Depends(get_current_active_user)
):
    """Create new animal record."""
    return await AnimalService.create(animal_data)
```

---

## 5. DATA PROTECTION

### 5.1 Encryption Strategy

| Data State | Algorithm | Key Management | Implementation |
|------------|-----------|----------------|----------------|
| **In Transit** | TLS 1.3 | Certificate Manager | AWS ACM, Let's Encrypt |
| **At Rest (DB)** | AES-256 | AWS KMS | RDS encryption |
| **At Rest (Files)** | AES-256 | AWS KMS | S3 SSE-KMS |
| **Application** | AES-256-GCM | HashiCorp Vault | Field-level encryption |
| **Backups** | AES-256 | AWS KMS | Encrypted snapshots |

### 5.2 Field-Level Encryption

```python
# Field-level encryption for PII
from cryptography.fernet import Fernet
from cryptography.hazmat.primitives import hashes
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2HMAC
import base64
import os

class FieldEncryption:
    """Field-level encryption service."""
    
    def __init__(self, key: bytes = None):
        """Initialize with encryption key."""
        if key is None:
            key = os.environ.get('FIELD_ENCRYPTION_KEY')
        self.cipher = Fernet(key)
    
    def encrypt(self, value: str) -> str:
        """Encrypt string value."""
        if not value:
            return value
        return self.cipher.encrypt(value.encode()).decode()
    
    def decrypt(self, encrypted: str) -> str:
        """Decrypt string value."""
        if not encrypted:
            return encrypted
        return self.cipher.decrypt(encrypted.encode()).decode()

# SQLAlchemy Type Decorator
from sqlalchemy.types import TypeDecorator, Text

class EncryptedString(TypeDecorator):
    """Encrypted string column type."""
    impl = Text
    
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.encryption = FieldEncryption()
    
    def process_bind_param(self, value, dialect):
        """Encrypt before saving."""
        if value is None:
            return None
        return self.encryption.encrypt(str(value))
    
    def process_result_value(self, value, dialect):
        """Decrypt after loading."""
        if value is None:
            return None
        return self.encryption.decrypt(value)

# Model usage
class Customer(Base):
    __tablename__ = 'customers'
    
    id = Column(Integer, primary_key=True)
    name = Column(String(255), nullable=False)
    email = Column(String(255), nullable=False)
    phone = Column(EncryptedString(50))  # Encrypted
    national_id = Column(EncryptedString(100))  # Encrypted
    address = Column(EncryptedString(500))  # Encrypted
```

### 5.3 Payment Data Security (PCI DSS)

```python
# Payment tokenization
class PaymentTokenization:
    """Handle payment card tokenization."""
    
    def __init__(self):
        self.vault = PaymentVault()  # Secure token vault
    
    def tokenize_card(self, card_data: dict) -> str:
        """
        Tokenize card data.
        Never store actual card numbers.
        """
        # Validate card data
        if not self._validate_card(card_data):
            raise ValueError("Invalid card data")
        
        # Send to payment gateway for tokenization
        token = self.vault.create_token(card_data)
        
        # Store only token and last 4 digits
        return {
            'token': token,
            'last4': card_data['number'][-4:],
            'brand': self._get_card_brand(card_data['number']),
            'exp_month': card_data['exp_month'],
            'exp_year': card_data['exp_year'],
        }
    
    def _validate_card(self, card_data: dict) -> bool:
        """Validate card number using Luhn algorithm."""
        # Luhn check implementation
        pass
    
    def _get_card_brand(self, number: str) -> str:
        """Identify card brand from number."""
        if number.startswith('4'):
            return 'visa'
        elif number.startswith('5'):
            return 'mastercard'
        # ... other brands
```

---

## 6. APPLICATION SECURITY

### 6.1 Input Validation

```python
# Input validation with Pydantic
from pydantic import BaseModel, Field, validator, EmailStr
from typing import Optional
import re

class AnimalCreateRequest(BaseModel):
    """Validated animal creation request."""
    
    name: str = Field(..., min_length=3, max_length=50, regex=r'^[A-Z0-9]+$')
    rfid_tag: Optional[str] = Field(None, max_length=100, regex=r'^[A-F0-9]+$')
    species: str = Field(..., regex=r'^(cattle|buffalo)$')
    gender: str = Field(..., regex=r'^(male|female)$')
    birth_date: Optional[str] = None
    
    @validator('name')
    def validate_name(cls, v):
        """Validate animal name format."""
        if not v.isalnum():
            raise ValueError('Name must be alphanumeric')
        return v.upper()
    
    @validator('rfid_tag')
    def validate_rfid(cls, v):
        """Validate RFID tag format."""
        if v and len(v) < 8:
            raise ValueError('RFID tag must be at least 8 characters')
        return v
    
    @validator('birth_date')
    def validate_birth_date(cls, v):
        """Validate birth date is not in future."""
        if v:
            from datetime import datetime
            date = datetime.strptime(v, '%Y-%m-%d').date()
            if date > datetime.now().date():
                raise ValueError('Birth date cannot be in future')
        return v

# Sanitization utilities
import bleach
from html import escape

class InputSanitizer:
    """Input sanitization utilities."""
    
    ALLOWED_TAGS = ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li']
    ALLOWED_ATTRIBUTES = {}
    
    @staticmethod
    def sanitize_html(value: str) -> str:
        """Sanitize HTML input."""
        if not value:
            return value
        return bleach.clean(
            value,
            tags=InputSanitizer.ALLOWED_TAGS,
            attributes=InputSanitizer.ALLOWED_ATTRIBUTES,
            strip=True
        )
    
    @staticmethod
    def escape_html(value: str) -> str:
        """Escape HTML entities."""
        if not value:
            return value
        return escape(value)
    
    @staticmethod
    def sanitize_sql(value: str) -> str:
        """Basic SQL injection prevention."""
        # Note: Use parameterized queries instead
        dangerous = [';', '--', '/*', '*/', 'xp_']
        for d in dangerous:
            value = value.replace(d, '')
        return value
```

### 6.2 Security Headers

```python
# Security headers middleware
from fastapi import Request, Response
from starlette.middleware.base import BaseHTTPMiddleware

class SecurityHeadersMiddleware(BaseHTTPMiddleware):
    """Add security headers to all responses."""
    
    async def dispatch(self, request: Request, call_next):
        response = await call_next(request)
        
        # Prevent MIME type sniffing
        response.headers['X-Content-Type-Options'] = 'nosniff'
        
        # Prevent clickjacking
        response.headers['X-Frame-Options'] = 'DENY'
        
        # XSS Protection
        response.headers['X-XSS-Protection'] = '1; mode=block'
        
        # Strict Transport Security
        response.headers['Strict-Transport-Security'] = (
            'max-age=31536000; includeSubDomains; preload'
        )
        
        # Content Security Policy
        response.headers['Content-Security-Policy'] = (
            "default-src 'self'; "
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'; "
            "style-src 'self' 'unsafe-inline'; "
            "img-src 'self' data: https:; "
            "font-src 'self'; "
            "connect-src 'self'; "
            "frame-ancestors 'none'; "
            "base-uri 'self'; "
            "form-action 'self';"
        )
        
        # Referrer Policy
        response.headers['Referrer-Policy'] = 'strict-origin-when-cross-origin'
        
        # Permissions Policy
        response.headers['Permissions-Policy'] = (
            'geolocation=(self), '
            'microphone=(), '
            'camera=(), '
            'payment=(self)'
        )
        
        return response

# Apply in FastAPI app
from fastapi import FastAPI

app = FastAPI()
app.add_middleware(SecurityHeadersMiddleware)
```

---

## 7. NETWORK SECURITY

### 7.1 AWS WAF Rules

```hcl
# Terraform WAF configuration
resource "aws_wafv2_web_acl" "main" {
  name        = "smart-dairy-waf"
  description = "WAF rules for Smart Dairy"
  scope       = "REGIONAL"

  default_action {
    allow {}
  }

  # Rate limiting rule
  rule {
    name     = "RateLimit"
    priority = 1

    action {
      block {}
    }

    statement {
      rate_based_statement {
        limit              = 2000
        aggregate_key_type = "IP"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "RateLimit"
      sampled_requests_enabled   = true
    }
  }

  # SQL Injection protection
  rule {
    name     = "SQLInjection"
    priority = 2

    override_action {
      none {}
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesSQLiRuleSet"
        vendor_name = "AWS"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "SQLInjection"
      sampled_requests_enabled   = true
    }
  }

  # XSS protection
  rule {
    name     = "XSSProtection"
    priority = 3

    override_action {
      none {}
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesKnownBadInputsRuleSet"
        vendor_name = "AWS"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "XSSProtection"
      sampled_requests_enabled   = true
    }
  }

  # Common rule set
  rule {
    name     = "CommonRuleSet"
    priority = 4

    override_action {
      none {}
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesCommonRuleSet"
        vendor_name = "AWS"
        
        rule_action_override {
          action_to_use {
            count {}
          }
          name = "SizeRestrictions_BODY"
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "CommonRuleSet"
      sampled_requests_enabled   = true
    }
  }

  visibility_config {
    cloudwatch_metrics_enabled = true
    metric_name                = "smart-dairy-waf"
    sampled_requests_enabled   = true
  }
}
```

### 7.2 VPC Security

**Security Group Rules Summary:**

| Source | Destination | Port | Protocol | Action |
|--------|-------------|------|----------|--------|
| Internet | ALB | 80, 443 | TCP | Allow |
| ALB | EKS Nodes | 8069 | TCP | Allow |
| EKS Nodes | RDS | 5432 | TCP | Allow |
| EKS Nodes | Redis | 6379 | TCP | Allow |
| EKS Nodes | Elasticsearch | 9200 | TCP | Allow |
| Bastion | EKS Nodes | 22 | TCP | Allow |
| Bastion | RDS | 5432 | TCP | Allow |
| VPN | All Internal | All | All | Allow |

---

## 8. COMPLIANCE & AUDITING

### 8.1 Compliance Matrix

| Requirement | Standard | Implementation | Evidence |
|-------------|----------|----------------|----------|
| Data Encryption | ISO 27001 | AES-256 | Encryption configs |
| Access Control | ISO 27001 | RBAC + MFA | IAM policies |
| Audit Logging | ISO 27001 | CloudTrail + App Logs | Log storage |
| Secure Development | ISO 27001 | SAST/DAST | Scan reports |
| Incident Response | ISO 27001 | IR Plan + Playbooks | Documentation |
| Data Protection | Bangladesh DPA | Consent + Encryption | Privacy policy |
| Payment Security | PCI DSS | Tokenization + Encryption | SAQ/ROC |

### 8.2 Audit Logging

```python
# Comprehensive audit logging
import logging
import json
from datetime import datetime
from functools import wraps

class AuditLogger:
    """Audit logging for compliance."""
    
    def __init__(self):
        self.logger = logging.getLogger('audit')
    
    def log_event(
        self,
        event_type: str,
        user_id: str,
        resource_type: str,
        resource_id: str,
        action: str,
        details: dict = None,
        success: bool = True
    ):
        """Log audit event."""
        event = {
            'timestamp': datetime.utcnow().isoformat(),
            'event_type': event_type,
            'user_id': user_id,
            'resource_type': resource_type,
            'resource_id': resource_id,
            'action': action,
            'details': details or {},
            'success': success,
            'ip_address': self._get_client_ip(),
            'user_agent': self._get_user_agent(),
        }
        
        self.logger.info(json.dumps(event))
    
    def audit_log(self, event_type: str, resource_type: str, action: str):
        """Decorator for automatic audit logging."""
        def decorator(func):
            @wraps(func)
            async def wrapper(*args, **kwargs):
                user_id = kwargs.get('current_user', {}).get('id', 'anonymous')
                resource_id = kwargs.get('id') or kwargs.get('resource_id')
                
                try:
                    result = await func(*args, **kwargs)
                    self.log_event(
                        event_type=event_type,
                        user_id=user_id,
                        resource_type=resource_type,
                        resource_id=str(resource_id) if resource_id else 'N/A',
                        action=action,
                        success=True
                    )
                    return result
                except Exception as e:
                    self.log_event(
                        event_type=event_type,
                        user_id=user_id,
                        resource_type=resource_type,
                        resource_id=str(resource_id) if resource_id else 'N/A',
                        action=action,
                        details={'error': str(e)},
                        success=False
                    )
                    raise
            return wrapper
        return decorator

# Usage
audit = AuditLogger()

@router.delete("/animals/{animal_id}")
@audit.audit_log(
    event_type="ANIMAL_DELETE",
    resource_type="animal",
    action="delete"
)
async def delete_animal(
    animal_id: int,
    current_user: User = Depends(get_current_user)
):
    """Delete animal with audit logging."""
    return await AnimalService.delete(animal_id)
```

---

## 9. INCIDENT RESPONSE

### 9.1 Incident Classification

| Severity | Criteria | Response Time | Escalation |
|----------|----------|---------------|------------|
| **Critical** | Data breach, System down | 15 minutes | Immediate |
| **High** | Security vulnerability, Performance degradation | 1 hour | 4 hours |
| **Medium** | Policy violation, Failed login attempts | 4 hours | 24 hours |
| **Low** | Minor issues, Informational | 24 hours | 72 hours |

### 9.2 Incident Response Playbook

```markdown
# Incident Response Playbook

## Phase 1: Detection & Analysis
1. Identify incident through monitoring/alerts
2. Assign incident severity
3. Create incident ticket
4. Notify response team

## Phase 2: Containment
1. Short-term: Block malicious traffic
2. Long-term: Isolate affected systems
3. Preserve evidence

## Phase 3: Eradication
1. Remove threat actor access
2. Patch vulnerabilities
3. Clean infected systems

## Phase 4: Recovery
1. Restore from clean backups
2. Validate system integrity
3. Resume normal operations

## Phase 5: Post-Incident
1. Document lessons learned
2. Update security controls
3. Communicate to stakeholders
```

---

## 10. APPENDICES

### Appendix A: Security Checklist

**Pre-Deployment:**
- [ ] All secrets stored in Secrets Manager
- [ ] TLS certificates configured
- [ ] WAF rules applied
- [ ] Security groups reviewed
- [ ] Encryption enabled for all data stores
- [ ] MFA enabled for admin accounts
- [ ] Audit logging configured
- [ ] Backup encryption verified

**Ongoing:**
- [ ] Weekly vulnerability scans
- [ ] Monthly penetration tests
- [ ] Quarterly access reviews
- [ ] Annual security audit

### Appendix B: Security Contacts

| Role | Name | Contact | Escalation |
|------|------|---------|------------|
| CISO | [Name] | [Email] | [Phone] |
| Security Lead | [Name] | [Email] | [Phone] |
| On-Call Security | - | security-oncall@smartdairybd.com | [Phone] |

---

**END OF SECURITY ARCHITECTURE DOCUMENT**
