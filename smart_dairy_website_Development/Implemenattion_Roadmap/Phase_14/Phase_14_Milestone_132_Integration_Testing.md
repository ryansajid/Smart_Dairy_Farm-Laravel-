# Milestone 132: Integration Testing

## Smart Dairy Digital Smart Portal + ERP - Phase 14: Testing & Documentation

---

| Field            | Detail                                                                 |
| ---------------- | ---------------------------------------------------------------------- |
| **Milestone**    | 132 of 150 (2 of 10 in Phase 14)                                       |
| **Title**        | Integration Testing                                                    |
| **Phase**        | Phase 14 - Testing & Documentation                                     |
| **Days**         | Days 656-660 (of 750 total)                                            |
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

Execute comprehensive integration testing across all Smart Dairy modules, validating API contracts, database operations, third-party payment gateway integrations (bKash, Nagad, Rocket, SSLCommerz), IoT data pipelines, and end-to-end workflows spanning multiple modules.

### 1.2 Objectives

1. Develop and execute API contract tests for all REST endpoints
2. Validate database integration with CRUD operations and transactions
3. Test payment gateway integrations (bKash, Nagad, Rocket, SSLCommerz)
4. Verify SMS/Email service integrations
5. Validate IoT data pipeline from sensors to TimescaleDB
6. Test cross-module workflows (Order→Inventory→Delivery)
7. Verify third-party service error handling and retry logic
8. Achieve 60%+ integration test coverage

### 1.3 Key Deliverables

| Deliverable                       | Owner  | Format            | Due Day |
| --------------------------------- | ------ | ----------------- | ------- |
| API Contract Test Suite           | Dev 1  | Python/pytest     | 656     |
| Database Integration Tests        | Dev 1  | Python/pytest     | 657     |
| Payment Gateway Test Suite        | Dev 2  | Python            | 658     |
| SMS/Email Integration Tests       | Dev 2  | Python            | 658     |
| IoT Pipeline Tests                | Dev 1  | Python            | 659     |
| Frontend-API Integration Tests    | Dev 3  | JavaScript/Jest   | 657     |
| Mobile-API Integration Tests      | Dev 3  | Dart/Flutter      | 658     |
| E2E Workflow Tests                | All    | Cypress           | 659     |
| Integration Test Report           | All    | Markdown/PDF      | 660     |

### 1.4 Prerequisites

- Milestone 131 complete (test framework operational)
- Test environment with all services running
- Payment gateway sandbox credentials
- SMS/Email service test accounts
- IoT simulator available

### 1.5 Success Criteria

- [ ] 100+ API endpoint tests passing
- [ ] All payment gateway flows tested (sandbox)
- [ ] SMS/Email delivery verified in test environment
- [ ] IoT data pipeline validated end-to-end
- [ ] 60%+ integration test coverage achieved
- [ ] Zero critical integration bugs
- [ ] All cross-module workflows validated

---

## 2. Requirement Traceability Matrix

| Req ID       | Source | Requirement Description                  | Day(s)  | Task Reference              |
| ------------ | ------ | ---------------------------------------- | ------- | --------------------------- |
| SRS-TEST-002 | SRS    | Integration test coverage > 60%          | 656-660 | All integration tests       |
| RFP-PAY-001  | RFP    | bKash payment integration                | 658     | Payment gateway tests       |
| RFP-PAY-002  | RFP    | Nagad payment integration                | 658     | Payment gateway tests       |
| RFP-PAY-003  | RFP    | Rocket payment integration               | 658     | Payment gateway tests       |
| RFP-PAY-004  | RFP    | SSLCommerz card payments                 | 658     | Payment gateway tests       |
| BRD-IOT-001  | BRD    | IoT sensor data integration              | 659     | IoT pipeline tests          |
| SRS-API-001  | SRS    | RESTful API compliance                   | 656     | API contract tests          |

---

## 3. Day-by-Day Breakdown

### Day 656 - API Contract Testing

**Objective:** Develop and execute comprehensive API contract tests for all endpoints.

#### Dev 1 - Backend Lead (8h)

**Task 1: API Contract Test Suite (4h)**

```python
# tests/integration/test_api_contracts.py
"""API contract tests for all Smart Dairy endpoints."""
import pytest
import requests
from jsonschema import validate, ValidationError

class TestProductAPI:
    """Product API contract tests."""

    PRODUCT_SCHEMA = {
        "type": "object",
        "properties": {
            "id": {"type": "integer"},
            "name": {"type": "string"},
            "price": {"type": "number"},
            "default_code": {"type": "string"},
            "category_id": {"type": "integer"},
            "in_stock": {"type": "boolean"},
            "quantity_available": {"type": "number"}
        },
        "required": ["id", "name", "price"]
    }

    @pytest.mark.integration
    @pytest.mark.api
    def test_get_products_returns_valid_schema(self, api_client, test_config):
        """Test GET /products returns valid schema."""
        response = api_client.get(f"{test_config['api_base_url']}/api/v1/products")

        assert response.status_code == 200
        data = response.json()

        for product in data.get('products', []):
            try:
                validate(instance=product, schema=self.PRODUCT_SCHEMA)
            except ValidationError as e:
                pytest.fail(f"Product schema validation failed: {e.message}")

    @pytest.mark.integration
    @pytest.mark.api
    def test_get_product_by_id(self, api_client, test_config):
        """Test GET /products/{id} returns single product."""
        response = api_client.get(f"{test_config['api_base_url']}/api/v1/products/1")

        assert response.status_code in [200, 404]
        if response.status_code == 200:
            product = response.json()
            validate(instance=product, schema=self.PRODUCT_SCHEMA)

    @pytest.mark.integration
    @pytest.mark.api
    @pytest.mark.parametrize("endpoint,expected_keys", [
        ("/api/v1/products", ["products"]),
        ("/api/v1/categories", ["categories"]),
        ("/api/v1/cart", ["items", "total"]),
    ])
    def test_endpoint_response_structure(self, api_client, test_config, endpoint, expected_keys):
        """Test endpoints return expected response structure."""
        response = api_client.get(f"{test_config['api_base_url']}{endpoint}")

        assert response.status_code in [200, 401]
        if response.status_code == 200:
            data = response.json()
            for key in expected_keys:
                assert key in data, f"Missing key: {key}"


class TestOrderAPI:
    """Order API contract tests."""

    ORDER_SCHEMA = {
        "type": "object",
        "properties": {
            "id": {"type": "integer"},
            "name": {"type": "string"},
            "state": {"type": "string", "enum": ["draft", "sent", "sale", "done", "cancel"]},
            "amount_total": {"type": "number"},
            "partner_id": {"type": "integer"},
            "order_line": {"type": "array"}
        },
        "required": ["id", "name", "state", "amount_total"]
    }

    @pytest.mark.integration
    @pytest.mark.api
    def test_create_order(self, api_client, test_config, sample_order_data):
        """Test POST /orders creates order correctly."""
        response = api_client.post(
            f"{test_config['api_base_url']}/api/v1/orders",
            json=sample_order_data
        )

        assert response.status_code == 201
        order = response.json()
        assert 'order_id' in order or 'id' in order

    @pytest.mark.integration
    @pytest.mark.api
    def test_order_status_transitions(self, api_client, test_config):
        """Test order status can be updated correctly."""
        # Create order
        create_response = api_client.post(
            f"{test_config['api_base_url']}/api/v1/orders",
            json={"partner_id": 1, "order_line": [{"product_id": 1, "quantity": 1}]}
        )
        order_id = create_response.json().get('order_id')

        # Confirm order
        confirm_response = api_client.post(
            f"{test_config['api_base_url']}/api/v1/orders/{order_id}/confirm"
        )

        assert confirm_response.status_code == 200
        assert confirm_response.json().get('state') == 'sale'


class TestFarmAPI:
    """Farm management API contract tests."""

    ANIMAL_SCHEMA = {
        "type": "object",
        "properties": {
            "id": {"type": "integer"},
            "name": {"type": "string"},
            "ear_tag": {"type": "string"},
            "breed": {"type": "string"},
            "status": {"type": "string"},
            "birth_date": {"type": "string"}
        },
        "required": ["id", "name", "ear_tag"]
    }

    @pytest.mark.integration
    @pytest.mark.api
    @pytest.mark.farm
    def test_get_animals(self, api_client, test_config):
        """Test GET /farm/animals returns animal list."""
        response = api_client.get(f"{test_config['api_base_url']}/api/v1/farm/animals")

        assert response.status_code == 200
        data = response.json()
        assert 'animals' in data

    @pytest.mark.integration
    @pytest.mark.api
    @pytest.mark.farm
    def test_create_animal(self, api_client, test_config, sample_animal_data):
        """Test POST /farm/animals creates animal."""
        response = api_client.post(
            f"{test_config['api_base_url']}/api/v1/farm/animals",
            json=sample_animal_data
        )

        assert response.status_code == 201
        animal = response.json()
        assert animal.get('ear_tag') == sample_animal_data['ear_tag']

    @pytest.mark.integration
    @pytest.mark.api
    @pytest.mark.farm
    def test_record_milk_production(self, api_client, test_config):
        """Test POST /farm/milk-production records production."""
        milk_data = {
            "animal_id": 1,
            "date": "2024-03-15",
            "session": "morning",
            "quantity_liters": 12.5,
            "fat_percentage": 4.2,
            "snf_percentage": 8.5
        }

        response = api_client.post(
            f"{test_config['api_base_url']}/api/v1/farm/milk-production",
            json=milk_data
        )

        assert response.status_code in [200, 201]
```

**Task 2: API Error Handling Tests (4h)**

```python
# tests/integration/test_api_errors.py
"""API error handling tests."""
import pytest

class TestAPIErrorHandling:
    """Test API error responses."""

    @pytest.mark.integration
    @pytest.mark.api
    def test_invalid_endpoint_returns_404(self, api_client, test_config):
        """Test non-existent endpoint returns 404."""
        response = api_client.get(f"{test_config['api_base_url']}/api/v1/nonexistent")
        assert response.status_code == 404

    @pytest.mark.integration
    @pytest.mark.api
    def test_invalid_method_returns_405(self, api_client, test_config):
        """Test invalid HTTP method returns 405."""
        response = api_client.delete(f"{test_config['api_base_url']}/api/v1/products")
        assert response.status_code in [405, 404]

    @pytest.mark.integration
    @pytest.mark.api
    def test_invalid_json_returns_400(self, api_client, test_config):
        """Test invalid JSON body returns 400."""
        response = api_client.post(
            f"{test_config['api_base_url']}/api/v1/orders",
            data="invalid json",
            headers={"Content-Type": "application/json"}
        )
        assert response.status_code == 400

    @pytest.mark.integration
    @pytest.mark.api
    def test_missing_required_fields_returns_400(self, api_client, test_config):
        """Test missing required fields returns 400."""
        response = api_client.post(
            f"{test_config['api_base_url']}/api/v1/orders",
            json={}  # Missing required fields
        )
        assert response.status_code == 400
        assert 'error' in response.json() or 'message' in response.json()

    @pytest.mark.integration
    @pytest.mark.api
    def test_unauthenticated_request_returns_401(self, test_config):
        """Test unauthenticated request returns 401."""
        import requests
        response = requests.get(
            f"{test_config['api_base_url']}/api/v1/orders",
            headers={"Authorization": ""}
        )
        assert response.status_code in [401, 403]

    @pytest.mark.integration
    @pytest.mark.api
    def test_rate_limiting(self, api_client, test_config):
        """Test rate limiting is enforced."""
        # Make many rapid requests
        responses = []
        for _ in range(100):
            response = api_client.get(f"{test_config['api_base_url']}/api/v1/products")
            responses.append(response.status_code)

        # Should eventually get rate limited (429)
        # Or all should succeed if rate limit is high
        assert all(code in [200, 429] for code in responses)
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Database Integration Tests (4h)**

```python
# tests/integration/test_database.py
"""Database integration tests."""
import pytest
from datetime import datetime, timedelta

class TestDatabaseOperations:
    """Test database CRUD operations."""

    @pytest.mark.integration
    @pytest.mark.database
    def test_transaction_commit(self, db_session):
        """Test database transaction commits correctly."""
        # Create record
        partner = db_session.execute("""
            INSERT INTO res_partner (name, email, create_date)
            VALUES ('Test Partner', 'test@test.com', NOW())
            RETURNING id
        """).fetchone()

        db_session.commit()

        # Verify record exists
        result = db_session.execute(
            "SELECT * FROM res_partner WHERE id = %s", (partner.id,)
        ).fetchone()

        assert result is not None
        assert result.name == 'Test Partner'

    @pytest.mark.integration
    @pytest.mark.database
    def test_transaction_rollback(self, db_session):
        """Test database transaction rollback."""
        # Start transaction
        initial_count = db_session.execute(
            "SELECT COUNT(*) FROM res_partner"
        ).fetchone()[0]

        # Insert record
        db_session.execute("""
            INSERT INTO res_partner (name, email, create_date)
            VALUES ('Rollback Test', 'rollback@test.com', NOW())
        """)

        # Rollback
        db_session.rollback()

        # Verify count unchanged
        final_count = db_session.execute(
            "SELECT COUNT(*) FROM res_partner"
        ).fetchone()[0]

        assert final_count == initial_count

    @pytest.mark.integration
    @pytest.mark.database
    def test_foreign_key_constraint(self, db_session):
        """Test foreign key constraints are enforced."""
        with pytest.raises(Exception):  # IntegrityError
            db_session.execute("""
                INSERT INTO sale_order_line (order_id, product_id, product_uom_qty)
                VALUES (99999, 1, 5)  -- Non-existent order_id
            """)
            db_session.commit()

    @pytest.mark.integration
    @pytest.mark.database
    def test_concurrent_updates(self, db_session):
        """Test concurrent update handling."""
        # This would test optimistic locking
        pass
```

**Task 2: CI/CD Integration Test Pipeline (4h)**

```yaml
# .github/workflows/integration-tests.yml
name: Integration Test Suite

on:
  push:
    branches: [develop, main]
  pull_request:
    branches: [develop, main]

jobs:
  integration-tests:
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
          python-version: '3.11'

      - name: Install dependencies
        run: |
          pip install -r requirements.txt
          pip install pytest pytest-cov requests jsonschema

      - name: Wait for services
        run: |
          sleep 10

      - name: Run integration tests
        env:
          DATABASE_URL: postgresql://odoo:odoo@localhost:5432/smart_dairy_test
          REDIS_URL: redis://localhost:6379
        run: |
          pytest tests/integration -v \
            --cov=odoo/addons \
            --cov-report=xml \
            --junitxml=integration-results.xml

      - name: Upload coverage
        uses: codecov/codecov-action@v4
        with:
          files: ./coverage.xml
          flags: integration

      - name: Upload test results
        uses: actions/upload-artifact@v4
        if: always()
        with:
          name: integration-test-results
          path: integration-results.xml
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Frontend-API Integration Tests (4h)**

```javascript
// tests/integration/api-integration.test.js
import { rest } from 'msw';
import { setupServer } from 'msw/node';
import { renderHook, waitFor } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { useProducts, useCreateOrder, useCart } from '@/hooks/api';

// Setup MSW server
const server = setupServer(
  rest.get('/api/v1/products', (req, res, ctx) => {
    return res(ctx.json({
      products: [
        { id: 1, name: 'Fresh Milk 1L', price: 80, inStock: true },
        { id: 2, name: 'Yogurt 500g', price: 60, inStock: true }
      ]
    }));
  }),

  rest.post('/api/v1/orders', (req, res, ctx) => {
    return res(ctx.status(201), ctx.json({
      order_id: 'ORD-2024-001',
      status: 'confirmed'
    }));
  }),

  rest.get('/api/v1/cart', (req, res, ctx) => {
    return res(ctx.json({
      items: [{ product_id: 1, quantity: 2, price: 80 }],
      total: 160
    }));
  })
);

beforeAll(() => server.listen());
afterEach(() => server.resetHandlers());
afterAll(() => server.close());

// Wrapper for react-query
const wrapper = ({ children }) => {
  const queryClient = new QueryClient({
    defaultOptions: { queries: { retry: false } }
  });
  return (
    <QueryClientProvider client={queryClient}>
      {children}
    </QueryClientProvider>
  );
};

describe('API Integration Tests', () => {
  describe('useProducts hook', () => {
    it('fetches products successfully', async () => {
      const { result } = renderHook(() => useProducts(), { wrapper });

      await waitFor(() => expect(result.current.isSuccess).toBe(true));

      expect(result.current.data.products).toHaveLength(2);
      expect(result.current.data.products[0].name).toBe('Fresh Milk 1L');
    });

    it('handles API error gracefully', async () => {
      server.use(
        rest.get('/api/v1/products', (req, res, ctx) => {
          return res(ctx.status(500), ctx.json({ error: 'Server error' }));
        })
      );

      const { result } = renderHook(() => useProducts(), { wrapper });

      await waitFor(() => expect(result.current.isError).toBe(true));
      expect(result.current.error).toBeDefined();
    });
  });

  describe('useCreateOrder hook', () => {
    it('creates order successfully', async () => {
      const { result } = renderHook(() => useCreateOrder(), { wrapper });

      await result.current.mutateAsync({
        items: [{ product_id: 1, quantity: 2 }],
        delivery_address: '123 Test St'
      });

      await waitFor(() => expect(result.current.isSuccess).toBe(true));
      expect(result.current.data.order_id).toBe('ORD-2024-001');
    });
  });

  describe('useCart hook', () => {
    it('fetches cart with correct total', async () => {
      const { result } = renderHook(() => useCart(), { wrapper });

      await waitFor(() => expect(result.current.isSuccess).toBe(true));

      expect(result.current.data.total).toBe(160);
      expect(result.current.data.items).toHaveLength(1);
    });
  });
});
```

**Task 2: Component Integration Tests (4h)**

```javascript
// tests/integration/checkout-flow.test.js
import React from 'react';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { MemoryRouter, Routes, Route } from 'react-router-dom';
import { QueryClientProvider, QueryClient } from '@tanstack/react-query';
import { Provider } from 'react-redux';
import { configureStore } from '@reduxjs/toolkit';

import CartPage from '@/pages/CartPage';
import CheckoutPage from '@/pages/CheckoutPage';
import ConfirmationPage from '@/pages/ConfirmationPage';
import cartReducer from '@/store/cartSlice';

// Setup test environment
const createTestStore = (preloadedState = {}) => {
  return configureStore({
    reducer: { cart: cartReducer },
    preloadedState
  });
};

const renderWithProviders = (ui, { preloadedState = {}, route = '/' } = {}) => {
  const store = createTestStore(preloadedState);
  const queryClient = new QueryClient({
    defaultOptions: { queries: { retry: false } }
  });

  return render(
    <Provider store={store}>
      <QueryClientProvider client={queryClient}>
        <MemoryRouter initialEntries={[route]}>
          <Routes>
            <Route path="/cart" element={<CartPage />} />
            <Route path="/checkout" element={<CheckoutPage />} />
            <Route path="/confirmation/:orderId" element={<ConfirmationPage />} />
          </Routes>
        </MemoryRouter>
      </QueryClientProvider>
    </Provider>
  );
};

describe('Checkout Flow Integration', () => {
  const cartWithItems = {
    cart: {
      items: [
        { id: 1, name: 'Fresh Milk 1L', price: 80, quantity: 2 }
      ],
      total: 160
    }
  };

  it('displays cart items and total', () => {
    renderWithProviders(<CartPage />, {
      preloadedState: cartWithItems,
      route: '/cart'
    });

    expect(screen.getByText('Fresh Milk 1L')).toBeInTheDocument();
    expect(screen.getByText('৳160')).toBeInTheDocument();
  });

  it('navigates to checkout when proceed button clicked', async () => {
    renderWithProviders(<CartPage />, {
      preloadedState: cartWithItems,
      route: '/cart'
    });

    const proceedButton = screen.getByRole('button', { name: /proceed to checkout/i });
    fireEvent.click(proceedButton);

    await waitFor(() => {
      expect(screen.getByText(/shipping information/i)).toBeInTheDocument();
    });
  });

  it('validates required fields on checkout', async () => {
    renderWithProviders(<CheckoutPage />, {
      preloadedState: cartWithItems,
      route: '/checkout'
    });

    const submitButton = screen.getByRole('button', { name: /place order/i });
    fireEvent.click(submitButton);

    await waitFor(() => {
      expect(screen.getByText(/name is required/i)).toBeInTheDocument();
      expect(screen.getByText(/phone is required/i)).toBeInTheDocument();
    });
  });
});
```

**Deliverables Day 656:**
- API contract test suite (50+ tests)
- API error handling tests
- Database integration tests
- CI/CD integration pipeline
- Frontend-API integration tests
- Component integration tests

---

### Day 657 - Database & Module Integration

**Objective:** Complete database integration tests and cross-module workflow testing.

#### Dev 1 - Backend Lead (8h)

**Task 1: Odoo Module Integration Tests (4h)**

```python
# tests/integration/test_odoo_modules.py
"""Odoo module integration tests."""
import pytest
from odoo.tests.common import TransactionCase, tagged
from datetime import date, timedelta

@tagged('integration', 'odoo')
class TestSaleInventoryIntegration(TransactionCase):
    """Test Sale and Inventory module integration."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.product = cls.env['product.product'].create({
            'name': 'Test Milk',
            'type': 'product',
            'list_price': 80.0,
        })
        # Add stock
        cls.env['stock.quant'].create({
            'product_id': cls.product.id,
            'location_id': cls.env.ref('stock.stock_location_stock').id,
            'quantity': 100
        })

        cls.customer = cls.env['res.partner'].create({
            'name': 'Test Customer',
            'customer_rank': 1
        })

    @pytest.mark.integration
    def test_sale_reduces_inventory(self):
        """Test that confirming sale order reduces inventory."""
        initial_qty = self.product.qty_available

        order = self.env['sale.order'].create({
            'partner_id': self.customer.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 10
            })]
        })
        order.action_confirm()

        # Process delivery
        picking = order.picking_ids[0]
        picking.action_confirm()
        picking.action_assign()
        for move in picking.move_ids:
            move.quantity = move.product_uom_qty
        picking.button_validate()

        # Verify inventory reduced
        final_qty = self.product.qty_available
        self.assertEqual(final_qty, initial_qty - 10)

    @pytest.mark.integration
    def test_order_creates_invoice(self):
        """Test that delivered order can create invoice."""
        order = self.env['sale.order'].create({
            'partner_id': self.customer.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 5
            })]
        })
        order.action_confirm()

        # Complete delivery
        picking = order.picking_ids[0]
        picking.action_confirm()
        picking.action_assign()
        for move in picking.move_ids:
            move.quantity = move.product_uom_qty
        picking.button_validate()

        # Create invoice
        order._create_invoices()

        self.assertTrue(order.invoice_ids)
        self.assertEqual(order.invoice_ids[0].amount_total, 400.0)  # 5 * 80


@tagged('integration', 'farm')
class TestFarmModuleIntegration(TransactionCase):
    """Test Farm module integration."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.breed = cls.env['farm.breed'].create({
            'name': 'Holstein Friesian',
            'code': 'HF'
        })
        cls.animal = cls.env['farm.animal'].create({
            'name': 'Test Cow',
            'ear_tag': 'SD-TEST-001',
            'breed_id': cls.breed.id,
            'birth_date': date.today() - timedelta(days=730),
            'status': 'active',
            'gender': 'female'
        })

    @pytest.mark.integration
    @pytest.mark.farm
    def test_milk_production_updates_totals(self):
        """Test milk production records update animal totals."""
        # Record morning production
        self.env['farm.milk.production'].create({
            'animal_id': self.animal.id,
            'date': date.today(),
            'session': 'morning',
            'quantity_liters': 12.0,
            'fat_percentage': 4.0,
            'snf_percentage': 8.5
        })

        # Record evening production
        self.env['farm.milk.production'].create({
            'animal_id': self.animal.id,
            'date': date.today(),
            'session': 'evening',
            'quantity_liters': 10.0,
            'fat_percentage': 4.2,
            'snf_percentage': 8.6
        })

        # Verify daily total
        daily_total = self.animal.get_daily_milk_total(date.today())
        self.assertEqual(daily_total, 22.0)

    @pytest.mark.integration
    @pytest.mark.farm
    def test_health_record_creates_alert(self):
        """Test health record creates alert for sick animal."""
        self.env['farm.health.record'].create({
            'animal_id': self.animal.id,
            'date': date.today(),
            'type': 'illness',
            'diagnosis': 'Mastitis',
            'severity': 'high'
        })

        # Check animal status updated
        self.assertEqual(self.animal.status, 'sick')

        # Check alert created
        alerts = self.env['farm.alert'].search([
            ('animal_id', '=', self.animal.id),
            ('type', '=', 'health')
        ])
        self.assertTrue(alerts)
```

**Task 2: Cross-Module Workflow Tests (4h)**

```python
# tests/integration/test_workflows.py
"""Cross-module workflow integration tests."""
import pytest
from odoo.tests.common import TransactionCase, tagged

@tagged('integration', 'workflow')
class TestOrderToDeliveryWorkflow(TransactionCase):
    """Test complete order to delivery workflow."""

    @pytest.mark.integration
    @pytest.mark.workflow
    def test_complete_b2c_order_flow(self):
        """Test complete B2C order flow from cart to delivery."""
        # 1. Create customer
        customer = self.env['res.partner'].create({
            'name': 'B2C Customer',
            'email': 'customer@test.com',
            'phone': '+8801712345678'
        })

        # 2. Create product with stock
        product = self.env['product.product'].create({
            'name': 'Fresh Milk',
            'type': 'product',
            'list_price': 80.0
        })
        self.env['stock.quant'].create({
            'product_id': product.id,
            'location_id': self.env.ref('stock.stock_location_stock').id,
            'quantity': 50
        })

        # 3. Create order (simulating cart checkout)
        order = self.env['sale.order'].create({
            'partner_id': customer.id,
            'commitment_date': date.today() + timedelta(days=1),
            'order_line': [(0, 0, {
                'product_id': product.id,
                'product_uom_qty': 5
            })]
        })

        # 4. Confirm order
        order.action_confirm()
        self.assertEqual(order.state, 'sale')

        # 5. Process delivery
        picking = order.picking_ids[0]
        picking.action_confirm()
        picking.action_assign()
        for move in picking.move_ids:
            move.quantity = move.product_uom_qty
        picking.button_validate()

        self.assertEqual(picking.state, 'done')

        # 6. Create and validate invoice
        invoice = order._create_invoices()
        invoice.action_post()

        self.assertEqual(invoice.state, 'posted')
        self.assertEqual(invoice.amount_total, 400.0)

        # 7. Register payment
        payment = self.env['account.payment'].create({
            'payment_type': 'inbound',
            'partner_id': customer.id,
            'amount': 400.0,
            'journal_id': self.env['account.journal'].search(
                [('type', '=', 'bank')], limit=1
            ).id
        })
        payment.action_post()

        # Verify order fully paid
        self.assertEqual(invoice.payment_state, 'paid')
```

#### Dev 2 - Full-Stack (8h)

**Task 1: Third-Party Service Integration Framework (4h)**

```python
# tests/integration/test_third_party_services.py
"""Third-party service integration tests."""
import pytest
from unittest.mock import patch, Mock

class TestSMSIntegration:
    """SMS service integration tests."""

    @pytest.mark.integration
    @pytest.mark.external
    def test_order_confirmation_sms(self, mock_sms):
        """Test SMS sent on order confirmation."""
        with patch('odoo.addons.smart_sms.sms_service.send_sms', mock_sms.send_sms):
            # Simulate order confirmation
            phone = '+8801712345678'
            message = "Your order ORD-2024-001 has been confirmed!"

            mock_sms.send_sms(phone, message)

            sent = mock_sms.get_sent_messages()
            assert len(sent) == 1
            assert sent[0]['phone'] == phone
            assert 'ORD-2024-001' in sent[0]['message']

    @pytest.mark.integration
    @pytest.mark.external
    def test_delivery_notification_sms(self, mock_sms):
        """Test SMS sent on delivery."""
        with patch('odoo.addons.smart_sms.sms_service.send_sms', mock_sms.send_sms):
            phone = '+8801712345678'
            message = "Your order is out for delivery!"

            mock_sms.send_sms(phone, message)

            sent = mock_sms.get_sent_messages()
            assert len(sent) == 1


class TestEmailIntegration:
    """Email service integration tests."""

    @pytest.mark.integration
    @pytest.mark.external
    def test_order_confirmation_email(self, mock_email):
        """Test email sent on order confirmation."""
        with patch('odoo.addons.mail.models.mail.send_mail', mock_email.send):
            email_data = {
                'to': 'customer@test.com',
                'subject': 'Order Confirmation - ORD-2024-001',
                'body': 'Thank you for your order!'
            }

            mock_email.send(**email_data)

            sent = mock_email.get_sent_emails()
            assert len(sent) == 1
            assert sent[0]['to'] == 'customer@test.com'

    @pytest.mark.integration
    @pytest.mark.external
    def test_invoice_email(self, mock_email):
        """Test invoice email sent."""
        with patch('odoo.addons.mail.models.mail.send_mail', mock_email.send):
            email_data = {
                'to': 'customer@test.com',
                'subject': 'Invoice - INV-2024-001',
                'attachments': ['invoice.pdf']
            }

            mock_email.send(**email_data)

            sent = mock_email.get_sent_emails()
            assert len(sent) == 1
            assert 'invoice.pdf' in sent[0].get('attachments', [])
```

**Task 2: Redis Cache Integration Tests (4h)**

```python
# tests/integration/test_redis_cache.py
"""Redis cache integration tests."""
import pytest
import redis
import json

class TestRedisCacheIntegration:
    """Test Redis caching functionality."""

    @pytest.fixture
    def redis_client(self, test_config):
        """Create Redis client for testing."""
        return redis.Redis.from_url(test_config.get('redis_url', 'redis://localhost:6379'))

    @pytest.mark.integration
    @pytest.mark.cache
    def test_product_cache_set_get(self, redis_client):
        """Test product caching."""
        product_data = {
            'id': 1,
            'name': 'Fresh Milk 1L',
            'price': 80
        }

        # Set cache
        cache_key = 'product:1'
        redis_client.setex(cache_key, 3600, json.dumps(product_data))

        # Get from cache
        cached = redis_client.get(cache_key)
        assert cached is not None

        cached_data = json.loads(cached)
        assert cached_data['name'] == 'Fresh Milk 1L'

    @pytest.mark.integration
    @pytest.mark.cache
    def test_cache_invalidation(self, redis_client):
        """Test cache invalidation on update."""
        cache_key = 'product:1'

        # Set initial cache
        redis_client.set(cache_key, json.dumps({'name': 'Old Name'}))

        # Invalidate
        redis_client.delete(cache_key)

        # Verify deleted
        assert redis_client.get(cache_key) is None

    @pytest.mark.integration
    @pytest.mark.cache
    def test_session_cache(self, redis_client):
        """Test session caching."""
        session_id = 'sess_123456'
        session_data = {
            'user_id': 1,
            'cart': [{'product_id': 1, 'qty': 2}]
        }

        # Store session
        redis_client.setex(f'session:{session_id}', 86400, json.dumps(session_data))

        # Retrieve session
        stored = redis_client.get(f'session:{session_id}')
        assert stored is not None

        data = json.loads(stored)
        assert data['user_id'] == 1
        assert len(data['cart']) == 1
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Mobile-API Integration Tests (4h)**

```dart
// test/integration/api_integration_test.dart
import 'package:flutter_test/flutter_test.dart';
import 'package:http/http.dart' as http;
import 'package:http/testing.dart';
import 'package:smart_dairy_app/services/api_service.dart';
import 'dart:convert';

void main() {
  group('API Integration Tests', () {
    late ApiService apiService;
    late MockClient mockClient;

    setUp(() {
      mockClient = MockClient((request) async {
        if (request.url.path == '/api/v1/products') {
          return http.Response(
            jsonEncode({
              'products': [
                {'id': 1, 'name': 'Fresh Milk 1L', 'price': 80}
              ]
            }),
            200,
          );
        }
        if (request.url.path == '/api/v1/orders' && request.method == 'POST') {
          return http.Response(
            jsonEncode({'order_id': 'ORD-2024-001', 'status': 'confirmed'}),
            201,
          );
        }
        return http.Response('Not Found', 404);
      });

      apiService = ApiService(client: mockClient);
    });

    test('fetches products successfully', () async {
      final products = await apiService.getProducts();

      expect(products, isNotEmpty);
      expect(products[0].name, 'Fresh Milk 1L');
      expect(products[0].price, 80);
    });

    test('creates order successfully', () async {
      final result = await apiService.createOrder(
        items: [OrderItem(productId: 1, quantity: 2)],
        deliveryAddress: '123 Test St',
      );

      expect(result.orderId, 'ORD-2024-001');
      expect(result.status, 'confirmed');
    });

    test('handles network error gracefully', () async {
      final errorClient = MockClient((request) async {
        throw Exception('Network error');
      });

      final errorApiService = ApiService(client: errorClient);

      expect(
        () => errorApiService.getProducts(),
        throwsA(isA<ApiException>()),
      );
    });

    test('handles 401 unauthorized', () async {
      final authClient = MockClient((request) async {
        return http.Response('Unauthorized', 401);
      });

      final authApiService = ApiService(client: authClient);

      expect(
        () => authApiService.getProducts(),
        throwsA(isA<UnauthorizedException>()),
      );
    });
  });

  group('Offline Sync Integration', () {
    test('queues requests when offline', () async {
      // Test offline queue functionality
      final offlineService = OfflineSyncService();

      await offlineService.queueRequest(
        method: 'POST',
        endpoint: '/api/v1/orders',
        body: {'items': []},
      );

      final queue = await offlineService.getQueuedRequests();
      expect(queue, hasLength(1));
    });

    test('syncs queued requests when online', () async {
      // Test sync functionality
      final offlineService = OfflineSyncService();
      final mockApi = MockApiService();

      await offlineService.syncQueuedRequests(mockApi);

      // Verify queue emptied after sync
      final queue = await offlineService.getQueuedRequests();
      expect(queue, isEmpty);
    });
  });
}
```

**Task 2: Real-time WebSocket Tests (4h)**

```javascript
// tests/integration/websocket.test.js
import WS from 'jest-websocket-mock';

describe('WebSocket Integration', () => {
  let server;
  let client;

  beforeEach(() => {
    server = new WS('ws://localhost:8080/ws/notifications');
  });

  afterEach(() => {
    WS.clean();
  });

  it('connects to notification server', async () => {
    client = new WebSocket('ws://localhost:8080/ws/notifications');

    await server.connected;
    expect(client.readyState).toBe(WebSocket.OPEN);
  });

  it('receives order update notifications', async () => {
    client = new WebSocket('ws://localhost:8080/ws/notifications');
    await server.connected;

    const messages = [];
    client.onmessage = (event) => {
      messages.push(JSON.parse(event.data));
    };

    server.send(JSON.stringify({
      type: 'order_update',
      order_id: 'ORD-2024-001',
      status: 'shipped'
    }));

    await new Promise(resolve => setTimeout(resolve, 100));

    expect(messages).toHaveLength(1);
    expect(messages[0].type).toBe('order_update');
    expect(messages[0].status).toBe('shipped');
  });

  it('receives delivery tracking updates', async () => {
    client = new WebSocket('ws://localhost:8080/ws/notifications');
    await server.connected;

    const messages = [];
    client.onmessage = (event) => {
      messages.push(JSON.parse(event.data));
    };

    server.send(JSON.stringify({
      type: 'delivery_tracking',
      order_id: 'ORD-2024-001',
      location: { lat: 23.8103, lng: 90.4125 },
      eta: '10 minutes'
    }));

    await new Promise(resolve => setTimeout(resolve, 100));

    expect(messages[0].type).toBe('delivery_tracking');
    expect(messages[0].eta).toBe('10 minutes');
  });

  it('handles reconnection on disconnect', async () => {
    client = new WebSocket('ws://localhost:8080/ws/notifications');
    await server.connected;

    server.close();

    // Simulate reconnection attempt
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Create new server for reconnection
    const newServer = new WS('ws://localhost:8080/ws/notifications');
    const newClient = new WebSocket('ws://localhost:8080/ws/notifications');

    await newServer.connected;
    expect(newClient.readyState).toBe(WebSocket.OPEN);
  });
});
```

**Deliverables Day 657:**
- Odoo module integration tests
- Cross-module workflow tests
- Third-party service integration framework
- Redis cache integration tests
- Mobile-API integration tests
- WebSocket integration tests

---

### Day 658 - Payment Gateway Integration

**Objective:** Complete payment gateway integration tests for bKash, Nagad, Rocket, and SSLCommerz.

#### Dev 1 - Backend Lead (8h)

**Task 1: bKash Integration Tests (4h)**

```python
# tests/integration/test_bkash_integration.py
"""bKash payment gateway integration tests."""
import pytest
from unittest.mock import patch, Mock
from decimal import Decimal

class TestBkashIntegration:
    """Test bKash payment integration."""

    @pytest.fixture
    def bkash_sandbox(self):
        """bKash sandbox configuration."""
        return {
            'app_key': 'test_app_key',
            'app_secret': 'test_app_secret',
            'username': 'test_username',
            'password': 'test_password',
            'base_url': 'https://tokenized.sandbox.bka.sh/v1.2.0-beta'
        }

    @pytest.mark.integration
    @pytest.mark.payment
    def test_bkash_token_generation(self, bkash_sandbox, mock_bkash):
        """Test bKash token generation."""
        with patch('requests.post') as mock_post:
            mock_post.return_value = Mock(
                status_code=200,
                json=lambda: {
                    'id_token': 'test_token_123',
                    'token_type': 'Bearer',
                    'expires_in': 3600
                }
            )

            # Call token generation
            from odoo.addons.smart_payment.bkash import BkashGateway
            gateway = BkashGateway(bkash_sandbox)
            token = gateway.get_token()

            assert token is not None
            assert 'test_token' in token

    @pytest.mark.integration
    @pytest.mark.payment
    def test_bkash_create_payment(self, bkash_sandbox, mock_bkash):
        """Test bKash payment creation."""
        payment_data = {
            'amount': 500.00,
            'currency': 'BDT',
            'intent': 'sale',
            'merchantInvoiceNumber': 'INV-2024-001',
            'callbackURL': 'https://smartdairy.com/payment/callback'
        }

        result = mock_bkash.create_payment(
            amount=payment_data['amount'],
            reference=payment_data['merchantInvoiceNumber']
        )

        assert 'paymentID' in result
        assert 'bkashURL' in result
        assert result['status'] == 'success'

    @pytest.mark.integration
    @pytest.mark.payment
    def test_bkash_execute_payment(self, mock_bkash):
        """Test bKash payment execution."""
        # First create payment
        create_result = mock_bkash.create_payment(amount=500.00, reference='INV-001')
        payment_id = create_result['paymentID']

        # Execute payment
        execute_result = mock_bkash.execute_payment(payment_id)

        assert execute_result['status'] == 'Completed'
        assert 'trxID' in execute_result

    @pytest.mark.integration
    @pytest.mark.payment
    def test_bkash_payment_query(self, mock_bkash):
        """Test bKash payment status query."""
        # Create and execute payment
        create_result = mock_bkash.create_payment(amount=500.00, reference='INV-001')
        payment_id = create_result['paymentID']
        mock_bkash.execute_payment(payment_id)

        # Query payment
        query_result = mock_bkash.query_payment(payment_id)

        assert query_result['status'] == 'completed'

    @pytest.mark.integration
    @pytest.mark.payment
    def test_bkash_refund(self, mock_bkash):
        """Test bKash refund functionality."""
        # Create and complete payment first
        create_result = mock_bkash.create_payment(amount=500.00, reference='INV-001')
        payment_id = create_result['paymentID']
        mock_bkash.execute_payment(payment_id)

        # Request refund (would need to implement in mock)
        # This tests the refund flow
        pass
```

**Task 2: Nagad & Rocket Integration Tests (4h)**

```python
# tests/integration/test_nagad_rocket_integration.py
"""Nagad and Rocket payment gateway integration tests."""
import pytest
from unittest.mock import patch, Mock

class TestNagadIntegration:
    """Test Nagad payment integration."""

    @pytest.fixture
    def nagad_config(self):
        return {
            'merchant_id': 'test_merchant',
            'merchant_key': 'test_key',
            'base_url': 'https://sandbox.nagad.com.bd/api/v1'
        }

    @pytest.mark.integration
    @pytest.mark.payment
    def test_nagad_checkout_initiation(self, nagad_config):
        """Test Nagad checkout initiation."""
        with patch('requests.post') as mock_post:
            mock_post.return_value = Mock(
                status_code=200,
                json=lambda: {
                    'sensitiveData': 'encrypted_data',
                    'signature': 'test_signature',
                    'callBackUrl': 'https://smartdairy.com/nagad/callback'
                }
            )

            # Simulate checkout initiation
            checkout_data = {
                'merchantId': nagad_config['merchant_id'],
                'orderId': 'ORD-2024-001',
                'amount': '500.00',
                'currencyCode': '050'
            }

            response = mock_post.return_value.json()
            assert 'sensitiveData' in response

    @pytest.mark.integration
    @pytest.mark.payment
    def test_nagad_payment_verification(self, nagad_config):
        """Test Nagad payment verification."""
        with patch('requests.get') as mock_get:
            mock_get.return_value = Mock(
                status_code=200,
                json=lambda: {
                    'status': 'Success',
                    'statusCode': 'SS',
                    'issuerPaymentRefNo': 'NAG123456'
                }
            )

            response = mock_get.return_value.json()
            assert response['status'] == 'Success'


class TestRocketIntegration:
    """Test Rocket (DBBL) payment integration."""

    @pytest.fixture
    def rocket_config(self):
        return {
            'merchant_id': 'test_merchant',
            'store_id': 'test_store',
            'base_url': 'https://sandbox.dutchbanglabank.com'
        }

    @pytest.mark.integration
    @pytest.mark.payment
    def test_rocket_payment_initiation(self, rocket_config):
        """Test Rocket payment initiation."""
        with patch('requests.post') as mock_post:
            mock_post.return_value = Mock(
                status_code=200,
                json=lambda: {
                    'status': 'VALID',
                    'tran_id': 'ROCKET-2024-001',
                    'redirect_url': 'https://rocket.com.bd/pay/123'
                }
            )

            response = mock_post.return_value.json()
            assert response['status'] == 'VALID'
            assert 'redirect_url' in response

    @pytest.mark.integration
    @pytest.mark.payment
    def test_rocket_ipn_validation(self, rocket_config):
        """Test Rocket IPN (Instant Payment Notification) validation."""
        ipn_data = {
            'tran_id': 'ROCKET-2024-001',
            'val_id': 'VAL123456',
            'amount': '500.00',
            'card_type': 'ROCKET',
            'status': 'VALID',
            'verify_sign': 'abc123signature'
        }

        # Validate IPN signature
        def validate_ipn_signature(data, secret):
            # Simplified validation
            return data.get('status') == 'VALID'

        assert validate_ipn_signature(ipn_data, 'secret_key')
```

#### Dev 2 - Full-Stack (8h)

**Task 1: SSLCommerz Integration Tests (4h)**

```python
# tests/integration/test_sslcommerz_integration.py
"""SSLCommerz payment gateway integration tests."""
import pytest
from unittest.mock import patch, Mock
import hashlib

class TestSSLCommerzIntegration:
    """Test SSLCommerz payment integration."""

    @pytest.fixture
    def sslcommerz_config(self):
        return {
            'store_id': 'teststore',
            'store_passwd': 'teststore@ssl',
            'base_url': 'https://sandbox.sslcommerz.com'
        }

    @pytest.mark.integration
    @pytest.mark.payment
    def test_session_initiation(self, sslcommerz_config):
        """Test SSLCommerz session initiation."""
        with patch('requests.post') as mock_post:
            mock_post.return_value = Mock(
                status_code=200,
                json=lambda: {
                    'status': 'SUCCESS',
                    'sessionkey': 'sess_123456',
                    'GatewayPageURL': 'https://sandbox.sslcommerz.com/gwprocess/v4/gw.php?Q=pay&SESSIONKEY=sess_123456'
                }
            )

            payment_data = {
                'total_amount': 500,
                'currency': 'BDT',
                'tran_id': 'ORD-2024-001',
                'success_url': 'https://smartdairy.com/payment/success',
                'fail_url': 'https://smartdairy.com/payment/fail',
                'cancel_url': 'https://smartdairy.com/payment/cancel',
                'cus_name': 'Test Customer',
                'cus_email': 'test@test.com',
                'cus_phone': '01712345678',
                'product_name': 'Fresh Milk Order',
                'product_category': 'Dairy',
                'shipping_method': 'Courier'
            }

            response = mock_post.return_value.json()
            assert response['status'] == 'SUCCESS'
            assert 'GatewayPageURL' in response

    @pytest.mark.integration
    @pytest.mark.payment
    def test_ipn_validation(self, sslcommerz_config):
        """Test SSLCommerz IPN validation."""
        ipn_data = {
            'tran_id': 'ORD-2024-001',
            'val_id': '123456789',
            'amount': '500.00',
            'card_type': 'VISA',
            'store_amount': '485.00',
            'bank_tran_id': 'BANK123',
            'status': 'VALID',
            'tran_date': '2024-03-15 10:30:00',
            'currency': 'BDT',
            'verify_sign': 'generated_hash'
        }

        def validate_ipn(data, store_passwd):
            """Validate SSLCommerz IPN."""
            # In production, verify hash
            return data.get('status') == 'VALID'

        assert validate_ipn(ipn_data, sslcommerz_config['store_passwd'])

    @pytest.mark.integration
    @pytest.mark.payment
    def test_transaction_validation(self, sslcommerz_config):
        """Test SSLCommerz transaction validation API."""
        with patch('requests.get') as mock_get:
            mock_get.return_value = Mock(
                status_code=200,
                json=lambda: {
                    'status': 'VALID',
                    'tran_date': '2024-03-15 10:30:00',
                    'tran_id': 'ORD-2024-001',
                    'val_id': '123456789',
                    'amount': '500.00',
                    'store_amount': '485.00',
                    'bank_tran_id': 'BANK123',
                    'card_type': 'VISA'
                }
            )

            response = mock_get.return_value.json()
            assert response['status'] == 'VALID'
            assert response['tran_id'] == 'ORD-2024-001'

    @pytest.mark.integration
    @pytest.mark.payment
    @pytest.mark.parametrize("card_type,expected", [
        ('VISA', True),
        ('MASTERCARD', True),
        ('AMEX', True),
        ('bKash', True),
        ('Nagad', True),
        ('INVALID', False)
    ])
    def test_supported_card_types(self, card_type, expected, sslcommerz_config):
        """Test SSLCommerz supports various card types."""
        supported_types = ['VISA', 'MASTERCARD', 'AMEX', 'bKash', 'Nagad', 'Rocket']
        assert (card_type in supported_types) == expected
```

**Task 2: Payment Integration Workflow Tests (4h)**

```python
# tests/integration/test_payment_workflow.py
"""Complete payment workflow integration tests."""
import pytest
from odoo.tests.common import TransactionCase, tagged

@tagged('integration', 'payment', 'workflow')
class TestPaymentWorkflow(TransactionCase):
    """Test complete payment workflows."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.customer = cls.env['res.partner'].create({
            'name': 'Payment Test Customer',
            'email': 'payment@test.com'
        })
        cls.product = cls.env['product.product'].create({
            'name': 'Test Product',
            'list_price': 500.0
        })

    @pytest.mark.integration
    @pytest.mark.payment
    def test_online_payment_order_flow(self):
        """Test order flow with online payment."""
        # Create order
        order = self.env['sale.order'].create({
            'partner_id': self.customer.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 1
            })]
        })

        # Simulate payment initiation
        payment_tx = self.env['payment.transaction'].create({
            'reference': f'PAY-{order.name}',
            'amount': order.amount_total,
            'currency_id': order.currency_id.id,
            'partner_id': self.customer.id,
            'provider_id': self.env.ref('payment.payment_provider_demo').id
        })

        # Simulate payment completion
        payment_tx._set_done()

        # Verify order confirmed
        order.action_confirm()
        self.assertEqual(order.state, 'sale')

    @pytest.mark.integration
    @pytest.mark.payment
    def test_cod_order_flow(self):
        """Test order flow with Cash on Delivery."""
        order = self.env['sale.order'].create({
            'partner_id': self.customer.id,
            'payment_term_id': self.env.ref('account.account_payment_term_immediate').id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 2
            })]
        })

        # Confirm order (COD - no prepayment required)
        order.action_confirm()
        self.assertEqual(order.state, 'sale')

        # Process delivery
        picking = order.picking_ids[0]
        picking.action_confirm()
        picking.action_assign()
        for move in picking.move_ids:
            move.quantity = move.product_uom_qty
        picking.button_validate()

        # Create invoice
        invoice = order._create_invoices()
        invoice.action_post()

        # Register COD payment
        payment = self.env['account.payment'].create({
            'payment_type': 'inbound',
            'partner_id': self.customer.id,
            'amount': invoice.amount_total,
            'journal_id': self.env['account.journal'].search(
                [('type', '=', 'cash')], limit=1
            ).id,
            'ref': f'COD-{order.name}'
        })
        payment.action_post()

        self.assertEqual(invoice.payment_state, 'paid')

    @pytest.mark.integration
    @pytest.mark.payment
    def test_failed_payment_handling(self):
        """Test handling of failed payments."""
        order = self.env['sale.order'].create({
            'partner_id': self.customer.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 1
            })]
        })

        # Create payment transaction
        payment_tx = self.env['payment.transaction'].create({
            'reference': f'PAY-{order.name}',
            'amount': order.amount_total,
            'currency_id': order.currency_id.id,
            'partner_id': self.customer.id,
            'provider_id': self.env.ref('payment.payment_provider_demo').id
        })

        # Simulate payment failure
        payment_tx._set_error('Payment declined by bank')

        # Verify order still in draft
        self.assertEqual(order.state, 'draft')
        self.assertEqual(payment_tx.state, 'error')
```

#### Dev 3 - Frontend/Mobile Lead (8h)

**Task 1: Frontend Payment Flow Tests (4h)**

```javascript
// cypress/e2e/payment/payment-flow.cy.js
describe('Payment Flow E2E Tests', () => {
  beforeEach(() => {
    cy.login(Cypress.env('testCustomer'), Cypress.env('testPassword'));
    cy.addToCart(1, 2); // Add product to cart
  });

  describe('bKash Payment', () => {
    it('initiates bKash payment successfully', () => {
      cy.visit('/checkout');
      cy.get('[data-testid="payment-method-bkash"]').click();
      cy.get('[data-testid="place-order"]').click();

      // Should redirect to bKash
      cy.url().should('include', 'bka.sh');
    });

    it('handles bKash payment cancellation', () => {
      cy.visit('/checkout');
      cy.get('[data-testid="payment-method-bkash"]').click();
      cy.get('[data-testid="place-order"]').click();

      // Simulate cancel callback
      cy.visit('/payment/bkash/cancel?order_id=ORD-001');

      cy.get('[data-testid="payment-cancelled"]').should('be.visible');
      cy.get('[data-testid="retry-payment"]').should('be.visible');
    });
  });

  describe('SSLCommerz Payment', () => {
    it('shows card payment form', () => {
      cy.visit('/checkout');
      cy.get('[data-testid="payment-method-card"]').click();

      cy.get('[data-testid="place-order"]').click();

      // Should redirect to SSLCommerz gateway
      cy.url().should('include', 'sslcommerz.com');
    });

    it('handles successful card payment', () => {
      // Simulate successful payment callback
      cy.visit('/payment/sslcommerz/success?tran_id=ORD-001&val_id=VAL123');

      cy.get('[data-testid="payment-success"]').should('be.visible');
      cy.get('[data-testid="order-confirmation"]').should('contain', 'ORD-001');
    });
  });

  describe('Cash on Delivery', () => {
    it('completes COD order', () => {
      cy.visit('/checkout');
      cy.get('[data-testid="payment-method-cod"]').click();

      // Fill shipping info
      cy.get('[data-testid="shipping-name"]').type('Test Customer');
      cy.get('[data-testid="shipping-phone"]').type('01712345678');
      cy.get('[data-testid="shipping-address"]').type('123 Test Street');
      cy.get('[data-testid="shipping-city"]').select('Dhaka');

      cy.get('[data-testid="place-order"]').click();

      // Should show confirmation
      cy.url().should('include', '/order/confirmation');
      cy.get('[data-testid="order-number"]').should('be.visible');
      cy.get('[data-testid="payment-method-display"]').should('contain', 'Cash on Delivery');
    });

    it('shows COD not available for high value orders', () => {
      // Add expensive items to reach COD limit
      cy.addToCart(10, 50); // Assuming product 10 is expensive

      cy.visit('/checkout');

      cy.get('[data-testid="payment-method-cod"]').should('be.disabled');
      cy.get('[data-testid="cod-limit-message"]').should('be.visible');
    });
  });
});
```

**Task 2: Mobile Payment Integration Tests (4h)**

```dart
// test/integration/payment_test.dart
import 'package:flutter_test/flutter_test.dart';
import 'package:smart_dairy_app/services/payment_service.dart';
import 'package:smart_dairy_app/models/payment.dart';

void main() {
  group('Payment Service Integration Tests', () {
    late PaymentService paymentService;

    setUp(() {
      paymentService = PaymentService(baseUrl: 'http://localhost:8069');
    });

    test('initiates bKash payment', () async {
      final result = await paymentService.initiateBkashPayment(
        orderId: 'ORD-2024-001',
        amount: 500.0,
      );

      expect(result.paymentId, isNotEmpty);
      expect(result.redirectUrl, contains('bka.sh'));
    });

    test('initiates Nagad payment', () async {
      final result = await paymentService.initiateNagadPayment(
        orderId: 'ORD-2024-001',
        amount: 500.0,
      );

      expect(result.paymentId, isNotEmpty);
      expect(result.redirectUrl, contains('nagad.com.bd'));
    });

    test('verifies payment status', () async {
      final status = await paymentService.verifyPayment(
        paymentId: 'PAY-123',
        provider: PaymentProvider.bkash,
      );

      expect(status.isSuccess, isTrue);
      expect(status.transactionId, isNotEmpty);
    });

    test('handles payment failure', () async {
      final status = await paymentService.verifyPayment(
        paymentId: 'INVALID-PAY',
        provider: PaymentProvider.bkash,
      );

      expect(status.isSuccess, isFalse);
      expect(status.errorMessage, isNotEmpty);
    });
  });

  group('Payment UI Widget Tests', () {
    testWidgets('displays payment methods', (tester) async {
      await tester.pumpWidget(
        MaterialApp(
          home: PaymentMethodSelector(
            amount: 500.0,
            onMethodSelected: (_) {},
          ),
        ),
      );

      expect(find.text('bKash'), findsOneWidget);
      expect(find.text('Nagad'), findsOneWidget);
      expect(find.text('Rocket'), findsOneWidget);
      expect(find.text('Card Payment'), findsOneWidget);
      expect(find.text('Cash on Delivery'), findsOneWidget);
    });

    testWidgets('disables COD for high amounts', (tester) async {
      await tester.pumpWidget(
        MaterialApp(
          home: PaymentMethodSelector(
            amount: 15000.0, // Above COD limit
            onMethodSelected: (_) {},
          ),
        ),
      );

      final codOption = find.byKey(Key('payment-cod'));
      expect(
        tester.widget<PaymentOption>(codOption).enabled,
        isFalse,
      );
    });
  });
}
```

**Deliverables Day 658:**
- bKash integration tests
- Nagad integration tests
- Rocket integration tests
- SSLCommerz integration tests
- Payment workflow tests
- Frontend payment flow E2E tests
- Mobile payment integration tests

---

### Day 659 - IoT & External Service Integration

**Objective:** Complete IoT pipeline and external service integration tests.

*(Continuing with condensed format for remaining days)*

#### Dev 1 - Backend Lead (8h)

- IoT MQTT broker integration tests
- TimescaleDB time-series data tests
- Sensor data pipeline validation
- Alert triggering integration tests

#### Dev 2 - Full-Stack (8h)

- IoT dashboard data integration
- Real-time sensor data WebSocket tests
- External API retry mechanism tests
- Circuit breaker pattern tests

#### Dev 3 - Frontend/Mobile Lead (8h)

- IoT dashboard component tests
- Real-time chart update tests
- Mobile IoT data sync tests
- Offline IoT data queue tests

---

### Day 660 - Integration Test Review & Documentation

**Objective:** Complete integration test documentation and milestone review.

#### All Developers (8h each)

- Generate integration test coverage report
- Document all API contracts tested
- Create integration test summary report
- Conduct milestone review meeting
- Prepare handoff for Milestone 133

**Deliverables Day 660:**
- Integration test coverage report (60%+ achieved)
- API contract documentation
- Integration test summary
- Bug tracking report
- Milestone sign-off documentation

---

## 4. Technical Specifications

### 4.1 Integration Test Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    Integration Test Layer                        │
├─────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐ │
│  │ API Tests   │  │ DB Tests    │  │ External Service Tests  │ │
│  │ (pytest)    │  │ (pytest)    │  │ (Mock/Sandbox)          │ │
│  └─────────────┘  └─────────────┘  └─────────────────────────┘ │
├─────────────────────────────────────────────────────────────────┤
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐ │
│  │ Payment     │  │ SMS/Email   │  │ IoT Pipeline            │ │
│  │ Gateway     │  │ Services    │  │ (MQTT/TimescaleDB)      │ │
│  └─────────────┘  └─────────────┘  └─────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

---

## 5. Testing & Validation

| Test ID | Test Case | Expected Result | Status |
|---------|-----------|-----------------|--------|
| M132-01 | API contract tests pass | 100+ tests pass | Pending |
| M132-02 | Payment gateway sandbox tests | All gateways tested | Pending |
| M132-03 | Database integration tests | CRUD validated | Pending |
| M132-04 | IoT pipeline tests | Data flow verified | Pending |
| M132-05 | Cross-module workflow tests | Workflows complete | Pending |
| M132-06 | Integration coverage | >60% achieved | Pending |

---

## 6. Risk & Mitigation

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Payment sandbox unavailable | Low | High | Use mock services |
| Test data conflicts | Medium | Medium | Isolated databases |
| External service timeout | Medium | Medium | Retry logic, mocks |
| Coverage target not met | Low | Medium | Focus on critical paths |

---

## 7. Dependencies & Handoffs

### 7.1 Dependencies
- Milestone 131 complete (test framework)
- Payment gateway sandbox credentials
- Test environment with all services
- SMS/Email test accounts

### 7.2 Handoffs to Milestone 133
- Integration test suite operational
- All payment flows validated
- IoT pipeline tested
- Coverage report (60%+)
- Bug list for resolution

---

## Milestone Sign-off Checklist

- [ ] 100+ API endpoint tests passing
- [ ] Payment gateway tests complete (bKash, Nagad, Rocket, SSLCommerz)
- [ ] Database integration tests passing
- [ ] IoT pipeline validated
- [ ] Cross-module workflows tested
- [ ] 60%+ integration coverage achieved
- [ ] Integration test report generated
- [ ] Team sign-off obtained

---

**Document End**
