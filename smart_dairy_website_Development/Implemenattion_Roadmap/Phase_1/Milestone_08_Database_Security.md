# Milestone 8: Database Security & High Availability

## Smart Dairy Digital Smart Portal + ERP — Phase 1: Foundation

| Field | Detail |
|---|---|
| **Milestone** | 8 of 10 |
| **Title** | Database Security & High Availability |
| **Phase** | Phase 1 — Foundation (Part B: Database & Security) |
| **Days** | Days 71–80 (of 100) |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03 |

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

### 1.1 Purpose

Milestone 8 hardens every data-layer component of the Smart Dairy platform — PostgreSQL 16, TimescaleDB, Elasticsearch 8, and Redis 7 — against unauthorized access, data leakage, and infrastructure failure. It also establishes a high-availability PostgreSQL cluster with automated failover and a tested disaster-recovery procedure. By the end of Day 80, Smart Dairy's 255-cattle, 900 L/day operation will be backed by an encrypted, audited, and resilient data tier that meets Bangladesh ICT Division compliance expectations.

### 1.2 Scope

| Area | In Scope | Out of Scope |
|---|---|---|
| Row-Level Security | RLS policies on all farm-schema tables | Application-layer RBAC (delivered in Milestone 6) |
| Column Encryption | PII fields: phone, email, NID, bank account | Full-disk encryption (OS-level) |
| Connection Security | SSL/TLS mutual auth, pg_hba.conf hardening | VPN tunnel configuration |
| High Availability | Streaming replication with Patroni, HAProxy | Multi-region geo-replication |
| TimescaleDB | Hypertables, continuous aggregates, retention | Advanced analytics dashboards (Phase 2) |
| Elasticsearch | xpack security, TLS, field-level security | Machine-learning anomaly detection |
| Redis | ACLs, TLS, namespace isolation | Redis Sentinel (using single-node + ACLs) |
| Audit Logging | pgAudit, DDL/DML tracking, log archival | SIEM integration (Phase 2) |
| Disaster Recovery | Failover drill, RTO/RPO measurement | Full business-continuity plan |

### 1.3 Success Criteria

| Criterion | Target |
|---|---|
| RLS policies enforced on all farm tables | 100% coverage across 8 schemas |
| PII columns encrypted at rest | AES-256-GCM for all identified fields |
| PostgreSQL connections over TLS | 100% (reject plaintext) |
| Replication lag under normal load | < 100 ms |
| Automatic failover time (RTO) | < 30 seconds |
| Recovery Point Objective (RPO) | 0 data loss (synchronous standby) |
| TimescaleDB compression ratio | > 10:1 on sensor data older than 7 days |
| Audit log coverage | All DDL + sensitive DML captured |
| DR drill completion | Pass with documented results |

### 1.4 Team Allocation

| Developer | Primary Role This Milestone | Hours/Day |
|---|---|---|
| Dev 1 | PostgreSQL RLS, encryption, pgAudit, Patroni config, TimescaleDB | ~8 |
| Dev 2 | Docker HA cluster, SSL certs, HAProxy, Elasticsearch/Redis security, DR drill | ~8 |
| Dev 3 | Security dashboard OWL components, monitoring alerts UI, documentation | ~8 |

---

## 2. Requirement Traceability Matrix

| Req ID | Requirement Description | Source Document | Day(s) | Deliverable |
|---|---|---|---|---|
| C-001 | Database design and schema enforcement | DB Design Spec | 71, 75 | RLS policies, hypertables |
| C-011 | Database security controls | Security Architecture (M6) | 71–73, 76–78 | RLS, encryption, TLS, audit |
| C-008 | TimescaleDB time-series guide | TimescaleDB Guide | 75 | Hypertables, aggregates, retention |
| C-011.1 | Row-level data isolation by farm | Security Architecture | 71 | RLS CREATE POLICY statements |
| C-011.2 | PII encryption at rest | Security Architecture | 72 | Fernet + pgcrypto encryption |
| C-011.3 | Encrypted database connections | Security Architecture | 73 | SSL mutual auth, pg_hba.conf |
| C-011.4 | High availability and failover | Infrastructure Spec | 74, 79 | Patroni cluster, DR drill |
| C-011.5 | Search engine security | Security Architecture | 76 | Elasticsearch xpack config |
| C-011.6 | Cache layer security | Security Architecture | 77 | Redis ACLs and TLS |
| C-011.7 | Database audit trail | Compliance Requirements | 78 | pgAudit setup, log rotation |

---

## 3. Day-by-Day Breakdown

### Day 71 — PostgreSQL Row-Level Security (RLS) Policies

**Objective:** Enforce data isolation at the database layer so that each user role can only access rows belonging to their assigned farm or organizational unit.

#### Dev 1 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Design RLS policy matrix: map each schema table to isolation columns (farm_id, company_id, user_id) | 1.5 |
| 2 | Write CREATE POLICY statements for `farm` schema tables (animal, milk_production, health_record, feed_schedule, breeding_record) | 2.5 |
| 3 | Write CREATE POLICY statements for `sales`, `inventory`, and `accounting` schema tables | 2.0 |
| 4 | Create helper function `auth.current_farm_id()` using `current_setting('app.current_farm_id')` | 1.0 |
| 5 | Test RLS with multiple database roles, verify row filtering | 1.0 |

**RLS Policies SQL:**

```sql
-- =============================================================
-- Row-Level Security Policies for Smart Dairy
-- Schema: farm, sales, inventory, accounting
-- =============================================================

-- Enable RLS on farm schema tables
ALTER TABLE farm.animal ENABLE ROW LEVEL SECURITY;
ALTER TABLE farm.milk_production ENABLE ROW LEVEL SECURITY;
ALTER TABLE farm.health_record ENABLE ROW LEVEL SECURITY;
ALTER TABLE farm.feed_schedule ENABLE ROW LEVEL SECURITY;
ALTER TABLE farm.breeding_record ENABLE ROW LEVEL SECURITY;

-- Force RLS even for table owners (security best practice)
ALTER TABLE farm.animal FORCE ROW LEVEL SECURITY;
ALTER TABLE farm.milk_production FORCE ROW LEVEL SECURITY;
ALTER TABLE farm.health_record FORCE ROW LEVEL SECURITY;

-- Helper function: retrieve current farm context
CREATE OR REPLACE FUNCTION auth.current_farm_id()
RETURNS INTEGER AS $$
BEGIN
    RETURN NULLIF(current_setting('app.current_farm_id', true), '')::INTEGER;
EXCEPTION
    WHEN OTHERS THEN
        RETURN NULL;
END;
$$ LANGUAGE plpgsql STABLE SECURITY DEFINER;

-- Helper function: check role membership
CREATE OR REPLACE FUNCTION auth.has_role(role_name TEXT)
RETURNS BOOLEAN AS $$
BEGIN
    RETURN EXISTS (
        SELECT 1 FROM pg_roles
        WHERE pg_has_role(current_user, oid, 'member')
        AND rolname = role_name
    );
END;
$$ LANGUAGE plpgsql STABLE SECURITY DEFINER;

-- ---- farm.animal ----
CREATE POLICY animal_farm_isolation ON farm.animal
    FOR ALL
    USING (farm_id = auth.current_farm_id())
    WITH CHECK (farm_id = auth.current_farm_id());

CREATE POLICY animal_admin_bypass ON farm.animal
    FOR ALL
    USING (auth.has_role('dairy_admin'))
    WITH CHECK (auth.has_role('dairy_admin'));

-- ---- farm.milk_production ----
CREATE POLICY milk_prod_farm_isolation ON farm.milk_production
    FOR ALL
    USING (farm_id = auth.current_farm_id())
    WITH CHECK (farm_id = auth.current_farm_id());

CREATE POLICY milk_prod_readonly_analyst ON farm.milk_production
    FOR SELECT
    USING (auth.has_role('dairy_analyst'));

-- ---- farm.health_record ----
CREATE POLICY health_farm_isolation ON farm.health_record
    FOR ALL
    USING (farm_id = auth.current_farm_id())
    WITH CHECK (farm_id = auth.current_farm_id());

CREATE POLICY health_vet_access ON farm.health_record
    FOR ALL
    USING (auth.has_role('dairy_veterinarian'))
    WITH CHECK (auth.has_role('dairy_veterinarian'));

-- ---- sales schema ----
ALTER TABLE sales.sale_order ENABLE ROW LEVEL SECURITY;
ALTER TABLE sales.customer ENABLE ROW LEVEL SECURITY;

CREATE POLICY sale_order_farm_isolation ON sales.sale_order
    FOR ALL
    USING (farm_id = auth.current_farm_id())
    WITH CHECK (farm_id = auth.current_farm_id());

CREATE POLICY customer_farm_isolation ON sales.customer
    FOR ALL
    USING (farm_id = auth.current_farm_id())
    WITH CHECK (farm_id = auth.current_farm_id());

-- ---- inventory schema ----
ALTER TABLE inventory.stock_move ENABLE ROW LEVEL SECURITY;

CREATE POLICY stock_move_farm_isolation ON inventory.stock_move
    FOR ALL
    USING (farm_id = auth.current_farm_id())
    WITH CHECK (farm_id = auth.current_farm_id());

-- ---- accounting schema ----
ALTER TABLE accounting.journal_entry ENABLE ROW LEVEL SECURITY;

CREATE POLICY journal_entry_farm_isolation ON accounting.journal_entry
    FOR ALL
    USING (farm_id = auth.current_farm_id())
    WITH CHECK (farm_id = auth.current_farm_id());

CREATE POLICY journal_entry_accountant ON accounting.journal_entry
    FOR SELECT
    USING (auth.has_role('dairy_accountant'));
```

#### Dev 2 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Create database roles: `dairy_admin`, `dairy_manager`, `dairy_worker`, `dairy_analyst`, `dairy_veterinarian`, `dairy_accountant` | 2.0 |
| 2 | Write Ansible playbook to deploy RLS policies across environments | 2.5 |
| 3 | Configure Odoo database session to inject `app.current_farm_id` via `SET LOCAL` on each request | 2.0 |
| 4 | Write integration tests: verify cross-farm data leakage is impossible | 1.5 |

#### Dev 3 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Design Security Dashboard wireframe (RLS status, encryption status, HA status panels) | 3.0 |
| 2 | Build OWL `RlsPolicyStatusWidget` showing per-table RLS enforcement status | 3.0 |
| 3 | Document RLS policy design decisions and role-to-policy mapping | 2.0 |

**End-of-Day 71 Deliverables:**
- [ ] RLS policies active on all farm, sales, inventory, accounting tables
- [ ] Database roles created and mapped
- [ ] Cross-farm isolation verified by tests
- [ ] RLS status widget rendering in dashboard

---

### Day 72 — Column-Level Encryption for PII Fields

**Objective:** Encrypt all personally identifiable information (PII) at the column level using Fernet (AES-256-GCM) in the application layer and pgcrypto at the database layer.

#### Dev 1 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Implement `FernetColumnEncryption` Python utility class | 3.0 |
| 2 | Integrate encryption with Odoo ORM fields via computed stored fields | 2.0 |
| 3 | Configure pgcrypto extension and write SQL helper functions | 1.5 |
| 4 | Implement key rotation mechanism | 1.5 |

**Column Encryption Python Utility:**

```python
# addons/smart_dairy_security/utils/column_encryption.py
"""
Column-level encryption for PII fields using Fernet (AES-256-CBC with HMAC).
Supports key rotation and integrates with Odoo ORM.
"""

import base64
import os
import json
import logging
from datetime import datetime, timezone
from cryptography.fernet import Fernet, MultiFernet, InvalidToken
from cryptography.hazmat.primitives.ciphers.aead import AESGCM
from odoo.exceptions import UserError

_logger = logging.getLogger(__name__)


class FernetColumnEncryption:
    """
    Manages Fernet-based encryption for database column values.
    Supports multiple keys for seamless key rotation.
    """

    def __init__(self, key_path: str = None, keys: list[str] = None):
        """
        Initialize with either a key file path or a list of base64 keys.
        The first key in the list is the active encryption key;
        subsequent keys are used only for decryption during rotation.
        """
        if keys:
            self._keys = keys
        elif key_path and os.path.exists(key_path):
            with open(key_path, 'r') as f:
                key_data = json.load(f)
            self._keys = key_data.get('keys', [])
        else:
            raise ValueError("Either key_path or keys must be provided")

        if not self._keys:
            raise ValueError("At least one encryption key is required")

        fernet_instances = [Fernet(k.encode()) for k in self._keys]
        self._multi_fernet = MultiFernet(fernet_instances)
        self._primary_fernet = fernet_instances[0]

    @staticmethod
    def generate_key() -> str:
        """Generate a new Fernet-compatible encryption key."""
        return Fernet.generate_key().decode()

    def encrypt(self, plaintext: str) -> str:
        """
        Encrypt a plaintext string. Returns base64-encoded ciphertext.
        Returns empty string for None/empty input.
        """
        if not plaintext:
            return ''
        try:
            token = self._primary_fernet.encrypt(plaintext.encode('utf-8'))
            return base64.urlsafe_b64encode(token).decode('ascii')
        except Exception as e:
            _logger.error("Encryption failed: %s", e)
            raise UserError("Failed to encrypt sensitive data") from e

    def decrypt(self, ciphertext: str) -> str:
        """
        Decrypt a ciphertext string. Tries all keys in order
        (primary first, then rotated-out keys).
        """
        if not ciphertext:
            return ''
        try:
            token = base64.urlsafe_b64decode(ciphertext.encode('ascii'))
            return self._multi_fernet.decrypt(token).decode('utf-8')
        except InvalidToken:
            _logger.error("Decryption failed: invalid token or expired key")
            raise UserError("Cannot decrypt data — key may have been rotated out")
        except Exception as e:
            _logger.error("Decryption failed: %s", e)
            raise UserError("Failed to decrypt sensitive data") from e

    def rotate_key(self, new_key: str = None) -> str:
        """
        Generate or accept a new key, prepend to key list.
        Returns the new key for storage.
        """
        if new_key is None:
            new_key = self.generate_key()

        self._keys.insert(0, new_key)
        fernet_instances = [Fernet(k.encode()) for k in self._keys]
        self._multi_fernet = MultiFernet(fernet_instances)
        self._primary_fernet = fernet_instances[0]

        _logger.info("Encryption key rotated at %s", datetime.now(timezone.utc))
        return new_key

    def re_encrypt_value(self, ciphertext: str) -> str:
        """Decrypt with any key and re-encrypt with the current primary key."""
        plaintext = self.decrypt(ciphertext)
        return self.encrypt(plaintext)


class AesGcmEncryption:
    """
    AES-256-GCM encryption for fields requiring authenticated encryption.
    Used for highly sensitive fields like bank account numbers.
    """

    def __init__(self, key: bytes):
        if len(key) != 32:
            raise ValueError("AES-256-GCM requires a 32-byte key")
        self._key = key
        self._aesgcm = AESGCM(key)

    def encrypt(self, plaintext: str) -> str:
        nonce = os.urandom(12)
        ciphertext = self._aesgcm.encrypt(
            nonce, plaintext.encode('utf-8'), None
        )
        payload = nonce + ciphertext
        return base64.b64encode(payload).decode('ascii')

    def decrypt(self, token: str) -> str:
        payload = base64.b64decode(token.encode('ascii'))
        nonce = payload[:12]
        ciphertext = payload[12:]
        plaintext = self._aesgcm.decrypt(nonce, ciphertext, None)
        return plaintext.decode('utf-8')
```

**Odoo ORM Integration:**

```python
# addons/smart_dairy_security/models/encrypted_mixin.py
from odoo import models, fields, api
from ..utils.column_encryption import FernetColumnEncryption

_encryptor = None

def _get_encryptor(env):
    global _encryptor
    if _encryptor is None:
        key_path = env['ir.config_parameter'].sudo().get_param(
            'smart_dairy.encryption_key_path',
            '/etc/smart_dairy/keys/column_keys.json'
        )
        _encryptor = FernetColumnEncryption(key_path=key_path)
    return _encryptor


class EncryptedFieldMixin(models.AbstractModel):
    _name = 'smart_dairy.encrypted.mixin'
    _description = 'Mixin for models with encrypted PII fields'

    def _encrypt_field(self, field_name, value):
        enc = _get_encryptor(self.env)
        return enc.encrypt(value) if value else ''

    def _decrypt_field(self, field_name, value):
        enc = _get_encryptor(self.env)
        return enc.decrypt(value) if value else ''


class EncryptedPartner(models.Model):
    _inherit = 'res.partner'
    _name = 'res.partner'

    phone_encrypted = fields.Char(string='Phone (Encrypted)', groups='base.group_system')
    email_encrypted = fields.Char(string='Email (Encrypted)', groups='base.group_system')
    nid_encrypted = fields.Char(string='NID (Encrypted)', groups='base.group_system')
    bank_account_encrypted = fields.Char(string='Bank Account (Encrypted)', groups='base.group_system')

    @api.model_create_multi
    def create(self, vals_list):
        enc = _get_encryptor(self.env)
        for vals in vals_list:
            for field_name in ('phone', 'email', 'nid_number', 'bank_account'):
                if vals.get(field_name):
                    vals[f'{field_name}_encrypted'] = enc.encrypt(vals[field_name])
        return super().create(vals_list)

    def write(self, vals):
        enc = _get_encryptor(self.env)
        for field_name in ('phone', 'email', 'nid_number', 'bank_account'):
            if vals.get(field_name):
                vals[f'{field_name}_encrypted'] = enc.encrypt(vals[field_name])
        return super().write(vals)
```

**pgcrypto Usage Examples:**

```sql
-- Enable pgcrypto extension
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- Encrypt a value with a symmetric passphrase
INSERT INTO sales.customer (name, phone_encrypted)
VALUES (
    'Rahim Uddin',
    pgp_sym_encrypt('+8801712345678', current_setting('app.encryption_passphrase'))
);

-- Decrypt a value
SELECT name,
       pgp_sym_decrypt(phone_encrypted::bytea, current_setting('app.encryption_passphrase')) AS phone
FROM sales.customer
WHERE id = 1;

-- Encrypt with AES-256
SELECT pgp_sym_encrypt(
    'BD12345678901234',
    current_setting('app.encryption_passphrase'),
    'cipher-algo=aes256, compress-algo=0'
);
```

#### Dev 2 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Set up encryption key management with Docker secrets and HashiCorp Vault dev mode | 3.0 |
| 2 | Write key rotation script and cron job (90-day rotation cycle) | 2.0 |
| 3 | Create migration script to encrypt existing PII data in-place | 2.0 |
| 4 | Write automated tests for encrypt/decrypt round-trip integrity | 1.0 |

#### Dev 3 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Build `EncryptionStatusWidget` OWL component (encrypted field count, key age, next rotation) | 3.5 |
| 2 | Add masked display for PII fields in list/form views (show last 4 chars only) | 3.0 |
| 3 | Document encryption scheme and key rotation procedure | 1.5 |

**End-of-Day 72 Deliverables:**
- [ ] FernetColumnEncryption class passing all unit tests
- [ ] Odoo ORM mixin encrypting PII on create/write
- [ ] pgcrypto extension enabled with helper functions
- [ ] Key management infrastructure deployed
- [ ] PII fields masked in frontend views

---

### Day 73 — Database Connection Security

**Objective:** Enforce TLS on all PostgreSQL connections, implement mutual certificate authentication, and harden pg_hba.conf.

#### Dev 1 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Write hardened pg_hba.conf configuration | 2.0 |
| 2 | Configure PostgreSQL SSL parameters in postgresql.conf | 2.0 |
| 3 | Update Odoo database connection strings to use SSL mode `verify-full` | 2.0 |
| 4 | Configure PgBouncer with TLS for connection pooling | 2.0 |

**pg_hba.conf Hardened Configuration:**

```conf
# =============================================================
# pg_hba.conf — Smart Dairy Hardened Configuration
# TYPE  DATABASE        USER            ADDRESS          METHOD
# =============================================================

# Reject all plaintext connections
# Local socket connections (Unix domain)
local   all             postgres                         peer
local   all             smart_dairy_admin                peer
local   all             all                              reject

# SSL-required connections — application
hostssl smart_dairy     odoo_app        10.0.1.0/24      scram-sha-256
hostssl smart_dairy     odoo_app        172.18.0.0/16    scram-sha-256

# SSL with client certificate — replication
hostssl replication     repl_user       10.0.1.0/24      cert clientcert=verify-full
hostssl replication     repl_user       172.18.0.0/16    cert clientcert=verify-full

# SSL-required connections — monitoring
hostssl smart_dairy     monitoring_user 10.0.1.0/24      scram-sha-256

# SSL-required connections — backup
hostssl smart_dairy     backup_user     10.0.1.50/32     scram-sha-256

# Reject everything else
host    all             all             0.0.0.0/0        reject
hostssl all             all             0.0.0.0/0        reject
```

**SSL Certificate Generation Script:**

```bash
#!/usr/bin/env bash
# generate_pg_certs.sh — Generate PostgreSQL server and client certificates
set -euo pipefail

CERT_DIR="/etc/smart_dairy/certs/postgresql"
CA_SUBJECT="/CN=SmartDairy-CA/O=Smart Dairy Ltd/C=BD"
SERVER_SUBJECT="/CN=pg-primary.smartdairy.local/O=Smart Dairy Ltd/C=BD"
CLIENT_SUBJECT="/CN=repl_user/O=Smart Dairy Ltd/C=BD"
DAYS_VALID=365

mkdir -p "${CERT_DIR}"/{ca,server,client}

echo "[1/5] Generating CA private key and certificate..."
openssl genrsa -out "${CERT_DIR}/ca/ca.key" 4096
openssl req -new -x509 -days ${DAYS_VALID} -key "${CERT_DIR}/ca/ca.key" \
    -out "${CERT_DIR}/ca/ca.crt" -subj "${CA_SUBJECT}"

echo "[2/5] Generating server private key and CSR..."
openssl genrsa -out "${CERT_DIR}/server/server.key" 2048
chmod 600 "${CERT_DIR}/server/server.key"
openssl req -new -key "${CERT_DIR}/server/server.key" \
    -out "${CERT_DIR}/server/server.csr" -subj "${SERVER_SUBJECT}"

echo "[3/5] Signing server certificate with CA..."
openssl x509 -req -days ${DAYS_VALID} \
    -in "${CERT_DIR}/server/server.csr" \
    -CA "${CERT_DIR}/ca/ca.crt" -CAkey "${CERT_DIR}/ca/ca.key" \
    -CAcreateserial -out "${CERT_DIR}/server/server.crt"

echo "[4/5] Generating client certificate for replication user..."
openssl genrsa -out "${CERT_DIR}/client/repl_user.key" 2048
chmod 600 "${CERT_DIR}/client/repl_user.key"
openssl req -new -key "${CERT_DIR}/client/repl_user.key" \
    -out "${CERT_DIR}/client/repl_user.csr" -subj "${CLIENT_SUBJECT}"
openssl x509 -req -days ${DAYS_VALID} \
    -in "${CERT_DIR}/client/repl_user.csr" \
    -CA "${CERT_DIR}/ca/ca.crt" -CAkey "${CERT_DIR}/ca/ca.key" \
    -CAcreateserial -out "${CERT_DIR}/client/repl_user.crt"

echo "[5/5] Setting permissions..."
chown postgres:postgres "${CERT_DIR}"/server/*
chmod 600 "${CERT_DIR}"/server/server.key
chmod 644 "${CERT_DIR}"/server/server.crt
chmod 644 "${CERT_DIR}"/ca/ca.crt

echo "Certificate generation complete."
echo "  CA cert:     ${CERT_DIR}/ca/ca.crt"
echo "  Server cert: ${CERT_DIR}/server/server.crt"
echo "  Server key:  ${CERT_DIR}/server/server.key"
echo "  Client cert: ${CERT_DIR}/client/repl_user.crt"
echo "  Client key:  ${CERT_DIR}/client/repl_user.key"
```

#### Dev 2 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Execute certificate generation script, distribute certs to Docker volumes | 2.0 |
| 2 | Configure PgBouncer with TLS termination (`client_tls_sslmode = require`) | 2.0 |
| 3 | Update Docker Compose to mount SSL certs and enforce `sslmode=verify-full` | 2.0 |
| 4 | Write connection security tests (verify plaintext rejected, verify cert auth works) | 2.0 |

#### Dev 3 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Build `ConnectionSecurityWidget` showing TLS status, certificate expiry dates | 3.0 |
| 2 | Add certificate expiry countdown alert to dashboard | 2.5 |
| 3 | Document connection security architecture with network diagram | 2.5 |

**End-of-Day 73 Deliverables:**
- [ ] SSL certificates generated and deployed
- [ ] pg_hba.conf rejecting all plaintext connections
- [ ] PgBouncer configured with TLS
- [ ] Connection security tests passing
- [ ] Certificate expiry monitoring in dashboard

---

### Day 74 — High Availability Setup

**Objective:** Deploy PostgreSQL streaming replication with Patroni for automated failover, fronted by HAProxy for transparent read/write splitting.

#### Dev 1 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Write Patroni configuration YAML for primary and standby | 3.0 |
| 2 | Configure synchronous replication parameters | 2.0 |
| 3 | Write replication monitoring queries and health-check scripts | 2.0 |
| 4 | Test manual switchover and verify zero data loss | 1.0 |

**Patroni Configuration (patroni.yml):**

```yaml
# patroni.yml — Smart Dairy PostgreSQL HA Configuration
scope: smart-dairy-cluster
name: pg-primary

restapi:
  listen: 0.0.0.0:8008
  connect_address: pg-primary:8008
  authentication:
    username: patroni_admin
    password: "${PATRONI_REST_PASSWORD}"

etcd3:
  hosts:
    - etcd1:2379
    - etcd2:2379
    - etcd3:2379

bootstrap:
  dcs:
    ttl: 30
    loop_wait: 10
    retry_timeout: 10
    maximum_lag_on_failover: 1048576  # 1 MB
    synchronous_mode: true
    synchronous_mode_strict: false
    postgresql:
      use_pg_rewind: true
      use_slots: true
      parameters:
        max_connections: 200
        shared_buffers: 2GB
        effective_cache_size: 6GB
        work_mem: 16MB
        maintenance_work_mem: 512MB
        wal_level: replica
        max_wal_senders: 5
        max_replication_slots: 5
        hot_standby: "on"
        wal_keep_size: 1GB
        synchronous_commit: "on"
        synchronous_standby_names: "smart-dairy-standby"
        ssl: "on"
        ssl_cert_file: /certs/server/server.crt
        ssl_key_file: /certs/server/server.key
        ssl_ca_file: /certs/ca/ca.crt
        shared_preload_libraries: "pg_stat_statements,pgaudit,timescaledb"

  initdb:
    - encoding: UTF8
    - data-checksums

  pg_hba:
    - hostssl replication repl_user 0.0.0.0/0 cert clientcert=verify-full
    - hostssl all odoo_app 0.0.0.0/0 scram-sha-256
    - host all all 0.0.0.0/0 reject

  users:
    admin:
      password: "${PG_ADMIN_PASSWORD}"
      options:
        - createrole
        - createdb
    repl_user:
      password: "${PG_REPL_PASSWORD}"
      options:
        - replication

postgresql:
  listen: 0.0.0.0:5432
  connect_address: pg-primary:5432
  data_dir: /var/lib/postgresql/data
  pgpass: /tmp/pgpass
  authentication:
    replication:
      username: repl_user
      password: "${PG_REPL_PASSWORD}"
      sslmode: verify-full
      sslcert: /certs/client/repl_user.crt
      sslkey: /certs/client/repl_user.key
      sslrootcert: /certs/ca/ca.crt
    superuser:
      username: postgres
      password: "${PG_SUPERUSER_PASSWORD}"

tags:
  nofailover: false
  noloadbalance: false
  clonefrom: false
  nosync: false
```

**Docker Compose for HA Cluster:**

```yaml
# docker-compose.ha.yml
version: "3.9"

x-pg-common: &pg-common
  image: timescale/timescaledb-ha:pg16-ts2.14
  environment:
    PATRONI_REST_PASSWORD: ${PATRONI_REST_PASSWORD}
    PG_ADMIN_PASSWORD: ${PG_ADMIN_PASSWORD}
    PG_REPL_PASSWORD: ${PG_REPL_PASSWORD}
    PG_SUPERUSER_PASSWORD: ${PG_SUPERUSER_PASSWORD}
  networks:
    - dairy-db-net
  volumes:
    - ./certs:/certs:ro

services:
  etcd1:
    image: quay.io/coreos/etcd:v3.5.12
    command: >
      etcd --name etcd1
           --initial-advertise-peer-urls http://etcd1:2380
           --listen-peer-urls http://0.0.0.0:2380
           --listen-client-urls http://0.0.0.0:2379
           --advertise-client-urls http://etcd1:2379
           --initial-cluster etcd1=http://etcd1:2380
           --initial-cluster-state new
    networks:
      - dairy-db-net

  pg-primary:
    <<: *pg-common
    hostname: pg-primary
    volumes:
      - pg_primary_data:/var/lib/postgresql/data
      - ./certs:/certs:ro
      - ./patroni/primary.yml:/etc/patroni.yml:ro
    command: patroni /etc/patroni.yml
    ports:
      - "5432:5432"
      - "8008:8008"

  pg-standby:
    <<: *pg-common
    hostname: pg-standby
    volumes:
      - pg_standby_data:/var/lib/postgresql/data
      - ./certs:/certs:ro
      - ./patroni/standby.yml:/etc/patroni.yml:ro
    command: patroni /etc/patroni.yml
    ports:
      - "5433:5432"
      - "8009:8008"

  haproxy:
    image: haproxy:2.9-alpine
    ports:
      - "5000:5000"   # Read-write (primary)
      - "5001:5001"   # Read-only (standby)
      - "7000:7000"   # Stats UI
    volumes:
      - ./haproxy/haproxy.cfg:/usr/local/etc/haproxy/haproxy.cfg:ro
    networks:
      - dairy-db-net
    depends_on:
      - pg-primary
      - pg-standby

volumes:
  pg_primary_data:
  pg_standby_data:

networks:
  dairy-db-net:
    driver: bridge
```

**HAProxy Configuration:**

```cfg
# haproxy.cfg — PostgreSQL read/write splitting
global
    maxconn 1000
    log stdout format raw local0

defaults
    mode tcp
    log global
    timeout connect 5s
    timeout client  30s
    timeout server  30s
    retries 3

listen stats
    bind *:7000
    mode http
    stats enable
    stats uri /
    stats refresh 10s

# Read-write: routes to primary only
listen pg_primary
    bind *:5000
    option httpchk GET /primary
    http-check expect status 200
    default-server inter 3s fall 3 rise 2 on-marked-down shutdown-sessions

    server pg-primary pg-primary:5432 maxconn 150 check port 8008
    server pg-standby pg-standby:5432 maxconn 150 check port 8009

# Read-only: routes to standby (falls back to primary)
listen pg_standby
    bind *:5001
    option httpchk GET /replica
    http-check expect status 200
    default-server inter 3s fall 3 rise 2 on-marked-down shutdown-sessions

    server pg-standby pg-standby:5432 maxconn 150 check port 8009
    server pg-primary pg-primary:5432 maxconn 150 check port 8008 backup
```

#### Dev 2 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Deploy Docker Compose HA stack and verify cluster formation | 3.0 |
| 2 | Configure HAProxy and test read/write routing | 2.0 |
| 3 | Set up Patroni REST API monitoring endpoints | 1.5 |
| 4 | Write automated health-check scripts (cron-based) | 1.5 |

#### Dev 3 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Build `HaStatusWidget` OWL component: primary/standby indicators, replication lag gauge | 4.0 |
| 2 | Integrate HAProxy stats page link into admin panel | 2.0 |
| 3 | Document HA architecture with failover sequence diagram | 2.0 |

**End-of-Day 74 Deliverables:**
- [ ] Patroni cluster running with primary + standby
- [ ] HAProxy routing reads to standby, writes to primary
- [ ] Synchronous replication confirmed (zero data loss)
- [ ] Manual switchover tested successfully
- [ ] HA status visible on dashboard

---

### Day 75 — TimescaleDB Setup for IoT Time-Series Data

**Objective:** Configure TimescaleDB hypertables for IoT sensor data, create continuous aggregates, and set retention and compression policies.

#### Dev 1 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Create TimescaleDB extension and iot schema hypertables | 2.5 |
| 2 | Define continuous aggregates for hourly and daily rollups | 2.5 |
| 3 | Configure retention policies (2 years raw, 5 years aggregated) | 1.5 |
| 4 | Enable compression on chunks older than 7 days | 1.5 |

**TimescaleDB Setup SQL:**

```sql
-- =============================================================
-- TimescaleDB Configuration — Smart Dairy IoT Data Layer
-- =============================================================

-- Enable TimescaleDB
CREATE EXTENSION IF NOT EXISTS timescaledb CASCADE;

-- Create IoT schema if not exists
CREATE SCHEMA IF NOT EXISTS iot;

-- ---- Primary hypertable: sensor_reading ----
CREATE TABLE IF NOT EXISTS iot.sensor_reading (
    time            TIMESTAMPTZ     NOT NULL,
    device_id       VARCHAR(50)     NOT NULL,
    animal_id       INTEGER,
    farm_id         INTEGER         NOT NULL,
    sensor_type     VARCHAR(30)     NOT NULL,  -- 'temperature', 'humidity', 'milk_flow', 'weight', 'activity'
    value           DOUBLE PRECISION NOT NULL,
    unit            VARCHAR(10)     NOT NULL,
    quality_score   SMALLINT        DEFAULT 100,  -- 0-100 data quality indicator
    metadata        JSONB,
    created_at      TIMESTAMPTZ     DEFAULT NOW()
);

-- Convert to hypertable, partition by 1-day chunks
SELECT create_hypertable(
    'iot.sensor_reading',
    'time',
    chunk_time_interval => INTERVAL '1 day',
    if_not_exists => TRUE
);

-- Add space partitioning by farm_id for multi-farm scenarios
SELECT add_dimension(
    'iot.sensor_reading',
    'farm_id',
    number_partitions => 4
);

-- Create indexes for common query patterns
CREATE INDEX idx_sensor_device_time ON iot.sensor_reading (device_id, time DESC);
CREATE INDEX idx_sensor_animal_type ON iot.sensor_reading (animal_id, sensor_type, time DESC)
    WHERE animal_id IS NOT NULL;
CREATE INDEX idx_sensor_farm_type ON iot.sensor_reading (farm_id, sensor_type, time DESC);

-- ---- Continuous Aggregate: hourly rollups ----
CREATE MATERIALIZED VIEW iot.sensor_hourly
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 hour', time)         AS bucket,
    device_id,
    animal_id,
    farm_id,
    sensor_type,
    AVG(value)                          AS avg_value,
    MIN(value)                          AS min_value,
    MAX(value)                          AS max_value,
    COUNT(*)                            AS sample_count,
    PERCENTILE_CONT(0.5) WITHIN GROUP (ORDER BY value) AS median_value
FROM iot.sensor_reading
GROUP BY bucket, device_id, animal_id, farm_id, sensor_type
WITH NO DATA;

-- Refresh policy: refresh hourly data every 30 minutes
SELECT add_continuous_aggregate_policy('iot.sensor_hourly',
    start_offset    => INTERVAL '3 hours',
    end_offset      => INTERVAL '1 hour',
    schedule_interval => INTERVAL '30 minutes'
);

-- ---- Continuous Aggregate: daily rollups ----
CREATE MATERIALIZED VIEW iot.sensor_daily
WITH (timescaledb.continuous) AS
SELECT
    time_bucket('1 day', bucket)        AS bucket,
    device_id,
    animal_id,
    farm_id,
    sensor_type,
    AVG(avg_value)                      AS avg_value,
    MIN(min_value)                      AS min_value,
    MAX(max_value)                      AS max_value,
    SUM(sample_count)                   AS total_samples
FROM iot.sensor_hourly
GROUP BY time_bucket('1 day', bucket), device_id, animal_id, farm_id, sensor_type
WITH NO DATA;

-- Refresh daily aggregates every 6 hours
SELECT add_continuous_aggregate_policy('iot.sensor_daily',
    start_offset    => INTERVAL '3 days',
    end_offset      => INTERVAL '1 day',
    schedule_interval => INTERVAL '6 hours'
);

-- ---- Retention Policies ----
-- Raw data: retain 2 years
SELECT add_retention_policy('iot.sensor_reading', INTERVAL '2 years');

-- Hourly aggregates: retain 3 years
SELECT add_retention_policy('iot.sensor_hourly', INTERVAL '3 years');

-- Daily aggregates: retain 5 years
SELECT add_retention_policy('iot.sensor_daily', INTERVAL '5 years');

-- ---- Compression Policy ----
-- Enable compression on raw data
ALTER TABLE iot.sensor_reading SET (
    timescaledb.compress,
    timescaledb.compress_segmentby = 'device_id, farm_id, sensor_type',
    timescaledb.compress_orderby = 'time DESC'
);

-- Compress chunks older than 7 days
SELECT add_compression_policy('iot.sensor_reading', INTERVAL '7 days');

-- ---- Milk Production Hypertable ----
CREATE TABLE IF NOT EXISTS iot.milk_session (
    time            TIMESTAMPTZ     NOT NULL,
    animal_id       INTEGER         NOT NULL,
    farm_id         INTEGER         NOT NULL,
    session_type    VARCHAR(10)     NOT NULL,  -- 'morning', 'evening'
    volume_liters   DOUBLE PRECISION NOT NULL,
    fat_percentage  DOUBLE PRECISION,
    protein_pct     DOUBLE PRECISION,
    somatic_cells   INTEGER,
    duration_sec    INTEGER,
    milker_id       INTEGER,
    device_id       VARCHAR(50)
);

SELECT create_hypertable('iot.milk_session', 'time',
    chunk_time_interval => INTERVAL '7 days');

CREATE INDEX idx_milk_session_animal ON iot.milk_session (animal_id, time DESC);
```

#### Dev 2 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Verify TimescaleDB extension loaded in Patroni cluster | 1.5 |
| 2 | Load sample IoT data (100K rows) and benchmark query performance | 2.5 |
| 3 | Verify compression ratios on 7-day-old sample data | 2.0 |
| 4 | Configure Grafana dashboard for TimescaleDB chunk and compression stats | 2.0 |

#### Dev 3 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Build `TimeSeriesWidget` OWL component for real-time sensor graphs | 4.0 |
| 2 | Create milk production daily chart using continuous aggregate data | 2.5 |
| 3 | Document TimescaleDB data model and query patterns | 1.5 |

**End-of-Day 75 Deliverables:**
- [ ] TimescaleDB hypertables created for sensor_reading and milk_session
- [ ] Continuous aggregates (hourly, daily) refreshing automatically
- [ ] Retention policies: 2 years raw, 3 years hourly, 5 years daily
- [ ] Compression active on chunks older than 7 days (target > 10:1 ratio)
- [ ] Sample data loaded and query performance benchmarked

---

### Day 76 — Elasticsearch 8 Security

**Objective:** Secure the Elasticsearch 8 cluster with authentication, TLS, and field-level security to protect PII in search indices.

#### Dev 1 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Configure xpack.security in elasticsearch.yml | 2.0 |
| 2 | Define roles with index-level and field-level security | 3.0 |
| 3 | Create index templates that exclude PII from default search results | 2.0 |
| 4 | Test role-based search isolation | 1.0 |

**Elasticsearch Security Configuration:**

```yaml
# elasticsearch.yml — Smart Dairy Elasticsearch 8 Security
cluster.name: smart-dairy-search
node.name: es-node-1

# Network
network.host: 0.0.0.0
http.port: 9200
transport.port: 9300

# Security — xpack
xpack.security.enabled: true
xpack.security.enrollment.enabled: true

# TLS for HTTP layer
xpack.security.http.ssl:
  enabled: true
  keystore.path: certs/http.p12
  truststore.path: certs/http.p12

# TLS for transport (inter-node)
xpack.security.transport.ssl:
  enabled: true
  verification_mode: certificate
  keystore.path: certs/transport.p12
  truststore.path: certs/transport.p12

# Authentication realms
xpack.security.authc:
  realms:
    native:
      native1:
        order: 0
    file:
      file1:
        order: 1

# Audit logging
xpack.security.audit.enabled: true
xpack.security.audit.logfile.events.include:
  - authentication_failed
  - access_denied
  - connection_denied
  - tampered_request
```

**Role Mappings and Index Security:**

```json
// PUT _security/role/dairy_search_user
{
  "cluster": ["monitor"],
  "indices": [
    {
      "names": ["dairy-animals-*", "dairy-milk-*", "dairy-inventory-*"],
      "privileges": ["read", "view_index_metadata"],
      "field_security": {
        "grant": ["*"],
        "except": ["phone", "email", "nid_number", "bank_account", "phone_encrypted", "email_encrypted"]
      }
    }
  ]
}

// PUT _security/role/dairy_admin_search
{
  "cluster": ["monitor", "manage_index_templates"],
  "indices": [
    {
      "names": ["dairy-*"],
      "privileges": ["all"],
      "field_security": {
        "grant": ["*"]
      }
    }
  ]
}

// PUT _security/role/dairy_analyst
{
  "cluster": ["monitor"],
  "indices": [
    {
      "names": ["dairy-analytics-*", "dairy-milk-*"],
      "privileges": ["read"],
      "field_security": {
        "grant": ["*"],
        "except": ["phone", "email", "nid_number", "bank_account"]
      }
    }
  ]
}

// Index template with PII field handling
// PUT _index_template/dairy-animals
{
  "index_patterns": ["dairy-animals-*"],
  "template": {
    "settings": {
      "number_of_shards": 1,
      "number_of_replicas": 1,
      "index.queries.cache.enabled": true
    },
    "mappings": {
      "properties": {
        "animal_id":     { "type": "integer" },
        "tag_number":    { "type": "keyword" },
        "name":          { "type": "text", "analyzer": "standard" },
        "breed":         { "type": "keyword" },
        "status":        { "type": "keyword" },
        "farm_id":       { "type": "integer" },
        "date_of_birth": { "type": "date" },
        "owner_phone":   { "type": "keyword", "doc_values": false },
        "owner_email":   { "type": "keyword", "doc_values": false },
        "last_updated":  { "type": "date" }
      }
    }
  }
}
```

#### Dev 2 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Generate Elasticsearch TLS certificates (http.p12, transport.p12) | 2.0 |
| 2 | Deploy secured Elasticsearch in Docker with xpack.security | 2.5 |
| 3 | Create users and assign roles via Elasticsearch API | 1.5 |
| 4 | Write integration tests for field-level security (verify PII hidden for non-admin roles) | 2.0 |

#### Dev 3 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Update search UI to handle 403 responses for restricted fields | 3.0 |
| 2 | Build `SearchSecurityWidget` showing Elasticsearch cluster health and security status | 3.0 |
| 3 | Document Elasticsearch security architecture | 2.0 |

**End-of-Day 76 Deliverables:**
- [ ] Elasticsearch xpack.security enabled with TLS
- [ ] Role-based access with field-level security for PII
- [ ] Index templates created for all dairy indices
- [ ] PII excluded from non-admin search results
- [ ] Audit logging active on Elasticsearch

---

### Day 77 — Redis 7 Security

**Objective:** Secure Redis 7 with ACLs, TLS encryption, namespace isolation, and proper memory management.

#### Dev 1 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Write Redis ACL configuration with per-service users | 2.5 |
| 2 | Configure TLS on Redis with certificate-based encryption | 2.0 |
| 3 | Implement key namespace isolation per service | 2.0 |
| 4 | Set expiry policies and maxmemory configuration | 1.5 |

**Redis ACL Configuration:**

```conf
# redis-acl.conf — Smart Dairy Redis 7 ACL Configuration

# Default user disabled (security hardening)
user default off

# Admin user — full access, for maintenance only
user redis_admin on >${REDIS_ADMIN_PASSWORD} ~* &* +@all

# Odoo session user — session keys only
user odoo_session on >${REDIS_SESSION_PASSWORD} ~session:* &* +@read +@write +@keyspace +del +expire +ttl +exists +type -@dangerous -@admin -config -debug -shutdown -slaveof -replicaof

# Cache service user — cache namespace only
user cache_service on >${REDIS_CACHE_PASSWORD} ~cache:* &* +get +set +del +mget +mset +expire +ttl +exists +scan +type +keys -@dangerous -@admin -config -debug -shutdown

# Celery worker user — celery namespace
user celery_worker on >${REDIS_CELERY_PASSWORD} ~celery:* ~_kombu.* &* +@list +@set +@sortedset +@string +@hash +@generic +lpush +rpush +lpop +rpop +brpop +blpop +llen +sadd +smembers +zadd +zrangebyscore -@dangerous -@admin

# IoT data user — real-time sensor cache
user iot_cache on >${REDIS_IOT_PASSWORD} ~iot:* ~sensor:* &* +get +set +del +expire +ttl +hset +hget +hgetall +hdel +publish +subscribe -@dangerous -@admin

# Monitoring user — read-only info access
user monitoring on >${REDIS_MON_PASSWORD} ~* &* +info +ping +dbsize +client|getname +slowlog|get -@write -@admin -@dangerous
```

**Redis Configuration (redis.conf excerpts):**

```conf
# redis.conf — Smart Dairy Redis 7 Security Configuration

# Bind to internal network only
bind 10.0.1.0 127.0.0.1
protected-mode yes

# TLS Configuration
tls-port 6380
port 0  # Disable non-TLS port
tls-cert-file /certs/redis/redis.crt
tls-key-file /certs/redis/redis.key
tls-ca-cert-file /certs/ca/ca.crt
tls-auth-clients optional
tls-protocols "TLSv1.2 TLSv1.3"
tls-ciphersuites "TLS_AES_256_GCM_SHA384:TLS_CHACHA20_POLY1305_SHA256"

# ACL file
aclfile /etc/redis/redis-acl.conf

# Memory management
maxmemory 512mb
maxmemory-policy allkeys-lru

# Key expiry defaults (set via application logic)
# session:*  -> 24 hours (86400)
# cache:*    -> 1 hour (3600)
# iot:*      -> 5 minutes (300)
# celery:*   -> managed by Celery

# Persistence (AOF for durability)
appendonly yes
appendfsync everysec
auto-aof-rewrite-percentage 100
auto-aof-rewrite-min-size 64mb

# Logging
loglevel notice
logfile /var/log/redis/redis.log

# Disable dangerous commands (belt-and-suspenders with ACLs)
rename-command FLUSHDB ""
rename-command FLUSHALL ""
rename-command CONFIG "DAIRY_CONFIG_a8f3e2"
rename-command DEBUG ""
```

#### Dev 2 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Generate Redis TLS certificates and mount in Docker | 2.0 |
| 2 | Deploy Redis 7 with ACL and TLS configuration in Docker Compose | 2.0 |
| 3 | Update all application connection strings to use TLS and ACL users | 2.5 |
| 4 | Write ACL integration tests (verify user isolation, command restrictions) | 1.5 |

#### Dev 3 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Build `RedisSecurityWidget` OWL component (connected clients, memory usage, ACL status) | 3.5 |
| 2 | Add Redis key namespace usage chart to dashboard | 2.5 |
| 3 | Document Redis security architecture and ACL user mappings | 2.0 |

**End-of-Day 77 Deliverables:**
- [ ] Redis 7 running with TLS-only connections (port 0 disabled)
- [ ] ACL users created per service with least-privilege permissions
- [ ] Key namespace isolation enforced
- [ ] maxmemory and eviction policy configured
- [ ] All application services connecting via TLS with ACL users

---

### Day 78 — Database Audit Logging

**Objective:** Deploy pgAudit for comprehensive SQL audit logging with rotation, archival, and analysis capabilities.

#### Dev 1 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Install and configure pgAudit extension | 2.0 |
| 2 | Create audit log schema and analysis views | 2.5 |
| 3 | Write log analysis queries for security reporting | 2.0 |
| 4 | Configure log rotation and archival with logrotate | 1.5 |

**pgAudit Configuration:**

```ini
# postgresql.conf — pgAudit settings

# Load pgAudit
shared_preload_libraries = 'pg_stat_statements,pgaudit,timescaledb'

# pgAudit configuration
pgaudit.log = 'ddl, role, write'           # Log DDL changes, role changes, and writes
pgaudit.log_catalog = off                   # Don't log pg_catalog queries
pgaudit.log_client = on                     # Include in client messages
pgaudit.log_level = 'log'                   # Logging level
pgaudit.log_parameter = on                  # Log query parameters
pgaudit.log_relation = on                   # Log relation (table) name
pgaudit.log_statement_once = off            # Log full statement each time
pgaudit.role = 'dairy_auditor'              # Role-based object auditing

# Logging configuration for audit trail
log_destination = 'csvlog'
logging_collector = on
log_directory = '/var/log/postgresql'
log_filename = 'postgresql-%Y-%m-%d.log'
log_rotation_age = 1d
log_rotation_size = 100MB
log_min_duration_statement = 500            # Log slow queries > 500ms
log_line_prefix = '%t [%p]: user=%u,db=%d,app=%a,client=%h '
log_connections = on
log_disconnections = on
```

**Audit Log Schema and Analysis:**

```sql
-- =============================================================
-- Audit Infrastructure — Smart Dairy
-- =============================================================

CREATE SCHEMA IF NOT EXISTS audit;

-- Parsed audit log table (populated by log ingestion job)
CREATE TABLE audit.log_entries (
    id              BIGSERIAL       PRIMARY KEY,
    log_time        TIMESTAMPTZ     NOT NULL,
    user_name       VARCHAR(100)    NOT NULL,
    database_name   VARCHAR(100),
    client_addr     INET,
    application     VARCHAR(100),
    command_tag     VARCHAR(50),    -- 'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'CREATE TABLE', etc.
    object_type     VARCHAR(50),    -- 'TABLE', 'INDEX', 'FUNCTION', etc.
    object_name     VARCHAR(200),
    statement       TEXT,
    parameter_list  TEXT,
    audit_type      VARCHAR(20),    -- 'SESSION', 'OBJECT'
    class           VARCHAR(20),    -- 'READ', 'WRITE', 'DDL', 'ROLE', 'MISC'
    created_at      TIMESTAMPTZ     DEFAULT NOW()
);

-- Indexes for efficient querying
CREATE INDEX idx_audit_time ON audit.log_entries (log_time DESC);
CREATE INDEX idx_audit_user ON audit.log_entries (user_name, log_time DESC);
CREATE INDEX idx_audit_command ON audit.log_entries (command_tag, log_time DESC);
CREATE INDEX idx_audit_object ON audit.log_entries (object_name, log_time DESC);

-- Partition by month for manageability
-- (In production, use declarative partitioning)

-- ---- Analysis Views ----

-- View: DDL changes (schema modifications)
CREATE VIEW audit.ddl_changes AS
SELECT log_time, user_name, command_tag, object_type, object_name, statement
FROM audit.log_entries
WHERE class = 'DDL'
ORDER BY log_time DESC;

-- View: Sensitive table access
CREATE VIEW audit.sensitive_access AS
SELECT log_time, user_name, client_addr, command_tag, object_name, statement
FROM audit.log_entries
WHERE object_name IN (
    'farm.health_record',
    'sales.customer',
    'accounting.journal_entry',
    'core.employee'
)
ORDER BY log_time DESC;

-- View: Failed operations (for security monitoring)
CREATE VIEW audit.failed_operations AS
SELECT log_time, user_name, client_addr, application, statement
FROM audit.log_entries
WHERE statement LIKE '%ERROR%' OR statement LIKE '%DENIED%'
ORDER BY log_time DESC;

-- ---- Analysis Queries ----

-- Top 10 users by write operations in the last 24 hours
-- SELECT user_name, COUNT(*) as write_count
-- FROM audit.log_entries
-- WHERE class = 'WRITE' AND log_time > NOW() - INTERVAL '24 hours'
-- GROUP BY user_name
-- ORDER BY write_count DESC
-- LIMIT 10;

-- Schema changes in the last 7 days
-- SELECT log_time, user_name, command_tag, object_name, statement
-- FROM audit.ddl_changes
-- WHERE log_time > NOW() - INTERVAL '7 days';

-- Retention: keep 90 days of parsed logs, archive older to cold storage
CREATE OR REPLACE FUNCTION audit.cleanup_old_logs()
RETURNS void AS $$
BEGIN
    DELETE FROM audit.log_entries
    WHERE log_time < NOW() - INTERVAL '90 days';
END;
$$ LANGUAGE plpgsql;
```

#### Dev 2 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Deploy pgAudit in Patroni cluster (update shared_preload_libraries) | 2.0 |
| 2 | Set up logrotate for PostgreSQL CSV logs (7-day rotation, gzip compression) | 1.5 |
| 3 | Write log ingestion script: parse CSV logs into audit.log_entries table | 3.0 |
| 4 | Configure cron job for log ingestion and cleanup | 1.5 |

#### Dev 3 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Build `AuditLogViewer` OWL component with filtering, search, and pagination | 4.0 |
| 2 | Add audit event count chart (last 24h, 7d, 30d) to Security Dashboard | 2.5 |
| 3 | Document audit logging architecture and compliance mapping | 1.5 |

**End-of-Day 78 Deliverables:**
- [ ] pgAudit extension loaded and logging DDL/role/write operations
- [ ] Audit log schema created with analysis views
- [ ] Log rotation configured (7-day cycle)
- [ ] Log ingestion pipeline populating audit.log_entries
- [ ] Audit log viewer visible in Security Dashboard

---

### Day 79 — Disaster Recovery Drill

**Objective:** Simulate a primary database failure, test automatic failover via Patroni, verify data integrity, and measure RTO/RPO.

#### Dev 1 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Write DR runbook with step-by-step failover and rollback procedures | 2.5 |
| 2 | Execute DR drill: simulate primary failure, monitor Patroni failover | 2.5 |
| 3 | Run data integrity verification queries after failover | 2.0 |
| 4 | Document drill results (RTO, RPO, issues encountered) | 1.0 |

**Disaster Recovery Runbook:**

```markdown
## Smart Dairy — Disaster Recovery Runbook

### Pre-Drill Checklist
1. Verify Patroni cluster is healthy: `patronictl -c /etc/patroni.yml list`
2. Confirm synchronous replication is active: standby shows `sync` state
3. Record baseline metrics:
   - Current WAL position on primary
   - Replication lag on standby
   - Row counts on key tables (farm.animal, farm.milk_production)
4. Notify team of scheduled DR drill window

### Failover Procedure

**Step 1: Record pre-failure state**
```sql
-- On PRIMARY before failure
SELECT pg_current_wal_lsn() AS primary_wal_position;
SELECT COUNT(*) FROM farm.animal;
SELECT COUNT(*) FROM farm.milk_production;
SELECT MAX(id) FROM sales.sale_order;
```

**Step 2: Simulate primary failure**
```bash
# Stop the primary PostgreSQL container
docker stop pg-primary
# Record the exact timestamp
date -u +"%Y-%m-%dT%H:%M:%S.%3NZ"
```

**Step 3: Monitor automatic failover**
```bash
# Watch Patroni logs on standby
docker logs -f pg-standby 2>&1 | grep -i "promoted\|leader\|failover"
# Record promotion timestamp
# Expected: Patroni promotes standby within 30 seconds
```

**Step 4: Verify new primary**
```bash
# Check cluster state
docker exec pg-standby patronictl -c /etc/patroni.yml list
# Expected: pg-standby shows as Leader
```

**Step 5: Verify data integrity**
```sql
-- On NEW PRIMARY (formerly standby)
SELECT pg_current_wal_lsn() AS new_primary_wal_position;
SELECT COUNT(*) FROM farm.animal;        -- Must match pre-failure
SELECT COUNT(*) FROM farm.milk_production; -- Must match pre-failure
SELECT MAX(id) FROM sales.sale_order;     -- Must match pre-failure

-- Verify RLS policies still active
SET app.current_farm_id = '1';
SELECT COUNT(*) FROM farm.animal;  -- Should be filtered

-- Verify encryption functions work
SELECT auth.current_farm_id();
```

**Step 6: Verify application connectivity**
```bash
# HAProxy should have detected failover and be routing to new primary
curl -s http://haproxy:7000/ | grep -i "pg-standby.*UP"
# Test application write path
curl -X POST http://localhost:8069/api/health-check
```

**Step 7: Measure RTO and RPO**
- RTO = (promotion_timestamp - failure_timestamp)
- RPO = Compare WAL positions: (primary_wal_at_failure - standby_wal_at_promotion)
- Target: RTO < 30s, RPO = 0

### Rollback Procedure (Restore Original Primary)

**Step 1: Rejoin old primary as standby**
```bash
docker start pg-primary
# Patroni will automatically rejoin as replica using pg_rewind
docker logs -f pg-primary 2>&1 | grep -i "rewind\|replica\|running"
```

**Step 2: Switchover back (optional, controlled)**
```bash
docker exec pg-standby patronictl -c /etc/patroni.yml switchover \
    --master pg-standby --candidate pg-primary --force
```

**Step 3: Verify final cluster state**
```bash
docker exec pg-primary patronictl -c /etc/patroni.yml list
# Expected: pg-primary is Leader, pg-standby is Sync Standby
```

### Post-Drill Report Template
| Metric | Target | Actual | Pass/Fail |
|---|---|---|---|
| RTO (failover time) | < 30 sec | ___ sec | |
| RPO (data loss) | 0 bytes | ___ bytes | |
| Application recovery | Automatic | Yes/No | |
| Data integrity | 100% match | ___% | |
| RLS policies active | Yes | Yes/No | |
| Encryption working | Yes | Yes/No | |
```

#### Dev 2 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Prepare DR drill environment with synthetic write load (simulate 900 L/day data flow) | 2.0 |
| 2 | Execute and time failover alongside Dev 1 | 2.0 |
| 3 | Test HAProxy automatic rerouting to new primary | 1.5 |
| 4 | Restore old primary as standby using pg_rewind, execute controlled switchover | 2.5 |

#### Dev 3 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Monitor UI availability during failover (record error states, reconnect time) | 2.5 |
| 2 | Build `DrDrillReportWidget` displaying last drill results (RTO, RPO, pass/fail) | 3.0 |
| 3 | Document DR drill results with screenshots and timestamps | 2.5 |

**End-of-Day 79 Deliverables:**
- [ ] DR drill executed with documented results
- [ ] RTO measured < 30 seconds
- [ ] RPO confirmed at 0 data loss (synchronous replication)
- [ ] Old primary successfully rejoined as standby
- [ ] DR report widget showing results on dashboard

---

### Day 80 — Milestone 8 Review & Sign-Off

**Objective:** Review all Milestone 8 deliverables, demonstrate database security and HA capabilities, and formally sign off.

#### Dev 1 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Run complete security test suite (RLS, encryption, audit, connection) | 2.5 |
| 2 | Prepare live demonstration of security features | 2.0 |
| 3 | Write Milestone 8 technical summary report | 2.0 |
| 4 | Address any outstanding security review items | 1.5 |

#### Dev 2 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Run infrastructure validation checks across all components | 2.0 |
| 2 | Demonstrate HA failover and recovery to stakeholders | 2.0 |
| 3 | Verify all Docker Compose configurations are production-ready | 2.0 |
| 4 | Prepare handoff documentation for Milestone 9 | 2.0 |

#### Dev 3 Tasks (~8 h)

| # | Task | Hours |
|---|---|---|
| 1 | Complete Security Dashboard integration test | 2.0 |
| 2 | Demonstrate dashboard to stakeholders | 1.5 |
| 3 | Final documentation polish and cross-referencing | 2.5 |
| 4 | Create Milestone 9 dependency checklist | 2.0 |

**OWL DatabaseSecurityDashboard Component:**

```javascript
/** @odoo-module **/
// addons/smart_dairy_security/static/src/components/security_dashboard.js

import { Component, useState, onWillStart, onMounted } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";
import { _t } from "@web/core/l10n/translation";

export class DatabaseSecurityDashboard extends Component {
    static template = "smart_dairy_security.SecurityDashboard";
    static props = {};

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            rlsStatus: { enabled: 0, total: 0, policies: [] },
            encryptionStatus: {
                encrypted_fields: 0,
                key_age_days: 0,
                next_rotation: "",
                algorithm: "AES-256-GCM",
            },
            haStatus: {
                primary: { host: "", status: "unknown", wal_position: "" },
                standby: { host: "", status: "unknown", lag_bytes: 0, lag_ms: 0 },
                last_failover: null,
            },
            auditStatus: {
                total_events_24h: 0,
                ddl_changes_7d: 0,
                failed_ops_24h: 0,
                log_size_mb: 0,
            },
            connectionSecurity: {
                tls_connections: 0,
                plaintext_connections: 0,
                cert_expiry_days: 0,
            },
            drDrill: {
                last_drill_date: "",
                rto_seconds: 0,
                rpo_bytes: 0,
                result: "unknown",
            },
            loading: true,
        });

        onWillStart(async () => {
            await this._loadAllData();
        });

        onMounted(() => {
            this._startAutoRefresh();
        });
    }

    async _loadAllData() {
        try {
            const [rls, encryption, ha, audit, connections, dr] = await Promise.all([
                this.rpc("/api/security/rls-status"),
                this.rpc("/api/security/encryption-status"),
                this.rpc("/api/security/ha-status"),
                this.rpc("/api/security/audit-status"),
                this.rpc("/api/security/connection-status"),
                this.rpc("/api/security/dr-drill-status"),
            ]);

            Object.assign(this.state.rlsStatus, rls);
            Object.assign(this.state.encryptionStatus, encryption);
            Object.assign(this.state.haStatus, ha);
            Object.assign(this.state.auditStatus, audit);
            Object.assign(this.state.connectionSecurity, connections);
            Object.assign(this.state.drDrill, dr);
        } catch (error) {
            this.notification.add(_t("Failed to load security dashboard data"), {
                type: "danger",
            });
            console.error("Security dashboard load error:", error);
        } finally {
            this.state.loading = false;
        }
    }

    _startAutoRefresh() {
        this._refreshInterval = setInterval(() => this._loadAllData(), 30000);
    }

    get rlsCoveragePercent() {
        const { enabled, total } = this.state.rlsStatus;
        return total > 0 ? Math.round((enabled / total) * 100) : 0;
    }

    get replicationLagClass() {
        const lag = this.state.haStatus.standby.lag_ms;
        if (lag < 50) return "text-success";
        if (lag < 200) return "text-warning";
        return "text-danger";
    }

    get drDrillStatusClass() {
        return this.state.drDrill.result === "pass" ? "badge-success" : "badge-danger";
    }

    get encryptionKeyHealthy() {
        return this.state.encryptionStatus.key_age_days < 90;
    }

    destroy() {
        if (this._refreshInterval) {
            clearInterval(this._refreshInterval);
        }
        super.destroy();
    }
}

registry.category("actions").add("smart_dairy_security.dashboard", {
    Component: DatabaseSecurityDashboard,
});
```

**Monitoring Alerts Configuration:**

```yaml
# alerts/database_security_alerts.yml
# Prometheus alerting rules for Smart Dairy Database Security

groups:
  - name: database_security
    interval: 30s
    rules:
      # Replication lag alert
      - alert: ReplicationLagHigh
        expr: pg_replication_lag_seconds > 1
        for: 2m
        labels:
          severity: warning
          component: postgresql
        annotations:
          summary: "PostgreSQL replication lag exceeds 1 second"
          description: "Standby {{ $labels.instance }} lag: {{ $value }}s"

      - alert: ReplicationLagCritical
        expr: pg_replication_lag_seconds > 10
        for: 1m
        labels:
          severity: critical
          component: postgresql
        annotations:
          summary: "PostgreSQL replication lag critical (>10s)"
          runbook: "Check standby connectivity and WAL shipping"

      # Authentication failure alerts
      - alert: PostgresAuthFailures
        expr: rate(pg_stat_activity_auth_failed_total[5m]) > 5
        for: 1m
        labels:
          severity: warning
          component: postgresql
        annotations:
          summary: "High rate of PostgreSQL authentication failures"

      - alert: ElasticsearchAuthFailures
        expr: rate(elasticsearch_audit_authentication_failed_total[5m]) > 3
        for: 2m
        labels:
          severity: warning
          component: elasticsearch
        annotations:
          summary: "Elasticsearch authentication failures detected"

      # Encryption key expiry
      - alert: EncryptionKeyExpiringSoon
        expr: smart_dairy_encryption_key_age_days > 75
        for: 1h
        labels:
          severity: warning
          component: encryption
        annotations:
          summary: "Encryption key approaching 90-day rotation deadline"
          description: "Key age: {{ $value }} days. Rotate before 90 days."

      - alert: EncryptionKeyExpired
        expr: smart_dairy_encryption_key_age_days > 90
        for: 5m
        labels:
          severity: critical
          component: encryption
        annotations:
          summary: "Encryption key has exceeded 90-day rotation policy"

      # SSL certificate expiry
      - alert: SSLCertExpiringSoon
        expr: (ssl_certificate_expiry_timestamp - time()) / 86400 < 30
        for: 1h
        labels:
          severity: warning
          component: tls
        annotations:
          summary: "SSL certificate expiring in less than 30 days"

      # Redis security
      - alert: RedisUnauthorizedAccess
        expr: rate(redis_rejected_connections_total[5m]) > 2
        for: 2m
        labels:
          severity: warning
          component: redis
        annotations:
          summary: "Redis rejecting unauthorized connections"

      # Patroni cluster health
      - alert: PatroniClusterDegraded
        expr: patroni_cluster_members < 2
        for: 1m
        labels:
          severity: critical
          component: postgresql
        annotations:
          summary: "Patroni cluster has fewer than 2 members"

      # Audit log gap
      - alert: AuditLogGap
        expr: time() - smart_dairy_last_audit_log_entry_timestamp > 300
        for: 5m
        labels:
          severity: warning
          component: audit
        annotations:
          summary: "No audit log entries in the last 5 minutes"
```

**End-of-Day 80 Deliverables:**
- [ ] All security tests passing (RLS, encryption, connections, audit)
- [ ] HA failover demonstrated to stakeholders
- [ ] Security Dashboard fully operational with all widgets
- [ ] Monitoring alerts configured and tested
- [ ] Milestone 8 technical summary signed off
- [ ] Handoff documentation prepared for Milestone 9

---

## 4. Technical Specifications

### 4.1 Security Architecture Summary

| Layer | Technology | Protection |
|---|---|---|
| Row-Level | PostgreSQL RLS | Farm-based data isolation |
| Column-Level | Fernet AES-256 + pgcrypto | PII encryption at rest |
| Connection | TLS 1.3 + mutual cert auth | Data in transit |
| High Availability | Patroni + streaming replication | Zero-downtime failover |
| Time-Series | TimescaleDB | Efficient IoT data management |
| Search | Elasticsearch xpack | Field-level security for PII |
| Cache | Redis 7 ACLs + TLS | Per-service command isolation |
| Audit | pgAudit | Complete DDL/DML audit trail |

### 4.2 Encryption Standards

| Field Category | Algorithm | Key Length | Rotation |
|---|---|---|---|
| Phone, Email | Fernet (AES-128-CBC + HMAC-SHA256) | 256-bit | 90 days |
| NID, Bank Account | AES-256-GCM | 256-bit | 90 days |
| Database connections | TLS 1.3 | 256-bit | Annual cert renewal |
| Replication | TLS + client certificates | 2048-bit RSA | Annual |
| Redis | TLS 1.2/1.3 | 256-bit | Annual |
| Elasticsearch | TLS (http + transport) | 256-bit | Annual |

### 4.3 Database Role Hierarchy

```
dairy_admin (superuser-like, RLS bypass)
  +-- dairy_manager (read/write all farm tables in assigned farm)
  |     +-- dairy_worker (read/write milk_production, feed_schedule)
  |     +-- dairy_veterinarian (read/write health_record, breeding_record)
  +-- dairy_accountant (read accounting, read sales)
  +-- dairy_analyst (read-only across farms for reporting)
  +-- dairy_auditor (pgAudit object-level audit role)
```

### 4.4 HA Topology

```
                    +-----------+
                    |  HAProxy  |
                    | :5000 RW  |
                    | :5001 RO  |
                    +-----+-----+
                          |
              +-----------+-----------+
              |                       |
      +-------+-------+     +--------+------+
      |  pg-primary    |     |  pg-standby   |
      |  (Patroni)     |<--->|  (Patroni)    |
      |  :5432         |sync |  :5432        |
      +-------+--------+repl +-------+-------+
              |                       |
              +----------+------------+
                         |
                   +-----+-----+
                   |   etcd    |
                   | consensus |
                   +-----------+
```

### 4.5 TimescaleDB Data Flow

```
IoT Sensors --> FastAPI --> iot.sensor_reading (hypertable)
                               |
                      +--------+--------+
                      |                 |
              iot.sensor_hourly   iot.sensor_daily
              (continuous agg)    (continuous agg)
                      |                 |
                 Retain 3 yr       Retain 5 yr
                      |
               Raw: Retain 2 yr
               Compress after 7 days
```

---

## 5. Testing & Validation

### 5.1 Test Matrix

| Test Category | Test Cases | Owner | Day |
|---|---|---|---|
| RLS Isolation | Cross-farm data leakage (5 tests) | Dev 1 | 71 |
| RLS Bypass | Admin bypass, analyst read-only (3 tests) | Dev 1 | 71 |
| Encryption Round-Trip | Encrypt/decrypt all PII field types (4 tests) | Dev 1 | 72 |
| Key Rotation | Rotate key, decrypt old data with new key set (2 tests) | Dev 2 | 72 |
| Connection Reject | Plaintext connection rejected (2 tests) | Dev 2 | 73 |
| Certificate Auth | Valid/invalid client cert (3 tests) | Dev 2 | 73 |
| Failover | Automatic promotion < 30s (1 test) | Dev 1 + Dev 2 | 74 |
| Data Integrity | Row counts match after failover (1 test) | Dev 1 | 79 |
| TimescaleDB Compression | Verify > 10:1 ratio (1 test) | Dev 2 | 75 |
| Continuous Aggregates | Hourly/daily values match manual calculation (2 tests) | Dev 1 | 75 |
| ES Field Security | PII hidden for non-admin roles (3 tests) | Dev 2 | 76 |
| Redis ACL | Command restriction per user (5 tests) | Dev 2 | 77 |
| Redis TLS | Plaintext connection rejected (1 test) | Dev 2 | 77 |
| pgAudit Capture | DDL and DML logged correctly (3 tests) | Dev 1 | 78 |
| DR Drill | Full failover and recovery (1 test) | All | 79 |
| Dashboard | All widgets render with correct data (6 tests) | Dev 3 | 80 |

**Total: 43 test cases**

### 5.2 Acceptance Criteria

| Criterion | Verification Method | Pass Condition |
|---|---|---|
| RLS prevents cross-farm reads | Query as dairy_worker for farm_id=2 while set to farm_id=1 | 0 rows returned |
| PII encrypted in database | Direct SQL SELECT on encrypted column | Ciphertext only (not plaintext) |
| Plaintext connections fail | `psql` without sslmode | Connection refused |
| Failover completes automatically | Docker stop primary | Standby promoted < 30s |
| Zero data loss on failover | Compare row counts and max IDs | 100% match |
| Compression > 10:1 | `SELECT * FROM hypertable_compression_stats()` | Ratio > 10 |
| Elasticsearch PII hidden | Search as `dairy_search_user` | Phone/email fields absent |
| Redis ACL enforced | `odoo_session` user tries `CONFIG` | NOPERM error |
| Audit logs capture DDL | Run `CREATE TABLE`, check audit | Statement logged |

---

## 6. Risk & Mitigation

| # | Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|---|
| R1 | RLS policies cause query performance degradation | Medium | High | Benchmark before/after; add indexes on farm_id; use `EXPLAIN ANALYZE` to verify policy evaluation cost |
| R2 | Encryption key loss results in permanent data loss | Low | Critical | Store keys in HashiCorp Vault with backup; maintain encrypted key backup offsite; test key recovery quarterly |
| R3 | Patroni failover takes longer than 30s under load | Medium | High | Tune `ttl` and `loop_wait` in Patroni config; ensure etcd latency < 10ms; test under simulated production load |
| R4 | TimescaleDB compression blocks queries | Low | Medium | Schedule compression during low-traffic hours (2–5 AM BDT); monitor chunk compression duration |
| R5 | Certificate expiry causes outages | Medium | High | Monitoring alert at 30 days before expiry; automated renewal script; calendar reminders for manual certs |
| R6 | pgAudit log volume overwhelms disk | Medium | Medium | Configure log rotation; exclude high-frequency read operations; monitor disk usage with alerts |
| R7 | Redis maxmemory eviction drops active sessions | Low | High | Size maxmemory to 2x expected usage; monitor eviction rate; set critical keys with `PERSIST` |
| R8 | Elasticsearch security config prevents Odoo indexing | Medium | Medium | Test indexing pipeline with security enabled before deploying; create dedicated indexing user with write privileges |

---

## 7. Dependencies & Handoffs

### 7.1 Incoming Dependencies (from Earlier Milestones)

| Dependency | Source Milestone | Required By Day | Status |
|---|---|---|---|
| Database schemas (core, farm, sales, inventory, accounting, analytics, audit, iot) | Milestone 4 (DB Optimization) | Day 71 | Delivered |
| Security architecture design and role definitions | Milestone 6 (Security Architecture) | Day 71 | Delivered |
| Docker Compose base configuration | Milestone 5 (Infrastructure) | Day 74 | Delivered |
| Odoo 19 CE base installation and module structure | Milestone 3 (Odoo Setup) | Day 72 | Delivered |
| Frontend dashboard layout and OWL component framework | Milestone 7 (Frontend Foundation) | Day 71 | Delivered |

### 7.2 Outgoing Handoffs (to Later Milestones)

| Deliverable | Target Milestone | Required By | Description |
|---|---|---|---|
| Secured PostgreSQL cluster (RLS + encryption + TLS) | Milestone 9 (API Development) | Day 81 | APIs must connect through secured PgBouncer with TLS |
| TimescaleDB hypertables and continuous aggregates | Milestone 9 (API Development) | Day 81 | API endpoints for IoT data query the hypertables |
| Elasticsearch secured cluster | Milestone 9 (API Development) | Day 81 | Search API uses authenticated Elasticsearch client |
| Redis secured instance | Milestone 9 (API Development) | Day 81 | Session management and caching use ACL-authenticated Redis |
| Database Security Dashboard | Milestone 10 (Integration & Testing) | Day 90 | Dashboard included in final integration testing |
| DR runbook and test results | Milestone 10 (Integration & Testing) | Day 90 | DR capability validated as part of go-live readiness |
| pgAudit infrastructure | Milestone 10 (Integration & Testing) | Day 90 | Audit trail available for compliance review |

### 7.3 External Dependencies

| Dependency | Provider | Risk Level | Contingency |
|---|---|---|---|
| Docker Hub image availability (TimescaleDB HA, etcd, HAProxy) | Docker Hub | Low | Pre-pull and cache images in private registry |
| HashiCorp Vault (key management) | HashiCorp | Low | Fall back to Docker secrets + encrypted file store |
| Let's Encrypt or internal CA for production certificates | Internal IT | Medium | Use self-signed certs for development; plan CA setup 2 weeks before production |

### 7.4 Milestone 8 Completion Checklist

| # | Item | Owner | Status |
|---|---|---|---|
| 1 | RLS policies active on all 8 schemas | Dev 1 | [ ] |
| 2 | PII column encryption with key rotation | Dev 1 | [ ] |
| 3 | TLS-only connections (pg_hba.conf hardened) | Dev 1 + Dev 2 | [ ] |
| 4 | SSL certificates generated and deployed | Dev 2 | [ ] |
| 5 | Patroni HA cluster operational | Dev 2 | [ ] |
| 6 | HAProxy read/write splitting verified | Dev 2 | [ ] |
| 7 | TimescaleDB hypertables with compression and retention | Dev 1 | [ ] |
| 8 | Elasticsearch xpack security with field-level controls | Dev 1 + Dev 2 | [ ] |
| 9 | Redis ACLs and TLS configured | Dev 1 + Dev 2 | [ ] |
| 10 | pgAudit logging operational with rotation | Dev 1 + Dev 2 | [ ] |
| 11 | DR drill completed with passing results | All | [ ] |
| 12 | Security Dashboard with all widgets | Dev 3 | [ ] |
| 13 | Monitoring alerts configured | Dev 2 + Dev 3 | [ ] |
| 14 | All 43 test cases passing | All | [ ] |
| 15 | Technical summary and handoff documentation | All | [ ] |

---

*End of Milestone 8: Database Security & High Availability*
