# SMART DAIRY LTD.
## BACKUP & RECOVERY PROCEDURES
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | C-010 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Database Administrator |
| **Owner** | Database Administrator |
| **Reviewer** | CTO |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Backup Architecture](#2-backup-architecture)
3. [Database Backup Procedures](#3-database-backup-procedures)
4. [File System Backups](#4-file-system-backups)
5. [Configuration Backups](#5-configuration-backups)
6. [Backup Verification & Testing](#6-backup-verification--testing)
7. [Recovery Procedures](#7-recovery-procedures)
8. [Disaster Recovery](#8-disaster-recovery)
9. [Retention Policies](#9-retention-policies)
10. [Monitoring & Alerting](#10-monitoring--alerting)
11. [Appendices](#11-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines comprehensive backup and recovery procedures for the Smart Dairy Smart Web Portal System and Integrated ERP. It ensures business continuity through systematic data protection and rapid recovery capabilities.

### 1.2 Backup Scope

| Component | Backup Type | Frequency | Criticality |
|-----------|-------------|-----------|-------------|
| **PostgreSQL Database** | Full + WAL | Daily full, continuous WAL | Critical |
| **Odoo Filestore** | Incremental | Hourly | Critical |
| **Application Configuration** | Versioned | On change | Critical |
| **Kubernetes Cluster State** | Full | Daily | High |
| **Mobile App Builds** | Versioned | On release | Medium |
| **Monitoring Data** | Incremental | Daily | Low |
| **Log Files** | Archive | Weekly | Low |

### 1.3 Recovery Objectives

| Metric | Target | Definition |
|--------|--------|------------|
| **RPO (Recovery Point Objective)** | 15 minutes | Maximum acceptable data loss |
| **RTO (Recovery Time Objective)** | 4 hours | Maximum acceptable downtime |
| **RTO (Critical Services)** | 1 hour | For payment & order systems |
| **RTO (Complete System)** | 8 hours | Full system restoration |

---

## 2. BACKUP ARCHITECTURE

### 2.1 Backup Infrastructure

```
┌─────────────────────────────────────────────────────────────────┐
│                    BACKUP ARCHITECTURE                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  PRIMARY REGION (ap-south-1)                                     │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │  Production Environment                                     ││
│  │  ┌──────────┐ ┌──────────┐ ┌──────────┐                    ││
│  │  │ PostgreSQL│ │  Odoo    │ │ FastAPI  │                    ││
│  │  │ Primary   │ │ Server   │ │ Server   │                    ││
│  │  └────┬─────┘ └────┬─────┘ └────┬─────┘                    ││
│  │       │            │            │                          ││
│  │       ▼            ▼            ▼                          ││
│  │  ┌─────────────────────────────────────────────────────────┐││
│  │  │              BACKUP AGENTS                               │││
│  │  │  • pg_dump / WAL archiving   • File rsync               │││
│  │  │  • etcd snapshots            • Config Git               │││
│  │  └─────────────────────────────────────────────────────────┘││
│  │                          │                                   ││
│  │                          ▼                                   ││
│  │  ┌─────────────────────────────────────────────────────────┐││
│  │  │              S3 BACKUP BUCKET                            │││
│  │  │  Bucket: smart-dairy-backups-ap-south-1                 │││
│  │  │  • Encrypted at rest (SSE-KMS)                          │││
│  │  │  • Versioning enabled                                   │││
│  │  │  • Cross-region replication                             │││
│  │  └─────────────────────────────────────────────────────────┘││
│  └─────────────────────────────────────────────────────────────┘│
│                              │                                   │
│                              ▼                                   │
│  DR REGION (ap-southeast-1)                                      │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │              S3 REPLICATED BACKUP                           ││
│  │  Bucket: smart-dairy-backups-ap-southeast-1                 ││
│  │  • Async replication from primary                           ││
│  │  • Glacier Deep Archive after 90 days                       ││
│  └─────────────────────────────────────────────────────────────┘│
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Backup Tools

| Tool | Purpose | Version |
|------|---------|---------|
| **pg_dump** | PostgreSQL logical backup | 16.x |
| **pg_basebackup** | PostgreSQL physical backup | 16.x |
| **WAL-E / WAL-G** | Continuous WAL archiving | 2.0.x |
| **Velero** | Kubernetes backup | 1.12.x |
| **Restic** | File system backup | 0.16.x |
| **AWS Backup** | Centralized backup management | Latest |
| **ArgoCD** | Configuration backup (GitOps) | 2.9.x |

---

## 3. DATABASE BACKUP PROCEDURES

### 3.1 PostgreSQL Full Backup

```bash
#!/bin/bash
# scripts/backup/postgresql-full-backup.sh

set -euo pipefail

# Configuration
BACKUP_BUCKET="s3://smart-dairy-backups-ap-south-1/postgresql"
DB_HOST="${DB_HOST:-smart-dairy-db.cluster-xxx.ap-south-1.rds.amazonaws.com}"
DB_NAME="${DB_NAME:-smart_dairy_prod}"
DB_USER="${DB_USER:-backup_user}"
RETENTION_DAYS=30
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_NAME="smart_dairy_${DATE}.sql.gz"

# Create backup directory
mkdir -p /tmp/postgresql-backup

echo "Starting PostgreSQL full backup at $(date)"

# Perform compressed dump
pg_dump \
  --host=$DB_HOST \
  --username=$DB_USER \
  --dbname=$DB_NAME \
  --format=custom \
  --compress=9 \
  --verbose \
  --file=/tmp/postgresql-backup/${BACKUP_NAME%.gz} \
  --jobs=4 \
  --blobs \
  --clean \
  --if-exists

# Compress backup
gzip -9 /tmp/postgresql-backup/${BACKUP_NAME%.gz}

# Calculate checksum
cd /tmp/postgresql-backup
sha256sum ${BACKUP_NAME} > ${BACKUP_NAME}.sha256

# Upload to S3 with server-side encryption
aws s3 cp /tmp/postgresql-backup/${BACKUP_NAME} \
  ${BACKUP_BUCKET}/full/${BACKUP_NAME} \
  --storage-class STANDARD_IA \
  --server-side-encryption AES256

aws s3 cp /tmp/postgresql-backup/${BACKUP_NAME}.sha256 \
  ${BACKUP_BUCKET}/full/${BACKUP_NAME}.sha256

# Verify upload
aws s3 ls ${BACKUP_BUCKET}/full/${BACKUP_NAME}

echo "Backup completed: ${BACKUP_NAME}"

# Cleanup old backups
echo "Cleaning up backups older than ${RETENTION_DAYS} days"
aws s3 ls ${BACKUP_BUCKET}/full/ | \
  awk '{print $4}' | \
  while read file; do
    file_date=$(echo $file | grep -oP '\d{8}')
    if [[ -n $file_date ]]; then
      file_epoch=$(date -d $file_date +%s)
      cutoff_epoch=$(date -d "${RETENTION_DAYS} days ago" +%s)
      if [[ $file_epoch -lt $cutoff_epoch ]]; then
        echo "Deleting old backup: $file"
        aws s3 rm ${BACKUP_BUCKET}/full/$file
      fi
    fi
  done

# Cleanup local files
rm -rf /tmp/postgresql-backup

echo "PostgreSQL backup completed at $(date)"
```

### 3.2 Continuous WAL Archiving

```bash
#!/bin/bash
# scripts/backup/wal-archive.sh
# Called by PostgreSQL archive_command

WAL_FILE=$1
BACKUP_BUCKET="s3://smart-dairy-backups-ap-south-1/postgresql/wal"

# Upload WAL file to S3 with encryption
aws s3 cp ${WAL_FILE} ${BACKUP_BUCKET}/$(basename ${WAL_FILE}) \
  --storage-class STANDARD \
  --server-side-encryption AES256

# Verify upload
if aws s3 ls ${BACKUP_BUCKET}/$(basename ${WAL_FILE}) > /dev/null 2>&1; then
  exit 0
else
  echo "Failed to archive WAL file: ${WAL_FILE}"
  exit 1
fi
```

### 3.3 PostgreSQL Configuration for WAL Archiving

```sql
-- Enable WAL archiving for Point-in-Time Recovery (PITR)
ALTER SYSTEM SET wal_level = replica;
ALTER SYSTEM SET archive_mode = on;
ALTER SYSTEM SET archive_command = 'wal-archive.sh %p';
ALTER SYSTEM SET archive_timeout = 600;  -- Archive every 10 minutes
ALTER SYSTEM SET max_wal_size = '2GB';
ALTER SYSTEM SET min_wal_size = '512MB';

-- Enable checksums for data corruption detection
ALTER SYSTEM SET data_checksums = on;

-- Apply changes
SELECT pg_reload_conf();
```

### 3.4 Automated Daily Backup with Cron

```bash
#!/bin/bash
# /etc/cron.d/smart-dairy-backup

# Database full backup - Daily at 2:00 AM
0 2 * * * postgres /opt/smart-dairy/scripts/backup/postgresql-full-backup.sh >> /var/log/smart-dairy/backup.log 2>&1

# WAL verification - Every hour
0 * * * * postgres /opt/smart-dairy/scripts/backup/verify-wal-archive.sh

# Incremental filestore backup - Every 6 hours
0 */6 * * * root /opt/smart-dairy/scripts/backup/filestore-backup.sh

# Kubernetes cluster backup - Daily at 3:00 AM
0 3 * * * root /opt/smart-dairy/scripts/backup/k8s-backup.sh

# Backup verification - Weekly on Sunday at 4:00 AM
0 4 * * 0 root /opt/smart-dairy/scripts/backup/verify-backups.sh
```

---

## 4. FILE SYSTEM BACKUPS

### 4.1 Odoo Filestore Backup

```bash
#!/bin/bash
# scripts/backup/filestore-backup.sh

set -euo pipefail

FILESTORE_PATH="/var/lib/odoo/filestore"
BACKUP_BUCKET="s3://smart-dairy-backups-ap-south-1/filestore"
RETENTION_DAYS=30
DATE=$(date +%Y%m%d_%H%M%S)

# Create incremental backup using restic
RESTIC_REPOSITORY="${BACKUP_BUCKET}"
RESTIC_PASSWORD="${RESTIC_PASSWORD:-$(aws secretsmanager get-secret-value --secret-id smart-dairy/restic-password --query SecretString --output text)}"

export RESTIC_REPOSITORY RESTIC_PASSWORD

# Initialize repository if not exists
if ! restic snapshots > /dev/null 2>&1; then
  restic init
fi

# Create backup snapshot
echo "Creating filestore backup snapshot..."
restic backup ${FILESTORE_PATH} \
  --tag "smart-dairy-filestore" \
  --tag "${DATE}" \
  --exclude="*.tmp" \
  --exclude="*.log" \
  --verbose

# Apply retention policy
echo "Applying retention policy..."
restic forget \
  --tag "smart-dairy-filestore" \
  --keep-daily 7 \
  --keep-weekly 4 \
  --keep-monthly 12 \
  --prune

echo "Filestore backup completed at $(date)"
```

### 4.2 Kubernetes Persistent Volume Backup

```bash
#!/bin/bash
# scripts/backup/k8s-pv-backup.sh

set -euo pipefail

# Backup using Velero
VELERO_NAMESPACE="velero"
BACKUP_NAME="smart-dairy-$(date +%Y%m%d-%H%M%S)"

# Create full cluster backup excluding temporary resources
echo "Creating Velero backup: ${BACKUP_NAME}"
velero backup create ${BACKUP_NAME} \
  --include-namespaces production,staging \
  --exclude-resources events,pods/log \
  --snapshot-volumes \
  --ttl 720h0m0s \
  --storage-location default \
  --wait

# Verify backup
echo "Verifying backup..."
velero backup describe ${BACKUP_NAME} --details

# Cleanup old backups (keep last 30 days)
echo "Cleaning up old backups..."
velero backup delete --all --confirm --selector "backup-age>720h"

echo "Kubernetes backup completed at $(date)"
```

---

## 5. CONFIGURATION BACKUPS

### 5.1 GitOps-Based Configuration Backup

```bash
#!/bin/bash
# scripts/backup/config-backup.sh

set -euo pipefail

BACKUP_DIR="/tmp/config-backup-$(date +%Y%m%d-%H%M%S)"
BACKUP_BUCKET="s3://smart-dairy-backups-ap-south-1/configuration"

mkdir -p ${BACKUP_DIR}

# Export Kubernetes configurations
echo "Exporting Kubernetes resources..."
kubectl get all --all-namespaces -o yaml > ${BACKUP_DIR}/k8s-resources.yaml
kubectl get configmaps --all-namespaces -o yaml > ${BACKUP_DIR}/configmaps.yaml
kubectl get secrets --all-namespaces -o yaml > ${BACKUP_DIR}/secrets-encrypted.yaml
kubectl get ingress --all-namespaces -o yaml > ${BACKUP_DIR}/ingresses.yaml

# Export Helm releases
echo "Exporting Helm releases..."
helm list --all-namespaces -o yaml > ${BACKUP_DIR}/helm-releases.yaml

# Export Terraform state (if managed locally)
if [ -d "/opt/terraform" ]; then
  cp -r /opt/terraform ${BACKUP_DIR}/
fi

# Create archive
tar -czf ${BACKUP_DIR}.tar.gz -C $(dirname ${BACKUP_DIR}) $(basename ${BACKUP_DIR})

# Upload to S3
aws s3 cp ${BACKUP_DIR}.tar.gz ${BACKUP_BUCKET}/config-$(date +%Y%m%d).tar.gz \
  --server-side-encryption AES256

# Cleanup
rm -rf ${BACKUP_DIR} ${BACKUP_DIR}.tar.gz

echo "Configuration backup completed at $(date)"
```

---

## 6. BACKUP VERIFICATION & TESTING

### 6.1 Automated Backup Verification

```bash
#!/bin/bash
# scripts/backup/verify-backups.sh

set -euo pipefail

REPORT_FILE="/var/log/smart-dairy/backup-verification-$(date +%Y%m%d).log"
FAILED=0

echo "=== Backup Verification Report - $(date) ===" | tee -a ${REPORT_FILE}

# Verify PostgreSQL backup
echo "Verifying PostgreSQL backup..." | tee -a ${REPORT_FILE}
LATEST_PG_BACKUP=$(aws s3 ls s3://smart-dairy-backups-ap-south-1/postgresql/full/ | sort | tail -n 1 | awk '{print $4}')
if aws s3 ls s3://smart-dairy-backups-ap-south-1/postgresql/full/${LATEST_PG_BACKUP} > /dev/null 2>&1; then
  echo "✓ PostgreSQL backup found: ${LATEST_PG_BACKUP}" | tee -a ${REPORT_FILE}
else
  echo "✗ PostgreSQL backup missing!" | tee -a ${REPORT_FILE}
  FAILED=1
fi

# Verify WAL archiving
echo "Verifying WAL archive..." | tee -a ${REPORT_FILE}
RECENT_WAL=$(aws s3 ls s3://smart-dairy-backups-ap-south-1/postgresql/wal/ | sort | tail -n 1)
if [[ -n ${RECENT_WAL} ]]; then
  WAL_DATE=$(echo ${RECENT_WAL} | awk '{print $1}')
  echo "✓ Latest WAL archive: ${WAL_DATE}" | tee -a ${REPORT_FILE}
else
  echo "✗ No WAL archives found!" | tee -a ${REPORT_FILE}
  FAILED=1
fi

# Verify filestore backup (restic)
echo "Verifying filestore backup..." | tee -a ${REPORT_FILE}
RESTIC_SNAPSHOTS=$(restic snapshots --json 2>/dev/null | jq length)
if [[ ${RESTIC_SNAPSHOTS} -gt 0 ]]; then
  echo "✓ Filestore backups found: ${RESTIC_SNAPSHOTS} snapshots" | tee -a ${REPORT_FILE}
else
  echo "✗ No filestore backups found!" | tee -a ${REPORT_FILE}
  FAILED=1
fi

# Verify Kubernetes backups
echo "Verifying Kubernetes backups..." | tee -a ${REPORT_FILE}
LATEST_K8S_BACKUP=$(velero backup get --output json | jq -r '.items | sort_by(.metadata.creationTimestamp) | last | .metadata.name')
if [[ -n ${LATEST_K8S_BACKUP} && ${LATEST_K8S_BACKUP} != "null" ]]; then
  echo "✓ Latest K8s backup: ${LATEST_K8S_BACKUP}" | tee -a ${REPORT_FILE}
else
  echo "✗ No Kubernetes backups found!" | tee -a ${REPORT_FILE}
  FAILED=1
fi

# Send alert if verification failed
if [[ ${FAILED} -eq 1 ]]; then
  echo "BACKUP VERIFICATION FAILED!" | tee -a ${REPORT_FILE}
  aws sns publish \
    --topic-arn arn:aws:sns:ap-south-1:123456789:smart-dairy-alerts \
    --subject "CRITICAL: Backup Verification Failed" \
    --message "Backup verification failed at $(date). Check ${REPORT_FILE} for details."
  exit 1
else
  echo "All backups verified successfully!" | tee -a ${REPORT_FILE}
fi

echo "=== End of Report ===" | tee -a ${REPORT_FILE}
```

### 6.2 Quarterly Recovery Testing

| Test Scenario | Frequency | RTO Target | Last Test |
|---------------|-----------|------------|-----------|
| Single table recovery | Monthly | 30 min | TBD |
| Full database restore | Quarterly | 4 hours | TBD |
| Point-in-time recovery | Quarterly | 2 hours | TBD |
| Filestore restore | Quarterly | 2 hours | TBD |
| Kubernetes cluster restore | Bi-annually | 8 hours | TBD |
| Complete DR failover | Annually | 4 hours | TBD |

---

## 7. RECOVERY PROCEDURES

### 7.1 Database Recovery

```bash
#!/bin/bash
# scripts/recovery/postgresql-restore.sh

set -euo pipefail

BACKUP_FILE=$1  # S3 path to backup file
TARGET_HOST=${2:-localhost}
TARGET_DB=${3:-smart_dairy_prod}

echo "Starting database restore from ${BACKUP_FILE}"

# Download backup from S3
LOCAL_BACKUP="/tmp/restore-$(date +%s).dump"
aws s3 cp ${BACKUP_FILE} ${LOCAL_BACKUP}

# Verify checksum
if [[ -f "${BACKUP_FILE}.sha256" ]]; then
  aws s3 cp ${BACKUP_FILE}.sha256 ${LOCAL_BACKUP}.sha256
  cd /tmp && sha256sum -c ${LOCAL_BACKUP}.sha256
fi

# Stop application connections
echo "Stopping application connections..."
kubectl scale deployment smart-dairy-odoo --replicas=0 -n production

# Drop and recreate database
echo "Preparing target database..."
psql -h ${TARGET_HOST} -U postgres -c "DROP DATABASE IF EXISTS ${TARGET_DB};"
psql -h ${TARGET_HOST} -U postgres -c "CREATE DATABASE ${TARGET_DB};"

# Restore database
echo "Restoring database (this may take a while)..."
pg_restore \
  --host=${TARGET_HOST} \
  --username=postgres \
  --dbname=${TARGET_DB} \
  --jobs=4 \
  --verbose \
  ${LOCAL_BACKUP}

# Verify restore
echo "Verifying restore..."
TABLE_COUNT=$(psql -h ${TARGET_HOST} -U postgres -d ${TARGET_DB} -t -c "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='public';")
echo "Restored ${TABLE_COUNT} tables"

# Restart application
echo "Restarting application..."
kubectl scale deployment smart-dairy-odoo --replicas=3 -n production

# Cleanup
rm -f ${LOCAL_BACKUP} ${LOCAL_BACKUP}.sha256

echo "Database restore completed at $(date)"
```

### 7.2 Point-in-Time Recovery (PITR)

```bash
#!/bin/bash
# scripts/recovery/pitr-restore.sh

set -euo pipefail

TARGET_TIMESTAMP=$1  # Format: "2026-01-31 14:30:00"
BASE_BACKUP=$2       # Base backup to restore from

echo "Starting PITR to: ${TARGET_TIMESTAMP}"

# Stop PostgreSQL
systemctl stop postgresql

# Clear data directory
rm -rf /var/lib/postgresql/data/*

# Restore base backup
tar -xzf ${BASE_BACKUP} -C /var/lib/postgresql/data/

# Configure recovery
cat > /var/lib/postgresql/data/recovery.signal <<EOF
restore_command = 'aws s3 cp s3://smart-dairy-backups-ap-south-1/postgresql/wal/%f %p'
recovery_target_time = '${TARGET_TIMESTAMP}'
recovery_target_action = 'promote'
EOF

# Start PostgreSQL in recovery mode
systemctl start postgresql

# Monitor recovery
echo "Waiting for recovery to complete..."
until psql -c "SELECT pg_is_in_recovery();" | grep -q "f"; do
  echo "Recovery in progress..."
  sleep 10
done

echo "PITR completed successfully!"
```

---

## 8. DISASTER RECOVERY

### 8.1 DR Runbook

```
DISASTER RECOVERY ACTIVATION CHECKLIST
======================================

Phase 1: Assessment (0-30 minutes)
-----------------------------------
□ Identify disaster scope and severity
□ Notify DR team and stakeholders
□ Document incident timeline
□ Assess data loss window

Phase 2: DR Activation (30-60 minutes)
--------------------------------------
□ Activate DR site (ap-southeast-1)
□ Update Route 53 DNS to point to DR
□ Restore database from replicated backups
□ Restore filestore from S3

Phase 3: Service Restoration (1-4 hours)
----------------------------------------
□ Start Kubernetes cluster in DR region
□ Deploy applications from GitOps
□ Verify database connectivity
□ Run smoke tests

Phase 4: Validation (4-6 hours)
-------------------------------
□ Verify all services operational
□ Run data consistency checks
□ Notify users of service restoration
□ Begin root cause analysis
```

### 8.2 Cross-Region Replication

```bash
#!/bin/bash
# Enable cross-region replication for S3 buckets

# Source bucket in primary region
aws s3api put-bucket-replication \
  --bucket smart-dairy-backups-ap-south-1 \
  --replication-configuration '{
    "Role": "arn:aws:iam::123456789:role/S3ReplicationRole",
    "Rules": [{
      "ID": "SmartDairyReplication",
      "Status": "Enabled",
      "Priority": 1,
      "DeleteMarkerReplication": { "Status": "Enabled" },
      "Filter": { "Prefix": "" },
      "Destination": {
        "Bucket": "arn:aws:s3:::smart-dairy-backups-ap-southeast-1",
        "StorageClass": "STANDARD_IA",
        "EncryptionConfiguration": {
          "ReplicaKmsKeyID": "arn:aws:kms:ap-southeast-1:123456789:key/dr-key-id"
        }
      },
      "SourceSelectionCriteria": {
        "SseKmsEncryptedObjects": { "Status": "Enabled" }
      }
    }]
  }'
```

---

## 9. RETENTION POLICIES

### 9.1 Data Retention Matrix

| Data Type | Retention Period | Storage Class | Archive After |
|-----------|-----------------|---------------|---------------|
| **Database Full Backups** | 90 days | Standard-IA | 30 days |
| **WAL Archives** | 35 days | Standard | 30 days |
| **Filestore Snapshots** | 1 year | Intelligent-Tiering | 90 days |
| **Configuration Backups** | 2 years | Standard | 1 year |
| **Kubernetes Backups** | 30 days | Standard | N/A |
| **Audit Logs** | 7 years | Glacier | 1 year |
| **Application Logs** | 90 days | Standard-IA | 30 days |

### 9.2 Automated Lifecycle Policies

```json
{
  "Rules": [
    {
      "ID": "DatabaseBackupLifecycle",
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
      "ID": "AuditLogLifecycle",
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
          "StorageClass": "GLACIER_DEEP_ARCHIVE"
        }
      ],
      "Expiration": {
        "Days": 2555
      }
    }
  ]
}
```

---

## 10. MONITORING & ALERTING

### 10.1 Backup Monitoring Dashboard

| Metric | Threshold | Alert Severity |
|--------|-----------|----------------|
| Last backup age | > 25 hours | Critical |
| WAL archive lag | > 15 minutes | High |
| Backup size change | ±50% from avg | Warning |
| Failed backup jobs | > 0 | Critical |
| Restore test failures | > 0 | High |
| S3 replication lag | > 1 hour | Warning |

### 10.2 Alert Configuration

```yaml
# Prometheus alert rules
groups:
  - name: backup-alerts
    rules:
      - alert: DatabaseBackupStale
        expr: time() - backup_last_success{job="postgresql"} > 90000
        for: 5m
        labels:
          severity: critical
        annotations:
          summary: "Database backup is stale"
          description: "Last successful backup was {{ $value | humanizeDuration }} ago"
      
      - alert: WALArchiveFailed
        expr: increase(wal_archive_failures[1h]) > 0
        for: 0m
        labels:
          severity: high
        annotations:
          summary: "WAL archiving is failing"
          description: "{{ $value }} WAL archive failures in the last hour"
      
      - alert: BackupStorageHigh
        expr: backup_storage_used / backup_storage_total > 0.85
        for: 5m
        labels:
          severity: warning
        annotations:
          summary: "Backup storage is running low"
          description: "{{ $value | humanizePercentage }} of backup storage used"
```

---

## 11. APPENDICES

### Appendix A: Recovery Time Estimates

| Recovery Type | Estimated Time | Prerequisites |
|---------------|----------------|---------------|
| Single record restore | 15-30 minutes | Backup access, DB connection |
| Single table restore | 30-60 minutes | Valid backup, matching schema |
| Full database restore | 2-4 hours | Recent backup, stopped apps |
| Point-in-time recovery | 3-6 hours | Base backup + WAL archives |
| Filestore restore | 1-3 hours | Restic repository access |
| Kubernetes restore | 2-4 hours | Velero backup, cluster ready |
| Full DR activation | 4-8 hours | DR infrastructure ready |

### Appendix B: Emergency Contacts

| Role | Contact | Responsibility |
|------|---------|----------------|
| DBA On-Call | +880-XXXX-XXXXXX | Database recovery |
| DevOps Lead | +880-XXXX-XXXXXX | Infrastructure recovery |
| CTO | +880-XXXX-XXXXXX | Decision authority |
| AWS Support | Enterprise Support | Cloud infrastructure |

---

**END OF BACKUP & RECOVERY PROCEDURES**

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Jan 31, 2026 | Database Administrator | Initial version |
