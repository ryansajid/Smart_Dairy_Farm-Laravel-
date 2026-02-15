# SMART DAIRY LTD.
## BACKUP & RECOVERY RUNBOOK
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | L-008 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Backup Administrator |
| **Owner** | Backup Administrator |
| **Reviewer** | IT Director |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Backup Strategy](#2-backup-strategy)
3. [Backup Scope](#3-backup-scope)
4. [Backup Schedule](#4-backup-schedule)
5. [Backup Storage](#5-backup-storage)
6. [Backup Verification](#6-backup-verification)
7. [Recovery Procedures](#7-recovery-procedures)
8. [Recovery Testing](#8-recovery-testing)
9. [Backup Monitoring](#9-backup-monitoring)
10. [Retention Policy](#10-retention-policy)
11. [Emergency Contacts](#11-emergency-contacts)
12. [Appendices](#12-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This runbook provides step-by-step operational procedures for backup operations and recovery execution for Smart Dairy's Smart Web Portal System and Integrated ERP. It serves as the primary reference for IT operations staff during routine backup operations and emergency recovery situations.

### 1.2 Scope

This document covers:
- All automated and manual backup procedures
- Recovery workflows for various disaster scenarios
- Verification and testing protocols
- Monitoring and alerting procedures
- Emergency response steps

### 1.3 Recovery Objectives

| Metric | Target | Business Justification |
|--------|--------|------------------------|
| **RPO (Recovery Point Objective)** | 15 minutes | Maximum acceptable data loss; critical for milk production tracking and financial transactions |
| **RTO - Critical Services** | 1 hour | ERP, Database, E-commerce, IoT Core - direct revenue impact |
| **RTO - Important Services** | 4 hours | B2B Portal, Analytics - operational impact |
| **RTO - Complete System** | 8 hours | Full business continuity restoration |

### 1.4 Document Hierarchy

```
┌─────────────────────────────────────────────────────────────┐
│              DOCUMENT HIERARCHY                              │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌─────────────────────────────────────────────────────┐   │
│  │  D-012 Disaster Recovery Plan (Strategic)            │   │
│  │  • DR architecture and decision framework            │   │
│  └──────────────────────┬──────────────────────────────┘   │
│                         │                                    │
│  ┌──────────────────────▼──────────────────────────────┐   │
│  │  C-010 Backup & Recovery Procedures (Technical)      │   │
│  │  • Detailed technical procedures and scripts         │   │
│  └──────────────────────┬──────────────────────────────┘   │
│                         │                                    │
│  ┌──────────────────────▼──────────────────────────────┐   │
│  │  L-008 Backup & Recovery Runbook (Operational)       │   │
│  │  • Day-to-day operational procedures                 │   │
│  │  • Step-by-step recovery workflows                   │   │
│  │  • Checklists and quick reference                    │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. BACKUP STRATEGY

### 2.1 Backup Types Overview

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         BACKUP STRATEGY OVERVIEW                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                    BACKUP FREQUENCY PYRAMID                            │  │
│  │                                                                         │  │
│  │                    ┌─────────────────┐                                 │  │
│  │                    │  FULL BACKUP    │  ← Weekly (Sundays 02:00)       │  │
│  │                    │  Complete data  │     Retention: 90 days          │  │
│  │                    │  snapshot       │                                 │  │
│  │                    └────────┬────────┘                                 │  │
│  │                             │                                           │  │
│  │              ┌──────────────┼──────────────┐                          │  │
│  │              ▼              ▼              ▼                          │  │
│  │       ┌────────────┐ ┌────────────┐ ┌────────────┐                   │  │
│  │       │ INCREMENTAL│ │ INCREMENTAL│ │ INCREMENTAL│                   │  │
│  │       │ Daily      │ │ Daily      │ │ Daily      │  ← Mon-Sat 02:00  │  │
│  │       │ Changes    │ │ Changes    │ │ Changes    │                   │  │
│  │       └────────────┘ └────────────┘ └────────────┘                   │  │
│  │                             │                                           │  │
│  │              ┌──────────────┼──────────────┐                          │  │
│  │              ▼              ▼              ▼                          │  │
│  │       ┌────────────┐ ┌────────────┐ ┌────────────┐                   │  │
│  │       │   TX LOG   │ │   TX LOG   │ │   TX LOG   │                   │  │
│  │       │  Hourly    │ │  Hourly    │ │  Hourly    │  ← Every hour     │  │
│  │       │  Every :00 │ │  Every :00 │ │  Every :00 │     Retention: 7d │  │
│  │       └────────────┘ └────────────┘ └────────────┘                   │  │
│  │                                                                         │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │  SNAPSHOT BACKUPS                                                     │  │
│  │  • On-demand before major changes                                    │  │
│  │  • Pre-deployment backups                                            │  │
│  │  • Monthly system snapshots                                          │  │
│  │  • Retention: 30 days                                                │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Full Backups (Weekly)

**Schedule:** Every Sunday at 02:00 AM (Asia/Dhaka timezone)

**Characteristics:**
| Attribute | Specification |
|-----------|---------------|
| **Frequency** | Weekly |
| **Schedule** | Sundays 02:00 AM BDT |
| **Type** | Full logical backup (pg_dump) |
| **Compression** | gzip level 9 |
| **Encryption** | AES-256 server-side encryption |
| **Retention** | 90 days |
| **Destination** | S3 ap-south-1 (Primary), ap-southeast-1 (DR) |

**Full Backup Workflow:**
```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      FULL BACKUP WORKFLOW                                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  02:00 │ ┌─────────────┐                                                     │
│        │ │   START     │ Triggered by cron                                   │
│        │ └──────┬──────┘                                                     │
│        │        ▼                                                              │
│  02:05 │ ┌─────────────┐                                                     │
│        │ │ Notify Team │ Slack notification: "Full backup starting"          │
│        │ └──────┬──────┘                                                     │
│        │        ▼                                                              │
│  02:10 │ ┌─────────────┐                                                     │
│        │ │ Set Maint.  │ Maintenance mode ON (if configured)                 │
│        │ │ Mode (Opt)  │                                                     │
│        │ └──────┬──────┘                                                     │
│        │        ▼                                                              │
│  02:15 │ ┌─────────────┐                                                     │
│        │ │ pg_dump     │ Custom format, parallel (4 jobs)                    │
│        │ │ Execution   │ Includes blobs, clean restore option                │
│        │ └──────┬──────┘                                                     │
│        │        ▼                                                              │
│  02:45 │ ┌─────────────┐                                                     │
│        │ │ Compress    │ gzip compression                                    │
│        │ └──────┬──────┘                                                     │
│        │        ▼                                                              │
│  02:50 │ ┌─────────────┐                                                     │
│        │ │ Checksum    │ SHA-256 hash generation                             │
│        │ └──────┬──────┘                                                     │
│        │        ▼                                                              │
│  02:55 │ ┌─────────────┐                                                     │
│        │ │ Upload S3   │ Primary region + Cross-region replication          │
│        │ └──────┬──────┘                                                     │
│        │        ▼                                                              │
│  03:00 │ ┌─────────────┐                                                     │
│        │ │ Verify      │ S3 object integrity check                           │
│        │ └──────┬──────┘                                                     │
│        │        ▼                                                              │
│  03:05 │ ┌─────────────┐                                                     │
│        │ │ Maint. Off  │ Maintenance mode OFF                                │
│        │ └──────┬──────┘                                                     │
│        │        ▼                                                              │
│  03:10 │ ┌─────────────┐                                                     │
│        │ │ Notify      │ Success/failure notification                        │
│        │ │ Complete    │ Update backup log                                   │
│        │ └─────────────┘                                                     │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.3 Incremental Backups (Daily)

**Schedule:** Monday through Saturday at 02:00 AM BDT

**Characteristics:**
| Attribute | Specification |
|-----------|---------------|
| **Frequency** | Daily (Mon-Sat) |
| **Type** | Differential incremental (restic) |
| **Source** | Odoo filestore, configuration files |
| **Retention** | 30 days (restic snapshots) |
| **Deduplication** | Enabled via restic |

### 2.4 Transaction Log Backups (Hourly)

**Schedule:** Every hour at :00 minutes

**Characteristics:**
| Attribute | Specification |
|-----------|---------------|
| **Frequency** | Hourly |
| **Type** | PostgreSQL WAL (Write-Ahead Log) archiving |
| **Mechanism** | Continuous streaming to S3 |
| **Retention** | 35 days |
| **Purpose** | Point-in-time recovery (PITR) |

**WAL Archiving Flow:**
```
┌─────────────────────────────────────────────────────────────────────────────┐
│                     WAL ARCHITECTURE                                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│   PostgreSQL                    Archive Command                    S3        │
│   ┌─────────┐                   ┌─────────────┐              ┌──────────┐   │
│   │  WAL    │ ──archive_command─▶│ wal-archive │ ──aws s3 cp─▶│  S3 WAL  │   │
│   │  Files  │                   │   script    │              │  Bucket  │   │
│   │ pg_wal/ │                   └─────────────┘              └────┬─────┘   │
│   └────┬────┘                                                    │         │
│        │                                                         │         │
│        │              ┌──────────────────────────────────────────┘         │
│        │              │                                                    │
│        │              ▼                                                    │
│        │   ┌─────────────────────┐                                         │
│        └──▶│  archive_timeout    │ 10 minutes (force switch if idle)       │
│            │  (10 min)           │                                         │
│            └─────────────────────┘                                         │
│                                                                              │
│   ┌───────────────────────────────────────────────────────────────────────┐│
│   │  ARCHIVE STATUS MONITORING                                             ││
│   │  • Check lag: SELECT * FROM pg_stat_archiver;                          ││
│   │  • Alert if: last_failed_time > 5 minutes                              ││
│   │  • Archive rate: ~16MB WAL segments every 10 min (typical)             ││
│   └───────────────────────────────────────────────────────────────────────┘│
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 2.5 Snapshot Backups

**Trigger Conditions:**
| Event | Snapshot Type | Retention |
|-------|---------------|-----------|
| Pre-deployment | Application snapshot | 30 days |
| Major DB migration | Database snapshot | 90 days |
| Monthly maintenance | System-wide snapshot | 30 days |
| On-demand request | Ad-hoc snapshot | Per request |

**Manual Snapshot Procedure:**
```bash
# Create on-demand snapshot
/opt/smart-dairy/scripts/backup/create-snapshot.sh \
  --name "pre-migration-$(date +%Y%m%d)" \
  --type full \
  --retention-days 90
```

---

## 3. BACKUP SCOPE

### 3.1 Backup Coverage Matrix

| Data Category | Components | Backup Type | Frequency | Priority |
|---------------|------------|-------------|-----------|----------|
| **Database** | PostgreSQL (Application Data) | Full + WAL | Daily + Hourly | Critical |
| **Database** | TimescaleDB (IoT Metrics) | Full + Continuous | Daily | Critical |
| **Application Files** | Odoo Filestore | Incremental | Daily | Critical |
| **Application Files** | Static Assets | Incremental | Daily | High |
| **Configuration** | Kubernetes Manifests | GitOps | On-change | Critical |
| **Configuration** | Environment Configs | Versioned | On-change | Critical |
| **IoT Data** | MQTT Broker State | Snapshot | Daily | High |
| **IoT Data** | Sensor Raw Data | TimescaleDB | Continuous | High |
| **User Uploads** | Customer Documents | Incremental | Daily | High |
| **User Uploads** | Product Images | S3 Versioning | Continuous | Medium |
| **Logs** | Application Logs | Archive | Weekly | Low |
| **Logs** | Audit Logs | Archive | Daily | High |

### 3.2 PostgreSQL Database Backup

**Databases Included:**
```sql
-- Primary application database
smart_dairy_prod      -- Main ERP data
smart_dairy_inventory -- Inventory management
smart_dairy_iot       -- IoT sensor data (TimescaleDB)
smart_dairy_logs      -- Application logs
```

**Backup Exclusions:**
```sql
-- Temporary/cache tables (not backed up)
session_store         -- Session data (rebuildable)
cache_entries         -- Application cache
job_queue_temp        -- Temporary job queue entries
```

### 3.3 Application Files Backup

**Filestore Paths:**
```
/var/lib/odoo/filestore/
├── smart_dairy_prod/
│   ├── attachments/           # User uploaded files
│   ├── products/              # Product images
│   ├── documents/             # Business documents
│   └── temp/                  # Excluded from backup
├── smart_dairy_staging/
└── smart_dairy_test/
```

**Configuration Files:**
```
/etc/odoo/
├── odoo.conf                  # Main configuration
├── odoo-staging.conf
└── odoo-test.conf

/etc/nginx/
├── nginx.conf
├── conf.d/
│   ├── smartdairy.conf
│   └── ssl-params.conf
└── ssl/
    ├── smartdairy.crt
    └── smartdairy.key
```

### 3.4 IoT Data Backup

**IoT Data Sources:**
| Source | Data Type | Backup Method | Frequency |
|--------|-----------|---------------|-----------|
| MQTT Broker | Message queues | Snapshot | Daily |
| TimescaleDB | Sensor readings | Continuous | Real-time |
| IoT Gateway | Device configs | GitOps | On-change |
| Cold Chain | Temperature logs | TimescaleDB | Real-time |

**IoT Data Retention:**
| Data Type | Hot Storage | Warm Storage | Cold Storage |
|-----------|-------------|--------------|--------------|
| Raw sensor data | 7 days | 90 days | 2 years |
| Aggregated metrics | 30 days | 1 year | 5 years |
| Alert logs | 1 year | 3 years | 7 years |

### 3.5 User Uploads Backup

**Upload Categories:**
| Category | Location | Size Estimate | Backup Strategy |
|----------|----------|---------------|-----------------|
| Product Images | S3: smart-dairy-media | ~50GB | S3 versioning + CRR |
| Customer Documents | S3: smart-dairy-documents | ~10GB | Daily incremental |
| Invoice PDFs | S3: smart-dairy-exports | ~5GB | Daily incremental |
| Farm Photos | Filestore | ~20GB | Weekly full |

---

## 4. BACKUP SCHEDULE

### 4.1 Weekly Schedule Matrix

| Day | Time (BDT) | Backup Type | Duration | Systems Affected |
|-----|------------|-------------|----------|------------------|
| **Sunday** | 02:00 | Full Database | 60-90 min | PostgreSQL, TimescaleDB |
| **Sunday** | 02:30 | Filestore Full | 30-60 min | Odoo Filestore |
| **Sunday** | 03:30 | Config Backup | 15 min | Kubernetes, Application configs |
| **Sunday** | 04:00 | Verification | 30 min | All systems |
| **Monday-Saturday** | 02:00 | Incremental | 15-30 min | File changes only |
| **Daily** | Every :00 | WAL Archive | Continuous | PostgreSQL TX logs |
| **Daily** | 01:00 | IoT Data | 20 min | TimescaleDB partitions |
| **Daily** | 03:00 | Kubernetes | 30 min | Cluster state (Velero) |
| **Weekly** | Sunday 05:00 | Log Archive | 60 min | Application logs |

### 4.2 Backup Schedule Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    WEEKLY BACKUP SCHEDULE (SUNDAY)                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Time    Activity                    System                    Status      │
│  ─────────────────────────────────────────────────────────────────────────── │
│                                                                              │
│  02:00 ┌─────────────────────────────────────────────────────────────────┐   │
│        │ ████████████████████████████████████████                        │   │
│        │ Full Database Backup          PostgreSQL                        │   │
│        └─────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  02:30    ┌─────────────────────────────────────────────┐                    │
│           │ ██████████████████████████████████          │                    │
│           │ Filestore Full Backup           Odoo        │                    │
│           └─────────────────────────────────────────────┘                    │
│                                                                              │
│  03:30           ┌─────────────────────┐                                      │
│                  │ ████████████████    │                                      │
│                  │ Config Backup       K8s/GitOps                         │   │
│                  └─────────────────────┘                                      │
│                                                                              │
│  04:00                  ┌─────────────────────┐                               │
│                         │ ████████████████    │                               │
│                         │ Verification        All systems                │   │
│                         └─────────────────────┘                               │
│                                                                              │
│  05:00                              ┌───────────────────────────────────┐    │
│                                     │ █████████████████████████████████│    │
│                                     │ Log Archive              Logs    │    │
│                                     └───────────────────────────────────┘    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│                    DAILY BACKUP SCHEDULE (MON-SAT)                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Time    Activity                    System                    Duration      │
│  ─────────────────────────────────────────────────────────────────────────── │
│  01:00   IoT Data Partition          TimescaleDB               20 min        │
│  02:00   Incremental Backup          Filestore                 15-30 min     │
│  03:00   Kubernetes Backup           Velero                    30 min        │
│  :00     WAL Archive (hourly)        PostgreSQL               Continuous    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 4.3 Cron Configuration

```bash
# /etc/cron.d/smart-dairy-backup
# Backup Schedule for Smart Dairy Production Environment
# Timezone: Asia/Dhaka (BDT, UTC+6)

# Full Database Backup - Sundays at 02:00
0 2 * * 0 postgres /opt/smart-dairy/scripts/backup/postgresql-full-backup.sh >> /var/log/smart-dairy/backup-full.log 2>&1

# Incremental Filestore - Daily at 02:00 (except Sunday full)
0 2 * * 1-6 root /opt/smart-dairy/scripts/backup/filestore-incremental.sh >> /var/log/smart-dairy/backup-incr.log 2>&1

# WAL Verification - Every hour
0 * * * * postgres /opt/smart-dairy/scripts/backup/verify-wal-archive.sh

# IoT Data Backup - Daily at 01:00
0 1 * * * postgres /opt/smart-dairy/scripts/backup/timescaledb-backup.sh

# Kubernetes Backup - Daily at 03:00
0 3 * * * root /opt/smart-dairy/scripts/backup/k8s-backup.sh

# Configuration Backup - Sundays at 03:30
30 3 * * 0 root /opt/smart-dairy/scripts/backup/config-backup.sh

# Backup Verification - Sundays at 04:00
0 4 * * 0 root /opt/smart-dairy/scripts/backup/verify-backups.sh

# Log Archive - Sundays at 05:00
0 5 * * 0 root /opt/smart-dairy/scripts/backup/archive-logs.sh

# Cleanup old backups - Daily at 06:00
0 6 * * * root /opt/smart-dairy/scripts/backup/cleanup-old-backups.sh
```

---

## 5. BACKUP STORAGE

### 5.1 Storage Architecture

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      BACKUP STORAGE ARCHITECTURE                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                    TIERED STORAGE STRATEGY                             │  │
│  │                                                                         │  │
│  │  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐            │  │
│  │  │   HOT        │    │   WARM       │    │   COLD       │            │  │
│  │  │   Tier       │───▶│   Tier       │───▶│   Tier       │            │  │
│  │  │              │    │              │    │              │            │  │
│  │  │ S3 Standard  │    │ S3 Standard- │    │ S3 Glacier   │            │  │
│  │  │ 0-30 days    │    │    IA        │    │ Deep Archive │            │  │
│  │  │              │    │ 30-90 days   │    │ 90+ days     │            │  │
│  │  └──────────────┘    └──────────────┘    └──────────────┘            │  │
│  │         │                   │                   │                      │  │
│  │         ▼                   ▼                   ▼                      │  │
│  │  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐            │  │
│  │  │ Immediate    │    │ Minutes      │    │ Hours        │            │  │
│  │  │ access       │    │ retrieval    │    │ retrieval    │            │  │
│  │  └──────────────┘    └──────────────┘    └──────────────┘            │  │
│  │                                                                         │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │                    MULTI-REGION REPLICATION                            │  │
│  │                                                                         │  │
│  │   PRIMARY REGION                    DR REGION                         │  │
│  │   ap-south-1 (Mumbai)              ap-southeast-1 (Singapore)         │  │
│  │                                                                         │  │
│  │   ┌─────────────────┐               ┌─────────────────┐               │  │
│  │   │ S3 Bucket       │ ════════════▶ │ S3 Bucket       │               │  │
│  │   │ (Source)        │   CRR Async   │ (Replica)       │               │  │
│  │   │                 │               │                 │               │  │
│  │   │ • Backups       │               │ • Replicated    │               │  │
│  │   │ • WAL archives  │               │   backups       │               │  │
│  │   │ • Snapshots     │               │ • DR copies     │               │  │
│  │   └─────────────────┘               └─────────────────┘               │  │
│  │                                                                         │  │
│  │   Replication RPO: <15 minutes (async)                                 │  │
│  │   Encryption: SSE-KMS in transit and at rest                           │  │
│  │                                                                         │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 5.2 Local Storage

**Purpose:** Staging area and fast recovery cache

| Specification | Details |
|---------------|---------|
| **Location** | `/backup` (mounted on backup server) |
| **Capacity** | 2 TB SSD |
| **Usage** | Staging for compression/encryption, recent backup cache |
| **Retention** | Last 7 days of backups |
| **Cleanup** | Automated daily purge |

**Local Storage Structure:**
```
/backup/
├── postgresql/
│   ├── current/               # Symlink to latest backup
│   ├── 20260126_020000/      # Full backup
│   ├── 20260127_020000/
│   └── staging/              # Temporary during backup
├── filestore/
│   └── restic-repo/          # Restic repository
├── config/
│   └── k8s-manifests/        # Kubernetes exports
└── logs/
    └── backup-YYYY-MM-DD.log
```

### 5.3 Cloud Storage (AWS S3)

**S3 Buckets:**

| Bucket Name | Region | Purpose | Storage Class |
|-------------|--------|---------|---------------|
| `smart-dairy-backups-ap-south-1` | ap-south-1 | Primary backup storage | Standard → IA → Glacier |
| `smart-dairy-backups-ap-southeast-1` | ap-southeast-1 | DR replica | Standard |
| `smart-dairy-wal-archive` | ap-south-1 | PostgreSQL WAL | Standard |
| `smart-dairy-velero-backups` | ap-south-1 | Kubernetes (Velero) | Standard |

**S3 Bucket Configuration:**
```json
{
  "Versioning": "Enabled",
  "Encryption": "SSE-KMS",
  "Replication": "Cross-region to ap-southeast-1",
  "ObjectLock": "Compliance mode, 1 day",
  "Lifecycle": {
    "TransitionToIA": "30 days",
    "TransitionToGlacier": "90 days",
    "Expiration": "365 days"
  }
}
```

### 5.4 Offsite Storage

**Offsite Backup Strategy:**

| Component | Method | Frequency | Location |
|-----------|--------|-----------|----------|
| Full DB Backups | Physical tape simulation | Monthly | AWS Glacier Deep Archive |
| Critical Configs | Air-gapped S3 | Weekly | Separate AWS account |
| Encryption Keys | HSM + Manual escrow | Quarterly | Physical safe (HQ) |

**Glacier Deep Archive:**
- Used for: Yearly compliance backups
- Retrieval time: 12-48 hours
- Retention: 7 years
- Cost: Lowest per GB storage

---

## 6. BACKUP VERIFICATION

### 6.1 Automated Checks

**Daily Automated Verification:**

| Check | Schedule | Success Criteria | Alert Action |
|-------|----------|------------------|--------------|
| Backup file exists | Post-backup | File present in S3 | Critical alert |
| Checksum validation | Post-backup | SHA-256 matches | Critical alert |
| File size check | Post-backup | Within ±20% of avg | Warning alert |
| WAL continuity | Hourly | No gaps in sequence | High alert |
| S3 replication | Daily | DR bucket synced | Warning alert |
| Restic snapshots | Daily | Recent snapshot exists | High alert |

**Verification Script:**
```bash
#!/bin/bash
# /opt/smart-dairy/scripts/backup/verify-backups.sh

REPORT_FILE="/var/log/smart-dairy/verification-$(date +%Y%m%d).log"
ALERT_WEBHOOK="https://hooks.slack.com/services/xxx"
FAILED=0

echo "=== Backup Verification Report - $(date) ===" > $REPORT_FILE

# Check 1: Latest PostgreSQL backup
LATEST_PG=$(aws s3 ls s3://smart-dairy-backups-ap-south-1/postgresql/full/ | sort | tail -1 | awk '{print $4}')
if [ -n "$LATEST_PG" ]; then
    echo "✓ PostgreSQL backup found: $LATEST_PG" >> $REPORT_FILE
else
    echo "✗ PostgreSQL backup NOT FOUND" >> $REPORT_FILE
    FAILED=1
fi

# Check 2: WAL archive continuity
WAL_COUNT=$(aws s3 ls s3://smart-dairy-backups-ap-south-1/postgresql/wal/ | wc -l)
if [ $WAL_COUNT -gt 10 ]; then
    echo "✓ WAL archives present: $WAL_COUNT files" >> $REPORT_FILE
else
    echo "✗ WAL archive count low: $WAL_COUNT" >> $REPORT_FILE
    FAILED=1
fi

# Check 3: Checksum validation
if aws s3 ls s3://smart-dairy-backups-ap-south-1/postgresql/full/${LATEST_PG}.sha256 > /dev/null 2>&1; then
    echo "✓ Checksum file present" >> $REPORT_FILE
else
    echo "✗ Checksum file missing" >> $REPORT_FILE
    FAILED=1
fi

# Check 4: Restic snapshots
SNAPSHOTS=$(restic snapshots --json 2>/dev/null | jq length)
if [ "$SNAPSHOTS" -gt 0 ]; then
    echo "✓ Restic snapshots: $SNAPSHOTS" >> $REPORT_FILE
else
    echo "✗ No Restic snapshots found" >> $REPORT_FILE
    FAILED=1
fi

# Send alert if failed
if [ $FAILED -eq 1 ]; then
    curl -X POST -H 'Content-type: application/json' \
        --data '{"text":"CRITICAL: Backup verification FAILED. Check '$REPORT_FILE'"}' \
        $ALERT_WEBHOOK
fi
```

### 6.2 Test Restores

**Monthly Test Restore Schedule:**

| Week | Test Type | Target Environment | Duration |
|------|-----------|-------------------|----------|
| Week 1 | Single table restore | Staging | 30 min |
| Week 2 | Filestore restore | Staging | 1 hour |
| Week 3 | Full DB restore to test instance | Test RDS | 2 hours |
| Week 4 | Point-in-time recovery | Test RDS | 2 hours |

**Test Restore Procedure:**
```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    TEST RESTORE WORKFLOW                                     │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  PREPARATION                                                                 │
│  □ Schedule test window (low impact hours)                                   │
│  □ Notify team of test activity                                              │
│  □ Prepare test environment (clean instance)                                 │
│  □ Select backup to restore (typically 2 days old)                           │
│                                                                              │
│  EXECUTION                                                                   │
│  □ Download backup from S3                                                   │
│  □ Verify checksum before restore                                            │
│  □ Execute restore operation                                                 │
│  □ Monitor progress and log timing                                           │
│                                                                              │
│  VALIDATION                                                                  │
│  □ Database connectivity test                                                │
│  □ Table count verification                                                  │
│  □ Row count sampling (key tables)                                           │
│  □ Application connection test                                               │
│  □ Data integrity spot checks                                                │
│                                                                              │
│  DOCUMENTATION                                                               │
│  □ Record restore time (actual RTO)                                          │
│  □ Document any issues encountered                                           │
│  □ Update runbook if procedure changes needed                                │
│  □ Cleanup test environment                                                  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 6.3 Integrity Verification

**PostgreSQL Integrity Checks:**
```bash
#!/bin/bash
# postgresql-integrity-check.sh

DB_NAME="smart_dairy_prod"
REPORT="/tmp/integrity-report-$(date +%Y%m%d).txt"

echo "PostgreSQL Integrity Check - $(date)" > $REPORT

# Check 1: Data checksums (if enabled)
psql -d $DB_NAME -c "SELECT pg_relation_check('public.res_partner');" >> $REPORT 2>&1

# Check 2: Table bloat analysis
psql -d $DB_NAME -c "
SELECT schemaname, relname, n_dead_tup, n_live_tup,
       round(n_dead_tup::numeric/nullif(n_live_tup,0)*100, 2) as dead_pct
FROM pg_stat_user_tables
WHERE n_dead_tup > 1000
ORDER BY n_dead_tup DESC LIMIT 10;
" >> $REPORT

# Check 3: Index validity
psql -d $DB_NAME -c "
SELECT schemaname, relname, indexrelname
FROM pg_stat_user_indexes
WHERE idx_scan = 0 AND pg_relation_size(indexrelid) > 100000;
" >> $REPORT

# Check 4: Replication lag (if replica exists)
psql -d $DB_NAME -c "SELECT EXTRACT(EPOCH FROM (now() - pg_last_xact_replay_timestamp())) AS lag_seconds;" >> $REPORT
```

**Restic Integrity Check:**
```bash
# Verify restic repository integrity
restic check --read-data-subset=10%  # Check 10% of data monthly
```

---

## 7. RECOVERY PROCEDURES

### 7.1 Recovery Decision Tree

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    RECOVERY DECISION TREE                                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  START: Data Loss or Corruption Detected                                     │
│       │                                                                      │
│       ▼                                                                      │
│  ┌─────────────────────────────────────┐                                     │
│  │ Is the primary region available?    │                                     │
│  └──────────────────┬──────────────────┘                                     │
│        YES │                    │ NO                                          │
│            ▼                    ▼                                            │
│  ┌─────────────────┐   ┌─────────────────────────┐                          │
│  │ What data type? │   │ ACTIVATE DR SITE        │                          │
│  └────────┬────────┘   │ See D-012 DR Plan       │                          │
│           │            └─────────────────────────┘                          │
│     ┌─────┴─────┬──────────┐                                               │
│     ▼           ▼          ▼                                               │
│ ┌────────┐ ┌─────────┐ ┌──────────┐                                        │
│ │ Single │ │ Database│ │ Complete │                                        │
│ │ Record │ │   Loss  │ │  System  │                                        │
│ └────┬───┘ └────┬────┘ └────┬─────┘                                        │
│      │          │           │                                              │
│      ▼          ▼           ▼                                              │
│ ┌──────────┐ ┌──────────┐ ┌──────────────┐                                │
│ │ SQL Query│ │ Point-in-│ │ Full Restore │                                │
│ │  Restore │ │   Time   │ │  from Backup │                                │
│ │  (7.2.1) │ │ Recovery │ │    (7.2.4)   │                                │
│ │          │ │  (7.2.2) │ │              │                                │
│ └──────────┘ └──────────┘ └──────────────┘                                │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 7.2 Point-in-Time Recovery (PITR)

**When to Use:** Database corruption, accidental bulk deletion, ransomware attack

**Prerequisites:**
- Base backup available
- WAL archives available up to target time
- Target time within retention period (35 days)

**PITR Procedure:**
```bash
#!/bin/bash
# /opt/smart-dairy/scripts/recovery/pitr-recovery.sh

# Configuration
TARGET_TIME="$1"  # Format: "2026-01-31 14:30:00+06"
RESTORE_HOST="restore-db.smartdairy.internal"
S3_BUCKET="s3://smart-dairy-backups-ap-south-1/postgresql"

echo "=== Starting PITR Recovery ==="
echo "Target Time: $TARGET_TIME"

# Step 1: Find latest base backup before target time
BASE_BACKUP=$(aws s3 ls ${S3_BUCKET}/full/ | \
    awk '{print $4}' | \
    while read f; do
        backup_time=$(echo $f | grep -oP '\d{8}_\d{6}')
        if [[ "$(date -d $backup_time +%s)" -le "$(date -d "$TARGET_TIME" +%s)" ]]; then
            echo $f
        fi
    done | sort | tail -1)

echo "Base backup: $BASE_BACKUP"

# Step 2: Download base backup
aws s3 cp ${S3_BUCKET}/full/${BASE_BACKUP} /tmp/restore-base.dump.gz
gunzip /tmp/restore-base.dump.gz

# Step 3: Restore base backup
echo "Restoring base backup..."
psql -h $RESTORE_HOST -U postgres -c "DROP DATABASE IF EXISTS smart_dairy_restore;"
psql -h $RESTORE_HOST -U postgres -c "CREATE DATABASE smart_dairy_restore;"
pg_restore -h $RESTORE_HOST -U postgres -d smart_dairy_restore \
    --jobs=4 /tmp/restore-base.dump

# Step 4: Configure recovery.conf for PITR
cat > /tmp/recovery.conf <<EOF
restore_command = 'aws s3 cp ${S3_BUCKET}/wal/%f %p'
recovery_target_time = '${TARGET_TIME}'
recovery_target_action = 'promote'
recovery_target_inclusive = true
EOF

# Step 5: Copy recovery config and restart
cp /tmp/recovery.conf /var/lib/postgresql/data/recovery.conf
systemctl restart postgresql

# Step 6: Monitor recovery
echo "Monitoring recovery progress..."
until psql -h $RESTORE_HOST -U postgres -c "SELECT pg_is_in_recovery();" | grep -q "f"; do
    echo "$(date): Recovery in progress..."
    sleep 30
done

echo "PITR Recovery Complete!"
echo "Database available at: $RESTORE_HOST"
```

**PITR Workflow Diagram:**
```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    POINT-IN-TIME RECOVERY WORKFLOW                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  IDENTIFICATION                                                              │
│  □ Determine corruption/deletion timestamp                                   │
│  □ Identify target recovery point (usually 5 min before incident)            │
│  □ Verify base backup exists before target time                              │
│                                                                              │
│  PREPARATION                                                                 │
│  □ Provision restore instance (isolated from production)                     │
│  □ Stop writes to affected database (maintenance mode)                       │
│  □ Notify stakeholders of recovery activity                                  │
│                                                                              │
│  EXECUTION                                                                   │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐                     │
│  │   Restore   │───▶│   Replay    │───▶│   Promote   │                     │
│  │ Base Backup │    │ WAL Files   │    │   to Standalone                     │
│  │             │    │ to target   │    │             │                     │
│  │ ~30 min     │    │ time        │    │             │                     │
│  └─────────────┘    │ ~1-3 hours  │    └─────────────┘                     │
│                     └─────────────┘                                         │
│                                                                              │
│  VALIDATION                                                                  │
│  □ Verify data at target point is correct                                    │
│  □ Export corrected data (if partial recovery)                               │
│  □ Or redirect application to recovered database                             │
│                                                                              │
│  CUTOVER                                                                     │
│  □ Sync final changes (if applicable)                                        │
│  □ Update application connection strings                                     │
│  □ Disable maintenance mode                                                  │
│  □ Resume normal operations                                                  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 7.3 Database Recovery

**Full Database Restore:**
```bash
#!/bin/bash
# /opt/smart-dairy/scripts/recovery/database-restore.sh

BACKUP_FILE="$1"  # S3 path
TARGET_HOST="${2:-localhost}"
TARGET_DB="${3:-smart_dairy_prod}"

echo "=== Database Restore Procedure ==="
echo "Backup: $BACKUP_FILE"
echo "Target: $TARGET_HOST/$TARGET_DB"

# Pre-restore checklist
echo "Pre-restore Checklist:"
echo "□ Application maintenance mode enabled? (y/n)"
read -r confirm
if [[ $confirm != "y" ]]; then
    echo "Enable maintenance mode first!"
    exit 1
fi

# Download backup
echo "Downloading backup..."
aws s3 cp $BACKUP_FILE /tmp/restore.dump.gz
gunzip /tmp/restore.dump.gz

# Verify checksum
if aws s3 cp ${BACKUP_FILE}.sha256 /tmp/restore.dump.sha256 2>/dev/null; then
    echo "Verifying checksum..."
    cd /tmp && sha256sum -c restore.dump.sha256 || exit 1
fi

# Drop and recreate database
echo "Preparing target database..."
psql -h $TARGET_HOST -U postgres -c "DROP DATABASE IF EXISTS ${TARGET_DB};"
psql -h $TARGET_HOST -U postgres -c "CREATE DATABASE ${TARGET_DB};"

# Restore
echo "Restoring database (this may take 30-60 minutes)..."
pg_restore -h $TARGET_HOST -U postgres -d $TARGET_DB \
    --jobs=4 \
    --verbose \
    /tmp/restore.dump 2>&1 | tee /tmp/restore.log

# Post-restore verification
echo "Post-restore verification..."
TABLE_COUNT=$(psql -h $TARGET_HOST -U postgres -d $TARGET_DB -t -c \
    "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='public';")
echo "Tables restored: $TABLE_COUNT"

# Cleanup
rm -f /tmp/restore.dump /tmp/restore.dump.sha256

echo "Restore complete!"
```

### 7.4 File Recovery

**Single File Restore:**
```bash
#!/bin/bash
# Restore single file from restic backup

FILE_PATH="$1"
SNAPSHOT_ID="${2:-latest}"
RESTORE_DIR="/tmp/file-restore-$(date +%s)"

# Restore from restic
restic restore $SNAPSHOT_ID \
    --target $RESTORE_DIR \
    --include "$FILE_PATH"

# Move to production (after verification)
echo "File restored to: $RESTORE_DIR/$FILE_PATH"
echo "Verify and then move to production location"
```

**Filestore Full Restore:**
```bash
#!/bin/bash
# Full filestore restore

restic restore latest \
    --target /var/lib/odoo/filestore-restored \
    --tag "smart-dairy-filestore"

# After verification, swap directories
mv /var/lib/odoo/filestore /var/lib/odoo/filestore-old
mv /var/lib/odoo/filestore-restored /var/lib/odoo/filestore
chown -R odoo:odoo /var/lib/odoo/filestore
systemctl restart odoo
```

### 7.5 Complete System Recovery

**Full System Recovery Checklist:**
```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    COMPLETE SYSTEM RECOVERY CHECKLIST                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  PHASE 1: INFRASTRUCTURE (0-2 hours)                                         │
│  □ Verify cloud provider access and credentials                              │
│  □ Provision Kubernetes cluster (EKS)                                        │
│  □ Provision RDS instance (or restore from snapshot)                         │
│  □ Verify network configuration (VPC, subnets, security groups)              │
│  □ Deploy load balancers and ingress controllers                             │
│                                                                              │
│  PHASE 2: DATABASE (1-3 hours)                                               │
│  □ Restore PostgreSQL from latest full backup                                │
│  □ Apply WAL archives to reach latest point                                  │
│  □ Verify database connectivity                                              │
│  □ Run basic data integrity checks                                           │
│  □ Update connection strings in configuration                                │
│                                                                              │
│  PHASE 3: APPLICATION (1-2 hours)                                            │
│  □ Deploy application containers from registry                               │
│  □ Restore filestore from backup                                             │
│  □ Apply configuration (ConfigMaps, Secrets)                                 │
│  □ Verify pod health and readiness                                           │
│  □ Test internal service communication                                       │
│                                                                              │
│  PHASE 4: INTEGRATIONS (1-2 hours)                                           │
│  □ Verify payment gateway connectivity                                       │
│  □ Test SMS gateway functionality                                            │
│  □ Verify IoT MQTT broker status                                             │
│  □ Test external API integrations                                            │
│  □ Verify SSL certificates                                                   │
│                                                                              │
│  PHASE 5: VALIDATION (1 hour)                                                │
│  □ Run smoke tests on critical user journeys                                 │
│  □ Verify B2C e-commerce checkout flow                                       │
│  □ Test B2B portal login and ordering                                        │
│  □ Verify IoT data ingestion                                                 │
│  □ Check monitoring and alerting systems                                     │
│                                                                              │
│  PHASE 6: CUTOVER (30 min)                                                   │
│  □ Update DNS to point to recovered environment                              │
│  □ Verify SSL/TLS termination                                                │
│  □ Monitor application logs for errors                                       │
│  □ Notify stakeholders of service restoration                                │
│  □ Begin post-incident review                                                │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 8. RECOVERY TESTING

### 8.1 Testing Schedule

| Test Type | Frequency | Scope | Lead Role |
|-----------|-----------|-------|-----------|
| **Tabletop Exercise** | Quarterly | Procedure review, team coordination | IT Director |
| **Component Test** | Monthly | Single service recovery | Backup Admin |
| **Partial Recovery** | Quarterly | Database + Application | DevOps Lead |
| **Full DR Drill** | Semi-annually | Complete site failover | IT Director |
| **Unannounced Test** | Annually | Surprise activation | CTO |

### 8.2 Testing Scenarios

**Scenario A: Database Corruption Recovery**
```
Objective: Recover from simulated table corruption
Setup:
  □ Create test database from production backup
  □ Corrupt specific table (simulate accidental deletion)
  □ Document corruption timestamp
Test:
  □ Perform point-in-time recovery to 5 min before corruption
  □ Verify data integrity after recovery
  □ Measure total recovery time
Success Criteria:
  ✓ Recovery time < 2 hours
  ✓ Data loss < 15 minutes worth
  ✓ All related data consistent
```

**Scenario B: Complete System Loss**
```
Objective: Recover entire production environment
Setup:
  □ Use isolated test environment
  □ Start with blank infrastructure
Test:
  □ Execute complete system recovery procedure
  □ Verify all services functional
  □ Test failover DNS update
Success Criteria:
  ✓ Recovery time < 8 hours
  ✓ All critical services operational
  ✓ Data loss within RPO (15 min)
```

**Scenario C: Ransomware Recovery**
```
Objective: Recover from ransomware scenario
Setup:
  □ Assume production data is encrypted/compromised
  □ Must use offline/immutable backups
Test:
  □ Restore from immutable S3 backups
  □ Verify backup integrity before restore
  □ Clean rebuild of environment
Success Criteria:
  ✓ Recovery without using potentially compromised backups
  ✓ Malware-free environment post-recovery
  ✓ Security validation passed
```

### 8.3 Test Results Documentation

**Recovery Test Report Template:**
```markdown
# Recovery Test Report

| Field | Value |
|-------|-------|
| Test ID | RTR-2026-001 |
| Date | January 31, 2026 |
| Test Type | Database PITR |
| Executed By | [Name] |
| Witness | [Name] |

## Test Objectives
[Description of what was tested]

## Test Execution

### Timeline
| Step | Planned | Actual | Status |
|------|---------|--------|--------|
| Preparation | 10 min | 12 min | ✓ |
| Backup Download | 15 min | 18 min | ✓ |
| Base Restore | 30 min | 28 min | ✓ |
| WAL Replay | 45 min | 52 min | ✓ |
| Validation | 20 min | 22 min | ✓ |
| **TOTAL** | **2:00** | **2:12** | ✓ |

## Results

### RTO Achievement
- Target: 2 hours
- Actual: 2 hours 12 minutes
- Status: PASS (within 10% variance)

### RPO Achievement
- Target: 15 minutes
- Actual: 8 minutes
- Status: PASS

### Data Integrity
- Tables restored: 156/156 ✓
- Row count variance: <0.1% ✓
- Referential integrity: PASS ✓

## Issues Encountered
[Document any problems or observations]

## Recommendations
[Process improvements identified]

## Sign-off
| Role | Name | Signature | Date |
|------|------|-----------|------|
| Tester | | | |
| Witness | | | |
| IT Director | | | |
```

---

## 9. BACKUP MONITORING

### 9.1 Monitoring Dashboard

**Key Metrics:**

| Metric | Target | Warning | Critical |
|--------|--------|---------|----------|
| Last successful backup age | < 25 hours | > 25 hours | > 48 hours |
| WAL archive lag | < 5 min | > 10 min | > 15 min |
| Backup job duration | < 2 hours | > 2 hours | > 4 hours |
| Backup storage used | < 70% | 70-85% | > 85% |
| Failed backup jobs | 0 | 1 | > 1 |
| Restore test failures | 0 | 1 | > 1 |
| S3 replication lag | < 1 hour | 1-2 hours | > 2 hours |

### 9.2 Alert Configuration

**Prometheus Alert Rules:**
```yaml
groups:
  - name: backup-monitoring
    rules:
      # Backup age alert
      - alert: BackupTooOld
        expr: time() - backup_last_success_timestamp > 90000
        for: 5m
        labels:
          severity: critical
        annotations:
          summary: "Backup has not completed successfully"
          description: "Last successful backup was {{ $value | humanizeDuration }} ago"
      
      # WAL archive lag
      - alert: WALArchiveLag
        expr: pg_stat_archiver_last_archived_time - pg_stat_archiver_last_failed_time > 900
        for: 2m
        labels:
          severity: high
        annotations:
          summary: "WAL archiving is lagging"
          
      # Backup storage
      - alert: BackupStorageHigh
        expr: backup_storage_used_bytes / backup_storage_total_bytes > 0.85
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "Backup storage is {{ $value | humanizePercentage }} full"
          
      # Failed backup jobs
      - alert: BackupJobFailed
        expr: increase(backup_job_failures[1h]) > 0
        for: 0m
        labels:
          severity: critical
        annotations:
          summary: "Backup job failed"
```

### 9.3 Daily Monitoring Checklist

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    DAILY BACKUP MONITORING CHECKLIST                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  MORNING CHECKS (08:00 BDT)                                                  │
│  □ Review overnight backup logs                                              │
│    - /var/log/smart-dairy/backup-full.log                                    │
│    - /var/log/smart-dairy/backup-incr.log                                    │
│    - /var/log/smart-dairy/wal-archive.log                                    │
│                                                                              │
│  □ Check S3 bucket for recent backups                                        │
│    $ aws s3 ls s3://smart-dairy-backups/postgresql/full/ | tail -5           │
│                                                                              │
│  □ Verify WAL archive continuity                                             │
│    $ aws s3 ls s3://smart-dairy-backups/postgresql/wal/ | tail -20           │
│                                                                              │
│  □ Check restic snapshots                                                    │
│    $ restic snapshots | tail -10                                             │
│                                                                              │
│  □ Review monitoring dashboard                                               │
│    - Grafana: Backup Dashboard                                               │
│    - All panels green?                                                       │
│                                                                              │
│  □ Verify no critical alerts in PagerDuty/Slack                              │
│                                                                              │
│  EVENING CHECKS (18:00 BDT)                                                  │
│  □ Confirm daily backups completed                                           │
│  □ Check storage utilization trends                                          │
│  □ Review any warnings from the day                                          │
│                                                                              │
│  WEEKLY CHECKS (Mondays)                                                     │
│  □ Review weekend full backup completion                                     │
│  □ Verify backup verification report                                         │
│  □ Check S3 lifecycle policy execution                                       │
│  □ Update backup metrics spreadsheet                                         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 10. RETENTION POLICY

### 10.1 Data Retention Matrix

| Data Type | Online (S3) | Warm (S3-IA) | Cold (Glacier) | Deep Archive | Total |
|-----------|-------------|--------------|----------------|--------------|-------|
| **Full DB Backups** | 30 days | 60 days | 90-365 days | - | 365 days |
| **Incremental** | 7 days | 23 days | - | - | 30 days |
| **WAL Archives** | 7 days | 28 days | - | - | 35 days |
| **Filestore** | 30 days | 60 days | 90-365 days | - | 365 days |
| **Config Backups** | 90 days | 275 days | 365-730 days | - | 2 years |
| **K8s Backups** | 7 days | 23 days | - | - | 30 days |
| **Audit Logs** | 30 days | 335 days | 366-1825 days | 1826-2555 | 7 years |
| **App Logs** | 7 days | 83 days | - | - | 90 days |

### 10.2 S3 Lifecycle Policies

```json
{
  "Rules": [
    {
      "ID": "DatabaseBackupsLifecycle",
      "Status": "Enabled",
      "Filter": {
        "Prefix": "postgresql/full/"
      },
      "Transitions": [
        {
          "Days": 30,
          "StorageClass": "STANDARD_IA"
        },
        {
          "Days": 90,
          "StorageClass": "GLACIER"
        }
      ],
      "Expiration": {
        "Days": 365
      }
    },
    {
      "ID": "AuditLogsLifecycle",
      "Status": "Enabled",
      "Filter": {
        "Prefix": "logs/audit/"
      },
      "Transitions": [
        {
          "Days": 30,
          "StorageClass": "STANDARD_IA"
        },
        {
          "Days": 365,
          "StorageClass": "GLACIER"
        },
        {
          "Days": 1825,
          "StorageClass": "DEEP_ARCHIVE"
        }
      ],
      "Expiration": {
        "Days": 2555
      }
    },
    {
      "ID": "IncompleteMultipartUploads",
      "Status": "Enabled",
      "Filter": {
        "Prefix": ""
      },
      "AbortIncompleteMultipartUpload": {
        "DaysAfterInitiation": 7
      }
    }
  ]
}
```

### 10.3 Cleanup Procedures

**Automated Cleanup Script:**
```bash
#!/bin/bash
# /opt/smart-dairy/scripts/backup/cleanup-old-backups.sh

# PostgreSQL full backups (keep 90 days)
aws s3 ls s3://smart-dairy-backups/postgresql/full/ | \
    awk '{print $4}' | \
    while read file; do
        file_date=$(echo $file | grep -oP '\d{8}')
        if [ -n "$file_date" ]; then
            age_days=$(( ($(date +%s) - $(date -d $file_date +%s)) / 86400 ))
            if [ $age_days -gt 90 ]; then
                echo "Deleting old backup: $file (age: $age_days days)"
                aws s3 rm s3://smart-dairy-backups/postgresql/full/$file
                aws s3 rm s3://smart-dairy-backups/postgresql/full/${file}.sha256 2>/dev/null
            fi
        fi
    done

# WAL archives (keep 35 days)
aws s3 ls s3://smart-dairy-backups/postgresql/wal/ | \
    awk '{print $4}' | \
    while read file; do
        # Extract timestamp from WAL filename
        wal_time=$(echo $file | grep -oP '\d{8}\d{6}')
        if [ -n "$wal_time" ]; then
            age_days=$(( ($(date +%s) - $(date -d ${wal_time:0:8} +%s)) / 86400 ))
            if [ $age_days -gt 35 ]; then
                aws s3 rm s3://smart-dairy-backups/postgresql/wal/$file
            fi
        fi
    done

# Restic snapshots (managed by restic forget)
restic forget --tag "smart-dairy-filestore" \
    --keep-daily 7 \
    --keep-weekly 4 \
    --keep-monthly 12 \
    --prune
```

---

## 11. EMERGENCY CONTACTS

### 11.1 Internal Contacts

| Role | Name | Primary Contact | Secondary Contact | Escalation |
|------|------|-----------------|-------------------|------------|
| **Incident Commander** | IT Director | +880-XXXX-XXXX01 | +880-XXXX-XXXX02 | CTO |
| **Backup Administrator** | [Primary] | +880-XXXX-XXXX10 | +880-XXXX-XXXX11 | IT Director |
| **Database Lead** | [DBA] | +880-XXXX-XXXX20 | +880-XXXX-XXXX21 | IT Director |
| **DevOps Lead** | [Name] | +880-XXXX-XXXX30 | +880-XXXX-XXXX31 | IT Director |
| **Security Lead** | [CISO] | +880-XXXX-XXXX40 | +880-XXXX-XXXX41 | CTO |
| **On-Call Engineer** | Rotation | PagerDuty | PagerDuty | DevOps Lead |

### 11.2 External Contacts

| Vendor | Service | Contact | Priority |
|--------|---------|---------|----------|
| **AWS Support** | Cloud Infrastructure | Enterprise Support Portal | P1 - Critical |
| **AWS TAM** | Technical Account | tam@aws.amazon.com | Escalation |
| **Odoo SA** | ERP Platform | support@odoo.com | P2 - High |
| **CloudFlare** | DNS/CDN | support.cloudflare.com | P2 - High |
| **SSL Provider** | Certificates | support@sslprovider.com | P2 - High |
| **Internet Provider** | Connectivity | [ISP Support] | P1 - Critical |

### 11.3 Emergency Escalation Matrix

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    ESCALATION MATRIX                                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Level 1: Backup Failure (Single Job)                                        │
│  ├── Backup Administrator notifies                                           │
│ └── If unresolved in 1 hour → Escalate to IT Director                        │
│                                                                              │
│  Level 2: Multiple Backup Failures / Data Loss Suspected                     │
│  ├── IT Director notified immediately                                        │
│  ├── DevOps Lead engaged                                                     │
│ └── If RPO at risk → Escalate to CTO                                         │
│                                                                              │
│  Level 3: Complete System Failure / DR Activation                            │
│  ├── CTO notified immediately                                                │
│  ├── Full DR team assembled                                                  │
│  ├── Customer communication initiated                                        │
│ └── If major incident → Escalate to CEO                                      │
│                                                                              │
│  Level 4: Security Incident / Ransomware                                     │
│  ├── CISO notified immediately                                               │
│  ├── Security response team activated                                        │
│  ├── Law enforcement notification (if required)                              │
│ └── Board notification as per policy                                         │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 12. APPENDICES

### Appendix A: Backup Scripts Reference

| Script | Location | Purpose | Schedule |
|--------|----------|---------|----------|
| `postgresql-full-backup.sh` | `/opt/smart-dairy/scripts/backup/` | Full DB backup | Sun 02:00 |
| `filestore-incremental.sh` | `/opt/smart-dairy/scripts/backup/` | Filestore backup | Daily 02:00 |
| `verify-wal-archive.sh` | `/opt/smart-dairy/scripts/backup/` | WAL verification | Hourly |
| `k8s-backup.sh` | `/opt/smart-dairy/scripts/backup/` | Kubernetes backup | Daily 03:00 |
| `config-backup.sh` | `/opt/smart-dairy/scripts/backup/` | Configuration backup | Sun 03:30 |
| `verify-backups.sh` | `/opt/smart-dairy/scripts/backup/` | Backup verification | Sun 04:00 |
| `cleanup-old-backups.sh` | `/opt/smart-dairy/scripts/backup/` | Retention cleanup | Daily 06:00 |
| `pitr-recovery.sh` | `/opt/smart-dairy/scripts/recovery/` | Point-in-time recovery | On-demand |
| `database-restore.sh` | `/opt/smart-dairy/scripts/recovery/` | Full DB restore | On-demand |

### Appendix B: Recovery Checklists

**Quick Reference: Database Recovery**
```
□ 1. Put system in maintenance mode
□ 2. Identify correct backup file
□ 3. Download backup and verify checksum
□ 4. Stop application services
□ 5. Drop and recreate database
□ 6. Execute pg_restore
□ 7. Verify table count
□ 8. Start application services
□ 9. Disable maintenance mode
□ 10. Verify functionality
□ 11. Document recovery in incident log
```

**Quick Reference: Filestore Recovery**
```
□ 1. Identify snapshot to restore
□ 2. Stop Odoo service
□ 3. Backup current filestore (if needed)
□ 4. Execute restic restore
□ 5. Verify file ownership (odoo:odoo)
□ 6. Start Odoo service
□ 7. Verify file accessibility
□ 8. Document recovery
```

### Appendix C: Emergency Response Runbook

**Step 1: Incident Detection**
```
Source of Alert:
□ Monitoring system alert
□ User report
□ Automated backup failure
□ Manual discovery

Initial Assessment:
□ What system is affected?
□ When did the issue start?
□ Is data loss suspected?
□ What is the business impact?
```

**Step 2: Immediate Response (First 15 Minutes)**
```
□ Acknowledge alert in PagerDuty
□ Notify Backup Administrator
□ Assess data loss window
□ Identify recovery options
□ Begin incident documentation
□ If data loss > RPO: Escalate to IT Director
```

**Step 3: Recovery Decision**
```
Decision Tree:
├─ Can issue be fixed without restore?
│  └─ YES → Fix in place, document
│  └─ NO → Continue
│
├─ Is point-in-time recovery needed?
│  └─ YES → PITR procedure (7.2)
│  └─ NO → Continue
│
├─ Full restore required?
│  └─ YES → Database restore (7.3)
│  └─ NO → File restore (7.4)
```

**Step 4: Post-Recovery Actions**
```
□ Verify all services operational
□ Run data integrity checks
□ Update stakeholders
□ Complete incident report
□ Schedule post-mortem
□ Update procedures if needed
```

### Appendix D: Backup Schedule Summary

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    BACKUP SCHEDULE SUMMARY                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │  SUNDAY (Full Backup Day)                                              │  │
│  │  02:00 - PostgreSQL Full Backup                                        │  │
│  │  02:30 - Filestore Full Backup                                         │  │
│  │  03:00 - Kubernetes Backup                                             │  │
│  │  03:30 - Configuration Backup                                          │  │
│  │  04:00 - Verification Tests                                            │  │
│  │  05:00 - Log Archive                                                   │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │  MONDAY-SATURDAY (Incremental)                                         │  │
│  │  01:00 - IoT Data Backup                                               │  │
│  │  02:00 - Incremental File Backup                                       │  │
│  │  03:00 - Kubernetes Backup                                             │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │  CONTINUOUS/HOURLY                                                     │  │
│  │  :00 - WAL Archive (every hour)                                        │  │
│  │  Real-time - S3 versioning for uploads                                 │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
│  ┌───────────────────────────────────────────────────────────────────────┐  │
│  │  MONTHLY                                                               │  │
│  │  1st Sunday - On-demand snapshot for compliance                        │  │
│  │  Last Friday - Test restore execution                                  │  │
│  └───────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Appendix E: Recovery Workflow Diagrams

**Database Recovery Workflow:**
```
┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐
│ DETECT  │────▶│ ASSESS  │────▶│ PREPARE │────▶│ RESTORE │────▶│ VALIDATE│
│  LOSS   │     │ DAMAGE  │     │  ENV    │     │  DATA   │     │ RESULT  │
└─────────┘     └─────────┘     └─────────┘     └─────────┘     └────┬────┘
                                                                     │
                              ┌──────────────────────────────────────┘
                              ▼
                       ┌─────────────┐
                       │   CUTOVER   │
                       │  TO PROD    │
                       └──────┬──────┘
                              │
                              ▼
                       ┌─────────────┐
                       │   VERIFY    │
                       │  SERVICE    │
                       └─────────────┘
```

**File Recovery Workflow:**
```
┌──────────────┐
│ IDENTIFY     │
│ MISSING FILE │
└──────┬───────┘
       │
       ▼
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│ LOCATE IN    │────▶│ RESTORE FROM │────▶│ VERIFY FILE  │
│ BACKUP       │     │ RESTIC/S3    │     │ INTEGRITY    │
└──────────────┘     └──────────────┘     └──────┬───────┘
                                                 │
                                                 ▼
                                          ┌──────────────┐
                                          │ MOVE TO      │
                                          │ PRODUCTION   │
                                          └──────────────┘
```

### Appendix F: Recovery Time Estimates

| Recovery Scenario | Estimated Time | Prerequisites |
|-------------------|----------------|---------------|
| Single record restore | 15-30 minutes | DB access, SQL knowledge |
| Single table restore | 30-60 minutes | Valid backup, matching schema |
| Full database restore | 2-4 hours | Recent backup, stopped apps |
| Point-in-time recovery | 3-6 hours | Base backup + WAL archives |
| Filestore restore | 1-3 hours | Restic repository access |
| Single file restore | 5-15 minutes | Restic snapshot identified |
| Kubernetes restore | 2-4 hours | Velero backup, cluster ready |
| Complete system recovery | 4-8 hours | DR infrastructure, all backups |

---

**END OF BACKUP & RECOVERY RUNBOOK**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Backup Administrator | Initial version |

---

*This document is confidential and proprietary to Smart Dairy Ltd. Unauthorized distribution is prohibited.*
