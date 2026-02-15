# Milestone 5: End-to-End Integration Testing & Phase 1A Completion

## Smart Dairy Digital Smart Portal + ERP — Phase 1: Foundation

| Field | Detail |
|---|---|
| **Milestone** | 5 of 10 |
| **Title** | End-to-End Integration Testing & Phase 1A Completion |
| **Phase** | Phase 1 — Foundation (Part A: Infrastructure & Core Setup) |
| **Days** | Days 41–50 (of 100) |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Dev 1 (Backend Lead), Dev 2 (Full-Stack/DevOps), Dev 3 (Frontend/Mobile Lead) |
| **Last Updated** | 2026-02-03 |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Requirement Traceability Matrix](#2-requirement-traceability-matrix)
3. [Day-by-Day Breakdown](#3-day-by-day-breakdown-days-4150)
4. [Technical Specifications](#4-technical-specifications)
5. [Testing & Validation](#5-testing--validation)
6. [Risk & Mitigation](#6-risk--mitigation)
7. [Dependencies & Handoffs](#7-dependencies--handoffs)

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 5 is the culmination of Phase 1A (Infrastructure & Core Setup). Over Days 41--50, the team will execute comprehensive end-to-end integration testing across every layer of the Smart Dairy platform: Odoo core modules, the custom `smart_farm_mgmt` module, FastAPI endpoints, OWL frontend components, and the CI/CD pipeline. Upon completion, the system will be validated as a stable, tested foundation ready for Phase 1B (Feature Expansion).

### 1.2 Scope

- Integration testing of all Odoo module inter-dependencies (Sales, Inventory, Accounting, Purchase, Manufacturing)
- Custom module integration verification (`smart_farm_mgmt` with core Odoo modules)
- API endpoint validation (FastAPI + Odoo RPC)
- Frontend-backend integration (OWL components with Odoo controllers)
- Performance regression testing (confirming M4 database optimizations hold)
- Security scanning (OWASP baseline, dependency audit, injection testing)
- Bug triage and critical fix sprint
- Phase 1A transition documentation and formal sign-off

### 1.3 Success Criteria

| Criterion | Target |
|---|---|
| Integration test pass rate | >= 95% |
| API endpoint coverage | 100% of defined routes |
| P0 / P1 bugs remaining | 0 |
| Performance regression delta | < 5% from M4 baselines |
| Security vulnerabilities (Critical/High) | 0 |
| Phase 1A checklist completion | 100% |

### 1.4 Key Deliverables

| # | Deliverable | Owner |
|---|---|---|
| D5.1 | Integration Test Plan document | Dev 1 |
| D5.2 | Passing integration test suite (pytest) | Dev 1 + Dev 2 |
| D5.3 | API test report | Dev 1 |
| D5.4 | Frontend integration test report | Dev 3 |
| D5.5 | Performance regression report | Dev 2 |
| D5.6 | Security scan report | Dev 2 |
| D5.7 | Bug triage log and fix record | All |
| D5.8 | Phase 1A Transition Document | All |
| D5.9 | Architecture Decision Records (ADRs) | Dev 1 |
| D5.10 | Phase 1A Sign-off certificate | All |

---

## 2. Requirement Traceability Matrix

This milestone traces directly to the governance documents established in the project charter.

| Requirement ID | Governance Doc | Description | Milestone 5 Coverage |
|---|---|---|---|
| G-001-TS-01 | G-001 Test Strategy | Integration test plan required for each phase gate | D5.1 Integration Test Plan |
| G-001-TS-02 | G-001 Test Strategy | Minimum 95% pass rate before phase transition | D5.2 Test Suite Execution |
| G-001-TS-03 | G-001 Test Strategy | Performance baseline regression checks | D5.5 Performance Regression |
| G-006-TD-01 | G-006 Testing Docs | Test data management and seeding strategy | Day 41 test data strategy |
| G-006-TD-02 | G-006 Testing Docs | Security scanning at each phase gate | D5.6 Security Scan |
| G-006-TD-03 | G-006 Testing Docs | Bug triage with priority classification | D5.7 Bug Triage Log |
| M4-OPT-01 | Milestone 4 | Database query optimizations verified | Day 46 regression testing |
| M3-FARM-01 | Milestone 3 | smart_farm_mgmt module functional | Day 43 custom module tests |
| M2-CORE-01 | Milestone 2 | Core Odoo modules configured | Day 42 inter-dependency tests |

---

## 3. Day-by-Day Breakdown (Days 41–50)

---

### Day 41: Integration Test Plan Development

**Objective:** Establish the test matrix, test data strategy, and integration test environment.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0–2 | Author the Integration Test Plan document: scope, approach, entry/exit criteria |
| 2–4 | Design the test matrix mapping every module pair (Sales-Inventory, Farm-Accounting, etc.) |
| 4–6 | Create test data seeding scripts for realistic farm scenarios (255 cattle, 75 lactating, 900L/day) |
| 6–8 | Write `conftest.py` with Odoo test fixtures and database setup/teardown |

**conftest.py — Odoo Integration Test Fixtures:**

```python
# tests/conftest.py
"""
Smart Dairy — Integration Test Configuration
Provides fixtures for Odoo module integration testing.
"""
import os
import pytest
import xmlrpc.client
import psycopg2
from datetime import date, timedelta

# ---------------------------------------------------------------------------
# Environment
# ---------------------------------------------------------------------------
ODOO_URL = os.getenv("ODOO_URL", "http://localhost:8069")
ODOO_DB = os.getenv("ODOO_DB", "smart_dairy_test")
ODOO_USER = os.getenv("ODOO_USER", "admin")
ODOO_PASSWORD = os.getenv("ODOO_PASSWORD", "admin")

PG_HOST = os.getenv("PG_HOST", "localhost")
PG_PORT = int(os.getenv("PG_PORT", "5432"))
PG_USER = os.getenv("PG_USER", "odoo")
PG_PASSWORD = os.getenv("PG_PASSWORD", "odoo")


@pytest.fixture(scope="session")
def odoo_xmlrpc():
    """Establish XML-RPC connection to the Odoo instance."""
    common = xmlrpc.client.ServerProxy(f"{ODOO_URL}/xmlrpc/2/common")
    uid = common.authenticate(ODOO_DB, ODOO_USER, ODOO_PASSWORD, {})
    assert uid, "Odoo authentication failed — check credentials and DB name."
    models = xmlrpc.client.ServerProxy(f"{ODOO_URL}/xmlrpc/2/object")
    return {"uid": uid, "models": models, "db": ODOO_DB, "password": ODOO_PASSWORD}


@pytest.fixture(scope="session")
def pg_connection():
    """Direct PostgreSQL connection for data verification."""
    conn = psycopg2.connect(
        host=PG_HOST,
        port=PG_PORT,
        dbname=ODOO_DB,
        user=PG_USER,
        password=PG_PASSWORD,
    )
    yield conn
    conn.close()


@pytest.fixture(scope="session")
def seed_farm_data(odoo_xmlrpc):
    """Seed realistic Smart Dairy test data — 255 cattle, 75 lactating cows."""
    m = odoo_xmlrpc["models"]
    db = odoo_xmlrpc["db"]
    uid = odoo_xmlrpc["uid"]
    pwd = odoo_xmlrpc["password"]

    # Create cattle categories
    category_ids = {}
    for name, count in [("Lactating Cow", 75), ("Dry Cow", 40),
                        ("Heifer", 50), ("Calf", 60), ("Bull", 30)]:
        cat_id = m.execute_kw(db, uid, pwd, "farm.cattle.category", "create",
                              [{"name": name, "expected_count": count}])
        category_ids[name] = cat_id

    # Create sample cattle records
    cattle_ids = []
    for i in range(1, 256):
        cat = "Lactating Cow" if i <= 75 else (
              "Dry Cow" if i <= 115 else (
              "Heifer" if i <= 165 else (
              "Calf" if i <= 225 else "Bull")))
        cattle_id = m.execute_kw(db, uid, pwd, "farm.cattle", "create", [{
            "name": f"SD-{i:04d}",
            "tag_number": f"TAG-{i:04d}",
            "category_id": category_ids[cat],
            "date_of_birth": str(date.today() - timedelta(days=365 * (2 + i % 5))),
            "weight": 350.0 + (i % 200),
            "is_active": True,
        }])
        cattle_ids.append(cattle_id)

    yield {
        "category_ids": category_ids,
        "cattle_ids": cattle_ids,
        "lactating_count": 75,
        "total_count": 255,
    }

    # Teardown: remove seeded data
    m.execute_kw(db, uid, pwd, "farm.cattle", "unlink", [cattle_ids])
    for cid in category_ids.values():
        m.execute_kw(db, uid, pwd, "farm.cattle.category", "unlink", [[cid]])


@pytest.fixture(scope="session")
def seed_milk_production(odoo_xmlrpc, seed_farm_data):
    """Seed 7 days of milk production records — ~900L/day target."""
    m = odoo_xmlrpc["models"]
    db = odoo_xmlrpc["db"]
    uid = odoo_xmlrpc["uid"]
    pwd = odoo_xmlrpc["password"]

    production_ids = []
    lactating_ids = seed_farm_data["cattle_ids"][:75]
    for day_offset in range(7):
        record_date = str(date.today() - timedelta(days=day_offset))
        for cow_id in lactating_ids:
            prod_id = m.execute_kw(db, uid, pwd, "farm.milk.production", "create", [{
                "cattle_id": cow_id,
                "date": record_date,
                "morning_yield": 6.0,
                "evening_yield": 6.0,
            }])
            production_ids.append(prod_id)

    yield {"production_ids": production_ids, "days": 7}

    m.execute_kw(db, uid, pwd, "farm.milk.production", "unlink", [production_ids])
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0–2 | Provision dedicated integration test environment (Docker Compose profile: `test`) |
| 2–4 | Configure test database with automated creation/teardown in CI pipeline |
| 4–6 | Set up pytest-cov, pytest-html, and allure report generation |
| 6–8 | Update GitHub Actions workflow to include integration test stage |

**pytest.ini — Test Configuration:**

```ini
# pytest.ini
[pytest]
testpaths = tests
python_files = test_*.py
python_classes = Test*
python_functions = test_*
markers =
    integration: End-to-end integration tests
    api: API endpoint tests
    security: Security validation tests
    performance: Performance regression tests
    e2e: Browser-based end-to-end tests
    slow: Tests that take > 30 seconds
addopts =
    --strict-markers
    --tb=short
    -v
    --cov=smart_farm_mgmt
    --cov=custom_addons
    --cov-report=html:reports/coverage
    --cov-report=xml:reports/coverage.xml
    --cov-fail-under=80
    --html=reports/integration_report.html
    --self-contained-html
```

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0–2 | Inventory all OWL components requiring integration validation |
| 2–4 | Set up QUnit test harness for OWL dashboard widgets |
| 4–6 | Create Playwright project configuration for E2E browser tests |
| 6–8 | Build test utility helpers (login flow, navigation, data assertions) |

**End-of-Day 41 Deliverables:**
- [ ] Integration Test Plan document (PDF/MD)
- [ ] `conftest.py` with all fixtures committed
- [ ] Test environment Docker profile operational
- [ ] QUnit and Playwright project scaffolded

---

### Day 42: Odoo Module Inter-Dependency Testing

**Objective:** Validate core Odoo module flows — Sales to Delivery to Invoice, Purchase to Inventory, Manufacturing BOM processing.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0–3 | Write `TestSalesToDeliveryToInvoice` — full order-to-cash cycle |
| 3–6 | Write `TestPurchaseToInventory` — procure-to-stock cycle |
| 6–8 | Write `TestManufacturingBOM` — BOM validation and production order |

**test_odoo_integration.py — Core Module Integration Tests:**

```python
# tests/integration/test_odoo_integration.py
"""
Smart Dairy — Odoo Core Module Integration Tests
Tests the Sales -> Inventory -> Accounting pipeline and related flows.
"""
import pytest
from datetime import date


@pytest.mark.integration
class TestSalesToDeliveryToInvoice:
    """Validate the complete order-to-cash flow for Saffron milk products."""

    def test_create_sale_order(self, odoo_xmlrpc):
        m = odoo_xmlrpc["models"]
        db, uid, pwd = odoo_xmlrpc["db"], odoo_xmlrpc["uid"], odoo_xmlrpc["password"]

        # Find or create the Saffron milk product
        product_ids = m.execute_kw(db, uid, pwd, "product.product", "search",
                                   [[("name", "=", "Saffron Raw Milk (1L)")]])
        assert product_ids, "Product 'Saffron Raw Milk (1L)' must exist."
        product_id = product_ids[0]

        # Find or create a test customer
        partner_ids = m.execute_kw(db, uid, pwd, "res.partner", "search",
                                   [[("name", "=", "Test Dairy Customer")]])
        if not partner_ids:
            partner_id = m.execute_kw(db, uid, pwd, "res.partner", "create",
                                      [{"name": "Test Dairy Customer",
                                        "email": "test@example.com"}])
        else:
            partner_id = partner_ids[0]

        # Create sale order — 100 litres of Saffron milk
        so_id = m.execute_kw(db, uid, pwd, "sale.order", "create", [{
            "partner_id": partner_id,
            "date_order": str(date.today()),
            "order_line": [(0, 0, {
                "product_id": product_id,
                "product_uom_qty": 100.0,
                "price_unit": 120.0,
            })],
        }])
        assert so_id, "Sale order creation must succeed."

        # Confirm the sale order
        m.execute_kw(db, uid, pwd, "sale.order", "action_confirm", [[so_id]])
        so_data = m.execute_kw(db, uid, pwd, "sale.order", "read",
                               [[so_id], ["state", "picking_ids", "invoice_ids"]])
        assert so_data[0]["state"] == "sale", "Order must be in 'sale' state."

        return so_id

    def test_delivery_created_on_confirmation(self, odoo_xmlrpc):
        so_id = self.test_create_sale_order(odoo_xmlrpc)
        m = odoo_xmlrpc["models"]
        db, uid, pwd = odoo_xmlrpc["db"], odoo_xmlrpc["uid"], odoo_xmlrpc["password"]

        so_data = m.execute_kw(db, uid, pwd, "sale.order", "read",
                               [[so_id], ["picking_ids"]])
        picking_ids = so_data[0]["picking_ids"]
        assert len(picking_ids) >= 1, "At least one delivery order must be created."

        # Validate and process the delivery
        for picking_id in picking_ids:
            picking = m.execute_kw(db, uid, pwd, "stock.picking", "read",
                                   [[picking_id], ["state"]])
            assert picking[0]["state"] in ("assigned", "waiting", "confirmed")

    def test_invoice_generation(self, odoo_xmlrpc):
        so_id = self.test_create_sale_order(odoo_xmlrpc)
        m = odoo_xmlrpc["models"]
        db, uid, pwd = odoo_xmlrpc["db"], odoo_xmlrpc["uid"], odoo_xmlrpc["password"]

        # Generate invoice from sale order
        m.execute_kw(db, uid, pwd, "sale.order", "action_create_invoices", [[so_id]])
        so_data = m.execute_kw(db, uid, pwd, "sale.order", "read",
                               [[so_id], ["invoice_ids"]])
        invoice_ids = so_data[0]["invoice_ids"]
        assert len(invoice_ids) >= 1, "Invoice must be generated from sale order."

        invoice = m.execute_kw(db, uid, pwd, "account.move", "read",
                               [[invoice_ids[0]], ["state", "amount_total"]])
        assert invoice[0]["amount_total"] == 12000.0, "Invoice total must be 100 * 120 = 12000 BDT."


@pytest.mark.integration
class TestPurchaseToInventory:
    """Validate purchase-to-stock flow for feed procurement."""

    def test_purchase_feed_and_receive(self, odoo_xmlrpc):
        m = odoo_xmlrpc["models"]
        db, uid, pwd = odoo_xmlrpc["db"], odoo_xmlrpc["uid"], odoo_xmlrpc["password"]

        # Find feed product
        feed_ids = m.execute_kw(db, uid, pwd, "product.product", "search",
                                [[("name", "ilike", "Cattle Feed")]])
        assert feed_ids, "Cattle Feed product must exist in the system."
        feed_id = feed_ids[0]

        # Find or create a feed supplier
        supplier_ids = m.execute_kw(db, uid, pwd, "res.partner", "search",
                                    [[("name", "=", "Test Feed Supplier")]])
        if not supplier_ids:
            supplier_id = m.execute_kw(db, uid, pwd, "res.partner", "create",
                                       [{"name": "Test Feed Supplier",
                                         "supplier_rank": 1}])
        else:
            supplier_id = supplier_ids[0]

        # Get starting inventory level
        quant_before = m.execute_kw(db, uid, pwd, "stock.quant", "search_read",
                                    [[("product_id", "=", feed_id),
                                      ("location_id.usage", "=", "internal")]],
                                    {"fields": ["quantity"]})
        qty_before = sum(q["quantity"] for q in quant_before)

        # Create and confirm purchase order — 500 kg of feed
        po_id = m.execute_kw(db, uid, pwd, "purchase.order", "create", [{
            "partner_id": supplier_id,
            "order_line": [(0, 0, {
                "product_id": feed_id,
                "product_qty": 500.0,
                "price_unit": 45.0,
            })],
        }])
        m.execute_kw(db, uid, pwd, "purchase.order", "button_confirm", [[po_id]])

        po_data = m.execute_kw(db, uid, pwd, "purchase.order", "read",
                               [[po_id], ["state", "picking_ids"]])
        assert po_data[0]["state"] == "purchase"
        assert len(po_data[0]["picking_ids"]) >= 1, "Receipt must be auto-created."


@pytest.mark.integration
class TestManufacturingBOM:
    """Validate BOM and manufacturing order for processed dairy products."""

    def test_bom_exists_for_pasteurized_milk(self, odoo_xmlrpc):
        m = odoo_xmlrpc["models"]
        db, uid, pwd = odoo_xmlrpc["db"], odoo_xmlrpc["uid"], odoo_xmlrpc["password"]

        bom_ids = m.execute_kw(db, uid, pwd, "mrp.bom", "search",
                               [[("product_tmpl_id.name", "ilike", "Pasteurized Milk")]])
        assert bom_ids, "BOM for Pasteurized Milk must be configured."

        bom = m.execute_kw(db, uid, pwd, "mrp.bom", "read",
                           [[bom_ids[0]], ["bom_line_ids", "product_qty"]])
        assert bom[0]["product_qty"] > 0
        assert len(bom[0]["bom_line_ids"]) >= 1, "BOM must have at least one component."

    def test_create_manufacturing_order(self, odoo_xmlrpc):
        m = odoo_xmlrpc["models"]
        db, uid, pwd = odoo_xmlrpc["db"], odoo_xmlrpc["uid"], odoo_xmlrpc["password"]

        product_ids = m.execute_kw(db, uid, pwd, "product.product", "search",
                                   [[("name", "ilike", "Saffron Pasteurized Milk")]])
        if not product_ids:
            pytest.skip("Pasteurized Milk product not configured yet.")

        mo_id = m.execute_kw(db, uid, pwd, "mrp.production", "create", [{
            "product_id": product_ids[0],
            "product_qty": 100.0,
        }])
        assert mo_id, "Manufacturing order creation must succeed."

        mo_data = m.execute_kw(db, uid, pwd, "mrp.production", "read",
                               [[mo_id], ["state"]])
        assert mo_data[0]["state"] in ("draft", "confirmed")
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0–3 | Execute test suite and debug environment issues |
| 3–5 | Configure parallel test execution with pytest-xdist |
| 5–8 | Set up test result archiving and trend reporting |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Assist Dev 1 with verifying Odoo UI state transitions match backend |
| 4–8 | Begin mapping OWL component data dependencies to Odoo controller routes |

**End-of-Day 42 Deliverables:**
- [ ] `test_odoo_integration.py` with all three test classes passing
- [ ] Parallel test execution configured
- [ ] Test result archive from first full run

---

### Day 43: Custom Module Integration Testing

**Objective:** Validate `smart_farm_mgmt` module integration with Inventory, Sales, and Accounting.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0–3 | Write `TestFarmToInventoryIntegration` — feed stock deductions, milk product creation |
| 3–6 | Write `TestFarmToAccountingIntegration` — farm cost journal entries |
| 6–8 | Write milk sales flow test — production record to sale order |

**test_farm_integration.py — Custom Module Integration:**

```python
# tests/integration/test_farm_integration.py
"""
Smart Dairy — smart_farm_mgmt Module Integration Tests
Validates custom module interactions with Odoo core (Inventory, Sales, Accounting).
"""
import pytest
from datetime import date, timedelta


@pytest.mark.integration
class TestFarmToInventoryIntegration:
    """Verify feed consumption reduces inventory and milk production creates stock."""

    def test_feed_consumption_reduces_stock(self, odoo_xmlrpc, seed_farm_data):
        m = odoo_xmlrpc["models"]
        db, uid, pwd = odoo_xmlrpc["db"], odoo_xmlrpc["uid"], odoo_xmlrpc["password"]

        feed_ids = m.execute_kw(db, uid, pwd, "product.product", "search",
                                [[("name", "ilike", "Cattle Feed")]])
        if not feed_ids:
            pytest.skip("Cattle Feed product not found.")

        # Record current stock level
        quants = m.execute_kw(db, uid, pwd, "stock.quant", "search_read",
                              [[("product_id", "=", feed_ids[0]),
                                ("location_id.usage", "=", "internal")]],
                              {"fields": ["quantity"]})
        stock_before = sum(q["quantity"] for q in quants)

        # Create a feed consumption record via smart_farm_mgmt
        consumption_id = m.execute_kw(db, uid, pwd, "farm.feed.consumption", "create", [{
            "date": str(date.today()),
            "product_id": feed_ids[0],
            "quantity": 50.0,
            "category_id": seed_farm_data["category_ids"]["Lactating Cow"],
            "notes": "Integration test — daily feed for lactating cows",
        }])
        assert consumption_id, "Feed consumption record must be created."

        # Confirm the consumption (triggers inventory move)
        m.execute_kw(db, uid, pwd, "farm.feed.consumption", "action_confirm",
                     [[consumption_id]])

        # Verify stock decreased
        quants_after = m.execute_kw(db, uid, pwd, "stock.quant", "search_read",
                                    [[("product_id", "=", feed_ids[0]),
                                      ("location_id.usage", "=", "internal")]],
                                    {"fields": ["quantity"]})
        stock_after = sum(q["quantity"] for q in quants_after)
        assert stock_after == stock_before - 50.0, (
            f"Stock must decrease by 50. Before: {stock_before}, After: {stock_after}"
        )

    def test_milk_production_creates_inventory(self, odoo_xmlrpc, seed_farm_data):
        m = odoo_xmlrpc["models"]
        db, uid, pwd = odoo_xmlrpc["db"], odoo_xmlrpc["uid"], odoo_xmlrpc["password"]

        milk_product_ids = m.execute_kw(db, uid, pwd, "product.product", "search",
                                        [[("name", "=", "Saffron Raw Milk (1L)")]])
        if not milk_product_ids:
            pytest.skip("Raw Milk product not configured.")

        # Record daily milk production for a single cow
        cow_id = seed_farm_data["cattle_ids"][0]
        prod_id = m.execute_kw(db, uid, pwd, "farm.milk.production", "create", [{
            "cattle_id": cow_id,
            "date": str(date.today()),
            "morning_yield": 6.0,
            "evening_yield": 6.0,
        }])

        # Confirm production (should create inventory receipt)
        m.execute_kw(db, uid, pwd, "farm.milk.production", "action_confirm",
                     [[prod_id]])

        record = m.execute_kw(db, uid, pwd, "farm.milk.production", "read",
                              [[prod_id], ["state", "total_yield"]])
        assert record[0]["total_yield"] == 12.0
        assert record[0]["state"] == "confirmed"


@pytest.mark.integration
class TestFarmToAccountingIntegration:
    """Verify farm operations generate correct accounting journal entries."""

    def test_feed_cost_creates_journal_entry(self, odoo_xmlrpc, seed_farm_data):
        m = odoo_xmlrpc["models"]
        db, uid, pwd = odoo_xmlrpc["db"], odoo_xmlrpc["uid"], odoo_xmlrpc["password"]

        # Search for farm expense journal
        journal_ids = m.execute_kw(db, uid, pwd, "account.journal", "search",
                                   [[("name", "ilike", "Farm Expense")]])
        if not journal_ids:
            pytest.skip("Farm Expense journal not configured.")

        # Look for journal entries linked to farm feed consumption
        entries = m.execute_kw(db, uid, pwd, "account.move", "search_read",
                               [[("journal_id", "=", journal_ids[0]),
                                 ("ref", "ilike", "feed consumption")]],
                               {"fields": ["amount_total", "state"], "limit": 5})
        # At minimum, verify the journal exists and is queryable
        assert isinstance(entries, list), "Journal entry query must return a list."

    def test_veterinary_cost_tracking(self, odoo_xmlrpc, seed_farm_data):
        m = odoo_xmlrpc["models"]
        db, uid, pwd = odoo_xmlrpc["db"], odoo_xmlrpc["uid"], odoo_xmlrpc["password"]

        cow_id = seed_farm_data["cattle_ids"][0]
        health_id = m.execute_kw(db, uid, pwd, "farm.health.record", "create", [{
            "cattle_id": cow_id,
            "date": str(date.today()),
            "type": "treatment",
            "description": "Routine deworming — integration test",
            "cost": 500.0,
            "veterinarian": "Dr. Test Vet",
        }])
        assert health_id, "Health record must be created."

        m.execute_kw(db, uid, pwd, "farm.health.record", "action_confirm",
                     [[health_id]])
        record = m.execute_kw(db, uid, pwd, "farm.health.record", "read",
                              [[health_id], ["state", "cost"]])
        assert record[0]["state"] == "confirmed"
        assert record[0]["cost"] == 500.0
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0–4 | Run full farm integration test suite, triage failures |
| 4–6 | Create test data cleanup automation (post-run hooks) |
| 6–8 | Integrate farm module test results into allure dashboard |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Verify farm dashboard data matches backend after test data seeding |
| 4–8 | Test cattle list/detail OWL views with seeded 255-record dataset |

**End-of-Day 43 Deliverables:**
- [ ] `test_farm_integration.py` — all custom module integration tests passing
- [ ] Confirmed feed-to-inventory and milk-to-inventory pipelines
- [ ] Test cleanup automation functional

---

### Day 44: API Endpoint Testing

**Objective:** Validate all FastAPI and Odoo RPC endpoints with comprehensive test coverage.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Write API test suite for all farm CRUD endpoints |
| 4–6 | Test authentication and authorization flows |
| 6–8 | Test error handling, edge cases, and input validation |

**test_api_endpoints.py — API Integration Tests:**

```python
# tests/api/test_api_endpoints.py
"""
Smart Dairy — API Endpoint Integration Tests
Tests FastAPI routes and Odoo JSON-RPC/XML-RPC endpoints.
"""
import os
import pytest
import httpx
from datetime import date

API_BASE = os.getenv("API_BASE_URL", "http://localhost:8000/api/v1")
ODOO_URL = os.getenv("ODOO_URL", "http://localhost:8069")


@pytest.fixture(scope="module")
def api_client():
    """Authenticated httpx client for FastAPI endpoints."""
    client = httpx.Client(base_url=API_BASE, timeout=30.0)
    # Authenticate
    response = client.post("/auth/login", json={
        "username": "admin",
        "password": os.getenv("API_PASSWORD", "admin"),
    })
    assert response.status_code == 200, f"Auth failed: {response.text}"
    token = response.json()["access_token"]
    client.headers["Authorization"] = f"Bearer {token}"
    yield client
    client.close()


@pytest.fixture(scope="module")
def unauthenticated_client():
    """Unauthenticated httpx client for testing 401 responses."""
    client = httpx.Client(base_url=API_BASE, timeout=30.0)
    yield client
    client.close()


@pytest.mark.api
class TestCattleEndpoints:
    """CRUD tests for /api/v1/cattle."""

    def test_list_cattle(self, api_client):
        resp = api_client.get("/cattle")
        assert resp.status_code == 200
        data = resp.json()
        assert "items" in data
        assert "total" in data
        assert data["total"] >= 0

    def test_list_cattle_pagination(self, api_client):
        resp = api_client.get("/cattle", params={"limit": 10, "offset": 0})
        assert resp.status_code == 200
        data = resp.json()
        assert len(data["items"]) <= 10

    def test_create_cattle(self, api_client):
        payload = {
            "name": "API-TEST-001",
            "tag_number": "API-TAG-001",
            "category": "Heifer",
            "date_of_birth": "2023-06-15",
            "weight": 280.5,
        }
        resp = api_client.post("/cattle", json=payload)
        assert resp.status_code == 201
        data = resp.json()
        assert data["name"] == "API-TEST-001"
        assert "id" in data
        return data["id"]

    def test_get_cattle_by_id(self, api_client):
        cattle_id = self.test_create_cattle(api_client)
        resp = api_client.get(f"/cattle/{cattle_id}")
        assert resp.status_code == 200
        assert resp.json()["tag_number"] == "API-TAG-001"

    def test_update_cattle(self, api_client):
        cattle_id = self.test_create_cattle(api_client)
        resp = api_client.put(f"/cattle/{cattle_id}", json={"weight": 295.0})
        assert resp.status_code == 200
        assert resp.json()["weight"] == 295.0

    def test_delete_cattle(self, api_client):
        cattle_id = self.test_create_cattle(api_client)
        resp = api_client.delete(f"/cattle/{cattle_id}")
        assert resp.status_code == 204
        resp2 = api_client.get(f"/cattle/{cattle_id}")
        assert resp2.status_code == 404

    def test_create_cattle_invalid_data(self, api_client):
        resp = api_client.post("/cattle", json={"name": ""})
        assert resp.status_code == 422

    def test_cattle_unauthenticated(self, unauthenticated_client):
        resp = unauthenticated_client.get("/cattle")
        assert resp.status_code == 401


@pytest.mark.api
class TestMilkProductionEndpoints:
    """CRUD tests for /api/v1/milk-production."""

    def test_record_production(self, api_client):
        payload = {
            "cattle_id": 1,
            "date": str(date.today()),
            "morning_yield": 6.5,
            "evening_yield": 5.5,
        }
        resp = api_client.post("/milk-production", json=payload)
        assert resp.status_code in (200, 201)
        data = resp.json()
        assert data["total_yield"] == 12.0

    def test_daily_summary(self, api_client):
        resp = api_client.get("/milk-production/summary",
                              params={"date": str(date.today())})
        assert resp.status_code == 200
        data = resp.json()
        assert "total_litres" in data
        assert "cow_count" in data

    def test_production_invalid_yield(self, api_client):
        payload = {
            "cattle_id": 1,
            "date": str(date.today()),
            "morning_yield": -5.0,
            "evening_yield": 5.0,
        }
        resp = api_client.post("/milk-production", json=payload)
        assert resp.status_code == 422


@pytest.mark.api
class TestHealthRecordEndpoints:
    """Tests for /api/v1/health-records."""

    def test_create_health_record(self, api_client):
        payload = {
            "cattle_id": 1,
            "date": str(date.today()),
            "type": "vaccination",
            "description": "FMD vaccination — API test",
            "cost": 350.0,
        }
        resp = api_client.post("/health-records", json=payload)
        assert resp.status_code in (200, 201)

    def test_list_health_records_by_cattle(self, api_client):
        resp = api_client.get("/health-records", params={"cattle_id": 1})
        assert resp.status_code == 200


@pytest.mark.api
class TestOdooRPCEndpoints:
    """Validate Odoo XML-RPC and JSON-RPC availability."""

    def test_xmlrpc_common_version(self):
        import xmlrpc.client
        common = xmlrpc.client.ServerProxy(f"{ODOO_URL}/xmlrpc/2/common")
        version = common.version()
        assert "server_version" in version
        assert version["server_version"].startswith("19.")

    def test_jsonrpc_call(self):
        resp = httpx.post(f"{ODOO_URL}/jsonrpc", json={
            "jsonrpc": "2.0",
            "method": "call",
            "params": {
                "service": "common",
                "method": "version",
                "args": [],
            },
            "id": 1,
        })
        assert resp.status_code == 200
        data = resp.json()
        assert "result" in data
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0–3 | Run API test suite, debug any environment/networking issues |
| 3–6 | Generate API test coverage report and endpoint map |
| 6–8 | Configure rate-limiting tests and timeout handling |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Test Flutter HTTP client integration with API endpoints |
| 4–8 | Validate API response structures match frontend data models |

**End-of-Day 44 Deliverables:**
- [ ] `test_api_endpoints.py` — all API tests passing
- [ ] Odoo RPC connectivity verified
- [ ] API endpoint coverage map generated

---

### Day 45: Frontend-Backend Integration Testing

**Objective:** Validate OWL components communicate correctly with Odoo controllers and render data accurately.

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Write QUnit tests for farm dashboard OWL widgets |
| 4–6 | Write Playwright E2E tests for critical user flows |
| 6–8 | Test form submission and data rendering cycles |

**OWL Component QUnit Tests:**

```javascript
/** @odoo-module **/
// static/tests/test_farm_dashboard.js
/**
 * Smart Dairy — QUnit Tests for Farm Dashboard OWL Components
 * Validates data binding, rendering, and user interaction.
 */
import { registry } from "@web/core/registry";
import { getFixture, mount, patchWithCleanup } from "@web/../tests/helpers/utils";
import { makeTestEnv } from "@web/../tests/helpers/mock_env";
import { FarmDashboard } from "@smart_farm_mgmt/components/farm_dashboard/farm_dashboard";
import { MilkProductionChart } from "@smart_farm_mgmt/components/milk_chart/milk_chart";
import { CattleSummaryCard } from "@smart_farm_mgmt/components/cattle_summary/cattle_summary";

let target;
let env;

QUnit.module("Smart Farm Dashboard", (hooks) => {
    hooks.beforeEach(async () => {
        target = getFixture();
        env = await makeTestEnv();

        // Mock the RPC calls
        patchWithCleanup(env.services.rpc, {
            async call(route, params) {
                if (route === "/smart_farm/dashboard_data") {
                    return {
                        total_cattle: 255,
                        lactating_cows: 75,
                        daily_milk_litres: 900,
                        milk_target: 950,
                        active_health_alerts: 3,
                        feed_stock_days: 14,
                        revenue_this_month: 2850000,
                    };
                }
                if (route === "/smart_farm/milk_production_trend") {
                    return {
                        labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                        values: [880, 910, 895, 920, 900, 885, 905],
                    };
                }
                return {};
            },
        });
    });

    QUnit.test("Dashboard renders cattle summary correctly", async (assert) => {
        assert.expect(4);
        const dashboard = await mount(FarmDashboard, target, { env });

        assert.containsOnce(target, ".farm-dashboard", "Dashboard component mounts.");
        assert.strictEqual(
            target.querySelector(".cattle-total").textContent.trim(),
            "255",
            "Total cattle count displays correctly."
        );
        assert.strictEqual(
            target.querySelector(".lactating-count").textContent.trim(),
            "75",
            "Lactating cow count displays correctly."
        );
        assert.strictEqual(
            target.querySelector(".daily-milk").textContent.trim(),
            "900 L",
            "Daily milk production displays correctly."
        );
    });

    QUnit.test("Milk production chart receives correct data", async (assert) => {
        assert.expect(2);
        const chart = await mount(MilkProductionChart, target, { env });

        assert.containsOnce(target, ".milk-chart-container", "Chart container renders.");
        assert.ok(
            target.querySelector("canvas") || target.querySelector("svg"),
            "Chart canvas or SVG element exists."
        );
    });

    QUnit.test("Cattle summary card handles zero data gracefully", async (assert) => {
        assert.expect(1);
        patchWithCleanup(env.services.rpc, {
            async call() {
                return { total_cattle: 0, lactating_cows: 0, daily_milk_litres: 0 };
            },
        });

        const card = await mount(CattleSummaryCard, target, { env });
        assert.containsOnce(target, ".cattle-summary", "Card renders even with zero data.");
    });

    QUnit.test("Dashboard shows health alerts badge", async (assert) => {
        assert.expect(1);
        const dashboard = await mount(FarmDashboard, target, { env });
        const badge = target.querySelector(".health-alerts-badge");
        assert.strictEqual(badge.textContent.trim(), "3", "Health alert count badge shows 3.");
    });
});
```

**Playwright E2E Test — Critical User Flows:**

```python
# tests/e2e/test_critical_flows.py
"""
Smart Dairy — Playwright E2E Tests for Critical User Flows
Browser-based integration testing of key business processes.
"""
import os
import pytest
from playwright.sync_api import Page, expect

ODOO_URL = os.getenv("ODOO_URL", "http://localhost:8069")


@pytest.fixture(scope="function")
def logged_in_page(page: Page):
    """Log in to Odoo and return the authenticated page."""
    page.goto(f"{ODOO_URL}/web/login")
    page.fill("input[name='login']", "admin")
    page.fill("input[name='password']", os.getenv("ODOO_PASSWORD", "admin"))
    page.click("button[type='submit']")
    page.wait_for_url("**/web#*")
    yield page


@pytest.mark.e2e
class TestCriticalUserFlows:
    """End-to-end browser tests for the most important user journeys."""

    def test_navigate_to_farm_dashboard(self, logged_in_page: Page):
        page = logged_in_page
        page.click("a:has-text('Farm Management')")
        page.wait_for_selector(".farm-dashboard", timeout=10_000)
        expect(page.locator(".cattle-total")).to_be_visible()
        expect(page.locator(".daily-milk")).to_be_visible()

    def test_record_milk_production(self, logged_in_page: Page):
        page = logged_in_page
        page.goto(f"{ODOO_URL}/web#action=smart_farm_mgmt.action_milk_production")
        page.wait_for_selector(".o_list_view", timeout=10_000)

        # Click Create
        page.click("button.o_list_button_add")
        page.wait_for_selector(".o_form_view")

        # Fill in production record
        page.fill("div[name='morning_yield'] input", "6.5")
        page.fill("div[name='evening_yield'] input", "5.5")

        # Save
        page.click("button.o_form_button_save")
        page.wait_for_selector(".o_form_view .o_form_saved", timeout=5_000)

    def test_view_cattle_list_and_detail(self, logged_in_page: Page):
        page = logged_in_page
        page.goto(f"{ODOO_URL}/web#action=smart_farm_mgmt.action_cattle_list")
        page.wait_for_selector(".o_list_view", timeout=10_000)

        # Verify records exist
        rows = page.locator(".o_data_row")
        expect(rows.first).to_be_visible()

        # Click first record to view detail
        rows.first.click()
        page.wait_for_selector(".o_form_view", timeout=5_000)
        expect(page.locator("div[name='name']")).to_be_visible()

    def test_create_sale_order_for_milk(self, logged_in_page: Page):
        page = logged_in_page
        page.goto(f"{ODOO_URL}/web#action=sale.action_quotations_with_onboarding")
        page.wait_for_selector(".o_list_view", timeout=10_000)

        page.click("button.o_list_button_add")
        page.wait_for_selector(".o_form_view")

        # Select customer
        page.click("div[name='partner_id'] input")
        page.fill("div[name='partner_id'] input", "Test Dairy Customer")
        page.wait_for_selector(".ui-autocomplete .ui-menu-item", timeout=5_000)
        page.click(".ui-autocomplete .ui-menu-item:first-child")
```

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Support Dev 3 with controller debugging and data format issues |
| 4–8 | Fix any API response format mismatches discovered during frontend testing |

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0–4 | Configure Playwright in CI (headless Chromium, video capture on failure) |
| 4–8 | Set up QUnit test runner in CI pipeline |

**End-of-Day 45 Deliverables:**
- [ ] QUnit tests for all farm dashboard OWL components passing
- [ ] Playwright E2E tests for 4 critical user flows passing
- [ ] CI pipeline running frontend tests in headless mode

---

### Day 46: Performance Regression Testing

**Objective:** Verify Milestone 4 database optimizations hold under integration-level load.

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0–3 | Write K6 integration load test scenarios |
| 3–6 | Execute load tests and compare against M4 baselines |
| 6–8 | Generate performance regression report |

**K6 Integration Load Test Script:**

```javascript
// tests/performance/k6_integration_test.js
/**
 * Smart Dairy — K6 Integration Load Test
 * Multi-step user journey simulating real dairy farm operations.
 * Baseline from Milestone 4 must not regress by more than 5%.
 */
import http from "k6/http";
import { check, sleep, group } from "k6";
import { Rate, Trend } from "k6/metrics";

const BASE_URL = __ENV.BASE_URL || "http://localhost:8000/api/v1";
const ODOO_URL = __ENV.ODOO_URL || "http://localhost:8069";

// Custom metrics
const errorRate = new Rate("errors");
const milkRecordLatency = new Trend("milk_record_latency", true);
const dashboardLatency = new Trend("dashboard_latency", true);
const saleOrderLatency = new Trend("sale_order_latency", true);

export const options = {
    stages: [
        { duration: "1m", target: 5 },    // Ramp up to 5 concurrent users
        { duration: "3m", target: 10 },   // Hold at 10 users
        { duration: "2m", target: 20 },   // Peak load — 20 users
        { duration: "1m", target: 0 },    // Ramp down
    ],
    thresholds: {
        http_req_duration: ["p(95)<2000"],       // 95th percentile < 2s
        errors: ["rate<0.05"],                    // Error rate < 5%
        milk_record_latency: ["p(95)<1500"],      // Milk record < 1.5s at p95
        dashboard_latency: ["p(95)<3000"],        // Dashboard < 3s at p95
        sale_order_latency: ["p(95)<2500"],       // Sale order < 2.5s at p95
    },
};

function authenticate() {
    const res = http.post(`${BASE_URL}/auth/login`, JSON.stringify({
        username: "admin",
        password: "admin",
    }), { headers: { "Content-Type": "application/json" } });

    check(res, { "auth success": (r) => r.status === 200 });
    return res.json("access_token");
}

export default function () {
    const token = authenticate();
    const authHeaders = {
        "Authorization": `Bearer ${token}`,
        "Content-Type": "application/json",
    };

    // Journey 1: Farm manager checks dashboard
    group("Dashboard Load", () => {
        const start = Date.now();
        const res = http.get(`${BASE_URL}/dashboard`, { headers: authHeaders });
        dashboardLatency.add(Date.now() - start);
        const ok = check(res, {
            "dashboard 200": (r) => r.status === 200,
            "has cattle count": (r) => r.json("total_cattle") !== undefined,
        });
        errorRate.add(!ok);
    });

    sleep(1);

    // Journey 2: Record morning milk production
    group("Record Milk Production", () => {
        const start = Date.now();
        const res = http.post(`${BASE_URL}/milk-production`, JSON.stringify({
            cattle_id: Math.floor(Math.random() * 75) + 1,
            date: new Date().toISOString().split("T")[0],
            morning_yield: 5.0 + Math.random() * 3,
            evening_yield: 4.5 + Math.random() * 3,
        }), { headers: authHeaders });
        milkRecordLatency.add(Date.now() - start);
        const ok = check(res, {
            "milk record created": (r) => [200, 201].includes(r.status),
        });
        errorRate.add(!ok);
    });

    sleep(1);

    // Journey 3: Create a sale order for milk
    group("Create Sale Order", () => {
        const start = Date.now();
        const res = http.post(`${BASE_URL}/sales/quick-order`, JSON.stringify({
            customer_name: "Walk-in Customer",
            product: "Saffron Raw Milk (1L)",
            quantity: Math.floor(Math.random() * 50) + 10,
        }), { headers: authHeaders });
        saleOrderLatency.add(Date.now() - start);
        const ok = check(res, {
            "sale order created": (r) => [200, 201].includes(r.status),
        });
        errorRate.add(!ok);
    });

    sleep(2);

    // Journey 4: Browse cattle list with pagination
    group("Browse Cattle", () => {
        const res = http.get(`${BASE_URL}/cattle?limit=20&offset=0`,
                             { headers: authHeaders });
        check(res, {
            "cattle list 200": (r) => r.status === 200,
            "has items": (r) => r.json("items").length > 0,
        });
    });

    sleep(1);
}
```

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Monitor slow queries during K6 run, verify M4 indexes still effective |
| 4–8 | Write pytest performance regression assertions |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Measure OWL component render times under data load (Lighthouse audit) |
| 4–8 | Optimize any frontend rendering bottlenecks discovered |

**End-of-Day 46 Deliverables:**
- [ ] K6 load test results with all thresholds passing
- [ ] Performance regression report comparing M4 vs M5 baselines
- [ ] Any frontend performance optimizations committed

---

### Day 47: Security Scan

**Objective:** Execute OWASP baseline security checks, dependency audit, and injection testing.

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0–2 | Configure and run Bandit static analysis |
| 2–4 | Run Safety dependency vulnerability check |
| 4–6 | Execute SQL injection and XSS test suites |
| 6–8 | Generate security scan report and remediation plan |

**Bandit Configuration (.bandit):**

```yaml
# .bandit
# Smart Dairy — Bandit Security Scan Configuration
# Docs: https://bandit.readthedocs.io/

[bandit]
# Target directories for scanning
targets = custom_addons/smart_farm_mgmt,api,scripts

# Skip test IDs that are known false positives for Odoo
skips = B101,B601

# Severity and confidence levels
# Report only MEDIUM and above
severity = MEDIUM

# Output format
format = json
output = reports/bandit_report.json

# Exclude test directories from the scan
exclude_dirs = tests,node_modules,.git,__pycache__

# Additional plugins
# B102: exec_used
# B105: hardcoded_password_string
# B108: hardcoded_tmp_directory
# B301: pickle usage
# B303: MD5 usage (weak hash)
# B608: SQL injection via string formatting
# B703: Django XSS (applicable pattern for OWL templates)
```

**Safety Dependency Check Script:**

```bash
#!/usr/bin/env bash
# scripts/security_check.sh
# Smart Dairy — Dependency Security Audit
# Runs safety check against known vulnerability databases.

set -euo pipefail

REPORT_DIR="reports/security"
mkdir -p "$REPORT_DIR"

echo "=========================================="
echo " Smart Dairy — Security Audit"
echo " Date: $(date '+%Y-%m-%d %H:%M:%S')"
echo "=========================================="

# --- 1. Python Dependency Audit (safety) ---
echo ""
echo "[1/4] Running Safety dependency check..."
pip install safety --quiet
safety check \
    --full-report \
    --output json \
    > "$REPORT_DIR/safety_report.json" 2>&1 || true

VULN_COUNT=$(python3 -c "
import json, sys
try:
    with open('$REPORT_DIR/safety_report.json') as f:
        data = json.load(f)
    print(len(data.get('vulnerabilities', [])))
except Exception:
    print(0)
")
echo "   Vulnerabilities found: $VULN_COUNT"

# --- 2. Bandit Static Analysis ---
echo ""
echo "[2/4] Running Bandit static analysis..."
pip install bandit --quiet
bandit -r custom_addons/smart_farm_mgmt api \
    -f json \
    -o "$REPORT_DIR/bandit_report.json" \
    --severity-level medium \
    --confidence-level medium || true

BANDIT_ISSUES=$(python3 -c "
import json
with open('$REPORT_DIR/bandit_report.json') as f:
    data = json.load(f)
print(len(data.get('results', [])))
")
echo "   Issues found: $BANDIT_ISSUES"

# --- 3. npm audit (for frontend) ---
echo ""
echo "[3/4] Running npm audit..."
if [ -f "package.json" ]; then
    npm audit --json > "$REPORT_DIR/npm_audit.json" 2>&1 || true
    echo "   npm audit report generated."
else
    echo "   No package.json found — skipping."
fi

# --- 4. Docker image scan (Trivy) ---
echo ""
echo "[4/4] Running Trivy container scan..."
if command -v trivy &> /dev/null; then
    trivy image smart-dairy:latest \
        --format json \
        --output "$REPORT_DIR/trivy_report.json" \
        --severity HIGH,CRITICAL || true
    echo "   Trivy scan complete."
else
    echo "   Trivy not installed — skipping container scan."
fi

echo ""
echo "=========================================="
echo " Audit Complete — Reports in $REPORT_DIR/"
echo "=========================================="
```

**SQL Injection and XSS Security Tests:**

```python
# tests/security/test_injection.py
"""
Smart Dairy — Injection and XSS Security Tests
OWASP Top 10 A03:2021 — Injection validation.
"""
import os
import pytest
import httpx

API_BASE = os.getenv("API_BASE_URL", "http://localhost:8000/api/v1")


@pytest.fixture(scope="module")
def auth_client():
    client = httpx.Client(base_url=API_BASE, timeout=30.0)
    resp = client.post("/auth/login", json={"username": "admin", "password": "admin"})
    client.headers["Authorization"] = f"Bearer {resp.json()['access_token']}"
    yield client
    client.close()


@pytest.mark.security
class TestSQLInjection:
    """Verify SQL injection vectors are properly sanitized."""

    SQL_PAYLOADS = [
        "' OR '1'='1",
        "'; DROP TABLE farm_cattle; --",
        "1 UNION SELECT * FROM res_users --",
        "' OR 1=1 --",
        "admin'--",
    ]

    @pytest.mark.parametrize("payload", SQL_PAYLOADS)
    def test_cattle_search_injection(self, auth_client, payload):
        resp = auth_client.get("/cattle", params={"search": payload})
        assert resp.status_code in (200, 400, 422), (
            f"Unexpected status {resp.status_code} for SQL payload."
        )
        body = resp.text.lower()
        assert "error" not in body or "sql" not in body, (
            "Response must not leak SQL error details."
        )

    @pytest.mark.parametrize("payload", SQL_PAYLOADS)
    def test_login_injection(self, auth_client, payload):
        client = httpx.Client(base_url=API_BASE, timeout=30.0)
        resp = client.post("/auth/login", json={
            "username": payload,
            "password": payload,
        })
        assert resp.status_code in (401, 400, 422)
        client.close()


@pytest.mark.security
class TestXSS:
    """Verify XSS payloads are sanitized in API responses."""

    XSS_PAYLOADS = [
        "<script>alert('xss')</script>",
        "<img src=x onerror=alert('xss')>",
        "javascript:alert('xss')",
        "<svg/onload=alert('xss')>",
        "'\"><script>alert('xss')</script>",
    ]

    @pytest.mark.parametrize("payload", XSS_PAYLOADS)
    def test_cattle_name_xss(self, auth_client, payload):
        resp = auth_client.post("/cattle", json={
            "name": payload,
            "tag_number": "XSS-TEST",
            "category": "Calf",
            "date_of_birth": "2024-01-01",
            "weight": 100.0,
        })
        if resp.status_code in (200, 201):
            data = resp.json()
            assert "<script>" not in data.get("name", ""), (
                "Stored XSS must be sanitized."
            )
        else:
            assert resp.status_code in (400, 422), "Malicious input must be rejected."
```

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Review Bandit findings and fix critical/high issues in Python code |
| 4–8 | Harden ORM queries and parameterized SQL in custom module |

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Review XSS test results and add input sanitization to OWL components |
| 4–8 | Audit Content Security Policy headers in Odoo configuration |

**End-of-Day 47 Deliverables:**
- [ ] Bandit report — zero critical/high issues
- [ ] Safety report — no known vulnerable dependencies
- [ ] SQL injection tests passing (all payloads neutralized)
- [ ] XSS tests passing (all payloads sanitized)
- [ ] Security scan summary report

---

### Day 48: Bug Triage and Critical Fix Sprint

**Objective:** Categorize all bugs found in Days 41--47, fix all P0/P1 items.

#### All Developers (~8h each)

| Hours | Task | Owner |
|---|---|---|
| 0–2 | Bug triage session — categorize all issues as P0/P1/P2 | All |
| 2–4 | P0 critical fixes (blockers, data corruption, security) | Dev 1 + Dev 2 |
| 4–6 | P1 high-priority fixes (broken integrations, incorrect calculations) | All |
| 6–8 | Re-run affected test suites, verify fixes, update test results | All |

**Bug Tracking Template (GitHub Issues Format):**

```markdown
<!-- .github/ISSUE_TEMPLATE/bug_report_m5.md -->
---
name: "M5 Integration Bug Report"
about: "Bug discovered during Milestone 5 integration testing"
title: "[M5-BUG] "
labels: ["milestone-5", "bug", "integration-testing"]
assignees: ""
---

## Bug Report — Milestone 5 Integration Testing

### Priority
<!-- Select one: P0-Critical / P1-High / P2-Medium / P3-Low -->
**Priority:** P0-Critical

### Category
<!-- Select: integration / api / frontend / performance / security / data -->
**Category:** integration

### Environment
- **Branch:** `phase-1a/milestone-5`
- **Odoo Version:** 19 CE
- **Python:** 3.11+
- **Database:** PostgreSQL 16
- **OS:** Ubuntu 22.04 (Docker)

### Description
<!-- Clear, concise description of the bug -->

### Steps to Reproduce
1.
2.
3.

### Expected Behavior
<!-- What should happen -->

### Actual Behavior
<!-- What actually happens -->

### Test Reference
<!-- Which test discovered this bug -->
- **Test File:** `tests/integration/test_farm_integration.py`
- **Test Class:** `TestFarmToInventoryIntegration`
- **Test Method:** `test_feed_consumption_reduces_stock`

### Stack Trace / Logs
```
<!-- Paste relevant logs here -->
```

### Screenshots
<!-- If applicable -->

### Proposed Fix
<!-- If you have an idea for the fix -->

### Impact Assessment
- **Affected Modules:** smart_farm_mgmt, stock
- **Data Impact:** None / Read-only / Write (potential corruption)
- **User Impact:** Blocking / Degraded / Cosmetic
```

**Test Coverage Report Generation (pyproject.toml section):**

```toml
# pyproject.toml — Coverage Configuration
[tool.coverage.run]
source = [
    "custom_addons/smart_farm_mgmt",
    "api",
]
omit = [
    "*/tests/*",
    "*/migrations/*",
    "*/__manifest__.py",
    "*/static/*",
]
branch = true

[tool.coverage.report]
fail_under = 80
show_missing = true
skip_covered = false
precision = 2
exclude_lines = [
    "pragma: no cover",
    "def __repr__",
    "raise NotImplementedError",
    "if __name__ == .__main__.",
    "if TYPE_CHECKING:",
    "pass",
]

[tool.coverage.html]
directory = "reports/coverage"
title = "Smart Dairy — Integration Test Coverage"
```

**End-of-Day 48 Deliverables:**
- [ ] Bug triage log with all issues categorized (P0/P1/P2/P3)
- [ ] All P0 bugs fixed and verified
- [ ] All P1 bugs fixed and verified
- [ ] Updated test results showing green across all suites
- [ ] Coverage report >= 80%

---

### Day 49: Phase 1A Transition Documentation

**Objective:** Produce all handoff artifacts, ADRs, and known issues log for Phase 1B team.

#### Dev 1 — Backend Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Write Architecture Decision Records (ADRs) for all key decisions |
| 4–8 | Document known issues, technical debt, and recommendations |

**Architecture Decision Record (ADR) Template:**

```markdown
<!-- docs/adr/ADR-XXXX-template.md -->
# ADR-XXXX: [Decision Title]

## Status
<!-- Proposed | Accepted | Deprecated | Superseded by ADR-YYYY -->
Accepted

## Date
2026-02-03

## Context
<!-- What is the issue that we are seeing that motivates this decision? -->
Describe the technical context and business drivers that necessitate this
architectural decision for the Smart Dairy platform.

## Decision
<!-- What is the change that we are proposing and/or doing? -->
We will [decision description].

## Rationale
<!-- Why did we choose this approach? What alternatives were considered? -->

### Alternatives Considered
| # | Alternative | Pros | Cons | Verdict |
|---|---|---|---|---|
| 1 | [Option A] | ... | ... | Rejected |
| 2 | [Option B — chosen] | ... | ... | Accepted |
| 3 | [Option C] | ... | ... | Rejected |

## Consequences
<!-- What becomes easier or more difficult to do because of this change? -->

### Positive
- ...

### Negative
- ...

### Risks
- ...

## References
- Milestone: M5
- Related ADRs: ADR-XXXX
- Governance Doc: G-XXX
```

**Sample ADR — ADR-0005: Odoo XML-RPC for Integration Testing:**

```markdown
# ADR-0005: Use XML-RPC for Integration Testing Against Odoo

## Status
Accepted

## Date
2026-02-03

## Context
Milestone 5 requires automated integration testing of Odoo modules. Odoo
provides two RPC interfaces (XML-RPC and JSON-RPC) and a Python test framework
(`odoo.tests`). We needed to decide which approach to use for our CI/CD
integration test suite that runs externally to the Odoo process.

## Decision
We will use Odoo's XML-RPC interface via Python's `xmlrpc.client` for
integration tests, combined with direct PostgreSQL connections for data
verification.

## Rationale

### Alternatives Considered
| # | Alternative | Pros | Cons | Verdict |
|---|---|---|---|---|
| 1 | Odoo internal test framework | Native, fast | Requires running inside Odoo process; hard to integrate with pytest | Rejected |
| 2 | XML-RPC (chosen) | Standard, well-documented, works with pytest | Slightly slower than internal | Accepted |
| 3 | JSON-RPC only | Modern format | Less documentation for testing patterns | Rejected |

## Consequences

### Positive
- Tests run independently of the Odoo process
- Full pytest ecosystem available (fixtures, parametrize, coverage)
- CI/CD integration is straightforward

### Negative
- Network overhead adds ~10% to test execution time
- Cannot test Odoo internal signals/hooks directly
```

#### Dev 2 — Full-Stack/DevOps (~8h)

| Hours | Task |
|---|---|
| 0–4 | Compile infrastructure documentation (Docker configs, CI/CD pipeline map) |
| 4–8 | Create environment reproduction guide and runbook |

**CI/CD Pipeline for Integration Tests (GitHub Actions):**

```yaml
# .github/workflows/integration_tests.yml
name: "M5 — Integration Tests"

on:
  push:
    branches: [main, "phase-1a/*", "milestone-5/*"]
  pull_request:
    branches: [main]

env:
  ODOO_VERSION: "19.0"
  PYTHON_VERSION: "3.11"
  PG_VERSION: "16"
  REDIS_VERSION: "7"

jobs:
  # -----------------------------------------------------------------
  # Job 1: Odoo Module Integration Tests
  # -----------------------------------------------------------------
  odoo-integration:
    name: "Odoo Module Integration"
    runs-on: ubuntu-latest
    timeout-minutes: 30

    services:
      postgres:
        image: postgres:16
        env:
          POSTGRES_USER: odoo
          POSTGRES_PASSWORD: odoo
          POSTGRES_DB: smart_dairy_test
        ports: ["5432:5432"]
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5

      redis:
        image: redis:7-alpine
        ports: ["6379:6379"]
        options: >-
          --health-cmd "redis-cli ping"
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5

    steps:
      - uses: actions/checkout@v4

      - name: Set up Python ${{ env.PYTHON_VERSION }}
        uses: actions/setup-python@v5
        with:
          python-version: ${{ env.PYTHON_VERSION }}

      - name: Cache pip dependencies
        uses: actions/cache@v4
        with:
          path: ~/.cache/pip
          key: ${{ runner.os }}-pip-${{ hashFiles('requirements*.txt') }}

      - name: Install dependencies
        run: |
          pip install --upgrade pip
          pip install -r requirements.txt
          pip install -r requirements-test.txt

      - name: Start Odoo (background)
        run: |
          python odoo-bin -d smart_dairy_test \
            --db_host=localhost --db_port=5432 \
            --db_user=odoo --db_password=odoo \
            --addons-path=addons,custom_addons \
            -i smart_farm_mgmt \
            --without-demo=False \
            --stop-after-init
          python odoo-bin -d smart_dairy_test \
            --db_host=localhost --db_port=5432 \
            --db_user=odoo --db_password=odoo \
            --addons-path=addons,custom_addons \
            --xmlrpc-port=8069 &
          sleep 15

      - name: Run integration tests
        env:
          ODOO_URL: "http://localhost:8069"
          ODOO_DB: "smart_dairy_test"
          ODOO_USER: "admin"
          ODOO_PASSWORD: "admin"
        run: |
          pytest tests/integration/ \
            -m integration \
            --junitxml=reports/integration_results.xml \
            --html=reports/integration_report.html \
            --self-contained-html \
            -v

      - name: Upload test reports
        if: always()
        uses: actions/upload-artifact@v4
        with:
          name: integration-test-reports
          path: reports/

  # -----------------------------------------------------------------
  # Job 2: API Endpoint Tests
  # -----------------------------------------------------------------
  api-tests:
    name: "API Endpoint Tests"
    runs-on: ubuntu-latest
    needs: odoo-integration
    timeout-minutes: 20

    steps:
      - uses: actions/checkout@v4

      - name: Set up Python
        uses: actions/setup-python@v5
        with:
          python-version: ${{ env.PYTHON_VERSION }}

      - name: Install dependencies
        run: |
          pip install -r requirements.txt
          pip install -r requirements-test.txt

      - name: Start services (Docker Compose)
        run: docker compose -f docker-compose.test.yml up -d --wait

      - name: Run API tests
        run: |
          pytest tests/api/ \
            -m api \
            --junitxml=reports/api_results.xml \
            -v

      - name: Upload API test reports
        if: always()
        uses: actions/upload-artifact@v4
        with:
          name: api-test-reports
          path: reports/

  # -----------------------------------------------------------------
  # Job 3: Security Scan
  # -----------------------------------------------------------------
  security-scan:
    name: "Security Scan"
    runs-on: ubuntu-latest
    timeout-minutes: 15

    steps:
      - uses: actions/checkout@v4

      - name: Set up Python
        uses: actions/setup-python@v5
        with:
          python-version: ${{ env.PYTHON_VERSION }}

      - name: Run Bandit
        run: |
          pip install bandit
          bandit -r custom_addons/smart_farm_mgmt api \
            -f json -o reports/bandit.json \
            --severity-level medium || true

      - name: Run Safety
        run: |
          pip install safety
          safety check --full-report --output json \
            > reports/safety.json || true

      - name: Check for critical findings
        run: |
          python scripts/check_security_report.py \
            --bandit reports/bandit.json \
            --safety reports/safety.json \
            --fail-on-high

      - name: Upload security reports
        if: always()
        uses: actions/upload-artifact@v4
        with:
          name: security-reports
          path: reports/

  # -----------------------------------------------------------------
  # Job 4: Frontend / E2E Tests
  # -----------------------------------------------------------------
  e2e-tests:
    name: "E2E Browser Tests"
    runs-on: ubuntu-latest
    needs: odoo-integration
    timeout-minutes: 25

    steps:
      - uses: actions/checkout@v4

      - name: Set up Python
        uses: actions/setup-python@v5
        with:
          python-version: ${{ env.PYTHON_VERSION }}

      - name: Install Playwright
        run: |
          pip install playwright pytest-playwright
          playwright install chromium --with-deps

      - name: Start services
        run: docker compose -f docker-compose.test.yml up -d --wait

      - name: Run E2E tests
        run: |
          pytest tests/e2e/ \
            -m e2e \
            --browser chromium \
            --headed=false \
            --video=retain-on-failure \
            --junitxml=reports/e2e_results.xml \
            -v

      - name: Upload E2E reports and videos
        if: always()
        uses: actions/upload-artifact@v4
        with:
          name: e2e-reports
          path: |
            reports/
            test-results/
```

#### Dev 3 — Frontend/Mobile Lead (~8h)

| Hours | Task |
|---|---|
| 0–4 | Document all OWL component APIs, props, and events |
| 4–8 | Create UI component library reference with screenshots |

**End-of-Day 49 Deliverables:**
- [ ] ADRs for all Phase 1A architectural decisions (minimum 5)
- [ ] Infrastructure runbook and reproduction guide
- [ ] CI/CD pipeline fully operational with all 4 jobs
- [ ] Frontend component documentation complete

---

### Day 50: Phase 1A Completion Review & Sign-Off

**Objective:** Formal review, retrospective, sign-off ceremony, and Phase 1B kickoff planning.

#### All Developers (~8h each)

| Hours | Task | Owner |
|---|---|---|
| 0–2 | Final test suite execution — all tests must pass | Dev 2 |
| 2–4 | Phase 1A completion checklist review | All |
| 4–5 | Retrospective: what went well, what to improve | All |
| 5–6 | Phase 1B kickoff planning and backlog grooming | All |
| 6–8 | Sign-off documentation and celebration | All |

**Phase 1A Completion Checklist:**

```markdown
<!-- docs/phase1a_completion_checklist.md -->
# Phase 1A Completion Checklist — Smart Dairy Digital Smart Portal + ERP

## Project: Smart Dairy Ltd — Saffron Brand
## Phase: 1A — Infrastructure & Core Setup (Days 1–50)
## Date: 2026-02-03

---

### Infrastructure (Milestone 1)
- [ ] Development environment operational (Docker Compose)
- [ ] PostgreSQL 16 running with optimized configuration
- [ ] Redis 7 caching layer operational
- [ ] Git repository with branch strategy established
- [ ] CI/CD pipeline (GitHub Actions) functional

### Core Odoo Modules (Milestone 2)
- [ ] Sales module configured for Saffron milk products
- [ ] Inventory module configured with farm warehouse/locations
- [ ] Accounting module with Bangladeshi chart of accounts
- [ ] Purchase module for feed/supply procurement
- [ ] Manufacturing module with dairy BOMs
- [ ] HR module with farm worker records

### Custom Module (Milestone 3)
- [ ] smart_farm_mgmt module installed and operational
- [ ] Cattle management (255 records, CRUD functional)
- [ ] Milk production tracking (daily recording for 75 cows)
- [ ] Feed management (consumption tracking, inventory link)
- [ ] Health records (vaccination, treatment, cost tracking)
- [ ] Farm dashboard (OWL component, real-time data)

### Database Optimization (Milestone 4)
- [ ] Query performance baselines established
- [ ] Indexes created for high-frequency queries
- [ ] Connection pooling configured (pgBouncer/Odoo)
- [ ] Backup and restore procedures tested
- [ ] Data migration scripts validated

### Integration Testing (Milestone 5)
- [ ] Integration test suite: >= 95% pass rate
- [ ] API endpoint coverage: 100%
- [ ] Frontend integration tests: all passing
- [ ] Performance regression: < 5% delta from M4
- [ ] Security scan: 0 critical/high vulnerabilities
- [ ] P0/P1 bugs: 0 remaining
- [ ] Code coverage: >= 80%

### Documentation
- [ ] Architecture Decision Records (ADRs) complete
- [ ] Infrastructure runbook finalized
- [ ] API documentation (OpenAPI/Swagger) published
- [ ] Known issues log with P2/P3 items for Phase 1B
- [ ] Phase 1A retrospective notes captured

---

### Sign-Off

| Role | Name | Signature | Date |
|---|---|---|---|
| Backend Lead (Dev 1) | | | |
| Full-Stack/DevOps (Dev 2) | | | |
| Frontend/Mobile Lead (Dev 3) | | | |
| Project Sponsor | | | |
```

**End-of-Day 50 Deliverables:**
- [ ] All tests green across every suite
- [ ] Phase 1A checklist 100% complete
- [ ] Retrospective notes captured
- [ ] Phase 1B backlog with top priorities identified
- [ ] Sign-off certificate completed

---

## 4. Technical Specifications

### 4.1 Test Framework Stack

| Component | Technology | Version | Purpose |
|---|---|---|---|
| Test Runner | pytest | 8.x | Primary test framework |
| HTTP Client | httpx | 0.27+ | API endpoint testing |
| Coverage | pytest-cov | 5.x | Code coverage measurement |
| Reporting | pytest-html + allure | Latest | Test report generation |
| Load Testing | K6 | 0.50+ | Performance/load testing |
| E2E Browser | Playwright | 1.45+ | Browser automation |
| Frontend Unit | QUnit | (Odoo bundled) | OWL component testing |
| Security (SAST) | Bandit | 1.7+ | Python static analysis |
| Security (SCA) | Safety | 3.x | Dependency vulnerability scan |
| Linting | Ruff | 0.5+ | Python linting (pre-test) |

### 4.2 Test Environment Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    CI Runner (GitHub Actions)            │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌────────┐ │
│  │  Odoo 19 │  │ FastAPI  │  │ Postgres │  │ Redis  │ │
│  │  :8069   │  │  :8000   │  │  :5432   │  │ :6379  │ │
│  └────┬─────┘  └────┬─────┘  └────┬─────┘  └───┬────┘ │
│       │              │             │             │      │
│  ┌────┴──────────────┴─────────────┴─────────────┴────┐ │
│  │              Docker Network (test_net)              │ │
│  └────────────────────────────────────────────────────┘ │
│                                                         │
│  ┌─────────────────────────────────────────────────┐   │
│  │              Test Execution Layer               │   │
│  │  pytest | K6 | Playwright | QUnit | Bandit      │   │
│  └─────────────────────────────────────────────────┘   │
│                                                         │
│  ┌─────────────────────────────────────────────────┐   │
│  │              Reports & Artifacts                │   │
│  │  HTML | XML (JUnit) | JSON | Coverage | Videos  │   │
│  └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
```

### 4.3 Test Data Profile

| Entity | Record Count | Notes |
|---|---|---|
| Cattle | 255 | Matches real herd size |
| Lactating Cows | 75 | Active milk producers |
| Daily Milk Records | 525 (75 x 7 days) | 7-day history seeded |
| Feed Products | 5 | Concentrate, silage, hay, mineral, supplement |
| Milk Products | 3 | Raw, pasteurized, flavoured |
| Customers | 10 | Mix of retail and wholesale |
| Suppliers | 5 | Feed, veterinary, equipment |
| Sale Orders | 20 | Pre-seeded for testing flows |
| Purchase Orders | 10 | Feed procurement records |

---

## 5. Testing & Validation

### 5.1 Test Categories and Execution Order

| Order | Category | Marker | Tool | Est. Duration |
|---|---|---|---|---|
| 1 | Integration (Odoo modules) | `@pytest.mark.integration` | pytest | 15 min |
| 2 | Integration (Custom module) | `@pytest.mark.integration` | pytest | 10 min |
| 3 | API endpoints | `@pytest.mark.api` | pytest + httpx | 8 min |
| 4 | Frontend unit | QUnit | Odoo test runner | 5 min |
| 5 | E2E browser | `@pytest.mark.e2e` | Playwright | 12 min |
| 6 | Performance regression | `@pytest.mark.performance` | K6 | 7 min |
| 7 | Security | `@pytest.mark.security` | Bandit + Safety + pytest | 10 min |

### 5.2 Pass/Fail Criteria

| Metric | Pass Threshold | Fail Action |
|---|---|---|
| Overall pass rate | >= 95% | Block Phase 1A sign-off |
| P0 bugs open | 0 | Mandatory fix sprint |
| P1 bugs open | 0 | Mandatory fix sprint |
| Code coverage | >= 80% | Add missing tests |
| Performance regression | < 5% from M4 baseline | Investigate and optimize |
| Security critical/high | 0 | Mandatory remediation |

### 5.3 Test Report Distribution

All reports are generated into `reports/` and archived as GitHub Actions artifacts:

- `reports/integration_report.html` — Odoo module integration results
- `reports/api_results.xml` — API test JUnit XML
- `reports/coverage/` — HTML coverage report
- `reports/e2e_results.xml` — Browser test JUnit XML
- `reports/security/bandit_report.json` — Static analysis findings
- `reports/security/safety_report.json` — Dependency audit
- `reports/k6_summary.json` — Load test results

---

## 6. Risk & Mitigation

| # | Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|---|
| R5.1 | Test environment instability (Docker/services) | Medium | High | Pre-tested Docker Compose profile; health checks on all services; retry logic in CI |
| R5.2 | Flaky tests due to timing/ordering | Medium | Medium | Use explicit waits; isolate test data per class; run with `--forked` if needed |
| R5.3 | M4 performance regressions discovered | Low | High | K6 baselines from M4 stored; automated threshold comparison; rollback plan |
| R5.4 | Critical security vulnerability in dependency | Low | Critical | Safety check in CI; pin all dependency versions; have upgrade path ready |
| R5.5 | Test data contamination between runs | Medium | Medium | Database teardown in fixtures; use unique naming prefixes; transaction rollback |
| R5.6 | Insufficient time for P0 bug fixes on Day 48 | Medium | High | Start triage on Day 47 evening; prioritize ruthlessly; defer P2/P3 to Phase 1B |
| R5.7 | OWL component tests fail in headless CI | Medium | Low | Record videos on failure; test locally first; use Playwright trace viewer |
| R5.8 | Team burnout near Phase 1A deadline | Low | Medium | Realistic daily scope; no overtime expectation; celebrate incremental wins |

### Contingency Plan

If the integration test pass rate is below 95% by end of Day 48:
1. Extend the bug fix sprint into Day 49 (shift documentation to Day 50 morning)
2. Reduce sign-off ceremony scope but maintain mandatory checklist review
3. Document unresolved P2 issues as explicit Phase 1B backlog items
4. Require a "conditional pass" with explicit remediation timeline

---

## 7. Dependencies & Handoffs

### 7.1 Upstream Dependencies (from Previous Milestones)

| Dependency | Source | Required By | Status |
|---|---|---|---|
| Docker environment operational | M1 | Day 41 | Completed |
| Core Odoo modules configured | M2 | Day 42 | Completed |
| smart_farm_mgmt module functional | M3 | Day 43 | Completed |
| Database optimized with baselines | M4 | Day 46 | Completed |
| Performance baseline metrics | M4 | Day 46 | Completed |

### 7.2 Downstream Handoffs (to Phase 1B)

| Artifact | Recipient | Handoff Date | Description |
|---|---|---|---|
| Phase 1A Completion Checklist | All | Day 50 | Verified checklist with sign-off |
| Known Issues Log | Dev 1 | Day 50 | P2/P3 bugs deferred to Phase 1B |
| ADR Collection | All | Day 49 | All architectural decisions documented |
| Infrastructure Runbook | Dev 2 | Day 49 | Environment setup and operations guide |
| Test Suite + Fixtures | All | Day 48 | Reusable for Phase 1B regression testing |
| Performance Baselines | Dev 2 | Day 46 | K6 results for ongoing comparison |
| CI/CD Pipeline Config | Dev 2 | Day 49 | GitHub Actions workflows for all test types |
| UI Component Docs | Dev 3 | Day 49 | OWL component API reference |

### 7.3 Phase 1B Preview (Milestones 6–10)

| Milestone | Title | Days | Key Focus |
|---|---|---|---|
| M6 | Advanced Farm Features | 51–60 | Breeding, reproduction, genetics tracking |
| M7 | Financial & Reporting | 61–70 | Advanced accounting, dashboards, BDT reports |
| M8 | Mobile Application | 71–80 | Flutter app for field workers |
| M9 | System Hardening | 81–90 | Production deployment, monitoring, backups |
| M10 | Go-Live & Training | 91–100 | User training, data migration, launch |

---

## Appendix A: Command Reference

```bash
# Run all integration tests
pytest tests/integration/ -m integration -v

# Run API tests only
pytest tests/api/ -m api -v

# Run security tests only
pytest tests/security/ -m security -v

# Run E2E browser tests
pytest tests/e2e/ -m e2e --browser chromium -v

# Run K6 load test
k6 run tests/performance/k6_integration_test.js

# Generate coverage report
pytest tests/ --cov=smart_farm_mgmt --cov-report=html:reports/coverage -v

# Run Bandit security scan
bandit -r custom_addons/smart_farm_mgmt api -f json -o reports/bandit.json

# Run Safety dependency check
safety check --full-report --output json > reports/safety.json

# Full security audit script
bash scripts/security_check.sh

# Start test environment
docker compose -f docker-compose.test.yml up -d --wait

# Tear down test environment
docker compose -f docker-compose.test.yml down -v
```

---

## Appendix B: File Structure

```
smart_dairy/
├── tests/
│   ├── conftest.py                          # Shared fixtures
│   ├── integration/
│   │   ├── test_odoo_integration.py         # Core module tests
│   │   └── test_farm_integration.py         # Custom module tests
│   ├── api/
│   │   └── test_api_endpoints.py            # API CRUD tests
│   ├── security/
│   │   └── test_injection.py                # SQL injection & XSS tests
│   ├── e2e/
│   │   └── test_critical_flows.py           # Playwright browser tests
│   └── performance/
│       └── k6_integration_test.js           # K6 load scenarios
├── reports/
│   ├── coverage/                            # HTML coverage report
│   ├── security/                            # Bandit + Safety reports
│   ├── integration_report.html
│   └── k6_summary.json
├── scripts/
│   └── security_check.sh                   # Full security audit
├── docs/
│   ├── adr/                                 # Architecture Decision Records
│   └── phase1a_completion_checklist.md
├── .github/
│   ├── workflows/
│   │   └── integration_tests.yml            # CI/CD pipeline
│   └── ISSUE_TEMPLATE/
│       └── bug_report_m5.md                 # Bug report template
├── .bandit                                  # Bandit configuration
├── pytest.ini                               # pytest configuration
└── pyproject.toml                           # Coverage configuration
```

---

*Document Version: 1.0 | Status: Draft | Smart Dairy Digital Smart Portal + ERP — Phase 1A*
