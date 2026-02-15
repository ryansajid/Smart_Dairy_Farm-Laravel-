# Milestone 70: E-commerce Testing & UAT

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P7-M70-v1.0 |
| **Milestone** | 70 - E-commerce Testing & UAT |
| **Phase** | Phase 7: B2C E-Commerce Core |
| **Days** | 441-450 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Execute comprehensive quality assurance including unit tests (>80% coverage), integration tests, end-to-end tests for critical user flows, performance testing, security audit (OWASP Top 10), stakeholder UAT, and final documentation for Phase 7 completion.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Unit Tests | Model, service, API tests |
| Integration Tests | Cross-module functionality |
| E2E Tests | Critical user journeys |
| Performance Tests | Load and stress testing |
| Security Audit | OWASP Top 10 compliance |
| UAT | Stakeholder acceptance |
| Bug Fixing | Critical and high priority |
| Documentation | API docs, user guides |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Unit test coverage | > 80% coverage |
| O2 | Integration tests | 100% API coverage |
| O3 | E2E tests | All critical flows pass |
| O4 | Performance | < 2s page load, < 300ms API |
| O5 | Security audit | 0 critical vulnerabilities |
| O6 | UAT completion | 100% acceptance criteria |
| O7 | Bug resolution | 0 critical, < 5 high bugs |
| O8 | Documentation | 100% API documented |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Due Day |
|---|-------------|-------|---------|
| D1 | Unit test suite | Dev 1 | Day 443 |
| D2 | Integration test suite | Dev 2 | Day 444 |
| D3 | E2E test scenarios | Dev 3 | Day 445 |
| D4 | Performance test results | Dev 2 | Day 446 |
| D5 | Security audit report | Dev 1 | Day 447 |
| D6 | UAT test cases | All | Day 444 |
| D7 | UAT execution | All | Day 448 |
| D8 | Bug fixes | All | Day 449 |
| D9 | Final documentation | All | Day 450 |
| D10 | Phase 7 sign-off | All | Day 450 |

---

## 4. Requirement Traceability

### 4.1 Testing Requirements

| Req ID | Description | Deliverable |
|--------|-------------|-------------|
| TEST-001 | Unit test coverage >80% | D1 |
| TEST-002 | Integration test all APIs | D2 |
| TEST-003 | E2E critical user flows | D3 |
| TEST-004 | Performance benchmarks | D4 |
| TEST-005 | Security audit OWASP | D5 |
| TEST-006 | UAT with stakeholders | D6, D7 |

---

## 5. Day-by-Day Development Plan

### Day 441-443: Unit Testing

#### Dev 1 (Backend Lead)

**Test Suite for Checkout**

```python
# File: smart_dairy_ecommerce/tests/test_checkout.py

from odoo.tests.common import TransactionCase, tagged
from odoo.exceptions import ValidationError

@tagged('post_install', '-at_install')
class TestCheckout(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.partner = cls.env['res.partner'].create({
            'name': 'Test Customer',
            'email': 'test@example.com',
            'is_b2c_customer': True,
        })
        cls.product = cls.env['product.product'].create({
            'name': 'Test Product',
            'type': 'product',
            'list_price': 100.00,
            'qty_available': 50,
        })

    def test_cart_add_item(self):
        """Test adding item to cart"""
        cart = self.env['cart.session'].get_or_create_session(
            partner_id=self.partner.id
        )
        result = cart.add_item(self.product.id, 2)

        self.assertIn('items', result)
        self.assertEqual(result['item_count'], 2)
        self.assertEqual(result['subtotal'], 200.00)

    def test_cart_add_item_insufficient_stock(self):
        """Test adding item exceeding stock"""
        cart = self.env['cart.session'].get_or_create_session(
            partner_id=self.partner.id
        )
        result = cart.add_item(self.product.id, 100)

        self.assertIn('error', result)
        self.assertIn('Insufficient stock', result['error'])

    def test_cart_update_quantity(self):
        """Test updating cart item quantity"""
        cart = self.env['cart.session'].get_or_create_session(
            partner_id=self.partner.id
        )
        cart.add_item(self.product.id, 2)
        result = cart.update_item_quantity(self.product.id, 5)

        self.assertEqual(result['item_count'], 5)

    def test_cart_remove_item(self):
        """Test removing item from cart"""
        cart = self.env['cart.session'].get_or_create_session(
            partner_id=self.partner.id
        )
        cart.add_item(self.product.id, 2)
        result = cart.remove_item(self.product.id)

        self.assertEqual(result['item_count'], 0)

    def test_promo_code_validation(self):
        """Test promo code validation"""
        promo = self.env['promo.code'].create({
            'name': 'TEST10',
            'discount_type': 'percent',
            'discount_value': 10,
            'min_order_amount': 500,
        })

        # Should fail - order too small
        result = promo.validate_code(self.partner.id, 100)
        self.assertFalse(result['valid'])

        # Should pass
        result = promo.validate_code(self.partner.id, 600)
        self.assertTrue(result['valid'])

    def test_promo_code_discount_calculation(self):
        """Test promo code discount calculation"""
        promo = self.env['promo.code'].create({
            'name': 'TEST20',
            'discount_type': 'percent',
            'discount_value': 20,
            'max_discount': 100,
        })

        # 20% of 400 = 80
        discount = promo.calculate_discount(400)
        self.assertEqual(discount, 80)

        # 20% of 1000 = 200, but max is 100
        discount = promo.calculate_discount(1000)
        self.assertEqual(discount, 100)


@tagged('post_install', '-at_install')
class TestLoyalty(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.partner = cls.env['res.partner'].create({
            'name': 'Loyalty Customer',
            'email': 'loyalty@example.com',
            'is_b2c_customer': True,
        })

    def test_points_earning(self):
        """Test loyalty points earning"""
        self.env['loyalty.transaction'].create({
            'partner_id': self.partner.id,
            'transaction_type': 'earn',
            'points': 100,
            'description': 'Test earning',
        })

        self.assertEqual(self.partner.loyalty_points, 100)
        self.assertEqual(self.partner.lifetime_points, 100)

    def test_points_redemption(self):
        """Test loyalty points redemption"""
        self.partner.loyalty_points = 500

        service = self.env['loyalty.points.service']
        result = service.redeem_points(self.partner.id, 200)

        self.assertTrue(result.get('success'))
        self.assertEqual(self.partner.loyalty_points, 300)

    def test_tier_calculation(self):
        """Test loyalty tier assignment"""
        # Create tiers
        self.env['loyalty.tier'].create({
            'name': 'Gold',
            'code': 'GOLD',
            'min_points': 5000,
        })

        self.partner.lifetime_points = 6000
        self.partner._compute_loyalty_tier()

        self.assertEqual(self.partner.loyalty_tier_id.code, 'GOLD')
```

**Test Suite for Reviews**

```python
# File: smart_dairy_ecommerce/tests/test_reviews.py

from odoo.tests.common import TransactionCase, tagged
from odoo.exceptions import ValidationError

@tagged('post_install', '-at_install')
class TestProductReviews(TransactionCase):

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.product = cls.env['product.product'].create({
            'name': 'Review Test Product',
            'list_price': 50.00,
        })
        cls.partner = cls.env['res.partner'].create({
            'name': 'Reviewer',
            'email': 'reviewer@example.com',
        })

    def test_review_creation(self):
        """Test creating a product review"""
        review = self.env['product.review'].create({
            'product_id': self.product.id,
            'partner_id': self.partner.id,
            'rating': 5,
            'title': 'Great product!',
            'content': 'Really enjoyed this product.',
        })

        self.assertEqual(review.state, 'pending')
        self.assertEqual(review.rating, 5)

    def test_review_rating_validation(self):
        """Test rating must be 1-5"""
        with self.assertRaises(ValidationError):
            self.env['product.review'].create({
                'product_id': self.product.id,
                'partner_id': self.partner.id,
                'rating': 6,
            })

    def test_review_approval(self):
        """Test review approval workflow"""
        review = self.env['product.review'].create({
            'product_id': self.product.id,
            'partner_id': self.partner.id,
            'rating': 4,
        })

        review.action_approve()

        self.assertEqual(review.state, 'approved')
        self.assertTrue(review.moderated_at)

    def test_average_rating_calculation(self):
        """Test product average rating"""
        for rating in [5, 4, 4, 5, 3]:
            review = self.env['product.review'].create({
                'product_id': self.product.id,
                'partner_id': self.partner.id,
                'rating': rating,
            })
            review.action_approve()

        self.product._compute_review_stats()

        self.assertEqual(self.product.review_count, 5)
        self.assertAlmostEqual(self.product.average_rating, 4.2, places=1)
```

#### Dev 2 (Full-Stack)

**Integration Tests**

```python
# File: smart_dairy_ecommerce/tests/test_integration.py

from odoo.tests.common import HttpCase, tagged

@tagged('post_install', '-at_install')
class TestAPIIntegration(HttpCase):

    def test_cart_api_flow(self):
        """Test complete cart API flow"""
        # Add to cart
        response = self.url_open(
            '/api/v1/cart/add',
            data={'product_id': 1, 'quantity': 2},
            headers={'Content-Type': 'application/json'}
        )
        self.assertEqual(response.status_code, 200)

        # Get cart
        response = self.url_open('/api/v1/cart')
        self.assertEqual(response.status_code, 200)
        data = response.json()
        self.assertTrue(data.get('success'))

    def test_checkout_api_flow(self):
        """Test checkout initialization"""
        self.authenticate('demo', 'demo')

        response = self.url_open(
            '/api/v1/checkout/init',
            headers={'Content-Type': 'application/json'}
        )
        self.assertEqual(response.status_code, 200)

    def test_search_api(self):
        """Test product search API"""
        response = self.url_open(
            '/shop/api/search',
            data={'query': 'milk'},
            headers={'Content-Type': 'application/json'}
        )
        self.assertEqual(response.status_code, 200)
```

#### Dev 3 (Frontend Lead)

**E2E Test Scenarios**

```javascript
// File: smart_dairy_ecommerce/tests/e2e/checkout.spec.js
// Using Playwright or Cypress

describe('Checkout Flow', () => {
    beforeEach(() => {
        cy.visit('/shop');
    });

    it('should complete guest checkout', () => {
        // Add product to cart
        cy.get('[data-product-id="1"]').click();
        cy.get('.add-to-cart-btn').click();
        cy.get('.sd-cart-badge').should('contain', '1');

        // Go to checkout
        cy.get('.sd-cart-toggle').click();
        cy.get('.checkout-btn').click();

        // Fill shipping info
        cy.get('#guest_email').type('test@example.com');
        cy.get('#recipient_name').type('Test User');
        cy.get('#phone').type('01712345678');
        cy.get('#street').type('123 Test Street');
        cy.get('#city').type('Dhaka');
        cy.get('#division').select('dhaka');

        // Select delivery slot
        cy.get('.delivery-slot').first().click();
        cy.get('.next-step-btn').click();

        // Select payment
        cy.get('[data-payment="cod"]').click();
        cy.get('.next-step-btn').click();

        // Confirm order
        cy.get('.place-order-btn').click();

        // Verify confirmation
        cy.url().should('include', '/shop/confirmation');
        cy.get('.order-number').should('be.visible');
    });

    it('should apply promo code', () => {
        cy.addProductToCart(1);
        cy.goToCheckout();

        cy.get('#promo_code').type('WELCOME10');
        cy.get('.apply-promo-btn').click();

        cy.get('.promo-discount').should('be.visible');
        cy.get('.total-amount').should('contain', 'discount applied');
    });
});

describe('User Account', () => {
    it('should display order history', () => {
        cy.login('customer@example.com', 'password');
        cy.visit('/my/orders');

        cy.get('.order-card').should('have.length.greaterThan', 0);
    });

    it('should track order status', () => {
        cy.login('customer@example.com', 'password');
        cy.visit('/my/orders/1');

        cy.get('.tracking-timeline').should('be.visible');
        cy.get('.current-status').should('be.visible');
    });
});
```

---

### Day 444-446: Performance & Security Testing

#### Performance Testing with Locust

```python
# File: smart_dairy_ecommerce/tests/performance/locust_test.py

from locust import HttpUser, task, between

class ShopUser(HttpUser):
    wait_time = between(1, 3)

    @task(3)
    def browse_products(self):
        self.client.get('/shop')

    @task(2)
    def search_products(self):
        self.client.post('/shop/api/search', json={
            'query': 'milk'
        })

    @task(2)
    def view_product(self):
        self.client.get('/shop/product/1')

    @task(1)
    def add_to_cart(self):
        self.client.post('/api/v1/cart/add', json={
            'product_id': 1,
            'quantity': 1
        })

    @task(1)
    def view_cart(self):
        self.client.get('/api/v1/cart')


class CheckoutUser(HttpUser):
    wait_time = between(2, 5)

    def on_start(self):
        # Login
        self.client.post('/web/login', {
            'login': 'demo@example.com',
            'password': 'demo'
        })

    @task
    def checkout_flow(self):
        # Add to cart
        self.client.post('/api/v1/cart/add', json={
            'product_id': 1,
            'quantity': 2
        })

        # Init checkout
        self.client.post('/api/v1/checkout/init')

        # Update shipping
        self.client.post('/api/v1/checkout/update', json={
            'step': 'shipping',
            'data': {
                'address': {'city': 'Dhaka'}
            }
        })
```

#### Security Audit Checklist

```markdown
# OWASP Top 10 Security Checklist

## A01:2021 - Broken Access Control
- [ ] Verify user can only access own orders
- [ ] Test admin routes require authentication
- [ ] Verify CORS configuration
- [ ] Test horizontal privilege escalation

## A02:2021 - Cryptographic Failures
- [ ] Verify HTTPS enforced
- [ ] Check password hashing (bcrypt/argon2)
- [ ] Verify sensitive data encrypted at rest
- [ ] Check API keys not exposed

## A03:2021 - Injection
- [ ] Test SQL injection on search
- [ ] Test XSS on user input fields
- [ ] Test command injection
- [ ] Verify parameterized queries

## A04:2021 - Insecure Design
- [ ] Review authentication flow
- [ ] Verify rate limiting on login
- [ ] Check account lockout policy
- [ ] Review session management

## A05:2021 - Security Misconfiguration
- [ ] Verify debug mode disabled
- [ ] Check error messages don't leak info
- [ ] Review HTTP security headers
- [ ] Verify default credentials changed

## A06:2021 - Vulnerable Components
- [ ] Run pip audit for Python deps
- [ ] Run npm audit for JS deps
- [ ] Check Odoo security patches
- [ ] Review third-party libraries

## A07:2021 - Authentication Failures
- [ ] Test password strength requirements
- [ ] Verify 2FA implementation
- [ ] Test session timeout
- [ ] Check remember me functionality

## A08:2021 - Software and Data Integrity
- [ ] Verify CSRF protection
- [ ] Check content integrity
- [ ] Review update mechanisms

## A09:2021 - Security Logging
- [ ] Verify login attempts logged
- [ ] Check failed auth logged
- [ ] Review audit trail completeness

## A10:2021 - Server-Side Request Forgery
- [ ] Test URL validation
- [ ] Verify internal network protection
```

---

### Day 447-450: UAT, Bug Fixes & Documentation

#### UAT Test Cases

| TC# | Test Case | Steps | Expected Result | Status |
|-----|-----------|-------|-----------------|--------|
| TC-001 | Guest Checkout | Add product, checkout as guest | Order created | |
| TC-002 | Registered Checkout | Login, checkout with saved address | Order with loyalty points | |
| TC-003 | Promo Code | Apply valid promo at checkout | Discount applied | |
| TC-004 | Order Tracking | View order, check tracking | Timeline displays | |
| TC-005 | Cancel Order | Request cancellation | Refund initiated | |
| TC-006 | Return Request | Create return request | RMA generated | |
| TC-007 | Product Review | Submit review with photo | Review in moderation | |
| TC-008 | Wishlist | Add to wishlist, share | Wishlist accessible | |
| TC-009 | Live Chat | Start chat, ask question | Bot responds | |
| TC-010 | Newsletter | Subscribe, confirm email | Subscription active | |

---

## 6. Test Coverage Summary

| Module | Unit Tests | Coverage |
|--------|-----------|----------|
| Checkout | 25 tests | 85% |
| Loyalty | 18 tests | 90% |
| Reviews | 15 tests | 82% |
| Orders | 22 tests | 88% |
| Cart | 12 tests | 91% |
| Email | 10 tests | 78% |
| Chat | 8 tests | 75% |

---

## 7. Performance Benchmarks

| Metric | Target | Achieved |
|--------|--------|----------|
| Homepage load | < 2s | |
| Search response | < 200ms | |
| Add to cart | < 500ms | |
| Checkout init | < 1s | |
| Order placement | < 3s | |
| API (p95) | < 300ms | |

---

## 8. Sign-off Checklist

### Quality Gates

- [ ] Unit test coverage > 80%
- [ ] All integration tests pass
- [ ] All E2E tests pass
- [ ] Performance targets met
- [ ] Security audit passed
- [ ] UAT completed with sign-off
- [ ] 0 critical bugs remaining
- [ ] < 5 high priority bugs remaining
- [ ] Documentation complete

### Phase 7 Exit Criteria

- [ ] All milestones (61-70) delivered
- [ ] Product catalog with search operational
- [ ] Customer accounts functional
- [ ] Shopping cart & checkout complete
- [ ] Order management operational
- [ ] Reviews & ratings active
- [ ] Email marketing configured
- [ ] Customer support chat live
- [ ] All tests passing
- [ ] Stakeholder approval obtained

---

## 9. Transition to Phase 8

### Handoff Items

1. **Technical Documentation**
   - API documentation
   - Database schema
   - Integration guides

2. **Operations Documentation**
   - Deployment procedures
   - Monitoring setup
   - Incident response

3. **Training Materials**
   - Admin user guide
   - Agent training guide
   - Customer FAQ

4. **Known Issues**
   - Bug backlog
   - Technical debt items
   - Enhancement requests

---

**Document End**

*Last Updated: 2025-01-15*
*Version: 1.0*
