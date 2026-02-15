# Milestone 80: Payment & Logistics Testing

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P8-M80-v1.0 |
| **Milestone** | 80 - Payment & Logistics Testing |
| **Phase** | Phase 8: Operations - Payment & Logistics |
| **Days** | 541-550 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Conduct comprehensive quality assurance testing for all Phase 8 payment and logistics features including unit tests, integration tests, end-to-end checkout-to-delivery tests, payment security audit, load testing, UAT with stakeholders, bug fixing, performance optimization, and documentation finalization.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| Unit Tests | All payment and logistics services |
| Integration Tests | Cross-module workflows |
| E2E Tests | Complete checkout to delivery |
| Security Audit | PCI DSS, OWASP checks |
| Load Testing | Payment API performance |
| UAT | Stakeholder acceptance |
| Bug Fixing | Critical and major issues |
| Documentation | API docs, user guides |

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Unit test coverage | > 80% |
| O2 | Integration test pass rate | 100% |
| O3 | E2E test scenarios | 20+ scenarios |
| O4 | Critical bugs | 0 at milestone end |
| O5 | Security vulnerabilities | 0 critical/high |
| O6 | API response time (p95) | < 2 seconds |
| O7 | UAT sign-off | All stakeholders |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Payment unit tests | Dev 1 | Python | Day 543 |
| D2 | Logistics unit tests | Dev 1 | Python | Day 544 |
| D3 | Integration test suite | Dev 2 | Python | Day 545 |
| D4 | E2E test scenarios | Dev 2 | Python | Day 546 |
| D5 | Security audit report | Dev 1 | PDF | Day 547 |
| D6 | Load test results | Dev 2 | Report | Day 547 |
| D7 | UAT test cases | Dev 3 | Excel | Day 545 |
| D8 | UAT execution | All | - | Day 548 |
| D9 | Bug fix sprint | All | - | Day 549 |
| D10 | Final documentation | All | MD/PDF | Day 550 |
| D11 | Phase 8 sign-off | All | Checklist | Day 550 |

---

## 4. Day-by-Day Testing Plan

### Day 541-543: Unit Testing

#### Dev 1 (Backend Lead)

**Payment Provider Tests**

```python
# File: smart_dairy_payment/tests/test_payment_providers.py

from odoo.tests.common import TransactionCase
from unittest.mock import patch, MagicMock
import json


class TestBkashPayment(TransactionCase):
    """Unit tests for bKash payment integration."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.provider = cls.env['payment.provider'].create({
            'name': 'bKash Test',
            'code': 'bkash',
            'state': 'test',
            'bkash_app_key': 'test_app_key',
            'bkash_app_secret': 'test_secret',
            'bkash_username': 'test_user',
            'bkash_password': 'test_pass',
            'bkash_mode': 'sandbox',
        })

        cls.partner = cls.env['res.partner'].create({
            'name': 'Test Customer',
            'email': 'test@example.com',
            'phone': '+8801712345678',
        })

    @patch('requests.post')
    def test_token_grant(self, mock_post):
        """Test bKash token grant flow."""
        mock_response = MagicMock()
        mock_response.json.return_value = {
            'id_token': 'test_token_123',
            'token_type': 'Bearer',
        }
        mock_response.status_code = 200
        mock_post.return_value = mock_response

        service = self.env['bkash.token.service']
        token = service._grant_token(self.provider)

        self.assertEqual(token, 'test_token_123')
        mock_post.assert_called_once()

    @patch('requests.post')
    def test_create_payment(self, mock_post):
        """Test bKash payment creation."""
        mock_response = MagicMock()
        mock_response.json.return_value = {
            'paymentID': 'PAY123456',
            'bkashURL': 'https://sandbox.bkash.com/pay/123',
            'statusCode': '0000',
            'statusMessage': 'Successful',
        }
        mock_response.status_code = 200
        mock_post.return_value = mock_response

        transaction = self.env['payment.transaction'].create({
            'provider_id': self.provider.id,
            'amount': 500.00,
            'currency_id': self.env.ref('base.BDT').id,
            'reference': 'TEST-001',
            'partner_id': self.partner.id,
        })

        service = self.env['bkash.checkout.service']
        with patch.object(service, '_grant_token', return_value='test_token'):
            result = service.create_payment(
                self.provider, transaction, 'https://callback.url'
            )

        self.assertTrue(result.get('success'))
        self.assertEqual(result['payment_id'], 'PAY123456')

    @patch('requests.post')
    def test_execute_payment(self, mock_post):
        """Test bKash payment execution."""
        mock_response = MagicMock()
        mock_response.json.return_value = {
            'statusCode': '0000',
            'statusMessage': 'Successful',
            'paymentID': 'PAY123456',
            'trxID': 'TRX789012',
            'amount': '500.00',
        }
        mock_response.status_code = 200
        mock_post.return_value = mock_response

        service = self.env['bkash.checkout.service']
        with patch.object(service, '_grant_token', return_value='test_token'):
            result = service.execute_payment(self.provider, 'PAY123456')

        self.assertTrue(result.get('success'))
        self.assertEqual(result['trx_id'], 'TRX789012')

    def test_refund_validation(self):
        """Test refund amount validation."""
        transaction = self.env['payment.transaction'].create({
            'provider_id': self.provider.id,
            'amount': 500.00,
            'currency_id': self.env.ref('base.BDT').id,
            'reference': 'TEST-002',
            'partner_id': self.partner.id,
            'state': 'done',
        })

        # Test refund > original amount should fail
        with self.assertRaises(Exception):
            self.env['bkash.refund.service'].process_refund(
                self.provider, transaction, amount=600.00
            )


class TestNagadPayment(TransactionCase):
    """Unit tests for Nagad payment integration."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.provider = cls.env['payment.provider'].create({
            'name': 'Nagad Test',
            'code': 'nagad',
            'state': 'test',
            'nagad_merchant_id': 'test_merchant',
            'nagad_mode': 'sandbox',
        })

    def test_encryption_service(self):
        """Test Nagad RSA encryption."""
        crypto = self.env['nagad.crypto.service']

        # Test data
        test_data = {'merchantId': 'test', 'orderId': '123'}

        # This would need actual test keys
        # encrypted = crypto.encrypt_data(test_data, test_public_key)
        # self.assertIsNotNone(encrypted)
        pass

    def test_signature_generation(self):
        """Test Nagad signature generation."""
        crypto = self.env['nagad.crypto.service']
        # Test signature generation
        pass


class TestSSLCommerzPayment(TransactionCase):
    """Unit tests for SSLCommerz payment integration."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.provider = cls.env['payment.provider'].create({
            'name': 'SSLCommerz Test',
            'code': 'sslcommerz',
            'state': 'test',
            'sslc_store_id': 'teststore',
            'sslc_store_passwd': 'testpass',
            'sslc_mode': 'sandbox',
        })

    @patch('requests.post')
    def test_session_initialization(self, mock_post):
        """Test SSLCommerz session init."""
        mock_response = MagicMock()
        mock_response.json.return_value = {
            'status': 'SUCCESS',
            'GatewayPageURL': 'https://sandbox.sslcommerz.com/pay/123',
            'sessionkey': 'SESSION123',
        }
        mock_post.return_value = mock_response

        service = self.env['sslcommerz.payment.service']
        # Test implementation

    def test_ipn_hash_verification(self):
        """Test IPN hash verification."""
        hash_service = self.env['sslcommerz.hash.service']

        ipn_data = {
            'tran_id': 'TEST001',
            'val_id': 'VAL123',
            'amount': '1000.00',
            'status': 'VALID',
            'verify_sign': 'expected_hash',
            'verify_key': 'tran_id,val_id,amount,status',
        }

        # Test hash verification logic
        # is_valid = hash_service.verify_ipn_hash(ipn_data, 'store_pass')
        pass


class TestCODPayment(TransactionCase):
    """Unit tests for COD payment processing."""

    def test_eligibility_check_new_customer(self):
        """Test COD eligibility for new customer."""
        partner = self.env['res.partner'].create({
            'name': 'New Customer',
            'phone': '+8801712345678',
        })

        service = self.env['cod.eligibility.service']
        result = service.check_eligibility(partner, 1500.00)

        self.assertTrue(result.get('eligible'))
        self.assertTrue(result.get('otp_required'))

    def test_eligibility_check_blacklisted(self):
        """Test COD eligibility for blacklisted customer."""
        partner = self.env['res.partner'].create({
            'name': 'Blacklisted Customer',
            'phone': '+8801712345678',
            'cod_blacklisted': True,
        })

        service = self.env['cod.eligibility.service']
        result = service.check_eligibility(partner, 1000.00)

        self.assertFalse(result.get('eligible'))
        self.assertEqual(result.get('reason'), 'blacklisted')

    def test_fraud_scoring(self):
        """Test fraud scoring algorithm."""
        partner = self.env['res.partner'].create({
            'name': 'Test Customer',
            'phone': '+8801712345678',
        })

        order = self.env['sale.order'].create({
            'partner_id': partner.id,
        })

        service = self.env['cod.fraud.scoring']
        result = service.calculate_fraud_score(partner, order)

        self.assertIn('score', result)
        self.assertIn('flags', result)
        self.assertLessEqual(result['score'], 100)

    def test_otp_generation(self):
        """Test OTP generation and validation."""
        otp_service = self.env['cod.otp.service']

        otp = otp_service.generate_otp(length=6)

        self.assertEqual(len(otp), 6)
        self.assertTrue(otp.isdigit())
```

#### Dev 2 (Full-Stack)

**Integration Tests**

```python
# File: smart_dairy_payment/tests/test_integration.py

from odoo.tests.common import TransactionCase, tagged
from unittest.mock import patch, MagicMock


@tagged('post_install', '-at_install')
class TestPaymentIntegration(TransactionCase):
    """Integration tests for payment workflows."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        # Setup test data
        cls.partner = cls.env['res.partner'].create({
            'name': 'Integration Test Customer',
            'email': 'integration@test.com',
            'phone': '+8801712345678',
        })

        cls.product = cls.env['product.product'].create({
            'name': 'Test Dairy Product',
            'list_price': 500.00,
            'type': 'consu',
        })

        # Create providers
        cls.bkash = cls.env['payment.provider'].create({
            'name': 'bKash',
            'code': 'bkash',
            'state': 'test',
        })

    def test_checkout_to_payment_flow(self):
        """Test complete checkout to payment flow."""
        # Create sale order
        order = self.env['sale.order'].create({
            'partner_id': self.partner.id,
            'order_line': [(0, 0, {
                'product_id': self.product.id,
                'product_uom_qty': 2,
            })],
        })

        # Confirm order
        order.action_confirm()
        self.assertEqual(order.state, 'sale')

        # Create payment transaction
        transaction = self.env['payment.transaction'].create({
            'provider_id': self.bkash.id,
            'amount': order.amount_total,
            'currency_id': self.env.ref('base.BDT').id,
            'reference': order.name,
            'partner_id': self.partner.id,
        })

        self.assertEqual(transaction.state, 'draft')

    def test_reconciliation_workflow(self):
        """Test payment reconciliation workflow."""
        # Create expected transactions
        # Create settlement data
        # Run reconciliation
        # Verify matches and discrepancies
        pass


@tagged('post_install', '-at_install')
class TestDeliveryIntegration(TransactionCase):
    """Integration tests for delivery workflows."""

    def test_zone_assignment_flow(self):
        """Test delivery zone assignment."""
        zone = self.env['delivery.zone'].create({
            'name': 'Dhaka Central',
            'code': 'DHK-CTR',
            'zone_type': 'radius',
            'center_latitude': 23.8103,
            'center_longitude': 90.4125,
            'radius_km': 5,
        })

        # Test point inside zone
        is_inside = zone.check_point_in_zone(23.8100, 90.4100)
        self.assertTrue(is_inside)

        # Test point outside zone
        is_outside = zone.check_point_in_zone(24.0000, 91.0000)
        self.assertFalse(is_outside)

    def test_route_optimization_flow(self):
        """Test route optimization workflow."""
        # Create test deliveries and drivers
        # Run optimization
        # Verify route quality
        pass

    def test_courier_shipment_flow(self):
        """Test courier shipment creation."""
        # Create order
        # Select courier
        # Create shipment
        # Verify tracking
        pass
```

### Day 544-546: E2E Testing & Load Testing

#### Dev 2 (Full-Stack)

**E2E Test Scenarios**

```python
# File: smart_dairy_payment/tests/test_e2e.py

from odoo.tests.common import HttpCase, tagged


@tagged('e2e', 'post_install')
class TestE2ECheckoutToDelivery(HttpCase):
    """End-to-end tests for complete customer journey."""

    def test_e2e_bkash_payment(self):
        """E2E test: Cart → Checkout → bKash Payment → Order Confirmation."""
        # 1. Add items to cart
        # 2. Proceed to checkout
        # 3. Select bKash payment
        # 4. Complete payment
        # 5. Verify order confirmation
        pass

    def test_e2e_cod_payment(self):
        """E2E test: Cart → Checkout → COD → OTP → Confirmation."""
        # 1. Add items to cart
        # 2. Proceed to checkout
        # 3. Select COD
        # 4. Verify OTP
        # 5. Confirm order
        pass

    def test_e2e_delivery_tracking(self):
        """E2E test: Order → Dispatch → Track → Deliver."""
        # 1. Create confirmed order
        # 2. Assign to route
        # 3. Start delivery
        # 4. Update GPS location
        # 5. Mark delivered
        # 6. Verify customer notification
        pass


@tagged('load', 'post_install')
class TestLoadPaymentAPI(HttpCase):
    """Load tests for payment APIs."""

    def test_payment_init_load(self):
        """Load test: Payment initialization under load."""
        # Simulate concurrent payment initializations
        pass

    def test_webhook_load(self):
        """Load test: Webhook processing under load."""
        # Simulate high webhook volume
        pass
```

**Load Test Configuration (Locust)**

```python
# File: smart_dairy_payment/tests/locustfile.py

from locust import HttpUser, task, between
import random
import uuid


class PaymentLoadTest(HttpUser):
    """Load testing for payment APIs."""
    wait_time = between(1, 3)

    def on_start(self):
        """Setup before tests."""
        self.headers = {
            'Content-Type': 'application/json',
        }

    @task(5)
    def test_payment_init(self):
        """Test payment initialization."""
        payload = {
            'provider_id': 1,
            'amount': random.randint(100, 5000),
            'reference': f'LOAD-{uuid.uuid4().hex[:8]}',
        }
        self.client.post(
            '/payment/bkash/initialize',
            json=payload,
            headers=self.headers
        )

    @task(3)
    def test_payment_status(self):
        """Test payment status check."""
        self.client.get(
            f'/payment/status/TEST-{random.randint(1, 1000)}'
        )

    @task(2)
    def test_zone_lookup(self):
        """Test delivery zone lookup."""
        lat = 23.8 + random.uniform(-0.1, 0.1)
        lng = 90.4 + random.uniform(-0.1, 0.1)
        self.client.post(
            '/delivery/zone/detect',
            json={'latitude': lat, 'longitude': lng},
            headers=self.headers
        )
```

### Day 547-548: Security Audit & UAT

#### Security Audit Checklist

```markdown
## Phase 8 Security Audit Checklist

### Payment Security
- [ ] No card data stored locally
- [ ] All API keys encrypted at rest
- [ ] HTTPS enforced for all payment APIs
- [ ] Webhook signatures verified
- [ ] Amount validation on all transactions
- [ ] SQL injection prevention verified
- [ ] XSS prevention verified

### Authentication & Authorization
- [ ] Payment admin roles properly restricted
- [ ] API rate limiting implemented
- [ ] Session management secure
- [ ] CSRF protection enabled

### Data Protection
- [ ] PII encrypted in database
- [ ] Audit logs implemented
- [ ] Data retention policies defined
- [ ] Backup procedures verified

### Third-Party Integrations
- [ ] API credentials secure
- [ ] Fallback handling implemented
- [ ] Error messages sanitized
- [ ] Timeout handling proper
```

#### UAT Test Cases

```markdown
## UAT Test Cases - Phase 8

### Payment Tests
| ID | Test Case | Steps | Expected Result | Status |
|----|-----------|-------|-----------------|--------|
| UAT-P01 | bKash payment success | 1. Add items 2. Checkout 3. Select bKash 4. Complete payment | Order confirmed, receipt shown | |
| UAT-P02 | bKash payment failure | 1. Initiate payment 2. Cancel on bKash | Order status shows failed | |
| UAT-P03 | Nagad payment success | Similar to P01 with Nagad | Order confirmed | |
| UAT-P04 | Card payment with 3DS | 1. Enter card 2. Complete 3DS | Payment successful | |
| UAT-P05 | COD order with OTP | 1. Select COD 2. Enter OTP | Order confirmed | |
| UAT-P06 | Refund processing | 1. Request refund 2. Admin approves | Refund credited | |

### Delivery Tests
| ID | Test Case | Steps | Expected Result | Status |
|----|-----------|-------|-----------------|--------|
| UAT-D01 | Zone detection | Enter address | Correct zone displayed | |
| UAT-D02 | Delivery fee calculation | Add items, check zone | Fee calculated correctly | |
| UAT-D03 | Express delivery option | Select express | Correct fee and time | |
| UAT-D04 | Order tracking | Track order | Live location shown | |
| UAT-D05 | Driver route optimization | Assign multiple orders | Optimized route generated | |

### SMS Tests
| ID | Test Case | Steps | Expected Result | Status |
|----|-----------|-------|-----------------|--------|
| UAT-S01 | Order confirmation SMS | Place order | SMS received | |
| UAT-S02 | Delivery update SMS | Order dispatched | SMS received | |
| UAT-S03 | OTP SMS | Request OTP | SMS with code received | |
```

### Day 549-550: Bug Fixing & Documentation

**Bug Tracking Template**

```markdown
## Bug Report Template

**Bug ID**: BUG-P8-XXX
**Severity**: Critical/High/Medium/Low
**Component**: Payment/Delivery/SMS
**Status**: Open/In Progress/Fixed/Verified

**Description**:
[Clear description of the bug]

**Steps to Reproduce**:
1. Step 1
2. Step 2
3. Step 3

**Expected Behavior**:
[What should happen]

**Actual Behavior**:
[What actually happens]

**Environment**:
- Browser/Device:
- User Role:

**Fix Details**:
- Root Cause:
- Solution:
- Files Changed:

**Verified By**: [Name]
**Verification Date**: [Date]
```

---

## 5. Test Coverage Summary

### 5.1 Coverage by Module

| Module | Unit Tests | Integration | E2E | Total Coverage |
|--------|------------|-------------|-----|----------------|
| bKash Payment | 15 | 5 | 3 | 85% |
| Nagad Payment | 12 | 4 | 2 | 82% |
| SSLCommerz | 14 | 6 | 3 | 88% |
| COD Processing | 18 | 5 | 4 | 90% |
| Reconciliation | 10 | 4 | 2 | 80% |
| Delivery Zones | 12 | 4 | 3 | 85% |
| Route Optimization | 8 | 3 | 2 | 78% |
| Courier Integration | 15 | 5 | 3 | 82% |
| SMS Notifications | 10 | 4 | 3 | 80% |

### 5.2 Test Execution Summary

| Metric | Target | Actual |
|--------|--------|--------|
| Total Test Cases | 200+ | |
| Pass Rate | > 95% | |
| Critical Bugs Fixed | 100% | |
| Code Coverage | > 80% | |

---

## 6. Sign-off Checklist

### 6.1 Testing Complete

- [ ] All unit tests passing
- [ ] All integration tests passing
- [ ] All E2E scenarios verified
- [ ] Load tests meet performance targets
- [ ] Security audit passed
- [ ] UAT signed off

### 6.2 Documentation Complete

- [ ] API documentation finalized
- [ ] User guides complete
- [ ] Technical documentation updated
- [ ] Deployment guide ready

### 6.3 Phase 8 Exit Criteria

- [ ] All payment methods functional
- [ ] Reconciliation system operational
- [ ] Delivery zones configured
- [ ] Route optimization working
- [ ] Courier integrations active
- [ ] SMS notifications sending
- [ ] 0 critical bugs remaining
- [ ] Stakeholder sign-off obtained

---

## 7. Phase 8 → Phase 9 Transition

### Handoff Items
1. All payment credentials and API keys documented
2. Courier partnership agreements finalized
3. SMS gateway contracts active
4. Support procedures documented
5. Monitoring dashboards operational

### Phase 9 Prerequisites Met
- [ ] Payment infrastructure stable
- [ ] Delivery operations automated
- [ ] Customer communications live
- [ ] Finance reconciliation running

---

**Document End**

*Milestone 80: Payment & Logistics Testing*
*Days 541-550 | Phase 8: Operations - Payment & Logistics*
*Smart Dairy Digital Smart Portal + ERP System*
