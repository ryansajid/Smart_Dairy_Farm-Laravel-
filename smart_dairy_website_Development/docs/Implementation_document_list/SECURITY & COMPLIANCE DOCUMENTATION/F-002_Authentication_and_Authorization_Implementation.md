# F-002: Authentication & Authorization Implementation

## Smart Dairy Ltd. - Smart Web Portal System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | F-002 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Lead |
| **Owner** | Security Lead |
| **Reviewer** | IT Director |
| **Classification** | Internal Use |
| **Status** | Approved |

### Revision History

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | January 31, 2026 | Security Lead | Initial release - Authentication & Authorization Implementation Guide |

### Distribution List

- IT Director
- Security Team
- Development Team Leads
- DevOps Engineers
- QA Team

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Authentication Strategies](#2-authentication-strategies)
3. [JWT Implementation](#3-jwt-implementation)
4. [OAuth 2.0 Flows](#4-oauth-20-flows)
5. [Multi-Factor Authentication](#5-multi-factor-authentication)
6. [Role-Based Access Control (RBAC)](#6-role-based-access-control-rbac)
7. [Attribute-Based Access Control (ABAC)](#7-attribute-based-access-control-abac)
8. [Session Management](#8-session-management)
9. [Password Policies](#9-password-policies)
10. [API Authentication](#10-api-authentication)
11. [Implementation Examples](#11-implementation-examples)
12. [Testing & Validation](#12-testing--validation)
13. [Troubleshooting](#13-troubleshooting)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document provides a comprehensive guide for implementing authentication and authorization mechanisms for the Smart Dairy Web Portal System. It covers all aspects of secure access control, from basic password authentication to advanced multi-factor authentication and fine-grained authorization patterns.

### 1.2 Scope

This implementation guide applies to:
- FastAPI API Gateway
- Odoo 19 CE ERP Integration
- Flutter Mobile Applications
- PostgreSQL User Data Storage
- Redis Session Management
- Third-party Integrations

### 1.3 Authentication Architecture Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                          SMART DAIRY AUTH ARCHITECTURE                       │
└─────────────────────────────────────────────────────────────────────────────┘

┌──────────────┐    ┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│   Flutter    │    │   Web App    │    │  IoT Devices │    │  Partners    │
│    Mobile    │    │   (React)    │    │   (Sensors)  │    │   (API)      │
└──────┬───────┘    └──────┬───────┘    └──────┬───────┘    └──────┬───────┘
       │                   │                   │                   │
       └───────────────────┴───────────────────┴───────────────────┘
                               │
                    ┌──────────▼──────────┐
                    │   API Gateway       │
                    │     (FastAPI)       │
                    │  ┌───────────────┐  │
                    │  │  Rate Limiter │  │
                    │  │  JWT Validator│  │
                    │  │  RBAC Engine  │  │
                    │  └───────────────┘  │
                    └──────────┬──────────┘
                               │
              ┌────────────────┼────────────────┐
              │                │                │
     ┌────────▼────────┐ ┌─────▼──────┐ ┌──────▼──────┐
     │   Auth Service  │ │  Session   │ │   Odoo 19   │
     │   (Python)      │ │   Store    │ │     CE      │
     │  ┌───────────┐  │ │  (Redis)   │ │  ┌───────┐  │
     │  │  OAuth2   │  │ └────────────┘ │  │ ERP   │  │
     │  │  Server   │  │                │  │ Auth  │  │
     │  └───────────┘  │                │  └───────┘  │
     │  ┌───────────┐  │                │             │
     │  │  MFA      │  │                │             │
     │  │  Engine   │  │                │             │
     │  └───────────┘  │                │             │
     └────────┬────────┘                │             │
              │                         │             │
     ┌────────▼────────┐                │             │
     │  User Database  │                │             │
     │  (PostgreSQL)   │                │             │
     └─────────────────┘                │             │
                                        │             │
                               ┌────────▼─────────────┘
                               │   Sync Service       │
                               └──────────────────────┘
```

### 1.4 Security Principles

1. **Defense in Depth**: Multiple layers of security controls
2. **Least Privilege**: Users receive minimum necessary permissions
3. **Zero Trust**: Verify every request, trust no connection implicitly
4. **Secure by Default**: All security features enabled by default
5. **Audit Everything**: Comprehensive logging of all authentication events

### 1.5 Technology Stack

| Component | Technology | Purpose |
|-----------|------------|---------|
| API Gateway | FastAPI | Primary authentication entry point |
| Identity Provider | Custom OAuth 2.0 | Token issuance and validation |
| Session Store | Redis | Distributed session management |
| User Store | PostgreSQL | Persistent user data storage |
| Password Hashing | Argon2 | Secure password storage |
| Token Signing | RS256 (RSA) | JWT token signing |
| MFA | TOTP/SMS/Email | Multi-factor authentication |

---

## 2. Authentication Strategies

### 2.1 Password-Based Authentication

#### 2.1.1 Overview

Password-based authentication is the primary method for user login. It implements secure password hashing, breach detection, and account lockout policies.

#### 2.1.2 Security Requirements

| Requirement | Implementation |
|-------------|----------------|
| Password Hashing | Argon2id with memory=64MB, iterations=3, parallelism=4 |
| Salt Generation | Cryptographically secure random 32 bytes |
| Breach Detection | Integration with Have I Been Pwned API |
| Account Lockout | 5 failed attempts = 15-minute lockout |
| Password History | Prevent reuse of last 12 passwords |

### 2.2 OAuth 2.0 / OpenID Connect

#### 2.2.1 Overview

OAuth 2.0 enables secure delegated access and third-party integrations. OpenID Connect provides identity layer on top of OAuth 2.0.

#### 2.2.2 Supported Flows

1. **Authorization Code Flow** - For server-side web applications
2. **Authorization Code with PKCE** - For mobile and SPA applications
3. **Client Credentials Flow** - For machine-to-machine communication
4. **Device Authorization Flow** - For IoT and input-constrained devices

### 2.3 SAML 2.0

#### 2.3.1 Overview

SAML 2.0 enables enterprise single sign-on (SSO) integration for corporate users.

#### 2.3.2 Supported Bindings

- HTTP Redirect Binding
- HTTP POST Binding
- Artifact Binding

### 2.4 Multi-Factor Authentication (MFA)

#### 2.4.1 Overview

MFA adds an additional layer of security by requiring multiple verification factors.

#### 2.4.2 Supported Methods

| Method | Priority | Use Case |
|--------|----------|----------|
| TOTP (Authenticator Apps) | Primary | Mobile users, admins |
| SMS OTP | Secondary | Backup method |
| Email OTP | Tertiary | Account recovery |
| Biometric | Experimental | Mobile app future feature |

---

## 3. JWT Implementation

### 3.1 Token Structure

Smart Dairy uses JSON Web Tokens (JWT) with the following structure:

```
┌─────────────────────────────────────────────────────────────────┐
│                     JWT TOKEN STRUCTURE                          │
└─────────────────────────────────────────────────────────────────┘

Header (Algorithm & Type)
{
  "alg": "RS256",
  "typ": "JWT",
  "kid": "smart-dairy-key-2026-01"
}

Payload (Claims)
{
  "sub": "user-uuid-12345",
  "iss": "smart-dairy-auth",
  "aud": "smart-dairy-api",
  "iat": 1706700000,
  "exp": 1706703600,
  "nbf": 1706700000,
  "jti": "unique-token-id",
  "scope": "read:herd write:milk-production",
  "roles": ["dairy_manager", "herd_supervisor"],
  "permissions": ["herd:read", "herd:write", "milk:read"],
  "tenant_id": "farm-001",
  "session_id": "session-uuid"
}

Signature (RS256)
RSASHA256(
  base64UrlEncode(header) + "." +
  base64UrlEncode(payload),
  private_key
)
```

### 3.2 Token Types

#### 3.2.1 Access Token

| Attribute | Value |
|-----------|-------|
| Type | JWT |
| Algorithm | RS256 |
| Expiration | 15 minutes |
| Contains | User identity, roles, permissions |
| Storage | Memory only (client-side) |

#### 3.2.2 Refresh Token

| Attribute | Value |
|-----------|-------|
| Type | Opaque token (random string) |
| Algorithm | HMAC-SHA256 |
| Expiration | 7 days (configurable) |
| Contains | Token ID only |
| Storage | Redis (server-side) |
| Rotation | Required on every use |

#### 3.2.3 ID Token (OIDC)

| Attribute | Value |
|-----------|-------|
| Type | JWT |
| Algorithm | RS256 |
| Expiration | 1 hour |
| Contains | User profile claims |

### 3.3 Token Validation

```python
"""
JWT Token Validation Rules
"""
VALIDATION_RULES = {
    "signature": {
        "algorithm": "RS256",
        "allowed_algorithms": ["RS256"],
        "reject_none": True,
    },
    "claims": {
        "required": ["sub", "iss", "aud", "exp", "iat"],
        "issuer": "smart-dairy-auth",
        "audience": "smart-dairy-api",
        "verify_exp": True,
        "verify_iat": True,
        "verify_nbf": True,
        "leeway_seconds": 30,
    },
    "custom": {
        "check_revocation": True,
        "verify_session_active": True,
    }
}
```

### 3.4 Refresh Token Flow

```
┌─────────┐                                    ┌──────────────┐
│  Client │                                    │  Auth Server │
└────┬────┘                                    └──────┬───────┘
     │                                                │
     │  1. POST /token/refresh                        │
     │  { refresh_token: "xxx" }                      │
     ├───────────────────────────────────────────────►│
     │                                                │
     │                                                │ 2. Validate refresh token
     │                                                │    (check Redis storage)
     │                                                │
     │                                                │ 3. Check if token is revoked
     │                                                │
     │                                                │ 4. Generate new token pair
     │                                                │    (rotate refresh token)
     │                                                │
     │  5. Return new tokens                          │    Store new refresh token
     │  { access_token, refresh_token }              │    Invalidate old refresh token
     │◄───────────────────────────────────────────────┤
     │                                                │
```

---

## 4. OAuth 2.0 Flows

### 4.1 Authorization Code Flow

#### 4.1.1 Flow Diagram

```
┌─────────┐                                    ┌─────────────┐      ┌─────────────┐
│  User   │                                    │   Client    │      │Auth Server  │
└────┬────┘                                    └──────┬──────┘      └──────┬──────┘
     │                                                │                    │
     │ 1. Click "Login"                               │                    │
     ├───────────────────────────────────────────────►│                    │
     │                                                │                    │
     │                                                │ 2. Redirect to /authorize
     │                                                │  {client_id, redirect_uri,
     │◄───────────────────────────────────────────────┤   scope, state}
     │                                                │                    │
     │ 3. GET /authorize                              │                    │
     ├─────────────────────────────────────────────────────────────────────►│
     │                                                │                    │
     │ 4. Login page shown, User enters credentials   │                    │
     │◄─────────────────────────────────────────────────────────────────────┤
     │                                                │                    │
     │ 5. POST /authorize (with credentials)          │                    │
     ├─────────────────────────────────────────────────────────────────────►│
     │                                                │                    │
     │                                                │                    │ 6. Validate
     │                                                │                    │    credentials
     │                                                │                    │
     │ 7. Redirect with authorization code            │                    │
     │◄─────────────────────────────────────────────────────────────────────┤
     │  {code, state}                                 │                    │
     │                                                │                    │
     │ 8. GET /callback                               │                    │
     ├───────────────────────────────────────────────►│                    │
     │                                                │                    │
     │                                                │ 9. POST /token     │
     │                                                │  {code, client_id,
     │                                                │   client_secret,
     │                                                │   redirect_uri}
     │                                                ├────────────────────►│
     │                                                │                    │
     │                                                │                    │ 10. Validate
     │                                                │                    │     code
     │                                                │                    │
     │                                                │ 11. Return tokens  │
     │                                                │◄────────────────────┤
     │                                                │  {access_token,
     │                                                │   refresh_token,
     │                                                │   id_token}
     │                                                │                    │
     │ 12. User authenticated, session started        │                    │
     │◄───────────────────────────────────────────────┤                    │
```

#### 4.1.2 Implementation Parameters

```python
AUTHORIZATION_CODE_CONFIG = {
    "authorization_endpoint": "/oauth/authorize",
    "token_endpoint": "/oauth/token",
    "code_lifetime": 600,  # 10 minutes
    "pkce_required": True,
    "pkce_methods": ["S256"],
    "allowed_response_types": ["code"],
    "allowed_grant_types": ["authorization_code"],
}
```

### 4.2 PKCE Flow (for Mobile/SPA)

#### 4.2.1 Overview

Proof Key for Code Exchange (PKCE) prevents authorization code interception attacks.

#### 4.2.2 PKCE Parameters

```python
PKCE_CONFIG = {
    "code_challenge_methods_supported": ["S256"],
    "code_verifier_length": 128,
    "code_challenge_method": "S256",  # SHA256
}
```

#### 4.2.3 PKCE Flow

```
Step 1: Client generates code_verifier
  code_verifier = random_string(128)

Step 2: Client creates code_challenge
  code_challenge = BASE64URL(SHA256(code_verifier))

Step 3: Authorization Request
  GET /oauth/authorize?
    response_type=code&
    client_id=mobile_app&
    redirect_uri=app://callback&
    scope=read write&
    state=xyz&
    code_challenge=abc123...&
    code_challenge_method=S256

Step 4: Token Request (after receiving code)
  POST /oauth/token
  {
    grant_type: "authorization_code",
    code: "received_code",
    redirect_uri: "app://callback",
    client_id: "mobile_app",
    code_verifier: "original_code_verifier"
  }

Step 5: Server validates
  computed_challenge = BASE64URL(SHA256(code_verifier))
  assert computed_challenge == stored_code_challenge
```

### 4.3 Client Credentials Flow

#### 4.3.1 Use Cases

- Machine-to-machine communication
- IoT device authentication
- Backend service communication
- Automated reporting systems

#### 4.3.2 Implementation

```python
CLIENT_CREDENTIALS_CONFIG = {
    "allowed_for": ["service_account", "iot_device", "integration"],
    "token_lifetime": 3600,  # 1 hour
    "scope_restriction": True,  # Only allowed scopes
    "allowed_scopes": ["system:read", "system:write", "data:sync"],
}
```

### 4.4 Device Authorization Flow

#### 4.4.1 Use Cases

- IoT sensors with limited input
- Smart dairy equipment
- Display-less devices

#### 4.4.2 Flow

```
Step 1: Device requests authorization
  POST /oauth/device/code
  { client_id: "iot_sensor_001" }

Response:
  {
    device_code: "abc123",
    user_code: "ABCD-EFGH",
    verification_uri: "https://smartdairy.com/device",
    verification_uri_complete: "https://smartdairy.com/device?user_code=ABCD-EFGH",
    expires_in: 1800,
    interval: 5
  }

Step 2: Device displays user_code to user
  "Please visit https://smartdairy.com/device and enter code: ABCD-EFGH"

Step 3: User visits URL, logs in, authorizes device

Step 4: Device polls for tokens
  POST /oauth/token
  {
    grant_type: "urn:ietf:params:oauth:grant-type:device_code",
    device_code: "abc123",
    client_id: "iot_sensor_001"
  }

Step 5: Server responds with tokens once authorized
  {
    access_token: "eyJ...",
    token_type: "Bearer",
    expires_in: 3600,
    refresh_token: "def456"
  }
```

---

## 5. Multi-Factor Authentication

### 5.1 MFA Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    MFA ARCHITECTURE                              │
└─────────────────────────────────────────────────────────────────┘

┌──────────────┐     ┌──────────────┐     ┌──────────────────────┐
│  User Login  │────►│ Password OK? │────►│ MFA Required?        │
│              │     │              │     │  - Admin: Always     │
└──────────────┘     └──────────────┘     │  - Sensitive: Always │
                                          │  - User pref: Config │
                                          └──────────┬───────────┘
                                                     │
                              ┌──────────────────────┼──────────────────────┐
                              │                      │                      │
                    ┌─────────▼─────────┐ ┌──────────▼──────────┐ ┌────────▼────────┐
                    │    TOTP (App)     │ │    SMS (Phone)      │ │  Email OTP      │
                    │  ┌─────────────┐  │ │  ┌─────────────┐    │ │  ┌───────────┐  │
                    │  │ Google Auth │  │ │  │ Twilio API  │    │ │  │ SendGrid  │  │
                    │  │ MS Auth     │  │ │  │ Vonage API  │    │ │  │ SMTP      │  │
                    │  │ FreeOTP     │  │ │  └─────────────┘    │ │  └───────────┘  │
                    │  └─────────────┘  │ │                     │ │                 │
                    └─────────┬─────────┘ └──────────┬──────────┘ └────────┬────────┘
                              │                      │                     │
                              └──────────────────────┼─────────────────────┘
                                                     │
                                          ┌──────────▼───────────┐
                                          │  Validate OTP Code   │
                                          └──────────┬───────────┘
                                                     │
                                          ┌──────────▼───────────┐
                                          │  Issue JWT Token     │
                                          └──────────────────────┘
```

### 5.2 TOTP (Time-based One-Time Password)

#### 5.2.1 Configuration

```python
TOTP_CONFIG = {
    "issuer_name": "Smart Dairy Ltd",
    "algorithm": "SHA1",  # Standard compatibility
    "digits": 6,
    "interval": 30,  # 30-second windows
    "clock_skew_seconds": 30,  # Allow 1 window skew
    "backup_codes_count": 10,
    "backup_code_length": 8,
}
```

#### 5.2.2 Setup Flow

```
Step 1: User initiates MFA setup
Step 2: Server generates secret
  secret = base32_random(20)  # 160 bits

Step 3: Generate QR Code URI
  otpauth://totp/Smart%20Dairy:user@example.com?
    secret=JBSWY3DPEHPK3PXP&
    issuer=Smart%20Dairy%20Ltd&
    algorithm=SHA1&
    digits=6&
    period=30

Step 4: User scans QR code with authenticator app
Step 5: User enters verification code
Step 6: Server validates and activates MFA
```

### 5.3 SMS OTP

#### 5.3.1 Configuration

```python
SMS_CONFIG = {
    "provider": "twilio",  # or "vonage", "aws_sns"
    "code_length": 6,
    "code_expiry": 300,  # 5 minutes
    "rate_limit": {
        "max_per_hour": 5,
        "max_per_day": 10,
    },
    "template": "Your Smart Dairy verification code is: {code}. Valid for 5 minutes.",
}
```

### 5.4 Email OTP

#### 5.4.1 Configuration

```python
EMAIL_OTP_CONFIG = {
    "code_length": 6,
    "code_expiry": 600,  # 10 minutes
    "rate_limit": {
        "max_per_hour": 3,
        "max_per_day": 5,
    },
    "template_id": "mfa_otp_email",
}
```

### 5.5 MFA Policy Matrix

| User Role | TOTP | SMS | Email | Biometric | Backup Codes |
|-----------|------|-----|-------|-----------|--------------|
| System Admin | Required | Optional | - | - | Required |
| Farm Manager | Required | Optional | - | - | Required |
| Herd Supervisor | Optional | Optional | Optional | - | Optional |
| Veterinarian | Optional | Optional | Optional | - | Optional |
| Data Analyst | Optional | Optional | - | - | Optional |
| IoT Device | - | - | - | - | Device Cert |

---

## 6. Role-Based Access Control (RBAC)

### 6.1 RBAC Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    RBAC HIERARCHY                                │
└─────────────────────────────────────────────────────────────────┘

                            ┌─────────────┐
                            │ SUPER_ADMIN │
                            └──────┬──────┘
                                   │
          ┌────────────────────────┼────────────────────────┐
          │                        │                        │
   ┌──────▼──────┐         ┌───────▼───────┐       ┌────────▼──────┐
   │ FARM_ADMIN  │         │  IT_ADMIN     │       │ REPORT_ADMIN  │
   └──────┬──────┘         └───────────────┘       └───────────────┘
          │
    ┌─────┼─────┬──────────┐
    │     │     │          │
┌───▼──┐┌─▼───┐┌▼────┐ ┌───▼────┐
│HERD  ││MILK ││HEALTH││FINANCE │
│MGR   ││MGR  ││MGR   ││MGR     │
└──┬───┘└─┬───┘└─┬───┘ └───┬────┘
   │      │      │         │
┌──▼───┐┌─▼───┐┌─▼──┐ ┌────▼───┐
│HERD  ││MILK ││VET ││ACCOUNT │
│SUPER ││TECH ││     ││ANT      │
└──────┘└─────┘└────┘ └────────┘
```

### 6.2 Role Definitions

| Role ID | Role Name | Description | Inherits From |
|---------|-----------|-------------|---------------|
| R001 | SUPER_ADMIN | Full system access | - |
| R002 | FARM_ADMIN | Farm management | - |
| R003 | IT_ADMIN | System configuration | - |
| R004 | REPORT_ADMIN | Report management | - |
| R005 | HERD_MANAGER | Herd operations | - |
| R006 | MILK_MANAGER | Milk production | - |
| R007 | HEALTH_MANAGER | Animal health | - |
| R008 | FINANCE_MANAGER | Financial operations | - |
| R009 | HERD_SUPERVISOR | Daily herd tasks | HERD_MANAGER |
| R010 | MILK_TECHNICIAN | Milk handling | MILK_MANAGER |
| R011 | VETERINARIAN | Veterinary care | HEALTH_MANAGER |
| R012 | ACCOUNTANT | Financial records | FINANCE_MANAGER |
| R013 | DATA_ANALYST | Analytics access | - |
| R014 | GUEST | Read-only access | - |

### 6.3 Permission Matrix

| Resource | Action | HERD_MGR | MILK_MGR | HEALTH_MGR | FINANCE_MGR | DATA_ANALYST |
|----------|--------|----------|----------|------------|-------------|--------------|
| cattle | read | ✓ | ✓ | ✓ | ✗ | ✓ |
| cattle | create | ✓ | ✗ | ✓ | ✗ | ✗ |
| cattle | update | ✓ | ✗ | ✓ | ✗ | ✗ |
| cattle | delete | ✓ | ✗ | ✗ | ✗ | ✗ |
| milk_production | read | ✓ | ✓ | ✗ | ✓ | ✓ |
| milk_production | create | ✗ | ✓ | ✗ | ✗ | ✗ |
| health_record | read | ✓ | ✗ | ✓ | ✗ | ✓ |
| health_record | create | ✗ | ✗ | ✓ | ✗ | ✗ |
| breeding | read | ✓ | ✗ | ✓ | ✗ | ✓ |
| breeding | create | ✓ | ✗ | ✓ | ✗ | ✗ |
| financial | read | ✗ | ✗ | ✗ | ✓ | ✓ |
| financial | create | ✗ | ✗ | ✗ | ✓ | ✗ |
| reports | read | ✓ | ✓ | ✓ | ✓ | ✓ |
| users | read | ✗ | ✗ | ✗ | ✗ | ✗ |

### 6.4 Permission Format

Permissions follow the format: `{resource}:{action}:{scope}`

```
Examples:
  cattle:read:own          - Read own farm's cattle
  cattle:read:all          - Read all cattle (super admin)
  milk_production:write:own - Write milk production for own farm
  reports:read:*           - Read all reports
```

---

## 7. Attribute-Based Access Control (ABAC)

### 7.1 ABAC Overview

ABAC provides dynamic authorization based on attributes of:
- **Subject** (user): role, department, clearance level
- **Resource**: farm_id, sensitive_level, owner
- **Action**: read, write, delete, approve
- **Environment**: time, location, device trust

### 7.2 ABAC Policy Examples

```python
ABAC_POLICIES = [
    {
        "name": "Business Hours Access",
        "description": "Sensitive operations only during business hours",
        "effect": "deny",
        "conditions": {
            "resource.sensitivity": "high",
            "environment.time": {
                "not_in_range": ["08:00", "18:00"]
            }
        }
    },
    {
        "name": "Farm Boundary Enforcement",
        "description": "Users can only access their assigned farm's data",
        "effect": "allow",
        "conditions": {
            "subject.farm_id": "== resource.farm_id"
        }
    },
    {
        "name": "Vet Emergency Override",
        "description": "Veterinarians can access health records outside hours for emergencies",
        "effect": "allow",
        "conditions": {
            "subject.role": "veterinarian",
            "action": "read",
            "resource.type": "health_record",
            "context.emergency": True
        }
    },
    {
        "name": "Admin IP Restriction",
        "description": "Admin access only from office network",
        "effect": "deny",
        "conditions": {
            "subject.role": "admin",
            "environment.ip": {
                "not_in_cidr": ["10.0.0.0/8", "192.168.1.0/24"]
            }
        }
    }
]
```

### 7.3 Policy Evaluation Engine

```
┌─────────────────────────────────────────────────────────────────┐
│                ABAC POLICY EVALUATION FLOW                       │
└─────────────────────────────────────────────────────────────────┘

Request: User wants to update cattle record #123

┌─────────────────────────────────────────────────────────────────┐
│ 1. Collect Attributes                                            │
├─────────────────────────────────────────────────────────────────┤
│ Subject:  {user_id: "u123", role: "herd_manager", farm_id: "f1"} │
│ Resource: {record_id: "123", type: "cattle", farm_id: "f1",       │
│           owner: "u456", sensitivity: "normal"}                  │
│ Action:   "update"                                               │
│ Env:      {time: "14:30", ip: "10.0.1.50", device_trust: "high"} │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│ 2. Evaluate RBAC (Static Check)                                  │
├─────────────────────────────────────────────────────────────────┤
│ Does herd_manager have cattle:update permission?                 │
│ Result: YES (via role permission)                                │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│ 3. Evaluate ABAC Policies (Dynamic Check)                        │
├─────────────────────────────────────────────────────────────────┤
│ Policy 1: Business Hours - PASSED (14:30 is within range)        │
│ Policy 2: Farm Boundary - PASSED (user.farm_id == resource.farm) │
│ Policy 3: Emergency Override - N/A                               │
│ Policy 4: IP Restriction - PASSED (IP in allowed range)          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│ 4. Final Decision                                                │
├─────────────────────────────────────────────────────────────────┤
│ RBAC: ALLOW                                                      │
│ ABAC: ALL POLICIES PASSED                                        │
│ RESULT: ACCESS GRANTED                                           │
└─────────────────────────────────────────────────────────────────┘
```

---

## 8. Session Management

### 8.1 Session Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                  SESSION ARCHITECTURE                            │
└─────────────────────────────────────────────────────────────────┘

┌─────────────┐     ┌─────────────────────────────────────────────────────┐
│   Client    │     │                    Server                            │
│             │     │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │
│  Access     │────►│  │   FastAPI   │  │   Redis     │  │  PostgreSQL │ │
│  Token      │     │  │   Gateway   │  │   Session   │  │   User DB   │ │
│  (Memory)   │     │  │             │  │   Store     │  │             │ │
│             │     │  │  Validates  │  │             │  │             │ │
│  Refresh    │     │  │  JWT Token  │  │  Session    │  │  Persistent │ │
│  Token      │────►│  │  (Stateless)│  │  State      │  │  User Data  │ │
│  (Secure    │     │  │             │  │             │  │             │ │
│   HttpOnly) │     │  │  Checks     │  │  - Session  │  │             │ │
│             │     │  │  Session    │──│    Status   │  │             │ │
│  Session    │     │  │  (Stateful) │  │  - Device   │  │             │ │
│  ID Cookie  │────►│  │             │  │    Info     │  │             │ │
│             │     │  └─────────────┘  │  - Revoked  │  │             │ │
│             │     │                   │    Tokens   │  │             │ │
│             │     │                   └─────────────┘  └─────────────┘ │
└─────────────┘     └─────────────────────────────────────────────────────┘
```

### 8.2 Session Lifecycle

```
┌──────────────┐
│   SESSION    │
│   STATES     │
└──────┬───────┘
       │
       ▼
┌──────────────┐    ┌─────────────────────────────────────────────────────┐
│   ACTIVE     │    │ - Access token valid (15 min)                       │
│              │    │ - Refresh token valid (7 days)                      │
│              │    │ - Session marked active in Redis                    │
│              │    │ - User can access all authorized resources          │
└──────┬───────┘    └─────────────────────────────────────────────────────┘
       │
       │ Idle timeout / Expiry
       ▼
┌──────────────┐    ┌─────────────────────────────────────────────────────┐
│   REFRESH    │    │ - Access token expired                              │
│              │    │ - Refresh token still valid                         │
│              │    │ - User can request new access token                 │
│              │    │ - Session remains active                            │
└──────┬───────┘    └─────────────────────────────────────────────────────┘
       │
       │ User logout / Admin revoke
       ▼
┌──────────────┐    ┌─────────────────────────────────────────────────────┐
│  REVOKED     │    │ - Session explicitly terminated                     │
│              │    │ - All tokens invalidated                            │
│              │    │ - User must re-authenticate                         │
│              │    │ - Entry added to token blacklist                    │
└──────┬───────┘    └─────────────────────────────────────────────────────┘
       │
       │ 30 days inactivity
       ▼
┌──────────────┐    ┌─────────────────────────────────────────────────────┐
│   EXPIRED    │    │ - Refresh token expired                             │
│              │    │ - Session cleaned up automatically                  │
│              │    │ - User must re-authenticate                         │
│              │    │ - Session record removed from Redis                 │
└──────────────┘    └─────────────────────────────────────────────────────┘
```

### 8.3 Session Configuration

```python
SESSION_CONFIG = {
    "access_token_ttl": 900,  # 15 minutes
    "refresh_token_ttl": 604800,  # 7 days
    "session_absolute_timeout": 2592000,  # 30 days
    "idle_timeout": 1800,  # 30 minutes
    "max_concurrent_sessions": 5,
    "single_session_per_device": False,
    "invalidate_on_password_change": True,
    "invalidate_on_security_event": True,
    "session_binding": {
        "ip_check": False,  # Too strict for mobile
        "device_fingerprint": True,
        "geo_location": "warn_only",
    }
}
```

### 8.4 Concurrent Session Management

```python
CONCURRENT_SESSION_POLICY = {
    "max_sessions_per_user": 5,
    "strategy": "oldest_first",  # or "deny_new"
    "notification": True,  # Notify user of new session
    "device_info_required": True,
}
```

---

## 9. Password Policies

### 9.1 Password Requirements

```python
PASSWORD_POLICY = {
    "minimum_length": 12,
    "maximum_length": 128,
    "complexity": {
        "uppercase": 1,
        "lowercase": 1,
        "digits": 1,
        "special_chars": 1,
        "special_char_set": "!@#$%^&*()_+-=[]{}|;:,.<>?"
    },
    "restrictions": {
        "no_username": True,
        "no_common_passwords": True,
        "no_personal_info": True,  # No names, birthdates
        "no_repeated_chars": 3,  # Max 3 consecutive same chars
        "no_sequential_chars": 4,  # No 1234, abcd, etc.
    },
    "history": {
        "prevent_reuse_count": 12,
        "history_duration_days": 365,
    },
    "rotation": {
        "mandatory_days": None,  # No forced rotation (NIST guidance)
        "maximum_days": 365,  # Soft warning
    }
}
```

### 9.2 Password Hashing (Argon2)

```python
ARGON2_CONFIG = {
    "time_cost": 3,  # iterations
    "memory_cost": 65536,  # 64 MB
    "parallelism": 4,
    "hash_len": 32,
    "salt_len": 32,
    "type": "argon2id",  # Most secure variant
}
```

### 9.3 Breach Detection

```python
BREACH_DETECTION_CONFIG = {
    "enabled": True,
    "provider": "haveibeenpwned",  # k-anonymity API
    "action_on_breach": "force_reset",  # or "warn"
    "check_on_registration": True,
    "check_on_login": True,
    "check_on_password_change": True,
}
```

---

## 10. API Authentication

### 10.1 Authentication Methods

| Method | Use Case | Security Level |
|--------|----------|----------------|
| JWT Bearer | User API access | High |
| API Key | Service-to-service | Medium-High |
| mTLS | High-security integrations | Very High |
| OAuth 2.0 | Third-party integrations | High |

### 10.2 API Key Authentication

```python
API_KEY_CONFIG = {
    "key_format": "sd_{prefix}_{random}",  # sd_prod_a1b2c3d4e5f6
    "prefix_length": 8,
    "random_length": 32,
    "hash_algorithm": "sha256",
    "rate_limiting": {
        "tier_free": "100/hour",
        "tier_basic": "1000/hour",
        "tier_premium": "10000/hour",
        "tier_enterprise": "unlimited",
    }
}
```

### 10.3 Mutual TLS (mTLS)

```python
MTLS_CONFIG = {
    "enabled_for": ["iot_devices", "partner_integrations"],
    "verify_mode": "CERT_REQUIRED",
    "certificate_authority": "/certs/ca-chain.pem",
    "crl_check": True,
    "ocsp_check": False,  # Optional enhancement
    "client_cert_mapping": {
        "field": "subject_common_name",
        "map_to": "device_id",
    }
}
```

### 10.4 API Gateway Authentication Flow

```
┌─────────┐                                      ┌─────────────┐
│ Client  │                                      │ API Gateway │
└────┬────┘                                      └──────┬──────┘
     │                                                  │
     │ 1. Request with Authorization header             │
     ├─────────────────────────────────────────────────►│
     │                                                  │
     │                                                  │ 2. Extract auth method
     │                                                  │    (Bearer/API Key/mTLS)
     │                                                  │
     │                                                  │ 3. Route to auth handler
     │                                                  │
     │                                                  │ 4. Validate credentials
     │                                                  │    - JWT: verify signature
     │                                                  │    - API Key: lookup & check
     │                                                  │    - mTLS: verify certificate
     │                                                  │
     │                                                  │ 5. Load user context
     │                                                  │    - Roles, permissions
     │                                                  │    - Rate limit quota
     │                                                  │
     │                                                  │ 6. Check rate limits
     │                                                  │
     │                                                  │ 7. RBAC/ABAC check
     │                                                  │    - Required permissions?
     │                                                  │    - Resource access?
     │                                                  │
     │ 8. Forward to backend service                    │
     │    (with user context headers)                   │
     │                                                  │
```

---

## 11. Implementation Examples


### 11.1 Project Structure

```
smart_dairy_auth/
├── app/
│   ├── __init__.py
│   ├── main.py                    # FastAPI application entry
│   ├── config.py                  # Configuration management
│   ├── core/
│   │   ├── __init__.py
│   │   ├── security.py            # Core security utilities
│   │   ├── jwt_handler.py         # JWT token handling
│   │   ├── password.py            # Password hashing
│   │   └── exceptions.py          # Custom exceptions
│   ├── auth/
│   │   ├── __init__.py
│   │   ├── router.py              # Auth endpoints
│   │   ├── oauth2.py              # OAuth 2.0 server
│   │   ├── mfa.py                 # MFA handlers
│   │   ├── models.py              # Auth data models
│   │   └── dependencies.py        # Auth dependencies
│   ├── rbac/
│   │   ├── __init__.py
│   │   ├── models.py              # Role/Permission models
│   │   ├── service.py             # RBAC logic
│   │   ├── middleware.py          # RBAC middleware
│   │   └── permissions.py         # Permission definitions
│   ├── api/
│   │   ├── __init__.py
│   │   └── v1/
│   │       ├── cattle.py          # Protected endpoints
│   │       ├── milk.py
│   │       └── users.py
│   ├── models/
│   │   ├── __init__.py
│   │   └── user.py                # SQLAlchemy models
│   └── services/
│       ├── redis_service.py       # Redis operations
│       └── email_service.py       # Email notifications
├── tests/
│   └── test_auth.py
├── requirements.txt
└── alembic/                       # Database migrations
```

### 11.2 Core Configuration (config.py)

```python
"""
Smart Dairy Authentication Configuration
"""
import os
from pathlib import Path
from pydantic import BaseSettings, Field
from typing import List, Optional


class SecuritySettings(BaseSettings):
    """Security-related configuration"""
    
    # JWT Configuration
    JWT_SECRET_KEY: str = Field(..., env="JWT_SECRET_KEY")
    JWT_PUBLIC_KEY: Optional[str] = Field(None, env="JWT_PUBLIC_KEY")
    JWT_PRIVATE_KEY: Optional[str] = Field(None, env="JWT_PRIVATE_KEY")
    JWT_ALGORITHM: str = "RS256"
    JWT_ACCESS_TOKEN_EXPIRE_MINUTES: int = 15
    JWT_REFRESH_TOKEN_EXPIRE_DAYS: int = 7
    JWT_ISSUER: str = "smart-dairy-auth"
    JWT_AUDIENCE: str = "smart-dairy-api"
    
    # Password Hashing (Argon2)
    ARGON2_TIME_COST: int = 3
    ARGON2_MEMORY_COST: int = 65536  # 64 MB
    ARGON2_PARALLELISM: int = 4
    ARGON2_HASH_LENGTH: int = 32
    ARGON2_SALT_LENGTH: int = 32
    
    # Session Configuration
    SESSION_TIMEOUT_MINUTES: int = 30
    MAX_CONCURRENT_SESSIONS: int = 5
    
    # MFA Configuration
    MFA_TOTP_ISSUER: str = "Smart Dairy Ltd"
    MFA_TOTP_DIGITS: int = 6
    MFA_TOTP_INTERVAL: int = 30
    MFA_BACKUP_CODES_COUNT: int = 10
    
    # OAuth 2.0
    OAUTH_CODE_EXPIRE_MINUTES: int = 10
    OAUTH_AUTHORIZATION_ENDPOINT: str = "/oauth/authorize"
    OAUTH_TOKEN_ENDPOINT: str = "/oauth/token"
    
    # API Keys
    API_KEY_PREFIX: str = "sd"
    API_KEY_LENGTH: int = 40
    
    # Rate Limiting
    RATE_LIMIT_DEFAULT: str = "100/hour"
    RATE_LIMIT_LOGIN: str = "5/minute"
    
    class Config:
        env_file = ".env"
        case_sensitive = True


class DatabaseSettings(BaseSettings):
    """Database configuration"""
    
    POSTGRES_HOST: str = "localhost"
    POSTGRES_PORT: int = 5432
    POSTGRES_USER: str = "smartdairy"
    POSTGRES_PASSWORD: str = Field(..., env="POSTGRES_PASSWORD")
    POSTGRES_DB: str = "smartdairy_auth"
    
    REDIS_HOST: str = "localhost"
    REDIS_PORT: int = 6379
    REDIS_PASSWORD: Optional[str] = None
    REDIS_DB: int = 0
    
    @property
    def database_url(self) -> str:
        return (
            f"postgresql+asyncpg://{self.POSTGRES_USER}:{self.POSTGRES_PASSWORD}"
            f"@{self.POSTGRES_HOST}:{self.POSTGRES_PORT}/{self.POSTGRES_DB}"
        )
    
    @property
    def redis_url(self) -> str:
        auth = f":{self.REDIS_PASSWORD}@" if self.REDIS_PASSWORD else ""
        return f"redis://{auth}{self.REDIS_HOST}:{self.REDIS_PORT}/{self.REDIS_DB}"


class Settings(BaseSettings):
    """Application settings"""
    
    APP_NAME: str = "Smart Dairy Auth Service"
    APP_VERSION: str = "1.0.0"
    DEBUG: bool = False
    
    security: SecuritySettings = SecuritySettings()
    database: DatabaseSettings = DatabaseSettings()


# Global settings instance
settings = Settings()
```

### 11.3 Password Hashing Implementation (core/password.py)

```python
"""
Password hashing utilities using Argon2
"""
import re
import secrets
import string
from typing import Tuple, List
from argon2 import PasswordHasher
from argon2.exceptions import VerifyMismatchError
import httpx

from app.config import settings


# Argon2 hasher instance with secure defaults
argon2_hasher = PasswordHasher(
    time_cost=settings.security.ARGON2_TIME_COST,
    memory_cost=settings.security.ARGON2_MEMORY_COST,
    parallelism=settings.security.ARGON2_PARALLELISM,
    hash_len=settings.security.ARGON2_HASH_LENGTH,
    salt_len=settings.security.ARGON2_SALT_LENGTH,
    type='ID',  # Argon2id - most secure variant
)


class PasswordValidator:
    """Validates password against security policies"""
    
    COMMON_PASSWORDS = {
        'password', '123456', '12345678', 'qwerty', 'abc123',
        'monkey', 'letmein', 'dragon', '111111', 'baseball',
        'iloveyou', 'trustno1', 'sunshine', 'princess', 'admin',
        'welcome', 'shadow', 'ashley', 'football', 'jesus',
        'michael', 'ninja', 'mustang', 'password1', '123456789',
        'adobe123', 'admin123', 'letmein1', 'photoshop', 'qazwsx'
    }
    
    @classmethod
    def validate(cls, password: str, username: str = None, 
                 email: str = None, full_name: str = None) -> Tuple[bool, List[str]]:
        """
        Validate password against all security policies
        
        Returns:
            Tuple of (is_valid, list_of_errors)
        """
        errors = []
        
        # Length checks
        if len(password) < 12:
            errors.append("Password must be at least 12 characters long")
        if len(password) > 128:
            errors.append("Password must not exceed 128 characters")
        
        # Complexity checks
        if not re.search(r'[A-Z]', password):
            errors.append("Password must contain at least one uppercase letter")
        if not re.search(r'[a-z]', password):
            errors.append("Password must contain at least one lowercase letter")
        if not re.search(r'\d', password):
            errors.append("Password must contain at least one digit")
        if not re.search(r'[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]', password):
            errors.append("Password must contain at least one special character")
        
        # Restrictions
        if username and username.lower() in password.lower():
            errors.append("Password cannot contain your username")
        if email and email.split('@')[0].lower() in password.lower():
            errors.append("Password cannot contain your email")
        if full_name:
            for part in full_name.lower().split():
                if len(part) > 2 and part in password.lower():
                    errors.append("Password cannot contain your name")
                    break
        
        # Common password check
        if password.lower() in cls.COMMON_PASSWORDS:
            errors.append("Password is too common. Please choose a unique password")
        
        # Sequential characters
        if re.search(r'(.)\1{2,}', password):  # 3+ repeated chars
            errors.append("Password cannot contain more than 2 consecutive identical characters")
        
        # Check for common sequences
        sequences = ['123', 'abc', 'qwe', 'password', 'admin']
        for seq in sequences:
            if seq in password.lower():
                errors.append(f"Password cannot contain common sequences like '{seq}'")
                break
        
        return len(errors) == 0, errors
    
    @staticmethod
    async def check_breach_database(password: str) -> Tuple[bool, int]:
        """
        Check if password exists in known breach databases using k-anonymity
        
        Returns:
            Tuple of (is_breached, breach_count)
        """
        import hashlib
        
        # SHA1 hash of password
        sha1_hash = hashlib.sha1(password.encode()).hexdigest().upper()
        prefix = sha1_hash[:5]
        suffix = sha1_hash[5:]
        
        try:
            async with httpx.AsyncClient() as client:
                response = await client.get(
                    f"https://api.pwnedpasswords.com/range/{prefix}",
                    headers={"Add-Padding": "true"},
                    timeout=5.0
                )
                response.raise_for_status()
                
                # Check if suffix exists in response
                for line in response.text.splitlines():
                    hash_suffix, count = line.split(':')
                    if hash_suffix == suffix:
                        return True, int(count)
                
                return False, 0
        except Exception:
            # If API fails, don't block registration
            return False, 0


class PasswordManager:
    """Manages password hashing and verification"""
    
    @staticmethod
    def hash_password(password: str) -> str:
        """Hash a password using Argon2id"""
        return argon2_hasher.hash(password)
    
    @staticmethod
    def verify_password(password: str, hash_string: str) -> bool:
        """Verify a password against its hash"""
        try:
            argon2_hasher.verify(hash_string, password)
            return True
        except VerifyMismatchError:
            return False
    
    @staticmethod
    def needs_rehash(hash_string: str) -> bool:
        """Check if password needs rehashing (parameters changed)"""
        return argon2_hasher.check_needs_rehash(hash_string)
    
    @staticmethod
    def generate_secure_password(length: int = 16) -> str:
        """Generate a cryptographically secure random password"""
        # Define character sets
        uppercase = string.ascii_uppercase
        lowercase = string.ascii_lowercase
        digits = string.digits
        special = "!@#$%^&*()_+-=[]{}|;:,.<>?"
        
        # Ensure at least one of each type
        password = [
            secrets.choice(uppercase),
            secrets.choice(lowercase),
            secrets.choice(digits),
            secrets.choice(special),
        ]
        
        # Fill remaining length with all characters
        all_chars = uppercase + lowercase + digits + special
        password += [secrets.choice(all_chars) for _ in range(length - 4)]
        
        # Shuffle to randomize positions
        secrets.SystemRandom().shuffle(password)
        
        return ''.join(password)


# Convenience functions
def hash_password(password: str) -> str:
    """Hash password (convenience function)"""
    return PasswordManager.hash_password(password)


def verify_password(password: str, hash_string: str) -> bool:
    """Verify password (convenience function)"""
    return PasswordManager.verify_password(password, hash_string)


async def validate_password(password: str, **kwargs) -> Tuple[bool, List[str]]:
    """Validate password (convenience function)"""
    return PasswordValidator.validate(password, **kwargs)
```

### 11.4 JWT Handler Implementation (core/jwt_handler.py)

```python
"""
JWT Token Generation and Validation
"""
import uuid
from datetime import datetime, timedelta
from typing import Optional, Dict, Any, List
from jose import JWTError, jwt
from pydantic import BaseModel

from app.config import settings


class TokenPayload(BaseModel):
    """JWT Token payload structure"""
    sub: str  # User ID (subject)
    iss: str  # Issuer
    aud: str  # Audience
    iat: datetime  # Issued at
    exp: datetime  # Expiration
    nbf: datetime  # Not before
    jti: str  # JWT ID (unique token identifier)
    scope: Optional[str] = None
    roles: List[str] = []
    permissions: List[str] = []
    tenant_id: Optional[str] = None
    session_id: Optional[str] = None
    type: str = "access"  # access, refresh, or id


class JWTHandler:
    """Handles JWT token operations"""
    
    def __init__(self):
        self.secret_key = settings.security.JWT_SECRET_KEY
        self.algorithm = settings.security.JWT_ALGORITHM
        self.issuer = settings.security.JWT_ISSUER
        self.audience = settings.security.JWT_AUDIENCE
        
        # Load RSA keys if using RS256
        if self.algorithm.startswith("RS"):
            self._load_rsa_keys()
    
    def _load_rsa_keys(self):
        """Load RSA private and public keys"""
        import os
        
        private_key_path = os.getenv("JWT_PRIVATE_KEY_PATH")
        public_key_path = os.getenv("JWT_PUBLIC_KEY_PATH")
        
        if private_key_path and os.path.exists(private_key_path):
            with open(private_key_path, "r") as f:
                self.private_key = f.read()
        else:
            self.private_key = settings.security.JWT_PRIVATE_KEY
        
        if public_key_path and os.path.exists(public_key_path):
            with open(public_key_path, "r") as f:
                self.public_key = f.read()
        else:
            self.public_key = settings.security.JWT_PUBLIC_KEY or self.private_key
    
    def create_access_token(
        self,
        user_id: str,
        roles: List[str],
        permissions: List[str],
        tenant_id: Optional[str] = None,
        session_id: Optional[str] = None,
        scope: Optional[str] = None,
        expires_delta: Optional[timedelta] = None,
        additional_claims: Optional[Dict[str, Any]] = None
    ) -> str:
        """
        Create a new access token
        
        Args:
            user_id: Unique user identifier
            roles: List of user roles
            permissions: List of user permissions
            tenant_id: Optional tenant/farm identifier
            session_id: Optional session identifier
            scope: OAuth scope string
            expires_delta: Custom expiration time
            additional_claims: Additional JWT claims
            
        Returns:
            Encoded JWT string
        """
        now = datetime.utcnow()
        
        if expires_delta:
            expire = now + expires_delta
        else:
            expire = now + timedelta(
                minutes=settings.security.JWT_ACCESS_TOKEN_EXPIRE_MINUTES
            )
        
        payload = {
            "sub": user_id,
            "iss": self.issuer,
            "aud": self.audience,
            "iat": now,
            "exp": expire,
            "nbf": now,
            "jti": str(uuid.uuid4()),
            "type": "access",
            "scope": scope or "",
            "roles": roles,
            "permissions": permissions,
            "tenant_id": tenant_id,
            "session_id": session_id,
        }
        
        if additional_claims:
            payload.update(additional_claims)
        
        # Use private key for signing if RS256
        key = self.private_key if self.algorithm.startswith("RS") else self.secret_key
        
        return jwt.encode(payload, key, algorithm=self.algorithm)
    
    def create_refresh_token(
        self,
        user_id: str,
        session_id: str,
        expires_delta: Optional[timedelta] = None
    ) -> str:
        """
        Create a new refresh token
        
        Args:
            user_id: Unique user identifier
            session_id: Session identifier for binding
            expires_delta: Custom expiration time
            
        Returns:
            Encoded JWT string
        """
        now = datetime.utcnow()
        
        if expires_delta:
            expire = now + expires_delta
        else:
            expire = now + timedelta(
                days=settings.security.JWT_REFRESH_TOKEN_EXPIRE_DAYS
            )
        
        payload = {
            "sub": user_id,
            "iss": self.issuer,
            "aud": self.audience,
            "iat": now,
            "exp": expire,
            "nbf": now,
            "jti": str(uuid.uuid4()),
            "type": "refresh",
            "session_id": session_id,
        }
        
        key = self.private_key if self.algorithm.startswith("RS") else self.secret_key
        
        return jwt.encode(payload, key, algorithm=self.algorithm)
    
    def decode_token(
        self,
        token: str,
        verify_exp: bool = True,
        token_type: Optional[str] = "access"
    ) -> Optional[TokenPayload]:
        """
        Decode and validate a JWT token
        
        Args:
            token: JWT string to decode
            verify_exp: Whether to verify expiration
            token_type: Expected token type (access/refresh/id)
            
        Returns:
            TokenPayload if valid, None otherwise
        """
        try:
            # Use public key for verification if RS256
            key = self.public_key if self.algorithm.startswith("RS") else self.secret_key
            
            payload = jwt.decode(
                token,
                key,
                algorithms=[self.algorithm],
                issuer=self.issuer,
                audience=self.audience,
                options={
                    "verify_exp": verify_exp,
                    "verify_iat": True,
                    "verify_nbf": True,
                }
            )
            
            # Verify token type if specified
            if token_type and payload.get("type") != token_type:
                return None
            
            # Convert timestamps to datetime
            for field in ["iat", "exp", "nbf"]:
                if field in payload:
                    payload[field] = datetime.utcfromtimestamp(payload[field])
            
            return TokenPayload(**payload)
            
        except JWTError:
            return None
    
    def get_token_expiry(self, token: str) -> Optional[datetime]:
        """Get token expiration datetime without full validation"""
        try:
            payload = jwt.get_unverified_claims(token)
            exp = payload.get("exp")
            if exp:
                return datetime.utcfromtimestamp(exp)
        except Exception:
            pass
        return None
    
    def get_token_jti(self, token: str) -> Optional[str]:
        """Get token unique identifier (JTI)"""
        try:
            payload = jwt.get_unverified_claims(token)
            return payload.get("jti")
        except Exception:
            return None


# Global JWT handler instance
jwt_handler = JWTHandler()


# Convenience functions
def create_access_token(**kwargs) -> str:
    """Create access token (convenience function)"""
    return jwt_handler.create_access_token(**kwargs)


def create_refresh_token(**kwargs) -> str:
    """Create refresh token (convenience function)"""
    return jwt_handler.create_refresh_token(**kwargs)


def decode_token(token: str, **kwargs) -> Optional[TokenPayload]:
    """Decode token (convenience function)"""
    return jwt_handler.decode_token(token, **kwargs)
```

### 11.5 OAuth 2.0 Server Implementation (auth/oauth2.py)

```python
"""
OAuth 2.0 Server Implementation
Supports: Authorization Code Flow, Client Credentials, Device Code
"""
import secrets
import hashlib
import base64
from datetime import datetime, timedelta
from typing import Optional, Dict, Any, List
from enum import Enum
from pydantic import BaseModel, Field
from fastapi import HTTPException, status

from app.config import settings
from app.core.jwt_handler import jwt_handler
from app.services.redis_service import redis_service


class GrantType(str, Enum):
    """OAuth 2.0 Grant Types"""
    AUTHORIZATION_CODE = "authorization_code"
    CLIENT_CREDENTIALS = "client_credentials"
    REFRESH_TOKEN = "refresh_token"
    DEVICE_CODE = "urn:ietf:params:oauth:grant-type:device_code"


class TokenType(str, Enum):
    """Token Types"""
    BEARER = "Bearer"
    MAC = "MAC"  # Not implemented


class OAuthClient(BaseModel):
    """OAuth 2.0 Client Registration"""
    client_id: str
    client_secret: Optional[str] = None
    client_name: str
    client_type: str  # "confidential" or "public"
    redirect_uris: List[str] = []
    allowed_grant_types: List[GrantType] = []
    allowed_scopes: List[str] = ["read", "write"]
    is_active: bool = True
    created_at: datetime = Field(default_factory=datetime.utcnow)


class AuthorizationCode(BaseModel):
    """Authorization Code Storage"""
    code: str
    client_id: str
    user_id: str
    redirect_uri: str
    scope: str
    code_challenge: Optional[str] = None
    code_challenge_method: Optional[str] = None
    expires_at: datetime
    used: bool = False


class DeviceCode(BaseModel):
    """Device Authorization Code"""
    device_code: str
    user_code: str
    client_id: str
    scope: str
    expires_at: datetime
    interval: int = 5
    status: str = "pending"  # pending, authorized, denied, expired
    user_id: Optional[str] = None


class TokenResponse(BaseModel):
    """OAuth 2.0 Token Response"""
    access_token: str
    token_type: str = "Bearer"
    expires_in: int
    refresh_token: Optional[str] = None
    scope: Optional[str] = None
    id_token: Optional[str] = None  # OIDC


class OAuth2Server:
    """OAuth 2.0 Authorization Server"""
    
    CODE_EXPIRY_MINUTES = 10
    DEVICE_CODE_EXPIRY_MINUTES = 30
    DEVICE_POLL_INTERVAL = 5
    
    def __init__(self):
        self.redis = redis_service
    
    def _generate_code(self) -> str:
        """Generate cryptographically secure authorization code"""
        return secrets.token_urlsafe(32)
    
    def _generate_user_code(self) -> str:
        """Generate user-friendly device code (e.g., ABCD-EFGH)"""
        # Generate 8 characters, format as XXXX-XXXX
        chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789"  # No confusing chars
        code = ''.join(secrets.choice(chars) for _ in range(8))
        return f"{code[:4]}-{code[4:]}"
    
    def _hash_code_verifier(self, verifier: str, method: str = "S256") -> str:
        """Hash PKCE code verifier"""
        if method == "S256":
            digest = hashlib.sha256(verifier.encode()).digest()
            return base64.urlsafe_b64encode(digest).rstrip(b'=').decode('ascii')
        return verifier  # Plaintext (not recommended)
    
    async def validate_client(
        self,
        client_id: str,
        client_secret: Optional[str] = None,
        grant_type: Optional[GrantType] = None
    ) -> OAuthClient:
        """
        Validate OAuth client credentials
        
        Args:
            client_id: Client identifier
            client_secret: Client secret (for confidential clients)
            grant_type: Requested grant type
            
        Returns:
            OAuthClient if valid
            
        Raises:
            HTTPException: If client is invalid
        """
        # In production, fetch from database
        client = await self._get_client_from_db(client_id)
        
        if not client or not client.is_active:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Invalid client",
                headers={"WWW-Authenticate": "Bearer"},
            )
        
        # Validate client secret for confidential clients
        if client.client_type == "confidential":
            if not client_secret or not secrets.compare_digest(
                client.client_secret or "", client_secret
            ):
                raise HTTPException(
                    status_code=status.HTTP_401_UNAUTHORIZED,
                    detail="Invalid client credentials",
                )
        
        # Validate grant type
        if grant_type and grant_type not in client.allowed_grant_types:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail="Grant type not allowed for this client",
            )
        
        return client
    
    async def create_authorization_code(
        self,
        client_id: str,
        user_id: str,
        redirect_uri: str,
        scope: str,
        code_challenge: Optional[str] = None,
        code_challenge_method: Optional[str] = "S256"
    ) -> str:
        """
        Create authorization code for Authorization Code Flow
        
        Args:
            client_id: OAuth client ID
            user_id: Authenticated user ID
            redirect_uri: Redirect URI
            scope: Requested scope
            code_challenge: PKCE code challenge
            code_challenge_method: PKCE method (S256 or plain)
            
        Returns:
            Authorization code string
        """
        code = self._generate_code()
        
        auth_code = AuthorizationCode(
            code=code,
            client_id=client_id,
            user_id=user_id,
            redirect_uri=redirect_uri,
            scope=scope,
            code_challenge=code_challenge,
            code_challenge_method=code_challenge_method,
            expires_at=datetime.utcnow() + timedelta(minutes=self.CODE_EXPIRY_MINUTES)
        )
        
        # Store in Redis with expiration
        await self.redis.setex(
            f"auth_code:{code}",
            int(timedelta(minutes=self.CODE_EXPIRY_MINUTES).total_seconds()),
            auth_code.json()
        )
        
        return code
    
    async def exchange_code_for_token(
        self,
        code: str,
        client_id: str,
        client_secret: Optional[str],
        redirect_uri: str,
        code_verifier: Optional[str] = None
    ) -> TokenResponse:
        """
        Exchange authorization code for access token
        
        Args:
            code: Authorization code
            client_id: Client ID
            client_secret: Client secret
            redirect_uri: Redirect URI (must match)
            code_verifier: PKCE code verifier
            
        Returns:
            TokenResponse with tokens
        """
        # Validate client
        client = await self.validate_client(client_id, client_secret)
        
        # Retrieve authorization code
        auth_code_data = await self.redis.get(f"auth_code:{code}")
        if not auth_code_data:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail="Invalid or expired authorization code"
            )
        
        auth_code = AuthorizationCode.parse_raw(auth_code_data)
        
        # Validate code hasn't been used
        if auth_code.used:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail="Authorization code already used"
            )
        
        # Validate expiration
        if datetime.utcnow() > auth_code.expires_at:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail="Authorization code expired"
            )
        
        # Validate client ID matches
        if auth_code.client_id != client_id:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail="Client ID mismatch"
            )
        
        # Validate redirect URI
        if auth_code.redirect_uri != redirect_uri:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail="Redirect URI mismatch"
            )
        
        # Validate PKCE code verifier
        if auth_code.code_challenge:
            if not code_verifier:
                raise HTTPException(
                    status_code=status.HTTP_400_BAD_REQUEST,
                    detail="Code verifier required"
                )
            
            computed_challenge = self._hash_code_verifier(
                code_verifier, 
                auth_code.code_challenge_method or "S256"
            )
            
            if not secrets.compare_digest(auth_code.code_challenge, computed_challenge):
                raise HTTPException(
                    status_code=status.HTTP_400_BAD_REQUEST,
                    detail="Invalid code verifier"
                )
        
        # Mark code as used (single use only)
        auth_code.used = True
        await self.redis.setex(
            f"auth_code:{code}",
            60,  # Keep for 1 minute for replay detection
            auth_code.json()
        )
        
        # Get user details
        user = await self._get_user_from_db(auth_code.user_id)
        
        # Create session
        session_id = await self._create_session(user.id)
        
        # Generate tokens
        access_token = jwt_handler.create_access_token(
            user_id=user.id,
            roles=user.roles,
            permissions=user.permissions,
            scope=auth_code.scope,
            session_id=session_id
        )
        
        refresh_token = jwt_handler.create_refresh_token(
            user_id=user.id,
            session_id=session_id
        )
        
        # Store refresh token hash
        await self._store_refresh_token(refresh_token, user.id, session_id)
        
        return TokenResponse(
            access_token=access_token,
            token_type="Bearer",
            expires_in=settings.security.JWT_ACCESS_TOKEN_EXPIRE_MINUTES * 60,
            refresh_token=refresh_token,
            scope=auth_code.scope
        )
    
    async def client_credentials_grant(
        self,
        client_id: str,
        client_secret: str,
        scope: Optional[str] = None
    ) -> TokenResponse:
        """
        Client Credentials Grant for machine-to-machine
        
        Args:
            client_id: Client ID
            client_secret: Client secret
            scope: Requested scope
            
        Returns:
            TokenResponse with access token (no refresh token)
        """
        client = await self.validate_client(
            client_id, 
            client_secret, 
            GrantType.CLIENT_CREDENTIALS
        )
        
        # Validate requested scope
        requested_scopes = set(scope.split()) if scope else set()
        allowed_scopes = set(client.allowed_scopes)
        
        if not requested_scopes.issubset(allowed_scopes):
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail="Invalid scope"
            )
        
        # Generate access token (no refresh token for client credentials)
        access_token = jwt_handler.create_access_token(
            user_id=f"client:{client_id}",
            roles=["service_account"],
            permissions=[f"{s}:execute" for s in requested_scopes],
            scope=" ".join(requested_scopes),
            expires_delta=timedelta(hours=1)  # Shorter for M2M
        )
        
        return TokenResponse(
            access_token=access_token,
            token_type="Bearer",
            expires_in=3600,
            scope=" ".join(requested_scopes)
        )
    
    async def refresh_token_grant(
        self,
        refresh_token: str,
        client_id: str,
        client_secret: Optional[str] = None,
        scope: Optional[str] = None
    ) -> TokenResponse:
        """
        Refresh Token Grant
        
        Args:
            refresh_token: Valid refresh token
            client_id: Client ID
            client_secret: Client secret (for confidential clients)
            scope: Requested scope (cannot exceed original)
            
        Returns:
            TokenResponse with new token pair
        """
        # Validate client
        client = await self.validate_client(client_id, client_secret)
        
        # Decode and validate refresh token
        token_payload = jwt_handler.decode_token(refresh_token, token_type="refresh")
        
        if not token_payload:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Invalid refresh token"
            )
        
        # Check if refresh token is revoked
        jti = jwt_handler.get_token_jti(refresh_token)
        is_revoked = await self.redis.exists(f"revoked_token:{jti}")
        
        if is_revoked:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Refresh token revoked"
            )
        
        # Get user details
        user = await self._get_user_from_db(token_payload.sub)
        
        # Validate session is still active
        session_active = await self._is_session_active(token_payload.session_id)
        
        if not session_active:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Session expired"
            )
        
        # Revoke old refresh token (token rotation)
        await self._revoke_token(jti, token_payload.exp)
        
        # Generate new token pair
        access_token = jwt_handler.create_access_token(
            user_id=user.id,
            roles=user.roles,
            permissions=user.permissions,
            session_id=token_payload.session_id,
            scope=scope
        )
        
        new_refresh_token = jwt_handler.create_refresh_token(
            user_id=user.id,
            session_id=token_payload.session_id
        )
        
        # Store new refresh token
        await self._store_refresh_token(
            new_refresh_token, 
            user.id, 
            token_payload.session_id
        )
        
        return TokenResponse(
            access_token=access_token,
            token_type="Bearer",
            expires_in=settings.security.JWT_ACCESS_TOKEN_EXPIRE_MINUTES * 60,
            refresh_token=new_refresh_token,
            scope=scope
        )
    
    async def initiate_device_flow(
        self,
        client_id: str,
        scope: Optional[str] = None
    ) -> Dict[str, Any]:
        """
        Initiate Device Authorization Flow
        
        Args:
            client_id: Client ID
            scope: Requested scope
            
        Returns:
            Device authorization response
        """
        client = await self.validate_client(client_id)
        
        device_code = secrets.token_urlsafe(32)
        user_code = self._generate_user_code()
        
        device_auth = DeviceCode(
            device_code=device_code,
            user_code=user_code,
            client_id=client_id,
            scope=scope or "",
            expires_at=datetime.utcnow() + timedelta(
                minutes=self.DEVICE_CODE_EXPIRY_MINUTES
            ),
            interval=self.DEVICE_POLL_INTERVAL
        )
        
        # Store device code
        await self.redis.setex(
            f"device_code:{device_code}",
            int(timedelta(minutes=self.DEVICE_CODE_EXPIRY_MINUTES).total_seconds()),
            device_auth.json()
        )
        
        # Store user code mapping
        await self.redis.setex(
            f"user_code:{user_code}",
            int(timedelta(minutes=self.DEVICE_CODE_EXPIRY_MINUTES).total_seconds()),
            device_code
        )
        
        return {
            "device_code": device_code,
            "user_code": user_code,
            "verification_uri": "https://smartdairy.com/device",
            "verification_uri_complete": f"https://smartdairy.com/device?user_code={user_code}",
            "expires_in": self.DEVICE_CODE_EXPIRY_MINUTES * 60,
            "interval": self.DEVICE_POLL_INTERVAL
        }
    
    # Helper methods (database interactions)
    async def _get_client_from_db(self, client_id: str) -> Optional[OAuthClient]:
        """Fetch OAuth client from database"""
        # Implementation depends on your database
        pass
    
    async def _get_user_from_db(self, user_id: str):
        """Fetch user from database"""
        # Implementation depends on your database
        pass
    
    async def _create_session(self, user_id: str) -> str:
        """Create new session"""
        # Implementation
        pass
    
    async def _is_session_active(self, session_id: str) -> bool:
        """Check if session is active"""
        # Implementation
        pass
    
    async def _store_refresh_token(self, token: str, user_id: str, session_id: str):
        """Store refresh token hash"""
        # Implementation
        pass
    
    async def _revoke_token(self, jti: str, exp: datetime):
        """Add token to revocation list"""
        ttl = int((exp - datetime.utcnow()).total_seconds())
        if ttl > 0:
            await self.redis.setex(f"revoked_token:{jti}", ttl, "1")


# Global OAuth2 server instance
oauth2_server = OAuth2Server()
```


### 11.6 Multi-Factor Authentication (auth/mfa.py)

```python
"""
Multi-Factor Authentication Implementation
Supports: TOTP, SMS OTP, Email OTP, Backup Codes
"""
import secrets
import base64
import hashlib
from datetime import datetime, timedelta
from typing import Optional, Tuple, List
from enum import Enum
from pydantic import BaseModel
import pyotp
import qrcode
import qrcode.image.svg
from io import BytesIO

from app.config import settings
from app.services.redis_service import redis_service


class MFAType(str, Enum):
    """MFA method types"""
    TOTP = "totp"
    SMS = "sms"
    EMAIL = "email"
    BACKUP_CODE = "backup_code"


class MFAStatus(str, Enum):
    """MFA verification status"""
    PENDING = "pending"
    VERIFIED = "verified"
    EXPIRED = "expired"
    FAILED = "failed"


class TOTPSetup(BaseModel):
    """TOTP setup response"""
    secret: str
    uri: str
    qr_code: str  # Base64 encoded SVG
    backup_codes: List[str]


class MFAVerification(BaseModel):
    """MFA verification request/response"""
    user_id: str
    mfa_type: MFAType
    code: str
    status: MFAStatus = MFAStatus.PENDING
    expires_at: Optional[datetime] = None


class MFAService:
    """Multi-Factor Authentication Service"""
    
    OTP_EXPIRY_MINUTES = 10
    MAX_ATTEMPTS = 5
    LOCKOUT_MINUTES = 15
    
    def __init__(self):
        self.redis = redis_service
    
    # ==================== TOTP Methods ====================
    
    def generate_totp_secret(self) -> str:
        """Generate new TOTP secret"""
        return pyotp.random_base32()
    
    def generate_totp_uri(
        self, 
        secret: str, 
        username: str,
        issuer: str = None
    ) -> str:
        """Generate TOTP URI for QR code"""
        totp = pyotp.TOTP(secret)
        return totp.provisioning_uri(
            name=username,
            issuer_name=issuer or settings.security.MFA_TOTP_ISSUER
        )
    
    def generate_qr_code(self, uri: str) -> str:
        """Generate QR code as base64 SVG"""
        factory = qrcode.image.svg.SvgImage
        qr = qrcode.make(uri, image_factory=factory)
        
        buffer = BytesIO()
        qr.save(buffer)
        svg_data = buffer.getvalue().decode()
        
        # Convert to base64 for embedding
        return base64.b64encode(svg_data.encode()).decode()
    
    def verify_totp(self, secret: str, code: str) -> bool:
        """
        Verify TOTP code
        
        Args:
            secret: User's TOTP secret
            code: Code to verify
            
        Returns:
            True if valid
        """
        totp = pyotp.TOTP(
            secret,
            digits=settings.security.MFA_TOTP_DIGITS,
            interval=settings.security.MFA_TOTP_INTERVAL
        )
        
        # Allow 1 interval before/after (clock skew tolerance)
        return totp.verify(code, valid_window=1)
    
    def generate_backup_codes(self, count: int = 10) -> List[str]:
        """
        Generate backup codes for account recovery
        
        Returns:
            List of plaintext backup codes
        """
        codes = []
        for _ in range(count):
            # 8 characters, alphanumeric
            code = ''.join(
                secrets.choice('ABCDEFGHJKLMNPQRSTUVWXYZ23456789') 
                for _ in range(8)
            )
            codes.append(code)
        return codes
    
    def hash_backup_code(self, code: str) -> str:
        """Hash backup code for storage"""
        return hashlib.sha256(code.encode()).hexdigest()
    
    async def setup_totp(self, user_id: str, username: str) -> TOTPSetup:
        """
        Setup TOTP for user
        
        Args:
            user_id: User identifier
            username: Username for QR code
            
        Returns:
            TOTPSetup with secret and QR code
        """
        secret = self.generate_totp_secret()
        uri = self.generate_totp_uri(secret, username)
        qr_code = self.generate_qr_code(uri)
        backup_codes = self.generate_backup_codes()
        
        # Store hashed backup codes
        hashed_codes = [self.hash_backup_code(c) for c in backup_codes]
        
        # Save to database (implementation needed)
        await self._save_totp_setup(user_id, secret, hashed_codes)
        
        return TOTPSetup(
            secret=secret,
            uri=uri,
            qr_code=qr_code,
            backup_codes=backup_codes  # Show once only!
        )
    
    # ==================== SMS OTP Methods ====================
    
    async def send_sms_otp(self, user_id: str, phone_number: str) -> bool:
        """
        Send SMS OTP
        
        Args:
            user_id: User identifier
            phone_number: E.164 formatted phone number
            
        Returns:
            True if sent successfully
        """
        # Check rate limits
        if not await self._check_rate_limit(user_id, MFAType.SMS):
            raise Exception("SMS rate limit exceeded")
        
        # Generate 6-digit code
        code = ''.join(secrets.choice('0123456789') for _ in range(6))
        
        # Store in Redis
        await self.redis.setex(
            f"mfa:sms:{user_id}",
            int(timedelta(minutes=self.OTP_EXPIRY_MINUTES).total_seconds()),
            code
        )
        
        # Send via SMS provider (Twilio, Vonage, etc.)
        await self._send_sms(phone_number, code)
        
        return True
    
    async def verify_sms_otp(self, user_id: str, code: str) -> bool:
        """Verify SMS OTP code"""
        stored_code = await self.redis.get(f"mfa:sms:{user_id}")
        
        if not stored_code:
            return False
        
        if stored_code == code:
            # Delete after successful verification
            await self.redis.delete(f"mfa:sms:{user_id}")
            return True
        
        return False
    
    # ==================== Email OTP Methods ====================
    
    async def send_email_otp(self, user_id: str, email: str) -> bool:
        """
        Send Email OTP
        
        Args:
            user_id: User identifier
            email: User email address
            
        Returns:
            True if sent successfully
        """
        # Check rate limits
        if not await self._check_rate_limit(user_id, MFAType.EMAIL):
            raise Exception("Email rate limit exceeded")
        
        # Generate 6-digit code
        code = ''.join(secrets.choice('0123456789') for _ in range(6))
        
        # Store in Redis
        await self.redis.setex(
            f"mfa:email:{user_id}",
            int(timedelta(minutes=self.OTP_EXPIRY_MINUTES).total_seconds()),
            code
        )
        
        # Send email
        await self._send_email_otp(email, code)
        
        return True
    
    async def verify_email_otp(self, user_id: str, code: str) -> bool:
        """Verify Email OTP code"""
        stored_code = await self.redis.get(f"mfa:email:{user_id}")
        
        if not stored_code:
            return False
        
        if stored_code == code:
            await self.redis.delete(f"mfa:email:{user_id}")
            return True
        
        return False
    
    # ==================== Backup Code Methods ====================
    
    async def verify_backup_code(self, user_id: str, code: str) -> bool:
        """
        Verify backup code
        
        Args:
            user_id: User identifier
            code: Backup code to verify
            
        Returns:
            True if valid and not used
        """
        hashed = self.hash_backup_code(code)
        
        # Check against stored hashed codes
        is_valid = await self._check_backup_code(user_id, hashed)
        
        if is_valid:
            # Mark as used
            await self._mark_backup_code_used(user_id, hashed)
            return True
        
        return False
    
    # ==================== Rate Limiting ====================
    
    async def _check_rate_limit(self, user_id: str, mfa_type: MFAType) -> bool:
        """Check if user has exceeded MFA rate limits"""
        key = f"mfa:ratelimit:{mfa_type.value}:{user_id}"
        
        # Get current count
        count = await self.redis.get(key)
        
        if mfa_type == MFAType.SMS:
            max_attempts = 5  # per hour
            window = 3600
        elif mfa_type == MFAType.EMAIL:
            max_attempts = 3  # per hour
            window = 3600
        else:
            return True
        
        if count and int(count) >= max_attempts:
            return False
        
        # Increment counter
        pipe = self.redis.pipeline()
        pipe.incr(key)
        pipe.expire(key, window)
        await pipe.execute()
        
        return True
    
    # ==================== Helper Methods ====================
    
    async def _save_totp_setup(self, user_id: str, secret: str, backup_codes: List[str]):
        """Save TOTP setup to database"""
        # Implementation depends on your database
        pass
    
    async def _send_sms(self, phone_number: str, code: str):
        """Send SMS via provider"""
        # Integration with Twilio, Vonage, etc.
        pass
    
    async def _send_email_otp(self, email: str, code: str):
        """Send OTP email"""
        # Integration with email service
        pass
    
    async def _check_backup_code(self, user_id: str, hashed_code: str) -> bool:
        """Check if backup code is valid"""
        # Implementation
        pass
    
    async def _mark_backup_code_used(self, user_id: str, hashed_code: str):
        """Mark backup code as used"""
        # Implementation
        pass


# Global MFA service instance
mfa_service = MFAService()
```

### 11.7 RBAC Middleware (rbac/middleware.py)

```python
"""
RBAC Middleware and Dependencies for FastAPI
"""
from functools import wraps
from typing import List, Optional, Callable, Union
from fastapi import Request, HTTPException, status, Depends
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials

from app.core.jwt_handler import jwt_handler, TokenPayload
from app.rbac.permissions import Permission, has_permission


# Security scheme for JWT
security_bearer = HTTPBearer(auto_error=False)


class RBACMiddleware:
    """
    RBAC Middleware for FastAPI
    
    Usage:
        @app.get("/cattle/{cattle_id}")
        @require_permissions([Permission.CATTLE_READ])
        async def get_cattle(cattle_id: str, user: CurrentUser):
            ...
    """
    
    def __init__(self, app=None):
        self.app = app
    
    async def __call__(self, request: Request, call_next):
        """Process request through RBAC middleware"""
        # Extract and validate JWT
        token = self._extract_token(request)
        
        if token:
            payload = jwt_handler.decode_token(token)
            if payload:
                request.state.user = payload
        
        response = await call_next(request)
        return response
    
    def _extract_token(self, request: Request) -> Optional[str]:
        """Extract JWT from Authorization header"""
        auth_header = request.headers.get("Authorization", "")
        
        if auth_header.startswith("Bearer "):
            return auth_header[7:]
        
        return None


async def get_current_user(
    credentials: HTTPAuthorizationCredentials = Depends(security_bearer)
) -> TokenPayload:
    """
    Dependency to get current authenticated user
    
    Args:
        credentials: Bearer token credentials
        
    Returns:
        TokenPayload with user information
        
    Raises:
        HTTPException: If authentication fails
    """
    if not credentials:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Authentication required",
            headers={"WWW-Authenticate": "Bearer"},
        )
    
    payload = jwt_handler.decode_token(credentials.credentials)
    
    if not payload:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid or expired token",
            headers={"WWW-Authenticate": "Bearer"},
        )
    
    return payload


# Type alias for dependency
CurrentUser = Depends(get_current_user)


def require_permissions(required_permissions: List[Union[str, Permission]]):
    """
    Decorator/Dependency factory to require specific permissions
    
    Usage:
        @app.post("/cattle")
        @require_permissions([Permission.CATTLE_CREATE])
        async def create_cattle(data: CattleCreate, user: CurrentUser):
            ...
    
    Or as dependency:
        @app.post("/cattle")
        async def create_cattle(
            data: CattleCreate,
            user: TokenPayload = Depends(require_permissions([Permission.CATTLE_CREATE]))
        ):
            ...
    """
    def checker(user: TokenPayload = Depends(get_current_user)) -> TokenPayload:
        # Convert Permission enums to strings
        perms = [p.value if isinstance(p, Permission) else p for p in required_permissions]
        
        # Check if user has all required permissions
        user_perms = set(user.permissions or [])
        
        for perm in perms:
            if perm not in user_perms:
                raise HTTPException(
                    status_code=status.HTTP_403_FORBIDDEN,
                    detail=f"Permission denied: {perm} required"
                )
        
        return user
    
    return checker


def require_roles(required_roles: List[str]):
    """
    Decorator/Dependency factory to require specific roles
    
    Usage:
        @app.get("/admin/dashboard")
        @require_roles(["admin", "super_admin"])
        async def admin_dashboard(user: CurrentUser):
            ...
    """
    def checker(user: TokenPayload = Depends(get_current_user)) -> TokenPayload:
        user_roles = set(user.roles or [])
        
        if not any(role in user_roles for role in required_roles):
            raise HTTPException(
                status_code=status.HTTP_403_FORBIDDEN,
                detail=f"Role required: one of {required_roles}"
            )
        
        return user
    
    return checker


def require_ownership(get_owner_id: Callable):
    """
    Decorator to require resource ownership
    
    Usage:
        @app.put("/cattle/{cattle_id}")
        @require_ownership(lambda cattle_id: get_cattle_owner(cattle_id))
        async def update_cattle(cattle_id: str, user: CurrentUser):
            ...
    """
    def decorator(func: Callable) -> Callable:
        @wraps(func)
        async def wrapper(*args, **kwargs):
            # Extract user from kwargs
            user = kwargs.get('user') or kwargs.get('current_user')
            
            if not user:
                raise HTTPException(
                    status_code=status.HTTP_401_UNAUTHORIZED,
                    detail="User not authenticated"
                )
            
            # Get owner ID of resource
            owner_id = await get_owner_id(*args, **kwargs)
            
            # Check ownership or admin role
            if user.sub != owner_id and "admin" not in (user.roles or []):
                raise HTTPException(
                    status_code=status.HTTP_403_FORBIDDEN,
                    detail="Access denied: not resource owner"
                )
            
            return await func(*args, **kwargs)
        
        return wrapper
    return decorator


class PermissionChecker:
    """
    Class-based permission checker for more complex scenarios
    """
    
    def __init__(
        self,
        permissions: Optional[List[str]] = None,
        roles: Optional[List[str]] = None,
        owner_field: Optional[str] = None,
        allow_admin: bool = True
    ):
        self.permissions = permissions or []
        self.roles = roles or []
        self.owner_field = owner_field
        self.allow_admin = allow_admin
    
    async def __call__(self, request: Request) -> TokenPayload:
        """Check permissions for request"""
        # Get authenticated user
        credentials = await security_bearer(request)
        user = await get_current_user(credentials)
        
        # Check role requirement
        if self.roles:
            user_roles = set(user.roles or [])
            if not any(role in user_roles for role in self.roles):
                if not (self.allow_admin and "admin" in user_roles):
                    raise HTTPException(
                        status_code=status.HTTP_403_FORBIDDEN,
                        detail="Insufficient role"
                    )
        
        # Check permission requirement
        if self.permissions:
            user_perms = set(user.permissions or [])
            if not all(p in user_perms for p in self.permissions):
                if not (self.allow_admin and "admin" in (user.roles or [])):
                    raise HTTPException(
                        status_code=status.HTTP_403_FORBIDDEN,
                        detail="Insufficient permissions"
                    )
        
        # Check ownership if specified
        if self.owner_field:
            # Extract owner from request path/body
            owner_id = request.path_params.get(self.owner_field) or \
                      request.query_params.get(self.owner_field)
            
            if owner_id and owner_id != user.sub:
                if not (self.allow_admin and "admin" in (user.roles or [])):
                    raise HTTPException(
                        status_code=status.HTTP_403_FORBIDDEN,
                        detail="Access denied"
                    )
        
        return user


# Convenience instances
def require_auth():
    """Require any authentication"""
    return Depends(get_current_user)


def require_admin():
    """Require admin role"""
    return Depends(require_roles(["admin", "super_admin"]))
```

### 11.8 RBAC Permissions Definition (rbac/permissions.py)

```python
"""
Permission Definitions for Smart Dairy RBAC
"""
from enum import Enum
from typing import List, Set


class Permission(str, Enum):
    """
    Permission enumeration for all system resources
    Format: RESOURCE_ACTION_SCOPE
    """
    
    # ==================== Cattle Management ====================
    CATTLE_READ = "cattle:read"
    CATTLE_CREATE = "cattle:create"
    CATTLE_UPDATE = "cattle:update"
    CATTLE_DELETE = "cattle:delete"
    CATTLE_EXPORT = "cattle:export"
    
    # ==================== Milk Production ====================
    MILK_READ = "milk_production:read"
    MILK_CREATE = "milk_production:create"
    MILK_UPDATE = "milk_production:update"
    MILK_DELETE = "milk_production:delete"
    MILK_ANALYZE = "milk_production:analyze"
    
    # ==================== Health Records ====================
    HEALTH_READ = "health_record:read"
    HEALTH_CREATE = "health_record:create"
    HEALTH_UPDATE = "health_record:update"
    HEALTH_DELETE = "health_record:delete"
    HEALTH_VACCINATION = "health_record:vaccination"
    HEALTH_TREATMENT = "health_record:treatment"
    
    # ==================== Breeding ====================
    BREEDING_READ = "breeding:read"
    BREEDING_CREATE = "breeding:create"
    BREEDING_UPDATE = "breeding:update"
    BREEDING_AI = "breeding:artificial_insemination"
    
    # ==================== Financial ====================
    FINANCE_READ = "financial:read"
    FINANCE_CREATE = "financial:create"
    FINANCE_UPDATE = "financial:update"
    FINANCE_DELETE = "financial:delete"
    FINANCE_REPORT = "financial:report"
    FINANCE_APPROVE = "financial:approve"
    
    # ==================== Inventory ====================
    INVENTORY_READ = "inventory:read"
    INVENTORY_CREATE = "inventory:create"
    INVENTORY_UPDATE = "inventory:update"
    INVENTORY_DELETE = "inventory:delete"
    
    # ==================== Reports ====================
    REPORT_READ = "report:read"
    REPORT_CREATE = "report:create"
    REPORT_EXPORT = "report:export"
    REPORT_SCHEDULE = "report:schedule"
    
    # ==================== Users & Admin ====================
    USER_READ = "user:read"
    USER_CREATE = "user:create"
    USER_UPDATE = "user:update"
    USER_DELETE = "user:delete"
    USER_MANAGE_ROLES = "user:manage_roles"
    
    # ==================== System ====================
    SYSTEM_CONFIG = "system:config"
    SYSTEM_LOGS = "system:logs"
    SYSTEM_BACKUP = "system:backup"
    SYSTEM_AUDIT = "system:audit"


# Role-Permission Mapping
ROLE_PERMISSIONS = {
    "super_admin": [
        # All permissions
        p for p in Permission
    ],
    
    "farm_admin": [
        Permission.CATTLE_READ, Permission.CATTLE_CREATE, 
        Permission.CATTLE_UPDATE, Permission.CATTLE_DELETE, Permission.CATTLE_EXPORT,
        Permission.MILK_READ, Permission.MILK_CREATE, Permission.MILK_UPDATE, Permission.MILK_DELETE,
        Permission.HEALTH_READ, Permission.HEALTH_CREATE, Permission.HEALTH_UPDATE, Permission.HEALTH_DELETE,
        Permission.BREEDING_READ, Permission.BREEDING_CREATE, Permission.BREEDING_UPDATE,
        Permission.FINANCE_READ, Permission.FINANCE_CREATE, Permission.FINANCE_UPDATE, 
        Permission.FINANCE_DELETE, Permission.FINANCE_REPORT,
        Permission.INVENTORY_READ, Permission.INVENTORY_CREATE, 
        Permission.INVENTORY_UPDATE, Permission.INVENTORY_DELETE,
        Permission.REPORT_READ, Permission.REPORT_CREATE, Permission.REPORT_EXPORT,
        Permission.USER_READ, Permission.USER_CREATE, Permission.USER_UPDATE,
        Permission.SYSTEM_AUDIT,
    ],
    
    "herd_manager": [
        Permission.CATTLE_READ, Permission.CATTLE_CREATE, Permission.CATTLE_UPDATE, Permission.CATTLE_EXPORT,
        Permission.MILK_READ,
        Permission.HEALTH_READ, Permission.HEALTH_CREATE, Permission.HEALTH_UPDATE,
        Permission.BREEDING_READ, Permission.BREEDING_CREATE, Permission.BREEDING_UPDATE, Permission.BREEDING_AI,
        Permission.INVENTORY_READ, Permission.INVENTORY_CREATE, Permission.INVENTORY_UPDATE,
        Permission.REPORT_READ, Permission.REPORT_CREATE,
    ],
    
    "milk_manager": [
        Permission.CATTLE_READ,
        Permission.MILK_READ, Permission.MILK_CREATE, Permission.MILK_UPDATE, 
        Permission.MILK_DELETE, Permission.MILK_ANALYZE,
        Permission.REPORT_READ, Permission.REPORT_CREATE,
    ],
    
    "health_manager": [
        Permission.CATTLE_READ,
        Permission.HEALTH_READ, Permission.HEALTH_CREATE, Permission.HEALTH_UPDATE,
        Permission.HEALTH_VACCINATION, Permission.HEALTH_TREATMENT,
        Permission.BREEDING_READ,
        Permission.REPORT_READ, Permission.REPORT_CREATE,
    ],
    
    "finance_manager": [
        Permission.CATTLE_READ,
        Permission.MILK_READ,
        Permission.FINANCE_READ, Permission.FINANCE_CREATE, 
        Permission.FINANCE_UPDATE, Permission.FINANCE_DELETE,
        Permission.FINANCE_REPORT, Permission.FINANCE_APPROVE,
        Permission.REPORT_READ, Permission.REPORT_CREATE, Permission.REPORT_EXPORT,
    ],
    
    "data_analyst": [
        Permission.CATTLE_READ,
        Permission.MILK_READ,
        Permission.HEALTH_READ,
        Permission.BREEDING_READ,
        Permission.FINANCE_READ,
        Permission.REPORT_READ, Permission.REPORT_CREATE, 
        Permission.REPORT_EXPORT, Permission.REPORT_SCHEDULE,
    ],
    
    "guest": [
        Permission.CATTLE_READ,
        Permission.MILK_READ,
        Permission.REPORT_READ,
    ],
}


class PermissionManager:
    """Manage role-based permissions"""
    
    @staticmethod
    def get_role_permissions(role: str) -> List[Permission]:
        """Get permissions for a role"""
        return ROLE_PERMISSIONS.get(role, [])
    
    @staticmethod
    def get_user_permissions(roles: List[str]) -> Set[str]:
        """Get combined permissions for multiple roles"""
        permissions = set()
        for role in roles:
            perms = ROLE_PERMISSIONS.get(role, [])
            permissions.update(p.value for p in perms)
        return permissions
    
    @staticmethod
    def has_permission(user_permissions: List[str], required: Permission) -> bool:
        """Check if user has a specific permission"""
        return required.value in user_permissions
    
    @staticmethod
    def has_any_permission(user_permissions: List[str], required: List[Permission]) -> bool:
        """Check if user has any of the required permissions"""
        required_values = {p.value for p in required}
        return bool(required_values & set(user_permissions))
    
    @staticmethod
    def has_all_permissions(user_permissions: List[str], required: List[Permission]) -> bool:
        """Check if user has all required permissions"""
        required_values = {p.value for p in required}
        return required_values.issubset(set(user_permissions))


# Convenience functions
def has_permission(user_permissions: List[str], permission: Permission) -> bool:
    """Check if user has permission"""
    return PermissionManager.has_permission(user_permissions, permission)


def get_permissions_for_roles(roles: List[str]) -> List[str]:
    """Get permissions for roles"""
    return list(PermissionManager.get_user_permissions(roles))
```

### 11.9 FastAPI Auth Router (auth/router.py)

```python
"""
Authentication API Routes
"""
from datetime import timedelta
from typing import Optional
from fastapi import APIRouter, Depends, HTTPException, status, Request, Response
from fastapi.security import OAuth2PasswordRequestForm, HTTPBearer
from pydantic import BaseModel, EmailStr

from app.config import settings
from app.core.password import PasswordManager, PasswordValidator, validate_password
from app.core.jwt_handler import jwt_handler, create_access_token, create_refresh_token
from app.auth.oauth2 import oauth2_server, TokenResponse, GrantType
from app.auth.mfa import mfa_service, MFAType
from app.auth.dependencies import get_current_user, TokenPayload


router = APIRouter(prefix="/auth", tags=["Authentication"])
security = HTTPBearer()


# ==================== Request/Response Models ====================

class UserRegistration(BaseModel):
    username: str
    email: EmailStr
    password: str
    full_name: str
    phone_number: Optional[str] = None
    farm_id: Optional[str] = None


class LoginRequest(BaseModel):
    username: str
    password: str
    mfa_code: Optional[str] = None


class LoginResponse(BaseModel):
    access_token: str
    token_type: str = "Bearer"
    expires_in: int
    refresh_token: str
    requires_mfa: bool = False
    mfa_types: list = []


class MFAVerifyRequest(BaseModel):
    temp_token: str
    mfa_type: MFAType
    code: str


class PasswordResetRequest(BaseModel):
    email: EmailStr


class PasswordResetConfirm(BaseModel):
    token: str
    new_password: str


class RefreshTokenRequest(BaseModel):
    refresh_token: str


# ==================== Registration ====================

@router.post("/register", status_code=status.HTTP_201_CREATED)
async def register_user(data: UserRegistration):
    """
    Register a new user account
    
    - Validates password strength
    - Checks for breach in known compromised databases
    - Creates user with hashed password
    """
    # Validate password
    is_valid, errors = await validate_password(
        data.password,
        username=data.username,
        email=data.email,
        full_name=data.full_name
    )
    
    if not is_valid:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail={"password_errors": errors}
        )
    
    # Check breach database
    is_breached, count = await PasswordValidator.check_breach_database(data.password)
    
    if is_breached:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail=f"This password has been found in {count:,} data breaches. Please choose a different password."
        )
    
    # Hash password
    password_hash = PasswordManager.hash_password(data.password)
    
    # Create user (implementation depends on your database)
    user = await create_user(
        username=data.username,
        email=data.email,
        password_hash=password_hash,
        full_name=data.full_name,
        phone_number=data.phone_number,
        farm_id=data.farm_id
    )
    
    return {
        "message": "Registration successful",
        "user_id": user.id,
        "requires_email_verification": True
    }


# ==================== Login ====================

@router.post("/login", response_model=LoginResponse)
async def login(
    request: Request,
    response: Response,
    form_data: OAuth2PasswordRequestForm = Depends()
):
    """
    Authenticate user and issue JWT tokens
    
    - Supports password-based authentication
    - Checks for MFA requirement
    - Issues access and refresh tokens
    """
    # Verify credentials
    user = await authenticate_user(form_data.username, form_data.password)
    
    if not user:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid username or password",
            headers={"WWW-Authenticate": "Bearer"},
        )
    
    # Check if account is locked
    if user.is_locked:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Account is locked. Please contact support."
        )
    
    # Check if MFA is required
    if user.mfa_enabled:
        # Generate temporary token for MFA flow
        temp_token = create_temp_token(user.id)
        
        return LoginResponse(
            access_token="",
            refresh_token="",
            expires_in=0,
            requires_mfa=True,
            mfa_types=user.mfa_types
        )
    
    # Create session
    session_id = await create_session(user.id, request)
    
    # Generate tokens
    access_token = create_access_token(
        user_id=user.id,
        roles=user.roles,
        permissions=user.permissions,
        tenant_id=user.farm_id,
        session_id=session_id
    )
    
    refresh_token = create_refresh_token(
        user_id=user.id,
        session_id=session_id
    )
    
    # Store refresh token
    await store_refresh_token(refresh_token, user.id, session_id)
    
    # Set session cookie
    response.set_cookie(
        key="session_id",
        value=session_id,
        httponly=True,
        secure=True,
        samesite="strict",
        max_age=settings.security.JWT_REFRESH_TOKEN_EXPIRE_DAYS * 86400
    )
    
    # Update last login
    await update_last_login(user.id)
    
    return LoginResponse(
        access_token=access_token,
        token_type="Bearer",
        expires_in=settings.security.JWT_ACCESS_TOKEN_EXPIRE_MINUTES * 60,
        refresh_token=refresh_token,
        requires_mfa=False
    )


@router.post("/mfa/verify")
async def verify_mfa(data: MFAVerifyRequest, request: Request):
    """
    Verify MFA code and complete authentication
    """
    # Verify temp token
    user_id = verify_temp_token(data.temp_token)
    
    if not user_id:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid or expired session"
        )
    
    user = await get_user_by_id(user_id)
    
    # Verify MFA code
    verified = False
    
    if data.mfa_type == MFAType.TOTP:
        verified = mfa_service.verify_totp(user.totp_secret, data.code)
    elif data.mfa_type == MFAType.SMS:
        verified = await mfa_service.verify_sms_otp(user.id, data.code)
    elif data.mfa_type == MFAType.EMAIL:
        verified = await mfa_service.verify_email_otp(user.id, data.code)
    elif data.mfa_type == MFAType.BACKUP_CODE:
        verified = await mfa_service.verify_backup_code(user.id, data.code)
    
    if not verified:
        # Increment failed attempts
        await increment_mfa_failures(user.id)
        
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid MFA code"
        )
    
    # Clear temp token
    await clear_temp_token(data.temp_token)
    
    # Create session
    session_id = await create_session(user.id, request)
    
    # Generate tokens
    access_token = create_access_token(
        user_id=user.id,
        roles=user.roles,
        permissions=user.permissions,
        tenant_id=user.farm_id,
        session_id=session_id
    )
    
    refresh_token = create_refresh_token(
        user_id=user.id,
        session_id=session_id
    )
    
    await store_refresh_token(refresh_token, user.id, session_id)
    
    return {
        "access_token": access_token,
        "token_type": "Bearer",
        "expires_in": settings.security.JWT_ACCESS_TOKEN_EXPIRE_MINUTES * 60,
        "refresh_token": refresh_token
    }


# ==================== Token Refresh ====================

@router.post("/refresh")
async def refresh_token(data: RefreshTokenRequest):
    """
    Refresh access token using refresh token
    
    Implements token rotation for enhanced security
    """
    result = await oauth2_server.refresh_token_grant(
        refresh_token=data.refresh_token,
        client_id="smart-dairy-web",  # Or extract from token
    )
    
    return result


# ==================== Logout ====================

@router.post("/logout")
async def logout(
    response: Response,
    current_user: TokenPayload = Depends(get_current_user)
):
    """
    Logout user and invalidate tokens
    """
    # Revoke access token
    jti = jwt_handler.get_token_jti(current_user)  # Need to implement
    if jti:
        await revoke_token(jti, current_user.exp)
    
    # Invalidate session
    if current_user.session_id:
        await invalidate_session(current_user.session_id)
    
    # Clear cookie
    response.delete_cookie("session_id")
    
    return {"message": "Successfully logged out"}


@router.post("/logout-all")
async def logout_all_devices(
    current_user: TokenPayload = Depends(get_current_user)
):
    """
    Logout from all devices
    """
    # Invalidate all sessions for user
    await invalidate_all_user_sessions(current_user.sub)
    
    return {"message": "Logged out from all devices"}


# ==================== OAuth 2.0 Endpoints ====================

@router.post("/oauth/token", response_model=TokenResponse)
async def oauth_token(
    grant_type: GrantType,
    client_id: str,
    client_secret: Optional[str] = None,
    code: Optional[str] = None,
    redirect_uri: Optional[str] = None,
    code_verifier: Optional[str] = None,
    refresh_token: Optional[str] = None,
    scope: Optional[str] = None
):
    """
    OAuth 2.0 Token Endpoint
    
    Supports: authorization_code, client_credentials, refresh_token
    """
    if grant_type == GrantType.AUTHORIZATION_CODE:
        return await oauth2_server.exchange_code_for_token(
            code=code,
            client_id=client_id,
            client_secret=client_secret,
            redirect_uri=redirect_uri,
            code_verifier=code_verifier
        )
    
    elif grant_type == GrantType.CLIENT_CREDENTIALS:
        return await oauth2_server.client_credentials_grant(
            client_id=client_id,
            client_secret=client_secret,
            scope=scope
        )
    
    elif grant_type == GrantType.REFRESH_TOKEN:
        return await oauth2_server.refresh_token_grant(
            refresh_token=refresh_token,
            client_id=client_id,
            client_secret=client_secret,
            scope=scope
        )
    
    else:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail=f"Unsupported grant type: {grant_type}"
        )


@router.post("/oauth/device/code")
async def device_authorization(
    client_id: str,
    scope: Optional[str] = None
):
    """
    Initiate Device Authorization Flow (RFC 8628)
    
    For IoT devices and input-constrained environments
    """
    return await oauth2_server.initiate_device_flow(client_id, scope)


# ==================== Password Management ====================

@router.post("/password/reset-request")
async def request_password_reset(data: PasswordResetRequest):
    """
    Request password reset email
    """
    user = await get_user_by_email(data.email)
    
    if user:
        # Generate reset token
        token = generate_reset_token(user.id)
        
        # Send email
        await send_password_reset_email(user.email, token)
    
    # Always return success to prevent user enumeration
    return {"message": "If an account exists, a reset email has been sent"}


@router.post("/password/reset")
async def confirm_password_reset(data: PasswordResetConfirm):
    """
    Confirm password reset with token
    """
    # Verify token
    user_id = verify_reset_token(data.token)
    
    if not user_id:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Invalid or expired reset token"
        )
    
    # Validate new password
    is_valid, errors = await validate_password(data.new_password)
    
    if not is_valid:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail={"password_errors": errors}
        )
    
    # Update password
    password_hash = PasswordManager.hash_password(data.new_password)
    await update_user_password(user_id, password_hash)
    
    # Invalidate all sessions
    await invalidate_all_user_sessions(user_id)
    
    return {"message": "Password reset successful"}


@router.post("/password/change")
async def change_password(
    current_password: str,
    new_password: str,
    current_user: TokenPayload = Depends(get_current_user)
):
    """
    Change password (authenticated users)
    """
    user = await get_user_by_id(current_user.sub)
    
    # Verify current password
    if not PasswordManager.verify_password(current_password, user.password_hash):
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Current password is incorrect"
        )
    
    # Validate new password
    is_valid, errors = await validate_password(
        new_password,
        username=user.username,
        email=user.email,
        full_name=user.full_name
    )
    
    if not is_valid:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail={"password_errors": errors}
        )
    
    # Check password history
    if await is_password_in_history(user.id, new_password):
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Cannot reuse recent passwords"
        )
    
    # Update password
    password_hash = PasswordManager.hash_password(new_password)
    await update_user_password(user.id, password_hash)
    await add_password_to_history(user.id, password_hash)
    
    # Invalidate all sessions (force re-login)
    await invalidate_all_user_sessions(user.id)
    
    return {"message": "Password changed successfully. Please login again."}


# ==================== MFA Management ====================

@router.post("/mfa/setup/totp")
async def setup_totp(current_user: TokenPayload = Depends(get_current_user)):
    """
    Setup TOTP-based MFA
    
    Returns QR code for authenticator app scanning
    """
    user = await get_user_by_id(current_user.sub)
    
    setup_data = await mfa_service.setup_totp(user.id, user.username)
    
    # Return QR code and secret (secret shown once for manual entry)
    return {
        "secret": setup_data.secret,
        "qr_code_svg": setup_data.qr_code,
        "backup_codes": setup_data.backup_codes,
        "warning": "Save backup codes securely - they will not be shown again!"
    }


@router.post("/mfa/verify-setup")
async def verify_totp_setup(
    code: str,
    current_user: TokenPayload = Depends(get_current_user)
):
    """
    Verify TOTP setup by validating first code
    """
    user = await get_user_by_id(current_user.sub)
    
    if not user.totp_secret:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="TOTP not setup"
        )
    
    is_valid = mfa_service.verify_totp(user.totp_secret, code)
    
    if not is_valid:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Invalid TOTP code"
        )
    
    # Enable MFA
    await enable_mfa(user.id, MFAType.TOTP)
    
    return {"message": "TOTP MFA enabled successfully"}


@router.delete("/mfa/disable")
async def disable_mfa(
    password: str,
    mfa_code: str,
    current_user: TokenPayload = Depends(get_current_user)
):
    """
    Disable MFA (requires password and MFA confirmation)
    """
    user = await get_user_by_id(current_user.sub)
    
    # Verify password
    if not PasswordManager.verify_password(password, user.password_hash):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid password"
        )
    
    # Verify MFA code
    is_valid = mfa_service.verify_totp(user.totp_secret, mfa_code)
    
    if not is_valid:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Invalid MFA code"
        )
    
    # Disable MFA
    await disable_user_mfa(user.id)
    
    return {"message": "MFA disabled successfully"}


# ==================== Helper Functions (to be implemented) ====================

async def create_user(**kwargs):
    """Create user in database"""
    pass

async def authenticate_user(username: str, password: str):
    """Verify user credentials"""
    pass

async def get_user_by_id(user_id: str):
    """Get user by ID"""
    pass

async def get_user_by_email(email: str):
    """Get user by email"""
    pass

async def create_session(user_id: str, request: Request):
    """Create new session"""
    pass

async def store_refresh_token(token: str, user_id: str, session_id: str):
    """Store refresh token hash"""
    pass

async def update_last_login(user_id: str):
    """Update last login timestamp"""
    pass

async def revoke_token(jti: str, exp: datetime):
    """Add token to revocation list"""
    pass

async def invalidate_session(session_id: str):
    """Invalidate session"""
    pass

async def invalidate_all_user_sessions(user_id: str):
    """Invalidate all user sessions"""
    pass

async def increment_mfa_failures(user_id: str):
    """Track MFA failures"""
    pass

def create_temp_token(user_id: str) -> str:
    """Create temporary token for MFA flow"""
    pass

def verify_temp_token(token: str) -> Optional[str]:
    """Verify temp token and return user_id"""
    pass

async def clear_temp_token(token: str):
    """Clear temp token"""
    pass

def generate_reset_token(user_id: str) -> str:
    """Generate password reset token"""
    pass

def verify_reset_token(token: str) -> Optional[str]:
    """Verify reset token"""
    pass

async def send_password_reset_email(email: str, token: str):
    """Send password reset email"""
    pass

async def update_user_password(user_id: str, password_hash: str):
    """Update user password"""
    pass

async def is_password_in_history(user_id: str, password: str) -> bool:
    """Check if password is in history"""
    pass

async def add_password_to_history(user_id: str, password_hash: str):
    """Add password to history"""
    pass

async def enable_mfa(user_id: str, mfa_type: MFAType):
    """Enable MFA for user"""
    pass

async def disable_user_mfa(user_id: str):
    """Disable MFA for user"""
    pass
```


### 11.10 Redis Service (services/redis_service.py)

```python
"""
Redis Service for Session and Token Management
"""
import json
from typing import Optional, Any, List
import aioredis
from app.config import settings


class RedisService:
    """Redis service for caching and session management"""
    
    def __init__(self):
        self.redis: Optional[aioredis.Redis] = None
        self.url = settings.database.redis_url
    
    async def connect(self):
        """Initialize Redis connection"""
        self.redis = await aioredis.from_url(
            self.url,
            encoding="utf-8",
            decode_responses=True
        )
    
    async def disconnect(self):
        """Close Redis connection"""
        if self.redis:
            await self.redis.close()
    
    async def get(self, key: str) -> Optional[str]:
        """Get value by key"""
        if not self.redis:
            await self.connect()
        return await self.redis.get(key)
    
    async def set(self, key: str, value: str, ex: Optional[int] = None):
        """Set value with optional expiration"""
        if not self.redis:
            await self.connect()
        await self.redis.set(key, value, ex=ex)
    
    async def setex(self, key: str, seconds: int, value: str):
        """Set value with expiration"""
        if not self.redis:
            await self.connect()
        await self.redis.setex(key, seconds, value)
    
    async def delete(self, key: str):
        """Delete key"""
        if not self.redis:
            await self.connect()
        await self.redis.delete(key)
    
    async def exists(self, key: str) -> bool:
        """Check if key exists"""
        if not self.redis:
            await self.connect()
        return await self.redis.exists(key) > 0
    
    async def expire(self, key: str, seconds: int):
        """Set expiration on existing key"""
        if not self.redis:
            await self.connect()
        await self.redis.expire(key, seconds)
    
    async def pipeline(self):
        """Get pipeline for batch operations"""
        if not self.redis:
            await self.connect()
        return self.redis.pipeline()
    
    # Session Management
    
    async def store_session(
        self,
        session_id: str,
        user_id: str,
        data: dict,
        ttl: int = 604800  # 7 days
    ):
        """Store session data"""
        session_data = {
            "user_id": user_id,
            "data": data,
            "created_at": str(datetime.utcnow())
        }
        await self.setex(
            f"session:{session_id}",
            ttl,
            json.dumps(session_data)
        )
    
    async def get_session(self, session_id: str) -> Optional[dict]:
        """Get session data"""
        data = await self.get(f"session:{session_id}")
        if data:
            return json.loads(data)
        return None
    
    async def delete_session(self, session_id: str):
        """Delete session"""
        await self.delete(f"session:{session_id}")
    
    async def get_user_sessions(self, user_id: str) -> List[str]:
        """Get all active sessions for user"""
        pattern = f"session:*"
        sessions = []
        async for key in self.redis.scan_iter(match=pattern):
            data = await self.get(key)
            if data:
                session = json.loads(data)
                if session.get("user_id") == user_id:
                    sessions.append(key.decode().replace("session:", ""))
        return sessions
    
    async def delete_user_sessions(self, user_id: str):
        """Delete all sessions for user"""
        sessions = await self.get_user_sessions(user_id)
        for session_id in sessions:
            await self.delete_session(session_id)
    
    # Token Blacklist
    
    async def blacklist_token(self, jti: str, exp: int):
        """Add token to blacklist until expiration"""
        await self.setex(f"blacklist:{jti}", exp, "1")
    
    async def is_token_blacklisted(self, jti: str) -> bool:
        """Check if token is blacklisted"""
        return await self.exists(f"blacklist:{jti}")
    
    # Rate Limiting
    
    async def increment_counter(self, key: str, window: int) -> int:
        """Increment rate limit counter"""
        pipe = await self.pipeline()
        await pipe.incr(key)
        await pipe.expire(key, window)
        results = await pipe.execute()
        return results[0]
    
    async def get_counter(self, key: str) -> int:
        """Get current counter value"""
        value = await self.get(key)
        return int(value) if value else 0


# Global instance
redis_service = RedisService()
```

### 11.11 API Endpoints Example (api/v1/cattle.py)

```python
"""
Protected Cattle Management API
Demonstrates RBAC-protected endpoints
"""
from fastapi import APIRouter, Depends, HTTPException, status
from typing import List, Optional

from app.auth.dependencies import (
    get_current_user, 
    require_permissions,
    require_roles,
    TokenPayload
)
from app.rbac.permissions import Permission


router = APIRouter(prefix="/cattle", tags=["Cattle"])


class CattleCreate(BaseModel):
    ear_tag: str
    name: Optional[str] = None
    breed: str
    birth_date: str
    gender: str
    farm_id: str


class CattleResponse(BaseModel):
    id: str
    ear_tag: str
    name: Optional[str]
    breed: str
    status: str


@router.get("/", response_model=List[CattleResponse])
async def list_cattle(
    farm_id: Optional[str] = None,
    skip: int = 0,
    limit: int = 100,
    user: TokenPayload = Depends(require_permissions([Permission.CATTLE_READ]))
):
    """
    List cattle with optional farm filter
    
    Requires: cattle:read permission
    """
    # Enforce farm boundary (ABAC)
    if farm_id and farm_id != user.tenant_id and "admin" not in user.roles:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Cannot access cattle from other farms"
        )
    
    # Default to user's farm
    target_farm = farm_id or user.tenant_id
    
    cattle = await get_cattle_by_farm(target_farm, skip, limit)
    return cattle


@router.get("/{cattle_id}", response_model=CattleResponse)
async def get_cattle(
    cattle_id: str,
    user: TokenPayload = Depends(require_permissions([Permission.CATTLE_READ]))
):
    """
    Get specific cattle details
    
    Requires: cattle:read permission
    """
    cattle = await get_cattle_by_id(cattle_id)
    
    if not cattle:
        raise HTTPException(status_code=404, detail="Cattle not found")
    
    # ABAC: Check farm ownership
    if cattle.farm_id != user.tenant_id and "admin" not in user.roles:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Access denied"
        )
    
    return cattle


@router.post("/", response_model=CattleResponse, status_code=status.HTTP_201_CREATED)
async def create_cattle(
    data: CattleCreate,
    user: TokenPayload = Depends(require_permissions([Permission.CATTLE_CREATE]))
):
    """
    Register new cattle
    
    Requires: cattle:create permission
    """
    # ABAC: Can only create in own farm
    if data.farm_id != user.tenant_id and "admin" not in user.roles:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Can only create cattle in your farm"
        )
    
    cattle = await create_cattle_record(data, user.sub)
    return cattle


@router.put("/{cattle_id}", response_model=CattleResponse)
async def update_cattle(
    cattle_id: str,
    data: CattleUpdate,
    user: TokenPayload = Depends(require_permissions([Permission.CATTLE_UPDATE]))
):
    """
    Update cattle information
    
    Requires: cattle:update permission
    """
    cattle = await get_cattle_by_id(cattle_id)
    
    if not cattle:
        raise HTTPException(status_code=404, detail="Cattle not found")
    
    # ABAC: Ownership check
    if cattle.farm_id != user.tenant_id and "admin" not in user.roles:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Access denied"
        )
    
    updated = await update_cattle_record(cattle_id, data)
    return updated


@router.delete("/{cattle_id}", status_code=status.HTTP_204_NO_CONTENT)
async def delete_cattle(
    cattle_id: str,
    user: TokenPayload = Depends(require_permissions([Permission.CATTLE_DELETE]))
):
    """
    Delete cattle record
    
    Requires: cattle:delete permission
    Admin or farm manager only
    """
    cattle = await get_cattle_by_id(cattle_id)
    
    if not cattle:
        raise HTTPException(status_code=404, detail="Cattle not found")
    
    if cattle.farm_id != user.tenant_id and "admin" not in user.roles:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Access denied"
        )
    
    await delete_cattle_record(cattle_id)
    return None


@router.get("/admin/export")
async def export_cattle_data(
    farm_id: Optional[str] = None,
    user: TokenPayload = Depends(require_permissions([
        Permission.CATTLE_EXPORT,
        Permission.REPORT_EXPORT
    ]))
):
    """
    Export cattle data
    
    Requires both cattle:export and report:export permissions
    """
    # Implementation
    pass


@router.get("/reports/production")
async def cattle_production_report(
    start_date: str,
    end_date: str,
    user: TokenPayload = Depends(require_roles(["farm_admin", "data_analyst"]))
):
    """
    Generate cattle production report
    
    Requires: farm_admin or data_analyst role
    """
    # Implementation
    pass
```

---

## 12. Testing & Validation

### 12.1 Authentication Testing Strategy

```python
"""
Test Suite for Authentication
"""
import pytest
from fastapi.testclient import TestClient
from datetime import datetime, timedelta

from app.main import app
from app.core.password import PasswordManager, PasswordValidator
from app.core.jwt_handler import jwt_handler
from app.auth.mfa import mfa_service


client = TestClient(app)


class TestPasswordSecurity:
    """Test password hashing and validation"""
    
    def test_password_hashing(self):
        """Test Argon2 password hashing"""
        password = "SecurePassword123!"
        hash1 = PasswordManager.hash_password(password)
        hash2 = PasswordManager.hash_password(password)
        
        # Same password should produce different hashes (due to salt)
        assert hash1 != hash2
        
        # Both should verify correctly
        assert PasswordManager.verify_password(password, hash1)
        assert PasswordManager.verify_password(password, hash2)
        
        # Wrong password should fail
        assert not PasswordManager.verify_password("WrongPassword", hash1)
    
    def test_password_validation(self):
        """Test password policy validation"""
        # Valid password
        valid, errors = PasswordValidator.validate(
            "SecurePass123!",
            username="john",
            email="john@example.com"
        )
        assert valid
        assert len(errors) == 0
        
        # Too short
        valid, errors = PasswordValidator.validate("Short1!")
        assert not valid
        assert any("12 characters" in e for e in errors)
        
        # Missing uppercase
        valid, errors = PasswordValidator.validate("lowercase123!")
        assert not valid
        assert any("uppercase" in e for e in errors)
        
        # Contains username
        valid, errors = PasswordValidator.validate(
            "johnSecure123!",
            username="john"
        )
        assert not valid
        assert any("username" in e for e in errors)
        
        # Common password
        valid, errors = PasswordValidator.validate("Password123!")
        assert not valid
        assert any("common" in e.lower() for e in errors)


class TestJWT:
    """Test JWT token handling"""
    
    def test_token_creation(self):
        """Test JWT token generation"""
        token = jwt_handler.create_access_token(
            user_id="user123",
            roles=["admin"],
            permissions=["read", "write"],
            tenant_id="farm001"
        )
        
        assert token is not None
        assert len(token.split(".")) == 3  # Header.Payload.Signature
    
    def test_token_validation(self):
        """Test JWT token validation"""
        token = jwt_handler.create_access_token(
            user_id="user123",
            roles=["admin"],
            permissions=["read"]
        )
        
        payload = jwt_handler.decode_token(token)
        
        assert payload is not None
        assert payload.sub == "user123"
        assert "admin" in payload.roles
        assert payload.type == "access"
    
    def test_expired_token(self):
        """Test expired token rejection"""
        # Create already expired token
        token = jwt_handler.create_access_token(
            user_id="user123",
            roles=["admin"],
            permissions=["read"],
            expires_delta=timedelta(seconds=-1)
        )
        
        payload = jwt_handler.decode_token(token)
        assert payload is None  # Should be rejected
    
    def test_tampered_token(self):
        """Test tampered token rejection"""
        token = jwt_handler.create_access_token(
            user_id="user123",
            roles=["admin"],
            permissions=["read"]
        )
        
        # Tamper with token
        parts = token.split(".")
        parts[1] = "tampered_payload"
        tampered_token = ".".join(parts)
        
        payload = jwt_handler.decode_token(tampered_token)
        assert payload is None


class TestMFA:
    """Test Multi-Factor Authentication"""
    
    def test_totp_generation(self):
        """Test TOTP secret generation"""
        secret = mfa_service.generate_totp_secret()
        assert len(secret) == 32  # Base32 encoded
    
    def test_totp_verification(self):
        """Test TOTP code verification"""
        import pyotp
        
        secret = pyotp.random_base32()
        totp = pyotp.TOTP(secret)
        code = totp.now()
        
        assert mfa_service.verify_totp(secret, code)
        
        # Wrong code should fail
        assert not mfa_service.verify_totp(secret, "000000")
    
    def test_backup_codes(self):
        """Test backup code generation"""
        codes = mfa_service.generate_backup_codes(count=10)
        
        assert len(codes) == 10
        assert all(len(c) == 8 for c in codes)
        
        # Codes should be unique
        assert len(set(codes)) == 10


class TestRBAC:
    """Test Role-Based Access Control"""
    
    def test_permission_check(self):
        """Test permission verification"""
        from app.rbac.permissions import has_permission, Permission
        
        user_perms = ["cattle:read", "cattle:create"]
        
        assert has_permission(user_perms, Permission.CATTLE_READ)
        assert has_permission(user_perms, Permission.CATTLE_CREATE)
        assert not has_permission(user_perms, Permission.CATTLE_DELETE)
    
    def test_role_permissions(self):
        """Test role-permission mapping"""
        from app.rbac.permissions import PermissionManager
        
        admin_perms = PermissionManager.get_role_permissions("super_admin")
        assert len(admin_perms) > 0
        assert Permission.CATTLE_DELETE in admin_perms


class TestAPIEndpoints:
    """Test API authentication"""
    
    def test_login_success(self):
        """Test successful login"""
        response = client.post("/auth/login", data={
            "username": "test@example.com",
            "password": "TestPassword123!"
        })
        
        assert response.status_code == 200
        data = response.json()
        assert "access_token" in data
        assert "refresh_token" in data
        assert data["token_type"] == "Bearer"
    
    def test_login_failure(self):
        """Test failed login"""
        response = client.post("/auth/login", data={
            "username": "test@example.com",
            "password": "WrongPassword"
        })
        
        assert response.status_code == 401
    
    def test_protected_endpoint(self):
        """Test protected endpoint access"""
        # Without token
        response = client.get("/api/v1/cattle/")
        assert response.status_code == 401
        
        # With token
        token = jwt_handler.create_access_token(
            user_id="user123",
            roles=["herd_manager"],
            permissions=["cattle:read"],
            tenant_id="farm001"
        )
        
        response = client.get(
            "/api/v1/cattle/",
            headers={"Authorization": f"Bearer {token}"}
        )
        
        # May be 200 or 404 depending on data, but not 401
        assert response.status_code != 401
    
    def test_insufficient_permissions(self):
        """Test permission denial"""
        token = jwt_handler.create_access_token(
            user_id="user123",
            roles=["guest"],
            permissions=["cattle:read"]
        )
        
        response = client.post(
            "/api/v1/cattle/",
            json={"ear_tag": "TEST001", "breed": "Holstein"},
            headers={"Authorization": f"Bearer {token}"}
        )
        
        assert response.status_code == 403


class TestOAuth2:
    """Test OAuth 2.0 flows"""
    
    @pytest.mark.asyncio
    async def test_client_credentials(self):
        """Test client credentials grant"""
        from app.auth.oauth2 import oauth2_server
        
        response = await oauth2_server.client_credentials_grant(
            client_id="test_client",
            client_secret="test_secret",
            scope="read"
        )
        
        assert response.access_token is not None
        assert response.token_type == "Bearer"
    
    @pytest.mark.asyncio
    async def test_pkce_verification(self):
        """Test PKCE code verification"""
        import hashlib
        import base64
        
        code_verifier = "test_verifier_12345"
        code_challenge = base64.urlsafe_b64encode(
            hashlib.sha256(code_verifier.encode()).digest()
        ).rstrip(b'=').decode('ascii')
        
        # Should verify correctly
        computed = oauth2_server._hash_code_verifier(code_verifier, "S256")
        assert computed == code_challenge


# Test Configuration
@pytest.fixture
def test_user():
    """Create test user fixture"""
    return {
        "id": "user123",
        "username": "testuser",
        "email": "test@example.com",
        "password_hash": PasswordManager.hash_password("TestPassword123!"),
        "roles": ["herd_manager"],
        "permissions": ["cattle:read", "cattle:create"],
        "farm_id": "farm001"
    }


@pytest.fixture
def admin_user():
    """Create admin user fixture"""
    return {
        "id": "admin123",
        "username": "admin",
        "email": "admin@example.com",
        "password_hash": PasswordManager.hash_password("AdminPassword123!"),
        "roles": ["super_admin"],
        "permissions": [p.value for p in Permission],
        "farm_id": "farm001"
    }
```

### 12.2 Security Testing Checklist

| Test Category | Test Case | Expected Result |
|---------------|-----------|-----------------|
| **Authentication** | Valid credentials | Successful login, tokens issued |
| | Invalid password | 401 Unauthorized |
| | Non-existent user | 401 Unauthorized (no user enumeration) |
| | SQL injection in username | Input sanitized, 401 error |
| | XSS in password field | Input sanitized |
| **Session** | Valid token | Access granted |
| | Expired token | 401 Unauthorized |
| | Revoked token | 401 Unauthorized |
| | Tampered token | 401 Unauthorized |
| | Missing token on protected endpoint | 401 Unauthorized |
| **MFA** | Valid TOTP code | Authentication successful |
| | Invalid TOTP code | 401 Unauthorized |
| | Reused TOTP code | 401 Unauthorized |
| | Expired OTP | 401 Unauthorized |
| | Valid backup code | Authentication successful |
| **Authorization** | Access without required permission | 403 Forbidden |
| | Cross-tenant access | 403 Forbidden |
| | Admin access to admin endpoint | 200 OK |
| | Non-admin access to admin endpoint | 403 Forbidden |
| **Password Security** | Weak password rejected | 400 Bad Request |
| | Breached password rejected | 400 Bad Request |
| | Password hash comparison timing | Constant time |
| **Rate Limiting** | Excessive login attempts | Account locked |
| | Brute force protection | Rate limited |
| **OAuth 2.0** | Valid authorization code | Tokens issued |
| | Reused authorization code | 400 Bad Request |
| | Invalid PKCE verifier | 400 Bad Request |
| | Expired authorization code | 400 Bad Request |

---

## 13. Troubleshooting

### 13.1 Common Authentication Issues

#### Issue: "Invalid token" errors

**Symptoms:**
- API returns 401 Unauthorized
- Error message: "Invalid or expired token"

**Diagnosis Steps:**
```bash
# 1. Check token format
echo "$TOKEN" | cut -d'.' -f2 | base64 -d 2>/dev/null | jq .

# 2. Verify token expiration date
python3 << 'EOF'
import jwt
import datetime
token = "YOUR_TOKEN_HERE"
payload = jwt.decode(token, options={"verify_signature": False})
exp = datetime.datetime.fromtimestamp(payload['exp'])
print(f"Token expires: {exp}")
print(f"Current time:  {datetime.datetime.utcnow()}")
EOF

# 3. Check Redis for revocation
redis-cli GET "blacklist:<jti>"
```

**Solutions:**
1. **Clock skew**: Ensure server time is synchronized (NTP)
2. **Token expired**: Refresh token using refresh endpoint
3. **Token revoked**: Re-authenticate
4. **Wrong signing key**: Verify JWT_SECRET_KEY configuration

---

#### Issue: "Permission denied" errors

**Symptoms:**
- API returns 403 Forbidden
- User has valid token but cannot access resource

**Diagnosis Steps:**
```python
# Check user permissions in token payload
from app.core.jwt_handler import decode_token

payload = decode_token(token)
print(f"User roles: {payload.roles}")
print(f"User permissions: {payload.permissions}")
print(f"Tenant ID: {payload.tenant_id}")
```

**Solutions:**
1. Check role-permission mapping in `ROLE_PERMISSIONS`
2. Verify user has correct roles assigned
3. Check ABAC policies if using dynamic authorization
4. Verify tenant/farm boundary enforcement

---

#### Issue: MFA verification fails

**Symptoms:**
- Valid TOTP code rejected
- "Invalid MFA code" error

**Diagnosis Steps:**
```bash
# Check server time (critical for TOTP)
date -u

# Check TOTP secret
echo "Secret: $TOTP_SECRET"
python3 << 'EOF'
import pyotp
import time

secret = "USER_SECRET_HERE"
totp = pyotp.TOTP(secret)

print(f"Current code: {totp.now()}")
print(f"Current time: {time.time()}")
print(f"Time window: {int(time.time()) // 30}")
EOF
```

**Solutions:**
1. **Clock sync**: Ensure server and device time are synchronized
2. **Wrong secret**: Re-setup MFA with new QR code
3. **Used code**: TOTP codes cannot be reused
4. **Window drift**: Increase `valid_window` if needed (not recommended)

---

#### Issue: OAuth authorization code errors

**Symptoms:**
- "Invalid authorization code"
- "Code already used"

**Diagnosis Steps:**
```bash
# Check Redis for code
redis-cli GET "auth_code:YOUR_CODE"

# Check TTL
redis-cli TTL "auth_code:YOUR_CODE"
```

**Solutions:**
1. **Code expired**: Codes expire in 10 minutes
2. **Code reused**: Authorization codes are single-use
3. **Wrong redirect_uri**: Must match initial request
4. **PKCE mismatch**: Verify code_verifier matches code_challenge

---

#### Issue: Session not persisting

**Symptoms:**
- User logged out unexpectedly
- Session data lost

**Diagnosis Steps:**
```bash
# Check Redis connection
redis-cli PING

# Check session exists
redis-cli GET "session:SESSION_ID"

# Check session TTL
redis-cli TTL "session:SESSION_ID"

# Check Redis memory
redis-cli INFO memory
```

**Solutions:**
1. **Redis down**: Check Redis service status
2. **Memory pressure**: Check maxmemory policy
3. **TTL too short**: Adjust session timeout configuration
4. **Cookie issues**: Verify cookie settings (secure, httponly, samesite)

---

### 13.2 Error Codes Reference

| Error Code | HTTP Status | Description | Resolution |
|------------|-------------|-------------|------------|
| `AUTH_001` | 401 | Invalid credentials | Check username/password |
| `AUTH_002` | 401 | Token expired | Refresh token or re-login |
| `AUTH_003` | 401 | Token revoked | Re-authenticate |
| `AUTH_004` | 401 | Invalid token signature | Check JWT configuration |
| `AUTH_005` | 403 | Insufficient permissions | Request additional permissions |
| `AUTH_006` | 403 | Cross-tenant access | Access only your farm's data |
| `AUTH_007` | 429 | Rate limit exceeded | Wait before retrying |
| `AUTH_008` | 403 | Account locked | Contact administrator |
| `AUTH_009` | 401 | MFA required | Complete MFA verification |
| `AUTH_010` | 401 | Invalid MFA code | Retry with correct code |
| `OAUTH_001` | 400 | Invalid grant type | Use supported grant type |
| `OAUTH_002` | 400 | Invalid client | Verify client credentials |
| `OAUTH_003` | 400 | Invalid scope | Request allowed scopes |
| `OAUTH_004` | 400 | Invalid code | Use valid authorization code |
| `OAUTH_005` | 400 | Invalid redirect_uri | Match initial request |

### 13.3 Debug Logging

```python
# Enable debug logging in config
LOGGING_CONFIG = {
    "version": 1,
    "loggers": {
        "auth": {
            "level": "DEBUG",
            "handlers": ["console", "file"]
        }
    }
}

# Log authentication events
logger.debug(f"Login attempt: user={username}, ip={client_ip}")
logger.debug(f"Token issued: user_id={user_id}, jti={jti}")
logger.debug(f"Permission check: user={user_id}, resource={resource}, result={allowed}")
```

---

## 14. Appendices

### Appendix A: Complete Requirements.txt

```
# FastAPI & Web Framework
fastapi==0.109.0
uvicorn[standard]==0.27.0
python-multipart==0.0.6

# Database
sqlalchemy[asyncio]==2.0.25
asyncpg==0.29.0
alembic==1.13.1

# Redis
aioredis==2.0.1

# Authentication & Security
python-jose[cryptography]==3.3.0
passlib[argon2]==1.7.4
pyotp==2.9.0
qrcode[pil]==7.4.2
bcrypt==4.1.2

# OAuth 2.0
authlib==1.3.0
httpx==0.26.0

# Validation & Serialization
pydantic==2.5.3
pydantic-settings==2.1.0
email-validator==2.1.0

# Testing
pytest==7.4.4
pytest-asyncio==0.23.3
httpx==0.26.0

# Monitoring & Logging
structlog==23.3.0
prometheus-client==0.19.0

# Utilities
python-dotenv==1.0.0
tenacity==8.2.3
```

### Appendix B: Environment Variables

```bash
# Application
APP_NAME="Smart Dairy Auth Service"
DEBUG=false
LOG_LEVEL=INFO

# Database
POSTGRES_HOST=localhost
POSTGRES_PORT=5432
POSTGRES_USER=smartdairy
POSTGRES_PASSWORD=<strong-password>
POSTGRES_DB=smartdairy_auth

# Redis
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=<redis-password>
REDIS_DB=0

# JWT Configuration
JWT_SECRET_KEY=<generate-strong-key>
JWT_PRIVATE_KEY_PATH=/certs/jwt-private.pem
JWT_PUBLIC_KEY_PATH=/certs/jwt-public.pem
JWT_ALGORITHM=RS256
JWT_ACCESS_TOKEN_EXPIRE_MINUTES=15
JWT_REFRESH_TOKEN_EXPIRE_DAYS=7

# Security
ARGON2_TIME_COST=3
ARGON2_MEMORY_COST=65536
ARGON2_PARALLELISM=4

# MFA
MFA_TOTP_ISSUER="Smart Dairy Ltd"
MFA_TOTP_DIGITS=6
MFA_TOTP_INTERVAL=30

# OAuth 2.0
OAUTH_CODE_EXPIRE_MINUTES=10

# Email (for MFA and notifications)
SMTP_HOST=smtp.sendgrid.net
SMTP_PORT=587
SMTP_USER=apikey
SMTP_PASSWORD=<sendgrid-api-key>
FROM_EMAIL=noreply@smartdairy.com

# SMS (Twilio)
TWILIO_ACCOUNT_SID=<account-sid>
TWILIO_AUTH_TOKEN=<auth-token>
TWILIO_PHONE_NUMBER=+1234567890

# Rate Limiting
RATE_LIMIT_DEFAULT=100/hour
RATE_LIMIT_LOGIN=5/minute
```

### Appendix C: Database Schema

```sql
-- Users table
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone_number VARCHAR(20),
    farm_id UUID REFERENCES farms(id),
    
    -- Status flags
    is_active BOOLEAN DEFAULT TRUE,
    is_verified BOOLEAN DEFAULT FALSE,
    is_locked BOOLEAN DEFAULT FALSE,
    
    -- MFA settings
    mfa_enabled BOOLEAN DEFAULT FALSE,
    mfa_type VARCHAR(20),
    totp_secret VARCHAR(255),
    backup_codes TEXT[], -- Hashed codes
    
    -- Timestamps
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    last_login TIMESTAMP WITH TIME ZONE,
    password_changed_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    -- Security
    failed_login_attempts INTEGER DEFAULT 0,
    locked_until TIMESTAMP WITH TIME ZONE
);

-- Roles table
CREATE TABLE roles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    permissions TEXT[] NOT NULL,
    is_system_role BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- User-Role mapping
CREATE TABLE user_roles (
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    role_id UUID REFERENCES roles(id) ON DELETE CASCADE,
    assigned_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    assigned_by UUID REFERENCES users(id),
    PRIMARY KEY (user_id, role_id)
);

-- Sessions table
CREATE TABLE sessions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    refresh_token_hash VARCHAR(255) NOT NULL,
    device_info JSONB,
    ip_address INET,
    user_agent TEXT,
    
    -- Timestamps
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    expires_at TIMESTAMP WITH TIME ZONE NOT NULL,
    last_active_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    -- Status
    is_active BOOLEAN DEFAULT TRUE,
    revoked_at TIMESTAMP WITH TIME ZONE,
    revoked_reason VARCHAR(50)
);

-- Password history (prevent reuse)
CREATE TABLE password_history (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Login attempts (rate limiting, audit)
CREATE TABLE login_attempts (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    username VARCHAR(50),
    ip_address INET NOT NULL,
    user_agent TEXT,
    success BOOLEAN NOT NULL,
    failure_reason VARCHAR(50),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- OAuth 2.0 Clients
CREATE TABLE oauth_clients (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    client_id VARCHAR(100) UNIQUE NOT NULL,
    client_secret_hash VARCHAR(255),
    client_name VARCHAR(100) NOT NULL,
    client_type VARCHAR(20) NOT NULL, -- 'confidential' or 'public'
    redirect_uris TEXT[] NOT NULL,
    allowed_grant_types TEXT[] NOT NULL,
    allowed_scopes TEXT[] DEFAULT ARRAY['read'],
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Audit log
CREATE TABLE audit_log (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id),
    action VARCHAR(50) NOT NULL,
    resource_type VARCHAR(50),
    resource_id VARCHAR(100),
    details JSONB,
    ip_address INET,
    user_agent TEXT,
    timestamp TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_farm ON users(farm_id);
CREATE INDEX idx_sessions_user ON sessions(user_id);
CREATE INDEX idx_sessions_refresh_token ON sessions(refresh_token_hash);
CREATE INDEX idx_login_attempts_ip ON login_attempts(ip_address);
CREATE INDEX idx_login_attempts_created ON login_attempts(created_at);
CREATE INDEX idx_audit_user ON audit_log(user_id);
CREATE INDEX idx_audit_timestamp ON audit_log(timestamp);
```

### Appendix D: Docker Compose Configuration

```yaml
version: '3.8'

services:
  api:
    build: .
    ports:
      - "8000:8000"
    environment:
      - POSTGRES_HOST=postgres
      - REDIS_HOST=redis
      - JWT_PRIVATE_KEY_PATH=/run/secrets/jwt_private_key
      - JWT_PUBLIC_KEY_PATH=/run/secrets/jwt_public_key
    secrets:
      - jwt_private_key
      - jwt_public_key
    depends_on:
      - postgres
      - redis
    networks:
      - auth-network

  postgres:
    image: postgres:16-alpine
    environment:
      POSTGRES_USER: smartdairy
      POSTGRES_PASSWORD_FILE: /run/secrets/db_password
      POSTGRES_DB: smartdairy_auth
    volumes:
      - postgres_data:/var/lib/postgresql/data
      - ./init.sql:/docker-entrypoint-initdb.d/init.sql
    secrets:
      - db_password
    networks:
      - auth-network

  redis:
    image: redis:7-alpine
    command: redis-server --requirepass ${REDIS_PASSWORD}
    volumes:
      - redis_data:/data
    networks:
      - auth-network

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf
      - ./ssl:/etc/nginx/ssl
    depends_on:
      - api
    networks:
      - auth-network

volumes:
  postgres_data:
  redis_data:

secrets:
  db_password:
    file: ./secrets/db_password.txt
  jwt_private_key:
    file: ./secrets/jwt-private.pem
  jwt_public_key:
    file: ./secrets/jwt-public.pem

networks:
  auth-network:
    driver: bridge
```

### Appendix E: RSA Key Generation

```bash
#!/bin/bash
# generate_keys.sh - Generate RSA key pair for JWT signing

KEY_DIR="./secrets"
mkdir -p $KEY_DIR

# Generate private key (4096 bits)
openssl genrsa -out $KEY_DIR/jwt-private.pem 4096

# Extract public key
openssl rsa -in $KEY_DIR/jwt-private.pem -pubout -out $KEY_DIR/jwt-public.pem

# Set permissions (readable only by owner)
chmod 600 $KEY_DIR/jwt-private.pem
chmod 644 $KEY_DIR/jwt-public.pem

echo "Keys generated in $KEY_DIR/"
echo "Private key: jwt-private.pem"
echo "Public key:  jwt-public.pem"

# Generate secure random secret for symmetric operations
openssl rand -base64 64 > $KEY_DIR/jwt-symmetric-secret.txt
echo "Symmetric secret: jwt-symmetric-secret.txt"
```

### Appendix F: Migration Script

```python
"""
Alembic migration script for initial auth schema
"""
from alembic import op
import sqlalchemy as sa
from sqlalchemy.dialects import postgresql

# revision identifiers
revision = '001_initial_auth'
down_revision = None
branch_labels = None
depends_on = None


def upgrade():
    # Create users table
    op.create_table(
        'users',
        sa.Column('id', postgresql.UUID(), server_default=sa.text('gen_random_uuid()'), nullable=False),
        sa.Column('username', sa.String(50), nullable=False),
        sa.Column('email', sa.String(255), nullable=False),
        sa.Column('password_hash', sa.String(255), nullable=False),
        sa.Column('full_name', sa.String(100)),
        sa.Column('phone_number', sa.String(20)),
        sa.Column('farm_id', postgresql.UUID()),
        sa.Column('is_active', sa.Boolean(), default=True),
        sa.Column('is_verified', sa.Boolean(), default=False),
        sa.Column('is_locked', sa.Boolean(), default=False),
        sa.Column('mfa_enabled', sa.Boolean(), default=False),
        sa.Column('mfa_type', sa.String(20)),
        sa.Column('totp_secret', sa.String(255)),
        sa.Column('backup_codes', postgresql.ARRAY(sa.Text())),
        sa.Column('created_at', sa.DateTime(timezone=True), server_default=sa.text('NOW()')),
        sa.Column('updated_at', sa.DateTime(timezone=True), server_default=sa.text('NOW()')),
        sa.Column('last_login', sa.DateTime(timezone=True)),
        sa.Column('password_changed_at', sa.DateTime(timezone=True), server_default=sa.text('NOW()')),
        sa.Column('failed_login_attempts', sa.Integer(), default=0),
        sa.Column('locked_until', sa.DateTime(timezone=True)),
        sa.PrimaryKeyConstraint('id'),
        sa.UniqueConstraint('username'),
        sa.UniqueConstraint('email')
    )
    
    # Create indexes
    op.create_index('idx_users_email', 'users', ['email'])
    op.create_index('idx_users_username', 'users', ['username'])
    
    # Create roles table
    op.create_table(
        'roles',
        sa.Column('id', postgresql.UUID(), server_default=sa.text('gen_random_uuid()'), nullable=False),
        sa.Column('name', sa.String(50), nullable=False),
        sa.Column('description', sa.Text()),
        sa.Column('permissions', postgresql.ARRAY(sa.Text()), nullable=False),
        sa.Column('is_system_role', sa.Boolean(), default=False),
        sa.Column('created_at', sa.DateTime(timezone=True), server_default=sa.text('NOW()')),
        sa.PrimaryKeyConstraint('id'),
        sa.UniqueConstraint('name')
    )
    
    # Create user_roles table
    op.create_table(
        'user_roles',
        sa.Column('user_id', postgresql.UUID(), nullable=False),
        sa.Column('role_id', postgresql.UUID(), nullable=False),
        sa.Column('assigned_at', sa.DateTime(timezone=True), server_default=sa.text('NOW()')),
        sa.Column('assigned_by', postgresql.UUID()),
        sa.ForeignKeyConstraint(['user_id'], ['users.id'], ondelete='CASCADE'),
        sa.ForeignKeyConstraint(['role_id'], ['roles.id'], ondelete='CASCADE'),
        sa.PrimaryKeyConstraint('user_id', 'role_id')
    )
    
    # Insert default roles
    op.execute("""
        INSERT INTO roles (name, description, permissions, is_system_role) VALUES
        ('super_admin', 'Full system access', 
         ARRAY['*:*'], TRUE),
        ('farm_admin', 'Farm management access',
         ARRAY['cattle:*', 'milk_production:*', 'health_record:*', 'breeding:*', 
               'financial:*', 'inventory:*', 'report:*', 'user:read', 'user:create'], TRUE),
        ('herd_manager', 'Herd operations',
         ARRAY['cattle:read', 'cattle:create', 'cattle:update', 'cattle:export',
               'health_record:read', 'health_record:create', 'health_record:update',
               'breeding:*', 'report:read', 'report:create'], TRUE),
        ('milk_manager', 'Milk production management',
         ARRAY['cattle:read', 'milk_production:*', 'report:read', 'report:create'], TRUE),
        ('veterinarian', 'Animal health management',
         ARRAY['cattle:read', 'health_record:*', 'breeding:read', 'report:read'], TRUE),
        ('data_analyst', 'Analytics and reporting',
         ARRAY['cattle:read', 'milk_production:read', 'health_record:read',
               'breeding:read', 'financial:read', 'report:*'], TRUE)
    """)


def downgrade():
    op.drop_table('user_roles')
    op.drop_table('roles')
    op.drop_index('idx_users_username', table_name='users')
    op.drop_index('idx_users_email', table_name='users')
    op.drop_table('users')
```

---

## Document Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Author | Security Lead | _________________ | January 31, 2026 |
| Owner | Security Lead | _________________ | January 31, 2026 |
| Reviewer | IT Director | _________________ | January 31, 2026 |
| Approver | CTO | _________________ | January 31, 2026 |

---

*Document ID: F-002*  
*Version: 1.0*  
*Classification: Internal Use*  
*© 2026 Smart Dairy Ltd. All rights reserved.*
