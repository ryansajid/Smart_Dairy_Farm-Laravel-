# Milestone 131: Comprehensive Test Plan

## Smart Dairy Digital Smart Portal + ERP - Phase 14: Testing & Documentation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 131 of 150 (1 of 10 in Phase 14)                                       |
| **Title**        | Comprehensive Test Plan                                                |
| **Phase**        | Phase 14 - Testing & Documentation                                     |
| **Days**         | Days 651-655 (of 750 total)                                            |
| **Duration**     | 5 working days                                                         |
| **Version**      | 1.0                                                                    |
| **Status**       | Draft                                                                  |
| **Authors**      | Dev 1 (Backend Lead), Dev 2 (Full-Stack), Dev 3 (Frontend Lead)        |

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

Establish a comprehensive test strategy and automation framework that will serve as the foundation for all testing activities in Phase 14. This includes setting up pytest for backend testing, Jest/Cypress for frontend testing, CI/CD test integration, and establishing coverage targets of 70%+ for unit tests and 60%+ for integration tests.

### 1.2 Objectives

1. Develop comprehensive test strategy document with scope, approach, and acceptance criteria
2. Configure pytest framework with fixtures, markers, and coverage reporting
3. Set up Jest for JavaScript unit testing with coverage thresholds
4. Configure Cypress for E2E testing with custom commands and fixtures
5. Integrate test execution into GitHub Actions CI/CD pipeline
6. Establish test data management strategy with factories and fixtures
7. Configure defect tracking workflow with severity classifications
8. Set up code coverage reporting with Codecov/SonarQube integration

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| Test Strategy Document            | Dev 1  | Markdown          | 651     |
| pytest Configuration              | Dev 1  | Python/YAML       | 652     |
| Jest Configuration                | Dev 3  | JavaScript/JSON   | 652     |
| Cypress Configuration             | Dev 3  | JavaScript        | 653     |
| CI/CD Test Pipeline               | Dev 2  | YAML              | 653     |
| Test Data Factories               | Dev 1  | Python            | 654     |
| Coverage Reporting Setup          | Dev 2  | YAML/Config       | 654     |
| Test Framework Documentation      | All    | Markdown          | 655     |

### 1.4 Prerequisites

- Phase 13 complete and all features stable
- All code merged to develop branch
- CI/CD pipeline operational
- Access to test environment
- Coverage tool accounts (Codecov/SonarQube)

### 1.5 Success Criteria

- [ ] Test strategy document approved by stakeholders
- [ ] pytest running with >50 initial test cases
- [ ] Jest configured with >30 initial test cases
- [ ] Cypress E2E tests for 5+ critical user flows
- [ ] CI/CD pipeline executing all tests on every PR
- [ ] Coverage reporting visible in PR comments
- [ ] Test data factories generating realistic data
- [ ] Defect tracking workflow documented

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| RFP-TEST-001 | RFP    | Test coverage > 80%                      | 651-655 | Coverage targets setup      |
| BRD-QA-001   | BRD    | Zero critical bugs at launch             | 651     | Test strategy               |
| SRS-TEST-001 | SRS    | Unit test coverage > 70%                 | 652     | pytest/Jest configuration   |
| SRS-TEST-002 | SRS    | Integration test coverage > 60%          | 652-653 | Framework setup             |
| SRS-TEST-003 | SRS    | E2E coverage > 50% critical paths        | 653     | Cypress configuration       |
| IMPL-QA-01   | Guide  | CI/CD test integration                   | 653     | Pipeline setup              |
| IMPL-QA-02   | Guide  | Automated coverage reporting             | 654     | Codecov integration         |

---

## 3. Day-by-Day Breakdown

### Day 651 - Test Strategy & Planning

**Objective:** Develop comprehensive test strategy document and establish testing scope.

#### Dev 1 - Backend Lead (8h)

**Task 1: Test Strategy Document (4h)**

```markdown
# Smart Dairy Test Strategy Document

## 1. Introduction

### 1.1 Purpose
This document defines the testing strategy for the Smart Dairy Digital Portal + ERP system,
ensuring comprehensive quality assurance across all modules.

### 1.2 Scope
- All Odoo custom modules (smart_farm_mgmt, smart_b2b_portal, smart_bd_local, smart_iot)
- Frontend web application (OWL Framework)
- Mobile applications (Flutter - Customer, Farm Worker, Driver)
- API endpoints (REST/JSON-RPC)
- Third-party integrations (bKash, Nagad, Rocket, SSLCommerz)

## 2. Test Levels

### 2.1 Unit Testing (Target: 70% coverage)
- Business logic functions
- Model methods and computations
- Utility functions
- Validators and formatters

### 2.2 Integration Testing (Target: 60% coverage)
- API endpoint testing
- Database operations
- Module interactions
- External service integrations

### 2.3 End-to-End Testing (Target: 50% critical paths)
- User workflows
- Multi-step processes
- Cross-module interactions

## 3. Test Types

### 3.1 Functional Testing
- Feature verification
- Business rule validation
- User acceptance criteria

### 3.2 Non-Functional Testing
- Performance testing (1000+ concurrent users)
- Security testing (OWASP Top 10)
- Accessibility testing (WCAG 2.1 AA)

## 4. Entry/Exit Criteria

### Entry Criteria
- Code complete and merged
- Test environment stable
- Test data prepared

### Exit Criteria
- >70% unit test coverage
- >60% integration test coverage
- Zero critical/high bugs
- All acceptance criteria met
```

**Task 2: Test Scope Analysis (2h)**

```python
# scripts/analyze_test_scope.py
"""
Analyze codebase to determine test scope and coverage targets.
"""
import os
import ast
from pathlib import Path
from dataclasses import dataclass
from typing import List, Dict

@dataclass
class ModuleAnalysis:
    name: str
    python_files: int
    js_files: int
    total_functions: int
    total_classes: int
    estimated_test_cases: int

def analyze_odoo_module(module_path: Path) -> ModuleAnalysis:
    """Analyze an Odoo module for test scope."""
    python_files = list(module_path.glob("**/*.py"))
    js_files = list(module_path.glob("**/*.js"))

    total_functions = 0
    total_classes = 0

    for py_file in python_files:
        try:
            with open(py_file, 'r', encoding='utf-8') as f:
                tree = ast.parse(f.read())
                total_functions += sum(1 for node in ast.walk(tree)
                                      if isinstance(node, ast.FunctionDef))
                total_classes += sum(1 for node in ast.walk(tree)
                                    if isinstance(node, ast.ClassDef))
        except Exception:
            continue

    # Estimate: 2 test cases per function, 5 per class
    estimated_tests = (total_functions * 2) + (total_classes * 5)

    return ModuleAnalysis(
        name=module_path.name,
        python_files=len(python_files),
        js_files=len(js_files),
        total_functions=total_functions,
        total_classes=total_classes,
        estimated_test_cases=estimated_tests
    )

def generate_scope_report(addons_path: str) -> Dict:
    """Generate test scope report for all modules."""
    modules = []
    addons = Path(addons_path)

    for module_dir in addons.iterdir():
        if module_dir.is_dir() and (module_dir / '__manifest__.py').exists():
            analysis = analyze_odoo_module(module_dir)
            modules.append(analysis)

    return {
        'modules': modules,
        'total_estimated_tests': sum(m.estimated_test_cases for m in modules),
        'total_python_files': sum(m.python_files for m in modules),
        'total_js_files': sum(m.js_files for m in modules)
    }

if __name__ == '__main__':
    report = generate_scope_report('./odoo/addons')
    print(f"Total estimated test cases: {report['total_estimated_tests']}")
```

**Task 3: Backend Test Categories Definition (2h)**

```python
# tests/conftest.py - Backend test configuration
"""
Smart Dairy pytest configuration and shared fixtures.
"""
import pytest
from odoo.tests.common import TransactionCase, HttpCase
from odoo.tools import config

# Test markers for categorization
def pytest_configure(config):
    config.addinivalue_line("markers", "unit: Unit tests")
    config.addinivalue_line("markers", "integration: Integration tests")
    config.addinivalue_line("markers", "e2e: End-to-end tests")
    config.addinivalue_line("markers", "slow: Slow running tests")
    config.addinivalue_line("markers", "security: Security-related tests")
    config.addinivalue_line("markers", "farm: Farm management module tests")
    config.addinivalue_line("markers", "b2c: B2C e-commerce tests")
    config.addinivalue_line("markers", "b2b: B2B portal tests")
    config.addinivalue_line("markers", "payment: Payment gateway tests")
    config.addinivalue_line("markers", "iot: IoT integration tests")

# Shared fixtures
@pytest.fixture(scope='session')
def test_config():
    """Test configuration settings."""
    return {
        'database': 'smart_dairy_test',
        'admin_password': 'test_admin_123',
        'api_base_url': 'http://localhost:8069',
        'timeout': 30
    }

@pytest.fixture
def sample_animal_data():
    """Sample animal data for farm tests."""
    return {
        'name': 'Test Cow 001',
        'ear_tag': 'SD-2024-001',
        'breed': 'Holstein Friesian',
        'birth_date': '2022-01-15',
        'status': 'active',
        'gender': 'female'
    }

@pytest.fixture
def sample_order_data():
    """Sample order data for e-commerce tests."""
    return {
        'partner_id': 1,
        'order_line': [
            {'product_id': 1, 'quantity': 5, 'price_unit': 80.0},
            {'product_id': 2, 'quantity': 2, 'price_unit': 120.0}
        ],
        'delivery_date': '2024-03-15',
        'delivery_slot': 'morning'
    }
```

#### Dev 2 - Full-Stack (8h)

**Task 1: CI/CD Test Pipeline Planning (4h)**

```yaml
# .github/workflows/test-strategy.yml
name: Test Strategy Validation

on:
  push:
    branches: [develop, main]
  pull_request:
    branches: [develop, main]

env:
  PYTHON_VERSION: '3.11'
  NODE_VERSION: '20'
  POSTGRES_VERSION: '16'

jobs:
  analyze-coverage:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Set up Python
        uses: actions/setup-python@v5
        with:
          python-version: ${{ env.PYTHON_VERSION }}

      - name: Analyze test coverage targets
        run: |
          echo "Coverage Targets:"
          echo "- Unit Tests: 70%"
          echo "- Integration Tests: 60%"
          echo "- E2E Critical Paths: 50%"

      - name: Count existing tests
        run: |
          echo "Existing Python tests:"
          find . -name "test_*.py" -o -name "*_test.py" | wc -l
          echo "Existing JS tests:"
          find . -name "*.test.js" -o -name "*.spec.js" | wc -l

  test-environment-check:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:16
        env:
          POSTGRES_DB: smart_dairy_test
          POSTGRES_USER: odoo
          POSTGRES_PASSWORD: odoo
        ports:
          - 5432:5432
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5

      redis:
        image: redis:7
        ports:
          - 6379:6379

    steps:
      - uses: actions/checkout@v4

      - name: Verify test environment
        run: |
          pg_isready -h localhost -p 5432
          redis-cli -h localhost ping
```

**Task 2: Test Environment Configuration (2h)**

```yaml
# docker-compose.test.yml
version: '3.8'

services:
  db-test:
    image: postgres:16
    environment:
      POSTGRES_DB: smart_dairy_test
      POSTGRES_USER: odoo
      POSTGRES_PASSWORD: odoo_test_password
    ports:
      - "5433:5432"
    volumes:
      - test_db_data:/var/lib/postgresql/data
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U odoo -d smart_dairy_test"]
      interval: 10s
      timeout: 5s
      retries: 5

  redis-test:
    image: redis:7
    ports:
      - "6380:6379"
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 5s
      retries: 5

  odoo-test:
    build:
      context: .
      dockerfile: Dockerfile.test
    depends_on:
      db-test:
        condition: service_healthy
      redis-test:
        condition: service_healthy
    environment:
      - DB_HOST=db-test
      - DB_PORT=5432
      - DB_USER=odoo
      - DB_PASSWORD=odoo_test_password
      - DB_NAME=smart_dairy_test
      - REDIS_URL=redis://redis-test:6379
    volumes:
      - ./odoo/addons:/opt/odoo/addons
      - ./tests:/opt/odoo/tests
    ports:
      - "8070:8069"
    command: ["odoo", "--test-enable", "-d", "smart_dairy_test"]

volumes:
  test_db_data:
```

**Task 3: Defect Tracking Workflow (2h)**

```markdown
# Defect Management Workflow

## Severity Levels

| Severity | Definition | SLA | Examples |
|----------|------------|-----|----------|
| Critical (S1) | System unusable, data loss | 4h fix | Payment failure, data corruption |
| High (S2) | Major feature broken | 1 day | Order cannot be placed, login fails |
| Medium (S3) | Feature impaired, workaround exists | 3 days | Report formatting issue |
| Low (S4) | Minor issue, cosmetic | End of phase | Typo, alignment issue |

## Bug Lifecycle

1. **New** - Bug reported
2. **Triaged** - Severity assigned
3. **Assigned** - Developer assigned
4. **In Progress** - Fix being developed
5. **In Review** - PR submitted
6. **Testing** - QA verification
7. **Verified** - Fix confirmed
8. **Closed** - Bug resolved

## Bug Report Template

```markdown
### Summary
[One-line description]

### Severity
[Critical/High/Medium/Low]

### Steps to Reproduce
1. Step 1
2. Step 2
3. Step 3

### Expected Result
[What should happen]

### Actual Result
[What actually happens]

### Environment
- Browser/OS:
- User Role:
- Test Data:

### Screenshots/Logs
[Attach relevant files]
```
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Frontend Test Strategy (4h)**

```javascript
// docs/frontend-test-strategy.js
/**
 * Frontend Testing Strategy for Smart Dairy
 *
 * Coverage Targets:
 * - Unit Tests: 70% of components
 * - Integration Tests: 60% of features
 * - E2E Tests: 50% of critical user journeys
 */

const testStrategy = {
  unitTesting: {
    framework: 'Jest',
    target: '70%',
    scope: [
      'React/OWL components',
      'Utility functions',
      'State management',
      'Form validations',
      'API response handlers'
    ],
    patterns: [
      'Component rendering tests',
      'Props validation tests',
      'Event handler tests',
      'State change tests',
      'Error boundary tests'
    ]
  },

  integrationTesting: {
    framework: 'Jest + React Testing Library',
    target: '60%',
    scope: [
      'Component interactions',
      'Form submissions',
      'API integration',
      'State management flows',
      'Authentication flows'
    ]
  },

  e2eTesting: {
    framework: 'Cypress',
    target: '50% critical paths',
    criticalPaths: [
      'User registration and login',
      'Product browsing and search',
      'Add to cart and checkout',
      'Order tracking',
      'Subscription management',
      'B2B order placement',
      'Farm animal registration',
      'Milk production recording',
      'Payment processing',
      'Report generation'
    ]
  },

  mobileTesting: {
    framework: 'Flutter Test',
    scope: [
      'Widget tests',
      'Integration tests',
      'Golden tests (UI snapshots)'
    ]
  }
};

export default testStrategy;
```

**Task 2: Jest Initial Configuration (2h)**

```javascript
// jest.config.js
module.exports = {
  testEnvironment: 'jsdom',
  roots: ['<rootDir>/src', '<rootDir>/tests'],
  testMatch: [
    '**/__tests__/**/*.+(ts|tsx|js)',
    '**/?(*.)+(spec|test).+(ts|tsx|js)'
  ],
  transform: {
    '^.+\\.(ts|tsx)$': 'ts-jest',
    '^.+\\.(js|jsx)$': 'babel-jest'
  },
  moduleNameMapper: {
    '^@/(.*)$': '<rootDir>/src/$1',
    '^@components/(.*)$': '<rootDir>/src/components/$1',
    '^@utils/(.*)$': '<rootDir>/src/utils/$1',
    '\\.(css|less|scss|sass)$': 'identity-obj-proxy'
  },
  setupFilesAfterEnv: ['<rootDir>/tests/setup.js'],
  collectCoverageFrom: [
    'src/**/*.{js,jsx,ts,tsx}',
    '!src/**/*.d.ts',
    '!src/index.tsx',
    '!src/reportWebVitals.ts'
  ],
  coverageThreshold: {
    global: {
      branches: 60,
      functions: 70,
      lines: 70,
      statements: 70
    }
  },
  coverageReporters: ['text', 'lcov', 'html'],
  testTimeout: 10000
};
```

**Task 3: Cypress Initial Configuration (2h)**

```javascript
// cypress.config.js
const { defineConfig } = require('cypress');

module.exports = defineConfig({
  e2e: {
    baseUrl: 'http://localhost:8069',
    specPattern: 'cypress/e2e/**/*.cy.{js,jsx,ts,tsx}',
    supportFile: 'cypress/support/e2e.js',
    viewportWidth: 1280,
    viewportHeight: 720,
    video: true,
    screenshotOnRunFailure: true,
    defaultCommandTimeout: 10000,
    requestTimeout: 10000,
    responseTimeout: 30000,

    env: {
      apiUrl: 'http://localhost:8069/api/v1',
      adminUser: 'admin@smartdairy.com',
      adminPassword: 'test_password',
      testCustomer: 'customer@test.com',
      testFarmer: 'farmer@test.com'
    },

    retries: {
      runMode: 2,
      openMode: 0
    },

    setupNodeEvents(on, config) {
      // Task implementations
      on('task', {
        log(message) {
          console.log(message);
          return null;
        },
        seedDatabase() {
          // Seed test data
          return null;
        },
        clearDatabase() {
          // Clear test data
          return null;
        }
      });

      return config;
    }
  },

  component: {
    devServer: {
      framework: 'react',
      bundler: 'vite'
    }
  }
});
```

**Deliverables Day 651:**
- Test strategy document draft
- Test scope analysis report
- pytest configuration foundation
- CI/CD test pipeline plan
- Frontend test strategy
- Jest/Cypress initial configuration

---

### Day 652 - Test Framework Setup

**Objective:** Complete pytest and Jest framework configuration with initial test cases.

#### Dev 1 - Backend Lead (8h)

**Task 1: pytest Framework Configuration (4h)**

```python
# pytest.ini
[pytest]
testpaths = tests
python_files = test_*.py *_test.py
python_classes = Test*
python_functions = test_*
addopts =
    -v
    --tb=short
    --strict-markers
    -ra
    --cov=odoo/addons/smart_dairy
    --cov-report=html:coverage_html
    --cov-report=xml:coverage.xml
    --cov-fail-under=70
markers =
    unit: Unit tests (fast, isolated)
    integration: Integration tests (database required)
    e2e: End-to-end tests (full stack)
    slow: Tests that take > 1 second
    security: Security-related tests
    farm: Farm management module
    b2c: B2C e-commerce module
    b2b: B2B portal module
    payment: Payment gateway tests
    iot: IoT integration tests
filterwarnings =
    ignore::DeprecationWarning
    ignore::PendingDeprecationWarning
```

```python
# tests/unit/test_animal_model.py
"""Unit tests for Animal model."""
import pytest
from datetime import date, timedelta
from unittest.mock import Mock, patch

class TestAnimalModel:
    """Test suite for farm.animal model."""

    @pytest.mark.unit
    @pytest.mark.farm
    def test_animal_age_calculation(self, sample_animal_data):
        """Test animal age is calculated correctly."""
        birth_date = date(2022, 1, 15)
        today = date(2024, 3, 15)

        with patch('datetime.date') as mock_date:
            mock_date.today.return_value = today
            # Age should be 2 years, 2 months
            expected_age_days = (today - birth_date).days
            assert expected_age_days == 790

    @pytest.mark.unit
    @pytest.mark.farm
    def test_animal_ear_tag_format_validation(self):
        """Test ear tag format validation."""
        valid_tags = ['SD-2024-001', 'SD-2024-999', 'SD-2025-001']
        invalid_tags = ['SD2024001', '2024-001', 'SD-24-001', '']

        import re
        pattern = r'^SD-\d{4}-\d{3}$'

        for tag in valid_tags:
            assert re.match(pattern, tag), f"Valid tag {tag} should match"

        for tag in invalid_tags:
            assert not re.match(pattern, tag), f"Invalid tag {tag} should not match"

    @pytest.mark.unit
    @pytest.mark.farm
    def test_animal_status_transitions(self):
        """Test valid status transitions."""
        valid_transitions = {
            'active': ['sick', 'dry', 'sold', 'deceased'],
            'sick': ['active', 'deceased'],
            'dry': ['active', 'pregnant'],
            'pregnant': ['active', 'calving'],
            'calving': ['active', 'deceased']
        }

        for from_status, to_statuses in valid_transitions.items():
            for to_status in to_statuses:
                assert to_status in valid_transitions.get(from_status, []) or \
                       to_status in ['active', 'deceased']

    @pytest.mark.unit
    @pytest.mark.farm
    def test_milk_yield_validation(self):
        """Test milk yield value validation."""
        valid_yields = [0, 5.5, 15.0, 25.0, 35.0]
        invalid_yields = [-1, -5.5, 100, 1000]

        def validate_yield(value):
            return 0 <= value <= 50  # Max 50 liters per session

        for yield_val in valid_yields:
            assert validate_yield(yield_val), f"{yield_val} should be valid"

        for yield_val in invalid_yields:
            assert not validate_yield(yield_val), f"{yield_val} should be invalid"
```

**Task 2: Odoo Test Case Setup (4h)**

```python
# tests/integration/test_order_workflow.py
"""Integration tests for order workflow."""
import pytest
from odoo.tests.common import TransactionCase, tagged
from odoo.exceptions import ValidationError, UserError
from datetime import datetime, timedelta

@tagged('integration', 'b2c')
class TestOrderWorkflow(TransactionCase):
    """Test order creation and processing workflow."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        # Create test customer
        cls.customer = cls.env['res.partner'].create({
            'name': 'Test Customer',
            'email': 'test@customer.com',
            'phone': '+8801712345678',
            'customer_rank': 1
        })

        # Create test product
        cls.product = cls.env['product.product'].create({
            'name': 'Fresh Milk 1L',
            'type': 'product',
            'list_price': 80.0,
            'default_code': 'MILK-1L',
            'categ_id': cls.env.ref('product.product_category_all').id
        })

    @pytest.mark.integration
    def test_order_creation(self):
        """Test basic order creation."""
        order = self.env['sale.order'].create({
            'partner_id': self.customer.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 5
            })]
        })

        self.assertEqual(order.state, 'draft')
        self.assertEqual(len(order.order_line), 1)
        self.assertEqual(order.amount_total, 400.0)  # 5 * 80

    @pytest.mark.integration
    def test_order_confirmation(self):
        """Test order confirmation workflow."""
        order = self.env['sale.order'].create({
            'partner_id': self.customer.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 2
            })]
        })

        order.action_confirm()
        self.assertEqual(order.state, 'sale')
        self.assertTrue(order.picking_ids)

    @pytest.mark.integration
    def test_order_with_delivery_slot(self):
        """Test order with delivery time slot."""
        tomorrow = datetime.now() + timedelta(days=1)

        order = self.env['sale.order'].create({
            'partner_id': self.customer.id,
            'commitment_date': tomorrow,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 3
            })]
        })

        self.assertEqual(order.commitment_date.date(), tomorrow.date())
```

#### Dev 2 - Full-Stack (8h)

**Task 1: CI/CD Test Integration (4h)**

```yaml
# .github/workflows/test-suite.yml
name: Comprehensive Test Suite

on:
  push:
    branches: [develop, main]
  pull_request:
    branches: [develop, main]

concurrency:
  group: ${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: true

env:
  PYTHON_VERSION: '3.11'
  NODE_VERSION: '20'

jobs:
  # Python Backend Tests
  backend-tests:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:16
        env:
          POSTGRES_DB: smart_dairy_test
          POSTGRES_USER: odoo
          POSTGRES_PASSWORD: odoo
        ports:
          - 5432:5432
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5

      redis:
        image: redis:7
        ports:
          - 6379:6379

    steps:
      - uses: actions/checkout@v4

      - name: Set up Python
        uses: actions/setup-python@v5
        with:
          python-version: ${{ env.PYTHON_VERSION }}
          cache: 'pip'

      - name: Install dependencies
        run: |
          pip install -r requirements.txt
          pip install pytest pytest-cov pytest-xdist

      - name: Run unit tests
        run: |
          pytest tests/unit -v --cov --cov-report=xml -n auto

      - name: Run integration tests
        run: |
          pytest tests/integration -v --cov --cov-append --cov-report=xml

      - name: Upload coverage
        uses: codecov/codecov-action@v4
        with:
          files: ./coverage.xml
          flags: backend
          name: backend-coverage

  # JavaScript Frontend Tests
  frontend-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Set up Node.js
        uses: actions/setup-node@v4
        with:
          node-version: ${{ env.NODE_VERSION }}
          cache: 'npm'

      - name: Install dependencies
        run: npm ci

      - name: Run Jest tests
        run: npm run test:coverage

      - name: Upload coverage
        uses: codecov/codecov-action@v4
        with:
          files: ./coverage/lcov.info
          flags: frontend
          name: frontend-coverage

  # E2E Tests
  e2e-tests:
    runs-on: ubuntu-latest
    needs: [backend-tests, frontend-tests]
    steps:
      - uses: actions/checkout@v4

      - name: Set up Node.js
        uses: actions/setup-node@v4
        with:
          node-version: ${{ env.NODE_VERSION }}

      - name: Install dependencies
        run: npm ci

      - name: Start test server
        run: |
          docker-compose -f docker-compose.test.yml up -d
          sleep 30

      - name: Run Cypress tests
        uses: cypress-io/github-action@v6
        with:
          wait-on: 'http://localhost:8069'
          wait-on-timeout: 120

      - name: Upload Cypress artifacts
        if: failure()
        uses: actions/upload-artifact@v4
        with:
          name: cypress-artifacts
          path: |
            cypress/videos
            cypress/screenshots
```

**Task 2: Coverage Reporting Setup (4h)**

```yaml
# codecov.yml
codecov:
  require_ci_to_pass: yes
  notify:
    wait_for_ci: yes

coverage:
  precision: 2
  round: down
  range: "60...100"
  status:
    project:
      default:
        target: 70%
        threshold: 2%
        if_ci_failed: error
      backend:
        target: 70%
        flags:
          - backend
      frontend:
        target: 70%
        flags:
          - frontend
    patch:
      default:
        target: 80%
        threshold: 5%

parsers:
  gcov:
    branch_detection:
      conditional: yes
      loop: yes
      method: no
      macro: no

comment:
  layout: "reach,diff,flags,files"
  behavior: default
  require_changes: no
  require_base: no
  require_head: yes

flags:
  backend:
    paths:
      - odoo/addons/
    carryforward: true
  frontend:
    paths:
      - src/
    carryforward: true
  e2e:
    paths:
      - cypress/
    carryforward: false
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Jest Test Setup (4h)**

```javascript
// tests/setup.js
import '@testing-library/jest-dom';
import { configure } from '@testing-library/react';

// Configure testing library
configure({
  testIdAttribute: 'data-testid',
});

// Mock window.matchMedia
Object.defineProperty(window, 'matchMedia', {
  writable: true,
  value: jest.fn().mockImplementation(query => ({
    matches: false,
    media: query,
    onchange: null,
    addListener: jest.fn(),
    removeListener: jest.fn(),
    addEventListener: jest.fn(),
    removeEventListener: jest.fn(),
    dispatchEvent: jest.fn(),
  })),
});

// Mock IntersectionObserver
global.IntersectionObserver = class IntersectionObserver {
  constructor(callback) {
    this.callback = callback;
  }
  observe() { return null; }
  unobserve() { return null; }
  disconnect() { return null; }
};

// Mock fetch
global.fetch = jest.fn(() =>
  Promise.resolve({
    json: () => Promise.resolve({}),
    ok: true,
  })
);

// Reset mocks before each test
beforeEach(() => {
  jest.clearAllMocks();
});
```

```javascript
// tests/components/ProductCard.test.js
import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import ProductCard from '@components/ProductCard';

describe('ProductCard Component', () => {
  const mockProduct = {
    id: 1,
    name: 'Fresh Milk 1L',
    price: 80,
    image: '/images/milk.jpg',
    description: 'Fresh organic milk',
    inStock: true
  };

  const mockOnAddToCart = jest.fn();

  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('renders product information correctly', () => {
    render(
      <ProductCard
        product={mockProduct}
        onAddToCart={mockOnAddToCart}
      />
    );

    expect(screen.getByText('Fresh Milk 1L')).toBeInTheDocument();
    expect(screen.getByText('৳80')).toBeInTheDocument();
    expect(screen.getByText('Fresh organic milk')).toBeInTheDocument();
  });

  it('calls onAddToCart when add button is clicked', () => {
    render(
      <ProductCard
        product={mockProduct}
        onAddToCart={mockOnAddToCart}
      />
    );

    const addButton = screen.getByRole('button', { name: /add to cart/i });
    fireEvent.click(addButton);

    expect(mockOnAddToCart).toHaveBeenCalledTimes(1);
    expect(mockOnAddToCart).toHaveBeenCalledWith(mockProduct);
  });

  it('disables add button when out of stock', () => {
    const outOfStockProduct = { ...mockProduct, inStock: false };

    render(
      <ProductCard
        product={outOfStockProduct}
        onAddToCart={mockOnAddToCart}
      />
    );

    const addButton = screen.getByRole('button', { name: /out of stock/i });
    expect(addButton).toBeDisabled();
  });

  it('displays correct currency format for Bangladeshi Taka', () => {
    render(
      <ProductCard
        product={mockProduct}
        onAddToCart={mockOnAddToCart}
      />
    );

    expect(screen.getByText(/৳80/)).toBeInTheDocument();
  });
});
```

**Task 2: Initial E2E Test Cases (4h)**

```javascript
// cypress/e2e/auth/login.cy.js
describe('User Authentication', () => {
  beforeEach(() => {
    cy.visit('/web/login');
  });

  it('displays login form', () => {
    cy.get('input[name="login"]').should('be.visible');
    cy.get('input[name="password"]').should('be.visible');
    cy.get('button[type="submit"]').should('be.visible');
  });

  it('shows error for invalid credentials', () => {
    cy.get('input[name="login"]').type('invalid@email.com');
    cy.get('input[name="password"]').type('wrongpassword');
    cy.get('button[type="submit"]').click();

    cy.get('.alert-danger').should('be.visible');
    cy.get('.alert-danger').should('contain', 'Wrong login/password');
  });

  it('successfully logs in with valid credentials', () => {
    cy.get('input[name="login"]').type(Cypress.env('adminUser'));
    cy.get('input[name="password"]').type(Cypress.env('adminPassword'));
    cy.get('button[type="submit"]').click();

    cy.url().should('include', '/web');
    cy.get('.o_main_navbar').should('be.visible');
  });

  it('redirects to requested page after login', () => {
    cy.visit('/web#action=sale.action_orders');

    // Should redirect to login
    cy.url().should('include', '/web/login');

    // Login
    cy.get('input[name="login"]').type(Cypress.env('adminUser'));
    cy.get('input[name="password"]').type(Cypress.env('adminPassword'));
    cy.get('button[type="submit"]').click();

    // Should redirect back to requested page
    cy.url().should('include', 'action=sale.action_orders');
  });
});
```

```javascript
// cypress/e2e/ecommerce/checkout.cy.js
describe('E-commerce Checkout Flow', () => {
  beforeEach(() => {
    cy.visit('/shop');
  });

  it('completes full checkout process', () => {
    // Add product to cart
    cy.get('[data-testid="product-card"]').first().click();
    cy.get('[data-testid="add-to-cart"]').click();

    // Verify cart updated
    cy.get('[data-testid="cart-count"]').should('contain', '1');

    // Go to cart
    cy.get('[data-testid="cart-icon"]').click();
    cy.url().should('include', '/shop/cart');

    // Proceed to checkout
    cy.get('[data-testid="checkout-button"]').click();

    // Fill shipping info
    cy.get('input[name="name"]').type('Test Customer');
    cy.get('input[name="phone"]').type('01712345678');
    cy.get('input[name="street"]').type('123 Test Street');
    cy.get('select[name="city"]').select('Dhaka');

    // Select delivery slot
    cy.get('[data-testid="delivery-slot-morning"]').click();

    // Select payment method
    cy.get('[data-testid="payment-cod"]').click();

    // Place order
    cy.get('[data-testid="place-order"]').click();

    // Verify success
    cy.url().should('include', '/shop/confirmation');
    cy.get('[data-testid="order-number"]').should('be.visible');
  });
});
```

**Deliverables Day 652:**
- pytest configuration complete
- Initial backend unit tests (50+)
- CI/CD test pipeline
- Coverage reporting setup
- Jest configuration complete
- Initial frontend tests (30+)
- Cypress E2E tests (5 critical paths)

---

### Day 653 - Test Automation & CI/CD Integration

**Objective:** Complete CI/CD integration and establish automated test execution.

#### Dev 1 - Backend Lead (8h)

**Task 1: Database Test Fixtures (4h)**

```python
# tests/fixtures/factories.py
"""Test data factories using Factory Boy."""
import factory
from factory import fuzzy
from datetime import date, timedelta
import random

class PartnerFactory(factory.Factory):
    """Factory for res.partner records."""
    class Meta:
        model = dict

    name = factory.Faker('name')
    email = factory.LazyAttribute(lambda o: f"{o.name.lower().replace(' ', '.')}@test.com")
    phone = factory.LazyFunction(lambda: f"+88017{random.randint(10000000, 99999999)}")
    street = factory.Faker('street_address')
    city = factory.Faker('city')
    customer_rank = 1

class ProductFactory(factory.Factory):
    """Factory for product.product records."""
    class Meta:
        model = dict

    name = factory.Sequence(lambda n: f"Test Product {n}")
    default_code = factory.Sequence(lambda n: f"PROD-{n:04d}")
    list_price = fuzzy.FuzzyDecimal(50, 500, precision=2)
    type = 'product'

class AnimalFactory(factory.Factory):
    """Factory for farm.animal records."""
    class Meta:
        model = dict

    name = factory.Sequence(lambda n: f"Cow {n:03d}")
    ear_tag = factory.Sequence(lambda n: f"SD-2024-{n:03d}")
    breed = fuzzy.FuzzyChoice(['Holstein Friesian', 'Jersey', 'Sahiwal', 'Red Chittagong'])
    birth_date = factory.LazyFunction(
        lambda: date.today() - timedelta(days=random.randint(365, 2000))
    )
    status = 'active'
    gender = 'female'

class OrderFactory(factory.Factory):
    """Factory for sale.order records."""
    class Meta:
        model = dict

    partner_id = factory.SubFactory(PartnerFactory)
    date_order = factory.LazyFunction(date.today)
    commitment_date = factory.LazyFunction(
        lambda: date.today() + timedelta(days=1)
    )

class MilkProductionFactory(factory.Factory):
    """Factory for milk production records."""
    class Meta:
        model = dict

    animal_id = factory.SubFactory(AnimalFactory)
    date = factory.LazyFunction(date.today)
    session = fuzzy.FuzzyChoice(['morning', 'evening'])
    quantity_liters = fuzzy.FuzzyDecimal(5, 25, precision=1)
    fat_percentage = fuzzy.FuzzyDecimal(3.0, 5.5, precision=2)
    snf_percentage = fuzzy.FuzzyDecimal(8.0, 9.5, precision=2)
```

**Task 2: API Test Suite (4h)**

```python
# tests/integration/test_api_endpoints.py
"""API endpoint integration tests."""
import pytest
import requests
from typing import Dict, Any

class TestAPIEndpoints:
    """Test suite for REST API endpoints."""

    @pytest.fixture
    def api_client(self, test_config):
        """Create authenticated API client."""
        session = requests.Session()
        # Authenticate
        auth_response = session.post(
            f"{test_config['api_base_url']}/web/session/authenticate",
            json={
                "jsonrpc": "2.0",
                "params": {
                    "db": test_config['database'],
                    "login": "admin",
                    "password": test_config['admin_password']
                }
            }
        )
        return session

    @pytest.mark.integration
    @pytest.mark.api
    def test_product_list_endpoint(self, api_client, test_config):
        """Test product listing API."""
        response = api_client.get(
            f"{test_config['api_base_url']}/api/v1/products"
        )

        assert response.status_code == 200
        data = response.json()
        assert 'products' in data
        assert isinstance(data['products'], list)

    @pytest.mark.integration
    @pytest.mark.api
    def test_order_creation_api(self, api_client, test_config, sample_order_data):
        """Test order creation via API."""
        response = api_client.post(
            f"{test_config['api_base_url']}/api/v1/orders",
            json=sample_order_data
        )

        assert response.status_code == 201
        data = response.json()
        assert 'order_id' in data
        assert data['status'] == 'draft'

    @pytest.mark.integration
    @pytest.mark.api
    def test_animal_list_endpoint(self, api_client, test_config):
        """Test animal listing API for farm module."""
        response = api_client.get(
            f"{test_config['api_base_url']}/api/v1/farm/animals"
        )

        assert response.status_code == 200
        data = response.json()
        assert 'animals' in data

    @pytest.mark.integration
    @pytest.mark.api
    @pytest.mark.parametrize("endpoint,method", [
        ("/api/v1/products", "GET"),
        ("/api/v1/categories", "GET"),
        ("/api/v1/cart", "GET"),
        ("/api/v1/orders", "GET"),
        ("/api/v1/farm/animals", "GET"),
        ("/api/v1/farm/milk-production", "GET"),
    ])
    def test_endpoints_return_valid_json(self, api_client, test_config, endpoint, method):
        """Test all endpoints return valid JSON."""
        if method == "GET":
            response = api_client.get(f"{test_config['api_base_url']}{endpoint}")

        assert response.status_code in [200, 401, 403]
        assert response.headers.get('Content-Type', '').startswith('application/json')
```

#### Dev 2 - Full-Stack (8h)

**Task 1: GitHub Actions Matrix Testing (4h)**

```yaml
# .github/workflows/matrix-tests.yml
name: Matrix Test Suite

on:
  push:
    branches: [develop, main]
  pull_request:
    branches: [develop, main]

jobs:
  test-matrix:
    runs-on: ${{ matrix.os }}
    strategy:
      fail-fast: false
      matrix:
        os: [ubuntu-latest]
        python-version: ['3.11', '3.12']
        node-version: ['18', '20']
        include:
          - os: ubuntu-latest
            python-version: '3.11'
            node-version: '20'
            coverage: true

    steps:
      - uses: actions/checkout@v4

      - name: Set up Python ${{ matrix.python-version }}
        uses: actions/setup-python@v5
        with:
          python-version: ${{ matrix.python-version }}

      - name: Set up Node.js ${{ matrix.node-version }}
        uses: actions/setup-node@v4
        with:
          node-version: ${{ matrix.node-version }}

      - name: Install Python dependencies
        run: |
          pip install -r requirements.txt
          pip install pytest pytest-cov

      - name: Install Node dependencies
        run: npm ci

      - name: Run Python tests
        run: pytest tests/ -v --cov=${{ matrix.coverage && '--cov-report=xml' || '' }}

      - name: Run JavaScript tests
        run: npm run test:ci

      - name: Upload coverage
        if: matrix.coverage
        uses: codecov/codecov-action@v4
        with:
          files: ./coverage.xml,./coverage/lcov.info
```

**Task 2: Test Reporting Dashboard (4h)**

```yaml
# .github/workflows/test-report.yml
name: Test Report Generation

on:
  workflow_run:
    workflows: ["Comprehensive Test Suite"]
    types: [completed]

jobs:
  generate-report:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Download test artifacts
        uses: dawidd6/action-download-artifact@v3
        with:
          workflow: test-suite.yml
          name: test-results
          path: ./test-results

      - name: Generate HTML Report
        run: |
          pip install pytest-html-reporter
          python scripts/generate_test_report.py

      - name: Deploy to GitHub Pages
        uses: peaceiris/actions-gh-pages@v4
        with:
          github_token: ${{ secrets.GITHUB_TOKEN }}
          publish_dir: ./test-reports
          destination_dir: reports
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Cypress Custom Commands (4h)**

```javascript
// cypress/support/commands.js
/**
 * Custom Cypress commands for Smart Dairy testing
 */

// Login command
Cypress.Commands.add('login', (email, password) => {
  cy.session([email, password], () => {
    cy.visit('/web/login');
    cy.get('input[name="login"]').type(email);
    cy.get('input[name="password"]').type(password);
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/web');
  });
});

// Add product to cart
Cypress.Commands.add('addToCart', (productId, quantity = 1) => {
  cy.visit(`/shop/product/${productId}`);
  cy.get('[data-testid="quantity-input"]').clear().type(quantity.toString());
  cy.get('[data-testid="add-to-cart"]').click();
  cy.get('[data-testid="cart-notification"]').should('be.visible');
});

// Complete checkout
Cypress.Commands.add('checkout', (shippingInfo, paymentMethod = 'cod') => {
  cy.visit('/shop/cart');
  cy.get('[data-testid="checkout-button"]').click();

  // Fill shipping
  cy.get('input[name="name"]').type(shippingInfo.name);
  cy.get('input[name="phone"]').type(shippingInfo.phone);
  cy.get('input[name="street"]').type(shippingInfo.address);
  cy.get('select[name="city"]').select(shippingInfo.city);

  // Select payment
  cy.get(`[data-testid="payment-${paymentMethod}"]`).click();

  // Place order
  cy.get('[data-testid="place-order"]').click();
});

// Farm module - Add animal
Cypress.Commands.add('addAnimal', (animalData) => {
  cy.visit('/web#action=smart_farm_mgmt.animal_action');
  cy.get('.o_list_button_add').click();

  cy.get('input[name="name"]').type(animalData.name);
  cy.get('input[name="ear_tag"]').type(animalData.earTag);
  cy.get('select[name="breed_id"]').select(animalData.breed);
  cy.get('input[name="birth_date"]').type(animalData.birthDate);

  cy.get('.o_form_button_save').click();
});

// API intercept helpers
Cypress.Commands.add('interceptAPI', (method, url, alias, response = null) => {
  if (response) {
    cy.intercept(method, url, response).as(alias);
  } else {
    cy.intercept(method, url).as(alias);
  }
});

// Wait for API and validate
Cypress.Commands.add('waitForAPI', (alias, expectedStatus = 200) => {
  cy.wait(`@${alias}`).its('response.statusCode').should('eq', expectedStatus);
});
```

**Task 2: Flutter Test Setup (4h)**

```dart
// test/widget_test.dart
import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:smart_dairy_app/main.dart';
import 'package:smart_dairy_app/screens/product_list_screen.dart';
import 'package:smart_dairy_app/widgets/product_card.dart';

void main() {
  group('ProductCard Widget Tests', () {
    testWidgets('displays product information correctly', (tester) async {
      final product = Product(
        id: 1,
        name: 'Fresh Milk 1L',
        price: 80.0,
        imageUrl: 'assets/images/milk.png',
        inStock: true,
      );

      await tester.pumpWidget(
        MaterialApp(
          home: Scaffold(
            body: ProductCard(
              product: product,
              onAddToCart: (_) {},
            ),
          ),
        ),
      );

      expect(find.text('Fresh Milk 1L'), findsOneWidget);
      expect(find.text('৳80'), findsOneWidget);
    });

    testWidgets('calls onAddToCart when button tapped', (tester) async {
      Product? tappedProduct;
      final product = Product(
        id: 1,
        name: 'Test Product',
        price: 100.0,
        imageUrl: '',
        inStock: true,
      );

      await tester.pumpWidget(
        MaterialApp(
          home: Scaffold(
            body: ProductCard(
              product: product,
              onAddToCart: (p) => tappedProduct = p,
            ),
          ),
        ),
      );

      await tester.tap(find.byType(ElevatedButton));
      await tester.pump();

      expect(tappedProduct, equals(product));
    });

    testWidgets('shows out of stock message when not available', (tester) async {
      final product = Product(
        id: 1,
        name: 'Out of Stock Product',
        price: 50.0,
        imageUrl: '',
        inStock: false,
      );

      await tester.pumpWidget(
        MaterialApp(
          home: Scaffold(
            body: ProductCard(
              product: product,
              onAddToCart: (_) {},
            ),
          ),
        ),
      );

      expect(find.text('Out of Stock'), findsOneWidget);

      // Button should be disabled
      final button = tester.widget<ElevatedButton>(find.byType(ElevatedButton));
      expect(button.onPressed, isNull);
    });
  });
}
```

**Deliverables Day 653:**
- Test data factories
- API test suite
- Matrix testing configuration
- Cypress custom commands
- Flutter test setup

---

### Day 654 - Test Data & Mock Services

**Objective:** Establish test data management and mock services for isolated testing.

#### Dev 1 - Backend Lead (8h)

**Task 1: Test Database Seeding (4h)**

```python
# scripts/seed_test_data.py
"""Seed test database with realistic data."""
import os
import sys
from datetime import date, timedelta
import random

# Add odoo to path
sys.path.insert(0, os.path.join(os.path.dirname(__file__), '..', 'odoo'))

from tests.fixtures.factories import (
    PartnerFactory, ProductFactory, AnimalFactory,
    OrderFactory, MilkProductionFactory
)

def seed_database(env):
    """Seed database with test data."""
    print("Seeding test database...")

    # Create test customers
    customers = []
    for _ in range(50):
        customer_data = PartnerFactory()
        customer = env['res.partner'].create(customer_data)
        customers.append(customer)
    print(f"Created {len(customers)} test customers")

    # Create test products
    dairy_products = [
        {'name': 'Fresh Milk 1L', 'price': 80, 'code': 'MILK-1L'},
        {'name': 'Fresh Milk 500ml', 'price': 45, 'code': 'MILK-500ML'},
        {'name': 'Yogurt 500g', 'price': 60, 'code': 'YOG-500G'},
        {'name': 'Butter 200g', 'price': 180, 'code': 'BUT-200G'},
        {'name': 'Cheese 250g', 'price': 220, 'code': 'CHE-250G'},
        {'name': 'Ghee 500g', 'price': 450, 'code': 'GHE-500G'},
        {'name': 'Paneer 200g', 'price': 150, 'code': 'PAN-200G'},
        {'name': 'Lassi 250ml', 'price': 35, 'code': 'LAS-250ML'},
    ]

    products = []
    for prod_data in dairy_products:
        product = env['product.product'].create({
            'name': prod_data['name'],
            'list_price': prod_data['price'],
            'default_code': prod_data['code'],
            'type': 'product'
        })
        products.append(product)
    print(f"Created {len(products)} test products")

    # Create test animals
    animals = []
    for i in range(100):
        animal_data = AnimalFactory()
        animal = env['farm.animal'].create(animal_data)
        animals.append(animal)
    print(f"Created {len(animals)} test animals")

    # Create milk production records
    for animal in animals[:50]:  # Lactating animals
        for day_offset in range(30):
            prod_date = date.today() - timedelta(days=day_offset)
            for session in ['morning', 'evening']:
                env['farm.milk.production'].create({
                    'animal_id': animal.id,
                    'date': prod_date,
                    'session': session,
                    'quantity_liters': random.uniform(8, 20),
                    'fat_percentage': random.uniform(3.5, 5.0),
                    'snf_percentage': random.uniform(8.0, 9.0)
                })
    print("Created milk production records")

    # Create test orders
    for _ in range(100):
        customer = random.choice(customers)
        product = random.choice(products)
        order = env['sale.order'].create({
            'partner_id': customer.id,
            'order_line': [(0, 0, {
                'product_id': product.id,
                'product_uom_qty': random.randint(1, 10)
            })]
        })
    print("Created 100 test orders")

    print("Test database seeding complete!")

if __name__ == '__main__':
    # This would be run within Odoo environment
    pass
```

**Task 2: Mock External Services (4h)**

```python
# tests/mocks/payment_gateway_mock.py
"""Mock payment gateway services for testing."""
from unittest.mock import Mock, patch
from typing import Dict, Any
import json

class MockBkashGateway:
    """Mock bKash payment gateway."""

    def __init__(self):
        self.transactions = {}

    def create_payment(self, amount: float, reference: str) -> Dict[str, Any]:
        """Create a mock payment."""
        transaction_id = f"BK{reference[:8].upper()}"
        self.transactions[transaction_id] = {
            'id': transaction_id,
            'amount': amount,
            'reference': reference,
            'status': 'pending'
        }
        return {
            'paymentID': transaction_id,
            'bkashURL': f'https://sandbox.bkash.com/pay/{transaction_id}',
            'status': 'success'
        }

    def execute_payment(self, payment_id: str) -> Dict[str, Any]:
        """Execute a mock payment."""
        if payment_id in self.transactions:
            self.transactions[payment_id]['status'] = 'completed'
            return {
                'paymentID': payment_id,
                'trxID': f"TRX{payment_id}",
                'status': 'Completed',
                'amount': self.transactions[payment_id]['amount']
            }
        return {'status': 'Failed', 'message': 'Payment not found'}

    def query_payment(self, payment_id: str) -> Dict[str, Any]:
        """Query payment status."""
        if payment_id in self.transactions:
            return self.transactions[payment_id]
        return {'status': 'not_found'}


class MockSMSService:
    """Mock SMS service for testing."""

    def __init__(self):
        self.sent_messages = []

    def send_sms(self, phone: str, message: str) -> Dict[str, Any]:
        """Send a mock SMS."""
        msg_record = {
            'phone': phone,
            'message': message,
            'status': 'sent',
            'timestamp': '2024-03-15T10:00:00Z'
        }
        self.sent_messages.append(msg_record)
        return {'status': 'success', 'message_id': f"MSG{len(self.sent_messages)}"}

    def get_sent_messages(self) -> list:
        """Get all sent messages for verification."""
        return self.sent_messages


# Pytest fixtures for mocks
import pytest

@pytest.fixture
def mock_bkash():
    """Provide mock bKash gateway."""
    return MockBkashGateway()

@pytest.fixture
def mock_sms():
    """Provide mock SMS service."""
    return MockSMSService()

@pytest.fixture
def mock_all_payment_gateways(mock_bkash):
    """Patch all payment gateway calls."""
    with patch('odoo.addons.smart_payment.bkash.BkashGateway', return_value=mock_bkash):
        yield {'bkash': mock_bkash}
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Coverage Threshold Configuration (4h)**

```javascript
// package.json scripts section
{
  "scripts": {
    "test": "jest",
    "test:watch": "jest --watch",
    "test:coverage": "jest --coverage",
    "test:ci": "jest --ci --coverage --reporters=default --reporters=jest-junit",
    "test:e2e": "cypress run",
    "test:e2e:open": "cypress open",
    "test:all": "npm run test:coverage && npm run test:e2e"
  },
  "jest": {
    "coverageThreshold": {
      "global": {
        "branches": 60,
        "functions": 70,
        "lines": 70,
        "statements": 70
      },
      "./src/components/": {
        "branches": 70,
        "functions": 80,
        "lines": 80,
        "statements": 80
      },
      "./src/utils/": {
        "branches": 80,
        "functions": 90,
        "lines": 90,
        "statements": 90
      }
    }
  }
}
```

**Task 2: SonarQube Integration (4h)**

```properties
# sonar-project.properties
sonar.projectKey=smart-dairy-portal
sonar.projectName=Smart Dairy Portal
sonar.projectVersion=1.0

# Source paths
sonar.sources=src,odoo/addons
sonar.tests=tests,cypress
sonar.exclusions=**/node_modules/**,**/vendor/**,**/*.min.js

# Coverage reports
sonar.python.coverage.reportPaths=coverage.xml
sonar.javascript.lcov.reportPaths=coverage/lcov.info

# Quality gates
sonar.qualitygate.wait=true

# Encoding
sonar.sourceEncoding=UTF-8
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: API Mock Server (4h)**

```javascript
// tests/mocks/handlers.js
import { rest } from 'msw';

export const handlers = [
  // Products API
  rest.get('/api/v1/products', (req, res, ctx) => {
    return res(
      ctx.status(200),
      ctx.json({
        products: [
          { id: 1, name: 'Fresh Milk 1L', price: 80, inStock: true },
          { id: 2, name: 'Yogurt 500g', price: 60, inStock: true },
          { id: 3, name: 'Butter 200g', price: 180, inStock: false },
        ]
      })
    );
  }),

  // Cart API
  rest.get('/api/v1/cart', (req, res, ctx) => {
    return res(
      ctx.status(200),
      ctx.json({
        items: [],
        total: 0
      })
    );
  }),

  rest.post('/api/v1/cart/add', (req, res, ctx) => {
    return res(
      ctx.status(200),
      ctx.json({
        success: true,
        cartCount: 1
      })
    );
  }),

  // Orders API
  rest.post('/api/v1/orders', (req, res, ctx) => {
    return res(
      ctx.status(201),
      ctx.json({
        order_id: 'ORD-2024-001',
        status: 'confirmed'
      })
    );
  }),

  // Farm API
  rest.get('/api/v1/farm/animals', (req, res, ctx) => {
    return res(
      ctx.status(200),
      ctx.json({
        animals: [
          { id: 1, name: 'Cow 001', ear_tag: 'SD-2024-001', status: 'active' },
          { id: 2, name: 'Cow 002', ear_tag: 'SD-2024-002', status: 'active' },
        ]
      })
    );
  }),
];
```

**Task 2: Component Test Utilities (4h)**

```javascript
// tests/utils/test-utils.js
import React from 'react';
import { render } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import { Provider } from 'react-redux';
import { configureStore } from '@reduxjs/toolkit';
import rootReducer from '@/store/rootReducer';

// Create a test store
export function createTestStore(preloadedState = {}) {
  return configureStore({
    reducer: rootReducer,
    preloadedState
  });
}

// Custom render with providers
export function renderWithProviders(
  ui,
  {
    preloadedState = {},
    store = createTestStore(preloadedState),
    ...renderOptions
  } = {}
) {
  function Wrapper({ children }) {
    return (
      <Provider store={store}>
        <BrowserRouter>
          {children}
        </BrowserRouter>
      </Provider>
    );
  }

  return { store, ...render(ui, { wrapper: Wrapper, ...renderOptions }) };
}

// Re-export everything from testing-library
export * from '@testing-library/react';

// Export custom utilities
export { renderWithProviders as render };
```

**Deliverables Day 654:**
- Test database seeding scripts
- Mock payment gateway services
- Coverage threshold configuration
- SonarQube integration
- API mock server
- Component test utilities

---

### Day 655 - Documentation & Milestone Review

**Objective:** Complete test framework documentation and conduct milestone review.

#### Dev 1 - Backend Lead (8h)

**Task 1: Test Framework Documentation (4h)**

```markdown
# Smart Dairy Test Framework Documentation

## 1. Overview

This document describes the testing framework, conventions, and best practices
for the Smart Dairy Digital Portal + ERP system.

## 2. Test Structure

```
tests/
├── conftest.py              # Shared fixtures and configuration
├── fixtures/
│   └── factories.py         # Test data factories
├── mocks/
│   ├── payment_gateway_mock.py
│   └── sms_mock.py
├── unit/
│   ├── test_animal_model.py
│   ├── test_order_model.py
│   └── test_utils.py
├── integration/
│   ├── test_api_endpoints.py
│   ├── test_order_workflow.py
│   └── test_payment_flow.py
└── e2e/
    └── test_full_workflows.py
```

## 3. Running Tests

### Unit Tests
```bash
pytest tests/unit -v --cov
```

### Integration Tests
```bash
pytest tests/integration -v --cov
```

### All Tests with Coverage
```bash
pytest --cov=odoo/addons/smart_dairy --cov-report=html
```

### Run Specific Module
```bash
pytest tests/unit/test_animal_model.py -v
```

### Run with Markers
```bash
pytest -m "farm and unit" -v
```

## 4. Writing Tests

### Unit Test Example
```python
@pytest.mark.unit
@pytest.mark.farm
def test_animal_age_calculation(sample_animal_data):
    """Test animal age calculation."""
    # Arrange
    birth_date = date(2022, 1, 15)

    # Act
    age = calculate_age(birth_date)

    # Assert
    assert age.years == 2
```

### Integration Test Example
```python
@pytest.mark.integration
def test_order_creation(db_session, sample_order_data):
    """Test order creation in database."""
    order = create_order(db_session, sample_order_data)

    assert order.id is not None
    assert order.state == 'draft'
```

## 5. Coverage Requirements

| Test Type | Coverage Target |
|-----------|----------------|
| Unit | 70% |
| Integration | 60% |
| E2E (critical paths) | 50% |

## 6. CI/CD Integration

Tests run automatically on:
- Every push to `develop` or `main`
- Every pull request
- Scheduled daily at 2:00 AM UTC
```

**Task 2: Milestone Sign-off Preparation (4h)**

- Compile all test metrics
- Generate coverage reports
- Prepare milestone presentation
- Document any blockers or issues

#### Dev 2 - Full-Stack (8h)

**Task 1: Test Pipeline Documentation (4h)**

```markdown
# CI/CD Test Pipeline Documentation

## Pipeline Stages

1. **Lint & Static Analysis**
   - ESLint for JavaScript
   - Pylint for Python
   - SonarQube quality gate

2. **Unit Tests**
   - pytest for backend
   - Jest for frontend
   - Coverage threshold: 70%

3. **Integration Tests**
   - API endpoint tests
   - Database integration
   - Coverage threshold: 60%

4. **E2E Tests**
   - Cypress browser tests
   - Critical path coverage: 50%

5. **Report Generation**
   - Coverage reports to Codecov
   - Test results to GitHub Actions
   - Quality metrics to SonarQube
```

**Task 2: Environment Setup Guide (4h)**

```markdown
# Test Environment Setup Guide

## Prerequisites

- Docker & Docker Compose
- Python 3.11+
- Node.js 20+
- PostgreSQL 16 (via Docker)
- Redis 7 (via Docker)

## Quick Start

1. Clone repository:
```bash
git clone https://github.com/smartdairy/portal.git
cd portal
```

2. Start test environment:
```bash
docker-compose -f docker-compose.test.yml up -d
```

3. Install dependencies:
```bash
pip install -r requirements.txt
npm install
```

4. Run tests:
```bash
# Backend
pytest tests/ -v

# Frontend
npm test

# E2E
npm run test:e2e
```
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Frontend Test Documentation (4h)**

```markdown
# Frontend Testing Guide

## Tools

- **Jest**: Unit and integration tests
- **React Testing Library**: Component testing
- **Cypress**: E2E testing
- **MSW**: API mocking

## Running Tests

```bash
# Run all tests
npm test

# Run with coverage
npm run test:coverage

# Run in watch mode
npm run test:watch

# Run E2E tests
npm run test:e2e
```

## Writing Component Tests

```javascript
import { render, screen, fireEvent } from '@testing-library/react';
import MyComponent from './MyComponent';

describe('MyComponent', () => {
  it('renders correctly', () => {
    render(<MyComponent />);
    expect(screen.getByText('Hello')).toBeInTheDocument();
  });

  it('handles click events', () => {
    const onClick = jest.fn();
    render(<MyComponent onClick={onClick} />);
    fireEvent.click(screen.getByRole('button'));
    expect(onClick).toHaveBeenCalled();
  });
});
```
```

**Task 2: Milestone Review & Sign-off (4h)**

- Review all deliverables
- Verify coverage targets
- Conduct team demo
- Document next milestone preparation

**Deliverables Day 655:**
- Test framework documentation
- CI/CD pipeline documentation
- Environment setup guide
- Frontend test guide
- Milestone review completed
- Sign-off documentation

---

## 4. Technical Specifications

### 4.1 Test Framework Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        CI/CD Pipeline                            │
│  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────────────────┐ │
│  │  Lint   │→ │  Unit   │→ │  Int    │→ │     E2E Tests       │ │
│  │ Check   │  │  Tests  │  │  Tests  │  │                     │ │
│  └─────────┘  └─────────┘  └─────────┘  └─────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                      Test Frameworks                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │   pytest     │  │    Jest      │  │   Cypress    │          │
│  │  (Backend)   │  │  (Frontend)  │  │    (E2E)     │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    Coverage Reporting                            │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │   Codecov    │  │  SonarQube   │  │  HTML Report │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
└─────────────────────────────────────────────────────────────────┘
```

---

## 5. Testing & Validation

### 5.1 Milestone Test Cases

| Test ID | Test Case | Expected Result | Status |
|---------|-----------|-----------------|--------|
| M131-01 | pytest configuration loads | No errors | Pending |
| M131-02 | Jest configuration loads | No errors | Pending |
| M131-03 | Cypress configuration loads | No errors | Pending |
| M131-04 | CI pipeline executes | All jobs pass | Pending |
| M131-05 | Coverage report generates | HTML/XML output | Pending |
| M131-06 | Test fixtures work | Data created | Pending |
| M131-07 | Mock services function | Mocks respond | Pending |

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Test environment instability | Medium | High | Docker isolation, health checks |
| Coverage targets not met | Low | Medium | Incremental improvement plan |
| CI/CD pipeline failures | Low | High | Retry logic, notifications |
| Test data conflicts | Medium | Medium | Isolated test databases |

---

## 7. Dependencies & Handoffs

### 7.1 Dependencies

- Phase 13 complete (all features stable)
- CI/CD infrastructure operational
- Test accounts provisioned (Codecov, SonarQube)

### 7.2 Handoffs to Milestone 132

- Test framework fully configured
- Initial test suite established
- CI/CD pipeline operational
- Coverage reporting active
- Documentation complete

---

## Milestone Sign-off Checklist

- [ ] Test strategy document approved
- [ ] pytest framework configured with 50+ tests
- [ ] Jest framework configured with 30+ tests
- [ ] Cypress E2E tests for 5+ critical paths
- [ ] CI/CD pipeline executing all tests
- [ ] Coverage reporting operational
- [ ] Test data factories created
- [ ] Mock services implemented
- [ ] Documentation complete
- [ ] Team sign-off obtained

---

**Document End**
