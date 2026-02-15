# Milestone 72: Nagad Payment Integration

## Smart Dairy Digital Smart Portal + ERP System

---

## Document Control

| Field | Value |
|-------|-------|
| **Document ID** | SD-IMPL-P8-M72-v1.0 |
| **Milestone** | 72 - Nagad Payment Integration |
| **Phase** | Phase 8: Operations - Payment & Logistics |
| **Days** | 461-470 |
| **Duration** | 10 Working Days |
| **Version** | 1.0 |
| **Status** | Draft |
| **Authors** | Technical Architecture Team |
| **Last Updated** | 2025-01-15 |

---

## Table of Contents

1. [Milestone Overview](#1-milestone-overview)
2. [Objectives](#2-objectives)
3. [Key Deliverables](#3-key-deliverables)
4. [Requirement Traceability](#4-requirement-traceability)
5. [Day-by-Day Development Plan](#5-day-by-day-development-plan)
6. [Technical Specifications](#6-technical-specifications)
7. [Database Schema](#7-database-schema)
8. [API Documentation](#8-api-documentation)
9. [Security Implementation](#9-security-implementation)
10. [Testing Requirements](#10-testing-requirements)
11. [Risk & Mitigation](#11-risk--mitigation)
12. [Dependencies](#12-dependencies)
13. [Sign-off Checklist](#13-sign-off-checklist)

---

## 1. Milestone Overview

### 1.1 Sprint Goal

**Implement complete Nagad Merchant Payment Gateway integration with RSA encryption, secure checkout flow, payment verification, refund processing, and webhook handling for reliable mobile financial service payments.**

### 1.2 Scope Summary

| Area | Included |
|------|----------|
| API Configuration | Nagad merchant credentials and key management |
| Encryption | RSA public/private key implementation |
| Checkout Flow | Initialize → Complete → Verify workflow |
| Refund | Full and partial refund processing |
| Webhooks | Callback handling with signature verification |
| Tokenization | Saved payment method support |
| UI Components | Nagad payment selection and status |
| Testing | Unit, integration, and E2E tests |

### 1.3 Prerequisites

- Milestone 71 (bKash Integration) completed
- Nagad Merchant account approved
- API credentials obtained from Nagad
- RSA key pair generated
- SSL certificate configured for callbacks

---

## 2. Objectives

| # | Objective | Success Metric |
|---|-----------|----------------|
| O1 | Nagad API integration | 100% API coverage |
| O2 | RSA encryption implementation | All payloads encrypted |
| O3 | Payment success rate | > 95% |
| O4 | Refund processing | < 24h processing time |
| O5 | Webhook reliability | 99.9% delivery success |
| O6 | Response time | < 3s for payment init |
| O7 | Test coverage | > 80% code coverage |

---

## 3. Key Deliverables

| # | Deliverable | Owner | Format | Due Day |
|---|-------------|-------|--------|---------|
| D1 | Nagad provider configuration | Dev 1 | Python/Odoo | Day 462 |
| D2 | RSA encryption service | Dev 1 | Python | Day 463 |
| D3 | Initialize checkout API | Dev 1 | Python | Day 464 |
| D4 | Complete payment workflow | Dev 1 | Python | Day 465 |
| D5 | Payment verification service | Dev 2 | Python | Day 465 |
| D6 | Refund processing | Dev 2 | Python | Day 466 |
| D7 | Webhook handler | Dev 2 | Python | Day 467 |
| D8 | Nagad payment UI component | Dev 3 | OWL/JS | Day 466 |
| D9 | Payment status tracking UI | Dev 3 | OWL/JS | Day 468 |
| D10 | Integration tests | All | Python | Day 469 |
| D11 | E2E tests and documentation | All | Mixed | Day 470 |

---

## 4. Requirement Traceability

### 4.1 RFP Requirements

| RFP Req ID | Description | Deliverable |
|------------|-------------|-------------|
| ECOM-PAY-002 | Nagad Payment Integration | D1-D7 |
| ECOM-PAY-002.1 | Nagad checkout flow | D3, D4 |
| ECOM-PAY-002.2 | Nagad refund processing | D6 |
| ECOM-PAY-002.3 | Payment verification | D5 |

### 4.2 BRD Requirements

| BRD Req ID | Description | Deliverable |
|------------|-------------|-------------|
| FR-CHK-002.2 | Mobile Financial Service - Nagad | D1-D7 |
| FR-CHK-002.5 | Payment status tracking | D5, D9 |
| FR-SEC-003 | Payment data encryption | D2 |

### 4.3 SRS Requirements

| SRS Req ID | Description | Deliverable |
|------------|-------------|-------------|
| SRS-PAY-002 | Nagad API integration | D1, D3, D4 |
| SRS-PAY-002.1 | RSA encryption | D2 |
| SRS-PAY-002.2 | Webhook processing | D7 |

---

## 5. Day-by-Day Development Plan

### Day 461: Project Setup & API Analysis

#### Day Objective
Set up Nagad integration module structure, analyze API documentation, and configure development environment.

#### Dev 1 (Backend Lead) - 8 Hours

**Task 1.1: Module Structure Creation (3h)**

```python
# File: smart_dairy_payment_nagad/__manifest__.py

{
    'name': 'Smart Dairy - Nagad Payment Integration',
    'version': '19.0.1.0.0',
    'category': 'Accounting/Payment',
    'summary': 'Nagad Payment Gateway Integration for Smart Dairy',
    'description': """
        Nagad Payment Integration Module
        ================================
        - Nagad Merchant Checkout API
        - RSA Encryption/Decryption
        - Payment verification
        - Refund processing
        - Webhook handling
    """,
    'author': 'Smart Dairy Technical Team',
    'website': 'https://smartdairy.com.bd',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'payment',
        'sale',
        'smart_dairy_payment',
    ],
    'data': [
        'security/ir.model.access.csv',
        'security/nagad_security.xml',
        'data/payment_provider_data.xml',
        'views/payment_provider_views.xml',
        'views/payment_transaction_views.xml',
    ],
    'assets': {
        'web.assets_frontend': [
            'smart_dairy_payment_nagad/static/src/js/**/*',
            'smart_dairy_payment_nagad/static/src/css/**/*',
            'smart_dairy_payment_nagad/static/src/xml/**/*',
        ],
    },
    'external_dependencies': {
        'python': ['pycryptodome', 'requests'],
    },
    'installable': True,
    'application': False,
    'auto_install': False,
}
```

**Task 1.2: Security Configuration (2h)**

```xml
<!-- File: smart_dairy_payment_nagad/security/nagad_security.xml -->

<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Nagad Configuration Access Group -->
    <record id="group_nagad_config" model="res.groups">
        <field name="name">Nagad Configuration</field>
        <field name="category_id" ref="base.module_category_accounting_accounting"/>
        <field name="implied_ids" eval="[(4, ref('base.group_system'))]"/>
    </record>

    <!-- Nagad Transaction View Group -->
    <record id="group_nagad_transaction_view" model="res.groups">
        <field name="name">View Nagad Transactions</field>
        <field name="category_id" ref="base.module_category_accounting_accounting"/>
    </record>

    <!-- Record Rules -->
    <record id="nagad_transaction_rule_accountant" model="ir.rule">
        <field name="name">Nagad Transactions: Accountant Access</field>
        <field name="model_id" ref="payment.model_payment_transaction"/>
        <field name="domain_force">[('provider_code', '=', 'nagad')]</field>
        <field name="groups" eval="[(4, ref('account.group_account_user'))]"/>
        <field name="perm_read" eval="True"/>
        <field name="perm_write" eval="False"/>
        <field name="perm_create" eval="False"/>
        <field name="perm_unlink" eval="False"/>
    </record>
</odoo>
```

**Task 1.3: API Documentation Analysis (3h)**

```python
# File: smart_dairy_payment_nagad/docs/nagad_api_spec.py
"""
Nagad API Specification Reference
=================================

Base URLs:
- Sandbox: https://sandbox.nagad.com.bd/api
- Production: https://api.nagad.com.bd/api

API Endpoints:
1. Initialize Checkout: POST /dfs/check-out/initialize/{merchantId}/{orderId}
2. Complete Payment: POST /dfs/check-out/complete/{paymentRefId}
3. Verify Payment: GET /dfs/verify/payment/{paymentRefId}
4. Refund: POST /dfs/refund/{paymentRefId}

Authentication:
- Uses RSA encryption for sensitive data
- Merchant public key encrypts data sent to Nagad
- Nagad public key used for signature verification

Required Headers:
- X-KM-Api-Version: v-0.2.0
- X-KM-IP-V4: Client IP address
- X-KM-Client-Type: PC_WEB or MOBILE_WEB

Encryption Requirements:
- Sensitive data encrypted with Nagad's public key
- Signature generated with Merchant's private key
- All timestamps in ISO 8601 format
"""

NAGAD_API_CONFIG = {
    'sandbox': {
        'base_url': 'https://sandbox.mynagad.com:10061/remote-payment-gateway-1.0',
        'callback_url_base': 'https://sandbox-ssl.mynagad.com:10061',
    },
    'production': {
        'base_url': 'https://api.mynagad.com/api',
        'callback_url_base': 'https://api.mynagad.com',
    },
    'api_version': 'v-0.2.0',
    'timeout': 30,
    'retry_attempts': 3,
}
```

#### Dev 2 (Full-Stack) - 8 Hours

**Task 2.1: RSA Key Management Setup (4h)**

```python
# File: smart_dairy_payment_nagad/services/key_manager.py

import os
import base64
from cryptography.hazmat.primitives import hashes, serialization
from cryptography.hazmat.primitives.asymmetric import rsa, padding
from cryptography.hazmat.backends import default_backend
from odoo import models, api, fields
from odoo.exceptions import UserError
import logging

_logger = logging.getLogger(__name__)


class NagadKeyManager(models.AbstractModel):
    _name = 'nagad.key.manager'
    _description = 'Nagad RSA Key Manager'

    @api.model
    def generate_key_pair(self, key_size=2048):
        """Generate new RSA key pair for Nagad integration."""
        try:
            private_key = rsa.generate_private_key(
                public_exponent=65537,
                key_size=key_size,
                backend=default_backend()
            )

            private_pem = private_key.private_bytes(
                encoding=serialization.Encoding.PEM,
                format=serialization.PrivateFormat.PKCS8,
                encryption_algorithm=serialization.NoEncryption()
            )

            public_key = private_key.public_key()
            public_pem = public_key.public_bytes(
                encoding=serialization.Encoding.PEM,
                format=serialization.PublicFormat.SubjectPublicKeyInfo
            )

            return {
                'private_key': private_pem.decode('utf-8'),
                'public_key': public_pem.decode('utf-8'),
            }
        except Exception as e:
            _logger.error(f"Key generation failed: {str(e)}")
            raise UserError(f"Failed to generate RSA keys: {str(e)}")

    @api.model
    def load_private_key(self, pem_data):
        """Load private key from PEM format."""
        try:
            if isinstance(pem_data, str):
                pem_data = pem_data.encode('utf-8')
            return serialization.load_pem_private_key(
                pem_data,
                password=None,
                backend=default_backend()
            )
        except Exception as e:
            _logger.error(f"Failed to load private key: {str(e)}")
            raise UserError("Invalid private key format")

    @api.model
    def load_public_key(self, pem_data):
        """Load public key from PEM format."""
        try:
            if isinstance(pem_data, str):
                pem_data = pem_data.encode('utf-8')
            return serialization.load_pem_public_key(
                pem_data,
                backend=default_backend()
            )
        except Exception as e:
            _logger.error(f"Failed to load public key: {str(e)}")
            raise UserError("Invalid public key format")
```

**Task 2.2: Environment Configuration (2h)**

```python
# File: smart_dairy_payment_nagad/services/nagad_config.py

from odoo import models, api, fields
import logging

_logger = logging.getLogger(__name__)


class NagadConfiguration(models.AbstractModel):
    _name = 'nagad.configuration'
    _description = 'Nagad Configuration Service'

    @api.model
    def get_config(self, provider):
        """Get Nagad configuration for a provider."""
        is_sandbox = provider.nagad_mode == 'sandbox'

        if is_sandbox:
            base_url = 'http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0'
        else:
            base_url = 'https://api.mynagad.com/api/dfs'

        return {
            'base_url': base_url,
            'merchant_id': provider.nagad_merchant_id,
            'merchant_number': provider.nagad_merchant_number,
            'public_key': provider.nagad_pg_public_key,
            'private_key': provider.nagad_merchant_private_key,
            'is_sandbox': is_sandbox,
            'timeout': 30,
            'api_version': 'v-0.2.0',
        }

    @api.model
    def validate_config(self, provider):
        """Validate Nagad provider configuration."""
        errors = []

        if not provider.nagad_merchant_id:
            errors.append("Merchant ID is required")
        if not provider.nagad_merchant_number:
            errors.append("Merchant Number is required")
        if not provider.nagad_pg_public_key:
            errors.append("Nagad Public Key is required")
        if not provider.nagad_merchant_private_key:
            errors.append("Merchant Private Key is required")

        return errors
```

**Task 2.3: Test Environment Setup (2h)**

```python
# File: smart_dairy_payment_nagad/tests/common.py

from odoo.tests.common import TransactionCase
from unittest.mock import patch, MagicMock
import json


class NagadTestCommon(TransactionCase):
    """Common test setup for Nagad payment tests."""

    @classmethod
    def setUpClass(cls):
        super().setUpClass()

        # Create test partner
        cls.partner = cls.env['res.partner'].create({
            'name': 'Test Customer Nagad',
            'email': 'test.nagad@example.com',
            'phone': '+8801712345678',
        })

        # Create Nagad provider
        cls.provider = cls.env['payment.provider'].create({
            'name': 'Nagad Test',
            'code': 'nagad',
            'state': 'test',
            'nagad_mode': 'sandbox',
            'nagad_merchant_id': 'TEST_MERCHANT_001',
            'nagad_merchant_number': '01700000000',
            'nagad_pg_public_key': cls._get_test_public_key(),
            'nagad_merchant_private_key': cls._get_test_private_key(),
        })

        # Create test sale order
        cls.product = cls.env['product.product'].create({
            'name': 'Test Dairy Product',
            'list_price': 500.00,
            'type': 'consu',
        })

        cls.sale_order = cls.env['sale.order'].create({
            'partner_id': cls.partner.id,
            'order_line': [(0, 0, {
                'product_id': cls.product.id,
                'product_uom_qty': 2,
                'price_unit': 500.00,
            })],
        })

    @classmethod
    def _get_test_public_key(cls):
        """Return test public key."""
        return """-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjBH9... (test key)
-----END PUBLIC KEY-----"""

    @classmethod
    def _get_test_private_key(cls):
        """Return test private key."""
        return """-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA... (test key)
-----END PRIVATE KEY-----"""

    def _mock_nagad_response(self, status='Success', payment_ref='NAGAD123456'):
        """Create mock Nagad API response."""
        return {
            'status': status,
            'statusCode': 'OK-0000',
            'paymentRefId': payment_ref,
            'amount': '1000.00',
            'merchantId': self.provider.nagad_merchant_id,
        }
```

#### Dev 3 (Frontend/Mobile Lead) - 8 Hours

**Task 3.1: UI Component Structure (4h)**

```javascript
/** @odoo-module **/
// File: smart_dairy_payment_nagad/static/src/js/nagad_payment.js

import { Component, useState, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { _t } from "@web/core/l10n/translation";

export class NagadPaymentForm extends Component {
    static template = "smart_dairy_payment_nagad.NagadPaymentForm";
    static props = {
        providerId: { type: Number, required: true },
        amount: { type: Number, required: true },
        currency: { type: String, default: "BDT" },
        reference: { type: String, required: true },
        partnerId: { type: Number, optional: true },
        onSuccess: { type: Function, optional: true },
        onError: { type: Function, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: false,
            step: 'init', // init, processing, redirect, complete, error
            nagadNumber: '',
            errorMessage: '',
            paymentRefId: null,
            redirectUrl: null,
        });

        onMounted(() => {
            this.validateProps();
        });
    }

    validateProps() {
        if (this.props.amount <= 0) {
            this.state.errorMessage = _t("Invalid payment amount");
            this.state.step = 'error';
        }
    }

    async onNagadNumberChange(ev) {
        let value = ev.target.value.replace(/\D/g, '');
        if (value.startsWith('880')) {
            value = value.substring(3);
        }
        if (value.startsWith('0')) {
            value = value.substring(1);
        }
        this.state.nagadNumber = value;
    }

    isValidNagadNumber() {
        const number = this.state.nagadNumber;
        // Nagad numbers start with 17, 13, 14, 18, 19, 16, 15
        const validPrefixes = ['17', '13', '14', '18', '19', '16', '15'];
        return number.length === 10 && validPrefixes.some(p => number.startsWith(p));
    }

    async initiatePayment() {
        if (!this.isValidNagadNumber()) {
            this.notification.add(_t("Please enter a valid Nagad number"), {
                type: "warning",
            });
            return;
        }

        this.state.loading = true;
        this.state.step = 'processing';

        try {
            const result = await this.rpc("/payment/nagad/initialize", {
                provider_id: this.props.providerId,
                amount: this.props.amount,
                reference: this.props.reference,
                nagad_number: `+880${this.state.nagadNumber}`,
                partner_id: this.props.partnerId,
            });

            if (result.success && result.redirect_url) {
                this.state.paymentRefId = result.payment_ref_id;
                this.state.redirectUrl = result.redirect_url;
                this.state.step = 'redirect';

                // Redirect to Nagad payment page
                window.location.href = result.redirect_url;
            } else {
                throw new Error(result.error || _t("Payment initialization failed"));
            }
        } catch (error) {
            this.state.step = 'error';
            this.state.errorMessage = error.message || _t("An error occurred");
            this.notification.add(this.state.errorMessage, { type: "danger" });

            if (this.props.onError) {
                this.props.onError(error);
            }
        } finally {
            this.state.loading = false;
        }
    }

    retryPayment() {
        this.state.step = 'init';
        this.state.errorMessage = '';
        this.state.paymentRefId = null;
    }
}
```

**Task 3.2: UI Template Design (2h)**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_payment_nagad/static/src/xml/nagad_payment_templates.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_payment_nagad.NagadPaymentForm">
        <div class="nagad-payment-form">
            <!-- Header with Nagad Logo -->
            <div class="nagad-header text-center mb-4">
                <img src="/smart_dairy_payment_nagad/static/src/img/nagad-logo.png"
                     alt="Nagad" class="nagad-logo" style="height: 50px;"/>
                <h4 class="mt-2">Pay with Nagad</h4>
            </div>

            <!-- Amount Display -->
            <div class="nagad-amount-display text-center mb-4 p-3 bg-light rounded">
                <span class="text-muted">Amount to Pay</span>
                <h3 class="mb-0">
                    <t t-esc="props.currency"/> <t t-esc="props.amount.toFixed(2)"/>
                </h3>
            </div>

            <!-- Step: Initial - Enter Nagad Number -->
            <t t-if="state.step === 'init'">
                <div class="nagad-number-input mb-4">
                    <label for="nagad_number" class="form-label">
                        Nagad Account Number
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">+880</span>
                        <input type="tel"
                               id="nagad_number"
                               class="form-control form-control-lg"
                               t-att-value="state.nagadNumber"
                               t-on-input="onNagadNumberChange"
                               placeholder="1XXXXXXXXX"
                               maxlength="10"
                               pattern="[0-9]*"
                               inputmode="numeric"/>
                    </div>
                    <small class="form-text text-muted">
                        Enter your 10-digit Nagad number
                    </small>
                </div>

                <button type="button"
                        class="btn btn-danger btn-lg w-100"
                        t-att-disabled="state.loading or !isValidNagadNumber()"
                        t-on-click="initiatePayment">
                    <t t-if="state.loading">
                        <span class="spinner-border spinner-border-sm me-2"/>
                        Processing...
                    </t>
                    <t t-else="">
                        <i class="fa fa-lock me-2"/>
                        Pay Securely with Nagad
                    </t>
                </button>
            </t>

            <!-- Step: Processing -->
            <t t-if="state.step === 'processing'">
                <div class="text-center py-5">
                    <div class="spinner-border text-danger mb-3" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0">Connecting to Nagad...</p>
                    <small class="text-muted">Please wait while we process your request</small>
                </div>
            </t>

            <!-- Step: Redirect -->
            <t t-if="state.step === 'redirect'">
                <div class="text-center py-5">
                    <div class="spinner-border text-danger mb-3">
                        <span class="visually-hidden">Redirecting...</span>
                    </div>
                    <p class="mb-0">Redirecting to Nagad...</p>
                    <small class="text-muted">
                        You will be redirected to complete your payment
                    </small>
                    <div class="mt-3">
                        <a t-att-href="state.redirectUrl" class="btn btn-outline-danger">
                            Click here if not redirected
                        </a>
                    </div>
                </div>
            </t>

            <!-- Step: Error -->
            <t t-if="state.step === 'error'">
                <div class="alert alert-danger text-center">
                    <i class="fa fa-exclamation-circle fa-2x mb-2"/>
                    <p class="mb-2"><t t-esc="state.errorMessage"/></p>
                    <button type="button"
                            class="btn btn-outline-danger"
                            t-on-click="retryPayment">
                        <i class="fa fa-refresh me-1"/>
                        Try Again
                    </button>
                </div>
            </t>

            <!-- Security Notice -->
            <div class="nagad-security-notice text-center mt-4">
                <small class="text-muted">
                    <i class="fa fa-shield me-1"/>
                    Secured by Nagad. Your payment information is encrypted.
                </small>
            </div>
        </div>
    </t>
</templates>
```

**Task 3.3: Styling (2h)**

```css
/* File: smart_dairy_payment_nagad/static/src/css/nagad_payment.css */

.nagad-payment-form {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
}

.nagad-header {
    border-bottom: 2px solid #dc3545;
    padding-bottom: 15px;
}

.nagad-logo {
    max-height: 50px;
    width: auto;
}

.nagad-amount-display {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe0e0 100%);
    border: 1px solid #dc3545;
}

.nagad-amount-display h3 {
    color: #dc3545;
    font-weight: 700;
}

.nagad-number-input .input-group-text {
    background-color: #dc3545;
    color: white;
    border-color: #dc3545;
    font-weight: 600;
}

.nagad-number-input .form-control:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    transition: all 0.3s ease;
}

.btn-danger:hover:not(:disabled) {
    background-color: #c82333;
    border-color: #bd2130;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.btn-danger:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.nagad-security-notice {
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
}

/* Mobile Responsiveness */
@media (max-width: 576px) {
    .nagad-payment-form {
        padding: 15px;
    }

    .nagad-amount-display h3 {
        font-size: 1.5rem;
    }
}
```

#### End of Day 461 Deliverables

- [ ] Module structure created
- [ ] Security configuration completed
- [ ] API specification documented
- [ ] Key manager service created
- [ ] Configuration service completed
- [ ] Test environment setup
- [ ] UI component structure created
- [ ] Templates and styling completed

---

### Day 462: Payment Provider Model & Configuration

#### Day Objective
Implement Nagad payment provider model with configuration fields and credential management.

#### Dev 1 (Backend Lead) - 8 Hours

**Task 1.1: Payment Provider Extension (4h)**

```python
# File: smart_dairy_payment_nagad/models/payment_provider.py

from odoo import models, fields, api
from odoo.exceptions import ValidationError
import logging

_logger = logging.getLogger(__name__)


class PaymentProvider(models.Model):
    _inherit = 'payment.provider'

    code = fields.Selection(
        selection_add=[('nagad', 'Nagad')],
        ondelete={'nagad': 'set default'}
    )

    # Nagad Configuration Fields
    nagad_merchant_id = fields.Char(
        string='Merchant ID',
        help='Nagad Merchant ID provided during registration',
        groups='base.group_system'
    )
    nagad_merchant_number = fields.Char(
        string='Merchant Number',
        help='Nagad registered mobile number',
        groups='base.group_system'
    )
    nagad_pg_public_key = fields.Text(
        string='Nagad Public Key',
        help='Public key provided by Nagad for encryption',
        groups='base.group_system'
    )
    nagad_merchant_private_key = fields.Text(
        string='Merchant Private Key',
        help='Your private key for signing requests',
        groups='base.group_system'
    )
    nagad_merchant_public_key = fields.Text(
        string='Merchant Public Key',
        help='Your public key registered with Nagad',
        groups='base.group_system'
    )
    nagad_mode = fields.Selection([
        ('sandbox', 'Sandbox'),
        ('production', 'Production'),
    ], string='Mode', default='sandbox', required=True)

    # Callback Configuration
    nagad_callback_url = fields.Char(
        string='Callback URL',
        compute='_compute_nagad_callback_url'
    )

    @api.depends('code')
    def _compute_nagad_callback_url(self):
        base_url = self.env['ir.config_parameter'].sudo().get_param('web.base.url')
        for provider in self:
            if provider.code == 'nagad':
                provider.nagad_callback_url = f"{base_url}/payment/nagad/callback"
            else:
                provider.nagad_callback_url = False

    def _get_nagad_base_url(self):
        """Get Nagad API base URL based on mode."""
        self.ensure_one()
        if self.nagad_mode == 'sandbox':
            return 'http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0'
        return 'https://api.mynagad.com/api/dfs'

    @api.constrains('code', 'nagad_merchant_id', 'nagad_merchant_number')
    def _check_nagad_credentials(self):
        for provider in self:
            if provider.code == 'nagad' and provider.state != 'disabled':
                if not provider.nagad_merchant_id:
                    raise ValidationError("Nagad Merchant ID is required")
                if not provider.nagad_merchant_number:
                    raise ValidationError("Nagad Merchant Number is required")

    def _get_supported_currencies(self):
        """Return supported currencies for Nagad."""
        supported = super()._get_supported_currencies()
        if self.code == 'nagad':
            return supported.filtered(lambda c: c.name == 'BDT')
        return supported

    def _nagad_get_api_url(self, endpoint):
        """Construct full API URL for Nagad endpoint."""
        base_url = self._get_nagad_base_url()
        return f"{base_url}{endpoint}"

    def action_nagad_test_connection(self):
        """Test Nagad API connection."""
        self.ensure_one()
        try:
            # Simple validation by checking if credentials are set
            errors = self.env['nagad.configuration'].validate_config(self)
            if errors:
                raise ValidationError('\n'.join(errors))

            return {
                'type': 'ir.actions.client',
                'tag': 'display_notification',
                'params': {
                    'title': 'Connection Test',
                    'message': 'Nagad configuration is valid!',
                    'type': 'success',
                    'sticky': False,
                }
            }
        except Exception as e:
            raise ValidationError(f"Connection test failed: {str(e)}")

    def action_generate_keys(self):
        """Generate new RSA key pair."""
        self.ensure_one()
        key_manager = self.env['nagad.key.manager']
        keys = key_manager.generate_key_pair()

        self.write({
            'nagad_merchant_private_key': keys['private_key'],
            'nagad_merchant_public_key': keys['public_key'],
        })

        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Keys Generated',
                'message': 'New RSA key pair has been generated. Please share the public key with Nagad.',
                'type': 'success',
                'sticky': True,
            }
        }
```

**Task 1.2: Provider Views Configuration (2h)**

```xml
<!-- File: smart_dairy_payment_nagad/views/payment_provider_views.xml -->

<?xml version="1.0" encoding="utf-8"?>
<odoo>
    <!-- Inherit Payment Provider Form -->
    <record id="payment_provider_form_nagad" model="ir.ui.view">
        <field name="name">payment.provider.form.nagad</field>
        <field name="model">payment.provider</field>
        <field name="inherit_id" ref="payment.payment_provider_form"/>
        <field name="arch" type="xml">
            <xpath expr="//group[@name='provider_credentials']" position="inside">
                <group string="Nagad Configuration"
                       name="nagad_credentials"
                       invisible="code != 'nagad'">
                    <field name="nagad_mode" widget="radio"/>
                    <field name="nagad_merchant_id"
                           password="True"
                           required="code == 'nagad' and state != 'disabled'"/>
                    <field name="nagad_merchant_number"
                           required="code == 'nagad' and state != 'disabled'"/>
                    <field name="nagad_callback_url" readonly="1"/>
                </group>

                <group string="Nagad Keys"
                       name="nagad_keys"
                       invisible="code != 'nagad'">
                    <field name="nagad_pg_public_key"
                           widget="text"
                           required="code == 'nagad' and state != 'disabled'"/>
                    <field name="nagad_merchant_private_key"
                           widget="text"
                           password="True"
                           required="code == 'nagad' and state != 'disabled'"/>
                    <field name="nagad_merchant_public_key"
                           widget="text"
                           readonly="1"/>
                    <button name="action_generate_keys"
                            string="Generate New Key Pair"
                            type="object"
                            class="btn-secondary"
                            invisible="code != 'nagad'"/>
                </group>
            </xpath>

            <xpath expr="//button[@name='action_toggle_is_published']" position="after">
                <button name="action_nagad_test_connection"
                        string="Test Connection"
                        type="object"
                        class="btn-secondary"
                        invisible="code != 'nagad'"/>
            </xpath>
        </field>
    </record>

    <!-- Provider Data -->
    <record id="payment_provider_nagad" model="payment.provider">
        <field name="name">Nagad</field>
        <field name="code">nagad</field>
        <field name="state">disabled</field>
        <field name="is_published">False</field>
        <field name="nagad_mode">sandbox</field>
        <field name="company_id" ref="base.main_company"/>
    </record>
</odoo>
```

**Task 1.3: Transaction Model Extension (2h)**

```python
# File: smart_dairy_payment_nagad/models/payment_transaction.py

from odoo import models, fields, api
import logging

_logger = logging.getLogger(__name__)


class PaymentTransaction(models.Model):
    _inherit = 'payment.transaction'

    # Nagad-specific fields
    nagad_payment_ref_id = fields.Char(
        string='Nagad Payment Ref ID',
        readonly=True,
        help='Payment reference ID from Nagad'
    )
    nagad_order_id = fields.Char(
        string='Nagad Order ID',
        readonly=True,
        help='Order ID sent to Nagad'
    )
    nagad_challenge = fields.Char(
        string='Nagad Challenge',
        readonly=True,
        groups='base.group_system'
    )
    nagad_issuer_payment_ref = fields.Char(
        string='Issuer Payment Ref',
        readonly=True,
        help='Payment reference from issuer (bank/MFS)'
    )
    nagad_payment_dt = fields.Datetime(
        string='Nagad Payment DateTime',
        readonly=True
    )
    nagad_status = fields.Selection([
        ('initiated', 'Initiated'),
        ('pending', 'Pending'),
        ('success', 'Success'),
        ('failed', 'Failed'),
        ('cancelled', 'Cancelled'),
        ('refunded', 'Refunded'),
    ], string='Nagad Status', readonly=True, default='initiated')

    def _get_specific_rendering_values(self, processing_values):
        """Return Nagad-specific rendering values."""
        res = super()._get_specific_rendering_values(processing_values)
        if self.provider_code != 'nagad':
            return res

        return {
            **res,
            'nagad_merchant_id': self.provider_id.nagad_merchant_id,
            'api_url': self.provider_id._get_nagad_base_url(),
        }

    def _nagad_create_order_id(self):
        """Generate unique order ID for Nagad."""
        import time
        timestamp = int(time.time() * 1000)
        return f"SD{self.id}{timestamp}"

    def _set_nagad_pending(self, payment_ref_id=None):
        """Set transaction to pending with Nagad payment ref."""
        self.ensure_one()
        vals = {
            'nagad_status': 'pending',
        }
        if payment_ref_id:
            vals['nagad_payment_ref_id'] = payment_ref_id
        self.write(vals)
        self._set_pending()

    def _set_nagad_done(self, nagad_data=None):
        """Set transaction as done from Nagad callback."""
        self.ensure_one()
        vals = {
            'nagad_status': 'success',
        }
        if nagad_data:
            vals.update({
                'nagad_issuer_payment_ref': nagad_data.get('issuerPaymentRefNo'),
                'nagad_payment_dt': fields.Datetime.now(),
            })
        self.write(vals)
        self._set_done()

    def _set_nagad_canceled(self, reason=None):
        """Set transaction as cancelled."""
        self.ensure_one()
        self.write({
            'nagad_status': 'cancelled',
            'state_message': reason or 'Payment cancelled by user',
        })
        self._set_canceled()

    def _set_nagad_error(self, error_message):
        """Set transaction as failed."""
        self.ensure_one()
        self.write({
            'nagad_status': 'failed',
            'state_message': error_message,
        })
        self._set_error(error_message)
```

#### Dev 2 (Full-Stack) - 8 Hours

**Task 2.1: RSA Encryption Service (4h)**

```python
# File: smart_dairy_payment_nagad/services/nagad_crypto.py

import json
import base64
from datetime import datetime
from Crypto.PublicKey import RSA
from Crypto.Cipher import PKCS1_v1_5
from Crypto.Signature import pkcs1_15
from Crypto.Hash import SHA256
from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class NagadCryptoService(models.AbstractModel):
    _name = 'nagad.crypto.service'
    _description = 'Nagad Cryptographic Service'

    @api.model
    def encrypt_data(self, data, public_key_pem):
        """
        Encrypt data using Nagad's public key.

        Args:
            data: Dictionary to encrypt
            public_key_pem: Nagad's public key in PEM format

        Returns:
            Base64 encoded encrypted string
        """
        try:
            if isinstance(data, dict):
                data = json.dumps(data)
            if isinstance(data, str):
                data = data.encode('utf-8')

            public_key = RSA.import_key(public_key_pem)
            cipher = PKCS1_v1_5.new(public_key)

            # RSA can only encrypt limited data, typically key_size/8 - 11 bytes
            max_length = (public_key.size_in_bytes() - 11)

            if len(data) > max_length:
                # For larger data, encrypt in chunks (rarely needed for Nagad)
                encrypted_chunks = []
                for i in range(0, len(data), max_length):
                    chunk = data[i:i + max_length]
                    encrypted_chunk = cipher.encrypt(chunk)
                    encrypted_chunks.append(encrypted_chunk)
                encrypted = b''.join(encrypted_chunks)
            else:
                encrypted = cipher.encrypt(data)

            return base64.b64encode(encrypted).decode('utf-8')
        except Exception as e:
            _logger.error(f"Encryption failed: {str(e)}")
            raise

    @api.model
    def decrypt_data(self, encrypted_data, private_key_pem):
        """
        Decrypt data using merchant's private key.

        Args:
            encrypted_data: Base64 encoded encrypted string
            private_key_pem: Merchant's private key in PEM format

        Returns:
            Decrypted data as dictionary
        """
        try:
            encrypted_bytes = base64.b64decode(encrypted_data)
            private_key = RSA.import_key(private_key_pem)
            cipher = PKCS1_v1_5.new(private_key)

            decrypted = cipher.decrypt(encrypted_bytes, sentinel=None)

            if decrypted:
                return json.loads(decrypted.decode('utf-8'))
            return None
        except Exception as e:
            _logger.error(f"Decryption failed: {str(e)}")
            raise

    @api.model
    def generate_signature(self, data, private_key_pem):
        """
        Generate digital signature for data.

        Args:
            data: String data to sign
            private_key_pem: Merchant's private key in PEM format

        Returns:
            Base64 encoded signature
        """
        try:
            if isinstance(data, dict):
                data = json.dumps(data, separators=(',', ':'))
            if isinstance(data, str):
                data = data.encode('utf-8')

            private_key = RSA.import_key(private_key_pem)
            h = SHA256.new(data)
            signature = pkcs1_15.new(private_key).sign(h)

            return base64.b64encode(signature).decode('utf-8')
        except Exception as e:
            _logger.error(f"Signature generation failed: {str(e)}")
            raise

    @api.model
    def verify_signature(self, data, signature, public_key_pem):
        """
        Verify digital signature.

        Args:
            data: Original string data
            signature: Base64 encoded signature
            public_key_pem: Signer's public key in PEM format

        Returns:
            Boolean indicating if signature is valid
        """
        try:
            if isinstance(data, dict):
                data = json.dumps(data, separators=(',', ':'))
            if isinstance(data, str):
                data = data.encode('utf-8')

            public_key = RSA.import_key(public_key_pem)
            h = SHA256.new(data)
            signature_bytes = base64.b64decode(signature)

            pkcs1_15.new(public_key).verify(h, signature_bytes)
            return True
        except Exception as e:
            _logger.warning(f"Signature verification failed: {str(e)}")
            return False

    @api.model
    def generate_sensitive_data(self, merchant_id, order_id, challenge, amount=None):
        """
        Generate sensitive data payload for Nagad API.

        Args:
            merchant_id: Nagad Merchant ID
            order_id: Unique order identifier
            challenge: Challenge from initialize response
            amount: Payment amount (for complete payment)

        Returns:
            Dictionary with sensitive data
        """
        timestamp = datetime.now().strftime('%Y%m%d%H%M%S')

        sensitive_data = {
            'merchantId': merchant_id,
            'orderId': order_id,
            'currencyCode': '050',  # BDT currency code
            'challenge': challenge,
            'datetime': timestamp,
        }

        if amount:
            sensitive_data['amount'] = str(amount)

        return sensitive_data
```

**Task 2.2: Date/Time Utilities (2h)**

```python
# File: smart_dairy_payment_nagad/services/nagad_utils.py

from datetime import datetime, timezone
import hashlib
import secrets
from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class NagadUtils(models.AbstractModel):
    _name = 'nagad.utils'
    _description = 'Nagad Utility Service'

    @api.model
    def get_timestamp(self):
        """Get current timestamp in Nagad format (YYYYMMDDHHmmss)."""
        return datetime.now().strftime('%Y%m%d%H%M%S')

    @api.model
    def get_iso_timestamp(self):
        """Get ISO 8601 formatted timestamp."""
        return datetime.now(timezone.utc).isoformat()

    @api.model
    def generate_order_id(self, prefix='SD', length=12):
        """
        Generate unique order ID.

        Args:
            prefix: Order ID prefix
            length: Total length including prefix

        Returns:
            Unique order ID string
        """
        timestamp = datetime.now().strftime('%y%m%d%H%M%S')
        random_part = secrets.token_hex(3).upper()
        order_id = f"{prefix}{timestamp}{random_part}"
        return order_id[:length] if len(order_id) > length else order_id

    @api.model
    def generate_challenge(self, length=32):
        """Generate random challenge string."""
        return secrets.token_urlsafe(length)

    @api.model
    def normalize_phone(self, phone):
        """
        Normalize Bangladesh phone number.

        Args:
            phone: Phone number in any format

        Returns:
            Phone number in +880XXXXXXXXXX format
        """
        if not phone:
            return None

        # Remove all non-digit characters
        digits = ''.join(filter(str.isdigit, phone))

        # Handle different formats
        if digits.startswith('880'):
            return f"+{digits}"
        elif digits.startswith('0'):
            return f"+880{digits[1:]}"
        elif len(digits) == 10:
            return f"+880{digits}"

        return f"+880{digits}"

    @api.model
    def validate_nagad_number(self, number):
        """
        Validate if number is a valid Nagad number.

        Args:
            number: Phone number to validate

        Returns:
            Boolean indicating validity
        """
        normalized = self.normalize_phone(number)
        if not normalized:
            return False

        # Remove +880 prefix
        digits = normalized[4:]

        # Check length (10 digits after country code)
        if len(digits) != 10:
            return False

        # Valid Nagad prefixes (operators)
        valid_prefixes = ['17', '13', '14', '18', '19', '16', '15']
        return any(digits.startswith(p) for p in valid_prefixes)

    @api.model
    def calculate_hash(self, data):
        """Calculate SHA256 hash of data."""
        if isinstance(data, dict):
            import json
            data = json.dumps(data, sort_keys=True)
        if isinstance(data, str):
            data = data.encode('utf-8')
        return hashlib.sha256(data).hexdigest()

    @api.model
    def mask_sensitive_data(self, data, fields_to_mask=None):
        """
        Mask sensitive data for logging.

        Args:
            data: Dictionary containing sensitive data
            fields_to_mask: List of field names to mask

        Returns:
            Dictionary with masked values
        """
        if fields_to_mask is None:
            fields_to_mask = [
                'password', 'private_key', 'secret', 'token',
                'signature', 'sensitiveData', 'encryptedData'
            ]

        masked = {}
        for key, value in data.items():
            if any(f.lower() in key.lower() for f in fields_to_mask):
                if isinstance(value, str) and len(value) > 8:
                    masked[key] = f"{value[:4]}...{value[-4:]}"
                else:
                    masked[key] = '****'
            elif isinstance(value, dict):
                masked[key] = self.mask_sensitive_data(value, fields_to_mask)
            else:
                masked[key] = value

        return masked
```

**Task 2.3: API Logger (2h)**

```python
# File: smart_dairy_payment_nagad/services/nagad_logger.py

from odoo import models, fields, api
import json
import logging

_logger = logging.getLogger(__name__)


class NagadAPILog(models.Model):
    _name = 'nagad.api.log'
    _description = 'Nagad API Request/Response Log'
    _order = 'create_date desc'

    transaction_id = fields.Many2one(
        'payment.transaction',
        string='Transaction',
        ondelete='cascade'
    )
    provider_id = fields.Many2one(
        'payment.provider',
        string='Provider'
    )
    endpoint = fields.Char(string='API Endpoint')
    method = fields.Selection([
        ('GET', 'GET'),
        ('POST', 'POST'),
    ], string='Method')
    request_data = fields.Text(string='Request Data')
    response_data = fields.Text(string='Response Data')
    response_code = fields.Integer(string='Response Code')
    duration_ms = fields.Integer(string='Duration (ms)')
    status = fields.Selection([
        ('success', 'Success'),
        ('error', 'Error'),
    ], string='Status')
    error_message = fields.Text(string='Error Message')

    @api.model
    def log_request(self, endpoint, method, request_data, response_data,
                    response_code, duration_ms, transaction=None, provider=None,
                    error_message=None):
        """Log API request and response."""
        # Mask sensitive data before logging
        utils = self.env['nagad.utils']

        masked_request = utils.mask_sensitive_data(request_data) if isinstance(request_data, dict) else request_data
        masked_response = utils.mask_sensitive_data(response_data) if isinstance(response_data, dict) else response_data

        return self.create({
            'transaction_id': transaction.id if transaction else False,
            'provider_id': provider.id if provider else False,
            'endpoint': endpoint,
            'method': method,
            'request_data': json.dumps(masked_request, indent=2) if isinstance(masked_request, dict) else str(masked_request),
            'response_data': json.dumps(masked_response, indent=2) if isinstance(masked_response, dict) else str(masked_response),
            'response_code': response_code,
            'duration_ms': duration_ms,
            'status': 'success' if 200 <= response_code < 300 else 'error',
            'error_message': error_message,
        })
```

#### Dev 3 (Frontend/Mobile Lead) - 8 Hours

**Task 3.1: Provider Selection Integration (4h)**

```javascript
/** @odoo-module **/
// File: smart_dairy_payment_nagad/static/src/js/payment_form_nagad.js

import { PaymentForm } from "@payment/js/payment_form";
import { patch } from "@web/core/utils/patch";

patch(PaymentForm.prototype, {
    /**
     * Override to add Nagad-specific behavior
     */
    async _prepareInlineForm(providerId, providerCode, paymentOptionId, flow) {
        if (providerCode !== 'nagad') {
            return super._prepareInlineForm(...arguments);
        }

        // Show Nagad inline form
        this._showNagadForm(providerId, paymentOptionId);
    },

    _showNagadForm(providerId, paymentOptionId) {
        const formContainer = document.querySelector(
            `[data-provider-id="${providerId}"] .o_payment_inline_form`
        );

        if (!formContainer) return;

        formContainer.innerHTML = `
            <div class="nagad-inline-form p-3">
                <div class="mb-3">
                    <label class="form-label">Nagad Account Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-danger text-white">+880</span>
                        <input type="tel"
                               class="form-control nagad-number-input"
                               placeholder="1XXXXXXXXX"
                               maxlength="10"
                               pattern="[0-9]*"
                               data-provider-id="${providerId}"
                               data-payment-option-id="${paymentOptionId}"/>
                    </div>
                    <small class="text-muted">Enter your 10-digit Nagad number</small>
                </div>
            </div>
        `;

        // Add input handler
        const input = formContainer.querySelector('.nagad-number-input');
        input.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    },

    async _submitForm(ev) {
        const providerCode = this.paymentContext.providerCode;

        if (providerCode === 'nagad') {
            return this._submitNagadPayment(ev);
        }

        return super._submitForm(...arguments);
    },

    async _submitNagadPayment(ev) {
        ev.preventDefault();

        const input = document.querySelector('.nagad-number-input');
        const nagadNumber = input?.value;

        if (!nagadNumber || nagadNumber.length !== 10) {
            this._displayError("Please enter a valid 10-digit Nagad number");
            return;
        }

        // Validate Nagad number prefix
        const validPrefixes = ['17', '13', '14', '18', '19', '16', '15'];
        if (!validPrefixes.some(p => nagadNumber.startsWith(p))) {
            this._displayError("Invalid Nagad number. Please check and try again.");
            return;
        }

        // Add Nagad number to form data
        this.paymentContext.nagadNumber = `+880${nagadNumber}`;

        // Continue with normal payment flow
        return super._submitForm(ev);
    },
});
```

**Task 3.2: Payment Status Page (2h)**

```javascript
/** @odoo-module **/
// File: smart_dairy_payment_nagad/static/src/js/nagad_status.js

import { Component, useState, onMounted, onWillUnmount } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";

export class NagadPaymentStatus extends Component {
    static template = "smart_dairy_payment_nagad.PaymentStatus";
    static props = {
        transactionReference: { type: String, required: true },
        paymentRefId: { type: String, optional: true },
    };

    setup() {
        this.rpc = useService("rpc");

        this.state = useState({
            status: 'checking',
            message: 'Checking payment status...',
            transaction: null,
            pollCount: 0,
            maxPolls: 30,
        });

        this.pollInterval = null;

        onMounted(() => {
            this.startPolling();
        });

        onWillUnmount(() => {
            this.stopPolling();
        });
    }

    startPolling() {
        this.checkStatus();
        this.pollInterval = setInterval(() => {
            if (this.state.pollCount >= this.state.maxPolls) {
                this.stopPolling();
                this.state.status = 'timeout';
                this.state.message = 'Payment verification timed out. Please check your order status.';
                return;
            }
            this.checkStatus();
        }, 3000); // Poll every 3 seconds
    }

    stopPolling() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
            this.pollInterval = null;
        }
    }

    async checkStatus() {
        this.state.pollCount++;

        try {
            const result = await this.rpc('/payment/nagad/status', {
                reference: this.props.transactionReference,
            });

            if (result.status === 'done') {
                this.stopPolling();
                this.state.status = 'success';
                this.state.message = 'Payment successful!';
                this.state.transaction = result.transaction;
            } else if (result.status === 'error' || result.status === 'cancel') {
                this.stopPolling();
                this.state.status = 'failed';
                this.state.message = result.message || 'Payment failed';
            }
            // If pending, continue polling
        } catch (error) {
            console.error('Status check failed:', error);
        }
    }

    getStatusIcon() {
        const icons = {
            checking: 'fa-spinner fa-spin',
            success: 'fa-check-circle text-success',
            failed: 'fa-times-circle text-danger',
            timeout: 'fa-clock text-warning',
        };
        return icons[this.state.status] || 'fa-question-circle';
    }

    redirectToOrders() {
        window.location.href = '/my/orders';
    }

    retryPayment() {
        window.location.href = '/shop/cart';
    }
}
```

**Task 3.3: Status Template (2h)**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_payment_nagad/static/src/xml/nagad_status_templates.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_payment_nagad.PaymentStatus">
        <div class="nagad-payment-status text-center py-5">
            <div class="status-icon mb-4">
                <i t-att-class="'fa fa-4x ' + getStatusIcon()"/>
            </div>

            <h3 class="mb-3" t-esc="state.message"/>

            <t t-if="state.status === 'checking'">
                <p class="text-muted">
                    Please wait while we verify your payment with Nagad...
                </p>
                <div class="progress mx-auto" style="max-width: 300px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                         t-att-style="'width: ' + (state.pollCount / state.maxPolls * 100) + '%'"/>
                </div>
            </t>

            <t t-if="state.status === 'success'">
                <div class="alert alert-success mx-auto" style="max-width: 400px;">
                    <p class="mb-1">
                        <strong>Transaction Reference:</strong>
                        <t t-esc="props.transactionReference"/>
                    </p>
                    <t t-if="state.transaction">
                        <p class="mb-0">
                            <strong>Amount Paid:</strong>
                            BDT <t t-esc="state.transaction.amount"/>
                        </p>
                    </t>
                </div>
                <button class="btn btn-success btn-lg mt-3" t-on-click="redirectToOrders">
                    <i class="fa fa-shopping-bag me-2"/>
                    View My Orders
                </button>
            </t>

            <t t-if="state.status === 'failed'">
                <div class="alert alert-danger mx-auto" style="max-width: 400px;">
                    <p class="mb-0">Your payment could not be processed.</p>
                </div>
                <button class="btn btn-danger btn-lg mt-3" t-on-click="retryPayment">
                    <i class="fa fa-refresh me-2"/>
                    Try Again
                </button>
            </t>

            <t t-if="state.status === 'timeout'">
                <div class="alert alert-warning mx-auto" style="max-width: 400px;">
                    <p class="mb-0">
                        We couldn't confirm your payment status.
                        Please check your Nagad app or order history.
                    </p>
                </div>
                <div class="mt-3">
                    <button class="btn btn-outline-secondary me-2" t-on-click="redirectToOrders">
                        Check Order Status
                    </button>
                    <button class="btn btn-warning" t-on-click="() => { state.pollCount = 0; startPolling(); }">
                        Retry Verification
                    </button>
                </div>
            </t>
        </div>
    </t>
</templates>
```

#### End of Day 462 Deliverables

- [ ] Payment provider model completed
- [ ] Provider views configured
- [ ] Transaction model extended
- [ ] RSA encryption service implemented
- [ ] Utility functions created
- [ ] API logger implemented
- [ ] Provider selection UI integrated
- [ ] Payment status component created

---

### Day 463: Initialize Checkout API

#### Day Objective
Implement Nagad Initialize Checkout API with encryption and signature generation.

#### Dev 1 (Backend Lead) - 8 Hours

**Task 1.1: Initialize Checkout Service (5h)**

```python
# File: smart_dairy_payment_nagad/services/nagad_checkout.py

import requests
import json
from datetime import datetime
from odoo import models, api
from odoo.exceptions import UserError
import logging

_logger = logging.getLogger(__name__)


class NagadCheckoutService(models.AbstractModel):
    _name = 'nagad.checkout.service'
    _description = 'Nagad Checkout Service'

    @api.model
    def initialize_checkout(self, provider, transaction, callback_url):
        """
        Initialize Nagad checkout process.

        Args:
            provider: payment.provider record
            transaction: payment.transaction record
            callback_url: URL for Nagad callback

        Returns:
            dict with paymentReferenceId and redirect URL
        """
        crypto = self.env['nagad.crypto.service']
        utils = self.env['nagad.utils']

        # Generate order ID
        order_id = utils.generate_order_id(prefix='SD')
        transaction.write({'nagad_order_id': order_id})

        # Get current timestamp
        timestamp = utils.get_timestamp()

        # Prepare sensitive data for encryption
        sensitive_data = {
            'merchantId': provider.nagad_merchant_id,
            'datetime': timestamp,
            'orderId': order_id,
            'challenge': utils.generate_challenge(40),
        }

        # Encrypt sensitive data with Nagad's public key
        encrypted_data = crypto.encrypt_data(
            sensitive_data,
            provider.nagad_pg_public_key
        )

        # Generate signature with merchant's private key
        signature = crypto.generate_signature(
            sensitive_data,
            provider.nagad_merchant_private_key
        )

        # Prepare request payload
        payload = {
            'dateTime': timestamp,
            'sensitiveData': encrypted_data,
            'signature': signature,
        }

        # Construct API URL
        base_url = provider._get_nagad_base_url()
        endpoint = f"/check-out/initialize/{provider.nagad_merchant_id}/{order_id}"
        url = f"{base_url}{endpoint}"

        # Make API request
        headers = {
            'Content-Type': 'application/json',
            'X-KM-Api-Version': 'v-0.2.0',
            'X-KM-IP-V4': self._get_client_ip(),
            'X-KM-Client-Type': 'PC_WEB',
        }

        start_time = datetime.now()

        try:
            _logger.info(f"Nagad Initialize: Calling {url}")
            response = requests.post(
                url,
                json=payload,
                headers=headers,
                timeout=30
            )

            duration_ms = int((datetime.now() - start_time).total_seconds() * 1000)
            response_data = response.json()

            # Log the request
            self.env['nagad.api.log'].log_request(
                endpoint=endpoint,
                method='POST',
                request_data=payload,
                response_data=response_data,
                response_code=response.status_code,
                duration_ms=duration_ms,
                transaction=transaction,
                provider=provider,
            )

            if response.status_code != 200:
                raise UserError(f"Nagad API error: {response_data.get('message', 'Unknown error')}")

            # Decrypt response sensitive data
            if 'sensitiveData' in response_data:
                decrypted_data = crypto.decrypt_data(
                    response_data['sensitiveData'],
                    provider.nagad_merchant_private_key
                )

                # Store challenge for complete payment
                if decrypted_data and 'challenge' in decrypted_data:
                    transaction.write({
                        'nagad_challenge': decrypted_data['challenge'],
                        'nagad_payment_ref_id': decrypted_data.get('paymentReferenceId'),
                    })

                return {
                    'success': True,
                    'payment_ref_id': decrypted_data.get('paymentReferenceId'),
                    'challenge': decrypted_data.get('challenge'),
                    'merchant_callback_url': decrypted_data.get('merchantCallbackURL'),
                }
            else:
                raise UserError("Invalid response from Nagad: Missing sensitiveData")

        except requests.exceptions.Timeout:
            _logger.error("Nagad Initialize: Request timeout")
            raise UserError("Payment gateway timeout. Please try again.")
        except requests.exceptions.RequestException as e:
            _logger.error(f"Nagad Initialize: Request failed - {str(e)}")
            raise UserError(f"Payment gateway error: {str(e)}")
        except json.JSONDecodeError:
            _logger.error("Nagad Initialize: Invalid JSON response")
            raise UserError("Invalid response from payment gateway")

    def _get_client_ip(self):
        """Get client IP address."""
        # In production, get from request
        # For now, return a placeholder
        return '127.0.0.1'
```

**Task 1.2: Complete Payment Service (3h)**

```python
# File: smart_dairy_payment_nagad/services/nagad_checkout.py (continued)

    @api.model
    def complete_payment(self, provider, transaction, callback_url):
        """
        Complete Nagad payment after initialization.

        Args:
            provider: payment.provider record
            transaction: payment.transaction record
            callback_url: Merchant callback URL

        Returns:
            dict with redirect URL for payment completion
        """
        crypto = self.env['nagad.crypto.service']
        utils = self.env['nagad.utils']

        if not transaction.nagad_payment_ref_id:
            raise UserError("Payment not initialized. Please start again.")

        if not transaction.nagad_challenge:
            raise UserError("Invalid payment session. Please start again.")

        timestamp = utils.get_timestamp()

        # Prepare sensitive data
        sensitive_data = {
            'merchantId': provider.nagad_merchant_id,
            'orderId': transaction.nagad_order_id,
            'currencyCode': '050',  # BDT
            'amount': str(transaction.amount),
            'challenge': transaction.nagad_challenge,
        }

        # Add additional order info
        additional_info = self._get_additional_merchant_info(transaction)

        # Encrypt sensitive data
        encrypted_data = crypto.encrypt_data(
            sensitive_data,
            provider.nagad_pg_public_key
        )

        # Generate signature
        signature = crypto.generate_signature(
            sensitive_data,
            provider.nagad_merchant_private_key
        )

        # Prepare payload
        payload = {
            'sensitiveData': encrypted_data,
            'signature': signature,
            'merchantCallbackURL': callback_url,
            'additionalMerchantInfo': additional_info,
        }

        # Construct API URL
        base_url = provider._get_nagad_base_url()
        endpoint = f"/check-out/complete/{transaction.nagad_payment_ref_id}"
        url = f"{base_url}{endpoint}"

        headers = {
            'Content-Type': 'application/json',
            'X-KM-Api-Version': 'v-0.2.0',
            'X-KM-IP-V4': self._get_client_ip(),
            'X-KM-Client-Type': 'PC_WEB',
        }

        start_time = datetime.now()

        try:
            _logger.info(f"Nagad Complete: Calling {url}")
            response = requests.post(
                url,
                json=payload,
                headers=headers,
                timeout=30
            )

            duration_ms = int((datetime.now() - start_time).total_seconds() * 1000)
            response_data = response.json()

            # Log request
            self.env['nagad.api.log'].log_request(
                endpoint=endpoint,
                method='POST',
                request_data=payload,
                response_data=response_data,
                response_code=response.status_code,
                duration_ms=duration_ms,
                transaction=transaction,
                provider=provider,
            )

            if response.status_code == 200 and response_data.get('status') == 'Success':
                # Get redirect URL for customer
                callback_url = response_data.get('callBackUrl')

                if callback_url:
                    transaction._set_nagad_pending(transaction.nagad_payment_ref_id)
                    return {
                        'success': True,
                        'redirect_url': callback_url,
                        'payment_ref_id': transaction.nagad_payment_ref_id,
                    }

            error_msg = response_data.get('message', 'Payment completion failed')
            _logger.error(f"Nagad Complete failed: {error_msg}")
            transaction._set_nagad_error(error_msg)

            return {
                'success': False,
                'error': error_msg,
            }

        except Exception as e:
            _logger.error(f"Nagad Complete error: {str(e)}")
            transaction._set_nagad_error(str(e))
            raise UserError(f"Payment error: {str(e)}")

    def _get_additional_merchant_info(self, transaction):
        """Get additional merchant info for Nagad."""
        return json.dumps({
            'reference': transaction.reference,
            'source': 'Smart Dairy Portal',
        })
```

#### Dev 2 (Full-Stack) - 8 Hours

**Task 2.1: HTTP Controller for Initialize (4h)**

```python
# File: smart_dairy_payment_nagad/controllers/main.py

import json
import logging
from odoo import http
from odoo.http import request

_logger = logging.getLogger(__name__)


class NagadController(http.Controller):

    @http.route('/payment/nagad/initialize', type='json', auth='public',
                methods=['POST'], csrf=False)
    def nagad_initialize(self, provider_id, amount, reference, nagad_number=None,
                         partner_id=None, **kwargs):
        """
        Initialize Nagad payment.

        Args:
            provider_id: ID of the Nagad payment provider
            amount: Payment amount in BDT
            reference: Order/transaction reference
            nagad_number: Customer's Nagad number (optional)
            partner_id: Customer partner ID (optional)

        Returns:
            dict with success status and redirect URL or error
        """
        try:
            provider = request.env['payment.provider'].sudo().browse(provider_id)
            if not provider.exists() or provider.code != 'nagad':
                return {'success': False, 'error': 'Invalid payment provider'}

            # Get or create transaction
            transaction = self._get_or_create_transaction(
                provider, amount, reference, partner_id
            )

            if not transaction:
                return {'success': False, 'error': 'Failed to create transaction'}

            # Get callback URL
            base_url = request.env['ir.config_parameter'].sudo().get_param('web.base.url')
            callback_url = f"{base_url}/payment/nagad/callback"

            # Initialize checkout
            checkout_service = request.env['nagad.checkout.service'].sudo()
            init_result = checkout_service.initialize_checkout(
                provider, transaction, callback_url
            )

            if not init_result.get('success'):
                return {'success': False, 'error': init_result.get('error', 'Initialization failed')}

            # Complete payment to get redirect URL
            complete_result = checkout_service.complete_payment(
                provider, transaction, callback_url
            )

            return complete_result

        except Exception as e:
            _logger.error(f"Nagad initialize error: {str(e)}")
            return {'success': False, 'error': str(e)}

    def _get_or_create_transaction(self, provider, amount, reference, partner_id):
        """Get existing or create new payment transaction."""
        Transaction = request.env['payment.transaction'].sudo()

        # Check for existing transaction
        existing = Transaction.search([
            ('reference', '=', reference),
            ('provider_id', '=', provider.id),
            ('state', 'in', ['draft', 'pending']),
        ], limit=1)

        if existing:
            return existing

        # Create new transaction
        vals = {
            'provider_id': provider.id,
            'amount': amount,
            'currency_id': request.env.ref('base.BDT').id,
            'reference': reference,
            'partner_id': partner_id or request.env.user.partner_id.id,
            'operation': 'online_direct',
        }

        return Transaction.create(vals)

    @http.route('/payment/nagad/callback', type='http', auth='public',
                methods=['GET', 'POST'], csrf=False)
    def nagad_callback(self, **post):
        """
        Handle Nagad payment callback.

        Nagad redirects customer here after payment completion.
        """
        _logger.info(f"Nagad callback received: {post}")

        try:
            # Extract parameters from callback
            payment_ref_id = post.get('payment_ref_id') or post.get('paymentRefId')
            status = post.get('status')
            order_id = post.get('order_id') or post.get('orderId')

            if not payment_ref_id:
                return request.redirect('/payment/status?error=missing_reference')

            # Find transaction
            transaction = request.env['payment.transaction'].sudo().search([
                ('nagad_payment_ref_id', '=', payment_ref_id),
            ], limit=1)

            if not transaction:
                _logger.error(f"Nagad callback: Transaction not found for {payment_ref_id}")
                return request.redirect('/payment/status?error=transaction_not_found')

            # Verify payment with Nagad
            verification_service = request.env['nagad.verification.service'].sudo()
            verify_result = verification_service.verify_payment(
                transaction.provider_id, payment_ref_id
            )

            if verify_result.get('success'):
                # Update transaction
                transaction._set_nagad_done(verify_result.get('data'))
                return request.redirect(
                    f'/payment/status?reference={transaction.reference}&status=success'
                )
            else:
                transaction._set_nagad_error(verify_result.get('error', 'Verification failed'))
                return request.redirect(
                    f'/payment/status?reference={transaction.reference}&status=failed'
                )

        except Exception as e:
            _logger.error(f"Nagad callback error: {str(e)}")
            return request.redirect('/payment/status?error=processing_error')

    @http.route('/payment/nagad/status', type='json', auth='public', methods=['POST'])
    def nagad_status(self, reference=None, **kwargs):
        """Check payment status by reference."""
        if not reference:
            return {'status': 'error', 'message': 'Missing reference'}

        transaction = request.env['payment.transaction'].sudo().search([
            ('reference', '=', reference),
        ], limit=1)

        if not transaction:
            return {'status': 'error', 'message': 'Transaction not found'}

        return {
            'status': transaction.state,
            'nagad_status': transaction.nagad_status,
            'message': transaction.state_message,
            'transaction': {
                'reference': transaction.reference,
                'amount': transaction.amount,
                'currency': transaction.currency_id.name,
            } if transaction.state == 'done' else None,
        }
```

**Task 2.2: Verification Service (4h)**

```python
# File: smart_dairy_payment_nagad/services/nagad_verification.py

import requests
from datetime import datetime
from odoo import models, api
import logging

_logger = logging.getLogger(__name__)


class NagadVerificationService(models.AbstractModel):
    _name = 'nagad.verification.service'
    _description = 'Nagad Payment Verification Service'

    @api.model
    def verify_payment(self, provider, payment_ref_id):
        """
        Verify payment status with Nagad.

        Args:
            provider: payment.provider record
            payment_ref_id: Nagad payment reference ID

        Returns:
            dict with verification result
        """
        base_url = provider._get_nagad_base_url()
        endpoint = f"/verify/payment/{payment_ref_id}"
        url = f"{base_url}{endpoint}"

        headers = {
            'Content-Type': 'application/json',
            'X-KM-Api-Version': 'v-0.2.0',
            'X-KM-IP-V4': '127.0.0.1',
            'X-KM-Client-Type': 'PC_WEB',
        }

        start_time = datetime.now()

        try:
            _logger.info(f"Nagad Verify: Calling {url}")
            response = requests.get(url, headers=headers, timeout=30)

            duration_ms = int((datetime.now() - start_time).total_seconds() * 1000)
            response_data = response.json()

            # Log request
            self.env['nagad.api.log'].log_request(
                endpoint=endpoint,
                method='GET',
                request_data={'paymentRefId': payment_ref_id},
                response_data=response_data,
                response_code=response.status_code,
                duration_ms=duration_ms,
                provider=provider,
            )

            if response.status_code == 200:
                status = response_data.get('status')

                if status == 'Success':
                    return {
                        'success': True,
                        'status': 'success',
                        'data': {
                            'paymentRefId': response_data.get('paymentRefId'),
                            'issuerPaymentRefNo': response_data.get('issuerPaymentRefNo'),
                            'amount': response_data.get('amount'),
                            'merchantId': response_data.get('merchantId'),
                            'orderId': response_data.get('orderId'),
                            'paymentDateTime': response_data.get('paymentDateTime'),
                        }
                    }
                elif status == 'Pending':
                    return {
                        'success': False,
                        'status': 'pending',
                        'message': 'Payment is still pending',
                    }
                else:
                    return {
                        'success': False,
                        'status': 'failed',
                        'error': response_data.get('message', 'Payment verification failed'),
                    }
            else:
                return {
                    'success': False,
                    'status': 'error',
                    'error': f"API error: {response.status_code}",
                }

        except Exception as e:
            _logger.error(f"Nagad verify error: {str(e)}")
            return {
                'success': False,
                'status': 'error',
                'error': str(e),
            }

    @api.model
    def verify_and_update_transaction(self, transaction):
        """
        Verify payment and update transaction status.

        Args:
            transaction: payment.transaction record

        Returns:
            Boolean indicating success
        """
        if not transaction.nagad_payment_ref_id:
            _logger.warning(f"Cannot verify transaction {transaction.id}: No payment ref ID")
            return False

        result = self.verify_payment(
            transaction.provider_id,
            transaction.nagad_payment_ref_id
        )

        if result.get('success'):
            transaction._set_nagad_done(result.get('data'))
            return True
        elif result.get('status') == 'pending':
            # Still pending, don't update
            return False
        else:
            transaction._set_nagad_error(result.get('error', 'Verification failed'))
            return False
```

#### Dev 3 (Frontend/Mobile Lead) - 8 Hours

**Task 3.1: Checkout Integration (4h)**

```javascript
/** @odoo-module **/
// File: smart_dairy_payment_nagad/static/src/js/checkout_nagad.js

import { Component, useState, onMounted } from "@odoo/owl";
import { useService } from "@web/core/utils/hooks";
import { _t } from "@web/core/l10n/translation";

export class NagadCheckoutButton extends Component {
    static template = "smart_dairy_payment_nagad.CheckoutButton";
    static props = {
        orderId: { type: Number, required: true },
        amount: { type: Number, required: true },
        providerId: { type: Number, required: true },
    };

    setup() {
        this.rpc = useService("rpc");
        this.notification = useService("notification");

        this.state = useState({
            loading: false,
            showNumberInput: false,
            nagadNumber: '',
            error: null,
        });
    }

    toggleNumberInput() {
        this.state.showNumberInput = !this.state.showNumberInput;
        this.state.error = null;
    }

    validateNumber() {
        const num = this.state.nagadNumber;
        if (num.length !== 10) {
            return _t("Number must be 10 digits");
        }
        const validPrefixes = ['17', '13', '14', '18', '19', '16', '15'];
        if (!validPrefixes.some(p => num.startsWith(p))) {
            return _t("Invalid Nagad number");
        }
        return null;
    }

    async initiatePayment() {
        const validationError = this.validateNumber();
        if (validationError) {
            this.state.error = validationError;
            return;
        }

        this.state.loading = true;
        this.state.error = null;

        try {
            const result = await this.rpc('/payment/nagad/initialize', {
                provider_id: this.props.providerId,
                amount: this.props.amount,
                reference: `SO-${this.props.orderId}-${Date.now()}`,
                nagad_number: `+880${this.state.nagadNumber}`,
            });

            if (result.success && result.redirect_url) {
                // Redirect to Nagad payment page
                window.location.href = result.redirect_url;
            } else {
                this.state.error = result.error || _t("Payment initialization failed");
                this.notification.add(this.state.error, { type: "danger" });
            }
        } catch (error) {
            this.state.error = error.message || _t("An error occurred");
            this.notification.add(this.state.error, { type: "danger" });
        } finally {
            this.state.loading = false;
        }
    }
}
```

**Task 3.2: Checkout Button Template (2h)**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!-- File: smart_dairy_payment_nagad/static/src/xml/checkout_templates.xml -->

<templates xml:space="preserve">
    <t t-name="smart_dairy_payment_nagad.CheckoutButton">
        <div class="nagad-checkout-widget">
            <t t-if="!state.showNumberInput">
                <button type="button"
                        class="btn btn-danger btn-lg w-100 d-flex align-items-center justify-content-center"
                        t-on-click="toggleNumberInput">
                    <img src="/smart_dairy_payment_nagad/static/src/img/nagad-icon.png"
                         alt="Nagad"
                         style="height: 24px; margin-right: 10px;"/>
                    Pay with Nagad
                </button>
            </t>

            <t t-if="state.showNumberInput">
                <div class="nagad-payment-card border rounded p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold">Pay with Nagad</span>
                        <button type="button"
                                class="btn-close"
                                t-on-click="toggleNumberInput"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Nagad Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-danger text-white border-danger">
                                +880
                            </span>
                            <input type="tel"
                                   class="form-control"
                                   t-att-class="{'is-invalid': state.error}"
                                   t-model="state.nagadNumber"
                                   placeholder="1XXXXXXXXX"
                                   maxlength="10"
                                   t-on-input="(e) => { state.nagadNumber = e.target.value.replace(/\\D/g, ''); state.error = null; }"/>
                        </div>
                        <t t-if="state.error">
                            <div class="invalid-feedback d-block">
                                <t t-esc="state.error"/>
                            </div>
                        </t>
                    </div>

                    <div class="d-grid">
                        <button type="button"
                                class="btn btn-danger"
                                t-att-disabled="state.loading || state.nagadNumber.length !== 10"
                                t-on-click="initiatePayment">
                            <t t-if="state.loading">
                                <span class="spinner-border spinner-border-sm me-2"/>
                                Processing...
                            </t>
                            <t t-else="">
                                Pay BDT <t t-esc="props.amount.toLocaleString()"/>
                            </t>
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fa fa-lock me-1"/>
                            Secured by Nagad
                        </small>
                    </div>
                </div>
            </t>
        </div>
    </t>
</templates>
```

**Task 3.3: Mobile-Responsive Testing (2h)**

```css
/* File: smart_dairy_payment_nagad/static/src/css/nagad_checkout.css */

.nagad-checkout-widget {
    max-width: 100%;
}

.nagad-payment-card {
    background: linear-gradient(to bottom, #fff, #fafafa);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.nagad-payment-card .input-group-text {
    font-weight: 600;
    min-width: 60px;
    justify-content: center;
}

.nagad-payment-card .form-control:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
}

.nagad-payment-card .btn-danger {
    background: linear-gradient(to bottom, #dc3545, #c82333);
    border: none;
    padding: 12px;
    font-weight: 600;
}

.nagad-payment-card .btn-danger:hover:not(:disabled) {
    background: linear-gradient(to bottom, #c82333, #bd2130);
}

/* Mobile optimizations */
@media (max-width: 576px) {
    .nagad-payment-card {
        border-radius: 12px;
    }

    .nagad-payment-card .form-control {
        font-size: 16px; /* Prevents iOS zoom */
        padding: 12px;
    }

    .nagad-payment-card .btn-danger {
        padding: 14px;
        font-size: 16px;
    }
}

/* Spinner animation */
.spinner-border {
    animation: spinner-border 0.75s linear infinite;
}

@keyframes spinner-border {
    to { transform: rotate(360deg); }
}
```

#### End of Day 463 Deliverables

- [ ] Initialize checkout service completed
- [ ] Complete payment service implemented
- [ ] HTTP controllers created
- [ ] Verification service implemented
- [ ] Checkout UI integration completed
- [ ] Mobile-responsive styling done

---

### Day 464-465: Payment Flow & Verification

*(Continuing implementation of payment execution, webhook handling, and transaction management)*

### Day 466-467: Refund Processing & Webhooks

### Day 468: UI Components & Testing

### Day 469: Integration Testing

### Day 470: Documentation & Final Testing

---

## 6. Technical Specifications

### 6.1 API Endpoints Summary

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/payment/nagad/initialize` | POST | Public | Initialize payment |
| `/payment/nagad/callback` | GET/POST | Public | Nagad callback handler |
| `/payment/nagad/status` | POST | Public | Check payment status |
| `/payment/nagad/webhook` | POST | Public | Webhook receiver |
| `/payment/nagad/refund` | POST | User | Initiate refund |

### 6.2 Encryption Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    INITIALIZE PAYMENT                        │
├─────────────────────────────────────────────────────────────┤
│  1. Generate sensitive data (merchantId, orderId, etc.)     │
│  2. Encrypt with Nagad's PUBLIC key                         │
│  3. Sign with Merchant's PRIVATE key                        │
│  4. Send to Nagad API                                       │
│  5. Receive response with encrypted sensitive data          │
│  6. Decrypt with Merchant's PRIVATE key                     │
│  7. Extract challenge for complete payment                  │
└─────────────────────────────────────────────────────────────┘
```

### 6.3 Payment States

| State | Description | Nagad Status |
|-------|-------------|--------------|
| `draft` | Transaction created | - |
| `pending` | Waiting for payment | `initiated`, `pending` |
| `done` | Payment successful | `success` |
| `cancel` | User cancelled | `cancelled` |
| `error` | Payment failed | `failed` |

---

## 7. Database Schema

### 7.1 Extended Fields on payment.transaction

```sql
-- Nagad-specific fields added to payment_transaction table
ALTER TABLE payment_transaction ADD COLUMN nagad_payment_ref_id VARCHAR(64);
ALTER TABLE payment_transaction ADD COLUMN nagad_order_id VARCHAR(32);
ALTER TABLE payment_transaction ADD COLUMN nagad_challenge VARCHAR(128);
ALTER TABLE payment_transaction ADD COLUMN nagad_issuer_payment_ref VARCHAR(64);
ALTER TABLE payment_transaction ADD COLUMN nagad_payment_dt TIMESTAMP;
ALTER TABLE payment_transaction ADD COLUMN nagad_status VARCHAR(20);

-- Indexes for performance
CREATE INDEX idx_pt_nagad_payment_ref ON payment_transaction(nagad_payment_ref_id);
CREATE INDEX idx_pt_nagad_order_id ON payment_transaction(nagad_order_id);
```

### 7.2 API Log Table

```sql
CREATE TABLE nagad_api_log (
    id SERIAL PRIMARY KEY,
    transaction_id INTEGER REFERENCES payment_transaction(id),
    provider_id INTEGER REFERENCES payment_provider(id),
    endpoint VARCHAR(256),
    method VARCHAR(10),
    request_data TEXT,
    response_data TEXT,
    response_code INTEGER,
    duration_ms INTEGER,
    status VARCHAR(20),
    error_message TEXT,
    create_date TIMESTAMP DEFAULT NOW(),
    write_date TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_nal_transaction ON nagad_api_log(transaction_id);
CREATE INDEX idx_nal_create_date ON nagad_api_log(create_date);
```

---

## 8. API Documentation

### 8.1 Initialize Payment Request

```json
POST /payment/nagad/initialize
Content-Type: application/json

{
    "provider_id": 5,
    "amount": 1500.00,
    "reference": "SO-12345-1705123456789",
    "nagad_number": "+8801712345678",
    "partner_id": 42
}
```

### 8.2 Initialize Payment Response

```json
{
    "success": true,
    "redirect_url": "https://api.mynagad.com/payment/...",
    "payment_ref_id": "MDAxMjM0NTY3ODkw..."
}
```

### 8.3 Callback Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `payment_ref_id` | string | Nagad payment reference |
| `status` | string | Payment status |
| `order_id` | string | Merchant order ID |
| `amount` | string | Payment amount |

---

## 9. Security Implementation

### 9.1 Key Management

- Private keys stored encrypted in database
- Keys accessible only to `base.group_system`
- Automatic key rotation support
- Audit logging for key access

### 9.2 Data Protection

- All sensitive data encrypted at rest
- TLS 1.2+ for all API communications
- Request signature verification
- IP whitelisting for webhooks

### 9.3 Compliance

- PCI DSS guidelines followed
- No card data storage
- Secure credential handling
- Comprehensive audit trails

---

## 10. Testing Requirements

### 10.1 Unit Test Coverage

| Component | Target Coverage |
|-----------|----------------|
| Crypto Service | > 95% |
| Checkout Service | > 90% |
| Verification Service | > 90% |
| Controllers | > 85% |
| Models | > 85% |

### 10.2 Test Scenarios

```python
# Example test cases
class TestNagadPayment(NagadTestCommon):

    def test_initialize_payment_success(self):
        """Test successful payment initialization."""
        pass

    def test_initialize_payment_invalid_merchant(self):
        """Test initialization with invalid merchant."""
        pass

    def test_complete_payment_success(self):
        """Test successful payment completion."""
        pass

    def test_verify_payment_success(self):
        """Test payment verification."""
        pass

    def test_refund_full_amount(self):
        """Test full refund processing."""
        pass

    def test_callback_valid_signature(self):
        """Test callback with valid signature."""
        pass

    def test_callback_invalid_signature(self):
        """Test callback with invalid signature."""
        pass
```

---

## 11. Risk & Mitigation

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| API downtime | High | Medium | Retry logic, fallback UI |
| Encryption failure | High | Low | Key validation, error handling |
| Callback failure | High | Medium | Webhook queue, manual verify |
| Fraudulent transactions | High | Medium | Amount validation, monitoring |
| Key compromise | Critical | Low | Key rotation, access controls |

---

## 12. Dependencies

### 12.1 Internal Dependencies

| Dependency | Module | Purpose |
|------------|--------|---------|
| Payment Provider | `payment` | Base payment functionality |
| Sale Order | `sale` | Order integration |
| Smart Dairy Payment | `smart_dairy_payment` | Base payment module |

### 12.2 External Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| `pycryptodome` | >= 3.18 | RSA encryption |
| `requests` | >= 2.28 | HTTP client |

---

## 13. Sign-off Checklist

### 13.1 Functional Requirements

- [ ] Nagad payment initialization works
- [ ] Payment completion redirects correctly
- [ ] Callback handling processes successfully
- [ ] Payment verification accurate
- [ ] Refund processing functional
- [ ] Error handling comprehensive

### 13.2 Technical Requirements

- [ ] RSA encryption working correctly
- [ ] Signature generation/verification working
- [ ] API logging comprehensive
- [ ] Database migrations applied
- [ ] Unit tests passing (>80% coverage)
- [ ] Integration tests passing

### 13.3 UI/UX Requirements

- [ ] Payment form user-friendly
- [ ] Mobile responsive design
- [ ] Loading states clear
- [ ] Error messages helpful
- [ ] Success feedback clear

### 13.4 Security Requirements

- [ ] Keys stored securely
- [ ] No sensitive data logged
- [ ] Callback signature verified
- [ ] Access controls in place

---

**Document End**

*Milestone 72: Nagad Payment Integration*
*Days 461-470 | Phase 8: Operations - Payment & Logistics*
*Smart Dairy Digital Smart Portal + ERP System*
