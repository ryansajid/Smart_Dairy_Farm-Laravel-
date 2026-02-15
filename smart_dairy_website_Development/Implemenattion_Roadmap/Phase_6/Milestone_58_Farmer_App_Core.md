# Milestone 58: Farmer App Core

## Smart Dairy Digital Smart Portal + ERP System
### Phase 6: Mobile App Foundation

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-P6-M58-2026 |
| **Milestone Number** | 58 |
| **Milestone Title** | Farmer App Core |
| **Phase** | 6 - Mobile App Foundation |
| **Sprint Days** | Days 321-330 (10 working days) |
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

Build the Farmer Mobile Application foundation with simple PIN/fingerprint authentication, offline animal database with sync, RFID tag scanning via NFC, barcode/QR scanning via camera, photo-based animal lookup, and quick animal search functionality optimized for rural field conditions.

### 1.2 Objectives

| # | Objective | Priority | Success Metric |
|---|-----------|----------|----------------|
| 1 | Implement simple PIN/fingerprint authentication | Critical | <5 seconds login |
| 2 | Build offline animal database with full sync | Critical | 1000+ animals offline |
| 3 | Create RFID tag scanning via NFC | Critical | 95%+ read accuracy |
| 4 | Implement barcode/QR scanning via camera | Critical | Works in low light |
| 5 | Build photo-based animal lookup | High | ML-assisted matching |
| 6 | Create quick animal search | High | <500ms response |
| 7 | Design for low-literacy users | Critical | Icon-based navigation |
| 8 | Implement offline-first architecture | Critical | Full day operation |
| 9 | Build animal detail view | Medium | Complete animal info |
| 10 | Create sync status indicators | Medium | Clear sync feedback |

### 1.3 Key Deliverables

| # | Deliverable | Type | Owner | Acceptance Criteria |
|---|-------------|------|-------|---------------------|
| 1 | Farmer Auth System | Full Stack | Dev 1/3 | PIN + fingerprint |
| 2 | Animal Database API | Odoo REST | Dev 1 | Sync + delta updates |
| 3 | Offline Animal Storage | Flutter/Drift | Dev 3 | 1000+ records |
| 4 | RFID/NFC Scanner | Flutter | Dev 3 | Multi-tag support |
| 5 | Camera Barcode Scanner | Flutter | Dev 3 | Low-light capable |
| 6 | Photo Animal Lookup | ML/Flutter | Dev 2/3 | Image matching |
| 7 | Animal Search Screen | Flutter UI | Dev 3 | Multi-field search |
| 8 | Animal Detail Screen | Flutter UI | Dev 3 | Complete info display |
| 9 | Sync Engine | Flutter | Dev 2 | Background sync |
| 10 | Low-Literacy UI | Flutter UI | Dev 3 | Icon-based design |

### 1.4 Prerequisites

| # | Prerequisite | Source | Status |
|---|--------------|--------|--------|
| 1 | Shared Flutter packages | Milestone 51 | Required |
| 2 | Offline sync patterns | Milestone 56 | Required |
| 3 | Animal data in Odoo | Herd Management | Required |
| 4 | RFID tag assignments | Configuration | Required |
| 5 | NFC-capable test devices | Hardware | Required |

### 1.5 Success Criteria

- [ ] Farmer can login within 5 seconds
- [ ] Animal database syncs within 2 minutes
- [ ] RFID scan accuracy >95%
- [ ] Barcode scan works in low light
- [ ] Search returns results <500ms
- [ ] App works full 8-hour shift offline
- [ ] UI accessible for low-literacy users
- [ ] Battery usage <20% per 8-hour shift
- [ ] Code coverage >80%

---

## 2. Requirement Traceability Matrix

### 2.1 RFP Requirements

| RFP Req ID | Requirement Description | Implementation | Day |
|------------|------------------------|----------------|-----|
| RFP-MOB-008 | Farmer data entry module | Core + M59 | 321-330 |
| RFP-MOB-010 | RFID/barcode scanning | Scanner integration | 325-327 |
| RFP-MOB-011 | Offline-first architecture | Sync engine | 323-324 |
| RFP-MOB-026 | Simple farmer authentication | PIN/fingerprint | 321-322 |

### 2.2 BRD Requirements

| BRD Req ID | Business Requirement | Implementation | Day |
|------------|---------------------|----------------|-----|
| BRD-MOB-004 | 100% farm data digitization | Offline capability | 323-324 |
| BRD-MOB-005 | Low-literacy accessibility | Icon-based UI | 328-330 |
| BRD-MOB-014 | Quick animal identification | RFID + barcode | 325-327 |

### 2.3 SRS Requirements

| SRS Req ID | Technical Requirement | Implementation | Day |
|------------|----------------------|----------------|-----|
| SRS-MOB-050 | NFC tag reading | NFC scanner | 325-326 |
| SRS-MOB-051 | Camera barcode scanning | mobile_scanner | 327 |
| SRS-MOB-052 | Offline animal database | Drift + sync | 323-324 |
| SRS-MOB-053 | Photo-based lookup | ML Kit/TFLite | 328 |

---

## 3. Day-by-Day Breakdown

### Day 321 (Sprint Day 1): Farmer Authentication Backend

#### Objectives
- Build farmer user model with simple auth
- Implement PIN and fingerprint authentication
- Create farmer app project setup

#### Dev 1 - Backend Lead (8 hours)

**Task 1.1: Farmer User Model (4 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/models/farmer_user.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import hashlib
import secrets
from datetime import datetime, timedelta

class FarmerUser(models.Model):
    _name = 'farmer.user'
    _description = 'Farmer App User'

    name = fields.Char('Name', required=True)
    phone = fields.Char('Phone', index=True)
    partner_id = fields.Many2one('res.partner', 'Contact')

    # Farm association
    farm_ids = fields.Many2many('farm.farm', string='Assigned Farms')
    primary_farm_id = fields.Many2one('farm.farm', 'Primary Farm')

    # Simple PIN Authentication
    pin_hash = fields.Char('PIN Hash', readonly=True)
    pin_salt = fields.Char('PIN Salt', readonly=True)
    pin_attempts = fields.Integer('Failed Attempts', default=0)
    pin_locked_until = fields.Datetime('Locked Until')

    # Device binding (simpler than field sales)
    device_id = fields.Char('Registered Device ID')
    device_name = fields.Char('Device Name')
    last_login = fields.Datetime('Last Login')

    # Biometric
    biometric_enabled = fields.Boolean('Biometric Enabled', default=False)
    biometric_public_key = fields.Text('Biometric Public Key')

    # Sync tracking
    last_sync = fields.Datetime('Last Sync')
    sync_token = fields.Char('Sync Token')

    # Status
    is_active = fields.Boolean('Active', default=True)
    language = fields.Selection([
        ('bn_BD', 'Bengali'),
        ('en_US', 'English'),
    ], 'Language', default='bn_BD')

    # Permissions
    can_record_milk = fields.Boolean('Can Record Milk', default=True)
    can_record_health = fields.Boolean('Can Record Health', default=True)
    can_view_reports = fields.Boolean('Can View Reports', default=False)

    _sql_constraints = [
        ('phone_unique', 'unique(phone)', 'Phone number must be unique'),
    ]

    def set_pin(self, pin):
        """Set 4-digit PIN."""
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
        """Verify PIN."""
        self.ensure_one()

        # Check if locked
        if self.pin_locked_until and self.pin_locked_until > fields.Datetime.now():
            remaining = (self.pin_locked_until - datetime.now()).seconds // 60
            raise ValidationError(f'PIN locked. Try again in {remaining} minutes')

        if not self.pin_hash or not self.pin_salt:
            raise ValidationError('PIN not set. Contact administrator.')

        pin_hash = hashlib.sha256((pin + self.pin_salt).encode()).hexdigest()

        if pin_hash == self.pin_hash:
            self.write({
                'pin_attempts': 0,
                'pin_locked_until': False,
                'last_login': fields.Datetime.now()
            })
            return True
        else:
            attempts = self.pin_attempts + 1
            updates = {'pin_attempts': attempts}

            # Lock after 5 failed attempts for 15 minutes
            if attempts >= 5:
                updates['pin_locked_until'] = datetime.now() + timedelta(minutes=15)

            self.write(updates)
            raise ValidationError(f'Wrong PIN. {5 - attempts} tries left.')

    def get_accessible_animal_count(self):
        """Get count of animals user can access."""
        self.ensure_one()
        Animal = self.env['farm.animal']

        if self.farm_ids:
            return Animal.search_count([
                ('farm_id', 'in', self.farm_ids.ids),
                ('status', '=', 'active')
            ])
        return 0

    def generate_sync_token(self):
        """Generate new sync token for this session."""
        self.ensure_one()
        token = secrets.token_hex(32)
        self.write({'sync_token': token})
        return token
```

**Task 1.2: Farmer Auth Controller (3 hours)**

```python
# odoo/addons/smart_dairy_mobile_api/controllers/farmer_auth_controller.py

from odoo import http
from odoo.http import request
from datetime import datetime
import jwt

class FarmerAuthController(http.Controller):

    @http.route('/api/v1/farmer/auth/login', type='json', auth='public', methods=['POST'], csrf=False)
    def login(self, **kwargs):
        """
        Farmer login with phone and PIN.

        Request:
            - phone: Phone number
            - pin: 4-digit PIN
            - device_id: Device identifier
            - device_name: Device name (optional)
        """
        try:
            phone = kwargs.get('phone')
            pin = kwargs.get('pin')
            device_id = kwargs.get('device_id')
            device_name = kwargs.get('device_name')

            if not phone or not pin:
                return {'success': False, 'error': 'Phone and PIN required'}

            if not device_id:
                return {'success': False, 'error': 'Device ID required'}

            # Normalize phone number
            phone = self._normalize_phone(phone)

            # Find farmer user
            FarmerUser = request.env['farmer.user'].sudo()
            farmer = FarmerUser.search([
                ('phone', '=', phone),
                ('is_active', '=', True)
            ], limit=1)

            if not farmer:
                return {'success': False, 'error': 'User not found'}

            # Verify PIN
            try:
                farmer.verify_pin(pin)
            except Exception as e:
                return {'success': False, 'error': str(e)}

            # Update device info
            farmer.write({
                'device_id': device_id,
                'device_name': device_name,
            })

            # Generate tokens
            jwt_service = request.env['mobile.jwt.service'].sudo()
            tokens = jwt_service.generate_tokens(
                user_id=farmer.id,
                device_id=device_id,
                extra_claims={
                    'user_type': 'farmer',
                    'farmer_id': farmer.id,
                }
            )

            # Generate sync token
            sync_token = farmer.generate_sync_token()

            return {
                'success': True,
                'data': {
                    'access_token': tokens['access_token'],
                    'refresh_token': tokens['refresh_token'],
                    'expires_in': tokens['expires_in'],
                    'sync_token': sync_token,
                    'user': self._serialize_farmer(farmer),
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/farmer/auth/verify-biometric', type='json', auth='public', methods=['POST'], csrf=False)
    def verify_biometric(self, **kwargs):
        """
        Verify biometric authentication.

        Request:
            - phone: Phone number
            - signature: Biometric signature
            - device_id: Device identifier
        """
        try:
            phone = kwargs.get('phone')
            signature = kwargs.get('signature')
            device_id = kwargs.get('device_id')

            if not all([phone, signature, device_id]):
                return {'success': False, 'error': 'Missing required fields'}

            phone = self._normalize_phone(phone)

            FarmerUser = request.env['farmer.user'].sudo()
            farmer = FarmerUser.search([
                ('phone', '=', phone),
                ('is_active', '=', True),
                ('biometric_enabled', '=', True),
                ('device_id', '=', device_id),
            ], limit=1)

            if not farmer:
                return {'success': False, 'error': 'Biometric not enabled or device mismatch'}

            # Verify signature (simplified - in production use proper crypto)
            # This would verify the signature against stored public key
            if not self._verify_biometric_signature(farmer, signature):
                return {'success': False, 'error': 'Biometric verification failed'}

            # Update last login
            farmer.write({'last_login': datetime.now()})

            # Generate tokens
            jwt_service = request.env['mobile.jwt.service'].sudo()
            tokens = jwt_service.generate_tokens(
                user_id=farmer.id,
                device_id=device_id,
                extra_claims={
                    'user_type': 'farmer',
                    'farmer_id': farmer.id,
                }
            )

            sync_token = farmer.generate_sync_token()

            return {
                'success': True,
                'data': {
                    'access_token': tokens['access_token'],
                    'refresh_token': tokens['refresh_token'],
                    'expires_in': tokens['expires_in'],
                    'sync_token': sync_token,
                    'user': self._serialize_farmer(farmer),
                }
            }

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/farmer/auth/enable-biometric', type='json', auth='jwt_farmer', methods=['POST'], csrf=False)
    def enable_biometric(self, **kwargs):
        """Enable biometric authentication."""
        try:
            farmer = self._get_current_farmer()
            public_key = kwargs.get('public_key')

            if not public_key:
                return {'success': False, 'error': 'Public key required'}

            farmer.write({
                'biometric_enabled': True,
                'biometric_public_key': public_key,
            })

            return {'success': True, 'message': 'Biometric enabled'}

        except Exception as e:
            return {'success': False, 'error': str(e)}

    @http.route('/api/v1/farmer/auth/profile', type='json', auth='jwt_farmer', methods=['GET'], csrf=False)
    def get_profile(self, **kwargs):
        """Get farmer profile."""
        try:
            farmer = self._get_current_farmer()
            return {
                'success': True,
                'data': self._serialize_farmer(farmer)
            }
        except Exception as e:
            return {'success': False, 'error': str(e)}

    def _normalize_phone(self, phone):
        """Normalize Bangladesh phone number."""
        import re
        phone = re.sub(r'[\s\-]', '', phone)

        # Handle various formats
        if phone.startswith('+880'):
            phone = '0' + phone[4:]
        elif phone.startswith('880'):
            phone = '0' + phone[3:]
        elif not phone.startswith('0'):
            phone = '0' + phone

        return phone

    def _verify_biometric_signature(self, farmer, signature):
        """Verify biometric signature - simplified."""
        # In production, implement proper signature verification
        return True

    def _get_current_farmer(self):
        """Get current farmer from JWT."""
        # Extract from JWT claims
        farmer_id = request.env.context.get('farmer_id')
        if farmer_id:
            return request.env['farmer.user'].sudo().browse(farmer_id)
        raise Exception('Farmer not found in context')

    def _serialize_farmer(self, farmer):
        """Serialize farmer data."""
        return {
            'id': farmer.id,
            'name': farmer.name,
            'phone': farmer.phone,
            'language': farmer.language,
            'primary_farm': {
                'id': farmer.primary_farm_id.id,
                'name': farmer.primary_farm_id.name,
            } if farmer.primary_farm_id else None,
            'farms': [{'id': f.id, 'name': f.name} for f in farmer.farm_ids],
            'animal_count': farmer.get_accessible_animal_count(),
            'permissions': {
                'can_record_milk': farmer.can_record_milk,
                'can_record_health': farmer.can_record_health,
                'can_view_reports': farmer.can_view_reports,
            },
            'biometric_enabled': farmer.biometric_enabled,
            'last_sync': farmer.last_sync.isoformat() if farmer.last_sync else None,
        }
```

**Task 1.3: Farmer JWT Auth (1 hour)**

```python
# odoo/addons/smart_dairy_mobile_api/models/jwt_farmer.py

from odoo import models
from odoo.http import request
from odoo.exceptions import AccessDenied
import jwt

class JWTFarmerAuth(models.AbstractModel):
    _inherit = 'ir.http'

    @classmethod
    def _auth_method_jwt_farmer(cls):
        """JWT authentication for farmer endpoints."""
        token = request.httprequest.headers.get('Authorization', '').replace('Bearer ', '')

        if not token:
            raise AccessDenied('No authorization token')

        try:
            jwt_service = request.env['mobile.jwt.service'].sudo()
            payload = jwt_service.verify_token(token)

            if payload.get('user_type') != 'farmer':
                raise AccessDenied('Not authorized for farmer app')

            farmer_id = payload.get('farmer_id')
            request.env = request.env(context=dict(
                request.env.context,
                farmer_id=farmer_id
            ))

        except jwt.ExpiredSignatureError:
            raise AccessDenied('Token expired')
        except jwt.InvalidTokenError as e:
            raise AccessDenied(f'Invalid token: {str(e)}')
```

#### Dev 2 - Full-Stack Developer (8 hours)

**Task 2.1: Farmer App Project Setup (4 hours)**

```yaml
# apps/farmer/pubspec.yaml

name: smart_dairy_farmer
description: Smart Dairy Farmer Mobile App
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

  # Scanning
  mobile_scanner: ^3.5.5
  nfc_manager: ^3.3.0

  # Camera & Image
  camera: ^0.10.5+5
  image_picker: ^1.0.4
  image: ^4.1.3

  # Location
  geolocator: ^10.1.0

  # Connectivity
  connectivity_plus: ^5.0.2

  # Background tasks
  workmanager: ^0.5.2

  # Speech (for Bangla voice - M59)
  speech_to_text: ^6.4.1

  # UI
  cupertino_icons: ^1.0.6
  flutter_svg: ^2.0.9
  shimmer: ^3.0.0

  # Utils
  intl: ^0.18.1
  uuid: ^4.2.2

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
    - assets/sounds/
```

**Task 2.2: Farmer Database Schema (4 hours)**

```dart
// apps/farmer/lib/core/database/farmer_database.dart

import 'dart:io';
import 'package:drift/drift.dart';
import 'package:drift/native.dart';
import 'package:path_provider/path_provider.dart';
import 'package:path/path.dart' as p;
import 'package:sqlite3_flutter_libs/sqlite3_flutter_libs.dart';

part 'farmer_database.g.dart';

// ==================== Tables ====================

// Animals table
class Animals extends Table {
  IntColumn get id => integer()();
  IntColumn get serverId => integer().nullable()();
  TextColumn get name => text().nullable()();
  TextColumn get tagNumber => text().nullable()();
  TextColumn get rfidTag => text().nullable()();
  TextColumn get earTag => text().nullable()();
  TextColumn get species => text().withDefault(const Constant('cattle'))();
  TextColumn get breed => text().nullable()();
  TextColumn get gender => text()();
  DateTimeColumn get birthDate => dateTime().nullable()();
  IntColumn get farmId => integer()();
  TextColumn get farmName => text()();
  IntColumn get motherId => integer().nullable()();
  IntColumn get fatherId => integer().nullable()();
  TextColumn get status => text().withDefault(const Constant('active'))();
  TextColumn get imageUrl => text().nullable()();
  TextColumn get localImagePath => text().nullable()();
  RealColumn get currentWeight => real().nullable()();
  TextColumn get notes => text().nullable()();

  // Sync fields
  BoolColumn get needsSync => boolean().withDefault(const Constant(false))();
  DateTimeColumn get syncedAt => dateTime().nullable()();
  DateTimeColumn get localUpdatedAt => dateTime()();
  TextColumn get syncStatus => text().withDefault(const Constant('synced'))();

  @override
  Set<Column> get primaryKey => {id};
}

// Milk records (for quick entry reference)
class MilkRecords extends Table {
  TextColumn get localId => text()();
  IntColumn get serverId => integer().nullable()();
  IntColumn get animalId => integer().references(Animals, #id)();
  DateTimeColumn get recordDate => dateTime()();
  TextColumn get session => text()(); // 'morning', 'evening'
  RealColumn get quantity => real()();
  TextColumn get unit => text().withDefault(const Constant('liter'))();
  RealColumn get fatContent => real().nullable()();
  RealColumn get snf => real().nullable()();
  TextColumn get quality => text().nullable()();
  TextColumn get notes => text().nullable()();
  RealColumn get latitude => real().nullable()();
  RealColumn get longitude => real().nullable()();

  // Sync
  BoolColumn get needsSync => boolean().withDefault(const Constant(true))();
  DateTimeColumn get syncedAt => dateTime().nullable()();
  DateTimeColumn get createdAt => dateTime()();

  @override
  Set<Column> get primaryKey => {localId};
}

// Health observations
class HealthRecords extends Table {
  TextColumn get localId => text()();
  IntColumn get serverId => integer().nullable()();
  IntColumn get animalId => integer().references(Animals, #id)();
  DateTimeColumn get recordDate => dateTime()();
  TextColumn get observationType => text()(); // 'symptom', 'treatment', 'vaccination'
  TextColumn get description => text()();
  TextColumn get severity => text().nullable()(); // 'mild', 'moderate', 'severe'
  TextColumn get photoPath => text().nullable()();
  TextColumn get voiceNotePath => text().nullable()();
  RealColumn get latitude => real().nullable()();
  RealColumn get longitude => real().nullable()();

  // Sync
  BoolColumn get needsSync => boolean().withDefault(const Constant(true))();
  DateTimeColumn get syncedAt => dateTime().nullable()();
  DateTimeColumn get createdAt => dateTime()();

  @override
  Set<Column> get primaryKey => {localId};
}

// Tag mappings for quick lookup
class TagMappings extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get tagValue => text()();
  TextColumn get tagType => text()(); // 'rfid', 'barcode', 'ear_tag'
  IntColumn get animalId => integer().references(Animals, #id)();
  DateTimeColumn get syncedAt => dateTime()();

  @override
  List<Set<Column>> get uniqueKeys => [{tagValue, tagType}];
}

// Sync queue
class SyncQueue extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get entityType => text()();
  TextColumn get entityId => text()();
  TextColumn get action => text()();
  TextColumn get payload => text()();
  IntColumn get priority => integer().withDefault(const Constant(5))();
  IntColumn get retryCount => integer().withDefault(const Constant(0))();
  DateTimeColumn get createdAt => dateTime()();
  DateTimeColumn get lastAttempt => dateTime().nullable()();
  TextColumn get lastError => text().nullable()();
}

// Recent scans for quick access
class RecentScans extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get animalId => integer().references(Animals, #id)();
  TextColumn get scanType => text()(); // 'rfid', 'barcode', 'search', 'photo'
  DateTimeColumn get scannedAt => dateTime()();
}

// ==================== Database ====================

@DriftDatabase(tables: [
  Animals,
  MilkRecords,
  HealthRecords,
  TagMappings,
  SyncQueue,
  RecentScans,
])
class FarmerDatabase extends _$FarmerDatabase {
  FarmerDatabase() : super(_openConnection());

  @override
  int get schemaVersion => 1;

  @override
  MigrationStrategy get migration {
    return MigrationStrategy(
      onCreate: (Migrator m) async {
        await m.createAll();
      },
    );
  }

  // Quick lookup by tag
  Future<Animal?> findAnimalByTag(String tagValue) async {
    final mapping = await (select(tagMappings)
      ..where((t) => t.tagValue.equals(tagValue)))
      .getSingleOrNull();

    if (mapping != null) {
      return await (select(animals)
        ..where((a) => a.id.equals(mapping.animalId)))
        .getSingleOrNull();
    }
    return null;
  }

  // Search animals
  Future<List<Animal>> searchAnimals(String query, {int limit = 50}) async {
    return await (select(animals)
      ..where((a) =>
        a.name.like('%$query%') |
        a.tagNumber.like('%$query%') |
        a.earTag.like('%$query%'))
      ..limit(limit))
      .get();
  }

  // Add to recent scans
  Future<void> addRecentScan(int animalId, String scanType) async {
    // Remove old entries for same animal
    await (delete(recentScans)
      ..where((r) => r.animalId.equals(animalId)))
      .go();

    // Add new entry
    await into(recentScans).insert(RecentScansCompanion.insert(
      animalId: animalId,
      scanType: scanType,
      scannedAt: DateTime.now(),
    ));

    // Keep only last 20 scans
    final allScans = await (select(recentScans)
      ..orderBy([(r) => OrderingTerm.desc(r.scannedAt)]))
      .get();

    if (allScans.length > 20) {
      final toDelete = allScans.skip(20).map((s) => s.id).toList();
      await (delete(recentScans)
        ..where((r) => r.id.isIn(toDelete)))
        .go();
    }
  }
}

LazyDatabase _openConnection() {
  return LazyDatabase(() async {
    final dbFolder = await getApplicationDocumentsDirectory();
    final file = File(p.join(dbFolder.path, 'farmer.db'));

    if (Platform.isAndroid) {
      await applyWorkaroundToOpenSqlite3OnOldAndroidVersions();
    }

    return NativeDatabase.createInBackground(file);
  });
}
```

#### Dev 3 - Mobile Lead (8 hours)

**Task 3.1: Farmer Auth Repository (3 hours)**

```dart
// apps/farmer/lib/features/auth/data/repositories/farmer_auth_repository.dart

import 'package:dartz/dartz.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:smart_dairy_api/api_client.dart';
import 'package:smart_dairy_farmer/core/error/failures.dart';
import 'package:smart_dairy_farmer/features/auth/data/models/farmer_auth_models.dart';

class FarmerAuthRepository {
  final ApiClient _apiClient;
  final FlutterSecureStorage _secureStorage;

  FarmerAuthRepository(this._apiClient, this._secureStorage);

  static const _accessTokenKey = 'farmer_access_token';
  static const _refreshTokenKey = 'farmer_refresh_token';
  static const _syncTokenKey = 'farmer_sync_token';
  static const _userDataKey = 'farmer_user_data';
  static const _phoneKey = 'farmer_phone';

  Future<Either<Failure, FarmerLoginResponse>> login({
    required String phone,
    required String pin,
    required String deviceId,
    String? deviceName,
  }) async {
    try {
      final response = await _apiClient.post(
        '/api/v1/farmer/auth/login',
        data: {
          'phone': phone,
          'pin': pin,
          'device_id': deviceId,
          'device_name': deviceName,
        },
      );

      if (response['success'] == true) {
        final loginResponse = FarmerLoginResponse.fromJson(response['data']);

        // Store tokens
        await _secureStorage.write(key: _accessTokenKey, value: loginResponse.accessToken);
        await _secureStorage.write(key: _refreshTokenKey, value: loginResponse.refreshToken);
        await _secureStorage.write(key: _syncTokenKey, value: loginResponse.syncToken);
        await _secureStorage.write(key: _phoneKey, value: phone);

        // Update API client
        _apiClient.setAuthToken(loginResponse.accessToken);

        return Right(loginResponse);
      }

      return Left(AuthFailure(response['error'] ?? 'Login failed'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, FarmerLoginResponse>> loginWithBiometric({
    required String phone,
    required String signature,
    required String deviceId,
  }) async {
    try {
      final response = await _apiClient.post(
        '/api/v1/farmer/auth/verify-biometric',
        data: {
          'phone': phone,
          'signature': signature,
          'device_id': deviceId,
        },
      );

      if (response['success'] == true) {
        final loginResponse = FarmerLoginResponse.fromJson(response['data']);

        await _secureStorage.write(key: _accessTokenKey, value: loginResponse.accessToken);
        await _secureStorage.write(key: _refreshTokenKey, value: loginResponse.refreshToken);
        await _secureStorage.write(key: _syncTokenKey, value: loginResponse.syncToken);

        _apiClient.setAuthToken(loginResponse.accessToken);

        return Right(loginResponse);
      }

      return Left(AuthFailure(response['error'] ?? 'Biometric verification failed'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<Either<Failure, void>> enableBiometric(String publicKey) async {
    try {
      final response = await _apiClient.post(
        '/api/v1/farmer/auth/enable-biometric',
        data: {'public_key': publicKey},
      );

      if (response['success'] == true) {
        return const Right(null);
      }

      return Left(ServerFailure(response['error'] ?? 'Failed to enable biometric'));
    } catch (e) {
      return Left(NetworkFailure(e.toString()));
    }
  }

  Future<bool> isLoggedIn() async {
    final token = await _secureStorage.read(key: _accessTokenKey);
    return token != null && token.isNotEmpty;
  }

  Future<String?> getSyncToken() async {
    return await _secureStorage.read(key: _syncTokenKey);
  }

  Future<String?> getStoredPhone() async {
    return await _secureStorage.read(key: _phoneKey);
  }

  Future<void> restoreSession() async {
    final token = await _secureStorage.read(key: _accessTokenKey);
    if (token != null) {
      _apiClient.setAuthToken(token);
    }
  }

  Future<void> logout() async {
    await _secureStorage.delete(key: _accessTokenKey);
    await _secureStorage.delete(key: _refreshTokenKey);
    await _secureStorage.delete(key: _syncTokenKey);
    _apiClient.clearAuthToken();
  }
}
```

**Task 3.2: Farmer Login Screen (5 hours)**

```dart
// apps/farmer/lib/features/auth/presentation/screens/farmer_login_screen.dart

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:local_auth/local_auth.dart';
import 'package:device_info_plus/device_info_plus.dart';
import 'dart:io';
import 'package:smart_dairy_farmer/core/theme/app_colors.dart';
import 'package:smart_dairy_farmer/features/auth/presentation/providers/farmer_auth_provider.dart';

class FarmerLoginScreen extends ConsumerStatefulWidget {
  const FarmerLoginScreen({super.key});

  @override
  ConsumerState<FarmerLoginScreen> createState() => _FarmerLoginScreenState();
}

class _FarmerLoginScreenState extends ConsumerState<FarmerLoginScreen> {
  final _phoneController = TextEditingController();
  final _pinController = TextEditingController();
  final _localAuth = LocalAuthentication();

  bool _obscurePin = true;
  bool _isLoading = false;
  bool _canUseBiometric = false;
  String? _storedPhone;

  @override
  void initState() {
    super.initState();
    _checkBiometric();
    _loadStoredPhone();
  }

  @override
  void dispose() {
    _phoneController.dispose();
    _pinController.dispose();
    super.dispose();
  }

  Future<void> _checkBiometric() async {
    try {
      final canCheck = await _localAuth.canCheckBiometrics;
      final isSupported = await _localAuth.isDeviceSupported();
      setState(() => _canUseBiometric = canCheck && isSupported);
    } catch (_) {
      setState(() => _canUseBiometric = false);
    }
  }

  Future<void> _loadStoredPhone() async {
    final phone = await ref.read(farmerAuthRepositoryProvider).getStoredPhone();
    if (phone != null) {
      setState(() {
        _storedPhone = phone;
        _phoneController.text = phone;
      });
    }
  }

  Future<String> _getDeviceId() async {
    final deviceInfo = DeviceInfoPlugin();
    if (Platform.isAndroid) {
      final info = await deviceInfo.androidInfo;
      return info.id;
    } else {
      final info = await deviceInfo.iosInfo;
      return info.identifierForVendor ?? '';
    }
  }

  Future<void> _handleLogin() async {
    if (_phoneController.text.isEmpty || _pinController.text.length != 4) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter phone and 4-digit PIN')),
      );
      return;
    }

    setState(() => _isLoading = true);

    try {
      final deviceId = await _getDeviceId();
      final success = await ref.read(farmerAuthProvider.notifier).login(
        phone: _phoneController.text,
        pin: _pinController.text,
        deviceId: deviceId,
      );

      if (success && mounted) {
        Navigator.pushReplacementNamed(context, '/home');
      }
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Future<void> _handleBiometricLogin() async {
    if (_storedPhone == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please login with PIN first')),
      );
      return;
    }

    try {
      final authenticated = await _localAuth.authenticate(
        localizedReason: 'Login with fingerprint',
        options: const AuthenticationOptions(biometricOnly: true),
      );

      if (authenticated && mounted) {
        setState(() => _isLoading = true);
        final deviceId = await _getDeviceId();

        final success = await ref.read(farmerAuthProvider.notifier).loginWithBiometric(
          phone: _storedPhone!,
          deviceId: deviceId,
        );

        if (success && mounted) {
          Navigator.pushReplacementNamed(context, '/home');
        }
      }
    } on PlatformException catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Biometric error: ${e.message}')),
      );
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(farmerAuthProvider);

    return Scaffold(
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Column(
            children: [
              const SizedBox(height: 40),

              // Logo and welcome - Large icons for visibility
              Image.asset(
                'assets/images/cow_icon.png',
                height: 120,
                errorBuilder: (_, __, ___) => const Icon(
                  Icons.agriculture,
                  size: 100,
                  color: AppColors.primary,
                ),
              ),

              const SizedBox(height: 24),

              // Title in Bengali and English
              const Text(
                'স্মার্ট ডেইরি',
                style: TextStyle(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const Text(
                'Smart Dairy',
                style: TextStyle(
                  fontSize: 18,
                  color: Colors.grey,
                ),
              ),

              const SizedBox(height: 48),

              // Phone input with large text
              TextField(
                controller: _phoneController,
                keyboardType: TextInputType.phone,
                style: const TextStyle(fontSize: 20),
                decoration: InputDecoration(
                  labelText: 'ফোন নম্বর / Phone',
                  hintText: '01XXXXXXXXX',
                  prefixIcon: const Icon(Icons.phone, size: 28),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  contentPadding: const EdgeInsets.all(20),
                ),
              ),

              const SizedBox(height: 20),

              // PIN input with large digits
              TextField(
                controller: _pinController,
                obscureText: _obscurePin,
                keyboardType: TextInputType.number,
                maxLength: 4,
                style: const TextStyle(fontSize: 24, letterSpacing: 8),
                textAlign: TextAlign.center,
                inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                decoration: InputDecoration(
                  labelText: 'পিন / PIN',
                  counterText: '',
                  prefixIcon: const Icon(Icons.lock, size: 28),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                  contentPadding: const EdgeInsets.all(20),
                  suffixIcon: IconButton(
                    icon: Icon(
                      _obscurePin ? Icons.visibility_off : Icons.visibility,
                      size: 28,
                    ),
                    onPressed: () => setState(() => _obscurePin = !_obscurePin),
                  ),
                ),
              ),

              // Error message
              if (authState.error != null)
                Container(
                  margin: const EdgeInsets.only(top: 16),
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.red[50],
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.error, color: Colors.red),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          authState.error!,
                          style: const TextStyle(color: Colors.red, fontSize: 16),
                        ),
                      ),
                    ],
                  ),
                ),

              const SizedBox(height: 32),

              // Login button - Large and prominent
              SizedBox(
                width: double.infinity,
                height: 60,
                child: ElevatedButton(
                  onPressed: (_isLoading || authState.isLoading) ? null : _handleLogin,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  child: (_isLoading || authState.isLoading)
                      ? const SizedBox(
                          height: 28,
                          width: 28,
                          child: CircularProgressIndicator(
                            strokeWidth: 3,
                            color: Colors.white,
                          ),
                        )
                      : const Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.login, size: 28),
                            SizedBox(width: 12),
                            Text(
                              'লগইন / Login',
                              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                            ),
                          ],
                        ),
                ),
              ),

              // Biometric login button
              if (_canUseBiometric && _storedPhone != null) ...[
                const SizedBox(height: 24),
                const Text(
                  'অথবা / Or',
                  style: TextStyle(color: Colors.grey),
                ),
                const SizedBox(height: 16),
                SizedBox(
                  width: double.infinity,
                  height: 60,
                  child: OutlinedButton(
                    onPressed: _isLoading ? null : _handleBiometricLogin,
                    style: OutlinedButton.styleFrom(
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    child: const Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.fingerprint, size: 32),
                        SizedBox(width: 12),
                        Text(
                          'আঙুলের ছাপ',
                          style: TextStyle(fontSize: 18),
                        ),
                      ],
                    ),
                  ),
                ),
              ],

              const SizedBox(height: 48),

              // Help text with icons
              TextButton.icon(
                onPressed: () => _showHelpDialog(context),
                icon: const Icon(Icons.help_outline, size: 28),
                label: const Text(
                  'সাহায্য / Help',
                  style: TextStyle(fontSize: 16),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  void _showHelpDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Row(
          children: [
            Icon(Icons.help_outline, size: 28),
            SizedBox(width: 8),
            Text('সাহায্য / Help'),
          ],
        ),
        content: const Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'ফোন নম্বর:',
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
            ),
            Text('আপনার নিবন্ধিত ফোন নম্বর দিন', style: TextStyle(fontSize: 14)),
            SizedBox(height: 16),
            Text(
              'পিন:',
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
            ),
            Text('৪ সংখ্যার পিন দিন', style: TextStyle(fontSize: 14)),
            SizedBox(height: 16),
            Text(
              'সমস্যা?',
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
            ),
            Text('সুপারভাইজারের সাথে যোগাযোগ করুন', style: TextStyle(fontSize: 14)),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('ঠিক আছে / OK', style: TextStyle(fontSize: 16)),
          ),
        ],
      ),
    );
  }
}
```

#### End-of-Day 321 Deliverables

- [ ] Farmer user model with PIN auth
- [ ] Farmer auth controller
- [ ] Biometric authentication support
- [ ] JWT auth for farmer endpoints
- [ ] Farmer app project setup
- [ ] Farmer database schema
- [ ] Auth repository
- [ ] Login screen (Bengali + English)

---

### Days 322-330 Summary

**Day 322**: Auth Completion & Home Screen
- Biometric service integration
- Home dashboard with large icons
- Language toggle (Bengali/English)

**Day 323**: Animal Database Backend
- Animal sync API with delta updates
- Tag lookup APIs
- Search APIs

**Day 324**: Animal Local Storage
- Animal DAO implementation
- Tag mapping storage
- Sync service

**Day 325**: RFID/NFC Scanner - Part 1
- NFC manager integration
- RFID read functionality
- Multiple tag format support

**Day 326**: RFID/NFC Scanner - Part 2
- Scanner UI screen
- Tag detection feedback
- Animal lookup on scan

**Day 327**: Barcode/QR Scanner
- Camera-based scanning
- Low-light optimization
- Multiple barcode formats

**Day 328**: Photo-Based Lookup
- Camera capture
- Image preprocessing
- ML-based matching (basic)

**Day 329**: Animal Search & Detail
- Multi-field search screen
- Animal detail view
- Recent scans display

**Day 330**: Integration & Testing
- Full scan workflow testing
- Offline scenario testing
- Low-literacy UI review

---

## 4. Technical Specifications

### 4.1 API Endpoints Summary

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/farmer/auth/login` | POST | Farmer login |
| `/api/v1/farmer/auth/verify-biometric` | POST | Biometric login |
| `/api/v1/farmer/auth/enable-biometric` | POST | Enable biometric |
| `/api/v1/farmer/animals` | GET | List animals |
| `/api/v1/farmer/animals/sync` | GET | Sync animals |
| `/api/v1/farmer/animals/lookup` | GET | Lookup by tag |
| `/api/v1/farmer/animals/{id}` | GET | Animal detail |
| `/api/v1/farmer/animals/search` | GET | Search animals |

### 4.2 Scanning Specifications

| Scan Type | Technology | Formats | Notes |
|-----------|------------|---------|-------|
| RFID | NFC | ISO 14443, ISO 15693 | 5cm range |
| Barcode | Camera | Code128, Code39, EAN | Low-light support |
| QR Code | Camera | QR | Animal ID encoding |

---

## 5. Testing & Validation

### 5.1 Unit Tests
- PIN encryption/verification
- Tag lookup logic
- Search algorithms
- Sync conflict resolution

### 5.2 Integration Tests
- Full auth flow
- Animal sync cycle
- Scanner accuracy
- Offline operation

### 5.3 Usability Tests
- Low-literacy user testing
- Large button accessibility
- Bengali language accuracy
- Field condition simulation

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| NFC not available | Medium | High | Fallback to barcode/search |
| Poor lighting for camera | High | Medium | Flash support, low-light optimization |
| Limited literacy | High | High | Icon-based UI, voice feedback |
| Network unavailable | High | High | Full offline capability |

---

## 7. Dependencies & Handoffs

### 7.1 Dependencies
- Milestone 51: Shared packages
- Milestone 56: Offline patterns

### 7.2 Handoffs to Milestone 59
- [ ] Auth system complete
- [ ] Animal database syncing
- [ ] Scanner functionality
- [ ] Basic UI patterns

---

## Document Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-02-03 | Technical Team | Initial document creation |

---

*End of Milestone 58 Document*
