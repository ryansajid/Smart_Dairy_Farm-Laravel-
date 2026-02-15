# SMART DAIRY LTD.
## MOBILE APP ARCHITECTURE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | H-001 |
| **Version** | 1.0 |
| **Date** | March 14, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Tech Lead |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Architecture Overview](#2-architecture-overview)
3. [Technology Stack](#3-technology-stack)
4. [Project Structure](#4-project-structure)
5. [State Management](#5-state-management)
6. [Networking Layer](#6-networking-layer)
7. [Local Storage](#7-local-storage)
8. [Authentication](#8-authentication)
9. [Feature Modules](#9-feature-modules)
10. [Push Notifications](#10-push-notifications)
11. [Security](#11-security)
12. [Testing Strategy](#12-testing-strategy)
13. [Build & Deployment](#13-build--deployment)
14. [Appendices](#14-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the comprehensive mobile application architecture for Smart Dairy's farm management mobile application. The app enables farm workers and supervisors to perform daily operations, record data, and access critical information while working in the field.

### 1.2 App Variants

| Variant | Target Users | Primary Features |
|---------|--------------|------------------|
| **Farm Worker App** | Field workers | Milk recording, task completion, animal lookup |
| **Supervisor App** | Farm supervisors | Full herd management, approvals, reporting |
| **Customer App** | B2C/B2B customers | Ordering, subscription management, tracking |

### 1.3 Supported Platforms

| Platform | Minimum Version | Target Version |
|----------|-----------------|----------------|
| **Android** | API 24 (Android 7.0) | API 34 (Android 14) |
| **iOS** | iOS 14.0 | iOS 17.0 |

### 1.4 Device Requirements

- **RAM**: 2GB minimum, 4GB recommended
- **Storage**: 100MB app + 500MB data
- **Network**: WiFi/4G capability
- **Camera**: Required for QR/RFID scanning
- **NFC**: Optional, for RFID tag reading

---

## 2. ARCHITECTURE OVERVIEW

### 2.1 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         SMART DAIRY MOBILE APP                               │
│                           (Flutter Framework)                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         PRESENTATION LAYER                             │  │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ │  │
│  │  │ Screens/Pages│ │   Widgets    │ │   Themes     │ │ Localization │ │  │
│  │  │              │ │              │ │              │ │   (i18n)     │ │  │
│  │  └──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘ │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         BUSINESS LOGIC LAYER                           │  │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ │  │
│  │  │    BLoC      │ │  Use Cases   │ │ Repositories │ │   Models     │ │  │
│  │  │  (State Mgmt)│ │              │ │              │ │   (Domain)   │ │  │
│  │  └──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘ │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         DATA LAYER                                     │  │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ │  │
│  │  │     API      │ │   Local DB   │ │   Cache      │ │   Sync       │ │  │
│  │  │   Client     │ │  (SQLite)    │ │   (Hive)     │ │   Engine     │ │  │
│  │  └──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘ │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         PLATFORM LAYER                                 │  │
│  │  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ │  │
│  │  │   Camera     │ │  Location    │ │     NFC      │ │  Biometric   │ │  │
│  │  │              │ │              │ │              │ │              │ │  │
│  │  └──────────────┘ └──────────────┘ └──────────────┘ └──────────────┘ │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Clean Architecture Pattern

```
┌─────────────────────────────────────────────────────────────────┐
│                    CLEAN ARCHITECTURE LAYERS                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  PRESENTATION (UI)                                               │
│  ├── Pages/Screens                                               │
│  ├── Widgets (Stateless/Stateful)                                │
│  └── BLoC (Business Logic Component)                             │
│                                                                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  DOMAIN (Core Business Logic)                                    │
│  ├── Entities (Models)                                           │
│  ├── Use Cases (Interactors)                                     │
│  └── Repository Interfaces                                       │
│                                                                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  DATA (Implementation Details)                                   │
│  ├── Repository Implementations                                  │
│  ├── Data Sources (Remote/Local)                                 │
│  └── DTOs (Data Transfer Objects)                                │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘

Dependency Rule: Outer layers depend on inner layers only
```

---

## 3. TECHNOLOGY STACK

### 3.1 Core Framework

| Component | Technology | Version | Purpose |
|-----------|------------|---------|---------|
| **Framework** | Flutter | 3.19+ | UI framework |
| **Language** | Dart | 3.3+ | Programming language |
| **State Management** | flutter_bloc | 8.1+ | BLoC pattern |
| **Dependency Injection** | get_it + injectable | 3.0+ | Service locator |
| **Routing** | go_router | 13.0+ | Navigation |

### 3.2 Data Layer

| Component | Package | Purpose |
|-----------|---------|---------|
| **Local Database** | sqflite | SQLite database |
| **ORM** | drift | Type-safe SQL |
| **Cache** | hive | Key-value cache |
| **API Client** | dio | HTTP client |
| **Serialization** | json_serializable | JSON parsing |
| **Connectivity** | connectivity_plus | Network status |

### 3.3 UI/UX

| Component | Package | Purpose |
|-----------|---------|---------|
| **Icons** | flutter_phosphor_icons | Icon library |
| **Charts** | fl_chart | Data visualization |
| **Tables** | data_table_2 | Data tables |
| **Forms** | reactive_forms | Form handling |
| **Loading** | shimmer | Loading effects |
| **Images** | cached_network_image | Image caching |

### 3.4 Platform Integration

| Feature | Package | Purpose |
|---------|---------|---------|
| **Camera** | camera | Photo capture |
| **Scanner** | mobile_scanner | QR/Barcode scan |
| **Location** | geolocator | GPS positioning |
| **Storage** | path_provider | File system access |
| **Biometric** | local_auth | Fingerprint/Face ID |
| **Notifications** | firebase_messaging | Push notifications |

---

## 4. PROJECT STRUCTURE

### 4.1 Directory Structure

```
lib/
├── main.dart                          # Application entry point
├── app.dart                           # App configuration
├── injection.dart                     # Dependency injection setup
│
├── core/                              # Core utilities
│   ├── constants/
│   │   ├── api_constants.dart
│   │   ├── app_constants.dart
│   │   └── storage_keys.dart
│   ├── errors/
│   │   ├── exceptions.dart
│   │   └── failures.dart
│   ├── network/
│   │   ├── api_client.dart
│   │   ├── network_info.dart
│   │   └── interceptors/
│   ├── theme/
│   │   ├── app_theme.dart
│   │   ├── app_colors.dart
│   │   └── app_typography.dart
│   ├── utils/
│   │   ├── date_formatter.dart
│   │   ├── validators.dart
│   │   └── extensions/
│   └── widgets/
│       ├── common_button.dart
│       ├── loading_indicator.dart
│       └── error_widget.dart
│
├── features/                          # Feature modules
│   ├── auth/
│   │   ├── data/
│   │   │   ├── datasources/
│   │   │   ├── models/
│   │   │   └── repositories/
│   │   ├── domain/
│   │   │   ├── entities/
│   │   │   ├── repositories/
│   │   │   └── usecases/
│   │   └── presentation/
│   │       ├── bloc/
│   │       ├── pages/
│   │       └── widgets/
│   │
│   ├── herd_management/
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   │
│   ├── milk_production/
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   │
│   ├── health_management/
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   │
│   ├── offline_sync/
│   │   ├── data/
│   │   ├── domain/
│   │   └── presentation/
│   │
│   └── dashboard/
│       ├── data/
│       ├── domain/
│       └── presentation/
│
└── generated/                         # Generated files
    ├── *.g.dart                       # JSON serializable
    └── *.config.dart                  # Injectable config
```

---

## 5. STATE MANAGEMENT

### 5.1 BLoC Pattern Implementation

```dart
// Core BLoC classes
// lib/core/bloc/smart_dairy_bloc.dart

import 'package:flutter_bloc/flutter_bloc.dart';

abstract class SmartDairyEvent {}

abstract class SmartDairyState {}

class InitialState extends SmartDairyState {}

class LoadingState extends SmartDairyState {}

class SuccessState<T> extends SmartDairyState {
  final T data;
  SuccessState(this.data);
}

class ErrorState extends SmartDairyState {
  final String message;
  final int? code;
  ErrorState(this.message, {this.code});
}

// Example: Milk Production BLoC
// lib/features/milk_production/presentation/bloc/milk_production_bloc.dart

// Events
abstract class MilkProductionEvent extends SmartDairyEvent {}

class LoadTodaysProduction extends MilkProductionEvent {}

class RecordMilkProduction extends MilkProductionEvent {
  final String animalId;
  final double quantityLiters;
  final String session;
  final DateTime timestamp;
  
  RecordMilkProduction({
    required this.animalId,
    required this.quantityLiters,
    required this.session,
    required this.timestamp,
  });
}

class SyncOfflineRecords extends MilkProductionEvent {}

// States
typedef MilkProductionData = Map<String, dynamic>;

class MilkProductionBloc extends Bloc<MilkProductionEvent, SmartDairyState> {
  final RecordMilkProductionUseCase _recordUseCase;
  final GetTodaysProductionUseCase _getProductionUseCase;
  final SyncManager _syncManager;
  
  MilkProductionBloc({
    required RecordMilkProductionUseCase recordUseCase,
    required GetTodaysProductionUseCase getProductionUseCase,
    required SyncManager syncManager,
  })  : _recordUseCase = recordUseCase,
        _getProductionUseCase = getProductionUseCase,
        _syncManager = syncManager,
        super(InitialState()) {
    on<LoadTodaysProduction>(_onLoadTodaysProduction);
    on<RecordMilkProduction>(_onRecordMilkProduction);
    on<SyncOfflineRecords>(_onSyncOfflineRecords);
  }
  
  Future<void> _onLoadTodaysProduction(
    LoadTodaysProduction event,
    Emitter<SmartDairyState> emit,
  ) async {
    emit(LoadingState());
    
    final result = await _getProductionUseCase();
    
    result.fold(
      (failure) => emit(ErrorState(failure.message)),
      (data) => emit(SuccessState<MilkProductionData>(data)),
    );
  }
  
  Future<void> _onRecordMilkProduction(
    RecordMilkProduction event,
    Emitter<SmartDairyState> emit,
  ) async {
    emit(LoadingState());
    
    final productionRecord = MilkProductionRecord(
      animalId: event.animalId,
      quantityLiters: event.quantityLiters,
      session: event.session,
      timestamp: event.timestamp,
    );
    
    final result = await _recordUseCase(productionRecord);
    
    result.fold(
      (failure) => emit(ErrorState(failure.message)),
      (_) {
        emit(SuccessState<Map<String, dynamic>>({'recorded': true}));
        // Reload data
        add(LoadTodaysProduction());
      },
    );
  }
}
```

### 5.2 State Flow Diagram

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│    EVENT     │────▶│    BLOC      │────▶│    STATE     │
│   (Input)    │     │  (Business   │     │   (Output)   │
│              │     │   Logic)     │     │              │
└──────────────┘     └──────────────┘     └──────────────┘
       │                    │                    │
       │                    │                    │
       ▼                    ▼                    ▼
  User Action          Use Cases            UI Update
  Button Click         Repository Call      Rebuild
  Form Submit          API/DB Operation     Show Data
```

---

## 6. NETWORKING LAYER

### 6.1 API Client Configuration

```dart
// lib/core/network/api_client.dart

import 'package:dio/dio.dart';

class ApiClient {
  late final Dio _dio;
  final String baseUrl;
  final String apiVersion = 'v1';
  
  ApiClient({required this.baseUrl}) {
    _dio = Dio(BaseOptions(
      baseUrl: '$baseUrl/api/$apiVersion',
      connectTimeout: const Duration(seconds: 30),
      receiveTimeout: const Duration(seconds: 30),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    ));
    
    _setupInterceptors();
  }
  
  void _setupInterceptors() {
    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        // Add auth token
        final token = await _getAuthToken();
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        
        // Add device info
        options.headers['X-Device-ID'] = await _getDeviceId();
        options.headers['X-App-Version'] = await _getAppVersion();
        
        return handler.next(options);
      },
      onError: (error, handler) async {
        if (error.response?.statusCode == 401) {
          // Token expired, try refresh
          final refreshed = await _refreshToken();
          if (refreshed) {
            // Retry request
            return handler.resolve(await _retry(error.requestOptions));
          }
        }
        return handler.next(error);
      },
    ));
  }
  
  Future<Response> get(String path, {Map<String, dynamic>? queryParameters}) {
    return _dio.get(path, queryParameters: queryParameters);
  }
  
  Future<Response> post(String path, {dynamic data}) {
    return _dio.post(path, data: data);
  }
  
  Future<Response> put(String path, {dynamic data}) {
    return _dio.put(path, data: data);
  }
  
  Future<Response> delete(String path) {
    return _dio.delete(path);
  }
}
```

### 6.2 Repository Pattern

```dart
// lib/features/milk_production/domain/repositories/milk_production_repository.dart

abstract class MilkProductionRepository {
  Future<Either<Failure, List<MilkProductionRecord>>> getTodaysProduction();
  Future<Either<Failure, MilkProductionRecord>> recordProduction(
    MilkProductionRecord record,
  );
  Future<Either<Failure, List<MilkProductionRecord>>> getAnimalProductionHistory(
    String animalId,
    DateTime startDate,
    DateTime endDate,
  );
  Future<Either<Failure, ProductionSummary>> getProductionSummary(
    DateTime date,
  );
}

// lib/features/milk_production/data/repositories/milk_production_repository_impl.dart

class MilkProductionRepositoryImpl implements MilkProductionRepository {
  final MilkProductionRemoteDataSource _remoteDataSource;
  final MilkProductionLocalDataSource _localDataSource;
  final NetworkInfo _networkInfo;
  
  MilkProductionRepositoryImpl({
    required MilkProductionRemoteDataSource remoteDataSource,
    required MilkProductionLocalDataSource localDataSource,
    required NetworkInfo networkInfo,
  })  : _remoteDataSource = remoteDataSource,
        _localDataSource = localDataSource,
        _networkInfo = networkInfo;
  
  @override
  Future<Either<Failure, MilkProductionRecord>> recordProduction(
    MilkProductionRecord record,
  ) async {
    if (await _networkInfo.isConnected) {
      try {
        final result = await _remoteDataSource.recordProduction(record);
        // Also save locally for caching
        await _localDataSource.cacheProductionRecord(result);
        return Right(result);
      } on ServerException catch (e) {
        // Save to local pending queue
        await _localDataSource.savePendingRecord(record);
        return Left(ServerFailure(e.message));
      }
    } else {
      // Offline - save to pending queue
      await _localDataSource.savePendingRecord(record);
      return Right(record.copyWith(syncStatus: SyncStatus.pending));
    }
  }
  
  @override
  Future<Either<Failure, List<MilkProductionRecord>>> getTodaysProduction() async {
    try {
      // Always try to get from remote first if online
      if (await _networkInfo.isConnected) {
        final remoteData = await _remoteDataSource.getTodaysProduction();
        await _localDataSource.cacheProductionRecords(remoteData);
        return Right(remoteData);
      }
    } on ServerException {
      // Fall through to local cache
    }
    
    // Return from local cache
    try {
      final localData = await _localDataSource.getCachedProduction();
      return Right(localData);
    } on CacheException {
      return Left(CacheFailure());
    }
  }
}
```

---

## 7. LOCAL STORAGE

### 7.1 SQLite Database Schema

```dart
// lib/core/database/app_database.dart

import 'package:drift/drift.dart';
import 'package:drift_flutter/drift_flutter.dart';

part 'app_database.g.dart';

@DataClassName('AnimalEntity')
class Animals extends Table {
  TextColumn get id => text()();
  TextColumn get rfidTag => text().nullable()();
  TextColumn get name => text()();
  TextColumn get breed => text()();
  TextColumn get status => text()();
  IntColumn get ageMonths => integer().nullable()();
  TextColumn get barnId => text().nullable()();
  DateTimeColumn get lastUpdated => dateTime()();
  
  @override
  Set<Column> get primaryKey => {id};
}

@DataClassName('MilkProductionEntity')
class MilkProductionRecords extends Table {
  TextColumn get localId => text()();
  TextColumn get serverId => text().nullable()();
  TextColumn get animalId => text()();
  RealColumn get quantityLiters => real()();
  TextColumn get session => text()();
  DateTimeColumn get productionDate => dateTime()();
  DateTimeColumn get recordedAt => dateTime()();
  TextColumn get syncStatus => text()(); // 'synced', 'pending', 'failed'
  TextColumn get recordingMethod => text()();
  
  @override
  Set<Column> get primaryKey => {localId};
}

@DataClassName('PendingSyncEntity')
class PendingSyncs extends Table {
  TextColumn get id => text()();
  TextColumn get entityType => text()(); // 'milk', 'health', 'animal'
  TextColumn get entityId => text()();
  TextColumn get operation => text()(); // 'create', 'update', 'delete'
  TextColumn get payload => text()();
  IntColumn get retryCount => integer().withDefault(const Constant(0))();
  DateTimeColumn get createdAt => dateTime()();
  DateTimeColumn get lastAttempt => dateTime().nullable()();
  
  @override
  Set<Column> get primaryKey => {id};
}

@DriftDatabase(tables: [Animals, MilkProductionRecords, PendingSyncs])
class AppDatabase extends _$AppDatabase {
  AppDatabase() : super(_openConnection());
  
  @override
  int get schemaVersion => 1;
  
  static QueryExecutor _openConnection() {
    return driftDatabase(name: 'smart_dairy_db');
  }
  
  // Animal queries
  Future<List<AnimalEntity>> getAllAnimals() => select(animals).get();
  
  Future<AnimalEntity?> getAnimalById(String id) {
    return (select(animals)..where((a) => a.id.equals(id))).getSingleOrNull();
  }
  
  Future<void> insertOrUpdateAnimal(AnimalEntity animal) {
    return into(animals).insertOnConflictUpdate(animal);
  }
  
  // Milk production queries
  Future<List<MilkProductionEntity>> getTodaysProduction() {
    final today = DateTime.now();
    return (select(milkProductionRecords)
          ..where((m) => m.productionDate.equals(today)))
        .get();
  }
  
  Future<List<MilkProductionEntity>> getPendingRecords() {
    return (select(milkProductionRecords)
          ..where((m) => m.syncStatus.equals('pending')))
        .get();
  }
  
  Future<void> markAsSynced(String localId, String serverId) {
    return update(milkProductionRecords).replace(
      MilkProductionEntity(
        localId: localId,
        serverId: serverId,
        syncStatus: 'synced',
      ),
    );
  }
  
  // Pending sync queries
  Future<List<PendingSyncEntity>> getPendingSyncs() => select(pendingSyncs).get();
  
  Future<void> addPendingSync(PendingSyncsCompanion sync) {
    return into(pendingSyncs).insert(sync);
  }
  
  Future<void> removePendingSync(String id) {
    return (delete(pendingSyncs)..where((s) => s.id.equals(id))).go();
  }
}
```

### 7.2 Cache Management

```dart
// lib/core/cache/cache_manager.dart

import 'package:hive/hive.dart';

class CacheManager {
  static const String _userBox = 'user_cache';
  static const String _dataBox = 'data_cache';
  static const String _settingsBox = 'settings_cache';
  
  late Box<String> _userCache;
  late Box<dynamic> _dataCache;
  late Box<dynamic> _settingsCache;
  
  Future<void> initialize() async {
    _userCache = await Hive.openBox<String>(_userBox);
    _dataCache = await Hive.openBox<dynamic>(_dataBox);
    _settingsCache = await Hive.openBox<dynamic>(_settingsBox);
  }
  
  // User data
  Future<void> saveUserToken(String token) async {
    await _userCache.put('auth_token', token);
  }
  
  String? getUserToken() {
    return _userCache.get('auth_token');
  }
  
  Future<void> clearUserData() async {
    await _userCache.clear();
  }
  
  // Generic data caching with TTL
  Future<void> cacheData(String key, dynamic data, {Duration? ttl}) async {
    final cacheEntry = {
      'data': data,
      'cachedAt': DateTime.now().millisecondsSinceEpoch,
      'ttl': ttl?.inMilliseconds,
    };
    await _dataCache.put(key, cacheEntry);
  }
  
  dynamic getCachedData(String key) {
    final entry = _dataCache.get(key);
    if (entry == null) return null;
    
    final cachedAt = entry['cachedAt'] as int;
    final ttl = entry['ttl'] as int?;
    
    if (ttl != null) {
      final expiryTime = cachedAt + ttl;
      if (DateTime.now().millisecondsSinceEpoch > expiryTime) {
        _dataCache.delete(key);
        return null;
      }
    }
    
    return entry['data'];
  }
  
  // Settings
  Future<void> saveSetting(String key, dynamic value) async {
    await _settingsCache.put(key, value);
  }
  
  T? getSetting<T>(String key) {
    return _settingsCache.get(key) as T?;
  }
}
```

---

## 8. AUTHENTICATION

### 8.1 Authentication Flow

```dart
// lib/features/auth/presentation/bloc/auth_bloc.dart

// Events
abstract class AuthEvent {}

class LoginRequested extends AuthEvent {
  final String username;
  final String password;
  
  LoginRequested({required this.username, required this.password});
}

class LogoutRequested extends AuthEvent {}

class CheckAuthStatus extends AuthEvent {}

class RefreshTokenRequested extends AuthEvent {}

// States
abstract class AuthState {}

class AuthInitial extends AuthState {}

class AuthLoading extends AuthState {}

class Authenticated extends AuthState {
  final User user;
  Authenticated(this.user);
}

class Unauthenticated extends AuthState {}

class AuthError extends AuthState {
  final String message;
  AuthError(this.message);
}

// BLoC
class AuthBloc extends Bloc<AuthEvent, AuthState> {
  final LoginUseCase _loginUseCase;
  final LogoutUseCase _logoutUseCase;
  final GetCurrentUserUseCase _getCurrentUserUseCase;
  final CacheManager _cacheManager;
  
  AuthBloc({
    required LoginUseCase loginUseCase,
    required LogoutUseCase logoutUseCase,
    required GetCurrentUserUseCase getCurrentUserUseCase,
    required CacheManager cacheManager,
  })  : _loginUseCase = loginUseCase,
        _logoutUseCase = logoutUseCase,
        _getCurrentUserUseCase = getCurrentUserUseCase,
        _cacheManager = cacheManager,
        super(AuthInitial()) {
    on<LoginRequested>(_onLoginRequested);
    on<LogoutRequested>(_onLogoutRequested);
    on<CheckAuthStatus>(_onCheckAuthStatus);
  }
  
  Future<void> _onLoginRequested(
    LoginRequested event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());
    
    final result = await _loginUseCase(
      LoginParams(
        username: event.username,
        password: event.password,
      ),
    );
    
    result.fold(
      (failure) => emit(AuthError(failure.message)),
      (user) {
        _cacheManager.saveUserToken(user.token);
        emit(Authenticated(user));
      },
    );
  }
  
  Future<void> _onLogoutRequested(
    LogoutRequested event,
    Emitter<AuthState> emit,
  ) async {
    await _logoutUseCase();
    await _cacheManager.clearUserData();
    emit(Unauthenticated());
  }
}
```

### 8.2 Biometric Authentication

```dart
// lib/features/auth/data/biometric_auth.dart

import 'package:local_auth/local_auth.dart';

class BiometricAuthService {
  final LocalAuthentication _localAuth = LocalAuthentication();
  
  Future<bool> isDeviceSupported() async {
    return await _localAuth.isDeviceSupported();
  }
  
  Future<bool> checkBiometrics() async {
    final availableBiometrics = await _localAuth.getAvailableBiometrics();
    return availableBiometrics.isNotEmpty;
  }
  
  Future<bool> authenticate() async {
    try {
      return await _localAuth.authenticate(
        localizedReason: 'Please authenticate to access Smart Dairy',
        authMessages: const [
          AndroidAuthMessages(
            signInTitle: 'Biometric Authentication',
            cancelButton: 'Cancel',
            biometricHint: 'Verify your identity',
            biometricNotRecognized: 'Not recognized, try again',
            biometricSuccess: 'Authentication successful',
            deviceCredentialsRequiredTitle: 'Device credentials required',
            deviceCredentialsSetupDescription: 'Please set up device credentials',
            goToSettingsButton: 'Go to Settings',
            goToSettingsDescription: 'Please set up biometric authentication in Settings',
          ),
          IOSAuthMessages(
            cancelButton: 'Cancel',
            goToSettingsButton: 'Go to Settings',
            goToSettingsDescription: 'Please set up biometric authentication in Settings',
            lockOut: 'Please re-enable biometric authentication',
          ),
        ],
        options: const AuthenticationOptions(
          useErrorDialogs: true,
          stickyAuth: true,
          biometricOnly: false,
        ),
      );
    } catch (e) {
      return false;
    }
  }
}
```

---

## 9. FEATURE MODULES

### 9.1 Milk Production Feature

```dart
// lib/features/milk_production/presentation/pages/milk_entry_page.dart

class MilkEntryPage extends StatefulWidget {
  const MilkEntryPage({super.key});
  
  @override
  State<MilkEntryPage> createState() => _MilkEntryPageState();
}

class _MilkEntryPageState extends State<MilkEntryPage> {
  final _rfidController = TextEditingController();
  final _volumeController = TextEditingController();
  String _selectedSession = 'morning';
  Animal? _scannedAnimal;
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Record Milk Production'),
      ),
      body: BlocConsumer<MilkProductionBloc, SmartDairyState>(
        listener: (context, state) {
          if (state is SuccessState) {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Milk production recorded successfully')),
            );
            _resetForm();
          } else if (state is ErrorState) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text('Error: ${state.message}')),
            );
          }
        },
        builder: (context, state) {
          return SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                // Session selector
                _buildSessionSelector(),
                const SizedBox(height: 24),
                
                // RFID Input
                _buildRfidInput(),
                const SizedBox(height: 16),
                
                // Animal info card
                if (_scannedAnimal != null) _buildAnimalCard(),
                const SizedBox(height: 24),
                
                // Volume input
                _buildVolumeInput(),
                const SizedBox(height: 32),
                
                // Submit button
                ElevatedButton(
                  onPressed: state is LoadingState ? null : _submitRecord,
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    backgroundColor: AppColors.primary,
                  ),
                  child: state is LoadingState
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text(
                          'Save Record',
                          style: TextStyle(fontSize: 16),
                        ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
  
  Widget _buildSessionSelector() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Milking Session',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                _buildSessionChip('morning', 'Morning', Icons.wb_sunny),
                const SizedBox(width: 8),
                _buildSessionChip('evening', 'Evening', Icons.wb_twilight),
                const SizedBox(width: 8),
                _buildSessionChip('night', 'Night', Icons.nights_stay),
              ],
            ),
          ],
        ),
      ),
    );
  }
  
  Widget _buildSessionChip(String value, String label, IconData icon) {
    final isSelected = _selectedSession == value;
    return ChoiceChip(
      selected: isSelected,
      onSelected: (_) => setState(() => _selectedSession = value),
      avatar: Icon(icon, size: 18),
      label: Text(label),
      selectedColor: AppColors.primary.withOpacity(0.2),
    );
  }
  
  Widget _buildRfidInput() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Scan RFID or Enter Animal ID',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _rfidController,
                    decoration: const InputDecoration(
                      hintText: 'Enter RFID or Animal ID',
                      prefixIcon: Icon(Icons.search),
                    ),
                    onSubmitted: (_) => _lookupAnimal(),
                  ),
                ),
                const SizedBox(width: 8),
                IconButton(
                  onPressed: _scanRfid,
                  icon: const Icon(Icons.qr_code_scanner),
                  style: IconButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
  
  Widget _buildAnimalCard() {
    return Card(
      color: AppColors.primary.withOpacity(0.1),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          children: [
            const Icon(Icons.pets, size: 48, color: AppColors.primary),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    _scannedAnimal!.name,
                    style: Theme.of(context).textTheme.titleLarge,
                  ),
                  Text(
                    '${_scannedAnimal!.breed} • ${_scannedAnimal!.status}',
                    style: Theme.of(context).textTheme.bodyMedium,
                  ),
                  Text(
                    'ID: ${_scannedAnimal!.id}',
                    style: Theme.of(context).textTheme.bodySmall,
                  ),
                ],
              ),
            ),
            IconButton(
              onPressed: () => setState(() => _scannedAnimal = null),
              icon: const Icon(Icons.close),
            ),
          ],
        ),
      ),
    );
  }
  
  Widget _buildVolumeInput() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Milk Volume (Liters)',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 12),
            TextField(
              controller: _volumeController,
              keyboardType: const TextInputType.numberWithOptions(decimal: true),
              decoration: const InputDecoration(
                hintText: '0.00',
                suffixText: 'L',
                border: OutlineInputBorder(),
              ),
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            // Quick select buttons
            Row(
              children: [15.0, 20.0, 25.0, 30.0].map((value) {
                return Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: OutlinedButton(
                    onPressed: () => _volumeController.text = value.toString(),
                    child: Text('${value.toInt()}L'),
                  ),
                );
              }).toList(),
            ),
          ],
        ),
      ),
    );
  }
  
  void _scanRfid() async {
    final result = await Navigator.push<String>(
      context,
      MaterialPageRoute(builder: (_) => const RfidScannerPage()),
    );
    if (result != null) {
      _rfidController.text = result;
      _lookupAnimal();
    }
  }
  
  void _lookupAnimal() {
    // Implementation to look up animal by RFID
  }
  
  void _submitRecord() {
    if (_scannedAnimal == null || _volumeController.text.isEmpty) return;
    
    context.read<MilkProductionBloc>().add(
      RecordMilkProduction(
        animalId: _scannedAnimal!.id,
        quantityLiters: double.parse(_volumeController.text),
        session: _selectedSession,
        timestamp: DateTime.now(),
      ),
    );
  }
  
  void _resetForm() {
    _rfidController.clear();
    _volumeController.clear();
    setState(() {
      _scannedAnimal = null;
    });
  }
}
```

---

## 10. PUSH NOTIFICATIONS

### 10.1 Firebase Messaging Setup

```dart
// lib/core/notifications/notification_service.dart

import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';

class NotificationService {
  final FirebaseMessaging _messaging = FirebaseMessaging.instance;
  final FlutterLocalNotificationsPlugin _localNotifications = 
      FlutterLocalNotificationsPlugin();
  
  Future<void> initialize() async {
    // Request permission
    await _messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );
    
    // Initialize local notifications
    const androidSettings = AndroidInitializationSettings('@mipmap/ic_launcher');
    const iosSettings = DarwinInitializationSettings();
    const initSettings = InitializationSettings(
      android: androidSettings,
      iOS: iosSettings,
    );
    await _localNotifications.initialize(initSettings);
    
    // Handle foreground messages
    FirebaseMessaging.onMessage.listen(_handleForegroundMessage);
    
    // Handle background/terminated messages
    FirebaseMessaging.onBackgroundMessage(_handleBackgroundMessage);
    
    // Handle notification taps
    FirebaseMessaging.onMessageOpenedApp.listen(_handleNotificationTap);
  }
  
  Future<String?> getToken() async {
    return await _messaging.getToken();
  }
  
  void _handleForegroundMessage(RemoteMessage message) {
    final notification = message.notification;
    final data = message.data;
    
    if (notification != null) {
      _showLocalNotification(
        id: message.hashCode,
        title: notification.title ?? 'Smart Dairy',
        body: notification.body ?? '',
        payload: data['route'],
      );
    }
  }
  
  Future<void> _showLocalNotification({
    required int id,
    required String title,
    required String body,
    String? payload,
  }) async {
    const androidDetails = AndroidNotificationDetails(
      'smart_dairy_channel',
      'Smart Dairy Notifications',
      channelDescription: 'Notifications for farm activities',
      importance: Importance.high,
      priority: Priority.high,
    );
    const iosDetails = DarwinNotificationDetails();
    const details = NotificationDetails(
      android: androidDetails,
      iOS: iosDetails,
    );
    
    await _localNotifications.show(id, title, body, details, payload: payload);
  }
  
  void _handleNotificationTap(RemoteMessage message) {
    final route = message.data['route'];
    final id = message.data['id'];
    
    // Navigate to appropriate screen
    switch (route) {
      case '/animal':
        // Navigate to animal detail
        break;
      case '/alert':
        // Navigate to alerts
        break;
      case '/task':
        // Navigate to task
        break;
    }
  }
}

// Background message handler (must be top-level function)
@pragma('vm:entry-point')
Future<void> _handleBackgroundMessage(RemoteMessage message) async {
  // Handle background message
  print('Handling background message: ${message.messageId}');
}
```

---

## 11. SECURITY

### 11.1 Certificate Pinning

```dart
// lib/core/security/certificate_pinning.dart

import 'package:dio/dio.dart';
import 'package:dio/io.dart';
import 'dart:io';

class CertificatePinning {
  static Dio createSecureDio() {
    final dio = Dio();
    
    (dio.httpClientAdapter as IOHttpClientAdapter).createHttpClient = () {
      final client = HttpClient();
      
      client.badCertificateCallback = (X509Certificate cert, String host, int port) {
        // Implement certificate pinning
        final serverCert = cert.pem;
        final expectedCert = _getExpectedCertificate();
        return serverCert == expectedCert;
      };
      
      return client;
    };
    
    return dio;
  }
  
  static String _getExpectedCertificate() {
    // Load pinned certificate
    return '''
-----BEGIN CERTIFICATE-----
MIIDXTCCAkWgAwIBAgIJAKoK/heBjcOuMA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNV
...
-----END CERTIFICATE-----
''';
  }
}
```

### 11.2 Root Detection

```dart
// lib/core/security/root_detection.dart

import 'package:flutter_jailbreak_detection/flutter_jailbreak_detection.dart';

class SecurityChecker {
  static Future<bool> isDeviceSecure() async {
    // Check for jailbreak/root
    final isJailbroken = await FlutterJailbreakDetection.jailbroken;
    if (isJailbroken) {
      return false;
    }
    
    // Check for emulator
    final isRealDevice = await FlutterJailbreakDetection.developerMode;
    // Additional checks...
    
    return true;
  }
  
  static void showSecurityWarning(BuildContext context) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (_) => AlertDialog(
        title: const Text('Security Warning'),
        content: const Text(
          'This device appears to be compromised. For security reasons, the app cannot run on rooted or jailbroken devices.'
        ),
        actions: [
          TextButton(
            onPressed: () => exit(0),
            child: const Text('Exit'),
          ),
        ],
      ),
    );
  }
}
```

---

## 12. TESTING STRATEGY

### 12.1 Testing Layers

| Test Type | Tool | Coverage Target |
|-----------|------|-----------------|
| **Unit Tests** | flutter_test | 80% business logic |
| **Widget Tests** | flutter_test | Critical screens |
| **Integration Tests** | integration_test | Key user flows |
| **E2E Tests** | patrol | Full scenarios |

### 12.2 Unit Test Example

```dart
// test/features/milk_production/milk_production_bloc_test.dart

import 'package:bloc_test/bloc_test.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';

class MockRecordMilkProductionUseCase extends Mock
    implements RecordMilkProductionUseCase {}

void main() {
  late MilkProductionBloc bloc;
  late MockRecordMilkProductionUseCase mockUseCase;
  
  setUp(() {
    mockUseCase = MockRecordMilkProductionUseCase();
    bloc = MilkProductionBloc(
      recordUseCase: mockUseCase,
      getProductionUseCase: MockGetTodaysProductionUseCase(),
      syncManager: MockSyncManager(),
    );
  });
  
  blocTest<MilkProductionBloc, SmartDairyState>(
    'emits [Loading, Success] when recording milk production',
    build: () {
      when(mockUseCase.call(any))
          .thenAnswer((_) async => const Right(null));
      return bloc;
    },
    act: (bloc) => bloc.add(RecordMilkProduction(
      animalId: 'SD-001',
      quantityLiters: 20.5,
      session: 'morning',
      timestamp: DateTime.now(),
    )),
    expect: () => [
      isA<LoadingState>(),
      isA<SuccessState>(),
    ],
  );
}
```

---

## 13. BUILD & DEPLOYMENT

### 13.1 Build Configurations

| Flavor | API Endpoint | Features |
|--------|--------------|----------|
| **Development** | api-dev.smartdairybd.com | Debug features, logging |
| **Staging** | api-staging.smartdairybd.com | Test environment |
| **Production** | api.smartdairybd.com | Release features |

### 13.2 CI/CD Pipeline

```yaml
# .github/workflows/mobile-ci.yml
name: Mobile CI/CD

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: '3.19.0'
      - run: flutter pub get
      - run: flutter analyze
      - run: flutter test
      - run: flutter test integration_test/

  build-android:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: subosito/flutter-action@v2
      - run: flutter pub get
      - run: flutter build apk --release --flavor production
      - run: flutter build appbundle --release --flavor production
      - uses: actions/upload-artifact@v3
        with:
          name: android-release
          path: build/app/outputs/flutter-apk/*.apk

  build-ios:
    needs: test
    runs-on: macos-latest
    steps:
      - uses: actions/checkout@v3
      - uses: subosito/flutter-action@v2
      - run: flutter pub get
      - run: flutter build ios --release --flavor production --no-codesign
      - uses: actions/upload-artifact@v3
        with:
          name: ios-release
          path: build/ios/iphoneos/*.app
```

---

## 14. APPENDICES

### Appendix A: Dependencies

```yaml
# pubspec.yaml dependencies
dependencies:
  flutter:
    sdk: flutter
  
  # State Management
  flutter_bloc: ^8.1.4
  equatable: ^2.0.5
  
  # Dependency Injection
  get_it: ^7.6.7
  injectable: ^2.4.1
  
  # Networking
  dio: ^5.4.1
  connectivity_plus: ^5.0.2
  
  # Local Storage
  drift: ^2.16.0
  drift_flutter: ^0.1.0
  hive: ^2.2.3
  hive_flutter: ^1.1.0
  
  # Serialization
  json_annotation: ^4.8.1
  freezed_annotation: ^2.4.1
  
  # UI
  go_router: ^13.2.0
  flutter_phosphor_icons: ^0.0.1+6
  fl_chart: ^0.66.0
  shimmer: ^3.0.0
  
  # Platform Integration
  camera: ^0.10.5+9
  mobile_scanner: ^3.5.7
  geolocator: ^10.1.0
  local_auth: ^2.2.0
  firebase_messaging: ^14.7.19
  
  # Security
  flutter_jailbreak_detection: ^1.8.0
  
dev_dependencies:
  flutter_test:
    sdk: flutter
  build_runner: ^2.4.8
  drift_dev: ^2.16.0
  injectable_generator: ^2.6.1
  json_serializable: ^6.7.1
  freezed: ^2.4.7
  bloc_test: ^9.1.6
  mockito: ^5.4.4
```

### Appendix B: Environment Configuration

```dart
// lib/core/config/app_config.dart

class AppConfig {
  final String apiBaseUrl;
  final String mqttBroker;
  final int mqttPort;
  final bool enableLogging;
  final Duration syncInterval;
  
  const AppConfig({
    required this.apiBaseUrl,
    required this.mqttBroker,
    required this.mqttPort,
    required this.enableLogging,
    required this.syncInterval,
  });
  
  static const development = AppConfig(
    apiBaseUrl: 'https://api-dev.smartdairybd.com',
    mqttBroker: 'mqtt-dev.smartdairybd.com',
    mqttPort: 8883,
    enableLogging: true,
    syncInterval: Duration(minutes: 1),
  );
  
  static const production = AppConfig(
    apiBaseUrl: 'https://api.smartdairybd.com',
    mqttBroker: 'mqtt.smartdairybd.com',
    mqttPort: 8883,
    enableLogging: false,
    syncInterval: Duration(minutes: 5),
  );
}
```

---

**END OF MOBILE APP ARCHITECTURE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Mar 14, 2026 | Mobile Lead | Initial version |
