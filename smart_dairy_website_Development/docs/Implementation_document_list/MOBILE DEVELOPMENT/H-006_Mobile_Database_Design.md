# H-006: Mobile Database Design (SQLite/Hive)

## Smart Dairy Ltd. - Mobile Application Database Architecture

---

**Document Control**

| Field | Value |
|-------|-------|
| **Document ID** | H-006 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Database Architect |
| **Status** | Approved |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [SQLite with Drift](#2-sqlite-with-drift)
3. [Hive for Key-Value Storage](#3-hive-for-key-value-storage)
4. [Database Schema Design](#4-database-schema-design)
5. [Entity Definitions](#5-entity-definitions)
6. [DAO Pattern](#6-dao-pattern)
7. [Database Migrations](#7-database-migrations)
8. [Query Patterns](#8-query-patterns)
9. [Relationships](#9-relationships)
10. [Encryption](#10-encryption)
11. [Backup & Restore](#11-backup--restore)
12. [Performance Optimization](#12-performance-optimization)
13. [Testing](#13-testing)
14. [Appendices](#14-appendices)

---

## 1. Introduction

### 1.1 Purpose

This document defines the mobile database architecture for Smart Dairy Ltd.'s Flutter mobile application. It establishes standards for local data persistence using SQLite (via Drift) for relational data and Hive for key-value storage.

### 1.2 Local Database Strategy

The mobile application employs a dual-database strategy:

| Database | Purpose | Use Cases |
|----------|---------|-----------|
| **SQLite (Drift)** | Structured relational data | Animals, milk records, health records, orders |
| **Hive** | Key-value storage | User preferences, auth tokens, app settings, cache |

### 1.3 SQLite vs Hive Selection Criteria

| Criteria | SQLite (Drift) | Hive |
|----------|----------------|------|
| Data Structure | Complex relational | Simple key-value |
| Query Capability | Full SQL support | Direct key access only |
| Type Safety | Full type safety via code generation | Type adapters required |
| Performance | Excellent for complex queries | Fastest for simple reads |
| Relationships | Foreign keys, joins | Not supported |
| Transactions | Full ACID compliance | Limited |
| Encryption | SQLCipher support | Built-in AES-256 |

### 1.4 Technology Stack

```yaml
# pubspec.yaml dependencies
dependencies:
  # SQLite with Drift
  drift: ^2.15.0
  sqlite3_flutter_libs: ^0.5.20
  path_provider: ^2.1.2
  path: ^1.8.3
  
  # Hive for Key-Value
  hive: ^2.2.3
  hive_flutter: ^1.1.0
  
  # Encryption
  sqlcipher_flutter_libs: ^0.5.20
  encrypt: ^5.0.1
  
dev_dependencies:
  drift_dev: ^2.15.0
  build_runner: ^2.4.8
  hive_generator: ^2.0.1
```

---

## 2. SQLite with Drift

### 2.1 Drift Setup

#### 2.1.1 Database Connection Setup

```dart
// lib/database/app_database.dart

import 'package:drift/drift.dart';
import 'package:drift_flutter/drift_flutter.dart';
import 'package:path_provider/path_provider.dart';
import 'package:path/path.dart' as p;

part 'app_database.g.dart';

@DriftDatabase(
  tables: [
    Animals,
    MilkRecords,
    HealthRecords,
    Users,
    Orders,
    SyncQueue,
  ],
  daos: [
    AnimalDao,
    MilkRecordDao,
    HealthRecordDao,
    UserDao,
    OrderDao,
    SyncQueueDao,
  ],
)
class AppDatabase extends _$AppDatabase {
  AppDatabase() : super(_openConnection());

  AppDatabase.forTesting(DatabaseConnection connection) : super(connection);

  @override
  int get schemaVersion => 1;

  static QueryExecutor _openConnection() {
    return driftDatabase(
      name: 'smart_dairy_db',
      native: const DriftNativeOptions(
        databasePath: getDatabasePath,
      ),
    );
  }

  static Future<String> getDatabasePath() async {
    final dbFolder = await getApplicationDocumentsDirectory();
    return p.join(dbFolder.path, 'smart_dairy.db');
  }
}
```

#### 2.1.2 Build Runner Configuration

```yaml
# build.yaml
targets:
  $default:
    builders:
      drift_dev:
        options:
          store_date_time_values_as_text: true
          sqlite:
            modules:
              - fts5
              - json1
```

### 2.2 Type-Safe Queries

Drift generates type-safe Dart code from table definitions:

```dart
// Generated code provides:
// - Type-safe SELECT queries
// - Compile-time checked column references
// - Auto-complete support for table columns

// Example of generated capabilities:
final animal = await (select(animals)
  ..where((a) => a.tag.equals('COW001'))
  ..limit(1))
  .getSingle();

// Type-safe inserts
await into(animals).insert(AnimalsCompanion(
  tag: Value('COW002'),
  name: Value('Bella'),
  breed: Value('Holstein'),
));
```

### 2.3 Migration Strategy

```dart
@override
MigrationStrategy get migration => MigrationStrategy(
  onCreate: (Migrator m) async {
    await m.createAll();
    await _createIndexes(m);
    await _insertSeedData();
  },
  onUpgrade: (Migrator m, int from, int to) async {
    await customStatement('PRAGMA foreign_keys = OFF');
    
    if (from < 2) {
      await m.addColumn(animals, animals.weight);
    }
    if (from < 3) {
      await m.createTable(healthRecords);
    }
    
    await customStatement('PRAGMA foreign_keys = ON');
  },
  beforeOpen: (details) async {
    await customStatement('PRAGMA foreign_keys = ON');
    await customStatement('PRAGMA journal_mode = WAL');
  },
);
```

---

## 3. Hive for Key-Value Storage

### 3.1 Hive Setup

```dart
// lib/storage/hive_storage.dart

import 'package:hive_flutter/hive_flutter.dart';

class HiveStorage {
  static const String _authBox = 'auth';
  static const String _settingsBox = 'settings';
  static const String _cacheBox = 'cache';
  static const String _syncBox = 'sync';

  static Future<void> initialize() async {
    await Hive.initFlutter();
    
    // Register adapters
    Hive.registerAdapter(UserProfileAdapter());
    Hive.registerAdapter(AppSettingsAdapter());
    
    // Open boxes
    await Hive.openBox<UserProfile>(_authBox);
    await Hive.openBox<AppSettings>(_settingsBox);
    await Hive.openBox<String>(_cacheBox);
    await Hive.openBox<SyncMetadata>(_syncBox);
  }

  // Auth storage
  static Box<UserProfile> get authBox => Hive.box<UserProfile>(_authBox);
  
  // Settings storage
  static Box<AppSettings> get settingsBox => Hive.box<AppSettings>(_settingsBox);
  
  // Cache storage
  static Box<String> get cacheBox => Hive.box<String>(_cacheBox);
  
  // Sync metadata storage
  static Box<SyncMetadata> get syncBox => Hive.box<SyncMetadata>(_syncBox);
}
```

### 3.2 Type Adapters

```dart
// lib/models/user_profile.dart

import 'package:hive/hive.dart';

part 'user_profile.g.dart';

@HiveType(typeId: 1)
class UserProfile extends HiveObject {
  @HiveField(0)
  final String id;
  
  @HiveField(1)
  final String name;
  
  @HiveField(2)
  final String email;
  
  @HiveField(3)
  final String role;
  
  @HiveField(4)
  final String authToken;
  
  @HiveField(5)
  final DateTime tokenExpiry;
  
  @HiveField(6)
  final String? refreshToken;

  UserProfile({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    required this.authToken,
    required this.tokenExpiry,
    this.refreshToken,
  });

  bool get isTokenValid => DateTime.now().isBefore(tokenExpiry);
}
```

### 3.3 Hive Encryption

```dart
// lib/storage/encrypted_hive.dart

import 'package:hive/hive.dart';
import 'package:encrypt/encrypt.dart' as encrypt;

class EncryptedHiveStorage {
  late final encrypt.Key _encryptionKey;
  late final HiveCipher _cipher;

  EncryptedHiveStorage(String base64Key) {
    _encryptionKey = encrypt.Key.fromBase64(base64Key);
    _cipher = HiveAesCipher(_encryptionKey.bytes);
  }

  Future<Box<T>> openEncryptedBox<T>(String name) async {
    return await Hive.openBox<T>(
      name,
      encryptionCipher: _cipher,
    );
  }

  // Generate secure key for first-time setup
  static String generateKey() {
    final key = encrypt.Key.fromSecureRandom(32);
    return key.base64;
  }
}
```

---

## 4. Database Schema Design

### 4.1 Schema Overview

```
+---------------+     +---------------+     +---------------+
|    Users      |     |   Animals     |     | MilkRecords   |
+---------------+     +---------------+     +---------------+
| PK id         |---->| PK id         |<----| PK id         |
|    name       |     |    tag (UQ)   |     | FK animalId   |
|    role       |     |    name       |     |    date       |
|    email      |     |    breed      |     |    amount     |
+---------------+     |    birthDate  |     |    quality    |
                      |    gender     |     +---------------+
                      |    status     |            |
                      |    ownerId    |------------|
                      +---------------+
                             |
                             |
                             v
                      +---------------+
                      |HealthRecords  |
                      +---------------+
                      | PK id         |
                      | FK animalId   |
                      |    date       |
                      |    type       |
                      |    diagnosis  |
                      +---------------+
```

### 4.2 Table Specifications

#### 4.2.1 Animals Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | INTEGER | PRIMARY KEY AUTOINCREMENT | Unique identifier |
| tag | TEXT | UNIQUE, NOT NULL | Physical ear tag number |
| name | TEXT | NOT NULL | Animal name |
| breed | TEXT | NOT NULL | Breed (Holstein, Jersey, etc.) |
| birthDate | DATETIME | | Date of birth |
| gender | TEXT | CHECK (gender IN ('M', 'F')) | M=Male, F=Female |
| status | TEXT | DEFAULT 'active' | active, sold, deceased |
| ownerId | INTEGER | FOREIGN KEY (users.id) | Reference to owner |
| weight | REAL | | Current weight in kg |
| imageUrl | TEXT | | Photo URL/path |
| notes | TEXT | | Additional notes |
| synced | BOOLEAN | DEFAULT FALSE | Sync status |
| createdAt | DATETIME | DEFAULT CURRENT_TIMESTAMP | Creation time |
| updatedAt | DATETIME | DEFAULT CURRENT_TIMESTAMP | Last update time |

#### 4.2.2 MilkRecords Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | INTEGER | PRIMARY KEY AUTOINCREMENT | Unique identifier |
| animalId | INTEGER | FOREIGN KEY, NOT NULL | Reference to animal |
| date | DATETIME | NOT NULL | Recording date/time |
| amount | REAL | NOT NULL, CHECK (amount > 0) | Milk volume in liters |
| quality | TEXT | | Grade (A, B, C) |
| fatContent | REAL | | Fat percentage |
| proteinContent | REAL | | Protein percentage |
| temperature | REAL | | Milk temperature |
| recordedBy | INTEGER | FOREIGN KEY (users.id) | User who recorded |
| session | TEXT | | Morning, Afternoon, Evening |
| notes | TEXT | | Additional notes |
| synced | BOOLEAN | DEFAULT FALSE | Sync status |
| createdAt | DATETIME | DEFAULT CURRENT_TIMESTAMP | Creation time |

#### 4.2.3 HealthRecords Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | INTEGER | PRIMARY KEY AUTOINCREMENT | Unique identifier |
| animalId | INTEGER | FOREIGN KEY, NOT NULL | Reference to animal |
| date | DATETIME | NOT NULL | Record date |
| type | TEXT | NOT NULL | Checkup, Treatment, Vaccination |
| diagnosis | TEXT | | Diagnosis description |
| treatment | TEXT | | Treatment given |
| medications | TEXT | | JSON array of medications |
| vetName | TEXT | | Veterinarian name |
| cost | REAL | | Treatment cost |
| followUpDate | DATETIME | | Next checkup date |
| notes | TEXT | | Additional notes |
| synced | BOOLEAN | DEFAULT FALSE | Sync status |
| createdAt | DATETIME | DEFAULT CURRENT_TIMESTAMP | Creation time |

#### 4.2.4 Users Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | INTEGER | PRIMARY KEY AUTOINCREMENT | Unique identifier |
| serverId | TEXT | UNIQUE | Server-side user ID |
| name | TEXT | NOT NULL | Full name |
| email | TEXT | UNIQUE | Email address |
| phone | TEXT | | Phone number |
| role | TEXT | NOT NULL | admin, manager, worker, vet |
| passwordHash | TEXT | | Local password hash |
| isActive | BOOLEAN | DEFAULT TRUE | Account status |
| lastLogin | DATETIME | | Last login timestamp |
| synced | BOOLEAN | DEFAULT FALSE | Sync status |
| createdAt | DATETIME | DEFAULT CURRENT_TIMESTAMP | Creation time |
| updatedAt | DATETIME | DEFAULT CURRENT_TIMESTAMP | Update time |

#### 4.2.5 Orders Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | INTEGER | PRIMARY KEY AUTOINCREMENT | Unique identifier |
| serverId | TEXT | UNIQUE | Server-side order ID |
| orderNumber | TEXT | UNIQUE, NOT NULL | Human-readable order number |
| customerId | INTEGER | FOREIGN KEY | Reference to customer |
| items | TEXT | NOT NULL | JSON array of order items |
| totalAmount | REAL | NOT NULL | Order total |
| status | TEXT | DEFAULT 'pending' | pending, confirmed, delivered, cancelled |
| syncStatus | TEXT | DEFAULT 'pending' | synced, pending, failed |
| orderDate | DATETIME | NOT NULL | Order placement date |
| deliveryDate | DATETIME | | Expected delivery date |
| paymentStatus | TEXT | DEFAULT 'unpaid' | unpaid, partial, paid |
| notes | TEXT | | Additional notes |
| createdAt | DATETIME | DEFAULT CURRENT_TIMESTAMP | Creation time |
| updatedAt | DATETIME | DEFAULT CURRENT_TIMESTAMP | Update time |

#### 4.2.6 SyncQueue Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | INTEGER | PRIMARY KEY AUTOINCREMENT | Unique identifier |
| operation | TEXT | NOT NULL | CREATE, UPDATE, DELETE |
| tableName | TEXT | NOT NULL | Target table name |
| recordId | INTEGER | NOT NULL | Local record ID |
| serverId | TEXT | | Server record ID if exists |
| data | TEXT | NOT NULL | JSON serialized data |
| priority | INTEGER | DEFAULT 5 | 1=Highest, 10=Lowest |
| attempts | INTEGER | DEFAULT 0 | Retry count |
| lastAttempt | DATETIME | | Last sync attempt |
| errorMessage | TEXT | | Last error if failed |
| createdAt | DATETIME | DEFAULT CURRENT_TIMESTAMP | Creation time |

---

## 5. Entity Definitions

### 5.1 Table Definitions with Drift

```dart
// lib/database/tables.dart

import 'package:drift/drift.dart';

// ============ ANIMALS TABLE ============
class Animals extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get tag => text().withLength(min: 1, max: 50).unique()();
  TextColumn get name => text().withLength(min: 1, max: 100)();
  TextColumn get breed => text().withLength(min: 1, max: 50)();
  DateTimeColumn get birthDate => dateTime().nullable()();
  TextColumn get gender => text().withLength(min: 1, max: 1)();
  TextColumn get status => text().withDefault(const Constant('active'))();
  IntColumn get ownerId => integer().nullable().references(Users, #id)();
  RealColumn get weight => real().nullable()();
  TextColumn get imageUrl => text().nullable()();
  TextColumn get notes => text().nullable()();
  BoolColumn get synced => boolean().withDefault(const Constant(false))();
  DateTimeColumn get createdAt => dateTime().withDefault(currentDateAndTime)();
  DateTimeColumn get updatedAt => dateTime().withDefault(currentDateAndTime)();

  @override
  List<String> get customConstraints => [
    'CHECK (gender IN (\'M\', \'F\'))',
    'CHECK (status IN (\'active\', \'sold\', \'deceased\'))',
  ];

  @override
  String get tableName => 'animals';
}

// ============ MILK RECORDS TABLE ============
class MilkRecords extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get animalId => integer().references(Animals, #id, onDelete: KeyAction.cascade)();
  DateTimeColumn get date => dateTime()();
  RealColumn get amount => real()();
  TextColumn get quality => text().nullable()();
  RealColumn get fatContent => real().nullable()();
  RealColumn get proteinContent => real().nullable()();
  RealColumn get temperature => real().nullable()();
  IntColumn get recordedBy => integer().nullable().references(Users, #id)();
  TextColumn get session => text().nullable()();
  TextColumn get notes => text().nullable()();
  BoolColumn get synced => boolean().withDefault(const Constant(false))();
  DateTimeColumn get createdAt => dateTime().withDefault(currentDateAndTime)();

  @override
  List<String> get customConstraints => [
    'CHECK (amount > 0)',
    'CHECK (quality IS NULL OR quality IN (\'A\', \'B\', \'C\'))',
    'CHECK (session IS NULL OR session IN (\'Morning\', \'Afternoon\', \'Evening\'))',
  ];

  @override
  String get tableName => 'milk_records';
}

// ============ HEALTH RECORDS TABLE ============
class HealthRecords extends Table {
  IntColumn get id => integer().autoIncrement()();
  IntColumn get animalId => integer().references(Animals, #id, onDelete: KeyAction.cascade)();
  DateTimeColumn get date => dateTime()();
  TextColumn get type => text()();
  TextColumn get diagnosis => text().nullable()();
  TextColumn get treatment => text().nullable()();
  TextColumn get medications => text().nullable()(); // JSON
  TextColumn get vetName => text().nullable()();
  RealColumn get cost => real().nullable()();
  DateTimeColumn get followUpDate => dateTime().nullable()();
  TextColumn get notes => text().nullable()();
  BoolColumn get synced => boolean().withDefault(const Constant(false))();
  DateTimeColumn get createdAt => dateTime().withDefault(currentDateAndTime)();

  @override
  List<String> get customConstraints => [
    'CHECK (type IN (\'Checkup\', \'Treatment\', \'Vaccination\', \'Surgery\', \'Deworming\'))',
  ];

  @override
  String get tableName => 'health_records';
}

// ============ USERS TABLE ============
class Users extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get serverId => text().nullable().unique()();
  TextColumn get name => text().withLength(min: 1, max: 100)();
  TextColumn get email => text().nullable().unique()();
  TextColumn get phone => text().nullable()();
  TextColumn get role => text()();
  TextColumn get passwordHash => text().nullable()();
  BoolColumn get isActive => boolean().withDefault(const Constant(true))();
  DateTimeColumn get lastLogin => dateTime().nullable()();
  BoolColumn get synced => boolean().withDefault(const Constant(false))();
  DateTimeColumn get createdAt => dateTime().withDefault(currentDateAndTime)();
  DateTimeColumn get updatedAt => dateTime().withDefault(currentDateAndTime)();

  @override
  List<String> get customConstraints => [
    'CHECK (role IN (\'admin\', \'manager\', \'worker\', \'vet\', \'viewer\'))',
  ];

  @override
  String get tableName => 'users';
}

// ============ ORDERS TABLE ============
class Orders extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get serverId => text().nullable().unique()();
  TextColumn get orderNumber => text().unique()();
  IntColumn get customerId => integer().nullable()();
  TextColumn get items => text()(); // JSON array
  RealColumn get totalAmount => real()();
  TextColumn get status => text().withDefault(const Constant('pending'))();
  TextColumn get syncStatus => text().withDefault(const Constant('pending'))();
  DateTimeColumn get orderDate => dateTime()();
  DateTimeColumn get deliveryDate => dateTime().nullable()();
  TextColumn get paymentStatus => text().withDefault(const Constant('unpaid'))();
  TextColumn get notes => text().nullable()();
  DateTimeColumn get createdAt => dateTime().withDefault(currentDateAndTime)();
  DateTimeColumn get updatedAt => dateTime().withDefault(currentDateAndTime)();

  @override
  List<String> get customConstraints => [
    'CHECK (status IN (\'pending\', \'confirmed\', \'processing\', \'shipped\', \'delivered\', \'cancelled\'))',
    'CHECK (syncStatus IN (\'synced\', \'pending\', \'failed\'))',
    'CHECK (paymentStatus IN (\'unpaid\', \'partial\', \'paid\'))',
  ];

  @override
  String get tableName => 'orders';
}

// ============ SYNC QUEUE TABLE ============
class SyncQueue extends Table {
  IntColumn get id => integer().autoIncrement()();
  TextColumn get operation => text()();
  TextColumn get tableName => text()();
  IntColumn get recordId => integer()();
  TextColumn get serverId => text().nullable()();
  TextColumn get data => text()();
  IntColumn get priority => integer().withDefault(const Constant(5))();
  IntColumn get attempts => integer().withDefault(const Constant(0))();
  DateTimeColumn get lastAttempt => dateTime().nullable()();
  TextColumn get errorMessage => text().nullable()();
  DateTimeColumn get createdAt => dateTime().withDefault(currentDateAndTime)();

  @override
  List<String> get customConstraints => [
    'CHECK (operation IN (\'CREATE\', \'UPDATE\', \'DELETE\'))',
    'CHECK (priority BETWEEN 1 AND 10)',
  ];

  @override
  String get tableName => 'sync_queue';
}
```

### 5.2 Data Classes

```dart
// lib/models/animal.dart

import 'package:freezed_annotation/freezed_annotation.dart';

part 'animal.freezed.dart';
part 'animal.g.dart';

@freezed
class Animal with _$Animal {
  const factory Animal({
    required int id,
    required String tag,
    required String name,
    required String breed,
    DateTime? birthDate,
    required String gender,
    @Default('active') String status,
    int? ownerId,
    double? weight,
    String? imageUrl,
    String? notes,
    @Default(false) bool synced,
    required DateTime createdAt,
    required DateTime updatedAt,
  }) = _Animal;

  factory Animal.fromJson(Map<String, dynamic> json) =>
      _$AnimalFromJson(json);
}

// lib/models/milk_record.dart

@freezed
class MilkRecord with _$MilkRecord {
  const factory MilkRecord({
    required int id,
    required int animalId,
    required DateTime date,
    required double amount,
    String? quality,
    double? fatContent,
    double? proteinContent,
    double? temperature,
    int? recordedBy,
    String? session,
    String? notes,
    @Default(false) bool synced,
    required DateTime createdAt,
  }) = _MilkRecord;

  factory MilkRecord.fromJson(Map<String, dynamic> json) =>
      _$MilkRecordFromJson(json);
}

// lib/models/health_record.dart

@freezed
class HealthRecord with _$HealthRecord {
  const factory HealthRecord({
    required int id,
    required int animalId,
    required DateTime date,
    required String type,
    String? diagnosis,
    String? treatment,
    List<Medication>? medications,
    String? vetName,
    double? cost,
    DateTime? followUpDate,
    String? notes,
    @Default(false) bool synced,
    required DateTime createdAt,
  }) = _HealthRecord;

  factory HealthRecord.fromJson(Map<String, dynamic> json) =>
      _$HealthRecordFromJson(json);
}

@freezed
class Medication with _$Medication {
  const factory Medication({
    required String name,
    required String dosage,
    required String frequency,
    required int durationDays,
  }) = _Medication;

  factory Medication.fromJson(Map<String, dynamic> json) =>
      _$MedicationFromJson(json);
}
```

---

## 6. DAO Pattern

### 6.1 Data Access Object Interface

```dart
// lib/database/dao/base_dao.dart

import 'package:drift/drift.dart';

abstract class BaseDao<T extends Table, D> {
  Future<D?> getById(int id);
  Future<List<D>> getAll();
  Future<int> insert(Insertable<T> entity);
  Future<bool> update(Insertable<T> entity);
  Future<int> delete(int id);
  Future<void> deleteAll();
}
```

### 6.2 Animal DAO

```dart
// lib/database/dao/animal_dao.dart

import 'package:drift/drift.dart';
import '../app_database.dart';
import '../tables.dart';

part 'animal_dao.g.dart';

@DriftAccessor(tables: [Animals])
class AnimalDao extends DatabaseAccessor<AppDatabase> with _$AnimalDaoMixin {
  AnimalDao(AppDatabase db) : super(db);

  // ============ READ OPERATIONS ============
  
  Future<Animal?> getById(int id) {
    return (select(animals)..where((a) => a.id.equals(id))).getSingleOrNull();
  }

  Future<Animal?> getByTag(String tag) {
    return (select(animals)..where((a) => a.tag.equals(tag))).getSingleOrNull();
  }

  Future<List<Animal>> getAll() => select(animals).get();

  Future<List<Animal>> getActive() {
    return (select(animals)..where((a) => a.status.equals('active'))).get();
  }

  Future<List<Animal>> getByBreed(String breed) {
    return (select(animals)..where((a) => a.breed.equals(breed))).get();
  }

  Future<List<Animal>> searchByName(String query) {
    return (select(animals)
      ..where((a) => a.name.like('%$query%'))
      ..orderBy([(a) => OrderingTerm(expression: a.name)]))
      .get();
  }

  Stream<List<Animal>> watchAll() => select(animals).watch();

  Stream<List<Animal>> watchActive() {
    return (select(animals)..where((a) => a.status.equals('active'))).watch();
  }

  // ============ WRITE OPERATIONS ============

  Future<int> insert(AnimalsCompanion animal) async {
    final id = await into(animals).insert(animal);
    await _addToSyncQueue('CREATE', id, 'animals');
    return id;
  }

  Future<bool> updateAnimal(AnimalsCompanion animal) async {
    final success = await update(animals).replace(animal);
    if (success && animal.id.present) {
      await _addToSyncQueue('UPDATE', animal.id.value, 'animals');
    }
    return success;
  }

  Future<int> deleteAnimal(int id) async {
    final count = await (delete(animals)..where((a) => a.id.equals(id))).go();
    if (count > 0) {
      await _addToSyncQueue('DELETE', id, 'animals');
    }
    return count;
  }

  // Batch operations
  Future<void> insertMany(List<AnimalsCompanion> items) async {
    await batch((batch) {
      batch.insertAll(animals, items);
    });
  }

  // ============ SYNC OPERATIONS ============

  Future<List<Animal>> getUnsynced() {
    return (select(animals)..where((a) => a.synced.equals(false))).get();
  }

  Future<void> markAsSynced(int id) async {
    await (update(animals)..where((a) => a.id.equals(id)))
        .write(const AnimalsCompanion(synced: Value(true)));
  }

  Future<void> markAllAsSynced() async {
    await (update(animals))
        .write(const AnimalsCompanion(synced: Value(true)));
  }

  // ============ ANALYTICS ============

  Future<int> getTotalCount() async {
    final query = selectOnly(animals)..addColumns([animals.id.count()]);
    final row = await query.getSingle();
    return row.read(animals.id.count()) ?? 0;
  }

  Future<Map<String, int>> getCountByBreed() async {
    final query = selectOnly(animals)
      ..addColumns([animals.breed, animals.id.count()])
      ..groupBy([animals.breed]);

    final rows = await query.get();
    return {
      for (var row in rows)
        row.read(animals.breed)!: row.read(animals.id.count()) ?? 0
    };
  }

  // Private helper
  Future<void> _addToSyncQueue(String operation, int recordId, String table) async {
    await into(syncQueue).insert(SyncQueueCompanion(
      operation: Value(operation),
      tableName: Value(table),
      recordId: Value(recordId),
      priority: const Value(3),
    ));
  }
}
```

### 6.3 Milk Record DAO

```dart
// lib/database/dao/milk_record_dao.dart

import 'package:drift/drift.dart';
import '../app_database.dart';
import '../tables.dart';

part 'milk_record_dao.g.dart';

@DriftAccessor(tables: [MilkRecords, Animals])
class MilkRecordDao extends DatabaseAccessor<AppDatabase> 
    with _$MilkRecordDaoMixin {
  MilkRecordDao(AppDatabase db) : super(db);

  // ============ READ OPERATIONS ============

  Future<MilkRecord?> getById(int id) {
    return (select(milkRecords)..where((m) => m.id.equals(id)))
        .getSingleOrNull();
  }

  Future<List<MilkRecord>> getAll() => select(milkRecords).get();

  Future<List<MilkRecord>> getByAnimal(int animalId) {
    return (select(milkRecords)
      ..where((m) => m.animalId.equals(animalId))
      ..orderBy([(m) => OrderingTerm.desc(m.date)]))
      .get();
  }

  Future<List<MilkRecord>> getByDateRange(DateTime start, DateTime end) {
    return (select(milkRecords)
      ..where((m) => m.date.isBetweenValues(start, end))
      ..orderBy([(m) => OrderingTerm.desc(m.date)]))
      .get();
  }

  Stream<List<MilkRecord>> watchByAnimal(int animalId) {
    return (select(milkRecords)
      ..where((m) => m.animalId.equals(animalId))
      ..orderBy([(m) => OrderingTerm.desc(m.date)]))
      .watch();
  }

  // ============ AGGREGATIONS ============

  Future<double> getTotalMilkByDateRange(DateTime start, DateTime end) async {
    final query = selectOnly(milkRecords)
      ..addColumns([milkRecords.amount.sum()])
      ..where(milkRecords.date.isBetweenValues(start, end));

    final row = await query.getSingle();
    return row.read(milkRecords.amount.sum()) ?? 0.0;
  }

  Future<double> getAverageMilkByAnimal(int animalId) async {
    final query = selectOnly(milkRecords)
      ..addColumns([milkRecords.amount.avg()])
      ..where(milkRecords.animalId.equals(animalId));

    final row = await query.getSingle();
    return row.read(milkRecords.amount.avg()) ?? 0.0;
  }

  Future<Map<DateTime, double>> getDailyTotals(DateTime start, DateTime end) async {
    final query = selectOnly(milkRecords)
      ..addColumns([milkRecords.date.date, milkRecords.amount.sum()])
      ..where(milkRecords.date.isBetweenValues(start, end))
      ..groupBy([milkRecords.date.date]);

    final rows = await query.get();
    return {
      for (var row in rows)
        row.read(milkRecords.date.date)!: row.read(milkRecords.amount.sum()) ?? 0.0
    };
  }

  // ============ WRITE OPERATIONS ============

  Future<int> insert(MilkRecordsCompanion record) async {
    final id = await into(milkRecords).insert(record);
    await _addToSyncQueue('CREATE', id, 'milk_records');
    return id;
  }

  Future<bool> updateRecord(MilkRecordsCompanion record) async {
    final success = await update(milkRecords).replace(record);
    if (success && record.id.present) {
      await _addToSyncQueue('UPDATE', record.id.value, 'milk_records');
    }
    return success;
  }

  Future<int> deleteRecord(int id) async {
    final count = await (delete(milkRecords)..where((m) => m.id.equals(id))).go();
    if (count > 0) {
      await _addToSyncQueue('DELETE', id, 'milk_records');
    }
    return count;
  }

  // ============ SYNC OPERATIONS ============

  Future<List<MilkRecord>> getUnsynced() {
    return (select(milkRecords)..where((m) => m.synced.equals(false))).get();
  }

  Future<void> markAsSynced(int id) async {
    await (update(milkRecords)..where((m) => m.id.equals(id)))
        .write(const MilkRecordsCompanion(synced: Value(true)));
  }

  Future<void> _addToSyncQueue(String operation, int recordId, String table) async {
    await into(syncQueue).insert(SyncQueueCompanion(
      operation: Value(operation),
      tableName: Value(table),
      recordId: Value(recordId),
      priority: const Value(2), // Higher priority for milk records
    ));
  }
}
```

### 6.4 Sync Queue DAO

```dart
// lib/database/dao/sync_queue_dao.dart

import 'package:drift/drift.dart';
import '../app_database.dart';
import '../tables.dart';

part 'sync_queue_dao.g.dart';

@DriftAccessor(tables: [SyncQueue])
class SyncQueueDao extends DatabaseAccessor<AppDatabase> 
    with _$SyncQueueDaoMixin {
  SyncQueueDao(AppDatabase db) : super(db);

  Future<List<SyncQueueData>> getPending() {
    return (select(syncQueue)
      ..orderBy([
        (s) => OrderingTerm.asc(s.priority),
        (s) => OrderingTerm.asc(s.createdAt),
      ]))
      .get();
  }

  Future<List<SyncQueueData>> getPendingByPriority(int maxPriority) {
    return (select(syncQueue)
      ..where((s) => s.priority.isSmallerOrEqualValue(maxPriority))
      ..orderBy([
        (s) => OrderingTerm.asc(s.priority),
        (s) => OrderingTerm.asc(s.createdAt),
      ]))
      .get();
  }

  Future<SyncQueueData?> getNextPending() async {
    final items = await (select(syncQueue)
      ..orderBy([
        (s) => OrderingTerm.asc(s.priority),
        (s) => OrderingTerm.asc(s.createdAt),
      ])
      ..limit(1))
      .get();
    return items.isNotEmpty ? items.first : null;
  }

  Future<int> add(SyncQueueCompanion item) => into(syncQueue).insert(item);

  Future<void> markAsCompleted(int id) async {
    await (delete(syncQueue)..where((s) => s.id.equals(id))).go();
  }

  Future<void> markAsFailed(int id, String error) async {
    await (update(syncQueue)..where((s) => s.id.equals(id)))
        .write(SyncQueueCompanion(
      attempts: const Value(1),
      lastAttempt: Value(DateTime.now()),
      errorMessage: Value(error),
    ));
  }

  Future<void> incrementAttempts(int id) async {
    final item = await (select(syncQueue)..where((s) => s.id.equals(id)))
        .getSingle();
    await (update(syncQueue)..where((s) => s.id.equals(id)))
        .write(SyncQueueCompanion(
      attempts: Value(item.attempts + 1),
      lastAttempt: Value(DateTime.now()),
    ));
  }

  Future<void> removeOldCompleted(Duration age) async {
    final cutoff = DateTime.now().subtract(age);
    await (delete(syncQueue)
      ..where((s) => s.createdAt.isSmallerThanValue(cutoff)))
      .go();
  }

  Future<int> getPendingCount() async {
    final query = selectOnly(syncQueue)..addColumns([syncQueue.id.count()]);
    final row = await query.getSingle();
    return row.read(syncQueue.id.count()) ?? 0;
  }

  Future<void> clearAll() async {
    await delete(syncQueue).go();
  }
}
```

---

## 7. Database Migrations

### 7.1 Migration Strategy

```dart
// lib/database/migrations/migration_strategy.dart

import 'package:drift/drift.dart';
import '../app_database.dart';

class DatabaseMigrationStrategy {
  static const int latestVersion = 3;

  static MigrationStrategy get strategy => MigrationStrategy(
    onCreate: (Migrator m) async {
      await m.createAll();
      await _createIndexes(m);
      await _createTriggers(m);
      await _seedInitialData(m);
    },
    onUpgrade: _migrate,
    beforeOpen: (details) async {
      // Enable foreign keys
      await customStatement('PRAGMA foreign_keys = ON');
      
      // Enable WAL mode for better concurrency
      await customStatement('PRAGMA journal_mode = WAL');
      
      // Set synchronous mode for performance/safety balance
      await customStatement('PRAGMA synchronous = NORMAL');
      
      // Verify database integrity
      if (details.hadUpgrade || details.wasCreated) {
        final result = await customSelect(
          'PRAGMA integrity_check',
        ).getSingle();
        final check = result.data['integrity_check'];
        if (check != 'ok') {
          throw Exception('Database integrity check failed: $check');
        }
      }
    },
  );

  static Future<void> _migrate(Migrator m, int from, int to) async {
    await customStatement('PRAGMA foreign_keys = OFF');
    
    try {
      if (from < 2) await _migrateToV2(m);
      if (from < 3) await _migrateToV3(m);
    } finally {
      await customStatement('PRAGMA foreign_keys = ON');
    }
  }

  // Migration to Version 2: Add weight column to animals
  static Future<void> _migrateToV2(Migrator m) async {
    await m.addColumn(animals, animals.weight);
    
    // Add index on weight for performance
    await m.createIndex(Index('idx_animals_weight', 
      'CREATE INDEX idx_animals_weight ON animals(weight)'));
  }

  // Migration to Version 3: Add health records table
  static Future<void> _migrateToV3(Migrator m) async {
    await m.createTable(healthRecords);
    
    // Add indexes for health records
    await m.createIndex(Index('idx_health_animal_date',
      'CREATE INDEX idx_health_animal_date ON health_records(animal_id, date)'));
    await m.createIndex(Index('idx_health_type',
      'CREATE INDEX idx_health_type ON health_records(type)'));
    await m.createIndex(Index('idx_health_followup',
      'CREATE INDEX idx_health_followup ON health_records(follow_up_date)'));
  }

  static Future<void> _createIndexes(Migrator m) async {
    // Animals indexes
    await m.createIndex(Index('idx_animals_tag', 
      'CREATE UNIQUE INDEX idx_animals_tag ON animals(tag)'));
    await m.createIndex(Index('idx_animals_status', 
      'CREATE INDEX idx_animals_status ON animals(status)'));
    await m.createIndex(Index('idx_animals_breed', 
      'CREATE INDEX idx_animals_breed ON animals(breed)'));
    await m.createIndex(Index('idx_animals_owner', 
      'CREATE INDEX idx_animals_owner ON animals(owner_id)'));

    // Milk records indexes
    await m.createIndex(Index('idx_milk_animal', 
      'CREATE INDEX idx_milk_animal ON milk_records(animal_id)'));
    await m.createIndex(Index('idx_milk_date', 
      'CREATE INDEX idx_milk_date ON milk_records(date)'));
    await m.createIndex(Index('idx_milk_animal_date', 
      'CREATE INDEX idx_milk_animal_date ON milk_records(animal_id, date)'));
    await m.createIndex(Index('idx_milk_synced', 
      'CREATE INDEX idx_milk_synced ON milk_records(synced)'));

    // Health records indexes
    await m.createIndex(Index('idx_health_animal', 
      'CREATE INDEX idx_health_animal ON health_records(animal_id)'));
    await m.createIndex(Index('idx_health_date', 
      'CREATE INDEX idx_health_date ON health_records(date)'));

    // Orders indexes
    await m.createIndex(Index('idx_orders_status', 
      'CREATE INDEX idx_orders_status ON orders(status)'));
    await m.createIndex(Index('idx_orders_sync', 
      'CREATE INDEX idx_orders_sync ON orders(sync_status)'));

    // Sync queue indexes
    await m.createIndex(Index('idx_sync_priority', 
      'CREATE INDEX idx_sync_priority ON sync_queue(priority, created_at)'));
  }

  static Future<void> _createTriggers(Migrator m) async {
    // Update trigger for animals.updated_at
    await customStatement('''
      CREATE TRIGGER IF NOT EXISTS trg_animals_updated_at
      AFTER UPDATE ON animals
      BEGIN
        UPDATE animals SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
      END
    ''');

    // Update trigger for orders.updated_at
    await customStatement('''
      CREATE TRIGGER IF NOT EXISTS trg_orders_updated_at
      AFTER UPDATE ON orders
      BEGIN
        UPDATE orders SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
      END
    ''');

    // Update trigger for users.updated_at
    await customStatement('''
      CREATE TRIGGER IF NOT EXISTS trg_users_updated_at
      AFTER UPDATE ON users
      BEGIN
        UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
      END
    ''');
  }

  static Future<void> _seedInitialData(Migrator m) async {
    // Seed admin user
    await into(users).insert(UsersCompanion(
      name: const Value('System Admin'),
      email: const Value('admin@smartdairy.com'),
      role: const Value('admin'),
      isActive: const Value(true),
    ));
  }
}
```

---

## 8. Query Patterns

### 8.1 SELECT Patterns

```dart
// lib/database/queries/select_patterns.dart

class SelectPatterns {
  final AppDatabase _db;

  SelectPatterns(this._db);

  // ===== BASIC SELECT =====

  // Select all columns
  Future<List<Animal>> selectAllAnimals() async {
    return await _db.select(_db.animals).get();
  }

  // Select specific columns
  Future<List<Map<String, dynamic>>> selectAnimalNames() async {
    final query = _db.selectOnly(_db.animals)
      ..addColumns([_db.animals.id, _db.animals.name, _db.animals.tag]);
    
    return await query.map((row) => {
      'id': row.read(_db.animals.id),
      'name': row.read(_db.animals.name),
      'tag': row.read(_db.animals.tag),
    }).get();
  }

  // ===== FILTERING =====

  // WHERE with equals
  Future<Animal?> findByTag(String tag) async {
    return await (_db.select(_db.animals)
      ..where((a) => a.tag.equals(tag)))
      .getSingleOrNull();
  }

  // WHERE with multiple conditions
  Future<List<Animal>> findActiveByBreed(String breed) async {
    return await (_db.select(_db.animals)
      ..where((a) => a.breed.equals(breed) & a.status.equals('active')))
      .get();
  }

  // WHERE with OR
  Future<List<Animal>> findByBreedOrStatus(String breed, String status) async {
    return await (_db.select(_db.animals)
      ..where((a) => a.breed.equals(breed) | a.status.equals(status)))
      .get();
  }

  // WHERE with IN
  Future<List<Animal>> findByBreeds(List<String> breeds) async {
    return await (_db.select(_db.animals)
      ..where((a) => a.breed.isIn(breeds)))
      .get();
  }

  // WHERE with LIKE (pattern matching)
  Future<List<Animal>> searchByName(String query) async {
    return await (_db.select(_db.animals)
      ..where((a) => a.name.like('%$query%')))
      .get();
  }

  // WHERE with date range
  Future<List<MilkRecord>> getRecordsByDateRange(
    DateTime start,
    DateTime end,
  ) async {
    return await (_db.select(_db.milkRecords)
      ..where((m) => m.date.isBetweenValues(start, end)))
      .get();
  }

  // ===== SORTING =====

  // ORDER BY single column
  Future<List<Animal>> getAnimalsSortedByName() async {
    return await (_db.select(_db.animals)
      ..orderBy([(a) => OrderingTerm.asc(a.name)]))
      .get();
  }

  // ORDER BY multiple columns
  Future<List<Animal>> getAnimalsSortedByBreedThenName() async {
    return await (_db.select(_db.animals)
      ..orderBy([
        (a) => OrderingTerm.asc(a.breed),
        (a) => OrderingTerm.asc(a.name),
      ]))
      .get();
  }

  // ===== PAGINATION =====

  // LIMIT and OFFSET
  Future<List<Animal>> getAnimalsPage(int page, int pageSize) async {
    return await (_db.select(_db.animals)
      ..limit(pageSize, offset: page * pageSize))
      .get();
  }

  // LIMIT with custom offset
  Future<List<MilkRecord>> getRecentRecords(int limit) async {
    return await (_db.select(_db.milkRecords)
      ..orderBy([(m) => OrderingTerm.desc(m.date)])
      ..limit(limit))
      .get();
  }
}
```

### 8.2 INSERT Patterns

```dart
// lib/database/queries/insert_patterns.dart

class InsertPatterns {
  final AppDatabase _db;

  InsertPatterns(this._db);

  // Insert single record
  Future<int> insertAnimal(Animal animal) async {
    return await _db.into(_db.animals).insert(
      AnimalsCompanion(
        tag: Value(animal.tag),
        name: Value(animal.name),
        breed: Value(animal.breed),
        birthDate: Value(animal.birthDate),
        gender: Value(animal.gender),
      ),
    );
  }

  // Insert with ON CONFLICT handling
  Future<int> insertOrUpdateAnimal(AnimalsCompanion animal) async {
    return await _db.into(_db.animals).insert(
      animal,
      onConflict: DoUpdate((old) => animal),
    );
  }

  // Insert or ignore (skip if exists)
  Future<int> insertAnimalIfNotExists(AnimalsCompanion animal) async {
    return await _db.into(_db.animals).insert(
      animal,
      mode: InsertMode.insertOrIgnore,
    );
  }

  // Insert multiple records in a batch
  Future<void> insertManyAnimals(List<AnimalsCompanion> animals) async {
    await _db.batch((batch) {
      batch.insertAll(_db.animals, animals);
    });
  }
}
```

### 8.3 UPDATE Patterns

```dart
// lib/database/queries/update_patterns.dart

class UpdatePatterns {
  final AppDatabase _db;

  UpdatePatterns(this._db);

  // Update single record
  Future<bool> updateAnimal(Animal animal) async {
    return await _db.update(_db.animals).replace(
      AnimalsCompanion(
        id: Value(animal.id),
        name: Value(animal.name),
        weight: Value(animal.weight),
        updatedAt: Value(DateTime.now()),
      ),
    );
  }

  // Update specific columns
  Future<int> updateAnimalWeight(int id, double weight) async {
    return await (_db.update(_db.animals)
      ..where((a) => a.id.equals(id)))
      .write(AnimalsCompanion(weight: Value(weight)));
  }

  // Update multiple records matching condition
  Future<int> markAllAsSynced() async {
    return await _db.update(_db.animals).write(
      const AnimalsCompanion(synced: Value(true)),
    );
  }

  // Insert or update
  Future<int> upsertAnimal(AnimalsCompanion animal) async {
    return await _db.into(_db.animals).insert(
      animal,
      onConflict: DoUpdate((old) => animal),
    );
  }
}
```

### 8.4 DELETE Patterns

```dart
// lib/database/queries/delete_patterns.dart

class DeletePatterns {
  final AppDatabase _db;

  DeletePatterns(this._db);

  // Delete single record by ID
  Future<int> deleteAnimal(int id) async {
    return await (_db.delete(_db.animals)
      ..where((a) => a.id.equals(id)))
      .go();
  }

  // Delete by condition
  Future<int> deleteOldRecords(DateTime before) async {
    return await (_db.delete(_db.milkRecords)
      ..where((m) => m.date.isSmallerThanValue(before)))
      .go();
  }

  // Delete with related records (manual cascade)
  Future<void> deleteAnimalWithRecords(int animalId) async {
    await _db.transaction(() async {
      // Delete related milk records
      await (_db.delete(_db.milkRecords)
        ..where((m) => m.animalId.equals(animalId)))
        .go();

      // Delete related health records
      await (_db.delete(_db.healthRecords)
        ..where((h) => h.animalId.equals(animalId)))
        .go();

      // Delete the animal
      await (_db.delete(_db.animals)
        ..where((a) => a.id.equals(animalId)))
        .go();
    });
  }

  // Soft delete
  Future<int> softDeleteAnimal(int id) async {
    return await (_db.update(_db.animals)
      ..where((a) => a.id.equals(id)))
      .write(const AnimalsCompanion(status: Value('deleted')));
  }

  // Delete all records from table
  Future<int> clearAllAnimals() async {
    return await _db.delete(_db.animals).go();
  }
}
```

---

## 9. Relationships

### 9.1 Foreign Key Relationships

```dart
// lib/database/relationships.dart

class RelationshipQueries {
  final AppDatabase _db;

  RelationshipQueries(this._db);

  // ===== ONE-TO-MANY: Animal -> MilkRecords =====

  // Get animal with all milk records
  Future<AnimalWithRecords> getAnimalWithMilkRecords(int animalId) async {
    final animal = await (_db.select(_db.animals)
      ..where((a) => a.id.equals(animalId)))
      .getSingle();

    final records = await (_db.select(_db.milkRecords)
      ..where((m) => m.animalId.equals(animalId))
      ..orderBy([(m) => OrderingTerm.desc(m.date)]))
      .get();

    return AnimalWithRecords(animal: animal, records: records);
  }

  // Stream of animal with records (reactive)
  Stream<AnimalWithRecords> watchAnimalWithMilkRecords(int animalId) {
    final animalStream = (_db.select(_db.animals)
      ..where((a) => a.id.equals(animalId)))
      .watchSingle();

    final recordsStream = (_db.select(_db.milkRecords)
      ..where((m) => m.animalId.equals(animalId))
      ..orderBy([(m) => OrderingTerm.desc(m.date)]))
      .watch();

    return Rx.combineLatest2(
      animalStream,
      recordsStream,
      (Animal animal, List<MilkRecord> records) =>
          AnimalWithRecords(animal: animal, records: records),
    );
  }

  // ===== JOINS =====

  // INNER JOIN: Animals with their owners
  Future<List<AnimalWithOwner>> getAnimalsWithOwners() async {
    final query = _db.select(_db.animals).join([
      innerJoin(
        _db.users,
        _db.users.id.equalsExp(_db.animals.ownerId),
      ),
    ]);

    return await query.map((row) {
      return AnimalWithOwner(
        animal: row.readTable(_db.animals),
        owner: row.readTable(_db.users),
      );
    }).get();
  }

  // JOIN with aggregation: Animals with milk totals
  Future<List<AnimalWithMilkTotal>> getAnimalsWithMilkTotals(
    DateTime start,
    DateTime end,
  ) async {
    final totalAmount = _db.milkRecords.amount.sum();
    final recordCount = _db.milkRecords.id.count();

    final query = _db.select(_db.animals).join([
      leftOuterJoin(
        _db.milkRecords,
        _db.milkRecords.animalId.equalsExp(_db.animals.id),
      ),
    ])
      ..where(_db.milkRecords.date.isBetweenValues(start, end) |
          _db.milkRecords.id.isNull())
      ..groupBy([_db.animals.id]);

    query.addColumns([totalAmount, recordCount]);

    return await query.map((row) {
      return AnimalWithMilkTotal(
        animal: row.readTable(_db.animals),
        totalMilk: row.read(totalAmount) ?? 0.0,
        recordCount: row.read(recordCount) ?? 0,
      );
    }).get();
  }
}

// Data classes for relationships
class AnimalWithRecords {
  final Animal animal;
  final List<MilkRecord> records;

  AnimalWithRecords({required this.animal, required this.records});
}

class AnimalWithOwner {
  final Animal animal;
  final User owner;

  AnimalWithOwner({required this.animal, required this.owner});
}

class AnimalWithMilkTotal {
  final Animal animal;
  final double totalMilk;
  final int recordCount;

  AnimalWithMilkTotal({
    required this.animal,
    required this.totalMilk,
    required this.recordCount,
  });
}
```

---

## 10. Encryption

### 10.1 SQLCipher Setup

```dart
// lib/database/encryption/encrypted_database.dart

import 'package:drift/drift.dart';
import 'package:drift_flutter/drift_flutter.dart';
import 'package:sqlcipher_flutter_libs/sqlcipher_flutter_libs.dart';
import 'package:path_provider/path_provider.dart';
import 'package:path/path.dart' as p;

class EncryptedDatabase {
  static QueryExecutor openEncryptedConnection(String password) {
    return driftDatabase(
      name: 'smart_dairy_encrypted',
      native: DriftNativeOptions(
        databasePath: () async {
          final dbFolder = await getApplicationDocumentsDirectory();
          return p.join(dbFolder.path, 'smart_dairy_encrypted.db');
        },
        setup: (rawDb) async {
          // Apply SQLCipher encryption
          await rawConfig(rawDb);
        },
      ),
    );
  }
}

// Encrypted database class
@DriftDatabase(tables: [Animals, MilkRecords, HealthRecords, Users, Orders])
class EncryptedAppDatabase extends _$EncryptedAppDatabase {
  EncryptedAppDatabase(String password) 
      : super(EncryptedDatabase.openEncryptedConnection(password));

  @override
  int get schemaVersion => 1;
}
```

### 10.2 Encryption Key Management

```dart
// lib/database/encryption/key_manager.dart

import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:crypto/crypto.dart';
import 'dart:convert';
import 'dart:math';
import 'dart:typed_data';

class DatabaseKeyManager {
  static const _storage = FlutterSecureStorage(
    aOptions: AndroidOptions(
      encryptedSharedPreferences: true,
    ),
    iOptions: IOSOptions(
      accessibility: KeychainAccessibility.first_unlock_this_device,
    ),
  );

  static const _dbKeyName = 'database_encryption_key';
  static const _passwordSaltKey = 'database_password_salt';

  /// Generates a new random encryption key
  static String generateKey() {
    final random = Random.secure();
    final keyBytes = Uint8List(32);
    for (int i = 0; i < 32; i++) {
      keyBytes[i] = random.nextInt(256);
    }
    return base64Encode(keyBytes);
  }

  /// Generates a salt for password hashing
  static String generateSalt() {
    final random = Random.secure();
    final saltBytes = Uint8List(16);
    for (int i = 0; i < 16; i++) {
      saltBytes[i] = random.nextInt(256);
    }
    return base64Encode(saltBytes);
  }

  /// Derives encryption key from user password
  static String deriveKeyFromPassword(String password, String salt) {
    final bytes = utf8.encode(password + salt);
    final hash = sha256.convert(bytes);
    return base64Encode(hash.bytes);
  }

  /// Stores encryption key securely
  static Future<void> storeKey(String key) async {
    await _storage.write(key: _dbKeyName, value: key);
  }

  /// Retrieves encryption key
  static Future<String?> getKey() async {
    return await _storage.read(key: _dbKeyName);
  }

  /// Deletes encryption key
  static Future<void> deleteKey() async {
    await _storage.delete(key: _dbKeyName);
  }

  /// Initialize encryption for first use
  static Future<String> initializeEncryption() async {
    final existingKey = await getKey();
    if (existingKey != null) {
      return existingKey;
    }

    final newKey = generateKey();
    await storeKey(newKey);
    return newKey;
  }
}
```

### 10.3 Encryption Service

```dart
// lib/database/encryption/encryption_service.dart

import 'dart:convert';
import 'dart:typed_data';
import 'package:encrypt/encrypt.dart' as encrypt;

class EncryptionService {
  late final encrypt.Key _key;
  late final encrypt.Encrypter _encrypter;

  EncryptionService(String base64Key) {
    _key = encrypt.Key.fromBase64(base64Key);
    _encrypter = encrypt.Encrypter(
      encrypt.AES(_key, mode: encrypt.AESMode.gcm),
    );
  }

  /// Encrypts plain text
  String encryptText(String plainText) {
    final iv = encrypt.IV.fromSecureRandom(12);
    final encrypted = _encrypter.encrypt(plainText, iv: iv);
    return base64Encode(iv.bytes + encrypted.bytes);
  }

  /// Decrypts encrypted text
  String decryptText(String encryptedData) {
    final bytes = base64Decode(encryptedData);
    final iv = encrypt.IV(Uint8List.fromList(bytes.sublist(0, 12)));
    final encrypted = encrypt.Encrypted(bytes.sublist(12));
    return _encrypter.decrypt(encrypted, iv: iv);
  }

  /// Encrypts JSON object
  String encryptJson(Map<String, dynamic> json) {
    return encryptText(jsonEncode(json));
  }

  /// Decrypts to JSON object
  Map<String, dynamic> decryptJson(String encryptedData) {
    return jsonDecode(decryptText(encryptedData));
  }
}
```

---

## 11. Backup & Restore

### 11.1 Backup Service

```dart
// lib/database/backup/backup_service.dart

import 'dart:io';
import 'package:path_provider/path_provider.dart';
import 'package:path/path.dart' as p;
import 'package:archive/archive.dart';
import 'package:share_plus/share_plus.dart';

class DatabaseBackupService {
  final AppDatabase _db;
  
  DatabaseBackupService(this._db);

  /// Creates a backup of the database
  Future<BackupResult> createBackup({bool includeMedia = false}) async {
    try {
      // Get database file path
      final dbPath = await AppDatabase.getDatabasePath();
      final dbFile = File(dbPath);
      
      if (!await dbFile.exists()) {
        return BackupResult(success: false, error: 'Database file not found');
      }

      // Create backup directory
      final backupDir = await _getBackupDirectory();
      final timestamp = DateTime.now().toIso8601String().replaceAll(':', '-');
      final backupName = 'smart_dairy_backup_$timestamp';
      final backupPath = p.join(backupDir.path, '$backupName.zip');

      // Create archive
      final archive = Archive();

      // Add database file
      final dbBytes = await dbFile.readAsBytes();
      archive.addFile(ArchiveFile('database.db', dbBytes.length, dbBytes));

      // Add schema version info
      final versionInfo = jsonEncode({
        'version': _db.schemaVersion,
        'timestamp': DateTime.now().toIso8601String(),
        'appVersion': await _getAppVersion(),
      });
      final versionBytes = utf8.encode(versionInfo);
      archive.addFile(ArchiveFile('version.json', versionBytes.length, versionBytes));

      // Write archive
      final zipEncoder = ZipEncoder();
      final zipData = zipEncoder.encode(archive);
      await File(backupPath).writeAsBytes(zipData!);

      return BackupResult(
        success: true,
        filePath: backupPath,
        fileSize: zipData.length,
        timestamp: DateTime.now(),
      );
    } catch (e) {
      return BackupResult(success: false, error: e.toString());
    }
  }

  /// Restores database from backup
  Future<RestoreResult> restoreBackup(String backupPath) async {
    try {
      final backupFile = File(backupPath);
      if (!await backupFile.exists()) {
        return RestoreResult(success: false, error: 'Backup file not found');
      }

      // Read and decode archive
      final bytes = await backupFile.readAsBytes();
      final archive = ZipDecoder().decodeBytes(bytes);

      // Verify version info
      final versionFile = archive.findFile('version.json');
      if (versionFile == null) {
        return RestoreResult(success: false, error: 'Invalid backup file');
      }

      final versionInfo = jsonDecode(utf8.decode(versionFile.content));
      final backupVersion = versionInfo['version'] as int;

      if (backupVersion > _db.schemaVersion) {
        return RestoreResult(
          success: false,
          error: 'Backup version is newer than app version',
        );
      }

      // Close current database connection
      await _db.close();

      // Restore database file
      final dbFile = archive.findFile('database.db');
      if (dbFile != null) {
        final dbPath = await AppDatabase.getDatabasePath();
        final file = File(dbPath);
        
        // Create backup of current database before restore
        final currentBackup = '$dbPath.bak';
        if (await file.exists()) {
          await file.copy(currentBackup);
        }

        await file.writeAsBytes(dbFile.content);
      }

      return RestoreResult(
        success: true,
        timestamp: DateTime.now(),
        schemaVersion: backupVersion,
      );
    } catch (e) {
      return RestoreResult(success: false, error: e.toString());
    }
  }

  /// Shares backup file
  Future<void> shareBackup(String filePath) async {
    await Share.shareXFiles([XFile(filePath)],
        subject: 'Smart Dairy Database Backup');
  }

  Future<Directory> _getBackupDirectory() async {
    final appDir = await getApplicationDocumentsDirectory();
    final backupDir = Directory(p.join(appDir.path, 'backups'));
    await backupDir.create(recursive: true);
    return backupDir;
  }

  Future<String> _getAppVersion() async {
    return '1.0.0';
  }
}

class BackupResult {
  final bool success;
  final String? filePath;
  final int? fileSize;
  final DateTime? timestamp;
  final String? error;

  BackupResult({
    required this.success,
    this.filePath,
    this.fileSize,
    this.timestamp,
    this.error,
  });
}

class RestoreResult {
  final bool success;
  final DateTime? timestamp;
  final int? schemaVersion;
  final String? error;

  RestoreResult({
    required this.success,
    this.timestamp,
    this.schemaVersion,
    this.error,
  });
}
```

### 11.2 Export/Import Procedures

```dart
// lib/database/backup/export_import.dart

import 'dart:convert';
import 'dart:io';
import 'package:csv/csv.dart';

class DataExportImportService {
  final AppDatabase _db;

  DataExportImportService(this._db);

  // ===== CSV EXPORT =====

  /// Exports animals to CSV
  Future<String> exportAnimalsToCsv() async {
    final animals = await _db.select(_db.animals).get();
    
    final rows = <List<dynamic>>[
      ['ID', 'Tag', 'Name', 'Breed', 'Birth Date', 'Gender', 'Status', 'Weight'],
      ...animals.map((a) => [
        a.id,
        a.tag,
        a.name,
        a.breed,
        a.birthDate?.toIso8601String(),
        a.gender,
        a.status,
        a.weight,
      ]),
    ];

    return const ListToCsvConverter().convert(rows);
  }

  /// Exports milk records to CSV
  Future<String> exportMilkRecordsToCsv(int? animalId) async {
    var query = _db.select(_db.milkRecords);
    if (animalId != null) {
      query = query..where((m) => m.animalId.equals(animalId));
    }
    final records = await query.get();

    final rows = <List<dynamic>>[
      ['ID', 'Animal ID', 'Date', 'Amount', 'Quality', 'Session'],
      ...records.map((r) => [
        r.id,
        r.animalId,
        r.date.toIso8601String(),
        r.amount,
        r.quality,
        r.session,
      ]),
    ];

    return const ListToCsvConverter().convert(rows);
  }

  // ===== JSON EXPORT =====

  /// Exports complete data to JSON
  Future<String> exportToJson() async {
    final data = <String, dynamic>{
      'exportDate': DateTime.now().toIso8601String(),
      'schemaVersion': _db.schemaVersion,
      'animals': await _db.select(_db.animals).get(),
      'milkRecords': await _db.select(_db.milkRecords).get(),
      'healthRecords': await _db.select(_db.healthRecords).get(),
      'orders': await _db.select(_db.orders).get(),
    };

    return jsonEncode(data);
  }

  // ===== IMPORT =====

  /// Imports animals from CSV
  Future<ImportResult> importAnimalsFromCsv(String csvContent) async {
    final rows = const CsvToListConverter().convert(csvContent);
    if (rows.isEmpty) {
      return ImportResult(success: false, error: 'Empty CSV file');
    }

    final headers = rows.first.map((h) => h.toString().toLowerCase()).toList();
    final imported = <int>[];
    final errors = <String>[];

    await _db.transaction(() async {
      for (int i = 1; i < rows.length; i++) {
        try {
          final row = rows[i];
          final companion = AnimalsCompanion(
            tag: Value(row[headers.indexOf('tag')].toString()),
            name: Value(row[headers.indexOf('name')].toString()),
            breed: Value(row[headers.indexOf('breed')].toString()),
            gender: Value(row[headers.indexOf('gender')].toString()),
          );
          final id = await _db.into(_db.animals).insert(companion);
          imported.add(id);
        } catch (e) {
          errors.add('Row $i: ${e.toString()}');
        }
      }
    });

    return ImportResult(
      success: errors.isEmpty,
      importedCount: imported.length,
      errorCount: errors.length,
      errors: errors,
    );
  }
}

class ImportResult {
  final bool success;
  final int importedCount;
  final int errorCount;
  final List<String>? errors;
  final String? error;

  ImportResult({
    required this.success,
    this.importedCount = 0,
    this.errorCount = 0,
    this.errors,
    this.error,
  });
}
```

---

## 12. Performance Optimization

### 12.1 Indexing Strategy

```dart
// lib/database/performance/indexes.dart

class IndexManager {
  static const List<IndexDefinition> recommendedIndexes = [
    // Animals indexes
    IndexDefinition(
      name: 'idx_animals_tag',
      table: 'animals',
      columns: ['tag'],
      unique: true,
    ),
    IndexDefinition(
      name: 'idx_animals_status',
      table: 'animals',
      columns: ['status'],
      unique: false,
    ),
    IndexDefinition(
      name: 'idx_animals_breed',
      table: 'animals',
      columns: ['breed'],
      unique: false,
    ),

    // Milk records indexes
    IndexDefinition(
      name: 'idx_milk_animal_date',
      table: 'milk_records',
      columns: ['animal_id', 'date'],
      unique: false,
    ),
    IndexDefinition(
      name: 'idx_milk_date',
      table: 'milk_records',
      columns: ['date'],
      unique: false,
    ),

    // Health records indexes
    IndexDefinition(
      name: 'idx_health_animal_date',
      table: 'health_records',
      columns: ['animal_id', 'date'],
      unique: false,
    ),
    IndexDefinition(
      name: 'idx_health_type',
      table: 'health_records',
      columns: ['type'],
      unique: false,
    ),
  ];
}

class IndexDefinition {
  final String name;
  final String table;
  final List<String> columns;
  final bool unique;

  const IndexDefinition({
    required this.name,
    required this.table,
    required this.columns,
    required this.unique,
  });

  String get sql => 
      'CREATE ${unique ? 'UNIQUE ' : ''}INDEX $name ON $table (${columns.join(', ')})';
}
```

### 12.2 Batch Operations

```dart
// lib/database/performance/batch_operations.dart

class BatchOperations {
  final AppDatabase _db;

  BatchOperations(this._db);

  /// Efficient bulk insert with conflict handling
  Future<BulkInsertResult> bulkInsertAnimals(
    List<AnimalsCompanion> animals, {
    int batchSize = 500,
  }) async {
    final inserted = <int>[];
    final errors = <String>[];

    for (var i = 0; i < animals.length; i += batchSize) {
      final end = (i + batchSize < animals.length) ? i + batchSize : animals.length;
      final batch = animals.sublist(i, end);

      try {
        await _db.batch((b) {
          for (final animal in batch) {
            b.insert(
              _db.animals,
              animal,
              onConflict: DoUpdate((old) => animal),
            );
          }
        });
        inserted.addAll(batch.map((_) => 0));
      } catch (e) {
        errors.add('Batch ${i ~/ batchSize}: ${e.toString()}');
      }
    }

    return BulkInsertResult(
      successCount: inserted.length,
      errorCount: errors.length,
      errors: errors,
    );
  }

  /// Bulk update with optimized query
  Future<int> bulkUpdateStatus(List<int> ids, String newStatus) async {
    return await _db.transaction(() async {
      var updatedCount = 0;
      
      // Process in chunks to avoid SQLITE_MAX_VARIABLE_NUMBER limit
      const chunkSize = 900;
      for (var i = 0; i < ids.length; i += chunkSize) {
        final chunk = ids.sublist(
          i, 
          i + chunkSize > ids.length ? ids.length : i + chunkSize,
        );
        
        final count = await (_db.update(_db.animals)
          ..where((a) => a.id.isIn(chunk)))
          .write(AnimalsCompanion(status: Value(newStatus)));
        
        updatedCount += count;
      }
      
      return updatedCount;
    });
  }
}

class BulkInsertResult {
  final int successCount;
  final int errorCount;
  final List<String> errors;

  BulkInsertResult({
    required this.successCount,
    required this.errorCount,
    required this.errors,
  });
}
```

### 12.3 Query Optimization

```dart
// lib/database/performance/query_optimizer.dart

class QueryOptimizer {
  final AppDatabase _db;

  QueryOptimizer(this._db);

  /// Uses covering indexes for better performance
  Future<List<AnimalSummary>> getAnimalSummaries() async {
    final query = _db.selectOnly(_db.animals)
      ..addColumns([
        _db.animals.id,
        _db.animals.tag,
        _db.animals.name,
        _db.animals.status,
      ])
      ..orderBy([OrderingTerm.asc(_db.animals.tag)]);

    return await query.map((row) => AnimalSummary(
      id: row.read(_db.animals.id)!,
      tag: row.read(_db.animals.tag)!,
      name: row.read(_db.animals.name)!,
      status: row.read(_db.animals.status)!,
    )).get();
  }

  /// Pagination with keyset pagination for large datasets
  Future<List<MilkRecord>> getRecordsPaginated({
    required int pageSize,
    int? lastId,
    DateTime? lastDate,
  }) async {
    var query = _db.select(_db.milkRecords)
      ..orderBy([
        (m) => OrderingTerm.desc(m.date),
        (m) => OrderingTerm.desc(m.id),
      ])
      ..limit(pageSize);

    if (lastId != null && lastDate != null) {
      query = query
        ..where((m) => 
          m.date.isSmallerThanValue(lastDate) |
          (m.date.equals(lastDate) & m.id.isSmallerThanValue(lastId)));
    }

    return await query.get();
  }

  /// Pre-aggregated data for dashboards
  Future<DashboardStats> getDashboardStats() async {
    // Single query for multiple aggregations
    final animalStats = await _db.customSelect('''
      SELECT 
        COUNT(*) as total_animals,
        COUNT(CASE WHEN status = 'active' THEN 1 END) as active_animals
      FROM animals
    ''').getSingle();

    final milkStats = await _db.customSelect('''
      SELECT 
        COALESCE(SUM(amount), 0) as total_milk_today
      FROM milk_records
      WHERE date(date) = date('now')
    ''').getSingle();

    return DashboardStats(
      totalAnimals: animalStats.read<int>('total_animals')!,
      activeAnimals: animalStats.read<int>('active_animals')!,
      totalMilkToday: milkStats.read<double>('total_milk_today')!,
    );
  }
}

class AnimalSummary {
  final int id;
  final String tag;
  final String name;
  final String status;

  AnimalSummary({
    required this.id,
    required this.tag,
    required this.name,
    required this.status,
  });
}

class DashboardStats {
  final int totalAnimals;
  final int activeAnimals;
  final double totalMilkToday;

  DashboardStats({
    required this.totalAnimals,
    required this.activeAnimals,
    required this.totalMilkToday,
  });
}
```

---

## 13. Testing

### 13.1 In-Memory Database Setup

```dart
// test/database/test_database.dart

import 'package:drift/drift.dart';
import 'package:drift/native.dart';
import 'package:smart_dairy/database/app_database.dart';

class TestDatabase {
  static AppDatabase createInMemoryDatabase() {
    return AppDatabase.forTesting(
      DatabaseConnection(NativeDatabase.memory()),
    );
  }

  static Future<AppDatabase> createInMemoryDatabaseWithData() async {
    final db = createInMemoryDatabase();
    await _seedTestData(db);
    return db;
  }

  static Future<void> _seedTestData(AppDatabase db) async {
    // Seed test animals
    final animal1 = await db.into(db.animals).insert(AnimalsCompanion(
      tag: const Value('COW001'),
      name: const Value('Bella'),
      breed: const Value('Holstein'),
      gender: const Value('F'),
      status: const Value('active'),
    ));

    final animal2 = await db.into(db.animals).insert(AnimalsCompanion(
      tag: const Value('COW002'),
      name: const Value('Daisy'),
      breed: const Value('Jersey'),
      gender: const Value('F'),
      status: const Value('active'),
    ));

    // Seed test milk records
    await db.into(db.milkRecords).insert(MilkRecordsCompanion(
      animalId: Value(animal1),
      date: Value(DateTime(2026, 1, 15, 7, 0)),
      amount: const Value(25.5),
      quality: const Value('A'),
      session: const Value('Morning'),
    ));

    await db.into(db.milkRecords).insert(MilkRecordsCompanion(
      animalId: Value(animal1),
      date: Value(DateTime(2026, 1, 15, 16, 0)),
      amount: const Value(22.0),
      quality: const Value('A'),
      session: const Value('Afternoon'),
    ));

    await db.into(db.milkRecords).insert(MilkRecordsCompanion(
      animalId: Value(animal2),
      date: Value(DateTime(2026, 1, 15, 7, 0)),
      amount: const Value(18.5),
      quality: const Value('B'),
      session: const Value('Morning'),
    ));
  }
}
```

### 13.2 DAO Unit Tests

```dart
// test/database/dao/animal_dao_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:smart_dairy/database/app_database.dart';
import '../test_database.dart';

void main() {
  group('AnimalDao', () {
    late AppDatabase db;
    late AnimalDao dao;

    setUp(() async {
      db = TestDatabase.createInMemoryDatabase();
      dao = AnimalDao(db);
    });

    tearDown(() async {
      await db.close();
    });

    group('insert', () {
      test('should insert animal and return generated ID', () async {
        final id = await dao.insert(const AnimalsCompanion(
          tag: Value('COW001'),
          name: Value('Bella'),
          breed: Value('Holstein'),
          gender: Value('F'),
        ));

        expect(id, equals(1));
      });

      test('should throw on duplicate tag', () async {
        await dao.insert(const AnimalsCompanion(
          tag: Value('COW001'),
          name: Value('Bella'),
          breed: Value('Holstein'),
          gender: Value('F'),
        ));

        expect(
          () => dao.insert(const AnimalsCompanion(
            tag: Value('COW001'),
            name: Value('Daisy'),
            breed: Value('Jersey'),
            gender: Value('F'),
          )),
          throwsException,
        );
      });
    });

    group('getById', () {
      test('should return animal by ID', () async {
        final id = await dao.insert(const AnimalsCompanion(
          tag: Value('COW001'),
          name: Value('Bella'),
          breed: Value('Holstein'),
          gender: Value('F'),
        ));

        final animal = await dao.getById(id);

        expect(animal, isNotNull);
        expect(animal!.tag, equals('COW001'));
        expect(animal.name, equals('Bella'));
      });

      test('should return null for non-existent ID', () async {
        final animal = await dao.getById(999);
        expect(animal, isNull);
      });
    });

    group('getActive', () {
      test('should return only active animals', () async {
        await dao.insert(const AnimalsCompanion(
          tag: Value('COW001'),
          name: Value('Bella'),
          breed: Value('Holstein'),
          gender: Value('F'),
          status: Value('active'),
        ));
        await dao.insert(const AnimalsCompanion(
          tag: Value('COW002'),
          name: Value('Sold Cow'),
          breed: Value('Jersey'),
          gender: Value('F'),
          status: Value('sold'),
        ));

        final animals = await dao.getActive();

        expect(animals.length, equals(1));
        expect(animals.first.name, equals('Bella'));
      });
    });

    group('searchByName', () {
      test('should return animals matching name pattern', () async {
        await dao.insert(const AnimalsCompanion(
          tag: Value('COW001'),
          name: Value('Bella'),
          breed: Value('Holstein'),
          gender: Value('F'),
        ));
        await dao.insert(const AnimalsCompanion(
          tag: Value('COW002'),
          name: Value('Bellatrix'),
          breed: Value('Jersey'),
          gender: Value('F'),
        ));
        await dao.insert(const AnimalsCompanion(
          tag: Value('COW003'),
          name: Value('Daisy'),
          breed: Value('Holstein'),
          gender: Value('F'),
        ));

        final results = await dao.searchByName('Bell');

        expect(results.length, equals(2));
      });
    });

    group('deleteAnimal', () {
      test('should delete animal by ID', () async {
        final id = await dao.insert(const AnimalsCompanion(
          tag: Value('COW001'),
          name: Value('Bella'),
          breed: Value('Holstein'),
          gender: Value('F'),
        ));

        final count = await dao.deleteAnimal(id);

        expect(count, equals(1));

        final animal = await dao.getById(id);
        expect(animal, isNull);
      });
    });
  });
}
```

---

## 14. Appendices

### Appendix A: Complete Database Schema

```sql
-- ============================================================
-- Smart Dairy Mobile Database Schema
-- Version: 1.0
-- Date: January 31, 2026
-- ============================================================

-- Users table
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    server_id TEXT UNIQUE,
    name TEXT NOT NULL,
    email TEXT UNIQUE,
    phone TEXT,
    role TEXT NOT NULL CHECK (role IN ('admin', 'manager', 'worker', 'vet', 'viewer')),
    password_hash TEXT,
    is_active INTEGER DEFAULT 1,
    last_login DATETIME,
    synced INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Animals table
CREATE TABLE animals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tag TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    breed TEXT NOT NULL,
    birth_date DATETIME,
    gender TEXT NOT NULL CHECK (gender IN ('M', 'F')),
    status TEXT DEFAULT 'active' CHECK (status IN ('active', 'sold', 'deceased')),
    owner_id INTEGER REFERENCES users(id),
    weight REAL,
    image_url TEXT,
    notes TEXT,
    synced INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Milk records table
CREATE TABLE milk_records (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    animal_id INTEGER NOT NULL REFERENCES animals(id) ON DELETE CASCADE,
    date DATETIME NOT NULL,
    amount REAL NOT NULL CHECK (amount > 0),
    quality TEXT CHECK (quality IN ('A', 'B', 'C')),
    fat_content REAL,
    protein_content REAL,
    temperature REAL,
    recorded_by INTEGER REFERENCES users(id),
    session TEXT CHECK (session IN ('Morning', 'Afternoon', 'Evening')),
    notes TEXT,
    synced INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Health records table
CREATE TABLE health_records (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    animal_id INTEGER NOT NULL REFERENCES animals(id) ON DELETE CASCADE,
    date DATETIME NOT NULL,
    type TEXT NOT NULL CHECK (type IN ('Checkup', 'Treatment', 'Vaccination', 'Surgery', 'Deworming')),
    diagnosis TEXT,
    treatment TEXT,
    medications TEXT,
    vet_name TEXT,
    cost REAL,
    follow_up_date DATETIME,
    notes TEXT,
    synced INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    server_id TEXT UNIQUE,
    order_number TEXT NOT NULL UNIQUE,
    customer_id INTEGER,
    items TEXT NOT NULL,
    total_amount REAL NOT NULL,
    status TEXT DEFAULT 'pending' CHECK (status IN ('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled')),
    sync_status TEXT DEFAULT 'pending' CHECK (sync_status IN ('synced', 'pending', 'failed')),
    order_date DATETIME NOT NULL,
    delivery_date DATETIME,
    payment_status TEXT DEFAULT 'unpaid' CHECK (payment_status IN ('unpaid', 'partial', 'paid')),
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Sync queue table
CREATE TABLE sync_queue (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    operation TEXT NOT NULL CHECK (operation IN ('CREATE', 'UPDATE', 'DELETE')),
    table_name TEXT NOT NULL,
    record_id INTEGER NOT NULL,
    server_id TEXT,
    data TEXT NOT NULL,
    priority INTEGER DEFAULT 5 CHECK (priority BETWEEN 1 AND 10),
    attempts INTEGER DEFAULT 0,
    last_attempt DATETIME,
    error_message TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- Indexes
-- ============================================================

-- Animals indexes
CREATE UNIQUE INDEX idx_animals_tag ON animals(tag);
CREATE INDEX idx_animals_status ON animals(status);
CREATE INDEX idx_animals_breed ON animals(breed);
CREATE INDEX idx_animals_owner ON animals(owner_id);

-- Milk records indexes
CREATE INDEX idx_milk_animal ON milk_records(animal_id);
CREATE INDEX idx_milk_date ON milk_records(date);
CREATE INDEX idx_milk_animal_date ON milk_records(animal_id, date);
CREATE INDEX idx_milk_synced ON milk_records(synced);

-- Health records indexes
CREATE INDEX idx_health_animal ON health_records(animal_id);
CREATE INDEX idx_health_date ON health_records(date);
CREATE INDEX idx_health_type ON health_records(type);
CREATE INDEX idx_health_followup ON health_records(follow_up_date);

-- Orders indexes
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_sync ON orders(sync_status);
CREATE INDEX idx_orders_date ON orders(order_date);

-- Sync queue indexes
CREATE INDEX idx_sync_priority ON sync_queue(priority, created_at);
CREATE INDEX idx_sync_table_record ON sync_queue(table_name, record_id);

-- ============================================================
-- Triggers
-- ============================================================

-- Auto-update updated_at for animals
CREATE TRIGGER trg_animals_updated_at
AFTER UPDATE ON animals
BEGIN
    UPDATE animals SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;

-- Auto-update updated_at for orders
CREATE TRIGGER trg_orders_updated_at
AFTER UPDATE ON orders
BEGIN
    UPDATE orders SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;

-- Auto-update updated_at for users
CREATE TRIGGER trg_users_updated_at
AFTER UPDATE ON users
BEGIN
    UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;
```

### Appendix B: Repository Pattern Integration

```dart
// lib/repositories/animal_repository.dart

import 'package:smart_dairy/database/app_database.dart';
import 'package:smart_dairy/services/sync_service.dart';

class AnimalRepository {
  final AppDatabase _db;
  final SyncService _syncService;

  AnimalRepository(this._db, this._syncService);

  Future<Animal?> getById(int id) => _db.animalDao.getById(id);

  Future<Animal?> getByTag(String tag) => _db.animalDao.getByTag(tag);

  Future<List<Animal>> getAll() => _db.animalDao.getAll();

  Future<List<Animal>> getActive() => _db.animalDao.getActive();

  Stream<List<Animal>> watchAll() => _db.animalDao.watchAll();

  Future<int> create(Animal animal) async {
    final id = await _db.animalDao.insert(AnimalsCompanion(
      tag: Value(animal.tag),
      name: Value(animal.name),
      breed: Value(animal.breed),
      birthDate: Value(animal.birthDate),
      gender: Value(animal.gender),
      status: Value(animal.status),
    ));
    
    // Trigger sync
    await _syncService.syncAnimal(id);
    
    return id;
  }

  Future<bool> update(Animal animal) async {
    final success = await _db.animalDao.updateAnimal(AnimalsCompanion(
      id: Value(animal.id),
      name: Value(animal.name),
      weight: Value(animal.weight),
      status: Value(animal.status),
    ));
    
    if (success) {
      await _syncService.syncAnimal(animal.id);
    }
    
    return success;
  }

  Future<int> delete(int id) async {
    final count = await _db.animalDao.deleteAnimal(id);
    return count;
  }
}
```

### Appendix C: Change History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-31 | Mobile Lead | Initial release |

---

**Document End**

*This document is the property of Smart Dairy Ltd. Unauthorized distribution is prohibited.*
