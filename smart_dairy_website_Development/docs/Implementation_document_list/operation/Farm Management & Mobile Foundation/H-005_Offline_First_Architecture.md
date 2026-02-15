# SMART DAIRY LTD.
## OFFLINE-FIRST ARCHITECTURE
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | H-005 |
| **Version** | 1.0 |
| **Date** | March 21, 2026 |
| **Author** | Mobile Lead |
| **Owner** | Mobile Lead |
| **Reviewer** | Solution Architect |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Offline-First Principles](#2-offline-first-principles)
3. [Architecture Overview](#3-architecture-overview)
4. [Data Synchronization](#4-data-synchronization)
5. [Conflict Resolution](#5-conflict-resolution)
6. [Local Database Design](#6-local-database-design)
7. [Sync Engine Implementation](#7-sync-engine-implementation)
8. [Queue Management](#8-queue-management)
9. [Network Awareness](#9-network-awareness)
10. [Testing Offline Scenarios](#10-testing-offline-scenarios)
11. [Performance Optimization](#11-performance-optimization)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the offline-first architecture for the Smart Dairy mobile application. It ensures farm workers can continue their daily operations even when network connectivity is unavailable or unreliable, with seamless synchronization when connectivity is restored.

### 1.2 Business Context

| Challenge | Impact | Solution |
|-----------|--------|----------|
| Rural farm location with poor cellular coverage | Cannot access or submit data | Local-first data storage |
| Milk recording during barn work | Real-time connection unreliable | Offline form submission |
| Multiple workers on same data | Conflicts when syncing | Conflict resolution strategy |
| Critical operations cannot wait | Business continuity risk | Guaranteed local persistence |

### 1.3 Offline-First Objectives

1. **100% Local Availability** - All critical data accessible offline
2. **Seamless Sync** - Automatic background synchronization
3. **Conflict Resolution** - Intelligent merging of concurrent changes
4. **Data Integrity** - No data loss during sync conflicts
5. **User Transparency** - Clear sync status indicators

---

## 2. OFFLINE-FIRST PRINCIPLES

### 2.1 Core Principles

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    OFFLINE-FIRST DESIGN PRINCIPLES                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  1. LOCAL-FIRST ARCHITECTURE                                                 │
│     ┌─────────────────────────────────────────────────────────────────────┐ │
│     │  All reads and writes happen locally first                          │ │
│     │  Network operations are secondary and asynchronous                  │ │
│     │  User experience is never blocked by network                        │ │
│     └─────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
│  2. EVENTUAL CONSISTENCY                                                     │
│     ┌─────────────────────────────────────────────────────────────────────┐ │
│     │  Local data is source of truth during offline                       │ │
│     │  Server reconciliation happens when connected                       │ │
│     │  Conflicts are resolved with business rules                         │ │
│     └─────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
│  3. OPTIMISTIC UI                                                            │
│     ┌─────────────────────────────────────────────────────────────────────┐ │
│     │  UI updates immediately on local write                              │ │
│     │  Sync happens in background                                         │ │
│     │  Rollback on sync failure if needed                                 │ │
│     └─────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
│  4. QUEUE-BASED SYNC                                                         │
│     ┌─────────────────────────────────────────────────────────────────────┐ │
│     │  All changes queued for sync                                        │ │
│     │  Retry with exponential backoff                                     │ │
│     │  Preserve operation order                                           │ │
│     └─────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Offline Capabilities Matrix

| Feature | Read Offline | Write Offline | Sync Priority | Conflict Risk |
|---------|--------------|---------------|---------------|---------------|
| **Milk Production** | ✅ Full history | ✅ Queue for sync | High | Low |
| **Animal Lookup** | ✅ Full herd | ❌ Read-only | Medium | None |
| **Health Records** | ✅ Recent records | ✅ Queue for sync | High | Medium |
| **Task List** | ✅ Assigned tasks | ✅ Completion status | Medium | Low |
| **Feed Recording** | ✅ Ration data | ✅ Queue for sync | High | Low |
| **Reports/Analytics** | ⚠️ Cached only | ❌ N/A | N/A | None |
| **User Profile** | ✅ Cached | ✅ Queue for sync | Low | Low |

---

## 3. ARCHITECTURE OVERVIEW

### 3.1 System Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         OFFLINE-FIRST ARCHITECTURE                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         PRESENTATION LAYER                             │  │
│  │  ┌─────────────────────────────────────────────────────────────────┐  │  │
│  │  │  UI Components (Optimistic updates, Sync status indicators)    │  │  │
│  │  └─────────────────────────────────────────────────────────────────┘  │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         BUSINESS LOGIC LAYER                           │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │   Use Cases  │  │   BLoC/VM    │  │   Sync State │               │  │
│  │  │              │  │              │  │   Management │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         DATA LAYER (Repository Pattern)                │  │
│  │  ┌─────────────────────────────────────────────────────────────────┐  │  │
│  │  │  Repository Coordinator (Routes to local/remote based on state) │  │  │
│  │  └─────────────────────────────────────────────────────────────────┘  │  │
│  │                                    │                                    │  │
│  │                    ┌───────────────┴───────────────┐                   │  │
│  │                    ▼                               ▼                   │  │
│  │  ┌───────────────────────────┐  ┌───────────────────────────┐       │  │
│  │  │      LOCAL SOURCE         │  │      REMOTE SOURCE        │       │  │
│  │  │  ┌─────────────────────┐  │  │  ┌─────────────────────┐  │       │  │
│  │  │  │   SQLite Database   │  │  │  │   REST API Client   │  │       │  │
│  │  │  │   (Drift/SQLite)    │  │  │  │   (Dio)             │  │       │  │
│  │  │  └─────────────────────┘  │  │  └─────────────────────┘  │       │  │
│  │  │  ┌─────────────────────┐  │  │  ┌─────────────────────┐  │       │  │
│  │  │  │   Pending Queue     │  │  │  │   API Endpoints     │  │       │  │
│  │  │  │   (Change Log)      │  │  │  │   (CRUD Operations) │  │       │  │
│  │  │  └─────────────────────┘  │  │  └─────────────────────┘  │       │  │
│  │  └───────────────────────────┘  └───────────────────────────┘       │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                    ▲                                         │
│                                    │                                         │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                         SYNC ENGINE                                    │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐               │  │
│  │  │   Network    │  │   Conflict   │  │   Background │               │  │
│  │  │   Monitor    │  │   Resolver   │  │   Sync Task  │               │  │
│  │  └──────────────┘  └──────────────┘  └──────────────┘               │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 Repository Pattern with Offline Support

```dart
// lib/core/data/repository/offline_first_repository.dart

import 'package:connectivity_plus/connectivity_plus.dart';

/// Base class for offline-first repositories
abstract class OfflineFirstRepository<LocalModel, RemoteModel> {
  final LocalDataSource<LocalModel> localDataSource;
  final RemoteDataSource<RemoteModel> remoteDataSource;
  final Connectivity connectivity;
  final SyncQueue syncQueue;
  
  OfflineFirstRepository({
    required this.localDataSource,
    required this.remoteDataSource,
    required this.connectivity,
    required this.syncQueue,
  });
  
  /// Get data - tries local first, fetches from remote if stale/missing
  Future<Result<List<LocalModel>>> getAll({bool forceRefresh = false}) async {
    // Always return local data first
    final localData = await localDataSource.getAll();
    
    // Check if we should fetch from remote
    if (await _shouldFetchFromRemote(forceRefresh)) {
      _fetchAndCacheRemoteData(); // Non-blocking
    }
    
    return Result.success(localData);
  }
  
  /// Create new item - save locally, queue for sync
  Future<Result<LocalModel>> create(CreateParams params) async {
    // Generate local ID
    final localId = _generateLocalId();
    
    // Create local model
    final localModel = await localDataSource.create(
      params.copyWith(id: localId, syncStatus: SyncStatus.pending),
    );
    
    // Queue for remote sync
    await syncQueue.enqueue(
      SyncOperation.create,
      entityType: entityType,
      localId: localId,
      payload: params.toJson(),
    );
    
    // Trigger sync if online
    if (await _isOnline()) {
      syncQueue.processQueue();
    }
    
    return Result.success(localModel);
  }
  
  /// Update item - update locally, queue for sync
  Future<Result<LocalModel>> update(String id, UpdateParams params) async {
    // Update local
    final localModel = await localDataSource.update(id, params);
    
    // Queue for remote sync
    await syncQueue.enqueue(
      SyncOperation.update,
      entityType: entityType,
      localId: id,
      payload: params.toJson(),
    );
    
    if (await _isOnline()) {
      syncQueue.processQueue();
    }
    
    return Result.success(localModel);
  }
  
  /// Delete item - soft delete locally, queue for sync
  Future<Result<void>> delete(String id) async {
    // Soft delete local
    await localDataSource.softDelete(id);
    
    // Queue for remote sync
    await syncQueue.enqueue(
      SyncOperation.delete,
      entityType: entityType,
      localId: id,
    );
    
    if (await _isOnline()) {
      syncQueue.processQueue();
    }
    
    return Result.success(null);
  }
  
  Future<bool> _isOnline() async {
    final result = await connectivity.checkConnectivity();
    return result != ConnectivityResult.none;
  }
  
  Future<bool> _shouldFetchFromRemote(bool forceRefresh) async {
    if (forceRefresh) return true;
    if (!await _isOnline()) return false;
    
    // Check cache staleness
    final lastSync = await localDataSource.getLastSyncTime();
    if (lastSync == null) return true;
    
    return DateTime.now().difference(lastSync) > const Duration(minutes: 5);
  }
  
  Future<void> _fetchAndCacheRemoteData() async {
    try {
      final remoteData = await remoteDataSource.getAll();
      await localDataSource.cacheAll(remoteData.map(toLocalModel).toList());
      await localDataSource.setLastSyncTime(DateTime.now());
    } catch (e) {
      // Log error, local data remains
    }
  }
  
  String _generateLocalId() {
    return 'local_${DateTime.now().millisecondsSinceEpoch}_${_randomString(6)}';
  }
  
  String get entityType;
  LocalModel toLocalModel(RemoteModel remote);
}
```

---

## 4. DATA SYNCHRONIZATION

### 4.1 Sync Strategies

| Strategy | Use Case | Implementation |
|----------|----------|----------------|
| **Pull Sync** | Initial load, periodic refresh | Fetch all server data, merge with local |
| **Push Sync** | Local changes to server | Process pending queue, send to API |
| **Bidirectional Sync** | Full synchronization | Pull then Push with conflict resolution |
| **Delta Sync** | Efficient updates | Only sync changed records since last sync |

### 4.2 Sync Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           SYNC PROCESS FLOW                                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐│
│  │  PENDING    │     │   CHECK     │     │    PUSH     │     │   VERIFY    ││
│  │   QUEUE     │────▶│  NETWORK    │────▶│   CHANGES   │────▶│    SYNC     ││
│  │             │     │             │     │             │     │             ││
│  └─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘│
│         │                                           │              │         │
│         │ Offline                                   │ Success      │         │
│         ▼                                           ▼              ▼         │
│  ┌─────────────┐                             ┌─────────────┐ ┌─────────────┐ │
│  │   RETRY     │                             │    MARK     │ │   PULL      │ │
│  │   LATER     │                             │   SYNCED    │ │   SERVER    │ │
│  │             │                             │             │ │   CHANGES   │ │
│  └─────────────┘                             └─────────────┘ └─────────────┘ │
│                                                                     │        │
│                                                                     ▼        │
│                                                              ┌─────────────┐ │
│                                                              │   MERGE     │ │
│                                                              │   LOCAL     │ │
│                                                              │             │ │
│                                                              └─────────────┘ │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.3 Sync Implementation

```dart
// lib/core/sync/sync_engine.dart

class SyncEngine {
  final SyncQueue _queue;
  final ApiClient _apiClient;
  final LocalDatabase _database;
  final ConflictResolver _conflictResolver;
  final NetworkMonitor _networkMonitor;
  
  Stream<SyncStatus> get syncStatus => _syncStatusController.stream;
  final _syncStatusController = StreamController<SyncStatus>.broadcast();
  
  SyncEngine({
    required SyncQueue queue,
    required ApiClient apiClient,
    required LocalDatabase database,
    required ConflictResolver conflictResolver,
    required NetworkMonitor networkMonitor,
  })  : _queue = queue,
        _apiClient = apiClient,
        _database = database,
        _conflictResolver = conflictResolver,
        _networkMonitor = networkMonitor {
    _initNetworkListener();
  }
  
  void _initNetworkListener() {
    _networkMonitor.onConnectivityChanged.listen((isOnline) {
      if (isOnline) {
        _triggerSync();
      }
    });
  }
  
  /// Main sync method - processes pending queue
  Future<SyncResult> sync() async {
    if (!await _networkMonitor.isOnline) {
      return SyncResult.offline();
    }
    
    _syncStatusController.add(SyncStatus.inProgress);
    
    try {
      // 1. Process pending queue (Push)
      final pushResult = await _pushChanges();
      
      // 2. Pull server changes
      final pullResult = await _pullChanges();
      
      // 3. Resolve conflicts
      final conflictResult = await _resolveConflicts(
        pushResult.conflicts,
      );
      
      _syncStatusController.add(SyncStatus.completed);
      
      return SyncResult.success(
        pushed: pushResult.count,
        pulled: pullResult.count,
        conflicts: conflictResult.resolved,
      );
    } catch (e) {
      _syncStatusController.add(SyncStatus.error);
      return SyncResult.failure(e.toString());
    }
  }
  
  Future<PushResult> _pushChanges() async {
    final pendingOps = await _queue.getPendingOperations();
    int successCount = 0;
    List<SyncConflict> conflicts = [];
    
    for (final op in pendingOps) {
      try {
        final result = await _executeOperation(op);
        
        if (result.hasConflict) {
          conflicts.add(result.conflict!);
        } else {
          await _markOperationSynced(op);
          successCount++;
        }
      } catch (e) {
        await _markOperationFailed(op, e.toString());
      }
    }
    
    return PushResult(
      count: successCount,
      conflicts: conflicts,
    );
  }
  
  Future<OperationResult> _executeOperation(SyncOperation op) async {
    switch (op.type) {
      case OperationType.create:
        return await _executeCreate(op);
      case OperationType.update:
        return await _executeUpdate(op);
      case OperationType.delete:
        return await _executeDelete(op);
    }
  }
  
  Future<OperationResult> _executeCreate(SyncOperation op) async {
    final response = await _apiClient.post(
      '/${op.entityType}',
      data: op.payload,
    );
    
    final serverId = response.data['id'];
    
    // Update local record with server ID
    await _database.updateLocalId(
      table: op.entityType,
      localId: op.localId,
      serverId: serverId,
    );
    
    return OperationResult.success();
  }
  
  Future<OperationResult> _executeUpdate(SyncOperation op) async {
    final serverId = await _database.getServerId(
      table: op.entityType,
      localId: op.localId,
    );
    
    try {
      final response = await _apiClient.put(
        '/${op.entityType}/$serverId',
        data: op.payload,
      );
      
      return OperationResult.success();
    } on ConflictException catch (e) {
      // Server has newer version
      return OperationResult.conflict(
        SyncConflict(
          operation: op,
          serverVersion: e.serverData,
          localVersion: op.payload,
        ),
      );
    }
  }
  
  Future<PullResult> _pullChanges() async {
    final lastSync = await _database.getLastSyncTime();
    
    final response = await _apiClient.get(
      '/sync/changes',
      queryParameters: {
        'since': lastSync?.toIso8601String(),
      },
    );
    
    final serverChanges = response.data['changes'] as List;
    int mergedCount = 0;
    
    for (final change in serverChanges) {
      final shouldMerge = await _shouldMergeChange(change);
      if (shouldMerge) {
        await _mergeServerChange(change);
        mergedCount++;
      }
    }
    
    await _database.setLastSyncTime(DateTime.now());
    
    return PullResult(count: mergedCount);
  }
  
  Future<bool> _shouldMergeChange(Map<String, dynamic> change) async {
    // Check if local has pending changes for same record
    final hasPending = await _queue.hasPendingOperation(
      entityType: change['entity_type'],
      entityId: change['id'],
    );
    
    if (!hasPending) return true;
    
    // If local has pending changes, conflict resolution needed
    return false;
  }
  
  Future<void> _triggerSync() async {
    // Debounce sync calls
    await Future.delayed(const Duration(seconds: 2));
    await sync();
  }
}
```

---

## 5. CONFLICT RESOLUTION

### 5.1 Conflict Types

| Type | Scenario | Resolution Strategy |
|------|----------|---------------------|
| **Update-Update** | Both client and server modified same record | Last-write-wins or merge fields |
| **Update-Delete** | Client updated, server deleted | Prefer delete with notification |
| **Delete-Update** | Client deleted, server updated | Soft delete with warning |
| **Create-Create** | Same ID generated locally and remotely | Reassign local ID |

### 5.2 Conflict Resolution Strategy

```dart
// lib/core/sync/conflict_resolver.dart

class ConflictResolver {
  final SyncPolicy _policy;
  final UserPreferences _preferences;
  
  ConflictResolver({
    required SyncPolicy policy,
    required UserPreferences preferences,
  })  : _policy = policy,
        _preferences = preferences;
  
  /// Resolve a sync conflict
  Future<ConflictResolution> resolve(SyncConflict conflict) async {
    switch (conflict.type) {
      case ConflictType.updateUpdate:
        return _resolveUpdateUpdate(conflict);
      case ConflictType.updateDelete:
        return _resolveUpdateDelete(conflict);
      case ConflictType.deleteUpdate:
        return _resolveDeleteUpdate(conflict);
      case ConflictType.createCreate:
        return _resolveCreateCreate(conflict);
    }
  }
  
  /// Update-Update Conflict: Both sides modified
  Future<ConflictResolution> _resolveUpdateUpdate(SyncConflict conflict) async {
    final localTime = DateTime.parse(conflict.localVersion['updated_at']);
    final serverTime = DateTime.parse(conflict.serverVersion['updated_at']);
    
    switch (_policy.updateConflictStrategy) {
      case ConflictStrategy.lastWriteWins:
        // Use whichever is newer
        if (serverTime.isAfter(localTime)) {
          return ConflictResolution.acceptServer(conflict.serverVersion);
        } else {
          return ConflictResolution.keepLocal(conflict.localVersion);
        }
        
      case ConflictStrategy.merge:
        // Merge non-conflicting fields
        final merged = {...conflict.serverVersion};
        conflict.localVersion.forEach((key, value) {
          if (!conflict.serverVersion.containsKey(key) ||
              conflict.serverVersion[key] == value) {
            merged[key] = value;
          }
        });
        return ConflictResolution.merge(merged);
        
      case ConflictStrategy.manual:
        // Queue for user resolution
        return ConflictResolution.deferToUser(conflict);
        
      case ConflictStrategy.serverWins:
        return ConflictResolution.acceptServer(conflict.serverVersion);
        
      case ConflictStrategy.localWins:
        return ConflictResolution.keepLocal(conflict.localVersion);
    }
  }
  
  /// Update-Delete Conflict: Local updated, server deleted
  Future<ConflictResolution> _resolveUpdateDelete(SyncConflict conflict) async {
    // By default, respect server delete but notify user
    return ConflictResolution.acceptServerDelete(
      notifyUser: true,
      backupData: conflict.localVersion,
    );
  }
  
  /// Delete-Update Conflict: Local deleted, server updated
  Future<ConflictResolution> _resolveDeleteUpdate(SyncConflict conflict) async {
    // Soft delete with warning
    return ConflictResolution.softDeleteWithWarning(
      serverVersion: conflict.serverVersion,
    );
  }
  
  /// Create-Create Conflict: Duplicate IDs
  Future<ConflictResolution> _resolveCreateCreate(SyncConflict conflict) async {
    // Reassign local ID
    final newLocalId = _generateNewId();
    return ConflictResolution.reassignLocalId(newLocalId);
  }
}

/// Conflict resolution policies by entity type
class SyncPolicy {
  final ConflictStrategy updateConflictStrategy;
  final bool notifyOnConflict;
  final bool autoResolveMinorConflicts;
  
  const SyncPolicy({
    required this.updateConflictStrategy,
    this.notifyOnConflict = true,
    this.autoResolveMinorConflicts = true,
  });
  
  static const milkProduction = SyncPolicy(
    updateConflictStrategy: ConflictStrategy.lastWriteWins,
    notifyOnConflict: false,
    autoResolveMinorConflicts: true,
  );
  
  static const animalProfile = SyncPolicy(
    updateConflictStrategy: ConflictStrategy.merge,
    notifyOnConflict: true,
    autoResolveMinorConflicts: false,
  );
  
  static const healthRecords = SyncPolicy(
    updateConflictStrategy: ConflictStrategy.localWins,
    notifyOnConflict: true,
    autoResolveMinorConflicts: false,
  );
}

enum ConflictStrategy {
  lastWriteWins,
  merge,
  manual,
  serverWins,
  localWins,
}
```

---

## 6. LOCAL DATABASE DESIGN

### 6.1 Database Schema

```dart
// lib/core/database/sync_database.dart

import 'package:drift/drift.dart';

part 'sync_database.g.dart';

/// Sync status for all entities
enum SyncStatus {
  synced,      // Matches server
  pending,     // Waiting to sync
  syncing,     // Currently syncing
  failed,      // Sync failed
  conflict,    // Has conflict needing resolution
}

/// Animals table with sync support
class Animals extends Table {
  TextColumn get id => text()();
  TextColumn get serverId => text().nullable()();
  TextColumn get rfidTag => text().nullable()();
  TextColumn get name => text()();
  TextColumn get breed => text().nullable()();
  TextColumn get status => text()();
  IntColumn get ageMonths => integer().nullable()();
  TextColumn get barnId => text().nullable()();
  
  // Sync fields
  IntColumn get syncStatus => intEnum<SyncStatus>()();
  DateTimeColumn get lastLocalChange => dateTime()();
  DateTimeColumn get lastSync => dateTime().nullable()();
  TextColumn get syncError => text().nullable()();
  IntColumn get version => integer().withDefault(const Constant(1))();
  BoolColumn get isDeleted => boolean().withDefault(const Constant(false))();
  
  @override
  Set<Column> get primaryKey => {id};
}

/// Milk production records with sync support
class MilkProductions extends Table {
  TextColumn get localId => text()();
  TextColumn get serverId => text().nullable()();
  TextColumn get animalId => text()();
  DateTimeColumn get productionDate => dateTime()();
  TextColumn get session => text()(); // morning, evening, night
  RealColumn get quantityLiters => real()();
  RealColumn get fatPercentage => real().nullable()();
  RealColumn get snfPercentage => real().nullable()();
  DateTimeColumn get recordedAt => dateTime()();
  TextColumn get recordedBy => text()();
  
  // Sync fields
  IntColumn get syncStatus => intEnum<SyncStatus>()();
  DateTimeColumn get lastSync => dateTime().nullable()();
  TextColumn get syncError => text().nullable()();
  IntColumn get retryCount => integer().withDefault(const Constant(0))();
  
  @override
  Set<Column> get primaryKey => {localId};
}

/// Pending sync operations queue
class PendingSyncs extends Table {
  TextColumn get id => text()();
  TextColumn get entityType => text()(); // 'animal', 'milk_production', etc.
  TextColumn get entityId => text()();   // local ID
  TextColumn get operation => text()();  // 'create', 'update', 'delete'
  TextColumn get payload => text()();    // JSON payload
  DateTimeColumn get createdAt => dateTime()();
  IntColumn get priority => integer().withDefault(const Constant(5))();
  IntColumn get retryCount => integer().withDefault(const Constant(0))();
  DateTimeColumn get lastAttempt => dateTime().nullable()();
  TextColumn get lastError => text().nullable()();
  
  @override
  Set<Column> get primaryKey => {id};
}

/// Sync metadata
class SyncMetadata extends Table {
  TextColumn get key => text()();
  TextColumn get value => text()();
  DateTimeColumn get updatedAt => dateTime()();
  
  @override
  Set<Column> get primaryKey => {key};
}

@DriftDatabase(tables: [Animals, MilkProductions, PendingSyncs, SyncMetadata])
class SyncDatabase extends _$SyncDatabase {
  SyncDatabase(super.executor);
  
  @override
  int get schemaVersion => 1;
  
  // Animal queries
  Future<List<Animal>> getAllAnimals() => select(animals).get();
  
  Future<List<Animal>> getPendingAnimals() {
    return (select(animals)
          ..where((a) => a.syncStatus.equals(SyncStatus.pending.index)))
        .get();
  }
  
  Future<void> insertOrUpdateAnimal(AnimalsCompanion animal) {
    return into(animals).insertOnConflictUpdate(animal);
  }
  
  Future<void> markAnimalSynced(String localId, String serverId) {
    return update(animals).replace(
      Animal(
        id: localId,
        serverId: serverId,
        syncStatus: SyncStatus.synced,
        lastSync: DateTime.now(),
      ),
    );
  }
  
  // Milk production queries
  Future<List<MilkProduction>> getTodaysProduction() {
    final today = DateTime.now();
    return (select(milkProductions)
          ..where((m) => m.productionDate.equals(today)))
        .get();
  }
  
  Future<List<MilkProduction>> getPendingMilkRecords() {
    return (select(milkProductions)
          ..where((m) => m.syncStatus.equals(SyncStatus.pending.index)))
        .get();
  }
  
  // Pending sync queue queries
  Future<List<PendingSync>> getPendingSyncs() {
    return (select(pendingSyncs)
          ..orderBy([(s) => OrderingTerm(expression: s.priority)]))
        .get();
  }
  
  Future<void> addPendingSync(PendingSyncsCompanion sync) {
    return into(pendingSyncs).insert(sync);
  }
  
  Future<void> removePendingSync(String id) {
    return (delete(pendingSyncs)..where((s) => s.id.equals(id))).go();
  }
  
  Future<void> incrementRetryCount(String id) {
    return customStatement('''
      UPDATE pending_syncs 
      SET retry_count = retry_count + 1, 
          last_attempt = CURRENT_TIMESTAMP 
      WHERE id = ?
    ''', [id]);
  }
  
  // Sync metadata
  Future<DateTime?> getLastSyncTime() async {
    final result = await (select(syncMetadata)
          ..where((m) => m.key.equals('last_full_sync')))
        .getSingleOrNull();
    return result != null ? DateTime.parse(result.value) : null;
  }
  
  Future<void> setLastSyncTime(DateTime time) {
    return into(syncMetadata).insertOnConflictUpdate(
      SyncMetadataCompanion(
        key: const Value('last_full_sync'),
        value: Value(time.toIso8601String()),
        updatedAt: Value(DateTime.now()),
      ),
    );
  }
}
```

---

## 7. SYNC ENGINE IMPLEMENTATION

### 7.1 Sync Queue Manager

```dart
// lib/core/sync/sync_queue.dart

class SyncQueue {
  final SyncDatabase _database;
  final Uuid _uuid = const Uuid();
  
  SyncQueue(this._database);
  
  /// Add operation to sync queue
  Future<String> enqueue(
    OperationType operation, {
    required String entityType,
    required String localId,
    Map<String, dynamic>? payload,
    int priority = 5,
  }) async {
    final id = _uuid.v4();
    
    await _database.addPendingSync(
      PendingSyncsCompanion(
        id: Value(id),
        entityType: Value(entityType),
        entityId: Value(localId),
        operation: Value(operation.name),
        payload: Value(payload != null ? jsonEncode(payload) : null),
        createdAt: Value(DateTime.now()),
        priority: Value(priority),
      ),
    );
    
    return id;
  }
  
  /// Get all pending operations
  Future<List<PendingSync>> getPendingOperations() {
    return _database.getPendingSyncs();
  }
  
  /// Get high priority operations (failures, critical data)
  Future<List<PendingSync>> getHighPriorityOperations() {
    return (select(_database.pendingSyncs)
          ..where((s) => s.priority.isSmallerOrEqualValue(3))
          ..orderBy([(s) => OrderingTerm(expression: s.createdAt)]))
        .get();
  }
  
  /// Mark operation as completed
  Future<void> markCompleted(String operationId) {
    return _database.removePendingSync(operationId);
  }
  
  /// Mark operation as failed
  Future<void> markFailed(String operationId, String error) async {
    final operation = await (select(_database.pendingSyncs)
          ..where((s) => s.id.equals(operationId)))
        .getSingle();
    
    // If retry count < 3, keep in queue with incremented count
    if (operation.retryCount < 3) {
      await _database.incrementRetryCount(operationId);
    } else {
      // Max retries reached - mark for manual review
      await customUpdate(
        'UPDATE pending_syncs SET last_error = ? WHERE id = ?',
        variables: [Variable(error), Variable(operationId)],
      );
    }
  }
  
  /// Check if entity has pending operations
  Future<bool> hasPendingOperation({
    required String entityType,
    required String entityId,
  }) async {
    final count = await (select(_database.pendingSyncs)
          ..where((s) => 
            s.entityType.equals(entityType) & 
            s.entityId.equals(entityId)
          ))
        .get();
    return count.isNotEmpty;
  }
  
  /// Get queue statistics
  Future<QueueStats> getStats() async {
    final totalQuery = select(_database.pendingSyncs).get();
    final highPriorityQuery = getHighPriorityOperations();
    final failedQuery = (select(_database.pendingSyncs)
          ..where((s) => s.retryCount.isBiggerOrEqualValue(3)))
        .get();
    
    final results = await Future.wait([totalQuery, highPriorityQuery, failedQuery]);
    
    return QueueStats(
      totalPending: results[0].length,
      highPriority: results[1].length,
      failed: results[2].length,
    );
  }
  
  /// Clear all pending operations (use with caution)
  Future<void> clearQueue() {
    return delete(_database.pendingSyncs).go();
  }
}

class QueueStats {
  final int totalPending;
  final int highPriority;
  final int failed;
  
  QueueStats({
    required this.totalPending,
    required this.highPriority,
    required this.failed,
  });
}

enum OperationType {
  create,
  update,
  delete,
}
```

---

## 8. QUEUE MANAGEMENT

### 8.1 Queue Prioritization

| Priority | Entity Type | Reason |
|----------|-------------|--------|
| 1 | Health emergencies | Critical animal welfare |
| 2 | Milk production | Daily business operations |
| 3 | Breeding records | Reproductive timing critical |
| 5 | Animal updates | Standard updates |
| 7 | Feed records | Lower urgency |
| 10 | Analytics/usage | Background only |

### 8.2 Background Sync Worker

```dart
// lib/core/sync/background_sync_worker.dart

import 'package:workmanager/workmanager.dart';

const String syncTaskName = 'smartdairy.backgroundSync';

@pragma('vm:entry-point')
void callbackDispatcher() {
  Workmanager().executeTask((task, inputData) async {
    switch (task) {
      case syncTaskName:
        return await _performBackgroundSync();
      default:
        return Future.value(true);
    }
  });
}

Future<bool> _performBackgroundSync() async {
  try {
    // Initialize dependencies
    final container = await buildDependencyContainer();
    final syncEngine = container<SyncEngine>();
    
    // Check network
    final connectivity = container<Connectivity>();
    if (!await _isGoodConnection(connectivity)) {
      return true; // Reschedule for later
    }
    
    // Perform sync
    final result = await syncEngine.sync();
    
    // Show notification if conflicts or failures
    if (result.hasConflicts || result.failedCount > 0) {
      await _showSyncNotification(result);
    }
    
    return true;
  } catch (e) {
    return false; // Retry later
  }
}

Future<bool> _isGoodConnection(Connectivity connectivity) async {
  final result = await connectivity.checkConnectivity();
  if (result == ConnectivityResult.none) return false;
  
  // Prefer WiFi or unmetered connections for background sync
  return result == ConnectivityResult.wifi;
}

class BackgroundSyncScheduler {
  Future<void> initialize() async {
    await Workmanager().initialize(callbackDispatcher);
  }
  
  Future<void> schedulePeriodicSync() async {
    await Workmanager().registerPeriodicTask(
      'periodic-sync',
      syncTaskName,
      frequency: const Duration(minutes: 15),
      constraints: Constraints(
        networkType: NetworkType.connected,
        requiresBatteryNotLow: true,
      ),
    );
  }
  
  Future<void> scheduleImmediateSync() async {
    await Workmanager().registerOneOffTask(
      'immediate-sync',
      syncTaskName,
      constraints: Constraints(
        networkType: NetworkType.connected,
      ),
    );
  }
  
  Future<void> cancelSync() async {
    await Workmanager().cancelAll();
  }
}
```

---

## 9. NETWORK AWARENESS

### 9.1 Network Monitor

```dart
// lib/core/network/network_monitor.dart

class NetworkMonitor {
  final Connectivity _connectivity;
  final StreamController<NetworkStatus> _statusController;
  
  Stream<NetworkStatus> get status => _statusController.stream;
  
  NetworkMonitor(this._connectivity)
      : _statusController = StreamController<NetworkStatus>.broadcast();
  
  void initialize() {
    _connectivity.onConnectivityChanged.listen((result) {
      final status = _mapToNetworkStatus(result);
      _statusController.add(status);
    });
  }
  
  NetworkStatus _mapToNetworkStatus(ConnectivityResult result) {
    switch (result) {
      case ConnectivityResult.wifi:
        return NetworkStatus.wifi;
      case ConnectivityResult.mobile:
        return NetworkStatus.cellular;
      case ConnectivityResult.none:
        return NetworkStatus.offline;
      default:
        return NetworkStatus.unknown;
    }
  }
  
  Future<bool> get isOnline async {
    final result = await _connectivity.checkConnectivity();
    return result != ConnectivityResult.none;
  }
  
  Future<bool> get isWifi async {
    final result = await _connectivity.checkConnectivity();
    return result == ConnectivityResult.wifi;
  }
  
  void dispose() {
    _statusController.close();
  }
}

enum NetworkStatus {
  wifi,
  cellular,
  offline,
  unknown,
}

/// Widget that rebuilds based on network status
class NetworkAwareBuilder extends StatelessWidget {
  final Widget online;
  final Widget offline;
  final Widget? cellular;
  
  const NetworkAwareBuilder({
    super.key,
    required this.online,
    required this.offline,
    this.cellular,
  });
  
  @override
  Widget build(BuildContext context) {
    return StreamBuilder<NetworkStatus>(
      stream: context.read<NetworkMonitor>().status,
      initialData: NetworkStatus.unknown,
      builder: (context, snapshot) {
        final status = snapshot.data;
        
        switch (status) {
          case NetworkStatus.wifi:
            return online;
          case NetworkStatus.cellular:
            return cellular ?? online;
          case NetworkStatus.offline:
            return offline;
          default:
            return online;
        }
      },
    );
  }
}
```

### 9.2 Sync Status Indicator

```dart
// lib/core/widgets/sync_status_indicator.dart

class SyncStatusIndicator extends StatelessWidget {
  const SyncStatusIndicator({super.key});
  
  @override
  Widget build(BuildContext context) {
    return StreamBuilder<SyncState>(
      stream: context.read<SyncEngine>().syncState,
      builder: (context, snapshot) {
        final state = snapshot.data ?? SyncState.idle;
        
        return AnimatedSwitcher(
          duration: const Duration(milliseconds: 300),
          child: _buildIndicator(state),
        );
      },
    );
  }
  
  Widget _buildIndicator(SyncState state) {
    switch (state) {
      case SyncState.idle:
        return const _SyncedIndicator();
      case SyncState.syncing:
        return const _SyncingIndicator();
      case SyncState.pending:
        return const _PendingIndicator();
      case SyncState.error:
        return const _ErrorIndicator();
      case SyncState.offline:
        return const _OfflineIndicator();
    }
  }
}

class _SyncedIndicator extends StatelessWidget {
  const _SyncedIndicator();
  
  @override
  Widget build(BuildContext context) {
    return Tooltip(
      message: 'All data synced',
      child: Icon(
        Icons.cloud_done,
        color: Colors.green[600],
        size: 20,
      ),
    );
  }
}

class _SyncingIndicator extends StatelessWidget {
  const _SyncingIndicator();
  
  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        SizedBox(
          width: 16,
          height: 16,
          child: CircularProgressIndicator(
            strokeWidth: 2,
            valueColor: AlwaysStoppedAnimation<Color>(Colors.blue[600]!),
          ),
        ),
        const SizedBox(width: 4),
        Text(
          'Syncing...',
          style: TextStyle(
            color: Colors.blue[600],
            fontSize: 12,
          ),
        ),
      ],
    );
  }
}

class _PendingIndicator extends StatelessWidget {
  const _PendingIndicator();
  
  @override
  Widget build(BuildContext context) {
    return Tooltip(
      message: 'Changes waiting to sync',
      child: Badge(
        child: Icon(
          Icons.cloud_upload,
          color: Colors.orange[600],
          size: 20,
        ),
      ),
    );
  }
}

class _OfflineIndicator extends StatelessWidget {
  const _OfflineIndicator();
  
  @override
  Widget build(BuildContext context) {
    return Tooltip(
      message: 'Offline - changes saved locally',
      child: Icon(
        Icons.cloud_off,
        color: Colors.grey[600],
        size: 20,
      ),
    );
  }
}

class _ErrorIndicator extends StatelessWidget {
  const _ErrorIndicator();
  
  @override
  Widget build(BuildContext context) {
    return Tooltip(
      message: 'Sync error - tap to retry',
      child: GestureDetector(
        onTap: () => context.read<SyncEngine>().sync(),
        child: Icon(
          Icons.cloud_off,
          color: Colors.red[600],
          size: 20,
        ),
      ),
    );
  }
}
```

---

## 10. TESTING OFFLINE SCENARIOS

### 10.1 Test Cases

| Scenario | Test Steps | Expected Result |
|----------|-----------|-----------------|
| **Offline Milk Recording** | 1. Turn off WiFi<br>2. Record milk production<br>3. Check sync status | Record saved locally, marked as pending |
| **Online Sync** | 1. Turn on WiFi<br>2. Wait for auto-sync<br>3. Verify server data | Pending records sync to server |
| **Conflict Resolution** | 1. Create offline record<br>2. Modify same record on server<br>3. Sync | Conflict detected, resolved per policy |
| **App Restart Offline** | 1. Record offline data<br>2. Kill app<br>3. Restart offline | Data persisted, ready to sync |
| **Queue Recovery** | 1. Queue multiple operations<br>2. Kill app mid-sync<br>3. Restart | Queue intact, sync resumes |

### 10.2 Integration Test Example

```dart
// test/integration/offline_sync_test.dart

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();
  
  group('Offline Sync Integration Tests', () {
    late SyncEngine syncEngine;
    late SyncDatabase database;
    late MockApiClient mockApi;
    
    setUp(() async {
      // Initialize test dependencies
      database = SyncDatabase(NativeDatabase.memory());
      mockApi = MockApiClient();
      syncEngine = SyncEngine(
        queue: SyncQueue(database),
        apiClient: mockApi,
        database: database,
        conflictResolver: ConflictResolver(
          policy: SyncPolicy.milkProduction,
          preferences: MockPreferences(),
        ),
        networkMonitor: MockNetworkMonitor(),
      );
    });
    
    testWidgets('Record milk production offline and sync', (tester) async {
      // Simulate offline state
      await mockApi.setConnectivity(false);
      
      // Record milk production
      final record = MilkProductionRecord(
        animalId: 'SD-001',
        quantityLiters: 20.5,
        session: 'morning',
      );
      
      await syncEngine.create(record);
      
      // Verify local storage
      final pending = await database.getPendingMilkRecords();
      expect(pending.length, 1);
      expect(pending.first.syncStatus, SyncStatus.pending);
      
      // Restore connectivity
      await mockApi.setConnectivity(true);
      
      // Trigger sync
      final result = await syncEngine.sync();
      
      // Verify sync success
      expect(result.pushed, 1);
      
      final synced = await database.getPendingMilkRecords();
      expect(synced.isEmpty, true);
    });
  });
}
```

---

## 11. PERFORMANCE OPTIMIZATION

### 11.1 Performance Targets

| Metric | Target | Measurement |
|--------|--------|-------------|
| Local write latency | < 50ms | From user action to local save |
| Sync batch size | 100 records | Per sync request |
| Initial sync time | < 30s | Full data download |
| Database size | < 500MB | Local storage limit |
| Memory usage | < 100MB | During sync operations |

### 11.2 Optimization Strategies

```dart
// Database indexing
@DriftDatabase(
  tables: [Animals, MilkProductions, PendingSyncs],
  indexes: [
    // Index for pending sync queries
    Index('idx_pending_sync_status', 'CREATE INDEX idx_pending_sync_status ON pending_syncs(sync_status)'),
    // Index for date range queries
    Index('idx_milk_production_date', 'CREATE INDEX idx_milk_production_date ON milk_productions(production_date)'),
  ],
)
```

---

## 12. APPENDICES

### Appendix A: Sync Error Codes

| Code | Description | User Action |
|------|-------------|-------------|
| SYNC_001 | Network timeout | Auto-retry |
| SYNC_002 | Server error | Auto-retry |
| SYNC_003 | Authentication failed | Re-login required |
| SYNC_004 | Conflict detected | User resolution |
| SYNC_005 | Data validation failed | Review data |
| SYNC_006 | Max retries exceeded | Manual review |

### Appendix B: Data Retention

| Data Type | Local Retention | Server Retention |
|-----------|-----------------|------------------|
| Milk production | 90 days | 2 years |
| Animal profiles | Full herd | Full history |
| Health records | 1 year | 7 years |
| Sync logs | 30 days | 90 days |
| Error logs | 7 days | 30 days |

---

**END OF OFFLINE-FIRST ARCHITECTURE**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Mar 21, 2026 | Mobile Lead | Initial version |
