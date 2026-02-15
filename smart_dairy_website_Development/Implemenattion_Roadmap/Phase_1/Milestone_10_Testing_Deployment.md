# Milestone 10: Comprehensive Testing, Staging Deployment & Phase 1 Completion

## Smart Dairy Digital Smart Portal + ERP — Phase 1: Foundation

| Field | Detail |
|---|---|
| **Milestone** | 10 of 10 |
| **Title** | Comprehensive Testing, Staging Deployment & Phase 1 Completion |
| **Phase** | Phase 1 — Foundation (Part B: Database & Security) |
| **Days** | Days 91–100 (of 100) |
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
8. [Phase 1 Completion & Phase 2 Transition](#8-phase-1-completion--phase-2-transition)

---

## 1. Milestone Overview

### 1.1 Purpose

Milestone 10 is the capstone of Phase 1. It consolidates all work from Milestones 1 through 9 into a fully tested, staging-deployed, and formally signed-off deliverable. The scope covers comprehensive testing (unit, integration, security, performance), staging environment deployment, user acceptance testing with stakeholders, bug resolution, documentation finalization, and Phase 2 transition planning.

### 1.2 Business Context

Smart Dairy Ltd operates an organic dairy farm in Bangladesh with 255 cattle (75 lactating cows) producing approximately 900 liters of milk daily under the "Saffron" brand. This milestone validates that the entire digital portal and ERP foundation meets production-readiness criteria before advancing to Phase 2 feature expansion.

### 1.3 Success Criteria

| Criterion | Target |
|---|---|
| Unit test coverage (custom code) | Greater than 80% |
| Integration test pass rate | 100% |
| OWASP Top 10 vulnerabilities | Zero critical or high findings |
| Page load time | Less than 2 seconds |
| API response time (p95) | Less than 200 milliseconds |
| Concurrent user support | 100 users nominal, 500 stress |
| UAT sign-off | All 15 user stories approved |
| Staging deployment | Fully operational with SSL |

### 1.4 Tech Stack Summary

- **Backend:** Odoo 19 CE, Python 3.11+, FastAPI
- **Database:** PostgreSQL 16, TimescaleDB, Redis 7
- **Frontend:** OWL Framework, Flutter 3.16+
- **Search:** Elasticsearch 8
- **Infrastructure:** Docker, Nginx, CI/CD pipelines

---

## 2. Requirement Traceability Matrix

| Req ID | Governance Doc | Requirement | Milestone 10 Coverage | Verification |
|---|---|---|---|---|
| G-001-TS-01 | G-001 Test Strategy | Master test plan for all phases | Day 91: Test plan creation | Test plan document review |
| G-001-TS-02 | G-001 Test Strategy | Unit test coverage thresholds | Day 92: Coverage enforcement at 80% | pytest-cov report |
| G-001-TS-03 | G-001 Test Strategy | Integration test suite | Day 93: E2E integration tests | CI pipeline pass |
| G-006-ST-01 | G-006 Security Testing | OWASP Top 10 verification | Day 94: Pen testing | ZAP scan report |
| G-006-ST-02 | G-006 Security Testing | Authenticated and unauthenticated scans | Day 94: DAST execution | Scan results |
| G-007-PT-01 | G-007 Performance Testing | Load test benchmarks | Day 95: K6 load tests | K6 HTML report |
| G-007-PT-02 | G-007 Performance Testing | SLA compliance validation | Day 95: Threshold checks | Pass/fail metrics |
| D-007-EM-01 | D-007 Environment Mgmt | Staging environment parity | Day 96: Docker staging deploy | Smoke test suite |
| D-007-EM-02 | D-007 Environment Mgmt | Deployment rollback capability | Day 96: Rollback script | Rollback drill |
| RTM-UAT-01 | Project Charter | Stakeholder acceptance | Day 97: UAT walkthrough | Sign-off forms |
| RTM-DOC-01 | Project Charter | Complete documentation | Day 99: Doc finalization | Doc checklist |
| RTM-PH2-01 | Project Charter | Phase 2 readiness | Day 100: Transition plan | Review meeting |

---

## 3. Day-by-Day Breakdown

### Day 91 — Comprehensive Test Plan for All Phase 1 Deliverables

**Objective:** Create a master test plan that covers all outputs from Milestones 1 through 9, provision test environments, and seed test data.

#### Dev 1 (Backend Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Draft master test plan document: define test categories (unit, integration, security, performance, UAT), entry/exit criteria, and scope boundaries |
| 2–4 | Build the test matrix mapping each M1-M9 deliverable to specific test cases; identify coverage gaps |
| 4–6 | Prepare test data seeding scripts for PostgreSQL and TimescaleDB: sample animals (255 cattle), milk records (90 days), quality metrics |
| 6–8 | Configure pytest infrastructure: markers, fixtures, and environment-specific settings |

#### Dev 2 (Full-Stack/DevOps) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Provision isolated test environment using Docker Compose; replicate production-like topology |
| 2–4 | Configure CI pipeline test stages: unit, integration, security scan, performance gates |
| 4–6 | Set up test result aggregation: JUnit XML output, coverage reports, artifact storage |
| 6–8 | Prepare test database snapshots for repeatable test runs; configure Redis test instance |

#### Dev 3 (Frontend/Mobile Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Define frontend test plan: OWL component tests, Flutter widget tests, E2E browser tests |
| 3–6 | Set up Playwright for browser-based E2E testing; configure Flutter test harness |
| 6–8 | Create test data fixtures for frontend: mock API responses, sample dashboard data |

**End-of-Day Deliverables:**
- Master test plan document (approved by all three developers)
- Test environment fully provisioned and accessible
- Test data seeded in all databases
- CI pipeline updated with test stage gates

---

### Day 92 — Unit Test Completion and Coverage Analysis

**Objective:** Achieve greater than 80% unit test coverage on all custom code, generate coverage reports, and close identified gaps.

#### Dev 1 (Backend Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Write unit tests for custom farm module: animal CRUD, milk collection, quality checks |
| 3–5 | Write unit tests for API endpoints: FastAPI route handlers, serialization, validation |
| 5–7 | Write unit tests for database operations: RLS policies, stored procedures, triggers |
| 7–8 | Run coverage analysis, identify gaps, and prioritize remaining tests |

#### Dev 2 (Full-Stack/DevOps) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Write unit tests for CI/CD scripts, deployment utilities, and configuration management |
| 3–5 | Write unit tests for security middleware: authentication, authorization, token validation |
| 5–7 | Configure coverage enforcement in CI: fail build below 80% threshold |
| 7–8 | Generate and review HTML coverage reports; publish to artifact storage |

#### Dev 3 (Frontend/Mobile Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Write OWL component unit tests: form components, list views, dashboard widgets |
| 3–6 | Write Flutter widget tests: milk entry screens, animal profiles, report views |
| 6–8 | Run frontend coverage analysis and fill gaps in component test coverage |

**End-of-Day Deliverables:**
- All unit tests passing in CI
- Coverage report showing greater than 80% on custom code
- Gap analysis document with remaining low-priority items logged as technical debt

---

### Day 93 — Integration Test Suite Execution

**Objective:** Execute full integration tests covering all Odoo modules, custom farm module, API endpoints, security components, and database operations.

#### Dev 1 (Backend Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Execute end-to-end milk collection flow tests: animal creation through accounting |
| 3–5 | Execute farm security integration tests: login, MFA, RLS verification, audit logging |
| 5–7 | Test database integration: PostgreSQL HA failover, TimescaleDB aggregation, Redis caching |
| 7–8 | Triage failures, document root causes, and create fix tickets |

#### Dev 2 (Full-Stack/DevOps) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Execute cross-module integration tests: Odoo core modules interacting with custom farm module |
| 3–5 | Test API integration: FastAPI to Odoo XML-RPC, webhook delivery, Elasticsearch indexing |
| 5–7 | Test infrastructure integration: Docker networking, Nginx routing, SSL termination |
| 7–8 | Consolidate integration test results and update test matrix |

#### Dev 3 (Frontend/Mobile Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Execute Playwright E2E tests: complete user workflows from login to report generation |
| 3–6 | Execute Flutter integration tests: mobile app API calls, offline sync, push notifications |
| 6–8 | Test frontend-backend contract: API response shapes match OWL and Flutter expectations |

**End-of-Day Deliverables:**
- Integration test suite report (all tests passing or failures documented)
- Root cause analysis for any failures
- Updated test matrix with integration results

---

### Day 94 — Security Penetration Testing

**Objective:** Verify OWASP Top 10 compliance, execute authenticated and unauthenticated scans, and perform manual penetration testing.

#### Dev 1 (Backend Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Run manual SQL injection tests against all custom endpoints; verify parameterized queries |
| 3–5 | Test authentication flows: brute force protection, session management, token expiry |
| 5–7 | Verify RLS enforcement under attack: attempt privilege escalation, cross-tenant data access |
| 7–8 | Document all findings with severity classification and remediation steps |

#### Dev 2 (Full-Stack/DevOps) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Execute OWASP ZAP automated scan (DAST) against staging endpoints |
| 3–5 | Run authenticated scan with valid session tokens; analyze results |
| 5–7 | Test security misconfiguration: exposed headers, directory listing, default credentials, CORS |
| 7–8 | Generate consolidated security report; classify findings by CVSS score |

#### Dev 3 (Frontend/Mobile Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Test XSS vectors in all OWL form inputs and rendered content |
| 3–5 | Test CSRF protection on state-changing operations; verify token rotation |
| 5–7 | Test Flutter app security: certificate pinning, local storage encryption, API key exposure |
| 7–8 | Verify Content Security Policy headers and Subresource Integrity |

**End-of-Day Deliverables:**
- ZAP scan report (HTML and JSON)
- Manual penetration test checklist (all items addressed)
- Security findings report with CVSS scores and remediation plan
- Confirmation of zero critical or high-severity findings (or remediation plan if any exist)

---

### Day 95 — Performance Testing

**Objective:** Execute load, stress, and endurance tests using K6. Validate SLA compliance: page load under 2 seconds, API response under 200 milliseconds at p95.

#### Dev 1 (Backend Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Optimize any database queries flagged during load tests; add missing indexes |
| 3–5 | Profile API endpoints under load: identify bottlenecks in ORM queries and serialization |
| 5–7 | Tune PostgreSQL configuration (work_mem, shared_buffers) based on load test observations |
| 7–8 | Validate TimescaleDB continuous aggregate performance under concurrent reads |

#### Dev 2 (Full-Stack/DevOps) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Execute K6 load test: 100 concurrent users for 10 minutes |
| 2–4 | Execute K6 stress test: ramp to 500 concurrent users |
| 4–6 | Execute K6 soak test: 50 users for 8 hours (run in background) |
| 6–8 | Analyze results, generate reports, compare against SLA thresholds |

#### Dev 3 (Frontend/Mobile Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Measure frontend performance: Lighthouse scores, Core Web Vitals, bundle size analysis |
| 3–5 | Profile OWL component rendering under load: identify re-render bottlenecks |
| 5–7 | Test Flutter app performance: startup time, frame rate, memory consumption |
| 7–8 | Document frontend performance baselines and optimization recommendations |

**End-of-Day Deliverables:**
- K6 load test report (HTML) confirming SLA compliance
- K6 stress test report with breaking point identified
- Soak test running (results available Day 96 morning)
- Performance optimization recommendations document

---

### Day 96 — Staging Environment Deployment

**Objective:** Deploy the complete Phase 1 system to a staging server using Docker Compose. Validate environment parity, configure DNS and SSL, and run smoke tests.

#### Dev 1 (Backend Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Verify database migration scripts run cleanly on staging PostgreSQL |
| 2–4 | Deploy and validate Odoo 19 CE with all custom modules on staging |
| 4–6 | Validate TimescaleDB hypertables, Redis cache connectivity, Elasticsearch indices |
| 6–8 | Run database smoke tests: CRUD operations, RLS checks, backup/restore validation |

#### Dev 2 (Full-Stack/DevOps) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Deploy Docker Compose stack to staging server; verify all containers healthy |
| 2–4 | Configure Nginx reverse proxy, DNS records, and SSL certificates (Let's Encrypt) |
| 4–6 | Execute staging smoke test suite: service health, connectivity, routing |
| 6–8 | Validate rollback procedure: simulate failed deployment, execute rollback, verify recovery |

#### Dev 3 (Frontend/Mobile Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Verify OWL frontend loads correctly on staging with production-like assets |
| 3–5 | Configure Flutter app to point to staging API endpoints; run mobile smoke tests |
| 5–7 | Test cross-browser compatibility on staging: Chrome, Firefox, Safari, Edge |
| 7–8 | Verify responsive design and mobile web experience on staging |

**End-of-Day Deliverables:**
- Staging environment fully operational at staging domain with valid SSL
- All smoke tests passing
- Rollback procedure validated and documented
- Soak test results from Day 95 reviewed and documented

---

### Day 97 — User Acceptance Testing (UAT) with Stakeholder Walkthrough

**Objective:** Conduct UAT session with stakeholders covering 15 user stories. Collect feedback and obtain sign-off forms.

#### Dev 1 (Backend Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Prepare demo data: realistic animal records, milk collections, quality results, sales orders |
| 2–5 | Lead backend-focused UAT scenarios: milk collection workflow, inventory management, reporting |
| 5–7 | Document stakeholder feedback on data accuracy, workflow correctness, report formats |
| 7–8 | Create bug tickets for all issues identified during UAT |

#### Dev 2 (Full-Stack/DevOps) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Prepare staging environment for UAT: ensure data freshness, monitor system health |
| 2–5 | Lead infrastructure UAT: demonstrate deployment, monitoring dashboards, backup procedures |
| 5–7 | Document performance observations during UAT: response times, error rates |
| 7–8 | Collect and organize sign-off forms from all stakeholders |

#### Dev 3 (Frontend/Mobile Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Prepare demo script with step-by-step walkthrough for all 15 user stories |
| 2–5 | Lead frontend UAT: walk stakeholders through web portal and mobile app interfaces |
| 5–7 | Capture UI/UX feedback: navigation issues, label clarity, color/layout preferences |
| 7–8 | Prioritize UI feedback items into P0-P3 severity categories |

**End-of-Day Deliverables:**
- UAT session completed with all 15 user stories tested
- Stakeholder feedback document
- Bug tickets created and prioritized (P0 through P3)
- Sign-off forms collected (or conditional sign-off with P0/P1 items listed)

---

### Day 98 — Bug Fixes and Critical Issue Resolution Sprint

**Objective:** Fix all P0 and P1 bugs from UAT, run regression tests, and deploy hotfixes to staging.

#### Dev 1 (Backend Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–4 | Fix P0/P1 backend bugs: data integrity issues, calculation errors, workflow failures |
| 4–6 | Write regression tests for each fixed bug to prevent recurrence |
| 6–8 | Verify fixes in local test environment; push to CI for full test suite run |

#### Dev 2 (Full-Stack/DevOps) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Fix P0/P1 infrastructure bugs: configuration issues, deployment problems |
| 2–4 | Deploy hotfix build to staging environment |
| 4–6 | Run full regression test suite on staging after hotfix deployment |
| 6–8 | Monitor staging stability post-deployment; verify no regressions |

#### Dev 3 (Frontend/Mobile Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–4 | Fix P0/P1 frontend bugs: rendering issues, navigation problems, form validation errors |
| 4–6 | Fix P0/P1 mobile bugs: layout issues, API integration fixes, offline mode corrections |
| 6–8 | Re-run E2E tests against staging with hotfixes; verify all user stories pass |

**End-of-Day Deliverables:**
- All P0 bugs resolved and verified on staging
- All P1 bugs resolved or documented with workarounds
- Regression test suite passing
- Updated staging deployment with all hotfixes applied

---

### Day 99 — Phase 1 Documentation Finalization

**Objective:** Complete all technical documentation, API docs, user guides, architecture decision records, and lessons learned.

#### Dev 1 (Backend Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Finalize API documentation: all endpoints, request/response schemas, authentication |
| 3–5 | Complete database documentation: schema diagrams, RLS policies, migration history |
| 5–7 | Write architecture decision records (ADRs) for key backend technical decisions |
| 7–8 | Review and approve all backend-related documentation |

#### Dev 2 (Full-Stack/DevOps) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Finalize infrastructure documentation: Docker setup, CI/CD pipelines, monitoring |
| 3–5 | Complete deployment runbook: step-by-step procedures, rollback, disaster recovery |
| 5–7 | Compile knowledge base: troubleshooting guides, FAQ, common operations |
| 7–8 | Organize all documentation in repository; verify completeness against checklist |

#### Dev 3 (Frontend/Mobile Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–3 | Finalize user guides: web portal navigation, mobile app usage, common workflows |
| 3–5 | Complete component library documentation: OWL components, Flutter widgets |
| 5–7 | Write lessons learned document from frontend/mobile perspective |
| 7–8 | Create Phase 2 UI/UX recommendations based on UAT feedback |

**End-of-Day Deliverables:**
- Complete technical documentation package
- API documentation (OpenAPI/Swagger)
- User guides for web and mobile
- Architecture decision records
- Lessons learned document
- Knowledge base and troubleshooting guides

---

### Day 100 — Phase 1 Completion Review, Formal Sign-Off & Phase 2 Kickoff Planning

**Objective:** Conduct retrospective, review velocity metrics, obtain formal Phase 1 sign-off, and plan Phase 2 Sprint 0.

#### Dev 1 (Backend Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Prepare Phase 1 backend metrics: test results summary, coverage stats, performance data |
| 2–4 | Lead technical retrospective: what went well, challenges, improvement opportunities |
| 4–6 | Draft Phase 2 backend requirements and technical debt items for backlog |
| 6–8 | Participate in formal sign-off meeting; present backend completion evidence |

#### Dev 2 (Full-Stack/DevOps) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Prepare velocity and burndown metrics for Phase 1 |
| 2–4 | Lead infrastructure retrospective; document CI/CD improvements for Phase 2 |
| 4–6 | Draft Phase 2 Sprint 0 plan: environment preparation, tooling upgrades, capacity planning |
| 6–8 | Archive Phase 1 artifacts; tag release in version control |

#### Dev 3 (Frontend/Mobile Lead) — ~8 hours

| Hours | Task |
|---|---|
| 0–2 | Prepare frontend metrics: Lighthouse scores, test results, component coverage |
| 2–4 | Lead UI/UX retrospective; compile user feedback themes |
| 4–6 | Draft Phase 2 frontend roadmap: new features, UI improvements, mobile enhancements |
| 6–8 | Participate in sign-off meeting; present frontend/mobile completion evidence |

**End-of-Day Deliverables:**
- Phase 1 retrospective document
- Velocity and burndown charts
- Formal Phase 1 sign-off obtained
- Phase 2 Sprint 0 plan drafted
- Release tag created in version control
- All Phase 1 artifacts archived

---

## 4. Technical Specifications

### 4.1 Master Test Plan Structure

```python
# tests/test_plan.py
"""
Smart Dairy - Phase 1 Master Test Plan Configuration
Defines test categories, entry/exit criteria, and environment requirements.
"""

MASTER_TEST_PLAN = {
    "project": "Smart Dairy Digital Smart Portal + ERP",
    "phase": "Phase 1 — Foundation",
    "version": "1.0",
    "test_categories": {
        "unit": {
            "scope": "Individual functions, methods, and classes",
            "tools": ["pytest", "pytest-cov", "pytest-mock", "unittest"],
            "coverage_target": 80,
            "owners": ["Dev 1", "Dev 2", "Dev 3"],
        },
        "integration": {
            "scope": "Cross-module interactions, API flows, DB operations",
            "tools": ["pytest", "httpx", "testcontainers"],
            "pass_rate_target": 100,
            "owners": ["Dev 1", "Dev 2"],
        },
        "security": {
            "scope": "OWASP Top 10, pen testing, vulnerability scanning",
            "tools": ["OWASP ZAP", "sqlmap", "custom scripts"],
            "zero_critical_high": True,
            "owners": ["Dev 2", "Dev 1"],
        },
        "performance": {
            "scope": "Load, stress, soak testing against SLA targets",
            "tools": ["K6", "Lighthouse", "Flutter DevTools"],
            "sla_targets": {
                "page_load_ms": 2000,
                "api_p95_ms": 200,
                "concurrent_users_nominal": 100,
                "concurrent_users_stress": 500,
            },
            "owners": ["Dev 2", "Dev 1"],
        },
        "uat": {
            "scope": "15 user stories validated by stakeholders",
            "tools": ["Manual testing", "demo scripts"],
            "signoff_required": True,
            "owners": ["Dev 3", "Dev 1"],
        },
    },
    "entry_criteria": [
        "All M1-M9 milestone deliverables completed",
        "Test environment provisioned and accessible",
        "Test data seeded in all databases",
        "CI pipeline configured with test stage gates",
        "All team members available for 10-day sprint",
    ],
    "exit_criteria": [
        "Unit test coverage >= 80% on custom code",
        "All integration tests passing (100% pass rate)",
        "Zero critical/high security findings (OWASP ZAP)",
        "Performance SLAs met: <2s page load, <200ms API p95",
        "All 15 UAT user stories signed off by stakeholders",
        "All P0 bugs resolved; P1 bugs resolved or documented",
        "Complete documentation package delivered",
        "Formal Phase 1 sign-off obtained",
    ],
    "test_environment": {
        "os": "Ubuntu 22.04 LTS (Docker host)",
        "python": "3.11+",
        "odoo": "19 CE",
        "postgresql": "16 with TimescaleDB",
        "redis": "7",
        "elasticsearch": "8",
        "docker": "24.0+",
        "nginx": "1.25+",
    },
}
```

### 4.2 Pytest Configuration

```toml
# pyproject.toml — Test configuration section
[tool.pytest.ini_options]
testpaths = ["tests"]
python_files = ["test_*.py", "*_test.py"]
python_classes = ["Test*"]
python_functions = ["test_*"]
addopts = [
    "--strict-markers",
    "--tb=short",
    "-v",
    "--junitxml=reports/junit.xml",
    "--cov=smart_dairy",
    "--cov-report=html:reports/coverage_html",
    "--cov-report=xml:reports/coverage.xml",
    "--cov-report=term-missing",
    "--cov-fail-under=80",
]
markers = [
    "unit: Unit tests (fast, isolated)",
    "integration: Integration tests (require DB/services)",
    "security: Security-related tests",
    "performance: Performance benchmark tests",
    "slow: Tests that take more than 10 seconds",
    "smoke: Quick smoke tests for deployment verification",
]
filterwarnings = [
    "ignore::DeprecationWarning",
]
asyncio_mode = "auto"
```

### 4.3 Conftest with Comprehensive Fixtures

```python
# tests/conftest.py
"""
Smart Dairy - Shared test fixtures for all test modules.
Covers database sessions, Odoo environments, API clients,
Redis connections, and test data factories.
"""
import os
import pytest
import redis
from datetime import date, datetime, timedelta
from unittest.mock import MagicMock
from decimal import Decimal

import psycopg2
from fastapi.testclient import TestClient
from httpx import AsyncClient


# ---------------------------------------------------------------------------
# Environment and Configuration Fixtures
# ---------------------------------------------------------------------------

@pytest.fixture(scope="session")
def test_config():
    """Return test environment configuration."""
    return {
        "db_host": os.getenv("TEST_DB_HOST", "localhost"),
        "db_port": int(os.getenv("TEST_DB_PORT", "5432")),
        "db_name": os.getenv("TEST_DB_NAME", "smart_dairy_test"),
        "db_user": os.getenv("TEST_DB_USER", "sd_test_user"),
        "db_password": os.getenv("TEST_DB_PASSWORD", "test_secret"),
        "redis_url": os.getenv("TEST_REDIS_URL", "redis://localhost:6379/1"),
        "odoo_url": os.getenv("TEST_ODOO_URL", "http://localhost:8069"),
        "api_url": os.getenv("TEST_API_URL", "http://localhost:8000"),
        "es_url": os.getenv("TEST_ES_URL", "http://localhost:9200"),
    }


# ---------------------------------------------------------------------------
# Database Fixtures
# ---------------------------------------------------------------------------

@pytest.fixture(scope="session")
def db_connection(test_config):
    """Create a PostgreSQL connection for the test session."""
    conn = psycopg2.connect(
        host=test_config["db_host"],
        port=test_config["db_port"],
        dbname=test_config["db_name"],
        user=test_config["db_user"],
        password=test_config["db_password"],
    )
    conn.autocommit = False
    yield conn
    conn.close()


@pytest.fixture
def db_cursor(db_connection):
    """Provide a database cursor that rolls back after each test."""
    cursor = db_connection.cursor()
    yield cursor
    db_connection.rollback()
    cursor.close()


# ---------------------------------------------------------------------------
# Redis Fixtures
# ---------------------------------------------------------------------------

@pytest.fixture(scope="session")
def redis_client(test_config):
    """Create a Redis client for the test session."""
    client = redis.from_url(test_config["redis_url"], decode_responses=True)
    yield client
    client.flushdb()
    client.close()


# ---------------------------------------------------------------------------
# API Client Fixtures
# ---------------------------------------------------------------------------

@pytest.fixture(scope="session")
def api_base_url(test_config):
    """Return API base URL."""
    return test_config["api_url"]


@pytest.fixture
def api_client(api_base_url):
    """Create a FastAPI test client."""
    from smart_dairy.api.main import app
    with TestClient(app, base_url=api_base_url) as client:
        yield client


@pytest.fixture
def authenticated_client(api_client):
    """Return an API client with valid authentication token."""
    response = api_client.post("/api/v1/auth/login", json={
        "username": "test_farm_manager",
        "password": "Test@Secure123",
        "mfa_code": "123456",
    })
    token = response.json()["access_token"]
    api_client.headers["Authorization"] = f"Bearer {token}"
    return api_client


# ---------------------------------------------------------------------------
# Odoo Environment Fixtures
# ---------------------------------------------------------------------------

@pytest.fixture
def odoo_env():
    """Mock Odoo environment for unit tests."""
    env = MagicMock()
    env.cr = MagicMock()
    env.uid = 2  # Admin user
    env.context = {"lang": "en_US", "tz": "Asia/Dhaka"}
    return env


# ---------------------------------------------------------------------------
# Test Data Factories
# ---------------------------------------------------------------------------

@pytest.fixture
def sample_animal():
    """Return a sample animal record dictionary."""
    return {
        "name": "SD-COW-001",
        "tag_number": "TAG-001",
        "species": "cattle",
        "breed": "Holstein Friesian Cross",
        "date_of_birth": date(2021, 3, 15),
        "gender": "female",
        "status": "lactating",
        "weight_kg": Decimal("485.5"),
        "farm_section": "Barn A",
    }


@pytest.fixture
def sample_milk_record(sample_animal):
    """Return a sample milk collection record."""
    return {
        "animal_tag": sample_animal["tag_number"],
        "collection_date": date.today(),
        "session": "morning",
        "quantity_liters": Decimal("12.5"),
        "fat_percentage": Decimal("3.8"),
        "snf_percentage": Decimal("8.5"),
        "temperature_celsius": Decimal("4.2"),
        "collector_id": 5,
    }


@pytest.fixture
def sample_quality_check(sample_milk_record):
    """Return a sample milk quality check record."""
    return {
        "batch_id": "BATCH-2026-02-03-AM",
        "test_date": datetime.now(),
        "somatic_cell_count": 180000,
        "bacterial_count": 25000,
        "antibiotic_residue": False,
        "aflatoxin_level": Decimal("0.02"),
        "ph_level": Decimal("6.7"),
        "result": "pass",
    }


@pytest.fixture
def sample_sale_order():
    """Return a sample sale order for dairy products."""
    return {
        "partner_id": 10,
        "order_date": date.today(),
        "delivery_date": date.today() + timedelta(days=1),
        "lines": [
            {"product": "Fresh Milk 1L", "qty": 100, "unit_price": Decimal("80.00")},
            {"product": "Yogurt 500g", "qty": 50, "unit_price": Decimal("65.00")},
        ],
    }
```

### 4.4 Integration Test: End-to-End Milk Collection

```python
# tests/integration/test_milk_collection_e2e.py
"""
Integration test: Complete milk collection flow from animal creation
through to accounting entry verification.
"""
import pytest
from decimal import Decimal
from datetime import date


@pytest.mark.integration
class TestEndToEndMilkCollection:
    """
    End-to-end test verifying the full milk collection workflow:
    1. Create animal record
    2. Record milk collection
    3. Run quality check
    4. Update inventory
    5. Create sale order
    6. Generate invoice
    7. Verify accounting entries
    """

    def test_full_milk_collection_flow(self, authenticated_client):
        """Test complete milk collection from cow to accounting."""
        # Step 1: Create animal
        animal_payload = {
            "name": "SD-COW-E2E-001",
            "tag_number": "E2E-TAG-001",
            "species": "cattle",
            "breed": "Holstein Friesian Cross",
            "date_of_birth": "2021-03-15",
            "gender": "female",
            "status": "lactating",
            "weight_kg": 485.5,
        }
        resp = authenticated_client.post("/api/v1/animals", json=animal_payload)
        assert resp.status_code == 201, f"Animal creation failed: {resp.text}"
        animal_id = resp.json()["id"]

        # Step 2: Record milk collection
        milk_payload = {
            "animal_id": animal_id,
            "collection_date": str(date.today()),
            "session": "morning",
            "quantity_liters": 12.5,
            "fat_percentage": 3.8,
            "snf_percentage": 8.5,
            "temperature_celsius": 4.2,
        }
        resp = authenticated_client.post("/api/v1/milk-collections", json=milk_payload)
        assert resp.status_code == 201
        milk_id = resp.json()["id"]
        batch_id = resp.json()["batch_id"]

        # Step 3: Quality check
        quality_payload = {
            "batch_id": batch_id,
            "somatic_cell_count": 180000,
            "bacterial_count": 25000,
            "antibiotic_residue": False,
            "aflatoxin_level": 0.02,
            "ph_level": 6.7,
        }
        resp = authenticated_client.post("/api/v1/quality-checks", json=quality_payload)
        assert resp.status_code == 201
        assert resp.json()["result"] == "pass"

        # Step 4: Verify inventory updated
        resp = authenticated_client.get(f"/api/v1/inventory/milk?batch_id={batch_id}")
        assert resp.status_code == 200
        inv = resp.json()
        assert float(inv["quantity_liters"]) == 12.5
        assert inv["quality_status"] == "approved"

        # Step 5: Create sale order
        sale_payload = {
            "partner_id": 10,
            "delivery_date": str(date.today()),
            "lines": [
                {
                    "product": "Fresh Milk",
                    "batch_id": batch_id,
                    "quantity_liters": 10.0,
                    "unit_price": 80.0,
                }
            ],
        }
        resp = authenticated_client.post("/api/v1/sales/orders", json=sale_payload)
        assert resp.status_code == 201
        order_id = resp.json()["id"]

        # Step 6: Confirm order and generate invoice
        resp = authenticated_client.post(f"/api/v1/sales/orders/{order_id}/confirm")
        assert resp.status_code == 200
        invoice_id = resp.json()["invoice_id"]
        assert invoice_id is not None

        # Step 7: Verify accounting entries
        resp = authenticated_client.get(f"/api/v1/accounting/invoices/{invoice_id}")
        assert resp.status_code == 200
        invoice = resp.json()
        assert invoice["state"] == "posted"
        assert float(invoice["amount_total"]) == 800.0  # 10L * 80 BDT

        # Verify journal entries created
        resp = authenticated_client.get(
            f"/api/v1/accounting/journal-entries?invoice_id={invoice_id}"
        )
        assert resp.status_code == 200
        entries = resp.json()["entries"]
        assert len(entries) >= 2  # At least debit and credit entries

        debit_total = sum(float(e["debit"]) for e in entries)
        credit_total = sum(float(e["credit"]) for e in entries)
        assert debit_total == credit_total  # Double-entry accounting

    def test_milk_collection_rejected_quality(self, authenticated_client):
        """Test that milk failing quality check does not enter inventory."""
        # Record milk and fail quality check
        milk_payload = {
            "animal_id": 1,
            "collection_date": str(date.today()),
            "session": "evening",
            "quantity_liters": 8.0,
            "fat_percentage": 2.1,
            "snf_percentage": 6.0,
            "temperature_celsius": 12.0,
        }
        resp = authenticated_client.post("/api/v1/milk-collections", json=milk_payload)
        assert resp.status_code == 201
        batch_id = resp.json()["batch_id"]

        quality_payload = {
            "batch_id": batch_id,
            "somatic_cell_count": 750000,
            "bacterial_count": 500000,
            "antibiotic_residue": True,
            "aflatoxin_level": 0.08,
            "ph_level": 5.2,
        }
        resp = authenticated_client.post("/api/v1/quality-checks", json=quality_payload)
        assert resp.status_code == 201
        assert resp.json()["result"] == "fail"

        # Verify inventory NOT updated for rejected batch
        resp = authenticated_client.get(f"/api/v1/inventory/milk?batch_id={batch_id}")
        assert resp.status_code == 200
        assert resp.json()["quality_status"] == "rejected"
```

### 4.5 Integration Test: Farm Security Flow

```python
# tests/integration/test_farm_security_flow.py
"""
Integration test: Security flow from login through MFA,
data access with RLS, and audit log verification.
"""
import pytest
import time


@pytest.mark.integration
@pytest.mark.security
class TestFarmSecurityFlow:
    """
    Test the complete security flow:
    1. Login with credentials
    2. MFA verification
    3. Access farm data with RLS enforcement
    4. Verify audit log entries
    """

    def test_authenticated_access_with_mfa(self, api_client):
        """Test login, MFA, and authorized data access."""
        # Step 1: Login
        login_resp = api_client.post("/api/v1/auth/login", json={
            "username": "farm_manager_01",
            "password": "FarmSecure@2026!",
        })
        assert login_resp.status_code == 200
        assert login_resp.json()["mfa_required"] is True
        session_token = login_resp.json()["session_token"]

        # Step 2: MFA verification
        mfa_resp = api_client.post("/api/v1/auth/mfa/verify", json={
            "session_token": session_token,
            "totp_code": "123456",  # Test fixture code
        })
        assert mfa_resp.status_code == 200
        access_token = mfa_resp.json()["access_token"]
        headers = {"Authorization": f"Bearer {access_token}"}

        # Step 3: Access farm data (RLS should scope to user's farm)
        animals_resp = api_client.get("/api/v1/animals", headers=headers)
        assert animals_resp.status_code == 200
        animals = animals_resp.json()["data"]
        # RLS ensures only this farm's animals are returned
        for animal in animals:
            assert animal["farm_id"] == 1  # farm_manager_01's farm

        # Step 4: Attempt cross-farm access (should be denied)
        other_farm_resp = api_client.get(
            "/api/v1/animals?farm_id=999", headers=headers
        )
        assert other_farm_resp.status_code in (200, 403)
        if other_farm_resp.status_code == 200:
            # RLS filters results; should return empty
            assert len(other_farm_resp.json()["data"]) == 0

        # Step 5: Verify audit log entry exists
        time.sleep(1)  # Allow async audit log write
        audit_resp = api_client.get(
            "/api/v1/audit/logs?user=farm_manager_01&action=login",
            headers=headers,
        )
        assert audit_resp.status_code == 200
        logs = audit_resp.json()["entries"]
        assert any(log["action"] == "login_success" for log in logs)

    def test_brute_force_protection(self, api_client):
        """Test that repeated failed logins trigger account lockout."""
        for i in range(6):
            resp = api_client.post("/api/v1/auth/login", json={
                "username": "farm_manager_01",
                "password": f"WrongPassword{i}",
            })

        # After 5+ failures, account should be locked
        assert resp.status_code == 429
        assert "locked" in resp.json()["detail"].lower()

    def test_expired_token_rejected(self, api_client):
        """Test that expired JWT tokens are properly rejected."""
        expired_token = (
            "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9."
            "eyJzdWIiOiJ0ZXN0IiwiZXhwIjoxNjAwMDAwMDAwfQ."
            "invalid_signature"
        )
        resp = api_client.get(
            "/api/v1/animals",
            headers={"Authorization": f"Bearer {expired_token}"},
        )
        assert resp.status_code == 401
```

### 4.6 OWASP Top 10 Penetration Test Scripts

```python
# tests/security/test_owasp_top10.py
"""
Automated OWASP Top 10 penetration test checklist.
Covers: Injection, Broken Auth, XSS, CSRF, SSRF, Security Misconfiguration.
"""
import pytest
import subprocess
import json


@pytest.mark.security
class TestOWASPTop10:
    """Automated checks for OWASP Top 10 2021 vulnerabilities."""

    BASE_URL = "http://staging.smartdairy.local"

    # A01:2021 - Broken Access Control
    def test_broken_access_control_idor(self, authenticated_client):
        """Test that users cannot access other users' resources via IDOR."""
        resp = authenticated_client.get("/api/v1/users/999/profile")
        assert resp.status_code in (403, 404)

    def test_broken_access_control_privilege_escalation(self, authenticated_client):
        """Test that regular users cannot access admin endpoints."""
        resp = authenticated_client.post("/api/v1/admin/users", json={
            "username": "hacker",
            "role": "admin",
        })
        assert resp.status_code == 403

    # A02:2021 - Cryptographic Failures
    def test_sensitive_data_not_in_response(self, authenticated_client):
        """Verify passwords and secrets are never returned in API responses."""
        resp = authenticated_client.get("/api/v1/users/me")
        assert resp.status_code == 200
        body = resp.json()
        assert "password" not in body
        assert "password_hash" not in body
        assert "secret_key" not in body

    # A03:2021 - Injection
    def test_sql_injection_login(self, api_client):
        """Test SQL injection resistance on login endpoint."""
        payloads = [
            "' OR '1'='1",
            "admin'--",
            "1; DROP TABLE users;--",
            "' UNION SELECT * FROM users--",
        ]
        for payload in payloads:
            resp = api_client.post("/api/v1/auth/login", json={
                "username": payload,
                "password": payload,
            })
            assert resp.status_code in (401, 422)

    def test_sql_injection_search(self, authenticated_client):
        """Test SQL injection resistance on search endpoints."""
        payloads = ["'; DROP TABLE animals;--", "1 OR 1=1", "UNION SELECT"]
        for payload in payloads:
            resp = authenticated_client.get(f"/api/v1/animals?search={payload}")
            assert resp.status_code in (200, 422)
            # If 200, results should be empty (not all records)
            if resp.status_code == 200:
                assert len(resp.json()["data"]) == 0

    # A05:2021 - Security Misconfiguration
    def test_no_server_version_header(self, api_client):
        """Verify server does not expose version information in headers."""
        resp = api_client.get("/api/v1/health")
        assert "X-Powered-By" not in resp.headers
        assert "Server" not in resp.headers or "nginx" not in resp.headers.get(
            "Server", ""
        ).lower()

    def test_no_directory_listing(self, api_client):
        """Verify directory listing is disabled."""
        resp = api_client.get("/static/")
        assert resp.status_code != 200 or "Index of" not in resp.text

    # A07:2021 - Cross-Site Scripting (XSS)
    def test_xss_in_animal_name(self, authenticated_client):
        """Test XSS payload is sanitized in animal name field."""
        xss_payloads = [
            "<script>alert('xss')</script>",
            "<img src=x onerror=alert('xss')>",
            "javascript:alert('xss')",
        ]
        for payload in xss_payloads:
            resp = authenticated_client.post("/api/v1/animals", json={
                "name": payload,
                "tag_number": "XSS-TEST",
                "species": "cattle",
            })
            if resp.status_code == 201:
                animal_id = resp.json()["id"]
                get_resp = authenticated_client.get(f"/api/v1/animals/{animal_id}")
                assert "<script>" not in get_resp.text

    # A08:2021 - SSRF
    def test_ssrf_in_webhook_url(self, authenticated_client):
        """Test SSRF protection on webhook configuration."""
        internal_urls = [
            "http://localhost:5432",
            "http://127.0.0.1:6379",
            "http://169.254.169.254/latest/meta-data/",
            "http://internal-service:8080",
        ]
        for url in internal_urls:
            resp = authenticated_client.post("/api/v1/webhooks", json={
                "name": "test",
                "url": url,
                "events": ["milk.collected"],
            })
            assert resp.status_code in (400, 422)

    # A09:2021 - Security Logging
    def test_security_events_logged(self, api_client, authenticated_client):
        """Verify that security-relevant events are captured in audit logs."""
        # Trigger a failed login
        api_client.post("/api/v1/auth/login", json={
            "username": "nonexistent",
            "password": "wrong",
        })

        # Check audit log
        resp = authenticated_client.get(
            "/api/v1/audit/logs?action=login_failed&limit=1"
        )
        assert resp.status_code == 200
        assert len(resp.json()["entries"]) > 0
```

### 4.7 K6 Load Test Scripts

```javascript
// tests/performance/k6_load_test.js
// K6 load, stress, and soak test scenarios for Smart Dairy
import http from 'k6/http';
import { check, group, sleep } from 'k6';
import { Rate, Trend } from 'k6/metrics';

// Custom metrics
const milkCollectionDuration = new Trend('milk_collection_duration');
const dashboardLoadDuration = new Trend('dashboard_load_duration');
const apiErrorRate = new Rate('api_errors');

// Configuration: define scenarios
export const options = {
  scenarios: {
    // Scenario 1: Normal load - milk collection flow
    milk_collection_flow: {
      executor: 'ramping-vus',
      startVUs: 0,
      stages: [
        { duration: '2m', target: 50 },   // Ramp up to 50 users
        { duration: '5m', target: 100 },   // Ramp up to 100 users
        { duration: '5m', target: 100 },   // Hold at 100 users
        { duration: '2m', target: 0 },     // Ramp down
      ],
      exec: 'milkCollectionFlow',
      tags: { scenario: 'load_test' },
    },

    // Scenario 2: Dashboard load simulation
    farm_dashboard_load: {
      executor: 'constant-vus',
      vus: 50,
      duration: '10m',
      exec: 'farmDashboardLoad',
      tags: { scenario: 'dashboard_load' },
    },

    // Scenario 3: API stress test
    api_stress_test: {
      executor: 'ramping-vus',
      startVUs: 0,
      stages: [
        { duration: '2m', target: 100 },
        { duration: '3m', target: 300 },
        { duration: '2m', target: 500 },   // Peak stress
        { duration: '3m', target: 500 },   // Hold peak
        { duration: '2m', target: 0 },     // Ramp down
      ],
      exec: 'apiStressTest',
      tags: { scenario: 'stress_test' },
    },
  },

  // SLA thresholds
  thresholds: {
    http_req_duration: [
      'p(95)<200',   // 95% of API requests under 200ms
      'p(99)<500',   // 99% under 500ms
    ],
    'http_req_duration{scenario:dashboard_load}': ['p(95)<2000'],
    milk_collection_duration: ['p(95)<300'],
    dashboard_load_duration: ['p(95)<2000'],
    api_errors: ['rate<0.01'],  // Less than 1% error rate
    http_req_failed: ['rate<0.01'],
  },
};

const BASE_URL = __ENV.BASE_URL || 'http://staging.smartdairy.local';

// Authenticate and get token
function authenticate() {
  const loginRes = http.post(`${BASE_URL}/api/v1/auth/login`, JSON.stringify({
    username: `loadtest_user_${__VU}`,
    password: 'LoadTest@2026!',
    mfa_code: '000000',
  }), {
    headers: { 'Content-Type': 'application/json' },
  });

  check(loginRes, { 'login successful': (r) => r.status === 200 });

  if (loginRes.status === 200) {
    return JSON.parse(loginRes.body).access_token;
  }
  return null;
}

function authHeaders(token) {
  return {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
  };
}

// Scenario 1: Milk Collection Flow
export function milkCollectionFlow() {
  const token = authenticate();
  if (!token) { apiErrorRate.add(1); return; }

  group('Milk Collection Flow', () => {
    const start = Date.now();

    // List animals
    const animalsRes = http.get(
      `${BASE_URL}/api/v1/animals?status=lactating&limit=10`,
      authHeaders(token)
    );
    check(animalsRes, { 'animals listed': (r) => r.status === 200 });

    // Record milk collection
    const milkRes = http.post(
      `${BASE_URL}/api/v1/milk-collections`,
      JSON.stringify({
        animal_id: Math.floor(Math.random() * 75) + 1,
        collection_date: new Date().toISOString().split('T')[0],
        session: Math.random() > 0.5 ? 'morning' : 'evening',
        quantity_liters: (Math.random() * 10 + 5).toFixed(1),
        fat_percentage: (Math.random() * 1.5 + 3.0).toFixed(1),
        snf_percentage: (Math.random() * 1.0 + 8.0).toFixed(1),
        temperature_celsius: (Math.random() * 2 + 3).toFixed(1),
      }),
      authHeaders(token)
    );
    check(milkRes, { 'milk recorded': (r) => r.status === 201 });

    if (milkRes.status !== 201) { apiErrorRate.add(1); }

    milkCollectionDuration.add(Date.now() - start);
  });

  sleep(Math.random() * 3 + 1);
}

// Scenario 2: Farm Dashboard Load
export function farmDashboardLoad() {
  const token = authenticate();
  if (!token) { apiErrorRate.add(1); return; }

  group('Dashboard Load', () => {
    const start = Date.now();

    // Load dashboard summary
    const summaryRes = http.get(
      `${BASE_URL}/api/v1/dashboard/summary`,
      authHeaders(token)
    );
    check(summaryRes, { 'summary loaded': (r) => r.status === 200 });

    // Load today's milk production
    const productionRes = http.get(
      `${BASE_URL}/api/v1/dashboard/milk-production?period=today`,
      authHeaders(token)
    );
    check(productionRes, { 'production loaded': (r) => r.status === 200 });

    // Load animal health status
    const healthRes = http.get(
      `${BASE_URL}/api/v1/dashboard/animal-health`,
      authHeaders(token)
    );
    check(healthRes, { 'health loaded': (r) => r.status === 200 });

    // Load recent quality checks
    const qualityRes = http.get(
      `${BASE_URL}/api/v1/dashboard/quality-summary`,
      authHeaders(token)
    );
    check(qualityRes, { 'quality loaded': (r) => r.status === 200 });

    dashboardLoadDuration.add(Date.now() - start);
  });

  sleep(Math.random() * 5 + 2);
}

// Scenario 3: API Stress Test
export function apiStressTest() {
  const token = authenticate();
  if (!token) { apiErrorRate.add(1); return; }

  const endpoints = [
    { method: 'GET', url: '/api/v1/animals?limit=20' },
    { method: 'GET', url: '/api/v1/milk-collections?limit=20' },
    { method: 'GET', url: '/api/v1/inventory/milk' },
    { method: 'GET', url: '/api/v1/quality-checks?limit=10' },
    { method: 'GET', url: '/api/v1/sales/orders?limit=10' },
    { method: 'GET', url: '/api/v1/reports/daily-summary' },
  ];

  const endpoint = endpoints[Math.floor(Math.random() * endpoints.length)];
  const res = http.get(`${BASE_URL}${endpoint.url}`, authHeaders(token));

  check(res, {
    'status is 200': (r) => r.status === 200,
    'response time OK': (r) => r.timings.duration < 500,
  });

  if (res.status !== 200) { apiErrorRate.add(1); }

  sleep(Math.random() * 2 + 0.5);
}
```

### 4.8 K6 Soak Test Configuration

```javascript
// tests/performance/k6_soak_test.js
// 8-hour endurance / soak test for Smart Dairy
import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate } from 'k6/metrics';

const errorRate = new Rate('errors');

export const options = {
  scenarios: {
    soak: {
      executor: 'constant-vus',
      vus: 50,
      duration: '8h',
    },
  },
  thresholds: {
    http_req_duration: ['p(95)<200', 'p(99)<500'],
    errors: ['rate<0.01'],
    http_req_failed: ['rate<0.01'],
  },
};

const BASE_URL = __ENV.BASE_URL || 'http://staging.smartdairy.local';

export default function () {
  const loginRes = http.post(`${BASE_URL}/api/v1/auth/login`, JSON.stringify({
    username: `soak_user_${__VU}`,
    password: 'SoakTest@2026!',
    mfa_code: '000000',
  }), { headers: { 'Content-Type': 'application/json' } });

  if (loginRes.status !== 200) { errorRate.add(1); sleep(5); return; }

  const token = JSON.parse(loginRes.body).access_token;
  const headers = {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
  };

  // Mixed workload simulating real usage
  const actions = [
    () => http.get(`${BASE_URL}/api/v1/animals?limit=10`, headers),
    () => http.get(`${BASE_URL}/api/v1/dashboard/summary`, headers),
    () => http.get(`${BASE_URL}/api/v1/milk-collections?limit=10`, headers),
    () => http.post(`${BASE_URL}/api/v1/milk-collections`, JSON.stringify({
      animal_id: Math.floor(Math.random() * 75) + 1,
      collection_date: new Date().toISOString().split('T')[0],
      session: 'morning',
      quantity_liters: 10.0,
      fat_percentage: 3.5,
      snf_percentage: 8.5,
      temperature_celsius: 4.0,
    }), headers),
  ];

  const action = actions[Math.floor(Math.random() * actions.length)];
  const res = action();

  check(res, { 'status OK': (r) => r.status >= 200 && r.status < 300 });
  if (res.status >= 400) { errorRate.add(1); }

  sleep(Math.random() * 10 + 5);  // 5-15 second think time
}
```

### 4.9 Docker Compose Staging Configuration

```yaml
# docker/staging/docker-compose.staging.yml
# Production-like staging deployment for Smart Dairy
version: '3.9'

services:
  # --- Nginx Reverse Proxy with SSL ---
  nginx:
    image: nginx:1.25-alpine
    container_name: sd_nginx
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx/nginx.conf:/etc/nginx/nginx.conf:ro
      - ./nginx/conf.d:/etc/nginx/conf.d:ro
      - ./ssl:/etc/nginx/ssl:ro
      - static_files:/var/www/static:ro
    depends_on:
      odoo:
        condition: service_healthy
      fastapi:
        condition: service_healthy
    restart: unless-stopped
    networks:
      - sd_frontend

  # --- Odoo 19 CE ---
  odoo:
    image: smartdairy/odoo:19-ce-phase1
    container_name: sd_odoo
    build:
      context: ../../
      dockerfile: docker/staging/Dockerfile.odoo
    environment:
      - DB_HOST=postgres_primary
      - DB_PORT=5432
      - DB_USER=${ODOO_DB_USER}
      - DB_PASSWORD=${ODOO_DB_PASSWORD}
      - DB_NAME=smart_dairy_staging
      - REDIS_URL=redis://redis_primary:6379/0
      - ADMIN_PASSWD=${ODOO_ADMIN_PASSWD}
    volumes:
      - odoo_data:/var/lib/odoo
      - odoo_addons:/mnt/extra-addons
      - static_files:/var/www/static
    depends_on:
      postgres_primary:
        condition: service_healthy
      redis_primary:
        condition: service_healthy
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8069/web/health"]
      interval: 30s
      timeout: 10s
      retries: 5
      start_period: 60s
    restart: unless-stopped
    networks:
      - sd_frontend
      - sd_backend

  # --- FastAPI Service ---
  fastapi:
    image: smartdairy/fastapi:phase1
    container_name: sd_fastapi
    build:
      context: ../../
      dockerfile: docker/staging/Dockerfile.fastapi
    environment:
      - DATABASE_URL=postgresql://${API_DB_USER}:${API_DB_PASSWORD}@postgres_primary:5432/smart_dairy_staging
      - REDIS_URL=redis://redis_primary:6379/1
      - ELASTICSEARCH_URL=http://elasticsearch:9200
      - JWT_SECRET_KEY=${JWT_SECRET_KEY}
      - JWT_ALGORITHM=RS256
      - ODOO_URL=http://odoo:8069
      - ENVIRONMENT=staging
    depends_on:
      postgres_primary:
        condition: service_healthy
      redis_primary:
        condition: service_healthy
      elasticsearch:
        condition: service_healthy
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8000/api/v1/health"]
      interval: 15s
      timeout: 5s
      retries: 3
    restart: unless-stopped
    networks:
      - sd_frontend
      - sd_backend

  # --- PostgreSQL Primary ---
  postgres_primary:
    image: timescale/timescaledb:latest-pg16
    container_name: sd_postgres_primary
    environment:
      - POSTGRES_USER=${PG_USER}
      - POSTGRES_PASSWORD=${PG_PASSWORD}
      - POSTGRES_DB=smart_dairy_staging
    volumes:
      - pg_primary_data:/var/lib/postgresql/data
      - ./postgres/init:/docker-entrypoint-initdb.d:ro
      - ./postgres/postgresql.conf:/etc/postgresql/postgresql.conf:ro
    command: postgres -c config_file=/etc/postgresql/postgresql.conf
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U ${PG_USER} -d smart_dairy_staging"]
      interval: 10s
      timeout: 5s
      retries: 5
    restart: unless-stopped
    networks:
      - sd_backend

  # --- PostgreSQL Replica ---
  postgres_replica:
    image: timescale/timescaledb:latest-pg16
    container_name: sd_postgres_replica
    environment:
      - POSTGRES_USER=${PG_USER}
      - POSTGRES_PASSWORD=${PG_PASSWORD}
      - PGDATA=/var/lib/postgresql/data
    volumes:
      - pg_replica_data:/var/lib/postgresql/data
    depends_on:
      postgres_primary:
        condition: service_healthy
    restart: unless-stopped
    networks:
      - sd_backend

  # --- Redis Primary ---
  redis_primary:
    image: redis:7-alpine
    container_name: sd_redis_primary
    command: >
      redis-server
      --requirepass ${REDIS_PASSWORD}
      --maxmemory 256mb
      --maxmemory-policy allkeys-lru
      --appendonly yes
    volumes:
      - redis_data:/data
    healthcheck:
      test: ["CMD", "redis-cli", "-a", "${REDIS_PASSWORD}", "ping"]
      interval: 10s
      timeout: 5s
      retries: 3
    restart: unless-stopped
    networks:
      - sd_backend

  # --- Elasticsearch ---
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:8.12.0
    container_name: sd_elasticsearch
    environment:
      - discovery.type=single-node
      - xpack.security.enabled=false
      - ES_JAVA_OPTS=-Xms512m -Xmx512m
    volumes:
      - es_data:/usr/share/elasticsearch/data
    healthcheck:
      test: ["CMD-SHELL", "curl -f http://localhost:9200/_cluster/health || exit 1"]
      interval: 15s
      timeout: 10s
      retries: 5
    restart: unless-stopped
    networks:
      - sd_backend

volumes:
  pg_primary_data:
  pg_replica_data:
  redis_data:
  es_data:
  odoo_data:
  odoo_addons:
  static_files:

networks:
  sd_frontend:
    driver: bridge
  sd_backend:
    driver: bridge
    internal: true
```

### 4.10 Staging Deployment Script

```bash
#!/usr/bin/env bash
# scripts/deploy_staging.sh
# Smart Dairy - Staging deployment with health checks and rollback
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
COMPOSE_FILE="${PROJECT_ROOT}/docker/staging/docker-compose.staging.yml"
ENV_FILE="${PROJECT_ROOT}/docker/staging/.env.staging"
DEPLOY_LOG="${PROJECT_ROOT}/logs/deploy_$(date +%Y%m%d_%H%M%S).log"
BACKUP_TAG=""

log() { echo "[$(date '+%Y-%m-%d %H:%M:%S')] $*" | tee -a "$DEPLOY_LOG"; }

# --- Pre-deployment checks ---
pre_deploy_checks() {
    log "=== Pre-deployment Checks ==="
    command -v docker >/dev/null 2>&1 || { log "ERROR: docker not found"; exit 1; }
    command -v docker compose >/dev/null 2>&1 || { log "ERROR: docker compose not found"; exit 1; }
    [ -f "$COMPOSE_FILE" ] || { log "ERROR: Compose file not found"; exit 1; }
    [ -f "$ENV_FILE" ] || { log "ERROR: .env.staging not found"; exit 1; }
    log "Pre-deployment checks passed."
}

# --- Backup current state ---
backup_current() {
    log "=== Backing Up Current State ==="
    BACKUP_TAG="backup_$(date +%Y%m%d_%H%M%S)"

    # Backup database
    docker exec sd_postgres_primary pg_dump \
        -U "$PG_USER" -d smart_dairy_staging \
        --format=custom \
        -f "/tmp/${BACKUP_TAG}.dump" 2>/dev/null || log "WARN: No existing DB to backup"

    if docker exec sd_postgres_primary test -f "/tmp/${BACKUP_TAG}.dump" 2>/dev/null; then
        docker cp "sd_postgres_primary:/tmp/${BACKUP_TAG}.dump" \
            "${PROJECT_ROOT}/backups/${BACKUP_TAG}.dump"
        log "Database backup saved: ${BACKUP_TAG}.dump"
    fi

    # Tag current images for rollback
    for svc in odoo fastapi; do
        local img="smartdairy/${svc}:phase1"
        if docker image inspect "$img" >/dev/null 2>&1; then
            docker tag "$img" "smartdairy/${svc}:${BACKUP_TAG}"
            log "Tagged $img as smartdairy/${svc}:${BACKUP_TAG}"
        fi
    done
}

# --- Deploy ---
deploy() {
    log "=== Deploying to Staging ==="

    # Build images
    log "Building images..."
    docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" build --no-cache

    # Stop existing services
    log "Stopping existing services..."
    docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" down --timeout 30

    # Start services
    log "Starting services..."
    docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" up -d

    # Wait for health checks
    log "Waiting for services to become healthy..."
    local max_wait=300
    local elapsed=0
    while [ $elapsed -lt $max_wait ]; do
        if docker compose -f "$COMPOSE_FILE" ps | grep -q "unhealthy\|starting"; then
            sleep 10
            elapsed=$((elapsed + 10))
            log "  Waiting... (${elapsed}s / ${max_wait}s)"
        else
            log "All services healthy after ${elapsed}s"
            return 0
        fi
    done

    log "ERROR: Services did not become healthy within ${max_wait}s"
    return 1
}

# --- Health check ---
health_check() {
    log "=== Running Health Checks ==="
    local failed=0

    # Odoo health
    if curl -sf http://localhost:8069/web/health >/dev/null; then
        log "  Odoo: HEALTHY"
    else
        log "  Odoo: FAILED"
        failed=1
    fi

    # FastAPI health
    if curl -sf http://localhost:8000/api/v1/health >/dev/null; then
        log "  FastAPI: HEALTHY"
    else
        log "  FastAPI: FAILED"
        failed=1
    fi

    # PostgreSQL
    if docker exec sd_postgres_primary pg_isready -U "$PG_USER" >/dev/null; then
        log "  PostgreSQL: HEALTHY"
    else
        log "  PostgreSQL: FAILED"
        failed=1
    fi

    # Redis
    if docker exec sd_redis_primary redis-cli -a "$REDIS_PASSWORD" ping | grep -q PONG; then
        log "  Redis: HEALTHY"
    else
        log "  Redis: FAILED"
        failed=1
    fi

    # Elasticsearch
    if curl -sf http://localhost:9200/_cluster/health >/dev/null; then
        log "  Elasticsearch: HEALTHY"
    else
        log "  Elasticsearch: FAILED"
        failed=1
    fi

    return $failed
}

# --- Rollback ---
rollback() {
    log "=== ROLLING BACK ==="
    if [ -z "$BACKUP_TAG" ]; then
        log "ERROR: No backup tag available for rollback"
        exit 1
    fi

    # Restore images
    for svc in odoo fastapi; do
        docker tag "smartdairy/${svc}:${BACKUP_TAG}" "smartdairy/${svc}:phase1"
    done

    # Restart with old images
    docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" down --timeout 30
    docker compose -f "$COMPOSE_FILE" --env-file "$ENV_FILE" up -d

    # Restore database if needed
    if [ -f "${PROJECT_ROOT}/backups/${BACKUP_TAG}.dump" ]; then
        sleep 30  # Wait for PostgreSQL
        docker cp "${PROJECT_ROOT}/backups/${BACKUP_TAG}.dump" \
            "sd_postgres_primary:/tmp/restore.dump"
        docker exec sd_postgres_primary pg_restore \
            -U "$PG_USER" -d smart_dairy_staging \
            --clean --if-exists "/tmp/restore.dump"
        log "Database restored from ${BACKUP_TAG}"
    fi

    log "Rollback completed."
}

# --- Main ---
main() {
    log "========================================"
    log "Smart Dairy - Staging Deployment"
    log "========================================"

    source "$ENV_FILE"
    mkdir -p "${PROJECT_ROOT}/backups" "${PROJECT_ROOT}/logs"

    pre_deploy_checks
    backup_current

    if deploy; then
        if health_check; then
            log "=== Deployment Successful ==="
        else
            log "Health checks failed. Initiating rollback."
            rollback
            exit 1
        fi
    else
        log "Deployment failed. Initiating rollback."
        rollback
        exit 1
    fi
}

main "$@"
```

### 4.11 Smoke Test Suite for Staging

```python
# tests/smoke/test_staging_smoke.py
"""
Smoke test suite to verify all staging services are operational.
Run after every staging deployment.
"""
import pytest
import requests
import redis
import psycopg2
import os


STAGING_URL = os.getenv("STAGING_URL", "https://staging.smartdairy.local")
API_URL = f"{STAGING_URL}/api/v1"
DB_CONFIG = {
    "host": os.getenv("STAGING_DB_HOST", "staging.smartdairy.local"),
    "port": int(os.getenv("STAGING_DB_PORT", "5432")),
    "dbname": "smart_dairy_staging",
    "user": os.getenv("STAGING_DB_USER", "sd_staging"),
    "password": os.getenv("STAGING_DB_PASSWORD"),
}
REDIS_URL = os.getenv("STAGING_REDIS_URL", "redis://staging.smartdairy.local:6379/0")


@pytest.mark.smoke
class TestStagingSmoke:
    """Verify all staging services are up and responsive."""

    def test_odoo_web_accessible(self):
        """Verify Odoo web interface is reachable."""
        resp = requests.get(f"{STAGING_URL}/web/login", timeout=10, verify=True)
        assert resp.status_code == 200
        assert "Odoo" in resp.text or "Login" in resp.text

    def test_api_health_endpoint(self):
        """Verify FastAPI health endpoint returns OK."""
        resp = requests.get(f"{API_URL}/health", timeout=5)
        assert resp.status_code == 200
        body = resp.json()
        assert body["status"] == "healthy"
        assert body["database"] == "connected"
        assert body["redis"] == "connected"

    def test_api_version_endpoint(self):
        """Verify API version endpoint returns correct version."""
        resp = requests.get(f"{API_URL}/version", timeout=5)
        assert resp.status_code == 200
        assert "version" in resp.json()

    def test_database_connectivity(self):
        """Verify PostgreSQL is accessible and responsive."""
        conn = psycopg2.connect(**DB_CONFIG)
        cursor = conn.cursor()
        cursor.execute("SELECT version();")
        version = cursor.fetchone()[0]
        assert "PostgreSQL" in version
        cursor.execute("SELECT 1;")
        assert cursor.fetchone()[0] == 1
        cursor.close()
        conn.close()

    def test_timescaledb_extension(self):
        """Verify TimescaleDB extension is installed."""
        conn = psycopg2.connect(**DB_CONFIG)
        cursor = conn.cursor()
        cursor.execute(
            "SELECT extname FROM pg_extension WHERE extname = 'timescaledb';"
        )
        result = cursor.fetchone()
        assert result is not None
        cursor.close()
        conn.close()

    def test_redis_connectivity(self):
        """Verify Redis is accessible and responsive."""
        client = redis.from_url(REDIS_URL, decode_responses=True)
        assert client.ping() is True
        client.set("smoke_test_key", "ok", ex=60)
        assert client.get("smoke_test_key") == "ok"
        client.delete("smoke_test_key")
        client.close()

    def test_elasticsearch_connectivity(self):
        """Verify Elasticsearch cluster is healthy."""
        resp = requests.get(
            f"{STAGING_URL.replace('https', 'http').replace('443', '9200')}/_cluster/health",
            timeout=10,
        )
        assert resp.status_code == 200
        health = resp.json()
        assert health["status"] in ("green", "yellow")

    def test_ssl_certificate_valid(self):
        """Verify SSL certificate is valid and not expired."""
        resp = requests.get(STAGING_URL, timeout=10, verify=True)
        assert resp.status_code == 200

    def test_login_endpoint_available(self):
        """Verify authentication endpoint accepts requests."""
        resp = requests.post(f"{API_URL}/auth/login", json={
            "username": "smoke_test",
            "password": "invalid",
        }, timeout=5)
        assert resp.status_code in (401, 422)

    def test_static_assets_served(self):
        """Verify static assets are served by Nginx."""
        resp = requests.get(f"{STAGING_URL}/web/static/src/img/logo.png", timeout=5)
        assert resp.status_code == 200
        assert "image" in resp.headers.get("Content-Type", "")
```

### 4.12 UAT Test Script Document

```python
# tests/uat/uat_test_scripts.py
"""
UAT test scripts: 15 user stories with step-by-step instructions.
Used during stakeholder walkthrough on Day 97.
"""

UAT_TEST_SCRIPTS = [
    {
        "id": "UAT-001",
        "story": "As a farm manager, I can log in to the portal with MFA",
        "priority": "P0",
        "steps": [
            "Navigate to https://staging.smartdairy.local/web/login",
            "Enter username: farm_manager and password: Demo@2026!",
            "System prompts for MFA code",
            "Enter TOTP code from authenticator app",
            "System redirects to main dashboard",
        ],
        "expected": "User sees the farm dashboard with today's summary",
        "result": None,  # pass / fail
        "notes": "",
    },
    {
        "id": "UAT-002",
        "story": "As a farm manager, I can view the daily milk production dashboard",
        "priority": "P0",
        "steps": [
            "From dashboard, click 'Milk Production' widget",
            "View today's total: approximately 900L across 75 cows",
            "Toggle between morning/evening sessions",
            "View production trend chart for last 30 days",
        ],
        "expected": "Dashboard shows accurate production data with charts",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-003",
        "story": "As a milk collector, I can record a new milk collection",
        "priority": "P0",
        "steps": [
            "Navigate to Milk Collection > New Collection",
            "Select animal from dropdown (e.g., SD-COW-001)",
            "Enter quantity: 12.5L, fat: 3.8%, SNF: 8.5%",
            "Enter temperature: 4.2 degrees Celsius",
            "Click 'Save and Submit'",
        ],
        "expected": "Record saved, batch ID generated, inventory updated",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-004",
        "story": "As a quality officer, I can perform milk quality checks",
        "priority": "P0",
        "steps": [
            "Navigate to Quality > Pending Checks",
            "Select batch from today's collections",
            "Enter SCC: 180000, bacterial count: 25000",
            "Mark antibiotic residue: No",
            "Click 'Submit Quality Check'",
        ],
        "expected": "Quality check recorded, batch approved/rejected based on thresholds",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-005",
        "story": "As a farm manager, I can view all animals with their status",
        "priority": "P0",
        "steps": [
            "Navigate to Farm > Animals",
            "View list of 255 cattle with status filters",
            "Filter by 'Lactating' to see 75 cows",
            "Click on an animal to view detailed profile",
        ],
        "expected": "Animal list loads with correct counts and filtering works",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-006",
        "story": "As a farm manager, I can register a new animal",
        "priority": "P1",
        "steps": [
            "Navigate to Farm > Animals > Create",
            "Fill in: name, tag, breed, DOB, gender, weight",
            "Upload animal photo",
            "Click 'Save'",
        ],
        "expected": "New animal record created with all fields populated",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-007",
        "story": "As a sales manager, I can create a milk sale order",
        "priority": "P0",
        "steps": [
            "Navigate to Sales > New Order",
            "Select customer from partner list",
            "Add product line: Fresh Milk, 100L at 80 BDT/L",
            "Set delivery date",
            "Click 'Confirm Order'",
        ],
        "expected": "Sale order confirmed, delivery order created",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-008",
        "story": "As an accountant, I can generate and view invoices",
        "priority": "P0",
        "steps": [
            "Navigate to Accounting > Invoices",
            "View list of invoices generated from confirmed orders",
            "Open an invoice to verify line items and totals",
            "Verify journal entries are correctly posted",
        ],
        "expected": "Invoices match sale orders; journal entries balance",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-009",
        "story": "As a farm manager, I can view inventory levels",
        "priority": "P1",
        "steps": [
            "Navigate to Inventory > Dashboard",
            "View current milk stock by quality grade",
            "View feed inventory levels",
            "Check low-stock alerts",
        ],
        "expected": "Inventory accurately reflects collections minus sales",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-010",
        "story": "As a farm manager, I can generate production reports",
        "priority": "P1",
        "steps": [
            "Navigate to Reports > Production",
            "Select date range: last 30 days",
            "View total production, per-cow average, quality metrics",
            "Export report as PDF",
        ],
        "expected": "Report generates with accurate data and exports correctly",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-011",
        "story": "As an admin, I can manage user roles and permissions",
        "priority": "P1",
        "steps": [
            "Navigate to Settings > Users",
            "Create new user with 'Milk Collector' role",
            "Verify role restricts access to only milk collection features",
            "Attempt to access admin settings with restricted user",
        ],
        "expected": "Role-based access control works correctly",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-012",
        "story": "As a farm manager, I can search for animals and records",
        "priority": "P2",
        "steps": [
            "Use global search bar to search 'SD-COW-001'",
            "Verify animal record appears in results",
            "Search for 'BATCH-2026' to find milk batches",
            "Verify search results are relevant and fast",
        ],
        "expected": "Search returns relevant results in under 2 seconds",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-013",
        "story": "As a mobile user, I can record milk collection on phone",
        "priority": "P1",
        "steps": [
            "Open Smart Dairy mobile app on Android phone",
            "Log in with farm credentials",
            "Navigate to Quick Collection",
            "Scan animal QR code or select from list",
            "Enter milk quantity and submit",
        ],
        "expected": "Collection recorded and synced to server",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-014",
        "story": "As an auditor, I can view system audit logs",
        "priority": "P1",
        "steps": [
            "Navigate to Settings > Audit Logs",
            "Filter by date range and action type",
            "View login events, data modifications, security events",
            "Export audit log as CSV",
        ],
        "expected": "All system activities are logged with timestamps and user IDs",
        "result": None,
        "notes": "",
    },
    {
        "id": "UAT-015",
        "story": "As a farm manager, I can view animal health records",
        "priority": "P2",
        "steps": [
            "Navigate to Farm > Animals > select cow",
            "Click 'Health Records' tab",
            "View vaccination history, health events, vet visits",
            "Add new health record entry",
        ],
        "expected": "Health records display correctly and new entries save",
        "result": None,
        "notes": "",
    },
]
```

### 4.13 Bug Report Template and Severity Classification

```python
# tests/templates/bug_report_template.py
"""
Bug report template and severity classification system.
Used during UAT and testing phases to track issues.
"""

SEVERITY_LEVELS = {
    "P0": {
        "name": "Critical — System Down",
        "description": "System is completely unavailable or data loss is occurring",
        "response_time": "Immediate (within 1 hour)",
        "resolution_target": "4 hours",
        "examples": [
            "Application crashes on startup",
            "Database corruption or data loss",
            "Security breach or authentication bypass",
            "All users unable to log in",
        ],
    },
    "P1": {
        "name": "High — Major Feature Broken",
        "description": "Core functionality is broken with no workaround",
        "response_time": "Within 4 hours",
        "resolution_target": "24 hours",
        "examples": [
            "Milk collection cannot be recorded",
            "Invoices generate incorrect amounts",
            "Quality checks not saving to database",
            "Dashboard shows wrong production data",
        ],
    },
    "P2": {
        "name": "Medium — Minor Issue",
        "description": "Feature works but with limitations or inconvenience",
        "response_time": "Within 1 business day",
        "resolution_target": "3 business days",
        "examples": [
            "Search returns results slowly (> 5 seconds)",
            "Report export includes extra blank page",
            "Date picker defaults to wrong timezone",
            "Pagination shows incorrect total count",
        ],
    },
    "P3": {
        "name": "Low — Cosmetic",
        "description": "Visual or cosmetic issue with no functional impact",
        "response_time": "Within 3 business days",
        "resolution_target": "Next sprint",
        "examples": [
            "Misaligned button on settings page",
            "Typo in help text",
            "Inconsistent icon sizing",
            "Color mismatch with brand guidelines",
        ],
    },
}

BUG_REPORT_TEMPLATE = {
    "bug_id": "",              # Auto-generated: BUG-YYYY-NNNN
    "title": "",               # Short descriptive title
    "severity": "",            # P0, P1, P2, P3
    "status": "open",          # open, in_progress, resolved, verified, closed
    "reported_by": "",         # Name of reporter
    "reported_date": "",       # ISO date
    "assigned_to": "",         # Developer assigned
    "milestone": "",           # Which milestone affected
    "module": "",              # Odoo module or component
    "environment": "",         # staging, test, local
    "description": "",         # Detailed description
    "steps_to_reproduce": [],  # Numbered steps
    "expected_result": "",     # What should happen
    "actual_result": "",       # What actually happens
    "screenshots": [],         # File paths or URLs
    "logs": "",                # Relevant log entries
    "resolution": "",          # How it was fixed
    "resolution_date": "",     # When fixed
    "verified_by": "",         # Who verified the fix
}
```

### 4.14 Phase 1 Completion Checklist

```python
# docs/phase1_completion_checklist.py
"""
Phase 1 completion checklist — all deliverables must be verified
before formal sign-off can be granted.
"""

PHASE_1_COMPLETION_CHECKLIST = {
    "milestone_1_environment": {
        "title": "M1: Development Environment Setup",
        "items": [
            {"item": "Docker development environment operational", "verified": False},
            {"item": "Odoo 19 CE installed and running", "verified": False},
            {"item": "PostgreSQL 16 with TimescaleDB configured", "verified": False},
            {"item": "Redis 7 cache layer operational", "verified": False},
            {"item": "Elasticsearch 8 search engine running", "verified": False},
            {"item": "CI/CD pipeline configured and tested", "verified": False},
        ],
    },
    "milestone_2_core_modules": {
        "title": "M2: Core Odoo Modules Configuration",
        "items": [
            {"item": "Sales module configured for dairy products", "verified": False},
            {"item": "Inventory module with milk batch tracking", "verified": False},
            {"item": "Accounting module with Bangladesh chart of accounts", "verified": False},
            {"item": "Purchase module for feed and supplies", "verified": False},
            {"item": "HR module for farm staff management", "verified": False},
        ],
    },
    "milestone_3_farm_module": {
        "title": "M3: Custom Farm Management Module",
        "items": [
            {"item": "Animal registry with 255 cattle records", "verified": False},
            {"item": "Milk collection workflow operational", "verified": False},
            {"item": "Quality check system with thresholds", "verified": False},
            {"item": "Health records and vaccination tracking", "verified": False},
            {"item": "Breeding and reproduction management", "verified": False},
        ],
    },
    "milestone_4_db_optimization": {
        "title": "M4: Database Optimization",
        "items": [
            {"item": "TimescaleDB hypertables for time-series data", "verified": False},
            {"item": "Query optimization (all queries < 100ms)", "verified": False},
            {"item": "Database indexing strategy implemented", "verified": False},
            {"item": "Connection pooling configured", "verified": False},
        ],
    },
    "milestone_5_integration": {
        "title": "M5: Integration Testing",
        "items": [
            {"item": "API integration tests passing", "verified": False},
            {"item": "Module-to-module integration verified", "verified": False},
            {"item": "External service integration tested", "verified": False},
        ],
    },
    "milestone_6_security_arch": {
        "title": "M6: Security Architecture",
        "items": [
            {"item": "Security architecture documented", "verified": False},
            {"item": "Encryption at rest and in transit", "verified": False},
            {"item": "Security headers configured", "verified": False},
        ],
    },
    "milestone_7_iam": {
        "title": "M7: Identity & Access Management",
        "items": [
            {"item": "Role-based access control implemented", "verified": False},
            {"item": "MFA enabled for all admin users", "verified": False},
            {"item": "Session management and token rotation", "verified": False},
        ],
    },
    "milestone_8_db_security": {
        "title": "M8: Database Security",
        "items": [
            {"item": "Row-level security policies active", "verified": False},
            {"item": "Database audit logging enabled", "verified": False},
            {"item": "Backup encryption configured", "verified": False},
        ],
    },
    "milestone_9_api_security": {
        "title": "M9: API Security",
        "items": [
            {"item": "Rate limiting on all endpoints", "verified": False},
            {"item": "Input validation and sanitization", "verified": False},
            {"item": "JWT authentication with RS256", "verified": False},
            {"item": "CORS and CSP headers configured", "verified": False},
        ],
    },
    "milestone_10_testing": {
        "title": "M10: Testing & Deployment",
        "items": [
            {"item": "Unit test coverage >= 80%", "verified": False},
            {"item": "All integration tests passing", "verified": False},
            {"item": "Zero critical/high security findings", "verified": False},
            {"item": "Performance SLAs met", "verified": False},
            {"item": "Staging environment deployed and stable", "verified": False},
            {"item": "All 15 UAT stories signed off", "verified": False},
            {"item": "All P0/P1 bugs resolved", "verified": False},
            {"item": "Documentation package complete", "verified": False},
        ],
    },
    "cross_cutting": {
        "title": "Cross-Cutting Concerns",
        "items": [
            {"item": "All code reviewed and merged to main branch", "verified": False},
            {"item": "Release tag created in version control", "verified": False},
            {"item": "Deployment runbook completed", "verified": False},
            {"item": "Disaster recovery plan documented", "verified": False},
            {"item": "Team knowledge transfer completed", "verified": False},
            {"item": "Formal sign-off obtained from stakeholders", "verified": False},
        ],
    },
}
```

### 4.15 Phase 2 Transition Document

```python
# docs/phase2_transition.py
"""
Phase 2 transition document: what carries forward, known technical debt,
and recommended Phase 2 priorities.
"""

PHASE_2_TRANSITION = {
    "carries_forward": {
        "infrastructure": [
            "Docker-based deployment pipeline",
            "PostgreSQL 16 + TimescaleDB database",
            "Redis 7 caching layer",
            "Elasticsearch 8 search infrastructure",
            "CI/CD pipeline with automated testing",
            "Nginx reverse proxy with SSL",
        ],
        "codebase": [
            "Odoo 19 CE with all Phase 1 custom modules",
            "FastAPI service layer",
            "OWL frontend components",
            "Flutter mobile application (beta)",
            "Comprehensive test suite (unit, integration, security, performance)",
        ],
        "documentation": [
            "Architecture decision records",
            "API documentation (OpenAPI)",
            "Deployment runbook",
            "User guides",
        ],
    },
    "known_technical_debt": [
        {
            "id": "TD-001",
            "area": "Backend",
            "description": "Some Odoo ORM queries not fully optimized for large datasets",
            "impact": "Medium — may affect performance at scale beyond 1000 animals",
            "recommendation": "Implement pagination and lazy loading in Phase 2",
        },
        {
            "id": "TD-002",
            "area": "Frontend",
            "description": "OWL components lack comprehensive accessibility (a11y) support",
            "impact": "Low — not regulatory requirement but best practice",
            "recommendation": "Add ARIA labels and keyboard navigation in Phase 2",
        },
        {
            "id": "TD-003",
            "area": "Mobile",
            "description": "Flutter offline sync has edge cases with conflict resolution",
            "impact": "Medium — may cause data inconsistency in poor connectivity areas",
            "recommendation": "Implement conflict resolution UI in Phase 2",
        },
        {
            "id": "TD-004",
            "area": "Infrastructure",
            "description": "PostgreSQL HA uses streaming replication; no automatic failover",
            "impact": "Medium — manual intervention required for DB failover",
            "recommendation": "Evaluate Patroni or pgBouncer for Phase 2 HA",
        },
        {
            "id": "TD-005",
            "area": "Testing",
            "description": "E2E tests cover happy paths; edge case coverage is limited",
            "impact": "Low — core flows tested but unusual scenarios may have bugs",
            "recommendation": "Expand E2E test scenarios in Phase 2",
        },
    ],
    "phase_2_recommended_priorities": [
        {
            "priority": 1,
            "feature": "Advanced Reporting & Analytics",
            "description": "Build comprehensive BI dashboards with predictive analytics for milk yield, animal health, and financial forecasting",
            "estimated_sprints": 3,
        },
        {
            "priority": 2,
            "feature": "Mobile App Production Release",
            "description": "Complete Flutter app with offline-first architecture, push notifications, and Play Store deployment",
            "estimated_sprints": 2,
        },
        {
            "priority": 3,
            "feature": "Supply Chain Integration",
            "description": "Integrate with suppliers and distributors for automated ordering and delivery tracking",
            "estimated_sprints": 2,
        },
        {
            "priority": 4,
            "feature": "IoT Sensor Integration",
            "description": "Connect milk meters, temperature sensors, and health monitoring devices",
            "estimated_sprints": 3,
        },
        {
            "priority": 5,
            "feature": "Customer Portal",
            "description": "Self-service portal for B2B customers to place orders and track deliveries",
            "estimated_sprints": 2,
        },
    ],
    "phase_2_sprint_0_plan": {
        "duration": "5 days",
        "objectives": [
            "Review and refine Phase 2 backlog based on Phase 1 learnings",
            "Upgrade any dependencies with security patches released during Phase 1",
            "Set up Phase 2 feature branches and project board",
            "Capacity planning for Phase 2 sprints",
            "Technical spike for top-priority features",
        ],
    },
}
```

### 4.16 OWL TestResultsDashboard Component

```javascript
/** @odoo-module **/
// smart_dairy/static/src/components/test_results_dashboard/test_results_dashboard.js
// OWL component for visualizing test results, coverage, and performance benchmarks.

import { Component, useState, onWillStart } from "@odoo/owl";
import { registry } from "@web/core/registry";
import { useService } from "@web/core/utils/hooks";

export class TestResultsDashboard extends Component {
    static template = "smart_dairy.TestResultsDashboard";
    static props = {};

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: true,
            summary: {
                totalTests: 0,
                passed: 0,
                failed: 0,
                skipped: 0,
                passRate: 0,
            },
            coverage: {
                overall: 0,
                byModule: [],
            },
            performance: {
                pageLoad: { p50: 0, p95: 0, p99: 0, sla: 2000 },
                apiResponse: { p50: 0, p95: 0, p99: 0, sla: 200 },
                concurrentUsers: { nominal: 0, stress: 0, breaking: 0 },
            },
            security: {
                critical: 0,
                high: 0,
                medium: 0,
                low: 0,
                lastScanDate: "",
            },
            milestoneStatus: [],
        });

        onWillStart(async () => {
            await this.loadTestResults();
        });
    }

    async loadTestResults() {
        try {
            const results = await this.rpc("/api/v1/test-results/summary", {});

            this.state.summary = {
                totalTests: results.total_tests,
                passed: results.passed,
                failed: results.failed,
                skipped: results.skipped,
                passRate: results.total_tests > 0
                    ? ((results.passed / results.total_tests) * 100).toFixed(1)
                    : 0,
            };

            this.state.coverage = {
                overall: results.coverage.overall_percent,
                byModule: results.coverage.modules.map(m => ({
                    name: m.name,
                    coverage: m.coverage_percent,
                    lines: m.lines_covered,
                    totalLines: m.total_lines,
                    meetsThreshold: m.coverage_percent >= 80,
                })),
            };

            this.state.performance = {
                pageLoad: {
                    p50: results.performance.page_load_p50,
                    p95: results.performance.page_load_p95,
                    p99: results.performance.page_load_p99,
                    sla: 2000,
                    meetsSLA: results.performance.page_load_p95 <= 2000,
                },
                apiResponse: {
                    p50: results.performance.api_p50,
                    p95: results.performance.api_p95,
                    p99: results.performance.api_p99,
                    sla: 200,
                    meetsSLA: results.performance.api_p95 <= 200,
                },
                concurrentUsers: {
                    nominal: results.performance.concurrent_nominal,
                    stress: results.performance.concurrent_stress,
                    breaking: results.performance.breaking_point,
                },
            };

            this.state.security = {
                critical: results.security.critical_count,
                high: results.security.high_count,
                medium: results.security.medium_count,
                low: results.security.low_count,
                lastScanDate: results.security.last_scan_date,
                passesGate: results.security.critical_count === 0
                    && results.security.high_count === 0,
            };

            this.state.milestoneStatus = results.milestones.map(m => ({
                id: m.id,
                title: m.title,
                testsTotal: m.tests_total,
                testsPassed: m.tests_passed,
                status: m.tests_passed === m.tests_total ? "complete" : "issues",
            }));

            this.state.loading = false;
        } catch (error) {
            this.notification.add("Failed to load test results", { type: "danger" });
            this.state.loading = false;
        }
    }

    get coverageColor() {
        const pct = this.state.coverage.overall;
        if (pct >= 80) return "text-success";
        if (pct >= 60) return "text-warning";
        return "text-danger";
    }

    get passRateColor() {
        const rate = parseFloat(this.state.summary.passRate);
        if (rate >= 99) return "text-success";
        if (rate >= 95) return "text-warning";
        return "text-danger";
    }

    get securityStatusLabel() {
        if (this.state.security.passesGate) return "PASS";
        return "FAIL";
    }

    get securityStatusColor() {
        return this.state.security.passesGate ? "badge-success" : "badge-danger";
    }

    async refreshResults() {
        this.state.loading = true;
        await this.loadTestResults();
    }
}

registry.category("actions").add("smart_dairy.test_results_dashboard", TestResultsDashboard);
```

### 4.17 Retrospective Template

```python
# docs/templates/retrospective_template.py
"""
Phase 1 retrospective template.
Used on Day 100 to capture team learnings.
"""

RETROSPECTIVE_TEMPLATE = {
    "phase": "Phase 1 — Foundation",
    "date": "2026-02-03",
    "participants": ["Dev 1 (Backend Lead)", "Dev 2 (Full-Stack/DevOps)", "Dev 3 (Frontend/Mobile Lead)"],
    "duration_planned_days": 100,
    "duration_actual_days": 100,
    "what_went_well": [
        {
            "category": "Technical",
            "items": [
                # To be filled during retrospective
                # Example: "Odoo 19 CE module architecture was straightforward to extend"
                # Example: "TimescaleDB provided excellent time-series query performance"
            ],
        },
        {
            "category": "Process",
            "items": [
                # Example: "Daily standups kept all three developers aligned"
                # Example: "Milestone-based planning gave clear targets"
            ],
        },
        {
            "category": "Collaboration",
            "items": [
                # Example: "Code reviews caught issues early"
                # Example: "Shared Docker environment reduced 'works on my machine' issues"
            ],
        },
    ],
    "what_to_improve": [
        {
            "category": "Technical",
            "items": [
                # Example: "Need better automated DB migration testing"
                # Example: "OWL framework documentation was limited for advanced use cases"
            ],
        },
        {
            "category": "Process",
            "items": [
                # Example: "Security testing should start earlier (shift left)"
                # Example: "Need more structured code review checklist"
            ],
        },
        {
            "category": "Collaboration",
            "items": [
                # Example: "Frontend-backend API contract changes caused rework"
                # Example: "Need earlier stakeholder feedback loops"
            ],
        },
    ],
    "action_items": [
        # Specific, measurable actions for Phase 2
        # {
        #     "action": "Implement API contract testing with Pact",
        #     "owner": "Dev 1",
        #     "due": "Phase 2 Sprint 1",
        # },
    ],
    "kudos": [
        # Team appreciation
        # "Dev 2 for setting up the CI/CD pipeline that saved hours of manual testing"
    ],
}
```

### 4.18 Velocity and Burndown Metrics Calculation

```python
# docs/metrics/velocity_burndown.py
"""
Phase 1 velocity and burndown metrics calculation.
Tracks story points delivered per milestone, cumulative progress,
and schedule adherence.
"""
from dataclasses import dataclass
from typing import List


@dataclass
class MilestoneMetrics:
    milestone_id: int
    title: str
    planned_days: int
    actual_days: int
    planned_story_points: int
    completed_story_points: int
    bugs_found: int
    bugs_resolved: int
    start_day: int
    end_day: int


PHASE_1_MILESTONES: List[MilestoneMetrics] = [
    MilestoneMetrics(1, "Environment Setup", 10, 10, 20, 20, 3, 3, 1, 10),
    MilestoneMetrics(2, "Core Odoo Modules", 10, 10, 30, 30, 5, 5, 11, 20),
    MilestoneMetrics(3, "Custom Farm Module", 10, 10, 40, 40, 8, 8, 21, 30),
    MilestoneMetrics(4, "DB Optimization", 10, 10, 25, 25, 4, 4, 31, 40),
    MilestoneMetrics(5, "Integration Testing", 10, 10, 20, 20, 6, 6, 41, 50),
    MilestoneMetrics(6, "Security Architecture", 10, 10, 30, 30, 3, 3, 51, 60),
    MilestoneMetrics(7, "IAM Implementation", 10, 10, 25, 25, 4, 4, 61, 70),
    MilestoneMetrics(8, "Database Security", 10, 10, 20, 20, 2, 2, 71, 80),
    MilestoneMetrics(9, "API Security", 10, 10, 25, 25, 5, 5, 81, 90),
    MilestoneMetrics(10, "Testing & Deployment", 10, 10, 35, 0, 0, 0, 91, 100),
]


def calculate_velocity():
    """Calculate average velocity (story points per 10-day milestone)."""
    completed = [m for m in PHASE_1_MILESTONES if m.completed_story_points > 0]
    if not completed:
        return 0
    total_points = sum(m.completed_story_points for m in completed)
    return total_points / len(completed)


def calculate_burndown():
    """Calculate burndown data showing remaining work over time."""
    total_planned = sum(m.planned_story_points for m in PHASE_1_MILESTONES)
    burndown = []
    remaining = total_planned

    for m in PHASE_1_MILESTONES:
        remaining -= m.completed_story_points
        burndown.append({
            "day": m.end_day,
            "milestone": f"M{m.milestone_id}",
            "completed": m.completed_story_points,
            "remaining": remaining,
            "ideal_remaining": total_planned - (
                total_planned * m.end_day / 100
            ),
        })
    return burndown


def calculate_schedule_adherence():
    """Calculate percentage of milestones delivered on time."""
    on_time = sum(
        1 for m in PHASE_1_MILESTONES
        if m.actual_days <= m.planned_days
    )
    return (on_time / len(PHASE_1_MILESTONES)) * 100


def calculate_defect_density():
    """Calculate defects per story point."""
    total_bugs = sum(m.bugs_found for m in PHASE_1_MILESTONES)
    total_points = sum(m.planned_story_points for m in PHASE_1_MILESTONES)
    return total_bugs / total_points if total_points else 0


def generate_phase1_summary():
    """Generate complete Phase 1 metrics summary."""
    total_planned = sum(m.planned_story_points for m in PHASE_1_MILESTONES)
    total_completed = sum(m.completed_story_points for m in PHASE_1_MILESTONES)
    total_bugs_found = sum(m.bugs_found for m in PHASE_1_MILESTONES)
    total_bugs_resolved = sum(m.bugs_resolved for m in PHASE_1_MILESTONES)

    return {
        "total_story_points_planned": total_planned,
        "total_story_points_completed": total_completed,
        "completion_percentage": (
            (total_completed / total_planned * 100) if total_planned else 0
        ),
        "average_velocity": calculate_velocity(),
        "schedule_adherence": calculate_schedule_adherence(),
        "total_bugs_found": total_bugs_found,
        "total_bugs_resolved": total_bugs_resolved,
        "bug_resolution_rate": (
            (total_bugs_resolved / total_bugs_found * 100)
            if total_bugs_found else 100
        ),
        "defect_density": calculate_defect_density(),
        "burndown": calculate_burndown(),
    }
```

---

## 5. Testing & Validation

### 5.1 Test Execution Summary

| Test Type | Day | Tool | Target | Exit Criterion |
|---|---|---|---|---|
| Unit Tests | 92 | pytest + pytest-cov | All custom modules | Coverage >= 80% |
| Integration Tests | 93 | pytest + httpx | E2E flows | 100% pass rate |
| Security Tests | 94 | OWASP ZAP + manual | OWASP Top 10 | Zero critical/high |
| Performance Tests | 95 | K6 + Lighthouse | SLA thresholds | All SLAs met |
| Smoke Tests | 96 | pytest (smoke suite) | Staging services | All services healthy |
| UAT | 97 | Manual (15 stories) | Stakeholder approval | All stories signed off |
| Regression Tests | 98 | Full test suite | Post-bugfix validation | No regressions |

### 5.2 Test Data Requirements

| Data Set | Volume | Source |
|---|---|---|
| Animal records | 255 cattle (75 lactating) | Seeded from production schema |
| Milk collections | 90 days of records (2 sessions/day) | Generated via factory scripts |
| Quality checks | 1 per batch for 90 days | Generated with realistic distribution |
| Sale orders | 60 orders over 90 days | Generated with partner data |
| User accounts | 15 accounts across 5 roles | Created via IAM setup scripts |
| Audit logs | 10,000+ entries | Generated from simulated activity |

### 5.3 Test Environment Matrix

| Environment | Purpose | Infrastructure |
|---|---|---|
| Local | Developer unit tests | Docker Compose (minimal) |
| CI | Automated test suite | GitHub Actions runners |
| Test | Integration and security testing | Dedicated Docker host |
| Staging | UAT and performance testing | Production-mirror Docker host |

---

## 6. Risk & Mitigation

| ID | Risk | Probability | Impact | Mitigation |
|---|---|---|---|---|
| R-10-01 | Test coverage target not met | Medium | High | Start coverage analysis early (Day 92); prioritize critical path code |
| R-10-02 | Critical security vulnerability found | Low | Critical | Have Dev 1 on standby for immediate fixes; pre-test with basic scans on Day 91 |
| R-10-03 | Performance SLA not met under load | Medium | High | Profile and optimize on Day 95; have DB tuning playbook ready |
| R-10-04 | Staging deployment fails | Low | High | Rollback script tested; maintain backup of last known good state |
| R-10-05 | Stakeholder unavailable for UAT | Medium | Medium | Schedule UAT early; have async feedback option via recorded walkthrough |
| R-10-06 | P0 bugs exceed capacity on Day 98 | Low | High | Triage strictly; defer P2/P3 to Phase 2; all three devs fix P0 bugs |
| R-10-07 | Documentation incomplete by Day 99 | Medium | Medium | Start documentation incrementally from Day 91; use automated doc generation |
| R-10-08 | Phase 2 planning delays Phase 1 close | Low | Low | Timebox Phase 2 planning to 4 hours on Day 100; keep separate from sign-off |

---

## 7. Dependencies & Handoffs

### 7.1 Inbound Dependencies (from Prior Milestones)

| Source | Dependency | Status |
|---|---|---|
| M1 | Docker development environment operational | Required by Day 91 |
| M2 | Core Odoo modules configured and tested | Required by Day 91 |
| M3 | Custom farm module feature-complete | Required by Day 91 |
| M4 | Database optimization applied and verified | Required by Day 91 |
| M5 | Integration tests from M5 as baseline | Required by Day 91 |
| M6 | Security architecture implemented | Required by Day 94 |
| M7 | IAM system operational with MFA | Required by Day 94 |
| M8 | Database security (RLS, audit) active | Required by Day 94 |
| M9 | API security (rate limiting, JWT) active | Required by Day 94 |

### 7.2 Outbound Deliverables (to Phase 2)

| Deliverable | Recipient | Format |
|---|---|---|
| Tested and deployed staging environment | Phase 2 team | Docker Compose + staging server |
| Complete test suite (unit, integration, security, perf) | Phase 2 team | Git repository |
| Phase 1 documentation package | All stakeholders | Markdown + PDF |
| Phase 2 transition document | Project management | Structured document |
| Known technical debt backlog | Phase 2 sprint planning | Ticketing system |
| Formal Phase 1 sign-off | Executive sponsors | Signed document |

### 7.3 External Dependencies

| Dependency | Owner | Risk if Unavailable |
|---|---|---|
| Staging server access | IT Operations | Cannot deploy on Day 96; mitigate with cloud fallback |
| SSL certificate issuance | DevOps / Let's Encrypt | Fallback to self-signed for staging |
| Stakeholder availability (Day 97) | Farm management | Reschedule to Day 98; delays Phase 1 close |
| OWASP ZAP license (if commercial) | Dev 2 | Use community edition (free) |

---

## 8. Phase 1 Completion & Phase 2 Transition

### 8.1 Phase 1 Sign-Off Criteria

All of the following must be true for formal sign-off:

1. All unit tests passing with coverage greater than or equal to 80% on custom code
2. All integration tests passing (100% pass rate)
3. Zero critical or high security vulnerabilities (OWASP ZAP scan clean)
4. Performance SLAs met: page load under 2 seconds, API p95 under 200 milliseconds
5. Staging environment fully operational with valid SSL
6. All 15 UAT user stories signed off by stakeholders
7. All P0 bugs resolved; all P1 bugs resolved or have documented workarounds
8. Complete documentation package delivered and reviewed
9. Phase 2 transition document approved

### 8.2 Sign-Off Meeting Agenda (Day 100)

| Time | Topic | Presenter |
|---|---|---|
| 09:00–09:30 | Phase 1 summary and metrics review | Dev 2 |
| 09:30–10:00 | Backend deliverables and test results | Dev 1 |
| 10:00–10:30 | Frontend/mobile deliverables and UAT results | Dev 3 |
| 10:30–11:00 | Security and performance evidence | Dev 2 |
| 11:00–11:30 | Known technical debt and Phase 2 recommendations | All |
| 11:30–12:00 | Formal sign-off and Phase 2 Sprint 0 kickoff | All |

### 8.3 Phase 2 Preview

Phase 2 builds on the foundation laid in Phase 1 with the following focus areas:

- **Advanced Reporting:** BI dashboards, predictive analytics, automated alerts
- **Mobile Production Release:** Flutter app on Play Store and App Store
- **Supply Chain:** Supplier integration, automated purchasing, delivery tracking
- **IoT Integration:** Milk meters, temperature sensors, health monitors
- **Customer Portal:** B2B self-service ordering and delivery tracking

### 8.4 Continuous Improvement Commitments

Based on the Phase 1 retrospective, the team commits to the following improvements in Phase 2:

1. Shift security testing left by integrating SAST into the CI pipeline from Sprint 1
2. Implement API contract testing (Pact or similar) to prevent frontend-backend mismatches
3. Establish fortnightly stakeholder demos instead of waiting for UAT at the end
4. Improve test data management with dedicated seeding and teardown scripts
5. Add automated performance regression tests to the CI pipeline

---

*This document is maintained by the Smart Dairy development team. For questions or updates, contact the milestone authors listed in the header.*
