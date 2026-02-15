# SMART DAIRY LTD.
## DATA RETENTION & ARCHIVING POLICY
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | C-007 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Data Architect |
| **Owner** | Chief Data Officer |
| **Reviewer** | Legal Counsel |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Regulatory Requirements](#2-regulatory-requirements)
3. [Data Classification](#3-data-classification)
4. [Retention Schedules](#4-retention-schedules)
5. [Archiving Strategy](#5-archiving-strategy)
6. [Data Purging Procedures](#6-data-purging-procedures)
7. [Storage Management](#7-storage-management)
8. [Implementation](#8-implementation)
9. [Monitoring & Compliance](#9-monitoring--compliance)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This policy defines the framework for retaining, archiving, and disposing of data within the Smart Dairy ERP system. It ensures compliance with legal, regulatory, and business requirements while optimizing storage costs and system performance.

### 1.2 Policy Objectives

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA RETENTION OBJECTIVES                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  COMPLIANCE                                                      │
│  • Meet Bangladesh tax record requirements (7 years)            │
│  • Satisfy company law obligations                              │
│  • Support audit and legal discovery                            │
│                                                                  │
│  OPERATIONAL                                                     │
│  • Maintain data required for business operations               │
│  • Support historical analysis and trend reporting              │
│  • Enable customer service and dispute resolution               │
│                                                                  │
│  EFFICIENCY                                                      │
│  • Optimize database performance                                │
│  • Manage storage costs effectively                             │
│  • Reduce backup and recovery times                             │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Scope

This policy applies to all data stored in:
- PostgreSQL production database
- TimescaleDB time-series database
- Filestore and document storage
- Backup systems
- Disaster recovery environments

---

## 2. REGULATORY REQUIREMENTS

### 2.1 Bangladesh Legal Framework

| Regulation | Requirement | Retention Period | Applicable Data |
|------------|-------------|------------------|-----------------|
| **Value Added Tax Act, 1991** | VAT records | 7 years | Sales invoices, purchase orders, VAT returns |
| **Income Tax Ordinance, 1984** | Tax records | 7 years | Financial transactions, payroll, expense reports |
| **Companies Act, 1994** | Company records | Permanent | Annual returns, board resolutions, share registers |
| **Labour Act, 2006** | Employment records | 7 years | Employee contracts, attendance, wages |
| **Digital Security Act, 2018** | Security logs | 2 years | Access logs, audit trails |

### 2.2 Industry-Specific Requirements

| Standard | Requirement | Retention Period |
|----------|-------------|------------------|
| **Bangladesh Standards (BDS)** | Quality control records | 5 years | Test reports, certifications |
| **Food Safety Regulations** | Traceability records | 3 years beyond product life | Batch records, supplier certificates |
| **Animal Health Records** | Veterinary records | 5 years | Health checks, treatment records |

### 2.3 International Standards

| Standard | Requirement |
|----------|-------------|
| **GDPR** | Personal data retained only as necessary; right to erasure |
| **ISO 27001** | Information security records retained for audit purposes |
| **SOX (if applicable)** | Financial records for corporate governance |

---

## 3. DATA CLASSIFICATION

### 3.1 Data Categories

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA CLASSIFICATION MATRIX                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  TIER 1: CRITICAL - Legal/Regulatory (7+ years)                 │
│  ├── Financial journal entries                                  │
│  ├── Tax returns and supporting documents                       │
│  ├── Legal contracts and agreements                             │
│  ├── Payroll and employment records                             │
│  └── Audit reports                                              │
│                                                                  │
│  TIER 2: IMPORTANT - Business Operations (3-7 years)            │
│  ├── Sales orders and invoices                                  │
│  ├── Purchase orders and receipts                               │
│  ├── Customer transaction history                               │
│  ├── Farm production records                                    │
│  ├── Animal health and breeding records                         │
│  └── Quality control records                                    │
│                                                                  │
│  TIER 3: OPERATIONAL - Short-term (1-3 years)                   │
│  ├── Active project data                                        │
│  ├── Temporary reports                                          │
│  ├── Draft documents                                            │
│  └── User session logs                                          │
│                                                                  │
│  TIER 4: TRANSIENT - Immediate purge (< 1 year)                 │
│  ├── System temporary files                                     │
│  ├── Cache data                                                 │
│  ├── Failed transaction logs (after resolution)                 │
│  └── Old email notifications                                    │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 3.2 Data Sensitivity Levels

| Level | Definition | Examples | Access Control |
|-------|------------|----------|----------------|
| **Public** | No restriction | Product catalogs, public announcements | Open |
| **Internal** | Company use only | Operational reports, internal memos | Authenticated users |
| **Confidential** | Restricted access | Financial data, customer lists | Role-based |
| **Restricted** | Need-to-know only | Payroll, strategic plans | Explicit authorization |

---

## 4. RETENTION SCHEDULES

### 4.1 Database Retention Matrix

| Schema | Table/Entity | Retention Period | Archive Action | Purge Action |
|--------|--------------|------------------|----------------|--------------|
| **accounting** | account_move | 7 years | Compress after 2 years | Move to archive DB at 7 years |
| **accounting** | account_move_line | 7 years | Compress after 2 years | Move to archive DB at 7 years |
| **sales** | sale_order | 7 years | Partition by year | Archive at 5 years, purge at 7 |
| **sales** | sale_order_line | 7 years | Partition by year | Archive at 5 years, purge at 7 |
| **farm** | milk_production | 7 years | TimescaleDB compression | Continuous aggregate only |
| **farm** | animal | Lifetime + 7 years | No archive | Soft delete only |
| **farm** | breeding_record | 7 years | Partition by year | Archive at 7 years |
| **iot** | sensor_data | 2 years | Aggressive compression | Agg continuous aggregates |
| **core** | res_partner | As long as relationship + 7 years | No archive | Anonymize after purge period |
| **core** | res_users | 7 years after termination | No archive | Hard delete after 7 years |

### 4.2 Filestore Retention

| File Type | Location | Retention Period | Archive Strategy |
|-----------|----------|------------------|------------------|
| **Invoices/PDFs** | filestore/invoices/ | 7 years | Move to S3 Glacier after 2 years |
| **Contracts** | filestore/contracts/ | Permanent | Keep in standard storage |
| **Animal Photos** | filestore/animals/ | Lifetime + 3 years | S3 Standard-IA after 1 year |
| **Reports** | filestore/reports/ | 3 years | Auto-delete after 3 years |
| **Temp Uploads** | filestore/temp/ | 30 days | Auto-purge after 30 days |
| **Email Attachments** | filestore/mail/ | 2 years | Archive to S3 Glacier after 1 year |

### 4.3 Log Retention

| Log Type | Retention | Storage Location | Compression |
|----------|-----------|------------------|-------------|
| **Application Logs** | 90 days | Elasticsearch | N/A |
| **Audit Logs** | 7 years | PostgreSQL + S3 | GZIP after 30 days |
| **Access Logs** | 2 years | S3 | GZIP after 7 days |
| **Error Logs** | 1 year | S3 | GZIP after 30 days |
| **Database Logs** | 30 days | RDS automatic | N/A |
| **Security Logs** | 2 years | S3 + SIEM | Encrypted, GZIP |

---

## 5. ARCHIVING STRATEGY

### 5.1 Archive Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA ARCHITECTURE OVER TIME                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ACTIVE DATA                    ARCHIVE DATA                     │
│  (0-2 Years)                    (2-7 Years)                      │
│  ┌──────────────┐               ┌──────────────┐                │
│  │  PostgreSQL  │               │  PostgreSQL  │                │
│  │  (Primary)   │──────────────▶│  (Archive)   │                │
│  │              │   Archive     │  (Read-only) │                │
│  │  Fast I/O    │   Process     │  Compressed  │                │
│  │  Full Access │               │  Limited     │                │
│  └──────────────┘               └──────────────┘                │
│         │                              │                         │
│         │                              │                         │
│         ▼                              ▼                         │
│  ┌──────────────┐               ┌──────────────┐                │
│  │   S3 Standard│               │ S3 Glacier   │                │
│  │   (Hot)      │               │ (Cold)       │                │
│  │   $0.023/GB  │               │   $0.004/GB  │                │
│  └──────────────┘               └──────────────┘                │
│                                                                  │
│  LONG-TERM ARCHIVE (7+ Years)                                    │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  S3 Glacier Deep Archive                                  │   │
│  │  $0.00099/GB  (Retrieval: 12 hours)                       │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 5.2 Archive Process Flow

```python
# Automated archiving process
class DataArchiver:
    """Automated data archiving system"""
    
    def archive_partitioned_table(self, schema, table, partition_column, archive_age_months):
        """Archive old partitions from partitioned tables"""
        
        # Find partitions older than threshold
        old_partitions = self.get_old_partitions(
            schema, table, partition_column, archive_age_months
        )
        
        for partition in old_partitions:
            # 1. Export partition to Parquet format
            export_path = f"s3://smart-dairy-archive/{schema}/{table}/{partition.name}.parquet"
            self.export_partition_to_s3(partition, export_path)
            
            # 2. Verify export integrity
            if self.verify_export(partition, export_path):
                # 3. Compress partition in database
                self.compress_partition(partition)
                
                # 4. Move to archive storage class
                self.move_to_glacier(export_path)
                
                # 5. Log archive event
                self.log_archive_event(schema, table, partition, export_path)
    
    def archive_timescale_chunks(self, hypertable, compress_after_days):
        """Compress old TimescaleDB chunks"""
        
        query = f"""
            SELECT compress_chunk(chunk_schema || '.' || chunk_name)
            FROM timescaledb_information.chunks
            WHERE hypertable_name = '{hypertable}'
              AND range_end < NOW() - INTERVAL '{compress_after_days} days'
              AND NOT is_compressed;
        """
        self.execute(query)
    
    def create_continuous_aggregate(self, hypertable, aggregate_name):
        """Create continuous aggregate for long-term analytics"""
        
        query = f"""
            CREATE MATERIALIZED VIEW {aggregate_name}
            WITH (timescaledb.continuous) AS
            SELECT 
                time_bucket('1 hour', time) as bucket,
                device_id,
                sensor_type,
                AVG(value) as avg_value,
                MIN(value) as min_value,
                MAX(value) as max_value,
                COUNT(*) as sample_count
            FROM {hypertable}
            GROUP BY bucket, device_id, sensor_type;
            
            -- Set retention policy on raw data
            SELECT add_retention_policy('{hypertable}', INTERVAL '2 years');
            
            -- Keep aggregates longer
            SELECT add_retention_policy('{aggregate_name}', INTERVAL '7 years');
        """
        self.execute(query)
```

### 5.3 Archive Storage Tiers

| Tier | Storage Class | Use Case | Cost/GB/Month | Retrieval Time |
|------|---------------|----------|---------------|----------------|
| **Hot** | S3 Standard | Active data, frequent access | $0.023 | Immediate |
| **Warm** | S3 Standard-IA | Monthly reports, old invoices | $0.0125 | Immediate |
| **Cool** | S3 Glacier Instant | Compliance data, annual access | $0.004 | Milliseconds |
| **Cold** | S3 Glacier Flexible | 7-year archives, legal hold | $0.0036 | 1-5 minutes |
| **Deep** | S3 Glacier Deep | Permanent archive | $0.00099 | 12 hours |

---

## 6. DATA PURGING PROCEDURES

### 6.1 Purge Strategy

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATA PURGE DECISION TREE                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  Is retention period expired?                                    │
│         │                                                        │
│    YES ─┴─ NO ──▶ Keep in active storage                        │
│         │                                                        │
│  Is there a legal hold?                                          │
│         │                                                        │
│    YES ─┴─ NO                                                    │
│         │                                                        │
│  Is it personal data?                                            │
│         │                                                        │
│    YES ─┴─ NO                                                    │
│         │              │                                         │
│  Anonymize    Archive then purge                                 │
│  (keep stats) │                                                  │
│               ▼                                                  │
│       Move to archive storage                                    │
│               │                                                  │
│               ▼                                                  │
│       Schedule final purge                                       │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 6.2 Safe Purge Procedures

```sql
-- Purge old temporary files (safe - no dependencies)
DELETE FROM ir_attachment
WHERE res_model = 'temp'
  AND create_date < NOW() - INTERVAL '30 days';

-- Anonymize old customer data instead of deleting
UPDATE res_partner
SET 
    name = 'ANONYMIZED-' || id,
    email = NULL,
    phone = NULL,
    mobile = NULL,
    street = NULL,
    active = FALSE,
    x_anonymized_at = NOW()
WHERE customer_type = 'b2c'
  AND active = FALSE
  AND write_date < NOW() - INTERVAL '7 years';

-- Purge old sensor data (TimescaleDB)
SELECT drop_chunks(
    'iot.sensor_data',
    older_than => INTERVAL '2 years'
);

-- Purge old logs (with backup)
-- 1. Export to S3 first
COPY (
    SELECT * FROM audit_log 
    WHERE created_at < NOW() - INTERVAL '2 years'
) TO PROGRAM 'aws s3 cp - s3://smart-dairy-archive/logs/audit_$(date +%Y%m%d).csv.gz' 
WITH (FORMAT csv, COMPRESSION gzip);

-- 2. Then delete
DELETE FROM audit_log 
WHERE created_at < NOW() - INTERVAL '2 years';
```

### 6.3 GDPR Right to Erasure Implementation

```python
class GDPRDataProcessor:
    """Handle GDPR data subject requests"""
    
    def right_to_erasure(self, partner_id):
        """Process right to erasure request"""
        
        # 1. Check for legal obligations preventing deletion
        if self.has_legal_hold(partner_id):
            raise Exception("Legal hold prevents deletion")
        
        # 2. Anonymize personal data
        self.anonymize_partner(partner_id)
        
        # 3. Delete related personal data
        self.delete_personal_attachments(partner_id);
        
        # 4. Clear communication history
        self.clear_communications(partner_id);
        
        # 5. Log deletion for audit
        self.log_gdpr_erasure(partner_id);
    
    def anonymize_partner(self, partner_id):
        """Anonymize while preserving analytics"""
        
        self.execute("""
            UPDATE res_partner
            SET 
                name = 'ANON_' || MD5(RANDOM()::TEXT),
                email = NULL,
                phone = NULL,
                mobile = NULL,
                street = NULL,
                street2 = NULL,
                city = NULL,
                zip = NULL,
                website = NULL,
                comment = NULL,
                active = FALSE,
                x_anonymized_at = NOW(),
                x_original_hash = MD5(name || email || phone)
            WHERE id = %s
        """, (partner_id,))
```

---

## 7. STORAGE MANAGEMENT

### 7.1 Storage Capacity Planning

| Year | Database Size | Filestore | Growth Rate | Action Required |
|------|---------------|-----------|-------------|-----------------|
| Year 1 | 210 GB | 50 GB | - | Baseline |
| Year 2 | 530 GB | 120 GB | 150% | Scale storage |
| Year 3 | 900 GB | 250 GB | 70% | Implement aggressive archiving |
| Year 5 | 1.5 TB | 500 GB | 40% | Archive Year 1-2 data |
| Year 7 | 2.2 TB | 800 GB | 30% | Full archive implementation |

### 7.2 Cost Optimization

```python
# Storage cost optimization script
class StorageOptimizer:
    """Optimize storage costs through intelligent tiering"""
    
    def analyze_storage_usage(self):
        """Analyze current storage usage"""
        
        analysis = {
            'database': self.get_db_storage_breakdown(),
            'filestore': self.get_filestore_breakdown(),
            'backups': self.get_backup_storage(),
            'logs': self.get_log_storage()
        }
        
        return analysis
    
    def recommend_optimizations(self):
        """Generate cost-saving recommendations"""
        
        recommendations = []
        
        # Check for large uncompressed tables
        large_tables = self.find_uncompressed_large_tables()
        for table in large_tables:
            recommendations.append({
                'type': 'compression',
                'target': table['name'],
                'potential_savings_gb': table['size_gb'] * 0.6,
                'action': f'Enable TimescaleDB compression for {table["name"]}'
            })
        
        # Check for old filestore files
        old_files = self.find_old_files(days=365)
        if old_files['total_size_gb'] > 100:
            recommendations.append({
                'type': 'archive',
                'target': 'filestore',
                'potential_savings_gb': old_files['total_size_gb'] * 0.8,
                'action': f'Move {old_files["count"]} files older than 1 year to Glacier'
            })
        
        return recommendations
    
    def project_costs(self, years=5):
        """Project storage costs over time"""
        
        projections = []
        current_size = self.get_current_storage_size()
        
        for year in range(1, years + 1):
            # Assume 50% YoY growth, with archiving reducing by 30%
            projected_size = current_size * (1.5 ** year) * 0.7
            
            # Calculate costs by tier
            hot_cost = projected_size * 0.2 * 0.023      # 20% hot
            warm_cost = projected_size * 0.3 * 0.0125    # 30% warm
            cold_cost = projected_size * 0.5 * 0.004     # 50% cold
            
            total_cost = (hot_cost + warm_cost + cold_cost) * 12
            
            projections.append({
                'year': year,
                'size_tb': projected_size / 1000,
                'annual_cost_usd': total_cost
            })
        
        return projections
```

---

## 8. IMPLEMENTATION

### 8.1 Automated Retention Jobs

```sql
-- PostgreSQL pg_cron jobs for automated retention

-- Daily: Purge temp files (3 AM)
SELECT cron.schedule('purge-temp-files', '0 3 * * *', 
    $$DELETE FROM ir_attachment WHERE res_model = 'temp' AND create_date < NOW() - INTERVAL '30 days'$$);

-- Weekly: Archive old audit logs (Sunday 2 AM)
SELECT cron.schedule('archive-audit-logs', '0 2 * * 0',
    $$SELECT archive_old_audit_logs()$$);

-- Monthly: Compress old milk production data (1st of month 1 AM)
SELECT cron.schedule('compress-milk-data', '0 2 1 * *',
    $$SELECT compress_old_partitions('farm', 'milk_production', 3)$$);

-- Quarterly: Move to glacier (First day of quarter 12 AM)
SELECT cron.schedule('glacier-archive', '0 0 1 */3 *',
    $$SELECT move_to_glacier_storage()$$);
```

### 8.2 Retention Configuration

```yaml
# retention_config.yaml
retention_policies:
  accounting:
    account_move:
      active_years: 2
      archive_after_years: 2
      purge_after_years: 7
      compression: true
      
  sales:
    sale_order:
      active_years: 2
      archive_after_years: 3
      purge_after_years: 7
      compression: true
      
  iot:
    sensor_data:
      active_days: 90
      compress_after_days: 30
      purge_after_years: 2
      continuous_aggregate: true
      aggregate_retention_years: 7
      
  filestore:
    invoices:
      hot_storage_months: 24
      glacier_after_months: 24
      purge_after_years: 7
      
    temp_files:
      purge_after_days: 30
      
    reports:
      purge_after_years: 3
```

---

## 9. MONITORING & COMPLIANCE

### 9.1 Compliance Dashboard

```sql
-- Compliance monitoring view
CREATE VIEW compliance.status AS
WITH retention_summary AS (
    SELECT 
        'account_move' as entity,
        COUNT(*) FILTER (WHERE date >= CURRENT_DATE - INTERVAL '2 years') as active_records,
        COUNT(*) FILTER (WHERE date < CURRENT_DATE - INTERVAL '2 years' 
                         AND date >= CURRENT_DATE - INTERVAL '7 years') as archive_eligible,
        COUNT(*) FILTER (WHERE date < CURRENT_DATE - INTERVAL '7 years') as purge_eligible,
        pg_size_pretty(pg_total_relation_size('accounting.move')) as storage_size
    FROM accounting.move
    
    UNION ALL
    
    SELECT 
        'sensor_data' as entity,
        COUNT(*) FILTER (WHERE time >= NOW() - INTERVAL '90 days') as active_records,
        COUNT(*) FILTER (WHERE time < NOW() - INTERVAL '90 days' 
                         AND time >= NOW() - INTERVAL '2 years') as archive_eligible,
        COUNT(*) FILTER (WHERE time < NOW() - INTERVAL '2 years') as purge_eligible,
        pg_size_pretty(pg_total_relation_size('iot.sensor_data')) as storage_size
    FROM iot.sensor_data
)
SELECT 
    entity,
    active_records,
    archive_eligible,
    purge_eligible,
    storage_size,
    CASE 
        WHEN purge_eligible > 0 THEN 'ACTION REQUIRED'
        WHEN archive_eligible > active_records * 0.5 THEN 'REVIEW NEEDED'
        ELSE 'COMPLIANT'
    END as compliance_status
FROM retention_summary;
```

### 9.2 Audit Trail

```sql
-- Retention action audit table
CREATE TABLE audit.retention_actions (
    id BIGSERIAL PRIMARY KEY,
    action_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    action_type VARCHAR(50) NOT NULL, -- 'archive', 'compress', 'purge', 'anonymize'
    entity_type VARCHAR(100) NOT NULL,
    entity_count INTEGER,
    size_impact_gb DECIMAL(10,2),
    performed_by VARCHAR(100),
    details JSONB,
    verification_hash VARCHAR(64)
);

-- Log all retention actions
CREATE OR REPLACE FUNCTION log_retention_action()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO audit.retention_actions (
        action_type, entity_type, details
    ) VALUES (
        TG_OP,
        TG_TABLE_NAME,
        jsonb_build_object(
            'old_data', OLD,
            'timestamp', CURRENT_TIMESTAMP
        )
    );
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
```

---

## 10. APPENDICES

### Appendix A: Retention Schedule Summary

| Data Type | Active | Archive | Purge | Storage Class |
|-----------|--------|---------|-------|---------------|
| Financial Transactions | 2 years | 5 years | 7 years | S3 Glacier |
| Sales Orders | 2 years | 3 years | 7 years | S3 Glacier |
| Customer Data | Relationship | 7 years after close | Anonymize | N/A |
| Farm Production | 2 years | 5 years | 7 years | S3 Glacier |
| IoT Sensor Data | 90 days | Agg only | 2 years | Compressed DB |
| Audit Logs | 2 years | 5 years | 7 years | S3 Glacier Deep |
| Email/Communications | 2 years | 5 years | 7 years | S3 Glacier |
| System Logs | 90 days | 1 year | 2 years | S3 Standard-IA |
| Temp Files | 30 days | - | 30 days | Deleted |

### Appendix B: Legal Hold Procedures

```
┌─────────────────────────────────────────────────────────────────┐
│                    LEGAL HOLD PROCEDURE                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. RECEIVE HOLD NOTICE                                          │
│     └── Legal/Compliance notifies IT of litigation hold         │
│                                                                  │
│  2. IDENTIFY SCOPE                                               │
│     └── Determine affected data, date ranges, custodians        │
│                                                                  │
│  3. SUSPEND RETENTION                                            │
│     └── Block automated purging for affected records            │
│         INSERT INTO legal_holds (case_id, scope, until_date)    │
│                                                                  │
│  4. PRESERVE DATA                                                │
│     └── Create litigation hold backup                           │
│         - Snapshot affected systems                             │
│         - Export to legal hold storage                          │
│         - Chain of custody documentation                        │
│                                                                  │
│  5. MONITOR COMPLIANCE                                           │
│     └── Regular checks that hold is maintained                  │
│                                                                  │
│  6. RELEASE HOLD                                                 │
│     └── When litigation ends, resume normal retention           │
│         DELETE FROM legal_holds WHERE case_id = '...'           │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Appendix C: Emergency Procedures

| Scenario | Action | Contact |
|----------|--------|---------|
| Accidental data deletion | Restore from backup immediately | DBA on-call |
| Legal discovery request | Freeze all retention processes | Legal + CTO |
| Audit data request | Generate compliance report | Compliance Officer |
| Storage capacity critical | Emergency archive old data | Data Architect |
| Retention job failure | Manual execution + investigation | DBA |

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Data Architect | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
