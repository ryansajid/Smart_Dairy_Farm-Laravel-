# Milestone 142: Data Migration & Validation

## Smart Dairy Digital Smart Portal + ERP — Phase 15: Deployment & Handover

| Field                | Detail                                                    |
|----------------------|-----------------------------------------------------------|
| **Milestone**        | 142 of 150                                                |
| **Title**            | Data Migration & Validation                               |
| **Phase**            | Phase 15 — Deployment & Handover (Part A: Deployment)     |
| **Days**             | Days 706–710 (of 750)                                     |
| **Duration**         | 5 working days                                            |
| **Version**          | 1.0                                                       |
| **Status**           | Draft                                                     |
| **Authors**          | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated**     | 2026-02-05                                                |

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

Execute comprehensive data migration from legacy systems to the Smart Dairy production platform with 100% data integrity. By Day 710, all master data, transactional data (2-year history), and farm records must be migrated, validated, reconciled, and signed off with zero data loss.

### 1.2 Objectives

1. Analyze and document legacy system data structures
2. Develop ETL scripts using Python/Odoo migration framework
3. Migrate master data (products, customers, suppliers, chart of accounts)
4. Migrate transactional data (sales, purchases, inventory movements - 2 years)
5. Migrate farm records (cattle profiles, health records, milk production)
6. Execute data validation with checksum verification
7. Perform reconciliation between legacy and new system
8. Document and execute rollback procedures
9. Obtain data migration sign-off from business owner
10. Archive legacy data with audit trail

### 1.3 Key Deliverables

| # | Deliverable | Owner | Format |
|---|-------------|-------|--------|
| 1 | Data mapping document | Dev 1 | Excel/Markdown |
| 2 | ETL scripts (master data) | Dev 1 | Python |
| 3 | ETL scripts (transactions) | Dev 1 | Python |
| 4 | ETL scripts (farm records) | Dev 1 | Python |
| 5 | Data validation queries | Dev 1 | SQL |
| 6 | Reconciliation report | Dev 1 | Excel |
| 7 | Rollback procedures | Dev 2 | Markdown/Scripts |
| 8 | Migration execution log | Dev 2 | Markdown |
| 9 | Data migration sign-off form | All | PDF |
| 10 | Legacy data archive | Dev 3 | Encrypted backup |

### 1.4 Prerequisites

- Milestone 141 complete (production infrastructure ready)
- Legacy system data export completed
- Database connectivity verified
- Rollback window defined (business approval for 30-min recovery)

### 1.5 Success Criteria

- [ ] 100% master data migrated with zero duplicates
- [ ] All transactional data migrated with referential integrity
- [ ] Farm records migrated with complete history
- [ ] Checksum validation passed for all tables
- [ ] Row count reconciliation passed (source = target)
- [ ] Financial balances reconciled (opening balances match)
- [ ] Rollback tested and verified (<30 min recovery)
- [ ] Business owner sign-off obtained

---

## 2. Requirement Traceability Matrix

| Req ID | Source | Requirement Description | Day(s) | Task Reference |
|--------|--------|-------------------------|--------|----------------|
| NFR-DATA-01 | BRD §7 | Zero data loss during migration | 706-710 | All ETL scripts |
| NFR-DATA-02 | BRD §7 | Data integrity validation 100% | 709-710 | Validation queries |
| SRS §7.1 | SRS | Master data migration | 707 | ETL master data |
| SRS §7.2 | SRS | Transaction data migration | 708 | ETL transactions |
| SRS §7.3 | SRS | Farm records migration | 709 | ETL farm data |
| D-011 §1-3 | Impl Guide | Data Migration Strategy | 706-710 | Full milestone |
| BRD §4.2 | BRD | Opening balance setup | 708 | Balance migration |

---

## 3. Day-by-Day Breakdown

---

### Day 706 — Migration Planning & Source Analysis

**Objective:** Analyze legacy system data structures, create detailed mapping documents, and set up ETL infrastructure.

---

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Analyze legacy database schema — 2.5h
2. Create source-to-target data mapping — 3h
3. Define data transformation rules — 1.5h
4. Set up ETL project structure — 1h

**Code: ETL Project Structure**

```
smart_dairy_migration/
├── config/
│   ├── __init__.py
│   ├── settings.py           # Environment configuration
│   ├── source_db.py          # Legacy DB connection
│   └── target_db.py          # Production DB connection
├── models/
│   ├── __init__.py
│   ├── source/               # Legacy data models
│   │   ├── customers.py
│   │   ├── products.py
│   │   ├── transactions.py
│   │   └── farm_records.py
│   └── target/               # Odoo target models
│       ├── res_partner.py
│       ├── product_template.py
│       ├── account_move.py
│       └── smart_farm.py
├── etl/
│   ├── __init__.py
│   ├── base.py               # Base ETL class
│   ├── extractors/           # Data extraction
│   ├── transformers/         # Data transformation
│   └── loaders/              # Data loading
├── migrations/
│   ├── 001_master_data.py
│   ├── 002_chart_of_accounts.py
│   ├── 003_customers_suppliers.py
│   ├── 004_products.py
│   ├── 005_transactions.py
│   └── 006_farm_records.py
├── validation/
│   ├── __init__.py
│   ├── checksums.py
│   ├── row_counts.py
│   └── reconciliation.py
├── rollback/
│   ├── __init__.py
│   └── procedures.py
├── reports/
│   ├── __init__.py
│   └── templates/
├── tests/
│   ├── test_extractors.py
│   ├── test_transformers.py
│   └── test_validators.py
├── scripts/
│   ├── run_migration.py
│   ├── validate_migration.py
│   └── rollback_migration.py
├── requirements.txt
└── README.md
```

**Code: Base ETL Class**

```python
# smart_dairy_migration/etl/base.py

import logging
from abc import ABC, abstractmethod
from datetime import datetime
from typing import Any, Dict, List, Optional
import hashlib
import json

import psycopg2
from psycopg2.extras import RealDictCursor
import xmlrpc.client

from config.settings import (
    SOURCE_DB_CONFIG,
    TARGET_DB_CONFIG,
    ODOO_URL,
    ODOO_DB,
    ODOO_USERNAME,
    ODOO_PASSWORD,
)

logger = logging.getLogger(__name__)


class MigrationStats:
    """Track migration statistics."""

    def __init__(self, migration_name: str):
        self.migration_name = migration_name
        self.start_time = None
        self.end_time = None
        self.source_count = 0
        self.target_count = 0
        self.success_count = 0
        self.error_count = 0
        self.skip_count = 0
        self.errors: List[Dict[str, Any]] = []

    def start(self):
        self.start_time = datetime.now()
        logger.info(f"Starting migration: {self.migration_name}")

    def end(self):
        self.end_time = datetime.now()
        duration = (self.end_time - self.start_time).total_seconds()
        logger.info(
            f"Completed migration: {self.migration_name} "
            f"in {duration:.2f}s - "
            f"Success: {self.success_count}, "
            f"Errors: {self.error_count}, "
            f"Skipped: {self.skip_count}"
        )

    def record_error(self, record_id: Any, error: str):
        self.error_count += 1
        self.errors.append({
            "record_id": record_id,
            "error": error,
            "timestamp": datetime.now().isoformat()
        })

    def to_dict(self) -> Dict[str, Any]:
        return {
            "migration_name": self.migration_name,
            "start_time": self.start_time.isoformat() if self.start_time else None,
            "end_time": self.end_time.isoformat() if self.end_time else None,
            "duration_seconds": (
                (self.end_time - self.start_time).total_seconds()
                if self.end_time and self.start_time else None
            ),
            "source_count": self.source_count,
            "target_count": self.target_count,
            "success_count": self.success_count,
            "error_count": self.error_count,
            "skip_count": self.skip_count,
            "success_rate": (
                f"{(self.success_count / self.source_count * 100):.2f}%"
                if self.source_count > 0 else "N/A"
            ),
            "errors": self.errors[:100]  # Limit to first 100 errors
        }


class BaseETL(ABC):
    """Base class for ETL operations."""

    def __init__(self, batch_size: int = 1000, dry_run: bool = False):
        self.batch_size = batch_size
        self.dry_run = dry_run
        self.stats = MigrationStats(self.__class__.__name__)
        self._source_conn = None
        self._odoo_models = None
        self._odoo_uid = None

    @property
    def source_conn(self):
        """Lazy connection to source database."""
        if self._source_conn is None:
            self._source_conn = psycopg2.connect(
                **SOURCE_DB_CONFIG,
                cursor_factory=RealDictCursor
            )
        return self._source_conn

    @property
    def odoo(self):
        """Lazy connection to Odoo via XML-RPC."""
        if self._odoo_models is None:
            common = xmlrpc.client.ServerProxy(f'{ODOO_URL}/xmlrpc/2/common')
            self._odoo_uid = common.authenticate(
                ODOO_DB, ODOO_USERNAME, ODOO_PASSWORD, {}
            )
            self._odoo_models = xmlrpc.client.ServerProxy(
                f'{ODOO_URL}/xmlrpc/2/object'
            )
        return self._odoo_models

    def odoo_execute(self, model: str, method: str, *args, **kwargs):
        """Execute Odoo method via XML-RPC."""
        return self.odoo.execute_kw(
            ODOO_DB,
            self._odoo_uid,
            ODOO_PASSWORD,
            model,
            method,
            args,
            kwargs
        )

    @abstractmethod
    def extract(self) -> List[Dict[str, Any]]:
        """Extract data from source system."""
        pass

    @abstractmethod
    def transform(self, record: Dict[str, Any]) -> Optional[Dict[str, Any]]:
        """Transform a single record to target format."""
        pass

    @abstractmethod
    def load(self, records: List[Dict[str, Any]]) -> int:
        """Load transformed records to target system."""
        pass

    def run(self) -> MigrationStats:
        """Execute the full ETL pipeline."""
        self.stats.start()

        try:
            # Extract
            logger.info("Extracting data from source...")
            source_data = self.extract()
            self.stats.source_count = len(source_data)
            logger.info(f"Extracted {len(source_data)} records")

            # Transform
            logger.info("Transforming data...")
            transformed_data = []
            for record in source_data:
                try:
                    transformed = self.transform(record)
                    if transformed:
                        transformed_data.append(transformed)
                    else:
                        self.stats.skip_count += 1
                except Exception as e:
                    self.stats.record_error(
                        record.get('id', 'unknown'),
                        str(e)
                    )
                    logger.error(f"Transform error: {e}")

            logger.info(f"Transformed {len(transformed_data)} records")

            # Load
            if not self.dry_run:
                logger.info("Loading data to target...")
                loaded_count = self.load(transformed_data)
                self.stats.success_count = loaded_count
                self.stats.target_count = loaded_count
                logger.info(f"Loaded {loaded_count} records")
            else:
                logger.info("DRY RUN - Skipping load phase")
                self.stats.success_count = len(transformed_data)

        except Exception as e:
            logger.exception(f"Migration failed: {e}")
            raise

        finally:
            self.stats.end()
            self._cleanup()

        return self.stats

    def _cleanup(self):
        """Clean up resources."""
        if self._source_conn:
            self._source_conn.close()

    @staticmethod
    def compute_checksum(data: Dict[str, Any]) -> str:
        """Compute MD5 checksum for a record."""
        json_str = json.dumps(data, sort_keys=True, default=str)
        return hashlib.md5(json_str.encode()).hexdigest()


class BatchLoader:
    """Helper class for batch loading to Odoo."""

    def __init__(self, etl: BaseETL, model: str, batch_size: int = 500):
        self.etl = etl
        self.model = model
        self.batch_size = batch_size
        self.loaded_count = 0

    def load_batch(self, records: List[Dict[str, Any]]) -> int:
        """Load records in batches."""
        for i in range(0, len(records), self.batch_size):
            batch = records[i:i + self.batch_size]
            try:
                ids = self.etl.odoo_execute(
                    self.model,
                    'create',
                    batch
                )
                self.loaded_count += len(ids) if isinstance(ids, list) else 1
                logger.info(
                    f"Loaded batch {i//self.batch_size + 1}: "
                    f"{len(batch)} records"
                )
            except Exception as e:
                logger.error(f"Batch load error: {e}")
                # Try one by one
                for record in batch:
                    try:
                        self.etl.odoo_execute(self.model, 'create', [record])
                        self.loaded_count += 1
                    except Exception as e2:
                        self.etl.stats.record_error(
                            record.get('legacy_id', 'unknown'),
                            str(e2)
                        )

        return self.loaded_count
```

**Code: Data Mapping Document Template**

```markdown
# Smart Dairy Data Migration Mapping

## Source System: Legacy Dairy Management System

### 1. Customer/Supplier Mapping

| Source Field | Source Type | Target Model | Target Field | Transformation |
|--------------|-------------|--------------|--------------|----------------|
| customer_id | INT | res.partner | x_legacy_id | Direct copy |
| customer_name | VARCHAR(200) | res.partner | name | Trim whitespace |
| phone | VARCHAR(20) | res.partner | phone | Format: +880XXXXXXXXXX |
| email | VARCHAR(100) | res.partner | email | Lowercase, validate |
| address | TEXT | res.partner | street | Split into street/city |
| is_supplier | BOOLEAN | res.partner | supplier_rank | 1 if True, 0 if False |
| is_customer | BOOLEAN | res.partner | customer_rank | 1 if True, 0 if False |
| credit_limit | DECIMAL | res.partner | credit_limit | Convert to BDT |
| created_date | DATETIME | res.partner | create_date | Convert timezone |

### 2. Product Mapping

| Source Field | Source Type | Target Model | Target Field | Transformation |
|--------------|-------------|--------------|--------------|----------------|
| product_id | INT | product.template | x_legacy_id | Direct copy |
| product_name | VARCHAR(200) | product.template | name | Trim whitespace |
| sku | VARCHAR(50) | product.template | default_code | Uppercase |
| category | VARCHAR(100) | product.template | categ_id | Lookup category ID |
| unit_price | DECIMAL | product.template | list_price | Convert to BDT |
| cost_price | DECIMAL | product.template | standard_price | Convert to BDT |
| quantity | DECIMAL | stock.quant | quantity | Direct copy |
| uom | VARCHAR(20) | product.template | uom_id | Map to Odoo UOM |

### 3. Cattle Registry Mapping

| Source Field | Source Type | Target Model | Target Field | Transformation |
|--------------|-------------|--------------|--------------|----------------|
| cattle_id | INT | smart.cattle | x_legacy_id | Direct copy |
| tag_number | VARCHAR(20) | smart.cattle | tag_number | Uppercase |
| name | VARCHAR(100) | smart.cattle | name | Trim whitespace |
| breed | VARCHAR(50) | smart.cattle | breed_id | Lookup breed ID |
| date_of_birth | DATE | smart.cattle | date_of_birth | Direct copy |
| gender | CHAR(1) | smart.cattle | gender | Map: M->male, F->female |
| mother_id | INT | smart.cattle | mother_id | Lookup migrated ID |
| father_id | INT | smart.cattle | father_id | Lookup migrated ID |
| status | VARCHAR(20) | smart.cattle | state | Map to state codes |
```

**End-of-Day 706 Deliverables (Dev 1):**

- [ ] Legacy database schema analyzed
- [ ] Source-to-target mapping document complete
- [ ] Data transformation rules defined
- [ ] ETL project structure created

---

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Set up migration environment (Docker) — 2h
2. Configure database connectivity — 2h
3. Create migration job runner (Kubernetes) — 2h
4. Develop rollback script framework — 2h

**Code: Migration Kubernetes Job**

```yaml
# kubernetes/migration/migration-job.yaml
apiVersion: batch/v1
kind: Job
metadata:
  name: smart-dairy-migration
  namespace: smart-dairy-prod
spec:
  ttlSecondsAfterFinished: 86400  # Clean up after 24 hours
  backoffLimit: 1
  template:
    metadata:
      labels:
        app: migration
    spec:
      restartPolicy: Never
      serviceAccountName: smart-dairy-migration

      containers:
        - name: migration
          image: ${ECR_REGISTRY}/smart-dairy-migration:${IMAGE_TAG}
          imagePullPolicy: Always

          command: ["python", "scripts/run_migration.py"]

          args:
            - "--phase"
            - "all"
            - "--batch-size"
            - "1000"
            - "--log-level"
            - "INFO"

          envFrom:
            - secretRef:
                name: migration-secrets
            - configMapRef:
                name: migration-config

          resources:
            requests:
              cpu: 2000m
              memory: 4Gi
            limits:
              cpu: 4000m
              memory: 8Gi

          volumeMounts:
            - name: migration-data
              mountPath: /app/data
            - name: migration-logs
              mountPath: /app/logs

      volumes:
        - name: migration-data
          persistentVolumeClaim:
            claimName: migration-data-pvc
        - name: migration-logs
          emptyDir: {}

---
apiVersion: v1
kind: ConfigMap
metadata:
  name: migration-config
  namespace: smart-dairy-prod
data:
  MIGRATION_PHASE: "all"
  BATCH_SIZE: "1000"
  DRY_RUN: "false"
  LOG_LEVEL: "INFO"
  VALIDATE_AFTER_LOAD: "true"
```

**Code: Rollback Script**

```python
# smart_dairy_migration/scripts/rollback_migration.py

#!/usr/bin/env python3
"""
Smart Dairy Migration Rollback Script

This script performs a complete rollback of migrated data.
Use only in case of critical migration failure.

DANGER: This will DELETE all migrated data from production!
"""

import argparse
import logging
import sys
from datetime import datetime
from typing import List, Tuple

import psycopg2
from psycopg2 import sql

from config.settings import TARGET_DB_CONFIG

logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)


# Tables to rollback in reverse dependency order
ROLLBACK_ORDER: List[Tuple[str, str]] = [
    # (table_name, where_clause)
    ("account_move_line", "x_migrated = true"),
    ("account_move", "x_migrated = true"),
    ("stock_move_line", "x_migrated = true"),
    ("stock_move", "x_migrated = true"),
    ("stock_picking", "x_migrated = true"),
    ("sale_order_line", "order_id IN (SELECT id FROM sale_order WHERE x_migrated = true)"),
    ("sale_order", "x_migrated = true"),
    ("purchase_order_line", "order_id IN (SELECT id FROM purchase_order WHERE x_migrated = true)"),
    ("purchase_order", "x_migrated = true"),
    ("smart_milk_production", "x_migrated = true"),
    ("smart_cattle_health", "x_migrated = true"),
    ("smart_cattle", "x_migrated = true"),
    ("product_product", "x_migrated = true"),
    ("product_template", "x_migrated = true"),
    ("res_partner", "x_migrated = true AND id > 1"),  # Keep admin partner
]


def confirm_rollback() -> bool:
    """Confirm rollback with user."""
    print("\n" + "=" * 60)
    print("WARNING: MIGRATION ROLLBACK")
    print("=" * 60)
    print("\nThis will DELETE all migrated data from production!")
    print("This action CANNOT be undone.")
    print("\nAffected tables:")
    for table, _ in ROLLBACK_ORDER:
        print(f"  - {table}")
    print()

    confirmation = input("Type 'ROLLBACK' to confirm: ")
    return confirmation == "ROLLBACK"


def get_rollback_counts(conn) -> dict:
    """Get counts of records to be deleted."""
    counts = {}
    with conn.cursor() as cur:
        for table, where_clause in ROLLBACK_ORDER:
            try:
                cur.execute(
                    sql.SQL("SELECT COUNT(*) FROM {} WHERE {}").format(
                        sql.Identifier(table),
                        sql.SQL(where_clause)
                    )
                )
                counts[table] = cur.fetchone()[0]
            except Exception as e:
                logger.warning(f"Could not count {table}: {e}")
                counts[table] = "unknown"
    return counts


def execute_rollback(conn, dry_run: bool = False) -> dict:
    """Execute the rollback."""
    results = {}
    start_time = datetime.now()

    with conn.cursor() as cur:
        for table, where_clause in ROLLBACK_ORDER:
            try:
                logger.info(f"Rolling back {table}...")

                if dry_run:
                    cur.execute(
                        sql.SQL("SELECT COUNT(*) FROM {} WHERE {}").format(
                            sql.Identifier(table),
                            sql.SQL(where_clause)
                        )
                    )
                    count = cur.fetchone()[0]
                    results[table] = {"status": "DRY_RUN", "count": count}
                    logger.info(f"  DRY RUN: Would delete {count} records")
                else:
                    cur.execute(
                        sql.SQL("DELETE FROM {} WHERE {}").format(
                            sql.Identifier(table),
                            sql.SQL(where_clause)
                        )
                    )
                    count = cur.rowcount
                    results[table] = {"status": "SUCCESS", "count": count}
                    logger.info(f"  Deleted {count} records")

            except Exception as e:
                logger.error(f"  Error rolling back {table}: {e}")
                results[table] = {"status": "ERROR", "error": str(e)}
                conn.rollback()
                raise

        if not dry_run:
            conn.commit()
            logger.info("Rollback committed successfully")

    end_time = datetime.now()
    duration = (end_time - start_time).total_seconds()

    return {
        "results": results,
        "duration_seconds": duration,
        "dry_run": dry_run
    }


def main():
    parser = argparse.ArgumentParser(
        description="Smart Dairy Migration Rollback"
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Simulate rollback without deleting data"
    )
    parser.add_argument(
        "--force",
        action="store_true",
        help="Skip confirmation prompt"
    )

    args = parser.parse_args()

    # Confirm unless forced
    if not args.force and not args.dry_run:
        if not confirm_rollback():
            logger.info("Rollback cancelled by user")
            sys.exit(0)

    try:
        conn = psycopg2.connect(**TARGET_DB_CONFIG)

        # Show what will be rolled back
        logger.info("Checking records to rollback...")
        counts = get_rollback_counts(conn)
        for table, count in counts.items():
            logger.info(f"  {table}: {count} records")

        # Execute rollback
        if args.dry_run:
            logger.info("\n=== DRY RUN MODE ===\n")

        results = execute_rollback(conn, dry_run=args.dry_run)

        # Print summary
        print("\n" + "=" * 60)
        print("ROLLBACK SUMMARY")
        print("=" * 60)
        for table, result in results["results"].items():
            status = result.get("status", "UNKNOWN")
            count = result.get("count", "N/A")
            print(f"  {table}: {status} ({count} records)")
        print(f"\nDuration: {results['duration_seconds']:.2f} seconds")
        print("=" * 60)

    except Exception as e:
        logger.exception(f"Rollback failed: {e}")
        sys.exit(1)
    finally:
        if conn:
            conn.close()


if __name__ == "__main__":
    main()
```

**End-of-Day 706 Deliverables (Dev 2):**

- [ ] Migration Docker environment configured
- [ ] Database connectivity established
- [ ] Kubernetes migration job created
- [ ] Rollback script framework developed

---

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**

1. Create migration tracking dashboard — 3h
2. Document migration procedures — 3h
3. Set up legacy data archival process — 2h

**End-of-Day 706 Deliverables (Dev 3):**

- [ ] Migration tracking dashboard created
- [ ] Migration procedures documented
- [ ] Legacy archival process defined

---

### Day 707 — Master Data Migration

**Objective:** Migrate all master data including chart of accounts, customers, suppliers, and products.

---

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Migrate chart of accounts — 2h
2. Migrate customers and suppliers — 3h
3. Migrate products and categories — 2h
4. Validate master data integrity — 1h

**Code: Customer/Supplier Migration**

```python
# smart_dairy_migration/migrations/003_customers_suppliers.py

"""
Customer and Supplier Migration

Migrates all customers and suppliers from legacy system to Odoo res.partner.
"""

import logging
from typing import Any, Dict, List, Optional

from etl.base import BaseETL, BatchLoader

logger = logging.getLogger(__name__)


class CustomerSupplierMigration(BaseETL):
    """Migrate customers and suppliers to res.partner."""

    QUERY = """
        SELECT
            c.customer_id,
            c.customer_name,
            c.customer_type,
            c.phone,
            c.mobile,
            c.email,
            c.address,
            c.city,
            c.postal_code,
            c.nid_number,
            c.tin_number,
            c.credit_limit,
            c.is_supplier,
            c.is_customer,
            c.active,
            c.created_date,
            c.notes
        FROM legacy_customers c
        WHERE c.active = true
        ORDER BY c.customer_id
    """

    def extract(self) -> List[Dict[str, Any]]:
        """Extract customers from legacy system."""
        with self.source_conn.cursor() as cur:
            cur.execute(self.QUERY)
            return cur.fetchall()

    def transform(self, record: Dict[str, Any]) -> Optional[Dict[str, Any]]:
        """Transform legacy customer to Odoo res.partner format."""
        # Skip if no name
        if not record.get('customer_name'):
            logger.warning(f"Skipping customer {record['customer_id']}: no name")
            return None

        # Determine partner type
        is_company = record.get('customer_type') == 'company'

        # Format phone number
        phone = self._format_phone(record.get('phone'))
        mobile = self._format_phone(record.get('mobile'))

        # Build address
        street = record.get('address', '')
        city = record.get('city', '')

        # Determine ranks
        customer_rank = 1 if record.get('is_customer') else 0
        supplier_rank = 1 if record.get('is_supplier') else 0

        return {
            'name': record['customer_name'].strip(),
            'is_company': is_company,
            'phone': phone,
            'mobile': mobile,
            'email': record.get('email', '').lower().strip() if record.get('email') else False,
            'street': street,
            'city': city,
            'zip': record.get('postal_code', ''),
            'country_id': self._get_bangladesh_country_id(),
            'vat': record.get('tin_number', ''),
            'credit_limit': float(record.get('credit_limit', 0) or 0),
            'customer_rank': customer_rank,
            'supplier_rank': supplier_rank,
            'active': True,
            'comment': record.get('notes', ''),
            # Custom fields
            'x_legacy_id': record['customer_id'],
            'x_nid_number': record.get('nid_number', ''),
            'x_migrated': True,
            'x_migration_date': self._get_current_datetime(),
        }

    def load(self, records: List[Dict[str, Any]]) -> int:
        """Load partners to Odoo."""
        loader = BatchLoader(self, 'res.partner', batch_size=500)
        return loader.load_batch(records)

    def _format_phone(self, phone: Optional[str]) -> str:
        """Format phone number to Bangladesh format."""
        if not phone:
            return ''

        # Remove non-digits
        digits = ''.join(filter(str.isdigit, phone))

        # Format as +880XXXXXXXXXX
        if len(digits) == 11 and digits.startswith('0'):
            return '+880' + digits[1:]
        elif len(digits) == 10:
            return '+880' + digits
        elif len(digits) == 13 and digits.startswith('880'):
            return '+' + digits
        else:
            return phone  # Return as-is if can't format

    def _get_bangladesh_country_id(self) -> int:
        """Get Bangladesh country ID from Odoo."""
        result = self.odoo_execute(
            'res.country',
            'search',
            [('code', '=', 'BD')],
            {'limit': 1}
        )
        return result[0] if result else 1

    def _get_current_datetime(self) -> str:
        """Get current datetime in Odoo format."""
        from datetime import datetime
        return datetime.now().strftime('%Y-%m-%d %H:%M:%S')


if __name__ == "__main__":
    import sys
    logging.basicConfig(level=logging.INFO)

    dry_run = '--dry-run' in sys.argv

    migration = CustomerSupplierMigration(
        batch_size=500,
        dry_run=dry_run
    )
    stats = migration.run()
    print(stats.to_dict())
```

**Code: Product Migration**

```python
# smart_dairy_migration/migrations/004_products.py

"""
Product Migration

Migrates products and product categories from legacy system to Odoo.
"""

import logging
from typing import Any, Dict, List, Optional

from etl.base import BaseETL, BatchLoader

logger = logging.getLogger(__name__)


class ProductMigration(BaseETL):
    """Migrate products to product.template and product.product."""

    CATEGORY_QUERY = """
        SELECT DISTINCT
            category_id,
            category_name,
            parent_category_id
        FROM legacy_product_categories
        ORDER BY parent_category_id NULLS FIRST, category_id
    """

    PRODUCT_QUERY = """
        SELECT
            p.product_id,
            p.product_name,
            p.sku,
            p.barcode,
            p.category_id,
            p.description,
            p.unit_price,
            p.cost_price,
            p.uom,
            p.weight,
            p.is_dairy_product,
            p.shelf_life_days,
            p.storage_temp,
            p.active,
            p.created_date,
            COALESCE(i.quantity, 0) as quantity_on_hand
        FROM legacy_products p
        LEFT JOIN legacy_inventory i ON p.product_id = i.product_id
        WHERE p.active = true
        ORDER BY p.product_id
    """

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.category_map = {}  # legacy_id -> odoo_id

    def extract(self) -> List[Dict[str, Any]]:
        """Extract products from legacy system."""
        # First migrate categories
        self._migrate_categories()

        # Then extract products
        with self.source_conn.cursor() as cur:
            cur.execute(self.PRODUCT_QUERY)
            return cur.fetchall()

    def _migrate_categories(self):
        """Migrate product categories first."""
        logger.info("Migrating product categories...")

        with self.source_conn.cursor() as cur:
            cur.execute(self.CATEGORY_QUERY)
            categories = cur.fetchall()

        for cat in categories:
            parent_id = False
            if cat['parent_category_id']:
                parent_id = self.category_map.get(cat['parent_category_id'], False)

            cat_data = {
                'name': cat['category_name'],
                'parent_id': parent_id,
                'x_legacy_id': cat['category_id'],
            }

            if not self.dry_run:
                result = self.odoo_execute(
                    'product.category',
                    'create',
                    [cat_data]
                )
                self.category_map[cat['category_id']] = result

        logger.info(f"Migrated {len(categories)} categories")

    def transform(self, record: Dict[str, Any]) -> Optional[Dict[str, Any]]:
        """Transform legacy product to Odoo format."""
        if not record.get('product_name'):
            return None

        # Map UOM
        uom_id = self._map_uom(record.get('uom', 'Unit'))

        # Map category
        categ_id = self.category_map.get(
            record.get('category_id'),
            self._get_default_category()
        )

        return {
            'name': record['product_name'].strip(),
            'default_code': record.get('sku', '').upper().strip(),
            'barcode': record.get('barcode', ''),
            'categ_id': categ_id,
            'description': record.get('description', ''),
            'list_price': float(record.get('unit_price', 0) or 0),
            'standard_price': float(record.get('cost_price', 0) or 0),
            'uom_id': uom_id,
            'uom_po_id': uom_id,
            'weight': float(record.get('weight', 0) or 0),
            'type': 'product',  # Storable product
            'active': True,
            # Custom dairy fields
            'x_is_dairy_product': record.get('is_dairy_product', False),
            'x_shelf_life_days': record.get('shelf_life_days', 0),
            'x_storage_temp': record.get('storage_temp', ''),
            'x_legacy_id': record['product_id'],
            'x_migrated': True,
        }

    def load(self, records: List[Dict[str, Any]]) -> int:
        """Load products to Odoo."""
        loader = BatchLoader(self, 'product.template', batch_size=200)
        return loader.load_batch(records)

    def _map_uom(self, legacy_uom: str) -> int:
        """Map legacy UOM to Odoo UOM ID."""
        uom_mapping = {
            'Unit': 'Units',
            'Liter': 'Liters',
            'KG': 'kg',
            'Gram': 'g',
            'Pack': 'Units',
            'Bottle': 'Units',
        }
        odoo_uom = uom_mapping.get(legacy_uom, 'Units')

        result = self.odoo_execute(
            'uom.uom',
            'search',
            [('name', '=', odoo_uom)],
            {'limit': 1}
        )
        return result[0] if result else 1

    def _get_default_category(self) -> int:
        """Get default product category ID."""
        result = self.odoo_execute(
            'product.category',
            'search',
            [('name', '=', 'All')],
            {'limit': 1}
        )
        return result[0] if result else 1
```

**End-of-Day 707 Deliverables (Dev 1):**

- [ ] Chart of accounts migrated
- [ ] Customers migrated (with validation)
- [ ] Suppliers migrated (with validation)
- [ ] Products migrated (with categories)
- [ ] Master data integrity validated

---

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Monitor migration job execution — 2h
2. Troubleshoot connectivity issues — 2h
3. Create incremental backup during migration — 2h
4. Document migration progress — 2h

**End-of-Day 707 Deliverables (Dev 2):**

- [ ] Migration job monitored
- [ ] Issues resolved
- [ ] Incremental backups completed
- [ ] Progress documented

---

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**

1. Validate migrated data in Odoo UI — 3h
2. Document data discrepancies — 2h
3. Update migration status dashboard — 3h

**End-of-Day 707 Deliverables (Dev 3):**

- [ ] UI validation completed
- [ ] Discrepancies documented
- [ ] Dashboard updated

---

### Day 708 — Transactional Data Migration

**Objective:** Migrate all transactional data including sales orders, purchase orders, inventory movements, and opening balances.

---

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Migrate sales orders and lines — 2.5h
2. Migrate purchase orders and lines — 2.5h
3. Migrate inventory movements — 2h
4. Set up opening balances — 1h

**Code: Sales Order Migration**

```python
# smart_dairy_migration/migrations/005_transactions.py

"""
Transaction Migration

Migrates sales orders, purchase orders, and inventory movements.
"""

import logging
from datetime import datetime
from typing import Any, Dict, List, Optional

from etl.base import BaseETL

logger = logging.getLogger(__name__)


class SalesOrderMigration(BaseETL):
    """Migrate sales orders to Odoo."""

    QUERY = """
        SELECT
            so.order_id,
            so.order_number,
            so.customer_id,
            so.order_date,
            so.delivery_date,
            so.total_amount,
            so.tax_amount,
            so.discount_amount,
            so.status,
            so.payment_status,
            so.notes,
            so.created_by,
            so.created_date
        FROM legacy_sales_orders so
        WHERE so.order_date >= NOW() - INTERVAL '2 years'
        ORDER BY so.order_date
    """

    LINES_QUERY = """
        SELECT
            sol.line_id,
            sol.order_id,
            sol.product_id,
            sol.quantity,
            sol.unit_price,
            sol.discount_percent,
            sol.tax_percent,
            sol.line_total
        FROM legacy_sales_order_lines sol
        WHERE sol.order_id = %s
        ORDER BY sol.line_id
    """

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.partner_map = {}
        self.product_map = {}
        self._load_mappings()

    def _load_mappings(self):
        """Load legacy ID to Odoo ID mappings."""
        # Partner mapping
        partners = self.odoo_execute(
            'res.partner',
            'search_read',
            [('x_legacy_id', '!=', False)],
            {'fields': ['id', 'x_legacy_id']}
        )
        self.partner_map = {p['x_legacy_id']: p['id'] for p in partners}

        # Product mapping
        products = self.odoo_execute(
            'product.product',
            'search_read',
            [('x_legacy_id', '!=', False)],
            {'fields': ['id', 'x_legacy_id']}
        )
        self.product_map = {p['x_legacy_id']: p['id'] for p in products}

        logger.info(
            f"Loaded mappings: {len(self.partner_map)} partners, "
            f"{len(self.product_map)} products"
        )

    def extract(self) -> List[Dict[str, Any]]:
        """Extract sales orders from legacy system."""
        with self.source_conn.cursor() as cur:
            cur.execute(self.QUERY)
            orders = cur.fetchall()

            # Fetch lines for each order
            for order in orders:
                cur.execute(self.LINES_QUERY, (order['order_id'],))
                order['lines'] = cur.fetchall()

            return orders

    def transform(self, record: Dict[str, Any]) -> Optional[Dict[str, Any]]:
        """Transform legacy sales order to Odoo format."""
        # Get partner
        partner_id = self.partner_map.get(record['customer_id'])
        if not partner_id:
            logger.warning(
                f"Skipping order {record['order_number']}: "
                f"customer {record['customer_id']} not found"
            )
            return None

        # Map status
        state_map = {
            'draft': 'draft',
            'confirmed': 'sale',
            'delivered': 'sale',
            'invoiced': 'sale',
            'cancelled': 'cancel',
        }
        state = state_map.get(record['status'], 'draft')

        # Transform order lines
        order_lines = []
        for line in record.get('lines', []):
            product_id = self.product_map.get(line['product_id'])
            if not product_id:
                logger.warning(
                    f"Skipping line: product {line['product_id']} not found"
                )
                continue

            order_lines.append((0, 0, {
                'product_id': product_id,
                'product_uom_qty': float(line['quantity']),
                'price_unit': float(line['unit_price']),
                'discount': float(line.get('discount_percent', 0)),
                'x_legacy_line_id': line['line_id'],
            }))

        if not order_lines:
            logger.warning(
                f"Skipping order {record['order_number']}: no valid lines"
            )
            return None

        return {
            'name': record['order_number'],
            'partner_id': partner_id,
            'date_order': record['order_date'].strftime('%Y-%m-%d %H:%M:%S'),
            'commitment_date': (
                record['delivery_date'].strftime('%Y-%m-%d %H:%M:%S')
                if record.get('delivery_date') else False
            ),
            'state': state,
            'note': record.get('notes', ''),
            'order_line': order_lines,
            'x_legacy_id': record['order_id'],
            'x_migrated': True,
        }

    def load(self, records: List[Dict[str, Any]]) -> int:
        """Load sales orders to Odoo."""
        loaded = 0
        for record in records:
            try:
                order_id = self.odoo_execute(
                    'sale.order',
                    'create',
                    [record]
                )
                loaded += 1

                if loaded % 100 == 0:
                    logger.info(f"Loaded {loaded} sales orders")

            except Exception as e:
                self.stats.record_error(
                    record.get('x_legacy_id', 'unknown'),
                    str(e)
                )
                logger.error(f"Error loading order {record['name']}: {e}")

        return loaded
```

**End-of-Day 708 Deliverables (Dev 1):**

- [ ] Sales orders migrated (2-year history)
- [ ] Purchase orders migrated
- [ ] Inventory movements migrated
- [ ] Opening balances configured

---

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Execute transaction migration jobs — 3h
2. Monitor database performance — 2h
3. Handle migration errors — 2h
4. Create migration checkpoints — 1h

**End-of-Day 708 Deliverables (Dev 2):**

- [ ] Transaction jobs executed
- [ ] Performance monitored
- [ ] Errors handled
- [ ] Checkpoints created

---

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**

1. Validate transactions in Odoo — 3h
2. Verify financial reconciliation — 3h
3. Document validation results — 2h

**End-of-Day 708 Deliverables (Dev 3):**

- [ ] Transactions validated
- [ ] Financial reconciliation verified
- [ ] Validation documented

---

### Day 709 — Farm Records Migration

**Objective:** Migrate all farm-specific data including cattle registry, health records, and milk production history.

---

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Migrate cattle registry — 2.5h
2. Migrate health records — 2h
3. Migrate milk production records — 2.5h
4. Validate farm data integrity — 1h

**Code: Cattle Migration**

```python
# smart_dairy_migration/migrations/006_farm_records.py

"""
Farm Records Migration

Migrates cattle registry, health records, and milk production data.
"""

import logging
from typing import Any, Dict, List, Optional

from etl.base import BaseETL, BatchLoader

logger = logging.getLogger(__name__)


class CattleMigration(BaseETL):
    """Migrate cattle registry to smart.cattle."""

    QUERY = """
        SELECT
            c.cattle_id,
            c.tag_number,
            c.name,
            c.breed_id,
            c.date_of_birth,
            c.gender,
            c.mother_id,
            c.father_id,
            c.purchase_date,
            c.purchase_price,
            c.source,
            c.current_weight,
            c.lactation_number,
            c.status,
            c.barn_id,
            c.notes,
            c.created_date
        FROM legacy_cattle c
        ORDER BY c.cattle_id
    """

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.cattle_map = {}  # For parent references
        self.breed_map = {}
        self.barn_map = {}
        self._load_mappings()

    def _load_mappings(self):
        """Load breed and barn mappings."""
        # Load breeds
        breeds = self.odoo_execute(
            'smart.cattle.breed',
            'search_read',
            [],
            {'fields': ['id', 'name']}
        )
        self.breed_map = {b['name'].lower(): b['id'] for b in breeds}

        # Load barns
        barns = self.odoo_execute(
            'smart.barn',
            'search_read',
            [],
            {'fields': ['id', 'name', 'x_legacy_id']}
        )
        self.barn_map = {b.get('x_legacy_id', b['id']): b['id'] for b in barns}

    def extract(self) -> List[Dict[str, Any]]:
        """Extract cattle from legacy system."""
        with self.source_conn.cursor() as cur:
            cur.execute(self.QUERY)
            return cur.fetchall()

    def transform(self, record: Dict[str, Any]) -> Optional[Dict[str, Any]]:
        """Transform legacy cattle to smart.cattle format."""
        if not record.get('tag_number'):
            return None

        # Map gender
        gender_map = {'M': 'male', 'F': 'female'}
        gender = gender_map.get(record.get('gender'), 'female')

        # Map status
        status_map = {
            'active': 'active',
            'sold': 'sold',
            'dead': 'deceased',
            'culled': 'culled',
        }
        state = status_map.get(record.get('status', '').lower(), 'active')

        # Get breed ID
        breed_id = False
        if record.get('breed_id'):
            breed_id = self._get_or_create_breed(record['breed_id'])

        # Get barn ID
        barn_id = self.barn_map.get(record.get('barn_id'), False)

        return {
            'tag_number': record['tag_number'].upper().strip(),
            'name': record.get('name', record['tag_number']),
            'breed_id': breed_id,
            'date_of_birth': (
                record['date_of_birth'].strftime('%Y-%m-%d')
                if record.get('date_of_birth') else False
            ),
            'gender': gender,
            'purchase_date': (
                record['purchase_date'].strftime('%Y-%m-%d')
                if record.get('purchase_date') else False
            ),
            'purchase_price': float(record.get('purchase_price', 0) or 0),
            'source': record.get('source', ''),
            'current_weight': float(record.get('current_weight', 0) or 0),
            'lactation_number': int(record.get('lactation_number', 0) or 0),
            'state': state,
            'barn_id': barn_id,
            'notes': record.get('notes', ''),
            'x_legacy_id': record['cattle_id'],
            'x_migrated': True,
        }

    def load(self, records: List[Dict[str, Any]]) -> int:
        """Load cattle to Odoo in two passes for parent references."""
        # First pass: create all cattle without parent references
        logger.info("First pass: creating cattle records...")
        for record in records:
            try:
                cattle_id = self.odoo_execute(
                    'smart.cattle',
                    'create',
                    [record]
                )
                self.cattle_map[record['x_legacy_id']] = cattle_id
            except Exception as e:
                self.stats.record_error(record['x_legacy_id'], str(e))

        # Second pass: update parent references
        logger.info("Second pass: updating parent references...")
        source_data = self.extract()
        for record in source_data:
            odoo_id = self.cattle_map.get(record['cattle_id'])
            if not odoo_id:
                continue

            updates = {}
            if record.get('mother_id'):
                mother_odoo_id = self.cattle_map.get(record['mother_id'])
                if mother_odoo_id:
                    updates['mother_id'] = mother_odoo_id

            if record.get('father_id'):
                father_odoo_id = self.cattle_map.get(record['father_id'])
                if father_odoo_id:
                    updates['father_id'] = father_odoo_id

            if updates:
                try:
                    self.odoo_execute(
                        'smart.cattle',
                        'write',
                        [[odoo_id], updates]
                    )
                except Exception as e:
                    logger.error(f"Error updating cattle {odoo_id}: {e}")

        return len(self.cattle_map)

    def _get_or_create_breed(self, legacy_breed_id: int) -> int:
        """Get breed ID or create if not exists."""
        # Simplified - in practice, map from legacy breed table
        default_breed = self.odoo_execute(
            'smart.cattle.breed',
            'search',
            [],
            {'limit': 1}
        )
        return default_breed[0] if default_breed else False


class MilkProductionMigration(BaseETL):
    """Migrate milk production records."""

    QUERY = """
        SELECT
            mp.record_id,
            mp.cattle_id,
            mp.record_date,
            mp.session,  -- 'morning' or 'evening'
            mp.quantity_liters,
            mp.fat_percentage,
            mp.snf_percentage,
            mp.quality_grade,
            mp.temperature,
            mp.collected_by,
            mp.notes,
            mp.created_date
        FROM legacy_milk_production mp
        WHERE mp.record_date >= NOW() - INTERVAL '2 years'
        ORDER BY mp.record_date, mp.cattle_id
    """

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.cattle_map = {}
        self._load_cattle_mapping()

    def _load_cattle_mapping(self):
        """Load cattle mapping."""
        cattle = self.odoo_execute(
            'smart.cattle',
            'search_read',
            [('x_legacy_id', '!=', False)],
            {'fields': ['id', 'x_legacy_id']}
        )
        self.cattle_map = {c['x_legacy_id']: c['id'] for c in cattle}

    def extract(self) -> List[Dict[str, Any]]:
        """Extract milk production records."""
        with self.source_conn.cursor() as cur:
            cur.execute(self.QUERY)
            return cur.fetchall()

    def transform(self, record: Dict[str, Any]) -> Optional[Dict[str, Any]]:
        """Transform milk production record."""
        cattle_id = self.cattle_map.get(record['cattle_id'])
        if not cattle_id:
            return None

        session_map = {
            'morning': 'am',
            'evening': 'pm',
            'am': 'am',
            'pm': 'pm',
        }
        session = session_map.get(record.get('session', '').lower(), 'am')

        return {
            'cattle_id': cattle_id,
            'date': record['record_date'].strftime('%Y-%m-%d'),
            'session': session,
            'quantity': float(record.get('quantity_liters', 0)),
            'fat_percentage': float(record.get('fat_percentage', 0) or 0),
            'snf_percentage': float(record.get('snf_percentage', 0) or 0),
            'temperature': float(record.get('temperature', 0) or 0),
            'notes': record.get('notes', ''),
            'x_legacy_id': record['record_id'],
            'x_migrated': True,
        }

    def load(self, records: List[Dict[str, Any]]) -> int:
        """Load milk production records."""
        loader = BatchLoader(self, 'smart.milk.production', batch_size=1000)
        return loader.load_batch(records)
```

**End-of-Day 709 Deliverables (Dev 1):**

- [ ] Cattle registry migrated
- [ ] Health records migrated
- [ ] Milk production history migrated
- [ ] Farm data integrity validated

---

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Execute farm migration jobs — 3h
2. Handle large data volumes — 2h
3. Create farm data backups — 2h
4. Monitor migration metrics — 1h

**End-of-Day 709 Deliverables (Dev 2):**

- [ ] Farm jobs executed
- [ ] Large volumes handled
- [ ] Backups created
- [ ] Metrics monitored

---

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**

1. Validate farm data in UI — 3h
2. Test farm module functionality — 3h
3. Document farm data status — 2h

**End-of-Day 709 Deliverables (Dev 3):**

- [ ] Farm data validated
- [ ] Farm module tested
- [ ] Status documented

---

### Day 710 — Validation, Reconciliation & Sign-off

**Objective:** Complete comprehensive data validation, financial reconciliation, and obtain formal sign-off.

---

#### Dev 1 — Backend Lead (8h)

**Tasks:**

1. Execute validation queries — 2h
2. Generate reconciliation reports — 2.5h
3. Fix identified discrepancies — 2.5h
4. Prepare sign-off documentation — 1h

**Code: Validation Queries**

```sql
-- validation/sql/master_data_validation.sql

-- =============================================================
-- SMART DAIRY DATA MIGRATION VALIDATION QUERIES
-- =============================================================

-- 1. Customer/Supplier Count Validation
SELECT
    'Customers' as data_type,
    (SELECT COUNT(*) FROM legacy_customers WHERE is_customer = true AND active = true) as source_count,
    (SELECT COUNT(*) FROM res_partner WHERE customer_rank > 0 AND x_migrated = true) as target_count,
    CASE
        WHEN (SELECT COUNT(*) FROM legacy_customers WHERE is_customer = true AND active = true) =
             (SELECT COUNT(*) FROM res_partner WHERE customer_rank > 0 AND x_migrated = true)
        THEN 'PASS'
        ELSE 'FAIL'
    END as validation_status
UNION ALL
SELECT
    'Suppliers',
    (SELECT COUNT(*) FROM legacy_customers WHERE is_supplier = true AND active = true),
    (SELECT COUNT(*) FROM res_partner WHERE supplier_rank > 0 AND x_migrated = true),
    CASE
        WHEN (SELECT COUNT(*) FROM legacy_customers WHERE is_supplier = true AND active = true) =
             (SELECT COUNT(*) FROM res_partner WHERE supplier_rank > 0 AND x_migrated = true)
        THEN 'PASS'
        ELSE 'FAIL'
    END;

-- 2. Product Count Validation
SELECT
    'Products' as data_type,
    (SELECT COUNT(*) FROM legacy_products WHERE active = true) as source_count,
    (SELECT COUNT(*) FROM product_template WHERE x_migrated = true) as target_count,
    CASE
        WHEN (SELECT COUNT(*) FROM legacy_products WHERE active = true) =
             (SELECT COUNT(*) FROM product_template WHERE x_migrated = true)
        THEN 'PASS'
        ELSE 'FAIL'
    END as validation_status;

-- 3. Sales Order Count Validation
SELECT
    'Sales Orders' as data_type,
    (SELECT COUNT(*) FROM legacy_sales_orders
     WHERE order_date >= NOW() - INTERVAL '2 years') as source_count,
    (SELECT COUNT(*) FROM sale_order WHERE x_migrated = true) as target_count,
    CASE
        WHEN (SELECT COUNT(*) FROM legacy_sales_orders WHERE order_date >= NOW() - INTERVAL '2 years') =
             (SELECT COUNT(*) FROM sale_order WHERE x_migrated = true)
        THEN 'PASS'
        ELSE 'FAIL'
    END as validation_status;

-- 4. Cattle Registry Validation
SELECT
    'Cattle' as data_type,
    (SELECT COUNT(*) FROM legacy_cattle) as source_count,
    (SELECT COUNT(*) FROM smart_cattle WHERE x_migrated = true) as target_count,
    CASE
        WHEN (SELECT COUNT(*) FROM legacy_cattle) =
             (SELECT COUNT(*) FROM smart_cattle WHERE x_migrated = true)
        THEN 'PASS'
        ELSE 'FAIL'
    END as validation_status;

-- 5. Milk Production Records Validation
SELECT
    'Milk Production' as data_type,
    (SELECT COUNT(*) FROM legacy_milk_production
     WHERE record_date >= NOW() - INTERVAL '2 years') as source_count,
    (SELECT COUNT(*) FROM smart_milk_production WHERE x_migrated = true) as target_count,
    CASE
        WHEN (SELECT COUNT(*) FROM legacy_milk_production WHERE record_date >= NOW() - INTERVAL '2 years') =
             (SELECT COUNT(*) FROM smart_milk_production WHERE x_migrated = true)
        THEN 'PASS'
        ELSE 'FAIL'
    END as validation_status;

-- 6. Financial Totals Validation
SELECT
    'Sales Total (BDT)' as metric,
    (SELECT COALESCE(SUM(total_amount), 0) FROM legacy_sales_orders
     WHERE order_date >= NOW() - INTERVAL '2 years') as source_value,
    (SELECT COALESCE(SUM(amount_total), 0) FROM sale_order
     WHERE x_migrated = true) as target_value,
    CASE
        WHEN ABS(
            (SELECT COALESCE(SUM(total_amount), 0) FROM legacy_sales_orders
             WHERE order_date >= NOW() - INTERVAL '2 years') -
            (SELECT COALESCE(SUM(amount_total), 0) FROM sale_order WHERE x_migrated = true)
        ) < 1.00  -- Allow BDT 1 tolerance for rounding
        THEN 'PASS'
        ELSE 'FAIL'
    END as validation_status;

-- 7. Checksum Validation (sample)
SELECT
    'Data Integrity' as check_type,
    COUNT(*) as records_checked,
    SUM(CASE WHEN source_checksum = target_checksum THEN 1 ELSE 0 END) as matching,
    SUM(CASE WHEN source_checksum != target_checksum THEN 1 ELSE 0 END) as mismatched
FROM (
    SELECT
        lc.customer_id,
        MD5(CONCAT(lc.customer_name, lc.phone, lc.email)) as source_checksum,
        MD5(CONCAT(rp.name, rp.phone, rp.email)) as target_checksum
    FROM legacy_customers lc
    JOIN res_partner rp ON lc.customer_id = rp.x_legacy_id
    WHERE lc.active = true
    LIMIT 1000
) checksums;
```

**Code: Reconciliation Report Generator**

```python
# smart_dairy_migration/validation/reconciliation.py

"""
Data Migration Reconciliation Report Generator
"""

import logging
from datetime import datetime
from typing import Dict, List, Any
import pandas as pd

from config.settings import SOURCE_DB_CONFIG, TARGET_DB_CONFIG
import psycopg2
from psycopg2.extras import RealDictCursor

logger = logging.getLogger(__name__)


class ReconciliationReport:
    """Generate migration reconciliation report."""

    def __init__(self):
        self.source_conn = psycopg2.connect(
            **SOURCE_DB_CONFIG,
            cursor_factory=RealDictCursor
        )
        self.target_conn = psycopg2.connect(
            **TARGET_DB_CONFIG,
            cursor_factory=RealDictCursor
        )
        self.results: List[Dict[str, Any]] = []

    def run_all_checks(self) -> pd.DataFrame:
        """Run all reconciliation checks."""
        logger.info("Starting reconciliation checks...")

        # Row count checks
        self._check_row_counts()

        # Financial reconciliation
        self._check_financial_totals()

        # Data integrity checks
        self._check_data_integrity()

        # Referential integrity
        self._check_referential_integrity()

        logger.info(f"Completed {len(self.results)} checks")

        return pd.DataFrame(self.results)

    def _check_row_counts(self):
        """Check row counts match between source and target."""
        checks = [
            ("Customers", "SELECT COUNT(*) FROM legacy_customers WHERE is_customer = true AND active = true",
             "SELECT COUNT(*) FROM res_partner WHERE customer_rank > 0 AND x_migrated = true"),
            ("Suppliers", "SELECT COUNT(*) FROM legacy_customers WHERE is_supplier = true AND active = true",
             "SELECT COUNT(*) FROM res_partner WHERE supplier_rank > 0 AND x_migrated = true"),
            ("Products", "SELECT COUNT(*) FROM legacy_products WHERE active = true",
             "SELECT COUNT(*) FROM product_template WHERE x_migrated = true"),
            ("Cattle", "SELECT COUNT(*) FROM legacy_cattle",
             "SELECT COUNT(*) FROM smart_cattle WHERE x_migrated = true"),
            ("Sales Orders", "SELECT COUNT(*) FROM legacy_sales_orders WHERE order_date >= NOW() - INTERVAL '2 years'",
             "SELECT COUNT(*) FROM sale_order WHERE x_migrated = true"),
            ("Milk Records", "SELECT COUNT(*) FROM legacy_milk_production WHERE record_date >= NOW() - INTERVAL '2 years'",
             "SELECT COUNT(*) FROM smart_milk_production WHERE x_migrated = true"),
        ]

        for name, source_query, target_query in checks:
            source_count = self._execute_source(source_query)
            target_count = self._execute_target(target_query)

            self.results.append({
                "Check Type": "Row Count",
                "Entity": name,
                "Source Count": source_count,
                "Target Count": target_count,
                "Difference": target_count - source_count,
                "Status": "PASS" if source_count == target_count else "FAIL",
                "Timestamp": datetime.now()
            })

    def _check_financial_totals(self):
        """Check financial totals match."""
        # Sales totals
        source_sales = self._execute_source(
            "SELECT COALESCE(SUM(total_amount), 0) FROM legacy_sales_orders "
            "WHERE order_date >= NOW() - INTERVAL '2 years'"
        )
        target_sales = self._execute_target(
            "SELECT COALESCE(SUM(amount_total), 0) FROM sale_order WHERE x_migrated = true"
        )

        self.results.append({
            "Check Type": "Financial",
            "Entity": "Sales Total (BDT)",
            "Source Count": f"{source_sales:,.2f}",
            "Target Count": f"{target_sales:,.2f}",
            "Difference": f"{target_sales - source_sales:,.2f}",
            "Status": "PASS" if abs(target_sales - source_sales) < 1.00 else "FAIL",
            "Timestamp": datetime.now()
        })

        # Purchase totals
        source_purchase = self._execute_source(
            "SELECT COALESCE(SUM(total_amount), 0) FROM legacy_purchase_orders "
            "WHERE order_date >= NOW() - INTERVAL '2 years'"
        )
        target_purchase = self._execute_target(
            "SELECT COALESCE(SUM(amount_total), 0) FROM purchase_order WHERE x_migrated = true"
        )

        self.results.append({
            "Check Type": "Financial",
            "Entity": "Purchase Total (BDT)",
            "Source Count": f"{source_purchase:,.2f}",
            "Target Count": f"{target_purchase:,.2f}",
            "Difference": f"{target_purchase - source_purchase:,.2f}",
            "Status": "PASS" if abs(target_purchase - source_purchase) < 1.00 else "FAIL",
            "Timestamp": datetime.now()
        })

    def _check_data_integrity(self):
        """Check data integrity with checksums."""
        # Sample check on customers
        with self.source_conn.cursor() as src_cur, self.target_conn.cursor() as tgt_cur:
            src_cur.execute("""
                SELECT customer_id, MD5(CONCAT(customer_name, phone, email)) as checksum
                FROM legacy_customers WHERE active = true LIMIT 100
            """)
            source_checksums = {r['customer_id']: r['checksum'] for r in src_cur.fetchall()}

            tgt_cur.execute("""
                SELECT x_legacy_id, MD5(CONCAT(name, phone, email)) as checksum
                FROM res_partner WHERE x_migrated = true AND x_legacy_id IS NOT NULL LIMIT 100
            """)
            target_checksums = {r['x_legacy_id']: r['checksum'] for r in tgt_cur.fetchall()}

        matching = sum(1 for k, v in source_checksums.items()
                       if target_checksums.get(k) == v)
        total = len(source_checksums)

        self.results.append({
            "Check Type": "Data Integrity",
            "Entity": "Customer Checksums (sample)",
            "Source Count": total,
            "Target Count": matching,
            "Difference": total - matching,
            "Status": "PASS" if matching == total else "FAIL",
            "Timestamp": datetime.now()
        })

    def _check_referential_integrity(self):
        """Check referential integrity."""
        # Check all cattle have valid references
        orphan_cattle = self._execute_target("""
            SELECT COUNT(*) FROM smart_cattle c
            WHERE c.mother_id IS NOT NULL
            AND NOT EXISTS (SELECT 1 FROM smart_cattle m WHERE m.id = c.mother_id)
        """)

        self.results.append({
            "Check Type": "Referential Integrity",
            "Entity": "Cattle Parent References",
            "Source Count": "N/A",
            "Target Count": orphan_cattle,
            "Difference": orphan_cattle,
            "Status": "PASS" if orphan_cattle == 0 else "FAIL",
            "Timestamp": datetime.now()
        })

    def _execute_source(self, query: str) -> Any:
        """Execute query on source database."""
        with self.source_conn.cursor() as cur:
            cur.execute(query)
            result = cur.fetchone()
            return list(result.values())[0] if result else 0

    def _execute_target(self, query: str) -> Any:
        """Execute query on target database."""
        with self.target_conn.cursor() as cur:
            cur.execute(query)
            result = cur.fetchone()
            return list(result.values())[0] if result else 0

    def generate_report(self, output_path: str = "reconciliation_report.xlsx"):
        """Generate Excel report."""
        df = self.run_all_checks()

        # Create Excel writer
        with pd.ExcelWriter(output_path, engine='openpyxl') as writer:
            # Summary sheet
            df.to_excel(writer, sheet_name='Summary', index=False)

            # Pivot by status
            status_summary = df.groupby('Status').size().reset_index(name='Count')
            status_summary.to_excel(writer, sheet_name='Status Summary', index=False)

        logger.info(f"Report saved to {output_path}")
        return output_path

    def close(self):
        """Close database connections."""
        self.source_conn.close()
        self.target_conn.close()


if __name__ == "__main__":
    logging.basicConfig(level=logging.INFO)

    report = ReconciliationReport()
    try:
        report.generate_report()
    finally:
        report.close()
```

**End-of-Day 710 Deliverables (Dev 1):**

- [ ] Validation queries executed
- [ ] Reconciliation report generated
- [ ] Discrepancies resolved
- [ ] Sign-off documentation prepared

---

#### Dev 2 — Full-Stack / DevOps (8h)

**Tasks:**

1. Execute final validation scripts — 2h
2. Test rollback procedures — 2.5h
3. Create final migration backup — 2h
4. Document migration completion — 1.5h

**End-of-Day 710 Deliverables (Dev 2):**

- [ ] Validation scripts executed
- [ ] Rollback tested (<30 min)
- [ ] Final backup created
- [ ] Completion documented

---

#### Dev 3 — Frontend / Mobile Lead (8h)

**Tasks:**

1. Compile reconciliation report — 3h
2. Coordinate business sign-off — 3h
3. Archive legacy data — 2h

**End-of-Day 710 Deliverables (Dev 3):**

- [ ] Reconciliation report compiled
- [ ] Business sign-off obtained
- [ ] Legacy data archived

---

## 4. Technical Specifications

### 4.1 Migration Volumes

| Data Type | Source Count | Target Count | Status |
|-----------|-------------|--------------|--------|
| Customers | ~5,000 | TBD | Pending |
| Suppliers | ~500 | TBD | Pending |
| Products | ~1,000 | TBD | Pending |
| Sales Orders (2yr) | ~50,000 | TBD | Pending |
| Purchase Orders (2yr) | ~10,000 | TBD | Pending |
| Cattle | ~300 | TBD | Pending |
| Health Records | ~5,000 | TBD | Pending |
| Milk Records (2yr) | ~200,000 | TBD | Pending |

### 4.2 Data Quality Rules

| Rule | Description | Enforcement |
|------|-------------|-------------|
| No duplicates | Each entity has unique identifier | Primary key constraint |
| Referential integrity | Foreign keys must exist | FK constraints |
| Required fields | Mandatory fields populated | NOT NULL + validation |
| Data format | Phone, email, dates formatted | Transform functions |
| Financial precision | 2 decimal places for amounts | DECIMAL(18,2) |

---

## 5. Testing & Validation

### 5.1 Validation Checklist

- [ ] Row counts match for all entities
- [ ] Financial totals reconcile (<BDT 1 variance)
- [ ] Checksums match for sample records
- [ ] Referential integrity verified
- [ ] Opening balances correct
- [ ] Historical data complete (2 years)
- [ ] Farm records linked correctly
- [ ] Rollback tested and verified

### 5.2 Acceptance Criteria

| Criterion | Target | Actual | Pass/Fail |
|-----------|--------|--------|-----------|
| Data completeness | 100% | TBD | |
| Row count accuracy | 100% | TBD | |
| Financial reconciliation | <BDT 1 variance | TBD | |
| Checksum validation | 100% match | TBD | |
| Rollback time | <30 minutes | TBD | |

---

## 6. Risk & Mitigation

| Risk ID | Description | Probability | Impact | Mitigation |
|---------|-------------|-------------|--------|------------|
| R-142-01 | Data volume causes timeout | Medium | High | Batch processing, increase timeouts |
| R-142-02 | Legacy data quality issues | High | Medium | Data cleansing transforms |
| R-142-03 | Referential integrity failures | Medium | High | Two-pass migration for parents |
| R-142-04 | Financial variance unacceptable | Low | Critical | Manual reconciliation |
| R-142-05 | Rollback takes too long | Low | Critical | Optimized rollback scripts |

---

## 7. Dependencies & Handoffs

### 7.1 Input Dependencies

| Dependency | Source | Required By | Status |
|------------|--------|-------------|--------|
| Production database ready | MS-141 | Day 706 | Pending |
| Legacy data export | Business | Day 706 | Pending |
| Cutover date confirmed | Management | Day 706 | Pending |

### 7.2 Output Handoffs

| Deliverable | Recipient | Handoff Date |
|-------------|-----------|--------------|
| Migrated production data | MS-143 (Security) | Day 710 |
| Reconciliation report | Business Owner | Day 710 |
| Rollback procedures | Operations | Day 710 |

---

## Milestone 142 Sign-off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Backend Lead (Dev 1) | | | |
| Full-Stack (Dev 2) | | | |
| Frontend Lead (Dev 3) | | | |
| Business Owner | | | |
| Project Manager | | | |

---

**Document End**

*Milestone 142: Data Migration & Validation*
*Smart Dairy Digital Smart Portal + ERP — Phase 15*
*Version 1.0 | Days 706-710*
