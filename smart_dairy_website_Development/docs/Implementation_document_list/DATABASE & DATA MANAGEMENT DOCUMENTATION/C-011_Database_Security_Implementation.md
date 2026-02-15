# SMART DAIRY LTD.
## DATABASE SECURITY IMPLEMENTATION
### Smart Web Portal System & Integrated ERP

---

| **Document Control** | |
|---------------------|---|
| **Document ID** | C-011 |
| **Version** | 1.0 |
| **Date** | January 31, 2026 |
| **Author** | Security Architect |
| **Owner** | Database Administrator |
| **Reviewer** | CISO |

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [Security Architecture](#2-security-architecture)
3. [Access Control](#3-access-control)
4. [Data Encryption](#4-data-encryption)
5. [Audit Logging](#5-audit-logging)
6. [Network Security](#6-network-security)
7. [Security Hardening](#7-security-hardening)
8. [Compliance Controls](#8-compliance-controls)
9. [Incident Response](#9-incident-response)
10. [Appendices](#10-appendices)

---

## 1. INTRODUCTION

### 1.1 Purpose

This document defines the comprehensive security framework for protecting Smart Dairy's database infrastructure. It covers access control, encryption, auditing, and compliance requirements.

### 1.2 Security Objectives

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATABASE SECURITY OBJECTIVES                  │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  CONFIDENTIALITY                                                 │
│  • Protect sensitive data from unauthorized access              │
│  • Encrypt data at rest and in transit                          │
│  • Implement least-privilege access controls                    │
│                                                                  │
│  INTEGRITY                                                       │
│  • Ensure data accuracy and consistency                         │
│  • Prevent unauthorized data modifications                      │
│  • Maintain audit trails for all changes                        │
│                                                                  │
│  AVAILABILITY                                                    │
│  • Ensure database availability (99.9% uptime)                  │
│  • Protect against denial of service attacks                    │
│  • Maintain disaster recovery capabilities                      │
│                                                                  │
│  COMPLIANCE                                                      │
│  • Meet Bangladesh Data Protection Act requirements             │
│  • Support GDPR compliance for international operations         │
│  • Satisfy industry security standards                          │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Threat Model

| Threat | Impact | Likelihood | Risk Level | Mitigation |
|--------|--------|------------|------------|------------|
| **Unauthorized Access** | High | Medium | High | Strong authentication, RBAC |
| **SQL Injection** | Critical | Medium | High | Parameterized queries, WAF |
| **Data Exfiltration** | Critical | Low | Medium | Encryption, DLP, monitoring |
| **Insider Threat** | High | Low | Medium | Audit logging, segregation |
| **Ransomware** | Critical | Low | Medium | Backups, network isolation |
| **Denial of Service** | High | Medium | High | Rate limiting, connection pooling |

---

## 2. SECURITY ARCHITECTURE

### 2.1 Defense in Depth

```
┌─────────────────────────────────────────────────────────────────┐
│                    DEFENSE IN DEPTH ARCHITECTURE                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  LAYER 1: PERIMETER                                              │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  • AWS Security Groups                                    │   │
│  │  • WAF (AWS WAF / CloudFlare)                             │   │
│  │  • DDoS Protection                                        │   │
│  │  • VPN Access for Admins                                  │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  LAYER 2: NETWORK                                              │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  • VPC Isolation                                          │   │
│  │  • Private Subnets (Database)                             │   │
│  │  • Network ACLs                                           │   │
│  │  • TLS 1.3 Encryption                                     │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  LAYER 3: APPLICATION                                          │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  • Authentication (Odoo + JWT)                            │   │
│  │  • Authorization (RBAC)                                   │   │
│  │  • Input Validation                                       │   │
│  │  • Session Management                                     │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  LAYER 4: DATABASE                                             │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  • PostgreSQL Native Security                             │   │
│  │  • Row-Level Security                                     │   │
│  │  • Encryption at Rest (AWS KMS)                           │   │
│  │  • Audit Logging (pgAudit)                                │   │
│  └──────────────────────────────────────────────────────────┘   │
│                              │                                   │
│  LAYER 5: DATA                                                 │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  • Field-Level Encryption (PII)                           │   │
│  │  • Data Masking                                           │   │
│  │  • Data Classification                                    │   │
│  │  • Retention & Disposal                                   │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 2.2 Security Boundaries

```
┌─────────────────────────────────────────────────────────────────┐
│                    SECURITY ZONES                                │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  PUBLIC ZONE (Internet)                                          │
│  ├── CloudFlare/WAF                                              │
│  └── CDN                                                         │
│                                                                  │
│  DMZ ZONE (Public Subnet)                                        │
│  ├── Load Balancers (ALB)                                        │
│  └── Bastion Hosts (Jump boxes)                                  │
│                                                                  │
│  APPLICATION ZONE (Private Subnet)                               │
│  ├── Odoo Application Servers                                    │
│  ├── FastAPI API Servers                                         │
│  └── Celery Workers                                              │
│                                                                  │
│  DATA ZONE (Private Subnet - Isolated)                          │
│  ├── PostgreSQL RDS Primary                                      │
│  ├── PostgreSQL RDS Replica                                      │
│  ├── TimescaleDB                                                 │
│  └── Redis Cluster                                               │
│                                                                  │
│  MANAGEMENT ZONE                                                 │
│  ├── Monitoring (Prometheus/Grafana)                             │
│  ├── Logging (ELK Stack)                                         │
│  └── Backup Storage (S3)                                         │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. ACCESS CONTROL

### 3.1 Role-Based Access Control (RBAC)

```sql
-- Database Role Hierarchy
-- Create role groups
CREATE ROLE app_read;
CREATE ROLE app_write;
CREATE ROLE app_admin;
CREATE ROLE db_admin;

-- Grant schema permissions
GRANT USAGE ON SCHEMA core, farm, sales, inventory, accounting, iot TO app_read;
GRANT USAGE ON SCHEMA core, farm, sales, inventory, accounting, iot TO app_write;

-- Grant table permissions to read role
GRANT SELECT ON ALL TABLES IN SCHEMA core TO app_read;
GRANT SELECT ON ALL TABLES IN SCHEMA farm TO app_read;
GRANT SELECT ON ALL TABLES IN SCHEMA sales TO app_read;
GRANT SELECT ON ALL TABLES IN SCHEMA inventory TO app_read;
GRANT SELECT ON ALL TABLES IN SCHEMA accounting TO app_read;
GRANT SELECT ON ALL TABLES IN SCHEMA iot TO app_read;

-- Grant write permissions
GRANT INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA core TO app_write;
GRANT INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA farm TO app_write;
GRANT INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA sales TO app_write;
GRANT INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA inventory TO app_write;

-- Grant sequence usage
GRANT USAGE ON ALL SEQUENCES IN SCHEMA core TO app_write;
GRANT USAGE ON ALL SEQUENCES IN SCHEMA farm TO app_write;

-- Create application user
CREATE USER smart_dairy_app WITH PASSWORD 'strong_random_password';
GRANT app_write TO smart_dairy_app;

-- Create read-only reporting user
CREATE USER smart_dairy_report WITH PASSWORD 'another_strong_password';
GRANT app_read TO smart_dairy_report;

-- Create admin user (for migrations)
CREATE USER smart_dairy_admin WITH PASSWORD 'admin_strong_password';
GRANT app_admin TO smart_dairy_admin;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA core TO app_admin;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA farm TO app_admin;
```

### 3.2 Application-Level Access Control

```python
# Odoo access control implementation
from odoo import models, fields, api
from odoo.exceptions import AccessDenied

class AccessControl:
    """Application-level access control for Smart Dairy"""
    
    # Permission matrix
    PERMISSIONS = {
        'farm_manager': {
            'farm.animal': ['read', 'write', 'create'],
            'farm.milk_production': ['read', 'write', 'create'],
            'sales.order': ['read'],
        },
        'sales_rep': {
            'sales.order': ['read', 'write', 'create'],
            'core.res_partner': ['read', 'write', 'create'],
            'farm.animal': ['read'],
        },
        'accountant': {
            'accounting.move': ['read', 'write'],
            'sales.order': ['read'],
            'farm.*': ['read'],
        },
        'data_analyst': {
            '*': ['read'],  # Read-only access to all
        }
    }
    
    def check_permission(self, user_role, model, operation):
        """Check if role has permission for operation on model"""
        role_perms = self.PERMISSIONS.get(user_role, {})
        
        # Check wildcard
        if '*' in role_perms:
            return operation in role_perms['*']
        
        # Check specific model
        model_perms = role_perms.get(model, [])
        return operation in model_perms
    
    def get_accessible_records(self, user, model):
        """Get record IDs accessible to user (multi-tenant filter)"""
        # Filter by company for multi-tenant isolation
        if user.company_id:
            return model.search([('company_id', 'in', user.company_ids.ids)])
        return model.search([])
```

### 3.3 Row-Level Security (RLS)

```sql
-- Enable Row-Level Security for multi-tenant isolation

-- Add company_id to tables for tenant isolation
ALTER TABLE core.res_partner ADD COLUMN company_id INTEGER REFERENCES core.res_company(id);
ALTER TABLE farm.animal ADD COLUMN company_id INTEGER REFERENCES core.res_company(id);
ALTER TABLE sales.order ADD COLUMN company_id INTEGER REFERENCES core.res_company(id);

-- Enable RLS on tables
ALTER TABLE core.res_partner ENABLE ROW LEVEL SECURITY;
ALTER TABLE farm.animal ENABLE ROW LEVEL SECURITY;
ALTER TABLE sales.order ENABLE ROW LEVEL SECURITY;

-- Create policy for company isolation
CREATE POLICY company_isolation_policy ON core.res_partner
    USING (company_id = current_setting('app.current_company_id')::INTEGER);

CREATE POLICY company_isolation_policy ON farm.animal
    USING (company_id = current_setting('app.current_company_id')::INTEGER);

CREATE POLICY company_isolation_policy ON sales.order
    USING (company_id = current_setting('app.current_company_id')::INTEGER);

-- Function to set company context
CREATE OR REPLACE FUNCTION set_company_context(company_id INTEGER)
RETURNS VOID AS $$
BEGIN
    PERFORM set_config('app.current_company_id', company_id::TEXT, FALSE);
END;
$$ LANGUAGE plpgsql;
```

---

## 4. DATA ENCRYPTION

### 4.1 Encryption at Rest

```bash
# AWS RDS Encryption Configuration
# Encryption is enabled at instance creation and cannot be disabled

# Create encrypted RDS instance
aws rds create-db-instance \
    --db-instance-identifier smart-dairy-db \
    --db-instance-class db.r6g.xlarge \
    --engine postgres \
    --engine-version 16.1 \
    --allocated-storage 500 \
    --storage-encrypted \
    --kms-key-id alias/smart-dairy-db-key \
    --master-username dbadmin \
    --master-user-password 'VeryStrongPassword123!'

# Verify encryption
aws rds describe-db-instances \
    --db-instance-identifier smart-dairy-db \
    --query 'DBInstances[0].StorageEncrypted'
```

### 4.2 Field-Level Encryption for PII

```python
# Field-level encryption for sensitive data
from cryptography.fernet import Fernet
from django.conf import settings
import base64

class FieldEncryption:
    """Encrypt sensitive fields at application level"""
    
    def __init__(self):
        self.cipher = Fernet(settings.ENCRYPTION_KEY)
    
    def encrypt(self, plaintext: str) -> str:
        """Encrypt a string value"""
        if not plaintext:
            return None
        return self.cipher.encrypt(plaintext.encode()).decode()
    
    def decrypt(self, ciphertext: str) -> str:
        """Decrypt an encrypted value"""
        if not ciphertext:
            return None
        return self.cipher.decrypt(ciphertext.encode()).decode()

# Usage in models
class ResPartner(models.Model):
    name = models.CharField(max_length=255)
    email = models.CharField(max_length=255)
    
    # Encrypted fields
    _mobile_encrypted = models.TextField(db_column='mobile')
    _national_id_encrypted = models.TextField(db_column='national_id', null=True)
    
    encryption = FieldEncryption()
    
    @property
    def mobile(self):
        return self.encryption.decrypt(self._mobile_encrypted)
    
    @mobile.setter
    def mobile(self, value):
        self._mobile_encrypted = self.encryption.encrypt(value)
```

### 4.3 Encryption in Transit

```yaml
# postgresql.conf settings for TLS
ssl = on
ssl_cert_file = '/etc/ssl/certs/server.crt'
ssl_key_file = '/etc/ssl/private/server.key'
ssl_ca_file = '/etc/ssl/certs/ca.crt'
ssl_crl_file = ''

# Require TLS for all connections
ssl_min_protocol_version = 'TLSv1.3'
ssl_ciphers = 'HIGH:!aNULL:!MD5'

# Force SSL for specific users
# In pg_hba.conf:
# hostssl all all 0.0.0.0/0 scram-sha-256
```

```python
# Python connection with SSL
import psycopg2

conn = psycopg2.connect(
    host="smart-dairy-db.cluster-xxx.ap-south-1.rds.amazonaws.com",
    database="smart_dairy_prod",
    user="smart_dairy_app",
    password="strong_password",
    sslmode="require",  # Enforce SSL
    sslrootcert="/path/to/rds-ca.pem"  # Verify RDS certificate
)
```

---

## 5. AUDIT LOGGING

### 5.1 pgAudit Configuration

```sql
-- Install pgAudit extension
CREATE EXTENSION IF NOT EXISTS pgaudit;

-- Configure audit logging
ALTER SYSTEM SET pgaudit.log = 'write, ddl';
ALTER SYSTEM SET pgaudit.log_catalog = off;
ALTER SYSTEM SET pgaudit.log_parameter = on;
ALTER SYSTEM SET pgaudit.log_relation = on;
ALTER SYSTEM SET pgaudit.log_statement_once = off;

-- Reload configuration
SELECT pg_reload_conf();

-- Create audit log table
CREATE TABLE audit.database_audit_log (
    id BIGSERIAL PRIMARY KEY,
    event_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_name TEXT,
    database_name TEXT,
    process_id INTEGER,
    connection_from TEXT,
    session_id TEXT,
    session_line_num BIGINT,
    command_tag TEXT,
    object_type TEXT,
    object_name TEXT,
    sql_text TEXT,
    parameters TEXT
);

-- Grant insert to audit role
GRANT INSERT ON audit.database_audit_log TO app_write;
```

### 5.2 Custom Audit Triggers

```sql
-- Comprehensive audit trigger for critical tables
CREATE OR REPLACE FUNCTION audit.audit_trigger()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        INSERT INTO audit.record_changes (
            table_name,
            operation,
            record_id,
            new_values,
            changed_by,
            changed_at
        ) VALUES (
            TG_TABLE_NAME,
            'INSERT',
            NEW.id,
            row_to_json(NEW),
            current_user,
            NOW()
        );
        RETURN NEW;
        
    ELSIF TG_OP = 'UPDATE' THEN
        INSERT INTO audit.record_changes (
            table_name,
            operation,
            record_id,
            old_values,
            new_values,
            changed_by,
            changed_at
        ) VALUES (
            TG_TABLE_NAME,
            'UPDATE',
            NEW.id,
            row_to_json(OLD),
            row_to_json(NEW),
            current_user,
            NOW()
        );
        RETURN NEW;
        
    ELSIF TG_OP = 'DELETE' THEN
        INSERT INTO audit.record_changes (
            table_name,
            operation,
            record_id,
            old_values,
            changed_by,
            changed_at
        ) VALUES (
            TG_TABLE_NAME,
            'DELETE',
            OLD.id,
            row_to_json(OLD),
            current_user,
            NOW()
        );
        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

-- Apply to critical tables
CREATE TRIGGER audit_res_partner
    AFTER INSERT OR UPDATE OR DELETE ON core.res_partner
    FOR EACH ROW EXECUTE FUNCTION audit.audit_trigger();

CREATE TRIGGER audit_farm_animal
    AFTER INSERT OR UPDATE OR DELETE ON farm.animal
    FOR EACH ROW EXECUTE FUNCTION audit.audit_trigger();

CREATE TRIGGER audit_sales_order
    AFTER INSERT OR UPDATE OR DELETE ON sales.order
    FOR EACH ROW EXECUTE FUNCTION audit.audit_trigger();
```

### 5.3 Audit Log Management

```sql
-- Automated audit log archiving
CREATE OR REPLACE FUNCTION archive_old_audit_logs()
RETURNS void AS $$
BEGIN
    -- Export old logs to S3 (via foreign data wrapper or external script)
    -- Then delete archived records
    
    DELETE FROM audit.record_changes
    WHERE changed_at < NOW() - INTERVAL '2 years';
    
    DELETE FROM audit.database_audit_log
    WHERE event_time < NOW() - INTERVAL '2 years';
END;
$$ LANGUAGE plpgsql;

-- Schedule archiving (using pg_cron)
SELECT cron.schedule('archive-audit-logs', '0 2 1 * *', 
    'SELECT archive_old_audit_logs()');
```

---

## 6. NETWORK SECURITY

### 6.1 AWS Security Groups

```bash
# Database Security Group
# Allow connections only from application servers

aws ec2 create-security-group \
    --group-name smart-dairy-db-sg \
    --description "Security group for Smart Dairy database"

# Allow PostgreSQL from application subnet only
aws ec2 authorize-security-group-ingress \
    --group-name smart-dairy-db-sg \
    --protocol tcp \
    --port 5432 \
    --source-group sg-app-servers

# Allow SSH from bastion only (for admin access)
aws ec2 authorize-security-group-ingress \
    --group-name smart-dairy-db-sg \
    --protocol tcp \
    --port 22 \
    --source-group sg-bastion

# Explicitly deny all other inbound
aws ec2 authorize-security-group-egress \
    --group-name smart-dairy-db-sg \
    --protocol -1 \
    --cidr 0.0.0.0/0
```

### 6.2 Connection Security

```python
# Connection pooling with security settings
DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.postgresql',
        'NAME': 'smart_dairy_prod',
        'USER': 'smart_dairy_app',
        'PASSWORD': os.environ['DB_PASSWORD'],  # From secrets manager
        'HOST': 'smart-dairy-db.cluster-xxx.ap-south-1.rds.amazonaws.com',
        'PORT': '5432',
        'OPTIONS': {
            'sslmode': 'require',
            'connect_timeout': 10,
        },
        'CONN_MAX_AGE': 600,
        'CONN_HEALTH_CHECKS': True,
    }
}

# Connection restrictions
MAX_CONNECTIONS = 200  # PostgreSQL max_connections
SUPERUSER_RESERVED_CONNECTIONS = 3

# Application connection limits
CONNECTION_POOL_SIZE = 20
CONNECTION_MAX_OVERFLOW = 10
CONNECTION_POOL_TIMEOUT = 30
```

---

## 7. SECURITY HARDENING

### 7.1 PostgreSQL Hardening

```sql
-- postgresql.conf hardening settings

-- Authentication
password_encryption = 'scram-sha-256'
ssl = on
ssl_min_protocol_version = 'TLSv1.2'

-- Connection limits
max_connections = 200
superuser_reserved_connections = 3

-- Statement timeout to prevent runaway queries
statement_timeout = '5min'
idle_in_transaction_session_timeout = '10min'
lock_timeout = '30s'

-- Logging
log_connections = on
log_disconnections = on
log_line_prefix = '%t [%p]: [%l-1] user=%u,db=%d,app=%a,client=%h '
log_statement = 'ddl'  -- Log DDL statements
log_min_duration_statement = 1000  -- Log slow queries (>1s)
log_checkpoints = on
log_lock_waits = on

-- Prevent dangerous operations
allow_system_table_mods = off
ignore_system_indexes = off

-- File permissions
log_file_mode = 0600
```

### 7.2 pg_hba.conf Access Control

```
# TYPE  DATABASE        USER            ADDRESS                 METHOD

# Local connections (Unix socket)
local   all             postgres                                peer
local   all             all                                     scram-sha-256

# IPv4 local connections (admin only via bastion)
hostssl smart_dairy_prod dbadmin     10.0.1.0/24             scram-sha-256

# Application servers
hostssl smart_dairy_prod smart_dairy_app 10.0.2.0/24         scram-sha-256
hostssl smart_dairy_prod smart_dairy_report 10.0.2.0/24       scram-sha-256

# Replication
hostssl replication     replicator      10.0.3.0/24             scram-sha-256

# Deny all others
host    all             all             0.0.0.0/0               reject
host    all             all             ::/0                    reject
```

---

## 8. COMPLIANCE CONTROLS

### 8.1 Bangladesh Data Protection Act Compliance

| Requirement | Implementation | Evidence |
|-------------|----------------|----------|
| **Data Minimization** | Only collect necessary data | Schema documentation |
| **Purpose Limitation** | Data use aligned with consent | Privacy policy |
| **Security Safeguards** | Encryption, access controls | Security audit logs |
| **Data Subject Rights** | Access, correction, deletion | API endpoints for DSAR |
| **Breach Notification** | 72-hour notification process | Incident response plan |
| **Data Localization** | Primary data stays in Bangladesh | AWS ap-south-1 region |

### 8.2 Data Subject Access Request (DSAR) Implementation

```python
class DSARHandler:
    """Handle Data Subject Access Requests"""
    
    def export_user_data(self, partner_id):
        """Export all data for a data subject"""
        
        export_data = {
            'profile': self.get_profile_data(partner_id),
            'orders': self.get_order_history(partner_id),
            'communications': self.get_communications(partner_id),
            'audit_log': self.get_audit_trail(partner_id),
        }
        
        # Generate PDF report
        report = self.generate_pdf_report(export_data)
        
        # Log the export
        self.log_dsar_export(partner_id, 'access')
        
        return report
    
    def delete_user_data(self, partner_id):
        """Delete/anonymize user data (Right to Erasure)"""
        
        # Check for legal holds
        if self.has_legal_hold(partner_id):
            raise Exception("Cannot delete: Legal hold in effect")
        
        # Anonymize personal data
        self.anonymize_partner_data(partner_id)
        
        # Delete associated records
        self.delete_associated_data(partner_id);
        
        # Log the deletion
        self.log_dsar_export(partner_id, 'deletion')
    
    def get_profile_data(self, partner_id):
        """Get partner profile data"""
        return self.execute("""
            SELECT name, email, mobile, customer_type, 
                   created_at, write_date
            FROM core.res_partner
            WHERE id = %s
        """, (partner_id,))
```

---

## 9. INCIDENT RESPONSE

### 9.1 Security Incident Classification

| Severity | Definition | Examples | Response Time |
|----------|------------|----------|---------------|
| **Critical** | Data breach, ransomware | Unauthorized admin access | Immediate |
| **High** | Potential breach, DoS | Failed auth spike, SQL injection attempt | 1 hour |
| **Medium** | Policy violation | Unusual query patterns | 4 hours |
| **Low** | Minor issues | Failed login attempts | 24 hours |

### 9.2 Incident Response Playbook

```
┌─────────────────────────────────────────────────────────────────┐
│                    SECURITY INCIDENT RESPONSE                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  1. DETECTION                                                    │
│     ├── Alert from monitoring system                            │
│     ├── User report                                             │
│     └── Audit log analysis                                      │
│                                                                  │
│  2. CONTAINMENT                                                  │
│     ├── Isolate affected systems                                │
│     │   REVOKE ALL PRIVILEGES FROM suspicious_user;             │
│     ├── Block suspicious IP addresses                           │
│     │   (Update security groups)                                │
│     └── Enable enhanced logging                                 │
│                                                                  │
│  3. INVESTIGATION                                                │
│     ├── Review audit logs                                       │
│     │   SELECT * FROM audit.database_audit_log                  │
│     │   WHERE event_time > NOW() - INTERVAL '1 hour';           │
│     ├── Identify scope of breach                                │
│     └── Preserve evidence                                       │
│                                                                  │
│  4. ERADICATION                                                  │
│     ├── Remove malicious access                                 │
│     ├── Patch vulnerabilities                                   │
│     └── Reset compromised credentials                           │
│                                                                  │
│  5. RECOVERY                                                     │
│     ├── Restore from clean backup if needed                     │
│     ├── Re-enable systems                                       │
│     └── Monitor for recurrence                                  │
│                                                                  │
│  6. POST-INCIDENT                                                │
│     ├── Document lessons learned                                │
│     ├── Update security controls                                │
│     └── Report to authorities if required                       │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### 9.3 Database Security Monitoring

```sql
-- Suspicious activity detection queries

-- Failed login attempts
SELECT 
    client_addr,
    COUNT(*) as failed_attempts,
    MAX(event_time) as last_attempt
FROM audit.database_audit_log
WHERE command_tag = 'authentication'
  AND error_severity = 'FATAL'
  AND event_time > NOW() - INTERVAL '1 hour'
GROUP BY client_addr
HAVING COUNT(*) > 5;

-- Unusual data access patterns
SELECT 
    user_name,
    table_name,
    COUNT(*) as access_count,
    COUNT(DISTINCT record_id) as unique_records
FROM audit.record_changes
WHERE changed_at > NOW() - INTERVAL '1 hour'
GROUP BY user_name, table_name
HAVING COUNT(*) > 1000;  -- Threshold for bulk access

-- After-hours database access
SELECT 
    user_name,
    COUNT(*) as query_count
FROM audit.database_audit_log
WHERE EXTRACT(HOUR FROM event_time) NOT BETWEEN 6 AND 22
  AND event_time > NOW() - INTERVAL '1 day'
GROUP BY user_name;
```

---

## 10. APPENDICES

### Appendix A: Security Checklist

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATABASE SECURITY CHECKLIST                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  DEPLOYMENT                                                      │
│  [ ] Encryption at rest enabled (AWS KMS)                       │
│  [ ] Encryption in transit enforced (TLS 1.2+)                  │
│  [ ] Security groups configured                                 │
│  [ ] Database in private subnet                                 │
│  [ ] No public access enabled                                   │
│                                                                  │
│  ACCESS CONTROL                                                  │
│  [ ] Strong passwords enforced                                  │
│  [ ] Role-based access implemented                              │
│  [ ] Least privilege principle applied                          │
│  [ ] Row-level security enabled                                 │
│  [ ] Service accounts separate from user accounts               │
│                                                                  │
│  AUDITING                                                        │
│  [ ] pgAudit enabled                                            │
│  [ ] Custom audit triggers on critical tables                   │
│  [ ] Audit logs retained for 2+ years                           │
│  [ ] Log monitoring alerts configured                           │
│                                                                  │
│  HARDENING                                                       │
│  [ ] Default passwords changed                                  │
│  [ ] Unnecessary extensions removed                             │
│  [ ] Statement timeout configured                               │
│  [ ] Connection limits set                                      │
│  [ ] Automated backup encryption enabled                        │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Appendix B: Security Tools

| Tool | Purpose | Usage |
|------|---------|-------|
| **AWS GuardDuty** | Threat detection | Monitor for malicious activity |
| **AWS Security Hub** | Security posture | Centralized security findings |
| **pgAudit** | Database auditing | Log database activities |
| **SSL Labs** | TLS validation | Test SSL configuration |
| **sqlmap** | SQL injection testing | Penetration testing |

### Appendix C: Password Policy

```sql
-- Password policy enforcement
ALTER SYSTEM SET password_encryption = 'scram-sha-256';

-- Create password check extension
CREATE EXTENSION IF NOT EXISTS passwordcheck;

-- Password requirements:
-- Minimum 12 characters
-- At least 1 uppercase, 1 lowercase, 1 digit, 1 special character
-- No username in password
-- No common words
```

---

## DOCUMENT REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | January 31, 2026 | Security Architect | Initial release |

---

*This document is the property of Smart Dairy Ltd. and contains confidential information.*
*Unauthorized distribution or reproduction is prohibited.*
