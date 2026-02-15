# Milestone 56: Field Sales App Core

## Smart Dairy Digital Smart Portal + ERP System
### Phase 6: Mobile App Foundation

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P6-M56-2026 |
| **Milestone Number** | 56 |
| **Milestone Title** | Field Sales App Core |
| **Phase** | 6 - Mobile App Foundation |
| **Sprint Days** | Days 301-310 (10 working days) |
| **Duration** | 2 weeks |
| **Version** | 1.0 |
| **Status** | Draft |
| **Created Date** | 2026-02-03 |
| **Last Updated** | 2026-02-03 |
| **Author** | Technical Architecture Team |
| **Reviewers** | Project Manager, Mobile Lead, QA Lead |

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

Build the Field Sales Mobile Application foundation with secure sales representative authentication, offline-capable customer database, customer registration functionality, credit limit tracking, and price list management for field operations.

### 1.2 Objectives

| # | Objective | Priority | Success Metric |
|---|-----------|----------|----------------|
| 1 | Implement sales rep authentication with PIN + device binding | Critical | Secure login functional |
| 2 | Build offline customer database with full sync | Critical | 1000+ customers offline |
| 3 | Create customer registration flow | Critical | New customers saved offline |
| 4 | Implement credit limit tracking and alerts | High | Real-time credit display |
| 5 | Build price list management with multiple lists | High | Price lists sync correctly |
| 6 | Create offline sync engine with conflict resolution | Critical | Sync within 1 minute |
| 7 | Implement customer search with filters | Medium | Search <500ms response |
| 8 | Build customer detail view with history | Medium | Order history accessible |
| 9 | Create dashboard with daily targets | High | KPIs displayed correctly |
| 10 | Implement device registration and management | Critical | Device binding secure |

### 1.3 Key Deliverables

| # | Deliverable | Type | Owner | Acceptance Criteria |
|---|-------------|------|-------|---------------------|
| 1 | Sales Rep Auth System | Full Stack | Dev 1/3 | PIN + device binding working |
| 2 | Customer Database API | Odoo REST | Dev 1 | CRUD + sync endpoints |
| 3 | Offline Customer Storage | Flutter/Drift | Dev 3 | 1000+ records offline |
| 4 | Customer Registration Screen | Flutter UI | Dev 3 | GPS capture, validation |
| 5 | Credit Limit Tracking | Full Stack | Dev 1/3 | Real-time updates |
| 6 | Price List APIs | Odoo REST | Dev 1 | Multiple price lists |
| 7 | Sync Engine | Flutter | Dev 2 | Conflict resolution |
| 8 | Customer Search | Flutter UI | Dev 3 | Multi-field search |
| 9 | Sales Dashboard | Flutter UI | Dev 3 | Target tracking |
| 10 | Device Management | Full Stack | Dev 2 | Registration, deactivation |

### 1.4 Prerequisites

| # | Prerequisite | Source | Status |
|---|--------------|--------|--------|
| 1 | Shared Flutter packages | Milestone 51 | Required |
| 2 | JWT authentication infrastructure | Milestone 51 | Required |
| 3 | Offline storage architecture | Milestone 51 | Required |
| 4 | Customer App patterns established | Milestone 55 | Required |
| 5 | Sales team data in Odoo | Configuration | Required |
| 6 | Price lists configured | Configuration | Required |

### 1.5 Success Criteria

- [ ] Sales rep can login with PIN and device is bound
- [ ] Customer database syncs within 1 minute
- [ ] Offline customer search works without network
- [ ] New customers can be registered offline
- [ ] Credit limits displayed and enforced
- [ ] Price lists sync and apply correctly
- [ ] Dashboard shows accurate daily targets
- [ ] App works for full 8-hour shift offline
- [ ] Code coverage >80%
- [ ] Battery usage <15% per 8-hour shift

---

## 2. Requirement Traceability Matrix

### 2.1 RFP Requirements

| RFP Req ID | Requirement Description | Implementation | Day |
|------------|------------------------|----------------|-----|
| RFP-MOB-006 | Field sales order taking | This + M57 | 301-310 |
| RFP-MOB-011 | Offline-first architecture | Sync engine | 305-306 |
| RFP-MOB-020 | Sales rep authentication | PIN + device binding | 301-302 |
| RFP-MOB-021 | Customer database offline | Drift storage | 303-304 |
| RFP-MOB-022 | Credit limit management | Credit tracking | 307-308 |

### 2.2 BRD Requirements

| BRD Req ID | Business Requirement | Implementation | Day |
|------------|---------------------|----------------|-----|
| BRD-MOB-003 | Field sales efficiency | Dashboard, targets | 309-310 |
| BRD-MOB-008 | New customer acquisition | Registration flow | 304 |
| BRD-MOB-009 | Credit control | Credit alerts | 307-308 |
| BRD-MOB-010 | Price accuracy | Price list sync | 308 |

### 2.3 SRS Requirements

| SRS Req ID | Technical Requirement | Implementation | Day |
|------------|----------------------|----------------|-----|
| SRS-MOB-040 | Device binding security | Device registration | 302 |
| SRS-MOB-041 | Offline data encryption | SQLCipher/encryption | 303 |
| SRS-MOB-042 | Sync conflict resolution | Last-write-wins + UI | 305-306 |
| SRS-MOB-043 | Background sync | WorkManager/BGTask | 306 |

---

## 3. Day-by-Day Breakdown

### Day 301 (Sprint Day 1): Field Sales Authentication Backend

#### Objectives
- Build sales rep authentication with PIN support
- Implement device registration API
- Create field sales user model extensions

#### Dev 1 - Backend Lead (8 hours)

**Task 1.1: Field Sales User Model (3 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/field_sales_user.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import hashlib
import secrets
from datetime import datetime, timedelta

class FieldSalesUser(models.Model):
    _name = 'field.sales.user'
    _description = 'Field Sales Representative'
    _inherits = {'res.users': 'user_id'}

    user_id = fields.Many2one('res.users', 'User', required=True, ondelete='cascade')
    employee_id = fields.Many2one('hr.employee', 'Employee', required=True)
    sales_team_id = fields.Many2one('crm.team', 'Sales Team')

    # PIN Authentication
    pin_hash = fields.Char('PIN Hash', readonly=True)
    pin_salt = fields.Char('PIN Salt', readonly=True)
    pin_attempts = fields.Integer('Failed PIN Attempts', default=0)
    pin_locked_until = fields.Datetime('PIN Locked Until')

    # Device Binding
    device_ids = fields.One2many('field.sales.device', 'sales_user_id', 'Registered Devices')
    max_devices = fields.Integer('Max Devices', default=2)

    # Territory & Assignment
    territory_ids = fields.Many2many('sales.territory', string='Assigned Territories')
    route_ids = fields.Many2many('sales.route', string='Assigned Routes')
    customer_ids = fields.Many2many('res.partner', string='Assigned Customers')

    # Targets
    daily_visit_target = fields.Integer('Daily Visit Target', default=25)
    daily_order_target = fields.Float('Daily Order Target (BDT)', default=50000)
    monthly_sales_target = fields.Float('Monthly Sales Target (BDT)')

    # Status
    is_active = fields.Boolean('Active', default=True)
    last_sync = fields.Datetime('Last Sync')
    current_latitude = fields.Float('Current Latitude', digits=(10, 7))
    current_longitude = fields.Float('Current Longitude', digits=(10, 7))
    location_updated = fields.Datetime('Location Updated')

    def set_pin(self, pin):
        """Set PIN for sales rep."""
        self.ensure_one()
        if not pin or len(pin) != 4 or not pin.isdigit():
            raise ValidationError('PIN must be exactly 4 digits')

        salt = secrets.token_hex(16)
        pin_hash = hashlib.sha256((pin + salt).encode()).hexdigest()

        self.write({
            'pin_hash': pin_hash,
            'pin_salt': salt,
            'pin_attempts': 0,
            'pin_locked_until': False
        })
        return True

    def verify_pin(self, pin):
        """Verify PIN for authentication."""
        self.ensure_one()

        # Check if locked
        if self.pin_locked_until and self.pin_locked_until > fields.Datetime.now():
            remaining = (self.pin_locked_until - datetime.now()).seconds // 60
            raise ValidationError(f'PIN locked. Try again in {remaining} minutes')

        if not self.pin_hash or not self.pin_salt:
            raise ValidationError('PIN not set')

        # Verify PIN
        pin_hash = hashlib.sha256((pin + self.pin_salt).encode()).hexdigest()

        if pin_hash == self.pin_hash:
            self.write({'pin_attempts': 0, 'pin_locked_until': False})
            return True
        else:
            attempts = self.pin_attempts + 1
            updates = {'pin_attempts': attempts}

            # Lock after 5 failed attempts
            if attempts >= 5:
                updates['pin_locked_until'] = datetime.now() + timedelta(minutes=30)

            self.write(updates)
            raise ValidationError(f'Invalid PIN. {5 - attempts} attempts remaining')

    def get_assigned_customer_count(self):
        """Get count of assigned customers."""
        self.ensure_one()
        if self.customer_ids:
            return len(self.customer_ids)

        # If no specific customers, get from territories
        Partner = self.env['res.partner']
        domain = [
            ('is_company', '=', False),
            ('customer_rank', '>', 0),
            ('active', '=', True)
        ]

        if self.territory_ids:
            domain.append(('territory_id', 'in', self.territory_ids.ids))

        return Partner.search_count(domain)


class FieldSalesDevice(models.Model):
    _name = 'field.sales.device'
    _description = 'Field Sales Registered Device'
    _order = 'create_date desc'

    sales_user_id = fields.Many2one('field.sales.user', 'Sales User', required=True, ondelete='cascade')
    device_id = fields.Char('Device ID', required=True, index=True)
    device_name = fields.Char('Device Name')
    device_model = fields.Char('Device Model')
    os_version = fields.Char('OS Version')
    app_version = fields.Char('App Version')
    fcm_token = fields.Char('FCM Token')

    is_active = fields.Boolean('Active', default=True)
    registered_date = fields.Datetime('Registered', default=fields.Datetime.now)
    last_active = fields.Datetime('Last Active')

    # Security
    device_hash = fields.Char('Device Hash', readonly=True)

    _sql_constraints = [
        ('device_user_unique', 'unique(sales_user_id, device_id)',
         'Device already registered for this user'),
    ]

    @api.model
    def register_device(self, sales_user_id, device_info):
        """Register a new device for sales user."""
        sales_user = self.env['field.sales.user'].browse(sales_user_id)

        # Check max devices
        active_devices = self.search_count([
            ('sales_user_id', '=', sales_user_id),
            ('is_active', '=', True)
        ])

        if active_devices >= sales_user.max_devices:
            raise ValidationError(f'Maximum {sales_user.max_devices} devices allowed')

        # Generate device hash for verification
        device_hash = hashlib.sha256(
            f"{device_info['device_id']}:{sales_user_id}:{secrets.token_hex(8)}".encode()
        ).hexdigest()

        device = self.create({
            'sales_user_id': sales_user_id,
            'device_id': device_info['device_id'],
            'device_name': device_info.get('device_name'),
            'device_model': device_info.get('device_model'),
            'os_version': device_info.get('os_version'),
            'app_version': device_info.get('app_version'),
            'device_hash': device_hash,
        })

        return device

    def verify_device(self):
        """Verify device is still valid."""
        self.ensure_one()
        if not self.is_active:
            raise ValidationError('Device has been deactivated')

        self.write({'last_active': fields.Datetime.now()})
        return True
```

**Task 1.2: Field Sales Authentication Controller (3 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/field_sales_auth_controller.py

from odoo import http
from odoo.http import request
from datetime import datetime, timedelta
import jwt
import json

class FieldSalesAuthController(http.Controller):

    @http.route('/api/v1/field-sales/auth/login', type='json', auth='public', methods=['POST'], csrf=False)
    def login(self, **kwargs):
        """
        Field sales rep login with employee ID and PIN.

        Request:
            - employee_code: Employee code/ID
            - pin: 4-digit PIN
            - device_info: Device information for binding
        """
        try:
            employee_code = kwargs.get('employee_code')
            pin = kwargs.get('pin')
            device_info = kwargs.get('device_info', {})

            if not employee_code or not pin:
                return {'success': False, 'error': 'Employee code and PIN required'}

            if not device_info.get('device_id'):
                return {'success': False, 'error': 'Device information required'}

            # Find sales user by employee code
            Employee = request.env['hr.employee'].sudo()
            employee = Employee.search([
                '|',
                ('barcode', '=', employee_code),
                ('identification_id', '=', employee_code)
            ], limit=1)

            if not employee:
                return {'success': False, 'error': 'Employee not found'}

            # Get field sales user
            FieldSalesUser = request.env['field.sales.user'].sudo()
            sales_user = FieldSalesUser.search([
                ('employee_id', '=', employee.id),
                ('is_active', '=', True)
            ], limit=1)

            if not sales_user:
                return {'success': False, 'error': 'Not authorized for field sales'}

            # Verify PIN
            try:
                sales_user.verify_pin(pin)
            except Exception as e:
                return {'success': False, 'error': str(e)}

            # Check device registration
            Device = request.env['field.sales.device'].sudo()
            device = Device.search([
                ('sales_user_id', '=', sales_user.id),
                ('device_id', '=', device_info['device_id']),
                ('is_active', '=', True)
            ], limit=1)

            if not device:
                # Register new device
                try:
                    device = Device.register_device(sales_user.id, device_info)
                except Exception as e:
                    return {'success': False, 'error': str(e)}

            # Generate tokens
            jwt_service = request.env['mobile.jwt.service'].sudo()
            tokens = jwt_service.generate_tokens(
                user_id=sales_user.user_id.id,
                device_id=device_info['device_id'],
                extra_claims={
                    'user_type': 'field_sales',
                    'sales_user_id': sales_user.id,
                    'device_hash': device.device_hash
                }
            )

            # Update last active
            device.write({'last_active': datetime.now()})
            sales_user.write({'last_sync': datetime.now()})

            return {
                'success': True,
                'data': {
                    'access_token': tokens['access_token'],
                    'refresh_token': tokens['refresh_token'],
                    'expires_in': tokens['expires_in'],
                    'user': self._serialize_sales_user(sales_user),
                    'device': {
                        'id': device.id,
                        'device_hash': device.device_hash,
                        'registered_date': device.registered_date.isoformat()
                    }
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/field-sales/auth/verify-device', type='json', auth='jwt_field_sales', methods=['POST'], csrf=False)
    def verify_device(self, **kwargs):
        """Verify device is still authorized."""
        try:
            current_user = request.env.user
            device_hash = kwargs.get('device_hash')

            if not device_hash:
                return {'success': False, 'error': 'Device hash required'}

            Device = request.env['field.sales.device'].sudo()
            device = Device.search([
                ('device_hash', '=', device_hash),
                ('is_active', '=', True)
            ], limit=1)

            if not device:
                return {'success': False, 'error': 'Device not authorized', 'code': 'DEVICE_INVALID'}

            device.verify_device()

            return {'success': True, 'data': {'valid': True}}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/field-sales/auth/change-pin', type='json', auth='jwt_field_sales', methods=['POST'], csrf=False)
    def change_pin(self, **kwargs):
        """Change sales rep PIN."""
        try:
            current_pin = kwargs.get('current_pin')
            new_pin = kwargs.get('new_pin')
            confirm_pin = kwargs.get('confirm_pin')

            if not all([current_pin, new_pin, confirm_pin]):
                return {'success': False, 'error': 'All fields required'}

            if new_pin != confirm_pin:
                return {'success': False, 'error': 'PINs do not match'}

            sales_user = self._get_current_sales_user()

            # Verify current PIN
            try:
                sales_user.verify_pin(current_pin)
            except Exception as e:
                return {'success': False, 'error': str(e)}

            # Set new PIN
            sales_user.set_pin(new_pin)

            return {'success': True, 'message': 'PIN changed successfully'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/field-sales/auth/logout', type='json', auth='jwt_field_sales', methods=['POST'], csrf=False)
    def logout(self, **kwargs):
        """Logout from field sales app."""
        try:
            device_hash = kwargs.get('device_hash')

            if device_hash:
                Device = request.env['field.sales.device'].sudo()
                device = Device.search([('device_hash', '=', device_hash)], limit=1)
                if device:
                    # Just update last active, don't deactivate
                    device.write({'last_active': datetime.now()})

            # Invalidate refresh token
            # Implementation depends on token storage strategy

            return {'success': True, 'message': 'Logged out successfully'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _get_current_sales_user(self):
        """Get current field sales user from JWT claims."""
        # Get from JWT claims or lookup
        current_user = request.env.user
        FieldSalesUser = request.env['field.sales.user'].sudo()
        return FieldSalesUser.search([
            ('user_id', '=', current_user.id),
            ('is_active', '=', True)
        ], limit=1)

    def _serialize_sales_user(self, sales_user):
        """Serialize sales user for response."""
        return {
            'id': sales_user.id,
            'name': sales_user.name,
            'employee_code': sales_user.employee_id.barcode or sales_user.employee_id.identification_id,
            'email': sales_user.email,
            'phone': sales_user.phone,
            'sales_team': sales_user.sales_team_id.name if sales_user.sales_team_id else None,
            'image_url': f"/web/image/field.sales.user/{sales_user.id}/image_256" if sales_user.image_256 else None,
            'targets': {
                'daily_visits': sales_user.daily_visit_target,
                'daily_orders': sales_user.daily_order_target,
                'monthly_sales': sales_user.monthly_sales_target,
            },
            'assigned_customers': sales_user.get_assigned_customer_count(),
            'territories': [{'id': t.id, 'name': t.name} for t in sales_user.territory_ids],
        }
```

**Task 1.3: JWT Field Sales Authentication (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/jwt_field_sales.py

from odoo import models, api
from odoo.http import request
from odoo.exceptions import AccessDenied
import jwt

class JWTFieldSalesAuth(models.AbstractModel):
    _name = 'ir.http'
    _inherit = 'ir.http'

    @classmethod
    def _auth_method_jwt_field_sales(cls):
        """JWT authentication for field sales endpoints."""
        token = request.httprequest.headers.get('Authorization', '').replace('Bearer ', '')

        if not token:
            raise AccessDenied('No authorization token provided')

        try:
            jwt_service = request.env['mobile.jwt.service'].sudo()
            payload = jwt_service.verify_token(token)

            # Verify field sales user type
            if payload.get('user_type') != 'field_sales':
                raise AccessDenied('Not authorized for field sales')

            # Verify device hash
            device_hash = payload.get('device_hash')
            if device_hash:
                Device = request.env['field.sales.device'].sudo()
                device = Device.search([
                    ('device_hash', '=', device_hash),
                    ('is_active', '=', True)
                ], limit=1)

                if not device:
                    raise AccessDenied('Device not authorized')

            # Set user
            user_id = payload.get('user_id')
            request.uid = user_id
            request.env = request.env(user=user_id)

        except jwt.ExpiredSignatureError:
            raise AccessDenied('Token expired')
        except jwt.InvalidTokenError as e:
            raise AccessDenied(f'Invalid token: {str(e)}')
```

#### Dev 2 - Full-Stack Developer (8 hours)

**Task 2.1: Field Sales App Project Setup (4 hours)**

```yaml
# apps/field_sales/pubspec.yaml

name: smart_dairy_field_sales
description: Smart Dairy Field Sales Mobile App
publish_to: 'none'
version: 1.0.0+1

environment:
  sdk: '>=3.2.0 <4.0.0'

dependencies:
  flutter:
    sdk: flutter

  # Shared packages
  smart_dairy_core:
    path: ../../packages/core
  smart_dairy_api:
    path: ../../packages/api
  smart_dairy_ui:
    path: ../../packages/ui

  # State management
  flutter_riverpod: ^2.4.9
  riverpod_annotation: ^2.3.3

  # Database
  drift: ^2.14.1
  sqlite3_flutter_libs: ^0.5.18
  path_provider: ^2.1.1
  path: ^1.8.3

  # Security
  flutter_secure_storage: ^9.0.0
  local_auth: ^2.1.6

  # Location
  geolocator: ^10.1.0
  geocoding: ^2.1.1

  # Connectivity
  connectivity_plus: ^5.0.2

  # Background tasks
  workmanager: ^0.5.2

  # UI
  cupertino_icons: ^1.0.6
  flutter_slidable: ^3.0.1
  pull_to_refresh: ^2.0.0
  shimmer: ^3.0.0

  # Utils
  intl: ^0.18.1
  uuid: ^4.2.2
  collection: ^1.18.0

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.1
  build_runner: ^2.4.7
  drift_dev: ^2.14.1
  riverpod_generator: ^2.3.9
  freezed: ^2.4.6
  json_serializable: ^6.7.1

flutter:
  uses-material-design: true

  assets:
    - assets/images/
    - assets/icons/
```

**Task 2.2: Field Sales Database Schema (4 hours)**

```dart
// apps/field_sales/lib/core/database/field_sales_database.dart

import 'dart:io';
import 'package:drift/drift.dart';
import 'package:drift/native.dart';
import 'package:path_provider/path_provider.dart';
import 'package:path/path.dart' as p;
import 'package:sqlite3/sqlite3.dart';
import 'package:sqlite3_flutter_libs/sqlite3_flutter_libs.dart';

part 'field_sales_database.g.dart';

// ==================== Tables ====================

// Customers table
class Customers extends Table {
  IntColumn get id => integer()();
  IntColumn get serverId => integer().nullable()();
  TextColumn get name => text()();
  TextColumn get phone => text().nullable()();
  TextColumn get email => text().nullable()();
  TextColumn get street => text().nullable()();
  TextColumn get street2 => text().nullable()();
  TextColumn get city => text().nullable()();
  TextColumn get zip => text().nullable()();
  RealColumn get latitude => real().nullable()();
  RealColumn get longitude => real().nullable()();
  TextColumn get customerType => text().withDefault(const Constant('regular'))();
  IntColumn get priceListId => integer().nullable()();
  RealColumn get creditLimit => real().withDefault(const Constant(0))();
  RealColumn get currentCredit => real().withDefault(const Constant(0))();
  RealColumn get availableCredit => real().withDefault(const Constant(0))();
  IntColumn get paymentTermDays => integer().withDefault(const Constant(0))();
  TextColumn get imageUrl => text().nullable()();
  BoolColumn get isActive => boolean().withDefault(const Constant(true))();
  DateTimeColumn get lastVisit => dateTime().nullable()();
  DateTimeColumn get lastOrder => dateTime().nullable()();
  IntColumn get visitFrequency => integer().withDefault(const Constant(7))();
  TextColumn get notes => text().nullable()();

  // Sync fields
  BoolColumn get needsSync => boolean().withDefault(const Constant(false))();
  DateTimeColumn get syncedAt => dateTime().nullable()();
  DateTimeColumn get localUpdatedAt => dateTime()();
  TextColumn get syncStatus => text().withDefault(const Constant('synced'))();

  @override
  Set<Column> get primaryKey => {id};
}

// Customer contacts
class CustomerContacts extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get customerId => integer().references(Customers, #id)();
  TextColumn get name => text()();
  TextColumn get phone => text().nullable()();
  TextColumn get email => text().nullable()();
  TextColumn get role => text().nullable()();
  BoolColumn get isPrimary => boolean().withDefault(const Constant(false))();
}

// Products for offline catalog
class Products extends Table {
  IntColumn get id => integer()();
  TextColumn get name => text()();
  TextColumn get code => text().nullable()();
  TextColumn get barcode => text().nullable()();
  IntColumn get categoryId => integer().nullable()();
  TextColumn get categoryName => text().nullable()();
  RealColumn get listPrice => real()();
  TextColumn get uom => text().withDefault(const Constant('Unit'))();
  RealColumn get stockQty => real().withDefault(const Constant(0))();
  BoolColumn get isAvailable => boolean().withDefault(const Constant(true))();
  TextColumn get imageUrl => text().nullable()();
  DateTimeColumn get syncedAt => dateTime()();

  @override
  Set<Column> get primaryKey => {id};
}

// Price lists
class PriceLists extends Table {
  IntColumn get id => integer()();
  TextColumn get name => text()();
  TextColumn get currency => text().withDefault(const Constant('BDT'))();
  BoolColumn get isDefault => boolean().withDefault(const Constant(false))();
  DateTimeColumn get validFrom => dateTime().nullable()();
  DateTimeColumn get validTo => dateTime().nullable()();
  DateTimeColumn get syncedAt => dateTime()();

  @override
  Set<Column> get primaryKey => {id};
}

// Price list items
class PriceListItems extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get priceListId => integer().references(PriceLists, #id)();
  IntColumn get productId => integer().references(Products, #id)();
  RealColumn get price => real()();
  RealColumn get minQty => real().withDefault(const Constant(1))();
  DateTimeColumn get syncedAt => dateTime()();
}

// Pending orders (offline)
class PendingOrders extends Table {
  TextColumn get localId => text()();
  IntColumn get serverId => integer().nullable()();
  IntColumn get customerId => integer().references(Customers, #id)();
  DateTimeColumn get orderDate => dateTime()();
  RealColumn get subtotal => real()();
  RealColumn get taxAmount => real()();
  RealColumn get discountAmount => real()();
  RealColumn get total => real()();
  TextColumn get notes => text().nullable()();
  TextColumn get status => text().withDefault(const Constant('draft'))();
  RealColumn get latitude => real().nullable()();
  RealColumn get longitude => real().nullable()();

  // Sync
  BoolColumn get needsSync => boolean().withDefault(const Constant(true))();
  DateTimeColumn get syncedAt => dateTime().nullable()();
  TextColumn get syncError => text().nullable()();
  DateTimeColumn get createdAt => dateTime()();

  @override
  Set<Column> get primaryKey => {localId};
}

// Pending order lines
class PendingOrderLines extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get orderId => text().references(PendingOrders, #localId)();
  IntColumn get productId => integer().references(Products, #id)();
  TextColumn get productName => text()();
  RealColumn get quantity => real()();
  TextColumn get uom => text()();
  RealColumn get unitPrice => real()();
  RealColumn get discount => real().withDefault(const Constant(0))();
  RealColumn get subtotal => real()();
}

// Customer visits
class CustomerVisits extends Table {
  TextColumn get localId => text()();
  IntColumn get serverId => integer().nullable()();
  IntColumn get customerId => integer().references(Customers, #id)();
  DateTimeColumn get checkInTime => dateTime()();
  DateTimeColumn get checkOutTime => dateTime().nullable()();
  RealColumn get checkInLatitude => real()();
  RealColumn get checkInLongitude => real()();
  RealColumn get checkOutLatitude => real().nullable()();
  RealColumn get checkOutLongitude => real().nullable()();
  TextColumn get visitType => text().withDefault(const Constant('sales'))();
  TextColumn get outcome => text().nullable()();
  TextColumn get notes => text().nullable()();
  TextColumn get photoPath => text().nullable()();

  // Sync
  BoolColumn get needsSync => boolean().withDefault(const Constant(true))();
  DateTimeColumn get syncedAt => dateTime().nullable()();

  @override
  Set<Column> get primaryKey => {localId};
}

// Sync queue
class SyncQueue extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get entityType => text()(); // 'customer', 'order', 'visit'
  TextColumn get entityId => text()();
  TextColumn get action => text()(); // 'create', 'update', 'delete'
  TextColumn get payload => text()();
  IntColumn get priority => integer().withDefault(const Constant(5))();
  IntColumn get retryCount => integer().withDefault(const Constant(0))();
  DateTimeColumn get createdAt => dateTime()();
  DateTimeColumn get lastAttempt => dateTime().nullable()();
  TextColumn get lastError => text().nullable()();
}

// ==================== Database ====================

@DriftDatabase(tables: [
  Customers,
  CustomerContacts,
  Products,
  PriceLists,
  PriceListItems,
  PendingOrders,
  PendingOrderLines,
  CustomerVisits,
  SyncQueue,
])
class FieldSalesDatabase extends _$FieldSalesDatabase {
  FieldSalesDatabase() : super(_openConnection());

  @override
  int get schemaVersion => 1;

  @override
  MigrationStrategy get migration {
    return MigrationStrategy(
      onCreate: (Migrator m) async {
        await m.createAll();
      },
      onUpgrade: (Migrator m, int from, int to) async {
        // Handle migrations
      },
    );
  }
}

LazyDatabase _openConnection() {
  return LazyDatabase(() async {
    final dbFolder = await getApplicationDocumentsDirectory();
    final file = File(p.join(dbFolder.path, 'field_sales.db'));

    // Enable SQLite encryption if needed
    if (Platform.isAndroid) {
      await applyWorkaroundToOpenSqlite3OnOldAndroidVersions();
    }

    return NativeDatabase.createInBackground(file);
  });
}
```

#### Dev 3 - Mobile Lead (8 hours)

**Task 3.1: Field Sales Auth Repository (3 hours)**

```dart
// apps/field_sales/lib/features/auth/data/repositories/auth_repository.dart

import 'package:dartz/dartz.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:smart_dairy_api/api_client.dart';
import 'package:smart_dairy_field_sales/core/error/failures.dart';
import 'package:smart_dairy_field_sales/features/auth/data/models/auth_models.dart';

class FieldSalesAuthRepository {
  final ApiClient _apiClient;
  final FlutterSecureStorage _secureStorage;

  FieldSalesAuthRepository(this._apiClient, this._secureStorage);

  // Storage keys
  static const _accessTokenKey = 'fs_access_token';
  static const _refreshTokenKey = 'fs_refresh_token';
  static const _deviceHashKey = 'fs_device_hash';
  static const _userDataKey = 'fs_user_data';

  Future<Either<Failure, LoginResponse>> login({
    required String employeeCode,
    required String pin,
    required DeviceInfo deviceInfo,
  }) async {
    try {
      final response = await _apiClient.post(
        '/api/v1/field-sales/auth/login',
        data: {
          'employee_code': employeeCode,
          'pin': pin,
          'device_info': deviceInfo.toJson(),
        },
      );

      if (response['success'] == true) {
        final loginResponse = LoginResponse.fromJson(response['data']);

        // Store tokens securely
        await _secureStorage.write(
          key: _accessTokenKey,
          value: loginResponse.accessToken,
        );
        await _secureStorage.write(
          key: _refreshTokenKey,
          value: loginResponse.refreshToken,
        );
        await _secureStorage.write(
          key: _deviceHashKey,
          value: loginResponse.device.deviceHash,
        );

        // Update API client with token
        _apiClient.setAuthToken(loginResponse.accessToken);

        return Right(loginResponse);
      }

      return Left(AuthFailure(response['error'] ?? 'Login failed'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, bool>> verifyDevice() async {
    try {
      final deviceHash = await _secureStorage.read(key: _deviceHashKey);

      if (deviceHash == null) {
        return const Left(AuthFailure('Device not registered'));
      }

      final response = await _apiClient.post(
        '/api/v1/field-sales/auth/verify-device',
        data: {'device_hash': deviceHash},
      );

      if (response['success'] == true) {
        return const Right(true);
      }

      // Device invalid, clear stored data
      if (response['code'] == 'DEVICE_INVALID') {
        await clearAuthData();
      }

      return Left(AuthFailure(response['error'] ?? 'Device verification failed'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, void>> changePin({
    required String currentPin,
    required String newPin,
    required String confirmPin,
  }) async {
    try {
      final response = await _apiClient.post(
        '/api/v1/field-sales/auth/change-pin',
        data: {
          'current_pin': currentPin,
          'new_pin': newPin,
          'confirm_pin': confirmPin,
        },
      );

      if (response['success'] == true) {
        return const Right(null);
      }

      return Left(AuthFailure(response['error'] ?? 'Failed to change PIN'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, void>> logout() async {
    try {
      final deviceHash = await _secureStorage.read(key: _deviceHashKey);

      await _apiClient.post(
        '/api/v1/field-sales/auth/logout',
        data: {'device_hash': deviceHash},
      );

      await clearAuthData();
      return const Right(null);
    } catch (e) {
      // Still clear local data even if API call fails
      await clearAuthData();
      return const Right(null);
    }
  }

  Future<bool> isLoggedIn() async {
    final token = await _secureStorage.read(key: _accessTokenKey);
    return token != null && token.isNotEmpty;
  }

  Future<String?> getAccessToken() async {
    return await _secureStorage.read(key: _accessTokenKey);
  }

  Future<String?> getDeviceHash() async {
    return await _secureStorage.read(key: _deviceHashKey);
  }

  Future<void> clearAuthData() async {
    await _secureStorage.delete(key: _accessTokenKey);
    await _secureStorage.delete(key: _refreshTokenKey);
    await _secureStorage.delete(key: _deviceHashKey);
    await _secureStorage.delete(key: _userDataKey);
    _apiClient.clearAuthToken();
  }

  Future<void> restoreSession() async {
    final token = await _secureStorage.read(key: _accessTokenKey);
    if (token != null) {
      _apiClient.setAuthToken(token);
    }
  }
}
```

**Task 3.2: Auth Models (2 hours)**

```dart
// apps/field_sales/lib/features/auth/data/models/auth_models.dart

import 'package:freezed_annotation/freezed_annotation.dart';

part 'auth_models.freezed.dart';
part 'auth_models.g.dart';

@freezed
class LoginResponse with _$LoginResponse {
  const factory LoginResponse({
    required String accessToken,
    required String refreshToken,
    required int expiresIn,
    required SalesUser user,
    required DeviceRegistration device,
  }) = _LoginResponse;

  factory LoginResponse.fromJson(Map<String, dynamic> json) =>
      _$LoginResponseFromJson(json);
}

@freezed
class SalesUser with _$SalesUser {
  const factory SalesUser({
    required int id,
    required String name,
    required String employeeCode,
    String? email,
    String? phone,
    String? salesTeam,
    String? imageUrl,
    required SalesTargets targets,
    required int assignedCustomers,
    required List<Territory> territories,
  }) = _SalesUser;

  factory SalesUser.fromJson(Map<String, dynamic> json) =>
      _$SalesUserFromJson(json);
}

@freezed
class SalesTargets with _$SalesTargets {
  const factory SalesTargets({
    required int dailyVisits,
    required double dailyOrders,
    required double monthlySales,
  }) = _SalesTargets;

  factory SalesTargets.fromJson(Map<String, dynamic> json) =>
      _$SalesTargetsFromJson(json);
}

@freezed
class Territory with _$Territory {
  const factory Territory({
    required int id,
    required String name,
  }) = _Territory;

  factory Territory.fromJson(Map<String, dynamic> json) =>
      _$TerritoryFromJson(json);
}

@freezed
class DeviceRegistration with _$DeviceRegistration {
  const factory DeviceRegistration({
    required int id,
    required String deviceHash,
    required DateTime registeredDate,
  }) = _DeviceRegistration;

  factory DeviceRegistration.fromJson(Map<String, dynamic> json) =>
      _$DeviceRegistrationFromJson(json);
}

@freezed
class DeviceInfo with _$DeviceInfo {
  const factory DeviceInfo({
    required String deviceId,
    String? deviceName,
    String? deviceModel,
    String? osVersion,
    String? appVersion,
  }) = _DeviceInfo;

  factory DeviceInfo.fromJson(Map<String, dynamic> json) =>
      _$DeviceInfoFromJson(json);
}
```

**Task 3.3: Login Screen (3 hours)**

```dart
// apps/field_sales/lib/features/auth/presentation/screens/login_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:device_info_plus/device_info_plus.dart';
import 'package:package_info_plus/package_info_plus.dart';
import 'dart:io';
import 'package:smart_dairy_field_sales/core/theme/app_colors.dart';
import 'package:smart_dairy_field_sales/features/auth/data/models/auth_models.dart';
import 'package:smart_dairy_field_sales/features/auth/presentation/providers/auth_provider.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _employeeCodeController = TextEditingController();
  final _pinController = TextEditingController();
  final _pinFocusNode = FocusNode();

  bool _obscurePin = true;
  bool _isLoading = false;
  String? _error;

  @override
  void dispose() {
    _employeeCodeController.dispose();
    _pinController.dispose();
    _pinFocusNode.dispose();
    super.dispose();
  }

  Future<DeviceInfo> _getDeviceInfo() async {
    final deviceInfo = DeviceInfoPlugin();
    final packageInfo = await PackageInfo.fromPlatform();

    if (Platform.isAndroid) {
      final androidInfo = await deviceInfo.androidInfo;
      return DeviceInfo(
        deviceId: androidInfo.id,
        deviceName: androidInfo.device,
        deviceModel: '${androidInfo.brand} ${androidInfo.model}',
        osVersion: 'Android ${androidInfo.version.release}',
        appVersion: packageInfo.version,
      );
    } else {
      final iosInfo = await deviceInfo.iosInfo;
      return DeviceInfo(
        deviceId: iosInfo.identifierForVendor ?? '',
        deviceName: iosInfo.name,
        deviceModel: iosInfo.model,
        osVersion: 'iOS ${iosInfo.systemVersion}',
        appVersion: packageInfo.version,
      );
    }
  }

  Future<void> _handleLogin() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _isLoading = true;
      _error = null;
    });

    try {
      final deviceInfo = await _getDeviceInfo();

      final result = await ref.read(authProvider.notifier).login(
        employeeCode: _employeeCodeController.text.trim(),
        pin: _pinController.text,
        deviceInfo: deviceInfo,
      );

      if (result && mounted) {
        Navigator.pushReplacementNamed(context, '/home');
      }
    } catch (e) {
      setState(() {
        _error = e.toString();
      });
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authProvider);

    return Scaffold(
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const SizedBox(height: 60),

                // Logo
                Center(
                  child: Image.asset(
                    'assets/images/logo.png',
                    height: 100,
                    errorBuilder: (_, __, ___) => const Icon(
                      Icons.local_shipping,
                      size: 80,
                      color: AppColors.primary,
                    ),
                  ),
                ),

                const SizedBox(height: 24),

                // Title
                const Text(
                  'Field Sales',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 28,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  'Login to start your day',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 16,
                    color: Colors.grey[600],
                  ),
                ),

                const SizedBox(height: 48),

                // Employee Code
                TextFormField(
                  controller: _employeeCodeController,
                  decoration: InputDecoration(
                    labelText: 'Employee Code',
                    hintText: 'Enter your employee ID',
                    prefixIcon: const Icon(Icons.badge_outlined),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  textInputAction: TextInputAction.next,
                  onFieldSubmitted: (_) => _pinFocusNode.requestFocus(),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Please enter your employee code';
                    }
                    return null;
                  },
                ),

                const SizedBox(height: 20),

                // PIN
                TextFormField(
                  controller: _pinController,
                  focusNode: _pinFocusNode,
                  obscureText: _obscurePin,
                  keyboardType: TextInputType.number,
                  maxLength: 4,
                  inputFormatters: [
                    FilteringTextInputFormatter.digitsOnly,
                    LengthLimitingTextInputFormatter(4),
                  ],
                  decoration: InputDecoration(
                    labelText: 'PIN',
                    hintText: 'Enter 4-digit PIN',
                    prefixIcon: const Icon(Icons.lock_outline),
                    counterText: '',
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                    suffixIcon: IconButton(
                      icon: Icon(
                        _obscurePin ? Icons.visibility_off : Icons.visibility,
                      ),
                      onPressed: () {
                        setState(() {
                          _obscurePin = !_obscurePin;
                        });
                      },
                    ),
                  ),
                  textInputAction: TextInputAction.done,
                  onFieldSubmitted: (_) => _handleLogin(),
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Please enter your PIN';
                    }
                    if (value.length != 4) {
                      return 'PIN must be 4 digits';
                    }
                    return null;
                  },
                ),

                const SizedBox(height: 12),

                // Error message
                if (_error != null || authState.error != null)
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.red[50],
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(color: Colors.red[200]!),
                    ),
                    child: Row(
                      children: [
                        const Icon(Icons.error_outline, color: Colors.red),
                        const SizedBox(width: 8),
                        Expanded(
                          child: Text(
                            _error ?? authState.error ?? '',
                            style: const TextStyle(color: Colors.red),
                          ),
                        ),
                      ],
                    ),
                  ),

                const SizedBox(height: 24),

                // Login button
                SizedBox(
                  height: 52,
                  child: ElevatedButton(
                    onPressed: (_isLoading || authState.isLoading)
                        ? null
                        : _handleLogin,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    child: (_isLoading || authState.isLoading)
                        ? const SizedBox(
                            height: 24,
                            width: 24,
                            child: CircularProgressIndicator(
                              strokeWidth: 2,
                              valueColor:
                                  AlwaysStoppedAnimation<Color>(Colors.white),
                            ),
                          )
                        : const Text(
                            'Login',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                  ),
                ),

                const SizedBox(height: 24),

                // Help text
                Center(
                  child: TextButton(
                    onPressed: () {
                      _showHelpDialog(context);
                    },
                    child: const Text('Need help logging in?'),
                  ),
                ),

                const SizedBox(height: 48),

                // Version
                Center(
                  child: FutureBuilder<PackageInfo>(
                    future: PackageInfo.fromPlatform(),
                    builder: (context, snapshot) {
                      final version = snapshot.data?.version ?? '';
                      return Text(
                        'Version $version',
                        style: TextStyle(
                          color: Colors.grey[500],
                          fontSize: 12,
                        ),
                      );
                    },
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  void _showHelpDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Login Help'),
        content: const Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Employee Code:'),
            Text(
              'Your unique employee ID provided by HR.',
              style: TextStyle(color: Colors.grey),
            ),
            SizedBox(height: 16),
            Text('PIN:'),
            Text(
              'Your 4-digit PIN. If you forgot your PIN, please contact your supervisor.',
              style: TextStyle(color: Colors.grey),
            ),
            SizedBox(height: 16),
            Text('Device Registration:'),
            Text(
              'Each sales rep can use up to 2 devices. Contact admin to remove old devices.',
              style: TextStyle(color: Colors.grey),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Got it'),
          ),
        ],
      ),
    );
  }
}
```

#### End-of-Day 301 Deliverables

- [ ] Field sales user model with PIN authentication
- [ ] Device binding model and registration
- [ ] Field sales authentication API
- [ ] JWT authentication for field sales
- [ ] Field sales app project setup
- [ ] Database schema for offline storage
- [ ] Auth repository implementation
- [ ] Auth models with Freezed
- [ ] Login screen UI

---

### Day 302 (Sprint Day 2): Device Management & PIN Security

#### Objectives
- Complete device management functionality
- Implement biometric authentication option
- Build PIN change and reset flows

#### Dev 1 - Backend Lead (8 hours)

**Task 1.1: Device Management API (4 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/device_management_controller.py

from odoo import http
from odoo.http import request
from datetime import datetime

class DeviceManagementController(http.Controller):

    @http.route('/api/v1/field-sales/devices', type='json', auth='jwt_field_sales', methods=['GET'], csrf=False)
    def get_devices(self, **kwargs):
        """Get all registered devices for current sales user."""
        try:
            sales_user = self._get_current_sales_user()

            devices = []
            for device in sales_user.device_ids:
                devices.append({
                    'id': device.id,
                    'device_id': device.device_id,
                    'device_name': device.device_name,
                    'device_model': device.device_model,
                    'os_version': device.os_version,
                    'app_version': device.app_version,
                    'is_active': device.is_active,
                    'registered_date': device.registered_date.isoformat() if device.registered_date else None,
                    'last_active': device.last_active.isoformat() if device.last_active else None,
                    'is_current': device.device_hash == kwargs.get('current_device_hash')
                })

            return {
                'success': True,
                'data': {
                    'devices': devices,
                    'max_devices': sales_user.max_devices,
                    'active_count': len([d for d in devices if d['is_active']])
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/field-sales/devices/<int:device_id>/deactivate', type='json', auth='jwt_field_sales', methods=['POST'], csrf=False)
    def deactivate_device(self, device_id, **kwargs):
        """Deactivate a registered device."""
        try:
            sales_user = self._get_current_sales_user()

            device = request.env['field.sales.device'].sudo().search([
                ('id', '=', device_id),
                ('sales_user_id', '=', sales_user.id)
            ], limit=1)

            if not device:
                return {'success': False, 'error': 'Device not found'}

            # Don't allow deactivating current device
            current_device_hash = kwargs.get('current_device_hash')
            if device.device_hash == current_device_hash:
                return {'success': False, 'error': 'Cannot deactivate current device'}

            device.write({'is_active': False})

            return {'success': True, 'message': 'Device deactivated'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/field-sales/devices/register-fcm', type='json', auth='jwt_field_sales', methods=['POST'], csrf=False)
    def register_fcm_token(self, **kwargs):
        """Register FCM token for push notifications."""
        try:
            device_hash = kwargs.get('device_hash')
            fcm_token = kwargs.get('fcm_token')

            if not device_hash or not fcm_token:
                return {'success': False, 'error': 'Device hash and FCM token required'}

            device = request.env['field.sales.device'].sudo().search([
                ('device_hash', '=', device_hash),
                ('is_active', '=', True)
            ], limit=1)

            if not device:
                return {'success': False, 'error': 'Device not found'}

            device.write({'fcm_token': fcm_token})

            return {'success': True, 'message': 'FCM token registered'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _get_current_sales_user(self):
        """Get current field sales user."""
        current_user = request.env.user
        FieldSalesUser = request.env['field.sales.user'].sudo()
        return FieldSalesUser.search([
            ('user_id', '=', current_user.id),
            ('is_active', '=', True)
        ], limit=1)
```

**Task 1.2: PIN Reset by Admin (2 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/field_sales_user.py (continued)

    def admin_reset_pin(self, new_pin):
        """Admin function to reset PIN."""
        self.ensure_one()
        if not self.env.user.has_group('smart_dairy_mobile_api.group_field_sales_admin'):
            raise ValidationError('Only administrators can reset PINs')

        self.set_pin(new_pin)

        # Send notification to user
        self._send_pin_reset_notification()

        return True

    def _send_pin_reset_notification(self):
        """Send notification about PIN reset."""
        notification_service = self.env['mobile.notification.service'].sudo()

        for device in self.device_ids.filtered(lambda d: d.is_active and d.fcm_token):
            notification_service.send_to_token(
                token=device.fcm_token,
                title='PIN Reset',
                body='Your PIN has been reset by administrator. Please login with new PIN.',
                data={'type': 'pin_reset', 'action': 'logout'}
            )

    def unlock_pin(self):
        """Unlock PIN after lockout."""
        self.ensure_one()
        self.write({
            'pin_attempts': 0,
            'pin_locked_until': False
        })
        return True
```

**Task 1.3: Admin Device Management View (2 hours)**

```xml
<!-- odoo/addons/smart_dairy_mobile_api/views/field_sales_views.xml -->

<odoo>
    <!-- Field Sales User Form View -->
    <record id="view_field_sales_user_form" model="ir.ui.view">
        <field name="name">field.sales.user.form</field>
        <field name="model">field.sales.user</field>
        <field name="arch" type="xml">
            <form string="Field Sales User">
                <header>
                    <button name="unlock_pin" string="Unlock PIN" type="object"
                            class="btn-secondary"
                            attrs="{'invisible': [('pin_locked_until', '=', False)]}"/>
                </header>
                <sheet>
                    <div class="oe_button_box" name="button_box">
                        <button class="oe_stat_button" type="object" name="action_view_devices"
                                icon="fa-mobile">
                            <field name="device_count" widget="statinfo" string="Devices"/>
                        </button>
                    </div>
                    <field name="image_1920" widget="image" class="oe_avatar"/>
                    <div class="oe_title">
                        <h1><field name="name" placeholder="Name"/></h1>
                    </div>
                    <group>
                        <group>
                            <field name="employee_id"/>
                            <field name="sales_team_id"/>
                            <field name="is_active"/>
                        </group>
                        <group>
                            <field name="email"/>
                            <field name="phone"/>
                            <field name="max_devices"/>
                        </group>
                    </group>
                    <notebook>
                        <page string="Targets">
                            <group>
                                <field name="daily_visit_target"/>
                                <field name="daily_order_target"/>
                                <field name="monthly_sales_target"/>
                            </group>
                        </page>
                        <page string="Territories">
                            <field name="territory_ids"/>
                            <field name="route_ids"/>
                        </page>
                        <page string="Assigned Customers">
                            <field name="customer_ids"/>
                        </page>
                        <page string="Devices">
                            <field name="device_ids">
                                <tree>
                                    <field name="device_name"/>
                                    <field name="device_model"/>
                                    <field name="app_version"/>
                                    <field name="is_active"/>
                                    <field name="registered_date"/>
                                    <field name="last_active"/>
                                </tree>
                            </field>
                        </page>
                        <page string="Security">
                            <group>
                                <field name="pin_attempts" readonly="1"/>
                                <field name="pin_locked_until" readonly="1"/>
                                <field name="last_sync" readonly="1"/>
                            </group>
                            <group>
                                <button name="admin_reset_pin" string="Reset PIN" type="object"
                                        class="btn-warning"
                                        groups="smart_dairy_mobile_api.group_field_sales_admin"/>
                            </group>
                        </page>
                    </notebook>
                </sheet>
            </form>
        </field>
    </record>

    <!-- Field Sales User Tree View -->
    <record id="view_field_sales_user_tree" model="ir.ui.view">
        <field name="name">field.sales.user.tree</field>
        <field name="model">field.sales.user</field>
        <field name="arch" type="xml">
            <tree string="Field Sales Users">
                <field name="name"/>
                <field name="employee_id"/>
                <field name="sales_team_id"/>
                <field name="daily_visit_target"/>
                <field name="daily_order_target"/>
                <field name="last_sync"/>
                <field name="is_active"/>
            </tree>
        </field>
    </record>

    <!-- Action -->
    <record id="action_field_sales_users" model="ir.actions.act_window">
        <field name="name">Field Sales Users</field>
        <field name="res_model">field.sales.user</field>
        <field name="view_mode">tree,form</field>
    </record>

    <!-- Menu -->
    <menuitem id="menu_field_sales_root" name="Field Sales" sequence="50"/>
    <menuitem id="menu_field_sales_users" name="Sales Representatives"
              parent="menu_field_sales_root"
              action="action_field_sales_users" sequence="10"/>
</odoo>
```

#### Dev 2 - Full-Stack Developer (8 hours)

**Task 2.1: Biometric Authentication Service (4 hours)**

```dart
// apps/field_sales/lib/core/services/biometric_service.dart

import 'package:flutter/services.dart';
import 'package:local_auth/local_auth.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class BiometricService {
  final LocalAuthentication _localAuth = LocalAuthentication();
  final FlutterSecureStorage _secureStorage;

  BiometricService(this._secureStorage);

  static const _biometricEnabledKey = 'fs_biometric_enabled';
  static const _biometricCredentialKey = 'fs_biometric_credential';

  /// Check if biometric authentication is available
  Future<bool> isAvailable() async {
    try {
      final canCheckBiometrics = await _localAuth.canCheckBiometrics;
      final isDeviceSupported = await _localAuth.isDeviceSupported();
      return canCheckBiometrics && isDeviceSupported;
    } on PlatformException {
      return false;
    }
  }

  /// Get available biometric types
  Future<List<BiometricType>> getAvailableBiometrics() async {
    try {
      return await _localAuth.getAvailableBiometrics();
    } on PlatformException {
      return [];
    }
  }

  /// Check if biometric login is enabled
  Future<bool> isBiometricEnabled() async {
    final enabled = await _secureStorage.read(key: _biometricEnabledKey);
    return enabled == 'true';
  }

  /// Enable biometric authentication
  Future<bool> enableBiometric(String employeeCode, String pin) async {
    // First authenticate with biometric
    final authenticated = await authenticate();
    if (!authenticated) return false;

    // Store credentials securely
    await _secureStorage.write(
      key: _biometricCredentialKey,
      value: '$employeeCode|$pin',
    );
    await _secureStorage.write(key: _biometricEnabledKey, value: 'true');

    return true;
  }

  /// Disable biometric authentication
  Future<void> disableBiometric() async {
    await _secureStorage.delete(key: _biometricEnabledKey);
    await _secureStorage.delete(key: _biometricCredentialKey);
  }

  /// Authenticate using biometrics
  Future<bool> authenticate({String reason = 'Authenticate to login'}) async {
    try {
      return await _localAuth.authenticate(
        localizedReason: reason,
        options: const AuthenticationOptions(
          stickyAuth: true,
          biometricOnly: true,
        ),
      );
    } on PlatformException {
      return false;
    }
  }

  /// Get stored credentials after biometric auth
  Future<(String, String)?> getStoredCredentials() async {
    final authenticated = await authenticate();
    if (!authenticated) return null;

    final credentials = await _secureStorage.read(key: _biometricCredentialKey);
    if (credentials == null) return null;

    final parts = credentials.split('|');
    if (parts.length != 2) return null;

    return (parts[0], parts[1]);
  }
}
```

**Task 2.2: Auth Provider (4 hours)**

```dart
// apps/field_sales/lib/features/auth/presentation/providers/auth_provider.dart

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_field_sales/features/auth/data/models/auth_models.dart';
import 'package:smart_dairy_field_sales/features/auth/data/repositories/auth_repository.dart';
import 'package:smart_dairy_field_sales/core/services/biometric_service.dart';

// Auth state
class AuthState {
  final SalesUser? user;
  final bool isLoading;
  final bool isAuthenticated;
  final String? error;
  final bool biometricAvailable;
  final bool biometricEnabled;

  const AuthState({
    this.user,
    this.isLoading = false,
    this.isAuthenticated = false,
    this.error,
    this.biometricAvailable = false,
    this.biometricEnabled = false,
  });

  AuthState copyWith({
    SalesUser? user,
    bool? isLoading,
    bool? isAuthenticated,
    String? error,
    bool? biometricAvailable,
    bool? biometricEnabled,
  }) {
    return AuthState(
      user: user ?? this.user,
      isLoading: isLoading ?? this.isLoading,
      isAuthenticated: isAuthenticated ?? this.isAuthenticated,
      error: error,
      biometricAvailable: biometricAvailable ?? this.biometricAvailable,
      biometricEnabled: biometricEnabled ?? this.biometricEnabled,
    );
  }
}

// Auth notifier
class AuthNotifier extends StateNotifier<AuthState> {
  final FieldSalesAuthRepository _authRepository;
  final BiometricService _biometricService;

  AuthNotifier(this._authRepository, this._biometricService)
      : super(const AuthState()) {
    _init();
  }

  Future<void> _init() async {
    // Check biometric availability
    final biometricAvailable = await _biometricService.isAvailable();
    final biometricEnabled = await _biometricService.isBiometricEnabled();

    state = state.copyWith(
      biometricAvailable: biometricAvailable,
      biometricEnabled: biometricEnabled,
    );

    // Check existing session
    await checkSession();
  }

  Future<void> checkSession() async {
    final isLoggedIn = await _authRepository.isLoggedIn();
    if (isLoggedIn) {
      await _authRepository.restoreSession();

      // Verify device is still valid
      final result = await _authRepository.verifyDevice();
      result.fold(
        (failure) {
          // Device invalid, logout
          logout();
        },
        (_) {
          state = state.copyWith(isAuthenticated: true);
        },
      );
    }
  }

  Future<bool> login({
    required String employeeCode,
    required String pin,
    required DeviceInfo deviceInfo,
  }) async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _authRepository.login(
      employeeCode: employeeCode,
      pin: pin,
      deviceInfo: deviceInfo,
    );

    return result.fold(
      (failure) {
        state = state.copyWith(
          isLoading: false,
          error: failure.message,
        );
        return false;
      },
      (response) {
        state = state.copyWith(
          isLoading: false,
          isAuthenticated: true,
          user: response.user,
        );
        return true;
      },
    );
  }

  Future<bool> loginWithBiometric(DeviceInfo deviceInfo) async {
    final credentials = await _biometricService.getStoredCredentials();
    if (credentials == null) {
      state = state.copyWith(error: 'Biometric authentication failed');
      return false;
    }

    return login(
      employeeCode: credentials.$1,
      pin: credentials.$2,
      deviceInfo: deviceInfo,
    );
  }

  Future<bool> enableBiometric(String employeeCode, String pin) async {
    final success = await _biometricService.enableBiometric(employeeCode, pin);
    if (success) {
      state = state.copyWith(biometricEnabled: true);
    }
    return success;
  }

  Future<void> disableBiometric() async {
    await _biometricService.disableBiometric();
    state = state.copyWith(biometricEnabled: false);
  }

  Future<bool> changePin({
    required String currentPin,
    required String newPin,
    required String confirmPin,
  }) async {
    state = state.copyWith(isLoading: true, error: null);

    final result = await _authRepository.changePin(
      currentPin: currentPin,
      newPin: newPin,
      confirmPin: confirmPin,
    );

    return result.fold(
      (failure) {
        state = state.copyWith(
          isLoading: false,
          error: failure.message,
        );
        return false;
      },
      (_) {
        state = state.copyWith(isLoading: false);

        // Update biometric credentials if enabled
        if (state.biometricEnabled && state.user != null) {
          enableBiometric(state.user!.employeeCode, newPin);
        }

        return true;
      },
    );
  }

  Future<void> logout() async {
    state = state.copyWith(isLoading: true);

    await _authRepository.logout();

    state = const AuthState();
  }
}

// Providers
final authRepositoryProvider = Provider<FieldSalesAuthRepository>((ref) {
  throw UnimplementedError('Override in main.dart');
});

final biometricServiceProvider = Provider<BiometricService>((ref) {
  throw UnimplementedError('Override in main.dart');
});

final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  return AuthNotifier(
    ref.watch(authRepositoryProvider),
    ref.watch(biometricServiceProvider),
  );
});
```

#### Dev 3 - Mobile Lead (8 hours)

**Task 3.1: PIN Change Screen (4 hours)**

```dart
// apps/field_sales/lib/features/auth/presentation/screens/change_pin_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_field_sales/core/theme/app_colors.dart';
import 'package:smart_dairy_field_sales/features/auth/presentation/providers/auth_provider.dart';

class ChangePinScreen extends ConsumerStatefulWidget {
  const ChangePinScreen({super.key});

  @override
  ConsumerState<ChangePinScreen> createState() => _ChangePinScreenState();
}

class _ChangePinScreenState extends ConsumerState<ChangePinScreen> {
  final _formKey = GlobalKey<FormState>();
  final _currentPinController = TextEditingController();
  final _newPinController = TextEditingController();
  final _confirmPinController = TextEditingController();

  bool _obscureCurrent = true;
  bool _obscureNew = true;
  bool _obscureConfirm = true;

  @override
  void dispose() {
    _currentPinController.dispose();
    _newPinController.dispose();
    _confirmPinController.dispose();
    super.dispose();
  }

  Future<void> _handleChangePin() async {
    if (!_formKey.currentState!.validate()) return;

    final success = await ref.read(authProvider.notifier).changePin(
      currentPin: _currentPinController.text,
      newPin: _newPinController.text,
      confirmPin: _confirmPinController.text,
    );

    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('PIN changed successfully'),
          backgroundColor: Colors.green,
        ),
      );
      Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Change PIN'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Info card
              Card(
                color: Colors.blue[50],
                child: const Padding(
                  padding: EdgeInsets.all(16),
                  child: Row(
                    children: [
                      Icon(Icons.info_outline, color: Colors.blue),
                      SizedBox(width: 12),
                      Expanded(
                        child: Text(
                          'Your PIN must be exactly 4 digits. Choose a PIN that is easy for you to remember but hard for others to guess.',
                          style: TextStyle(fontSize: 13),
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 24),

              // Current PIN
              TextFormField(
                controller: _currentPinController,
                obscureText: _obscureCurrent,
                keyboardType: TextInputType.number,
                maxLength: 4,
                inputFormatters: [
                  FilteringTextInputFormatter.digitsOnly,
                ],
                decoration: InputDecoration(
                  labelText: 'Current PIN',
                  prefixIcon: const Icon(Icons.lock_outline),
                  counterText: '',
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  suffixIcon: IconButton(
                    icon: Icon(
                      _obscureCurrent ? Icons.visibility_off : Icons.visibility,
                    ),
                    onPressed: () {
                      setState(() => _obscureCurrent = !_obscureCurrent);
                    },
                  ),
                ),
                validator: (value) {
                  if (value == null || value.length != 4) {
                    return 'Enter your current 4-digit PIN';
                  }
                  return null;
                },
              ),

              const SizedBox(height: 20),

              // New PIN
              TextFormField(
                controller: _newPinController,
                obscureText: _obscureNew,
                keyboardType: TextInputType.number,
                maxLength: 4,
                inputFormatters: [
                  FilteringTextInputFormatter.digitsOnly,
                ],
                decoration: InputDecoration(
                  labelText: 'New PIN',
                  prefixIcon: const Icon(Icons.lock),
                  counterText: '',
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  suffixIcon: IconButton(
                    icon: Icon(
                      _obscureNew ? Icons.visibility_off : Icons.visibility,
                    ),
                    onPressed: () {
                      setState(() => _obscureNew = !_obscureNew);
                    },
                  ),
                ),
                validator: (value) {
                  if (value == null || value.length != 4) {
                    return 'Enter a new 4-digit PIN';
                  }
                  if (value == _currentPinController.text) {
                    return 'New PIN must be different from current';
                  }
                  // Check for simple patterns
                  if (_isWeakPin(value)) {
                    return 'PIN is too simple. Avoid sequences like 1234';
                  }
                  return null;
                },
              ),

              const SizedBox(height: 20),

              // Confirm PIN
              TextFormField(
                controller: _confirmPinController,
                obscureText: _obscureConfirm,
                keyboardType: TextInputType.number,
                maxLength: 4,
                inputFormatters: [
                  FilteringTextInputFormatter.digitsOnly,
                ],
                decoration: InputDecoration(
                  labelText: 'Confirm New PIN',
                  prefixIcon: const Icon(Icons.lock_clock),
                  counterText: '',
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  suffixIcon: IconButton(
                    icon: Icon(
                      _obscureConfirm ? Icons.visibility_off : Icons.visibility,
                    ),
                    onPressed: () {
                      setState(() => _obscureConfirm = !_obscureConfirm);
                    },
                  ),
                ),
                validator: (value) {
                  if (value != _newPinController.text) {
                    return 'PINs do not match';
                  }
                  return null;
                },
              ),

              // Error message
              if (authState.error != null) ...[
                const SizedBox(height: 16),
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.red[50],
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.error_outline, color: Colors.red),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          authState.error!,
                          style: const TextStyle(color: Colors.red),
                        ),
                      ),
                    ],
                  ),
                ),
              ],

              const SizedBox(height: 32),

              // Change button
              SizedBox(
                height: 52,
                child: ElevatedButton(
                  onPressed: authState.isLoading ? null : _handleChangePin,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  child: authState.isLoading
                      ? const SizedBox(
                          height: 24,
                          width: 24,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                          ),
                        )
                      : const Text(
                          'Change PIN',
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  bool _isWeakPin(String pin) {
    // Check for sequential numbers
    const sequences = ['0123', '1234', '2345', '3456', '4567', '5678', '6789', '9876', '8765', '7654', '6543', '5432', '4321', '3210'];
    if (sequences.contains(pin)) return true;

    // Check for repeated digits
    if (pin.split('').toSet().length == 1) return true;

    // Check for common PINs
    const commonPins = ['0000', '1111', '2222', '1212', '1010'];
    if (commonPins.contains(pin)) return true;

    return false;
  }
}
```

**Task 3.2: Biometric Settings (4 hours)**

```dart
// apps/field_sales/lib/features/settings/presentation/screens/security_settings_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:smart_dairy_field_sales/core/theme/app_colors.dart';
import 'package:smart_dairy_field_sales/features/auth/presentation/providers/auth_provider.dart';

class SecuritySettingsScreen extends ConsumerWidget {
  const SecuritySettingsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Security Settings'),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // PIN Section
          _buildSectionHeader('PIN'),
          Card(
            child: Column(
              children: [
                ListTile(
                  leading: const Icon(Icons.lock_outline),
                  title: const Text('Change PIN'),
                  subtitle: const Text('Update your 4-digit login PIN'),
                  trailing: const Icon(Icons.chevron_right),
                  onTap: () => Navigator.pushNamed(context, '/change-pin'),
                ),
              ],
            ),
          ),

          const SizedBox(height: 24),

          // Biometric Section
          _buildSectionHeader('Biometric Authentication'),
          Card(
            child: Column(
              children: [
                if (!authState.biometricAvailable)
                  const ListTile(
                    leading: Icon(Icons.fingerprint, color: Colors.grey),
                    title: Text('Biometric Login'),
                    subtitle: Text('Not available on this device'),
                  )
                else
                  SwitchListTile(
                    secondary: const Icon(Icons.fingerprint),
                    title: const Text('Biometric Login'),
                    subtitle: Text(
                      authState.biometricEnabled
                          ? 'Use fingerprint or face to login'
                          : 'Enable quick login with biometrics',
                    ),
                    value: authState.biometricEnabled,
                    onChanged: (value) async {
                      if (value) {
                        // Show PIN verification dialog first
                        final pin = await _showPinDialog(context);
                        if (pin != null && authState.user != null) {
                          await ref.read(authProvider.notifier).enableBiometric(
                            authState.user!.employeeCode,
                            pin,
                          );
                        }
                      } else {
                        await ref.read(authProvider.notifier).disableBiometric();
                      }
                    },
                  ),
              ],
            ),
          ),

          const SizedBox(height: 24),

          // Device Section
          _buildSectionHeader('Registered Devices'),
          Card(
            child: ListTile(
              leading: const Icon(Icons.devices),
              title: const Text('Manage Devices'),
              subtitle: const Text('View and remove registered devices'),
              trailing: const Icon(Icons.chevron_right),
              onTap: () => Navigator.pushNamed(context, '/devices'),
            ),
          ),

          const SizedBox(height: 24),

          // Security Tips
          _buildSecurityTips(),
        ],
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.only(left: 4, bottom: 8),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 14,
          fontWeight: FontWeight.bold,
          color: Colors.grey,
        ),
      ),
    );
  }

  Widget _buildSecurityTips() {
    return Card(
      color: AppColors.primary.withOpacity(0.05),
      child: const Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(Icons.security, color: AppColors.primary),
                SizedBox(width: 8),
                Text(
                  'Security Tips',
                  style: TextStyle(fontWeight: FontWeight.bold),
                ),
              ],
            ),
            SizedBox(height: 12),
            Text(
              '• Never share your PIN with anyone\n'
              '• Change your PIN regularly\n'
              '• Use biometric login for convenience\n'
              '• Report lost devices immediately\n'
              '• Log out when not using the app',
              style: TextStyle(fontSize: 13, height: 1.5),
            ),
          ],
        ),
      ),
    );
  }

  Future<String?> _showPinDialog(BuildContext context) async {
    final controller = TextEditingController();
    return showDialog<String>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Enter PIN'),
        content: TextField(
          controller: controller,
          obscureText: true,
          keyboardType: TextInputType.number,
          maxLength: 4,
          decoration: const InputDecoration(
            labelText: 'Current PIN',
            counterText: '',
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, controller.text),
            child: const Text('Confirm'),
          ),
        ],
      ),
    );
  }
}
```

#### End-of-Day 302 Deliverables

- [ ] Device management API endpoints
- [ ] Admin PIN reset functionality
- [ ] Admin views for field sales users
- [ ] Biometric authentication service
- [ ] Auth provider with biometric support
- [ ] PIN change screen
- [ ] Security settings screen
- [ ] Device management UI

---

### Days 303-310 Summary

**Day 303**: Customer Database Backend
- Customer sync API with delta updates
- Customer CRUD operations
- Territory-based customer filtering

**Day 304**: Customer Database Flutter
- Customer DAO and local operations
- Customer sync service
- Customer list screen with search

**Day 305**: Offline Sync Engine - Part 1
- Sync queue management
- Conflict detection
- Background sync setup

**Day 306**: Offline Sync Engine - Part 2
- Conflict resolution UI
- Sync status indicators
- Network connectivity handling

**Day 307**: Credit Limit Tracking
- Credit limit APIs
- Credit calculation service
- Credit warning alerts

**Day 308**: Price List Management
- Price list sync APIs
- Multiple price list support
- Customer-specific pricing

**Day 309**: Dashboard & Targets
- Sales dashboard screen
- Daily/weekly/monthly targets
- Performance metrics

**Day 310**: Integration & Testing
- Full sync testing
- Offline scenario testing
- Performance optimization

---

## 4. Technical Specifications

### 4.1 API Endpoints Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/field-sales/auth/login` | POST | Sales rep login |
| `/api/v1/field-sales/auth/verify-device` | POST | Verify device |
| `/api/v1/field-sales/auth/change-pin` | POST | Change PIN |
| `/api/v1/field-sales/auth/logout` | POST | Logout |
| `/api/v1/field-sales/devices` | GET | List devices |
| `/api/v1/field-sales/devices/{id}/deactivate` | POST | Deactivate device |
| `/api/v1/field-sales/customers` | GET | List customers |
| `/api/v1/field-sales/customers/sync` | GET | Sync customers |
| `/api/v1/field-sales/customers` | POST | Create customer |
| `/api/v1/field-sales/customers/{id}` | PUT | Update customer |
| `/api/v1/field-sales/price-lists` | GET | Get price lists |
| `/api/v1/field-sales/dashboard` | GET | Dashboard data |

### 4.2 Database Tables Summary

| Table | Description |
|-------|-------------|
| `Customers` | Offline customer database |
| `CustomerContacts` | Customer contact persons |
| `Products` | Offline product catalog |
| `PriceLists` | Price list definitions |
| `PriceListItems` | Price list line items |
| `PendingOrders` | Orders created offline |
| `PendingOrderLines` | Order line items |
| `CustomerVisits` | Visit tracking |
| `SyncQueue` | Pending sync operations |

---

## 5. Testing & Validation

### 5.1 Unit Tests
- PIN encryption/verification
- Device binding logic
- Sync conflict resolution
- Credit calculations

### 5.2 Integration Tests
- Full auth flow
- Customer sync cycle
- Offline operation tests
- Background sync tests

### 5.3 Security Tests
- PIN brute force protection
- Device binding validation
- Token expiration handling
- Data encryption verification

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Device theft with data | Medium | High | PIN lockout, remote wipe capability |
| Sync conflicts | High | Medium | Last-write-wins with UI resolution |
| Large customer database | Medium | Medium | Pagination, incremental sync |
| Poor network in field | High | High | Full offline capability |

---

## 7. Dependencies & Handoffs

### 7.1 Dependencies
- Milestone 51: Shared packages
- Milestone 55: API patterns established

### 7.2 Handoffs to Milestone 57
- [ ] Auth system complete
- [ ] Customer database syncing
- [ ] Offline architecture proven
- [ ] Price lists functional

---

## Document Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-03 | Technical Team | Initial document creation |

---

*End of Milestone 56 Document*
