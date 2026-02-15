# Milestone 6: Security Architecture Design & Foundation

## Smart Dairy Digital Smart Portal + ERP -- Phase 1: Foundation

| Field            | Detail                                                                        |
| ---------------- | ----------------------------------------------------------------------------- |
| **Milestone**    | 6 of 10                                                                       |
| **Title**        | Security Architecture Design & Foundation                                     |
| **Phase**        | Phase 1 -- Foundation (Part B: Database & Security)                           |
| **Days**         | Days 51--60 (of 100)                                                          |
| **Version**      | 1.0                                                                           |
| **Status**       | Draft                                                                         |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03                                                                    |

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

Milestone 6 establishes the complete security architecture for the Smart Dairy Digital Smart Portal + ERP system. This milestone covers threat modeling, identity and access management, encryption at rest and in transit, network hardening, compliance with Bangladesh regulatory requirements, audit logging, incident response planning, and security testing strategy. Every design decision is documented with working code examples ready for integration into the Odoo 19 CE platform.

### 1.2 Scope

- STRIDE-based threat modeling for all Smart Dairy assets (255 cattle, 75 lactating cows, 900L daily production data, financial records, employee PII)
- Role-Based Access Control (RBAC) with 8 roles and 9 permission types
- Encryption: TLS 1.3 transport, AES-256-GCM at rest, Fernet for PII column-level encryption
- Network security: VPC topology, firewall rules, Nginx hardening, DMZ architecture
- Compliance mapping to Bangladesh ICT Act 2006, Digital Security Act 2018, and GDPR-like protections
- Audit trail framework with 7-year log retention and tamper-proof records
- Incident response playbook and security testing automation (SAST, DAST, dependency scanning)
- Security monitoring dashboard (OWL component)

### 1.3 Success Criteria

| Criterion                                    | Measurement                                    |
| -------------------------------------------- | ---------------------------------------------- |
| Threat model covers all critical assets       | 100% of identified assets in STRIDE matrix     |
| RBAC roles configured and tested              | 8 roles, 9 permission types, all passing tests |
| PII encryption operational                    | All PII fields encrypted with Fernet           |
| TLS 1.3 enforced on all endpoints             | SSL Labs grade A+ or equivalent                |
| Audit logging captures all write operations   | Zero gaps in audit trail for CUD operations    |
| Firewall rules block unauthorized traffic     | Penetration test confirms no open side-ports   |
| Compliance checklist 100% addressed           | Bangladesh ICT Act + DSA mapped                |
| Incident response playbook reviewed           | Sign-off from all three developers             |

### 1.4 Team Allocation

| Developer | Primary Responsibilities                                       | Hours |
| --------- | -------------------------------------------------------------- | ----- |
| Dev 1     | RBAC, encryption, audit logging, Vault integration, DB security | 80h   |
| Dev 2     | Network security, firewall, TLS, CI/CD security, IR planning   | 80h   |
| Dev 3     | Security dashboard UI, frontend security, CSP policies          | 80h   |

---

## 2. Requirement Traceability Matrix

| Req ID | Requirement Description              | Day(s)  | Deliverable                          | Verification Method       |
| ------ | ------------------------------------ | ------- | ------------------------------------ | ------------------------- |
| F-001  | Security Architecture Design         | 51--60  | Complete security architecture doc   | Architecture review       |
| F-001a | Threat Modeling (STRIDE)             | 51      | Threat model document                | Peer review               |
| F-001b | RBAC with 8 Roles                    | 52      | Odoo security groups XML             | Role-based access tests   |
| F-001c | Encryption at Rest (AES-256)         | 53, 56  | Encryption utility + DB config       | Encryption validation     |
| F-001d | Encryption in Transit (TLS 1.3)      | 54, 56  | Nginx TLS config + certs             | SSL Labs / testssl.sh     |
| F-001e | Audit Trail Framework                | 55      | AuditMixin + audit.log schema        | Audit log integrity tests |
| F-011  | Incident Response                    | 58      | IR playbook + escalation matrix      | Tabletop exercise         |
| D-014  | System Hardening                     | 54, 57  | Firewall rules + SAST/DAST config    | Penetration test plan     |
| D-014a | Network Segmentation                 | 54      | VPC topology + security groups       | Network scan              |
| D-014b | Security Testing Pipeline            | 57      | Bandit + ZAP + safety configs        | CI pipeline execution     |

---

## 3. Day-by-Day Breakdown

### Day 51 -- Security Requirements Analysis & Threat Modeling

**Objective:** Conduct comprehensive threat modeling using STRIDE methodology, create asset inventory, define trust boundaries, and classify all data handled by Smart Dairy.

#### Dev 1 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Enumerate all data assets (cattle, milk, financial, HR, customer) | 2h |
| 2 | Build STRIDE threat model for backend services | 3h |
| 3 | Define data classification levels              | 1.5h |
| 4 | Document trust boundaries between services     | 1.5h |

#### Dev 2 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Map infrastructure attack surface (Docker, Nginx, PostgreSQL, Redis) | 3h |
| 2 | Identify network trust boundaries and entry points | 2h |
| 3 | Evaluate third-party dependency risks          | 1.5h |
| 4 | Draft infrastructure threat register           | 1.5h |

#### Dev 3 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Analyze frontend attack surface (XSS, CSRF, clickjacking) | 3h |
| 2 | Map client-side data flows and storage         | 2h |
| 3 | Review OWL framework security characteristics  | 1.5h |
| 4 | Document Flutter mobile app threat vectors     | 1.5h |

**STRIDE Threat Model Document Structure:**

```yaml
# smart_dairy_threat_model.yaml
# STRIDE Threat Model for Smart Dairy Digital Smart Portal + ERP

metadata:
  project: Smart Dairy Digital Smart Portal + ERP
  version: "1.0"
  date: "2026-02-03"
  methodology: STRIDE
  authors:
    - Dev 1 (Backend Lead)
    - Dev 2 (Full-Stack/DevOps)
    - Dev 3 (Frontend/Mobile Lead)

assets:
  - id: A-001
    name: Cattle Health Records
    classification: Confidential
    description: Medical history, vaccination records, disease logs for 255 cattle
    owner: Veterinarian

  - id: A-002
    name: Milk Production Data
    classification: Internal
    description: Daily yield records (~900L/day), quality test results, 75 lactating cows
    owner: Farm Manager

  - id: A-003
    name: Financial Records
    classification: Restricted
    description: Revenue, expenses, payroll, invoices, tax filings
    owner: Accountant

  - id: A-004
    name: Employee PII
    classification: Restricted
    description: Names, NID numbers, addresses, salary, bank details
    owner: HR Manager

  - id: A-005
    name: Customer Data
    classification: Confidential
    description: Buyer profiles, order history, contact details
    owner: Farm Manager

  - id: A-006
    name: Authentication Credentials
    classification: Restricted
    description: User passwords, API keys, session tokens
    owner: Super Admin

trust_boundaries:
  - id: TB-001
    name: Internet / DMZ Boundary
    description: Nginx reverse proxy separates public internet from application tier

  - id: TB-002
    name: Application / Database Boundary
    description: Odoo application connects to PostgreSQL over private network only

  - id: TB-003
    name: Internal / External API Boundary
    description: FastAPI microservices isolated from Odoo core via internal network

threats:
  spoofing:
    - id: S-001
      target: A-006
      description: Attacker impersonates legitimate user via stolen credentials
      likelihood: High
      impact: Critical
      mitigation: MFA enforcement, session management, bcrypt password hashing

    - id: S-002
      target: TB-003
      description: Rogue service spoofs API identity
      likelihood: Medium
      impact: High
      mitigation: Mutual TLS between services, API key validation

  tampering:
    - id: T-001
      target: A-002
      description: Unauthorized modification of milk production records
      likelihood: Medium
      impact: High
      mitigation: Audit logging with AuditMixin, write-once audit records

    - id: T-002
      target: A-003
      description: Financial record tampering
      likelihood: Low
      impact: Critical
      mitigation: Database triggers, hash-chain audit logs, RBAC restrictions

  repudiation:
    - id: R-001
      target: All assets
      description: User denies performing an action (e.g., data deletion)
      likelihood: Medium
      impact: High
      mitigation: Tamper-proof audit trail with IP, timestamp, user, before/after

  information_disclosure:
    - id: I-001
      target: A-004
      description: Employee PII leaked via SQL injection or insecure API
      likelihood: Medium
      impact: Critical
      mitigation: Fernet encryption on PII columns, parameterized queries

    - id: I-002
      target: A-006
      description: Credentials exposed in logs or error messages
      likelihood: Medium
      impact: Critical
      mitigation: Log scrubbing, secret management via Vault

  denial_of_service:
    - id: D-001
      target: TB-001
      description: DDoS attack on public-facing Nginx
      likelihood: High
      impact: High
      mitigation: Rate limiting, fail2ban, CDN, connection limits

  elevation_of_privilege:
    - id: E-001
      target: A-003
      description: Milk Collector escalates to Accountant privileges
      likelihood: Low
      impact: Critical
      mitigation: Strict RBAC groups, no implicit permissions, regular audits
```

**Data Classification Matrix:**

```python
# addons/smart_dairy_security/models/data_classification.py

from enum import Enum
from dataclasses import dataclass
from typing import List


class ClassificationLevel(Enum):
    """Data classification levels for Smart Dairy.

    Aligned with Bangladesh Digital Security Act 2018 and
    international best practices.
    """
    PUBLIC = "public"
    INTERNAL = "internal"
    CONFIDENTIAL = "confidential"
    RESTRICTED = "restricted"


@dataclass
class DataClassification:
    level: ClassificationLevel
    description: str
    encryption_required: bool
    access_roles: List[str]
    retention_years: int
    audit_required: bool


DATA_CLASSIFICATIONS = {
    ClassificationLevel.PUBLIC: DataClassification(
        level=ClassificationLevel.PUBLIC,
        description="Publicly available information (product catalog, farm overview)",
        encryption_required=False,
        access_roles=["public", "customer", "milk_collector", "veterinarian",
                       "farm_manager", "accountant", "hr_manager", "super_admin"],
        retention_years=1,
        audit_required=False,
    ),
    ClassificationLevel.INTERNAL: DataClassification(
        level=ClassificationLevel.INTERNAL,
        description="Internal operational data (milk yields, cattle counts, schedules)",
        encryption_required=False,
        access_roles=["milk_collector", "veterinarian", "farm_manager",
                       "accountant", "hr_manager", "super_admin"],
        retention_years=3,
        audit_required=True,
    ),
    ClassificationLevel.CONFIDENTIAL: DataClassification(
        level=ClassificationLevel.CONFIDENTIAL,
        description="Sensitive business data (health records, customer data, contracts)",
        encryption_required=True,
        access_roles=["veterinarian", "farm_manager", "accountant",
                       "hr_manager", "super_admin"],
        retention_years=5,
        audit_required=True,
    ),
    ClassificationLevel.RESTRICTED: DataClassification(
        level=ClassificationLevel.RESTRICTED,
        description="Highly sensitive data (PII, financials, credentials, NID numbers)",
        encryption_required=True,
        access_roles=["hr_manager", "super_admin"],
        retention_years=7,
        audit_required=True,
    ),
}
```

**End-of-Day 51 Deliverables:**
- [x] STRIDE threat model document (`smart_dairy_threat_model.yaml`)
- [x] Asset inventory with classification levels
- [x] Trust boundary diagrams
- [x] Data classification matrix implementation
- [x] Threat register with likelihood/impact ratings

---

### Day 52 -- Identity & Access Management (IAM) Design

**Objective:** Design and implement the complete RBAC model with 8 roles, 9 permission types, group hierarchy in Odoo 19 CE, and permission matrix enforcement.

#### Dev 1 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Define 8 roles as Odoo security groups in XML  | 3h    |
| 2 | Build permission matrix (9 permission types)   | 2.5h  |
| 3 | Implement record rules for row-level security  | 1.5h  |
| 4 | Write unit tests for role-permission mappings   | 1h    |

#### Dev 2 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Configure Odoo session management and timeouts | 2h    |
| 2 | Set up password policy enforcement             | 2h    |
| 3 | Integrate LDAP/OAuth2 authentication stubs     | 2.5h  |
| 4 | Document IAM architecture diagrams             | 1.5h  |

#### Dev 3 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Implement frontend route guards per role       | 3h    |
| 2 | Build role-aware UI component visibility logic | 2.5h  |
| 3 | Design permission-denied user experience       | 1.5h  |
| 4 | Create role selector for testing/dev mode      | 1h    |

**RBAC Role Definitions (Odoo XML):**

```xml
<?xml version="1.0" encoding="utf-8"?>
<!-- addons/smart_dairy_security/security/security_groups.xml -->
<odoo>
    <!-- ============================================================ -->
    <!-- Module Category: Smart Dairy                                  -->
    <!-- ============================================================ -->
    <record id="module_category_smart_dairy" model="ir.module.category">
        <field name="name">Smart Dairy</field>
        <field name="description">Smart Dairy Digital Smart Portal roles and permissions</field>
        <field name="sequence">100</field>
    </record>

    <!-- ============================================================ -->
    <!-- Role 1: Public (unauthenticated / minimal access)            -->
    <!-- ============================================================ -->
    <record id="group_smart_dairy_public" model="res.groups">
        <field name="name">Public User</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="comment">
            Read-only access to public product catalog and farm overview.
            No authentication required for designated public endpoints.
        </field>
    </record>

    <!-- ============================================================ -->
    <!-- Role 2: Customer                                              -->
    <!-- ============================================================ -->
    <record id="group_smart_dairy_customer" model="res.groups">
        <field name="name">Customer</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[(4, ref('group_smart_dairy_public'))]"/>
        <field name="comment">
            Authenticated buyers. Can view products, place orders,
            view own order history. Cannot access farm operations.
        </field>
    </record>

    <!-- ============================================================ -->
    <!-- Role 3: Milk Collector                                        -->
    <!-- ============================================================ -->
    <record id="group_smart_dairy_milk_collector" model="res.groups">
        <field name="name">Milk Collector</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[(4, ref('group_smart_dairy_public'))]"/>
        <field name="comment">
            Field staff responsible for daily milk collection.
            Can record milk yields, view assigned cattle, update collection logs.
        </field>
    </record>

    <!-- ============================================================ -->
    <!-- Role 4: Veterinarian                                          -->
    <!-- ============================================================ -->
    <record id="group_smart_dairy_veterinarian" model="res.groups">
        <field name="name">Veterinarian</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[(4, ref('group_smart_dairy_public'))]"/>
        <field name="comment">
            Manages cattle health records, vaccinations, treatments,
            breeding schedules. Full access to animal health data.
        </field>
    </record>

    <!-- ============================================================ -->
    <!-- Role 5: Accountant                                            -->
    <!-- ============================================================ -->
    <record id="group_smart_dairy_accountant" model="res.groups">
        <field name="name">Accountant</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[(4, ref('group_smart_dairy_public'))]"/>
        <field name="comment">
            Manages financial records, invoicing, payroll processing,
            tax reports. Access to all financial modules.
        </field>
    </record>

    <!-- ============================================================ -->
    <!-- Role 6: HR Manager                                            -->
    <!-- ============================================================ -->
    <record id="group_smart_dairy_hr_manager" model="res.groups">
        <field name="name">HR Manager</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[(4, ref('group_smart_dairy_public'))]"/>
        <field name="comment">
            Manages employee records, attendance, leave, PII data.
            Access to restricted employee information including NID.
        </field>
    </record>

    <!-- ============================================================ -->
    <!-- Role 7: Farm Manager                                          -->
    <!-- ============================================================ -->
    <record id="group_smart_dairy_farm_manager" model="res.groups">
        <field name="name">Farm Manager</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[
            (4, ref('group_smart_dairy_milk_collector')),
            (4, ref('group_smart_dairy_veterinarian')),
        ]"/>
        <field name="comment">
            Oversees all farm operations. Inherits Milk Collector and
            Veterinarian permissions. Can approve operations and view reports.
        </field>
    </record>

    <!-- ============================================================ -->
    <!-- Role 8: Super Admin                                           -->
    <!-- ============================================================ -->
    <record id="group_smart_dairy_super_admin" model="res.groups">
        <field name="name">Super Admin</field>
        <field name="category_id" ref="module_category_smart_dairy"/>
        <field name="implied_ids" eval="[
            (4, ref('group_smart_dairy_farm_manager')),
            (4, ref('group_smart_dairy_accountant')),
            (4, ref('group_smart_dairy_hr_manager')),
        ]"/>
        <field name="comment">
            Full system access. Inherits all other roles.
            Can manage users, configure system, access audit logs.
        </field>
    </record>
</odoo>
```

**Permission Matrix Python Implementation:**

```python
# addons/smart_dairy_security/models/permission_matrix.py

from enum import Flag, auto
from typing import Dict, Set
import logging

_logger = logging.getLogger(__name__)


class Permission(Flag):
    """Nine permission types for Smart Dairy RBAC."""
    CREATE  = auto()
    READ    = auto()
    UPDATE  = auto()
    DELETE  = auto()
    EXPORT  = auto()
    IMPORT  = auto()
    APPROVE = auto()
    AUDIT   = auto()
    ADMIN   = auto()

    # Convenience combinations
    CRUD = CREATE | READ | UPDATE | DELETE
    DATA_TRANSFER = EXPORT | IMPORT
    FULL = CREATE | READ | UPDATE | DELETE | EXPORT | IMPORT | APPROVE | AUDIT | ADMIN


class Role:
    """Defines the 8 Smart Dairy roles."""
    PUBLIC = "public"
    CUSTOMER = "customer"
    MILK_COLLECTOR = "milk_collector"
    VETERINARIAN = "veterinarian"
    ACCOUNTANT = "accountant"
    HR_MANAGER = "hr_manager"
    FARM_MANAGER = "farm_manager"
    SUPER_ADMIN = "super_admin"


class Resource:
    """System resources subject to permission checks."""
    CATTLE_RECORDS = "cattle.records"
    MILK_COLLECTION = "milk.collection"
    HEALTH_RECORDS = "health.records"
    FINANCIAL_RECORDS = "financial.records"
    EMPLOYEE_PII = "employee.pii"
    CUSTOMER_DATA = "customer.data"
    AUDIT_LOGS = "audit.logs"
    SYSTEM_CONFIG = "system.config"
    PRODUCT_CATALOG = "product.catalog"
    REPORTS = "reports"


# ------------------------------------------------------------------ #
#  Permission Matrix: Role -> Resource -> Permissions                  #
# ------------------------------------------------------------------ #
PERMISSION_MATRIX: Dict[str, Dict[str, Permission]] = {
    Role.PUBLIC: {
        Resource.PRODUCT_CATALOG: Permission.READ,
    },
    Role.CUSTOMER: {
        Resource.PRODUCT_CATALOG: Permission.READ,
        Resource.CUSTOMER_DATA: Permission.READ | Permission.UPDATE,  # own data only
    },
    Role.MILK_COLLECTOR: {
        Resource.MILK_COLLECTION: Permission.CREATE | Permission.READ | Permission.UPDATE,
        Resource.CATTLE_RECORDS: Permission.READ,
        Resource.PRODUCT_CATALOG: Permission.READ,
    },
    Role.VETERINARIAN: {
        Resource.HEALTH_RECORDS: Permission.CRUD,
        Resource.CATTLE_RECORDS: Permission.READ | Permission.UPDATE,
        Resource.PRODUCT_CATALOG: Permission.READ,
        Resource.REPORTS: Permission.READ | Permission.EXPORT,
    },
    Role.ACCOUNTANT: {
        Resource.FINANCIAL_RECORDS: Permission.CRUD | Permission.EXPORT | Permission.APPROVE,
        Resource.REPORTS: Permission.READ | Permission.EXPORT,
        Resource.PRODUCT_CATALOG: Permission.READ,
    },
    Role.HR_MANAGER: {
        Resource.EMPLOYEE_PII: Permission.CRUD | Permission.EXPORT | Permission.IMPORT,
        Resource.REPORTS: Permission.READ | Permission.EXPORT,
        Resource.PRODUCT_CATALOG: Permission.READ,
    },
    Role.FARM_MANAGER: {
        Resource.CATTLE_RECORDS: Permission.CRUD | Permission.APPROVE,
        Resource.MILK_COLLECTION: Permission.CRUD | Permission.APPROVE | Permission.EXPORT,
        Resource.HEALTH_RECORDS: Permission.CRUD | Permission.APPROVE,
        Resource.CUSTOMER_DATA: Permission.READ,
        Resource.REPORTS: Permission.READ | Permission.EXPORT,
        Resource.PRODUCT_CATALOG: Permission.CRUD,
    },
    Role.SUPER_ADMIN: {
        Resource.CATTLE_RECORDS: Permission.FULL,
        Resource.MILK_COLLECTION: Permission.FULL,
        Resource.HEALTH_RECORDS: Permission.FULL,
        Resource.FINANCIAL_RECORDS: Permission.FULL,
        Resource.EMPLOYEE_PII: Permission.FULL,
        Resource.CUSTOMER_DATA: Permission.FULL,
        Resource.AUDIT_LOGS: Permission.READ | Permission.AUDIT,
        Resource.SYSTEM_CONFIG: Permission.FULL,
        Resource.PRODUCT_CATALOG: Permission.FULL,
        Resource.REPORTS: Permission.FULL,
    },
}


def check_permission(role: str, resource: str, permission: Permission) -> bool:
    """Check whether a given role has the specified permission on a resource.

    Args:
        role: One of the Role constants.
        resource: One of the Resource constants.
        permission: A Permission flag (may be a combination).

    Returns:
        True if the role holds ALL requested permission flags on the resource.
    """
    role_perms = PERMISSION_MATRIX.get(role, {})
    granted = role_perms.get(resource, Permission(0))
    has_access = (granted & permission) == permission
    if not has_access:
        _logger.warning(
            "Permission denied: role=%s resource=%s requested=%s granted=%s",
            role, resource, permission, granted,
        )
    return has_access


def get_permitted_resources(role: str) -> Dict[str, Permission]:
    """Return all resources and their permissions for a given role."""
    return PERMISSION_MATRIX.get(role, {})


def get_roles_for_resource(resource: str, permission: Permission) -> Set[str]:
    """Return all roles that have the specified permission on a resource."""
    return {
        role for role, resources in PERMISSION_MATRIX.items()
        if (resources.get(resource, Permission(0)) & permission) == permission
    }
```

**End-of-Day 52 Deliverables:**
- [x] Odoo security groups XML with 8 roles and hierarchy
- [x] Permission matrix implementation (9 permission types)
- [x] Record rules for row-level security
- [x] Frontend route guard design
- [x] IAM architecture documentation

---

### Day 53 -- Database Security Design

**Objective:** Implement encryption at rest with AES-256, column-level Fernet encryption for PII fields, and integrate HashiCorp Vault for key management.

#### Dev 1 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Implement Fernet encryption utility class      | 3h    |
| 2 | Build HashiCorp Vault integration client       | 2.5h  |
| 3 | Apply column-level encryption to PII models    | 1.5h  |
| 4 | Write encryption round-trip tests              | 1h    |

#### Dev 2 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Configure PostgreSQL 16 TDE (transparent data encryption) | 3h |
| 2 | Set up HashiCorp Vault server in Docker        | 2.5h  |
| 3 | Configure Vault auto-unseal and audit backend  | 1.5h  |
| 4 | Document key rotation procedures               | 1h    |

#### Dev 3 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Implement client-side data sanitization        | 3h    |
| 2 | Build encrypted local storage wrapper (Flutter)| 2.5h  |
| 3 | Design PII masking for UI display              | 1.5h  |
| 4 | Test encrypted data rendering in OWL           | 1h    |

**Fernet Encryption Utility Class:**

```python
# addons/smart_dairy_security/utils/encryption.py

import base64
import logging
import os
from datetime import datetime, timezone
from typing import Optional, List

from cryptography.fernet import Fernet, MultiFernet, InvalidToken

_logger = logging.getLogger(__name__)


class SmartDairyEncryption:
    """Fernet-based encryption utility for PII fields.

    Supports key rotation via MultiFernet: the first key in the list
    is always used for encryption; all keys are tried for decryption.
    Keys are loaded from HashiCorp Vault or environment variables.

    Usage:
        encryptor = SmartDairyEncryption.from_env()
        cipher = encryptor.encrypt("NID-12345678")
        plain  = encryptor.decrypt(cipher)
    """

    def __init__(self, keys: List[bytes]):
        """Initialize with one or more Fernet keys (newest first).

        Args:
            keys: List of 32-byte URL-safe base64-encoded Fernet keys.
                  The first key is the current encryption key.

        Raises:
            ValueError: If no keys are provided.
        """
        if not keys:
            raise ValueError("At least one encryption key is required.")
        fernets = [Fernet(k) for k in keys]
        self._multi = MultiFernet(fernets)
        self._current_key_id = base64.urlsafe_b64encode(keys[0][:8]).decode()
        _logger.info("SmartDairyEncryption initialized with %d key(s).", len(keys))

    @classmethod
    def from_env(cls) -> "SmartDairyEncryption":
        """Create an instance using keys from environment variables.

        Reads SMART_DAIRY_FERNET_KEYS (comma-separated base64 keys).
        Falls back to SMART_DAIRY_FERNET_KEY for single-key setups.
        """
        multi = os.environ.get("SMART_DAIRY_FERNET_KEYS")
        if multi:
            keys = [k.strip().encode() for k in multi.split(",") if k.strip()]
        else:
            single = os.environ.get("SMART_DAIRY_FERNET_KEY")
            if not single:
                raise EnvironmentError(
                    "Set SMART_DAIRY_FERNET_KEYS or SMART_DAIRY_FERNET_KEY."
                )
            keys = [single.strip().encode()]
        return cls(keys)

    @classmethod
    def from_vault(cls, vault_client, secret_path: str = "secret/data/smart_dairy/fernet"):
        """Load encryption keys from HashiCorp Vault.

        Args:
            vault_client: An authenticated VaultClient instance.
            secret_path: Vault KV v2 path for the Fernet keys.
        """
        secret = vault_client.read_secret(secret_path)
        keys_str = secret.get("fernet_keys", "")
        keys = [k.strip().encode() for k in keys_str.split(",") if k.strip()]
        return cls(keys)

    @staticmethod
    def generate_key() -> str:
        """Generate a new Fernet key. Use this for initial setup or rotation."""
        return Fernet.generate_key().decode()

    def encrypt(self, plaintext: str) -> str:
        """Encrypt a plaintext string.

        Args:
            plaintext: The string to encrypt (e.g., NID number, phone).

        Returns:
            Base64-encoded ciphertext string, safe for database storage.
        """
        if not plaintext:
            return ""
        token = self._multi.encrypt(plaintext.encode("utf-8"))
        return token.decode("utf-8")

    def decrypt(self, ciphertext: str) -> str:
        """Decrypt a ciphertext string.

        Tries all keys in the MultiFernet ring (handles key rotation).

        Args:
            ciphertext: Base64-encoded Fernet token.

        Returns:
            Original plaintext string.

        Raises:
            InvalidToken: If decryption fails with all available keys.
        """
        if not ciphertext:
            return ""
        try:
            plaintext = self._multi.decrypt(ciphertext.encode("utf-8"))
            return plaintext.decode("utf-8")
        except InvalidToken:
            _logger.error("Decryption failed -- token invalid or key mismatch.")
            raise

    def rotate(self, ciphertext: str) -> str:
        """Re-encrypt a ciphertext with the current (newest) key.

        Use during key rotation to migrate old ciphertexts.

        Args:
            ciphertext: Existing Fernet token encrypted with an older key.

        Returns:
            New Fernet token encrypted with the current key.
        """
        if not ciphertext:
            return ""
        rotated = self._multi.rotate(ciphertext.encode("utf-8"))
        return rotated.decode("utf-8")
```

**HashiCorp Vault Integration Client:**

```python
# addons/smart_dairy_security/utils/vault_client.py

import logging
import os
from typing import Any, Dict, Optional

import hvac

_logger = logging.getLogger(__name__)


class VaultClient:
    """HashiCorp Vault client for Smart Dairy secret management.

    Provides methods for retrieving static secrets, generating dynamic
    database credentials, and managing encryption keys.

    Environment variables:
        VAULT_ADDR:  Vault server URL (default: http://127.0.0.1:8200)
        VAULT_TOKEN: Authentication token (for dev/testing)
        VAULT_ROLE_ID / VAULT_SECRET_ID: AppRole authentication (production)
    """

    def __init__(
        self,
        url: Optional[str] = None,
        token: Optional[str] = None,
        role_id: Optional[str] = None,
        secret_id: Optional[str] = None,
    ):
        self.url = url or os.environ.get("VAULT_ADDR", "http://127.0.0.1:8200")
        self._client = hvac.Client(url=self.url)

        if token or os.environ.get("VAULT_TOKEN"):
            self._client.token = token or os.environ["VAULT_TOKEN"]
        elif role_id or os.environ.get("VAULT_ROLE_ID"):
            self._auth_approle(
                role_id or os.environ["VAULT_ROLE_ID"],
                secret_id or os.environ["VAULT_SECRET_ID"],
            )
        else:
            raise EnvironmentError("No Vault authentication method configured.")

        if not self._client.is_authenticated():
            raise ConnectionError("Vault authentication failed.")
        _logger.info("Vault client authenticated at %s", self.url)

    def _auth_approle(self, role_id: str, secret_id: str) -> None:
        """Authenticate using Vault AppRole method."""
        resp = self._client.auth.approle.login(
            role_id=role_id,
            secret_id=secret_id,
        )
        self._client.token = resp["auth"]["client_token"]
        _logger.info("Vault AppRole authentication successful.")

    def read_secret(self, path: str) -> Dict[str, Any]:
        """Read a secret from Vault KV v2 secrets engine.

        Args:
            path: Full Vault path (e.g., 'secret/data/smart_dairy/db').

        Returns:
            Dictionary of secret key-value pairs.
        """
        response = self._client.secrets.kv.v2.read_secret_version(path=path)
        return response["data"]["data"]

    def get_database_credentials(self, role: str = "smart-dairy-app") -> Dict[str, str]:
        """Generate dynamic PostgreSQL credentials via Vault database engine.

        Args:
            role: Vault database role name.

        Returns:
            Dict with 'username', 'password', and 'lease_id'.
        """
        creds = self._client.secrets.databases.generate_credentials(name=role)
        return {
            "username": creds["data"]["username"],
            "password": creds["data"]["password"],
            "lease_id": creds["lease_id"],
            "lease_duration": creds["lease_duration"],
        }

    def get_fernet_keys(self) -> str:
        """Retrieve Fernet encryption keys from Vault."""
        secret = self.read_secret("smart_dairy/fernet")
        return secret.get("fernet_keys", "")

    def renew_lease(self, lease_id: str, increment: int = 3600) -> None:
        """Renew a Vault lease (e.g., for dynamic DB credentials)."""
        self._client.sys.renew_lease(lease_id=lease_id, increment=increment)
        _logger.info("Lease %s renewed for %ds.", lease_id, increment)
```

**End-of-Day 53 Deliverables:**
- [x] Fernet encryption utility with key rotation support
- [x] Vault integration client (static secrets + dynamic DB credentials)
- [x] PostgreSQL TDE configuration
- [x] Client-side data sanitization and PII masking designs

---

### Day 54 -- Network Security Design

**Objective:** Design VPC topology, implement firewall rules, configure DMZ for public-facing services, and harden Nginx as the reverse proxy.

#### Dev 1 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Define application-level network requirements  | 2h    |
| 2 | Configure PostgreSQL pg_hba.conf for network isolation | 2h |
| 3 | Set up Redis authentication and ACLs           | 2h    |
| 4 | Document internal service communication matrix | 2h    |

#### Dev 2 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Design VPC topology and subnet allocation      | 2h    |
| 2 | Write iptables/nftables firewall rules         | 3h    |
| 3 | Configure Nginx security hardening             | 2h    |
| 4 | Set up fail2ban for SSH and web endpoints      | 1h    |

#### Dev 3 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Implement CORS policy configuration            | 2h    |
| 2 | Configure Content Security Policy headers      | 2.5h  |
| 3 | Set up Subresource Integrity for static assets | 2h    |
| 4 | Test cross-origin restrictions in browser       | 1.5h  |

**Nginx Security Hardening Configuration:**

```nginx
# config/nginx/smart_dairy_ssl.conf
# Nginx security-hardened configuration for Smart Dairy Portal

# Rate limiting zones
limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
limit_req_zone $binary_remote_addr zone=api:10m rate=30r/s;
limit_req_zone $binary_remote_addr zone=general:10m rate=10r/s;
limit_conn_zone $binary_remote_addr zone=addr:10m;

# Upstream definitions
upstream odoo_backend {
    server 127.0.0.1:8069;
    keepalive 32;
}

upstream fastapi_backend {
    server 127.0.0.1:8000;
    keepalive 16;
}

# Redirect all HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name dairy.smartdairy.com.bd;
    return 301 https://$server_name$request_uri;
}

# Main HTTPS server block
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name dairy.smartdairy.com.bd;

    # -----------------------------------------------------------
    # TLS 1.3 Configuration
    # -----------------------------------------------------------
    ssl_certificate     /etc/letsencrypt/live/dairy.smartdairy.com.bd/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/dairy.smartdairy.com.bd/privkey.pem;
    ssl_trusted_certificate /etc/letsencrypt/live/dairy.smartdairy.com.bd/chain.pem;

    ssl_protocols TLSv1.3;
    ssl_prefer_server_ciphers off;
    ssl_session_timeout 1d;
    ssl_session_cache shared:SSL:10m;
    ssl_session_tickets off;

    # OCSP Stapling
    ssl_stapling on;
    ssl_stapling_verify on;
    resolver 8.8.8.8 8.8.4.4 valid=300s;
    resolver_timeout 5s;

    # -----------------------------------------------------------
    # Security Headers
    # -----------------------------------------------------------
    add_header Strict-Transport-Security "max-age=63072000; includeSubDomains; preload" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "camera=(), microphone=(), geolocation=()" always;
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: blob:; font-src 'self'; connect-src 'self'; frame-ancestors 'self';" always;

    # Hide server version
    server_tokens off;
    more_clear_headers Server;

    # -----------------------------------------------------------
    # Connection Limits
    # -----------------------------------------------------------
    limit_conn addr 20;
    client_max_body_size 25m;
    client_body_timeout 15s;
    client_header_timeout 15s;
    send_timeout 30s;

    # -----------------------------------------------------------
    # Odoo Backend
    # -----------------------------------------------------------
    location / {
        limit_req zone=general burst=20 nodelay;
        proxy_pass http://odoo_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_read_timeout 720s;
        proxy_connect_timeout 10s;
    }

    # Login endpoint with strict rate limiting
    location /web/login {
        limit_req zone=login burst=3 nodelay;
        proxy_pass http://odoo_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    # -----------------------------------------------------------
    # FastAPI Microservices
    # -----------------------------------------------------------
    location /api/v1/ {
        limit_req zone=api burst=50 nodelay;
        proxy_pass http://fastapi_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    # -----------------------------------------------------------
    # Static Files (with caching)
    # -----------------------------------------------------------
    location ~* /web/static/ {
        proxy_pass http://odoo_backend;
        proxy_cache_valid 200 60m;
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # -----------------------------------------------------------
    # Block sensitive paths
    # -----------------------------------------------------------
    location ~ /\. { deny all; }
    location ~ ~$  { deny all; }
    location = /web/database/manager { deny all; }
    location = /web/database/selector { deny all; }
}
```

**Firewall Rules Script (nftables):**

```bash
#!/usr/bin/env bash
# scripts/security/firewall_setup.sh
# nftables firewall rules for Smart Dairy production server

set -euo pipefail

echo "[*] Smart Dairy Firewall Configuration (nftables)"

# Flush existing rules
nft flush ruleset

nft -f - <<'EOF'
table inet smart_dairy_filter {

    # ---- Sets for trusted networks ----
    set trusted_admin {
        type ipv4_addr
        elements = { 10.0.1.0/24 }
        comment "Admin subnet"
    }

    set docker_internal {
        type ipv4_addr
        elements = { 172.18.0.0/16 }
        comment "Docker bridge network"
    }

    # ---- Input chain ----
    chain input {
        type filter hook input priority 0; policy drop;

        # Loopback always allowed
        iif lo accept

        # Established and related connections
        ct state established,related accept

        # Drop invalid
        ct state invalid drop

        # ICMP (ping) rate limited
        ip protocol icmp limit rate 5/second accept

        # SSH from admin subnet only
        tcp dport 22 ip saddr @trusted_admin ct state new accept

        # HTTP/HTTPS from anywhere
        tcp dport { 80, 443 } ct state new accept

        # PostgreSQL from Docker internal only
        tcp dport 5432 ip saddr @docker_internal accept

        # Redis from Docker internal only
        tcp dport 6379 ip saddr @docker_internal accept

        # Odoo from Docker internal only (direct access)
        tcp dport 8069 ip saddr @docker_internal accept

        # FastAPI from Docker internal only
        tcp dport 8000 ip saddr @docker_internal accept

        # Vault from Docker internal only
        tcp dport 8200 ip saddr @docker_internal accept

        # Log and drop everything else
        log prefix "NFT-DROP-IN: " level warn
        drop
    }

    # ---- Forward chain (Docker) ----
    chain forward {
        type filter hook forward priority 0; policy drop;
        ct state established,related accept
        ip saddr @docker_internal accept
        ip daddr @docker_internal ct state new accept
        log prefix "NFT-DROP-FWD: " level warn
        drop
    }

    # ---- Output chain ----
    chain output {
        type filter hook output priority 0; policy accept;
        # Allow all outbound (DNS, updates, etc.)
    }
}
EOF

echo "[+] Firewall rules applied successfully."
nft list ruleset
```

**End-of-Day 54 Deliverables:**
- [x] VPC topology diagram with subnet allocation
- [x] nftables firewall rules script
- [x] Nginx security-hardened configuration (TLS 1.3, headers, rate limiting)
- [x] PostgreSQL and Redis network isolation configs
- [x] fail2ban configuration for SSH and web

---

### Day 55 -- Compliance & Audit Trail Design

**Objective:** Build the audit logging framework with tamper-proof records, map to Bangladesh regulatory requirements, and design 7-year retention policy.

#### Dev 1 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Implement AuditMixin for Odoo models           | 3h    |
| 2 | Create audit.log database schema               | 2h    |
| 3 | Build audit log query API                       | 1.5h  |
| 4 | Write audit integrity verification              | 1.5h  |

#### Dev 2 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Configure log aggregation (rsyslog/Loki)       | 2.5h  |
| 2 | Set up log rotation with 7-year retention      | 2h    |
| 3 | Implement log shipping to cold storage          | 2h    |
| 4 | Map Bangladesh ICT Act / DSA requirements      | 1.5h  |

#### Dev 3 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Design audit log viewer UI mockups              | 3h    |
| 2 | Build OWL audit timeline component              | 3h    |
| 3 | Implement filtered audit search interface       | 2h    |

**Audit Logging Mixin for Odoo Models:**

```python
# addons/smart_dairy_security/models/audit_mixin.py

import hashlib
import json
import logging
from datetime import datetime, timezone

from odoo import api, fields, models

_logger = logging.getLogger(__name__)


class AuditLog(models.Model):
    """Tamper-proof audit log storage.

    Every write operation on audited models creates an immutable record
    here. Records are chained via prev_hash to detect tampering.
    Retention: 7 years per Bangladesh ICT Act 2006 requirements.
    """
    _name = "audit.log"
    _description = "Smart Dairy Audit Log"
    _order = "timestamp desc"
    _rec_name = "display_name"

    # ---- Core fields ----
    user_id = fields.Many2one("res.users", string="User", required=True, index=True)
    action = fields.Selection(
        [
            ("create", "Create"),
            ("write", "Update"),
            ("unlink", "Delete"),
            ("read", "Read"),
            ("export", "Export"),
            ("import", "Import"),
            ("approve", "Approve"),
            ("login", "Login"),
            ("logout", "Logout"),
            ("login_failed", "Login Failed"),
        ],
        string="Action",
        required=True,
        index=True,
    )
    model_name = fields.Char(string="Model", required=True, index=True)
    record_id = fields.Integer(string="Record ID", index=True)
    record_display = fields.Char(string="Record Name")

    # ---- Change tracking ----
    old_values = fields.Text(string="Old Values (JSON)")
    new_values = fields.Text(string="New Values (JSON)")
    changed_fields = fields.Char(string="Changed Fields")

    # ---- Context ----
    ip_address = fields.Char(string="IP Address", size=45)
    user_agent = fields.Char(string="User Agent")
    session_id = fields.Char(string="Session ID", index=True)

    # ---- Integrity ----
    timestamp = fields.Datetime(
        string="Timestamp", required=True, default=lambda self: fields.Datetime.now()
    )
    record_hash = fields.Char(string="Record Hash", size=64)
    prev_hash = fields.Char(string="Previous Hash", size=64)

    # ---- Classification ----
    data_classification = fields.Selection(
        [
            ("public", "Public"),
            ("internal", "Internal"),
            ("confidential", "Confidential"),
            ("restricted", "Restricted"),
        ],
        string="Data Classification",
        default="internal",
    )

    display_name = fields.Char(compute="_compute_display_name", store=False)

    @api.depends("action", "model_name", "record_id")
    def _compute_display_name(self):
        for rec in self:
            rec.display_name = f"{rec.action} {rec.model_name}/{rec.record_id}"

    @api.model_create_multi
    def create(self, vals_list):
        """Override create to compute hash chain for tamper detection."""
        last_log = self.search([], limit=1, order="id desc")
        prev = last_log.record_hash if last_log else "0" * 64

        for vals in vals_list:
            vals["prev_hash"] = prev
            hash_input = json.dumps(
                {k: str(v) for k, v in sorted(vals.items()) if k != "record_hash"},
                sort_keys=True,
            )
            vals["record_hash"] = hashlib.sha256(hash_input.encode()).hexdigest()
            prev = vals["record_hash"]

        records = super().create(vals_list)
        return records

    def write(self, vals):
        """Audit log records are immutable. Prevent modification."""
        raise models.ValidationError("Audit log records cannot be modified.")

    def unlink(self):
        """Audit log records cannot be deleted."""
        raise models.ValidationError("Audit log records cannot be deleted.")


class AuditMixin(models.AbstractModel):
    """Mixin that automatically creates audit log entries for CUD operations.

    Usage:
        class CattleRecord(models.Model):
            _name = 'smart_dairy.cattle'
            _inherit = ['smart_dairy.audit.mixin']
    """
    _name = "smart_dairy.audit.mixin"
    _description = "Audit Trail Mixin"

    def _get_audit_context(self):
        """Extract request context for audit logging."""
        request = self.env.context.get("request")
        ip = "127.0.0.1"
        user_agent = "system"
        session_id = ""
        if hasattr(self.env, "request") and self.env.request:
            ip = self.env.request.httprequest.remote_addr or ip
            user_agent = self.env.request.httprequest.user_agent.string or user_agent
            session_id = self.env.request.session.sid or ""
        return ip, user_agent, session_id

    def _serialize_values(self, record, field_names):
        """Serialize field values to JSON-safe dictionary."""
        result = {}
        for fname in field_names:
            field = record._fields.get(fname)
            if field is None:
                continue
            value = record[fname]
            if field.type == "many2one":
                result[fname] = value.id if value else False
            elif field.type in ("one2many", "many2many"):
                result[fname] = value.ids if value else []
            elif field.type in ("date", "datetime"):
                result[fname] = str(value) if value else False
            else:
                result[fname] = value
        return result

    @api.model_create_multi
    def create(self, vals_list):
        records = super().create(vals_list)
        ip, ua, sid = self._get_audit_context()
        audit_vals = []
        for record in records:
            audit_vals.append({
                "user_id": self.env.uid,
                "action": "create",
                "model_name": self._name,
                "record_id": record.id,
                "record_display": record.display_name if hasattr(record, "display_name") else str(record.id),
                "old_values": "{}",
                "new_values": json.dumps(self._serialize_values(record, record._fields.keys())),
                "ip_address": ip,
                "user_agent": ua,
                "session_id": sid,
                "timestamp": fields.Datetime.now(),
            })
        if audit_vals:
            self.env["audit.log"].sudo().create(audit_vals)
        return records

    def write(self, vals):
        ip, ua, sid = self._get_audit_context()
        audit_vals = []
        for record in self:
            old_values = self._serialize_values(record, vals.keys())
            audit_vals.append({
                "user_id": self.env.uid,
                "action": "write",
                "model_name": self._name,
                "record_id": record.id,
                "record_display": record.display_name if hasattr(record, "display_name") else str(record.id),
                "old_values": json.dumps(old_values),
                "new_values": json.dumps(vals),
                "changed_fields": ",".join(vals.keys()),
                "ip_address": ip,
                "user_agent": ua,
                "session_id": sid,
                "timestamp": fields.Datetime.now(),
            })
        result = super().write(vals)
        if audit_vals:
            self.env["audit.log"].sudo().create(audit_vals)
        return result

    def unlink(self):
        ip, ua, sid = self._get_audit_context()
        audit_vals = []
        for record in self:
            audit_vals.append({
                "user_id": self.env.uid,
                "action": "unlink",
                "model_name": self._name,
                "record_id": record.id,
                "record_display": record.display_name if hasattr(record, "display_name") else str(record.id),
                "old_values": json.dumps(self._serialize_values(record, record._fields.keys())),
                "new_values": "{}",
                "ip_address": ip,
                "user_agent": ua,
                "session_id": sid,
                "timestamp": fields.Datetime.now(),
            })
        if audit_vals:
            self.env["audit.log"].sudo().create(audit_vals)
        return super().unlink()
```

**Audit Log Database Schema (SQL reference):**

```sql
-- Reference schema for audit.log (Odoo ORM generates this, shown for documentation)

CREATE TABLE audit_log (
    id              SERIAL PRIMARY KEY,
    user_id         INTEGER NOT NULL REFERENCES res_users(id),
    action          VARCHAR(20) NOT NULL,
    model_name      VARCHAR(128) NOT NULL,
    record_id       INTEGER,
    record_display  VARCHAR(256),
    old_values      TEXT,           -- JSON of previous field values
    new_values      TEXT,           -- JSON of new field values
    changed_fields  VARCHAR(512),
    ip_address      VARCHAR(45),    -- Supports IPv6
    user_agent      VARCHAR(512),
    session_id      VARCHAR(128),
    timestamp       TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    record_hash     CHAR(64),       -- SHA-256 of record contents
    prev_hash       CHAR(64),       -- Previous record hash (chain)
    data_classification VARCHAR(20) DEFAULT 'internal',
    create_date     TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    write_date      TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Performance indexes
CREATE INDEX idx_audit_log_user      ON audit_log(user_id);
CREATE INDEX idx_audit_log_action    ON audit_log(action);
CREATE INDEX idx_audit_log_model     ON audit_log(model_name);
CREATE INDEX idx_audit_log_timestamp ON audit_log(timestamp);
CREATE INDEX idx_audit_log_session   ON audit_log(session_id);
CREATE INDEX idx_audit_log_composite ON audit_log(model_name, record_id, timestamp);

-- Partition by month for retention management
-- (Apply via PostgreSQL 16 declarative partitioning in production)
```

**End-of-Day 55 Deliverables:**
- [x] AuditMixin implementation with before/after change tracking
- [x] audit.log model with hash-chain tamper detection
- [x] Audit log database schema with indexes
- [x] Bangladesh regulatory compliance mapping document
- [x] 7-year log retention and rotation configuration

---

### Day 56 -- Encryption Implementation

**Objective:** Implement TLS 1.3 for all transport, AES-256-GCM for data at rest, complete Fernet integration for PII fields, and automate certificate management.

#### Dev 1 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Integrate Fernet encryption into PII model fields | 3h |
| 2 | Build encrypted field descriptor for Odoo      | 2.5h  |
| 3 | Write encryption migration script for existing data | 1.5h |
| 4 | Test encrypt/decrypt round-trip on all PII fields | 1h |

#### Dev 2 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Set up Let's Encrypt + certbot automation      | 2.5h  |
| 2 | Configure TLS 1.3 on Nginx with OCSP stapling  | 2h    |
| 3 | Enable PostgreSQL SSL connections               | 2h    |
| 4 | Configure Redis TLS                             | 1.5h  |

#### Dev 3 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Implement secure cookie configuration          | 2h    |
| 2 | Add certificate pinning stubs for Flutter app  | 2.5h  |
| 3 | Test TLS connections from all client types      | 2h    |
| 4 | Verify CSP headers do not break OWL components | 1.5h  |

**TLS Certificate Automation Script:**

```bash
#!/usr/bin/env bash
# scripts/security/setup_tls_certificates.sh
# Automate TLS certificate provisioning with Let's Encrypt + certbot

set -euo pipefail

DOMAIN="dairy.smartdairy.com.bd"
EMAIL="admin@smartdairy.com.bd"
WEBROOT="/var/www/certbot"
NGINX_CONF="/etc/nginx/sites-enabled/smart_dairy_ssl.conf"

echo "[*] Smart Dairy TLS Certificate Setup"

# ---- Install certbot if not present ----
if ! command -v certbot &>/dev/null; then
    echo "[+] Installing certbot..."
    apt-get update -qq
    apt-get install -y -qq certbot python3-certbot-nginx
fi

# ---- Obtain certificate ----
echo "[+] Requesting certificate for ${DOMAIN}..."
certbot certonly \
    --nginx \
    --non-interactive \
    --agree-tos \
    --email "${EMAIL}" \
    --domains "${DOMAIN}" \
    --preferred-challenges http \
    --deploy-hook "systemctl reload nginx"

# ---- Verify certificate ----
echo "[+] Verifying certificate..."
openssl x509 -in "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" \
    -noout -subject -dates -issuer

# ---- Set up auto-renewal cron ----
echo "[+] Configuring automatic renewal..."
CRON_CMD="0 3 * * * certbot renew --quiet --deploy-hook 'systemctl reload nginx'"
(crontab -l 2>/dev/null | grep -v certbot; echo "${CRON_CMD}") | crontab -

# ---- Generate DH parameters (if not exists) ----
DH_PARAMS="/etc/nginx/dhparam.pem"
if [ ! -f "${DH_PARAMS}" ]; then
    echo "[+] Generating DH parameters (4096-bit)..."
    openssl dhparam -out "${DH_PARAMS}" 4096
fi

# ---- Test renewal ----
echo "[+] Testing renewal process..."
certbot renew --dry-run

echo "[+] TLS certificate setup complete."
echo "    Certificate: /etc/letsencrypt/live/${DOMAIN}/fullchain.pem"
echo "    Private key: /etc/letsencrypt/live/${DOMAIN}/privkey.pem"
echo "    Auto-renewal: daily at 03:00 via cron"
```

**Security Headers Middleware for FastAPI:**

```python
# services/fastapi_app/middleware/security_headers.py

from starlette.middleware.base import BaseHTTPMiddleware
from starlette.requests import Request
from starlette.responses import Response
from fastapi import FastAPI


class SecurityHeadersMiddleware(BaseHTTPMiddleware):
    """Add security headers to all FastAPI responses.

    Headers aligned with OWASP recommendations and Smart Dairy
    security architecture requirements.
    """

    SECURITY_HEADERS = {
        "Strict-Transport-Security": "max-age=63072000; includeSubDomains; preload",
        "X-Content-Type-Options": "nosniff",
        "X-Frame-Options": "DENY",
        "X-XSS-Protection": "1; mode=block",
        "Referrer-Policy": "strict-origin-when-cross-origin",
        "Permissions-Policy": "camera=(), microphone=(), geolocation=()",
        "Cache-Control": "no-store, no-cache, must-revalidate",
        "Pragma": "no-cache",
        "Content-Security-Policy": (
            "default-src 'self'; "
            "script-src 'self'; "
            "style-src 'self' 'unsafe-inline'; "
            "img-src 'self' data:; "
            "font-src 'self'; "
            "connect-src 'self'; "
            "frame-ancestors 'none';"
        ),
    }

    async def dispatch(self, request: Request, call_next) -> Response:
        response = await call_next(request)
        for header, value in self.SECURITY_HEADERS.items():
            response.headers[header] = value
        # Remove server identification
        response.headers.pop("server", None)
        return response


def register_security_middleware(app: FastAPI) -> None:
    """Register security middleware on a FastAPI application."""
    app.add_middleware(SecurityHeadersMiddleware)
```

**End-of-Day 56 Deliverables:**
- [x] TLS 1.3 operational on all endpoints (Nginx, PostgreSQL, Redis)
- [x] Let's Encrypt certificate automation with auto-renewal
- [x] Fernet encryption integrated into all PII model fields
- [x] FastAPI security headers middleware
- [x] Secure cookie configuration

---

### Day 57 -- Security Testing Strategy

**Objective:** Establish SAST, DAST, and dependency scanning pipelines. Create penetration testing plan.

#### Dev 1 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Configure Bandit for Python SAST               | 2h    |
| 2 | Set up semgrep rules for Odoo-specific patterns | 2.5h  |
| 3 | Configure safety for dependency vulnerability scanning | 1.5h |
| 4 | Write custom security test suite               | 2h    |

#### Dev 2 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Set up OWASP ZAP for automated DAST            | 3h    |
| 2 | Integrate security scanning into CI/CD pipeline | 2.5h  |
| 3 | Configure security scan reporting               | 1.5h  |
| 4 | Draft penetration testing scope document        | 1h    |

#### Dev 3 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Run XSS and CSRF testing on OWL components     | 3h    |
| 2 | Test CSP policy effectiveness                   | 2h    |
| 3 | Validate input sanitization on all forms        | 2h    |
| 4 | Document frontend security test results         | 1h    |

**Bandit Configuration for Python SAST:**

```yaml
# .bandit
# Bandit SAST configuration for Smart Dairy

[bandit]
targets: addons/smart_dairy_security,addons/smart_dairy_cattle,addons/smart_dairy_milk,services/fastapi_app
exclude: /tests/,/test_/,/migrations/,__pycache__
skips: B101
recursive: true
severity: low
confidence: low
```

```yaml
# bandit.yaml
# Extended Bandit configuration

profile:
  name: smart_dairy_security_scan
  include:
    - any_other_function_with_shell_equals_true
    - assert_used
    - exec_used
    - hardcoded_bind_all_interfaces
    - hardcoded_password_default
    - hardcoded_password_funcarg
    - hardcoded_password_string
    - hardcoded_sql_expressions
    - hardcoded_tmp_directory
    - hashlib
    - jinja2_autoescape_false
    - linux_commands_wildcard_injection
    - paramiko_calls
    - request_with_no_cert_validation
    - set_bad_file_permissions
    - snmp_crypto_check
    - sql_statements
    - ssl_with_bad_defaults
    - ssl_with_bad_version
    - ssl_with_no_version
    - start_process_with_a_shell
    - start_process_with_no_shell
    - start_process_with_partial_path
    - subprocess_popen_with_shell_equals_true
    - subprocess_without_shell_equals_true
    - try_except_continue
    - try_except_pass
    - use_of_mako_templates
    - weak_cryptographic_key
    - yaml_load

output_format: json
output_file: reports/bandit_results.json

severity_levels:
  - LOW
  - MEDIUM
  - HIGH

confidence_levels:
  - LOW
  - MEDIUM
  - HIGH
```

**OWASP ZAP Automation Script:**

```bash
#!/usr/bin/env bash
# scripts/security/zap_baseline_scan.sh
# OWASP ZAP automated baseline scan for Smart Dairy

set -euo pipefail

TARGET_URL="${1:-https://dairy.smartdairy.com.bd}"
REPORT_DIR="reports/security/zap"
ZAP_DOCKER_IMAGE="ghcr.io/zaproxy/zaproxy:stable"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

echo "[*] Smart Dairy OWASP ZAP Baseline Scan"
echo "    Target: ${TARGET_URL}"
echo "    Report: ${REPORT_DIR}/zap_report_${TIMESTAMP}.html"

mkdir -p "${REPORT_DIR}"

# ---- ZAP Baseline Scan (passive + light active) ----
docker run --rm \
    -v "$(pwd)/${REPORT_DIR}:/zap/wrk:rw" \
    -t "${ZAP_DOCKER_IMAGE}" \
    zap-baseline.py \
    -t "${TARGET_URL}" \
    -g gen.conf \
    -r "zap_report_${TIMESTAMP}.html" \
    -J "zap_report_${TIMESTAMP}.json" \
    -l WARN \
    --auto \
    -z "-config scanner.strength=MEDIUM \
        -config scanner.threshold=LOW \
        -config view.mode=standard"

EXIT_CODE=$?

# ---- Evaluate results ----
echo ""
if [ ${EXIT_CODE} -eq 0 ]; then
    echo "[+] PASS: No warnings or failures detected."
elif [ ${EXIT_CODE} -eq 1 ]; then
    echo "[!] WARN: Warnings detected. Review report."
elif [ ${EXIT_CODE} -eq 2 ]; then
    echo "[!] FAIL: Failures detected. Immediate review required."
else
    echo "[!] ERROR: ZAP scan encountered an error (exit code: ${EXIT_CODE})."
fi

echo "    HTML Report: ${REPORT_DIR}/zap_report_${TIMESTAMP}.html"
echo "    JSON Report: ${REPORT_DIR}/zap_report_${TIMESTAMP}.json"
exit ${EXIT_CODE}
```

**End-of-Day 57 Deliverables:**
- [x] Bandit SAST configuration and initial scan results
- [x] OWASP ZAP baseline scan automation
- [x] Dependency vulnerability scanning (safety) configured
- [x] Security testing integrated into CI/CD pipeline
- [x] Penetration testing scope document

---

### Day 58 -- Incident Response Planning

**Objective:** Create incident response playbook, escalation matrix, communication templates, and recovery procedures aligned with F-011.

#### Dev 1 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Define incident severity classification        | 2h    |
| 2 | Write technical recovery procedures            | 3h    |
| 3 | Create database backup verification scripts    | 2h    |
| 4 | Document rollback procedures                   | 1h    |

#### Dev 2 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Author incident response playbook              | 3h    |
| 2 | Design escalation matrix and contact tree      | 2h    |
| 3 | Create monitoring alert thresholds             | 2h    |
| 4 | Set up incident communication channels         | 1h    |

#### Dev 3 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Design incident status page UI                 | 3h    |
| 2 | Build notification component for security events| 2.5h  |
| 3 | Create user-facing incident communication templates | 1.5h |
| 4 | Test alert notification delivery               | 1h    |

**Incident Response Playbook Template:**

```yaml
# docs/security/incident_response_playbook.yaml
# Smart Dairy Incident Response Playbook (F-011)

metadata:
  document: Incident Response Playbook
  version: "1.0"
  classification: Confidential
  owner: Dev 2 (Full-Stack/DevOps)
  last_review: "2026-02-03"
  next_review: "2026-05-03"

severity_levels:
  - level: SEV-1 (Critical)
    description: >
      Complete system outage, active data breach, ransomware, compromise
      of restricted data (PII, financial records).
    response_time: 15 minutes
    resolution_target: 4 hours
    escalation: Immediate - all team members + management
    examples:
      - Database breach exposing employee NID numbers
      - Ransomware encryption of production servers
      - Complete loss of milk collection data

  - level: SEV-2 (High)
    description: >
      Partial system degradation, suspected unauthorized access,
      security control failure, compliance violation detected.
    response_time: 30 minutes
    resolution_target: 8 hours
    escalation: Dev 1 + Dev 2 within 30 minutes
    examples:
      - Unauthorized admin login detected
      - TLS certificate expiry causing service interruption
      - Firewall rule misconfiguration exposing internal ports

  - level: SEV-3 (Medium)
    description: >
      Minor security event, failed attack attempt, single user affected,
      non-critical vulnerability discovered.
    response_time: 2 hours
    resolution_target: 24 hours
    escalation: Assigned developer within 2 hours
    examples:
      - Brute-force login attempts (blocked by rate limiting)
      - Minor dependency vulnerability disclosed
      - Single user account compromise (non-admin)

  - level: SEV-4 (Low)
    description: >
      Informational security event, policy violation, security
      improvement opportunity.
    response_time: 24 hours
    resolution_target: 1 week
    escalation: Logged in issue tracker
    examples:
      - Weak password detected during audit
      - Non-critical security header missing
      - Log retention nearing capacity threshold

escalation_matrix:
  primary_contacts:
    - role: Incident Commander
      name: Dev 2 (Full-Stack/DevOps)
      phone: "+880-XXXX-XXXX"
      email: dev2@smartdairy.com.bd
    - role: Technical Lead
      name: Dev 1 (Backend Lead)
      phone: "+880-XXXX-XXXX"
      email: dev1@smartdairy.com.bd
    - role: Communications Lead
      name: Dev 3 (Frontend/Mobile Lead)
      phone: "+880-XXXX-XXXX"
      email: dev3@smartdairy.com.bd

response_phases:
  1_detection:
    actions:
      - Confirm the alert is a genuine security incident (not a false positive)
      - Classify severity level (SEV-1 through SEV-4)
      - Activate incident response team per escalation matrix
      - Begin incident log documentation with timestamp, reporter, initial details
    tools:
      - Monitoring dashboards (Grafana/Prometheus)
      - Audit log query API
      - fail2ban logs and Nginx access logs

  2_containment:
    actions:
      - Isolate affected systems (network segmentation, disable compromised accounts)
      - Preserve forensic evidence (snapshot affected containers, copy logs)
      - Block attacking IP addresses via firewall rules
      - Rotate compromised credentials immediately
      - Assess blast radius (which data/systems were accessed)
    commands:
      isolate_container: "docker network disconnect smart_dairy_net <container>"
      block_ip: "nft add element inet smart_dairy_filter blocklist { <ip> }"
      disable_user: "python3 -c \"import xmlrpc; ...\""  # Odoo user deactivation
      rotate_keys: "scripts/security/rotate_fernet_keys.sh"

  3_eradication:
    actions:
      - Remove attacker access (backdoors, malicious code, compromised keys)
      - Patch exploited vulnerability
      - Rebuild affected containers from known-good images
      - Re-scan with SAST/DAST to confirm remediation
    verification:
      - Run full Bandit scan on affected codebase
      - Execute OWASP ZAP scan against remediated endpoints
      - Verify audit log integrity (hash chain validation)

  4_recovery:
    actions:
      - Restore services from verified backups if needed
      - Gradually restore network connectivity
      - Monitor closely for 48 hours post-recovery
      - Verify data integrity across all databases
    rollback_procedure:
      - "docker-compose down"
      - "pg_restore -d smart_dairy /backups/latest_verified.dump"
      - "docker-compose up -d"
      - "python3 scripts/verify_data_integrity.py"

  5_post_incident:
    actions:
      - Conduct blameless post-mortem within 48 hours
      - Update threat model with new findings
      - Revise security controls as needed
      - File regulatory notification if required (Bangladesh DSA 2018)
      - Update this playbook with lessons learned
    report_template:
      - Incident timeline (detection to resolution)
      - Root cause analysis
      - Data impact assessment
      - Remediation actions taken
      - Recommendations for prevention

regulatory_notifications:
  bangladesh_dsa_2018:
    requirement: >
      Notify Bangladesh Telecommunication Regulatory Commission (BTRC)
      within 72 hours of a confirmed data breach involving personal data.
    contact: BTRC Cyber Security Division
    threshold: Any breach involving employee PII, customer data, or NID numbers
```

**End-of-Day 58 Deliverables:**
- [x] Incident response playbook (5 phases, 4 severity levels)
- [x] Escalation matrix with contacts and response times
- [x] Recovery procedures and rollback scripts
- [x] Monitoring alert threshold configuration
- [x] Incident status page UI design

---

### Day 59 -- Security Documentation & Policy Authoring

**Objective:** Author security policy documents, acceptable use policy, and data handling procedures for Smart Dairy operations.

#### Dev 1 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Write data handling and encryption procedures  | 3h    |
| 2 | Document API security standards                | 2h    |
| 3 | Create secure coding guidelines for team       | 2h    |
| 4 | Review and finalize technical security docs    | 1h    |

#### Dev 2 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Author infrastructure security policy          | 3h    |
| 2 | Document backup and disaster recovery policy   | 2h    |
| 3 | Write access management procedures             | 2h    |
| 4 | Compile compliance checklist (ICT Act, DSA)    | 1h    |

#### Dev 3 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Build OWL SecurityDashboard component          | 4h    |
| 2 | Integrate audit trail viewer                   | 2.5h  |
| 3 | Add failed login monitor widget                | 1.5h  |

**OWL SecurityDashboard Component:**

```javascript
/** @odoo-module **/
// addons/smart_dairy_security/static/src/components/security_dashboard/security_dashboard.js

import { Component, useState, onWillStart } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";
import { formatDateTime } from "@web/core/l10n/dates";

export class SecurityDashboard extends Component {
    static template = "smart_dairy_security.SecurityDashboard";
    static props = {};

    setup() {
        this.orm = useService("orm");
        this.action = useService("action");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            summary: {
                totalEvents: 0,
                failedLogins: 0,
                criticalAlerts: 0,
                activeUsers: 0,
            },
            recentEvents: [],
            failedLogins: [],
            auditTrail: [],
            selectedTab: "overview",
            dateRange: "today",
        });

        onWillStart(async () => {
            await this.loadDashboardData();
        });
    }

    async loadDashboardData() {
        this.state.loading = true;
        try {
            const [summary, events, logins, audit] = await Promise.all([
                this.fetchSecuritySummary(),
                this.fetchRecentEvents(),
                this.fetchFailedLogins(),
                this.fetchAuditTrail(),
            ]);
            this.state.summary = summary;
            this.state.recentEvents = events;
            this.state.failedLogins = logins;
            this.state.auditTrail = audit;
        } catch (error) {
            this.notification.add(
                "Failed to load security dashboard data.",
                { type: "danger" }
            );
            console.error("SecurityDashboard load error:", error);
        } finally {
            this.state.loading = false;
        }
    }

    async fetchSecuritySummary() {
        const results = await this.orm.call(
            "audit.log", "get_security_summary",
            [this.state.dateRange]
        );
        return results;
    }

    async fetchRecentEvents() {
        return await this.orm.searchRead(
            "audit.log",
            [["action", "in", ["login_failed", "unlink", "export"]]],
            ["user_id", "action", "model_name", "record_id",
             "ip_address", "timestamp", "data_classification"],
            { limit: 50, order: "timestamp desc" }
        );
    }

    async fetchFailedLogins() {
        return await this.orm.searchRead(
            "audit.log",
            [["action", "=", "login_failed"]],
            ["user_id", "ip_address", "user_agent", "timestamp"],
            { limit: 25, order: "timestamp desc" }
        );
    }

    async fetchAuditTrail() {
        return await this.orm.searchRead(
            "audit.log",
            [],
            ["user_id", "action", "model_name", "record_display",
             "changed_fields", "ip_address", "timestamp"],
            { limit: 100, order: "timestamp desc" }
        );
    }

    onTabChange(tab) {
        this.state.selectedTab = tab;
    }

    onDateRangeChange(range) {
        this.state.dateRange = range;
        this.loadDashboardData();
    }

    getSeverityClass(event) {
        if (event.action === "login_failed") return "sd-severity-high";
        if (event.data_classification === "restricted") return "sd-severity-critical";
        if (event.action === "unlink") return "sd-severity-medium";
        return "sd-severity-low";
    }

    formatTimestamp(ts) {
        return formatDateTime(ts);
    }

    async onViewAuditDetail(recordId) {
        await this.action.doAction({
            type: "ir.actions.act_window",
            res_model: "audit.log",
            res_id: recordId,
            views: [[false, "form"]],
            target: "new",
        });
    }

    async onExportAuditLog() {
        await this.action.doAction({
            type: "ir.actions.act_url",
            url: "/api/v1/security/audit/export?range=" + this.state.dateRange,
        });
    }
}

registry.category("actions").add(
    "smart_dairy_security.security_dashboard",
    SecurityDashboard
);
```

```xml
<?xml version="1.0" encoding="utf-8"?>
<!-- addons/smart_dairy_security/static/src/components/security_dashboard/security_dashboard.xml -->
<templates xml:space="preserve">
    <t t-name="smart_dairy_security.SecurityDashboard">
        <div class="sd-security-dashboard o_action">
            <div class="sd-dashboard-header">
                <h2>Security Dashboard</h2>
                <div class="sd-date-filter">
                    <button t-att-class="state.dateRange === 'today' ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                            t-on-click="() => this.onDateRangeChange('today')">Today</button>
                    <button t-att-class="state.dateRange === 'week' ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                            t-on-click="() => this.onDateRangeChange('week')">This Week</button>
                    <button t-att-class="state.dateRange === 'month' ? 'btn btn-primary' : 'btn btn-outline-secondary'"
                            t-on-click="() => this.onDateRangeChange('month')">This Month</button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="sd-summary-cards row" t-if="!state.loading">
                <div class="col-md-3">
                    <div class="sd-card sd-card-info">
                        <span class="sd-card-value" t-esc="state.summary.totalEvents"/>
                        <span class="sd-card-label">Total Events</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sd-card sd-card-danger">
                        <span class="sd-card-value" t-esc="state.summary.failedLogins"/>
                        <span class="sd-card-label">Failed Logins</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sd-card sd-card-warning">
                        <span class="sd-card-value" t-esc="state.summary.criticalAlerts"/>
                        <span class="sd-card-label">Critical Alerts</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sd-card sd-card-success">
                        <span class="sd-card-value" t-esc="state.summary.activeUsers"/>
                        <span class="sd-card-label">Active Users</span>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs sd-tabs">
                <li class="nav-item">
                    <a t-att-class="state.selectedTab === 'overview' ? 'nav-link active' : 'nav-link'"
                       t-on-click="() => this.onTabChange('overview')">Overview</a>
                </li>
                <li class="nav-item">
                    <a t-att-class="state.selectedTab === 'failed_logins' ? 'nav-link active' : 'nav-link'"
                       t-on-click="() => this.onTabChange('failed_logins')">Failed Logins</a>
                </li>
                <li class="nav-item">
                    <a t-att-class="state.selectedTab === 'audit' ? 'nav-link active' : 'nav-link'"
                       t-on-click="() => this.onTabChange('audit')">Audit Trail</a>
                </li>
            </ul>

            <!-- Audit Trail Tab -->
            <div t-if="state.selectedTab === 'audit'" class="sd-audit-trail">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Timestamp</th><th>User</th><th>Action</th>
                            <th>Model</th><th>Record</th><th>Fields</th><th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <t t-foreach="state.auditTrail" t-as="entry" t-key="entry.id">
                            <tr t-on-click="() => this.onViewAuditDetail(entry.id)"
                                class="sd-clickable-row">
                                <td t-esc="formatTimestamp(entry.timestamp)"/>
                                <td t-esc="entry.user_id[1]"/>
                                <td><span t-att-class="'badge sd-badge-' + entry.action"
                                          t-esc="entry.action"/></td>
                                <td t-esc="entry.model_name"/>
                                <td t-esc="entry.record_display"/>
                                <td t-esc="entry.changed_fields"/>
                                <td t-esc="entry.ip_address"/>
                            </tr>
                        </t>
                    </tbody>
                </table>
                <button class="btn btn-outline-primary mt-2" t-on-click="onExportAuditLog">
                    Export Audit Log
                </button>
            </div>

            <!-- Loading State -->
            <div t-if="state.loading" class="sd-loading text-center p-5">
                <div class="spinner-border" role="status"/>
                <p>Loading security data...</p>
            </div>
        </div>
    </t>
</templates>
```

**End-of-Day 59 Deliverables:**
- [x] Security policy document
- [x] Data handling and encryption procedures
- [x] OWL SecurityDashboard component (overview, failed logins, audit trail)
- [x] Secure coding guidelines
- [x] Compliance checklist for Bangladesh ICT Act and DSA

---

### Day 60 -- Milestone 6 Review & Security Architecture Sign-Off

**Objective:** Conduct milestone review, present threat model, obtain sign-off on security architecture, and prepare handoff to Milestone 7.

#### Dev 1 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Run final security test suite (SAST + unit)   | 2h    |
| 2 | Verify encryption round-trip on all PII fields | 1.5h  |
| 3 | Validate audit log integrity (hash chain)      | 1.5h  |
| 4 | Present threat model and RBAC design           | 3h    |

#### Dev 2 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Execute ZAP baseline scan and review results   | 2h    |
| 2 | Verify firewall rules and TLS configuration    | 2h    |
| 3 | Present IR playbook and network architecture   | 2h    |
| 4 | Document outstanding security items for M7     | 2h    |

#### Dev 3 Tasks (~8h)

| # | Task                                           | Hours |
|---|------------------------------------------------|-------|
| 1 | Demo SecurityDashboard component               | 2h    |
| 2 | Verify CSP and security headers in browser     | 2h    |
| 3 | Present frontend security review results       | 2h    |
| 4 | Prepare UI handoff documentation for M7        | 2h    |

**End-of-Day 60 Deliverables:**
- [x] Milestone 6 review meeting completed
- [x] Threat model presentation delivered and accepted
- [x] All security tests passing (SAST, unit, integration)
- [x] ZAP baseline scan report (zero high-severity findings)
- [x] Security architecture document signed off by all developers
- [x] Handoff document for Milestone 7 prepared

---

## 4. Technical Specifications

### 4.1 Encryption Standards

| Layer          | Algorithm     | Key Size | Implementation              |
| -------------- | ------------- | -------- | --------------------------- |
| Transport      | TLS 1.3       | 256-bit  | Nginx + Let's Encrypt       |
| Data at Rest   | AES-256-GCM   | 256-bit  | PostgreSQL TDE              |
| PII Fields     | Fernet        | 256-bit  | Python cryptography library  |
| Password Hash  | bcrypt        | 12 rounds| Odoo default (passlib)      |
| Audit Integrity| SHA-256       | 256-bit  | Hash chain in audit.log     |
| Key Management | Vault Transit | 4096-bit | HashiCorp Vault             |

### 4.2 RBAC Role Hierarchy

```
Super Admin
  |-- Farm Manager
  |     |-- Milk Collector
  |     |-- Veterinarian
  |-- Accountant
  |-- HR Manager
  |
Customer (independent branch)
Public (base, no auth required)
```

### 4.3 Network Architecture

```
Internet
   |
   v
[Nginx (DMZ)] -- TLS 1.3 termination, WAF, rate limiting
   |
   +---> [Odoo 19 CE]  -- port 8069 (Docker internal only)
   |        |
   |        +---> [PostgreSQL 16] -- port 5432 (Docker internal, SSL)
   |        +---> [Redis 7]       -- port 6379 (Docker internal, ACL)
   |
   +---> [FastAPI]      -- port 8000 (Docker internal only)
   |
   +---> [HashiCorp Vault] -- port 8200 (Docker internal only)
```

### 4.4 Compliance Mapping

| Regulation                      | Requirement                          | Smart Dairy Control                    |
| ------------------------------- | ------------------------------------ | -------------------------------------- |
| Bangladesh ICT Act 2006 S.54    | Unauthorized computer access         | RBAC, firewall, audit logging          |
| Bangladesh ICT Act 2006 S.56    | Data damage or disruption            | Backups, IR playbook, hash chain audit |
| Digital Security Act 2018 S.15  | Unauthorized access to critical info | Fernet PII encryption, Vault keys      |
| Digital Security Act 2018 S.26  | Identity fraud                       | bcrypt hashing, session management     |
| Digital Security Act 2018 S.34  | Hacking and system intrusion         | nftables firewall, fail2ban, ZAP scans |
| GDPR-like (data protection)     | Data minimization, consent, erasure  | Data classification, audit trail       |
| GDPR-like (breach notification) | 72-hour notification                 | IR playbook phase 5, BTRC contact      |

---

## 5. Testing & Validation

### 5.1 Security Testing Matrix

| Test Type              | Tool                | Frequency      | Threshold                  |
| ---------------------- | ------------------- | -------------- | -------------------------- |
| Static Analysis (SAST) | Bandit + Semgrep    | Every commit   | Zero HIGH severity         |
| Dynamic Analysis (DAST)| OWASP ZAP           | Weekly         | Zero HIGH/CRITICAL alerts  |
| Dependency Scanning    | safety / pip-audit  | Every build    | Zero known vulnerabilities |
| Penetration Testing    | Manual              | Quarterly      | No exploitable findings    |
| Encryption Validation  | Custom test suite   | Every release  | All PII fields encrypted   |
| RBAC Validation        | Unit tests          | Every commit   | All 8 roles, 9 perm types  |
| Audit Integrity        | Hash chain verifier | Daily (cron)   | Zero chain breaks          |
| TLS Verification       | testssl.sh          | Weekly         | Grade A+ equivalent        |

### 5.2 Acceptance Criteria

| ID   | Criterion                                                    | Status  |
| ---- | ------------------------------------------------------------ | ------- |
| AC-1 | STRIDE threat model covers all 6 asset categories            | Pending |
| AC-2 | 8 RBAC roles configured with correct permission inheritance  | Pending |
| AC-3 | 9 permission types enforced across all protected resources   | Pending |
| AC-4 | Fernet encryption encrypts/decrypts all PII fields correctly | Pending |
| AC-5 | TLS 1.3 enforced on all external-facing endpoints            | Pending |
| AC-6 | Firewall blocks all unauthorized ports                       | Pending |
| AC-7 | Audit log captures all CUD operations with hash chain        | Pending |
| AC-8 | Bandit scan shows zero HIGH severity findings                | Pending |
| AC-9 | ZAP baseline scan shows zero HIGH/CRITICAL alerts            | Pending |
| AC-10| IR playbook documents all 5 response phases                  | Pending |
| AC-11| Bangladesh regulatory requirements fully mapped              | Pending |
| AC-12| SecurityDashboard renders events, logins, and audit trail    | Pending |

---

## 6. Risk & Mitigation

| ID   | Risk                                          | Probability | Impact   | Mitigation                                            |
| ---- | --------------------------------------------- | ----------- | -------- | ----------------------------------------------------- |
| R-01 | Fernet key compromise                         | Low         | Critical | Vault-managed keys, rotation policy, key escrow       |
| R-02 | TLS certificate expiry causes downtime        | Medium      | High     | Certbot auto-renewal with monitoring alerts            |
| R-03 | RBAC misconfiguration grants excess privilege | Medium      | High     | Automated permission tests on every commit             |
| R-04 | Audit log storage exceeds capacity            | Medium      | Medium   | Monthly partitioning, cold storage archival            |
| R-05 | Zero-day vulnerability in dependencies        | Medium      | High     | Daily dependency scanning, rapid patching procedure    |
| R-06 | Insider threat (employee misuse)              | Low         | High     | Least-privilege RBAC, comprehensive audit trail        |
| R-07 | Bangladesh regulatory change                  | Low         | Medium   | Quarterly compliance review, modular policy framework  |
| R-08 | Vault unavailability blocks application start | Low         | Critical | Vault HA cluster, cached credential fallback (1 hour) |
| R-09 | DDoS attack overwhelms Nginx                  | Medium      | High     | Rate limiting, connection limits, CDN consideration    |
| R-10 | Incomplete audit trail gaps                   | Low         | High     | AuditMixin on all models, daily integrity check        |

---

## 7. Dependencies & Handoffs

### 7.1 Upstream Dependencies (from previous milestones)

| Dependency                     | Source Milestone | Status  |
| ------------------------------ | ---------------- | ------- |
| Docker environment operational | Milestone 1      | Done    |
| PostgreSQL 16 configured       | Milestone 5      | Done    |
| Redis 7 configured             | Milestone 5      | Done    |
| Odoo 19 CE base modules        | Milestone 2      | Done    |
| CI/CD pipeline operational     | Milestone 3      | Done    |
| Database schema finalized      | Milestone 5      | Done    |

### 7.2 Downstream Handoffs (to future milestones)

| Deliverable                    | Target Milestone | Handoff Date |
| ------------------------------ | ---------------- | ------------ |
| RBAC groups for module access  | Milestone 7      | Day 60       |
| Encryption utilities for PII   | Milestone 7      | Day 60       |
| Audit mixin for all models     | Milestone 7      | Day 60       |
| Security headers config        | Milestone 8      | Day 60       |
| SecurityDashboard component    | Milestone 9      | Day 60       |
| IR playbook for ops team       | Milestone 10     | Day 60       |

### 7.3 Milestone 6 Completion Checklist

- [ ] STRIDE threat model document complete and reviewed
- [ ] 8 RBAC roles implemented in Odoo security groups XML
- [ ] Permission matrix (9 types) implemented and tested
- [ ] Fernet encryption utility operational with key rotation
- [ ] HashiCorp Vault integration tested (secrets + dynamic DB creds)
- [ ] Nginx hardened (TLS 1.3, HSTS, CSP, rate limiting)
- [ ] nftables firewall rules deployed and validated
- [ ] AuditMixin integrated with hash-chain tamper detection
- [ ] audit.log schema created with performance indexes
- [ ] TLS certificate automation (Let's Encrypt + certbot)
- [ ] Bandit SAST configured and passing
- [ ] OWASP ZAP baseline scan configured
- [ ] Incident response playbook authored (F-011)
- [ ] FastAPI security headers middleware deployed
- [ ] Data classification matrix defined (4 levels)
- [ ] OWL SecurityDashboard component functional
- [ ] Bangladesh ICT Act / DSA compliance mapped
- [ ] All acceptance criteria verified
- [ ] Handoff documentation prepared for Milestone 7

---

*End of Milestone 6: Security Architecture Design & Foundation*
