# Milestone 51: Flutter Project Foundation

## Smart Dairy Digital Smart Portal + ERP — Phase 6: Mobile App Foundation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 51 of 150 (1 of 10 in Phase 6)                                         |
| **Title**        | Flutter Project Foundation                                             |
| **Phase**        | Phase 6 — Mobile App Foundation                                        |
| **Days**         | Days 251-260 (10 working days)                                         |
| **Duration**     | 10 working days                                                        |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Mobile Lead)          |
| **Last Updated** | 2026-02-03                                                             |

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

### 1.1 Sprint Goal

Establish a robust Flutter monorepo architecture with shared packages, Clean Architecture implementation, CI/CD pipeline, Firebase integration, and foundational components that will serve as the common base for all three Smart Dairy mobile applications: Customer App, Field Sales App, and Farmer App.

### 1.2 Objectives

1. Create Flutter monorepo structure using Melos package manager
2. Implement Clean Architecture (presentation, domain, data layers)
3. Set up Riverpod state management with code generation
4. Configure Drift (SQLite) for offline data persistence
5. Set up Hive for fast key-value caching
6. Create shared Dio API client with JWT interceptors
7. Integrate Firebase services (FCM, Analytics, Crashlytics)
8. Establish CI/CD pipeline with CodeMagic
9. Create design system with Smart Dairy brand components
10. Implement localization framework (English and Bangla)

### 1.3 Key Deliverables

| Deliverable                     | Owner  | Format            | Due Day |
| ------------------------------- | ------ | ----------------- | ------- |
| Flutter Monorepo Structure      | Dev 3  | Project structure | 251     |
| Shared Core Package             | Dev 3  | Dart package      | 252     |
| Mobile REST API Module          | Dev 1  | Odoo module       | 253     |
| Dio API Client with JWT         | Dev 3  | Dart package      | 254     |
| Drift Database Schema           | Dev 3  | Dart package      | 255     |
| Firebase Project Setup          | Dev 2  | Firebase config   | 256     |
| CI/CD Pipeline                  | Dev 2  | CodeMagic YAML    | 257     |
| Design System Components        | Dev 3  | Dart package      | 258     |
| Localization (en/bn)            | Dev 3  | ARB files         | 259     |
| Three App Scaffolds             | Dev 3  | Flutter apps      | 260     |

### 1.4 Prerequisites

- Phase 5 complete and all REST APIs stable
- Odoo 19 CE development environment operational
- Firebase account created and billing enabled
- Google Play Console account registered
- Apple Developer account registered ($99/year paid)
- CodeMagic account created
- Development machines with Flutter SDK 3.16+
- Android Studio and Xcode installed
- VS Code with Flutter extensions

### 1.5 Success Criteria

- [ ] Monorepo builds successfully for all three apps
- [ ] Shared packages have >90% code coverage
- [ ] API client successfully authenticates with Odoo
- [ ] Drift database schema matches backend models
- [ ] Firebase push notifications work on both platforms
- [ ] CI/CD pipeline completes in <15 minutes
- [ ] Design system matches brand guidelines
- [ ] English and Bangla translations render correctly
- [ ] All apps launch without errors on iOS and Android

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference        |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------- |
| RFP-MOB-001  | RFP    | Cross-platform mobile development        | 251-260 | Flutter monorepo      |
| RFP-MOB-011  | RFP    | Offline-first architecture               | 255     | Drift SQLite setup    |
| SRS-MOB-001  | SRS    | Flutter 3.16+ with Dart 3.2+             | 251     | SDK installation      |
| SRS-MOB-002  | SRS    | Riverpod state management                | 252     | Core package setup    |
| SRS-MOB-003  | SRS    | Drift SQLite for offline storage         | 255     | Database schema       |
| BRD-MOB-001  | BRD    | Support 50,000+ customers                | 253     | Scalable API design   |
| H-001        | Impl   | Mobile App Architecture                  | 251-252 | Clean Architecture    |

---

## 3. Day-by-Day Breakdown

### Day 251 — Flutter SDK & Monorepo Setup

**Objective:** Install Flutter SDK, create monorepo structure with Melos, and establish project organization for all three apps.

#### Dev 1 — Backend Lead (8h)

**Task 1: Mobile API Gateway Planning (4h)**

```python
# odoo/addons/smart_dairy_mobile_api/__manifest__.py
{
    'name': 'Smart Dairy Mobile API',
    'version': '19.0.1.0.0',
    'category': 'Technical',
    'summary': 'REST API endpoints for mobile applications',
    'description': '''
        Smart Dairy Mobile API Module
        =============================
        This module provides REST API endpoints for:
        - Customer App (B2C e-commerce)
        - Field Sales App (Route management)
        - Farmer App (Farm data entry)

        Features:
        - JWT authentication
        - Rate limiting
        - Request validation
        - Response formatting
        - Error handling
        - API versioning
    ''',
    'author': 'Smart Dairy',
    'website': 'https://smartdairy.com.bd',
    'depends': [
        'base',
        'sale',
        'product',
        'stock',
        'contacts',
        'smart_dairy_farm',  # From Phase 5
        'smart_dairy_b2c',   # From Phase 3
    ],
    'data': [
        'security/ir.model.access.csv',
        'data/ir_config_parameter.xml',
        'views/mobile_api_log_views.xml',
    ],
    'installable': True,
    'application': False,
    'auto_install': False,
    'license': 'LGPL-3',
}
```

**Task 2: JWT Authentication Service Design (4h)**

```python
# odoo/addons/smart_dairy_mobile_api/models/jwt_service.py
import jwt
import logging
from datetime import datetime, timedelta
from odoo import models, api
from odoo.exceptions import AccessDenied

_logger = logging.getLogger(__name__)

class JWTService(models.AbstractModel):
    _name = 'mobile.jwt.service'
    _description = 'JWT Token Management Service'

    @api.model
    def get_secret_key(self):
        """Get JWT secret key from system parameters"""
        return self.env['ir.config_parameter'].sudo().get_param(
            'mobile.api.jwt.secret',
            default='smart_dairy_jwt_secret_change_in_production'
        )

    @api.model
    def get_token_expiry_hours(self):
        """Get token expiry hours from system parameters"""
        return int(self.env['ir.config_parameter'].sudo().get_param(
            'mobile.api.jwt.expiry_hours',
            default='24'
        ))

    @api.model
    def get_refresh_token_expiry_days(self):
        """Get refresh token expiry days"""
        return int(self.env['ir.config_parameter'].sudo().get_param(
            'mobile.api.jwt.refresh_expiry_days',
            default='30'
        ))

    @api.model
    def generate_tokens(self, user_id, device_id=None):
        """
        Generate access and refresh tokens for a user.

        Args:
            user_id: The Odoo user ID
            device_id: Optional device identifier for tracking

        Returns:
            dict: Contains access_token, refresh_token, and expiry info
        """
        secret = self.get_secret_key()
        access_expiry = datetime.utcnow() + timedelta(hours=self.get_token_expiry_hours())
        refresh_expiry = datetime.utcnow() + timedelta(days=self.get_refresh_token_expiry_days())

        # Access token payload
        access_payload = {
            'user_id': user_id,
            'device_id': device_id,
            'type': 'access',
            'exp': access_expiry,
            'iat': datetime.utcnow(),
        }

        # Refresh token payload
        refresh_payload = {
            'user_id': user_id,
            'device_id': device_id,
            'type': 'refresh',
            'exp': refresh_expiry,
            'iat': datetime.utcnow(),
        }

        access_token = jwt.encode(access_payload, secret, algorithm='HS256')
        refresh_token = jwt.encode(refresh_payload, secret, algorithm='HS256')

        # Log token generation
        _logger.info(f'Generated tokens for user {user_id}, device {device_id}')

        return {
            'access_token': access_token,
            'refresh_token': refresh_token,
            'access_expires_in': self.get_token_expiry_hours() * 3600,
            'refresh_expires_in': self.get_refresh_token_expiry_days() * 86400,
            'token_type': 'Bearer',
        }

    @api.model
    def verify_token(self, token, token_type='access'):
        """
        Verify and decode a JWT token.

        Args:
            token: The JWT token string
            token_type: Expected token type ('access' or 'refresh')

        Returns:
            dict: Decoded token payload

        Raises:
            AccessDenied: If token is invalid or expired
        """
        try:
            secret = self.get_secret_key()
            payload = jwt.decode(token, secret, algorithms=['HS256'])

            # Verify token type
            if payload.get('type') != token_type:
                raise AccessDenied(f'Invalid token type. Expected {token_type}')

            return payload

        except jwt.ExpiredSignatureError:
            _logger.warning('Token expired')
            raise AccessDenied('Token has expired')
        except jwt.InvalidTokenError as e:
            _logger.warning(f'Invalid token: {str(e)}')
            raise AccessDenied('Invalid token')

    @api.model
    def refresh_access_token(self, refresh_token):
        """
        Generate new access token using refresh token.

        Args:
            refresh_token: Valid refresh token

        Returns:
            dict: New access token info
        """
        payload = self.verify_token(refresh_token, token_type='refresh')
        user_id = payload.get('user_id')
        device_id = payload.get('device_id')

        # Generate new access token only
        secret = self.get_secret_key()
        access_expiry = datetime.utcnow() + timedelta(hours=self.get_token_expiry_hours())

        access_payload = {
            'user_id': user_id,
            'device_id': device_id,
            'type': 'access',
            'exp': access_expiry,
            'iat': datetime.utcnow(),
        }

        access_token = jwt.encode(access_payload, secret, algorithm='HS256')

        return {
            'access_token': access_token,
            'expires_in': self.get_token_expiry_hours() * 3600,
            'token_type': 'Bearer',
        }

    @api.model
    def revoke_token(self, token):
        """
        Revoke a token by adding it to blacklist.

        Args:
            token: The token to revoke
        """
        # Store in Redis or database blacklist
        self.env['mobile.token.blacklist'].sudo().create({
            'token_hash': self._hash_token(token),
            'revoked_at': datetime.now(),
        })

    def _hash_token(self, token):
        """Create hash of token for blacklist storage"""
        import hashlib
        return hashlib.sha256(token.encode()).hexdigest()
```

#### Dev 2 — Full-Stack (8h)

**Task 1: Firebase Project Setup (4h)**

1. Create Firebase project "smart-dairy-mobile"
2. Enable Authentication providers (Phone, Email, Google, Facebook)
3. Set up Cloud Messaging for push notifications
4. Configure Analytics and Crashlytics
5. Create iOS and Android apps in Firebase console
6. Download configuration files:
   - `google-services.json` (Android)
   - `GoogleService-Info.plist` (iOS)

**Task 2: CodeMagic Account Setup (2h)**

1. Create CodeMagic account and link GitHub
2. Create workflow templates for:
   - Development builds (on push to develop)
   - Staging builds (on push to staging)
   - Production builds (on push to main)

**Task 3: App Store Accounts Verification (2h)**

1. Verify Google Play Console access
2. Verify Apple Developer account
3. Create app identifiers:
   - `com.smartdairy.customer`
   - `com.smartdairy.fieldsales`
   - `com.smartdairy.farmer`
4. Set up iOS provisioning profiles

#### Dev 3 — Mobile Lead (8h)

**Task 1: Flutter SDK Installation & Configuration (2h)**

```bash
# Install Flutter SDK on development machines
git clone https://github.com/flutter/flutter.git -b stable ~/flutter
export PATH="$PATH:$HOME/flutter/bin"

# Verify installation
flutter doctor -v

# Expected output - all checkmarks
# [✓] Flutter (Channel stable, 3.16.x)
# [✓] Android toolchain - develop for Android devices
# [✓] Xcode - develop for iOS and macOS
# [✓] Chrome - develop for the web
# [✓] Android Studio
# [✓] VS Code

# Enable platforms
flutter config --enable-android
flutter config --enable-ios
flutter config --enable-web  # For testing

# Install Melos for monorepo management
dart pub global activate melos
```

**Task 2: Create Monorepo Structure (4h)**

```bash
# Create root project directory
mkdir smart_dairy_mobile
cd smart_dairy_mobile

# Initialize git repository
git init

# Create directory structure
mkdir -p apps/customer
mkdir -p apps/field_sales
mkdir -p apps/farmer
mkdir -p packages/core
mkdir -p packages/ui
mkdir -p packages/api_client
mkdir -p packages/database
mkdir -p packages/auth
mkdir -p scripts
mkdir -p .github/workflows
```

**Task 3: Configure Melos (2h)**

```yaml
# melos.yaml
name: smart_dairy_mobile
repository: https://github.com/smartdairy/smart_dairy_mobile

packages:
  - apps/*
  - packages/*

ide:
  intellij:
    enabled: true
    moduleNamePrefix: smart_dairy_

command:
  bootstrap:
    usePubspecOverrides: true

scripts:
  analyze:
    run: melos exec -- "flutter analyze --no-fatal-infos"
    description: Run flutter analyze in all packages

  test:
    run: melos exec -- "flutter test --coverage"
    description: Run tests in all packages

  test:ci:
    run: melos exec --fail-fast -- "flutter test --coverage"
    description: Run tests in CI (fail fast mode)

  format:
    run: melos exec -- "dart format ."
    description: Format all Dart code

  format:check:
    run: melos exec -- "dart format --set-exit-if-changed ."
    description: Check code formatting

  clean:
    run: melos exec -- "flutter clean"
    description: Clean all packages

  build:android:
    run: |
      cd apps/customer && flutter build apk --release
      cd ../field_sales && flutter build apk --release
      cd ../farmer && flutter build apk --release
    description: Build Android APKs for all apps

  build:ios:
    run: |
      cd apps/customer && flutter build ios --release --no-codesign
      cd ../field_sales && flutter build ios --release --no-codesign
      cd ../farmer && flutter build ios --release --no-codesign
    description: Build iOS apps for all apps

  gen:
    run: melos exec --depends-on="build_runner" -- "dart run build_runner build --delete-conflicting-outputs"
    description: Run build_runner in all packages

  gen:watch:
    run: melos exec --depends-on="build_runner" -- "dart run build_runner watch --delete-conflicting-outputs"
    description: Watch and run build_runner

  l10n:
    run: melos exec --depends-on="flutter_localizations" -- "flutter gen-l10n"
    description: Generate localizations
```

**End-of-Day 251 Deliverables:**

- [ ] Flutter SDK installed on all development machines
- [ ] Monorepo structure created with Melos
- [ ] Git repository initialized with .gitignore
- [ ] Firebase project created
- [ ] CodeMagic account connected to repository
- [ ] Mobile API module manifest created

---

### Day 252 — Core Package & Clean Architecture

**Objective:** Create the shared core package with Clean Architecture layers, dependency injection, and base classes.

#### Dev 1 — Backend Lead (8h)

**Task 1: API Base Controller (4h)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/base_controller.py
from odoo import http
from odoo.http import request
import json
import logging
import functools
from datetime import datetime

_logger = logging.getLogger(__name__)


def api_response(success=True, data=None, message=None, error_code=None, status_code=200):
    """
    Standard API response format for mobile apps.

    Args:
        success: Boolean indicating success/failure
        data: Response data (dict or list)
        message: Human-readable message
        error_code: Machine-readable error code
        status_code: HTTP status code

    Returns:
        JSON response
    """
    response = {
        'success': success,
        'timestamp': datetime.utcnow().isoformat(),
    }

    if data is not None:
        response['data'] = data

    if message:
        response['message'] = message

    if error_code:
        response['error_code'] = error_code

    return response


def jwt_required(func):
    """
    Decorator to require JWT authentication for an endpoint.

    Usage:
        @http.route('/api/v1/protected', type='json', auth='none')
        @jwt_required
        def protected_endpoint(self, **kwargs):
            user = request.env.user
            ...
    """
    @functools.wraps(func)
    def wrapper(self, *args, **kwargs):
        # Get authorization header
        auth_header = request.httprequest.headers.get('Authorization', '')

        if not auth_header.startswith('Bearer '):
            return api_response(
                success=False,
                message='Missing or invalid Authorization header',
                error_code='AUTH_MISSING',
                status_code=401
            )

        token = auth_header[7:]  # Remove 'Bearer ' prefix

        try:
            # Verify token
            jwt_service = request.env['mobile.jwt.service'].sudo()
            payload = jwt_service.verify_token(token)

            # Set user context
            user_id = payload.get('user_id')
            user = request.env['res.users'].sudo().browse(user_id)

            if not user.exists():
                return api_response(
                    success=False,
                    message='User not found',
                    error_code='USER_NOT_FOUND',
                    status_code=401
                )

            # Update request environment with authenticated user
            request.update_env(user=user)

            # Add user info to kwargs for convenience
            kwargs['current_user'] = user
            kwargs['jwt_payload'] = payload

            return func(self, *args, **kwargs)

        except Exception as e:
            _logger.warning(f'JWT authentication failed: {str(e)}')
            return api_response(
                success=False,
                message=str(e),
                error_code='AUTH_FAILED',
                status_code=401
            )

    return wrapper


def rate_limit(max_requests=100, window_seconds=60):
    """
    Rate limiting decorator using Redis.

    Args:
        max_requests: Maximum requests allowed in window
        window_seconds: Time window in seconds
    """
    def decorator(func):
        @functools.wraps(func)
        def wrapper(self, *args, **kwargs):
            # Get client identifier (IP or user ID)
            client_ip = request.httprequest.remote_addr
            auth_header = request.httprequest.headers.get('Authorization', '')

            if auth_header.startswith('Bearer '):
                # Use user ID from token if available
                try:
                    token = auth_header[7:]
                    jwt_service = request.env['mobile.jwt.service'].sudo()
                    payload = jwt_service.verify_token(token)
                    identifier = f"user:{payload.get('user_id')}"
                except:
                    identifier = f"ip:{client_ip}"
            else:
                identifier = f"ip:{client_ip}"

            # Rate limit key
            endpoint = request.httprequest.path
            key = f"rate_limit:{endpoint}:{identifier}"

            # Check rate limit using Redis
            redis_client = request.env['ir.config_parameter'].sudo()._get_redis_client()

            if redis_client:
                current = redis_client.get(key)

                if current and int(current) >= max_requests:
                    return api_response(
                        success=False,
                        message=f'Rate limit exceeded. Try again in {window_seconds} seconds.',
                        error_code='RATE_LIMIT_EXCEEDED',
                        status_code=429
                    )

                # Increment counter
                pipe = redis_client.pipeline()
                pipe.incr(key)
                pipe.expire(key, window_seconds)
                pipe.execute()

            return func(self, *args, **kwargs)

        return wrapper
    return decorator


class MobileAPIController(http.Controller):
    """
    Base controller for all mobile API endpoints.
    Provides common functionality and response formatting.
    """

    def _get_pagination_params(self, kwargs):
        """Extract pagination parameters from request"""
        page = int(kwargs.get('page', 1))
        limit = min(int(kwargs.get('limit', 20)), 100)  # Max 100 per page
        offset = (page - 1) * limit
        return page, limit, offset

    def _paginated_response(self, records, total, page, limit, serializer=None):
        """
        Create paginated response.

        Args:
            records: Odoo recordset
            total: Total count
            page: Current page
            limit: Items per page
            serializer: Optional function to serialize records
        """
        if serializer:
            items = [serializer(r) for r in records]
        else:
            items = records.read()

        return api_response(
            success=True,
            data={
                'items': items,
                'pagination': {
                    'page': page,
                    'limit': limit,
                    'total': total,
                    'total_pages': (total + limit - 1) // limit,
                    'has_next': page * limit < total,
                    'has_prev': page > 1,
                }
            }
        )

    def _validate_required_fields(self, data, required_fields):
        """
        Validate that required fields are present in data.

        Args:
            data: Request data dict
            required_fields: List of required field names

        Returns:
            tuple: (is_valid, error_response)
        """
        missing = [f for f in required_fields if not data.get(f)]

        if missing:
            return False, api_response(
                success=False,
                message=f"Missing required fields: {', '.join(missing)}",
                error_code='VALIDATION_ERROR',
                status_code=400
            )

        return True, None
```

**Task 2: Authentication Endpoints (4h)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/auth_controller.py
from odoo import http
from odoo.http import request
from .base_controller import MobileAPIController, api_response, rate_limit
import random
import re
import logging

_logger = logging.getLogger(__name__)


class AuthController(MobileAPIController):
    """Authentication API endpoints for mobile apps"""

    # ============== PHONE OTP AUTHENTICATION ==============

    @http.route('/api/v1/auth/otp/send', type='json', auth='none', methods=['POST'], csrf=False)
    @rate_limit(max_requests=5, window_seconds=300)  # 5 requests per 5 minutes
    def send_otp(self, **kwargs):
        """
        Send OTP to phone number.

        Request:
            {
                "phone": "+8801712345678"
            }

        Response:
            {
                "success": true,
                "data": {
                    "phone": "+8801712345678",
                    "expires_in": 300,
                    "resend_in": 60
                }
            }
        """
        phone = kwargs.get('phone', '').strip()

        # Validate phone format (Bangladesh)
        if not self._validate_bangladesh_phone(phone):
            return api_response(
                success=False,
                message='Invalid phone number. Use format: +8801XXXXXXXXX',
                error_code='INVALID_PHONE'
            )

        # Generate 6-digit OTP
        otp = ''.join([str(random.randint(0, 9)) for _ in range(6)])

        # Store OTP (expires in 5 minutes)
        self.env['mobile.otp'].sudo().create({
            'phone': phone,
            'otp': otp,
            'purpose': 'login',
            'expires_at': fields.Datetime.add(fields.Datetime.now(), minutes=5),
        })

        # Send SMS via gateway
        sms_service = self.env['sms.service'].sudo()
        sms_sent = sms_service.send_otp_sms(phone, otp)

        if not sms_sent:
            return api_response(
                success=False,
                message='Failed to send OTP. Please try again.',
                error_code='SMS_FAILED'
            )

        _logger.info(f'OTP sent to {phone}')

        return api_response(
            success=True,
            data={
                'phone': phone,
                'expires_in': 300,
                'resend_in': 60,
            },
            message='OTP sent successfully'
        )

    @http.route('/api/v1/auth/otp/verify', type='json', auth='none', methods=['POST'], csrf=False)
    @rate_limit(max_requests=10, window_seconds=300)
    def verify_otp(self, **kwargs):
        """
        Verify OTP and authenticate user.

        Request:
            {
                "phone": "+8801712345678",
                "otp": "123456",
                "device_id": "device-uuid",
                "device_name": "Samsung Galaxy S21",
                "fcm_token": "firebase-token"
            }

        Response:
            {
                "success": true,
                "data": {
                    "access_token": "...",
                    "refresh_token": "...",
                    "expires_in": 86400,
                    "user": {
                        "id": 1,
                        "name": "John Doe",
                        "phone": "+8801712345678",
                        "email": null,
                        "is_new_user": false
                    }
                }
            }
        """
        phone = kwargs.get('phone', '').strip()
        otp = kwargs.get('otp', '').strip()
        device_id = kwargs.get('device_id')
        device_name = kwargs.get('device_name', 'Unknown Device')
        fcm_token = kwargs.get('fcm_token')

        # Validate OTP
        otp_record = self.env['mobile.otp'].sudo().search([
            ('phone', '=', phone),
            ('otp', '=', otp),
            ('expires_at', '>', fields.Datetime.now()),
            ('verified', '=', False),
        ], limit=1)

        if not otp_record:
            return api_response(
                success=False,
                message='Invalid or expired OTP',
                error_code='INVALID_OTP'
            )

        # Mark OTP as verified
        otp_record.write({'verified': True})

        # Find or create user
        partner = self.env['res.partner'].sudo().search([
            ('phone', '=', phone)
        ], limit=1)

        is_new_user = False

        if not partner:
            # Create new partner and user
            partner = self.env['res.partner'].sudo().create({
                'name': f'Customer {phone[-4:]}',
                'phone': phone,
                'customer_rank': 1,
            })

            user = self.env['res.users'].sudo().create({
                'name': partner.name,
                'login': phone,
                'partner_id': partner.id,
                'groups_id': [(6, 0, [self.env.ref('base.group_portal').id])],
            })

            is_new_user = True
        else:
            user = self.env['res.users'].sudo().search([
                ('partner_id', '=', partner.id)
            ], limit=1)

            if not user:
                user = self.env['res.users'].sudo().create({
                    'name': partner.name,
                    'login': phone,
                    'partner_id': partner.id,
                    'groups_id': [(6, 0, [self.env.ref('base.group_portal').id])],
                })

        # Register device
        if device_id:
            self._register_device(user.id, device_id, device_name, fcm_token)

        # Generate tokens
        jwt_service = self.env['mobile.jwt.service'].sudo()
        tokens = jwt_service.generate_tokens(user.id, device_id)

        return api_response(
            success=True,
            data={
                **tokens,
                'user': self._serialize_user(user, is_new_user)
            }
        )

    # ============== EMAIL AUTHENTICATION ==============

    @http.route('/api/v1/auth/login', type='json', auth='none', methods=['POST'], csrf=False)
    @rate_limit(max_requests=10, window_seconds=300)
    def email_login(self, **kwargs):
        """
        Login with email and password.

        Request:
            {
                "email": "user@example.com",
                "password": "password123",
                "device_id": "device-uuid",
                "fcm_token": "firebase-token"
            }
        """
        email = kwargs.get('email', '').strip().lower()
        password = kwargs.get('password', '')
        device_id = kwargs.get('device_id')
        fcm_token = kwargs.get('fcm_token')

        # Validate email format
        if not re.match(r'^[\w\.-]+@[\w\.-]+\.\w+$', email):
            return api_response(
                success=False,
                message='Invalid email format',
                error_code='INVALID_EMAIL'
            )

        # Authenticate
        try:
            uid = request.session.authenticate(request.db, email, password)

            if not uid:
                return api_response(
                    success=False,
                    message='Invalid credentials',
                    error_code='INVALID_CREDENTIALS'
                )

            user = self.env['res.users'].sudo().browse(uid)

            # Register device
            if device_id:
                self._register_device(uid, device_id, 'Unknown Device', fcm_token)

            # Generate tokens
            jwt_service = self.env['mobile.jwt.service'].sudo()
            tokens = jwt_service.generate_tokens(uid, device_id)

            return api_response(
                success=True,
                data={
                    **tokens,
                    'user': self._serialize_user(user, False)
                }
            )

        except Exception as e:
            _logger.warning(f'Login failed for {email}: {str(e)}')
            return api_response(
                success=False,
                message='Invalid credentials',
                error_code='INVALID_CREDENTIALS'
            )

    # ============== TOKEN REFRESH ==============

    @http.route('/api/v1/auth/refresh', type='json', auth='none', methods=['POST'], csrf=False)
    def refresh_token(self, **kwargs):
        """
        Refresh access token using refresh token.

        Request:
            {
                "refresh_token": "..."
            }
        """
        refresh_token = kwargs.get('refresh_token', '')

        if not refresh_token:
            return api_response(
                success=False,
                message='Refresh token is required',
                error_code='MISSING_TOKEN'
            )

        try:
            jwt_service = self.env['mobile.jwt.service'].sudo()
            tokens = jwt_service.refresh_access_token(refresh_token)

            return api_response(
                success=True,
                data=tokens
            )
        except Exception as e:
            return api_response(
                success=False,
                message=str(e),
                error_code='REFRESH_FAILED'
            )

    # ============== LOGOUT ==============

    @http.route('/api/v1/auth/logout', type='json', auth='none', methods=['POST'], csrf=False)
    def logout(self, **kwargs):
        """
        Logout and revoke tokens.

        Request:
            {
                "device_id": "device-uuid"  // Optional: logout from specific device
            }
        """
        auth_header = request.httprequest.headers.get('Authorization', '')
        device_id = kwargs.get('device_id')

        if auth_header.startswith('Bearer '):
            token = auth_header[7:]
            jwt_service = self.env['mobile.jwt.service'].sudo()
            jwt_service.revoke_token(token)

        if device_id:
            # Remove device registration
            self.env['mobile.device'].sudo().search([
                ('device_id', '=', device_id)
            ]).unlink()

        return api_response(
            success=True,
            message='Logged out successfully'
        )

    # ============== HELPER METHODS ==============

    def _validate_bangladesh_phone(self, phone):
        """Validate Bangladesh phone number format"""
        # Accepts: +8801XXXXXXXXX or 01XXXXXXXXX
        patterns = [
            r'^\+8801[3-9]\d{8}$',  # +8801712345678
            r'^01[3-9]\d{8}$',      # 01712345678
        ]
        return any(re.match(p, phone) for p in patterns)

    def _normalize_phone(self, phone):
        """Normalize phone to +880 format"""
        phone = re.sub(r'[^\d]', '', phone)
        if phone.startswith('01'):
            return '+880' + phone
        if phone.startswith('8801'):
            return '+' + phone
        return phone

    def _register_device(self, user_id, device_id, device_name, fcm_token):
        """Register or update device for push notifications"""
        Device = self.env['mobile.device'].sudo()

        existing = Device.search([
            ('user_id', '=', user_id),
            ('device_id', '=', device_id)
        ], limit=1)

        if existing:
            existing.write({
                'fcm_token': fcm_token,
                'last_active': fields.Datetime.now(),
            })
        else:
            Device.create({
                'user_id': user_id,
                'device_id': device_id,
                'device_name': device_name,
                'fcm_token': fcm_token,
            })

    def _serialize_user(self, user, is_new_user=False):
        """Serialize user for API response"""
        partner = user.partner_id
        return {
            'id': user.id,
            'name': user.name,
            'email': user.email or None,
            'phone': partner.phone or None,
            'image_url': f'/web/image/res.partner/{partner.id}/image_128' if partner.image_128 else None,
            'is_new_user': is_new_user,
        }
```

#### Dev 2 — Full-Stack (8h)

**Task 1: Firebase FlutterFire CLI Setup (3h)**

```bash
# Install FlutterFire CLI
dart pub global activate flutterfire_cli

# Configure Firebase for all apps
cd apps/customer
flutterfire configure --project=smart-dairy-mobile \
  --android-package-name=com.smartdairy.customer \
  --ios-bundle-id=com.smartdairy.customer

cd ../field_sales
flutterfire configure --project=smart-dairy-mobile \
  --android-package-name=com.smartdairy.fieldsales \
  --ios-bundle-id=com.smartdairy.fieldsales

cd ../farmer
flutterfire configure --project=smart-dairy-mobile \
  --android-package-name=com.smartdairy.farmer \
  --ios-bundle-id=com.smartdairy.farmer
```

**Task 2: Environment Configuration (3h)**

```dart
// packages/core/lib/src/config/environment.dart
enum Environment { development, staging, production }

class EnvironmentConfig {
  final Environment environment;
  final String apiBaseUrl;
  final String webSocketUrl;
  final bool enableLogging;
  final bool enableCrashlytics;
  final Duration apiTimeout;

  const EnvironmentConfig._({
    required this.environment,
    required this.apiBaseUrl,
    required this.webSocketUrl,
    required this.enableLogging,
    required this.enableCrashlytics,
    required this.apiTimeout,
  });

  static const development = EnvironmentConfig._(
    environment: Environment.development,
    apiBaseUrl: 'http://localhost:8069/api/v1',
    webSocketUrl: 'ws://localhost:8069/websocket',
    enableLogging: true,
    enableCrashlytics: false,
    apiTimeout: Duration(seconds: 60),
  );

  static const staging = EnvironmentConfig._(
    environment: Environment.staging,
    apiBaseUrl: 'https://staging-api.smartdairy.com.bd/api/v1',
    webSocketUrl: 'wss://staging-api.smartdairy.com.bd/websocket',
    enableLogging: true,
    enableCrashlytics: true,
    apiTimeout: Duration(seconds: 30),
  );

  static const production = EnvironmentConfig._(
    environment: Environment.production,
    apiBaseUrl: 'https://api.smartdairy.com.bd/api/v1',
    webSocketUrl: 'wss://api.smartdairy.com.bd/websocket',
    enableLogging: false,
    enableCrashlytics: true,
    apiTimeout: Duration(seconds: 30),
  );

  bool get isDevelopment => environment == Environment.development;
  bool get isStaging => environment == Environment.staging;
  bool get isProduction => environment == Environment.production;
}
```

**Task 3: Logging Service (2h)**

```dart
// packages/core/lib/src/services/logging_service.dart
import 'package:flutter/foundation.dart';
import 'package:firebase_crashlytics/firebase_crashlytics.dart';

enum LogLevel { debug, info, warning, error }

class LoggingService {
  final bool enableConsole;
  final bool enableCrashlytics;

  LoggingService({
    this.enableConsole = true,
    this.enableCrashlytics = false,
  });

  void debug(String message, [dynamic error, StackTrace? stackTrace]) {
    _log(LogLevel.debug, message, error, stackTrace);
  }

  void info(String message, [dynamic error, StackTrace? stackTrace]) {
    _log(LogLevel.info, message, error, stackTrace);
  }

  void warning(String message, [dynamic error, StackTrace? stackTrace]) {
    _log(LogLevel.warning, message, error, stackTrace);
  }

  void error(String message, [dynamic error, StackTrace? stackTrace]) {
    _log(LogLevel.error, message, error, stackTrace);

    if (enableCrashlytics && error != null) {
      FirebaseCrashlytics.instance.recordError(
        error,
        stackTrace,
        reason: message,
        fatal: false,
      );
    }
  }

  void fatal(String message, dynamic error, StackTrace? stackTrace) {
    _log(LogLevel.error, '[FATAL] $message', error, stackTrace);

    if (enableCrashlytics) {
      FirebaseCrashlytics.instance.recordError(
        error,
        stackTrace,
        reason: message,
        fatal: true,
      );
    }
  }

  void setUserId(String userId) {
    if (enableCrashlytics) {
      FirebaseCrashlytics.instance.setUserIdentifier(userId);
    }
  }

  void setCustomKey(String key, dynamic value) {
    if (enableCrashlytics) {
      FirebaseCrashlytics.instance.setCustomKey(key, value.toString());
    }
  }

  void _log(LogLevel level, String message, [dynamic error, StackTrace? stackTrace]) {
    if (!enableConsole) return;

    final timestamp = DateTime.now().toIso8601String();
    final prefix = _getLevelPrefix(level);

    if (kDebugMode) {
      debugPrint('$timestamp $prefix $message');
      if (error != null) {
        debugPrint('Error: $error');
      }
      if (stackTrace != null) {
        debugPrint('Stack trace:\n$stackTrace');
      }
    }
  }

  String _getLevelPrefix(LogLevel level) {
    switch (level) {
      case LogLevel.debug:
        return '[DEBUG]';
      case LogLevel.info:
        return '[INFO]';
      case LogLevel.warning:
        return '[WARN]';
      case LogLevel.error:
        return '[ERROR]';
    }
  }
}
```

#### Dev 3 — Mobile Lead (8h)

**Task 1: Core Package Structure (4h)**

```dart
// packages/core/pubspec.yaml
name: smart_dairy_core
description: Core shared functionality for Smart Dairy mobile apps
version: 1.0.0
publish_to: none

environment:
  sdk: '>=3.2.0 <4.0.0'
  flutter: '>=3.16.0'

dependencies:
  flutter:
    sdk: flutter

  # State Management
  flutter_riverpod: ^2.4.9
  riverpod_annotation: ^2.3.3

  # Networking
  dio: ^5.4.0
  retrofit: ^4.1.0
  json_annotation: ^4.8.1

  # Local Storage
  drift: ^2.14.1
  sqlite3_flutter_libs: ^0.5.18
  path_provider: ^2.1.1
  path: ^1.8.3
  hive: ^2.2.3
  hive_flutter: ^1.1.0
  flutter_secure_storage: ^9.0.0

  # Firebase
  firebase_core: ^2.24.2
  firebase_analytics: ^10.7.4
  firebase_crashlytics: ^3.4.8
  firebase_messaging: ^14.7.9

  # Utilities
  intl: ^0.19.0
  equatable: ^2.0.5
  uuid: ^4.2.2
  connectivity_plus: ^5.0.2

  # Freezed for immutable classes
  freezed_annotation: ^2.4.1

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.1
  build_runner: ^2.4.7
  riverpod_generator: ^2.3.9
  retrofit_generator: ^8.1.0
  json_serializable: ^6.7.1
  freezed: ^2.4.6
  drift_dev: ^2.14.1
  hive_generator: ^2.0.1
  mockito: ^5.4.4
  mocktail: ^1.0.1
```

**Task 2: Clean Architecture Layers (4h)**

```dart
// packages/core/lib/src/domain/entities/user.dart
import 'package:freezed_annotation/freezed_annotation.dart';

part 'user.freezed.dart';

@freezed
class User with _$User {
  const factory User({
    required int id,
    required String name,
    String? email,
    String? phone,
    String? imageUrl,
    @Default(false) bool isNewUser,
  }) = _User;
}

// packages/core/lib/src/domain/repositories/auth_repository.dart
import '../entities/user.dart';

abstract class AuthRepository {
  Future<AuthResult> sendOtp(String phone);
  Future<AuthResult> verifyOtp(String phone, String otp, {String? deviceId, String? fcmToken});
  Future<AuthResult> loginWithEmail(String email, String password, {String? deviceId, String? fcmToken});
  Future<AuthResult> refreshToken(String refreshToken);
  Future<void> logout({String? deviceId});
  Future<User?> getCurrentUser();
  Stream<User?> watchCurrentUser();
}

@freezed
class AuthResult with _$AuthResult {
  const factory AuthResult.success({
    required String accessToken,
    required String refreshToken,
    required int expiresIn,
    required User user,
  }) = AuthResultSuccess;

  const factory AuthResult.error({
    required String message,
    String? errorCode,
  }) = AuthResultError;
}

// packages/core/lib/src/domain/usecases/send_otp_usecase.dart
import '../repositories/auth_repository.dart';

class SendOtpUseCase {
  final AuthRepository _repository;

  SendOtpUseCase(this._repository);

  Future<AuthResult> execute(String phone) {
    return _repository.sendOtp(phone);
  }
}

// packages/core/lib/src/data/repositories/auth_repository_impl.dart
import '../../domain/repositories/auth_repository.dart';
import '../../domain/entities/user.dart';
import '../datasources/remote/auth_remote_datasource.dart';
import '../datasources/local/auth_local_datasource.dart';

class AuthRepositoryImpl implements AuthRepository {
  final AuthRemoteDataSource _remoteDataSource;
  final AuthLocalDataSource _localDataSource;

  AuthRepositoryImpl(this._remoteDataSource, this._localDataSource);

  @override
  Future<AuthResult> sendOtp(String phone) async {
    try {
      final response = await _remoteDataSource.sendOtp(phone);
      return AuthResult.success(
        accessToken: '',
        refreshToken: '',
        expiresIn: response.expiresIn,
        user: User(id: 0, name: ''),
      );
    } catch (e) {
      return AuthResult.error(message: e.toString());
    }
  }

  @override
  Future<AuthResult> verifyOtp(String phone, String otp, {String? deviceId, String? fcmToken}) async {
    try {
      final response = await _remoteDataSource.verifyOtp(phone, otp, deviceId: deviceId, fcmToken: fcmToken);

      // Save tokens locally
      await _localDataSource.saveTokens(
        accessToken: response.accessToken,
        refreshToken: response.refreshToken,
      );

      // Save user
      await _localDataSource.saveUser(response.user);

      return AuthResult.success(
        accessToken: response.accessToken,
        refreshToken: response.refreshToken,
        expiresIn: response.expiresIn,
        user: response.user,
      );
    } catch (e) {
      return AuthResult.error(message: e.toString());
    }
  }

  @override
  Future<User?> getCurrentUser() async {
    return _localDataSource.getUser();
  }

  @override
  Stream<User?> watchCurrentUser() {
    return _localDataSource.watchUser();
  }

  // ... implement other methods
}
```

**End-of-Day 252 Deliverables:**

- [ ] Core package structure created with Clean Architecture
- [ ] Domain entities defined (User, AuthResult)
- [ ] Repository interfaces defined
- [ ] Use cases implemented
- [ ] Backend authentication endpoints created
- [ ] Firebase configured for all apps
- [ ] Environment configuration system created

---

### Day 253-260 Breakdown Summary

Due to document size constraints, Days 253-260 follow the same detailed pattern:

**Day 253: Dio API Client & Interceptors**
- Dev 1: Complete REST API endpoints for products and categories
- Dev 2: Set up API monitoring and logging
- Dev 3: Create Dio client with JWT interceptors, error handling, retry logic

**Day 254: Riverpod Providers & State Management**
- Dev 1: User and session management APIs
- Dev 2: Redis caching for session data
- Dev 3: Riverpod providers for auth, user, connectivity states

**Day 255: Drift Database Schema**
- Dev 1: Sync metadata API endpoints
- Dev 2: Background sync job configuration
- Dev 3: Drift database with tables for products, cart, orders, animals

**Day 256: Firebase Integration**
- Dev 1: Push notification endpoints
- Dev 2: Firebase Cloud Functions for notifications
- Dev 3: FCM token handling, notification permissions, foreground/background handling

**Day 257: CI/CD Pipeline**
- Dev 1: API versioning and documentation
- Dev 2: CodeMagic workflows for Android and iOS builds
- Dev 3: Build flavors (dev, staging, prod)

**Day 258: Design System Components**
- Dev 1: Asset serving endpoints (images, icons)
- Dev 2: CDN configuration for static assets
- Dev 3: Theme, colors, typography, buttons, inputs, cards

**Day 259: Localization Framework**
- Dev 1: Translation API for dynamic content
- Dev 2: Translation management system
- Dev 3: ARB files for English and Bangla, localization delegates

**Day 260: App Scaffolds & Integration Testing**
- Dev 1: API integration testing
- Dev 2: E2E test environment setup
- Dev 3: Three app scaffolds with shared packages, navigation, basic screens

---

## 4. Technical Specifications

### 4.1 Monorepo Structure

```
smart_dairy_mobile/
├── apps/
│   ├── customer/           # B2C Customer App
│   │   ├── lib/
│   │   ├── android/
│   │   ├── ios/
│   │   └── pubspec.yaml
│   ├── field_sales/        # Field Sales App
│   │   ├── lib/
│   │   ├── android/
│   │   ├── ios/
│   │   └── pubspec.yaml
│   └── farmer/             # Farmer App
│       ├── lib/
│       ├── android/
│       ├── ios/
│       └── pubspec.yaml
├── packages/
│   ├── core/               # Shared business logic
│   │   ├── lib/
│   │   │   ├── src/
│   │   │   │   ├── domain/     # Entities, repositories, use cases
│   │   │   │   ├── data/       # Implementations, data sources
│   │   │   │   ├── config/     # Environment, constants
│   │   │   │   └── services/   # Logging, analytics
│   │   │   └── core.dart
│   │   └── pubspec.yaml
│   ├── api_client/         # Dio client and API interfaces
│   ├── database/           # Drift database
│   ├── ui/                 # Design system components
│   └── auth/               # Authentication logic
├── melos.yaml
├── pubspec.yaml
└── README.md
```

### 4.2 State Management Architecture

```dart
// Riverpod provider hierarchy
authStateProvider          // AsyncNotifier<AuthState>
  ├── currentUserProvider  // Provider<User?>
  ├── isLoggedInProvider   // Provider<bool>
  └── accessTokenProvider  // Provider<String?>

connectivityProvider       // StreamProvider<ConnectivityResult>

// Feature providers
productsProvider          // AsyncNotifier<List<Product>>
cartProvider              // AsyncNotifier<Cart>
ordersProvider            // AsyncNotifier<List<Order>>
```

### 4.3 Database Schema (Drift)

```dart
// Core tables
class Users extends Table {
  IntColumn get id => integer()();
  TextColumn get name => text()();
  TextColumn get email => text().nullable()();
  TextColumn get phone => text().nullable()();
  TextColumn get imageUrl => text().nullable()();
  DateTimeColumn get lastSync => dateTime()();

  @override
  Set<Column> get primaryKey => {id};
}

class SyncQueue extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get entityType => text()();
  IntColumn get entityId => integer()();
  TextColumn get action => text()(); // create, update, delete
  TextColumn get data => text()(); // JSON payload
  DateTimeColumn get createdAt => dateTime()();
  BoolColumn get synced => boolean().withDefault(const Constant(false))();
}
```

---

## 5. Testing & Validation

### 5.1 Unit Tests

```dart
// packages/core/test/domain/usecases/send_otp_usecase_test.dart
import 'package:flutter_test/flutter_test.dart';
import 'package:mocktail/mocktail.dart';
import 'package:smart_dairy_core/core.dart';

class MockAuthRepository extends Mock implements AuthRepository {}

void main() {
  late SendOtpUseCase useCase;
  late MockAuthRepository mockRepository;

  setUp(() {
    mockRepository = MockAuthRepository();
    useCase = SendOtpUseCase(mockRepository);
  });

  group('SendOtpUseCase', () {
    const testPhone = '+8801712345678';

    test('should call repository.sendOtp with correct phone', () async {
      // Arrange
      when(() => mockRepository.sendOtp(testPhone))
          .thenAnswer((_) async => AuthResult.success(...));

      // Act
      await useCase.execute(testPhone);

      // Assert
      verify(() => mockRepository.sendOtp(testPhone)).called(1);
    });

    test('should return error when repository fails', () async {
      // Arrange
      when(() => mockRepository.sendOtp(any()))
          .thenAnswer((_) async => AuthResult.error(message: 'Network error'));

      // Act
      final result = await useCase.execute(testPhone);

      // Assert
      expect(result, isA<AuthResultError>());
    });
  });
}
```

### 5.2 Test Coverage Requirements

| Package | Required Coverage | Focus Areas |
|---------|------------------|-------------|
| core | >90% | Use cases, repositories |
| api_client | >85% | Interceptors, error handling |
| database | >80% | DAOs, migrations |
| ui | >70% | Widget rendering |

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Flutter SDK issues | Low | High | Pin Flutter version, test early |
| Package compatibility | Medium | Medium | Use stable versions, test combinations |
| Firebase quota limits | Low | Medium | Monitor usage, set alerts |
| Build time too long | Medium | Low | Optimize CI, use caching |
| iOS signing issues | Medium | Medium | Set up certificates early |

---

## 7. Dependencies & Handoffs

### 7.1 Input Dependencies

| Dependency | Source | Required By |
|------------|--------|-------------|
| Phase 5 REST APIs | Phase 5 | Day 251 |
| Odoo 19 CE environment | Phase 1 | Day 251 |
| Firebase account | IT Admin | Day 251 |
| App Store accounts | IT Admin | Day 251 |

### 7.2 Output Handoffs

| Deliverable | Recipient | Handoff Day |
|-------------|-----------|-------------|
| Monorepo structure | Dev Team | 260 |
| Shared packages | All apps | 260 |
| CI/CD pipeline | Dev Team | 260 |
| API documentation | QA Team | 260 |

---

## Milestone Sign-off Checklist

- [ ] All code merged to develop branch
- [ ] CI/CD pipeline passing
- [ ] Test coverage requirements met
- [ ] Documentation updated
- [ ] Code review completed
- [ ] Demo conducted
- [ ] No P0/P1 bugs
- [ ] Ready for Milestone 52

---

**Document Prepared By:** Smart Dairy Development Team
**Review Status:** Pending Technical Review
**Next Milestone:** Milestone 52 — Customer App Authentication
